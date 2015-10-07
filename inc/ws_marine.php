<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_marine.php';
$pageVersion	= '3.20 2015-07-19';
#-------------------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
$page_title     = langtransstr('Marine trafic');
$my_lang        = $SITE['lang'];
$lat            = $SITE['latitude'];    // '37.4460';   replace with coordinate of a harbour nearby 
$lon            = $SITE['longitude'];   // '24.9467';   same
#
echo '<!-- start ws_marine.php -->
<div class="blockDiv" style="vertical-align: bottom;">
<style scoped>
iframe {vertical-align: bottom;}
</style>
<h3 class="blockHead">'.$page_title.'</h3>
<script type="text/javascript">
        width     = "100%";	//the width of the embedded map in pixels or percentage
        height    = "900px";	//the height of the embedded map in pixels or percentage
        border    = "0";        //the width of the border around the map (zero means no border)
        shownames = "false";	//to display ship names on the map (true or false)
        latitude  = "'.$lat.'";	//the latitude of the center of the map, in decimal degrees
        longitude = "'.$lon.'";	//the longitude of the center of the map, in decimal degrees
        zoom      = "9";	//the zoom level of the map (values between 2 and 17)
        maptype   = "0";	//use 0 for Normal map, 1 for Satellite, 2 for Hybrid, 3 for Terrain
        trackvessel="0";	//MMSI of a vessel (note: it will displayed only if within range of the system)
        fleet     = "";		//the registered email address of a user-defined fleet
        remember  = false;	//remember or not the last position of the map (true or false)
        language  ="'.$my_lang.'";  //the preferred display language
        showmenu  = true;	//show or hide thee map options menu
</script>
<script type="text/javascript" src="http://www.marinetraffic.com/js/embed.js"></script>
</div>
<!-- end of ws_marine.php -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-19 release 2.8 version 
