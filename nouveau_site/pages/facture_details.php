<?php
$invoiceNo = (int) ($_GET['invoice'] ?? 0);
try {
    $lines = $invoiceNo > 0 ? $db->fetchAll(
        "SELECT m.*, p.reference_x, p.nom_x
         FROM mvt m
         LEFT JOIN produit p ON p.id_x = m.id_x
         WHERE m.type_de_mvt = 'facture' AND m.numero_commande_stock = ?
         ORDER BY m.id_mvt ASC",
        [$invoiceNo]
    ) : [];
} catch (Throwable $e) {
    $lines = [];
    flash('danger', 'Impossible de charger la facture: ' . $e->getMessage());
}
$total = 0.0;
?>
<section class="receipt-toolbar">
    <button class="button button-primary" onclick="window.print()"><span class="ui-icon ui-icon-printer" aria-hidden="true"></span>Imprimer A4</button>
    <a class="button button-soft" href="<?= e(url('index.php?page=factures')) ?>"><span class="ui-icon ui-icon-arrow-left" aria-hidden="true"></span>Retour</a>
</section>
<section class="receipt-a4">
    <header class="receipt-head">
        <div>
            <h2>Royal Inventory</h2>
            <p>Facture #<?= e($invoiceNo) ?></p>
        </div>
        <div><?= e(date('d/m/Y H:i')) ?></div>
    </header>
    <?php if (!$lines): ?>
        <p class="empty">Aucune ligne facture a afficher.</p>
    <?php else: ?>
        <p><strong>Fournisseur:</strong> <?= e($lines[0]['nom_client_fournisseur']) ?> | <strong>Date:</strong> <?= e($lines[0]['Date_du_Journal_mvt'] ?: $lines[0]['description_date']) ?></p>
        <table class="receipt-table">
            <thead><tr><th>Ref</th><th>Designation</th><th>QT</th><th>PU fournisseur</th><th>Montant</th></tr></thead>
            <tbody>
            <?php foreach ($lines as $line): ?>
                <?php
                $amount = (float) $line['qt'] * (float) $line['prix_unitaire'];
                $total += $amount;
                ?>
                <tr>
                    <td><?= e($line['reference_x']) ?></td>
                    <td><?= e($line['nom_x']) ?></td>
                    <td><?= e($line['qt']) ?></td>
                    <td><?= money($line['prix_unitaire']) ?></td>
                    <td><?= money($amount) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot><tr><th colspan="4">TOTAL</th><th><?= money($total) ?></th></tr></tfoot>
        </table>
    <?php endif; ?>
</section>
