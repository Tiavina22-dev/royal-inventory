<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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
//-------------------------------------------

if (isset($_GET['id_x'])) {
  $id_x = $_GET['id_x'];
}
//CHANGE VALUE OF NOTE PRIX
//-------------------------------------------
$note_prix ='NC';
$query_c = "UPDATE produit
            SET user_x = ? ,note_prix_tantely = ?
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username, $note_prix, $id_x));	

$q->closeCursor();
//show modifed article on search again
$query_c = "SELECT reference_x FROM produit
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x));
	$result = $q -> fetch();
	$result = $result['reference_x'];	

$q->closeCursor();
$key_word = $result;
setcookie("key_word",$key_word, time()+5);
header("location: prix_ambato_1.php");

?>