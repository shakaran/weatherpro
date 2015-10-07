<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'rain3Sources.php';		
$pageVersion	= '3.04 2015-04-30';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 3.04 2015-04-04 release 2.7 version
#-------------------------------------------------------------------------------------------------
#
if (!isset ($ws['img_rain']) ) {
	include '_my_scripts/set_links.php';
}
#
switch ($SITE['region']) {
        case 'europe':
                include 'europe/rain_radar_europe.php'; 
                break;
        case 'america':
                include 'usa/rain_radar_america.php';
                break;        
        case 'canada':
                include 'canada/rain_radar_canada.php';
                break;        
        case 'other':
                include 'other/rain_radar_other.php'; 
                break;        
        default: 
                echo '<h3 style="">region not supported yet</h3>';
}
