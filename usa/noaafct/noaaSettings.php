<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { //--self downloader --
   $filenameReal = __FILE__;		# display source of script if requested so
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
$pageName	= 'noaaSettings.php';
$pageVersion	= '3.20 2015-07-16';
#-----------------------------------------------------------------------
# 3.20 2015-07-16 release 2.8 version
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-----------------------------------------------------------------------
# IMPORTANT  THIS IS THE TEMPLATE VERSION - NO STAND-ALONE SUPPORT
#
# ---------------  SETTINGS ---------------------------------------------
# icons
$myImgDir		= './img/';
$myIconsDir		= './img/';
#
$myDefaultIconsDir	= './wsIcons/default_icons/';
$myDefaultIconsSml	= './wsIcons/default_icons_small/';
$myDefaultIconsExt	= 'png';
#
$myNoaaIconsDir   	= $myIconsDir.'NOAA_Icons/';		// not used, replaced by direct load of new icons
$myNoaaIconsSml   	= $myIconsDir.'NOAA_Icons_small/';	// not used, replaced by direct load of new icons
$myNoaaIconsExt   	= 'jpg';
#
$myWindIcons		= './wsIcons/windIcons/';
$myWindIconsSmall	= './wsIcons/windIconsSmall/';
$windIconsExt		= 'png';
#
#$myJavascriptsDir	= $wsmyfolder.'javaScripts/';
$myJavascriptsDir	='./javaScripts/';
$myCacheDir		= './cache/'; // $wsmyfolder.'cache/';
#
# --------------- END OF SETTINGS ----------------------------------------------
if (!isset ($topCount) ) {$topCount = 8;} elseif ($topCount > 10) {$topCount = 10;}
$wsIconWidth 	= '60px';  // width of the icons in the top part.
#
/*
# set the Timezone abbreviation automatically based on $myTimezone;
if (!function_exists('date_default_timezone_set')) {
	 putenv("TZ=" . $myTimezone);
} else {
	 date_default_timezone_set($myTimezone);
}
*/
# colors to use for temp
# temparray 2 starts at -32C, so add 32 to C temp
$tempArray2=array(
'#F6AAB1', '#F6A7B6', '#F6A5BB', '#F6A2C1', '#F6A0C7', '#F79ECD', '#F79BD4', '#F799DB', '#F796E2', '#F794EA', 
'#F792F3', '#F38FF7', '#EA8DF7', '#E08AF8', '#D688F8', '#CC86F8', '#C183F8', '#B681F8', '#AA7EF8', '#9E7CF8', 
'#9179F8', '#8477F9', '#7775F9', '#727BF9', '#7085F9', '#6D8FF9', '#6B99F9', '#68A4F9', '#66AFF9', '#64BBFA', 
'#61C7FA', '#5FD3FA', '#5CE0FA', '#5AEEFA', '#57FAF9', '#55FAEB', '#52FADC', '#50FBCD', '#4DFBBE', '#4BFBAE', 
'#48FB9E', '#46FB8D', '#43FB7C', '#41FB6A', '#3EFB58', '#3CFC46', '#40FC39', '#4FFC37', '#5DFC35', '#6DFC32', 
'#7DFC30', '#8DFC2D', '#9DFC2A', '#AEFD28', '#C0FD25', '#D2FD23', '#E4FD20', '#F7FD1E', '#FDF01B', '#FDDC19', 
'#FDC816', '#FDC816', '#FEB414', '#FEB414', '#FE9F11', '#FE9F11', '#FE890F', '#FE890F', '#FE730C', '#FE730C', 
'#FE5D0A', '#FE5D0A', '#FE4607', '#FE4607', '#FE2F05', '#FE2F05', '#FE1802', '#FE1802', '#FF0000', '#FF0000',
);
#
$arrLookupNoaa = array (		// known noaa icon codes to default icon translation array
'bkn'	=>	'300', 	'nbkn'	=>	'300n',	'nbknfg'=>	'252n',	'few'	=>	'100',	'nfew'	=>	'100n',
'cloudy'=>	'300', 	'ncloudy'=>	'300n',	'fg'	=>	'252',	'nfg'	=>	'252n',	'hazy'	=>	'150', 
'hi_tsra'=>	'342',	'hi_ntsra'=>	'342n',	'ip'	=>	'232',	'nip'	=>  	'232n',	'mist'	=>	'250',
'ovc'	=>	'400',	'novc'	=>	'400n',	'ra'	=>	'211',	'nra'	=>	'211n',	'rasn'	=>	'231',
'nrasn'	=>	'231n',	'ra1'	=>	'901',	'skc'	=>	'100', 	'nskc'	=>	'100n', 'sn'	=>	'121',
'nsn'	=>	'121n',	'sct'	=>	'200',	'nsct'	=>	'200n',	'sctfg'	=>	'251',	'scttsra'=>	'141',
'nscttsra'=>	'141n',	'nsvrtsra'=>	'342n', 'tsra'	=>	'241',	'ntsra'	=>	'241n',	'shra'	=>	'312',
'nshra'	=>	'312n', 'wind'	=>	'600', 	'nwind' =>	'600',	'windyrain' =>	'600', 	'mix' 	=>	'432', 
'nmix'	=>	'432n',	'cold'	=>	'700',	'hot'	=>  	'701',	'fzra'	=>  	'231',	'nfzra' => 	'231n',
'hi_shwrs' =>	'111',	'hi_nshwrs'=>	'111n','raip'	=>	'231',	'nraip'=> 	'231n', 'blizzard'=>'432',
);
#
$repl		= array ('&deg;',' ','/');
#
$torain		= strtolower( str_replace ($repl,'',$uomRain ) );
$fromrain	= 'in';

$totemp		= strtolower( str_replace ($repl,'',$uomTemp) );
$fromtemp	= 'f';

$tobaro		= strtolower( str_replace ($repl,'',$uomBaro) );
$frombaro	= 'inhg';

$towind		= strtolower( str_replace ($repl,'',$uomWind) );
$fromwind	= 'mph';

$tosnow		= strtolower( str_replace ($repl,'',$uomSnow) );
$fromsnow	= 'in';

$todistance	= strtolower( str_replace ($repl,'',$uomDistance) );
$fromdistance	= 'mi';
#
if ($myCharset == 'UTF-8') {$degree  ='°';} else {$degree  = utf8_decode ('°');}
#
$utcDiff 	= date('Z');	// used for graphs timestamps
$srise		= 8;		// will be set correctly based on latitude en longitude
$sset		= 20;		// same
#
# How many degrees difference fromnormal temp for chill / heat, before shown in output
$appTempDiff	= 4;	// for C: 4
if ($totemp == 'F') {$appTempDiff = round(9*$appTempDiff/5);}
#
$minUV		= 1;	//  Min UV level to be shown in hour-table
$minPoP		= 9;	// min Pop % before it will be shown when there is no amount of rain forecasted
#
$logoNWS	= '<a href="http://www.weather.gov/" target="_blank"><img src="http://www.srh.noaa.gov/images/jan/Icons/NWS_Logo.png" alt="logo nws" style="height: 40px;"  /></a>';
#
$wsnoaalang	= $wsmyfolder.'lang/';	// lang files
#-------------------------------------------------------------------------------------
#  Language array construct
ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): Creating lang translate array -->');
$ownTranslate	= true;
$wsnoaafctLOOKUP= array();				// array with FROM and TO languages
$missingTrans	= array();				// array with strings with missing translation requests
$myLangfile		= $wsnoaalang.'noaalanguage-'.$myLang.'.txt';
ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): Trying to load langfile '.$myLangfile.'  -->');
if (file_exists($myLangfile) ) {
	ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): Langfile '.$myLangfile.' loading -->');
	$loaded = $nLanglookup = $skipped = $invalid = 0;
	$lfile 		= file($myLangfile);		// read the file
	foreach ($lfile as $rec) { 
		$loaded++;
		$recin = trim($rec);
		list($type, $item,$translation) = explode('|',$recin . '|||||');
		if ($type <> 'langlookup') {$skipped++; continue;}
		if ($item && $translation) {
			$translation		= trim($translation);
			$item 				= trim($item);
			if ($myCharset <> 'UTF-8') {
				$translation 	= utf8_decode($translation);
			} 
			if ($lower) {
				$translation	= strtolower($translation);
			}						
			$wsnoaafctLOOKUP[$item]  = $translation;
			$nLanglookup++;
		} else {
			$invalid++;
		}  // eo is langlookup
	}  // eo for each lang record
	ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): loaded: '.$loaded.' - skipped: '.$skipped.' - invalid: '.$invalid.' - used: '.$nLanglookup.' entries of file '.$myLangfile.' -->');
} // eo file exist

