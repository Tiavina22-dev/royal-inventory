<!DOCTYPE html>
<html>
<head>
    <title>MVT SMPL</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css">
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
 <h2 class="text-center text-warning">MVT SMPL</h2>
</body>
<!--------------------------------------->
<form role="form" action="simple_search_mvt_s.php" method="POST">
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
    $S10 ="";
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
    if ($point_de_vente == "Ambato_Pneu") {$S10 = "selected";}
?>
<br>
	<div>
		<select class="btn btn-success" name="point_de_vente">
		<option class="btn-warning" value="Tous" <?php echo $S1;?>>Tous les Points de Vente</option>
		<option value="Ambaibo_Tole" <?php echo $S3;?>>Ambaibo TOLE</option>
	    <option class="btn-warning" value="Amparafa" <?php echo $S5;?>>AMPARAFA</option>
	    <option value="Ambato_Tantely" <?php echo $S6;?>>AMBATO Tantely</option>
	    <option class="btn-warning" value="Ambato_veve_photo" <?php echo $S7;?>>VEVE</option>
	    <option value="Bejofo" <?php echo $S8;?>>BEJOFO</option>
	    <option class="btn-warning" value="Soalazaina" <?php echo $S9;?>>SOALAZAINA</option>
        <option value="Ambato_Pneu" <?php echo $S10;?>>AMBATO MIANGALY</option>
		</select>
	</div>
<br>
<input type="search" class="btn btn-light text-left" value="<?php echo $_COOKIE['key_word']?>" name="key_word" placeholder="Name/Ref/id_x">
<?php
}else{
?>
<br>
	<div>
		<select class="btn btn-success" name="point_de_vente">
		<option class="btn-warning" value="Tous" selected>Tous les Points de Vente</option>
		<option value="Ambaibo_Tole">Ambaibo TOLE</option>
	    <option class="btn-warning" value="Amparafa">AMPARAFA</option>
	    <option value="Ambato_Tantely">AMBATO Tantely</option>
	    <option class="btn-warning" value="Ambato_veve_photo">VEVE</option>
	    <option value="Bejofo">BEJOFO</option>
	    <option class="btn-warning" value="Soalazaina">SOALAZAINA</option>
        <option value="Ambato_Pneu">AMBATO MIANGALY</option>
		</select>
	</div>
<br>
<input type="text" class="btn btn-warning text-left" name="key_word" placeholder="Name/Ref/id_x">
<?php
}
?>
<button type="submit" class="btn btn-info">Search</button>
</div>
<br>
<div class="flex-center flex-column text-danger">
	<table class="table-borderless table-sm">
	<tr>
		<td class="text-left">
		    <input type="checkbox" name="checkbox" checked>
		    <label>Resultat Limité</label>
    	</td>
    </tr>
    <tr>
    	<td class="text-left">
    		<input type="checkbox" name="checkbox_stock_list">
		    <label>Ancien Stock et Vente</label>
    	</td>
    </tr>
    </table>
</div>
</form>
<?php
include('connect.php');
    $key_word = "";
    $status = 'OFF';
   	if (isset($_COOKIE['checkbox_stock_list'])){$status = 'UNDIFENED';}

