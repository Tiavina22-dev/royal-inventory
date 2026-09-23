<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
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
    <div class="text-center">
        <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#Demarrer">SHOP</button>
    </div>
    <br>
    <br>
    <!-------------------------------------->
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">CHOISIR POINT DE VENTE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
        </div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="report_critere.php" method="post">
    <!------------------AUTO LISTE SHOP------------------>
    <div class="form-group">
        <label>POINT DE VENTE</label>
        <select class="form-control btn-secondary" name="point_de_vente" oninput="unlock($(this));" id = "point_de_vente">
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
    <div class="form-group">
        <button type="submit" class="btn btn-success" id = "valider" disabled>Valider</button>
    </div>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
	<?php
  	//include('char_line_vente_7_jours.php');
    include('char_r2.php');
    include('char_line_versement_global_report.php');
?>
  <br>
  <br>
  <br>
  <br>
  <!-- jQuery -->
  <!-- Your custom scripts (optional) -->
</body>
<script>
function unlock(e){

    var debut =  e.val();
    var point_de_vente = document.getElementById('point_de_vente').value;
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    //alert(point_de_vente.length);
    if (debut.length != 0 &&  point_de_vente.length != 0) {
        
        //alert("OK");
        //$("#"+i+"L").removeAttr("style");
        $("#valider").removeAttr('disabled');
    }
}
</script>
</html>
