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

$type_de_mvt = "stock";

$id_x = 0;
if (isset($_POST['id_x'])) {
	$id_x = $_POST['id_x'];
}


$inventory_date = '';
if (isset($_POST['GI_Date'])) {
	$inventory_date = $_POST['GI_Date'];
}


$qt = 0;
if (isset($_POST['qt'])) {
	$qt = abs($_POST['qt'])*(-1);
}


$qt_rectificative = 0;
if (isset($_POST['qt_rectificative'])) {
	$qt_rectificative = $_POST['qt_rectificative'] - $qt;
}

$nom_du_client = "";
if (isset($_POST['nom_client_fournisseur'])) {
	$nom_du_client = $_POST['nom_client_fournisseur'];
}

$prix_unitaire = 0 ;
if (isset($_POST['prix_unitaire'])) {
	$prix_unitaire = $_POST['prix_unitaire'];
}

$note = '';
if (isset($_POST['note'])) {
	$note = $_POST['note'];
}

$description_date = '';
$date1_mysql = '';
if (isset($_POST['date_journal'])) {
	//For description
	$description_date = $_POST['date_journal'];
	$description_date = str_replace('-', '/', $description_date);
	$description_date = DateTime::createFromFormat('Y/m/d', $description_date);
	$description_date = $description_date -> format('d/m/y');
	$description_date = "Abandon/Retours du ".$description_date;
	//For Mysql
	$date1_mysql = $_POST['date_journal'];

}

$description_date_cookie = '';
if (isset($_POST['description_date'])) {
	$description_date_cookie = $_POST['description_date'];
}

$no_activite_cookies = '';
if (isset($_POST['no_activite'])) {
	$no_activite_cookies = $_POST['no_activite'];
}


//-------Reccuperation du valeur MAX DANS MEMO--------
$valeur1 = 0 ;
$reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 2');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur1 = $donnees['valeur_memo']+1;
          }
$reponse->closeCursor();
//-------Reccuperation du valeur MAX DANS MVT VENTE--------
$valeur2 = 0 ;
$reponse = $bdd->prepare("SELECT MAX(numero_commande_stock) as numero FROM mvt WHERE type_de_mvt = 'stock';");
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur2 = $donnees['numero']+1;
         //$valeur2 = 1746;
          }
$reponse->closeCursor();
//-------Reccuperation du valeur MAX DANS COMMANDE VENTE--------
$valeur3 = 0;
$reponse = $bdd->prepare("SELECT MAX(numero_commande) as numero FROM stock_prep;");
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
         $valeur3 = $donnees['numero']+1;
          }
$reponse->closeCursor();
//--------------------RESULTAT-----------------------------------
$numero_commande_stock = MAX($valeur1,$valeur2,$valeur3);
$ref_stock = $numero_commande_stock.'-1';
//--------------------RESULTAT-----------------------------------

//INSERT RETOURS TO THE mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,Date_du_Journal_mvt,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'OFF')";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$username,$date1_mysql));	

	$q->closeCursor();
//MODIFY APPROPRIATE GI
	//MODIFY QT Rectificative COMMUNE

		  $query_qr = "UPDATE mvt
		            SET prix_aparafa = ?
		            WHERE id_x = ? AND nom_client_fournisseur = ? AND Date_du_Journal_mvt = ?";

		  $qr = $bdd->prepare($query_qr);

		  $qr->execute(array($qt_rectificative, $id_x,$nom_du_client,$inventory_date)); 

		  $qr->closeCursor();
		  //----------------------
	
//set message on notification champ
setcookie("nom_client_fournisseur",$nom_du_client, time()+5);
setcookie("description_date",$description_date_cookie, time()+5);
setcookie("no_activite",$no_activite_cookies, time()+5);
echo $nom_du_client;
echo '<br>';
echo $description_date_cookie;
echo '<br>';
echo $no_activite_cookies;
//back to commande page
header("location: stock_recap_details.php");

?>