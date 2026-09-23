<?php
try {
    $rows = $db->fetchAll(
        'SELECT c.id_commande, c.numero_commande, c.nom_du_client, c.description_date, c.qt, c.prix_de_vente, c.prix_client, c.note_commande, p.reference_x, p.nom_x, c.user
         FROM commande c
         LEFT JOIN produit p ON p.id_x = c.id_x
         ORDER BY c.id_commande DESC
         LIMIT 80'
    );
} catch (Throwable $e) {
    $rows = [];
    flash('danger', 'Impossible de charger les commandes: ' . $e->getMessage());
}
?>
<section class="panel panel-accent panel-accent-orange">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Ventes</p>
            <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Commandes temporaires</h2>
        </div>
        <span class="badge badge-readonly">Lecture seule</span>
        <a class="button button-primary" href="<?= e(url('index.php?page=vente')) ?>"><span class="ui-icon ui-icon-cart" aria-hidden="true"></span>Nouvelle vente</a>
    </div>
    <p class="section-note">Commandes actuellement en preparation.</p>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Point</th><th>Article</th><th>QT</th><th>PU</th><th>Utilisateur</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td data-label="No"><?= e($row['numero_commande']) ?><br><span class="operation-badge operation-badge--sale">Vente</span></td>
                    <td data-label="Point"><?= e($row['nom_du_client']) ?></td>
                    <td data-label="Article"><?= e($row['reference_x']) ?><br><strong><?= e($row['nom_x']) ?></strong></td>
                    <td data-label="QT"><?= e($row['qt']) ?></td>
                    <td data-label="PU"><?= money($row['prix_de_vente']) ?></td>
                    <td data-label="Utilisateur"><?= e($row['user']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
