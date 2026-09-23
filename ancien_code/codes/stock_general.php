<!DOCTYPE html>
<html>
<head>
	<title>Gerer Article</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/pourcentage.css" rel="stylesheet">
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
  

</head>
<body style="background: #6c757d">
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<h4 class="text-center">MODIFIER/SUPRIMER ARTICLE</h4>
<?php
//Connect to BD
include('connect.php');
//Nombre OF ALL product
$nb_all_product= 0;
$query_product_all = 'SELECT * FROM produit';
	$q = $bdd->prepare($query_product_all);

	$q->execute(array());
	$nb_all_product=$q->rowCount ();
	$q->closeCursor();
  //Number of photo vide or NULL
  $query_img_null = "SELECT * FROM produit WHERE img_path_x IS NULL OR img_path_x = ''";
  $q = $bdd->prepare($query_img_null);
  $q->execute(array());
  $nb_null_img=$q->rowCount ();
  $q->closeCursor();
//Number of prix fournisseur zero or NULL
$query_pf_null = 'SELECT * FROM produit WHERE prix_fournisseur IS NULL OR prix_fournisseur = 0';
	$q = $bdd->prepare($query_pf_null);
	$q->execute(array());
	$nb_null_prix_fournisseur=$q->rowCount ();
	$q->closeCursor();
	//Number of prix prix de vente zero or NULL
$query_pv_null = 'SELECT * FROM produit WHERE prix_de_vente IS NULL OR prix_de_vente = 0';
	$q = $bdd->prepare($query_pv_null);
	$q->execute(array());
	$nb_null_prix_pv=$q->rowCount ();
	$q->closeCursor();
//Calule pourcentage
	$avec_prix_fournisseur = (($nb_all_product - $nb_null_prix_fournisseur)*100)/$nb_all_product;
	$avec_prix_pv = (($nb_all_product - $nb_null_prix_pv)*100)/$nb_all_product;
	$quatremille = (($nb_all_product)*100)/10000;
  $avec_img = (($nb_all_product - $nb_null_img)*100)/$nb_all_product;
//QUERY DESTINY FOR AUTO DETECT DUPLICATE
	$query_num_stock = $bdd->query('SELECT reference_x FROM produit');
	$u = 0;
	while ($reference_x = $query_num_stock -> fetch())
{
	$u = $u + 1;
	$ref[$u] = $reference_x['reference_x'];
	//echo $reference_x['reference_x'];
}
	//$u = 2;
	$query_num_stock ->closeCursor();
?>
<!------------SIMPLE SEARCH------------------>
<!--------CERCLE POURCENTAGE----------------->
<br>
<br>
<br>
<br>
<div class="flex-wrapper">
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart rose">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="<?php echo $avec_prix_pv;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($avec_prix_pv);?>%</text>
      <text x="18" y="24.35" class="pv">PRIX UNITAIRE</text>
      <text x="18" y="27.35" class="fournisseur">(<?php echo ($nb_all_product-$nb_null_prix_pv).'/'.$nb_all_product;?>)</text>
    </svg>
  </div>
  
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart rose">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="<?php echo $avec_prix_fournisseur;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($avec_prix_fournisseur);?>%</text>
      <text x="18" y="24.35" class="jaune">Prix Fournisseur</text>
      <text x="18" y="27.35" class="fournisseur">(<?php echo ($nb_all_product-$nb_null_prix_fournisseur).'/'.$nb_all_product;?>)</text>
    </svg>
  </div>
    <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart rose">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="<?php echo $avec_img;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($avec_img);?>%</text>
      <text x="18" y="24.35" class="photo">PHOTO</text>
      <text x="18" y="27.35" class="fournisseur">(<?php echo ($nb_all_product-$nb_null_img).'/'.$nb_all_product;?>)</text>
    </svg>
  </div>

  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart rose">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="<?php echo $quatremille;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="19.35" class="percentage"><?php echo intval($quatremille);?>%</text>
      <text x="18" y="24.35" class="jaune">ARTICLE</text>
      <text x="18" y="27.35" class="fournisseur"><?php echo $nb_all_product;?>/10000</text>
    </svg>
  </div>
