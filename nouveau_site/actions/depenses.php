<?php

require __DIR__ . '/../app/bootstrap.php';

$auth->requireAuth();
$auth->requireWrite();
require_post();
verify_csrf();

try {
    $action = (string) ($_POST['action'] ?? '');
    $username = $auth->username();

    if ($action === 'create') {
        $user = $db->fetch('SELECT numero_commande FROM user WHERE User_Name = ?', [$username]);
        $activityNo = (int) ($user['numero_commande'] ?? 0);
        if ($activityNo <= 0) {
            throw new RuntimeException('Aucune activite de vente courante pour rattacher la depense.');
        }

        $motif = trim((string) ($_POST['motif'] ?? ''));
        $royal = (float) ($_POST['depense'] ?? 0);
        $point = (float) ($_POST['depense_aparafa'] ?? 0);
        $tsinjo = (float) ($_POST['depense_tsinjo'] ?? 0);
        if ($royal == 0.0 && $point == 0.0 && $tsinjo == 0.0) {
            throw new InvalidArgumentException('Montant de depense obligatoire.');
        }

        if ($royal != 0.0) {
            $db->execute(
                'INSERT INTO depense(montant, motif, activity_no, user_depense) VALUES (?, ?, ?, ?)',
                [$royal, $motif, $activityNo, $username]
            );
        }
        if ($point != 0.0) {
            $db->execute(
                'INSERT INTO depense(depense_aparafa, motif, activity_no, user_depense) VALUES (?, ?, ?, ?)',
                [$point, $motif, $activityNo, $username]
            );
        }
        if ($tsinjo != 0.0) {
            $db->execute(
                'INSERT INTO depense(depense_tsinjo, motif, activity_no, user_depense) VALUES (?, ?, ?, ?)',
                [$tsinjo, $motif, $activityNo, $username]
            );
        }
        flash('success', 'Depense ajoutee sur l activite #' . $activityNo . '.');
    } elseif ($action === 'update') {
        $id = (int) ($_POST['id_depense'] ?? 0);
        if ($id <= 0) {
            throw new InvalidArgumentException('Depense introuvable.');
        }
        $db->execute(
            'UPDATE depense SET montant = ?, depense_aparafa = ?, motif = ?, user_depense = ? WHERE id_depense = ?',
            [
                (float) ($_POST['depense_royal'] ?? 0),
                (float) ($_POST['depense_aparafa'] ?? 0),
                trim((string) ($_POST['motif'] ?? '')),
                $username,
                $id,
            ]
        );
        flash('success', 'Depense modifiee.');
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id_depense'] ?? 0);
        if ($id <= 0) {
            throw new InvalidArgumentException('Depense introuvable.');
        }
        $db->execute('DELETE FROM depense WHERE id_depense = ?', [$id]);
        flash('success', 'Depense supprimee.');
    } else {
        throw new InvalidArgumentException('Action depense inconnue.');
    }
} catch (Throwable $e) {
    flash('danger', $e->getMessage());
}

redirect('../index.php?page=depenses');
