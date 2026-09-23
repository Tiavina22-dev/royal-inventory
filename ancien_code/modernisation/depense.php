<!DOCTYPE html>
<html>
<head>
	<title>Depense</title>
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
 <?php include("footer.php"); ?>
<!--------------------------------------->
<?php
include('connect.php');
//initialisation for Select and Mouveau
#####################MULTI-SESSION######################
$query_depense ="SELECT * FROM depense LEFT JOIN mvt ON numero_commande_stock = activity_no WHERE type_de_mvt = 'vente' GROUP BY id_depense ORDER BY id_depense DESC";

$query_gp_inventory = $bdd->prepare($query_depense);
 $query_gp_inventory->execute(array());
//-------------------------

 ?>
 	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<div class="text-center">
    <h2 class="top20_style">DEPENSES</h2>
    </div>
	<br>
	<br>
  <ul class="list-group">
  	<li class="table btn-brown text-left container center"><a><h4 style='font-weight: bold'>N | ID <|> Date du Journal | Point de vente | Montant | Motif > > > > > > > > > > > > > > | User | Creat | Mod | Sup</h4></a></li>
 <?php
 
 $color = "list-group-item-light";
 $colort = "text-danger";
 $point_de_vente = "";
 $user_stock_prep = "";
 $target = "";
 $id = 0;
 $n = 0;
 $xp = 0;
 while ($donnees = $query_gp_inventory -> fetch())
{
$n = $n + 1;
	$point_de_vente = $donnees['nom_client_fournisseur'];
	$user_stock_prep = $donnees['user_mvt'];
	$montant = $donnees['montant'];
	$no = $donnees['activity_no'];
	$x = chr(35).'/0-9-';
	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $donnees['date_time'], $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date_time = $res_regex[1];
        $date_time = DateTime::createFromFormat('Y-m-d', $date_time);
		$date_time = $date_time -> format('d/m/y');

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
		 case "Ambato_veve_photo":
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
		    $colort = "text-primary";
		    break;
		case "Soalazaina":
		    $colort = "text-info";
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
?>
	
    <li class="table btn-brown text-left <?php echo$colort; ?> container">
    	<a><span class="w3-badge w3-gray"><?php echo (($xp+$n)); ?></span><?php echo "<b class=text-danger style='font-weight: bold'> $no</b>".' | '.$donnees['description_date'].' | '."<u style='font-weight: bold'>$point_de_vente</u>".' || '."<u style='font-weight: bold'>$montant Ar</u>".' || '.$donnees['motif'];?></a>

    	<a class="float-right" href="delete_depense_individual.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/DeleteDustbin-512.png" height="40" width="43" background alt="Edit" /></a>
    	<a class="float-right" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_depense']; ?>"><img src="img/edit_icon_1.png" height="40" width="43" background alt="Edit" /></a>
    	<b class="float-right text-light"><?php echo "$user_stock_prep on $date_time"; ?></b>
    </li>
<?php
}
$query_depense_list = $bdd->prepare($query_depense);
$query_depense_list->execute(array());  
//get row count
$total_depense_line=$query_depense_list->rowCount ();
$total_depense = 0;
$total_depense_aparafa = 0;
$no = 0;
while ($donnees = $query_depense_list -> fetch())
{ 
	$total_depense = $donnees['montant'] + $total_depense;
	$no = $no + 1;
}
?>
<li class="table table-dark text-left container center" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_depense']; ?>" ><a><h4 style='font-weight: bold'>######TOTAL DEPENSE##### <?php echo " =====>  <u>$total_depense Ar</u>"; ?></h4></a></li>
<?php
$query_gp_inventory->closeCursor();
//QUERY FOR DEPENSE LIST
//Query to liste depense of actual activity ID
//$query_depense ="SELECT * FROM depense LEFT JOIN mvt ON numero_commande_stock = activity_no WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = 'Amparafa' GROUP BY id_depense ORDER BY id_depense DESC";
$query_depense ="SELECT * FROM depense LEFT JOIN mvt ON numero_commande_stock = activity_no WHERE type_de_mvt = 'vente' GROUP BY id_depense ORDER BY id_depense DESC";
$query_depense_list = $bdd->prepare($query_depense);
$query_depense_list->execute(array());  
//get row count
$total_depense_line=$query_depense_list->rowCount ();
//Read cookie to resolve new client name
$time_for_nouveau_commande_cookie = 0;
if (isset($_COOKIE['time_for_nouveau_commande_cookie'])) 
{
   $time_for_nouveau_commande_cookie=$_COOKIE['time_for_nouveau_commande_cookie'];
 }


?>
<!------------SIMPLE SEARCH------------------>
<!---------------search result---------------------------->
<?php
$key_word = "";

?>
<!-------------END SIMPLE SEARCH-------------------------->
<?php

//Notification confirme data saved
$nofification = "";
 $msg_nok = "";
 if (isset($_COOKIE['nofification'])) 
{
   $nofification=$_COOKIE['nofification'];
 }
  if (isset($_COOKIE['msg_nok'])) 
{
   $msg_nok=$_COOKIE['msg_nok'];
 }
?>
<br>
<br>
	<h5 class="text-center text-success"><?php echo $msg_nok; ?></h5>
	<h5 class="text-center text-danger"><?php echo $nofification; ?></h5>
<!--
	####################DEBUT AFFICHAGE DEPENSE############################
--->
<div>
        <tbody>
        	<?php
 $query_gp_inventory = $bdd->prepare($query_depense);
 $query_gp_inventory->execute(array());
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
		switch ($point_de_vente) {

		     case "Ambato_Tantely":
		    $colort = "text-warning";
		    break;
		  case "Ambaibo_Tole":
		    $colort = "text-light";
		    break;
		  case "Amparafa":
		    $colort = "text-primary";
		    break;
		case "Soalazaina":
		    $colort = "text-info";
		    break;
		case "Bejofo":
		    $colort = "text-success";
		    break;
		}

	}
$total_depense = 0;
$total_depense_aparafa = 0;
$no = 0;
while ($donnees = $query_depense_list -> fetch())
{ 
	$total_depense = $donnees['montant'] + $total_depense;
	$total_depense_aparafa = $donnees['depense_aparafa'] + $total_depense_aparafa;
	$no = $no + 1;
	//echo $donnees['motif'];
?>
	<!-- model form DEPENSE-->
			<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_depense']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">MODIFIER DEPENSE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div> <div class="modal-body">
			<!-- actual form -->
			<form role="form" action="modifier_depense.php" method="post">
				<div class="form-group">
					<label id="textzoned3">Depense Royal</label>
					<input class="form-control" name="depense_royal" value="<?php echo $donnees['montant']; ?>" type="number" id="textzoned2">
				</div>
				<div class="form-group">
					<label>Motif</label>
					<input class="form-control" name="motif" value="<?php echo $donnees['motif']; ?>" type="text">
					<input name="id_depense" value="<?php echo $donnees['id_depense']; ?>" type="hidden">
				</div>
					<button type="submit" class="btn btn-success">Valider</button>
				</div>
			</form>
			<!-- actual form ends -->
			</div>
			</div>
			</div>
			</div>
<!-------------------------------------->
<?php
}
$query_depense_list->closeCursor();
?>
</tbody>
</table>
</div>
<div>
<!--
	####################FIN AFFICHAGE DEPENSE############################
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->

<br>
<br>
<br>
</div>
</div>
</ul>
<!-------------------------------------->
<!--Javascript--->

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>