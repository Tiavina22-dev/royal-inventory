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
   $nom_client_fournisseur = $_COOKIE['nom_client_fournisseur'];
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
	 	//USE FOR JS COMPARE DATE
  	    $chaine =  $description_date.'';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $inventory_date = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);

        //Eviter wrong format and unwanted space for $date1
        $inventory_date = str_replace('/ ', '-', $inventory_date);
        $inventory_date = str_replace(' / ', '-', $inventory_date);
        $inventory_date = DateTime::createFromFormat('d/m/y', $inventory_date);
        $inventory_date = $inventory_date -> format('Y-m-d');
        //--------------------------

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
<a href="stock_recap.php"><button type="submit" class="btn btn-info">RETOURS</button></a>
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
   $query_stock_inventaire = "SELECT *,nom_x,prix_unitaire,qt,numero_commande_stock,ref_commande_stock from mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND description_date = ? AND type_de_mvt = 'stock'";
	$q = $bdd->prepare($query_stock_inventaire);

	$q->execute(array($nom_client_fournisseur, $description_date));
	//Number of Line
	$nb_line = $q->rowCount ();
	$ib = $nb_line;	
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
					<form role="form" action="modifier_date_stock.php" method="post">
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
        <th></th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>QT</th>
        <th class="text-right">PU Royal</th>
        <th class="text-right">PU Client</th>
        <th class="text-right">Fournisseur</th>
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
//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }                
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo ($nb_line); $nb_line = $nb_line-1; ?></td>
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['qt']; ?></td>
        <td class="text-right"><?php echo $donnees['prix_unitaire']; ?></td>
        <td class="text-info text-right"><?php echo ($donnees['prix_client']+0); ?></td>
        <td class="text-secondary text-right"><?php echo $donnees['prix_fournisseur']+0; ?></td>
        <td><?php echo $donnees['note'].' | '.$donnees['note_x']; ?></td>
        <td><?php
		//Check history
		$query_history = "SELECT * FROM history WHERE  type = 'Mvt_Stock' AND before_change = ? ORDER BY date_time DESC";
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

        }
        echo ($donnees['user_mvt'].$noti); ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_mvt']; ?>" <?php if ($donnees['status'] == 'OFF') {echo 'disabled';}  ?>>Modifier</button>
			<?php if ($donnees['status']=='General_Inventory') {
				?>
			<a href="#" data-toggle="modal" title = "AJOUTER RETOUR OUBLIE ?" data-target="#<?php echo "R".$donnees['id_mvt']; ?>"><span class="w3-badge w3-red"> +R</span></a>
			<a href="#" data-toggle="modal" title = "AJOUTER STOCK OUBLIE ?" data-target="#<?php echo "S".$donnees['id_mvt']; ?>"><span class="w3-badge w3-green">+S</span></a>
			<?php } ?>
		</td>
		<td><a style="<?php if ($donnees['status'] == 'OFF' || $donnees['status']=='General_Inventory') {echo 'pointer-events: none';}  ?>;" class="center" href="delete_mvt_stock.php?id_mvt=<?php echo $donnees['id_mvt']; ?>&description_date=<?php echo $description_date; ?>&nom_client_fournisseur=<?php echo $nom_client_fournisseur; ?>&no_activite=<?php echo $no_activite; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
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
					<form role="form" action="modifier_mvt_stock.php" method="post">
						<div class="form-group">
						<label>Nom/Details</label>
						<input class="form-control" name="nom_x" value="<?php echo htmlspecialchars($donnees['nom_x']); ?>" type="text" disabled="true">
						</div>
						<div class="form-group">
						<label>QUANTITE</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" step="any">
						</div>
						<div class="form-group">
						<label>PRIX ANOMEZANA ROYAL</label>
						<input class="form-control" name="prix_unitaire" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" step="any">
						<input type="hidden" name="id_mvt" value="<?php echo $donnees['id_mvt']; ?>">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur; ?>">
						<input type="hidden" name="description_date" value="<?php echo $description_date; ?>">
						</div>
						<div class="form-group">
						<label>PRIX AMAROTANA</label>
						<input class="form-control" name="prix_client" value="<?php echo ($donnees['prix_client']+0); ?>" type="number" step="any"">
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
<!-------FORM DE +R-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "R".$donnees['id_mvt']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title"><big class ='text-danger'>AJOUTER RETOURS</big><br> <?php echo $donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_retours_inventory.php" method="post">
						<div class="form-group">
						<label>Nom/Details</label>
						<input class="form-control" name="nom_x" value="<?php echo htmlspecialchars($donnees['nom_x']); ?>" type="text" disabled="true">
						</div>
						<div class="form-group">
						<label id="date_aff1">Choisir la date du Retours</label><span id="date_aff2" class="float-right"></span>
						<input id="date_id" oninput="Check_date($(this));" class="form-control bg-warning" value="" name="date_journal" type="DATE">
						</div>
						<div class="form-group">
						<label>Quantite</label>
						<input class="form-control" name="qt" value=0 type="number" step="any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire <?php echo $donnees['nom_client_fournisseur']; ?></label>
						<input class="form-control" name="prix_unitaire" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" disabled="true">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur; ?>">
						<input type="hidden" name="id_x" value = "<?php echo $donnees['id_x']; ?>">
						<input type="hidden" name="GI_Date" value = "<?php echo $inventory_date; ?>">
						<input type="hidden" name="qt_rectificative" value = "<?php echo $donnees['prix_aparafa']; ?>">
						<input type="hidden" name="description_date" value="<?php echo $description_date; ?>">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note" value="Retours Hita nandritra ny <?php echo $description_date; ?>" type="text">
						<input class="form-control bg-light" name="no_activite" value="<?php echo $no_activite;?>" type="hidden">
						</div>
						<button type="submit" id="R_OK<?php echo $nb_line+1;?>" class="btn btn-success" disabled>Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