</div>
<!-------FIN CERCLE POURCENTAGE---------------------->
<?php
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
}
if (isset($_COOKIE['img_msg'])) 
{
   $img_msg=$_COOKIE['img_msg'];
?>
<h5 class="text-center text-danger"><b><?php echo $img_msg; ?></b></h5>
<?php } //if cookies img_msg 
$j = 1;
?>
<form role="form" action="simple_search_x.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" name="key_word" value="<?php echo $key_word?>" placeholder="Name/Code/Search">
<button type="submit" class="btn btn-info">Search</button>
<button id="nouveau" type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_new">Nouveau</button>
<!------------<input class="btn btn-light" type="password" oninput="unlock($(this));" placeholder="">----------------->
</div>
</form>
<!---------------search result---------------------------->
<?php
if (isset($_COOKIE['key_word'])) 
{
   //echo $key_word;
   //Query to liste searched product
   $query_product_search = 'SELECT * FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? OR id_x LIKE ? ORDER BY nom_x';
	$q = $bdd->prepare($query_product_search);

	$q->execute(array("%".$key_word."%", "%".$key_word."%", "%".$key_word."%"));
	//Number of Line
	$nb_line=$q->rowCount ();	
	if ($nb_line == 0) {
		; ?>
  <div class="text-center">
  <h5 class="animated fadeIn mb-4"><blockquote class="container"><p class="mb-0"><?php echo "<br><b>"."[".$key_word."]"."does not exist on the base"; ?></p></blockquote></h5></div>
<?php	} else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table class="table-info table container">
        <thead class="btn-success text-dark">
        <tr>
        <th></th>
        <th class="text-pink">ID</th>
        <th>Nom/Description de Produit</th>
        <th>Prix</th>
        <th class="text-pink">Ref ID</th>
        <th>Note</th>
        <th>by</th>
        <th colspan="2">Modifier/suprimer</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$image_path_x = "img_x/default_x.png";
//Query searcher word
while ($donnees = $q -> fetch())
{  
$ref_id = $j;
//echo $ref_id; 
$text_id1 = 'text_id1'.$j;
$text_id2 = 'text_id2'.$j;

//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }
//PREVENT DELETION PRODUCT WHO HAVE MVT
     //TABLE MVT
     $query_mvt = 'SELECT * FROM mvt WHERE id_x = ?;';
      $query_mvt = $bdd->prepare($query_mvt);
      $query_mvt->execute(array($donnees['id_x']));
      //Number of Line
      $nb_line_mvt = $query_mvt->rowCount ();
      $query_mvt -> closeCursor();
      //TABLE stock_prep
     $query_stock_prep = 'SELECT * FROM stock_prep WHERE id_x = ?;';
      $query_stock_prep = $bdd->prepare($query_stock_prep);
      $query_stock_prep->execute(array($donnees['id_x']));
      //Number of Line
      $nb_line_stock_prep = $query_stock_prep->rowCount ();
      $query_stock_prep -> closeCursor();
      //TABLE commande
     $query_commande = 'SELECT * FROM commande WHERE id_x = ?;';
      $query_commande = $bdd->prepare($query_commande);
      $query_commande->execute(array($donnees['id_x']));
      //Number of Line
      $nb_line_commande = $query_commande->rowCount ();
      $query_commande -> closeCursor();
//SOMME DES 3
      $nb_line_mvt = $nb_line_mvt+$nb_line_stock_prep+$nb_line_commande;
//--------------------------------------
      $state_button ="disabled";
      $state_suppr = "pointer-events: none";
      $id_m = $j.'M';
      $id_s = $j.'S';
      $j = $j+1;
?>
<!------------------------------------------------->
        <tr class="bg-dark text-warning">
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td class="text-secondary"><?php echo $donnees['id_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td class="text-pink"><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['note_x']; ?></td>
        <td><?php echo $donnees['user_x']; ?></td>
        
		<td><button id="<?php echo $id_m; ?>" type="button" class="text-center" data-toggle="modal" data-target="#<?php echo "no".$donnees['id_x']; ?>"><img src="img/edit_icon.png" height="30" width="30" background alt="Edit" /></button></td>
		<td>
      <?php if ($nb_line_mvt == 0) { ?>
      <a id="<?php echo $id_s; ?>" style="<?php echo $state_suppr; ?>" class="center" href="delete_produit.php?id_x=<?php echo $donnees['id_x']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a>
      <?php }; ?>
    </td>
</tr>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id_x']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier stock No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_produit.php" method="post">
						<div class="form-group">
						<label>Nom/Details</label>
						<input class="form-control" name="nom_x" value="<?php echo htmlspecialchars($donnees['nom_x']); ?>" type="text">
						</div>
						<div class="form-group">
						<input class="form-control" name="prix_de_vente" value="<?php echo $donnees['prix_de_vente']; ?>" type="hidden">
						<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
						</div>
						<div class="form-group">
						<label>Reference ID (EN MAJUSCULE)</label>
						<p class="text-secondary float-right" id="<?php echo $text_id1; ?>"></p>
						</div>
						<div class="form-group">
						<input class="form-control" name="reference_x" value="<?php echo htmlspecialchars($donnees['reference_x']); ?>" type="text" id="<?php echo $ref_id; ?>" oninput="referenceFunction($(this));">
						<p class="text-secondary float-right" id="<?php echo $text_id2; ?>"></p>
						</div>
						<div class="form-group">
						<label>Note/Fanamarihana (raha ilaina)</label>
						<input class="form-control" name="note_x" value="<?php echo $donnees['note_x']; ?>" type="text">
						</div>
						<button type="submit" class="btn btn-success">Valider</button>
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
          <h4 class="modal-title">Modifier Image du Produit No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="modifier_image_produit.php" enctype="multipart/form-data" method="post">
            <div class="form-group text-center">
              <img src="<?php echo $image_path_x;?>" height=100% width=100% align="middle"/>
            </div>
            <div class="form-group">
            <label><b>MODIFY IMAGE?</b></label>
            <input class="btn btn-warning form-control" name="uploadedimage" type="file">
            <input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
            </div>
            <div class="form-group">
            <button type="submit" name="Upload Now" class="btn btn-success">Save Image</button>
            <?php if (strlen($donnees['img_path_x']) != 0) {
            ?>
            <a class="btn btn-danger float-right" href="delete_img_produit.php?id_x=<?php echo $donnees['id_x']; ?>">Remove Image</a>
            <?php
            }
            ?>
            </div>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
<?php
}
?>
        </tbody>
    </table>
    <br>
    <br>
 <?php
	}
 $q->closeCursor();
 }
