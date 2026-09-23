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
$debut_date = "";
$fin_date = "";
$point_de_vente = "";

if (isset($_POST['point_de_vente']))
{
	$point_de_vente = $_POST['point_de_vente'];
}
if (isset($_POST['debut_date']))
{
	$debut_date = $_POST['debut_date'];

}

if (isset($_POST['fin_date']))
{
  $fin_date = $_POST['fin_date'];

}

//Set cookies to appear imediately preparation page
if (strlen($debut_date)>1 AND strlen($fin_date)>1 AND strlen($point_de_vente)>1)
{
setcookie("point_de_vente_cookie",($point_de_vente), time()+5);
setcookie("debut_date",($debut_date), time()+5);
setcookie("fin_date",($fin_date), time()+5);
}
header("location: analyse_vente.php");

?>