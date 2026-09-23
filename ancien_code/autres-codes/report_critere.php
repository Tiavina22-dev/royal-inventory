<?php
include('connect.php');
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
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

$point_de_vente = "";

if (isset($_POST['point_de_vente']))
{
	$point_de_vente = $_POST['point_de_vente'];
}

//Set cookies to appear imediately preparation page
if (strlen($point_de_vente)>1)
{
setcookie("point_de_vente",($point_de_vente), time()+5);
}
header("location: report_2.php");

?>