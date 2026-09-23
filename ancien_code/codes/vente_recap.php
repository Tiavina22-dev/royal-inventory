<!DOCTYPE html>
<html>
<head>
	<title>Journal de Vente</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

</head>
<body style="background: #343a40">
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
 <?php
//Connect to BD
include('connect.php');
$today = date("Y-m-d");
//Read cookie for validation msg
 $msg_supression ="";
 if (isset($_COOKIE['msg_supression'])) 
{
   $msg_supression=$_COOKIE['msg_supression'];
 }

 //Show Less result
 $page = 0;
 if (isset($_COOKIE['page'])) 
{
   $page = $_COOKIE['page'];
 }
 //get rows number
    $aff = 'TOUTES LES POINTS DE VENTES';
    $point_de_vente = 'TOUTES LES POINTS DE VENTES';
    $debut_date = 'NO';
    $fin_date = 'NO';
 if (isset($_COOKIE['point_de_vente'])) 
    {
        $fin_date = $_COOKIE['fin_date'];
        $debut_date = $_COOKIE['debut_date'];
        $point_de_vente = $_COOKIE['point_de_vente'];
        //FORMAT DATE FOR AFFICHAGE
        $debut = DateTime::createFromFormat('Y-m-d',$debut_date) ;
        $debut = $debut -> format('d M Y');
        $fin = DateTime::createFromFormat('Y-m-d',$fin_date) ;
        $fin = $fin -> format('d M Y');
        //-----------------------------
        $aff = "<span class='text-pink'>".strtoupper($point_de_vente)."</span> | DU <span class='text-orange-1'>".$debut."</span> AU <span class='text-orange-1'>".$fin.'</span>';
        /**********************
        $query_nb_r = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND (Date_du_Journal_mvt BETWEEN ? AND ?) GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC";
        $query_nb_r = $bdd->prepare($query_nb_r);
        $query_nb_r->execute(array($point_de_vente, $debut_date , $fin_date));
        $page_max = $query_nb_r -> rowCount ();
        $query_nb_r -> closeCursor();
        $page_max = floor($page_max/100);
        *********************/
        $page_max = 0;
    } else {
     	$query_nb_r = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC";
    	$query_nb_r = $bdd->prepare($query_nb_r);
    	$query_nb_r->execute(array());
    	$page_max = $query_nb_r -> rowCount ();
    	$query_nb_r -> closeCursor();
    	$page_max = floor($page_max/100);
    }
	if ($page > $page_max) {
		$page = 0;

	}
	if ($page <= 0) {
		$page = 0;
	}

//-------------------------

 if (isset($_COOKIE['all_result'])) 
{
    $xp = 0;
//Query to liste All Journal de vente
$query_journal_vente = $bdd->query("SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC;");
	$result = "<a href='vente_recap.php'>SHOW 100 LATEST RESULTS ONLY?</a>";
	$page = 'all';
}else{
	$xp = $page*100;
    //$x = 100;
    if (isset($_COOKIE['point_de_vente'])) 
    {

    $query_journal_vente = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ? AND (Date_du_Journal_mvt BETWEEN ? AND ?) GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC;";
    $query_journal_vente = $bdd->prepare($query_journal_vente);
    $query_journal_vente->execute(array($point_de_vente, $debut_date , $fin_date));
    $result = "";
    } else {
    $query_journal_vente = "SELECT * FROM (SELECT * FROM mvt  WHERE type_de_mvt = 'vente' GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC LIMIT $xp,100 ;";
    $query_journal_vente = $bdd->prepare($query_journal_vente);
    $query_journal_vente->execute(array());
    $result = 'Page '.($page+1)."/".($page_max+1)." (<a href='activate_all_vente_recap.php'>SHOW ALL RESULTS?</a>)";
    }	
}
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------------------------->
<!---------TO REMOVED : COMMENT THE FOLLOWING CODE-------->
<!--Debut du collapsible--->
<div id="contenu">
	<!--Liste tous les stocks--->
		<div id="allstock">
			<div class="card-body">
	<!-----------------Show All Stock------------------->
    <br>
    <br>
    <br>
    <br>
    <br>
	<h2 class=" text-center animated fadeIn mb-4"><blockquote class="container"><p class="mb-0"><b>LISTE DU JOURNAL DE VENTE<br>DE <?php echo $aff; ?></b>(<a href="vente_recap_legere.php">Forme legère?</a> )</h2>
    <div class="text-center">
        <button type="submit" class="btn btn-warning" data-toggle="modal" data-target="#Demarrer">PERSONNALISER</button>
    </div>
    <h5 class="text-center"><span class="text-danger"><?php echo $msg_supression; ?></span><br><span class="text-info"><b><?php echo $result; ?></b></span></h5>
    <!-------------------------------------->
