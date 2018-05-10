<?php
if ($_FILES['picture']){
	include ("IBISvars.inc");
	if (!$guest_acc){
		print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
	if ($mysqli->connect_error){
		die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
	}
	$contID = trim($_POST['contID']); 
	$cleanname = str_replace("$space", "$underscore", $name);
	$securityA = trim($_POST['secA']);
	$username = trim($_POST['userName']);
	$prefix = "reg";
	$uploadDate =  date('Y-m-d H:i:s');
	$messg = "";
	$email = trim($_POST['email']);
	$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
	if ($stmt->execute()){
		$stmt->bind_result($numFile) or die ("cannot bind parameters.");
		$stmt->fetch();
		$stmt->close();
	}
	$justname = $_FILES['picture']['name'];
	$extn = substr($justname, -4);
	$newName = $prefix.$numFile.$extn ;
	$uploadfile = $uploaddir.$newName ;
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	if(!$tmpFilePath){$fileList = $_POST['medRef'];}
	if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
		exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile"); // call convert to resize the image
	
		$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )");
		$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $serverpath) or die ("cannot bind parameters.");
		$MediaID = 0;
		$Type = "image";
		$filename = $newName;
		$fileList =$filename;
		$tags = $cleanname;
		$contribRef = $contID;
		$uploadDate =  $uploadDate;
		$uploaderType = "c";
		$serverpath = $serverRoot.$filename;
		$stmt2->execute();
		if ($stmt2->affected_rows == -1){
			$messg =  "IBIS Upload failed <br>";
		}
		$stmt2->close();
	}else {
		$messg = "File upload failed <br>";
	}
	$space = ' ';
	$undescore = '_';
	$role = "c";
	$email = $_POST['email']; 
	$mediaRef = $fileList;
	$regDate = $uploadDate;
	$securityQ = $_POST['secQ'];
	$passwd = trim($_POST['pssWD']);
	$stmt3 = $mysqli->prepare("UPDATE Contributers SET role='$role' , email='$email' , mediaRef='$mediaRef' , regDate='$regDate' , securityQ='$securityQ' , securityA='$securityA' , userName='$username' , passwrd='$passwd' WHERE ContribID='$contID'") or die ("cant prepare statement 3".$mysqli->error);
	$stmt3->execute();
	if ($stmt3->affected_rows == -1){
		$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/notokeydoke.png\"><span id=\"messSpan\">Something went wrong please check your connection</span>";
		}else{
			$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/okeydoke.png\"><span id=\"messSpan\">Data upload successful</span>";
		}
	$stmt3->close();
	$htmlhead = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="content-type" content="text/xml; charset=utf8" />
  		<title>IBIS Registration Confirmation</title>
		<script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
    	<script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
    	<script type="text/javascript">
		function clWin(){
			close();
		}
		</script>
  		<link rel="stylesheet"
	    	type="text/css"
      		href="http://192.168.43.132/ibis/BUreg.css"
    		>
	</head>
 	<body>
		<div id="allContainer" class="ac">
 			<div id="logo">
	    	<img id="logo_image" src="http://192.168.43.132/ibis/images/Logo1_fullsizetransp.png">
      </div>
      <p class="heading">Registration Update Confirmation</p>
      <div id="container">
			<div id="detail_fs">
		<input type="button" value="Close" onclick="clWin()" />';
	$htmlFoot ='</div></body></html>';
	print "$htmlhead"."$message". "$htmlFoot";
}else{
	print "no upload variable found";
}
/*
 function getUserName($inName, $secA){  // why is this here again ????
		  $nameArray = str_split($inName);
		  $secCount = strlen($secA);
		  $letVal = 0;
		  foreach ($nameArray as $letter){
		    $letVal =+ ord($letter);
		  }
		  $rcode = ceil(($secCount * $letVal)/10); 
		  $theUserName = "$inName" . "$rcode";
		  return $theUserName;
    }
    not using this in here cause you cant change your name, but it might be usefull later|^|
    */
?>
