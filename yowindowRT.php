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
$pageName		= 'yowindowRT.php';
$pageVersion	        = '3.10 2014-01.26';
$SITE['wsModules'][$pageName]   = 'version: ' . $pageVersion;
$pageFile = $realtimeWS = basename(__FILE__);	
#
$str_yowindowRT = $pageFile.' - '.$pageVersion;
#echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
include 'wsLoadSettings.php';
$realtime       = true;
$rtOut          = 'yowindowxml';
include $SITE['wsAjaxDataLoad'];
#
if (isset ($_GET['debug'])) {
        echo  '<pre>'.PHP_EOL.'$lang = '.$lang.PHP_EOL.'$ws returned from '.$SITE['wsAjaxDataLoad'].PHP_EOL; 
        print_r($ws);
}

if ($SITE['uomTemp'] == '&deg;C') {$yowin_temp = 'c';} else {$yowin_temp = 'f';}
# wind
$uom            = strtolower (trim($SITE['uomWind']) );
$from           = array (' ',   '&nbsp;','km/h','kts'   ,'m/s'  ,'mph'); 
$to             = array ('',    '',      'kph', 'knot'  ,'mps'  ,'mph' );
$yowin_wind     = str_replace ($from,  $to, $uom );
# pressure
$uom            = strtolower (trim($SITE['uomBaro']) );
$from           = array (' ',   '&nbsp;','hpa', 'mb',   'inhg'); 
$to             = array ('',    '',      'hpa', 'mbar', 'in');
$yowin_baro     = str_replace ($from,  $to, $uom);
# rain
$uom            = strtolower (trim($SITE['uomRain']) );
$from           = array (' ', '&nbsp;');
$yowin_rain     = str_replace ($from,  '', $uom );
if (trim($yowin_rain) == 'mm') {$yowin_rain = 'mm';} else {$yowin_rain = 'in';}
# distance
$uom            = strtolower (trim($SITE['uomDistance']) );
$from           = array (' ', '&nbsp;');
$yowin_dist       = str_replace ($from,  '', $uom );
if (trim($yowin_dist)  == 'km') {$yowin_dist = 'km';} else {$yowin_dist = 'mile';}
#
if (!isset ($SITE['yowin_time'])  ) {$SITE['yowin_time'] = 60;}
$string = '<?xml version="1.0" encoding="'.$SITE['charset'].'"?>
<!-- Leuven-Template  '.$SITE['WXsoftwareLongName'].' '.$ws['wsVersion'].' -->
<!-- YOWINDOW XML - PWS SPECS '.$str_yowindowRT.' -->
<response>
    <current_weather>
        <auto_update><interval value="'.$SITE['yowin_time'].'"/></auto_update> 
        <temperature unit="'.$yowin_temp.'">
                <current value="'.$ws['tempAct'].'"/>
        </temperature>
        <humidity value="'.$ws['humiAct'].'"/>
        <pressure value="'.$ws['baroAct'].'" unit="'.$yowin_baro.'"/>
        <wind>
                <speed value="'.$ws['windAct'].'" unit="'.$yowin_wind.'"/>
                <gusts value="'.$ws['gustAct'].'" unit="'.$yowin_wind.'"/>
                <direction value="'.$ws['windActDir'].'"/>
        </wind>
        <sky>
            <precipitation>
                  <rain>
                        <rate        value="'.$ws['rainRateAct'].'" unit="'.$yowin_rain.'"/>
                        <daily_total value="'.$ws['rainToday'].'" unit="'.$yowin_rain.'"/>
                  </rain>
            </precipitation>
        </sky>
        <uv value="'.$ws['uvAct'].'"/>
        <solar radiation="'.$ws['solarAct'].'" />
    </current_weather>
</response>';
if (!isset ($_GET['debug'])) {
        ob_clean();
        header("Content-Type: text/plain; charset=".$SITE['charset']."");
}
echo $string;
