<!DOCTYPE html>
<html>
<head>
	<title>TOMBONY</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link rel="stylesheet" type="text/css" href="css/style_Index.css">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
    <br>
    <br>
    <br>
    <br>
    <br>
<div class="text-center">
        <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#Demarrer">CHOISIR DATE</button>
</div>
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">CRITERE DU LISTE DE VENTE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
        </div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="tsinjo_critere.php" method="post">
    <div class="form-group">
        <label>DATE DU DEBUT</label>
        <input class="form-control btn-primary" value = <?php $starting = strtotime("-1 Months");
 $starting = date("Y-m-01", $starting); echo $starting; ?> name="debut_date" type="DATE" oninput="unlock($(this));" id = "debut_date">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Valider</button>
    </div>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
 <?php
//Connect to BD
include('connect.php');
    
        $debut_date = strtotime("-1 Months");
        $debut_date = date("Y-m-01", $debut_date);
        $fin_date = date("Y-m-d");

     if (isset($_COOKIE['fin_date'])) 
    {
        $fin_date = $_COOKIE['fin_date'];
        $debut_date = $_COOKIE['debut_date'];
    }
        //FORMAT DATE FOR AFFICHAGE
        $debut = DateTime::createFromFormat('Y-m-d',$debut_date) ;
        $mois = $debut -> format('M Y');
        $YEAR =$debut -> format('Y');
        $MOIS =$debut -> format('M');
    

    $TL_p75 = 0;
    $p75_a = 0;
    $p75_b = 0;
    $p75_s = 0;
    $p25_a = 0;
    $p25_b = 0;
    $p25_s = 0;
    //-------------AMPARAFA--------------
    $SALAIRE_A = 800000;
    $POURCENTAGE_A = 25;
    $POURCENTAGE_A_TSINJO = 75;
    $POURCENTAGE_A_COMPT_ASSISTANTS = 23;
    $POURCENTAGE_A_COMPT_ADMIN = 20;
    $POURCENTAGE_A_ROYAL = 32;

    $TL_COMPT_ADMIN_A = 0;
    $TL_COMPT_ASSISTANTS_A = 0;
    $TL_ROYAL_A = 0;

    //-------------BEJOFO----------------
    $SALAIRE_B = 800000;
    $POURCENTAGE_B = 25;
    $POURCENTAGE_B_TSINJO = 75;
    $POURCENTAGE_B_COMPT_ASSISTANTS = 23;
    $POURCENTAGE_B_COMPT_ADMIN = 20;
    $POURCENTAGE_B_ROYAL = 32;

    $TL_COMPT_ADMIN_A = 0;
    $TL_COMPT_ASSISTANTS_A = 0;
    $TL_ROYAL_A = 0;
    //-------------SOALAZAINA------------
    $SALAIRE_S = 800000;
    $POURCENTAGE_S = 27;
    $POURCENTAGE_S_TSINJO = 73;
    $POURCENTAGE_S_COMPT_ASSISTANTS = 23;
    $POURCENTAGE_S_COMPT_ADMIN = 20;
    $POURCENTAGE_S_ROYAL = 30;

    $TL_COMPT_ADMIN_S = 0;
    $TL_COMPT_ASSISTANTS_S = 0;
    $TL_ROYAL_S = 0;
//-----------------TSINJO----------------
    

//Read cookie for validation msg


?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------------------------->
<!---------TO REMOVED : COMMENT THE FOLLOWING CODE-------->
<!--Debut du collapsible--->
<!---||||||||||||||||||||||||||||||||||||||||AMPARAFA||||||||||||||||||||||||||||||||||--->
<br>
<br>
<br>
<br>
<br>
<br>
<div id="contenu">
	<!--Liste tous les stocks--->
	<div class="card">
		<div id="allstock">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
	 <table class="tg">
	
        <thead>
        <tr class="violet">
            <th style="text-align: center;" colspan="5">COMPTE AMPARAFA MOIS DE <?php echo strtoupper($mois) ; ?></th>
        </tr>
        <tr class="orange">
            <th>Date</th>
            <th style="text-align: right;">VERSEMENT</th>
            <th style="text-align: right;">TSY AMPY</th>
            <th style="text-align: right;">NALAINY</th>
            <th style="text-align: right;">TOMBONY</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = 'Amparafa' AND YEAR(Date_du_Journal_mvt) = ? AND date_format(Date_du_Journal_mvt,'%b') = ? GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC ;";
