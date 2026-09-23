<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

function return_description(?string $date): string
{
    $date = $date ?: date('Y-m-d');
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return 'Abandon/Retours du ' . ($dt ? $dt->format('d/m/y') : date('d/m/y'));
}

function return_mysql_date(string $description): string
{
    if (preg_match('/([0-9]{2,4}[\/#-][0-9]{2,4}[\/#-][0-9]{2,4})/', $description, $matches) !== 1) {
        throw new InvalidArgumentException('Date retour introuvable.');
    }

    $date = str_replace(['#', '/'], '-', $matches[1]);
    $date = preg_replace('/\s+/', '', $date);
    $dt = DateTime::createFromFormat('d-m-y', $date) ?: DateTime::createFromFormat('d-m-Y', $date);
    if (!$dt) {
        throw new InvalidArgumentException('Date retour invalide.');
    }

    return $dt->format('Y-m-d');
}

try {
    $action = (string) ($_POST['action'] ?? '');
    $username = $auth->username();

    if ($action === 'start') {
        $point = trim((string) ($_POST['point_de_vente'] ?? ''));
        if ($point === '') {
            throw new InvalidArgumentException('Point de vente obligatoire.');
        }

        $description = return_description((string) ($_POST['date_retour'] ?? ''));

        $db->transaction(function () use ($db, $point, $description, $username): void {
            $memoNext = (int) $db->scalar('SELECT COALESCE(valeur_memo, 0) + 1 FROM memo WHERE id_memo = 2');
            $mvtNext = (int) $db->scalar("SELECT COALESCE(MAX(numero_commande_stock), 0) + 1 FROM mvt WHERE type_de_mvt = 'stock'");
            $prepNext = (int) $db->scalar('SELECT COALESCE(MAX(numero_stock_prep), 0) + 1 FROM stock_prep');
            $next = max($memoNext, $mvtNext, $prepNext);

            $db->execute('UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 2', [$point, $next, $description]);
            $db->execute(
                'UPDATE user SET stock_client_name = ?, stock_numero_commande = ?, stock_description_date = ? WHERE User_Name = ?',
                [$point, $next, $description, $username]
            );
        });

        flash('success', 'Retour demarre.');
    } elseif ($action === 'add_line') {
        $context = $db->fetch('SELECT stock_numero_commande, stock_client_name, stock_description_date FROM user WHERE User_Name = ?', [$username]);
        if (!$context || stripos((string) $context['stock_description_date'], 'Abandon') === false) {
            throw new RuntimeException('Demarre d abord un retour.');
        }

        $productId = (int) ($_POST['id_x'] ?? 0);
        $qty = (float) ($_POST['qt'] ?? 0);
        $price = (float) ($_POST['prix_de_vente'] ?? 0);
        $note = trim((string) ($_POST['note_stock'] ?? ''));
        $product = $repository->product($productId);

        if (!$product) {
            throw new InvalidArgumentException('Article introuvable.');
        }
        if ($qty <= 0) {
            throw new InvalidArgumentException('Quantite retour positive obligatoire.');
        }
        if ($price < 0) {
            throw new InvalidArgumentException('Prix retour invalide.');
        }

        $db->execute(
            'INSERT INTO stock_prep(numero_stock_prep, nom_du_client, description_date, id_x, qt, prix_de_vente, state, note, user_stock_prep)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                (int) $context['stock_numero_commande'],
                (string) $context['stock_client_name'],
                (string) $context['stock_description_date'],
                $productId,
                $qty,
                $price,
                'preparing',
                $note,
                $username,
            ]
        );

        flash('success', 'Ligne retour ajoutee.');
    } elseif ($action === 'remove_line') {
        $db->execute(
            "DELETE FROM stock_prep WHERE id_stock_prep = ? AND description_date LIKE '%Abandon%' AND user_stock_prep = ?",
            [(int) ($_POST['id_stock_prep'] ?? 0), $username]
        );
        flash('success', 'Ligne retour retiree.');
    } elseif ($action === 'cancel') {
        $db->execute("DELETE FROM stock_prep WHERE description_date LIKE '%Abandon%' AND user_stock_prep = ?", [$username]);
        flash('success', 'Preparation retour annulee.');
    } elseif ($action === 'validate_return') {
        $validated = $db->transaction(function () use ($db, $username): array {
            $locked = $db->execute(
                "UPDATE stock_prep
                 SET state = 'validating'
                 WHERE description_date LIKE '%Abandon%' AND user_stock_prep = ?
                   AND (state IS NULL OR state != 'validating')",
                [$username]
            );
            if ($locked <= 0) {
                throw new RuntimeException('Aucune ligne retour a valider.');
            }

            $lines = $db->fetchAll(
                "SELECT sp.*, p.id_x AS product_exists
                 FROM stock_prep sp
                 INNER JOIN produit p ON p.id_x = sp.id_x
                 WHERE sp.description_date LIKE '%Abandon%' AND sp.user_stock_prep = ? AND sp.state = 'validating'
                 ORDER BY sp.id_stock_prep ASC",
                [$username]
            );
            if (!$lines) {
                throw new RuntimeException('Aucune ligne retour a valider.');
            }

            $dateMysql = return_mysql_date((string) $lines[0]['description_date']);
            $lineNo = 0;
            $returnNo = 0;
            foreach ($lines as $line) {
                $lineNo++;
                $productId = (int) $line['id_x'];
                $qty = (float) $line['qt'];
                $price = (float) $line['prix_de_vente'];
                if ($productId <= 0 || $qty <= 0 || $price < 0) {
                    throw new RuntimeException('Ligne retour invalide.');
                }

                $returnNo = (int) $line['numero_stock_prep'];
                $db->execute(
                    'INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire, nom_client_fournisseur, description_date, numero_commande_stock, ref_commande_stock, note, user_mvt, Date_du_Journal_mvt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        'stock',
                        $productId,
                        $qty,
                        $price,
                        (string) $line['nom_du_client'],
                        (string) $line['description_date'],
                        $returnNo,
                        $returnNo . '-' . $lineNo,
                        (string) ($line['note'] ?? ''),
                        (string) $line['user_stock_prep'],
                        $dateMysql,
                    ]
                );
            }

            $db->execute(
                "DELETE FROM stock_prep WHERE description_date LIKE '%Abandon%' AND user_stock_prep = ? AND state = 'validating'",
                [$username]
            );

            return ['count' => count($lines), 'return_no' => $returnNo];
        });

        flash('success', 'Retour valide avec succes. Mouvement #' . $validated['return_no'] . ' - ' . $validated['count'] . ' ligne(s).');
    } else {
        throw new InvalidArgumentException('Action retour inconnue.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=retours');
