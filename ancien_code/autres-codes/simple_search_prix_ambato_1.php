<?php
$key_word = "" ;
if (isset($_POST['key_word']))
{
	$key_word = $_POST['key_word'];
}
//echo $key_word;
//Set cookies to appear imediately preparation page
setcookie("key_word",$key_word, time()+5);
//Eviter la page de demarrage
$time_for_nouveau_stock_cookie = 1 ;
setcookie("time_for_nouveau_stock_cookie",$time_for_nouveau_stock_cookie, time()+5);
header("location: prix_ambato_1.php");
?>