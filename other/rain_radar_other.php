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
$pageName	= 'rain_radar_other.php';
$pageVersion	= '3.00 2015-09-10';
#		
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#----------------------------------------------------------------------
# 3.00 2015-09-10 release version 
#-----------------------------------Settings:---------------------------
#
$pageTitle      = langtransstr('Precipitation forecasts'); 
#
$YrnoAvailable  = true;         // world wide radar available
$OtherAvailable = false;        // to add your own local radar image

# yrno world wide radar forecast
$linkYrno       ='http://www.yr.no/kart/#lat='.$SITE['latitude'].'&amp;lon='.$SITE['longitude'].'amp;zoom=6amp;laga=nedb%C3%B8ramp;baseid=PunktUtlandet%3A2187922amp;proj=900913';
$descYrno       = langtransstr('Yrno radar');           // only displayed (on the tab) if there is more then 1 radar
#
# other radar
$imgOther       = 'http:// we have no other radars yet';
$linkOther      = '<a href="http:// link to main site for radar" target="_blank">';     // if we have a link to the other radar
#$linkOther      = '';                                                                  // if no link remove comment mark
$descrOther     = langtransstr('Another radar description'); 
$msgOther       = langtransstr('Click on image for the radar site with a lot of extra information');                                                          
# 
$imgStyle	= 'style="width: 100%; vertical-align: bottom"';
#
$tabsWanted     = true; // ONLY if we have more then 1 radar, do we want to put them in tabs - default is yes, who wants otherwise?
#
#------------------------  end of Settings   ---------------------------
#
$count  = 0;                    // number of radars for this region
if ($YrnoAvailable)   { $count++; }
if ($OtherAvailable)  { $count++; }
#
echo '<!-- page --><div class="blockDiv"><h3 class="blockHead">'.$pageTitle.'</h3>'.PHP_EOL;
#
if ($count < 2 || $tabsWanted == false) { 
        $start_tab      = $end_tab      = '';
        $count          = 0;
}
else {  $start_tab      = '<div class="tabbertab" style="padding: 0;">'.PHP_EOL;        // if more then 1 radar put them in tabs
        $end_tab        = '</div>'.PHP_EOL;
        echo '<!-- tabber --><br /><div class="tabber" style="width: 100%; ">'.PHP_EOL;
} // eo check tabs
#
# ----------------------------------  Radar 1 ---- using an iframe -----
#
if ($YrnoAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descYrno.'</h3>'.PHP_EOL; } 
#
        echo '<iframe src="'.$linkYrno.'" style="width:100%; height: 800px; border: none; overflow: hidden; back-ground: transparent;"></iframe>'.PHP_EOL;
#
        echo $end_tab;
}
# --------------------------------  for future use as an example -------
#
# ----------------------- Radar 2  an image with an optional link  -----
#

if ($OtherAvailable)  {
        if ($count > 1) {echo $start_tab. '<h3 style="text-align: center;">'.$descrOther.'</h3>'.PHP_EOL; }
#
        if ($linkOther <> '') {
# first a message to the visitor if there is a link
                echo '<h4  class="blockHead" style="padding: 5px;">'.langtransstr('Click on image for the radar site with a lot of extra information').'</h4>'.PHP_EOL;
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
echo '</div><!-- page -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-09-10 release 2.8 version 
