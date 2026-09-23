<!DOCTYPE html>
<html>
<head>
	<title>commande</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/arrondi.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
<!--------------------------------------->
<?php
include('connect.php');
//initialisation for Select and Mouveau
$i = 1;
//initialisation
$client_name = "";
$description_date = "";
$img_path = "img/default_img/default_pdp.png";
//QUERY DESTINY FOR AUTO DETECT DUPLICATE
	$query_num_stock = $bdd->query('SELECT reference_x FROM produit');
	$u = 0;
	while ($reference_x = $query_num_stock -> fetch())
{
	$u = $u + 1;
	$ref[$u] = $reference_x['reference_x'];
	//echo $reference_x['reference_x'];
}
	//$u = 2;
	$query_num_stock ->closeCursor();
//Read cookie for validation msg
 $msg_validation ="";
 $annuler = "";
 $numero_commande="";
 $point_de_vente_cookie = "";
 if (isset($_COOKIE['msg_validation'])) 
{
   $msg_validation=$_COOKIE['msg_validation'];
 }
 if (isset($_COOKIE['numero_commande'])) 
{
   $numero_commande=$_COOKIE['numero_commande'];
   $annuler = "Annuler?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie=$_COOKIE['point_de_vente_cookie'];
 }
?>
<h4 class="text-center"><?php echo $point_de_vente_cookie; ?></h4>
<h5 class="text-center text-success"><?php echo $msg_validation; ?><a class="center" href="anuler_validation.php?numero_commande=<?php echo $numero_commande; ?>" onclick="confirmationDelete('Anuler la validation?');return false; post ;"><?php echo $annuler; ?></a></h5>
<?php
#####################MULTI-SESSION######################
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
if ($username=='STANDARD') {
	# QUERY GET DISTINCT USER
		$query_user = $bdd->prepare('SELECT DISTINCT(user) FROM commande');
        $query_user->execute(array());
        //get row count
		$nb_line=$query_user->rowCount ();
		if ($nb_line > 0) 
		{
		while ($donnees = $query_user->fetch())
         {
		//Query to liste Waiting command for each user
        $username = $donnees['user'];
		$query_commande_list = $bdd->prepare('SELECT id_commande,nom_du_client,nom_x,reference_x,	note_commande,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY id_commande DESC;');
		$query_commande_list->execute(array($username));
		// Query for nom du client, numero de commande et date
		$query_client_name = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
		        $query_client_name->execute(array($username));
		while ($donnees = $query_client_name->fetch())
         {
        	$numero_commande = $donnees['numero_commande'];
         	$client_name = $donnees['point_de_vente'];
         	$description_date = $donnees['description_date'];
         	$img_path = $donnees['img_path'];
          }
		$query_client_name->closeCursor();
?>
<div>
	<div class="text-center bg-primary">
	<b>TRAITER PAR 
    <img src="<?php echo $img_path?>" height="80" width="80" align="middle" class="arrondi" />
    <?php echo $username;?></b>
  </div>
		 <table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="12"><?php echo ($username." || ".$client_name." || ".$description_date." (Activiter No. ".$numero_commande.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th>Qt</th>
        <th>PU</th>
        <th>Montant</th>
        <th>PU A/fa</th>
        <th>MT A/fa</th>
        <th>Bc A/fa</th>
        <th>Note</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$sous_total_aparafa = 0;
$no = 0;
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$sous_total_aparafa = $donnees['sous_total_aparafa'] + $sous_total_aparafa;
	$no = $no + 1;
?>
	<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td><?php echo $donnees['qt']; ?></td>
		<td><?php echo $donnees['prix_unitaire'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total'].' Ar'; ?></td>
		<td><?php echo $donnees['prix_aparafa'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total_aparafa'].' Ar'; ?></td>
		<td><?php echo ($donnees['sous_total_aparafa']-$donnees['sous_total']).' Ar'; ?></td>
		<td><?php echo $donnees['note_commande']; ?></td>
	</tr>
<?php
}
?>
		<tr>
        <th colspan="5">########TOTAL #####</th>
        <th><?php echo ($total_commande); ?> Ar</th>
        <th></th>
        <th><?php echo ($sous_total_aparafa); ?> Ar</th>
        <?php
        $difference = $sous_total_aparafa-$total_commande;
        if ($difference < 0)
        {
        	$difference = 0;
        }
        ?>
        <th colspan="3">(A/fa Diff:<?php echo ($difference); ?> Ar)</th>
        </tr>;
</tbody>
</table>
</div>
<?php
//Query to liste depense of actual activity ID
$query_depense ="SELECT * FROM depense WHERE activity_no = ? ";
$query_depense_list = $bdd->prepare($query_depense);

  $query_depense_list->execute(array($numero_commande));  
//get row count
$total_depense_line=$query_depense_list->rowCount ();
?>
<div>
		 <table class="table-active table">
        <thead>
        <tr>
        	<th class="text-center bg-secondary text-light" colspan="11">DEPENSE <?php echo ($client_name." || ".$description_date." (Activiter No. ".$numero_commande.")"); ?></th>
        </tr>
	        <tr>
	        <th>No.</th>
	        <th>ID</th>
	        <th>MOTIF</th>
	        <th>MONTANT</th>
	        <th>APARAFA</th>
	        <th></th>
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
		<td><?php echo $donnees['id_depense']; ?></td>
		<td><?php echo $donnees['motif']; ?></td>
		<td><?php echo $donnees['montant']; ?></td>
		<td><?php echo $donnees['depense_aparafa']; ?></td>
		<th></th>
	</tr>
<?php
}
$query_depense_list->closeCursor();
?>
		<tr>
        <th colspan="3">########SOUS TOTAL#####</th>
        <th><?php echo $total_depense; ?> Ar</th>
        <th><?php echo $total_depense_aparafa; ?> Ar</th>
        <th>TOTAL: <?php echo ($total_depense+$total_depense_aparafa); ?> Ar</th>
        </tr>
</tbody>
</table>
<p class="form-control text-center text-light" style="background-color: purple"><b>MT ROYAL =<?php echo floor($total_commande);?> | Depense ROYAL:<?php echo $total_depense;?> | Depense A/FA:<?php echo $total_depense_aparafa;?> | Reste ROYAL=<?php echo floor($total_commande-$total_depense);?> | Reste GLOBAL=<?php echo floor($sous_total_aparafa-$total_depense_aparafa-$total_depense);?></b></p>
<br>
<br>
</div>
<?php
		##############END OF WAITING COMMANDE FOR EACH USER		
          } //While query_user
		$query_user->closeCursor();
		} else {
			# code...
			echo "<div class='text-center'><b>PAS DE TRAITEMENT DU JOURNAL DE VENTE EN COURS</b></div>";
		}

} else {
	# code...
#####################################################
?>
<?php
//Connect to BD

//Query to liste All Waiting command
$query_commande_list = $bdd->prepare('SELECT id_commande,nom_du_client,nom_x,reference_x,	note_commande,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY id_commande DESC;');
$query_commande_list->execute(array($username));
//get row count
$total_line=$query_commande_list->rowCount ();
//GET NUMERO ACTIVITY
$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $reponse->execute(array($username));
        $activity_no = 0;
       while ($donnees = $reponse->fetch())
         {
          //Numero de commande stored on user table
         $activity_no = $donnees['numero_commande'];
          }
$reponse->closeCursor();
//-------------------------------------------
//QUERY FOR DEPENSE LIST
//Query to liste depense of actual activity ID
$query_depense ="SELECT * FROM depense WHERE activity_no = ? ";
$query_depense_list = $bdd->prepare($query_depense);

  $query_depense_list->execute(array($activity_no));  
//get row count
$total_depense_line=$query_depense_list->rowCount ();
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
	<div class="text-center">
	<button type="submit" class="btn btn-success" data-toggle="modal" data-target="#Demarrer">Demarrer</button>
	</div>
<!-------------------------------------->
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Date/Description du Journal</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="new_client_name_commande.php" method="post">
	<div class="form-group">
		<label>Ex: Journal du 23/10/20</label>
		<input class="form-control" value="Journal du " name="date_journal" placeholder="Ex: Journal du 23/10/20" type="text">
	</div>
	<div class="form-group">
		<label>Point de vente</label>
		<select class="form-control btn" name="point_de_vente">
		<option value="Ambaibo_Electronique">Ambaibo_Electronique</option>
	    <option value="Ambaibo_loko">Ambaibo_loko</option>
	    <option value="Ambaibo_Tole">Ambaibo_Tole</option>
	    <option value="Amparafa">Amparafa</option>
	    <option value="Ambato_Tantely">Ambato_Tantely</option>
	    <option value="Ambato_veve_photo">Ambato_veve_photo</option>
	    <option value="Bejofo">bejofo</option>
		</select>
	</div>
	<div class="form-group">
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
} else {
?>
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<form role="form" action="simple_search_commande.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" name="key_word" placeholder="Name/Code/Search">
<button type="submit" class="btn btn-info">Search</button>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_new">Nouveau?</button>
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#Modal_depense">Depense</button>
</div>
</form>
<br>
<!---------------search result---------------------------->
<?php
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x WHERE nom_x LIKE ? OR reference_x LIKE ? OR produit.id_x LIKE ? GROUP BY reference_x ORDER BY date_time';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%", "%".$key_word."%"));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<p class='text-center';><b>".$key_word."</b>"."<b class='text-danger';>"." does not exist on the base</b></p>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div class="text-center">
		<b>Result for [<?php echo $key_word; ?>]</b>
	</div>
   <table class="table-info table">
        <thead>
        <tr>
        <th>Nom/Description de Produit</th>
        <th>Ref ID</th>
        <th>Prix</th>
        <th>Qte Dispo</th>
        <th>Ajouter</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php

//Query searcher word
$check_id = "";
$textzone_id = "";

while ($donnees = $q -> fetch())
{ 
     $qt_dispo_v1 = $donnees['qt']*1;
     $check_id = "myCheck".$i; 
     $textzone_id = "textzone".$i;
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x'] . $donnees['note_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo $qt_dispo_v1; ?></td>
    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo ("no".$donnees['id_x']); ?>">Select</button></td>
        </tr>
		<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$donnees['id_x']); ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title"><?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_commande.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="1" type="number" step="any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="number">
						</div>
						<div class="bg-warning">
						<input type="checkbox" id="<?php echo $check_id; ?>" onclick="myFunction()">
						<label for="myCheck">APARAFA IHANY</label>
						<input class="form-control btn-danger" name="prix_aparafa" value=0 type="number" id="<?php echo $textzone_id; ?>" style="display:none">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" type="text" name="note_commande">
						</div>
						<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
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
 <?php
	}
 $q->closeCursor();
 }
