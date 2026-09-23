<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/style_invoice.css">
        <title>Print</title>
    </head>
    <body class="centered">
        <?php
        //Get current username
        session_start();
        if (isset($_SESSION['User_Name'])) 
        {
          $username = $_SESSION['User_Name'];
        }
        else 
        {
          //default pdp
          $username = "default";
        }
        //Connect to BD
        include('connect.php');
        //GET CLIENT CODE AND REF PU
        if (isset($_POST['client_code'])) {
        $client_code = $_POST['client_code'];
        }

        if (isset($_POST['reference_pu'])) {
        $reference_pu = $_POST['reference_pu'];
        }
        //SELECTED ID------------------------------------------
        if (isset($_POST['vente_id_array']))
            {
                if ($_POST['vente_id_array'] != 0) {
                    # code...

                $ids=explode(',',$_POST['vente_id_array']);
                //echo $ids[0];

                    // From this string ($timestamp) can we print
                    date_default_timezone_set("Etc/GMT-2");
                    $timestamp = strftime("%Y/%m/%d %H:%M:%S %Y");

                    ?>
            <div class="ticket">
            <img src="img/logo.png" alt="Logo">
            <p class="centered"><b><?php echo (strftime("%d/%m/%Y %H:%M:%S", strtotime($timestamp)).' | no° '.$client_code.' | '.$reference_pu); ?></b></p>
            <table>
                <thead>
                    <tr>
                        <th class="description">Description</th>
                        <th class="quantity">Q</th>
                        <th class="price">MT</th>
                    </tr>
                </thead>
                <tbody>
                 <?php 
                 $total_waiting = 0;
                 $no = 0;
                    foreach ($ids as $id) 
                {
                    $no = $no +1;
                    //Query to liste SELECTED ID Waiting command
                    $query_commande_list = $bdd->prepare("SELECT id_commande,nom_du_client,nom_x,reference_x,note_commande,state,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? AND commande.state LIKE 'preparing' AND id_commande LIKE ?;");
                    $query_commande_list->execute(array($username,$id));
                    $donnees = $query_commande_list -> fetch();
                    $total_waiting = $donnees['sous_total']+$total_waiting;
                    //Handle Product xx Name
                    $product_name = $donnees['nom_x'];
                    if ($donnees['reference_x'] == 'XXXX') {
                        $product_name = $donnees['note_commande'];
                    }
                ?>
                    <tr>
                        <td class="description"><?php echo $no.'- '.$product_name; ?></td>
                        <td class="quantity"><?php echo $donnees['qt']; ?></td>
                        <td class="price"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
                    </tr>
                <?php
                $query_commande_list->closeCursor();
                //echo $id;
                }//END FOREACH
                ?>
                    <tr>
                        <td class="total" colspan="2"><b>TOTAL</b></td>
                        <td class="price"><b><?php echo number_format($total_waiting,0, "", " "); ?></b></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered"><b>Misaotra Tompoko!</b></p>
        <div class="centered">
            <button id="btnPrint" class="hidden-print">IMPRESSION</button>          <a href="commande_royal.php"><button class="hidden-print">RECTIFIER</button></a>
        <form role="form" action="valider_waiting_selected_commande_royal.php" method="post">
                <button type="submit" class="hidden-print">TERMINER</button>
                <input type="hidden" name="id_selected" value="<?php echo $_POST['vente_id_array']; ?>">
                
        </form>
    </div>
        </div>
            <?php
                    
                    //$sql = "DELETE FROM user WHERE Id_User=?";

                    //$q = $bdd->prepare($sql);

                    //$q->execute(array($id));
                    //$q->closeCursor();
            }// !=0
            else{
        //-----------------------------------------------------

        //Query to liste All Waiting command
        $query_commande_list = $bdd->prepare("SELECT id_commande,nom_du_client,nom_x,reference_x,note_commande,state,qt,commande.prix_de_vente as prix_unitaire,prix_aparafa, (commande.prix_de_vente)*qt as sous_total, prix_aparafa*qt as sous_total_aparafa,produit.prix_de_vente as PO,commande.id_x as id_x FROM commande INNER JOIN produit ON commande.id_x = produit.id_x WHERE commande.user LIKE ? AND commande.state LIKE 'preparing' ORDER BY id_commande DESC;");
        $query_commande_list->execute(array($username));
        //get row count
        // From this string ($timestamp) can we print
        date_default_timezone_set("Etc/GMT-2");
        $timestamp = strftime("%Y/%m/%d %H:%M:%S %Y");

        //echo strftime("%d-%m-%Y %H:%M:%S", strtotime($timestamp))."<br/>";
        ?>
        <div class="ticket">
            <img src="img/logo.png" alt="Logo">
            <p class="centered"><b><?php echo (strftime("%d/%m/%Y %H:%M:%S", strtotime($timestamp)).' | no° '.$client_code.' | '.$reference_pu); ?></b></p>
            <table>
                <thead>
                    <tr>
                        <th class="description">Description</th>
                        <th class="quantity">Q</th>
                        <th class="price">MT</th>
                    </tr>
                </thead>
                <tbody>
                 <?php  
                    $total_waiting = 0;
                    $no = 0;
                    while ($donnees = $query_commande_list -> fetch())
                    {
                        $no = $no +1;
                    $total_waiting = $donnees['sous_total']+$total_waiting;
                    //Handle Product xx Name
                    $product_name = $donnees['nom_x'];
                    if ($donnees['reference_x'] == 'XXXX') {
                        $product_name = $donnees['note_commande'];
                    }
                ?>
                    <tr>
                        <td class="description"><?php echo $no.'- '.$product_name; ?></td>
                        <td class="quantity"><?php echo $donnees['qt']; ?></td>
                        <td class="price"><?php echo number_format($donnees['sous_total'],0, "", " "); ?></td>
                    </tr>
                <?php
                    }
                $query_commande_list->closeCursor();
                ?>
                    <tr>
                        <td class="total" colspan="2"><b>TOTAL</b></td>
                        <td class="price"><b><?php echo number_format($total_waiting,0, "", " "); ?></b></td>
                    </tr>
                </tbody>
            </table>
            <p class="centered"><b>Misaotra Tompoko!</b></p>
        <div class="centered">
        <button id="btnPrint" class="hidden-print">IMPRESSION</button>          <a href="commande_royal.php"><button class="hidden-print">RECTIFIER</button></a>          <a href="valider_waiting_commande_royal.php"><button class="hidden-print">TERMINER</button></a>
        </div>
        </div>
        <?php
                    }//END ELSE
        }//END ISSET
        ?>
        <script src="js/script_invoice.js"></script>
    </body>
</html>