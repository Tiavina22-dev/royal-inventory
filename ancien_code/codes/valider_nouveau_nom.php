<?php
include('connect.php');
/*
TABLE colonne
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

if (isset($_GET['id_x'])) {
  $id_x = $_GET['id_x'];
}
//-----------GET CURRENT CHECKING VALUE------------
$query_c = "SELECT * FROM produit
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x));
	$result = $q -> fetch();
	$result = $result['checking'];
	$q->closeCursor();
	$result = $result.' | '.$username;
//-------------------------------------------------
$query_c = "UPDATE produit
            SET checking = ?
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($result,$id_x));	

$q->closeCursor();
//-------------------------------------------
//setcookie("key_word",$key_word, time()+5);
header("location: new_product_notif.php");
?>