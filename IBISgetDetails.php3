<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
      <title> Details</title>
      <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
      <script type="text/javascript" src="http://192.168.43.132/ibis/Gmain.js"></script>
	    <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>

      <link rel="stylesheet"
        type="text/css"
        href="http://192.168.43.132/ibis/IBIS_maincss.css"
      />
      
    
    </head> 
    <body onload=initForm()>
      <div id="DETallContainer" class="ac">
        <div id="dateTime">
	        <div id="dateBlock">The Date</div>
	        <div id="timeBlock">The Time</div>       
	      </div>
	      <div id="logo_image_holder">
	        <img id="logo_image" src="/ibis/images/Logo1_fullsizetransp.png"  />
	      </div> 
	      <div id=pgButtons>
          
     <input type=button id="rightBackButton" class="buttonclass" onclick="goBack()" value="Go Back"/>
     <input type="button" id="editDetails" class="buttonclass" onclick="editSub()" style="display:none;" value="Edit This Page"/>
     <a id="backButton" href="/ibis/IBISmain.html" class="buttonclass">Back to Main Page </a>
     
     
	      </div>

	      
<?php

$theCat = $_POST['catVal'];
$theSpecies = $_POST['speciesRef'];
$imglistOptions = "";
$qClose = ';';
$picList ="";
$imgpath = "";
$recID = "";
$recnum = "";
$viewC = 0;
$theTab = "";
$theField = "";
$edCount = 0;
$thespec = "";
  include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }

	if ($theCat == "vegetables"){
	$prefix = "veg";
	$theTab = "VegetablesEdits";
	$theField = "VegetableID";
	$thespec = "species";
  $stmt3 = $mysqli->prepare("SELECT VegetableID, phylum, subPhylum, class, subClass, Vorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, ecology, distrib, uses, growing, category, status, uploadDate, mediaRefs, contribRef, score  FROM Vegetables WHERE species='$theSpecies'");

  $stmt3->bind_result($vegID, $phylum, $subPhylum, $class, $subClass, $Vorder, $subOrder, $family, $subFamily, $genus, $subGenus, $species, $subSpecies, $common_Names, $name_Notes, $description, $ecology, $distrib_Notes, $uses, $growing, $category, $status, $uploadDate, $mediaRefs, $contribRef, $currScore );	
  $stmt3->execute();
  $stmt3->fetch();
  $stmt3->close();
  $recID = "$prefix$vegID";
  $recnum = $vegID;
 $mediaList = explode(":", $mediaRefs);
// print "<p>$recID</p>";
 foreach ($mediaList as $mediaRef){
   if ($mediaRef == ""){
     continue;
   }
 $imglistOptions .= "filename='$mediaRef' or "; 
 } 
  $prestate = $imglistOptions.$qClose;
  $prestmt = str_replace(" or ;", ";", $prestate);
  $stmtQ = "SELECT serverpath, tags FROM Media WHERE ".$prestmt;
  $stmt4 = $mysqli->prepare($stmtQ);
  $stmt4->bind_result($imgpath, $imgtag);
  $stmt4->execute();
  while ($stmt4->fetch()){
    $picList .= "<img class=\"imgClass\" src=\"$imgpath\" title=\"$imgtag\" />";
  }
   	 
   $picList = str_replace("$imagesfroot", "$imageshroot", $picList);
   $picList = str_replace("$imagesdroot", "$imageshroot", $picList);
   $picList = str_replace("$imagesNotebookroot","$imageshroot", $picList);
   print " <div id=\"detail_fs\" class=\"littleDD\"><div id=\"catHeading\" class=\"cathead\"><span class=\"italC\"> $genus $species</span></div>
<div id=fqnNameList>
  <div class=\"itemC\"><label class=\"labelClass\">Phylum</label>
  <p class=\"FQNname FQNC shortText\">$phylum</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Phylum</label>
  <p class=\"FQNname FQNC shortText\">$subPhylum</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Class</label>
  <p class=\"FQNname FQNC shortText\">$class</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Class</label>
  <p class=\"FQNname FQNC shortText\">$subClass</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Order</label>
  <p class=\"FQNname FQNC shortText\">$Vorder</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Order</label>
  <p class=\"FQNname FQNC shortText\">$subOrder</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Family</label>
  <p class=\"FQNname FQNC shortText\">$family</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Family</label>
  <p class=\"FQNname FQNC shortText\">$subFamily</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Genus</label>
  <p class=\"FQNname FQNC shortText\">$genus</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Genus</label>
  <p class=\"FQNname FQNC shortText\">$subGenus</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Species</label>
  <p class=\"FQNname FQNC shortText\">$species</p></div>
  <span class=\"itemC\"><label class=\"labelClass\">sub-Species</label>
  <p class=\"FQNname FQNC shortText\">$subSpecies</p></span>
  <div class=\"itemC longText\"><label class=\"labelClass\">Common Names</label>
  <p class=\"FQNname FQNC \">$common_Names</p></div>
</div>

<div id=\"suboD\">
<div id=\"otherDetails\">
      <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Name Notes</label>
    <p class=\"oDD shortText\">$name_Notes</p></span>
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Description</label>
    <p class=\"oDD shortText\">$description</p></span>
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Ecology</label>
    <p class=\"oDD shortText\">$ecology</p></span>
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Distribution</label>
    <p class=\"oDD shortText\">$distrib_Notes</p></span>
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Uses</label>
    <p class=\"oDD shortText\">$uses</p></span>
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Growing</label>
    <p class=\"oDD shortText\">$growing</p></span>    
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Category</label>
    <p class=\"oDD shortText\">$category</p></span>  
    <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Status</label>
    <p class=\"oDD shortText\">$status</p></span>
  </div>
  <div id=\"oDDOutput\" class=\"OutputVeg\"></div>
  </div>
  <div id=\"imgGrid\">$picList </div>
  <form name=\"editForm\" action=\"../../cgi-bin/IBISeditStuff.php3\" method=\"POST\" enctype=\"multipart/form-data\" class=\"hiddentext\">
    <input type=\"text\" name=\"thecat\" id=\"thecat\" class=\"nothiddentext\" value=\"$theCat\"/>
	  <input type=\"text\" name=\"specref\" id=\"specRef\" class=\"nothiddentext\" value=\"$theSpecies\"/>
	   <input type=\"text\" name=\"conRef\" id=\"conRef\" class=\"hiddentext\" value=\"$contribRef\"/>
	    <input type=\"text\" name=\"recID\" id=\"recID\" class=\"hiddentext\" value=\"$vegID\"/>
 </form>
  ";
	}
	if ($theCat == "animals"){
	$prefix = "anim";
	$theTab = "AnimalsEdits";
	$theField = "AnimalID";
	$thespec = "species";
  $stmt3 = $mysqli->prepare("SELECT AnimalID, phylum, subPhylum, class, subClass, Aorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, habits, ecology, distrib,  uploadDate, mediaRefs, contribRef, status, score  FROM Animals WHERE species='$theSpecies'");

  $stmt3->bind_result($animID, $phylum, $subPhylum, $class, $subClass, $order, $subOrder, $family, $subFamily, $genus, $subGenus, $species, $subSpecies, $common_Names, $name_Notes, $description, $habits, $ecology, $distrib_Notes, $uploadDate, $mediaRefs, $contribRef, $status, $currScore);	
  $stmt3->execute();
  $stmt3->fetch();
  $stmt3->close();
 $mediaList = explode(":", $mediaRefs);
 $recID = "$prefix$animID";
 $recnum = $animID;
 foreach ($mediaList as $mediaRef){
   if ($mediaRef == ""){
     continue;
   }
 $imglistOptions .= "filename='$mediaRef' or "; 
 } 
  $prestate = $imglistOptions.$qClose;
  $prestmt = str_replace(" or ;", ";", $prestate);
  $stmtQ = "SELECT serverpath, tags FROM Media WHERE ".$prestmt;
  $stmt4 = $mysqli->prepare($stmtQ);
  $stmt4->bind_result($imgpath, $imgtag);
  $stmt4->execute();
  while ($stmt4->fetch()){
    $picList .= "<img class=\"imgClass\" src=\"$imgpath\" title=\"$imgtag\"/>";
  }
   	 
   $picList = str_replace("$imagesfroot", "$imageshroot", $picList);
   $picList = str_replace("$imagesdroot", "$imageshroot", $picList);
   $picList = str_replace("$imagesNotebookroot","$imageshroot", $picList);
  
   print "<div id=\"detail_fs\" class=\"littleDD\"> <div id=\"catHeading\" class=\"cathead\"><span class=\"italC\"> $genus $species</span></div>
<div id=fqnNameList>
  <div class=\"itemC\"><label class=\"labelClass\">Phylum</label>
  <p class=\"FQNname FQNC shortText\">$phylum</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Phylum</label>
  <p class=\"FQNname FQNC shortText\">$subPhylum</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Class</label>
  <p class=\"FQNname FQNC shortText\">$class</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Class</label>
  <p class=\"FQNname FQNC shortText\">$subClass</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Order</label>
  <p class=\"FQNname FQNC shortText\">$order</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Order</label>
  <p class=\"FQNname FQNC shortText\">$subOrder</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Family</label>
  <p class=\"FQNname FQNC shortText\">$family</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Family</label>
  <p class=\"FQNname FQNC shortText\">$subFamily</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Genus</label>
  <p class=\"FQNname FQNC shortText\">$genus</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">sub-Genus</label>
  <p class=\"FQNname FQNC shortText\">$subGenus</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Species</label>
  <p class=\"FQNname FQNC shortText\">$species</p></div>
  <span class=\"itemC\"><label class=\"labelClass\">sub-Species</label>
  <p class=\"FQNname FQNC shortText\">$subSpecies</p></span>
  <div class=\"itemC longText\"><label class=\"labelClass\">Common Names</label>
  <p class=\"FQNname FQNC \">$common_Names</p></div>
</div>
<div id=\"otherDetails\">
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Name Notes</label>
  <p class=\"oDD shortText\">$name_Notes</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Description</label>
  <p class=\"oDD shortText\">$description</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Ecology</label>
  <p class=\"oDD shortText\">$ecology</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Distribution</label>
  <p class=\"oDD shortText\">$distrib_Notes</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Habits</label>
  <p class=\"oDD shortText\">$habits</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Status</label>
    <p class=\"oDD shortText\">$status</p></span>
  </div>
  
  <div id=\"oDDOutput\" class=\"OutputAnim\"></div>
  <div id=\"imgGrid\">$picList </div>
  <form name=\"editForm\" action=\"../../cgi-bin/IBISeditStuff.php3\" method=\"POST\" enctype=\"multipart/form-data\" class=\"hiddentext\">
    <input type=\"text\" name=\"thecat\" id=\"thecat\" class=\"hiddentext\" value=\"$theCat\"/>
	  <input type=\"text\" name=\"specref\" id=\"specRef\" class=\"hiddentext\" value=\"$theSpecies\"/>
	   <input type=\"text\" name=\"conRef\" id=\"conRef\" class=\"hiddentext\" value=\"$contribRef\"/>
	   <input type=\"text\" name=\"recID\" id=\"recID\" class=\"hiddentext\" value=\"$animID\"/>
 </form>
  ";
	}

	if ($theCat == "minerals"){
	$prefix = "min";
	$theTab = "MineralsEdits";
	$theField = "MineralID";
	$thespec = "name";
  $stmt3 = $mysqli->prepare("SELECT MineralID, name, Mgroup, crystalSys, habit, chemForm, hardness, density, cleavage, fracture, streak, lustre, fluorescence, notes, origin, characteristics, uses, mediaRefs,   contribRef, uploadDate, distrib, score  FROM Minerals WHERE name='$theSpecies'");

  $stmt3->bind_result($minID, $name, $Mgroup, $crystalSys, $habit, $chemForm, $hardness, $density, $cleavage, $fracture, $streak, $lustre, $fluorescence, $notes, $origin, $characteristics, $uses, $mediaRefs, $contribRef, $uploadDate, $distrib, $currScore);	
  $stmt3->execute();
  $stmt3->fetch();
  $stmt3->close();
 $mediaList = explode(":", $mediaRefs);
 $recID = "$prefix$minID";
 $recnum = $minID;
 foreach ($mediaList as $mediaRef){
   if ($mediaRef == ""){
     continue;
   }
 $imglistOptions .= "filename='$mediaRef' or "; 
 } 
  $prestate = $imglistOptions.$qClose;
  $prestmt = str_replace(" or ;", ";", $prestate);
  $stmtQ = "SELECT serverpath, tags FROM Media WHERE ".$prestmt;
  $stmt4 = $mysqli->prepare($stmtQ);
  $stmt4->bind_result($imgpath, $imgtag);
  $stmt4->execute();
  while ($stmt4->fetch()){
    $picList .= "<img class=\"imgClass\" src=\"$imgpath\" title=\"$imgtag\" />";
  }
   	 
   $picList = str_replace("$imagesfroot", "$imageshroot", $picList);
   $picList = str_replace("$imagesdroot", "$imageshroot", $picList);
      $picList = str_replace("$imagesNotebookroot","$imageshroot", $picList);
   //$emptyreg = "/w/";
   $chemFormArray = str_split("$chemForm");
   $chemForm = "";
   foreach ($chemFormArray as $chemChar){
    if (intVal($chemChar)){
    
      $chemChar = '<sub>'.$chemChar.'</sub>';
       //  print " $chemChar: \n";
    }
    $chemForm .= "$chemChar"; 
   }
   print "<div id=\"detail_fs\" class=\"littleDD\"><div id=\"catHeading\" class=\"cathead\">$name  : $chemForm</div>
<div id=fqnNameList>
  <div class=\"itemC\"><label class=\"labelClass\">Name</label>
  <p class=\"FQNname FQNC shortText\">$name</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Group</label>
  <p class=\"FQNname FQNC shortText\">$Mgroup</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Crystal System</label>
  <p class=\"FQNname FQNC shortText\">$crystalSys</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Habit</label>
  <p class=\"FQNname FQNC shortText\">$habit</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Chemical Formula</label>
  <p class=\"FQNname FQNC shortText\">$chemForm</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Hardness</label>
  <p class=\"FQNname FQNC shortText\">$hardness</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Density</label>
  <p class=\"FQNname FQNC shortText\">$density</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Cleavage</label>
  <p class=\"FQNname FQNC shortText\">$cleavage</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Fracture</label>
  <p class=\"FQNname FQNC shortText\">$fracture</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Streak</label>
  <p class=\"FQNname FQNC shortText\">$streak</p></div>
  <div class=\"itemC\"><label class=\"labelClass\">Fluorescence</label>
  <p class=\"FQNname FQNC shortText\">$fluorescence</p></div>
</div>
<div id=\"otherDetails\">
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Notes</label>
  <p class=\"oDD shortText\">$notes</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Origin</label>
  <p class=\"oDD shortText\">$origin</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Characteristics</label>
  <p class=\"oDD shortText\">$characteristics</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">Distribution</label>
  <p class=\"oDD shortText\">$distrib</p></span>
  <span class=\"oDC\"><label class=\"oDHClass labelClass\" onClick=\"show_oDD(this)\">uses</label>
  <p class=\"oDD shortText\">$uses</p></span>
  </div>
  <div id=\"oDDOutput\" class=\"OutputMin\"></div>
  <div id=\"imgGrid\">$picList</div>
  <form name=\"editForm\" action=\"../../cgi-bin/IBISeditStuff.php3\" method=\"POST\" enctype=\"multipart/form-data\" class=\"hiddentext\">
    <input type=\"text\" name=\"thecat\" id=\"thecat\" class=\"hiddentext\" value=\"$theCat\"/>
	  <input type=\"text\" name=\"specref\" id=\"specRef\" class=\"hiddentext\" value=\"$theSpecies\"/>
	  <input type=\"text\" name=\"conRef\" id=\"conRef\" class=\"hiddentext\" value=\"$contribRef\"/>
	  <input type=\"text\" name=\"recID\" id=\"recID\" class=\"hiddentext\" value=\"$minID\"/>
	  
 </form>
  ";
	}
	include ("IBISviews.php3");
	if ($currScore == ""){
	$currScore = "0";
	
	}
