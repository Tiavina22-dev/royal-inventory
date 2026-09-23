<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

function control_description(?string $date): string
{
    $date = $date ?: date('Y-m-d');
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return 'Verifier/Rectifier le ' . ($dt ? $dt->format('d/m/y') : date('d/m/y'));
}

function control_mysql_date(string $description): string
{
    if (preg_match('/([0-9]{2,4}[\/#-][0-9]{2,4}[\/#-][0-9]{2,4})/', $description, $matches) !== 1) {
        throw new InvalidArgumentException('Date controle introuvable.');
    }
    $date = str_replace(['#', '/'], '-', $matches[1]);
    $date = preg_replace('/\s+/', '', $date);
    $dt = DateTime::createFromFormat('d-m-y', $date) ?: DateTime::createFromFormat('d-m-Y', $date);
    if (!$dt) {
        throw new InvalidArgumentException('Date controle invalide.');
    }
    return $dt->format('Y-m-d');
}

function control_inventory_description(string $mysqlDate): string
{
    $dt = DateTime::createFromFormat('Y-m-d', $mysqlDate);
    return 'Inventaire/Ajout du ' . ($dt ? $dt->format('d/m/y') : date('d/m/y'));
}

function control_current_price(array $product, string $point): float
{
    if ($point === 'Ambato_Tantely') {
        return (float) ($product['pu_ambato_tantely'] ?? 0);
    }
    if ($point === 'Soalazaina') {
        return (float) ($product['pu_soalazaina'] ?? 0);
    }
    if ($point === 'Amparafa') {
        return (float) ($product['pu_aparafa'] ?? 0);
    }
    return (float) ($product['prix_de_vente'] ?? 0);
}

function control_theoretical_inventory(Database $db, int $productId, string $point, string $inventoryDate): float
{
    $vente = (float) $db->scalar(
        "SELECT COALESCE(SUM(qt), 0) FROM mvt
         WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND Date_du_Journal_mvt <= ?",
        [$productId, $point, $inventoryDate]
    );
    $stock = (float) $db->scalar(
        "SELECT COALESCE(SUM(qt), 0) FROM mvt
         WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?
           AND status NOT LIKE 'General_Inventory' AND Date_du_Journal_mvt <= ?",
        [$productId, $point, $inventoryDate]
    );
    $generalInventory = (float) $db->scalar(
        "SELECT COALESCE(SUM(prix_aparafa), 0) FROM mvt
         WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?
           AND status LIKE 'General_Inventory'",
        [$productId, $point]
    );
    return $vente + $stock + $generalInventory;
}

