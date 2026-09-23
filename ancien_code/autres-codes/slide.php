<!DOCTYPE html>
<html>
<head>
	<title>SLIDE</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/controle_pannel.css" rel="stylesheet">
	<!---add other css--->

</head>
<body>
 <!------------------------------
 <img class="mySlides" src="img/default_img/default_pdp.png">
 <img class="mySlides" src="img/solar_panel4.png">
 <img class="mySlides" src="img/img_snow.jpg">
 --------->
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
    <br>
    <br>
    <br>
    <br>
    <br>
 <img class="mySlides" src="img/default_img/default_pdp.png">
 <img class="mySlides" src="img/solar_panel4.png">
 <img class="mySlides" src="img/img_snow.jpg">
</body>
<!--------------------------------------->

<?php //include ('pannel_2.php'); ?>
<?php //include ('pannel_1.php'); ?>


<script src="js/jquery.js"></script>

<script type="text/javascript">
    var slideIndex = 0;
    carousel();
    function carousel() {
      var i;
      var x = document.getElementsByClassName("mySlides");
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
      }
      slideIndex++;
      if (slideIndex > x.length) { slideIndex = 1}
        x[slideIndex-1].style.display = "block";
      setTimeout(carousel, 2000);//2 seconde
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
</html>