$query_journal_vente = $bdd->prepare($query_journal_vente);
$query_journal_vente->execute(array($YEAR , $MOIS));
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
$sum_resolution = 0;
$sum_mihoatra = 0;
$sum_difference = 0;
$sum_depense_aparafa = 0;
$classname="tg-0lax2a";
$Versmt = 0;

while ($donnees = $query_journal_vente -> fetch())
{ 
$sum_resolution = $donnees['resolution'] + $sum_resolution;
$sum_mihoatra = $donnees['mihoatra'] + $sum_mihoatra;
$sum_difference = $donnees['difference_aparafa'] + $sum_difference;
$sum=$donnees['Montant']+$sum;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
//$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
//$path = str_replace("\\", "/", $path);

//echo $path;
//$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
//Show vola nalain i Aparafa----------------------
    $depense_aparafa = 0;
    $query_nalain_aparafa = 'SELECT SUM(depense_aparafa) as depense_aparafa FROM depense WHERE activity_no = ?';
    $q = $bdd->prepare($query_nalain_aparafa);
    $q->execute(array($donnees['no_activite']));
    $nbr=$q->rowCount ();
    $data = $q -> fetch();
    if ($nbr<>0) {
        $depense_aparafa = ABS($data['depense_aparafa']);
    } else {
        $depense_aparafa = 0;
    }
    $q->closeCursor();
    $sum_depense_aparafa = $sum_depense_aparafa + $depense_aparafa;
//Show Depense Royal------------------------
    $point_de_vente = $donnees['nom_client_fournisseur'];

//------------------------------------------------- 
//COLOR OF LINE
  if ($classname=="tg-0lax2a") {
    $classname="tg-0lax4b";
  } else {
    $classname="tg-0lax2a";
  }
  $Versmt = $Versmt + $donnees['Montant']+0;                  
?>
<!------------------------------------------------->
        <tr class=<?php echo $classname; ?>>
        <td style="text-align: left;"><b><?php echo $donnees['description_date']; ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($donnees['Montant'],0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format(ABS($donnees['resolution']),0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format(ABS($depense_aparafa),0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($donnees['difference_aparafa'],0, "", " ");?></b></td>
        </tr>

<!-------------------------------------->
<?php
}
?>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right;"><?php $Versmt_amparafa = $Versmt; echo number_format($Versmt,0, "", " "); ?></th>
            <th style="text-align: right;"><?php $tsyampy_amparafa = abs($sum_resolution); echo number_format(abs($sum_resolution),0, "", " "); ?></th>
            <th style="text-align: right;"><?php $nalain_amparafa = abs($sum_depense_aparafa); echo number_format(abs($sum_depense_aparafa),0, "", " "); ?></th>
            <th style="text-align: right;"><?php $tombony_amparafa = $sum_difference; echo number_format($sum_difference,0, "", " "); ?></th>
        </tr>
    <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
            echo "TOMBONY TSINJO (100%)";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(($sum_difference - $SALAIRE_A+$sum_resolution),0, "", " ");
        ?></th>
    </tr>
	<tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
            echo "SALAIRE DE BASE AMPARAFA<br>";
            echo $POURCENTAGE_A."%<br>";
            echo "<big>TL SALAIRE AMPARAFA</big><br>";
            echo "EFA NALAINY<br>";
            echo "RESTE SALAIRE AMPARAFA<br>";
        ?></th>
        <th style="text-align: right;"><?php
        if ($sum_difference < $SALAIRE_A) {
            $salaire_vendeuse = $sum_difference;
            $p25_vendeuse = 0;
            $p75 = 0;
            $TL_COMPT_ADMIN_A = 0;
            $TL_COMPT_ASSISTANTS_A = 0;
            $TL_ROYAL_A = 0;
        } else {
            $p25_vendeuse = ($sum_difference - $SALAIRE_A+$sum_resolution)*$POURCENTAGE_A/100;
            $p75 = ($sum_difference - $SALAIRE_A+$sum_resolution)*$POURCENTAGE_A_TSINJO/100;
            $salaire_vendeuse = $SALAIRE_A;

            $TL_COMPT_ADMIN_A = ($sum_difference - $SALAIRE_A+$sum_resolution)*$POURCENTAGE_A_COMPT_ADMIN/100;
            $TL_COMPT_ASSISTANTS_A = ($sum_difference - $SALAIRE_A+$sum_resolution)*$POURCENTAGE_A_COMPT_ASSISTANTS/100;
            $TL_ROYAL_A = ($sum_difference - $SALAIRE_A+$sum_resolution)*$POURCENTAGE_A_ROYAL/100;
        }
        $TL_p75 = $p75 + $TL_p75;
        $p75_a = $p75;
        $p25_a = $p25_vendeuse;

        echo number_format(abs($salaire_vendeuse),0, "", " ");
        echo "<br>";
        echo number_format(abs($p25_vendeuse),0, "", " ");
        echo "<br>";
        echo "<big>".number_format(abs($salaire_vendeuse + $p25_vendeuse),0, "", " ")."</big>";
        echo "<br>";
        echo number_format(abs($sum_depense_aparafa),0, "", " ");
        echo "<br>";
        echo number_format(($salaire_vendeuse + $p25_vendeuse - $sum_depense_aparafa),0, "", " ");
        ?></th>
    </tr>
    <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
        
            echo 'TSINJO ROYAL '.(100-$POURCENTAGE_A)."% <br>HIKARAKARANA NY TSENA <br>HOAN'NY FITSABOANA<br> MPIKARAKARA ENTANA SY KAONTY";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(abs($p75),0, "", " ");
        ?></th>
    </tr>
        </tbody>
    </table>
    <!---||||||||||||||||||||||||||||||||||||||||SOALAZAINA||||||||||||||||||||||||||||||||||--->
    <br>
    <br>
    <br>
    <br>
    <table class="tg">
    
        <thead>
        <tr class="violet">
            <th style="text-align: center;" colspan="5">COMPTE SOALAZAINA MOIS DE <?php echo strtoupper($mois) ; ?></th>
        </tr>
        <tr class="orange">
            <th>Date</th>
            <th style="text-align: right;">VERSEMENT</th>
            <th style="text-align: right;">TSY AMPY</th>
            <th style="text-align: right;">NALAINY</th>
            <th style="text-align: right;">TOMBONY</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = 'Soalazaina' AND YEAR(Date_du_Journal_mvt) = ? AND date_format(Date_du_Journal_mvt,'%b') = ? GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC ;";
$query_journal_vente = $bdd->prepare($query_journal_vente);
$query_journal_vente->execute(array($YEAR , $MOIS));
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
$sum_resolution = 0;
$sum_mihoatra = 0;
$sum_difference = 0;
$sum_depense_aparafa = 0;
$classname="tg-0lax2a";
$Versmt = 0;

while ($donnees = $query_journal_vente -> fetch())
{ 
$sum_resolution = $donnees['resolution'] + $sum_resolution;
$sum_mihoatra = $donnees['mihoatra'] + $sum_mihoatra;
$sum_difference = $donnees['difference_aparafa'] + $sum_difference;
$sum=$donnees['Montant']+$sum;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
//$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
//$path = str_replace("\\", "/", $path);

//echo $path;
//$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
//Show vola nalain i Aparafa----------------------
    $depense_aparafa = 0;
    $query_nalain_aparafa = 'SELECT SUM(depense_aparafa) as depense_aparafa FROM depense WHERE activity_no = ?';
    $q = $bdd->prepare($query_nalain_aparafa);
    $q->execute(array($donnees['no_activite']));
    $nbr=$q->rowCount ();
    $data = $q -> fetch();
    if ($nbr<>0) {
        $depense_aparafa = ABS($data['depense_aparafa']);
    } else {
        $depense_aparafa = 0;
    }
    $q->closeCursor();
    $sum_depense_aparafa = $sum_depense_aparafa + $depense_aparafa;
//Show Depense Royal------------------------

    $point_de_vente = $donnees['nom_client_fournisseur'];

//------------------------------------------------- 
//COLOR OF LINE
  if ($classname=="tg-0lax2a") {
    $classname="tg-0lax4b";
  } else {
    $classname="tg-0lax2a";
  }
  $Versmt = $Versmt + $donnees['Montant']+0;                  
?>
<!------------------------------------------------->
        <tr class=<?php echo $classname; ?>>
        <td style="text-align: left;"><b><?php echo $donnees['description_date']; ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($donnees['Montant'],0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format(ABS($donnees['resolution']),0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format(ABS($depense_aparafa),0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($donnees['difference_aparafa'],0, "", " ");?></b></td>
        </tr>

<!-------------------------------------->
<?php
}
?>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right;"><?php $Versmt_soal = $Versmt; echo number_format($Versmt,0, "", " "); ?></th>
            <th style="text-align: right;"><?php $tsyampy_soal = abs($sum_resolution); echo number_format(abs($sum_resolution),0, "", " "); ?></th>
            <th style="text-align: right;"><?php $nalain_soal = abs($sum_depense_aparafa); echo number_format(abs($sum_depense_aparafa),0, "", " "); ?></th>
            <th style="text-align: right;"><?php $tombony_soal = $sum_difference; echo number_format($sum_difference,0, "", " "); ?></th>
        </tr>
   <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
            echo "TOMBONY TSINJO (100%)";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(($sum_difference - $SALAIRE_S+$sum_resolution),0, "", " ");
        ?></th>
    </tr>
    <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
            echo "SALAIRE DE BASE SOALAZAINA<br>";
            echo $POURCENTAGE_S."%<br>";
            echo "<big>TL SALAIRE SOALAZAINA</big><br>";
            echo "EFA NALAINY<br>";
            echo "RESTE SOALAZAINA<br>";
        ?></th>
        <th style="text-align: right;"><?php
        if ($sum_difference < $SALAIRE_S) {
            $salaire_vendeuse = $sum_difference;
            $p25_vendeuse = 0;
            $p75 = 0;

            $TL_COMPT_ADMIN_S = 0;
            $TL_COMPT_ASSISTANTS_S = 0;
            $TL_ROYAL_S = 0;
        } else {
            $p25_vendeuse = ($sum_difference - $SALAIRE_S+$sum_resolution)*$POURCENTAGE_S/100;
            $p75 = ($sum_difference - $SALAIRE_S+$sum_resolution)*$POURCENTAGE_S_TSINJO/100;
            $salaire_vendeuse = $SALAIRE_S;

            $TL_COMPT_ADMIN_S = ($sum_difference - $SALAIRE_S+$sum_resolution)*$POURCENTAGE_S_COMPT_ADMIN/100;
            $TL_COMPT_ASSISTANTS_S = ($sum_difference - $SALAIRE_S+$sum_resolution)*$POURCENTAGE_S_COMPT_ASSISTANTS/100;
            $TL_ROYAL_S = ($sum_difference - $SALAIRE_S+$sum_resolution)*$POURCENTAGE_S_ROYAL/100;
        }
        
        $TL_p75 = $p75 + $TL_p75;
        $p75_s = $p75;
        $p25_s = $p25_vendeuse;
        
        echo number_format(abs($salaire_vendeuse),0, "", " ");
        echo "<br>";
        echo number_format(abs($p25_vendeuse),0, "", " ");
        echo "<br>";
        echo '<big>'.number_format(abs($salaire_vendeuse + $p25_vendeuse),0, "", " ").'</big>';
        echo "<br>";
        echo number_format(abs($sum_depense_aparafa),0, "", " ");
        echo "<br>";
        echo number_format(($salaire_vendeuse + $p25_vendeuse - $sum_depense_aparafa),0, "", " ");
        ?></th>
    </tr>
        <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
        
            echo 'TSINJO ROYAL '.(100-$POURCENTAGE_S)."% <br>HIKARAKARANA NY TSENA <br>HOAN'NY FITSABOANA<br> MPIKARAKARA ENTANA SY KAONTY";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(abs($p75),0, "", " ");
        ?></th>
    </tr>
        </tbody>
    </table>
        <!---||||||||||||||||||||||||||||||||||||||||BEJOFO||||||||||||||||||||||||||||||||||--->
    <br>
    <br>
    <br>
    <br>
    <table class="tg">
    
        <thead>
        <tr class="violet">
            <th style="text-align: center;" colspan="5">COMPTE BEJOFO MOIS DE <?php echo strtoupper($mois) ; ?></th>
        </tr>
        <tr class="orange">
            <th>Date</th>
            <th style="text-align: right;">VERSEMENT</th>
            <th style="text-align: right;">TSY AMPY</th>
            <th style="text-align: right;">NALAINY</th>
            <th style="text-align: right;">TOMBONY</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Journal de vente
$query_journal_vente = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = 'Bejofo' AND YEAR(Date_du_Journal_mvt) = ? AND date_format(Date_du_Journal_mvt,'%b') = ? GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC ;";
$query_journal_vente = $bdd->prepare($query_journal_vente);
$query_journal_vente->execute(array($YEAR , $MOIS));
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
$sum_resolution = 0;
$sum_mihoatra = 0;
$sum_difference = 0;
$sum_depense_aparafa = 0;
$classname="tg-0lax2a";
$Versmt = 0;

while ($donnees = $query_journal_vente -> fetch())
{ 
$sum_resolution = $donnees['resolution'] + $sum_resolution;
$sum_mihoatra = $donnees['mihoatra'] + $sum_mihoatra;
$sum_difference = $donnees['difference_aparafa'] + $sum_difference;
$sum=$donnees['Montant']+$sum;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
//$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
//$path = str_replace("\\", "/", $path);

//echo $path;
//$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
//Show vola nalain i Aparafa----------------------
    $depense_aparafa = 0;
    $query_nalain_aparafa = 'SELECT SUM(depense_aparafa) as depense_aparafa FROM depense WHERE activity_no = ?';
    $q = $bdd->prepare($query_nalain_aparafa);
    $q->execute(array($donnees['no_activite']));
    $nbr=$q->rowCount ();
    $data = $q -> fetch();
    if ($nbr<>0) {
        $depense_aparafa = ABS($data['depense_aparafa']);
    } else {
        $depense_aparafa = 0;
    }
    $q->closeCursor();
    $sum_depense_aparafa = $sum_depense_aparafa + $depense_aparafa;
//Show Depense Royal------------------------

    $point_de_vente = $donnees['nom_client_fournisseur'];

//------------------------------------------------- 
//COLOR OF LINE
  if ($classname=="tg-0lax2a") {
    $classname="tg-0lax4b";
  } else {
    $classname="tg-0lax2a";
  }
  $Versmt = $Versmt + $donnees['Montant']+0;                  
?>
<!------------------------------------------------->
        <tr class=<?php echo $classname; ?>>
        <td style="text-align: left;"><b><?php echo $donnees['description_date']; ?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($donnees['Montant'],0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format(ABS($donnees['resolution']),0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format(ABS($depense_aparafa),0, "", " ");?></b></td>
        <td style="text-align: right;"><b><?php echo number_format($donnees['difference_aparafa'],0, "", " ");?></b></td>
        </tr>

<!-------------------------------------->
<?php
}
?>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right;"><?php $Versmt_bejofo = $Versmt; echo number_format($Versmt,0, "", " "); ?></th>
            <th style="text-align: right;"><?php $tsyampy_bejofo = abs($sum_resolution); echo number_format(abs($sum_resolution),0, "", " "); ?></th>
            <th style="text-align: right;"><?php $nalain_bejofo = abs($sum_depense_aparafa); echo number_format(abs($sum_depense_aparafa),0, "", " "); ?></th>
            <th style="text-align: right;"><?php $tombony_bejofo = $sum_difference; echo number_format($sum_difference,0, "", " "); ?></th>
        </tr>
   <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
            echo "TOMBONY TSINJO (100%)";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(($sum_difference - $SALAIRE_B+$sum_resolution),0, "", " ");
        ?></th>
    </tr>
    <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
            echo "SALAIRE DE BASE BEJOFO<br>";
            echo $POURCENTAGE_B."%<br>";
            echo "<big>TL SALAIRE BEJOFO</big><br>";
            echo "EFA NALAINY<br>";
            echo "RESTE BEJOFO<br>";
        ?></th>
        <th style="text-align: right;"><?php
        if ($sum_difference < $SALAIRE_B) {
            $salaire_vendeuse = $sum_difference;
            $p25_vendeuse = 0;
            $p75 = 0;

            $TL_COMPT_ADMIN_B = 0;
            $TL_COMPT_ASSISTANTS_B = 0;
            $TL_ROYAL_B = 0;
        } else {
            $p25_vendeuse = ($sum_difference - $SALAIRE_B+$sum_resolution)*$POURCENTAGE_B/100;
            $p75 = ($sum_difference - $SALAIRE_B+$sum_resolution)*$POURCENTAGE_B_TSINJO/100;
            $salaire_vendeuse = $SALAIRE_B;

            $TL_COMPT_ADMIN_B = ($sum_difference - $SALAIRE_B+$sum_resolution)*$POURCENTAGE_B_COMPT_ADMIN/100;
            $TL_COMPT_ASSISTANTS_B = ($sum_difference - $SALAIRE_B+$sum_resolution)*$POURCENTAGE_B_COMPT_ASSISTANTS/100;
            $TL_ROYAL_B = ($sum_difference - $SALAIRE_B+$sum_resolution)*$POURCENTAGE_B_ROYAL/100;
        }
        $TL_p75 = $p75 + $TL_p75;
        $p75_b = $p75;
        $p25_b = $p25_vendeuse;

        echo number_format(abs($salaire_vendeuse),0, "", " ");
        echo "<br>";
        echo number_format(abs($p25_vendeuse),0, "", " ");
        echo "<br>";
        echo '<big>'.number_format(abs($salaire_vendeuse + $p25_vendeuse),0, "", " ").'</big>';
        echo "<br>";
        echo number_format(abs($sum_depense_aparafa),0, "", " ");
        echo "<br>";
        echo number_format(($salaire_vendeuse + $p25_vendeuse - $sum_depense_aparafa),0, "", " ");
        ?></th>
    </tr>
    <tr class="tg-0laxa">
        <th colspan="4" style="text-align: right;"><?php
        
            echo 'TSINJO ROYAL '.(100-$POURCENTAGE_B)."% <br>HIKARAKARANA NY TSENA <br>HOAN'NY FITSABOANA<br> MPIKARAKARA ENTANA SY KAONTY";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(abs($p75),0, "", " ");
        ?></th>
    </tr>
        </tbody>
    </table>
    <!---||||||||||||||||||||||||||||||||||||||||RECAP||||||||||||||||||||||||||||||||||--->
    <br>
    <br>
    <br>
    <br>
    <table class="tg">
    
        <thead>
        <tr class="violet">
            <th style="text-align: center;" colspan="6">RECAPITULATION DU COMPTE SOALAZAINA-AMPARAFA-BEJOFO MOIS DE <?php echo strtoupper($mois) ; ?></th>
        </tr>
        <tr class="orange">
            <th>Date</th>
            <th style="text-align: right;">VERSEMENT</th>
            <th style="text-align: right;">TSY AMPY</th>
            <th style="text-align: right;">TOMBONY</th>
            <th style="text-align: right;">SALAIRE</th>
            <th style="text-align: right;">TSINJO ~75%</th>



        </tr>
        </thead>
        <tbody>
            <tr class="tg-0lax2a">
                <td style="text-align: left;"><b>AMPARAFA</b></td>
                <td style="text-align: right;"><b><?php echo number_format($Versmt_amparafa,0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php echo number_format(ABS($tsyampy_amparafa),0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php
                echo 'TOTAL : '.number_format($tombony_amparafa,0, "", " ").'<br> RESTE : '.number_format(($tombony_amparafa-$tsyampy_amparafa),0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php
                echo 'BASE : '.number_format($SALAIRE_A,0, "", " ").'<br>+ '.$POURCENTAGE_A.'% :'.number_format($p25_a,0, "", " ").'<br><big>TOTAL : '.number_format($p25_a+$SALAIRE_A,0, "", " ").'</big><br>NALAINY : '.number_format(ABS($nalain_amparafa),0, "", " ").'<br>RESTE : '.number_format($p25_a+$SALAIRE_A-$nalain_amparafa,0, "", " ");
                ?></b></td>
                <td style="text-align: right;"><b><?php echo number_format($p75_a,0, "", " ");?></b></td>
            </tr>
            <tr class="tg-0lax4b">
                <td style="text-align: left;"><b>SOALAZAINA</b></td>
                <td style="text-align: right;"><b><?php echo number_format($Versmt_soal,0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php echo number_format(ABS($tsyampy_soal),0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php
                echo 'TOTAL : '.number_format($tombony_soal,0, "", " ").'<br> RESTE : '.number_format(($tombony_soal-$tsyampy_soal),0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php
                echo 'BASE : '.number_format($SALAIRE_S,0, "", " ").'<br>+ '.$POURCENTAGE_S.'% :'.number_format($p25_s,0, "", " ").'<br><big>TOTAL : '.number_format($p25_s+$SALAIRE_S,0, "", " ").'</big><br>NALAINY : '.number_format(ABS($nalain_soal),0, "", " ").'<br>RESTE : '.number_format($p25_s+$SALAIRE_S-$nalain_soal,0, "", " ");
                ?></b></td>
                <td style="text-align: right;"><b><?php echo number_format($p75_s,0, "", " ");?></b></td>
            </tr>
            <tr class="tg-0lax2a">
                <td style="text-align: left;"><b>BEJOFO</b></td>
                <td style="text-align: right;"><b><?php echo number_format($Versmt_bejofo,0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php echo number_format(ABS($tsyampy_bejofo),0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php
                echo 'TOTAL : '.number_format($tombony_bejofo,0, "", " ").'<br> RESTE : '.number_format(($tombony_bejofo-$tsyampy_bejofo),0, "", " ");?></b></td>
                <td style="text-align: right;"><b><?php
                echo 'BASE : '.number_format($SALAIRE_B,0, "", " ").'<br>+ '.$POURCENTAGE_B.'% :'.number_format($p25_b,0, "", " ").'<br><big>TOTAL : '.number_format($p25_b+$SALAIRE_B,0, "", " ").'</big><br>NALAINY : '.number_format(ABS($nalain_bejofo),0, "", " ").'<br>RESTE : '.number_format($p25_b+$SALAIRE_B-$nalain_bejofo,0, "", " ");
                ?></b></td>
                <td style="text-align: right;"><b><?php echo number_format($p75_b,0, "", " ");?></b></td>
            </tr>
        <tr>
            <th>TOTAL</th>
            <th style="text-align: right;"><?php echo number_format(($Versmt_bejofo+$Versmt_amparafa+$Versmt_soal),0, "", " "); ?></th>
            <th style="text-align: right;">#######</th>
            <th style="text-align: right;">#######</th>
            <th style="text-align: right;">#######</th>
            <th style="text-align: right;"><?php echo number_format($TL_p75,0, "", " "); ?></th>
            <th></th>
            <th></th>
        </tr>
    <tr class="tg-0laxa">
        <th colspan="5" style="text-align: right;"><?php
            echo "TOTAL TSINJO 75%";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format(abs($TL_p75),0, "", " ");
        ?></th>
    </tr>
    <tr class="tg-0laxa">
        <th colspan="5" style="text-align: right;"><?php
        
            echo "ASSISTANTS COMPTABLE 22%<br>";
            echo "ADMINISTRATEUR COMPTABLE 19%<br>";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format($TL_COMPT_ASSISTANTS_B+$TL_COMPT_ASSISTANTS_S+$TL_COMPT_ASSISTANTS_A,0, "", " ");
        echo "<br>";
        echo number_format($TL_COMPT_ADMIN_B+$TL_COMPT_ADMIN_S+$TL_COMPT_ADMIN_A,0, "", " ");
        ?></th>
    </tr>
    <tr class="tg-0laxa">
        <th colspan="5" style="text-align: right;"><?php
        
            echo "VOLA MIPETRAKA HOAN'NY FITSABOANA - HOAN'NY ANKIZY MPANOMANA ENTANA - FANDANIANA NY ASA ROYAL 34%";
        ?></th>
        <th style="text-align: right;"><?php
        echo number_format($TL_ROYAL_B+$TL_ROYAL_S+$TL_ROYAL_A,0, "", " ");
        ?></th>
    </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
			</div>			
		</div>
	</div>
	<!-------------------------->



<!--FIN du collapsible----->
<!--------------END OF THE CODE TO REMOVED-------->
<!--------------LISTE DU STOCK EN COURS----------->
</div>

<!-------------------------------------->
<!--Javascript--->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>