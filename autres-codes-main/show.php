<!DOCTYPE html>
<html>
<head>
	<title>stock</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">

</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>

<form action="show_stock.php" method="POST">
	<div class="text-center">
	<select class="btn" name="point_de_vente">
	<option value="Ambaibo_Electronique">Ambaibo_Electronique</option>
    <option value="Ambaibo_Quincaillerie">Ambaibo_Quincaillerie</option>
    <option value="Ambaibo_Tole">Ambaibo_Tole</option>
    <option value="Amparafa">Amparafa</option>
    <option value="Ambato_Tantely">Ambato_piece</option>
    <option value="Ambato_veve_photo">Ambato_veve_photo</option>
    <option value="Bejofo">bejofo</option>
	</select>
	<button type="submit" class="btn btn-success">Afficher</button>
	</div>
</form>

 
</body>