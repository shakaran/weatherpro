<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   # display source of script if requested so
   $filenameReal = __FILE__;
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
$pageName	= 'us_aqhi_dash_map.php';
$pageVersion	= '3.20 2015-07-17';
#-------------------------------------------------------------------------------
# 3.20 2015-07-17 release 2.8 version (latest highcharts version)
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#---------------------------------------------------------------------------------------
$script = '_my_texts/us_aqhi.php';
ws_message (  '<!-- module us_aqhi_dash_map.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
#---------------------------------------------------------------------------------------
#
# pleasae do not change below this line
#
$arr_width      = array (40,40,40,30 , 22);
$img_html       = '';
$img_height     = ''; #'max-height: 180px;';
$i              = 0;
$img            = array();
if (isset($url_img_usa) && $url_img_usa <> '' && $url_img_usa <> false) {
        $now_ymd        = date('Ymd',time());
        $arr            = explode ('*',$url_img_usa);
        $last_part      = count($arr)-1;
        $img[]  = $arr[0].$now_ymd.$arr[$last_part];
}
if (isset($url_img_fct) && $url_img_fct <> '' && $url_img_fct <> false) {
        $now_ymd        = date('Ymd',time());
        $arr            = explode ('*',$url_img_fct);
        $last_part      = count($arr)-1;
        $img[]  = $arr[0].$now_ymd.$arr[$last_part];
}

if (isset($url_img_obs) && $url_img_obs <> '' && $url_img_obs <> false) {$img[] = $url_img_obs;}
if (isset($url_img_obs_mov) && $url_img_obs_mov <> '' && $url_img_obs_mov <> false) {$img[] = $url_img_obs_mov;}
$end     = count($img);

if ($end == 0) {
        if ($wsDebug) {$head = '<h3>no images found, sorry</h3>'; }
} 
else {  $head = '<h3 class="blockHead" style= "margin:0px;">Air quality for '. $SITE['yourArea'].
                ' - Click on an image to enlarge it - For more information visit <a href="http://www.airnow.gov/" target="_blank">Airnow.gov</a></h3>';
}
$extra  = '';
$img_width  = 'width: '.$arr_width[$end].'%; ';
for ($n = 0; $n < $end; $n++) {
        $img_html.= $extra.'<td style="'.$img_width.'">
<a  href="'.$img[$n].'" rel="lightbox" title="Click image to enlarge">
<img style="vertical-align: bottom; width: 100%; '.$img_height.'" src="'.$img[$n].'" alt="img" /></a></td>'.PHP_EOL;
        $extra = '<td>&nbsp;</td>';
}
$colors = '<table class="sortable genericTable" style="text-align: center; width: 100%; margin: 0 auto; font-size: 8px;">
<tbody>  
<tr style="height: 10px;">
<td style="width: 16.66%; font-weight:bold; color: #000; background-color: rgb(0,228,0);">Good</td>
<td style="width: 16.66%; font-weight:bold; color: #000; background-color: rgb(255,255,0);">Moderate</td>
<td style="width: 16.66%; font-weight:bold; color: #000; background-color: rgb(255,126,0);">Unhealthy-Sensitive&nbsp;Groups</td>
<td style="width: 16.66%; font-weight:bold; color: #fff; background-color: rgb(255,0,0);">Unhealthy</td>
<td style="width: 16.66%; font-weight:bold; color: #fff; background-color: rgb(153,0,76);">Very Unhealthy </td>
<td style="width: 16.66%; font-weight:bold; color: #fff; background-color: rgb(76,0,38);">Hazardous</td>
</tr>
</tbody>
</table>';

echo '<!-- start outuput us_aqhi_dash_map.php -->
<div class="blockDiv">
'.$head.'
<table class="genericTable" style="width: 100%">
<tr>'.
$img_html.'</tr>
</table>
'.$colors.'
</div>
<!-- end of outuput us_aqhi_dash_map.php -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-17 release 2.8 version 
