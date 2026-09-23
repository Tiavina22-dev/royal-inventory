
<!--------------------------------------->
<?php

// print the calendar for the current month 
list($month,$year) = explode(',',date('m,Y')); 
 if (isset($_GET['month'])) {
	$month = $_GET['month'];
 }
 if (isset($_GET['year'])) {
	$year = $_GET['year'];
 }
pc_calendar($month,$year);
function pc_calendar($month,$year,$opts = '') { 
    // set default options // 
    if (! is_array($opts)) { $opts = array(); }
if (! isset($opts['today_color'])) { $opts['today_color'] = '#FFFF00'; 
} 
    if (! isset($opts['month_link'])) { 
        $opts['month_link'] =  
            '<a href="'.$_SERVER['PHP_SELF'].'?month=%d&year=%d">%s</a>'; 
    } 
     
    list($this_month,$this_year,$this_day) = explode(',',strftime('%m,%Y,%d')); 
    $day_highlight = (($this_month == $month) && ($this_year == $year)); 
     
    list($prev_month,$prev_year) = explode(',',strftime('%m,%Y',mktime(0,0,0,$month-1,1,$year))); 
    $prev_month_link = 
sprintf($opts['month_link'],$prev_month,$prev_year,'&lt;'); 
     
    list($next_month,$next_year) = explode(',',strftime('%m,%Y',mktime(0,0,0,$month+1,1,$year))); 
    $next_month_link = sprintf($opts['month_link'],$next_month,$next_year,'&gt;'); 
     
?> 
<div class="text-center">
    <h5 class="animated fadeIn mb-4"><blockquote class="container"><p class="mb-0"><b>AUJOURD'HUI</b></p></blockquote></h5>
</div>
<table border="0" cellspacing="0" cellpadding="2" align="center" style="font-weight: bold;background-image: linear-gradient(to right,rgba(255, 110, 196, .9),white,rgba(255, 216, 111, .9)">
        <tr style="font-weight: bold;background-image: linear-gradient(to right,red,white,red)"> 
                <td align="left"> 
                        <?php print $prev_month_link ?> 
                </td> 
                <td colspan="5" align="center"> 
                <?php print strftime('%B %Y',mktime(0,0,0,$month,1,$year)); 
?> 
                </td> 
                <td align="right"> 
                        <?php print $next_month_link ?> 
                </td> 
        </tr> 
<?php 
    $totaldays = date('t',mktime(0,0,0,$month,1,$year)); 
  
    // print out days of the week 
    print '<tr>'; 
    $weekdays = array('Su','Mo','Tu','We','Th','Fr','Sa'); 
    while (list($k,$v) = each($weekdays)) { 
        print '<td align="center">'.$v.'</td>'; 
    } 
    print '</tr><tr>'; 
    // align the first day of the month with the right week day 
    $day_offset = date("w",mktime(0, 0, 0, $month, 1, $year)); 
    if ($day_offset > 0) {  
        for ($i = 0; $i < $day_offset; $i++) { print '<td>&nbsp;</td>'; } 
    } 
    $yesterday = time() - 86400;  
 
    // print out the days 
    for ($day = 1; $day <= $totaldays; $day++) { 
        $day_secs = mktime(0,0,0,$month,$day,$year); 
        if ($day_secs >= $yesterday) {   
            if ($day_highlight && ($day == $this_day)) { 
                print sprintf('<td align="center" bgcolor="%s">%d</td>', 
                              $opts['today_color'],$day); 
            } else {
            	print sprintf('<td align="center">%d</td>',$day); 
            } 
        } else { 
            print sprintf('<td align="center">%d</td>',$day); 
        } 
        $day_offset++; 
 
        // start a new row each week //  
        if ($day_offset == 7) { 
            $day_offset = 0; 
            print "</tr>\n"; 
            if ($day < $totaldays) { print '<tr>'; } 
        } 
    } 
    // fill in the last week with blanks // 
    if ($day_offset > 0) { $day_offset = 7 - $day_offset; } 
    if ($day_offset > 0) {  
        for ($i = 0; $i < $day_offset; $i++) { print '<td>&nbsp;</td>'; } 
    } 
    print '</tr></table><br><br>'; 
}
//------------END CALENDAR---------------
//Connect to BD
include('connect.php');
//################TANTELY#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambato_Tantely' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_tantely = "31/01/20";
    $liste_v_tantely = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_tantely = str_replace('- ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace('/ ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace(' / ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace(' -', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace(' - ', '/', $latest_date_v_tantely);
        $latest_date_v_tantely = str_replace('-', '/', $latest_date_v_tantely);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        //Get date list with name
        $liste_v_tantely = $liste_v_tantely.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_tantely);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_tantely = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();

   //Query to get date of Journal de stock Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Ambato_Tantely' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_tantely = "31-01-00";
    $liste_s_tantely = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_tantely = str_replace('- ', '/', $latest_date_s_tantely);
        $latest_date_s_tantely = str_replace('/ ', '/', $latest_date_s_tantely);
        $latest_date_s_tantely = str_replace(' / ', '/', $latest_date_s_tantely);
        $latest_date_s_tantely = str_replace(' -', '/', $latest_date_s_tantely);
        $latest_date_s_tantely = str_replace(' - ', '/', $latest_date_s_tantely);
        $latest_date_s_tantely = str_replace('-', '/', $latest_date_s_tantely);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_s_tantely = $liste_s_tantely.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_tantely);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_tantely = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
 //###################END TANTELY#######################
   //################TAHINA PNEU#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambato_Pneu' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_pneu = "31/01/20";
    $liste_v_pneu = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_pneu = str_replace('- ', '/', $latest_date_v_pneu);
        $latest_date_v_pneu = str_replace('/ ', '/', $latest_date_v_pneu);
        $latest_date_v_pneu = str_replace(' / ', '/', $latest_date_v_pneu);
        $latest_date_v_pneu = str_replace(' -', '/', $latest_date_v_pneu);
        $latest_date_v_pneu = str_replace(' - ', '/', $latest_date_v_pneu);
        $latest_date_v_pneu = str_replace('-', '/', $latest_date_v_pneu);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
        $date1_en =$date1_en -> format('y/m/d');
        //Get date list with name
        $liste_v_pneu = $liste_v_pneu.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_pneu);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_pneu = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
   $qc->closeCursor();

   //Query to get date of Journal de stock Pneu
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Ambato_Pneu' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_pneu = "31-01-00";
    $liste_s_pneu = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_pneu = str_replace('- ', '/', $latest_date_s_pneu);
        $latest_date_s_pneu = str_replace('/ ', '/', $latest_date_s_pneu);
        $latest_date_s_pneu = str_replace(' / ', '/', $latest_date_s_pneu);
        $latest_date_s_pneu = str_replace(' -', '/', $latest_date_s_pneu);
        $latest_date_s_pneu = str_replace(' - ', '/', $latest_date_s_pneu);
        $latest_date_s_pneu = str_replace('-', '/', $latest_date_s_pneu);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
        $date1_en =$date1_en -> format('y/m/d');
        $liste_s_pneu = $liste_s_pneu.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_pneu);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_pneu = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
   $qc->closeCursor();
 //###################END PNEU#######################
   //################AMPARAFA#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Amparafa' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_amparafa = "09/09/09";
    $liste_v_amparafa = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_amparafa = str_replace('- ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace('/ ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace(' / ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace(' -', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace(' - ', '/', $latest_date_v_amparafa);
        $latest_date_v_amparafa = str_replace('-', '/', $latest_date_v_amparafa);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_v_amparafa = $liste_v_amparafa.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_amparafa);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_amparafa = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();

   //Query to get date of Journal de vente Amparafa
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Amparafa' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_amparafa = "31-01-00";
    $liste_s_amparafa = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_amparafa = str_replace('- ', '/', $latest_date_s_amparafa);
        $latest_date_s_amparafa = str_replace('/ ', '/', $latest_date_s_amparafa);
        $latest_date_s_amparafa = str_replace(' / ', '/', $latest_date_s_amparafa);
        $latest_date_s_amparafa = str_replace(' -', '/', $latest_date_s_amparafa);
        $latest_date_s_amparafa = str_replace(' - ', '/', $latest_date_s_amparafa);
        $latest_date_s_amparafa = str_replace('-', '/', $latest_date_s_amparafa);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_s_amparafa = $liste_s_amparafa.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_amparafa);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_amparafa = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
 //###################END AMPARAFA#######################
   //################SOALAZAINA#####################
   //Query to get date of Journal de vente Soalazaina
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Soalazaina' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_soalazaina = "09/09/09";
    $liste_v_soalazaina = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_soalazaina = str_replace('- ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace('/ ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace(' / ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace(' -', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace(' - ', '/', $latest_date_v_soalazaina);
        $latest_date_v_soalazaina = str_replace('-', '/', $latest_date_v_soalazaina);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
        $date1_en =$date1_en -> format('y/m/d');
        $liste_v_soalazaina = $liste_v_soalazaina.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_soalazaina);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_soalazaina = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
   $qc->closeCursor();

   //Query to get date of Journal de vente Soalazaina
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Soalazaina' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_soalazaina = "31-01-00";
    $liste_s_soalazaina = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_soalazaina = str_replace('- ', '/', $latest_date_s_soalazaina);
        $latest_date_s_soalazaina = str_replace('/ ', '/', $latest_date_s_soalazaina);
        $latest_date_s_soalazaina = str_replace(' / ', '/', $latest_date_s_soalazaina);
        $latest_date_s_soalazaina = str_replace(' -', '/', $latest_date_s_soalazaina);
        $latest_date_s_soalazaina = str_replace(' - ', '/', $latest_date_s_soalazaina);
        $latest_date_s_soalazaina = str_replace('-', '/', $latest_date_s_soalazaina);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
        $date1_en =$date1_en -> format('y/m/d');
        $liste_s_soalazaina = $liste_s_soalazaina.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_soalazaina);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_soalazaina = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
   $qc->closeCursor();
 //###################END SOALAZAINA#######################
    //################ELECTRONIQUE#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Electronique' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_electronique = "09/09/09";
    $liste_v_electronique = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_electronique = str_replace('- ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace('/ ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace(' / ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace(' -', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace(' - ', '/', $latest_date_v_electronique);
        $latest_date_v_electronique = str_replace('-', '/', $latest_date_v_electronique);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
        $date1_en =$date1_en -> format('y/m/d');
        $liste_v_electronique = $liste_v_electronique.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_electronique);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_electronique = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
   $qc->closeCursor();

   //Query to get date of Journal de vente Amparafa
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Ambaibo_Electronique' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_electronique = "31-01-00";
    $liste_s_electronique = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_electronique = str_replace('- ', '/', $latest_date_s_electronique);
        $latest_date_s_electronique = str_replace('/ ', '/', $latest_date_s_electronique);
        $latest_date_s_electronique = str_replace(' / ', '/', $latest_date_s_electronique);
        $latest_date_s_electronique = str_replace(' -', '/', $latest_date_s_electronique);
        $latest_date_s_electronique = str_replace(' - ', '/', $latest_date_s_electronique);
        $latest_date_s_electronique = str_replace('-', '/', $latest_date_s_electronique);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
        $date1_en =$date1_en -> format('y/m/d');
        $liste_s_electronique = $liste_s_electronique.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

        $latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_electronique);
        $latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_electronique = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

            }
   $qc->closeCursor();
 //###################END ELECTRONIQUE#######################
