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
$pageName	= 'us_aqhi_dash.php';
$pageVersion	= '3.20 2015-09-10';
#-------------------------------------------------------------------------------
# 3.20 2015-09-10 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
#  to do: document
#-----------------------------------------------------------------------------------------
$aqhi_api_key   = '55DC78B1-993D-48FD-9E8E-EEC5E9BB58B6';
#-----------------------------------------------------------------------------------------
$wsaqhius       = $pageFile;
$now_ymd        = date('Y-m-d',time());
$distance_all   = '50';
$cache_allowed	= 7200;				// seconds before file is to old
$return_string  = '';
$arr_quality    = array();
$loaded_current = $loaded_fct   = false;
$aqhi_detail_p  = $SITE['pages']['us_aqhi_page']; 
$aqhi_details   = true; 	// set to false if you want to go link to the details page, // wsAQHIusPage

$test           = false;
#
if ($test) {$startEcho      = ''; $endEcho        = '';} else {$startEcho      = '<!-- '; $endEcho        = ' -->';}
/*
http://www.airnowapi.org/aq/forecast/latLong/?format=text/csv&latitude=29.7510&longitude=-95.35108
&date=2014-12-03&distance=25&API_KEY=55DC78B1-993D-48FD-9E8E-EEC5E9BB58B6
*/
$url_aqhi_fct   = 'http://www.airnowapi.org/aq/forecast/latLong/?format=text/csv'.
'&latitude='.	$SITE['latitude'].
'&longitude='.	$SITE['longitude'].
'&date='.	$now_ymd .
'&distance='.	$distance_all.
'&API_KEY='.	$aqhi_api_key;
/*
http://www.airnowapi.org/aq/observation/latLong/current/?format=text/csv&latitude=29.7510&longitude=-95.3510
&distance=25&API_KEY=55DC78B1-993D-48FD-9E8E-EEC5E9BB58B6
*/
$url_aqhi_obs   = 'http://www.airnowapi.org/aq/observation/latLong/current/?format=text/csv'.
'&latitude='.	$SITE['latitude'].
'&longitude='.	$SITE['longitude'].
'&distance='.	$distance_all.
'&API_KEY='.	$aqhi_api_key;
#
$save_to_cache 	= true;	
$load_current   = $load_fct   = false;		// default set to load from cache
$string		= $wsaqhius.'_'.$SITE['latitude'].'_'.$SITE['longitude'];
$from		= array ('php','.');
$string		= trim( str_replace ($from,'',$string) );
$cached_file    = $SITE['cacheDir'].$string.'.txt';

#$local_fct      = './usa/aq/forecast.csv';	// for testing only
#$local_current  = './usa/aq/observed.csv';
#
if ( isset($_REQUEST['force']) && $_REQUEST['force'] == 'aqhi') {
        ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): cache checking is skipped as request for force was set to aqhi '.$endEcho,true);
        $load_current   = $load_fct   = true;           // we have to lad the files    
} elseif ( file_exists($cached_file) ) {
	$file_time	= filemtime($cached_file);
	$now		= time();
	$diff		= $now - $file_time;
	if ($cache_allowed < 3600) {$cache_allowed = 3600; }  // always use cache;	
	ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.')'.": AQHI data ($cached_file) 
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff (seconds)
	diff allowed = $cache_allowed (seconds) ".$endEcho);	
	if (isset ($cron_all) ) {			// runnig a cron job
		$cache_allowed 	= $cache_allowed - 600;	// 
		 ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): max cache lowered with 600 seconds as cron job is running -->');
	}	
        if ($diff <= $cache_allowed){
                $save_to_cache 	= false;         // all data is loaded from cache
                $arr_quality    = unserialize(file_get_contents($cached_file));
                ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): data ('.$cached_file.')  loaded '.$endEcho);
        } 
        else {  ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): data will be loaded from url'.$endEcho);
                $load_current   = $load_fct   = true;   // we have to load the files  
        }
} else {$load_current   = $load_fct   = true;}   	// we have to load the files 
// eo check cache
#
if ($load_fct) {        // no cache available / allowed
        ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): retrieving '.$url_aqhi_fct.$endEcho);
        if (isset($local_fct) && $local_fct <> '') {
                ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'):forecast loaded from test file: '.$local_fct.$endEcho,true);
                $raw_fct        = file_get_contents($local_fct);
        } 
        else {  $ch = curl_init();
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_URL, $url_aqhi_fct);
                curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
                $raw_fct        = curl_exec ($ch);
                curl_close ($ch);
        }
	if (!$raw_fct || check_aqhi_errors ($raw_fct)) {
	       ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): forecast data could not be retrieved from url / contains errors '.$endEcho,true);
	}
	else {	# process fct
		$save_to_cache  = true;
		$arr    = explode ("\n", $raw_fct);
#        echo '<pre>'; print_r ($arr);
		$end    = count($arr);
		$i 	= 0;
		for ($n = 1; $n < $end; $n++) {
			$string = trim ($arr[$n]);
			if ($string == '') {continue;}
			$arr2   = explode ('","',$string);
			$arr_quality['fct'][$i]['issued']  = $arr2[0];
			$arr_quality['fct'][$i]['date']    = $arr2[1];
			$arr_quality['fct'][$i]['loc']     = $arr2[2].', '.$arr2[3];
			$arr_quality['fct'][$i]['param']   = $arr2[6];
			$arr_quality['fct'][$i]['aqhi']    = $arr2[7];
			$arr_quality['fct'][$i]['nr']      = $arr2[8];
			$arr_quality['fct'][$i]['name']    = $arr2[9];
			$arr_quality['fct'][$i]['action']  = $arr2[10];
			$arr_quality['fct'][$i]['text']    = str_replace('"','',$arr2[11]);
			$i++;
		}
 #       echo '<pre>'; print_r($arr_quality); exit;		
	} // eo process fct
} // eo load_fct

