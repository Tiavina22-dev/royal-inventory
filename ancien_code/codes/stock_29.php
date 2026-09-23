<!DOCTYPE html>
<html>
<head>
	<title>stock</title>
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
 $point_de_vente_cookie = "";
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
<?php
//Connect to BD
include('connect.php');
//Query to liste All Waiting stock

if (isset($_COOKIE['limit'])) 
{
   $query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x ORDER BY id_stock_prep DESC";
 }else{
$limit = 200;
$query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x ORDER BY id_stock_prep DESC LIMIT $limit";
}
	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array());	

 

$query_stock_prep = $bdd->query('SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x ORDER BY id_stock_prep DESC;');
//get row count
$total_line=$query_stock_prep->rowCount ();
//echo $total_line;
//Read cookie to resolve new client name
$time_for_nouveau_stock_cookie = 0;
if (isset($_COOKIE['time_for_nouveau_stock_cookie'])) 
{
   $time_for_nouveau_stock_cookie=$_COOKIE['time_for_nouveau_stock_cookie'];
 }
//if waiting commande exist set value of $waiting_commande to 1
 	$waiting_stock = 0 ;
 if ($total_line!=0)
 {
 	$waiting_stock = 1 ;
 }
 //Calculate cookie and row count commande (1 OR 1 = 1 | 1 OR 0 = 1 | 0 OR 0 = 0)
 //$time_for_nouveau_commande_cookie = 0;
 //$waiting_commande = 0;
 $decision_new_stock =  $time_for_nouveau_stock_cookie || $waiting_stock;
 //echo "time_for_nouveau_command".$time_for_nouveau_stock_cookie."waiting_commande".$waiting_stock." = ".$decision_new_stock;
if ($decision_new_stock == 0)
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
			<h4 class="modal-title">Date/Description du stock</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="new_stock.php" method="post">
	<div class="form-group">
		<label>Ex: Inventaire/Ajout du 23/10/20</label>
		<input class="form-control" value="Inventaire/Ajout du " name="description_date" type="text">
	</div>
	<div class="form-group">
		<label>Point de vente</label>
		<select class="form-control btn" name="point_de_vente">
		<option value="Ambaibo_Electronique">Ambaibo_Electronique</option>
	    <option value="Ambaibo_Quincaillerie">Ambaibo_Quincaillerie</option>
	    <option value="Ambaibo_Tole">Ambaibo_Tole</option>
	    <option value="Amparafa">Amparafa</option>
	    <option value="Ambato_Tantely">Ambato_piece</option>
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
<form role="form" action="simple_search.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" name="key_word" placeholder="Name/Code/Search">
<button type="submit" class="btn btn-info">Search</button>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_new">Nouveau?</button>
</div>
</form>
<br>
<br>
<br>
<!---------------search result---------------------------->
<?php
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x WHERE nom_x LIKE ? OR reference_x LIKE ? GROUP BY reference_x ORDER BY date_time';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%"));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<br><b>"."[".$key_word."]"." does not exist on the base</b><br>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
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

//Query searcher word

