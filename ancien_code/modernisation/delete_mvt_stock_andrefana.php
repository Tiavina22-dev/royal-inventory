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
$nom_client_fournisseur ="";
	if (isset($_GET['nom_client_fournisseur'])) {
  	$nom_client_fournisseur = $_GET['nom_client_fournisseur'];
	}

	$description_date ="Journal du 22/10/20";
	if (isset($_GET['description_date'])) {
  	$description_date = $_GET['description_date'];
	}

	$no_activite = 0;
	if (isset($_GET['no_activite'])) {
  	$no_activite = $_GET['no_activite'];
	}
//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM mvt_calc
            WHERE id_mvt = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_mvt));	

$q->closeCursor();
setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
setcookie("description_date",$description_date, time()+5);
setcookie("no_activite",$no_activite, time()+5);
header("location: stock_recap_details_andrefana.php");

?>