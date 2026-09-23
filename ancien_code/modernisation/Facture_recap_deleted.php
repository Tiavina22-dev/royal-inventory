<!DOCTYPE html>
<html>
<head>
	<title>DELETED INVOICE</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
</head>
<body style="background: #EAE8E8">
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
 <div class="container">
  <h2>FACTURE SUPPRIMEE</h2>
  <ul class="list-group">
 <?php
 include('connect.php');
 //Query to liste FOR GROUPAGE INVENTORY
$query_gp ="SELECT *,description_date,nom_client_fournisseur,numero_commande_stock FROM mvt_history WHERE type_de_mvt='facture' GROUP BY description_date,nom_client_fournisseur ORDER BY numero_commande_stock DESC;" ;
$query_gp_inventory = $bdd->prepare($query_gp);

 $query_gp_inventory->execute(array());
 $color = "list-group-item-light";
 $point_de_vente = "";
 $user_stock_prep = "";
 $target = "";
 $id = 0;
 while ($donnees = $query_gp_inventory -> fetch())
{

	$point_de_vente = $donnees['nom_client_fournisseur'];
	$user_stock_prep = $donnees['user_mvt'];
	$x = chr(35).'/0-9-';
	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $donnees['date_time'], $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date_time = $res_regex[1];
        $date_time = DateTime::createFromFormat('Y-m-d', $date_time);
		$date_time = $date_time -> format('l d F Y');

        } else {
        $date_time = '####';
        }
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
		case "Soalazaina":
		    $color = "list-group-item list-group-item-success";
		    break;
		  default:
		    $color = "list-group-item list-group-item-light";
		}
//echo 'point_de_vente:'.$point_de_vente.' | '.'color:'.$color;
//echo $target;
		$state_suppr = "pointer-events: none";
		if ($donnees['status'] == 'OFF') {
			$id_name = 'OFF';
		}
		else {
			$id = $id +1;
			$id_name = $id;
		}
		
		$GI = '';
		if ($donnees['status'] == 'General_Inventory') {
			$GI = 'Inventaire';
		}
		//Check history change
		$query_history = "SELECT * FROM history WHERE  type = 'Mvt_Facture' AND after_change = ?";
		$query_history = $bdd->prepare($query_history);
		$query_history -> execute(array($donnees['numero_commande_stock']));
		//Number of Line
		$nb_history = $query_history->rowCount ();
		$query_history -> closeCursor();
		$noti = '';
		if ($nb_history > 0) {
			$noti = " <span title = 'CHANGE EXIST' class='w3-badge w3-red'>!</span> ";
		}

?>

    <li class="<?php echo $color;?>" data-target="#<?php echo $donnees['numero_commande_stock']; ?>"><a target="_blank" rel="noopener noreferrer" href="facture_recap_details_deleted.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>"><?php echo $donnees['description_date'].' | '.$point_de_vente.' | by '.$user_stock_prep.' on '.$date_time.$noti;?></a><span class="w3-badge w3-margin-right w3-red"><?php echo $GI; ?></span></li>
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
</body>
<script>
function unlock(e){
	var id = <?php echo json_encode($id); ?>;
	var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2022") {
    	for (var i = 1; i <= id; i++) {
    	//alert(i);
    	$("#"+i).removeAttr("style");
    	}
	}
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>