<!DOCTYPE html>
<html>
<head>
	<title>COMPTE AMPARAFA PRINTED</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link rel="stylesheet" type="text/css" href="css/style_Index.css">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
 <?php
//Connect to BD
include('connect.php');
//fUNCTION REFORMAT NUMBER FOR LISIBLITY
      function ref_format($str, $step, $reverse = false) {

      if ($reverse)
              return strrev(chunk_split(strrev($str), $step, ' '));

          return chunk_split($str, $step, ' ');
        }
//---------------------------------------
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
	<h4 class="text-center"><b>COMPTE AMPARAFA NON REGULARISE</b></h4>
    <h5 class="text-center text-danger"><?php echo $msg_supression; ?></h5>
	 <table class="tg">
	
        <thead>
        <tr class="violet">
            <th style="text-align: center;" colspan="6">COMPTE AMPARAFA JUSQU'AU 30 SEPT 2022</th>
        </tr>
        <tr class="orange">
            <th>Date</th>
            <th style="text-align: right;">VERSEMENT</th>
            <th style="text-align: right;">TOMBONY</th>
            <th style="text-align: right;">TSY AMPY</th>
            <th style="text-align: right;">NALAINY</th>
            <th style="text-align: left;">NOTE</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = $bdd->query("SELECT *,recap_vente.status as status,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = 'vente' AND nom_client_fournisseur='Amparafa' AND recap_vente.status ='NON_RESOLU' AND Date_du_Journal_mvt < '2022-10-01' GROUP BY mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC;");
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
$sum_resolution = 0;
$sum_mihoatra = 0;
$sum_difference = 0;
$sum_depense_aparafa = 0;
$classname="tg-0lax2a";
$resolution = 0;
while ($donnees = $query_journal_vente -> fetch())
{ 
	if ($donnees['Date_du_Journal_mvt'] > '2022-03-21') {
		$resolution = $donnees['resolution'];
		$sum_resolution = $donnees['resolution'] + $sum_resolution;

	}

$sum_mihoatra = $donnees['mihoatra'] + $sum_mihoatra;
$sum_difference = $donnees['difference_aparafa'] + $sum_difference;
$sum=$donnees['Montant']+$sum;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
//$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
//$path = str_replace("\\", "/", $path);

//echo $path;
//$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
//Show vola nalain i Aparafa----------------------
	$Aff_motif = '';
    $depense_aparafa = 0;
    $query_nalain_aparafa = 'SELECT * FROM depense WHERE activity_no = ?';
    $q = $bdd->prepare($query_nalain_aparafa);
    $q->execute(array($donnees['no_activite']));
    $nbr=$q->rowCount ();
    while ($data = $q -> fetch())
    {
    	$depense_aparafa = ABS($data['depense_aparafa']) + $depense_aparafa;
    	if (ABS($data['depense_aparafa'])<>0) {
    		$Aff_motif = $data['motif'].'<br>'.$Aff_motif;
    	}
  

    }
    $q->closeCursor();

    if ($nbr<>0) {
        
    } else {
        $depense_aparafa = 0;
    }

    $sum_depense_aparafa = $sum_depense_aparafa + $depense_aparafa;
//Show Depense Royal------------------------
    $depense_royal = 0;
    $query_nalain_aparafa = 'SELECT motif,montant FROM depense WHERE activity_no = ?';
    $q = $bdd->prepare($query_nalain_aparafa);
    $q->execute(array($donnees['no_activite']));
    $nbr=$q->rowCount ();
    while ($data = $q -> fetch())
    {
        $depense_royal = ABS($data['montant'])+$depense_royal;
        if (ABS($data['montant'])<>0) {
            //$Aff_motif = $data['motif'].' : '.ref_format(ABS($data['montant']), 3, true).'<br>'.$Aff_motif;
        }
    }
    $q->closeCursor();

    $point_de_vente = $donnees['nom_client_fournisseur'];

//------------------------------------------------- 
//COLOR OF LINE
  if ($classname=="tg-0lax2a") {
    $classname="tg-0lax4b";
  } else {
    $classname="tg-0lax2a";
  }                   
?>
<!------------------------------------------------->
        <tr class=<?php echo $classname; ?>>
        <td style="text-align: left;"><b><?php echo $donnees['description_date']; ?></b></td>
        <td style="text-align: right;"><b><?php echo ref_format($donnees['Montant'], 3, true);?></b></td>
        <td style="text-align: right;"><b><?php echo ref_format($donnees['difference_aparafa'], 3, true);?></b></td>
        <td style="text-align: right;"><b><?php echo ref_format(ABS($resolution), 3, true);?></b></td>
        <td style="text-align: right;"><b><?php echo ref_format(ABS($depense_aparafa), 3, true);?></b></td>
        
        <td style="text-align: left;"><b><?php echo $Aff_motif;?></b></td>
        </tr>

<!-------------------------------------->
<?php
}
?>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right;">#######</th>
            <th style="text-align: right;"><?php echo ref_format($sum_difference, 3, true); ?></th>
            <th style="text-align: right;"><?php echo ref_format(abs($sum_resolution), 3, true); ?></th>
            <th style="text-align: right;"><?php echo ref_format(abs($sum_depense_aparafa), 3, true); ?></th>
            
        </tr>
	<tr class="tg-0laxa">
        <th colspan="5" style="text-align: right;"><?php
        $resultante = 0;
        $resultante = ABS($sum_difference)-ABS($sum_resolution)-ABS($sum_depense_aparafa); 
        if ($resultante >= 0) {
            echo "AMBIM-BOLA NY AMPARAFA";
        } else {
            echo "VOLA AVERIN'NY AMPARAFA";
        }
        ?></th>
        <th style="text-align: left;"><?php echo ref_format(abs($resultante), 3, true); ?></th>
    </tr>
        </tbody>
    </table>
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