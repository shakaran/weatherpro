<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsMetarTxt.php';
$pageVersion	= '3.20 2015-09-28';
#-------------------------------------------------------------------------------
# 3.20 2015-09-28 release 2.8 version plus typing error
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$script         = str_replace ('.php','',$pageFile);
$cacheFileDir   = $SITE['cacheDir'];
#------------------- main function mtr_conditions ----------------------
function mtr_conditions ($icao,$test='') {
        global $metarPtr, $group;
        global $script, $SITE, $result;
        $function               = $script.'-mtr_conditions';
        $result                 = array ();
        $metarCacheName         = $SITE['cacheDir'].'metarcache-'.$icao.'.arr';
        $metarRefetchSeconds    = 600;                  // fetch every 10 minutes
# overrides
        if(isset($_REQUEST['force']) and strtolower($_REQUEST['force']) == 'metar') {
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): No cache used as force=metar was added to url -->',true);
                $metarRefetchSeconds    = 0;
        }
        if(isset($_REQUEST['cache']) and strtolower($_REQUEST['cache']) == 'metar') {
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Cache used as cache=metar was added to url -->',true);
                $metarRefetchSeconds    = 9999999;
        }
        ws_message ( '<!-- module '.$function.' ('.__LINE__.'): using METAR ICAO='.$icao.' -->');
        $metarURL = 'http://weather.noaa.gov/pub/data/observations/metar/stations/'. $icao.'.TXT';
        $html   = '';
        $raw    = '';
