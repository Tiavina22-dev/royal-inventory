<!DOCTYPE html>
<html>
<head>
	<title>Ajout du Materiel</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->

</head>
<body>

</body>
<div class="close">
<button type="button" class="btn btn-info">x</button>
</div>
<h3 class="text-center">Nouvelle enregistrement</h3>
<div class="container" style="background: pink">
<div class="row">
	<div class="col-sm-10">
		<div class="panel-body">
			<form role="form">
				<div class="form-group">
					<label>Anarana/Model/Reference/Caracteristique/Details</label>
					<input class="form-control" type="text" id="Search" placeholder="Nom du materiel">

					<label>Quantite/Isany</label>
					<input class="form-control" type="number" id="Search" placeholder="Quantite">
					
					<label>Prix de base / Ivarotana</label>
					<input class="form-control" type="number" id="Search" placeholder="Prix Ivarotana">

					<label>Masokarena</label>
					<input class="form-control" type="number" id="Search" placeholder="Prix d'achat">

					<label>Categorie/Classe(<a href="#myModal" data-toggle="modal">Exemple</a>)</label>
					<div class="form-inline">
					<input class="form-control" type="text" id="Search" placeholder="Ex: Pneu/Loko/Piece Moto">
					
					</div>

					<label>Code d'identification (<a href="#myModal" data-toggle="modal">Code existante</a>) | Optional</label>
					<input class="form-control" type="text" id="Search" placeholder="Ex: 001FT6 >>> fer 6 Turquie">
				</div>
				<button type="submit" class="btn btn-info">Enregistrer</button>
			</form>
		</div>
	</div>	
</div>

<!-----Search result show with select----->
<br>
<div class="table-bordered">
	<table class="table-responsive-sm" width=100%>
		<tr>
			<td class="text-center" colspan="3">Ny efa tafiditra Androany</td>
		</tr>
		<tr>
			<td>Piston_RS Prix:10 000Ar (50 dispo)</td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-xs btn-success">Modifier</a></td>
			<td><a href="#myModal" data-toggle="modal" class="btn btn-danger">Supprimer</a></td>
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