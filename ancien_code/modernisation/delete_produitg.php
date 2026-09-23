<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id_x'])) {
  $id_x = $_GET['id_x'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM produit
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x));	

$q->closeCursor();
header("location: reference_generator.php");

?>