?>
<br>
<br>
<br>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->
<!-------FORM DE NOUVEAU-------->
    <!-- modal form NOUVEAU-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_new" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Nouveau Produit</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form id="form_prix" role="form" action="nouveau_produit.php" enctype="multipart/form-data" method="post">
            <div class="form-group">
            <div class="form-group">
            <label>Nom/Description de produit</label>
            <input class="form-control" name="nom_x" placeholder="Ex: Huile 90" type="text">
            </div>
            <div class="form-group">
            <label>Reference ID (EN MAJUSCULE)</label>
            <p class="text-secondary float-right" id="warning_msg"></p>
          </div>
          <div class="form-group">
            <input class="form-control" id="reference_x" name="reference_x" placeholder="Unique Ex: Fer 6 --> F06" type="text">
          </div>
            <label>Prix Fournisseur</label>
            <p class="text-secondary float-right" id="warning_msg2"></p>
            <input class="form-control" name="fournisseur" value=0 type="number" step="any" id="fournisseur">
            </div>
            <div class="form-group float-left">
            <label>Pourcentage (%)</label>
            <!-------------------------------->

            <!--------------------------------->
            <input class="form-control" name="pourcentage" value=0 type="number" step="any" id="pourcentage">
            </div>
            <div class="form-group  float-right">
            <label>Benefice (Ariary)</label>
            <input class="form-control" name="benefice" type="number" step="any" value=0 id="benefice_id">
            </div>
            <div class="form-group">
            <label>Prix Unitaire (Ariary)</label>
            <input class="form-control" name="prix_de_vente" value=0 type="number" id="prix_de_vente">
            <input type="hidden" name="id_x" value=0>
            </div>
            <div class="form-group">
            <label>Note/Fanamarihana (raha ilaina)</label>
            <input class="form-control" name="note_x" value="" type="text">
            </div>
            <div class="form-group">
            <label><b>BROWSE IMAGE FILE</b></label>
            <input class="btn btn-warning form-control" name="uploadedimage" type="file">
            </div>
            <button type="submit" class="btn btn-success">Valider</button>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
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
      		msg="Efa Misy";
      	} else {
      		//alert("nook");
      		//color = "green";
      	}
      	
      }
      //write notification on div id=warning_msg ;
      if (msg=="Efa Misy") {
      	color = "red";
      } else {
      	color = "green";
      	msg="Mety Tsara";
      }
     $("#"+text_id1).text(msg);
     $("#"+text_id2).text(msg);
     document.getElementById(input).style.borderColor = color;
}
</script>
<script>
$(document).ready(function(){
    $("#reference_x").on("input", function(){
    //----------------------
      var y = $(this).val();
      var jArray = [];
      var u = <?php echo json_encode($u); ?>;
      var msg;
      var color;
      jArray = <?php echo json_encode($ref); ?>;
       document.getElementById("reference_x").style.borderColor =  "green";
      msg="";
      for(var i=1; i<=u; i++)
      { //alert(jArray[i]);

        if (jArray[i]==y) {
          msg="Efa Misy";
        } else {
          //alert("nook");
          //color = "green";
        }
        
      }
      //write notification on div id=warning_msg ;
      if (msg=="Efa Misy") {
        color = "red";
      } else {
        color = "green";
        msg="Mety Tsara";
      }
      $("#warning_msg").text(msg);
      $("#warning_msg2").text(msg);
      document.getElementById("reference_x").style.borderColor = color;
      
      
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
   //var x = 2;

    $("#pourcentage").on("input", function(){
        // Print entered value in a div box
      var f = document.getElementById("fournisseur").value;
      var p = $(this).val();
      var b = (f * p)/100;
      var pu = (f*1) + (b*1) ;
       //$("#result").text(y*x);
        $("#benefice_id").val(b);
        $("#prix_de_vente").val(pu);
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
   //var x = 2;

    $("#fournisseur").on("input", function(){
        // Print entered value in a div box
      var p = document.getElementById("pourcentage").value;
      var f = $(this).val();
      var b = (f * p)/100;
      var pu = (f*1) + (b*1) ;
       //$("#result").text(y*x);
        $("#benefice_id").val(b);
        $("#prix_de_vente").val(pu);
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
   //var x = 2;

    $("#benefice_id").on("input", function(){
        // Print entered value in a div box
      var f = document.getElementById("fournisseur").value;
      var b = $(this).val();
      var p = (b * 100)/f;
      var pu = (f*1) + (b*1) ;
       //$("#result").text(y*x);
        $("#pourcentage").val(p);
        $("#prix_de_vente").val(pu);
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
   //var x = 2;

    $("#prix_de_vente").on("input", function(){
        // Print entered value in a div box
      var f = document.getElementById("fournisseur").value;
      var pu = $(this).val();
      var b = (pu*1)-(f*1);
      var p = (((pu*1)-(f*1))*100)/(f*1);
       //$("#result").text(y*x);
        $("#pourcentage").val(p);
        $("#benefice_id").val(b);
    });
});
</script>
<script>
function unlock(e){
  var ib = <?php echo json_encode($j); ?>;
  //alert(ib);
  var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2022") {
      for (var i = 1; i < ib; i++) {
      //alert(i);
      $("#"+i+"S").removeAttr("style");
      $("#"+i+"M").removeAttr('disabled');
      }
      $("#nouveau").removeAttr('disabled');;
  }
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>