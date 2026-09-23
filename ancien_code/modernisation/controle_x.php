<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ajouter Stock et Inventaire</title>
<script src="js/jquery-3.5.1.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/w3.css">
</head>
<body style="background: #950808">
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
				//Handle other user
				 if (isset($_COOKIE['other_user'])) 
				{
				   $username =$_COOKIE['other_user'];
				 }
				 //----------------
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
   $vers_controle = 'Afficher Rectification et Controle?';
   //----PLAY SOUND-------------------------
	?>
   <script type="text/javascript">
	var audio = new Audio('sound/done.mp3').play();
	</script>
	<?php
	//----PLAY SOUND-------------------------
 }
 //---------------------------------------------
 $query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ?");
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['numero_stock_prep'];
         $client_name = $donnees['nom_du_client'];
         $description_date = $donnees['description_date'];
         //------------------CONVERT $description_date to english format------------------
		//GET DATE ON description date chaine
		$description_date_english = $description_date;
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date_english, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $description_date_english = $res_regex[1];
    	}
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
		//-------------------------------------------------------------
          }
		$query_client_name->closeCursor();
		//---------------------------------
if (isset($_COOKIE['point_de_vente'])) 
{
	$point_de_vente_cookie = "<b>".$_COOKIE['point_de_vente']." | ".$_COOKIE['description_date']." | Activity No.".$_COOKIE['numero_stock']."</b>";
	$description_date = $_COOKIE['description_date'];
	//GET DATE ON description date chaine
		$description_date_english = $description_date;
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date_english, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $description_date_english = $res_regex[1];
    	}
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
		//-------------------------------------------------------------
}

?>
<h4 class="text-center text-success"><?php echo $point_de_vente_cookie; ?></h4>
<h4 class="text-center text-primary"><a href="latest_controle.php"> <?php echo $vers_controle; ?></a></h4>
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
   $query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
 }else{
$limit = 30;
$query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC LIMIT $limit";
}

	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array($username));

	//------------Handlle Number----------------------
	$query_ps_no = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ?";
	$query_stock_prep_list_no = $bdd->prepare($query_ps_no);
	$query_stock_prep_list_no->execute(array($username));
	//-------------------------------------------------
 

$query_stock_prep = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC;";
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

				 //Handle other user
				 if (isset($_COOKIE['other_user'])) 
				{
				   $total_line = 1;
				 }
				 //----------------
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
	<button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#Demarrer">Controler Stock?</button>
</div>
<?php
//echo $username;
if ($username == 'TRELAHY' || $username == 'Mann' || $username == 'Hery'|| $username == 'Samuel'|| $username == "El'Sam") {
	$query_user = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' GROUP BY user_stock_prep;";
    $query_user = $bdd->prepare($query_user);
    $query_user->execute(array());
    $user_line = $query_user->rowCount ();
    //echo $user_line;
    if ($user_line!=0)
 	{
 	
?>
<br>
<br>
<div class="text-center">
	<button class="btn btn-success" data-toggle="modal" data-target="#Other_user">Show For Other User</button>
</div>
<!-------------------------------------->
<!-- model form Other User-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Other_user" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">OTHER USER</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="other_user_controle.php" method="post">
	<!------------------AUTO LISTE USER------------------>
  <div class="form-group">
    <label>User Possible</label>
    <select class="form-control btn" name="user_name">
    <option value="" selected disabled hidden>Choose On User</option>
    <?php
    while ($donnees = $query_user -> fetch())
    {
    ?>
    <option value=<?php echo $donnees['user_stock_prep']; ?>><?php echo '<b>'.$donnees['user_stock_prep'].'</b>  ('.$donnees['nom_du_client'].' | '.$donnees['description_date'].')'; ?></option>
      <?php
      }
      $query_user->closeCursor();
      ?>
    </select>
  </div>
  <!------------------END LISTE USER------------------>
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
	}//END IF User_line
} //END IF TRELAHY
?>
<!-------------------------------------->
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Date de Controle</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="new_controle.php" method="post">
	<div class="form-group">
		<label>Choisir la date de verification</label>
		<input class="form-control" value="" name="description_date" type="DATE">
	</div>
	<!------------------AUTO LISTE SHOP------------------>
  <div class="form-group">
    <label>Point de vente</label>
    <select class="form-control" name="point_de_vente">
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
?>
<br>
<br>
<form role="form" action="simple_search_controle.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" name="key_word" placeholder="Name/Code/Search" value="<?php echo $key_word; ?>">
<button type="submit" class="btn btn-info">Search</button><a href="controle_jpg.php"><button type="button" class="btn btn-brown">Facture</button></a>
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
   //GET QT FOR Point de Vente
	$point_de_vente = "";
    if (isset($_COOKIE['point_de_vente'])) 
    {$point_de_vente = $_COOKIE['point_de_vente'];}
	
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
		<input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
	</div>
   <table class="table-info table">
        <thead>
        <tr>
        <th></th>
        <th>Nom/Description de Produit</th>
        <th class="text-right">QT Estimer</th>
        <th>Ref ID</th>
        <th>Ajouter</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$i = 1;
