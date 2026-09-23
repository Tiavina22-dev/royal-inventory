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

$name = "";
if (isset($_POST['name'])) {
  $name = $_POST['name'];
}

$mobil = 0 ;
if (isset($_POST['mobil'])) {
  $mobil = $_POST['mobil'];
}
$mail = "" ;
if (isset($_POST['mail'])) {
  $mail = $_POST['mail'];
}

$id_contact = "" ;
if (isset($_POST['id_contact'])) {
  $id_contact = $_POST['id_contact'];
}
//-------------------------------------------
$query_c = "UPDATE contact 
            SET name = ? ,mobil = ?, mail = ?, modified_by = ? 
            WHERE id_contact = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($name,$mobil, $mail, $username, $id_contact));  

$q->closeCursor();
//echo ("Depense=".$depense_royal." depense_aparafa=".$depense_aparafa." motif=".$motif." dusername=".$username." id_depense=".$id_depense);
header("location: contact.php");

?>