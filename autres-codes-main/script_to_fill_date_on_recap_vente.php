
<!--------------------------------------->
<?php
//Connect to BD
include('connect.php');
//################GET ALL NO ACTIVITY#####################
   $query = "SELECT * from recap_vente INNER JOIN mvt ON recap_vente.no_activite = mvt.numero_commande_stock WHERE type_de_mvt = 'vente' GROUP BY mvt.numero_commande_stock";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    
    while ($donnees = $qc -> fetch())
    {

        //GET Description date
        $chaine = "";
        $date1 = "";
        $query_description_date = "SELECT * FROM mvt WHERE type_de_mvt = 'vente' AND numero_commande_stock = ? LIMIT 1" ;
        $qd = $bdd->prepare($query_description_date);
        $qd->execute(array($donnees['no_activite']));
        $date = $qd -> fetch();
        $chaine =  $date['description_date'].' ';
        echo $date['description_date'];
        echo "<br>";
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
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
        echo "No : ".$donnees['no_activite']." : ".$date1_mysql;
        echo "<br>";
        //Query to Change note_general and directory of recap_vente
        $query_date_modif = "UPDATE recap_vente
                SET Date_du_Journal = ?
                WHERE no_activite = ?";

        $qdm = $bdd->prepare($query_date_modif);

        $qdm->execute(array($date1_mysql,$donnees['no_activite']));  

        $qdm->closeCursor();
        } else {
            echo "No : ".$donnees['no_activite']." Date Introuvable (".$chaine.")"." Montant : ".$donnees['Montant'];
            echo "<br>";
        }

		    }
   $qc->closeCursor();
