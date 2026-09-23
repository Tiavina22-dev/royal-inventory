<?php

// configuration

include('connect.php');

// new data
if (isset($_POST['fullname'])) {
	$fullname = $_POST['fullname'];
}
if (isset($_POST['initials'])) {
	$initials = $_POST['initials'];
}
if (isset($_POST['currentname'])) {
	$currentname = $_POST['currentname'];
}
if (isset($_POST['username'])) {
	$username = $_POST['username'];
}
if (isset($_POST['departemnt'])) {
	$departemnt = $_POST['departemnt'];
}
if (isset($_POST['sex'])) {
	$sex = $_POST['sex'];
}
if (isset($_POST['note'])) {
	$note = $_POST['note'];
}
if (isset($_POST['password'])) {
	$password = $_POST['password'];
}

// Variable for update & delete
if (isset($_POST['memids'])) {
	$id = $_POST['memids'];
}

if (isset($_POST['action'])) {
	$action = $_POST['action'];
}
$permission = "N";
$img_path = "img/default_img/default_pdp.png";
// Count if username already exist on the table

$reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name LIKE ?');
 $reponse->execute(array($username));
 $username_count=$reponse->rowCount ();

// query
if ($action == "Delete Data") 
{
	# code...
	$sql = "DELETE FROM user

	WHERE Id_User=?";

	$q = $bdd->prepare($sql);

	$q->execute(array($id));
	//echo "Delete Data";

} 

elseif ($action == "Update Data") 
{
 	# code...
 	echo "Update Data";
 	$sql = "UPDATE user 

	SET Full_Name=?, Initials=?, Current_Name=?, User_Name=?, Departement=?, Sex=?, Note=?

	WHERE Id_User=?";

	$q = $bdd->prepare($sql);

	$q->execute(array($fullname,$initials,$currentname,$username,$departemnt,$sex,$note,$id));
 } 

 elseif ($action == "Save Data") 
 {
 	# code...
 	$sql = "INSERT INTO user(Full_Name, Initials, Current_Name, User_Name, Departement, Sex, Note,Permission,img_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($sql);

	$q->execute(array($fullname,$initials,$currentname,$username,$departemnt,$sex,$note,$permission,$img_path));

 }	
 elseif ($action == "Register") 
 {
 	# test if username already exist

 	if ($username_count>0) {
 		# code...
 		$msg="Existing Username, try anyone";
 		setcookie("msg_register",$msg, time()+30);
 		setcookie("username_count",$username_count, time()+30);
 	} else {
 		 
 		 $sql = "INSERT INTO user(Full_Name, Initials, Current_Name, User_Name, Departement, Sex, Password, Permission,img_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($sql);

	$q->execute(array($fullname,$initials,$currentname,$username,$departemnt,$sex,$password,$permission,$img_path));
	
	$msg="Congratulation! Thank you to register!</br> To have permission for singin, send \"".$username."\" to the responsible.";
 	setcookie("username_count",$username_count, time()+30);
 	setcookie("msg_register",$msg, time()+30);
 	}
 	

 }	

//retour vers page principal
header("location: gerer_user.php");

?>