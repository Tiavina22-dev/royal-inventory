<!DOCTYPE html>
<html>
<head>
	<title>COMPTE PRINTED</title>
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
 if (isset($_COOKIE['point_de_vente'])) 
{
   $point_de_vente=$_COOKIE['point_de_vente'];
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
	<a href="vente_recap_bejofo.php"><span class="float-right"> <img src="img/cancel_icon.png" height="50" width="50" background alt="Edit" /></span></a> 
	<h4 class="text-center">COMPTE <?php echo $point_de_vente; ?> NON REGULARISE</h4>
    <h5 class="text-center text-danger"><?php echo $msg_supression; ?></h5>
	 <table class="table table-bordered table-sm">
	
        <thead>
        <tr>
            <th>Date</th>
            <th style="text-align: right;">ROYAL VERSEMENT</th>
            <th style="text-align: right;">VOLA MIHOATRA</th>
            <th style="text-align: right;">VOLA TSY AMPY</th>
            <th style="text-align: left;">FANAZAVANA</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
    $query_journal_vente = "SELECT *,recap_vente.status as status,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND recap_vente.status ='NON_RESOLU' GROUP BY mvt.numero_commande_stock ORDER BY id DESC;";
    $query_journal_vente = $bdd->prepare($query_journal_vente);

    $query_journal_vente->execute(array($point_de_vente));
//Query to liste All Waiting command
$rowtextarea = 0;
$sum_resolution = 0;
$sum_mihoatra = 0;

while ($donnees = $query_journal_vente -> fetch())
{
    if (($donnees['mihoatra']+0)!= 0 OR ($donnees['resolution']+0)!= 0) {
        $sum_resolution = $donnees['resolution'] + $sum_resolution;
        $sum=$donnees['Montant']+$sum;
        $rowtextarea = ($donnees['nb_ligne']*2)+13;
        $sum_mihoatra=$donnees['mihoatra']+$sum_mihoatra;

            $point_de_vente = $donnees['nom_client_fournisseur'];

        //-------------------------------------------------                    
        ?>
        <!------------------------------------------------->
                <tr>
                <td><a href="vente_recap_details_amparafa.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>"><?php echo $donnees['description_date']; ?></a></td>
                <td style="text-align: right;"><b><?php echo $donnees['Montant']." Ar";?></b></td>
                <td style="text-align: right;"><b><?php echo  ($donnees['mihoatra']+0)." Ar";?></b></td>
                <td style="text-align: right;"><b><?php echo ($donnees['resolution']*(-1))." Ar";?></b></td>
                <td><?php echo $donnees['note_general']; ?></td>
                </tr>

        <!-------------------------------------->
<?php
    } //END IF
}
$query_journal_vente->closeCursor();
?>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right;">#######</th>
            <th style="text-align: right;"><?php echo (abs($sum_mihoatra)); ?> Ar</th>
            <th style="text-align: right;"><?php echo (abs($sum_resolution)); ?> Ar</th>
            <th style="text-align: right;"></th>
        </tr>
	<tr class="bg-info">
        <th colspan="4" style="text-align: right;"><?php
        $resultante = 0;
        $resultante = $sum_mihoatra+$sum_resolution; 
        if ($resultante >= 0) {
            echo "VOLA MIHOATRA";
        } else {
            echo "VOLA TSY AMPY";
        }
        ?></th>
        <th style="text-align: left;"><?php echo abs($resultante); ?> Ar</th>
    </tr>
        </tbody>
    </table>
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
<?php  } else {
    echo "<br><br><br><br>";
    echo "DO NOT REFRESH THIS PAGE";
    echo "<a href='vente_recap_bejofo.php'>
                                        <button class='btn btn-warning'>BACK</button>
                                        </a>";
}

 ?>

<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>