<?php
include ("IBISvars.inc");
 	if (!$guest_acc){
  		print "the include file was not included <br>";
 	}
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
	if ($mysqli->connect_error){
   	 die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  	}
  	$prefix = "";
  	$metricMax = 0;
    $justname = $_FILES['picture']['name'];
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	$tagstring = $_POST['imgtag'];
	if ($tagstring == "animal"){
		$prefix = "anim";
		$metricMax = 5000;
	} 
	if ($tagstring == "vegetable"){
		$prefix = "veg";
		$metricMax = 6000;
	}
	if ($tagstring == "mineral"){
		$prefix = "min";
		$metricMax = 4000;
	} 
	$MAEmetricPair = "";
	$metricList =  "";
	$psnmetricList = "";
	$filePair = "";
	$imgList = "";
	$metList = "";
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
	$uploaddir = "/var/www/html/ibis/Data/Images/temp/";
	$uploadfile = $uploaddir.$newName;
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
	  exec("/usr/bin/convert -resize 120x90! $uploadfile $uploadfile"); 
	}
	$stmt3 = $mysqli->prepare("SELECT filename, serverpath from Media where filename like '$prefix%'"); 
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
	  $thumbDir = "/var/www/html/ibis/Data/Images/thumbails/";
	  $theSPath = str_replace("$imagesNotebookroot","$thumbDir",$theSPath );
	  $fC = $fC + 1;
	 $RMSEmetric =  `/usr/bin/compare -metric MSE $uploadfile $theSPath null: 2>&1`;
	 $metricList = "MSE Avg: $RMSEmetric \n";
	  if (strstr($RMSEmetric,$errtext)){
	  	continue;
	  }
	  if ($RMSEmetric < $metricMax){
	  	$imgList .= "<img class=\"imgthumb\" src=\"$theSPath\" title=\"$theFName\n$metricList\" height=\"200px\" width=\"200px\" onclick=\"getNames(this)\"/>";
	  	$metList .= "<li>$theFName : $metricList</li>";
	  }
	 }
	  $imgList = str_replace("$imagesdroot", "$imageshroot", $imgList);
	  $imgList = str_replace("$imagesNotebookroot", "$imageshroot", $imgList);
      $imgList = str_replace("$imagesfroot", "$imageshroot", $imgList);
      $uploadfile = str_replace("$imagesNotebookroot", "$imageshroot", $uploadfile);
      $origF =  "<div id=\"OimageDiv\"><img class=\"imgthumb\" src=\"$uploadfile\" title=\"uploaded file\"/></div>";
	  $imgDiv = "<div id=\"imgDiv\">$imgList</div>";
	  $htmlH = '<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width"/>
        <title>
        Image Match Result
        </title>
        <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
        <script type="text/javascript" src="http://192.168.43.132/ibis/Gmain.js"></script>
        <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
        <script type="text/javascript">
        	function closeThis(){
        		document.location = "/ibis/IBISpiccompare.php3";
        		}
        </script>
        <link rel="stylesheet"
        type="text/css"
        href="/ibis/imgmatch.css"
      />
      <link rel="stylesheet" 
    	type="text/css" 
    	media="only screen and (max-width: 580px)" 
    	href="/ibis/smallerDevice.css" />
     </head>
     <body onload="initForm()"><div id="Retcontainer"><div id="dateTime">
        <div id="dateBlock">The Date</div>
        <div id="timeBlock">The Time</div>       
      </div>
      <div id="logo_image_holder">
        <img id="logo_image" src="http://192.168.43.132/ibis/images/Logo1_fullsizetransp.png" />
      </div><div id="retHeading">Image Match Result</div>
      <div id="subContainer"><input type="text" value="'.$tagstring.'" style="display:none" id="catval">';
	$htmlF = '<div id="listDiv"></div><span class="linkC" onclick="closeThis()" >Dismiss</span></div></div></body></html>';
	print "$htmlH $origF $imgDiv $htmlF ";
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
    root mean squared (normalized root mean squared) //-> this turned out to be the most accurate and efficient
		*/ 
?>
