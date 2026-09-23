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
	<link rel="stylesheet" href="css/w3.css">
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
<h4 class="text-center"><b>GERER PRIX</b></h4>
<?php
//Connect to BD
include('connect.php');
//Nombre OF ALL product
$nb_all_product= 0;
$query_product_all = 'SELECT id_x FROM produit';
	$q = $bdd->prepare($query_product_all);

	$q->execute(array());
	$nb_all_product=$q->rowCount ();
	$q->closeCursor();
//Number of prix fournisseur zero or NULL
$query_pf_null = 'SELECT id_x FROM produit WHERE prix_fournisseur IS NULL OR prix_fournisseur = 0';
	$q = $bdd->prepare($query_pf_null);
	$q->execute(array());
	$nb_null_prix_fournisseur=$q->rowCount ();
	$q->closeCursor();
	//Number of prix prix de vente zero or NULL
$query_pv_null = 'SELECT id_x FROM produit WHERE prix_de_vente IS NULL OR prix_de_vente = 0';
	$q = $bdd->prepare($query_pv_null);
	$q->execute(array());
	$nb_null_prix_pv=$q->rowCount ();
	$q->closeCursor();
//Calule pourcentage
	$avec_prix_fournisseur = (($nb_all_product - $nb_null_prix_fournisseur)*100)/$nb_all_product;
	$avec_prix_pv = (($nb_all_product - $nb_null_prix_pv)*100)/$nb_all_product;
	$quatremille = (($nb_all_product)*100)/4000;
?>
<!--------CERCLE POURCENTAGE----------------->
<div class="flex-wrapper">
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart orange">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="<?php echo $avec_prix_pv;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($avec_prix_pv);?>%</text>
      <text x="18" y="24.35" class="pv">PU(<?php echo ($nb_all_product-$nb_null_prix_pv).'/'.$nb_all_product;?>)</text>
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
        stroke-dasharray="<?php echo $avec_prix_fournisseur;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($avec_prix_fournisseur);?>%</text>
      <text x="18" y="24.35" class="fournisseur">Prix Fournisseur</text>
      <text x="18" y="27.35" class="fournisseur">(<?php echo ($nb_all_product-$nb_null_prix_fournisseur).'/'.$nb_all_product;?>)</text>
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
        stroke-dasharray="<?php echo $quatremille;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($quatremille);?>%</text>
      <text x="18" y="24.35" class="fournisseur">Nombre d'Article</text>
      <text x="18" y="27.35" class="but"><?php echo $nb_all_product;?>/4000</text>
    </svg>
  </div>
</div>
<!-------FIN CERCLE POURCENTAGE---------------------->
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<?php
$key_word = "";
if (isset($_COOKIE['key_word'])){$key_word=$_COOKIE['key_word'];}
 ?>
<div class="text-center">
<form role="form" action="simple_notif_prix_ambato_1.php" method="post">
		<select class="btn btn-warning" name="point_de_vente">
		<!---------
		<option value="Ambaibo_Electronique">ELECTRONIQUE</option>
	    <option value="Ambaibo_Tole">Ambaibo TOLE</option>
	    <option value="Ambaibo_loko">Ambaibo LOKO</option>
	    <option value="Ambato_veve_photo">VEVE</option>
	    <option value="Bejofo">BEJOFO</option>
	    ---------->
	    <option value="Ambato_Tantely" selected>AMBATO Tantely</option>
		</select>
		<button type="submit" class="btn btn-success">|Show New Prix|</button>
</form>
</div>
<?php
if ($key_word=="Notification du nouveau prix") {
?>
<br>
<div class="text-center">
<form role="form" action="reset_note_prix_ambato_1.php" method="post">
		<select class="btn w3-purple" name="point_de_vente">
	    <option value="Ambato_Tantely" selected>AMBATO Tantely</option>
		</select>
		<a href="#" onclick="confirmationDelete('RESET AND DELETE NOTIFICATION?');return false; post ;"><button type="submit" class="btn w3-purple">Reset Notification</button></a>
</form>
</div>
<?php
}
?>
<br>
 <div class="text-center">
