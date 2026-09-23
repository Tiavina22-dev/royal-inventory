<?php
include('connect.php');
/*
 PRODUITS :
 - reference_x format A1Z (lettre + chiffre + lettre)
 - PAS DE mouvement
*/

$sql = "
SELECT
    p.id_x,
    p.reference_x,
    p.nom_x
FROM produit p
WHERE
    UPPER(TRIM(p.reference_x)) REGEXP '^[A-Z][0-9][A-Z]$'
    AND NOT EXISTS (
        SELECT 1
        FROM mvt m
        WHERE m.produit_id = p.id_x
    )
ORDER BY p.reference_x
";

$stmt = $bdd->prepare($sql);
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang=\"fr\">
<head>
<meta charset=\"UTF-8\">
<title>Produits sans mouvement</title>

<style>
body{
    font-family:Arial, sans-serif;
    background:#f4f6f8;
}
.box{
    width:800px;
    margin:30px auto;
    background:#fff;
    padding:20px;
    border-radius:6px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:#007bff;
    color:#fff;
}
th,td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
    color:#000; /* tsy miova na dark mode */
}
tr:nth-child(even){
    background:#f2f2f2;
}
.empty{
    color:#999;
    font-style:italic;
}
</style>
</head>

<body>

<div class=\"box\">
<h3>Produits sans mouvement (A1Z → Z9Z)</h3>

<table>
<tr>
    <th>ID</th>
    <th>Référence</th>
    <th>Produit</th>
</tr>

<?php if($produits): ?>
    <?php foreach($produits as $p): ?>
    <tr>
        <td><?= $p['id_x'] ?></td>
        <td><?= htmlspecialchars($p['reference_x']) ?></td>
        <td><?= htmlspecialchars($p['nom_x']) ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan=\"3\" class=\"empty\">
            Aucun produit sans mouvement
        </td>
    </tr>
<?php endif; ?>
</table>
</div>

</body>
</html>
