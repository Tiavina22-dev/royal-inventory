<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//INSERT ALL COMMANDE TO THE mvt table
$query_get_all_commande = $bdd->query('SELECT * FROM stock_prep;');
// Query for valeur memo
$query_num_stock = $bdd->query('SELECT valeur_memo FROM memo WHERE id_memo = 2;');
$num_stock = $query_num_stock -> fetch();
$num_stock = $num_stock['valeur_memo'];
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	
	$no = $no + 1;
	$type_de_mvt = "stock";
	$id_x = $donnees['id_x'];
	$qt = $donnees['qt'];
	$nom_du_client = $donnees['nom_du_client'];
	$numero_commande_stock = $donnees['numero_stock_prep'];
	$ref_stock = $donnees['numero_stock_prep']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente'];
	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,numero_commande_stock,ref_commande_stock) VALUES (?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$nom_du_client,$numero_commande_stock,$ref_stock));	

	$q->closeCursor();
}
	
//-----------DELETE COMMANDE------------
include('anuler_stock.php');
//set message on notification champ
$msg_validation = 'Stock de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande, time()+5);
//back to commande page
header("location: stock.php");

?>