$j = 1;
$image_path_x = "img_x/default_x.png";
//Query searcher word

while ($donnees = $q -> fetch())
{ 
//ID NAME
$id_qt_estimer = $j.'_id_qt_estimer';
$id_qt_actuelle = $j.'_id_qt_actuelle';
$id_qt_reduite = $j.'_id_qt_reduite';
$id_qt_reduite_mirror = $j.'_id_qt_reduite_mirror';
$j= $j+1;
//----------------
//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     } 
     //GET QT VENTE || QUERY to sum each point de vente
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
	$qsm->execute(array($donnees['id_x'],$point_de_vente));
	$qt_vente = 0;
    while ($data1 = $qsm -> fetch()) {

    	//GET DATE ON description date chaine
		$chaine = "";
		$date1 = "";
		$chaine =  $data1['description_date'].' ';
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    	}
    	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    	//Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
		//---------------------------------
		if ($date1_en <= $description_date_english) {

			$qt_vente = $data1['qt']+$qt_vente;
        }
    	
    }
    $qsm->closeCursor();
    //QUERY to sum stock
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
    $qsm->execute(array($donnees['id_x'],$point_de_vente));
    $qt_stock = 0;
    //RESTE STOCK
    while ( $data1 = $qsm -> fetch()) {
    	//---------------------------------
		//GET DATE ON description date chaine
		$chaine = "";
		$date1 = "";
		$chaine =  $data1['description_date'];
		//------HANDLE ZERO AND NEGATIF-----------------------------
		if ($chaine =='balance_zero' OR $chaine =='balance_negative') 
			{$chaine = $data1['date_time'];
			$x = chr(35).'/0-9-';
    		preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
			if (isset($res_regex[1])) {
        	$chaine = $res_regex[1];
        	$chaine = DateTime::createFromFormat('Y-m-d', $chaine);
			$chaine =$chaine -> format('d-m-y');
    		}
			}
		//----------------------------------------------------------
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    	}
    	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    	//Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
		//---------------------------------
		if ($date1_en <= $description_date_english) {

			$qt_stock = $data1['qt']+$qt_stock;
        }
    	
    }
    $qsm->closeCursor();
    $stock_actu = $qt_stock + $qt_vente;
    //##################################################
    //HANDLE PRIX
    $pu = $donnees['prix_de_vente']+0;
    if ($point_de_vente == 'Ambato_Tantely') {
    	$pu = $donnees['pu_ambato_tantely']+0;
    }

    if ($point_de_vente == 'Amparafa') {
    	$pu = $donnees['pu_aparafa']+0;
    }

    if ($point_de_vente == "Soalazaina") 
      {$pu = $donnees['pu_soalazaina']+0;}
