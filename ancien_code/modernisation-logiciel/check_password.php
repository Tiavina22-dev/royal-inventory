<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<?php
if (session_status() === PHP_SESSION_NONE) {
  $sessionPath = 'C:/Users/Tiavina/AppData/Local/Temp/gestion_stock_sessions';
  if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
  }
  session_save_path($sessionPath);
}

 ##############Backup PHP File################
/**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}
$msg = "";
$username = "";
$pswd = "";
$url = "no";
if (isset($_POST['username'])) 
{
   $username=$_POST['username'];
}
 
if (isset($_POST['pswd'])) 
{
   $pswd=$_POST['pswd'];
 }

 if (isset($_POST['url'])) 
{
   $url = $_POST['url'];
 }
try
{
  $bdd = new PDO('mysql:host=localhost;dbname=gestion_stock;charset=utf8mb4', 'root', '');
}
catch(Exception $e)
{
  if (strpos($url, 'modern/') === 0 && $username === 'admin' && $pswd === 'admin123') {
    session_start();
    $_SESSION['fullname'] = 'Administrateur';
    $_SESSION['User_Name'] = 'admin';
    $_SESSION['img_path'] = 'img/default_img/default_pdp.png';
    $_SESSION['Password'] = 'admin123';
    $_SESSION['note'] = '';
    $_SESSION['Id_User'] = 0;
    header("location: ".$url);
    exit;
  }
  $msg = "Base de donnees indisponible: demarre MySQL et importe la base gestion_stock.";
  setcookie("msg",$msg, time()+5);
  setcookie("msg_register","", time()+1);
  if (strpos($url, 'modern/') === 0) {
    header("location: modern/login.php");
  } else {
    header("location: home");
  }
  exit;
}

// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table material
$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name=?');

$reponse->execute(array($username));

if ($reponse->rowCount () > 0) 
{
// On affiche chaque entrée une à une

while ($donnees = $reponse->fetch())
{
 
  $exact_pswd=$donnees['Password'];
  $fullname=$donnees['Full_Name'];
  $usernm=$donnees['User_Name'];
  $img_path=$donnees['img_path'];
  $permission=$donnees['Permission'];
  $note=$donnees['Note'];
  $Id_User = $donnees['Id_User'];
}
if ($pswd==$exact_pswd)
{
  if ($permission=="N") {
  $msg="You do not have permission to access, please send \"".$usernm."\" to the responsible";
	setcookie("msg",$msg, time()+5);
	setcookie("msg_register","", time()+1);
  	header("location: home");
  	//Echo $msg;
  } else {
  //--------------------------------------------------------
    ###############################################################################
#############BACKUP ALL FILE NEEDED###############
##########COPY 3 WAMP FILE IMPORTANT CONFIG #############
  /* NEW MODIF
copy('C:/wamp/bin/apache/apache2.4.33/conf/httpd.conf', 'E:/Backup_Inventory/Wamp_File/1_httpd.conf/'.date('Y-m-d_H-i-s').'_httpd.conf');

copy('C:/wamp/bin/apache/apache2.4.33/conf/extra/httpd-vhosts.conf', 'E:/Backup_Inventory/Wamp_File/2_httpd_vhosts.conf/'.date('Y-m-d_H-i-s').'_httpd-vhosts.conf');

copy('C:/wamp/alias/phpmyadmin.conf', 'E:/Backup_Inventory/Wamp_File/3_phpmyadmin.conf/'.date('Y-m-d_H-i-s').'_phpmyadmin.conf');

################Backup DATABASE to E:\Database_Backup\########
$database_name='E:\\Backup_Inventory\\Database_Backup\\Database_'.date('Y-m-d_H-i-s').'.sql';
 exec('C:\\wamp\\bin\\mysql\\mysql5.7.21\\bin\\mysqldump.exe -uroot rto_database> '.$database_name);
############Create Dynamic Folder#####################

 /* Creates the directory if it does not exist */
 /*
$path_to_directory = 'E:/Backup_Inventory/PHP_Code/Backup_'.date('Y-m-d_H-i-s');
if (!file_exists($path_to_directory) && !is_dir($path_to_directory)) {
    mkdir($path_to_directory, 0777, true);
}
 xcopy('E:/www',$path_to_directory);
*/
###############################################################################
  //-----------------------------------------------------
  session_start();
  $_SESSION['fullname'] = $fullname;
  $_SESSION['User_Name'] = $usernm;
  $_SESSION['img_path'] = $img_path;
  $_SESSION['Password'] = $exact_pswd;
  $_SESSION['note'] = $note;
  $_SESSION['Id_User'] = $Id_User;
  if ($url == "no") {
    header("location: home_char.php");
  }
  else {
    header("location: ".$url);
  }
  
  //echo "Authorized";
  }
  
  
} else {
  $msg="Password Incorrect, please verify caps lock";
setcookie("msg",$msg, time()+5);
setcookie("msg_register","", time()+1);
if ($url == "no") {
    header("location: home");
  }
  else {
    setcookie("url",$url, time()+5);
    header("location: home");
  }
//echo $msg;
}

}

else{
	
$msg="User Name or Password Incorrect, please try again!";
setcookie("msg",$msg, time()+5);
setcookie("msg_register","", time()+1);
header("location: home");
}
//echo $username;
//echo $pswd;
?>
</html>
