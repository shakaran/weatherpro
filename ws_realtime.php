<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$SITE			= array();
#
$pageName		= 'ws_realtime.php';
$pageVersion	        = '3.11 2015-02-13';
$SITE['wsModules'][$pageName]   = 'version: ' . $pageVersion; 
$pageFile = basename(__FILE__);	
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName.' - '.$pageVersion; }
$realtimeScript         = $pageName.' - '.$SITE['wsModules'][$pageFile];
# ------------------------------------------------------------------------------
# to load the realtime data into string and send it back to the requestingsteelseries javascript handler 
# ------------------------------------------------------------------------------
#
include         'wsLoadSettings.php';
$realtime       = true;
$rtOut          = 'steel';
include $SITE['wsAjaxDataLoad'];
return;