<!DOCTYPE html>
<html>
<head>
    <title>PRIX FOURNISSEUR ET STOCK</title>
    <!---add bootstrap css--->
    <script  src="js/jquery-3.5.1.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css">
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
 <h2 class="text-center text-warning">DERNIER PRIX DANS FACTURE ET STOCK</h2>
</body>
<!--------------------------------------->
<form role="form" action="simple_search_mvt_prix.php" method="POST">
<div class="text-center">
<?php  
if (isset($_COOKIE['key_word'])) 
{
	$point_de_vente = "";
	$S1 ="";
	$S2 ="";
	$S3 ="";
	$S4 ="";
	$S5 ="";
	$S6 ="";
	$S7 ="";
	$S8 ="";
	$S9 ="";
    $S10 ="";
	if (isset($_COOKIE['point_de_vente'])) 
	{$point_de_vente = $_COOKIE['point_de_vente'];}
	if ($point_de_vente == "Tous") {$S1 = "selected";}
	if ($point_de_vente == "Ambaibo_Electronique") {$S2 = "selected";}
	if ($point_de_vente == "Ambaibo_Tole") {$S3 = "selected";}
	if ($point_de_vente == "Ambaibo_loko") {$S4 = "selected";}
	if ($point_de_vente == "Amparafa") {$S5 = "selected";}
	if ($point_de_vente == "Ambato_Tantely") {$S6 = "selected";}
	if ($point_de_vente == "Ambato_veve_photo") {$S7 = "selected";}
	if ($point_de_vente == "Bejofo") {$S8 = "selected";}
	if ($point_de_vente == "Soalazaina") {$S9 = "selected";}
    if ($point_de_vente == "Ambato_Pneu") {$S10 = "selected";}
?>
<br>
	<div>
		<select class="btn btn-secondary" name="point_de_vente">
		<option value="Tous" <?php echo $S1;?>>TOUS FACTURE ET POINT DE VENTE</option>
		<option value="Ambaibo_Electronique" <?php echo $S2;?>>ELECTRONIQUE</option>
		<option value="Ambaibo_Tole" <?php echo $S3;?>>Ambaibo TOLE</option>
	    <option value="Ambaibo_loko" <?php echo $S4;?>>Ambaibo LOKO</option>
	    <option value="Amparafa" <?php echo $S5;?>>AMPARAFA</option>
	    <option value="Ambato_Tantely" <?php echo $S6;?>>AMBATO Tantely</option>
	    <option value="Ambato_veve_photo" <?php echo $S7;?>>VEVE</option>
	    <option value="Bejofo" <?php echo $S8;?>>BEJOFO</option>
	    <option value="Soalazaina" <?php echo $S9;?>>SOALAZAINA</option>
        <option value="Ambato_Pneu" <?php echo $S10;?>>AMBATO TAHINA</option>
		</select>
	</div>
<br>
<input type="search" class="btn light-table-filter text-left" value="<?php echo $_COOKIE['key_word']?>" name="key_word" placeholder="Name/Ref/id_x">
<?php
}else{
?>
<br>
	<div>
		<select class="btn btn-secondary" name="point_de_vente">
		<option value="Tous" selected>Tous les Points de Vente</option>
		<option value="Ambaibo_Electronique">ELECTRONIQUE</option>
		<option value="Ambaibo_Tole">Ambaibo TOLE</option>
	    <option value="Ambaibo_loko">Ambaibo LOKO</option>
	    <option value="Amparafa">AMPARAFA</option>
	    <option value="Ambato_Tantely">AMBATO Tantely</option>
	    <option value="Ambato_veve_photo">VEVE</option>
	    <option value="Bejofo">BEJOFO</option>
	    <option value="Soalazaina">SOALAZAINA</option>
        <option value="Ambato_Pneu">AMBATO TAHINA</option>
		</select>
	</div>
<br>
<input type="text" class="btn light-table-filter text-left" name="key_word" placeholder="Name/Ref/id_x">
<?php
}
?>
<button type="submit" class="btn btn-info">Search</button>
</div>
<br>
<div class="flex-center flex-column">
	<table class="table-borderless table-sm">
	<tr>
		<td class="text-left">
		    <input type="checkbox" name="checkbox" checked>
		    <label>Resultat Limité</label>
    	</td>
    </tr>
    </table>
</div>
</form>
<?php
include('connect.php');
    $key_word = "";
    $status = 'OFF';
   	if (isset($_COOKIE['checkbox_stock_list'])){$status = 'UNDIFENED';}

if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //-----------------SEARCH TIPS--------------------------------------------------
    $key_word0 = str_replace(array('-','/','_'), ' ', $key_word);
    $key_word1 = strtok($key_word0," ");
    $key_word2 = explode(' ',str_replace(array('-','/'), ' ', $key_word0));
    $key_word2 = end($key_word2);
    //------------


   if (isset($_COOKIE['point_de_vente'])) 
	{$point_de_vente = $_COOKIE['point_de_vente'];}
