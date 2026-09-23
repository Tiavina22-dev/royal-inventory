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

$state = "preparing";
//-----------CHECK IF VENTE_CALC HAVE EXISTING LINE------------
    $query_exist_line = $bdd->prepare("SELECT * FROM vente_calc WHERE user = ? ");
    $query_exist_line->execute(array($username));
    $exist_line = $query_exist_line->rowCount ();
    if ($exist_line > 0) {
    	while ($data =  $query_exist_line->fetch())
         {
          //Numero de commande est la valeure memo
         $numero_commande = $data['numero_commande'];
         $nom_du_client = $data['nom_du_client'];
         $description_date = $data['description_date'];
          }
		 $query_exist_line->closeCursor();
    } else {
//-------Reccuperation du valeur memo--------
$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $reponse->execute(array($username));

       while ($donnees = $reponse->fetch())
         {
          //Numero de commande est la valeure memo
         $numero_commande = $donnees['numero_vente_calc'];
         $nom_du_client = $donnees['point_de_vente_vente_calc'];
         $description_date = $donnees['description_date_vente_calc'];
          }
$reponse->closeCursor();
$query_exist_line->closeCursor();
//-------------------------------------------
}//END ELSE
$query_c = "INSERT INTO vente_calc(numero_commande, nom_du_client,description_date, id_x, qt, prix_de_vente,prix_aparafa, state,note_commande,user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande, $nom_du_client,$description_date, $id_x, $qt, $prix_de_vente,$prix_aparafa, $state,$note_commande,$username));	

$q->closeCursor();

header("location: commande_andrefana.php");

?>