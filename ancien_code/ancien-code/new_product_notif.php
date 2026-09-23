<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Verify New Product Name</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/mdb.min.css">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" href="css/top20.css">
  <link rel="stylesheet" href="css/list_type4.css">
</head>
<body>
<?php include("header.php"); ?>
<br>
<br>
<br>
<br>
<br>
<!--------------------------------------->
<?php
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
//################TANTELY#####################
    //Query to get each point de vente
    $query_client = "SELECT DISTINCT(nom_client_fournisseur) FROM produit INNER JOIN mvt ON mvt.id_x = produit.id_x WHERE date_time_x  > date_sub(now(), interval 1 week)";
    $query_client = $bdd->prepare($query_client);
    $query_client->execute(array());
    //--------------------------------
    ?>
    <h2 class="top20_style">SOROHY NY FIFANGARONA</h2>
    <h2 class="top20_style">HAMARINO NY ANARANA VAOVAO</h2>
    <div class="list-type4">
    <div class="list-group4">
        <span class="puce4"><span style='font-weight: bold'><p style="margin-left: 20px">Iza ireo afaka Manamarina Ny anarana Vaovao?</p></span>
        <span class="puce4" style="margin-left: 30px"><span style="margin-left: 30px">Herman | Samuel | Joela | Laza</span></span></span>
        <span class="puce4">
          <span style='font-weight: bold'><p style="margin-left: 20px">Inona no tsy maintsy ataon'ny Mpanamarina? </p></span>
          <span class="puce4" style="margin-left: 30px"> <span style="margin-left: 30px">Manontany an'ireo Mpandefa entan'i Royal : Izay Mahay Pièce na Quincaillerie</span></span>
          <span class="puce4" style="margin-left: 30px"><span style="margin-left: 30px">Na Miantso an'i Ranto 034 49 826 06</span></span>
          <span class="puce4" style="margin-left: 30px"><span style="margin-left: 30px">Na <a href="contact.php" class="text-white" target='_blank' rel='noopener noreferrer'>Miantso</a> ny Point de Vente Nandefasana ny entana raha io entana vaovao io tokoa ny voarainy</span></span>
          <span class="puce4" style="margin-left: 30px"><span style="margin-left: 30px">Tsindrina ny bouton <img src="img/valider.png" height="30" width="30" background alt="Edit"/></span></span>
        </span>
    </div>
    </div>
    <div class="flex-center flex-column">
    <table class="table-bordered table-sm bg-info">
    <?php
    while ($donnees = $query_client -> fetch())
    {
        ?>
        <tr>
        <th class="text-center bg-dark text-light" colspan="11"><b><?php echo $donnees['nom_client_fournisseur'];?></b></th>
        </tr>
        <tr>
        <th class="text-center bg-danger text-light"></th>
        <th class="text-center bg-danger text-light"><b>NEW PRODUCT NAME</b></th>
        <th class="text-center bg-danger text-light"><b>REF</b></th>
        <th class="text-center bg-danger text-light"><b>JOURNAL</b></th>
        <th class="text-center bg-danger text-light"><b>QT</b></th>
        <th class="text-center bg-danger text-light"><b>PU</b></th>
        <th class="text-center bg-danger text-light"><b>CREATED DATE</b></th>
        <th class="text-center bg-danger text-light"><b>CREATED BY</b></th>
        <th class="text-center bg-danger text-light"><b>USED BY</b></th>
        <th class="text-center bg-danger text-light"><b>PERMITED BY</b></th>
        <th class="text-center bg-danger text-light"></th>
        </tr>
        <tbody>
    <?php
         //QUERY to get product for each by QT PV
         $query_new_x = "SELECT *,date_time_x,reference_x,UPPER(user_x) as user_x,UPPER(user_mvt) as user_mvt FROM produit INNER JOIN mvt ON mvt.id_x = produit.id_x WHERE nom_client_fournisseur = ? AND date_time_x  > date_sub(now(), interval 1 week) GROUP BY reference_x";
        $query_new_x = $bdd->prepare($query_new_x);
        $query_new_x->execute(array($donnees['nom_client_fournisseur']));
        ?>
            <?php
            $no = 0;
                while ($data = $query_new_x -> fetch())
                {
                    //$state_suppr = "pointer-events: none";
                    $state_suppr = "";
                    $user_check = (strstr( $data['checking'], $username ) ? "Yes" : "No" );
                    //-----------HANDLE BD COLOR
                    $bg = '';
                    $color = '';
                    $color_badge ='';
                    //if ($user_check == "No") {}
                    if (strlen($data['checking']) == 0) {
                    	$color = 'text-danger';
                    	$color_badge ='w3-pink';
                    	$bg = 'bg-warning';
                    }
                    $no = $no + 1;
            ?>
            <tr>
                <td class="<?php echo $bg.' '.$color;?>"><span class="w3-badge w3-margin-right <?php echo $color_badge;?>"><?php echo $no;?></span></td>
                <td class="<?php echo $bg.' '.$color;?>"><?php echo $data['nom_x'];?></td>
                <td class="<?php echo $bg;?>"><?php echo $data['reference_x'];?></td>
                <td class="<?php echo $bg;?>"><?php echo $data['description_date'];?></td>
                <td class="<?php echo $bg;?>"><?php echo ABS($data['qt']);?></td>
                <td class="<?php echo $bg;?>"><?php echo $data['prix_unitaire'];?></td>
                <td class="<?php echo $bg;?>"><?php echo $data['date_time_x'];?></td>
                <td class="<?php echo $bg;?>"><span style='font-weight: bold' class="text-danger"><?php echo $data['user_x'];?></span></td>
                <td class="<?php echo $bg;?>"><?php echo $data['user_mvt'];?></td>
                <td class="<?php echo $bg;?>"><?php echo $data['checking'];?></td>
                <td class="<?php echo $bg;?>">
                    <?php
                    if ($user_check == "Yes") {
                        echo "OK";
                    }else{
                        if ($username != "NOT_PERMIT") 
                        {
                        
                    ?>
                    <a style="<?php echo $state_suppr; ?>;" class="center" title='ACCEPTER?' href="valider_nouveau_nom.php?id_x=<?php echo $data['id_x']; ?>" onclick="confirmationDelete('Vous-avez accepter?');return false; post ;"><img src="img/valider.png" height="30" width="30" background alt="Edit"/></a>
                    <?php
                        }
                    }
                    ?>
                </td>
            <?php
                } //CLOSE OF WHILE
                $query_new_x->closeCursor();
            ?>
            </tr>
        </tbody>
    <?php
    }
   $query_client->closeCursor();;
?>
        </table>
        </div>
<br>
<br>
<br>
<br>
<br>
<br>
  <!-- jQuery -->
  <!-- Bootstrap tooltips -->
  <!-- Bootstrap core JavaScript -->
  <!-- MDB core JavaScript -->
  <!-- Your custom scripts (optional) -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/confirmation.js"></script>
<?php include("footer.php"); ?>
</body>
</html>