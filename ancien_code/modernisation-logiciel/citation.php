<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Citation</title>
 
  <!-- Font Awesome -->
  
  <!-- Bootstrap core CSS -->
  
  <!-- Material Design Bootstrap -->
  
  <!-- Your custom styles (optional) -->
  
</head>
<body>
<?php
include('connect.php');
//RECUPERATION DU CITATION ACTIF
	#VENTE
	//Query to liste negatif product
    $query_update = "SELECT * FROM history WHERE type = 'citation' AND flag = 'ON' ORDER BY date_time DESC";
    $query_update = $bdd->prepare($query_update);
    $query_update->execute(array());
  	$donnees_v = $query_update -> fetch();
    $citation = $donnees_v['before_change'];
    $auteur = $donnees_v['details'];
  	$query_update -> closeCursor();
  	
?>
  <!-- Start your project here-->  
    <div class="container" style="font-weight: bold;background-image: linear-gradient(rgba(48, 63, 159, .9),rgba(62, 69, 81, .3),rgba(5, 255, 163, .9))">
      <div>
<!-----<a href="additional_analyse_cookie.php" title="VOIR PLUS DE DETAILS SUR L'ANALYSE">------>
            <blockquote class="text-center">
              <p><?php echo nl2br($citation);?></p>
              <footer class="blockquote-footer text-dark"><?php echo $auteur;?></footer>
            </blockquote>
<!-----</a>---->
      </div>  
    </div>


</body>
</html>
