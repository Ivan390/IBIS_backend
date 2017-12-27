<?php
/*
calculates the number of times a page has been viewed
*/
$stmtV2 = "";
$stmtV3 = "";


include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
  
  $stmtV1 = $mysqli->prepare("SELECT recordID, viewCount FROM Views WHERE recordID=?") or die ("could not prepare statement 1". $mysqli->error);
    $stmtV1->bind_param("s",$recID) or die ("could not bind parameter");
  
  $stmtV1->execute() or die ("could not execute"); 
  $stmtV1->bind_result($recoID, $viewC) or die ("could not bind");
   
  $stmtV1->fetch();
  $stmtV1->close();
  $viewC = $viewC + 1;
  if ($recoID == ""){
  		$stmtV2 = $mysqli->prepare("insert into Views (ViewID, recordID,viewCount, viewDate, recordNum) values (?,?,?,?,?)") or die ("Could not prepare statement 2". $mysqli->error);
		$stmtV2->bind_param('sssss', $viewid, $recordid, $viewcount, $viewdate, $recordnum);
		$viewid = 0;
		$recordid = $recID;
		$viewcount = $viewC;
		$viewdate = date('Y-m-d H:i:s');
		$recordnum = $recnum;
		$stmtV2->execute();
			if ($stmtV2->affected_rows == -1){
	// this means the transaction could nt be completed and I should put something 
	// to deal with that condition.	
	
			}
			$stmtV2->close();
  }else {
  $stmtV2 = $mysqli->prepare("update Views set viewCount = '$viewC' where recordID = '$recID'") or die ("could not update table Views");
  $stmtV2->execute() or die ("could not execute statement 2");
  
  }
  
  $stmtV3 = $mysqli->prepare("SELECT count($thespec) FROM $theTab WHERE $theField =?") or die ("could not prepare statement 1". $mysqli->error);
    $stmtV3->bind_param("s",$recnum) or die ("could not bind parameter");
  
  $stmtV3->execute() or die ("could not execute"); 
  $stmtV3->bind_result($edCount) or die ("could not bind");
   
  $stmtV3->fetch();
  $stmtV3->close();
?>
