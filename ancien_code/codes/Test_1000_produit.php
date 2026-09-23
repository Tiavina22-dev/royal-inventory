<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------future_id_x MAX+1---------------------

//-------------------------------------------
for ($i=0; $i < 3000; $i++) { 
	

	$nom_x = "Produit test".$i;


$id_unite = 1;


	$prix_de_vente = 1000;



	$reference_x = "XXX".$i;



//-----------INSERT INTO produit------------
$query_x = "INSERT INTO produit(id_unite, nom_x, prix_de_vente, reference_x) VALUES (?, ?, ?, ?)";

	$q = $bdd->prepare($query_x);

	$q->execute(array($id_unite, $nom_x, $prix_de_vente, $reference_x));
$q->closeCursor();

}

?>