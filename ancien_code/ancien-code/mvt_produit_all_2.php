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
<form role="form" action="simple_search_mvt_all_2.php" method="POST">
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
   $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,pu_soalazaina FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?) ORDER BY nom_x;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array("%".$key_word."%", "%".$key_word."%","%".$key_word."%"));
} else {
	//Query for specifique point de vente
	//FOR key word tous
	if ($key_word == 'All product') {
		$query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,pu_soalazaina FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table ORDER BY nom_x;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($point_de_vente));
	} else {
   $query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,pu_soalazaina FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?) ORDER BY nom_x;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($point_de_vente,"%".$key_word."%", "%".$key_word."%","%".$key_word."%"));
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
   <table id="example2" class="table table-striped table-bordered dt-responsive nowrap btn-secondary" style="width:100%">
        <thead>
        <tr class="text-warning">
        <th>#</th>
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
		//---------PU-------------
	$prix = number_format($donnees['prix_de_vente'],0, "", " ");
    if ($point_de_vente=='Amparafa') {$prix = number_format($donnees['pu_aparafa'],0, "", " ");}
    if ($point_de_vente=='Ambato_Tantely') {$prix = number_format($donnees['pu_ambato_tantely'],0, "", " ");}
    if ($point_de_vente=='Soalazaina') {$prix = number_format($donnees['pu_soalazaina'],0, "", " ");}
    if ($prix == 0) {
    	$prix = '####';
    }
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-right"><?php echo $prix; ?></td>
        <td>
            <ul class="group">
                        <?php
                        $total_stock = 0;
                        while ($donnees1 = $qs -> fetch())
                        { 
                            $total_stock = $total_stock + $donnees1['qt'];
                            ?>
                          <li class="list-group-item text-danger"><?php echo $donnees1['description_date']; ?>#<?php echo $donnees1['qt']; ?></li>
                        <?php
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item text-dark"><b>
                            <?php 
                             while ($data = $qpv -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'stock' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            echo 'STOCK '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv->closeCursor();
                            ?></b></li>
                        </ul>
        </td>
        <td>
        <ul class="list-group">
                        <?php
                        $total_vente = 0;
                        while ($donnees2 = $qv -> fetch())
                        { 
                            $total_vente = $total_vente + $donnees2['qt'];

                            ?>
                          <li class="list-group-item text-danger"><?php echo $donnees2['description_date']; ?>#<?php echo ABS($donnees2['qt']); ?></li>
                        <?php
                        }
                        $qv->closeCursor();
                        ?>
                        <li class="list-group-item text-dark"><b>
                            <?php 
                             while ($data = $qpv2 -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT (SUM(qt)*(-1)) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'vente' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            echo 'VENTE '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv2->closeCursor();
                            ?></b>
                        </li>
                        </ul>
        </td>
        <td class="text-right"><?php echo number_format($donnees['sm'],2); ?></td>
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