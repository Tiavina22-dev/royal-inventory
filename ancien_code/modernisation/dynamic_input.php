<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery Detect Change in Input Field</title>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function(){
   //var x = 2;

    $("#myInput").on("input", function(){
        // Print entered value in a div box
      var x = document.getElementById("id1").value;
      var v = document.getElementById("variable").value;
      var y = $(this).val();
      for (var i = 1; (i<=v) ; i++) {
        var name = "#result"+i;
        $(name).text(y*v);
      }
    });
});
</script>
</head>
<body>
  <p><input type="text" placeholder="Type something..." id="id1"></p>
    <p><input type="text" placeholder="Type something..." id="myInput"></p>
    <p><input type="hidden" placeholder="Type something..." id="variable" value=3></p>
    <div id="result1"></div>
    <div id="result2"></div>
    <div id="result3"></div>
</body>
</html>