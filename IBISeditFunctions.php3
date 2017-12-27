<?php
$fileList ="";
$tagCount = 0;
$newTagList = "";
$newName = "";
$upcount = count($_FILES['ibismedia']['name']); // get number of uploaded files
$upfile = $_FILES['ibismedia']['name'][0]; // get the name of the first uploaded file
if ($upcount > 0 && $upfile != ""){ // test if more than 0 upload and that the name is not empty 
    $stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement."); 
    if ($stmt->execute()){ // prefix is initialized in the calling script
      $stmt->bind_result($numFile) or die ("cannot bind parameters.");
      $stmt->fetch();
      $stmt->close();
      $filecount = count($_FILES['ibismedia']['name']); // probably dont have to get this again here
      $postTags = explode("::", $_POST['newtagslist']); // explode the list of submitted new imagetags
      $postTagCnt = count($postTags);
     
      foreach ($postTags as $tag){
		for ($d=0; $d <= $filecount; $d++){ //
	// if($posTags[$d] != "" ){
	  	$justname = $_FILES['ibismedia']['name'][$d];// 
	  	$temptag = $tag;
	  	if (!strstr($temptag, $justname)) {
	    	$tag = "$justname : no tag";
	    	$newTagList .= "$tag::";
	    	$tagCount++;
	// continue;
	  	}else {
	    	$newTagList .= "$tag::";
	    	$tagCount++;
	  }
	}
    //}
    }
      $newPostTags = explode("::", $newTagList);
		foreach ($newPostTags as $atag){
			//print "$atag </br>";
		}
 		for ($i = 0; $i < $filecount; $i++){
 			$numFile++;
 			$justname = $_FILES['ibismedia']['name'][$i];
 			$tmpFilePath = $_FILES['ibismedia']['tmp_name'][$i];
 			$extn = substr($justname, -4);
 			for ($c = 0; $c < $tagCount; $c++){
	 			$tagstring = $newPostTags[$c];
	 			// print "this is just the name : $justname </br>";
	 			// print "This is the tagstring $tagstring </br>";
	 			if (strstr( $tagstring, $justname )){ // if the tagstring contains the filename
	 		 		$newName = $prefix.$numFile.$extn; // define the new filename
	 			//	print "This is the new file name $newName";
 					$tagItem = explode(" : ", $tagstring);
	 		 		$justTag = $tagItem[1];
 					//print "The new name ".$newName." : " .$justTag. "<br>" ;
 				}
 				if ($tagstring == "no tag added"){
	 				$newName = $prefix.$numFile.$extn;
	 				$justTag = "no tag added";
	 			//	print "The new name ".$newName." : " .$justTag. "<br>" ;
	 			}
	 		 }
		$uploadfile = $uploaddir.$newName;
  	$tmpFilePath = $_FILES['ibismedia']['tmp_name'][$i];
		if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
  // print("File upload was successful <br>");
  		$fileMessg = "File upload was successful <br>";
  		exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile");
		  $fileList .= "$newName:";
	 		$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )");
			$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $servPath) or die ("cannot bind parameters.");
			$MediaID = 0;
			$Type = "image";
			$filename = $newName;
			$tags = $justTag; //***This need to be evaluated....done
			$uploadDate = date('Y-m-d H:i:s');
			$contribRef =trim($_POST['contributer_ID']);
			$uploaderType = "c";
			$servPath = $uploadfile;
			$stmt2->execute();
		//print "statement should have executed....</br>";
			$dataMessg = "statement should have executed....</br>";
			if ($stmt2->affected_rows == -1){
	//	print "IBIS Upload failed <br>";
				$dataError = "IBIS Media Upload failed <br>";
			}else{
			//print "IBIS Data upload succesful<br>";
				$dataMessg = "IBIS Media upload succesful<br>";
			}
			$stmt2->close();
  	  } else {
  	    $dataError = "Media upload failed <br>";
  	  }
  	}
	}else {
 		$dataError =  "Statement did not execute <br>";
	}
	}else {
	// print "no ibismedia to upload";
	}// end of mediaupload routine
	// start running  through deletelist check
 	$existRefs = $_POST['mediarefs']; // get existing references from POST
 	$fileList .= "$existRefs:"; // fileList gets populated in mediaupload routine and the current refs get appended
 	$delList = array_key_exists('imgDeletelist', $_POST)?$_POST['imgDeletelist']:null;
 	if ($delList==""){
 		//print "No images to delete";
 //continue;
 	}
  	$delListrefs = explode(":", $delList);
//print "";
	foreach ($delListrefs as $delItem){
	//print "$delItem <br>";
 		$fileList = str_replace("$delItem", "", $fileList);
	}
$fileList = str_replace(" ", ":", $fileList);// replace spaces with colons in filelist
//$fileList = str_replace("&[:]+",":", $fileList );// squash repeated colons into a single colon
//$fileList = str_replace("^:","", $fileList );// repplace colons on start of line with null // end delete list routine
	// print "Filelist minus Dellist is $fileList<br>";
	$theTagList = array_key_exists("editedtagslist",$_POST)?$_POST['editedtagslist']:null;
	if ($theTagList != null){
	  	$newTagsList = explode("::", $theTagList);
	  	foreach ($newTagsList as $tagItem){
		    if ($tagItem == ""){
			    continue;
		    }
		$fileTagList = explode(":", $tagItem);
		if (!$fileTagList){
    // print "no tags were added to the edited tag list";
		
		}else {

    $theRef = trim($fileTagList[0]);
		$theTag = trim($fileTagList[1]);
		// print "$theRef : $theTag  <br>";
		$theResult = $mysqli->prepare("update Media set tags='$theTag' where filename='$theRef'") or die ("could not update Media table". $mysqli->error);
		$theResult->execute() or die ("could not execute tag update");
	}
	}
	
		//$mediaQuery = "update Media set tags='$theTag' where filename='$theRef'";
	 	
	}
	
?>
