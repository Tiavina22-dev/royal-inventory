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
                                        <a href="top20p_activate.php">
                                        <button class="btn btn-primary">PRINTABLE</button>
                                        </a>
                                        </div>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
     </div>
    <h2 class="top20_style">TOP 20 DU 6 MOIS</h2>
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
        <th class="text-center bg-danger text-light"><b>PAR QUANTITE</b></th>
        <th class="text-center bg-danger text-light"><b>PAR MONTANT</b></th>
        </tr>
        <tbody>
    <?php
         //QUERY to get product for each by QT PV
         $query_qt = "SELECT *,produit.id_x as ID,nom_x,SUM(ABS(qt)) as QT,prix_unitaire,SUM(ABS(qt))*prix_unitaire as Montant,date_time FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 25 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? GROUP BY produit.id_x ORDER BY QT DESC LIMIT 20;";
        $qqt = $bdd->prepare($query_qt);
        $qqt->execute(array($donnees['nom_client_fournisseur']));
       //QUERY to get product for each by MT
         $query_mt = "SELECT *,produit.id_x as ID,nom_x,SUM(ABS(qt)) as QT,prix_unitaire,SUM(ABS(qt))*prix_unitaire as Montant,(SUM(ABS(qt))*SUM(ABS(qt))*prix_unitaire*prix_unitaire) as filtre,date_time FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 25 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? GROUP BY produit.id_x ORDER BY filtre DESC LIMIT 20;";
        $qmt = $bdd->prepare($query_mt);
        $qmt->execute(array($donnees['nom_client_fournisseur']));
        ?>
            <tr>
                <td>
                    <div class="top20">
                        <ol>
                            <?php
                                $cle = "";
                                for ($i=0; $i < 20; $i++) 
                                {
                                    $data_qt = $qqt -> fetch();
                                    if (null != $data_qt) {
                                    //---------------------------------------------------------------
                                    //Query QT for specifique point de vente
                                    $query_stock_qt = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (id_x = ?);";
                                    $qt = $bdd->prepare($query_stock_qt);

                                    $qt->execute(array($donnees['nom_client_fournisseur'],$data_qt['id_x']));
                                    while ($donnees2 = $qt -> fetch())
                                    {
                                        $qt_actu1 = intval($donnees2['sm']);
                                    }
                                    $qt->closeCursor();
                                    //---------------------------------------------------------------
                                    //---------------------------------------------------------------
                                    //QUERY TO SHOW STOCK
                                    $query_stock1 = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
                                    $qs1 = $bdd->prepare($query_stock1);
                                    $qs1->execute(array($data_qt['id_x'],$donnees['nom_client_fournisseur']));
                                    //---------------------------------------------------------------
                                    //QUERY TO SHOW VENTE
                                    $query_stock_vente1 = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
                                    $qv1 = $bdd->prepare($query_stock_vente1);
                                    $qv1->execute(array($data_qt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //QUERY to get each point de vente
                                    $query_stock_pv1 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
                                    $qpv1 = $bdd->prepare($query_stock_pv1);
                                    $qpv1->execute(array($data_qt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //QUERY to get each point de vente
                                    $query_stock_pv2 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
                                    $qpv2 = $bdd->prepare($query_stock_pv2);
                                    $qpv2->execute(array($data_qt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //QUERY to get each point de vente
                                    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
                                    $qpv3 = $bdd->prepare($query_stock_pv);
                                    $qpv3->execute(array($data_qt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    $cle = $donnees['nom_client_fournisseur'].$data_qt['ID']."QT";
                                    $cle2 = $donnees['nom_client_fournisseur'].$data_qt['ID']."QT2";
                                    echo "<span class='top20_list'>".$data_qt['nom_x']."<a class ='w3-badge w3-right w3-margin-center w3-red' data-toggle='modal' data-target='#".$cle."'>".intval($data_qt['QT'])."</a>"."<a class ='w3-badge w3-right w3-margin-center w3-orange' style='font-size:8pt' data-toggle='modal' data-target='#".$cle2."'>".intval($qt_actu1)."</a>"."</span>";
                                    ?> 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>VENTE DETAILS DE <?php echo $data_qt['nom_x']; ?></b></h4>
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
                                        $query_qt_details = "SELECT * FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 25 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND mvt.id_x = ?;";
                                        $qqtd = $bdd->prepare($query_qt_details);
                                        $qqtd->execute(array($donnees['nom_client_fournisseur'],$data_qt['ID']));
                                        $l = 1;
                                        $total = 0;
                                        while ($donnees2 = $qqtd -> fetch())
                                        { 
                                            $total = $total + ABS($donnees2['qt']);
                                            ?>
                                          <li class="list-group-item text-left"><span class="w3-badge w3-blue w3-margin-right"><b><?php echo $l; ?></b></span><span style="font-weight: bold"><?php echo $donnees2['description_date']."</span> Traité par ".$donnees2['user_mvt']; ?><span class="w3-badge w3-right w3-pink w3-margin-right"><?php echo ABS($donnees2['qt']); ?></span></li>
                                        <?php
                                        $l=$l+1;
                                        }
                                        $qqtd->closeCursor();
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
                        <!-- modal form QUATITE AFFICHAGE---------------------------------------->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle2; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>VENTE DETAILS DE <?php echo $data_qt['nom_x']; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                    <form role="form" action="#" method="post">
                                        <div class="form-group">
                                        <label><span style="font-weight: bold">STOCK</span></label>
                                        <ul class="list-group">
                                        <?php
                                                $total_stock = 0;
                                                if (null != $data_qt) {
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
                                                    if (null != $data_qt) {
                                                     while ($data = $qpv1 -> fetch()) {
                                                    //QUERY to sum each point de vente
                                                    $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);

                                                    $qsm->execute(array($data_qt['id_x'],$data['nom_client_fournisseur']));
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
                                                if (null != $data_qt) {
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
                                                    if (null != $data_qt) {
                                                     while ($data = $qpv2 -> fetch()) {
                                                    //QUERY to sum each point de vente
                                                    $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);

                                                    $qsm->execute(array($data_qt['id_x'],$data['nom_client_fournisseur']));
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
                                                if (null != $data_qt) {
                                                     while ($data = $qpv3 -> fetch()) {
                                                    //QUERY to sum each vente by point de vente
                                                    $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);
                                                    $qsm->execute(array($data_qt['id_x'],$data['nom_client_fournisseur']));
                                                    $datax1 = $qsm -> fetch();
                                                    $vente = $datax1['sm_v'];
                                                    $qsm->closeCursor();
                                                    //QUERY to sum each stock by point de vente
                                                    $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);
                                                    $qsm->execute(array($data_qt['id_x'],$data['nom_client_fournisseur']));
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
                                    </form>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
                        <!------------------------------------------->
                            <?php
                                }//if
                                else
                                {
                                    echo "<span class='top20_list'>En attente du Journal</span>";
                                }
                                } //CLOSE OF FOR
                    $qqt->closeCursor();
                            ?>
            
                        </ol>
                    </div>
            </td>
                <td>
                    <div class="top20">
                        <ol>
                            <?php
                                $cle = "";
                                for ($i=0; $i < 20; $i++) 
                                {
                                    $data_mt = $qmt -> fetch();
                                    if (null != $data_mt) {
                                    //---------------------------------------------------------------
                                    //Query QT for specifique point de vente
                                    $query_stock_qt = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (id_x = ?);";
                                    $qt = $bdd->prepare($query_stock_qt);

                                    $qt->execute(array($donnees['nom_client_fournisseur'],$data_mt['id_x']));
                                    while ($donnees2 = $qt -> fetch())
                                    {
                                        $qt_actu2 = intval($donnees2['sm']);
                                    }
                                    $qt->closeCursor();
                                    //---------------------------------------------------------------
                                    //QUERY TO SHOW STOCK
                                    $query_stock1b = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
                                    $qs1b = $bdd->prepare($query_stock1b);
                                    $qs1b->execute(array($data_mt['id_x'],$donnees['nom_client_fournisseur']));
                                    //---------------------------------------------------------------
                                    //QUERY TO SHOW VENTE
                                    $query_stock_vente1b = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
                                    $qv1b = $bdd->prepare($query_stock_vente1b);
                                    $qv1b->execute(array($data_mt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //QUERY to get each point de vente
                                    $query_stock_pv1b = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
                                    $qpv1b = $bdd->prepare($query_stock_pv1b);
                                    $qpv1b->execute(array($data_mt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //QUERY to get each point de vente
                                    $query_stock_pv2b = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
                                    $qpv2b = $bdd->prepare($query_stock_pv2b);
                                    $qpv2b->execute(array($data_mt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //QUERY to get each point de vente
                                    $query_stock_pvb = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
                                    $qpv3b = $bdd->prepare($query_stock_pvb);
                                    $qpv3b->execute(array($data_mt['id_x'],$donnees['nom_client_fournisseur']));
                                    //----------------------------------------------------
                                    //---------------------------------------------------------------
                                    $cle = $donnees['nom_client_fournisseur'].$data_mt['ID']."MT";
                                    $cle2 = $donnees['nom_client_fournisseur'].$data_mt['ID']."MT2";
                                    echo "<span class='top20_list'>".$data_mt['nom_x']."<a class ='w3-badge w3-right w3-margin-center w3-red' data-toggle='modal' data-target='#".$cle."'>".number_format(intval($data_mt['Montant']),0, "", " ")."</a>"."<a class ='w3-badge w3-right w3-margin-center w3-orange' style='font-size:8pt' data-toggle='modal' data-target='#".$cle2."'>".intval($qt_actu2)."</a>"."</span>";
                                    ?>
                                    <!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>VENTE DETAILS DE <?php echo $data_mt['nom_x']; ?></b></h4>
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
                                        $query_mt_details = "SELECT *,(ABS(qt)*prix_unitaire) as Montant FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE date_time > date_sub(now(), interval 25 week) AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND mvt.id_x = ?;";
                                        $qmtd = $bdd->prepare($query_mt_details);
                                        $qmtd->execute(array($donnees['nom_client_fournisseur'],$data_mt['ID']));
                                        $l = 1;
                                        $total = 0;//number_format(intval($data_mt['Montant']),0, "", " ")
                                        while ($donnees2 = $qmtd -> fetch())
                                        { 
                                            $total = $total + $donnees2['Montant'];
                                            ?>
                                          <li class="list-group-item text-left"><span class="w3-badge w3-blue w3-margin-right"><b><?php echo $l; ?></b></span><span style="font-weight: bold"><?php echo $donnees2['description_date']."</span> Traité par ".$donnees2['user_mvt']; ?><span class="w3-right"><span class="w3-badge w3-green"><?php echo ABS($donnees2['qt']); ?></span> x <span class="w3-badge w3-purple"><?php echo number_format($donnees2['prix_unitaire'],0, "", " "); ?></span> = <span class="w3-badge w3-pink"><?php echo number_format($donnees2['Montant'],0, "", " "); ?></span></span></li>
                                        <?php
                                        $l=$l+1;
                                        }
                                        $qmtd->closeCursor();
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
                                                <!-- modal form QUATITE AFFICHAGE---------------------------------------->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $cle2; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b>MOUVEMENT DE <?php echo $data_mt['nom_x']; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                    <form role="form" action="#" method="post">
                                        <div class="form-group">
                                        <label><span style="font-weight: bold">STOCK</span></label>
                                        <ul class="list-group">
                                        <?php
                                                $total_stock = 0;
                                                if (null != $data_mt) {
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
                                                    if (null != $data_mt) {
                                                     while ($data = $qpv1b -> fetch()) {
                                                    //QUERY to sum each point de vente
                                                    $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);

                                                    $qsm->execute(array($data_mt['id_x'],$data['nom_client_fournisseur']));
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
                                                if (null != $data_mt) {
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
                                                    if (null != $data_mt) {
                                                     while ($data = $qpv2b -> fetch()) {
                                                    //QUERY to sum each point de vente
                                                    $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);

                                                    $qsm->execute(array($data_mt['id_x'],$data['nom_client_fournisseur']));
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
                                                if (null != $data_mt) {
                                                     while ($data = $qpv3b -> fetch()) {
                                                    //QUERY to sum each vente by point de vente
                                                    $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);
                                                    $qsm->execute(array($data_mt['id_x'],$data['nom_client_fournisseur']));
                                                    $datax1 = $qsm -> fetch();
                                                    $vente = $datax1['sm_v'];
                                                    $qsm->closeCursor();
                                                    //QUERY to sum each stock by point de vente
                                                    $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                                                    $qsm = $bdd->prepare($query);
                                                    $qsm->execute(array($data_mt['id_x'],$data['nom_client_fournisseur']));
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
                                    </form>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
                            <?php
                                }//end if
                                else
                                {
                                    echo "<span class='top20_list'>En attente du Journal</span>";
                                }
                                } //CLOSE OF WHILE $qqt
                            ?>
            
                        </ol>
                    </div>
            </td>
            <tr>
        <?php
        $qmt->closeCursor();
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