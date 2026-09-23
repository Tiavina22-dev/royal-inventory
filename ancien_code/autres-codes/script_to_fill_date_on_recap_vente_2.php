
<!--------------------------------------->
<?php
//Connect to BD
include('connect.php');
//################GET Date NULL#####################
   $query = "SELECT * from recap_vente WHERE Date_du_Journal IS NULL";
    $qc = $bdd->prepare($query);

    $qc->execute(array());

    //Get date on string and compare
    
    while ($donnees = $qc -> fetch())
    {

        //GET Date value from Actual Journal
        $chaine = "";
        $query_description_date = "SELECT * FROM recap_vente_2 WHERE no_activite = ?" ;
        $qd = $bdd->prepare($query_description_date);
        $qd->execute(array($donnees['no_activite']));
        $date = $qd -> fetch();
        $chaine =  $date['Date_du_Journal'];
        $qd ->closeCursor();
        if (strlen($chaine)>0 ) 
            
        {
        echo "No : ".$donnees['no_activite']."---".$chaine;
        echo "<br>";
        //--------------------------------
        $query_date_modif = "UPDATE recap_vente
                SET Date_du_Journal = ?
                WHERE no_activite = ?";

        $qdm = $bdd->prepare($query_date_modif);

        $qdm->execute(array($chaine,$donnees['no_activite']));  

        $qdm->closeCursor();
        } else {
        echo "No : ".$donnees['no_activite']."--- Pas de resultat";
            echo "<br>";
        }
            
    }

	
   $qc->closeCursor();
