<!DOCTYPE html>
<html>
<head>
	<title>Journal de Vente</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/mdb.min.css">
	<!---add other css--->
	<link rel="stylesheet" type="text/css" href="css/style_Index.css">
	<link href="css/arrondi.css" rel="stylesheet">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="css/mota.css">
	<link rel="stylesheet" href="css/top20.css">

</head>
<body>
<!------<body style="background: #CF9FFF">----->
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
   $controle = "Controle de vente?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie=$_COOKIE['point_de_vente_cookie'];
 }
?>
<br>
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
		$query_commande_list = $bdd->prepare('SELECT img_path_x,id_commande,nom_du_client,nom_x,reference_x,note_commande,qt,commande.prix_de_vente as prix_unitaire,prix_client, (commande.prix_de_vente)*qt as sous_total, prix_client*qt as sous_total_client,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY id_commande DESC;');
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
	<div class="text-center bg-primary container">
	<b>TRAITER PAR 
    <img src="<?php echo $img_path?>" height="80" width="80" align="middle" class="arrondi" />
    <?php echo $username;?></b>
  </div>
		 <table class="table-striped table container">
        <thead class="table-danger">
        <tr>
        	<th class="text-center" colspan="14"><?php echo ($username." || ".$client_name." || ".$description_date." (Activiter No. ".$numero_commande.")"); ?></th>
        </tr>
        <tr style="background: #c0ca33">
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class='text-right'>QT</th>
        <th class='text-right'>PU</th>
        <th></th>
        <th class='text-right'>MONTANT</th>
        <!------------------
        <?php
        if ($client_name=='Amparafa' OR $client_name=='Bejofo' OR $client_name=='Soalazaina') {
        ?>
        <th class='text-right'>PU <?php echo $client_name; ?></th>
        <th class='text-right'>MT <?php echo $client_name; ?></th>
        <th class='text-right'>Bc <?php echo $client_name; ?></th>
        <?php
        } 
        ?>
        -------------------->
        <th>Note</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$sous_total_client = 0;
$search_color_table = 'table-warning';
$no = 0;
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$sous_total_client = $donnees['sous_total_client'] + $sous_total_client;
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
		if ($search_color_table == 'table-warning') {$search_color_table = 'table-dark';} else {$search_color_table = 'table-warning';}
	//---------------------------------------------------------number_format($donnees['sous_total'],0, "", " ")
?>
	<tr class="<?php echo $search_color_table; ?>">
<?php
			if ($search_color_table = 'table-dark') {
     	echo "<tr class='text-warning'>";
     }
     	else {
     	echo "<tr class='text-light'>";
     }
   ?>
		<td><?php echo $no; ?></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class='text-right'><?php echo $donnees['qt']; ?></td>
		<td class='text-right'><?php echo  number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
		<td><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $Aff1_difference_prix; ?></span></td>
		<td class='text-right'><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<!-----------
        <td class='text-right'><?php echo number_format($donnees['prix_client'],0, "", " "); ?></td>
		<td class='text-right'><?php echo number_format($donnees['sous_total_client'],0, "", " "); ?></td>
		<td class='text-right'><?php echo number_format(($donnees['sous_total_client']-$donnees['sous_total']),0, "", " "); ?></td>
		<td><?php echo $donnees['note_commande']; ?></td>
		------------>
		<td></td>
	</tr>
<?php
}
?>
		<tr style="background: #607d8b" class="text-warning">
				<th colspan="2"><?php echo $description_date ?></th>
		        <th colspan="4" class="text-center">#####TOTAL VENTE#####</th>
		        <th  class="text-right"><b><?php echo number_format($total_commande,0, "", " "); ?></b></th>
		        <th></th>
		        </tr>
        <!---------
        <?php
        if ($client_name=='Amparafa' OR $client_name=='Bejofo' OR $client_name=='Soalazaina') {
        	$difference = $sous_total_client-$total_commande;
	        if ($difference < 0)
	        {
	        	$difference = 0;
	        }
        ?>
        <th class='text-right'><?php echo number_format($sous_total_client,0, "", " "); ?></th>
        <th class='text-right'><?php echo number_format($difference,0, "", " ");?></th>
        <th></th>
        --------->
        <?php
        } 
        ?>
        </tr>
</tbody>
</table>
</div>
<?php
//Query to liste depense of actual activity ID
$query_client_name = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['numero_commande'];
         $client_name = $donnees['point_de_vente'];
         $description_date = $donnees['description_date'];
          }
		$query_client_name->closeCursor();

$query_depense ="SELECT * FROM depense WHERE activity_no = ? ";
$query_depense_list = $bdd->prepare($query_depense);

  $query_depense_list->execute(array($numero_commande));  
//get row count
$total_depense_line=$query_depense_list->rowCount ();
?>
<div>
		 <table class="table-active table btn-brown container">
        <thead class="text-light">
        <tr>
        	<th class="text-center bg-secondary text-light" colspan="7">DEPENSE <?php echo (strtoupper($client_name)." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
            <th>No.</th>
	        <th>ID</th>
	        <th>MOTIF</th>
	        <th class="text-right">DEPENSE ROYAL</th>
	        <th></th>
	        <th>Suppr</th>
        </tr>
        </thead>
        <tbody class="text-light">
<?php
$total_depense = 0;
$total_depense_aparafa = 0;
$total_depense_tsinjo = 0;
$no = 0;
while ($donnees = $query_depense_list -> fetch())
{ 
	$total_depense = $donnees['montant'] + $total_depense;
	$no = $no + 1;
	//echo $donnees['motif'];
?>
	<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['id_depense']; ?></td>
		<td><?php echo $donnees['motif']; ?></td>
		<td class="text-right"><?php 
		if (($donnees['montant']+0) != 0) {
			echo number_format($donnees['montant'],0, "", " ");
		
		 ?></td>
	    <?php
	    } 
	    ?>
	    <td></td>
		<td><a class="center" href="delete_depense.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a></td>
	</tr>
<?php
}
}
$query_depense_list->closeCursor();
$query_commande_list = $bdd->prepare('SELECT img_path_x,id_commande,nom_du_client,nom_x,reference_x,	note_commande,qt,commande.prix_de_vente as prix_unitaire,prix_client, (commande.prix_de_vente)*qt as sous_total, prix_client*qt as sous_total_client,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY id_commande DESC;');
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

