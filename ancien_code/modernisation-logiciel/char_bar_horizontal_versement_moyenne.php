<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Material Design for Bootstrap</title>
 
  <!-- Font Awesome -->
  
  <!-- Bootstrap core CSS -->

  <!-- Material Design Bootstrap -->
  
  <!-- Your custom styles (optional) -->
  
</head>
<body>
<?php
include('connect.php');
//versement moyenne du semaine Ambato tantely##############
$query_moyenne_ambato_tantely = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Tantely' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne_ambato_tantely);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION DU LISTE
  $AT[0] = 0;
  $AT[1] = 0;
  $AT[2] = 0;
  $AT[3] = 0;
  $AT[4] = 0;
  $AT[5] = 0;
  $AT[6] = 0;

  $AT_date[0] = "";
  $AT_date[1] = "";
  $AT_date[2] = "";
  $AT_date[3] = "";
  $AT_date[4] = "";
  $AT_date[5] = "";
  $AT_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT TANTELY
  $AT[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $AT_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $AT_date[$i] = str_replace('- ', '/', $AT_date[$i]);
        $AT_date[$i] = str_replace('/ ', '/', $AT_date[$i]);
        $AT_date[$i] = str_replace(' / ', '/', $AT_date[$i]);
        $AT_date[$i] = str_replace(' -', '/', $AT_date[$i]);
        $AT_date[$i] = str_replace(' - ', '/', $AT_date[$i]);
        $AT_date[$i] = str_replace('-', '/', $AT_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_tantely = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX TANTELY
$query_max_montant_vente_ambato_tantely = "SELECT DISTINCT numero_commande_stock,MAX(Montant) as max FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Tantely' AND type_de_mvt = 'vente'" ;
  $q = $bdd->prepare($query_max_montant_vente_ambato_tantely);
  $q->execute(array());
  //initialisation
  $max_tantely = 0;
  $donnees = $q -> fetch();
  $max_tantely = $donnees['max'];
  $q->closeCursor();
  //echo $max;
  //versement moyenne du semaine Ambato Pneu##############
$query_moyenne_ambato_pneu = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Pneu' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne_ambato_pneu);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION DU LISTE
  $AP[0] = 0;
  $AP[1] = 0;
  $AP[2] = 0;
  $AP[3] = 0;
  $AP[4] = 0;
  $AP[5] = 0;
  $AP[6] = 0;

  $AP_date[0] = "";
  $AP_date[1] = "";
  $AP_date[2] = "";
  $AP_date[3] = "";
  $AP_date[4] = "";
  $AP_date[5] = "";
  $AP_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT TAHINA PNEU
  $AP[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $AP_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $AP_date[$i] = str_replace('- ', '/', $AP_date[$i]);
        $AP_date[$i] = str_replace('/ ', '/', $AP_date[$i]);
        $AP_date[$i] = str_replace(' / ', '/', $AP_date[$i]);
        $AP_date[$i] = str_replace(' -', '/', $AP_date[$i]);
        $AP_date[$i] = str_replace(' - ', '/', $AP_date[$i]);
        $AP_date[$i] = str_replace('-', '/', $AP_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_pneu = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX TAHINA AMBATO
$query_max_montant_vente_ambato_pneu = "SELECT DISTINCT numero_commande_stock,MAX(Montant) as max FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_Pneu' AND type_de_mvt = 'vente'" ;
  $q = $bdd->prepare($query_max_montant_vente_ambato_pneu);
  $q->execute(array());
  //initialisation
  $max_pneu = 0;
  $donnees = $q -> fetch();
  $max_pneu = $donnees['max'];
  $q->closeCursor();
  //echo $max;
  //versement moyenne du semaine ELECTRONIQUE ##############
$query_moyenne_Ambaibo_Electronique = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Electronique' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne_Ambaibo_Electronique);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION DU LISTE
  $AE[0] = 0;
  $AE[1] = 0;
  $AE[2] = 0;
  $AE[3] = 0;
  $AE[4] = 0;
  $AE[5] = 0;
  $AE[6] = 0;

  $AE_date[0] = "";
  $AE_date[1] = "";
  $AE_date[2] = "";
  $AE_date[3] = "";
  $AE_date[4] = "";
  $AE_date[5] = "";
  $AE_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT ELECTRONIQUE
  $AE[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $AE_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $AE_date[$i] = str_replace('- ', '/', $AE_date[$i]);
        $AE_date[$i] = str_replace('/ ', '/', $AE_date[$i]);
        $AE_date[$i] = str_replace(' / ', '/', $AE_date[$i]);
        $AE_date[$i] = str_replace(' -', '/', $AE_date[$i]);
        $AE_date[$i] = str_replace(' - ', '/', $AE_date[$i]);
        $AE_date[$i] = str_replace('-', '/', $AE_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_elec = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX ELECTRONIQUE
$query_max_montant_vente_Ambaibo_Electronique = "SELECT DISTINCT numero_commande_stock,MAX(Montant) as max FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Electronique' AND type_de_mvt = 'vente'" ;
  $q = $bdd->prepare($query_max_montant_vente_Ambaibo_Electronique);
  $q->execute(array());
  //initialisation
  $max_elec = 0;
  $donnees = $q -> fetch();
  $max_elec = $donnees['max'];
  $q->closeCursor();
  //echo $max;
  //versement moyenne du semaine SOALAZAINA ##############
$query_moyenne_Ambaiboho = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Tsena' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne_Ambaiboho);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION DU LISTE
  $SR[0] = 0;
  $SR[1] = 0;
  $SR[2] = 0;
  $SR[3] = 0;
  $SR[4] = 0;
  $SR[5] = 0;
  $SR[6] = 0;

  $SR_date[0] = "";
  $SR_date[1] = "";
  $SR_date[2] = "";
  $SR_date[3] = "";
  $SR_date[4] = "";
  $SR_date[5] = "";
  $SR_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT SOALAZAINA
  $SR[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $SR_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $SR_date[$i] = str_replace('- ', '/', $SR_date[$i]);
        $SR_date[$i] = str_replace('/ ', '/', $SR_date[$i]);
        $SR_date[$i] = str_replace(' / ', '/', $SR_date[$i]);
        $SR_date[$i] = str_replace(' -', '/', $SR_date[$i]);
        $SR_date[$i] = str_replace(' - ', '/', $SR_date[$i]);
        $SR_date[$i] = str_replace('-', '/', $SR_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_ambaiboho = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX SOALAZAINA
$query_max_montant_vente_Ambaiboho = "SELECT DISTINCT numero_commande_stock,MAX(Montant) as max FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Tsena' AND type_de_mvt = 'vente'" ;
  $q = $bdd->prepare($query_max_montant_vente_Ambaiboho);
  $q->execute(array());
  //initialisation
  $max_Ambaiboho = 0;
  $donnees = $q -> fetch();
  $max_Ambaiboho = $donnees['max'];
  $q->closeCursor();
  //echo $max;
  //versement moyenne du semaine Amparafa##################
$query_moyenne = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Amparafa' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION
  $AF[0] = 0;
  $AF[1] = 0;
  $AF[2] = 0;
  $AF[3] = 0;
  $AF[4] = 0;
  $AF[5] = 0;
  $AF[6] = 0;

  $AF_date[0] = "";
  $AF_date[1] = "";
  $AF_date[2] = "";
  $AF_date[3] = "";
  $AF_date[4] = "";
  $AF_date[5] = "";
  $AF_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT AMPARAFA
  $AF[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $AF_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $AF_date[$i] = str_replace('- ', '/', $AF_date[$i]);
        $AF_date[$i] = str_replace('/ ', '/', $AF_date[$i]);
        $AF_date[$i] = str_replace(' / ', '/', $AF_date[$i]);
        $AF_date[$i] = str_replace(' -', '/', $AF_date[$i]);
        $AF_date[$i] = str_replace(' - ', '/', $AF_date[$i]);
        $AF_date[$i] = str_replace('-', '/', $AF_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_amparafa = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX AMPARAFA
$query_max_montant_vente = "SELECT MAX(Montant) as max FROM recap_vente INNER JOIN (SELECT DISTINCT numero_commande_stock,nom_client_fournisseur,type_de_mvt FROM mvt WHERE nom_client_fournisseur = 'Amparafa' AND type_de_mvt = 'vente') new ON no_activite = numero_commande_stock;" ;
  $q = $bdd->prepare($query_max_montant_vente);
  $q->execute(array());
  //initialisation
  $max_amparafa = 0;
  $donnees = $q -> fetch();
  $max_amparafa = $donnees['max'];
  $q->closeCursor();
  //echo $max;
   //versement moyenne du semaine Bejofo##################
$query_moyenne = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Bejofo' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION
  $BJ[0] = 0;
  $BJ[1] = 0;
  $BJ[2] = 0;
  $BJ[3] = 0;
  $BJ[4] = 0;
  $BJ[5] = 0;
  $BJ[6] = 0;

  $BJ_date[0] = "";
  $BJ_date[1] = "";
  $BJ_date[2] = "";
  $BJ_date[3] = "";
  $BJ_date[4] = "";
  $BJ_date[5] = "";
  $BJ_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT BEJOFO
  $BJ[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $BJ_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $BJ_date[$i] = str_replace('- ', '/', $BJ_date[$i]);
        $BJ_date[$i] = str_replace('/ ', '/', $BJ_date[$i]);
        $BJ_date[$i] = str_replace(' / ', '/', $BJ_date[$i]);
        $BJ_date[$i] = str_replace(' -', '/', $BJ_date[$i]);
        $BJ_date[$i] = str_replace(' - ', '/', $BJ_date[$i]);
        $BJ_date[$i] = str_replace('-', '/', $BJ_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_bejofo = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX BEJOFO
$query_max_montant_vente = "SELECT MAX(Montant) as max FROM recap_vente INNER JOIN (SELECT DISTINCT numero_commande_stock,nom_client_fournisseur,type_de_mvt FROM mvt WHERE nom_client_fournisseur = 'Bejofo' AND type_de_mvt = 'vente') new ON no_activite = numero_commande_stock;" ;
  $q = $bdd->prepare($query_max_montant_vente);
  $q->execute(array());
  //initialisation
  $max_bejofo = 0;
  $donnees = $q -> fetch();
  $max_bejofo = $donnees['max'];
  $q->closeCursor();
  //echo $max;
  //versement moyenne du semaine Morarano##############
$query_moyenne = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%rano%' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  
  //initialisation
  $total = 0;
  //INITIALISATION
  $MO[0] = 0;
  $MO[1] = 0;
  $MO[2] = 0;
  $MO[3] = 0;
  $MO[4] = 0;
  $MO[5] = 0;
  $MO[6] = 0;

  $MO_date[0] = "";
  $MO_date[1] = "";
  $MO_date[2] = "";
  $MO_date[3] = "";
  $MO_date[4] = "";
  $MO_date[5] = "";
  $MO_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MORARANO
  $MO[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $MO_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $MO_date[$i] = str_replace('- ', '/', $MO_date[$i]);
        $MO_date[$i] = str_replace('/ ', '/', $MO_date[$i]);
        $MO_date[$i] = str_replace(' / ', '/', $MO_date[$i]);
        $MO_date[$i] = str_replace(' -', '/', $MO_date[$i]);
        $MO_date[$i] = str_replace(' - ', '/', $MO_date[$i]);
        $MO_date[$i] = str_replace('-', '/', $MO_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_morarano = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX MORARANO
$query_max_montant_vente = "SELECT MAX(Montant) as max FROM recap_vente INNER JOIN (SELECT DISTINCT numero_commande_stock,nom_client_fournisseur,type_de_mvt,description_date FROM mvt WHERE nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%rano%' AND type_de_mvt = 'vente') new ON no_activite = numero_commande_stock;" ;
  $q = $bdd->prepare($query_max_montant_vente);
  $q->execute(array());
  //initialisation
  $max_morarano = 0;
  $donnees = $q -> fetch();
  $max_morarano = $donnees['max'];
  $q->closeCursor();
    //versement moyenne du semaine ANDREFANA##############
$query_moyenne = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%fana%' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION
  $ANDREFANA[0] = 0;
  $ANDREFANA[1] = 0;
  $ANDREFANA[2] = 0;
  $ANDREFANA[3] = 0;
  $ANDREFANA[4] = 0;
  $ANDREFANA[5] = 0;
  $ANDREFANA[6] = 0;

  $ANDREFANA_date[0] = "";
  $ANDREFANA_date[1] = "";
  $ANDREFANA_date[2] = "";
  $ANDREFANA_date[3] = "";
  $ANDREFANA_date[4] = "";
  $ANDREFANA_date[5] = "";
  $ANDREFANA_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT ANDREFANA
  $ANDREFANA[$i]=$donnees['Montant'];
  //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $ANDREFANA_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $ANDREFANA_date[$i] = str_replace('- ', '/', $ANDREFANA_date[$i]);
        $ANDREFANA_date[$i] = str_replace('/ ', '/', $ANDREFANA_date[$i]);
        $ANDREFANA_date[$i] = str_replace(' / ', '/', $ANDREFANA_date[$i]);
        $ANDREFANA_date[$i] = str_replace(' -', '/', $ANDREFANA_date[$i]);
        $ANDREFANA_date[$i] = str_replace(' - ', '/', $ANDREFANA_date[$i]);
        $ANDREFANA_date[$i] = str_replace('-', '/', $ANDREFANA_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_andrefana = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX ANDREFANA
$query_max_montant_vente = "SELECT MAX(Montant) as max FROM recap_vente INNER JOIN (SELECT DISTINCT numero_commande_stock,nom_client_fournisseur,type_de_mvt,description_date FROM mvt WHERE nom_client_fournisseur = 'Ambaibo_Tole' AND description_date LIKE '%fana%' AND type_de_mvt = 'vente') new ON no_activite = numero_commande_stock;" ;
  $q = $bdd->prepare($query_max_montant_vente);
  $q->execute(array());
  //initialisation
  $max_andrefana = 0;
  $donnees = $q -> fetch();
  $max_andrefana = $donnees['max'];
  $q->closeCursor();
  //versement moyenne du semaine TOLE##############
$query_moyenne = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambaibo_Tole' AND description_date NOT LIKE '%rano%' AND description_date NOT LIKE '%fana%' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION
  $ATOLE[0] = 0;
  $ATOLE[1] = 0;
  $ATOLE[2] = 0;
  $ATOLE[3] = 0;
  $ATOLE[4] = 0;
  $ATOLE[5] = 0;
  $ATOLE[6] = 0;

  $ATOLE_date[0] = "";
  $ATOLE_date[1] = "";
  $ATOLE_date[2] = "";
  $ATOLE_date[3] = "";
  $ATOLE_date[4] = "";
  $ATOLE_date[5] = "";
  $ATOLE_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT AMBAIBO TOLE
  $ATOLE[$i]=$donnees['Montant'];
    //GET Date on a string-------------
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $ATOLE_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $ATOLE_date[$i] = str_replace('- ', '/', $ATOLE_date[$i]);
        $ATOLE_date[$i] = str_replace('/ ', '/', $ATOLE_date[$i]);
        $ATOLE_date[$i] = str_replace(' / ', '/', $ATOLE_date[$i]);
        $ATOLE_date[$i] = str_replace(' -', '/', $ATOLE_date[$i]);
        $ATOLE_date[$i] = str_replace(' - ', '/', $ATOLE_date[$i]);
        $ATOLE_date[$i] = str_replace('-', '/', $ATOLE_date[$i]);
        }
//-----------------------------------
  $i = $i + 1;
}
$moyenne_tole = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX AMBAIBO TOLE
$query_max_montant_vente = "SELECT MAX(Montant) as max FROM recap_vente INNER JOIN (SELECT DISTINCT numero_commande_stock,nom_client_fournisseur,type_de_mvt,description_date FROM mvt WHERE nom_client_fournisseur = 'Ambaibo_Tole' AND description_date NOT LIKE '%rano%' AND description_date NOT LIKE '%fana%' AND type_de_mvt = 'vente') new ON no_activite = numero_commande_stock;" ;
  $q = $bdd->prepare($query_max_montant_vente);
  $q->execute(array());
  //initialisation
  $max_tole = 0;
  $donnees = $q -> fetch();
  $max_tole = $donnees['max'];
  $q->closeCursor();
     //versement moyenne du semaine Veve##################
$query_moyenne = "SELECT DISTINCT numero_commande_stock,Montant,description_date FROM recap_vente INNER JOIN mvt ON no_activite = numero_commande_stock  WHERE mvt.nom_client_fournisseur = 'Ambato_veve_photo' AND type_de_mvt = 'vente' ORDER BY Date_du_Journal DESC LIMIT 7" ;
  $q = $bdd->prepare($query_moyenne);
  $q->execute(array());
  $nb_line=$q->rowCount ();
  //initialisation
  if ($nb_line == 0) 
  {
    $nb_line = 1;
  }
  $total = 0;
  //INITIALISATION
  $VV[0] = 0;
  $VV[1] = 0;
  $VV[2] = 0;
  $VV[3] = 0;
  $VV[4] = 0;
  $VV[5] = 0;
  $VV[6] = 0;

  $VV_date[0] = "";
  $VV_date[1] = "";
  $VV_date[2] = "";
  $VV_date[3] = "";
  $VV_date[4] = "";
  $VV_date[5] = "";
  $VV_date[6] = "";
  $i = 0;
  while ($donnees = $q -> fetch())
{
  $total = $donnees['Montant']+$total;
  //RECUPERATION LISTE 5 MONTANT VEVE
  $VV[$i]=$donnees['Montant'];
  //GET Date on a string
  $chaine =  $donnees['description_date'].' ';
        $x = chr(35).'/0-9-';
        preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
        //Prise en compte separation date / or -
        if (isset($res_regex[1])) {
        $VV_date[$i] = $res_regex[1];
        //Eviter wrong format and unwanted space for $date1
        $VV_date[$i] = str_replace('- ', '/', $VV_date[$i]);
        $VV_date[$i] = str_replace('/ ', '/', $VV_date[$i]);
        $VV_date[$i] = str_replace(' / ', '/', $VV_date[$i]);
        $VV_date[$i] = str_replace(' -', '/', $VV_date[$i]);
        $VV_date[$i] = str_replace(' - ', '/', $VV_date[$i]);
        $VV_date[$i] = str_replace('-', '/', $VV_date[$i]);
        }
  $i = $i + 1;
}
$moyenne_veve = $total/$nb_line;
//echo $moyenne;
  $q->closeCursor();
  //MAX VEVE
$query_max_montant_vente = "SELECT MAX(Montant) as max FROM recap_vente INNER JOIN (SELECT DISTINCT numero_commande_stock,nom_client_fournisseur,type_de_mvt FROM mvt WHERE nom_client_fournisseur = 'Ambato_veve_photo' AND type_de_mvt = 'vente') new ON no_activite = numero_commande_stock;" ;
  $q = $bdd->prepare($query_max_montant_vente);
  $q->execute(array());
  //initialisation
  $max_veve = 0;
  $donnees = $q -> fetch();
  $max_veve = $donnees['max'];
  $q->closeCursor();
  //echo $max;
 ?>
 
  <!-- End your project here-->
  <div class="flex-center flex-column">
  <table class="table-bordered table-sm bg-light">
    <thead>
    	<tr>
        <th colspan="9" class="text-center"><b>VERSEMENT DANS 7 DERNIERS JOURS</b></th>
      </tr>
      <tr>
        <th colspan="8" class="bg-secondary text-center text-light"><b>>>>>>  PLUS RECENT  >>>></b></th>
        <th class="bg-secondary text-light">MOYENNE PAR JOUR</th>
      </tr>
    </thead>
  <tbody>
    <tr>
      <th scope="row"><b style="font-size:12pt">Ambato</b></th>
      <?php
      //fUNCTION REFORMAT
      function ref_format($str, $step, $reverse = false) {

      if ($reverse)
              return strrev(chunk_split(strrev($str), $step, ' '));

          return chunk_split($str, $step, ' ');
        }
      $AT_M = 0 ;
      for ($i=6; $i >= 0; $i--) { $AT_M = $AT_M + $AT[$i];
        ?>
      <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $AT_date[$i]; ?></span><br><?php echo ref_format($AT[$i], 3, true); ?></td>
    <?php } ?>
      <td class="text-right"><span style="font-weight: bold; font-size:18pt"><?php $AT_M = floor($AT_M/7); echo ref_format($AT_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
    <!-------------------------------->
    <tr>
      <th scope="col" style="font-size:12pt"><b>Bejofo</b></th>
      <?php $BJ_M = 0; for ($i=6; $i >= 0; $i--) { $BJ_M = $BJ_M + $BJ[$i]; ?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $BJ_date[$i]; ?></span><br><?php echo ref_format($BJ[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold; font-size:18pt"><?php $BJ_M = floor($BJ_M/7); echo ref_format($BJ_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
    <!-------------------------------->
    <tr>
      <th scope="col" style="font-size:12pt"><b>Ambaiboho</b></th>
      <?php $SR_M = 0; for ($i=6; $i >= 0; $i--) { $SR_M = $SR_M + $SR[$i]; ?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $SR_date[$i]; ?></span><br><?php echo ref_format($SR[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold; font-size:18pt"><?php $SR_M = floor($SR_M/7); echo ref_format($SR_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
    <!----------------------------->
    <tr>
      <th scope="col" style="font-size:12pt"><b>Amparafa</b></th>
      <?php $AF_M =0; for ($i=6; $i >= 0; $i--) {$AF_M = $AF[$i] + $AF_M; ?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $AF_date[$i]; ?></span><br><?php echo ref_format($AF[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold; font-size:18pt"><?php $AF_M =floor($AF_M/7);  echo ref_format($AF_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
    <!--------------------------------
    <tr>
      <th scope="col" style="font-size:12pt"><b>Ambaibo Tole</b></th>
      <?php $ATOLE_M = 0; for ($i=6; $i >= 0; $i--) { $ATOLE_M = $ATOLE[$i] + $ATOLE_M; ?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $ATOLE_date[$i]; ?></span><br><?php echo ref_format($ATOLE[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold; font-size:18pt"><?php $ATOLE_M = floor($ATOLE_M/7) ; echo ref_format($ATOLE_M, 3, true); ?><span style="font-weight: bold ; font-size:12pt"> /jour</span></span></td>
    </tr>
    ------------------------------
    <tr>
      <th scope="col" style="font-size:12pt"><b>Morarano</b></th>
      <?php $MO_M = 0; for ($i=6; $i >= 0; $i--) { ?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $MO_date[$i]; ?></span><br><?php echo ref_format($MO[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold ; font-size:18pt"><?php $MO_M = floor($MO[0]); echo ref_format($MO_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /sem</span></td>
    </tr>
    ------------------------------
    <tr>
      <th scope="col" style="font-size:12pt"><b>Andrefana</b></th>
      <?php $ANDREFANA_M = 0; for ($i=6; $i >= 0; $i--) { ?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $ANDREFANA_date[$i]; ?></span><br><?php echo ref_format($ANDREFANA[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold ; font-size:18pt"><?php $ANDREFANA_M = floor($ANDREFANA[0]); echo ref_format($ANDREFANA_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /sem</span></td>
    </tr>
    ------------------------------
    <tr>
      <th scope="col" style="font-size:12pt"><b>Veve</b></th>
      <?php $VV_M = 0; for ($i=6; $i >= 0; $i--) { $VV_M = $VV[$i]+$VV_M;?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $VV_date[$i]; ?></span><br><?php echo ref_format($VV[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold ; font-size:18pt"><?php $VV_M = floor($VV_M/7) ;echo ref_format($VV_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
   ------------------------------>
        <tr>
      <th scope="col" style="font-size:12pt"><b>Eléctonique</b></th>
      <?php $AE_M = 0; for ($i=6; $i >= 0; $i--) { $AE_M = $AE[$i]+$AE_M;?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $AE_date[$i]; ?></span><br><?php echo ref_format($AE[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold ; font-size:18pt"><?php $AE_M = floor($AE_M/7) ;echo ref_format($AE_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
    <!--------------------------------
    <tr>
      <th scope="col" style="font-size:12pt"><b>Ambato Pneu</b></th>
      <?php $AP_M = 0; for ($i=6; $i >= 0; $i--) { $AP_M = $AP[$i]+$AP_M;?>
        <td class="text-right" style="font-size:12pt"><span style="font-weight: bold"><?php echo $AP_date[$i]; ?></span><br><?php echo ref_format($AP[$i], 3, true); ?></td>
      <?php } ?>
      <td class="text-right"><span style="font-weight: bold ; font-size:18pt"><?php $AP_M = floor($AP_M/7) ;echo ref_format($AP_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></td>
    </tr>
    <!-------------------------------->
    <tr>
        <th colspan="8" class="bg-secondary"></th>
        <th class="bg-secondary text-light"><span style="font-weight: bold ; font-size:18pt"><?php $T_M = $AT_M+$BJ_M+$SR_M+$AF_M+$ATOLE_M+$MO_M+$ANDREFANA_M+$VV_M+$AE_M+$AP_M ;echo ref_format($T_M, 3, true); ?></span><span style="font-weight: bold ; font-size:12pt"> /jour</span></th>
      </tr>
  </tbody>
</table>
</div>
<br>
<br>
<!-----START CHAR HORIZONTAL----
    <div>
    <div class="flex-center flex-column">
      <h5 class="animated fadeIn mb-3"><b>VERSEMENT MOYENNE DANS 5 DERNIERS JOURS</b></h5>
      <canvas id="horizontalBar" style="max-width: 80%;"></canvas>
    </div>
    <br>
    <br>
    <!-----TO AUTO AJUST WITH PARENT USE LIKE THIS
    <div class="col-md-5">
      <canvas id="horizontalBar"></canvas>
  </div>
  ----
  </div>
  <!-----END CHAR HORIZONTAL---->
  <br>
  <br>
  <br>
  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript">
    //VARIABLE MAX
    var max_tantely = <?php echo json_encode($max_tantely); ?>;
    //var max_pneu = <?php echo json_encode($max_pneu); ?>;
    var max_elec = <?php echo json_encode($max_elec); ?>;
    var max_bejofo = <?php echo json_encode($max_bejofo); ?>;
    var max_amparafa = <?php echo json_encode($max_amparafa); ?>;
    /*var max_tole = <?php echo json_encode($max_tole); ?>;
    var max_morarano = <?php echo json_encode($max_morarano); ?>;
    var max_andrefana = <?php echo json_encode($max_andrefana); ?>;
    var max_veve = <?php echo json_encode($max_veve); ?>;*/
    var max_ambaiboho = <?php echo json_encode($max_ambaiboho); ?>;
    //VARIABLE MOYENNE
    var moyenne_tantely = <?php echo json_encode($moyenne_tantely); ?>;
    //var moyenne_pneu = <?php echo json_encode($moyenne_pneu); ?>;
    var moyenne_elec = <?php echo json_encode($moyenne_elec); ?>;
    var moyenne_bejofo = <?php echo json_encode($moyenne_bejofo); ?>;
    var moyenne_amparafa = <?php echo json_encode($moyenne_amparafa); ?>;
    /*var moyenne_tole = <?php echo json_encode($moyenne_tole); ?>;
    var moyenne_morarano = <?php echo json_encode($moyenne_morarano); ?>;
    var moyenne_andrefana = <?php echo json_encode($moyenne_andrefana); ?>;*/
    var query_moyenne_ambaiboho = <?php echo json_encode($moyenne_ambaiboho); ?>;
    //var moyenne_bejofo = 80000;
    var moyenne_veve = <?php echo json_encode($moyenne_veve); ?>;
    new Chart(document.getElementById("horizontalBar"), {
"type": "horizontalBar",
"data": {
"labels": ["Ambato", "Amparafa",/* "Ambaibo Tole", "Morarano", "Andrefana",*/ "Bejofo","Tsena",/* "Veve",*/"Eléctonique"/*,"Ambato Pneu"*/],
"datasets": [{
"label": [("MAX:Ambato"+"("+max_tantely+")"),(" A/fa"+"("+max_amparafa+")"),/*(" Tole"+"("+max_tole+")"),(" Morarano"+"("+max_morarano+")"),("Andrefana"+"("+max_andrefana+")"),*/(" Bejofo"+"("+max_bejofo+")"),(" Tsena"+"("+max_ambaiboho+")"),/*(" Veve"+"("+max_veve+")"),*/(" Eléctonique"+"("+max_elec+")"),/*(" Pneu"+"("+max_pneu+")")*/],
"data":  [moyenne_tantely, moyenne_amparafa,/* moyenne_tole, moyenne_morarano, moyenne_andrefana,*/ moyenne_bejofo, moyenne_ambaiboho,/* moyenne_veve,*/ moyenne_elec,/*moyenne_pneu*/],
"fill": false,
"backgroundColor": ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)",
"rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(54, 162, 235, 0.2)",
"rgba(153, 102, 255, 0.2)", "rgba(236, 255, 0, 0.2)", "rgba(201, 203, 207, 0.2)", "rgba(92, 255, 0, 0.2)", "rgba(157, 255, 0, 0.2)"
],
"borderColor": ["rgb(255, 99, 132)", "rgb(255, 159, 64)", "rgb(255, 205, 86)",
"rgb(75, 192, 192)", "rgb(54, 162, 235)", "rgb(153, 102, 255)", "rgb(255, 111, 0)","rgb(201, 203, 207)", "rgb(0, 175, 0)", "rgb(111, 175, 0)"
],
"borderWidth": 1
}]
},
"options": {
"scales": {
"xAxes": [{
"ticks": {
"beginAtZero": true
}
}]
}
}
});
  </script>
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
  </script>

</body>
</html>
