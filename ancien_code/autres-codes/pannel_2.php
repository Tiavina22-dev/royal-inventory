<?php
include('connect.php');
//------------ACTIVITY-----------------
$activity = 0;
$query_activity = "SELECT * FROM history WHERE DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE() AND type = 'tracking_activity' ORDER BY date_time";
$query_activity = $bdd->prepare($query_activity);
$query_activity->execute(array());
$activity = $query_activity->rowCount ();
$query_activity->closeCursor();
#GET MAX activity
$activity_pourcent = 0;
$query_activity = "SELECT MAX(score) as max FROM (SELECT DATE_FORMAT(date_time,'%Y-%m-%d'),COUNT(*) as score FROM history WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN   (CURDATE() - INTERVAL 14 DAY) AND CURDATE() AND type = 'tracking_activity' GROUP BY DATE_FORMAT(date_time,'%Y-%m-%d')) x";
$query_activity = $bdd->prepare($query_activity);
$query_activity->execute(array());
  while ($data = $query_activity -> fetch())
{
  $activity_max = $data['max'];
  if ($data['max']>0) {
    $activity_pourcent = ($activity*100)/($data['max']);
  }
}
$query_activity->closeCursor();
//-------------------------------------
//------------JOURNAL-----------------
$vente = 0;
$query_vente = "SELECT * FROM commande GROUP BY description_date";
$query_vente = $bdd->prepare($query_vente);
$query_vente->execute(array());
$vente = $query_vente->rowCount ();
$query_vente->closeCursor();
#--------------------------
$query_vente = "SELECT * FROM vente_calc GROUP BY description_date";
$query_vente = $bdd->prepare($query_vente);
$query_vente->execute(array());
$vente = $vente + ($query_vente->rowCount ());
$query_vente->closeCursor();
#max
$vente_pourcent = 0;
$vente_max = 0;
$query_vente = "SELECT max(nb) as max FROM (SELECT COUNT(date) as nb FROM (SELECT nom_client_fournisseur,Date_du_Journal_mvt,DATE_FORMAT(date_time,'%Y-%m-%d') as date FROM mvt WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN  (CURDATE() - INTERVAL 14 DAY) AND CURDATE() AND type_de_mvt = 'vente' GROUP BY numero_commande_stock) x GROUP BY date) y";
$query_vente = $bdd->prepare($query_vente);
$query_vente->execute(array());
  while ($data = $query_vente -> fetch())
{
  $vente_max = $data['max'];
  if ($data['max']>0) {
    $vente_pourcent = ($vente*100)/($data['max']);
  }
  
}
$query_vente->closeCursor();
//-------------------------------------
//------------STOCK-----------------
$stock = 0;
$query_stock = "SELECT * FROM stock_prep GROUP BY description_date";
$query_stock = $bdd->prepare($query_stock);
$query_stock->execute(array());
$stock = $query_stock->rowCount ();
$query_stock->closeCursor();
#--------------------------
$query_stock = "SELECT * FROM stock_prep_calc GROUP BY description_date";
$query_stock = $bdd->prepare($query_stock);
$query_stock->execute(array());
$stock = $stock + ($query_stock->rowCount ());
$query_stock->closeCursor();
#max
$stock_pourcent = 0;
$stock_max = 0;
$query_stock = "SELECT max(nb) as max FROM (SELECT COUNT(date) as nb FROM (SELECT nom_client_fournisseur,Date_du_Journal_mvt,DATE_FORMAT(date_time,'%Y-%m-%d') as date FROM mvt WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN  (CURDATE() - INTERVAL 14 DAY) AND CURDATE() AND type_de_mvt = 'stock' GROUP BY numero_commande_stock) x GROUP BY date) y";
$query_stock = $bdd->prepare($query_stock);
$query_stock->execute(array());
  while ($data = $query_stock -> fetch())
{
  $stock_max = $data['max'];
  if ($data['max']>0) {
    $stock_pourcent = ($stock*100)/($data['max']);
  }
  
}
$query_stock->closeCursor();

