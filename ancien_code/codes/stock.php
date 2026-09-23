<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ajouter Stock et Inventaire</title>
<script src="js/jquery-3.5.1.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/mdb.min.css">
	<!---add other css--->
<link rel="stylesheet" href="css/w3.css">

<link rel="stylesheet" href="css/top20.css">
</head>
<body style="background: #6c757d">
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
   $numero_commande=$_COOKIE['numero_commande'];
   $annuler = "Annuler?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie=$_COOKIE['point_de_vente_cookie'];
 }
$vers_controle ='';
if (isset($_COOKIE['msg_validation'])) 
{
   $point_de_vente_cookie=$_COOKIE['msg_validation'];
   $vers_controle = 'Controler stock?';
 	//----PLAY SOUND-------------------------
	?>
   <script type="text/javascript">
	var audio = new Audio('sound/done.mp3').play();
	</script>
	<?php
	//----PLAY SOUND-------------------------
 }

?>
<h4 class="text-center text-primary"><a href="stock_recap.php"> <?php echo $vers_controle; ?></a></h4>
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
   $query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
 }else{
$limit = 500;
$query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC LIMIT $limit";
}
	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array($username));	

 

$query_stock_prep = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC;";
$query_stock_prep = $bdd->prepare($query_stock_prep);

$query_stock_prep->execute(array($username));
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
 	$parentShop = "Tsena";
$subShop = ""; // default
$client_name = $parentShop; // initial display

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
    <h2 class="top20_style">stock</h2>
    </div>
 <div class="text-center">
	<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#Demarrer">Demarrer</button>
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
		<label>Ex: Choisir la date</label>
		<input class="form-control" value="" name="description_date" type="DATE">
	</div>
	<!------------------AUTO LISTE SHOP------------------>
	<?php if($username == 'TRoyal') { ?>
<div class="form-group">
    <label>Point de vente</label>
    <select class="form-control" id="parentShop" name="point_de_vente">
        <option value="Tsena" selected>Tsena</option>
    </select>
</div>
<div class="form-group">
    <label>Sub Point de vente (Zanaka)</label>
    <select class="form-control" id="childShop" name="sub_point_de_vente">
        <option value="" selected disabled hidden>Choisir Sub Point de vente</option>
        <option value="Etagere A">Etagere A</option>
        <option value="Etagere B">Etagere B</option>
        <option value="Etagere C">Etagere C</option>
        <option value="Etagere D">Etagere D</option>
        <option value="Etagere E">Etagere E</option>
        <option value="Etagere F">Etagere F</option>
        <option value="Etagere G">Etagere G</option>
        <option value="Etagere H">Etagere H</option>
        <option value="Etagere I">Etagere I</option>
        <option value="Etagere J">Etagere J</option>
        <option value="Etagere K">Etagere K</option>
        <option value="Etagere L">Etagere L</option>
        <option value="Etagere M">Etagere M</option>
        <option value="Etagere N">Etagere N</option>
        <option value="Etagere 1">Etagere 1</option>
        <option value="Etagere 2">Etagere 2</option>
        <option value="Etagere 3">Etagere 3</option>
        <option value="Etagere 4">Etagere 4</option>
        <option value="Etagere 5">Etagere 5</option>
        <option value="Etagere 6">Etagere 6</option>
        <option value="Etagere 7">Etagere 7</option>
        <option value="Etagere 8">Etagere 8</option>
        <option value="Etagere 9">Etagere 9</option>
    </select>
</div>

<script>
$(document).ready(function() {
    $("#childShop").change(function() {
        var parent = $("#parentShop").val();
        var sub = $(this).val();
        var display = parent;
        if(sub) {
            display += " | " + sub;
        }
        display += " || <?php echo $description_date; ?> (Activiter No. <?php echo $num_stock; ?>)";

        // Update h4 and table header
        $("#clientDisplay").text(display);
        $("#tableClient").text(display);
    });
});
</script>
	<?php
	} else { ?>
		<div class="form-group">
		<label>Point de vente</label>
		<select class="form-control btn-light" name="point_de_vente">
		<option value="" selected disabled hidden>Choisir Point de vente</option>
		<?php
		$query_shop = "SELECT * FROM shop ORDER BY long_name;";
		$query_shop = $bdd->prepare($query_shop);
		$query_shop->execute(array());
		while ($donnees = $query_shop -> fetch())
		{
		?>
		<option value=<?php echo $donnees['short_name']; ?>><?php echo $donnees['long_name']; ?></option>
	    <?php
	    }
	    ?>
		</select>
	</div>
	<?php
	}
	?>
	
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
		$query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ?");
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
if ($username == 'TRoyal') {
    $parentShop = "Tsena";
    // raha manana cookie sub_point_de_vente
    $subShop = isset($_COOKIE['sub_point_de_vente_cookie']) ? $_COOKIE['sub_point_de_vente_cookie'] : "";
    if (!empty($subShop)) {
        $client_name = $parentShop . " | " . $subShop;
    } else {
        $client_name = $parentShop;
    }
}

