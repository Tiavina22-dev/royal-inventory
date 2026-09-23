<?php

if (isset($_POST['permission_id_array'])) 
{
	$permission_id_array = $_POST['permission_id_array'];
	include('connect.php');
foreach ($permission_id_array as $id) 
	{
  $sqlh = "UPDATE user SET Permission='Y' WHERE Id_User=?";

  $h = $bdd->prepare($sqlh);

  $h->execute(array($id));
  $h->closeCursor();
  
	}
}
?>