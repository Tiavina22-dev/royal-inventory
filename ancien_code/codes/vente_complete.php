<?php
include('connect.php'); // connexion à la base de données

$msg_ok = $msg_nok = "";
if (isset($_COOKIE['msg_ok'])) $msg_ok=$_COOKIE['msg_ok'];
if (isset($_COOKIE['msg_nok'])) $msg_nok=$_COOKIE['msg_nok'];

$id_stock = isset($_GET['id_stock']) ? intval($_GET['id_stock']) : 0;
$client_name = isset($_GET['client']) ? $_GET['client'] : '';
$type_vente = isset($_GET['type']) ? $_GET['type'] : 'vente';

$key_word = isset($_GET['key_word']) ? $_GET['key_word'] : '';
$checkbox = isset($_GET['checkbox']) ? $_GET['checkbox'] : 'TRUE';

// Fetch stock
if (!empty($key_word)) {
    if ($checkbox == 'TRUE') {
        $query_stock = $bdd->prepare("SELECT * FROM produit WHERE (nom_x LIKE ? OR reference_x LIKE ? OR id_x LIKE ?) AND stock_id = ?");
        $query_stock->execute(["%$key_word%", "%$key_word%", "%$key_word%", $id_stock]);
    } else {
        $query_stock = $bdd->prepare("SELECT * FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? OR id_x LIKE ?");
        $query_stock->execute(["%$key_word%", "%$key_word%", "%$key_word%"]);
    }
} else {
    $query_stock = $bdd->prepare("SELECT * FROM produit WHERE stock_id = ?");
    $query_stock->execute([$id_stock]);
}

// Fetch commandes existantes
$query_commande_list = $bdd->prepare("SELECT * FROM commande WHERE id_stock = ? AND client_name = ?");
$query_commande_list->execute([$id_stock, $client_name]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vente Complete</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
.light-table-filter { margin-bottom:10px; }
</style>
</head>
<body class="bg-light">
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<div class="container mt-4">
<h2 class="text-center">VENTE - <?php echo strtoupper($client_name); ?></h2>
<h5 class="text-center text-success"><?php echo $msg_ok; ?></h5>
<h5 class="text-center text-danger"><?php echo $msg_nok; ?></h5>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<!-- SEARCH FORM -->
<form role="form" action="vente_complete.php" method="GET" class="mb-3">
    <div class="text-center">
        <input type="search" class="form-control d-inline-block" style="width:300px;" name="key_word" placeholder="Nom / Ref / Code" value="<?php echo htmlspecialchars($key_word); ?>">
        <input type="hidden" name="client" value="<?php echo htmlspecialchars($client_name); ?>">
        <input type="hidden" name="id_stock" value="<?php echo $id_stock; ?>">
        <button type="submit" class="btn btn-info">Search</button>
        <a href="stock_reduit.php" target="_blank" class="btn btn-indigo">Stock Retirer / Retours</a>
    </div>
    <div class="text-center mt-2">
        <input type="checkbox" name="checkbox" value="TRUE" <?php if($checkbox=='TRUE') echo 'checked'; ?>>
        <label>Chercher dans le Stock Existant Seulement</label>
    </div>
</form>

<?php if($query_stock->rowCount() == 0): ?>
<p class="text-center text-danger"><b>Aucun produit trouvé pour '<?php echo htmlspecialchars($key_word); ?>'</b></p>
<?php else: ?>
<!-- TABLE STOCK -->
<table class="table table-striped">
<thead class="table-danger">
<tr>
<th>Image</th>
<th>Nom / Description</th>
<th>Ref ID</th>
<th>Prix</th>
<th>Qte Dispo</th>
<th>Ajouter</th>
</tr>
</thead>
<tbody>
<?php while($donnees = $query_stock->fetch()): 
$pu_list = $donnees['prix_de_vente'];
$qt_dispo = $donnees['qt_dispo'];
$image_path_x = $donnees['img_path_x'] ?: 'img_x/default_x.png';
?>
<tr>
<td class="text-center">
<a href="#View" data-toggle="modal" data-target="#<?php echo "img".$donnees['id_x']; ?>">
<img src="<?php echo $image_path_x; ?>" height="50" width="50" alt="Image"/>
</a>
</td>
<td><?php echo $donnees['nom_x'].' : '.$donnees['note_x']; ?></td>
<td><?php echo $donnees['reference_x']; ?></td>
<td><?php echo number_format($pu_list,0,""," "); ?></td>
<td><?php echo $qt_dispo; ?></td>
<td>
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#<?php echo ("no".$donnees['id_x']); ?>">Select</button>
</td>
</tr>

<!-- MODAL INSERT COMMANDE -->
<div class="modal fade" id="<?php echo ("no".$donnees['id_x']); ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header bg-warning">
<h5 class="modal-title">Ajouter <?php echo $donnees['nom_x']; ?></h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<form action="insert_commande.php" method="post">
<div class="form-group">
<label>Quantité</label>
<input type="number" step="any" name="qt" class="form-control" value="1">
</div>
<div class="form-group">
<label>Prix de Vente (Ariary)</label>
<input type="number" name="prix_de_vente" class="form-control" value="<?php echo $pu_list; ?>">
</div>
<div class="form-group">
<label>Note</label>
<input type="text" name="note_commande" class="form-control">
</div>
<input type="hidden" name="id_x" value="<?php echo $donnees['id_x']; ?>">
<input type="hidden" name="client_name" value="<?php echo $client_name; ?>">
<input type="hidden" name="id_stock" value="<?php echo $id_stock; ?>">
<button type="submit" class="btn btn-success btn-block">Valider</button>
</form>
</div>
</div>
</div>
</div>

<?php endwhile; ?>
</tbody>
</table>
<?php endif; ?>

<!-- TABLE COMMANDE -->
<h3 class="mt-4">Commandes en cours</h3>
<table class="table table-striped table-bordered">
<thead class="table-primary">
<tr>
<th>No</th>
<th>Ref</th>
<th>Nom</th>
<th>Qt</th>
<th>Prix</th>
<th>Sous Total</th>
<th>Note</th>
<th>Modifier</th>
<th>Supprimer</th>
</tr>
</thead>
<tbody>
<?php
$total_commande = 0;
$no = 0;
while($donnees = $query_commande_list->fetch()):
$no++;
$total_commande += $donnees['prix_unitaire']*$donnees['qt'];
?>
<tr>
<td><?php echo $no; ?></td>
<td><?php echo $donnees['reference_x']; ?></td>
<td><?php echo $donnees['nom_x']; ?></td>
<td><?php echo $donnees['qt']; ?></td>
<td><?php echo number_format($donnees['prix_unitaire'],0,""," "); ?></td>
<td><?php echo number_format($donnees['prix_unitaire']*$donnees['qt'],0,""," "); ?></td>
<td><?php echo $donnees['note_commande']; ?></td>
<td><a href="modifier_commande.php?id_commande=<?php echo $donnees['id_commande']; ?>" class="btn btn-warning btn-sm">Modifier</a></td>
<td><a href="delete_commande.php?id_commande=<?php echo $donnees['id_commande']; ?>" class="btn btn-danger btn-sm">Supprimer</a></td>
</tr>
<?php endwhile; ?>
<tr class="table-success">
<th colspan="5">TOTAL VENTE</th>
<th><?php echo number_format($total_commande,0,""," "); ?></th>
<th colspan="3"></th>
</tr>
</tbody>
</table>

</div>

</body>
</html>
