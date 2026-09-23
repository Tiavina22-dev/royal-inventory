<?php 
$string = "CD-1-ok";
$string = str_replace(array('-','/'), ' ', $string);
$string1 =  strtok($string,' ');
$string2 =  explode(' ',str_replace(array('-','/'), ' ', $string));
echo count($string2);
$string2 = end($string2);
echo $string1;
echo $string2;
?>
