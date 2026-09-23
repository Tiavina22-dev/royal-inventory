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

//INSERT ALL COMMANDE TO THE mvt table
$query_get_all_commande = "SELECT * FROM stock_prep_calc WHERE description_date LIKE '%Entana%' AND user_stock_prep = ? ;";
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
	$numero_commande_stock = $donnees['numero_stock_prep'];
	$ref_stock = $donnees['numero_stock_prep']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente']+0;
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
	if ($nom_du_client =='Amparafa') {
		$prix_avant = $data['pu_aparafa']+0;
	}
	if (($nom_du_client !='Amparafa') AND ($nom_du_client !='Ambato_Tantely')) {
		$prix_avant = $data['prix_de_vente']+0;
	}

	if ($nom_du_client =='Ambato_Tantely') {
		$note_prix = $data['note_prix_tantely'];
	}
	if ($nom_du_client =='Amparafa') {
		$note_prix = $data['note_prix_amparafa'];
	}
	if (($nom_du_client !='Amparafa') AND ($nom_du_client !='Ambato_Tantely')) {
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
	$query_c = "INSERT INTO mvt_calc(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$user_stock_prep));	

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
$query_c = "DELETE FROM stock_prep_calc WHERE description_date LIKE '%Entana%' AND user_stock_prep = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();
//------------------------------------
//set message on notification champ
$msg_validation = 'Stock de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande_stock, time()+5);
//back to commande page
header("location: stock_andrefana_retours.php");

?>