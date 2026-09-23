<!DOCTYPE html>
<html>
<head>
    <title>Stock Negatif</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">
    <!---add other css--->
</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<?php
include('connect.php');
    $nom_client_fournisseur = "";
    if (isset($_GET['nom_client_fournisseur'])) {
    $nom_client_fournisseur = $_GET['nom_client_fournisseur'];
    }
//Query to liste negatif product
   $query_stock_negatif = 'SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, (SUM(qt)) as qt, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND qt<0 GROUP BY id_x ORDER BY qt';
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($nom_client_fournisseur));
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<h4 class="text-center"><?php echo $nom_client_fournisseur;?> || STOCK NEGATIF (<?php echo $nb_line;?>)</h4>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_epuise.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<div>
    <input id="ancre1" type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
    <a href="stock_epuise.php" class="float-right"><button type="submit" class="btn btn-info">RETOURS</button></a>
</div>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>QT</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;

while ($donnees = $q -> fetch())
{ 
               
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['qt']; ?></td>
<?php
$j = $j+1;
}
?>
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
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
</html>