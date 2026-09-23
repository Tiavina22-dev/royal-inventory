<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

$action = (string) ($_POST['action'] ?? '');
$operation = (string) ($_POST['operation'] ?? 'in');
$username = $auth->username();

try {
    if ($action === 'start') {
        $number = $stockService->startOperation(
            $username,
            trim((string) ($_POST['point_de_vente'] ?? '')),
            (string) ($_POST['date_stock'] ?? date('Y-m-d')),
            $operation
        );
        flash('success', 'Contexte stock demarre: #' . $number . '.');
    } elseif ($action === 'add_line') {
        $stockService->addPreparedLine($username, $_POST);
        flash('success', 'Ligne stock ajoutee.');
    } elseif ($action === 'remove_line') {
        $stockService->removePreparedLine($username, (int) ($_POST['id_stock_prep'] ?? 0));
        flash('success', 'Ligne stock retiree.');
    } elseif ($action === 'cancel') {
        $stockService->cancelPrepared($username, $operation);
        flash('success', 'Preparation stock annulee.');
    } elseif ($action === 'validate') {
        $number = $stockService->validatePrepared($username, $operation);
        flash('success', 'Stock valide dans mvt. Mouvement #' . $number . '.');
    } else {
        flash('danger', 'Action stock inconnue.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=stock&operation=' . ($operation === 'out' ? 'out' : 'in'));
