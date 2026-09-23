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


if (isset($_GET['id_commande'])) {
  $id_commande = $_GET['id_commande'];
}

//-----------INSERT INTO produit------------
//-----------UPDATE------------
  $query_c = "UPDATE commande
            SET state = ? 
            WHERE id_commande = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($state,$id_commande));	

  $q->closeCursor();
  //-------------------------------------------
  //Valider for TRELAHY FOR VERIFICATION
  $username = 'trelahy';
  $query_c = "UPDATE commande
            SET state = ? 
            WHERE  user = ?";

  $q = $bdd->prepare($query_c);

  $q->execute(array($state,$username)); 

  $q->closeCursor();
  //-------------------------------------------
header("location: commande_ambato_1.php");

?>