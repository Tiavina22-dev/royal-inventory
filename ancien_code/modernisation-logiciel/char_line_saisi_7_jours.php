<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
 
  <!-- Font Awesome -->
  
  <!-- Bootstrap core CSS -->
  
  <!-- Material Design Bootstrap -->
  
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/mota.css">
</head>
<body>
<?php
include('connect.php');
//RECUPERATION DU DATE PLUS RECENT entre STOCK ET VENTE
	#VENTE
	$query = "SELECT CAST(date_time as DATE) as daty from mvt WHERE type_de_mvt = 'vente' GROUP BY CAST(date_time as DATE) ORDER BY id_mvt DESC LIMIT 1;" ;
  	$q = $bdd->prepare($query);
  	$q->execute(array());
  	$donnees_v = $q -> fetch();
  	$date_vente = '';
  	if (isset($donnees_v['daty'])) {
  	$date_vente = $donnees_v['daty'];
  	//CONVERT DATE FORMAT to day-month-year
  	$date_vente = DateTime::createFromFormat('Y-m-d', $date_vente);
  	} //if isset
  	$q->closeCursor();
  	#STOCK
	$query = "SELECT CAST(date_time as DATE) as daty from mvt WHERE type_de_mvt = 'stock' GROUP BY CAST(date_time as DATE) ORDER BY id_mvt DESC LIMIT 1;" ;
  	$q = $bdd->prepare($query);
  	$q->execute(array());
  	$donnees_s = $q -> fetch();
  	$date_stock = '';
  	if (isset($donnees_s['daty'])) {
  	$date_stock = $donnees_s['daty'];
  	//CONVERT DATE FORMAT to day-month-year
  	$date_stock = DateTime::createFromFormat('Y-m-d', $date_stock);
  	}
  	$q->closeCursor();
  	#RECUPERATION DU DATE RECENT
  	$date_stock_s = strtotime($date_stock -> format('d-m-Y'));
  	$date_vente_v = strtotime($date_vente -> format('d-m-Y'));
  	if (isset($donnees_v['daty']) AND isset($donnees_s['daty'])) {
  	if ($date_stock_s < $date_vente_v) {
  		$D0 = $date_vente -> format('d-m-Y');
  	} else {
  		$D0 = $date_stock -> format('d-m-Y');
  	}//else
  	# GET PRECEDENT DATE D-1
  		$D0_str = strtotime($D0);
		//Calcule du precedent date
		$D1 = $D0_str - 86400;
		$D1 = getdate($D1);
		//Date in complet format
		$D1 = $D1["mday"]."-".$D1["month"]."-".$D1["year"];
		//Date in number format
		$D1 = strtotime($D1);
		$D1 = idate("Y",$D1).'-'.idate("m",$D1).'-'.idate("d",$D1);
    $D1 = DateTime::createFromFormat('Y-m-d', $D1);
    $D1 =$D1 -> format('d-m-Y');
    //echo $D0."<br>";
    //echo $D1."<br>";
	# GET PRECEDENT DATE D-2
  		$D1_str = strtotime($D1);
		//Calcule du precedent date
		$D2 = $D1_str - 86400;
		$D2 = getdate($D2);
		//Date in complet format
		$D2 = $D2["mday"]."-".$D2["month"]."-".$D2["year"];
		//Date in number format
		$D2 = strtotime($D2);
		$D2 = idate("Y",$D2).'-'.idate("m",$D2).'-'.idate("d",$D2);
    $D2 = DateTime::createFromFormat('Y-m-d', $D2);
    $D2 =$D2 -> format('d-m-Y');
		//echo $D2."<br>";
	# GET PRECEDENT DATE D-3
  		$D2_str = strtotime($D2);
		//Calcule du precedent date
		$D3 = $D2_str - 86400;
		$D3 = getdate($D3);
		//Date in complet format
		$D3 = $D3["mday"]."-".$D3["month"]."-".$D3["year"];
		//Date in number format
		$D3 = strtotime($D3);
		$D3 = idate("Y",$D3).'-'.idate("m",$D3).'-'.idate("d",$D3);
    $D3 = DateTime::createFromFormat('Y-m-d', $D3);
    $D3 =$D3 -> format('d-m-Y');
		//echo $D3."<br>";
	# GET PRECEDENT DATE D-4
  		$D3_str = strtotime($D3);
		//Calcule du precedent date
		$D4 = $D3_str - 86400;
		$D4 = getdate($D4);
		//Date in complet format
		$D4 = $D4["mday"]."-".$D4["month"]."-".$D4["year"];
		//Date in number format
		$D4 = strtotime($D4);
		$D4 = idate("Y",$D4).'-'.idate("m",$D4).'-'.idate("d",$D4);
    $D4 = DateTime::createFromFormat('Y-m-d', $D4);
    $D4 =$D4 -> format('d-m-Y');
		//echo $D4."<br>";
	# GET PRECEDENT DATE D-4
  		$D4_str = strtotime($D4);
		//Calcule du precedent date
		$D5 = $D4_str - 86400;
		$D5 = getdate($D5);
		//Date in complet format
		$D5 = $D5["mday"]."-".$D5["month"]."-".$D5["year"];
		//Date in number format
		$D5 = strtotime($D5);
		$D5 = idate("Y",$D5).'-'.idate("m",$D5).'-'.idate("d",$D5);
    $D5 = DateTime::createFromFormat('Y-m-d', $D5);
    $D5 =$D5 -> format('d-m-Y');
		//echo $D5."<br>";
	# GET PRECEDENT DATE D-5
  		$D5_str = strtotime($D5);
		//Calcule du precedent date
		$D6 = $D5_str - 86400;
		$D6 = getdate($D6);
		//Date in complet format
		$D6 = $D6["mday"]."-".$D6["month"]."-".$D6["year"];
		//Date in number format
		$D6 = strtotime($D6);
		$D6 = idate("Y",$D6).'-'.idate("m",$D6).'-'.idate("d",$D6);
    $D6 = DateTime::createFromFormat('Y-m-d', $D6);
    $D6 =$D6 -> format('d-m-Y');
		//echo $D6."<br>";
  } //if isset
  	
