<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>MESSAGING</title>
  <!-- Font Awesome -->
  <!--
  <link rel="stylesheet" href="css/all.css">
  -->
  <!-- Google Fonts Roboto -->
  <!--
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  -->
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Material Design Bootstrap -->
  <link rel="stylesheet" href="css/mdb.min.css">
  <!-- Your custom styles (optional) -->
  <!--
  <link rel="stylesheet" href="css/style.css">
  -->
  <link rel="stylesheet" href="css/mota.css">
  <link rel="stylesheet" href="css/w3.css">
  <style type="text/css">
  	.no-uppercase{text-transform: none;}
  </style>
</head>
<body>
<?php include("header.php"); ?>
<?php include("footer.php"); ?>
<!--------------------------------------->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
include('connect.php');
//Get actual user
if (isset($_SESSION['User_Name'])) 
{
  $username = $_SESSION['User_Name'];
  $Current_Id_User = $_SESSION['Id_User'];
}
else 
{
  //default pdp
  $username = "default";
}
//Query for chatable user
$reponse = $bdd->prepare("SELECT * FROM user WHERE (Departement ='comptable' OR Departement ='aide comptable') AND User_Name != ? ORDER BY Full_Name");
$reponse->execute(array($username));
//initialisation
$image_path = "img/default_img/default_pdp.png";
 ?>

<!-- actual form -->
<div class="flex-center">
<div class="btn btn-light animated fadeIn mb-3">
<form role="form" action="msg_send.php" enctype="multipart/form-data" method="post">
  <div class="form-group">
    <label>WRITE NEW MESSAGE</label>
  </div>
  
  <div class="form-group">
    <label>To</label>
    <select class="btn" name="destinataire">
    <?php
  $selected_all = "";
    if (isset($_COOKIE['selected'])) 
  {
    $selected = $_COOKIE['selected'];
  } else
  {
    $selected_all = "selected";
    $selected = "";
  }
    while ($donnees = $reponse->fetch())
	{
    if ($donnees['Id_User'] == $selected) {
      $selected = "selected";
    }
	?>
    <option value="<?php echo $donnees['Id_User'];?>" <?php echo $selected;?>><?php echo $donnees['Full_Name'];?></option>
    <?php
    if ($selected == "selected") {
      $selected = "";
    }
	}
	$reponse->closeCursor();
    ?>
      <option value="all" <?php echo $selected_all;?>>Everyone</option>
    </select>
  </div>
  <div class="form-group">
    <textarea name="msg" rows="8" cols="54"></textarea>
  </div>
  <div class="form-group">
  	<label>
    	<img src="img/attach.PNG" height="40" width="40" background alt="Edit" align="middle" />
		<input name="uploadedimage" type="file" style="display: none;" accept="image/*" onchange="loadFile1(event)">
	</label>
    <button type="submit" class="btn btn-success">SEND</button>
</div>
<div class="form-group">
	<img id="output"/>									  
