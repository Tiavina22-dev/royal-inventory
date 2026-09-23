<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
//Get username
session_start();

$depense_royal = 0;
if (isset($_POST['depense_royal'])) {
  $depense_royal = $_POST['depense_royal'];
}

$motif = "" ;
if (isset($_POST['motif'])) {
  $motif = $_POST['motif'];
}

$id_depense = 0 ;
if (isset($_POST['id_depense'])) {
  $id_depense = $_POST['id_depense'];
}

//-------------------------------------------
$query_c = "UPDATE depense
            SET montant = ? ,depense_royal = ?, motif = ?
            WHERE id_depense = ? ";

  $q = $bdd->prepare($query_c);

  $q->execute(array($depense_royal,$depense_royal, $motif, $id_depense));  

$q->closeCursor();
//echo ("Depense=".$depense_royal." depense_aparafa=".$depense_aparafa." motif=".$motif." dusername=".$username." id_depense=".$id_depense);
header("location: commande.php");

?>