//7 dernier Nombre de saisi de vente
$query = "SELECT CAST(date_time as DATE) as daty, COUNT(*) as result from mvt WHERE type_de_mvt = 'vente' GROUP BY CAST(date_time as DATE) ORDER BY id_mvt DESC;" ;
  $q = $bdd->prepare($query);
  $q->execute(array());
  //initialisation
  $j = 1;
  $nb_saisi_vente[1] = 0;
  $nb_saisi_vente[2] = 0;
  $nb_saisi_vente[3] = 0;
  $nb_saisi_vente[4] = 0;
  $nb_saisi_vente[5] = 0;
  $nb_saisi_vente[6] = 0;
  $nb_saisi_vente[7] = 0;
  while ($donnees = $q -> fetch())
{
  //$nb_saisi_vente[$j] = $donnees['result'];
  $date_sv = $donnees['daty'];
  //CONVERT DATE FORMAT
  $date_sv = DateTime::createFromFormat('Y-m-d', $date_sv);
  $date_sv =$date_sv -> format('d-m-Y');
  #GET VALUE CAS PAR CAS
  //echo $daty."<br>";
  //echo $D0."<br>";
  //echo $date_sv."<br>";
  	if ($date_sv == $D0) {$nb_saisi_vente[1] = $donnees['result'];}
  	if ($date_sv == $D1) {$nb_saisi_vente[2] = $donnees['result'];}
  	if ($date_sv == $D2) {$nb_saisi_vente[3] = $donnees['result'];}
  	if ($date_sv == $D3) {$nb_saisi_vente[4] = $donnees['result'];}
  	if ($date_sv == $D4) {$nb_saisi_vente[5] = $donnees['result'];}
  	if ($date_sv == $D5) {$nb_saisi_vente[6] = $donnees['result'];}
  	if ($date_sv == $D6) {$nb_saisi_vente[7] = $donnees['result'];}
  //echo $donnees['nb_ligne'];
  //echo $nb_saisi_vente[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //7 dernier Nombre de journal de vente
$query = "SELECT CAST(date_time as DATE) as daty, COUNT(*) as result from (SELECT id_mvt,date_time,numero_commande_stock,COUNT(*) FROM mvt WHERE type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY id_mvt DESC) s GROUP BY CAST(date_time as DATE) ORDER BY CAST(date_time as DATE) DESC;" ;
  $q = $bdd->prepare($query);
  $q->execute(array());
  //initialisation
  $j = 1;
  $nb_jv[1] = 0;
  $nb_jv[2] = 0;
  $nb_jv[3] = 0;
  $nb_jv[4] = 0;
  $nb_jv[5] = 0;
  $nb_jv[6] = 0;
  $nb_jv[7] = 0;
  $jv_today = 0;
  while ($donnees = $q -> fetch())
{
  //$nb_jv[$j] = $donnees['result'];
  #GET VALUE CAS PAR CAS
  $daty = $donnees['daty'];
  $daty = DateTime::createFromFormat('Y-m-d', $daty);
  $daty =$daty -> format('d-m-Y');
  //echo $daty."<br>";
  	if ($daty == $D0) {$nb_jv[1] = $donnees['result'];}
  	if ($daty == $D1) {$nb_jv[2] = $donnees['result'];}
  	if ($daty == $D2) {$nb_jv[3] = $donnees['result'];}
  	if ($daty == $D3) {$nb_jv[4] = $donnees['result'];}
  	if ($daty == $D4) {$nb_jv[5] = $donnees['result'];}
  	if ($daty == $D5) {$nb_jv[6] = $donnees['result'];}
  	if ($daty == $D6) {$nb_jv[7] = $donnees['result'];}

  if ($j==1) 
  	{
  $date_jv = $donnees['daty'];
  //CONVERT DATE FORMAT
  $date_jv = DateTime::createFromFormat('Y-m-d', $date_jv);
  //GET LATEST DATE
  $date_jv =$date_jv -> format('d-m-Y');
  //GET number of today's journal of vente
  $today = date("d-m-Y");
  if ($today == $date_jv) {
    $jv_today = $donnees['result'];
  }
	}
  //echo $donnees['nb_ligne'];
 //echo $nb_saisi_stock[$j].'<br>';
  $j = $j+1;
}//WHILE
  $q->closeCursor();
  //7 dernier Nombre de saisi de stock
$query = "SELECT CAST(date_time as DATE) as daty, COUNT(*) as result from mvt WHERE type_de_mvt = 'stock' GROUP BY CAST(date_time as DATE) ORDER BY id_mvt DESC;" ;
  $q = $bdd->prepare($query);
  $q->execute(array());
  //initialisation
  $j = 1;
  $nb_saisi_stock[1] = 0;
  $nb_saisi_stock[2] = 0;
  $nb_saisi_stock[3] = 0;
  $nb_saisi_stock[4] = 0;
  $nb_saisi_stock[5] = 0;
  $nb_saisi_stock[6] = 0;
  $nb_saisi_stock[7] = 0;
  while ($donnees = $q -> fetch())
{
  //$nb_saisi_stock[$j] = $donnees['result'];
  $date_ss = $donnees['daty'];
  //CONVERT DATE FORMAT
  $date_ss = DateTime::createFromFormat('Y-m-d', $date_ss);
  $date_ss =$date_ss -> format('d-m-Y');
  //echo $donnees['nb_ligne'];
 //echo $nb_saisi_stock[$j].'<br>';
  #GET VALUE CAS PAR CAS
  //echo $date_ss."<br>";
  	if ($date_ss == $D0) {$nb_saisi_stock[1] = $donnees['result'];}
  	if ($date_ss == $D1) {$nb_saisi_stock[2] = $donnees['result'];}
  	if ($date_ss == $D2) {$nb_saisi_stock[3] = $donnees['result'];}
  	if ($date_ss == $D3) {$nb_saisi_stock[4] = $donnees['result'];}
  	if ($date_ss == $D4) {$nb_saisi_stock[5] = $donnees['result'];}
  	if ($date_ss == $D5) {$nb_saisi_stock[6] = $donnees['result'];}
  	if ($date_ss == $D6) {$nb_saisi_stock[7] = $donnees['result'];}
  $j = $j+1;
}
  $q->closeCursor();
   //5 dernier Nombre de stock
$query = "SELECT CAST(date_time as DATE) as daty, COUNT(*) as result from (SELECT id_mvt,date_time,numero_commande_stock,COUNT(*) FROM mvt WHERE type_de_mvt = 'stock' GROUP BY numero_commande_stock ORDER BY id_mvt DESC) s GROUP BY CAST(date_time as DATE) ORDER BY CAST(date_time as DATE) DESC;" ;
  $q = $bdd->prepare($query);
  $q->execute(array());
  //initialisation
  $j = 1;
  $nb_js[1] = 0;
  $nb_js[2] = 0;
  $nb_js[3] = 0;
  $nb_js[4] = 0;
  $nb_js[5] = 0;
  $nb_js[6] = 0;
  $nb_js[7] = 0;
  $js_today = 0;
  while ($donnees = $q -> fetch())
{
  //$nb_js[$j] = $donnees['result'];
  #GET VALUE CAS PAR CAS
	  $daty = $donnees['daty'];
	  $daty = DateTime::createFromFormat('Y-m-d', $daty);
	  $daty =$daty -> format('d-m-Y');
	  //echo $daty."<br>";
	  	if ($daty == $D0) {$nb_js[1] = $donnees['result'];}
	  	if ($daty == $D1) {$nb_js[2] = $donnees['result'];}
	  	if ($daty == $D2) {$nb_js[3] = $donnees['result'];}
	  	if ($daty == $D3) {$nb_js[4] = $donnees['result'];}
	  	if ($daty == $D4) {$nb_js[5] = $donnees['result'];}
	  	if ($daty == $D5) {$nb_js[6] = $donnees['result'];}
	  	if ($daty == $D6) {$nb_js[7] = $donnees['result'];}
  if ($j==1) 
  	{
  $date_js = $donnees['daty'];
  //CONVERT DATE FORMAT
  $date_js = DateTime::createFromFormat('Y-m-d', $date_js);
  //GET latest date
  $date_js =$date_js -> format('d-m-Y');
  //GET number of today's journal of stock
  $today = date("d-m-Y");
  if ($today == $date_js) {
    $js_today = $donnees['result'];
  }
	}
  //echo $donnees['nb_ligne'];
 //echo $nb_js[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();

?>
  <!-- Start your project here-->  
  <div class="text-center">
      <span class="btn bg-secondary font-weight-bold text-light text-left">Journal today: <?php echo $jv_today;
      //--SCORE---
      $query2 = "SELECT user_mvt, COUNT(*) as score FROM (SELECT user_mvt FROM mvt WHERE type_de_mvt = 'vente' AND CAST(date_time as DATE) = CAST(curdate() AS DATE)  UNION ALL SELECT user FROM commande) s GROUP BY user_mvt ORDER BY score DESC;";
        $q2 = $bdd->prepare($query2);
        $q2->execute(array());
        $nb_row1 = $q2->rowCount ();
      if ($nb_row1 != 0 ){
        echo('</br></br>SCORE DE SAISI</br>');
        if ($jv_today == 0 ){echo "(IN PROGRESS)</br>";}
         while ($d = $q2 -> fetch())
        {
          echo '# '.$d['user_mvt'].':'.$d['score'].'</br>';
        }
        $q2->closeCursor();
      }
       ?></span>
      <button class="btn btn-primary font-weight-bold text-light text-left">Stock today: <?php echo $js_today;
      //--SCORE---
      $query2 = "SELECT user_mvt, COUNT(*) as score FROM (SELECT user_mvt FROM mvt WHERE type_de_mvt = 'stock' AND CAST(date_time as DATE) = CAST(curdate() AS DATE)  UNION ALL SELECT user_stock_prep FROM stock_prep UNION ALL SELECT user_stock_prep FROM stock_prep_calc) s GROUP BY user_mvt ORDER BY score DESC;";
        $q2 = $bdd->prepare($query2);
        $q2->execute(array());
        $nb_row2=$q2->rowCount ();
        if ($nb_row2 != 0 ){
        echo('</br></br>SCORE DE SAISI</br>');
        if ($js_today == 0 ){echo "(IN PROGRESS)</br>";}
         while ($d = $q2 -> fetch())
        {
          echo '# '.$d['user_mvt'].':'.$d['score'].'</br>';
        }
        $q2->closeCursor();
      }
       ?></button>
       <h5 class="animated fadeIn mb-4"><blockquote class="container"><p class="mb-0"><b>CONTROLE DE SAISI DU 7 DERNIERS JOURS</b></p></blockquote></h5>

    <div class="container flex-center flex-column " style="font-weight: bold;background-image: linear-gradient(to right,#8bc34a,rgba(5, 255, 163, .9),white,rgba(5, 255, 163, .9),#8bc34a)">
      <canvas id="lineChart_saisi" style="max-width: 900px;"></canvas>
      <br>
      <br>
    </div>
    <!-----TO AUTO AJUST WITH PARENT USE LIKE THIS
    <div class="col-md-5">
  		<canvas id="lineChart_saisi"></canvas>
	</div>
	---->
  </div>
  <!-- End your project here-->

  <!-- jQuery -->
  
  <!-- Bootstrap tooltips -->
  <!-- Bootstrap core JavaScript -->
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    //SAISI DE VENTE
    var AT1 = <?php echo json_encode($nb_saisi_vente[1]); ?>;
    var AT2 = <?php echo json_encode($nb_saisi_vente[2]); ?>;
    var AT3 = <?php echo json_encode($nb_saisi_vente[3]); ?>;
    var AT4 = <?php echo json_encode($nb_saisi_vente[4]); ?>;
    var AT5 = <?php echo json_encode($nb_saisi_vente[5]); ?>;
    var AT6 = <?php echo json_encode($nb_saisi_vente[6]); ?>;
    var AT7 = <?php echo json_encode($nb_saisi_vente[7]); ?>;
    //SAISIE DE STOCK
    var AF1 = <?php echo json_encode($nb_saisi_stock[1]); ?>;
    var AF2 = <?php echo json_encode($nb_saisi_stock[2]); ?>;
    var AF3 = <?php echo json_encode($nb_saisi_stock[3]); ?>;
    var AF4 = <?php echo json_encode($nb_saisi_stock[4]); ?>;
    var AF5 = <?php echo json_encode($nb_saisi_stock[5]); ?>;
    var AF6 = <?php echo json_encode($nb_saisi_stock[6]); ?>;
    var AF7 = <?php echo json_encode($nb_saisi_stock[7]); ?>;
    //NB JOURNAL DE VENTE
    var BJ1 = <?php echo json_encode($nb_jv[1]); ?>;
    var BJ2 = <?php echo json_encode($nb_jv[2]); ?>;
    var BJ3 = <?php echo json_encode($nb_jv[3]); ?>;
    var BJ4 = <?php echo json_encode($nb_jv[4]); ?>;
    var BJ5 = <?php echo json_encode($nb_jv[5]); ?>;
    var BJ6 = <?php echo json_encode($nb_jv[6]); ?>;
    var BJ7 = <?php echo json_encode($nb_jv[7]); ?>;
    
    //JOURNAL DE STOCK
    var MR1 = <?php echo json_encode($nb_js[1]); ?>;
    var MR2 = <?php echo json_encode($nb_js[2]); ?>;
    var MR3 = <?php echo json_encode($nb_js[3]); ?>;
    var MR4 = <?php echo json_encode($nb_js[4]); ?>;
    var MR5 = <?php echo json_encode($nb_js[5]); ?>;
    var MR6 = <?php echo json_encode($nb_js[6]); ?>;
    var MR7 = <?php echo json_encode($nb_js[7]); ?>;
    
    //DERNIER DATE
    var date_js = <?php echo json_encode($date_js); ?>;
    var date_jv = <?php echo json_encode($date_jv); ?>;
    var date_ss = <?php echo json_encode($date_ss); ?>;
    var date_sv = <?php echo json_encode($date_sv); ?>;
var ctxL = document.getElementById("lineChart_saisi").getContext('2d');
var mylineChart_saisi = new Chart(ctxL, {
type: 'line',
data: {
labels: ["J6", "J5", "J4", "J3", "J2", "J1", "J0"],
datasets: [{
label: "Saisi Vente ",
data: [AT7, AT6, AT5, AT4, AT3, AT2, AT1],
backgroundColor: [
'rgba(105, 0, 132, .4)',
],
borderColor: [
'rgba(200, 99, 132, 1)',
],
borderWidth: 2
},
{
label: "Saisi Stock ",
data: [AF7, AF6, AF5, AF4, AF3, AF2, AF1],
backgroundColor: [
'rgba(26, 213, 234, .5)',
],
borderColor: [
'rgba(16, 157, 173, 1)',
],
borderWidth: 2
},
{
label: date_jv+" | Journal de vente",
data: [BJ7, BJ6, BJ5, BJ4, BJ3, BJ2, BJ1],
backgroundColor: [
'rgba(255, 255, 128, .5)',
],
borderColor: [
'rgba(255, 127, 39, 1)',
],
borderWidth: 2
},
{
label: date_js+" | Journal de stock",
data: [MR7, MR6, MR5, MR4, MR3, MR2, MR1],
backgroundColor: [
'rgba(135, 219, 128, .5)',
],
borderColor: [
'rgba(9, 93, 16, 1)',
],
borderWidth: 2
},
]
},
options: {
responsive: true
}
});
  </script>

</body>
</html>
