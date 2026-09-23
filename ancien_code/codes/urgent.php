<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>URGENT</title>
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
   $query_update = "SELECT * FROM history WHERE type = 'urgent' GROUP BY responsable ORDER BY responsable";
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
        <th class="text-center bg-warning" colspan="2"><b>PRIX ET TRAVAIL URGENT</b> <a class="center" href="#" data-toggle="modal" data-target="#add"><img src="img/eeeee_icon.png" height="30" width="30" background alt="Edit" /></a>
      </th>
      </tr>
      <tr class="bg-warning">
        <th><b>RESPONSABLE</b></th>
        <th class="text-center"><b>DETAILS</b></th>
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
  $query_urgent = "SELECT * FROM history WHERE type = 'urgent' AND responsable = ? ORDER BY date_time DESC";
  $query_urgent = $bdd->prepare($query_urgent);
  $query_urgent->execute(array($donnees['responsable']));
  $task_list = "";

  while ($dc = $query_urgent -> fetch())
  {
    //-----JOURS DE RETARD-----

    	$date_en = DateTime::createFromFormat('Y-m-d H:i:s', $dc['date_time']);
        $date_en = $date_en -> format('y-m-d');
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff = date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $color = 'w3-red';
        if($days == 0){$color = 'w3-green';}
    //-------------------------
      $task_list =$task_list."<a class='center' href='#' data-toggle='modal' data-target='#conges".$dc['history_id']."'><span style='font-weight: bold'>".$dc['date_time']." - Jour de retard : <span class='w3-badge ".$color."'>".$days."</span>".'</span></a>'."<br><span class ='text-danger' style='font-weight: bold'> + URGENT :</span> ".$dc['details']."<br><br>";
    ?>
    <!-------FORM DE MODIFIER-------->
    <!-- modal form MODIFIER-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "conges".$dc['history_id']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">MODIFY URGENT TASK</h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="modifier_urgent.php" method="post">
            <div class="form-group">
              <label>DETAILS (Prix AMBATO, Prix SOALAZAINA)</label>
              <input class="form-control" value='<?php echo ($dc['details']); ?>' name="details" type="text">
              <input class="form-control" value ="<?php echo $dc['history_id']; ?>" name="history_id" hidden>
            </div>
            <button type="submit" class="btn btn-success">ENREGISTER</button>
          </form>
          <span class ="text-right"><a href="delete_urgent.php?history_id=<?php echo $dc['history_id']; ?>">
            <button class="btn btn-danger">SUPPRIMER</button></a></span>
          <!-- actual form ends -->
          </div>
        </div>
      </div>
    </div>
<!-------------------------------------->
    <?php 

  }
  $query_urgent->closeCursor();
  //--------END LIST ---------
  if ($bg == 'bg-info') {
    $bg = '';
  }  else {
    $bg = 'bg-info';
  }
  

?>
<tr class="<?php echo $bg;?>">
  <td><span style='font-weight: bold'><?php echo $donnees['responsable']; ?></span></td>
  <td><?php echo $task_list; ?></td>
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
    <!-- modal form ADD URGENT TASK-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title text-center text-danger"><b>PRIX/TRAVAIL URGENT</b></h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form" action="add_urgent.php" method="post">
            <div class="form-group">
              <label>DETAILS (Prix AMBATO, Prix SOALAZAINA)</label>
              <input class="form-control" placeholder="Prix Ambato" name="details" type="text">
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
