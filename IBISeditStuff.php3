<?php
$vegcss = 'vegedit.css';	// ************
$mincss = 'minedit.css';	// style sheets
$animcss = 'animedit.css';  // ************
$theCat = array_key_exists('thecat',$_REQUEST)?$_REQUEST['thecat']: null; // post variable from IBISgetDetails.php3
$theSpecies = array_key_exists('specref',$_REQUEST)?$_REQUEST['specref']: null; // post variable from IBISgetDetails.php3
$theGenus = array_key_exists('genref',$_REQUEST)?$_REQUEST['genref']: null; 
$imglistOptions = ""; // list that holds the mediarefs for the database query that returns the serverpath and tags
$recID = $_POST['recID'];
$qClose = ';';
$picList ="";
$theHeading = ucfirst($theCat);

function makePicList($mediaRefers){ // function that processes the mediaRefs returned from the database 
	global $mysqli, $imagesfroot, $imagesdroot, $imageshroot, $imagesNotebookroot; 
	$imglistOptions = "";
	$qClose = ';';
	$picListfunc ="";
	$mediaList = explode(":", $mediaRefers); // explode the list references into an array
	foreach ($mediaList as $mediaRef){ // loop through the array
		if ($mediaRef == ""){ // if the variable is empty continue with the next one
  			continue;
  		}
		$imglistOptions .= "filename='$mediaRef' or "; // construct the query's WHERE clause ...
	} 

  	$prestate = $imglistOptions.$qClose; // ...
  	$prestmt = str_replace(" or ;", ";", $prestate); // ...>
  	$stmtQ = "SELECT serverpath, tags FROM Media WHERE $prestmt"; // construct database query 
  	$stmt4 = $mysqli->prepare($stmtQ) or die ("cant call Media ".$mysqli->error ); 
  	$stmt4->bind_result($imgpath, $imgtag); 
  	$stmt4->execute();
  	while ($stmt4->fetch()){
    	$picListfunc .= "<img class=\"imgClass\" src=\"$imgpath\" title=\"$imgtag\" onclick=\"showImgOpts(this)\"/></nbsp>";
  	}
  	$picListfunc = str_replace("$imagesfroot", "$imageshroot", $picListfunc);
  	$picListfunc = str_replace("$imagesdroot", "$imageshroot", $picListfunc);
  	$picListfunc = str_replace("$imagesNotebookroot","$imageshroot", $picListfunc);
	return $picListfunc;
} // end of makePicList function