</div>
</form>
</div>
</div>
<?php
$reponse = $bdd->prepare("SELECT * FROM chat INNER JOIN user ON (chat.to_id = user.Id_User) OR (chat.from_id = user.Id_User) WHERE User_Name = ? ORDER BY id_chat DESC");
$reponse->execute(array($username));
?>
<div class="flex-center" >
<div  class="text-center bg-light">
  <div class="form-group">
	<br>
    <label><b>MESSAGE</b></label>
  </div>
  <?php
  $u = 0;
  while ($donnees = $reponse->fetch())
	{
     
     if ($donnees['from_id'] == $Current_Id_User) {
       $q = $bdd->prepare('SELECT * FROM user WHERE Id_User = ?');
        $q->execute(array($donnees['to_id']));

       while ($data = $q->fetch())
         {
         $image_path = $data['img_path'].'';
         $from = $data['Full_Name'];
         $from_id = $data['Id_User'];
          }
      $q->closeCursor();
     } else {
		//GET PATH
		$q = $bdd->prepare('SELECT * FROM user WHERE Id_User = ?');
    $q->execute(array($donnees['from_id']));

       while ($data = $q->fetch())
         {
         $image_path = $data['img_path'];
         $from = $data['Full_Name'];
         $from_id = $data['Id_User'];
          }
		$q->closeCursor();
    }
		//Check for valide affichage
		$validation = "yes";
		//echo 'u = '.$u;
		//echo '<br>';
		for ($i=0; $i <= $u; $i++) { 
		//echo 'i = '.$i;
		//echo '<br>';
			if (isset($unused_id1[$i])) {
			//echo 'unused_id'.'------'.$unused_id[$i].'------'.$donnees['from_id'];
			//echo '<br>';
			if (($unused_id1[$i] == $donnees['from_id'] AND $unused_id2[$i] == $donnees['to_id']) OR ($unused_id1[$i] == $donnees['to_id'] AND $unused_id2[$i] == $donnees['from_id'])) {
				$validation = "no";

			}
			}
		}
		//echo $donnees['from_id'].'------'.$validation;
		//echo '<br>';
		if ($validation == "yes") {
			//GET NUMBER OF new msg
			$q = $bdd->prepare("SELECT * FROM chat WHERE from_id = ? AND to_id = ? AND status = 'new'");
	        $q->execute(array($from_id,$Current_Id_User));
	        $nb_new_msg = $q -> rowCount ();
	        if ( $nb_new_msg == 0) {
	        	 $nb_new_msg ='';
	        }

			$q->closeCursor();
			//-------------------------------
			$msg = $donnees['msg'];
			if ($donnees['status']=='deleted') {
				$msg = '<i>[Deleted]</i>';
			}
			if ($donnees['status']=='new') {
				$msg = '<b>'.$donnees['msg'].'</b>';
			}
  ?>
  <div class="form-group">
  	<button id="<?php echo $from_id; ?>" class="btn btn-info vu" style="width: 460px;" data-toggle="modal" data-target="#<?php echo ("msg".$u); ?>"><img class="float-left arrondi" src="<?php echo $image_path; ?>" height="90" width="90" background alt="Edit" /><?php echo $from; ?> <br> <?php echo $msg;
                                                //Time controle
                                          date_default_timezone_set('Europe/Moscow');
                                          $date_start = date_create($donnees['date_time']);
                                          $date_end = date_create(date("y-m-d H:i:s"));
                                          $diff=date_diff($date_start,$date_end);
                                          $mounth = $diff->format("%m");
                                          $day = $diff->format("%a");
                                          $hours = $diff->format("%h");
                                          $minutes = $diff->format("%i");
                                          $sec = $diff->format("%s");
                                          $aff = $diff->format("Il y a %a j");

                                            if ($day < 1 AND $mounth < 1) {
                                            $aff = $diff->format("Il y a %h h");
                                            }
                                            if ($day < 1 AND $mounth < 1 AND $hours < 1) {
                                            $aff = $diff->format("Il y a %i min");
                                            }
                                            if ($day < 1 AND $mounth < 1 AND $hours < 1 AND $minutes < 1) {
                                            $aff = "Maintenant";
                                            }

                                          //--------------------------
                                            echo "<br>".$aff;
    ?> <span class="w3-badge w3-right w3-margin-right w3-red"><p id="<?php echo 'noti'.$from_id; ?>"><?php echo $nb_new_msg; ?></p></span></button>
  </div>
		<!------------------------------------------->
 
                        <!-- modal form DETAILS-->
                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "msg".$u; ?>" class="modal fade">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title"><img class="dropdown-toggle arrondi" src="<?php echo $image_path; ?>" height="50" width="50" background alt="Edit" align="middle" /><b><?php echo $from; ?></b></h4>
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
                                    </div>
                                    <div class="modal-body">
                                                                        	<div class="bg-light animated fadeIn mb-3">
										<form role="form" enctype="multipart/form-data" action="msg_send.php" method="post">
										<div class="form-group">
										  </div>
										  <br>
										  <div class="form-group">
										    <textarea name="msg" class="form-control"></textarea>
										  </div>
										  <div class="form-group">
										  	<input type="hidden" name="destinataire" value="<?php echo $from_id; ?>">
										  	<label>
										    	<img src="img/attach.PNG" height="40" width="40" background alt="Edit" align="middle" />
										    	<input name="uploadedimage" id="<?php echo $u; ?>" type="file" style="display: none;" accept="image/*" onchange="loadFile(event)">
										    </label>
										    <button type="submit" class="btn btn-success">SEND</button>
										  </div>
										  <div class="form-group">
										  	<img id="<?php echo "preview".$u; ?>"/>
										  </div>
										</form>
										</div>
                                    <!-- actual form -->
                                    <table class="table bg-light">
                                    <?php
                                    //GET PATH
									$q = $bdd->prepare("SELECT * FROM chat WHERE (from_id = ? AND to_id = ?) OR (from_id = ? AND to_id = ?) ORDER BY id_chat DESC");
							        $q->execute(array($from_id,$donnees['Id_User'],$donnees['Id_User'],$from_id));

							        $y = 0;
							       while ($data = $q->fetch())
							         {
							         	$y = $y+1;
							         	if ($data['to_id']==$donnees['Id_User']) {
							         		$sms = $data['msg'];
							         		if ($data['status']=='deleted') {
							         			$sms = '<i>[Deleted]</i>';
							         		}
							        ?>
										<tr class="bg-info">
											<td class="text-left">
                                        <a href="delete_msg.php?id_chat=<?php echo $data['id_chat']; ?>" onclick="confirmationDelete('DELETE this MSG?');return false; post ;"><img class="dropdown-toggle arrondi" src="<?php echo $image_path; ?>" height="50" width="50" background alt="Edit" align="middle" /></a>
                                        </td>
                                        <td class="text-left no-uppercase">
                                        <?php echo $sms;
                                        if (strlen($data['path_photo'])>0) {
                                        ?>
                                        			<br>
                                        			<a href="#View" data-toggle="modal" data-target="#<?php echo "photo".$y; ?>">
                                        			<img src="<?php echo $data['path_photo']; ?>" height="200" width="200" background alt="Edit" align="middle" />
                                        			</a>
                                        			<!-------FORM DE AFFICHER PHOTO-------->
													    <!-- modal form AFFICHER PHOTO-->
													    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "photo".$y; ?>" class="modal fade">
													      <div class="modal-dialog">
													        <div class="modal-content">
													          <div class="modal-header">
													          <h4 class="modal-title">VIEW PHOTO</h4>
													          </div>
													          <div class="modal-body">
													          <!-- actual form -->
													          <form role="form" action="" enctype="multipart/form-data" method="post">
													            <div class="form-group text-center">
													              <img src="<?php echo $data['path_photo'];?>" height=100% width=100% align="middle"/>
													            </div>
													            <div class="form-group">
													            <a class="btn btn-danger float-right" href="delete_img_sms.php?id_chat=<?php echo $data['id_chat']; ?>">Remove Photo</a>
													            </div>
													          </form>
													          <!-- actual form ends -->
													          </div>
													        </div>
													      </div>
													    </div>
													<!-------------------------------------->
                                        			<?php
                                        		}
                                            //Time controle
                                          date_default_timezone_set('Europe/Moscow');
                                          $date_start = date_create($data['date_time']);
                                          $date_end = date_create(date("y-m-d H:i:s"));
                                          $diff=date_diff($date_start,$date_end);
                                          $mounth = $diff->format("%m");
                                          $day = $diff->format("%a");
                                          $hours = $diff->format("%h");
                                          $minutes = $diff->format("%i");
                                          $sec = $diff->format("%s");
                                          $aff = $diff->format("Il y a %a j");

                                            if ($day < 1 AND $mounth < 1) {
                                            $aff = $diff->format("Il y a %h h");
                                            }
                                            if ($day < 1 AND $mounth < 1 AND $hours < 1) {
                                            $aff = $diff->format("Il y a %i min");
                                            }
                                            if ($day < 1 AND $mounth < 1 AND $hours < 1 AND $minutes < 1) {
                                            $aff = "Maintenant";
                                            }

                                          //--------------------------
                                            echo "<br>".$aff;
                                        		?>
                                        </td>
                                        </tr>
							         <?php
							         	} else {
							         		$sms = $data['msg'];
							         		if ($data['status']=='deleted') {
							         			$sms = '<i>[Deleted]</i>';
							         		}
                                    ?>
                                        <tr>
											<td class="text-left no-uppercase">
												<a href="delete_msg.php?id_chat=<?php echo $data['id_chat']; ?>" onclick="confirmationDelete('DELETE this MSG?');return false; post ;">
                                        		<img class="dropdown-toggle" src="img/msg1.png" height="50" width="90" background alt="Edit" align="middle" />
                                        		</a>
                                        	</td>
                                        	<td class="text-right no-uppercase">
                                        		<?php echo $sms; 
                                        		if (strlen($data['path_photo'])>0) {
                                        			?>
                                        			<br>
                                        			<a href="#View" data-toggle="modal" data-target="#<?php echo "photo".$y; ?>">
                                        			<img src="<?php echo $data['path_photo']; ?>" height="200" width="200" background alt="Edit" align="middle" />
                                        			</a>
                                        			<!-------FORM DE AFFICHER PHOTO-------->
													    <!-- modal form AFFICHER PHOTO-->
													    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo "photo".$y; ?>" class="modal fade">
													      <div class="modal-dialog">
													        <div class="modal-content">
													          <div class="modal-header">
													          <h4 class="modal-title">VIEW PHOTO</h4>
													          </div>
													          <div class="modal-body">
													          <!-- actual form -->
													          <form role="form" action="" enctype="multipart/form-data" method="post">
													            <div class="form-group text-center">
													              <img src="<?php echo $data['path_photo'];?>" height=100% width=100% align="middle"/>
													            </div>
													            <div class="form-group">
													            <a class="btn btn-danger float-right" href="delete_img_sms.php?id_chat=<?php echo $data['id_chat']; ?>">Remove Photo</a>
													            </div>
													          </form>
													          <!-- actual form ends -->
													          </div>
													        </div>
													      </div>
													    </div>
													<!-------------------------------------->
                                        			<?php
                                        		}
                                            //Time controle
                                          date_default_timezone_set('Europe/Moscow');
                                          $date_start = date_create($data['date_time']);
                                          $date_end = date_create(date("y-m-d H:i:s"));
                                          $diff=date_diff($date_start,$date_end);
                                          $mounth = $diff->format("%m");
                                          $day = $diff->format("%a");
                                          $hours = $diff->format("%h");
                                          $minutes = $diff->format("%i");
                                          $sec = $diff->format("%s");
                                          $aff = $diff->format("Il y a %a j");

                                            if ($day < 1 AND $mounth < 1) {
                                            $aff = $diff->format("Il y a %h h");
                                            }
                                            if ($day < 1 AND $mounth < 1 AND $hours < 1) {
                                            $aff = $diff->format("Il y a %i min");
                                            }
                                            if ($day < 1 AND $mounth < 1 AND $hours < 1 AND $minutes < 1) {
                                            $aff = "Maintenant";
                                            }

                                          //--------------------------
                                            echo "<br>".$aff;
                                        		?>
                                        	</td>
                                        </tr>
                                    <?php
                                		}//else
                                       }
									$q->closeCursor();
                                    ?>
                                    </table>
                                    <!-- actual form ends -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------------->
  <?php
  		}
  			
        $unused_id1[$u] = $donnees['from_id'];
        $unused_id2[$u] = $donnees['to_id'];
  			//echo 'unused_id'.'------'.$unused_id[$u];
			//echo '<br>';
	         $u =$u+1;
  }
	$reponse->closeCursor();
  ?>
</div>
</div>
<!-- actual form ends -->
<br>
<br>
 <!-- jQuery -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Your custom scripts (optional) -->
  <script  src="js/confirmation.js"></script>

  <script type="text/javascript">
    $(".vu").click(function(){
        var from_id = $(this).attr("id");
        var Current_Id_User = <?php echo json_encode($Current_Id_User); ?>;

        
            $.ajax({
               url: 'vu_msg.php',

               method:'GET',
               data: {from_id: from_id,
                      Current_Id_User : Current_Id_User
                      },
               error: function() {
                  alert('Something is wrong');
               },
               success: function(data) {
                    //$("#"+id).remove();
                    //$(this).load(this);
                    $("#noti"+from_id).text("");
                    //alert("Record removed successfully"+Current_Id_User+from_id);  
               }
            });
    });

</script>
<script>
  var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('preview'+(event.target.id));
      output.height = "500";
      output.width = "500";
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
</script>
<script>
  var loadFile1 = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('output');
      output.height = "500";
      output.width = "500";
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };
</script>
</body>
</html>