if ($total_line == 0)  {
}
else { ?>
		<tr style="background:#1b5e20">
	        <th colspan="3">########TOTAL DEPENSE#####</th>
	        <th class="text-right"><?php echo number_format($total_depense,0, "", " "); ?></th>
	        <th></th>
	        <th></th>
        </tr>
</tbody>
</table>
<p class="form-control text-center text-light container" style="background-color: purple"><b>MT ROYAL =<?php echo floor($total_commande);?> | Depense ROYAL:<?php echo $total_depense;?> <?php
	    if ($client_name=='Amparafa' OR $client_name=='Bejofo' OR $client_name=='Soalazaina') {
	    	echo "| Depense <?php if ($client_name=='Amparafa') {echo 'A/fa';} if ($client_name=='Bejofo') {echo 'B/fo';} if ($client_name=='Soalazaina') {echo 'Soal';} ?> :".$total_depense_aparafa;
	    ?>
	    
	    <?php
	    } 
	    ?> | Reste ROYAL=<?php echo floor($total_commande-$total_depense);?>
	    <?php
	    if ($client_name=='Amparafa' OR $client_name=='Bejofo' OR $client_name=='Soalazaina') {
	    	echo " | Reste GLOBAL=";
	    	echo floor($sous_total_client-$total_depense_aparafa-$total_depense);;
	    ?>
	    
	    <?php
	    } 
	    ?></b></p>
<br>
<br>
<br>
<br>
<br>
<br>
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
$query_commande_list = $bdd->prepare('SELECT img_path_x,id_commande,nom_du_client,nom_x,reference_x,	note_commande,qt,commande.prix_de_vente as prix_unitaire,prix_client, (commande.prix_de_vente)*qt as sous_total, prix_client*qt as sous_total_client,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? ORDER BY id_commande DESC;');
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
	//----------LATEST JOURNAL DE VENTE---------------
	//################TANTELY#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambato_Tantely' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_tantely = "31/01/20";
    $liste_v_tantely = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $chaine =  $donnees['description_date'].' ';
        $liste_v_tantely = $liste_v_tantely.'# '.$donnees['description_date'].'<br>';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_tantely = str_replace('- ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace('/ ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace(' / ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace(' -', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace(' - ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace('-', '/', $latest_date_v_tantely);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_tantely);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_tantely = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		$latest_date_v_tantely_c = $latest_date_v_tantely;
		//Convert date to format YYYY/mm/dd
        $latest_date_v_tantely = DateTime::createFromFormat('d/m/y', $latest_date_v_tantely);
		$latest_date_v_tantely = $latest_date_v_tantely -> format('Y-m-d');
		//------next day-------SOLUTION 1-----------------
		//$latest_date_v_tantely = explode('/', $latest_date_v_tantely);
		//$latest_date_v_tantely = Date("Y/m/d",mktime(0,0,0,$latest_date_v_tantely[1],$latest_date_v_tantely[2]+1,$latest_date_v_tantely[0]));
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_tantely = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_tantely)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_tantely = Date('l', strtotime($latest_date_v_tantely));
   		if ($day_tantely == 'Sunday') {
   			$latest_date_v_tantely = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_tantely)));
   			$day_tantely = Date('l', strtotime($latest_date_v_tantely));
   		}
   		//-----------------------------------------
		$latest_date_v_tantely_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_tantely);
		$latest_date_v_tantely_a = $latest_date_v_tantely_a -> format('d/m/y');
	//---------------------------------------
   	//################AMPARAFA#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Amparafa' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_amparafa = "09/09/09";
    $liste_v_amparafa = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_amparafa = $liste_v_amparafa.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_amparafa = str_replace('- ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace('/ ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace(' / ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace(' -', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace(' - ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace('-', '/', $latest_date_v_amparafa);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_amparafa);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_amparafa = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		    //Convert date to format YYYY/mm/dd
		$latest_date_v_amparafa_c = $latest_date_v_amparafa;
        $latest_date_v_amparafa = DateTime::createFromFormat('d/m/y', $latest_date_v_amparafa);
		$latest_date_v_amparafa = $latest_date_v_amparafa -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_amparafa = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_amparafa)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_amparafa = Date('l', strtotime($latest_date_v_amparafa));
   		if ($day_amparafa == 'Sunday') {
   			$latest_date_v_amparafa = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_amparafa)));
   			$day_amparafa = Date('l', strtotime($latest_date_v_amparafa));
   		}
   		//-----------------------------------------
		$latest_date_v_amparafa_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_amparafa);
		$latest_date_v_amparafa_a = $latest_date_v_amparafa_a -> format('d/m/y');
   		$qc->closeCursor();
   		//################SOALAZAINA#####################
   //Query to get date of Journal de vente Soalazaina
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Soalazaina' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_soalazaina = "09/09/09";
    $liste_v_soalazaina = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_soalazaina = $liste_v_soalazaina.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_soalazaina = str_replace('- ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace('/ ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace(' / ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace(' -', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace(' - ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace('-', '/', $latest_date_v_soalazaina);

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

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_soalazaina);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_soalazaina = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
            $latest_date_v_soalazaina_c = $latest_date_v_soalazaina;
   //Convert date to format YYYY/mm/dd
        $latest_date_v_soalazaina = DateTime::createFromFormat('d/m/y', $latest_date_v_soalazaina);
		$latest_date_v_soalazaina = $latest_date_v_soalazaina -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_soalazaina = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_soalazaina)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_soalazaina = Date('l', strtotime($latest_date_v_soalazaina));
   		if ($day_soalazaina == 'Sunday') {
   			$latest_date_v_soalazaina = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_soalazaina)));
   			$day_soalazaina = Date('l', strtotime($latest_date_v_soalazaina));
   		}
   		//-----------------------------------------
		$latest_date_v_soalazaina_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_soalazaina);
		$latest_date_v_soalazaina_a = $latest_date_v_soalazaina_a -> format('d/m/y');
   $qc->closeCursor();
   //################ELECTRONIQUE#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Electronique' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_electronique = "09/09/09";
    $liste_v_electronique = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_electronique = $liste_v_electronique.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_electronique = str_replace('- ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace('/ ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace(' / ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace(' -', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace(' - ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace('-', '/', $latest_date_v_electronique);

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

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_electronique);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_electronique = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
        $latest_date_v_electronique_c = $latest_date_v_electronique;
            //Convert date to format YYYY/mm/dd
        $latest_date_v_electronique = DateTime::createFromFormat('d/m/y', $latest_date_v_electronique);
		$latest_date_v_electronique = $latest_date_v_electronique -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_electronique = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_electronique)));
		
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_electronique = Date('l', strtotime($latest_date_v_electronique));
   		if ($day_electronique == 'Sunday') {
   			$latest_date_v_electronique = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_electronique)));
   			$day_electronique = Date('l', strtotime($latest_date_v_electronique));
   		}
   		//-----------------------------------------
		$latest_date_v_electronique_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_electronique);
		$latest_date_v_electronique_a = $latest_date_v_electronique_a -> format('d/m/y');
   $qc->closeCursor();
   //################BEJOFO#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Bejofo' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_bejofo = "09/99/99";
    $liste_v_bejofo = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_bejofo = $liste_v_bejofo.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_bejofo = str_replace('- ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace('/ ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace(' / ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace(' -', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace(' - ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace('-', '/', $latest_date_v_bejofo);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_bejofo);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_bejofo = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		$latest_date_v_bejofo_c = $latest_date_v_bejofo;
		    //Convert date to format YYYY/mm/dd
        $latest_date_v_bejofo = DateTime::createFromFormat('d/m/y', $latest_date_v_bejofo);
		$latest_date_v_bejofo = $latest_date_v_bejofo -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_bejofo = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_bejofo)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_bejofo = Date('l', strtotime($latest_date_v_bejofo));
   		if ($day_bejofo == 'Sunday') {
   			$latest_date_v_bejofo = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_bejofo)));
   			$day_bejofo = Date('l', strtotime($latest_date_v_bejofo));
   		}
   		//-----------------------------------------
		$latest_date_v_bejofo_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_bejofo);
		$latest_date_v_bejofo_a = $latest_date_v_bejofo_a -> format('d/m/y');
   $qc->closeCursor();
   //################VEVE#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambato_veve_photo' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_Ambato_veve_photo = "09/09/09";
    $liste_v_veve_photo = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_veve_photo = $liste_v_veve_photo.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_Ambato_veve_photo = str_replace('- ', '/', $latest_date_v_Ambato_veve_photo);
        $latest_date_v_Ambato_veve_photo = str_replace('/ ', '/', $latest_date_v_Ambato_veve_photo);
        $latest_date_v_Ambato_veve_photo = str_replace(' / ', '/', $latest_date_v_Ambato_veve_photo);
        $latest_date_v_Ambato_veve_photo = str_replace(' -', '/', $latest_date_v_Ambato_veve_photo);
        $latest_date_v_Ambato_veve_photo = str_replace(' - ', '/', $latest_date_v_Ambato_veve_photo);
        $latest_date_v_Ambato_veve_photo = str_replace('-', '/', $latest_date_v_Ambato_veve_photo);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_Ambato_veve_photo);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_Ambato_veve_photo = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		    //Convert date to format YYYY/mm/dd
		$latest_date_v_Ambato_veve_photo_c = $latest_date_v_Ambato_veve_photo;
        $latest_date_v_Ambato_veve_photo = DateTime::createFromFormat('d/m/y', $latest_date_v_Ambato_veve_photo);
		$latest_date_v_Ambato_veve_photo = $latest_date_v_Ambato_veve_photo -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_Ambato_veve_photo = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_Ambato_veve_photo)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_veve = Date('l', strtotime($latest_date_v_Ambato_veve_photo));
   		if ($day_veve == 'Sunday') {
   			$latest_date_v_Ambato_veve_photo = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_Ambato_veve_photo)));
   			$day_veve = Date('l', strtotime($latest_date_v_Ambato_veve_photo));
   		}
   		//-----------------------------------------
		$latest_date_v_Ambato_veve_photo_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_Ambato_veve_photo);
		$latest_date_v_Ambato_veve_photo_a = $latest_date_v_Ambato_veve_photo_a -> format('d/m/y');
   $qc->closeCursor();
   //################AMBAIBOHO TOLE#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Tole' AND description_date NOT LIKE '%MORARANO%'AND description_date NOT LIKE '%ANDREFANA%' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_ambaibo_tole = "09/09/09";
    $liste_v_ambaibo_tole = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_ambaibo_tole = $liste_v_ambaibo_tole.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_ambaibo_tole = str_replace('- ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace('/ ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace(' / ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace(' -', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace(' - ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace('-', '/', $latest_date_v_ambaibo_tole);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_ambaibo_tole);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_ambaibo_tole = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		 $latest_date_v_ambaibo_tole_c = $latest_date_v_ambaibo_tole;
		    //Convert date to format YYYY/mm/dd
        $latest_date_v_ambaibo_tole = DateTime::createFromFormat('d/m/y', $latest_date_v_ambaibo_tole);
		$latest_date_v_ambaibo_tole = $latest_date_v_ambaibo_tole -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_ambaibo_tole = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_ambaibo_tole)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_tole = Date('l', strtotime($latest_date_v_ambaibo_tole));
   		if ($day_tole == 'Sunday') {
   			$latest_date_v_ambaibo_tole = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_ambaibo_tole)));
   			$day_tole = Date('l', strtotime($latest_date_v_ambaibo_tole));
   		}
   		//-----------------------------------------
		$latest_date_v_ambaibo_tole_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_ambaibo_tole);
		$latest_date_v_ambaibo_tole_a = $latest_date_v_ambaibo_tole_a -> format('d/m/y');
   $qc->closeCursor();
   //################MORARANO#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Tole' AND description_date LIKE '%MORARANO%' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_morarano = "09/09/09";
    $liste_v_morarano = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_morarano = $liste_v_morarano.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_morarano = str_replace('- ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace('/ ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace(' / ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace(' -', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace(' - ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace('-', '/', $latest_date_v_morarano);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_morarano);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_morarano = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		    //Convert date to format YYYY/mm/dd
		$latest_date_v_morarano_c = $latest_date_v_morarano;
       $latest_date_v_morarano = DateTime::createFromFormat('d/m/y', $latest_date_v_morarano);
		$latest_date_v_morarano = $latest_date_v_morarano -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_morarano = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_morarano)));
		//------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_morarano = Date('l', strtotime($latest_date_v_morarano));
   		if ($day_morarano == 'Sunday') {
   			$latest_date_v_morarano = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_morarano)));
   			$day_morarano = Date('l', strtotime($latest_date_v_morarano));
   		}
   		//-----------------------------------------
		$latest_date_v_morarano_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_morarano);
		$latest_date_v_morarano_a = $latest_date_v_morarano_a -> format('d/m/y');
   $qc->closeCursor();
   //################ANDREFANA#####################
   //Query to get date of Journal de vente
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Tole' AND description_date LIKE '%ANDREFANA%' GROUP BY description_date ORDER BY id_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_andrefana = "09/09/09";
    $liste_v_andrefana = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_v_andrefana = $liste_v_andrefana.'# '.$donnees['description_date'].'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_andrefana = str_replace('- ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace('/ ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace(' / ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace(' -', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace(' - ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace('-', '/', $latest_date_v_andrefana);

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

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_andrefana);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_andrefana = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
		    //Convert date to format YYYY/mm/dd
		$latest_date_v_andrefana_c = $latest_date_v_andrefana;
       $latest_date_v_andrefana = DateTime::createFromFormat('d/m/y', $latest_date_v_andrefana);
		$latest_date_v_andrefana = $latest_date_v_andrefana -> format('Y-m-d');
		//------next day-------SOLUTION 2-----------------
		$latest_date_v_andrefana = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_andrefana)));
		//----------------------------------------
   		$qc->closeCursor();
   		//Get day name
   		$day_andrefana = Date('l', strtotime($latest_date_v_andrefana));
   		if ($day_andrefana == 'Sunday') {
   			$latest_date_v_andrefana = Date("Y-m-d",strtotime('+1 day',strtotime($latest_date_v_andrefana)));
   			$day_andrefana = Date('l', strtotime($latest_date_v_andrefana));
   		}
   		//-----------------------------------------
		$latest_date_v_andrefana_a = DateTime::createFromFormat('Y-m-d', $latest_date_v_andrefana);
		$latest_date_v_andrefana_a = $latest_date_v_andrefana_a -> format('d/m/y');
   		$qc->closeCursor();
   //################END ANDREFANA#####################
