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
$pageName	= 'ec_fct_generate_html.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-07-27 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# generate html strings to display (parts) of the forecast page
#-----------------------------------------------------------------------
# load the settings
$script	        = 'ec_settings.php';
ws_message (  '<!-- module ec_fct_generate_html.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
# get the data from the weather class
if (!isset ($ecForecast) ) {		// check first if the script is already loaded (for warnings) so the class = OK
	$script	= 'ec_fct_create_arr.php';
	ws_message (  '<!-- module ec_fct_generate_html.php ('.__LINE__.'): loading '.$script.' -->');
	include_once $script;
	$weather 	= new ecPlainWeather ();
}
$ecForecast 	= $weather->getWeatherData($caProvince,$caCityCode);
if (!is_array ($ecForecast) ) {
	ws_message (  '<!-- module ec_fct_generate_html.php ('.__LINE__.'): no good data returned, script ends -->',true);
	ws_message (  'Module ec_fct_generate_html.php ('.__LINE__.'): No good data - ending script');
	return false;;
}
#echo '<pre>'; print_r ($ecForecast); echo '</pre>';
#-------------------------------------------------------------------------------
# now we generate the html to be used for output to the screen
#
$fileTime	= $ecForecast['information']['fileTimeStamp'];
$line1          = 'Environment Canada '.langtransstr('forecast for').':&nbsp;'.$yourArea;
$line2          = '&nbsp;&nbsp;'.langtransstr('Updated').  ':&nbsp;'.ecLongDate($fileTime).' - '.date ($timeFormat,$fileTime); 
# These are the first  line(s) on the one page city forecast
$stringTop ='<div style="text-align: center;">'.$line1.$line2.'</div>';
#
# the icons 
$tdWidth        = floor(100 / $topCount).'%';
$daypart 	= $icon = $PoP = $desc = $tempLow = $tempHigh = '    <tr>'.PHP_EOL;
$first          = PHP_EOL.'      <td style="width: '.$tdWidth.'; vertical-align: bottom; text-align:center;  font-size: 80%;">';
$PoPNeeded	= false;
$count	        = 1;
foreach ($ecForecast['forecast'] as $key => $arr) {
	if ($count > $topCount) {break;} else {$count++;}

	$daypart	 .= $first.'<span style="font-weight: bold;">'.$arr['period'].'</span></td>';

	$icon		 .= $first.'<img src="';
	if ($SITE['ecIconsOwn']) {
		$icon 	.= $ecIconsLoc.$arr['iconNumber'];
		if ($showPoP && $arr['PoP'] > 9) {$icon .= 'p'.$arr['PoP'];}
		$icon 	.= $ecIconsExt;
	} else {
		$icon 	.= $SITE['defaultIconsDir'].$arr['defaultIcon'].'.png';
	}
	$icon		.= '" style="width: '.$wsIconWidth .';" title="'.$arr['iconText'].'" alt="'.$arr['iconText'].'"></td>';

	if ($showPoP && $arr['PoP'] > 0) {
		$PoP	.= $first.'<br />'.langtransstr('PoP').': '.$arr['PoP'].'%<br />&nbsp;</td>';
		$PoPNeeded	= true;
	} else {
		$PoP	.= $first.'</td>';
	}

	$desc		.= $first.$arr['iconText'].'</td>';

	if (isset ($arr['temp']['low']) ){
		$tempLow	.= $first.'Lo: <span style="color: blue;">'.$arr['temp']['low'].'</span></td>';
	}  else {
		$tempLow	.= $first.'</td>';
	}
	if (isset ($arr['temp']['high']) ){	
		$tempHigh	.= $first.'Hi: <span style="color: red;">'.$arr['temp']['high'].'</span></td>';
	}  else {
		$tempHigh	.= $first.'</td>';
	}
}
$daypart	.= PHP_EOL.'    </tr>'.PHP_EOL;
$icon		.= PHP_EOL.'    </tr>'.PHP_EOL;
$PoP            .= PHP_EOL.'    </tr>'.PHP_EOL;
$desc	        .= PHP_EOL.'    </tr>'.PHP_EOL;
$tempLow	.= PHP_EOL.'    </tr>'.PHP_EOL;
$tempHigh	.= PHP_EOL.'    </tr>'.PHP_EOL;

#-------------------------------------------------------------------------------
$ecIcons 	= PHP_EOL.'<table class= "genericTable" style="width: 100%;">
  <tbody>
';
$ecIcons .= $daypart.$icon.$desc.$tempHigh.$tempLow;
if ($PoPNeeded) {$ecIcons .= $PoP;}
$ecIcons .= '  <tbody>
</table>'.PHP_EOL;
# -----------
$ecPlainTextHead = '<p style="margin: 4px; color: blue; font-size: 200%;">7-DAY FORECAST</p>'.PHP_EOL;
$rowColor	= 'row-dark'; // = row-light;
$ecPlainText= '
<table class="genericTable" style="width: 100%;  text-align:left;">
  <tbody>'.PHP_EOL;
foreach ($ecForecast['forecast'] as $key => $arr) {
	$daypartString = $arr['daypart'];
	$ecPlainText .= '
    <tr class="'.$rowColor.'" >
      <td style="vertical-align: top; text-align:right;  font-weight:bold; padding: 10px 10px 10px 10px;"><span style="">'.$daypartString.'</span></td>';
    $ecPlainText .= '
      <td  style="vertical-align: top; padding: 10px 10px 10px 10px;">'.$arr['forecastText'].'</td>
    </tr>'.PHP_EOL;
    if ($rowColor == 'row-dark') {$rowColor = 'row-light';} else {$rowColor = 'row-dark';}
}
$ecPlainText .= '
  <tbody>
</table>'.PHP_EOL;

$city = $ecForecast['information']['location'];
$creditLink = '
&nbsp; Script developed by <a href="http://leuven-template.eu/index.php" target="_blank">Weerstation Leuven</a>
&nbsp; Forecast from <a href="http://ec.gc.ca/meteo-weather/default.asp?lang=En&amp;n=FDF98F96-1" target="_blank">Environment Canada</a> for '.$city;
return true;

# ================
function ecLongDate ($time) {
	global $dateLongFormat;
#
	$longDays		= array ("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$longMonths		= array ("January","February","March","April","May","June","July","August","September","October","November","December");
#
	$longDate = date ($dateLongFormat,$time);
	$from	= array();
	$to		= array();
	for ($i = 0; $i < count($longDays); $i++) {
		if (ecfound($longDate,$longDays[$i])) {
			$from[] = $longDays[$i];
			$to[] 	= langtransstr($longDays[$i]);
			break;
		}
	}
	for ($i = 0; $i < count($longMonths); $i++) {
		if (ecfound($longDate,$longMonths[$i])) {
			$from[] = $longMonths[$i];
			$to[] 	= langtransstr($longMonths[$i]);
			break;
		}
	}
	$longDate = str_replace ($from, $to, $longDate);
	return $longDate;
}

# Returns whether needle was found in haystack
function ecFound($haystack, $needle){
$pos = strpos($haystack, $needle);
   if ($pos === false) {
   return false;
   } else {
   return true;
   }
}
#---------------------------------------------------------------------------
