<?php
include('connect.php');

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

/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
//-------Reccuperation du valeur MAX DANS MEMO--------
$reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 2');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur1 = $donnees['valeur_memo']+1;
          }
$reponse->closeCursor();
//-------Reccuperation du valeur MAX DANS MVT VENTE--------
$reponse = $bdd->prepare("SELECT MAX(numero_commande_stock) as numero FROM mvt WHERE type_de_mvt = 'stock';");
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur2 = $donnees['numero']+1;
         //$valeur2 = 1746;
          }
$reponse->closeCursor();
//-------Reccuperation du valeur MAX DANS COMMANDE VENTE--------
$reponse = $bdd->prepare("SELECT MAX(numero_commande) as numero FROM stock_prep;");
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur3 = $donnees['numero']+1;
          }
$reponse->closeCursor();
//--------------------RESULTAT-----------------------------------
$valeur_memo = MAX($valeur1,$valeur2,$valeur3);
//--------------------RESULTAT-----------------------------------
$description_date = "";
$point_de_vente = "";

if (isset($_POST['point_de_vente']))
{
	$point_de_vente = $_POST['point_de_vente'];
}


if (isset($_POST['description_date']))
{
		//Convert english format to french format
	$daty = $_POST['description_date'];
	if (strlen($daty)>1) {
	//correct format
	$daty = str_replace('-', '/', $daty);
    $daty = DateTime::createFromFormat('Y/m/d', $daty);
	$daty =$daty -> format('d/m/y');
	$description_date = "Reduit/Retirer du ".$daty;
	}
}

//-------------UPDATE MEMO-------------
$query_c = 'UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 2';
	$q = $bdd->prepare($query_c);

	$q->execute(array($point_de_vente, $valeur_memo,$description_date));	

$q->closeCursor();
//-------------UPDATE USER INFO-------------
$query_c = 'UPDATE user SET stock_client_name = ?, stock_numero_commande = ?, stock_description_date = ? WHERE User_Name = ?';
	$q = $bdd->prepare($query_c);

	$q->execute(array($point_de_vente, $valeur_memo,$description_date,$username));	

$q->closeCursor();
//Set cookies to appear imediately preparation page
if (strlen($description_date)>1 AND strlen($point_de_vente)>1)
{
$time_for_nouveau_stock_cookie = 1 ;
setcookie("time_for_nouveau_stock_cookie",$time_for_nouveau_stock_cookie, time()+5);
setcookie("point_de_vente_cookie",($point_de_vente." || ".$description_date), time()+5);
}
header("location: stock_reduit.php");

?>