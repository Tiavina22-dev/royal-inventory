<!DOCTYPE html>
<html>
<head>
  <title>Journal de Vente</title>
  <!---add bootstrap css--->
  <script src="js/jquery-3.5.1.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/mdb.min.css">
  <!---add other css--->
  <link rel="stylesheet" type="text/css" href="css/style_Index.css">
  <link href="css/arrondi.css" rel="stylesheet">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/mota.css">
  <link rel="stylesheet" href="css/top20.css">

</head>
<body>
<!------<body style="background: #CF9FFF">----->
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
<?php

//---------------ACTIVITY CONTROLE----------------------
        include('connect.php'); ?>
        <div class="center">

          <br>
<br>
<br>
<br>
<br>
<br>
<h4 class="text-center text-primary">ACTIVITE AUJOURD'HUI</h4>
          <?php
        $query_activity_tracking = $bdd->prepare("SELECT * FROM history WHERE DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE() AND type = 'tracking_activity' GROUP BY responsable ORDER BY responsable");
        $query_activity_tracking->execute(array());
        while ($donnees = $query_activity_tracking -> fetch())
        { ?>
          <br><span style='font-weight: bold' class='text-center text-danger'>--------<?php echo $donnees['responsable'];?>--------</span><span class='text-warning'>
        <?php
          $query_activity = $bdd->prepare("SELECT *,COUNT(details) as nbr,MAX(date_time) as tmax FROM history WHERE DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE() AND type = 'tracking_activity' AND responsable = ? GROUP BY details ORDER BY date_time");
          $query_activity->execute(array($donnees['responsable']));
          while ($data = $query_activity -> fetch())
          {
            //Time controle
            date_default_timezone_set('Europe/Moscow');
            $date_start = date_create($data['tmax']);
            $date_end = date_create(date("y-m-d H:i:s"));
            $diff=date_diff($date_start,$date_end);
            $mounth = $diff->format("%m");
            $day = $diff->format("%a");
            $hours = $diff->format("%h");
            $minutes = $diff->format("%i");
            $sec = $diff->format("%s");
            $aff = $diff->format("il y a %a j");
              if ($day < 1 AND $mounth < 1) {
              $aff = $diff->format("il y a %h h");
              }
              if ($day < 1 AND $mounth < 1 AND $hours < 1) {
              $aff = $diff->format("il y a %i min");
              }
              if ($day < 1 AND $mounth < 1 AND $hours < 1 AND $minutes < 1) {
              $aff = "maintenant";
              }
            //--------------------------
            echo '<br>- '.$data['details'].' ('.$data['nbr'].') '."<span class = 'text-success'>".$aff.'</span>';

          }
           $query_activity ->closeCursor();

        }
        $query_activity_tracking ->closeCursor();

         echo " </span></a>";

?>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
  </body>
</html>