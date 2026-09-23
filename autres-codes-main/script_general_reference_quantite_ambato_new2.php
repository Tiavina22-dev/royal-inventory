<!DOCTYPE html>
<html>
<head>
    <title>Nouveau Stock Ambato</title>
    <!---add bootstrap css--->
    <script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">
    <!---add other css--->
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
<h4 id="ancre1" class="text-center">NOUVEAU STOCK AMBATO TANTELY 26-31 JUILLET 2021</h4>
<?php
//Connect to BD
include('connect.php');
$nom_client_fournisseur ='Ambato_Tantely';
   //Query to liste searched product
   $query_stock_inventaire = "SELECT produit.id_x as id_x, nom_x, prix_de_vente,prix_unitaire, (SUM(qt)) as sm, reference_x, note_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur ='Ambato_Tantely' AND status ='2nd_General_Inventory_26_Jolay_2021' GROUP BY id_x ORDER BY nom_x;";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array());
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>No</th>
        <th>NOM DE PRODUIT</th>
        <th>REFERENCE</th>
        <th class="text-right">QT</th>
        <th class="text-right">PU</th>
        <th class="text-right">Montant</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
$total = 0;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
//$ref_id = "ref_id".$j;
//echo $ref_id;
$Montant = 0;
if ($donnees['sm']>0) {
    $Montant = $donnees['prix_unitaire']*$donnees['sm'];
    $total = $total+$Montant;
}                                          
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td class="text-right"><?php echo $donnees['sm']; ?></td>
        <td class="text-right"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
        <td class="text-right"><?php echo number_format($Montant,0, "", " "); ?></td>
        </tr>
<?php
$j = $j+1;
}
?>
        <tr>
            <th>No</th>
            <th>REFERENCE</th>
            <th>NOM DE PRODUIT</th>
            <th class="text-right">QT</th>
            <th class="text-right">PU</th>
            <th class="text-right"><?php echo number_format($total,0, "", " "); ?></th>
        </tr>
        </tbody>
    </table>
<br>
<br>
 <?php
    }
 $q->closeCursor();
 //-------------------------STOCK PREVIOUS INACTIF--------------------------------
 $query_stock_inventaire = "SELECT produit.id_x as id_x, nom_x, prix_de_vente,prix_unitaire, (SUM(qt)) as sm, reference_x, note_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur ='Ambato_Tantely' AND status ='previous' GROUP BY id_x ORDER BY nom_x;";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array());
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<br>
<br>
<h4 id="ancre1" class="text-center">PRECEDENT STOCK ABANBONNE (<a href="remove_previous_mvt.php">Eliminer Tous les Stock-Ventes precedents</a>)</h4>
   <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>No</th>
        <th>NOM DE PRODUIT</th>
        <th>REFERENCE</th>
        <th class="text-right">QT</th>
        <th class="text-right">PU</th>
        <th class="text-right">Montant</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
$total = 0;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
//$ref_id = "ref_id".$j;
//echo $ref_id;
//----------- focus only on stock
$query_stock_inventaire = "SELECT * FROM mvt WHERE status ='2nd_General_Inventory_26_Jolay_2021' AND id_x = ?;";
$qz = $bdd->prepare($query_stock_inventaire);
$qz->execute(array($donnees['id_x']));
//Number of Line
$nb_line_nw=$qz->rowCount ();
if ($nb_line_nw == 0) {
//---------------------------
$Montant = 0;
if ($donnees['sm']>0) {
    $Montant = $donnees['prix_unitaire']*$donnees['sm'];
    $total = $total+$Montant;
}                                          
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td class="text-right"><?php echo $donnees['sm']; ?></td>
        <td class="text-right"><?php echo number_format($donnees['prix_unitaire'],0, "", " "); ?></td>
        <td class="text-right"><?php echo number_format($Montant,0, "", " "); ?></td>
        </tr>
<?php
$j = $j+1;
} // End if nb_line
}
?>
        <tr>
            <th>No</th>
            <th>REFERENCE</th>
            <th>NOM DE PRODUIT</th>
            <th class="text-right">QT</th>
            <th class="text-right">PU</th>
            <th class="text-right"><?php echo number_format($total,0, "", " "); ?></th>
        </tr>
        </tbody>
    </table>
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