if ($point_de_vente == "Tous") {
	//TOUS LES POINTS DE VENTE
    //Query tous listeproduct
    if (isset($_COOKIE['checkbox'])) 
    {
        //More Result
        //echo "MORE RESULT";
   //$query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE mvt.status != ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ? OR nom_x LIKE ? OR nom_x LIKE ? OR id_x LIKE ? OR reference_x LIKE ?);";
    $query_stock_negatif = "(SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE type_de_mvt = 'facture' OR type_de_mvt = 'stock' GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (nom_x LIKE ?) OR (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?)) UNION ALL (SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, qt as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE type_de_mvt = 'facture' OR type_de_mvt = 'stock') as resultante_table WHERE note LIKE ?)";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array("%".$key_word."%" ,"%".$key_word1."%","%".$key_word2."%" , "%".$key_word."%","%".$key_word."%","%".$key_word."%"));
    } else {
        //echo "LIMITED RESULT";
        //Limited result
        //$query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x,mvt.status, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE mvt.status != ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?);";
        $query_stock_negatif = "(SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire,type_de_mvt FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE type_de_mvt = 'facture' OR type_de_mvt = 'stock' GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?)) UNION ALL (SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, qt as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire,type_de_mvt FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE type_de_mvt = 'facture' OR type_de_mvt = 'stock' GROUP BY note) as resultante_table WHERE note LIKE ?);";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array("%".$key_word."%", "%".$key_word."%","%".$key_word."%","%".$key_word."%"));
    }
    } else {
    //Query for specifique point de vente
    if (isset($_COOKIE['checkbox'])) 
    {
        //echo "MORE RESULT";
        //More Result
   //$query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND mvt.status != ? GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ? OR nom_x LIKE ? OR nom_x LIKE ? OR id_x LIKE ? OR reference_x LIKE ?);";
   $query_stock_negatif = "(SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND (type_de_mvt = 'facture' OR type_de_mvt = 'stock') GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (nom_x LIKE ?) OR (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?)) UNION ALL (SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, qt as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND (type_de_mvt = 'facture' OR type_de_mvt = 'stock')) as resultante_table WHERE note LIKE ?)";
    $q = $bdd->prepare($query_stock_negatif);

    $q->execute(array($point_de_vente,"%".$key_word."%" ,"%".$key_word1."%","%".$key_word2."%", "%".$key_word."%","%".$key_word."%",$point_de_vente,"%".$key_word."%"));
    } else {
        //echo "LIMITED RESULT";
        //Limited result
        //$query_stock_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND mvt.status != ? GROUP BY id_x) as resultante_table WHERE ((nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?));";
        $query_stock_negatif = "(SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire,type_de_mvt FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND (type_de_mvt = 'facture' OR type_de_mvt = 'stock') GROUP BY id_x) as resultante_table WHERE (nom_x LIKE ?) OR (id_x LIKE ?) OR (reference_x LIKE ?)) UNION ALL (SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, qt as sm, reference_x,prix_fournisseur,prix_de_vente,img_path_x,pu_aparafa,pu_ambato_tantely,note,prix_unitaire,type_de_mvt FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND (type_de_mvt = 'facture' OR type_de_mvt = 'stock') GROUP BY note) as resultante_table WHERE note LIKE ?);";
        $q = $bdd->prepare($query_stock_negatif);

        $q->execute(array($point_de_vente,"%".$key_word."%", "%".$key_word."%","%".$key_word."%",$point_de_vente,"%".$key_word."%"));
    }
}
    //Number of Line
    $nb_line=$q->rowCount ();   
