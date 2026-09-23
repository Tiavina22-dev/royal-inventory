<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
session_start();
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
//GET GATEGORY
$category = 'no';
if (isset($_POST['category'])) {
	$category = $_POST['category'];
}
//POINT DE VENTE
$point_de_vente = '';
if (isset($_POST['point_de_vente'])) {
	$point_de_vente = $_POST['point_de_vente'];
}

$numero_stock = 0;
if (isset($_POST['numero_stock'])) {
	$numero_stock = $_POST['numero_stock'];
}

//DETECT INVENTORY DATE
$inventory_date = '';
if (isset($_POST['inventory_date'])) {
	    //-----------------------------
    //Extract date in Mysql format
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $_POST['inventory_date'], $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '-', $date1);
        $date1 = str_replace('/ ', '-', $date1);
        $date1 = str_replace(' / ', '-', $date1);
        $date1 = str_replace(' -', '-', $date1);
        $date1 = str_replace(' - ', '-', $date1);
        $date1 = str_replace('/', '-', $date1);
        //Convert date to english format for compare
        $inventory_date = DateTime::createFromFormat('y-m-d', $date1);
        	//Buffering
			ob_start();
			
        echo "--------------------------------------------------------------------------------------<br>";
        echo '<b>'.strtoupper($point_de_vente).' | INVENTAIRE GENERAL DU '.$inventory_date -> format('d F Y').'</b><br>';
        echo "--------------------------------------------------------------------------------------<br>";
        $description_date_zero = 'Inventaire/Ajout du '.$inventory_date -> format('d-m-y');
        $description_date_zero = str_replace('-', '/', $description_date_zero);

        $inventory_date = $inventory_date -> format('Y-m-d');

        }
	//---------------------------

}

//Handle All product zero and does not exist in inventory
	#STOCK DOES NOT EXIST
	$query_ps = "SELECT produit.id_x,reference_x,nom_x,qt,type_de_mvt,pu_ambato_tantely FROM (SELECT mvt.id_x as id_x,nom_client_fournisseur,type_de_mvt,mvt.qt,stock_prep.description_date FROM mvt LEFT JOIN (SELECT * FROM stock_prep WHERE nom_du_client = ? AND stock_prep.description_date LIKE '%Rectifier%') as stock_prep ON mvt.id_x = stock_prep.id_x WHERE mvt.nom_client_fournisseur = ? AND stock_prep.id_x IS NULL) as T1 INNER JOIN produit ON T1.id_x = produit.id_x GROUP BY produit.id_x ORDER BY nom_x";
	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array($point_de_vente,$point_de_vente));	
	//get row count
	$total_line=$query_stock_prep_list->rowCount ();

	echo "<table>";
	$line_number = 1;
	$mihoatra = 0;
	$banga = 0;
	$ok = 0;
	while ($donnees = $query_stock_prep_list -> fetch())
	{
		//----------------------------------------------------
	  	//QT DISPO UNTIL INVENTORY DATE
	  	//QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND Date_du_Journal_mvt <= ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$point_de_vente,$inventory_date));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status NOT LIKE 'General_Inventory' AND Date_du_Journal_mvt <= ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$point_de_vente,$inventory_date));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$point_de_vente));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //----------------------
                            $qt_dispo = $vente + $stock + $stock_GI;
                            if ($qt_dispo > 0) {
								$banga = $banga + 1;
                            	$obs = 'Tsy ampy : ';
                            	$color = "style = 'color: red'";
                            }
                            if ($qt_dispo < 0) {
                            	$mihoatra = $mihoatra + 1;
                            	$obs = 'Mihoatra : ';
                            	$color = "style = 'color: green'";
                            }
                            if ($qt_dispo == 0) {
                            	$ok = $ok + 1;
                            	$obs = 'Efa Lany : ';
                            	$color = "";
                            }
    //---------------------
    #GET PRIX
		//GET PRIX
		$verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
		$verification_prix = $bdd->prepare($verification_prix);
		$verification_prix->execute(array($donnees['id_x']));
		//-----Calcule difference de prix------
		$data = $verification_prix -> fetch();
		//GET CURRENT PRICE FOR PV RESPECTIVE
		$current_price = $data['prix_de_vente']+0;
		if ($point_de_vente == 'Ambato_Tantely') {
			$current_price = $data['pu_ambato_tantely']+0;
		}
		if ($point_de_vente == 'Soalazaina') {
			$current_price = $data['pu_soalazaina']+0;
		}
		if ($point_de_vente == 'Amparafa') {
			$current_price = $data['pu_aparafa']+0;
		}
		$verification_prix ->closeCursor();
		#-------------------------------
		 $prix_client = 0;
		echo "<tr ".$color.'><td>'.$line_number.'</td><td>'.$donnees['nom_x'].'--'.$donnees['reference_x'].'--'.$donnees['id_x'].'</td><td>'.$current_price.' Ar</td><td>'.$prix_client.' Ar</td><td>Stock:'.($stock + $stock_GI).'</td><td>Vente:'.$vente.'</td><td>'.$obs.(0-$qt_dispo).'</td><td>By '.$username.'</td><td><b>Auto Detect</b></td></tr>';
		$line_number = $line_number + 1;
	//INSERT DATA OF GI TO MVT
		$type_de_mvt = "stock";
		$qt = 0;
		$qt_rectificative = (0-$qt_dispo);
		$num = 1;
		$ref_stock =$numero_stock.'-'.$num;
		$num = $num+1;
		$note = 'Automatic';
		$status = "General_Inventory";

		//Query to insert one by one in mvt table NB: qt rectificqtive save to prix_aparafa AUTOMATIC
		$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_aparafa, prix_unitaire,prix_client,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,status,Date_du_Journal_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

		$q = $bdd->prepare($query_c);

		$q->execute(array($type_de_mvt, $donnees['id_x'], $qt, $qt_rectificative, $current_price, $prix_client,$point_de_vente,$description_date_zero,$numero_stock,$ref_stock,$note,$username,$status,$inventory_date));	

		$q->closeCursor();


	}
	
	//-------------------------------------------------------------------------------------------

