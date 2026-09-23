<!DOCTYPE html>
<html>
<head>
  <title>Globale Reference</title>
  <!---add bootstrap css--->
  <script src="js/jquery-3.5.1.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!---add other css--->
  <link rel="stylesheet" type="text/css" href="css/style_Index.css">
  <link rel="stylesheet" href="css/mota.css">
</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
  <?php
  include('connect.php');
  //Get point de vente name
  $nom_client_fournisseur ='';
  $selected='0';
  $j = 1;
  if (isset($_COOKIE['point_de_vente'])) 
    {$nom_client_fournisseur = $_COOKIE['point_de_vente'];
    switch($nom_client_fournisseur){
        case "Ambaibo_Electronique":
            $selected='1';
            break;
        case "Ambaibo_loko":
            $selected='2';
            break;
        case "Ambaibo_Tole":
            $selected='3';
            break;
        case "Amparafa":
            $selected='4';
            break;
        case "Ambato_Tantely":
            $selected='5';
            break;
        case "Ambato_veve_photo":
            $selected='6';
            break;
        case "Bejofo":
            $selected='7';
            break;
        case "Soalazaina":
            $selected='8';
            break;
        default:
            $selected='0';
            break;
    }
  //echo $nom_client_fournisseur;
  //echo $selected;
  }
  //Connect to BD
include('connect.php');

?>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="text-center">
<form role="form" action="exist_produit.php" method="post">
  <div>
    <select class="btn btn-warning" name="point_de_vente">
    <option value="none" selected disabled hidden>Choose Point de vente</option>
      
      <option value="Amparafa" <?php if($selected == '4'){echo("selected");}?>>Amparafa</option>
      <option value="Ambato_Tantely" <?php if($selected == '5'){echo("selected");}?>>Ambato_Tantely</option>
      <option value="Bejofo" <?php if($selected == '7'){echo("selected");}?>>bejofo</option>
      <option value="Ambaibo_Electronique" <?php if($selected == '1'){echo("selected");}?>>Ambaibo_Electronique</option>
  <!--<option value="Soalazaina" <?php if($selected == '8'){echo("selected");}?>>Ranto Soalazaina</option>
      <option value="Ambaibo_Tole" <?php  if($selected == '3'){echo("selected");}?>>Ambaibo_Tole</option>
      <option value="Ambato_veve_photo" <?php if($selected == '6'){echo("selected");}?>>Ambato_veve_photo</option>
      <option value="Ambaibo_loko" <?php if($selected == '2'){echo("selected");}?>>Ambaibo_loko</option>
    -->
    </select>
    <button type="submit" class="btn btn-success">show</button>
    <a href="home_char.php"><button type="button" class="btn btn-danger">Retour</button></a>
    <br>
    <br>
    <div>
    <input type="checkbox" name="checkbox">
    <label class="text-danger"><b>WITH PICTURE</b></label>
  </div>
  <div>
    <input type="checkbox" name="checkbox_version" onclick="confirmationDelete('ARE YOU SURE?');return false; post ;">
    <label class="text-danger"><b>NEW VERSION</b></label>
  </div>
  <div>
  	<?php
  	  $version = 0;
    //-------Reccuperation version en cours--------
    $reponse = $bdd->prepare("SELECT * FROM memo WHERE id_memo = 3");
        $reponse->execute(array());
       while ($donnees = $reponse->fetch())
         {
         $version = $donnees['valeur_memo'];
          }
    $reponse->closeCursor();
  $name_version = "GLOBALE REFERENCE PRODUIT (Version 5.".$version.")";
  ?>
  </div>
  </div>
</form>
</div>
<br>
<br>
<!--------------------------------------->
<h4 class="text-center"><?php echo $name_version; ?></h4>
<?php

   //Query to liste searched product
   $query_stock_inventaire = 'SELECT * from produit INNER JOIN mvt ON produit.id_x = mvt.id_x WHERE nom_client_fournisseur = ? GROUP BY reference_x;';
  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array($nom_client_fournisseur));
  //Number of Line
  $nb_line=$q->rowCount ();
  //echo $nb_line;
  //Put all value in array
  $k=1;
  while ($donnees = $q -> fetch())
  {
    $exist_x[$k] = $donnees['reference_x'];
    //echo $exist_x[$k];
    $k=$k+1;
  }

//PREVENT DELETION PRODUCT WHO HAVE MVT
     //TABLE MVT
  
  if ($nb_line == 0) {
    $query_stock_inventaire = 'SELECT * from produit ORDER BY nom_x;';
  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array());
?>
   <table class="table table-dark container">
        <thead>
        <tr class="btn-brown">
        <th>No</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>FANAMARIHANA</th>
        <th>DELETE</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$j = 1;
