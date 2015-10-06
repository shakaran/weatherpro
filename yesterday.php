<?php ini_set('display_errors', 'On');   error_reporting(E_ALL);
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header('Content-type: text/plain');
   header('Accept-Ranges: bytes');
   header('Content-Length: $download_size');
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$SITE		        = array();
#
$pageNameYday		= 'yesterday.php';
$pageVersionYday	= '3.02 2015-04-20';
# ------------------------------------------------------------------------------
# 3.02 2015-04-20 release 2.7 beta
# ------------------------------------------------------------------------------
include         'wsLoadSettings.php';
if (isset ($SITE['password']) ) 
        { $pass  = trim($SITE['password']); } 
else    { $pass = ''; }
if (isset ($SITE['wsDebug'])  && $SITE['wsDebug'] == true) 
        { $debug = true; } 
else    { $debug = false; } 
if (!isset ($_REQUEST['pw']) )
        { $pw   = ''; }
else    { $pw   = trim($_REQUEST['pw']); }
$message = '';
if (!$debug) {  // check if password is used to run cronjob
        if ($pass <> $pw) 
                { echo 'ERROR invalid call to script'; exit;}
        if ($pass == '') 
                { $message      .= '<br />WARNING  Settings should be password protected '; }
} else {if ($pass <> $pw) 
                { $message      .= '<br />WARNING  passwords do not match ';}
        if ($pass == '') 
                { $message      .= '<br />WARNING  Settings should be password protected '; }
}
$realtime       = true;
$rtOut          = 'none';
include         $SITE['wsAjaxDataLoad'];
#
# set default values non exisitng sensors
#
if (!$SITE['UV'] == true) {
        $ws['uvMaxToday']       = '--';
        $ws['uvMaxTodayTime']   = $ws['actTime'];
}
if (!$SITE['SOLAR'] == true) {
        $ws['solarMaxToday']    = '--';
        $ws['solarMaxTodayTime']= $ws['actTime'];
}
if ( isset($_GET['debug']) ) {echo '<pre>'.PHP_EOL; print_r($ws);}  
$string = '# generated at '.date($SITE['timeFormat'].' e',time()).'
|versionYday|'.$pageVersionYday.'|!
|pagenameYday|'.$pageNameYday.'|!
|fromtempYday|'.$SITE['uomTemp'].'|!
|datetimeYday|'.$ws['actTime'].'|!
|tempMinYday|'.         $ws['tempMinToday'].'|!
|tempMinYdayTime|'.     $ws['tempMinTodayTime'].'|!
|tempMaxYday|'.         $ws['tempMaxToday'].'|!
|tempMaxYdayTime|'.     $ws['tempMaxTodayTime'].'|!
|dewpMinYday|'.         $ws['dewpMinToday'].'|!
|dewpMinYdayTime|'.     $ws['tempMinTodayTime'].'|!
|dewpMaxYday|'.         $ws['dewpMaxToday'].'|!
|dewpMaxYdayTime|'.     $ws['dewpMaxTodayTime'].'|!
|chilMinYday|'.         $ws['chilMinToday'].'|!
|chilMinYdayTime|'.     $ws['chilMinTodayTime'].'|!
|heatMaxYday|'.         $ws['heatMaxToday'].'|!

|frombaroYday|'.$SITE['uomBaro'].'|!
|baroMinYday|'.         $ws['baroMinToday'].'|!
|baroMinYdayTime|'.     $ws['baroMinTodayTime'].'|!
|baroMaxYday|'.         $ws['baroMaxToday'].'|!
|baroMaxYdayTime|'.     $ws['baroMaxTodayTime'].'|!
|humiMinYday|'.         $ws['humiMinToday'].'|!
|humiMinYdayTime|'.     $ws['humiMinTodayTime'].'|!
|humiMaxYday|'.         $ws['humiMaxToday'].'|!
|humiMaxYdayTime|'.     $ws['humiMaxTodayTime'].'|!
|fromrainYday|'.$SITE['uomRain'].'|!
|rainRateYday|'.        $ws['rainRateToday'].'|!
|rainYday|'.            $ws['rainToday'].'|!
|etYday|'.              $ws['etToday'].'|!
|fromwindYday|'.$SITE['uomWind'].'|!
|gustMaxYday|'.         $ws['gustMaxToday'].'|!
|gustMaxYdayTime|'.     $ws['gustMaxTodayTime'].'|!
|uvMaxYday|'.           $ws['uvMaxToday'].'|!
|uvMaxYdayTime|'.       $ws['uvMaxTodayTime'].'|!
|solarMaxYday|'.        $ws['solarMaxToday'].'|!
|solarMaxYdayTime|'.    $ws['solarMaxTodayTime'].'|!
';
if (isset ( $ws['heatMaxTodayTime']) ) {$string .='|heatMaxYdayTime|'.     $ws['heatMaxTodayTime'].'|!
';
}
if (isset ($SITE['soilUsed']) && $SITE['soilUsed'] && $SITE['soilCount']*1.0 > 0) {
        if ($SITE['soilCount']*1.0 > 4) {$count = 4;} else {$count = $SITE['soilCount'];}
        for ($i = 1; $i <= $count; $i++) {
                $string .= '|soilTempMaxYday_'.$i.'|'.      $ws['soilTempMaxToday'][$i].'|!
|soilTempMinYday_'.$i.'|'.      $ws['soilTempMinToday'][$i].'|!
|soilTempMaxYdayTime_'.$i.'|'.  $ws['soilTempMaxTodayTime'][$i].'|!
|soilTempMinYdayTime_'.$i.'|'.  $ws['soilTempMinTodayTime'][$i].'|!
|moistMaxYday_'.$i.'|'.         $ws['moistMaxToday'][$i].'|!
|moistMinYday_'.$i.'|'.         $ws['moistMinToday'][$i].'|!
|moistMaxYdayTime_'.$i.'|'.     $ws['moistMaxTodayTime'][$i].'|!
|moistMinYdayTime_'.$i.'|'.     $ws['moistMinTodayTime'][$i].'|!
';
        } // eo for
} // eo soil
if (isset ($SITE['leafUsed']) && $SITE['leafUsed'] && $SITE['leafCount']*1.0 > 0 && isset ($ws['leafWetMaxToday'][1]) ) {
        if ($SITE['leafCount']*1.0 > 4) {$count = 4;} else {$count = $SITE['leafCount'];}
        for ($i = 1; $i <= $count; $i++) {
                $string .= '|leafWetMaxYday_'.$i.'|'.       $ws['leafWetMaxToday'][$i].'|!
|leafWetMinYday_'.$i.'|'.       $ws['leafWetMinToday'][$i].'|!
|leafWetMaxYdayTime_'.$i.'|'.   $ws['leafWetMaxTodayTime'][$i].'|!
|leafWetMinYdayTime_'.$i.'|'.   $ws['leafWetMinTodayTime'][$i].'|!
';
        } // eo for
} // eo soil




if ( isset($_GET['debug']) ) {echo '<pre>'.PHP_EOL.$string; exit;}  
#
if (!file_put_contents($SITE['ydayTags'],$string) ){   
        echo $yesterdayscript.': Could not save '.$SITE['ydayTags'].'.'.$message.PHP_EOL;
} else {
        echo 'succes - saved '.$SITE['ydayTags'].$message;
}?>