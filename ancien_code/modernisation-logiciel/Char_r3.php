<?php

include('connect.php');
$key_x = '';
$shop = '';
if (isset($_COOKIE['key_word'])) 
{
	$key_x = $_COOKIE['key_word'];
	$shop = $_COOKIE['point_de_vente'];
}
 //MONTHLY VERSEMENT
$query_versement_monthly="SET @rownr = 0;";
$q = $bdd->prepare($query_versement_monthly);
$q->execute(array());
$q->closeCursor();
if ($shop == 'Tous') {
	$query_versement_monthly="SELECT SQL_NO_CACHE YEAR(Date_du_Journal_mvt) as YEAR,date_format(Date_du_Journal_mvt,'%b') as MONTH, SUM(ABS(qt)) as Montant,@rownr:=@rownr+1 as no FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente'  GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt) ORDER BY Date_du_Journal_mvt DESC LIMIT 12";
	$q = $bdd->prepare($query_versement_monthly);
  	$q->execute(array($key_x));
} else{
	$query_versement_monthly="SELECT SQL_NO_CACHE YEAR(Date_du_Journal_mvt) as YEAR,date_format(Date_du_Journal_mvt,'%b') as MONTH, SUM(ABS(qt)) as Montant,@rownr:=@rownr+1 as no FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'vente'  GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt) ORDER BY Date_du_Journal_mvt DESC LIMIT 12";
	$q = $bdd->prepare($query_versement_monthly);
  	$q->execute(array($key_x,$shop));
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

  $max = 0;
  $min = 9999999999999;

  while ($donnees = $q -> fetch())
{
  $Vrsmt_MM[$j] = $donnees['Montant'];
  $moi_name[$j] = $donnees['MONTH'].' '.$donnees['YEAR'];
  //MAX
  if ($max < $Vrsmt_MM[$j]) {
  	$max = $Vrsmt_MM[$j];
  }
  //MIN
  if ($min > $Vrsmt_MM[$j]) {
  	$min = $Vrsmt_MM[$j];
  }
  
  //echo $Vrsmt_MM[$j];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
 
$dataPoints = array(
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
 var max = <?php echo json_encode($max+($max*20/100)); ?>;
 var min = <?php echo json_encode($min+0); ?>;
 //Interval min max divisé en 20
 var coef = (max - min) / 21;
 //alert(coef);
 //alert (min+(coef*21));
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "QUANTITE VENDU DANS 12 DERNIERS MOIS | ANALYSE ANNUELLE"
	},
	axisY: {
		minimum: 0,
		maximum: max,
		suffix: ""
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
		yVal =  Math.min(Math.max(deltaY + dps[i].y, 0), max);
		label_val = dps[i].label;
		color = yVal <= min ? "#ECFF00" : yVal > min && (min+(coef*1)) > yVal ? "#00FFFF" : yVal > (min+(coef*1)) && (min+(coef*2)) > yVal ? "#00F2FF" : yVal > (min+(coef*2)) && (min+(coef*3)) > yVal ? "#00A2FF" : yVal > (min+(coef*3)) && (min+(coef*4)) > yVal ? "#0088FF" : yVal > (min+(coef*4)) && (min+(coef*5)) > yVal ? "#006CFF" : yVal > (min+(coef*5)) && (min+(coef*6)) > yVal ? "#0054FF" : yVal > (min+(coef*6)) && (min+(coef*7)) > yVal ? "#003CFF" : yVal > (min+(coef*7)) && (min+(coef*8)) > yVal ? "#0021FF" : yVal > (min+(coef*8)) && (min+(coef*9)) > yVal ? "#0000FF" : yVal > (min+(coef*9)) && (min+(coef*10)) > yVal ? "#2300FF" : yVal > (min+(coef*10)) && (min+(coef*11)) > yVal ? "#5200FF" : yVal > (min+(coef*11)) && (min+(coef*12)) > yVal ? "#8000FF" : yVal > (min+(coef*12)) && (min+(coef*13)) > yVal  ? "#DC00FF" : yVal > (min+(coef*13)) && (min+(coef*14)) > yVal ? "#FF00FF" : yVal > (min+(coef*14)) && (min+(coef*15)) > yVal ? "#FF00E2" : yVal > (min+(coef*15)) && (min+(coef*16)) > yVal ? "#FF00C0" : yVal > (min+(coef*16)) && (min+(coef*17)) > yVal ? "#FF0095" : yVal > (min+(coef*17)) && (min+(coef*18)) > yVal ? "#FF0070" : yVal > (min+(coef*18)) && (min+(coef*19)) > yVal ? "#FF0051" : yVal > (min+(coef*19)) && (min+(coef*20)) > yVal ? "#FF002C" : yVal > (min+(coef*20)) && (min+(coef*21)) > yVal ? "#FF0008" : yVal >= (min+(coef*21)) ? "#FF0000": null;
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
