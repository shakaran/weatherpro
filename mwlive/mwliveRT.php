<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
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
if (isset ($_GET['debug'])) {ini_set('display_errors', 'On'); error_reporting(E_ALL);	} else { ob_start();}
#
$pageName		= 'mwliveRT.php';
$pageVersion	        = '3.10 2015-01-27';
$SITE['wsModules'][$pageName]   = 'version: ' . $pageVersion;
$pageFile = $realtimeWS = basename(__FILE__);	
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName.' - '.$pageVersion;}

$text_xml = '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->";
#
$folderdown		= '../';  // from how many folders up is this script called (mwlive)
chdir ($folderdown);

include 'wsLoadSettings.php';
$realtime       = true;
$rtOut          = 'mwxml';
include $SITE['wsAjaxDataLoad'];


ob_start();
echo '<!-- <pre> 
$lang = '.$lang.'
$ws returned 
'; print_r($ws); echo ' -->';
$string = '<?xml version="1.0" encoding="'.$SITE['charset'].'"?>
<!-- mwlive XML version: 3.10 2015-01-27 -->
'.$text_xml.'
<wx>
  <header>
        <label>Station: '.$SITE['organ'].'</label>
 <!--        <datetime>'.langtransstr('Live weatherdata from').' Meteoware</datetime>	-->
        <load>'.langtransstr('Actual data is loaded . . .').'</load>
        <lastrefresh>'.langtransstr('Actual data from:').'</lastrefresh>
        <locale>en</locale>			
  </header>';
if ($SITE['uomTemp'] == '&deg;C') 
       {$uabbr = '°C'; 
        $temptext = langtransstr('Celsius');
} else {$uabbr = '°F';
        $temptext = langtransstr('Fahrenheit');
}
$limit_low      = (string)wsConvertTemperature('-10', 'c');
$limit_low      = round(5* (floor($limit_low / 5) ));
$limit_high     = (string)wsConvertTemperature('35', 'c');
$limit_high     = round(5* (floor($limit_high / 5) ));
if ($ws['tempMinYear'] < $limit_low) {$limit_low = 5* (floor($ws['tempMinYear'] / 5) );}
if ($ws['dewpMinToday'] < $limit_low) {$limit_low = 5* (floor($ws['dewpMinToday'] / 5) );}
if ($ws['chilMinToday'] < $limit_low) {$limit_low = 5* (floor($ws['chilMinToday'] / 5) );}

if ($ws['tempMaxYear'] > $limit_high) {$limit_high = 5* (ceil($ws['tempMaxYear'] / 5) );}
if ($ws['dewpMaxToday'] > $limit_high) {$limit_high = 5* (ceil($ws['dewpMaxToday'] / 5) );} 
 
$string .= '
  <temperature unit="'.$temptext.'" label="'.langtransstr('Temperature').'">
        <min dt="'.WStime($ws['tempMinTodayTime']).'" uabbr="'.$uabbr.'" label="'.langtransstr('Lowest').'">'.$ws['tempMinToday'].'</min>
        <max dt="'.WStime($ws['tempMaxTodayTime']).'" uabbr="'.$uabbr.'" label="'.langtransstr('Highest').'">'.$ws['tempMaxToday'].'</max>
        <current dt="'.WStime($ws['actTime']).'">'.$ws['tempAct'].'</current>
        <limits min="'.$limit_low.'" max="'.$limit_high.'"> </limits>		
  </temperature>'; 
$string .= '	
  <dewpoint unit="'.$temptext.'" label="'.langtransstr('Dewpoint').'">
        <min dt="'.WStime($ws['dewpMinTodayTime']).'" uabbr="'.$uabbr.'" label="'.langtransstr('Lowest').'">'.$ws['dewpMinToday'].'</min>
        <max dt="'.WStime($ws['dewpMaxTodayTime']).'" uabbr="'.$uabbr.'" label="'.langtransstr('Highest').'">'.$ws['dewpMaxToday'].'</max>
        <current dt="'.WStime($ws['actTime']).'">'.$ws['dewpAct'].'</current>
       <limits min="'.$limit_low.'" max="'.$limit_high.'"> </limits>		
  </dewpoint>';
 
