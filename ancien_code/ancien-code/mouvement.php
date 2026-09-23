<?php
$id_x = 1; // produit

$sql = "
SELECT 
SUM(CASE WHEN type_de_mvt='stock' THEN qt ELSE 0 END) -
SUM(CASE WHEN type_de_mvt='vente' THEN qt ELSE 0 END) AS stock_restant
FROM mvt
WHERE id_x = ?
";
$q = $bdd->prepare($sql);
$q->execute([$id_x]);
$data = $q->fetch();

echo "Stock restant : ".$data['stock_restant'];
