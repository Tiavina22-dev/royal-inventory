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
//-------------------------------------------
$id_rectification = 0;
if (isset($_GET['id_rectification'])) {
  $id_rectification = $_GET['id_rectification'];
}
//-------------------------------------------
//INSERT rectification stock TO THE mvt table
$query_get_all_commande = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? AND id_stock_prep = ?;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);

$query_get_all_commande->execute(array($username , $id_rectification));
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	
	//$no = $no + 1;
	$no = $donnees['id_stock_prep'];
	$type_de_mvt = "stock";
	$id_x = $donnees['id_x'];
	$qt = $donnees['qt'];
	$nom_du_client = $donnees['nom_du_client'];
	$description_date = $donnees['description_date'];
	$numero_commande_stock = $donnees['numero_stock_prep'];
	$ref_stock = $donnees['numero_stock_prep']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente']+0;
	$note = $donnees['note'];
	$user_stock_prep = $donnees['user_stock_prep'];
	//--------------------------------------------------------------------------------
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
	//VERIFY CHANGEMENT DE PRIX
	$verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
	$verification_prix = $bdd->prepare($verification_prix);
	$verification_prix->execute(array($id_x));
	//-----Calcule difference de prix------
	$data = $verification_prix -> fetch();
	$prix_avant = $data['prix_de_vente']+0;
	$note_prix = $data['note_prix'];
	$difference_prix = $prix_unitaire - $prix_avant;
	//################################################## 
	//GET QT VENTE || QUERY to sum each point de vente
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
    $qsm = $bdd->prepare($query);
	$qsm->execute(array($id_x,$nom_du_client));
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
		if ($date1_en < $description_date_english) {

			$qt_vente = $data1['qt']+$qt_vente;
        }
    	
    }
    $qsm->closeCursor();
    //QUERY to sum stock
    $query = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
    $qsm = $bdd->prepare($query);
    $qsm->execute(array($id_x,$nom_du_client));
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
		if ($date1_en < $description_date_english) {

			$qt_stock = $data1['qt']+$qt_stock;
        }
    	
    }
    $qsm->closeCursor();
    //RESTE STOCK
    $stock_actu = $qt_stock + $qt_vente;
    $qt_rectificative = $qt - $stock_actu;
    //################################################## 

	if ($difference_prix != 0) {
		$note_prix = "NEW";
	}else{
		$difference_prix = $data['difference_prix'];
	}
	$verification_prix ->closeCursor();
	//-------------------------------------

	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt_rectificative, $prix_unitaire,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$user_stock_prep));	

	$q->closeCursor();
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
	
//-----------DELETE PREPARATION------------
$query_c = "DELETE FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? AND id_stock_prep = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username , $id_rectification));	

$q->closeCursor();
//------------------------------------
//set message on notification champ
$msg_validation = 'Rectification du Stock de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande_stock, time()+5);
//back to commande page
header("location: controle_x.php");
?>