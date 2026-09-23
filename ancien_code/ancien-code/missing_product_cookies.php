<?php
$point_de_vente ="";
if (isset($_POST['point_de_vente'])) {
	$point_de_vente = $_POST['point_de_vente'];
}

if (isset($_POST['checkbox'])) {
	$checkbox = $_POST['checkbox'];
}
if ($checkbox == TRUE) {
setcookie("details_date","YES", time()+5);
}
	setcookie("point_de_vente_cookies",$point_de_vente, time()+5);
	header("location: controle_ambato.php");
?>