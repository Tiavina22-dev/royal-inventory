<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>SOLAR ENERGY CALCULATOR</title>
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
  <link rel="stylesheet" href="css/w3.css">
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<br>
<?php
//initialisation
$charge_controler = 0;
$panel_puissance = 0;
$batery_puissance = 0;
$inverter_puissance = 0;

if (isset($_COOKIE['charge_controler'])) {
  $charge_controler = $_COOKIE['charge_controler'];

  if (isset($_COOKIE['panel_puissance'])) {
    $panel_puissance = $_COOKIE['panel_puissance'];
  }

  if (isset($_COOKIE['batery_puissance'])) {
    $batery_puissance = $_COOKIE['batery_puissance'];
  }

  if (isset($_COOKIE['inverter_puissance'])) {
    $inverter_puissance = $_COOKIE['inverter_puissance'];
  }
 ?>
<div class="flex-center flex-column animated fadeIn mb-3">
    <h5 class="animated fadeIn mb-3"><b>CHARACTERISTIC OF MATERIAL</b></h5>
   <table class="table-sm bg-light">
        <thead>
        <tr>
        <th colspan="2"><b>SOLAR PANEL</b></th>
        <th colspan="2"><b><span class="w3-badge w3-right w3-margin-right w3-blue"><?php echo ceil($panel_puissance); ?></span></b></th>
        <th colspan="2"><b>Watts</b></th>
        <th colspan="2"><b><?php $a = 2500 * ceil($panel_puissance); echo $a." Ar"; ?></b></th>
        <th colspan="2"><img src="img/solar_panel5.png" height="200" width="200" background alt="Edit" /></th>
        </tr>
        <tr>
        <th colspan="2"><b>BATERY</b></th>
        <th colspan="2"><b><span class="w3-badge w3-right w3-margin-right w3-red"><?php echo ceil($batery_puissance); ?></span></b></th>
        <th colspan="2"><b>AH</b></th>
        <th colspan="2"><b><?php $a = 3000 * ceil($batery_puissance); echo $a." Ar"; ?></b></th>
        <th colspan="2"><img src="img/batery.png" height="100" width="200" background alt="Edit" /></th>
        </tr>
        <tr>
        <th colspan="2"><b>CHARGE CONTROLER</b></th>
        <th colspan="2"><b><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ceil($charge_controler); ?></span></b></th>
        <th colspan="2"><b>A</b></th>
        <th colspan="2"><b><?php echo "30A 20000Ar"; ?></b></th>
        <th colspan="2"><img src="img/charge_controler.png" height="90" width="170" background alt="Edit" /></th>
        </tr>
        <tr>
        <th colspan="2"><b>INVERTER</b></th>
        <th colspan="2"><b><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ceil($inverter_puissance); ?></span></b></th>
        <th colspan="2"><b>Watts</b></th>
        <th colspan="2"><b><?php echo "500W 60000Ar"; ?></b></th>
        <th colspan="2"><img src="img/inverter1.png" height="100" width="200" background alt="Edit" /></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
    <div class="flex-center">
    <a href="energie_solaire.php"><button type="submit" class="btn btn-primary">RETOURS</button></a>
    </div>
    <?php
    }// end first if
    else{
      ?>

<!-- actual form -->
<div class="flex-center">
<div class="btn btn-light animated fadeIn mb-3">
<form role="form" action="solar_calc.php" method="post">
  <div class="form-group">
    <label><b>INPUT DATA</b></label>
  </div>
  <div class="form-group">
    <label>Charge (W)</label>
    <input class="btn" value="100" name="charge" type="number">
  </div>
  <div class="form-group">
    <label>Hours of Activity/day</label>
    <select class="btn" name="horaire">
    <option value="1">1h</option>
      <option value="2">2h</option>
      <option value="4">4h</option>
      <option value="8">8h</option>
      <option value="12" selected>12h</option>
      <option value="24">24h</option>
    </select>
  </div>
  <div class="form-group">
    <label>No sunlight</label>
    <select class="btn" name="no_sunlight">
    <option value="0.21" selected>5H</option>
    <option value="1">1 days</option>
      <option value="2">2 days</option>
      <option value="3">3 days</option>
      <option value="5">5 days</option>
      <option value="7">7 days</option>
    </select>
  </div>
  <div class="form-group">
    <label>Inverter Efficiency</label>
    <select class="btn" name="Efficiency">
      <option value="0.90">Max (90%)</option>
      <option value="0.75">Average (75%)</option>
      <option value="0.50" selected>Min (50%)</option>
    </select>
  </div>
  <div class="form-group">
    <label>TENSION INVERTER INPUT</label>
    <select class="btn" name="tension">
    <option value="6">6V</option>
    <option value="12" selected>12V</option>
      <option value="24">24V</option>
    </select>
  </div>
  <div class="form-group">
    <button type="submit" class="btn btn-success">CALCULATE</button>
  </div>
</form>
</div>
</div>
<!-- actual form ends -->
    <?php
    }//first else
    ?>
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