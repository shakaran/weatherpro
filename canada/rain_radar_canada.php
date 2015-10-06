<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;            # display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'rain_radar_canada.php';
$pageVersion	= '3.20 2015-08-02';
#-------------------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
$pageTitle      = langtransstr('Precipitation radars'); 
#
#-----------------------------------------------------------------------
#                               wu radar --- station
#-----------------------------------------------------------------------
# Go to http://www.wunderground.com/weather-radar/canada/
# Select your station by clicking on the mapon one of the white crosses
# example you arrive at page http://www.wunderground.com/radar/radblast.asp?ID=XNC
# The three lettercode at the end (XMB) is needed four lines below at $wuRadarStation	
#
$wuStationAvailable	= true;                 // set to false if you do not want this radar

$wuRadarStation		= 'XNC';
$descWU_station          = langtransstr('WU radar').' '.$wuRadarStation;
$linkWU_station         = 'http://www.wunderground.com/weather-radar/united-states/';   // link to wu site with extra information
$linkWU_station_text     = langtransstr('Click on the image for the USA site');
#-----------------------------------------------------------------------
#                             EC radars 
#-----------------------------------------------------------------------
# go to http://weather.gc.ca/radar/index_e.html
# click on a radar station and in the browser address area you find the local statyions code
# example http://weather.gc.ca/radar/index_e.html?id=XMB
# The three lettercode at the end (XMB) is needed 
if ($lang == 'fr') {$ec_lang = 'f'; } else {$ec_lang = 'e'; }
#
$ecRadarAvailable_1	= true;          
$ec_station_1            = $SITE['radarStation'];
$ecRadarAvailable_2	= true;          
$ec_station_2            = 'XGO';
$ec_description         = langtransstr('EC Radar').' - ';
#-----------------------------------------------------------------------      
#                               other radar
#-----------------------------------------------------------------------
# just some code to jump-start with other radar stations 
#
$OtherAvailable         = false;
$imgOther               = 'http:// we have no other radars yet';
#$linkOther              = '';                                                          // if no link remove comment mark

