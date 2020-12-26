<?php
/*
calculates the number of times a page has been viewed
*/
$stmtV2 = "";
include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
  print"just before the statement\n";
  $stmtV1 = $mysqli->prepare("SELECT recordID, viewCount FROM Views WHERE recordID='$recID'") or die ("could not prepare statement 1". $mysqli->error);
   print"just after the statement\n";
  $stmtV1->bind_result($recID, $viewC) or die ("could not bind");
  $stmtV1->execute() or die ("could not execute");
  $stmtV1->fetch() or die ("could not fetch". $stmtV1->error);
  $stmtV1->close();
  $viewC = $viewC + 1;
  if ($recID == ""){
  print "record number is empty\n";
		$stmtV2 = $mysqli->prepare("insert into Views (ViewID, recordID,viewCount, viewDate, recordNum) values (?,?,?,?,?)") or die ("Could not prepare statement 2". $mysqli->error);
		$stmtV2->bind_param('sssss', $viewid, $recordid, $viewcount, $viewdate, $recordnum);
		$viewid = 0;
		$recordid = $recID;
		$viewcount = $viewC;
		$viewdate = date('Y-m-d H:i:s');
		$recordnum = $recnum;
  }else {
  $stmtV2 = $mysqli->prepare("update Views set viewCount = '$viewC' where recordID = '$recID'") or die ("could not update table Views");
  $stmtV2->execute() or die ("could not execute statement 2");
  
  }
  
?>
