<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    <title> Search Form</title>
    <script type="text/javascript" src="http://192.168.43.132/ibis/jquery-1.11.3.js"> </script>
    <script type="text/javascript" src="http://192.168.43.132/ibis/Gmain.js"></script>
	<script type="text/javascript" src="http://192.168.43.132/ibis/dateshorts.js"></script>
	<link rel="stylesheet"
        type="text/css"
        href="http://192.168.43.132/ibis/IBIS_maincss.css"
      /> 
	<link rel="stylesheet" type="text/css" media="only screen and (max-width: 500px)" href="http://192.168.43.132/ibis/smallerDevice.css" /> 
  </head> 
    <body onload=initForm() style="width:100vw">
      <div id="SFallContainer" class="ac">
        <div id="dateTime">
	        <div id="dateBlock">The Date</div>
	        <div id="timeBlock">The Time</div>       
	      </div>
	      <div id="logo_image_holder">
	        <img id="logo_image" src="/ibis/images/Logo1_fullsizetransp.png"  />
	      </div> 
	      <div id=pgButtons>
			<a id="backButton" href="/ibis/IBISmain.html" class="buttonclass">Back to Main Page </a>
			<input type="button" id="getDetails" class="buttonclass hiddentext" onclick="getDetails()" value="Get More Details" />
	      </div>
	      <div id="catHeading" class="cathead">
	        <?php
	        	$pageHead = ucfirst($_POST['catValue']);
	        	echo "$pageHead";
	        ?> Index
	      </div>
	      <form name="infoForm" action="../cgi-bin/IBISgetDetails.php3" method="POST" enctype="multipart/form-data" class="hiddentext">
	        <input type="text" id="genusRef" name="genusRef" class="" value="" />
	        <input type="text" id="speciesRef" name="speciesRef" class="" value="" />
	        <input type="text" id="recID" name="recID" value="" />
	        <input type=text id="catVal" name="catVal" class="" value=
			    <?php
			      $htmlheading = ($_POST['catValue']);
			      echo "$htmlheading"; 
			    ?>
	        />
          </form>
	      <div id=subContain >
	       <div id="theCategory">
	        <div id="theCategoryHeading"></div>
	        <div id="catPicsList" class="catCont" > </div>
	        <div id="picTag" class="imgtag" ></div>
	      </div>
	      <div id=findOptions class="littleDD">
	      	<span id="DDLOption" ></span>
	        <select id="DDL2" class="selectList" ></select></br>
	        <span id="DDL3Option"></span>
	        <span id="buttContSpan" >
	         	<span id="op2btSpan" onClick="getSelectInfo()"> 
	         		<input type="button" id="option2btn" class="buttonclass" onClick="getSelectInfo()" value="Selection">
	         	</span>
	         	<span id="opt3btSpan" onClick="getkeyword()">
	          		<input type="button" id="option3btn" class="buttonclass" onClick="getkeyword()" value="Keyword">
	          	</span>
	        </span>
	      </div>
	     <div id=infoSet class=infoset></div>
	     <div id="GBlock"><span id="Ghead">Glossary</span>
	      	<p class="Gselect" onclick="readGloss(this)">A</p>
			<p class="Gselect" onclick="readGloss(this)">B</p>
			<p class="Gselect" onclick="readGloss(this)">C</p>
			<p class="Gselect" onclick="readGloss(this)">D</p>
			<p class="Gselect" onclick="readGloss(this)">E</p>
			<p class="Gselect" onclick="readGloss(this)">F</p>
			<p class="Gselect" onclick="readGloss(this)">G</p>
			<p class="Gselect" onclick="readGloss(this)">H</p>
			<p class="Gselect" onclick="readGloss(this)">I</p>
			<p class="Gselect" onclick="readGloss(this)">J</p>
			<p class="Gselect" onclick="readGloss(this)">K</p>
			<p class="Gselect" onclick="readGloss(this)">L</p>
			<p class="Gselect" onclick="readGloss(this)">M</p>
			<p class="Gselect" onclick="readGloss(this)">N</p>
			<p class="Gselect" onclick="readGloss(this)">O</p>
			<p class="Gselect" onclick="readGloss(this)">P</p>
			<p class="Gselect" onclick="readGloss(this)">Q</p>
			<p class="Gselect" onclick="readGloss(this)">R</p>
			<p class="Gselect" onclick="readGloss(this)">S</p>
			<p class="Gselect" onclick="readGloss(this)">T</p>
			<p class="Gselect" onclick="readGloss(this)">U</p>
			<p class="Gselect" onclick="readGloss(this)">V</p>
			<p class="Gselect" onclick="readGloss(this)">W</p>
			<p class="Gselect" onclick="readGloss(this)">X</p>
			<p class="Gselect" onclick="readGloss(this)">Y</p>
			<p class="Gselect" onclick="readGloss(this)">Z</p>
			<p class=" Gbutton" onclick="closeGl()" style="display : none;">close it</p>
			<div id="retBlock" onclick="" style="display : none;"></div>
	      	<div id="defBlock" onclick="closethis(this)" style="display : none;"></div>
	     </div>
	   </div>
    </div>
    <script>
		var DDL1Text = "";
		var DDL3select = "";
        var thiscatVal = $("#catVal").val();
   		if (thiscatVal == "vegetables"){
   DDL1Text = '<select id="DDL1Veg" class="littleDD selectList" onClick="populateDDL2()" onChange="populateDDL2()" width="60px"><option class="listItem " >Family</option><option class="listItem " value="genus">Genus</option><option class="listItem " value="species">Species</option></select>';
DDL3select='<select id="DDL3dd" class="littleDD selectList"><option value="descrip">Description</option><option value="ecology">Ecology</option><option value="distrib">Distribution</option><option value="uses">Uses</option>				<option value="growing">Growing</option><option value="nameNotes">Name Notes</option><option value="localNames">Local Names</option><option value="category">Category</option></select><input type="text" id="ddl3Txt" name="ddl3Txt">';
   
  		}
  		if (thiscatVal == "animals"){
   	DDL1Text = '<select id="DDL1Veg" class="littleDD selectList" onClick="populateDDL2()" onChange="populateDDL2()" width="60px"><option class="listItem " >Class</option><option class="listItem " >Order</option><option class="listItem " >Family</option><option class="listItem" value="genus">Genus</option><option class="listItem " value="species">Species</option></select>';
   DDL3select='<select id="DDL3dd" class="littleDD selectList">  <option value="descrip">Description</option>   <option value="ecology">Ecology</option><option value="distrib">Distribution</option>   <option value="habits">Habits</option>   <option value="status">Status</option><option value="nameNotes">Name Notes</option><option value="localNames">Local Names</option></select><input type="text" id="ddl3Txt" name="ddl3Txt">';
  		}
  		if (thiscatVal == "minerals"){
   DDL1Text = ' <select id="DDL1Min" class="littleDD selectList " onClick="populateDDL2()" onChange="populateDDL2()" width="60px"><option class="listItem " >Name</option><option class="listItem " value="Mgroup">Group</option><option class="listItem " value="chemForm">Chemical Formula</option> </select>';
   DDL3select='<select id="DDL3dd" class="littleDD selectList"><option value="uses">Uses</option><option value="origin">Origin</option><option value="notes">Notes</option><option value="characteristics">Characteristics</option></select><input type="text" id="ddl3Txt" name="ddl3Txt">';
		}
