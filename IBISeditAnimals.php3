<?php
// connect to database
	include ("IBISvars.inc");
	if (!$guest_acc){
	$IBISerror = "the include file was not included <br>";
	//	print "the include file was not included <br>";
	}
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  	if ($mysqli->connect_error){
   		die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
	//$MainBackButton = '<div id="pgButtons" class="littleDD"><a href="/ibis/IBISmain.html" class="buttonclass littleDD">Back to Main Screen</a></div>';
	//print "$MainBackButton <br>";
	$fileMessg =""; 
	$fileError ="";
	$dataMessg ="";
	$dataError ="";
	$tagCount = 0;
	if ($_POST['kingdom'] == "Animalia"){
		$prefix = "anim";
		include ("IBISeditFunctions.php3");
	//$thisspecies = trim(array_key_exists('species',$_POST)?$_POST['species']:null);
	$thisAID= trim(array_key_exists('AnimalID',$_POST)?$_POST['AnimalID']:null);
	$stmt3a = "INSERT INTO AnimalsEdits (AnimalID, phylum, subPhylum, class, subClass, Aorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, habits, ecology, distrib, status, uploadDate, mediaRefs, contribRef, editComnt, origDate )
	 select AnimalID, phylum, subPhylum, class, subClass, Aorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, habits, ecology, distrib, status, uploadDate, mediaRefs, contribRef, editComnt, origDate from Animals where AnimalID='$thisAID';";
	$st3aResult = $mysqli->query($stmt3a) or die ("could not copy to Edits table". $mysqli->error);
	$stmt3b = ("delete from Animals where AnimalID='$thisAID'");
	$st3bResult = $mysqli->query($stmt3b) or die ("could not  delete from Data table".$mysqli->error);
  $dataMessg = "Data received....processing will follow <br>";
	$stmt3 = $mysqli->prepare("INSERT INTO Animals (AnimalID, phylum, subPhylum, class, subClass, Aorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, habits, ecology, distrib, status, uploadDate, mediaRefs, contribRef,editComnt, origDate ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die ("could not prepare statement 3 <br>");
	$stmt3->bind_param('sssssssssssssssssssssssss', $AnimalID, $phylum, $subPhylum, $class, $subClass, $order, $suborder, $family, $subfamily, $genus, $subgenus, $species, $subspecies, $common_Names, $name_Notes, $description, $habits, $ecology, $distrib_Notes, $status, $uploadDate, $mediaRefs,  $contributer_ID, $editComnt, $origDate );
	$AnimalID = trim(array_key_exists('AnimalID',$_POST)?$_POST['AnimalID']:null); 
	$phylum = trim(array_key_exists('phylum',$_POST)?$_POST['phylum']:null); 
	$subPhylum = trim(array_key_exists('subPhylum',$_POST)?$_POST['subPhylum']:null);
	$class = trim(array_key_exists('class',$_POST)?$_POST['class']:null);
	$subClass = trim(array_key_exists('subClass',$_POST)?$_POST['subClass']:null);
	$order = trim(array_key_exists('order',$_POST)?$_POST['order']:null);
	$suborder = trim(array_key_exists('subOrder',$_POST)?$_POST['subOrder']:null);
	$family = trim(array_key_exists('family',$_POST)?$_POST['family']:null);
	$subfamily = trim(array_key_exists('subFamily',$_POST)?$_POST['subFamily']:null);
	$genus = trim(array_key_exists('genus',$_POST)?$_POST['genus']:null);
	$subgenus = trim(array_key_exists('subGenus',$_POST)?$_POST['subGenus']:null);
	$species = trim(array_key_exists('species',$_POST)?$_POST['species']:null);
	$subspecies = trim(array_key_exists('subSpecies',$_POST)?$_POST['subSpecies']:null);
	$common_Names = trim(array_key_exists('common_Names',$_POST)?$_POST['common_Names']:null);
	$name_Notes = trim(array_key_exists('name_Notes',$_POST)?$_POST['name_Notes']:null);
	$description = trim(array_key_exists('description',$_POST)?$_POST['description']:null);
	$ecology = trim(array_key_exists('ecology',$_POST)?$_POST['ecology']:null);
	$habits = trim(array_key_exists('habits',$_POST)?$_POST['habits']:null);
	$distrib_Notes = trim(array_key_exists('distrib_Notes',$_POST)?$_POST['distrib_Notes']:null);
	$contributer_ID = trim(array_key_exists('contributer_ID',$_POST)?$_POST['contributer_ID']:null);
	$origDate = trim(array_key_exists('origDate',$_POST)?$_POST['origDate']:null);
	$uploadDate = date('Y-m-d H:i:s');
	$status = trim(array_key_exists('status',$_POST)?$_POST['status']:null);
	$category = trim(array_key_exists('category',$_POST)?$_POST['category']:null);
	$fileList = trim($fileList, "\s:");
	$fileList = str_replace("::",":",$fileList);
	$fileList = "$fileList:";
	$mediaRefs = $fileList;
	$editComnt = trim(array_key_exists('editComnt',$_POST)?$_POST['editComnt']:null);
	$stmt3->execute();
	if ($stmt3->affected_rows == -1){
// this means the transaction could nt be completed and I should put something 
// to deal with that condition.	
// (expr) ? ValueIfTrue : ValueIfFalse ;

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
		 <script type="text/javascript">
	 	$(document).ready(function(){
	 	 	$(\'#mediaPic\').change(handleFileSelect);	
	 		if (sessionStorage.userRef){
	 	 	 sessVar = sessionStorage.userRef;
	 	 	 sesA = sessVar.split(":");
	 	 	 conID = sesA[1];
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
