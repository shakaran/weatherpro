<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'cloudRadarv3.php';
$pageVersion	= '3.20 2015-07-13';
#---------------------------------------------------------------------------------------
# 3.20 2015-07-13 release 2.8 version
# --------------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# -------Settings: ---------------------------------------------------------------------
#
# --------------------------------------------------------------------------------------
if ($SITE['region'] == 'europe') {
	$strHead	= langtransstr('Cloud Radar by').'sat24.com';
	$urlLink	= 'http://www.sat24.com/'.$lang.'/';
	$imgClouds	= 'http://www.sat24.com/image2.ashx?country=eu&amp;type=loop&amp;sat=vis';      // http://www.sat24.com/image2.ashx?region=eu&ir=True
	$imgCloudsI	= 'http://www.sat24.com/image2.ashx?region=eu&amp;ir=True';                     // http://www.sat24.com/image2.ashx?region=eu&ir=True
	$imgAlt		= langtransstr('Cloudradar').' Benelux';
	$strExtra	= langtransstr('Go to the sat24.com site with a lot of extra information');
	$img_width	= '100%';
} 
elseif ($SITE['region'] == 'america' || $SITE['region'] == 'canada') {
	$strHead	= langtransstr('Cloud Radar by').' NOAA';
	$urlLink	= 'http://www.goes.noaa.gov/nhemi.html';
	$imgClouds	= 'http://www.ssd.noaa.gov/goes/comp/nhem/vis-animated.gif?time='.time();	  // http://www.ssd.noaa.gov/goes/comp/nhem/
	$imgCloudsI	= 'http://www.ssd.noaa.gov/goes/comp/nhem/rb-animated.gif?time='.time();	  // http://www.ssd.noaa.gov/goes/comp/nhem/
	$imgAlt		= langtransstr('Cloudradar');	
	$strExtra	= langtransstr('Go to the the NOAA Geostationary Satellite Server site with a lot of extra information');
	$img_width	= '100%';
#	$img_width	= '720px';	// natural size  = 720px
}
else {  ws_message ('<!-- module '.$pageFile. '('.__LINE__.'): no clud radar for this region, switch to precip radar -->',true);
	$script = 'wsPrecipRadar.php';
	ws_message ('<!-- module '.$pageFile. '('.__LINE__.'): loading '.$script.' -->');
	include $script; 
	return;
}  // no cloud radar for other area
#
$imgStyle	= 'style="width:'.$img_width.'; vertical-align: bottom;"';
$buttonVis	= langtransstr('Visible');
$buttonIR	= langtransstr('Infrared');
# --------------------------------------------------------------------------------------
echo '<div class="blockDiv" style="text-align: center;">
<h3 class="blockHead">'.$strHead.'</h3>
<div class="tabber" style="width: 100%; ">
<div class="tabbertab " style="padding: 0; background-color: #f9f9f9;">
<h3>'.$buttonVis.'</h3>
<p class="blockHead">'.$strExtra.'&nbsp;
  <a href="'.$urlLink.'"  target="_blank">
    <img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information" />
  </a>
</p>
<img src="'.$imgClouds.'" alt="'.$imgAlt.'"  '.$imgStyle.'/>
</div>

<div class="tabbertab " style="padding: 0; background-color: #f9f9f9;">
<h3>'.$buttonIR.'</h3>
<p class="blockHead">'.$strExtra.'&nbsp;
  <a href="'.$urlLink.'"  target="_blank">
    <img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information" />
  </a>
</p>
<img src="'.$imgCloudsI.'" alt="'.$imgAlt.'"  '.$imgStyle.'/></a>
</div>
</div>
</div>
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
