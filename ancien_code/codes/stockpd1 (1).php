<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Facture</title>
<script src="js/jquery-3.5.1.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">
  <!---add other css--->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }
  table, th, td {
    border: 1px solid black;
  }
  th, td {
    padding: 4px;
    text-align: left;
  }
</style>
  <style>
  /* Raha tabilao manontolo */
  #tab {
    font-family: Arial, sans-serif; /* mety soloina Verdana, Calibri, sns */
    font-size: 20px;                /* ngeza kokoa */
  }

  /* Raha lohateny (th) tianao ho tena lehibe */
  #tab th {
    font-size: 22px;
    font-weight: bold;
    color: darkblue;
  }

  /* Raha vatana (td) tianao ho kely kokoa noho ny th */
  #tab td {
    font-size: 18px;
    font-weight: bold;
  }
</style>
</head>
<body>
<!--------------------------------------->
<?php
//GET PARAMETER
  $nom_client_fournisseur ="";
  //Cookies from Delete
  if (isset($_COOKIE['nom_client_fournisseur'])) 
  {
   $nom_client_fournisseur = $_COOKIE['nom_client_fournisseur'];
  }
  //from stock_recap
  if (isset($_GET['nom_client_fournisseur'])) {
    $nom_client_fournisseur = $_GET['nom_client_fournisseur'];
  }

  $description_date ="Journal du 22/10/20";
  //Cookies from Delete and modifier_date_stock
  if (isset($_COOKIE['description_date'])) 
  {
   $description_date=$_COOKIE['description_date'];
  }
  //from stock_recap
  if (isset($_GET['description_date'])) {
    $description_date = $_GET['description_date'];
  }
    //USE FOR JS COMPARE DATE
        $chaine =  $description_date.'';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $inventory_date = $res_regex[1];
        }
        //$date1 = strtotime($res_regex[1]);
        //$date1 = date("y/m/d", $date1);

        //Eviter wrong format and unwanted space for $date1
        $inventory_date = str_replace('/ ', '-', $inventory_date);
        $inventory_date = str_replace(' / ', '-', $inventory_date);
        $inventory_date = DateTime::createFromFormat('d/m/y', $inventory_date);
        $inventory_date = $inventory_date -> format('Y-m-d');
        //--------------------------

  //from stock_recap
  if (isset($_GET['no_activite'])) {
    $no_activite = $_GET['no_activite'];
  }
  //Cookies from modify date

  if (isset($_COOKIE['no_activite'])) 
  {
   $no_activite=$_COOKIE['no_activite'];
  }
//Connect to BD
include('connect.php');
//QUERY REFERENCE TEST
  $query_num_stock = $bdd->query('SELECT reference_x FROM produit');
  $u = 0;
  while ($reference_x = $query_num_stock -> fetch())
{
  $u = $u + 1;
  $ref[$u] = $reference_x['reference_x'];
  //echo $reference_x['reference_x'];
}
  //$u = 2;
  $query_num_stock ->closeCursor();
?>
<!------------SIMPLE SEARCH------------------>
<br>
<br>
<div class="text-center">
  <a href="stock_recap.php">
  <button class="btn btn-success">Retours</button></a>
  <button class="btn btn-primary" id="exportBtn">Sary</button>
</div>
<br>
<br>
<br>
<!---------------search result---------------------------->
<?php
/*
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{
   $key_word=$_COOKIE['key_word'];
   //echo $key_word;
}
*/

   //Query to liste searched product
   $query_stock_inventaire = "SELECT *,nom_x,prix_unitaire,qt,numero_commande_stock,ref_commande_stock from mvt NATURAL JOIN produit WHERE nom_client_fournisseur = ? AND description_date = ? AND type_de_mvt = 'stock'";
  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array($nom_client_fournisseur, $description_date));
  //Number of Line
  $nb_line = $q->rowCount ();
  if ($nb_line == 0) {
    echo "<br><b>"."[".$nom_client_fournisseur."]"." does not exist on the base (table produit), Click <a href='stock_recap.php'>RETOURS</b><br>";
  } else {
  
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');

?>
<div id="container" class="container">
    <br>
    <h4  id="titre-tableau" class="text-center" style="font-family: Arial ; font-weight: bold; font-size: 25px;"><?php echo $description_date;?> | <?php echo $nom_client_fournisseur;?></h4>
    <br>
   <table id="tab" class="table table-bordered dt-responsive nowrap">
        <thead>
        <tr>
        <th>No</th>
        <th>Ref</th>
        <th>Designation</th>
        <th>QT</th>
        <th class="text-right">PU</th>
        <th class="text-right">Amarotana</th>
       <!------------ <th class="text-right">Fournisseur</th>------------------->
        <th>NOTE</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
$Total = 0;
$MT = 0;
$nb_line = 1;

//Query searcher word
$ref_id = "xxxxxxs";

while ($donnees = $q -> fetch())
{ 
$ref_id = $j;
//echo $ref_id; 
$text_id1 = 'text_id1'.$j;
$text_id2 = 'text_id2'.$j;
$j = $j+1;
$qt = $donnees['qt'];
$MT = $donnees['prix_unitaire']*$qt;
$Total = $MT+$Total;

//IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'img_x/default_x.png';
     }                
?>
<!------------------------------------------------->
      <tr>
        <td class="text-center"><?php echo ($nb_line); $nb_line = $nb_line+1; ?></td>
        <td class="text-center"><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td class="text-center"><?php echo $qt; ?></td>
        <td class="text-right"><?php echo $donnees['prix_unitaire']; ?></td>
        <td class="text-info text-right"><?php echo ($donnees['prix_client']+0); ?></td>
        <td><?php echo $donnees['note']; ?></td>
      </tr>
<?php
}
?>

        </tbody>
    </table>
    <br>
    <br>
