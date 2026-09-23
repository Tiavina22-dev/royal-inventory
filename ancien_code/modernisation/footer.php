<link rel="stylesheet" href="css/w3.css">
<style>
.animated-heading {
    color: #FFFFFF;
    font-family: arial;
    height: 22px;
    overflow: hidden;
    border-radius: 10px;
    padding: 0px 15px;
}
.txt{
    text-align: left;
    font-size: 11px;
    border-radius: 10px;
    display: block;
    line-height: 15px;
}
.txt:first-child{
    animation: spin 12s infinite;
}
@-webkit-keyframes spin {
    0%{
        -webkit-margin-top: 0;
    }
    9%{
        margin-top: -22px;
    }
    18%{
        margin-top: -44px;
    }
    27%{
        margin-top: -64px;
    }
    36%{
        margin-top: -86px;
    }
    50%{
        margin-top: -98px;
    }
}
@keyframes spin {
    0%{
        margin-top: 0;
    }
    9%{
        margin-top: -22px;
    }
    18%{
        margin-top: -44px;
    }
    27%{
        margin-top: -68px;
    }
    36%{
        margin-top: -90px;
    }
    50%{
        margin-top: -112px;
    }
} 
</style>
<?php 
include('connect.php');
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
  $fullname = $_SESSION['fullname'];
}
else 
{
  //default pdp
  $username = "default";
}
$confirm_verify = 'NO';
//----GET ALL STOCK TASK IN PROGRESS
		$query_stock_prep = $bdd->prepare('SELECT *,MAX(date_time_stock_prep) as tmax FROM stock_prep GROUP BY description_date ORDER BY user_stock_prep');
        $query_stock_prep->execute(array());
		$nb_stock_prep = $query_stock_prep->rowCount ();
    $query_stock_prep ->closeCursor();
		//----------------------------------------------
		$query_stock_prep_calc = $bdd->prepare('SELECT *,MAX(date_time_stock_prep_calc) as tmax FROM stock_prep_calc GROUP BY description_date ORDER BY user_stock_prep');
        $query_stock_prep_calc->execute(array());
		$nb_stock_prep_calc = $query_stock_prep_calc->rowCount ();
    $query_stock_prep_calc ->closeCursor();
//-----GET ALL VENTE TASK IN PROGRESS
		$query_commande = $bdd->prepare('SELECT *,MAX(date_time_commande) as tmax FROM commande GROUP BY description_date ORDER BY user');
        $query_commande->execute(array());
		$nb_commande = $query_commande->rowCount ();
    $query_commande ->closeCursor();
		//----------------------------------------------
		$query_vente_calc = $bdd->prepare('SELECT *,MAX(date_time_vente_calc) as tmax FROM vente_calc GROUP BY description_date ORDER BY user');
        $query_vente_calc->execute(array());
		$nb_vente_calc = $query_vente_calc->rowCount ();
    $query_vente_calc ->closeCursor();
    //----------NEW NAME NOTIF-----------------------
    $query_new_name = $bdd->prepare('SELECT checking,date_time_x,reference_x,user_x,user_mvt FROM produit INNER JOIN mvt ON mvt.id_x = produit.id_x WHERE LENGTH(checking) IS NULL AND date_time_x  > date_sub(now(), interval 1 week);');
    $query_new_name->execute(array());
    $nb_new_name = $query_new_name->rowCount ();
    //------------------------------------------------------

