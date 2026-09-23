<!DOCTYPE html>
<html>
<head>
    <title>Mouvement Produit</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/w3.css">
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
<form role="form" action="simple_search_mvt_report.php" method="POST">
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
		</select>
	</div>
<br>
<input type="search" class="light-table-filter" name="key_word" placeholder="Vide Pour Afficher Tous">
<?php
}
?>
<button type="submit" class="btn btn-info">Search</button>
</div>
</form>
<?php
include('connect.php');
    $key_word = "";
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
	//Query tous listeproduct
   $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x GROUP BY id_x ORDER BY reference_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?);";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array("%".$key_word."%", "%".$key_word."%","%".$key_word."%"));
} else {
	if ($key_word == 'All product') {
		$query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,pu_soalazaina FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table ORDER BY nom_x;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($point_de_vente));
	} else {
	//Query for specifique point de vente
   $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x ORDER BY reference_x) as resultante_table WHERE ((nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?));";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($point_de_vente,"%".$key_word."%", "%".$key_word."%","%".$key_word."%"));
    }
}
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<br>
<br>
<h4 class="text-center">MOUVEMENT DES PRODUITS</h4>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><b>"."[".$key_word."]"." does not exist on the base, Click <a href='stock_epuise.php'>RETOURS</b><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div class="text-center">
		<b>Result for [<?php echo $key_word; ?>]</b>
	</div>
   <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th>NOM DE PRODUIT</th>
        <th class="text-right">Stock</th>
        <th class="text-right text-primary">Vente</th>
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
	   $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' ";
	    $qs = $bdd->prepare($query_stock);
	    $qs->execute(array($donnees['id_x']));
	//QUERY to get each point de vente
	    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' ";
	    $qpv = $bdd->prepare($query_stock_pv);
	    $qpv->execute(array($donnees['id_x']));
	//QUERY to get each point de vente
	    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' ";
	    $qpv3 = $bdd->prepare($query_stock_pv);
	    $qpv3->execute(array($donnees['id_x']));
	//----------------------------------------------------
	//QUERY TO SHOW VENTE
	   $query_stock_vente = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' ";
	    $qv = $bdd->prepare($query_stock_vente);

	    $qv->execute(array($donnees['id_x']));
	    //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
        //TL VENTE
        $query_all_vente = "SELECT SUM(qt) as vqt FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' ";
        $qv_all = $bdd->prepare($query_all_vente);
        $qv_all->execute(array($donnees['id_x']));
        $data_vente = $qv_all -> fetch();
        $total_vente1 = $data_vente['vqt']+0;
        $qv_all->closeCursor();
	    //QUERY to get each point de vente
	    $query_stock_pv2 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' ";
	    $qpv2 = $bdd->prepare($query_stock_pv2);
	    $qpv2->execute(array($donnees['id_x']));
	  //----------------------------------------------------
} else {
	//---------------SPECIFIC POINT DE VENTE---------------
	//QUERY TO SHOW STOCK
	   $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
	    $qs = $bdd->prepare($query_stock);
	    $qs->execute(array($donnees['id_x'],$point_de_vente));
	//QUERY to get each point de vente
	    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
	    $qpv = $bdd->prepare($query_stock_pv);
	    $qpv->execute(array($donnees['id_x'],$point_de_vente));
	//QUERY to get each point de vente
	    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ? ";
	    $qpv3 = $bdd->prepare($query_stock_pv);
	    $qpv3->execute(array($donnees['id_x'],$point_de_vente));
	//----------------------------------------------------
	//QUERY TO SHOW VENTE
	   $query_stock_vente = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
	    $qv = $bdd->prepare($query_stock_vente);

	    $qv->execute(array($donnees['id_x'],$point_de_vente));
	    //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
        //TL VENTE
        $query_all_vente = "SELECT SUM(qt) as vqt FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
        $qv_all = $bdd->prepare($query_all_vente);
        $qv_all->execute(array($donnees['id_x'],$point_de_vente));
        $data_vente = $qv_all -> fetch();
        $total_vente1 = $data_vente['vqt']+0;
        $qv_all->closeCursor();
	    //QUERY to get each point de vente
	    $query_stock_pv2 = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
	    $qpv2 = $bdd->prepare($query_stock_pv2);
	    $qpv2->execute(array($donnees['id_x'],$point_de_vente));
	  	//----------------------------------------------------
}
		//-------------------------------------
		$ref_id = $j;
		$text_id1 = 'text_id1'.$j;
		$text_id2 = 'text_id2'.$j;
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-right"><b><?php echo number_format($donnees['sm'],0); ?></b></td>
        <td class="text-right text-primary"><?php echo number_format(ABS($total_vente1),0); ?></td>
    </tr>
        <!-- modal form QUATITE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><b><?php echo $donnees['nom_x']; ?><br>PF:<?php echo $donnees['prix_fournisseur']; ?> | PU General:<?php echo $donnees['prix_de_vente']; ?> | PU Afa:<?php echo $donnees['pu_aparafa']; ?> | PU Tantely:<?php echo $donnees['pu_ambato_tantely']; ?></b></h4>
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
                          <li class="list-group-item"><?php echo $donnees1['nom_client_fournisseur']; ?> | <?php echo $donnees1['description_date']; ?> | <b><?php echo $donnees1['prix_unitaire']; ?></b><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item"><b>
                            <?php 
                             while ($data = $qpv -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv->closeCursor();
                            ?>GRAND TOTAL</b><span class="w3-badge w3-right w3-margin-right"><?php echo number_format($total_stock,2); ?></span></li>
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
                          <li class="list-group-item"><?php echo $donnees2['nom_client_fournisseur']; ?> | <?php echo $donnees2['description_date']; ?> | <b><?php echo $donnees2['prix_unitaire']; ?></b><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ABS($donnees2['qt']); ?></span></li>
                        <?php
                        }
                        $qv->closeCursor();
                        ?>
                        <li class="list-group-item"><b>
                            <?php 
                             while ($data = $qpv2 -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv2->closeCursor();
                            ?>GRAND TOTAL
                        </b><span class="w3-badge w3-right w3-margin-right"><?php echo number_format(ABS($total_vente),2); ?></span></li>
                        </ul>
                        </div>
                        <div class="form-group">
                        <label><b>RESTE STOCK</b></label>
                        <ul class="list-group">
                            <?php 
                             while ($data = $qpv3 -> fetch()) {
                            //QUERY to sum each vente by point de vente
                            $query = "SELECT SUM(qt) as sm_v FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            $vente = $data1['sm_v'];
                            $qsm->closeCursor();
                            //QUERY to sum each stock by point de vente
                            $query = "SELECT SUM(qt) as sm_s FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);
                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            $stock = $data1['sm_s'];
                            $qsm->closeCursor();
                            echo '<li class="list-group-item">TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-purple">'.number_format(($stock+$vente),2).'</span></li>';
                            $qsm->closeCursor();
                            }
                            $qpv3->closeCursor();
                            ?>
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
<!-- modal form FIND AND REPLACE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no_replace".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><b class="text-primary">AUTOMATIQUE REMPLACEMENT | <span class="text-danger">HAMARINO TSARA!!!</span></b></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form" action="modifier_mvt_vente_find_replace.php" method="post">
                        <div class="form-group">
                        <label><b class="text-danger">PRODUIT:</b> <?php echo ($donnees['nom_x']); ?></label>
                        </div>
                        <div class="form-group">
                        <label><b class="text-danger">POINT DE VENTE:</b> <?php echo ($point_de_vente); ?></label>
                        </div>
                        <div class="form-group">
                        <label><b>REMPLACER PAR (REFERENCE EN MAJUSCULE)</b></label>
                        <p class="text-secondary float-right" id="<?php echo $text_id1; ?>"></p>
                        </div>
                        <div class="form-group">
						<input class="form-control" name="reference_x" value="<?php echo htmlspecialchars($donnees['reference_x']); ?>" type="text" id="<?php echo $ref_id; ?>" oninput="referenceFunction($(this));">
						<p class="text-secondary float-right" id="<?php echo $text_id2; ?>"></p>
						</div>
						<input type="hidden" name="actual_id" value="<?php echo $donnees['id_x']; ?>">
						<input type="hidden" name="nom_client_fournisseur" value="<?php echo $point_de_vente; ?>">
						<div class="form-group">
							<button type="submit" class="btn btn-danger" onclick="confirmationDelete('Are You Sure?');return false; post ;">REMPLACER</button>
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
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
<script  src="js/confirmation.js"></script>
</html>