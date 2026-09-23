<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>FACTURE</title>
<script src="js/jquery-3.5.1.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
<link rel="stylesheet" href="css/w3.css">
</head>
<body style="background: #FFC2F0">
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<br>
 <br>
 <br>
 <br>
 <br>
 <br>
<?php
$img_path = "img/default_img/default_pdp.png";
//Connect to BD
	include('connect.php');
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
	//Read cookie for validation msg
 
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
   $numero_commande = $_COOKIE['numero_commande'];
   $annuler = "Annuler?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie = $_COOKIE['point_de_vente_cookie'];
 }
$vers_controle = '';
if (isset($_COOKIE['msg_validation'])) 
{
   $point_de_vente_cookie = $_COOKIE['msg_validation'];
   $vers_controle = 'Liste Facture?';
 	//----PLAY SOUND-------------------------
	?>
   <script type="text/javascript">
	var audio = new Audio('sound/done.mp3').play();
	</script>
	<?php
	//----PLAY SOUND-------------------------
 }

?>
<h4 class="text-center text-primary"><a href="facture_recap.php"> <?php echo $vers_controle; ?></a></h4>
<?php

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
	//Query to liste All Waiting stock

if (isset($_COOKIE['limit'])) 
{
   $query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
 }else{
$query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
}
	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array($username));	

 

$query_stock_prep = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC;";
$query_stock_prep = $bdd->prepare($query_stock_prep);

$query_stock_prep->execute(array($username));
//get row count
$total_line=$query_stock_prep->rowCount ();
//echo $total_line;
//Read cookie to resolve new client name
$time_for_nouveau_stock_cookie = 0;
if (isset($_COOKIE['time_for_nouveau_stock_cookie'])) 
{
   $time_for_nouveau_stock_cookie = $_COOKIE['time_for_nouveau_stock_cookie'];
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
 if ($username == 'STANDARD') {
 	$decision_new_stock = 1;
 } 
 
 if ($decision_new_stock == 0) {
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
			<h4 class="modal-title">Date et Fournisseur du Facture</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="new_facture.php" method="post">
	<div class="form-group">
		<label>Choisir la Date du facture</label>
		<input class="form-control" value="" name="description_date" type="DATE">
	</div>
	<!------------------AUTO LISTE SHOP------------------>
	<div class="form-group">
		<label>Nom du Fournisseur (Ex : Timotimoka, Aloalo, Tsaramora, ...)</label>
		<input class="form-control" value="" name="fournisseur" type="text">
	</div>
	<!------------------END AUTO LISTE SHOP------------------>
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
<!------------SIMPLE SEARCH---------------->
<?php 
	if ($username == 'STANDARD') {
		#VIDE
	}
	else 
	{
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{$key_word = $_COOKIE['key_word'];}
##########################################################
$client_name = "";
		$query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep = ?");
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['numero_stock_prep'];
         $client_name = $donnees['nom_du_client']."";
         $description_date = $donnees['description_date'];
          }
$query_client_name->closeCursor();

//HANDLE EMPTY CLIENT NAME AT STARING SELECT EMPTY STOCK_PREP
if (strlen($client_name) == 0) {
		$query_client_name = $bdd->prepare("SELECT * FROM user WHERE User_Name = ?");
        $query_client_name->execute(array($username));
		while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['stock_numero_commande'];
         $client_name = $donnees['stock_client_name']."";
         $description_date = $donnees['stock_description_date'];
          }
			$query_client_name->closeCursor();
}
##############################################################
		//$description_date = "xxx";
?>
<div class="text-center mb-3">
    <a href="vente.php" target="_blank" class="btn btn-warning">
        ➖ Nouvelle Vente
    </a>
</div>

<h2 class="text-center text-danger"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></h2>
<br>
<br>
<form role="form" action="simple_search_facture.php" method="POST">
<div class="text-center">
<input type="search" class="btn btn-light light-table-filter" name="key_word" placeholder="Name/Code/Search" value="<?php echo $key_word; ?>">
<button type="submit" class="btn btn-info">Search</button>
<a href="stock_general.php" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-success">Nouveau</button></a>
<br>
<br>
<div class="text-center">
    <input type="checkbox" name="checkbox">
    <label>Stock Déja Existé à <b><?php echo $client_name; ?></b> Seulement</label>
</div>
</div>
</form>
<?php
	} //close else
?>
<!---------------search result---------------------------->
<?php
//$key_word = "";

