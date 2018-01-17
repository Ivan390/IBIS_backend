
<?php
include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
  	exit;
 	}
  $mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }

$dataT = $_POST['table'];
$medList = "";
$invalidC = 0;
$validC = 0;

if ($dataT == "Vegetables"){
	$dataID = "VegetableID";
	$prefix = "veg";
}
if ($dataT == "Animals"){
	$dataID = "AnimalID";
		$prefix = "anim";
}
if ($dataT == "Minerals"){
	$dataID = "MineralID";
		$prefix = "min";
}
for ($delC = 6; $delC < 10; $delC++){
$item = "min$delC.png";
	$delS = $mysqli->prepare("delete from Media where filename ='$item'") or die ($mysqli->error);
	$delS->execute();
	$delS->close();
}
/*
$stmnt1 = $mysqli->prepare("select filename, serverpath from Media where filename like '$prefix%'");

$stmnt1->execute();
$stmnt1->bind_result($flName, $sPath);
while($stmnt1->fetch()){
$medList .= "$flName:$sPath::";
}
$stmnt1->close();
$MentryA = explode("::",$medList );
$entryC = count($MentryA);
foreach ($MentryA as $medEntry){
	$medRec = explode(":",$medEntry);
	$fName = $medRec[0];
	$stmnt2 = $mysqli->prepare("select count($dataID) from $dataT where mediaRefs like '%$fName%'")or die ($mysqli->error);
	$stmnt2->execute();
	$stmnt2->bind_result($dataC);
	$stmnt2->close();
	if($dataC <= 0){
		$invalidC++;
	}else {
		$validC++;
	}
	
}
print "$entryC entries</br>$validC valid</br>$invalidC invalid";*/

?>
