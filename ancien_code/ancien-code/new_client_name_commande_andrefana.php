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
//-------Reccuperation du valeur MAX DANS MEMO--------
$reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 1');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur1 = $donnees['valeur_memo']+1;
          }
$reponse->closeCursor();
//-------Reccuperation du valeur MAX DANS MVT VENTE--------
$reponse = $bdd->prepare("SELECT MAX(numero_commande_stock) as numero FROM mvt_calc WHERE type_de_mvt = 'vente';");
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur2 = $donnees['numero']+1;
         //$valeur2 = 1746;
          }
$reponse->closeCursor();
//-------Reccuperation du valeur MAX DANS COMMANDE VENTE--------
$reponse = $bdd->prepare("SELECT MAX(numero_commande) as numero FROM vente_calc;");
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur3 = $donnees['numero']+1;
          }
$reponse->closeCursor();
//--------------------RESULTAT-----------------------------------
$valeur_memo = MAX($valeur1,$valeur2,$valeur3);
//--------------------RESULTAT-----------------------------------
$date_journal="";
$point_de_vente = "";

if (isset($_POST['point_de_vente']))
{
	$point_de_vente = $_POST['point_de_vente'];
}

if (isset($_POST['date_journal']))
{
		//Convert english format to french format
	$daty = $_POST['date_journal'];
	if (strlen($daty)>1)
	{//correct format
		$daty = str_replace('-', '/', $daty);
	    $daty = DateTime::createFromFormat('Y/m/d', $daty);
		$daty =$daty -> format('d/m/y');
		$date_journal = "Journal du ".$daty;
		if ($point_de_vente == "Morarano") {
		$date_journal = "Journal du ".$daty." (MORARANO)";
		}
		if ($point_de_vente == "Andrefana") {
		$date_journal = "Journal du ".$daty." (ANDREFANA)";
		}
	}
}

if ($point_de_vente == "Morarano" || $point_de_vente == "Andrefana") {
	$point_de_vente = "Ambaibo_Tole";
}

//-------------UPDATE MEMO-------------
$query_c = 'UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 1';
	$q = $bdd->prepare($query_c);

	$q->execute(array($point_de_vente, $valeur_memo, $date_journal));	

$q->closeCursor();
//-------------UPDATE USER MEMO-------------
$query_c = 'UPDATE user SET point_de_vente_vente_calc = ?, numero_vente_calc = ?, description_date_vente_calc = ? WHERE User_Name = ?';
	$q = $bdd->prepare($query_c);

	$q->execute(array($point_de_vente, $valeur_memo, $date_journal, $username));	

$q->closeCursor();
//Set cookies to appear imediately preparation page
if (strlen($date_journal)>1 AND strlen($point_de_vente)>1)
{$time_for_nouveau_commande_cookie = 1 ;
setcookie("time_for_nouveau_commande_cookie",$time_for_nouveau_commande_cookie, time()+5);
setcookie("point_de_vente_cookie",($point_de_vente." || ".$date_journal), time()+5);}
header("location: commande_andrefana.php");

?>