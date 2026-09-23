<?php
session_start();
if (isset($_SESSION['User_Name'])) 
{
  $usernam = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $usernam = "default";
}

include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['numero_commande_stock'])) {
  $numero_commande_stock = $_GET['numero_commande_stock'];
}

//GET Data on mvt table
	$query_current_mvt ="SELECT * FROM mvt WHERE numero_commande_stock = ? AND type_de_mvt = 'stock'";
	$query_current_mvt = $bdd->prepare($query_current_mvt);
	$query_current_mvt -> execute(array($numero_commande_stock));
	while ($donnees = $query_current_mvt -> fetch())
	{
		$type_de_mvt = $donnees['type_de_mvt'].'';
		$id_x = $donnees['id_x'];
		$qt = $donnees['qt']+0;
		$prix_unitaire = $donnees['prix_unitaire']+0;
		$prix_aparafa = $donnees['prix_aparafa']+0;
		$nom_du_client = $donnees['nom_client_fournisseur'].'';
		$description_date = $donnees['description_date'].'';
		$numero_commande = $donnees['numero_commande_stock'].'';
		$ref_commande = $donnees['ref_commande_stock'].'';
		$note = $donnees['note'].'';
		$username = $donnees['user_mvt'].' Del By '.$usernam;
		$date1_mysql = $donnees['Date_du_Journal_mvt'].'';
	//MIGRATION OF DATA ONE BY ONE Mvt_history
	$query_c = "INSERT INTO mvt_history(type_de_mvt, id_x, qt, prix_unitaire,prix_aparafa,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,Date_du_Journal_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$prix_aparafa,$nom_du_client,$description_date,$numero_commande,$ref_commande,$note,$username,$date1_mysql));	

	$q->closeCursor();
	}
	$query_current_mvt -> closeCursor();

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM mvt
            WHERE numero_commande_stock = ? AND type_de_mvt = 'stock'";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande_stock));	

$q->closeCursor();
$msg_validation = 'Journal supprimer avec succes';
setcookie("msg_supression",$msg_validation, time()+5);
header("location: stock_recap.php");

?>