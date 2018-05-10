<?php
 include ("IBISvars.inc");
  if (!$guest_acc){
  	print "the include file was not included <br>";
  }
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS'); // connect to IBIS
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
	$val1 = array_key_exists('name1',$_POST)?$_POST['name1']: null;
	$val2 = array_key_exists('name2',$_POST)?$_POST['name2']: null;
	$val3 = array_key_exists('name3',$_POST)?$_POST['name3']: null;
	$val4 = array_key_exists('name4',$_POST)?$_POST['name4']: null;
	$val5 = array_key_exists('name5',$_POST)?$_POST['name5']: null;
	
	trim($val1);
	trim($val2);
	trim($val3);
	trim($val4);
	trim($val5);
	print "$val1, $val2, $val3, $val4, $val5";
?>
