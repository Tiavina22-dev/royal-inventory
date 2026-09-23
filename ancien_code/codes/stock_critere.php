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
if (isset($_POST['zero']))
{
	$critaire = $_POST['zero'];
	if ($critaire == 'vente_zero' ) {
		$description = "Produit Sans Vente Seulement (Vente = 0)";
	}
	if ($critaire == 'stock_zero') {
		$description = "Produit Sans Stock Seulement (Stock = 0)";
	}
	if ($critaire == 'Stock_Vente') {
		$description = "Produit avec Stock et Vente Seulement";
	}
	if ($critaire == 'All') {
		$description = "Tous les Vente ou Stock";
	}

}

//Set cookies to appear imediately preparation page
if (strlen($point_de_vente)>1 AND strlen($critaire)>1)
{
setcookie("point_de_vente_cookie",($point_de_vente), time()+5);
setcookie("critaire",($critaire), time()+5);
setcookie("description",($description), time()+5);
}
header("location: analyse_stock.php");

?>