<?php
try {
    $counts = $repository->dashboardCounts();
    $recent = $repository->recentMovements();
    $recentProducts = $repository->products('', 6);
    $recentSales = $salesService->recentSales(8);
    $salesAmount = (float) $db->scalar('SELECT COALESCE(SUM(Montant), 0) FROM recap_vente');
    $stockRows = $stockService->currentStock('', '', 200);
    $stockAvailable = 0.0;
    $stockWatch = [];
    foreach ($stockRows as $stockRow) {
        $remaining = (float) ($stockRow['restant'] ?? 0);
        $stockAvailable += $remaining;
        if ($remaining <= 0 && count($stockWatch) < 8) {
            $stockWatch[] = $stockRow;
        }
    }
} catch (Throwable $e) {
    $counts = ['products' => 0, 'sales' => 0, 'stock_moves' => 0, 'invoices' => 0, 'shops' => 0];
    $recent = $recentProducts = $recentSales = [];
    $salesAmount = $stockAvailable = 0.0;
    $stockWatch = [];
}
?>
<section class="module-hero module-hero-dashboard">
    <div>
        <p class="panel-kicker">Bienvenue</p>
        <h2>Bonjour, <?= e($auth->user()['full_name'] ?: $auth->username()) ?></h2>
        <p>Acces rapide aux operations courantes de Royal Inventory.</p>
    </div>
    <div class="quick-actions">
        <a class="button button-success" href="<?= e(url('index.php?page=vente')) ?>"><span class="ui-icon ui-icon-cart" aria-hidden="true"></span>Nouvelle vente</a>
        <a class="button button-warning" href="<?= e(url('index.php?page=stock&operation=in')) ?>"><span class="ui-icon ui-icon-download" aria-hidden="true"></span>Entree stock</a>
        <a class="button button-info" href="<?= e(url('index.php?page=articles')) ?>"><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter article</a>
        <a class="button button-info" href="<?= e(url('index.php?page=factures')) ?>"><span class="ui-icon ui-icon-receipt" aria-hidden="true"></span>Facture</a>
        <a class="button button-return" href="<?= e(url('index.php?page=retours')) ?>"><span class="ui-icon ui-icon-return" aria-hidden="true"></span>Retour</a>
        <a class="button button-warning" href="<?= e(url('index.php?page=depenses')) ?>"><span class="ui-icon ui-icon-wallet" aria-hidden="true"></span>Depense</a>
    </div>
</section>

<section class="metric-grid">
    <div class="metric metric-products"><span class="metric-icon" aria-hidden="true"></span><span>Articles</span><strong><?= money($counts['products']) ?></strong><small>Catalogue</small></div>
    <div class="metric metric-sales"><span class="metric-icon" aria-hidden="true"></span><span>Journaux vente</span><strong><?= money($counts['sales']) ?></strong><small>Ventes</small></div>
    <div class="metric metric-amount"><span class="metric-icon" aria-hidden="true"></span><span>Montant ventes</span><strong><?= money($salesAmount) ?></strong><small>Versements</small></div>
    <div class="metric metric-stock <?= $stockAvailable < 0 ? 'metric-negative' : '' ?>"><span class="metric-icon" aria-hidden="true"></span><span>Stock disponible</span><strong><?= money($stockAvailable) ?></strong><small>Quantite calculee</small></div>
    <div class="metric metric-invoices"><span class="metric-icon" aria-hidden="true"></span><span>Factures</span><strong><?= money($counts['invoices']) ?></strong><small>Documents</small></div>
    <div class="metric metric-shops"><span class="metric-icon" aria-hidden="true"></span><span>Points de vente</span><strong><?= money($counts['shops']) ?></strong><small>Boutiques</small></div>
</section>

<div class="dashboard-grid">
    <section class="panel panel-accent panel-accent-teal">
        <div class="panel-head">
            <div>
                <p class="panel-kicker">Activite</p>
                <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Derniers mouvements</h2>
            </div>
            <a class="button button-soft" href="<?= e(url('index.php?page=mouvements')) ?>"><span class="ui-icon ui-icon-eye" aria-hidden="true"></span>Voir mouvements</a>
        </div>
        <?php if (!$recent): ?>
            <p class="empty">Aucune donnee chargee.</p>
        <?php else: ?>
            <div class="table-wrap responsive-table">
                <table>
                    <thead><tr><th>Type</th><th>Reference</th><th>Produit</th><th>Point</th><th>Quantite</th><th>Prix</th></tr></thead>
                    <tbody>
                    <?php foreach ($recent as $row): ?>
                        <?php
                        $badge = operation_badge($row);
                        $qty = (float) ($row['qt'] ?? 0);
                        $qtyClass = $qty < 0 ? 'text-danger' : ($qty > 0 ? 'text-success' : '');
                        $qtyText = ($qty > 0 ? '+' : '') . (string) $row['qt'];
                        ?>
                        <tr>
                            <td data-label="Type"><span class="operation-badge <?= e($badge[1]) ?>"><?= e($badge[0]) ?></span></td>
                            <td data-label="Reference"><?= e($row['reference_x']) ?></td>
                            <td data-label="Produit"><?= e($row['nom_x']) ?></td>
                            <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                            <td data-label="Quantite" class="<?= e($qtyClass) ?>"><?= e($qtyText) ?></td>
                            <td data-label="Prix"><?= money($row['prix_unitaire']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="panel panel-accent panel-accent-orange">
        <div class="panel-head">
            <div>
                <p class="panel-kicker">Stock</p>
                <h2><span class="ui-icon ui-icon-package" aria-hidden="true"></span>Articles a surveiller</h2>
            </div>
        </div>
        <?php if (!$stockWatch): ?>
            <p class="empty">Aucun article critique dans les lignes chargees.</p>
        <?php else: ?>
            <div class="cards-list">
                <?php foreach ($stockWatch as $row): ?>
                    <div class="legacy-link">
                        <strong><?= e($row['reference_x']) ?></strong>
                        <span><?= e($row['nom_x']) ?></span>
                        <span>Restant: <?= e($row['restant']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<section class="panel panel-accent panel-accent-blue">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Catalogue</p>
            <h2><span class="ui-icon ui-icon-package" aria-hidden="true"></span>Articles recents</h2>
        </div>
    </div>
    <div class="cards-list cards-compact">
        <?php foreach ($recentProducts as $product): ?>
                <div class="legacy-link">
                    <strong><?= e($product['reference_x']) ?></strong>
                    <span><?= e($product['nom_x']) ?></span>
                    <span><?= money($product['prix_de_vente']) ?> Ar</span>
                </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel panel-accent panel-accent-green">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Ventes</p>
            <h2><span class="ui-icon ui-icon-cart" aria-hidden="true"></span>Ventes recentes</h2>
        </div>
        <a class="button button-soft" href="<?= e(url('index.php?page=vente')) ?>"><span class="ui-icon ui-icon-cart" aria-hidden="true"></span>Ouvrir vente</a>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Date</th><th>Point</th><th>Lignes</th><th>Versement</th><th>Difference</th></tr></thead>
            <tbody>
            <?php foreach ($recentSales as $sale): ?>
                <tr>
                    <td data-label="No">#<?= e($sale['numero_commande_stock']) ?></td>
                    <td data-label="Date"><?= e($sale['description_date']) ?></td>
                    <td data-label="Point"><?= e($sale['nom_client_fournisseur']) ?></td>
                    <td data-label="Lignes"><?= e($sale['nb_lignes']) ?></td>
                    <td data-label="Versement"><?= money($sale['Montant']) ?></td>
                    <td data-label="Difference"><?= money($sale['difference_aparafa']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
