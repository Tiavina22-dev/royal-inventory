<?php
$q = trim((string) ($_GET['q'] ?? ''));
$shop = trim((string) ($_GET['shop'] ?? ''));
$productQ = trim((string) ($_GET['product_q'] ?? ''));
$operation = ($_GET['operation'] ?? 'in') === 'out' ? 'out' : 'in';
try {
    $shops = $repository->shops();
    $context = $stockService->currentContext($auth->username());
    $prepLines = $stockService->preparedLines($auth->username(), $operation);
    $products = $repository->products($productQ, 12);
    $stockRows = $stockService->currentStock($q, $shop);
    $recentStock = $stockService->recentStockMoves();
} catch (Throwable $e) {
    $shops = $prepLines = $products = $stockRows = $recentStock = [];
    $context = null;
    flash('danger', 'Impossible de charger STOCK: ' . $e->getMessage());
}
$totalArticles = count($stockRows);
$totalRestant = 0.0;
$totalEntrees = 0.0;
$totalVentes = 0.0;
foreach ($stockRows as $row) {
    $totalRestant += (float) ($row['restant'] ?? 0);
    $totalEntrees += (float) ($row['total_stock'] ?? 0);
    $totalVentes += (float) ($row['total_vente'] ?? 0);
}
$contextDescription = (string) ($context['stock_description_date'] ?? '');
$hasStockContext = $context && !empty($context['stock_numero_commande'])
    && (
        ($operation === 'in' && stripos($contextDescription, 'Ajout') !== false)
        || ($operation === 'out' && stripos($contextDescription, 'Retirer') !== false)
    );
?>
<section class="stock-summary" aria-label="Resume du stock">
    <div class="stock-card stock-card-articles"><span class="stock-card-icon"><span class="ui-icon ui-icon-package" aria-hidden="true"></span></span><span>Articles affiches</span><strong><?= money($totalArticles) ?></strong><small>Nombre d'articles dans la liste</small></div>
    <div class="stock-card stock-card-in"><span class="stock-card-icon"><span class="ui-icon ui-icon-download" aria-hidden="true"></span></span><span>Entrees</span><strong><?= money($totalEntrees) ?></strong><small>Total des quantites entrees</small></div>
    <div class="stock-card stock-card-out"><span class="stock-card-icon"><span class="ui-icon ui-icon-arrow-left" aria-hidden="true"></span></span><span>Ventes / sorties</span><strong><?= money(abs($totalVentes)) ?></strong><small>Total des quantites sorties</small></div>
    <div class="stock-card stock-card-rest"><span class="stock-card-icon"><span class="ui-icon ui-icon-package" aria-hidden="true"></span></span><span>Restant</span><strong><?= money($totalRestant) ?></strong><small>Stock actuel disponible</small></div>
</section>

<div class="stock-workspace">
    <div class="stock-workspace-main">
