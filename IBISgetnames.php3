<?php
	include ("IBISvars.inc");
	$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
	if ($mysqli->connect_error){
	  die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
	}
	$theName = $_POST['name1'];
	$table = $_POST['name2'];
	$entriesL = "";
	$item = "";
	$defin = "";
	if ($table == "vegetables"){
	 $table = "vegGlossary";
	}
	if ($table == "minerals"){
	 $table = "minGlossary";
	}
	if ($table == "animals"){
	 $table = "animGlossary";
	}
	$stmnt1 = $mysqli->prepare("SELECT distinct item, definition from $table where item like '$theName%' ") or die ($mysqli->error);
	$stmnt1->bind_result($item,$defin) or die ($mysqli->error);
	$stmnt1->execute() or die ($mysqli->error);
	while ($stmnt1->fetch()){
	 $define = trim($defin);
	 $define = str_replace("?", " ", $define);
		$entriesL .= "$item:*$define@|";
	}
	$stmnt1->close();
	print "$entriesL";
?>
