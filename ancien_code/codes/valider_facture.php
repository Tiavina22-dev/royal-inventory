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

//INSERT ALL STOCK PREP TO THE mvt table
$query_get_all_commande = "SELECT * FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? ;";
$query_get_all_commande = $bdd->prepare($query_get_all_commande);

$query_get_all_commande->execute(array($username));
$no = 0;
while ($donnees = $query_get_all_commande -> fetch())
{	
	$no = $no + 1;
	$type_de_mvt = "facture";
	$id_x = $donnees['id_x'];
	$qt = $donnees['qt'];
	$nom_du_client = $donnees['nom_du_client'];
	$description_date = $donnees['description_date'];
	//Extract date in Mysql format
		$date1_mysql = null;
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '-', $date1);
        $date1 = str_replace('/ ', '-', $date1);
        $date1 = str_replace(' / ', '-', $date1);
        $date1 = str_replace(' -', '-', $date1);
        $date1 = str_replace(' - ', '-', $date1);
        $date1 = str_replace('/', '-', $date1);
        
        //echo $date1;
        //Convert date to english format for compare
        $date1_mysql = DateTime::createFromFormat('d-m-y', $date1);
        $date1_mysql = $date1_mysql -> format('Y-m-d');
        }
	//---------------------------
	$numero_commande_stock = $donnees['numero_stock_prep'];
	$ref_stock = $donnees['numero_stock_prep']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente']+0;
	$note = $donnees['note'];
	$user_stock_prep = $donnees['user_stock_prep'];

	//----------------UPDATE PRIX FOURNISSEUR-------------------
  //Check change is valide
  $verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
  $verification_prix = $bdd->prepare($verification_prix);
  $verification_prix->execute(array($id_x));
  $data = $verification_prix -> fetch();
  $difference_prix_fournisseur = $prix_unitaire - ($data['prix_fournisseur']+0);
  if ($difference_prix_fournisseur  != 0) {

    //------------ Update table produit -----------
    $query_p = "UPDATE produit
                SET prix_fournisseur = ?
                WHERE id_x = ?";

      $q = $bdd->prepare($query_p);

      $q->execute(array($prix_unitaire,$id_x));

      $q->closeCursor();
      //--------------Update table history--------------------------
      $before_change = ' | PU Fournisseur : '.($data['prix_fournisseur']+0).'</br>';
      $after_change = " | PU Fournisseur : <span class ='text-danger'><b>".$prix_unitaire.'</b></span></br>';
      $query_c = "INSERT INTO history(after_change,before_change, details,responsable,type) VALUES (?, ?, ?, ?,?)";

      $q = $bdd->prepare($query_c);

      $type = "Prix";
      $q->execute(array($after_change ,$before_change , $id_x, $username, $type));  

      $q->closeCursor();
    } //END IF
    $verification_prix -> closeCursor();
    //----------------UPDATE PRIX FOURNISSEUR END-------------------

	//-------------------------------------

	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt,Date_du_Journal_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$nom_du_client,$description_date,$numero_commande_stock,$ref_stock,$note,$user_stock_prep,$date1_mysql));	

	$q->closeCursor();
	//Change official prix
	/*
	echo $note_prix;
	echo "<br>";
	echo $difference_prix;
	echo "<br>";
	echo "<br>";
	echo $user_stock_prep;
	echo "<br>";
	echo $id_x;
	*/
}
$query_get_all_commande ->closeCursor();
	
//-----------DELETE COMMANDE------------
$query_c = "DELETE FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep = ? ";

	$q = $bdd->prepare($query_c);

	$q->execute(array($username));	

$q->closeCursor();
//------------------------------------
//set message on notification champ
$msg_validation = 'Facture de '.$nom_du_client.' valide avec succes';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande_stock, time()+5);
//back to commande page
header("location: facture.php");

?>