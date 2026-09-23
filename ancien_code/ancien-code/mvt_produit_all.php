<!DOCTYPE html>
<html>
<head>
    <title>Mouvement Simple</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/w3.css">
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
</body>
<!--------------------------------------->
<form role="form" action="simple_search_mvt_all.php" method="POST">
<div class="text-center">
<?php  
if (isset($_COOKIE['key_word'])) 
{
	$point_de_vente = "";
	$S1 ="";
	$S2 ="";
	$S3 ="";
	$S4 ="";
	$S5 ="";
	$S6 ="";
	$S7 ="";
	$S8 ="";
	$S9 ="";
	if (isset($_COOKIE['point_de_vente'])) 
	{$point_de_vente = $_COOKIE['point_de_vente'];}
	if ($point_de_vente == "Tous") {$S1 = "selected";}
	if ($point_de_vente == "Ambaibo_Electronique") {$S2 = "selected";}
	if ($point_de_vente == "Ambaibo_Tole") {$S3 = "selected";}
	if ($point_de_vente == "Ambaibo_loko") {$S4 = "selected";}
	if ($point_de_vente == "Amparafa") {$S5 = "selected";}
	if ($point_de_vente == "Ambato_Tantely") {$S6 = "selected";}
	if ($point_de_vente == "Ambato_veve_photo") {$S7 = "selected";}
	if ($point_de_vente == "Bejofo") {$S8 = "selected";}
	if ($point_de_vente == "Soalazaina") {$S9 = "selected";}
?>
<br>
<br>
<br>
	<div>
		<select class="btn btn-secondary" name="point_de_vente">
		<option value="Tous" <?php echo $S1;?>>Tous les Points de Vente</option>
		<option value="Ambaibo_Electronique" <?php echo $S2;?>>ELECTRONIQUE</option>
		<option value="Ambaibo_Tole" <?php echo $S3;?>>Ambaibo TOLE</option>
	    <option value="Ambaibo_loko" <?php echo $S4;?>>Ambaibo LOKO</option>
	    <option value="Amparafa" <?php echo $S5;?>>AMPARAFA</option>
	    <option value="Ambato_Tantely" <?php echo $S6;?>>AMBATO Tantely</option>
	    <option value="Ambato_veve_photo" <?php echo $S7;?>>VEVE</option>
	    <option value="Bejofo" <?php echo $S8;?>>BEJOFO</option>
	    <option value="Soalazaina" <?php echo $S9;?>>SOALAZAINA</option>
		</select>
	</div>
<br>
<input type="search" class="light-table-filter" value="<?php echo $_COOKIE['key_word']?>" name="key_word" placeholder="Name/Ref/id_x">
<?php
}else{
?>
<br>
<br>
<br>
	<div>
		<select class="btn btn-secondary" name="point_de_vente">
		<option value="Tous" selected>Tous les Points de Vente</option>
		<option value="Ambaibo_Electronique">ELECTRONIQUE</option>
		<option value="Ambaibo_Tole">Ambaibo TOLE</option>
	    <option value="Ambaibo_loko">Ambaibo LOKO</option>
	    <option value="Amparafa">AMPARAFA</option>
	    <option value="Ambato_Tantely">AMBATO Tantely</option>
	    <option value="Ambato_veve_photo">VEVE</option>
	    <option value="Bejofo">BEJOFO</option>
	    <option value="Soalazaina">SOALAZAINA</option>
		</select>
	</div>
<br>
<input type="text" class="btn btn-warning text-left" name="key_word" placeholder="Name/Ref/id_x">
<?php
}
?>
<button type="submit" class="btn btn-info">Search</button>
</div>
</form>
<?php
include('connect.php');
    $key_word = "";
    $status = 'OFF';
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
			   //------QUERY SPECIAL TO GET ALL REFERENCE-----------
				$query_num_stock = $bdd->query('SELECT reference_x FROM produit');
				$u = 0;
				while ($reference_x = $query_num_stock -> fetch())
				{
				$u = $u + 1;
				$ref[$u] = $reference_x['reference_x'];
				}
				$query_num_stock ->closeCursor();
				//--------------------------------------------------

   if (isset($_COOKIE['point_de_vente'])) 
  {$point_de_vente = $_COOKIE['point_de_vente'];}
