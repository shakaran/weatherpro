<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'ec_settings.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-27-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
if (!isset ($topCount) ) {$topCount = 8;} elseif ($topCount > 8) {$topCount = 8;}
$wsIconWidth 	        = '60px';  // width of the icons in the top part.
# date and time formats
$timezone 	        = $SITE['tz'];
$lat 		        = $SITE['latitude'];
$long		        = $SITE['longitude'];
$dateTimeFormat         = $SITE['timeFormat'];
$timeFormat 	        = $SITE['timeOnlyFormat'];
$dateFormat 	        = $SITE['dateOnlyFormat'];
$dateLongFormat         = isset($SITE['dateLongFormat'])? $SITE['dateLongFormat'] : 'l d F Y';
# icons
$ecIconsLoc	        = 'canada/other-ec-icons/';
$ecIconsExt	        = '.png';

$SITE['ecIconsOwn']     = true;
$showPoP		= true;
# location
if (!isset ( $SITE['caProvince']) ) { $SITE['caProvince']	= 'BC';}		// BC			NU    
if (!isset ( $SITE['caCityCode']) ) { $SITE['caCityCode']	= 's0000141';}		// s0000141     s0000714 

$province		= $SITE['caProvince'];
$cityCode		= $SITE['caCityCode'];

$tempSimple		= false;
if (isset ($SITE['tempSimple']) ) {$tempSimple = $SITE['tempSimple'];}
#
$validWarningTypes			= array ();
$validWarningTypes['ended']		= -1;
$validWarningTypes['advisory']		= 2;
$validWarningTypes['warning']		= 1;
$validWarningTypes['watch']		= 0;
$validWarningTypes['statement']		= 0;
$validWarningPriorities			= array ();
$validWarningPriorities['low']		= 0;
$validWarningPriorities['medium']	= 1;
$validWarningPriorities['high']		= 2;
$validWarningPriorities['urgent']	= 3;
$validWarningcolors			= array ('Yellow', 'Orange', 'Red');
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
