<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'rain_radar_canada_small.php';
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$utc_date       = gmdate ('Y_m_d_H_00',time() - 5400);                    # 2015_05_03_10_00
#
$image_width    = '240';        // 240 is the normal width in pixels
#
$pageTitle      = langtransstr('Precipitation radars'); 
$link_text      = langtransstr('more information');
$title_text     = langtransstr('Click image to enlarge');

#
#-----------------------------------------------------------------------
#                               EC radar --- regional
#-----------------------------------------------------------------------
$descGC_region  = langtransstr('National radar');
$imgGC_region   = 'http://weather.gc.ca/data/radar/detailed/temp_image/COMPOSITE_NAT/COMPOSITE_NAT_PRECIP_RAIN_'.$utc_date.'.GIF';
#-----------------------------------------------------------------------
#                              radar --- station 1
#-----------------------------------------------------------------------
$descGC_station = langtransstr('Radar station').' '.$SITE['radarStation'];
$imgGC_station  = 'http://weather.gc.ca/data/radar/detailed/temp_image/'.$SITE['radarStation'].'/'.$SITE['radarStation'].'_PRECIP_RAIN_'.$utc_date.'.GIF';
#-----------------------------------------------------------------------
# I realy mean it, no settings below this point
# Only change below if you realy know what you are doing.
# If in doubt, use a forum or the support site at http://leuven-template.eu/index.php
$link_to_page           = $SITE['pages']['wsPrecipRadar'].'&amp;lang='.$lang.$extraP.$skiptopText;
$image_width            = 1.0*$image_width;
if ($image_width > 320 || $image_width < 100) {$image_width    = '240';}
$image_style            = 'width: '.$image_width.'px; margin: 4px auto;  border: none; vertical-align: bottom;';
#
echo '<div class="blockDiv"><!-- rain_radar_canada_small -->
<table class="genericTable">
<tbody>
<tr class="blockHead" style="">
<td style="width: 2%;">&nbsp;</td>
<td style="width: 47%;">
<h4 style="margin: 0px;">'.
$descGC_region.'&nbsp;<a href="'.$link_to_page.'"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle;" alt="'.$link_text.'" title="'.$link_text.'"></a>
</h4></td>
<td style="width: 2%;"></td>
<td style="width: 47%;">
<h4 style="margin: 0px;">'.
$descGC_station.'&nbsp;<a href="'.$link_to_page.'"><img src="./img/submit.png" style="margin: 1px; vertical-align: middle;" alt="'.$link_text.'" title="'.$link_text.'"></a>
</h4></td>
<td style="width: 2%;">&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><a  href="'.$imgGC_region.'"  rel="lightbox" title="'.$title_text.'"><img src="'.$imgGC_region.'"  alt="'.$descGC_region.'"   style="'.$image_style.'" /></a></td>
<td>&nbsp;</td>
<td><a  href="'.$imgGC_station.'" rel="lightbox" title="'.$title_text.'"><img src="'.$imgGC_station.'" alt="'.$descGC_station.'"   style="'.$image_style.'" /></a></td>
<td>&nbsp;</td>
</tr>
</tbody></table>
</div><!-- end of rain_radar_canada_small -->'.PHP_EOL;
return;
# ----------------------  version history
# 3.20 2015-07-28 release 2.8 version 
