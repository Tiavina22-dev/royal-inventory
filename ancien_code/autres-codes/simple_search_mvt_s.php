<?php
$key_word = "" ;
if (isset($_POST['key_word']))
{ $key_word = $_POST['key_word'];}

$point_de_vente = "";
if (isset($_POST['point_de_vente']))
{ $point_de_vente = $_POST['point_de_vente'];}

if (isset($_POST['checkbox'])) {
} else {
	setcookie("checkbox",'FALSE', time()+5);
}

if (isset($_POST['checkbox_stock_list'])) {
	setcookie("checkbox_stock_list",'TRUE', time()+5);
}

if (isset($_GET['id_x']))
{
	$key_word = $_GET['id_x'];
}
if (isset($_GET['shop']))
{
	$point_de_vente = $_GET['shop'];
	setcookie("analyse",'Activer', time()+5);
}
//echo $key_word;
//--------tracking user activity----------
    $activity = "Verification des produits | mvt";
    $username = "default";
    include('connect.php');
    session_start();
    if (isset($_SESSION['User_Name'])) 
    {
      $username = $_SESSION['User_Name'];
    }
    $type = 'tracking_activity';
    $query_activity = "INSERT INTO history(details, type, responsable) VALUES (?, ?, ?)";

    $query_activity = $bdd->prepare($query_activity);

    $query_activity->execute(array($activity, $type, $username));
    $query_activity->closeCursor();
    //----------------------------------------
//Set cookies to appear imediately preparation page
setcookie("key_word",$key_word, time()+5);
setcookie("point_de_vente",$point_de_vente, time()+5);
//Eviter la page de demarrage
$time_for_nouveau_stock_cookie = 1 ;
setcookie("time_for_nouveau_stock_cookie",$time_for_nouveau_stock_cookie, time()+5);
header("location: mvt_produit_s.php");
?>