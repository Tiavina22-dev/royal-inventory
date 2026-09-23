<!DOCTYPE html>
<html>
<head>
    <title>Prix Comparaison</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/w3.css">
    <!---add other css--->
</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
</body>
<!--------------------------------------->

<?php
include('connect.php');
  $key_word = "QPECE20ROS";
if ($key_word <> "") 
{
//Query to liste product
   $query_x = "SELECT produit.id_x as id_x,produit.prix_de_vente as prix_officiel,reference_x,nom_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x GROUP BY id_x ORDER BY nom_x;";
    $q = $bdd->prepare($query_x);

    $q->execute(array());
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<h4 class="text-center">LISTE DES PRIX DES PRODUITS</h4>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><b>"."[".$key_word."]"." does not exist on the base, Click <a href='stock_epuise.php'>RETOURS</b><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>Liste PU</th>
        <th>Min</th>
        <th>Max</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;

while ($donnees = $q -> fetch())
{ 
//QUERY TO SHOW ALL MVT
   $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL";
    $qs = $bdd->prepare($query_stock);

    $qs->execute(array($donnees['id_x']));
//GET ALL PRIX
    $pu_list='';
    $pu_list="# ".$donnees['prix_officiel']." : Officiel"."<br>";
    $max = 0;
    $max_complet ='';
    $min = 0;
    $min_complet ='';
while ($donnees1 = $qs -> fetch())
{
	$pu_list = $pu_list."# ".$donnees1['prix_unitaire']." : ".$donnees1['nom_client_fournisseur']." (".$donnees1['type_de_mvt'].")"."<br>";
	
	if ($max < $donnees1['prix_unitaire']) {
	$max = $donnees1['prix_unitaire'];
	$max_complet = $max." : ".$donnees1['nom_client_fournisseur'];
	}
	if ($max <= $donnees['prix_officiel']) {
    	$max_complet = $donnees['prix_officiel']." : Officiel";
    }

	if ($min == 0) {
		$min = $donnees1['prix_unitaire'];
		$min_complet = $min." : ".$donnees1['nom_client_fournisseur'];
	} 	
	if ($min > $donnees1['prix_unitaire']) {
	$min = $donnees1['prix_unitaire'];
	$min_complet = $min." : ".$donnees1['nom_client_fournisseur'];
	}
	if ($min >= $donnees['prix_officiel'] AND $donnees['prix_officiel'] != 0) {
    	$min_complet = $donnees['prix_officiel']." : Officiel";
    }
	
}
$qs->closeCursor();
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $pu_list; ?></td>
        <td><?php echo $min_complet; ?></td>
        <td><?php echo $max_complet; ?></td>
    </tr>
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
 <?php
    }
 $q->closeCursor();
 }
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
</html>