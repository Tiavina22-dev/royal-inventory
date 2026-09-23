<?php
$point_de_vente ="";
if (isset($_POST['point_de_vente'])) {
	$point_de_vente = $_POST['point_de_vente'];
}
$checkbox = 0 ;
if (isset($_POST['checkbox'])) {
	$checkbox = $_POST['checkbox'];
}
if ($checkbox == TRUE) {
	setcookie("point_de_vente",$point_de_vente, time()+5);
	header("location: reference_generator_img.php");
} else {
	setcookie("point_de_vente",$point_de_vente, time()+5);
	header("location: reference_generator_simple.php");
}
$checkbox_version = 0 ;
if (isset($_POST['checkbox_version'])) {
	if ($_POST['checkbox_version'] == TRUE) {
		include('connect.php');
		$version = 0;
		//-------Reccuperation version en cours--------
		$reponse = $bdd->prepare("SELECT * FROM memo WHERE id_memo = 3");
        $reponse->execute(array());
       while ($donnees = $reponse->fetch())
         {
         $version = $donnees['valeur_memo']+1;
          }
		$reponse->closeCursor();

		//-------------UPDATE MEMO-------------
		$query_c = 'UPDATE memo SET valeur_memo = ? WHERE id_memo = 3';
		$q = $bdd->prepare($query_c);

		$q->execute(array($version));	

		$q->closeCursor();
	}

}

?>