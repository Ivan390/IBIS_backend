<?php 

include ("IBISvars.inc");
 	if (!$guest_acc){
  	print "the include file was not included <br>"; 
 	}
  $mysqli = new mysqli('localhost', "$guest_acc", "$guest_pass", 'IBIS');
  if ($mysqli->connect_error){
    die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
  }
$retlist2 = "";
$retlist3 = "";
$retlist = "";
$catVal = $_GET['catValtext'];
$DDL2value = array_key_exists('ddl2val',$_GET)?$_GET['ddl2val']: null;
$DDL1value = array_key_exists('ddl1val',$_GET)?$_GET['ddl1val']: null;
$DDL1Minvalue = array_key_exists('ddl1minval',$_GET)?$_GET['ddl1minval']: null; 
$pictitle = array_key_exists('pictitle',$_GET)?$_GET['pictitle']: null; 
if ($DDL1value == ""){
  $fieldval = $DDL1Minvalue;
}
if ($DDL1Minvalue == ""){
  $fieldval = $DDL1value;
}
if ($fieldval == "Order"){
	$fieldval = "Aorder";
}
if ($catVal == "vegetables"){
	$IBIS_T = "Vegetables";
}
if ($catVal == "animals"){
	$IBIS_T = "Animals";
}
if ($catVal == "minerals"){
	$IBIS_T = "Minerals";
}
if ($DDL2value == ""){
  populateDDL1();
}if (!$DDL2value == "") {
  getPics();
}

function populateDDL1() {
$tom = "";
$stmt = ""; 
$retlist ="";
global $mysqli, $fieldval, $IBIS_T ;
	if ($stmt = $mysqli->prepare("SELECT DISTINCT $fieldval FROM $IBIS_T")){
   // print "statement prepared. <br>";
  }else { 
    print "error preparing stmt :". $mysqli->error;
  }
  $stmt->bind_result($tom);
  if ($stmt->execute()){
    		 //print "statement executed.<br>";
  }else {
     print $mysqli->error;
  }
  while ($stmt->fetch()){
    $retlist .= "$tom:";
  }
  $stmt->close();
  print $retlist;
}




?>
