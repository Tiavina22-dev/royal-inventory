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

  $details = '';
  if (isset($_POST['details'])) 
  {  $details = $_POST['details'];}

   $id = '';
  if (isset($_POST['history_id'])) 
  {  $history_id = $_POST['history_id'];}
//-------------------------------------------
$query_c = "UPDATE history 
            SET details = ?, responsable = ?
            WHERE history_id = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($details, $username,$history_id,));  

$q->closeCursor();
header("location: urgent.php");
?>