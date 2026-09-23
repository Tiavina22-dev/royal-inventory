<!DOCTYPE html>
<html>
<head>
	<title>SHOP</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link rel="stylesheet" type="text/css" href="css/style_Index.css">
	<link href="css/arrondi.css" rel="stylesheet">
	<link rel="stylesheet" href="css/w3.css">

</head>
<body style="background: #CF9FFF">
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
<!--------------------------------------->
<?php
include('connect.php');
$query_shop = "SELECT * FROM shop ORDER BY long_name;";
$query_shop = $bdd->prepare($query_shop);

$query_shop->execute(array());
//get row count
$total_line=$query_shop->rowCount ();
?>
<div class="text-center">
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_new">NEW SHOP</button>
</div>
<br>
	 <table class="table-striped table">
        <thead class="table-danger">
        <tr>
        	<th class="text-center" colspan="6">POINT DE VENTE</th>
        </tr>
        <tr>
        <th></th>
        <th>No.</th>
        <th>Nom Courant</th>
        <th>Unique Nom</th>
        <th colspan="2">Modifier/Suppr</th>
        </tr>
        </thead>
        <tbody>
<?php

$no = 0;
$search_color_table = 'table-success';
while ($donnees = $query_shop -> fetch())
{ 
	$no = $no+1;
	//Handle color table
	if ($search_color_table == 'table-success') {$search_color_table = 'table-light';} else {$search_color_table = 'table-success';}
?>
		<tr class="<?php echo $search_color_table; ?>">
		<td></td>
		<td><a href="#ancre1"><?php echo $no; ?></a></td>
		<td><?php echo $donnees['long_name']; ?></td>
		<td><?php echo $donnees['short_name']; ?></td>
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['shop_id']; ?>">Modifier</button></td>
		<td><a class="center" href="#" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon2.png" height="30" width="30" background alt="Edit" /></a></td>
<!-------FORM DE MODIFIER-------->
		<!-- modal form MODIFIER-->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['shop_id']; ?>" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">Modifier Nom Courant</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<form role="form" action="modifier_shop.php" method="post">
						<div class="form-group">
						<label>Nom Courant</label>
						<input class="form-control" name="long_name" value="<?php echo $donnees['long_name']; ?>" type="text">
						</div>
						<div class="form-group">
						<label>Unique Nom</label>
						<input class="form-control" name="short_name" value="<?php echo $donnees['short_name']; ?>" type="text" disabled>
						<input class="form-control" name="shop_id" value="<?php echo $donnees['shop_id']; ?>" type="number" hidden>
						</div>
						<button type="submit" class="btn btn-success">Valider</button>
					</form>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------------------->
	</tr>
<?php
}
?>
</tbody>
</table>
</div>
	
<!-------------------------------------->
<!-- model form NOUVEAU-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Modal_new" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Nouveau Point De Vente</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_shop.php" method="post">
	<div class="form-group">
		<label>Nom Unique (Sans Espace)</label>
		<input class="form-control" name="short_name" placeholder="Ex: Soalazaina" type="text">
	</div>
	<div class="form-group">
		<label>Nom Courant et Descriptif</label>
		<input class="form-control" name="long_name" placeholder="Ex: Ranto Soalazaina" type="text">
	</div>
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

<!-------------------------------------->
<!--Javascript--->
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
       document.getElementById("reference_x").style.borderColor =
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
      }
      $("#warning_msg").text(msg);
      document.getElementById("reference_x").style.borderColor = color;
      
      
    });
});
</script>
<script>