# --------------------------------------------------------------------------------------
# functions for noaa scripts
#-------------------------------------------------------------------------------------
#  calculate winddir compass
# --------------------------------------------------------------------------------------
function noaaconvertwinddir($value) {
	global $wsDebug;	
	$winddir = $value;
	if (!isset($winddir)) { 
		$return = "---"; 
	} elseif (!is_numeric($winddir)) { 
		$return = $winddir;
	} else {
		$windlabel = array ("North","NNE", "NE", "ENE", "East", "ESE", "SE", "SSE", "South",
		 "SSW","SW", "WSW", "West", "WNW", "NW", "NNW");
		$return = $windlabel[ fmod((($winddir + 11) / 22.5),16) ];
	}
	ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'):  function noaaconvertwinddir in = '.$value.' out =  '.$return.' -->');
	return $return;
}
# --------------------------------------------------------------------------------------
#  convert windspeed
# --------------------------------------------------------------------------------------
function noaaconvertwind($value,$from ='',$to ='') {
	global $fromwind, $towind, $wsDebug;
	if ($to == '') {
		if ($towind == 'mh') {$to = 'mph';}  else {$to = $towind;}
	}
	if ($from == '') {
		$from =	 $fromwind;
	}	
	if ($from == $to) {
		ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): function wuconvertwind: in = speed: '.$value.', unitFrom: '.$from.', unitTo: '.$to.'. No conversion needed -->');
		return $value;
	}
	$amount		=str_replace(',','.',$value);
	$out 		= 0;
	$error		= '';
	$convertArr= array
			   (    "kmh"=> array('kmh' => 1	, 'kts' => 0.5399568034557235	, 'ms' => 0.2777777777777778 	, 'mph' => 0.621371192237334 ),
				"kts"=> array('kmh' => 1.852	, 'kts' => 1 			, 'ms' => 0.5144444444444445 	, 'mph' => 1.1507794480235425),
				"ms" => array('kmh' => 3.6	, 'kts' => 1.9438444924406046	, 'ms' => 1 			, 'mph' => 2.236936292054402 ),
				"mph"=> array('kmh' => 1.609344	, 'kts' => 0.8689762419006479	, 'ms' => 0.44704 		, 'mph' => 1 ));
	if ((  		$from	==='kmh')	|| ($from === 'kts') 	|| ($from === 'ms')	|| ($from === 'mph') ) {
		if ((	$to	==='kmh')	|| ($to	  === 'kts') 	|| ($to   === 'ms')	|| ($to   === 'mph') ) {
			$out = $convertArr[$from][$to];
		}  
	}
	$return 	= round($out*$amount,0);
	if ($error <> '') {
		ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): function wuconvertwind: in = speed: '.$value.', unitFrom: '.$from.' ,unitTo: '.$to.', out = '.$return.' -->');
	}	
	return $return;
} // eof convert windspeed	
#-------------------------------------------------------------------------------------
#    Convert rainfall
# --------------------------------------------------------------------------------------
function noaaconvertrain($value,$xx='',$yy='') {
	global $fromrain, $torain, $wsDebug;
	if ($fromrain == $torain) {
		ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): function wuconvertrain: in = rainfall: '.$value.', unitFrom: '.$fromrain.', unitTo: '.$torain.'. No conversion needed -->');
		return $value;
	}
	$amount		= str_replace(',','.',$value);
	$out 		= 0;	
	$convertArr	= array
			   ("mm"=> array('mm' => 1		,'in' => 0.03937007874015748 	, 'cm' => 0.1 ),
				"in"=> array('mm' => 25.4	,'in' => 1						, 'cm' => 2.54),
				"cm"=> array('mm' => 10		,'in' => 0.3937007874015748 	, 'cm' => 1 )
				);
	if ((  $fromrain ==='mm') || ($fromrain === 'in') || ($fromrain === 'cm') ) {
		if (($torain ==='mm') ||   ($torain === 'in') || ($torain === 'cm') ) {
			$out = $convertArr[$fromrain][$torain];
		}  
	}
	if ($torain == 'mm') {
		$round = 0;
	} elseif ($torain == 'cm') {
		$round = 1;	
	} else {
		$round = 2;	
	}
	$return	= round($out*$amount,$round);
	ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): function noaaconvertrain: in = rainfall: '.$amount.' , unitFrom: '.$fromrain.' ,unitTo: '.$torain.', out = '.$return.' -->');
	return $return;
} // eof convert rainfall
# --------------------------------------------------------------------------------------
#    Convert temperature and clean up input
# --------------------------------------------------------------------------------------
function noaaconvertemp($amount,$xx='',$yy='') {
	global $fromtemp, $totemp, $wsDebug;
	if (isset ($amount)) {
		$amount	= str_replace(',','.',$amount);
		$out 	= $amount*1.0;
	} else {
		$out	= 0;
	}
	$error 	= '';
	if ($fromtemp == $totemp) {return $amount;}
	elseif (($fromtemp == 'c') && ($totemp = 'f')) {$out = 32 +(9*$amount/5);}
	elseif (($fromtemp == 'f') && ($totemp = 'c')) {$out = 5*($amount -32)/9;}
	else { $error	= 'invalid UOM';}
	$return = round($out,1);
	if ($wsDebug || $error 	<> '') {
		if ($amount == '---') {$amount = '- - -';}
		ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): function noaaconvertemp in = temperature: '.$amount." , unitFrom: $fromtemp ,unitTo: $totemp, out = $return -->");
		if ($error <> '') {echo "<!-- ========== $error ============== -->".PHP_EOL;}
	}
	return $return;
} // eof convert temperature
# --------------------------------------------------------------------------------------
#	noaacommontemperature
# --------------------------------------------------------------------------------------
function noaacommontemperature($value,$xx='',$yy=''){
	global $totemp, $tempArray2, $tempSimple;
	$color			= 'red';
	$temp			= round($value);
	if ($totemp == 'c') {								// for the color lookup we need C as unit
		$colorTemp	= $temp + 32;				// first color entry => -32 C
	} else {
		$colorTemp	= round( 5*($value-32)/9 ) + 32;		
	} 
	if (!$tempSimple) {
		if ($colorTemp < 0) {$colorTemp = 0;} elseif ($colorTemp >= count ($tempArray2) )  {$colorTemp = count ($tempArray2) - 1;}
		$color		= $tempArray2[$colorTemp];
		$tempString	= '<span class="myTemp" style="color: '.$color.';" >'.$temp.'&deg;</span>';	
	} else {
		if ($colorTemp <  32) { $color = 'blue'; } else {$color = 'red';}
		$tempString	= '<span class="myTemp" style="font-size: 150%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	}
	return $tempString;
}
#-------------------------------------------------------------------------------------
#	returns Beaufort information based on windspeed 
#-------------------------------------------------------------------------------------
function noaabeaufort ($wind,$usedunit='') {
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
               $wind    = noaaconvertwind($wind,$usedunit,'kts');
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

	ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): function noaabeaufortnr in = winspeed: '.$wind.' , unitFrom: '.$usedunit.
		',  nr = '.$beaufort[0].' color ='.$beaufort[1].', text = '.$beaufort[2].' -->');
	return $beaufort;
} // eof noaabeaufort
#----------------------------------------------------------------------not used yet --
function wsconvertnoaaicon ($icon) {
	global $arrLookupNoaa;
	if (!isset ($arrLookupNoaa[$icon]) ) {
	        ws_message (  '<!-- module noaaSettings.php ('.__LINE__.'): ERROR undifined icon  =>'.$icon.'<= in $arrLookupNoaa -->',true);
	        return $icon;
	}
	else   {return $arrLookupNoaa[$icon];}
}
#-------------------------------------------------------------------------------------
#  Language function
function wsnoaafcttransstr ($string) {
	global $trans,  $wsnoaafctLOOKUP, $missingTrans, $lower;	
	$value	= trim ($string);
	if (!isset ($wsnoaafctLOOKUP[$value]) ) {
		$return	= str_replace ($trans,'',$string);
		$missingTrans[$value]	= $return;	
	} else {
		$value	= $wsnoaafctLOOKUP[$value];
		$return	= $value;
	}
	if ($lower) {$return	= strtolower($return);}
	return $return;	
}
# ----------------------  version history
# 3.20 2015-07-16 release 2.8 version 
