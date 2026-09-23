<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//Get username
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
//-------------------------------------------
$prix_de_vente = 0;
$pu_aparafa = 0;
$prix_fournisseur = 0;
$benefice = 0;

$note_x = "";
if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente']+0;
}

if (isset($_POST['pu_aparafa'])) {
	$pu_aparafa = $_POST['pu_aparafa']+0;
}

if (isset($_POST['pu_tantely'])) {
	$pu_tantely = $_POST['pu_tantely']+0;
}

if (isset($_POST['prix_fournisseur'])) {
	$prix_fournisseur = $_POST['prix_fournisseur']+0;
}
if (isset($_POST['benefice'])) {
	$benefice = $_POST['benefice']+0;
}
if (isset($_POST['id_x'])) {
	$id_x = $_POST['id_x'];
}
if (isset($_POST['note_x'])) {
  $note_x = $_POST['note_x'];
}

//-----------INSERT INTO produit------------
/*
echo "PU".$prix_de_vente;
echo "PF".$prix_fournisseur;
echo "BN".$benefice;
echo "NT".$note_x;
*/
//VERIFY CHANGEMENT DE PRIX
	$verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
	$verification_prix = $bdd->prepare($verification_prix);
	$verification_prix->execute(array($id_x));
	$data = $verification_prix -> fetch();
	//-----Calcule difference de prix Global------
	$prix_avant_global = $data['prix_de_vente']+0;
	$note_prix = $data['note_prix']."";
	$current_note_x = $data['note_x']."";
	$difference_prix_global = $prix_de_vente - $prix_avant_global;
	$difference_prix_fournisseur = $prix_fournisseur - ($data['prix_fournisseur']+0);

	$change_prix = "";

	//Save note change
	if ($current_note_x != $note_x) { $change_prix = "YES";}
	if ($difference_prix_fournisseur != 0) { $change_prix = "YES";}

	if ($difference_prix_global != 0) {
		$note_prix = "NEW";
		$change_prix = "YES";
		//To handle empty note field
		if (strlen($note_x) == 0) {
			$note_x = "latest";
		}
	}else{
		$difference_prix_global = $data['difference_prix'];
	}
	//-----Calcule difference de prix Tantely------
	$prix_avant_tentely = $data['pu_ambato_tantely']+0;
	$note_prix_tantely = $data['note_prix_tantely'];
	$difference_prix_tantely = $pu_tantely - $prix_avant_tentely;
	
	if ($difference_prix_tantely != 0) {
		$note_prix_tantely = "NEW";
		$change_prix = "YES";
		//To handle empty note field
		if (strlen($note_x) == 0) {
			$note_x = "latest";
		}
	}else{
		$difference_prix_tantely = $data['difference_prix_tantely'];
	}

	//-----Calcule difference de prix Amparafa------
	$prix_avant_amparafa = $data['pu_aparafa']+0;
	$note_prix_amparafa = $data['note_prix_amparafa'];
	$difference_prix_amparafa = $pu_aparafa - $prix_avant_amparafa;
	
	if ($difference_prix_amparafa != 0) {
		$note_prix_amparafa = "NEW";
		$change_prix = "YES";
		//To handle empty note field
		if (strlen($note_x) == 0) {
			$note_x = "latest";
		}
	}else{
		$difference_prix_amparafa = $data['difference_prix_amparafa'];
	}

	$verification_prix ->closeCursor();
	//-------------------------------------
//-------------------------------------------
	//STOP QUERY if no change in prix field
	if ($change_prix == "YES") {
		
		$query_c = "UPDATE produit
		            SET prix_de_vente = ?,pu_aparafa = ?,pu_ambato_tantely = ?,prix_fournisseur = ?, benefice = ?, note_x = ?, user_x = ? ,note_prix = ?, note_prix_tantely = ?, note_prix_amparafa = ?,difference_prix = ?, difference_prix_tantely = ?, difference_prix_amparafa = ?
		            WHERE id_x = ?";

			$q = $bdd->prepare($query_c);

			$q->execute(array($prix_de_vente,$pu_aparafa,$pu_tantely, $prix_fournisseur, $benefice,$note_x,$username, $note_prix,$note_prix_tantely,$note_prix_amparafa, $difference_prix_global,$difference_prix_tantely,$difference_prix_amparafa, $id_x));	

		$q->closeCursor();
		}

//show modifed article on search again
$query_c = "SELECT reference_x FROM produit
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x));
	$result = $q -> fetch();
	$result = $result['reference_x'];	

$q->closeCursor();
$key_word = $result;
setcookie("key_word",$key_word, time()+5);
header("location: prix_ambato_1.php");

?>