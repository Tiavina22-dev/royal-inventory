<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Details Controle Stock</title>
<script src="js/jquery-3.5.1.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/w3.css">
</head>
<body>
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
 }
if (isset($_COOKIE['point_de_vente'])) 
{
	$point_de_vente_cookie = "<b>".$_COOKIE['point_de_vente']." | ".$_COOKIE['description_date']." | Activity No.".$_COOKIE['numero_stock']."</b>";
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
   $query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,produit.prix_de_vente as pu,pu_aparafa,pu_ambato_tantely,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
 }else{
$limit = 200;
$query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,produit.prix_de_vente as pu,pu_aparafa,pu_ambato_tantely,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
}
	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array($username));	

 

$query_stock_prep = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.prix_de_vente as pu,pu_aparafa,pu_ambato_tantely FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC;";
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
 <p>-------------AUCUN RESULTAT--------------</p>
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
<div class="text-center">
  <button class="btn btn-success" data-toggle="modal" data-target="#Other_user">Show For Other User</button>
</div>
<br>
<br>
<!-------------------------------------->
<!-- model form Other User-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Other_user" class="modal fade">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">OTHER USER</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
    </div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="other_user_controle_printable.php" method="post">
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
  <div class="text-center">
      <a href="controle_x.php"><button type="button" class="btn btn-info">RETOUR</button></a>
  </div>
 <?php
 } else {
 ?>
 <div class="text-center">
<a class="center" href="controle_x.php"><button type="button" class="btn btn-info">RETOUR</button></a>
<a class="center" href="controle_x_details.php"><button type="button" class="btn btn-brown">DATE DETAILS</button></a>
</div>
<?php
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
//$description_date = "xxx";
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
         $query_ps = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,produit.prix_de_vente as pu,pu_aparafa,pu_ambato_tantely FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
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
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-right">Quantite</th>
        <th class="text-right">Prix unitaire</th>
        <th class="text-right">Montant</th>
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
	//Handle diminution or augmentation prix
	$query_product_search = "SELECT * FROM produit WHERE id_x = ?";
	$q = $bdd->prepare($query_product_search);
	$q->execute(array($donnees['id_x']));
	$data = $q -> fetch();
	$current_prix = $data['prix_de_vente']+0;
	$q->closeCursor();

	$difference_prix = $donnees['prix_unitaire'] - $current_prix;
	if ($difference_prix > 0) {
		$color_badge = "w3-red";
		$difference_prix = "+".$difference_prix;
	} else {
		$color_badge = "w3-green";
	}
	if ($difference_prix == 0) {$difference_prix ="";}
	//---------------------------------------------------------
			?>
				<tr>
					<td><a href="#ancre1"><?php echo $no; ?></a></td>
					<td><?php echo $donnees['reference_x']; ?></td>
					<td><?php echo $donnees['nom_x']; ?></td>
					<td class="text-right"><?php echo $donnees['qt']; ?></td>
					<td class="text-right"><?php echo number_format($donnees['prix_unitaire']); ?><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $difference_prix; ?></span></td>
					<td class="text-right"><?php echo number_format($donnees['sous_total']); ?></td>
					<td><?php echo $donnees['note']; ?></td>
				</tr>

			<?php
			}
			?>
		<tr>
        <th colspan="5">############### TOTAL ###########</th>
        <th colspan="4" class="text-right"><?php echo number_format($total_commande); ?> Ar</th>
        </tr>
</tbody>
</table>
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
		<input id="ancre1" type="search" class="light-table-filter" data-table="table-bordered" placeholder="Filter/Search">
		<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
	</div>
	<div class="text-center">
  <button class="btn btn-danger filter-obs" data-filter="minus">Tsy ampy</button>
  <button class="btn btn-warning filter-obs" data-filter="all">Tsy milamina</button>
  <button class="btn btn-success filter-obs" data-filter="plus">Mihoatra</button>
</div>

		<table class="table-bordered table">
        <thead>
        <tr>
        	<th class="text-center" colspan="13"><?php echo ($client_name." || ".$description_date." (Activiter No. ".$num_stock.")<br>Date du Dernier Journal de Vente : ".$latest_date."<br>Responsable : ".$username); ?></th>
        </tr>
        <tr>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-center" colspan="2">STOCK</th>
        <th class="text-center" colspan="2">VENTE</th>
        <th class="text-center" colspan="2">RESTE<br>Estime | Reel</th>
        <th class="text-right">Observation</th>
        <th class="text-right">ROYAL</th>
        <th class="text-right">CLIENT</th>
        <th class="text-right">MONTANT</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$no = 0;