if ($point_de_vente == "Tous") {
  //TOUS LES POINTS DE VENTE
    //Query tous listeproduct
    if (isset($_COOKIE['checkbox'])) 
    {
        //More Result
        //echo "MORE RESULT";
   $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE mvt.status != ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ? OR nom_x LIKE ? OR nom_x LIKE ? OR id_x LIKE ? OR reference_x LIKE ?);";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($status,"%".$key_word."%" ,"%".$key_word1."%","%".$key_word2."%" , "%".$key_word."%","%".$key_word."%"));
    } else {
        //echo "LIMITED RESULT";
        //Limited result
        $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x,mvt.status, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE mvt.status != ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?);";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($status,"%".$key_word."%", "%".$key_word."%","%".$key_word."%"));
    }
    } else {
    //Query for specifique point de vente
    if (isset($_COOKIE['checkbox'])) 
    {
        //echo "MORE RESULT";
        //More Result
   $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND mvt.status != ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ? OR nom_x LIKE ? OR nom_x LIKE ? OR id_x LIKE ? OR reference_x LIKE ?);";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($point_de_vente,$status,"%".$key_word."%" ,"%".$key_word1."%","%".$key_word2."%", "%".$key_word."%","%".$key_word."%"));
    } else {
        //echo "LIMITED RESULT";
        //Limited result
        $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND mvt.status != ? GROUP BY id_x) as resultante_table WHERE ((nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?));";
        $q = $bdd->prepare($query_stock_negatif);

        $q->execute(array($point_de_vente,$status,"%".$key_word."%", "%".$key_word."%","%".$key_word."%"));
    }
}
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<br>
<br>
<h4 class="text-center text-light">MOUVEMENT DES PRODUITS</h4>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><span style='font-weight: bold'>"."[".$key_word."]"." does not exist on the base, Click <a href='stock_epuise.php'>RETOURS</span><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div class="text-center">
		<b>Result for [<?php echo $key_word; ?>]</b>
	</div>
   <table id="example2" class="table table-striped table-bordered dt-responsive nowrap btn-dark" style="width:100%">
        <thead>
        <tr class="text-warning">
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th class="text-right">PU</th>
        <th>STOCK</th>
        <th>VENTE</th>
        <th class="text-right">RESTE</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
$image_path_x = "img_x/default_x.png";

