<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

try {
    $action = (string) ($_POST['action'] ?? 'create');
    if ($action === 'update') {
        $id = (int) ($_POST['id_x'] ?? 0);
        $name = trim((string) ($_POST['nom_x'] ?? ''));
        $reference = strtoupper(trim((string) ($_POST['reference_x'] ?? '')));
        if ($id <= 0 || $name === '' || $reference === '') {
            throw new InvalidArgumentException('Article, nom et reference sont obligatoires.');
        }

        $db->execute(
            'UPDATE produit SET nom_x = ?, prix_de_vente = ?, reference_x = ?, note_x = ?, user_x = ? WHERE id_x = ?',
            [
                $name,
                (float) ($_POST['prix_de_vente'] ?? 0),
                $reference,
                trim((string) ($_POST['note_x'] ?? '')),
                $auth->username(),
                $id,
            ]
        );
        flash('success', 'Article modifie: #' . $id . '.');
    } else {
        $id = $stockService->addProduct($_POST, $auth->username());
        flash('success', 'Article cree: #' . $id . '.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=articles');
