<?php
	include ("IBISvars.inc");
	if (!$guest_acc){
			print "the include file was not included <br>";
	}	
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
	if ($mysqli->connect_error){
	   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
	}
	$logID = $_POST['name1'];
	$outDate = date('Y-m-d H:i:s');
	$stmnt = $mysqli->prepare("update Login set outDate='$outDate' where LoginID = '$logID'")or die ($mysqli->error);
	if ($stmnt->execute()){
		$retM = "update went well";
	}else {
		$retM ="";
	}
	print "$retM";
?>		
