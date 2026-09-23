<?php
$saleNo = (int) ($_GET['sale'] ?? 0);
$isPreview = isset($_GET['preview']);
try {
    if ($saleNo > 0) {
        $lines = $salesService->saleDetails($saleNo);
        $recap = $salesService->recap($saleNo);
    } elseif ($isPreview) {
        $lines = $salesService->cart($auth->username());
        $recap = null;
    } else {
        $lines = [];
        $recap = null;
    }
} catch (Throwable $e) {
    $lines = [];
    $recap = null;
    flash('danger', 'Impossible de charger le recu: ' . $e->getMessage());
}
$total = 0.0;
?>
<section class="receipt-toolbar">
    <button class="button button-primary" onclick="window.print()"><span class="ui-icon ui-icon-printer" aria-hidden="true"></span>Imprimer A4</button>
    <button class="button button-soft" onclick="alert('Export PDF: installe dompdf ou une bibliotheque PDF compatible, puis connecte cette action.')"><span class="ui-icon ui-icon-download" aria-hidden="true"></span>Export PDF</button>
    <a class="button button-soft" href="<?= e(url('index.php?page=vente')) ?>"><span class="ui-icon ui-icon-arrow-left" aria-hidden="true"></span>Retour</a>
</section>
<section class="receipt-a4">
    <header class="receipt-head">
        <div>
            <h2>Royal Inventory</h2>
            <p>Recu de vente <?= $saleNo ? '#' . e($saleNo) : '(apercu panier)' ?></p>
        </div>
        <div><?= e(date('d/m/Y H:i')) ?></div>
    </header>
    <?php if (!$lines): ?>
        <p class="empty">Aucune ligne a afficher.</p>
    <?php else: ?>
        <table class="receipt-table">
            <thead><tr><th>Ref</th><th>Designation</th><th>QT</th><th>PU</th><th>Montant</th></tr></thead>
            <tbody>
            <?php foreach ($lines as $line): ?>
                <?php
                $qty = abs((float) ($line['qt'] ?? 0));
                $price = (float) ($line['prix_unitaire'] ?? $line['prix_de_vente'] ?? 0);
                $amount = $qty * $price;
                $total += $amount;
                ?>
                <tr>
                    <td><?= e($line['reference_x']) ?></td>
                    <td><?= e($line['nom_x']) ?></td>
                    <td><?= e($qty) ?></td>
                    <td><?= money($price) ?></td>
                    <td><?= money($amount) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot><tr><th colspan="4">TOTAL</th><th><?= money($total) ?></th></tr></tfoot>
        </table>
        <?php if ($recap): ?>
            <p class="receipt-note"><?= e($recap['note_general'] ?? '') ?></p>
        <?php endif; ?>
    <?php endif; ?>
</section>