while ($donnees = $q -> fetch())
{ 
     $qt_dispo_v1 = $donnees['qt']*1;                         
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x'] . $donnees['note_x']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo $qt_dispo_v1; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo $donnees['reference_x']; ?>">Select</button></td>
        </tr>
		<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $donnees['reference_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title"><?php echo $donnees['reference_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_stock_prep.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="1" type="number" step="any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="number">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" type="text" name="note_stock">
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
		$query_client_name = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 2');
        $query_client_name->execute(array());
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['valeur_memo'];
         $client_name = $donnees['note_memo'];
         $description_date = $donnees['description_date'];
          }
		$query_client_name->closeCursor();
//$description_date = "xxx";
if ($total_line == 0)
{
	//echo "Aucune commande en cours";
} else {

?>
	<h5 class="text-center text-success"><?php echo $msg_ok; ?></h5>
	<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
	<div>
		<input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
		<a href="limit.php">
		<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
		</a>
	</div>
	 <table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="9"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th>Quantite</th>
        <th>Prix unitaire</th>
        <th>Montant</th>
        <th>Note</th>
        <th colspan="2">Modifier/Suppr</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$no = 0;
while ($donnees = $query_stock_prep_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$no = $no + 1;
?>
		<tr>
			
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td><?php echo $donnees['qt']; ?></td>
		<td><?php echo $donnees['prix_unitaire'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total'].' Ar'; ?></td>
		<td><?php echo $donnees['note']; ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_stock_prep']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_stock_prep.php?id_stock_prep=<?php echo $donnees['id_stock_prep']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_stock_prep']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier stock No. <?php echo $donnees['id_stock_prep']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_stock.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" Step="any">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
						<input type="hidden" name="id_stock_prep" value="<?php echo $donnees['id_stock_prep']; ?>">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note_stock" value="<?php echo $donnees['note']; ?>" type="text">
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
        <th colspan="4">############### TOTAL ###########</th>
        <th colspan="4"><?php echo $total_commande; ?> Ar</th>
        </tr>
</tbody>
</table>
<div class="text-center">
<a class="center" href="anuler_stock.php" onclick="confirmationDelete('Anuler cette action?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
<a class="center" href="valider_stock.php" onclick="confirmationDelete('Valider le stock?');return false; post ;"><button type="button" class="btn btn-success">Valider</button></a>
<br>
<br>
<br>
<br>
--->
</div>
<?php
	}
	}
?>
<!-------------------------------------->
<!-- model form NOUVEAU-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_new" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Nouveau Article</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_stock_produit.php" method="post">
	<div class="form-group">
		<label>Nom/Description de produit</label>
		<input class="form-control" name="nom_x" placeholder="Ex: Huile 90" type="text">
	</div>
	<div class="form-group">
		<label>Prix Unitaire (Ariary)</label>
		<input class="form-control" name="prix_de_vente" value="0" placeholder="Ex: 15000" type="number" >
	</div>
	<div class="form-group">
		<label>Quantite Atsasany: 0.5 ; Fefany: 0.25 (Tsy miasa ny virgule)</label>
		<input class="form-control" name="qt" value=1  type="number" step="any">
	</div>
	<div>
		<label>Reference ID</label>
	</div>
	<div id="warning_msg">
			<?php
              if (isset($_COOKIE['msg_E'])) 
              {
                 echo $_COOKIE['msg_E'];
              }
              ?>

	</div>
	<div class="form-group form-inline">
		<input class="form-control" id="reference_x" name="reference_x" placeholder="Code_unique Ex: Fer 6 --> F06" type="text">
		<button type="button" id="action" class="btn btn-info">Tester Code/Reference</button>
	</div>
		<div class="form-group">
		<label>Note/Fanamarihana(raha ilaina)</label>
		<input class="form-control" name="note_stock" type="text">
	</div>
	<div class="form-inline form-group">
		<button type="submit"  class="btn btn-success">Valider</button>
	</div>
</form>
<!-----
				<div class="form-group">
					<label>Nom/Description de produit</label>
					<input class="form-control" type="text" name="nom_x" placeholder="Ex: Huile 90">

					<label>Quantite/Isany</label>
					<input class="form-control" type="number" id="Search" placeholder="Quantite">
					
					<label>Prix de base / Ivarotana</label>
					<input class="form-control" type="number" id="Search" placeholder="Prix Ivarotana">

					<label>Masokarena</label>
					<input class="form-control" type="number" id="Search" placeholder="Prix d'achat">

					<label>Categorie/Classe(<a href="#myModal" data-toggle="modal">Exemple</a>)</label>
					<div class="form-inline">
					<input class="form-control" type="text" id="Search" placeholder="Ex: Pneu/Loko/Piece Moto">
					
					</div>

					<label>Code d'identification (<a href="#myModal" data-toggle="modal">Code existante</a>) | Optional</label>
					<input class="form-control" type="text" id="Search" placeholder="Ex: 001FT6 >>> fer 6 Turquie">
				</div>
				<button type="submit" class="btn btn-info">Enregistrer</button>
------>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
<!--- Ajax Hide/Show script--------------------->
        <script>
        $(document).ready(function(){


            $("#action").click(function(){
                var reference_x=$("#reference_x").val();
                $.ajax({
                    url:'test_ref.php',
                    method:'POST',
                    data:{
                        reference_x:reference_x
                    },
                    
                   success:function(data){
                       alert("Successfully Saved");
                       $("#warning_msg").load(" #warning_msg");
                       //setTimeout(function () {$("#loader").hide();}, 3000); //Without 3s it not work

                   }
                   
                });
            });
        });
    </script>
    <!---------------------------------------------->

<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>