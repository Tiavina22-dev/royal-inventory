<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

function invoice_description(?string $date): string
{
    $date = $date ?: date('Y-m-d');
    $dt = DateTime::createFromFormat('Y-m-d', $date);
    return 'Facture du ' . ($dt ? $dt->format('d/m/y') : date('d/m/y'));
}

function invoice_mysql_date(string $description): string
{
    if (preg_match('/([0-9]{2,4}[\/#-][0-9]{2,4}[\/#-][0-9]{2,4})/', $description, $matches) !== 1) {
        throw new InvalidArgumentException('Date facture introuvable.');
    }
    $date = str_replace(['#', '/'], '-', $matches[1]);
    $date = preg_replace('/\s+/', '', $date);
    $dt = DateTime::createFromFormat('d-m-y', $date) ?: DateTime::createFromFormat('d-m-Y', $date);
    if (!$dt) {
        throw new InvalidArgumentException('Date facture invalide.');
    }
    return $dt->format('Y-m-d');
}

try {
    $action = (string) ($_POST['action'] ?? '');
    $username = $auth->username();

    if ($action === 'start') {
        $supplier = trim((string) ($_POST['fournisseur'] ?? ''));
        if ($supplier === '') {
            throw new InvalidArgumentException('Fournisseur obligatoire.');
        }
        $description = invoice_description((string) ($_POST['date_facture'] ?? ''));

        $db->transaction(function () use ($db, $supplier, $description, $username): void {
            $memoNext = (int) $db->scalar('SELECT COALESCE(valeur_memo, 0) + 1 FROM memo WHERE id_memo = 2');
            $mvtNext = (int) $db->scalar("SELECT COALESCE(MAX(numero_commande_stock), 0) + 1 FROM mvt WHERE type_de_mvt = 'stock'");
            $prepNext = (int) $db->scalar('SELECT COALESCE(MAX(numero_stock_prep), 0) + 1 FROM stock_prep');
            $next = max($memoNext, $mvtNext, $prepNext);

            $db->execute('UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 2', [$supplier, $next, $description]);
            $db->execute('UPDATE user SET stock_client_name = ?, stock_numero_commande = ?, stock_description_date = ? WHERE User_Name = ?', [$supplier, $next, $description, $username]);
        });
        flash('success', 'Facture demarree.');
    } elseif ($action === 'add_line') {
        $context = $db->fetch('SELECT stock_numero_commande, stock_client_name, stock_description_date FROM user WHERE User_Name = ?', [$username]);
        if (!$context || stripos((string) $context['stock_description_date'], 'Facture') === false) {
            throw new RuntimeException('Demarre d abord une facture.');
        }
        $productId = (int) ($_POST['id_x'] ?? 0);
        $qty = (float) ($_POST['qt'] ?? 0);
        $supplierPrice = (float) ($_POST['prix_fournisseur'] ?? 0);
        $note = trim((string) ($_POST['note_stock'] ?? ''));
        if ($productId <= 0 || $qty <= 0) {
            throw new InvalidArgumentException('Article et quantite positive obligatoires.');
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
                $supplierPrice,
                'preparing',
                $note,
                $username,
            ]
        );
        flash('success', 'Ligne facture ajoutee.');
    } elseif ($action === 'remove_line') {
        $db->execute(
            "DELETE FROM stock_prep WHERE id_stock_prep = ? AND description_date LIKE '%Facture%' AND user_stock_prep = ?",
            [(int) ($_POST['id_stock_prep'] ?? 0), $username]
        );
        flash('success', 'Ligne facture retiree.');
    } elseif ($action === 'cancel') {
        $db->execute("DELETE FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep = ?", [$username]);
        flash('success', 'Preparation facture annulee.');
    } elseif ($action === 'validate_invoice') {
        $context = $db->fetch('SELECT stock_numero_commande, stock_client_name, stock_description_date FROM user WHERE User_Name = ?', [$username]);
        if (!$context || stripos((string) $context['stock_description_date'], 'Facture') === false) {
            throw new RuntimeException('Aucune facture en preparation a valider.');
        }
        $supplier = trim((string) $context['stock_client_name']);
        $invoiceNo = (int) $context['stock_numero_commande'];
        $description = (string) $context['stock_description_date'];
        if ($supplier === '' || $invoiceNo <= 0) {
            throw new RuntimeException('Contexte facture incomplet.');
        }
        $dateMysql = invoice_mysql_date($description);

        $validatedLines = $db->transaction(function () use ($db, $username, $supplier, $dateMysql): array {
            $locked = $db->execute(
                "UPDATE stock_prep
                 SET state = 'validating'
                 WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? AND nom_du_client = ?
                   AND (state IS NULL OR state != 'validating')",
                [$username, $supplier]
            );
            if ($locked <= 0) {
                throw new RuntimeException('Aucune ligne facture a valider.');
            }

            $lines = $db->fetchAll(
                "SELECT sp.*, p.prix_fournisseur
                 FROM stock_prep sp
                 INNER JOIN produit p ON p.id_x = sp.id_x
                 WHERE sp.description_date LIKE '%Facture%' AND sp.user_stock_prep = ? AND sp.nom_du_client = ?
                   AND sp.state = 'validating'
                 ORDER BY sp.id_stock_prep ASC",
                [$username, $supplier]
            );
            if (!$lines) {
                throw new RuntimeException('Aucune ligne facture a valider.');
            }

            $lineNo = 0;
            $invoiceNo = 0;
            foreach ($lines as $line) {
                $lineNo++;
                $productId = (int) $line['id_x'];
                $qty = (float) $line['qt'];
                $supplierPrice = (float) $line['prix_de_vente'];
                if ($productId <= 0 || $qty <= 0) {
                    throw new RuntimeException('Article ou quantite facture invalide.');
                }
                $invoiceNo = (int) $line['numero_stock_prep'];
                $oldSupplierPrice = (float) $line['prix_fournisseur'];

                if (($supplierPrice - $oldSupplierPrice) != 0.0) {
                    $db->execute('UPDATE produit SET prix_fournisseur = ? WHERE id_x = ?', [$supplierPrice, $productId]);
                    $before = ' | PU Fournisseur : ' . ($oldSupplierPrice + 0) . '</br>';
                    $after = " | PU Fournisseur : <span class ='text-danger'><b>" . ($supplierPrice + 0) . '</b></span></br>';
                    $db->execute(
                        'INSERT INTO history(after_change, before_change, details, responsable, type) VALUES (?, ?, ?, ?, ?)',
                        [$after, $before, (string) $productId, $username, 'Prix']
                    );
                }

                $db->execute(
                    'INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire, nom_client_fournisseur, description_date, numero_commande_stock, ref_commande_stock, note, user_mvt, Date_du_Journal_mvt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        'facture',
                        $productId,
                        $qty,
                        $supplierPrice,
                        (string) $line['nom_du_client'],
                        (string) $line['description_date'],
                        $invoiceNo,
                        $invoiceNo . '-' . $lineNo,
                        (string) ($line['note'] ?? ''),
                        (string) $line['user_stock_prep'],
                        $dateMysql,
                    ]
                );
            }

            $db->execute(
                "DELETE FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? AND nom_du_client = ? AND state = 'validating'",
                [$username, $supplier]
            );

            return ['count' => count($lines), 'invoice' => $invoiceNo];
        });

        flash('success', 'Facture validee avec succes. ' . $validatedLines['count'] . ' ligne(s) enregistree(s).');
    } else {
        throw new InvalidArgumentException('Action facture inconnue.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=factures');
