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
$pageName	= 'wsForecast.php';
$pageVersion	= '3.20 2015-07-19';
#-------------------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-----------------------------------------------------------------------
$arrFct	= array();
$arrFct['metno']= 'ws_metno_page';
$arrFct['yahoo']= 'yahooForecast2';
$arrFct['wu']	= 'wu_fct_page';
$arrFct['wxsim']= 'wsWxsimPrintFull';
$arrFct['yrno']	= 'ws_yrno_page';
$arrFct['noaa']	= 'noaa_full_page';
$arrFct['ec']	= 'ec_print_fct';
$arrFct['hwa']	= 'hwaFullPage';

# yowindow

$fct_org	= $SITE['fctOrg'];
$fct_page	= $arrFct[$fct_org];
$fct_link	= $menuArray[$fct_page]['link'];
ws_message (  '<!-- module wsForecast.php ('.__LINE__.'): loading '.$fct_link.' -->');
include $fct_link;
# ----------------------  version history
# 3.20 2015-07-19 release 2.8 version 
