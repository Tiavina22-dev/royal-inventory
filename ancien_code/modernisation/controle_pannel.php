<!DOCTYPE html>
<html>
<head>
	<title>CONTROLE</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/controle_pannel.css" rel="stylesheet">
  <link rel="stylesheet" href="css/mota.css">
	<!---add other css--->

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
 <div id="pannel_show">
  <?php include("pannel_1.php"); ?>
 </div>
</body>
<!--------------------------------------->

<?php //include ('pannel_2.php'); ?>
<?php //include ('pannel_1.php'); ?>


<script src="js/jquery.js"></script>
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
<script type="text/javascript">
  //Refresh each 15s-------
    setInterval(function(){
        pannel_2();
    },30000);
  //-----------------------
  //Function of tracking vente
  n = 1;
    function pannel_2() 
  {
    n = 1 + n;
  //
  file = 'pannel_1.php';
  if ((n%2) <= 0) {
    file = 'pannel_2.php';
    //document.location.reload(true);
  }
  
      $.ajax({
        url: file,
        type: 'GET',
        dataType: 'html'
    })
    .done(function( data ) {
        //Audio for xprinter
        //alert('hi');
        //if (data=='MATY') {
        document.getElementById('pannel_show').innerHTML = data;
        //--------USING BY TABLE IN pannel_1--------
        if (file =="pannel_1.php") {
            $(document).ready(function() {
            $('#example').DataTable();
            } );
        }
        //-----------------------
        //document.getElementById('tracking_activity').style.color = 'red';
        

    })
    .fail(function() {
        $('#pannel_show').prepend('Error retrieving new messages..'); // there was an error, so display an error
    });
  }
</script>
</html>