<?php

require __DIR__ . '/app/bootstrap.php';

$auth->requireAuth();

$page = current_page();
$allowedPages = [
    'dashboard',
    'vente',
    'vente_details',
    'recu',
    'stock',
    'mouvements',
    'articles',
    'commandes',
    'retours',
    'depenses',
    'controle',
    'rapports',
    'factures',
    'facture_details',
    'utilisateurs',
    'parametres',
    'ancienne_interface',
];

if (!in_array($page, $allowedPages, true)) {
    $page = 'dashboard';
}

$pageTitle = [
    'dashboard' => 'Tableau de bord',
    'vente' => 'Vente',
    'vente_details' => 'Details vente',
    'recu' => 'Recu',
    'stock' => 'Stock',
    'mouvements' => 'Mouvements',
    'articles' => 'Articles',
    'commandes' => 'Commandes',
    'retours' => 'Retours',
    'depenses' => 'Depenses',
    'controle' => 'Controle',
    'rapports' => 'Rapports / Analyse',
    'factures' => 'Factures / Recus',
    'facture_details' => 'Details facture',
    'utilisateurs' => 'Utilisateurs',
    'parametres' => 'Parametres',
    'ancienne_interface' => 'Ancienne interface',
][$page] ?? 'Tableau de bord';

require __DIR__ . '/partials/header.php';
require __DIR__ . '/pages/' . $page . '.php';
require __DIR__ . '/partials/footer.php';
