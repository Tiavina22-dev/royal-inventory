<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//Get username
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
//-------future_id_x MAX+1---------------------
$reponse = $bdd->prepare('SELECT (MAX(id_x)+1) as future_id_x FROM produit');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
        {
          $future_id_x=$donnees['future_id_x'];
        }
//-------------------------------------------
$nom_x =""; 
if (isset($_POST['nom_x'])) {
	$nom_x = $_POST['nom_x'];
}

$id_unite = 1;

$prix_de_vente = 0;

if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}

$prix_aparafa = 0;

if (isset($_POST['prix_aparafa'])) {
  $prix_aparafa = $_POST['prix_aparafa'];
}
//echo $prix_aparafa;
$reference_x ="";

if (isset($_POST['reference_x'])) {
	$reference_x = $_POST['reference_x'];
}

$qt = 0;

if (isset($_POST['qt'])) {
	$qt = $_POST['qt'];
}
$note_x ="";
if (isset($_POST['note_x'])) {
  $note_x = $_POST['note_x'];
}
$state = "preparing";
//-----------INSERT INTO produit------------
$query_x = "INSERT INTO produit(id_unite, nom_x, prix_de_vente, reference_x,note_x,user) VALUES (?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_x);

	$q->execute(array($id_unite, $nom_x, $prix_de_vente, $reference_x,$note_x,$username));
$q->closeCursor();
//-------derniere ID---------------------
$reponse = $bdd->prepare('SELECT MAX(id_x) as latest_id FROM produit');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
        {
          $latest_id=$donnees['latest_id'];
        }
//-------------------------------------------
//-----------INSERT INTO commande------------
//-------Reccuperation du valeur memo--------
$description_date = "";
$nom_du_client = "";
$reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 1');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $numero_commande = $donnees['valeur_memo'];
         $nom_du_client = $donnees['note_memo'];
         $description_date = $donnees['description_date'];
          }
$reponse->closeCursor();
//-------------------------------------------
$query_c = "INSERT INTO commande(numero_commande, nom_du_client,description_date , id_x, qt, prix_de_vente,prix_aparafa, state,note_commande,user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande, $nom_du_client,$description_date , $latest_id, $qt, $prix_de_vente,$prix_aparafa, $state,$note_x,$username));	

$q->closeCursor();
//echo $username;
header("location: commande.php");
?>