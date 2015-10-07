<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_wu_map.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-10-09';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-10-09 release version
# ----------------------------------------------------------------------
# settings:
$page_title     = 'WeatherUnderground '.'stations';
$lat            = $SITE['latitude'];
$lon            = $SITE['longitude'];
$width          = '100%';
$height         = '700px';
$url            = 'http://www.wunderground.com/cgi-bin/findweather/getForecast?brand=wxmap&amp;query='.
$lat.','.$lon.
'&amp;lat='.$lat.'&amp;lon='.$lon.
'&amp;zoom=11&amp;type=roadmap&amp;units=metric&amp;rad=0&amp;sat=0&amp;svr=0&amp;cams=0&amp;tor=0&amp;wxsn=1&amp;wxsn.mode=temp&amp;wxsn.opa=50&amp;wxsn.bcdgtemp=0&amp;wxsn.rf=0';
echo '<!-- WeatherUnderground stations Map -->
<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'</h3>
<iframe src="'.$url.'" style ="border: none; width: '.$width.'; height: '.$height.'; margin: 0px; padding: 0px; vertical-align: bottom; "></iframe>
</div>
<!-- end of WeatherUnderground stations Map -->
';