<section class="panel panel-accent panel-accent-blue">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Operation stock</p>
            <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Preparation</h2>
            <p class="section-note">Configurez l'operation de stock</p>
        </div>
        <span class="badge badge-readonly">Flux standard</span>
    </div>
    <form method="post" action="<?= e(url('actions/stock.php')) ?>" class="form-grid">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="start">
        <label>Operation
            <select name="operation">
                <option value="in" <?= $operation === 'in' ? 'selected' : '' ?>>Entree / ajout</option>
                <option value="out" <?= $operation === 'out' ? 'selected' : '' ?>>Sortie / reduction</option>
            </select>
        </label>
        <label>Point de vente
            <select name="point_de_vente" required>
                <option value="">Choisir</option>
                <?php foreach ($shops as $row): ?>
                    <option value="<?= e($row['short_name']) ?>" <?= (($context['stock_client_name'] ?? '') === $row['short_name']) ? 'selected' : '' ?>><?= e($row['long_name'] ?: $row['short_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Date
            <input type="date" name="date_stock" value="<?= e(date('Y-m-d')) ?>">
        </label>
        <button class="button button-primary" type="submit"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Demarrer le stock</button>
    </form>
    <?php if ($hasStockContext): ?>
        <div class="context-grid">
            <div><span>No stock</span><strong>#<?= e($context['stock_numero_commande']) ?></strong></div>
            <div><span>Point</span><strong><?= e($context['stock_client_name']) ?></strong></div>
            <div><span>Description</span><strong><?= e($context['stock_description_date']) ?></strong></div>
        </div>
    <?php endif; ?>
</section>
    </div>

    <div class="stock-workspace-side">
<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Articles</p>
            <h2><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter une ligne stock</h2>
            <p class="section-note">Recherchez un article et ajoutez une quantite</p>
        </div>
        <form class="inline-search" method="get">
            <input type="hidden" name="page" value="stock">
            <input type="hidden" name="operation" value="<?= e($operation) ?>">
            <input name="product_q" value="<?= e($productQ) ?>" placeholder="Nom, reference ou ID">
            <button class="button button-soft"><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher</button>
        </form>
    </div>
    <div class="cards-list">
        <?php foreach ($products as $product): ?>
            <?php $defaultPrice = (float) ($product['prix_de_vente'] ?? 0); ?>
            <form method="post" action="<?= e(url('actions/stock.php')) ?>" class="item-row stock-item-row product-result-card <?= $operation === 'in' ? 'product-result-card--stock-entry' : 'product-result-card--stock-exit' ?>">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="add_line">
                <input type="hidden" name="operation" value="<?= e($operation) ?>">
                <input type="hidden" name="id_x" value="<?= e($product['id_x']) ?>">
                <div class="product-result-card__identity">
                    <strong><?= e($product['nom_x']) ?></strong>
                    <span>Ref : <?= e($product['reference_x']) ?> | ID : <?= e($product['id_x']) ?></span>
                </div>
                <div class="product-result-card__fields product-result-card__fields--stock">
                    <label class="item-field item-field-qty">Quantite
                        <input name="qt" type="number" min="0.01" step="any" value="1" required>
                    </label>
                    <label class="item-field item-field-price">Prix vente
                        <input name="prix_de_vente" type="number" step="any" value="<?= e($defaultPrice) ?>">
                    </label>
                    <?php if ($operation === 'in'): ?>
                        <label class="item-field item-field-price">Prix client
                            <input name="prix_client" type="number" step="any" value="<?= e($defaultPrice) ?>">
                        </label>
                    <?php endif; ?>
                    <label class="item-field item-field-price">Prix fournisseur
                        <input name="prix_fournisseur" type="number" step="any" value="<?= e($product['prix_fournisseur'] ?? 0) ?>">
                    </label>
                    <label class="item-field item-field-note">Note
                        <input name="note_stock" placeholder="Note">
                    </label>
                </div>
                <div class="product-result-card__actions">
                    <button class="button button-primary"><span class="ui-icon ui-icon-plus" aria-hidden="true"></span><?= $operation === 'in' ? 'Ajouter au stock' : 'Retirer du stock' ?></button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel panel-accent panel-accent-orange">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Preparation</p>
            <h2><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Lignes a valider</h2>
            <p class="section-note">Articles ajoutes dans cette operation</p>
        </div>
    </div>
    <?php if (!$prepLines): ?>
        <p class="empty">Aucune ligne stock en preparation.</p>
    <?php else: ?>
        <div class="table-wrap responsive-table">
            <table>
                <thead><tr><th>Article</th><th>QT</th><th>Prix</th><th>Note</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($prepLines as $line): ?>
                    <tr>
                        <td data-label="Article"><?= e($line['reference_x']) ?><br><strong><?= e($line['nom_x']) ?></strong></td>
                        <td data-label="QT"><?= e($line['qt']) ?></td>
                        <td data-label="Prix"><?= money($line['prix_de_vente']) ?></td>
                        <td data-label="Note"><?= e($line['note']) ?></td>
                        <td>
                            <form method="post" action="<?= e(url('actions/stock.php')) ?>" onsubmit="return confirm('Retirer cette ligne stock ?')">
                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="remove_line">
                                <input type="hidden" name="id_stock_prep" value="<?= e($line['id_stock_prep']) ?>">
                                <button class="icon-danger" title="Retirer"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="actions-row form-actions">
            <form method="post" action="<?= e(url('actions/stock.php')) ?>" onsubmit="return confirm('Valider ces mouvements stock ?')">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="validate">
                <input type="hidden" name="operation" value="<?= e($operation) ?>">
                <button class="button button-primary" type="submit"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Valider le stock</button>
            </form>
            <form method="post" action="<?= e(url('actions/stock.php')) ?>" onsubmit="return confirm('Annuler la preparation stock ?')">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="cancel">
                <input type="hidden" name="operation" value="<?= e($operation) ?>">
                <button class="button button-danger" type="submit"><span class="ui-icon ui-icon-close" aria-hidden="true"></span>Annuler</button>
            </form>
        </div>
    <?php endif; ?>
</section>
    </div>
</div>

<section class="panel panel-accent panel-accent-purple">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Recherche</p>
            <h2><span class="ui-icon ui-icon-package" aria-hidden="true"></span>Stock actuel</h2>
            <p class="section-note">Liste des articles et mouvements calcules depuis les donnees existantes.</p>
        </div>
        <form class="inline-search" method="get">
            <input type="hidden" name="page" value="stock">
            <select name="shop">
                <option value="">Tous points</option>
                <?php foreach ($shops as $row): ?>
                    <option value="<?= e($row['short_name']) ?>" <?= $shop === $row['short_name'] ? 'selected' : '' ?>><?= e($row['long_name'] ?: $row['short_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input name="q" value="<?= e($q) ?>" placeholder="Article ou reference">
            <button class="button button-soft"><span class="ui-icon ui-icon-filter" aria-hidden="true"></span>Filtrer</button>
        </form>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>Reference</th><th>Article</th><th>Entrees</th><th>Ventes</th><th>Restant</th><th>Prix</th></tr></thead>
            <tbody>
            <?php foreach ($stockRows as $row): ?>
                <?php
                $restant = (float) ($row['restant'] ?? 0);
                $stockClass = $restant < 0 ? 'stock-negative' : ($restant <= 0 ? 'stock-low' : '');
                $stockBadge = $restant < 0 ? ['Stock negatif', 'badge-stock-negative'] : ($restant == 0.0 ? ['Stock epuise', 'badge-stock-empty'] : ['En stock', 'badge-stock-ok']);
                ?>
                <tr class="<?= e($stockClass) ?>">
                    <td data-label="Reference"><?= e($row['reference_x']) ?></td>
                    <td data-label="Article"><?= e($row['nom_x']) ?></td>
                    <td data-label="Entrees"><?= e($row['total_stock']) ?></td>
                    <td data-label="Ventes"><?= e($row['total_vente']) ?></td>
                    <td data-label="Restant"><strong class="stock-qty-state"><?= e($row['restant']) ?></strong> <span class="stock-state-badge <?= e($stockBadge[1]) ?>"><?= e($stockBadge[0]) ?></span></td>
                    <td data-label="Prix"><?= money($row['prix_de_vente']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<section class="panel panel-accent panel-accent-history stock-history-panel">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Historique</p>
            <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Mouvements stock recents</h2>
            <p class="section-note">Derniers mouvements effectues sur les articles.</p>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Statut</th><th>Reference</th><th>Article</th><th>Point</th><th>QT</th><th>Prix</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($recentStock as $row): ?>
                <?php
                $qty = (float) ($row['qt'] ?? 0);
                $isIn = $qty >= 0;
                $qtyText = ($qty > 0 ? '+' : '') . e($row['qt']);
                $badge = operation_badge($row + ['type_de_mvt' => 'stock']);
                ?>
                <tr class="<?= $isIn ? 'stock-move-in' : 'stock-move-out' ?>">
                    <td data-label="No">#<?= e($row['numero_commande_stock']) ?></td>
                    <td data-label="Statut"><span class="operation-badge <?= e($badge[1]) ?>"><?= e($badge[0]) ?></span></td>
                    <td data-label="Reference"><?= e($row['reference_x']) ?></td>
                    <td data-label="Article"><?= e($row['nom_x']) ?></td>
                    <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="QT"><strong class="<?= $isIn ? 'qty-positive' : 'qty-negative' ?>"><?= $qtyText ?></strong></td>
                    <td data-label="Prix"><?= money($row['prix_unitaire']) ?></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal_mvt']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