function myFunction() {
var myCheck = "";
var textzone = "";
//alert("OK");
for (var u = 0 ; u <= <?php echo json_encode($i); ?>; u++) {
	myCheck = "myCheck"+u;
	textzone = "textzone"+u;
  var checkBox = document.getElementById(myCheck);
  var text = document.getElementById(textzone);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
}

</script>
<script>

function myFunction2() {
var myCheck = "";
var textzone = "";
//alert("OK");
for (var u = 1 ; u <= <?php echo json_encode($m); ?>; u++) {
	myCheck = "myCheck2"+u;
	textzone = "textzone2"+u;
  var checkBox = document.getElementById(myCheck);
  var text = document.getElementById(textzone);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
}

</script>
<script>

function dFunction() {
  var checkBox = document.getElementById("myCheckd");
  var text = document.getElementById("textzoned");
  var text2 = document.getElementById("textzoned2");
  var text3 = document.getElementById("textzoned3");
  if (checkBox.checked == true){
    text.style.display = "block";
    text2.style.display = "none";
    text3.style.display = "none";
  } else {
     text.style.display = "none";
     text2.style.display = "block";
     text3.style.display = "block";
  }
}

</script>
<script>
$(document).ready(function(){
   //var x = 2;

    $("#general_note").on("input", function(){
        // Print entered value in a div box
      //var x = document.getElementById("id1").value;
      //var v = document.getElementById("variable").value;
      var general_note = $(this).val();

    });
});
</script>
<script>
function MontantFunction(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant = j + '_id_montant';
	var id_qt = j + '_id_qt';
	var qt = document.getElementById(id_qt).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant).text(mt);
}
</script>
<script>
function MontantFunctionModif(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant_modif = j + '_id_montant_modif';
	var id_qt_modif = j + '_id_qt_modif';
	var qt = document.getElementById(id_qt_modif).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant_modif).text(mt);
}
</script>
<script>
function Montant2Function(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant = j + '_id_montant';
	var id_pu = j + '_id_pu';
	var id_pu2 = "textzone"+j;
	var id_montant3 =  j + '_id_montant3';
	var pu = document.getElementById(id_pu).value;
	var pu2 = document.getElementById(id_pu2).value;
	var qt =  e.val();
	var mt = pu*qt;
	var mt2 = pu2*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant).text(mt);
    $("#"+id_montant3).text(mt2);
}
</script>
<script>
function Montant2FunctionModif(e){
	var j = parseFloat(e.attr('id'));
	//alert(j);
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant_modif = j + '_id_montant_modif';
	var id_pu_modif = j + '_id_pu_modif';
	var id_pu2_modif = "textzone2"+j;
	var id_montant3_modif =  j + '_id_montant3_modif';
	var pu = document.getElementById(id_pu_modif).value;
	var pu2 = document.getElementById(id_pu2_modif).value;
	var qt =  e.val();
	var mt = pu*qt;
	var mt2 = pu2*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant_modif).text(mt);
    $("#"+id_montant3_modif).text(mt2);
}
</script>
<script>
function Montant3Function(e){
	//var j = parseFloat(e.attr('id'));
	var j = e.attr('id');
	var l = j.length;
	var y = j.length - 8;
	//alert (x);
	var x = parseFloat(j.slice(8,l));
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant3 = x + '_id_montant3';
	var id_qt = x + '_id_qt';
	//var id_pua = 'textzone'+j;
	var qt = document.getElementById(id_qt).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant3).text(mt);
}
</script>
<script>
function Montant3FunctionModif(e){
	//var j = parseFloat(e.attr('id'));
	var j = e.attr('id');
	var l = j.length;

	//alert (x);
	var x = parseFloat(j.slice(9,l));
	//$id_prix_fournisseur = $j.'_id_prix_fournisseur';
	// $id_benefice = $j.'_id_benefice';
	// $id_pourcentage = $j.'_id_pourcentage';
	// $id_prix_de_vente = $j.'_id_prix_de_vente';
	var id_montant3_modif = x + '_id_montant3_modif';
	var id_qt_modif = x + '_id_qt_modif';
	//var id_pua = 'textzone'+j;
	var qt = document.getElementById(id_qt_modif).value;
	var pu =  e.val();
	var mt = pu*qt;
    //$("#"+id_montant).val(mt);
    $("#"+id_montant3_modif).text(mt);
}
</script>
<script>
function unlock(e){
	var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2020") {
    	$("#valider").removeAttr("style");
	}
}
</script>
<script>

function Resolution(e) {
var versement = <?php echo json_encode($TL_VRST); ?>;
var vata =  e.val();
//alert("OK");
  if ((versement - vata) > 0){
    $("#Tsy_ampy").val(versement - vata);
    $("#Mihoatra").val('0');
  } else {
  	$("#Tsy_ampy").val('0');
    $("#Mihoatra").val(Math.abs(versement - vata));
  }
}

</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>