<!DOCTYPE html>
<html>
<head>
	<title>Gerer Article</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<?php
//GET PARAMETER
	$nom_client_fournisseur ="Dont refresh on this page";
	//Cookies from Delete
	if (isset($_COOKIE['nom_client_fournisseur'])) 
	{
   $nom_client_fournisseur=$_COOKIE['nom_client_fournisseur'];
 	}
	//from stock_recap
	if (isset($_GET['nom_client_fournisseur'])) {
  	$nom_client_fournisseur = $_GET['nom_client_fournisseur'];
	}

	$description_date ="Journal du 22/10/20";
	//Cookies from Delete
	if (isset($_COOKIE['description_date'])) 
	{
   $description_date=$_COOKIE['description_date'];
 	}
	//from stock_recap
	if (isset($_GET['description_date'])) {
  	$description_date = $_GET['description_date'];
	}
	//echo "Nom Client".$nom_client_fournisseur;
	//echo "Description Date".$description_date;
?>
<h4 class="text-center"><?php echo $description_date;?> | <?php echo $nom_client_fournisseur;?></h4>
<?php
//Connect to BD
include('connect.php');
//QUERY REFERENCE TEST
	$query_num_stock = $bdd->query('SELECT reference_x FROM produit');
	$u = 0;
	while ($reference_x = $query_num_stock -> fetch())
{
	$u = $u + 1;
	$ref[$u] = $reference_x['reference_x'];
	//echo $reference_x['reference_x'];
}
	//$u = 2;
	$query_num_stock ->closeCursor();
