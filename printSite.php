<?php  # ini_set('display_errors', 'On'); error_reporting(E_ALL); 
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

# ----------------------------------------------------------------------
if (!isset($SITE) ) { $SITE=array(); $insideTemplate = false; } else {$insideTemplate = true;}
# ----------------------------------------------------------------------
$pageName	= 'printSite.php';
$pageVersion	= '3.20.2015-09-10';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.20.2015-09-10 release version
# ----------------------------------------------------------------------
#  TO DO 
#-----------------------------------------------------------------------
$style_nr       = 'style =" float: left; color: gray; font-size: 13px; font-family: monospace; text-align: right; margin-right: 6pt; padding-right: 6pt;  border-right: 1px solid gray;"';
$style_code     = 'style =" white-space: nowrap;font-size: 13px; font-family: monospace; "';
$style_pre      = 'style =" border: 1px solid black;" ';
#-----------------------------------------------------------------------
if ($insideTemplate == false) 
     {  include_once 'wsLoadSettings.php';
# echo '<pre>'; print_r($SITE); exit;
        printSiteHtml();
        $ptext='';}
else {  $ptext='&amp;p='.$_REQUEST['p'].'';     // #data-area
        $page_title     = langtransstr('Display key values and files');
        $style_pre      = 'style =" border-top: 1px solid black; border-bottom: 1px solid black;" ';
        echo '<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'</h3>'.PHP_EOL;
        }
#
$passkey        = '';
if (!isset($SITE['password']) ||  trim($SITE['password']) == '' ) {
        $password       = true;
        $extraText      = '<i style="color: red;"> SettingsFiles should pe password protected</i>';}
else {  $password       = false;
        if (isset ($_REQUEST['pw']) )           {$passkey = trim($_REQUEST['pw']);}
        if (isset ($_REQUEST['password']) )     {$passkey = trim($_REQUEST['password']);}
        if ($passkey == trim($SITE['password']) ) {
                $password       = true;
                $extraText      = ' - protected ';
        } 
}
if (isset ($_REQUEST['wp']) ) {$wp = '&amp;wp='.$_REQUEST['wp'];} else {$wp = '';}
$ptext  .= '&amp;pw='.$passkey.$wp;
if (isset ($SITE['skipTop']) && $SITE['skipTop'])  {$ptext .= $skiptopText;}
#
if (function_exists ('highlight_file') ) {$doHighlight = true;} else {$doHighlight = false;}
#
echo '<div id="buttons" style="padding: 10px 0 0 0;">'.PHP_EOL;
                        echo '<a href="?start'.            $ptext.'"><button id="step1"  name="step1">First checks</button></a>'.PHP_EOL;
                        echo '<br /><br />'.PHP_EOL;
                        echo '<a href="?settings'.         $ptext.'"><button id="step2"  name="step2">Settings</button></a>'.PHP_EOL;
if ($doHighlight) {     echo '<a href="?settings&amp;high'.$ptext.'"><button id="step2h" name="step2h"><i style="background-color: yellow; color: red;">Settings</i></button></a>'.PHP_EOL;}
if ($insideTemplate) {  echo '<a href="?site'.             $ptext.'"><button id="step9"  name="step2">Site values</button></a>'.PHP_EOL;}
                        echo '<br /><br />'.PHP_EOL;
if ($SITE['wsTagsSrc'] <> '') {
                        echo '<a href="?compare'.          $ptext.'"><button id="step3"  name="step3">Weather-tags compared</button></a>'.PHP_EOL;
                        echo '<a href="?tagsrc'.           $ptext.'"><button id="step4"  name="step4">Weather-tags WX</button></a>'.PHP_EOL;
    if ($doHighlight) { echo '<a href="?tagsrc&amp;high'.  $ptext.'"><button id="step4h" name="step4h"><i style="background-color: yellow; color: red;">Weather-tags WX</i></button></a>'.PHP_EOL;}
}
                        echo '<a href="?tags'.             $ptext.'"><button id="step6"  name="step6">Weather-tags uploaded </button></a>'.PHP_EOL;
if ($doHighlight) {     echo '<a href="?tags&amp;high'.    $ptext.'"><button id="step7"  name="step7"><i style="background-color: yellow; color: red;">Weather-tags uploaded</i></button></a>'.PHP_EOL;}

