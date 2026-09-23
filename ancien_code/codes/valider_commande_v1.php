<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//GET GENERAL_NOTE
$note_general = "";
if (isset($_POST['note_general'])) {
	$note_general = $_POST['note_general'];
}
//INSERT ALL COMMANDE TO THE mvt table
$query_get_all_commande = $bdd->query('SELECT id_commande,numero_commande,nom_du_client,nom_x,commande.id_x as id_x,description_date,reference_x,note_commande,qt,commande.prix_de_vente as prix_de_vente, (commande.prix_de_vente)*qt as sous_total FROM commande INNER JOIN produit ON commande.id_x = produit.id_x;');
//$total_line=$query_get_all_commande->rowCount ();
// Query for valeur memo
$query_num_stock = $bdd->query('SELECT valeur_memo FROM memo WHERE id_memo = 1;');
$num_stock = $query_num_stock -> fetch();
$num_stock = $num_stock['valeur_memo'];
$no = 0;
$somme = 0;
$history ="";
while ($donnees = $query_get_all_commande -> fetch())
{
	$no = $no + 1;
	$type_de_mvt = "vente";
	$id_x = $donnees['id_x'];
	$qt = -($donnees['qt']);
	$nom_du_client = $donnees['nom_du_client'];
	$description_date = $donnees['description_date'];
	$note = $donnees['note_commande'];
	$numero_commande = $donnees['numero_commande'];
	$ref_commande = $donnees['numero_commande']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente'];
	//variable history for recap_vente
	$history = "# ".$no." # ".$donnees['nom_x']."|qt:".$qt."|PU:".$prix_unitaire."|MT:".$donnees['sous_total']." #".CHAR(13).$history;
	$somme = $donnees['sous_total']+$somme;
	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$nom_du_client,$description_date,$numero_commande,$ref_commande,$note));	

	$q->closeCursor();
}
//--------QUERY TO INSERT ON RECAP_VENT-------
	$query_c = "INSERT INTO recap_vente(no_activite, nb_ligne, Montant, history,note_general) VALUES (?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande,$no,$somme,$history,$note_general));	

	$q->closeCursor();
//-----------DELETE COMMANDE------------
include('anuler_commande.php');
//set message on notification champ
$msg_validation = 'Commande de '.$nom_du_client.' valide avec succes | ';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande, time()+5);
//back to commande page
header("location: commande.php");

?>