if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //-----------------SEARCH TIPS--------------------------------------------------
    $key_word0 = str_replace(array('-','/','_'), ' ', $key_word);
    $key_word1 = strtok($key_word0," ");
    $key_word2 = explode(' ',str_replace(array('-','/'), ' ', $key_word0));
    $key_word2 = end($key_word2);
    //------------
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
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><span style='font-weight: bold'>"."[".$key_word."]"." does not exist on the base anymore, Click <a href='stock_epuise.php'>RETOURS</a></span><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap btn-brown" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th></th>
        <th></th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>PRIX</th>
        <th>QT</th>
        <th></th>
        <th></th>
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
     /*
   $query_prix = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL";
    $qrp = $bdd->prepare($query_prix);

    $qrp->execute(array($donnees['id_x']));
    $pu_list='';
    $pu_list="<span style='font-weight: bold'># ".($donnees['prix_de_vente']+0)." : PU General</span>"."<br>"."# ".($donnees['pu_aparafa']+0)." : PU Amparafa"."<br>"."<span style='font-weight: bold'># ".($donnees['pu_ambato_tantely']+0)." : PU Ambato</span>"."<br>";
    $k = 0;
    while ($donnees1 = $qrp -> fetch())
    {
        $k = $k+1;
        if ($k < 5) {   
    $pu_list = $pu_list."# ".$donnees1['prix_unitaire']." : ".$donnees1['nom_client_fournisseur']." (".$donnees1['type_de_mvt'].")"."<br>";
    }
    }
    $qrp->closeCursor();
    */
    //=====================HANDLE PU LIST=========================
    $pu_list='---------<br>';
    $query_shop = "SELECT DISTINCT(nom_client_fournisseur) as nf FROM mvt WHERE id_x = ? ORDER BY nom_client_fournisseur";
    $query_shop = $bdd->prepare($query_shop);
    $query_shop->execute(array($donnees['id_x']));
    while ($donnees_qsh = $query_shop -> fetch())
    {
    	$query_prix = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'stock' ORDER BY Date_du_Journal_mvt DESC LIMIT 2";
    	$query_prix = $bdd->prepare($query_prix);

    	$query_prix->execute(array($donnees['id_x'],$donnees_qsh['nf']));
    	//$pu_list = $pu_list.$donnees_qsh['nf'];
    	while ($donnees_pu = $query_prix -> fetch())
    	{
    		$date = DateTime::createFromFormat('Y-m-d', $donnees_pu['Date_du_Journal_mvt']);
        	$daty = $date -> format('l d M Y');
    		$pu_list = $pu_list."# <span style='font-weight: bold'><span class='text-danger'>".number_format($donnees_pu['prix_unitaire'],0, "", " ")."</span>"." - <span class='text-warning'>".number_format($donnees_pu['prix_client'],0, "", " ")."</span></span> : ".$donnees_pu['nom_client_fournisseur']." : <span style='font-weight: bold'>".$daty."</span><br>";
    	}
    	$pu_list = $pu_list.'---------<br>';
    	$query_prix->closeCursor();
    	
    }
    $query_shop->closeCursor();
    //Adding Prix Fournisseur in pu_list
        $pu_list = "---------<br># <span class='text-primary'><span style='font-weight: bold'>".number_format(($donnees['prix_fournisseur']+0),0, "", " ")."</span></span> : Prix Fournisseur - Reference | <a href = 'simple_search_prix.php?key_word=".$donnees['reference_x']."' target='_blank' rel='noopener noreferrer'><span class='text-primary'>Modifier?</span></a><br>".$pu_list;

    //=======================END PU LIST=======================
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
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("no_replace".$j); ?>"><img src="img/replace.png" height="30" width="30" background alt="Edit" /></a></td>
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $pu_list; ?></td>
        <td><?php echo number_format($qt_dispo,2); ?></td>
        <td class="text-center"><a class="center" href="simple_search_mvt.php?id_x=<?php echo $donnees['id_x']; ?>&shop=<?php echo $point_de_vente; ?>"><img src="img/char.png" height="50" width="50" background alt="Edit" /></a></td>
        <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("no".$j); ?>"><img src="img/liste.png" height="30" width="30" background alt="Edit" /></a></td>
    </tr>
        <!-- modal form QUATITE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content btn-brown">
                    <div class="modal-header btn-amber">
                    <h4 class="modal-title"><span style='font-weight: bold' class="text-dark"><?php echo $donnees['reference_x']; ?></span> <span style='font-weight: bold'> <?php echo $donnees['nom_x']; ?></span></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body text-primary">
                    <!-- actual form -->
                    <form role="form">
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-secondary">STOCK</span></label>
                        <ul class="list-group">
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
                          	<span class="text-danger" > INVENTAIRE GENERAL du <?php echo $donnees1['Date_du_Journal_mvt']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $qt_GI; ?></span>
                          </li>
                            	<?php
                            }//IF Latest
                            } else //IF OFF
                            {
                            	?>
                            	<li class="list-group-item">
                          	<span class="text-danger" > INVENTAIRE GENERAL du <?php echo $donnees1['Date_du_Journal_mvt']; ?></span><span class="w3-badge w3-right w3-margin-right <?php echo $badge_color; ?>"><?php echo $aff_qt_GI; ?></span>
                          </li>
                            	<?php
                            }
                        	} else //ELSE OF IF ($donnees1['status']=='General_Inventory') 
                        	{
                        	$total_stock = $total_stock + $donnees1['qt'];

                            ?>
                          <li class="list-group-item <?php echo $text_color; ?>"><?php echo $donnees1['description_date']; ?><?php
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
                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($Sous_total,2).'</span><br>'; 
                            }
                            $qpv->closeCursor();
                            ?>
                        </li>
                        </ul>
                        </div>
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-success">VENTE</span></label>
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
                          <li class="list-group-item <?php echo $text_color; ?>"><?php echo $donnees2['description_date']; ?><?php
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
                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv2->closeCursor();
                            ?>
                            </span>
                            </li>
                            <li class="list-group-item"><span style='font-weight: bold' class="text-danger">TOTAL VENTE
                        <span class="w3-badge w3-right w3-margin-right"><?php echo number_format(ABS($total_vente),2); ?></span></span></li>
                        </ul>
                        </div>
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-primary">RESTE</span></label>
                        <ul class="list-group">
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
                            
                            echo "<li class='list-group-item'><span style='font-weight: bold' class='text-dark'>RESTE ".$data['nom_client_fournisseur'].' (TL Stock : '.$stock.' | TL Vente : '.$vente.') <span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2).'</span></span></li>';
                            $qsm->closeCursor();
                            }
                            $qpv3->closeCursor();
                            ?>
                        </ul>
                        </div>
                        </div>
                    </form>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
