<!DOCTYPE html>
<html>
<head>
	<title>Stock Amparafa</title>
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
<h4 id="ancre1" class="text-center">QUANTITE ACTUELLE D'AMPARAFA</h4>
<?php
//Connect to BD
include('connect.php');

   //Query to liste searched product
    $nom_client_fournisseur = 'Amparafa';
   $query_stock_inventaire = "SELECT produit.id_x as id_x, nom_x, prix_de_vente, qt, reference_x, note_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur ='Amparafa' AND reference_x LIKE 'Q_%' GROUP BY id_x ORDER BY nom_x;";
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
        <th>No</th>
        <th>ID_X</th>
        <th>NOM DE PRODUIT</th>
        <th class="text-right">QT</th>
        <th class="text-right">PU</th>
        <th class="text-right">Montant</th>
        <th>REFERENCE</th>
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
	//HANDLE QT DISPO FOR EACH POINT DE VENTE
    //QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$nom_client_fournisseur));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$nom_client_fournisseur));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$nom_client_fournisseur));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //----------------------
                            $qt_dispo_v1 = $vente + $stock + $stock_GI;
    //--------------------- 
    $Montant = 0;
if ($qt_dispo_v1>0) {
    $Montant = $donnees['prix_de_vente']*$qt_dispo_v1;
    $total = $total+$Montant;
}                   
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['id_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-right"><?php echo $qt_dispo_v1; ?></td>
        <td class="text-right"><?php echo number_format($donnees['prix_de_vente'],0, "", " "); ?></td>
        <td class="text-right"><?php echo number_format($Montant,0, "", " "); ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
    	</tr>
<?php
$j = $j+1;
}
?>
        <tr>
            <th>No</th>
            <th>ID_X</th>
            <th>NOM DE PRODUIT</th>
            <th class="text-right">QT</th>
            <th class="text-right">PU</th>
            <th class="text-right"><?php echo number_format($total,0, "", " "); ?></th>
            <th>REFERENCE</th>
        </tr>
        </tbody>
    </table>
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