# get the metar data from the cache or from the URL if the cache is 'stale
        if (file_exists($metarCacheName) ){
                $file_time      = filemtime($metarCacheName);
                $now            = time();
                $diff           = ($now     -   $file_time);
                ws_message ( '<!-- module '.$function.' ('.__LINE__.'): 
        cache file   = '.$metarCacheName.'
        cache time   = '.date('c',$file_time).' from unix time '.$file_time.'
        current time = '.date('c',$now).' from unix time '.$now.' 
        difference   = '.$diff.' (seconds)
        diff allowed = '.$metarRefetchSeconds.' (seconds) -->');
        	if ($diff < $metarRefetchSeconds) {
        	        ws_message ( '<!-- module '.$function.' ('.__LINE__.'): Data loaded from cache. -->');
        	        $return   = unserialize(file_get_contents($metarCacheName) );
        	        return $return;
        	} 
        } 
        if ($test <> '') { 
                ws_message ( '<!-- module '.$function.' ('.__LINE__.'): Data will be loaded from testfile -->');
                $rawhtml = $test;    
        } 
        else{   ws_message ( '<!-- module '.$function.' ('.__LINE__.'): Data will be loaded from '.$metarURL.' -->');
                $rawhtml        = ws_metar_curl($metarURL);
        }
        ws_message ( '<!-- module '.$function.' ('.__LINE__.'): $rawhtml = '.PHP_EOL.$rawhtml.' -->');
        $html   = $rawhtml;
# ADD checks for correct data  
#
#              
        if (empty($rawhtml)){
                return false;
        }
        list ($date,$raw_metar) = explode ("\n",trim($html));   # date = 2015/08/29 09:20    # date should become 2015-08-29T09:20:00Z
        $result['metar_raw']    = trim ($raw_metar);            # [raw_text] => EBBR 290920Z 05003KT 010V120 9999 FEW045 20/13 Q1023 NOSIG
        $result['metar_cleaned']= '';
        $from                   = array ('/',' ');
        $to                     = array ('-','T');
        $date                   = str_replace ($from,$to,$date);
        list($id,$rest)         = explode (' ',trim($raw_metar));
        $result['station_id']   = $id;                          # [station_id] => EBBR
        $result['errors']       = array();
        if (trim($icao) <> trim($id) ) {
                $result['errors'][]     = 'Conflicting METAR-id, requested: >'.trim($icao).'< Returned: >'.trim($id).'<'; 
                ws_message ( '<!-- module '.$function.' ('.__LINE__.'): '.$result['errors'].' -->', true);
        }
        $result['time']         = $date.':00 UTC';               # [time] => 2015-08-29T09:20:00Z
        $age                    = abs(time() - strtotime($result['time']));
        $result['age']          = $age;
        $to_old                 = '';
        if (isset ($metar_max_age) && $age > $metar_max_age) {
                $to_old                 = 'To old for >'.($age - $metar_max_age).'< seconds';
                $result['errors'][]     = 'Metar: '.$to_old;
                ws_message ( '<!-- module '.$function.' ('.__LINE__.'): age in seconds = '.$age.'< from date >'.$result['time'].'< '.$to_old.' -->', true);
        }
        else {  ws_message ( '<!-- module '.$function.' ('.__LINE__.'): age in seconds = '.$age.'< from date >'.$result['time'].'< -->');
        }
# Clean up the metar.. some are not properly formatted, human made, most likely. Thanks to Ken True for this clean-up code!
        $unprocMetar    = $metar        = $raw_metar;      
        $metar  = preg_replace('|[\r\n]+|is','',$metar);        // remove internal newlines
	$metar  = preg_replace('|/////KT|is','VRB00KT',$metar); // replace bogus wind report
	$metar  = preg_replace('|@|is','',$metar);              // remove strange @ in metar
	$metar  = preg_replace('|///|is',' ',$metar);           // remove strange standalone slashes
	$metar  = preg_replace('| /|is',' ',$metar);            // remove strange standalone slashes
	$metar  = preg_replace('| / |is',' ',$metar);           // remove strange standalone slashes
	$metar  = preg_replace('| \s+|is',' ',$metar);          // remove multiple spaces
	$metar  = preg_replace('| COR |i',' ',$metar);          // remove COR (correction) from raw metar
	$metar  = preg_replace('|(\d{5}) KT|i','${1}KT',$metar);    // fix any space in wind value
	$metar  = preg_replace('| 999 |',' 9999 ',$metar);      // fix malformed unlimited visibility
	$metar  = preg_replace('| LRA |',' -RA ',$metar);       // fix malformed light rain
	$metar  = preg_replace('| HRA |',' +RA ',$metar);       // fix malformed light rain
	// $metar = preg_replace('| (\d)SM|i',' 0${1}SM',$metar); // fix malformed visibility to two digits
 	// $metar = preg_replace('| (\d+) (\d+)/(\d+)SM |i',' $1_$2/${3}SM ',$metar); // fix NOAA visibility
 
        ws_message ( '<!-- module '.$function.' ('.__LINE__.'): cleaned $metar = '.$metar.' -->');
        $result['metar_cleaned']= $metar;
        $result['max-icon']     = '';
        
        mtr_process($metar,$icao);      // actually parse the metar for conditions. 

# check if icon is correct / complete
        $icon           = $result['max-icon'];
        if ($icon == '') {
                if (isset ($result['visibility_sm']) &&  $result['visibility_sm'] == 'max') {
                        $result['max-icon']     = '000';
                }
                else {  $result['max-icon']     = '901';
                        $error                  = 'Metar contains no info to generate icon ';
                        $result['errors'][]     = $error;
                        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$result['metar_cleaned'].' result: ' .$error.' -->',true);
                }
        } 
        elseif ($icon > 0 && $icon < 100) {
                $result['max-icon']     = $icon + 100;
                $error                  = 'Metar missing cloud inf0, icon changed from '.$icon.' to '. $result['max-icon'];
                $result['errors'][]     = $error;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$result['metar_cleaned'].' result: '.$error.' -->',true);
        }
        $icon_url       = $SITE["defaultIconsDir"].$result['max-icon'].'.png';
        if (!is_file($icon_url) ) {
                $error                  = 'Unknown icon generated: >'.$result['max-icon'].'< with url >'.$icon_url;
                $result['errors'][]     = $error;
                $result['max-icon']     = '901';
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$result['metar_cleaned'].' result: '.$error.' -->',true);
        }
        $result['icon_url']     = $icon_url;
# finished for here
   	ws_message (  '<!-- module '.$function.' ('.__LINE__.'):  decode for '.$icao.' is'.PHP_EOL.print_r($result,true).' -->');
# skip save to cahce if we are testing large numbers of metars      
        if ($test <> '') {return $result;}
