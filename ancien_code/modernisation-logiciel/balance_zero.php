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

$id_x ="";
if (isset($_POST['id_x'])) {
	$id_x = $_POST['id_x'];
}

$sm ="";
if (isset($_POST['sm'])) {
	$sm = ABS($_POST['sm']);
}

$qt_actu ="";
if (isset($_POST['qt_actu'])) {
	$qt_actu = ABS($_POST['qt_actu']);
}
$qt = $sm + $qt_actu;
//-----------Change id_x on mvt--------------
if ($qt_actu<>0) {
//-------------------------------------------
$type_de_mvt = 'stock';
$prix_unitaire = 0;
$prix_aparafa = 0;
$description_date = 'balance_zero';
$numero_commande = "00";
$ref_commande = "00-00";
$note = "Confirmer Zero";
//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,prix_aparafa,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$prix_aparafa,$nom_client_fournisseur,$description_date,$numero_commande,$ref_commande,$note,$username));	

	$q->closeCursor();

//echo 'id_x ='.$id_x;
//echo 'qt ='.$qt;
//echo 'prix_unitaire ='.$prix_unitaire;
//echo 'note ='.$note;
//echo ' id_mvt ='.$id_mvt;
//echo "nom_client_fournisseur = ".$nom_client_fournisseur;
//echo "description_date = ".$description_date;
//setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
//setcookie("description_date",$description_date, time()+5);
$msg = "Balancement Effectuer!";
} else {
$msg = "Efa Zero io, izay Tsy zero balancena!";
}
setcookie("msg",$msg, time()+5);
header("location: stock_epuise_zero.php?nom_client_fournisseur=$nom_client_fournisseur");

?>