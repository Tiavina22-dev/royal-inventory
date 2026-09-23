<!DOCTYPE html>
<html>
<head>
    <title>ANALYSE VENTE</title>
    <!---add bootstrap css--->
    <script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); 
 $starting = strtotime("-1 Months");
 $starting = date("Y-m-01", $starting);
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
<h2 id="ancre1" class="text-center text-warning">VENTE <?php
if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente = $_COOKIE['point_de_vente_cookie'];
		
   $debut = DateTime::createFromFormat('Y-m-d',$_COOKIE['debut_date']) ;
   $debut = $debut -> format('d M Y');

   $fin = DateTime::createFromFormat('Y-m-d',$_COOKIE['fin_date']) ;
   $fin = $fin -> format('d M Y');

   echo strtoupper($point_de_vente)." du <span class = 'text-info'>".$debut."</span> au <span class = 'text-info'>".$fin.'</span>';
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
			<h4 class="modal-title">CRITERE DE L'ANALYSE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="vente_critere.php" method="post">
	<div class="form-group">
		<label>DATE DU DEBUT</label>
		<input class="form-control btn-primary" value = <?php echo $starting; ?>  name="debut_date" type="DATE" oninput="unlock($(this));" id = "debut_date">
	</div>
	<div class="form-group">
		<label>DATE DU FIN</label>
		<input class="form-control btn-success" value= <?php echo date("Y-m-d"); ?> name="fin_date" type="DATE">
	</div>
	<!------------------AUTO LISTE SHOP------------------>
	<div class="form-group">
		<label>POINT DE VENTE</label>
		<select class="form-control btn btn-secondary" name="point_de_vente" oninput="unlock($(this));" id = "point_de_vente">
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
		<button type="submit" class="btn btn-success" id = "valider" disabled>Valider</button>
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



if (isset($_COOKIE['debut_date'])) 
{
   $debut_date = $_COOKIE['debut_date'];
}

if (isset($_COOKIE['fin_date'])) 
{
   $fin_date = $_COOKIE['fin_date'];

   //Query to liste searched product
   $query_stock_inventaire = "SELECT prix_aparafa,prix_fournisseur,prix_de_vente,type_de_mvt,produit.id_x as id_x, nom_x, prix_de_vente, (SUM(qt)*(-1)) as sm, reference_x, note_x,((prix_unitaire-prix_fournisseur)*100/(prix_de_vente)) as pourcentage,ref_commande_stock,(prix_unitaire-prix_fournisseur) as tombony FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='vente' AND (Date_du_Journal_mvt BETWEEN ? AND ?)  GROUP BY id_x ORDER BY sm";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array($point_de_vente , $debut_date , $fin_date ));
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$point_de_vente."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<div>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
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
$GT = 0;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
	//$ref_id = "ref_id".$j;
	//echo $ref_id;
$pourcentage = $donnees['pourcentage'];                     
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td class="text-right"><b><a href="#" data-toggle="modal" data-target="#<?php echo ("no".$j); ?>"><?php echo number_format($donnees['sm'],2); ?></a></b></td>
        <!-- modal form QUATITE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><span style='font-weight: bold' class="text-orange-2"><?php echo $donnees['nom_x']; ?></span></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form">
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-purple">VENTE du <?php 
                        $daty = DateTime::createFromFormat('Y-m-d', $debut_date);
                        $daty = $daty -> format('d/m/y');
                        echo $daty; ?> au <?php
                        $daty = DateTime::createFromFormat('Y-m-d', $fin_date);
                        $daty = $daty -> format('d/m/y');
                        echo $daty; ?></span></label>
                        <ul class="list-group">
                        <?php
                        //GET QT DETAILS
						$query_vente_detals = "SELECT *,prix_aparafa,prix_fournisseur,prix_de_vente,type_de_mvt,produit.id_x as id_x, nom_x, prix_de_vente, ABS(qt) as qt, reference_x, note_x,((prix_unitaire-prix_fournisseur)*100/(prix_de_vente)) as pourcentage,ref_commande_stock,(prix_unitaire-prix_fournisseur) as tombony FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='vente' AND (Date_du_Journal_mvt BETWEEN ? AND ?) AND id_x = ? ORDER BY Date_du_Journal_mvt";
					    $query_vente_detals = $bdd->prepare($query_vente_detals);

					    $query_vente_detals -> execute(array($point_de_vente , $debut_date , $fin_date,$donnees['id_x'] ));
                        $total_vente = 0;
                        while ($donnees2 = $query_vente_detals -> fetch())
                        { 
                            $total_vente = $total_vente + $donnees2['qt'];
                            //HANDLE OFF STATUS COLOR
                            $badge_color = 'w3-green';
                            $text_color = '';

                            ?>
                          <li class="list-group-item <?php echo $text_color; ?>"><?php echo $donnees2['nom_client_fournisseur']; ?> | <?php echo $donnees2['description_date']; ?> | <?php
                          if ($donnees2['id_x'] == 134655) {
                            echo $donnees2['note'].' | ';
                          }
                          echo $donnees2['user_mvt']; ?> | <span style='font-weight: bold'><?php echo number_format($donnees2['prix_unitaire'],0, "", " "); ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo ABS($donnees2['qt']); ?></span></li>
                        <?php
                        }
                        $query_vente_detals->closeCursor();
                        ?>

                            <li class="list-group-item"><span style='font-weight: bold'>TOTAL VENTE
                        <span class="w3-badge w3-right w3-margin-right"><?php echo number_format(ABS($total_vente),2); ?></span></span></li>
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
        <td class="text-right"><?php if (($donnees['prix_fournisseur']+0) == 0) {
        	echo "-";
        }else {echo number_format($donnees['prix_fournisseur'],0, "", " ");} ?></td>
        <td class="text-right"><?php echo number_format($donnees['prix_de_vente'],0, "", " "); ?></td>
        <td class="text-right"><?php
        if (($donnees['prix_fournisseur']+0) == 0) {
        	echo "-";
        }else
        {echo number_format($pourcentage,2).'%';} ?></td>
        <td class="text-right"><?php 
        if (($donnees['prix_fournisseur']+0) == 0) {
        	echo "-";
        }else
        {echo number_format($donnees['tombony'],0, "", " ");} ?></td>
        <td class="text-right"><?php $GT = $GT + ($donnees['sm']*$donnees['prix_de_vente']);echo number_format(($donnees['sm']*$donnees['prix_de_vente']),0, "", " "); ?></td>
        </tr>
<?php
$j = $j+1;
}
?>
        </tbody>
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>GRAND TOTAL</th>
            <th class="text-right"><?php echo number_format($GT,0, "", " "); ?></th>
        </tr>
        </thead>
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
<script>
function unlock(e){

    var debut =  e.val();
    var point_de_vente = document.getElementById('point_de_vente').value;
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    //alert(point_de_vente.length);
    if (debut.length != 0 &&  point_de_vente.length != 0) {
        
        //alert("OK");
        //$("#"+i+"L").removeAttr("style");
        $("#valider").removeAttr('disabled');
    }
}
</script>
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
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>