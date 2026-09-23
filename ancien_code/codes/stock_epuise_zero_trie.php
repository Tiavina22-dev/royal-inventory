<!DOCTYPE html>
<html>
<head>
    <title>Stock Negatif</title>
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
    $nom_client_fournisseur = "Ambato_Tantely";
    if (isset($_GET['nom_client_fournisseur'])) {
    $nom_client_fournisseur = $_GET['nom_client_fournisseur'];
    }
//Query to liste negatif product
   //$query_stock_negatif = 'SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE sm = 0;';
   $query_stock_negatif ="SELECT z.nom_client_fournisseur ,z.id_x,z.nom_x,smzero as qt_actu,v.sm as qt_vendu,z.reference_x FROM (SELECT id_x,smzero,nom_x,nom_client_fournisseur,reference_x FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as smzero, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? GROUP BY id_x) as resultante_table WHERE smzero = 0) z LEFT JOIN (SELECT produit.id_x as id_x,(SUM(qt)*(-1)) as sm, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND type_de_mvt='vente' AND status !='OFF' GROUP BY id_x) v ON z.id_x = v.id_x ORDER BY sm DESC;";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($nom_client_fournisseur,$nom_client_fournisseur));
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<h4 class="text-center"><?php echo $nom_client_fournisseur;?> || STOCK ZERO (<?php echo $nb_line;?>)</h4>
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
        <th>QT</th>
        <th>Vente</th>
        <th>Details</th>
        <th></th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;

while ($donnees = $q -> fetch())
{ 
//QUERY TO SHOW STOCK
   $query_stock = "SELECT * FROM mvt WHERE nom_client_fournisseur = ? AND id_x = ? AND type_de_mvt = 'stock' AND status !='OFF'";
    $qs = $bdd->prepare($query_stock);

    $qs->execute(array($donnees['nom_client_fournisseur'],$donnees['id_x']));
//QUERY TO SHOW VENTE
   $query_stock_vente = "SELECT * FROM mvt WHERE nom_client_fournisseur = ? AND id_x = ? AND type_de_mvt = 'vente' AND status !='OFF'";
    $qv = $bdd->prepare($query_stock_vente);

    $qv->execute(array($donnees['nom_client_fournisseur'],$donnees['id_x']));
    //echo $donnees['nom_client_fournisseur'].$donnees['id_x'];
?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['qt_actu']; ?></td>
        <td><?php echo $donnees['qt_vendu']+0; ?></td>
        <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("no".$j); ?>"><img src="img/liste.png" height="30" width="30" background alt="Edit" /></a></td>
        <td class="text-center"><a class="center" href="#" data-toggle="modal" data-target="#<?php echo ("b".$j); ?>"><img src="img/reset.png" height="30" width="30" background alt="Edit" /></a></td>
    </tr>
        <!-- modal form DETAILS-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("no".$j); ?>" class="modal fade">
            <div class="modal-dialog">
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
                            ?>
                          <li class="list-group-item"><?php echo $donnees1['description_date']; ?><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo $donnees1['qt']; ?></span></li>
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
<!-- modal form BALANCE-->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo ("b".$j); ?>" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h4 class="modal-title">Balance du stock de <?php echo $donnees['nom_x']; ?></h4>
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                    </div>
                    <div class="modal-body">
                    <!-- actual form -->
                    <form role="form" action="balance_zero.php" method="post">
                        <div class="form-group">
                        <label>Quantite Actuel de ce produit</label>
                        <input class="form-control text-center" name="qt_actu" value="0" type="number" step="any">
                        </div>
                        <input type="hidden" name="nom_client_fournisseur" value="<?php echo $nom_client_fournisseur; ?>">
                        <input type="hidden" name="sm" value="<?php echo $donnees['qt_actu']; ?>">
                        <input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
                        <a class="center"  /></a>
                        <button type="submit" class="btn btn-success" disabled>Balancer</button>
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
</html>