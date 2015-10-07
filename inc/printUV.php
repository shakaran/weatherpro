<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'printUV.php';	
$pageVersion	= '3.20 2015-07-19';
#-------------------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$uv_page	= true;
include_once 	$SITE['uvScript'];
echo $uvhtml;
# ----------------------------------------------------------------------
#
# If you want to add extra information you can add it here also.


#
# ----------------------  version history
# 3.20 2015-07-19 release 2.8 version 

