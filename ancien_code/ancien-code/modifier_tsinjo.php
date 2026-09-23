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

  $montant_tsinjo = '';
  if (isset($_POST['montant_tsinjo'])) 
  {  $montant_tsinjo = $_POST['montant_tsinjo'];}

  $motif_tsinjo ='';
  if (isset($_POST['motif_tsinjo']))
  {$motif_tsinjo = $_POST['motif_tsinjo'];}

   $id_tsinjo = '';
  if (isset($_POST['id_tsinjo'])) 
  {  $id_tsinjo = $_POST['id_tsinjo'];}
//-------------------------------------------
$query_c = "UPDATE tsinjo 
            SET montant_tsinjo = ? ,motif_tsinjo = ?, Responsable = ?
            WHERE id_tsinjo = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($montant_tsinjo, $motif_tsinjo, $username,$id_tsinjo));  

$q->closeCursor();
header("location: tsinjo.php");
?>