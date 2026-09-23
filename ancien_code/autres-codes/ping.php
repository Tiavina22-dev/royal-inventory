<!DOCTYPE html>
<html>
<head>
    <title>PING</title>
</head>
<body>
    <?php
    /* 
    $host ="192.168.0.105";
    exec("ping -c 1 ".$host, $outpout, $result);
    print_r($outpout);
    if ($result==0) {
        echo "success";
    } else {echo "unrecheable";}
    */
    //$ip ="192.168.0.105";
    ?>
    <script type="text/javascript">
        function ifServerOnline(ifOnline, ifOffline)
{
    var img = document.body.appendChild(document.createElement("img"));
    img.onload = function()
    {
        ifOnline && ifOnline.constructor == Function && ifOnline();
    };
    img.onerror = function()
    {
        ifOffline && ifOffline.constructor == Function && ifOffline();
    };
    img.src = "http://localhost/favicon.ico";        
}

ifServerOnline(function()
{
    //  server online code here
    alert('online');
},
function ()
{
    //  server offline code here
    alert('offline');
});
    </script>
</body>
</html>