##############################################################
		//$description_date = "xxx";
?>
<h4 class="text-center text-danger" id="clientDisplay">
    <?php echo $client_name . " | " . $subShop . " || " . $description_date . " (Activiter No. " . $num_stock . ")"; ?>
</h4>
<br>
<br>
<form role="form" action="simple_search.php" method="POST">
<div class="text-center">
<input type="search" class="btn btn-light light-table-filter" name="key_word" placeholder="Name/Code/Search" value="<?php echo $key_word; ?>">
<button type="submit" class="btn btn-info">Search</button>
<a href="stock_general.php" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-success">Nouveau</button></a>
<br>
<br>
<!---------
<div class="text-center">
    <input type="checkbox" name="checkbox" checked>
    <label>Stock Déja Existé à <b><?php echo $client_name; ?></b> Seulement</label>
</div>
------>
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
		echo "<br><b class='text-light'>"."[".$key_word."]</b>"."<b class='text-danger'> does not exist on the base</b><br>";
		echo "</div>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');
?>
	<div class="container">
		<input id="ancre1" type="search" class="btn btn-light light-table-filter" data-table="table-secondary" placeholder="Filter/Search">
	</div>
	<br>
   <table class="table-secondary table container">
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
$textzone_id = "";
$check_id = "";
while ($donnees = $q -> fetch())
{ 
$textzone_id = "textzone".$i;
$check_id = "myCheck".$i; 
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
      <tr class = "bg-dark text-light">
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['nom_x'] .' : '. $donnees['note_x']; ?></td>
        <td class='text-right'><b>General</b><br>Amparafa<br><b>Ambato</b><br>Soalazaina</td>
        <td class='text-right'><b><?php echo number_format($donnees['prix_de_vente'],0, "", " "); ?></b><br><?php echo number_format($donnees['pu_aparafa'],0, "", " "); ?><br><b><?php echo number_format($donnees['pu_ambato_tantely'],0, "", " "); ?></b><br><?php echo number_format($donnees['pu_soalazaina'],0, "", " "); ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo 'no'.$donnees['id_x']; ?>">Select</button>
    	</td>
      </tr>
		<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo 'no'.$donnees['id_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content btn-brown">
					<div class="modal-header">
					<h4 class="modal-title"><?php echo $donnees['reference_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_stock_prep.php" method="post">
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-warning">
						<label><span class="text-white"> | </span><span class="text-orange-2">QUANTITE (Ex: Atsasany : 0.5 ; Fefany : 0.25)</span></label>
						<input class="btn bg-primary form-control" name="qt" value="1" type="number" step="any">
						</div>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-secondary">
						<label><span class="text-white"> | </span><span class="text-purple">REFERENCE - PRIX FOUNISSEUR (Ariary)</span></label>
						<input class="btn bg-secondary form-control" name="prix_fournisseur" value="<?php echo $pf; ?>" type="number">
						</div>
						<?php if ($client_name =='MoraranoCh' ) { ?>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-success">
						<label><span class="text-white"> | </span><span class="text-success">PRIX AMAROTANA (Ariary)</span></label>
						<input class="btn bg-warning form-control" name="prix_de_vente" value="<?php echo $pv; ?>" type="number">
						</div>
						<?php
							} else { 
						if ($client_name =='Tsena' ) { ?>
							<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
						<span class="text-white"> | </span><input type="checkbox" id="<?php echo $check_id; ?>" onclick="myFunction()" checked>
						<label for="myCheck"><span class="text-light">PRIX AMAROTANA <?php echo strtoupper($client_name); ?></span></label>
						<input class="form-control btn btn-danger" name="prix_client" value="<?php echo $pv; ?>" type="number" id="<?php echo $textzone_id; ?>">
						</div> 
						<?php
						} else {
								?>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-success">
						<label><span class="text-white"> | </span><span class="text-success">PRIX ANOMEZANA ROYAL (Ariary)</span></label>
						<input class="btn bg-success form-control" name="prix_de_vente" value="<?php echo $pv; ?>" type="number">
						</div>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
						<span class="text-white"> | </span><input type="checkbox" id="<?php echo $check_id; ?>" onclick="myFunction()" checked>
						<label for="myCheck"><span class="text-light">PRIX AMAROTANA <?php echo strtoupper($client_name); ?></span></label>
						<input class="form-control btn btn-danger" name="prix_client" value="<?php echo $pv; ?>" type="number" id="<?php echo $textzone_id; ?>">
						</div>
						<?php 
						}
						}	
						 ?>
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
		$query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ?");
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
		$query_user = $bdd->prepare("SELECT user_stock_prep,nom_du_client,numero_stock_prep,description_date FROM stock_prep WHERE description_date LIKE '%Ajout%' GROUP BY user_stock_prep");
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
         $query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,prix_client,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
        $query_stock_prep_list = $bdd->prepare($query_ps);
		$query_stock_prep_list->execute(array($username));
		?>
	<div class="text-center bg-primary">
		<b>TRAITER PAR 
	    <img src="<?php echo $img_path?>" height="80" width="80" align="middle" class="arrondi" />
	    <?php echo $username;?></b>
  	</div>
		<table class="table-info table container">
        <thead>
        <tr>
        	<th class="text-center" colspan="10"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class='text-right'>QT</th>
        <?php
        if ($client_name == 'Amparafa') {echo "<th class='text-right'>PU AMPARAFA</th>";}
        if ($client_name <> 'Amparafa' AND $client_name <> 'Ambato_Tantely' AND $client_name <> 'Soalazaina'){echo "<th class='text-right'>PU GENERAL</th>";};?>
        
        <th class='text-right'>PU ROYAL</th>
        <th></th>
        <th class='text-right'>FAMAROTANA</th>
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
					<td><a href="#ancre1" class="text-success"><?php echo $no; ?></a></td>
					<td><?php echo $donnees['reference_x']; ?></td>
					<td><?php echo $donnees['nom_x']; ?></td>
					<td class='text-right'><?php echo $donnees['qt']; ?></td>
					<td class='text-right'><?php echo number_format($donnees['prix_unitaire'],0, "", " ");?></td>
					<td><span class="w3-badge w3-right <?php echo $color_badge; ?>"><?php echo $Aff1_difference_prix; ?></span></td>
        			<td class='text-right'><?php echo number_format($donnees['prix_client'],0, "", " ");?></td>
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
	echo "<div class='text-center text-light'><b>PAS DE TRAITEMENT EN COURS</b></div>";
    	}//Close IF No.1
} else {

?>

	<h5 class="text-center text-success"><?php echo $msg_ok
	; ?></h5>
	<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
	<div class="text-center">
    <h2 class="top20_style">STOCK</h2>
    </div>
	<div class="container">
		<input id="ancre1" type="search" class="btn btn-light light-table-filter" data-table="table-info" placeholder="Filter/Search">
		<a href="stockp.php"><button type="button" class="btn btn-brown">Facture</button></a>
		<a href="limit.php">
		<input type="button" class="btn float-right bg-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
		</a>
	</div>
	<br>
		<table class="table-info table container">
        <thead>
        <tr class="bg-light">
        	<th class="text-center" colspan="10" id="tableClient">
                <?php echo $client_name . " | " . $subShop . " || " . $description_date . " (Activiter No. " . $num_stock . ")"; ?>
            </th>
        </tr>
        <tr style="background: #c0ca33">
        <th>No.</th>
        <th></th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-right">QT</th>
        
        <?php if ($client_name =='MoraranoCh' ) { ?>
        	<th class='text-pink text-right'>AMAROTANA</th>
		<?php } else {
			# code...
		?>
		<th class='text-right'>PU ROYAL</th>
        <th class="text-pink text-right">AMAROTANA</th>
        <th></th>
        <?php 
        	}
         ?>
        <th class="text-right">MONTANT</th>
        <th>NOTE</th>
        <th class="text-secondary text-right">PU REF</th>
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
$no = 0;
$color_2 ="bg-light";
$textzone_id2 = "";
$check_id2 = "";
while ($donnees = $query_stock_prep_list -> fetch())
{ 
	
	$total_commande = $donnees['sous_total'] + $total_commande;
	$no = $no + 1;
	$textzone_id2 = "textzone2".$no;
	$check_id2 = "myCheck2".$no; 
	$m = $no;
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
	$Aff_difference_prix ="";
	$difference_prix = $donnees['prix_unitaire'] - $current_prix;
	$Aff_difference_prix = number_format($difference_prix,0, "", " ");
	if ($difference_prix > 0) {
		$color_badge = "w3-red";
		$difference_prix = "+".number_format($difference_prix,0, "", " ");
		$Aff_difference_prix = $difference_prix;
	} else {
		$color_badge = "w3-green";
	}
	if ($difference_prix == 0) {$Aff_difference_prix ="";}
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
		<tr class = "bg-dark text-warning">
			
		<td><a href="#ancre1" class="text-success"><?php echo $no; ?></a></td>
		<td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class="text-right"><?php echo $donnees['qt']; ?></td>
		<?php if ($client_name =='MoraranoCh' ) { ?>
			<td class="text-pink text-right"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
		<?php } else {
			# code...
		?>
		<td class="text-right"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
        <td class="text-pink text-right"><?php echo number_format($donnees['prix_client'],0, "", " "); ?></td>
		<td><span class="w3-badge w3-right <?php echo $color_badge; ?>"><?php echo $Aff_difference_prix; ?></span></td>
		<?php 
			}
		 ?>
		<td class="text-right"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<td><?php echo $donnees['note']; ?></td>
		<td class="text-secondary text-right"><?php
		//Check change is valide
		  $verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
		  $verification_prix = $bdd->prepare($verification_prix);
		  $verification_prix->execute(array($donnees['id_x']));
		  $data = $verification_prix -> fetch();
		  echo (number_format($data['prix_fournisseur']+0,0, "", " ")); 
		  $verification_prix -> closeCursor();
		  ?></td>
		<?php
		if ($username=='STANDARD') {
			# VIDE
		} else {		
		?>
		<td>
		<button type="button" class="text-center" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_stock_prep']; ?>"><img src="img/edit_icon.png" height="30" width="30" background alt="Edit" /></button></td>
		<td><a class="center" href="delete_stock_prep.php?id_stock_prep=<?php echo $donnees['id_stock_prep']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a></td>
		<?php
		}//Close else 
		?>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_stock_prep']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content btn-brown">
					<div class="modal-header">
					<h4 class="modal-title">Modifier stock No. <?php echo $donnees['id_stock_prep']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_stock.php" method="post">
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-warning">
						<label> <span class="text-white">| </span><span class="text-orange-2">QUANTITE (Ex: Atsasany : 0.5 ; Fefany : 0.25)</span></label>
						<input class="btn bg-danger form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" Step="any">
						</div>
						<?php if ($client_name =='MoraranoCh' ) { ?>
							<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-success">
						<label class="text-white"> | <span class="text-success">PRIX AMAROTANA (Ariary)</span></label>
						<input class="btn bg-success form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
						<input type="hidden" name="id_stock_prep" value="<?php echo $donnees['id_stock_prep']; ?>">
						</div>
						<?php
							} else {
						# code...
								?>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-success">
						<label class="text-white"> | <span class="text-success">PRIX ANOMEZANA ROYAL  (Ariary)</span></label>
						<input class="btn bg-success form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
						<input type="hidden" name="id_stock_prep" value="<?php echo $donnees['id_stock_prep']; ?>">
						</div>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
						<span class="text-white">| </span><input type="checkbox" id="<?php echo $check_id2; ?>" onclick="myFunction2()">
						<label for="myCheck" class="text-primary">PRIX AMAROTANA <?php echo strtoupper($client_name); ?></label>
						<input class="form-control btn btn-danger" name="prix_client" value="<?php echo $donnees['prix_client']; ?>" type="number" id="<?php echo $textzone_id2; ?>" style="display:none">
						</div>
					<?php } ?>
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
			<?php if ($client_name =='MoraranoCh' ) { ?>
        <th colspan="6">############### TOTAL ###########</th>
         <?php
			} else {
								?>
		<th colspan="8">############### TOTAL ###########</th>
		<?php } ?>
        <th class="text-right"><?php echo number_format($total_commande,0, "", " "); ?></th>
        <th colspan="4"></th>
        <!--------
        <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
        	<th></th>
        <?php } ?>
        ------>
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
<form role="form" action="valider_stock.php" method="post">
<a id="anuler" class="center" href="anuler_stock.php" onclick="confirmationDelete('Anuler cette action?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
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
<script>

function myFunction() {
var myCheck = "";
var textzone = "";
//alert(<?php echo json_encode($i); ?>);
for (var u = 1 ; u < <?php echo json_encode($i); ?>; u++) {
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
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>