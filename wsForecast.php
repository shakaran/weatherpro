<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
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
