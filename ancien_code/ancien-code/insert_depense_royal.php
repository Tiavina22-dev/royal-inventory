<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
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

$motif ="";
if (isset($_POST['motif'])) {
  $motif = $_POST['motif'];
}

$depense = 0 ;
if (isset($_POST['depense'])) {
  $depense = $_POST['depense'];
}
$depense_aparafa = 0 ;
if (isset($_POST['depense_aparafa'])) {
  $depense_aparafa = $_POST['depense_aparafa'];
}
//-------------------------------------------------
// Query for nom du client
$query_client_name = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $activity_no = $donnees['numero_commande'];
         //$nom_du_client = $donnees['point_de_vente'];
         //$description_date = $donnees['description_date'];
          }
    $query_client_name->closeCursor();
//-------------------------------------------
$query_c = "INSERT INTO depense(montant,depense_aparafa, motif,activity_no,user_depense) VALUES (?, ?, ?, ?,?)";

  $q = $bdd->prepare($query_c);

  $q->execute(array($depense,$depense_aparafa, $motif,$activity_no,$username));  

$q->closeCursor();
//echo ("Depense=".$depense."Depense Amparafa=".$depense_aparafa" Motif=".$motif." Activity_no=".$activity_no." dusername=".$username);
//Eviter la page de demarrage
$time_for_nouveau_commande_cookie= 1 ;
$total_line = 1 ;
setcookie("time_for_nouveau_commande_cookie",$time_for_nouveau_commande_cookie, time()+5);
setcookie("total_line",$total_line, time()+5);
header("location: commande_royal.php");

?>