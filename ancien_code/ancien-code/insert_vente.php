<?php
session_start();
include("connect.php");

if (!isset($_SESSION['User_Name'])) {
    header("Location: login.php");
    exit;
}

$id_x   = $_POST['id_x'];
$qt     = $_POST['qt'];
$prix   = $_POST['prix'];
$client = $_POST['client'];
$note   = $_POST['note'];
$user   = $_SESSION['User_Name'];


// 🔍 CALCUL STOCK DISPONIBLE
$sql_stock = "
SELECT 
SUM(CASE WHEN type_de_mvt='stock' THEN qt ELSE 0 END) -
SUM(CASE WHEN type_de_mvt='vente' THEN qt ELSE 0 END) AS stock_restant
FROM mvt
WHERE id_x = ?
";
$q = $bdd->prepare($sql_stock);
$q->execute([$id_x]);
$data = $q->fetch();

$stock_restant = $data['stock_restant'] ?? 0;

// ❌ Raha tsy ampy stock
if ($qt > $stock_restant) {
    setcookie("msg_nok", "Stock insuffisant !", time()+3, "/");
    header("Location: vente.php");
    exit;
}

// ✅ INSERT VENTE
$sql = "
INSERT INTO mvt
(id_x, qt, prix_unitaire, type_de_mvt, nom_client_fournisseur, note, date_mvt, user_mvt)
VALUES (?, ?, ?, 'vente', ?, ?, NOW(), ?)
";

$ins = $bdd->prepare($sql);
$ins->execute([
    $id_x,
    $qt,
    $prix,
    $client,
    $note,
    $user
]);

setcookie("msg_ok", "Vente enregistrée avec succès", time()+3, "/");
header("Location: vente.php");
