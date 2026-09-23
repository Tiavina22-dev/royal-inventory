<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
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
//get Produit name
$nom_x ="";
if (isset($_POST['nom_x'])) {
	$nom_x = $_POST['nom_x'];
}

$id_unite = 1;
$note_commande = "";
$qt = 0;
$id_x = 0 ;
$prix_de_vente = 0 ;
$prix_aparafa = 0 ;
if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}
if (isset($_POST['prix_aparafa'])) {
  $prix_aparafa = $_POST['prix_aparafa'];
}
if (isset($_POST['note_commande'])) {
	$note_commande = $_POST['note_commande'];
}

if (isset($_POST['qt'])) {
	$qt = $_POST['qt'];
}
if (isset($_POST['id_x'])) {
  $id_x = $_POST['id_x'];
}

if (isset($_POST['prix_fournisseur'])) {
  $prix_fournisseur = $_POST['prix_fournisseur'];
}

$state = "preparing";
//-----------INSERT INTO produit------------
//-----------INSERT INTO commande------------
//-------Reccuperation du valeur memo--------
$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $reponse->execute(array($username));

       while ($donnees = $reponse->fetch())
         {
          //Numero de commande est la valeure memo
         $numero_commande = $donnees['numero_commande'];
         $nom_du_client = $donnees['point_de_vente'];
         $description_date = $donnees['description_date'];
          }
$reponse->closeCursor();
//-------------------------------------------
$query_c = "INSERT INTO commande(numero_commande, nom_du_client,description_date, id_x, qt, prix_de_vente,prix_aparafa, state,note_commande,user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande, $nom_du_client,$description_date, $id_x, $qt, $prix_de_vente,$prix_fournisseur, $state,$note_commande,$username));	

  $q->closeCursor();
  //-------------------------------------------
  //Copy to user trelahy for verification
  /*
  $username = 'trelahy';
  $query_c = "INSERT INTO commande(numero_commande, nom_du_client,description_date, id_x, qt, prix_de_vente,prix_aparafa, state,note_commande,user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $q = $bdd->prepare($query_c);

  $q->execute(array($numero_commande, $nom_du_client,$description_date, $id_x, $qt, $prix_de_vente,$prix_fournisseur, $state,$note_commande,$username));  

  $q->closeCursor();
  */
  //-------------------------------------------
header("location: commande_royal.php");

?>