<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsGlobalMap.php';
$pageVersion	= '3.20 2015-08-01';
#-------------------------------------------------------------------------------
# 3.20 2015-08-01 release 2.8 version
#-------------------------------------------------------------------------------
# for use with pre 2.8 release
if (!function_exists ('ws_message') ) {
    function ws_message ($message,$always=false) {
	global $wsDebug, $SITE;
	$echo	= $always;
	if ( $echo == false && isset ($wsDebug) && $wsDebug == true ) 			{$echo = true;}
	if ( $echo == false && isset ($SIE['wsDebug']) && $SIE['wsDebug'] == true ) 	{$echo = true;}
	if ( $echo == true ) {echo $message.PHP_EOL;}
    }
}
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
#--------------------------------- Settings ------------------------------------
#
$folder			='glo/';
$condIconsDir 		= $folder."MESO-images/";
$doLinkTarget 		= true; 			// =true to add target="_blank" to links in popups
$netLinksPath 		= $SITE['cacheDir'];  		// relative path for including the network links files (cached)
#
if ($SITE['uomTemp'] == '&deg;C') {$gmTempUOM = 'C';} else {$gmTempUOM = 'F';}
$gmWindUOM 		= trim($SITE['uomWind']) ; 
$gmBaroUOM 		= trim($SITE['uomBaro']);
$gmRainUOM 		= trim($SITE['uomRain']); 
#  Google map settings
$gmMapCenter 	        = trim($SITE['latitude']).','.trim($SITE['longitude']);
$gmMapZoom 		= 4; 				// initial map zoom level 1=world, 10=city;
$gmMapType 		= 'TERRAIN'; 			// ='ROADMAP', ='TERRAIN', ='SATELLITE', ='HYBRID' for Google Map Type (ALL CAPS)
$doRotatingLegends      = false;			// display all weahtervalues continuasly
$do_tabs 		= true;				// divide the output in two tabs 1 map 1 list
#-------------------------------------------end settings------------------------
#                          DO NOT CHANGE ANYTHING BELOW THIS LINE
# Here starts the first part
#
$cacheAllowed 		= 600;  		// 10 minute cache time
$cacheDir 		= $SITE['cacheDir']; 	// target directory for cache files 
#------------------------------ end settings      ------------------------------
#
$masterHost 		= 'http://www.northamericanweather.net/';
$filename		= 'global-conditions.json';
#
$GoogleLang = array ( // ISO 639-1 2-character language abbreviations from country domain to Google usage
  'af' => 'af', 'bg' => 'bg', 'ct' => 'ca', 'dk' => 'da', 'nl' => 'nl', 'en' => 'en',
  'fi' => 'fi', 'fr' => 'fr', 'de' => 'de', 'el' => 'el', 'ga' => 'ga', 'it' => 'it',
  'he' => 'iw', 'hu' => 'hu', 'no' => 'no', 'pl' => 'pl', 'pt' => 'pt', 'ro' => 'ro',
  'es' => 'es', 'se' => 'sv', 'si' => 'sl', );

