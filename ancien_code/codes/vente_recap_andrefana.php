<!DOCTYPE html>
<html>
<head>
	<title>VENTE ANDREFANA</title>
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
 <?php
//Connect to BD
include('connect.php');
//Read cookie for validation msg
 $msg_supression ="";
 if (isset($_COOKIE['msg_supression'])) 
{
   $msg_supression=$_COOKIE['msg_supression'];
 }
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------------------------->
<!---------TO REMOVED : COMMENT THE FOLLOWING CODE-------->
<!--Debut du collapsible--->
<div id="contenu">
	<!--Liste tous les stocks--->
		<div id="allstock">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
	<h4 class="text-center">JOURNAL DE VENTE ANDREFANA</h4>
    <h5 class="text-center text-danger"><?php echo $msg_supression; ?></h5>
	 <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
        <th></th>
        <th>Act No</th>
        <th>Point De vente</th>
        <th>Date</th>
        <th class="text-center">Nb vente</th>
        <th>Pour Royal</th>
        <th>Depense Royal</th>
        <th>Latsaka</th>
        <th>Mihoatra</th>
        <th>Note</th>
        <th>Remarque</th>
        <th>Details</th>
        <th>Piece Jointe</th>
        <th>By</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = $bdd->query('SELECT *,recap_vente_calc.no_activite as no_activite FROM recap_vente_calc INNER JOIN mvt_calc ON recap_vente_calc.no_activite = mvt_calc.numero_commande_stock WHERE type_de_mvt = "vente" GROUP BY mvt_calc.numero_commande_stock ORDER BY id DESC;');
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
$i = "|";
while ($donnees = $query_journal_vente -> fetch())
{
//Query for x for each action
$query_nb_x = 'SELECT id_mvt,numero_commande_stock FROM mvt_calc WHERE id_x = 134655 AND numero_commande_stock = ?';
	$q = $bdd->prepare($query_nb_x);
	$q->execute(array($donnees['no_activite']));
	$nb_x=$q->rowCount ();
	$q->closeCursor();
//-------------------------
//Show vola nalain i Aparafa
	$query_nalain_aparafa = 'SELECT SUM(depense_aparafa) as depense_aparafa FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query_nalain_aparafa);
	$q->execute(array($donnees['no_activite']));
	$nbr=$q->rowCount ();
	$data = $q -> fetch();
	if ($nbr==0) {
		$depense_aparafa = 0;
	} else {
		$depense_aparafa = $data['depense_aparafa']*(-1);
	}
	
	$q->closeCursor();

//-------------------------
//Show depense royal
	$query = 'SELECT SUM(montant) as depense_royal FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query);
	$q->execute(array($donnees['no_activite']));
	$nbr=$q->rowCount ();
	$data = $q -> fetch();
	if ($nbr==0) {
		$depense_royal = 0;
	} else {
		$depense_royal = $data['depense_royal']*(-1);
	}
	
	$q->closeCursor();
//-------------------------
//List Motif depense royal
	$query = 'SELECT * FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query);
	$q->execute(array($donnees['no_activite']));
	$motif = '';
		while ($data = $q -> fetch()) {
		if ($data['montant']!=0) {$motif =$motif.'# '.$data['montant'].' : '.$data['motif']."\n";}
		
		}
	
	$q->closeCursor();
//-------------------------
$sum=$donnees['Montant']+$sum;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
$path = str_replace("\\", "/", $path);

//echo $path;
$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);

$point_de_vente = $donnees['nom_client_fournisseur']; 
echo $i;                    
?>
<!------------------------------------------------->
        <tr>
        <td><a class="center" href="supprimer_journal_andrefana.php?numero_commande=<?php echo $donnees['no_activite']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
        <td><?php echo $donnees['no_activite']; ?></td>
        <td><?php echo $donnees['nom_client_fournisseur']; ?></td>
        <td><a href="vente_recap_details_andrefana.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $donnees['no_activite'];?>"><?php echo $donnees['description_date']; ?></a></td>
        <td class="text-center"><?php echo $donnees['nb_ligne']; ?></td>
        <td style="text-align: right;"><b><?php echo $donnees['Montant'];?></b></td>
        <?php
        $dr = $depense_royal+0;
        if ($dr == 0) {
       	?>
       	<td style="text-align: right;"><b><?php echo $dr;?></b></td>
       	<?php
        } else {
        ?>
        <td style="text-align: right;"><b><a href="" data-toggle="modal" data-target="#<?php echo "mo".$donnees['id']; ?>"><?php echo $dr;?></a></b></td>
        <?php
        }
        ?>
        <td style="text-align: right;"><b><?php echo $donnees['resolution'];?></b></td>
        <td style="text-align: right;"><b><?php echo $donnees['mihoatra']+0;?></b></td>
        <td><?php echo $donnees['note_general']; ?></td>
        <td><?php echo $donnees['c_point']; ?></td>
        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id']; ?>">Details</button></td>
        <td><?php         
        //print each file name
        echo "<ul>";

        foreach($local as $item)
        {
        echo '<li><a href="zoom.php?path='.$item.'">'.$item.'</a></li>';
        }

        echo "</ul>";
        ?>
        </td>
        <td><?php echo $donnees['user_mvt']; ?></td>
        </tr>
<!-------FORM DE DETAILS-------->
        <!-- modal form DETAILS-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id']; ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">DETAILS DU <?php echo $donnees['description_date']." : ".$donnees['nom_client_fournisseur']; ?></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <div>
                        <textarea class="form-control" rows="<?php echo $rowtextarea; ?>" id="comment"><?php echo $donnees['history']; ?></textarea>
                    </div>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
<!-------FORM DE MOTIF DEPENSE-------->
        <!-- modal form MOTIF-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "mo".$donnees['id']; ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">DEPENSE DANS <?php echo $donnees['description_date']." : ".$donnees['nom_client_fournisseur']; ?></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <div>
                        <textarea class="form-control" rows="6" id="comment"><?php echo $motif; ?></textarea>
                    </div>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->

<!-------------------------------------->
<?php
}
?>
        </tbody>
    </table>
    <br>
    <br>
			</div>			
		</div>
	<!-------------------------->



<!--FIN du collapsible----->
<!--------------END OF THE CODE TO REMOVED-------->
<!--------------LISTE DU STOCK EN COURS----------->
</div>

<!-------------------------------------->
<!--Javascript--->
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