//Query searcher word
while ($donnees = $q -> fetch())
{ 
//$ref_id = "ref_id".$j;
//echo $ref_id; 
$query_mvt = 'SELECT * FROM mvt WHERE id_x = ?;';
      $query_mvt = $bdd->prepare($query_mvt);
      $query_mvt->execute(array($donnees['id_x']));
      //Number of Line
      $nb_line_mvt = $query_mvt->rowCount ();
      $query_mvt -> closeCursor();
      //TABLE stock_prep
     $query_stock_prep = 'SELECT * FROM stock_prep WHERE id_x = ?;';
      $query_stock_prep = $bdd->prepare($query_stock_prep);
      $query_stock_prep->execute(array($donnees['id_x']));
      //Number of Line
      $nb_line_stock_prep = $query_stock_prep->rowCount ();
      $query_stock_prep -> closeCursor();
      //TABLE commande
     $query_commande = 'SELECT * FROM commande WHERE id_x = ?;';
      $query_commande = $bdd->prepare($query_commande);
      $query_commande->execute(array($donnees['id_x']));
      //Number of Line
      $nb_line_commande = $query_commande->rowCount ();
      $query_commande -> closeCursor();
//SOMME DES 3
      $nb_line_mvt = $nb_line_mvt+$nb_line_stock_prep+$nb_line_commande;
//--------------------------------------
      $id_s = $j.'S';
      $j = $j+1;
                    
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#" class="text-primary"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td></td>
        <td>
      <?php if ($nb_line_mvt == 0) { ?>
      <a id="<?php echo $id_s; ?>" class="center" href="delete_produitg.php?id_x=<?php echo $donnees['id_x']; ?>" onclick="confirmationDelete('Do you want to DELETE this line?');return false; post ;"><img src="img/deleteicon.png" height="30" width="30" background alt="Edit" /></a>
      <?php }; ?>
    </td>
      </tr>
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
    <br>
    <br>
    <br>
    <br>
   <?php
  } else {
  
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');
$query_stock_inventaire = 'SELECT * from produit ORDER BY nom_x;';
$q = $bdd->prepare($query_stock_inventaire);
$q->execute(array());
$nb_total = $q->rowCount();
?>
<div class="container">
   <table class="table table-dark">
        <thead>
        <tr>
          <th class = "text-center violet" colspan="7"><?php echo $name_version; ?></th>
        </tr>
        <tr class="btn-brown">
        <th>No</th>
        <th>REF</th>
        <th>NOM DU PRODUIT <?php echo '('.$nom_client_fournisseur.' | '.$nb_line.'/'.$nb_total.')' ;?></th>
<!-----------
        <th class = "orange">Notification</th>
        <th class = "orange">CATEGORIE</th>
------------>
		<th class="text-right">PU Ref</th>
        <th class ="text-right">ROYAL</th>
        <th class ="text-right">CLIENT</th>
        </tr>
        </thead>
        <tbody>
<!----------------QUERY TABLE---------------------->
<?php
$f = 1;
$x = 0;
$classname="tg-0lax4a";
//Query searcher word
while ($donnees = $q -> fetch())
{ 
$bold = 'no';
//echo $f;
//Handle CATEGORY
$char3 = substr($donnees['reference_x'],0,3);
$char2 = substr($donnees['reference_x'],0,2);
$char1 = substr($donnees['reference_x'],0,1);
switch(true){
        case ($char2=='AE' || $char3=='APE'):
            $cat='Auto Electricite';
            break;
        case ($char2 =='AC'):
            $cat='Auto Consommable';
            break;
        case ($char3 =='ACH'):
            $cat='Auto Huile';
            break;
        case ($char2 =='AO'):
            $cat='Auto Outils';
            break;
        case ($char2 =='AP'):
            $cat='Auto Piece';
            break;
        case ($char2 =='QC'):
            $cat='Qrie Consommable';
            break;
        case ($char2 =='QE'):
            $cat='Qrie Electricite';
            break;
        case ($char2 =='QO'):
            $cat='Qrie Outils';
            break;
        case ($char2 =='QP'):
            $cat='Qrie Peinture';
            break;
        case ($char2 =='QS'):
            $cat='Qrie Sanitaire';
            break;
        case ($char1 =='Q'):
            $cat='Qrie General';
            break;
        default:
            $cat='--';
            //$cat=strlen($char2);
            break;
    }
//END CATECORY FUCNTION
//COLOR OF LINE
  if ($classname=="tg-0lax4a") {
    $classname="tg-0lax4b";
  } else {
    $classname="tg-0lax4a";
  }
  //Handle diminution or augmentation prix Ambato/Amparafa/General
	$difference_prix = number_format(($donnees['difference_prix']+0),0, "", " ");
	if ($nom_client_fournisseur=='Amparafa') {$difference_prix = number_format(($donnees['difference_prix_amparafa']+0),0, "", " ");}
	if ($nom_client_fournisseur=='Ambato_Tantely') {$difference_prix = number_format(($donnees['difference_prix_tantely']+0),0, "", " ");}
	if ($nom_client_fournisseur=='Soalazaina') {$difference_prix = number_format(($donnees['difference_prix_soalazaina']+0),0, "", " ");}
	if ($difference_prix > 0) {
		$color_badge = "rouge";
		$difference_prix = "+".$difference_prix;
	} else {
		$color_badge = "vert";
	}
	$note_prix = $donnees['note_prix']."";
	if ($nom_client_fournisseur=='Amparafa') {$note_prix = $donnees['note_prix_amparafa']."";}
	if ($nom_client_fournisseur=='Ambato_Tantely') {$note_prix = $donnees['note_prix_tantely']."";}
	if ($nom_client_fournisseur=='Soalazaina') {$note_prix = $donnees['note_prix_soalazaina']."";}
	if ($note_prix == "NC" || $note_prix == "")
		{
			$difference_prix ="";
			$color_badge = $classname;
		}
	//--------------------------------------
for ($i=1; $i <= $nb_line; $i++)
{
  //echo $bold;
     if ($donnees['reference_x'] == $exist_x[$i])
     {$bold = 'yes';
		$prix = 'Waiting';
		}                                
} 
//PRIX CONFIRMED

if ($donnees['note_x'] == 'ok' || $donnees['note_x'] == '2021' || $donnees['note_x'] == 'latest' || $donnees['prix_de_vente'] != 0 || strlen($donnees['prix_de_vente']) > 1 || $donnees['pu_aparafa'] != 0 || strlen($donnees['pu_aparafa']) > 1 || $donnees['pu_ambato_tantely'] != 0 || strlen($donnees['pu_ambato_tantely']) > 1 || $donnees['pu_soalazaina'] != 0 || strlen($donnees['pu_soalazaina']) > 1) {
    $prix = number_format($donnees['prix_de_vente'],0, "", " ");
    if ($nom_client_fournisseur=='Amparafa') {$prix = number_format($donnees['pu_aparafa'],0, "", " ");}
    if ($nom_client_fournisseur=='Ambato_Tantely') {$prix = number_format($donnees['pu_ambato_tantely'],0, "", " ");}
    if ($nom_client_fournisseur=='Soalazaina') {$prix = number_format($donnees['pu_soalazaina'],0, "", " ");}
    $x = $x + 1;
  }
if ($bold=='yes')
{
  
  //echo $bold;
  //echo $f;
	if ($prix == 0) {
		$prix = 'waiting';
	}

	//GET LATEST PRIX CLIENT----------
	$query_prix_Client = "SELECT * from mvt WHERE type_de_mvt = 'stock' AND id_x = ? AND nom_client_fournisseur = ? ORDER BY Date_du_Journal_mvt DESC LIMIT 1;";
	$query_prix_Client = $bdd->prepare($query_prix_Client);

	$query_prix_Client ->execute(array($donnees['id_x'],$nom_client_fournisseur));
	$donnees_client = $query_prix_Client -> fetch();
	$prix_latest_client = $donnees_client['prix_client']+0;
	$query_prix_Client -> closeCursor();

	if ($prix_latest_client == 0) {
		$prix_latest_client = '--';
	} else {
		$prix_latest_client = number_format($prix_latest_client,0, "", " ");
	}
?>
<tr>
    <td class=<?php echo $classname; ?>><a href="#" class="text-primary"><?php echo $f; ?></a></td>
    <td class=<?php echo $classname; ?>><b><?php echo $donnees['reference_x']; ?></b></td>
    <td class=<?php echo $classname; ?>><b><?php echo $donnees['nom_x']; ?></b></td>
    <!----------------------------------------------------------------------------------
    <td class="<?php echo $color_badge; ?>"><b style="color: white"><?php echo $difference_prix; ?></b></td>
    <td class=<?php echo $classname; ?>><b><?php echo $cat; ?></b></td>
    -------------------------------------------------------->
    <td class="<?php echo $classname; ?> text-right"><b><?php echo $donnees['prix_fournisseur']+0; ?></td>
    <td class="<?php echo $classname; ?> text-right"><b><?php echo $prix; ?></b></td>
    <td class="<?php echo $classname; ?> text-right"><b><?php echo $prix_latest_client; ?></b></td>
</tr>
<?php
$f = $f+1;
} else {
  //echo $bold;
  //echo $f;
$prix = '----';
?>
<!--
<tr>
    <td class=<?php echo $classname; ?>><a href="#"><?php echo $f; ?></a></td>
    <td class="text-secondary <?php echo $classname; ?>"><?php echo $donnees['reference_x']; ?></td>
    <td class="text-secondary <?php echo $classname; ?>"><?php echo $donnees['nom_x']; ?></td>
    <td class="<?php echo $classname; ?> text-center"></td>
    <td class="text-secondary <?php echo $classname; ?>"><?php echo $cat; ?></td>
    <td class="<?php echo $classname; ?> text-center"><?php echo $prix; ?></td>
</tr>
-->
<?php
//$f = $f+1;
}

?>
<!------------------------------------------------->

<?php
}
?>
        </tbody>
    </table>
</div>
<div class="text-light container">
    <label class="float-right">Nombre d'Articles <?php echo $nom_client_fournisseur .' | '. $nb_line.'/'.$x; ?></label>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
 <?php
  }
 $q->closeCursor();
?>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>