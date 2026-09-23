<html>
<head>
  <script type="text/javascript">
    window.onload = setInterval(clock,1000);

    function clock()
    {
    var d = new Date();
    
    var date = d.getDate();
    
    var month = d.getMonth();
    var montharr =["Jan","Feb","Mar","April","Mey","Jona","Jol","Aog","Sep","Okt","Nov","Des"];
    month=montharr[month];
    
    var year = d.getFullYear();
    
    var day = d.getDay();
    var dayarr =["Alahady","Alatsinainy","Talata","Alarobia","Alakamisy","Zoma","Sabotsy"];
    day=dayarr[day];
    
    var hour =d.getHours();
      var min = d.getMinutes();
    var sec = d.getSeconds();
  
    document.getElementById("date").innerHTML=day+" "+date+" "+month+" "+year;
    document.getElementById("time").innerHTML=hour+":"+min+":"+sec;
    }
  </script>
</head>

<body>
   <p id="date"></p>
   <p id="time"></p>

 </body>
</html>