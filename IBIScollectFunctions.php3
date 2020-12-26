<?php
$fileList ="";
$newName ="";
$tagItem ="";
$justTag ="";
$numfile = 0;
$typea = "";
$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
if ($stmt->execute()){
  $stmt->bind_result($numFile) or die ("cannot bind parameters.");
  $stmt->fetch();
  $stmt->close();
  $filecount = count($_FILES['ibismedia']['name']); // get the number of uploaded files and there should be at least one image
  //$tagCount = count($_POST['imgtag']);
  $tags = array_key_exists('imgtag',$_POST)?$_POST['imgtag']: 0;
  //print "$tags";
  if (count($tags) > 0){
	$tagCount = count($tags); // if tags are uploaded get how many
  } else {
  		$tagCount = 0;
  } // if no tags uploaded set the counter to 0
  // print "there are $tagCount tags <br>";
  for ($i = 0; $i < $filecount; $i++){ // iterate over the uploaded files; this is the loop that will deal with each file
    $numFile++;
    $justname = $_FILES['ibismedia']['name'][$i]; // get the uploaded file name
    $tmpFilePath = $_FILES['ibismedia']['tmp_name'][$i]; // get the temp file name
    	print "made it here with $justname <br>";
    $extn = substr($justname, -4); // get the extension from the original file
   // if (){} // here should be a check to deal with different types of extensions
    for ($c = 0; $c <= $tagCount; $c++){ // iterate over the tags if any are uploaded
      $tagstring = $_POST['imgtag'][$c]; // get the tag
    //	print "This is the tagstring $tagstring <br>";
      if (strstr( $tagstring, $justname )){ // see if the tag contains the uploaded filename
      	$newName = $prefix.$numFile.$extn; // construct the new filename ** this shouldn't be here
		$tagItem = explode(" : ", $tagstring); // if the tag contains the filename explode it
		$justTag = $tagItem[1]; // get just the tag part of the uploaded tag
	  } 
    }
    //print "just the tag, ".$justTag."<br>";
    $newName = $prefix.$numFile.$extn; // construct the new filename
    $uploadfile = $uploaddir.$newName ; // construct the server upload path
    if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) { // test the file upload, following code runs only on upload success
      // print("File upload was successful <br>");
      	$fileMessg = "File upload was successful <br>";
      	exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile"); // call convert to resize the image
      	$fileList .= "$newName:"; // add the new filename to the list of uploaded files
      	$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )"); // write the new media file to the Media table
      	$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $servPath) or die ("cannot bind parameters.");
      	if ($extn == "jpg" || $extn == "png" || $extn == "gif"){
      		$typea = "image";
      	}
      	$MediaID = 0;
      	$Type = $typea;
      	$filename = $newName;
      	$tags = $justTag; //***This need to be evaluated....done
     	$uploadDate = date('Y-m-d H:i:s');
     	$contribRef = trim($_POST['contributer_ID']);
      	$uploaderType = "c";
      	$servPath = $uploadfile;
      	$stmt2->execute();
       	if ($stmt2->affected_rows == -1){
			$dataError = "IBIS Media Upload failed <br>";
      	}else{
			$dataMessg = "IBIS Media upload succesful<br>";
      	}
      	$stmt2->close();
    } else { // end of file upload check, following code runs on upload error
       	$dataError = "Media upload failed <br>";
    }
  } // end of processing file, loop for next file, 
}else { // end of data connection check, if this test fails none of the above run
  $dataError =  "Statement did not execute <br>";
}
		
?>
