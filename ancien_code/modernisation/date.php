<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>DATE CALCULATION</title>
	<!---add bootstrap css--->
	<script src="js/jquery-3.5.1.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<!---add other css--->
	<link href="css/style.css" rel="stylesheet">


</head>
<body>
 <?php include("header.php"); ?>
 <?php include("footer.php"); ?>
<!--------------------------------------->
<?php
$res_regex[1] = "";
$chaine="Journal du 2021/12/20";
$x = chr(35).'/0-9-';
//$y = '*';
preg_match("'([0-9]{2,4}[$x]{1,2}[0-9]{2,4}[$x]{1,2}[0-9]{2,4})'", $chaine, $res_regex);
$aff = $res_regex[1];
//RECHERCHE DANS UNE CHAINE
$chaine_2="Reduit/Retirer du 28/12/20";
//$x = chr(35).'/0-9-';
//$y = '*';
$searched ="";
preg_match("'([Reduit]{5})'", $chaine_2, $res_regex_1);
preg_match("'([Retirer]{6})'", $chaine_2, $res_regex_2);
if (isset($res_regex_1[1])) {
	$searched1 = $res_regex_1[1];
}
if (isset($res_regex_2[1])) {
	$searched2 = $res_regex_2[1];
}

echo "---------------------------------------------";
echo '<br>';
echo $searched1;
echo '<br>';
echo $searched2;
echo '<br>';
echo "---------------------------------------------";
echo '<br>';
//echo '---------------'.'<br>';
//echo chr(83).chr(65).chr(77).chr(85).chr(69).chr(76).'<br>';
//echo '<span>&#128516</span>';
//echo ord("❤").'<br>';
//echo '---------------'.'<br>';
echo '<br>';
//echo '<br>';
echo $aff;
echo '<br>';
$d1 = "31/12/20";
$d2 = "30/12/20";
if ($d1 < $d2) {
	echo "$d2 is higher";
} else {
	echo "$d1 is higher";
}

$v1 = "02/06/2011";    
$yankeeTimestamp = strtotime($v1);

// European format
//

$v2 = "06-02-2011";    
$euroTimestamp = strtotime($v2);

echo date("m/d/y", $yankeeTimestamp); // returns 02/06/2011
echo date("m/d/y", $euroTimestamp); // returns 02/06/2011

//---------------------------------
echo "<br><br><br>";
//$date_start = date_create("28/12/2018");
//$date_end = date_create("29/12/2018");
//---------------
$date_start = date_create("18-12-2018");
$date_end = date_create("19-12-2018");
//---------------
$diff=date_diff($date_start,$date_end);
$months = $diff->format("%m months");
$years = $diff->format("%y years");
$days = $diff->format("%d days");

echo $years .' '.$months.' '.$days;
//-------------------------------------
echo "<br><br><br>";
echo "Today is " . date("Y/m/d") . "<br>";
echo "Today is " . date("Y.m.d") . "<br>";
echo "Today is " . date("Y-m-d") . "<br>";
echo "Today is " . date("l");
//------------------------------------
$today = strtotime("14-01-2021");
	echo $today."<br>";
	$tomorrow = strtotime("15-01-2021");
	echo $tomorrow."<br>";
	$differance = $today - 86400;
	echo $differance."<br>";
	$d = getdate($differance);
	echo "John Lennon was born on " . $d["mday"] ." ". $d["month"] . " , " .$d["year"] . "<br / >";
	echo $d ['mday'].'-'.$d ['month'].'-'.$d ['year']."<br/>";
  	//echo $localTime;
$d = strtotime( "02-02-2021" );
// Displays "The year 2000 is a leap year."
echo "The year" . idate( "m", $tomorrow );
echo " is " . ( idate( "L", $d ) ? "" : "not" ) . " a leap year.<br/>";  
// Displays "The month in question has 29 days."
echo " The month in question has " . idate( "t", $d ) . " days.<br/>";
function numberBetween($varToCheck, $high, $low) {
if($varToCheck < $low) return false;
if($varToCheck > $high) return false;
return true;
}
if (numberBetween(20, 100, 20)) {
echo 'The number is in the range!';
} else {
echo 'The number is outside the range!';
}
echo '<br>----------- DIFFERENCE BETWEEN 2 DATE -------------<br>';
$timezone = date_default_timezone_get();
echo date("y-m-d H:i:s").'<br>';
echo 'ACTUAL Time zone : '.$timezone.'<br>';
date_default_timezone_set('Europe/Moscow');
$timezone = date_default_timezone_get();
echo 'CHANGING Time zone to: '.$timezone.'<br>';
$date_en = '22-06-29 17:10:10';
$today = date("y-m-d H:i:s");
echo $today.'<br>';
        $date_start = date_create($date_en);
        $date_end = date_create($today);
$diff=date_diff($date_start,$date_end);
//$days = $diff->format("%m");
$days = $diff->format("%a days");
$days = $diff->format("%y years %m months %d days %h hours %i minutes %s seconds");

//$diff=date_diff('22-01-01','22-01-02');
echo $days;
?>
<!--Javascript--->


<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script  src="js/function.js"></script>
<script  src="js/confirmation.js"></script>
</body>
</html>