?>
<!-------------END SIMPLE SEARCH-------------------------->
<?php
// Query for nom du client
$query_client_name = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['numero_commande'];
         $client_name = $donnees['point_de_vente'];
         $description_date = $donnees['description_date'];
          }
		$query_client_name->closeCursor();

if ($total_line == 0)
{
	//echo "Aucune commande en cours";
} else {
//Notification confirme data saved
$msg_ok = "";
 $msg_nok = "";
 if (isset($_COOKIE['msg_ok'])) 
{
   $msg_ok=$_COOKIE['msg_ok'];
 }
  if (isset($_COOKIE['msg_nok'])) 
{
   $msg_nok=$_COOKIE['msg_nok'];
 }
?>
	<h5 class="text-center text-success"><?php echo $msg_ok; ?></h5>
	<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
	<div>
	<input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
	<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
	</div>
	 <table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="12"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th>Qt</th>
        <th>PU</th>
        <th>Montant</th>
        <th>PU A/fa</th>
        <th>MT A/fa</th>
        <th>Bc A/fa</th>
        <th>Note</th>
        <th colspan="3">Modifier/Suppr</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$sous_total_aparafa = 0;
$no = 0;
$check_id2 = "";
$textzone_id2 = "";
$m = 1;
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$sous_total_aparafa = $donnees['sous_total_aparafa'] + $sous_total_aparafa;
	$no = $no + 1;
	$check_id2 = "myCheck2".$m; 
     $textzone_id2 = "textzone2".$m;
     $m = $m+1;
?>
		<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td><?php echo $donnees['qt']; ?></td>
		<td><?php echo $donnees['prix_unitaire'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total'].' Ar'; ?></td>
		<td><?php echo $donnees['prix_aparafa'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total_aparafa'].' Ar'; ?></td>
		<td><?php echo ($donnees['sous_total_aparafa']-$donnees['sous_total']).' Ar'; ?></td>
		<td><?php echo $donnees['note_commande']; ?></td>
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
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" step = "any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
						</div>
						<div class="bg-warning">
						<input type="checkbox" id="<?php echo $check_id2; ?>" onclick="myFunction2()">
						<label for="myCheck">APARAFA IHANY</label>
						<input class="form-control btn-danger" name="prix_aparafa" value=0 type="number" id="<?php echo $textzone_id2; ?>" style="display:none">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana</label>
						<input class="form-control" name="note_commande" value="<?php echo $donnees['note_commande']; ?>" type="text">
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
        <th colspan="5">########TOTAL #####</th>
        <th><?php echo ($total_commande); ?> Ar</th>
        <th></th>
        <th><?php echo ($sous_total_aparafa); ?> Ar</th>
        <?php
        $difference = $sous_total_aparafa-$total_commande;
        if ($difference < 0)
        {
        	$difference = 0;
        }
        ?>
        <th colspan="4">(A/fa Diff:<?php echo ($difference); ?> Ar)</th>
        </tr>;
</tbody>
</table>
</div>
<!--
	####################DEBUT AFFICHAGE DEPENSE############################
--->
<div>
		 <table class="table-active table">
        <thead>
        <tr>
        	<th class="text-center bg-secondary text-light" colspan="11">DEPENSE <?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>ID</th>
        <th>MOTIF</th>
        <th>MONTANT</th>
        <th>APARAFA</th>
        <th></th>
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
		<td><?php echo $donnees['id_depense']; ?></td>
		<td><?php echo $donnees['motif']; ?></td>
		<td><?php echo $donnees['montant']; ?></td>
		<td><?php echo $donnees['depense_aparafa']; ?></td>
		<td><a class="center" href="delete_depense.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
	</tr>
<?php
}
$query_depense_list->closeCursor();
?>
		<tr>
        <th colspan="3">########SOUS TOTAL#####</th>
        <th><?php echo $total_depense; ?> Ar</th>
        <th><?php echo $total_depense_aparafa; ?> Ar</th>
        <th>TOTAL: <?php echo ($total_depense+$total_depense_aparafa); ?> Ar</th>
        </tr>
</tbody>
</table>
</div>
<div>
<!--
	####################FIN AFFICHAGE DEPENSE############################
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->
<p class="form-control text-center text-light" style="background-color: purple"><b>MT ROYAL =<?php echo floor($total_commande);?> | Depense ROYAL:<?php echo $total_depense;?> | Depense A/FA:<?php echo $total_depense_aparafa;?> | Reste ROYAL=<?php echo floor($total_commande-$total_depense);?> | Reste GLOBAL=<?php echo floor($sous_total_aparafa-$total_depense_aparafa-$total_depense);?></b></p>
<form action="valider_commande.php" method="POST">
<div>
	<label><b>VERSEMENT POUR ROYAL ESPECE OU BANK (Ar)</b></label>
	<label class="float-right"><b>POINT</b>##########</label>
</div>
<div>
<input class="col-sm-5 btn" style="background: yellow" name="versement" value=0 type="number" step="any">
<select class="btn float-right" style="background: pink" name="point">
		<option value="congratulation">Felicitation</option>
	    <option value="avertissement">Avertissement</option>
</select>
</div>
<div>
<br>
<label><b>RESOLUTION Ex: -3000 Raha TSY AMPY (NEGATIF) | 3000 Raha Mihoatra NY VOLA</b></label>
</div>
<div>
<input class="col-sm-5 btn" style="background: pink" name="resolution" value=0 type="number" step="any">
</div>
<div class="form-group">
<br>
<label class="float-left"><b>NOTE</b> Ex:Versement BFV REF:857179 du 23.10.2020</label>
<input class="form-control bg-light" name="note_general" placeholder="note:Compte est bon" type="text">
</div>
<div class="form-group">
<label class="float-left"><b>Chemin</b> Ex: C:\wamp64\www\GS\pj\2020\10 Oct\Vente\Ambato_tantely\22 Alakamisy</label>
<input class="form-control bg-light" name="path" placeholder="Copie-coller l'emplacement du photo ici" type="text">
</div>
<div class="form-group text-center">
<a class="center" href="anuler_commande.php" onclick="confirmationDelete('Anuler le commande?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
<a class="center" onclick="confirmationDelete('Valider le commande?');return false; post ;"><button type="submit" class="btn btn-success">Valider</button></a>
</div>
</form>
<br>
<br>
<br>
</div>
<?php
	}
	}
	}//End of if MULTI-SESSION
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
		<label>Reference ID (EN MAJUSCULE)</label>
		<p class="text-danger float-right" id="warning_msg"></p>
	</div>
	<div class="form-group">
		<input class="form-control" id="reference_x" name="reference_x" placeholder="Unique Ex: Fer 6 --> F06" type="text">
	</div>
	<div class="form-group">
		<label>Quantite (0.5 ; 0.25)</label>
		<input class="form-control" name="qt" value="1" type="number" step="any">
	</div>
	<div class="form-group">
		<label>Prix Unitaire (Ariary)</label>
		<input class="form-control" name="prix_de_vente" value=0 type="number">
	</div>
	<div class="bg-warning">
		<input type="checkbox" id="myCheck0" onclick="myFunction()">
		<label for="myCheck">APARAFA IHANY</label>
		<input class="form-control btn-danger" name="prix_aparafa" value=0 type="number" id="textzone0" style="display:none">
	</div>
		<div class="form-group">
		<label>Note/Fanamarihana(raha ilaina)</label>
		<input class="form-control" name="note_x" type="text">
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success">Valider</button>
	</div>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