if ($insideTemplate) {  echo '<a href="?values'.           $ptext.'"><button id="step8"  name="step8">All WX values</button></a>'.PHP_EOL;}

echo '</div>'.PHP_EOL;
# define tag files
$arr_tags[]     = $SITE['wsTags'];
$arr_srcs[]     = $SITE['wsTagsSrc'];
if (isset ($SITE['ydayTags']) && $SITE['ydayTags'] <> '' && $SITE['ydayTags'] <> 'no') {
        $arr_tags[]     = $SITE['ydayTags'];
        $arr_srcs[]     = $SITE['wsYTagsSrc'];
}
if (isset ($_REQUEST['high']))          {$doHighlight = true;} else {$doHighlight = false;}
$show           = '';
if (isset ($_REQUEST['tags']))          {$show   = 'tags';}
if (isset ($_REQUEST['tagsrc']))        {$show   = 'tagsrc';}
#
if (isset ($_REQUEST['settings']))      {$show   = 'settings';} 
if (!isset ($_REQUEST['settings']) &&  !isset ($_REQUEST['site']) ) {$extraText = '';}
#
switch ($show) {
    case 'tags':
        $arr_display    = $arr_tags;
    break;
    case 'tagsrc':
        $arr_display    = $arr_srcs;
    break;
    case 'settings':
        if ($password == false) {
                echo '<h3>Password needed and should be correct</h3>'.PHP_EOL;
        } else {
                $arr_display    = array ('_my_texts/wsUserSettings.php','wsLoadSettings.php');
                break;
        }
    default:
        $arr_display    = array();
}
#
$count  = count ($arr_display);
for ($i = 0; $i < $count; $i++) {
        $file_display   = $arr_display[$i];
        if(!file_exists($file_display) ) {
                echo '<h3>'.$file_display.' can not be found</h3>'.PHP_EOL;
                continue;
        }
        $file_time      = date ($SITE['timeFormat'],filemtime($file_display));
        echo '<a name=link'.$i.'></a><h3 style="margin: 10px;">'.$file_display.$extraText.' - dated: '.$file_time.'</h4>'.PHP_EOL;
        if ($count > 1) {
                if ($i < ($count - 1) ) {  // style="padding: 10px 0 10px 0;"
                        echo '<div  style="padding: 10px 0 10px 0;"><a href=#link'.($i+1).'><button><b>=> </b>'.$arr_display[$i+1].'</button></a></div>'.PHP_EOL;
                } else {echo '<div  style="padding: 10px 0 10px 0;"><a href=#link'.($i-1).'><button><b>=> </b>'.$arr_display[$i-1].'</button></a></div>'.PHP_EOL;
                }
        }
	echo '<pre '.$style_pre.'>'.PHP_EOL;
        if($doHighlight) { 
                $lines  = implode(range(1, count(file($file_display))), '<br />'); 
                $content= highlight_file($file_display, true); 
                echo '<table><tr><td '.$style_nr.'>'.$lines.'</td><td '.$style_code.'>'.$content.'</td></tr></table>';
        } else {      
                $flines = file($file_display);
                for($n = 0; $n < count($flines); $n++) {
                        $line   = str_replace('<','&lt;',$flines[$n]);
                        $linenr = sprintf('%5d',$n+1);
                        echo $linenr.' | '.$line;
                }
        }  
        echo '</pre>'.PHP_EOL;
} // eo for each arr_display
if ($show <> '') {
        if ($insideTemplate == false)  {printSiteHtmlEnd();}  else {echo '<br /></div>';}
        return;
}
# -----------
if (isset ($_REQUEST['compare'])) {
        $count  = count($arr_srcs);
        for ($i = 0; $i < $count; $i++) {   
                $srcsLines     = file($arr_srcs[$i]); 
                $pos    = strpos ($arr_srcs[$i] , 'cron.txt');
                if ($pos) {
                        $cron = true; 
                } 
                else {  $cron = false;
                        $empty_line = 'src-line not found';
                }
                if ($SITE['WXsoftware'] == 'WS' )   {array_shift($srcsLines);}
                $tagsLines      = file($arr_tags[$i]);
                if ($SITE['WXsoftware'] == 'WV') {
                        $arr    = array();
                        for ($n = 0; $n < count($srcsLines); $n++) {
                                if (trim($srcsLines[$n]) <> '') {$arr[] = $srcsLines[$n];}
                        }
                        $srcsLines      = $arr;
                        $arr    = array();
                        for ($n = 0; $n < count($tagsLines); $n++) {
                                if (trim($tagsLines[$n]) <> '') {$arr[] = $tagsLines[$n];}
                        }
                        $tagsLines      = $arr;                              
                }
                $file_time      = date ($SITE['timeFormat'],filemtime($arr_tags[$i]));
                echo '<a name=link'.$i.'><h3 style="margin: 10px;">';
                if (!$cron) {echo $arr_srcs[$i].' compared to ';}
                echo $arr_tags[$i].' - dated: '.$file_time.'</h4>'.PHP_EOL;
                if ($count > 1) {
                        if ($i < ($count - 1) ) {
                                echo '<div  style="padding: 10px 0 10px 0;"><a href=#link'.($i+1).'><button><b>=> </b>'.$arr_srcs[$i+1].'</button></a></div>'.PHP_EOL;
                        } else {echo '<div  style="padding: 10px 0 10px 0;"><a href=#link'.($i-1).'><button><b>=> </b>'.$arr_srcs[$i-1].'</button></a></div>'.PHP_EOL;}
                }
                echo '<pre '.$style_pre.'>'.PHP_EOL;
                $no_nr          = '     ';
                $count_lines    = count($srcsLines);
                if (count($tagsLines) > $count_lines) {$count_lines = count($tagsLines);}
                for($n = 0; $n < $count_lines; $n++) {
                        if (isset ($tagsLines[$n]) ) { 
                                $tag_line       = trim($tagsLines[$n]); } 
                        else  { $tag_line       = 'line not found'; } 
                        if (isset ($srcsLines[$n]) ) { 
                                $src_line        = trim($srcsLines[$n]); }
                        elseif ($cron) {
                                $src_line       = $tag_line; }
                        else  { $src_line       = $empty_line; }           
                        $src_line       = str_replace('<','&lt;',$src_line);
                        $tag_line       = str_replace('<','&lt;',$tag_line);
                        $linenr         = sprintf('%5d',$n+1);
                        for ($c = 0; $c < strlen($src_line); $c++) {
                                if (substr ($src_line,$c,1) <> substr ($tag_line,$c,1) ){
                                        $next_part      = substr($tag_line,$c);
                                        $first_part     = '<span style="color: #D0D0D0;">'.substr($tag_line,0,$c).'</span>';
                                        $start_src      = strlen($src_line);
                                        $start_tag      = strlen($next_part);
                                        
                                        if ($start_src < $start_tag) {$end = $start_src;} else {$end = $start_tag;}
                                        $last_part      = '';
                                        for ($c = 0; $c <= $end; $c++) {
                                                if (substr($src_line, $start_src-$c,1) <> substr($next_part,$start_tag-$c,1)) {
                                                        $last_part      = substr($tag_line,-$c+1);
                                                        $next_part      = substr($next_part,0,$start_tag-$c+1);
          /*                                   echo PHP_EOL.PHP_EOL.$src_line.'<-'.PHP_EOL;
                                             echo $next_part.'<-'.PHP_EOL;
                                             
                                             echo '$c ='.$c.' $last_part = '.$last_part.' - $next_part = '.$next_part; exit;
                                             
         */                                               break;           
                                                }
                                        } 
                                        $tag_line       = $first_part.
                                                        '<span style="color: red";>'.$next_part.'</span>'.
                                                        '<span style="color: #D0D0D0;">'.$last_part.'</span>';
                                        break;
                                }
                        } 
                        echo $linenr.' |&nbsp;'.$src_line.PHP_EOL;
                        if ($src_line <> $tag_line) {
                                echo $no_nr .' |&nbsp;'.$tag_line.PHP_EOL;
                        }
                }
                echo '</pre>'.PHP_EOL;     
        }
        if ($insideTemplate == false)  {printSiteHtmlEnd();}  else {echo '<br /></div>';}
        return;
}
# -----------
if (isset ($_REQUEST['values']))  {
        echo '<h3 style="margin: 10px;">&nbsp;&nbsp;These are the converted tags as stored in the <i>$ws</i> array</h3>'.PHP_EOL;
        print_values ($ws);
        if (isset ($ws['trendsExist']) ) {
                $wsTrends = unserialize(file_get_contents($ws['trendsExist']));
# echo '<pre>'; print_r ($wsTrends); exit;
                echo '<h3 style="margin: 10px;">&nbsp;&nbsp;These are the converted tags for the trend page stored in the <i>$wsTrends</i> array</h3>'.PHP_EOL;
                print_values ($wsTrends);
        }
        if ($insideTemplate == false)  {printSiteHtmlEnd();}  else {echo '<br /></div>';}
        return;
}
# -----------
if (isset ($_REQUEST['site']))  {
        if ($password == false) {
                echo '<h3>Password needed and should be correct</h3>'.PHP_EOL;
                return;
        }
        echo '<h3 style="margin: 10px;">&nbsp;&nbsp;These are the converted settings as stored in the <i>$SITE</i> array. '.$extraText.'</h3>'.PHP_EOL;
        print_values ($SITE);
        if ($insideTemplate == false)  {printSiteHtmlEnd();}  else {echo '<br /></div>';}
        return;
}
# -----------
# here we display key information and links
$left   = '<tr><td style="width: 20%; text-align: right;">';
$divide = '</td><td style="border-left: 1px solid black;">&nbsp;';
$right  = '</td></tr>'.PHP_EOL;
echo '<br />'.PHP_EOL;
if ($insideTemplate == false) {
        echo '<table style="width: 100%; border: 1px solid black; border-collapse: collapse;">';} 