if (isset($_COOKIE['key_word'])) 
{
   //echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT * FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? GROUP BY id_x ORDER BY reference_x';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%"));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<div class='text-center'>";
		echo "<br><b class='text-danger'>"."[".$key_word."]"." does not exist on the base</b><br>";
		echo "</div>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');
?>
	<div>
		<input id="ancre1" type="search" class="btn btn-light light-table-filter" data-table="table-secondary" placeholder="Filter/Search">
	</div>
	<br>
   <table class="table-secondary table">
        <thead>
        <tr class="bg-warning">
        <th></th>
        <th>Nom/Description de Produit</th>
        <th></th>
        <th class='text-right'>Prix</th>
        <th>Ref ID</th>
        <th>Ajouter</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$i = 1;
$image_path_x = "img_x/default_x.png";
//Query searcher word
$color_1 = "bg-warning";
while ($donnees = $q -> fetch())
{ 
//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }
$pf = $donnees['prix_fournisseur']+0;
$pv = $donnees['prix_de_vente']+0;
if ($client_name == "Ambato_Tantely") {$pv = $donnees['pu_ambato_tantely']+0;}
if ($client_name == "Amparafa") {$pv = $donnees['pu_aparafa']+0;} 
if ($client_name == "Soalazaina") {$pv = $donnees['pu_soalazaina']+0;}
    //Handle colore select table
	if ($color_1 =="bg-warning") {
		 $color_1 = ""; 
	}else {
		$color_1 ="bg-warning";
	}  
?>
<!------------------------------------------------->
      <tr class="<?php echo $color_1;?>">
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['nom_x'] .' : '. $donnees['note_x']; ?></td>
        <td class='text-right'><b>Reference</b><br>Amparafa<br><b>Ambato</b><br>Soalazaina</td>
        <td class='text-right'><b><?php echo number_format($pf,0, "", " "); ?></b><br><?php echo number_format($donnees['pu_aparafa'],0, "", " "); ?><br><b><?php echo number_format($donnees['pu_ambato_tantely'],0, "", " "); ?></b><br><?php echo number_format($donnees['pu_soalazaina'],0, "", " "); ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
    	<td>
    		<?php 
    		   if (isset($_COOKIE['checkbox'])) 
    		{
    			?>
    			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo 'no'.$donnees['id_x']; ?>">Select</button>
    			<?php
    		} else 
    		{
    			$query_stock_exist = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'stock';";
			    $qr = $bdd->prepare($query_stock_exist);

			    $qr->execute(array($donnees['id_x'],$client_name));
			    $nb_line_existant=$qr->rowCount ();
			    $qr->closeCursor();
			    if ($nb_line_existant > 0) {
			    
			    ?>
			    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo 'no'.$donnees['id_x']; ?>">Select</button>
			    <?php
			    }else{
			    ?>
			    <button type="button" class="btn btn-success" disabled>Select</button>
			    <?php	
			    }//if $nb_line_existant
    		} //else
			?>
    	</td>
      </tr>
		<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo 'no'.$donnees['id_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title"><?php echo $donnees['reference_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_facture_prep.php" method="post">
						<div class="form-group">
						<label><b>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</b></label>
						<input class="btn bg-info form-control" name="qt" value="1" type="number" step="any">
						</div>
						<div class="form-group">
						<label><b>Reference | Prix Fournisseur (Ariary)</b></label>
						<input class="btn bg-warning form-control" name="prix_fournisseur" value="<?php echo $pf; ?>" type="number">
						</div>
						<div class="form-group">
						<label><b>Note/Fanamarihana (raha ilaina)</b></label>
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
<!-------FORM DE MODIFIER IMAGE-------->
    <!-- modal form MODIFIER IMAGE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "img".$donnees['id_x']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Produit No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
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
<br>
<br>
<br>
<!-------------END SIMPLE SEARCH-------------------------->
<?php
##################### CLIENT NAME #################################################
/*
		$query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep = ?");
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['numero_stock_prep'];
         $client_name = $donnees['nom_du_client'];
         $description_date = $donnees['description_date'];
          }
		$query_client_name->closeCursor();
		//$description_date = "xxx";
###################################################################################
*/
if ($total_line == 0)
{
	if ($username=='STANDARD') { //IF No.1
	//--------------LISTE OF WAITING STOCK---------------
# QUERY GET DISTINCT USER
		$query_user = $bdd->prepare("SELECT user_stock_prep,nom_du_client,numero_stock_prep,description_date FROM stock_prep WHERE description_date LIKE '%Facture%' GROUP BY user_stock_prep");
        $query_user->execute(array());
        //get row count
		$nb_line=$query_user->rowCount ();
		if ($nb_line > 0){
		while ($donnees = $query_user->fetch())
         {
         //guet user, client name, Numero stock, description date
         $username = $donnees['user_stock_prep'];
         $client_name = $donnees['nom_du_client'];
         $num_stock = $donnees['numero_stock_prep'];
         $description_date = $donnees['description_date'];
         //Query for nom du client, numero de commande et date, path
		$query_client_name = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
		        $query_client_name->execute(array($username));
		while ($donnees = $query_client_name->fetch())
         {
        	//$numero_commande = $donnees['numero_commande'];
         	//$client_name = $donnees['point_de_vente'];
         	//$description_date = $donnees['description_date'];
         	$img_path = $donnees['img_path'];
          }
		$query_client_name->closeCursor();

		//LIST OF WAITING COMMANDE FOR EACH USER
         $query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
        $query_stock_prep_list = $bdd->prepare($query_ps);
		$query_stock_prep_list->execute(array($username));
		?>
	<div class="text-center bg-primary">
		<b>TRAITER PAR 
	    <img src="<?php echo $img_path?>" height="80" width="80" align="middle" class="arrondi" />
	    <?php echo $username;?></b>
  	</div>
		<table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="10"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class='text-right'>QT</th>
        <?php if ($client_name == 'Ambato_Tantely') {echo "<th class='text-right'>PU TANTELY</th>";};
        if ($client_name == 'Amparafa') {echo "<th class='text-right'>PU AMPARAFA</th>";}
        if ($client_name <> 'Amparafa' AND $client_name <> 'Ambato_Tantely' AND $client_name <> 'Soalazaina'){echo "<th class='text-right'>PU GENERAL</th>";};?>
        <th></th>
        <th class='text-right'>MONTANT</th>
        <th>NOTE</th>
        </tr>
        </thead>
        <tbody>
		<?php
		$total_commande = 0;
		$no = 0 ;
		while ($donnees = $query_stock_prep_list -> fetch())
			{ 
				$total_commande = $donnees['sous_total'] + $total_commande;
				$no = $no + 1;
	//Handle diminution or augmentation prix
	$query_product_search = "SELECT * FROM produit WHERE id_x = ?";
	$q = $bdd->prepare($query_product_search);
	$q->execute(array($donnees['id_x']));
	$data = $q -> fetch();
	$current_prix = $data['prix_de_vente']+0;
	if ($client_name == 'Ambato_Tantely') {$current_prix = $data['pu_ambato_tantely']+0;}
	if ($client_name == 'Amparafa') {$current_prix = $data['pu_aparafa']+0;}
	if ($client_name == 'Soalazaina') {$current_prix = $data['pu_soalazaina']+0;}
	$q->closeCursor();
	
	$Aff1_difference_prix ="";
	$difference_prix = $donnees['prix_unitaire'] - $current_prix;
	$Aff1_difference_prix =number_format($difference_prix,0, "", " ");
	if ($difference_prix > 0) {
		$color_badge = "w3-red";
		$difference_prix = "+".number_format($difference_prix,0, "", " ");
		$Aff1_difference_prix =$difference_prix;
	} else {
		$color_badge = "w3-green";
	}
	if ($difference_prix == 0) {$Aff1_difference_prix ="";}
	//---------------------------------------------------------
			?>
				<tr>
					<td><a href="#ancre1"><?php echo $no; ?></a></td>
					<td><?php echo $donnees['reference_x']; ?></td>
					<td><?php
					if ($donnees['reference_x'] == 'XXXX') {
						echo $donnees['note'].' - ';
					}
					echo $donnees['nom_x']; ?></td>
					<td class='text-right'><?php echo $donnees['qt']; ?></td>
					<td class='text-right'><?php echo number_format($donnees['prix_unitaire'],0, "", " ");?></td>
					<td><span class="w3-badge w3-right <?php echo $color_badge; ?>"><?php echo $Aff1_difference_prix; ?></span></td>
					<td class='text-right'><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
					<td><?php echo $donnees['note']; ?></td>
				</tr>

			<?php
			}
			?>
		<tr>
        <th colspan="6">############### TOTAL ###########</th>
        <th class='text-right'><?php echo number_format($total_commande,0, "", " "); ?></th>
        <th colspan="3"></th>
        </tr>
</tbody>
</table>
<br>
<br>
<?php
         }//WHILE USER
         $query_user->closeCursor();
		}//if USER STANDARD
	//---------------------------------------------------
		} else {		
		//echo "Aucune commande en cours";
	echo "<div class='text-center'><b>PAS DE TRAITEMENT EN COURS</b></div>";
    	}//Close IF No.1
} else {
?>
	<h5 class="text-center text-success"><?php echo $msg_ok
	; ?></h5>
	<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
	<div>
		<input id="ancre1" type="search" class="btn btn-light light-table-filter" data-table="table-info" placeholder="Filter/Search">
		<input type="button" class="btn float-right bg-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
	</div>
	<br>
		<table class="table-info table">
        <thead>
        <tr class="bg-light">
        	<th class="text-center" colspan="11"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr class="bg-light">
        <th>No.</th>
        <th></th>
        <th>Code</th>
        <th>Designation</th>
        <th class="text-right">QT</th>
        <th class="text-left">PU REF</th>
        <th></th>
        <th class="text-right">MONTANT</th>
        <th>NOTE</th>
        <?php
		if ($username=='STANDARD') {
			# VIDE
		} else {		
		?>
        <th colspan="2">MODIFIER/SUPPR</th>
        <?php
    	}//Close ELSE
        ?>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$no = ($query_stock_prep_list ->rowCount ())+1;
$color_2 ="bg-light";
while ($donnees = $query_stock_prep_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$no = $no - 1;

	//---------------------------------------------------------
	//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }    

     //Handle colore select table
	if ($color_2 =="bg-light") {
		 $color_2 = ""; 
	}else {
		$color_2 ="bg-light";
	}  
?>
		<tr class="<?php echo $color_2; ?>">
			
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php
		if ($donnees['reference_x'] == 'XXXX') {
						echo $donnees['note'].' - ';
					}
		echo $donnees['nom_x']; ?></td>
		<td class="text-right"><?php echo $donnees['qt']; ?></td>
		<td class="text-left"><b><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></b></td>
		<td class="text-left"><?php
		//Check change is valide
		  $verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
		  $verification_prix = $bdd->prepare($verification_prix);
		  $verification_prix->execute(array($donnees['id_x']));
		  $data = $verification_prix -> fetch();
		  $variation = ($donnees['prix_unitaire']+0)-($data['prix_fournisseur']+0);
		  if ($variation > 0) {
		  	if (($data['prix_fournisseur']+0) == 0) {
		  		echo "<span class='w3-badge w3-right w3-blue'>NONE</span>";
		  	} else {
		  		echo "<span class='w3-badge w3-right w3-red'>+".$variation."</span>";
		  	}
		   	
		   }
		 if ($variation <= 0) {
		   	echo "<span class='w3-badge w3-right w3-green'>".$variation."</span>";
		   }
		  $verification_prix -> closeCursor();
		  ?></td>
		<td class="text-right"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<td><?php echo $donnees['note']; ?></td>
		<?php
		if ($username=='STANDARD') {
			# VIDE
		} else {		
		?>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_stock_prep']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_facture_prep.php?id_stock_prep=<?php echo $donnees['id_stock_prep']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
		<?php
		}//Close else 
		?>
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
					<form role="form" action="modifier_facture.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="btn bg-info form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" Step="any">
						</div>
						<div class="form-group">
						<label>Prix Reference/Fournisseur (Ariary)</label>
						<input class="btn bg-warning form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
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
          <form role="form" action="modifier_image_produit_stock.php" enctype="multipart/form-data" method="post">
            <div class="form-group text-center">
              <img src="<?php echo $image_path_x;?>" height=100% width=100% align="middle"/>
            </div>
            <div class="form-group">
            <label><b>MODIFY IMAGE?</b></label>
            <input class="btn btn-warning form-control" name="uploadedimage" type="file">
            <input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
            </div>
            <div class="form-group">
            <button type="submit" name="Upload Now" class="btn btn-success">Save Image</button>
            <?php if (strlen($donnees['img_path_x']) != 0) {
            ?>
            <a class="btn btn-danger float-right" href="delete_img_produit_stock.php?id_x=<?php echo $donnees['id_x']; ?>">Remove Image</a>
            <?php
            }
            ?>
            </div>
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
        <th colspan="7">############### TOTAL ###########</th>
        <th class="text-right"><?php echo number_format($total_commande,0, "", " "); ?></th>
        <th colspan="3"></th>
        </tr>
</tbody>
</table>
<div class="text-center">
<?php
if ($username == 'STANDARD') {
	# VIDE
} else {
$state_suppr = "pointer-events: none";
?>
<form role="form" action="valider_facture.php" method="post">
<a id="anuler" class="center" href="anuler_facture.php" onclick="confirmationDelete('Anuler cette action?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
<a style="<?php echo $state_suppr; ?>" id="valider" class="center" href="#" onclick="confirmationDelete('Valider le stock de <?php echo $client_name ;?>?');return false; post ;"><button type="submit" class="btn btn-success">Valider</button></a>
<input name="vente_id_array" class="btn btn-light" type="password" oninput="unlock($(this));" placeholder="">
</form>
<?php
}// close ELSE
 ?>
<br>
<br>
<br>
<br>
</div>
<?php
}
?>
<?php  
 }
 ?>
<!-------------------------------------->
	 
</body>

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
function unlock(e){
	var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2020") {
    	$("#valider").removeAttr("style");
	}
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>