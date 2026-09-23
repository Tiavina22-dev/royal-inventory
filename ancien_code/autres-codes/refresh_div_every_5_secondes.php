<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>REFRESH DIV</title>
</head>
<body>
<div id="sample">Testing refresh every 5 seconds</div>
</body> 
<script src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(

 function() {

 setInterval(function() {

 var someval = Math.floor(Math.random() * 100);

  $('#sample').text('Test' + someval);

 }, 5000);  //Delay here = 5 seconds 

});
</script>
</html>