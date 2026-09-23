<!DOCTYPE html>
<html>
<head>
	<title>Gerer Prix</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/pourcentage.css" rel="stylesheet">
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
	<script src="js/jquery-3.5.1.min.js"></script>

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<h4 class="text-center">GESTION DE PRIX</h4>

<div class="flex-wrapper">
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart orange">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="30, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="20.35" class="percentage">30%</text>
    </svg>
  </div>
  
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart green">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="60, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="20.35" class="percentage">60%</text>
    </svg>
  </div>

  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart blue">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="90, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="20.35" class="percentage">90%</text>
    </svg>
  </div>
</div>
<?php
//Connect to BD
include('connect.php');
?>
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<form role="form" action="simple_search_prix.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" name="key_word" placeholder="Name/Code/Search">
<button type="submit" class="btn btn-info">Search</button>
</div>
</form>
<br>
<br>
<br>
<!---------------search result---------------------------->
<?php
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT * FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? GROUP BY nom_x';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%"));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<br><b>"."[".$key_word."]"." does not exist on the base</b><br>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table class="table-info table">
        <thead>
        <tr>
        <th>ID</th>
        <th>Ref ID</th>
        <th>Nom/Description de Produit</th>
        <th>Prix fournisseur</th>
        <th>%</th>
        <th>Benefice</th>
        <th>Prix de vente</th>
        <th>Note</th>
        <th colspan="2">Modifier/suprimer</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$i = 1;
$j = 1;

//Query searcher word
$pourcentage = 0 ;
while ($donnees = $q -> fetch())
{
	//ID NAME
$id_prix_fournisseur = $j.'_id_prix_fournisseur';
$id_benefice = $j.'_id_benefice';
$id_pourcentage = $j.'_id_pourcentage';
$id_prix_de_vente = $j.'_id_prix_de_vente';
//----------------
$j = $j+1;
//----------------
	$prix_fournisseur = $donnees['prix_fournisseur'];
	$prix_unitaire = $donnees['prix_de_vente'];
	$benefice = ($prix_unitaire-$prix_fournisseur);
	if ($prix_fournisseur==0) {
		$pourcentage = 0;
	} else {
		$pourcentage = (($benefice*100)/$prix_fournisseur);
	}
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['id_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['prix_fournisseur']." Ar"; ?></td>
        <td><?php echo $pourcentage."%"; ?></td>
        <td><?php echo $donnees['benefice']." Ar"; ?></td>
        <td><?php echo $donnees['prix_de_vente']." Ar"; ?></td>
        <td><?php echo $donnees['note_x']; ?></td>
        
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_x']; ?>">Modifier</button></td>
		<td><a class="center" href="delete_produit.php?id_x=<?php echo $donnees['id_x']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier Prix du Produit No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form id="form_prix" role="form" action="modifier_prix_produit.php" method="post">
						<div class="form-group">
						<label>Prix Fournisseur</label>
						<input class="form-control" name="prix_fournisseur" value="<?php echo $donnees['prix_fournisseur']; ?>" type="number" step="any" id="<?php echo $id_prix_fournisseur; ?>" oninput="fournisseurFunction($(this));">
						</div>
						<div class="form-group float-left">
						<label>Pourcentage (%)</label>
						<!-------------------------------->

						<!--------------------------------->
						<input class="form-control" name="pourcentage" value="<?php echo $pourcentage; ?>" type="number" step="any" id="<?php echo $id_pourcentage; ?>" oninput="pourcentageFunction($(this));">
						</div>
						<div class="form-group  float-right">
						<label>Benefice (Ariary)</label>
						<input class="form-control" name="benefice" type="number" step="any" value="<?php echo $benefice; ?>" id="<?php echo $id_benefice; ?>" oninput="beneficeFunction($(this));">
						</div>
						<div class="form-group">
						<label>Prix Unitaire (Ariary)</label>
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="number" id="<?php echo $id_prix_de_vente; ?>" oninput="pvFunction($(this));">
						<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note_x" value="<?php echo $donnees['note_x']; ?>" type="text">
						</div>
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!-------------------------------------->
<?php
$i+=1;
}
?>
        </tbody>
    </table>
 <?php
	}
 $q->closeCursor();
 }
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------------------------->
<!---------TO REMOVED : COMMENT THE FOLLOWING CODE-------->
<!--Debut du collapsible--->
<!---------------Radio disable input field----------------->
<!-------Auto Update complet pourcentage field----------------->
<script>
function fournisseurFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_pourcentage = j + '_id_pourcentage';
	var id_benefice = j + '_id_benefice';
	var id_prix_de_vente = j + '_id_prix_de_vente';
	var p = document.getElementById(id_pourcentage).value;
	//alert(id_prix_de_vente);
      var f = e.val();
      var b = (f * p)/100;
      var pu = (f*1) + (b*1) ;

       $("#"+id_benefice).val(b);
       $("#"+id_prix_de_vente).val(pu);
}
</script>
<script>
function beneficeFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_fournisseur = j + '_id_prix_fournisseur';
	var id_pourcentage = j + '_id_pourcentage';
	var id_prix_de_vente = j + '_id_prix_de_vente';
	var f = document.getElementById(id_fournisseur).value;
	//alert(id_prix_de_vente);
      var b = e.val();
      var p = (b * 100)/f;
      var pu = (f*1) + (b*1) ;
      $("#"+id_pourcentage).val(p);
      $("#"+id_prix_de_vente).val(pu);
}
</script>
<script>
function pourcentageFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_fournisseur = j + '_id_prix_fournisseur';
	var id_benefice = j + '_id_benefice';
	var id_prix_de_vente = j + '_id_prix_de_vente';
	var f = document.getElementById(id_fournisseur).value;
	var p =  e.val();
    var b = (f * p)/100;
    var pu = (f*1) + (b*1) ;
    $("#"+id_benefice).val(b);
    $("#"+id_prix_de_vente).val(pu);
}
</script>
<script>
function pvFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_fournisseur = j + '_id_prix_fournisseur';
	var id_benefice = j + '_id_benefice';
	var id_pourcentage = j + '_id_pourcentage';
	var f = document.getElementById(id_fournisseur).value;
	var pu =  e.val();
	var b = (pu*1)-(f*1);
    var p = (((pu*1)-(f*1))*100)/(f*1);
    $("#"+id_benefice).val(b);
    $("#"+id_pourcentage).val(p);
}
</script>
<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>