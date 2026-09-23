<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
  <!-- Font Awesome -->
   <link rel="stylesheet" href="fontawesome/css/all.css">
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/popup.css">
    <link href="css/registration.css" rel="stylesheet" type="text/css">
    <script src="js/jquery.min.js"></script>
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<?php
if (isset($_COOKIE['msg'])) 
{
   $msg=$_COOKIE['msg'];
 } else {
   $msg = "";
 };
if (isset($_SESSION['img_path'])) 
{
  $img_path = $_SESSION['img_path'];
}
else 
{
  //default pdp
  $img_path = "img/default_img/default_pdp.png";
}
?>

<div class="register text-center">
  <h4 class="text-center">Change profil photo</h4>
  <h5 class="text-center text-danger"><?php echo $msg;?></h5>
  <img src="<?php echo $img_path;?>" height="90" width="90" align="middle"/>
  <form action="saveimage.php" enctype="multipart/form-data" method="post">

    <table class="table table-borderless" style="border-collapse: collapse; font: 12px Tahoma;" border="1" cellspacing="5" cellpadding="5">
      <tbody><tr>
      <td>
      <input name="uploadedimage" type="file">
      </td>

      </tr>

      <tr>
      <td>
      <input name="Upload Now" type="submit" value="Upload Image">
      </td>
      </tr>


      </tbody></table>

</form>

<h4>Change password</h4>
<div class="content" autocomplete="off">
<form action="change_password.php" method="post">
<input type="password" name="oldpswd" placeholder='Old password' id="oldpswd">
<div class="on-focus clearfix" style="position: relative; padding: 0px;  display: table;margin: auto;">
<input type="password" name="newpswd1" placeholder='New password' id="password1">
<div id="warning_msgp1" class="tool-tip  slideIn"></div>
</div>
<div class="on-focus clearfix" style="position: relative; padding: 0px;  display: table;margin: auto;">
<input type="password" name="newpswd2" placeholder='Confirm password' id="password2">
<div id="warning_msgp2" class="tool-tip  slideIn"></div>
</div>
<input type="submit" name="Save password" value="Submit">  
</form>
</div>

</div>
  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
   <script>
$(document).ready(function(){
    $("#password1").on("input", function(){
    //----------------------
      var p1 = $(this).val();
      var msg;
      var color;
       document.getElementById("password1").style.borderColor = "green";
      msg="Mety tsara";
      //write notification on div id=warning_msg ;
      if (p1=="") {
        color = "red";
        msg="Tsy Mety";
      } else {
        color = "green";
        msg="Azo ekena";
      }
      //$("#warning_msg").text(msg);
      $("#warning_msgp1").text(msg);
      document.getElementById("password1").style.borderColor = color;
      
      
    });
});
</script>
 <script>
$(document).ready(function(){
    $("#password2").on("input", function(){
    //----------------------
      var p2 = $(this).val();
      var p1 = document.getElementById("password1").value;
      var msg;
      var color;
       document.getElementById("password2").style.borderColor = "green";
      msg="Mety tsara";
      //write notification on div id=warning_msg ;
      if (p2=="") {
        color = "red";
        msg="Tsy Mety";
      } else {
        if (p1==p2)
        {color = "green";
        msg="Mety Tsara";}
        else
        {color = "red";
        msg="Tsy Mitovy";}
      }
      //$("#warning_msg").text(msg);
      $("#warning_msgp2").text(msg);
      document.getElementById("password2").style.borderColor = color;
      
      
    });
});
</script>
</body>
</html>
