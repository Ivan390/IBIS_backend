<?php 

include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>"; 
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
$retlist4 = "";
function getSummarry() {
global $mysqli, $retlist4;

$stmtZ = "";
$catVal = $_GET['catValtext'];
$pictitle = $_GET['pictitle'];
$binomial = explode(":", $pictitle);
$Pgenus = trim($binomial[0]);
if (count($binomial) > 1){
$Pspecies = $binomial[1];
}else{
$Pspecies = "";
}
//$Pspecies = trim($binomial[1]); // $Pspecies = trim($binomial[1] ? $binomial[1]] : null);
//$Pspecies = trim($binomial[1] ? $binomial[1] : null);
  if ($catVal == "vegetables"){ 
    $stmtZ = "SELECT family, genus, species, localNames FROM Vegetables WHERE species like '$Pspecies%' and genus ='$Pgenus'";
    if ($stmt4 = $mysqli->prepare("$stmtZ")){
  }else { 
    print "error preparing stmt4 :". $mysqli->error;
  }
  if ($stmt4->execute()){
  }else {
     print "error executing stmt4 :". $mysqli->error;
  }
   $stmt4->bind_result($family, $genus, $species, $localnames);
    while ($stmt4->fetch()){
    $retlist4 .= "$catVal:$family:$genus:$species:$localnames";
    }
   
  }
  if ($catVal == "minerals"){
    $stmtZ = "select name, Mgroup, crystalSys, chemForm from Minerals where name= '$pictitle'";
   if ($stmt4 = $mysqli->prepare("$stmtZ")){
  }else { 
    print "error preparing stmt4 :". $mysqli->error;
  }
  if ($stmt4->execute()){
  }else {
     print "error executing stmt4 :". $mysqli->error;
  }
   $stmt4->bind_result($name, $mgroup, $crystalsys, $chemform);
    $stmt4->fetch();
   $retlist4 = "$catVal:$name:$mgroup:$crystalsys:$chemform";
  }
  
   if ($catVal == "animals"){ 
    $stmtZ = "SELECT family, genus, species, localNames FROM Animals WHERE species = '$Pspecies' and genus = '$Pgenus'";
    if ($stmt4 = $mysqli->prepare("$stmtZ")){
  }else { 
    print "error preparing stmt4 :". $mysqli->error;
  }
  if ($stmt4->execute()){
  }else {
     print "error executing stmt4 :". $mysqli->error;
  }
   $stmt4->bind_result($family, $genus, $species, $localnames);
     while ($stmt4->fetch()){
    $retlist4 .= "$catVal:$family:$genus:$species:$localnames";
     } 
 // if ($IBIS_T == "vegetables" || $IBIS_T == "animals"){ 
//
}
  print $retlist4;
  
  }

getSummarry();

?>
