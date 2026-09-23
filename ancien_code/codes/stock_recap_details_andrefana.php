<!DOCTYPE html>
<html>
<head>
	<title>DETAILS ANDREFANA</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
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
//GET PARAMETER
	$nom_client_fournisseur ="Dont refresh on this page";
	//Cookies from Delete
	if (isset($_COOKIE['nom_client_fournisseur'])) 
	{
   $nom_client_fournisseur=$_COOKIE['nom_client_fournisseur'];
 	}
	//from stock_recap
	if (isset($_GET['nom_client_fournisseur'])) {
  	$nom_client_fournisseur = $_GET['nom_client_fournisseur'];
	}

	$description_date ="Journal du 22/10/20";
	//Cookies from Delete and modifier_date_stock
	if (isset($_COOKIE['description_date'])) 
	{
   $description_date=$_COOKIE['description_date'];
 	}
	//from stock_recap
	if (isset($_GET['description_date'])) {
  	$description_date = $_GET['description_date'];
	}

	//from stock_recap
	if (isset($_GET['no_activite'])) {
  	$no_activite = $_GET['no_activite'];
	}
	//Cookies from modify date
	if (isset($_COOKIE['no_activite'])) 
	{
   $no_activite=$_COOKIE['no_activite'];
 	}
	//echo "Nom Client".$nom_client_fournisseur;
	//echo "Description Date".$description_date;
?>
<h4 class="text-center"><?php echo $description_date;?> | <?php echo $nom_client_fournisseur;?></h4>
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
<a href="stock_recap_andrefana.php"><button type="submit" class="btn btn-info">RETOURS</button></a>
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
   $query_stock_inventaire = "SELECT *,nom_x,prix_unitaire,qt,numero_commande_stock,ref_commande_stock from mvt_calc NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND description_date = ? AND type_de_mvt = 'stock'";
	$q = $bdd->prepare($query_stock_inventaire);

	$q->execute(array($nom_client_fournisseur, $description_date));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<div>
	<input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
	<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="Edit Date" data-toggle="modal" data-target="#edit">
	<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit" class="modal fade">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Edit Date du <?php echo $description_date;?> | <?php echo $nom_client_fournisseur;?> | Activity No.<?php echo $no_activite;?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_date_stock_andrefana.php" method="post">
						<div class="form-group">
						<label><b>description_date</b>: Inventaire/Ajout du 23/10/20</label>
						<input class="form-control" value="<?php echo $description_date;?>" name="description_date" type="text">
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
   <table class="table-info table">
        <thead>
        <tr>
        <th>No</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>QT</th>
        <th>PU</th>
        <th>PU Officiel</th>
        <th>Special Afa</th>
        <th>NOTE</th>
        <th>By</th>
        <th colspan="2">Modifier/suprimer</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;

//Query searcher word
$ref_id = "xxxxxxs";
while ($donnees = $q -> fetch())
{ 
$ref_id = $j;
//echo $ref_id; 
$text_id1 = 'text_id1'.$j;
$text_id2 = 'text_id2'.$j;
$j = $j+1;                 
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo ($nb_line); $nb_line = $nb_line-1; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['qt']; ?></td>
        <td><?php echo $donnees['prix_unitaire']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo ($donnees['pu_aparafa']+0); ?></td>
        <td><?php echo $donnees['note'].' | '.$donnees['note_x']; ?></td>
        <td><?php echo $donnees['user_mvt']; ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_mvt']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_mvt_stock_andrefana.php?id_mvt=<?php echo $donnees['id_mvt']; ?>&description_date=<?php echo $description_date; ?>&nom_client_fournisseur=<?php echo $nom_client_fournisseur; ?>&no_activite=<?php echo $no_activite; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_mvt']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier le mvt No. <?php echo $donnees['id_mvt']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_mvt_stock_andrefana.php" method="post">
						<div class="form-group">
						<label>Nom/Details</label>
						<input class="form-control" name="nom_x" value="<?php echo htmlspecialchars($donnees['nom_x']); ?>" type="text" disabled="true">
						</div>
						<div class="form-group">
						<label>Quantite</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" step="any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire <?php echo $donnees['nom_client_fournisseur']; ?></label>
						<input class="form-control" name="prix_unitaire" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" step="any">
						<input type="hidden" name="id_mvt" value="<?php echo $donnees['id_mvt']; ?>">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur; ?>">
						<input type="hidden" name="description_date" value="<?php echo $description_date; ?>">
						</div>
						<div class="form-group">
						<label>Prix Unitaire Officiel</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="text" disabled = "true">
						</div>
						<div class="form-group">
						<label>Prix de Demarrage (Special Afa)</label>
						<input class="form-control" name="pu_aparafa" value="<?php echo ($donnees['pu_aparafa']+0); ?>" type="text" disabled = "true">
						</div>
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
						<input class="form-control bg-light" name="no_activite" value="<?php echo $no_activite;?>" type="hidden">
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
        </tbody>
    </table>
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