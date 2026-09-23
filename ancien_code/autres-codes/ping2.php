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
    Ping server using
<select id="approachOptions">
    <option value="getscript">$.getScript()</option>
    <option value="ajax">AJAX</option>
    <option value="image">Image Trick</option>
    <option value="jsonp">JSONP</option>
</select>
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
    setInterval(function(){
        refreshAllSites('getscript');
    },15000);
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
    switch (approach) {
      case "ajax":
        // 1. Using AJAX (works only for the same origin)
        checkStatusUsingAJAX(sites[name]);
        break;

      case "image":
        // 2. Using Image trick (works only if 
        // the remote server returns image)
        checkStatusUsingImage(sites[name]);
        break;

      case "jsonp":
        // 3. Using JSONP (Doesn't work because server is an HTML page)
        checkStatusUsingJSONP(sites[name]);
        break;

      case "getscript":
        // 4. Using GetScript (Doesn't work for lorem pixel because its MIME type ('image/jpeg') is not executable.)
        checkStatusUsingGetScript(sites[name]);
        break;
    }
  });
}

// Approach 4: Using GetScript
function checkStatusUsingGetScript(site) {
  $.getScript(site.url + "?callback=?").done(function() {
    document.getElementById(site.id).innerHTML = "Up"
  }).fail(function() {
    document.getElementById(site.id).innerHTML = "Down"
  });
}

// Approach 3: Using JSONP
// Doesn't work as server returns an HTML page
function checkStatusUsingJSONP(site) {
  jsonp(site.url, function() {
    document.getElementById(site.id).innerHTML = "Up"
  }, function() {
    document.getElementById(site.id).innerHTML = "Down"
  });

  // Define JSONP
  function jsonp(url, success, fail) {
    var callbackName = 'jsonpCallback' +
      Math.round(10000 * Math.random());

    window[callbackName] = function(data) {
      console.log("calling callback");
      delete window[callbackName];
      document.body.removeChild(script);
      success(escape(data));
    }

    var script = document.createElement('script');
    script.src = url + "?callback=" + callbackName;
    script.onerror = fail;

    document.body.appendChild(script);
  }
}

// Approach 2: Using Image loading trick
// Only works if the remote server returns Image e.g. lorem pixel
// https://stackoverflow.com/a/5224638/351708
function checkStatusUsingImage(site) {
  var image = document.createElement("img");
  image.onload = function() {
    document.getElementById(site.id).innerHTML = "Up"
  };
  image.onerror = function() {
    document.getElementById(site.id).innerHTML = "Down"
  };

  image.src = site.url;
}


// Approach 1
// Using AJAX - It will only be successful in case of JSFiddle
// as for the rest of the sites it will throw CORS exception
function checkStatusUsingAJAX(site) {
  $.ajax(site.url).done(function() {
    document.getElementById(site.id).innerHTML = "Up"
  }).fail(function() {
    document.getElementById(site.id).innerHTML = "Down"
  });
};

// Other functions
var select = document.getElementById("approachOptions");
select.addEventListener("change", function(e) {
  refreshAllSites(this.value);
});
    </script>
</html>