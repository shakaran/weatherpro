<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { //--self downloader --
   $filenameReal = __FILE__;	# display source of script if requested so
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
$pageName	= 'us_aqhi_page.php';
$pageVersion	= '3.20 2015-07-17';
#-------------------------------------------------------------------------------
# 3.20 2015-07-17 release 2.8 version 
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
#---------------------------------------------------------------------------------------
$script = './usa/aq/us_aqhi_dash_map.php';
ws_message (  '<!-- module us_aqhi_page.php ('.__LINE__.'): loading '.$script.' -->');
include $script;

$aqhi_compact   = false;
$script = './usa/aq/us_aqhi_dash.php';
ws_message (  '<!-- module us_aqhi_page.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
#
echo '<div class="blockDiv">
<h3 class="blockHead" style= "margin:0px;">Color codes explained</h3>'.PHP_EOL;
$aqhi_color_array_us    = array();
        $aqhi_color_array_us[]  = array ('text' =>'white',      'rgb' => 'rgb(0,0,0)'     , 'color' => '#000');
        $aqhi_color_array_us[]  = array ('text' =>'Green',      'rgb' => 'rgb(0,228,0)'   , 'color' => '#000');
        $aqhi_color_array_us[]  = array ('text' =>'Yellow',     'rgb' => 'rgb(255,255,0)' , 'color' => '#000');
        $aqhi_color_array_us[]  = array ('text' =>'Orange',     'rgb' => 'rgb(255,126,0)' , 'color' => '#000');;
        $aqhi_color_array_us[]  = array ('text' =>'Red',        'rgb' => 'rgb(255,0,0)'   , 'color' => '#FFF');
        $aqhi_color_array_us[]  = array ('text' =>'Purple',     'rgb' => 'rgb(153,0,76)'  , 'color' => '#FFF');
        $aqhi_color_array_us[]  = array ('text' =>'Maroon',     'rgb' => 'rgb(76,0,38)'   , 'color' => '#FFF');
        $aqhi_color_array_us[]  = array ('text' =>'white',      'rgb' => 'rgb(0,0,0)'     , 'color' => '#FFF');

echo '<table class=genericTable" style="text-align: center; width: 100%; margin: 0 auto;">
<thead style="background-color: #ccc;">
<tr style="border-bottom: 1px solid grey;">
<th>AQHI</th><th style="min-width: 100px;">Value</th><th>Meaning</th>
</tr>
<tbody>  
<tr style="border-bottom: 1px solid grey;">
<td style="font-weight:bold; color:  '.$aqhi_color_array_us[1]['color'].'; background-color:'.$aqhi_color_array_us[1]['rgb'].';">Good</td>
<td style="border: 1px solid grey;">0 to 50</td>
<td >Air quality is considered satisfactory, and air pollution poses little or no risk</td>
</tr>
<tr style="border-bottom: 1px solid grey;">
<td style="font-weight:bold; color:  '.$aqhi_color_array_us[2]['color'].'; background-color:'.$aqhi_color_array_us[2]['rgb'].';">Moderate</td>
<td style="border: 1px solid grey;">51 to 100</td>
<td >Air quality is acceptable; however, for some pollutants there may be a moderate health concern for a very small number of people who are unusually sensitive to air pollution.</td>
</tr>
<tr style="border-bottom: 1px solid grey;">
<td  style="font-weight:bold; color:  '.$aqhi_color_array_us[3]['color'].'; background-color:'.$aqhi_color_array_us[3]['rgb'].';">Unhealthy for Sensitive Groups</td>
<td style="border: 1px solid grey;">101 to 150</td>
<td >Members of sensitive groups may experience health effects. The general public is not likely to be affected. </td>
</tr>
<tr style="border-bottom: 1px solid grey;">
<td  style="font-weight:bold; color:  '.$aqhi_color_array_us[4]['color'].'; background-color:'.$aqhi_color_array_us[4]['rgb'].';">Unhealthy</td>
<td style="border: 1px solid grey;">151 to 200</td>
<td >Everyone may begin to experience health effects; members of sensitive groups may experience more serious health effects.</td>
</tr>
<tr style="border-bottom: 1px solid grey;">
<td  style="font-weight:bold; color:  '.$aqhi_color_array_us[5]['color'].'; background-color:'.$aqhi_color_array_us[5]['rgb'].';">Very Unhealthy </td>
<td style="border: 1px solid grey;">201 to 300</td>
<td >Health warnings of emergency conditions. The entire population is more likely to be affected. </td>
</tr>
<tr style="border-bottom: 1px solid grey;">
<td  style="font-weight:bold; color:  '.$aqhi_color_array_us[6]['color'].'; background-color:'.$aqhi_color_array_us[6]['rgb'].';">Hazardous</td>
<td style="border: 1px solid grey;">301 to 500</td>
<td >Health alert: everyone may experience more serious health effects</td>
</tr>
<tr style="background-color: #ccc;"><td colspan="3" style="border-top: 1px solid grey;">Data originates from <a href="http://www.airnow.gov/" target="_blank">
Airnow.gov </a> where you can find a wealth of information</td></tr>
</tbody>
</table>
</div>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-17 release 2.8 version 
