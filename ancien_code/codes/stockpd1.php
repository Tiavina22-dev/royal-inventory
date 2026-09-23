<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ajouter Stock et Inventaire</title>
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
  th, tr {
    padding: 4px;
    text-align: center;
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
<!------<body style="background: #CF9FFF">----->
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
<br>
 <br>
 <br>
 <br>
 <br>
 <br>
<?php
include("connect.php");

// ==========================
// SESSION USER
// ==========================
$username = isset($_SESSION['User_Name']) ? $_SESSION['User_Name'] : "default";

// ==========================
// GET PARAMETERS
// ==========================
$nom_client_fournisseur = isset($_GET['nom_client_fournisseur']) ? $_GET['nom_client_fournisseur'] : "";
$description_date = isset($_GET['description_date']) ? $_GET['description_date'] : "";

// ==========================
// CHECK IF NOTES EXIST
// ==========================
$notes_exist = false;
$sql_note = "SELECT COUNT(*) as total_note
             FROM mvt
             WHERE nom_client_fournisseur = ?
             AND description_date = ?
             AND type_de_mvt = 'stock'
             AND note IS NOT NULL
             AND note != ''";
$stmt_note = $bdd->prepare($sql_note);
$stmt_note->execute([$nom_client_fournisseur, $description_date]);
$res_note = $stmt_note->fetch(PDO::FETCH_ASSOC);
if ($res_note && $res_note['total_note'] > 0) {
    $notes_exist = true;
}

// ==========================
// MAIN QUERY
// ==========================
$sql = "SELECT *
        FROM mvt
        NATURAL JOIN produit
        WHERE nom_client_fournisseur = ?
        AND description_date = ?
        AND type_de_mvt = 'stock'
        ORDER BY nom_x ASC";
$q = $bdd->prepare($sql);
$q->execute([$nom_client_fournisseur, $description_date]);
?>

<div class="text-center">
    <a href="stock_recap.php" class="btn btn-success">Retour</a>
    <button class="btn btn-primary" id="exportBtn">Sary</button>
</div>

<?php if ($q->rowCount() == 0): ?>
    <div class='alert alert-danger text-center'>Aucune donnée trouvée</div>
<?php else: ?>

<div id="container" class="container">
    <br>
    <br>
    <h4 id="titre-tableau" class="text-center" style="font-family: Arial ; font-weight: bold; font-size: 25px;"> 
        <?= htmlspecialchars($nom_client_fournisseur) ?> | 
        <?= htmlspecialchars($description_date) ?> 
    </h4>

    <table id="tab" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Ref</th>
                <th>Designation</th>
                <th>QT</th>
                <?php $isSpecial = ($nom_client_fournisseur == 'MoraranoCh' || $nom_client_fournisseur == 'Bejofo'); ?>
                <?php if ($isSpecial): ?>
                    <th>Amarotana</th>
                <?php else: ?>
                    <th>PU</th>
                    <th>Amarotana</th>
                <?php endif; ?>
                <?php if ($notes_exist): ?>
                    <th>NOTE</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while ($row = $q->fetch(PDO::FETCH_ASSOC)):
                $qt = $row['qt'];
                $pu = $row['prix_unitaire'];
                $pc = $row['prix_client'];
            ?>
            <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td class="text-center"><?= htmlspecialchars($row['reference_x']) ?></td>
                <td><?= htmlspecialchars($row['nom_x']) ?></td>
                <td class="text-center"><?= $qt ?></td>
                <?php if ($isSpecial): ?>
                    <td class="text-right"><?= number_format($pu) ?></td>
                <?php else: ?>
                    <td class="text-right"><?= number_format($pu) ?></td>
                    <td class="text-right"><?= number_format($pc) ?></td>
                <?php endif; ?>
                <?php if ($notes_exist): ?>
                    <td><?= !empty(trim($row['note'])) ? htmlspecialchars($row['note']) : "" ?></td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
</div>

<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btn = document.getElementById("exportBtn");

    btn.addEventListener("click", async function() {
        const container = document.getElementById('container');
        const table = container.querySelector("#tab");
        const thead = table.querySelector("thead");
        const tbodyRows = Array.from(table.querySelectorAll("tbody tr"));

        if (!table || tbodyRows.length === 0) return;

        // --- 1. FANAMBOARANA NY TITRE SY NY ANARANA ---
const titreElement = document.getElementById("titre-tableau");
let titreHiseho = "Stock Tableau"; // Default raha tsy hita ilay element

if (titreElement) {
    const t = titreElement.innerText; // "Ambato_Tantely | Inventaire/Ajout du 20/02/26"
    
    // Maka ny anaran'ny toerana (site) - "Ambato_Tantely"
    // Ampiasaina ny split('|') ary esorina ny space amin'ny trim()
    const siteName = t.split('|')[0].trim(); 
    
    // Maka ny daty (mitady ny format 00/00/00)
    const dateMatch = t.match(/(\d{2}\/\d{2}\/\d{2})/);
    const dateStock = dateMatch ? dateMatch[0] : "";
    
    // Ity no lohateny hiseho: "Stock Ambato_Tantely 20/02/26"
    titreHiseho = "Stock " + siteName + " " + dateStock;
}

// Ity no ampiasaina amin'ny anaran'ny sary (download) - soloina "-" ny "/"
const safeTitre = titreHiseho.replace(/\//g, "-");


        const A4_WIDTH_MM = 210;
        const A4_HEIGHT_MM = 297;
        const PX_PER_MM = 3.78; 
        const MAX_CONTENT_HEIGHT = (A4_HEIGHT_MM - 25) * PX_PER_MM;

        let pageCount = 0;
        let pagesArray = []; // Tahiry hitahirizana ny pejy rehetra
        let pageDiv, tempTable, currentTbody, usedHeight;

        function createNewPage() {
            pageCount++;
            pageDiv = document.createElement("div");
            pageDiv.style.width = A4_WIDTH_MM + "mm";
            pageDiv.style.height = A4_HEIGHT_MM + "mm";
            pageDiv.style.padding = "10mm";
            pageDiv.style.boxSizing = "border-box";
            pageDiv.style.background = "#fff";
            pageDiv.style.position = "fixed";
            pageDiv.style.top = "0";
            pageDiv.style.left = "-5000px"; // Afenina mandra-pahavitan'ny kajy
            pageDiv.style.zIndex = "9999";

            if (titreElement) {
    // Mamorona singa vaovao (h2) fa tsy mikopia an'ilay teo aloha intsony
    const lohatenyVaovao = document.createElement("h2");
    
    // TitreHiseho dia ilay namboarintsika teo aloha: "Stock Ambato_Tantely 20/02/26"
    lohatenyVaovao.innerText = titreHiseho; 
    
    // Fanamboarana ny fisehony (Style)
    lohatenyVaovao.style.margin = "0 0 15px 0";
    lohatenyVaovao.style.textAlign = "center"; // Atao eo afovoany
    lohatenyVaovao.style.fontSize = "20px";
    lohatenyVaovao.style.fontWeight = "bold";
    lohatenyVaovao.style.fontFamily = "Arial, sans-serif";
    
    // Ampidirina ao anaty pejy
    pageDiv.appendChild(lohatenyVaovao);
}


            tempTable = document.createElement("table");
            tempTable.style.cssText = window.getComputedStyle(table).cssText;
            tempTable.style.width = "100%";
            tempTable.style.borderCollapse = "collapse";

            if (thead) {
                tempTable.appendChild(thead.cloneNode(true));
            }

            currentTbody = document.createElement("tbody");
            tempTable.appendChild(currentTbody);
            pageDiv.appendChild(tempTable);

            // FOOTER (Tsy misy ligne intsony)
            const footer = document.createElement("div");
            footer.className = "page-number-footer"; // Class ahafahana manova azy avy eo
            footer.style.position = "absolute";
            footer.style.bottom = "10mm";
            footer.style.right = "90mm";
            footer.style.fontSize = "16px";
            footer.style.fontWeight = "bold";
            footer.style.fontFamily = "Arial, sans-serif";
            footer.style.textAlign = "center";
            // Nesorina ny borderTop teto
            pageDiv.appendChild(footer);

            document.body.appendChild(pageDiv);
            pagesArray.push({ element: pageDiv, footer: footer, num: pageCount });
            
            usedHeight = tempTable.offsetTop + (thead ? thead.offsetHeight : 0);
        }

        // Mamorona pejy voalohany
        createNewPage();

        // Mizara ny andalana anaty pejy
        for (let i = 0; i < tbodyRows.length; i++) {
            const row = tbodyRows[i];
            const clonedRow = row.cloneNode(true);

            const originalCells = row.querySelectorAll("td, th");
            clonedRow.querySelectorAll("td, th").forEach((cell, idx) => {
                cell.style.cssText = window.getComputedStyle(originalCells[idx]).cssText;
            });

            currentTbody.appendChild(clonedRow);
            const rowHeight = clonedRow.offsetHeight;

            if (usedHeight + rowHeight > MAX_CONTENT_HEIGHT) {
                currentTbody.removeChild(clonedRow);
                createNewPage(); // Mamorona pejy vaovao raha feno ny teo
                currentTbody.appendChild(clonedRow);
                usedHeight = tempTable.offsetTop + (thead ? thead.offsetHeight : 0) + clonedRow.offsetHeight;
            } else {
                usedHeight += rowHeight;
            }
        }

        // EXPORT REHETRA REHEFA VITA NY KAJY
        const totalPages = pagesArray.length;

        for (const item of pagesArray) {
            // Fanovana ny soratra ho "Pejy: 1/4"
            item.footer.innerText = "Page " + item.num + " sur " + totalPages;

            // Famoahana ho sary amin'ny alalan'ny html2canvas
            const canvas = await html2canvas(item.element, { scale: 2, useCORS: true });
            const link = document.createElement("a");
            link.href = canvas.toDataURL("image/jpeg", 0.95);
            link.download = `${safeTitre} ${item.num}.jpg`;
            link.click();

            // Esory ny div rehefa vita ny export
            document.body.removeChild(item.element);
        }
    });
});

</script>


</body>
</html>
