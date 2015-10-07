<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'cloudRadar.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-12 release version
# -------------------------------------------------------------------------------------------------
# Settings:
# imgage style
$imgStyle	= 'style="width: 100%; vertical-align: top;"';
# europe or north america or ??
if ($SITE['region'] == 'europe') {
	$strHead	= langtransstr('Cloud Radar by').'sat24.com';
	$urlLink	= 'http://www.sat24.com/'.$lang.'/';
	$imgClouds	= 'http://www.sat24.com/image2.ashx?country=eu&amp;type=loop&amp;sat=vis';      // http://www.sat24.com/image2.ashx?region=eu&ir=True
	$imgCloudsI	= 'http://www.sat24.com/image2.ashx?region=eu&amp;ir=True';                     // http://www.sat24.com/image2.ashx?region=eu&ir=True
	$imgAlt		= langtransstr('Cloudradar').' Benelux';
	$strExtra	= langtransstr('Click on image for the sat24.com site with a lot of extra information');
} else {
	$strHead	= langtransstr('Cloud Radar by').' NOAA';
	$urlLink	= 'http://www.goes.noaa.gov/nhemi.html';
	$imgClouds	= 'http://www.ssd.noaa.gov/goes/comp/nhem/vis-animated.gif?time='.time();	  // http://www.ssd.noaa.gov/goes/comp/nhem/
	$imgCloudsI	= 'http://www.ssd.noaa.gov/goes/comp/nhem/rb-animated.gif?time='.time();	  // http://www.ssd.noaa.gov/goes/comp/nhem/
	$imgAlt		= langtransstr('Cloudradar');	
	$strExtra	= langtransstr('Click on image for the NOAA Geostationary Satellite Server site with a lot of extra information');
}
$buttonVis	= langtransstr('Visible');
$buttonIR	= langtransstr('Infrared');
$showVis	= $showIR	= false;
if (isset($_REQUEST['IR']) ){
	$showIR	= true;
} else {
	$showVis= true;
}

# now we display the selected radar image
#
echo '<div class="blockDiv" style="background-color: grey; border: 1px solid grey;">
<div class="blockHead">
 <h3 class="blockHead">'.$strHead.' - '.$strExtra.'</h3>
 <div style="width: 320px; margin: 0 auto;">
  <form method="post" name="menu_select" action="index.php?p=42">
   <button id="vis"  name = "vis"  style="width: 100px;">'.$buttonVis.'</button>
   <button id="IR"   name = "IR"   style="width: 100px;">'.$buttonIR.'</button>
  </form>
 </div>
</div>'.PHP_EOL;
if ($showVis) {
	echo '<a href="'.$urlLink.'" target="_blank" ><img src="'.$imgClouds.'" alt="'.$imgAlt.'"  '.$imgStyle.'/></a>';
} else {
	echo '<a href="'.$urlLink.'" target="_blank" ><img src="'.$imgCloudsI.'" alt="'.$imgAlt.'"  '.$imgStyle.'/></a>';
}	
echo '
</div>'.PHP_EOL;