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
//GET GENERAL_NOTE
$note_general = "";
if (isset($_POST['current_note'])) {
	$note_general = $_POST['current_note'];
}

$id = "";
if (isset($_POST['id'])) {
	$id = $_POST['id'];
}


$resolution = 0;

$mihoatra = 0;
if (isset($_POST['additional_note'])) {
	$note_general = $note_general.' | Vola Tsy ampy '. $_POST['additional_note'];
}

	//Query to Change note_general and directory of recap_vente
	$query_c = "UPDATE recap_vente
            SET note_general = ?,responsable = ?,resolution =?
            WHERE id = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($note_general, $username,$resolution, $id));	

	$q->closeCursor();

//echo "note_general = ".$note_general."<br> path = ".$path."<br> benefice_aparafa = ".$benefice_aparafa."<br> resolution = ".$resolution."<br> mihoatra = ".$mihoatra."<br> royal_versement = ".$royal_versement."<br> no_activite = ".$no_activite;
//cookies to back show details again
//back to commande page
header("location: vente_recap.php");

?>