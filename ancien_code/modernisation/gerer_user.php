WW<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Home</title>
  <!-- Font Awesome -->
   <link rel="stylesheet" href="fontawesome/css/all.css">
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <link rel="stylesheet" href="css/w3.css">
    <script src="js/jquery.min.js"></script>
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<?php
include('connect.php');
if (isset($_COOKIE['msg'])) 
{
   $msg=$_COOKIE['msg'];
 } else {
   $msg = "";
 };
?>
<br>
<br>
<br>
<br>
<br>
<br>
<div id="Users">

<!-- ****************************** -->
  <?php
    $reponse = $bdd->prepare('SELECT * FROM user WHERE Permission="N" ORDER BY Date_Time DESC');
    $reponse->execute(array());
    $permission_count=$reponse->rowCount ();
  ?>
    
    <h3 class="text-center text-info">
        <?php  if ($permission_count==0) 
                {
                  #Hidde Text and permission button image (Show nothing)
        ?>
        <?php
                }

                else
                {
                  #Show Text and permission button image
          ?>
        NEW USER NEED PERMISSION (<?php echo $permission_count; ?>)
        <button  class="confirm btn btn-info" onclick="getcheckboxvalue6()">
        <img src="img\permission4.png" height="20" width="20";>
      Accept?
                      <?php

            }
          ?>

    </button>
    </h3>
  

          <?php  if ($permission_count==0) 
                {
                  #Caption table is hidden
                }

                else
                {
                  #Show table result
                ?>
  <div>
  <table class="table table-sm table-info">
    
    <tr>
    <td class="tg-fa13"><strong>Full Name</strong></td>
    <td class="tg-fa13"><strong>Current Name</strong></td>
    <td class="tg-fa13"><strong>Department</strong></td>
    <td class="tg-fa13"><strong>Username</strong></td>
    <td class="text-center"><input type="checkbox" class="selectall"/></td>
    </tr>
      <?php
      $classname="tg-0lax2a";
      while ($donnees = $reponse->fetch())
      {
        if ($classname=="tg-0lax2a") {
          $classname="tg-0lax2b";
        } else {
          $classname="tg-0lax2a";
        }
  

    ?>
    <tr>
    <td class=<?php echo $classname; ?>><?php echo $donnees['Full_Name']; ?></td>
    <td class=<?php echo $classname; ?>><?php echo $donnees['Current_Name']; ?></td>
    <td class=<?php echo $classname; ?>><?php echo $donnees['Departement']; ?></td>
    <td class=<?php echo $classname; ?>><?php echo $donnees['User_Name']; ?></td>
    <td style="text-align: center;" class=<?php echo $classname; ?>><input type="checkbox" class="justone" name="groupDelete6[]" value="<?php echo $donnees['Id_User']; ?>" id="groupDelete6" /></td>
   </tr>
  <?php
    }
    $reponse->closeCursor();
    
    ?>
  </table>
</div>
          <?php

            }
          ?>
<!--*********************************--->

  <form action="add_user_form">
    <h3 id="top3" class="text-center text-secondary">
      LIST OF USER
      <button type="submit" class="btn btn-secondary"><img src="img\add_icon.png" height="20" width="20">New User</button>
    </h3>
  </form>

  <?php
  include('connect.php');

  // Si tout va bien, on peut continuer

  // On récupère tout le contenu de la table material
  $reponse = $bdd->query('SELECT * FROM user ORDER BY Full_Name');

  // On affiche chaque entrée une à une
  ?>
<table class="table table-bordered table-secondary">
  <tr>
    <th>Full Name</th>
    <th >User Name</th>
    <th >Department</th>
    <th>Action</th>
    <th><input type="image" src="img/deleteicon2.png" height="30" width="30" background alt="Edit" id="submitGroupDelete1" onclick="getcheckboxvalue5()" /></th>
    <th class="tg-fa13">Top</th>
  </tr>

<?php

while ($donnees = $reponse->fetch())
{
  
?>
    <tr>
    <td ><?php echo $donnees['Full_Name']; ?></td>
    <td ><?php echo $donnees['User_Name']; ?></td>
    <td><?php echo $donnees['Departement']; ?></td>
    <td class="text-center">
      <a href="edit_user_form.php?id=<?php echo $donnees['Id_User']; ?>" onclick="post">
        <img src="img/edit_user_icon.png" height="30" width="30" alt="Edit" />
      </a>
    </td>
     <td class="text-center"><?php echo $donnees['Id_User']; ?></td>
    <td>
      <a href="#top3" >
        <img src="img/arrows.png" height="20" width="20" background alt="Edit" />
      </a>
    </td>
   </tr>
<?php
}

$reponse->closeCursor(); // Termine le traitement de la requête

?>
</table>
<br>
<br>
<br>
</div>
  <!--- Ajax CKECKBOX ALL------------------>
 <script type="text/javascript">
      $('.selectall').click(function() {
        if ($(this).is(':checked')) {
          $('input:checkbox').prop('checked', true);
        } else {
          $('input:checkbox').prop('checked', false);
        }
    });
  $("input[type='checkbox'].justone").change(function(){
        var a = $("input[type='checkbox'].justone");
        if(a.length == a.filter(":checked").length){
            $('.selectall').prop('checked', true);
        }
        else {
            $('.selectall').prop('checked', false);
        }
  });
      
    </script>
  <!--- Ajax DELETE MULTIPLE IN USER TABLE USING CHECKBOX FUNCTION------------------>
    <script>
      
      function getcheckboxvalue5()
      {
        //function to get checked value as array;
        var user_id_array = (function(){
          var a = [];
          $("#groupDelete5:checked").each(function(){
            a.push(this.value);
          });
          return a;})()

        //alert(history_id_array.length); //(get array count in Javascript)

        if ((user_id_array.length)>0) 
          {
            var result5 = confirm('Do you want to DELETE line ID '+user_id_array+'?');
            if (result5)
            {
              //alert(user_id_array+" will be deleted");
                      $.ajax
                      ({
                      url:'delete_user_checkbox.php',
                      method:'POST',
                      data:{user_id_array:user_id_array},
                      success:function(data){
                        alert((user_id_array.length)+" lines succesffuly deleted");
                        //openCity(event, 'Materials');
                        location.reload(true);}
                    });
            }
            else
            {
            //alert("Canceled");
            }
          } 
        else 
          {
            alert("Please check the line you want to delete");
          }
      };
//--------------------------------------------------------------------
      function getcheckboxvalue6()
      {
        //function to get checked value as array;
        var permission_id_array = (function(){
          var a = [];
          $("#groupDelete6:checked").each(function(){
            a.push(this.value);
          });
          return a;})()

        //alert(history_id_array.length); //(get array count in Javascript)

        if ((permission_id_array)>0) 
          {
            var result6 = confirm('Do you want to give access to line ID '+permission_id_array+'?');
            if (result6)
            {
              //alert(history_id_array+" will be deleted");
                      $.ajax
                      ({
                      url:'give_permission.php',
                      method:'POST',
                      data:{permission_id_array:permission_id_array},
                      success:function(data){
                        alert((permission_id_array.length)+" permited");
                        location.reload(true);}
                    });
            }
            else
            {
            //alert("Canceled");
            }
          } 
        else 
          {
            alert("Please check a line");
          }
      };

  </script>

    <!---------------------------------------------->
  <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
</body>
</html>