?>
<nav class="navbar fixed-bottom navbar-expand-sm navbar-dark app-bottombar">
  <!-- Brand -->
  <!--
  <a class="navbar-brand" href="#">Logo</a>
	-->
  <!---------------->
     <!--------------------------------------------->
        <!-- Dropdown Profile -->
    <div class="text-white ">
      <img src="img/royal1.png" height="45" width="60" align="middle" />
    </div>
    <h1 class="animated-heading">
      <span class="txt">TICKETING XPRINTER <b><label id="xprinterStatus"></b></span>
      <span class="txt">CANON PRINTER <b><label id="canonprinterStatus"></b></span>
      <span class="txt">PC ELECTRONIQUE <b><label id="electroniquepcStatus"></b></span>
      <span class="txt">TICKETING XPRINTER <b><label id="xprinterStatus3"></b></span>
      <span class="txt">CANON PRINTER <b><label id="canonprinterStatus2"></b></span>
      <span class="txt">TICKETING XPRINTER <b><label id="xprinterStatus4"></b></span>
    </h1>
    <!---------------------------------------------> 
    <span class="nav-item dropup">
          <a class="nav-link text-white" href="controle_pannel.php"><b>CONTROLE</b></a>
    </span>
            <!--------------------------------------------->
        <span class="nav-item dropup">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>EN COURS <span class="w3-badge w3-margin-right w3-red"><?php echo $nb_commande+$nb_vente_calc+$nb_stock_prep+$nb_stock_prep_calc; ?></span></b></a>
          <div class="dropdown-menu">
          	<a class="dropdown-item" href="#"><span class="text-secondary" style="font-weight: bold">STOCK (<?php echo $nb_stock_prep+$nb_stock_prep_calc; ?>)</span></a>
                <label id="tracking_stock">Please wait...<img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></label>
			     <a class="dropdown-item" href="#"><span class="text-primary" style="font-weight: bold">VENTE (<?php echo $nb_commande+$nb_vente_calc; ?>)</span></a>
                <label id="tracking_vente">Please wait...<img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></label>
          <!----<a class="dropdown-item" href="#"><span class="text-warning" style="font-weight: bold">ACTIVITE AUJOURD'HUI</span></a>
                <label id="tracking_activity">Please wait...<img src="img/loading6.gif" height="30" width="30" background alt="Edit"/></label>------>
          </div>
        </span>
        <!--------------------------------------------->
        <span class="nav-item dropup">
          <a class="nav-link text-white" href="tracking_activity.php"><b>ACTIVITE AUJOURD'HUI</b></a>
        </span>
        <!--------------------------------------------->
        <span class="nav-item dropup">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>ANALYSE</b></a>
          <div class="dropdown-menu">
            <span class="nav-item dropup">
            <a class="dropdown-item" href="analyse_stock.php" style="font-weight: bold;background-image: linear-gradient(rgba(243,125,44),rgba(249,195,157),rgba(243,125,44))"><span style="font-weight: bold">STOCK</span></a>
            <a class="dropdown-item" style="font-weight: bold;background-image: linear-gradient(rgba(32, 150, 255, .9),rgba(255, 110, 196, .9),rgba(32, 150, 255, .9))" href="analyse_ecart_prix.php"><span style="font-weight: bold">PRIX NON CONFORME</span></a>
            <a class="dropdown-item" style="font-weight: bold;background-image: linear-gradient(rgba(243,125,44),rgba(249,195,157),rgba(243,125,44))" href="analyse_vente.php"><span style="font-weight: bold">VENTE</span></a>
            <a class="dropdown-item" style="font-weight: bold;background-image: linear-gradient(rgba(32, 150, 255, .9),rgba(255, 110, 196, .9),rgba(32, 150, 255, .9))" href="report_2.php"><span style="font-weight: bold">DIAGRAMME MENSUEL</span></a>
            <a class="dropdown-item" style="font-weight: bold;background-image: linear-gradient(rgba(243,125,44),rgba(249,195,157),rgba(243,125,44))" href="report_1.php"><span style="font-weight: bold">RAPPORT JOURNALIER</span></a>
            </span>
          </div>
        </span>
        <!--------------------------------------------->
        <?php
        //Check history change
        $query_history_vente = "SELECT COUNT(*) as sm, responsable FROM history WHERE  type = 'Mvt_Vente' GROUP BY responsable";
        $query_history_vente = $bdd->prepare($query_history_vente);
        $query_history_vente -> execute(array());


        $query_history_stock = "SELECT COUNT(*) as sm, responsable FROM history WHERE  type = 'Mvt_Stock' GROUP BY responsable";
        $query_history_stock = $bdd->prepare($query_history_stock);
        $query_history_stock -> execute(array());

        ?>
        <!--------------------------------------------->
        <span class="nav-item dropup">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>CHANGE <span class='w3-badge w3-red'>!</span></b></a>
          <div class="dropdown-menu">
            <span class="nav-item dropup">
            <a class="dropdown-item" href="stock_recap.php"><span class="text-secondary" style="font-weight: bold">STOCK</span><br>
              <?php
              while ($donnees_hs = $query_history_stock -> fetch())
              {
              echo ' |By '.$donnees_hs['responsable']." <span class='w3-badge w3-yellow'>".$donnees_hs['sm'].'</span><br>';
              }
              $query_history_stock -> closeCursor();
              ?>

            </a>
            <a class="dropdown-item" href="vente_recap.php"><span class="text-info" style="font-weight: bold">VENTE</span><br>
              <?php
              while ($donnees_hv = $query_history_vente -> fetch())
              {
              echo ' |By '.$donnees_hv['responsable']." <span class='w3-badge w3-yellow'>".$donnees_hv['sm'].'</span><br>';
              }
              $query_history_vente -> closeCursor();
              ?>
            </a>
            </span>
          </div>
        </span>
        <!--------------------------------------------->
                <!--------------------------------------------->
        <span class="nav-item dropup">
          <a class="nav-link text-white" href="new_product_notif.php"><b>NEW NAME </b><?php if ($nb_new_name > 0) {?><span class="w3-badge w3-margin-right w3-red"><?php echo $nb_new_name; ?></span><?php 

          //NOTIFICATION FOR CHECKER
          //QUERY to get product for each by QT PV
         $query_new_x = "SELECT * FROM produit INNER JOIN mvt ON mvt.id_x = produit.id_x WHERE date_time_x  > date_sub(now(), interval 1 week)";
        $query_new_x = $bdd->prepare($query_new_x);
        $query_new_x->execute(array());
        ?>
            <?php
            $no = 0;
                while ($data = $query_new_x -> fetch())
                {
                	$user_check = (strstr( $data['checking'], $username ) ? "Yes" : "No" );
                	if ($user_check == "Yes") {
                        echo "OK";
                        $query_new_x ->closeCursor();
                    }else{
                        if ($username != "NOT_PERMIT") //NOT_PERMIT
                        {
                        echo "<span class='w3-badge w3-margin-right w3-red'>!</span>".strtoupper($fullname)." : ATAOVY NY FANAMARINANAO AZAFADY";
                        $confirm_verify = 'YES';
                        }
                        $query_new_x ->closeCursor();
                }
                }
                $query_new_x ->closeCursor();
      } ?></a>
      </span>
      <span class="nav-item dropup">
        <a class="nav-link text-white" href="urgent.php">
      <?php
      //---------------------
      $query_urgent = "SELECT * FROM history WHERE type = 'urgent'";
      $query_urgent = $bdd->prepare($query_urgent);
      $query_urgent->execute(array());
      $task_number = $query_urgent->rowCount ();
      if ($task_number > 0) {
        
        ?>
        <b><span class='w3-badge w3-red'><?php echo $task_number; ?> - URGENT</span></b>
        <?php
      } else {
        ?>
        +URG
        <?php
      }
      ?>
      </a>
    </span>
        <!--------------------------------------------->

