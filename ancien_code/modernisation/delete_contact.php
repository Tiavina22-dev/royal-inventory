<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
$id_contact = 0;
if (isset($_GET['id_contact'])) {
  $id_contact = $_GET['id_contact'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM contact
            WHERE id_contact = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_contact));	

$q->closeCursor();
header("location: contact.php");

?>