?>
<!------------------------------------------------->
      <tr>
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['nom_x'] .' : '. $donnees['note_x']; ?></td>
        <td class="text-right"><?php echo $stock_actu; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo 'no'.$donnees['id_x']; ?>">Select</button></td>
      </tr>
		<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo 'no'.$donnees['id_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title text-center"><?php echo "<b>".strtoupper($point_de_vente)."</b> | ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_stock_prep_ajuster.php" enctype="multipart/form-data" method="post">
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
						<label><span class="text-white"> | </span><span class="text-danger">Quantite Estimer</span></label>
						<input class="form-control" name="qt_estimer" value="<?php echo $stock_actu; ?>" type="number" id="<?php echo $id_qt_estimer; ?>" disabled>
						</div>
						<div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-warning">
						<label><span class="text-white"> | </span><span class="text-orange-2"><b>Quantite Actuelle/Reelle/Exacte</b></span></label><b><span class="float-right" style="color: green" id="<?php echo $id_qt_reduite; ?>"> OK </span></b>
						<input class="form-control bg-warning" name="qt_reelle" value="<?php echo $stock_actu; ?>" type="number" step="any" oninput="resteFunction($(this));" id="<?php echo $id_qt_actuelle; ?>">
						</div>
            <div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-success">
            <label><span class="text-white"> | </span><span class="text-success">PRIX ANOMEZANA ROYAL (Ariary)</span></label>
            <input class="btn bg-success form-control" name="prix_de_vente" value="<?php echo $pu; ?>" type="number">
            </div>
            <?php if ($point_de_vente == 'Amparafa' OR $point_de_vente == 'Soalazaina' OR $point_de_vente == 'Bejofo') {;?>
            <div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
            <span class="text-white"> | </span>
            <label for="myCheck"><span class="text-pink">PRIX AMAROTANA <?php echo strtoupper($point_de_vente); ?> </span></label>
            <input class="form-control btn btn-danger" name="prix_client" value="<?php echo $pu; ?>" type="number">
            </div>
            <?php } ?>
						<div class="form-group">
						<input class="form-control" name="qt_rectificative" value="0" type="number" step="any" id="<?php echo $id_qt_reduite_mirror; ?>" hidden>
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" type="text" name="note_stock">
						</div>
						<!-----------INSERT IMAGE------------>
						<div class="form-group text-center">
			              <img src="<?php echo $image_path_x;?>" height=50% width=50% align="middle"/>
			            </div>
			            <div class="form-group">
			            <label><b>MODIFY IMAGE?</b></label>
			            <input class="btn btn-warning form-control" name="uploadedimage" type="file">
			            </div>
			            <!----------------------->
			            <input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
						<button type="submit" class="btn btn-success">AJOUTER</button>
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
		

if ($total_line == 0)
{
	if ($username=='STANDARD') { //IF No.1
	//--------------LISTE OF WAITING STOCK---------------
# QUERY GET DISTINCT USER
		$query_user = $bdd->prepare("SELECT user_stock_prep,nom_du_client,numero_stock_prep,description_date FROM stock_prep WHERE description_date LIKE '%Rectifier%' GROUP BY user_stock_prep");
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
        //------------------CONVERT $description_date to english format------------------
		//GET DATE ON description date chaine
		$description_date_english = $description_date;
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date_english, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $description_date_english = $res_regex[1];
    	}
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
		//-------------------------------------------------------------
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
         $query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,prix_client,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
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
        	<th class="text-center" colspan="9"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th></th>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-right">QT Base</th>
        <th class="text-right">QT Reelle</th>
        <th class="text-right">Rectifier</th>
        <th class="text-left">PU ROYAL</th>
        <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
        <th class="text-left">FAMAROTANA</th>
        <?php } ?>
        <th>Note</th>
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

     //GET QT VENTE || QUERY to sum each point de vente
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
	$qsm->execute(array($donnees['id_x'],$client_name));
    //calcule sum vente
    $qt_vente = 0;
    while ($data1 = $qsm -> fetch())
	{
		//---------------------------------
		//GET DATE ON description date chaine
		$chaine = "";
		$date1 = "";
		$chaine =  $data1['description_date'].' ';
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    	}
    	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    	//Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
		//---------------------------------
		if ($date1_en <= $description_date_english) {

			$qt_vente = $data1['qt']+$qt_vente;
        }

	}
    $qsm->closeCursor();
    //QUERY to sum stock
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
    $qsm->execute(array($donnees['id_x'],$client_name));
    //calcule sum stock
    $qt_stock = 0;
    while ($data1 = $qsm -> fetch())
	{

		//---------------------------------
		//GET DATE ON description date chaine
		$chaine = "";
		$date1 = "";
		$chaine =  $data1['description_date'];
		//------HANDLE ZERO AND NEGATIF-----------------------------
		if ($chaine =='balance_zero' OR $chaine =='balance_negative') 
			{$chaine = $data1['date_time'];
			$x = chr(35).'/0-9-';
    		preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
			if (isset($res_regex[1])) {
        	$chaine = $res_regex[1];
        	$chaine = DateTime::createFromFormat('Y-m-d', $chaine);
			$chaine =$chaine -> format('d-m-y');
    		}
			}
		//----------------------------------------------------------
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    	}
    	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    	//Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
		//---------------------------------
		if ($date1_en <= $description_date_english) {

			$qt_stock = $data1['qt']+$qt_stock;
        }

	}
    $qsm->closeCursor();
    //RESTE STOCK
    $stock_actu = $qt_stock + $qt_vente;
