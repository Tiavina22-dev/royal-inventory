<?php
$q = trim((string) ($_GET['q'] ?? ''));
try {
    $context = $db->fetch('SELECT stock_numero_commande, stock_client_name, stock_description_date FROM user WHERE User_Name = ?', [$auth->username()]);
    $products = $repository->products($q, 12);
    $prepLines = $db->fetchAll(
        "SELECT sp.*, p.reference_x, p.nom_x, p.prix_fournisseur
         FROM stock_prep sp
         LEFT JOIN produit p ON p.id_x = sp.id_x
         WHERE sp.description_date LIKE '%Facture%' AND sp.user_stock_prep = ?
         ORDER BY sp.id_stock_prep ASC",
        [$auth->username()]
    );
    $rows = $db->fetchAll(
        "SELECT m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, m.Date_du_Journal_mvt,
                COUNT(*) AS lignes, SUM(m.qt * m.prix_unitaire) AS total, MAX(m.id_mvt) AS latest_id
         FROM mvt m
         WHERE m.type_de_mvt = 'facture'
         GROUP BY m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, m.Date_du_Journal_mvt
         ORDER BY latest_id DESC
         LIMIT 80"
    );
} catch (Throwable $e) {
    $products = $prepLines = $rows = [];
    $context = null;
    flash('danger', 'Impossible de charger factures: ' . $e->getMessage());
}
$hasInvoiceContext = $context && stripos((string) ($context['stock_description_date'] ?? ''), 'Facture') !== false;
$prepTotal = 0.0;
foreach ($prepLines as $line) {
    $prepTotal += (float) $line['qt'] * (float) $line['prix_de_vente'];
}
?>
<div class="stock-workspace">
    <div class="stock-workspace-main">
        <section class="panel panel-accent panel-accent-purple">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Facturation</p>
                    <h2><span class="ui-icon ui-icon-receipt" aria-hidden="true"></span>Factures / recus</h2>
                    <p class="section-note">Preparez les lignes facture avant validation dans l'ancien flux.</p>
                </div>
                <span class="badge badge-readonly">Validation finale connectee</span>
            </div>
            <form method="post" action="<?= e(url('actions/factures.php')) ?>" class="form-grid">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="start">
                <label>Fournisseur <input name="fournisseur" value="<?= e($context['stock_client_name'] ?? '') ?>" required></label>
                <label>Date facture <input type="date" name="date_facture" value="<?= e(date('Y-m-d')) ?>"></label>
                <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Demarrer facture</button>
            </form>
            <?php if ($hasInvoiceContext): ?>
                <div class="context-grid">
                    <div><span>No facture</span><strong>#<?= e($context['stock_numero_commande']) ?></strong></div>
                    <div><span>Fournisseur</span><strong><?= e($context['stock_client_name']) ?></strong></div>
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
                    <h2><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter une ligne facture</h2>
                </div>
                <form class="inline-search" method="get">
                    <input type="hidden" name="page" value="factures">
                    <input name="q" value="<?= e($q) ?>" placeholder="Nom, reference ou ID">
                    <button class="button button-soft"><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher</button>
                </form>
            </div>
            <div class="cards-list">
                <?php foreach ($products as $product): ?>
                    <form method="post" action="<?= e(url('actions/factures.php')) ?>" class="item-row stock-item-row product-result-card product-result-card--invoice">
                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="add_line">
                        <input type="hidden" name="id_x" value="<?= e($product['id_x']) ?>">
                        <div class="product-result-card__identity">
                            <strong><?= e($product['nom_x']) ?></strong>
                            <span>Ref : <?= e($product['reference_x']) ?> | ID : <?= e($product['id_x']) ?></span>
                        </div>
                        <div class="product-result-card__fields product-result-card__fields--invoice">
                            <label class="item-field item-field-qty">Quantite
                                <input name="qt" type="number" step="any" min="0.01" value="1" required>
                            </label>
                            <label class="item-field item-field-price">Prix fournisseur
                                <input name="prix_fournisseur" type="number" step="any" value="<?= e($product['prix_fournisseur'] ?? 0) ?>">
                            </label>
                            <label class="item-field item-field-note">Note
                                <input name="note_stock" placeholder="Note">
                            </label>
                        </div>
                        <div class="product-result-card__actions">
                            <button class="button button-primary" <?= $hasInvoiceContext ? '' : 'disabled' ?>><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Ajouter</button>
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
            <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Lignes facture en cours</h2>
            <p class="section-note">Total preparation: <strong><?= money($prepTotal) ?> Ar</strong></p>
        </div>
        <?php if ($prepLines): ?>
            <form method="post" action="<?= e(url('actions/factures.php')) ?>" onsubmit="return confirm('Annuler cette preparation facture ?')">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="cancel">
                <button class="button button-danger"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span>Annuler</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>Fournisseur</th><th>Article</th><th>QT</th><th>Prix fournisseur</th><th>Total</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($prepLines as $line): ?>
                <tr>
                    <td data-label="Fournisseur"><?= e($line['nom_du_client']) ?></td>
                    <td data-label="Article"><?= e($line['reference_x']) ?><br><strong><?= e($line['nom_x']) ?></strong></td>
                    <td data-label="QT"><?= e($line['qt']) ?></td>
                    <td data-label="Prix fournisseur"><?= money($line['prix_de_vente']) ?></td>
                    <td data-label="Total"><?= money((float) $line['qt'] * (float) $line['prix_de_vente']) ?></td>
                    <td data-label="Action">
                        <form method="post" action="<?= e(url('actions/factures.php')) ?>" onsubmit="return confirm('Retirer cette ligne facture ?')">
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
    <div class="alert alert-warning">Validation sensible: elle cree les mouvements facture, met a jour le prix fournisseur si besoin, inscrit history et nettoie cette preparation.</div>
    <?php if ($hasInvoiceContext && $prepLines): ?>
        <form method="post" action="<?= e(url('actions/factures.php')) ?>" class="form-actions validate-actions" onsubmit="return confirm('Confirmer la validation definitive de cette facture ?')">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="validate_invoice">
            <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Valider definitivement la facture</button>
        </form>
    <?php endif; ?>
</section>

<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Liste</p>
            <h2><span class="ui-icon ui-icon-receipt" aria-hidden="true"></span>Factures validees</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Fournisseur</th><th>Date</th><th>Lignes</th><th>Total</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td data-label="No">#<?= e($row['numero_commande_stock']) ?><br><span class="operation-badge operation-badge--invoice">Facture</span></td>
                    <td data-label="Fournisseur"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal_mvt'] ?: $row['description_date']) ?></td>
                    <td data-label="Lignes"><?= e($row['lignes']) ?></td>
                    <td data-label="Total"><?= money($row['total']) ?></td>
                    <td data-label="Action"><a class="button button-soft" href="<?= e(url('index.php?page=facture_details&invoice=' . $row['numero_commande_stock'])) ?>"><span class="ui-icon ui-icon-eye" aria-hidden="true"></span>Details</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
