<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

try {
    $action = (string) ($_POST['action'] ?? '');
    if ($action !== 'grant_permission') {
        throw new InvalidArgumentException('Action utilisateur non connectee.');
    }

    $id = (int) ($_POST['id_user'] ?? 0);
    if ($id <= 0) {
        throw new InvalidArgumentException('Utilisateur introuvable.');
    }

    $db->execute("UPDATE user SET Permission = 'Y' WHERE Id_User = ?", [$id]);
    flash('success', 'Permission accordee.');
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=utilisateurs');
