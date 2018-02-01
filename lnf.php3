<?php
	$inVar = $_POST['name1'];
	include ("IBISvars.inc");
 	if (!$guest_acc){
  		print "the include file was not included <br>"; 
 	}
  	$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  	if ($mysqli->connect_error){
    	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
	$returnList = "";
	if ($inVar == "Animals" || $inVar == "Vegetables"){		
		if ($inVar == "Animals"){
			$recID = "AnimalID";
		}
		if ($inVar == "Vegetables"){
			$recID = "VegetableID";
		}
		$stmnt = $mysqli->prepare("select $recID, genus, species, localNames from $inVar where mediaRefs = 'noinfo' or mediaRefs = '' order by genus") or die ($mysqli->error);
	}
	$stmnt->bind_result($recID, $gen,$spec,$Cnames) or die ($mysqli->error);
	$stmnt->execute() or die ($mysqli->error);
	while ($stmnt->fetch()){
		$returnList .= "$recID:$gen:$spec:$Cnames:@";
	}
	print "$returnList";
?>
