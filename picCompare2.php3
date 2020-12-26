
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
	$metList = "";
	print "made it here with $justname <br>";
	$extn = substr($justname, -4);
	$extn = "$extn";
	$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
	if ($stmt->execute()){
		$stmt->bind_result($numFile) or die ("cannot bind parameters.");
		$stmt->fetch();
		$stmt->close();
	 }
	$numFile = $numFile + 4;
	$newName = $prefix.$numFile.$extn;
	print "The new name ".$newName." <br>" ;
	$uploaddir = "/var/www/html/ibis/Data/Images/temp/";			
	$uploadfile = $uploaddir.$newName;
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	print "temp filename : $tmpFilePath <br>";
	if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
		
	  print("File upload was successful <br>");
	  exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile"); 
	}
	
	$stmt3 = $mysqli->prepare("SELECT filename, serverpath from Media where filename like '%$prefix%'"); 
	$stmt3->bind_result($fileName, $serverPath); 	
	$stmt3->execute();
	while ($stmt3->fetch()){
	  $filePair .= "$fileName:$serverPath::";
	}	  
	$pairArray = explode("::", $filePair);
	$fC = 0;
	$errtext = "No such file";
	foreach ($pairArray as $pairSet) {
	  $thisPair = explode(":", $pairSet);
	  if ($thisPair[0] == ""){
	    continue;
	  }
	  $theFName = $thisPair[0];
	  $theSPath = $thisPair[1];
	  $fC = $fC + 1;
	 // $MSEmetric =  `/usr/bin/compare -metric MSE $uploadfile $theSPath null: 2>&1`;
	 $RMSEmetric =  `/usr/bin/compare -metric MSE $uploadfile $theSPath null: 2>&1`;
	 // $MAEmetric =  `/usr/bin/compare -metric MAE $uploadfile $theSPath null: 2>&1`;
	  //$MEPPmetric =  `/usr/bin/compare -metric MEPP $uploadfile $theSPath null: 2>&1`;
	//  $MSEmetric =  `/usr/bin/compare -metric MSE $uploadfile $theSPath null: 2>&1`;
	//  $NCCmetric =  `/usr/bin/compare -metric NCC $uploadfile $theSPath null: 2>&1`;
	//  $PAEmetric = `/usr/bin/compare -metric PAE $uploadfile $theSPath null: 2>&1`;
	// $metricAvg = ($MAEmetric + $AEmetric + $MAEmetric + $MEPPmetric  + $NCCmetric + $PAEmetric) / 6;
	 
	 /* 
	  $metricList = "PSNR : $PSNRmetric \nAE : $AEmetric\nMAE : $MAEmetric\nMEPP : $MEPPmetric\nMSE : $MSEmetric\nNCC : $NCCmetric\nPAE : $PAEmetric "; 
	  */
	  $metricList = "MSE Avg: $RMSEmetric \n";
	  if (strstr($RMSEmetric,$errtext)){
	  	continue;
	  
	  }
	 // print "phash metric for $uploadfile and $theSPath  = $metricList <br>"; -fuzz 10%
	  if ($RMSEmetric < 9000){
	  	$imgList .= "<img class=\"imgthumb\" src=\"$theSPath\" title=\"$theFName\n$metricList\"/>";
	  	$metList .= "<li>$theFName : $metricList</li>";
	  }
	 }
	 
	  $imgList = str_replace("$imagesdroot", "$imageshroot", $imgList);
	$imgList = str_replace("$imagesNotebookroot", "$imageshroot", $imgList);
   $imgList = str_replace("$imagesfroot", "$imageshroot", $imgList);
   $uploadfile = str_replace("$imagesNotebookroot", "$imageshroot", $uploadfile);
	 //print "UploadFile : $uploadfile <br> Server path <br> $fC files have been read for comparison from the database<br>";
	 $origF =  "<img class=\"imgthumb\" src=\"$uploadfile\" title=\"uploaded file\"/>";
	 $imgDiv = "<div id=\"imgDiv\">$imgList</div>";
	 $listDiv = "<div id=\"listDiv\"><ul>$metList</ul></div>";
	 
	$htmlH = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>
        Image Match Result
        </title>
        <link rel="stylesheet"
        type="text/css"
        href="/ibis/imgmatch.css"
      />
     </head>
     <body>';
	$htmlF = '</body></html>';
	print "$htmlH \n$origF  $listDiv $imgDiv \n$htmlF ";
/*	
AE
    absolute error count, number of different pixels (-fuzz effected)
FUZZ
    mean color distance
MAE
    mean absolute error (normalized), average channel error distance
MEPP
    mean error per pixel (normalized mean error, normalized peak error)
MSE
    mean error squared, average of the channel error squared
NCC
    normalized cross correlation
PAE
    peak absolute (normalized peak absolute)
PHASH
    perceptual hash for the sRGB and HCLp colorspaces. Specify an alternative colorspace with -define phash:colorspaces=colorspace,colorspace,...
PSNR
    peak signal to noise ratio
RMSE
    root mean squared (normalized root mean squared)


		*/ 
?>

