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
$point_de_vente = '';
if (isset($_POST['point_de_vente'])) {
  $point_de_vente = $_POST['point_de_vente'];
}
//CHANGE VALUE OF NOTE PRIX
//-------------------------------------------
$note_prix ='NC';
if ($point_de_vente == 'all') {
	$query_c = "UPDATE produit
            SET user_x = ? ,note_prix = ?,note_prix_amparafa = ?,note_prix_tantely = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username, $note_prix, $note_prix,$note_prix));
	$q->closeCursor();
}
if ($point_de_vente == 'Amparafa') {
	$query_c = "UPDATE produit
            SET user_x = ? ,note_prix_amparafa = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username, $note_prix));
	$q->closeCursor();
}	
if ($point_de_vente == 'General') {
	$query_c = "UPDATE produit
            SET user_x = ? ,note_prix = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username, $note_prix));
	$q->closeCursor();	
}
if ($point_de_vente == 'Ambato_Tantely') {
	$query_c = "UPDATE produit
            SET user_x = ? ,note_prix_tantely = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username, $note_prix));
	$q->closeCursor();
}
if ($point_de_vente == 'Soalazaina') {
	$query_c = "UPDATE produit
            SET user_x = ? ,note_prix_soalazaina = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username, $note_prix));
	$q->closeCursor();
}	

//show modifed article on search again
$point_de_vente = "";
$key_word = "Notification du nouveau prix";
setcookie("key_word",$key_word, time()+5);
setcookie("point_de_vente",$point_de_vente, time()+5);
header("location: prix.php");
?>