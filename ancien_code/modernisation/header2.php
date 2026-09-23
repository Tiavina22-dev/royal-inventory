<?php
session_start();
if (isset($_SESSION['fullname'])) 
{
  $fullname = $_SESSION['fullname'];
  $Id_User = $_SESSION['Id_User'];
  $username = $_SESSION['User_Name'];
}
else 
{
  $fullname = "";
  $username = "";
  $url = basename($_SERVER['PHP_SELF']);
  setcookie("url",$url, time()+5);
  header("location: home");
}
if (isset($_SESSION['img_path'])) 
{
  $img_path = $_SESSION['img_path'];
}
else 
{
  //default pdp
  $img_path = "img/default_img/default_pdp.png";
}
$phone ='03X XX XXX XX';
if (isset($_SESSION['note'])) 
{
 $phone = $_SESSION['note'];
}
include('connect.php');
//---------NOTIFICATION MSG----------------------
      //GET NUMBER OF new msg
      $q = $bdd->prepare("SELECT * FROM chat WHERE to_id = ? AND status = 'new' ORDER BY id_chat DESC");
          $q->execute(array($Id_User));
          $nb_new_msg = $q -> rowCount ();
          if ( $nb_new_msg == 0) {
             $nb_new_msg ='';
          }
      //-------------------------------
      //Calcule du conges disponible
  $query_conges = "SELECT * FROM history WHERE type = 'conges' AND before_change = ? ORDER BY date_time";
  $query_conges = $bdd->prepare($query_conges);
  $query_conges->execute(array($username));
  $in = 0;
  $out = 0;
  $list_in = "";
  $list_out = "";
  while ($dc = $query_conges -> fetch())
  {
    if ($dc['after_change']=='add') {
      $in = ($dc['flag']+0)+$in;
    } else {
      //REMOVE
      $out = ($dc['flag']+0)+$out;
    }

  }
  $query_conges->closeCursor();

      //----------------------------
?>
<!-- Navigation -->
<link rel="stylesheet" type="text/css" href="css/arrondi.css">
<link rel="stylesheet" href="css/mdb.min.css">

<nav class="navbar fixed-top navbar-expand-sm navbar-dark fixed-top" style="font-weight: bold;background-image: linear-gradient(rgba(255, 110, 196, .9),#000,rgba(5, 255, 163, .9)">
  <div class="container">
    <!-- Dropdown Profile -->
    <div class="dropdown">
      <a class="navbar-brand" href="#" id="navbardrop" data-toggle="dropdown">
        <img src="<?php echo  $img_path ?>" height="60" width="60" align="middle" class="dropdown-toggle arrondi" />
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="profile.php" target="_blank" rel="noopener noreferrer">Profil</a>
        <a class="dropdown-item" href="conges_dispo.php" target="_blank" rel="noopener noreferrer" title="Disponible après un an de travail">Congès <span class="w3-badge w3-margin-right w3-pink"><?php echo ($in-$out);?></span></a>
        <a class="dropdown-item" href="contact.php" target="_blank" rel="noopener noreferrer">Contact</a>
        <a class="dropdown-item" href="deconnection.php">Deconnexion</a>
      </div>
      
    </div>
    <span class="text-white">
    <?php echo $fullname;?>
    <br>
    <span id="time"></span>
    <br>
    <span id="date"></span></span>
    <!---------------------------------------------> 
    <!------Menu Button appear on little screen width----->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    <!--------------------------------------------->
        <!--------------------------------------------->
         <?php
         $q->closeCursor();
        
        //GET Number of VENTE DELETED
          $query_nb_r = 'SELECT * FROM mvt_history WHERE type_de_mvt = "vente"';
          $query_nb_r = $bdd->prepare($query_nb_r);
          $query_nb_r->execute(array());
          $corbeille_vente = $query_nb_r -> rowCount ();
          $query_nb_r -> closeCursor();
        //GET Number of STOCK DELETED
          $query_nb_r = 'SELECT * FROM mvt_history WHERE type_de_mvt = "stock"';
          $query_nb_r = $bdd->prepare($query_nb_r);
          $query_nb_r->execute(array());
          $corbeille_stock = $query_nb_r -> rowCount ();
          $query_nb_r -> closeCursor();
        //GET Number of FACTURE DELETED
          $query_nb_r = 'SELECT * FROM mvt_history WHERE type_de_mvt = "facture"';
          $query_nb_r = $bdd->prepare($query_nb_r);
          $query_nb_r->execute(array());
          $corbeille_facture = $query_nb_r -> rowCount ();
          $query_nb_r -> closeCursor();
        ?>

        <!--------------------------------------------->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>FACTURE</b></a>
          <div class="dropdown-menu">
            <a class="dropdown-item bg-warning" href="facture.php"">Nouveau Facture</a>
            <a class="dropdown-item" href="facture_recap.php" target="_blank" rel="noopener noreferrer">Liste des Factures</a>
            <a class="dropdown-item bg-warning" href="mvt_produit_facture.php" target="_blank" rel="noopener noreferrer">RECHERCHE</a>
            <a class="dropdown-item" href="facture_recap_deleted.php" target="_blank" rel="noopener noreferrer">Corbeille <span id="noti" class="w3-badge w3-red"><?php echo $corbeille_facture; ?></span></a>
          </div>
        </li>
        <!--------------------------------------------->

        <!--------------------------------------------->
    </div>
  </div>
</nav>
  <script type="text/javascript">
    window.onload = setInterval(clock,1000);

    function clock()
    {
    var d = new Date();
    
    var date = d.getDate();
    
    var month = d.getMonth();
    var montharr =["Jan","Feb","Mar","April","Mey","Jona","Jol","Aog","Sep","Okt","Nov","Des"];
    month=montharr[month];
    
    var year = d.getFullYear();
    
    var day = d.getDay();
    var dayarr =["Alahady","Alatsinainy","Talata","Alarobia","Alakamisy","Zoma","Sabotsy"];
    day=dayarr[day];
    
    var hour =d.getHours();
      var min = d.getMinutes();
    var sec = d.getSeconds();
  
    document.getElementById("date").innerHTML=day+" "+date+" "+month+" "+year;
    document.getElementById("time").innerHTML=hour+":"+min+":"+sec;
    }
  </script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript">
    $(".vu").click(function(){
        
      //alert("Record removed successfully");
        
            $.ajax({
               
               error: function() {
                  alert('Something is wrong');
               },
               success: function(data) {
                    //$("#"+id).remove();
                    //$(this).load(this);
                    $("#noti").text("");
                    //alert("Record removed successfully");  
               }
            });
    });

</script>