?>
<div class="text-center">
    <h2 class="top20_style">VENTE</h2>
    </div>
	<div class="text-center">
	<button type="submit" class="btn btn-white" data-toggle="modal" data-target="#Demarrer">Demarrer</button>
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
<form role="form" action="new_client_name_commande.php" method="post">
	<!------------------AUTO LISTE SHOP------------------>
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
	<!------------------END AUTO LISTE SHOP------------------>
	<div class="form-group">
		<label id="date_aff1">Choisir la date du journal</label><span id="date_aff2" class="float-right btn-light"></span>
		<input id="date_id" oninput="Check_date($(this));" class="form-control bg-warning" value="" name="date_journal" type="DATE">
	</div>
	<div class="form-group">
		<button type="submit" id="start_button" class="btn btn-success" disabled>Valider</button>
		<input oninput="unlock2($(this));" class="btn bg-light" type="password">
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
<form role="form" action="simple_search_commande.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter btn btn-light" name="key_word" placeholder="Name/Code/Search" value="<?php echo $key_word; ?>">
<button type="submit" class="btn btn-info">Search</button>
<a href="stock_reduit.php" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-indigo">Stock Retirer / Retours</button></a>
</div>
<br>
<div class="text-center text-light">
    <input type="checkbox" name="checkbox" checked>
    <label>Chercher Dans le Stock Existant Seulement</label>
