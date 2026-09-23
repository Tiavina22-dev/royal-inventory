<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------future_id_x MAX+1---------------------
$reponse = $bdd->prepare('SELECT (MAX(id_x)+1) as future_id_x FROM produit');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
        {
          $future_id_x=$donnees['future_id_x'];
        }
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

if (isset($_POST['nom_x'])) {
	$nom_x = $_POST['nom_x'];
}

$id_unite = 1;
$prix_de_vente = 0 ;
if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}

$reference_x = "";
if (isset($_POST['reference_x'])) {
	$reference_x = $_POST['reference_x'];
}
$qt = 0 ;
if (isset($_POST['qt'])) {
	$qt = $_POST['qt'];
}
$note_x ="";
if (isset($_POST['note_x'])) {
  $note_x = $_POST['note_x'];
}

$state = "preparing";
//-----------INSERT INTO produit------------
$query_x = "INSERT INTO produit(id_unite, nom_x, prix_de_vente, reference_x, note_x, user_x) VALUES (?, ?, ?, ?, ?, ?)";

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
//-----------INSERT INTO stock_prep------------
//-------Reccuperation du valeur memo--------
        $description_date = "";
        $nom_du_client = "";
$reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 2');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $numero_stock_prep = $donnees['valeur_memo'];
         $nom_du_client = $donnees['note_memo'];
         $description_date = $donnees['description_date'];
          }
$reponse->closeCursor();
//-------------------------------------------
$query_c = "INSERT INTO stock_prep(numero_stock_prep, nom_du_client,description_date, id_x, qt, prix_de_vente, state,note,user_stock_prep) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_stock_prep, $nom_du_client, $description_date, $latest_id, $qt, $prix_de_vente, $state, $note_x,$username));	
//-----------------VERIFICATION PERTE DB------------------
  if ($future_id_x == $latest_id ) {
    # code...
    //echo "Valide";
    $msg = "Successfuly Saved";
    setcookie("msg_ok",$msg, time()+5);
  } else {
    # code...
    //echo "Invalide";
    $msg = "Failed";
    setcookie("msg_nok",$msg, time()+5);
  }
  
//-------------------------------------------
$q->closeCursor();
echo $prix_de_vente;
header("location: stock.php");
?>