<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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
//GET GATEGORY
$category = 'no';
if (isset($_POST['category'])) {
	$category = $_POST['category'];
}

//INSERT rectification stock TO THE mvt table
$query_get_all_commande = "SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);

$query_get_all_commande->execute(array($username));
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	
	//$no = $no + 1;
	$no = $donnees['id_stock_prep'];
	$type_de_mvt = "stock";
	$id_x = $donnees['id_x'];
	$qt = abs($donnees['qt']);
	$nom_du_client = $donnees['nom_du_client'];
	$description_date = 'Inventaire/Ajout du 26/07/21';
	$numero_commande_stock = $donnees['numero_stock_prep'];
	$ref_stock = $donnees['numero_stock_prep']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente']+0;
	$note = $donnees['note'];
	$user_stock_prep = $donnees['user_stock_prep'];
	//--------------------------------------------------------------------------------
	//-------------------------------------------------------------
	//VERIFY CHANGEMENT DE PRIX
	$verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
	$verification_prix = $bdd->prepare($verification_prix);
	$verification_prix->execute(array($id_x));
	//-----Calcule difference de prix------
	$data = $verification_prix -> fetch();
	$prix_avant = $data['pu_ambato_tantely']+0;
	$note_prix = $data['note_prix'];
	$difference_prix = 0;
	//################################################## 
    $qt_rectificative = $qt;//Special general inventory Ambato
    //################################################## 

	if ($difference_prix != 0) {
		$note_prix = "NEW";
	}else{
		$difference_prix = $data['difference_prix'];
	}
	$verification_prix ->closeCursor();
	//-------------------------------------
	$status = "2nd_General_Inventory_26_Jolay_2021";
	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt_rectificative, $prix_avant,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$user_stock_prep,$status));	

	$q->closeCursor();
	//Change official prix
	if ($nom_du_client =='Ambato_Tantely')
	{
		$note = "latest";
		$query_c = "UPDATE produit SET pu_ambato_tantely = ?, note_x = ?, user_x = ? ,note_prix_tantely = ?, difference_prix_tantely = ? WHERE id_x = ?";
		$q = $bdd->prepare($query_c);
		$q->execute(array($prix_avant,$note,$user_stock_prep, $note_prix, $difference_prix, $id_x));
	}
	//Change official prix
	//echo $qt_rectificative;
	//echo "<br>";
	/*
	echo $note_prix;
	echo "<br>";
	echo $difference_prix;
	echo "<br>";
	echo "<br>";
	echo $user_stock_prep;
	echo "<br>";
	echo $id_x;
	*/
}
//-----------DELETE PREPARATION------------
$query_c = "DELETE FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();
//------------------------------------
//--------Clean All Previous Ambato MVT---------
	$query_c = "DELETE FROM mvt WHERE (nom_client_fournisseur = 'Ambato_Tantely' AND  status = 'previous') ";

	$q = $bdd->prepare($query_c);

	$q->execute(array());	

	$q->closeCursor();
//-----------------------------------------

//set message on notification champ
$msg_validation = 'Rectification du Stock de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande_stock, time()+5);
//back to commande page
header("location: controle_x.php");
?>