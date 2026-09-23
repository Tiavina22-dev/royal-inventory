<?php
$user = $auth->user();
$currentContext = null;
try {
    if ($auth->check()) {
        $currentContext = $salesService->currentContext($auth->username());
    }
} catch (Throwable $e) {
    $currentContext = null;
}
$pageSubtitles = [
    'dashboard' => "Vue d'ensemble",
    'vente' => 'Enregistrer une nouvelle vente',
    'stock' => 'Etat actuel des articles',
    'articles' => 'Catalogue et prix',
    'mouvements' => 'Historique des operations',
    'factures' => 'Receptions fournisseur',
    'retours' => 'Retours standard',
    'depenses' => 'Suivi des depenses',
    'controle' => 'Inventaire et rectifications',
    'rapports' => 'Analyse des ventes',
    'utilisateurs' => 'Permissions et comptes',
    'parametres' => 'Configuration application',
    'commandes' => 'Paniers et commandes temporaires',
];
$nav = [
    'principal' => [
        'dashboard' => ['Accueil', 'home'],
        'vente' => ['Vente', 'sale'],
        'stock' => ['Stock', 'stock'],
        'articles' => ['Articles', 'box'],
        'mouvements' => ['Mouvements', 'move'],
        'factures' => ['Factures / Recus', 'file'],
        'retours' => ['Retours', 'return'],
        'depenses' => ['Depenses', 'money'],
        'controle' => ['Controle', 'check'],
        'rapports' => ['Rapports', 'report'],
    ],
    'administration' => [
        'commandes' => ['Commandes', 'list'],
        'utilisateurs' => ['Utilisateurs', 'users'],
        'parametres' => ['Parametres', 'settings'],
    ],
    'systeme' => [
        'ancienne_interface' => ['Ancienne interface', 'legacy'],
    ],
];
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - <?= e($config['app_name']) ?></title>
    <link rel="stylesheet" href="public/assets/css/app.css">
</head>
<body class="page-<?= e($page) ?>">
<div class="app-shell">
    <header class="topbar">
        <button class="icon-button" data-toggle-sidebar aria-label="Menu"><span class="ui-icon ui-icon-menu" aria-hidden="true"></span></button>
        <a class="brand" href="<?= e(url('index.php')) ?>">
            <span class="brand-mark"><span class="ui-icon ui-icon-crown" aria-hidden="true"></span></span>
            <span class="brand-text">Royal Inventory</span>
        </a>
        <?php if (!empty($currentContext['point_de_vente'])): ?>
            <div class="topbar-context"><span class="ui-icon ui-icon-pin" aria-hidden="true"></span>Point de vente : <strong><?= e($currentContext['point_de_vente']) ?></strong></div>
        <?php endif; ?>
        <div class="topbar-spacer"></div>
        <div class="topbar-user">
            <span class="date-chip"><span class="ui-icon ui-icon-calendar" aria-hidden="true"></span><?= e(date('d/m/Y')) ?></span>
            <span class="user-chip"><span class="ui-icon ui-icon-user" aria-hidden="true"></span><?= e($user['full_name'] ?: $user['username']) ?></span>
            <a class="button button-ghost" href="<?= e(url('logout.php')) ?>"><span class="ui-icon ui-icon-logout" aria-hidden="true"></span>Deconnexion</a>
        </div>
    </header>

    <aside class="sidebar" data-sidebar>
        <button class="drawer-close" data-close-sidebar aria-label="Fermer"><span class="ui-icon ui-icon-close" aria-hidden="true"></span></button>
        <div class="sidebar-brand">
            <span class="brand-mark"><span class="ui-icon ui-icon-crown" aria-hidden="true"></span></span>
            <div>
                <strong>Royal Inventory</strong>
                <span>Gestion de stock</span>
            </div>
        </div>
        <nav>
            <?php foreach ($nav['principal'] as $key => [$label, $icon]): ?>
                <a class="nav-item <?= $page === $key ? 'active' : '' ?>" href="<?= e(url('index.php?page=' . $key)) ?>">
                    <span class="nav-icon nav-icon-<?= e($icon) ?>" aria-hidden="true"></span>
                    <span><?= e($label) ?></span>
                </a>
            <?php endforeach; ?>
            <div class="nav-separator"></div>
            <div class="nav-group">Administration</div>
            <?php foreach ($nav['administration'] as $key => [$label, $icon]): ?>
                <a class="nav-item <?= $page === $key ? 'active' : '' ?>" href="<?= e(url('index.php?page=' . $key)) ?>">
                    <span class="nav-icon nav-icon-<?= e($icon) ?>" aria-hidden="true"></span>
                    <span><?= e($label) ?></span>
                </a>
            <?php endforeach; ?>
            <div class="nav-separator"></div>
            <?php foreach ($nav['systeme'] as $key => [$label, $icon]): ?>
                    <a class="nav-item <?= $page === $key ? 'active' : '' ?>" href="<?= e(url('index.php?page=' . $key)) ?>">
                        <span class="nav-icon nav-icon-<?= e($icon) ?>" aria-hidden="true"></span>
                        <span><?= e($label) ?></span>
                    </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-account">
            <span><?= e($user['full_name'] ?: $user['username']) ?></span>
            <a href="<?= e(url('logout.php')) ?>"><span class="ui-icon ui-icon-logout" aria-hidden="true"></span>Deconnexion</a>
        </div>
        <div class="sidebar-version">Version 1.0.0</div>
    </aside>
    <div class="drawer-backdrop" data-close-sidebar></div>

    <main class="content">
        <div class="page-head">
            <div>
                <h1><?= e($pageTitle) ?></h1>
                <p><?= e($pageSubtitles[$page] ?? 'Interface de gestion') ?></p>
            </div>
            <div class="page-actions">
                <a class="button button-soft" href="<?= e(url('index.php?page=ancienne_interface')) ?>"><span class="ui-icon ui-icon-external" aria-hidden="true"></span>Ancienne interface</a>
            </div>
        </div>

        <?php foreach (flashes() as $message): ?>
            <div class="alert alert-<?= e($message['type']) ?>"><?= e($message['message']) ?></div>
        <?php endforeach; ?>

        <?php if (!$db->available()): ?>
            <div class="alert alert-danger">
                Connexion MySQL indisponible. Les ecrans restent accessibles mais les donnees ne peuvent pas etre chargees.
                <div class="small"><?= e($db->errorMessage()) ?></div>
            </div>
        <?php endif; ?>
