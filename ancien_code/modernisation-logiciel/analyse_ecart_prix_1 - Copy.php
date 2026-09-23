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
  	$nom_client_fournisseur ='Ambato_Tantely';
	$selected='0';
  if (isset($_COOKIE['point_de_vente'])) 
    {$nom_client_fournisseur = $_COOKIE['point_de_vente'];
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
  }
 ?>
</body>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<h2 id="ancre1" class="text-center text-danger">PRIX INCONFORME AU PRIX OFFICIEL A VERIFIER</h2>
<h4 id="ancre1" class="text-center">LES VENTES <?php echo $nom_client_fournisseur;?></h4>
<div class="text-center">
<form role="form" action="analyse_prix.php" method="post">
  <div>
    <select class="btn btn-warning" name="point_de_vente">
    <option value="none" selected disabled hidden>Choose Point de vente</option>
      <option value="Ambaibo_Tole" <?php  if($selected == '3'){echo("selected");}?>>Ambaibo_Tole</option>
      <option value="Amparafa" <?php if($selected == '4'){echo("selected");}?>>Amparafa</option>
      <option value="Ambato_Tantely" <?php if($selected == '5'){echo("selected");}?>>Ambato_Tantely</option>
      <option value="Ambato_veve_photo" <?php if($selected == '6'){echo("selected");}?>>Ambato_veve_photo</option>
      <option value="Bejofo" <?php if($selected == '7'){echo("selected");}?>>bejofo</option>
      <option value="Soalazaina" <?php if($selected == '8'){echo("selected");}?>>Soalazaina</option>
      <!--
      <option value="Ambaibo_Electronique" <?php if($selected == '1'){echo("selected");}?>>Ambaibo_Electronique</option>
      <option value="Ambaibo_loko" <?php if($selected == '2'){echo("selected");}?>>Ambaibo_loko</option>
    -->
    </select>
    <button type="submit" class="btn btn-success">show</button>
    <br>
    <br>
  </div>
  <br>
    <div class="text-center">
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
        
        <label>Show with previews nonconforming</label>
    </div>
</form>
</div>
<?php
//Connect to BD
include('connect.php');
    $point_de_vente = $nom_client_fournisseur;
   //Query to liste searched product
   $query_stock_inventaire = "SELECT * FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='vente' GROUP BY id_x;";
    $q = $bdd->prepare($query_stock_inventaire);

    $q->execute(array($point_de_vente));
    //Number of Line
    $nb_line=$q->rowCount ();   
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
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
    $query_elementaire = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt='vente' ORDER BY id_mvt";
    $query_elementaire = $bdd->prepare($query_elementaire);
    $query_elementaire->execute(array($donnees['id_x'],$point_de_vente));
    //DETECT IF INCONFORM EXIST
    $query_incoform = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt='vente' AND numero_commande_stock > ? ORDER BY id_mvt";
    $query_incoform = $bdd->prepare($query_incoform);
    $query_incoform->execute(array($donnees['id_x'],$point_de_vente,$max));
    $nb_line_incoform = $query_incoform->rowCount ();
    $query_incoform ->closeCursor();
    //-----------------------------
    } else {
    $query_elementaire = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt='vente' AND numero_commande_stock > ? ORDER BY id_mvt";
    $query_elementaire = $bdd->prepare($query_elementaire);
    $query_elementaire->execute(array($donnees['id_x'],$point_de_vente,$max));
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

    if ($detecteur == 'yes') {
    //QUERY TO SHOW STOCK
       $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'stock' ORDER BY id_mvt";
        $qs = $bdd->prepare($query_stock);
        $qs->execute(array($donnees['id_x'],$point_de_vente));
    //QUERY to get each point de vente
        $query_stock_pv = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'stock' ORDER BY id_mvt";
        $qpv = $bdd->prepare($query_stock_pv);
        $qpv->execute(array($donnees['id_x'],$point_de_vente));
    //QUERY to get each point de vente
        $query_stock_pv = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'stock' ORDER BY id_mvt";
        $qpv3 = $bdd->prepare($query_stock_pv);
        $qpv3->execute(array($donnees['id_x'],$point_de_vente));
    //----------------------------------------------------
    //QUERY TO SHOW VENTE
       $query_stock_vente = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'vente' ORDER BY id_mvt";
        $qv = $bdd->prepare($query_stock_vente);

        $qv->execute(array($donnees['id_x'],$point_de_vente));
        //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
        //QUERY to get each point de vente
        $query_stock_pv2 = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'vente' ORDER BY id_mvt";
        $qpv2 = $bdd->prepare($query_stock_pv2);
        $qpv2->execute(array($donnees['id_x'],$point_de_vente));
      //----------------------------------------------------
        $state_suppr = "";
        $color = 'text-success';
        if ($ecart < 0) {
            $color = 'text-danger';
        }
                    
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#ancre1"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td class="text-right"><?php echo number_format($ref_prix,0, "", ","); ?></td>
        <td class="text-right"><?php echo number_format($pu,0, "", ","); ?></td>
        <td class="text-right <?php echo $color; ?>"><b><?php echo number_format($ecart,0, "", ","); ?></b></td>
        <td><?php echo $flag;?></td>
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
                        <label><b>STOCK</b></label>
                        <ul class="list-group">
                        <?php
                        $total_stock = 0;
                        while ($donnees1 = $qs -> fetch())
                        { 
                            $total_stock = $total_stock + $donnees1['qt'];
                            ?>
                          <li class="list-group-item"><?php echo $donnees1['nom_client_fournisseur']; ?> | <?php echo $donnees1['description_date']; ?> | <?php echo $donnees1['user_mvt']; ?> | <b><?php echo $donnees1['prix_unitaire']; ?></b><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item"><b>
                            GRAND TOTAL</b><span class="w3-badge w3-right w3-margin-right"><?php echo number_format($total_stock,2); ?></span></li>
                        </ul>
                        </div>
                        <div class="form-group">
                        <label><b>VENTE</b></label>
                        <ul class="list-group">
                        <?php
                        $total_vente = 0;
                        while ($donnees2 = $qv -> fetch())
                        { 
                            $total_vente = $total_vente + $donnees2['qt'];

                            ?>
                          <li class="list-group-item"><?php echo $donnees2['nom_client_fournisseur']; ?> | <?php echo $donnees2['description_date']; ?> | <?php echo $donnees2['user_mvt']; ?> | <b><?php echo $donnees2['prix_unitaire']; ?></b><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ABS($donnees2['qt']); ?></span></li>
                        <?php
                        }
                        $qv->closeCursor();
                        ?>
                        <li class="list-group-item"><b>
                        GRAND TOTAL
                        </b><span class="w3-badge w3-right w3-margin-right"><?php echo number_format(ABS($total_vente),2); ?></span></li>
                        </ul>
                        </div>
                        <div class="form-group">
                        <label><b>RESTE STOCK</b></label>
                        <ul class="list-group">
                            <li class="list-group-item"><b>GRAND TOTAL</b><span class="w3-badge w3-right w3-margin-right w3-deep-purple"><?php echo number_format(($total_stock+$total_vente),2); ?></span></li>
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
<br>
<br>
    <br>
    <br>
 <?php
    }
 $q->closeCursor();
 
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