<?php // fake modal output handler
if (isset($_GET['fakeit'])){
    $random_robot =  md5(rand());
    die("<img src='https://robohash.org/$random_robot.png'>");
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My page</title>

    <!-- CSS dependencies -->
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
</head>
<body>

<p>
<ul id="my_list" >
    <?php foreach (range(1,5) as $post_id) {  // this is just to fake some post links ?>
        <li><a href="javascript:showModal(<?php echo $post_id; ?>);"  >See post <?php echo $post_id; ?></a></li>
    <?php } ?>
</ul>
</p>

<!-- JS dependencies -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- bootbox code -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
</html>
<?php
$SITE_URL = 'stock_recap_details.php'; // change this to your real site url
?>
<script>
    function showModal(id) {
        $.ajax({ // regular jQuery ajax call
            type: 'GET',
            url: '<?php echo $SITE_URL; ?>/posts.php?id=' + id,
            success: function (data) {
                bootbox.dialog({
                    message: data,
                    title: "Robot number :" + id,
                    buttons: { // here you can define your modal's buttons
                        button1: {
                            label: "Got it !",
                            className: "btn-default",
                            callback: function () {
                                console.log("Button 1 clicked !");
                                // code de be executed before closing the modal (when the user clicks the button)
                            }
                        }
                    },
                    size: "large"
                });
            }
        });
    }
</script>
</body>