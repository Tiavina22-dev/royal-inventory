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

// Maka ny lisitry ny shop rehetra misy mouvement
$query_shop = "
    SELECT DISTINCT s.short_name, s.long_name 
    FROM shop s
    JOIN mvt m ON m.nom_client_fournisseur = s.short_name
    WHERE m.status != 'OFF'
    ORDER BY s.long_name
";
$stmt_shop = $bdd->prepare($query_shop);
$stmt_shop->execute();
$shops = $stmt_shop->fetchAll();

// Raha mbola tsy voafidy dia tsy misy filtrage
$point_de_vente = isset($_GET['point_de_vente']) ? $_GET['point_de_vente'] : null;
?>

<div style="text-align:center; margin:20px;">
    <form method="GET">
        <select name="point_de_vente" class="form-control btn btn-warning" style="width:300px; display:inline-block;">
            <option value="" disabled selected>Choisir Point de Vente</option>
            <?php foreach ($shops as $shop) { ?>
                <option value="<?= $shop['short_name']; ?>" 
                    <?= ($point_de_vente == $shop['short_name']) ? 'selected' : ''; ?>>
                    <?= $shop['long_name']; ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" class="btn btn-success btn-sm">Filtrer</button>
    </form>
</div>

<?php
if ($point_de_vente) {
    // Maka ny ventes taorian'ny inventaire farany
    $query_stock_inventaire = "
        SELECT * 
        FROM (
            SELECT * 
            FROM mvt  
            WHERE type_de_mvt = 'vente'
              AND nom_client_fournisseur = ?
              AND Date_du_Journal_mvt >= (
                  SELECT MAX(Date_du_Journal_mvt)
                  FROM mvt AS inv
                  WHERE inv.type_de_mvt = 'stock'
                    AND inv.status LIKE 'General_Inventory'
                    AND inv.nom_client_fournisseur = mvt.nom_client_fournisseur
              )
            GROUP BY numero_commande_stock
        ) AS mvt 
        INNER JOIN recap_vente 
            ON recap_vente.no_activite = mvt.numero_commande_stock 
        ORDER BY Date_du_Journal_mvt DESC
    ";

    $q = $bdd->prepare($query_stock_inventaire);
    $q->execute([$point_de_vente]);

    echo '<ul class="list-group container">';
    $j = 1;
    while ($donnees = $q->fetch()) {
        $target = $donnees['numero_commande_stock'];
        $desc   = $donnees['description_date'];
        $user   = $donnees['user_mvt'];

        echo '
        <li class="list-group-item">
            <a target="_blank" href="vente_recap_details.php?nom_client_fournisseur='.$point_de_vente.'&description_date='.$desc.'&no_activite='.$target.'">
                <span class="badge badge-dark">'.$j.'</span>
                '.$desc.' | <u style="font-weight:bold">'.$point_de_vente.'</u> | by '.$user.'
            </a>
        </li>';
        $j++;
    }
    echo '</ul>';
}
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