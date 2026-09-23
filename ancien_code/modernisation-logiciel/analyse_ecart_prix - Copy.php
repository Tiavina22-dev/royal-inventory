<!DOCTYPE html>
<html>
<head>
    <title>Vente Ambato Tantely</title>
    <!---add bootstrap css--->
    <script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php");
 //Get point de vente name
  	$nom_client_fournisseur ='';
	$selected='0';
    
  if (isset($_COOKIE['point_de_vente'])) 
    {$nom_client_fournisseur = $_COOKIE['point_de_vente'];
    /*
    switch($nom_client_fournisseur){
        case "Ambaibo_Electronique":
            $selected='1';
            break;
        case "Ambaibo_loko":
            $selected='2';
            break;
        case "Ambaibo_Tole":
            $selected='3';
            break;
        case "Amparafa":
            $selected='4';
            break;
        case "Ambato_Tantely":
            $selected='5';
            break;
        case "Ambato_veve_photo":
            $selected='6';
            break;
        case "Bejofo":
            $selected='7';
            break;
        case "Soalazaina":
            $selected='8';
            break;
        default:
            $selected='0';
            break;
    }
    
  //echo $nom_client_fournisseur;
  //echo $selected;
  */
  }
  $starting_date = "2022-06-01";
  if (isset($_COOKIE['starting_date'])) 
    {$starting_date = $_COOKIE['starting_date'];}
  
 ?>
</body>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<br>
<h2 id="ancre1" class="text-center text-danger">PRIX NON CONFORME AU PRIX OFFICIEL</br><?php echo strtoupper($nom_client_fournisseur); ?></h2>
<br>
 <div class="text-center">
    <button class="btn btn-secondary" data-toggle="modal" data-target="#Demarrer">CRITERE</button>
</div>
<br>
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><b>CRITERE DE L'ANALYSE DU PRIX</b></h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
        </div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="analyse_prix.php" method="post">
    <!------------------AUTO LISTE SHOP------------------>
    <div class="container border border-warning">
    <div class="form-group">
        <label><b>DEBUT DU JOURNAL ANALYSE</b></label>
        <input class="form-control btn-success" value= <?php echo $starting_date; ?> name="starting_date" type="DATE">
    </div>
    <div class="form-group">
        <label><b>POINT DE VENTE</b></label>
        <select class="form-control btn btn-warning" name="point_de_vente">
        <option value="" selected disabled hidden>Choisir Point de vente</option>
        <?php
        $query_shop = "SELECT * FROM shop ORDER BY long_name;";
        $query_shop = $bdd->prepare($query_shop);
        $query_shop->execute(array());
        while ($donnees = $query_shop -> fetch())
        {
        ?>
        <option value=<?php echo $donnees['short_name']; ?>><?php echo $donnees['long_name']; ?></option>
        <?php
        }
        ?>
        </select>
    </div>
    <!------------------END AUTO LISTE SHOP------------------>
    <div class="">
        <?php
        if (isset($_COOKIE['checkbox'])){
        ?>
        <input type="checkbox" name="checkbox" checked>
        <?php
        }else{
        ?>
        <input type="checkbox" name="checkbox">
        <?php
        }
        ?>
        
        <label><b>AFFICHER TOUS LES PRIX INSTABLES</b></label>
    </div>
    </div>
    <br>
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
if (isset($_COOKIE['point_de_vente'])){
    $point_de_vente = $nom_client_fournisseur;
   //Query to liste searched product
   $query_stock_inventaire = "SELECT * FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt ='vente' AND Date_du_Journal_mvt >= ? GROUP BY id_x;";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array($point_de_vente, $starting_date));
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<div>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>No</th>
        <th>NOM DE PRODUIT</th>
        <th>CODE</th>
        <th class="text-right">PU REF</th>
        <th class="text-right">PU</th>
        <th class="text-right">ECART</th>
        <th>FLAG</th>
        <?php
        if (isset($_COOKIE['checkbox'])){
        ?>
        <th>BY</th>
        <?php
        }
        ?>
        <th></th>
        <th></th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
