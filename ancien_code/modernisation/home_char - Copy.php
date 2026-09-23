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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<?php
include('connect.php');
//5 dernier Nombre de vente Ambato Tantely
$query_nb_vente_ambato_tantely = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Tantely' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_ambato_tantely);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambato_Tantely_J[1] = 0;
  $Ambato_Tantely_J[2] = 0;
  $Ambato_Tantely_J[3] = 0;
  $Ambato_Tantely_J[4] = 0;
  $Ambato_Tantely_J[5] = 0;
  $Ambato_Tantely_J[6] = 0;
  $Ambato_Tantely_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambato_Tantely_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
  //echo $Ambato_Tantely_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //5 dernier Nombre de vente Amparafa
$query_nb_vente_amparafa = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Amparafa' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_amparafa);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Amparafa_J[1] = 0;
  $Amparafa_J[2] = 0;
  $Amparafa_J[3] = 0;
  $Amparafa_J[4] = 0;
  $Amparafa_J[5] = 0;
  $Amparafa_J[6] = 0;
  $Amparafa_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Amparafa_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
 //echo $Amparafa_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //5 dernier Nombre de vente Ambaibo_Tole
$query_nb_vente_amparafa = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date NOT LIKE '%rano%' AND description_date NOT LIKE '%fana%' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_amparafa);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambaibo_Tole_J[1] = 0;
  $Ambaibo_Tole_J[2] = 0;
  $Ambaibo_Tole_J[3] = 0;
  $Ambaibo_Tole_J[4] = 0;
  $Ambaibo_Tole_J[5] = 0;
  $Ambaibo_Tole_J[6] = 0;
  $Ambaibo_Tole_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambaibo_Tole_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
 //echo $Ambaibo_Tole_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //5 dernier Nombre de vente MORARANO
$query_nb_vente_morarano = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%rano%' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_morarano);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Morarano_J[1] = 0;
  $Morarano_J[2] = 0;
  $Morarano_J[3] = 0;
  $Morarano_J[4] = 0;
  $Morarano_J[5] = 0;
  $Morarano_J[6] = 0;
  $Morarano_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Morarano_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
 //echo $Morarano_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();

//5 dernier Nombre de vente Andrefana
$query_nb_vente_andrefana = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%fana%' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_andrefana);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Andrefana_J[1] = 0;
  $Andrefana_J[2] = 0;
  $Andrefana_J[3] = 0;
  $Andrefana_J[4] = 0;
  $Andrefana_J[5] = 0;
  $Andrefana_J[6] = 0;
  $Andrefana_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Andrefana_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
 //echo $Andrefana_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
