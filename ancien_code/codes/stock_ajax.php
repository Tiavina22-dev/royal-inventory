<!DOCTYPE html>
<html>
<head>
	<title>stock</title>
	<!---add bootstrap css--->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">
  
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
</body>
<!--------------------------------------->
<div id="warning_msg">
			<?php
              if (isset($_COOKIE['msg_E'])) 
              {
                 echo $_COOKIE['msg_E'];
              }
              ?>

</div>
<form role="form" action="test_ref.php" method="POST">
<div class="text-center">
<input type="search" class="light-table-filter" id="reference_x" name="key_word" placeholder="Name/Code/Search">
<button type="submit"  class="btn btn-info">Search</button>
<button type="button" id="action" class="btn btn-success">Nouveau?</button>
</div>
</form>
------>
<!--- Ajax Hide/Show script--------------------->
        <script>
        $(document).ready(function(){


            $("#action").click(function(){
                var reference_x=$("#reference_x").val();
                $.ajax({
                    url:'test_ref.php',
                    method:'POST',
                    data:{
                        reference_x:reference_x
                    },
                    
                   success:function(data){
                       alert("Successfully Saved");
                       $("#warning_msg").load(" #warning_msg");
                       //setTimeout(function () {$("#loader").hide();}, 3000); //Without 3s it not work

                   }
                   
                });
            });
        });
    </script>
    <!---------------------------------------------->

<!-------------------------------------->
<!--Javascript--->
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</html>