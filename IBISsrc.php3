<?php
include ("IBISvars.inc");
	if (!$guest_acc){
		print "can't connect to database";
		exit;
	}
	
	$mysqli = new mysqli('localhost', "$contrib_acc", "$contrib_pass", 'IBIS');
	if($mysqli->connect_error){
		die('Connect Error ('. $mysqli->connect_errno . ')' .$mysqli->connect_error);
	}
	
	$srcMethod = $_POST['Meth'];
		if ($srcMethod == "new"){
		//print "method is $srcMethod";
		$stmnt = $mysqli->prepare("insert into Sources (type, title, publshr, publAddr, publDate, ISBN, auth, editr, url, pgNum, contribRef) values (?,?,?,?,?,?,?,?,?,?,?)") or die ($mysqli->error);
		$stmnt->bind_param('sssssssssss', $Type, $Title, $Publisher, $PubAddr, $PubDate, $isbn, $Author, $Editor, $URL, $PgNum, $ContribRef) or die ($mysqli->error);
		$Type = trim(array_key_exists('type',$_POST)?$_POST['type']: null); 
		$Title = trim(array_key_exists('title',$_POST)?$_POST['title']:null);
		$Publisher = trim(array_key_exists('publshr',$_POST)?$_POST['publshr']:null);
		$PubDate = trim(array_key_exists('publDate',$_POST)?$_POST['publDate']:null);
		$PubAddr = trim(array_key_exists('publAddr',$_POST)?$_POST['publAddr']:null);
		$isbn = trim(array_key_exists('ISBN',$_POST)?$_POST['ISBN']:null);
		$Author = trim(array_key_exists('author',$_POST)?$_POST['author']:null);
		$Editor = trim(array_key_exists('editor',$_POST)?$_POST['editor']:null);
		$pgNum = trim(array_key_exists('pgNumber',$_POST)?$_POST['pgNumber']:null);
		$URL = trim(array_key_exists('url',$_POST)?$_POST['url']:null);
		$ContribRef = trim(array_key_exists('contributer',$_POST)?$_POST['contributer']:null);
		
		$stmnt->execute() or die ($mysqli->error);
		print "upload went well";
	}elseif ($srcMethod == "update"){
		$recID = trim(array_key_exists('SrcID',$_POST)?$_POST['SrcID']:null);
		$Type = trim(array_key_exists('type',$_POST)?$_POST['type']: null); 
		$Title = trim(array_key_exists('title',$_POST)?$_POST['title']:null);
		$Publisher = trim(array_key_exists('publshr',$_POST)?$_POST['publshr']:null);
		$PubDate = trim(array_key_exists('publDate',$_POST)?$_POST['publDate']:null);
		$PubAddr = trim(array_key_exists('publAddr',$_POST)?$_POST['publAddr']:null);
		$isbn = trim(array_key_exists('ISBN',$_POST)?$_POST['ISBN']:null);
		$Author = trim(array_key_exists('author',$_POST)?$_POST['author']:null);
		$Editor = trim(array_key_exists('editor',$_POST)?$_POST['editor']:null);
		$pgNum = trim(array_key_exists('pgNumber',$_POST)?$_POST['pgNumber']:null);
		$URL = trim(array_key_exists('url',$_POST)?$_POST['url']:null);
		$ContribRef = trim(array_key_exists('contributer',$_POST)?$_POST['contributer']:null);
		$stmnt = $mysqli->prepare("update Sources set type = \"$Type\", title = \"$Title\", publshr = \"$Publisher\", publAddr = \"$PubAddr\", publDate = \"$PubDate\", ISBN = \"$isbn\", auth = \"$Author\", editr = \"$Editor\", url = \"$URL\", contribRef = \"$ContribRef\" where RefID = \"$recID\"") or die ($mysqli->error);
		$stmnt->execute() or die ($mysqli->error);
		print "update went well";
	}
	
	
?>
