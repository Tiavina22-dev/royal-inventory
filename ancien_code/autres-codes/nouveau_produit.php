<?php
include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
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

if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}

if (isset($_POST['id_x'])) {
	$id_x = $_POST['id_x'];
}

$fournisseur = 0;
if (isset($_POST['fournisseur'])) {
	$fournisseur = $_POST['fournisseur'];
}

$pourcentage=0;
if (isset($_POST['pourcentage'])) {
	$pourcentage = $_POST['pourcentage'];
}

$benefice=0;
if (isset($_POST['benefice'])) {
	$benefice = $_POST['benefice'];
}

if (isset($_POST['reference_x'])) {
  $reference_x = $_POST['reference_x'];
}
if (isset($_POST['note_x'])) {
  $note_x = $_POST['note_x'];
}

//-----------INSERT INTO produit------------
//-------------------------------------------
$id_unite = 1;
$id_cat = 0;
// IMAGE FUNCTION-------------
function GetImageExtension($imagetype)
     {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }
//----------------------------

if (!empty($_FILES["uploadedimage"]["name"])) {

  $file_name=$_FILES["uploadedimage"]["name"];
  $temp_name=$_FILES["uploadedimage"]["tmp_name"];
  $imgtype=$_FILES["uploadedimage"]["type"];
  $ext= GetImageExtension($imgtype);
  //Use new customized image name
  $imagename="img_".$reference_x.$ext;
  //Use current image name
  //$imagename=$_FILES["uploadedimage"]["name"];
  $target_path = "img_x/".$imagename;

if(move_uploaded_file($temp_name, $target_path)) {
$query_c = "INSERT INTO produit (id_cat,id_unite,nom_x,prix_de_vente,prix_fournisseur,benefice,reference_x,note_x,user_x,img_path_x) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_cat, $id_unite,$nom_x,$prix_de_vente,$fournisseur,$benefice,$reference_x,$note_x,$username,$target_path));	

$q->closeCursor();
}else{
exit("Error While uploading image on the server");
}
$key_word = $reference_x;
setcookie("key_word",$key_word, time()+5);
header("location: stock_general.php");
} else {
//---WITHOUT IMAGE
	$query_c = "INSERT INTO produit (id_cat,id_unite,nom_x,prix_de_vente,prix_fournisseur,benefice,reference_x,note_x,user_x) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($id_cat, $id_unite,$nom_x,$prix_de_vente,$fournisseur,$benefice,$reference_x,$note_x,$username));	

$q->closeCursor();
$key_word = $reference_x;
setcookie("key_word",$key_word, time()+5);
header("location: stock_general.php");

}
?>