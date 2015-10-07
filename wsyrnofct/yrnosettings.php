<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'yrnosettings.php';
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
if (!isset ($insideTemplate)) {
#-----------------------------------------------------------------------
# # # # # Here you have to make the changes for stand-alone use  # # # # 
#
$yrnoID		= 'Belgium/Flanders/Leuven';	// #####        generic adres
$iconsOwn	= false; 		        // #####        use original yrno icons or our general icons (false)
#
$yourArea	= 'yourArea';		        // #####	example	Leuven
$organ		= 'yourStationName';	        // #####			Weerstation Leuven
#
$latitude	= '50.89518';  		        // #####	for Leuven
$longitude	= '4.69741';		        // #####	for Leuven
#
$charset        = 'UTF-8';                      // #####	character set used for this website/script
$lower          = false;                        // #####	convert all texts to lowercase
#
$tempSimple	= false;		        // #####        false = we want colorfull temps;  true = red blue temps
#
#-----------------------------------------------------------------------
# units of measurement UOM  settings     	// #####        set them the same as used on your other webpages
#-----------------------------------------------------------------------
$uomTemp	= '&deg;C';		// ='&deg;C', ='&deg;F'
#$uomTemp	= '&deg;F';

$uomRain	= ' mm';		// =' mm', =' in'
#$uomRain	= ' in';

$uomWind 	= ' km/h';		// =' km/h', =' kts', =' m/s', =' mph'
#$uomWind 	= ' kts';
#$uomWind 	= ' m/s';
#$uomWind 	= ' mph';

$uomBaro	= ' hPa';		// =' hPa', =' mb', =' inHg'
#$uomBaro	= ' mb';
#$uomBaro	= ' inHg';

$uomSnow	= ' cm';		// =' cm', =' in'
#$uomSnow	= ' in';

$uomDistance	= ' km';		// =' km', = ' mi'  used for visibillity 
#$uomDistance	= ' mi';
#-----------------------------------------------------------------------
# date and time settings
#-----------------------------------------------------------------------
$timeFormat	 = 'd-m-Y H:i';	        // 31-03-2012 14:03
#$timeFormat     = 'M j Y g:i a';       // March 31 2012 2:03 pm
$timeOnlyFormat	 = 'H:i';	        // 14:03  (hh=00..23);
#$timeOnlyFormat = 'g:i a';             // 2:03 pm
$hourOnlyFormat	 = 'H';		        // 14  
#$hourOnlyFormat = 'ga';                // 2pm
$dateOnlyFormat	 = 'd-m-Y';	        // 31-03-2013
#$dateOnlyFormat = 'M j Y';             // March 3 2013
$dateLongFormat	 = 'l d F Y';	        // Thursday 3 january 2013
#$dateLongFormat = 'l M j Y';           // Thursday January 3 2013
#
$timezone	= 'Europe/Brussels';	// Time zone for the whole of western europe, 
#                                       // leave it if you do not know your EXACT timezone description
#-----------------------------------------------------------------------
# Multilanguage support 
#-----------------------------------------------------------------------
$lang 		= 'en';		                // default language  to use
#-----------------------W A R N I N G ----------------------------------
}
#	if you want to change anything down here make sure you know what you are doing
#-----------------------W A R N I N G ----------------------------------
#
#-----------------------------------------------------------------------
# directory settings
#-----------------------------------------------------------------------
$cacheDir	= './cache/'; #$scriptDir.'cache/';	        // the retrieved information is cached here
$langDir	= $scriptDir.'lang/';	        // all language files are store here
$javascriptsDir	= './javaScripts/';	// contains the javascripts for graphs
$iconsDir	= $scriptDir.'img/';	        // all icons are stored here in separate folders
$imgDir	        = $scriptDir.'img/';	        // contains images like sun-up  sun-down
#-----------------------------------------------------------------------
# the icon folders 
#-----------------------------------------------------------------------
$iconsXml	= $iconsDir.'yrno_icons/';	// yrno original icons
$iconsXmlSmall	= $iconsDir.'yrno_icons/';
$iconsXmlExt	= '.png';
$iconsDef	= $iconsDir.'default_icons/';	// default KDE icons
$iconsDefSmall	= $iconsDir.'default_icons/';
$iconsDefExt	= '.png';
$iconsWind      = $iconsDir.'wind_icons/';	// wind icons (white ones)
$iconsWindSmall	= $iconsDir.'wind_icons_small/';	
$iconsWindExt	= '.png';
/*	
#-----------------------------------------------------------------------
# set the Timezone abbreviation automatically based on $timezone
#-----------------------------------------------------------------------
if (!function_exists('date_default_timezone_set')) {
	 putenv("TZ=" . $timezone);
} else {
	 date_default_timezone_set($timezone);
}
$tzName	= date("T",time());
echo '<!-- Timezone = '.$tzName.' Time = '.date($timeFormat,time()).' -->'.PHP_EOL;
*/
#-------------------------------------------------------------------------------
#  function  Convert windspeed
#-------------------------------------------------------------------------------
$yrno_convert_wind      = array (
        "kmh"=> array('kmh' => 1	, 'kts' => 0.5399568034557235	, 'ms' => 0.2777777777777778 	, 'mh' => 0.621371192237334 ),
        "kts"=> array('kmh' => 1.852	, 'kts' => 1 			, 'ms' => 0.5144444444444445 	, 'mh' => 1.1507794480235425),
        "ms" => array('kmh' => 3.6	, 'kts' => 1.9438444924406046	, 'ms' => 1 			, 'mh' => 2.236936292054402 ),
        "mh" => array('kmh' => 1.609344	, 'kts' => 0.8689762419006479	, 'ms' => 0.44704 		, 'mh' => 1 ) );
