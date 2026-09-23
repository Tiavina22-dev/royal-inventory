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

if (isset($_POST['debut_date']))
{
	$debut_date = $_POST['debut_date'];

}


//Set cookies to appear imediately preparation page
if (strlen($debut_date)>1)
{

setcookie("debut_date",($debut_date), time()+5);
setcookie("fin_date",($debut_date), time()+5);
}
header("location: vente_recap_solde_printed.php");

?>