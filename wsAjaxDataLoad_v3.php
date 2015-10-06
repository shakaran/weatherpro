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
ob_start ();    // to remove later on all echos and so on from the output

#
$pageName	= 'wsAjaxDataLoad_v3.php';
$pageVersion	= '3.20 2015-09-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
#
if (!function_exists ('ws_message') ){
        function ws_message ($message,$always=false,&$string=false) {
                global $wsDebug, $SITE;
                $echo	= $always;
                if ( $echo == false && isset ($wsDebug) && $wsDebug == true ) 			{$echo = true;}
                if ( $echo == false && isset ($SIE['wsDebug']) && $SIE['wsDebug'] == true ) 	{$echo = true;}
                if ( $echo == true  && $string === false) {echo $message.PHP_EOL;}
                if ( $echo == true  && $string <>  false) {$string .= $message.PHP_EOL;}
        }
}
ws_message ( '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageName]." -->");
$realtime_all   = '<!-- module '.$pageName.':';
# ----------------------------------------------------------------------
#  3.20 2015-09-05  release 2.8 version
# to load the changed data into string and send it back to the requesting ajax handler ; 
# added more debug info / 03-19 uom for http mb / added extra clrtwtime code /vws wind dir wflash/ extrea trnaslate code fcst / added extra cltrw time code 2 /
#-----------------------------------------------------------------------
if (isset($_REQUEST['wp'])) {
	$wp = strtoupper($_REQUEST['wp']);
        $SITE['switch_wp']      = $wp;
}
#
require_once "wsLoadSettings.php";
#
include_once         $SITE['langFunc'];      // 'wsLangFunctions.php';
include_once         $SITE['functions'];     // 'wsFunctions.php';

if (!isset ($realtime) ){ $realtime = false;};
$error_display  = '';
#
ws_message ( $realtime_all.'('.__LINE__.'): loading '.$SITE['getData'].' -->');
include $SITE['getData'];
#

if (isset ($SITE['realtime']) && $SITE['realtime'] <> 'none') {
        load_realtime_file ($realtime_all, $error_display);            // scriptname string for errors
} else {ws_message ( $realtime_all.'('.__LINE__.'): no realtime data specified in settings  -->');}
#
#
if (!function_exists(' WStime') ){
        function WStime($datetime){
                return substr($datetime,8,2).':'.substr($datetime,10,2);
        }
}
$ws['fcstTxtOrg']	= '';
if (!isset ($ws['fcstTxt']) || $ws['fcstTxt'] == '') {
        $ws['fcstTxt']  = langtransstr('Data will be reloaded in');      
} elseif (isset ($SITE['DavisVP']) && $SITE['DavisVP']) {
	$ws['fcstTxtOrg']	= $ws['fcstTxt'];
        $from = array ('hrs.', 'temp.');
        $to   = array ('hours', 'temperature');
        $ws['fcstTxt'] = ucfirst(str_replace ($from, $to, $ws['fcstTxt'] ));		
        $arrVantage	= explode('.',$ws['fcstTxt'].'.');
        $text		= '';
        $end		= count($arrVantage);
        $inbetween      = '';
        for ($i=0;$i < $end; $i++){
                $strng	= trim($arrVantage[$i]);
                if ((isset($strng) && (strlen($strng) >= 1))) {
                        $text          .=  $inbetween.langtransstr(ucfirst($strng));
                         $inbetween      = ' ';
                        }
        }
        $ws['fcstTxt']  = $text;
}
if (!isset ($rtOut) ) {$rtOut = 'ajax';}
switch ($rtOut) {
  case 'steel':
        if (!isset ($ws['rtTime']) ){ $ws['rtTime']	= $ws['actTime'];}      
        $arrOut['date']	        = WStime($ws['rtTime']);        # "date":"16:47",  "<#date format=hh:nn>",    						
        $arrOut['temp']		= $ws['tempAct'];
        $arrOut['tempTL']	= $ws['tempMinToday'];
        $arrOut['tempTH']	= $ws['tempMaxToday'];
        $arrOut['intemp']	= $ws['tempActInside'];
        $arrOut['dew']		= $ws['dewpAct'];
        $arrOut['dewpointTL']	= $ws['dewpMinToday'];
        $arrOut['dewpointTH']	= $ws['dewpMaxToday'];
        if (isset ($ws['appTemp']) ) 
              { $arrOut['apptemp'] = $ws['appTemp'];} 
        else  { $arrOut['apptemp'] = $arrOut['temp'];}
 # <#apptemp> perceived temperature, caused by the combined effects of air temperature, relative humidity and wind 
        if (isset ($ws['apptempL']) )
              { $arrOut['apptempTL'] = $ws['apptempL'];}
        else  { $arrOut['apptempTL'] = $ws['tempMinToday'];}
        if (isset ($ws['apptempTH']) )
              { $arrOut['apptemTpH'] = $ws['apptempTH'];}
        else  { $arrOut['apptempTH'] = $ws['tempMaxToday'];}
        $arrOut['wchill']	= $ws['chilAct'];
        $arrOut['wchillTL']	= $ws['chilMinToday'];
        $arrOut['heatindex']	= $ws['heatAct'];
        if ($ws['heatMaxToday'] == '--'){$ws['heatMaxToday'] = $ws['heatAct'];}
        $arrOut['heatindexTH']	= $ws['heatMaxToday'];
        if (!isset($ws['humidex']) ) {$ws['humidex'] = $ws['heatAct'];}
        $arrOut['humidex']	= $ws['humidex'];

        $arrOut['wlatest']	= $ws['windAct'];               # "wlatest":"<#wlatest>",  "wlatest":"0,9",
        $arrOut['wspeed']	= $ws['windAct'];               # The 10-minute average / latest 'wind' value from the console
        $arrOut['wgust']	= $ws['gustAct'];
        $arrOut['wgustTM']	= $ws['gustMaxToday'];

        $arrOut['bearing']	= $ws['windActDir'];
        $arrOut['avgbearing']	= $ws['windAvgDir'];

        $arrOut['press']	= $ws['baroAct'];
        $arrOut['pressTL']	= $ws['baroMinToday'];
        $arrOut['pressTH']	= $ws['baroMaxToday'];
        if (isset ($ws['baroMinYear']) && $ws['baroMinYear'] <> 0) {
                $arrOut['pressL']	= $ws['baroMinYear'];} 
        else {  $arrOut['pressL']	= $arrOut['pressTL'];}

        if (isset ($ws['baroMaxYear']) && $ws['baroMaxYear'] <> 0) {
                $arrOut['pressH']	= $ws['baroMaxYear'];} 
        else {  $arrOut['pressH']	= $arrOut['pressTH'];}

        $arrOut['rfall']	= $ws['rainToday'];
        $arrOut['rrate']	= $ws['rainRateAct'];
        if (isset ($ws['rainRateToday']) ) 
                {$arrOut['rrateTM']	= $ws['rainRateToday'];} 
        else    {$arrOut['rrateTM']	= '';}

        $arrOut['hum']		= $ws['humiAct'];
        $arrOut['humTL']	= $ws['humiMinToday'];
        $arrOut['humTH']	= $ws['humiMaxToday'];
        
        if (isset ($ws['humiInAct']) ) 
                {$arrOut['inhum']	= $ws['humiInAct'];} 
        else    {$arrOut['inhum']	= '';}

        $arrOut['SensorContactLost']= '0';
        $arrOut['forecast']	= $error_display.' '.$ws['fcstTxt']; 

        $arrOut['tempunit']	= (string)str_replace('&deg;','',$SITE['uomTemp']);
        $arrOut['windunit']	= (string)trim($SITE['uomWind']);
        $arrOut['pressunit']	= (string)trim($SITE['uomBaro']);
        $arrOut['rainunit']	= (string)trim($SITE['uomRain']);

        $arrOut['temptrend']	= $ws['tempDelta'];

        $arrOut['TtempTL']	= WStime($ws['tempMinTodayTime']);       //  ' 5:59';
        $arrOut['TtempTH']	= WStime($ws['tempMaxTodayTime']);
        $arrOut['TdewpointTL']	= WStime($ws['dewpMinTodayTime']);
        $arrOut['TdewpointTH']	= WStime($ws['dewpMaxTodayTime']);
        $arrOut['TapptempTL']	= WStime($ws['tempMinTodayTime']);      // ?
        $arrOut['TapptempTH']	= WStime($ws['tempMaxTodayTime']);      // ?
        $arrOut['TwchillTL']	= WStime($ws['chilMinTodayTime']);
        $arrOut['TheatindexTH']	= '00:00'; # WStime($ws['heatMaxTodayTime']);

        $arrOut['TrrateTM']	= '00:00';
        $arrOut['ThourlyrainTH']= '00:00';
        if (isset($ws['lastRained']) && $ws['lastRained'] <> ''  && $ws['lastRained'] <> 0) {
                $lastRained             = substr($ws['lastRained'],0,8).'T'.substr($ws['lastRained'],8,6);   
                $arrOut['LastRained']   =date($SITE['timeFormat'],strtotime ($lastRained));	// "LastRainTipISO":"2014-11-25 09:42",
        }
        else  { $arrOut['LastRained']   = ''; }  
        $arrOut['LastRainTipISO']= $arrOut['LastRained'];              
        $arrOut['hourlyrainTH']	= '0.0';                                // "hourlyrainTH":"0,2", "hourlyrainTH":"<#hourlyrainTH>",

        if (isset ($ws['humiMinTodayTime']) ) 
                {$arrOut['ThumTL']	= WStime($ws['humiMinTodayTime']);} 
        else    {$arrOut['ThumTL']	= '';}
        $arrOut['ThumTH']	= WStime($ws['humiMaxTodayTime']);

        $arrOut['TpressTL']	= WStime($ws['baroMinTodayTime']);
        $arrOut['TpressTH']	= WStime($ws['baroMaxTodayTime']);

        $arrOut['presstrendval']= $ws['baroDelta'];

        $arrOut['Tbeaufort']	= $ws['windBeafort'];                   // "Tbeaufort":"F1",  "Tbeaufort":"<#Tbeaufort>",
        $arrOut['TwgustTM']	= WStime($ws['gustMaxTodayTime']);

        $arrOut['windTM']	= $ws['gustMaxToday'];

        $arrOut['bearingTM']	= '5';
       
        $arrOut['BearingRangeFrom10']	= '0';
        $arrOut['BearingRangeTo10']	= '359';
        
        $time                           = strtotime (substr($ws['rtTime'],0,8).'T'.substr($ws['rtTime'],8,6) );
        $arrOut['timeUTC']	        = gmdate ( 'Y,m,d,H,i,s', $time);
        $arrOut['UV']		        = $ws['uvAct'];
        $arrOut['UVTH']		        = $ws['uvMaxToday'];     // "UVTH":"<#UVTH>",
        $arrOut['SolarRad']	        = $ws['solarAct'];
        $arrOut['SolarTM']	        = $ws['solarMaxToday'];     // "SolarTM":"<#solarTH>",
        $arrOut['CurrentSolarMax']      = $ws['solarMaxToday'];
        $arrOut['domwinddir']           = wsConvertWinddir($ws['windAvgDir']);
        if (!isset ($ws['WindRoseData']) ) {
                $ws['WindRoseData']     = '[]';} # '[0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0]';}
        $arrOut['WindRoseData']         = $ws['WindRoseData'];
        $arrOut['windrun']              = '0.0';
        $arrOut['version']	        = '0';	
        $arrOut['build']	        = '0';	
        $arrOut['ver']		        = '11';	        // "ver":"11"	//  '9';

        # send converted info back
        if ( isset($_GET['debug']) ) {
                echo '<br />'.PHP_EOL;
                echo 'processed to output array ='.PHP_EOL;
                var_dump ($arrOut);
                echo 'data returned to calling javascript program ='.PHP_EOL;
        } else {
                ob_clean(); 
                header("Content-Type: text/plain; charset=".$SITE['charset']."");
        }
        $string = '{';
        $seperator='';
        foreach  ($arrOut as $key => $value) {
                $string.=$seperator;
                if ($key <> 'WindRoseData') {
                        $string.= '"'.$key.'":"'.(string)$value.'"';}
                else {  $string.= '"'.$key.'":'.(string)$value;}
                $seperator= ',';
        }
        $string .= '}';
        echo $string;
        return;
   case 'mwxml':
        ob_clean();
   break;
   case 'yowindowxml':
        ob_clean();
   break;
   case 'ajax':
   	ws_message ( $realtime_all.'('.__LINE__.'): loading '.$SITE['loadData'].'  -->');
        include_once($SITE['loadData']);        // 'wsDataLoad.php';
        if (isset ($load_ajax_only) && $load_ajax_only) {
                ob_get_flush();
                return;
        }
        ob_clean();
        echo $string1;
        return;
   break;
   case 'none':
   break;
   default: 
        ws_message ( $realtime_all.'('.__LINE__.'): unknown output type '.$rtOut.' -->');
} // eo switch realtime file out
#
function load_realtime_file ($realtime_all, &$error_display) {
        global $SITE, $ws, $wsDebug, $fileTimeUpload; 
#
        if ( $SITE['realtime'] <> 'none' ) {
                if (!isset ($SITE['realtime_file'] )) 
                       {$error_display = 'realtime = '.$SITE['realtime'].' but: no realtime-file specified. ';
                        ws_message ( $realtime_all.'('.__LINE__.'): '.$error_display.'- continue  -->');
                        return;
                }
                if (!is_file ($SITE['realtime_file']))
                       {$error_display = 'realtime = '.$SITE['realtime'].' but: no '.$SITE['realtime_file'].' found. ';
                        ws_message ( $realtime_all.'('.__LINE__.'): '.$error_display.'- continue  -->');
                        return;
                }
                if (isset ($SITE['realtime_file2'])  && !is_file ($SITE['realtime_file2']))
                       {$error_display = 'realtime file 2 = '.$SITE['realtime_file2'].' but: no '.$SITE['realtime_file2'].' found. ';
                        ws_message ( $realtime_all.'('.__LINE__.'): '.$error_display.'- continue  -->'); 
                }    
        }
        ws_message ( $realtime_all.'('.__LINE__.'): '.$SITE['realtime_file'].' try to load.  -->');
        $string 	= file_get_contents($SITE['realtime_file']);
        $filetime       = filemtime ($SITE['realtime_file']);
        if (isset ($fileTimeUpload) ) {
                if ( (30 + $filetime)  <   $fileTimeUpload ) {
                        ws_message (  $realtime_all.'('.__LINE__.'): realtime file to old Tagfile = '.$fileTimeUpload.' realtimefile = '.$filetime.' -->');
                        return;
                }
        }
#
        switch ($SITE['realtime']) {
            case 'wflash':
                $pos                    = strpos(substr($string,0,10), 'F=' );
                if (!$pos === false)  { $string = substr($string,$pos+2); }  
                $arr_rt                = explode(",",$string);
                $fromTemp	        = 'F';          // ='&deg;C', ='&deg;F'
                $fromWind	        = 'mPh';        // =' km/h', =' kts', =' m/s', =' mph'
                $fromRain	        = 'in';         // =' mm', =' in'
                $fromBaro	        = 'inHg';       // =' hPa', =' mb', =' inHg'
#                
                $ws['rtTime']           = wdDate(trim($arr_rt[1]));
                $ws['tempAct']	        = (string)wsConvertTemperature($arr_rt[9],     $fromTemp);
                $ws['tempActInside']	= (string)wsConvertTemperature($arr_rt[8],     $fromTemp);
 #               $ws['tempDelta']	= (string)wsConvertTemperature($arr_rt[37],    $fromTemp);
$temp1hourAgo		= (string)wsConvertTemperature($arr_rt[9], $fromTemp,$fromTemp) 
			- (string)wsConvertTemperature($arr_rt[37], $fromTemp,$fromTemp) ;
$ws['tempDelta']	= $ws['tempAct'] 
			- wsConvertTemperature  ($temp1hourAgo,$fromTemp);

                $ws['dewpAct']	        = (string)wsConvertTemperature($arr_rt[24],    $fromTemp);
                $ws['appTemp']	        = (string)wsConvertTemperature($arr_rt[29],    $fromTemp);  # ??
                $ws['chilAct']          = (string)wsConvertTemperature($arr_rt[21],    $fromTemp);
                $ws['heatAct']          = (string)wsConvertTemperature($arr_rt[23],    $fromTemp);
                $ws['windAct']          = (string)wsConvertWindspeed($arr_rt[4],       $fromWind);
                $ws['windActDir']       = 1.0*$arr_rt[3];
                $ws['gustAct']          = (string)wsConvertWindspeed($arr_rt[5],       $fromWind);
                $ws['windBeafort']	= (string)wsBeaufortNumber ($ws['windAct'], $SITE['uomWind']);
                $ws['windActDir']       = (string)round($arr_rt[3]);
                $ws['baroAct']          = (string)wsConvertBaro($arr_rt[25],           $fromBaro);
                $ws['baroDelta']	= (string)wsConvertBaro($arr_rt[53],           $fromBaro);
                $ws['humiAct']          = (string)round($arr_rt[7]);
                $ws['humiInAct']        = (string)round($arr_rt[6]);
                $ws['uvAct']            = (string)round($arr_rt[19],1);
 #               $ws['uvMaxToday']       = (string)round($arr_rt[46],1);
                $ws['solarAct']         = (string)round($arr_rt[20],1);
# process wflash2.txt
                if (isset ($SITE['realtime_file2'])) {
                        ws_message (  $realtime_all.'('.__LINE__.'): '.$SITE['realtime_file2'].' try to load.  -->');
                        $string	                = file_get_contents($SITE['realtime_file2']);
                        $pos                    = strpos(substr($string,0,10), 'S=' );
                        if (!$pos === false)  { $string = substr($string,9); }

                        $arr_rt        = explode(",",substr($string,2) );
                        $rtdate        = wdDate(trim($arr_rt[0]));
                        if ($rtdate > $ws['rtTime']) {$ws['rtTime'] = $rtdate; }
                        $ws['tempMinToday']     = (string)wsConvertTemperature($arr_rt[92],    $fromTemp);
                        $ws['tempMaxToday']     = (string)wsConvertTemperature($arr_rt[36],    $fromTemp);
                        $ws['dewpMinToday']     = (string)wsConvertTemperature($arr_rt[107],   $fromTemp);
                        $ws['dewpMaxToday']     = (string)wsConvertTemperature($arr_rt[51],    $fromTemp);
                        $ws['chilMinToday']     = (string)wsConvertTemperature($arr_rt[104],   $fromTemp);
                        $ws['heatMaxToday']     = (string)wsConvertTemperature($arr_rt[50],    $fromTemp);
                        $ws['gustMaxToday']     = (string)wsConvertWindspeed($arr_rt[32],      $fromWind);
                        $ws['windAvgDir']       = (string)$arr_rt[2];
                        $ws['baroMinToday']     = (string)wsConvertBaro($arr_rt[108],          $fromBaro);
                        $ws['baroMaxToday']     = (string)wsConvertBaro($arr_rt[52],           $fromBaro);
                        $ws['rainToday']        = (string)wsConvertRainfall($arr_rt[254],      $fromRain);
                        $ws['rainRateAct']      = (string)wsConvertRainfall($arr_rt[257],      $fromRain);
                        $ws['rainRateToday']    = (string)wsConvertRainfall($arr_rt[150],      $fromRain);
                        $ws['humiMinToday']     = (string)round($arr_rt[90]);
                        $ws['humiMaxToday']     = (string)round($arr_rt[34]);
                        $ws['windAvgDir']       = (string)round($arr_rt[2]);
                        $ws['solarMaxToday']    = (string)$arr_rt[47];
                        $ws['uvMaxToday']       = (string)round($arr_rt[46],1);
                }        // eo realtimefile 2
           break; 
           case 'json':
                $oldDegree              = iconv ('UTF-8','windows-1252//TRANSLIT','°'); // clean for nonjson characters
                $from 		        = array ('&deg;','°', $oldDegree);
                $string 	        = str_replace ($from, '', $string);
#
                if ($SITE['WXsoftware'] == 'WC') {      // uses decimal , no point
                        $string	= str_replace(',' , '.' , $string);
                        $string = str_replace('|' , ',' , $string);
                }

                $arr_rt	                = json_decode($string, true);
                $fromTemp	        = str_replace ('&#176;','',$arr_rt ['tempunit']);
                $fromWind	        = $arr_rt ['windunit'];
                $fromRain	        = $arr_rt ['rainunit'];
                $fromBaro	        = $arr_rt ['pressunit'];
                $ws['rtTime']           = wdDate($arr_rt['date']);
                $ws['tempAct']	        = (string)wsConvertTemperature($arr_rt['temp'], $fromTemp);	        // "temp":"4,6",
                $ws['tempActInside']    = (string)wsConvertTemperature($arr_rt['intemp'], $fromTemp);	        // "intemp":"18,7",
                $ws['dewpAct']		= (string)wsConvertTemperature($arr_rt['dew'], $fromTemp);	        // "dew":"3,3",
                $ws['appTemp']          = (string)wsConvertTemperature($arr_rt['apptemp'], $fromTemp);
                $ws['chilAct']          = (string)wsConvertTemperature($arr_rt['wchill'], $fromTemp);
                $ws['windAct']          = (string)wsConvertWindspeed($arr_rt['wlatest'], $fromWind);
                $ws['windBeafort']	= (string)wsBeaufortNumber ($ws['windAct'], $SITE['uomWind']);
                $ws['gustAct']          = (string)wsConvertWindspeed($arr_rt['wgust'], $fromWind);
                $ws['windActDir']       = (string)$arr_rt['bearing'];
                $ws['windAvgDir']       = (string)$arr_rt['avgbearing'];
                $ws['baroAct']          = (string)wsConvertBaro($arr_rt['press'], $fromBaro);
                $ws['rainToday']        = (string)wsConvertRainfall($arr_rt['rfall'], $fromRain);
                $ws['rainRateAct']      = (string)wsConvertRainfall($arr_rt['rrate'], $fromRain);
                $ws['humiAct']          = (string)$arr_rt['hum'];
                $ws['humiInAct']        = (string)$arr_rt['inhum'];
                
                $ws['uvAct']            = (string)$arr_rt['UV'];
                if ($ws['uvAct'] > $ws['uvMaxToday']) {$ws['uvMaxToday'] = $ws['uvAct'];}
                $ws['solarAct']         = (string)$arr_rt['SolarRad'];
                if ($ws['solarAct'] > $ws['solarMaxToday']) {$ws['solarMaxToday'] = $ws['solarAct'];}
           break;
           case 'cltrw':
                $oldDegree              = iconv ('UTF-8','windows-1252//TRANSLIT','°');
                $from 		        = array ('&deg;','°',$oldDegree);
                $string 	        = trim(str_replace ($from, '', $string));
                $string                 = preg_replace("/[\r\n]+[\s\t]*[\r\n]+/","\n",$string);
                $arr_rt                = explode(" ", $string);
                if ($arr_rt[0] <> '12345') {
                        $errorfile      = substr ($string,0,20);
                        ws_message (  $realtime_all.'('.__LINE__.'): This file ('.$SITE['realtime_file'].') seems not to be a '.$SITE['realtime'].' type file  
'.$errorfile.' -->');
                        return;
                }
                $fromTemp	        = 'c';
                $fromWind	        = 'kts';
                $fromRain	        = 'mm';
                $fromBaro	        = 'hpa';
                $arr                    = explode ('-',$arr_rt['32']);
                $last	= count($arr) -1;
                $time                   = trim(str_replace('_',' ',$arr[$last]));
                $ws['rtTime']           = wdDate($time);
                $ws['tempAct']	        = (string)wsConvertTemperature($arr_rt['4'], $fromTemp);
                $ws['tempActInside']	= (string)wsConvertTemperature($arr_rt['12'], $fromTemp);
                $ws['dewpAct']	        = (string)wsConvertTemperature($arr_rt['72'], $fromTemp);
                $ws['appTemp']	        = (string)wsConvertTemperature($arr_rt['130'], $fromTemp);
                $ws['chilAct']          = (string)wsConvertTemperature($arr_rt['44'], $fromTemp);
                $ws['windAct']          = (string)wsConvertWindspeed($arr_rt['1'], $fromWind);
                $ws['windBeafort']	= (string)wsBeaufortNumber ($ws['windAct'], $SITE['uomWind']);
                $ws['gustAct']          = (string)wsConvertWindspeed($arr_rt['2'], $fromWind);
                $ws['windActDir']       = (string)$arr_rt['3'];
                $ws['windAvgDir']       = (string)$arr_rt['117'];
                $ws['baroAct']          = (string)wsConvertBaro($arr_rt['6'], $fromBaro);
                $ws['rainToday']        = (string)wsConvertRainfall($arr_rt['7'], $fromRain);
                $ws['rainRateAct']      = (string)wsConvertRainfall($arr_rt['10'], $fromRain);
                $ws['humiAct']          = (string)$arr_rt['5'];
                $ws['uvAct']            = (string)$arr_rt['79'];
                $ws['solarAct']         = (string)$arr_rt['127'];
           break;
           case 'http':
                $pos                    = strpos (substr($string,0,20),'|',0);
                if (!$pos) {$split_char = ',';} else {$split_char = '|';}
                $arr_rt	= explode ($split_char,$string);
#	$arr_rt[nr]
#       0       1		2		3		4       5		6		7		8		9
# [hh]:[mm],[th0temp-act],[th0temp-dmin],[th0temp-dmax],[thb0temp-act],[th0dew-act],[th0dew-dmin],[th0dew-dmax],[wind0chill-act],[wind0chill-dmin],
#	10		11			12		13		14		15		16		17			18		19
# [wind0wind-act],[wind0avgwind-act],[wind0wind-max10],[wind0wind-dmax],[wind0dir-act],[wind0dir-avg10:--],[thb0seapress-act],[thb0seapress-dmin],[thb0seapress-dmax],[thb0seapress-ymin],
#	20		21			22		23		24	   25		26		27		28		29
# [thb0seapress-ymax],[rain0total-daysum],[rain0rate-act],[rain0rate-dmax],[th0hum-act],[th0hum-dmin],[th0hum-dmax],[thb0hum-act],[th0temp-val60:--],[th0temp-dmintime],
#	30		31			32		33		34			35		36		37				38			39
# [th0temp-dmaxtime],[th0dew-dmintime],[th0dew-dmaxtime],[wind0chill-dmin],[rain0rate-dmaxtime],[th0hum-dmintime],[th0hum-dmaxtime],[thb0seapress-dmintime],[thb0seapress-dmaxtime],[thb0seapress-val60:--],
#		40		41			42		43		44		45		46			47		48				49
# [wind0wind-act=bft.0],[wind0wind-dmaxtime],[wind0avgwind-dmax],[uv0index-act:--],[sol0rad-act:--],[sol0rad-hmax:--],[sol0rad-dmax:--],[mbsystem-swversion],[mbsystem-buildnum],[YYYY]:[MM]:[DD]:[Uhh]:[Umm]:[Uss]
#       50
#[forecast-text]
                $ws['rtTime']           = wdDate(trim($arr_rt['0']));
                $ws['tempAct']		= (string)wsConvertTemperature($arr_rt[1], 'c');	
                $ws['dewpAct']          = (string)wsConvertTemperature($arr_rt[5], 'c');	
                $ws['chilAct']          = (string)wsConvertTemperature($arr_rt[8], 'c');	
                $ws['chilMinToday']     = (string)wsConvertTemperature($arr_rt[9], 'c');
                $ws['windAct']          = (string)wsConvertWindspeed($arr_rt[11], 'm/s');
                $ws['windBeafort']	= (string)wsBeaufortNumber ($ws['windAct'], $SITE['uomWind']);
                $ws['gustAct']          = (string)wsConvertWindspeed($arr_rt[12], 'm/s');
                $ws['windActDir']       = (string)$arr_rt[14];
                $ws['windAvgDir']       = (string)$arr_rt[15];
                $ws['baroAct']          = (string)wsConvertBaro($arr_rt[16], 'hpa');
                $ws['rainToday']        = (string)wsConvertRainfall($arr_rt[21], 'mm');
                $ws['rainRateAct']      = (string)wsConvertRainfall($arr_rt[22], 'mm');
                $ws['humiAct']          = (string)$arr_rt[24];	
                $ws['humiInAct']        = (string)$arr_rt[27];
                $ws['uvAct']            = (string)$arr_rt[43];
                $ws['solarAct']         = (string)$arr_rt[44];
           break;
           case 'weatherlink':
#echo $string; exit;

                $arr    = explode ("\n",$string);
#                var_dump ($arr); exit;
        
                $end    = count ($arr);
                for ($n = 0; $n < $end; $n++) {
                        $line   = trim ($arr[$n]);
                        if ($line  == '' ) {continue;}
                        $substr = substr($line,1,5);
                        if ($substr  == '-----')  {continue;}
                        if (substr($line,0,1) <> '|') {continue;}
                        list ($skip,$name, $content) = explode ('|',$line.'|');
                        $name   = trim($name);
                        $content= trim($content);
 #                       if ($content  == '' ) {ws_message (  $startEcho.$tagsScript.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL;continue; }
                        $arr_rt[$name]=$content;
                }
#                var_dump ($arr_rt); exit;             
                $oldDegree              = iconv ('UTF-8','windows-1252//TRANSLIT','°');
                $from 		        = array ('&deg;','°',$oldDegree);
                $arr_rt['tempunit']	= str_replace ($from, '', $arr_rt['tempunit']);
                $arr_rt['windunit']	= str_replace ('r', '', $arr_rt['windunit']);
                $fromTemp	        = $arr_rt ['tempunit'];
                $fromWind	        = $arr_rt ['windunit'];
                $fromRain	        = $arr_rt ['rainunit'];
                $fromBaro	        = $arr_rt ['pressunit'];
                $ws['rtTime']           = wdDate($arr_rt['date']);
                $ws['rtVersion']        = $arr_rt['rtVersion'];
                $ws['tempAct']	        = (string)wsConvertTemperature($arr_rt['temp'], $fromTemp);	        // "temp":"4,6",
                $ws['tempActInside']    = (string)wsConvertTemperature($arr_rt['intemp'], $fromTemp);	        // "intemp":"18,7",
                $ws['dewpAct']		= (string)wsConvertTemperature($arr_rt['dew'], $fromTemp);	        // "dew":"3,3",
                $ws['chilAct']          = (string)wsConvertTemperature($arr_rt['wchill'], $fromTemp);
                $ws['windAct']          = (string)wsConvertWindspeed($arr_rt['wlatest'], $fromWind);
                $ws['windBeafort']	= (string)wsBeaufortNumber ($ws['windAct'], $SITE['uomWind']);
                $ws['gustAct']          = (string)wsConvertWindspeed($arr_rt['wgust'], $fromWind);
                $ws['windActDir']       = (string)$arr_rt['bearing'];
                $ws['windAvgDir']       = (string)$arr_rt['avgbearing'];
                $ws['baroAct']          = (string)wsConvertBaro($arr_rt['press'], $fromBaro);
                $ws['rainToday']        = (string)wsConvertRainfall($arr_rt['rfall'], $fromRain);
                $ws['rainRateAct']      = (string)wsConvertRainfall($arr_rt['rrate'], $fromRain);
                $ws['humiAct']          = (string)$arr_rt['hum'];
                $ws['uvAct']            = (string)$arr_rt['UV'];
                if ($ws['uvAct']   > $ws['uvMaxToday']) {$ws['uvMaxToday'] = $ws['uvAct'];}
                $ws['solarAct']         = (string)$arr_rt['SolarRad'];
                if ($ws['solarAct'] > $ws['solarMaxToday']) {$ws['solarMaxToday'] = $ws['solarAct'];}           
           break;
           default:
                ws_message ( $realtime_all.'('.__LINE__.'): unknown realtime type '.$SITE['realtime'].' found    -->');
        } 
} 
# ----------------------  version history
# 3.20 2015-09-05 release 2.8 version 
