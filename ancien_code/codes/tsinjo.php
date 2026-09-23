<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>TSINJO</title>
  <!-- Font Awesome -->
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
  <link rel="stylesheet" href="css/list_type2.css">
  <link rel="stylesheet" href="css/list_type3.css">
  <link rel="stylesheet" href="css/list_type5.css">
  <link rel="stylesheet" href="css/list_type6.css">
  <link rel="stylesheet" href="css/w3.css">
  -->
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php");
include('connect.php');
//GET USERNAME
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
$Tl_dispo = 0;
//Query to month an Year name
   $query_update = "SELECT SQL_NO_CACHE YEAR(date_time) as YEAR,date_format(date_time,'%b') as MONTH FROM tsinjo WHERE Status_tsinjo = 'actif' GROUP BY YEAR(date_time)*100 + MONTH(date_time) ORDER BY date_time DESC;";
    $query_update = $bdd->prepare($query_update);

    $query_update->execute(array());

?>
<!-- *******************MATERIALS**********************-->
<div id="About">
<br>
<br>
<br>
<br>
<br>
<br>
<h3></h3>
<div class="flex-center flex-column">
<table class="table-bordered table-sm">
    <thead>
      <tr>
        <th class="text-center bg-warning" colspan="6"><b>SOLDE TSINJO</b> <a class="center" href="#" data-toggle="modal" data-target="#add"><img src="img/eeeee_icon.png" height="30" width="30" background alt="Edit" /></a>
      </th>
      </tr>
      <tr class="bg-warning">
        <th><b>MOIS</b></th>
        <th colspan="2" class="text-center"><b>ENTRE</b></th>
        <th colspan="2" class="text-center"><b>SORTIE</b></th>
        <th><b>DISPONIBLE</b></th>
      </tr>
    </thead>
    <tbody>
<?php
$bg = 'bg-info';
while ($donnees = $query_update -> fetch())
{ 
  #$donnees['before_change'];
  #$donnees['details'];
  #$donnees['responsable'];
  //LIST OF IN AND OUT DAYS-------------
  $query_tsinjo = "SELECT * FROM tsinjo WHERE Status_tsinjo = 'actif' AND YEAR(date_time) = ? AND date_format(date_time,'%b') = ? ORDER BY date_time;";
  $query_tsinjo = $bdd->prepare($query_tsinjo);
  $query_tsinjo->execute(array($donnees['YEAR'],$donnees['MONTH']));
  $in = 0;
  $out = 0;
  $Tl_dispo = 0;
  $list_in = "";
  $list_out = "";
  while ($dc = $query_tsinjo -> fetch())
  {
    if ($dc['type_tsinjo']=='add') {
      $in = ($dc['montant_tsinjo']+0)+$in;
      $list_in = "<a class='center' href='#' data-toggle='modal' data-target='#conges".$dc['id_tsinjo']."'>".$dc['date_time'].'</a>'.'<br> | '.$dc['motif_tsinjo'] .' (By '.$dc['Responsable'].') : '.number_format($dc['montant_tsinjo'],0, "", " ").'<br>--------------------<br>'.$list_in;
    } else {
      //REMOVE
      $out = ($dc['montant_tsinjo']+0)+$out;
      $list_out = "<a class='center' href='#' data-toggle='modal' data-target='#conges".$dc['id_tsinjo']."'>".$dc['date_time'].'</a>'.'<br> | '.$dc['motif_tsinjo'] .' (By '.$dc['Responsable'].') : '.number_format($dc['montant_tsinjo'],0, "", " ").'<br>--------------------<br>'.$list_out;
    }
    ?>
    <!-------FORM DE MODIFIER-------->
    <!-- modal form MODIFIER-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "conges".$dc['id_tsinjo']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">MODIFY TSINJO</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="modifier_tsinjo.php" method="post">
            <div class="form-group">
              <label>MONTANT</label>
              <input class="text-center form-control" value='<?php echo ($dc['montant_tsinjo'])+0; ?>' name="montant_tsinjo" type="number" step="any">
            </div>
            <div class="form-group">
              <label>MOTIF (fitsaboana, deplacement, credit)</label>
              <input class="form-control" value='<?php echo ($dc['motif_tsinjo']); ?>' name="motif_tsinjo" type="text">
              <input class="form-control" value ="<?php echo $dc['id_tsinjo']; ?>" name="id_tsinjo" hidden>
            </div>
            <button type="submit" class="btn btn-success">ENREGISTER</button>
          </form>
          <span class ="text-right"><a href="delete_tsinjo.php?id_tsinjo=<?php echo $dc['id_tsinjo']; ?>">
            <button class="btn btn-danger">SUPPRIMER</button></a></span>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
    <?php 

  }
  $query_tsinjo->closeCursor();
  //--------END LIST ---------
  if ($bg == 'bg-info') {
    $bg = '';
  }  else {
    $bg = 'bg-info';
  }
  
$Tl_dispo = ($in-$out) + $Tl_dispo;
?>
<tr class="<?php echo $bg;?>">
  <td style='font-weight: bold'><?php echo strtoupper($donnees['MONTH']).' '.$donnees['YEAR']; ?></td>
  <td><?php echo $list_in; ?></td>
  <th><span class="w3-badge w3-margin-right w3-green"><?php echo number_format($in,0, "", " "); ?></span></th>
  <td><?php echo $list_out; ?></td>
  <th><span class="w3-badge w3-margin-right w3-yellow"><?php echo number_format($out,0, "", " "); ?></th>
  <td class="text-center"><span class="w3-badge w3-margin-right w3-pink"><?php echo number_format(($in-$out),0, "", " "); ?></td>
</tr>
</span>

<?php
}
 $query_update->closeCursor();
 ?>
 <tr class="text-right bg-warning">
  <th  colspan="5" style='font-weight: bold'>VOLA AZO AMPIASAINA</th>
  <th class="text-center"><span class="w3-badge w3-margin-right w3-purple"><?php echo number_format($Tl_dispo,0, "", " "); ?></th>
</tr>
 </tbody>
</table>
</div>

<!-------------------------------------->
    <!-- modal form ADD NOTE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">TSINJO PANNEL</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="add_tsinjo.php" method="post">
            <div class="form-group">
              <label>AJOUTER ou ENLEVER</label>
              <select class="form-control bg-warning" name="action">
                <option value="" selected disabled hidden>Action</option>
                  <option value='add'>Ajouter</option>
                  <option value='remove'>Enlever</option>
              </select>
            </div>
            <div class="form-group">
            	<label>MONTANT</label>
            	<input class="text-center form-control" value="0" name="montant" type="number" step="any">
            </div>
            <div class="form-group">
              <label>MOTIF (FITSABOANA - CREDIT - DEPLACEMENT - FRAIS)</label>
              <input class="form-control" placeholder="Fitsaboana" name="motif" type="text">
            </div>
            <button type="submit" class="btn btn-success">OK</button>
          </form>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->

  <!-- jQuery -->
  <!--
  <script type="text/javascript" src="js/mdb.min.js"></script>
  -->
   <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript" src="js/bootstrap.min.js"></script>
   <script  src="js/confirmation.js"></script>
  <!-- Your custom scripts (optional) -->

</body>
</html>