<!-------FORM DE +S-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "S".$donnees['id_mvt']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title"><big class ='text-danger'>AJOUTER STOCK</big><br> <?php echo $donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_stock_inventory.php" method="post">
						<div class="form-group">
						<label>Nom/Details</label>
						<input class="form-control" name="nom_x" value="<?php echo htmlspecialchars($donnees['nom_x']); ?>" type="text" disabled="true">
						</div>
						<div class="form-group">
						<label id="date_aff1">Choisir la date du Stock</label><span id="date_aff2" class="float-right"></span>
						<input id="date_id" oninput="Check_date($(this));" class="form-control bg-warning" value="" name="date_journal" type="DATE">
						</div>
						<div class="form-group">
						<label>Quantite</label>
						<input class="form-control" name="qt" value=0 type="number" step="any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire <?php echo $donnees['nom_client_fournisseur']; ?></label>
						<input class="form-control" name="prix_unitaire" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" disabled="true">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur; ?>">
						<input type="hidden" name="id_x" value = "<?php echo $donnees['id_x']; ?>">
						<input type="hidden" name="GI_Date" value = "<?php echo $inventory_date; ?>">
						<input type="hidden" name="qt_rectificative" value = "<?php echo $donnees['prix_aparafa']; ?>">
						<input type="hidden" name="description_date" value="<?php echo $description_date; ?>">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note" value="Stock Tsy Tafiditra nandritra ny <?php echo $description_date; ?>" type="text">
						<input class="form-control bg-light" name="no_activite" value="<?php echo $no_activite;?>" type="hidden">
						</div>
						<button type="submit" id="S_OK<?php echo $nb_line+1;?>" class="btn btn-success" disabled>Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
<!-------FORM DE MODIFIER IMAGE-------->
    <!-- modal form MODIFIER IMAGE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "img".$donnees['id_x']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Modifier Image du Produit No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form">
            <div class="form-group text-center">
              <img src="<?php echo $image_path_x;?>" height=100% width=100% align="middle"/>
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
<script>
	function Check_date(e) {

	var daty =  e.val();
	var ib = <?php echo json_encode($ib); ?>;
	var inventory_date = <?php echo json_encode($inventory_date); ?>;

	
	//alert(daty);
	//alert(inventory_date);
	if ((daty < inventory_date)) {
		for (var i = 1; i <= ib; i++) {
		$("#R_OK"+i).removeAttr('disabled');
		$("#S_OK"+i).removeAttr('disabled');
		}
	}
	else{
		//alert('INACCEPTABLE');
		for (var i = 1; i <= ib; i++) {
		$("#R_OK"+i).prop('disabled',true);
		$("#S_OK"+i).prop('disabled',true);
		}
		//alert('different');

	}
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>