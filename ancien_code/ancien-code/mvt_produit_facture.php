<!DOCTYPE html>
<html>
<head>
    <title>FACTURE SEARCH</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css">
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
 <h2 class="text-center text-warning">RECHERCHE DANS LES FACTURES ENREGISTEES</h2>
</body>
<!--------------------------------------->
<form role="form" action="simple_search_mvt_facture.php" method="POST">
<div class="text-center">
<?php  
if (isset($_COOKIE['key_word'])) 
{
	$point_de_vente = "";

?>
<br>
<br>
<input type="search" class="btn btn-warning light-table-filter text-left" value="<?php echo $_COOKIE['key_word']?>" name="key_word" placeholder="Name/Ref/id_x">
<?php
}else{
?>
<br>
<br>
<input type="search" class="btn btn-warning light-table-filter text-left" name="key_word" placeholder="Name/Ref/id_x">
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

	//Query tous listeproduct
   $query_stock_negatif = "(SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE type_de_mvt = 'facture' GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?)) UNION ALL (SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, qt as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE type_de_mvt = 'facture') as resultante_table WHERE note LIKE ?) ;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array("%".$key_word."%", "%".$key_word."%","%".$key_word."%","%".$key_word."%"));
    //Number of Line
    $nb_line=$q->rowCount ();
?>
<br>
<br>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><span style='font-weight: bold'>"."[".$key_word."]"." does not exist on the base, Click <a href='stock_epuise.php'>RETOURS</span><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
	<div class="text-center">
		<span style='font-weight: bold'>Result for [<?php echo $key_word; ?>]</span>
	</div>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th></th>
        <th>CODE</th>
        <th>NOM DE PRODUIT</th>
        <th>DERNIER PRIX FOURNISSEUR</th>
        <th>QT</th>
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
    $pu_list="<span style='font-weight: bold'>".number_format(($donnees['prix_unitaire']+0),0, "", " ")."</span>";


	//------------------------------
	//QUERY TO SHOW FACTURE
	 
	if ($donnees['reference_x'] == 'XXXX') {
		//FOR XXXX
	     $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'facture' AND note LIKE ? ORDER BY Date_du_Journal_mvt DESC";
	     $qs = $bdd->prepare($query_stock);
		 $qs->execute(array($donnees['id_x'],$donnees['note']));
		 //QUERY to get each fournisseur
	    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'facture' AND note LIKE ?";
	    $qpv = $bdd->prepare($query_stock_pv);
	    $qpv->execute(array($donnees['id_x'],$donnees['note']));
    }else {
		$query_stock = "SELECT * FROM mvt WHERE id_x = ? AND type_de_mvt = 'facture' ORDER BY Date_du_Journal_mvt DESC";
		$qs = $bdd->prepare($query_stock);
		$qs->execute(array($donnees['id_x']));
		//QUERY to get each fournisseur
	    $query_stock_pv = "SELECT DISTINCT(nom_client_fournisseur) FROM mvt WHERE id_x = ? AND type_de_mvt = 'facture' ";
	    $qpv = $bdd->prepare($query_stock_pv);
	    $qpv->execute(array($donnees['id_x']));
          }
	
	//----------------------------------------------------
		//-------------------------------------
		$ref_id = $j;
		$text_id1 = 'text_id1'.$j;
		$text_id2 = 'text_id2'.$j;
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php
        if ($donnees['reference_x'] == 'XXXX') {
            echo $donnees['note'].' - ';
          }
        echo $donnees['nom_x']; ?></td>
        <td><?php echo $pu_list; ?></td>
        <td><?php echo number_format($donnees['sm'],2); ?></td>
        <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("no".$j); ?>"><img src="img/liste.png" height="30" width="30" background alt="Edit" /></a></td>
    </tr>
        <!-- modal form QUATITE AFFICHAGE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title"><span style='font-weight: bold'><?php echo $donnees['nom_x']; ?><br>PU REFERENCE : <?php echo $donnees['prix_fournisseur']; ?></span></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form">
                        <div class="form-group">
                        <label><span style='font-weight: bold'>FACTURE ENREGISTRER</span></label>
                        <ul class="list-group">
                        <?php
                        $total_stock = 0;
                        while ($donnees1 = $qs -> fetch())
                        { 
                            $total_stock = $total_stock + $donnees1['qt'];
                            ?>
                          <li class="list-group-item"><?php echo $donnees1['nom_client_fournisseur']; ?> | <?php echo $donnees1['description_date']; ?> | <?php
                          if ($donnees1['id_x'] == 134655) {
                            echo $donnees1['note'].' | ';
                          }
                          echo $donnees1['user_mvt']." | <span style='font-weight: bold'>".$donnees1['prix_unitaire']; ?></span><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item"><span style='font-weight: bold'>
                            <?php 
                             while ($data = $qpv -> fetch()) {
                            //QUERY to sum each point de vente
                            $query = "SELECT SUM(qt) as sm FROM mvt WHERE id_x = ? AND type_de_mvt = 'facture' AND nom_client_fournisseur = ?";
                            $qsm = $bdd->prepare($query);

                            $qsm->execute(array($donnees['id_x'],$data['nom_client_fournisseur']));
                            $data1 = $qsm -> fetch();
                            echo 'TL '.$data['nom_client_fournisseur'].'<span class="w3-badge w3-right w3-margin-right w3-grey">'.number_format($data1['sm'],2).'</span><br>';
                            $qsm->closeCursor();
                            }
                            $qpv->closeCursor();
                            ?>GRAND TOTAL</span><span class="w3-badge w3-right w3-margin-right"><?php echo number_format($total_stock,2); ?></span></li>
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