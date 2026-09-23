<!DOCTYPE html>
<html>
<head>
  <title>Globale Reference V5.9</title>
  <!---add bootstrap css--->
  <script src="js/jquery-3.5.1.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/style_Index.css">
  <!---add other css--->

</head>
<body>
  <?php
  //Get point de vente name
  $nom_client_fournisseur ='';
  $selected='0';
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
        default:
            $selected='0';
            break;
    }
  //echo $nom_client_fournisseur;
  //echo $selected;
  }
    //Connect to BD
include('connect.php');
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
<!--------------------------------------->
<br>
<br>
<div class="text-center">
<form role="form" action="exist_produit_img.php" method="post">
  <div>
    <select class="btn" name="point_de_vente">
    <option value="none" selected disabled hidden>Choose Point de vente</option>
      <option value="Ambaibo_Tole" <?php  if($selected == '3'){echo("selected");}?>>Ambaibo_Tole</option>
      <option value="Amparafa" <?php if($selected == '4'){echo("selected");}?>>Amparafa</option>
      <option value="Ambato_Tantely" <?php if($selected == '5'){echo("selected");}?>>Ambato_Tantely</option>
      <option value="Ambato_veve_photo" <?php if($selected == '6'){echo("selected");}?>>Ambato_veve_photo</option>
      <option value="Bejofo" <?php if($selected == '7'){echo("selected");}?>>bejofo</option>
      <!--
      <option value="Ambaibo_Electronique" <?php if($selected == '1'){echo("selected");}?>>Ambaibo_Electronique</option>
      <option value="Ambaibo_loko" <?php if($selected == '2'){echo("selected");}?>>Ambaibo_loko</option>
    -->
    </select>
    <button type="submit" class="btn btn-success">show</button>
    <a href="home_char.php"><button type="button" class="btn btn-danger">Retour</button></a>
    <br>
    <br>
    <div>
    <input type="checkbox" name="checkbox">
    <label><b>WITH PICTURE</b></label>
  </div>
  <div>
    <input type="checkbox" name="checkbox_version" onclick="confirmationDelete('ARE YOU SURE?');return false; post ;">
    <label class="text-danger"><b>NEW VERSION</b></label>
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
   $query_stock_inventaire = 'SELECT nom_x,reference_x from produit INNER JOIN mvt ON produit.id_x = mvt.id_x WHERE nom_client_fournisseur = ? GROUP BY reference_x;';
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
  $q->closeCursor();
  
  if ($nb_line == 0) {
    $query_stock_inventaire = 'SELECT nom_x,reference_x from produit ORDER BY nom_x;';
  $q = $bdd->prepare($query_stock_inventaire);

  $q->execute(array());
  ?>
   <table class="table-info table">
        <thead>
        <tr>
        <th>No</th>
        <th>REFERENCE</th>
        <th>NOM DE PRODUIT</th>
        <th>FANAMARIHANA</th>
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
?>
<!------------------------------------------------->
        <tr>
        <td><a href="#"><?php echo $j; ?></a></td>
        <td><?php echo $donnees['reference_x']; ?></td>
        <td><?php echo $donnees['nom_x']; ?></td>
        <td></td>
      </tr>
<?php
$j = $j+1;
}
?>
        </tbody>
    </table>
   <?php
  } else {
  
//$query_product_search = $bdd->query('SELECT FROM produit WHERE nom_x LIKE ? OR reference_x LIKE ? ORDER BY id_x;');
$query_stock_inventaire = 'SELECT * from produit GROUP BY reference_x;';
$q = $bdd->prepare($query_stock_inventaire);
$q->execute(array());
$nb_total = $q->rowCount();
?>
<div class="text-center">
   <table class="tg">
        <thead>
        <tr>
          <th class="text-center violet" colspan="6"><?php echo $name_version; ?></th>
        </tr>
        <tr>
        <th class = "orange">No</th>
        <th class = "orange"></th>
        <th class = "orange">REFERENCE</th>
        <th class = "orange">NOM DE PRODUIT <?php echo '('.$nom_client_fournisseur.' | '.$nb_line.'/'.$nb_total.')' ;?></th>
        <th class = "orange">Notification</th>
        <th class = "orange">PRIX</th>
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
            $cat='Autre';
            //$cat=strlen($char2);
            break;
    }
