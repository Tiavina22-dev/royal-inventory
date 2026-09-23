<?php
$groups = [
    'VENTE' => ['vente_new', 'vente_history', 'vente_light', 'vente_deleted'],
    'STOCK' => ['stock_add', 'stock_remove', 'stock_recap', 'articles'],
    'MVT / CONTROLE' => ['mouvements', 'controle'],
    'FACTURES / DEPENSES / UTILISATEURS' => ['factures', 'factures_recap', 'depenses', 'utilisateurs'],
];
$labels = [
    'vente_new' => 'Nouvelle vente ancienne interface',
    'vente_history' => 'Historique des ventes',
    'vente_light' => 'Journal de vente simplifie',
    'vente_deleted' => 'Ventes supprimees',
    'stock_add' => 'Entree de stock',
    'stock_remove' => 'Sortie de stock',
    'stock_recap' => 'Recapitulatif stock',
    'articles' => 'Articles',
    'mouvements' => 'Mouvements',
    'controle' => 'Controle',
    'factures' => 'Factures',
    'factures_recap' => 'Recapitulatif factures',
    'depenses' => 'Depenses',
    'utilisateurs' => 'Utilisateurs',
];
?>
<section class="panel panel-accent panel-accent-blue notice-panel">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Migration</p>
            <h2><span class="ui-icon ui-icon-external" aria-hidden="true"></span>Passerelle temporaire</h2>
        </div>
        <span class="badge badge-readonly">Disponible via l'ancienne interface</span>
    </div>
</section>
<?php foreach ($groups as $title => $keys): ?>
    <section class="panel panel-accent panel-accent-teal">
        <div class="panel-head"><h2><span class="ui-icon ui-icon-external" aria-hidden="true"></span><?= e($title) ?></h2></div>
        <div class="legacy-grid">
            <?php foreach ($keys as $key): ?>
                <a class="legacy-link" target="_blank" rel="noopener noreferrer" href="<?= e(legacy_url($legacyRoutes, $key, $config['legacy_base_url'])) ?>">
                    <strong><span class="ui-icon ui-icon-external" aria-hidden="true"></span><?= e($labels[$key] ?? $key) ?></strong>
                    <span><?= e($legacyRoutes[$key] ?? '') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>
