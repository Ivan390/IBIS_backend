<?php 
	$mysqli = new mysqli('localhost', 'Ivan', 'matilda95', 'CANDAS');
	if ($mysqli->connect_error){
		die('Connect Error('. $mysqli->connect_errno .')' . $mysqli->connect_error);
		}
		
	print "cool buddy";	
?>
