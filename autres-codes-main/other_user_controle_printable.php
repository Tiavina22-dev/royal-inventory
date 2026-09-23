<?php
//include('connect.php');
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
if (isset($_POST['user_name']))
{ $user_name = $_POST['user_name'];}

setcookie("other_user",$user_name, time()+5);
setcookie("limit",'no_limit', time()+5);
header("location: controle_x_printable.php");
?>