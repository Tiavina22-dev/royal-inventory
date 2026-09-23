<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//--------------------------------------
if (isset($_GET['from_id'])) {
  $from_id = $_GET['from_id'];
}
if (isset($_GET['Current_Id_User'])) {
  $Current_Id_User = $_GET['Current_Id_User'];
}
//-----------DELETE COMMANDE------------
//---------------------------------------
$query_c = "UPDATE chat 
			SET status = 'vu'
            WHERE from_id = ? AND to_id = ? AND status = 'new'";

	$q = $bdd->prepare($query_c);

	$q->execute(array($from_id,$Current_Id_User));	

$q->closeCursor();
header("location: chat.php");

?>