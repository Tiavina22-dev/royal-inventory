<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//GET GENERAL_NOTE

$point = "congratulation";
//INSERT ALL COMMANDE TO THE mvt table
$query_get_all_commande = $bdd->query('SELECT id_mvt,numero_commande_stock,nom_client_fournisseur,nom_x,mvt.id_x as id_x,description_date,reference_x,qt*(-1) as qt,mvt.prix_unitaire as prix_unitaire, (mvt.prix_unitaire)*(-qt) as sous_total FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE numero_commande_stock=95;');
//$total_line=$query_get_all_commande->rowCount ();

$versement = 328600;
$no = 0;
$somme = 0;
$history ="";
while ($donnees = $query_get_all_commande -> fetch())
{
	$no = $no + 1;
	$id_x = $donnees['id_x'];
	$qt = -($donnees['qt']);
	$nom_du_client = $donnees['nom_client_fournisseur'];
	$description_date = $donnees['description_date'];
	$numero_commande = $donnees['numero_commande_stock'];
	$prix_unitaire = $donnees['prix_unitaire'];
	//variable history for recap_vente
	$history = $history."# ".$no." # ".$donnees['nom_x']." | qt: ".$donnees['qt']."| PU: ".$prix_unitaire." | MT: ".$donnees['sous_total']." #"."\r";
	$somme = $donnees['sous_total']+$somme;
	//Query to insert one by one in mvt table
}
//Add TOTAL AND VERSEMENT on history
$note_general = "Versement BFV REF:857178 du 23.10.20";
$history = $history."\r"."######AUTOMATIQUE GRAND TL:".$somme." Ar#######"."\r"."-------------------------------------"."\r"."--------VERSEMEMT BANK:".$versement." Ar-------"."\r"."-------------------------------------";
//--------QUERY TO INSERT ON RECAP_VENT-------
	$query_c = "INSERT INTO recap_vente(no_activite, nb_ligne, Montant, history,note_general,c_point) VALUES (?, ?, ?, ?, ?,?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande,$no,$somme,$history,$note_general,$point));	

	$q->closeCursor();
	echo "NC".$numero_commande."No".$no."SOM".$somme."Hist".$history."note_general".$note_general."Point".$point;
?>