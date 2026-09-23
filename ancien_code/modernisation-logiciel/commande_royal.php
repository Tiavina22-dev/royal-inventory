<!DOCTYPE html>
<html>
<head>
	<title>commande</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link rel="stylesheet" type="text/css" href="css/style_Index.css">
	<link href="css/arrondi.css" rel="stylesheet">
	<link rel="stylesheet" href="css/w3.css">

</head>
<body style="background: #CF9FFF">
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
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
 $controle = "";
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
   $controle = "Controle de vente?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie=$_COOKIE['point_de_vente_cookie'];
 }
?>
<h4 class="text-center"><?php echo $point_de_vente_cookie; ?></h4>
<h5 class="text-center"><b><span class="text-success"><?php echo $msg_validation; ?></span><a class="center" href="anuler_validation.php?numero_commande=<?php echo $numero_commande; ?>" onclick="confirmationDelete('Anuler la validation?');return false; post ;"><span class="text-danger"><?php echo $annuler; ?></span></a></b></h5>
<h5 class="text-center text-info"><a class="center" href="vente_recap.php"><b><?php echo $controle; ?></b></a></h5>
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
		$query_commande_list = $bdd->prepare('SELECT img_path_x, id_commande,nom_du_client,nom_x,reference_x,note_commande,state,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY state ASC;');
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
        <th class='text-right'>QT</th>
        <th class='text-right'>PU</th>
        <th></th>
        <th class='text-right'>MONTANT</th>
        <?php
        if ($client_name=='Amparafa') {
        ?>
        <th class='text-right'>PU A/fa</th>
        <th class='text-right'>MT A/fa</th>
        <th class='text-right'>Bc A/fa</th>
        <?php
        } 
        ?>
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
	//Handle diminution or augmentation prix
	$query_product_search = "SELECT * FROM produit WHERE id_x = ?";
	$q = $bdd->prepare($query_product_search);
	$q->execute(array($donnees['id_x']));
	$data = $q -> fetch();
	$current_prix = $data['prix_de_vente']+0;
	if ($client_name=='Ambato_Tantely') {
		$current_prix = $data['pu_ambato_tantely']+0;
	}
	if ($client_name=='Amparafa') {
		$current_prix = $data['pu_aparafa']+0;
	}
	$q->closeCursor();

	$Aff1_difference_prix ="";
	$difference_prix = $donnees['prix_unitaire'] - $current_prix;
	$Aff1_difference_prix =  number_format($difference_prix,0, "", " ");
	if ($difference_prix > 0) {
		$color_badge = "w3-green";
		$difference_prix = "+". number_format($difference_prix,0, "", " ");
		$Aff1_difference_prix = $difference_prix;
	} else {
		$color_badge = "w3-red";
	}
	if ($difference_prix == 0) {$Aff1_difference_prix ="";}
	//---------------------------------------------------------number_format($donnees['sous_total'],0, "", " ")
?>
	<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class='text-right'><?php echo $donnees['qt']; ?></td>
		<td class='text-right'><?php echo  number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
		<td><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $Aff1_difference_prix; ?></span></td>
		<td class='text-right'><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<?php
        if ($client_name=='Amparafa') {
        ?>
        <td class='text-right'><?php echo number_format($donnees['prix_aparafa'],0, "", " "); ?></td>
		<td class='text-right'><?php echo number_format($donnees['sous_total_aparafa'],0, "", " "); ?></td>
		<td class='text-right'><?php echo number_format(($donnees['sous_total_aparafa']-$donnees['sous_total']),0, "", " "); ?></td>
        <?php
        } 
        ?>
		<td><?php echo $donnees['note_commande']; ?></td>
	</tr>
<?php
}
?>
		<tr>
        <th colspan="6">########TOTAL #####</th>
        <th class='text-right'><?php echo number_format($total_commande,0, "", " "); ?></th>
        <th></th>
        <?php
        if ($client_name=='Amparafa') {
        	$difference = $sous_total_aparafa-$total_commande;
	        if ($difference < 0)
	        {
	        	$difference = 0;
	        }
        ?>
        <th class='text-right'><?php echo number_format($sous_total_aparafa,0, "", " "); ?></th>
        <th class='text-right'><?php echo number_format($difference,0, "", " ");?></th>
        <th></th>
        <?php
        } 
        ?>
        </tr>
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
	        <th class='text-right'>MONTANT</th>
	        <?php
	        if ($client_name=='Amparafa') {
	        ?>
	        <th class='text-right'>APARAFA</th>
	        <?php
	        } 
	        ?>
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
		<td class='text-right'><?php echo $donnees['motif']; ?></td>
		<td class='text-right'><?php echo number_format($donnees['montant'],0, "", " "); ?></td>
		<?php
	    if ($client_name=='Amparafa') {
	    ?>
	    <td class='text-right'><?php echo number_format($donnees['depense_aparafa'],0, "", " "); ?></td>
	    <?php
	    } 
	    ?>
		<th></th>
	</tr>
<?php
}
$query_depense_list->closeCursor();
?>
		<tr>
        <th colspan="3">########TOTAL DEPENSE#####</th>
        <th class='text-right'><?php echo number_format($total_depense,0, "", " "); ?></th>
        <?php
	    if ($client_name=='Amparafa') {
	    ?>
	    <th class='text-right'><?php echo number_format($total_depense_aparafa,0, "", " "); ?></th>
	    <?php
	    } 
	    ?>
	    <th class='text-right'>TOTAL: <?php echo number_format(($total_depense+$total_depense_aparafa),0, "", " "); ?></th>
        </tr>
