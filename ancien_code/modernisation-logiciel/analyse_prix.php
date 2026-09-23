<?php
$point_de_vente ="";
if (isset($_POST['point_de_vente'])) {
	$point_de_vente = $_POST['point_de_vente'];
}
if (isset($_POST['starting_date']))
{
	$starting_date = $_POST['starting_date'];

}
$checkbox = 0 ;
if (isset($_POST['checkbox'])) {
	$checkbox = $_POST['checkbox'];
}
if ($checkbox == TRUE) {
	setcookie("point_de_vente",$point_de_vente, time()+5);
	setcookie("starting_date",$starting_date, time()+5);
	setcookie("checkbox",'YES', time()+5);
	header("location: analyse_ecart_prix.php");
} else {
	setcookie("point_de_vente",$point_de_vente, time()+5);
	setcookie("starting_date",$starting_date, time()+5);
	header("location: analyse_ecart_prix.php");
}
?>