//END CATECORY FUCNTION
  //Handle diminution or augmentation prix Ambato/Amparafa/General
	$difference_prix = number_format(($donnees['difference_prix']+0),0, "", " ");
	if ($nom_client_fournisseur=='Amparafa') {$difference_prix = number_format(($donnees['difference_prix_amparafa']+0),0, "", " ");}
	if ($nom_client_fournisseur=='Ambato_Tantely') {$difference_prix = number_format(($donnees['difference_prix_tantely']+0),0, "", " ");}
	if ($difference_prix > 0) {
		$color_badge = "rouge";
		$difference_prix = "+".$difference_prix;
	} else {
		$color_badge = "vert";
	}
	$note_prix = $donnees['note_prix']."";
	if ($nom_client_fournisseur=='Amparafa') {$note_prix = $donnees['note_prix_amparafa']."";}
	if ($nom_client_fournisseur=='Ambato_Tantely') {$note_prix = $donnees['note_prix_tantely']."";}
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

if ($donnees['note_x'] == 'ok' || $donnees['note_x'] == '2021' || $donnees['note_x'] == 'latest' || $donnees['prix_de_vente'] != 0 || strlen($donnees['prix_de_vente']) > 1 || $donnees['pu_aparafa'] != 0 || strlen($donnees['pu_aparafa']) > 1 || $donnees['pu_ambato_tantely'] != 0 || strlen($donnees['pu_ambato_tantely']) > 1) {
    $prix = number_format($donnees['prix_de_vente'],0, "", " ");
    if ($nom_client_fournisseur=='Amparafa') {$prix = number_format($donnees['pu_aparafa'],0, "", " ");}
    if ($nom_client_fournisseur=='Ambato_Tantely') {$prix = number_format($donnees['pu_ambato_tantely'],0, "", " ");}
    $x = $x + 1;
  }
if ($bold=='yes')
{
  //COLOR OF LINE
  if ($classname=="tg-0lax4a") {
    $classname="tg-0lax4b";
  } else {
    $classname="tg-0lax4a";
  }
  //echo $bold;
  //echo $f;
	if ($prix == 0) {
		$prix = 'waiting';
	}

  //IMAGE PATH_X
     $image_path_x = $donnees['img_path_x'];
     if (strlen($image_path_x) == 0) {
     $image_path_x = 'no';
     }
  //BADGE COLOR
    if ($color_badge != 'rouge' AND $color_badge != 'vert') {
      $color_badge = $classname;
    }
?>
<tr>
    <td class=<?php echo $classname; ?>><a href="#"><?php echo $f; ?></a></td>
    <td class=<?php echo $classname; ?>>
      <?php if ($image_path_x != 'no') {
      ?>
      <img src="<?php echo $image_path_x; ?>" height="80" width="80" background alt="Edit" />
      <?php } ?>
    </td>
    <td class=<?php echo $classname; ?>><b><?php echo $donnees['reference_x']; ?></b></td>
    <td class=<?php echo $classname; ?>><b><?php echo $donnees['nom_x']; ?></b></td>
    <td class="<?php echo $color_badge; ?>"><b style="color: white"><?php echo $difference_prix; ?></b></td>
    <td class="<?php echo $classname; ?> text-right"><b><?php echo $prix; ?></b></td>
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
<tr>
    <td></td>
    <td class="text-secondary"></td>
    <td class="text-secondary"></td>
    <td class="text-secondary"></td>
    <td class="text-secondary"></td>
    <td><?php echo $x.'/'.$nb_line; ?></td>
</tr>
        </tbody>
    </table>
</div>
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