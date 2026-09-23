<?php
$q = trim((string) ($_GET['q'] ?? ''));

if (!function_exists('return_page_price_for_shop')) {
    function return_page_price_for_shop(array $product, string $shop): float
    {
        if ($shop === 'Ambato_Tantely') {
            return (float) ($product['pu_ambato_tantely'] ?? 0);
        }
        if ($shop === 'Amparafa') {
            return (float) ($product['pu_aparafa'] ?? 0);
        }
        return (float) ($product['prix_de_vente'] ?? 0);
    }
}

try {
    $shops = $repository->shops();
    $context = $db->fetch('SELECT stock_numero_commande, stock_client_name, stock_description_date FROM user WHERE User_Name = ?', [$auth->username()]);
    $hasReturnContext = $context && stripos((string) ($context['stock_description_date'] ?? ''), 'Abandon') !== false;
    $products = $repository->products($q, 12);
    $prepLines = $db->fetchAll(
        "SELECT sp.*, p.reference_x, p.nom_x
         FROM stock_prep sp
         LEFT JOIN produit p ON p.id_x = sp.id_x
         WHERE sp.description_date LIKE '%Abandon%' AND sp.user_stock_prep = ?
         ORDER BY sp.id_stock_prep ASC",
        [$auth->username()]
    );
    $rows = $db->fetchAll(
        "SELECT m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, m.Date_du_Journal_mvt,
                COUNT(*) AS lignes, SUM(m.qt) AS quantite, SUM(m.qt * m.prix_unitaire) AS total,
                MAX(m.id_mvt) AS latest_id, MAX(m.user_mvt) AS user_mvt
         FROM mvt m
         WHERE m.type_de_mvt = 'stock' AND m.description_date LIKE '%Abandon%'
         GROUP BY m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, m.Date_du_Journal_mvt
         ORDER BY latest_id DESC
         LIMIT 80"
    );
} catch (Throwable $e) {
    $shops = $products = $prepLines = $rows = [];
    $context = null;
    $hasReturnContext = false;
    flash('danger', 'Impossible de charger les retours: ' . $e->getMessage());
}

