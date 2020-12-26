<?php
/*
  so this is IBISlogin.php3
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

 include ("IBISvars.inc");
	if (!$guest_acc){
		print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }

  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
    // put a nicer message here later
  }
  if ($stmt = $mysqli->prepare("SELECT $fname, $passwd, $accslvl, serverpath FROM Contributers, Media WHERE userName='$Uname' and email='$Uemail' and Media.filename=Contributers.mediaRef")){
    $stmt->execute();
         $stmt->bind_result($Firstname, $password, $acclvl, $picpath);
        $stmt->fetch();
       if ($Firstname == "" ){
        print "no match found";
       }else {
       $picpath = str_replace("$imagesNotebookroot", "$imageshroot", $picpath);
        print $Firstname. " : " .$password. " : " .$Uname. " : " .$acclvl. " : ". $picpath ;
       }
       
      
  }
	//print $Uemail. " : ". $Uname;
/*
 select firstname, passwrd from Contributers where email="ivy3595@gmail.com" and user_name="Ivan66";
*/		
?>


