<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/


//INSERT ALL COMMANDE TO THE mvt table
$query_get_all_commande = "SELECT * FROM mvt WHERE nom_client_fournisseur = 'Soalazaina' ;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);

$query_get_all_commande->execute(array());
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	

	$id_x = $donnees['id_x'];
	$pu = $donnees['prix_unitaire']+0;
	
	//Query to insert one by one in mvt table
	$query_c = "UPDATE produit
		            SET pu_soalazaina = ?
		            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($pu, $id_x));	

	$q->closeCursor();

	}
	$query_get_all_commande->closeCursor();
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
	

?>