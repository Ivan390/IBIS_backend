<?php
$htmlHead = '<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
      <title> Details</title>
      <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
      <script type="text/javascript" src="http://192.168.43.132/ibis/Gmain.js"></script>
	    <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>

      <link rel="stylesheet"
        type="text/css"
        href="http://192.168.43.132/ibis/IBIS_maincss.css"
      />
      <script type="text/javascript">
	 			$(document).ready(function(){
	 	 	$(\'#regPic\').change(handleFileSelect);	
	 	})
	 </script>
    </head> 
    <body onload=initForm()>
	    <div id="DETallContainer" class="ac">
        <div id="dateTime">
	        <div id="dateBlock">The Date</div>
	        <div id="timeBlock">The Time</div>       
	      </div>
	      <div id="logo_image_holder">
	        <img id="logo_image" src="/ibis/images/Logo1_fullsizetransp.png"  />
	      </div><div id="pgButtons" class="littleDD">
	        <a href="http://192.168.43.132/ibis/IBISmain.html" class="buttonclass littleDD"><img src="" alt="">Back to Main Screen</a></div>';
$htmlClose = '</body><html>';	
$userName = $_GET['userN'];
$userName = trim($userName);
include ("IBISvars.inc");
if (!$guest_acc){
	print("some thing went wrong. IBISvars missing");
}
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS' );
if ($mysqli->connect_error){
	die('Connect Error ('.$mysqli->connect_errno.')' .$mysqli->connect_error);
}

$stmnt1 = $mysqli->prepare("select name, lastname, userName, Media.serverpath, regDate, email from Contributers, Media where Contributers.ContribID = \"$userName\" and Contributers.mediaRef = Media.filename") or die ("could not prepare statement " . $mysqli->error);
$stmnt1->bind_result($fName, $lName, $username, $mediapath, $regDate, $emailA );     
$stmnt1->execute();
$stmnt1->fetch() ;
$stmnt1->close();
$Qtotal = 0;
#$stmnt2 = $mysqli->prepare("select sum(score) from Vegetables where contribRef = \"$userName\"");
$stmnt2 = $mysqli->prepare("select sum(Vegetables.score) from Vegetables where contribRef = \"$userName\" union select sum(Animals.score)  from Animals where contribRef = \"$userName\" union select sum(Minerals.score) from Minerals where contribRef = \"$userName\"") or die ("could not prepare statement " . $mysqli->error); 
#select sum(Animals.score) from Animals where contribRef = \"$userName\" select sum(Minerals.score) from Minerals where contribRef = \"$userName\"" ) ;
$stmnt2->bind_result($Qresult);
$stmnt2->execute();
while ($stmnt2->fetch()){
	$Qtotal = $Qtotal + $Qresult;
}
$stmnt2->close();

$contribScore = $Qtotal;
$mediapath = str_replace("$imagesfroot", "$imageshroot", $mediapath);
$mediapath = str_replace("$imagesdroot", "$imageshroot", $mediapath);
$mediapath = str_replace("$imagesNotebookroot","$imageshroot", $mediapath);
$adminDiv = "<div id=\"adminDiv\"><input type=\"button\" value=\"Edit your details\" onclick=\"loadRegWin()\" /><input type=\"button\" value=\"Dismiss\" onclick=\"closeRegWin()\" style=\"display:none;\" id=\"closewin\" /></div></div>";
$infoDiv = "<div id=\"informDiv\">
<span id=\"nameSpan\">$fName $lName </span></br><span id=\"imageSpan\"><img src=\"$mediapath\" class=\"optImage\"/></span><p>Your current score is</p><span id=\"scoreSpan\">$contribScore</span><input type=\"text\" name=\"userName\" value=\"$userName\" class=\"hiddentext\" id=\"userRef\"/>";
print("$htmlHead $infoDiv $adminDiv $htmlClose");
?>
