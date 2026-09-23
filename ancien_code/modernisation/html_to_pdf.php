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
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<!-- *******************MATERIALS**********************-->
<div id="content" class="bg-info">
<br>
<br>
<br>
<br>
<h3 style="font-size: 20px">SUMARY</h3>
	<div class="list-type4">
		<div class="list-group4">
		<span class="puce4" style="font-size: 10px">Website fonctionality : Stock Management</span><br>
		<span class="puce4" style="font-size: 10px">Starting dev Date : August 2020</span><br>
		<span class="puce4" style="font-size: 10px">Program based on : HTML5, CSS, JavaScript, PHP, Mysql</span><br>
		<span class="puce4" style="font-size: 10px">Associed bibliotheque : Bootstrap, Ajax, W3Schools, canva</span><br>
		</div>
	</div>
	
</div>
	<table border="1" style="font-family:Georgia, Garamond, Serif;color:blue;font-style:italic;">
<tr>
<th style="height: 20px">Table Header</th>
</tr>
<tr>
<td><span style="font-size: 10px">Table cell 1</span></td>
</tr>
<tr>
<td>Table cell 4</td>
</tr>
</table>
<div id="elementH"></div>
   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/jspdf.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
  	var doc = new jsPDF({
        orientation: 'p', 
        unit: 'mm', 
        format: [80, 210]
});
	var elementHTML = $('#content').html();
	var specialElementHandlers = {
    '#elementH': function (element, renderer) {
        return true;
    }
};
doc.fromHTML(elementHTML, 5, 5, {
    'width': 1000,
    'elementHandlers': specialElementHandlers
});
// Save the PDF
doc.save('sample-document.pdf');
  </script>

</body>
</html>
