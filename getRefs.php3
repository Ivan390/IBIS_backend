<?php
include ('IBISvars.inc');
$titleList ="";
$titles = "";
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS' );
if($mysqli->connect_error){
		die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
	}


$contrib = $_POST['name1'];
$stmnt = $mysqli->prepare("select distinct RefID, title from Sources") or die ($mysqli->error);
$stmnt->bind_result($refid,$titles) or die ($mysqli->error);
$stmnt->execute() or die ($mysqli->error);
while ($stmnt->fetch()){
	$titleList .= "$refid:*$titles:@";
}
$stmnt->close();
print "$titleList";
?>
