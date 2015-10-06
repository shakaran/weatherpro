<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;            # display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'wsPrecipRadar.php';		
$pageVersion	= '3.20 2015-07-13';
#---------------------------------------------------------------------------
# 3.20 2015-07-13 release 2.8 version
#-------------------------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------------------------
if (!isset ($ws['img_rain']) ) {
	ws_message (  '<!-- module wsPrecipRadar.php ('.__LINE__.'): loading _my_scripts/set_links.php -->');
	include '_my_scripts/set_links.php';
}
#
switch ($SITE['region']) {
        case 'europe':
        	ws_message (  '<!-- module wsPrecipRadar.php ('.__LINE__.'): loading europe/rain_radar_europe.php -->');
                include 'europe/rain_radar_europe.php'; 
                break;
        case 'america':
        	ws_message (  '<!-- module wsPrecipRadar.php ('.__LINE__.'): loading usa/rain_radar_america.php -->');
                include 'usa/rain_radar_america.php';
                break;        
        case 'canada':
        	ws_message (  '<!-- module wsPrecipRadar.php ('.__LINE__.'): loading canada/rain_radar_canada.php -->');
                include 'canada/rain_radar_canada.php';
                break;        
        case 'other':
        	ws_message (  '<!-- module wsPrecipRadar.php ('.__LINE__.'): loading other/rain_radar_other.php -->');
                include 'other/rain_radar_other.php'; 
                break;        
        default: 
                echo '<h3 style="">region not supported yet</h3>';
}
# ----------------------  version history
# 3.20 2015-07-13 release 2.8 version 
