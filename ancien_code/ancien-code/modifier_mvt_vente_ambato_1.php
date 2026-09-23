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

$description_date = "";
if (isset($_POST['description_date'])) {
	$description_date = $_POST['description_date'];
}

$id_mvt = 0;
if (isset($_POST['id_mvt'])) {
	$id_mvt = $_POST['id_mvt'];
}

$prix_unitaire = 0;
if (isset($_POST['prix_unitaire'])) {
	$prix_unitaire = $_POST['prix_unitaire'];
}
$prix_aparafa = 0;
if (isset($_POST['prix_aparafa'])) {
	$prix_aparafa = $_POST['prix_aparafa'];
}
$reference_x ="";
if (isset($_POST['reference_x'])) {
	$reference_x = $_POST['reference_x'];
}
$qt = 0;
if (isset($_POST['qt'])) {
  $qt = abs($_POST['qt'])*(-1);
}
$note="";
if (isset($_POST['note'])) {
  $note = $_POST['note'];
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

$query_c = "UPDATE mvt
            SET id_x = ?,qt = ?, prix_unitaire = ?, prix_aparafa = ?, note = ?, user_mvt = ?
            WHERE id_mvt = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x, $qt, $prix_unitaire, $prix_aparafa, $note, $username, $id_mvt));	

$q->closeCursor();

//echo 'id_x ='.$id_x;
//echo 'qt ='.$qt;
//echo 'prix_unitaire ='.$prix_unitaire;
//echo 'note ='.$note;
//echo ' id_mvt ='.$id_mvt;
//echo "nom_client_fournisseur = ".$nom_client_fournisseur;
//echo "description_date = ".$description_date;
}
setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
setcookie("description_date",$description_date, time()+5);
header("location: vente_recap_details_ambato_1.php");

?>