<!-- model form DEPENSE-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_depense" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">NOUVEAU DEPENSE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_depense.php" method="post">
	<div class="form-group">
		<label id="textzoned3">Montant (Ariary)</label>
		<input class="form-control" name="depense" value=0 type="number" id="textzoned2">
	</div>
	<div class="bg-warning">
		<input type="checkbox" id="myCheckd" onclick="dFunction()">
		<label for="myCheck">APARAFA MAKA VOLA</label>
		<input class="form-control btn-danger" name="depense_aparafa" value=0 type="number" id="textzoned" style="display:none">
	</div>
	<div class="form-group">
		<label>Motif</label>
		<input class="form-control" name="motif" placeholder="Ex: frais" type="text">
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

<!-------------------------------------->
<!--Javascript--->
<script>
$(document).ready(function(){
    $("#reference_x").on("input", function(){
    //----------------------
      var y = $(this).val();
      var jArray = [];
      var u = <?php echo json_encode($u); ?>;
      var msg;
      var color;
      jArray = <?php echo json_encode($ref); ?>;
       document.getElementById("reference_x").style.borderColor =
      msg="";
      for(var i=1; i<=u; i++)
      { //alert(jArray[i]);

      	if (jArray[i]==y) {
      		msg="Efa Misy";
      	} else {
      		//alert("nook");
      		//color = "green";
      	}
      	
      }
      //write notification on div id=warning_msg ;
      if (msg=="Efa Misy") {
      	color = "red";
      } else {
      	color = "green";
      }
      $("#warning_msg").text(msg);
      document.getElementById("reference_x").style.borderColor = color;
      
      
    });
});
</script>
<script>