else {  echo '<table style="width: 100%; border-top: 1px solid black; border-bottom: 1px solid black; border-collapse: collapse;">';} 
#echo '<tr><th style="text-align: right;">checked for</th><th style="border-left: 1px solid black; text-align: left;">&nbsp;result</th></tr>'.PHP_EOL;
echo '<tr><td colspan="2" style="background-color:#D0D0D0;">Website and PHP key information</td></tr>'.PHP_EOL;
echo $left.'Webserver OS'       .$divide.php_uname()    .$right;
echo $left.'PHP Version'        .$divide.phpversion()    .$right;
echo $left.'Document root'      .$divide.$_SERVER['DOCUMENT_ROOT'] .$right;
$arr_ini_get    = array ('allow_url_fopen','allow_url_include');
for ($i = 0; $i < count($arr_ini_get); $i++) {
        $string         = ini_get($arr_ini_get[$i])?'on':'off';
        echo $left.$arr_ini_get[$i].$divide.$string.$right;
}

echo '<tr><td colspan="2" style="background-color:#D0D0D0;">Needed built-in PHP classes / functions</td></tr>'.PHP_EOL;
$arr_class_check  = array('SimpleXMLElement');
for ($i = 0; $i < count($arr_class_check); $i++) {
        $key    = $arr_class_check[$i];
        $string = class_exists($key)?'ok':'<i style="color: red;">off</i>';
        echo $left.$key.$divide.$string.$right;
}
$arr_fun_check  = array('iconv','json_decode','curl_init','curl_setopt','curl_exec','curl_error','curl_close','highlight_file');
for ($i = 0; $i < count($arr_fun_check); $i++) {
        $key    = $arr_fun_check[$i];
        $string = function_exists($key)?'ok':'<i style="color: red;">off</i>';
        echo $left.$key.$divide.$string.$right;
}
#print_r (gd_info());
if (function_exists ('gd_info') ) {$arr_gd = gd_info();} else {$arr_gd = array();}
echo '<tr><td colspan="2" style="background-color:#D0D0D0;">GD support availability</td></tr>'.PHP_EOL;
if (!isset ($arr_gd ['GD Version']) ) {
        echo $left.'ERROR'.$divide.'<span style="color: red;">Needed GD support seems not to be present</span>'.$right;
}  
$arr_skip       = array ('WBMP Support','XPM Support','XBM Support','JIS-mapped Japanese Font Support'); 
foreach ($arr_gd as $key => $value) {
        if  (in_array ($key,$arr_skip) ) {continue;}
        if ($value === true) {$value = 'ok';}
        if ($value === false) {$value = '<i style="color: red;">not set</i>';}
        echo $left.$key.$divide.$value.$right;
}
echo '<tr><td colspan="2" style="background-color:#D0D0D0;">Template settings</td></tr>'.PHP_EOL;
$arr_settings           = array('userChangeDebug','userChangeLang','mobileSite','region','WXsoftware');
for ($i = 0; $i < count($arr_settings); $i++) {
        $key    = $arr_settings[$i];
        if ($SITE[$key] === true) {$string = 'true';} else {$string = (string) $SITE[$key];}
        echo $left.$key.$divide.$string.$right;
}
echo '<tr><td colspan="2" style="background-color:#D0D0D0;">Template free forecasts used</td></tr>'.PHP_EOL;

