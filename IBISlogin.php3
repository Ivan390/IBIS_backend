<?php
/*
   this is IBISlogin.php3
  collect the sent in values
  login to database and match email
  if not found { return error message}
  else {return greeting}
*/
  $Uemail = $_GET['uEmail'];
  $Uname = $_GET['uName'];
  $fname = "name";
  $passwd = "passwrd";
  $accslvl = "role";
  $userID = "";
  $serverA = "";
  
  //foreach ($serverList as $svItem){
  // $serverA .= "$svItem\n";
  //}
  $serverA = $_SERVER['REMOTE_ADDR'];
  $serverH= $_SERVER['HTTP_USER_AGENT'];
  //$serverList = "Host $serverH : Address $serverA";
 include ("IBISvars.inc");
	if (!$guest_acc){
		print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }

  if ($stmt = $mysqli->prepare("SELECT ContribID, $fname, $passwd, $accslvl, serverpath FROM Contributers, Media WHERE userName='$Uname' and email='$Uemail' and Media.filename=Contributers.mediaRef")){
    $stmt->execute();
         $stmt->bind_result( $uID, $Firstname, $password, $acclvl, $picpath);
        $stmt->fetch();
        $stmt->close();
       if ($Firstname == "" ){
        print "no match found";
       }else {
        $picpath = str_replace("$imagesNotebookroot", "$imageshroot", $picpath);
        
      
     
  
  $mysqli2 = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli2->connect_error){
   	die('Connect Error ('. $mysqli2->connect_errno . ')' .$mysqli2->connect_error);
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
   }else{
   $serverList = "upload not okay";
   }
   print "$uID::$Firstname::$password::$Uname::$acclvl::$picpath::$indate" ;
       }
   
   
      
       
      
  }
	//print $Uemail. " : ". $Uname;
/*
 select firstname, passwrd from Contributers where email="ivy3595@gmail.com" and user_name="Ivan66";
*/		
?>


