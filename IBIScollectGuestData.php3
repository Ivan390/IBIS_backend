<?php
$fileMessg ="no errors";
$fileError ="no errors";
$dataMessg ="no errors";
$dataError ="no errors";
$prefix = "guest";
$fileList ="";
$newName = "";
include ("IBISvars.inc");
if (!$guest_acc){
	print "the include file was not included <br>";
}
$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
if ($mysqli->connect_error){
   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
}
$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
if ($stmt->execute()){
	$stmt->bind_result($numFile) or die ("cannot bind parameters.");
	$stmt->fetch();
	$stmt->close();
	$numFile++;
	$justname = $_FILES['pictures']['name'];
	$tmpFilePath = $_FILES['pictures']['tmp_name'];
	$extn = substr($justname, -4);
	$tagstring = $_POST['imgtag'];
	$newName = $prefix.$numFile.$extn;
	$tagItem = explode(" : ", $tagstring);
	$justTag = $tagItem[1];
	$uploadfile = $uploaddir.$newName ;
	$tmpFilePath = $_FILES['pictures']['tmp_name'];
	if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
	   	exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile"); 
 		$fileList .= "$newName:";
 		$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )");
		$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $servPath) or die ("cannot bind parameters.");
		$MediaID = 0;
		$Type = "image";
		$filename = $newName;
		$tags = $justTag; //***This need to be evaluated....done
		$contribRef = trim($_POST['contribRef']);
		$uploaderType = "c";
		$uploadDate = date('Y-m-d H:i:s');
		$servPath = $uploadfile;
		$stmt2->execute();
		if ($stmt2->affected_rows == -1){
		}else{
		}
	 	} else {
		}
	 			
		}else {
		//	print "Statement did not execute <br>";
		}
	 	$stmt2->close();
		//	print "Data received....processing will follow <br>";
		$stmt3 = $mysqli->prepare("INSERT INTO Guestbook (Gcomment, entryDate, mediaRefs, Gname ) VALUES (?,?,?,?)") or die ( $mysqli->error);
		$stmt3->bind_param('ssss', $Gcomment, $entryDate, $mediaRefs, $gname);
		$gname = array_key_exists('gname',$_POST)?$_POST['gname']: null;
		$Gcomment = array_key_exists('Gcomment',$_POST)?$_POST['Gcomment']: null;
		$entryDate = $uploadDate; //date('Y-m-d H:i:s');
		$mediaRefs = $filename;
		$stmt3->execute();
		if ($stmt3->affected_rows == -1){
			$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/notokeydoke.png\"><span id=\"messSpan\">Something went wrong please check your connection</span>";
		}else{
			$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/okeydoke.png\"><span id=\"messSpan\">Data upload successful</span>";
		}
		$stmt3->close();		
			
$htmlHead = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	  <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>
	    I B I S - Edit Result  
    </title>
    <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
     <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
		 <script type="text/javascript">
	 		$(document).ready(function(){
	 	 		$(\'#mediaPic\').change(handleFileSelect);	
	 			if (sessionStorage.userRef){
	 	 	 		sessVar = sessionStorage.userRef;
	 	 	 		sesA = sessVar.split("::");
	 	 	 		conID = sesA[0];
	 	 	 		$("#contrib_ID").val(conID);
	 	 	 	}else {
	    			alert("You should really not be on this page!")
	    			document.location = "IBISmain.html";
	   	 		}
	 		})
	 	</script>
		  <link rel="stylesheet"
	    	type="text/css"
     		 href="http://192.168.43.132/ibis/animedit.css"
   		 >
   </head>
   <body name="ResultBody" onload="starttime()" >
	   <div id="allContainer" class="ac">
	  	<div id="dateTime">
	        <div id="dateBlock">The Date</div>
	        <div id="timeBlock">The Time</div>       
	    </div>
	    <div name="Heading">
	        <img id="logo_image" src="http://192.168.43.132/ibis/images/Logo1_fullsizetransp.png">
        	<p id="headingText">Entry Complete</p>
        </div>
        <div id="pgButtons" class="littleDD">
	        <a href="http://192.168.43.132/ibis/IBISmain.html" class="buttonclass littleDD"><img src="" alt="">Back to Main Screen</a>
	        <a href="http://192.168.43.132/cgi-bin/IBISnewGuest.php3" class="buttonclass littleDD"><img src="" alt="">Back to Guestbook</a>
        </div>
        <div id="detail_fs_min" class="littleDD" >'.$message.'</div></body></html>';
print "$htmlHead";
?>



