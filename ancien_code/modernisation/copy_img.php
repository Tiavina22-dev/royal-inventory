<?php 
$directory = "img_x/";
$img = glob($directory."*.jpg");
foreach ($img as $img) {
	echo '<img src = "'.$img.'"/><br/>';
}
; ?>