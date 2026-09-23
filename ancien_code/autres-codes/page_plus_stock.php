<?php

//-------------------------------------------

if (isset($_GET['page'])) {
  $page = $_GET['page'];
  $page = $page + 1;
  setcookie("page",$page, time()+5);
}

header("location: stock_recap.php");

?>