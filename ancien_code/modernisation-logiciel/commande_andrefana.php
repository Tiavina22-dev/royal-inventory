<!DOCTYPE html>
<html>
<head>
	<title>VENTE CALCULATOR</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/arrondi.css" rel="stylesheet">
	<link rel="stylesheet" href="css/w3.css">

</head>
<body style="background: #00FF85">
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
 $controle2 = "";
 $numero_commande="";
 $point_de_vente_cookie = "";
 if (isset($_COOKIE['msg_validation'])) 
{
   $msg_validation=$_COOKIE['msg_validation'];
   //----PLAY SOUND-------------------------
	?>
   <script type="text/javascript">
	var audio = new Audio('sound/done.mp3').play();
	</script>
	<?php
	//----PLAY SOUND-------------------------
 }
 if (isset($_COOKIE['numero_commande'])) 
{
   $numero_commande=$_COOKIE['numero_commande'];
   $annuler = "Annuler?";
   $controle = "Afficher vente Andrefana?";
   $controle2 = "Afficher stock et retours Andrefana?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie=$_COOKIE['point_de_vente_cookie'];
 }
?>
<h4 class="text-center"><?php echo $point_de_vente_cookie; ?></h4>
<h5 class="text-center"><b><span class="text-success"><?php echo $msg_validation; ?></span><a class="center" href="anuler_validation.php?numero_commande=<?php echo $numero_commande; ?>" onclick="confirmationDelete('Anuler la validation?');return false; post ;"><span class="text-danger"><?php echo $annuler; ?></span></a></b></h5>
<h5 class="text-center text-info"><a class="center" href="vente_recap_andrefana.php"><b><?php echo $controle; ?></b></a><br><a class="center" href="stock_recap_andrefana.php"><b><?php echo $controle2; ?></b></a></h5>
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
} else { //IF NOT STANDARD USER
	# code...
#####################################################
?>
<?php
//Connect to BD

//Query to liste All Waiting command
$query_commande_list = $bdd->prepare('SELECT id_commande,nom_du_client,nom_x,reference_x,	note_commande,qt,vente_calc.prix_de_vente as prix_unitaire,prix_aparafa, (vente_calc.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa,produit.prix_de_vente as PO,vente_calc.id_x as id_x FROM vente_calc INNER JOIN produit ON vente_calc.id_x = produit.id_x WHERE vente_calc.user LIKE ? ORDER BY id_commande DESC;');
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
         $activity_no = $donnees['numero_vente_calc'];
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
	<button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#Demarrer">Demarrer</button>
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
<form role="form" action="new_client_name_commande_andrefana.php" method="post">
	<div class="form-group">
		<label>Choisir la date</label>
		<input class="form-control" value="" name="date_journal" type="DATE">
	</div>
	<div class="form-group">
		<label>Point de vente</label>
		<select oninput="Check_shop($(this));" id="shop_id" class="form-control btn-warning" name="point_de_vente">
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
        $num_stock = $donnees['numero_vente_calc'];
         $client_name = $donnees['point_de_vente_vente_calc'];
         $description_date = $donnees['description_date_vente_calc'];
          }
		$query_client_name->closeCursor();
//------------------------------------
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
}
?>
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<form role="form" action="simple_search_commande_andrefana.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" name="key_word" placeholder="Name/Code/Search" value="<?php echo $key_word; ?>">
<button type="submit" class="btn btn-info">Search</button>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_new" hidden>Nouveau?</button>
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#Modal_depense">Depense</button>
</div>
</form>
<!---------------search result---------------------------->
<?php
//$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   //$key_word=$_COOKIE['key_word'];
   //echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x WHERE nom_x LIKE ? OR reference_x LIKE ? OR produit.id_x LIKE ? GROUP BY reference_x ORDER BY date_time';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%", "%".$key_word."%"));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<p class='text-center';><b>".$key_word."</b>"."<b class='text-danger';>"." does not exist on the base</b></p>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div>
		<input id="ancre1" type="search" class="light-table-filter" data-table="table-secondary" placeholder="Filter/Search">
	</div>
   <table class="table-secondary table">
        <thead>
        <tr>
        <th></th>
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
$image_path_x = "img_x/default_x.png";
$j = 1;

