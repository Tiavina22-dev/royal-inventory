<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
  <!-- Font Awesome -->
  <!--
  <link rel="stylesheet" href="css/all.css">
  -->
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <!--
  <link rel="stylesheet" href="css/style.css">
  -->
  <link rel="stylesheet" href="css/top20.css">
  <link rel="stylesheet" href="css/w3.css">
</head>
<body style="background: #343a40">
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<?php
  $char_cookie = 0;
  $top20_cookie = 0;
  if (isset($_COOKIE['char_cookie'])) 
  { 
	?>
    <div class="text-center">
  <a href="home_char.php">
  <button class="btn btn-success">ACCEUIL</button>
  </a>
  </div>
	<?php
  	include('char_line_vente_7_jours.php');
    include('char_line_versement_7_jours.php');
  $char_cookie = 1;
}
  elseif (isset($_COOKIE['top20_cookie'])) {
  	$top20_cookie = 1;
  	echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    if ($_COOKIE['top20_cookie'] == 'a') {
      include('top20a.php');
    }
    if ($_COOKIE['top20_cookie'] == 'b') {
      include('top20b.php');
    }
    if ($_COOKIE['top20_cookie'] == 'c') {
      include('top20c.php');
    }
    if ($_COOKIE['top20_cookie'] == 'p') {
      include('top20_printable.php');
    }
  	
  }
  else
  {
  ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
     <?php if ($username == 'TRoyal' ) { ?>
	 <div class="text-center">
    <h2 class="top20_style">VUE GLOBALE</h2>
	
	<img src="img/royal_original.jpg" height="900" width="1500" />
	</div>
	<?php
	} else { ?>
    <div class="text-center">
    <h2 class="top20_style">VUE GLOBALE</h2>
  <a href="char_activate.php">
  <button class="btn btn-warning">Show CHAR</button>
  </a>
  <button class="btn btn-warning" data-toggle='modal' data-target='#top20'>SHOW TOP 20</button>
  
                      <!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="top20" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                        <div class="form-group">
                                        <a href="top20a_activate.php">
                                        <button class="btn btn-secondary">TOP 20 DE LA SEMAINE</button>
                                        </a>
                                        <a href="top20b_activate.php">
                                        <button class="btn btn-info">TOP 20 DU MOIS</button>
                                        </a>
                                        <a href="top20c_activate.php">
                                        <button class="btn btn-danger">TOP 20 DU 6 MOIS</button>
                                        </a>
                                        <a href="top20p_activate.php">
                                        <button class="btn btn-primary">PRINTABLE</button>
                                        </a>
                                        </div>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
  </div>
  <?php
  //include('eval_saisi.php');
  }
	 } //end else
  ?>
  <?php if ($username == 'TRoyal' ) { }
  else  {
  	# code...
if ($char_cookie == 1) 
  {include('char_bar_horizontal_versement_moyenne.php');}
else{
	if ($top20_cookie == 0) {
?>
<?php 
	include('char_line_saisi_7_jours.php');
	//include('latest_journal.php');
	//include('stock_epuise.php');
  //include('citation.php');
//include('stock_balance.php');

}
}
}?>
<br>
<br>
<br>
<br>
<br>
<br>
  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
</body>
</html>
