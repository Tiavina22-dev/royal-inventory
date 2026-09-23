<!DOCTYPE html>
<html>
<head>
	<title>POURCENTAGE</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/pourcentage.css" rel="stylesheet">
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">

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
 <h4 class="text-center">CLICK</h4>

<div class="text-center">
<button id="nouveau" type="button" class="btn btn-secondary" data-toggle="modal" data-target="#Calc_age"><b>CALCULER POURCENTAGE</b></button>
</div>

         <!--------------------------------------------->
        <!-------FORM POURCENTAGE CALC-------->
    <!-- modal form NOUVEAU-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-2" id="Calc_age" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title"><b>% CALCULATOR</b></h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form id="form_prix" role="form" action="nouveau_produit.php" enctype="multipart/form-data" method="post">
            <div class="form-group">
            <label><b class="text-danger">PRIX FOURNISSEUR (REFERENCE)</b></label>
            <p class="text-secondary float-right" id="warning_msg2"></p>
            <input class="form-control" name="fournisseur" value=0 type="number" step="any" id="fournisseur">
            </div>
            <div class="form-group float-left">
            <label><b class="text-primary">Pourcentage (%)</b></label>
            <!-------------------------------->

            <!--------------------------------->
            <input class="form-control" name="pourcentage" value=20 type="number" step="any" id="pourcentage">
            </div>
            <div class="form-group  float-right">
            <label><b class="text-primary">Benefice (Ariary)</b></label>
            <input class="form-control" name="benefice" type="number" step="any" value=0 id="benefice_id">
            </div>
            <div class="form-group">
            <label><b>PU ANOMEZANA (Ariary)</b></label>
            <input class="form-control" name="prix_de_vente" value=0 type="number" id="prix_de_vente">
            <input type="hidden" name="id_x" value=0>
            </div>
            <button data-dismiss="modal" class="btn btn-success">FERMER</button>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
</body>
<!--------------------------------------->

<br>
<br>
<br>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->

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