while ($donnees = $q -> fetch())
{ 
     $qt_dispo_v1 = $donnees['qt']*1;
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
	if ($client_name == 'Amparafa') {$pu = $donnees['pu_aparafa']+0;	}
	if ($client_name == 'Ambato_Tantely') {$pu = $donnees['pu_ambato_tantely']+0;}
?>
<!------------------------------------------------->
        <tr>
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['nom_x'].' : '.$donnees['note_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $pu_list; ?></td>
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
					<form role="form" action="insert_commande_andrefana.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="1" type="number" step="any" id="<?php echo $id_qt; ?>" oninput="Montant2Function($(this));">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label><span class="w3-badge w3-right w3-margin-right w3-green" id="<?php echo $id_montant; ?>"><?php echo $pu; ?></span>
						<input class="form-control btn-light" name="prix_de_vente" value="<?php echo $pu; ?>" type="number" oninput="MontantFunction($(this));" id="<?php echo $id_pu; ?>">
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
        	<th class="text-center" colspan="15"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-right text-secondary"><a href="stock_andrefana.php" target="_blank" rel="noopener noreferrer">Allez</a></th>
        <th class="text-right text-success"><a href="stock_andrefana_retours.php" target="_blank" rel="noopener noreferrer">Retours</a></th>
        <th class="text-right text-danger">Vendu</th>
        <th class="text-right text-primary">Obs</th>
        <th class="text-right">QT</th>
        <th class="text-right">PU</th>
        <th></th>
        <th class="text-right">Montant</th>
        <th class="text-right"><span class='text-success'>Mihoatra</span><br><span class='text-danger'>Tsy Ampy</span></th>
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
$j = 1;
$mihoatra = 0;
$latsaka = 0;
$Chaine_ref = '';
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$sous_total_aparafa = $donnees['sous_total_aparafa'] + $sous_total_aparafa;
	$no = $no + 1;
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
	//----------Nbre total de stock--------------
	$query_qs = "SELECT SUM(qt) as qt FROM stock_prep_calc WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? AND id_x = ? ";
	$query_stock_prep_list = $bdd->prepare($query_qs);

	$query_stock_prep_list->execute(array($username,$donnees['id_x']));

	$data_stock = $query_stock_prep_list -> fetch();
	$Sum_stock = $data_stock['qt']+0;
	$query_stock_prep_list -> closeCursor();

	//-------------------------------------------
	//----------Nbre total de RETOURS--------------
	$query_qs = "SELECT SUM(qt) as qt FROM stock_prep_calc WHERE description_date LIKE '%Entana%' AND user_stock_prep = ? AND id_x = ? ";
	$query_stock_prep_list = $bdd->prepare($query_qs);

	$query_stock_prep_list->execute(array($username,$donnees['id_x']));

	$data_stock = $query_stock_prep_list -> fetch();
	$Sum_retours = $data_stock['qt']+0;
	$query_stock_prep_list -> closeCursor();

	//-------------------------------------------
	//----------Nbre total de VENDU--------------
	$query_qs = "SELECT SUM(qt) as qt FROM vente_calc WHERE user LIKE ? AND id_x = ? ";
	$query_vente_prep_list = $bdd->prepare($query_qs);

	$query_vente_prep_list->execute(array($username,$donnees['id_x']));

	$data_vente = $query_vente_prep_list -> fetch();
	$Sum_vendu = $data_vente['qt']+0;
	$query_vente_prep_list -> closeCursor();

	//-------------------------------------------
?>
		<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php 
		if ($donnees['reference_x']=='XXXX') {echo $donnees['note_commande'];}
		else{echo $donnees['nom_x'];} ?></td>
		<td class="text-right text-secondary"><?php echo $Sum_stock; ?></td>
		<td class="text-right text-success"><?php echo $Sum_retours; ?></td>
		<td class="text-right text-danger"><?php echo $Sum_vendu; ?></td>
		<td class="text-right"><?php
		$obs = $Sum_retours-($Sum_stock-$Sum_vendu);
		if (($Sum_retours == ($Sum_stock-$Sum_vendu)) || $donnees['reference_x'] == 'XXXX') {
			$obsaff ="<span class='w3-badge w3-blue'>"."OK".'</span>';
		} else {
			$obsaff = "<span class='w3-badge w3-red'>".($Sum_retours-($Sum_stock-$Sum_vendu)).'</span>';
			if ($obs > 0) {
				$obsaff = "<span class='w3-badge w3-orange'>".'+'.$obs.'</span>';
			}
		}
		echo $obsaff; ?></td>
		<td class="text-right"><b><?php echo $donnees['qt']; ?></b></td>
		<td class="text-right"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
		<td><span class="w3-badge <?php echo $color_badge; ?>"><?php echo $aff_difference_prix; ?></span></td>
		<td class="text-right"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<td class="text-right"><?php
		//---------DETECTEUR DE REPETITION---------
		//Search word 'Reduit'
		preg_match("/".$donnees['reference_x']."/", $Chaine_ref, $existance);
		
		$Chaine_ref = $donnees['reference_x'].$Chaine_ref;
		//-----------------------------------------
		if (isset($existance[0])) {
			if ($obs > 0) {
			echo "<span class='text-light'>".number_format(ABS($obs*$donnees['prix_unitaire']),0, "", " ").'</span>';
			}
			if ($obs < 0) {
			echo "<span class='text-light'>".number_format(ABS($obs*$donnees['prix_unitaire']),0, "", " ").'</span>';
			}

		}else //IF DOES NOT EXIST
		{
		if ($obs > 0) {
			$mihoatra = ABS($obs*$donnees['prix_unitaire']) + $mihoatra;
			echo "<span class='text-success'>".number_format(ABS($obs*$donnees['prix_unitaire']),0, "", " ").'</span>';
		}
		if ($obs < 0) {
			$latsaka = ABS($obs*$donnees['prix_unitaire']) + $latsaka;
			echo "<span class='text-danger'>".number_format(ABS($obs*$donnees['prix_unitaire']),0, "", " ").'</span>';
		}
		}
		?></td>
		<td><?php if ($donnees['reference_x']=='XXXX') {}
		else{echo $donnees['note_commande'];}; ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_commande']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_commande_andrefana.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
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
					<form role="form" action="modifier_commande_andrefana.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" step = "any" id="<?php echo $id_qt_modif; ?>" oninput="Montant2FunctionModif($(this));">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label><span class="w3-badge w3-right w3-margin-right w3-green" id="<?php echo $id_montant_modif; ?>"><?php echo $donnees['prix_unitaire']*$donnees['qt']; ?></span>
						<input class="form-control btn-success" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" oninput="MontantFunctionModif($(this));" id="<?php echo $id_pu_modif; ?>">
						</div>
						<?php if ($client_name == 'Amparafa') {;?>
						<div class="bg-warning">
						<input type="checkbox" id="<?php echo $check_id2; ?>" onclick="myFunction2()">
						<label for="myCheck">APARAFA IHANY</label><span class="w3-badge w3-right w3-margin-right w3-red" id="<?php echo $id_montant3_modif; ?>"><?php echo $donnees['prix_aparafa']*$donnees['qt']; ?></span>
						<input class="form-control btn-danger" name="prix_aparafa" value="<?php echo $donnees['prix_aparafa']; ?>" type="number" id="<?php echo $textzone_id2; ?>" style="display:none" oninput="Montant3FunctionModif($(this));">
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
<!------------------------------->
	</tr>
<?php
}
	//Query for materiel pas de vente
	$query_no_vente = "SELECT TR.id_x as id_x,reference_x,nom_x,SUM(qt) as qt FROM (SELECT id_stock_prep,stock_prep_calc.nom_du_client as nom_du_client,stock_prep_calc.qt,stock_prep_calc.prix_de_vente as prix_unitaire, stock_prep_calc.prix_de_vente,note,stock_prep_calc.id_x,stock_prep_calc.description_date,user_stock_prep FROM stock_prep_calc LEFT JOIN vente_calc ON stock_prep_calc.id_x= vente_calc.id_x AND stock_prep_calc.user_stock_prep= vente_calc.user WHERE stock_prep_calc.description_date LIKE '%Ajout%' AND user_stock_prep = ?) as TR INNER JOIN produit ON TR.id_x = produit.id_x GROUP BY reference_x";
	$query_no_vente = $bdd->prepare($query_no_vente);

  	$query_no_vente ->execute(array($username));
	while ($data_1 = $query_no_vente->fetch())
         {
         //Check if id does not exist in vente_calc
        $query_vente_exist = "SELECT * FROM vente_calc WHERE id_x = ? AND user = ?";
		$query_vente_exist = $bdd->prepare($query_vente_exist);

		$query_vente_exist->execute(array($data_1['id_x'],$username));
		//Number of Line
		$nb_vente_exist=$query_vente_exist->rowCount ();
		$query_vente_exist -> closeCursor();
         if ($nb_vente_exist == 0) {
         	$no = $no +1;
         	//Nb de retours
         	$query_retours_exist = "SELECT SUM(qt) as qt FROM stock_prep_calc WHERE description_date LIKE '%Entana%' AND user_stock_prep = ? AND id_x = ? ";
			$query_retours_exist = $bdd->prepare($query_retours_exist);

			$query_retours_exist->execute(array($username,$data_1['id_x']));
			//Number of Line
			$nb_retours = $query_retours_exist->fetch();
			$tl_retours = $nb_retours['qt'];
			$query_retours_exist -> closeCursor();
?>
	<tr>
		<td class="text-secondary"><a href="#ancre1"><?php echo $no; ?></a></td>
		<td class="text-secondary"><?php echo $data_1['reference_x']; ?></td>
		<td class="text-secondary"><?php 
		if ($data_1['reference_x']=='XXXX') {echo $data_1['note_commande'];}
		else{echo $data_1['nom_x'];} ?></td>
		<td class="text-right text-secondary"><?php echo $data_1['qt']; ?></td>
		<td class="text-right text-success"><?php echo $tl_retours; ?></td>
		<td class="text-right text-danger">0</td>
		<td class="text-right"><?php
		$obs = $tl_retours-$data_1['qt'];
		$noti = '';
		if ($tl_retours == $data_1['qt']) {
			$obsaff ="<span class='w3-badge w3-blue'>"."OK".'</span>';
		} else {
			$obsaff = "<span class='w3-badge w3-red'>".$obs.'</span>';
			$noti = ' | BANGA NY ENTANA NIVERINA';
			if ($obs > 0) {
				$obsaff = "<span class='w3-badge w3-orange'>".'+'.$obs.'</span>';
				$noti = ' | NIHOATRA NY ENTANA NIVERINA';
			}
		}
		echo $obsaff; ?></td>
		<td colspan="8">TSY NAHALAFOSANA<?php echo $noti; ?></td>
	</tr>
<?php
		}
         }
$query_no_vente -> closeCursor();
?>
				<tr>
		        <th colspan="10">########TOTAL #####</th>
		        <th  class="text-right"><?php echo number_format($total_commande,0, "", " "); ?></th>
		        <th class="text-right"><span class='text-success'><?php echo number_format($mihoatra,0, "", " "); ?></span><br><span class='text-danger'><?php echo number_format($latsaka,0, "", " "); ?></span></th>
		        <th><?php
		        if (($mihoatra-$latsaka)>0) {
		        	echo "<span class='text-success'> Mihoatra ".number_format(ABS($mihoatra-$latsaka),0, "", " ")."</span>";
		        }
		        if (($mihoatra-$latsaka)<0) {
		        	echo "<span class='text-danger'> Banga ".number_format(ABS($mihoatra-$latsaka),0, "", " ")."</span>";
		        }
				?></th>
		        <th></th>
		        <th></th>
		        </tr>
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
		<td><a class="center" href="delete_depense.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
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
		    <th class="text-right"></th>
        </tr>
</tbody>
</table>
</div>
<div>
<!--
	####################FIN AFFICHAGE DEPENSE############################
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->
<p class="form-control text-center text-light" style="background-color: purple"><b>MT ROYAL = <?php echo floor($total_commande);?> | Depense ROYAL = <?php echo $total_depense;?> | Reste ROYAL = <?php echo floor($total_commande-$total_depense);?></b></p>
<form action="valider_commande_andrefana.php" method="POST">
<div>
	<label><b>VOLA AVY AO ANATY VATA</b></label>
	<label class="float-right"><b>POINT</b>##########</label>
</div>
<div>
<input class="col-sm-5 btn" style="background: yellow" name="versement" value=0 type="number" step="any" oninput="Resolution($(this));">
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
<input class="col-sm-5 btn" style="background: pink" name="resolution" value=0 type="number" step="any" id="Tsy_ampy">
</div>
<div>
<br>
<label><b>VOLA MIHOATRA (Versement Mihoatra) Ex: 5000</b></label>
</div>
<div>
<input class="col-sm-5 btn" style="background: orange" name="mihoatra" value=0 type="number" step="any" id="Mihoatra">
</div>
<div class="form-group">
<br>
<label class="float-left"><b>FANAMARIHANA</b> Ex:Mihoatra ny entana nalefa</label>
<input class="form-control bg-light" name="note_general" placeholder="Fanamarihana" type="text">
</div>
<div class="form-group">
<label class="float-left" hidden><b>Chemin</b> Ex: C:\wamp64\www\GS\pj\2020\10 Oct\Vente\Ambato_tantely\22 Alakamisy</label>
<input class="form-control bg-light" name="path" placeholder="Copie-coller l'emplacement du photo ici" type="hidden">
</div>
<div class="form-group text-center">
<a class="center" href="anuler_commande_andrefana.php" onclick="confirmationDelete('Anuler le commande?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
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
<form role="form" action="insert_depense_andrefana.php" method="post">
	<div class="form-group">
		<label id="textzoned3">Montant (Ariary)</label>
		<input class="form-control" name="depense" value=0 type="number" id="textzoned2">
	</div>
	<input name="activity_no" value=<?php echo $activity_no;?> type="hidden">
	<div class="form-group">
		<label>Motif</label>
		<input class="form-control" name="motif" placeholder="Ex: Sakafo/Charbon" type="text">
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

function Resolution(e) {
var versement = <?php echo json_encode($total_commande-$total_depense-($mihoatra-$latsaka)); ?>;
var vata =  e.val();
//alert("OK");
  if ((versement - vata) > 0){
    $("#Tsy_ampy").val(versement - vata);
    $("#Mihoatra").val('0');
  } else {
  	$("#Tsy_ampy").val('0');
    $("#Mihoatra").val(Math.abs(versement - vata));
  }
}

</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>