include ("IBISvars.inc");
 	if (!$guest_acc){
  		print "the include file was not included <br>";
 	}
  	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  	if ($mysqli->connect_error){
    	die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
	if ($theCat == "vegetables"){ // Vegetables editing process for getting existing dataset
		$styleSheet = $vegcss;
		$RECID = "VegetableID";
  		$stmt3 = $mysqli->prepare("SELECT VegetableID, phylum, subPhylum, class, subClass, Vorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, ecology, distrib, uses, growing, category, status, uploadDate, mediaRefs, contribRef  FROM Vegetables WHERE $RECID = $recID")or die("cannot prepare select statement");
  		$stmt3->bind_result($VegetableID, $phylum, $subPhylum, $class, $subClass, $Vorder, $subOrder, $family, $subFamily, $genus, $subGenus, $species, $subSpecies, $common_Names, $name_Notes, $description, $ecology, $distrib_Notes, $uses, $growing, $category, $status, $uploadDate, $mediaRefs, $contribRef)or die("cannot bind dataset result");	
  		$stmt3->execute();
  		$stmt3->fetch();
  		$stmt3->close();
  		$picList = makePicList($mediaRefs); // call function to resolve mediaRefs to filepaths
		$FormOutput = '
	<form  name="EditsForm" action="../../cgi-bin/IBISeditVegetables.php3" method="POST" enctype="multipart/form-data" class="">
		<fieldset id="dataForm" class="nothiddentext">
		<input type="text" id="kingdom" name="kingdom" class="hiddentext" value="Plantae">
		<input type="text" id="origdate" name="origDate" class="hiddentext" value="'.$uploadDate.'">
		<input type="text" id="VegetableID" name="VegetableID" class="hiddentext" value="'.$VegetableID.'">
		<input type="text" id="mediarefs" name="mediarefs" class="hiddentext" value="'.$mediaRefs.'">
		<span class="inputClass"><label class="labelClass">Phylum</label>
			<input type="text" id="Phylum" name="phylum" class="nothiddentext" value="'.$phylum.'"></span>
		<span class="inputClass"><label class="labelClass">sub-Phylum</label>
			<input type="text" id="subPhylum" name="subPhylum" class="nothiddentext" value="'.$subPhylum.'"></span>
		<span class="inputClass"><label class="labelClass">Class</label>
		  	<input type="text" id="Class" name="class" class="nothiddentext" value="'.$class.'"></span>
		<span class="inputClass"><label class="labelClass">sub-Class</label>
	  		<input type="text" id="subClass" name="subClass" class="nothiddentext" value="'.$subClass.'"></span>
		<span class="inputClass"><label class="labelClass">Order</label>
			<input type="text" id="Order" name="Vorder" class="nothiddentext" value="'.$Vorder.'"></span>
		<span class="inputClass"><label class="labelClass">sub-Order</label>
			<input type="text" id="subOrder" name="subOrder" class="nothiddentext" value="'.$subOrder.'"></span>
		<span class="inputClass"><label class="labelClass">Family</label>
			<input type="text" id="Family" name="family" class="nothiddentext" value="'.$family.'"></span>
		<span class="inputClass"><label class="labelClass">subFamily</label>
			<input type="text" id="sub-Family" name="subFamily" class="nothiddentext" value="'.$subFamily.'"></span>
		<span class="inputClass"><label class="labelClass">Genus</label>
			<input type="text" id="Genus" name="genus" class="nothiddentext" value="'.$genus.'"></span>
		<span class="inputClass"><label class="labelClass">sub-Genus</label>
			<input type="text" id="subGenus" name="subGenus" class="nothiddentext" value="'.$subGenus.'"></span>
		<span class="inputClass"><label class="labelClass">species</label>
			<input type="text" id="Species" name="species" class="nothiddentext" value="'.$species.'"></span>
		<span class="inputClass"><label class="labelClass">subSpecies</label>
			<input type="text" id="subSpecies" name="subSpecies" class="nothiddentext" value="'.$subSpecies.'"></span>
		<span class="inputClass"><label class="labelClass">Common Names</label>
			<input type="text" id="Common_Names" name="common_Names" class="nothiddentext" value="'.$common_Names.'"></span>
		<span class="inputClass"><label class="labelClass">name_Notes</label>
			<textarea id="Name_Notes" name="name_Notes" class="nothiddentext">'.$name_Notes.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Description</label>
			<textarea id="Description" name="description" class="nothiddentext">'.$description.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Ecology</label>
			<textarea id="Ecology" name="ecology" class="nothiddentext">'.$ecology.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Distribution</label>
			<textarea id="Distrib_Notes" name="distrib_Notes" class="nothiddentext">'.$distrib_Notes.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Uses</label>
			<textarea id="Uses" name="uses" class="nothiddentext">'.$uses.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Growing</label>
			<textarea id="Growing" name="growing" class="nothiddentext">'.$growing.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Category</label>
			<textarea id="category" name = "category" class="nothiddentext">'.$category.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Status</label>
			<textarea id="status" name = "status" class="nothiddentext">'.$status.'</textarea></span>
		<span class="inputClass"><label class="labelClass nothiddentext">Editing Comment</label>
			<textarea name="editComnt" id="editComnt" class="nothiddentext"></textarea></span>
		<span class="inputClass"><label class="labelClass hiddentext">Contributer Ref</label>
			<input type="text" name="contributer_ID" id="contrib_ID" class="hiddentext" value=""></span>
	</div>
	<div id="images" class="littleDD">
		<div id="oldImages" class="imgHolder">	
			<label class="labelclass">Existing Images</labelclass>'
			 .$picList.'
			<input type="text" class="hiddentext" id="imgCounter" size="10" value="" />
		</div>
		<div id="oldTags"></div>
		<div id="newImages">
 			<p class="labelclass">Add Media</p>
	  		<input type="file" name="ibismedia[]" id="mediaPic" class="littleDD" multiple />
	  	<div id="imgDisplay"><label class="labelclass">New Images</labelclass></div>
		<div id="optionsDsplay" class="littleDD" style="display : none;"></div> 
			<textarea name="newtagslist" id="newtagslist" class="hiddentext"></textarea>
			<textarea name="editedtagslist" id="editedtagslist" class="hiddentext"></textarea>
			<textarea name="imgDeletelist" id="imgDeletelist" class="hiddentext"></textarea>
		</div>
	</fieldset>
</form>';
}
if ($theCat == "animals"){  // Animals editing process for getting existing dataset
	$styleSheet = $animcss;
	$RECID = "AnimalID";
if (!$stmtAnim = $mysqli->prepare("SELECT AnimalID, phylum, subPhylum, class, subClass, Aorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, habits, ecology, distrib, status, uploadDate, mediaRefs, contribRef  FROM Animals WHERE $RECID = $recID")){
  	print $mysqli->error;
}
$stmtAnim->bind_result($AnimalID,$phylum, $subPhylum, $class, $subClass, $Aorder, $subOrder, $family, $subFamily, $genus, $subGenus, $species, $subSpecies, $common_Names, $name_Notes, $description, $habits, $ecology, $distrib_Notes, $status, $uploadDate, $mediaRefs, $contribRef) or die ("could not bind result");	
$stmtAnim->execute();
$stmtAnim->fetch();
$stmtAnim->close();
$picList = makePicList($mediaRefs); // call function to resolve mediaRefs to filepaths
$FormOutput = '
<form  name="EditsForm" action="../../cgi-bin/IBISeditAnimals.php3" method="POST" enctype="multipart/form-data" class="">
	<fieldset id="dataForm" class="nothiddentext">
		<input type="text" id="kingdom" name="kingdom" class="hiddentext" value="Animalia">
		<input type="text" id="AnimalID" name="AnimalID" class="hiddentext" value="'.$AnimalID.'">
		<input type="text" id="origdate" name="origDate" class="hiddentext" value="'.$uploadDate.'">
	  <input type="text" id="mediarefs" name="mediarefs" class="hiddentext" value="'.$mediaRefs.'">
		<span class="inputClass"><label class="labelClass">Phylum</label>
			<input type="text" id="Phylum" name="phylum" class="nothiddentext" value="'.$phylum.'"></span>
		<span class="inputClass"><label class="labelClass">subPhylum</label>
		 	<input type="text" id="subPhylum" name="subPhylum" class="nothiddentext" value="'.$subPhylum.'"></span>
		<span class="inputClass"><label class="labelClass">Class</label>
		  <input type="text" id="Class" name="class" class="nothiddentext" value="'.$class.'"></span>
		<span class="inputClass"><label class="labelClass">subClass</label>
		  <input type="text" id="subClass" name="subClass" class="nothiddentext" value="'.$subClass.'"></span>
		<span class="inputClass"><label class="labelClass">Order</label>
			<input type="text" id="Order" name="order" class="nothiddentext" value="'.$Aorder.'"></span>
		<span class="inputClass"><label class="labelClass">subOrder</label>
			<input type="text" id="subOrder" name="subOrder" class="nothiddentext" value="'.$subOrder.'"></span>
		<span class="inputClass"><label class="labelClass">Family</label>
			<input type="text" id="Family" name="family" class="nothiddentext" value="'.$family.'"></span>
		<span class="inputClass"><label class="labelClass">subFamily</label>
			<input type="text" id="sub-Family" name="subFamily" class="nothiddentext" value="'.$subFamily.'"></span>
		<span class="inputClass"><label class="labelClass">Genus</label>
			<input type="text" id="Genus" name="genus" class="nothiddentext" value="'.$genus.'"></span>
		<span class="inputClass"><label class="labelClass">subGenus</label>
			<input type="text" id="subGenus" name="subGenus" class="nothiddentext" value="'.$subGenus.'"></span>
		<span class="inputClass"><label class="labelClass">species</label>
			<input type="text" id="Species" name="species" class="nothiddentext" value="'.$species.'"></span>
		<span class="inputClass"><label class="labelClass">subSpecies</label>
			<input type="text" id="subSpecies" name="subSpecies" class="nothiddentext" value="'.$subSpecies.'"></span>
		<span class="inputClass"><label class="labelClass">Common Names</label>
			<input type="text" id="Common_Names" name="common_Names" class="nothiddentext" value="'.$common_Names.'"></span>
		<span class="inputClass"><label class="labelClass">Name Notes</label>
			<textarea id="Name_Notes" name="name_Notes" class="nothiddentext">'.$name_Notes.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Description</label>
			<textarea id="Description" name="description" class="nothiddentext">'.$description.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Habits</label>
			<textarea id="habits" name="habits" class="nothiddentext">'.$habits.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Ecology</label>
			<textarea id="Ecology" name="ecology" class="nothiddentext">'.$ecology.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Distribution</label>
			<textarea id="Distrib_Notes" name="distrib_Notes" class="nothiddentext">'.$distrib_Notes.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Status</label>
			<textarea id="Status" name="status" class="nothiddentext">'.$status.'</textarea></span>
		<span class="inputClass"><label class="labelClass nothiddentext">Editing Comment</label>
			<textarea name="editComnt" id="editComnt" class="nothiddentext"></textarea></span>
		<span class="inputClass"><label class="labelClass hiddentext">Contributer Ref</label>
			<input type="text" name="contributer_ID" id="contrib_ID" class="hiddentext" value=""></span>
	</div>
	<div id="images" class="littleDD">
		<div id="oldImages" class="imgHolder">	
			<label class="labelclass">Existing Images</labelclass>'
		 .$picList.'
		 <input type="text" class="hiddentext" id="imgCounter" size="10" value=""/>
		</div>
		<div id="oldTags"></div>
		<div id="newImages">
			<p class="labelclass">Add Media</p>
	  	<input type="file" name="ibismedia[]" id="mediaPic" class="littleDD" multiple />
	  	<div id="imgDisplay"><label class="labelclass">New Images</labelclass></div>
			<div id="optionsDsplay" class="littleDD" style="display : none;"></div> 
			<textarea name="newtagslist" id="newtagslist" class="hiddentext"></textarea>
			<textarea name="editedtagslist" id="editedtagslist" class="hiddentext"></textarea>
			<textarea name="imgDeletelist" id="imgDeletelist" class="hiddentext"></textarea>
		</div>
   </div>
	</fieldset>
</form>';
}		
if ($theCat == "minerals"){ // call function to resolve mediaRefs to filepaths
	$styleSheet = $mincss;
	$RECID = "MineralID";
	$stmtAnim = $mysqli->prepare("SELECT MineralID, name, Mgroup, crystalSys, habit, chemForm, hardness, density, cleavage, fracture, streak, lustre, fluorescence, notes, origin, characteristics, uses,  mediaRefs, contribRef, uploadDate, distrib  FROM Minerals WHERE $RECID = $recID") or die($mysqli->error);
	  $stmtAnim->bind_result($MineralID, $name, $Mgroup, $crystalSys, $habit, $chemForm, $hardness, $density, $cleavage, $fracture, $streak, $lustre, $fluorescence, $notes, $origin, $characteristics, $uses, $mediaRefs, $contribRef, $uploadDate, $distribution ) or die ("could not bind stuff ".$mysqli->error);	
	  $stmtAnim->execute();
	  $stmtAnim->fetch();
	  $stmtAnim->close();
	  print "$crystalSys</br>";
		$picList = makePicList($mediaRefs); // call function to resolve mediaRefs to filepaths
		$FormOutput = '
<form  name="EditsForm" action="../../cgi-bin/IBISeditMinerals.php3" method="POST" enctype="multipart/form-data" class="">
	<fieldset id="dataForm" class="nothiddentext">
		<input type="text" id="kingdom" name="kingdom" class="hiddentext" value="Minerals">
  	<input type="text" id="origdate" name="origDate" class="hiddentext" value="'.$uploadDate.'">
	  <input type="text" id="VegetableID" name="MineralID" class="hiddentext" value="'.$MineralID.'">
	  <input type="text" id="mediarefs" name="mediarefs" class="hiddentext" value="'.$mediaRefs.'">
		<span class="inputClass"><label class="labelClass">Name</label>
		  <input type="text" id="name" name="name" class="nothiddentext" value="'.$name.'"></span>
		<span class="inputClass"><label class="labelClass">Group</label>
		  <input type="text" id="Mgroup" name="Mgroup" class="nothiddentext" value="'.$Mgroup.'"></span>
		<span class="inputClass"><label class="labelClass">Crystal System</label>
			<input type="text" id="crystalSys" name="crystalSys" class="nothiddentext" value="'.$crystalSys.'"></span>
		<span class="inputClass"><label class="labelClass">Habit</label>
		  <input type="text" id="habit" name="habit" class="nothiddentext" value="'.$habit.'"></span>
		<span class="inputClass"><label class="labelClass">Chemical Formula</label>
			<input type="text" id="chemForm" name="chemForm" class="nothiddentext" value="'.$chemForm.'"></span>
		<span class="inputClass"><label class="labelClass">Hardness</label>
			<input type="text" id="hardness" name="hardness" class="nothiddentext" value="'.$hardness.'"></span>
		<span class="inputClass"><label class="labelClass">Density</label>
			<input type="text" id="density" name="density" class="nothiddentext" value="'.$density.'"></span>
		<span class="inputClass"><label class="labelClass">Cleavage</label>
			<input type="text" id=cleavage" name="cleavage" class="nothiddentext" value="'.$cleavage.'"></span>
		<span class="inputClass"><label class="labelClass">Fracture</label>
			<input type="text" id="fracture" name="fracture" class="nothiddentext" value="'.$fracture.'"></span>
		<span class="inputClass"><label class="labelClass">Streak</label>
			<input type="text" id="streak" name="streak" class="nothiddentext" value="'.$streak.'"></span>
		<span class="inputClass"><label class="labelClass">Lustre</label>
			<input type="text" id="lustre" name="lustre" class="nothiddentext" value="'.$lustre.'"></span>
		<span class="inputClass"><label class="labelClass">Fluorescence</label>
			<input type="text" id="fluorescence" name="fluorescence" class="nothiddentext" value="'.$fluorescence.'"></span>
		<span class="inputClass"><label class="labelClass">Notes</label>
			<input type="text" id="notes" name="notes" class="nothiddentext" value="'.$notes.'"></span>
		<span class="inputClass"><label class="labelClass">Origin</label>
			<textarea id="origin" name="origin" class="nothiddentext">'.$origin.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Characteristics</label>
			<textarea id="characteristics" name="characteristics" class="nothiddentext">'.$characteristics.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Uses</label>
			<textarea id="uses" name="uses" class="nothiddentext">'.$uses.'</textarea></span>
		<span class="inputClass"><label class="labelClass">Distribution</label>
			<textarea id="distribution" name="distribution" class="nothiddentext">'.$distribution.'</textarea></span>
		<span class="inputClass"><label class="labelClass hiddentext">mediaRefs</label>
			<textarea id="mediaRefs" name="mediaRefs" class="hiddentext">'.$mediaRefs.'</textarea></span>
		<span class="inputClass"><label class="labelClass hiddentext">contribRef</label>
			<input type="text" name="contributer_ID" id="contrib_ID" class="hiddentext" value=""></span>
		<span class="inputClass"><label class="labelClass nothiddentext">Editing Comment</label>
			<textarea name="editComnt" id="editComnt" class="nothiddentext"></textarea></span>
	</div>
	<div id="images" class="littleDD">
		<div id="oldImages" class="imgHolder">	
			<label class="labelclass">Existing Images</labelclass>'
		 .$picList.'
		 <input type="text" class="hiddentext" id="imgCounter" size="10" value=""/>
		</div>
		<div id="oldTags"></div>
		<div id="newImages">
		  <p class="labelclass">Add Media</p>
  	  <input type="file" name="ibismedia[]" id="mediaPic" class="littleDD" multiple />
  	  <div id="imgDisplay"><label class="labelclass">New Images</labelclass></div>
		  <div id="optionsDsplay" class="littleDD" style="display : none;"></div> 
		  <textarea name="newtagslist" id="newtagslist"class="hiddentext"></textarea>
		  <textarea name="editedtagslist" id="editedtagslist" class="hiddentext"></textarea>
		<textarea name="imgDeletelist" id="imgDeletelist" class="hiddentext"></textarea>
	  </div>
  </div>
</fieldset>
</form>';
}

// start of html template ->
$htmlHead = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	  <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>
	    I B I S - Edit '.$theHeading.'  
    </title>
    <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
    </div></div><script type="text/javascript" src="http://192.168.43.132/ibis/fileselect.js"></script>
      <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
		 <script type="text/javascript">
	 		$(document).ready(function(){
		 	 	$(\'#mediaPic\').change(handleFileSelect);	
		 		if (sessionStorage.userRef){
			 	 	 sessVar = sessionStorage.userRef;
			 	 	 sesA = sessVar.split("::");
			 	 	 conID = sesA[0];
			 	 	 $("#contrib_ID").val(conID);
			 	 	 var imgCount = document.images;
			 	 	 var counterV = $(".imgClass").length;
			 	 	 $("#imgCounter").val(counterV);
			 	}else {
					alert("You should really not be on this page!")
					document.location = "http://192.168.43.132/ibis/IBISmain.html";
				}
	 		})
	 	</script>
		<link rel="stylesheet"
	    	type="text/css"
      		href="http://192.168.43.132/ibis/'.$styleSheet.'"
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
         <p id="headingText">'.$theHeading.' Editing  Form</p>
        </div>
        <div id="pgButtons" class="littleDD">
	       	<input type="button" value="Submit Data" class="buttonclass littleDD" onclick="doEdSubmit()">   
	        <a href="http://192.168.43.132/ibis/IBISmain.html" class="buttonclass littleDD"><img src="" alt="">Back to Main Screen</a>
        </div>
        <div id="detail_fs_min" class="littleDD" >';
$htmlClose = '</body></html>';	 			
$htmlPage = $htmlHead.$FormOutput.$htmlClose;
print "$htmlPage";
?>