//---------------------------------------------------------
	$qte = number_format($donnees['qt']) - number_format($stock_actu);
	$signe = "+";
	//BADGE COLOR
	$color_badge = "w3-orange";
	if ($qte < 0) {
		$color_badge = "w3-red";
		$signe = "";
	}

	if ($qte == 0) {
		$qte = "OK";
		$signe = "";
    $color_badge = "w3-green";

	}
	//IMAGE PATH_X-------------------------------------------------
	//$image_path_x =$donnees['img_path_x']."";
	$query_product_path = 'SELECT * FROM produit WHERE id_x LIKE ?';
	$qpath = $bdd->prepare($query_product_path);

	$qpath->execute(array($donnees['id_x']));
	$data_path = $qpath -> fetch();
    $image_path_x = $data_path['img_path_x'];
    $qpath->closeCursor();
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }
	//---------------------------------------------------------
			?>
				<tr>
					<td class="text-center"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></td>
          <td><a href="#ancre1"><?php echo $no; ?></a></td>
          <td><?php echo $donnees['reference_x']; ?></td>
          <td><?php echo $donnees['nom_x']; ?></td>
          <td class="text-right"><?php echo $stock_actu; ?></td>
          <td class="text-right"><?php echo (number_format($donnees['qt'])); ?></td>
          <td class="text-right"><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $signe.$qte; ?></span></td>
          <td><?php echo $donnees['prix_unitaire']+0; ?></td>
          <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
            <td><?php echo $donnees['prix_client']+0; ?></td>
          <?php } ?>
          <td><?php echo $donnees['note']; ?></td>
				</tr>

			<?php
			}
			?>
</tbody>
</table>
<br>
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
//-------------------------------------------------------------
	//GET LATEST JOURNAL DE VENTE
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != 'OFF' GROUP BY description_date;";
    $qc = $bdd->prepare($query);

    $qc->execute(array($client_name));
    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date = "31-01/ 20";
    while ($result = $qc -> fetch())
    {
        $chaine =  $result['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date = str_replace('- ', '/', $latest_date);
        $latest_date = str_replace('/ ', '/', $latest_date);
        $latest_date = str_replace(' / ', '/', $latest_date);
        $latest_date = str_replace(' -', '/', $latest_date);
        $latest_date = str_replace(' - ', '/', $latest_date);
        $latest_date = str_replace('-', '/', $latest_date);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date);
		$latest_en = $latest_en -> format('y/m/d');
		//echo '#'.$latest_en.'<br>';
        if ($latest_en < $date1_en) {
            $latest_date = $date1 ;
            $latest_english = $date1_en;
            //echo '#'.$latest_english.'<br>';
        }

	}
   		$qc->closeCursor();
		//echo '# Journal Latest :: '.$latest_english.'<br>';
		//-------------------------------------------------------------
