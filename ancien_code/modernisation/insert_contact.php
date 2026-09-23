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

$name = "Default";

if (isset($_POST['name'])) {
	$name = $_POST['name'];
}

$mobil = "000000000";
if (isset($_POST['mobil'])) {
	$mobil = $_POST['mobil'];
}

$mail = "waiting...";
if (isset($_POST['mail'])) {
  $mail = $_POST['mail'];
}


$query_c = "INSERT INTO contact(name, mobil,mail, modified_by) VALUES (?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($name, $mobil,$mail, $username));
  $q->closeCursor();
header("location: contact.php");

?>