?>
<!------------SIMPLE SEARCH------------------>
<!---------------search result---------------------------->
<?php
/*
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //echo $key_word;
}
*/

   //Query to liste searched product
   $query_stock_inventaire = 'SELECT *,nom_x,prix_unitaire,qt,numero_commande_stock,ref_commande_stock from mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND description_date = ? ;';
	$q = $bdd->prepare($query_stock_inventaire);

	$q->execute(array($nom_client_fournisseur, $description_date));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='vente_recap_amparafa.php'>RETOURS</b><br>";
	} else {
	
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table class="table-info table">
        <thead>
        <tr class="table-bordered">
        <th rowspan="2">NOM DE PRODUIT</th>
        <th rowspan="2" class="text-center">QT</th>
        <th colspan="2" class="text-center">ANOMEZANA</th>
        <th colspan="2" class="text-center">IVAROTANA</th>
        <th rowspan="2" class="text-center">Tombony</th>
        <th rowspan="2" class="text-center">NOTE</th>
        </tr>
        <tr class="table-bordered">
        <th class="text-center">PU</th>
        <th class="text-center">MT</th>
        <th class="text-center">PU</th>
        <th class="text-center">MT</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;

//Query searcher word
$ref_id = "xxxxxxs";
$bnc_total = 0;
$total_anomezana = 0;
$total_ivarotana = 0;
while ($donnees = $q -> fetch())
{ 
$ref_id = $j;
//echo $ref_id; 
$text_id1 = 'text_id1'.$j;
$text_id2 = 'text_id2'.$j;
$benefice_aparafa = ($donnees['prix_aparafa']-$donnees['prix_unitaire'])*($donnees['qt']*(-1));
$bnc_total = $bnc_total + $benefice_aparafa;
$total_anomezana = $total_anomezana + ($donnees['prix_unitaire']*($donnees['qt']*(-1)));
$total_ivarotana = $total_ivarotana + ($donnees['prix_aparafa']*($donnees['qt']*(-1)));
$j = $j+1;                 
?>
<!------------------------------------------------->
        <tr class="table-bordered">
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-center"><?php echo $donnees['qt']*(-1); ?></td>
        <td class="text-right"><?php echo $donnees['prix_unitaire']; ?></td>
        <td class="text-right"><?php echo $donnees['prix_unitaire']*($donnees['qt']*(-1)); ?></td>
        <td class="text-right"><?php echo $donnees['prix_aparafa']; ?></td>
        <td class="text-right"><?php echo $donnees['prix_aparafa']*($donnees['qt']*(-1)); ?></td>
        <td class="text-right"><?php echo $benefice_aparafa; ?></td>
        <td><?php echo $donnees['note'].' | '.$donnees['note_x']; ?></td>
    </tr>
<!-------------------------------------->
<?php
$activity_no = $donnees['numero_commande_stock'];
}
//Show vola nalain i Aparafa
	$depense_aparafa = 0;
	$query_nalain_aparafa = 'SELECT montant,motif,depense_aparafa FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query_nalain_aparafa);
	$q->execute(array($activity_no));
	$nbr=$q->rowCount ();
	$data = $q -> fetch();
	$note_depense = "";
	if ($nbr==0) {
		$depense_aparafa = 0;
		$depense_royal = 0;
	} else {
		$depense_aparafa = $data['depense_aparafa'];
		$depense_royal = $data['montant'];
		$note_depense = $data['motif'];
	}
	
	$q->closeCursor();
//Show vola tsy ampy
	$vola_tsy_ampy = 0;
	$query_tsy_ampy = 'SELECT resolution,note_general FROM recap_vente WHERE no_activite = ?';
	$q = $bdd->prepare($query_tsy_ampy);
	$q->execute(array($activity_no));
	$nbr=$q->rowCount ();
	$data = $q -> fetch();
	if ($nbr==0) {
		$vola_tsy_ampy = 0;
		
	} else {
		$vola_tsy_ampy = $data['resolution']*(-1);
		
	}
	if ($vola_tsy_ampy==0) {
		$note ="";
	} else {
		$note = $data['note_general'];
	}
	
	$q->closeCursor();
//-------------------------
?>
	<tr class="table-bordered">
        <th>GRAND TOTAL</th>
        <th></th>
        <th></th>
        <th class="text-right"><?php echo $total_anomezana; ?></th>
        <th></th>
        <th class="text-right"><?php echo $total_ivarotana; ?></th>
        <th class="text-right"><?php echo $bnc_total;  ?> </th>
        <th></th>
    </tr>
    <tr class="table-bordered">
    	 <th colspan="3">DEPENSE ROYAL (<?php echo $note_depense;  ?>)</th>
    	  <th class="text-right"><?php echo $depense_royal;  ?></th>
        <th class="text-right" colspan="2">VOLA TSY AMPY</th>
        <th class="text-right"><?php echo $vola_tsy_ampy;  ?></th>
        <th><?php echo $note;  ?></th>
    </tr>
    <tr class="table-bordered">
    	<th colspan="3">VERSEMENT FINAL POUR ROYAL</th>
    	  <th class="text-right"><?php echo $total_anomezana-$depense_royal;  ?></th>
        <th colspan="2" class="text-right">VOLA NALAINA</th>
        <th class="text-right"><?php echo $depense_aparafa;  ?></th>
        <th></th>
    </tr>
    <tr class="table-bordered">
        <th colspan="6" class="text-right">AMBIM-BOLA</th>
        <th class="text-right"><?php echo $bnc_total - $depense_aparafa - $vola_tsy_ampy;  ?></th>
        <th></th>
    </tr>
        </tbody>
    </table>
    <br>
    <br>
 <?php
	}
 $q->closeCursor();
 
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->
<script>
function referenceFunction(e)
{
     //alert("Search suggestions can come here!!");
     var u = <?php echo json_encode($u); ?>;
     //var y = $(this).val();
     //var val = e.target.value;
     //alert(e.val());
     //alert(e.attr('id'));
     //e.style.borderColor = "red";
     var jArray = [];
     jArray = <?php echo json_encode($ref); ?>;
     var msg = "";
     var color;
     var input = e.attr('id');
     var y = e.val();
     var text_id1 = 'text_id1'+input;
     var text_id2 = 'text_id2'+input;
     //alert(text_id1);
  	document.getElementById(input).style.borderColor = "green";
      for(var i=1; i<=u; i++)
      { //alert(jArray[i]);

      	if (jArray[i]==y) {
      		msg="Mety tsara";
      	} else {
      		//alert("nook");
      		//color = "green";
      	}
      	
      }
      //write notification on div id=warning_msg ;
      if (msg=="Mety tsara") {
      	color = "green";
      } else {
      	color = "red";
      	msg="Reference efa misy ampiasaina";
      }
     $("#"+text_id1).text(msg);
     $("#"+text_id2).text(msg);
     document.getElementById(input).style.borderColor = color;
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>