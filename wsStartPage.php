<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsStartPage.php';
$pageVersion	= '3.20 2015-09-22';
#-----------------------------------------------------------------------
# 3.20 2015-09-22 release 2.8 version ONLY
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-----------------------------------------------------------------------
$dashboard 	= array();
#
#---------------------------- SETTINGS:   ------------------------------
# Rearrange and place here the lines you want to use



# leave all other lines WITH a comment mark below this one
#---------------------------- NOT USED:   ------------------------------
# old fashioned dashboard
$dashboard[]	= 'ajax';	// ajax  dashboard
# air quality
$dashboard[]    = 'us_aqhi_map';// US air quality maps
$dashboard[]    = 'us_aqhi';    // US air quality sliders
$dashboard[]	= 'ca_aqhi'; 	// Canada air quality 

# "realtime displays"
#$dashboard[]	= 'steel2rows';	        // steelseries 6 - 8 in 2 rows  // only one of the steelseries at the same time
#$dashboard[]	= 'steel1row';	        // steelseries 6 in 1 row
#$dashboard[]	= 'mwlive'; 		// Meteoware live
#$dashboard[]	= 'wdlive'; 		// weatherDisplay live
#$dashboard[]	= 'wdliveplug'; 	// weatherDisplay alternative meteoplug  
#$dashboard[]	= 'wulive';		// wulive 
#$dashboard[]	= 'meteoplug';		// meteoplug dashboard

# rain / thunder radars
#$dashboard[]	= 'rain';		// rain 2 or 3 radars
#$dashboard[]	= 'radar';		// rain thunder cloud

$dashboard[]	= 'uv';			// uv 6 day forecast + explanation dropdown

# forecasts
$dashboard[]	= 'fc'; 		// one of the forecast as based on settings
#$dashboard[]	= 'zam'; 		// zambretti forecast
#$dashboard[]	= 'davis'; 		// davis forecast txt if available
#$dashboard[]	= 'knmi';	        // Netherlands KNMI local forecast

$dashboard[]	= 'yrno';		// yrno two day meteogram

$dashboard[]	= 'yowindow'; 		// yowindow

# other
#$dashboard[]	= 'soil'; 		// soil  sensors
#$dashboard[]	= 'earth'; 		// fournilab's 24 earth day night picture
#$dashboard[]	= 'metars'; 		// nearby cities temp/cond

#$dashboard[]	= 'yahoos'; 		// nearby cities temp/cond

#-----------------------------------------------------------------------
# PLEASE   DO NOT CHANGE ANYTHING BELOW THIS LINE
#-----------------------------------------------------------------------
#
#-------------------------------------------------------------------------------------
$cntDash 	= count($dashboard);
$conflictSteel  = false;
#
for ($iDash = 0; $iDash < $cntDash; $iDash++) {
#
	if ($dashboard[$iDash] == 'ajax') {
		$script	= 'wsAjaxDashboard_v3.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'us_aqhi_map') {
		if ($SITE['region'] <> 'america') {continue;}
		$script	= 'usa/aq/us_aqhi_dash_map.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash]  == 'us_aqhi') {
		if ($SITE['region'] <> 'america') {continue;};
		$script	= 'usa/aq/us_aqhi_dash.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'ca_aqhi') {
		if ($SITE['region'] <> 'canada') {continue;}
		$script	= 'canada/ec_dash_aqhi.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'steel2rows') {
		if ($conflictSteel == true) {continue;}
		$script	= 'gauges/gaugeSmall.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		$conflictSteel  = true;
		continue;
	}
	if ($dashboard[$iDash] == 'steel1row'){
		if ($conflictSteel == true) {continue;}
		$script	= 'gauges/gaugeXsmall.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;		
		$conflictSteel  = true;
		continue;
	}
	if ($dashboard[$iDash] == 'mwlive') {
		$script = 'mwlive/wsMWlive_v3.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;				
		continue;
	}

	if ($dashboard[$iDash] == 'wdlive') {
		$wdlSmall       = true;
		if ($SITE['wdlPage'] 	== 'yes' || $SITE['wdlPage'] === true) {
			$script = 'wdl/incWdlive.php';
			ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
			include $script;					
		}
		continue;
	}
	if ($dashboard[$iDash] == 'wdliveplug') {
		if ($SITE['MeteoplugPage'] == 'yes' || $SITE['MeteoplugPage'] === true)  {
			$script = 'scriptsMP/meteoplugWDlive.php';
			ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
			include $script;					
		}
		continue;
	}
	if ($dashboard[$iDash] == 'wulive' && $SITE["wuMember"] == true) {
		$wuSmall         = true;
		$script = 'inc/WU_Live.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;				
		continue;
	}
	if ($dashboard[$iDash] == 'meteoplug') {
		if ($SITE['MeteoplugPage'] == 'yes' || $SITE['MeteoplugPage'] === true)  {
			$script = 'scriptsMP/meteoplugDashboard.php';
			ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
			include $script;					
		}
		continue;
	}
	if ($dashboard[$iDash] == 'radar') {
		$script = 'wsDashRadar.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;				
	}
	if ($dashboard[$iDash] == 'rain') {
		$script = 'inc/rain3SourcesSmall.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'uv') {
		if (!isset ($uvhtml) ) {
			$script = $SITE['uvScript'];
			ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
			include $script;					
		}
		echo $uvhtml;
		continue;
	}
	if ($dashboard[$iDash] == 'fc') { 
		$script = 'wsDashForecast.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'zam') { 
		$script = 'forecasts/wsZambretti.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'davis' ) { 
		if (!$SITE['DavisVP']) {continue;}
		$script = 'wsDashDavis.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'knmi') {
		if  (($SITE['region'] == 'europe') && ($SITE['netherlands'] ||  $SITE['belgium']) ){ 
			$script = 'europe/ws_knmi_fct.php';
			ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
			include $script;					
 		}
		continue;
	}
	if ($dashboard[$iDash] == 'yrno') {
		$script = 'wsyrnofct/wsDashAvansert.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	#
	if ($dashboard[$iDash] == 'yowindow') {
		if (isset ($wsDashYowindowLoaded)  && $wsDashYowindowLoaded == true) {continue;}
		$script = 'wsDashYowindow.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		echo '<div class="blockDiv" style="background-color: grey;">'.PHP_EOL;
		include $script;
		echo '</div>';
		continue;
	}
	if ($dashboard[$iDash] == 'soil') {
		if (!$SITE['soilUsed']) {continue;}
		$script = 'soil/wsDashSoil.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	#
	if ($dashboard[$iDash] == 'earth') {
		$script = 'wsDashEarth.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}

	if ($dashboard[$iDash] == 'metars') {
		$script = 'metar/dash_metar.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
	if ($dashboard[$iDash] == 'yahoos') {
		$script = 'forecasts/dash_yahoo.php';
		ws_message (  '<!-- module wsStartPage.php ('.__LINE__.'): loading '.$script.' -->');
		include $script;
		continue;
	}
}
# ----------------------  version history
# 3.20 2015-09-22 release 2.8 version 