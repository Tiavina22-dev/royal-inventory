<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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
//echo $username;
//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "DELETE FROM vente_calc WHERE user = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();

//-----------DELETE DEPENSE------------
//---GET no activity by client name----
$query_client_name = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $activity_no = $donnees['numero_commande'];
         //$nom_du_client = $donnees['point_de_vente'];
         //$description_date = $donnees['description_date'];
          }
    $query_client_name->closeCursor();
//---DELETE DEPENSE---------------------
    $query_c = "DELETE FROM depense WHERE activity_no = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($activity_no));	

	$q->closeCursor();
//-------------------------------------------

header("location: commande_andrefana.php");
?>