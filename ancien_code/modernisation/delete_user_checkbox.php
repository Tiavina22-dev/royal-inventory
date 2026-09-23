<?php
// configuration
include('connect.php');
//Recuperer tous les valeurs par ID Numbrer (Use _GET methode to get url parameter)
if (isset($_POST['user_id_array']))
{

	$ids=$_POST['user_id_array'];

	foreach ($ids as $id) 
	{
		
		$sql = "DELETE FROM user WHERE Id_User=?";

		$q = $bdd->prepare($sql);

		$q->execute(array($id));
		$q->closeCursor();
	}


}


//Default values for Status column History Registred or Unregistred

	
	//header("location: index");

?>