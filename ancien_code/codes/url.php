<?php 
//FULL URL
$url = "http".(($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
echo 'URL : '.$url;;

$url = basename($_SERVER['PHP_SELF']);
//$url = 'home.php';
//header("location: ".$url);
//header("location: php.php");
echo 'FILE NAME : '.$url;
?>