$arr_free_pages         = array('wxsimPage','metnoPage','yrnoPage','yahooPage');
for ($i = 0; $i < count($arr_free_pages); $i++) {
        $key    = $arr_free_pages[$i];
        if ($SITE[$key] === true) {$string = 'true';} else {$string = (string) $SITE[$key];}
        echo $left.$key.$divide.$string.$right;
}
echo '<tr><td colspan="2" style="background-color:#D0D0D0;">Template key-based forecast used</td></tr>'.PHP_EOL;
$arr_key_pages          = array('wuPage','hwaPage','worldPage','ewnID');
for ($i = 0; $i < count($arr_key_pages); $i++) {
        $key    = $arr_key_pages[$i];
        if      ($SITE[$key] === true)  {$string = 'true';} 
        elseif  ($SITE[$key] === false) {$string = 'false';} 
        else                            {$string = (string) $SITE[$key];}
        echo $left.$key.$divide.$string.$right;
}

echo '<tr><td colspan="1" style="background-color:#D0D0D0;">Check data-load.
</td><td style="border-left: 1px solid black; background-color:#D0D0D0;">&nbsp;Clicking on the link will retrieve the information based on your settings </td></tr>'.PHP_EOL;
$arr_links	= array();
if ($SITE['region'] == 'europe' && $SITE["warnings"] == true) {
        $arr_links[]	= array('link' 	=> 'http://www.meteoalarm.eu/en_UK/0/0/'.$SITE['warnArea'].'.html',
		'txt'	=> 'EU weather warning');
}
if ($SITE['curCondFrom'] == 'metar') {
         $arr_links[]	= array('link' 	=> 'http://weather.noaa.gov/pub/data/observations/metar/stations/'. $SITE['METAR'].'.TXT',
		'txt'	=> 'Metar current conditions');
} 
if ($SITE['yahooPage'] == 'yes' || $SITE['yahooPage'] == true || $SITE["curCondFrom"] == "yahoo") {
        $text   = 'Yahoo current conditions &amp; forecast';
        if ($SITE['yahooPage'] <> 'yes' && $SITE['yahooPage'] <> true) {$text   = 'Yahoo current conditions';}
        elseif ($SITE["curCondFrom"] <> "yahoo") {$text   = 'Yahoo current forecast';}
        $arr_links[]	= array('link' 	=> 'http://weather.yahooapis.com/forecastrss?w='.$SITE['yaPlaceID'].'&amp;u=c',
		'txt'	=> $text);
} 
if ($SITE['ecPage'] === true || ($SITE["warnings"] == true && $SITE["region"] == "canada")) {
        $arr_links[]	= array('link' 	=> 'http://dd.meteo.gc.ca/citypage_weather/xml/'.$SITE["caProvince"].'/'.$SITE["caCityCode"].'_e.xml',
		'txt'	=> 'EC forecast and warnings');
}
if ($SITE['metnoPage'] === 'yes' ||  $SITE['metnoPage'] === true) {
         $arr_links[]	= array('link' 	=> 'http://api.met.no/weatherapi/locationforecast/1.9/?lat='.$SITE['latitude'].';lon='.$SITE['longitude'],
		'txt'	=> 'Metno forecast');
}
if ($SITE['yrnoPage'] === 'yes' ||  $SITE['yrnoPage'] === true) {
         $arr_links[]	= array('link' 	=> 'http://www.yr.no/place/'.$SITE['yrnoID'].'varsel.xml',
		'txt'	=> 'Yrno forecast');
}
if (isset ($SITE['wuID']) && $SITE['wuID'] <> '' && $SITE['wuID'] <> false && $SITE['wuID'] <> 'no') {
         $year = date('Y');
         $arr_links[]	= array('link' 	=> 'http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$SITE['wuID'].'&month=01&day=01&year='.$year.'&format=1&graphspan=year',
		'txt'	=> 'WU stations data');
}
for ($i = 0; $i < count($arr_links); $i++) {
        $key    = $arr_links[$i]['txt'];
        $link   = $text = $arr_links[$i]['link'];
        if (strlen($link) > 80 ) {$text = substr($link,0,80).' . . . ';}
        $string = '<a target="_blank" href="'.$link.'">'.$text.'</a>';
        echo $left.$key.$divide.$string.$right;
}
$arr_links      = array();
if ($SITE['WXsoftware'] === 'DW') {  # http://www.weatherlink.com/xml.php?user='.$SITE['wlink_key'].'&pass='.$SITE['wlink_pw']
        if ($password == true) { $dwkey  = $SITE['wlink_pw']; } else {$dwkey  = '_password_needed_';}
        $arr_links[]	= array('link' 	=> 'http://www.weatherlink.com/xml.php?user='.$SITE['wlink_key'].'&pass='.$dwkey,
		'txt'	=> 'WeatherLink.com weatherdata');
}

