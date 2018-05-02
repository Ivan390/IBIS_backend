<?php
  $Uemail = $_GET['uEmail'];
  $Uname = $_GET['uName'];
  $fname = "name";
  $passwd = "passwrd";
  $accslvl = "role";
  $userID = "";
  $serverA = "";
  $Lindate ="";
  $Loutdate ="";
  $logID ="";
  $serverA = $_SERVER['REMOTE_ADDR'];
  $serverH= $_SERVER['HTTP_USER_AGENT'];
	include ("IBISvars.inc");
	if (!$guest_acc){
		print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
	if ($mysqli->connect_error){
	   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
	}
	$stmt = $mysqli->prepare("SELECT ContribID, $fname, $passwd, $accslvl, serverpath FROM Contributers, Media WHERE userName='$Uname' and email='$Uemail' and Media.filename=Contributers.mediaRef") or die ($mysqli->error);
	$stmt->bind_result( $uID, $Firstname, $password, $acclvl, $picpath);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$picpath = str_replace("$imagesNotebookroot", "$imageshroot", $picpath);
	if ($Firstname == "" ){
		print "no match found";
		exit;
	}else {
       	$mysqli2 = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  		if ($mysqli2->connect_error){
   			die('Connect Error ('. $mysqli2->connect_errno . ')' .$mysqli2->connect_error);
 		}
		$logcheck = $mysqli2->prepare("Select max(LoginID), max(inDate), max(outDate) from Login where contribRef = '$Uname'") or die ($mysqli2->error);
		$logcheck->bind_result($logID, $Lindate, $LoutDate) or die ($mysqli2->error);
		$logcheck->fetch();
		$logcheck->close();
        if (!$logID ){
           $newLog = $mysqli2->prepare("Insert into Login (LoginID, contribRef, inDate,browser, ipAddress ) values (?,?,?,?,?)") or die ($mysqli->error);
           $newLog->bind_param('sssss',$logID, $contref, $indate, $cHost, $cIP);
		   $logID = 0;
		   $contref = $uID;
		   $indate = date('Y-m-d H:i:s');
		   $cHost = $serverH;
		   $cIP = $serverA;
		   if ($newLog->execute()){
			   $serverList = "upload okay";
			   $lastID  = $newLog->insert_id;
		   }else{
		   		$serverList = "upload not okay";
		   }
		}else{
	  			if ($Loutdate<$Lindate){
		    		$Noutdate = $Lindate;
	  		 	 	$stmnt3 = $mysqli2->prepare("update Login set outDate='$Lindate' where LoginID='$logid'")or die ($mysqli2->error);
       				$stmnt3->execute()or die ($mysqli2->error);
       				$stmnt3->close();
       			}
			    $stmnt2 = $mysqli2->prepare("Insert into Login (LoginID, contribRef, inDate,browser, ipAddress ) values (?,?,?,?,?)") or die ($mysqli->error);
			   $stmnt2->bind_param('sssss',$logID, $contref, $indate, $cHost, $cIP);
			   $logID = 0;
			   $contref = $uID;
			   $indate = date('Y-m-d H:i:s');
			   $cHost = $serverH;
			   $cIP = $serverA;
			   if ($stmnt2->execute()){
			   		$serverList = "upload okay";
			   		$lastID  = $stmnt2->insert_id;
			   }else{
			   		$serverList = "upload not okay";
			   }
	  		}
  		}
print "$uID::$Firstname::$password::$Uname::$acclvl::$picpath::$lastID" ;
?>