<!-- model form Demarrer-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Demarrer" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">CRITERE DU LISTE DE VENTE</h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
        </div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="vente_recap_critere.php" method="post">
    <div class="form-group">
        <label>DATE DU DEBUT</label>
        <input class="form-control btn-primary" value = <?php $starting = strtotime("-1 Months");
 $starting = date("Y-m-01", $starting); echo $starting; ?> name="debut_date" type="DATE" oninput="unlock($(this));" id = "debut_date">
    </div>
    <div class="form-group">
        <label>DATE DU FIN</label>
        <input class="form-control btn-success" value= <?php echo $today; ?> name="fin_date" type="DATE">
    </div>
    <!------------------AUTO LISTE SHOP------------------>
    <div class="form-group">
        <label>POINT DE VENTE</label>
        <select class="form-control btn-danger" name="point_de_vente" oninput="unlock($(this));" id = "point_de_vente">
        <option value="" selected disabled hidden>Choisir Point de vente</option>
        <?php
        $query_shop = "SELECT * FROM shop ORDER BY long_name;";
        $query_shop = $bdd->prepare($query_shop);
        $query_shop->execute(array());
        while ($donnees = $query_shop -> fetch())
        {
        ?>
        <option class="btn-warning" value=<?php echo $donnees['short_name']; ?>><?php echo $donnees['long_name']; ?></option>
        <?php
        }
        ?>
        </select>
    </div>
    <!------------------END AUTO LISTE SHOP------------------>
    <div class="form-group">
        <button type="submit" class="btn btn-success" id = "valider" disabled>Valider</button>
    </div>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->
    <div class="text-center">
    <a title="PREVIOUS" style="<?php if ($page <= 0 AND $page = 'all') {echo "pointer-events: none";} ?>" class="center" href="page_moin.php?page=<?php echo $page; ?>&point_de_vente=<?php echo $point_de_vente;?>&debut=<?php echo $debut_date;?>&fin=<?php echo $fin_date;?>"><img src="img/flech_gche.png" height="30" width="30" background alt="Edit" /></a>
    <a title="NEXT" style="<?php if ($page >= $page_max AND $page = 'all') {echo "pointer-events: none";} ?>" class="center" href="page_plus.php?page=<?php echo $page; ?>&point_de_vente=<?php echo $point_de_vente;?>&debut=<?php echo $debut_date;?>&fin=<?php echo $fin_date;?>"><img src="img/flech_dte.png" height="30" width="30" background alt="Edit" /></a>
    </div>
    <br>
    <br>
	 <table id="example" class="table table-striped table-bordered btn-light" style="width:100%">
        <thead>
        <tr style="font-weight: bold;background-image: linear-gradient(rgba(255, 110, 196, .9),rgba(255, 216, 111, .9)">
        <th>Delete</th>
        <th>Shop & Date</th>
        <th class="text-center"></th>
        <th style="text-align: right;">Versmt<br>Depns</th>
        <th style="text-align: right;">Tsy&nbspAmpy<br>Mihoatra</th>
        <th>Note</th>
        <th>Details</th>
        <th>Piece Jointe</th>
        <th>CREATOR<br>MODIFIED</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$sum = 0;
//Query to liste All Waiting command
$rowtextarea = 0;
$path = "";
//$i = "|";
$n = 0;
$Versmt = 0;
$Tombony = 0;
$dps = 0;
$TL_tsyampy = 0;
$TL_mihoatra = 0;

