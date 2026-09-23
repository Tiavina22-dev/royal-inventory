<?php
$key_word = "" ;
if (isset($_POST['key_word']))
{
	$key_word = $_POST['key_word'];
}
//echo $key_word;
//--------tracking user activity----------
	include('connect.php');
    $activity = "Dans Saisi du Stock du Vente Mobile | search";
    $username = "default";
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
//Eviter la page de demarrage
$time_for_nouveau_stock_cookie = 1 ;
setcookie("time_for_nouveau_stock_cookie",$time_for_nouveau_stock_cookie, time()+5);
header("location: stock_andrefana.php");
?>