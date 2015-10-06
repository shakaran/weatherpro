<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName       ='yahooForecast2.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-27-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# Display a list of forecast date from Yahoo
#-------------------------------------------------------------------------------
# First get the data from a weather class 
#
if (!isset ($yahooArray)) {
	ws_message (  	'<!-- module yahooForecast2.php ('.__LINE__.'): loading  yahoo.weather2.php -->');
	include_once 	'yahoo.weather2.php';
	$weather        = new yahooWeather();
#	$yahooArray      = $weather->getWeatherData($SITE['yaPlaceID']);
} else {
	ws_message (	'<!-- '.$pageName.' - yahoo data already loaded --> ');
}
if (isset ($_REQUEST['city']) ) {$yaPlaceID  = $_REQUEST['city'];} else {  $yaPlaceID = $SITE['yaPlaceID'];}
$yahooArray      = $weather->getWeatherData($yaPlaceID);
#---------------------------------------------------------------------------
# define array and fill first row with headings
#---------------------------------------------------------------------------
$forecast = array ();   			
$forecast[0]['period']		=langtransstr('date');
$forecast[0]['condition']	=langtransstr('forecast');
$forecast[0]['iconUrl']		=langtransstr('icon');
$forecast[0]['tempLow']		=langtransstr('low');
$forecast[0]['tempHigh']	=langtransstr('high');
#---------------------------------------------------------------------------
# load icon translate if necessary
include_once ('wsIconUrl.php');
#---------------------------------------------------------------------------
#process each forecast		echo '<pre>'.PHP_EOL; print_r($yahooArray);
if ($SITE['fctOrg'] <> 'yahoo') {$style = 'style="height: 43px;"';} else {$style = 'style="height: 20px;"';} 
$id='1';
foreach ($yahooArray['forecast'] as $arr) {
	if ($arr['timestamp'] == 'timestamp') {continue;}
	$unixTime 			= $arr['timestamp'];
	$forecast[$id]['period']	= langtransstr(date('l', $unixTime)).' '.date('j', $unixTime).' '.langtransstr(date('F', $unixTime));
	$forecast[$id]['condition']	= yahooSplit($arr['condition']);
	$notUsed = '';	$iconOut='';	$iconUrlOut = '';
	wsChangeIcon ('yahoo',$arr['icon'], $iconOut, $arr['iconUrl'], $iconUrlOut, $notUsed);
	$forecast[$id]['iconUrl']	= '<img alt="icon '.$arr['condition'].'" src="'.$iconUrlOut.'" '.$style.'/>';
	$forecast[$id]['tempLow']	= $arr['tempLow'];
	$forecast[$id]['tempHigh']	= $arr['tempHigh'];
	$id++;
}
#-------------------------------------------------------------------------------
# now we are going to print the data to the screen
#-------------------------------------------------------------------------------
if ($SITE['fctOrg'] <> 'yahoo') {echo '<div class="blockDiv">'.PHP_EOL;}
$rowcolor=0;
if (isset ($_REQUEST['city']) ) {$area_yahoo =  $yahooArray['request_info'][0]['city'];} else {$area_yahoo = $SITE['organ'];}
echo '<h3 class="blockHead">&nbsp;5 '.langtransstr('day forecast for').' '.$area_yahoo."</h3>".PHP_EOL;
echo '
<table class="genericTable">
<tbody>
<tr class="row-dark">'.PHP_EOL;
// print headings
echo '<th>'.$forecast[0]['period'].'</th>
<th>'.$forecast[0]['condition'].'</th>
<th>'.$forecast[0]['iconUrl'].'</th>
<th>'.$forecast[0]['tempLow'].'</th>
<th>'.$forecast[0]['tempHigh'].'</th>'.PHP_EOL;
echo '</tr>'.PHP_EOL;
$style='row-light';
for ($i=1;$i<=count($forecast)-1;$i++) {
	echo '<tr class="'.$style.'">
<td>'.$forecast[$i]['period'].'</td>
<td>'.$forecast[$i]['condition'].'</td>
<td>'.$forecast[$i]['iconUrl'].'</td>
<td>'.$forecast[$i]['tempLow'].'</td>
<td>'.$forecast[$i]['tempHigh'].'</td>'.PHP_EOL;
	echo '</tr>'.PHP_EOL;  
	if ($rowcolor == 0)										// for odd even lines with different color
		{$style='row-dark'; $rowcolor = 1;} 
		else 
		{$style='row-light'; $rowcolor = 0;}
}
$logo ='https://s.yimg.com/rz/l/yahoo_weather_en-US_f_pw_119x34_2x.png';
#$logo ='http://l.yimg.com/a/i/brand/purplelogo//uh/us/news-wea.gif';
if ($SITE['fctOrg'] <> 'yahoo') {
        $credit ='<small style="color: white;">(v2) '.
        langtransstr('Original script by').'&nbsp;<a href="http://leuven-template.eu/" target="_blank">Weerstation Leuven</a><br />'.
        langtransstr('Forecast for').'&nbsp;'.$yahooArray['request_info'][0]['city'].'&nbsp;'.
        langtransstr('updated').'&nbsp;'.$yahooArray['request_info'][0]['time'].'</small>';
        echo '<tr class="blockHead">
<td><a href="http://weather.yahoo.com" target="_blank" style="vertical-align: middle;">
<img style="max-height: 24px; vertical-align: bottom;" src="'.$logo.'"  alt="yahoo logo" /></a></td>'.PHP_EOL;
        echo '<td colspan="4">'.$credit.'</td></tr>'.PHP_EOL;
        echo '</tbody></table>'.PHP_EOL;
}else {
        echo '</tbody></table>'.PHP_EOL;
        echo '<h3 class="blockHead"><small style="color: white;">(v2)&nbsp;Yahoo&nbsp;'.
        langtransstr('Forecast for').'&nbsp;'.$yahooArray['request_info'][0]['city'].'&nbsp;'.
        langtransstr('updated').'&nbsp;'.$yahooArray['request_info'][0]['time'].'</small>'.'</h3>'.PHP_EOL;
}
if ($SITE['fctOrg'] <> 'yahoo') {echo '</div>'.PHP_EOL;}

#
function yahooSplit($words) {
	$arrTxt=explode ('/',$words);		// AM Light Snow/ Drizzle late
	$cndTxt='';
	$iEnd=count($arrTxt)-1;
	for ($i2=0;$i2<=$iEnd;$i2++)
	{
		$text=$arrTxt[$i2];
		if ($text <> '') {
//		echo '<!-- '.$text.' -->'.PHP_EOL;
			if (substr($text,0,2) == 'AM') {
				$cndTxt.=langtransstr('Morning'). ': ';
				$text=substr($text,2);
			}
			if (substr($text,0,2) == 'PM') {
				$cndTxt.=langtransstr('Afternoon'). ': ';
				$text=substr($text,2);
			}
			if (substr($text,-5) == ' Late') {
				$cndTxt.=langtransstr('Late'). ': ';
				$text=substr($text,0,strlen($text)-5);
			}				
			if (substr($text,0,2) == 'PM') {
				$cndTxt.=langtransstr('Afternoon'). ': ';
				$text=substr($text,2);
			}			
			$cndTxt.=langtransstr($text);
			if ($iEnd > $i2) {$cndTxt.='/ ';}
		}
	}
	return( $cndTxt);
}
?>
