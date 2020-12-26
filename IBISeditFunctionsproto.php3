<?php
$fileList ="";
$tagCount = 0;
$newTagList = "";
$newName = "";
$upcount = count($_FILES['ibismedia']['name']); 
$upfile = $_FILES['ibismedia']['name'][0];
if ($upcount > 0 && $upfile != ""){
  $stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement."); 
  if ($stmt->execute()){
    $stmt->bind_result($numFile) or die ("cannot bind parameters.");
    $stmt->fetch();
    $stmt->close();
    $filecount = count($_FILES['ibismedia']['name']);
    $postTags = explode("::", $_POST['newtagslist']);
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
	if (strstr( $tagstring, $justname )){
	  $newName = $prefix.$numFile.$extn;
	  $tagItem = explode(" : ", $tagstring);
	  $justTag = $tagItem[1];
	}
	if ($tagstring == "no tag added"){
	  $newName = $prefix.$numFile.$extn;
	  $justTag = "no tag added";
	}
      }
      $uploadfile = $uploaddir.$newName;
      $tmpFilePath = $_FILES['ibismedia']['tmp_name'][$i];
      if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
	$fileMessg = "File upload was successful <br>";
	exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile");
	$fileList .= "$newName:";
	$stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )");
	$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $servPath) or die ("cannot bind parameters.");
	$MediaID = 0;
	$Type = "image";
	$filename = $newName;
	$tags = $justTag; 
	$uploadDate = date('Y-m-d H:i:s');
	$contribRef = trim($_POST['contributer_ID']);
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
      } else {
	$dataError = "Media upload failed <br>";
      }
    }
  }else {
    $dataError =  "Statement did not execute <br>";
  }
}else {
}
$existRefs = $_POST['mediarefs'];
$fileList .= "$existRefs:";
$delList = array_key_exists('imgDeletelist', $_POST)?$_POST['imgDeletelist']:null;
if ($delList==""){
  
}
$delListrefs = explode(":", $delList);
foreach ($delListrefs as $delItem){
  $fileList = str_replace("$delItem", "", $fileList);
}
$fileList = str_replace(" ", ":", $fileList);
$fileList = ereg_replace("[:]+",":", $fileList );
$fileList = ereg_replace("^:","", $fileList );
$theTagList = array_key_exists("editedtagslist",$_POST)?$_POST['editedtagslist']:null;
if ($theTagList != null){
  $newTagsList = explode("::", $theTagList);
  foreach ($newTagsList as $tagItem){
    if ($tagItem == ""){
      continue;
    }
    $fileTagList = explode(":", $tagItem);
    if (!$fileTagList){
    
    }else {
      $theRef = trim($fileTagList[0]);
      $theTag = trim($fileTagList[1]);
      $theResult = $mysqli->prepare("update Media set tags='$theTag' where filename='$theRef'") or die ("could not update Media table". $mysqli->error);
      $theResult->execute() or die ("could not execute tag update");
    }
  }
}
?>
