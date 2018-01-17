<?php
include ("IBISvars.inc");
if (!$guest_acc){
		print "the include file was not included <br>";
}	
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli->connect_error){
   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
  
  $Uid = $_POST['name1'];
  $inDate = $_POST['name2'];
  $outDate = date('Y-m-d H:i:s');
  $stmnt = $mysqli->prepare("update Login set outDate='$outDate' where contribRef = '$Uid' and inDate = '$inDate'")or die ($mysqli->error);
 if ($stmnt->execute()){
 $retM = "update went well";
  }else {
  	$retM ="update failed";
 }
	print "$retM";
?>		
