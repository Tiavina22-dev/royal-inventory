    <?php
//header("refresh: 20");//seconde
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>PING</title>
    <style type="text/css">
        table {
  border-collapse: collapse;
  width: 100%;
}

td,
th {
  border-bottom: 1px solid black;
}
    </style>
    
</head>
<body>
<table>
  <tr>
    <th>Website</th>
    <th>Status</th>
    <th>Uptime</th>
  </tr>
  <tr>
    <td>XPRINTER</td>
    <td>
      <label id="xprinterStatus"></label>
    </td>
    <td></td>
  </tr>
  <tr>
    <td>LAZA PC</td>
    <td>
      <label id="royalpcStatus"></label>
    </td>
    <td></td>
  </tr>
  <tr>
    <td>ELECRTONIQUE PC</td>
    <td>
      <label id="electroniquepcStatus"></label>
    </td>
    <td></td>
  </tr>
  <tr>
    <td>CANON PRINTER</td>
    <td>
      <label id="canonprinterStatus"></label>
    </td>
    <td></td>
  </tr>
</table>
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
</body>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
  //Refresh each 15s-------
    setInterval(function(){
        refreshAllSites('getscript');
    },15000);
  //-----------------------
</script>
    <script type="text/javascript">
var sites = {
  google: {
    url: "http://192.168.0.104",
    id: "xprinterStatus"
  },
  dummy: {
    url: "http://royal",
    id: "royalpcStatus"
  },
  jsfiddle: {
    url: "http://192.168.0.105",
    id: "electroniquepcStatus"
  },
  lorem: {
    url: "http://192.168.0.101",
    id: "canonprinterStatus"
  }
};

// Default
refreshAllSites('getscript');

function refreshAllSites(approach) {
  Object.keys(sites).forEach(function(name) {
    // Four approaches
    checkStatusUsingGetScript(sites[name]);
  });
}

// Approach 4: Using GetScript
function checkStatusUsingGetScript(site) {
  $.getScript(site.url + "?callback=?").done(function() {
    document.getElementById(site.id).innerHTML = "Up";
    document.getElementById(site.id).style.color = '#00FF00';
  }).fail(function() {
    document.getElementById(site.id).innerHTML = "Down";
    document.getElementById(site.id).style.color = 'red';
  });
}


// Other functions
    </script>
</html>