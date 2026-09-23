<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------future_id_x MAX+1---------------------
//-------------------------------------------
$reference_x = "";
if (isset($_POST['reference_x'])) {
  $reference_x = $_POST['reference_x'];
}

$reponse = $bdd->prepare('SELECT * FROM produit WHERE reference_x = ?');
        $reponse->execute(array($reference_x));
        $msg = $reponse->rowCount ();
  echo $msg;
  $msg ="Mandeha";
$reponse->closeCursor();
setcookie("msg_E",$msg, time()+5);
//header("location: stock.php");
?>