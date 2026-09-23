<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id_tsinjo'])) {
  $id = $_GET['id_tsinjo'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM tsinjo 
            WHERE id_tsinjo = ?";

$q = $bdd->prepare($query_c);

$q->execute(array($id));	

$q->closeCursor();

header("location: tsinjo.php");

?>