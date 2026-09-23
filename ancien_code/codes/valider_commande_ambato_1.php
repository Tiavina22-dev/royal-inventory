<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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
//GET GENERAL_NOTE
$note_general = "";
if (isset($_POST['note_general'])) {
	$note_general = $_POST['note_general'];
}
$resolution = 0;
if (isset($_POST['resolution'])) {
	$resolution = abs($_POST['resolution'])*(-1);
}
$versement = "";
if (isset($_POST['versement'])) {
	$versement = $_POST['versement'];
}
$path = "";
if (isset($_POST['path'])) {
	$path = $_POST['path'];
}
$point = "";
if (isset($_POST['point'])) {
	$point = $_POST['point'];
}
$mihoatra = "";
if (isset($_POST['mihoatra'])) {
	$mihoatra = abs($_POST['mihoatra']);
}
//INSERT COMMANDE FOR CURRENT SESSION TO THE mvt table
$query_current_commande ="SELECT id_commande,numero_commande,nom_du_client,nom_x,commande.id_x as id_x,description_date,reference_x,note_commande,qt,commande.prix_de_vente as prix_de_vente,commande.prix_aparafa as prix_aparafa, (commande.prix_de_vente)*qt as sous_total,(commande.prix_aparafa)*qt as sous_total_aparafa FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user = ?;";
$query_get_current_commande = $bdd->prepare($query_current_commande);

 $query_get_current_commande->execute(array($username));
//GET NUMERO ACTIVITY
$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $reponse->execute(array($username));
        $activity_no = 0;
       while ($donnees = $reponse->fetch())
         {
          //Numero de commande memorise dans user table
         $activity_no = $donnees['numero_commande'];
          }
$reponse->closeCursor();
//Query to liste depense of actual activity ID
$query_depense ="SELECT * FROM depense WHERE activity_no = ? ";
$query_depense_list = $bdd->prepare($query_depense);

 $query_depense_list->execute(array($activity_no));
 $somme_depense = 0;
 $somme_depense_aparafa = 0;
 while ($donnees = $query_depense_list -> fetch())
{
$somme_depense = $donnees['montant']+ $somme_depense;
$somme_depense_aparafa = $donnees['depense_aparafa']+$somme_depense_aparafa;
}
$all_depense = $somme_depense + $somme_depense_aparafa;
 //-----------------------
$no = 0;
$somme = 0;
$somme_aparafa = 0;
$history ="";
while ($donnees = $query_get_current_commande -> fetch())
{
	$no = $no + 1;
	$type_de_mvt = "vente";
	$id_x = $donnees['id_x'];
	$qt = -($donnees['qt']);
	$nom_du_client = $donnees['nom_du_client'];
	$description_date = $donnees['description_date'];
	$note = $donnees['note_commande'];
	$numero_commande = $donnees['numero_commande'];
	$ref_commande = $donnees['numero_commande']."-".$no;
	$prix_unitaire = $donnees['prix_de_vente'];
	$prix_aparafa = $donnees['prix_aparafa'];
	//variable history for recap_vente
	$history = $history."=> ".$no." # ".$donnees['nom_x']." | ".$note." # qt: ".$donnees['qt']." # PU: ".$prix_unitaire." # MT: ".$donnees['sous_total']." # A/FA: ".$donnees['sous_total_aparafa']." #"."\r";
	$somme_aparafa = $donnees['sous_total_aparafa']+$somme_aparafa;
	$somme = $donnees['sous_total']+$somme;
	//Query to insert one by one in mvt table
	$query_c = "INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire,prix_aparafa,nom_client_fournisseur,description_date,numero_commande_stock,ref_commande_stock,note,user_mvt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($type_de_mvt, $id_x, $qt, $prix_unitaire,$prix_aparafa,$nom_du_client,$description_date,$numero_commande,$ref_commande,$note,$username));	

	$q->closeCursor();
}
//Add TOTAL AND VERSEMENT on history
$difference_aparafa = 0;
if ($nom_du_client == 'Amparafa') {
	$difference_aparafa = $somme_aparafa-$somme;
}
/*
$difference_aparafa_ambiny = $difference_aparafa - $somme_depense_aparafa;
if ($difference_aparafa < 0) {
	$difference_aparafa = 0;
}

if ($difference_aparafa_ambiny < 0) {
	$difference_aparafa_ambiny = 0;
}
*/

$reste_versement = $somme - $all_depense;

$history = $history."\r"."----------------------------------------------------------------"."\r"."----------TL VENTE:".$somme." Ar----------"."\r"."----------------------------------------------------------------"."\r"."-----------------SPECIAL AMPARAFA------------------"."\r"." IVAROTANY TL:".$somme_aparafa." Ar "." | DIFF:".$difference_aparafa." Ar | NALAINY:".$somme_depense_aparafa."\r"."------------------------DEPENSE---------------------------"."\r"."DEPENSE ROYAL:".$somme_depense." Ar | VOLA NALAIN NY APARAFA:".$somme_depense_aparafa." Ar | TL DEPENSE:".$all_depense." Ar"."\r"."-----------------------------------------------------------------"."\r"."--------NET POUR ROYAL:".$versement." Ar-------"."\r"."-----------------------------------------------------------------";
//--------QUERY TO INSERT ON RECAP_VENT-------
$status ="NON_RESOLU";
	$query_c = "INSERT INTO recap_vente(no_activite, nb_ligne, Montant,difference_aparafa,resolution,mihoatra,history,note_general,c_point,directory,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_commande,$no,$versement,$difference_aparafa,$resolution,$mihoatra,$history,$note_general,$point,$path,$status));	

	$q->closeCursor();
	//echo "NC".$numero_commande."No".$no."SOM".$somme."DIFF:".$difference_aparafa."Hist".$history."note_general".$note_general."Point".$point."Path:".$path;
//-----------DELETE COMMANDE------------
include('anuler_commande_1.php');
//set message on notification champ
$msg_validation = 'Commande de '.$nom_du_client.' valide avec succes | ';
setcookie("msg_validation",$msg_validation, time()+5);
setcookie("numero_commande",$numero_commande, time()+5);
//back to commande page
header("location: commande_ambato_1.php");

?>