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

//INSERT ALL STOCK PREP TO THE mvt table
$query_get_all_commande = "SELECT * FROM stock_prep WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);

$query_get_all_commande->execute(array($username));
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	
	$no = $no + 1;
	$type_de_mvt = "stock";
	$id_x = $donnees['id_x'];
	$qt = $donnees['qt'];
	$nom_du_client = $donnees['nom_du_client'];
	$description_date = $donnees['description_date'];
	//Extract date in Mysql format
		$date1_mysql = null;
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date, $res_regex);
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
	$prix_client = $donnees['prix_client']+0;
	$note = $donnees['note'];
	$user_stock_prep = $donnees['user_stock_prep'];

	//VERIFY CHANGEMENT DE PRIX
	$verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
	$verification_prix = $bdd->prepare($verification_prix);
	$verification_prix->execute(array($id_x));
	//-----Calcule difference de prix------
	$data = $verification_prix -> fetch();
	if ($nom_du_client =='Ambato_Tantely') {
		$prix_avant = $data['pu_ambato_tantely']+0;
	}

	if ($nom_du_client =='Soalazaina') {
		$prix_avant = $data['pu_soalazaina']+0;
	}

	if ($nom_du_client =='Amparafa') {
		$prix_avant = $data['pu_aparafa']+0;
	}
	if (($nom_du_client !='Amparafa') AND ($nom_du_client !='Ambato_Tantely') AND ($nom_du_client !='Soalazaina')) {
		$prix_avant = $data['prix_de_vente']+0;
	}

	if ($nom_du_client =='Ambato_Tantely') {
		$note_prix = $data['note_prix_tantely'];
	}
	if ($nom_du_client =='Amparafa') {
		$note_prix = $data['note_prix_amparafa'];
	}

	if ($nom_du_client =='Soalazaina') {
		$note_prix = $data['note_prix_soalazaina'];
	}

	if (($nom_du_client !='Amparafa') AND ($nom_du_client !='Ambato_Tantely') AND ($nom_du_client !='Soalazaina')) {
		$note_prix = $data['note_prix'];
	}
	
	$difference_prix = $prix_unitaire - $prix_avant;
	
	if ($difference_prix != 0) {
		$note_prix = "NEW";
	}else{
		$difference_prix = $data['difference_prix'];
	}
	$verification_prix ->closeCursor();
	//-------------------------------------

	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,Date_du_Journal_mvt,prix_client) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$user_stock_prep,$date1_mysql,$prix_client));	

	$q->closeCursor();
	//Change official prix
	if ($nom_du_client =='Ambato_Tantely') {
		$note = "latest";
		$query_c = "UPDATE produit SET pu_ambato_tantely = ?, note_x = ?, user_x = ? ,note_prix_tantely = ?, difference_prix_tantely = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($prix_unitaire,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
	}
	else
	{
	if ($nom_du_client =='Amparafa') {
		$note = "latest";
		$query_c = "UPDATE produit SET pu_aparafa = ?, note_x = ?, user_x = ?,note_prix_amparafa = ?, difference_prix_amparafa = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($prix_unitaire,$note,$user_stock_prep, $note_prix, $difference_prix,$id_x));
	} else {
		if ($nom_du_client =='Soalazaina') {
		$note = "latest";
		$query_c = "UPDATE produit SET pu_soalazaina = ?, note_x = ?, user_x = ?,note_prix_soalazaina = ?, difference_prix_soalazaina = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($prix_unitaire,$note,$user_stock_prep, $note_prix, $difference_prix,$id_x));
		}
		else
		{
		$note = "latest";
		$query_c = "UPDATE produit SET prix_de_vente = ?, note_x = ?, user_x = ? ,note_prix = ?, difference_prix = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($prix_unitaire,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
		}
	}
	}
	$q->closeCursor();
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
	
//-----------DELETE COMMANDE------------
$query_c = "DELETE FROM stock_prep WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();
//------------------------------------
//set message on notification champ
$msg_validation = 'Stock de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande_stock, time()+5);
//back to commande page
header("location: stock.php");

?>