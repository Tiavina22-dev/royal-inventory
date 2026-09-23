<?php
include('connect.php');
session_start();
if (isset($_SESSION['User_Name'])) 
{
  $usernm = $_SESSION['User_Name'];
}
else 
{
  //header("location: home.php");
  $usernm = "default";
}

$id_x = 0;
if (isset($_GET['id_x'])) {
  $id_x = $_GET['id_x'];
}
//echo $id_x;
//GET REAL IMG PATH
$query_path_img_x = "SELECT * FROM produit WHERE id_x LIKE ?";
$q = $bdd->prepare($query_path_img_x);
$q->execute(array("%".$id_x."%"));
$donnees = $q -> fetch();
$image_path_x = "";
$image_path_x = $donnees['img_path_x'];
$q->closeCursor();

  if (strlen($image_path_x) != 0)
    {
      //DELETE PREVIEWS IMG FILE
      unlink($image_path_x);
      //DELETE PATH ON DB
      $sql = "UPDATE produit 
      SET img_path_x = '', user_x = ?
      WHERE id_x =?";
      $q = $bdd->prepare($sql);
      $q->execute(array($usernm, $id_x));
      $q->closeCursor();
    }

setcookie("img_msg",'IMAGE REMOVED', time()+5);
header("location: stock.php");

?>