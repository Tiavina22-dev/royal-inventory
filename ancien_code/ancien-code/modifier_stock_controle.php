<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------


$prix_client = 0;
if (isset($_POST['prix_client'])) {
	$prix_client = $_POST['prix_client'];
}

if (isset($_POST['qt_reelle'])) {
	$qt = $_POST['qt_reelle'];
}

if (isset($_POST['note_stock'])) {
	$note_stock = $_POST['note_stock'];
}

if (isset($_POST['id_stock_prep'])) {
  $id_stock_prep = $_POST['id_stock_prep'];
}

if (isset($_POST['prix_de_vente'])) {
  $prix_de_vente = $_POST['prix_de_vente'];
}

//-----------INSERT INTO produit------------
//-------------------------------------------
$query_c = "UPDATE stock_prep
            SET qt = ?, note = ?, prix_de_vente = ?, prix_client = ?
            WHERE id_stock_prep = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($qt, $note_stock, $prix_de_vente,$prix_client,$id_stock_prep));	

$q->closeCursor();

header("location: controle_x.php");

?>