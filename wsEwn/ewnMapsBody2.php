<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
#-----------------------------------------------------------------------
# just to know which script version is executing
#-----------------------------------------------------------------------
$pageName	= 'ewnMapsBody2.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile 	= basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
echo '<div id="frcc" style="width: '.$mainwidth.'px; margin: 5px;">';
$skipHTML5 = true;
echo $nfrcbody;
echo $nfrccreds;
echo $ewnfooter;
echo '</div>';