$Lang = 'en';
if(isset($GoogleLang[$SITE['lang']])) {
	$Lang = $GoogleLang[$SITE['lang']];
	echo "<!-- module $pageFile (".__LINE__."): lang=".$SITE['lang']." used - Google Lang=$Lang -->".PHP_EOL;
}
$cacheName		= $cacheDir.$filename;
#
echo '<!-- start global map -->'.PHP_EOL;
$fileOK			= false;
if (file_exists($cacheName) ){ 
	$file_time      = filemtime($cacheName);
	$now            = time();
	$diff           = ($now     -   $file_time);
	ws_message (  "<!-- module $pageFile (".__LINE__."): $cacheName times:
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff seconds
	diff allowed = $cacheAllowed seconds -->");	
	if ($diff <= $cacheAllowed){
		ws_message (  "<!-- module $pageFile (".__LINE__."): File $cacheName current in cache -->");
		echo '<script  type="text/javascript">'.PHP_EOL;
		readfile ($cacheName);
		echo '</script>'.PHP_EOL;
		$fileOK = true;
	}  // file is valid echo it to the browser as a file in javascript.
} // eo file exist and valid
if ($fileOK == false) {
	ws_message (  "<!-- module $pageFile (".__LINE__."): For file $cacheName we need to load a fresh copy -->");
	$URL	= $masterHost.$filename;
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
	$rawdata	= curl_exec ($ch);
	curl_close ($ch);
	$rawdata = iconv ("WINDOWS-1252", "UTF-8//IGNORE",$rawdata);
        if (file_put_contents($cacheName, $rawdata)) {   
               ws_message (  "<!-- module $pageFile (".__LINE__."): File $cacheName saved to cache  -->");
        } else {
        	ws_message (  "<h3>module $pageFile (".__LINE__."): ERROR  File $cacheName could not be saved to cache. Program stops.</h3>");
        	return;
        }
	echo '<script  type="text/javascript">'.PHP_EOL.$rawdata.'</script>'.PHP_EOL;
}
echo '<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language='.$Lang.'" type="text/javascript"></script>'.PHP_EOL;
#echo '<script src="'.$folder.'global-map.js" type="text/javascript"></script>'.PHP_EOL;
echo '<div class="blockDiv">
<style scoped>
@charset "UTF-8";
/* CSS for styling Global Google Map and controls */
/* Version 2.00 - 27-Nov-2013 */
  #map-container {
	padding: 0px;
	border-width: 0px;
	border-style: solid;
	border-color: #ccc #ccc #999 #ccc;
	-webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
	-moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
	box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
	width: 100%;
  }

  #map {width: 100%;height: 680px;}

  #legend {
	color: blue;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
  }
  .GMcontent0 { display: inline;}
  .GMcontent1 { display: none;}
  .GMcontent2 { display: none;}
  .GMcontent3 { display: none;}
  .GMcontent4 { display: none;}
  .GMcontent5 { display: none;}
  .GMcontent6 { display: none;}
  .GMcontent7 { display: none;}
  .GMcontent8 { display: none;}
  #GMcontrols {
	  font-family: Verdana, Arial, Helvetica, sans-serif;
	  font-size: 8pt;
	  font-weight: normal;
	  position: relative;
	  display: inline;
	  padding: 0 0;
	  margin: 0 0;
	  border: none;
  }
  #GMcontrolsUOM select {
	  font-family: Arial, Helvetica, sans-serif;
	  font-size: 8pt;
	  font-weight: normal;
	  padding: 0 0;
	  margin: 0 0;
  }
