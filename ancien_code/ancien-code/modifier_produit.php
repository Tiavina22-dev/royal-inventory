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

if (isset($_POST['nom_x'])) {
	$nom_x = $_POST['nom_x'];
}

if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}

if (isset($_POST['id_x'])) {
	$id_x = $_POST['id_x'];
}

if (isset($_POST['reference_x'])) {
  $reference_x = $_POST['reference_x'];
}
if (isset($_POST['note_x'])) {
  $note_x = $_POST['note_x'];
}
//-----------INSERT INTO produit------------
//-------------------------------------------
$query_c = "UPDATE produit
            SET nom_x = ?, prix_de_vente = ?, reference_x = ?, note_x = ?, user_x = ?
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($nom_x, $prix_de_vente, $reference_x,$note_x,$username,$id_x));	

$q->closeCursor();
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
header("location: stock_general.php");

?>