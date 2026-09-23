
<!--------------------------------------->
<?php
//Connect to BD
include('connect.php');

   //Query to liste point de vente
   $query_client_liste = "SELECT DISTINCT nom_client_fournisseur from mvt";
    $qc = $bdd->prepare($query_client_liste);

    $qc->execute(array());
    //Number of Line
    $nb_client=$qc->rowCount ();   
?>
<div class="flex-center flex-column">
   <table class="table-sm" style="font-weight: bold;background-image: linear-gradient(rgba(255, 110, 196, .9),rgba(252, 98, 98, .9),rgba(255, 216, 111, .9),#e0f2f1">
        <thead>
        <tr>
        <th class="text-center" colspan="3"  style="font-weight: bold;background-image: linear-gradient(to right,red,white,red)"><b>ZERO & NEGATIF</b></th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php

while ($donnees = $qc -> fetch())
{ 

//Query for Produit epuise qt egal zero
$query_produit_epuise = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND status !='OFF' GROUP BY id_x) as resultante_table WHERE sm = 0 ORDER BY sm DESC;";
    $q = $bdd->prepare($query_produit_epuise);
    $q->execute(array($donnees['nom_client_fournisseur']));
    //Number of Line
    $nb_produit_zero=$q->rowCount ();
     $q->closeCursor();
     
     if ($nb_produit_zero == 0) {
     $details_zero = "";
     } else {
     $details_zero = "ZERO";
     }
     
//Query for Produit negatif
$query_produit_negatif = "SELECT * FROM (SELECT nom_client_fournisseur, produit.id_x as id_x, nom_x, SUM(qt) as sm, reference_x FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND status !='OFF' GROUP BY id_x) as resultante_table WHERE sm < 0 ORDER BY sm DESC;";
    $q = $bdd->prepare($query_produit_negatif);
    $q->execute(array($donnees['nom_client_fournisseur']));
    //Number of Line
    $nb_produit_negatif=$q->rowCount ();
     $q->closeCursor();
     if ($nb_produit_negatif == 0) {
     $details_negatif = "";
     } else {
     $details_negatif = "NEG";
     }
     if ($nb_produit_zero > 0 || $nb_produit_negatif > 0){
?>
<!------------------------------------------------->
       <tr>
        <td><?php echo $donnees['nom_client_fournisseur']; ?></td>
        <td><?php echo $nb_produit_zero; ?><a href="stock_epuise_zero_trie.php?nom_client_fournisseur=<?php echo $donnees['nom_client_fournisseur']; ?>"><span class="w3-badge w3-right w3-margin-right w3-red"><?php echo $details_zero; ?></span></a></td>
        <td><?php echo $nb_produit_negatif; ?><a href="stock_epuise_negatif.php?nom_client_fournisseur=<?php echo $donnees['nom_client_fournisseur']; ?>"><span class="w3-badge w3-right w3-margin-right w3-purple"><?php echo $details_negatif; ?></span></a></td>
       </tr>
 <?php
    }
    }
 $qc->closeCursor();
 
?>
        </tboady>
    </table>
    </div>
<br>
<br>