if ($SITE['wuPage'] === 'yes' ||  $SITE['wuPage'] === true) {
        if ($password == true) { $wukey  = $SITE['wuKey']; } else {$wukey  = '_password_needed_';}
        $arr_links[]	= array('link' 	=> 'http://api.wunderground.com/api/'.$wukey.'/conditions/forecast10day/lang:EN/q/'.$SITE['latitude'].','.$SITE['longitude'].'.xml',
		'txt'	=> 'WeatherUnderground forecast');
}
if ($SITE['hwaPage'] === 'yes' || $SITE['hwaPage'] === true) {
        if ($password == true) { 
                $hwaid  = $SITE['hwaXmlId']; 
                $hwakey = $SITE['hwaXmlKey'];
        } else {$hwakey = '_password_needed_';
                $hwaid  = '___';
        }
        $arr_links[]	= array('link' 	=> 'http://www.hetweeractueel.nl/includes/custom/mosfeed.php?id='.$hwaid.'&amp;securitycode='.$hwakey.'&amp;extended=1',
		'txt'	=> 'Het Weer Actueel forecast');
}
if ($SITE['worldPage'] === 'yes' ||  $SITE['worldPage'] === true) {
        if (! isset($SITE['worldAPI']) ) {$SITE['worldAPI'] = 1;}
        if ($SITE['worldAPI'] == 1) {
                if ($password == true) { $worldkey  = $SITE['worldKey']; } else {$worldkey  = '_password_needed_';}
                $arr_links[]	= array('link' 	=> 'http://api.worldweatheronline.com/free/v1/weather.ashx?q='.$SITE['latitude'].','.$SITE['longitude'].'&format=xml&num_of_days=5&key='.$worldkey,'txt'=> 'World Weather forecast');
        } else {if ($password == true) { $worldkey  = $SITE['worldKey2']; } else {$worldkey  = '_password_needed_';}
                $arr_links[]	= array('link' 	=> 'http://api.worldweatheronline.com/free/v2/weather.ashx?q='.$SITE['latitude'].','.$SITE['longitude'].'&format=xml&num_of_days=5&key='.$worldkey,'txt'=> 'World Weather forecast');
        }

}
// http://api.worldweatheronline.com/free/v1/weather.ashx?q=41.30068,-72.793671&format=xml&num_of_days=8&key=egdt5rnspg8ypk8zuwpnnbw8
echo '<tr><td colspan="1" style="background-color:#D0D0D0;">Check data-load.
</td><td style="border-left: 1px solid black; background-color:#D0D0D0;">&nbsp;Clicking on the link will retrieve the information based on your settings.  <b>password needed to execute the links</b> </td></tr>'.PHP_EOL;
for ($i = 0; $i < count($arr_links); $i++) {
        $key    = $arr_links[$i]['txt'];
        $link   = $text = $arr_links[$i]['link'];
        if (strlen($link) > 80 ) {$text = substr($link,0,80).' . . . ';}
        $string = '<a target="_blank" href="'.$link.'">'.$text.'</a>';
        echo $left.$key.$divide.$string.$right;
}

