<!DOCTYPE html>
<html>
<head>
	<title>STOCK ANDREFANA</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 <div class="container">
 	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
  <h2 class="text-info">LISTE STOCK ANDREFANA</h2>
  <ul class="list-group">
 <?php
 include('connect.php');
 //Query to liste FOR GROUPAGE INVENTORY
$query_gp ="SELECT *,description_date,nom_client_fournisseur,numero_commande_stock FROM mvt_calc WHERE type_de_mvt='stock' GROUP BY description_date,nom_client_fournisseur ORDER BY numero_commande_stock DESC;" ;
$query_gp_inventory = $bdd->prepare($query_gp);

 $query_gp_inventory->execute(array());
 $color = "list-group-item-light";
 $point_de_vente = "";
 $user_stock_prep = "";
 $target = "";
 while ($donnees = $query_gp_inventory -> fetch())
{

	$point_de_vente = $donnees['nom_client_fournisseur'];
	$user_stock_prep = $donnees['user_mvt'];
	$target = $donnees['numero_commande_stock'];
		switch ($point_de_vente) {
		  case "Ambato_Tantely":
		    $color = "list-group-item list-group-item-danger";
		    break;
		  case "Ambaibo_Tole":
		    $color = "list-group-item list-group-item-warning";
		    break;
		  case "Ambaibo_loko":
		    $color = "list-group-item list-group-item-secondary";
		    break;
		  case "Amparafa":
		    $color = "list-group-item list-group-item-primary";
		    break;
		  case "Ambaibo_Electronique":
		    $color = "list-group-item list-group-item-info";
		    break;
		    case "Ambato_veve_photo":
		    $color = "list-group-item list-group-item-dark";
		    break;
		  default:
		    $color = "list-group-item list-group-item-light";
		}
//echo 'point_de_vente:'.$point_de_vente.' | '.'color:'.$color;
//echo $target;
?>

    <li class="<?php echo $color;?>" data-target="#<?php echo $donnees['numero_commande_stock']; ?>"><a href="stock_recap_details_andrefana.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>"><?php echo $donnees['description_date'].' | '.$point_de_vente.' | by '.$user_stock_prep;?></a></li>
<?php
}
$query_gp_inventory->closeCursor();
 ?>
   </ul>
</div>
<br>
<br>
<br>
	<br>
	<br>
	<br>
</body>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>