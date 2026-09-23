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

$history_details = "";

if (isset($_POST['history_details'])) {
	$history_details = $_POST['history_details'];
}

$type = 'update';

$query_c = "INSERT INTO history(details, type,responsable) VALUES (?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($history_details, $type, $username));
  $q->closeCursor();
header("location: about.php");

?>