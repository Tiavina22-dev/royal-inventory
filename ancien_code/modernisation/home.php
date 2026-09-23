<?php
$img_path = "img/default_img/default_pdp.png";
session_start();
if (isset($_SESSION['img_path'])) 
{
  $img_path = $_SESSION['img_path'];
}
if (isset($_COOKIE['img_path'])) 
{
   $img_path=$_COOKIE['img_path'];
 }

	$focus_state_1 = "autofocus";
	$focus_state_2 = "";
 	$username = "";
 if (isset($_COOKIE['username'])) 
{
   $username = $_COOKIE['username'];
   $focus_state_1 = "";
   $focus_state_2 = "autofocus";
 }

if (isset($_COOKIE['msg'])) 
{
   $msg=$_COOKIE['msg'];
 } else {
   $msg = "";
 };
 if (isset($_COOKIE['msg_register'])) 
{
   $msg_register=$_COOKIE['msg_register'];
 } else {
   $msg_register = "";
 };
 // GET URL
 $url = 'no';
 if (isset($_COOKIE['url'])) 
{
   $url = $_COOKIE['url'];
 }
setcookie("img_path",$img_path, time()+3600);
setcookie("username",$username, time()+3600);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Include CSS FILE--> 
<link rel="stylesheet" type="text/css" href="css/style_Home.css">
<link rel="stylesheet" type="text/css" href="css/box.css">
<link href="css/bootstrap.min.css" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="css/modern_ui.css">
</head>
<body class="app-login-page">
<main class="app-login-shell">
  <section class="app-login-hero">
    <h1>Royal Inventory</h1>
    <p>Gestion de stock, ventes, caisse et controles dans une interface plus claire pour le travail quotidien.</p>
    <canvas id="clock" width="210" height="210"></canvas>
    <script src="js/Vector.js"></script> 
    <script src="js/clock.js"></script>
  </section>

  <section class="app-login-card">
    <div class="app-login-logo">
      <img src="img/replace.png" alt="Royal Inventory" />
      <div>
        <h2>Connexion</h2>
        <strong>Stock & Cash Management</strong>
      </div>
    </div>
    <p class="app-login-message"><?php echo $msg.$msg_register; ?></p>
    <form method="post" action="check_password">
      <img src="<?php echo $img_path?>" alt="Profil" class="app-login-avatar" />
      <input type="text" <?php echo $focus_state_1;?> placeholder="User Name" value="<?php echo $username;?>" name="username"/>
      <input type="password" <?php echo $focus_state_2;?> placeholder="Password" name="pswd"/>
      <input type="hidden" value="<?php echo $url;?>" name="url"/>
      <input type="submit" name="action" value="Sign In" />
      <a class="app-register-link" href="registration.php">Register?</a>
    </form>
  </section>
</main>



<!-- *******************SIGNIN**********************
<div id="check" class="tabcontent">

  
  <h2>OK</h2>


  
</div>
-->


<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
</body>
</html>