</tbody>
</table>
<p class="form-control text-center text-light" style="background-color: purple"><b>MT MAHAVANTANA =<?php echo floor($total_commande);?> | Depense MAHAVANTANA:<?php echo $total_depense;?> <?php
	    if ($client_name=='Amparafa') {
	    	echo '| Depense A/FA:'.$total_depense_aparafa;
	    ?>
	    
	    <?php
	    } 
	    ?> | Reste MAHAVANTANA =<?php echo floor($total_commande-$total_depense);?>
	    <?php
	    if ($client_name=='Amparafa') {
	    	echo " | Reste GLOBAL=";
	    	echo floor($sous_total_aparafa-$total_depense_aparafa-$total_depense);;
	    ?>
	    
	    <?php
	    } 
	    ?></b></p>
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

} else { //IF NOT STANDARD USER
	# code...
#####################################################
?>
<?php
//Connect to BD

//Query to liste All Waiting command
$query_commande_list = $bdd->prepare('SELECT img_path_x,id_commande,nom_du_client,nom_x,reference_x,note_commande,state,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY state ASC, id_commande DESC;');
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
 if (isset($_COOKIE['total_line'])) 
{
   $total_line=$_COOKIE['total_line'];
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
<form role="form" action="new_client_name_commande_royal.php" method="post">
	<div class="form-group">
		<label>Choisir la date du journal</label>
		<input class="form-control" value= <?php $today = date("Y-m-d"); echo $today; ?> name="date_journal" type="DATE">
	</div>
	<div class="form-group">
		<label>Point de vente</label>
		<select class="form-control btn" name="point_de_vente">
		<option value="" disabled hidden>Choisir Point de vente</option>
		<option value="Ambaibo_Electronique" selected>ELECTRONIQUE</option>
	    <option value="Ambaibo_Tole">Ambaibo TOLE</option>
	    <option value="Morarano">MORARANO</option>
	    <option value="Andrefana">ANDREFANA</option>
	    <option value="Ambaibo_loko">Ambaibo LOKO</option>
	    <option value="Amparafa">AMPARAFA</option>
	    <option value="Ambato_Tantely">AMBATO Tantely</option>
	    <option value="Ambato_veve_photo">VEVE</option>
	    <option value="Bejofo">BEJOFO</option>
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
//echo "Hi SAMUEL";
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
//--------------------------------------------------
//HANDLE OUBLIE DE VALIDER REGULIEREMENT POUR L'ELECTRONIQUE
	//GET DATE ON description date chaine
		$description_date_english = $description_date;
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date_english, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $description_date_english = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $description_date_english = str_replace('- ', '/', $description_date_english);
        $description_date_english = str_replace('/ ', '/', $description_date_english);
        $description_date_english = str_replace(' / ', '/', $description_date_english);
        $description_date_english = str_replace(' -', '/', $description_date_english);
        $description_date_english = str_replace(' - ', '/', $description_date_english);
        $description_date_english = str_replace('-', '/', $description_date_english);
    	//Convert date to english format for compare
        $description_date_english = DateTime::createFromFormat('d/m/y', $description_date_english);
		$description_date_english = $description_date_english -> format('y/m/d');
    	}
		//GET TODAY DATE
  		$today = date("y/m/d");
		$blockage = 'disabled';
		$text = 'ENTANA LAFO OMALY IO! <br>VALIDEO ALOHA VAO MAMPIDITRA NY ANDROANY IANAO';
		if ($today==$description_date_english) {
			$blockage = '';
			//$text = 'HAMARINO TSARA ALOHA VAO ATAO PRINTY';
			$text = '';
		}
		//echo $today;
//--------------------------------------------------
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
}
?>
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<form role="form" action="simple_search_commande_mahavantana.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter btn btn-light" name="key_word" placeholder="Name/Code/Search" value="<?php echo $key_word; ?>" <?php echo $blockage; ?>>
<button type="submit" class="btn btn-info" <?php echo $blockage; ?>>Search</button>
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#Modal_depense" <?php echo $blockage; ?>>Depense</button>
</div>
<div class="text-center">
    <input type="checkbox" name="checkbox" checked>
    <label>Chercher Dans le Stock Existant Seulement</label>
