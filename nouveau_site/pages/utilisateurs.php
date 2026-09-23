<?php
try {
    $rows = $db->fetchAll('SELECT Id_User, Full_Name, User_Name, Departement, Permission, Note FROM user ORDER BY Full_Name LIMIT 100');
} catch (Throwable $e) {
    $rows = [];
    flash('danger', 'Impossible de charger utilisateurs: ' . $e->getMessage());
}
?>
<section class="panel panel-accent panel-accent-blue notice-panel">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Administration</p>
            <h2><span class="ui-icon ui-icon-users" aria-hidden="true"></span>Utilisateurs et permissions</h2>
        </div>
        <span class="badge badge-readonly">Lecture seule</span>
    </div>
</section>
<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Comptes</p>
            <h2><span class="ui-icon ui-icon-users" aria-hidden="true"></span>Liste des utilisateurs</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>ID</th><th>Nom</th><th>Utilisateur</th><th>Departement</th><th>Permission</th><th>Note</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td data-label="ID"><?= e($row['Id_User']) ?></td>
                    <td data-label="Nom"><?= e($row['Full_Name']) ?></td>
                    <td data-label="Utilisateur"><?= e($row['User_Name']) ?></td>
                    <td data-label="Departement"><?= e($row['Departement']) ?></td>
                    <td data-label="Permission"><span class="pill <?= $row['Permission'] === 'Y' ? 'badge-stock-ok' : 'badge-stock-empty' ?>"><?= $row['Permission'] === 'Y' ? 'Autorise' : 'En attente' ?></span></td>
                    <td data-label="Note"><?= e($row['Note']) ?></td>
                    <td data-label="Action">
                        <?php if ($row['Permission'] !== 'Y'): ?>
                            <form method="post" action="<?= e(url('actions/utilisateurs.php')) ?>" onsubmit="return confirm('Accorder la permission a cet utilisateur ?')">
                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="grant_permission">
                                <input type="hidden" name="id_user" value="<?= e($row['Id_User']) ?>">
                                <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Autoriser</button>
                            </form>
                        <?php else: ?>
                            <span class="badge badge-readonly">OK</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
