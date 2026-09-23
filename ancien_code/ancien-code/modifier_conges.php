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

  $days = '';
  if (isset($_POST['days'])) 
  {  $days = $_POST['days'];}

  $motif ='';
  if (isset($_POST['motif']))
  {$motif = $_POST['motif'];}

   $id = '';
  if (isset($_POST['history_id'])) 
  {  $history_id = $_POST['history_id'];}
//-------------------------------------------
$query_c = "UPDATE history 
            SET flag = ? ,details = ?, responsable = ?
            WHERE history_id = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($days, $motif, $username,$history_id,));  

$q->closeCursor();
header("location: conges.php");
?>