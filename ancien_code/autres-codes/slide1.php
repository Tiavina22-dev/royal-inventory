    <?php
//header("refresh: 20");//seconde
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>PING</title>
    <style>
.animated-heading {
    color: #00bcd4;
    font-family: arial;
    height: 22px;
    overflow: hidden;
    border-radius: 10px;
    padding: 0px 15px;
}
.txt{
    text-align: center;
    font-size: 11px;
    border-radius: 10px;
    display: block;
    line-height: 22px;
}
.txt:first-child{
    animation: spin 12s infinite;
}
@-webkit-keyframes spin {
    0%{
        -webkit-margin-top: 0;
    }
    16%{
        -webkit-margin-top: -22px;
    }
    33%{
        -webkit-margin-top: -44px;
    }
    50%{
        -webkit-margin-top: -68px;
    }
}
@keyframes spin {
    0%{
        margin-top: 0;
    }
    16%{
        margin-top: -22px;
    }
    33%{
        margin-top: -44px;
    }
    50%{
        margin-top: -68px;
    }
} 
</style>
</head>
<body>
<h1 class="animated-heading">
      <span class="txt">Heading One</span>
      <span class="txt">Heading Two</span>
      <span class="txt">Heading Three</span>
      <span class="txt">Heading Four</span>
</h1>
</body>

</html>