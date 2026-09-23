<!DOCTYPE html>
<html>
<head>
    <title>ANALYSE STOCK</title>
    <!---add bootstrap css--->
    <script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); 
 ?>
</body>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h2 id="ancre1" class="text-center text-warning"><?php
if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente = $_COOKIE['point_de_vente_cookie'];
		
   $critaire = $_COOKIE['critaire'];
   $description = $_COOKIE['description'];

   echo strtoupper($point_de_vente)." | <span class = 'text-info'>".strtoupper($description).'</span>';
}
?></h2>
 <div class="text-center">
	<button type="submit" class="btn btn-secondary" data-toggle="modal" data-target="#Demarrer">CRITERE</button>
	</div>
<!-------------------------------------->
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"><b>CRITERE DE L'ANALYSE</b></h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="stock_critere.php" method="post">
    <div class="container border border-secondary">
	<div class="">
        <input type="radio" name="zero" value="vente_zero">
        <label><b>Produit Sans Vente Seulement (Vente = 0)</b></label>
    </div>
    <div>
        <input type="radio" name="zero" value="stock_zero">
        <label><b>Produit Sans Stock Seulement (Stock = 0)</b></label>
    </div>
    <div>
        <input type="radio" name="zero" value="Stock_Vente" checked>
        <label><b>Produit avec Stock et Vente Seulement</b></label>
    </div>
    <div>
        <input type="radio" name="zero" value="All" checked>
        <label><b>Tous les Vente ou Stock</b></label>
    </div>
    </div>
    <br>
	<!------------------AUTO LISTE SHOP------------------>
	<div class="form-group">
		<label><b>POINT DE VENTE</b></label>
		<select class="form-control btn btn-secondary" name="point_de_vente">
		<option value="" selected disabled hidden>Choisir Point de vente</option>
		<?php
		$query_shop = "SELECT * FROM shop ORDER BY long_name;";
		$query_shop = $bdd->prepare($query_shop);
		$query_shop->execute(array());
		while ($donnees = $query_shop -> fetch())
		{
		?>
		<option value=<?php echo $donnees['short_name']; ?>><?php echo $donnees['long_name']; ?></option>
	    <?php
	    }
	    ?>
		</select>
	</div>
	<!------------------END AUTO LISTE SHOP------------------>
	<div class="form-group">
		<button type="submit" class="btn btn-success">Valider</button>
	</div>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
<?php
//Connect to BD
include('connect.php');


