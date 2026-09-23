<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
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

$short_name = "";

if (isset($_POST['short_name'])) {
	$short_name = $_POST['short_name'];
}

$long_name = "";
if (isset($_POST['long_name'])) {
	$long_name = $_POST['long_name'];
}


$query_c = "INSERT INTO shop(long_name, short_name,shop_user) VALUES (?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($long_name, $short_name, $username));
  $q->closeCursor();
header("location: add_shop.php");

?>