$descrOther             = langtransstr('Another radar description'); 
$linkOther              = 'http:// link to main site for radar';     // if we have a link to the other radar
$linkOtherText          = langtransstr('Click on image for the radar site with a lot of extra information');                                                          
#-----------------------------------------------------------------------
#                               yrno world wide radar 
#-----------------------------------------------------------------------
$YrnoAvailable          = true;        // world wide radar available
# yrno world wide radar forecast
$linkYrno               ='http://www.yr.no/kart/#lat='.$SITE['latitude'].'&lon='.$SITE['longitude'].'&zoom=6&laga=nedb%C3%B8r&baseid=PunktUtlandet%3A2187922&proj=900913';
$descYrno               = langtransstr('Yrno radar');           // only displayed (on the tab) if there is more then 1 radar
# 
# ---------------------- general settings all radars  ------------------
#
$imgStyle	= 'style="width: 100%; vertical-align: bottom"';
#
$tabsWanted     = true; // ONLY if we have more then 1 radar, do we want to put them in tabs - default is yes, who wants otherwise?
#
#------------------------  end of Settings   ---------------------------
# I realy mean it, no settings below this point
# Only change below if you realy know what you are doing.
# If in doubt, use a forum or the support site at http://leuven-template.eu/index.php
#
$count  = 0;                    // number of radars for this region
#
if ($wuStationAvailable) {
        $count++;
#       $imgWU_station    = 'http://radblast.wunderground.com/cgi-bin/radar/WUNIDS_map?station='.$wuRadarStation.'&amp;brand=wui&amp;num=6&amp;delay=15&amp;type=N0R&amp;frame=0&amp;scale=1.000&amp;noclutter=0&amp;showstorms=0&amp;mapx=400&amp;mapy=240&amp;centerx=400&amp;centery=240&amp;transx=0&amp;transy=0&amp;showlabels=1&amp;severe=0&amp;rainsnow=0&amp;lightning=0&amp;smooth=0&amp;rand=23537938&amp;lat=0&amp;lon=0&amp;label=you&amp;time='.time();
# Above link works for US only, link below for Canadain ?
        $imgWU_station    = 'http://radblast.wunderground.com/cgi-bin/radar/WUNIDS_map?station='.$wuRadarStation.'&amp;brand=wui&amp;num=6&amp;delay=15&amp;type=C0R&amp;frame=0&amp;scale=1.000&amp;noclutter=0&amp;showstorms=0&amp;mapx=400&amp;mapy=240&amp;centerx=400&amp;centery=240&amp;transx=0&amp;transy=0&amp;showlabels=1&amp;severe=0&amp;rainsnow=0&amp;lightning=Hide&amp;smooth=0&amp;rand=23840097&amp;lat=0&amp;lon=0&amp;label=you&amp;time='.time();
}
if ($OtherAvailable)    { $count++; }
if ($YrnoAvailable)     { $count++; }
if ($ecRadarAvailable_1) { $count++; }
if ($ecRadarAvailable_2) { $count++; }
#
echo '<!-- canada radars page -->
<div class="blockDiv"><h3 class="blockHead">'.$pageTitle.'</h3>'.PHP_EOL;
#
if ($count < 2 || $tabsWanted == false) { 
        $start_tab      = $end_tab      = '';
        $count          = 0;
}
else {  $start_tab      = '<div class="tabbertab" style="padding: 0; background-color: #f9f9f9;">'.PHP_EOL;        // if more then 1 radar put them in tabs
        $end_tab        = '</div>'.PHP_EOL;
        echo '<!-- tabber --><br /><div class="tabber" style="width: 100%; ">'.PHP_EOL;
} // eo check tabs
#
if ($ecRadarAvailable_1) { 
        if ($count > 1) { echo $start_tab.'<h3 style="text-align: center;">'.$ec_description.$ec_station_1.'</h3>'.PHP_EOL; }             
        echo '<div style="width: 800px; margin: 0 auto; "><br />
<iframe id="iframe1" src="http://weather.gc.ca/radar/index_'.$ec_lang.'.html?id='.$ec_station_1.'" 
style="margin-top: -160px;  height: 2000px; width: 800px; border: none; overflow: hidden; back-ground: transparent;">
</iframe>
</div>'.PHP_EOL;
        echo $end_tab; 
}  // eo if ec radar 1
if ($ecRadarAvailable_2) { 
        if ($count > 1) { echo $start_tab.'<h3 style="text-align: center;">'.$ec_description.$ec_station_2.'</h3>'.PHP_EOL; }             
        echo '<div style="width: 800px; margin: 0 auto; "><br />
<iframe id="iframe2" src="http://weather.gc.ca/radar/index_'.$ec_lang.'.html?id='.$ec_station_2.'" 
style="margin-top: -160px;  height: 2000px; width: 800px; border: none; overflow: hidden; back-ground: transparent;">
</iframe>
</div>'.PHP_EOL;
        echo $end_tab; 
}  // eo if ec radar 2
# ----------------------- WU station Radar  ------------------------------------
#
if ($wuStationAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descWU_station .'</h3>'.PHP_EOL; }
#
        if ($linkWU_station <> '') {
# first a message to the visitor if there is a link
                echo '<h4  class="blockHead" style="padding: 5px;">'.$linkWU_station_text.'</h4>'.PHP_EOL;
                echo '<a href="'.$linkWU_station.'"  target="_blank">';
        }
# now the img of the radar        
	echo '<img src="'.$imgWU_station.'" '.$imgStyle.' alt="'.$descWU_station .'"  />';
        if ($linkWU_station <> '') {echo '</a>'.PHP_EOL;}
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
        echo '</div><!-- tabber -->
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
echo '</div>
<!-- end of <!-- canada radars page -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
