<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="content-type" content="text/xml; charset=utf8" />
  		<title>IBIS Registration Confirmation</title>
		<script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
    	<script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
  		<link rel="stylesheet"
	    	type="text/css"
      		href="http://192.168.43.132/ibis/Breg.css"
    		>
	</head>
 	<body>
		<div id="allContainer" class="ac">
 			<div id="logo">
	    	<img id="logo_image" src="http://192.168.43.132/ibis/images/Logo1_fullsizetransp.png">
      </div>
      <p class="heading">IBIS Registration Confirmation</p>
      <div id="pgButtons" class="littleDD">
	    	<a href="http://192.168.43.132/ibis/IBISmain.html" class="buttonclass littleDD"><img src="" alt="">Back to Main Screen</a>
	    </div>
		
		
		<div id="detail_fs">
		<div id="phpOutput" class="phpOut">
			
			<?php
				$fileList ="";
				include ("IBISvars.inc");
				if (!$guest_acc){
			    	print "the include file was not included <br>";
				}
				$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
				if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
				$filecount = count($_FILES['picture']['name']);
				$name = trim($_POST['name']); 
				$cleanname = str_replace("$space", "$underscore", $name);
				$securityA = trim($_POST['secA']);
				$username = getUserName("$cleanname", "$securityA");
				$prefix = "reg";
  				$email = trim($_POST['email']);
	  			$stmt6 = $mysqli->prepare("SELECT count(ContribID) FROM Contributers WHERE email='$email'");
  				$stmt6->execute();
			  	$stmt6->bind_result($emailCount);
				$stmt6->fetch();
				$stmt6->close();
				if ($emailCount > 0){
					$reason= "this email  <div class=Important>". $email . "</div> is allready registered</br>record not added.";
					$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/notokeydoke.png\"><span id=\"messSpan\">$reason</span>";
					print "$message";
					exit;
				}else{
    			$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
    			if ($stmt->execute()){
      				$stmt->bind_result($numFile) or die ("cannot bind parameters.");
      				$stmt->fetch();
      				$stmt->close();
      				for ($i = 0; $i < $filecount; $i++){
        				$numFile = $numFile + 1;
						$justname = $_FILES['picture']['name'];
						$extn = substr($justname, -4);
						$newName = $prefix.$numFile.$extn ;
        				$uploadfile = $uploaddir.$newName ;
        				$tmpFilePath = $_FILES['picture']['tmp_name'];
        				if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
        				exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile");
	  						$fileList = "$newName";
	  						$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )");
	  						$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $serverpath) or die ("cannot bind parameters.");
							$MediaID = 0;
							$Type = "image";
							$filename = $newName;
							$tags = $cleanname;
							$contribRef = $username;
							$uploadDate =  date("Ymd");
							$uploaderType = "c";
							$serverpath = $serverRoot.$filename;
							$stmt2->execute();
							if ($stmt2->affected_rows == -1){
								print "IBIS Upload failed <br>";
							}else{
							}
        				} else {
	 						print("File upload failed <br>");
        				}
      				}
    				}else {
     					print "this stupid did not execute";
    				}
    				$stmt2->close();
    				$stmt3 = $mysqli->prepare("INSERT INTO Contributers ( ContribID, role, name, lastname, email, mediaRef, regDate, securityQ, securityA, userName, passwrd ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)") or die ("cannot create statement.");
    				$stmt3->bind_param('sssssssssss', $ContribID, $role, $cleanname, $lastname, $email, $mediaRef, $regDate,$securityQ, $securityA, $username, $passwd) or die ("cannot bind parameters.");
				    $contribID = "";
				    $space = ' ';
    				$undescore = '_';
    				$role = "c";
				    $lastname = $_POST['lastname'];
    				$email = $_POST['email']; 
				    $mediaRef = $fileList;
				    $regDate = $uploadDate;
				    $securityQ = $_POST['secQ'];
				    $passwd = crypt($name,$username);
				    print "Your user name is <div class=Important>". $username ."</div></br>";
    				print "Your email is <div class=Important>". $email ."</div></br>";
    				$stmt3->execute();
    				if ($stmt3->affected_rows == -1){
     					
     					$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/notokeydoke.png\"><span id=\"messSpan\">$reason</span>";
    				}else{
			$message = "<img id=\"sucCheck\" src=\"http://192.168.43.132/ibis/images/okeydoke.png\"><span id=\"messSpan\">Data upload successful</span>";
		}
    					$stmt3->close();
  					}
  
				function getUserName($inName, $secA){
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
				print "<div>$message</div>";
				?>
			</div>
			</div>
			</div>
		</div>
	</body>
</html>
