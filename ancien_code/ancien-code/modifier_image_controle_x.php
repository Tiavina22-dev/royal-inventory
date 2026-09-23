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
if (isset($_POST['id_x'])) {
  $id_x = $_POST['id_x'];
}
echo $id_x;
//GET REAL IMG PATH
$query_path_img_x = "SELECT * FROM produit WHERE id_x LIKE ?";
$q = $bdd->prepare($query_path_img_x);
$q->execute(array("%".$id_x."%"));
$donnees = $q -> fetch();
$image_path_x = "";
$image_path_x = $donnees['img_path_x'];
$q->closeCursor();               

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
   
   
   
if (!empty($_FILES["uploadedimage"]["name"])) {
  //DELETE PREVIEWS IMG FILE
  if (strlen($image_path_x) != 0) {unlink($image_path_x);}

  $file_name=$_FILES["uploadedimage"]["name"];
  $temp_name=$_FILES["uploadedimage"]["tmp_name"];
  $imgtype=$_FILES["uploadedimage"]["type"];
  $ext= GetImageExtension($imgtype);
  //Use new customized image name
  $imagename="img"."_".$id_x.$ext;
  //Use current image name
  //$imagename=$_FILES["uploadedimage"]["name"];
  $target_path = "img_x/".$imagename;
  

if(move_uploaded_file($temp_name, $target_path)) {
  /*
  $query_upload="UPDATE user 
  SET 'img_path'='.$target_path.' 
  WHERE 'User_Name'= $usernm ";
  mysql_query($query_upload) or die("error in $query_upload == ----> ".mysql_error());
  */

  $sql = "UPDATE produit 

  SET img_path_x = ?, user_x = ?

  WHERE id_x =?";

  $q = $bdd->prepare($sql);

  $q->execute(array($target_path, $usernm, $id_x));
  $q->closeCursor();
  
}else{

   exit("Error While uploading image on the server");
} 
setcookie("key_word",$id_x, time()+5);
setcookie("img_msg",'IMAGE SAVED', time()+5);
header("location: controle_x.php");
} else { //Empty img file
//------------IMAG FAILED TO SAVE--------
setcookie("key_word",$id_x, time()+5);
setcookie("img_msg",'PLEASE CHOOSE AN IMAGE FILE', time()+5);
header("location: controle_x.php");
//---------------------------------------
}//else Empty img file
?>