
<?php
//compare -metric [MAE|MSE|PSE|PSNR|RMSE ] image1 image2 null:

//upload an image
///specify whether veg|anim|min
include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>";
 	}
  $mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
  
  $justname = $_FILES['picture']['name'];
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	$tagstring = $_POST['imgtag']; 
	$prefix = $tagstring;
	$MAEmetricPair = "";
	$metricList =  "";
	$psnmetricList = "";
	$filePair = "";
	$imgList = "";
	print "made it here with $justname <br>";
	$extn = substr($justname, -4);
	$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
	 	if ($stmt->execute()){
			$stmt->bind_result($numFile) or die ("cannot bind parameters.");
			$stmt->fetch();
			$stmt->close();
	 }
	 		
	 			$numFile++;
	// $oldPath = /var/www/html/IBIS_Files_new/Data/Images/
	// $newPath = /var/www/html/ibis/Data/Images/
	
	/*
	 
	 
	*/
	 //	print "This is the tagstring $tagstring <br>";
	$newName = $prefix.$numFile.$extn;
	print "The new name ".$newName." <br>" ;
	$uploaddir = "/var/www/html/ibis/Data/Images/temp/";			
	$uploadfile = $uploaddir.$newName ;
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
		//exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile"); 
	  print("File upload was successful <br>");
	}
	 //$outputA ="";
	/*$stmt3 = $mysqli->prepare("SELECT filename, serverpath from Media where filename like '%$prefix%'"); 
	$stmt3->bind_result($fileName, $serverPath); 	
	$stmt3->execute();
	while ($stmt3->fetch()){
	  $filePair .= "$fileName:$serverPath::";
	}	  
	$pairArray = explode("::", $filePair);
	foreach ($pairArray as $pairSet) {
	  $thisPair = explode(":", $pairSet);
	  if ($thisPair[0] == ""){
	    continue;
	  }
	  $theFName = $thisPair[0];
	  $theSPath = $thisPair[1];
	  if (dirname($theSPath) == $oldPath) {
		$theSPath =  str_replace($oldPath, $newPath, $theSPath);
	 }
		   // $commnd = "/usr/bin/compare -metric MAE $uploadfile $theSPath null:";
		   exec("/usr/bin/convert -resize 300x300! $uploadfile ");
		   exec("/usr/bin/convert -resize 300x300! $theSPath ");
		 $MSEmetric =  `/usr/bin/compare -metric MSE $uploadfile $theSPath null: 2>&1`;
		   $mseMetval = explode(" ", $MSEmetric);    
	//   $MAEmetric =  `/usr/bin/compare -metric MAE $uploadfile $theSPath null: 2>&1`;
    //   $maeMetval = explode(" ", $MAEmetric);
		 //   $PSNRmetric =  `/usr/bin/compare -metric PSNR $uploadfile $theSPath null: 2>&1`;
	  // $RMSEmetric =  `/usr/bin/compare -metric RMSE $uploadfile $theSPath null: 2>&1`;
	 // $rmseMetval = explode(" ", $RMSEmetric);
	  $intSum = $MSEmetric;  //($rmseMetval[0]+$mseMetval[0]+$maeMetval[0] + $PSNRmetric)/4;
	 // if ($intSum < 15500){
	    $metricList .= "<li>Filename $theFName : metric avg $intSum</li>";
	    $imgList .= "<img class=\"imgthumb\" src=\"$theSPath\" title=\"$theFName\"/>";
	//  }
		   // $metricList .= "<li>Filename $theFName : MSEmetric $MSEmetric</li>";
		    
		    //$psnmetricList .= "<li>Filename $theFName : PSNRmetric $PSNRmetric</li>";
		   // $metricList .= "<li>Filename $theFName : RMSEmetric $RMSEmetric</li>";
		    
		   
		   // foreach ($outputA as $MAEmetric  ){
		  // print "this is the metric $MAEmetric ";
		 // }
	 }
	  $imgList = str_replace("$imagesdroot", "$imageshroot", $imgList);
	$imgList = str_replace("$imagesNotebookroot", "$imageshroot", $imgList);
   $imgList = str_replace("$imagesfroot", "$imageshroot", $imgList);
	 $imgDiv = "<div id=\"imgDiv\">$imgList</div>";
	 $metDiv = "<div id=\"metDiv\">$metricList</div>";
	print "$imgDiv $metDiv ";
*/		 
?>

