<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'rain_radar_america.php';
$pageVersion	= '3.20 2015-07-19';
#----------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version 
#----------------------------------------------------------------------		
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------Settings:---------------------------
#
$pageTitle      = langtransstr('Precipitation radars'); 
#
#-----------------------------------------------------------------------
#                               wu radar --- regional
#-----------------------------------------------------------------------
$wuRegionalAvailable    = true;
$descWuRegionalArea     = langtransstr('WU Radar').' - regional';
$linkWuRegionalArea     = 'http://www.wunderground.com/weather-radar/united-states/';// link to wu site with extra information
#$linkWuRegionalArea    = '';                                                       // if no link remove comment mark
$linkWuRegionalAreaText = langtransstr('Go to the USA Radar site');
$wuRegionalWidth	= '100%';
#
#-----------------------------------------------------------------------
#                               wu radar --- station
#-----------------------------------------------------------------------
$wuStationAvailable	= true;                 // set to false if you do not want this radar
$wuRadarStation		= $SITE['radarStation'];
$descWuStation          = langtransstr('station').' '.$wuRadarStation;
$linkWuStation          = 'http://www.wunderground.com/weather-radar/united-states/';   // link to wu site with extra information
$linkWuStationText      = langtransstr('Go to the USA Radar site');
$wuStationWidth		= '100%';
#-----------------------------------------------------------------------
#                             wu radar --- mosaic 
#-----------------------------------------------------------------------
# fairly difficult to find the correct "large area " radar as I can not locate the large page anymore 
# (old page was : http://www.wunderground.com/radar/mosaic.asp)
# radar areas are coded as  2xradar??_anim.gif   where ??  equals to  a1 - a5  b1 - b5    c1 - c5  d1 - d5  e1 -e5
/* Example       
http://icons.wunderground.com/data/640x480/2xradara1_anim.gif?dontcache=f43fb33fbc55303f8832c9893e5614af&time=12345678
*/
$wuRadarAvailable	= false;                // replaced by regional radar in most cases 
#
$wuRadarArea		= '2xradara1_anim.gif';
#
$descWuRadarArea        = langtransstr('WU Radar').' - mosaic';
$linkWuRadarArea        = 'http://www.wunderground.com/weather-radar/united-states/';   // link to wu site with extra information
$linkWuRadarAreaText    = langtransstr('Go to the USA Radar site');
#-----------------------------------------------------------------------
#                               yrno world wide radar 
#-----------------------------------------------------------------------
$YrnoAvailable          = true;        // world wide radar available
# yrno world wide radar forecast
$linkYrno               ='http://www.yr.no/kart/#lat='.$SITE['latitude'].'&amp;lon='.$SITE['longitude'].'&amp;zoom=4&amp;laga=nedb%C3%B8r&amp;baseid=PunktUtlandet%3A2187922&amp;proj=900913';
$descYrno               = langtransstr('Yrno radar');           // only displayed (on the tab) if there is more then 1 radar
#
#-----------------------------------------------------------------------      
#                               other radar
#-----------------------------------------------------------------------
# just some code to jump-start with other radar stations 
#
$OtherAvailable         = false;
$imgOther               = 'http:// we have no other radars yet';
#$linkOther              = '';                                         // if no link remove comment mark

$descrOther             = langtransstr('Another radar description'); 
$linkOther              = 'http:// link to main site for radar';       // if we have a link to the other radar
$linkOtherText          = langtransstr('Click on image for the radar site with a lot of extra information');                                                          
 