//################BEJOFO#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Bejofo' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_bejofo = "09/09/09";
    $liste_v_bejofo = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_bejofo = str_replace('- ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace('/ ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace(' / ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace(' -', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace(' - ', '/', $latest_date_v_bejofo);
        $latest_date_v_bejofo = str_replace('-', '/', $latest_date_v_bejofo);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_v_bejofo = $liste_v_bejofo.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_bejofo);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_bejofo = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();

   //Query to get date of Journal de vente
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Bejofo' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_bejofo = "31-01-00";
    $liste_s_bejofo = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_bejofo = str_replace('- ', '/', $latest_date_s_bejofo);
        $latest_date_s_bejofo = str_replace('/ ', '/', $latest_date_s_bejofo);
        $latest_date_s_bejofo = str_replace(' / ', '/', $latest_date_s_bejofo);
        $latest_date_s_bejofo = str_replace(' -', '/', $latest_date_s_bejofo);
        $latest_date_s_bejofo = str_replace(' - ', '/', $latest_date_s_bejofo);
        $latest_date_s_bejofo = str_replace('-', '/', $latest_date_s_bejofo);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_s_bejofo = $liste_s_bejofo.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_bejofo);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_bejofo = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
 //###################END BEJOFO#######################
      //################VEVE#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='MoraranoCh' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_MoraranoCh = "09/09/09";
    $liste_v_MoraranoCh = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_MoraranoCh = str_replace('- ', '/', $latest_date_v_MoraranoCh);
        $latest_date_v_MoraranoCh = str_replace('/ ', '/', $latest_date_v_MoraranoCh);
        $latest_date_v_MoraranoCh = str_replace(' / ', '/', $latest_date_v_MoraranoCh);
        $latest_date_v_MoraranoCh = str_replace(' -', '/', $latest_date_v_MoraranoCh);
        $latest_date_v_MoraranoCh = str_replace(' - ', '/', $latest_date_v_MoraranoCh);
        $latest_date_v_MoraranoCh = str_replace('-', '/', $latest_date_v_MoraranoCh);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_v_MoraranoCh = $liste_v_MoraranoCh.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_MoraranoCh);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_MoraranoCh = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();

   //Query to get date of Journal de vente
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='MoraranoCh' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_MoraranoCh = "31-01-00";
    $liste_s_MoraranoCh = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_MoraranoCh = str_replace('- ', '/', $latest_date_s_MoraranoCh);
        $latest_date_s_MoraranoCh = str_replace('/ ', '/', $latest_date_s_MoraranoCh);
        $latest_date_s_MoraranoCh = str_replace(' / ', '/', $latest_date_s_MoraranoCh);
        $latest_date_s_MoraranoCh = str_replace(' -', '/', $latest_date_s_MoraranoCh);
        $latest_date_s_MoraranoCh = str_replace(' - ', '/', $latest_date_s_MoraranoCh);
        $latest_date_s_MoraranoCh = str_replace('-', '/', $latest_date_s_MoraranoCh);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //echo ('data1 = '.$date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_s_MoraranoCh = $liste_s_MoraranoCh.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_MoraranoCh);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_MoraranoCh = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
 //###################END VEVE#######################
      //################AMBAIBOHO TOLE#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Tole' AND description_date NOT LIKE '%MORARANO%'AND description_date NOT LIKE '%ANDREFANA%' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_ambaibo_tole = "09/09/09";
    $liste_v_ambaibo_tole = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_ambaibo_tole = str_replace('- ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace('/ ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace(' / ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace(' -', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace(' - ', '/', $latest_date_v_ambaibo_tole);
        $latest_date_v_ambaibo_tole = str_replace('-', '/', $latest_date_v_ambaibo_tole);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_v_ambaibo_tole = $liste_v_ambaibo_tole.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_ambaibo_tole);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_ambaibo_tole = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();

   //Query to get date of Journal de vente
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'stock' AND nom_client_fournisseur ='Ambaibo_Tole' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "31-01-00";
    $latest_date_s_ambaibo_tole = "31-01-00";
    $liste_s_ambaibo_tole = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        $liste_s_ambaibo_tole = $liste_s_ambaibo_tole.'# '.$donnees['description_date'].' | '.$day_name.'<br>';
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_s_ambaibo_tole = str_replace('- ', '/', $latest_date_s_ambaibo_tole);
        $latest_date_s_ambaibo_tole = str_replace('/ ', '/', $latest_date_s_ambaibo_tole);
        $latest_date_s_ambaibo_tole = str_replace(' / ', '/', $latest_date_s_ambaibo_tole);
        $latest_date_s_ambaibo_tole = str_replace(' -', '/', $latest_date_s_ambaibo_tole);
        $latest_date_s_ambaibo_tole = str_replace(' - ', '/', $latest_date_s_ambaibo_tole);
        $latest_date_s_ambaibo_tole = str_replace('-', '/', $latest_date_s_ambaibo_tole);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
        //echo ('data1 = '.$date1);
		$date1_en =$date1_en -> format('y/m/d');

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_s_ambaibo_tole);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_s_ambaibo_tole = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
 //###################END AMBAIBOHO TOLE#######################
    //################MORARANO#####################
   //Query to get date of Journal de vente Ambato_Tantely
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Tole' AND description_date LIKE '%MORARANO%' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_morarano = "09/09/09";
    $liste_v_morarano = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_morarano = str_replace('- ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace('/ ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace(' / ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace(' -', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace(' - ', '/', $latest_date_v_morarano);
        $latest_date_v_morarano = str_replace('-', '/', $latest_date_v_morarano);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_v_morarano = $liste_v_morarano.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_morarano);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_morarano = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
   //################END MORARANO#####################

   //################ANDREFANA#####################
   //Query to get date of Journal de vente
   $query = "SELECT description_date  from mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur ='Ambaibo_Tole' AND description_date LIKE '%ANDREFANA%' GROUP BY description_date ORDER BY Date_du_Journal_mvt DESC;";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    $chaine = "";
    $date1 = "";
    $latest_date_v_andrefana = "09/09/09";
    $liste_v_andrefana = "";
    //$donnees = $qc -> fetch();
    //$latest_date = $donnees['description_date'];
    while ($donnees = $qc -> fetch())
    {
        
        $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);


        //Eviter wrong format and unwanted space for $latest_date
        $latest_date_v_andrefana = str_replace('- ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace('/ ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace(' / ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace(' -', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace(' - ', '/', $latest_date_v_andrefana);
        $latest_date_v_andrefana = str_replace('-', '/', $latest_date_v_andrefana);

        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '/', $date1);
        $date1 = str_replace('/ ', '/', $date1);
        $date1 = str_replace(' / ', '/', $date1);
        $date1 = str_replace(' -', '/', $date1);
        $date1 = str_replace(' - ', '/', $date1);
        $date1 = str_replace('-', '/', $date1);
        

        //Convert date to english format for compare
        $date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        $day_name = $date1_en -> format('l');
		$date1_en =$date1_en -> format('y/m/d');
        $liste_v_andrefana = $liste_v_andrefana.'# '.$donnees['description_date'].' | '.$day_name.'<br>';

		$latest_en = DateTime::createFromFormat('d/m/Y', $latest_date_v_andrefana);
		$latest_en = $latest_en -> format('y/m/d');
        if ($latest_en < $date1_en) {
            $latest_date_v_andrefana = $date1 ;
            //echo '#'.$latest_date.'<br>';
        }

        //echo '>>'.$date1.'<br>';

		    }
   $qc->closeCursor();
   
  
   
   //################END ANDREFANA#####################
?>
<div class="flex-center flex-column">
   <table class="table-bordered table-sm" style="font-weight: bold;background-image: linear-gradient(to right,white,pink,#9c27b0,#673ab7,#3f51b5,#2196f3,#03a9f4,#00bcd4,#009688,#4caf50,#8bc34a,#cddc39,#ffeb3b,#ffc107,#ff9800,#ff5722,#9e9e9e,white)">
        <thead>
        <tr>
        <th class="text-center" colspan="4" style="font-weight: bold;background-image: linear-gradient(to right,red,white,red)"><b>DATE DU DERNIER JOURNAL</b></th>
        </tr>
        <tr>
        <th class="text-center"><b>Point de vente</b></th>
        <th class="text-center"><b>Inventaire</b></th>
        <th class="text-center"><b>Vente</b></th>
        <th class="text-center"><b>Jours de Retard</b></th>
        </tr>
        </thead>
        <tbody>

<!------------------------------------------------->
       <tr>
       	<td><b>AMBATO TANTELY</b></td>
       	<td><b><a href="" data-toggle="modal" data-target="#s_tantely"><?php echo $latest_date_s_tantely;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_tantely" class="text-marron"><?php echo $latest_date_v_tantely;?></a></b></td>
        <!-----------------RETARD------------------>
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_tantely);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        function numberBetween($varToCheck, $high, $low) {
        if($varToCheck < $low) return false;
        if($varToCheck > $high) return false;
        return true;
        }
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!------------------END RETARD----------------------->
       </tr>
       <tr>
       	<td><b>AMPARAFA</b></td>
       	<td><b><a href="" data-toggle="modal" data-target="#s_aparafa"><?php echo $latest_date_s_amparafa;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_amparafa" class="text-marron"><?php echo $latest_date_v_amparafa;?></a></b></td>
                <!-----------------RETARD------------------>
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_amparafa);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!----------------------------------------->
       </tr>
       <tr>
       	<td><b>BEJOFO</b></td>
       	<td><b><a href="" data-toggle="modal" data-target="#s_bejofo"><?php echo $latest_date_s_bejofo;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_bejofo" class="text-marron"><?php echo $latest_date_v_bejofo;?></a></b></td>
        <!-----------------RETARD------------------>
        <?php
        //$date1_en = DateTime::createFromFormat('d/m/Y', $date1);
        //$latest_date_v_bejofo = "02/01/21";
        //echo $latest_date_v_bejofo;
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_bejofo);
        
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!------------------END RETARD----------------------->
       </tr>
       <!-------------------------------------------------
       <tr>
        <td><b>SOALAZAINA</b></td>
        <td><b><a href="" data-toggle="modal" data-target="#s_soalazaina"><?php echo $latest_date_s_soalazaina;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_soalazaina" class="text-marron"><?php echo $latest_date_v_soalazaina;?></a></b></td>
        <!-----------------RETARD------------------
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_soalazaina);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!------------------END RETARD----------------------->
       </tr>
       
       <tr>
       	<td><b>MORARANOCH</b></td>
       	<td><b><a href="" data-toggle="modal" data-target="#s_MoraranoCh"><?php echo $latest_date_s_MoraranoCh;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_MoraranoCh" class="text-marron"><?php echo $latest_date_v_MoraranoCh;?></a></b></td>
      <!------------RETARD------------------>
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_MoraranoCh);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //---------------------------------      
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>

       <!----------------END RETARD-----------------------
       </tr>
       <tr>
       	<td><b>AMBAIBOHO TOLE</b></td>
       	<td rowspan="3"><b><a href="" data-toggle="modal" data-target="#s_tole"><?php echo $latest_date_s_ambaibo_tole;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_tole" class="text-marron"><?php echo $latest_date_v_ambaibo_tole;?></a></b></td>
        <!-----------------RETARD------------------
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_ambaibo_tole);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!------------------END RETARD-----------------------
       </tr>
       <tr>
       	<td><b>MORARANO</b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_morarano" class="text-marron"><?php echo $latest_date_v_morarano;?></a></b></td>
        <!-----------------RETARD------------------
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_morarano);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!------------------END RETARD-----------------------
       </tr>
       <tr>
       	<td><b>ANDREFANA</b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_andrefana" class="text-marron"><?php echo $latest_date_v_andrefana;?></a></b></td>
        <!-----------------RETARD------------------
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_andrefana);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
       </tr>
       <tr>
        <td><b>AMBATO MIANGALY</b></td>
        <td><b><a href="" data-toggle="modal" data-target="#s_pneu"><?php echo $latest_date_s_pneu;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_pneu" class="text-marron"><?php echo $latest_date_v_pneu;?></a></b></td>
                <!-----------------RETARD------------------
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_pneu);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
        <!----------------------------------------->
       </tr>
       <!-----------
       <tr>
        <td><b>ELECTRONIQUE</b></td>
        <td><b><a href="" data-toggle="modal" data-target="#s_electronique"><?php echo $latest_date_s_electronique;?></a></b></td>
        <td><b><a href="" data-toggle="modal" data-target="#v_electronique"><?php echo $latest_date_v_electronique;?></a></b></td>
             ---------------RETARD----------------
        <?php
        $date_en = DateTime::createFromFormat('d/m/y', $latest_date_v_electronique);
        $date_en =$date_en -> format('y/m/d');
        $date_en = str_replace('/', '-', $date_en);
        $today = date("y-m-d");
        $date_start = date_create($date_en);
        $date_end = date_create($today);
        $diff=date_diff($date_start,$date_end);
        //$days = $diff->format("%R%a days");
        $days = $diff->format("%a");
        $couleur = "";
        $notification = "";
        //---------------RANGE---------------
        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        $notification = "Very Good";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        $notification = "Good";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        $notification = "Bad";
        }
        //-----------------------------------
            
        ?>
        <td><b><?php echo $notification;?><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></b></td>
      ---------------------------------------
       </tr>
       --------->
