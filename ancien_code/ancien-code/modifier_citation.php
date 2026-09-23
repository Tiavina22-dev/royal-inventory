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

$command = "";
if (isset($_POST['command'])) {
  $command = $_POST['command'];
  //Balise color
  $command = str_replace('@danger', "<span class = 'text-danger'>", $command);
  $command = str_replace('@warning', "<span class = 'text-warning'>", $command);
  $command = str_replace('@info', "<span class = 'text-info'>", $command);
  $command = str_replace('@white', "<span class = 'text-white'>", $command);
  $command = str_replace('@primary', "<span class = 'text-primary'>", $command);
  $command = str_replace('@success', "<span class = 'text-success'>", $command);
  $command = str_replace('@secondary', "<span class = 'text-secondary'>", $command);
  $command = str_replace('@close', "</span>", $command);
}

$details = "" ;
if (isset($_POST['details'])) {
  $details = $_POST['details'];
  //Balise color
  $details = str_replace('@danger', "<span class = 'text-danger'>", $details);
  $details = str_replace('@warning', "<span class = 'text-warning'>", $details);
  $details = str_replace('@info', "<span class = 'text-info'>", $details);
  $details = str_replace('@white', "<span class = 'text-white'>", $details);
  $details = str_replace('@success', "<span class = 'text-success'>", $details);
  $details = str_replace('@primary', "<span class = 'text-primary'>", $details);
  $details = str_replace('@secondary', "<span class = 'text-secondary'>", $details);
  $details = str_replace('@close', "</span>", $details);
}

$id = "" ;
if (isset($_POST['id'])) {
  $id = $_POST['id'];
}
//-------------------------------------------
$query_c = "UPDATE history 
            SET before_change = ? ,details = ?
            WHERE history_id = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($command,$details,$id));  

$q->closeCursor();
//echo ("Depense=".$depense_royal." depense_aparafa=".$depense_aparafa." motif=".$motif." dusername=".$username." id_depense=".$id_depense);
header("location: citation_add.php");

?>