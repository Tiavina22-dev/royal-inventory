<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.css">
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/list_type4.css"> 
  <link rel="stylesheet" href="css/list_type1.css">
  <!--
  <link rel="stylesheet" href="css/list_type2.css">
  <link rel="stylesheet" href="css/list_type3.css">
  <link rel="stylesheet" href="css/list_type5.css">
  <link rel="stylesheet" href="css/list_type6.css">
  <link rel="stylesheet" href="css/w3.css">
  -->
</head>
<body>

<?php //include("footer.php"); 
// Include autoloader 
require_once 'dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();

// Load content from html file 
$html = file_get_contents("index2.html"); 
$dompdf->loadHtml($html); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'landscape'); 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
// Output the generated PDF to Browser 
$dompdf->stream("codexworld", array("Attachment" => 1));
?>
<!-- *******************MATERIALS**********************-->
<div id="content" class="bg-info">
<br>
<br>
<br>
<br>
<h3>SUMARY</h3>
	<div class="list-type4">
		<div class="list-group4">
		<span class="puce4">Website fonctionality : Stock Management</span>
		<span class="puce4">Starting dev Date : August 2020</span>
		<span class="puce4">Program based on : HTML5, CSS, JavaScript, PHP, Mysql</span>
		<span class="puce4">Associed bibliotheque : Bootstrap, Ajax, W3Schools, canva</span>
		</div>
	</div>
</div>
	
<div id="elementH"></div>
   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- Your custom scripts (optional) -->
</body>
</html>
