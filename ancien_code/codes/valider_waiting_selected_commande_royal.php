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

if (isset($_POST['id_selected']))
    {
      //echo "string";
      $ids=explode(',',$_POST['id_selected']);

    

$state = "ready";
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
foreach ($ids as $id) 
  {
          $query_c = "UPDATE commande
                    SET state = ? 
                    WHERE  user = ? AND id_commande LIKE ?";

        	$q = $bdd->prepare($query_c);

        	$q->execute(array($state,$username,$id));	

          $q->closeCursor();
  }
  //-------------------------------------------
}//END ISSET
header("location: commande_royal.php");

?>