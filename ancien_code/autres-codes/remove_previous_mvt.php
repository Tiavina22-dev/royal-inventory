<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/

	//--------Clean Previous Ambato MVT---------
	$query_c = "DELETE FROM mvt WHERE (nom_client_fournisseur = 'Ambato_Tantely' AND  status = 'previous') ";

	$q = $bdd->prepare($query_c);

	$q->execute(array());	

	$q->closeCursor();
	//-----------------------------------------
header("location: script_general_reference_quantite_ambato_new2.php");
?>