<?php
/*
gets called by the editing page and deals with all the image processing
writes new media to the IBIS.Media table
updates tags for media in IBIS.Media table
returns a list of mediaRefs to calling page
*/
$tagCount = 0;
$newTagList = "";
$newName = "";
$loadFlist ="";
$justname ="";
function Ntags($justname){
/*process new media tags
get POST[newtagslist]
anim10.png:fluffybush::anim9.png:yellowflower::
*/
global $justname;
 //print "$justname <br>";
$postTags = explode("::", $_POST['newtagslist']); 
if ($postTags[0] == "") {
	
	$caption = "no tags";
	
	return $caption;
	
 }else{
 	foreach ($postTags as $ftag ){
 		if ($ftag == ""){continue;}
 	
 		if (strstr($ftag, $justname)){
 	
 		$justtag = explode(":", $ftag);
 		$caption = $justtag[1];
 		
 		return $caption;
 		}
 		
 	}
 }
}
function Rmedia(){
global $fileList, $mysqli ; 
/*process removed media
get POST[imgDeletelist] http://192.168.43.132/ibis/Data/Images/veg120.jpg:http://192.168.43.132/ibis/Data/Images/veg120.jpg:
foreach deletelist entry
remove entries from current mediaRef list 
return updated mediaRef list
*/
$delList = trim(array_key_exists('imgDeletelist', $_POST)?$_POST['imgDeletelist']:null);
$existRefs = trim(array_key_exists('mediarefs',$_POST)?$_POST['mediarefs']:null); 

$filepart = 'http://192.168.43.132/ibis/Data/Images/';
$delList = str_replace("$filepart","",$delList);
//$existRefs = $_POST['mediarefs']; // get existing references from POST
$fileList .= "$existRefs"; 
$delListrefs = explode(":", $delList);
		foreach ($delListrefs as $delItem){
			if ($delItem == ""){
			 continue; 
			 }else{
			 $delItem = "$delItem:";
			 $fileList = str_replace("$delItem", "", $fileList);
			 }
		}
}
function Amedia(){
// /*process added media and new tags
//get POST[FILES] list 
//foreach FILE.item
//create newfilename
//add newfilename to serverpatlist
//create mediaRef item
//check for media tags -> tags get Ntags()
//write new Media dataset to IBIS.media
//return mediaRef list
//*/
global $fileList, $mysqli, $prefix, $filecount, $newTagList, $tagCount, $filecount, $loadFlist, $uploaddir, $justname,$fileMessg ; 
$upcount = count($_FILES['ibismedia']['name']); // get number of uploaded files
$upfile = $_FILES['ibismedia']['name'][0]; // get the name of the first uploaded file
if ($upcount > 0 && $upfile != ""){ // test if more than 0 upload and that the name is not empty 
	$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '$prefix%'") or die ("cannot create statement."); 
    if ($stmt->execute()){ // prefix is initialized in the calling script
		$stmt->bind_result($numFile) or die ("cannot bind parameters.");
		$stmt->fetch();
		$stmt->close();
		for ($i = 0; $i < $upcount; $i++){
 			$numFile++;
 			$justname = $_FILES['ibismedia']['name'][$i];
 			$tmpFilePath = $_FILES['ibismedia']['tmp_name'][$i];
 			$extn = substr($justname, -4);	
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
			$tags = Ntags($justname); //"temptag$i";    //$justTag; //***This need to be evaluated....
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
 // end add media function
 }
 
 
function Etags() {
/*process edited media tags
get POST[editedtagslist]
foreach listitem 
split the item  	item[0] = the file refernce
					item[1] = the edited tag
run database update query
*/ 
	global $mysqli, $tagStatus;
	$editedtags = trim(array_key_exists('editedtagslist', $_POST)?$_POST['editedtagslist']:null);
	if ($editedtags){
		$editedTagList = explode("::", $editedtags);
		foreach ($editedTagList as $tagItem){
			if ($tagItem == ""){
				continue;
			}
			//print "$tagItem<br>";
			$ecaption = explode(":", $tagItem);
			$newCaption = $ecaption[1];
			$fileRef = $ecaption[0];
			$Etagstmt = $mysqli->prepare("update Media set tags = '$newCaption' where filename = '$fileRef'") or die ("cannot create statement.");
			$Etagstmt->execute();
			//$Etagstmt->bind_result($eRsult);
			//	$Etagstmt->fetch();
			$Etagstmt->close();
			if ($Etagstmt->affected_rows == -1){
				$tagStatus = "this did not update the database<br>";
			}else{
				$tagStatus = "everything went well";
			}
		}
	}else {
		return;
	}
}
Rmedia();
Amedia();
Etags()

?>