<!-- modal form FIND AND REPLACE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no_replace".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><span style='font-weight: bold' class="text-primary">AUTOMATIQUE REMPLACEMENT | <span class="text-danger">HAMARINO TSARA!!!</span></span></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form" action="modifier_mvt_vente_find_replace.php" method="post">
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-danger">PRODUIT:</span> <?php echo ($donnees['nom_x']); ?></label>
                        </div>
                        <div class="form-group">
                        <label><span style='font-weight: bold' class="text-danger">POINT DE VENTE:</span> <?php echo ($point_de_vente); ?></label>
                        </div>
                        <div class="form-group">
                        <label><span style='font-weight: bold'>REMPLACER PAR (REFERENCE EN MAJUSCULE)</span></label>
                        <p class="text-secondary float-right" id="<?php echo $text_id1; ?>"></p>
                        </div>
                        <div class="form-group">
						<input class="form-control" name="reference_x" value="<?php echo htmlspecialchars($donnees['reference_x']); ?>" type="text" id="<?php echo $ref_id; ?>" oninput="referenceFunction($(this));">
						<p class="text-secondary float-right" id="<?php echo $text_id2; ?>"></p>
						</div>
						<input type="hidden" name="actual_id" value="<?php echo $donnees['id_x']; ?>">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $point_de_vente; ?>">
						<div class="form-group">
							<button type="submit" class="btn btn-danger" onclick="confirmationDelete('Are You Sure?');return false; post ;" id=<?php echo ($j.'B'); ?> disabled>REMPLACER</button>
							<input class="btn btn-light" type="password" oninput="unlock($(this));">
						</div>
                        </div>
                    </form>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
<!-------FORM DE MODIFIER IMAGE-------->
    <!-- modal form MODIFIER IMAGE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "img".$donnees['id_x']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Produit No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form">
            <div class="form-group text-center">
              <img src="<?php echo $image_path_x;?>" height=100% width=100% align="middle"/>
            </div>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
    <br>
    <?php
    if (isset($_COOKIE['analyse'])) 
    {
        
        ?>
        <h2 class="text-center"><span style='font-weight: bold'><?php echo $produit.' | '.$point_de_vente ?></span></h2>
        <?php
        include('char_r3.php');
    }
    ?>
    <br>
    <br>
    <br>
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