//GET VALUE FROM CHECKING IF EXIST
    $nb_line_max = 0;
    $max = 0;
    $checker = 'NO';
    $query_max = "SELECT * FROM checking WHERE id_x = ? AND shop = ? AND checking_type = 'prix_different' AND value = (SELECT MAX(value) FROM checking WHERE id_x = ? AND shop = ? AND checking_type = 'prix_different');";
    $query_max = $bdd->prepare($query_max);
    $query_max->execute(array($donnees['id_x'],$point_de_vente,$donnees['id_x'],$point_de_vente));
    $nb_line_max = $query_max->rowCount ();
    if ($nb_line_max > 0) {
    $result = $query_max -> fetch();
    $max = $result['value'];
    }
    $query_max->closeCursor();
//GET LIST OF RESPONSIBLE
    $query_resp = "SELECT * FROM checking WHERE id_x = ? AND shop = ? AND checking_type = 'prix_different';";
    $query_resp = $bdd->prepare($query_resp);
    $query_resp->execute(array($donnees['id_x'],$point_de_vente));
    $nb_line_resp = $query_resp->rowCount ();
    if ($nb_line_resp > 0) {
       $checker = ''; 
    }
    while ($result_r = $query_resp -> fetch())
    {
       $checker = $result_r['responsable'].'::'.$result_r['checking_date'].'<br>'.$checker;  
    }
    $query_resp ->closeCursor();

