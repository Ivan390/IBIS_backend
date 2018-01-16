<?php
/*
this script receives data from the Ratings module
it connects to IBISVotes
check to see if the page has been rated allready
if so..
 it updates the existing record by calculating the sum of the current score and the submitted score
if not it writes a new entry to the Votes table

then it connects to IBISprofiles
finds the record matching the submitted contribRef
updates the contributer score by doing the math

*/
$rating = $_POST['name1'];
$recNumber = $_POST['name2'];
$contribRef = $_POST['name3'];
$cat = $_POST['name4'];
if ($cat == "vegetables"){
$prefix = "veg";
$catTable = "Vegetables";
$fName = "VegetableID";
}
if ($cat == "animals"){
$prefix = "anim";
$catTable = "Animals";
$fName = "AnimalID";
}
if ($cat == "minerals"){
$prefix = "min";
$catTable = "Minerals";
$fName = "MineralID";
}

$recordID = "$prefix$recNumber";
$voteID = 0;
$newScore = 0;
include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
$stmnt1 = $mysqli->prepare("select score from Votes where recID = \"$recordID\"");
$stmnt1->bind_result($currScore);
$stmnt1->execute();
$stmnt1->fetch();
$stmnt1->close();

if (!$currScore){
$newScore = $rating;
 $messg = "no previous score";
 $stmnt2 = $mysqli->prepare("insert into Votes (VoteID, score, recID, recNum)values(?,?,?,?)") or die ('Connect Error ('. $mysqli->errno . ')' .$mysqli->error);
 $stmnt2->bind_param("ssss", $voteID, $rating, $recordID, $recNumber );
 $stmnt2->execute() or die ("could not execute statement 2");
 $stmnt2->close();
 $messg = "$messg : new score submited";
}else{
	$newScore = $currScore + $rating;
	$messg = "current score is $currScore\nnew score is $newScore\n";
}
$stmt2 = $mysqli->prepare("update Votes set score = \"$newScore\" where recID = \"$recordID\"") or die ($mysqli->error);
	$stmt3 = $mysqli->prepare("update $catTable set score = \"$newScore\" where $fName = \"$recNumber\"") or die ($mysqli->error);
	$stmt2->execute() or die ("could not execute update".$mysqli->error);
	$stmt2->close();
	$stmt3->execute() or die ("could not execute update $catTable".$mysqli->error);
	$stmt3->close();

$recData = "$recordID:$newScore";


print "$recData";
?>
