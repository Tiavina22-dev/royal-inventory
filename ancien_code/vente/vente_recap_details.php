<!DOCTYPE html>
<html>
<head>
	<title>Gerer Article</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
</head>
<body style="background: #343a40">
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<?php
//GET PARAMETER
	$nom_client_fournisseur ="Dont refresh on this page";
	//Cookies from Delete
	if (isset($_COOKIE['nom_client_fournisseur'])) 
	{
   $nom_client_fournisseur=$_COOKIE['nom_client_fournisseur'];
 	}
	//from vente_recap
	if (isset($_GET['nom_client_fournisseur'])) {
  	$nom_client_fournisseur = $_GET['nom_client_fournisseur'];
	}

	$no_activite = 273;
	//Cookies from Modif path
	if (isset($_COOKIE['no_activite'])) 
	{
   $no_activite=$_COOKIE['no_activite'];
 	}
	//from vente_recap
	if (isset($_GET['no_activite'])) {
  	$no_activite = $_GET['no_activite'];
	}

	$description_date ="Journal du 22/10/20";
	//Cookies from Delete
	if (isset($_COOKIE['description_date'])) 
	{
   $description_date=$_COOKIE['description_date'];
 	}
	//from stock_recap
	if (isset($_GET['description_date'])) {
  	$description_date = $_GET['description_date'];
	}
	//echo "Nom Client".$nom_client_fournisseur;
	//echo "Description Date".$description_date;
