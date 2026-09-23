<!DOCTYPE html>
<html>
<head>
  <title>Globale Reference V5.5</title>
  <!---add bootstrap css--->
  <script src="js/jquery-3.5.1.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet"> 
  <!---add other css--->
  <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style_Index.css">
</head>
<body>
<!--------------------------------------->
<h4 class="text-center">VENTE DETAILS FORME LEGERE</h4>
<h4 class="text-center"><a href="home_char.php">RETOURS</a></h4>
<?php
//Connect to BD
include('connect.php');

    $query_stock_inventaire = 'SELECT *,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = "vente" AND nom_client_fournisseur = "Ambato_Tantely" GROUP BY mvt.numero_commande_stock ORDER BY id DESC;';
  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array());
  ?>
   <table class="table-info table">
        <thead>
        <tr>
        <th>POINT DE VENTE</th>
        <th>DATE</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
$point_de_vente = $donnees['nom_client_fournisseur'];                    
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo$donnees['nom_client_fournisseur']; ?></td>
        <td><a href="vente_recap_details_ambato_1.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $donnees['no_activite'];?>"><?php echo $donnees['description_date']; ?></a></td>
      </tr>
<?php
$j = $j+1;
}
$q->closeCursor();
?>
        </tbody>
    </table>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>