</div>
<h1 class="text-danger text-center"><?php echo $text; ?></h1>
</form>
<!---------------search result---------------------------->
<?php
//$key_word = "";
$checkbox = 'TRUE';
if (isset($_COOKIE['key_word'])) 
{
   //$key_word=$_COOKIE['key_word'];
   //echo $key_word;
   //Query to liste searched product
   //VERIFY CHECKBOX
	//-----------------SEARCH TIPS--------------------------------------------------
	$key_word0 = str_replace(array('-','/','_'), ' ', $key_word);
	$key_word1 = strtok($key_word0," ");
	$key_word2 = explode(' ',str_replace(array('-','/'), ' ', $key_word0));
	$key_word2 = end($key_word2);
	if (strlen($key_word2) < 2) {
		$key_word2 = $key_word0;
	}
	//------------
	if (isset($_COOKIE['checkbox'])) 
	{$checkbox = $_COOKIE['checkbox'];}
	//-----------------------------
	if ($checkbox == 'TRUE') {
		# PRODUIT EXISTANT DANS LE STOCK SEULEMENT
		$query_product_search = "SELECT produit.id_x as id_x, nom_x, prix_de_vente,prix_fournisseur, qt, reference_x, note_x,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE (nom_x LIKE ? OR nom_x LIKE ? OR nom_x LIKE ? OR reference_x LIKE ? OR produit.id_x LIKE ?) AND type_de_mvt='stock' AND nom_client_fournisseur = ? GROUP BY reference_x ORDER BY date_time";
		$q = $bdd->prepare($query_product_search);

		$q->execute(array("%".$key_word."%","%".$key_word1."%","%".$key_word2."%", "%".$key_word."%", "%".$key_word."%" , $client_name));
	} else {
		# GLOBAL PRODUIT
		$query_product_search = 'SELECT produit.id_x as id_x, nom_x, prix_de_vente,prix_fournisseur, qt, reference_x, note_x,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x WHERE nom_x LIKE ? OR nom_x LIKE ? OR nom_x LIKE ? OR reference_x LIKE ? OR produit.id_x LIKE ? GROUP BY reference_x ORDER BY date_time';
		$q = $bdd->prepare($query_product_search);

		$q->execute(array("%".$key_word."%","%".$key_word1."%","%".$key_word2."%", "%".$key_word."%", "%".$key_word."%"));
	}
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<p class='text-center';><b>".$client_name." does <span class='text-danger';>NOT have "."".$key_word."</span> on his stock! Add first</b></p>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div>
		<input id="ancre1" type="search" class="light-table-filter btn btn-light" data-table="table-secondary" placeholder="Filter/Search">
	</div>
	<br>
   <table class="table-secondary table">
        <thead class = "table-danger">
        <tr>
        <th></th>
        <th></th>
        <th>Nom/Description de Produit</th>
        <th>Ref ID</th>
        <th>Prix</th>
        <th>Qte Dispo</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php

//Query searcher word
$check_id = "";
$textzone_id = "";
$image_path_x = "img_x/default_x.png";
$search_color_table = 'table-success';
$j = 1;

while ($donnees = $q -> fetch())
{ 
    //HANDLE QT DISPO FOR EACH POINT DE VENTE
	$query_qt = "SELECT SUM(qt) as qt FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? GROUP BY id_x";
    $query_qt = $bdd->prepare($query_qt);
    $query_qt->execute(array($donnees['id_x'],$client_name));
    $data_qt = $query_qt -> fetch();
    $qt_dispo_v1 = $data_qt['qt']*1;
   //------------------------------------
     $check_id = "myCheck".$i; 
     $textzone_id = "textzone".$i;
     //IMAGE PATH_
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }
     //GET LIST PRIX
   $query_prix = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL";
    $qs = $bdd->prepare($query_prix);

    $qs->execute(array($donnees['id_x']));
//GET ALL PRIX number_format($difference_prix,0, "", " ")
    $pu_list='';
    $pu_list="<b># ".number_format(($donnees['prix_de_vente']+0),0, "", " ")." : PU General</b>"."<br>"."# ".number_format(($donnees['pu_aparafa']+0),0, "", " ")." : PU Amparafa"."<br>"."<b># ".number_format(($donnees['pu_ambato_tantely']+0),0, "", " ")." : PU Ambato</b>"."<br>";

    $k = 0;
    while ($donnees1 = $qs -> fetch())
	{
		$k = $k+1;
		if ($k < 5) {	
	$pu_list = $pu_list."# ".number_format(($donnees1['prix_unitaire']+0),0, "", " ")." : ".$donnees1['nom_client_fournisseur']." (".$donnees1['type_de_mvt'].")"."<br>";
	}
	}
	$qs->closeCursor();
	//Handle Montant
	$id_montant = $j.'_id_montant';
	$id_qt = $j.'_id_qt';
	$id_pu = $j.'_id_pu';
	$id_montant3 = $j.'_id_montant3';
	$j = $j+1;

	//Specify PU by point de vente
	$pu = $donnees['prix_de_vente']+0;
	if ($client_name == 'Amparafa') {$pu = $donnees['pu_aparafa']+0;}
	if ($client_name == 'Ambato_Tantely') {$pu = $donnees['pu_ambato_tantely']+0;}
	//Handle blanc xxxx
	$statexx_note_id = 'state';
	$statexx_btn_id = 'statebtn';
	$statexx = "enabled";
	if ($donnees['reference_x']=='XXXX') {
		$statexx = "disabled";
		$statexx_note_id = $donnees['id_x'].'state';
		$statexx_btn_id = $donnees['id_x'].'stateb';
	}
	//Handle color table
	if ($search_color_table == 'table-success') {$search_color_table = 'table-warning';} else {$search_color_table = 'table-success';}
?>
<!------------------------------------------------->
        <tr class = "<?php echo $search_color_table; ?>">
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo ("no".$donnees['id_x']); ?>">Choisir</button></td>
        <td><?php echo $donnees['nom_x'].' : '.$donnees['note_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $pu_list; ?></td>
        <td><?php echo $qt_dispo_v1; ?></td>
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
					<form role="form" action="insert_commande_royal.php" method="post">
						<div class="form-group">
						<label><b>QUANTITE</b> (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control btn btn-light" name="qt" value="1" type="number" step="any" id="<?php echo $id_qt; ?>" oninput="Montant2Function($(this));">
						</div>
						<div class="form-group">
						<label><b>PRIX UNITAIRE (Ariary)</b></label><span class="w3-badge w3-right w3-margin-right w3-green" id="<?php echo $id_montant; ?>"><?php echo $pu; ?></span>
						<input class="form-control btn btn-light" name="prix_de_vente" value="<?php echo $pu; ?>" type="number" oninput="MontantFunction($(this));" id="<?php echo $id_pu; ?>">
						</div>
						<div class="form-group">
						<label class="text-danger"><b>MASOKARENA (Reference)</b></label>
						<input class="form-control btn btn-warning" name="prix_fournisseur" value="<?php echo $donnees['prix_fournisseur']+0; ?>" type="number" step="any">
						</div>
						<?php if ($client_name == 'Amparafa') {;?>
						<div class="bg-warning">
						<input type="checkbox" id="<?php echo $check_id; ?>" onclick="myFunction()">
						<label for="myCheck">APARAFA IHANY</label><span class="w3-badge w3-right w3-margin-right w3-red" id="<?php echo $id_montant3; ?>"><?php echo $donnees['pu_aparafa']+0; ?></span>
						<input class="form-control btn-danger" name="prix_aparafa" value="<?php echo $donnees['pu_aparafa']+0; ?>" type="number" id="<?php echo $textzone_id; ?>" style="display:none" oninput="Montant3Function($(this));">
						</div>
						<?php }else{; ?>
						<div>
						<input class="form-control btn-danger" id="<?php echo $textzone_id; ?>" style="display:none">
						</div>
						<?php }; ?>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" id="<?php echo $statexx_note_id;?>" type="text" name="note_commande" oninput="StatusCheck($(this));">
						</div>
						<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
						<button type="submit" id="<?php echo $statexx_btn_id;?>" class="btn btn-success" <?php echo $statexx; ?>>Valider</button>
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
<!-------------END SIMPLE SEARCH-------------------------->
<?php

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
 //Check if there are commande en cours
 $query_commande_list_in_progress = $bdd->prepare("SELECT img_path_x,id_commande,nom_du_client,nom_x,reference_x,note_commande,state,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? AND commande.state LIKE 'preparing' ORDER BY state ASC, id_commande DESC;");
 $query_commande_list_in_progress ->execute(array($username));
 $nb_waiting = $query_commande_list_in_progress->rowCount ();
 $query_commande_list_in_progress->closeCursor();
?>
	<div>
	<input id="ancre1" type="search" class="light-table-filter btn btn-light" data-table="table-info" placeholder="Filter/Search">

	<input type="button" class="float-right btn btn-secondary font-weight-bold text-light" value="Dévérroullier <?php echo ($total_line); ?> Lignes" data-toggle="modal" data-target="#Edit_Mode">
<!-------FORM DE ACTIVER MODIFIABLE MODE-------->
		<!-- modal ACTIVER MODIFIABLE MODE-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Edit_Mode" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">MOT DE PASSE DE L'ADMINISTRATEUR</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="editable_mode_commande_royal.php" method="post">
						<div class="form-group">
						<label>ADMIN PASSWORD RECOMMENDED</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="password" id="password" oninput="PasswordCheck($(this));">
						</div>
						<button type="submit" class="btn btn-success" id="password_validation" disabled>ACTIVER</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->

	</div>
	<br>
	<?php if ($nb_waiting != 0) {
	?>
	<div class="form-group text-center">
	<a href="valider_waiting_commande_royal.php" onclick="confirmationDelete('Valider le commande?');return false; post ;"><button class="btn btn-danger" id="Verroullier1">Verroullier (Payment Effectué)</button></a>
	<a href="invoice.php" data-toggle="modal" data-target="#Print_click"><button class="btn btn-warning" id="Imprimer1">Imprimer Facture</button></a>
	</div>
	<?php
		}
	?>
	<div class="form-group text-left">
	<button class="btn btn-dark" disabled><span id="selected"><img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></span> SELECTED || MONTANT : <span id="total_waiting2"><img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></span></button>
	<button class="float-right btn btn-light" disabled>WIFI : <b><span id="routeurStatus"><span id="total_waiting2"><img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></span></b> || IMPRIMANTE : <b><span id="xprinterStatus2"><span id="total_waiting2"><img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></span></b><span id="xprinterStatus3"></span></button>
	</div>
	 <table class="table-info table">
        <thead class="table-dark">
        <tr>
        	<th class="text-center" colspan="15"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th></th>
        <th>No.</th>
        <th></th>
        <th>Designation</th>
        <th class="text-right">Qt</th>
        <th class="text-right">PU</th>
        <th class="text-right">REF</th>
        <th class="text-right">Montant</th>
        <?php
        if ($client_name=='Amparafa') {
        ?>
        <th class="text-right">PU A/fa</th>
        <th class="text-right">MT A/fa</th>
        <th class="text-right">Bc A/fa</th>
        <?php
        } 
        ?>
        <th>Note</th>
        <th>Lock</th>
        <th colspan="3">Modifier/Suppr</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$sous_total_aparafa = 0;
$total_waiting = 0;
$no = $total_line;
$check_id2 = "";
$textzone_id2 = "";
$m = 1;
$j = 1;
$reference_pu = "";
$tovana = "";
$selected = 0;
$ib = 0;
$search_color_table = 'table-success';
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$sous_total_aparafa = $donnees['sous_total_aparafa'] + $sous_total_aparafa;
	//$no = $no + 1;
	$check_id2 = "myCheck2".$m; 
     $textzone_id2 = "textzone2".$m;
     $m = $m+1;
    //Handle diminution or augmentation prix
	$query_product_search = "SELECT * FROM produit WHERE id_x = ?";
	$q = $bdd->prepare($query_product_search);
	$q->execute(array($donnees['id_x']));
	$data = $q -> fetch();
	$current_prix = $data['prix_de_vente']+0;

	if ($client_name=='Ambato_Tantely') {
		$current_prix = $data['pu_ambato_tantely']+0;
	}
	if ($client_name=='Amparafa') {
		$current_prix = $data['pu_aparafa']+0;
	}
	$q->closeCursor();
	
	$aff_difference_prix = "";
	$difference_prix = $donnees['prix_unitaire'] - $current_prix;
	$aff_difference_prix = number_format($difference_prix,0, "", " ");
	if ($difference_prix > 0) {
		$color_badge = "w3-green";
		$difference_prix = "+".number_format($difference_prix,0, "", " ");
		$aff_difference_prix = $difference_prix;
	} else {
		$color_badge = "w3-red";
	}
	if ($difference_prix == 0) {$aff_difference_prix ="";}
	//---------------------------------------------------------
	//Handle Montant
	$id_montant_modif = $j.'_id_montant_modif';
	$id_qt_modif = $j.'_id_qt_modif';
	$id_pu_modif = $j.'_id_pu_modif';
	$id_montant3_modif = $j.'_id_montant3_modif';
	$j = $j+1;
	$state_button ="";
	$state_suppr ="";
	//Prevente Electronique user to delete or modify
		if ($username == "Royal") {
			$state_button ="disabled";
			$state_suppr = "pointer-events: none";
		}
	//----------------------------------------------
	$color_waiting ="";
	if ($donnees['state']=='preparing') {
		$selected = $selected + 1;
		$total_waiting = $donnees['sous_total']+$total_waiting;
		$color_waiting ="text-danger";
		$reference_pu = $reference_pu.$tovana.($donnees['prix_aparafa']+0);
		$tovana = '/';
		$state_button ="";
		$state_suppr = "";
	}else{$ib = $ib+1;}
	//Handle Product xx Name
	$product_name = $donnees['nom_x'];
	$product_note = $donnees['note_commande'];
	if ($donnees['reference_x'] == 'XXXX') {
		$product_name = $donnees['note_commande'];
		$product_note = "";
	}
	//Handle color table
	if ($search_color_table == 'table-success') {$search_color_table = 'table-light';} else {$search_color_table = 'table-success';}
	//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }   
?>
		<tr class="<?php echo $search_color_table; ?>">
		<td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
		<td class="<?php echo $color_waiting; ?>"><a href="#ancre1"><?php echo $no; $no = $no-1; ?></a></td>
		<td class="<?php echo $color_waiting; ?>">
			<?php if ($donnees['state']=='preparing') { ?>
			<input type="checkbox" value=<?php echo $donnees['sous_total']+0; ?> name="groupChecked" id="<?php echo $donnees['id_commande']; ?>" onclick="getcheckboxvalue()" checked>
			<?php } ?>
		</td>
		<td class="<?php echo $color_waiting; ?>"><?php echo $product_name; ?></td>
		<td class="text-right <?php echo $color_waiting; ?>"><?php echo $donnees['qt']; ?></td>
		<!----
		<td><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $aff_difference_prix; ?></span></td>
		--->
		<td class="text-right <?php echo $color_waiting; ?>"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
		<td class="text-right <?php echo $color_waiting; ?>"><?php echo $donnees['prix_aparafa']+0; ?></td>
		<td class="text-right <?php echo $color_waiting; ?>"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<?php
        if ($client_name=='Amparafa') {
        ?>
        <td  class="text-right <?php echo $color_waiting; ?>"><?php echo number_format($donnees['prix_aparafa'],0, "", " "); ?></td>
		<td  class="text-right <?php echo $color_waiting; ?>"><?php echo number_format($donnees['sous_total_aparafa'],0, "", " "); ?></td>
		<td  class="text-right <?php echo $color_waiting; ?>"><?php echo number_format(($donnees['sous_total_aparafa']-$donnees['sous_total']),0, "", " "); ?></td>
        <?php
        } 
        ?>
		<td class="<?php echo $color_waiting; ?>"><?php echo $product_note; ?></td>
		<td>
			<?php
        	if ($donnees['state']=='preparing') {
        	?>
        	<a style="<?php echo $state_suppr; ?>;" class="center" title='VERROULLIER' href="lock_commande_royal.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to lock this line?');return false; post ;"><img src="img/lock.png" height="30" width="30" background alt="Edit"/></a>
        	<?php
        	} else {
        	?>
        	<a id="<?php echo $ib.'L'; ?>" style="<?php echo $state_suppr; ?>;" class="center" href="unlock_commande_royal.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to unlock this line?');return false; post ;"><img src="img/unlock.png" height="30" width="30" background alt="Edit"/></a>
        	<?php
        	}
        	?>
		</td>
		<td><button id=<?php if ($donnees['state']=='preparing'){}else{echo ($ib.'B');};?> type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_commande']; ?>" <?php echo $state_button; ?>>Modifier</button></td>
		<td>
			<?php
        	if ($donnees['state']=='preparing') {
        	?>
			<a style="<?php echo $state_suppr; ?>;" title='SUPPRIMER' class="center" href="delete_commande_royal.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit"/></a>
			<?php
        	} else {
        	?>
			<a id="<?php echo $ib; ?>" style="<?php echo $state_suppr; ?>;" class="center" href="delete_commande_royal.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit"/></a>
			<?php
        	}
        	?>
		</td>
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
					<form role="form" action="modifier_commande_royal.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control btn" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" step = "any" id="<?php echo $id_qt_modif; ?>" oninput="Montant2FunctionModif($(this));">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label><span class="w3-badge w3-right w3-margin-right w3-green" id="<?php echo $id_montant_modif; ?>"><?php echo $donnees['prix_unitaire']*$donnees['qt']; ?></span>
						<input class="form-control  btn btn-success" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" oninput="MontantFunctionModif($(this));" id="<?php echo $id_pu_modif; ?>">
						</div>
						<div class="form-group">
						<label>REFERENCE PRIX (Masokarena)</label>
						<input class="form-control  btn" name="prix_fournisseur" value='<?php echo ($donnees['prix_aparafa']+0);?>' type="number" step = "any">
						</div>
						<?php if ($client_name == 'Amparafa') {;?>
						<div class="bg-warning">
						<input type="checkbox" id="<?php echo $check_id2; ?>" onclick="myFunction2()">
						<label for="myCheck">APARAFA IHANY</label><span class="w3-badge w3-right w3-margin-right w3-red" id="<?php echo $id_montant3_modif; ?>"><?php echo $donnees['prix_aparafa']*$donnees['qt']; ?></span>
						<input class="form-control btn-danger" name="prix_aparafa" value="<?php echo $donnees['prix_aparafa']+0; ?>" type="number" id="<?php echo $textzone_id2; ?>" style="display:none" oninput="Montant3FunctionModif($(this));">
						</div>
						<?php }else{; ?>
						<div>
						<input class="form-control btn-danger" id="<?php echo $textzone_id2; ?>" style="display:none">
						</div>
						<?php }; ?>
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
<!------------------------------->
	</tr>
<?php
}
?>
				<tr class="table-primary">
		        <th colspan="6">########TOTAL #####</th>
		        <th></th>
		        <th  class="text-right"><?php echo number_format($total_commande,0, "", " "); ?></th>
		        <th class="text-danger"><a href="" data-toggle="modal" data-target="#famerimbola_click"><span id="total_waiting"><?php if ($total_waiting != 0) {echo number_format(($total_waiting),0, "", " ");}  ?></span></a></th>
		        <th colspan="3" class="text-danger"><?php echo 'Ref:'.$reference_pu ; ?></th>
<!-------FORM FAMERIMBOLA-------->
		<!-- modal FORM FAMERIMBOLA-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="famerimbola_click" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">AUTO CALCULATE</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="" method="post">
						<div class="form-group">
						<label>VOLA VOARAY</label>
						<input class="form-control btn-warning" name="prix_de_vente" value="<?php echo $total_waiting; ?>" type="number" oninput="FamerimbolaFunction($(this));" id="vola_voaray">
						</div>
						<div class="form-group">
						<label>SOMME A PAYER</label>
						<input class="form-control" name="qt" value="<?php echo number_format($total_waiting,0, "", " "); ?>" type="text" id="net_a_payer" disabled>
						</div>
						<div class="form-group">
						<label class="text-danger"><b>FAMERIM-BOLA</b></label><span class="w3-badge w3-right w3-margin-right w3-red" id="famerimbola">0</span>
						</div>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
		        <?php
		        if ($client_name=='Amparafa') {
		        	$difference = ($sous_total_aparafa-$total_commande);
			        if ($difference < 0)
			        {
			        	$difference = 0;
			        }
		        ?>
		        <th  class="text-right"><?php echo number_format($sous_total_aparafa,0, "", " "); ?></th>
		        <th colspan="2" class="text-right">(A/fa Diff:<?php echo number_format($difference,0, "", " "); ?>)</th>
		        <?php
		        } 
		        ?>
		        </tr>
</tbody>
</table>
<div class="form-group text-center">
<?php if ($nb_waiting != 0) 
{
	?>
<a href="valider_waiting_commande_royal.php" onclick="confirmationDelete('Valider le commande?');return false; post ;"><button class="btn btn-danger" id="Verroullier2">Verroullier (Payment Effectué)</button></a>
<a href="invoice.php" data-toggle="modal" data-target="#Print_click"><button class="btn btn-warning" id="Imprimer2">Imprimer Facture</button></a>
<!-------FORM REMPLIR NUMERO DU CLIENT-------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Print_click" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title text-danger">VOUS-VOULEZ IMPRIMER?</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="invoice.php" method="post">
						<div class="form-group">
						<label>SAISIR LE NUMERO ou CODE DU CLIENT</label>
						<input class="form-control" name="client_code" value="C" type="text">
						</div>
						<div class="form-group">
							<label  class="form-control btn text-info">MONTANT = <span id="mt_client" name="mt_client"><?php echo $total_waiting; ?></span> Ar</label>
						</div>
						<input class="form-control" name="reference_pu" value="<?php echo $reference_pu; ?>" type="hidden" id="net_a_payer">
						<input name="vente_id_array" value = 0 type="hidden" id="vente_id_array">
						<button type="submit" class="btn btn-danger">IMPRIMER</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
<script>
	window.onload = function(){
		var s = <?php echo json_encode($selected); ?>;
		var t = <?php echo json_encode($total_waiting); ?>;
		//alert(s);
		$("#total_waiting2").text(t);
		$("#selected").text(s);

	};
</script>
<?php
} 
?>
<input name="vente_id_array" class="btn btn-light float-right" type="password" oninput="unlock($(this));" placeholder="Password">
</div>
<br>
<br>
<br>
</div>
<!--
	####################DEBUT AFFICHAGE DEPENSE############################
--->
<div>
		 <table class="table-active table">
        <thead>
        <tr>
        	<th class="text-center bg-secondary text-light" colspan="6">DEPENSE <?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
            <th>No.</th>
	        <th>ID</th>
	        <th>MOTIF</th>
	        <th class="text-right">MONTANT</th>
	        <?php
	        if ($client_name=='Amparafa') {
	        ?>
	        <th class="text-right">APARAFA</th>
	        <?php
	        } 
	        ?>
	        <th>Suppr</th>
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
		<td class="text-right"><?php echo number_format($donnees['montant'],0, "", " "); ?></td>
		<?php
	    if ($client_name=='Amparafa') {
	    ?>
	    <td class="text-right"><?php echo number_format($donnees['depense_aparafa'],0, "", " "); ?></td>
	    <?php
	    } 
	    ?>
		<td><a class="center" href="delete_depense_royal.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
	</tr>
<?php
}
$query_depense_list->closeCursor();
?>
		<tr>
	        <th colspan="3">########TOTAL DEPENSE#####</th>
	        <th class="text-right"><?php echo number_format($total_depense,0, "", " "); ?></th>
	        <?php
		    if ($client_name=='Amparafa') {
		    ?>
		    <th class="text-right"><?php echo number_format($total_depense_aparafa,0, "", " "); ?></th>
		    <?php
		    } 
		    ?>
		    <th class="text-right"><?php echo "TOTAL: ".number_format(($total_depense+$total_depense_aparafa),0, "", " ");?></th>
        </tr>
</tbody>
</table>
</div>
<div>
<!--
	####################FIN AFFICHAGE DEPENSE############################
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->
<p class="form-control text-center text-light" style="background-color: purple"><b>MT MAHAVANTANA =<?php echo floor($total_commande);?> | Depense MAHAVANTANA:<?php echo $total_depense;?> <?php
	    if ($client_name=='Amparafa') {
	    	echo '| NALAIN A/FA:'.$total_depense_aparafa;
	    ?>
	    
	    <?php
	    } 
	    ?> | Reste MAHAVANTANA=<?php echo floor($total_commande-$total_depense);?>
	    <?php
	    if ($client_name=='Amparafa') {
	    	echo " | Reste GLOBAL=";
	    	echo floor($sous_total_aparafa-$total_depense_aparafa-$total_depense);;
	    ?>
	    
	    <?php
	    } 
	    ?></b></p>
<form action="valider_commande_royal.php" method="POST">
<div>
	<label><b>VERSEMENT POUR MAHAVANTANA SEULEMENT</b></label>
	<label class="float-right"><b>POINT</b>##########</label>
</div>
<div>
<input class="col-sm-5 btn" style="background: yellow" name="versement" value=<?php echo floor($total_commande-$total_depense);?> type="number" step="any">
<select class="btn float-right" style="background: pink" name="point">
		<option value="congratulation">Felicitation</option>
	    <option value="avertissement">Avertissement</option>
</select>
</div>
<div>
<br>
<label><b>RESOLUTION (Versement tsy ampy) Ex: 3000</b></label>
</div>
<div>
<input class="col-sm-5 btn" style="background: pink" name="resolution" value=0 type="number" step="any">
</div>
<div>
<br>
<label><b>VOLA MIHOATRA (Versement Mihoatra) Ex: 5000</b></label>
</div>
<div>
<input class="col-sm-5 btn" style="background: orange" name="mihoatra" value=0 type="number" step="any">
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
<a id="annuler_link" style="<?php echo $state_suppr; ?>;" href="anuler_commande_royal.php" onclick="confirmationDelete('Anuler le commande?');return false; post ;"><button id="annuler_button" type="button" class="btn btn-danger" <?php echo $state_button; ?>>Anuler</button></a>
<a onclick="confirmationDelete('Valider le commande?');return false; post ;"><button type="submit" class="btn btn-success">Valider</button></a>
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
<form role="form" action="insert_depense_mahavantana.php" method="post">
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
<script>
function MontantFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant = j + '_id_montant';
	var id_qt = j + '_id_qt';
	var qt = document.getElementById(id_qt).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant).text(mt);
}
</script>
<script>
function MontantFunctionModif(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant_modif = j + '_id_montant_modif';
	var id_qt_modif = j + '_id_qt_modif';
	var qt = document.getElementById(id_qt_modif).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant_modif).text(mt);
}
</script>
<script>
function Montant2Function(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant = j + '_id_montant';
	var id_pu = j + '_id_pu';
	var id_pu2 = "textzone"+j;
	var id_montant3 =  j + '_id_montant3';
	var pu = document.getElementById(id_pu).value;
	var pu2 = document.getElementById(id_pu2).value;
	var qt =  e.val();
	var mt = pu*qt;
	var mt2 = pu2*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant).text(mt);
    $("#"+id_montant3).text(mt2);
}
</script>
<script>
function Montant2FunctionModif(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant_modif = j + '_id_montant_modif';
	var id_pu_modif = j + '_id_pu_modif';
	var id_pu2_modif = "textzone2"+j;
	var id_montant3_modif =  j + '_id_montant3_modif';
	var pu = document.getElementById(id_pu_modif).value;
	var pu2 = document.getElementById(id_pu2_modif).value;
	var qt =  e.val();
	var mt = pu*qt;
	var mt2 = pu2*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant_modif).text(mt);
    $("#"+id_montant3_modif).text(mt2);
}
</script>
<script>
function Montant3Function(e){
	//var j = parseFloat(e.attr('id'));
	var j = e.attr('id');
	var l = j.length;
	var y = j.length - 8;
	//alert (x);
	var x = parseFloat(j.slice(8,l));
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant3 = x + '_id_montant3';
	var id_qt = x + '_id_qt';
	//var id_pua = 'textzone'+j;
	var qt = document.getElementById(id_qt).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant3).text(mt);
}
</script>
<script>
function Montant3FunctionModif(e){
	//var j = parseFloat(e.attr('id'));
	var j = e.attr('id');
	var l = j.length;

	//alert (x);
	var x = parseFloat(j.slice(9,l));
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant3_modif = x + '_id_montant3_modif';
	var id_qt_modif = x + '_id_qt_modif';
	//var id_pua = 'textzone'+j;
	var qt = document.getElementById(id_qt_modif).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant3_modif).text(mt);
}
</script>
<script>
function FamerimbolaFunction(e){
	var net_a_payer = <?php echo json_encode($total_waiting); ?>;
	//alert(net_a_payer);
	var vola_voaray =  e.val();
	var famerimbola = vola_voaray - net_a_payer;
    //$("#"+id_montant).val(mt);
    $("#famerimbola").text(famerimbola);
}
</script>
<script>
function PasswordCheck(e){
	var password =  e.val();
	//alert(password);
	$("#password_validation").prop('disabled', true);
	if (password == "2020") { $("#password_validation").prop('disabled', false);}
}
</script>
<script>
function StatusCheck(e){
	var string =  e.val();
	var id_state_btn = e.attr('id')+'b';

	//alert(string);
	$("#"+id_state_btn).prop('disabled', true);
	if (string.length > 0) { $("#"+id_state_btn).prop('disabled', false);}
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>

<script type="text/javascript">
  //Refresh each 15s-------
    setInterval(function(){
        refreshAllSites2('getscript');
    },15000);
  //-----------------------
</script>
    <script type="text/javascript">
var sites2 = {
  routeur: {
    url: "http://192.168.0.1",
    id: "routeurStatus"
  },
};

// Default
refreshAllSites2('getscript');

function refreshAllSites2(approach) {
  Object.keys(sites2).forEach(function(name) {
    // Four approaches
    checkStatusUsingGetScript2(sites2[name]);
  });
}

// Approach 4: Using GetScript
function checkStatusUsingGetScript2(site2) {
  $.getScript(site2.url + "?callback=?").done(function() {
    document.getElementById(site2.id).innerHTML = "VELONA";
    document.getElementById(site2.id).style.color = 'green';
    document.getElementById('xprinterStatus3').innerHTML = "";
  }).fail(function() {
  	var id_of_device2 = document.getElementById(site2.id).id;
    if (id_of_device2 == 'routeurStatus') 
    {
    //GIVE CONSEIL TO TURN ON WIFI
    document.getElementById('xprinterStatus3').innerHTML = ", ACTIVER DABORD L'WIFI DE VOTRE ORDINATEUR";
    document.getElementById('xprinterStatus3').style.color = 'red';
    //Imprimante
    document.getElementById(site2.id).innerHTML = "MATY";
    document.getElementById(site2.id).style.color = 'red';
    }
	else {
    document.getElementById(site2.id).innerHTML = "MATY";
    document.getElementById(site2.id).style.color = 'red';
    }
  });
}


// Other functions
    </script>
 <script>
      
      function getcheckboxvalue()
      {
        //function to get checked value as array;
        var vente_value_array = (function(){
          var a = [];
          $("input:checkbox[name=groupChecked]:checked").each(function(){
            a.push(this.value);
          });
          return a;})()

       	var vente_id_array = (function(){
          var b = [];
          $("input:checkbox[name=groupChecked]:checked").each(function(){
            b.push(this.id);
          });
          return b;})()

        //alert(history_id_array.length); //(get array count in Javascript)
        if ((vente_id_array.length)>0) 
          {
            //Convert string array to int
	        var result = vente_value_array.map(i=>Number(i));
	        var sum = result.reduceRight(function(s,c){return s+c;});
	        //alert(sum);
	        $("#total_waiting").text(sum);
	        $("#mt_client").text(sum);
	        $("#vente_id_array").val(vente_id_array);
	        $("#total_waiting2").text(sum);
	        $("#net_a_payer").text(sum);
			$("#selected").text(vente_id_array.length);
			//ENABLE BOUTON
			$("#Verroullier1").removeAttr('disabled');
			$("#Verroullier2").removeAttr('disabled');
			$("#Imprimer1").removeAttr('disabled');
			$("#Imprimer2").removeAttr('disabled');
          } 
        else 
          {
            //alert("COCHER AU MOIN UNE LIGNE");
            $("#total_waiting").text(0);
	        $("#mt_client").text(0);
	        $("#vente_id_array").val(vente_id_array);
	        $("#total_waiting2").text(0);
	        $("#net_a_payer").text(0);
			$("#selected").text(0);
			//DISABLE BOUTON
			$("#Verroullier1").prop('disabled',true);
			$("#Verroullier2").prop('disabled',true);
			$("#Imprimer1").prop('disabled',true);
			$("#Imprimer2").prop('disabled',true);
          }
      };
//--------------------------------------------------------------------
</script>
<script>
function unlock(e){
	var ib = <?php echo json_encode($ib); ?>;
	var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2020") {
    	for (var i = 1; i <= ib; i++) {
    	//alert(i);
    	$("#"+i).removeAttr("style");
    	$("#"+i+"L").removeAttr("style");
    	$("#"+i+"B").removeAttr('disabled');
    	}
    	$("#annuler_link").removeAttr("style");
    	$("#annuler_button").removeAttr("disabled");
	}
}
</script>
</body>
</html>