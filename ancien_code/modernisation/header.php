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
<style> 
<style>
.modal{
  display:none;
  position:fixed;
  z-index:9999;
  padding-top:60px;
  left:0;
  top:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.8);
}

.modal-content{
  margin:auto;
  display:block;
  max-width:90%;
  max-height:90%;
  animation:zoom 0.3s;
}

@keyframes zoom{
  from{transform:scale(0.5)}
  to{transform:scale(1)}
}

.close{
  position:absolute;
  top:15px;
  right:35px;
  color:white;
  font-size:40px;
  font-weight:bold;
  cursor:pointer;
}
</style>

</style>
<!-- Navigation -->
<link rel="stylesheet" type="text/css" href="css/arrondi.css">
<link rel="stylesheet" href="css/mdb.min.css">
<link rel="stylesheet" href="css/modern_ui.css">

<nav class="navbar fixed-top navbar-expand-sm navbar-dark app-topbar">
  <div class="container">
    <!-- Dropdown Profile -->
    <div class="dropdown">
      <a class="navbar-brand" href="#" id="navbardrop" data-toggle="dropdown">
        <img src="<?php echo  $img_path ?>" height="60" width="60" align="middle" class="dropdown-toggle arrondi" />
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="profile.php" target="_blank" rel="noopener noreferrer">Profil</a>
        <a class="dropdown-item" href="contact.php" target="_blank" rel="noopener noreferrer">Contact</a>
        <a class="dropdown-item" href="deconnection.php">Deconnexion</a>
      </div>
    
    </div>
    <a class="app-brand" href="home_char.php">
      <strong>Royal Inventory</strong>
      <span>Stock & Cash Management</span>
    </a>
    <span class="app-user-meta">
      <strong><?php echo $fullname;?></strong>
      <span><span id="time"></span> | <span id="date"></span></span>
    </span>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
    <!--------------------------------------------->
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="home_char.php"><img src="img/home.png" height="40" width="40" /></a>
        </li>
        <?php
 
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
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>VENTE</b></a>
          <div class="dropdown-menu">
            <a class="dropdown-item bg-success" href="commande.php"><span style="font-weight: bold">Nouveau Journal</span></a>
            <a class="dropdown-item bg-warning" href="retours_vente.php"><span style="font-weight: bold">Abandoner (Retourner)</span></a>
            <a class="dropdown-item bg-info" href="vente_recap.php"><span style="font-weight: bold">Liste de Journal de Vente</span></a>
            <a class="dropdown-item bg-secondary" href="vente_recap_legere.php"><span style="font-weight: bold">Liste de Journal de Vente en forme legère</span></a>
            <a class="dropdown-item" href="vente_recap_deleted.php" target="_blank" rel="noopener noreferrer">Corbeille<span id="noti" class="w3-badge w3-red"> <?php echo $corbeille_vente; ?></span></a>
          </div>
        </li>

          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>STOCK</b></a>
          <div class="dropdown-menu">
            <a class="dropdown-item bg-info" href="stock.php"><span style="font-weight: bold">Ajouter</span></a>
            <a class="dropdown-item bg-warning" href="stock_reduit.php"><span style="font-weight: bold">Retirer</span></a>
            <a class="dropdown-item bg-success" href="stock_recap.php"><span style="font-weight: bold">Liste d' inventaire/stock</span></a>
            <a class="dropdown-item bg-primary" href="stock_general.php"><span style="font-weight: bold">Ajouter/Modifier Produit</span></a>
            <a class="dropdown-item bg-warning" href="controle_x.php"><span style="font-weight: bold">Inventaire</span></a>
            <a class="dropdown-item" href="latest_controle.php">Afficher les verification</a>
            <a class="dropdown-item bg-success" href="controle_ambato_new.php"><span style="font-weight: bold">Controle des Produits Nouveaux/Absents</span></a>
            <a class="dropdown-item bg-warning" href="stock_recap_deleted.php" target="_blank" rel="noopener noreferrer">Corbeille<span id="noti" class="w3-badge w3-red"> <?php echo $corbeille_stock; ?></span></a>
            <a class="dropdown-item bg-warning" href="cdispo.php"><span style="font-weight: bold">Reference X</span></a>
          </div>
        </li>

        
        <!--------------------------------------------->
        <!--------------------------------------------->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>MVT</b></a>
          <div class="dropdown-menu">
            <a class="dropdown-item bg-info" href="mvt_produit.php"><span style="font-weight: bold">Stock et Vente</span></a>
            <a class="dropdown-item bg-warning" href="mvt_produit_all.php"><span style="font-weight: bold">MVT | Forme Détailé</span></a>
          </div>
        </li>
        <!--------------------------------------------->
        <!--------------------------------------------->
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>GERER</b></a>
          <div class="dropdown-menu">
            <a class="dropdown-item bg-info" href="prix.php"><span style="font-weight: bold">Gerer Prix</span></a>

            <a class="dropdown-item bg-success" href="depense.php"><span style="font-weight: bold">Depense</span></a>
 <!--------------<a class="dropdown-item bg-warning" href="mvt_comparaison_prix.php">Comparer Prix</a>----------------->
            <a class="dropdown-item" href="#">Historique</a>
            <a class="dropdown-item bg-warning" href="add_shop.php"><span style="font-weight: bold">Add/Edit Shop</span></a>
            <a class="dropdown-item" href="conges.php" target="_blank" rel="noopener noreferrer">Time away</a>
            <a class="dropdown-item" href="gerer_user.php">Gerer Utilisateur</a>
            <a class="dropdown-item bg-success" href="reference_generator_simple.php"><span style="font-weight: bold">Generer Reference</span></a>
            <a class="dropdown-item" href="report_2.php" target="_blank" rel="noopener noreferrer">Diagramme Mensuel</a>
            <a class="dropdown-item bg-warning" href="report_1.php" target="_blank" rel="noopener noreferrer">Rapport Journalier</a>
          </div>
        </li>
        <!--------------------------------------------->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbardrop" data-toggle="dropdown"><b>OUTILS</b></a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="calculator_pourcent.php" target="_blank" rel="noopener noreferrer">Calcule de Pourcentage(%)</a>
            <a class="dropdown-item" href="calendar.php" target="_blank" rel="noopener noreferrer">Calendar</a>
            <a class="dropdown-item bg-warning" href="backup_db.php" target="_blank" rel="noopener noreferrer"><span style="font-weight: bold">Backup database</span></a>
          </div>
        </li>
<!-------------------------------------->
        <li class="nav-item">
          <a class="nav-link" href="about.php"  target="_blank" rel="noopener noreferrer"><img src="img/help1.png" height="30" width="30" background alt="Edit" />
                <span class="sr-only">(current)</span>
              </a>
        </li>
        <li class="nav-item">
  <div class="dropdown">
    <img src="img/mahav2.png"
         height="60"
         width="220"
         style="cursor:pointer"
         onclick="openModal()"/>
  </div>
</li>

        <!--------------------------------------------->
      </ul>
    </div>
  </div>
</nav>
<!-- Modal -->
<div id="imgModal" class="modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="imgBig">
</div>

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
<script>
function openModal(){
  document.getElementById('imgModal').style.display = 'block';
  document.getElementById('imgBig').src = 'img/mahav.png';
}

function closeModal(){
  document.getElementById('imgModal').style.display = 'none';
}
</script>
