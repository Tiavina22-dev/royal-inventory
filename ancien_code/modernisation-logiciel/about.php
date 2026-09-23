<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.css">
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/list_type4.css"> 
  <link rel="stylesheet" href="css/list_type1.css">
  <!--
  <link rel="stylesheet" href="css/list_type2.css">
  <link rel="stylesheet" href="css/list_type3.css">
  <link rel="stylesheet" href="css/list_type5.css">
  <link rel="stylesheet" href="css/list_type6.css">
  <link rel="stylesheet" href="css/w3.css">
  -->
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php");
include('connect.php');
//Query to liste negatif product
   $query_update = "SELECT * FROM history WHERE type = 'update' ORDER BY date_time DESC";
    $query_update = $bdd->prepare($query_update);

    $query_update->execute(array());

?>
<!-- *******************MATERIALS**********************-->
<div id="About" class="bg-info">
<br>
<br>
<br>
<br>
<br>
<br>

<div class="list-type4">
	<h3>UPDATE RELEASE NOTE <a class="center" href="#" data-toggle="modal" data-target="#add"><img src="img/eeeee_icon.png" height="30" width="30" background alt="Edit" /></a></h3>
<div class="list-group4">
<?php 
while ($donnees = $query_update -> fetch())
{ 
?>
<span class="puce4"><?php echo "<span style='font-weight: bold' class = 'text-white'>".$donnees['date_time'].' : </span>'.nl2br($donnees['details'])." <br><span class = 'text-warning'> - <i>Added by " .$donnees['responsable'].'</i>'; ?></span></span>
<?php
}
 $query_update->closeCursor();
 ?>
</div>
</div>
<!-------------------------------------->
    <!-- modal form ADD NOTE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">ADD HISTORY UPDATE DETAILS</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="add_history_update.php" method="post">
            <div class="form-group">
            <textarea class="form-control" rows="4" placeholder="Add update details here" name="history_details"></textarea>
            </div>
            <button type="submit" class="btn btn-success">ADD</button>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
<div class="list-type4">
	<h3>SUMARY</h3>
<div class="list-group4">
<span class="puce4">Website fonctionality : Stock Management</span>
<span class="puce4">Starting dev Date : August 2020</span>
<span class="puce4">Program based on : HTML5, CSS, JavaScript, PHP, Mysql</span>
<span class="puce4">Associed bibliotheque : Bootstrap, Ajax, W3Schools, canva</span>
</div>
</div>

<div class="list-type1">
	<h3>FEATURES</h3>
<ol>
<li><a href="deconnection.php">Sign in page : with analogic clock and profile photo</a></li>
<li><a href="registration.php">Register option:  Ability to check non identic password and capable to check unicity of username</a></li>
<li><a href="#">Management data : Able to add new data (capable to check unicity of data), modify and delete (checkable rows)</a></li>
<li><a href="#">Management User : Give an option to identify and give permission to a new registered user</a></li>
<li><a href="#">Management User : Option to delete modify existing user</a></li>
<li><a href="#">Management User : xxxxxxxxxxxx</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
</ol>
</div>
<!------
#############################################
<div class="list-type2">
<ol>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
</ol>
</div>
#############################################
<div class="list-type3">
<ol>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
</ol>
</div>
#############################################
<div class="list-type5">
<ol>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
</ol>
</div>
#############################################
<div class="list-type6">
<ol>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
<li><a href="#">Formget Online Form Builder Create Online Forms</a></li>
</ol>
</div>
#############################################
<h1>Stock management features:</h1>

<h3># <br />
<p># </p>
<p>#</p>
<p># Alternate row's color to easily identify each line<br /></p>
<p># Manage user tab:
    <ul>
      <li></li>
      <li>Option to add new user</li>
      <li></li>
  </ul>
<p># Group by User and Group by Category tab with delete/modify option</p>
<p># History tab:<br />
    <ul>
    <li>Have a delete option</li>
    <li>Contain log of all action performed to the database (Ability to identify the precise changes)</li>
    </ul>
<p># Analysis tab:</br>
    <ul>
    <li>Give many ability to visualize materials in stock</li>
    <li>Synchronize the exchange of information about the material changes (place and state)</li>
    <li>Give a general view for materials location</li>
    </ul>
<p># Messages tab: Ability to send amessage to other user</p>
<p># Manage profile tab<br />
    <ul>
    <li>Change profile photo</li>
    <li>Change password</li>
    </ul>
<p># Sing out tab</p>
<p># About tab</p>
<h2>Features on the next release</h2>
<p># Auto backup of sql database and php files at sing in and logout</p>
<p># Auto logout after 15 min inactif</p>
-->
<p style="text-align: center;">Updated Date: Desamber 22, 2020</p>
<p style="text-align: center;">Author: Relahy Tongalaza</p>
</h3>
<br>
<br>
<br>
</div>  
  <!-- jQuery -->
  <!--
  <script type="text/javascript" src="js/mdb.min.js"></script>
  -->
   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- Your custom scripts (optional) -->

</body>
</html>
