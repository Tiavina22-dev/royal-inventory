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


if (isset($_POST['action'])) {
	//Personnel name
	$action = $_POST['action'];

	if (isset($_POST['montant'])) {
	$montant = $_POST['montant'];
	$motif ='';
	if (isset($_POST['motif']))
	{$motif = $_POST['motif'];}
	$Status_tsinjo = 'actif';
	$query_c = "INSERT INTO tsinjo(type_tsinjo, montant_tsinjo, motif_tsinjo, Responsable,Status_tsinjo) VALUES (?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($action,$montant, $motif, $username,$Status_tsinjo));
  	$q->closeCursor();

}

}



header("location: tsinjo.php");

?>