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


if (isset($_POST['personnel'])) {
	//Personnel name
	$personnel = $_POST['personnel'];
	if (isset($_POST['action'])) {
	$action = $_POST['action'];
	if (isset($_POST['days'])) {
	$days = $_POST['days'];

	$motif ='';
	if (isset($_POST['motif']))
	{$motif = $_POST['motif'];}

	$type = 'conges';

	$query_c = "INSERT INTO history(before_change, after_change, flag, details, type, responsable) VALUES (?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($personnel, $action,$days, $motif, $type, $username));
  	$q->closeCursor();

}
}

}



header("location: conges.php");

?>