?>
  <!-- Start your project here-->  
  <div>
    <div class="flex-center flex-column">
      <h5 class="animated fadeIn mb-3">Nombre de vente dans 7 derniers Jours</h5>
      <canvas id="lineChart" style="max-width: 900px;"></canvas>
    </div>
    <!-----TO AUTO AJUST WITH PARENT USE LIKE THIS
    <div class="col-md-5">
  		<canvas id="lineChart"></canvas>
	</div>
	---->
  </div>
  <!-- End your project here-->

  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    //TANTELY AMBATO
    var AT1 = <?php echo json_encode($Ambato_Tantely_J[1]); ?>;
    var AT2 = <?php echo json_encode($Ambato_Tantely_J[2]); ?>;
    var AT3 = <?php echo json_encode($Ambato_Tantely_J[3]); ?>;
    var AT4 = <?php echo json_encode($Ambato_Tantely_J[4]); ?>;
    var AT5 = <?php echo json_encode($Ambato_Tantely_J[5]); ?>;
    var AT6 = <?php echo json_encode($Ambato_Tantely_J[6]); ?>;
    var AT7 = <?php echo json_encode($Ambato_Tantely_J[7]); ?>;
    //AMPARAFA
    var AF1 = <?php echo json_encode($Amparafa_J[1]); ?>;
    var AF2 = <?php echo json_encode($Amparafa_J[2]); ?>;
    var AF3 = <?php echo json_encode($Amparafa_J[3]); ?>;
    var AF4 = <?php echo json_encode($Amparafa_J[4]); ?>;
    var AF5 = <?php echo json_encode($Amparafa_J[5]); ?>;
    var AF6 = <?php echo json_encode($Amparafa_J[6]); ?>;
    var AF7 = <?php echo json_encode($Amparafa_J[7]); ?>;
    //AMBAIBO_TOLE
    var AB1 = <?php echo json_encode($Ambaibo_Tole_J[1]); ?>;
    var AB2 = <?php echo json_encode($Ambaibo_Tole_J[2]); ?>;
    var AB3 = <?php echo json_encode($Ambaibo_Tole_J[3]); ?>;
    var AB4 = <?php echo json_encode($Ambaibo_Tole_J[4]); ?>;
    var AB5 = <?php echo json_encode($Ambaibo_Tole_J[5]); ?>;
    var AB6 = <?php echo json_encode($Ambaibo_Tole_J[6]); ?>;
    var AB7 = <?php echo json_encode($Ambaibo_Tole_J[7]); ?>;
    //MORARANO
    var MR1 = <?php echo json_encode($Morarano_J[1]); ?>;
    var MR2 = <?php echo json_encode($Morarano_J[2]); ?>;
    var MR3 = <?php echo json_encode($Morarano_J[3]); ?>;
    var MR4 = <?php echo json_encode($Morarano_J[4]); ?>;
    var MR5 = <?php echo json_encode($Morarano_J[5]); ?>;
    var MR6 = <?php echo json_encode($Morarano_J[6]); ?>;
    var MR7 = <?php echo json_encode($Morarano_J[7]); ?>;
    //ANDREFANA
    var DR1 = <?php echo json_encode($Andrefana_J[1]); ?>;
    var DR2 = <?php echo json_encode($Andrefana_J[2]); ?>;
    var DR3 = <?php echo json_encode($Andrefana_J[3]); ?>;
    var DR4 = <?php echo json_encode($Andrefana_J[4]); ?>;
    var DR5 = <?php echo json_encode($Andrefana_J[5]); ?>;
    var DR6 = <?php echo json_encode($Andrefana_J[6]); ?>;
    var DR7 = <?php echo json_encode($Andrefana_J[7]); ?>;

var ctxL = document.getElementById("lineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
type: 'line',
data: {
labels: ["J1", "J2", "J3", "J4", "J5", "J6", "J7"],
datasets: [{
label: "Ambato Tantely",
data: [AT1, AT2, AT3, AT4, AT5, AT6, AT7],
backgroundColor: [
'rgba(105, 0, 132, .4)',
],
borderColor: [
'rgba(200, 99, 132, 1)',
],
borderWidth: 2
},
{
label: "Amparafa",
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
label: "Ambaibo Tole",
data: [AB7, AB6, AB5, AB4, AB3, AB2, AB1],
backgroundColor: [
'rgba(0, 137, 132, .2)',
],
borderColor: [
'rgba(0, 10, 130, .7)',
],
borderWidth: 2
},
{
label: "Andrefana",
data: [DR7, DR6, DR5, DR4, DR3, DR2, DR1],
backgroundColor: [
'rgba(239, 215, 82, .5)',
],
borderColor: [
'rgba(196, 169, 18, 1)',
],
borderWidth: 2
},
{
label: "Morarano",
data: [MR7, MR6, MR5, MR4, MR3, MR2, MR1],
backgroundColor: [
'rgba(135, 219, 128, .5)',
],
borderColor: [
'rgba(9, 93, 16, 1)',
],
borderWidth: 2
},
{
label: "Veve",
data: [1, 1, 0, 1, 0, 1, 0],
backgroundColor: [
'rgba(194, 156, 227, .8)',
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
