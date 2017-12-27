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
$rating = $POST[name1];
$recNumber = $POST[name2];
$contribRef = $POST[name3];
$category = $POST[name4];

include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }

print "data was sent";
?>