<!------------------END RETARD----------------------->
<!------------------ modal form AMBATO VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_tantely" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE TANTELY</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_tantely;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
    <!------------------ modal form AMBATO PNEU------------------------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_pneu" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">JOURNAL DE VENTE TAHINA PNEU</h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <?php echo $liste_v_pneu;?>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!------------------ modal form TAHINA STOCK------------------------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_pneu" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">JOURNAL DE STOCK TAHINA PNEU</h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <?php echo $liste_s_pneu;?>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!------------------ modal form AMBATO STOCK------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_tantely" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE STOCK TANTELY</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_s_tantely;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------------------------------------------------>
<!------------------ modal form SOALAZAINA VENTE------------------------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_soalazaina" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">JOURNAL DE VENTE SOALAZAINA</h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <?php echo $liste_v_soalazaina;?>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!------------------ modal form SOALAZAINA STOCK------------------------->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_soalazaina" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">JOURNAL DE STOCK SOALAZAINA</h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <?php echo $liste_s_soalazaina;?>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!------------------------------------------------------------>
<!------------------ modal form VEVE VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_MoraranoCh" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE MORARANOCH</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_MoraranoCh;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form VEVE STOCK------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_MoraranoCh" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE STOCK MORARANOCH</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_s_MoraranoCh;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form AMPARAFA VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_amparafa" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE AMPARAFA</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_amparafa;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form AMPARAFA STOCK------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_aparafa" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE STOCK AMPARAFA</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_s_amparafa;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------------------------------------------------>
<!------------------ modal form BEJOFO VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_bejofo" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE BEJOFO</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_bejofo;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form BEJOFO STOCK------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_bejofo" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE STOCK BEJOFO</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_s_bejofo;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------------------------------------------------>
<!------------------ modal form ELECRTONIQUE VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_electronique" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE ELECRTONIQUE</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_electronique;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form ELECRTONIQUE STOCK------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_electronique" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE STOCK ELECRTONIQUE</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_s_electronique;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------------------------------------------------>
<!------------------ modal form AMBAIBO TOLE VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_tole" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE AMBAIBO TOLE</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_ambaibo_tole;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form AMBAIBO TOLE STOCK------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="s_tole" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE STOCK AMBAIBO TOLE</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_s_ambaibo_tole;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form MORARANO VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_morarano" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE MORARANO</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_morarano;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------ modal form ANDREFANA VENTE------------------------->
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="v_andrefana" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title">JOURNAL DE VENTE ANDREFANA</h4>
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
					</div>
					<div class="modal-body">
					<!-- actual form -->
					<?php echo $liste_v_andrefana;?>
					<!-- actual form ends -->
					</div>
				</div>
			</div>
		</div>
<!------------------------------------------------------------>
        </tbody>
    </table>
    </div>
<br>
<br>
<script type="text/javascript">

</script>