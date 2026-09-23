<!DOCTYPE html>
<html>
<head>
	<title>VENTE DELETED</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">

</head>
<body style="background: #EAE8E8">
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
 <?php
//Connect to BD
include('connect.php');
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
 	$query_nb_r = 'SELECT * FROM recap_vente INNER JOIN mvt_history ON recap_vente.no_activite = mvt_history.numero_commande_stock WHERE type_de_mvt = "vente" GROUP BY mvt_history.numero_commande_stock ORDER BY no_activite DESC';
	$query_nb_r = $bdd->prepare($query_nb_r);
	$query_nb_r->execute(array());
	$page_max = $query_nb_r -> rowCount ();
	$query_nb_r -> closeCursor();
	$page_max = floor($page_max/20);
	if ($page >= $page_max) {
		$page = 0;

	}
	if ($page <= 0) {
		$page = 0;
	}

//-------------------------

 if (isset($_COOKIE['all_result'])) 
{
//Query to liste All Journal de vente
$query_journal_vente = $bdd->query('SELECT *,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt_history ON recap_vente.no_activite = mvt_history.numero_commande_stock WHERE type_de_mvt = "vente" GROUP BY mvt_history.numero_commande_stock ORDER BY no_activite DESC;');
	$result = "<a href='vente_recap.php'>SHOW 20 LATEST RESULTS ONLY?</a>";
	$page = 'all';
}else{
	$x = $page*20;
	//$x = 20;
	$query_journal_vente = "SELECT *,recap_vente.no_activite as no_activite FROM recap_vente INNER JOIN mvt_history ON recap_vente.no_activite = mvt_history.numero_commande_stock WHERE type_de_mvt = 'vente' GROUP BY mvt_history.numero_commande_stock ORDER BY no_activite DESC LIMIT $x,20 ;";
	$query_journal_vente = $bdd->prepare($query_journal_vente);
    $query_journal_vente->execute(array());
	$result = 'Page '.($page+1)."/".($page_max+1)." (<a href='activate_all_vente_recap.php'>SHOW ALL RESULTS?</a>)";	
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
    <br>
    <br>
	<h2 class="text-center text-warning">VENTE SUPPRIMEE</h2>
    <div class="text-center">
    <a style="<?php if ($page <= 0 AND $page = 'all') {echo "pointer-events: none";} ?>" class="center" href="page_moin.php?page=<?php echo $page; ?>"><img src="img/flech_gche.png" height="30" width="30" background alt="Edit" /></a>
    <a style="<?php if ($page >= $page_max AND $page = 'all') {echo "pointer-events: none";} ?>" class="center" href="page_plus.php?page=<?php echo $page; ?>"><img src="img/flech_dte.png" height="30" width="30" background alt="Edit" /></a>
    </div>
	 <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
        <th>Shop & Date</th>
        <th class="text-center"></th>
        <th style="text-align: right;">Versmt<br>Depns</th>
        <th style="text-align: right;">A/fa<br>Nalainy<br>Tombony</th>
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
while ($donnees = $query_journal_vente -> fetch())
{
//Query for x for each action
$query_nb_x = 'SELECT id_mvt,numero_commande_stock FROM mvt_history WHERE id_x = 134655 AND numero_commande_stock = ?';
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
$path = "p".ltrim($donnees['directory'],"C:\wamp64\www\GSTEST\\")."\\";
$path = str_replace("\\", "/", $path);

//echo $path;
$local = glob("" . $path . "{*.jpg,*.gif,*.png}", GLOB_BRACE);

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

?>
<!------------------------------------------------->
        <tr>
        <td>
            <?php echo $donnees['nom_client_fournisseur']; ?><br>
            <a href="vente_recap_details_deleted.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $donnees['no_activite'];?>" target="_blank" rel="noopener noreferrer"><b><span class="text-primary"><?php echo $donnees['description_date'].$noti; ?></span></b></a>
        </td>
        <td class="text-left">
            <span class="text-success">V:<?php echo $donnees['nb_ligne']; ?></span><br>
            <span class="text-danger">X:<?php echo $nb_x; ?></span> 
        </td>
        <?php
        $dr = $depense_royal+0;
        if ($dr == 0) {
       	?>
       	<td style="text-align: right;"><b><?php echo $donnees['Montant'];?><br><span class="text-danger"><?php echo $dr;?></span></b></td>
       	<?php
        } else {
        ?>
        <td style="text-align: right;"><b><?php echo $donnees['Montant'];?><br><a href="" data-toggle="modal" data-target="#<?php echo "mo".$donnees['id']; ?>"><span class="text-danger"><?php echo $dr;?></span></a></b></td>
        <?php
        }
        ?>
        <td style="text-align: right;"><b><span class="text-danger"><?php echo abs($depense_aparafa);?></span><br><span class="text-info"><?php echo $donnees['difference_aparafa'];?></span></b></td>
        <td style="text-align: right;"><b>
            <?php 
            if ($donnees['resolution'] == 0) {
             ?>
             <span class="text-danger"><?php echo $donnees['resolution'];?></span>
            <?php
            } else {
            ?>
            <span class="text-danger" data-toggle="modal" data-target="#<?php echo "tsyampy".$donnees['id']; ?>"><?php echo $donnees['resolution'];?></span>
            <?php
            }
            ?>
            
            <br>
                        <?php 
            if ($donnees['mihoatra'] == 0) {
             ?>
             <span class="text-info"><?php echo $donnees['mihoatra']+0;?></span>
            <?php
            } else {
            ?>
            <span class="text-info" data-toggle="modal" data-target="#<?php echo "mihoatra".$donnees['id']; ?>"><?php echo $donnees['mihoatra']+0;?></span>
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
        echo '<li><a href="zoom.php?path='.$item.'">'.$item.'</a></li>';
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