<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName       ='worldForecast2.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2015-02-16';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2015-02-16 beta release 2.7 version
#-------------------------------------------------------------------------------
if (!isset ($SITE['worldAPI']) ) {$SITE['worldAPI'] = 1;}
if ($SITE['worldAPI'] == 2) {
        include 'worldForecast2api2.php';}
else  { include 'worldForecast2api1.php';}