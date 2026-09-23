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
	$history_details = str_replace('@danger', "<span class = 'text-danger'>", $history_details);
	$history_details = str_replace('@warning', "<span class = 'text-warning'>", $history_details);
	$history_details = str_replace('@info', "<span class = 'text-info'>", $history_details);
	$history_details = str_replace('@white', "<span class = 'text-white'>", $history_details);
	$history_details = str_replace('@primary', "<span class = 'text-primary'>", $history_details);
	$history_details = str_replace('@success', "<span class = 'text-success'>", $history_details);
	$history_details = str_replace('@secondary', "<span class = 'text-secondary'>", $history_details);
	$history_details = str_replace('@close', "</span>", $history_details);
}
if (isset($_POST['command'])) {
	$command = $_POST['command'];
	$command = str_replace('@danger', "<span class = 'text-danger'>", $command);
	$command = str_replace('@warning', "<span class = 'text-warning'>", $command);
	$command = str_replace('@info', "<span class = 'text-info'>", $command);
	$command = str_replace('@white', "<span class = 'text-white'>", $command);
	$command = str_replace('@success', "<span class = 'text-success'>", $command);
	$command = str_replace('@primary', "<span class = 'text-primary'>", $command);
	$command = str_replace('@secondary', "<span class = 'text-secondary'>", $command);
	$command = str_replace('@close', "</span>", $command);
}
$type = 'php';

$query_c = "INSERT INTO history(before_change, details, type, responsable) VALUES (?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($command, $history_details, $type, $username));
  $q->closeCursor();
header("location: php.php");

?>