<!DOCTYPE html>
<html>
<head>
	<title>COMPTE BEJOFO ET AUTRE</title>
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
<div id="contenu">
	<!--Liste tous les stocks--->
	<div class="card">
		<div id="allstock">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
	<h4 class="text-center">COMPTE BEJOFO</h4>
    <h5 class="text-center text-danger"><?php echo $msg_supression; ?></h5>
    <div>
	<input type="search" class="light-table-filter" data-table="table-bordered" placeholder="Filter/Search">
	<input type="button" class="float-right bg-secondary font-weight-bold text-light" data-toggle='modal' data-target='#top20' value="OTHER SHOP">
	<!------------------------------------------->
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="top20" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                        <div class="form-group">
                                        <a href="vente_recap_bejofo_activate.php">
                                        <button class="form-control btn btn-danger">BEJOFO</button>
                                        </a>
                                        </div>
                                        <div class="form-group">
                                        <a href="vente_recap_ambato_activate.php">
                                        <button class="form-control btn btn-info">AMBATO TANTELY</button>
                                        </a>
                                        </div>
                                        <div class="form-group">
                                        <a href="vente_recap_veve_activate.php">
                                        <button class="form-control btn btn-secondary">AMBATO VEVE</button>
                                        </a>
                                        </div>
                                        <div class="form-group">
                                        <a href="vente_recap_soalazaina_activate.php">
                                        <button class="form-control btn btn-primary">RANTO SOALAZAINA</button>
                                        </a>
                                        </div>
                                        </div>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
	</div>
	 <table class="table table-bordered table-sm">
	
        <thead>
        <tr>
        <th>Act No</th>
        <th>Status</th>
        <th>Date</th>
        <th>Versement Royal</th>
        <th>Mihoatra</th>
        <th>Tsy Ampy</th>
        <th>Note</th>
        <th>Details</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = $bdd->query("SELECT *,recap_vente.status as status,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = 'vente' AND nom_client_fournisseur='Bejofo' AND recap_vente.status ='NON_RESOLU' GROUP BY mvt.numero_commande_stock ORDER BY id DESC;");
//Query to liste All Waiting command
$rowtextarea = 0;
$sum_resolution = 0;
$sum_mihoatra = 0;

while ($donnees = $query_journal_vente -> fetch())
{ 
if (($donnees['mihoatra']+0)!= 0 OR ($donnees['resolution']+0)!= 0) {
$sum_resolution = $donnees['resolution'] + $sum_resolution;
$sum=$donnees['Montant']+$sum;
$sum_mihoatra=$donnees['mihoatra']+$sum_mihoatra;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
    $point_de_vente = $donnees['nom_client_fournisseur'];

//-------------------------------------------------                   
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['no_activite']; ?></td>
        <td><?php echo $donnees['status']; ?></td>
        <td><a href="vente_recap_details_amparafa.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>"><?php echo $donnees['description_date']; ?></a></td>
        <td style="text-align: right;"><b><?php echo $donnees['Montant'];?></b></td>
        <td style="text-align: right;"><b><?php echo $donnees['mihoatra']+0;?></b></td>
        <td style="text-align: right;"><b><?php echo abs($donnees['resolution']+0);?></b></td>
        <td><?php echo $donnees['note_general']; ?></td>
        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id']; ?>">Details</button></td>
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
	 } //END IF
}
?>
	<tr class="bg-info">
        <th colspan="3" class="text-center">TOTAL >>>>></th>
        <th style="text-align: right;">Versement Royal</th>
        <th style="text-align: right;"><?php echo $sum_mihoatra; ?></th>
        <th style="text-align: right;"><?php echo $sum_resolution; ?></th>
        <th colspan="2" class="text-center"><?php
        $resultante = 0;
        $resultante = $sum_mihoatra+$sum_resolution; 
        if ($resultante >= 0) {
        	$notification ="VOLA MIHOATRA (".$resultante." Ar)";
        	echo $notification;
        } else {  
        	$notification ="TL VERSEMENT TSY AMPY BEJOFO : ".$resultante*(-1)." Ar";
        	echo $notification;
        }
        ?></th>
    </tr>
        </tbody>
    </table>
        <div>
        <input type="button" class="float-right bg-secondary font-weight-bold text-light" value="RESOLU?" data-toggle='modal' data-target='#resolution'>
    </div>
    <!------------------------------------------->
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="resolution" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                        <div class="form-group">
                                        <a href="vente_recap_bejofo_reset_activate.php">
                                        <button class="form-control btn btn-danger" onclick="confirmationDelete('DEJA RESOLU?');return false; post ;">BEJOFO</button>
                                        </a>
                                        </div>
                                        <div class="form-group">
                                        <a href="vente_recap_ambato_reset_activate.php">
                                        <button class="form-control btn btn-info" onclick="confirmationDelete('DEJA RESOLU?');return false; post ;">AMBATO TANTELY</button>
                                        </a>
                                        </div>
                                        <div class="form-group">
                                        <a href="vente_recap_veve_reset_activate.php">
                                        <button class="form-control btn btn-secondary" onclick="confirmationDelete('DEJA RESOLU?');return false; post ;">AMBATO VEVE</button>
                                        </a>
                                        </div>
                                        </div>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
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