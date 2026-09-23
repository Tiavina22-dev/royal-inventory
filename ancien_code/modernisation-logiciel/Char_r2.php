<?php

include('connect.php');
$point_de_vente = 'TOUS LES SHOPS';
 if (isset($_COOKIE['point_de_vente'])) 
{
     $point_de_vente = $_COOKIE['point_de_vente'];
     
     $query_versement_monthly="SELECT SQL_NO_CACHE YEAR(Date_du_Journal_mvt) as YEAR,SUM(Montant) as Montant,date_format(Date_du_Journal_mvt,'%b') as MONTH,nom_client_fournisseur FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt) ORDER BY Date_du_Journal_mvt DESC";
    $q = $bdd->prepare($query_versement_monthly);
    $q->execute(array($point_de_vente));
    $rallonge = 1000000;
 }
 else {
 //MONTHLY VERSEMENT
    $query_versement_monthly="SET @rownr = 0;";
    $q = $bdd->prepare($query_versement_monthly);
    $q->execute(array());
    $q->closeCursor();
    $query_versement_monthly="SELECT SQL_NO_CACHE YEAR(Date_du_Journal) as YEAR,date_format(Date_du_Journal,'%b') as MONTH, SUM(Montant) as Montant,@rownr:=@rownr+1 as no FROM recap_vente GROUP BY YEAR(Date_du_Journal)*100 + MONTH(Date_du_Journal) ORDER BY Date_du_Journal DESC LIMIT 16";
      $q = $bdd->prepare($query_versement_monthly);
      $q->execute(array());
      $rallonge = 10000000;
  }
  //initialisation
  $j = 1;
  $Vrsmt_MM[1] = 0;
  $Vrsmt_MM[2] = 0;
  $Vrsmt_MM[3] = 0;
  $Vrsmt_MM[4] = 0;
  $Vrsmt_MM[5] = 0;
  $Vrsmt_MM[6] = 0;
  $Vrsmt_MM[7] = 0;
  $Vrsmt_MM[8] = 0;
  $Vrsmt_MM[9] = 0;
  $Vrsmt_MM[10] = 0;
  $Vrsmt_MM[11] = 0;
  $Vrsmt_MM[12] = 0;
  $Vrsmt_MM[13] = 0;
  $Vrsmt_MM[14] = 0;
  $Vrsmt_MM[15] = 0;
  $Vrsmt_MM[16] = 0;

  //monthname
  $moi_name[1] = '';
  $moi_name[2] = '';
  $moi_name[3] = '';
  $moi_name[4] = '';
  $moi_name[5] = '';
  $moi_name[6] = '';
  $moi_name[7] = '';
  $moi_name[8] = '';
  $moi_name[9] = '';
  $moi_name[10] = '';
  $moi_name[11] = '';
  $moi_name[12] = '';
  $moi_name[13] = '';
  $moi_name[14] = '';
  $moi_name[15] = '';
  $moi_name[16] = '';

  $max = 0;
  while ($donnees = $q -> fetch())
{
  $Vrsmt_MM[$j] = $donnees['Montant'];
  $moi_name[$j] = $donnees['MONTH'].' '.$donnees['YEAR'];
  //echo $Vrsmt_MM[$j];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  //GET MAX
  if ($max < $donnees['Montant']) { $max = $donnees['Montant']; }
  $j = $j+1;
}
  $q->closeCursor();
$dataPoints = array(
	array("label"=> $moi_name[16], "y"=> $Vrsmt_MM[16]),
	array("label"=> $moi_name[15], "y"=> $Vrsmt_MM[15]),
	array("label"=> $moi_name[14], "y"=> $Vrsmt_MM[14]),
	array("label"=> $moi_name[13], "y"=> $Vrsmt_MM[13]),
	array("label"=> $moi_name[12], "y"=> $Vrsmt_MM[12]),
	array("label"=> $moi_name[11], "y"=> $Vrsmt_MM[11]),
	array("label"=> $moi_name[10], "y"=> $Vrsmt_MM[10]),
	array("label"=> $moi_name[9], "y"=> $Vrsmt_MM[9]),
	array("label"=> $moi_name[8], "y"=> $Vrsmt_MM[8]),
	array("label"=> $moi_name[7], "y"=> $Vrsmt_MM[7]),
	array("label"=> $moi_name[6], "y"=> $Vrsmt_MM[6]),
	array("label"=> $moi_name[5], "y"=> $Vrsmt_MM[5]),
	array("label"=> $moi_name[4], "y"=> $Vrsmt_MM[4]),
	array("label"=> $moi_name[3], "y"=> $Vrsmt_MM[3]),
	array("label"=> $moi_name[2], "y"=> $Vrsmt_MM[2]),
	array("label"=> $moi_name[1], "y"=> $Vrsmt_MM[1]),
);
 
