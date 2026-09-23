<?php
$key_word = "" ;
if (isset($_POST['key_word']))
{ $key_word = $_POST['key_word'];}

if (strlen($key_word) == 0) {
	$key_word = "All product" ;
}

$point_de_vente = "";
if (isset($_POST['point_de_vente']))
{ $point_de_vente = $_POST['point_de_vente'];}

//echo $key_word;
//Set cookies to appear imediately preparation page
setcookie("key_word",$key_word, time()+5);
setcookie("point_de_vente",$point_de_vente, time()+5);
//Eviter la page de demarrage
$time_for_nouveau_stock_cookie = 1 ;
setcookie("time_for_nouveau_stock_cookie",$time_for_nouveau_stock_cookie, time()+5);
header("location: mvt_produit_report.php");
?>