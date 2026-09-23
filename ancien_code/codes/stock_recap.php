<!DOCTYPE html>
<html>
<head>
	<title>Gerer Article</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="css/top20.css">
</head>
<body style="background: #343a40">
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php");
 include('connect.php');
 //Suppression alert
 $msg_supression ="";
 if (isset($_COOKIE['msg_supression'])) 
{
   $msg_supression=$_COOKIE['msg_supression'];
 }
//Show Less result
 $page = 0;
 if (isset($_COOKIE['page'])) 
{
   $page = $_COOKIE['page'];
 }
 //get rows number
 	$query_nb_r = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'stock' GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock";
	$query_nb_r = $bdd->prepare($query_nb_r);
	$query_nb_r->execute(array());
	$page_max = $query_nb_r -> rowCount ();
	$query_nb_r -> closeCursor();
	$page_max = floor($page_max/100);
	if ($page > $page_max) {
		$page = 0;

	}
	if ($page <= 0) {
		$page = 0;
	}
 //------------------------------------
  if (isset($_COOKIE['all_result'])) 
{
	$xp = 0;
//Query to liste All Journal de vente
$query_gp ="SELECT *,description_date,nom_client_fournisseur,numero_commande_stock FROM mvt WHERE type_de_mvt='stock' AND (status='General_Inventory' OR status !='OFF') GROUP BY description_date,nom_client_fournisseur ORDER BY Date_du_Journal_mvt DESC;" ;
	$result = "<a href='stock_recap.php'>SHOW 100 LATEST RESULTS ONLY?</a>";
	$page = 'all';
}else{
	$xp = $page*100;
	//$x = 20;
	$query_gp ="SELECT *,description_date,nom_client_fournisseur,numero_commande_stock FROM mvt WHERE type_de_mvt='stock' AND (status='General_Inventory' OR status !='OFF') GROUP BY description_date,nom_client_fournisseur ORDER BY Date_du_Journal_mvt DESC LIMIT $xp,100 ;" ;
	$result = 'Page '.($page+1)."/".($page_max+1)." (<a href='activate_all_stock_recap.php'>SHOW ALL RESULTS?</a>)";	
}
$query_gp_inventory = $bdd->prepare($query_gp);
 $query_gp_inventory->execute(array());
//-------------------------

 ?>
 	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
 <div class="container">
  <h2 class="top20_style">LISTES DES STOCKS<input name="vente_id_array" class="btn btn-light float-right" type="password" oninput="unlock($(this));"></h2>
  <h5 class="text-center"><span class="text-danger"><?php echo $msg_supression; ?></span><br><span class="text-info"><b><?php echo $result; ?></b></span></h5>
  <div class="text-center">
    <a title="PREVIOUS" style="<?php if ($page <= 0 AND $page = 'all') {echo "pointer-events: none";} ?>" class="center" href="page_moin_stock.php?page=<?php echo $page; ?>"><img src="img/flech_gche.png" height="30" width="30" background alt="Edit" /></a>
    <a title="NEXT" style="<?php if ($page >= $page_max AND $page = 'all') {echo "pointer-events: none";} ?>" class="center" href="page_plus_stock.php?page=<?php echo $page; ?>"><img src="img/flech_dte.png" height="30" width="30" background alt="Edit" /></a>
    </div>
    <br>
  <ul class="list-group">
 <?php
 
 $color = "list-group-item-light";
 $colort = "text-danger";
 $point_de_vente = "";
 $user_stock_prep = "";
 $target = "";
 $id = 0;
 $n = 0;
 while ($donnees = $query_gp_inventory -> fetch())
{
$n = $n + 1;
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
		    $color = "list-group-item list-group-item-dark";
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
		    $color = "list-group-item list-group-item-success";
		    break;
		 case "MoraranoCh":
		    $color = "list-group-item list-group-item-dark";
		    break;
		case "Soalazaina":
		    $color = "list-group-item list-group-item-info";
		    break;
		  default:
		    $color = "list-group-item list-group-item-success";
		}
		switch ($point_de_vente) {

		     case "Ambato_Tantely":
		    $colort = "text-warning";
		    break;
		  case "Ambaibo_Tole":
		    $colort = "text-light";
		    break;
		  case "Amparafa":
		    $colort = "text-info";
		    break;
		case "MoraranoCh":
		    $colort = "text-light";
		    break;
		case "Esther_Q":
		    $colort = "text-dark";
		    break;
		case "Esther_P":
		    $colort = "text-dark";
		    break;
		  default:
		    $colort = "text-success";
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
		$query_no_activity = "SELECT * FROM mvt WHERE description_date = ? AND nom_client_fournisseur = ? GROUP BY numero_commande_stock;";
		$query_no_activity = $bdd->prepare($query_no_activity);
		$query_no_activity -> execute(array($donnees['description_date'],$point_de_vente));
		$nb_history = 0;
		while ($donnees1 = $query_no_activity -> fetch())
		{
			$query_history = "SELECT * FROM history WHERE  type = 'Mvt_Stock' AND after_change = ?";
			$query_history = $bdd->prepare($query_history);
			$query_history -> execute(array($donnees1['numero_commande_stock']));
			//Number of Line
			$nb_history = ($query_history->rowCount ()) + $nb_history;
			$query_history -> closeCursor();
		}
		$query_no_activity -> closeCursor();

		
		$noti = '';
		if ($nb_history > 0) {
			$noti = " <span title = 'CHANGE EXIST' class='w3-badge w3-red'>!</span> ";
		}
if ($donnees['status'] == 'OFF') {
	# code...
} else {
	# code...


?>

    <li class="table btn-brown text-left <?php echo$colort; ?> container" data-target="#<?php echo $donnees['numero_commande_stock']; ?>"><a target="_blank" rel="noopener noreferrer" href="stock_recap_details.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>"><span class="w3-badge w3-gray"><?php echo (($xp+$n)); ?></span> <?php echo $donnees['description_date'].' | '."<u style='font-weight: bold'>$point_de_vente</u>".' | by '.$user_stock_prep.' on '.$date_time.$noti;?></a><span class="w3-badge w3-margin-right w3-red"><?php echo $GI; ?></span><a id="<?php echo $id_name; ?>" style="<?php echo $state_suppr; ?>;" title='SUPPRIMER' class="float-right" href="delete_stock.php?numero_commande_stock=<?php echo $donnees['numero_commande_stock']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit"/></a>
    <?php if ($donnees['status'] == 'General_Inventory') { ?> 	
    <a target="_blank" rel="noopener noreferrer" href="stock_recap_details1.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>">ØØØØ</a>
    <?php } else { ?>
    <a target="_blank" rel="noopener noreferrer" href="stock_recap_details1.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>">↓↓↓↓</a></li>
<?php
}
}
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