<!DOCTYPE html>
<html>
<head>
	<title>Controle</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->

</head>
<body>

</body>
<h2 class="text-center">Page de Controle</h2>
<div class="container" style="background: #FFA251">
	<div class="float-right">
	<span><b>Etat du caisse a 9:30</b> <br>TL:<a href="#myModal" data-toggle="modal"> 10 000 000 Ar </a> <br> Bc: 1 0000 000 Ar</span>
	</div>
	
<!-----Item Epuise----->
<br>
<br>
<br>
<br>
<div class="table-bordered">
	<table class="table-responsive-sm" width=100%>
		<tr>
			<td class="text-center" colspan="3"><b>Item Epuise</b></td>
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
<br>
</div>
<!-----Liste Item le plus vendu----->
<hr>
<div class="container" style="background: pink">
<br>
<div class="table-bordered">
<table class="table-responsive-sm" width=100%>
	<tr>
		<td class="text-center" colspan="5"><b>Item le plus vendu</b></td>
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
<!---------------------------------------------------->
<br>
<div class="text-center">
<form action="/action_page.php">
	<label for="plusvendu1">De</label>
 	<input type="date" id="plusvendu1" name="plusvendu1">
 	<label for="plusvendu2">à</label>
  	<input type="date" id="plusvendu2" name="plusbeneficieux2">
  	<input type="submit" class="btn btn-warning" value="Show">
</form>
</div>
<br>
<!---------------------------------------------------->
</div>
<!-----Materiel le plus beneficieux----->
</div>
<hr>
<div class="container" style="background: #FFA251">
<br>
<div class="table-bordered">
<table class="table-responsive-sm" width=100%>
	<tr>
		<td class="text-center" colspan="5"><b>Item le plus beneficieux</b></td>
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
<!---------------------------------------------------->
<br>
<div class="text-center">
<form action="/action_page.php">
	<label for="plusbeneficieux1">De</label>
 	<input type="date" id="plusbeneficieux1" name="plusbeneficieux1">
 	<label for="plusbeneficieux2">à</label>
  	<input type="date" id="plusbeneficieux2" name="plusbeneficieux2">
  	<input type="submit" class="btn btn-warning" value="Show">
</form>
</div>
<!---------------------------------------------------->
<br>
</div>
<hr>
<!-----Liste de Depense----->
</div>
<div class="container" style="background: pink">
<br>
<div class="table-bordered">
<table class="table-responsive-sm" width=100%>
	<tr>
		<td class="text-center" colspan="5"><b>Depense journaliere</b></td>
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
<!---------------------------------------------------->
<br>
<div class="text-center">
<form action="/action_page.php">
	<label for="depense1">De</label>
 	<input type="date" id="depense1" name="depense1">
 	<label for="depense2">à</label>
  	<input type="date" id="depense2" name="depense2">
  	<input type="submit" class="btn btn-warning" value="Show">
</form>
</div>
<!---------------------------------------------------->
<br>
</div>
<hr>
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
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>