?>

<script>
window.onload = function () {
var max = <?php echo json_encode($max); ?>;
var maxy = <?php echo json_encode($rallonge+$max); ?>;
var min = 1000000;
var pv = <?php echo json_encode(' | '.strtoupper($point_de_vente)); ?>;
var ecart = (max - min)/22;
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "DIAGRAMME DE VERSEMENT DU 14 DERNIERS MOIS | ANALYSE ANNUELLE" + pv
	},
	axisY: {
		minimum: 0,
		maximum: maxy,
		suffix: "Ar"
	},
	data: [{
		type: "column",
		//yValueFormatString: "#,##0\" Ar\"",
		yValueFormatString: "#,##0",
		indexLabel: "{y}",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
 
function updateChart() {
	var color,deltaY, yVal;
	var dps = chart.options.data[0].dataPoints;
	for (var i = 0; i < dps.length; i++) {
		//deltaY = (2 + Math.random() * (-2 - 2));
		deltaY = 0;
		yVal =  Math.min(Math.max(deltaY + dps[i].y, 0), 400000000);
		label_val = dps[i].label;
		color = yVal < min ? "#ECFF00" : yVal >= min && (min+ecart) > yVal ? "#00FFFF" : yVal > (min+ecart) && (min+(ecart*2)) > yVal ? "#00F2FF" : yVal > (min+(ecart*2)) && (min+(ecart*3)) > yVal ? "#00A2FF" : yVal > (min+(ecart*3)) && (min+(ecart*4)) > yVal ? "#0088FF" : yVal > (min+(ecart*4)) && (min+(ecart*5)) > yVal ? "#006CFF" : yVal > (min+(ecart*5)) && (min+(ecart*6)) > yVal ? "#0054FF" : yVal > (min+(ecart*6)) && (min+(ecart*7)) > yVal ? "#003CFF" : yVal > (min+(ecart*7)) && (min+(ecart*8)) > yVal ? "#0021FF" : yVal > (min+(ecart*8)) && (min+(ecart*9)) > yVal ? "#0000FF" : yVal > (min+(ecart*9)) && (min+(ecart*10)) > yVal ? "#2300FF" : yVal > (min+(ecart*10)) && (min+(ecart*11)) > yVal ? "#5200FF" : yVal > (min+(ecart*11)) && (min+(ecart*12)) > yVal ? "#8000FF" : yVal > (min+(ecart*12)) && (min+(ecart*13)) > yVal  ? "#DC00FF" : yVal > (min+(ecart*13)) && (min+(ecart*14)) > yVal ? "#FF00FF" : yVal > (min+(ecart*14)) && (min+(ecart*15)) > yVal ? "#FF00E2" : yVal > (min+(ecart*15)) && (min+(ecart*16)) > yVal ? "#FF00C0" : yVal > (min+(ecart*16)) && (min+(ecart*17)) > yVal ? "#FF0095" : yVal > (min+(ecart*17)) && (min+(ecart*18)) > yVal ? "#FF0070" : yVal > (min+(ecart*18)) && (min+(ecart*19)) > yVal ? "#FF0051" : yVal > (min+(ecart*19)) && (min+(ecart*20)) > yVal ? "#FF002C" : yVal > (min+(ecart*20)) && (min+(ecart*21)) > yVal ? "#FF0008" : yVal > (min+(ecart*21)) ? "#FF0000": null;
		dps[i] = {label:label_val , y: yVal, color: color};
	}
	chart.options.data[0].dataPoints = dps;
	chart.render();
};
updateChart();
 
//setInterval(function () { updateChart() }, 1000);
 
}
</script>

<div id="chartContainer" style="height: 370px; width: 90%;margin: auto;"></div>
<script type="text/javascript" src="js/canvasjs.min.js"></script>
