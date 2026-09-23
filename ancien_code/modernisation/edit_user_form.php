<?php

include('connect.php');

$id=$_GET['id'];
//$id=$_GET[id];

$result = $bdd->prepare('SELECT * FROM user WHERE Id_User= ?');


$result->execute(array($id));


for($i=0; $row = $result->fetch(); $i++){

?>
<head>
<!--
<meta name="viewport" content="width=device-width, initial-scale=1">
-->
<meta http-equiv="refresh" content="900;url=auto_logout" />

<!-- Include CSS FILE--> 
<link rel="stylesheet" type="text/css" href="css/style_Edit_User_Form.css">

</head>
<div class="tab">
  <button class="tablinks">Editing  User: <?php echo $row['Full_Name']; ?></button>
  <form action="index">
    <button class="close" style="position: absolute;right: 20;"> Close </button>
</form>
</div>

<div>
<form action="edit_user" method="POST">

<input type="hidden" name="memids" value="<?php echo $id; ?>" />

Full Name<br>

<input type="text" name="fullname" value="<?php echo $row['Full_Name']; ?>" /><br>

Initials<br>

<input type="text" name="initials" value="<?php echo $row['Initials']; ?>" /><br>

Current Name<br>

<input type="text" name="currentname" value="<?php echo $row['Current_Name']; ?>" /><br>
User name<br>

<input type="text" name="username" value="<?php echo $row['User_Name']; ?>" /><br>

Department<br>
<input type="text" name="departemnt" value="<?php echo $row['Departement']; ?>" /><br>
Sex<br>
<input type="text" name="sex" value="<?php echo $row['Sex']; ?>" /><br>
Note<br>
<input type="text" name="note" value="<?php echo $row['Note']; ?>" /><br>

<input type="submit" class="inputsave" name="action" value="Update Data" style="background-image: url('img/update_icon.png');border: none;background-repeat: no-repeat;background-size: 10% 100%;" onclick="confirmationDelete('Save Change?');return false;"/>
<input type="submit" class="inputdelete" name="action" value="Delete Data" style="background-image: url('img/delete_icon.png');border: none;background-repeat: no-repeat;background-size: 10% 100%;" onclick="confirmationDelete('Remove user from database?');return false;"/>
<input type="submit" style="background-image: url('img/cancel_icon.png');border: none;background-repeat: no-repeat;background-size: 10% 100%;" class="inputcancel" name="action" value="Cancel" onclick="confirmationDelete('Discard Change?');return false;"/>

</form>
</div>

<?php

	}

?>
<!-- funtion of confirmation dialog box -->
<script type="text/javascript">
function confirmationDelete(action)
{
   var conf = confirm(action);
   if(conf)
      window.location=anchor.attr("");
}

</script>