function myFunction() {
var myCheck = "";
var textzone = "";
//alert("OK");
for (var u = 0 ; u <= <?php echo json_encode($i); ?>; u++) {
	myCheck = "myCheck"+u;
	textzone = "textzone"+u;
  var checkBox = document.getElementById(myCheck);
  var text = document.getElementById(textzone);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
}

</script>
<script>

function myFunction2() {
var myCheck = "";
var textzone = "";
//alert("OK");
for (var u = 1 ; u <= <?php echo json_encode($m); ?>; u++) {
	myCheck = "myCheck2"+u;
	textzone = "textzone2"+u;
  var checkBox = document.getElementById(myCheck);
  var text = document.getElementById(textzone);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
}

</script>
<script>

function dFunction() {
  var checkBox = document.getElementById("myCheckd");
  var text = document.getElementById("textzoned");
  var text2 = document.getElementById("textzoned2");
  var text3 = document.getElementById("textzoned3");
  if (checkBox.checked == true){
    text.style.display = "block";
    text2.style.display = "none";
    text3.style.display = "none";
  } else {
     text.style.display = "none";
     text2.style.display = "block";
     text3.style.display = "block";
  }
}

</script>
<script>
$(document).ready(function(){
   //var x = 2;

    $("#general_note").on("input", function(){
        // Print entered value in a div box
      //var x = document.getElementById("id1").value;
      //var v = document.getElementById("variable").value;
      var general_note = $(this).val();

    });
});
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>