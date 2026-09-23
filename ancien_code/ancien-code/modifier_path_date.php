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
//GET GENERAL_NOTE
$note_general = "";
if (isset($_POST['note_general'])) {
	$note_general = $_POST['note_general'];
}

$description_date = "defaut_not_set";
if (isset($_POST['description_date'])) {
	$description_date = $_POST['description_date'];
	$date1_mysql = null;
	    $chaine =  $description_date.' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
	//Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '-', $date1);
        $date1 = str_replace('/ ', '-', $date1);
        $date1 = str_replace(' / ', '-', $date1);
        $date1 = str_replace(' -', '-', $date1);
        $date1 = str_replace(' - ', '-', $date1);
        $date1 = str_replace('/', '-', $date1);
        
        //echo $date1;
        //Convert date to english format for compare
        $date1_mysql = DateTime::createFromFormat('d-m-y', $date1);
        $date1_mysql = $date1_mysql -> format('Y-m-d');
	}
}

$nom_client_fournisseur = "";
if (isset($_POST['nom_client_fournisseur'])) {
	$nom_client_fournisseur = $_POST['nom_client_fournisseur'];
}

$no_activite = 0;
if (isset($_POST['no_activite'])) {
	$no_activite = $_POST['no_activite'];
}

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


	//Query to Change description date of mvt
	$query_c = "UPDATE mvt
            SET description_date = ?, Date_du_Journal_mvt = ?
            WHERE numero_commande_stock = ? AND type_de_mvt = 'vente'";

	$q = $bdd->prepare($query_c);

	$q->execute(array($description_date, $date1_mysql, $no_activite));	

	$q->closeCursor();

	//Query to Change note_general and directory of recap_vente
	$query_c = "UPDATE recap_vente
            SET note_general = ?,directory = ?,difference_aparafa = ?,resolution =?, mihoatra =?, Montant = ?, Date_du_Journal = ?, responsable = ?
            WHERE no_activite = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($note_general, $path,$benefice_aparafa, $resolution,$mihoatra, $royal_versement, $date1_mysql, $username, $no_activite));	

	$q->closeCursor();

//echo "note_general = ".$note_general."<br> path = ".$path."<br> benefice_aparafa = ".$benefice_aparafa."<br> resolution = ".$resolution."<br> mihoatra = ".$mihoatra."<br> royal_versement = ".$royal_versement."<br> no_activite = ".$no_activite;
//cookies to back show details again
setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
setcookie("description_date",$description_date, time()+5);
setcookie("no_activite",$no_activite, time()+5);
//back to commande page
header("location: vente_recap_details.php");
//echo $date1_mysql;

?>