</div>
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
	if (isset($_COOKIE['checkbox'])) 
	{$checkbox = $_COOKIE['checkbox'];}
	//-----------------------------
	if ($checkbox == 'TRUE') {
		# PRODUIT EXISTANT DANS LE STOCK SEULEMENT
		$query_product_search = "SELECT prix_fournisseur,produit.id_x as id_x, nom_x, prix_de_vente, qt, reference_x, note_x,img_path_x,pu_aparafa,pu_ambato_tantely,pu_soalazaina FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE (nom_x LIKE ? OR reference_x LIKE ? OR produit.id_x LIKE ?) AND type_de_mvt='stock' AND nom_client_fournisseur = ? GROUP BY reference_x ORDER BY date_time";
		$q = $bdd->prepare($query_product_search);

		$q->execute(array("%".$key_word."%", "%".$key_word."%", "%".$key_word."%" , $client_name));
	} else {
		# GLOBAL PRODUIT
		$query_product_search = 'SELECT prix_fournisseur,produit.id_x as id_x, nom_x, prix_de_vente, qt, reference_x, note_x,img_path_x,pu_aparafa,pu_ambato_tantely,pu_soalazaina FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x WHERE nom_x LIKE ? OR reference_x LIKE ? OR produit.id_x LIKE ? GROUP BY reference_x ORDER BY date_time';
		$q = $bdd->prepare($query_product_search);

		$q->execute(array("%".$key_word."%", "%".$key_word."%", "%".$key_word."%"));
	}
	
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<p class='text-center';><b>".$client_name." does <span class='text-danger';>NOT have "."".$key_word."</span> on his stock! Add first </b></p>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div class="container">
		<input id="ancre1" type="search" class="light-table-filter btn btn-light" data-table="table-striped" placeholder="Filter/Search">
	</div>
</br>
   <table class="table-striped table container">
        <thead>
        <tr class = "table-danger">
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
 $search_color_table = 'table-success';
