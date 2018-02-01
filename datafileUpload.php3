<?php
include ("IBISvars.inc");
	if (!$guest_acc){
		print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  	if ($mysqli->connect_error){
   		die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
  
$goodList = "";
$fc = count($_FILES['dataF']['name']);
$uploadP = "/var/www/html/ibis/Data/";
$table = "";
for ($i = 0; $i < $fc; $i++){
	$fName = $_FILES['dataF']['name'][$i];
	$tmpF = $_FILES['dataF']['tmp_name'][$i];
	$newName = "$uploadP"."$fName";
	if ( move_uploaded_file("$tmpF", "$newName") ) {
		$lineA = file($newName);
		foreach ($lineA as $fieldsA){
		$rec = explode(",",$fieldsA);
		if ($rec[0] == "family"){
			continue;
		}
		if (strstr("$newName","plants")){
			$table = "Vegetables";
		}else{
			$table = "Animals";
		}
		$family = trim(ucfirst(strtolower($rec[0])));
		$gen = trim(ucfirst(strtolower($rec[1])));
		$spec = trim(strtolower($rec[2]));
		$Cnames = str_replace(":",",",trim(ucwords($rec[3]))); 	
		$haspic = "no";
		$checkQ = $mysqli->prepare("select count(species) from $table where genus = '$gen' and species = '$spec'") or die ($mysqli->error);
		$checkQ->bind_result($specC) or die ($mysqli->error);
		$checkQ->execute() or die ($mysqli->error);
		while ($checkQ->fetch()){
			if ($specC > 0){
			 continue;
			}else{
				$goodList .= "$family:$gen:$spec:$Cnames:$haspic:$table|@";
			}
		}
		$checkQ->close();
		}
	}else {
print "problem with $newName upload<br>";
}
$goodListA = explode("|@", $goodList);
	foreach ($goodListA as $gEntry ){
		if ($gEntry == ""){
			continue;
		}
		print "$gEntry</br>";
		$GL = explode(":", $gEntry);
		$datTable = "$GL[5]";
		$fm = "$GL[0]";
		$ge = "$GL[1]";
		$sp = "$GL[2]";
		$cn = "$GL[3]";
		$hi = "$GL[4]";
		//print "$datTable</br>";
		$stmnt = $mysqli->prepare("insert into $datTable (family, genus, species, localNames, hasImage) values (?,?,?,?,?)") or die ($mysqli->error);
		$stmnt->bind_param("sssss",$fm, $ge, $sp, $cn, $hi) or die ($mysqli->error);
		$stmnt->execute() or die ($mysqli->error);
		$stmnt->close();
		print "done</br>";
	}
}
?>
