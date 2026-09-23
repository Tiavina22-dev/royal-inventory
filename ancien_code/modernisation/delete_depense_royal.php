<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id_depense'])) {
  $id_depense = $_GET['id_depense'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM depense
            WHERE id_depense = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_depense));	

$q->closeCursor();
header("location: commande_royal.php");

?>