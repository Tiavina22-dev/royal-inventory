<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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
//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM stock_prep_calc WHERE description_date LIKE '%Entana%' AND user_stock_prep = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();
//NOTIFICATION
$msg_validation = 'Annulation du stock avec succes';
setcookie("msg_validation",$msg_validation, time()+5);

header("location: stock_andrefana_retours.php");

?>