<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
//---------SET status To RESOLU-------------------
 //Query to liste All Journal de vente
    $query_journal_vente = "SELECT *,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = 'Amparafa' AND status ='NON_RESOLU' GROUP BY mvt.numero_commande_stock ORDER BY id DESC;";
    $query_journal_vente = $bdd->prepare($query_journal_vente);

    $query_journal_vente->execute(array());
    while ($donnees = $query_journal_vente -> fetch())
{
		$query_c = "UPDATE  recap_vente SET status = 'RESOLU' WHERE no_activite = ? ";

		$q = $bdd->prepare($query_c);

		$q->execute(array($donnees['no_activite']));	

		$q->closeCursor();
}
$query_journal_vente->closeCursor();
header("location: vente_recap_amparafa.php");

?>