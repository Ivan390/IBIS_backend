
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
	if ($tagstring == "anim")	{
		$prefix = "anim";
	}elseif ($tagstring == "veg"){
		$prefix = "veg";
	}
	
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
	
	 //	print "This is the tagstring $tagstring <br>";
	$newName = $prefix.$numFile.$extn;
	print "The new name ".$newName." <br>" ;
	$uploaddir = "/var/www/html/ibis/Data/Images/temp/";			
	$uploadfile = $uploaddir.$newName ;
	$tmpFilePath = $_FILES['picture']['tmp_name'];
	if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
		exec("/usr/bin/convert -resize 400x300! $uploadfile $uploadfile"); 
	  print("File upload was successful <br>");
	}
	 //$outputA ="";
	$stmt3 = $mysqli->prepare("SELECT filename, serverpath from Media where filename like '%$prefix%'"); 
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
	  
		   // $commnd = "/usr/bin/compare -metric MAE $uploadfile $theSPath null:";
		//   exec("/usr/bin/convert -resize 300x300! $uploadfile ");
		 	 $MSEmetric =  `/usr/bin/compare -metric MSE $uploadfile $theSPath null: 2>&1`;
		   $mseMetval = explode(" ", $MSEmetric);    
	 		 //$fuzzmetric =  `/usr/bin/compare -metric Fuzz $uploadfile $theSPath null: 2>&1`;
     	 //$fuzzMetval = explode(" ", $fuzzmetric);
		   $PSNRmetric =  `/usr/bin/compare -metric PSNR $uploadfile $theSPath null: 2>&1`;
		   $PSNRMetval = explode(" ", $PSNRmetric);
			 $RMSEmetric =  `/usr/bin/compare -metric RMSE $uploadfile $theSPath null: 2>&1`;
			 $rmseMetval = explode(" ", $RMSEmetric);
			 
			 $AEmetric = `/usr/bin/compare -metric AE -fuzz 70% $uploadfile $theSPath null: 2>&1`;
			 //$intSum = ( $mseMetval[0] + $PSNRMetval[0] + $rmseMetval[0] )/3;
			 $intSum =  $AEmetric;
			 
	  	 if ($intSum < 20000){
	  	 $metricList2 = "<li>Filename $theFName : metric avg $intSum <br>PSNR : $PSNRmetric <br>MSE : $MSEmetric <br> RMSE : $RMSEmetric <br> AE : $AEmetric</li>";
	 		   $metricList .= "<li>Filename $theFName : metric avg $intSum <br>PSNR : $PSNRmetric <br>MSE : $MSEmetric <br> RMSE : $RMSEmetric <br> AE : $AEmetric</li>";
	  		 $imgList .= "<img class=\"imgthumb\" src=\"$theSPath\" title=\"$metricList2\" width=\"100px\" height=\"100px\"/>";
	 		}
		   // $metricList .= "<li>Filename $theFName : MSEmetric $MSEmetric</li>";
		    
		    //$psnmetricList .= "<li>Filename $theFName : PSNRmetric $PSNRmetric</li>";
		   // $metricList .= "<li>Filename $theFName : RMSEmetric $RMSEmetric</li>";
		    
		   
		   // foreach ($outputA as $MAEmetric  ){
		  // print "this is the metric $MAEmetric ";
		 // }
	 }
	//  $imgList = str_replace("$imagesdroot", "$imageshroot", $imgList);
   $imgList = str_replace("$imagesNotebookroot", "$imageshroot", $imgList);
	 $imgDiv = "<div id=\"imgDiv\">$imgList</div>";
	 $metDiv = "<div id=\"metDiv\">$metricList</div>";
	$HTMLH = '<!DOCTYPE html>
	<html>
		<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
    <title>
      I B I S - Indexed Biological Information Server Picture Comparer
    </title>
	<style type="text/css">
	#metdiv {
	position : relative;
	width : 80%;
	height : 200px;
	border : 1px solid black;
	border-radius : 25px;
	overflow : auto;
	padding : 10px;
	margin : 20px;
	}
	</style>
		</head>
		<body> ';
	$htmlB = "$imgDiv" . "$metDiv </body> </html>";
	
	
	
	print "$HTMLH $htmlB ";
		 
?>

