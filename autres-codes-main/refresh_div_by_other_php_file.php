<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>REFRESH DIV</title>
</head>
<body>
<div id="msgs">Testing refresh every 5 seconds</div>
</body> 
<script src="js/jquery.js"></script>
<script type="text/javascript">
setInterval( refreshMessages, 5000 );
function refreshMessages()
{
    $.ajax({
        url: 'xprinter_state.php',
        type: 'GET',
        dataType: 'html'
    })
    .done(function( data ) {
        $('#msgs').html( data ); // data came back ok, so display it
        if (data=='MATY') {
            //Audio for xprinter
        var audio = new Audio('sound/beep.mp3');
        audio.play();
        }
        
    })
    .fail(function() {
        $('#msgs').prepend('Error retrieving new messages..'); // there was an error, so display an error
    });
}
</script>
</html>