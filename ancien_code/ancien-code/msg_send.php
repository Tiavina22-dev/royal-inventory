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
//Handle img
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
$target_path = "";
if (!empty($_FILES["uploadedimage"]["name"])) {

  $file_name=$_FILES["uploadedimage"]["name"];
  $temp_name=$_FILES["uploadedimage"]["tmp_name"];
  $imgtype=$_FILES["uploadedimage"]["type"];
  $ext= GetImageExtension($imgtype);
  //Use new customized image name
  $imagename=date("d-m-Y")."-".time().$ext;
  //Use current image name
  //$imagename=$_FILES["uploadedimage"]["name"];
  $target_path = "img_msg/".$imagename;
  

if(move_uploaded_file($temp_name, $target_path)) {
  /*
  $query_upload="UPDATE user 
  SET 'img_path'='.$target_path.' 
  WHERE 'User_Name'= $usernm ";
  mysql_query($query_upload) or die("error in $query_upload == ----> ".mysql_error());
  

  $sql = "UPDATE chat 

  SET path_photo = ?

  WHERE User_Name=?";

  $q = $bdd->prepare($sql);

  $q->execute(array($target_path, $usernm));
  $q->closeCursor();
  */
  
}else{

   exit("Error While uploading image on the server");
   $target_path = "";
} 

}//End if img
//get Produit name
$destinataire =0;
if (isset($_POST['destinataire'])) {
	$destinataire = $_POST['destinataire'];
}

$msg = "";
if (isset($_POST['msg'])) {
	$msg = date('d/m/y h:i:s').'<br>'.$_POST['msg'];
}

$status = "new";
//QUERY TO GET FROM ID
$from_id =0;
$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $reponse->execute(array($username));

       while ($donnees = $reponse->fetch())
         {
         $from_id = $donnees['Id_User'];
          }
$reponse->closeCursor();
if ($destinataire == "all") {

  $reponse = $bdd->prepare("SELECT * FROM user WHERE (Departement ='comptable' OR Departement = 'Aide comptable' OR Departement = 'Accounting') AND User_Name != ?");
        $reponse->execute(array($username));

       while ($donnees = $reponse->fetch())
         {
             $destinataire = $donnees['Id_User'];
             //-----------INSERT INTO CHAT------------
             if (strlen($target_path)>0) {
              $query_c = "INSERT INTO chat(from_id, to_id, msg, status,path_photo) VALUES (?, ?, ?, ?, ?)";

              $q = $bdd->prepare($query_c);

              $q->execute(array($from_id, $destinataire,$msg, $status, $target_path));
             }else{
              $query_c = "INSERT INTO chat(from_id, to_id, msg, status) VALUES (?, ?, ?, ?)";

              $q = $bdd->prepare($query_c);

              $q->execute(array($from_id, $destinataire,$msg, $status));

             }
                

              $q->closeCursor();
          }
      $reponse->closeCursor();
} else{
//-----------INSERT INTO CHAT------------
  if (strlen($target_path)>0) {
  $query_c = "INSERT INTO chat(from_id, to_id, msg, status,path_photo) VALUES (?, ?, ?, ?, ?)";

  $q = $bdd->prepare($query_c);

  $q->execute(array($from_id, $destinataire,$msg, $status, $target_path));  
    
  }else{
  $query_c = "INSERT INTO chat(from_id, to_id, msg, status) VALUES (?, ?, ?, ?)";

  $q = $bdd->prepare($query_c);

  $q->execute(array($from_id, $destinataire,$msg, $status));  

  }

$q->closeCursor();
setcookie("selected",$destinataire, time()+5);
}
/*
echo $from_id;
echo "<br>";
echo $destinataire;
echo "<br>";
echo $msg;
echo "<br>";
echo $status;

echo $target_path;
*/
header("location: chat.php");

?>