#
function yrnoConvertWind($amount, $from,$to='n/a') {
	global $toWind, $yrno_convert_wind, $wsDebug;
	if ($to == 'n/a') {$to = $toWind;}
	$amount	= str_replace(',','.',$amount); // just to make sure that the value is having a decimal point!
        $out    = 1;
        if ($from <> $to) {$out = $yrno_convert_wind[$from][$to];}
	$return = round($out*$amount);
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): function convert wind input speed: '.$amount.' - unitFrom: '.$from.' - unitTo: '.$to.' - out = '.$return.' -->');	
	return $return;
} // eof convert windspeed
#-------------------------------------------------------------------------------
#  function  Convert baro pressure
#-------------------------------------------------------------------------------
$yrno_convert_baro      = array (
        "mb" 	=> array('mb' => 1	, 'hpa' => 1            , 'mmhg' => 0.75006 	, 'inhg' => 0.02953 ),
        "hpa"	=> array('mb' => 1	, 'hpa' => 1            , 'mmhg' => 0.75006 	, 'inhg' => 0.02953),
        "mmhg"	=> array('mb' => 1.3332	, 'hpa' => 1.3332       , 'mmhg' => 1 		, 'inhg' => 0.03937 ),
        "inhg"	=> array('mb' => 33.864	, 'hpa' => 33.864       , 'mmhg' => 25.4 	, 'inhg' => 1) );
#
function yrnoConvertBaro($amount, $from) {
	global  $toBaro, $yrno_convert_baro, $wsDebug;
	$amount	= str_replace(',','.',$amount); // just to make sure that the value is having a decimal point!
        $out    = 1;
        if ($from <> $toBaro) {$out = $yrno_convert_baro[$from][$toBaro];}
	if ($toBaro == 'inhg') 
	        {$return 	= round($out*$amount,2);}
	else    {$return 	= round($out*$amount);}
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'):  function yrnoConvertBaro input pressure: '.$amount.' - unitFrom: '.$from.' - unitTo: '.$toBaro.' - out = '.$return.' -->');
	return$return;
} // eof convert baropressure
#-------------------------------------------------------------------------------
#  function  Convert rainfall
#-------------------------------------------------------------------------------
$yrno_convert_temp      = array (
        "mm"=> array('mm' => 1		,'in' => 0.03937007874015748 	, 'cm' => 0.1 ),
        "in"=> array('mm' => 25.4	,'in' => 1			, 'cm' => 2.54),
        "cm"=> array('mm' => 10		,'in' => 0.3937007874015748 	, 'cm' => 1 ) );
