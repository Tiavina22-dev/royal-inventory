<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
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

if (isset($_GET['id_x'])) {
  $id_x = $_GET['id_x'];
}

if (isset($_GET['shop'])) {
  $shop = $_GET['shop'];

	//-----------GET CURRENT MAX NO ACTIVITY VALUE------------
	$query_c = "SELECT MAX(numero_commande_stock) as max_num FROM mvt
	            WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";

		$q = $bdd->prepare($query_c);

		$q->execute(array($id_x,$shop));
		$result = $q -> fetch();
		$result = $result['max_num'];
		$q->closeCursor();
	//-------------------------------------------------
	$query_c = "INSERT INTO checking
	            (id_x , shop , responsable , value ,checking_type) VALUES (?, ?, ?, ?,'prix_different')";

		$q = $bdd->prepare($query_c);

		$q->execute(array($id_x,$shop,$username,$result));	

	$q->closeCursor();
	//-------------------------------------------
	//setcookie("key_word",$key_word, time()+5);
	setcookie("point_de_vente",$shop, time()+5);
}
header("location: analyse_ecart_prix.php");
?>