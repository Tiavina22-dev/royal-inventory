<?php

//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');
//--------------------QUERY VERSEMENT TODAY
include('connect.php');
  $today = '';
  $query_today = "SELECT SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE()";
  $query_today = $bdd->prepare($query_today);
  $query_today->execute(array());
  while ($data = $query_today -> fetch())
  {
    $today = number_format(floor(ABS($data['st'])),0, "", " ");
  }
  $query_today -> closeCursor();
//--------------------QUERY VERSEMENT DU MOI ACTUEL
  $vrst_current_month = 0;
  $query_current_month = "SELECT *,SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND Date_du_Journal_mvt >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND Date_du_Journal_mvt < LAST_DAY(CURDATE()) + INTERVAL 1 DAY";
  $query_current_month = $bdd->prepare($query_current_month);
  $query_current_month->execute(array());
  while ($data = $query_current_month -> fetch())
  {
    $vrst_current_month = number_format(ABS($data['st']),0, "", " ");
  }

  $query_current_month -> closeCursor();
//------------------QUERY DU VERSEMENT DU MOI PASSE
  $last_month = 0;
  $mois_last ='';
  $query_last = "SELECT *,SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND Date_du_Journal_mvt >= LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 2 MONTH AND Date_du_Journal_mvt < LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH";
  //(SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)
  
  $query_last = $bdd->prepare($query_last);
  $query_last->execute(array());
  while ($data = $query_last -> fetch())
  {
    $last_month = number_format(ABS($data['st']),0, "", " ");
    $mois_last = $data['Date_du_Journal_mvt'];
  }
  $query_last -> closeCursor();
  if (strlen($mois_last)>0) {
    $mois_last = DateTime::createFromFormat('Y-m-d',$mois_last) ;
    $mois_last = $mois_last -> format('F Y : ');
  }

  //--------------PIC FOR ALL
              $query_pic = "SELECT YEAR,MONTH,st FROM (SELECT YEAR(Date_du_Journal_mvt) as YEAR,date_format(Date_du_Journal_mvt,'%b') as MONTH, ABS(SUM(prix_unitaire*qt)) as st FROM mvt WHERE type_de_mvt = 'vente'  GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt)) x WHERE st = (SELECT MAX(st) FROM (SELECT YEAR(Date_du_Journal_mvt) as YEAR,date_format(Date_du_Journal_mvt,'%b') as MONTH, ABS(SUM(prix_unitaire*qt)) as st FROM mvt WHERE type_de_mvt = 'vente'  GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt)) y)";
            $query_pic = $bdd->prepare($query_pic);
            $query_pic->execute(array());
            $pic_aff = '';
            $mois_pic = '';
              while ($data = $query_pic -> fetch())
            {
              $pic_aff = number_format(floor($data['st']),0, "", " ");
              $mois_pic = 'Pic '.$data['MONTH'].' '.$data['YEAR'].' : ';
            }
              $query_pic->closeCursor();
  //QUERY FOR Last month

?>
<div class="px-xl-5">
  <div class="text-right">
  <span class="btn text-white text-right text-uppercase" style="background-image: linear-gradient(to right,rgba(0,0,255),rgba(173,173,255)"><b><?php echo DATE('F Y : ')."<span class='display-4'>".$vrst_current_month."</span>"; ?> Ar<br><?php echo $mois_last.$last_month; ?> Ar<br><?php echo $mois_pic.$pic_aff; ?> Ar</b></span>
  <span class="btn text-right" style="background-image: linear-gradient(to right,black,rgba(152,141,141)"><b><span class="display-1 text-vert"><?php echo $today; ?></span> AR</b></span>
  </div>
</div>
<div class="px-xl-5">
   <table id="example" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr style="background-image: linear-gradient(to right,yellow,rgba(157,255,0),yellow">
        <th><span class="font-weight-bold">POINT DE VENTE</span></th>
        <th class="text-marron">RETARD</th>
        <th></th>
        <th></th>
        <th class="text-right"><span class="font-weight-bold text-danger">MOIS RECORD</span></th>
        <th></th>
        <th class="text-right"><span class="text-info font-weight-bold">MOIS PRECEDENT</span></th>
        <th></th>
        <th class="text-right"><span class="text-purple font-weight-bold">MOIS RECENT</span></th>
        <th class="text-right"><span class="text-danger font-weight-bold">MOYENNE/JOUR</span></th>
        <th class="text-right"><span class="text-moyenne font-weight-bold">AUJOURD'HUI</span></th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$image_path_x = "img_x/default_x.png";
