<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Ref X</title>
  <!-- Font Awesome -->
  <!--
  <link rel="stylesheet" href="css/all.css">
  -->
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <!--
  <link rel="stylesheet" href="css/style.css">
  -->
  <link rel="stylesheet" href="css/mota.css">
  <link rel="stylesheet" href="css/w3.css">
  <style type="text/css">
    .no-uppercase{text-transform: none;}
  </style>
</head>
<body style="background: #CEE3F6">
<?php include("header.php"); ?>
<?php include("footer.php"); ?>

<?php
include('connect.php');
// 1. Mamorona liste de codes A1A → Z9Z
$letters = range('A', 'Z');
$digits  = range(1, 9);

$all_codes = [];
foreach ($letters as $first) {
    foreach ($digits as $num) {
        foreach ($letters as $second) {
            $all_codes[] = $first . $num . $second;
        }
    }
}

// 2. Maka codes efa misy ao amin'ny table product
$stmt = $bdd->query("SELECT reference_x FROM produit");
$existing_codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 3. Mitahiry codes mbola tsy misy
$unused_codes = array_diff($all_codes, $existing_codes);
sort($unused_codes); // milahatra A→Z

$cols = 10; // isan'ny colonne

?>
<br>
<br>
<br>
<br>
<br>
<div class="container" style="text-align:center; margin-top:20px;">
    <h3>Codes mbola tsy nampiasaina</h3>

    <table border="1" cellpadding="5" style="margin:auto; text-align:center;">
        <?php 
        $i = 0;
        foreach ($unused_codes as $code): 
            if ($i % $cols === 0) echo "<tr>";
        ?>
            <td><?php echo htmlspecialchars($code); ?></td>
        <?php 
            $i++;
            if ($i % $cols === 0) echo "</tr>";
        endforeach;
        // Raha tsy feno ny dernière ligne
        if ($i % $cols !== 0) {
            $remaining = $cols - ($i % $cols);
            for ($j=0; $j<$remaining; $j++) echo "<td></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
<br>
<div class="container" style="text-align:center; margin-top:20px;">
<h3>Produit tsisy mvt</h3>
</div>
<br>

<?php

//PREVENT DELETION PRODUCT WHO HAVE MVT
     //TABLE MVT
  
    $query_stock_inventaire = "
    SELECT *
    FROM produit
    WHERE reference_x REGEXP '^[A-Z][1-9][A-Z]$'
    ORDER BY nom_x
";

  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array());
?>


   <table class="table table-dark container">
        <thead>
        <tr class="btn-brown">
        <th>No</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>FANAMARIHANA</th>
        <th>DELETE</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$no = 1; // compteur affichage

while ($donnees = $q->fetch())
{
    // MVT
    $query_mvt = $bdd->prepare('SELECT 1 FROM mvt WHERE id_x = ? LIMIT 1');
    $query_mvt->execute([$donnees['id_x']]);
    $nb_line_mvt = $query_mvt->rowCount();
    $query_mvt->closeCursor();

    // STOCK PREP
    $query_stock_prep = $bdd->prepare('SELECT 1 FROM stock_prep WHERE id_x = ? LIMIT 1');
    $query_stock_prep->execute([$donnees['id_x']]);
    $nb_line_stock_prep = $query_stock_prep->rowCount();
    $query_stock_prep->closeCursor();

    // COMMANDE
    $query_commande = $bdd->prepare('SELECT 1 FROM commande WHERE id_x = ? LIMIT 1');
    $query_commande->execute([$donnees['id_x']]);
    $nb_line_commande = $query_commande->rowCount();
    $query_commande->closeCursor();

    $total = $nb_line_mvt + $nb_line_stock_prep + $nb_line_commande;

    // 👉 AFFICHAGE UNIQUEMENT SI PAS DE MVT
    if ($total == 0) {
?>
        <tr>
            <td><?= $no ?></td>
            <td><?= htmlspecialchars($donnees['reference_x']) ?></td>
            <td><?= htmlspecialchars($donnees['nom_x']) ?></td>
            <td></td>
            <td>
                <a href="delete_produitg.php?id_x=<?= $donnees['id_x'] ?>"
                   onclick="return confirmationDelete('Do you want to DELETE this line?');">
                   <img src="img/deleteicon.png" height="30" width="30" alt="Delete">
                </a>
            </td>
        </tr>
<?php
        $no++; // ⬅️ miakatra ICI SEULEMENT
    }
}
?>

        </tbody>
    </table>
    <br>
    <br>
<br>
<br>
 <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>

</body>
</html>