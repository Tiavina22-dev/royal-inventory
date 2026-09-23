<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
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
$nom_client_fournisseur = "";
if (isset($_POST['nom_client_fournisseur'])) {
	$nom_client_fournisseur = $_POST['nom_client_fournisseur'];
}


$actual_id = 0;
if (isset($_POST['actual_id'])) {
	$actual_id = $_POST['actual_id'];
}


$reference_x ="";
if (isset($_POST['reference_x'])) {
	$reference_x = $_POST['reference_x'];
}

//QUERY FOR CHANGE OF REFERENCE
$id_x = 0;
$query_change_ref = 'SELECT * from produit WHERE reference_x = ? ;';
	$q = $bdd->prepare($query_change_ref);

	$q->execute(array($reference_x));
	//Number of Line
	$nb_line=$q->rowCount ();
	$donnees = $q -> fetch();
	$id_x = $donnees['id_x'];
	if ($nb_line == 0) {
	} else {
//-----------Change id_x on mvt--------------
//-------------------------------------------
if ($nom_client_fournisseur == "Tous") {
	$query_c = "UPDATE mvt
            SET id_x = ?, user_mvt = ?
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x, $username, $actual_id));	

} else {
	$query_c = "UPDATE mvt
            SET id_x = ?, user_mvt = ?
            WHERE id_x = ? AND nom_client_fournisseur = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x, $username, $actual_id, $nom_client_fournisseur));	

}
	$q->closeCursor();

}
setcookie("key_word",$id_x, time()+5);
setcookie("point_de_vente",$nom_client_fournisseur, time()+5);
header("location: mvt_produit.php");

?>