?>
<br>
<br>
<br>
<br>
<br>
<br>
<h2 class="text-center text-warning"><?php echo $description_date;?> | <?php echo $nom_client_fournisseur;?></h2>
<?php
//Connect to BD
include('connect.php');
//QUERY REFERENCE TEST
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
?>
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<div class="text-center">	
<a href="vente_recap.php"><button type="submit" class="btn btn-info">RETOURS</button></a>
</div>
<br>
<br>
<br>
<!---------------search result---------------------------->
<?php
/*
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //echo $key_word;
}
*/

   //Query to liste searched product
   $query_stock_inventaire = "SELECT *,nom_x,prix_unitaire,qt,numero_commande_stock,ref_commande_stock from mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND description_date = ? AND type_de_mvt = 'vente' ORDER BY id_mvt DESC";
	$q = $bdd->prepare($query_stock_inventaire);

	$q->execute(array($nom_client_fournisseur, $description_date));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='vente_recap.php'>RETOURS</b><br>";
	} else {
	
//Query to get path/Date/Note
   $query_path_date = 'SELECT * from recap_vente WHERE no_activite = ?';
	$query_path_date = $bdd->prepare($query_path_date);

	$query_path_date->execute(array($no_activite));
	while ($donnees = $query_path_date -> fetch())
	{
		$directory = $donnees['directory'];
		$note_general = $donnees['note_general'];
		$benefice_aparafa = $donnees['difference_aparafa']+0;
		$resolution = $donnees['resolution']+0;
		$mihoatra = $donnees['mihoatra']+0;
		$royal_versement = $donnees['Montant']+0;		
	}
?>
<div class="container">
	<input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
	<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="Edit Path/Date" data-toggle="modal" data-target="#edit">
	<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit" class="modal fade">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Edit Path/Date du <?php echo $description_date;?> | <?php echo $nom_client_fournisseur;?> | Activity No.<?php echo $no_activite;?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_path_date.php" method="post">
						<div class="form-group">
						<label><b>description_date</b>: Journal du 23/10/20</label>
						<input class="form-control" value="<?php echo $description_date;?>" name="description_date" type="text">
						</div>
						<div class="form-group">
						<label><b>Path</b>: C:\wamp64\www\GS\pj\2020\10 Oct\Vente\Ambato_tantely\22 Alakamisy</label>
						<input class="form-control bg-light" name="path" value="<?php echo $directory;?>" type="text">
						</div>
						<div class="form-group">
						<label><b>NOTE</b> Ex:Versement BFV REF:857179 du 23.10.2020</label>
						<input class="form-control bg-light" name="note_general" value="<?php echo $note_general;?>" type="text">
						</div>
						<!---------
						<div class="form-group">
						<label><b>Benefice Amparafa</b></label>
						<input class="form-control bg-light" name="benefice_aparafa" value="<?php echo $benefice_aparafa;?>" type="number">
						</div>
						------------>
						<div class="form-group">
						<label><b>RESOLUTION</b></label>
						<input class="form-control bg-light" name="resolution" value="<?php echo $resolution*(-1);?>" type="number">
						</div>
						<div class="form-group">
						<label><b>VOLA MIHOATRA</b></label>
						<input class="form-control bg-light" name="mihoatra" value="<?php echo $mihoatra;?>" type="number">
						</div>
						<div class="form-group">
						<label><b>Royal Versement</b></label>
						<input class="form-control bg-light" name="royal_versement" value="<?php echo $royal_versement;?>" type="number">
						</div>
						<input class="form-control bg-light" name="no_activite" value="<?php echo $no_activite;?>" type="hidden">
						<input class="form-control bg-light" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur;?>" type="hidden">
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
</div>

   <table class="table table-dark container">
        <thead>
        <tr class="text-danger">
        <th>ID</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>QT</th>
        <th>PU</th>
        <th>MT</th>
        <!------------------------
        <?php
        if ($nom_client_fournisseur == 'Amparafa' || $nom_client_fournisseur == 'Bejofo' || $nom_client_fournisseur == 'Soalazaina') {
        echo "<th>PU A/fa</th><th>MT A/fa</th>
        <th>Bn A/fa</th>";
        }
        ?>
        ------------------------->
        <th>NOTE</th>
        <th>By</th>
        <th colspan="2">Modifier/suprimer</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
$m = 1;
//Query searcher word
$ref_id = "xxxxxxs";
$bnc_total = 0;
$check_id = "";
$textzone_id = "";
$sm_mt = 0;
$sm_mt_afa = 0;
$search_color_table = 'table-dark';
while ($donnees = $q -> fetch())
{ 
$ref_id = $j;
//echo $ref_id; 
$text_id1 = 'text_id1'.$j;
$text_id2 = 'text_id2'.$j;
$sm_mt = ($donnees['prix_unitaire']*$donnees['qt']*(-1))+$sm_mt;
$sm_mt_afa = (($donnees['prix_aparafa']+$donnees['prix_client'])*$donnees['qt']*(-1))+$sm_mt_afa;
$benefice_aparafa = ($donnees['prix_client']+$donnees['prix_aparafa']-$donnees['prix_unitaire'])*($donnees['qt']*(-1));
$bnc_total = $bnc_total + $benefice_aparafa;
$j = $j+1;
	$check_id2 = "myCheck2".$m; 
     $textzone_id2 = "textzone2".$m;
     $m = $m+1;              
?>
<!------------------------------------------------->
       <tr class = "bg-dark text-light">
        <td><?php echo $donnees['ref_commande_stock']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo abs($donnees['qt']); ?></td>
        <td><?php echo $donnees['prix_unitaire']; ?></td>
        <td><?php echo ($donnees['prix_unitaire']*abs($donnees['qt'])); ?></td>
        <!--------------------------------
        <?php
        if ($nom_client_fournisseur=="Amparafa" || $nom_client_fournisseur == 'Bejofo' || $nom_client_fournisseur == 'Soalazaina') {
       	?>
       	<td><?php echo $donnees['prix_aparafa']+$donnees['prix_client']; ?></td>
       	<td><?php echo (($donnees['prix_aparafa']+$donnees['prix_client'])*abs($donnees['qt'])); ?></td>
        <td><?php echo $benefice_aparafa; ?></td>
       	<?php
        }
        ?>
        ---------------------------------->
        <td><?php echo $donnees['note'].' | '.$donnees['note_x']; ?></td>
        <td><?php
		//Check history
		$query_history = "SELECT * FROM history WHERE  type = 'Mvt_Vente' AND before_change = ? ORDER BY date_time DESC";
		$query_history = $bdd->prepare($query_history);
		$query_history -> execute(array($donnees['ref_commande_stock']));
		//Number of Line
		$nb_history = $query_history->rowCount ();
		$change_list = '';
		
        $noti = '';
        if ($nb_history > 0) {
        	$noti = " <a href='#' data-toggle='modal' title = 'CLICK TO SHOW CHANGE' data-target='#"."CHANGE".$donnees['id_mvt']."'><span class='w3-badge w3-red'>!</span></a>";
        	//-----CHANGE LISTE MODALE----------------
        	?>
		        	<!-------FORM DE LISTE-------->
				<!-- modal form MODIFIER-->
				<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "CHANGE".$donnees['id_mvt']; ?>" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
							<h4 class="modal-title"><big class ='text-danger'>CHANGE LISTE</big><br> <?php echo $donnees['nom_x']; ?></h4>
							<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
							</div>
							<div class="modal-body">
							<!-- actual form -->
							<form role="form" action="#" method="post">
								<div class="form-group">
								<ul class="list-group">
									<li class="list-group-item">
										<?php
										while ($donnees_history = $query_history -> fetch())
										{
											echo '<b>'.$donnees_history['date_time'].' By '.$donnees_history['responsable'].'</b><br>'.$donnees_history['details'].'<br><br>';
										}
								        $query_history -> closeCursor();
										 echo $change_list; ?>
									</li>
								</ul>
								</div>
								
								<button data-dismiss="modal" type="button" class="btn btn-success">OK</button>
							</form>
							<!-- actual form ends -->
							</div>
						</div>
					</div>
				</div>
		<!-------------------------------------->
		<?php
        	//-----------------------------------

        } echo ($donnees['user_mvt'].$noti); ?></td>
		<td><button type="button" class="text-center" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_mvt']; ?>" <?php if ($donnees['status'] == 'OFF') {echo "disabled";} ?>><img src="img/edit_icon.png" height="30" width="30" background alt="Edit" /></button></td>
		<!-------------FONCTION SUPPRIMER A UTILISER PLUS TARD------------
		<td><a class="center" href="delete_mvt_vente.php?id_mvt=<?php echo $donnees['id_mvt']; ?>&description_date=<?php echo $description_date; ?>&nom_client_fournisseur=<?php echo $nom_client_fournisseur; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/delete_icon.png" height="30" width="30" background alt="Edit" /></a></td>
		---------->
		<td><a style="<?php if ($donnees['status'] == 'OFF') {echo "pointer-events: none";} ?>" class="center" href="#" onclick="confirmationDelete('INTERDIT de Supprimer une vente | Votre action sera enregister');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a></td>

<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_mvt']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content btn-brown">
					<div class="modal-header">
					<h4 class="modal-title text-warning">Modifier le mvt No. <?php echo $donnees['id_mvt']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_mvt_vente.php" method="post">
						<!---------
						<div class="form-group">
						<label>Nom/Details</label>
						<input class="form-control" name="nom_x" value="<?php echo htmlspecialchars($donnees['nom_x']); ?>" type="text" disabled="true">
						</div>
						----------->
						<div class="form-group">
						<label>Quantite</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']*(-1); ?>" type="number" step="any">
						</div>
						<div class="form-group">
						<label>Prix ROYAL</label>
						<input class="form-control" name="prix_unitaire" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" step="any">
						<input type="hidden" name="id_mvt" value="<?php echo $donnees['id_mvt']; ?>">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur; ?>">
						<input type="hidden" name="description_date" value="<?php echo $description_date; ?>">
						</div>
						<!----------
						<div class="form-group">
						<label>PU OFFICIEL</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="text" disabled = "true">
						</div>
						------------>
						<div class="form-group">
						<label>Reference ID (EN MAJUSCULE)</label>
						<p class="text-secondary float-right" id="<?php echo $text_id1; ?>"></p>
						</div>
						<div class="form-group">
							<!---
						<input class="form-control" name="reference_x" id="<?php echo $ref_id; ?>" value="<?php echo htmlspecialchars($donnees['reference_x']); ?>" type="text">
							--->
						<input class="form-control" name="reference_x" value="<?php echo htmlspecialchars($donnees['reference_x']); ?>" type="text" id="<?php echo $ref_id; ?>" oninput="referenceFunction($(this));">
						<p class="text-secondary float-right" id="<?php echo $text_id2; ?>"></p>
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note" value="<?php echo htmlspecialchars($donnees['note']); ?>" type="text">
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
}
?>
	<tr class="text-danger">
        <th>ID</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>QT</th>
        <th>PU</th>
        <th><?php echo $sm_mt;?></th>
        <!------------------------------------
        <?php
        if ($nom_client_fournisseur == 'Amparafa' || $nom_client_fournisseur == 'Bejofo' || $nom_client_fournisseur == 'Soalazaina') {
        echo "<th>PU A/fa</th> <th>".$sm_mt_afa."</th> <th>".
        $bnc_total."</th>";
        }
        ?>
        ------------------------------------->
        <th>NOTE</th>
        <th>BY</th>
        <th colspan="2">Modifier/suprimer</th>
        </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
 <?php
	}
 $q->closeCursor();
 
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->
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
function referenceFunction(e)
{
     //alert("Search suggestions can come here!!");
     var u = <?php echo json_encode($u); ?>;
     //var y = $(this).val();
     //var val = e.target.value;
     //alert(e.val());
     //alert(e.attr('id'));
     //e.style.borderColor = "red";
     var jArray = [];
     jArray = <?php echo json_encode($ref); ?>;
     var msg = "";
     var color;
     var input = e.attr('id');
     var y = e.val();
     var text_id1 = 'text_id1'+input;
     var text_id2 = 'text_id2'+input;
     //alert(text_id1);
  	document.getElementById(input).style.borderColor = "green";
      for(var i=1; i<=u; i++)
      { //alert(jArray[i]);

      	if (jArray[i]==y) {
      		msg="Mety tsara";
      	} else {
      		//alert("nook");
      		//color = "green";
      	}
      	
      }
      //write notification on div id=warning_msg ;
      if (msg=="Mety tsara") {
      	color = "green";
      } else {
      	color = "red";
      	msg="Reference efa misy ampiasaina";
      }
     $("#"+text_id1).text(msg);
     $("#"+text_id2).text(msg);
     document.getElementById(input).style.borderColor = color;
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>