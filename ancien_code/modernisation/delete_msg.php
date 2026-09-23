<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------

if (isset($_GET['id_chat'])) {
  $id_chat = $_GET['id_chat'];
}

//-----------DELETE COMMANDE------------
//-------------------------------------------
$query_c = "UPDATE chat 
			SET status = 'deleted'
            WHERE id_chat = ?";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_chat));	

$q->closeCursor();
//DELETE IMAGE
//GET REAL IMG PATH
$query_path_img_x = "SELECT * FROM chat WHERE id_chat LIKE ?";
$q = $bdd->prepare($query_path_img_x);
$q->execute(array("%".$id_chat."%"));
$donnees = $q -> fetch();
$image_path_x = $donnees['path_photo'];
$q->closeCursor();

  if (strlen($image_path_x) != 0)
    {
      //DELETE PREVIEWS IMG FILE
      unlink($image_path_x);
      //DELETE PATH ON DB
      $sql = "UPDATE chat 
      SET path_photo = ''
      WHERE id_chat = ?";
      $q = $bdd->prepare($sql);
      $q->execute(array( $id_chat));
      $q->closeCursor();
    }
header("location: chat.php");

?>