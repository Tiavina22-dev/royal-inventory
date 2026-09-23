<!DOCTYPE html>
<html>
<head>
	<title>comande</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->

</head>
<body>

</body>
<h3 class="text-center">(icon listes) Command Preparation</h3>
<div class="container" style="background: #FFA251">
<div class="row">
	<div class="col-sm-5">
		<div class="panel-body">
			<form role="form">
				<div class="form-group">
					<label> Search ID or Word</label>
					<input class="form-control" type="text" id="Search" placeholder="Imput Id or Word">
				</div>
				<button type="submit" class="btn btn-info">Search</button>
			</form>
		</div>
	</div>	
</div>

<!-----Search result show with select----->
<br>
<div class="table-bordered">
	<table class="table-responsive-sm" width=100%>
		<tr>
			<td class="text-center" colspan="3">RESULT</td>
		</tr>
		<tr>
			<td>Piston_RS Prix:10 000Ar (50 dispo)</td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-xs btn-success">Details</a></td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-primary">Select</a></td>
		</tr>
		<tr>
			<td>Piston_RS Prix:10 000Ar (50 dispo)</td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-xs btn-success">Details</a></td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-primary">Select</a></td>
		</tr>
		<tr>
			<td>Piston_RS Prix:10 000Ar (50 dispo)</td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-xs btn-success">Details</a></td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-primary">Select</a></td>
		</tr>
	</table>
</div>

<!-----List of command show on table----->
<br>
</div>
<br>
<div class="container" style="background: pink">
<br>
<div class="table-bordered">
<table class="table-responsive-sm" width=100%>
	<tr>
		<td class="text-center" colspan="5">Commande de Mr Rabe</td>
	</tr>
	<tr>
		<td>Objet 1</td>
		<td>Nombre</td>
		<td>Prix</td>
		<td><button type="button" class="btn btn-primary">Modifier</button></td>
		<td><button type="button" class="btn btn-danger">Suppr</button></td>
	</tr>
		<tr>
		<td>Objet 2</td>
		<td>Nombre</td>
		<td>Prix</td>
		<td><button type="button" class="btn btn-primary">Modifier</button></td>
		<td><button type="button" class="btn btn-danger">Suppr</button></td>
	</tr>
</table>
</div>
<br>
<!--
<div class="container btn-group-vertical">
-->
<div class="text-center">
<!--
<button type="button" class="btn btn-xs btn-success">Facture</button>
--->
<button type="button" class="btn btn-danger">Anuler</button>
<button type="button" class="btn btn-primary">Valider</button>
</div>
<br>
</div>
<br>
<!--#############FORM IN MODAL################-->
<!-- model form -->
<div class="row"> 
	<div class="col-sm-5" style="background: pink">
		<div class="panel-body">
	<!-- button to generate model form -->
	<!--
	<a href="#myModal" data-toggle="modal" class="btn btn-xs btn-success"> Form in Modal </a>
	-->
	<!-- model form settings-->
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Form Tittle</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form">
	<div class="form-group">
		<label for="exampleInputEmail1">Prix Final</label>
		<input class="form-control" id="prix_final" placeholder="Enter Prix" type="number">
	</div>
<div class="form-group">
	<label for="exampleInputPassword1">Combien?</label>
	<input class="form-control" id="quantite" placeholder="quantite" type="number">
</div>
<button type="submit" class="btn btn-default">Valider</button>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<hr>
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>