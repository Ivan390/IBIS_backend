<?php
include ("IBISvars.inc");
$filename = "/var/www/html/ibis/Data/vegGlossary.txt";
$lineA = file($filename);
$uploadDate = date('Y-m-d H:i:s');
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
if ($mysqli->connect_error){
  die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
}
foreach ($lineA as $aline){
	$entryA = explode(":", $aline);
	$item = trim($entryA[0]);
	$defin = trim($entryA[1]);
	$table = "vegGlossary";
	$stmnt1 = $mysqli->prepare("INSERT INTO $table (glossID, item, definition, uploadDate, diagramref) VALUES (?,?,?,?,?)") or die ($mysqli->error);
	$stmnt1->bind_param('sssss',$theGloss, $theItem,$theDef,$uploadDate, $theDGref )or die ($mysqli->error);
	$theGloss = 0;
	$theItem = $item;
	$theDef = $defin;
	$theDGref = "no image";
	$uploadDate = $uploadDate;
	$stmnt1->execute()or die ($mysqli->error);
}
?>