?>
	<h5 class="text-center text-success"><?php echo $msg_ok
	; ?></h5>
	<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
	<div>
		<input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
		<a href="limit_controle_x.php">
		<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
		</a>
	</div>
	<div class="text-center">
  <button class="btn btn-danger filter-obs" data-filter="minus">Tsy ampy</button>
  <button class="btn btn-warning filter-obs" data-filter="all">Tsy milamina</button>
  <button class="btn btn-success filter-obs" data-filter="plus">Mihoatra</button>
</div>
		<table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="13"><?php echo (strtoupper($client_name)." || ".$description_date." (Activiter No. ".$num_stock.")<br>Date du Dernier Journal de Vente : ".$latest_date."<br>Responsable : ".$username); ?></th>
          <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
        <th class="text-right"></th>
        <?php } ?>
        </tr>
        <tr>
        <th></th>
        <th>No.</th>
        <th></th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-right">QT Base</th>
        <th class="text-right" colspan="2">QT Reelle</th>
        <th class="text-right">Rectifier</th>
        <th class="text-right">PU ROYAL</th>
        <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
        <th class="text-right">FAMAROTANA</th>
        <?php } ?>
        <th>Note</th>
        <th colspan="2">Modifier/Suppr</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$j = 1;
//$total_line=$query_stock_prep->rowCount ();
$no = $query_stock_prep_list_no->rowCount ();
while ($donnees = $query_stock_prep_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;

     //GET QT VENTE || QUERY to sum each point de vente
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
	$qsm->execute(array($donnees['id_x'],$client_name));
    //calcule sum vente
    $qt_vente = 0;
    while ($data1 = $qsm -> fetch())
	{
		//---------------------------------
		//GET DATE ON description date chaine
		$chaine = "";
		$date1 = "";
		$chaine =  $data1['description_date'].' ';
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    	}
    	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    	//Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
		//---------------------------------
		if ($date1_en <= $description_date_english) {

			$qt_vente = $data1['qt']+$qt_vente;
        }

	}
    $qsm->closeCursor();
    //QUERY to sum stock
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
    $qsm->execute(array($donnees['id_x'],$client_name));
    //calcule sum stock
    $qt_stock = 0;
    while ($data1 = $qsm -> fetch())
	{

		//---------------------------------
		//GET DATE ON description date chaine
		$chaine = "";
		$date1 = "";
		$chaine =  $data1['description_date'];
		//------HANDLE ZERO AND NEGATIF-----------------------------
		if ($chaine =='balance_zero' OR $chaine =='balance_negative') 
			{$chaine = $data1['date_time'];
			$x = chr(35).'/0-9-';
    		preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
			if (isset($res_regex[1])) {
        	$chaine = $res_regex[1];
        	$chaine = DateTime::createFromFormat('Y-m-d', $chaine);
			$chaine =$chaine -> format('d-m-y');
    		}
			}
		//----------------------------------------------------------
    	$x = chr(35).'/0-9-';
    	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    	//Prise en compte separation date / or -
    	if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    	}
    	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    	//Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
		//---------------------------------
		if ($date1_en <= $description_date_english) {

			$qt_stock = $data1['qt']+$qt_stock;
        }

	}
    $qsm->closeCursor();
    //RESTE STOCK
    $stock_actu = $qt_stock + $qt_vente;
    //################################################## 
    //ID NAME
	$id_qt_estimer_modifier = $j.'_id_qt_estimer_modifier';
	$id_qt_actuelle_modifier = $j.'_id_qt_actuelle_modifier';
	$id_qt_reduite_modifier = $j.'_id_qt_reduite_modifier';
	$id_qt_reduite_mirror_modifier = $j.'_id_qt_reduite_mirror_modifier';
	$j= $j+1;
//----------------

