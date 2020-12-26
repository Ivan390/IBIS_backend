<?php

function cleanup($somtext){
$pest = '?';
$amper = '&amp;';
$persand = '&';
$space = " ";
$sometext = str_replace("$pest", "$space", $somtext);
$sometext = str_replace("$amper", "$persand", $somtext);
return $sometext;


}
?>
