<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
$id_mvt = 0;
if (isset($_GET['id_mvt'])) {
  $id_mvt = $_GET['id_mvt'];
}
$nom_client_fournisseur ="Amparafa";
	if (isset($_GET['nom_client_fournisseur'])) {
  	$nom_client_fournisseur = $_GET['nom_client_fournisseur'];
	}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM mvt
            WHERE id_mvt = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_mvt));	

$q->closeCursor();
setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
$msg='Balanced zero deleted Successfuly';
setcookie("msg",$msg, time()+5);
header("location: stock_balanced_zero.php");

?>