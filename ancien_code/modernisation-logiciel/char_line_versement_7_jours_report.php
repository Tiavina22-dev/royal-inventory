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
$query_versement_ambato_tantely = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Tantely' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_ambato_tantely);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambato_Tantely_JJ[1] = 0;
  $Ambato_Tantely_JJ[2] = 0;
  $Ambato_Tantely_JJ[3] = 0;
  $Ambato_Tantely_JJ[4] = 0;
  $Ambato_Tantely_JJ[5] = 0;
  $Ambato_Tantely_JJ[6] = 0;
  $Ambato_Tantely_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambato_Tantely_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
  //echo $Ambato_Tantely_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Ambato Pneu
$query_versement_ambato_pneu = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Pneu' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_ambato_pneu);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambato_Pneu_JJ[1] = 0;
  $Ambato_Pneu_JJ[2] = 0;
  $Ambato_Pneu_JJ[3] = 0;
  $Ambato_Pneu_JJ[4] = 0;
  $Ambato_Pneu_JJ[5] = 0;
  $Ambato_Pneu_JJ[6] = 0;
  $Ambato_Pneu_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambato_Pneu_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
  //echo $Ambato_Tantely_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Ambaibo_Electronique
$query_versement_Ambaibo_Electronique = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Electronique' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_Ambaibo_Electronique);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambaibo_Electronique_JJ[1] = 0;
  $Ambaibo_Electronique_JJ[2] = 0;
  $Ambaibo_Electronique_JJ[3] = 0;
  $Ambaibo_Electronique_JJ[4] = 0;
  $Ambaibo_Electronique_JJ[5] = 0;
  $Ambaibo_Electronique_JJ[6] = 0;
  $Ambaibo_Electronique_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambaibo_Electronique_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
  //echo $Ambato_Tantely_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
    //7 dernier Nombre de vente Soalazaina
$query_versement_Soalazaina = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Soalazaina' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_Soalazaina);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Soalazaina_JJ[1] = 0;
  $Soalazaina_JJ[2] = 0;
  $Soalazaina_JJ[3] = 0;
  $Soalazaina_JJ[4] = 0;
  $Soalazaina_JJ[5] = 0;
  $Soalazaina_JJ[6] = 0;
  $Soalazaina_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Soalazaina_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
  //echo $Ambato_Tantely_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //7 dernier Nombre de vente Bejofo
$query_versement_bejofo = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Bejofo' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_bejofo);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Bejofo_JJ[1] = 0;
  $Bejofo_JJ[2] = 0;
  $Bejofo_JJ[3] = 0;
  $Bejofo_JJ[4] = 0;
  $Bejofo_JJ[5] = 0;
  $Bejofo_JJ[6] = 0;
  $Bejofo_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Bejofo_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Amparafa
$query_versement_amparafa = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Amparafa' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_amparafa);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Amparafa_JJ[1] = 0;
  $Amparafa_JJ[2] = 0;
  $Amparafa_JJ[3] = 0;
  $Amparafa_JJ[4] = 0;
  $Amparafa_JJ[5] = 0;
  $Amparafa_JJ[6] = 0;
  $Amparafa_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Amparafa_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //5 dernier Nombre de vente Ambaibo_Tole
$query_versement_amparafa = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND mvt.type_de_mvt = 'vente' AND description_date NOT LIKE '%rano%' AND description_date NOT LIKE '%fana%' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_amparafa);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Ambaibo_Tole_JJ[1] = 0;
  $Ambaibo_Tole_JJ[2] = 0;
  $Ambaibo_Tole_JJ[3] = 0;
  $Ambaibo_Tole_JJ[4] = 0;
  $Ambaibo_Tole_JJ[5] = 0;
  $Ambaibo_Tole_JJ[6] = 0;
  $Ambaibo_Tole_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Ambaibo_Tole_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
 //echo $Ambaibo_Tole_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
   //5 dernier Nombre de vente MORARANO
$query_versement_morarano = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%rano%' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_morarano);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Morarano_JJ[1] = 0;
  $Morarano_JJ[2] = 0;
  $Morarano_JJ[3] = 0;
  $Morarano_JJ[4] = 0;
  $Morarano_JJ[5] = 0;
  $Morarano_JJ[6] = 0;
  $Morarano_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Morarano_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
 //echo $Morarano_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();

//5 dernier Nombre de vente Andrefana
$query_versement_andrefana = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%fana%' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_andrefana);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Andrefana_JJ[1] = 0;
  $Andrefana_JJ[2] = 0;
  $Andrefana_JJ[3] = 0;
  $Andrefana_JJ[4] = 0;
  $Andrefana_JJ[5] = 0;
  $Andrefana_JJ[6] = 0;
  $Andrefana_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Andrefana_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
 //echo $Andrefana_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
  //7 dernier Nombre de vente Ambato_veve_photo
