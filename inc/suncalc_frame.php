<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'suncalc_frame.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.00 2014-10-11';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.00 2014-10-11 release version
# ----------------------------------------------------------------------
# settings:
$page_title     = 'SunCalc';
$width          = '100%';
$height         = '700px';
$lat            = $SITE['latitude'];
$lon            = $SITE['longitude'];
$date           = date ('Y.m.d/H:i', time() );
$memory         = false;
if (!$memory)   {$extra = '/#/'.$lat.','.$lon.',8/'.$date;}
else            {$extra = '';}
$url            = 'http://suncalc.net'.$extra;
$style          = 'style ="border: none; width:'.$width.'; height: '.$height.'; margin: 0px; padding: 0px; vertical-align: bottom; "';
#
echo '
<!-- '.$page_title.' -->
<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'</h3>
<iframe src="'.$url.'" '.$style.'>
Here the suncalc map will be drawn
</iframe>
</div>
<!-- end of '.$page_title.' -->
';
?>