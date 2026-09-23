<?php
$q = trim((string) ($_GET['q'] ?? ''));
try {
    $shops = $repository->shops();
    $context = $db->fetch('SELECT numero_commande, point_de_vente, description_date FROM user WHERE User_Name = ?', [$auth->username()]);
    $products = $repository->products($q, 12);
    $prepLines = $db->fetchAll(
        "SELECT sp.*, p.reference_x, p.nom_x
         FROM stock_prep sp
         LEFT JOIN produit p ON p.id_x = sp.id_x
         WHERE sp.description_date LIKE '%Rectifier%' AND sp.user_stock_prep = ?
         ORDER BY sp.id_stock_prep ASC",
        [$auth->username()]
    );
    $recentControls = $db->fetchAll(
        "SELECT m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, m.Date_du_Journal_mvt, m.user_mvt,
                COUNT(*) AS lignes, SUM(m.prix_aparafa) AS difference_totale, MAX(m.id_mvt) AS latest_id
         FROM mvt m
         WHERE m.type_de_mvt = 'stock' AND m.status = 'General_Inventory'
         GROUP BY m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, m.Date_du_Journal_mvt, m.user_mvt
         ORDER BY latest_id DESC
         LIMIT 40"
    );
} catch (Throwable $e) {
    $shops = $products = $prepLines = $recentControls = [];
    $context = null;
    flash('danger', 'Impossible de charger controle: ' . $e->getMessage());
}

$hasControlContext = $context && stripos((string) ($context['description_date'] ?? ''), 'Rectifier') !== false;
function control_page_mysql_date(?string $description): string
{
    $description = (string) $description;
    if (preg_match('/([0-9]{2,4}[\/#-][0-9]{2,4}[\/#-][0-9]{2,4})/', $description, $matches) !== 1) {
        return date('Y-m-d');
    }
    $date = str_replace(['#', '/'], '-', $matches[1]);
    $date = preg_replace('/\s+/', '', $date);
    $dt = DateTime::createFromFormat('d-m-y', $date) ?: DateTime::createFromFormat('d-m-Y', $date);
    return $dt ? $dt->format('Y-m-d') : date('Y-m-d');
}

