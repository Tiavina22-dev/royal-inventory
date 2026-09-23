<?php
$activity = trim((string) ($_GET['activity'] ?? ''));
try {
    $currentUser = $db->fetch('SELECT numero_commande FROM user WHERE User_Name = ?', [$auth->username()]);
    $currentActivity = (int) ($currentUser['numero_commande'] ?? 0);
    if ($activity !== '') {
        $rows = $db->fetchAll('SELECT * FROM depense WHERE activity_no = ? ORDER BY id_depense DESC LIMIT 100', [(int) $activity]);
    } else {
        $rows = $db->fetchAll('SELECT * FROM depense ORDER BY id_depense DESC LIMIT 100');
    }
} catch (Throwable $e) {
    $currentActivity = 0;
    $rows = [];
    flash('danger', 'Impossible de charger les depenses: ' . $e->getMessage());
}
?>
<section class="panel panel-accent panel-accent-orange notice-panel">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Depenses</p>
            <h2><span class="ui-icon ui-icon-wallet" aria-hidden="true"></span>Suivi des depenses</h2>
        </div>
        <span class="badge badge-success">Flux historique</span>
    </div>
    <form method="post" action="<?= e(url('actions/depenses.php')) ?>" class="form-grid">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="create">
        <label>Activite courante <input value="<?= e($currentActivity > 0 ? '#' . $currentActivity : 'Aucune activite') ?>" disabled></label>
        <label>Royal <input type="number" step="any" name="depense" value="0"></label>
        <label>Point de vente <input type="number" step="any" name="depense_aparafa" value="0"></label>
        <label>Tsinjo <input type="number" step="any" name="depense_tsinjo" value="0"></label>
        <label class="full">Motif <input name="motif" placeholder="Motif"></label>
        <button class="button button-primary"><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter la depense</button>
    </form>
</section>
<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Liste</p>
            <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Dernieres depenses</h2>
        </div>
        <form class="inline-search" method="get">
            <input type="hidden" name="page" value="depenses">
            <input name="activity" value="<?= e($activity) ?>" placeholder="No activite">
            <button class="button button-soft"><span class="ui-icon ui-icon-filter" aria-hidden="true"></span>Filtrer</button>
        </form>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>ID</th><th>Activite</th><th>Motif</th><th>Royal</th><th>Point vente</th><th>Tsinjo</th><th>Utilisateur</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td data-label="ID"><?= e($row['id_depense']) ?></td>
                    <td data-label="Activite"><?= e($row['activity_no']) ?></td>
                    <td data-label="Motif"><?= e($row['motif']) ?></td>
                    <td data-label="Royal"><?= money($row['montant'] ?? 0) ?></td>
                    <td data-label="Point vente"><?= money($row['depense_aparafa'] ?? 0) ?></td>
                    <td data-label="Tsinjo"><?= money($row['depense_tsinjo'] ?? 0) ?></td>
                    <td data-label="Utilisateur"><?= e($row['user_depense'] ?? '') ?></td>
                    <td data-label="Action">
                        <details class="inline-details">
                            <summary class="button button-soft"><span class="ui-icon ui-icon-pencil" aria-hidden="true"></span>Modifier</summary>
                            <form method="post" action="<?= e(url('actions/depenses.php')) ?>" class="form-grid compact-form">
                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id_depense" value="<?= e($row['id_depense']) ?>">
                                <label>Royal <input type="number" step="any" name="depense_royal" value="<?= e($row['montant'] ?? 0) ?>"></label>
                                <label>Point vente <input type="number" step="any" name="depense_aparafa" value="<?= e($row['depense_aparafa'] ?? 0) ?>"></label>
                                <label class="full">Motif <input name="motif" value="<?= e($row['motif']) ?>"></label>
                                <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Enregistrer</button>
                            </form>
                            <form method="post" action="<?= e(url('actions/depenses.php')) ?>" onsubmit="return confirm('Supprimer cette depense ?')">
                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_depense" value="<?= e($row['id_depense']) ?>">
                                <button class="button button-danger"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span>Supprimer</button>
                            </form>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
