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
 
	if ($_POST['Akingdom'] == "Animalia"){
	 
	 	$prefix = "anim";
	 	$fileList ="";
	 include ("IBIScollectFunctions.php3");
	 		
			//print "Data received....processing will follow <br>";
			$stmt3 = $mysqli->prepare("INSERT INTO Animals (AnimalID, phylum, subPhylum, class, subClass, Aorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, habits, ecology, distrib, status, uploadDate, mediaRefs, contribRef, origDate ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die ("could not prepare statement 3 <br>");
			$stmt3->bind_param('ssssssssssssssssssssssss', $AnimalID, $Aphylum, $AsubPhylum, $Aclass, $AsubClass, $Aorder, $Asuborder, $Afamily, $Asubfamily, $Agenus, $Asubgenus, $Aspecies, $Asubspecies, $Acommon_Names, $Aname_Notes, $Adescription, $Ahabits, $Aecology, $Adistrib_Notes,  $Astatus, $AuploadDate, $AmediaRefs,  $Acontributer_ID, $origDate );
			$AnimalID = 0;
			$Aphylum = trim(array_key_exists('Aphylum',$_POST)?$_POST['Aphylum']: null); 
			$AsubPhylum = trim(array_key_exists('AsubPhylum',$_POST)?$_POST['AsubPhylum']: null);
			$Aclass = trim(array_key_exists('Aclass',$_POST)?$_POST['Aclass']: null);
			$AsubClass = trim(array_key_exists('AsubClass',$_POST)?$_POST['AsubClass']: null);
			$Aorder = trim(array_key_exists('Aorder',$_POST)?$_POST['Aorder']: null);
			$Asuborder = trim(array_key_exists('subOrder',$_POST)?$_POST['subOrder']: null);
			$Afamily = trim(array_key_exists('Afamily',$_POST)?$_POST['Afamily']: null);
			$Asubfamily = trim(array_key_exists('subFamily',$_POST)?$_POST['subFamily']: null);
			$Agenus =  trim(array_key_exists('Agenus',$_POST)?$_POST['Agenus']: null);
			$Asubgenus = trim(array_key_exists('subGenus',$_POST)?$_POST['subGenus']: null);
			$Aspecies = trim(array_key_exists('Aspecies',$_POST)?$_POST['Aspecies']: null);
			$Aspecies = strtolower($Aspecies);
			$Asubspecies = trim(array_key_exists('subSpecies',$_POST)?$_POST['subSpecies']: null);
			$Acommon_Names = trim(array_key_exists('Acommon_Names',$_POST)?$_POST['Acommon_Names']: null);
			$Aname_Notes = trim(array_key_exists('Aname_Notes',$_POST)?$_POST['Aname_Notes']: null);
			$Adescription = trim(array_key_exists('Adescription',$_POST)?$_POST['Adescription']: null);
			$Ahabits = trim(array_key_exists('Ahabits',$_POST)?$_POST['Ahabits']: null);
			$Adistrib_Notes = trim(array_key_exists('Adistrib_Notes',$_POST)?$_POST['Adistrib_Notes']: null);
			$Acontributer_ID = trim(array_key_exists('contributer_ID',$_POST)?$_POST['contributer_ID']: null);
			$origDate = $uploadDate;
			$AuploadDate = $uploadDate;
			$Astatus = trim(array_key_exists('status',$_POST)?$_POST['status']: null);
			$AmediaRefs = $fileList;
			$stmt3->execute();
			if ($stmt3->affected_rows == -1){
	// this means the transaction could nt be completed and I should put something 
	// to deal with that condition.		
			}
			$stmt3->close();		
	}

$MainBackButton = '<div id="pgButtons" class="littleDD">
         <a href="http://192.168.43.132/ibis/IBISmain.html" class="buttonclass littleDD">Back to Main Screen</a>
        </div>';
$BackButton = '
	 	<div id="pgButtons" class="littleDD">
			<a href="http://192.168.43.132/ibis/IBISnewAnimals.html" class="buttonclass littleDD">Enter another</a>
		</div>';
$resultWinHead = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>
            I B I S - Uploads Form
        </title>
        <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
			  <link rel="stylesheet"
  				type="text/css"
    			href="http://192.168.43.132/ibis/DataResult.css"
   			>
    </head>
    <body id="AllContainer" class="ac" >
    '.$MainBackButton .$BackButton.'
		  <div id="subContainer">
		  <div id="resultsDiv"><img id="sucCheck" src="http://192.168.43.132/ibis/images/success.png">';
$resultWinFoot='</div>
		  </div>
    </body></html>';		  
//print "this is the end of the script<br>";
print "$resultWinHead.$resultWinFoot ";
//print "$fileMessg $fileError";
//print "$dataMessg $dataError";
 
 

?>