$today_tl = 0;
//Query searcher word
function numberBetween($varToCheck, $high, $low) {
        if($varToCheck < $low) return false;
        if($varToCheck > $high) return false;
        return true;
        }
//GET POINT DE VENTE
$query_shop = "SELECT DISTINCT(nom_client_fournisseur) as nf FROM mvt WHERE type_de_mvt = 'vente' ORDER BY nom_client_fournisseur";
$query_shop = $bdd->prepare($query_shop);
$query_shop->execute(array());
while ($donnees = $query_shop -> fetch())
{  
  //QUERY FOR PIC
            
            $query_pic = "SELECT YEAR,MONTH,st FROM (SELECT YEAR(Date_du_Journal_mvt) as YEAR,date_format(Date_du_Journal_mvt,'%b') as MONTH, ABS(SUM(prix_unitaire*qt)) as st FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?  GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt)) x WHERE st = (SELECT MAX(st) FROM (SELECT YEAR(Date_du_Journal_mvt) as YEAR,date_format(Date_du_Journal_mvt,'%b') as MONTH, ABS(SUM(prix_unitaire*qt)) as st FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?  GROUP BY YEAR(Date_du_Journal_mvt)*100 + MONTH(Date_du_Journal_mvt)) y)";
            $query_pic = $bdd->prepare($query_pic);
            $query_pic->execute(array($donnees['nf'],$donnees['nf']));
            $pic_aff = '';
            $mois_pic = '';
              while ($data = $query_pic -> fetch())
            {
              $pic_aff = number_format(floor($data['st']),0, "", ",");
              $mois_pic = '<b>Pic '.$data['MONTH'].' '.$data['YEAR'].'</b>';
            }
              $query_pic->closeCursor();
  //QUERY FOR Last month
  $tl_last = 0;
  $mois_last ='';
  $query_last = "SELECT *,SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND Date_du_Journal_mvt >= LAST_DAY((SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)) + INTERVAL 1 DAY - INTERVAL 2 MONTH AND Date_du_Journal_mvt < LAST_DAY((SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)) + INTERVAL 1 DAY - INTERVAL 1 MONTH";
  //(SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)
  
  $query_last = $bdd->prepare($query_last);
  $query_last->execute(array($donnees['nf'],$donnees['nf'],$donnees['nf']));
  while ($data = $query_last -> fetch())
  {
    $tl_last = ABS($data['st']);
    $mois_last = $data['Date_du_Journal_mvt'];
  }
  $query_last -> closeCursor();
  $last_aff = '';
  if (strlen($mois_last)>0) {
    $mois_last = DateTime::createFromFormat('Y-m-d',$mois_last) ;
    $mois_last = $mois_last -> format('F Y ');
    $last_aff = number_format(floor($tl_last),0, "", ",");
  }
  
  //QUERY FOR TODAY IF EXIST
  $today_aff = '';
  $query_today = "SELECT SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND DATE_FORMAT(date_time,'%Y-%m-%d') = CURDATE()";
  $query_today = $bdd->prepare($query_today);
  $query_today->execute(array($donnees['nf']));
  while ($data = $query_today -> fetch())
  {
    $today_aff = floor(ABS($data['st']));
    $today_tl = $today_tl + $today_aff;
  }
 $query_today -> closeCursor();
  //QUERY FOR CURRENT MONTH
  $tl_current_month = 0;
  $query_current_month = "SELECT *,SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND Date_du_Journal_mvt >= LAST_DAY((SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND Date_du_Journal_mvt < LAST_DAY((SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)) + INTERVAL 1 DAY";
  $query_current_month = $bdd->prepare($query_current_month);
  $query_current_month->execute(array($donnees['nf'],$donnees['nf'],$donnees['nf']));
  while ($data = $query_current_month -> fetch())
  {
    $tl_current_month = ABS($data['st']);
    $mois_name = $data['Date_du_Journal_mvt'];
  }

  $query_current_month -> closeCursor();
  $current_month_aff = '';
  if (strlen($mois_name)>0) {
    $mois_name = DateTime::createFromFormat('Y-m-d',$mois_name) ;
    $mois_name = $mois_name -> format('F Y ');
    $current_month_aff = number_format(floor($tl_current_month),0, "", ",");
  } 
  //MOYENNE PAR JOUR DU 7 DERNIER JOUR
  $average = 0;
  $query_current_month = "SELECT AVG(st) as average FROM (SELECT Date_du_Journal_mvt,SUM(qt*prix_unitaire) as st FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND Date_du_Journal_mvt >= LAST_DAY((SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)) + INTERVAL 1 DAY - INTERVAL 1 MONTH AND Date_du_Journal_mvt < LAST_DAY((SELECT MAX(Date_du_Journal_mvt) FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?)) + INTERVAL 1 DAY GROUP BY Date_du_Journal_mvt ORDER BY Date_du_Journal_mvt DESC LIMIT 7) x";
  $query_current_month = $bdd->prepare($query_current_month);
  $query_current_month->execute(array($donnees['nf'],$donnees['nf'],$donnees['nf']));
  while ($data = $query_current_month -> fetch())
  {
    $average = ABS($data['average']);
  }

  $query_current_month -> closeCursor();
//GET LATEST JOURNAL
  $query_latest = "SELECT MAX(Date_du_Journal_mvt) as date_max,nom_client_fournisseur FROM mvt WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? ";
  $query_latest = $bdd->prepare($query_latest);
  $query_latest->execute(array($donnees['nf']));
  while ($data = $query_latest -> fetch())
  {
    $date_max = $data['date_max'];
  }
  $query_latest -> closeCursor();
  #calcule jour
  $days = 0;
  $today = date("Y-m-d");
  $date_start = date_create($date_max);
  $date_end = date_create($today);
  $diff=date_diff($date_start,$date_end);
  $days = $diff->format("%a");
  //---------------COLOR RANGE---------------

        if (numberBetween($days, 3, 0)) {
        $couleur = "w3-green";
        }
        if (numberBetween($days, 5, 4)) {
        $couleur = "w3-purple";
        }
        if ($days > 5) {
        $couleur = "w3-red";
        }
  //-----------------------------------

?>
<tr>
        <td class="text-uppercase font-weight-bold text-light"><?php echo $donnees['nf']; ?></td>
        <td><?php echo $date_max; ?></td>
        <td><span class="w3-badge w3-right w3-margin-right <?php echo $couleur;?>"><?php echo $days.' j';?></span></td>
        <td class="text-pink"><?php echo $mois_pic; ?></td>
        <td class = 'text-right text-pink'><b><?php echo $pic_aff; ?></b></td>
        <td class = 'text-primary'><b><?php echo $mois_last; ?></b></td>
        <td class = 'text-right text-primary'><b><?php echo $last_aff; ?></b></td>
        <td class = 'text-purple'><b><?php echo $mois_name; ?></b></td>
        <td class = 'text-purple text-right'><b><?php echo $current_month_aff; ?></b></td>
        <td class = 'text-right text-orange-1'><b><?php echo number_format($average,0, "", ","); ?></b></td>
        <td class = 'text-right text-danger'><b><?php echo number_format($today_aff,0, "", ","); ?></b></td>
</tr>
        
<?php
}
?>
        </tbody>
        <thead>
        <tr style="background-image: linear-gradient(to right,yellow,rgba(157,255,0),yellow">
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th ></th>
        <th class="text-right text-danger"><b><?php echo number_format($today_tl,0, "", ","); ?></b></th>
        </tr>
        </thead>
    </table>
</div>
    <br>
    <br>
 <?php
 $query_shop->closeCursor();
?>
<br>
<br>
<br>
<!-------------END SIMPLE SEARCH-------------------------->