while ($donnees = $query_journal_vente -> fetch())
{
//Query for x for each action
$query_nb_x = 'SELECT id_mvt,numero_commande_stock FROM mvt WHERE id_x = 134655 AND numero_commande_stock = ?';
	$q = $bdd->prepare($query_nb_x);
	$q->execute(array($donnees['no_activite']));
	$nb_x=$q->rowCount ();
	$q->closeCursor();
//-------------------------
//Show vola nalain i Aparafa
	$query_nalain_aparafa = 'SELECT SUM(depense_aparafa) as depense_aparafa FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query_nalain_aparafa);
	$q->execute(array($donnees['no_activite']));
	$nbr=$q->rowCount ();
	$data = $q -> fetch();
	if ($nbr==0) {
		$depense_aparafa = 0;
	} else {
		$depense_aparafa = $data['depense_aparafa']*(-1);
	}
	
	$q->closeCursor();

//-------------------------
//Show depense royal
	$query = 'SELECT SUM(montant) as depense_royal FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query);
	$q->execute(array($donnees['no_activite']));
	$nbr=$q->rowCount ();
	$data = $q -> fetch();
	if ($nbr==0) {
		$depense_royal = 0;
	} else {
		$depense_royal = $data['depense_royal']*(-1);
	}
	
	$q->closeCursor();
//-------------------------
//List Motif depense royal
	$query = 'SELECT * FROM depense WHERE activity_no = ?';
	$q = $bdd->prepare($query);
	$q->execute(array($donnees['no_activite']));
	$motif = '';
		while ($data = $q -> fetch()) {
		if ($data['montant']!=0) {$motif =$motif.'# '.$data['montant'].' : '.$data['motif']."\n";}
		
		}
	
	$q->closeCursor();
//-------------------------
$sum=$donnees['Montant']+$sum;
$rowtextarea = ($donnees['nb_ligne']*2)+13;
//echo $rowtextarea;
$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GESTION_STOCK\\")."\\";
$path = str_replace("\\", "/", $path);

//echo $path;
$local = glob("" . $path . "{*.jpg,*.gif,*.jpeg,*.png}", GLOB_BRACE);

$point_de_vente = $donnees['nom_client_fournisseur']; 
//echo $i;
//Check history change
        $query_history = "SELECT * FROM history WHERE  type = 'Mvt_Vente' AND after_change = ?";
        $query_history = $bdd->prepare($query_history);
        $query_history -> execute(array($donnees['numero_commande_stock']));
        //Number of Line
        $nb_history = $query_history->rowCount ();
        $query_history -> closeCursor();
        $noti = '';
        if ($nb_history > 0) {
            $noti = " <span title = 'CHANGE EXIST' class='w3-badge w3-red'>!</span> ";
        }
$n = $n + 1;
$Versmt = $Versmt + $donnees['Montant']+0;
$Tombony = $Tombony + $donnees['difference_aparafa']+0;
?>
<!------------------------------------------------->
        <tr style="background: #757575">
        <td><a style="<?php if ($donnees['status'] == 'OFF') {echo "pointer-events: none";} ?>" class="text-center" href="supprimer_journal.php?numero_commande=<?php echo $donnees['no_activite']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="40" width="40" background alt="Edit" /></a></td>
        <td>
            <span class="w3-badge w3-gray"><?php echo (($xp+$n)); ?></span> <?php echo $donnees['nom_client_fournisseur']; ?><br>
            <a href="vente_recap_details.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $donnees['no_activite'];?>" target="_blank" rel="noopener noreferrer"><b><span class="text-orange-1"><?php echo $donnees['description_date'].$noti; ?></span></b></a>
        </td>
        <td class="text-left">
            <span class="text-success">V:<?php echo $donnees['nb_ligne']; ?></span><br>
            <span class="text-info">X:<?php echo $nb_x; ?></span> 
        </td>
        <?php
        $dr = $depense_royal+0;
        if ($dr == 0) {
       	?>
       	<td style="text-align: right;"><b><?php echo number_format($donnees['Montant'],0, "", " ");?><br><span class="text-danger"><?php echo number_format($dr,0, "", " ");?></span></b></td>
       	<?php
        } else {
        ?>
        <td style="text-align: right;"><b><?php echo number_format($donnees['Montant'],0, "", " ");?><br><a href="" data-toggle="modal" data-target="#<?php echo "mo".$donnees['id']; ?>"><span class="text-danger"><?php echo number_format($dr,0, "", " ");?></span></a></b></td>
        <?php
        }
        ?>
        <td style="text-align: right;"><b>
            <?php 
            if ($donnees['resolution'] == 0) {
             ?>
             <span class="text-warning"><?php echo number_format($donnees['resolution'],0, "", " ");?></span>
            <?php
            } else {
            	$TL_tsyampy = abs($donnees['resolution']) + $TL_tsyampy;
            ?>
            <span class="text-warning" data-toggle="modal" data-target="#<?php echo "tsyampy".$donnees['id']; ?>"><?php echo number_format(abs($donnees['resolution']),0, "", " ");?></span>
            <?php
            }
            ?>
            
            <br>
                        <?php 
            if ($donnees['mihoatra'] == 0) {
             ?>
             <span class="text-success"><?php echo number_format(($donnees['mihoatra']+0),0, "", " ");?></span>
            <?php
            } else {
            	$TL_mihoatra = $TL_mihoatra + $donnees['mihoatra'];
            ?>
            <span class="text-success" data-toggle="modal" data-target="#<?php echo "mihoatra".$donnees['id']; ?>"><?php echo number_format(($donnees['mihoatra']+0),0, "", " ");?></span>
            <?php
            }
            ?>
            

        </b></td>
        <td><?php echo $donnees['note_general']; ?></td>
        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo "no".$donnees['id']; ?>">Details</button></td>
        <td><?php         
        //print each file name
        echo "<ul>";

        foreach($local as $item)
        {
        echo '<li> . <a href="zoom.php?path='.$item.'">'.$item.'</a></li>';
        }

        echo "</ul>";
        ?>
        </td>
        <td><?php echo $donnees['user_mvt']; ?><br><?php echo $donnees['responsable']; ?></td>
        </tr>
<?php 
            if ($donnees['resolution'] != 0) {
             ?>
<!-------FORM DE REMOVE TSY AMPY-------->
        <!-- modal form MODIFIER-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "tsyampy".$donnees['id']; ?>" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title text-center text-danger"><b>FANESORANA NY VOLA TSY AMPY <br> t@ <?php echo $donnees['description_date']." <br> ".$donnees['nom_client_fournisseur']; ?></b></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form" action="delete_tsy_ampy.php" method="post">
                        <div class="form-group">
                        <label><b>FANAMARIHANA TALOHA </b><br> <?php echo $donnees['note_general']; ?></label>
                        </div>
                        <div class="form-group">
                        <label><b>FANAMARIHANA FANAMPINY (ASIO DATY)</b></label>
                        <input class="form-control btn btn-light" name="additional_note" value="<?php echo $donnees['resolution']; ?> voaloa tamin'ny " type="text">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $donnees['id']; ?>">
                        <input type="hidden" name="current_note" value="<?php echo $donnees['note_general']; ?>">
                        </div>
                        <div class="form-group text-center">
                        <button type="submit" class="btn btn-danger">ESORINA ?</button>
                        </div>
                    </form>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
            <?php
            }
            if ($donnees['mihoatra'] != 0) {
             ?>
<!-------FORM DE REMOVE MIHOATRA-------->
        <!-- modal form MODIFIER-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "mihoatra".$donnees['id']; ?>" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title text-center text-info"><b>FANESORANA NY VOLA MIHOATRA <br> t@ <?php echo $donnees['description_date']." <br> ".$donnees['nom_client_fournisseur']; ?></b></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form" action="delete_mihoatra.php" method="post">
                        <div class="form-group">
                        <label><b>FANAMARIHANA TALOHA </b><br> <?php echo $donnees['note_general']; ?></label>
                        </div>
                        <div class="form-group">
                        <label><b>FANAMARIHANA FANAMPINY (ASIO DATY)</b></label>
                        <input class="form-control btn btn-light" name="additional_note" value="<?php echo $donnees['mihoatra']; ?> nalainy tamin'ny " type="text">
                        </div>
                        <input type="hidden" name="id" value="<?php echo $donnees['id']; ?>">
                        <input type="hidden" name="current_note" value="<?php echo $donnees['note_general']; ?>">
                        </div>
                        <div class="form-group text-center">
                        <button type="submit" class="btn btn-warning">ESORINA ?</button>
                        </div>
                    </form>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
            <?php
            }
            ?>
<!-------FORM DE DETAILS-------->
        <!-- modal form DETAILS-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "no".$donnees['id']; ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">DETAILS DU <?php echo $donnees['description_date']." : ".$donnees['nom_client_fournisseur']; ?></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <div>
                        <textarea class="form-control" rows="<?php echo $rowtextarea; ?>" id="comment"><?php echo $donnees['history']; ?></textarea>
                    </div>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
<!-------FORM DE MOTIF DEPENSE-------->
        <!-- modal form MOTIF-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "mo".$donnees['id']; ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">DEPENSE DANS <?php echo $donnees['description_date']." : ".$donnees['nom_client_fournisseur']; ?></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <div>
                        <textarea class="form-control" rows="6" id="comment"><?php echo $motif; ?></textarea>
                    </div>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->

<!-------------------------------------->
<?php
}
?>
        </tbody>
                <thead>
        <tr style="background: #607d8b">
        <th></th>
        <th></th>
        <th class="text-center"></th>
        <th style="text-align: right;"><?php echo number_format($Versmt,0, "", " "); ?></th>
        <th class="text-right"><span class="text-warning"><?php echo number_format(abs($TL_tsyampy),0, "", " ");?></span><br><span class="text-success"><?php echo number_format($TL_mihoatra,0, "", " ");?></span></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        </tr>
        </thead>
    </table>
    <br>
    <br>
    <br>
    <br>
			</div>			
		</div>
	<!-------------------------->



<!--FIN du collapsible----->
<!--------------END OF THE CODE TO REMOVED-------->
<!--------------LISTE DU STOCK EN COURS----------->
</div>

<!-------------------------------------->
<!--Javascript--->
<script>
function unlock(e){

    var debut =  e.val();
    var point_de_vente = document.getElementById('point_de_vente').value;
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    //alert(point_de_vente.length);
    if (debut.length != 0 &&  point_de_vente.length != 0) {
        
        //alert("OK");
        //$("#"+i+"L").removeAttr("style");
        $("#valider").removeAttr('disabled');
    }
}
</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>