if ($load_current) {     // no cache available / allowed  load observation
        ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): retrieving '.$url_aqhi_obs.$endEcho);
	if (isset($local_current) && $local_current <> '') {
                ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): observation loaded from test file: '.$local_current.' '.$endEcho,true);
                $raw_obs        = file_get_contents($local_current);
        } 
        else {  $ch = curl_init();
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_URL, $url_aqhi_obs);
                curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
                $raw_obs = curl_exec ($ch);
                curl_close ($ch);
        }
	if (!$raw_obs || check_aqhi_errors ($raw_obs)) {
	         ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'):  observation data could not be retrieved from url / contains errors  '.$endEcho,true);
	}
	else { #       process observation
		$save_to_cache  = true;	
		$arr    = explode ("\n", $raw_obs);
		$end    = count($arr);
		$i 	= 0;
		for ($n = 1; $n < $end; $n++) {
			$string = trim ($arr[$n]);
			if ($string == '') {continue;}
			$string = str_replace ('","','|',$string);
			$arr2   = explode ('|',$string.'|||');
			$arr_quality['obs'][$i]['date']    = $arr2[0];
			$arr_quality['obs'][$i]['hour']    = $arr2[1];
			$arr_quality['obs'][$i]['loc']     = $arr2[3].', '.$arr2[4];
			$arr_quality['obs'][$i]['param']   = $arr2[7];
			$arr_quality['obs'][$i]['aqhi']    = $arr2[8];
			$arr_quality['obs'][$i]['nr']      = $arr2[9];
			$arr_quality['obs'][$i]['name']    = str_replace('"','',$arr2[10]);
			$i++;
		}
        }  // eo process observation
} // eo load_current

