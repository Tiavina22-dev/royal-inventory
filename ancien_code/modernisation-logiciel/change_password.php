<?php 
if (isset($_POST['oldpswd']))
{
  $oldpswd = $_POST['oldpswd'];
}else
{
  //echo "Problem oldpswd";
}
if (isset($_POST['newpswd1']))
{
  $newpswd1 = $_POST['newpswd1'];
}else
{
  $msg="We do not receive your pswd";
  setcookie("msg",$msg, time()+5);
  header("location: home.php");
}
if (isset($_POST['newpswd2']))
{
  $newpswd2 = $_POST['newpswd2'];
}else
{
  $msg="We do not receive your pswd confirmation!";
  setcookie("msg",$msg, time()+5);
  header("location: profile.php");
}
if ($newpswd1 == "" || $newpswd2 == "") {
$msg="Mot de passe vide inacceptable!";
  setcookie("msg",$msg, time()+5);
  header("location: profile.php");
}
else
{
session_start();
if (isset($_SESSION['Password']))
{
  $pswd=$_SESSION['Password'];
}
else 
{
 echo "your pswd is".$_SESSION['pswd'];
}
if (isset($_SESSION['User_Name'])) {
 $usernm = $_SESSION['User_Name'];
}
else 
{
  $msg="Sorry!You have a Username Problem, please try again";
  setcookie("msg",$msg, time()+5);
  header("location: profile.php");
}
if (($oldpswd==$pswd) AND ($newpswd1==$newpswd2)) 
{
  //echo "Password Exact";
  include('connect.php');
  $sql = "UPDATE user 

  SET Password=?

  WHERE User_Name=?";

  $q = $bdd->prepare($sql);

  $q->execute(array($newpswd1, $usernm));
  $q->closeCursor();

  $_SESSION['Password']=$newpswd1;

  $msg="METY TSARA FANOVANA NATAONAO!";
  setcookie("msg",$msg, time()+5);
  header("location: profile.php");
}
else
{
  $msg="Sorry!Password Incorrect, Please Try again";
  setcookie("msg",$msg, time()+5);
  header("location: profile.php");
}
}
?>