$string .= '	
  <windchill unit="'.$temptext.'" label="'.langtransstr('Wind Chill').'">
        <min dt="'.WStime($ws['chilMinTodayTime']).'" uabbr="'.$uabbr.'" label="'.langtransstr('Lowest ').'">'.$ws['chilMinToday'].'</min>';
if (isset ($ws['chilMaxTodayTime']) ) {
        $string .= '         
        <max dt="'.WStime($ws['chilMaxTodayTime']).'" uabbr="'.$uabbr.'" label="'.langtransstr('Highest ').'">'.$ws['chilMaxToday'].'</max>';
}
$string .= '        
        <current dt="'.WStime($ws['actTime']).'">'.$ws['chilAct'].'</current>
        <limits min="'.$limit_low.'" max="'.$limit_high.'"> </limits>		
  </windchill>'; 
$limit_low      = (string)wsConvertBaro('950', 'hpa');
$limit_low      = round(5* (floor($limit_low / 5) ),1);
$limit_high     = (string)wsConvertBaro('1050', 'hpa');
$limit_high     = round(5* (floor($limit_high / 5) ),1);
  
$string .= ' 
  <pressure unit="'.$uomBaro.'" label="'.langtransstr('Pressure').'">
        <min dt="'.WStime($ws['baroMinTodayTime']).'" uabbr=" hpa" label="'.langtransstr('Lowest ').'">'.$ws['baroMinToday'].'</min>
        <max dt="'.WStime($ws['baroMaxTodayTime']).'" uabbr=" hpa" label="'.langtransstr('Highest ').'">'.$ws['baroMaxToday'].'</max>
        <current dt="'.WStime($ws['actTime']).'">'.$ws['baroAct'].'</current>
        <limits min="'.$limit_low.'" max="'.$limit_high.'"> </limits>
  </pressure>';
$string .= '		
  <humidity unit="%" label="'.langtransstr('Humidity').'">
        <min dt="'.WStime($ws['humiMinTodayTime']).'" uabbr="%" label="'.langtransstr('Lowest').'">'.$ws['humiMinToday'].'</min>
        <max dt="'.WStime($ws['humiMaxTodayTime']).'" uabbr="%" label="'.langtransstr('Highest').'">'.$ws['humiMaxToday'].'</max>
        <current dt="'.WStime($ws['actTime']).'">'.$ws['humiAct'].'</current>
  </humidity>';

$limit_high     = (string)wsConvertRainfall('50', 'hpa');
if ($ws['rainMonth'] > $limit_high) {$limit_high = 5* (ceil($ws['rainMonth'] / 5) );} 
$limit_high     = round(5* (floor($limit_high / 5) ),1);

if (!isset ($ws['rainHour']) ) {$ws['rainHour'] = 0;}
$string .= '
  <precipitation unit="'.$uomRain.'" label="'.langtransstr('Precipitation').'">  
        <current    dt="'.WStime($ws['actTime']).'"  uabbr="'.$uomRain.'" label="'.langtransstr('Today').'">'.$ws['rainToday'].'</current>
        <interval1h dt="1 '.langtransstr('Hour').'" uabbr="'.$uomRain.'" label="'.langtransstr('Last hour').'">'.$ws['rainHour'].'</interval1h>
        <interval6h dt="'.langtransstr('Month').'"  uabbr="'.$uomRain.'" label="'.langtransstr('This month').'">'.$ws['rainMonth'].'</interval6h>
        <limits min="0" max="'.$limit_high.'"> </limits>
  </precipitation>';
$string .= '	
  <winddirection unit="°" label="'.langtransstr('Wind').'">
        <current dt="'.WStime($ws['actTime']).'" uabbr="°;" label="Windrichting">240</current>
  </winddirection>		
  <windspeed unit="'.$uomWind.'" label="'.langtransstr('Wind').'">
        <current dt="'.WStime($ws['actTime']).'" uabbr="'.$uomWind.'" label="'.langtransstr('Wind').'">'.$ws['windAct'].'</current>
        <peak dt="" uabbr="'.$uomWind.'" label="'.langtransstr('Highest').' '.langtransstr('Gust').'">'.$ws['gustMaxToday'].'</peak>
        <limits min="0" max="50"> </limits>
  </windspeed>		
</wx>';
#ob_flush(); 
ob_clean(); 
echo $string;