$("#DDLOption").html(DDL1Text);
$("#DDL3Option").html(DDL3select);
   
function populateDDL2(){
	$("#catPicsList").html("");
	var catVal = $("#catVal").val();
  	if (catVal == "minerals"){
    	var DDL1Minvalue = $("#DDL1Min").val();
    	var  DDL1value = "";
	}else{
   		var DDL1Minvalue = "";
   		var DDL1value = $("#DDL1Veg").val();
  	}
    $.ajax({
    	url : '../cgi-bin/IBISindexCreator.php3',
    	type : "get",
      	async : "false",
     	data : {ddl1val : DDL1value, ddl1minval : DDL1Minvalue, catValtext: catVal },
     	success : function(data){
     	var testregexp = /no match/;
	    if (testregexp.test(data)) {
	   		alert("Okayyyy");
	    }else {
	        var theretval = "";
	        var thedata = data.split(":");
	        for (b = 0; b < thedata.length; b++){
	          if (!thedata[b] == ""){
	            theretval += "<option>" + thedata[b] + "</option>";
	          }
	        }
        	$("#DDL2").html(theretval);
        	$("#infoSet").html("");
        	$("#specipicH").html("");
        }
     }
    });
}
function getSelectInfo(){
  var catVal = $("#catVal").val();
  if (catVal == "minerals"){
    var DDL1Minvalue = $("#DDL1Min").val();
    DDL1value = "";
  }else{
  var DDL1value = $("#DDL1Veg").val();
    DDL1Minvalue = "";
  }
  var DDL2value = $("#DDL2").val();
    if (DDL2value == "") {
      alert("please select a catagory from the first list please");
      exit;
      }
	  $.ajax({
	      url : '../cgi-bin/IBISgetCatIndex.php3',
	      type : "get",
	      async : "false",
	      data : {ddl1val: DDL1value, ddl2val : DDL2value, ddl1minval : DDL1Minvalue, catValtext : catVal },
	      success : function(data){
	      var testregexp = /no match/;
  	      if (testregexp.test(data)) {
	        alert("Okayyyy");
       	  }else {
	      var valueList = "";
	      var theSet = "";
	      var thedata = data.split(";");
	      for (b = 0; b < thedata.length; b++){
	        if (!thedata[b] == ""){
	          valueList = thedata[b].split(":");
	          if (catVal == "vegetables" || catVal == "animals"){
	          binomial = valueList[2]+":"+valueList[1];
	          }
	          if (catVal == 'minerals') {
	          	binomial = valueList[1];
	          }
	          theSet += "<span class=\"linksclass\"><img src=" + valueList[0] + " title=" + binomial + " onClick=showSummarry(this)></span>"; 
	          }
	        }
	        $("#catPicsList").html(theSet); 
	        $("#infoSet").html("");
	      } 
     	}
      });
}

