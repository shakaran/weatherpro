<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'hwaFullPage.php';
$pageVersion	= '3.05 2015-04-02';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 3.05 2015-04-02 beta 2.7 release version - validator OK
#---------------------------------------------------------------------------
#
# Customize here:
#
$printGraph	= true;			// original HWA graph / header with 2 coloms for each day (12 hours data)
#
$iconGraph	= true;			// icon type header  with 2 icons for each day (12 hours data)
#
$topCount	= 10;			// max nr of day-part forecasts in icons or graph
$topWidth	= '100%';		// set do disired width 999px  or 100%
#
$chartsGraph	= true;			// high charts graph one colom for every 3 hours
#
$hwaTable	= true;			// table with one line for every 3 hours
#
#
$SITE['scriptIconsWind']= './wsHwa/hwa_icons/';
$windIconsSmall		= $SITE['windIconsSmall'];
#
# Then we get the data from a weather class 
#
include 'hwaCreateArr.php';
$weather 	= new hwaWeather ();
$returnArray 	= $weather->getWeatherData('');
if (!isset ($returnArray['forecast']) ) {
        echo '<h3 style="color: red; text-align: center;">HWA forecast: Invalid data returned for part / all of the forecast data - forecast incomplete </h3>';
        if ($wsDebug && isset ($returnArray) ) {echo '<pre>'; print_r($returnArray); echo '</pre>';}
        return false;
} 
else  { $end_forecast = count($returnArray['forecast']);
        if ($end_forecast < 3 ) {
                echo '<h3 style="color: red; text-align: center;">HWA forecast: incomplete data returned for part / all of the forecast data</h3>';  
                if ($wsDebug) {echo '<pre>'; print_r($returnArray); echo '</pre>';}
                return false; 
        }
}
#
#---------------------------------------------------------------------------
# Now create all tables and graphs to be printed here
#
# echo '<pre>';print_r ($returnArray); exit;
include ('hwaGenerateHtml.php');
if ($skip == true) {
	echo '<h3> No valid input found. All data is in the past.</h3>'.PHP_EOL;
	return;
}

# Now ready for printing to the screen. Use echo for that
#   $stringColom	: original HWA graph
#	$tableIcons		: icon 
#	$graphPart1		: javascript / highcharts graph
#	$hwaListTable	: table with all forecast lines
#
echo '<div class="blockDiv">';
echo '<h3  class="blockHead" style="">HWA 7 '.langtransstr('day graphical forecast for').' '.$SITE['organ'].'</h3>'.PHP_EOL;

if (isset ($printGraph) && $printGraph == true) {
	$margin = '0px';	// for top bottom margin. set to '0px' if not needed or any desired distance
	echo '<!-- enclosing div for hwa print graph -->
<div id="printGraph" style="width: 100%; margin: '.$margin.' auto;">'.PHP_EOL;
	echo $stringColom.PHP_EOL;
	echo '</div>
<!-- eo enclosing div for hwa print graph -->'.PHP_EOL;
}
#
if (isset ($iconGraph) && $iconGraph == true) {
	echo '<div id="iconGraph" style=""><hr />';
	echo $tableIcons.PHP_EOL;
	echo '<hr /></div>';
}
#
if (isset ($chartsGraph) && $chartsGraph == true) {
						// $topwidth is set in first lines of script 
	echo '<br />
<div id="containerTemp">here the graph will be drawn</div>'.PHP_EOL;
	echo $graphPart1.PHP_EOL;
}
if (isset ($hwaTable) && $hwaTable == true) {
	$margin = '10px';	// for top bottom margin. set to '0px' if not needed or any desired distance
	$height = '500px';	// to restrict height to suppress very large/long pages
	$width	= $topWidth; // '100%';	//
	echo '<div style="width: '.$width.'; height: '.$height.'; overflow-y: scroll; margin: '.$margin.' auto;">'.PHP_EOL;
#	echo '<div style="display: block; width: '.$width.'; margin: '.$margin.' auto;">'.PHP_EOL;
	echo $hwaListTable.PHP_EOL;
	echo '</div>'.PHP_EOL;
}
echo $credits.PHP_EOL;
echo '</div>'.PHP_EOL;

$javaFolder = $SITE['javascriptsDir'];
echo '<script type="text/javascript" src="'.$javaFolder.'jquery.js"></script>'.PHP_EOL;
echo '<script type="text/javascript" src="'.$javaFolder.'highcharts.js"></script>'.PHP_EOL;
echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;

?>