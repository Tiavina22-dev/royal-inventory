<?php

//---------------TRACKING VENTE----------------------
    include('connect.php');
    $query_commande = $bdd->prepare('SELECT *,MAX(date_time_commande) as tmax FROM commande GROUP BY description_date ORDER BY user');
        $query_commande->execute(array());
            while ($donnees = $query_commande -> fetch())
      {

      echo "<a class='dropdown-item text-primary' href='#'> #";
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
      echo $donnees['nom_du_client'].' | '.$donnees['description_date'].' | '.$donnees['user'].' | <span class="text-dark">'.$aff.'</span></a>';
      }
      $query_commande ->closeCursor();
      //-----------------------
      $query_vente_calc = $bdd->prepare('SELECT *,MAX(date_time_vente_calc) as tmax FROM vente_calc GROUP BY description_date ORDER BY user');
      $query_vente_calc->execute(array());
      while ($donnees = $query_vente_calc -> fetch())
      {
      echo "<a class='dropdown-item text-primary' href='#'> #"; 
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
      echo $donnees['nom_du_client'].' | '.$donnees['description_date'].' | '.$donnees['user'].' | <span class="text-dark">'.$aff.'</span></a>';
      }
      $query_vente_calc ->closeCursor();

?>