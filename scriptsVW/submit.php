<?php    ini_set('display_errors', 'On'); error_reporting(E_ALL); 
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: Mon,26 JUL 1997 05:00:00 GMT");
#
if (!isset ($_GET["I"]) || $_GET["I"]<>"DEMO" ){echo 'invalid account'; return;}
#
$location       = '../uploadVW/';         // our location relative to folder were we have to put the data
#
if (isset ($_GET["F"])) { 
        $fp     = fopen($location.'wflash.txt', 'w');
        fwrite($fp, "F=".$_GET["F"]); }
elseif (isset ($_GET["S"]) ){
        $fp     = fopen($location.'wflash2.txt', 'w');
        fwrite($fp, "S=".$_GET["S"]); }
else {  echo 'invalid data'; return;}
#
fclose($fp);