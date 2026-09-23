<?php
/*
TABLE colonne
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/

setcookie("additional_analyse_cookie","active", time()+5);
header("location: home_char.php");
?>