</style>
<h3 class="blockHead" style="margin: 0;">'.langtransstr('Global Station Map of Affiliated Weather Networks').'</h3>'.PHP_EOL;
if (!isset ($do_tabs) ) {$do_tabs = false;}
#
if ($do_tabs == true) {echo '<br />
<div class="tabber" style="width: 99%; margin: 0 auto;">'.PHP_EOL; 
}
# --------------------------------------------------------------------------------------
# map part
# --------------------------------------------------------------------------------------
if(!file_exists ($folder.'global-map-inc.php')) { 
	echo '<p>Module  '.$pageFile.' ('.__LINE__.') The Regional map is currently not available.</p>';  
	echo '</div>
<!-- end global map -->'.PHP_EOL;
	return;
	
} 
if ($do_tabs == true) { 
	echo '<div class="tabbertab" style="width: 100%; padding: 0;">
<h3>'.langtransstr('Map').'</h3>'.PHP_EOL;
} 
include_once    $folder.'global-map-inc.php'; 
echo '<br />
<h4 style="text-align: center;">'.langtransstr('About the Global Map').'</h4>
<div style="width: 90%; margin: 0 auto; text-align: center;">'.$secondPart.'<br /></div>'.PHP_EOL;
if ($do_tabs == true) { 
	echo '</div>'.PHP_EOL;
}
# --------------------------------------------------------------------------------------
# list networks part
# --------------------------------------------------------------------------------------
$text_width 	= '700px';
$stringDiv 	= '';
$cacheAllowed 	= 600;  // 10 minute cache time
#
$fileSet = array(
  'network-list-inc.html' 	=> 'http://www.northamericanweather.net/network-list-inc-ml.html',
  'member-count.txt' 		=> 'http://www.northamericanweather.net/member-count-ml.txt',
);
foreach ($fileSet as $cacheName => $URL) {
	if (file_exists($netLinksPath.$cacheName) ){ 
        	$file_time      = filemtime($netLinksPath.$cacheName);
		$now            = time();
		$diff           = ($now     -   $file_time);
		ws_message (  "<!-- module global-map-inc.php (".__LINE__."): $cacheName times:
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff seconds
	diff allowed = $cacheAllowed seconds -->");	
		if ($diff <= $cacheAllowed){
			ws_message (  "<!-- module global-map-inc.php (".__LINE__."): $cacheName current in cache -->");
			continue;
		}
	}
	ws_message ( "<!-- module global-map-inc.php (".__LINE__."): for $cacheName we need to load a fresh copy -->");
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
	$rawdata	= curl_exec ($ch);
	curl_close ($ch);
	if ($doLinkTarget) {
		$rawdata = str_replace('<a href', '<a target="_blank" href',$rawdata);
	}
        if (file_put_contents($netLinksPath.$cacheName, $rawdata)) {   
                ws_message ( "<!-- module global-map-inc.php (".__LINE__."): File $cacheName saved to cache  -->");
        }
 } // eo for each
if ($do_tabs == true) { 
	echo '<div class="tabbertab" style="width: 98%; padding: 0; text-align: left;">'.PHP_EOL;
}
echo '<h3 style="text-align: center;">'.langtransstr('Affiliated Regional Weather Networks').'</h3>
<p style="width: '.$text_width.'; margin: 0 auto; text-align: center;"><br />'; 
include $netLinksPath.'member-count.txt'; 
echo '
</p>
<br />
<div style="width: '.$text_width.'; margin: 0 auto;">'.PHP_EOL;
include $netLinksPath.'network-list-inc.html' ;
echo '
</div>'.PHP_EOL;
if(isset($thirdPart)) {
   	echo '<div style="width: '.$text_width.'; margin: 0 auto; text-align: left;"><br /><br />'.$thirdPart.'<br /></div>'.PHP_EOL; 
}
if ($do_tabs == true) { 
echo '</div>'.PHP_EOL;  
}
# --------------------------------------------------------------------------------------
# end part
# --------------------------------------------------------------------------------------
if ($do_tabs == true) {
	echo '</div>
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
echo '<h3 class="blockHead">'.$creditPart.'</h3>';
echo '</div>
<!-- end global map -->'.PHP_EOL;
return;
#
function gmGenBoilerplate () {
	global $firstPart,$secondPart,$thirdPart, $creditPart, $doLinkTarget;
	
# for javascript animation control button lables
define('MESO_RUN', 		'Run'					);
define('MESO_PAUSE', 		'Pause'					);
define('MESO_STEP', 		'Step'					);

	
	if ( $doLinkTarget == true) {$target = 'target="_blank"';} else {$target = '';}
// copy of  Saratoga  Version 2.00 - 27-Nov-2012 - initial release
# The $firstPart is what gets printed when the page is first presented.
	$firstPart = '
<noscript><b>JavaScript must be enabled in order for you to use Google Maps.</b> 
<br />However, it seems JavaScript is either disabled or not supported by your browser. 
<br />To view Google Maps, enable JavaScript by changing your browser options, and then  try again.
</noscript>';
#
# The $secondPart is what gets printed under the legend on the map.
	$secondPart = '
<p>This <a href="http://maps.google.com/" '.$target.'>Google Map</a> shows the locations of current affiliated regional weather network member stations.</p>
<p><img src="glo/MESO-images/m1.png" width="25" height="25" alt="cluster marker image" /> 
Markers with numbers indicate clusters of stations - click to zoom the map to show station markers.
If you click on a marker for a station, a descriptive window will open and show the station features,
a link to the station\'s homepage, the regional network affiliations for the station, 
and current conditions at the station (where available).</p>
';
#
# The $thirdPart is what gets printed at the bottom of the page.
	$thirdPart = '
<p><small>
If you have a personal weather station publishing to a personal weather website, you can submit a request to have your
data included in this display by visiting the network for your geography from the list above.
</small></p>
';
#
	$creditPart = '
<small>Map data from 
<a href="http://www.northamericanweather.net/" '.$target.'>Affiliated Regional Networks</a> 
and scripts from
<a href="http://saratoga-weather.org/" '.$target.'>Saratoga-Weather.org</a>
</small>
';
} // eo function gmGenBoilerplate
# ----------------------  version history
# 3.20 2015-08-01 release 2.8 version 