$j = 1;
$total_mt = 0;
while ($donnees = $query_stock_prep_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$no = $no + 1;

     //GET QT VENTE || QUERY to sum each point de vente
    $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
	$qsm->execute(array($donnees['id_x'],$client_name));
    $data1 = $qsm -> fetch();
    $qt_vente = $data1['sm'];
    $qsm->closeCursor();
    //QUERY to sum stock
    $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != 'OFF'";
    $qsm = $bdd->prepare($query);
    $qsm->execute(array($donnees['id_x'],$client_name));
    $data1 = $qsm -> fetch();
    $qt_stock = $data1['sm'];
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
//--------GET LISTE OF STOCK AND VENTE---------------------
		//QUERY TO SHOW STOCK
	   $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != 'OFF'";
	    $qs = $bdd->prepare($query_stock);

	    $qs->execute(array($donnees['id_x'],$client_name));
	    $total_stock = 0;
	    $liste_stock = "";
      $hors_cadre_stock = 0;
                        while ($donnees1 = $qs -> fetch())
                        { 
                          $total_stock = $total_stock + $donnees1['qt'];
                          //GET DATE ONLY
                          $daty1 = $donnees1['description_date'];
                          //------HANDLE ZERO AND NEGATIF-----------------------------
                          if ($daty1 =='balance_zero' OR $daty1 =='balance_negative') 
                            {$daty1 = $donnees1['date_time'];
                            $x = chr(35).'/0-9-';
                              preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $daty1, $res_regex);
                            if (isset($res_regex[1])) {
                                $daty1 = $res_regex[1];
                                $daty1 = DateTime::createFromFormat('Y-m-d', $daty1);
                                $daty1 =$daty1 -> format('d/m/y');
                              }
                            }
                          //----------------------------------------------------------
                          preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $daty1, $res_regex);
                          if (isset($res_regex[1])) {
                          	$daty1 = $res_regex[1];
                          }
                          //Eviter wrong format and unwanted space for $date1
                          $date1_en = $daty1;
                          $date1_en = str_replace('- ', '/', $date1_en);
                          $date1_en = str_replace('/ ', '/', $date1_en);
                          $date1_en = str_replace(' / ', '/', $date1_en);
                          $date1_en = str_replace(' -', '/', $date1_en);
                          $date1_en = str_replace(' - ', '/', $date1_en);
                          $date1_en = str_replace('-', '/', $date1_en);
                          //Convert date to english format for compare
                          $date1_en = DateTime::createFromFormat('d/m/Y', $date1_en);
                          $date1_en =$date1_en -> format('y/m/d');
                          //---------------------------------
                          $tete ="";
                          $queue ="";
                          if ($date1_en > $description_date_english)
                          {
                          $tete = "<span style='color: #C0C0C0'>";
                          $queue = '</span>';
                          $hors_cadre_stock = $hors_cadre_stock + $donnees1['qt'];
                          }
                          //GET DATE WITH DESCRIPTION
                          if (isset($_COOKIE['details_date']))
                          {$daty1 = $donnees1['description_date'];}

                          $liste_stock = $liste_stock.$tete.$daty1.'#<b>'.$donnees1['qt'].'</b>'.$queue.'<br>';
                        }
                        $qs->closeCursor();
		//QUERY TO SHOW VENTE
	   $query_stock_vente = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != 'OFF' ";
	    $qv = $bdd->prepare($query_stock_vente);

	    $qv->execute(array($donnees['id_x'],$client_name));
	    $total_vente = 0;
	    $liste_vente = "";
      $hors_cadre_vente = 0;
                        while ($donnees2 = $qv -> fetch())
                        { 
                        	$total_vente = $total_vente + ABS($donnees2['qt']);
                          //GET DATE ONLY
                         	$daty2 = $donnees2['description_date'];
                          preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $daty2, $res_regex);
                          if (isset($res_regex[1])) {
                          	$daty2 = $res_regex[1];
                          }
                          //Eviter wrong format and unwanted space for $date1
                          $date1_en = $daty2;
                          $date1_en = str_replace('- ', '/', $date1_en);
                          $date1_en = str_replace('/ ', '/', $date1_en);
                          $date1_en = str_replace(' / ', '/', $date1_en);
                          $date1_en = str_replace(' -', '/', $date1_en);
                          $date1_en = str_replace(' - ', '/', $date1_en);
                          $date1_en = str_replace('-', '/', $date1_en);
                          //Convert date to english format for compare
                          $date1_en = DateTime::createFromFormat('d/m/Y', $date1_en);
                          $date1_en =$date1_en -> format('y/m/d');
                          //---------------------------------
                          $tete ="";
                          $queue ="";
                          if ($date1_en > $description_date_english)
                          {
                          $tete = "<span style='color: #C0C0C0'>";
                          $queue = '</span>';
                          $hors_cadre_vente = ABS($donnees2['qt'])+$hors_cadre_vente;
                          }
                          //GET DATE WITH DESCRIPTION
                          if (isset($_COOKIE['details_date']))
                          {$daty2 = $donnees2['description_date'];}
                          $liste_vente = $liste_vente.$tete.$daty2.'#<b>'. ABS($donnees2['qt']).'</b>'.$queue.'<br>';
                        }
                        $qv->closeCursor();


	//PU for each point de vente
	$pu = $donnees['prix_unitaire'];
  $puaff = number_format($pu,0, "", " ");
	//if ($client_name == "Amparafa") {$pu = $donnees['pu_aparafa'];$puaff =number_format($pu,0, "", " ");}
	//if ($client_name == "Ambato_Tantely") {$pu = $donnees['pu_ambato_tantely'];$puaff =number_format($pu,0, "", " ");}