//---------GET QT INSERTED BY EACH USER-------------------------
	$liste_qt = "";
	$qt_total = 0 ;
	$query_qt = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND id_x = ?";
	$query_stock_prep_qt = $bdd->prepare($query_qt);
	$query_stock_prep_qt->execute(array($donnees['id_x']));
	while ($data_qt = $query_stock_prep_qt -> fetch())
	{
		//$liste_vente = $liste_vente.$tete.$daty2.'#<b>'. ABS($donnees2['qt']).'</b>'.$queue.'<br>';
		$liste_qt = $liste_qt.'#'.$data_qt['user_stock_prep'].' -- '.$data_qt['qt'].'<br>';
		$qt_total =$qt_total + abs($data_qt['qt']);
	}
	$query_stock_prep_qt -> closeCursor();
		$liste_qt = $liste_qt.'<b>#Total ------ '.$qt_total.'</b>';
//-------------------------------------------------------------
	$qte = $qt_total - $stock_actu;
  //Insert QT Rectificative COMMUNE
  $query_qr = "UPDATE stock_prep
            SET qt_rectificative = ?
            WHERE id_x = ? AND nom_du_client = ?";

  $qr = $bdd->prepare($query_qr);

  $qr->execute(array(($qt_total - $stock_actu), $donnees['id_x'],$client_name)); 

  $qr->closeCursor();
  //----------------------
	$signe = "+";
	//BADGE COLOR
	$color_badge = "w3-orange";
	if ($qte < 0) {
		$color_badge = "w3-red";
		$signe = "";
	}

	if ($qte == 0) {
		$qte = "OK";
		$signe = "";
    $color_badge = "w3-green";
	}

	//IMAGE PATH_X-------------------------------------------------
	$query_product_path = 'SELECT * FROM produit WHERE id_x LIKE ?';
	$qpath = $bdd->prepare($query_product_path);

	$qpath->execute(array($donnees['id_x']));
	$data_path = $qpath -> fetch();
    $image_path_x = $data_path['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }
  //QT A AJOUTE
  $qt_rec = number_format($donnees['qt']) - number_format($stock_actu);
  if ($qt_rec == 0) {
      $qt_rec = 'OK';
      $color_rec = 'green';
      }
  if ($qt_rec < 0) {
      $color_rec = 'red';
      }
  if ($qt_rec > 0) {
      $color_rec = 'blue';
      }  
?>
		<tr>
		<td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>	
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
    <!---
    	<td><a class="center" href="valider_stock_controle_individual.php?id_rectification=<?php echo $donnees['id_stock_prep']; ?>" onclick="confirmationDelete('VALIDATE this line?');return false; post ;"><img src="img/valider.png" height="30" width="30" background alt="Edit" /></a></td>
      ----->
    <td></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class="text-right"><?php echo $stock_actu; ?></td>
		<td><?php echo $liste_qt; ?></td>
		<td class="text-right"><?php echo (number_format($donnees['qt'])); ?></td>
		<td class="text-right observation" data-type="<?php 
    if ($qte > 0) echo 'plus'; 
    elseif ($qte < 0) echo 'minus'; 
    else echo 'ok'; 
?>">
    <?php 
        $observation = "OK"; 
        if ($qte > 0) { 
            $observation = "+" . $qte; 
        } elseif ($qte < 0) { 
            $observation = $qte; 
        }
        echo "<span class='w3-badge w3-right w3-margin-right {$color_badge}'>" . $observation . "</span>"; 
    ?>
</td>
<!-----------------------------------
		<td class="text-right"><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $signe.$qte; ?></span></td>
------------------------------------->
		<td class="text-right"><?php echo $donnees['prix_unitaire']; ?></td>
    <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
            <td class="text-right text-pink"><?php echo $donnees['prix_client']+0; ?></td>
    <?php } ?>
		<td><?php echo $donnees['note']; ?></td>
		<?php
		if ($username=='STANDARD') {
			# VIDE
		} else {		
		?>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_stock_prep']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_stock_prep_controle.php?id_stock_prep=<?php echo $donnees['id_stock_prep']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
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
					<form role="form" action="modifier_stock_controle.php" method="post">

          <div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
            <label><span class="text-white"> | </span><span class="text-danger">Quantite Estimer</span></label>
            <input class="form-control" name="qt_estimer" value="<?php echo $stock_actu; ?>" type="number" id="<?php echo $id_qt_estimer_modifier; ?>" disabled>
          </div>

          <div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-warning">
            <label><span class="text-white"> | </span><span class="text-orange-2"><b>Quantite Actuelle/Reelle/Exacte</b></span></label><b><span class="float-right" style="color: <?php echo $color_rec; ?>" id="<?php echo $id_qt_reduite_modifier; ?>"> <?php echo $qt_rec; ?></span></b>
            <input class="form-control bg-warning" name="qt_reelle" value="<?php echo $donnees['qt']; ?>" type="number" step="any" oninput="resteFunctionModifier($(this));" id="<?php echo $id_qt_actuelle_modifier; ?>">
          </div>
          <div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-success">
            <label><span class="text-white"> | </span><span class="text-success">PRIX ANOMEZANA ROYAL (Ariary)</span></label>
            <input class="btn bg-success form-control" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number">
          </div>
          <?php if ($client_name == 'Amparafa' OR $client_name == 'Soalazaina' OR $client_name == 'Bejofo') {;?>
            <div class="form-group border border-right-0 border-top-0 border-bottom-0 border-5 border-danger">
            <span class="text-white"> | </span>
            <label for="myCheck"><span class="text-pink">PRIX AMAROTANA <?php echo strtoupper($client_name); ?> </span></label>
            <input class="form-control btn btn-danger" name="prix_client" value="<?php echo $donnees['prix_client']; ?>" type="number">
            </div>
          <?php } ?>
						<div class="form-group">
						<input class="form-control" name="qt_rectificative_modifier" value="<?php echo (number_format($donnees['qt']) - number_format($stock_actu)); ?>" type="number" step="any" id="<?php echo $id_qt_reduite_mirror_modifier; ?>" hidden>
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" type="text" value="<?php echo $donnees['note']; ?>" name="note_stock">
						</div>
						<input type="hidden" name="id_stock_prep" value="<?php echo $donnees['id_stock_prep']; ?>">
						<button type="submit" class="btn btn-success">Rectifier</button>
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
          <form role="form" action="modifier_image_controle_x.php" enctype="multipart/form-data" method="post">
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
	$no = $no - 1;
}
?>
</tbody>
</table>
<?php
if ($username == 'STANDARD') {
	# VIDE
} else {
	//GET DATE ON description date chaine
	$chaine = "";
	$date1 = "";
	$chaine =  $description_date.' ';
	$latest_english = "";
    $x = chr(35).'/0-9-';
    preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
    //Prise en compte separation date / or -
    if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
    }else{$date1 ='10/09/20';}
    //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
    //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
		$date1_en =$date1_en -> format('y/m/d');
	//echo '# Journal Latest :: '.$latest_english.'<br>';
	//echo '# Controle Date EN ::'.$date1_en.'<br>';
	$state_button = "disabled";
	if ($latest_english >= $date1_en) {$state_button = "enabled";}
	else{$state_button = "disabled";}
 