#
function yrnoConvertRain($amount, $from) {
	global $toRain, $yrno_convert_temp, $wsDebug;
	$amount	= str_replace(',','.',$amount); // just to make sure that the value is having a decimal point!
        $out    = 1;
        if ($from <> $toRain) { $out = $yrno_convert_temp[$from][$toRain];}
	if ($toRain == 'mm' ) {
	        $return = round($out*$amount,1);
	} else {
	        $return = round($out*$amount,2);	
	}
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): function yrnoConvertRain input rain: '.$amount.' - unitFrom: '.$from.' - unitTo: '.$toRain.' - out = '.$return.' -->');
	return $return;
} // eof convert rainfall
#-------------------------------------------------------------------------------
#   function Convert temperature
#-------------------------------------------------------------------------------
function yrnoConvertTemp($amount, $from) {
	global $toTemp, $wsDebug;
	$amount	= str_replace(',','.',$amount); // just to make sure that the value is having a decimal point!
        $out    = 1;
        if ($from <> $toTemp) {
                if ($from == 'c')
                        {$return        = 32 +(9*$amount/5);}
                else    {$return        = 5*($amount -32)/9;}
	} else    {$return = $out * $amount;}
	$return = round($return);
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): function yrnoConvertTemp input temp: '.$amount.' - unitFrom: '.$from.' - unitTo: '.$toTemp.' - out = '.$return.' -->');
	return $return;
} // eof convert temperature
#-------------------------------------------------------------------------------
#  function  Convert distance
#-------------------------------------------------------------------------------
$yrno_convert_wrun = array (
        "km"	=> array('km' => 1			, 'mi' => 0.621371192237	, 'ft' => 3280.83989501 , 'm' => 1000 ),
        "mi"	=> array('km' => 1.609344000000865	, 'mi' => 1			, 'ft' => 5280		, 'm' => 1609.344000000865 ),
        "ft"	=> array('km' => 0.0003048		, 'mi' => 0.000189393939394	, 'ft' => 1		, 'm' => 0.30480000000029017 ),
        "m"	=> array('km' => 0.001			, 'mi' => 0.000621371192237	, 'ft' => 3.28083989501 , 'm' => 1 ) );
#
function yrnoConvertRun($amount, $from) {
	global $toDistance, $yrno_convert_wrun, $wsDebug;
	$amount	= str_replace(',','.',$amount); // just to make sure that the value is having a decimal point!
        $out    = 1; 
        if ($from <> $toDistance) { $out = $yrno_convert_wrun[$from][$toDistance];}
	$return = round($out*$amount);
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'):  function yrnoConvertRun input distance: '.$amount.' - unitFrom: '.$from.' - unitTo: '.$toDistance.' - out = '.$return.' -->');
	return $return;
} // eof convert distance
#-------------------------------------------------------------------------------
#  function language translate
#-------------------------------------------------------------------------------
function yrnotransstr ($string) {
	global $trans,  $yrnoLOOKUP, $missingTrans;	
	$value	= trim ($string);
	if (!isset ($yrnoLOOKUP[$value]) ) {
		$return		        = str_replace ($trans,'',$string);
		$missingTrans[$value]	= $return;
		return $return;	
	} else {
		$value		        = $yrnoLOOKUP[$value];
		return $value;
	}
} 
#-------------------------------------------------------------------------------------
#  function curl load data from web
#-------------------------------------------------------------------------------
function yrnoCurl ($string) {
        $string = str_replace(' ','_',trim($string));
	ws_message ( "<!-- module yrnosettings.php (".__LINE__."):  Weather data loaded from url: $string  -->");
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $string);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);         // relatively long, but wu sometimes need that 1 minute
	$rawData = curl_exec ($ch);
	curl_close ($ch);
	if (empty($rawData)){
		echo "module yrnosettings.php (".__LINE__."): ERROR Weather data loaded from url: $string - FAILED".PHP_EOL;
		return false;
	}
