<?php
include('connect.php');
//QUERY DESTINY FOR AUTO DETECT DUPLICATE
  $query_num_stock = $bdd->query('SELECT User_Name FROM user');
  $u = 0;
  $ref[0] = "";
  while ($data = $query_num_stock -> fetch())
{
  $u = $u + 1;
  $ref[$u] = $data['User_Name'];
  //echo $reference_x['reference_x'];
}
  //$u = 2;
  $query_num_stock ->closeCursor();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link rel="stylesheet" href="fontawesome/css/all.css">
		<link href="css/registration.css" rel="stylesheet" type="text/css">
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="css/popup.css">
	</head>
	<body>
		<div class="register">
		<h1 id="warning_msg">Inscription<?php if (isset($_COOKIE['msg_register'])){$msg=$_COOKIE['msg_register'];echo ' | <strong style="color: red">'.$msg.'</strong>';} ?></h1>
              <!-- Image loader -->
      <!-- Image loader -->
			<div class="content" autocomplete="off">
				<label for="full name">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="fullname" placeholder="Nom Complet" id="fullname" required>
				<input type="hidden" name="initials" placeholder="Fanahohizana Ex: RT" id="initials" value="RY">
				<input type="hidden" name="currentname" placeholder="Anarana Fiantsoana" id="currentname" value="Royal">
				<label for="department">
					<i class="fas fa-laptop"></i>
				</label>
				<select name="department" id="departemnt" required/>
    				<option value="Accounting">Aide Comptable</option>
    				<option value="Sailer">Vendeur/Vendeuse</option>
    				<option value="admin">Administrateur</option>
				</select>
				<label for="Nom d'utilisateur">
					<i class="fas fa-user"></i>
				</label>
				<div class="on-focus clearfix" style="position: relative; padding: 0px;  display: table; float: left">
				<input type="text" name="username" placeholder="Nom d'utilisateur" id="username" required>
				<div id="warning_msg2" class="tool-tip  slideIn"></div>
				</div>
				<label for="password1">
					<i class="fas fa-lock"></i>
				</label>
				<div class="on-focus clearfix" style="position: relative; padding: 0px;  display: table; float: left">
				<input type="password" name="password1" autocomplete="new-password" placeholder="Mot de passe" id="password1" required>
				<div id="warning_msgp1" class="tool-tip  slideIn"></div>
				</div>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<div class="on-focus clearfix" style="position: relative; padding: 0px;  display: table; float: left">
				<input type="password" name="password2" autocomplete="new-password" placeholder="Confirmé le Mot de passe" id="password2" required>
				<div id="warning_msgp2" class="tool-tip  slideIn"></div>
				</div>
				<label for="sex">
					<i class="fas fa-female"></i>
				</label>
				<select name="sex" id="sex" />
    				<option value="M">Male</option>
    				<option value="F">Female</option>
				</select>
        <div id="loader" style='background-color: transparent; display: none;'>
        <img src='img/loading1.gif' width='32px' height='32px'>
        LOADING....
      </div>
				<input type="submit" value="S'inscrire" onclick="savevalue();">
				Avoir un Compte? <a href="home.php"> Se connecter</a>
				<br/> <br/>
			</div>
		</div>
	<!------------------------SAVE TO DATABASE USING AJAX-------------------------->
	        <script>
        function savevalue()
        {		
                var fullname=document.getElementById("fullname").value;
                var sex=document.getElementById("sex").value;
                var initials=document.getElementById("initials").value;
                var currentname=document.getElementById("currentname").value;
                var departemnt=document.getElementById("departemnt").value;
                var username=document.getElementById("username").value;
                var password1=document.getElementById("password1").value;
                var password2=document.getElementById("password2").value;
                var password=password1;
                var action='Register';
                var url = "home.php";
                $("#loader").show();
                if (password1==password2) 
                {
                //alert(fullname+initials+currentname+departemnt+sex+username+password+action);
                $.ajax({
                    url:'edit_user.php',
                    method:'POST',
                    data:{
                        fullname:fullname,
                        initials:initials,
                        currentname:currentname,
                        departemnt:departemnt,
                        sex:sex,
                        username:username,
                        password:password,
                        action:action
                    },
                    
                   success:function(data){
                        setTimeout(function () {$("#loader").hide();}, 3000);
                   		if (getCookie("username_count")==0)
                   			{window.location = url;}
                   		else
                   			{$("#warning_msg").load(" #warning_msg");}
                   		//window.location = url;
                   		//var msg="khkjhsa";
                       //alert(msg);
                       //
                       //setTimeout(function () {$("#loader").hide();}, 3000); //Without 3s it not work

                   }
                   
                });
            	}
            else{alert("Password not conform");}
            //alert("End");
        };
     function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}; 

    </script>
    <script>
$(document).ready(function(){
    $("#username").on("input", function(){
    //----------------------
      var y = $(this).val();
          y = y.toUpperCase();
      var jArray = [];
      var u = <?php echo json_encode($u); ?>;
      var msg;
      var color;
      jArray = <?php echo json_encode($ref); ?>;
       document.getElementById("username").style.borderColor = "green";
      msg="Mety tsara";
      for(var i=0; i<=u; i++)
      { //alert(jArray[i]);
        jArray[i] = jArray[i].toUpperCase();

        if (jArray[i]==y) {
         msg="Tsy Mety";
        } else {
          //alert("nook");
          //color = "green";
          //msg="Efa Misy";
        }
        
      }
      //write notification on div id=warning_msg ;
      if (msg=="Tsy Mety") {
        color = "red";
      } else {
        color = "green";
        msg="Mety";
      }
      //$("#warning_msg").text(msg);
      $("#warning_msg2").text(msg);
      document.getElementById("username").style.borderColor = color;
      
      
    });
});
</script>
 <script>
$(document).ready(function(){
    $("#password1").on("input", function(){
    //----------------------
      var p1 = $(this).val();
      var msg;
      var color;
       document.getElementById("password1").style.borderColor = "green";
      msg="Mety tsara";
      //write notification on div id=warning_msg ;
      if (p1=="") {
        color = "red";
        msg="Tsy Mety";
      } else {
        color = "green";
        msg="Azo ekena";
      }
      //$("#warning_msg").text(msg);
      $("#warning_msgp1").text(msg);
      document.getElementById("password1").style.borderColor = color;
      
      
    });
});
</script>
 <script>
$(document).ready(function(){
    $("#password2").on("input", function(){
    //----------------------
      var p2 = $(this).val();
      var p1 = document.getElementById("password1").value;
      var msg;
      var color;
       document.getElementById("password2").style.borderColor = "green";
      msg="Mety tsara";
      //write notification on div id=warning_msg ;
      if (p2=="") {
        color = "red";
        msg="Tsy Mety";
      } else {
      	if (p1==p2)
      	{color = "green";
        msg="Mety Tsara";}
      	else
      	{color = "red";
        msg="Tsy Mitovy";}
      }
      //$("#warning_msg").text(msg);
      $("#warning_msgp2").text(msg);
      document.getElementById("password2").style.borderColor = color;
      
      
    });
});
</script>
	</body>
</html>