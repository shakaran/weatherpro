<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'dash_yahoo.php';
$pageVersion	= '3.20 2015-09-19';
#-------------------------------------------------------------------------------
# 3.20 2015-09-19 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$max_cities     = $number_cities = 4;          // number of cities to be shown
#
$yahoo_folder   ='./forecasts/';  
$selection_file = $SITE['multi_fct_keys'];
#
$my_yahoos      = array();
$arr            = file($selection_file);
$end            = count ($arr);
#echo '<pre>'; print_r ($arr);  exit;
$key    = 0;
for ($n = 0; $n < $end; $n++) {        
        $string         = trim($arr[$n]);
        if ($string == '') {continue;}
        if (substr($string,0,1) == '#') {continue;}
        list ($none,$lat,$lon,$area,$metar,$hahooID) = explode ('|',$string.'||||');
        $lat            = trim($lat);
        $lon            = trim($lon);
        $area           = trim($area);
        $metar          = trim($metar);
        $hahooID        = trim($hahooID);
        if ($lat == '' || $lon == '' || $area == '' || $metar == '' || $hahooID == '')  {continue;}     // skip lines with invalid key values
        if (!is_numeric($hahooID) ) {continue;}     // skip lines with non-numeric yahoo id
        $key++;        
        $my_yahoos[$hahooID]      = array ('key' => $key, 'name' => $area, 'lat' =>  $lat, 'lon' =>  $lon,'hahooID' => $hahooID,'found' => false );               
}
#
if (!isset ($yahooArray)) {
        ws_message (  '<!-- moduledash_yahoo.php ('.__LINE__.'): loading yahoo.weather2.php -->');
	include_once $yahoo_folder.'yahoo.weather2.php';
} 
else {  ws_message ('<!-- dash_yahoo.php ('.__LINE__.'): yahoo data already loaded --> ');
}
$weather        = new yahooWeather();
$testDash       = false;
$result_arr     = array();
#
#echo '<pre>'; print_r ($my_yahoos);  exit;
$count_yahoo    = 0;
$start_yahoo    = microtime(true);
foreach ($my_yahoos as $key => $arr) {
        $hahooID        = $arr['hahooID'];
        $result         = $weather->getWeatherData($hahooID);
#echo '<pre>'; var_dump($result['ccn'][1]); exit;
        if (!$result)  {unset ($my_yahoos[$key]); continue;} # {echo '<br />false for : '.$key; continue;} else {echo '<br />true for : '.$key;}
        $count_yahoo++;
        $my_yahoos[$key]['found']       = true;
        $my_yahoos[$key]['temp']        = round($result['ccn'][1]['tempNU'],0);
        $notUsed = '';	$iconOut='';	$iconUrlOut = '';
	wsChangeIcon ('yahoo',$result['ccn'][1]['icon'], $iconOut, $result['ccn'][1]['iconUrl'], $iconUrlOut, $notUsed);
        $my_yahoos[$key]['iconurl']     = $iconUrlOut;
        $my_yahoos[$key]['desc']        = langtransstr( $result['ccn'][1]['text']);
        if ($count_yahoo >=  $max_cities) {break;}
#echo '<pre>'; var_dump($my_yahoos[$key ]); exit;
}
$seconds_yahoo  = microtime(true) - $start_yahoo;
ws_message ('<!-- <!-- dash_yahoo.php ('.__LINE__.'): loading '.$count_yahoo.' ccn using yahoo in '.$seconds_yahoo.' seconds -->');
if ( $count_yahoo == 0) {return;}
#echo '<pre>'; var_dump($my_yahoos); exit;
echo '<div class="blockDiv">
<h3 class="blockHead">',langtransstr('The weather in interesting cities nearby').'</h3>'.PHP_EOL;
#
$width = round ( ( 100  / $count_yahoo ), 1).'%';
#echo '<pre>'; print_r($my_yahoos);
echo '<table class="" style="width: 100%;">'.PHP_EOL;
$line1 = $line2 = $line3 = '';
if (!isset ($skiptopText) ) {$skiptopText = '';}
$max_cities     = 1;
foreach ($my_yahoos as $key => $arr) {
        $line1 .= '<th class="blockHead" style="width:'.$width.'; "><small>'.$my_yahoos[$key]['name'].'</small> 
<a href="'.$SITE['pages']['yahooForecast2'].'&amp;lang='.$lang.'&amp;city='.$my_yahoos[$key]['hahooID'].$skiptopText.'">
<span style="float: right;"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information">&nbsp;</span></a>       
        </th>';
        $line2 .= '<td style="text-align: center;">'.
                ws_commontemp_color($my_yahoos[$key]['temp']).
                '&nbsp;&nbsp;<img src="'.$my_yahoos[$key]['iconurl'] .'" alt=" " style="width: 28px; vertical-align: bottom;"/></td>';
        $line3 .= '<td style="text-align: center;">'.$my_yahoos[$key]['desc'].'</td>';
        $max_cities++;
        if ($max_cities > $number_cities) {break;}

}
echo '<tr>'.$line2.'</tr>'.PHP_EOL;
echo '<tr style="">'.$line3.'</tr>'.PHP_EOL;
echo '<tr style="height: 16px;">'.$line1.'</tr>'.PHP_EOL;
echo '</table>
</div>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-9-19 release 2.8 version 
