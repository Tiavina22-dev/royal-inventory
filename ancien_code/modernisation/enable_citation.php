<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id'])) {
  $id = $_GET['id'];
}

//-----------ALL FLAG TO OFF------------
	$query_c = "UPDATE history 
            SET flag = 'OFF'
            WHERE type = 'citation' ";

  	$q = $bdd->prepare($query_c);

  	$q->execute(array());  

	$q->closeCursor();
//-------------FLAG ON FOR THIS ID ONLY-----------------------
	$query_c = "UPDATE history 
            SET flag = 'ON'
            WHERE history_id = ? ";

  	$q = $bdd->prepare($query_c);

  	$q->execute(array($id));  

$q->closeCursor();
header("location: citation_add.php");

?>