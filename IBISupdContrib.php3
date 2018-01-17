<?php
if ($_FILES['picture']){
include ("IBISvars.inc");
if (!$guest_acc){
    print "the include file was not included <br>";
  }
$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
//$name = trim($_POST['name']);
$contID = trim($_POST['contID']); 
 $cleanname = str_replace("$space", "$underscore", $name);
 $securityA = trim($_POST['secA']);
 $username = trim($_POST['userName']);
  $prefix = "reg";
  $uploadDate =  date('Y-m-d H:i:s');
  $messg = "";
  $email = trim($_POST['email']);
$stmt = $mysqli->prepare("SELECT count(MediaID) FROM Media WHERE filename LIKE '%$prefix%'") or die ("cannot create statement.");
    if ($stmt->execute()){
      $stmt->bind_result($numFile) or die ("cannot bind parameters.");
      $stmt->fetch();
      $stmt->close();
      }
      $justname = $_FILES['picture']['name'];
      $extn = substr($justname, -4);
				$newName = $prefix.$numFile.$extn ;
        $uploadfile = $uploaddir.$newName ;
        $tmpFilePath = $_FILES['picture']['tmp_name'];
        if(!$tmpFilePath){$fileList = $_POST['medRef'];}
        if ( move_uploaded_file("$tmpFilePath", "$uploadfile") ) {
        $stmt2 = $mysqli->prepare("INSERT INTO Media ( MediaID, type, filename, tags, uploadDate, contribRef, uploaderType, serverpath ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )");
	  			$stmt2->bind_param('ssssssss', $MediaID, $Type, $filename, $tags, $uploadDate, $contribRef, $uploaderType, $serverpath) or die ("cannot bind parameters.");
					$MediaID = 0;
					$Type = "image";
					$filename = $newName;
					$fileList =$filename;
					$tags = $cleanname;
					$contribRef = $contID;
					$uploadDate =  $uploadDate;
					$uploaderType = "c";
					$serverpath = $serverRoot.$filename;
					$stmt2->execute();
					 
					if ($stmt2->affected_rows == -1){
						 $messg =  "IBIS Upload failed <br>";
					}
        $stmt2->close();
        }else {
	 				 $messg = "File upload failed <br>";
        }
       
       
   // $ContribID = '';
    $space = ' ';
    $undescore = '_';
    $role = "c";

    $email = $_POST['email']; 
    $mediaRef = $fileList;
    $regDate = $uploadDate;
    $securityQ = $_POST['secQ'];
    $passwd = trim($_POST['pssWD']);
     $stmt3 = $mysqli->prepare("UPDATE Contributers SET role='$role' , email='$email' , mediaRef='$mediaRef' , regDate='$regDate' , securityQ='$securityQ' , securityA='$securityA' , userName='$username' , passwrd='$passwd' WHERE ContribID='$contID'") or die ("cant prepare statement 3".$mysqli->error);

     $stmt3->execute();
     if ($stmt3->affected_rows == -1){
      $messg =  "the record was not added";
    }
    $stmt3->close();

$htmlhead = '<!doctype html>
<head>
<script type="text/javascript">
function clWin(){
close();
}

</script>
</head>
<body>
<div id="container">
	<input type="button" value="Close" onclick="clWin()" />
</div>'
;
$htmlFoot ='</body></html>';
print "$htmlhead"."$messg". "$htmlFoot";
}else{
print "no upload variable found";
}

 function getUserName($inName, $secA){
		  $nameArray = str_split($inName);
		  $secCount = strlen($secA);
		  $letVal = 0;
		  foreach ($nameArray as $letter){
		    $letVal =+ ord($letter);
		  }
		  $rcode = ceil(($secCount * $letVal)/10); 
		  $theUserName = "$inName" . "$rcode";
		  return $theUserName;
    }
?>
