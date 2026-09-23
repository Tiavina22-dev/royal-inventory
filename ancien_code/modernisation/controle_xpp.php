<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Filtre Observation</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">

<div class="container">
  <h3 class="text-center mb-4">Tableau Stock</h3>

  <!-- Bouton Filtre -->
  <div class="mb-3 text-center">
    <button class="btn btn-danger filter-obs" data-filter="minus">Filtre -</button>
    <button class="btn btn-warning filter-obs" data-filter="all">Filtre - sy +</button>
    <button class="btn btn-success filter-obs" data-filter="plus">Filtre +</button>
  </div>

  <!-- Tableau -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Référence</th>
        <th>Nom</th>
        <th>Prix unitaire</th>
        <th>Sous total</th>
        <th>Observation</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // ohatra fotsiny angamba: mety avy amin'ny base de données ny tena izy
      $donnees = [
        ["reference_x"=>"REF001", "nom_x"=>"Produit A", "prix_unitaire"=>1000, "sous_total"=>5000, "qte"=>2],
        ["reference_x"=>"REF002", "nom_x"=>"Produit B", "prix_unitaire"=>2000, "sous_total"=>10000, "qte"=>-3],
        ["reference_x"=>"REF003", "nom_x"=>"Produit C", "prix_unitaire"=>1500, "sous_total"=>7500, "qte"=>0],
        ["reference_x"=>"REF004", "nom_x"=>"Produit D", "prix_unitaire"=>1800, "sous_total"=>9000, "qte"=>5],
      ];

      foreach ($donnees as $d) {
        $qte = $d['qte'];
        $color_badge = "bg-secondary";
        $obs_type = "ok";
        $observation = "OK";

        if ($qte > 0) {
          $observation = "+" . $qte;
          $color_badge = "bg-success";
          $obs_type = "plus";
        } elseif ($qte < 0) {
          $observation = $qte; // efa misy signe -
          $color_badge = "bg-danger";
          $obs_type = "minus";
        }

        echo "<tr>";
        echo "<td>{$d['reference_x']}</td>";
        echo "<td>{$d['nom_x']}</td>";
        echo "<td class='text-end'>".number_format($d['prix_unitaire'],0,","," ")."</td>";
        echo "<td class='text-end'>".number_format($d['sous_total'],0,","," ")."</td>";
        echo "<td class='text-end observation' data-type='{$obs_type}'>
                <span class='badge {$color_badge}'>{$observation}</span>
              </td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Script filtre -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const buttons = document.querySelectorAll(".filter-obs");
  
  buttons.forEach(btn => {
    btn.addEventListener("click", function() {
      const filter = this.getAttribute("data-filter");
      const rows = document.querySelectorAll("table tbody tr");
      
      rows.forEach(row => {
        const obs = row.querySelector(".observation");
        if (!obs) return;

        const type = obs.getAttribute("data-type");

        if (filter === "all") {
          // aseho ireo minus sy plus ihany
          if (type === "minus" || type === "plus") {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        } else if (type === filter) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  });
});
</script>

</body>
</html>