# ---------------------- genreal settings all radars  ------------------
#
$imgStyle	= 'style="width: 100%; vertical-align: bottom;"';
#
$tabsWanted     = true; // ONLY if we have more then 1 radar, do we want to put them in tabs - default is yes, who wants otherwise?
#
#------------------------  end of Settings   ---------------------------
#-----------------------------------------------------------------------
# I  mean it, no settings below this point
# Only change below if you realy know what you are doing.
# If in doubt, use a forum or the support site at http://leuven-template.eu/index.php
#
$count  = 0;                    // number of radars for this region
#
if ($wuRadarAvailable) {
        $count++;
        $imgWUradar     = 'http://icons.wunderground.com/data/640x480/'.$wuRadarArea.'?dontcache=f43fb33fbc55303f8832c9893e5614af&amp;time='.time();
}
if ($wuRegionalAvailable) {
        $count++;
        $imgWUregional  = $ws['img_rain'] ;
}
if ($wuStationAvailable) {
        $count++;
        $imgWUstation    = 'http://radblast.wunderground.com/cgi-bin/radar/WUNIDS_map?station='.$wuRadarStation.'&amp;brand=wui&amp;num=6&amp;delay=15&amp;type=N0R&amp;frame=0&amp;scale=1.000&amp;noclutter=0&amp;showstorms=0&amp;mapx=400&amp;mapy=240&amp;centerx=400&amp;centery=240&amp;transx=0&amp;transy=0&amp;showlabels=1&amp;severe=0&amp;rainsnow=0&amp;lightning=0&amp;smooth=0&amp;rand=23537938&amp;lat=0&amp;lon=0&amp;label=you&amp;time='.time();
}
if ($OtherAvailable)    { $count++; }
if ($YrnoAvailable)     { $count++; }
#
echo '<!-- output rain_radar_america.php -->
<div class="blockDiv" style="text-align: center;"><h3 class="blockHead">'.$pageTitle.'</h3>'.PHP_EOL;
#
if ($count < 2 || $tabsWanted == false) { 
        $start_tab      = $end_tab      = '';
        $count          = 0;
}
else {  $start_tab      = '<div class="tabbertab" style="padding: 0;">'.PHP_EOL;        // if more then 1 radar put them in tabs
        $end_tab        = '</div>'.PHP_EOL;
        echo '<div class="tabber" style="width: 100%; ">'.PHP_EOL;
} // eo check tabs
#
# ----------------------- WU area Radar   --------------------------------------
#
if ($wuRadarAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descWuRadarArea.'</h3>'.PHP_EOL; }
#
        if ($linkWuRadarArea <> '') {
# first a message to the visitor if there is a link
                echo '<h4  class="blockHead" style="">'.$linkWuRadarAreaText.'
<a href="'.$linkWuRadarArea.'"  target="_blank">
<img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information">
</a></h4>'.PHP_EOL;
        }
# now the img of the radar        
	echo '<img src="'.$imgWUradar.'" '.$imgStyle.' alt="'.$descWuRadarArea.'"  />';
        echo $end_tab;
}
#
# ----------------------- WU regional Radar   --------------------------------------
#
if ($wuRegionalAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descWuRegionalArea.'</h3>'.PHP_EOL; }
#
        if ($linkWuRegionalArea <> '') {
# first a message to the visitor if there is a link
        	echo '<p  class="blockHead" style="">'.$linkWuRegionalAreaText.'
<a href="'.$linkWuRegionalArea.'"  target="_blank">
<img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information">
</a></p>'.PHP_EOL;
        }
# now the img of the radar 
	echo '<div style="width:'.$wuRegionalWidth.'; margin: 0 auto;">'.PHP_EOL;            
	echo '<img src="'.$imgWUregional.'" '.$imgStyle.' alt="'.$descWuRegionalArea.'"  />';
        echo '</div>';
        echo $end_tab;

}

# ----------------------- WU station Radar  ------------------------------------
#
if ($wuStationAvailable)  {
	$imgStyle	= 'style="width:'.$wuStationWidth.'; vertical-align: bottom;  margin: 0 auto;"';
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descWuStation.'</h3>'.PHP_EOL; }
#
        if ($linkWuStation <> '') {
# first a message to the visitor if there is a link
                echo '<h4  class="blockHead" style="">'.$linkWuStationText.'
<a href="'.$linkWuStation.'"  target="_blank">
<img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information">
</a></h4>'.PHP_EOL;
                
        }
# now the img of the radar  
	echo '<div style="width:'.$wuStationWidth.'; margin: 0 auto;">'.PHP_EOL;      
	echo '<img src="'.$imgWUstation.'" '.$imgStyle.' alt="'.$descWuStation.'"  />';
        echo '</div>';
        echo $end_tab;
}





# ----------------------------------  Yrno ------ using an iframe -----
#
if ($YrnoAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descYrno.'</h3>'.PHP_EOL; } 
#
        echo '<iframe src="'.$linkYrno.'" style="width:100%; height: 800px; border: none; overflow: hidden; back-ground: transparent;"></iframe>'.PHP_EOL;
#
        echo $end_tab;
}
#
# ----------------------- and an example to use for extra radars   -----
#
if ($OtherAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descrOther.'</h3>'.PHP_EOL; }
#
        if ($linkOther <> '') {
# first a message to the visitor if there is a link
                echo '<h4  class="blockHead" style="padding: 5px;">'.$linkOtherText.'</h4>'.PHP_EOL;
                echo $linkOther;
        }
# now the img of the radar        
	echo '<img src="'.$imgOther.'" '.$imgStyle.' alt="'.$descrOther.'"  />';
        if ($linkOther <> '') {echo '</a>'.PHP_EOL;}
        echo $end_tab;
}
# -------------------------------- end of example for future use -------
#
if ($count > 1) {                       // if we havew tabs we need the javascript for it
        echo '</div>
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
echo '</div>
<!-- end of output rain_radar_america.php -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-19 release 2.8 version 
