<!DOCTYPE html>
<html>
<head>
	<title>commande</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<?php
//Read cookie for validation msg
 $msg_validation ="";
 $annuler = "";
 $numero_commande="";
 if (isset($_COOKIE['msg_validation'])) 
{
   $msg_validation=$_COOKIE['msg_validation'];
 }
 if (isset($_COOKIE['numero_commande'])) 
{
   $numero_commande=$_COOKIE['numero_commande'];
   $annuler = "Annuler?";
 }
?>
<h4 class="text-center">Preparation du Commande</h4>
<h5 class="text-center text-success"><?php echo $msg_validation; ?><a class="center" href="anuler_validation.php?numero_commande=<?php echo $numero_commande; ?>" onclick="confirmationDelete('Anuler la validation?');return false; post ;"><?php echo $annuler; ?></a></h5>
<?php
//Connect to BD
include('connect.php');
//Query to liste All Waiting command
$query_commande_list = $bdd->query('SELECT id_commande,nom_du_client,nom_x,qt,commande.prix_de_vente as prix_unitaire, (commande.prix_de_vente)*qt as sous_total FROM commande INNER JOIN produit ON commande.id_x = produit.id_x;');
//get row count
$total_line=$query_commande_list->rowCount ();
//Read cookie to resolve new client name
$time_for_nouveau_commande_cookie = 0;
if (isset($_COOKIE['time_for_nouveau_commande_cookie'])) 
{
   $time_for_nouveau_commande_cookie=$_COOKIE['time_for_nouveau_commande_cookie'];
 }
//if waiting commande exist set value of $waiting_commande to 1
 	$waiting_commande = 0 ;
 if ($total_line!=0)
 {
 	$waiting_commande = 1 ;
 }
 //Calculate cookie and row count commande (1 OR 1 = 1 | 1 OR 0 = 1 | 0 OR 0 = 0)
 //$time_for_nouveau_commande_cookie = 0;
 //$waiting_commande = 0;
 $decision_new_commande =  $time_for_nouveau_commande_cookie || $waiting_commande;
 //echo "time_for_nouveau_command".$time_for_nouveau_commande_cookie."waiting_commande".$waiting_commande."=".$decision_new_commande;
if ($decision_new_commande == 0)
{
?>
<form action="new_client_name_commande.php" method="POST">
	<div class="text-center">
	<input name="nom_client" placeholder="Nom du Client" type="text">
	<button type="submit" class="btn btn-success">Nouveau commande</button>
	</div>
</form>

<?php 
} else {
?>
<!--Debut du collapsible--->
<div id="contenu">
	<!--Liste tous les stocks--->
	<div class="card">
		<div class="card-header  bg-info" data-toggle="collapse" href="#allstock">
			<a class="card-link text-white" href="#TTous-les-produits-disponibles">Tous les produits disponibles (DEMARRER?)</a>
		</div>
		<div id="allstock" class="collapse" data-parent="#contenu">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
	<input type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#Modal_new">Nouveau?</button>
	 <table class="table-info table">
        <thead>
        <tr>
        <th>Nom/Description de Produit</th>
        <th>Prix</th>
        <th>Qte Dispo</th>
        <th>Ref ID</th>
        <th>Ajouter</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$i = 1;
//Query to liste All product
$query_commande_search = $bdd->query('SELECT produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x GROUP BY reference_x ORDER BY date_time;');
//Query to liste All Waiting command

while ($donnees = $query_commande_search -> fetch())
{ 

 $qt_dispo = $donnees['qt']*1;
                             
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x'] . $donnees['note_x']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo $qt_dispo; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo $donnees['reference_x']; ?>">Select</button></td>
        </tr>
		<!-- modal form SELECT-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $donnees['reference_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title"><?php echo $donnees['reference_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_commande.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="1" type="number">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="number">
						<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
						</div>
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
<?php
$i+=1;
}
?>
        </tbody>
    </table>
			</div>			
		</div>
	</div>
	<!-------------------------->



<!--FIN du collapsible----->
<!-----LISTE DU COMMANDE EN COURS----->
</div>
<?php
// Query for nom du client
$query_client_name = $bdd->query('SELECT nom_du_client FROM commande;');
$client_name=$query_client_name -> fetch();

if ($total_line == 0)
{
	//echo "Aucune commande en cours";
} else {

?>
	 <table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="6">Commande de <?php echo $client_name['nom_du_client']; ?></th>
        </tr>
        <tr>
        <th>Designation</th>
        <th>Quantite</th>
        <th>Prix unitaire</th>
        <th>Montant</th>
        <th colspan="2">Modifier/Suppr</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
?>
		<tr>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td><?php echo $donnees['qt']; ?></td>
		<td><?php echo $donnees['prix_unitaire'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total'].' Ar'; ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_commande']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_commande.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_commande']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier commande No. <?php echo $donnees['id_commande']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_commande.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
						<input type="hidden" name="id_commande" value="<?php echo $donnees['id_commande']; ?>">
						</div>
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
<!------------------------------->
	</tr>
<?php
}
?>
		<tr>
        <th colspan="3">############### TOTAL ###########</th>
        <th colspan="3"><?php echo $total_commande; ?> Ar</th>
        </tr>
</tbody>
</table>
</div>
<!--
<div class="container btn-group-vertical">
-->
<div class="text-center">
<!--
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->
<a class="center" href="anuler_commande.php" onclick="confirmationDelete('Anuler le commande?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
<a class="center" href="valider_commande.php" onclick="confirmationDelete('Valider le commande?');return false; post ;"><button type="button" class="btn btn-success">Valider</button></a>
</div>
<?php
	}
	}
?>
</div>
<!-------------------------------------->
<!-- model form NOUVEAU-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_new" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Nouveau Article</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_commande_produit.php" method="post">
	<div class="form-group">
		<label>Nom/Description de produit</label>
		<input class="form-control" name="nom_x" placeholder="Ex: Huile 90" type="text">
	</div>
	<div class="form-group">
		<label>Prix Unitaire (Ariary)</label>
		<input class="form-control" name="prix_de_vente" placeholder="Ex: 15000" type="number">
	</div>
	<div class="form-group">
		<label>Quantite</label>
		<input class="form-control" name="qt" placeholder="Atsasany: 0.5 ; Fefany: 0.25 (Tsy miasa ny virgule)" type="number">
	</div>
	<div class="form-group">
		<label>Reference ID</label>
		<input class="form-control" name="reference_x" placeholder="Izay tiana Ex: Fer 6 --> F06" type="text">
	</div>
<button type="submit" class="btn btn-success">Valider</button>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->


<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>