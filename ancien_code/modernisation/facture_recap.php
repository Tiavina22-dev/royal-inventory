<!DOCTYPE html>
<html>
<head>
	<title>LISTE FACTURE</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
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
 <div class="container">
  <h2>LISTE DES FACTURES DU FOURNISSEUR <input name="vente_id_array" class="btn btn-light float-right" type="password" oninput="unlock($(this));"></h2>
  <ul class="list-group">
 <?php
 include('connect.php');
 //Query to liste FOR GROUPAGE INVENTORY
$query_gp ="SELECT *,description_date,nom_client_fournisseur,numero_commande_stock FROM mvt WHERE type_de_mvt='facture' GROUP BY description_date,nom_client_fournisseur ORDER BY Date_du_Journal_mvt DESC;" ;
$query_gp_inventory = $bdd->prepare($query_gp);

 $query_gp_inventory->execute(array());
 $color = "list-group-item list-group-item-danger";
 $point_de_vente = "";
 $user_stock_prep = "";
 $target = "";
 $id = 0;
 while ($donnees = $query_gp_inventory -> fetch())
{

	$point_de_vente = $donnees['nom_client_fournisseur'];
	$user_stock_prep = $donnees['user_mvt'];
	//Comptage de X if EXIST
	$query_facture_x = "SELECT * FROM mvt WHERE id_x = 134655 AND date_time = ?";
	$query_facture_x  = $bdd->prepare($query_facture_x);
	$query_facture_x -> execute(array($donnees['date_time']));
	$nb_x = $query_facture_x ->rowCount ();
	$query_facture_x -> closeCursor();
	if ($nb_x == 0) {
		$nb_x = '';
	}else{$nb_x = $nb_x.'x';}
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
	if ($color == "list-group-item list-group-item-danger") {
		$color = "list-group-item list-group-item-success";
	} else {
		$color = "list-group-item list-group-item-danger";
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

?>

    <li class="<?php echo $color;?>" data-target="#<?php echo $donnees['numero_commande_stock']; ?>"><a target="_blank" rel="noopener noreferrer" href="facture_recap_details.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>"><?php echo $donnees['description_date'].' | '.$point_de_vente.' | by '.$user_stock_prep.' on '.$date_time;?></a> <span class="w3-badge w3-margin-right w3-red"><?php echo $nb_x; ?></span> <span class="w3-badge w3-margin-right w3-red"><?php echo $GI; ?></span><a id="<?php echo $id_name; ?>" style="<?php echo $state_suppr; ?>;" title='SUPPRIMER' class="float-right" href="delete_facture.php?numero_commande_stock=<?php echo $donnees['numero_commande_stock']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit"/></a></li>
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