function control_theoretical_stock(Database $db, int $productId, string $shop, string $inventoryDate): float
{
    if ($productId <= 0 || $shop === '') {
        return 0.0;
    }
    $vente = (float) $db->scalar(
        "SELECT COALESCE(SUM(qt), 0) FROM mvt
         WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND Date_du_Journal_mvt <= ?",
        [$productId, $shop, $inventoryDate]
    );
    $stock = (float) $db->scalar(
        "SELECT COALESCE(SUM(qt), 0) FROM mvt
         WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?
           AND status NOT LIKE 'General_Inventory' AND Date_du_Journal_mvt <= ?",
        [$productId, $shop, $inventoryDate]
    );
    $generalInventory = (float) $db->scalar(
        "SELECT COALESCE(SUM(prix_aparafa), 0) FROM mvt
         WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?
           AND status LIKE 'General_Inventory'",
        [$productId, $shop]
    );
    return $vente + $stock + $generalInventory;
}
$inventoryDate = control_page_mysql_date($context['description_date'] ?? null);
?>
<div class="stock-workspace">
    <div class="stock-workspace-main">
        <section class="panel panel-accent panel-accent-blue">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Inventaire</p>
                    <h2><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Controle / inventaire</h2>
                    <p class="section-note">Preparez les ecarts constates avant validation historique.</p>
                </div>
                <span class="badge badge-success">Validation finale connectee</span>
            </div>
            <form method="post" action="<?= e(url('actions/controle.php')) ?>" class="form-grid">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="start">
                <label>Point de vente
                    <select name="point_de_vente" required>
                        <option value="">Choisir</option>
                        <?php foreach ($shops as $row): ?>
                            <option value="<?= e($row['short_name']) ?>" <?= (($context['point_de_vente'] ?? '') === $row['short_name']) ? 'selected' : '' ?>><?= e($row['long_name'] ?: $row['short_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Date controle <input type="date" name="date_controle" value="<?= e(date('Y-m-d')) ?>"></label>
                <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Demarrer le controle</button>
            </form>
            <?php if ($hasControlContext): ?>
                <div class="context-grid">
                    <div><span>No controle</span><strong>#<?= e($context['numero_commande']) ?></strong></div>
                    <div><span>Point</span><strong><?= e($context['point_de_vente']) ?></strong></div>
                    <div><span>Description</span><strong><?= e($context['description_date']) ?></strong></div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <div class="stock-workspace-side">
        <section class="panel panel-accent panel-accent-teal">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Articles</p>
                    <h2><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher un article</h2>
                </div>
                <form class="inline-search" method="get">
                    <input type="hidden" name="page" value="controle">
                    <input name="q" value="<?= e($q) ?>" placeholder="Nom, reference ou ID">
                    <button class="button button-soft"><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher</button>
                </form>
            </div>
            <div class="cards-list">
                <?php foreach ($products as $product): ?>
                    <?php
                    $theoretical = $hasControlContext ? control_theoretical_stock($db, (int) $product['id_x'], (string) $context['point_de_vente'], $inventoryDate) : 0.0;
                    $price = (float) ($product['prix_de_vente'] ?? 0);
                    ?>
                    <form method="post" action="<?= e(url('actions/controle.php')) ?>" class="item-row stock-item-row product-result-card product-result-card--control">
                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="add_line">
                        <input type="hidden" name="id_x" value="<?= e($product['id_x']) ?>">
                        <div class="product-result-card__identity">
                            <strong><?= e($product['nom_x']) ?></strong>
                            <span>Ref : <?= e($product['reference_x']) ?> | ID : <?= e($product['id_x']) ?></span>
                            <span class="product-result-card__meta">Stock theorique : <?= e($theoretical) ?></span>
                        </div>
                        <div class="product-result-card__fields product-result-card__fields--control">
                            <label class="item-field item-field-qty">Stock constate
                                <input name="qt_reelle" type="number" step="any" value="<?= e($theoretical) ?>" required>
                            </label>
                            <label class="item-field item-field-price">Prix Royal
                                <input name="prix_de_vente" type="number" step="any" value="<?= e($price) ?>">
                            </label>
                            <label class="item-field item-field-price">Prix client
                                <input name="prix_client" type="number" step="any" value="<?= e($price) ?>">
                            </label>
                            <label class="item-field item-field-note">Note
                                <input name="note_stock" placeholder="Note">
                            </label>
                        </div>
                        <div class="product-result-card__actions">
                            <button class="button button-primary" <?= $hasControlContext ? '' : 'disabled' ?>><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter au controle</button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>

<section class="panel panel-accent panel-accent-orange">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Preparation</p>
            <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Rectifications en cours</h2>
            <p class="section-note">Ces lignes restent dans stock_prep jusqu'a la validation definitive du controle.</p>
        </div>
        <?php if ($prepLines): ?>
            <form method="post" action="<?= e(url('actions/controle.php')) ?>" onsubmit="return confirm('Annuler cette preparation controle ?')">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="cancel">
                <button class="button button-danger"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span>Annuler</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>Point</th><th>Article</th><th>Stock theorique</th><th>Stock constate</th><th>Ecart</th><th>Prix</th><th>Note</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($prepLines as $row): ?>
                <?php
                $theoretical = control_theoretical_stock($db, (int) $row['id_x'], (string) $row['nom_du_client'], $inventoryDate);
                $observed = (float) $row['qt'];
                $gap = $observed - $theoretical;
                $gapClass = $gap > 0 ? 'move-status-in' : ($gap < 0 ? 'move-status-out' : 'badge-muted');
                $gapText = $gap > 0 ? '+' . $gap : (string) $gap;
                ?>
                <tr>
                    <td data-label="Point"><?= e($row['nom_du_client']) ?></td>
                    <td data-label="Article"><?= e($row['reference_x']) ?><br><strong><?= e($row['nom_x']) ?></strong></td>
                    <td data-label="Stock theorique"><?= e($theoretical) ?></td>
                    <td data-label="Stock constate"><?= e($row['qt']) ?></td>
                    <td data-label="Ecart"><span class="move-status <?= e($gapClass) ?>"><?= e($gapText) ?></span></td>
                    <td data-label="Prix"><?= money($row['prix_de_vente']) ?></td>
                    <td data-label="Note"><?= e($row['note']) ?></td>
                    <td data-label="Action">
                        <form method="post" action="<?= e(url('actions/controle.php')) ?>" onsubmit="return confirm('Retirer cette ligne ?')">
                            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="action" value="remove_line">
                            <input type="hidden" name="id_stock_prep" value="<?= e($row['id_stock_prep']) ?>">
                            <button class="icon-danger" title="Retirer"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="alert alert-warning">Validation General_Inventory sensible: elle cree les mouvements d'inventaire, nettoie cette preparation et desactive les anciens mouvements du point de vente jusqu'a la date du controle.</div>
    <?php if ($hasControlContext && $prepLines): ?>
        <form method="post" action="<?= e(url('actions/controle.php')) ?>" class="form-actions validate-actions" onsubmit="return confirm('Confirmer la validation definitive de ce controle ?')">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="validate_general_inventory">
            <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Valider definitivement le controle</button>
        </form>
    <?php endif; ?>
</section>

<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Historique</p>
            <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Controles valides recents</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Point</th><th>Date</th><th>Lignes</th><th>Difference</th><th>Utilisateur</th></tr></thead>
            <tbody>
            <?php foreach ($recentControls as $row): ?>
                <tr>
                    <td data-label="No">#<?= e($row['numero_commande_stock']) ?><br><span class="operation-badge operation-badge--control">General Inventory</span></td>
                    <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal_mvt'] ?: $row['description_date']) ?></td>
                    <td data-label="Lignes"><?= e($row['lignes']) ?></td>
                    <td data-label="Difference"><?= e($row['difference_totale']) ?></td>
                    <td data-label="Utilisateur"><?= e($row['user_mvt']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
