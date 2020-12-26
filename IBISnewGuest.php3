<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/xml; charset=utf-8" />
   <title>
    I B I S Guest Book
   </title>
   <script type="text/javascript" src="/ibis/jquery-1.11.3.js"> </script>
   <script type="text/javascript" src="/ibis/Gmain.js"></script>
   <script type="text/javascript" src="/ibis/dateshorts.js"></script>
   <script type="text/javascript" src="/ibis/DD_rondies.js"></script>
   <script type="text/javascript">
	  DD_roundies.addRule('.littleDD', 35);
	  DD_roundies.addRule('.bigDD', 25);
	 </script>
	 <script type="text/javascript">
	 	$(document).ready(function(){
	 	 	$('#pictures').change(handleFileSelect);	
	 		
	 	})
	 </script>
 
  <link rel="stylesheet"
     type="text/css"
     href="/ibis/guestcss.css"
  />
  </head>
  <body id="TheBody" onload="initForm()">
  	<div id=allContainer class="littleDD">
   	<div id="dateTime">
     	<div id="dateBlock">The Date</div>
     	<div id="timeBlock">The Time</div>       
   	</div>
   	<div id="logo_image_holder">
     	<img id="logo_image" src="/ibis/images/Logo1_fullsizetransp.png"  />
   	</div>
   	<div id=pgButtons>
     <a id="backButton" href="/ibis/IBISmain.html" class="buttonclass littleDD">Back to Main Page </a>
     <input id="submitButton" type="button" onclick="submitThis()" class="buttonclass littleDD" value="Submit"/>
    </div> 
  	<div id="gTitle">Guest Book</div>
  	<div id="detailcont">
    <div id="detail_fs" class="infoblock littleDD">
     	<form name="guestBook" action="../cgi-bin/IBIScollectGuestData.php3" method="POST" enctype="multipart/form-data">
	  		<input type="text" name="kingdom" value="Guestbook" class="hiddentext"/>
	   		<p class="inputclass">
					<label class="labelClass">Please enter your name.</label>
					<input type="text" name="gname" class="oneliner littleDD"  />
	  		</p>
	  		<p class="inputclass">
					<label class="labelClass">What is on your mind?.</label>
					<textarea name="Gcomment" id="gcommnt" class="multiliner littleDD" ></textarea>
	  		</p>
	  		<div id="guestPic">
					<label class="labelClass">Add a picture</label></br>
					<input id="pictures" type ="file" name="pictures" class="oneliner" />
					<div id="optionsDsplay" class="nothiddentext"></div>
					<div id="GuestPic"></div>
					<div id="newtagslist" class="nothiddentext"></div>
	  		</div>
      </form>
      <?php
   	include ("IBISvars.inc");
	 	if (!$guest_acc){
			print "the include file was not included <br>"; 
	 	}
		$mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
		if ($mysqli->connect_error){
				die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
		}
    $stmt = $mysqli->prepare("SELECT Gcomment, serverpath, entryDate, Gname FROM Media, Guestbook WHERE filename LIKE '%guest%' AND mediarefs=filename order by entryDate DESC") or die ("cannot create statement.");
    $picList ="";
		if ($stmt->execute()){
			$stmt->bind_result($tag, $serverpath, $entryDate, $gname  ) or die ("cannot bind parameters.");
			$countMax = 3;
			while ($stmt->fetch()){
				$theDatelist = explode(" ", $entryDate);
				$justDate = $theDatelist[0];
				$tag = "\t$justDate \n $gname said $tag"; 	
				$fileName = str_replace("$imagesfroot", "$imageshroot", $serverpath);
				$fileName = str_replace("$imagesdroot", "$imageshroot", $fileName);
				$fileName = str_replace("$imagesNotebookroot", "$imageshroot", $fileName);
				$picList .= "$fileName:$tag::";
			}
		}
		$stmt->close();
   ?>
      <div id=guestArea class="littleDD">
    <div id="hiddencatPicsList" class="specimage hiddentext"><?php print $picList; ?></div>
	  <div id="guestImages"><input type="button" id="showguests" class="buttonClass" onclick="showGuests()" value="<Show Guests>" />
	  <div id="catPicsList" class="specimage"></div>
   </div>
    </div>
  </div>
  
   
</div>
</div>
<script type="text/javascript" src="http://192.168.43.132/ibis/fileselecter.js"></script>
<script type="text/javascript" src="http://192.168.43.132/ibis/guests.js"></script>
</body> 

</html>