?>
<br>
<br>
<!-------------------------------------------------------->
<!---------------search result---------------------------->
<?php
    if ($nb_line == 0) {
        echo "<br><span style='font-weight: bold'>"."[".$key_word."]"." does not exist on the base anymore, Click <a href='stock_epuise.php'>RETOURS</span><br>";
    } else {
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
        <th>#</th>
        <th></th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>PRIX</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
$image_path_x = "img_x/default_x.png";

while ($donnees = $q -> fetch())
{
//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }

    //=====================HANDLE PU LIST=========================
    $pu_list='---------<br>';
    $query_shop = "SELECT DISTINCT(nom_client_fournisseur) as nf FROM mvt WHERE id_x = ? ORDER BY nom_client_fournisseur";
    $query_shop = $bdd->prepare($query_shop);
    $query_shop->execute(array($donnees['id_x']));
    while ($donnees_qsh = $query_shop -> fetch())
    {
        if ($donnees['reference_x'] == 'XXXX') {
        $query_prix = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'facture' AND note LIKE ? ORDER BY Date_du_Journal_mvt DESC LIMIT 4";
        $query_prix = $bdd->prepare($query_prix);
        $query_prix->execute(array($donnees['id_x'],$donnees_qsh['nf'],$donnees['note']));
        $nb_facture = $query_prix -> rowCount ();
        while ($donnees_pu = $query_prix -> fetch())
        {
            $date = DateTime::createFromFormat('Y-m-d', $donnees_pu['Date_du_Journal_mvt']);
            $daty = $date -> format('l d M Y');
            $pu_list = $pu_list."# <span style='font-weight: bold'><span class='text-secondary'>".number_format($donnees_pu['prix_unitaire'],0, "", " ")." : Facture</span></span> | ".$donnees_pu['nom_client_fournisseur']." : <span style='font-weight: bold'>".$daty."</span><br>";
        }
        if ($nb_facture > 0) {
            $pu_list = $pu_list.'---------<br>';
        }
        $query_prix->closeCursor();
        } else {
        //IF not XXXX
            //FOR FACTURE
        $query_prix = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND type_de_mvt = 'facture' ORDER BY Date_du_Journal_mvt DESC LIMIT 4";
        $query_prix = $bdd->prepare($query_prix);
        $query_prix->execute(array($donnees['id_x'],$donnees_qsh['nf']));
        //$pu_list = $pu_list.$donnees_qsh['nf'];
        while ($donnees_pu = $query_prix -> fetch())
        {
            $date = DateTime::createFromFormat('Y-m-d', $donnees_pu['Date_du_Journal_mvt']);
            $daty = $date -> format('l d M Y');
            $pu_list = $pu_list."# <span class='text-secondary'><span style='font-weight: bold'>".number_format($donnees_pu['prix_unitaire'],0, "", " ")." : Facture </span></span> | ".$donnees_pu['nom_client_fournisseur']." : <span style='font-weight: bold'>".$daty."</span><br>";
            
        }
        //STOCK
        $query_prix = "SELECT * FROM mvt WHERE id_x = ? AND nom_client_fournisseur = ? AND (type_de_mvt = 'stock') ORDER BY Date_du_Journal_mvt DESC LIMIT 4";
        $query_prix = $bdd->prepare($query_prix);

        $query_prix->execute(array($donnees['id_x'],$donnees_qsh['nf']));
        while ($donnees_pu = $query_prix -> fetch())
        {
            $date = DateTime::createFromFormat('Y-m-d', $donnees_pu['Date_du_Journal_mvt']);
            $daty = $date -> format('l d M Y'); 
            $pu_list = $pu_list."# <span style='font-weight: bold'><span class='text-danger'>".number_format($donnees_pu['prix_unitaire'],0, "", " ")." - <span class='text-warning'>".number_format($donnees_pu['prix_client'],0, "", " ")."</span></span> : ".$donnees_pu['nom_client_fournisseur']." : <span style='font-weight: bold'>".$daty."</span><br>";  
            
        }
    	$pu_list = $pu_list.'---------<br>';
    	$query_prix->closeCursor();
        }
    	
    }
    $query_shop->closeCursor();
    //Adding Prix Fournisseur in pu_list
    if ($donnees['reference_x'] != 'XXXX') {
        $pu_list = "---------<br># <span class='text-primary'><span style='font-weight: bold'>".number_format(($donnees['prix_fournisseur']+0),0, "", " ")."</span></span> : Prix Fournisseur - Reference | <a href = 'simple_search_prix.php?key_word=".$donnees['reference_x']."' target='_blank' rel='noopener noreferrer'><span class='text-primary'>Modifier?</span></a><br>".$pu_list;

    }

    //=======================END PU LIST=======================

?>
<!------------------------------------------------->
    <tr>
        <td><?php echo $j; ?></td>
        <td class="text-center"><a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>"><img src="<?php echo $image_path_x; ?>" height="50" width="50" background alt="Edit" /></a></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><span style='font-weight: bold'><?php
        if ($donnees['reference_x'] == 'XXXX') {
            echo $donnees['note'].' - ';
          }
        echo $donnees['nom_x']; ?></span></td>
        <td><?php echo $pu_list; ?></td>
    </tr>
  <!-------FORM DE MODIFIER IMAGE-------->
    <!-- modal form MODIFIER IMAGE-->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "img".$donnees['id_x']; ?>" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <h4 class="modal-title">Produit No. <?php echo $donnees['id_x']." : ".$donnees['nom_x']; ?></h4>
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
          </div>
          <div class="modal-body">
          <!-- actual form -->
          <form role="form">
            <div class="form-group text-center">
              <img src="<?php echo $image_path_x;?>" height=100% width=100% align="middle"/>
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
    <br>
    <br>
    <br>
    <br>
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
<script  src="js/confirmation.js"></script>
</html>