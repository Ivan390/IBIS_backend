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
 $MainBackButton = '<div id="pgButtons" class="littleDD">
         <a href="/ibis/IBISmain.html" class="buttonclass littleDD">Back to Main Screen</a>
        </div>';
// print "$MainBackButton <br>";
	if ($_POST['Akingdom'] == "Minerals"){
 	$BackButton = '
 		<div id="pgButtons" class="littleDD">
    	<a href="/ibis/IBISnewMinerals.html" class="buttonclass littleDD">Enter another</a>
    </div>';
 	$prefix = "min";
 	$fileList ="";
 include ("IBIScollectFunctions.php3");
  print "Data received....processing will follow <br>";
	$stmt3 = $mysqli->prepare("INSERT INTO Minerals (MineralID, name, Mgroup, crystalSys, habit, chemForm, hardness, density, cleavage, fracture, streak, lustre, fluorescence, notes, origin, characteristics, uses, mediaRefs, contribRef, uploadDate, distrib, origDate) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")or die ("could not prepare statement 3 <br>");
	$stmt3->bind_param('ssssssssssssssssssssss', $minID, $Mname, $Mgroup, $crystalsys, $habit, $chemform, $hardness, $density, $cleavage, $fracture, $streak, $lustre, $fluorescence, $notes, $origin, $characteristics, $Muses, $Mediarefs, $McontribRef, $Muploaddate, $distrib, $origDate );
	$minID = 0;
	$Mname = trim(array_key_exists('Mname',$_POST)?$_POST['Mname']: null); 
	$Mgroup = trim(array_key_exists('Mname',$_POST)?$_POST['Mgroup']: null);
	$crystalsys = trim(array_key_exists('crystSys',$_POST)?$_POST['crystSys']: null);
	$habit = trim(array_key_exists('Mhabit',$_POST)?$_POST['Mhabit']: null);
	$chemform = trim(array_key_exists('Mchemical_Formula',$_POST)?$_POST['Mchemical_Formula']: null);
	$hardness = trim(array_key_exists('Mhardness',$_POST)?$_POST['Mhardness']: null);
	$density = trim(array_key_exists('Mdensity',$_POST)?$_POST['Mdensity']: null);
	$cleavage = trim(array_key_exists('Mcleavage',$_POST)?$_POST['Mcleavage']: null);
	$fracture = trim(array_key_exists('Mfracture',$_POST)?$_POST['Mfracture']: null);
	$streak = trim(array_key_exists('Mstreak',$_POST)?$_POST['Mstreak']: null);
	$lustre = trim(array_key_exists('Mlustre',$_POST)?$_POST['Mlustre']: null);
	$fluorescence = trim(array_key_exists('Mfluorescense',$_POST)?$_POST['Mfluorescense']: null);
	$notes = trim(array_key_exists('Mnotes',$_POST)?$_POST['Mnotes']: null);
	$origin = trim(array_key_exists('Morigin',$_POST)?$_POST['Morigin']: null);
	$characteristics = trim(array_key_exists('Mcharacteristics',$_POST)?$_POST['Mcharacteristics']: null);
	$Mediarefs = $fileList;
	$origDate = $uploadDate;
	$Muploaddate = date('Y-m-d H:i:s');;
	$McontribRef =  trim(array_key_exists('contributer_ID',$_POST)?$_POST['contributer_ID']: null);
	$distrib = trim(array_key_exists('Mdistrib',$_POST)?$_POST['Mdistrib']: null);
	$Muses = trim(array_key_exists('Muses',$_POST)?$_POST['Muses']: null);
	$stmt3->execute();
	if ($stmt3->affected_rows == -1){
// this means the transaction could nt be completed and I should put something 
// to deal with that condition.		
		$dataError = "uploadError";
		$dataMessg = "This transaction could not be completed";
	}
	$stmt3->close();		
}
print "this is the end of the script<br>";
print "$MainBackButton $BackButton ";
//print "$fileMessg $fileError";
//print "$dataMessg $dataError";
?>
