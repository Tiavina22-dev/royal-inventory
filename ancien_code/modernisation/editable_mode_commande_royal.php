<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
//Get username
session_start();
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
$state = "preparing";
//-----------INSERT INTO commande------------

//-------------------------------------------
$query_c = "UPDATE commande
            SET state = ? 
            WHERE user = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($state,$username));	

$q->closeCursor();
//echo $username;
header("location: commande_royal.php");

?>