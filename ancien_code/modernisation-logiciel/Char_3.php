<?php
 
$dataPoints = array(
	array("label"=> "Core 1", "y"=> 20),
	array("label"=> "Core 2", "y"=> 65),
	array("label"=> "Core 3", "y"=> 11),
	array("label"=> "Core 4", "y"=> 5),
	array("label"=> "Core 5", "y"=> 48),
	array("label"=> "Core 6", "y"=> 8),
	array("label"=> "Core 7", "y"=> 2),
	array("label"=> "Core 8", "y"=> 18)
);
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "CPU Usage in 8-Core Processor"
	},
	axisY: {
		minimum: 0,
		maximum: 100,
		suffix: "%"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.00\"%\"",
		indexLabel: "{y}",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
 
function updateChart() {
	var color,deltaY, yVal;
	var dps = chart.options.data[0].dataPoints;
	for (var i = 0; i < dps.length; i++) {
		deltaY = (2 + Math.random() * (-2 - 2));
		yVal =  Math.min(Math.max(deltaY + dps[i].y, 0), 90);
		color = yVal > 75 ? "#FF2500" : yVal >= 50 ? "#FF6000" : yVal < 50 ? "#41CF35" : null;
		dps[i] = {label: "Core "+(i+1) , y: yVal, color: color};
	}
	chart.options.data[0].dataPoints = dps;
	chart.render();
};
updateChart();
 
setInterval(function () { updateChart() }, 1000);
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script type="text/javascript" src="js/canvasjs.min.js"></script>
</body>
</html>