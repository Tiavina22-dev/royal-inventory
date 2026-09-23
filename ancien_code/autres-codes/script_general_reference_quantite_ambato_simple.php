<!DOCTYPE html>
<html>
<head>
    <title>Stock Ambato Tantely</title>
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
<h4 id="ancre1" class="text-center">QUANTITE ACTUELLE AMBATO TANTELY</h4>
<?php
//Connect to BD
include('connect.php');
$nom_client_fournisseur ='Ambato_Tantely';
   //Query to liste searched product
   $query_stock_inventaire = "SELECT produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)) as sm, reference_x, note_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur ='Ambato_Tantely' GROUP BY id_x ORDER BY reference_x DESC;";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array());
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
        <!--
        <th>No</th>
        <th>ID_X</th>
    	-->
        <th>NOM DE PRODUIT</th>
        <th>QT</th>
        <!--
        <th>REFERENCE</th>
    	-->
        <th>Prix estimated</th>
        <th>Prix Recent</th>
        <th>Prix Officiel</th>
        <th>History</th>
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
//GET ALL PRIX ON MVT
//$query_stock = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL AND type_de_mvt='stock' GROUP BY prix_unitaire";
//GET ACCEPTABLE PRIX FROM LATEST APRES INVENTAIRE
$query_stock = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL AND type_de_mvt = 'stock' AND description_date NOT LIKE '%Retirer%' ORDER BY date_time DESC LIMIT 1;";
$qa = $bdd->prepare($query_stock);
$qa->execute(array($donnees['id_x']));
$data = $qa -> fetch();
$prix_recent ='';
$prix_recent = $data['prix_unitaire']+0;
$qa->closeCursor();
//----------------------------------------------
$query_stock = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL";
    $qs = $bdd->prepare($query_stock);
    $qs->execute(array($donnees['id_x']));
    $prix_officiel=$donnees['prix_de_vente'];
    $details = "";
    while ($donnees1 = $qs -> fetch()) //WHILE 2
	{
	$fournisseur = $donnees1['nom_client_fournisseur'];
    //$details = $details."# ".$donnees1['prix_unitaire']." : ".$fournisseur." ".$donnees1['description_date']."<br>";
	$details = $details."# ".$donnees1['prix_unitaire']." : ".$fournisseur." ".$donnees1['description_date']."<br>";
	}
	$qs->closeCursor();
?>
<!------------------------------------------------->
        <tr>
        <!--
        <td><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['id_x']; ?></td>
    	-->
        <td><?php echo $donnees['reference_x']." : ".$donnees['nom_x']; ?></td>
        <td><?php echo floor($donnees['sm']); ?></td>
        <!--
        <td><?php echo $donnees['reference_x']; ?></td>
    	-->
        <td><?php echo $details; ?></td>
        <td><?php echo $prix_recent; ?></td>
        <td><?php echo $prix_officiel; ?></td>
        <td><?php echo $donnees['note_x']; ?></td>
        </tr>
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