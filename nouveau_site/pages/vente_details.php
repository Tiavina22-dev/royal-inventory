<?php
$saleNo = (int) ($_GET['sale'] ?? 0);
try {
    $lines = $saleNo > 0 ? $salesService->saleDetails($saleNo) : [];
    $recap = $saleNo > 0 ? $salesService->recap($saleNo) : null;
} catch (Throwable $e) {
    $lines = [];
    $recap = null;
    flash('danger', 'Impossible de charger les details: ' . $e->getMessage());
}
$sum = 0.0;
?>
<section class="panel panel-accent panel-accent-purple">
    <div class="panel-head">
        <h2><span class="ui-icon ui-icon-receipt" aria-hidden="true"></span>Journal #<?= e($saleNo) ?></h2>
        <div class="actions-row">
            <a class="button button-soft" href="<?= e(url('index.php?page=recu&sale=' . $saleNo)) ?>"><span class="ui-icon ui-icon-printer" aria-hidden="true"></span>Recu / impression</a>
            <a class="button button-soft" href="<?= e(url('index.php?page=vente')) ?>"><span class="ui-icon ui-icon-arrow-left" aria-hidden="true"></span>Retour vente</a>
        </div>
    </div>
    <?php if (!$lines): ?>
        <p class="empty">Vente introuvable.</p>
    <?php else: ?>
        <div class="table-wrap responsive-table">
            <table>
                <thead><tr><th>Reference</th><th>Produit</th><th>QT</th><th>PU</th><th>Montant</th><th>Note</th><th>Par</th></tr></thead>
                <tbody>
                <?php foreach ($lines as $line): ?>
                    <?php $amount = abs((float) $line['qt']) * (float) $line['prix_unitaire']; $sum += $amount; ?>
                    <tr>
                        <td data-label="Reference"><?= e($line['reference_x']) ?></td>
                        <td data-label="Produit"><?= e($line['nom_x']) ?></td>
                        <td data-label="QT"><?= e(abs((float) $line['qt'])) ?></td>
                        <td data-label="PU"><?= money($line['prix_unitaire']) ?></td>
                        <td data-label="Montant"><?= money($amount) ?></td>
                        <td data-label="Note"><?= e($line['note']) ?></td>
                        <td data-label="Par"><?= e($line['user_mvt']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot><tr><th colspan="4">Total</th><th><?= money($sum) ?></th><th colspan="2"></th></tr></tfoot>
            </table>
        </div>
        <?php if ($recap): ?>
            <div class="recap-grid">
                <div><span>Versement</span><strong><?= money($recap['Montant']) ?></strong></div>
                <div><span>Difference</span><strong><?= money($recap['difference_aparafa']) ?></strong></div>
                <div><span>Resolution</span><strong><?= money($recap['resolution']) ?></strong></div>
                <div><span>Mihoatra</span><strong><?= money($recap['mihoatra']) ?></strong></div>
            </div>
            <pre class="history-box"><?= e($recap['history'] ?? '') ?></pre>
        <?php endif; ?>
    <?php endif; ?>
</section>