//INSERT rectification stock TO THE mvt table
//$query_get_all_commande = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND nom_du_client = ? AND user_stock_prep = ? ;";
$query_get_all_commande = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND nom_du_client = ?;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);
$query_get_all_commande->execute(array($point_de_vente));
//$query_get_all_commande->execute(array($point_de_vente,$username));
echo "<tr><td></td><td></td><td colspan = '6'>#################  COMPTAGE SUR PLACE  ######################</td></tr>";
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	
	//$no = $no + 1;
	$no = $donnees['id_stock_prep'];
	$type_de_mvt = "stock";
	$id_x = $donnees['id_x'];
	$qt = abs($donnees['qt']);
	$nom_du_client = $point_de_vente;
	//GET Date on a string
  	$description_date =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $description_date = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $description_date = str_replace('- ', '/', $description_date);
        $description_date = str_replace('/ ', '/', $description_date);
        $description_date = str_replace(' / ', '/', $description_date);
        $description_date = str_replace(' -', '/', $description_date);
        $description_date = str_replace(' - ', '/', $description_date);
        $description_date = str_replace('-', '/', $description_date);
        }
    //-----------------------------
    //Extract date in Mysql format
		$date1_mysql = null;
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $donnees['description_date'], $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '-', $date1);
        $date1 = str_replace('/ ', '-', $date1);
        $date1 = str_replace(' / ', '-', $date1);
        $date1 = str_replace(' -', '-', $date1);
        $date1 = str_replace(' - ', '-', $date1);
        $date1 = str_replace('/', '-', $date1);
        
        //echo $date1;
        //Convert date to english format for compare
        $date1_mysql = DateTime::createFromFormat('d-m-y', $date1);
        $date1_mysql = $date1_mysql -> format('Y-m-d');
        }
	//---------------------------
	$numero_commande_stock = $donnees['numero_stock_prep'];
	$ref_stock = $donnees['numero_stock_prep']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente']+0;
	$note = $donnees['note'];
	$user_stock_prep = $donnees['user_stock_prep'];
	//--------------------------------------------------------------------------------
	//-------------------------------------------------------------
	//VERIFY CHANGEMENT DE PRIX
	$prix_client = $donnees['prix_client']+0;
	$current_price = $donnees['prix_de_vente'];
	
		$verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
		$verification_prix = $bdd->prepare($verification_prix);
		$verification_prix->execute(array($id_x));
		//-----Calcule difference de prix------
		$data = $verification_prix -> fetch();
	if ($donnees['prix_de_vente'] == 0) {
		//GET CURRENT PRICE FOR PV RESPECTIVE
		$current_price = $data['prix_de_vente']+0;
		if ($nom_du_client == 'Ambato_Tantely') {
			$current_price = $data['pu_ambato_tantely']+0;
		}
		if ($nom_du_client == 'Soalazaina') {
			$current_price = $data['pu_soalazaina']+0;
		}
		if ($nom_du_client == 'Amparafa') {
			$current_price = $data['pu_aparafa']+0;
		}
	}
	

	$note_prix = $data['note_prix'];
	$difference_prix = 0;
	//################################################## 
    $qt_rectificative = $donnees['qt_rectificative'];//Special general inventory Ambato
    //################################################## 

	if ($difference_prix != 0) {
		$note_prix = "NEW";
	}else{
		$difference_prix = $data['difference_prix'];
	}
	//GET PRODUCT NAME AND REFERENCE
	$product_name = $data['nom_x'];
	$reference_x = $data['reference_x'];
	$verification_prix ->closeCursor();
	#---------------------------------
		if ($qt_rectificative > 0) {
									$mihoatra = $mihoatra + 1;
	                            	$obs = 'Mihoatra : ';
	                            	$color = "style = 'color: green'";
	                            }
	    if ($qt_rectificative < 0) {
	    							$banga = $banga + 1;
	                            	$obs = 'Tsy ampy : ';
	                            	$color = "style = 'color: red'";
	                            }
	    if ($qt_rectificative == 0) {
	    							$ok = $ok + 1;
	                            	$obs = 'OK : ';
	                            	$color = "";
	                            }
	//-------------------------------------
	$status = "General_Inventory";
	$file_name ='pj/INVENTORY_LOG/'.strtoupper($point_de_vente).'_INVENTAIRE_'.str_replace('/', '-',$description_date).'_Saved_on_'.date('Y-m-d_H-i-s').'.html';
	$description_date = 'Inventaire/Ajout du '.$description_date;
	//LOG
	echo "<tr ".$color.'><td>'.$line_number.'</td><td>'.$product_name.'--'.$reference_x.'--'.$id_x.'</td><td>'.$current_price.' Ar</td><td>'.$prix_client.' Ar</td><td>Reste Estimé:'.($qt - $qt_rectificative).'</td><td>Reste Reel:'.$qt.'</td><td>'.$obs.($qt_rectificative).'</td><td>By '.$user_stock_prep.'</td><td><b>Comptage Sur Place</b></td></tr>';
	$line_number = $line_number + 1;
	/*
	echo "<br>";
	echo $current_price;
	echo "<br>";
	echo $nom_du_client;
	echo "<br>";
	echo $description_date;
	echo "<br>";
	echo $note_prix;
	echo "<br>";
	echo $difference_prix;
	echo "<br>";

	*/
	/*****************************************************************/
	//Query to insert one by one in mvt table NB: qt rectificqtive save to prix_aparafa
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_aparafa, prix_unitaire,prix_client,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,status,Date_du_Journal_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $qt_rectificative, $current_price,$prix_client,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$user_stock_prep,$status,$date1_mysql));	

	$q->closeCursor();
	//Change official prix
	if ($nom_du_client =='Ambato_Tantely')
	{
		$note = "latest";
		$query_c = "UPDATE produit SET pu_ambato_tantely = ?, note_x = ?, user_x = ? ,note_prix_tantely = ?, difference_prix_tantely = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($current_price,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
	}
	if ($nom_du_client =='Amparafa')
	{
		$note = "latest";
		$query_c = "UPDATE produit SET pu_aparafa = ?, note_x = ?, user_x = ? ,note_prix_amparafa = ?, difference_prix_amparafa = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($current_price,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
	}
	if ($nom_du_client =='Soalazaina')
	{
		$note = "latest";
		$query_c = "UPDATE produit SET pu_soalazaina = ?, note_x = ?, user_x = ? ,note_prix_soalazaina = ?, difference_prix_soalazaina = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($current_price,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
	}
	if ($nom_du_client =='Bejofo' || $nom_du_client =='Ambato_veve_photo' || $nom_du_client =='Ambaibo_Electronique'|| $nom_du_client =='Ambaibo_Tole')
	{
		$note = "latest";
		$query_c = "UPDATE produit SET prix_de_vente = ?, note_x = ?, user_x = ? ,note_prix = ?, difference_prix = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($current_price,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
	}

	//Change official prix
	//echo $qt_rectificative;
	//echo "<br>";
	/*
	echo $note_prix;
	echo "<br>";
	echo $difference_prix;
	echo "<br>";
	echo "<br>";
	echo $user_stock_prep;
	echo "<br>";
	echo $id_x;
	*/
	
}
echo "</table>";
echo "<br><br><table><tr style = 'font-weight: bold'><td>Total Article </td><td>-------------------</td><td>".($line_number - 1)."</td><td>-------------------</td><td>100%</td></tr><tr style = 'font-weight: bold'><td>Total OK</td><td>-------------------</td><td>".$ok.'</td><td>-------------------</td><td>'.ceil($ok*100/($line_number - 1))."%</td></tr><tr style = 'color: red ; font-weight: bold'><td>Total Banga</td><td>-------------------</td><td>".$banga.'</td><td>-------------------</td><td>'.ceil($banga*100/($line_number - 1))."%</td></tr><tr style = 'color: green ; font-weight: bold'><td>Total Mihoatra</td><td>-------------------</td><td>".$mihoatra.'</td><td>-------------------</td><td>'.ceil($mihoatra*100/($line_number - 1)).'%</td></tr></table><br><br>';
echo " <br><br>-----> CLEANING OF INVENTORY TABLE (stock_prep)........";


//-----------DELETE PREPARATION------------
//$query_c = "DELETE FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND nom_du_client = ? AND user_stock_prep = ? ";
	$query_c = "DELETE FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND nom_du_client = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($nom_du_client));	

$q->closeCursor();
//------------------------------------

echo "<br><br> -----> DISABLE ALL PREVIOUS STOCK AND VENTE ........<br><br>";

//--------Change All Previous MVT to inactif---------
	$query_c = "UPDATE mvt SET status = 'OFF'  WHERE nom_client_fournisseur = ? AND status != 'General_Inventory' AND Date_du_Journal_mvt <= ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($nom_du_client,$inventory_date));	

	$q->closeCursor();
//-----------------------------------------

//set message on notification champ
$msg_validation = 'Rectification du Stock de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande_stock, time()+5);
//back to commande page
echo "<br><br> -----> DONE ........<br><br>";

file_put_contents($file_name, ob_get_contents())
?>

<a href="controle_x.php"><button>CLOSE</button></a>
<br><br>