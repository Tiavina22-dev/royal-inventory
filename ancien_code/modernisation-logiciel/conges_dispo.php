<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Congès</title>
  <!-- Font Awesome -->
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
  <link rel="stylesheet" href="css/list_type2.css">
  <link rel="stylesheet" href="css/list_type3.css">
  <link rel="stylesheet" href="css/list_type5.css">
  <link rel="stylesheet" href="css/list_type6.css">
  <link rel="stylesheet" href="css/w3.css">
  -->
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php");
include('connect.php');
//GET USERNAME
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}

?>
<!-- *******************MATERIALS**********************-->
<div id="About">
<br>
<br>
<br>
<br>
<br>
<br>
<h3></h3>
<div class="flex-center flex-column">
<table class="table-bordered table-sm">
    <thead>
      <tr>
        <th class="text-center bg-warning" colspan="5"><b>JOURS DE CONGES DE <?php echo $username; ?></b>
      </th>
      </tr>
      <tr class="bg-warning">
        <th colspan="2" class="text-center"><b>JOURS OBTENU</b></th>
        <th colspan="2" class="text-center"><b>JOURS PRISE</b></th>
        <th><b>JOURS DISPONIBLE</b></th>
      </tr>
    </thead>
    <tbody>
<?php
$bg = 'bg-info';
 
  #$donnees['before_change'];
  #$donnees['details'];
  #$donnees['responsable'];
  //LIST OF IN AND OUT DAYS-------------
  $query_conges = "SELECT * FROM history WHERE type = 'conges' AND before_change = ? ORDER BY date_time";
  $query_conges = $bdd->prepare($query_conges);
  $query_conges->execute(array($username));
  $in = 0;
  $out = 0;
  $list_in = "";
  $list_out = "";
  while ($dc = $query_conges -> fetch())
  {
    if ($dc['after_change']=='add') {
      $in = ($dc['flag']+0)+$in;
      $list_in = $dc['date_time'].'<br> | Raison : '.$dc['details'] .' : '.$dc['flag'].'<br>'.$list_in;
    } else {
      //REMOVE
      $out = ($dc['flag']+0)+$out;
      $list_out = $dc['date_time'].'<br> | Raison : '.$dc['details'] .' : '.$dc['flag'].'<br>'.$list_out;
    }

  }
  $query_conges->closeCursor();
  //--------END LIST ---------
  if ($bg == 'bg-info') {
    $bg = '';
  }  else {
    $bg = 'bg-info';
  }
  

?>
<tr class="<?php echo $bg;?>">
  <td><?php echo $list_in; ?></td>
  <th><span class="w3-badge w3-margin-right w3-green"><?php echo $in; ?></span></th>
  <td><?php echo $list_out; ?></td>
  <th><span class="w3-badge w3-margin-right w3-yellow"><?php echo $out; ?></th>
  <td class="text-center"><span class="w3-badge w3-margin-right w3-pink"><?php echo ($in-$out); ?></td>
</tr>
</span>
 </tbody>
</table>
</div>


  <!-- jQuery -->
  <!--
  <script type="text/javascript" src="js/mdb.min.js"></script>
  -->
   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
   <script  src="js/confirmation.js"></script>
  <!-- Your custom scripts (optional) -->

</body>
</html>
