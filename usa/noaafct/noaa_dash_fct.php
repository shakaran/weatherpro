<?php
# ---------------HERE YOU NEED TO MAKE SOME CHANGES --------------------
#
$fullpage_link          = true;                 # set to false if no link to full page is wanted            ######
$noaa_page              = 'noaa_full_page';     // script name of the full page  forecast
$noaa_page_alt          = 'noaa_plain_page';    // script name of the full page  forecast
#
#  first we set which parts  of the page should be printed
#
$updateTimes	        = false;		// two lines with recent file / new update information
#
$showHazards            = false;         // show hazard warnings when available
#
$iconGraph	        = true;		// icon type header  with 2 icons for each day (12 hours data)
$iconsSeparate	        = false;
$topCount	        = 10;		// max nr of day-part forecast-icons 
#
$chartsGraph	        = true;		// high charts graph one colom for every 3 / 6 hours
$graphsSeparate	        = false;		// graph separate (true) or in a tab (false)
$graphHeight	        = '340';	// height of graph.					
#
$fcstTable	        = true;		// table with one line for every 3 / 6 hours
$plainTable             = true;         // table with plain forecast
$tabHeight 	        = '400';	// to restrict height of tabs to suppress very large/long pages
# --------------- END OF SETTINGS --------------------------------------
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'noaa_dash_fct.php';
$pageVersion	= '3.20 2015-07-29';
# ----------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
$insideTemplate         = true;
$myTimezone 		= $SITE['tz'];
$myLatitude		= $SITE['latitude'];
$myLongitude		= $SITE['longitude'];
$myLang 		= $SITE['lang'];
$myArea			= $SITE['yourArea'];
$myStation		= $SITE['organ'];
$myCharset		= $SITE['charset'];
$noaaIconsOwn		= $SITE['noaaIconsOwn']; 
$tempSimple     	= $SITE['tempSimple'];
$lower			= $SITE['textLowerCase'];	
#
$dateTimeFormat     	= $SITE['timeFormat'];
$timeFormat 		= $SITE['timeOnlyFormat'];
$hourOnlyFormat 	= $SITE['hourOnlyFormat'];
$dateOnlyFormat 	= $SITE['dateOnlyFormat'];
$dateLongFormat 	= $SITE['dateLongFormat'];
#
$wsmyfolder		= './usa/noaafct/';		// only change this if you stored the wu forecasts scripts in another folder
#
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
echo '<!-- output noaa_dash_fct.php -->
<div class="blockDiv">'.PHP_EOL;
#
echo '<div class="ajaxHead" >'.langtransstr('Weather Forecast').' ('.langtransstr('summary').')&nbsp;&nbsp;';
if ($fullpage_link <> '') {
        if (isset ($SITE['pages'][$noaa_page]) ) {$link = $SITE['pages'][$noaa_page];} else {$link = $SITE['pages'][$noaa_page_alt];}
        $noaaLink      =  $link.'&amp;lang='.$lang.$extraP.$skiptopText;       // pagenumber for full forecast page
        echo '<a href="'.$noaaLink.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
}
echo '
</div>'.PHP_EOL;

# ------------------------------------------------------------------------------
# here we build the noaa page
#
#  ------------------- load all settings ---------------------------------------
$script	= 'noaaSettings.php';
ws_message (  '<!-- module noaa_dash_fct.php ('.__LINE__.'): loading '.$script.' -->');
include $script ;
#
#  ------------------- generate and print requested info   ---------------------
if ($showHazards || $iconGraph || $plainTable) {
        $script	= 'noaaPlainGenerateHtml.php';
        ws_message (  '<!-- module noaa_dash_fct.php ('.__LINE__.'): loading '.$script.' -->');
        include $script ;
}
#
$script	= 'noaaDigitalGenerateHtml.php';
ws_message (  '<!-- module noaa_dash_fct.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
#

if ($updateTimes) {echo '<div class="blockHead" style="">'.$wsUpdateTimes.'</div>'.PHP_EOL;}

if ($showHazards && $hazardsString <> '') { echo $hazardsString;}

if ($iconGraph && $iconsSeparate) {echo '<div class="noaadiv" style="margin: 10px 0px;">'.$noaaIconsHtml.'</div>'.PHP_EOL;}

if ($chartsGraph && $graphsSeparate) {
	echo 
'<div id="containerTemp" class="noaadiv" style="height: '.$graphHeight.'px; ">
	here the graph will be drawn
</div>'.
$graphPart1.PHP_EOL;
}
# now the tabs for the tables with all data
#

if ($tabHeight <> '') { $styleHeight ='height:'.(int) $tabHeight.'px;';} else {$styleHeight = '';}
#
echo '<div class="tabber"  style="">'.PHP_EOL;
if ($iconGraph && !$iconsSeparate) {
        echo '<div class="tabbertab noaadiv" style=""><h2> '.wsnoaafcttransstr('Icons').' </h2><br />'.$noaaIconsHtml.'<br /></div>'.PHP_EOL;     
}
if ($chartsGraph && !$graphsSeparate) {		// are the graphs separate on the page or are they in a tab  (=false)
	echo '<div class="tabbertab" style=""><h2> '.wsnoaafcttransstr('Graph').' </h2>
<div id="containerTemp" class="noaadiv" style="height: '.$graphHeight.'px; margin: 4px auto;">here the graph will be drawn</div>'.
		$graphPart1.'
</div>'.PHP_EOL;	
}
if ($plainTable ) {
        echo '	<div class="tabbertab noaadiv" style="'.$styleHeight.'"><h2> '.wsnoaafcttransstr('Forecast').' </h2>'.
	$noaaPlainText.
	$creditLink.'
	</div>'.PHP_EOL;
}
if ($fcstTable) {
        echo '	<div class="tabbertab noaadiv" style="'.$styleHeight.'"><h2> '.wsnoaafcttransstr('Details').' </h2>'.
	        $wsFcstTable.'
	</div>'.PHP_EOL;
}
echo '</div>'.PHP_EOL;  // eo tabber
#
if ($fcstTable) {
	echo '<script type="text/javascript" src="'.$myJavascriptsDir.'tabber.js"></script>'.PHP_EOL;
}
if ($chartsGraph) {
	echo '<script type="text/javascript" src="'.$myJavascriptsDir.'jquery.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$myJavascriptsDir.'highcharts.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
# ------------------------------------------------------------------------------
echo '</div>
<!-- end output noaa_dash_fct.php -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version
