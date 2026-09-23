<?php
if (isset($_POST['point_de_vente'])) {
  $point_de_vente = $_POST['point_de_vente'];
}
$key_word = "Notification du nouveau prix" ;
//Set cookies to appear imediately preparation page
setcookie("key_word",$key_word, time()+5);
setcookie("point_de_vente",'Ambato_Tantely', time()+5);
header("location: prix_ambato_1.php");
?>