$prepTotal = 0.0;
foreach ($prepLines as $line) {
    $prepTotal += (float) $line['qt'] * (float) $line['prix_de_vente'];
}
$contextShop = (string) ($context['stock_client_name'] ?? '');
?>
<div class="stock-workspace">
    <div class="stock-workspace-main">
        <section class="panel panel-accent panel-accent-blue">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Retours</p>
                    <h2><span class="ui-icon ui-icon-return" aria-hidden="true"></span>Preparation retour</h2>
                    <p class="section-note">Flux standard historique: Abandon / Retours du produit.</p>
                </div>
                <span class="badge badge-success">Flux standard connecte</span>
            </div>
            <form method="post" action="<?= e(url('actions/retours.php')) ?>" class="form-grid">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="start">
                <label>Point de vente
                    <select name="point_de_vente" required>
                        <option value="">Choisir</option>
                        <?php foreach ($shops as $shopRow): ?>
                            <?php
                            $short = (string) ($shopRow['short_name'] ?? '');
                            $label = (string) (($shopRow['long_name'] ?? '') ?: $short);
                            ?>
                            <option value="<?= e($short) ?>" <?= $contextShop === $short ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Date retour
                    <input type="date" name="date_retour" value="<?= e(date('Y-m-d')) ?>">
                </label>
                <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Demarrer retour</button>
            </form>
            <?php if ($hasReturnContext): ?>
                <div class="context-grid">
                    <div><span>No retour</span><strong>#<?= e($context['stock_numero_commande']) ?></strong></div>
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
                    <h2><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher un article</h2>
                </div>
                <form class="inline-search" method="get">
                    <input type="hidden" name="page" value="retours">
                    <input name="q" value="<?= e($q) ?>" placeholder="Nom, reference ou ID">
                    <button class="button button-soft"><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher</button>
                </form>
            </div>
            <div class="cards-list">
                <?php foreach ($products as $product): ?>
                    <?php $defaultPrice = return_page_price_for_shop($product, $contextShop); ?>
                    <form method="post" action="<?= e(url('actions/retours.php')) ?>" class="item-row stock-item-row product-result-card product-result-card--return">
                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="add_line">
                        <input type="hidden" name="id_x" value="<?= e($product['id_x']) ?>">
                        <div class="product-result-card__identity">
                            <strong><?= e($product['nom_x']) ?></strong>
                            <span>Ref : <?= e($product['reference_x']) ?> | ID : <?= e($product['id_x']) ?></span>
                        </div>
                        <div class="product-result-card__fields product-result-card__fields--return">
                            <label class="item-field item-field-qty">Quantite
                                <input name="qt" type="number" min="0.01" step="any" value="1" required>
                            </label>
                            <label class="item-field item-field-price">Prix unitaire
                                <input name="prix_de_vente" type="number" step="any" value="<?= e($defaultPrice) ?>">
                            </label>
                            <label class="item-field item-field-note">Note
                                <input name="note_stock" placeholder="Note">
                            </label>
                        </div>
                        <div class="product-result-card__actions">
                            <button class="button button-primary" <?= $hasReturnContext ? '' : 'disabled' ?>><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter au retour</button>
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
            <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Lignes retour en cours</h2>
            <p class="section-note">Total preparation: <strong><?= money($prepTotal) ?> Ar</strong></p>
        </div>
        <?php if ($prepLines): ?>
            <form method="post" action="<?= e(url('actions/retours.php')) ?>" onsubmit="return confirm('Annuler cette preparation retour ?')">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="cancel">
                <button class="button button-danger"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span>Annuler</button>
            </form>
        <?php endif; ?>
    </div>
    <?php if (!$prepLines): ?>
        <p class="empty">Aucune ligne retour en preparation.</p>
    <?php else: ?>
        <div class="table-wrap responsive-table">
            <table>
                <thead><tr><th>No</th><th>Point</th><th>Article</th><th>QT</th><th>PU</th><th>Total</th><th>Note</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($prepLines as $line): ?>
                    <tr>
                        <td data-label="No">#<?= e($line['numero_stock_prep']) ?></td>
                        <td data-label="Point"><?= e($line['nom_du_client']) ?></td>
                        <td data-label="Article"><?= e($line['reference_x']) ?><br><strong><?= e($line['nom_x']) ?></strong></td>
                        <td data-label="QT" class="text-success">+<?= e(number_format((float) $line['qt'], 3, '.', ' ')) ?></td>
                        <td data-label="PU"><?= money($line['prix_de_vente']) ?></td>
                        <td data-label="Total"><?= money((float) $line['qt'] * (float) $line['prix_de_vente']) ?></td>
                        <td data-label="Note"><?= e($line['note']) ?></td>
                        <td data-label="Action">
                            <form method="post" action="<?= e(url('actions/retours.php')) ?>" onsubmit="return confirm('Retirer cette ligne retour ?')">
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
        <form method="post" action="<?= e(url('actions/retours.php')) ?>" class="form-actions validate-actions" onsubmit="return confirm('Confirmer la validation definitive de ce retour ?')">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="validate_return">
            <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Valider definitivement le retour</button>
        </form>
    <?php endif; ?>
</section>

<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Historique</p>
            <h2><span class="ui-icon ui-icon-history" aria-hidden="true"></span>Retours valides recents</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Date</th><th>Point</th><th>Lignes</th><th>Quantite</th><th>Total</th><th>Utilisateur</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td data-label="No">#<?= e($row['numero_commande_stock']) ?><br><span class="operation-badge operation-badge--return">Retour</span></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal_mvt'] ?: $row['description_date']) ?></td>
                    <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="Lignes"><?= e($row['lignes']) ?></td>
                    <td data-label="Quantite" class="text-success">+<?= e(number_format((float) $row['quantite'], 3, '.', ' ')) ?></td>
                    <td data-label="Total"><?= money($row['total']) ?></td>
                    <td data-label="Utilisateur"><?= e($row['user_mvt']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?>
                <tr><td colspan="7">Aucun retour valide trouve.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