# save normal metar to cache
        if (!file_put_contents($metarCacheName, serialize($result))){
                ws_message ( '<h3>Unable to write cache '.$metarCacheName.'. Program halted</h3>',true);
                exit;
        } 
        ws_message ( '<!-- module '.$function.' ('.__LINE__.'): data saved to cache '.$metarCacheName.' -->');
        return $result;
} // eof mtr_conditions
#
# ------------------Main decoding of this script.----------------------------
function mtr_process($metar,$icao) {
# This function directs the examination of each group of the METAR. 
# The problem with a METAR is that not all the groups have to be there, some groups could be missing. 
# Fortunately, the groups must be in a specific order.
# This function also assumes that a METAR is well-formed, that is, no typographical mistakes.
# This function uses a function variable to organize the sequence in which to decode each group. 
# Each function checks to see if it can decode the current METAR part. 
# If not, then the group pointer is advanced for the next function to try. 
# If yes, the function decodes that part of the METAR and advances the METAR pointer and group pointer.
# If the function can be called again to decode similar information, then the group pointer does not get advanced.
# This function was modified by Ken True - webmaster@saratoga-weather.org to  work with the Saratoga template sets.
# Adapted to work with the Leuven-Template

        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_conditions';
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): function started -->');
 
 	$metarParts = explode(' ',$metar);
        $groupName = array(
                'mtr_get_station',
                'mtr_get_time',
                'mtr_get_station_type',
                'mtr_get_wind',
                'mtr_get_var_wind',
                'mtr_get_visibility',
                'mtr_get_runway',
                'mtr_get_conditions',
                'mtr_get_cloud_cover',
                'mtr_get_temperature',
                'mtr_get_altimeter');
        $metarPtr       = 2;                    // mtr_get_station identity & time  is ignored
	$group          = 2;                    // start with station type
	$end            = count($groupName);	
	while ($group < $end) {
	        if (!isset ($metarParts[$metarPtr]) ) {
	                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): no more parts in METAR -->');  
	                break;
	        }
		$part   = $metarParts[$metarPtr];
		ws_message (  '<!-- module '.$function.' ('.__LINE__.'): calling '.$groupName[$group] .' part = '.$part.' ptr = '.$metarPtr.' grp = '.$group.' -->');
		$groupName[$group]($part);      // $groupName is a function variable
	}
} // eof mtr_process
# ------------------------------------------------------------------------------
function mtr_get_station($part)  { 
# Script assumes this matches requesting $station. 
# This function is never called. It is here for completeness of documentation.
        global $script;
        $function       = $script.'-mtr_get_station';
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
       return;
 } // eof mtr_get_station