return $rawData;
}  // eo curl function 
#-------------------------------------------------------------------------------------
#  Beaufort information based on windspeed 
#-------------------------------------------------------------------------------------
function yrnobeaufort ($wind,$usedunit='') {
        global $wsDebug;
        $beaufort       = array();      // return array with nr - color - text
        $colors         = array(        // colors for beaufort numbers
	"transparent", "transparent", "transparent", "transparent", "transparent", "transparent", 
	"#FFFF53", "#F46E07", "#F00008", "#F36A6A", "#6D6F04", "#640071", "#650003"
	);	
	$texts          = array(        //  descriptive text for beaufirt scale numbers
	"Calm", "Light air", "Light breeze", "Gentle breeze", "Moderate breeze", "Fresh breeze",
	"Strong breeze", "Near gale", "Gale", "Strong gale", "Storm",
	"Violent storm", "Hurricane"
	);

        if ($usedunit <> '' && $usedunit <> 'kts') {            // convert windspeed
               $wind    = yrnoconvertwind($wind,$usedunit,'kts');
        }
	$windkts = $wind * 1.0;
#
	switch (TRUE) {         	// return a number for the beaufort scale based on wind in knots
		case ($windkts < 1 ):
	 		$beuafortnr = 0;
	 		break;
		case ($windkts <	4 ):
			$beuafortnr = 1;
			break;
		case ($windkts <	7 ):
			$beuafortnr = 2;
			break;
		case ($windkts <  11 ):
			$beuafortnr = 3;
			break;
		case ($windkts <  17 ):
			$beuafortnr = 4;
			break;
		case ($windkts <  22 ):
			$beuafortnr = 5;
			break;
		case ($windkts <  28 ):
			$beuafortnr = 6;
			break;
		case ($windkts <  34 ):
			$beuafortnr = 7;
			break;
		case ($windkts <  41 ):
			$beuafortnr = 8;
			break;
		case ($windkts <  48 ):
			$beuafortnr = 9;
			break;
		case ($windkts <  56 ):
			$beuafortnr = 10;
			break;
		case ($windkts <  64 ):
			$beuafortnr = 11;
			break;
		default:
			$beuafortnr = 12;
			break;
	}  // eo switch
	$beaufort[]  = $beuafortnr;
	$beaufort[]  = $colors[$beuafortnr];
	$beaufort[]  = $texts[$beuafortnr];
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): yrnobeaufort in = windspeed: '.$wind.' , unitFrom: '.$usedunit.
		',  nr = '.$beaufort[0].' color ='.$beaufort[1].', text = '.$beaufort[2].' -->');
	
	return $beaufort;
} // eof yrnobeaufort
#-------------------------------------------------------------------------------------
#  yrno icon setting 
#-------------------------------------------------------------------------------------
function yrnoIcon ($iconIn) {
        global  $iconsOwn, 
                $iconsXml, $iconsXmlSmall, $iconsXmlExt,
                $iconsDef, $iconsDefSmall, $iconsDefExt;
        if ($iconsOwn) {
                $return[]       = $iconsXml.$iconIn.$iconsXmlExt;
                $return[]       = $iconsXmlSmall.$iconIn.$iconsXmlExt;               
        } else {
                $return[]       = $iconsDef.$iconIn.$iconsXmlExt;
                $return[]       = $iconsDefSmall.$iconIn.$iconsXmlExt;                      
        }
        return $return;
}
#-----------------------------------------------------------------------
# Returns whether needle was found in haystack
#-----------------------------------------------------------------------
function scriptFound($haystack, $needle){
$pos    = strpos($haystack, $needle);
   if ($pos === false) {
        return false;
   } else {
        return true;
   }
}
#-------------------------------------------------------------------------------
#  Language array construct  This is NOT a function and only executed once at load of the script !
#-------------------------------------------------------------------------------
ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): Creating lang translate array -->');
if (isset ($_REQUEST['lang']) ) {
        $savelang       = $lang;
        $lang           = trim(substr($_REQUEST['lang'].'xxxx',0,2) );
}

