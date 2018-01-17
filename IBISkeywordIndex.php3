
<?php
	/*
	this receives the name of the table to search($catVal) the heading to search($DDL3Sel) the keyword($DDL3txt) 
	the query needs to return a list of $fullPath:$name:: pairs to newIndexCreator 
	query1 = select mediaRefs from $IBIS_T where $DDL3Sel like '%$DDL3txt%';
	
	select  fullPath, $(name/species) from Media, $IBIS_T where 
	
	
	*/
  $DDL3txt = array_key_exists('ddl3Txt',$_GET)?$_GET['ddl3Txt']: null; // get the fieldvalue
  $DDL3Sel = array_key_exists('ddl3dd',$_GET)?$_GET['ddl3dd']: null;// get the fieldheading
  /*
  $DDL1Minvalue = array_key_exists('ddl1minval',$_GET)?$_GET['ddl1minval']: null; // get the fieldheading if its a mineral
  if ($DDL1value == ""){ // if its not a vegetable or animal heading
    $fieldval = $DDL1Minvalue; // the  fieldheading gets the value of the $DDL1Minvalue
  }if ($DDL1Minvalue == ""){ // if its not a mineral heading 
    $fieldval = $DDL1value;// the fieldheading gets the value of $DDL1value
  }
  */
  $catVal = $_GET['catValtext'];// the table to search
  $retlist = "";
  $retlist2 = "";
  $retlist3 = "";
  $IBIS_T = "";
  $firstMList = "";
  $memberList = "";
  include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
	if ($catVal == "vegetables"){
	$IBIS_T = "Vegetables";
	}
	if ($catVal == "animals"){
	$IBIS_T = "Animals";
	}
	if ($catVal == "minerals"){
	$IBIS_T = "Minerals";
	}
	
  if ($stmt2 = $mysqli->prepare("SELECT mediaRefs FROM $IBIS_T WHERE $DDL3Sel like '%$DDL3txt%'")){
  	// select mediaRefs from Vegetables where family = "Ruschia";
    }else { 
    	print $mysqli->error;
    }
    if ($stmt2->execute()){
    }else {
     print $mysqli->error;
   }
   $stmt2->bind_result($ddl2return);
    while ($stmt2->fetch()){
		  
    	$retlist2 .= "$ddl2return:"; // returns a list of mediaRefs
    }
   $stmt2->close(); 
   if ($retlist2 == ""){
			 	print "nomatch";
			 	exit;
   		}
   $splitSetArray = explode("::", $retlist2); //split the list of medRefs each set seperated with ::
   $numSets = count($splitSetArray); // get how many sets there are
   for ($j = 0; $j < $numSets; $j++){ // loop over the list of sets
    $asSet = explode(":", $splitSetArray[$j]); // split up each set
    if (!$asSet[0]){ // if the first index of the set array is null stop the loop
     break;
    }
    $firstM = $asSet[0]; // collect the first entry from each set
    $firstMList .= "$firstM:"; // add the first member to a running list of first members
   }
	if ($catVal == "vegetables" || $catVal == "animals"){ 
  if ($catVal == "animals"){
        $IBISData = "IBIS.Animals";
      }
  if ($catVal == "vegetables"){
        $IBISData = "IBIS.Vegetables";
      }
  $mainQ = "select genus, species, serverpath from IBIS.Media, $IBISData where";   
      }
	if ($catVal == "minerals"){
  $IBISData = "IBIS.Minerals";
  $mainQ = "select name, serverpath from IBIS.Media, IBIS.Minerals where ";
  }
$qClose = ';';
$options = "";
$colon = ':';
$memberList = explode(":",$firstMList);
$memCount = count($memberList);
for ($h = 0; $h < $memCount; $h++){
  if ($memberList[$h] == ""){
    continue;
  }
  $options .= ' filename =\''. $memberList[$h].'\' and mediaRefs like \'%'. $memberList[$h].'%\' or' ;
}
$prestmt = $options.$qClose;
$prestmt = str_replace("or;", ";", $prestmt);
$stmtQ = $mainQ. $prestmt;
if ($stmt3 = $mysqli->prepare("$stmtQ")){

    }else { 
    	print $mysqli->error;
    }
    if ($stmt3->execute()){

    }else {
     print $mysqli->error;
   }
if ($catVal == "vegetables" || $catVal == "animals"){ 
   $stmt3->bind_result($Pgenus, $species, $fullPath);
    while ($stmt3->fetch()){
   	$retlist3 .="$fullPath:$species:$Pgenus::";
}// $fullpath:$family:$genus:$species:$localNames:: 
   } 
if ($catVal == "minerals"){
   $stmt3->bind_result($name, $fullPath);
    while ($stmt3->fetch()){
   	$retlist3 .= "$fullPath:$name::";// $fullpath:$family:$genus:$species:$localNames::
   	}
}
   $stmt3->close(); 

  //}
   $retlist3 = str_replace("$imagesdroot", "$imageshroot", $retlist3);
   $retlist3 = str_replace("$imagesfroot", "$imageshroot", $retlist3);
   $retlist3 = str_replace("$imagesNotebookroot","$imageshroot", $retlist3);
   if ($retlist3 == ""){
   	$retlist3 = "nomatch";
   }
   print "$retlist3";
   

?>
