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
//7 dernier Nombre de vente Ambato Tantely
$query_nb_vente_ambato_tantely = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Tantely' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
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
  //7 dernier Nombre de vente Ambato Tahina
$query_nb_vente_ambato_pneu = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Pneu' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_ambato_pneu);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambato_Pneu_J[1] = 0;
  $Ambato_Pneu_J[2] = 0;
  $Ambato_Pneu_J[3] = 0;
  $Ambato_Pneu_J[4] = 0;
  $Ambato_Pneu_J[5] = 0;
  $Ambato_Pneu_J[6] = 0;
  $Ambato_Pneu_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambato_Pneu_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
  //echo $Ambato_Pneu_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Soalazaina
$query_nb_vente_soalazaina = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Soalazaina' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_soalazaina);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Soalazaina_J[1] = 0;
  $Soalazaina_J[2] = 0;
  $Soalazaina_J[3] = 0;
  $Soalazaina_J[4] = 0;
  $Soalazaina_J[5] = 0;
  $Soalazaina_J[6] = 0;
  $Soalazaina_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Soalazaina_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
  //echo $Soalazaina_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Ambaibo_Electronique
$query_nb_vente_Ambaibo_Electronique = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Electronique' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_Ambaibo_Electronique);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambaibo_Electronique_J[1] = 0;
  $Ambaibo_Electronique_J[2] = 0;
  $Ambaibo_Electronique_J[3] = 0;
  $Ambaibo_Electronique_J[4] = 0;
  $Ambaibo_Electronique_J[5] = 0;
  $Ambaibo_Electronique_J[6] = 0;
  $Ambaibo_Electronique_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambaibo_Electronique_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
  //echo $Ambaibo_Electronique_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //7 dernier Nombre de vente Bejofo
$query_nb_vente_bejofo = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Bejofo' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_bejofo);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Bejofo_J[1] = 0;
  $Bejofo_J[2] = 0;
  $Bejofo_J[3] = 0;
  $Bejofo_J[4] = 0;
  $Bejofo_J[5] = 0;
  $Bejofo_J[6] = 0;
  $Bejofo_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Bejofo_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
 //echo $Amparafa_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Amparafa
$query_nb_vente_amparafa = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Amparafa' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
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
$query_nb_vente_amparafa = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND mvt.type_de_mvt = 'vente' AND description_date NOT LIKE '%rano%' AND description_date NOT LIKE '%fana%' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
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
$query_nb_vente_morarano = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%rano%' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
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
$query_nb_vente_andrefana = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%fana%' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
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
  //7 dernier Nombre de vente Ambato_veve_photo