try {
    $action = (string) ($_POST['action'] ?? '');
    $username = $auth->username();

    if ($action === 'start') {
        $point = trim((string) ($_POST['point_de_vente'] ?? ''));
        if ($point === '') {
            throw new InvalidArgumentException('Point de vente obligatoire.');
        }
        $description = control_description((string) ($_POST['date_controle'] ?? ''));

        $db->transaction(function () use ($db, $point, $description, $username): void {
            $memoNext = (int) $db->scalar('SELECT COALESCE(valeur_memo, 0) + 1 FROM memo WHERE id_memo = 2');
            $mvtNext = (int) $db->scalar("SELECT COALESCE(MAX(numero_commande_stock), 0) + 1 FROM mvt WHERE type_de_mvt = 'stock'");
            $prepNext = (int) $db->scalar('SELECT COALESCE(MAX(numero_stock_prep), 0) + 1 FROM stock_prep');
            $next = max($memoNext, $mvtNext, $prepNext);

            $db->execute('UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 2', [$point, $next, $description]);
            $db->execute('UPDATE user SET numero_commande = ?, point_de_vente = ?, description_date = ? WHERE User_Name = ?', [$next, $point, $description, $username]);
        });
        flash('success', 'Controle demarre.');
    } elseif ($action === 'add_line') {
        $context = $db->fetch('SELECT numero_commande, point_de_vente, description_date FROM user WHERE User_Name = ?', [$username]);
        if (!$context || stripos((string) $context['description_date'], 'Rectifier') === false) {
            throw new RuntimeException('Demarre d abord un controle.');
        }
        $productId = (int) ($_POST['id_x'] ?? 0);
        $realQty = (float) ($_POST['qt_reelle'] ?? 0);
        $price = (float) ($_POST['prix_de_vente'] ?? 0);
        $clientPrice = (float) ($_POST['prix_client'] ?? 0);
        $note = trim((string) ($_POST['note_stock'] ?? ''));
        if ($productId <= 0) {
            throw new InvalidArgumentException('Article obligatoire.');
        }

        $db->execute(
            'INSERT INTO stock_prep(numero_stock_prep, nom_du_client, description_date, id_x, qt, prix_de_vente, prix_client, state, note, user_stock_prep)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                (int) $context['numero_commande'],
                (string) $context['point_de_vente'],
                (string) $context['description_date'],
                $productId,
                $realQty,
                $price,
                $clientPrice,
                'preparing',
                $note,
                $username,
            ]
        );
        flash('success', 'Ligne de controle ajoutee.');
    } elseif ($action === 'remove_line') {
        $db->execute(
            "DELETE FROM stock_prep WHERE id_stock_prep = ? AND description_date LIKE '%Rectifier%' AND user_stock_prep = ?",
            [(int) ($_POST['id_stock_prep'] ?? 0), $username]
        );
        flash('success', 'Ligne retiree.');
    } elseif ($action === 'cancel') {
        $db->execute("DELETE FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ?", [$username]);
        flash('success', 'Preparation controle annulee.');
    } elseif ($action === 'validate_general_inventory') {
        $context = $db->fetch('SELECT numero_commande, point_de_vente, description_date FROM user WHERE User_Name = ?', [$username]);
        if (!$context || stripos((string) $context['description_date'], 'Rectifier') === false) {
            throw new RuntimeException('Aucun controle en cours a valider.');
        }
        $point = (string) $context['point_de_vente'];
        $number = (int) $context['numero_commande'];
        $sourceDescription = (string) $context['description_date'];
        if ($point === '' || $number <= 0) {
            throw new RuntimeException('Contexte controle incomplet.');
        }
        $knownShop = $db->fetch('SELECT short_name FROM shop WHERE short_name = ?', [$point]);
        if (!$knownShop) {
            throw new RuntimeException('Point de vente controle inconnu.');
        }
        $inventoryDate = control_mysql_date($sourceDescription);
        $inventoryDescription = control_inventory_description($inventoryDate);

        $validatedLines = $db->transaction(function () use ($db, $username, $point, $number, $inventoryDate, $inventoryDescription): int {
            $locked = $db->execute(
                "UPDATE stock_prep
                 SET state = 'validating'
                 WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? AND nom_du_client = ?
                   AND (state IS NULL OR state != 'validating')",
                [$username, $point]
            );
            if ($locked <= 0) {
                throw new RuntimeException('Aucune rectification a valider.');
            }

            $lines = $db->fetchAll(
                "SELECT sp.*, p.nom_x, p.prix_de_vente, p.pu_aparafa, p.pu_ambato_tantely, p.pu_soalazaina,
                        p.note_prix, p.note_prix_amparafa, p.note_prix_tantely, p.note_prix_soalazaina,
                        p.difference_prix, p.difference_prix_amparafa, p.difference_prix_tantely, p.difference_prix_soalazaina
                 FROM stock_prep sp
                 INNER JOIN produit p ON p.id_x = sp.id_x
                 WHERE sp.description_date LIKE '%Rectifier%' AND sp.user_stock_prep = ? AND sp.nom_du_client = ?
                   AND sp.state = 'validating'
                 ORDER BY sp.id_stock_prep ASC",
                [$username, $point]
            );
            if (!$lines) {
                throw new RuntimeException('Aucune rectification a valider.');
            }

            $count = 0;
            foreach ($lines as $line) {
                $productId = (int) $line['id_x'];
                $observed = abs((float) $line['qt']);
                $theoretical = control_theoretical_inventory($db, $productId, $point, $inventoryDate);
                $rectification = $observed - $theoretical;
                $price = (float) $line['prix_de_vente'];
                if ($price == 0.0) {
                    $price = control_current_price($line, $point);
                }
                $clientPrice = (float) ($line['prix_client'] ?? 0);
                $reference = $number . '-' . (int) $line['id_stock_prep'];

                $db->execute(
                    "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_aparafa, prix_unitaire, prix_client,
                            nom_client_fournisseur, description_date, numero_commande_stock, ref_commande_stock,
                            note, user_mvt, status, Date_du_Journal_mvt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        'stock',
                        $productId,
                        $observed,
                        $rectification,
                        $price,
                        $clientPrice,
                        $point,
                        $inventoryDescription,
                        $number,
                        $reference,
                        (string) ($line['note'] ?? ''),
                        (string) $line['user_stock_prep'],
                        'General_Inventory',
                        $inventoryDate,
                    ]
                );

                if ($point === 'Ambato_Tantely') {
                    $db->execute(
                        'UPDATE produit SET pu_ambato_tantely = ?, note_x = ?, user_x = ?, note_prix_tantely = ?, difference_prix_tantely = ? WHERE id_x = ?',
                        [$price, 'latest', (string) $line['user_stock_prep'], (string) ($line['note_prix_tantely'] ?? ''), 0, $productId]
                    );
                } elseif ($point === 'Amparafa') {
                    $db->execute(
                        'UPDATE produit SET pu_aparafa = ?, note_x = ?, user_x = ?, note_prix_amparafa = ?, difference_prix_amparafa = ? WHERE id_x = ?',
                        [$price, 'latest', (string) $line['user_stock_prep'], (string) ($line['note_prix_amparafa'] ?? ''), 0, $productId]
                    );
                } elseif ($point === 'Soalazaina') {
                    $db->execute(
                        'UPDATE produit SET pu_soalazaina = ?, note_x = ?, user_x = ?, note_prix_soalazaina = ?, difference_prix_soalazaina = ? WHERE id_x = ?',
                        [$price, 'latest', (string) $line['user_stock_prep'], (string) ($line['note_prix_soalazaina'] ?? ''), 0, $productId]
                    );
                } elseif (in_array($point, ['Bejofo', 'Ambato_veve_photo', 'Ambaibo_Electronique', 'Ambaibo_Tole'], true)) {
                    $db->execute(
                        'UPDATE produit SET prix_de_vente = ?, note_x = ?, user_x = ?, note_prix = ?, difference_prix = ? WHERE id_x = ?',
                        [$price, 'latest', (string) $line['user_stock_prep'], (string) ($line['note_prix'] ?? ''), 0, $productId]
                    );
                }
                $count++;
            }

            $preparedProductIds = array_map(static fn (array $line): int => (int) $line['id_x'], $lines);
            $productRows = $db->fetchAll(
                "SELECT DISTINCT m.id_x, p.prix_de_vente, p.pu_aparafa, p.pu_ambato_tantely, p.pu_soalazaina
                 FROM mvt m
                 INNER JOIN produit p ON p.id_x = m.id_x
                 WHERE m.nom_client_fournisseur = ? AND m.id_x NOT IN (" . implode(',', array_fill(0, count($preparedProductIds), '?')) . ")",
                array_merge([$point], $preparedProductIds)
            );
            foreach ($productRows as $product) {
                $productId = (int) $product['id_x'];
                $theoretical = control_theoretical_inventory($db, $productId, $point, $inventoryDate);
                $rectification = 0 - $theoretical;
                $price = control_current_price($product, $point);
                $db->execute(
                    "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_aparafa, prix_unitaire, prix_client,
                            nom_client_fournisseur, description_date, numero_commande_stock, ref_commande_stock,
                            note, user_mvt, status, Date_du_Journal_mvt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        'stock',
                        $productId,
                        0,
                        $rectification,
                        $price,
                        0,
                        $point,
                        $inventoryDescription,
                        $number,
                        $number . '-1',
                        'Automatic',
                        $username,
                        'General_Inventory',
                        $inventoryDate,
                    ]
                );
                $count++;
            }

            $db->execute(
                "DELETE FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? AND nom_du_client = ? AND state = 'validating'",
                [$username, $point]
            );
            $db->execute(
                "UPDATE mvt SET status = 'OFF'
                 WHERE nom_client_fournisseur = ? AND status != 'General_Inventory' AND Date_du_Journal_mvt <= ?",
                [$point, $inventoryDate]
            );

            return $count;
        });

        flash('success', 'Controle valide avec succes. ' . $validatedLines . ' mouvement(s) General_Inventory cree(s).');
    } else {
        throw new InvalidArgumentException('Action controle inconnue.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=controle');
