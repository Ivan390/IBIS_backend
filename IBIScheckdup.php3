<?php
/*
	this script checks for duplicate species/names entries in the database
	it gets called from the new data entry pages and returns the match if one is found, adefault string if not
	for now it works for the species entries i.e from the veg and animal input pages 
	but I still need to implement it on the name or mineral input page
*/

$stmt1 = "";
	$retList = "";
	$Gspecies = array_key_exists('species',$_GET)?$_GET['species']: null;
	$DataTable = array_key_exists('catval',$_GET)?$_GET['catval']: null;
	include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
  	exit;
 	}
  $mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
  if ($DataTable == "Animals" || $DataTable == "Vegetables"){
  $stmt1 = $mysqli->prepare("SELECT genus, species from $DataTable where species like '%".$Gspecies."%'");
  }else if($DataTable == "Minerals"){
  $stmt1 = $mysqli->prepare("SELECT name, chemForm from $DataTable where name like '%".$Gspecies."%'");
  }
  
  $stmt1->execute();
  $stmt1->bind_result($genItem, $specItem);
  while($stmt1->fetch()){
  	
  		$retList .= "$genItem:$specItem::";
  	}
  	if ($retList == ""){
  	$retList = "nomatch:";
  	}
  
  print "$retList";
?>
