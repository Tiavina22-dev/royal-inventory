<?php
$details_date = "YES" ;
//echo $key_word;
//Set cookies to appear imediately preparation page
setcookie("details_date",$details_date, time()+5);
header("location: controle_ambato.php");
?>