</nav>
<script src="js/jquery.js"></script>
<script type="text/javascript">
  //Refresh each 15s-------
    setInterval(function(){
        refreshAllSites('getscript');
        xprinter();
        tracking_activity();
        tracking_vente();
        tracking_stock();
    },15000);
  //-----------------------
</script>
    <script type="text/javascript">
var sites = {
  electronique_pc: {
    url: "http://CLIENT1-GS",
    id: "electroniquepcStatus"
  },
  canon_printer: {
    url: "http://192.168.0.101",
    id: "canonprinterStatus"
  },
  canon_printer2: {
    url: "http://192.168.0.101",
    id: "canonprinterStatus2"
  }
};

// Default
refreshAllSites('getscript');

function refreshAllSites(approach) {
  Object.keys(sites).forEach(function(name) {
    // Four approaches
    checkStatusUsingGetScript(sites[name]);
  });
}

// Approach 4: Using GetScript
function checkStatusUsingGetScript(site) {
  $.getScript(site.url + "?callback=?").done(function() {
    document.getElementById(site.id).innerHTML = "ONLINE";
    document.getElementById(site.id).style.color = '#00FA25';
  }).fail(function() {
    document.getElementById(site.id).innerHTML = "OFFLINE";
    document.getElementById(site.id).style.color = 'red';
  });
}

