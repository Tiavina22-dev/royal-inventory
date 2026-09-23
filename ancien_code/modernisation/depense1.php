<!DOCTYPE html>
<html>
<head>
	<title>Depense</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">


</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
<!--------------------------------------->
<?php
include('connect.php');
//initialisation for Select and Mouveau
#####################MULTI-SESSION######################

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
	<h5 class="text-center text-success"><?php echo $msg_nok; ?></h5>
	<h5 class="text-center text-danger"><?php echo $nofification; ?></h5>
	<div>
	<input id="ancre1" type="search" class="light-table-filter" data-table="table-active" placeholder="Filter/Search">
	</div>

<!--
	####################DEBUT AFFICHAGE DEPENSE############################
--->
<div>
		 <table class="table-active table">
        <thead>
        <tr>
            <th class="bg-secondary text-light">No</th>
            <th class="bg-secondary text-light">Point de vente</th>
            <th class="bg-secondary text-light">Date</th>
            <th class="bg-secondary text-light">Act No</th>
	        <th class="bg-secondary text-light">MOTIF</th>
	        <th class="bg-secondary text-light">ROYAL</th>
	        <th class="bg-secondary text-light">APARAFA</th>
	        <th class="bg-secondary text-light">By</th>
	        <th class="bg-secondary text-light"></th>
	        <th class="bg-secondary text-light"></th>
        </tr>
        </thead>
        <tbody>
<?php
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
	<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['nom_client_fournisseur']; ?></td>
		<td><?php echo $donnees['description_date']; ?></td>
		<td><?php echo $donnees['activity_no']; ?></td>
		<td><?php echo $donnees['motif']; ?></td>
		<td><?php echo $donnees['montant']; ?></td>
	    <td><?php echo $donnees['depense_aparafa']; ?></td>
	    <td><?php echo $donnees['user_depense']; ?></td>
	    <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_depense']; ?>">Modifier</button></td>
	    <td><a class="center" href="delete_depense_individual.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
	    <!--
		<td><a class="center" href="delete_depense.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
	-->
	</tr>
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
					<label id="textzoned3">APARAFA MAKA VOLA</label>
					<input class="form-control" name="depense_aparafa" value="<?php echo $donnees['depense_aparafa']; ?>" type="number" id="textzoned2">
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
		<tr>
	        <th colspan="3">########TOTAL DEPENSE#####</th>
	        <td></td>
	        <td></td>
	        <th><?php echo $total_depense; ?></th>
		    <th><?php echo $total_depense_aparafa; ?></th>
		    <th><?php echo "TOTAL: ".($total_depense+$total_depense_aparafa);?></th>
		    <td></td>
	        <td></td>
        </tr>
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
<!-------------------------------------->
<!--Javascript--->

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>