?>
<div class="text-center">
<form role="form" action="valider_stock_controle.php" method="post">
<a class="center" href="controle_x_printable.php"><button type="button" class="btn btn-secondary">Details</button></a>
<a class="center" href="anuler_stock_controle.php" onclick="confirmationDelete('Anuler cette action?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
<!--
<a class="center" href="#" onclick="confirmationDelete('Valider le stock de <?php echo $client_name ;?>?');return false; post ;"><button type="submit" class="btn btn-success" <?php echo $state_button; ?>>Valider</button></a>
--->
</form>
</div>
<div class="text-center">
  <br>
  <br>
  <form role="form" action="valider_stock_controle_std.php" method="post">
  <input name="vente_id_array" class="btn btn-dark" type="password" oninput="unlock($(this));">
  <input name="point_de_vente" value=<?php echo $client_name;?> type="hidden">
  <input name="inventory_date" value=<?php echo $description_date_english;?> type="hidden">
  <input name="numero_stock" value=<?php echo $num_stock;?> type="hidden">
  <a class="center" href="" onclick="confirmationDelete('VALIDER GENERAL INVENTORY?');return false; post ;"><button type="submit" class="btn btn-primary" id='valider' disabled>Validate FOR GI PURPOSE ONLY (<?php echo $client_name;?>)</button></a>
  </form>
