<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
 
  <!-- Font Awesome -->
  <link rel="stylesheet" href="css/all.css">
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include('connect.php');
//fUNCTION REFORMAT
      function ref_format($str, $step, $reverse = false) {

      if ($reverse)
              return strrev(chunk_split(strrev($str), $step, ' '));

          return chunk_split($str, $step, ' ');
        }
  //MONTHLY VERSEMENT
$query_versement_monthly="SET @rownr = 0;";
$q = $bdd->prepare($query_versement_monthly);
$q->execute(array());
$q->closeCursor();
$query_versement_monthly="SELECT SQL_NO_CACHE YEAR(Date_du_Journal) as YEAR,date_format(Date_du_Journal,'%b') as MONTH, SUM(Montant) as Montant,@rownr:=@rownr+1 as no FROM recap_vente GROUP BY YEAR(Date_du_Journal)*100 + MONTH(Date_du_Journal) ORDER BY no DESC LIMIT 12";
  $q = $bdd->prepare($query_versement_monthly);
  $q->execute(array());
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

  while ($donnees = $q -> fetch())
{
  $Vrsmt_MM[$j] = $donnees['Montant'];
  $moi_name[$j] = $donnees['MONTH'].' '.$donnees['YEAR'];
  //echo $Vrsmt_MM[$j];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
?>
  <!-- Start your project here-->  
  <div>
    <div class="flex-center flex-column">
      <canvas id="VlineChart" style="max-width: 900px;"></canvas>
      <br>
      <br>
    </div>
    <!-----TO AUTO AJUST WITH PARENT USE LIKE THIS
    <div class="col-md-5">
  		<canvas id="VlineChart"></canvas>
	</div>
	---->
  </div>
    <!-- End your project here-->
  <div class="flex-center flex-column">
  <table class="table-bordered table-sm bg-light">
  <tbody>
    <tr>
      <th scope="row"><b style="font-size:10pt">MOIS</b></th>
      <?php
     
      for ($i=12; $i >= 1; $i--) {
        ?>
      <td class="text-right" style="font-size:10pt"><span style="font-weight: bold"><?php echo $moi_name[$i]; ?></span></td>
    <?php } ?>
    </tr>
    <!-------------------------------->
    <tr>
      <th scope="col" style="font-size:10pt"><b>MONTANT (Ar)</b></th>
      <?php 
      for ($i=12; $i >= 1; $i--) { 
      	?>
        <td class="text-right" style="font-size:10pt"><span style="font-weight: bold"><?php echo ref_format($Vrsmt_MM[$i], 3, true); ?></span></td>
      <?php } ?>
  
    </tr>
        <tr>
    <th scope="col" style="font-size:10pt"><b>MONTANT (Fmg)</b></th>
      <?php 
      for ($i=12; $i >= 1; $i--) { 
      	?>
        <td class="text-right" style="font-size:10pt"><span><?php echo ref_format(5*($Vrsmt_MM[$i]), 3, true); ?></span></td>
      <?php } ?>
  
    </tr>
    <!-------------------------------->
  </tbody>
</table>
</div>
<br>
<br>
<!-----START CHAR HORIZONTAL---->
    <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
  <!-- End your project here-->
  <script type="text/javascript">

    //VALUE
    var VVV1 = <?php echo json_encode($Vrsmt_MM[1]); ?>;
    var VVV2 = <?php echo json_encode($Vrsmt_MM[2]); ?>;
    var VVV3 = <?php echo json_encode($Vrsmt_MM[3]); ?>;
    var VVV4 = <?php echo json_encode($Vrsmt_MM[4]); ?>;
    var VVV5 = <?php echo json_encode($Vrsmt_MM[5]); ?>;
    var VVV6 = <?php echo json_encode($Vrsmt_MM[6]); ?>;
    var VVV7 = <?php echo json_encode($Vrsmt_MM[7]); ?>;
    var VVV8 = <?php echo json_encode($Vrsmt_MM[8]); ?>;
    var VVV9 = <?php echo json_encode($Vrsmt_MM[9]); ?>;
    var VVV10 = <?php echo json_encode($Vrsmt_MM[10]); ?>;
    var VVV11 = <?php echo json_encode($Vrsmt_MM[11]); ?>;
    var VVV12 = <?php echo json_encode($Vrsmt_MM[12]); ?>;
    //MOUNTH NAME
    var MN1 = <?php echo json_encode($moi_name[1]); ?>;
    var MN2 = <?php echo json_encode($moi_name[2]); ?>;
    var MN3 = <?php echo json_encode($moi_name[3]); ?>;
    var MN4 = <?php echo json_encode($moi_name[4]); ?>;
    var MN5 = <?php echo json_encode($moi_name[5]); ?>;
    var MN6 = <?php echo json_encode($moi_name[6]); ?>;
    var MN7 = <?php echo json_encode($moi_name[7]); ?>;
    var MN8 = <?php echo json_encode($moi_name[8]); ?>;
    var MN9 = <?php echo json_encode($moi_name[9]); ?>;
    var MN10 = <?php echo json_encode($moi_name[10]); ?>;
    var MN11 = <?php echo json_encode($moi_name[11]); ?>;
    var MN12 = <?php echo json_encode($moi_name[12]); ?>;


var vctxL = document.getElementById("VlineChart").getContext('2d');
var VmyVlineChart = new Chart(vctxL, {
type: 'line',
data: {
labels: [MN12, MN11, MN10, MN9, MN8, MN7, MN6, MN5, MN4, MN3, MN2, MN1],
datasets: [
{
label: "VERSEMENT GENERAL DU MOI",
data: [VVV12,VVV11,VVV10,VVV9,VVV8,VVV7, VVV6, VVV5, VVV4, VVV3, VVV2, VVV1],
backgroundColor: [
'rgba(188, 120, 255, .8)',
],
borderColor: [
'rgba(77, 33, 115, 1)',
],
borderWidth: 2
}
]
},
options: {
responsive: true
}
});
  </script>
  </body>
</html>