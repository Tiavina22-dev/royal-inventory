<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Congès</title>
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
//Query to employe name
   $query_update = "SELECT * FROM history WHERE type = 'conges' GROUP BY before_change ORDER BY before_change";
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
        <th class="text-center bg-warning" colspan="6"><b>JOURS DE CONGES</b> <a class="center" href="#" data-toggle="modal" data-target="#add"><img src="img/eeeee_icon.png" height="30" width="30" background alt="Edit" /></a>
      </th>
      </tr>
      <tr class="bg-warning">
        <th><b>EMPLOYE</b></th>
        <th colspan="2" class="text-center"><b>JOURS OBTENU</b></th>
        <th colspan="2" class="text-center"><b>JOURS PRISE</b></th>
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
  $query_conges = "SELECT * FROM history WHERE type = 'conges' AND before_change = ? ORDER BY date_time";
  $query_conges = $bdd->prepare($query_conges);
  $query_conges->execute(array($donnees['before_change']));
  $in = 0;
  $out = 0;
  $list_in = "";
  $list_out = "";
  while ($dc = $query_conges -> fetch())
  {
    if ($dc['after_change']=='add') {
      $in = ($dc['flag']+0)+$in;
      $list_in = "<a class='center' href='#' data-toggle='modal' data-target='#conges".$dc['history_id']."'>".$dc['date_time'].'</a>'.'<br> | Raison : '.$dc['details'] .' (By '.$dc['responsable'].') : '.$dc['flag'].'<br>'.$list_in;
    } else {
      //REMOVE
      $out = ($dc['flag']+0)+$out;
      $list_out = "<a class='center' href='#' data-toggle='modal' data-target='#conges".$dc['history_id']."'>".$dc['date_time'].'</a>'.'<br> | Raison : '.$dc['details'] .' (By '.$dc['responsable'].') : '.$dc['flag'].'<br>'.$list_out;
    }
    ?>
    <!-------FORM DE MODIFIER-------->
    <!-- modal form MODIFIER-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "conges".$dc['history_id']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">MODIFY COMMANDE</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="modifier_conges.php" method="post">
            <div class="form-group">
              <label>NOMBRE DE JOURS</label>
              <input class="text-center form-control" value='<?php echo ($dc['flag'])+0; ?>' name="days" type="number" step="any">
            </div>
            <div class="form-group">
              <label>MOTIF (conges 2022, vacance, perso)</label>
              <input class="form-control" value='<?php echo ($dc['details']); ?>' name="motif" type="text">
              <input class="form-control" value ="<?php echo $dc['history_id']; ?>" name="history_id" hidden>
            </div>
            <button type="submit" class="btn btn-success">ENREGISTER</button>
          </form>
          <span class ="text-right"><a href="delete_conges.php?history_id=<?php echo $dc['history_id']; ?>">
            <button class="btn btn-danger">SUPPRIMER</button></a></span>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
    <?php 

  }
  $query_conges->closeCursor();
  //--------END LIST ---------
  if ($bg == 'bg-info') {
    $bg = '';
  }  else {
    $bg = 'bg-info';
  }
  

?>
<tr class="<?php echo $bg;?>">
  <td><?php echo $donnees['before_change']; ?></td>
  <td><?php echo $list_in; ?></td>
  <th><span class="w3-badge w3-margin-right w3-green"><?php echo $in; ?></span></th>
  <td><?php echo $list_out; ?></td>
  <th><span class="w3-badge w3-margin-right w3-yellow"><?php echo $out; ?></th>
  <td class="text-center"><span class="w3-badge w3-margin-right w3-pink"><?php echo ($in-$out); ?></td>
</tr>
</span>

<?php
}
 $query_update->closeCursor();
 ?>
 </tbody>
</table>
</div>

<!-------------------------------------->
    <!-- modal form ADD NOTE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">CONGES PANNEL</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="add_days.php" method="post">
              <div class="form-group">
              <label>PERSONNEL NAME</label>
              <?php
              $query_personnel = "SELECT * FROM user ORDER BY Full_Name DESC";
              $query_personnel = $bdd->prepare($query_personnel);
              $query_personnel->execute(array());
              ?>
              <select class="form-control" name="personnel">
                <option value="" selected disabled hidden>Choisir le personnel</option>
                <?php
                while ($data_personel = $query_personnel -> fetch())
                {
                  ?>
                  <option value=<?php echo $data_personel['User_Name'];?>><?php echo $data_personel['Full_Name'].' ('.$data_personel['User_Name'].')';?></option>
                  <?php
                }
                $query_personnel->closeCursor();
                ?>

              </select>
            </div>
            <div class="form-group">
              <label>AJOUTER ou ENLEVER</label>
              <select class="form-control bg-warning" name="action">
                <option value="" selected disabled hidden>Action</option>
                  <option value='add'>Ajouter</option>
                  <option value='remove'>Enlever</option>
              </select>
            </div>
            <div class="form-group">
            	<label>NOMBRE DE JOURS</label>
            	<input class="text-center form-control" value="1" name="days" type="number" step="any">
            </div>
            <div class="form-group">
              <label>MOTIF (conges 2022, vacance, perso)</label>
              <input class="form-control" placeholder="conges 2022" name="motif" type="text">
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