</div>
<?php
}// close ELSE
 ?>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
}
?>
<?php  
 }
 ?>
 
</body>
<script>
function resteFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//ID NAME
//$id_qt_estimer = $j.'_id_qt_estimer';
//$id_qt_actuelle = $j.'_id_qt_actuelle';
//$id_qt_reduite = $j.'_id_qt_reduite';
	var id_qt_estimer = j + '_id_qt_estimer';
	var id_qt_actuelle = j + '_id_qt_actuelle';
	var id_qt_reduite = j + '_id_qt_reduite';
	var id_qt_reduite_mirror = j + '_id_qt_reduite_mirror';
	var qe = document.getElementById(id_qt_estimer).value;
	//alert(id_prix_de_vente);
      var qa = e.val();
      var qr = qa - qe;
      if (qr > 0) {
      document.getElementById(id_qt_reduite).style.color = 'blue';
      $("#"+id_qt_reduite).text(qr);
      }
      if (qr == 0) {
      document.getElementById(id_qt_reduite).style.color = 'green';
      $("#"+id_qt_reduite).text('OK');
      }
      if (qr < 0) {
      document.getElementById(id_qt_reduite).style.color = 'red';
      $("#"+id_qt_reduite).text(qr);
      }
      $("#"+id_qt_reduite_mirror).val(qr);
}
</script>
<script>
function resteFunctionModifier(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//ID NAME
//$id_qt_estimer = $j.'_id_qt_estimer';
//$id_qt_actuelle = $j.'_id_qt_actuelle';
//$id_qt_reduite = $j.'_id_qt_reduite';
	var id_qt_estimer_modifier = j + '_id_qt_estimer_modifier';
	var id_qt_actuelle_modifier = j + '_id_qt_actuelle_modifier';
	var id_qt_reduite_modifier = j + '_id_qt_reduite_modifier';
	var id_qt_reduite_mirror_modifier = j + '_id_qt_reduite_mirror_modifier';
	var qe = document.getElementById(id_qt_estimer_modifier).value;
	//alert(id_prix_de_vente);
      var qa = e.val();
      var qr = qa - qe;
      if (qr > 0) {
      document.getElementById(id_qt_reduite_modifier).style.color = 'blue';
      $("#"+id_qt_reduite_modifier).text(qr);
      }
      if (qr == 0) {
      document.getElementById(id_qt_reduite_modifier).style.color = 'green';
      $("#"+id_qt_reduite_modifier).text('OK');
      }
      if (qr < 0) {
      document.getElementById(id_qt_reduite_modifier).style.color = 'red';
      $("#"+id_qt_reduite_modifier).text(qr);
      }
      $("#"+id_qt_reduite_mirror_modifier).val(qr);
}
</script>
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
    if (password == "2021") {
      //$("#valider").removeAttr("style");
      $("#valider").removeAttr('disabled');
  }
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const buttons = document.querySelectorAll(".filter-obs");
  
  buttons.forEach(btn => {
    btn.addEventListener("click", function() {
      const filter = this.getAttribute("data-filter");
      const rows = document.querySelectorAll("table tbody tr");
      
      rows.forEach(row => {
        const obs = row.querySelector(".observation");
        if (!obs) return; // raha tsy misy colonne

        const type = obs.getAttribute("data-type");

        if (filter === "all") {
          // aseho ireo minus sy plus ihany
          if (type === "minus" || type === "plus") {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        } else if (type === filter) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  });
});
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>