<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'rain3SourcesSmall.php';		
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# Settings:

switch ($SITE['region']) {
        case 'europe':
                include 'europe/rain_radar_europe_small.php';
                break;
        case 'america':
                include 'usa/rain_radar_america_small.php';
                break;  
        case 'canada':
                include 'canada/rain_radar_canada_small.php';
                break; 
        case 'other':
                return;  // no small radars for yrno
                break;        
        default: 
                echo '<h3 style="">region not supported yet</h3>';   
}
 # ----------------------  version history
# 3.20 2015-07-28 release 2.8 version 
 