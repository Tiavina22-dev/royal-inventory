<!DOCTYPE html>
<html>
<head>
  <title>VENTE LEGERE</title>
  <!---add bootstrap css--->
  <script src="js/jquery-3.5.1.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet"> 
  <!---add other css--->
  <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style_Index.css">
  <link rel="stylesheet" href="css/mota.css">
</head>
<body>
<?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
<!--------------------------------------->
<h4 class="text-center text-warning">VENTE DETAILS FORME LEGERE</h4>
<h4 class="text-center text-light"><a href="vente_recap.php">Forme detailée</a> | <a href="home_char.php">Home</a></h4>
<?php
//Connect to BD
include('connect.php');

    $query_stock_inventaire = 'SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = "vente" GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC';
  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array());
  ?>
 
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
$point_de_vente = $donnees['nom_client_fournisseur']; ?>
<div class="center container">
<?php
echo "<br><span style='font-weight: bold' class='text-pink'>".$j.") <a href='vente_recap_details.php?nom_client_fournisseur=".$point_de_vente."&description_date=".$donnees['description_date']."&no_activite=".$donnees['no_activite']."' target='_blank' rel='noopener noreferrer' class='text-info'>".$donnees['description_date'].' <u class="text-success">' .$point_de_vente."</U></a> by | <u class='text-light'>".$donnees['user_mvt']; ?></u></span><br>
</div>
<!------------------------------------------------->
<?php
$j = $j+1;
}
$q->closeCursor();
?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>