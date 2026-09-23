<?php
$q = trim((string) ($_GET['q'] ?? ''));
$type = trim((string) ($_GET['type'] ?? ''));
$shop = trim((string) ($_GET['shop'] ?? ''));
$from = trim((string) ($_GET['from'] ?? ''));
$to = trim((string) ($_GET['to'] ?? ''));
try {
    $shops = $repository->shops();
    $where = [];
    $params = [];
    if ($q !== '') {
        $where[] = '(p.reference_x LIKE ? OR p.nom_x LIKE ? OR CAST(m.id_x AS CHAR) LIKE ? OR CAST(m.numero_commande_stock AS CHAR) LIKE ?)';
        $like = '%' . $q . '%';
        array_push($params, $like, $like, $like, $like);
    }
    if ($type !== '') {
        $where[] = 'm.type_de_mvt = ?';
        $params[] = $type;
    }
    if ($shop !== '') {
        $where[] = 'm.nom_client_fournisseur = ?';
        $params[] = $shop;
    }
    if ($from !== '') {
        $where[] = 'm.Date_du_Journal_mvt >= ?';
        $params[] = $from;
    }
    if ($to !== '') {
        $where[] = 'm.Date_du_Journal_mvt <= ?';
        $params[] = $to;
    }
    $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $rows = $db->fetchAll(
        "SELECT m.id_mvt, m.type_de_mvt, m.nom_client_fournisseur, m.description_date, m.numero_commande_stock,
                m.qt, m.prix_unitaire, m.Date_du_Journal_mvt, p.reference_x, p.nom_x
         FROM mvt m
         LEFT JOIN produit p ON p.id_x = m.id_x
         {$sqlWhere}
         ORDER BY m.id_mvt DESC
         LIMIT 100",
        $params
    );
} catch (Throwable $e) {
    $shops = [];
    $rows = [];
    flash('danger', 'Impossible de charger les mouvements: ' . $e->getMessage());
}
?>
<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Historique</p>
            <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Derniers mouvements</h2>
        </div>
        <span class="badge badge-readonly">Lecture seule</span>
    </div>
    <form class="form-grid" method="get">
        <input type="hidden" name="page" value="mouvements">
        <label>Recherche <input name="q" value="<?= e($q) ?>" placeholder="Reference, article, ID ou no"></label>
        <label>Type
            <select name="type">
                <option value="">Tous</option>
                <?php foreach (['stock', 'vente', 'facture'] as $option): ?>
                    <option value="<?= e($option) ?>" <?= $type === $option ? 'selected' : '' ?>><?= e(ucfirst($option)) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Point
            <select name="shop">
                <option value="">Tous</option>
                <?php foreach ($shops as $row): ?>
                    <option value="<?= e($row['short_name']) ?>" <?= $shop === $row['short_name'] ? 'selected' : '' ?>><?= e($row['long_name'] ?: $row['short_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Du <input type="date" name="from" value="<?= e($from) ?>"></label>
        <label>Au <input type="date" name="to" value="<?= e($to) ?>"></label>
        <button class="button button-soft"><span class="ui-icon ui-icon-filter" aria-hidden="true"></span>Filtrer</button>
    </form>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>ID</th><th>Type</th><th>Reference</th><th>Produit</th><th>Point</th><th>QT</th><th>PU</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <?php
                $badge = operation_badge($row);
                $qty = (float) ($row['qt'] ?? 0);
                $qtyClass = $qty < 0 ? 'text-danger' : ($qty > 0 ? 'text-success' : '');
                $qtyText = ($qty > 0 ? '+' : '') . (string) $row['qt'];
                ?>
                <tr>
                    <td data-label="ID"><?= e($row['id_mvt']) ?></td>
                    <td data-label="Type"><span class="operation-badge <?= e($badge[1]) ?>"><?= e($badge[0]) ?></span></td>
                    <td data-label="Reference"><?= e($row['reference_x']) ?></td>
                    <td data-label="Produit"><?= e($row['nom_x']) ?></td>
                    <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="QT" class="<?= e($qtyClass) ?>"><?= e($qtyText) ?></td>
                    <td data-label="PU"><?= money($row['prix_unitaire']) ?></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal_mvt']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
