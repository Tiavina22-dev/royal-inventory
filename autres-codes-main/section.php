  <section class="container">
    <h1>Preparation Commande</h2>
 
    <input type="search" class="light-table-filter" data-table="table-info" placeholder="Filter/Search">
    <a href="#Modal_new" data-toggle="modal" class="btn btn-success">Nouveau Produit</a>
     
    <table class="table-info table">
        <thead>
        <tr>
        <th>Nom/Description de Produit</th>
        <th>Prix</th>
        <th>Qte Dispo</th>
        <th>Note</th>
        <th>Ref ID</th>
        <th>Ajouter</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
include('connect.php');
$query_commande_search = $bdd->query('SELECT nom_x, prix_de_vente, (SUM(qt)) as qt, reference_x, note_x FROM mvt INNER JOIN produit ON mvt.id_x = produit.id_x GROUP BY reference_x ORDER BY date_time;');
while ($donnees = $query_commande_search -> fetch())
{             
?>
<!------------------------------------------------->
        <tr>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td><?php echo $donnees['prix_de_vente']; ?></td>
        <td><?php echo $donnees['qt']; ?></td>
        <td><?php echo $donnees['note_x']; ?></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><a href="#Modal_select" data-toggle="modal" class="btn btn-primary">Select</a></td>
        </tr>
<!------------------------------------------------->
<?php
}
?>
        </tbody>
    </table>
 
</section>