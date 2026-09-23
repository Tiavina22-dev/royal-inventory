<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
if (isset($_POST['nom_client'])) {
	$nom_client = $_POST['nom_client'];
}

$reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 1');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur_memo = $donnees['valeur_memo'];
         $nom_du_client = $donnees['note_memo'];
          }
$reponse->closeCursor();
//-------------------------------------------
$query_c = "INSERT INTO commande(valeur_memo, nom_du_client, id_x, qt, prix_de_vente, state) VALUES (?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($valeur_memo, $nom_du_client, $id_x, $qt, $prix_de_vente, $state));	

$q->closeCursor();

header("location: commande.php");

?>