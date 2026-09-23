<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

$action = (string) ($_POST['action'] ?? '');
$username = $auth->username();

try {
    if ($action === 'start') {
        $saleNo = $salesService->startSale(
            $username,
            trim((string) ($_POST['point_de_vente'] ?? '')),
            (string) ($_POST['date_vente'] ?? date('Y-m-d')),
            trim((string) ($_POST['note_general'] ?? ''))
        );
        flash('success', 'Vente demarree: journal #' . $saleNo . '.');
    } elseif ($action === 'add_line') {
        $salesService->addCartLine(
            $username,
            (int) ($_POST['id_x'] ?? 0),
            (float) ($_POST['qt'] ?? 0),
            (float) ($_POST['prix_de_vente'] ?? 0),
            (float) ($_POST['prix_client'] ?? ($_POST['prix_de_vente'] ?? 0)),
            trim((string) ($_POST['note_commande'] ?? ''))
        );
        flash('success', 'Article ajoute au panier.');
    } elseif ($action === 'update_line') {
        $salesService->updateCartLine(
            $username,
            (int) ($_POST['id_commande'] ?? 0),
            (float) ($_POST['qt'] ?? 0),
            (float) ($_POST['prix_de_vente'] ?? 0),
            (float) ($_POST['prix_client'] ?? ($_POST['prix_de_vente'] ?? 0)),
            trim((string) ($_POST['note_commande'] ?? ''))
        );
        flash('success', 'Ligne modifiee.');
    } elseif ($action === 'remove_line') {
        $salesService->removeCartLine($username, (int) ($_POST['id_commande'] ?? 0));
        flash('success', 'Ligne retiree du panier.');
    } elseif ($action === 'cancel') {
        $salesService->cancelSale($username);
        flash('success', 'Vente en cours annulee.');
    } elseif ($action === 'validate') {
        $saleNo = $salesService->validateSale($username, $_POST);
        flash('success', 'Vente validee dans mvt et recap_vente. Journal #' . $saleNo . '.');
        redirect('../index.php?page=recu&sale=' . $saleNo);
    } else {
        flash('warning', 'Action vente inconnue.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=vente');

