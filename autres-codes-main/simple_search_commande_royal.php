<?php
$key_word = "" ;
if (isset($_POST['key_word']))
{
	$key_word = $_POST['key_word'];
}
$checkbox = 0 ;
if (isset($_POST['checkbox'])) {
	$checkbox = $_POST['checkbox'];
}
if ($checkbox == TRUE) {
	$checkbox = 'TRUE';
} else {
	$checkbox = 'FALSE';
}
//echo $key_word;
//Set cookies to appear imediately preparation page
setcookie("key_word",$key_word, time()+5);
setcookie("checkbox",$checkbox, time()+5);
//Eviter la page de demarrage
$time_for_nouveau_commande_cookie= 1 ;
setcookie("time_for_nouveau_commande_cookie",$time_for_nouveau_commande_cookie, time()+5);
header("location: commande_royal.php");
?>