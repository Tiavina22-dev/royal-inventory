<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
$prix_de_vente = 0;
$qt = 0;
$id_commande = 0;
$note_commande = "";
if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}
$prix_aparafa = 0;
if (isset($_POST['prix_fournisseur'])) {
	$prix_aparafa = $_POST['prix_fournisseur'];
}
if (isset($_POST['qt'])) {
	$qt = $_POST['qt'];
}
if (isset($_POST['id_commande'])) {
  $id_commande = $_POST['id_commande'];
}
if (isset($_POST['note_commande'])) {
	$note_commande = $_POST['note_commande'];
}
//-----------INSERT INTO produit------------
//-------------------------------------------
$query_c = "UPDATE commande
            SET qt = ?, prix_de_vente = ?, prix_aparafa = ?, note_commande = ?
            WHERE id_commande = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($qt, $prix_de_vente,$prix_aparafa, $note_commande, $id_commande));	

$q->closeCursor();

header("location: commande_ambato_1.php");

?>