$query_nb_vente_veve = "SELECT nb_ligne FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_veve_photo' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_nb_vente_veve);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Veve_J[1] = 0;
  $Veve_J[2] = 0;
  $Veve_J[3] = 0;
  $Veve_J[4] = 0;
  $Veve_J[5] = 0;
  $Veve_J[6] = 0;
  $Veve_J[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Veve_J[$j] = $donnees['nb_ligne'];
  //echo $donnees['nb_ligne'];
 //echo $Amparafa_J[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
?>
  <!-- Start your project here-->  
  <div>
    <div class="flex-center flex-column">
      <h5 class="animated fadeIn mb-3">NOMBRE DE VENTE DANS 7 DERNIERS JOURS</h5>
      <canvas id="lineChart" style="max-width: 60%;"></canvas>
      <br>
      <br>
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
    //TANTELY PNEU
    /*var AP1 = <?php echo json_encode($Ambato_Pneu_J[1]); ?>;
    var AP2 = <?php echo json_encode($Ambato_Pneu_J[2]); ?>;
    var AP3 = <?php echo json_encode($Ambato_Pneu_J[3]); ?>;
    var AP4 = <?php echo json_encode($Ambato_Pneu_J[4]); ?>;
    var AP5 = <?php echo json_encode($Ambato_Pneu_J[5]); ?>;
    var AP6 = <?php echo json_encode($Ambato_Pneu_J[6]); ?>;
    var AP7 = <?php echo json_encode($Ambato_Pneu_J[7]); ?>;*/
    //AMPARAFA
    var AF1 = <?php echo json_encode($Amparafa_J[1]); ?>;
    var AF2 = <?php echo json_encode($Amparafa_J[2]); ?>;
    var AF3 = <?php echo json_encode($Amparafa_J[3]); ?>;
    var AF4 = <?php echo json_encode($Amparafa_J[4]); ?>;
    var AF5 = <?php echo json_encode($Amparafa_J[5]); ?>;
    var AF6 = <?php echo json_encode($Amparafa_J[6]); ?>;
    var AF7 = <?php echo json_encode($Amparafa_J[7]); ?>;
    //AMBAIBOHO ELECTRONIQUE

    var AE1 = <?php echo json_encode($Ambaibo_Electronique_J[1]); ?>;
    var AE2 = <?php echo json_encode($Ambaibo_Electronique_J[2]); ?>;
    var AE3 = <?php echo json_encode($Ambaibo_Electronique_J[3]); ?>;
    var AE4 = <?php echo json_encode($Ambaibo_Electronique_J[4]); ?>;
    var AE5 = <?php echo json_encode($Ambaibo_Electronique_J[5]); ?>;
    var AE6 = <?php echo json_encode($Ambaibo_Electronique_J[6]); ?>;
    var AE7 = <?php echo json_encode($Ambaibo_Electronique_J[7]); ?>;
    //SOALAZAINA
    /*var SR1 = <?php echo json_encode($Soalazaina_J[1]); ?>;
    var SR2 = <?php echo json_encode($Soalazaina_J[2]); ?>;
    var SR3 = <?php echo json_encode($Soalazaina_J[3]); ?>;
    var SR4 = <?php echo json_encode($Soalazaina_J[4]); ?>;
    var SR5 = <?php echo json_encode($Soalazaina_J[5]); ?>;
    var SR6 = <?php echo json_encode($Soalazaina_J[6]); ?>;
    var SR7 = <?php echo json_encode($Soalazaina_J[7]); ?>;*/
    //BEJOFO
    var BJ1 = <?php echo json_encode($Bejofo_J[1]); ?>;
    var BJ2 = <?php echo json_encode($Bejofo_J[2]); ?>;
    var BJ3 = <?php echo json_encode($Bejofo_J[3]); ?>;
    var BJ4 = <?php echo json_encode($Bejofo_J[4]); ?>;
    var BJ5 = <?php echo json_encode($Bejofo_J[5]); ?>;
    var BJ6 = <?php echo json_encode($Bejofo_J[6]); ?>;
    var BJ7 = <?php echo json_encode($Bejofo_J[7]); ?>;
    //AMBAIBO_TOLE
    /*var AB1 = <?php echo json_encode($Ambaibo_Tole_J[1]); ?>;
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
    //VEVE
    var VV1 = <?php echo json_encode($Veve_J[1]); ?>;
    var VV2 = <?php echo json_encode($Veve_J[2]); ?>;
    var VV3 = <?php echo json_encode($Veve_J[3]); ?>;
    var VV4 = <?php echo json_encode($Veve_J[4]); ?>;
    var VV5 = <?php echo json_encode($Veve_J[5]); ?>;
    var VV6 = <?php echo json_encode($Veve_J[6]); ?>;
    var VV7 = <?php echo json_encode($Veve_J[7]); ?>;*/

var ctxL = document.getElementById("lineChart").getContext('2d');
var myLineChart = new Chart(ctxL, {
type: 'line',
data: {
labels: ["J6", "J5", "J4", "J3", "J2", "J1", "J0"],
datasets: [{
label: "Ambato",
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
label: "Eléc",
data: [AE7, AE6, AE5, AE4, AE3, AE2, AE1],
backgroundColor: [
'rgba(255, 0, 228, .4)',
],
borderColor: [
'rgba(255, 255, 255, 1)',
],
borderWidth: 2
},
{
label: "A/fa",
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
label: "Bejofo",
data: [BJ7, BJ6, BJ5, BJ4, BJ3, BJ2, BJ1],
backgroundColor: [
'rgba(190, 190, 190, .5)',
],
borderColor: [
'rgba(122, 122, 122, 1)',
],
borderWidth: 2
},
/*{
label: "Pneu",
data: [AP7, AP6, AP5, AP4, AP3, AP2, AP1],
backgroundColor: [
'rgba(206, 255, 0, .4)',
],
borderColor: [
'rgba(131, 255, 0, 1)',
],
borderWidth: 2
},
{
label: "Ranto",
data: [SR7, SR6, SR5, SR4, SR3, SR2, SR1],
backgroundColor: [
'rgba(236, 255, 0, .5)',
],
borderColor: [
'rgba(255, 111, 0, 1)',
],
borderWidth: 2
},
{
label: "A/bo Tole",
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
data: [VV7, VV6, VV5, VV4, VV3, VV2, VV1],
backgroundColor: [
'rgba(194, 156, 227, .8)',
],
borderColor: [
'rgba(77, 33, 115, 1)',
],
borderWidth: 2
}*/
]
},
options: {
responsive: true
}
});
  </script>

</body>
</html>
