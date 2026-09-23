<!DOCTYPE html>
<html>
<head>
    <title>Vente Amparafa</title>
    <!---add bootstrap css--->
    <script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

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
<h4 id="ancre1" class="text-center">LES VENTES ELECTRONIQUE</h4>
<?php
//Connect to BD
include('connect.php');

   //Query to liste searched product
   $query_stock_inventaire = "SELECT prix_aparafa,prix_fournisseur,prix_de_vente,type_de_mvt,produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)*(-1)) as sm, reference_x, note_x,((prix_unitaire-prix_fournisseur)*100/(prix_de_vente)) as pourcentage,ref_commande_stock,(prix_unitaire-prix_fournisseur) as tombony FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur ='Ambaibo_Electronique' AND type_de_mvt='vente' GROUP BY id_x ORDER BY tombony DESC;";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array());
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>REF_ID</th>
        <th>NOM DE PRODUIT</th>
        <th>REFERENCE</th>
        <th>QT</th>
        <th>PF</th>
        <th>PU</th>
        <th>%</th>
        <th>TOMBONY</th>
        <th>MT</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
//$ref_id = "ref_id".$j;
//echo $ref_id;
$pourcentage = $donnees['pourcentage'];                     
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#ancre1"><?php echo $donnees['ref_commande_stock']; ?></a></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo number_format($donnees['sm'],2); ?></td>
        <td><?php echo $donnees['prix_fournisseur']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo number_format($pourcentage,2); ?></td>
        <td><?php echo $donnees['tombony']; ?></td>
        <td><?php echo $donnees['sm']*$donnees['prix_aparafa']; ?></td>
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
<br>
<br>
<br>
<br>
 <?php
    }
 $q->closeCursor();
 
?>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
</html>