# ------------------------------------------------------------------------------
function mtr_get_time($part) { 
# Ignore observation time. This information is found in the first line of the NWS file.
# Format is ddhhmmZ where dd = day, hh = hours, mm = minutes in UTC time.
        global $script;
        $function       = $script.'-mtr_get_time';
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
        return;
}
# ------------------------------------------------------------------------------
function mtr_get_station_type($part) { 
# Ignore station type if present.
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_station_type';
        if ($part == 'AUTO' || $part == 'COR') {
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): station type present, skipped, $metarPtr set to '.$metarPtr.' -->');
        }
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
}
# ------------------------------------------------------------------------------
function mtr_get_wind($part) { 
# Decodes wind direction and speed information.
# Format is dddssKT where ddd = degrees from North, ss = speed,
# KT for knots  or dddssGggKT where G stands for gust and gg = gust speed. 
#       ss or gg can be a 3-digit number.)
#       KT can be replaced with MPS for meters per second or KMH for  kilometers per hour.
# Also possible   VRBssKT  VRB03KT
#
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_wind';
        if (preg_match('/^([0-9G]{5,10}|VRB[0-9G]{2,7})(KT|MPS|KMH)$/',$part,$pieces)) {
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): windspeed present -->');
                $part = $pieces[1];
                $unit = $pieces[2];
                if ($part == '00000') {
                        $result['wind_speed_kt'] = 0;
                }
                else  { preg_match('/([0-9]{3}|VRB)([0-9]{2,3})G?([0-9]{2,3})?/',$part,$pieces);
                        if ($pieces[1] == 'VRB') {
                                $result['wind_dir']     = 'varies';
                        }        
                        else {  $result['wind_dir']     = (integer) $pieces[1];
                        }
                        # [wind_speed_kt]
                        # [gust_speed]
                        if (isset($pieces[3]) &&  $pieces[3] <> 0 ) {
                                $result['gust_speed']           = $pieces[3];
 	                }	                
	                if ($unit == 'KT') {
	                        $result['wind_speed_kt']        = $pieces[2];
	                        $result['wind_unit_org']        = 'kts';
	                        $result['wind_speed_org']       = $pieces[2];	                        
	                } 
	                elseif ($unit == 'MPS') {
	                        $result['wind_speed_kt']        = wsConvertWindspeed($pieces[2],'ms','kts');
	                        $result['wind_unit_org']        = 'ms';
	                        $result['wind_speed_org']       = $pieces[2];

	                } 
	                else {  $result['wind_speed_kt']        = wsConvertWindspeed($pieces[2],'kmh','kts');
	                        $result['wind_unit_org']        = 'kmh';
	                        $result['wind_speed_org']       = $pieces[2];
	                }
                } // eo else calm
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
        }
        $group++;      
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
} // eof mtr_get_wind
# ------------------------------------------------------------------------------
function mtr_get_var_wind($part) {
# Ignore variable wind direction information if present.
# Format is fffVttt where V stands for varies from fff degrees to ttt degrees.
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_wind';
        if (preg_match('/([0-9]{3})V([0-9]{3})/',$part,$pieces)) {
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): variable wind direction present, ignored, $metarPtr set to '.$metarPtr.' -->');
        }
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
} // eof mtr_get_var_wind
# ------------------------------------------------------------------------------
function mtr_get_visibility($part) {
# This function will be called a second time if visibility is limited to an integer mile plus a fraction part.
# Format is 
#       mmSM for mm = statute miles, 
#       m n/dSM for m = mile  and n/d = fraction of a mile, 
#       4-digit number nnnn (withleading zeros) for nnnn = meters.
        global $metarPtr, $group, $script, $result;       
        static $integerMile = '';
        $function       = $script.'-mtr_get_visibility';
        if (strlen($part) == 1) {       // visibility is limited to a whole mile plus a fraction part
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): part length == 1 -->');
                $integerMile = $part . ' ';
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $metarPtr++;
                return;
        } // eo whole mile
#
        if ($part == '9999') {
                $result['visibility_prefix']    = 'More than';  
                $result['visibility_sm']        = round(9999*0.00062,2);
                $result['visibility_org']       = 9999;
                $result['visibility_unit']      = 'm';
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' international code for meters, maximum visibility -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
                $integerMile = '';
                return;
        }
         if ($part == '0000') {
                $result['visibility_prefix']    = '';  
                $result['visibility_sm']        = 'min';
                $result['visibility_org']       = $part;
                $result['visibility_unit']      = 'm';
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' international code for meters, no visibility -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
                $integerMile = '';
                return;
        }
        if (preg_match('|^\d{4}$|',$part)) {   // international code for meters of visibility
                $result['visibility_prefix']    = '';  
                $result['visibility_sm']        = round($part*0.00062,2);
	        $result['visibility_org']       = $part;
	        $result['visibility_unit']      = 'm';   
	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' international code for meters of visibility in miiles  >'.$result['visibility_sm'].'< -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
                $integerMile = ''; 
                return;
        } // eo international code
        
        if (substr($part,-2) == 'SM') {  // visibility is in miles
                $part   = substr($part,0,strlen($part)-2);
                if (substr($part,0,1) == 'M')  {                // ?
                        $result['visibility_prefix']    = 'Less than ';
                        $part   = substr($part, 1);
                } 
                else {  $result['visibility_prefix']    = '';  
                }
                $result['visibility_sm']        = $integerMile.$part;
                $result['visibility_org']       = $result['visibility_sm'];
                $result['visibility_unit']      = 'mi';
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' Visibility is in miles $result["visibility_sm"] = >'.$result['visibility_sm'].'< -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
	        $integerMile = '';
                return;
        }
        if (substr($part,-2) == 'KM') {   // unknown (Reported by NFFN in Fiji)
                if (substr($part,0,1) == 'M')  {                // ?
                        $result['visibility_prefix']    = 'Less than ';
                        $part   = substr($part, 1);
                }
                else {  $result['visibility_prefix']    = '';  
                }
                $result['visibility_sm']        = round($part*0.62,2);
                $result['visibility_org']       = $integerMile.$part;
                $result['visibility_unit']      = 'km';
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' Visibility is in KM = >'.$result['visibility_sm'].'< -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
	        $integerMile = '';
                return;
        }
       
        if (preg_match('/^([0-9]{4})/',$part,$pieces)) {  // visibility is in meters
                $result['visibility_prefix']    = ''; 
                $result['visibility_sm']        = round($part*0.00062,2);  
	        $result['visibility_org']       = $pieces[0];
	        $result['visibility_unit']      = 'm';
	
	        $result['visibility_dir']       = str_replace($pieces[0],'',$part);
	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' international code for meters of visibility plus direction -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
                $integerMile = ''; 
                return;
        } 
        if ($part == 'CAVOK')   { // good weather
                $result['visibility_prefix']    = 'More than';  
                $result['visibility_sm']        = round(9999*0.00062,2);
                $result['visibility_org']       = 9999;
                $result['visibility_unit']      = 'm';
                $result['conditions'][]         = 'Clear';
                $result['covers'][]             = array ('txt' => 'Clear', 'height' => '');   
                $result['max-icon']             = '000';
	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.'  good weather $result["visibility_sm"] = >'.$result['visibility_sm'].'< -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group += 4;  // can skip the next 3 groups
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' = extra steps -->');
                return;
        }
        $group++;     
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
} // eof mtr_get_visibility
# ------------------------------------------------------------------------------
function mtr_get_runway($part) { 
# Ignore runway information if present. Maybe called a second time.
# Runway format is Rrrr/vvvvFT where rrr = runway number and vvvv = visibility in feet.
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_runway';
 #     
        if(preg_match('|^\d{4}[NESW]+$|',$part)) {      // WMO formatted limited visibility, ignore here as the first is already checked in visibility
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' WMO formatted limited visibility, ignored -->');
	        $metarPtr++;
	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
	        return;
        }
