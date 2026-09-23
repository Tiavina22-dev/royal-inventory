<?php
$q = trim((string) ($_GET['q'] ?? ''));
try {
    $shops = $repository->shops();
    $context = $salesService->currentContext($auth->username());
    $cart = $salesService->cart($auth->username());
    $totals = $salesService->totals($cart, (string) ($context['point_de_vente'] ?? ''));
    $products = $repository->products($q, 20);
    $sales = $salesService->recentSales(20);
} catch (Throwable $e) {
    $shops = $cart = $products = $sales = [];
    $context = null;
    $totals = ['royal' => 0, 'client' => 0, 'difference' => 0, 'lines' => 0];
    flash('danger', 'Impossible de charger VENTE: ' . $e->getMessage());
}
?>
<div class="sale-layout">
    <div class="sale-main">
        <section class="panel panel-accent panel-accent-blue">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Contexte</p>
                    <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Informations de vente</h2>
                </div>
                <span class="status-dot <?= $cart ? 'on' : '' ?>"><?= $cart ? 'En cours' : 'Pret' ?></span>
            </div>
            <form method="post" action="<?= e(url('actions/vente.php')) ?>" class="form-grid">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="start">
                <label>Point de vente
                    <select name="point_de_vente" required>
                        <option value="">Choisir</option>
                        <?php foreach ($shops as $shop): ?>
                            <option value="<?= e($shop['short_name']) ?>" <?= (($context['point_de_vente'] ?? '') === $shop['short_name']) ? 'selected' : '' ?>>
                                <?= e($shop['long_name'] ?: $shop['short_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Date
                    <input type="date" name="date_vente" value="<?= e(date('Y-m-d')) ?>">
                </label>
                <label class="full">Note generale
                    <input name="note_general" placeholder="Versement, observation, reference...">
                </label>
                <button class="button button-primary" type="submit"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span><?= $context ? 'Modifier le contexte' : 'Demarrer la vente' ?></button>
            </form>
            <?php if ($context): ?>
                <div class="context-grid">
                    <div><span>Journal courant</span><strong>#<?= e($context['numero_commande']) ?></strong></div>
                    <div><span>Point de vente</span><strong><?= e($context['point_de_vente']) ?></strong></div>
                    <div><span>Date</span><strong><?= e($context['description_date']) ?></strong></div>
                </div>
            <?php endif; ?>
        </section>

        <section class="panel panel-accent panel-accent-teal">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Articles</p>
                    <h2><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Recherche et selection</h2>
                </div>
                <form class="inline-search" method="get">
                    <input type="hidden" name="page" value="vente">
                    <input name="q" value="<?= e($q) ?>" placeholder="Nom, reference ou ID">
                    <button class="button button-soft"><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher</button>
                </form>
            </div>
            <div class="cards-list">
                <?php foreach ($products as $product): ?>
                    <?php
                    $defaultPrice = (float) ($product['prix_de_vente'] ?? 0);
                    $pv = (string) ($context['point_de_vente'] ?? '');
                    if ($pv === 'Amparafa' && isset($product['pu_aparafa'])) {
                        $defaultPrice = (float) $product['pu_aparafa'];
                    } elseif ($pv === 'Ambato_Tantely' && isset($product['pu_ambato_tantely'])) {
                        $defaultPrice = (float) $product['pu_ambato_tantely'];
                    } elseif ($pv === 'Soalazaina' && isset($product['pu_soalazaina'])) {
                        $defaultPrice = (float) $product['pu_soalazaina'];
                    }
                    ?>
                    <form method="post" action="<?= e(url('actions/vente.php')) ?>" class="item-row sale-product-card product-result-card product-result-card--sale">
                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="add_line">
                        <input type="hidden" name="id_x" value="<?= e($product['id_x']) ?>">
                        <div class="sale-product-card__identity product-result-card__identity">
                            <strong><?= e($product['nom_x']) ?></strong>
                            <span><?= e($product['reference_x']) ?> | ID <?= e($product['id_x']) ?></span>
                        </div>
                        <div class="sale-product-card__fields">
                            <label class="item-field item-field-qty">Quantite
                                <input name="qt" type="number" min="0.01" step="any" value="1" aria-label="Quantite">
                            </label>
                            <label class="item-field item-field-price">Prix de vente
                                <input name="prix_de_vente" type="number" step="any" value="<?= e($defaultPrice) ?>" aria-label="Prix Royal">
                            </label>
                            <label class="item-field item-field-price">Prix client
                                <input name="prix_client" type="number" step="any" value="<?= e($defaultPrice) ?>" aria-label="Prix client">
                            </label>
                            <label class="item-field item-field-note">Note
                                <input name="note_commande" placeholder="Note">
                            </label>
                        </div>
                        <div class="sale-product-card__actions">
                            <button class="button button-primary"><span class="ui-icon ui-icon-plus" aria-hidden="true"></span><span class="desktop-label">Ajouter</span><span class="mobile-label">Ajouter au panier</span></button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="panel panel-accent panel-accent-orange">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Panier</p>
                    <h2><span class="ui-icon ui-icon-cart" aria-hidden="true"></span>Lignes de vente</h2>
                </div>
            </div>
            <?php if (!$cart): ?>
                <p class="empty">Aucun article dans le panier.</p>
            <?php else: ?>
                <div class="table-wrap responsive-table">
                    <table>
                        <thead><tr><th>Article</th><th>QT</th><th>PU Royal</th><th>PU client</th><th>Note</th><th>Total</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($cart as $line): ?>
                            <tr>
                                <td data-label="Article"><?= e($line['reference_x']) ?><br><strong><?= e($line['nom_x']) ?></strong></td>
                                <td colspan="5" class="cart-edit-cell">
                                    <form method="post" action="<?= e(url('actions/vente.php')) ?>" class="cart-edit">
                                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="action" value="update_line">
                                        <input type="hidden" name="id_commande" value="<?= e($line['id_commande']) ?>">
                                        <input name="qt" type="number" min="0.01" step="any" value="<?= e($line['qt']) ?>">
                                        <input name="prix_de_vente" type="number" step="any" value="<?= e($line['prix_de_vente']) ?>">
                                        <input name="prix_client" type="number" step="any" value="<?= e($line['prix_client']) ?>">
                                        <input name="note_commande" value="<?= e($line['note_commande']) ?>">
                                        <strong><?= money(((float) $line['prix_de_vente']) * ((float) $line['qt'])) ?></strong>
                                        <button class="button button-soft"><span class="ui-icon ui-icon-pencil" aria-hidden="true"></span>OK</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="<?= e(url('actions/vente.php')) ?>" onsubmit="return confirm('Retirer cette ligne ?')">
                                        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="action" value="remove_line">
                                        <input type="hidden" name="id_commande" value="<?= e($line['id_commande']) ?>">
                                        <button class="icon-danger" title="Retirer"><span class="ui-icon ui-icon-trash" aria-hidden="true"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </div>

    <aside class="sale-side">
        <section class="panel panel-accent panel-accent-purple summary-card">
            <div class="panel-head">
                <div>
                    <p class="panel-kicker">Resume</p>
                    <h2><span class="ui-icon ui-icon-wallet" aria-hidden="true"></span>Vente en cours</h2>
                </div>
                <a class="button button-soft" href="<?= e(url('index.php?page=recu&preview=1')) ?>"><span class="ui-icon ui-icon-eye" aria-hidden="true"></span>Apercu</a>
            </div>
            <?php $differenceClass = (float) $totals['difference'] > 0 ? 'sale-total-positive' : ((float) $totals['difference'] < 0 ? 'sale-total-negative' : 'sale-total-neutral'); ?>
            <div class="totals">
                <div class="sale-total-lines"><span>Lignes</span><strong><?= e($totals['lines']) ?></strong></div>
                <div class="sale-total-royal"><span>Total Royal</span><strong><?= money($totals['royal']) ?></strong></div>
                <div class="sale-total-client"><span>Total client</span><strong><?= money($totals['client']) ?></strong></div>
                <div class="<?= e($differenceClass) ?>"><span>Difference</span><strong><?= money($totals['difference']) ?></strong></div>
            </div>
            <form method="post" action="<?= e(url('actions/vente.php')) ?>" onsubmit="return confirm('Annuler la vente en cours ?')" class="actions-row form-actions">
                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="cancel">
                <button class="button button-danger" type="submit"><span class="ui-icon ui-icon-close" aria-hidden="true"></span>Annuler</button>
            </form>
        </section>

        <?php if ($cart): ?>
            <section class="panel panel-accent panel-accent-green">
                <div class="panel-head">
                    <div>
                        <p class="panel-kicker">Validation</p>
                        <h2><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Finaliser</h2>
                    </div>
                </div>
                <form method="post" action="<?= e(url('actions/vente.php')) ?>" class="validate-box">
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="action" value="validate">
                    <label>Versement Royal <input name="versement" type="number" step="any" value="<?= e($totals['royal']) ?>"></label>
                    <label>Vola tsy ampy <input name="resolution" type="number" step="any" value="0"></label>
                    <label>Mihoatra <input name="mihoatra" type="number" step="any" value="0"></label>
                    <label>Point caisse <input name="point" placeholder="Optionnel"></label>
                    <label class="full">Path pieces jointes <input name="path" placeholder="Optionnel"></label>
                    <label class="full">Note generale <input name="note_general"></label>
                    <div class="validate-actions">
                        <button class="button button-primary" type="submit" onclick="return confirm('Valider cette vente dans mvt et recap_vente ?')"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Valider la vente</button>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </aside>
</div>

<section class="panel panel-accent panel-accent-teal sale-history-panel">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Historique</p>
            <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Historique ventes</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Date</th><th>Point</th><th>Lignes</th><th>Versement</th><th>Difference</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td data-label="No">#<?= e($sale['numero_commande_stock']) ?></td>
                    <td data-label="Date"><?= e($sale['description_date']) ?></td>
                    <td data-label="Point"><?= e($sale['nom_client_fournisseur']) ?></td>
                    <td data-label="Lignes"><?= e($sale['nb_lignes']) ?></td>
                    <td data-label="Versement"><?= money($sale['Montant']) ?></td>
                    <td data-label="Difference"><?= money($sale['difference_aparafa']) ?></td>
                    <td><a class="button button-soft" href="<?= e(url('index.php?page=vente_details&sale=' . $sale['numero_commande_stock'])) ?>"><span class="ui-icon ui-icon-eye" aria-hidden="true"></span>Details</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