while ($donnees = $q -> fetch())
{
//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }
//QUERY TO GET LISTE PRIX DE VENTE
   $query_prix = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL";
    $qrp = $bdd->prepare($query_prix);

    $qrp->execute(array($donnees['id_x']));
    $pu_list='';
    $pu_list="<b># ".($donnees['prix_de_vente']+0)." : PU General</b>"."<br>"."# ".($donnees['pu_aparafa']+0)." : PU Amparafa"."<br>"."<b># ".($donnees['pu_ambato_tantely']+0)." : PU Ambato</b>"."<br>";
    $k = 0;
    while ($donnees1 = $qrp -> fetch())
    {
        $k = $k+1;
        if ($k < 5) {   
    $pu_list = $pu_list."# ".$donnees1['prix_unitaire']." : ".$donnees1['nom_client_fournisseur']." (".$donnees1['type_de_mvt'].")"."<br>";
    }
    }
    $qrp->closeCursor();
if ($point_de_vente =="Tous") {
  //------------------------------
  //QUERY TO SHOW STOCK
     $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND status != ? ORDER BY Date_du_Journal_mvt";
      $qs = $bdd->prepare($query_stock);
      $qs->execute(array($donnees['id_x'],$status));
  //QUERY to get each point de vente
      $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND status != ? ";
      $qpv = $bdd->prepare($query_stock_pv);
      $qpv->execute(array($donnees['id_x'],$status));
  //QUERY to get each point de vente
      $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND status != ? ";
      $qpv3 = $bdd->prepare($query_stock_pv);
      $qpv3->execute(array($donnees['id_x'],$status));
  //----------------------------------------------------
  //QUERY TO SHOW VENTE
     $query_stock_vente = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND status != ? ORDER BY Date_du_Journal_mvt";
      $qv = $bdd->prepare($query_stock_vente);

      $qv->execute(array($donnees['id_x'],$status));
      //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
      //QUERY to get each point de vente
      $query_stock_pv2 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND status != ?";
      $qpv2 = $bdd->prepare($query_stock_pv2);
      $qpv2->execute(array($donnees['id_x'],$status));
    //----------------------------------------------------
            //----------------------------------------------------
      //QT DISPO
      //QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x']));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x']));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x']));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //----------------------
                            $qt_dispo = $vente + $stock + $stock_GI;
    //---------------------
} else {
  //---------------SPECIFIC POINT DE VENTE---------------
  //QUERY TO SHOW STOCK
     $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ? ORDER BY Date_du_Journal_mvt";
      $qs = $bdd->prepare($query_stock);
      $qs->execute(array($donnees['id_x'],$point_de_vente,$status));
  //QUERY to get each point de vente
      $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ?";
      $qpv = $bdd->prepare($query_stock_pv);
      $qpv->execute(array($donnees['id_x'],$point_de_vente,$status));
  //QUERY to get each point de vente
      $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ?";
      $qpv3 = $bdd->prepare($query_stock_pv);
      $qpv3->execute(array($donnees['id_x'],$point_de_vente,$status));
  //----------------------------------------------------
  //QUERY TO SHOW VENTE
     $query_stock_vente = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != ? ORDER BY Date_du_Journal_mvt";
      $qv = $bdd->prepare($query_stock_vente);

      $qv->execute(array($donnees['id_x'],$point_de_vente,$status));
      //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
      //QUERY to get each point de vente
      $query_stock_pv2 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != ?";
      $qpv2 = $bdd->prepare($query_stock_pv2);
      $qpv2->execute(array($donnees['id_x'],$point_de_vente,$status));
      //----------------------------------------------------
      //QT DISPO
      //QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$point_de_vente));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$point_de_vente));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$point_de_vente));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //----------------------
                            $qt_dispo = $vente + $stock + $stock_GI;
    //---------------------
}
    //-------------------------------------
    $ref_id = $j;
    $text_id1 = 'text_id1'.$j;
    $text_id2 = 'text_id2'.$j;
        $produit = $donnees['nom_x'];
		//---------PU-------------
	$prix = number_format($donnees['prix_de_vente'],0, "", " ");
    if ($point_de_vente=='Amparafa') {$prix = number_format($donnees['pu_aparafa'],0, "", " ");}
    if ($point_de_vente=='Ambato_Tantely') {$prix = number_format($donnees['pu_ambato_tantely'],0, "", " ");}
    if ($point_de_vente=='Soalazaina') {$prix = number_format($donnees['pu_soalazaina'],0, "", " ");}
    if ($prix == 0) {
    	$prix = '####';
    }
    if ($point_de_vente == "Tous") {
    	?>
    	<tr>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-right"><?php echo $prix; ?></td>
        <td>
            <ul class="group">
                        <?php
                        $total_stock = 0;
                        $latest_GI = '2020-01-01';
                        $commune = 'NO';
                        while ($donnees1 = $qs -> fetch())
                        { 
                            
                            //HANDLE OFF STATUS COLOR
                            $badge_color = 'w3-orange';
                            $text_color = '';
                            if ($donnees1['status']=='OFF') {
                              $badge_color = 'w3-grey';
                              $text_color = 'text-light';
                            }
                            //HANDLE GI

                            if ($donnees1['status']=='General_Inventory') {

                            //Handle qt Difference commune
                            if ($commune == $donnees1['Date_du_Journal_mvt']) {
                                 $qt_GI = 0;
                                 $aff_qt_GI = 'C';
                            } else {
                                
                                $qt_GI = $donnees1['prix_aparafa'];
                                $aff_qt_GI = $qt_GI ;
                            }
                           
                            
                            $commune = $donnees1['Date_du_Journal_mvt'];

                            if ($status == 'OFF') {
                              
                              #GET LATEST Date_du_Journal_mvt
                              $query_ld = "SELECT MAX(Date_du_Journal_mvt) as latest_date FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory'";
                              $qld = $bdd->prepare($query_ld);
                              $qld->execute(array($donnees1['id_x'],$donnees1['nom_client_fournisseur']));
                              $data1 = $qld -> fetch();
                              $latest_GI = $data1['latest_date'];
                              $qld->closeCursor();
                              if (strlen($latest_GI) == 0) {
                                
                              }
                              #--------------------------------
                              $qt_GI = 0;
                              if ($donnees1['Date_du_Journal_mvt'] == $latest_GI) {
                                $qt_GI = $donnees1['qt'];

                              }

                            }
                            //echo $qt_GI;
                            $total_stock = $total_stock + $qt_GI;

                            if ($status == 'OFF') {
                              if ($donnees1['Date_du_Journal_mvt'] == $latest_GI) {
                               if ($qt_GI == 0) {
                                  # code...
                                } else {
                                 
                              ?>
                            <li class="list-group-item">
                            <span class="text-danger" > <?php echo $donnees1['nom_client_fournisseur']; ?> | INVENTAIRE du <?php echo $donnees1['Date_du_Journal_mvt']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $qt_GI; ?></span>
                          </li>
                              <?php
                            }
                            }//IF Latest
                            } else //IF OFF
                            {
                              if ($aff_qt_GI == 0) {
                                # code...
                              } else {

                              ?>
                              <li class="list-group-item">
                            <span class="text-danger" ><?php echo $donnees1['nom_client_fournisseur']; ?> | INVENTAIRE du <?php echo $donnees1['Date_du_Journal_mvt']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $aff_qt_GI; ?></span>
                          </li>
                              <?php
                            }
                            }
                          } else //ELSE OF IF ($donnees1['status']=='General_Inventory') 
                          {
                          $total_stock = $total_stock + $donnees1['qt'];

                            ?>
                          <li class="list-group-item text-primary"><?php echo $donnees1['nom_client_fournisseur']; ?> | <?php echo $donnees1['description_date']; ?><?php
                          if ($donnees1['id_x'] == 134655) {
                            echo $donnees1['note'];
                          }
                         ?> <span style='font-weight: bold' class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                        }
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item"><span style='font-weight: bold' class="text-success">
                            <?php 
                             while ($data = $qpv -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ? AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            $Sous_total = $data1['sm'] + 0;
                            $qsm->closeCursor();

                            //FOR GI ONLY
                            $query = "SELECT SUM(prix_aparafa) as sm FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            $Sous_total_GI = $data1['sm']+0;
                            $qsm->closeCursor();
                            if ($status == 'OFF') {
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' AND Date_du_Journal_mvt = ? ";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$latest_GI));
                            $data1 = $qsm -> fetch();
                            $Sous_total_GI = $data1['sm']+0;
                            $qsm->closeCursor();
                            }

                            $Sous_total = $Sous_total_GI + $Sous_total;
                  
                            //-----------
                            echo 'TOTAL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($Sous_total,2).'</span><br>'; 
                          
                            }
                            $qpv->closeCursor();
                            ?>
                        </li>
                        </ul>
                        </div>
                        <td>
                        <div class="form-group">
                        <ul class="list-group">
                        <?php
                        $total_vente = 0;
                        while ($donnees2 = $qv -> fetch())
                        { 
                            $total_vente = $total_vente + $donnees2['qt'];
                            //HANDLE OFF STATUS COLOR
                            $badge_color = 'w3-green';
                            $text_color = '';
                            if ($donnees2['status']=='OFF') {
                              $badge_color = 'w3-grey';
                              $text_color = 'text-light';
                            }

                            ?>
                          <li class="list-group-item text-primary"><?php echo $donnees2['nom_client_fournisseur']; ?> | <?php echo $donnees2['description_date']; ?><?php
                          if ($donnees2['id_x'] == 134655) {
                            echo $donnees2['note'];
                          }
                           ?><span style='font-weight: bold' class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo ABS($donnees2['qt']); ?></span></li>
                        <?php
                        }
                        $qv->closeCursor();
                        ?>
                        <li class="list-group-item"><span style='font-weight: bold' class="text-success">
                            <?php 
                             while ($data = $qpv2 -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            echo 'TOTAL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv2->closeCursor();
                            ?>
                            </span>
                            </li>

                        </ul>
                        </div>
                        </td>
                        <td>
                        <div>
                        <ul>
                            <?php 
                             while ($data = $qpv3 -> fetch()) {
                            //QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ? AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            if ($status == 'OFF') {
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' AND Date_du_Journal_mvt = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$latest_GI));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //---------------------
                            }
                            
                            $stock = $stock + $stock_GI;
                            
                            echo "<li class='list-group-item'><span style='font-weight: bold' class='text-dark'> ".$data['nom_client_fournisseur'].' <span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2).'</span></span></li>';
                            $qsm->closeCursor();
                            } 
                            ?>
                          </ul>
                        </div>
                        </td>
    					</tr>
    					<?php
    					# code...
    } else {
		?>
    	<tr>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-right"><?php echo $prix; ?></td>
        <td>
            <ul class="group">
                        <?php
                        $total_stock = 0;
                        $latest_GI = '2020-01-01';
                        $commune = 'NO';
                        while ($donnees1 = $qs -> fetch())
                        { 
                            
                            //HANDLE OFF STATUS COLOR
                            $badge_color = 'w3-orange';
                            $text_color = '';
                            if ($donnees1['status']=='OFF') {
                              $badge_color = 'w3-grey';
                              $text_color = 'text-light';
                            }
                            //HANDLE GI

                            if ($donnees1['status']=='General_Inventory') {

                            //Handle qt Difference commune
                            if ($commune == $donnees1['Date_du_Journal_mvt']) {
                                 $qt_GI = 0;
                                 $aff_qt_GI = 'C';
                            } else {
                                
                                $qt_GI = $donnees1['prix_aparafa'];
                                $aff_qt_GI = $qt_GI ;
                            }
                           
                            
                            $commune = $donnees1['Date_du_Journal_mvt'];

                            if ($status == 'OFF') {
                              
                              #GET LATEST Date_du_Journal_mvt
                              $query_ld = "SELECT MAX(Date_du_Journal_mvt) as latest_date FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory'";
                              $qld = $bdd->prepare($query_ld);
                              $qld->execute(array($donnees1['id_x'],$donnees1['nom_client_fournisseur']));
                              $data1 = $qld -> fetch();
                              $latest_GI = $data1['latest_date'];
                              $qld->closeCursor();
                              if (strlen($latest_GI) == 0) {
                                
                              }
                              #--------------------------------
                              $qt_GI = 0;
                              if ($donnees1['Date_du_Journal_mvt'] == $latest_GI) {
                                $qt_GI = $donnees1['qt'];

                              }

                            }
                            //echo $qt_GI;
                            $total_stock = $total_stock + $qt_GI;

                            if ($status == 'OFF') {
                              if ($donnees1['Date_du_Journal_mvt'] == $latest_GI) {
                              ?>
                            <li class="list-group-item">
                            <span class="text-danger" > INVENTAIRE du <?php echo $donnees1['Date_du_Journal_mvt']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $qt_GI; ?></span>
                          </li>
                              <?php
                            }//IF Latest
                            } else //IF OFF
                            {
                              ?>
                              <li class="list-group-item">
                            <span class="text-danger" > INVENTAIRE du <?php echo $donnees1['Date_du_Journal_mvt']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $aff_qt_GI; ?></span>
                          </li>
                              <?php
                            }
                          } else //ELSE OF IF ($donnees1['status']=='General_Inventory') 
                          {
                          $total_stock = $total_stock + $donnees1['qt'];

                            ?>
                          <li class="list-group-item text-primary"><?php echo $donnees1['description_date']; ?><?php
                          if ($donnees1['id_x'] == 134655) {
                            echo $donnees1['note'];
                          }
                         ?> <span style='font-weight: bold' class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                        }
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item"><span style='font-weight: bold' class="text-success">
                            <?php 
                             while ($data = $qpv -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ? AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            $Sous_total = $data1['sm'] + 0;
                            $qsm->closeCursor();

                            //FOR GI ONLY
                            $query = "SELECT SUM(prix_aparafa) as sm FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            $Sous_total_GI = $data1['sm']+0;
                            $qsm->closeCursor();
                            if ($status == 'OFF') {
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' AND Date_du_Journal_mvt = ? ";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$latest_GI));
                            $data1 = $qsm -> fetch();
                            $Sous_total_GI = $data1['sm']+0;
                            $qsm->closeCursor();
                            }

                            $Sous_total = $Sous_total_GI + $Sous_total;
                            //-----------
                            echo 'TOTAL '.'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($Sous_total,2).'</span><br>'; 
                            }
                            $qpv->closeCursor();
                            ?>
                        </li>
                        </ul>
                        </div>
                        <td>
                        <div class="form-group">
                        <ul class="list-group">
                        <?php
                        $total_vente = 0;
                        while ($donnees2 = $qv -> fetch())
                        { 
                            $total_vente = $total_vente + $donnees2['qt'];
                            //HANDLE OFF STATUS COLOR
                            $badge_color = 'w3-green';
                            $text_color = '';
                            if ($donnees2['status']=='OFF') {
                              $badge_color = 'w3-grey';
                              $text_color = 'text-light';
                            }

                            ?>
                          <li class="list-group-item text-primary"><?php echo $donnees2['description_date']; ?><?php
                          if ($donnees2['id_x'] == 134655) {
                            echo $donnees2['note'];
                          }
                           ?><span style='font-weight: bold' class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo ABS($donnees2['qt']); ?></span></li>
                        <?php
                        }
                        $qv->closeCursor();
                        ?>
                        <li class="list-group-item"><span style='font-weight: bold' class="text-success">
                            <?php 
                             while ($data = $qpv2 -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            echo 'TOTAL '.'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv2->closeCursor();
                            ?>
                            </span>
                            </li>

                        </ul>
                        </div>
                        </td>
                        <td>
                        <div>
                        <ul>
                            <?php 
                             while ($data = $qpv3 -> fetch()) {
                            //QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND status != ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v']+0;
                            $qsm->closeCursor();

                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status != ? AND status NOT LIKE 'General_Inventory'";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$status));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            
                            //GI ONLY--------------
                            $query = "SELECT SUM(prix_aparafa) as sm_s FROM (SELECT prix_aparafa FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' GROUP BY Date_du_Journal_mvt) X";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            if ($status == 'OFF') {
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND status LIKE 'General_Inventory' AND Date_du_Journal_mvt = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur'],$latest_GI));
                            $data1 = $qsm -> fetch();
                            $stock_GI = $data1['sm_s']+0;
                            $qsm->closeCursor();
                            //---------------------
                            }
                            
                            $stock = $stock + $stock_GI;
                            
                            echo "<span style='font-weight: bold'>".' <span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2).'</span></span>';
                            $qsm->closeCursor();
                            } 
                            ?>
                          </ul>
                        </div>
                        </td>
                      </tr>
                      <?php
    	# code...
    }
    }
     ?>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
 <?php
    }
 $q->closeCursor();

 }
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script>
function referenceFunction(e)
{
     //alert("Search suggestions can come here!!");
     var u = <?php echo json_encode($u); ?>;
     //var y = $(this).val();
     //var val = e.target.value;
     //alert(e.val());
     //alert(e.attr('id'));
     //e.style.borderColor = "red";
     var jArray = [];
     jArray = <?php echo json_encode($ref); ?>;
     var msg = "";
     var color;
     var input = e.attr('id');
     var y = e.val();
     var text_id1 = 'text_id1'+input;
     var text_id2 = 'text_id2'+input;
     //alert(text_id1);
  	document.getElementById(input).style.borderColor = "green";
      for(var i=1; i<=u; i++)
      { //alert(jArray[i]);

      	if (jArray[i]==y) {
      		msg="Mety tsara";
      	} else {
      		//alert("nook");
      		//color = "green";
      	}
      	
      }
      //write notification on div id=warning_msg ;
      if (msg=="Mety tsara") {
      	color = "green";
      } else {
      	color = "red";
      	msg="Reference efa misy ampiasaina";
      }
     $("#"+text_id1).text(msg);
     $("#"+text_id2).text(msg);
     document.getElementById(input).style.borderColor = color;
}
</script>
<script>
function unlock(e){
	var ib = <?php echo json_encode($j); ?>;
	var password =  e.val();
	//alert(ib);
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2021") {
    	for (var i = 1; i < ib; i++) {
    	//alert(i+'B');
    	$("#"+i+'B').removeAttr("disabled");
    	//$("#"+i+"L").removeAttr("style");
    	}
	}
}
</script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
<script  src="js/confirmation.js"></script>
</html>