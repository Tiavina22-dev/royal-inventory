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
$reference_x ="";
if (isset($_POST['reference_x'])) {
	$reference_x = $_POST['reference_x'];
}
$qt = 0;
if (isset($_POST['qt'])) {
  $qt = abs($_POST['qt']);
}
$note="";
if (isset($_POST['note'])) {
  $note = $_POST['note'];
}

$no_activite = 0;
if (isset($_POST['no_activite'])) {
	$no_activite = $_POST['no_activite'];
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
		//----------------UPDATE PRIX FOURNISSEUR-------------------
  //Check change is valide
  $verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
  $verification_prix = $bdd->prepare($verification_prix);
  $verification_prix->execute(array($id_x));
  $data = $verification_prix -> fetch();
  $difference_prix_fournisseur = $prix_unitaire - ($data['prix_fournisseur']+0);
  if ($difference_prix_fournisseur  != 0) {

    //------------ Update table produit -----------
    $query_p = "UPDATE produit
                SET prix_fournisseur = ?
                WHERE id_x = ?";

      $q = $bdd->prepare($query_p);

      $q->execute(array($prix_unitaire,$id_x));

      $q->closeCursor();
      //--------------Update table history--------------------------
      $before_change = ' | PU Fournisseur : '.($data['prix_fournisseur']+0).'</br>';
      $after_change = " | PU Fournisseur : <span class ='text-danger'><b>".$prix_unitaire.'</b></span></br>';
      $query_c = "INSERT INTO history(after_change,before_change, details,responsable,type) VALUES (?, ?, ?, ?,?)";

      $q = $bdd->prepare($query_c);

      $type = "Prix";
      $q->execute(array($after_change ,$before_change , $id_x, $username, $type));  

      $q->closeCursor();
    } //END IF
    $verification_prix -> closeCursor();
    //----------------UPDATE PRIX FOURNISSEUR END-------------------
//-------------------------------------------

$query_c = "UPDATE mvt
            SET id_x = ?,qt = ?, prix_unitaire = ?, note = ?, user_mvt = ?
            WHERE id_mvt = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_x, $qt, $prix_unitaire, $note, $username, $id_mvt));	

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
setcookie("no_activite",$no_activite, time()+5);
header("location: facture_recap_details.php");

?>