//-------------------------------------
//-----------------FACTURE----------------
$facture = 0;
$query_facture = "SELECT * FROM stock_prep WHERE description_date LIKE '%Facture%' GROUP BY description_date";
$query_facture = $bdd->prepare($query_facture);
$query_facture->execute(array());
$facture = $query_facture->rowCount ();
$query_facture->closeCursor();
#Max
$facture_pourcent = 0;
$facture_max = 0;
$query_facture = "SELECT max(nb) as max FROM (SELECT COUNT(date) as nb FROM (SELECT nom_client_fournisseur,Date_du_Journal_mvt,DATE_FORMAT(date_time,'%Y-%m-%d') as date FROM mvt WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN  (CURDATE() - INTERVAL 100 DAY) AND CURDATE() AND type_de_mvt = 'facture'  GROUP BY numero_commande_stock) x GROUP BY date) y";
$query_facture = $bdd->prepare($query_facture);
$query_facture->execute(array());
  while ($data = $query_facture -> fetch())
{
  $facture_max = $data['max'];
  $facture_pourcent = 0;
  if ($data['max'] > 0) {
    $facture_pourcent = ($facture*100)/($data['max']);
  }
  
}
$query_facture->closeCursor();
//-------------------------------------
?>
<!------------SIMPLE SEARCH------------------>
<!--------CERCLE POURCENTAGE----------------->
<div class="flex-wrapper">
  <div class="single-chart">
    <svg viewbox="0 0 36 36" class="circular-chart rose">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle"
        stroke-dasharray="<?php echo $activity_pourcent;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="16.35" class="percentage"><?php echo intval($activity);?></text>
      <text x="18" y="21.35" class="jaune">ACTIVITE</text>
      <text x="18" y="25.35" class="fournisseur"><?php echo 'Max '.$activity_max;?></text>
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
        stroke-dasharray="<?php echo $vente_pourcent;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="16.35" class="percentage"><?php echo intval($vente);?></text>
      <text x="18" y="21.35" class="pv">JOURNAL</text>
      <text x="18" y="25.35" class="fournisseur"><?php echo 'Max '.$vente_max;?></text>
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
        stroke-dasharray="<?php echo $stock_pourcent;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="16.35" class="percentage"><?php echo intval($stock);?></text>
      <text x="18" y="21.35" class="but">STOCK</text>
      <text x="18" y="25.35" class="fournisseur"><?php echo 'Max '.$stock_max;?></text>
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
        stroke-dasharray="<?php echo $facture_pourcent;?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <text x="18" y="16.35" class="percentage"><?php echo intval($facture);?></text>
      <text x="18" y="21.35" class="photo">FACTURE</text>
      <text x="18" y="25.35" class="fournisseur"><?php echo 'Max '.$facture_max;?></text>
    </svg>
  </div>
