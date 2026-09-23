<!DOCTYPE html>
<html>
<head>
	<title>Stock</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<div  class="container-fluid">
<br>
<br>
<h2 class="text-center">Stock</h2>
<span>!!! Rehefa tsy tafiditra ara-dalana ny mivoaka sy miditra dia tsy a jours ny stock</span>

<!--Debut du collapsible--->

<div id="contenu">
	<!--Liste tous les stocks--->
	<div class="card">
		<div class="card-header">
			<a class="card-link" data-toggle="collapse" href="#allstock">
				Afficher toute les stocks
			</a>
		</div>
		<div id="allstock" class="collapse" data-parent="#contenu">
			<div class="card-body">
				List of all material
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
</div>
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>