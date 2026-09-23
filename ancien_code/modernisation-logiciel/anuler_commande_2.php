<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//session_start();
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
echo $username;
//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM vente_calc WHERE user = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();
//header("location: commande.php");

?>