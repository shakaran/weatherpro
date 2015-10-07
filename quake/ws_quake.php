<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'incQuake.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '2.30 2013-09-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
#---------------------------------------------------------------------------
# 3.00 2013-12-10 update  - this is mostly Ken True's script - see credits
#---------------------------------------------------------------------------
#
$doIncludeQuake         = true;
$setDistanceDisplay     = trim($SITE['uomDistance']);   // ' km' or ' mi' 
$setDistanceRadius      = 200000;  	// same units as first unit in $setDistanceDisplay

# NOTE: quakes of magnitude 1.0+ are available for USA locations only.
#    non-USA location earthquakes of magnitude 4.0+ are the only ones available from the USGS
$setMinMagnitude	= '2.0';  	// minimum Richter Magnitude to display
$setHighMagnitude       = '4.0';  	// highlight this Magnitude and greater
$setMapZoomDefault      = 4;    	// default zoom for Google Map 1=world to 13=street
$setDoLinkTarget        = 1;   	        // =1 to have links open in new page, =0 for XHTML 1.0-Strict


# script will use your $SITE[] values for latitude, longitude, timezone and time display format

$SITE['cityname']       = $SITE['organ'];       // missing in Leuven settings
$SITE['cacheFileDir']   = $SITE['cacheDir'];    // different names 

$imagesDir              = './quake/ajax-images/';

?>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language=<?php echo $lang; ?>" type="text/javascript"></script>
<script src="quake/quake-json.js" type="text/javascript"></script>

<div class="blockDiv">
<style scoped>
.quake { width: 100%;}
#map-container { width: 100%; display: none;}
#map {width: 100%; height: 600px; }
#actions {list-style: none; padding: 0;}
#inline-actions { padding-top: 10px;}
.item { margin-left: 20px;}
</style>

	<h3 class="blockHead"><?php langtrans('Earthquakes in the past 7 days'); ?></h3>

	<div style="width: 100%; margin: 0 auto;">
		<?php include_once("quake-json.php"); ?>
	</div>
</div>
<br />
<div class="blockDiv" style="background-color:white;">
	<h3 class="blockHead"><?php langtrans('Map and data courtesy of');?> 
 		 <a href="http://earthquake.usgs.gov/earthquakes/map/"><?php langtrans('United States Geological Survey');?></a>
  		<br /><br />
  		<small><?php langtrans('Original script by'); ?>&nbsp;
  		<a href="http://saratoga-weather.org/" target="_blank">Saratoga-weather.org</a>
  		<?php langtrans('Adapted for the template by'); ?>&nbsp;
  		<a href="http://leuven-template.eu/" target="_blank">Weerstation Leuven</a>
  		</small>
  	</h3>
</div>