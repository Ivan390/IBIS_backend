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
 	if ($_POST['Akingdom'] == "Plantae"){
 		$BackButton = '
 		<div id="pgButtons" class="littleDD">
    	<a href="/ibis/IBISnewVegetables.html" class="buttonclass littleDD">Enter Another</a></div>';
 		$prefix = "veg";
 		$fileList ="";
	  include ("IBIScollectFunctions.php3");
  $dataMessg = "Data received....processing will follow <br>";
	$stmt3 = $mysqli->prepare("INSERT INTO Vegetables (VegetableID, phylum, subPhylum, class, subClass, Vorder, subOrder, family, subFamily, genus, subGenus, species, subSpecies, localNames, nameNotes, descrip, ecology, distrib, uses, growing, category, status, uploadDate, mediaRefs, contribRef, origDate ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die ("could not prepare statement 3 <br>");
	$stmt3->bind_param('ssssssssssssssssssssssssss', $VegetableID, $phylum, $subPhylum, $class, $subClass, $order, $suborder, $family, $subfamily, $genus, $subgenus, $species, $subspecies, $common_Names, $name_Notes, $description, $ecology, $distrib_Notes, $uses, $growing, $category, $tatus, $uploadDate, $mediaRefs,  $contributer_ID, $origDate );
	$VegetableID = 0;
	$phylum = trim(array_key_exists('phylum',$_POST)?$_POST['phylum']: null); 
	$subPhylum = trim(array_key_exists('subPhylum',$_POST)?$_POST['subPhylum']: null);
	$class = trim(array_key_exists('class',$_POST)?$_POST['class']: null);
	$subClass = trim(array_key_exists('subClass',$_POST)?$_POST['subClass']: null);
	$order = trim(array_key_exists('order',$_POST)?$_POST['order']: null);
	$suborder = trim(array_key_exists('suborder',$_POST)?$_POST['suborder']: null);
	$family = trim(array_key_exists('family',$_POST)?$_POST['family']: null);
	$subfamily = trim(array_key_exists('subfamily',$_POST)?$_POST['subfamily']: null);
	$genus = trim(array_key_exists('genus',$_POST)?$_POST['genus']: null);
	$subgenus = trim(array_key_exists('subgenus',$_POST)?$_POST['subgenus']: null);
	$species = trim(array_key_exists('species',$_POST)?$_POST['species']: null);
	$subspecies = trim(array_key_exists('subspecies',$_POST)?$_POST['subspecies']: null);
	$common_Names = trim(array_key_exists('common_Names',$_POST)?$_POST['common_Names']: null);
	$name_Notes = trim(array_key_exists('name_Notes',$_POST)?$_POST['name_Notes']: null);
	$description = trim(array_key_exists('description',$_POST)?$_POST['description']: null);
	$ecology = trim(array_key_exists('ecology',$_POST)?$_POST['ecology']: null);
	$distrib_Notes = trim(array_key_exists('distrib_Notes',$_POST)?$_POST['distrib_Notes']: null);
	$uses = trim(array_key_exists('uses',$_POST)?$_POST['uses']: null);
	$growing = trim(array_key_exists('growing',$_POST)?$_POST['growing']: null);
	$contributer_ID = trim(array_key_exists('contributer_ID',$_POST)?$_POST['contributer_ID']: null);
	$origDate = $uploadDate;
	$uploadDate = $uploadDate;
	$tatus = trim(array_key_exists('status',$_POST)?$_POST['status']: null);
	$category = trim(array_key_exists('category',$_POST)?$_POST['category']: null);
	$mediaRefs = $fileList;
	$stmt3->execute();
	if ($stmt3->affected_rows == -1){
// this means the transaction could nt be completed and I should put something 
// to deal with that condition.	
	}	
	$stmt3->close();
}

print "this is the end of the script<br>";
print "$MainBackButton $BackButton ";
//print "$fileMessg $fileError";
//print "$dataMessg $dataError";
 
 

?>
