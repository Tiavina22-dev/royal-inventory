<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Top 20</title>
  <link rel="stylesheet" href="css/top20.css">
</head>
<body>
<br>
<br>
<br>
<br>
<br>
<!--------------------------------------->
<?php
//Connect to BD
include('connect.php');
//################TANTELY#####################
    //Query to get each point de vente
    $query_top20_cn = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE type_de_mvt = 'vente' ";
    $qt20_1 = $bdd->prepare($query_top20_cn);
    $qt20_1->execute(array());
    //--------------------------------
    ?>
    <div class="text-center">
      <button class="btn btn-success" data-toggle='modal' data-target='#top20'>AUTRE OPTION</button>
      <!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="top20" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                        <div class="form-group">
                                        <a href="home_char.php">
                                        <button class="btn btn-success">ACCEUIL</button>
                                        </a>
                                        <a href="top20a_activate.php">
                                        <button class="btn btn-secondary">TOP 20 DE LA SEMAINE</button>
                                        </a>
                                        <a href="top20b_activate.php">
                                        <button class="btn btn-info">TOP 20 DU MOIS</button>
                                        </a>
                                        <a href="top20c_activate.php">
                                        <button class="btn btn-danger">TOP 20 DU 6 MOIS</button>
                                        </a>
                                        </div>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
     </div>
    <h2 class="top20_style">TOP 20 DU <?php echo date('d M Y'); ?></span></h2>
    <div class="flex-center flex-column">
    <table class="table-bordered table-sm bg-info">
    <?php
    while ($donnees = $qt20_1 -> fetch())
    {
        ?>
        <tr>
        <th class="text-center bg-dark text-light" colspan="10"><b><?php echo "TOP 20 ".$donnees['nom_client_fournisseur'];?></b></th>
        </tr>
        <tr>
        <th class="text-center bg-danger text-light" colspan="2"><b>PAR SEMAINE</b></th>
        <th class="text-center bg-warning text-light" colspan="2"><b>PAR MOIS</b></th>
        <th class="text-center bg-danger text-light" colspan="2"><b>PAR 6 MOIS</b></th>
        </tr>
        <tbody>
    <?php
         //QUERY to get product for each by QT Hebdomadaire PV
         $query_qt1 = "SELECT *,produit.id_x as ID,nom_x,SUM(ABS(qt)) as QT,prix_unitaire,SUM(ABS(qt))*prix_unitaire as Montant,date_time FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 2 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? GROUP BY produit.id_x ORDER BY QT DESC LIMIT 20;";
        $qqt1 = $bdd->prepare($query_qt1);
        $qqt1->execute(array($donnees['nom_client_fournisseur']));
       //QUERY to get product for each by QT PAR MOI
         $query_qt2 = "SELECT *,produit.id_x as ID,nom_x,SUM(ABS(qt)) as QT,prix_unitaire,SUM(ABS(qt))*prix_unitaire as Montant,date_time FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 5 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? GROUP BY produit.id_x ORDER BY QT DESC LIMIT 20;";
        $qqt2 = $bdd->prepare($query_qt2);
        $qqt2->execute(array($donnees['nom_client_fournisseur']));
        //QUERY to get product for each by QT PAR SEMETRE
         $query_qt3 = "SELECT *,produit.id_x as ID,nom_x,SUM(ABS(qt)) as QT,prix_unitaire,SUM(ABS(qt))*prix_unitaire as Montant,date_time FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 25 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? GROUP BY produit.id_x ORDER BY QT DESC LIMIT 20;";
        $qqt3 = $bdd->prepare($query_qt3);
        $qqt3->execute(array($donnees['nom_client_fournisseur']));

        $cle = "";

        for ($i=0; $i < 20; $i++)
        {
            
            $nom_x1 = "Waiting for Journal";
            $id1 = $i;
            $qt1 = "";
            $no1 = "";
            $data1 = $qqt1 -> fetch();
            $qt_actu1 = "";

            if (null != $data1) 
            {
            $id1 = $data1['ID'];
            $nom_x1 = $data1['nom_x'];
            $qt1 = intval($data1['QT']);
            $no1 = $i + 1;
            //---------------------------------------------------------------
            //Query QT for specifique point de vente
			$query_stock_qt = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (id_x = ?);";
			$qt = $bdd->prepare($query_stock_qt);

			$qt->execute(array($donnees['nom_client_fournisseur'],$data1['id_x']));
			while ($donnees2 = $qt -> fetch())
            {
            	$qt_actu1 = intval($donnees2['sm']);
            }
            $qt->closeCursor();
			//---------------------------------------------------------------
			//QUERY TO SHOW STOCK
	   		$query_stock1 = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
	    	$qs1 = $bdd->prepare($query_stock1);
	    	$qs1->execute(array($data1['id_x'],$donnees['nom_client_fournisseur']));
	    	//---------------------------------------------------------------
			//QUERY TO SHOW VENTE
	   		$query_stock_vente1 = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
	    	$qv1 = $bdd->prepare($query_stock_vente1);
	    	$qv1->execute(array($data1['id_x'],$donnees['nom_client_fournisseur']));
	    	//----------------------------------------------------
	    	//QUERY to get each point de vente
		    $query_stock_pv1 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
		    $qpv1 = $bdd->prepare($query_stock_pv1);
		    $qpv1->execute(array($data1['id_x'],$donnees['nom_client_fournisseur']));
		    //----------------------------------------------------
		    //QUERY to get each point de vente
		    $query_stock_pv2 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
		    $qpv2 = $bdd->prepare($query_stock_pv2);
		    $qpv2->execute(array($data1['id_x'],$donnees['nom_client_fournisseur']));
		  	//----------------------------------------------------
		  	//QUERY to get each point de vente
		    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
		    $qpv3 = $bdd->prepare($query_stock_pv);
		    $qpv3->execute(array($data1['id_x'],$donnees['nom_client_fournisseur']));
		    //----------------------------------------------------
            }

            $nom_x2 = "Waiting for Journal";
            $id2 = $i;
            $qt2 = "";
            $no2 = "";
            $qt_actu2 = "";
            $data2 = $qqt2 -> fetch();
            if (null != $data2) 
            {
            $id2 = $data2['ID'];
            $nom_x2 = $data2['nom_x'];
            $qt2 = intval($data2['QT']);
            $no2 = $i + 1;
           
            //---------------------------------------------------------------
            //Query QT for specifique point de vente
			$query_stock_qt = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (id_x = ?);";
			$qt = $bdd->prepare($query_stock_qt);

			$qt->execute(array($donnees['nom_client_fournisseur'],$data2['id_x']));
			while ($donnees2 = $qt -> fetch())
            {
            	$qt_actu2 = intval($donnees2['sm']);
            }
            $qt->closeCursor();
			//---------------------------------------------------------------
			//QUERY TO SHOW STOCK
	   		$query_stock1b = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
	    	$qs1b = $bdd->prepare($query_stock1b);
	    	$qs1b->execute(array($data2['id_x'],$donnees['nom_client_fournisseur']));
	    	//---------------------------------------------------------------
			//QUERY TO SHOW VENTE
	   		$query_stock_vente1b = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
	    	$qv1b = $bdd->prepare($query_stock_vente1b);
	    	$qv1b->execute(array($data2['id_x'],$donnees['nom_client_fournisseur']));
	    	//----------------------------------------------------
	    	//QUERY to get each point de vente
		    $query_stock_pv1b = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
		    $qpv1b = $bdd->prepare($query_stock_pv1b);
		    $qpv1b->execute(array($data2['id_x'],$donnees['nom_client_fournisseur']));
		    //----------------------------------------------------
		    //QUERY to get each point de vente
		    $query_stock_pv2b = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
		    $qpv2b = $bdd->prepare($query_stock_pv2b);
		    $qpv2b->execute(array($data2['id_x'],$donnees['nom_client_fournisseur']));
		  	//----------------------------------------------------
		  	//QUERY to get each point de vente
		    $query_stock_pvb = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
		    $qpv3b = $bdd->prepare($query_stock_pvb);
		    $qpv3b->execute(array($data2['id_x'],$donnees['nom_client_fournisseur']));
		    //----------------------------------------------------
            }

            $nom_x3 = "Waiting for Journal";
            $id3 = $i;
            $qt3 = "";
            $no3 = "";
            $qt_actu3 = "";
            $data3 = $qqt3 -> fetch();
            if (null != $data3) 
            {
            $id3 = $data3['ID'];
            $nom_x3 = $data3['nom_x'];
            $qt3 = intval($data3['QT']);
            $no3 = $i + 1;
            
            //---------------------------------------------------------------
            //Query QT for specifique point de vente
			$query_stock_qt = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (id_x = ?);";
			$qt = $bdd->prepare($query_stock_qt);

			$qt->execute(array($donnees['nom_client_fournisseur'],$data3['id_x']));
			while ($donnees2 = $qt -> fetch())
            {
            	$qt_actu3 = intval($donnees2['sm']);
            	//$qt_actu3 = 999999;
            }
            $qt->closeCursor();
			//---------------------------------------------------------------
			//QUERY TO SHOW STOCK
	   		$query_stock1c = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
	    	$qs1c = $bdd->prepare($query_stock1c);
	    	$qs1c->execute(array($data3['id_x'],$donnees['nom_client_fournisseur']));
	    	//---------------------------------------------------------------
			//QUERY TO SHOW VENTE
	   		$query_stock_vente1c = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
	    	$qv1c = $bdd->prepare($query_stock_vente1c);
	    	$qv1c->execute(array($data3['id_x'],$donnees['nom_client_fournisseur']));
	    	//----------------------------------------------------
	    	//QUERY to get each point de vente
		    $query_stock_pv1c = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
		    $qpv1c = $bdd->prepare($query_stock_pv1c);
		    $qpv1c->execute(array($data3['id_x'],$donnees['nom_client_fournisseur']));
		    //----------------------------------------------------
		    //QUERY to get each point de vente
		    $query_stock_pv2c = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
		    $qpv2c = $bdd->prepare($query_stock_pv2c);
		    $qpv2c->execute(array($data3['id_x'],$donnees['nom_client_fournisseur']));
		  	//----------------------------------------------------
		  	//QUERY to get each point de vente
		    $query_stock_pvc = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
		    $qpv3c = $bdd->prepare($query_stock_pvc);
		    $qpv3c->execute(array($data3['id_x'],$donnees['nom_client_fournisseur']));
		    //----------------------------------------------------
            }
            //$no1 = 33;
           //AND $data2 = $qqt2 -> fetch() AND $data3 = $qqt3 -> fetch();
        ?>
                <tr>
                <td>
                    <?php
                    $cle = $donnees['nom_client_fournisseur'].$id1."QTA1";
                    echo "<span class ='w3-badge w3-left w3-margin-right w3-yellow'>".$no1."</span>".$nom_x1."<a class ='w3-badge w3-right w3-margin-center w3-green' style='font-size:8pt' data-toggle='modal' data-target='#".$cle."'>".$qt_actu1."</a>";
                    ?>
                            <!-- modal form QUATITE AFFICHAGE---------------------------------------->
						        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ($cle); ?>" class="modal fade">
						            <div class="modal-dialog modal-lg">
						                <div class="modal-content">
						                    <div class="modal-header">
						                    <h4 class="modal-title"><b><?php echo $nom_x1; ?></b></h4>
						                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
						                    </div>
						                    <div class="modal-body">
						                    <!-- actual form -->
						                    <form role="form">
						                        <div class="form-group">
						                        <label><b>STOCK</b></label>
						                        <ul class="list-group">
						                        <?php
						                        $total_stock = 0;
						                        if (null != $data1) {
						                        while ($donnees1 = $qs1 -> fetch())
						                        { 
						                            $total_stock = $total_stock + $donnees1['qt'];
						                            ?>
						                          <li class="list-group-item text-left"><?php echo $donnees1['nom_client_fournisseur']; ?> | <span style="font-weight: bold"><?php echo $donnees1['description_date']; ?></span> | <?php echo $donnees1['prix_unitaire']; ?><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
						                        <?php
						                        }
						                        $qs1->closeCursor();
						                        }// end if
						                        ?>
						                        <li class="list-group-item text-left"><span style="font-weight: bold">
						                            <?php
						                            if (null != $data1) {
						                             while ($data = $qpv1 -> fetch()) {
						                            //QUERY to sum each point de vente
						                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);

						                            $qsm->execute(array($data1['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($datax1['sm'],2, ",", " ").'</span><br>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv1->closeCursor();
						                            }// end if
						                            ?></span></li>
						                        </ul>
						                        </div>
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">VENTE</span></label>
						                        <ul class="list-group">
						                        <?php
						                        $total_vente = 0;
						                        if (null != $data1) {
						                        while ($donneesx2 = $qv1 -> fetch())
						                        { 
						                            $total_vente = $total_vente + $donnees2['qt'];

						                            ?>
						                          <li class="list-group-item text-left"><?php echo $donneesx2['nom_client_fournisseur']; ?> | <span style="font-weight: bold"><?php echo $donneesx2['description_date']; ?></span> | <?php echo $donneesx2['prix_unitaire']; ?><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ABS($donneesx2['qt']); ?></span></li>
						                        <?php
						                        }
						                        $qv1->closeCursor();
						                        }// end if
						                        ?>
						                        <li class="list-group-item text-left"><span style="font-weight: bold">
						                            <?php
						                            if (null != $data1) {
						                             while ($data = $qpv2 -> fetch()) {
						                            //QUERY to sum each point de vente
						                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);

						                            $qsm->execute(array($data1['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($datax1['sm'],2, ",", " ").'</span><br>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv2->closeCursor();
						                            }// end if
						                            ?>
						                        </li>
						                        </ul>
						                        </div>
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">RESTE STOCK</span></label>
						                        <ul class="list-group">
						                            <?php
						                        if (null != $data1) {
						                             while ($data = $qpv3 -> fetch()) {
						                            //QUERY to sum each vente by point de vente
						                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);
						                            $qsm->execute(array($data1['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            $vente = $datax1['sm_v'];
						                            $qsm->closeCursor();
						                            //QUERY to sum each stock by point de vente
						                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);
						                            $qsm->execute(array($data1['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            $stock = $datax1['sm_s'];
						                            $qsm->closeCursor();
						                            echo '<li class="list-group-item text-left"><span style="font-weight: bold">TL '.$data['nom_client_fournisseur'].'</span><span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2, ",", " ").'</span></li>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv3->closeCursor();
						                        }// end if
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
						<!---------------------------------------------------------------------------->
                </td>
               	<td>
                      <?php
                    $cle = $donnees['nom_client_fournisseur'].$id1."QT1";
                    echo "<a class ='w3-badge w3-right w3-red' data-toggle='modal' data-target='#".$cle."'>".$qt1."</a>";
                    ?>      
                    <!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>VENTE DETAILS DE <?php echo $data1['nom_x']; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                    <form role="form" action="#" method="post">
                                        <div class="form-group">
                                        <label><span style="font-weight: bold">DATE DU VENTE</span></label>
                                        <ul class="list-group">
                                        <?php
                                        //QUERY to get product for each by QT PV DETAILS
                                        $query_qt_details = "SELECT * FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 2 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND mvt.id_x = ?;";
                                        $qqt1d = $bdd->prepare($query_qt_details);
                                        $qqt1d->execute(array($donnees['nom_client_fournisseur'],$data1['ID']));
                                        $l = 1;
                                        $total = 0;
                                        while ($donnees2 = $qqt1d -> fetch())
                                        { 
                                            $total = $total + ABS($donnees2['qt']);
                                            ?>
                                          <li class="list-group-item text-left"><span class="w3-badge w3-blue w3-margin-right"><b><?php echo $l; ?></b></span><span style="font-weight: bold"><?php echo $donnees2['description_date']."</span> Traité par ".$donnees2['user_mvt']; ?><span class="w3-badge w3-right w3-pink w3-margin-right"><?php echo ABS($donnees2['qt']); ?></span></li>
                                        <?php
                                        $l=$l+1;
                                        }
                                        $qqt1d->closeCursor();
                                        ?>
                                        <li class="list-group-item text-left"><span style="font-weight: bold">TOTAL</span><span class="w3-badge w3-right w3-margin-right"><?php echo $total; ?></span></li>
                                        </ul>
                                        </div>
                                    </form>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
                </td>
                <td>
                    <?php
                    $cle = $donnees['nom_client_fournisseur'].$id1."QTA2";
                    echo "<span class ='w3-badge w3-left w3-margin-right w3-yellow'>".$no2."</span>".$nom_x2."<a class ='w3-badge w3-right w3-margin-center w3-green' style='font-size:8pt' data-toggle='modal' data-target='#".$cle."'>".$qt_actu2."</a>";
                    ?>
		             <!---- modal form QUATITE AFFICHAGE---------------------------------------->
						        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ($cle); ?>" class="modal fade">
						            <div class="modal-dialog modal-lg">
						                <div class="modal-content">
						                    <div class="modal-header">
						                    <h4 class="modal-title"><span style="font-weight: bold"><?php echo $nom_x2; ?></b></h4>
						                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
						                    </div>
						                    <div class="modal-body">
						                    <!-- actual form -->
						                    <form role="form">
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">STOCK</span></label>
						                        <ul class="list-group">
						                        <?php
						                        $total_stock = 0;
						                        if (null != $data2) {
						                        while ($donnees1 = $qs1b -> fetch())
						                        { 
						                            $total_stock = $total_stock + $donnees1['qt'];
						                            ?>
						                          <li class="list-group-item text-left"><?php echo $donnees1['nom_client_fournisseur']; ?> | <span style="font-weight: bold"><?php echo $donnees1['description_date']; ?></span> | <?php echo $donnees1['prix_unitaire']; ?><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
						                        <?php
						                        }
						                        $qs1b->closeCursor();
						                        }// end if
						                        ?>
						                        <li class="list-group-item text-left"><span style="font-weight: bold">
						                            <?php
						                            if (null != $data2) {
						                             while ($data = $qpv1b -> fetch()) {
						                            //QUERY to sum each point de vente
						                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);

						                            $qsm->execute(array($data2['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($datax1['sm'],2, ",", " ").'</span><br>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv1b->closeCursor();
						                            }// end if
						                            ?></span></li>
						                        </ul>
						                        </div>
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">VENTE</span></label>
						                        <ul class="list-group">
						                        <?php
						                        $total_vente = 0;
						                        if (null != $data2) {
						                        while ($donneesx2 = $qv1b -> fetch())
						                        { 
						                            $total_vente = $total_vente + $donnees2['qt'];

						                            ?>
						                          <li class="list-group-item text-left"><?php echo $donneesx2['nom_client_fournisseur']; ?> | <span style="font-weight: bold"><?php echo $donneesx2['description_date']; ?></span> | <?php echo $donneesx2['prix_unitaire']; ?><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ABS($donneesx2['qt']); ?></span></li>
						                        <?php
						                        }
						                        $qv1b->closeCursor();
						                        }// end if
						                        ?>
						                        <li class="list-group-item text-left"><span style="font-weight: bold">
						                            <?php
						                            if (null != $data2) {
						                             while ($data = $qpv2b -> fetch()) {
						                            //QUERY to sum each point de vente
						                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);

						                            $qsm->execute(array($data2['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($datax1['sm'],2, ",", " ").'</span><br>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv2b->closeCursor();
						                            }// end if
						                            ?>
						                        </li>
						                        </ul>
						                        </div>
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">RESTE STOCK</span></label>
						                        <ul class="list-group">
						                            <?php
						                        if (null != $data2) {
						                             while ($data = $qpv3b -> fetch()) {
						                            //QUERY to sum each vente by point de vente
						                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);
						                            $qsm->execute(array($data2['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            $vente = $datax1['sm_v'];
						                            $qsm->closeCursor();
						                            //QUERY to sum each stock by point de vente
						                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);
						                            $qsm->execute(array($data2['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            $stock = $datax1['sm_s'];
						                            $qsm->closeCursor();
						                            echo '<li class="list-group-item text-left"><span style="font-weight: bold">TL '.$data['nom_client_fournisseur'].'</span><span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2, ",", " ").'</span></li>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv3b->closeCursor();
						                        }// end if
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
						<!---------------------------------------------------------------------------->
                </td>
               	<td>
                      <?php
                    $cle = $donnees['nom_client_fournisseur'].$id2."QT2";
                    echo "<a class ='w3-badge w3-right w3-red' data-toggle='modal' data-target='#".$cle."'>".$qt2."</a>";
                    ?> 
                    <!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>VENTE DETAILS DE <?php echo $data1['nom_x']; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                    <form role="form" action="#" method="post">
                                        <div class="form-group">
                                        <label><span style="font-weight: bold">DATE DU VENTE</span></label>
                                        <ul class="list-group">
                                        <?php
                                        //QUERY to get product for each by MT PV DETAILS
                                        $query_qt2_details = "SELECT * FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 5 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND mvt.id_x = ?;";
                                        $qqt2d = $bdd->prepare($query_qt2_details);
                                        $qqt2d->execute(array($donnees['nom_client_fournisseur'],$data2['ID']));
                                        $l = 1;
                                        $total = 0;//number_format(intval($data2['Montant']),0, "", " ")
                                        while ($donnees2 = $qqt2d -> fetch())
                                        { 
                                            $total = $total + ABS($donnees2['qt']);
                                            ?>
                                          <li class="list-group-item text-left"><span class="w3-badge w3-blue w3-margin-right"><b><?php echo $l; ?></b></span><span style="font-weight: bold"><?php echo $donnees2['description_date']."</span> Traité par ".$donnees2['user_mvt']; ?><span class="w3-badge w3-right w3-pink w3-margin-right"><?php echo ABS($donnees2['qt']); ?></span></li>
                                        <?php
                                        $l=$l+1;
                                        }
                                        $qqt2d->closeCursor();
                                        ?>
                                        <li class="list-group-item text-left"><span style="font-weight: bold">TOTAL</span><span class="w3-badge w3-right w3-margin-right"><?php echo number_format($total,0, "", " "); ?></span></li>
                                        </ul>
                                        </div>
                                    </form>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
                </td>
                <td>
                    <?php
                    $cle = $donnees['nom_client_fournisseur'].$id1."QTA3";
                    echo "<span class ='w3-badge w3-left w3-margin-right w3-yellow'>".$no3."</span>".$nom_x3."<a class ='w3-badge w3-right w3-margin-center w3-green' style='font-size:8pt' data-toggle='modal' data-target='#".$cle."'>".$qt_actu3."</a>";
                    ?>
                    <!-- modal form QUATITE AFFICHAGE---------------------------------------->
						        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ($cle); ?>" class="modal fade">
						            <div class="modal-dialog modal-lg">
						                <div class="modal-content">
						                    <div class="modal-header">
						                    <h4 class="modal-title"><span style="font-weight: bold"><?php echo $nom_x3; ?></b></h4>
						                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
						                    </div>
						                    <div class="modal-body">
						                    <!-- actual form -->
						                    <form role="form">
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">STOCK</span></label>
						                        <ul class="list-group">
						                        <?php
						                        $total_stock = 0;
						                        if (null != $data3) {
						                        while ($donnees1 = $qs1c -> fetch())
						                        { 
						                            $total_stock = $total_stock + $donnees1['qt'];
						                            ?>
						                          <li class="list-group-item text-left"><?php echo $donnees1['nom_client_fournisseur']; ?> | <span style="font-weight: bold"><?php echo $donnees1['description_date']; ?></span> | <?php echo $donnees1['prix_unitaire']; ?><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
						                        <?php
						                        }
						                        $qs1c->closeCursor();
						                        }// end if
						                        ?>
						                        <li class="list-group-item text-left"><span style="font-weight: bold">
						                            <?php
						                            if (null != $data3) {
						                             while ($data = $qpv1c -> fetch()) {
						                            //QUERY to sum each point de vente
						                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);

						                            $qsm->execute(array($data3['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($datax1['sm'],2, ",", " ").'</span><br>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv1c->closeCursor();
						                            }// end if
						                            ?></span></li>
						                        </ul>
						                        </div>
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">VENTE</span></label>
						                        <ul class="list-group">
						                        <?php
						                        $total_vente = 0;
						                        if (null != $data3) {
						                        while ($donneesx2 = $qv1c -> fetch())
						                        { 
						                            $total_vente = $total_vente + $donnees2['qt'];

						                            ?>
						                          <li class="list-group-item text-left"><?php echo $donneesx2['nom_client_fournisseur']; ?> | <span style="font-weight: bold"><?php echo $donneesx2['description_date']; ?></span> | <?php echo $donneesx2['prix_unitaire']; ?><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ABS($donneesx2['qt']); ?></span></li>
						                        <?php
						                        }
						                        $qv1c->closeCursor();
						                        }// end if
						                        ?>
						                        <li class="list-group-item text-left"><span style="font-weight: bold">
						                            <?php
						                            if (null != $data3) {
						                             while ($data = $qpv2c -> fetch()) {
						                            //QUERY to sum each point de vente
						                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);

						                            $qsm->execute(array($data3['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($datax1['sm'],2, ",", " ").'</span><br>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv2c->closeCursor();
						                            }// end if
						                            ?>
						                        </li>
						                        </ul>
						                        </div>
						                        <div class="form-group">
						                        <label><span style="font-weight: bold">RESTE STOCK</span></label>
						                        <ul class="list-group">
						                            <?php
						                        if (null != $data3) {
						                             while ($data = $qpv3c -> fetch()) {
						                            //QUERY to sum each vente by point de vente
						                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);
						                            $qsm->execute(array($data3['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            $vente = $datax1['sm_v'];
						                            $qsm->closeCursor();
						                            //QUERY to sum each stock by point de vente
						                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
						                            $qsm = $bdd->prepare($query);
						                            $qsm->execute(array($data3['id_x'],$data['nom_client_fournisseur']));
						                            $datax1 = $qsm -> fetch();
						                            $stock = $datax1['sm_s'];
						                            $qsm->closeCursor();
						                            echo '<li class="list-group-item text-left"><span style="font-weight: bold">TL '.$data['nom_client_fournisseur'].'</span><span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2, ",", " ").'</span></li>';
						                            $qsm->closeCursor();
						                            }
						                            $qpv3c->closeCursor();
						                        }// end if
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
						<!---------------------------------------------------------------------------->
                </td>
               	<td>
                      <?php
                    $cle = $donnees['nom_client_fournisseur'].$id3."QT3";
                    echo "<a class ='w3-badge w3-right w3-red' data-toggle='modal' data-target='#".$cle."'>".$qt3."</a>";
                    ?> 
                    <!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>VENTE DETAILS DE <?php echo $data1['nom_x']; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                    <form role="form" action="#" method="post">
                                        <div class="form-group">
                                        <label><span style="font-weight: bold">DATE DU VENTE</span></label>
                                        <ul class="list-group">
                                        <?php
                                        //QUERY to get product for each by MT PV DETAILS 6 MOIS
                                        $query_qt3_details = "SELECT * FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 25 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND mvt.id_x = ?;";
                                        $qqt3d = $bdd->prepare($query_qt3_details);
                                        $qqt3d->execute(array($donnees['nom_client_fournisseur'],$data3['ID']));
                                        $l = 1;
                                        $total = 0;//number_format(intval($data2['Montant']),0, "", " ")
                                        while ($donnees2 = $qqt3d -> fetch())
                                        { 
                                            $total = $total + ABS($donnees2['qt']);
                                            ?>
                                          <li class="list-group-item text-left"><span class="w3-badge w3-blue w3-margin-right"><b><?php echo $l; ?></b></span><span style="font-weight: bold"><?php echo $donnees2['description_date']."</span> Traité par ".$donnees2['user_mvt']; ?><span class="w3-badge w3-right w3-pink w3-margin-right"><?php echo ABS($donnees2['qt']); ?></span></li>
                                        <?php
                                        $l=$l+1;
                                        }
                                        $qqt2d->closeCursor();
                                        ?>
                                        <li class="list-group-item text-left"><span style="font-weight: bold">TOTAL</span><span class="w3-badge w3-right w3-margin-right"><?php echo $total; ?></span></li>
                                        </ul>
                                        </div>
                                    </form>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
                </td>
                </tr>
        <?php
        } //CLOSE OF WHILE $qqt1
        ?>
        <?php

        $qqt1->closeCursor();
        $qqt2->closeCursor();
     ?>
        </tbody>
    <?php
    }
   $qt20_1->closeCursor();
?>
        </table>
        </div>
<br>
<br>
<script type="text/javascript">

</script>
  <!-- jQuery -->
  <!-- Bootstrap tooltips -->
  <!-- Bootstrap core JavaScript -->
  <!-- MDB core JavaScript -->
  <!-- Your custom scripts (optional) -->
</body>
</html>