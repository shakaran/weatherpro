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
