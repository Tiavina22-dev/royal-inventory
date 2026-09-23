
<!--------------------------------------->
<?php
//Connect to BD
include('connect.php');

	//Nombre OF ALL product
	$nb_all_product= 0;
	$query_product_all = 'SELECT id_x FROM produit';
	$q = $bdd->prepare($query_product_all);
	$q->execute(array());
	$nb_all_product=$q->rowCount ();
	$q->closeCursor();
	//Query to COUNT STOCK
	$query_general_stock = "SELECT * FROM MVT WHERE type_de_mvt = 'stock';";
    $qgs = $bdd->prepare($query_general_stock);

    $qgs->execute(array());
    //Number of Line
    $nb_vente=$qgs->rowCount ();
    $qgs->closeCursor(); 
    //Query to COUNT VENTE
	$query_general_vente = "SELECT * FROM MVT WHERE type_de_mvt = 'vente';";
    $qgv = $bdd->prepare($query_general_vente);

    $qgv->execute(array());
    //Number of Line
    $nb_stock=$qgv->rowCount ();
    $qgv->closeCursor();
    //Query to COUNT INVOICE ROW
  $query_general_facture = "SELECT * FROM MVT WHERE type_de_mvt = 'facture';";
    $qgf = $bdd->prepare($query_general_facture);

    $qgf->execute(array());
    //Number of Line
    $nb_facture_ligne = $qgf->rowCount ();
    $qgf->closeCursor(); 
    //Query to COUNT INVOICE
  $query_general_facture = "SELECT * FROM MVT WHERE type_de_mvt = 'facture'  GROUP BY numero_commande_stock;";
    $qgf = $bdd->prepare($query_general_facture);

    $qgf->execute(array());
    //Number of Line
    $nb_facture = $qgf->rowCount ();
    $qgf->closeCursor(); 
    //Query to COUNT INVENTAIRE
	$query_inventory = "SELECT COUNT(*) FROM MVT WHERE type_de_mvt = 'stock' GROUP BY numero_commande_stock;";
    $qi = $bdd->prepare($query_inventory);

    $qi->execute(array());
    //Number of Line
    $nb_inventory=$qi->rowCount ();
    $qi->closeCursor();
    //Query to COUNT JOURNAL
	$query_journal = "SELECT COUNT(*) FROM MVT WHERE type_de_mvt = 'vente' GROUP BY numero_commande_stock;";
    $qj = $bdd->prepare($query_journal);

    $qj->execute(array());
    //Number of Line
    $nb_journal=$qj->rowCount ();
    $qj->closeCursor();
 
   //Query to liste point de vente
   $query_client_liste = "SELECT DISTINCT nom_client_fournisseur from mvt WHERE type_de_mvt = 'stock' OR type_de_mvt = 'vente'";
    $qc = $bdd->prepare($query_client_liste);

    $qc->execute(array());
    //Number of Line
    $nb_client=$qc->rowCount ();
    //Versement mensuel
    //MONTHLY VERSEMENT
$query_versement_monthly="SET @rownr = 0;";
$q = $bdd->prepare($query_versement_monthly);
$q->execute(array());
$q->closeCursor();
$query_versement_monthly="SELECT SQL_NO_CACHE YEAR(Date_du_Journal) as YEAR,date_format(Date_du_Journal,'%b') as MONTH, SUM(Montant) as Montant,@rownr:=@rownr+1 as no FROM recap_vente GROUP BY YEAR(Date_du_Journal)*100 + MONTH(Date_du_Journal) ORDER BY no DESC LIMIT 1";
  $q = $bdd->prepare($query_versement_monthly);
  $q->execute(array());
  //initialisation
  $j = 1;
  $Vrsmt_MM[1] = 0;

  //monthname
  $moi_name[1] = '';

  while ($donnees = $q -> fetch())
{
  $Vrsmt_MM[$j] = $donnees['Montant'];
  $moi_name[$j] = $donnees['MONTH'].' '.$donnees['YEAR'];
  //echo $Vrsmt_MM[$j];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
  $j = $j+1;
}
  $q->closeCursor(); 
  //VERSEMENT MAX
  //MONTHLY VERSEMENT
$query_versement_monthly="SET @rownr = 0;";
$q = $bdd->prepare($query_versement_monthly);
$q->execute(array());
$q->closeCursor();
$query_versement_monthly="SELECT SQL_NO_CACHE MAX(Montant) as max FROM (SELECT YEAR(Date_du_Journal) as YEAR,date_format(Date_du_Journal,'%b') as MONTH, SUM(Montant) as Montant,@rownr:=@rownr+1 as no FROM recap_vente GROUP BY YEAR(Date_du_Journal)*100 + MONTH(Date_du_Journal)) as x";
  $q = $bdd->prepare($query_versement_monthly);
  $q->execute(array());
  //initialisation
  $Max = '';

  while ($donnees = $q -> fetch())
{
  $Max = $donnees['max'];
  //echo $Vrsmt_MM[$j];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
}
  $q->closeCursor();
  //Mounth name of MAX
