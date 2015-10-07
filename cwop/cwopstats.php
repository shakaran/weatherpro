<?php #	ini_set('display_errors', 'On');  error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'cwopstats.php';
$pageVersion	= '3.20 2015-10-01';
#-------------------------------------------------------------------------------
# 3.20 2015-10-01 release 2.8 version / error cwopID removed
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#  MODIFIED VERSION for use in Leuven-Template
#-----------------------------------------------------------------------
#
#   Project:    Data Quality Statistics
#   Module:     CwopStats
#   Purpose:    Provides Data Quality Statistics for Web page display
#   Authors:    Michael (michael@relayweather.com
#
#-----------------------------------------------------------------------

$cwop 		= $SITE['cwopID'];	// Enter your CWOP Identification number Ex. CW3783 = C3783
$cityname	= $SITE['organ'];	// Enter your City,State
$sitename	= $SITE['organ'];  	// Enter sitename
#
$graphdate      = date("Ymd"); // date for graph images url
#
if (!isset($_GET['span'])  || $_GET['span'] == false) {
        $span = 'w4';                   // default 4 weeks  if no SPAN or span=false
} 
else {	$span = trim($_GET['span']);    // we check the input later
}
#
$spanLength	= array('3d', '7d', '14d', '4w', '8w', '13w', '26w', '39w', '52w');     // allowed span values
$span2days	= array(3, 7, 14, 28, 56, 91, 182, 273, 364);
$key 		= 0;
#
if (isset ($_GET['span']) ) {$key = array_search($_GET['span'], $spanLength);} else {$key = false;}
#
if (!$key) {$key = 3;}                  // invalid val;ues substitued with 4 weeks
#
$span		= $span2days["$key"]." Days";
$daycount	= $span2days["$key"];
$fileName	= "http://weather.gladstonefamily.net/site/".$cwop."?days=".$daycount.";tile=10#Data";
if($key <= 3){                          // use these links for data 4 weeks or less     
        $chartbaro	= "http://weather.gladstonefamily.net/qchart/".$cwop."?date=". $graphdate ."&amp;sensor=baro&amp;days=".$span2days["$key"]."";
        $charttemp	= "http://weather.gladstonefamily.net/qchart/".$cwop."?date=". $graphdate ."&amp;sensor=temp&amp;days=".$span2days["$key"]."";
        $chartdew	= "http://weather.gladstonefamily.net/qchart/".$cwop."?date=". $graphdate ."&amp;sensor=dewp&amp;days=".$span2days["$key"]."";
}                      
else {                                  // if the data is greater than 4 weeks, use this data
        $chartbaro	= "http://weather.gladstonefamily.net/cgi-bin/wxsitequal.pl?site=".$cwop."&amp;days=".$span2days["$key"]."&amp;sensor=baro";
        $charttemp	= "http://weather.gladstonefamily.net/cgi-bin/wxsitequal.pl?site=".$cwop."&amp;days=".$span2days["$key"]."&amp;sensor=temp";
        $chartdew	= "http://weather.gladstonefamily.net/cgi-bin/wxsitequal.pl?site=".$cwop."&amp;days=".$span2days["$key"]."&amp;sensor=dewp";
}
//Start Parsing the City page
$url4 	= ($fileName);
$html4	= implode(' ', file($url4));

// Find Baro --  If Baro Out of Tolerance Error is found stop the display of the additional table rows
if (preg_match('|Worst daily average barometer error|Uis', $html4))  {
   preg_match('|Average barometer error: <td align=right>(.*)<tr><Td>Error standard deviation: <td align=right>(.*)<tr><td>Worst daily a|', $html4, $baroerr);
$avbaroerr = trim($baroerr[1]);
$sdbaroerr = trim($baroerr[2]);
}
else{
   preg_match('|Average barometer error: <td align=right>(.*)<tr><Td>Error standard deviation: <td align=right>(.*)</table>|', $html4, $baroerr);
$avbaroerr = trim($baroerr[1]);
$sdbaroerr = trim($baroerr[2]);
}