$arr_dirs       = array ($SITE['cacheDir'],$SITE['uploadDir']);
echo '<tr><td colspan="2" style="background-color:#D0D0D0;">Checking directories / folders for write permissions</td></tr>'.PHP_EOL;
for ($i = 0; $i < count($arr_dirs); $i++) {
	$path 	= $arr_dirs[$i];
	$file	= $path.'printsite3_test.txt';  // file with userdata
	$status = ' try to write file - '.$file.': ';
	$now	= date($SITE['timeFormat'], time());
	$string = 'testing for weatherprogram '.$SITE['WXsoftware'].' writing to '.$file.' at '.$now;
	$do_delete      = true;
	$ret	= file_put_contents ($file, $string);
	if ($ret)   {
	        $status .= ' writable'.PHP_EOL;
	}
	else  { $status .= ' not writable, trying to set write permissions - ';
	        chmod($path, 0777);
	        $ret	= file_put_contents ($file, 'second-time '.$string);
	        if ($ret) {
	                $status .= ' now writable'.PHP_EOL;
	        }
                else  { $status .= ' write permissions could not be set, fatal error'.PHP_EOL;
                        $do_delete = false;
                        $text   = '<i style="color: red;">directory needs to get proper permissions</i>: ';
	        }
	}
	if ($do_delete) {
	        $deleted = unlink($file);
		$status .= $deleted?'- then deleted the test file.':'- unable to delete '.$file;
		$text   = $deleted?'<b>OK</b>: ':'<i style="color: red;">directory needs to get proper permissions</i>: ';
	}
        echo $left.$path.$divide.$text.$status.$right;
}
echo '</table>'.PHP_EOL;
if ($insideTemplate == false)  {printSiteHtmlEnd();}  else {echo '<br /></div>';}

