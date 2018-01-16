<?php
/*
this script receives comments from the getDetails page
along with the contribRef and the recordID
this data is then written to the las editors profile record
*/
$contRef = $_POST['name1'];
$commnt = $_POST['name2'];
$recID = $_POST['name3'];
$comID = 0;
$messg = "$contRef : $commnt : $recID";
$uploadDate = date('Y-m-d H:i:s');
include ("IBISvars.inc");
 	if (!$guest_acc){
  		print "the include file was not included <br>";
 	}
  	$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
 	if ($mysqli->connect_error){
    	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
  	$stmnt1 = $mysqli->prepare("INSERT into Comments (CommentID, comment, recID, contribRef, comntDate) VALUES (?,?,?,?,?)");
  	$stmnt1->bind_param("sssss",$comID,$commnt,$recID,$contRef, $uploadDate) or die ("could not bind parameters");
  	$stmnt1->execute();
  	$stmnt1->fetch();
  	$stmnt1->close();
  if ($stmnt1->affected_rows == -1){
  $messg = "record was not written";
  }else{
  $messg = "record was written successfully";
  }
 
  print "$messg";
?>