function showSummarry(that){
 var thedata2 = "";
 var valueList2 = "";
 var valueList3 = "";
  var thiscatVal = $("#catVal").val();
  var picTitle = that.title;
  var picsrc = that.src;
  $.ajax({
      url : '../cgi-bin/IBISgetSummarry.php3',
      type : "get",
      async : "false",
      data : {pictitle:picTitle, catValtext:thiscatVal},
      success : function(data){
      var testregexp = /no match/;
	     if (testregexp.test(data)) {
	        alert("Okayyyy");
       }else { 
       // animals::Aonyx:capensis:Cape ClawlessOtter:animals::Pternistes:capensis:CapeFrancolin:
	      thedata2 = data.split(":");
	     if (thedata2[0] == "vegetables"|| thedata2[0] == "animals"){
	       valueList2 = "<div id=speciePic><img src=\"" + picsrc + "\" title=\"" +thedata2[2]+ " :  "+thedata2[3]+ "\" width=\"200px\" height=\"200px\" /></div><div id=sumDetails class=\"\"><span class=\"headingC\">Family</span><br><span class=\"detailC\">" + thedata2[1] + "</span><br><span class=\"headingC\">Genus</span><br><span class=\"detailC\">" + thedata2[2] + "</span><br><span class=\"headingC\">Species</span><br><span class=\"detailC\">" + thedata2[3] + "</span><br> <span class=\"headingC\">Local Names</span><br><span class=\"detailC localN\">" + thedata2[4] + "</span><br><input type=\"button\" value=\"Dismiss\" onclick=\"closeThis()\" class=\"buttonclass\" id=\"closeInfo\"></div>";
     // valueList3 = "";
   	        $("#speciesRef").val(thedata2[3]);
   	        $("#genusRef").val(thedata2[2]);
   	        $("#recID").val(thedata2[5]);

	     }
	     if (thedata2[0] == "minerals"){
	     	var chemForm = thedata2[4];
	     	var chemArray = chemForm.split("");
	     	chemForm = "";
	     	for (j = 0; j < chemArray.length; j++){
	      		if (!isNaN(chemArray[j])){
	        		chemArray[j] = "<sub>"+chemArray[j]+"</sub>";
	      		}
	      		chemForm += chemArray[j];
	     	}
	     valueList2 = "<div id=speciePic><img src=\"" + picsrc + "\" title=\"" +thedata2[2]+ " :  "+thedata2[3]+ "\" width=\"200px\" height=\"200px\" /></div><div id=sumDetails class=\"\"><span class=\"headingC\">Name</span><br><span class=\"detailC\">" + thedata2[1] + "</span><br><span class=\"headingC\">Group</span><br><span class=\"detailC\">" + thedata2[2] + "</span><br><span class=\"headingC\">Crystal System</span><br><span class=\"detailC\">" + thedata2[3] + "</span><br><span class=\"headingC\">Chemical Formula</span><br><span class=\"detailC\">" + chemForm + "</span><br><input type=\"button\" value=\"Dismiss\" onclick=\"closeThis()\" class=\"buttonclass\" id=\"closeInfo\"></div>";
	    $("#speciesRef").val(thedata2[1]);
     }
     $("#infoSet").html(valueList2); 
     $("#infoSet").show('fast'); 
     $("#speciesRef").val(thedata2[1]);
   	        $("#genusRef").val(thedata2[2]);
   	        $("#recID").val(thedata2[5]);

    } 
    }
  });
$('#getDetails').removeClass("hiddentext");
}