//------------------------------------------------------
	//QUANTITE
	$qte_all = number_format($donnees['qt']-$stock_actu-$hors_cadre_vente+$hors_cadre_stock);
  $qte = number_format($donnees['qt']-(($total_stock-$hors_cadre_stock)-($total_vente-$hors_cadre_vente)));
	$mt = ABS($pu*$qte);
	$mtaff = number_format($mt,0, "", " ");
//---------------------------------------------------------
	//BADGE COLOR
	$color_badge = "w3-green";
  $color_mt = "text-orange-1";
	$signe ="+";
	if ($qte < 0) {
		$color_badge = "w3-red";
    $color_mt = "text-danger";
		$signe ="";
	}
  $color_badge2 = "w3-grey";
  $signe2 ="+";
  if ($qte_all < 0) {
    $signe2 ="";
  }
//------------------------------------------------------
	if (($qte == 0)) {
		$qte = "OK";
		$signe ="";
	}
  if (($qte_all == 0)) {
    $qte_all = "OK";
    $signe2 ="";
  }
  if (($qte == 0)) {
    $mtaff = '--';
    $pu = '--';
    $mt = 0;
  }
  if (($qte < 0)) {
    $mt = -$mt;
    //$mt = 0;
    $mtaff = '-'.$mtaff;
  }
	$total_mt = $mt+$total_mt;
	
?>
	<tr>
			
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class="text-right"><?php echo $liste_stock; ?></td>
		<td class="text-right"><b><?php echo ($total_stock-$hors_cadre_stock).'<br>'."<span style='color: #C0C0C0'>".$total_stock."</span>"; ?></b></td>
		<td class="text-right"><?php echo $liste_vente; ?></td>
		<td class="text-right"><b><?php echo ($total_vente-$hors_cadre_vente).'<br>'."<span style='color: #C0C0C0'>".$total_vente."</span>"; ?></b></td>
		<td class="text-right"><b><?php echo (($total_stock-$hors_cadre_stock)-($total_vente-$hors_cadre_vente)).'<br>'."<span style='color: #C0C0C0'>".$stock_actu."</span>"; ?></b></td>
		<td class="text-left"><span class="w3-badge w3-right w3-margin-left; ?>"><?php echo $donnees['qt']; ?></span></td>
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

		<!-------------------------------------
		<td class="text-right"><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $signe.$qte; ?></span><br><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge2; ?>"><?php echo $signe2.$qte_all; ?></span></td>
		--------------------------------------->
		<td class="text-right"><?php echo $puaff; ?></td>
    <td class="text-right text-primary"><?php echo number_format($donnees['prix_client'],0, "", " "); ?></td>
		<td class="text-right <?php echo $color_mt; ?>"><?php echo $mtaff; ?></td>
	</tr>
<?php
}
?>
	<tr>
        <th colspan="12"></th>
        <th class="text-right"><?php echo number_format($total_mt,0, "", " "); ?></th>
        </tr>
</tbody>
</table>
<div class="text-center">
<?php
if ($username == 'STANDARD') {
	# VIDE
} else {
	//GET DATE ON description date chaine
	$chaine = "";
	$date1 = "";
	$chaine =  $description_date.' ';
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
	//echo '# Journal Latest :: '.$latest_english.'<br>';
	//echo '# Controle Date EN ::'.$date1_en.'<br>';
	$state_button = "disabled";
	if ($latest_english >= $date1_en) {$state_button = "enabled";}
	else{$state_button = "disabled";}
 
?>
<a class="center" href="controle_x.php"><button type="button" class="btn btn-success">RETOURS</button></a>
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
      $("#"+id_qt_reduite).val(qr);
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
      $("#"+id_qt_reduite_modifier).val(qr);
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