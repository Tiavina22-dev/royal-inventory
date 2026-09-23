<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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

$description_date = "default_not_set";
if (isset($_POST['description_date'])) {
	$description_date = $_POST['description_date'];
}

$nom_client_fournisseur = "";
if (isset($_POST['nom_client_fournisseur'])) {
	$nom_client_fournisseur = $_POST['nom_client_fournisseur'];
}

$no_activite = 0;
if (isset($_POST['no_activite'])) {
	$no_activite = $_POST['no_activite'];
}
/*
$benefice_aparafa = 0;
if (isset($_POST['benefice_aparafa'])) {
	$benefice_aparafa = $_POST['benefice_aparafa'];
}

$resolution = 0;
if (isset($_POST['resolution'])) {
	$resolution = $_POST['resolution']*(-1);
}

$mihoatra = 0;
if (isset($_POST['mihoatra'])) {
	$mihoatra = $_POST['mihoatra'];
}

$royal_versement = 0;
if (isset($_POST['royal_versement'])) {
	$royal_versement = $_POST['royal_versement'];
}

$path = "defaut_not_set";
if (isset($_POST['path'])) {
	$path = $_POST['path'];
}
*/

	//Query to Change description date of mvt
	$query_c = "UPDATE mvt_calc
            SET description_date = ?,user_mvt = ?
            WHERE numero_commande_stock = ? AND type_de_mvt = 'stock'";

	$q = $bdd->prepare($query_c);

	$q->execute(array($description_date, $username, $no_activite));	

	$q->closeCursor();

	//Query to Change note_general and directory of recap_vente
	/*
	$query_c = "UPDATE recap_vente
            SET note_general = ?,directory = ?,difference_aparafa = ?,resolution =?, mihoatra =?, Montant = ?
            WHERE no_activite = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($note_general, $path,$benefice_aparafa, $resolution,$mihoatra, $royal_versement, $no_activite));	

	$q->closeCursor();
*/
//echo "note_general = ".$note_general."<br> path = ".$path."<br> benefice_aparafa = ".$benefice_aparafa."<br> resolution = ".$resolution."<br> mihoatra = ".$mihoatra."<br> royal_versement = ".$royal_versement."<br> no_activite = ".$no_activite;
//cookies to back show details again
setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
setcookie("description_date",$description_date, time()+5);
setcookie("no_activite",$no_activite, time()+5);
//back to commande page
header("location: stock_recap_details_andrefana.php");

?>