#-----------------------------------------------------------------------
function process_array ($value) {
        echo '<table class="" style="border: none;">'.PHP_EOL;
        foreach ($value as $key2 => $value2) {
                echo '<tr style = ""><td>'.$key2.' =></td><td>';
                if (is_array($value2) ) {process_array ($value2);} 
                elseif ($value2 === '') {echo $value2; }
                elseif ($value2 === '0' || $value2 === '1') {echo $value2; }
                elseif ($value2 === false) {echo 'false'; }
                elseif ($value2 === true)  {echo 'true'; }
                else {  $from   = array ('&',' ');
                        $to     = array ('&amp','&nbsp;');
                        echo "'".str_replace($from,$to,$value2)."'"; 
                }
                echo '</td></tr>'.PHP_EOL;
        }
        echo '</table>'.PHP_EOL;
} // eo function
#
function print_values ($array) {
        echo '<table  class="" style = "border: 1px solid black; width: 98%; margin: 0 auto; font-family: monospace; border-collapse: collapse;">'.PHP_EOL;
        if (defined ('SORT_NATURAL') && defined ('SORT_FLAG_CASE') ) 
             {  ksort($array,SORT_NATURAL | SORT_FLAG_CASE); }
        else {  ksort($array); }
        foreach ($array as $key => $value) {        
                echo '<tr style = "border: solid 1px; "><td style="border-right: solid 1px; ">'.$key.'</td><td style="text-align: left;">';
                if (is_array($value) ) {process_array ($value);}  
                elseif ($value === '') {echo $value; }
                elseif ($value === '0' || $value === '1') {echo $value; }
                elseif ($value === false) {echo 'false'; }
                elseif ($value === true)  {echo 'true'; }
#                else {echo str_replace('&','&amp;',$value); }
                else {  $from   = array ('&',' ');
                        $to     = array ('&amp','&nbsp;');
                        echo "'".str_replace($from,$to,$value)."'"; 
                }
        }
        echo '</table>'.PHP_EOL;
}
#
function printSiteHtml(){
        global $SITE;
        echo '<!DOCTYPE html>
<html lang="'.$SITE['lang'].'">
<head>
	<meta charset="'.$SITE['charset'].'"/>
	<meta name="description" content="Print utility Leuven Template" />
        <title>Print utility Leuven Template</title>
        <style>
*{
	margin: 0;
}
        </style>
</head>
<body style="background-color:#FFFFFF; font-family:Arial, Helvetica, sans-serif;font-size: 10pt;">
<!-- page wrapper -->
';
}
#
function printSiteHtmlEnd(){
        echo '<br />
</body>
</html>';
        exit;
}
# ----------------------  version history
# 3.20 2015-09-10 release 2.8 version 