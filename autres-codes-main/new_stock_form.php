<!DOCTYPE html>
<html>
<head>
	<title>stock</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->

<!-------------------------------------->
<!-- model form NOUVEAU-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_new" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Nouveau Article</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_stock_produit.php" method="post">
	<div class="form-group">
		<label>Nom/Description de produit</label>
		<input class="form-control" name="nom_x" placeholder="Ex: Huile 90" type="text">
	</div>
	<div class="form-group">
		<label>Prix Unitaire (Ariary)</label>
		<input class="form-control" name="prix_de_vente" placeholder="Ex: 15000" type="number">
	</div>
	<div class="form-group">
		<label>Quantite</label>
		<input class="form-control" name="qt" placeholder="Atsasany: 0.5 ; Fefany: 0.25 (Tsy miasa ny virgule)" type="number">
	</div>
	<div id="warning_msg">
		<?php
              if (isset($_COOKIE['msg_E'])) 
              {
                 echo $_COOKIE['msg_E'];
             }
         ?>
		<label>Reference ID </label>
	</div>
	<div class="form-group form-inline">
		<input class="form-control" name="reference_x" placeholder="Code_unique Ex: Fer 6 --> F06" type="text">
	</div>
		<div class="form-group">
		<label>Note/Fanamarihana(raha ilaina)</label>
		<input class="form-control" name="note_stock" type="text">
	</div>
	<div class="form-inline form-group">
		<button type="submit" class="btn btn-success">Valider</button>
	</div>
</form>
<!-----
				<div class="form-group">
					<label>Nom/Description de produit</label>
					<input class="form-control" type="text" name="nom_x" placeholder="Ex: Huile 90">

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
------>

<!-------------------------------------->


<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>