<?php

//-------------------------------------------

if (isset($_GET['page'])) {
  $page = $_GET['page'];
  $page = $page + 1;
  $debut = $_GET['debut'];
  $fin = $_GET['fin'];
  $point_de_vente = $_GET['point_de_vente'];
  if ($point_de_vente == 'TOUTES LES POINTS DE VENTES') {
  	setcookie("page",$page, time()+5);
  } else
  {
  	setcookie("page",$page, time()+5);
  	setcookie("point_de_vente",$point_de_vente, time()+5);
  	setcookie("debut_date",$debut, time()+5);
  	setcookie("fin_date",$fin, time()+5);
  }
  
}

header("location: vente_recap.php");

?>