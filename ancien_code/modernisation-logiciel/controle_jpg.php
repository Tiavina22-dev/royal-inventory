<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ajouter Stock et Inventaire</title>
<script src="js/jquery-3.5.1.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }
  table, th, td {
    border: 1px solid black;
  }
  th, td {
    padding: 4px;
    text-align: left;
  }
</style>
	<style>
  /* Raha tabilao manontolo */
  #tab {
    font-family: Arial, sans-serif; /* mety soloina Verdana, Calibri, sns */
    font-size: 20px;                /* ngeza kokoa */
  }

  /* Raha lohateny (th) tianao ho tena lehibe */
  #tab th {
    font-size: 22px;
    font-weight: bold;
    color: darkblue;
  }

  /* Raha vatana (td) tianao ho kely kokoa noho ny th */
  #tab td {
    font-size: 18px;
    font-weight: bold;
  }
</style>
</head>
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
$limit = 5000;
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
//echo $username;
if ($username == 'TRELAHY' || $username == 'Mann' || $username == 'Hery'|| $username == 'Samuel'|| $username == "El'Sam") {
	$query_user = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' GROUP BY user_stock_prep;";
    $query_user = $bdd->prepare($query_user);
    $query_user->execute(array());
    $user_line = $query_user->rowCount ();
    //echo $user_line;
   //END IF User_line
} //END IF TRELAHY
?>
<!-------------------------------------->
<!-------------------------------------->
 <?php
 } else {
 ?>
<!------------SIMPLE SEARCH---------------->
<?php

$key_word = "";
if (isset($_COOKIE['key_word'])) 
{$key_word = $_COOKIE['key_word'];}
?>
<br>
<br>
<div class="text-center">
<button class="btn btn-primary" id="exportBtn">Sary</button>
</div>

<!---------------search result---------------------------->
<br>
<br>
<br>
<!-------------END SIMPLE SEARCH-------------------------->
<?php
		

if ($total_line == 0)
{		
		//echo "Aucune commande en cours";
	echo "<div class='text-center'><b>PAS DE TRAITEMENT EN COURS</b></div>";
    	//Close IF No.1
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
		<div id="container" class="container">
		<br>
		<h4  id="titre-tableau" class="text-center" style="font-family: Arial ; font-weight: bold; font-size: 20px;"><?php echo (strtoupper($client_name)." || ".$description_date); ?></h4>
		<br>
		<table id="tab" class="table table-bordered dt-responsive nowrap">
        <thead>
        <tr>
        <th class="text-center">Ref</th>
        <th class="text-center">Designation</th>
        <th class="text-center">QT</th>
        <th class="text-center">PU</th>
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
    <!---
    	<td><a class="center" href="valider_stock_controle_individual.php?id_rectification=<?php echo $donnees['id_stock_prep']; ?>" onclick="confirmationDelete('VALIDATE this line?');return false; post ;"><img src="img/valider.png" height="30" width="30" background alt="Edit" /></a></td>
      ----->
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class="text-right"><?php echo (number_format($donnees['qt'])); ?></td>
		<td class="text-right"><?php echo $donnees['prix_unitaire']; ?></td>
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
 
}// close ELSE
 ?>
<br>
<br>
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
 
</body>
<script>
	document.addEventListener("DOMContentLoaded", function() {
    const btn = document.getElementById("exportBtn");

    btn.addEventListener("click", async function() {
        const container = document.getElementById('container');
        const table = container.querySelector("#tab");
        const thead = table.querySelector("thead");
        const tbodyRows = Array.from(table.querySelectorAll("tbody tr"));

        if (!table || tbodyRows.length === 0) { 
            console.error("Table introuvable ou vide"); 
            return; 
        }

        const titreElement = document.getElementById("titre-tableau");
        const safeTitre = titreElement 
            ? titreElement.innerText.replace(/\s+/g, " ").replace(/[^\w\-]/g, " ") 
            : "tableau";

        // Dimensions A4
        const A4_WIDTH_MM = 210;
        const A4_HEIGHT_MM = 297;
        const PX_PER_MM = 3.78; // 96dpi approx
        const A4_WIDTH_PX = A4_WIDTH_MM * PX_PER_MM;
        const A4_HEIGHT_PX = A4_HEIGHT_MM * PX_PER_MM;

        // Esorina padding 20mm (10mm ambony + ambany) ho an'ny content
        const CONTENT_HEIGHT_PX = A4_HEIGHT_PX - (20 * PX_PER_MM);

        let pageCount = 0;
        let pageDiv, tempTable, currentTbody;
        let usedHeight = 0; // kajy ampiasaina

        function newPage() {
            pageDiv = document.createElement("div");
            pageDiv.style.width = A4_WIDTH_MM + "mm";
            pageDiv.style.height = A4_HEIGHT_MM + "mm";
            pageDiv.style.boxSizing = "border-box";
            pageDiv.style.padding = "5mm";
            pageDiv.style.position = "absolute";
            pageDiv.style.left = "-9999px";
            pageDiv.style.background = "#fff";

            if (titreElement) {
                const clonedTitre = titreElement.cloneNode(true);
                clonedTitre.style.marginBottom = "5px";
                pageDiv.appendChild(clonedTitre);
                usedHeight = clonedTitre.offsetHeight || 30; // kajy tombanana
            } else {
                usedHeight = 0;
            }

            tempTable = document.createElement("table");
            tempTable.style.cssText = window.getComputedStyle(table).cssText;

            if (thead) {
                const clonedHead = thead.cloneNode(true);
                tempTable.appendChild(clonedHead);
                usedHeight += 40; // tombanana ho an'ny header
            }

            currentTbody = document.createElement("tbody");
            tempTable.appendChild(currentTbody);
            pageDiv.appendChild(tempTable);
            document.body.appendChild(pageDiv);
        }

        async function exportPage() {
            pageCount++;
            await html2canvas(pageDiv, { scale: 2, useCORS: true, allowTaint: true })
                .then(canvas => {
                    const imgData = canvas.toDataURL("image/jpeg", 1.0);
                    const link = document.createElement("a");
                    link.href = imgData;
                    link.download = `${safeTitre}-${pageCount}.jpg`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            document.body.removeChild(pageDiv);
        }

        newPage();

        for (let i = 0; i < tbodyRows.length; i++) {
            const clonedRow = tbodyRows[i].cloneNode(true);
            clonedRow.querySelectorAll("td, th").forEach(cell => {
                cell.style.cssText = window.getComputedStyle(cell).cssText;
            });

            // Tombanana ny haavon'ny row
            document.body.appendChild(clonedRow);
            let rowHeight = clonedRow.offsetHeight || 20;
            document.body.removeChild(clonedRow);

            if (usedHeight + rowHeight > CONTENT_HEIGHT_PX) {
                // Raha tsy maharaka intsony dia export aloha
                await exportPage();
                newPage();
                usedHeight = 40; // header
            }

            currentTbody.appendChild(clonedRow);
            usedHeight += rowHeight;
        }

        // Pejy farany
        await exportPage();
    });
});

</script>
</html>