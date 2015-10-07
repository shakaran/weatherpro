<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_google-weather.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.00 2014-10-11';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.00 2014-10-11 release version
# ----------------------------------------------------------------------
# settings:
$lat            = $SITE['latitude'];
$lon            = $SITE['longitude'];
$page_title     = 'Google world weather';
$map_height     = '500px';
$map_zoomlevel  = '8';
$map_style      = 'style="width: 100%; height: '.$map_height.';"';
if      ($SITE['uomTemp'] == '&deg;C')   {$google_temp = 'CELSIUS'; } else  {$google_temp = 'FAHRENHEIT';}
#
echo '<!-- '.$pageName.' -->'.PHP_EOL;
?>
<div class="blockDiv">
<h3 class="blockHead" style="height: 20px; "><?php echo $page_title; ?>
<button onclick="toggleClouds()" style="margin: 1px; float: right;">Clouds on-off</button>
</h3>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true&amp;libraries=weather"></script> 
<script> 
var map, weatherLayer, cloudLayer; 
function initialize() { 
 map = new google.maps.Map(document.getElementById('map'), { 
   center: new google.maps.LatLng(<?php echo $lat.','.$lon; ?>), 
   zoom: <?php echo $map_zoomlevel; ?>, 
   mapTypeId: google.maps.MapTypeId.TERRAIN 
 }); 
 tempLayer = new google.maps.weather.WeatherLayer({temperatureUnits:google.maps.weather.TemperatureUnit.<?php echo $google_temp; ?>});
 tempLayer.setMap(map);

 cloudLayer = new google.maps.weather.CloudLayer(); 
 cloudLayer.setMap(map); 
} 
google.maps.event.addDomListener(window, 'load', initialize); 
function toggleClouds() { 
 cloudLayer.setMap(cloudLayer.getMap() ? null : map); 
}        
</script> 
<div id="map" <?php echo $map_style; ?> ></div>
</div>
<!-- <?php echo $pageName; ?> ends here -->