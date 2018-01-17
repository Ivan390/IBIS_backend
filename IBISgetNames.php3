<?php
include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>"; 
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }

$fileRef = $_POST['name1'];
$catVal = $_POST['name2'];
$dataT = "";
if ($catVal == "animal"){
	$dataT = "Animals";
	$gen = "genus";
	$spec = "species";
	$common = "localNames";
}
if ($catVal == "mineral"){
	$dataT = "Minerals";
	$gen = "name";
	$spec = "Mgroup";
	$common = "chemForm";
	
}
if ($catVal == "vegetable"){
	$dataT = "Vegetables";
	$gen = "genus";
	$spec = "species";
	$common = "localNames";
}


if ($fileRef){
	$stmnt1 = $mysqli->prepare("select $gen, $spec, $common from $dataT where mediaRefs like '%$fileRef%' ")or die ($mysqli->error);
	$stmnt1->execute() or die ($mysqli->error);
	$stmnt1->bind_result($Gen, $Spec, $Common) or die ($mysqli->error);
	$stmnt1->fetch();
	

	print "$Gen:$Spec:$Common";
}

?>
