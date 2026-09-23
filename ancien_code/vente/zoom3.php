<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>JavaScript Increasing and Decreasing Image Size</title>
<style>
    button{
        padding: 3px 6px;
    }
    button img{
        vertical-align: middle;
    }
</style>
<script>
    function zoomin(){
        var myImg = document.getElementById("sky");
        var currWidth = myImg.clientWidth;
        if(currWidth == 500){
            alert("Maximum zoom-in level reached.");
        } else{
            myImg.style.width = (currWidth + 50) + "px";
        } 
    }
    function zoomout(){
        var myImg = document.getElementById("sky");
        var currWidth = myImg.clientWidth;
        if(currWidth == 50){
            alert("Maximum zoom-out level reached.");
        } else{
            myImg.style.width = (currWidth - 50) + "px";
        }
    }
</script>
</head>
<body>
    <p>
        <button type="button" onclick="zoomin()"><img src="img/zoom-in.png"> Zoom In</button>
        <button type="button" onclick="zoomout()"><img src="img/zoom-out.png"> Zoom Out</button>
    </p>
    <img src="img/sky.jpg" id="sky" width="250" alt="Cloudy Sky">
</body>
</html>