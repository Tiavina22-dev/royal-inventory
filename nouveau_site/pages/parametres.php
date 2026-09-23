<?php
try {
    $shops = $repository->shops();
} catch (Throwable $e) {
    $shops = [];
}
?>
<section class="panel panel-accent panel-accent-blue">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Points de vente</p>
            <h2><span class="ui-icon ui-icon-pin" aria-hidden="true"></span>Points de vente detectes</h2>
        </div>
    </div>
    <div class="chips">
        <?php foreach ($shops as $shop): ?>
            <span><?= e($shop['long_name'] ?: $shop['short_name']) ?></span>
        <?php endforeach; ?>
    </div>
</section>
<section class="panel panel-accent panel-accent-purple">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Application</p>
            <h2><span class="ui-icon ui-icon-settings" aria-hidden="true"></span>Informations application</h2>
        </div>
    </div>
    <div class="settings-grid">
        <div><span>Nom</span><strong><?= e($config['app_name']) ?></strong></div>
        <div><span>Ancienne interface</span><strong><?= e($config['legacy_base_url']) ?></strong></div>
    </div>
</section>
<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Technique</p>
            <h2><span class="ui-icon ui-icon-clipboard" aria-hidden="true"></span>Informations techniques</h2>
        </div>
        <span class="badge badge-muted">Secondaire</span>
    </div>
    <div class="settings-grid">
        <div><span>Base</span><strong><?= e($config['db']['database']) ?></strong></div>
        <div><span>Hote</span><strong><?= e($config['db']['host'] . ':' . $config['db']['port']) ?></strong></div>
        <div><span>Charset</span><strong><?= e($config['db']['charset']) ?></strong></div>
    </div>
</section>
