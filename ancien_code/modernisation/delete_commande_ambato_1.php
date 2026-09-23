<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id_commande'])) {
  $id_commande = $_GET['id_commande'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM commande
            WHERE id_commande = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_commande));	

$q->closeCursor();
header("location: commande_ambato_1.php");

?>