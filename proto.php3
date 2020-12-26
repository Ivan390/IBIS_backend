<?php
/*
gets called by the editing page and deals with all the image processing
writes new media to the IBIS.Media table
updates tags for media in IBIS.Media table
returns a list of mediaRefs to calling page
*/

function Rmedia(mediaRefs){
/*process removed media
get POST[imgDeletelist] http://192.168.43.132/ibis/Data/Images/veg120.jpg:http://192.168.43.132/ibis/Data/Images/veg120.jpg:
foreach deletelist entry
remove entries from current mediaRef list 
return updated mediaRef list
*/
$delList = array_key_exists('imgDeletelist', $_POST)?$_POST['imgDeletelist']:null;
$filepart = 'http://192.168.43.132/ibis/Data/Images/';
$delList = str_replace("$filepart","",$delList);
$existRefs = $_POST['mediarefs']; // get existing references from POST
$fileList .= "$existRefs:"; 

 	if ($delList==""){
		exit;
 	} else {
 		$delListrefs = explode(":", $delList);
		foreach ($delListrefs as $delItem){
			if ($delItem == ""){ continue; }
			$fileList = str_replace("$delItem", "", $fileList);
		}
 	}

}
function Amedia{
/*process added media and new tags
get POST[FILES] list 
foreach FILE.item
create newfilename
add newfilename to serverpatlist
create mediaRef item
check for media tags -> tags get Ntags()
write new Media dataset to IBIS.media
return mediaRef list
*/

$upcount = count($_FILES['ibismedia']['name']); // get number of uploaded files
$upfile = $_FILES['ibismedia']['name'][0]; // get the name of the first uploaded file
if ($upcount > 0 && $upfile != ""){ // test if more than 0 upload and that the name is not empty 
    $stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement."); 
    if ($stmt->execute()){ // prefix is initialized in the calling script
      $stmt->bind_result($numFile) or die ("cannot bind parameters.");
      $stmt->fetch();
      $stmt->close();
     // $filecount = count($_FILES['ibismedia']['name']); // probably dont have to get this again here
      $postTags = explode("::", $_POST['newtagslist']); // explode the list of submitted new imagetags
      $postTagCnt = count($postTags); // get number of uploaded new tags
      foreach ($postTags as $tag){ // loop through postTags 
		
		for ($d=0; $d <= $filecount; $d++){ // loop through the list of uploaded filenames
	  	$justname = $_FILES['ibismedia']['name'][$d];// capture the current file name
	  	$temptag = $tag; // capture the current tag
	  	if (strstr($temptag, $justname)) { // test if tag contains file name
	    	$newTagList .= "$tag::"; // if it does add the complete tag to the newTaglist
	    	$tagCount++;	    	   // increment tag count 	
	  	}else { // if the tag doesn't contain the file name 
	    	$tag = "$justname : no tag"; //construct a new tag with default entry as tag part
	    	$newTagList .= "$tag::"; // add new tag to newTagslist
	    	$tagCount++;	// increment tag count
	  }
	} // next filename
   
    } // next tag
      $newPostTags = explode("::", $newTagList); // explode the newTaglist
		
 		for ($i = 0; $i < $filecount; $i++){ // loop through uploaded files
 			$numFile++;
 			$justname = $_FILES['ibismedia']['name'][$i]; // get original filename
 			$tmpFilePath = $_FILES['ibismedia']['tmp_name'][$i]; // get server temp filename
 			$extn = substr($justname, -4); // get file extension from original filename
 			for ($c = 0; $c < $tagCount; $c++){ // loop through the list of tags
	 			$tagstring = $newPostTags[$c]; // capture the current tagstring
	 			if (strstr( $tagstring, $justname )){ // if the tagstring contains the filename	 		 		
	 				$tagItem = explode(" : ", $tagstring);
	 		 		$justTag = $tagItem[1]; 
	 		 		if ($justTag == "no tag") {
	 		 			$justTag = "no tag added";
	 		 		}
	 		 	} 				 
	 		 } // next tag
	 		 $newName = $prefix.$numFile.$extn; // define the new filename
			 $uploadfile = $uploaddir.$newName;
  	 		 if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
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
				$dataMessg = "statement should have executed....</br>";
				if ($stmt2->affected_rows == -1){
					$dataError = "IBIS Media Upload failed <br>";
				}else{
					$dataMessg = "IBIS Media upload succesful<br>";
				}
				$stmt2->close();
  	  		} // end upload file test condition
  	  		else {
  	    		$dataError = "Media upload failed <br>";
  	  		}
  		} // next file
	} // end stmnt execute condition
	else {
 		$dataError =  "Statement did not execute <br>";
	}
	}// end upcount test condition
} // end add media function
function Etags {
/*process edited media tags
get POST[editedtagslist]
foreach listitem 
split the item  	item[0] = the file refernce
					item[1] = the edited tag
run database update query
*/ 


}
function Ntags{
/*process new media tags
get POST[newtagslist]

*/

}
medRefs = $_POST['mediarefs'];
Rmedia(medRefs);
Amedia();

?>