<form role="form" action="simple_search_prix_ambato_1.php" method="POST">
<input type="search" class="light-table-filter" name="key_word" value="<?php echo $key_word ?>" placeholder="Name/Code/Search">
<button type="submit" class="btn btn-info">Search</button>
</form>
</div>
<!---------------search result---------------------------->
<?php
if (isset($_COOKIE['key_word'])) 
{
	//Handle search notification
	if ($key_word=="Notification du nouveau prix") {
		//GET Point_de_vente_name
		$point_de_vente = '';
		if (isset($_COOKIE['point_de_vente'])) 
		{$point_de_vente = $_COOKIE['point_de_vente'];}
		//Query to liste searched product
	$query_product_search = "SELECT * FROM produit WHERE note_prix ='NEW' OR note_prix_amparafa = 'NEW' OR note_prix_tantely = 'NEW' GROUP BY nom_x";
	if ($point_de_vente == 'General') {
	$query_product_search = "SELECT * FROM produit WHERE note_prix = 'NEW' GROUP BY nom_x";
	}
	if ($point_de_vente == 'Amparafa') {
	$query_product_search = "SELECT * FROM produit WHERE note_prix_amparafa = 'NEW' GROUP BY nom_x";
	}
	if ($point_de_vente == 'Ambato_Tantely') {
	$query_product_search = "SELECT * FROM produit WHERE note_prix_tantely = 'NEW' GROUP BY nom_x";
	}
		$q = $bdd->prepare($query_product_search);

		$q->execute(array());
	} else {
	//echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT * FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? GROUP BY nom_x';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%"));
	} //Close of else Handle search notification
	
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
        <th class="text-center" colspan="3">Prix Officiel</th>
        <th>Note</th>
        <th>By</th>
        <th></th>
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
	$prix_fournisseur = $donnees['prix_fournisseur']+0;
	$prix_unitaire = $donnees['prix_de_vente']+0;
	$pu_aparafa = $donnees['pu_aparafa']+0;
	$pu_tantely = $donnees['pu_ambato_tantely']+0;
	$benefice = ($prix_unitaire-$prix_fournisseur);
	if ($prix_fournisseur==0) {
		$pourcentage = 0;
	} else {
		$pourcentage = (($benefice*100)/$prix_fournisseur);
	}
	//Handle diminution or augmentation prix
	$difference_prix = $donnees['difference_prix']+0;
	if ($difference_prix > 0) {
		$color_badge = "w3-red";
		$difference_prix = "+".$difference_prix;
	} else {
		$color_badge = "w3-green";
	}
	$note_prix = $donnees['note_prix']."";
	if ($note_prix == "NC" || $note_prix == ""){ $difference_prix ="";	}
	//Handle diminution or augmentation prix Tantely
	$difference_prix_tantely = $donnees['difference_prix_tantely']+0;
	if ($difference_prix_tantely > 0) {
		$color_badge_tantely = "w3-red";
		$difference_prix_tantely = "+".$difference_prix_tantely;
	} else {
		$color_badge_tantely = "w3-green";
	}
	$note_prix_tantely = $donnees['note_prix_tantely']."";
	if ($note_prix_tantely == "NC" || $note_prix_tantely == ""){ $difference_prix_tantely ="";	}

	//Handle diminution or augmentation prix Amprafa
	$difference_prix_amparafa = $donnees['difference_prix_amparafa']+0;
	if ($difference_prix_amparafa > 0) {
		$color_badge_amparafa = "w3-red";
		$difference_prix_amparafa = "+".$difference_prix_amparafa;
	} else {
		$color_badge_amparafa = "w3-green";
	}
	$note_prix_amparafa = $donnees['note_prix_amparafa']."";
	if ($note_prix_amparafa == "NC" || $note_prix_amparafa == ""){ $difference_prix_amparafa ="";	}
	//----------------------------------------------
	$pu_name = "<b>Tantely</b>";
	
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['id_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $pu_name; ?></td>
        <td><?php echo "<b>".($donnees['pu_ambato_tantely']+0)."</b>"; ?></td>
        <td><a class="center" href="reset_note_prix_tantely_1.php?id_x=<?php echo $donnees['id_x']; ?>" onclick="confirmationDelete('Do you want to Remove Notification?');return false; post ;"><span class="w3-badge w3-left w3-margin-right <?php echo $color_badge_tantely; ?>"><?php echo $difference_prix_tantely; ?></span></a></td>
        <td><?php echo $donnees['note_x']; ?></td>
        <td><?php echo $donnees['user_x']; ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_x']; ?>">Modifier</button></td>
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
					<form id="form_prix" role="form" action="modifier_prix_produit_ambato_1.php" method="post">
						<div class="form-group">
						<label hidden>Prix Fournisseur</label>
						<input class="form-control" name="prix_fournisseur" value="<?php echo $donnees['prix_fournisseur']; ?>" type="number" step="any" id="<?php echo $id_prix_fournisseur; ?>" oninput="fournisseurFunction($(this));" hidden>
						</div>
						<div class="form-group float-left">
						<label hidden>Pourcentage (%)</label>
						<!-------------------------------->

						<!--------------------------------->
						<input class="form-control" name="pourcentage" value="<?php echo $pourcentage; ?>" type="number" step="any" id="<?php echo $id_pourcentage; ?>" oninput="pourcentageFunction($(this));" hidden>
						</div>
						<div class="form-group  float-right">
						<label hidden>Benefice (Ariary)</label>
						<input class="form-control" name="benefice" type="number" step="any" value="<?php echo $benefice; ?>" id="<?php echo $id_benefice; ?>" oninput="beneficeFunction($(this));" hidden>
						</div>
						<div class="form-group">
						<label hidden>Prix Unitaire GLOBALE (Ariary)</label>
						<input class="form-control btn-success" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="number" id="<?php echo $id_prix_de_vente; ?>" oninput="pvFunction($(this));" hidden>
						<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>" hidden>
						</div>
						<div class="form-group">
						<label hidden>PU de Demarrage Amparafa (Ariary)</label>
						<input class="form-control btn-warning" name="pu_aparafa" type="number" step="any" value="<?php echo $pu_aparafa; ?>" hidden>
						</div>
						<div class="form-group">
						<label>PU Ambato Tantely (Ariary)</label>
						<input class="form-control btn-secondary" name="pu_tantely" type="number" step="any" value="<?php echo $pu_tantely; ?>">
						</div>
						<div class="form-group">
						<label hidden>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note_x" value="<?php echo $donnees['note_x']; ?>" type="text" hidden>
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
<br>
<br>
<br>
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
	var pu = document.getElementById(id_prix_de_vente).value;
	//alert(id_prix_de_vente);
      var f = e.val();
      var b = pu - f;
      var p = ((pu - f)*100)/f;

       $("#"+id_benefice).val(b);
       $("#"+id_pourcentage).val(p);
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