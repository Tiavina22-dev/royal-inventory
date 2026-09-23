<!DOCTYPE html>
<html>
<head>
    <title>CONTACT</title>
    <!---add bootstrap css--->
    <script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h4 id="ancre1" class="text-center">Contact des Colaborateurs</h4>
<?php
//Connect to BD
include('connect.php');

   //Query to liste searched product
   $query_contact = "SELECT * FROM contact ORDER BY name;";
    $q = $bdd->prepare($query_contact);

    $q->execute(array());

?>
<!---------------NEW CONTACT----------------------->
	<div class="text-center">
	<button type="submit" class="btn btn-success" data-toggle="modal" data-target="#new_contact">New Contact</button>
	</div>
<!-------------------------------------->
<!-- model form new_contact-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="new_contact" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">NEW CONTACT</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_contact.php" method="post">
	<div class="form-group">
		<label>Name</label>
		<input class="form-control" name="name" placeholder="Ex: Royal" type="text">
	</div>
	<div class="form-group">
		<label>Contact</label>
		<input class="form-control" name="mobil" placeholder="034 50 422 09" type="text">
	</div>
	<div class="form-group">
		<label>Mail Adress</label>
		<input class="form-control" name="mail" placeholder="Ex: royal@gmail.com" type="text">
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-success">Add</button>
	</div>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
   <table id="example" class="table table-dark table-striped table-bordered dt-responsive nowrap " style="width:100%">
        <thead>
        <tr>
        <th class="text-center">#</th>
        <th>Name</th>
        <th class="text-center">Phone No.</th>
        <th class="text-center">Mail</th>
        <th class="text-center">By</th>
        <th class="text-center">Edit</th>
        <th class="text-center">Del</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
               
?>
<!------------------------------------------------->
        <tr>
        <td class="text-center"><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['name']; ?></td>
        <td class="text-center"><b><?php echo $donnees['mobil']; ?></b></td>
        <td><?php echo $donnees['mail']; ?></td>
        <td><?php echo $donnees['modified_by']; ?></td>
        <td class="text-center"><a data-toggle="modal" data-target="#<?php echo "no".$donnees['id_contact']; ?>"><img src="img/edit_icon_1.png" height="30" width="30" background alt="Edit" /></a></td>
        <td class="text-center"><a class="center" href="delete_contact.php?id_contact=<?php echo $donnees['id_contact']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a></td>
        <!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_contact']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier Contact de <?php echo $donnees['name']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_contact.php" method="post">
						<div class="form-group">
						<label>Name</label>
						<input class="form-control" name="name" value="<?php echo $donnees['name']; ?>" type="text">
						</div>
						<div class="form-group">
						<label>Numero</label>
						<input class="form-control" name="mobil" value="<?php echo $donnees['mobil']; ?>" type="text">
						</div>
						<div class="form-group">
						<label>Mail</label>
						<input class="form-control" name="mail" value="<?php echo $donnees['mail']; ?>" type="text">
						<input type="hidden" name="id_contact" value="<?php echo $donnees['id_contact']; ?>">
						</div>
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
        </tr>
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
<br>
<br>
<br>
<br>
 <?php
 $q->closeCursor();
 
?>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
<script  src="js/confirmation.js"></script>
</html>