$query_versement_monthly="SET @rownr = 0;";
$q = $bdd->prepare($query_versement_monthly);
$q->execute(array());
$q->closeCursor();
$query_versement_monthly="SELECT SQL_NO_CACHE *,Montant FROM (SELECT YEAR(Date_du_Journal) as YEAR,date_format(Date_du_Journal,'%b') as MONTH, SUM(Montant) as Montant,@rownr:=@rownr+1 as no FROM recap_vente GROUP BY YEAR(Date_du_Journal)*100 + MONTH(Date_du_Journal)) as x WHERE Montant = ?";
  $q = $bdd->prepare($query_versement_monthly);
  $q->execute(array($Max));
  //initialisation
  $Month_Max = '';

  while ($donnees = $q -> fetch())
{
  $Month_Max = $donnees['MONTH'].' '.$donnees['YEAR'];
  //echo $Vrsmt_MM[$j];
  //echo $donnees['Montant'];
 //echo $Amparafa_JJ[$j].'<br>';
}
  $q->closeCursor();
  //fUNCTION REFORMAT
      function ref_format($str, $step, $reverse = false) {

      if ($reverse)
              return strrev(chunk_split(strrev($str), $step, ' '));

          return chunk_split($str, $step, ' ');
        }
?>
<div class="flex-center flex-column animated fadeIn mb-3">
    <h5 class="animated fadeIn mb-3">VUE GLOBALE DE L' ACTIVITE</h5>
   <table class="table-sm bg-light">
        <thead>
        <tr>
        <th colspan="2"><b>NOMBRE DE POINT DE VENTE<span class="w3-badge w3-right w3-margin-right w3-blue"><?php echo $nb_client; ?></span></b></th>
        <th colspan="2"><b>NOMBRE D'ARTICLE<span class="w3-badge w3-right w3-margin-right w3-red"><?php echo ref_format($nb_all_product, 3, true); ?></span></b></th>
        </tr>
        <tr>
        <th colspan="2"><b>NOMBRE DE STOCK<span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ref_format($nb_inventory, 3, true); ?></span></b></th>
        <th colspan="2"><b>NOMBRE DE LIGNE DE STOCK <span class="w3-badge w3-right w3-margin-right w3-green"><?php echo ref_format($nb_stock, 3, true); ?></span></b></th>
        </tr>
        <tr>
        <th colspan="2"><b>NOMBRE DE JOURNAL<span class="w3-badge w3-right w3-margin-right w3-purple"><?php echo ref_format($nb_journal, 3, true); ?></span></b></th>
        <th colspan="2"><b>NOMBRE DE LIGNE DE VENTE<span class="w3-badge w3-right w3-margin-right w3-purple"><?php echo ref_format($nb_vente, 3, true); ?></span></b></th>
        </tr>
        <tr>
        <th colspan="2"><b>NOMBRE DE FACTURE<span class="w3-badge w3-right w3-margin-right w3-pink"><?php echo ref_format($nb_facture, 3, true); ?></span></b></th>
        <th colspan="2"><b>NOMBRE DE LIGNE DE FACURE<span class="w3-badge w3-right w3-margin-right w3-pink"><?php echo ref_format($nb_facture_ligne, 3, true); ?></span></b></th>
        </tr>
        <tr>
        <th colspan="2"><b>TL VERSEMENT <?php echo $moi_name[1]; ?></b><span class="w3-badge w3-right w3-margin-right w3-yellow"><?php echo ref_format($Vrsmt_MM[1], 3, true); ?></span></th>
        <th colspan="2"><b>PIC <?php echo $Month_Max; ?></b><span class="w3-badge w3-right w3-margin-right w3-yellow"><?php echo ref_format($Max, 3, true); ?></span></th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
while ($donnees = $qc -> fetch())
{ 

//Query to COUNT EACH STOCK
$query_each_stock = "SELECT COUNT(*) FROM MVT WHERE type_de_mvt = 'stock' AND nom_client_fournisseur = ?GROUP BY numero_commande_stock;";
    $q = $bdd->prepare($query_each_stock);
    $q->execute(array($donnees['nom_client_fournisseur']));
    //Number of Line
    $nb_each_inventory=$q->rowCount ();
     $q->closeCursor();
     
//Query to COUNT EACH JOURNAL
$query_each_vente = "SELECT COUNT(*) FROM MVT WHERE type_de_mvt = 'vente' AND nom_client_fournisseur = ?GROUP BY numero_commande_stock;";
    $q = $bdd->prepare($query_each_vente);
    $q->execute(array($donnees['nom_client_fournisseur']));
    //Number of Line
    $nb_each_journal=$q->rowCount ();
     $q->closeCursor();
?>
<!------------------------------------------------->
       <tr>
        <td><?php echo $donnees['nom_client_fournisseur']; ?></td>
        <td colspan="2"><i>Apres Inventaire</i><span class="w3-badge w3-right w3-margin-right w3-orange"><?php echo ref_format($nb_each_inventory, 3, true); ?></span></td>
        <td><i>Journal de vente</i><span class="w3-badge w3-right w3-margin-right"><?php echo ref_format($nb_each_journal, 3, true); ?></span></a></td>
       </tr>
 <?php
    }
 $qc->closeCursor();
 
?>
        </tbody>
    </table>
    </div>
<br>
<br>