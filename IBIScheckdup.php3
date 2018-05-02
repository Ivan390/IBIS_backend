<?php
	$retList = "";
	$Gspecies = array_key_exists('species',$_GET)?$_GET['species']: null;
	$DataTable = array_key_exists('catval',$_GET)?$_GET['catval']: null;
	$category =  array_key_exists('group',$_GET)?$_GET['group']: null;
	if ($category == "Vegetables"){
		$spec = "species";
		$gen = "genus";
		$ID = "VegetableID";
		$Cname = "localnames";
		}
	if ($category == "Animals"){
		$spec = "species";
		$gen = "genus";
		$ID = "AnimalID";
		$Cname = "localnames";
	}	
	if ($category == "Minerals"){
		$spec = "name";
		$gen = "chemForm";
		$ID = "MineralID";
		$Cname = "Mgroup";
	}
	include ("IBISvars.inc");
 	if (!$guest_acc){
	  	print "the include file was not included <br>";
	  	exit;
 	}
  	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  	if ($mysqli->connect_error){
    	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
	$stmt1 = $mysqli->prepare("SELECT $ID, $spec, $gen, $Cname, contribRef from $DataTable where $spec = '$Gspecies'") or die ("cant select data ". $mysqli->error);
	$stmt1->execute();
  	$stmt1->bind_result($recID, $genItem, $specItem, $contrRef, $commName);
  	while($stmt1->fetch()){
  		$retList .= "$recID:$genItem:$specItem:$contrRef:$commName::";
  	}
  	if ($retList == ""){
  		$retList = "nomatch:";
  	}
  print "$retList";
?>