// Other functions
  function xprinter() 
  {
  //alert('hi');
  //ping divece without web server
      $.ajax({
        url: 'xprinter_state.php',
        type: 'GET',
        dataType: 'html'
    })
    .done(function( data ) {
        //$('#xprinterStatus').html( data ); // data came back ok, so display it
        //Audio for xprinter
        var audio = new Audio('sound/beep.mp3');
        //alert('hi');
        if (data=='MATY') {
        audio.play();
        document.getElementById('xprinterStatus').innerHTML = "OFFLINE";
        document.getElementById('xprinterStatus').style.color = 'red';
        //------------------------------------------------------------
        document.getElementById('xprinterStatus3').innerHTML = "OFFLINE";
        document.getElementById('xprinterStatus3').style.color = 'red';
        //------------------------------------------------------------
        document.getElementById('xprinterStatus4').innerHTML = "OFFLINE";
        document.getElementById('xprinterStatus4').style.color = 'red';
        //DIV ON ROYAL COMMANDE
        document.getElementById('xprinterStatus2').innerHTML = "MATY";
        document.getElementById('xprinterStatus2').style.color = 'red';
        } else 
        {
          document.getElementById('xprinterStatus').innerHTML = "ONLINE";
          document.getElementById('xprinterStatus').style.color = '#00FA25';
          //---------------------------------------------------------
          document.getElementById('xprinterStatus3').innerHTML = "ONLINE";
          document.getElementById('xprinterStatus3').style.color = '#00FA25';
          //---------------------------------------------------------
          document.getElementById('xprinterStatus4').innerHTML = "ONLINE";
          document.getElementById('xprinterStatus4').style.color = '#00FA25';
          //DIV ON ROYAL COMMANDE
          document.getElementById('xprinterStatus2').innerHTML = "VELONA";
          document.getElementById('xprinterStatus2').style.color = 'green';
        }
    })
    .fail(function() {
        $('#xprinterStatus').prepend('Error retrieving new messages..'); // there was an error, so display an error
    });
  }
      //--------------------------------------------
  //Function of tracking activity
    function tracking_activity() 
  {
  //alert('hi');
      $.ajax({
        url: 'tracking_activity.php',
        type: 'GET',
        dataType: 'html'
    })
    .done(function( data ) {
        //Audio for xprinter
        //alert('hi');
        //if (data=='MATY') {
        document.getElementById('tracking_activity').innerHTML = data;
        //document.getElementById('tracking_activity').style.color = 'red';
        

    })
    .fail(function() {
        $('#tracking_activity').prepend('Error retrieving new messages..'); // there was an error, so display an error
    });
  }
    //--------------------------------------------
    //Function of tracking vente
    function tracking_vente() 
  {
  //alert('hi');
      $.ajax({
        url: 'tracking_vente.php',
        type: 'GET',
        dataType: 'html'
    })
    .done(function( data ) {
        //Audio for xprinter
        //alert('hi');
        //if (data=='MATY') {
        document.getElementById('tracking_vente').innerHTML = data;
        //document.getElementById('tracking_activity').style.color = 'red';
        

    })
    .fail(function() {
        $('#tracking_vente').prepend('Error retrieving new messages..'); // there was an error, so display an error
    });
  }
    //--------------------------------------------
    //Function of tracking stock
    function tracking_stock() 
  {
  //alert('hi');
      $.ajax({
        url: 'tracking_stock.php',
        type: 'GET',
        dataType: 'html'
    })
    .done(function( data ) {
        //Audio for xprinter
        //alert('hi');
        //if (data=='MATY') {
        document.getElementById('tracking_stock').innerHTML = data;
        //document.getElementById('tracking_activity').style.color = 'red';
        

    })
    .fail(function() {
        $('#tracking_stock').prepend('Error retrieving new messages..'); // there was an error, so display an error
    });
  }
    //--------------------------------------------
    </script>
    <?php
    /* 
    if ($confirm_verify == 'YES') {
    	echo"<script> alert ('MANANA ASA FANAMARINANA MAIKA IANAO') </script>";
    }
    */
    ?>
