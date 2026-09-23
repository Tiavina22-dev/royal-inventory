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
//GET USERNAME
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
//Query to liste negatif product
   $query_update = "SELECT * FROM history WHERE type = 'php' ORDER BY date_time DESC";
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
<h3><b>COMMAND PHP</b> <a class="center" href="#" data-toggle="modal" data-target="#add"><img src="img/eeeee_icon.png" height="30" width="30" background alt="Edit" /></a></h3>
<div class="list-type4">
<div class="list-group4">
<?php 
while ($donnees = $query_update -> fetch())
{ 
?>
<span class="puce4"><?php echo "<span style='font-weight: bold'>".nl2br($donnees['before_change']).'</span> <br>'.nl2br($donnees['details']).' <br>- <i>Added by ' .$donnees['responsable'].'</i>'; 
if ($username == "TRELAHY") {
?>
<br>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo "ci".$donnees['history_id']; ?>">MODIFY</button>
<a href="delete_php.php?id=<?php echo $donnees['history_id']; ?>" onclick="confirmationDelete('DELETE THIS LINE?');return false; post ;"><button type="button" class="btn btn-warning">DELETE</button></a>
<?php
}
?>
</span>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "ci".$donnees['history_id']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">MODIFY COMMANDE</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_php.php" method="post">
						<div class="form-group">
							<label>COMMAND (Color:@danger, @info, @warning, @primary, @secondary,@white, @close)</label>
	            			<textarea class="form-control" rows="4" name="command"><?php echo $donnees['before_change']; ?></textarea>
						</div>
						<div class="form-group">
			            	<label>DETAILS</label>
			            	<textarea class="form-control" rows="8" name="details"><?php echo $donnees['details']; ?></textarea>
            			</div>
            			<input type="hidden" name="id" value="<?php echo $donnees['history_id']; ?>">
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
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
          <h4 class="modal-title">ADD PHP COMMAND</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="add_php_command.php" method="post">
            <div class="form-group">
	            <label>COMMAND (Color:@danger, @info, @warning, @primary, @secondary,@white, @close)</label>
	            <textarea class="form-control" rows="4" placeholder="Put Command here" name="command"></textarea>
            </div>
            <div class="form-group">
            	<label>DETAILS</label>
            	<textarea class="form-control" rows="8" placeholder="Put Details here" name="history_details"></textarea>
            </div>
            <button type="submit" class="btn btn-success">ADD</button>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->

  <!-- jQuery -->
  <!--
  <script type="text/javascript" src="js/mdb.min.js"></script>
  -->
   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
   <script  src="js/confirmation.js"></script>
  <!-- Your custom scripts (optional) -->

</body>
</html>