if ($save_to_cache)  {
        if (file_put_contents($cached_file, serialize($arr_quality))){   
                ws_message ( $startEcho.$wsaqhius.' ('.__LINE__.'): AQHI data ('.$cached_file.') saved to cache  '.$endEcho);
        } else {
                echo '<h3>FATAL ERROR: AQHI data ('.$cached_file.') could not be saved to cache, cache is not correctly set. Program ends </h3>';
                return false;
        }
}
#echo '<pre>'; print_r($arr_quality); # exit;
if (isset ($aqhi_compact) ) {
	$aqhi_details = $aqhi_compact;
}
if (isset ($aqhi_details) && $aqhi_details == false) {
        if (!isset ($skiptopText)) {$skiptopText = '#data-area';}
        $link 	= $aqhi_detail_p.'&amp;lang='.$lang.$extraP.$skiptopText.'"';
        $img_i  = './img/submit.png';  
}
else {  $link 	= '"javascript:aqhi_us_click()"';
	$img_i  = './img/i_symbolWhite.png';
} 
aqhi_load_array_us();
#
if (!isset ($aqhi_compact) ) {
	$ahqi_link      = '<a href='.$link.'><img src="'.$img_i.'" style="margin:1px; vertical-align: middle;" alt =" " title ="'.langtransstr('more information').'"></a>';
} 
else {	$ahqi_link      = '';
}
$java_hide_show = '<!-- start output '.$wsaqhius.' -->
<script type="text/javascript">
  function aqhi_us_click() {
        hideshow(document.getElementById(\'aqhi_us_small\'))
        hideshow(document.getElementById(\'aqhi_us_large\'))
        }
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>'.PHP_EOL;
$arrow_black  = '<td> <div style="border-left:10px solid transparent;
border-right:10px solid transparent;
border-top:10px solid #000;
height:0;
width:0;
margin:0 auto;
"></div></td>'.PHP_EOL;
#echo '<pre>'; print_r($arr_quality); 
$header_arr [0] = langtransstr('Air quality forecast');
if (isset ($arr_quality['obs'][0]['loc'])){
        $location       = $arr_quality['obs'][0]['loc'];
} elseif (isset ($arr_quality['fct'][0]['loc'])){
        $location       = $arr_quality['fct'][0]['loc'];
} else {echo '<h3 style="text-align: center;"> Air quality => no valid data found for this area </h3>'; return false;}
$title          = $header_arr [0];
$time           = '';
$str_out        = '<div class="blockDiv">
<h3 class="blockHead" style= "margin:0px;">'.$location.' - '.$title.'&nbsp;&nbsp;'.$ahqi_link .'</h3>'.PHP_EOL;

$str_max        = '<div id="aqhi_us_large" style="display: none; max-width: 800px; width: 90%; margin: 10px auto;">
<table class="" style = "width: 100%; border-collapse: collapse;">'.PHP_EOL;
$str_small      = '<div id="aqhi_us_small" style="display: block; max-width: 800px; width: 90%; margin: 10px auto;">
<table class="" style = "width: 100%; border-collapse: collapse;">'.PHP_EOL;
$str_hr         = '<tr style="height: 4px;"><td style="height: 4px;" colspan ="10"><hr /></td></tr>'.PHP_EOL;

$high_aqhi      = $high_nr        = '-10';
if (isset ($arr_quality['obs']) ) {
        $end_obs        = count($arr_quality['obs']);
        $period         = langtransstr('Current');
        for ($n = 0; $n < $end_obs; $n++) {
                $ahqi           = $arr_quality['obs'][$n]['aqhi'];
                $nr             = $arr_quality['obs'][$n]['nr'];
                $param          = $arr_quality['obs'][$n]['param'];
                $risk           = langtransstr($arr_quality['obs'][$n]['name']);
                if ($nr > 6)    {$nr = 6;}
                if ($nr < 1)    {$nr = 0;}
                if ($ahqi < 1) {                // not specified 
                        $ahqi_txt       = $aqhi_key_array_us[$nr];
                } else {
                        $ahqi_txt       = $ahqi;
                }
                        
                $string = '<tr><td style="text-align: left;"><small>'.$period.'</small></td>
<td style="text-align: left;"><small>'.$param.'</small></td>
<td style="text-align: center;"><small><b>'.$ahqi_txt.'</b></small></td>
<td style="text-align: left;"><small>'.$risk.'</small></td>'.PHP_EOL;
                for ($p = 1; $p <= 6; $p++) {
                        if ($nr == $p) {$string .= $arrow_black;} else {$string .= '<td>&nbsp;</td>'; }
                }
                $string .= '</tr>'.PHP_EOL;
                $str_max .= $string;
                if ($nr > $high_nr || $ahqi >  $high_aqhi) {
                        $high_nr        = $nr;
                        $high_aqhi      = $ahqi;
                        $param          = '';
                        $high_obs       = $string;
                }	
        }
        $str_small      .= $high_obs;
        if ($end_obs > 1) {$str_max .= $str_hr;}

}
$high_aqhi      = $high_nr      = '-10';
$date           = $issued       = '';
if (isset ($arr_quality['fct']) ) {
        $issued         = $arr_quality['fct'][0]['issued'];
        $from           = array ('"','-');
        $issued         = str_replace ($from,'',$issued);
        $date_text      = $issued.'T120000';
        $date_time      = strtotime($date_text);
        $issued         = date ($SITE['dateLongFormat'],$date_time);
        $end_fct        = count($arr_quality['fct']);
        $date           = $arr_quality['fct'][0]['date'];
        $line_date      = 1;
        for ($n = 0; $n < $end_fct; $n++) {
                $period         = $arr_quality['fct'][$n]['date'];
                $ahqi           = $arr_quality['fct'][$n]['aqhi'];
                $nr             = $arr_quality['fct'][$n]['nr'];
                $param          = $arr_quality['fct'][$n]['param'];
                $risk           = langtransstr($arr_quality['fct'][$n]['name']);
                if ($nr > 6)    {$nr = 6;}
                if ($nr < 1)    {$nr = 0;}
                if ($ahqi < 1) {                // not specified 
                        $ahqi_txt       = $aqhi_key_array_us[$nr];
                } else {
                        $ahqi_txt       = $ahqi;
                }
                        
                $string = '<tr>
<td style="text-align: left;"><small>'.$period.'</small></td>
<td style="text-align: left;"><small>'.$param.'</small></td>
<td style="text-align: center;"><small><b>'.$ahqi_txt.'</b></small></td>
<td style="text-align: left;"><small>'.$risk.'</small></td>'.PHP_EOL;
                for ($p = 1; $p <= 6; $p++) {
                        if ($nr == $p) {$string .= $arrow_black;} else {$string .= '<td>&nbsp;</td>'; }
                }
                $string .= '</tr>'.PHP_EOL;
                 if ($period <> $date) {
                        if ($line_date > 1) {$str_max .= $str_hr;}
                        $line_date      = 0;
                        $str_small      .= $high_fct;
                        $date           = $period;
                        $high_aqhi      = $high_nr        = '-10'; 
                }
                $str_max .= $string;
                $line_date++;
                if ($nr > $high_nr || $ahqi >  $high_aqhi) {
                        $high_nr        = $nr;
                        $high_aqhi      = $ahqi;
                        $high_fct       = $string;
                }	
        }
        $str_small      .= $high_fct;
}

$str_colors = '
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
for ($n = 1; $n <= 6; $n++) {
        $str_colors .= '
<td style = "width: 10%; text-align: center; border: 1px solid grey; color: '.$aqhi_color_array_us[$n]['color'].';  font-weight:bold; background-color: '.$aqhi_color_array_us[$n]['rgb'].'; ">'.$n.'</td>';
}
$str_colors .= '
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
<td colspan="6"><small>Forecast issued at: '.$issued.'</small></td></tr>'.PHP_EOL;

$explain        = '<table class="sortable genericTable" style="text-align: center; width: 100%; margin: 0 auto;">
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
</table>'.PHP_EOL;
if (isset ($aqhi_compact) && $aqhi_compact == false) {  // used on full page
        $str_max .= $str_colors.'</table></div>';
        $str_small.= $str_colors.'</table></div>';
        $show   = $java_hide_show.$str_out.$str_small. $str_max.'<script type="text/javascript"> aqhi_us_click() </script></div>';
       
} else {       // used on dashboard
        $str_max .= $str_colors.'</table><br /><div style="border: 1px solid grey;">'.$explain.'</div><br /></div>';
        $str_small.= $str_colors.'</table></div>';
        $show   = $java_hide_show.$str_out.$str_small. $str_max.'</div>';
}
echo $show.PHP_EOL.'<!-- end of output '.$wsaqhius.' -->'.PHP_EOL;
function check_aqhi_errors ($rawData) {
        return false;
} 
function aqhi_load_array_us() {
        global $aqhi_key_array_us,$aqhi_desc_array_us, $aqhi_color_array_us;
 
        $aqhi_key_array_us      = array ('0','0-50','51-100','101-150','151-200','201-300','301-500','>501','9999999');
        $aqhi_nr_array_us       = array (0,1,2,3,4,5,6,0);
        $aqhi_desc_array_us[]   = 'unknown';
        $aqhi_desc_array_us[]   = 'Good';
        $aqhi_desc_array_us[]   = 'Moderate';
        $aqhi_desc_array_us[]   = 'Unhealthy for Sensitive Groups';
        $aqhi_desc_array_us[]   = 'Unhealthy';
        $aqhi_desc_array_us[]   = 'Very Unhealthy';
        $aqhi_desc_array_us[]   = 'Hazardous';
        $aqhi_desc_array_us[]   = 'unkown / error in data';

        $aqhi_color_array_us[]  = array ('text' =>'white',      'rgb' => 'rgb(0,0,0)'     , 'color' => '#000');
        $aqhi_color_array_us[]  = array ('text' =>'Green',      'rgb' => 'rgb(0,228,0)'   , 'color' => '#000');
        $aqhi_color_array_us[]  = array ('text' =>'Yellow',     'rgb' => 'rgb(255,255,0)' , 'color' => '#000');
        $aqhi_color_array_us[]  = array ('text' =>'Orange',     'rgb' => 'rgb(255,126,0)' , 'color' => '#000');;
        $aqhi_color_array_us[]  = array ('text' =>'Red',        'rgb' => 'rgb(255,0,0)'   , 'color' => '#FFF');
        $aqhi_color_array_us[]  = array ('text' =>'Purple',     'rgb' => 'rgb(153,0,76)'  , 'color' => '#FFF');
        $aqhi_color_array_us[]  = array ('text' =>'Maroon',     'rgb' => 'rgb(76,0,38)'   , 'color' => '#FFF');
        $aqhi_color_array_us[]  = array ('text' =>'white',      'rgb' => 'rgb(0,0,0)'     , 'color' => '#FFF');
     
}//  eof aqhi_load_array_us
# ----------------------  version history
# 3.20 2015-09-10 release 2.8 version 
