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
$prix_client = 0;
if (isset($_POST['prix_client'])) {
	$prix_client = $_POST['prix_client'];
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
//CHECK CHANGE
	$query_check = 'SELECT * from mvt WHERE id_mvt = ? ;';
	$query_check = $bdd->prepare($query_check);

	$query_check->execute(array($id_mvt));
	$donnees_check = $query_check -> fetch();
	$numero_commande_stock = $donnees_check['numero_commande_stock'];
	$before_change = $donnees_check['ref_commande_stock'];
	$id_x_old = $donnees_check['id_x'];

//For history
	$change = '';
	if ($qt != $donnees_check['qt']) {
		$change = ' |QT ('.ABS($donnees_check['qt']).' <b>></b> '.ABS($qt).')<br>';
	}
	if ($prix_unitaire != $donnees_check['prix_unitaire']) {
		$change = $change.' |PU ('.$donnees_check['prix_unitaire'].' <b>></b> '.$prix_unitaire.')<br>';
	}
	if ($prix_client != $donnees_check['prix_client']) {
		$change = $change.' |PU Client ('.$donnees_check['prix_client'].' <b>></b> '.$prix_client.')<br>';
	}

	$query_check -> closeCursor();
//QUERY FOR CHANGE OF REFERENCE
$id_x = 0;
$query_change_ref = 'SELECT * from produit WHERE reference_x = ? ;';
	$q = $bdd->prepare($query_change_ref);

	$q->execute(array($reference_x));
	//Number of Line
	$nb_line=$q->rowCount ();
	$donnees = $q -> fetch();
	$id_x = $donnees['id_x'];
	if ($id_x_old != $id_x) {//Check if change exist
		if ($nb_line == 0) { //check if reference is valide
			} else {
		//-----------Change id_x on mvt--------------
				//GET OLD PRODUCT NAME
		$query_old = 'SELECT * from produit WHERE id_x = ? ;';
		$query_old = $bdd->prepare($query_old);

		$query_old->execute(array($id_x_old));
		//Number of Line
		$donnees_old = $query_old -> fetch();
		$change = $change.' |PRODUCT ('.$donnees_old['reference_x'].'#'.$donnees_old['nom_x'].' <b>></b> '.$donnees['reference_x'].'#'.$donnees['nom_x'].')<br>';
		$query_old -> closeCursor();
		//-------------------------------------------

		}
		
	}
	if (strlen($change) > 0) {
		//-----------WRITE HISTORY--------------
		
    	$after_change = $numero_commande_stock;
     	$query_c = "INSERT INTO history(after_change,before_change, details,responsable,type) VALUES (?, ?, ?, ?,?)";
		$q = $bdd->prepare($query_c);

		$type = "Mvt_Vente";
		$q->execute(array($after_change ,$before_change , $change, $username, $type));  
		$q->closeCursor();
		//-----------UPDATE MVT--------------
		$query_c = "UPDATE mvt
            SET id_x = ?,qt = ?, prix_unitaire = ?, prix_client = ?, note = ?, user_mvt = ?
            WHERE id_mvt = ?";

		$q = $bdd->prepare($query_c);

		$q->execute(array($id_x, $qt, $prix_unitaire, $prix_client, $note, $username, $id_mvt));	

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
header("location: vente_recap_details.php");

?>