while ($donnees = $q -> fetch())
{ 
	//HANDLE QT DISPO FOR EACH POINT DE VENTE
    //QUERY to sum each vente by point de vente

                             $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$client_name));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?/* AND status NOT LIKE 'General_Inventory'*/";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$client_name));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?/* AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X*/";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$client_name));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //----------------------
                            $qt_dispo = $vente + $stock/* + $stock_GI*/;
    //---------------------
   //------------------------------------
     $check_id = "myCheck".$i; 
     $textzone_id = "textzone".$i;
     //IMAGE PATH_
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
 }
         //=====================HANDLE PU LIST=========================
    $pu_list='---------<br>';
    $query_shop = "SELECT DISTINCT(nom_client_fournisseur) as nf FROM mvt WHERE id_x = ? ORDER BY nom_client_fournisseur";
    $query_shop = $bdd->prepare($query_shop);
    $query_shop->execute(array($donnees['id_x']));
    while ($donnees_qsh = $query_shop -> fetch())
    {
    	$query_prix = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'stock' ORDER BY Date_du_Journal_mvt DESC LIMIT 2";
    	$query_prix = $bdd->prepare($query_prix);

    	$query_prix->execute(array($donnees['id_x'],$donnees_qsh['nf']));
    	//$pu_list = $pu_list.$donnees_qsh['nf'];
    	while ($donnees_pu = $query_prix -> fetch())
    	{
    		$date = DateTime::createFromFormat('Y-m-d', $donnees_pu['Date_du_Journal_mvt']);
        	$daty = $date -> format('l d M Y');
    		$pu_list = $pu_list."# <span class='text-danger'><b>".number_format($donnees_pu['prix_unitaire'],0, "", " ")."</span></b>"." - <span class='text-warning'><b>".number_format($donnees_pu['prix_client'],0, "", " ")."</span></b> : ".$donnees_pu['nom_client_fournisseur']." : <b>".$daty."</b><br>";
    	}
    	$pu_list = $pu_list.'---------<br>';
    	$query_prix->closeCursor();
    	
    }
    $query_shop->closeCursor();
    //Adding Prix Fournisseur in pu_list
        $pu_list = "---------<br># <span class='text-primary'><b>".number_format(($donnees['prix_fournisseur']+0),0, "", " ").'</span></b> : Reference | Prix Fournisseur<br>'.$pu_list;

    //=======================END PU LIST=======================
	//Handle Montant
	$id_montant = $j.'_id_montant';
	$id_qt = $j.'_id_qt';
	$id_pu = $j.'_id_pu';
	$j = $j+1;

	//Specify PU by point de vente
	$pu = $donnees['prix_de_vente']+0;
	if ($client_name == 'Amparafa') {$pu = $donnees['pu_aparafa']+0;	}
	if ($client_name == 'Ambato_Tantely') {$pu = $donnees['pu_ambato_tantely']+0;}
	if ($client_name == 'Soalazaina') {$pu = $donnees['pu_soalazaina']+0;}
	//Handle color table
	if ($search_color_table == 'table-success') {$search_color_table = 'table-warning';} else {$search_color_table = 'table-success';}
	//GET LATEST PRIX CLIENT----------
	$query_prix_Client = "SELECT * from mvt WHERE type_de_mvt = 'stock' AND id_x = ? AND nom_client_fournisseur = ? ORDER BY Date_du_Journal_mvt DESC LIMIT 1;";
	$query_prix_Client = $bdd->prepare($query_prix_Client);

	$query_prix_Client ->execute(array($donnees['id_x'],$client_name));
	$donnees_client = $query_prix_Client -> fetch();
	$prix_latest_client = $donnees_client['prix_client']+0;
	$query_prix_Client -> closeCursor();
	if ($prix_latest_client == 0) {
		//$prix_latest_client = $pu;
	}
	//--------------------------------
?>
<!------------------------------------------------->
        <tr class = "bg-dark text-light">
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['nom_x'].' : '.$donnees['note_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $pu_list; ?></td>
        <td><?php echo $qt_dispo; ?></td>
    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo ("no".$donnees['id_x']); ?>">Select</button></td>
        </tr>
		<!-- modal form SELECT FOR SIMPLE SEARCH-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$donnees['id_x']); ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content btn-brown">
					<div class="modal-header">
					<h4 class="modal-title text-warning"><?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="insert_commande.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control btn btn-indigo" name="qt" value="1" type="number" step="any" id="<?php echo $id_qt; ?>" oninput="Montant2Function($(this));">
						</div>
						<div class="form-group">
						<label>PRIX ANOMEZANA ROYAL (Ariary)</label><span class="w3-badge w3-right w3-margin-right w3-green" id="<?php echo $id_montant; ?>"><?php echo $pu; ?></span>
						<input class="form-control btn btn-danger" name="prix_de_vente" value="<?php echo $pu; ?>" type="number" oninput="MontantFunction($(this));" id="<?php echo $id_pu; ?>">
						<input class="form-control btn-danger" id="<?php echo $textzone_id; ?>" style="display:none">
						</div>
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
    <br>
    <br>
    <br>
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
</br>
	<h5 class="text-center text-success"><?php echo $msg_ok; ?></h5>
	<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
	<div class="text-center">
    <h2 class="top20_style">VENTE</h2>
    </div>
	<div class="container">
	<input id="ancre1" type="search" class="light-table-filter btn btn-light" data-table="table-striped" placeholder="Filter/Search">
	<input type="button" class="float-right btn btn-secondary font-weight-bold text-light" value="<?php echo ($total_line); ?> Lignes">
	</div>
