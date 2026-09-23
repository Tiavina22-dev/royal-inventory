<!DOCTYPE html>
<html>
<head>
	<title>Gerer Article</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="css/top20.css">
	<link rel="stylesheet" href="css/filtre.css">
	<link rel="stylesheet" href="css/mode.css">
</head>
<body style="background: #343a40">
	<br>
 <br>
 <br>
 <br>
 <br>
 <br>
<!--------------------------------------->

<h4 class="text-center text-warning">VENTE DETAILS FORME LEGERE</h4>
<h4 class="text-center text-light"><a href="vente_recap.php">Forme detailée</a> | <a href="home_char.php">Home</a></h4>

<?php
//Connect to BD
 include("header.php"); 
 include("footer.php");
 include('connect.php');
?>
<div class="text-center mb-3">
    <select oninput="Check_shop($(this));" id="filter_pdv" class="select-btn" name="point_de_vente">
        <option value="">Tous les Point de vente</option>
        <?php
        $query_shop = "
            SELECT DISTINCT s.short_name, s.long_name FROM shop s
            JOIN mvt m ON m.nom_client_fournisseur = s.short_name
            WHERE m.status != 'OFF'
            ORDER BY s.long_name;
        ";
        $query_shop = $bdd->prepare($query_shop);
        $query_shop->execute();

        while ($donnees = $query_shop->fetch()) {
        ?>
            <option value="<?php echo $donnees['short_name']; ?>">
                <?php echo $donnees['long_name']; ?>
            </option>
        <?php } ?>
    </select>
</div>
<!------------------
	<div class="text-center mb-3">
  <select id="filter_pdv" class="filter-btn">
    <option value="">Tous les Points de vente</option>
    <option value="Ambato_Tantely">Ambato_Tantely</option>
    <option value="Ambaibo_Tole">Ambaibo_Tole</option>
    <option value="Amparafa">Amparafa</option>
    <option value="Bejofo">Bejofo</option>
  </select>
</div>-------------------->

<?php

	//$x = 20;
	$query_stock_inventaire ="SELECT * FROM (SELECT * FROM mvt WHERE type_de_mvt = 'vente' GROUP BY numero_commande_stock) as mvt INNER JOIN recap_vente ON recap_vente.no_activite = mvt.numero_commande_stock ORDER BY Date_du_Journal_mvt DESC";	
$q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array()); ?>

  <div>
  <ul class="list-group">

  	<?php 
 $j = 1;
 $color = "text-danger";
 $point_de_vente = "";
 $target = "";
 $id = 0;
 $n = 0;
 while ($donnees = $q -> fetch())
{
$n = $n + 1;
	$point_de_vente = $donnees['nom_client_fournisseur'];
	$x = chr(35).'/0-9-';
	preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $donnees['date_time'], $res_regex);
        //Prise 
	$target = $donnees['numero_commande_stock'];
		switch ($point_de_vente) {

		     case "Ambato_Tantely":
		    $color = "text-warning";
		    break;
		  case "Ambaibo_Tole":
		    $color = "text-light";
		    break;
		  case "Amparafa":
		    $color = "text-info";
		    break;
		case "Bejofo":
		    $color = "text-success";
		    break;
		  default:
		    $color = "text-success";
		}
?>
<!----------------------->
<div class="table btn-brown text-left <?php echo $color; ?> container" 
     data-pdv="<?php echo $point_de_vente; ?>" 
     data-target="#<?php echo $donnees['numero_commande_stock']; ?>">
     
    <a target="_blank" rel="noopener noreferrer" 
       href="vente_recap_details.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>">
       <span class="w3-badge w3-gray"><?php echo $j; ?></span> 
       <?php echo $donnees['description_date'].' | '."<u style='font-weight: bold'>$point_de_vente</u>".' | by '.$donnees['user_mvt'] ;?>
    </a>
    
    <a style="<?php if ($donnees['status'] == 'OFF') {echo "pointer-events: none";} ?>" 
       class="float-right" 
       href="supprimer_journal.php?numero_commande=<?php echo $donnees['no_activite']; ?>" 
       onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;">
       <img src="img/deleteicon.png" height="30" width="30" alt="Edit" />
    </a>
    
    <br><br>
    <span class="text-light"><?php echo $donnees['note_general']; ?></span>
</div>

<!----------------

<div class="table btn-brown text-left <?php echo$color; ?> container" data-target="#<?php echo $donnees['numero_commande_stock']; ?>"><a target="_blank" rel="noopener noreferrer" href="vente_recap_details.php?nom_client_fournisseur=<?php echo $point_de_vente; ?>&description_date=<?php echo $donnees['description_date']; ?>&no_activite=<?php echo $target; ?>"><span class="w3-badge w3-gray"><?php echo $j; ?></span> <?php echo $donnees['description_date'].' | '."<u style='font-weight: bold'>$point_de_vente</u>".' | by '.$donnees['user_mvt'] ;?></a><a style="<?php if ($donnees['status'] == 'OFF') {echo "pointer-events: none";} ?>" class="float-right" href="supprimer_journal.php?numero_commande=<?php echo $donnees['no_activite']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a><br><br><span class="text-light"><?php echo $donnees['note_general']; ?></span></div>------->
</ul>
</div>
<?php
$j = $j+1;
}

$q->closeCursor();
 ?>
<br>
<br>
	<br>
	<br>
	<br>
	<script>
document.getElementById('filter_pdv').addEventListener('change', function() {
    let selected = this.value;
    let items = document.querySelectorAll('[data-pdv]');
    
    items.forEach(function(item) {
        if (selected === "" || item.getAttribute('data-pdv') === selected) {
            item.style.display = "block"; // miseho
        } else {
            item.style.display = "none"; // afenina
        }
    });
});
</script>

</body>

<script>
function unlock(e){
	var id = <?php echo json_encode($id); ?>;
	var password =  e.val();
    //$("#"+id_montant).val(mt);
    //$("#"+ib).on('click',doSubmit);
    //$("#"+ib).text("style");
    if (password == "2022") {
    	for (var i = 1; i <= id; i++) {
    	//alert(i);
    	$("#"+i).removeAttr("style");
    	}
	}
}
</script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>