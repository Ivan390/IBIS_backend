

      <?php
      	include ("IBISvars.inc");
				if (!$guest_acc){
					print("some thing went wrong. IBISvars missing");
				}
				$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS' );
				if ($mysqli->connect_error){
					die('Connect Error ('.$mysqli->connect_errno.')' .$mysqli->connect_error);
				}
				$userR = $_GET['userN'];

				$stmnt1 = $mysqli->prepare("select ContribID, name, lastname, email, securityQ, securityA, mediaRef,username,  passwrd,  serverpath from Contributers, Media where ContribID = \"$userR\" and Contributers.mediaRef = Media.filename");
				$stmnt1->bind_result($contID, $fName, $Lname, $Email, $secQ, $secA, $medRef,$uName,$passWd, $imgP) or die ("could not bind data");
				$stmnt1->execute();
				$stmnt1->fetch();
				$stmnt1->close();
	
$htmlH='<!DOCTYPE html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>
            I B I S - Animal Input Form
        </title>
        <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
        <script type="text/javascript" src="http://192.168.43.132/ibis/main.js"> </script>
        <script type="text/javascript" src="http://192.168.43.132/ibis/fileselect.js"></script>
        <script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"> </script>
        <script type="text/javascript" src="http://192.168.43.132/ibis/dataInputFunctions.js"></script>	
	<script type="text/javascript">
	$(document).ready(function(){
          $(\'#mediaPic\').change(handleFileSelect);	
	  if (sessionStorage.userRef){
             sessVar = sessionStorage.userRef;
	     sesA = sessVar.split("::");
	      conID = sesA[0];
	      $(\'#contrib_ID\').val(conID);
	  }else {
	    alert("You should really not be on this page!")
	    document.location = "IBISmain.html";
	  }
        }) 
	 
       </script>
	<link rel="stylesheet"
        type="text/css"
        href="http://192.168.43.132/ibis/DataInput.css"
  >
    <script type="text/javascript">
function clWin(){
close();
}
function updateDetails(){
	document.udateContrib.submit();
}
</script>
</head>
<body name="regupdateBody" id="TheBody" onload="initForm()">';

$datapart ='
     <div id="allContainer">
      	<div id="lookupdiv" ></div>
	<div id="dateTime">
	 <div id="dateBlock">The Date</div>
	 <div id="timeBlock">The Time</div>       
	 </div>
        <div id="Heading">
	        <img id="reglogo_image" src="http://192.168.43.132/ibis/images/Logo1_fullsizetransp.png">
            <p id="regheadingText">Edit Login Details</p>
        </div>
<div id="subContain">
<form name="udateContrib" action="../../cgi-bin/IBISupdContrib.php3" method="POST" enctype="multipart/form-data">
	<fieldset id="edDetails" class="littleDD">
		<div id="personalDetails" class="littleDD">
			<p><label class="heading labelClass">Edit your Details</label></br></br>
				</p>
			<p> 
				<label class="labelClass" class="requiredf" >Your email address</label>
				<input type="text" name="email" class="inputClass littleDD requiredf" value="'.$Email.'" />
			</p>
			
		</div>
		<div id=imgDisplay2 class="bigDD">
				<label class="labelClass">Change your profile picture</label>
				<input type="file" name="picture" id="regPic" class="littleDD" onchange="handleFileSelect(this)" />
			</div>	
		
		<div id="securityInfo" class="bigDD">
		<div id="imgDisplay1"></div>
			<p>
				<label class="labelClass ">Select a question and enter an answer in the space below.</label>
			</p>
			<p>
				<select id = "secQ" name="secQ" class="labelClass littleDD selectClass" >';
	$optList = ["favColor", "favFood", "uncName", "petName"];
	$listOpt = "";
	foreach ($optList as $optVal){
		if ($optVal == "favColor"){$optText = "What is your favourite colour?";}
		if ($optVal == "favFood"){$optText = "What is your favourite food?";}
		if ($optVal == "uncName"){$optText = "What is the name of your favourite uncle?";}
		if ($optVal == "petName"){$optText = "What is the name of your favourite pet?";}
		if ($optVal == $secQ){
			$listOpt .="<option value = \"$optVal\" selected=\"selected\">$optText</option>";
			continue;
		}
		$listOpt .= "<option value = \"$optVal\">$optText</option>";
	}			
	$selList = $listOpt;			
					
	$restHTML="				</select></br>
				<input type=\"text\" name=\"secA\" id=\"secA\" class=\"labelClass littleDD\" value=\"$secA\" />
			</p>
		</div>
		<input type=\"button\" value=\"Submit\" onclick=\"updateDetails()\" />
		<input type=\"text\" id=\"pssWD\" name=\"pssWD\" class=\"hiddentext\" value=\"$passWd\" />
		<input type=\"text\" id=\"userName\" name=\"userName\" class=\"hiddentext\" value=\"$uName\" />
		<input type=\"text\" id=\"contID\" name=\"contID\" class=\"hiddentext\" value=\"$contID\"/>
		<input type=\"text\" id=\"medref\" name=\"medRef\" class=\"hiddentext\" value=\"$medRef\" />
		<input type=\"text\" id=\"kingdom\" name=\"kingdom\" value=\"Register\" class=\"hiddentext\" >
	</fieldset>
";
$endHTML = '	<input type="button" id="regSubmit" value="Dismiss" onclick="clWin()">
</form></div></body>
<script type="text/javascript">
function clWin(){
close();
}
function updateDetails(){
 document.udateContrib.submit();
}
function handleFileSelect(that) {
	var files = that.files; 
	for (var i = 0, f; f = files[i]; i++) {
		if (!f.type.match(\'image.*\')) {
		 	continue;
	 	}
	 	if (i > 5){
			alert("Select up to 6 media files");
			continue;
		}
		var reader = new FileReader();
		reader.onload = (function(theFile) {
			return function(e) {
				$(\'#imgDisplay1\').append(\'<img class="thumbpic" src="\'+ e.target.result + \'" title="\'+ escape(theFile.name)+ \'" onclick="showOps(this)" /\>\');
			};
		})(f);
		reader.readAsDataURL(f);
		}
	
}
</script>
<html>';
	print "$htmlH"."$datapart"."$selList"."$restHTML"."$endHTML";
	
				
      ?>
      

 

