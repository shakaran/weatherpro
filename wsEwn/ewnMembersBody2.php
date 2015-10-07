<?php
ini_set('display_errors', 'Off'); 
error_reporting(0);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
#-----------------------------------------------------------------------
# just to know which script version is executing
#-----------------------------------------------------------------------
$pageName	= 'ewnMembersBody2.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile 	= basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
#
$skipHTML5 = true;
echo '<div style="padding: 5px;">'.PHP_EOL;
echo $ewndata;
echo '</div>';