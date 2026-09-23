<?php
session_start();
if (isset($_SESSION['User_Name'])) 
{
  $usernm = $_SESSION['User_Name'];
}
else 
{
  //header("location: home.php");
  $usernm = "";
}
$img_path = "";
//Delete previews img file
if (isset($_SESSION['img_path'])) 
{
  $img_path = $_SESSION['img_path'];
  unlink($img_path);
}
else 
{
  //header("location: home.php");
}

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

  $file_name=$_FILES["uploadedimage"]["name"];
  $temp_name=$_FILES["uploadedimage"]["tmp_name"];
  $imgtype=$_FILES["uploadedimage"]["type"];
  $ext= GetImageExtension($imgtype);
  //Use new customized image name
  $imagename=date("d-m-Y")."-".time().$ext;
  //Use current image name
  //$imagename=$_FILES["uploadedimage"]["name"];
  $target_path = "img/".$imagename;
  

if(move_uploaded_file($temp_name, $target_path)) {
  include('connect.php');
  /*
  $query_upload="UPDATE user 
  SET 'img_path'='.$target_path.' 
  WHERE 'User_Name'= $usernm ";
  mysql_query($query_upload) or die("error in $query_upload == ----> ".mysql_error());
  */

  $sql = "UPDATE user 

  SET img_path=?

  WHERE User_Name=?";

  $q = $bdd->prepare($sql);

  $q->execute(array($target_path, $usernm));
  $q->closeCursor();
  
}else{

   exit("Error While uploading image on the server");
} 

$_SESSION['img_path']=$target_path;
header("location: profile.php");
}

?>;