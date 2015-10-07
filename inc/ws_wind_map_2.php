<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_wind_map_2.php';
$pageVersion	= '3.20 2015-07-19';
#-------------------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#------------------------------ settings:    -----------------------------------
$page_title     = langtransstr('Wind and forecast map');
$credits     	= langtransstr('Courtesy to'); 
$wind_uom       = str_replace ('/', '', trim($SITE['uomWind']) );        // =' km/h', =' kts', =' m/s', =' mph'
$region         = $SITE['region'];
$lat            = round($SITE['latitude'],3);
$lon            = round($SITE['longitude'],3);
$url            = 'https://www.windyty.com/?surface,wind,now,'.$lat.','.$lon.',4';
echo '
<!-- wind map -->
<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'</h3>
<iframe src="'.$url.'" 
  style ="border: none; 
  width:100%; height: 600px; 
  margin: 0px; 
  padding: 0px; 
  vertical-align: bottom; ">
</iframe>
<h3 class="blockHead">'.$credits.'<a href="https://www.windyty.com/" target ="_blank"><img 
src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information" /></a>
 www.windyty.com
</h3>
</div>'.PHP_EOL;