$query_versement_veve = "SELECT Montant FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_veve_photo' AND mvt.type_de_mvt = 'vente' GROUP BY numero_commande_stock ORDER BY numero_commande_stock DESC LIMIT 7" ;
  $q = $bdd->prepare($query_versement_veve);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Veve_JJ[1] = 0;
  $Veve_JJ[2] = 0;
  $Veve_JJ[3] = 0;
  $Veve_JJ[4] = 0;
  $Veve_JJ[5] = 0;
  $Veve_JJ[6] = 0;
  $Veve_JJ[7] = 0;
  while ($donnees = $q -> fetch())
{
  $Veve_JJ[$j] = $donnees['Montant'];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor();
?>
  <!-- Start your project here-->  
  <div>
    <div class="flex-center flex-column">
      <h5 class="animated fadeIn mb-3"><b>VERSEMENT DANS 7 DERNIERS JOURS</b></h5>
      <canvas id="VlineChart" style="max-width: 60%;"></canvas>
      <br>
      <br>
    </div>
    <!-----TO AUTO AJUST WITH PARENT USE LIKE THIS
    <div class="col-md-5">
  		<canvas id="VlineChart"></canvas>
	</div>
	---->
  </div>
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
    //TANTELY AMBATO
    var VAT1 = <?php echo json_encode($Ambato_Tantely_JJ[1]); ?>;
    var VAT2 = <?php echo json_encode($Ambato_Tantely_JJ[2]); ?>;
    var VAT3 = <?php echo json_encode($Ambato_Tantely_JJ[3]); ?>;
    var VAT4 = <?php echo json_encode($Ambato_Tantely_JJ[4]); ?>;
    var VAT5 = <?php echo json_encode($Ambato_Tantely_JJ[5]); ?>;
    var VAT6 = <?php echo json_encode($Ambato_Tantely_JJ[6]); ?>;
    var VAT7 = <?php echo json_encode($Ambato_Tantely_JJ[7]); ?>;
    //TANTELY PNEU
    var VAP1 = <?php echo json_encode($Ambato_Pneu_JJ[1]); ?>;
    var VAP2 = <?php echo json_encode($Ambato_Pneu_JJ[2]); ?>;
    var VAP3 = <?php echo json_encode($Ambato_Pneu_JJ[3]); ?>;
    var VAP4 = <?php echo json_encode($Ambato_Pneu_JJ[4]); ?>;
    var VAP5 = <?php echo json_encode($Ambato_Pneu_JJ[5]); ?>;
    var VAP6 = <?php echo json_encode($Ambato_Pneu_JJ[6]); ?>;
    var VAP7 = <?php echo json_encode($Ambato_Pneu_JJ[7]); ?>;
    //AMBAIBOHO ELECTRONIQUE
    var VAE1 = <?php echo json_encode($Ambaibo_Electronique_JJ[1]); ?>;
    var VAE2 = <?php echo json_encode($Ambaibo_Electronique_JJ[2]); ?>;
    var VAE3 = <?php echo json_encode($Ambaibo_Electronique_JJ[3]); ?>;
    var VAE4 = <?php echo json_encode($Ambaibo_Electronique_JJ[4]); ?>;
    var VAE5 = <?php echo json_encode($Ambaibo_Electronique_JJ[5]); ?>;
    var VAE6 = <?php echo json_encode($Ambaibo_Electronique_JJ[6]); ?>;
    var VAE7 = <?php echo json_encode($Ambaibo_Electronique_JJ[7]); ?>;
    //SOALAZAINA RANTO
    var VSR1 = <?php echo json_encode($Soalazaina_JJ[1]); ?>;
    var VSR2 = <?php echo json_encode($Soalazaina_JJ[2]); ?>;
    var VSR3 = <?php echo json_encode($Soalazaina_JJ[3]); ?>;
    var VSR4 = <?php echo json_encode($Soalazaina_JJ[4]); ?>;
    var VSR5 = <?php echo json_encode($Soalazaina_JJ[5]); ?>;
    var VSR6 = <?php echo json_encode($Soalazaina_JJ[6]); ?>;
    var VSR7 = <?php echo json_encode($Soalazaina_JJ[7]); ?>;
    //AMPARAFA
    var VAF1 = <?php echo json_encode($Amparafa_JJ[1]); ?>;
    var VAF2 = <?php echo json_encode($Amparafa_JJ[2]); ?>;
    var VAF3 = <?php echo json_encode($Amparafa_JJ[3]); ?>;
    var VAF4 = <?php echo json_encode($Amparafa_JJ[4]); ?>;
    var VAF5 = <?php echo json_encode($Amparafa_JJ[5]); ?>;
    var VAF6 = <?php echo json_encode($Amparafa_JJ[6]); ?>;
    var VAF7 = <?php echo json_encode($Amparafa_JJ[7]); ?>;
    //BEJOFO
    var VBJ1 = <?php echo json_encode($Bejofo_JJ[1]); ?>;
    var VBJ2 = <?php echo json_encode($Bejofo_JJ[2]); ?>;
    var VBJ3 = <?php echo json_encode($Bejofo_JJ[3]); ?>;
    var VBJ4 = <?php echo json_encode($Bejofo_JJ[4]); ?>;
    var VBJ5 = <?php echo json_encode($Bejofo_JJ[5]); ?>;
    var VBJ6 = <?php echo json_encode($Bejofo_JJ[6]); ?>;
    var VBJ7 = <?php echo json_encode($Bejofo_JJ[7]); ?>;
    //AMBAIBO_TOLE
    var VAB1 = <?php echo json_encode($Ambaibo_Tole_JJ[1]); ?>;
    var VAB2 = <?php echo json_encode($Ambaibo_Tole_JJ[2]); ?>;
    var VAB3 = <?php echo json_encode($Ambaibo_Tole_JJ[3]); ?>;
    var VAB4 = <?php echo json_encode($Ambaibo_Tole_JJ[4]); ?>;
    var VAB5 = <?php echo json_encode($Ambaibo_Tole_JJ[5]); ?>;
    var VAB6 = <?php echo json_encode($Ambaibo_Tole_JJ[6]); ?>;
    var VAB7 = <?php echo json_encode($Ambaibo_Tole_JJ[7]); ?>;
    //MORARANO
    var VMR1 = <?php echo json_encode($Morarano_JJ[1]); ?>;
    var VMR2 = <?php echo json_encode($Morarano_JJ[2]); ?>;
    var VMR3 = <?php echo json_encode($Morarano_JJ[3]); ?>;
    var VMR4 = <?php echo json_encode($Morarano_JJ[4]); ?>;
    var VMR5 = <?php echo json_encode($Morarano_JJ[5]); ?>;
    var VMR6 = <?php echo json_encode($Morarano_JJ[6]); ?>;
    var VMR7 = <?php echo json_encode($Morarano_JJ[7]); ?>;
    //ANDREFANA
    var VDR1 = <?php echo json_encode($Andrefana_JJ[1]); ?>;
    var VDR2 = <?php echo json_encode($Andrefana_JJ[2]); ?>;
    var VDR3 = <?php echo json_encode($Andrefana_JJ[3]); ?>;
    var VDR4 = <?php echo json_encode($Andrefana_JJ[4]); ?>;
    var VDR5 = <?php echo json_encode($Andrefana_JJ[5]); ?>;
    var VDR6 = <?php echo json_encode($Andrefana_JJ[6]); ?>;
    var VDR7 = <?php echo json_encode($Andrefana_JJ[7]); ?>;
    //VEVE
    var VVV1 = <?php echo json_encode($Veve_JJ[1]); ?>;
    var VVV2 = <?php echo json_encode($Veve_JJ[2]); ?>;
    var VVV3 = <?php echo json_encode($Veve_JJ[3]); ?>;
    var VVV4 = <?php echo json_encode($Veve_JJ[4]); ?>;
    var VVV5 = <?php echo json_encode($Veve_JJ[5]); ?>;
    var VVV6 = <?php echo json_encode($Veve_JJ[6]); ?>;
    var VVV7 = <?php echo json_encode($Veve_JJ[7]); ?>;

var vctxL = document.getElementById("VlineChart").getContext('2d');
var VmyVlineChart = new Chart(vctxL, {
type: 'line',
data: {
labels: ["J6", "J5", "J4", "J3", "J2", "J1", "J0"],
datasets: [{
label: "Tantely",
data: [VAT7, VAT6, VAT5, VAT4, VAT3, VAT2, VAT1],
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
data: [VAE7, VAE6, VAE5, VAE4, VAE3, VAE2, VAE1],
backgroundColor: [
'rgba(255, 0, 228, .4)',
],
borderColor: [
'rgba(255, 255, 255, 1)',
],
borderWidth: 2
},
{
label: "Pneu",
data: [VAP7, VAP6, VAP5, VAP4, VAP3, VAP2, VAP1],
backgroundColor: [
'rgba(206, 255, 0, .4)',
],
borderColor: [
'rgba(131, 255, 0, 1)',
],
borderWidth: 2
},
{
label: "A/fa",
data: [VAF7, VAF6, VAF5, VAF4, VAF3, VAF2, VAF1],
backgroundColor: [
'rgba(26, 213, 234, .5)',
],
borderColor: [
'rgba(16, 157, 173, 1)',
],
borderWidth: 2
},
{
label: "Bejefo",
data: [VBJ7, VBJ6, VBJ5, VBJ4, VBJ3, VBJ2, VBJ1],
backgroundColor: [
'rgba(190, 190, 190, .5)',
],
borderColor: [
'rgba(122, 122, 122, 1)',
],
borderWidth: 2
},
{
label: "Ranto",
data: [VSR7, VSR6, VSR5, VSR4, VSR3, VSR2, VSR1],
backgroundColor: [
'rgba(236, 255, 0, .5)',
],
borderColor: [
'rgba(255, 111, 0, 1)',
],
borderWidth: 2
},
{
label: "Tole",
data: [VAB7, VAB6, VAB5, VAB4, VAB3, VAB2, VAB1],
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
data: [VDR7, VDR6, VDR5, VDR4, VDR3, VDR2, VDR1],
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
data: [VMR7, VMR6, VMR5, VMR4, VMR3, VMR2, VMR1],
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
data: [VVV7, VVV6, VVV5, VVV4, VVV3, VVV2, VVV1],
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