</div>
 <?php
  }

 $q->closeCursor();
 
?>
</body>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const btn = document.getElementById("exportBtn");

    btn.addEventListener("click", async function() {
        const container = document.getElementById('container');
        const table = container.querySelector("#tab");
        const thead = table.querySelector("thead");
        const tbodyRows = Array.from(table.querySelectorAll("tbody tr"));

        if (!table || tbodyRows.length === 0) { 
            console.error("Table introuvable ou vide"); 
            return; 
        }

        const titreElement = document.getElementById("titre-tableau");
        const safeTitre = titreElement 
            ? titreElement.innerText.replace(/\s+/g, " ").replace(/[^\w\-]/g, " ") 
            : "tableau";

        // Dimensions A4
        const A4_WIDTH_MM = 210;
        const A4_HEIGHT_MM = 297;
        const PX_PER_MM = 3.78; // 96dpi approx
        const A4_WIDTH_PX = A4_WIDTH_MM * PX_PER_MM;
        const A4_HEIGHT_PX = A4_HEIGHT_MM * PX_PER_MM;

        // Esorina padding 20mm (10mm ambony + ambany) ho an'ny content
        const CONTENT_HEIGHT_PX = A4_HEIGHT_PX - (20 * PX_PER_MM);

        let pageCount = 0;
        let pageDiv, tempTable, currentTbody;
        let usedHeight = 0; // kajy ampiasaina

        function newPage() {
            pageDiv = document.createElement("div");
            pageDiv.style.width = A4_WIDTH_MM + "mm";
            pageDiv.style.height = A4_HEIGHT_MM + "mm";
            pageDiv.style.boxSizing = "border-box";
            pageDiv.style.padding = "5mm";
            pageDiv.style.position = "absolute";
            pageDiv.style.left = "-9999px";
            pageDiv.style.background = "#fff";

            if (titreElement) {
                const clonedTitre = titreElement.cloneNode(true);
                clonedTitre.style.marginBottom = "5px";
                pageDiv.appendChild(clonedTitre);
                usedHeight = clonedTitre.offsetHeight || 30; // kajy tombanana
            } else {
                usedHeight = 0;
            }

            tempTable = document.createElement("table");
            tempTable.style.cssText = window.getComputedStyle(table).cssText;

            if (thead) {
                const clonedHead = thead.cloneNode(true);
                tempTable.appendChild(clonedHead);
                usedHeight += 40; // tombanana ho an'ny header
            }

            currentTbody = document.createElement("tbody");
            tempTable.appendChild(currentTbody);
            pageDiv.appendChild(tempTable);
            document.body.appendChild(pageDiv);
        }

        async function exportPage() {
            pageCount++;
            await html2canvas(pageDiv, { scale: 2, useCORS: true, allowTaint: true })
                .then(canvas => {
                    const imgData = canvas.toDataURL("image/jpeg", 1.0);
                    const link = document.createElement("a");
                    link.href = imgData;
                    link.download = `${safeTitre}-${pageCount}.jpg`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            document.body.removeChild(pageDiv);
        }

        newPage();

        for (let i = 0; i < tbodyRows.length; i++) {
            const clonedRow = tbodyRows[i].cloneNode(true);
            clonedRow.querySelectorAll("td, th").forEach(cell => {
                cell.style.cssText = window.getComputedStyle(cell).cssText;
            });

            // Tombanana ny haavon'ny row
            document.body.appendChild(clonedRow);
            let rowHeight = clonedRow.offsetHeight || 20;
            document.body.removeChild(clonedRow);

            if (usedHeight + rowHeight > CONTENT_HEIGHT_PX) {
                // Raha tsy maharaka intsony dia export aloha
                await exportPage();
                newPage();
                usedHeight = 40; // header
            }

            currentTbody.appendChild(clonedRow);
            usedHeight += rowHeight;
        }

        // Pejy farany
        await exportPage();
    });
});

</script>
</html>