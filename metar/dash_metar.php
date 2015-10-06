<?php
#-------------------------    SETTINGS   ---------------------------------------
$max_cities     = 4;                    // number of cities to be shown
$forecast_page  = 'ws_metno_page';
#-------------------------------------------------------------------------------
#
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
$pageName	= 'dash_metar.php';
$pageVersion	= '3.20 2015-08-26';
#-------------------------------------------------------------------------------
# 3.20 2015-08-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$metar_folder   = './metar/';
$selection_file = $SITE['multi_fct_keys'];
$my_metars      = array();
$arr            = file($selection_file);
$end            = count ($arr);
$key            = 0;
for ($n = 0; $n < $end; $n++) {        
        $string         = trim($arr[$n]);       // |51.2603851|4.3577201|Antwerp   |EBAW |966591|xx  |
        if ($string == '') {continue;}
        if (substr($string,0,1) == '#') {continue;}
        list ($none,$lat,$lon,$area,$metar) = explode ('|',$string.'||||');
        $lat            = trim($lat);
        $lon            = trim($lon);
        $area           = trim($area);
        $metar          = trim($metar);
        if ($lat == '' || $lon == '' || $area == '' || $metar == '')    {continue;}     // skip lines with invalid key values
        if (!is_numeric($lat) ||   !is_numeric($lon) )                  {continue;}     // skip lines with non-numeric lat lon 
        $key++;        
        $my_metars[$metar]      = array ('key' => $key, 'name' => $area, 'lat' =>  $lat, '$lon' =>  $lon, 'found' => false );               
}

$testDash       = false;
$number_cities  = 0;
$start_metar    = microtime(true);
if (!function_exists ('mtr_conditions') ) { 
        $script = $metar_folder.'wsMetarTxt.php';
        ws_message (  '<!-- module dash_metar.php ('.__LINE__.'): loading '.$script.' -->');
        include $script; 
}
foreach ($my_metars as $key => $arr) {
        $mtr   = mtr_conditions($key);
        if ($mtr == false)  {
                unset ($my_metars[$key]); continue;
        }
        $number_cities++;
        $my_metars[$key]['found']       = true;
        $my_metars[$key]['temp']        = round(wsConvertTemperature ($mtr['temp'], 'C'),0);
        $my_metars[$key]['iconurl']     = $mtr['icon_url'];
        $extra  = $long_text      = '';
        if (isset ($mtr['conditions']) && $mtr['conditions'] <> '' ) {
                $end     = count ($mtr['conditions']);
                for ($n1 = 0; $n1 < $end; $n1++) {
                        $text           = langtransstr($mtr['conditions'][$n1]);
                        $long_text     .= $extra.$text;
                        $extra          = ', ';
                }
        }
        if (isset ($mtr['covers_max']) && $mtr['covers_max'] <> '') {
                $text           = langtransstr($mtr['covers_max']);
                $long_text     .= $extra.$text;
        }
        $my_metars[$key]['desc']     = $long_text;
        if ($number_cities >=  $max_cities) {break;}
}
$seconds_metar  = microtime(true) - $start_metar;
#
#
$width = round ( ( 100  / $number_cities ), 4).'%';
$line1 = $line2 = $line3 = '';
if (!isset ($skiptopText) ) {$skiptopText = '';}
$link_forecast     = false;
if (isset ($SITE['pages'][$forecast_page]) ) {$link_forecast     = $SITE['pages'][$forecast_page];}
# generate the td's to output later.
$max_cities     = 1;
foreach ($my_metars as $key => $arr) {
        $line1 .= '<th class="blockHead" style="width:'.$width.'; "><small>'.$my_metars[$key]['name'].'</small> ';
        if ($link_forecast  <> false ) {
                $line1 .='
<a href="'.$link_forecast.'&amp;lang='.$lang.'&amp;city='.$my_metars[$key]['key'].$skiptopText.'">
<span style="float: right;"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information">&nbsp;</span></a> ';
        }
        $line1 .='</th>'.PHP_EOL;
        $line2 .= '<td style="text-align: center;">'.
                ws_commontemp_color($my_metars[$key]['temp']).
                '&nbsp;&nbsp;<img src="./'.$my_metars[$key]['iconurl'] .'" alt=" " style="width: 28px; vertical-align: bottom;"/></td>'.PHP_EOL;
        $line3 .= '<td style="text-align: center;">'.$my_metars[$key]['desc'].'</td>'.PHP_EOL;
        $max_cities++;
        if ($max_cities > $number_cities) {break;}
}
# echo all data now
echo '<!-- dash_metar.php -->
<div class="blockDiv">
<h3 class="blockHead">',langtransstr('The weather in interesting cities nearby').'</h3>'.PHP_EOL;
ws_message ('<!-- module dash_metar.php ('.__LINE__.'): loaded '.$number_cities.' ccn using METAR in '.$seconds_metar.' seconds -->',true);
echo '<table class="" style="width: 100%;">
<tr>'.PHP_EOL.$line2.'</tr>
<tr style="">'.PHP_EOL.$line3.'</tr>
<tr style="height: 16px;">'.PHP_EOL.$line1.'</tr>
</table>
</div>
<!-- end of dash_metar.php -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-08-26 release 2.8 version 
