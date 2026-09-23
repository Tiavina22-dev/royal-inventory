<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Controle Pannel</title>
  <!-- Font Awesome -->
  <!--
  <link rel="stylesheet" href="css/all.css">
  -->
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <!--
  <link rel="stylesheet" href="css/style.css">
  -->
  <link rel="stylesheet" href="css/w3.css">
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
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
    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE description_date LIKE '%Rectifier%' AND type_de_mvt = 'stock' ";
    $qpv0 = $bdd->prepare($query_stock_pv);
    $qpv0->execute(array());
    //--------------------------------
    ?>
    <div class="flex-center flex-column">
    <table class="table-bordered table-sm bg-light">
    <?php
    while ($donnees = $qpv0 -> fetch())
    {
        ?>
        <thead>
        <tr>
        <th class="text-center bg-dark text-light" colspan="9"><b><?php echo $donnees['nom_client_fournisseur'];?><br>LES RECTIFICATIONS ET LES CONTROLE EFFECTUEES</b></th>
        </tr>
        <tr>
        <th class="text-center"><b>Produit</b></th>
        <th class="text-center"><b>QT_Actu</b></th>
        <th class="text-center"><b>+</b></th>
        <th class="text-center"><b>-</b></th>
        <th class="text-center"><b>0</b></th>
        <th class="text-center"><b>TL</b></th>
        <th class="text-center"><b>PU</b></th>
        <th class="text-center"><b>Gain/Perte</b></th>
        <th class="text-center"><b>Dernier Controle</b></th>
        </tr>
        </thead>
        <tbody>
    <?php
                //QUERY to get all product verified
                $query = "SELECT *,produit.id_x as id_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE mvt.description_date LIKE '%Rectifier%' AND mvt.type_de_mvt = 'stock' AND mvt.nom_client_fournisseur = ? GROUP BY produit.nom_x;";
                $qsm = $bdd->prepare($query);
                $qsm->execute(array($donnees['nom_client_fournisseur']));
                $no = 0;
                $TL = 0;
                while ($data = $qsm -> fetch())
                {
                    //$latest_date ="04/03/21";
                    $pu = $data['prix_de_vente'];
                    if ($donnees['nom_client_fournisseur'] == 'Amparafa') {$pu = $data['pu_aparafa'];}
                    if ($donnees['nom_client_fournisseur'] == 'Ambato_Tantely') {$pu = $data['pu_ambato_tantely'];}
                    //GET Each product
                    //QUERY to get rectification negatif
                    $query_stock_pv = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND (qt<0) AND description_date LIKE '%Rectifier%';";
                    $qpv = $bdd->prepare($query_stock_pv);
                    $qpv->execute(array($data['id_x'],$donnees['nom_client_fournisseur']));
                    $Nb_neg=$qpv->rowCount ();
                    $qpv->closeCursor();
                    //QUERY to get rectification positif
                    $query_stock_pv = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND (qt>0) AND description_date LIKE '%Rectifier%';";
                    $qpv = $bdd->prepare($query_stock_pv);
                    $qpv->execute(array($data['id_x'],$donnees['nom_client_fournisseur']));
                    $Nb_positif=$qpv->rowCount ();
                    $qpv->closeCursor();
                    //QUERY to get rectification zero
                    $query_stock_pv = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND (qt=0) AND description_date LIKE '%Rectifier%';";
                    $qpv = $bdd->prepare($query_stock_pv);
                    $qpv->execute(array($data['id_x'],$donnees['nom_client_fournisseur']));
                    $Nb_zero=$qpv->rowCount ();
                    $qpv->closeCursor();
                    //QUERY to get All rectification
                    $query_stock_rec = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND description_date LIKE '%Rectifier%' ORDER BY id_mvt DESC;";
                    $qpv_rec = $bdd->prepare($query_stock_rec);
                    $qpv_rec->execute(array($data['id_x'],$donnees['nom_client_fournisseur']));
                    $Nb_total=$qpv_rec->rowCount();
                    
                    //Get Latest Date
                   $query = "SELECT *  from mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND description_date LIKE '%Rectifier%' GROUP BY description_date;";
                    $qc = $bdd->prepare($query);

                    $qc->execute(array($data['id_x'],$donnees['nom_client_fournisseur']));
                    $chaine = "";
                    $date1 = "";
                    $latest_date = "31-01/ 20";
                        while ($donnees1 = $qc -> fetch())
                        {
                            $chaine =  $donnees1['description_date'].' ';
                            $x = chr(35).'/0-9-';
                            preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
                            //Prise en compte separation date / or -
                            if (isset($res_regex[1])) {
                            $date1 = $res_regex[1];
                            //echo $donnees['nom_client_fournisseur']." : ".$date1;
                            //echo "<br>";
                            }
                            //$date1 = strtotime($res_regex[1]);
                            //$date1 = date("y/m/d", $date1);


                            //Eviter wrong format and unwanted space for $latest_date
                            $latest_date = str_replace('- ', '/', $latest_date);
                            $latest_date = str_replace('/ ', '/', $latest_date);
                            $latest_date = str_replace(' / ', '/', $latest_date);
                            $latest_date = str_replace(' -', '/', $latest_date);
                            $latest_date = str_replace(' - ', '/', $latest_date);
                            $latest_date = str_replace('-', '/', $latest_date);

                            //Eviter wrong format and unwanted space for $date1
                            $date1 = str_replace('- ', '/', $date1);
                            $date1 = str_replace('/ ', '/', $date1);
                            $date1 = str_replace(' / ', '/', $date1);
                            $date1 = str_replace(' -', '/', $date1);
                            $date1 = str_replace(' - ', '/', $date1);
                            $date1 = str_replace('-', '/', $date1);
                            

                            //Convert date to english format for compare
                            $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
                            $date1_en =$date1_en -> format('y/m/d');

                            $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date);
                            $latest_en = $latest_en -> format('y/m/d');
                            /*
                            echo $donnees['nom_client_fournisseur']." : latest_en ".$latest_en.'<br>';
                            echo $donnees['nom_client_fournisseur']." : date1_en ".$date1_en.'<br>';
                            */
                            if ($latest_en < $date1_en) {
                                $latest_date = $date1 ;
                                //echo 'Choisi'.$latest_date.'<br>';
                            }

                            //echo '>>'.$date1.'<br>';

                                }
                    $qc->closeCursor();
                    //QUERY to get All rectification
                    $query_stock_rec = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? AND description_date LIKE '%Rectifier%' ORDER BY id_mvt DESC;";
                    $qpv_rec = $bdd->prepare($query_stock_rec);
                    $qpv_rec->execute(array($data['id_x'],$donnees['nom_client_fournisseur']));
                    $Nb_total=$qpv_rec->rowCount();

                    //---------------------------------------------------------------
                    //Query QT for specifique point de vente
				   $query_stock_qt = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (id_x = ?);";
				    $qt = $bdd->prepare($query_stock_qt);

				    $qt->execute(array($donnees['nom_client_fournisseur'],$data['id_x']));
				    while ($donnees2 = $qt -> fetch())
                        {
                        	$qt_actu = number_format($donnees2['sm']);
                        }
                    $qt->closeCursor();
				    //---------------------------------------------------------------
                    ?>
                    <tr>
                        <td><?php echo $data['nom_x'];?></td>
                        <th class="text-center"><b><?php echo $qt_actu;?></b></th>
                        <td><span class="w3-badge w3-right w3-margin-center w3-orange"><?php echo $Nb_positif;?></span></td>
                        <td><span class="w3-badge w3-right w3-margin-center w3-red"><?php echo $Nb_neg;?></span></td>
                        <td><span class="w3-badge w3-right w3-margin-center w3-green"><?php echo $Nb_zero;?></span></td>
                        <td><span class="w3-badge w3-right w3-margin-center w3-purple"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ($donnees['nom_client_fournisseur'].$no); ?>"><?php echo $Nb_total;?></a></span></td>
                        <td class="text-right"><?php echo $pu;?></td>
                        <?php $gain_perte = ($pu*$Nb_positif)-($pu*$Nb_neg);$TL = $TL+$gain_perte;?>
                        <td class="text-right"><?php echo $gain_perte;?></td>
                        <td class="text-right"><?php echo $latest_date;?></td>
                        <!------------------------------------------->
                                    <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ($donnees['nom_client_fournisseur'].$no); ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><b><?php echo $data['nom_x']." (".$data['reference_x'].")"; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                    <!-- actual form -->
                                    <form role="form" action="#" method="post">
                                        <div class="form-group">
                                        <label><span style="font-weight: bold">DATE / RECTIFICATION / CONTROLE</span></label>
                                        <ul class="list-group">
                                        <?php
                                        $l = $Nb_total;
                                        while ($donnees2 = $qpv_rec -> fetch())
                                        { 
                                            $sign = "";
                                            if ($donnees2['qt']<0) {
                                                $color = "w3-red";
                                            }
                                            if ($donnees2['qt']>0) {
                                                $color = "w3-orange";
                                                $sign = "+";
                                            }
                                            if ($donnees2['qt']==0) {
                                                $color = "w3-green";
                                            }
                                            $note ="";
                                            if (isset($donnees2['note'])) {
                                               $note = " (".$donnees2['note'].")";
                                            }
                                            
                                            ?>
                                          <li class="list-group-item text-left"><span class="w3-badge w3-blue w3-margin-right"><b><?php echo $l; ?></b></span><?php echo $donnees2['description_date'].$note." By ".$donnees2['user_mvt']; ?><span class="w3-badge w3-right w3-margin-right <?php echo $color;?>"><?php echo $sign.$donnees2['qt']; ?></span></li>
                                        <?php
                                        $l=$l-1;
                                        }
                                        $qpv_rec->closeCursor();
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
                    </tr>
                    <?php
                    $no=$no+1;
                }
                $qsm->closeCursor();
     ?>
        <tr>
            <td class="text-right" colspan="6"><span style="font-weight: bold">TOTAL GAIN/PERTE</span></td>
            <td class="text-right"><span style="font-weight: bold"><?php echo $TL;?></span></td>  
            <td class="text-right"></td>
        </tr>
        </tbody>
    <?php
    }
   $qpv0->closeCursor();
?>
        </table>
        </div>
<br>
<br>
<script type="text/javascript">

</script>
  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
</body>
</html>