#
        if(preg_match('|^R\d\d|',$part)) {
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): '.$part.' runway information, ignored -->');
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                return;
        }
#
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
}  // eof mtr_get_runway
# ------------------------------------------------------------------------------
function mtr_get_conditions($part) {
# Decodes current weather conditions.
# This function maybe called several times to decode all conditions.
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_conditions';
	static $wxCode = array(
		'VC' => array('nr' => 0,	'txt' =>	'Nearby '),
		'MI' => array('nr' => 0,	'txt' =>	'Shallow '),
		'PR' => array('nr' => 0,	'txt' =>	'Partial '),
		'BC' => array('nr' => 0,	'txt' =>	'Patches of '),
		'DR' => array('nr' => 0,	'txt' =>	'Low Drifting '),
		'BL' => array('nr' => 0,	'txt' =>	'Blowing '),
		'TS' => array('nr' => 0,	'txt' =>	'Thunderstorm'),
		'FZ' => array('nr' => 0,	'txt' =>	'Freezing '),
		'TS' => array('nr' => 0,	'txt' =>	'Thunderstorm'),
		'FZ' => array('nr' => 0,	'txt' =>	'Freezing '),
		'DZ' => array('nr' => 10,	'txt' =>	'Drizzle'),
		'RA' => array('nr' => 10,	'txt' =>	'Rain'),
		'SN' => array('nr' => 20,	'txt' =>	'Snow'),
		'SG' => array('nr' => 20,	'txt' =>	'Snow Grains'),
		'IC' => array('nr' => 30,	'txt' =>	'Ice Crystals'),
		'PE' => array('nr' => 30,	'txt' =>	'Ice Pellets'),
		'GR' => array('nr' => 30,	'txt' =>	'Hail'),
		'GS' => array('nr' => 30,	'txt' =>	'Small Hail'),
		'PL' => array('nr' => 30,	'txt' =>	'Ice Pellets'),
		'UP' => array('nr' => 0,	'txt' =>	''),
		'SH' => array('nr' => 0,	'txt' =>	'Showers'),
		'BR' => array('nr' => 50,	'txt' =>	'Mist'),
		'FG' => array('nr' => 50,	'txt' =>	'Fog'),
		'FU' => array('nr' => 50,	'txt' =>	'Smoke'),
		'VA' => array('nr' => 50,	'txt' =>	'Volcanic Ash'),
		'DU' => array('nr' => 50,	'txt' =>	'Widespread Dust'),
		'SA' => array('nr' => 50,	'txt' =>	'Sand'),
		'HZ' => array('nr' => 50,	'txt' =>	'Haze'),
		'PY' => array('nr' => 0,	'txt' =>	'Spray'),
		'PO' => array('nr' => 0,        'txt' =>	'Well-developed Dust/Sand Whirls'),
		'SQ' => array('nr' => 0,        'txt' =>	'Squalls'),
		'FC' => array('nr' => 0,        'txt' =>	'Funnel Cloud, Tornado, or Waterspout'),
		'SS' => array('nr' => 0,        'txt' =>	'Sandstorm/Duststorm')  );
		
	if (preg_match('/^(-|\+|VC)?(TS|SH|FZ|BL|DR|MI|BC|PR|RA|DZ|SN|SG|GR|GS|PE|IC|UP|BR|FG|FU|VA|DU|SA|HZ|PY|PO|SQ|FC|SS|DS)+$/',$part,$pieces)) {
	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): part =  >'.$part.'< -->');
		if (substr($part,0,1) == '-') {
			$prefix = 'Light ';
			$part = substr($part,1);
		}
		elseif (substr($part,0,1) == '+') {
			$prefix = 'Heavy ';
			$part = substr($part,1);
		}
		elseif (substr($part,0,2) == 'VC') {
			$prefix = 'Nearby ';
			$part = substr($part,2);
		}
		else {  $prefix = '';  // moderate conditions have no descriptor
		}
		
		if (substr($part,0,2) == 'SH' && strlen ($part > 2)) {  // The 'showers' code 'SH' is moved behind the next 2-letter code to make the English translation read better.
		        $part = substr($part,2,2) . substr($part,0,2). substr($part, 4);
		}
		while ($code = substr($part,0,2)) {
		        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Processing $part =  >'.$part.'< $code =  >'.$code.'< -->');
			$result['conditions'][]         = $prefix.$wxCode[$code]['txt'];
			$result['conditions_codes'][]   = $code;
			$nr                             = $wxCode[$code]['nr'];
			if (!isset ($result['max-icon']) || $nr > $result['max-icon']) {
			        $result['max-icon']     = $nr;
			}
			$prefix                         = '';
			$part = substr($part,2);
		}
		$metarPtr++;
		ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
	}  // eo if known code
	else {  if (!isset ($result['max-icon']) ) {$result['max-icon'] = '000';}
	        $group++;
		ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
	}
} // eof mtr_get_conditions
# ------------------------------------------------------------------------------
function mtr_get_cloud_cover($part) {# Decodes cloud cover information.
# This function maybe called several times to decode all cloud layer observations.
# Only the last layer is saved.
# Format is 
#       SKC or CLR for clear skies,
#       cccnnn where ccc = 3-letter code and nnn = altitude of cloud layer in hundreds of feet.
# 'VV' seems to be used for very low cloud layers. (Other conversion factor: 1 m = 3.28084 ft)
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_cloud_cover';
	static $cloudCode = array(  
	        'SKC' => array ('nr' => 000,	'txt' =>'Clear'),
	        'CLR' => array ('nr' => 000,	'txt' =>'Clear'),
		'FEW' => array ('nr' => 100,	'txt' =>'Few Clouds'),
		'FW'  => array ('nr' => 100,	'txt' =>'Few Clouds'),
		'SCT' => array ('nr' => 200,	'txt' =>'Partly Cloudy'),
		'BKN' => array ('nr' => 300,	'txt' =>'Mostly Cloudy'),
		'BNK' => array ('nr' => 300,	'txt' =>'Mostly Cloudy'),
		'BK'  => array ('nr' => 300,	'txt' =>'Mostly Cloudy'),
		'OVC' => array ('nr' => 400,	'txt' =>'Overcast'),
		'NSC' => array ('nr' => 200,	'txt' =>'Partly Cloudy'),       // official designation >No significant clouds< we map to Partly Cloudy
                'NCD' => array ('nr' => 000,	'txt' =>'Clear'),               // official designation >No cloud detected< we map to Clear   
		'TCU' => array ('nr' => 000,	'txt' =>'Thunderstorm'),        // official designation >Towering Cumulus< we map to Thunderstorm
		'CB'  => array ('nr' => 000,	'txt' =>'Thunderstorm'),        // official designation >Cumulonimbus< we map to Thunderstorm
		'VV'  => array ('nr' => 400,	'txt' =>'Overcast')   );
	static $max_cloud = '-1';
	static $txt_cloud = '';
	ws_message (  '<!-- module '.$function.' ('.__LINE__.'): On entry: $part =  >'.$part.'< -->');
	if ($part == 'VV') {
	        $result['covers'][]     = array ('txt' => $cloudCode[$part]['txt'], 'height' => '');      
	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $part = VV without height -->');
		$metarPtr++;
		ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $txt_cloud      = 'Overcast';
		return;
	}
	if ($part == 'SKC' || $part == 'CLR' || $part == 'NSC' || $part == 'NCD'|| $part == 'TCU'|| $part == 'CB') {
		$result['covers'][]     = array ('txt' => $cloudCode[$part]['txt'], 'height' => '');   
		$nr             = $cloudCode[$part]['nr'];
		if ($nr > $max_cloud) {
		        $max_cloud      = $nr;
		        $txt_cloud      = $cloudCode[$part]['txt'];
		} 
		$metarPtr++;
		ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
		return;
	}
	if (preg_match('/^([A-Z]{2,3})([0-9]{3})/',$part,$pieces)) {  // codes for CB and TCU are ignored
                $key            = $pieces[1];
                $altitude       = (integer) 100 * $pieces[2];  // units are feet
                $result['covers'][]     = array ('txt' => $cloudCode[$key]['txt'], 'height' => $altitude);
                $nr             = $cloudCode[$key]['nr'];
                if ($nr > $max_cloud) {
                        $max_cloud = $nr;
 		        $txt_cloud = $cloudCode[$key]['txt'];                       
                }
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                return;     
        }
        if ($max_cloud <> '-1') {
                if ($txt_cloud <> '') {
                        $result['covers_max'] = $txt_cloud;
                }
                $result['max-icon']     = $result['max-icon'] + $max_cloud;
                if ($result['max-icon'] == '') {
                        $result['max-icon'] = '000';
                }
        } 
        $max_cloud      = -1;
        $txt_cloud      = '';
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
} // eof mtr_get_cloud_cover
# ------------------------------------------------------------------------------
function mtr_get_temperature($part)  {
# Decodes temperature and dew point information. 
# Relative humidity is calculated. 
# Also, depending on the temperature, Heat Index or Wind  Chill Temperature is calculated.
#
# Format is tt/dd where tt = temperature and dd = dew point temperature.
# All units are in Celsius. 
# A 'M' preceeding the tt or dd indicates a negative temperature. 
#
# Some stations do not report dew point, so the format is tt/ or tt/XX.
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_temperature';
        if (preg_match('/^(M?[0-9]{2})\/(M?[0-9]{2}|[X]{2})?$/',$part,$pieces))  {
                $tempC          = (integer) strtr($pieces[1], 'M', '-');
                $result['temp'] = $tempC;
  
                mtr_calculate_wind_chill();
                
                if (isset($pieces[2]) and strlen($pieces[2]) != 0 && $pieces[2] != 'XX')  {
                        $dewC                   = (integer) strtr($pieces[2], 'M', '-');
                        $result['dewpoint_c']   = $dewC;
	                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Final result dewpoint =  >'.$result['dewpoint_c'].'< -->'); 
                        $rh     = round(100 * pow((112 - (0.1 * $tempC) + $dewC) /  (112 + (0.9 * $tempC)), 8));
                        $result['humidity']     = $rh ;
                        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Final result humidity =  >'.$result['humidity'].'< -->'); 
                        
                        mtr_calculate_heat_index();
                }
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
                return;
        }
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
} // eof mtr_get_temperature
# ------------------------------------------------------------------------------
function mtr_get_altimeter($part)  {  # Decodes   altimeter or barometer information.
# Format is 
#       Annnn where nnnn represents a real number as nn.nn in inches of Hg,
#       Qpppp where pppp = hectoPascals.
        global $metarPtr, $group, $script, $result;
        $function       = $script.'-mtr_get_altimeter';
 
        if (preg_match('/^(A|Q)([0-9]{4})/',$part,$pieces))  {
                if ($pieces[1] == 'A')  {
                        $pressureIN     = substr($pieces[2],0,2) . '.' . substr($pieces[2],2);  //  units are inches Hg,
                        $pressureHPA    = round($pressureIN / 0.02953,1);                       //  convert to hectoPascals
                }  
                else {  $pressureHPA = (integer) $pieces[2];        // units are hectoPascals
                        $pressureIN = round(0.02953 * $pressureHPA,2);  // convert to inches Hg
                }
                $result['slp_hpa']      = $pressureHPA;
                $result['slp_inhg']     = $pressureIN;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Final result pressure in hpa: >'.$result['slp_hpa'].'< in inhg: >'.$result['slp_inhg'].'< -->'); 
                $metarPtr++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $metarPtr set to '.$metarPtr.' -->');
                $group++;
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
                return;
        }  // valid input
        $group++;
        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): $group set to '.$group.' -->');
}
# ------------------------------------------------------------------------------
# SUPPORTING FUNCTIONS 
# ------------------------------------------------------------------------------
function mtr_calculate_heat_index() { 
# Calculate Heat Index based on temperature in F and relative humidity (65 = 65%)
        global $script, $result;
        $function       = $script.'-mtr_calculate_heat_index';
        $tempF          = 32 + (9 *  $result['temp'] / 5);
        $rh             = $result['humidity']; 
        if ($tempF > 79 && $rh > 39) {
                $hiF = -42.379 + 2.04901523 * $tempF + 10.14333127 * $rh - 0.22475541 * $tempF * $rh;
                $hiF += -0.00683783 * pow($tempF, 2) - 0.05481717 * pow($rh, 2);
                $hiF += 0.00122874 * pow($tempF, 2) * $rh + 0.00085282 * $tempF * pow($rh, 2);
                $hiF += -0.00000199 * pow($tempF, 2) * pow($rh, 2);
                $hiF = round($hiF);
                $hiC = round(($hiF - 32) / 1.8);
                $result['heatindex_c']  = $hiC;
                $result['heatindex_f']  = $hiF;
 	        ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Final result heatindex  in C: >'.$result['heatindex_c'].'< F: >'.$result['heatindex_f'].'< -->');   
        }
} // eof mtr_calculate_heat_index
# ------------------------------------------------------------------------------
function mtr_calculate_wind_chill()  {
# Calculate Wind Chill based on temperature in F and wind speed in miles per hour
        global $script, $result;
        $function       = $script.'-mtr_calculate_wind_chill';
#
        $tempF          = 32 + (9 *  $result['temp'] / 5); 
        if (!isset ($result['wind_speed_kt']) ) {return;}
        $windspeed      = 1.15077 *  $result['wind_speed_kt'];  // in mh for windchill calc
        if ($tempF < 51 && $windspeed > 3) {
                $chillF = 35.74 + 0.6215 * $tempF - 35.75 * pow($windspeed, 0.16) +  0.4275 * $tempF * pow($windspeed, 0.16);
                $chillF = round($chillF);
                $chillC = round(($chillF - 32) / 1.8);
                $result['windchill_c']  = $chillC;
                $result['windchill_f']  = $chillF;	        
                ws_message (  '<!-- module '.$function.' ('.__LINE__.'): Final resultwindchill in C: >'.$result['windchill_c'].'< F: >'.$result['windchill_c'].'< -->');     
        } // eo calc windchill 
} // eof mtr_calculate_wind_chill
# ----------------------------------------------------------------------
function ws_metar_curl ($fullurl) {
# load the raw metar at http://weather.noaa.gov/pub/data/observations/metar/stations/XXXX.TXT
        global $script, $error;
        $function       = $script.'-ws_metar_curl';
        ws_message ( '<!-- module '.$function.' ('.__LINE__.'): data loaded from '.$fullurl.'  -->');
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $fullurl);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
        $rawdata = curl_exec ($ch);
	$info	= curl_getinfo($ch);
#	print_r ($info);
	$error  = curl_error($ch);
	curl_close ($ch);
	unset ($ch);
	ws_message ( '<!-- module '.$function.' ('.__LINE__.'): Possible errors >'.$error.'< -->');
        if (empty($rawdata)){
                ws_message ( '<!-- module '.$function.' ('.__LINE__.'): ERROR data empty for '.$fullurl.' -->',true);       
        }
        return $rawdata;
} // eof ws_metar_curl
# ----------------------  version history
# 3.20 2015-09-28 release 2.8 version 
