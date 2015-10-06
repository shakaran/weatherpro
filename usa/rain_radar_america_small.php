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
$pageName	= 'rain_radar_america_small.php';
$pageVersion	= '3.20 2015-07-22';
#----------------------------------------------------------------------
# 3.20 2015-07-22 release 2.8 version 
#----------------------------------------------------------------------		
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#----------------------------------------------------------------------		
#
$pageTitle      = langtransstr('Precipitation radars'); 
$link_text      = langtransstr('more information');
$title_text     = langtransstr('Click image to enlarge');
#-----------------------------------------------------------------------
#                               wu radar --- regional
#-----------------------------------------------------------------------
$descWU_region  = langtransstr('Regional radar');
$linkWU_region  = 'http://www.wunderground.com/weather-radar/united-states/';   // link to wu site with extra information
$imgWU_region   = 'http://icons.wunderground.com/data/640x480/'.$SITE['WUregion'].'_rd_anim.gif';
#-----------------------------------------------------------------------
#                               wu radar --- station
#-----------------------------------------------------------------------
$descWU_station = langtransstr('Radar station').' '.$SITE['radarStation'];
$linkWU_station = 'http://www.wunderground.com/weather-radar/united-states/';   // link to wu site with extra information
$imgWU_station  = 'http://radblast.wunderground.com/cgi-bin/radar/WUNIDS_map?station='.$SITE['radarStation'].'&amp;brand=wui&amp;num=6&amp;delay=15&amp;type=C0R&amp;frame=0&amp;scale=1.000&amp;noclutter=0&amp;lat=0&amp;lon=0&amp;label=you&amp;showstorms=0&amp;map.x=400&amp;map.y=240&amp;centerx=400&amp;centery=240&amp;transx=0&amp;transy=0&amp;showlabels=1&amp;severe=0&amp;rainsnow=0&amp;lightning=0&amp;smooth=0&amp;time='.time();
$imgWU_station  = 'http://radblast.wunderground.com/cgi-bin/radar/WUNIDS_map?station='.$SITE['radarStation'].'&amp;brand=wui&amp;num=6&amp;delay=15&amp;type=N0R&amp;frame=0&amp;scale=1.000&amp;noclutter=0&amp;showstorms=0&amp;mapx=400&amp;mapy=240&amp;centerx=400&amp;centery=240&amp;transx=0&amp;transy=0&amp;showlabels=1&amp;severe=0&amp;rainsnow=0&amp;lightning=0&amp;smooth=0&amp;rand=23537938&amp;lat=0&amp;lon=0&amp;label=you&amp;time='.time();
# There are nearly similar links. If the second one does not work comment the second one and probably the first one will work
#
#------------------------  end of Settings   ---------------------------
# I realy mean it, no settings below this point
# Only change below if you realy know what you are doing.
# If in doubt, use a forum or the support site at http://leuven-template.eu/index.php
$link_to_page   = $SITE['pages']['wsPrecipRadar'].'&amp;lang='.$lang.$extraP.$skiptopText;
$width          = 100;
$imgStyle	= 'border: none; max-width: '.$width.'%; max-height: 250px; margin: 4px auto; vertical-align: bottom;'; 
$title_text     = langtransstr('Click image to enlarge');
#
echo '<!-- output '.$pageFile.' -->
<div class="blockDiv">
<table class="genericTable" style="width: 100%;">
<tbody>
<tr class="blockHead" style="margin: 0px; ">
<td style="width: 2%;">&nbsp;</td>
<td style="width: 47%;">
<h4 style="margin: 0px;">'.
        $descWU_region.'<a href="'.$link_to_page.'"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle;" alt="'.$link_text.'" title="'.$link_text.'"></a>
</h4></td>
<td style="width: 2%;">&nbsp;</td>
<td style="width: 47%;">
<h4 style="margin: 0px;">'.
        $descWU_station.'<a href="'.$link_to_page.'"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle;" alt="'.$link_text.'" title="'.$link_text.'"></a>
</h4></td>
<td style="width: 2%;">&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><a  href="'.$imgWU_region.'"  rel="lightbox" title="'.$title_text.'"><img src="'.$imgWU_region.'" alt="'.$descWU_region.'"       style="'.$imgStyle.'" /></a></td>
<td>&nbsp;</td>
<td><a  href="'.$imgWU_station.'"  rel="lightbox" title="'.$title_text.'"><img src="'.$imgWU_station.'" alt="'.$descWU_station.'"     style="'.$imgStyle.'" /></a></td>
<td>&nbsp;</td>
</tr>
</tbody></table>
</div>
<!-- end of output '.$pageFile.'  -->'.PHP_EOL;
return;
# ----------------------  version history
# 3.20 2015-07-22 release 2.8 version 
