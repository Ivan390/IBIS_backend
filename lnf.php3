<?php
	$inVar = $_POST['name1'];
	$weirdch = '/[^a-zA-Z]/';
	$spce = ' ';
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
		$stmnt = $mysqli->prepare("select $recID, genus, species, localNames from $inVar where mediaRefs = 'noinfo' or mediaRefs = '' or mediaRefs = 'noinfo:' order by genus") or die ($mysqli->error);
	}
	$stmnt->bind_result($recID, $gen,$spec,$Cnames) or die ($mysqli->error);
	$stmnt->execute() or die ($mysqli->error);
	while ($stmnt->fetch()){
	if ($gen == "" || $gen == "noinfo") {
		$gen = "noinfo";

	}
	if ($spec == "" || $spec == "noinfo") {
		$spec = "noinfo";

	}
	if ($Cnames == "" || $Cnames == "noinfo") {
		$Cnames = "noinfo";

	}
  	$recID; // =	preg_replace("$weirdch", "$spce", $recID );
		$gen =	preg_replace("$weirdch", "$spce", $gen );
		$spec =	preg_replace("$weirdch", "$spce", $spec);
		$Cnames =	preg_replace("$weirdch", "$spce", $Cnames );
		$returnList .= "$recID:$gen:$spec:$Cnames:@";
	}
	print "$returnList";
?>
