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
if (isset($_GET['id_chat'])) {
  $id_chat = $_GET['id_chat'];
}
//echo $id_x;
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

//setcookie("key_word",$id_x, time()+5);
//setcookie("img_msg",'IMAGE REMOVED', time()+5);
header("location: chat.php");

?>