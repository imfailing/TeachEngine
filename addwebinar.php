<?php
include 'settings.php';  
$topic = $_POST['topic']; 
$description = $_POST['description'];
$category = 1;
$date = $_POST['date']; 
$time = $_POST['time']; 		
$author = $_COOKIE['id'];
$maxusers = $_POST['maxusers'];		
mysql_query("INSERT INTO session SET topic='".$topic."', description='".$description."', category='".$category."', author='".$author."', date='".$date."', time='".$time."', maxusers='".$maxusers."', state='3'"); 
?>