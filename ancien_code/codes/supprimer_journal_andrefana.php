<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
if (isset($_GET['numero_commande']))
{
  $numero_commande = $_GET['numero_commande'];
}
//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM mvt_calc WHERE numero_commande_stock = ? AND type_de_mvt = 'vente'";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande));	

$q->closeCursor();
//-----------DELETE COMMANDE------------
/*
//-------------------------------------------
$query_c = "DELETE FROM recap_vente WHERE no_activite = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande));	

$q->closeCursor();
*/
//-----------DELETE DEPENSE------------
//-------------------------------------------
$query_c = "DELETE FROM depense WHERE activity_no = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande));	

$q->closeCursor();
//-------------------------------------------
$msg_validation = 'Journal supprimer avec succes';
setcookie("msg_supression",$msg_validation, time()+5);
header("location: vente_recap_andrefana.php");

?>