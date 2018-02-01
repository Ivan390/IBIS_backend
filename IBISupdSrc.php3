<?php
include ("IBISvars.inc");
if (!$guest_acc){
	print "no IBISvars";
	
}
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
}
$recID = $_POST['name1'];
//print "$recID came to here";
$stmnt = $mysqli->prepare("select RefID, type, title, publshr, publAddr, publDate, ISBN, auth, editr, url, contribRef from Sources where RefID = $recID ") or die ($mysqli->error);
$stmnt->execute();
//print "$stmnt waas passed through";
$stmnt->bind_result($refID, $Type, $Title, $Publ, $PubAddr, $PublD, $isbn, $Auth, $Editr, $Url, $contrib) or die ($mysqli->error);
$stmnt->fetch() or die ($mysqli->error);
$retlist = "$Type:@$Title:@$Publ:@$PubAddr:@$PublD:@$isbn:@$Auth:@$Editr:@$Url:@$contrib:@$refID";
	print "$retlist";
?>
