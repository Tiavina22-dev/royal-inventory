<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id_stock_prep'])) {
  $id_stock_prep = $_GET['id_stock_prep'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM stock_prep
            WHERE id_stock_prep = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_stock_prep));	

$q->closeCursor();
header("location: stock.php");

?>