</div>
<div class="container-fluid">
<!-------FIN CERCLE POURCENTAGE---------------------->
<?php
//GET USER ACTIF
$query_user = "SELECT user,COUNT(user) as score FROM ((SELECT user FROM commande GROUP BY user ORDER BY user) UNION ALL (SELECT user FROM vente_calc GROUP BY user ORDER BY user) UNION ALL (SELECT user_stock_prep as user FROM stock_prep_calc GROUP BY user_stock_prep ORDER BY user_stock_prep) UNION ALL (SELECT user_stock_prep as user FROM stock_prep GROUP BY user_stock_prep ORDER BY user_stock_prep) UNION ALL (SELECT responsable as user FROM history WHERE DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE() AND type = 'tracking_activity' ORDER BY responsable)) x GROUP BY user ORDER BY score DESC";
$query_user = $bdd->prepare($query_user);
$query_user->execute(array());
while ($donnees = $query_user -> fetch())
{
//GET PHOTO
  $img_path = "img/default_img/default_pdp.png";
  $query_user_photo = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
  $query_user_photo->execute(array($donnees['user']));
    while ($data = $query_user_photo->fetch())
         {
          $img_path = $data['img_path'];
          $fullname = $data['Full_Name'];
          }
    $query_user_photo->closeCursor();
?>
<div class="badge" style="background-image: linear-gradient(black,rgba(103,103,103))">
  <div class="card-header" style="background-image: linear-gradient(to right,rgba(0,172,217),white,rgba(0,172,217))">
    <img src="<?php echo  $img_path ?>" height="60" width="60" class="arrondi" />
    <?php echo $fullname;?>
  </div><br>
    <div class="card-body text-dark text-left bg-light">
    <?php
    //-----------------VENTE TRACKING----------------------------------
      $query_commande = $bdd->prepare('SELECT *,MAX(date_time_commande) as tmax FROM commande WHERE user = ? GROUP BY description_date ORDER BY user');
      $query_commande->execute(array($donnees['user']));
      #------------------------------------------
      $query_vente_calc = $bdd->prepare('SELECT *,MAX(date_time_vente_calc) as tmax FROM vente_calc WHERE user = ? GROUP BY description_date ORDER BY user');
      $query_vente_calc->execute(array($donnees['user']));
      #----------------------------------------
      $nb_line = ($query_commande->rowCount ())+($query_vente_calc->rowCount ());
    if ($nb_line > 0) {
      echo "<big class = 'text-purple'>VENTE</big>";
    }
     $no = 0;
      while ($data_vente = $query_commande -> fetch())
      {
         $no = 1+$no;
      echo "<span class='dropdown-item text-purple'><b><span class='w3-badge w3-margin-right w3-blue'>".$no."</span>";
      //Time controle
      date_default_timezone_set('Europe/Moscow');
      $date_start = date_create($data_vente['tmax']);
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
      echo $data_vente['nom_du_client'].' | '.$data_vente['description_date'].' | <span class="text-dark">'.$aff.'</span></b></span>';
      }
      $query_commande ->closeCursor();
      //-----------------------
      while ($data_vente = $query_vente_calc -> fetch())
      {
        $no = 1+$no;
      echo "<span class='dropdown-item text-purple'> <b><span class='w3-badge w3-margin-right w3-blue'>".$no."</span>"; 
            //Time controle
      date_default_timezone_set('Europe/Moscow');
      $date_start = date_create($data_vente['tmax']);
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
      echo $data_vente['nom_du_client'].' | '.$data_vente['description_date'].' | <span class="text-dark">'.$aff.'</span></b></span>';
      }
      $query_vente_calc ->closeCursor();

//---------------TRACKING STOCK----------------------
    $query_stock_prep = $bdd->prepare('SELECT *,MAX(date_time_stock_prep) as tmax FROM stock_prep WHERE user_stock_prep = ? GROUP BY description_date');
    $query_stock_prep->execute(array($donnees['user']));
    # ------------------------------
    $query_stock_prep_calc = $bdd->prepare('SELECT *,MAX(date_time_stock_prep_calc) as tmax FROM stock_prep_calc WHERE user_stock_prep = ? GROUP BY description_date');
    $query_stock_prep_calc->execute(array($donnees['user']));
    #--------------------------------------
    $nb_line = ($query_stock_prep->rowCount ())+($query_stock_prep_calc->rowCount ());
    if ($nb_line > 0) {
      echo "<big class = 'text-info'>STOCK</big>";
    }
    $no = 0;
    while ($data_stock = $query_stock_prep -> fetch())
      {
      $no = 1+$no;
       echo "<span class='dropdown-item text-info'> <b><span class='w3-badge w3-margin-right w3-pink'>".$no."</span>";
      //Time controle
      date_default_timezone_set('Europe/Moscow');
      $date_start = date_create($data_stock['tmax']);
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
      echo $data_stock['nom_du_client'].' | '.$data_stock['description_date'].' | <span class="text-dark">'.$aff.'</span></b></span>';
      }
      $query_stock_prep ->closeCursor();
      //------------------------------------
      
      while ($data_stock = $query_stock_prep_calc -> fetch())
      {
        $no = 1+$no;
      echo "<span class='dropdown-item text-info'> <b><span class='w3-badge w3-margin-right w3-pink'>".$no."</span>";
                  //Time controle
      date_default_timezone_set('Europe/Moscow');
      $date_start = date_create($data_stock['tmax']);
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
      echo $data_stock['nom_du_client'].' | '.$data_stock['description_date'].' | <span class="text-dark">'.$aff.'</span></b></span>';
      }
      $query_stock_prep_calc ->closeCursor();

      //------------TRACKING ACTIVITY---------------------
          $query_activity = $bdd->prepare("SELECT *,COUNT(details) as nbr,MAX(date_time) as tmax FROM history WHERE DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE() AND type = 'tracking_activity' AND responsable = ? GROUP BY details ORDER BY date_time");
          $query_activity->execute(array($donnees['user']));
          $nb_line = $query_activity->rowCount ();
          if ($nb_line > 0) {
            echo "<big class = 'text-orange-2'>AVTIVITY</big>";
          }
          $no = 0;
          while ($data = $query_activity -> fetch())
          {
            $no = 1+$no;
            echo "<span class='dropdown-item text-orange-2'> <b><span class='w3-badge w3-margin-right w3-purple'>".$no."</span>";
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
            echo $data['details'].' ('.$data['nbr'].') | '."<span class = 'text-dark'>".$aff.'<b></span></span>';

          }
           $query_activity ->closeCursor();
?>
    </div>
    <span>
      <br>
    </span>
  </div>
<?php
}
$query_user ->closeCursor();
?>
</div>
<br>
<br>
<br>
<br>