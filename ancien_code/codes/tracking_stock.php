<?php

//---------------TRACKING STOCK----------------------
    include('connect.php');
    $query_stock_prep = $bdd->prepare('SELECT *,MAX(date_time_stock_prep) as tmax FROM stock_prep GROUP BY description_date ORDER BY user_stock_prep');
    $query_stock_prep->execute(array());
    while ($donnees = $query_stock_prep -> fetch())
      {
       echo "<a class='dropdown-item text-secondary' href='#'> #";
      //Time controle
      date_default_timezone_set('Europe/Moscow');
      $date_start = date_create($donnees['tmax']);
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
      echo $donnees['nom_du_client'].' | '.$donnees['description_date'].' | '.$donnees['user_stock_prep'].' | <span class="text-dark">'.$aff.'</span></a>';
      }
      $query_stock_prep ->closeCursor();
      //------------------------------------
      $query_stock_prep_calc = $bdd->prepare('SELECT *,MAX(date_time_stock_prep_calc) as tmax FROM stock_prep_calc GROUP BY description_date ORDER BY user_stock_prep');
        $query_stock_prep_calc->execute(array());
      while ($donnees = $query_stock_prep_calc -> fetch())
      {

      echo "<a class='dropdown-item text-secondary' href='#'> #";
                  //Time controle
      date_default_timezone_set('Europe/Moscow');
      $date_start = date_create($donnees['tmax']);
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
      echo $donnees['nom_du_client'].' | '.$donnees['description_date'].' | '.$donnees['user_stock_prep'].' | <span class="text-dark">'.$aff.'</span></a>';
      }
      $query_stock_prep_calc ->closeCursor();
?>