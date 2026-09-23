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

$long_name = "";
if (isset($_POST['long_name'])) {
  $long_name = $_POST['long_name'];
}

$shop_id = "" ;
if (isset($_POST['shop_id'])) {
  $shop_id = $_POST['shop_id'];
}
//-------------------------------------------
$query_c = "UPDATE shop 
            SET long_name = ? ,shop_user = ? 
            WHERE shop_id = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($long_name, $username, $shop_id));  

$q->closeCursor();
//echo ("Depense=".$depense_royal." depense_aparafa=".$depense_aparafa." motif=".$motif." dusername=".$username." id_depense=".$id_depense);
header("location: add_shop.php");

?>