$ownTranslate	= true;
$yrnoLOOKUP     = array();	// array with FROM and TO languages
$missingTrans	= array();	// array with strings with missing translation requests
$langfile	= $langDir.'yrnolanguage-'.$lang.'.txt';
if (!file_exists($langfile) ) {
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): Langfile '.$langfile.' does not exist -->');
	$lang           = $savelang;
	$langfile	= $langDir.'yrnolanguage-'.$lang.'.txt';
}
ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): Trying to load langfile '.$langfile.'  -->');
if (file_exists($langfile) ) {
	ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): Langfile '.$langfile.' loading -->');
	$loaded         = $nLanglookup = $skipped = $invalid = 0;
	$lfile 		= file($langfile);		// read the file
        ws_message (  '<!-- module yrnosettings.php ('.__LINE__.'): Using character set '.$charset.'  -->');
        $doneUTF        = '';
	foreach ($lfile as $rec) { 
		$loaded++;
		$recin = trim($rec);
		list($type, $item,$translation) = explode('|',$recin . '|||||');
		if ($type <> 'langlookup') {$skipped++; continue;}
		if ($item && $translation) {
			$translation	= trim($translation);
			$item 		= trim($item);
			if ($charset <> 'UTF-8')        {
			        $doneUTF        = ' and - done UTF conversion';
			        $translation    = iconv("UTF-8",$charset.'//TRANSLIT', $translation);
			}
			if ($lower) 	                {$translation   = strtolower($translation);}
			$yrnoLOOKUP[$item]  = $translation;
			$nLanglookup++;
		} else {
			$invalid++;
		}  // eo is langlookup
	}  // eo for each lang record
	ws_message ( '<!-- module yrnosettings.php ('.__LINE__.'): loaded: '.$loaded.' - skipped: '.$skipped.' - invalid: '.$invalid.' - used: '.$nLanglookup.' entries of file '.$langfile.$doneUTF.' -->');
} // eo file exist
#-------------------------------------------------------------------------------
# check uoms and make the $uomTo
#-------------------------------------------------------------------------------
# temp
$error          = 'Invalid UOM for ';
$from           = array ('&deg;','/',' ','p','les');
$toTemp         = strtolower(str_replace($from,'',trim($uomTemp) ) );
if ($toTemp <> 'c' && $toTemp <> 'f') { $error  .= 'Temperature: unit used '.$uomTemp.' - ';}
#
$toRain         = strtolower(str_replace($from,'',trim($uomRain) ) );
if ($toRain <> 'mm' && $toRain <> 'in') { $error  .= 'Rain: unit used '.$uomRain.' - ';}
#
$toWind         = strtolower(str_replace($from,'',trim($uomWind) ) );
if ($toWind <> 'kmh' && $toWind <> 'kts' &&
    $toWind <> 'ms' && $toWind <> 'mh') { $error  .= 'Wind: unit used '.$uomWind.' - ';}
#
$toBaro         = strtolower(str_replace($from,'',trim($uomBaro) ) );
if ($toBaro <> 'hpa' && $toBaro <> 'mb' && $toBaro <> 'inhg') { $error  .= 'Pressure: unit used '.$uomBaro.' - ';}
#
$toSnow         = strtolower(str_replace($from,'',trim($uomSnow) ) );
if ($toSnow <> 'cm' && $toSnow <> 'in') { $error  .= 'Snowdepth: unit used '.$uomSnow.' - ';}
#
$toDistance         = strtolower(str_replace($from,'',trim($uomDistance) ) );
if ($toDistance <> 'km' && $toDistance <> 'mi') { $error  .= 'Distance: unit used '.$uomDistance.' - ';}
#
if ($error <> 'Invalid UOM for ') {
        $error .= '<br />Program wil halt. Please change the settings in the '.$pageFile.' script';
        echo $error;
        exit;
}