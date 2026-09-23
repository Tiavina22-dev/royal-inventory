<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------


if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}

if (isset($_POST['qt'])) {
	$qt = $_POST['qt'];
}

if (isset($_POST['note_stock'])) {
	$note_stock = $_POST['note_stock'];
}

if (isset($_POST['id_stock_prep'])) {
  $id_stock_prep = $_POST['id_stock_prep'];
}

//-----------INSERT INTO produit------------
//-------------------------------------------
$query_c = "UPDATE stock_prep_calc
            SET qt = ?, prix_de_vente = ?, note = ?
            WHERE id_stock_prep = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($qt, $prix_de_vente, $note_stock, $id_stock_prep));	

$q->closeCursor();

header("location: stock_andrefana_retours.php");

?>