if (isset($_COOKIE['point_de_vente_cookie'])) 
{


   if ($critaire == 'vente_zero') {
        $query_stock_inventaire = "SELECT s.id_x, s.nom_x,s.reference_x, (0) as vente,stock,(0+stock) as reste FROM (SELECT produit.id_x as id_x, nom_x, (SUM(qt)) as vente, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='vente' GROUP BY id_x) as v RIGHT JOIN 
(SELECT * FROM ((SELECT z.id_x, z.nom_x, stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z LEFT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE w.id_x IS NULL) UNION ALL (SELECT w.id_x, w.nom_x,stock_GI as stock,w.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z RIGHT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE z.id_x IS NULL)) as U1 UNION ALL (SELECT z.id_x, z.nom_x,(stock+stock_GI) as stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z INNER JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x)) as s ON v.id_x = s.id_x WHERE v.id_x IS NULL;";
        $q = $bdd->prepare($query_stock_inventaire);

        $q->execute(array($point_de_vente , $point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente));
   }
   if ($critaire == 'stock_zero') {
    $query_stock_inventaire = "SELECT v.id_x, v.nom_x,v.reference_x, vente,(0) as stock,(vente) as reste FROM (SELECT produit.id_x as id_x, nom_x, (SUM(qt)) as vente, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='vente' GROUP BY id_x) as v LEFT JOIN 
(SELECT * FROM ((SELECT z.id_x, z.nom_x, stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z LEFT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE w.id_x IS NULL) UNION ALL (SELECT w.id_x, w.nom_x,stock_GI as stock,w.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z RIGHT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE z.id_x IS NULL)) as U1 UNION ALL (SELECT z.id_x, z.nom_x,(stock+stock_GI) as stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z INNER JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x)) as s ON v.id_x = s.id_x WHERE s.id_x IS NULL";
        $q = $bdd->prepare($query_stock_inventaire);

        $q->execute(array($point_de_vente , $point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente));

   }

   if ($critaire == 'Stock_Vente') {
    $query_stock_inventaire = "SELECT v.id_x, v.nom_x,v.reference_x, vente,stock,(vente+stock) as reste FROM (SELECT produit.id_x as id_x, nom_x, (SUM(qt)) as vente, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='vente' GROUP BY id_x) as v INNER JOIN 
(SELECT * FROM ((SELECT z.id_x, z.nom_x, stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z LEFT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE w.id_x IS NULL) UNION ALL (SELECT w.id_x, w.nom_x,stock_GI as stock,w.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z RIGHT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE z.id_x IS NULL)) as U1 UNION ALL (SELECT z.id_x, z.nom_x,(stock+stock_GI) as stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z INNER JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x)) as s ON v.id_x = s.id_x";
        $q = $bdd->prepare($query_stock_inventaire);

        $q->execute(array($point_de_vente , $point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente));

   }
   if ($critaire == 'All') {
    $query_stock_inventaire = "SELECT * FROM ((SELECT s.id_x, s.nom_x,s.reference_x, (0) as vente,stock,(0+stock) as reste FROM (SELECT produit.id_x as id_x, nom_x, (SUM(qt)) as vente, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='vente' GROUP BY id_x) as v RIGHT JOIN 
    (SELECT * FROM ((SELECT z.id_x, z.nom_x, stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z LEFT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE w.id_x IS NULL) UNION ALL (SELECT w.id_x, w.nom_x,stock_GI as stock,w.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z RIGHT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE z.id_x IS NULL)) as U1 UNION ALL (SELECT z.id_x, z.nom_x,(stock+stock_GI) as stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z INNER JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x)) as s ON v.id_x = s.id_x WHERE v.id_x IS NULL) UNION ALL (SELECT v.id_x, v.nom_x,v.reference_x, vente,(0) as stock,(vente) as reste FROM (SELECT produit.id_x as id_x, nom_x, (SUM(qt)) as vente, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='vente' GROUP BY id_x) as v LEFT JOIN 
(SELECT * FROM ((SELECT z.id_x, z.nom_x, stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z LEFT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE w.id_x IS NULL) UNION ALL (SELECT w.id_x, w.nom_x,stock_GI as stock,w.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z RIGHT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE z.id_x IS NULL)) as U1 UNION ALL (SELECT z.id_x, z.nom_x,(stock+stock_GI) as stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z INNER JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x)) as s ON v.id_x = s.id_x WHERE s.id_x IS NULL)) as Q1 UNION ALL (SELECT v.id_x, v.nom_x,v.reference_x, vente,stock,(vente+stock) as reste FROM (SELECT produit.id_x as id_x, nom_x, (SUM(qt)) as vente, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='vente' GROUP BY id_x) as v INNER JOIN 
(SELECT * FROM ((SELECT z.id_x, z.nom_x, stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z LEFT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE w.id_x IS NULL) UNION ALL (SELECT w.id_x, w.nom_x,stock_GI as stock,w.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z RIGHT JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x WHERE z.id_x IS NULL)) as U1 UNION ALL (SELECT z.id_x, z.nom_x,(stock+stock_GI) as stock,z.reference_x FROM  (SELECT produit.id_x, nom_x, (SUM(qt)) as stock, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status NOT LIKE 'General_Inventory' GROUP BY id_x) z INNER JOIN (SELECT produit.id_x, nom_x, (SUM(prix_aparafa)) as stock_GI, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur  =  ? AND type_de_mvt='stock' AND status LIKE 'General_Inventory' GROUP BY id_x ORDER BY id_x) w ON z.id_x = w.id_x)) as s ON v.id_x = s.id_x)";
        $q = $bdd->prepare($query_stock_inventaire);

        $q->execute(array($point_de_vente , $point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente,$point_de_vente , $point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente,$point_de_vente , $point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente ,$point_de_vente));

   }
    
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$description."]"." does not exist";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<div class="container">
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>NOM DE PRODUIT</th>
        <th>REFERENCE</th>
        <th class="text-right text-orange-2">VENTE</th>
        <th class="text-right text-purple">STOCK</th>
        <th class="text-right">RESTE</th>
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
	//STOCK LISTE
	//QUERY TO SHOW STOCK
	   $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ORDER BY Date_du_Journal_mvt";
	    $qs = $bdd->prepare($query_stock);
	    $qs->execute(array($donnees['id_x'],$point_de_vente));
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td class="text-right text-orange-2"><b><?php echo $donnees['vente']+0; ?></b></td>
        <td class="text-right text-purple"><a href="#" data-toggle="modal" data-target = "#<?php echo $donnees['reference_x']; ?>"><b><?php echo $donnees['stock']+0; ?></b></a></td>
        <td class="text-right"><b><?php echo $donnees['stock']+$donnees['vente']+0; ?></b></td>
        </tr>
<!-- modal form QUATITE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $donnees['reference_x']; ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><span style='font-weight: bold'>DATE DU STOCK</span></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form">
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-secondary">STOCK</span></label>
                        <ul class="list-group">
                        <?php
                        
                        while ($donnees1 = $qs -> fetch())
                        { 
                        	$text_color = 'text-dark';
                        	$badge_color = 'w3-grey';
                        	$date = $donnees1['description_date'];
                        	if ($donnees1['status']=='General_Inventory') {
                        		$date = 'General Inventory : '.$donnees1['Date_du_Journal_mvt'];
                        		$text_color = 'text-orange-2';
                        		$badge_color = 'w3-orange';

                        	}
                            
                            ?>
                          <li class="list-group-item <?php echo $text_color; ?>"><?php echo $donnees1['nom_client_fournisseur']; ?> | <?php echo $date; ?> | <?php
                          if ($donnees1['id_x'] == 134655) {
                            echo $donnees1['note'].' | ';
                          }
                          echo $donnees1['user_mvt']; ?> | <span style='font-weight: bold'><?php echo $donnees1['prix_unitaire']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                        }
                        $qs->closeCursor();
                        ?>
                        </ul>
                        </div>
                        </div>
                    </form>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
</div>
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
 }//END IF
 else {
 	echo "<h4 id='ancre1' class='text-center text-danger'>CLICK CRITERE</h4>";
 }
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