</br>
	 <table class="table-striped table container">
        <thead class="table-danger">
        <tr>
        	<th class="text-center" colspan="13"><?php echo (strtoupper($client_name)." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr style="background: #c0ca33">
        <th></th>
        <th>No.</th>
        <th>Ref</th>
        <th>Designation</th>
        <th class="text-right">Qt</th>
        <th class="text-right">PU</th>
        <th class="text-center">Equart</th>
        <th class="text-right">MT ROYAL</th>
        <th>Note</th>
        <th>Modifier</th>
        <th>Supprimer</th>
        </tr>
        </thead>
        <tbody>
<?php
$total_commande = 0;
$sous_total_client = 0;
$no = 0;
$check_id2 = "";
$textzone_id2 = "";
$m = 1;
$j = 1;
$search_color_table = 'table-warning';
while ($donnees = $query_commande_list -> fetch())
{ 
	$total_commande = $donnees['sous_total'] + $total_commande;
	$sous_total_client = $donnees['sous_total_client'] + $sous_total_client;
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
	if ($client_name=='Amparafa' OR $client_name=='Bejofo' OR $client_name=='Soalazaina') {
		$current_prix = $data['pu_aparafa']+0;
	}
	if ($client_name=='Soalazaina') {
		$current_prix = $data['pu_soalazaina']+0;
	}
	$q->closeCursor();
	
	$aff_difference_prix = "";
	$aff_difference_prix_2 = "";
	$difference_prix = $donnees['prix_unitaire'] - $current_prix;
	//GET LATEST PRIX CLIENT----------
	$query_prix_Client = "SELECT * from mvt WHERE type_de_mvt = 'stock' AND id_x = ? AND nom_client_fournisseur = ? ORDER BY Date_du_Journal_mvt DESC LIMIT 1;";
	$query_prix_Client = $bdd->prepare($query_prix_Client);

	$query_prix_Client ->execute(array($donnees['id_x'],$client_name));
	$donnees_client = $query_prix_Client -> fetch();
	$prix_latest_client = $donnees_client['prix_client']+0;
	$query_prix_Client -> closeCursor();
	//--------------------------------
	$difference_prix_2 = $donnees['prix_client'] - $prix_latest_client;
	$aff_difference_prix = number_format($difference_prix,0, "", " ");
	$aff_difference_prix_2 = number_format($difference_prix_2,0, "", " ");
	if ($difference_prix > 0) {
		$color_badge = "w3-green";
		$difference_prix = "+".number_format($difference_prix,0, "", " ");
		$aff_difference_prix = $difference_prix;
	} else {
		$color_badge = "w3-red";
	}
	if ($difference_prix_2 > 0) {
		$color_badge_2 = "w3-green";
		$difference_prix_2 = "+".number_format($difference_prix_2,0, "", " ");
		$aff_difference_prix_2 = $difference_prix_2;
	} else {
		$color_badge_2 = "w3-red";
	}
	if ($difference_prix == 0) {$aff_difference_prix ="";}
	if ($difference_prix_2 == 0) {$aff_difference_prix_2 ="";}
	if ($prix_latest_client == 0) {$aff_difference_prix_2 = 'None';}
	//---------------------------------------------------------
	//Handle Montant
	$id_montant_modif = $j.'_id_montant_modif';
	$id_qt_modif = $j.'_id_qt_modif';
	$id_pu_modif = $j.'_id_pu_modif';
	$id_montant3_modif = $j.'_id_montant3_modif';
	$j = $j+1;
	//Handle color table
	if ($search_color_table == 'table-warning') {$search_color_table = 'table-dark';} else {$search_color_table = 'table-warning';}
	//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
 	}
     
     
     
?>
		<tr class="<?php echo $search_color_table; ?>">
<?php
			if ($search_color_table = 'table-dark') {
     	echo "<tr class='text-warning'>";
     }
     	else {
     	echo "<tr class='text-light'>";
     }
   ?>
		<td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
		<td><a href="" class="text-success"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['reference_x']; ?></td>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td class="text-right"><?php echo $donnees['qt']; ?></td>
		<td class="text-right"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
		<td><span class="w3-badge w3-right w3-margin-right <?php echo $color_badge; ?>"><?php echo $aff_difference_prix; ?></span></td>
		<td class="text-right"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
		<td><?php echo $donnees['note_commande']; ?></td>
		<!-------------------
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_commande']; ?>">Modifier</button></td>
		--------------------->
		<td><button type="button" class="text-center" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_commande']; ?>"><img src="img/edit_icon.png" height="30" width="30" background alt="Edit" /></button></td>
		<td><a class="center" href="delete_commande.php?id_commande=<?php echo $donnees['id_commande']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a></td>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_commande']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content btn-brown">
					<div class="modal-header">
					<h4 class="modal-title text-warning">Modifier commande No. <?php echo $donnees['id_commande']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_commande.php" method="post">
						<div class="form-group">
						<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
						<input class="form-control btn btn-indigo" name="qt" value="<?php echo $donnees['qt']; ?>" type="number" step = "any" id="<?php echo $id_qt_modif; ?>" oninput="Montant2FunctionModif($(this));">
						</div>
						<div class="form-group">
						<label>PRIX ANOMEZANA ROYAL (Ariary)</label><span class="w3-badge w3-right w3-margin-right w3-green" id="<?php echo $id_montant_modif; ?>"><?php echo $donnees['prix_unitaire']*$donnees['qt']; ?></span>
						<input class="form-control btn btn-danger" name="prix_de_vente" value="<?php echo $donnees['prix_unitaire']; ?>" type="number" oninput="MontantFunctionModif($(this));" id="<?php echo $id_pu_modif; ?>">
						<input class="form-control btn-danger" id="<?php echo $textzone_id2; ?>" style="display:none">
						</div>
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
				<tr style="background: #607d8b" class="text-warning">
				<th colspan="3"><?php echo $description_date ?></th>
		        <th colspan="4" class="text-center">#####TOTAL VENTE#####</th>
		        <th  class="text-right"><b><?php echo number_format($total_commande,0, "", " "); ?></b></th>
		        <th></th>
		        <th></th>
		        <th></th>
		        </tr>
</tbody>
</table>
</div>
<!--
	####################DEBUT AFFICHAGE DEPENSE############################
--->
<div class="text-center">
<button type="button" class="btn btn-teal" data-toggle="modal" data-target="#Modal_depense">Depense</button>
</div>
<br>
<br>
<div>
		 <table class="table-active table btn-brown container">
        <thead class="text-light">
        <tr>
        	<th class="text-center bg-secondary text-light" colspan="7">DEPENSE <?php echo (strtoupper($client_name)." || ".$description_date." (Activiter No. ".$num_stock.")"); ?></th>
        </tr>
        <tr>
            <th>No.</th>
	        <th>ID</th>
	        <th>MOTIF</th>
	        <th class="text-right">DEPENSE ROYAL</th>
	        <th></th>
	        <th>Suppr</th>
        </tr>
        </thead>
        <tbody class="text-light">
<?php
$total_depense = 0;
$total_depense_aparafa = 0;
$total_depense_tsinjo = 0;
$no = 0;
while ($donnees = $query_depense_list -> fetch())
{ 
	$total_depense = $donnees['montant'] + $total_depense;
	$no = $no + 1;
	//echo $donnees['motif'];
?>
	<tr>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['id_depense']; ?></td>
		<td><?php echo $donnees['motif']; ?></td>
		<td class="text-right"><?php 
		if (($donnees['montant']+0) != 0) {
			echo number_format($donnees['montant'],0, "", " ");
		
		 ?></td>
	    <?php
	    } 
	    ?>
	    <td></td>
		<td><a class="center" href="delete_depense.php?id_depense=<?php echo $donnees['id_depense']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a></td>
	</tr>
<?php
}
}
$query_depense_list->closeCursor();

