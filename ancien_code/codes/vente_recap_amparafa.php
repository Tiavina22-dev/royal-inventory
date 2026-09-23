<!DOCTYPE html>
<html>
<head>
	<title>COMPTE AMPARAFA</title>
	<!---add bootstrap css--->
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
<br>
<br>
<br>
<br>
<br>
<br>
<div id="contenu">
	<!--Liste tous les stocks--->
	<div class="card">
		<div id="allstock">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
	<h4 class="text-center">COMPTE AMPARAFA</h4>
    <h5 class="text-center text-danger"><?php echo $msg_supression; ?></h5>
    <div>
	<input type="search" class="light-table-filter" data-table="table-bordered" placeholder="Filter/Search">
	<a href="vente_recap_amparafa_printed.php">
	<input type="button" class="float-right bg-secondary font-weight-bold text-light" value="PRINTED FORMAT">
	</a>
	</div>
	 <table class="table table-bordered table-sm">
	
        <thead>
        <tr>
        <th>Act No</th>
        <th>Status</th>
        <th>Date</th>
        <th>Mihoatra</th>
        <th>Pour Royal</th>
        <th>Pour A/fa</th>
        <th>Resolution</th>
        <th>Nalain A/fa</th>
        <th>Note</th>
        <th>Remarque</th>
        <th>Details</th>
        <th>Piece Jointe</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = $bdd->query("SELECT *,recap_vente.status as status,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = 'vente' AND nom_client_fournisseur='Amparafa' GROUP BY mvt.numero_commande_stock ORDER BY id DESC;");
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
$sum_resolution = 0;
$sum_difference = 0;
$sum_depense_aparafa = 0;
$sum_mihoatra = 0;

while ($donnees = $query_journal_vente -> fetch())
{ 
$sum_resolution = $donnees['resolution'] + $sum_resolution;
$sum_difference = $donnees['difference_aparafa'] + $sum_difference;
$sum=$donnees['Montant']+$sum;
$sum_mihoatra=$donnees['mihoatra']+$sum_mihoatra;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
$path = str_replace("\\", "/", $path);

//echo $path;
$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
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
    $sum_depense_aparafa = $sum_depense_aparafa + $depense_aparafa;
    
    $q->closeCursor();
    $point_de_vente = $donnees['nom_client_fournisseur'];

//-------------------------------------------------                    
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['no_activite']; ?></td>
        <td><?php echo $donnees['status']; ?></td>
        <td><a href="vente_recap_details_amparafa.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>"><?php echo $donnees['description_date']; ?></a></td>
        <td style="text-align: right;"><b><?php echo $donnees['mihoatra']+0;?></b></td>
        <td style="text-align: right;"><b><?php echo $donnees['Montant'];?></b></td>
        <td style="text-align: right;"><b><?php echo $donnees['difference_aparafa'];?></b></td>
        <td style="text-align: right;"><b><?php echo $donnees['resolution'];?></b></td>
        <td style="text-align: right;"><b><?php echo ABS($depense_aparafa);?></b></td>
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

<!-------------------------------------->
<?php
}
?>
	<tr class="bg-info">
        <th colspan="3" class="text-center">TOTAL >>>>></th>
        <th><?php echo $sum_mihoatra; ?></th>
        <th>Pour Royal</th>
        <th><?php echo $sum_difference; ?></th>
        <th><?php echo $sum_resolution; ?></th>
        <th><?php echo $sum_depense_aparafa; ?></th>
        <th class="text-right">AMBIM-BOLA NY AMPARAFA</th>
        <th colspan="2"><?php
        $resultante = 0;
        $resultante = $sum_difference+$sum_resolution+$sum_depense_aparafa; 
        if ($resultante >= 0) {
        	echo $resultante;
        	$notification ="COMPTE OK";
        } else {
        	echo "0";
        	$notification ="MAMERIM-BOLA RY ZAREO (".$resultante*(-1)." Ar)";
        }
        ?> Ar</th>
        <th><?php echo $notification; ?></th>
    </tr>
        </tbody>
    </table>
        <div>
        <a href="vente_recap_amparafa_reset.php" onclick="confirmationDelete('DEJA RESOLU?');return false; post ;">
        <input type="button" class="float-right bg-secondary font-weight-bold text-light" value="RESOLU?" disabled>
        </a>
    </div>
    <br>
    <br>
    <br>
    <br>
			</div>			
		</div>
	</div>
	<!-------------------------->



<!--FIN du collapsible----->
<!--------------END OF THE CODE TO REMOVED-------->
<!--------------LISTE DU STOCK EN COURS----------->
</div>

<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>