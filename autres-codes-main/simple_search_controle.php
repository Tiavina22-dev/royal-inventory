<?php
$key_word = "" ;
if (isset($_POST['key_word']))
{
	$key_word = $_POST['key_word'];
}
//echo $key_word;
//GET Point de vente
//-------Reccuperation du valeur memo--------
//Get username
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

include('connect.php');

 $query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Rectifier%' AND user_stock_prep = ?");
        $query_client_name->execute(array($username));
        $nb_line = $query_client_name->rowCount ();
while ($donnees = $query_client_name->fetch())
         {
        $numero_stock = $donnees['numero_stock_prep'];
         $point_de_vente = $donnees['nom_du_client'];
         $description_date = $donnees['description_date'];
          }
    $query_client_name->closeCursor();
if ($nb_line == 0) {

            $reponse = $bdd->prepare('SELECT * FROM user WHERE User_Name = ?');
                    $reponse->execute(array($username));

                   while ($donnees = $reponse->fetch())
                     {
                     $point_de_vente = $donnees['point_de_vente'];
                     $description_date = $donnees['description_date'];
                     $numero_stock = $donnees['numero_commande'];
                      }
            $reponse->closeCursor();
  }
//--------tracking user activity----------
    $activity = "Dans Saisi Inventaire | search";
    $username = "default";
    if (isset($_SESSION['User_Name'])) 
    {
      $username = $_SESSION['User_Name'];
    }
    $type = 'tracking_activity';
    $query_activity = "INSERT INTO history(details, type, responsable) VALUES (?, ?, ?)";

    $query_activity = $bdd->prepare($query_activity);

    $query_activity->execute(array($activity, $type, $username));
    $query_activity->closeCursor();
    //----------------------------------------
//Set cookies to appear imediately preparation page
setcookie("key_word",$key_word, time()+5);
setcookie("point_de_vente",$point_de_vente, time()+5);
setcookie("description_date",$description_date, time()+5);
setcookie("numero_stock",$numero_stock, time()+5);
//Eviter la page de demarrage
$time_for_nouveau_stock_cookie = 1 ;
setcookie("time_for_nouveau_stock_cookie",$time_for_nouveau_stock_cookie, time()+5);
header("location: controle_x.php");
?>