if ($total_line == 0)  {
}
else { ?>
		<tr style="background:#1b5e20">
	        <th colspan="3">########TOTAL DEPENSE#####</th>
	        <th class="text-right"><?php echo number_format($total_depense,0, "", " "); ?></th>
	        <th></th>
	        <th></th>
        </tr>
 <?php
  }
  ?>
</tbody>
</table>
</div>
<div class="container">
<!--
	####################FIN AFFICHAGE DEPENSE############################
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->
<<?php
if ($total_line == 0)  {
}
else { ?>
<p class="form-control text-center text-light" style="background-color: purple"><b>MT ROYAL =<?php echo Round($total_commande);?> | Depense ROYAL : <?php echo $total_depense;?> | Reste ROYAL=<?php echo floor($total_commande-$total_depense);?>
	    <?php
	    $state_suppr = "pointer-events: none";
	    ?></b></p>
<div>
<form action="valider_commande.php" method="POST" class="text-light">
<div>
	<label><b>VERSEMENT DANS LE BORDEREAU OU DANS LE CAHIER DE VERSEMENT</b></label>
	<label class="float-right"><b>POINT</b>##########</label>
</div>
<div>
<input class="col-sm-5 btn" style="background: brown" value=<?php 

/*if ($client_name=='Amparafa' OR $client_name=='Bejofo' OR $client_name=='Soalazaina') 
	{$TL_VRST = $sous_total_client-$total_depense_aparafa-$total_depense;echo floor($TL_VRST);}
else
	{$TL_VRST = $total_commande-$total_depense ;echo floor($TL_VRST);}
*/
$TL_VRST = $total_commande-$total_depense ;
echo floor($TL_VRST);
;?> type="number" step="any" oninput="Resolution($(this));">
<input class="col-sm-5 btn" style="background: yellow" name="versement" value=<?php echo Round($total_commande);?> type="hidden">
<select class="float-right" style="background: red" name="point">
		<option value="congratulation">Felicitation</option>
	    <option value="avertissement">Avertissement</option>
</select>
</div>
<div>
<br>
<label><b>TSY AMPY</b></label>
</div>
<div class="text-danger">
<input class="col-sm-5 btn" style="background: cyan" name="resolution" value=0 type="number" step="any" id="Tsy_ampy">
</div>
<div>
<br>
<label><b>VOLA MIHOATRA</b></label>
</div>
<div>
<input class="col-sm-5 btn" style="background: orange" name="mihoatra" value=0 type="number" step="any" id="Mihoatra">
</div>
<div class="form-group">
<br>
<label class="float-left"><b>NOTE</b> Ex:Versement BFV REF:857179 du 23.10.2020</label>
<input class="form-control bg-light" name="note_general" placeholder="note:Compte est bon" type="text">
</div>
<div class="form-group">
<label class="float-left"><b>Chemin</b> Ex: C:\wamp64\www\GS\pj\2020\10 Oct\Vente\Ambato_tantely\22 Alakamisy</label>
<input class="form-control" style="background: #d4e157" name="path" placeholder="Copie-coller l'emplacement du photo ici" type="text">
</div>
<div class="form-group text-center">
<a href="anuler_commande.php" onclick="confirmationDelete('Anuler le commande?');return false; post ;"><button type="button" class="btn btn-danger">Anuler</button></a>
<a style="<?php echo $state_suppr; ?>" id="valider" onclick="confirmationDelete('Valider le commande?');return false; post ;"><button type="submit" class="btn btn-success">Valider</button></a>
<input name="vente_id_array" class="btn btn-light" type="password" oninput="unlock($(this));" placeholder="">
</div>
</form>
<br>
<br>
<br>
</div>
</div>
<?php
    }
	}
	}//End of if MULTI-SESSION
?>
</div>
<!-------------------------------------->
<!-- model form DEPENSE-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_depense" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content btn-brown">
		<div class="modal-header btn-light">
			<h4 class="modal-title">NOUVEAU DEPENSE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_depense.php" method="post">
	<div class="form-group">
		<label id="textzoned3">Montant (Ariary)</label>
		<input class="form-control btn btn-warning" name="depense" value=0 type="number" id="textzoned2">
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

function dFunction() {
  var checkBox = document.getElementById("myCheckd");
  var text = document.getElementById("textzoned");
  var text_pv = document.getElementById("textzoned_pv");
  var checkd_pv = document.getElementById("Checkd_pv");
  var checkd_pv_label = document.getElementById("Checkd_pv_label");
  var pv_label = document.getElementById("pv_label");
  var text2 = document.getElementById("textzoned2");
  var text3 = document.getElementById("textzoned3");
  if (checkBox.checked == true){
    text.style.display = "block";
    text_pv.style.display = "block";
    checkd_pv.style.display = "block";
    checkd_pv_label.style.display = "block";
    pv_label.style.display = "block";
    text2.style.display = "none";
    text3.style.display = "none";
  } else {
     text.style.display = "none";
     text_pv.style.display = "none";
     checkd_pv.style.display = "none";
     checkd_pv_label.style.display = "none";
     pv_label.style.display = "none";
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
	var id_pu2 = "textzone"+j;
	var id_qt = j + '_id_qt';
	var qt = document.getElementById(id_qt).value;
	var pu2 = document.getElementById(id_pu2).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant).text(mt);
    //alert((pu2*qt)-mt);
    $("#"+id_montant+'bc').text((pu2*qt)-mt);
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
	var id_pu2_modif = "textzone2"+j;
	var qt = document.getElementById(id_qt_modif).value;
	var pu2 = document.getElementById(id_pu2_modif).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant_modif).text(mt);
    $("#"+id_montant_modif+'bc').text((pu2*qt)-mt);
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
    $("#"+id_montant+'bc').text(mt2-mt);
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
    $("#"+id_montant_modif+'bc').text(mt2-mt);
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
	var id_montant = x + '_id_montant';
	var id_qt = x + '_id_qt';
	var id_pu = x + '_id_pu';
	//var id_pua = 'textzone'+j;
	var qt = document.getElementById(id_qt).value;
	var pu2 = document.getElementById(id_pu).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant3).text(mt);
    $("#"+id_montant+'bc').text(mt-(pu2*qt));
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
	var id_montant_modif_bc = x + '_id_montant_modifbc';
	var id_pu_modif = x + '_id_pu_modif';
	//var id_pua = 'textzone'+j;
	var qt = document.getElementById(id_qt_modif).value;
	var pu2 = document.getElementById(id_pu_modif).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant3_modif).text(mt);
    $("#"+id_montant_modif_bc).text(mt-(pu2*qt));
}
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
function unlock2(e){
	var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2020") {
    	$("#start_button").removeAttr('disabled');
	}else{
		$("#start_button").prop('disabled',true);
	}
}
</script>
<script>