//TEST SI IL Y AVAIT DIFFERENT
    //PRIX DE REFERENCE
    $ref_prix = $donnees['prix_de_vente']+0;
    if ($point_de_vente == 'Ambato_Tantely') {
        $ref_prix = $donnees['pu_ambato_tantely']+0;
    }
    if ($point_de_vente == 'Amparafa') {
        $ref_prix = $donnees['pu_aparafa']+0;
    }
    if ($point_de_vente == 'Soalazaina') {
        $ref_prix = $donnees['pu_soalazaina']+0;
    }
    if (isset($_COOKIE['checkbox'])){ 
    $query_elementaire = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt='vente' AND Date_du_Journal_mvt >= ? ORDER BY id_mvt";
    $query_elementaire = $bdd->prepare($query_elementaire);
    $query_elementaire->execute(array($donnees['id_x'],$point_de_vente, $starting_date));
    //DETECT IF INCONFORM EXIST
               //TEST IF INCOFORM EXIST
        $logic = 'NO';
        $non_conform = '';
        $pu_ref_stock_list = '';
        $pu_list = '';
        $difference_list = '';
        $affected_list = 0 ;
        $query_incoform = "SELECT * FROM mvt WHERE nom_client_fournisseur  =  ? AND type_de_mvt = 'vente' AND id_x = ? AND Date_du_Journal_mvt >= ? ";
        $query_incoform = $bdd->prepare($query_incoform);
        $query_incoform->execute(array($point_de_vente,$donnees['id_x'],$starting_date));
        while ($data_incoform = $query_incoform -> fetch()){
            //GET PU REF FOR THIS DATE
            $sub_query_incoform = "SELECT MAX(prix_unitaire) as prix_unitaire,Date_du_Journal_mvt,qt,user_mvt,description_date FROM mvt WHERE nom_client_fournisseur  =  ? AND type_de_mvt = 'stock' AND id_x  =  ? AND Date_du_Journal_mvt = (SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE nom_client_fournisseur  =  ? AND type_de_mvt = 'stock' AND id_x  =  ? AND Date_du_Journal_mvt <= ?)";
            $sub_query_incoform = $bdd->prepare($sub_query_incoform);
            $sub_query_incoform->execute(array($point_de_vente,$donnees['id_x'],$point_de_vente,$donnees['id_x'],$data_incoform['Date_du_Journal_mvt']));
            $pu_ref_stock =0;
            while ($sub_data_incoform = $sub_query_incoform -> fetch())
            {
                $pu_ref_stock = $sub_data_incoform['prix_unitaire']+0;
                $date_ref_stock = $sub_data_incoform['description_date'];
                $qt_ref_stock = $sub_data_incoform['qt'];
                $user_mvt_ref_stock = $sub_data_incoform['user_mvt'];

            }
            $sub_query_incoform ->closeCursor();
            $difference = $data_incoform['prix_unitaire'] - $pu_ref_stock;
            if ($difference < 0) {
                //echo $donnees['nom_x'].' Vente '.$data_incoform['Date_du_Journal_mvt'].' '.$data_incoform['prix_unitaire'].' - STOCK '.$pu_ref_stock.' '.$date_ref_stock.' '.$difference.'<br>';
                $non_conform = "<li class='list-group-item'><b>STOCK REFERENCE :</b> ".$date_ref_stock." | ".$user_mvt_ref_stock." <span class = 'text-pink'><b>".$pu_ref_stock."</b></span><span class='w3-badge w3-right w3-margin-right w3-orange'>".$qt_ref_stock."</span><br><b>VENTE : </b>".$data_incoform['description_date']." | ".$data_incoform['user_mvt']."<span class = 'text-marron'> <b>".$data_incoform['prix_unitaire']."</b></span><span class='w3-badge w3-right w3-margin-right w3-green'>".ABS($data_incoform['qt'])."</span><br><span class = 'text-danger'><b>BANGA : ".$difference."</b></span></li><br>".$non_conform;
                $pu_ref_stock_list =number_format($pu_ref_stock,0, "", ",").'<br>'.$pu_ref_stock_list;
                $pu_list = number_format($data_incoform['prix_unitaire'],0, "", ",").'<br>'.$pu_list;
                $difference_list = number_format($difference,0, "", ",").'<br>'.$difference_list;
                $affected_list = $affected_list + 1;
                $logic = 'YES';
            }
            

        }
        $query_incoform ->closeCursor();
        //TEST IF INCONFORM ALREADY CHECKED
        $query_incoform = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt='vente' AND Date_du_Journal_mvt >= ? AND numero_commande_stock > ? ORDER BY id_mvt";
        $query_incoform = $bdd->prepare($query_incoform);
        $query_incoform->execute(array($donnees['id_x'],$point_de_vente,$starting_date,$max));
        $nb_line_incoform = $query_incoform->rowCount ();
        $query_incoform ->closeCursor();
    //-----------------------------
    } else {
    //DETECT IF INCONFORM EXIST
               //TEST IF INCOFORM EXIST
        $logic = 'NO';
        $non_conform = '';
        $pu_ref_stock_list = '';
        $pu_list = '';
        $difference_list = '';
        $affected_list = 0 ;
        $query_incoform = "SELECT * FROM mvt WHERE nom_client_fournisseur  =  ? AND type_de_mvt = 'vente' AND id_x = ? AND Date_du_Journal_mvt >= ? ";
        $query_incoform = $bdd->prepare($query_incoform);
        $query_incoform->execute(array($point_de_vente,$donnees['id_x'],$starting_date));
        while ($data_incoform = $query_incoform -> fetch()){
            //GET PU REF FOR THIS DATE
            $sub_query_incoform = "SELECT MAX(prix_unitaire) as prix_unitaire,Date_du_Journal_mvt,qt,user_mvt,description_date FROM mvt WHERE nom_client_fournisseur  =  ? AND type_de_mvt = 'stock' AND id_x  =  ? AND Date_du_Journal_mvt = (SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE nom_client_fournisseur  =  ? AND type_de_mvt = 'stock' AND id_x  =  ? AND Date_du_Journal_mvt <= ?)";
            $sub_query_incoform = $bdd->prepare($sub_query_incoform);
            $sub_query_incoform->execute(array($point_de_vente,$donnees['id_x'],$point_de_vente,$donnees['id_x'],$data_incoform['Date_du_Journal_mvt']));
            $pu_ref_stock =0;
            while ($sub_data_incoform = $sub_query_incoform -> fetch())
            {
                $pu_ref_stock = $sub_data_incoform['prix_unitaire']+0;
                $date_ref_stock = $sub_data_incoform['description_date'];
                $qt_ref_stock = $sub_data_incoform['qt'];
                $user_mvt_ref_stock = $sub_data_incoform['user_mvt'];

            }
            $sub_query_incoform ->closeCursor();
            $difference = $data_incoform['prix_unitaire'] - $pu_ref_stock;
            if ($difference < 0) {
                //echo $donnees['nom_x'].' Vente '.$data_incoform['Date_du_Journal_mvt'].' '.$data_incoform['prix_unitaire'].' - STOCK '.$pu_ref_stock.' '.$date_ref_stock.' '.$difference.'<br>';
                $non_conform = "<li class='list-group-item'><b>STOCK REFERENCE :</b> ".$date_ref_stock." | ".$user_mvt_ref_stock." <span class = 'text-pink'><b>".$pu_ref_stock."</b></span><span class='w3-badge w3-right w3-margin-right w3-orange'>".$qt_ref_stock."</span><br><b>VENTE : </b>".$data_incoform['description_date']." | ".$data_incoform['user_mvt']."<span class = 'text-marron'> <b>".$data_incoform['prix_unitaire']."</b></span><span class='w3-badge w3-right w3-margin-right w3-green'>".ABS($data_incoform['qt'])."</span><br><span class = 'text-danger'><b>BANGA : ".$difference."</b></span></li><br>".$non_conform;
                $pu_ref_stock_list =number_format($pu_ref_stock,0, "", ",").'<br>'.$pu_ref_stock_list;
                $pu_list = number_format($data_incoform['prix_unitaire'],0, "", ",").'<br>'.$pu_list;
                $difference_list = number_format($difference,0, "", ",").'<br>'.$difference_list;
                $affected_list = $affected_list + 1;
                $logic = 'YES';
            }
            

        }
        $query_incoform ->closeCursor();
        //TEST IF INCONFORM ALREADY CHECKED
    $query_elementaire = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt='vente' AND Date_du_Journal_mvt >= ? AND numero_commande_stock > ? ORDER BY id_mvt";
    $query_elementaire = $bdd->prepare($query_elementaire);
    $query_elementaire->execute(array($donnees['id_x'],$point_de_vente,$starting_date,$max));
    $nb_line_incoform = $query_elementaire->rowCount ();
    }
    
    $detecteur ='no';
    $ecart = 0;
    $pu = 0;
    while ($data = $query_elementaire -> fetch())
    {
        $flag = 'PREVIOUS';
        if ($ref_prix <> ($data['prix_unitaire']+0)) {
            $detecteur ='yes';
            $ecart = ($data['prix_unitaire']+0) - $ref_prix;
            $pu = $data['prix_unitaire']+0;
            $flag = "<span class = 'text-danger'>LATEST</span>";
        }
    }
    $query_elementaire->closeCursor();

    if ($detecteur == 'yes' AND $logic == 'YES') {
        $state_suppr = "";
        $color = 'text-danger';
                    
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-success"><?php echo $donnees['reference_x']; ?></td>
        <td class="text-right"><?php echo $pu_ref_stock_list; ?></td>
        <td class="text-right"><?php echo $pu_list; ?></td>
        <td class="text-right <?php echo $color; ?>"><b><?php echo $difference_list; ?></b></td>
        <td><?php echo $affected_list.' Affected';?></td>
        <?php
        if (isset($_COOKIE['checkbox'])){
        ?>
        <td><?php echo $checker; ?></td>
        <?php
        }
        ?>
                <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("no".$j); ?>"><img src="img/liste.png" height="30" width="30" background alt="Edit" /></a></td>
        <?php
        if ($nb_line_incoform > 0){
        ?>
        <td><a style="<?php echo $state_suppr; ?>;" class="center" title='VALIDER?' href="valider_different_prix.php?id_x=<?php echo $donnees['id_x']; ?>&shop=<?php echo $point_de_vente; ?>" onclick="confirmationDelete('Vous-avez accepter?');return false; post ;"><img src="img/valider.png" height="30" width="30" background alt="Edit"/></a></td>
        <?php
        } else {
        ?> 
        <td>OK</td>
        <?php
        }
        ?>
    </tr>
        <!-- modal form QUATITE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><b><?php echo $donnees['nom_x']; ?><br>PF:<?php echo $donnees['prix_fournisseur']; ?> | PU General:<?php echo $donnees['prix_de_vente']; ?> | PU Afa:<?php echo $donnees['pu_aparafa']; ?> | PU Tantely:<?php echo $donnees['pu_ambato_tantely']; ?> | PU Soalazaina:<?php echo $donnees['pu_soalazaina']; ?></b></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form">
                        <div class="form-group">
                        <ul class="list-group">
                        <?php
                        echo $non_conform;
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
        </tr>
<?php
$j = $j+1;
    }//end if detecteur

}
?>
        </tbody>
    </table>
</div>
<br>
<br>
    <br>
    <br>
 <?php
    }
 $q->closeCursor();
 }//END ISSET Point_de_vente
?>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
</html>