<!DOCTYPE html>
<html>
<head>
    <title>Baisse Prix Ambato</title>
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
 <br>
 <br>
</body>
<!--------------------------------------->

<?php
include('connect.php');
$perte_total = 0;
  $key_word = "QPECE20ROS";
if ($key_word <> "") 
{
//Query to liste product
   $query_x = "SELECT produit.id_x as id_x,produit.prix_de_vente as prix_officiel,reference_x,nom_x,nom_client_fournisseur FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = 'Ambato_Tantely' GROUP BY id_x ORDER BY nom_x;";
    $q = $bdd->prepare($query_x);

    $q->execute(array());
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<h4 class="text-center"><b>PRIX VARIER AMBATO</b></h4>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><b>"."[".$key_word."]"." does not exist on the base, Click <a href='stock_epuise.php'>RETOURS</b><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');
?>
<!------------------------------------------------>
<div>
<table id="example" class="table table-striped table-bordered dt-responsive" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Produit</th>
            <th>Details</th>
            <th><span title="Prix de Reference">Prix Normal</span>/<span title="Taux de Normatisation">Taux</span></th>
            <th>Prix Abaisser</th>
            <th>Latsaka</th>
            <th>QT</th>
            <th>PERTE</th>
        </tr>
    </thead>
<!------------------------------------------------>
<?php
$j = 1;

while ($donnees = $q -> fetch())
{ 
//QUERY TO SHOW ALL MVT
   $query_stock = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL AND type_de_mvt='vente'";
    $qs = $bdd->prepare($query_stock);

    $qs->execute(array($donnees['id_x']));
//GET ALL PRIX
    //$pu_list='';
    //Prix OFFICIEL Unused
    $prix_officiel=$donnees['prix_officiel'];
    $max = 0;
    //$max_complet ='';
    $min = 0;
    $min_complet ='';
    $details ="";
while ($donnees1 = $qs -> fetch()) //WHILE 2
{

//----------------MAX--------------------
	if ($max < $donnees1['prix_unitaire']) {
	$max = $donnees1['prix_unitaire'];
	//$max_complet = $max." : ".$donnees1['nom_client_fournisseur'];
	}
//--------------MIN-------------------------
	if ($min == 0) {
		$min = $donnees1['prix_unitaire'];
	//$min_complet = $min." : ".$donnees1['nom_client_fournisseur'];
	} 	
	if ($min > $donnees1['prix_unitaire']) {
	$min = $donnees1['prix_unitaire'];
	//$min_complet = $min." : ".$donnees1['nom_client_fournisseur'];
	}
	if ($min >= $donnees['prix_officiel'] AND $donnees['prix_officiel'] != 0) {
    //$min_complet = $donnees['prix_officiel']." : Officiel";
    }
    //GET ALL PRIX
    $fournisseur = $donnees1['nom_client_fournisseur'];
    $details = "# ".$donnees1['prix_unitaire']." : ".$fournisseur." ".$donnees1['description_date']."<br>".$details;
	
} //END WHILE 2
//echo "max $max <br>";

//QUERY TO GET PRIX DE REFERENCE
   $query = "SELECT qt_prix,prix_unitaire FROM (SELECT COUNT(prix_unitaire) as qt_prix,prix_unitaire FROM mvt WHERE id_x = ? AND type_de_mvt='vente' GROUP BY prix_unitaire) t WHERE qt_prix=(SELECT MAX(qt_prix) FROM (SELECT COUNT(prix_unitaire) as qt_prix,prix_unitaire FROM mvt WHERE id_x = ? AND type_de_mvt='vente' GROUP BY prix_unitaire) t);";
    $qs = $bdd->prepare($query);

    $qs->execute(array($donnees['id_x'],$donnees['id_x']));
    $nb_row = $qs->rowCount ();
    //initialisation
    $Prix_de_reference = 0;
    $qt_prix = 0;
    $donnees1 = $qs -> fetch();
    
    if ($nb_row > 0) { //start if row_number
    $Prix_de_reference = $donnees1['prix_unitaire'];
    $qt_prix = $donnees1['qt_prix'];
    $qs->closeCursor();
    //if prix miverimberina ambany loatra
    if ($qt_prix < 2) {
    $Prix_de_reference = $max;
    }
    if ($Prix_de_reference > 0) {//start prix de reference valider
    //---------------------------------------------------
    //Compare prix tantely avec prix de reference
        //QUERY TO GET ALL AMBATO PRIX DE VENTE
   $query = "SELECT * FROM mvt WHERE id_x = ? AND prix_unitaire != 0 AND prix_unitaire IS NOT NULL AND nom_client_fournisseur='Ambato_Tantely' AND type_de_mvt = 'vente'";
    $qs = $bdd->prepare($query);

    $qs->execute(array($donnees['id_x']));
    $Perte_Royal = 0;
    $qt_x = "";
    $ecart = "";
    $prix_abaisser = "";
    while ($donnees1 = $qs -> fetch()) //WHILE 4
    {
        
        $prix_tantely = $donnees1['prix_unitaire'];
        //echo "Prix Tantely $prix_tantely <br>";
        if ($Prix_de_reference > $prix_tantely) {
        //echo "Prix Tantely mahafeno fepetra $prix_tantely <br>";
        //echo "Prix_de_reference $Prix_de_reference <br>";
        //echo "QT".$donnees1['qt']."<br>";
        $Perte_Royal = (($Prix_de_reference - $prix_tantely)*$donnees1['qt']*(-1))+$Perte_Royal;
        $prix_abaisser = $prix_tantely."<br>".$prix_abaisser;
        $qt_x = $donnees1['qt']*(-1)."<br>".$qt_x;
        $ecart = ($Prix_de_reference - $prix_tantely)."<br>".$ecart;
        //echo "$Perte_Royal <br>";
        }
        

    
} //END WHILE 4
$qs->closeCursor();

    //--------------------------------------------------
//echo " $Perte_Royal ".$donnees['nom_x']. "<br>";
$perte_total = $Perte_Royal + $perte_total;
if ($Perte_Royal != 0) {
?>
<!------------------------------------------------>
<tr>
        <td><a href="#"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $details; ?></td>
        <td><?php echo $Prix_de_reference." (Taux : ".$qt_prix.")"; ?></td>
        <td><?php echo $prix_abaisser; ?></td>
        <td><?php echo $ecart; ?></td>
        <td><?php echo $qt_x; ?></td>
        <td><?php echo $Perte_Royal; ?></td>
</tr>
<!------------------------------------------------>
<?php
    $j = $j+1;
    }// END IF $Perte_Royal != 0
 }//end prix de reference valider
  }//end if row_number

$qs->closeCursor();
}
    //echo "##################################";
    //echo " perte_total $perte_total <br>";
?>
        <tr>
                <td>TOTAL</td>
                <td>######</td>
                <td>######</td>
                <td>######</td>
                <td>######</td>
                <td>######</td>
                <td>######</td>
                <td><?php echo $perte_total; ?></td>
        </tr>
    </table>
    </div>
    <br>
    <br>
    <br>
    <br>
 <?php
    }
 $q->closeCursor();

 }
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