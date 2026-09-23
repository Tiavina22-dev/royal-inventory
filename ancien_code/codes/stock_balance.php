
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
   <table class="table-sm bg-info">
        <thead>
        <tr>
        <th class="text-center" colspan="3"><b>BALANCED</b></th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
while ($donnees = $qc -> fetch())
{ 

//Query for occurence of balance_zero
$query_produit_epuise = "SELECT * FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND (description_date LIKE '%balance_zero%');";
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
     
//Query for occurence of balance_negatif
$query_produit_negatif = "SELECT * FROM mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND (description_date LIKE '%balance_negative%');";
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
?>
<!------------------------------------------------->
       <tr>
        <td><?php echo $donnees['nom_client_fournisseur']; ?></td>
        <td><?php echo $nb_produit_zero; ?><a href="stock_balanced_zero.php?nom_client_fournisseur=<?php echo $donnees['nom_client_fournisseur']; ?>"><span class="w3-badge w3-right w3-margin-right w3-red"><?php echo $details_zero; ?></span></a></td>
        <td><?php echo $nb_produit_negatif; ?><a href="stock_balanced_negatif.php?nom_client_fournisseur=<?php echo $donnees['nom_client_fournisseur']; ?>"><span class="w3-badge w3-right w3-margin-right w3-purple"><?php echo $details_negatif; ?></span></a></td>
       </tr>
 <?php
    }
 $qc->closeCursor();
 
?>
        </tbody>
    </table>
    </div>
<br>
<br>