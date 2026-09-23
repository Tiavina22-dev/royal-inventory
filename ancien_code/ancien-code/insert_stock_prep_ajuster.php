<?php
include('connect.php');
/*
produit (id_x, id_cat, id_unite, nom_x, prix_de_vente, reference_x, note_x)
commande (valeur_memo, id_x, qt, prix_de_vente, state)
*/
//-------------------------------------------
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

$id_unite = 1;


	$prix_de_vente = 0;
  if (isset($_POST['prix_de_vente'])) {
  $prix_de_vente = $_POST['prix_de_vente'];
  }

$prix_client = 0;
if (isset($_POST['prix_client'])) {
	$prix_client = $_POST['prix_client'];
}


if (isset($_POST['qt_reelle'])) {
  $qt = $_POST['qt_reelle'];
}

$note_stock ="";
if (isset($_POST['note_stock'])) {
  $note_stock = $_POST['note_stock'];
}
if (isset($_POST['id_x'])) {
  $id_x = $_POST['id_x'];
}

$state = "preparing";
//-------Compter le contenu---------------------
$nb_preview_line_stock_prep = 999999;
$reponse = $bdd->prepare('SELECT * FROM stock_prep');
$reponse->execute(array());
$nb_previous_line_stock_prep = ($reponse -> rowCount ())+1;
$reponse -> closeCursor();
//-------------------------------------------
//-------Reccuperation du valeur memo--------
    $query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ? ");
        $query_client_name->execute(array($username));
        $total_line=$query_client_name->rowCount ();

        if ($total_line == 0) {
//-------------------------------------------
        $reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
        $reponse->execute(array($username));

       while ($donnees = $reponse->fetch())
         {
          //Numero de commande est la valeure memo
         $numero_stock = $donnees['numero_commande'];
         $nom_du_client = $donnees['point_de_vente'];
         $description_date = $donnees['description_date'];
          }
      $reponse->closeCursor();
//-------------------------------------------
        }
        else{
//-------------------------------------------
      while ($donnees = $query_client_name->fetch())
          {
              $numero_stock = $donnees['numero_stock_prep'];
              $nom_du_client = $donnees['nom_du_client'];
              $description_date = $donnees['description_date'];
          }
          $query_client_name->closeCursor();
//-------------------------------------------
    }
    /*
    echo "Numero Stock ".$numero_stock;
    echo "<br>";
    echo "Client name".$nom_du_client;
    echo "<br>";
    echo "Description date".$description_date;
    echo "<br>";
    echo "id_x".$id_x;
    echo "<br>";
    echo "qt".$qt;
    echo "<br>";
    echo "Prix de vente".$prix_de_vente;
    echo "<br>";
    echo "state ".$state;
    echo "<br>";
    echo "Note".$note_stock;
    echo "<br>";
    echo "$username".$username;
    */
$query_c = "INSERT INTO stock_prep(numero_stock_prep, nom_du_client,description_date, id_x, qt, prix_de_vente,prix_client,state, note,user_stock_prep) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_stock, $nom_du_client,$description_date, $id_x, $qt, $prix_de_vente,$prix_client, $state, $note_stock, $username));
  $q->closeCursor();
  //----------------UPDATE PRIX TEMPORAIRE-------------------
  /*
$query_p = "UPDATE produit
            SET prix_de_vente = ?
            WHERE id_x = ?";

  $q = $bdd->prepare($query_p);

  $q->execute(array($prix_de_vente,$id_x));

  $q->closeCursor();
  */
//-------Actual_id_x---------------------
$reponse = $bdd->prepare('SELECT * FROM stock_prep');
$reponse->execute(array());
$nb_actual_line_stock_prep = $reponse -> rowCount ();
$reponse -> closeCursor();
//-------------------------------------------
//-----------------VERIFICATION PERTE DB------------------
  
  if ( $nb_previous_line_stock_prep == $nb_actual_line_stock_prep ) 
  {
    # code...
    //echo "Valide";
    $msg = "Successfuly Saved";
    setcookie("msg_ok",$msg, time()+5);
  }
  else
  {
    # code...
    //echo "Invalide";
    $msg = "Failed";
    setcookie("msg_nok",$msg, time()+5);
  }
  //echo $nb_previous_line_stock_prep;
  //echo "<br>";
  //echo $nb_actual_line_stock_prep;
//-------------------------------------------
//##################################### SAVE IMAGE ##################################################################
  //GET REAL IMG PATH
  //echo $id_x;
$query_path_img_x = "SELECT * FROM produit WHERE id_x LIKE ?";
$qi = $bdd->prepare($query_path_img_x);
$qi->execute(array("%".$id_x."%"));
$donnees_img = $qi -> fetch();
$image_path_x = "";
$image_path_x = $donnees_img['img_path_x'];
$qi->closeCursor();               
//echo $image_path_x;
    function GetImageExtension($imagetype)
     {
       if(empty($imagetype)) return false;
       switch($imagetype)
       {
           case 'image/bmp': return '.bmp';
           case 'image/gif': return '.gif';
           case 'image/jpeg': return '.jpg';
           case 'image/png': return '.png';
           default: return false;
       }
     }
   
   
   
if (!empty($_FILES["uploadedimage"]["name"])) {
  //DELETE PREVIEWS IMG FILE
  if (strlen($image_path_x) != 0) {unlink($image_path_x);}

  $file_name=$_FILES["uploadedimage"]["name"];
  $temp_name=$_FILES["uploadedimage"]["tmp_name"];
  $imgtype=$_FILES["uploadedimage"]["type"];
  $ext= GetImageExtension($imgtype);
  //Use new customized image name
  $imagename="img"."_".$id_x.$ext;
  //Use current image name
  //$imagename=$_FILES["uploadedimage"]["name"];
  $target_path = "img_x/".$imagename;
  

if(move_uploaded_file($temp_name, $target_path)) {
  /*
  $query_upload="UPDATE user 
  SET 'img_path'='.$target_path.' 
  WHERE 'User_Name'= $usernm ";
  mysql_query($query_upload) or die("error in $query_upload == ----> ".mysql_error());
  */

  $sql = "UPDATE produit 

  SET img_path_x = ?, user_x = ?

  WHERE id_x =?";

  $q = $bdd->prepare($sql);

  $q->execute(array($target_path, $username, $id_x));
  $q->closeCursor();
  
}else{

   exit("Error While uploading image on the server");
} 
setcookie("key_word",$id_x, time()+5);
setcookie("img_msg",'IMAGE SAVED', time()+5);
} else { //Empty img file
//------------IMAG FAILED TO SAVE--------
setcookie("key_word",$id_x, time()+5);
setcookie("msg_nok",'PLEASE CHOOSE AN IMAGE FILE', time()+5);
//---------------------------------------
}//else Empty img file
//--------------------------------------------

header("location: controle_x.php");

?>