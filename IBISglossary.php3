<?php
include ("IBISvars.inc");
if (!$guest_acc){
  print "the include file was not included <br>";
}
$itemN = 0;
$numFile = 0;
$prefix = "gls";
$uploadDate = date('Y-m-d H:i:s');
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
if ($mysqli->connect_error){
  die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
}
$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
if ($stmt->execute()){
  $stmt->bind_result($numFile) or die ("cannot bind parameters.");
  $stmt->fetch();
  $stmt->close();
}

	if ($_POST){
	
		$entryCount = $_POST['ICVal'];
		for ($i = 1; $i <= $entryCount; $i++){
		$itemN++;
			$item = "item".$i;
			$def = "definition".$i;
			$diagC = "diagramCap".$i;
			$picRef = "picref" . $i;
			$thisDef = $_POST["$def"];
			$thisItem = $_POST["$item"];
			$thisdiagC = $_POST["$diagC"];
			$thisPicRef = $_POST["$picRef"];
			if (!$thisPicRef == ""){
			$imageRef = getImageRef($thisPicRef);
			}
			else{
				$imageRef = "no image";
			}
			if ($thisdiagC == ""){
				$thisdiagC = "empty caption";
			}
			if ($thisDef == ""){
				$thisDef = "empty definition";
			}
			if ($thisItem == ""){
				$thisItem = "empty term";
			}
			
			
			$stmnt1 = $mysqli->prepare("INSERT into vegGlossary(GlossID, item, definition, diagramref, uploadDate) VALUES (?,?,?,?,?)");
			$stmnt1->bind_param('sssss',$theID,$theItem,$theDef,$theDGref, $uploadDate );
			$theID = 0;
			$theItem = $thisItem;
			$theDef = $thisDef;
			$theDGref = $imageRef;
			$uploadDate = $uploadDate;
			$stmnt1->execute();
		}
	}else{
		print "files array not found";
	}
	
	
	function getImageRef($Ref){
	global $prefix, $uploaddir, $numFile, $mysqli, $uploadDate;
	
	$ufC = count($_FILES);
	$a=$ufC;
  for ($j=1; $j<=$ufC; $j++){
  $picName = "pictures" . $j;
		if ($_FILES["$picName"]['name'] == $Ref){
		$numFile++;
			$oldFname=$_FILES["$picName"]['name'];
			$extn = substr($oldFname, -4);
			$newfileN = "$uploaddir"."$prefix"."$numFile"."$extn";
			$newName = "$prefix"."$numFile"."$extn";
			$tempF =$_FILES["$picName"]['tmp_name'];
			$cap = "diagramCap" . $j;
			$caption = $_POST["$cap"];
			if(move_uploaded_file("$tempF","$newfileN")){
				exec("/usr/bin/convert -resize 400x300! $newfileN $newfileN");
				$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )"); // write the new media file to the Media table
      	$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $servPath) or die ("cannot bind parameters.");
      	$typea = "";
      	if ($extn == "jpg" || $extn == "png" || $extn == "gif"){
      		$typea = "image";
      	}
      	$MediaID = 0;
      	$Type = $extn;
      	$filename = $newName;
      	$tags = $caption; //***This need to be evaluated....done
     	$uploadDate = $uploadDate;
     	$contribRef = trim($_POST['contributer_ID']);
      	$uploaderType = "c";
      	$servPath = $newfileN;
      	$stmt2->execute();
			}
			
			$a = "$newName";
		}
	}
			return $a;
	}
	$htmlR = '<!doctype html>
	<head>
	<script type="text/javascript">
	document.location = "/ibis/IBISvegGlossaryEntry.html";
	</script>
	</head><body></body></html>	
	';
	print "$htmlR";
?>
