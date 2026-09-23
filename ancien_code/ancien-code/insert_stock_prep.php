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

if (isset($_POST['prix_de_vente'])) {
	$prix_de_vente = $_POST['prix_de_vente'];
}

if (isset($_POST['prix_fournisseur'])) {
  $prix_fournisseur = $_POST['prix_fournisseur'];
}


if (isset($_POST['qt'])) {
	$qt = $_POST['qt'];
}
if (isset($_POST['note_stock'])) {
  $note_stock = $_POST['note_stock'];
}
if (isset($_POST['id_x'])) {
  $id_x = $_POST['id_x'];
}
if (isset($_POST['prix_client'])) {
  $prix_client = $_POST['prix_client'];
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
    $query_client_name = $bdd->prepare("SELECT * FROM stock_prep WHERE description_date LIKE '%Ajout%' AND user_stock_prep = ? ");
        $query_client_name->execute(array($username));
        $total_line=$query_client_name->rowCount ();

        if ($total_line == 0) {
//-------------------------------------------
        $reponse = $bdd->prepare('SELECT * FROM memo WHERE id_memo = 2');
        $reponse->execute(array());

       while ($donnees = $reponse->fetch())
         {
          //Numero de commande est la valeure memo
         $numero_stock = $donnees['valeur_memo'];
         $nom_du_client = $donnees['note_memo'];
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
$query_c = "INSERT INTO stock_prep(numero_stock_prep, nom_du_client,description_date, id_x, qt, prix_de_vente, state, note,user_stock_prep, prix_client) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$q = $bdd->prepare($query_c);

	$q->execute(array($numero_stock, $nom_du_client,$description_date, $id_x, $qt, $prix_de_vente, $state, $note_stock, $username,$prix_client));
  $q->closeCursor();
  //----------------UPDATE PRIX FOURNISSEUR-------------------
  //Check change is valide
  $verification_prix = "SELECT * FROM produit WHERE id_x = ? ;";
  $verification_prix = $bdd->prepare($verification_prix);
  $verification_prix->execute(array($id_x));
  $data = $verification_prix -> fetch();
  $difference_prix_fournisseur = $prix_fournisseur - ($data['prix_fournisseur']+0);
  if ($difference_prix_fournisseur  != 0) {

    //------------ Update table produit -----------
    $query_p = "UPDATE produit
                SET prix_fournisseur = ?
                WHERE id_x = ?";

      $q = $bdd->prepare($query_p);

      $q->execute(array($prix_fournisseur,$id_x));

      $q->closeCursor();
      //--------------Update table history--------------------------
      $before_change = ' | PU Fournisseur : '.($data['prix_fournisseur']+0).'</br>';
      $after_change = " | PU Fournisseur : <span class ='text-danger'><b>".$prix_fournisseur.'</b></span></br>';
      $query_c = "INSERT INTO history(after_change,before_change, details,responsable,type) VALUES (?, ?, ?, ?,?)";

      $q = $bdd->prepare($query_c);

      $type = "Prix";
      $q->execute(array($after_change ,$before_change , $id_x, $username, $type));  

      $q->closeCursor();
    } //END IF
    $verification_prix -> closeCursor();
    //----------------UPDATE PRIX FOURNISSEUR END-------------------
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

header("location: stock.php");

?>