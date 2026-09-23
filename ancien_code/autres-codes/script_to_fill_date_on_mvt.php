
<!--------------------------------------->
<?php
//Connect to BD
include('connect.php');
//################GET ALL NO ACTIVITY#####################
/*
   $query = "UPDATE mvt SET description_date = 'Inventaire/Ajout du 30/10/20' WHERE numero_commande_stock = 107 AND type_de_mvt ='stock'";
    $qc = $bdd->prepare($query);

    $qc->execute(array());
     $qc->closeCursor();
*/
//################GET ALL NO ACTIVITY#####################
   $query = "SELECT * FROM mvt WHERE Date_du_Journal_mvt is null";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    echo "---------------STARTING---------------------";
    echo "<br>";
    while ($donnees = $qc -> fetch())
    {

        $description_date = $donnees['description_date'];
        if ($description_date == 'balance_negative' || $description_date=='balance_zero') {
            $description_date = $donnees['date_time'];
            $description_date = DateTime::createFromFormat('Y-m-d H:i:s', $description_date);
            $description_date = $description_date -> format('d-m-y');
        }
        echo "id mvt".$donnees['id_mvt'];
        echo "Description date --- ".$description_date;
        echo "---";
        echo $donnees['Date_du_Journal_mvt'];
        echo "<br>";
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $description_date, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $date1 = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $date1 = str_replace('- ', '-', $date1);
        $date1 = str_replace('/ ', '-', $date1);
        $date1 = str_replace(' / ', '-', $date1);
        $date1 = str_replace(' -', '-', $date1);
        $date1 = str_replace(' - ', '-', $date1);
        $date1 = str_replace('/', '-', $date1);
        
        //echo $date1;
        //Convert date to english format for compare
        $date1_mysql = DateTime::createFromFormat('d-m-y', $date1);
        $date1_mysql = $date1_mysql -> format('Y-m-d');
        //Get date list with name
        echo "No : ".$donnees['numero_commande_stock']." : ".$date1_mysql;
        echo " -- ADDED <br>";
        echo "-------------------------------<br>";
        //Query to Change note_general and directory of recap_vente
        $query_date_modif = "UPDATE mvt
                SET Date_du_Journal_mvt = ?
                WHERE id_mvt = ?";

        $qdm = $bdd->prepare($query_date_modif);

        $qdm->execute(array($date1_mysql,$donnees['id_mvt']));  

        $qdm->closeCursor();
        } else {
            echo "id mvt : ".$donnees['id_mvt']." Date Introuvable (".$description_date.")";
            echo "<br>";
        }

		    }
   $qc->closeCursor();
   echo "-------------------END------------------------";
   ?>
