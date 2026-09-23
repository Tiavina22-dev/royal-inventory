<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/


$query_get_all_commande = "SELECT * FROM produit WHERE pu_aparafa > 0;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);

$query_get_all_commande->execute(array());
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	

	$id_x = $donnees['id_x'];
	$prix_unitaire = $donnees['prix_de_vente'];
	$note_prix = $donnees['note_prix'];
	$prix_amparafa = $donnees['pu_aparafa']+0;
	if ($prix_amparafa == 0 ) {
	$prix_amparafa = $donnees['prix_de_vente'];
	}
	else
	{$prix_amparafa = $donnees['pu_aparafa']+0;}
	$diff = $donnees['difference_prix'];
	echo $donnees['reference_x'];
	echo "<br>";
	echo "Note : ".$note_prix;
	echo "<br>";
	echo "difference_prix : ".$diff;
	echo "<br>";
	echo "PU GENERAL ".$prix_unitaire;
	echo "<br>";
	echo "PU AMPARAFA ".$prix_amparafa;
	echo "<br>";
	echo "PU AMBATO ".$prix_unitaire;
	echo "<br>";
	echo "########################################";
	echo "<br>";
	/*
	$query_c = "UPDATE produit
            SET pu_ambato_tantely = ?,pu_aparafa = ?,difference_prix_tantely = ?,difference_prix_amparafa =?,note_prix_tantely = ?, note_prix_amparafa = ?
            WHERE id_x = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($prix_unitaire,$prix_amparafa,$diff,$diff, $note_prix, $note_prix, $id_x));	

	$q->closeCursor();
	*/
}
	
$query_get_all_commande->closeCursor();

?>