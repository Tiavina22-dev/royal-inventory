<!DOCTYPE html>
<html>
<head>
    <title>Balanced Negatif</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/w3.css">
    <!---add other css--->
</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
 <br>
 <br>
 <br>
 <br>
</body>
<!--------------------------------------->
<?php
include('connect.php');
    $msg = "";
    if (isset($_COOKIE['msg'])) 
    {
    $msg=$_COOKIE['msg'];
     }
    $nom_client_fournisseur = "";
    if (isset($_GET['nom_client_fournisseur'])) {
    $nom_client_fournisseur = $_GET['nom_client_fournisseur'];
    }
    //GET nom_client_fournisseur from coockie
    if (isset($_COOKIE['nom_client_fournisseur'])) 
    {
    $nom_client_fournisseur=$_COOKIE['nom_client_fournisseur'];
     }
//Query to liste balance_negative
   $query_stock_negatif = "SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND (description_date LIKE '%balance_negative%') GROUP BY id_x;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($nom_client_fournisseur));
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<h4 class="text-center"><?php echo $nom_client_fournisseur;?> || STOCK BALANCED NEGATIF (<?php echo $nb_line;?>)</h4>
<h5 class="text-center text-danger"><?php echo $msg;?></h5>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_epuise.php'>RETOURS</b><br>";
    } else {
    
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>QT Neg</th>
        <th>Details</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;

while ($donnees = $q -> fetch())
{
//QUERY TO SHOW REGULARY STOCK
   $query_stock = "SELECT * FROM mvt WHERE nom_client_fournisseur = ? AND id_x = ? AND type_de_mvt = 'stock'";
    $qs = $bdd->prepare($query_stock);

    $qs->execute(array($donnees['nom_client_fournisseur'],$donnees['id_x']));
//QUERY TO SHOW VENTE
   $query_stock_vente = "SELECT * FROM mvt WHERE nom_client_fournisseur = ? AND id_x = ? AND type_de_mvt = 'vente' ";
    $qv = $bdd->prepare($query_stock_vente);

    $qv->execute(array($donnees['nom_client_fournisseur'],$donnees['id_x']));
    //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['sm']; ?></td>
        <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("no".$j); ?>"><img src="img/liste.png" height="30" width="30" background alt="Edit" /></a></td>
    </tr>
        <!-- modal form DETAILS-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Balance du stock de <?php echo $donnees['nom_x']; ?></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form" action="balance_negatif.php" method="post">
                        <div class="form-group">
                        <label><b>STOCK</b></label>
                        <ul class="list-group">
                        <?php
                        $total_stock = 0;
                        while ($donnees1 = $qs -> fetch())
                        { 
                            $total_stock = $total_stock + $donnees1['qt'];
                            if ($donnees1['description_date']=='balance_negative') {
                        ?>
                        <li class="list-group-item"><?php echo $donnees1['description_date'].' ('.$donnees1['date_time'].')'; ?> | <a href="delete_mvt_stock_neg.php?id_mvt=<?php echo $donnees1['id_mvt']; ?>&nom_client_fournisseur=<?php echo $donnees1['nom_client_fournisseur']; ?>" onclick="confirmationDelete('Do you want to DELETE?');return false; post ;">Annuler?</a><span class="w3-badge w3-right w3-margin-right w3-red"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                            } else {
                        ?>
                        <li class="list-group-item"><?php echo $donnees1['description_date']; ?><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
                        <?php
                            }//end else
                            
                            ?>
                        <?php
                        }
                        $qs->closeCursor();
                        ?>
                        <li class="list-group-item"><b>Total</b><span class="w3-badge w3-right w3-margin-right"><?php echo $total_stock; ?></span></li>
                        </ul>
                        </div>
                        <div class="form-group">
                        <label><b>VENTE</b></label>
                        <ul class="list-group">
                        <?php
                        $total_vente = 0;
                        while ($donnees2 = $qv -> fetch())
                        { 
                            $total_vente = $total_vente + $donnees2['qt'];
                            ?>
                          <li class="list-group-item"><?php echo $donnees2['description_date']; ?><span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ABS($donnees2['qt']); ?></span></li>
                        <?php
                        }
                        $qv->closeCursor();
                        ?>
                        <li class="list-group-item"><b>Total</b><span class="w3-badge w3-right w3-margin-right"><?php echo ABS($total_vente); ?></span></li>
                        </ul>
                        </div>
                    </form>
                    <!-- actual form ends -->
                    </div>
                </div>
            </div>
        </div>
<!-------------------------------------->
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
    <div>
    <a href="home_char.php" class="float-right"><button type="submit" class="btn btn-info">RETOURS</button></a>
</div>
    <br>
    <br>
    <br>
    <br>
 <?php
    }
 $q->closeCursor();
 
?>
<!-------------END SIMPLE SEARCH-------------------------->
<!-------------------------------------->
<!-------------------------------------->
<!--Javascript--->
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script  src="js/jquery.dataTables.min.js"></script>
<script  src="js/dataTables.bootstrap4.min.js"></script>
<script  src="js/dataTables.responsive.min.js"></script>
<script  src="js/responsive.bootstrap4.min.js"></script>
<script  src="js/confirmation.js"></script>
</html>