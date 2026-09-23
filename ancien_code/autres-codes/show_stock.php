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
	<?php
		if (isset($_POST['point_de_vente'])) {
	$point_de_vente = $_POST['point_de_vente'];

	$query_stock = $bdd->query('SELECT id_mvt, id_x, qt, prix_unitaire, nom_client_fournisseur, FROM mvt WHERE nom_client_fournisseur=point_de_vente;');
	$stock=$query_stock -> fetch();

	echo $stock[];
	
	}
	?>
	<table class="table-info table">
        <thead>
        <tr>
        <th>Nom/Description de Produit</th>
        <th>Prix</th>
        <th>Qte Dispo</th>
        <th>Ref ID</th>
        <th>Ajouter</th>
        </tr>
        </thead>
        <tbody>
</body>