?>

</div>
<form name="detailInfoForm" action="../../cgi-bin/IBISnewIndexCreator.php3" method="POST" enctype="multipart/form-data" class="hiddentext">
	        <input type=text id="catVal" name="catValue" class="" value=
	        <?php $htmlheading = ($_POST['catVal']);
	        echo "$htmlheading"; 
	        ?>
	        >
	     </form>
<script>
function goBack(){
  document.detailInfoForm.submit();
}
 function editSub(){
 document.editForm.submit();
 }

</script>
<?php
print "<div id=viewsBlock><label>Viewed <span id=Vcount>$viewC</span> Times</label><br><label>Edited <span id=Ecount>$edCount</span> Times </label></br><label>Current Score </br><span id=\"currScore\">$currScore</span></label></div><div id=\"ratingsBlock\">
	<legend><span id=\"ratehead\">Rate this page</span><p>
		<input
			type=\"radio\"
			name=\"score\"
			id=\"rad1\"
			value=\"5\"
			checked=\"checked\"
		/>Very good
		</p>
		<p>
		<input
			type=\"radio\"
			name=\"score\"
			id=\"rad2\"
			value=\"3\"
		/>Good
		</p>
		<p>
		<input
			type=\"radio\"
			name=\"score\"
			id=\"rad3\"
			value=\"1\"
		/>Okay, I guess
		</p>
		<p>
		<input
			type=\"radio\"
			name=\"score\"
			id=\"rad4\"
			value=\"-1\"
		/>barely usefull
		</p>
		<p>
		<input
			type=\"radio\"
			name=\"score\"
			id=\"rad5\"
			value=\"-3\"
		/>missing information
		</p>
		<p>
		<input
			type=\"radio\"
			name=\"score\"
			id=\"rad6\"
			value=\"-5\"
		/>wrong information
		</p>
		
	</legend><div id=\"rButtons\">
	<input type=\"button\" onclick=\"submitVote()\" class=\"button\" value=\"Submit\">
	<input type=\"button\" onclick=\"cancelVote()\" class=\"button\" value=\"Cancel\">
	</div>
</div><div id=\"showRates\">
	<span id=\"ratebutton\"><input type=\"button\" id=\"ratebut\" class=\"button\" value=\"Rate this page\" onclick=\"showRating()\"></span><span id=\"rtecmnt\">not sent</span>
</div>
	<div id=\"commentBlock\" display=\"none\">
		<label>You have given this page a low score, You can...</label></br>
		<a href=\"http://192.168.43.132/ibis/IBISregistration.html\" class=\"littleDD linksclass\">Register as a contributer and edit it</a> or</br>
		<label>Write a note to the person who edited it last</label></br>
		<textarea id=\"edNotes\" name=\"Ednotes\" class=\"notes\"></textarea></br>
		<input type=\"button\" class=\"button\" value=\"Submit Note\" onclick=\"submitNote()\"/>
		<input type=\"button\" class=\"button\" value=\"Dismiss\" onclick=\"dismissNote()\"/>
	</div>
	
	<span id=\"rateSent\"><input type=\"text\" id=\"rateIsSent\" value=\"no\"/><input type=\"input\" id=\"rcID\" value=\"\" style=\"display:none;\"/></span>";
?>
</body>

</html>
