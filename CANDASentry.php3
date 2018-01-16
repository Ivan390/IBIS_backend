<?php 
	$mysqli = new mysqli('localhost', 'Ivan', 'matilda95', 'CANDAS');
	if ($mysqli->connect_error){
		die('Connect Error('. $mysqli->connect_errno .')' . $mysqli->connect_error);
		}
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$clIP = $_SERVER['REMOTE_ADDR'];
	
		print "$browser</br>$clIP";
		
?>
