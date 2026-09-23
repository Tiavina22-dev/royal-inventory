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
  $qt = abs($_POST['qt']);
  	//EVITER ERREUR DE SIGNE
  	preg_match("'([Reduit]{5})'", $description_date, $res_regex_1);//Search word 'Reduit'
	preg_match("'([Retirer]{6})'", $description_date, $res_regex_2);//Search word 'Retirer'
	if (isset($res_regex_1[1])) { //if mot Reduit existe dans description qt negatif
		$qt = abs($qt) *(-1);
	}
	if (isset($res_regex_2[1])) { //if mot Reduit existe dans description qt negatif
		$qt = abs($qt) *(-1);
	}
	//----------------------
	//POUR VERIFIER/RECTIFIER
	preg_match("'([Verifier]{7})'", $description_date, $res_regex_3);//Search word 'Reduit'
	preg_match("'([Rectifier]{8})'", $description_date, $res_regex_4);//Search word 'Retirer'
	if (isset($res_regex_3[1])) { //if mot Verifier existe dans description qt negatif
		$qt = $_POST['qt'];
	}
	if (isset($res_regex_4[1])) { //if mot Rectifier existe dans description qt negatif
		$qt = $_POST['qt'];
	}
}
$note="";
if (isset($_POST['note'])) {
  $note = $_POST['note'];
}

$no_activite = 0;
if (isset($_POST['no_activite'])) {
	$no_activite = $_POST['no_activite'];
}
	//Check if status is GI
	$query_GI = 'SELECT * from mvt WHERE id_mvt = ? ;';
	$q_GI = $bdd->prepare($query_GI);

	$q_GI->execute(array($id_mvt));
	$donnees = $q_GI -> fetch();
	$status = $donnees['status'];
	$numero_commande_stock = $donnees['numero_commande_stock'];
	$before_change = $donnees['ref_commande_stock'];
	$id_x_old = $donnees['id_x'];
	//echo $donnees['prix_aparafa'];
	//For history
	$change = '';
	if ($qt != $donnees['qt']) {
		$change = ' |QT ('.$donnees['qt'].' <b>></b> '.$qt.')<br>';
	}
	if ($prix_unitaire != $donnees['prix_unitaire']) {
		$change = $change.' |PU Royal('.$donnees['prix_unitaire'].' <b>></b> '.$prix_unitaire.')<br>';
	}

	if ($prix_client != $donnees['prix_client']) {
		$change = $change.' |PU Amarotana ('.($donnees['prix_client']+0).' <b>></b> '.$prix_client.')<br>';
	}

	//qt_rect_new = qt_rect_preview + (qt_new - qt_preview)
	if ($status == 'General_Inventory') {
		$qt_rectificative = $donnees['prix_aparafa'] + ($_POST['qt']-$donnees['qt']);
		//Insert QT Rectificative COMMUNE
		  $query_qr = "UPDATE mvt
		            SET prix_aparafa = ?
		            WHERE id_x = ? AND nom_client_fournisseur = ? AND Date_du_Journal_mvt = ?";

		  $qr = $bdd->prepare($query_qr);

		  $qr->execute(array($qt_rectificative, $donnees['id_x'],$donnees['nom_client_fournisseur'],$donnees['Date_du_Journal_mvt'])); 

		  $qr->closeCursor();
		  //----------------------
		  //echo $id_mvt,$donnees['nom_client_fournisseur'],$donnees['Date_du_Journal_mvt'];
	}
	
	$q_GI->closeCursor();

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

		$type = "Mvt_Stock";
		$q->execute(array($after_change ,$before_change , $change, $username, $type));  
		$q->closeCursor();
		//-----------UPDATE MVT--------------
				$query_c = "UPDATE mvt
		            SET id_x = ?,qt = ?, prix_unitaire = ?, prix_client = ? , note = ?, user_mvt = ?
		            WHERE id_mvt = ?";

			$q = $bdd->prepare($query_c);

			$q->execute(array($id_x, $qt, $prix_unitaire,$prix_client , $note, $username, $id_mvt));	

		$q->closeCursor();
		//echo 'id_x ='.$id_x;
		//echo 'qt ='.$qt;
		//echo 'prix_unitaire ='.$prix_unitaire;
		//echo 'note ='.$note;
		//echo ' id_mvt ='.$id_mvt;
		//echo "nom_client_fournisseur = ".$nom_client_fournisseur;
		//echo "description_date = ".$description_date;
	}
//------------------------------------
setcookie("nom_client_fournisseur",$nom_client_fournisseur, time()+5);
setcookie("description_date",$description_date, time()+5);
setcookie("no_activite",$no_activite, time()+5);
header("location: stock_recap_details.php");

?>