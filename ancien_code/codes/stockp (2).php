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
$img_path = "img/default_img/default_pdp.png";
//Connect to BD
	include('connect.php');
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
}
else 
{
  //default pdp
  $username = "default";
}
	//Read cookie for validation msg
 
 $annuler = "";
 $numero_commande="";
 $point_de_vente_cookie = "";
 $msg_ok = "";
 $msg_nok = "";
 if (isset($_COOKIE['msg_ok'])) 
{
   $msg_ok=$_COOKIE['msg_ok'];
 }
  if (isset($_COOKIE['msg_nok'])) 
{
   $msg_nok=$_COOKIE['msg_nok'];
 }


 if (isset($_COOKIE['numero_commande'])) 
{
   $numero_commande=$_COOKIE['numero_commande'];
   $annuler = "Annuler?";
 }
  if (isset($_COOKIE['point_de_vente_cookie'])) 
{
   $point_de_vente_cookie=$_COOKIE['point_de_vente_cookie'];
 }
$vers_controle ='';
if (isset($_COOKIE['msg_validation'])) 
{
   $point_de_vente_cookie=$_COOKIE['msg_validation'];
   $vers_controle = 'Controler stock?';
 	//----PLAY SOUND-------------------------
	?>
   <script type="text/javascript">
	var audio = new Audio('sound/done.mp3').play();
	</script>
	<?php
	//----PLAY SOUND-------------------------
 }

?>
<h4 class="text-center text-primary"><a href="stock_recap.php"> <?php echo $vers_controle; ?></a></h4>
<?php

	//QUERY DESTINY FOR AUTO DETECT DUPLICATE
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
	//Query to liste All Waiting stock

if (isset($_COOKIE['limit'])) 
{
   $query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC";
 }else{
$limit = 200;
$query_ps = "SELECT img_path_x,id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,produit.id_x as id_x,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC LIMIT $limit";
}
	$query_stock_prep_list = $bdd->prepare($query_ps);

	$query_stock_prep_list->execute(array($username));	

 

$query_stock_prep = "SELECT id_stock_prep,nom_du_client,nom_x,qt,stock_prep.prix_de_vente as prix_unitaire,reference_x, (stock_prep.prix_de_vente)*qt as sous_total,note,prix_client FROM stock_prep INNER JOIN produit ON stock_prep.id_x = produit.id_x WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ORDER BY id_stock_prep DESC;";
$query_stock_prep = $bdd->prepare($query_stock_prep);

$query_stock_prep->execute(array($username));
//get row count
$total_line=$query_stock_prep->rowCount ();
//echo $total_line;
//Read cookie to resolve new client name
$time_for_nouveau_stock_cookie = 0;
if (isset($_COOKIE['time_for_nouveau_stock_cookie'])) 
{
   $time_for_nouveau_stock_cookie=$_COOKIE['time_for_nouveau_stock_cookie'];
 }
//if waiting commande exist set value of $waiting_commande to 1
 	$waiting_stock = 0 ;
 if ($total_line!=0)
 {
 	$waiting_stock = 1 ;
 }
 //Calculate cookie and row count commande (1 OR 1 = 1 | 1 OR 0 = 1 | 0 OR 0 = 0)
 //$time_for_nouveau_commande_cookie = 0;
 //$waiting_commande = 0;
 $decision_new_stock =  $time_for_nouveau_stock_cookie || $waiting_stock;
 //echo "time_for_nouveau_command".$time_for_nouveau_stock_cookie."waiting_commande".$waiting_stock." = ".$decision_new_stock;
 if ($username == 'STANDARD') {
 	$decision_new_stock = 1;
 } 
 
 if ($decision_new_stock == 0) {
 ?>

 <?php
 } else {
 ?>
<!------------SIMPLE SEARCH---------------->
<?php 
	if ($username == 'STANDARD') {
		#VIDE
	}
	else 
	{
$key_word = "";
if (isset($_COOKIE['key_word'])) 
{$key_word = $_COOKIE['key_word'];}
##########################################################
$client_name = "";
		$query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ?");
        $query_client_name->execute(array($username));
while ($donnees = $query_client_name->fetch())
         {
        $num_stock = $donnees['numero_stock_prep'];
         $client_name = $donnees['nom_du_client']."";
         $description_date = $donnees['description_date'];
          }
$query_client_name->closeCursor();

//HANDLE EMPTY CLIENT NAME AT STARING SELECT EMPTY STOCK_PREP
if (strlen($client_name) == 0)
##############################################################
		//$description_date = "xxx";
?>
<br>
<br>
<div class="text-center"><a href="stock.php"><button class="btn btn-success">Retours</button></a><a href="stock.php"><button class="btn btn-danger">Modifier</button></a><button class="btn btn-primary" id="exportBtn">Sary</button>
</div>
<?php
	} //close else
// Maka ny vokatra rehetra indray mandeha
$query_stock_prep_list->execute(array($username));
$rows = $query_stock_prep_list->fetchAll(PDO::FETCH_ASSOC);

// Jereo raha misy note tsy banga
$notes_exist = false;
foreach ($rows as $donnees) {
    if (!empty(trim($donnees['note']))) {
        $notes_exist = true;
        break;
    }
}
?>
		<div id="container" class="container">
		<br>
		<h4  id="titre-tableau" class="text-center" style="font-family: Arial ; font-weight: bold; font-size: 25px;"><?php echo ($client_name." | ".$description_date); ?></h4>
		<br>
		<table id="tab" class="table table-bordered dt-responsive nowrap">
        <thead>
        <tr class="text-center">
        <th class="text-center">Ref</th>
        <th class="text-center">Designation</th>
        <th class="text-center">QT</th>
        <?php if ($client_name =='MoraranoCh' ) { ?>
          <th class="text-center">AMAROTANA</th>
          <?php
                } else {  ?>
        <th class="text-center">PU</th>
        <th class="text-center">AMAROTANA</th>
        <?php } ?>
            <?php if ($notes_exist) { ?>
                <th>NOTE</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $donnees) { ?>
            <tr>
                <td class="text-center"><?php echo $donnees['reference_x']; ?></td>
                <td><?php echo $donnees['nom_x']; ?></td>
                <td class="text-center"><?php echo $donnees['qt']; ?></td>
                <td class="text-right"><?php echo number_format($donnees['prix_unitaire'], 0, "", " "); ?></td>
                <?php if ($client_name =='MoraranoCh' ) {
                } else {  ?>
                <td class="text-right"><?php echo number_format($donnees['prix_client'], 0, "", " "); ?></td>
                <?php } ?>
                <?php if ($notes_exist) { ?>
                    <td><?php echo !empty(trim($donnees['note'])) ? $donnees['note'] : ""; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody> 
</table>
<br>
<br>
<br>
<br>
</div>


<?php
 }
 ?>
<!-------------------------------------->
	 
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