<!DOCTYPE html>
<html>
<head>
	<title>commande</title>
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
<h4 class="text-center">Preparation du Commande</h4>
<!--Debut du collapsible--->
<div id="contenu">
	<!--Liste tous les stocks--->
	<div class="card">
		<div class="card-header  bg-info" data-toggle="collapse" href="#allstock">
			<a class="card-link text-white" href="#TTous-les-produits-disponibles">Tous les produits disponibles (Preparation?)</a>
		</div>
		<div id="allstock" class="collapse" data-parent="#contenu">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
	<input type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#Modal_new">Nouveau?</button>
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
<!----------------QUERY TABLE---------------------->
<?php
$i = 1;

include('connect.php');
//Query for x dispo only
//$query_commande_search = $bdd->query('SELECT nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x GROUP BY reference_x ORDER BY date_time;');
//Query to liste All product
$query_commande_search = $bdd->query('SELECT nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x FROM mvt RIGHT JOIN produit ON mvt.id_x = produit.id_x GROUP BY reference_x ORDER BY date_time;');
//Query to liste All Waiting command

while ($donnees = $query_commande_search -> fetch())
{ 
//$modal_array[$i]=$donnees['reference_x'];

 $qt_dispo = $donnees['qt']*1;
                             
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x'] . $donnees['note_x']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo $qt_dispo; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        
        <!---
        <td><a href="#<?php echo $donnees['reference_x']; ?>" data-toggle="modal" class="btn btn-primary"><?php echo $donnees['reference_x']; ?></a></td>
        -->
        <!---
        <td><input type="submit" class="btn btn-primary" id="select_action" name="Select"></td>
    	-->

    	<td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo $donnees['reference_x']; ?>">Select</button></td>
        </tr>
<!-- modal form SELECT-->
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $donnees['reference_x']; ?>" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"><?php echo $donnees['reference_x']." : ".$donnees['nom_x']; ?></h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_vente.php" method="post">
	<div class="form-group">
		<label>Quantite (Ex: Atsasany : 0.5 ; Fefany : 0.25)</label>
		<input class="form-control" name="qt" value="1" type="number">
	</div>
	<div class="form-group">
		<label>Prix Unitaire (Ariary)</label>
		<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="number">
	</div>
<button type="submit" class="btn btn-default">Valider</button>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
<?php
//echo $u;
//
$i+=1;

}
?>
        </tbody>
    </table>
			</div>			
		</div>
	</div>
	<!-------------------------->
	<!--Boucle Liste par date d enregistrement--->
	
	<div class="card">
		<div class="card-header">
			<a class="card-link" data-toggle="collapse" href="#groupedate">
				Goupement par date
			</a>
		</div>
		<div id="groupedate" class="collapse" data-parent="#contenu">
			<div class="card-body">
				<div id="souscontenu1">
	<!--boocle sous collapse par date--->
		<div class="card">
		<div class="card-header">
			<a class="card-link" data-toggle="collapse" href="#variable_date">
				Enregistrement du date 12/05/20
			</a>
		</div>
		<div id="variable_date" class="collapse" data-parent="#souscontenu1">
			<div class="card-body">
				List de Enregistrement du date 12/05/20
			</div>			
		</div>
	</div>
	<!-------------------------->
				</div>
			</div>			
		</div>
	</div>
	
	<!------------------------------------------->
	<!-----------Groupement par categorie-------->
	<div class="card">
		<div class="card-header">
			<a class="card-link" data-toggle="collapse" href="#groupecategorie">
				Goupement par categorie
			</a>
		</div>
		<div id="groupecategorie" class="collapse" data-parent="#contenu">
			<div class="card-body">
				<div id="souscontenu2">
	<!--boocle sous collapse par date--->
		<div class="card">
		<div class="card-header">
			<a class="card-link" data-toggle="collapse" href="#variable_date">
				Enregistrement du date 12/05/20
			</a>
		</div>
		<div id="variable_date" class="collapse" data-parent="#souscontenu2">
			<div class="card-body">
<!---###############CONTENU CATEGORIE SPECIFIQUE#############--->
<div class="container" style="background: #FFA251">
<br>
<div class="table-bordered">
	<table class="table-responsive-sm" width=100%>
		<tr>
			<td class="text-center" colspan="3"><b>Mouvement</b></td>
		</tr>
		<tr>
			<td><b>Horaire</b></td>
			<td><b>Action</b></td>
			<td><b>Details</b></td>
		</tr>
		<tr>
			<td>08:04:56</td>
			<td>Ajout de materiel</td>
			<td>ID:0009 ;Item: Segment X; Prix de base:10 000 Ar</td>
		</tr>
		<tr>
			<td>12:04:56</td>
			<td>Modification</td>
			<td>Prix de base: 10 000 Ar >> 12 000 Ar</td>
		</tr>
		<tr>
			<td>13:04:56</td>
			<td>Vente</td>
			<td>1 Chanbre a air(2 600 Ar); 2 Pistion xs (2 200 Ar); 6 Bougie (56 600 000 Ar) >> TL: 99 000 Ar</td>
		</tr>
	</table>
</div>
<br>
</div>
<!--##########################################################-->
			</div>			
		</div>
	</div>
	<!-------------------------->
				</div>
			</div>			
		</div>
	</div>
	<!------------------------------------------->

</div>

<!--FIN du collapsible----->
<!-----LISTE DU COMMANDE EN COURS----->
<br>
</div>
<br>

	 <table class="table-info table">
        <thead>
        <tr>
        	<th class="text-center" colspan="6">Commande de Mr Rabe</th>
        </tr>
        <tr>
        <th>Article</th>
        <th>Quantite</th>
        <th>Prix unitaire</th>
        <th>Sous total</th>
        <th colspan="2">Action</th>
        </tr>
        </thead>
        <tbody>
<?php
//Query to liste All Waiting command
$query_commande_list = $bdd->query('SELECT nom_x, (SUM(qt)) as qt,commande.prix_de_vente as prix_unitaire, (commande.prix_de_vente)*(SUM(qt)) as sous_total FROM commande INNER JOIN produit ON commande.id_x = produit.id_x GROUP BY commande.id_x;');
while ($donnees = $query_commande_list -> fetch())
{ 
	
?>
		<tr>
		<td><?php echo $donnees['nom_x']; ?></td>
		<td><?php echo $donnees['qt']; ?></td>
		<td><?php echo $donnees['prix_unitaire'].' Ar'; ?></td>
		<td><?php echo $donnees['sous_total'].' Ar'; ?></td>
		<td><button type="button" class="btn btn-primary">Modifier</button></td>
		<td><button type="button" class="btn btn-danger">Suppr</button></td>
	</tr>
<?php
}
?>
</tbody>
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
<!-- modal form SELECT-->
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
<!-------------------------------------->
<!-- model form NOUVEAU-->
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_new" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Nouveau Article</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_vente.php" method="post">
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
	<div class="form-group">
		<label>Reference ID</label>
		<input class="form-control" name="reference_x" placeholder="Izay tiana Ex: Fer 6 --> F06" type="text">
	</div>
<button type="submit" class="btn btn-default">Valider</button>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->


<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
</html>