//Find Avg Temp and Std Dev Temp --  If Temp Out of Tolerance Error is found stop the display of the additional table rows
if (preg_match('|Worst average temperature error|Uis', $html4)) { 
preg_match('|Average temperature error<td align=right>(.*)<tr><td>Worst a|Uis', $html4, $posnegtemperrors);
$temperr = preg_split("/<td align=right>/", $posnegtemperrors[0]);

$avtemperr24 = trim($temperr[1]);
$avtemperr24 = preg_replace('/<font color=red>/', '' , $avtemperr24);
$avtemperrday = trim($temperr[2]);
$avtemperrday = preg_replace('/<font color=red>/', '' , $avtemperrday);
$avtemperrnite = trim($temperr[3]);
$avtemperrnite = preg_replace('/<font color=red>/', '' , $avtemperrnite);
$avtemperrnite = preg_replace('/<tr><td>/', '' , $avtemperrnite);
$avtemperrnite = preg_replace('/Error standard deviation/', '' , $avtemperrnite);
$sdtemperr24 = trim($temperr[4]);
$sdtemperr24 = preg_replace('/<font color=red>/', '' , $sdtemperr24);
$sdtemperrday = trim($temperr[5]);
$sdtemperrday = preg_replace('/<font color=red>/', '' , $sdtemperrday);
$sdtemperrnite = trim(strip_tags($temperr[6]));
$sdtemperrnite = preg_replace('/Worst a/', '' , $sdtemperrnite);
$sdtemperrnite = preg_replace('/<font color=red>/', '' , $sdtemperrnite);
}
else {
preg_match('|Average temperature error<td align=right>(.*)</table>|Uis', $html4, $posnegtemperrors);
$temperr = preg_split("/<td align=right>/", $posnegtemperrors[0]);

$avtemperr24 = trim($temperr[1]);
$avtemperr24 = preg_replace('/<font color=red>/', '' , $avtemperr24);
$avtemperrday = trim($temperr[2]);
$avtemperrday = preg_replace('/<font color=red>/', '' , $avtemperrday);
$avtemperrnite = trim($temperr[3]);
$avtemperrnite = preg_replace('/<font color=red>/', '' , $avtemperrnite);
$avtemperrnite = preg_replace('/<tr><td>/', '' , $avtemperrnite);
$avtemperrnite = preg_replace('/Error standard deviation/', '' , $avtemperrnite);
$sdtemperr24 = trim($temperr[4]);
$sdtemperr24 = preg_replace('/<font color=red>/', '' , $sdtemperr24);
$sdtemperrday = trim($temperr[5]);
$sdtemperrday = preg_replace('/<font color=red>/', '' , $sdtemperrday);
$sdtemperrnite = trim(strip_tags($temperr[6]));
$sdtemperrnite = preg_replace('/Worst a/', '' , $sdtemperrnite);
$sdtemperrnite = preg_replace('/<font color=red>/', '' , $sdtemperrnite);
}


//Find Avg Dew and Std Dev Dew --  If Dew Out of Tolerance Error is found stop the display of the additional table rows
if (preg_match('|Worst average dewpoint error|Uis', $html4)) {
   preg_match('|Average dewpoint error<td align=right>(.*)<tr><td>Worst a|', $html4, $posnegdewerrors);
$errtempdew = preg_split("/<td align=right>/", $posnegdewerrors[0]);

$avdewerr24 = trim($errtempdew[1]);
$avdewerr24 = preg_replace('/<font color=red>/', '' , $avdewerr24);
$avdewerrday = trim($errtempdew[2]);
$avdewerrday = preg_replace('/<font color=red>/', '' , $avdewerrday);
$avdewerrnite = trim($errtempdew[3]);
$avdewerrnite = preg_replace('/<font color=red>/', '' , $avdewerrnite);
$avdewerrnite = preg_replace('/<tr><td>/', '' , $avdewerrnite);
$avdewerrnite = preg_replace('/Error standard deviation/', '' , $avdewerrnite);
$sddewerr24 = trim($errtempdew[4]);
$sddewerr24 = preg_replace('/<font color=red>/', '' , $sddewerr24);
$sddewerrday = trim($errtempdew[5]);
$sddewerrday = preg_replace('/<font color=red>/', '' , $sddewerrday);
$sddewerrnite = trim(strip_tags($errtempdew[6]));
$sddewerrnite = preg_replace('/Worst a/', '' , $sddewerrnite);
$sddewerrnite = preg_replace('/<font color=red>/', '' , $sddewerrnite);
}
else {
preg_match('|Average dewpoint error<td align=right>(.*)</table>|Uis', $html4, $posnegdewerrors);
$errtempdew = preg_split("/<td align=right>/", $posnegdewerrors[0]);

$avdewerr24 = trim($errtempdew[1]);
$avdewerr24 = preg_replace('/<font color=red>/', '' , $avdewerr24);
$avdewerrday = trim($errtempdew[2]);
$avdewerrday = preg_replace('/<font color=red>/', '' , $avdewerrday);
$avdewerrnite = trim($errtempdew[3]);
$avdewerrnite = preg_replace('/<font color=red>/', '' , $avdewerrnite);
$avdewerrnite = preg_replace('/<tr><td>/', '' , $avdewerrnite);
$avdewerrnite = preg_replace('/Error standard deviation/', '' , $avdewerrnite);
$sddewerr24 = trim($errtempdew[4]);
$sddewerr24 = preg_replace('/<font color=red>/', '' , $sddewerr24);
$sddewerrday = trim($errtempdew[5]);
$sddewerrday = preg_replace('/<font color=red>/', '' , $sddewerrday);
$sddewerrnite = trim(strip_tags($errtempdew[6]));
$sddewerrnite = preg_replace('/Worst a/', '' , $sddewerrnite);
$sddewerrnite = preg_replace('/<font color=red>/', '' , $sddewerrnite);
}


//MADIS Rating
preg_match_all('|alt="MADIS rating \d\d0{0,3}\%|Uis', $html4, $madis);
$madis[0][0] = preg_replace('|alt="MADIS rating |', '', $madis[0][0]);
$madis[0][1] = preg_replace('|alt="MADIS rating |', '', $madis[0][1]);
$madis[0][2] = preg_replace('|alt="MADIS rating |', '', $madis[0][2]);
$madis[0][3] = preg_replace('|alt="MADIS rating |', '', $madis[0][3]);
$madis[0][0] = preg_replace('|%|', '', $madis[0][0]);
$madis[0][1] = preg_replace('|%|', '', $madis[0][1]);
$madis[0][2] = preg_replace('|%|', '', $madis[0][2]);
$madis[0][3] = preg_replace('|%|', '', $madis[0][3]);
$qcbaro = trim($madis[0][0]);
$qctemp = trim($madis[0][1]);
$qcdewp = trim($madis[0][2]);
$qcwind = trim($madis[0][3]);