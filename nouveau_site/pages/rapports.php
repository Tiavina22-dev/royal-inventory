<?php

if (!function_exists('ri_report_where')) {
    function ri_report_where(array $filters, array &$params, bool $includeType = false): string
    {
        $clauses = ['1 = 1'];

        if ($filters['from'] !== '') {
            $clauses[] = 'm.Date_du_Journal_mvt >= ?';
            $params[] = $filters['from'];
        }

        if ($filters['to'] !== '') {
            $clauses[] = 'm.Date_du_Journal_mvt <= ?';
            $params[] = $filters['to'];
        }

        if ($filters['shop'] !== '') {
            $clauses[] = 'm.nom_client_fournisseur = ?';
            $params[] = $filters['shop'];
        }

        if ($includeType && $filters['type'] !== '') {
            $clauses[] = 'm.type_de_mvt = ?';
            $params[] = $filters['type'];
        }

        if ($filters['article'] !== '') {
            $like = '%' . $filters['article'] . '%';
            $clauses[] = '(p.reference_x LIKE ? OR p.nom_x LIKE ? OR CAST(m.id_x AS CHAR) LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        return 'WHERE ' . implode(' AND ', $clauses);
    }
}

$filters = [
    'from' => trim((string) ($_GET['from'] ?? '')),
    'to' => trim((string) ($_GET['to'] ?? '')),
    'shop' => trim((string) ($_GET['shop'] ?? '')),
    'type' => trim((string) ($_GET['type'] ?? '')),
    'article' => trim((string) ($_GET['article'] ?? '')),
];

$shops = [];
$allowedTypes = ['vente', 'stock', 'facture'];
$summary = [
    'sales_journals' => 0,
    'sales_total' => 0,
    'movements' => 0,
    'invoices' => 0,
];
$salesByShop = [];
$movementsByType = [];
$recentMovements = [];
$recentSales = [];

try {
    $shops = $repository->shops();
    $dbTypes = $db->fetchAll(
        "SELECT DISTINCT type_de_mvt
         FROM mvt
         WHERE type_de_mvt IS NOT NULL AND type_de_mvt <> ''
         ORDER BY type_de_mvt"
    );
    foreach ($dbTypes as $dbType) {
        $typeValue = (string) ($dbType['type_de_mvt'] ?? '');
        if ($typeValue !== '' && !in_array($typeValue, $allowedTypes, true)) {
            $allowedTypes[] = $typeValue;
        }
    }
    if (!in_array($filters['type'], $allowedTypes, true)) {
        $filters['type'] = '';
    }

    $summaryParams = [];
    $summaryWhere = ri_report_where($filters, $summaryParams, false);
    $summary = $db->fetch(
        "SELECT
            COUNT(*) AS movements,
            COUNT(DISTINCT CASE WHEN m.type_de_mvt = 'vente' THEN m.numero_commande_stock END) AS sales_journals,
            COALESCE(SUM(CASE WHEN m.type_de_mvt = 'vente' THEN ABS(m.qt) * m.prix_unitaire ELSE 0 END), 0) AS sales_total,
            COUNT(DISTINCT CASE WHEN m.type_de_mvt = 'facture' THEN m.numero_commande_stock END) AS invoices
         FROM mvt m
         LEFT JOIN produit p ON p.id_x = m.id_x
         {$summaryWhere}",
        $summaryParams
    ) ?: $summary;

    $salesParams = [];
    $salesWhere = ri_report_where(array_merge($filters, ['type' => '']), $salesParams, false);
    $salesByShop = $db->fetchAll(
        "SELECT m.nom_client_fournisseur,
                COUNT(DISTINCT m.numero_commande_stock) AS journals,
                COALESCE(SUM(ABS(m.qt) * m.prix_unitaire), 0) AS total
         FROM mvt m
         LEFT JOIN produit p ON p.id_x = m.id_x
         {$salesWhere} AND m.type_de_mvt = 'vente'
         GROUP BY m.nom_client_fournisseur
         ORDER BY total DESC
         LIMIT 20",
        $salesParams
    );

    $typeParams = [];
    $typeWhere = ri_report_where($filters, $typeParams, true);
    $movementsByType = $db->fetchAll(
        "SELECT m.type_de_mvt,
                COUNT(*) AS line_count,
                COUNT(DISTINCT m.numero_commande_stock) AS journals,
                COALESCE(SUM(m.qt), 0) AS quantity,
                COALESCE(SUM(ABS(m.qt) * m.prix_unitaire), 0) AS total
         FROM mvt m
         LEFT JOIN produit p ON p.id_x = m.id_x
         {$typeWhere}
         GROUP BY m.type_de_mvt
         ORDER BY line_count DESC, m.type_de_mvt
         LIMIT 20",
        $typeParams
    );

    $recentParams = [];
    $recentWhere = ri_report_where($filters, $recentParams, true);
    $recentMovements = $db->fetchAll(
        "SELECT m.id_mvt, m.type_de_mvt, m.nom_client_fournisseur, m.description_date,
                m.numero_commande_stock, m.qt, m.prix_unitaire, m.Date_du_Journal_mvt,
                p.reference_x, p.nom_x
         FROM mvt m
         LEFT JOIN produit p ON p.id_x = m.id_x
         {$recentWhere}
         ORDER BY m.id_mvt DESC
         LIMIT 50",
        $recentParams
    );

    $recentSalesParams = [];
    $recentSalesWhere = ri_report_where(array_merge($filters, ['type' => '']), $recentSalesParams, false);
    $recentSales = $db->fetchAll(
        "SELECT rv.no_activite, rv.nb_ligne, rv.Montant, rv.difference_aparafa,
                rv.c_point, rv.Date_du_Journal, rv.status
         FROM recap_vente rv
         WHERE (? = '' OR rv.Date_du_Journal >= ?)
           AND (? = '' OR rv.Date_du_Journal <= ?)
           AND (? = '' OR rv.c_point = ?)
         ORDER BY rv.no_activite DESC
         LIMIT 30",
        [
            $filters['from'],
            $filters['from'],
            $filters['to'],
            $filters['to'],
            $filters['shop'],
            $filters['shop'],
        ]
    );
} catch (Throwable $e) {
    flash('danger', 'Impossible de charger les rapports: ' . $e->getMessage());
}
?>
<div class="reports-page">
<section class="reports-header panel panel-accent panel-accent-purple">
    <div class="reports-header__top">
        <div>
            <p class="panel-kicker">Analyse</p>
            <div class="reports-title">
                <span class="reports-title__icon"><span class="ui-icon ui-icon-chart" aria-hidden="true"></span></span>
                <div>
                    <h2>Rapports / Analyse</h2>
                    <p>Consultations issues des mouvements, ventes et factures existants.</p>
                </div>
            </div>
        </div>
        <span class="reports-readonly"><span class="ui-icon ui-icon-eye" aria-hidden="true"></span>Lecture seule</span>
    </div>
    <form method="get" class="reports-filters">
        <input type="hidden" name="page" value="rapports">
        <label class="reports-filter-date">
            Debut
            <input type="date" name="from" value="<?= e($filters['from']) ?>">
        </label>
        <label class="reports-filter-date">
            Fin
            <input type="date" name="to" value="<?= e($filters['to']) ?>">
        </label>
        <label class="reports-filter-shop">
            Point de vente
            <select name="shop">
                <option value="">Tous</option>
                <?php foreach ($shops as $shopRow): ?>
                    <?php $shopName = (string) ($shopRow['long_name'] ?? $shopRow['short_name'] ?? ''); ?>
                    <option value="<?= e($shopName) ?>" <?= $filters['shop'] === $shopName ? 'selected' : '' ?>><?= e($shopName) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="reports-filter-type">
            Type mouvement
            <select name="type">
                <option value="">Tous</option>
                <?php foreach ($allowedTypes as $type): ?>
                    <option value="<?= e($type) ?>" <?= $filters['type'] === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="reports-filter-article">
            Article
            <input type="search" name="article" value="<?= e($filters['article']) ?>" placeholder="Reference, nom ou ID">
        </label>
        <button class="button reports-button-filter" type="submit"><span class="ui-icon ui-icon-filter" aria-hidden="true"></span>Filtrer</button>
        <a class="button reports-button-reset" href="<?= e(url('index.php?page=rapports')) ?>"><span class="ui-icon ui-icon-close" aria-hidden="true"></span>Reinitialiser</a>
    </form>
</section>

<section class="reports-kpi-grid">
    <article class="reports-kpi-card reports-kpi-card--sales">
        <div class="reports-kpi-icon"><span class="ui-icon ui-icon-cart" aria-hidden="true"></span></div>
        <div class="reports-kpi-content">
            <div class="reports-kpi-label">Journaux vente</div>
            <div class="reports-kpi-value"><?= e((int) ($summary['sales_journals'] ?? 0)) ?></div>
            <div class="reports-kpi-caption">Ventes filtrees</div>
        </div>
    </article>
    <article class="reports-kpi-card reports-kpi-card--amount">
        <div class="reports-kpi-icon"><span class="ui-icon ui-icon-wallet" aria-hidden="true"></span></div>
        <div class="reports-kpi-content">
            <div class="reports-kpi-label">Montant ventes</div>
            <div class="reports-kpi-value"><?= money($summary['sales_total'] ?? 0) ?></div>
            <div class="reports-kpi-caption">Total Royal estime</div>
        </div>
    </article>
    <article class="reports-kpi-card reports-kpi-card--moves">
        <div class="reports-kpi-icon"><span class="ui-icon ui-icon-move" aria-hidden="true"></span></div>
        <div class="reports-kpi-content">
            <div class="reports-kpi-label">Mouvements</div>
            <div class="reports-kpi-value"><?= e((int) ($summary['movements'] ?? 0)) ?></div>
            <div class="reports-kpi-caption">Lignes mvt filtrees</div>
        </div>
    </article>
    <article class="reports-kpi-card reports-kpi-card--invoices">
        <div class="reports-kpi-icon"><span class="ui-icon ui-icon-receipt" aria-hidden="true"></span></div>
        <div class="reports-kpi-content">
            <div class="reports-kpi-label">Factures</div>
            <div class="reports-kpi-value"><?= e((int) ($summary['invoices'] ?? 0)) ?></div>
            <div class="reports-kpi-caption">Receptions fournisseur</div>
        </div>
    </article>
</section>

<section class="panel panel-accent panel-accent-green">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Ventes</p>
            <h2><span class="ui-icon ui-icon-cart" aria-hidden="true"></span>Ventes par point de vente</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>Point</th><th>Journaux</th><th>Total estime</th></tr></thead>
            <tbody>
            <?php foreach ($salesByShop as $row): ?>
                <tr>
                    <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="Journaux"><?= e($row['journals']) ?></td>
                    <td data-label="Total"><?= money($row['total']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$salesByShop): ?>
                <tr><td colspan="3">Aucune vente pour ces criteres.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
</div>

<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Mouvements</p>
            <h2><span class="ui-icon ui-icon-move" aria-hidden="true"></span>Totaux par type</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>Type</th><th>Lignes</th><th>Journaux</th><th>Quantite</th><th>Total valorise</th></tr></thead>
            <tbody>
            <?php foreach ($movementsByType as $row): ?>
                <?php
                $qty = (float) ($row['quantity'] ?? 0);
                $badge = operation_badge($row + ['qt' => $qty]);
                ?>
                <tr>
                    <td data-label="Type"><span class="operation-badge <?= e($badge[1]) ?>"><?= e($badge[0]) ?></span></td>
                    <td data-label="Lignes"><?= e($row['line_count']) ?></td>
                    <td data-label="Journaux"><?= e($row['journals']) ?></td>
                    <td data-label="Quantite" class="<?= $qty < 0 ? 'text-danger' : ($qty > 0 ? 'text-success' : '') ?>"><?= e(number_format($qty, 3, '.', ' ')) ?></td>
                    <td data-label="Total"><?= money($row['total']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$movementsByType): ?>
                <tr><td colspan="5">Aucun mouvement pour ces criteres.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="panel panel-accent panel-accent-purple">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Journaux</p>
            <h2><span class="ui-icon ui-icon-history" aria-hidden="true"></span>Ventes recentes</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Date</th><th>Point</th><th>Lignes</th><th>Versement</th><th>Difference</th><th>Statut</th></tr></thead>
            <tbody>
            <?php foreach ($recentSales as $row): ?>
                <tr>
                    <td data-label="No">#<?= e($row['no_activite']) ?></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal']) ?></td>
                    <td data-label="Point"><?= e($row['c_point']) ?></td>
                    <td data-label="Lignes"><?= e($row['nb_ligne']) ?></td>
                    <td data-label="Versement"><?= money($row['Montant']) ?></td>
                    <td data-label="Difference"><?= money($row['difference_aparafa']) ?></td>
                    <td data-label="Statut"><span class="badge badge-readonly"><?= e($row['status']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$recentSales): ?>
                <tr><td colspan="7">Aucun journal vente pour ces criteres.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="panel panel-accent panel-accent-teal">
    <div class="panel-head">
        <div>
            <p class="panel-kicker">Historique</p>
            <h2><span class="ui-icon ui-icon-history" aria-hidden="true"></span>Mouvements recents</h2>
        </div>
    </div>
    <div class="table-wrap responsive-table">
        <table>
            <thead><tr><th>No</th><th>Type</th><th>Reference</th><th>Article</th><th>Point</th><th>QT</th><th>Prix</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($recentMovements as $row): ?>
                <?php
                $qt = (float) ($row['qt'] ?? 0);
                $badge = operation_badge($row);
                ?>
                <tr>
                    <td data-label="No">#<?= e($row['id_mvt']) ?></td>
                    <td data-label="Type"><span class="operation-badge <?= e($badge[1]) ?>"><?= e($badge[0]) ?></span></td>
                    <td data-label="Reference"><?= e($row['reference_x']) ?></td>
                    <td data-label="Article"><strong><?= e($row['nom_x']) ?></strong></td>
                    <td data-label="Point"><?= e($row['nom_client_fournisseur']) ?></td>
                    <td data-label="QT" class="<?= $qt < 0 ? 'text-danger' : ($qt > 0 ? 'text-success' : '') ?>"><?= e(($qt > 0 ? '+' : '') . number_format($qt, 3, '.', ' ')) ?></td>
                    <td data-label="Prix"><?= money($row['prix_unitaire']) ?></td>
                    <td data-label="Date"><?= e($row['Date_du_Journal_mvt']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$recentMovements): ?>
                <tr><td colspan="8">Aucun mouvement pour ces criteres.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