function getDetails(){
   var infoIsSet = $("#infoSet").html();;
   if (infoIsSet == ""){
		alert("Please choose an item");
   }else{
	   	document.infoForm.submit();
   }
}

function closeThis(){
   	$("#infoSet").hide('slow');
}
   
function getkeyword(){
	var ddl3Slct = $('#DDL3dd').val();
	var ddl3Text = $('#ddl3Txt').val();
	var catVal = $("#catVal").val();
  	$.ajax({
      url : '../cgi-bin/IBISkeywordIndex.php3',
      type : "get",
      async : "false",
      data : {ddl3dd: ddl3Slct, ddl3Txt : ddl3Text, catValtext : catVal },
      success : function(data){
      var testregexp = /nomatch/;
	  if (testregexp.test(data)) {
	  	theSet = "<p>No matching Records</p>";
      }else {
	      var valueList = "";
	      var theSet = "";
	      var thedata = data.split("::");
	      for (b = 0; b < thedata.length; b++){
	        if (!thedata[b] == ""){
	          valueList = thedata[b].split(":");
	          if (catVal == "vegetables" || catVal == "animals"){
	         	binomial = valueList[2]+":"+valueList[1];
	          }
	          if (catVal == 'minerals') {
	          	binomial = valueList[1];
	          }
	          theSet += "<span class=\"linksclass\"><img src=" + valueList[0] + " title=" + binomial + " width=\"100px\" height=\"100px\" onClick=showSummarry(this)></span>"; 
	          }
	        }
	      } 
	      $("#catPicsList").html(theSet); 
	      $("#infoSet").html("");
	      $("#specipicH").html(""); 
    	 }
      });
	}				
    </script>
    </body>
</html>   
