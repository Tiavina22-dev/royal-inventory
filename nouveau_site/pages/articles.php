<?php
$q = trim((string) ($_GET['q'] ?? ''));
try {
    $products = $repository->products($q, 80);
} catch (Throwable $e) {
    $products = [];
    flash('danger', 'Impossible de charger les articles: ' . $e->getMessage());
}
?>
<section class="panel panel-accent panel-accent-purple">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Catalogue</p>
            <h2><span class="ui-icon ui-icon-package" aria-hidden="true"></span>Articles</h2>
        </div>
        <form class="inline-search" method="get">
            <input type="hidden" name="page" value="articles">
            <input name="q" value="<?= e($q) ?>" placeholder="Recherche">
            <button class="button button-soft"><span class="ui-icon ui-icon-search" aria-hidden="true"></span>Rechercher</button>
        </form>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>ID</th><th>Reference</th><th>Nom</th><th>Prix vente</th><th>Prix fournisseur</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td data-label="ID"><?= e($product['id_x']) ?></td>
                    <td data-label="Reference"><?= e($product['reference_x']) ?></td>
                    <td data-label="Nom"><?= e($product['nom_x']) ?></td>
                    <td data-label="Prix vente"><?= money($product['prix_de_vente']) ?></td>
                    <td data-label="Prix fournisseur"><?= money($product['prix_fournisseur']) ?></td>
                    <td data-label="Action">
                        <details class="inline-details">
                            <summary class="button button-soft"><span class="ui-icon ui-icon-pencil" aria-hidden="true"></span>Modifier</summary>
                            <form method="post" action="<?= e(url('actions/articles.php')) ?>" class="form-grid compact-form">
                                <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id_x" value="<?= e($product['id_x']) ?>">
                                <label>Reference <input name="reference_x" value="<?= e($product['reference_x']) ?>" required></label>
                                <label>Nom <input name="nom_x" value="<?= e($product['nom_x']) ?>" required></label>
                                <label>Prix vente <input type="number" step="any" name="prix_de_vente" value="<?= e($product['prix_de_vente']) ?>"></label>
                                <label class="full">Note <input name="note_x" value="<?= e($product['note_x'] ?? '') ?>"></label>
                                <button class="button button-primary"><span class="ui-icon ui-icon-check-circle" aria-hidden="true"></span>Enregistrer</button>
                            </form>
                        </details>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<details class="panel panel-accent panel-accent-blue collapsible-panel">
    <summary>
        <div class="panel-head">
            <div>
                <p class="panel-kicker">Creation</p>
                <h2><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Nouvel article</h2>
            </div>
            <span class="badge badge-readonly">Action existante</span>
        </div>
    </summary>
    <form method="post" action="<?= e(url('actions/articles.php')) ?>" class="form-grid">
        <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="create">
        <label>Reference <input name="reference_x" required></label>
        <label>Nom <input name="nom_x" required></label>
        <label>Prix vente <input type="number" step="any" name="prix_de_vente" value="0"></label>
        <label>Prix fournisseur <input type="number" step="any" name="prix_fournisseur" value="0"></label>
        <label class="full">Note <input name="note_x"></label>
        <button class="button button-primary"><span class="ui-icon ui-icon-plus" aria-hidden="true"></span>Creer</button>
    </form>
</details>

<section class="panel panel-accent panel-accent-orange notice-panel">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Prix</p>
            <h2><span class="ui-icon ui-icon-tag" aria-hidden="true"></span>Prix par point de vente</h2>
        </div>
        <span class="badge badge-warning">En attente</span>
    </div>
</section>
