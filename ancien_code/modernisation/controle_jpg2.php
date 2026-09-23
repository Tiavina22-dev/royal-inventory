<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<title>Rectification Stock</title>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/html2canvas.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">

<style>

table{
border-collapse:collapse;
width:100%;
}

table,th,td{
border:1px solid black;
}

th,td{
padding:4px;
text-align:left;
}

#tab{
font-family:Arial;
font-size:20px;
}

#tab th{
font-size:22px;
font-weight:bold;
color:darkblue;
}

#tab td{
font-size:18px;
font-weight:bold;
}

</style>

</head>

<body>

<?php

include("header.php");
include("footer.php");
include("connect.php");


/* USER */

$username="default";

if(isset($_SESSION['User_Name'])){
$username=$_SESSION['User_Name'];
}

if(isset($_COOKIE['other_user'])){
$username=$_COOKIE['other_user'];
}


/* MESSAGE */

$vers_controle="";

if(isset($_COOKIE['msg_validation'])){

$vers_controle="Afficher Rectification et Controle?";

?>

<script>
new Audio('sound/done.mp3').play();
</script>

<?php
}


/* CLIENT INFO */

$client_name="";
$description_date="";

$query=$bdd->prepare("

SELECT nom_du_client,description_date
FROM stock_prep
WHERE description_date LIKE '%Rectifier%'
AND user_stock_prep=?

");

$query->execute(array($username));

while($row=$query->fetch()){

$client_name=$row['nom_du_client'];
$description_date=$row['description_date'];

}

$query->closeCursor();


/* LISTE PRODUITS */

$sql="

SELECT
reference_x,
nom_x,
qt,
stock_prep.prix_de_vente AS prix_unitaire,
note

FROM stock_prep

INNER JOIN produit
ON stock_prep.id_x=produit.id_x

WHERE description_date LIKE '%Rectifier%'
AND user_stock_prep=?

ORDER BY nom_x ASC

";

if(!isset($_COOKIE['limit'])){
$sql.=" LIMIT 5000";
}

$q=$bdd->prepare($sql);
$q->execute(array($username));

$total_line=$q->rowCount();


?>

<h4 class="text-center text-primary">
<a href="latest_controle.php"><?php echo $vers_controle; ?></a>
</h4>


<br>
<br>
<br>
<br>

<div class="text-center">

<a href="controle_x.php">
<button class="btn btn-success">Retour</button>
</a>

<button class="btn btn-primary" id="exportBtn">
Sary
</button>

</div>

<br>


<?php

if($total_line==0){

echo "<div class='text-center'><b>PAS DE TRAITEMENT EN COURS</b></div>";

}else{


/* CHECK NOTE */

$notes_exist=false;

$q_note=$bdd->prepare("

SELECT note
FROM stock_prep
WHERE description_date LIKE '%Rectifier%'
AND user_stock_prep=?

");

$q_note->execute(array($username));

while($n=$q_note->fetch()){

if(!empty(trim($n['note']))){
$notes_exist=true;
break;
}

}

$q_note->closeCursor();

?>

<div id="container" class="container">

<br>

<h4 id="titre-tableau"
class="text-center"
style="font-family:Arial;font-weight:bold;font-size:20px">

<?php echo strtoupper($client_name)." || ".$description_date; ?>

</h4>

<br>

<table id="tab" class="table table-bordered">

<thead>

<tr>
<th class="text-center">No</th>
<th class="text-center">Ref</th>
<th class="text-center">Designation</th>
<th class="text-center">QT</th>
<th class="text-center">PU</th>

<?php if($notes_exist){ ?>
<th class="text-center">NOTE</th>
<?php } ?>

</tr>

</thead>


<tbody>

<?php
$i = 1;
while($row=$q->fetch()){

?>

<tr>
<td class="text-center"><?= $i++ ?></td>
<td><?php echo $row['reference_x']; ?></td>

<td><?php echo htmlspecialchars($row['nom_x']); ?></td>

<td class="text-right">
<?php echo number_format($row['qt']); ?>
</td>

<td class="text-right">
<?php echo number_format($row['prix_unitaire']); ?>
</td>

<?php if($notes_exist){ ?>

<td>

<?php
if(!empty(trim($row['note']))){
echo htmlspecialchars($row['note']);
}
?>

</td>

<?php } ?>

</tr>

<?php
}
?>

</tbody>

</table>

<br><br><br><br>

</div>

<?php
}
?>

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