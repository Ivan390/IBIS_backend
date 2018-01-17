<?php
// connect to database
	include ("IBISvars.inc");
	if (!$guest_acc){
		print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli->connect_error){
   	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
	$MainBackButton = '
 	<div id="pgButtons" class="littleDD">
  	<a href="/ibis/IBISmain.html" class="buttonclass littleDD">Back to Main Screen</a>
  </div>';
	print "$MainBackButton <br>";
	$fileMessg =""; 
	$fileError ="";
	$dataMessg ="";
	$dataError ="";
	if ($_POST['kingdom'] == "Minerals"){
		$prefix = "min";
		include ("IBISeditFunctions.php3");
	$thisMID= trim(array_key_exists('MineralID',$_POST)?$_POST['MineralID']:null);
	//$thisspecies = trim(array_key_exists('name',$_POST)?$_POST['name']:null);
	$stmt3a = "INSERT INTO MineralsEdits (MineralID, name, Mgroup, crystalSys, habit, chemForm, hardness, density, cleavage, fracture, streak, lustre, fluorescence, notes, origin, characteristics, uses, mediaRefs, contribRef, uploadDate, distrib, editComnt, origDate)
	 select MineralID, name, Mgroup, crystalSys, habit, chemForm, hardness, density, cleavage, fracture, streak, lustre, fluorescence, notes, origin, characteristics, uses, mediaRefs, contribRef, uploadDate, distrib, editComnt, origDate from Minerals where MineralID='$thisMID';";
	$st3aResult = $mysqli->query($stmt3a) or die ("could not copy to Edits table". $mysqli->error);
	$stmt3b = ("delete from Minerals where MineralID='$thisMID'");
	$st3bResult = $mysqli->query($stmt3b) or die ("could not  delete from Data table".$mysqli->error);
  $dataMessg = "Data received....processing will follow <br>";
	$stmt3 = $mysqli->prepare("INSERT INTO Minerals (MineralID, name, Mgroup, crystalSys, habit, chemForm, hardness, density, cleavage, fracture, streak, lustre, fluorescence, notes, origin, characteristics, uses, mediaRefs, contribRef, uploadDate, distrib, editComnt, origDate ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die ("could not prepare statement 3 <br>");
	$stmt3->bind_param('sssssssssssssssssssssss', $MineralID, $name, $Mgroup, $crystalSys, $habit, $chemForm, $hardness, $density, $cleavage, $fracture, $streak, $lustre, $fluorescence, $notes, $origin, $characteristics, $uses, $mediaRefs, $contribRef, $uploadDate,  $distribution, $editComnt, $origDate );
	$MineralID = trim(array_key_exists('MineralID',$_POST)?$_POST['MineralID']:null); 
	$name = trim(array_key_exists('name',$_POST)?$_POST['name']:null); 
	$Mgroup = trim(array_key_exists('Mgroup',$_POST)?$_POST['Mgroup']:null);
	$crystalSys = trim(array_key_exists('crystalSys',$_POST)?$_POST['crystalSys']:null);
	$habit = trim(array_key_exists('habit',$_POST)?$_POST['habit']:null);
	$chemForm = trim(array_key_exists('chemForm',$_POST)?$_POST['chemForm']:null);
	$hardness = trim(array_key_exists('hardness',$_POST)?$_POST['hardness']:null);
	$density = trim(array_key_exists('density',$_POST)?$_POST['density']:null);
	$cleavage = trim(array_key_exists('cleavage',$_POST)?$_POST['cleavage']:null);
	$fracture = trim(array_key_exists('fracture',$_POST)?$_POST['fracture']:null);
	$streak = trim(array_key_exists('streak',$_POST)?$_POST['streak']:null);
	$lustre = trim(array_key_exists('lustre',$_POST)?$_POST['lustre']:null);
	$fluorescence = trim(array_key_exists('fluorescence',$_POST)?$_POST['fluorescence']:null);
	$notes = trim(array_key_exists('notes',$_POST)?$_POST['notes']:null);
	$characteristics = trim(array_key_exists('characteristics',$_POST)?$_POST['characteristics']:null);
	$uses = trim(array_key_exists('uses',$_POST)?$_POST['uses']:null);
	//$mediaRefs = trim(array_key_exists('mediaRefs',$_POST)?$_POST['mediaRefs']:null);
	$distribution = trim(array_key_exists('distribution',$_POST)?$_POST['distribution']:null);
	$contributer_ID = trim(array_key_exists('contribRef',$_POST)?$_POST['contribRef']:null);
	$origDate = trim(array_key_exists('origDate',$_POST)?$_POST['origDate']:null);
	$fileList = trim($fileList, "\s:");
	$fileList = str_replace("::",":",$fileList);
	$fileList = "$fileList:";
	$mediaRefs = $fileList;
	$uploadDate = date('Y-m-d H:i:s');
	$editComnt = trim(array_key_exists('editComnt',$_POST)?$_POST['editComnt']:null);
	$stmt3->execute();
	if ($stmt3->affected_rows == -1){
// this means the transaction could nt be completed and I should put something 
// to deal with that condition.	
	}
	$stmt3->close();		
}
$htmlHead = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	  <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>
	    I B I S - Edit Result  
    </title>
    <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
     <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
     <script type="text/javascript" src="http://192.168.43.132/ibis/fileselect.js"></script>
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
      href="http://192.168.43.132/ibis/minedit.css"
    >
   </head>
   <body name="VegInputBody" onload="starttime()" >
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
        </div>
        <div id="detail_fs_min" class="littleDD" ></div>';
       
print "$htmlHead";
 
?>
