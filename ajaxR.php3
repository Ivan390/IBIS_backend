<?php
$js = '<!doctype html>
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
    <title>JQUERY</title>
    <script type="text/javascript" src="../ibis/jquery-1.11.3.js"" ></script>
    <style>
    	#response {
    	position : relative;
    	color : red;
    	border : 1px black solid;
    	border-radius : 20px;
    	font-size : 28px;
    	width : auto;
    	height : auto;
    	top : 100px;
    	left : 100px;
    	}
    </style>
    <script>
    $(document).ready(function() {
    
    	    document.location = "http://127.0.0.1/candas/JQtestsForms.html";
    }
    
    );
    </script>
</head><body>';
$je = '</body></html>';

if ($_FILES){
$content = "Files array is available";
}else{
$content= "Files array is no available";
}

print "$js $content $je";



?>