function Resolution(e) {
var versement = <?php echo json_encode(Round($TL_VRST)); ?>;
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

<script>
	function Check_date(e) {

	var daty =  e.val();
	var shop = document.getElementById('shop_id').value;

	if (shop == 'Ambato_Tantely') {
		var next_day = <?php echo json_encode($latest_date_v_tantely); ?>;
		var day = <?php echo json_encode($day_tantely); ?>;
	}
	if (shop == 'Amparafa') {
		var next_day = <?php echo json_encode($latest_date_v_amparafa); ?>;
		var day = <?php echo json_encode($day_amparafa); ?>;
	}
	if (shop == 'Bejofo') {
		var next_day = <?php echo json_encode($latest_date_v_bejofo); ?>;
		var day = <?php echo json_encode($day_bejofo); ?>;
	}
	if (shop == 'Ambaibo_Tole') {
		var next_day = <?php echo json_encode($latest_date_v_ambaibo_tole); ?>;
		var day = <?php echo json_encode($day_tole); ?>;
	}
	if (shop == 'Ambato_veve_photo') {
		var next_day = <?php echo json_encode($latest_date_v_Ambato_veve_photo); ?>;
		var day = <?php echo json_encode($day_veve); ?>;
	}

	if (shop == 'Soalazaina') {
		var next_day = <?php echo json_encode($latest_date_v_soalazaina); ?>;
		var day = <?php echo json_encode($day_soalazaina); ?>;
	}

	if (shop == 'Andrefana') {
		var next_day = <?php echo json_encode($latest_date_v_andrefana); ?>;
		var day = <?php echo json_encode($day_andrefana); ?>;
	}
	if (shop == 'Morarano') {
		var next_day = <?php echo json_encode($latest_date_v_morarano); ?>;
		var day = <?php echo json_encode($day_morarano); ?>;
	}
	if (shop == 'Ambaibo_Electronique') {
		var next_day = <?php echo json_encode($latest_date_v_electronique); ?>;
		var day = <?php echo json_encode($day_electronique); ?>;
	}

	
	//alert(next_day);
	//alert(day);
	if ((daty == next_day)) {
		$("#start_button").removeAttr('disabled');
		document.getElementById('date_aff2').style.color = 'green';
	}
	else{
		//alert('INACCEPTABLE');
		$("#start_button").prop('disabled',true);
		document.getElementById('date_aff2').style.color = 'red';
		//alert('different');

	}
}
</script>
<script>
	function Check_shop(e) {
		var shop =  e.val();
		//alert(shop);
		if (shop == 'Morarano') {
			var next_day = <?php echo json_encode($latest_date_v_morarano_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_morarano_c); ?>;
			var day = <?php echo json_encode($day_morarano); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Ambaibo_Electronique') {
			var next_day = <?php echo json_encode($latest_date_v_electronique_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_electronique_c); ?>;
			var day = <?php echo json_encode($day_electronique); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Soalazaina') {
			var next_day = <?php echo json_encode($latest_date_v_soalazaina_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_soalazaina_c); ?>;
			var day = <?php echo json_encode($day_soalazaina); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Amparafa') {
			var next_day = <?php echo json_encode($latest_date_v_amparafa_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_amparafa_c); ?>;
			var day = <?php echo json_encode($day_amparafa); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//-----------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Ambaibo_Tole') {
			var next_day = <?php echo json_encode($latest_date_v_ambaibo_tole_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_ambaibo_tole_c); ?>;
			var day = <?php echo json_encode($day_tole); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Bejofo') {
			var next_day = <?php echo json_encode($latest_date_v_bejofo_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_bejofo_c); ?>;
			var day = <?php echo json_encode($day_bejofo); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Ambato_veve_photo') {
			var next_day = <?php echo json_encode($latest_date_v_Ambato_veve_photo_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_Ambato_veve_photo_c); ?>;
			var day = <?php echo json_encode($day_veve); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Ambato_Tantely') {
			var next_day = <?php echo json_encode($latest_date_v_tantely_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_tantely_c); ?>;
			var day = <?php echo json_encode($day_tantely); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
		if (shop == 'Andrefana') {
			var next_day = <?php echo json_encode($latest_date_v_andrefana_a); ?>;
			var current_day = <?php echo json_encode($latest_date_v_andrefana_c); ?>;
			var day = <?php echo json_encode($day_andrefana); ?>;
			//------DAY TO MALAGASY--------------
			if (day == 'Monday') {day = 'Latsinainy';}
			if (day == 'Tuesday') {day = 'Talata';}
			if (day == 'Wednesday') {day = 'Alarobia';}
			if (day == 'Thursday') {day = 'Alakamisy';}
			if (day == 'Friday') {day = 'Zoma';}
			if (day == 'Saturday') {day = 'Sabotsy';}
			//--------------------------------------
			document.getElementById('date_aff1').innerHTML = 'Farany: '+current_day;
			document.getElementById('date_aff2').innerHTML = 'Manaraka: '+day+' '+next_day;
		}
	}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>