<?php
include('connect.php');
session_start();

// Get username
if (isset($_SESSION['User_Name'])) {
    $username = $_SESSION['User_Name'];
} else {
    $username = "default";
}

// Get POST variables safely
$description_date = isset($_POST['description_date']) ? $_POST['description_date'] : 'default_not_set';
$nom_client_fournisseur = isset($_POST['nom_client_fournisseur']) ? $_POST['nom_client_fournisseur'] : '';
$no_activite = isset($_POST['no_activite']) ? $_POST['no_activite'] : 0;

// Convert description_date to MySQL date format
$date1_mysql = null;
if ($description_date != 'default_not_set') {
    $chaine = $description_date . ' ';
    $x = chr(35).'/0-9-';
    preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);

    if (isset($res_regex[1])) {
        $date1 = str_replace(['- ', '/ ', ' / ', ' -', ' - ', '/'], '-', $res_regex[1]);
        $date_obj = DateTime::createFromFormat('d-m-y', $date1);
        if ($date_obj) {
            $date1_mysql = $date_obj->format('Y-m-d');
        }
    }
}

// Update mvt table including nom_client_fournisseur
$query_c = "UPDATE mvt
            SET description_date = ?, 
                Date_du_Journal_mvt = ?, 
                user_mvt = ?, 
                nom_client_fournisseur = ?
            WHERE numero_commande_stock = ? AND type_de_mvt = 'stock'";

$q = $bdd->prepare($query_c);
$q->execute(array($description_date, $date1_mysql, $username, $nom_client_fournisseur, $no_activite));
$q->closeCursor();

// Set cookies to reload details page
setcookie("nom_client_fournisseur", $nom_client_fournisseur, time()+5);
setcookie("description_date", $description_date, time()+5);
setcookie("no_activite", $no_activite, time()+5);

// Redirect back to details page
header("location: stock_recap_details.php");
exit;
?>
