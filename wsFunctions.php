<?php 	#ini_set('display_errors', 'On'); error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsFunctions.php';
$pageVersion	= '3.02 2015-04-19';   #buoy mods
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString	.='<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->";
#-------------------------------------------------------------------------------------
# version 2.12  remove error mph thanks  Jerry Wilkins 
# version 2.13  error in in unit for baro with CU thanks to jerbo 
# version 2.14  error in in unit for MPH wind
# version 3.00  release version
# release 3.01  non inch in baro one decimal
# release 3.02  color temp added
#-------------------------------------------------------------------------------------
# changes number to required uotput
#-------------------------------------------------------------------------------------
$decTemp        = intval($SITE['decTemp']);
$decBaro 	= intval($SITE['decBaro']);
$decWind 	= intval($SITE['decWind']);
$decPrecip      = intval($SITE['decPrecip']);
$decSnow        = intval($SITE['decSnow']);
#
function wsNumber ($num, $dec=1,$point='',$sep='') {
        global $SITE;
        $amount	= str_replace(',','.',$num);
        if ($point == '') {
                if ($SITE['commaDecimal']) {$commaDecimal = ',';} else {$commaDecimal = '.';} 
        } 
        else  { $commaDecimal = $point; }
        if (is_numeric ($amount) ) {$amount = number_format (round($amount,$dec),$dec,$commaDecimal,$sep);}
        return $amount;
}
#-------------------------------------------------------------------------------------
# 	encodes string into html valid string according to site settings
#-------------------------------------------------------------------------------------
function wsHTMLstring ($string) {
	global $SITE;
	$return = htmlentities ($string, $SITE['htmlVersion'] , $SITE['charset']);
	return $return;
}
#-------------------------------------------------------------------------------------
# 	returns Temp Feels like based on chill heat and temp
#-------------------------------------------------------------------------------------
function wsFeelslikeTemp ($temp,$windchill,$heatindex,$tempUOM) {
	global $SITE, $wsDebug;
# establish the feelslike temperature and return a word describing how it feels
	$HeatWords[0]	= 	array('t' => 99, 	'txt' => 'black', 'bg' => '', 		 'word' =>	'Unknown');
	$HeatWords[1]	= 	array('t' => 54, 	'txt' => 'white', 'bg' => '#BA1928', 'word' =>	'Extreme Heat Danger');
	$HeatWords[2]	= 	array('t' => 50, 	'txt' => 'white', 'bg' => '#E02538', 'word' =>	'Heat Danger');
	$HeatWords[3]	= 	array('t' => 45, 	'txt' => 'black', 'bg' => '#E178A1', 'word' =>	'Extreme Heat Caution');
	$HeatWords[4]	= 	array('t' => 39, 	'txt' => 'black', 'bg' => '#E178A1', 'word' =>	'Extremely Hot');
	$HeatWords[5]	= 	array('t' => 34, 	'txt' => 'white', 'bg' => '#CC6633', 'word' =>	'Uncomfortably Hot');
	$HeatWords[6]	= 	array('t' => 29, 	'txt' => 'white', 'bg' => '#CC6633', 'word' =>	'Hot');
	$HeatWords[7]	= 	array('t' => 21, 	'txt' => 'black', 'bg' => '#CC9933', 'word' =>	'Warm');
	$HeatWords[8]	= 	array('t' => 16, 	'txt' => 'black', 'bg' => '#C6EF8C', 'word' =>	'Comfortable');
	$HeatWords[9]	= 	array('t' => 8, 	'txt' => 'black', 'bg' => '#89B2EA', 'word' =>	'Cool');
	$HeatWords[10]	= 	array('t' => -1, 	'txt' => 'white', 'bg' => '#6699FF', 'word' =>	'Cold');
	$HeatWords[11]	= 	array('t' => -10, 	'txt' => 'white', 'bg' => '#3366FF', 'word' =>	'Uncomfortably Cold');
	$HeatWords[12]	= 	array('t' => -17, 	'txt' => 'white', 'bg' => '#806AF9', 'word' =>	'Very Cold');
	$HeatWords[13]	= 	array('t' => -999, 	'txt' => 'black', 'bg' => '#91ACFF', 'word' =>	'Extreme Cold');
#					
# first clean and convert all temperatures to Centigrade if needed
	$TC = $temp;
	$WC = $windchill;
	$HC = $heatindex;
# decimal: comma to point
	$TC = preg_replace('|,|','.',$temp);
	$WC = preg_replace('|,|','.',$windchill);
	$HC = preg_replace('|,|','.',$heatindex);
# convert F to C if needed
	if (preg_match('|F|i',$tempUOM))  { 
		$TC = wsConvertTemperature($TC, 'f','c');
		$WC = wsConvertTemperature($WC, 'f','c');
		$HC = wsConvertTemperature($HC, 'f','c');
	}
# Feelslike
	if ($TC <= 16.0 ) {		//use WindChill
		$feelslike = $WC;
	} elseif ($TC >=27.0) { //use HeatIndex
		$feelslike = $HC;
	} else {				// use temperature
		$feelslike = $TC;   
	}
	#
	$hcWord = $HeatWords[0];			// default = unknown
	$end	= count ($HeatWords);
	#
	for ($n = 0; $n < $end; $n++) {
		if ($feelslike >= $HeatWords[$n]['t']) {break;}
	}
	if (preg_match('|F|i',$tempUOM))  { // convert C back to F if need be
		$feelslike = wsConvertTemperature($feelslike, 'c','f');
	}
	$feelslike	= round($feelslike,0);	
	$colorTxt	= $HeatWords[$n]['txt'];
	$colorBg	= $HeatWords[$n]['bg'];
	$word		= $HeatWords[$n]['word'];
	$words		= wsHTMLstring (langtransstr($word));
	$hcHTML 	= '<span style="border: solid 1px; color: '.$colorTxt.'; background-color: '.$colorBg.';">&nbsp;'.$words.'&nbsp;</span>';
	if($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsFeelslikeTemp() input T,WC,HI,U = '$temp,$windchill,$heatindex,$tempUOM' cnvt T,WC,HI='$TC,$WC,$HC' feelslike=$feelslike hcWord=$hcHTML -->".PHP_EOL;
	}
	return array($feelslike,$hcHTML);	
} // end of Feelslike
#
#-------------------------------------------------------------------------------------
# 	returns Barotrend text  like "Rising Slowly"  translated to user lang
#-------------------------------------------------------------------------------------
function wsBarotrendText($rawpress,$usedunit='hPa') {
	global $wsDebug;
#	first convert to hPa for comparisons
	$btrend = wsConvertBaro($rawpress, $usedunit,'hPa');
	$btrend = round(10*$btrend);
	switch (TRUE) {
		case (($btrend >= -6) and ($btrend <= 6)):
			$words = "Steady";
			break;
		case (($btrend > 6) and ($btrend < 20)):
			$words = "Rising Slowly";
			break;
		case ($btrend >= 20):
			$words = "Rising Rapidly";
			break;
		case (($btrend < -6) and ($btrend > -20)):
			$words = "Falling Slowly";
			break;
		case ($btrend <= -20):
			$words = "Falling Rapidly";
		break;
	} // end switch
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsBarotrendText in = $rawpress ($usedunit) out = $btrend (hPa) trend = $words -->\n";
	}
	$return = langtransstr($words);
	return $return;
} // eof barotrend text
#
#-------------------------------------------------------------------------------------
#   converts (and translates) degrees to windlabels f.e. 2 degrees to North
function wsConvertWinddir ($degrees) {
	global $wsDebug;	
	$winddir = $degrees;
	if (!isset($winddir)) { 
		$return = "---"; 
	} elseif (!is_numeric($winddir)) { 
		$return = $winddir;
	} else {
		$windlabel = array ("North","NNE", "NE", "ENE", "East", "ESE", "SE", "SSE", "South",
		 "SSW","SW", "WSW", "West", "WNW", "NW", "NNW");
		$return = $windlabel[ fmod((($winddir + 11) / 22.5),16) ];
	}
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsConvertWinddir in = $degrees out = $return -->\n";
	}
	return $return;
} // eof wsConvertWinddir
#
#-------------------------------------------------------------------------------------
#    Convert windspeed
function wsConvertWindspeed($amount, $usedunit,$reqUnit='') {
	global $SITE, $wsDebug;
	$amount		=str_replace(',','.',$amount);
	$out 		= 0;	
	
	if ($reqUnit == '') {$toUnit = $SITE['uomWind'];} else {$toUnit = $reqUnit;}
	$repl = array ('/',' ','p');
	$with = array ('','','');
	$convertArr= array
			   ("kmh"=> array('kmh' => 1		, 'kts' => 0.5399568034557235	, 'ms' => 0.2777777777777778 	, 'mh' => 0.621371192237334 ),
				"kts"=> array('kmh' => 1.852	, 'kts' => 1 					, 'ms' => 0.5144444444444445 	, 'mh' => 1.1507794480235425),
				"ms" => array('kmh' => 3.6		, 'kts' => 1.9438444924406046	, 'ms' => 1 					, 'mh' => 2.236936292054402 ),
				"mh" => array('kmh' => 1.609344	, 'kts' => 0.8689762419006479	, 'ms' => 0.44704 				, 'mh' => 1 ));
	$from 	= trim(str_replace ($repl,$with,strtolower($usedunit)));
	$to   	= trim(str_replace ($repl,$with,strtolower($toUnit)));
	$error	= 'invalid UOM';
	if (($from ==='kmh') || ($from === 'kts') || ($from === 'ms') || ($from === 'mh')) {
		if (($to ==='kmh') || ($to === 'kts') || ($to === 'ms') || ($to === 'mh')) {
			$out = $convertArr[$from][$to];
			$error	= '';
			}  
	}
	$return 	= round($out*$amount,1);
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsConvertWindspeed in = speed:$amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}
	}	
	return $return;
} // eof convert windspeed
#
#-------------------------------------------------------------------------------------
#    Convert baro pressure
function wsConvertBaro($amount, $usedunit,$reqUnit='') {
	global $SITE, $wsDebug;
	$amount		= str_replace(',','.',$amount);
	$out		= 0;	
	if ($reqUnit == '') {$toUnit = $SITE['uomBaro'];} else {$toUnit = $reqUnit;}
	$repl		= array ('/',' ');
	$with		= array ('','');
	$convertArr	= array
			   ("mb" 	=> array('mb' => 1		, 'hpa' => 1 , 		'mmhg' => 0.75006 	, 'inhg' => 0.02953 ),
				"hpa"	=> array('mb' => 1		, 'hpa' => 1 , 		'mmhg' => 0.75006 	, 'inhg' => 0.02953),
				"mmhg"	=> array('mb' => 1.3332	, 'hpa' => 1.3332 , 'mmhg' => 1 		, 'inhg' => 0.03937 ),
				"inhg"	=> array('mb' => 33.864	, 'hpa' => 33.864 , 'mmhg' => 25.4 		, 'inhg' => 1));
	$from 		= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   		= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	$error	= 'invalid UOM';
	if ($from ==='in')  {$from ='inhg';}
	if (($from ==='mb') || ($from === 'hpa') || ($from === 'mmhg') || ($from === 'inhg')  ) {
		if (($to ==='mb') || ($to === 'hpa') || ($to === 'mmhg') || ($to === 'inhg')) {
			$out = $convertArr[$from][$to];
			$error	= '';
		}  
	}
	if ($to == "hpa" || $to == "mb" ) {
		$return	= round($out*(float)$amount,1);
	} else {
		$return	= round($out*(float)$amount,2);
	}
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsConvertBaro in = pressure:$amount , unitFrom: $usedunit ,unitTo: $toUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}		
	}
	return$return;
} // eof convert baropressure
#
#-------------------------------------------------------------------------------------
#    Convert rainfall
function wsConvertRainfall($amount, $usedunit,$reqUnit='') {
	global $SITE, $wsDebug;
	$amount		=str_replace(',','.',$amount);
	$out 		= 0;	
	if ($reqUnit == '') {$toUnit = $SITE['uomRain'];} else {$toUnit = $reqUnit;}
	$repl 		= array ('/',' ');
	$with 		= array ('','');
	$convertArr	= array
			   ("mm"=> array('mm' => 1		,'in' => 0.03937007874015748 	, 'cm' => 0.1 ),
				"in"=> array('mm' => 25.4	,'in' => 1						, 'cm' => 2.54),
				"cm"=> array('mm' => 10		,'in' => 0.3937007874015748 	, 'cm' => 1 )
				);
	$from 	= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   	= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	$error	= 'invalid UOM';
	if ((  $from ==='mm') || ($from === 'in') || ($from === 'cm')) {
		if (($to ==='mm') ||   ($to === 'in') ||   ($to === 'cm')) {
			$out = $convertArr[$from][$to];
			$error = '';
		}  
	}
	if ($to == 'mm') {
		$return	= round($out*$amount,1);
	} else {
		$return	= round($out*$amount,3);
	}	
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsConvertRainfall in = rainfall: $amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}
	}
	return $return;
} // eof convert rainfall
#
#-------------------------------------------------------------------------------------
#    Convert temperature rate of change so 9F = 5C
function wsConvertTempRate($amount, $usedunit,$reqUnit='') {
	global $SITE, $wsDebug;
	if (isset ($amount)) {
		$amount	= str_replace(',','.',$amount);
		$out 	= $amount*1.0;
	} else {
		$out	= 0;
	}
	if ($reqUnit == '') {$toUnit = $SITE['uomTemp'];} else {$toUnit = $reqUnit;}
	$repl 	= array ('/',' ','&deg;','elsius','�');
	$with 	= array ('','','','','');
	$from 	= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   	= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if ($from == $to) {$return	= $out;}
	elseif (($from == 'c') && ($to = 'f')) {$out = 9*$amount/5;}
	elseif (($from == 'f') && ($to = 'c')) {$out = 5*$amount/9;}
	else { $error	= 'invalid UOM';}
	$return = round($out,1);
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsConvertTemperature in = temperature: $amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if (isset($error)) {echo "<!-- module wsFunctions.php: ========== $error invalid UOM -'.$usedunit.'-wsConvertTempRate ============== -->".PHP_EOL;}
	}
	return $return;
} // eof convert temperature
#
#-------------------------------------------------------------------------------------
#    Convert temperature and clean up input
function wsConvertTemperature($amount, $usedunit,$reqUnit='') {
#echo '<!-- $amount = '.$amount.' $usedunit = '.$usedunit.' $reqUnit = '.$reqUnit.'-->'.PHP_EOL;
	global $SITE, $wsDebug;
	if (isset ($amount)) {
		$amount	= str_replace(',','.',$amount);
		$out 	= $amount*1.0;
	} else {
		$out	= 0;
	}
	if ($reqUnit == '') {$toUnit = $SITE['uomTemp'];} else {$toUnit = $reqUnit;}
	$repl 	= array ('&#176;','/',' ','&deg;','elsius','�C');
	$with 	= array (''      ,     '' ,'' ,''     ,''      ,'c');
	$from 	= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   	= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if ($from == $to) {$return	= $out;}
	elseif (($from == 'c') && ($to = 'f')) {$out = 32 +(9*$amount/5);}
	elseif (($from == 'f') && ($to = 'c')) {$out = 5*($amount -32)/9;}
	else { $error	= 'invalid UOM';}
	$return = round($out,1);
	if ($wsDebug) {
		if ($amount == '---') {$amount = '- - -';}
		echo "<!-- module wsFunctions.php: function wsConvertTemperature in = temperature: $amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if (isset($error)) {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}
	}
	return $return;
} // eof convert temperature
#
#-------------------------------------------------------------------------------------
#    Convert distance
function wsConvertDistance($amount, $usedunit,$reqUnit='') {
	global $SITE, $wsDebug;
	if (isset ($amount)) {
		$amount	=str_replace(',','.',$amount);
		$out 	= ((int)$amount)*1.0;
	} else {
		$out=0;
	}
	if ($reqUnit == '') {$toUnit = $SITE['uomDistance'];} else {$toUnit = $reqUnit;}
	$repl 	= array ('/',' ');
	$with 	= array ('','');
	$from 	= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   	= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	$error	= 'invalid UOM';
	$convertArr= array  (
		"km"	=> array('km' => 1			, 'mi' => 0.621371192237	, 'nmi' => 0.540	, 'ft' => 3280.83989501 , 'm' => 1000 ),
		"mi"	=> array('km' => 1.609344000000865	, 'mi' => 1			, 'nmi' => 0.869	, 'ft' => 5280		, 'm' => 1609.344000000865 ),
		"nmi"	=> array('km' => 1.852			, 'mi' => 1.151			, 'nmi' => 1		, 'ft' => 6076.115	, 'm' => 1852 ),
		"ft"	=> array('km' => 0.0003048		, 'mi' => 0.000189393939394	, 'nmi' => 0.000165	, 'ft' => 1		, 'm' => 0.30480000000029017 ),
		"m"	=> array('km' => 0.001			, 'mi' => 0.000621371192237	, 'nmi' => 0.000540	, 'ft' => 3.28083989501 , 'm' => 1 )
		
	);
	if (($from ==='km') || ($from === 'mi') || ($from === 'ft') || ($from === 'm') || ($from === 'nmi') ) {
		if (($to ==='km') || ($to === 'mi') || ($to === 'ft') || ($to === 'm') || ($to === 'nmi') ) {
			$out = $convertArr[$from][$to];
			$error	= '';
			}  
	}      // invalid unit
	$return = round($out*$amount,1);
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsConvertDistance in = distance: $amount , unitFrom: $usedunit ,unitTo: $reqUnit ($toUnit), factor = $out ($return) -->\n";
		if ($error <> '') {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}
	}	
	return $return;
} // eof convert distance
#
#-------------------------------------------------------------------------------------
#    Convert array of meteo  values
function wsConvertArray($kind, $array, $usedunit,$reqUnit) {
	if ($usedunit == $reqUnit) {return($array);}
	foreach ($array as $key => $value) {
		switch ($kind) {
			case 'temp':
				$array[$key] = wsConvertTemperature($value, $usedunit,$reqUnit);
				break;
			case 'wind':
				$array[$key] = wsConvertWindspeed($value, $usedunit,$reqUnit);
				break; 
			case 'rain':
				$array[$key] = wsConvertRainfall($value, $usedunit,$reqUnit);
				break; 
			case 'baro':
				$array[$key] = wsConvertBaro($value, $usedunit,$reqUnit);
				break; 
		} // end switch
	} // end foreach
return ($array);		
} // eof convert array of meteovalues
#
#-------------------------------------------------------------------------------------
#	returns Beaufort Number based on windspeed
#----------------------------------------------------------------------not used yet --
function wsBeaufortNumber ($inWind,$usedunit) {
	global $wsDebug;
	$rawwind 	= $inWind;
	// first convert all winds to knots
	$amount		= str_replace(',','.',$rawwind);
	$wind0kts 	= 0.0;
	if       (preg_match('/kts|knot/i',$usedunit)) {
		$wind0kts = $rawwind * 1.0;
	} elseif (preg_match('/mph/i',$usedunit)) {
		$wind0kts = wsConvertWindspeed($inWind, 'mph','kts');		//  $wind0kts = $rawwind * 0.8689762;
	} elseif (preg_match('/mps|m\/s/i',$usedunit)) {
		$wind0kts = wsConvertWindspeed($inWind, 'ms','kts');		//  $wind0kts = $rawwind * 1.94384449;
	} elseif  (preg_match('/kmh|km\/h/i',$usedunit)) {
		$wind0kts = wsConvertWindspeed($inWind, 'kmh','kts');		//  $wind0kts = $rawwind * 0.539956803;
	} else {
		if ($wsDebug) {
			echo  "<!-- function wsBeaufortNumber unknown input unit '$usedunit' for wind = $rawwind -->".PHP_EOL;
		}
		$wind0kts = $rawwind * 1.0;
	}
	// return a number for the beaufort scale based on wind in knots
	switch (TRUE) {
		case ($wind0kts < 1 ):
	 		$return = 0;
	 		break;
		case ($wind0kts <	4 ):
			$return = 1;
			break;
		case ($wind0kts <	7 ):
			$return = 2;
			break;
		case ($wind0kts <  11 ):
			$return = 3;
			break;
		case ($wind0kts <  17 ):
			$return = 4;
			break;
		case ($wind0kts <  22 ):
			$return = 5;
			break;
		case ($wind0kts <  28 ):
			$return = 6;
			break;
		case ($wind0kts <  34 ):
			$return = 7;
			break;
		case ($wind0kts <  41 ):
			$return = 8;
			break;
		case ($wind0kts <  48 ):
			$return = 9;
			break;
		case ($wind0kts <  56 ):
			$return = 10;
			break;
		case ($wind0kts <  64 ):
			$return = 11;
			break;
		case ($wind0kts >= 64 ):
			$return = 12;
			break;
		default:
			$return = 'invalid';
	}  // eo switch
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsBeaufortNumber in = winspeed: $amount , unitFrom: $usedunit  out = $return -->\n";
	}	
	return $return;
} // eof wsBeaufortNumber
#
#-------------------------------------------------------------------------------------
#	wsBeaufortText returns descriptive text like "Light breeze"
#-------------------------------------------------------------------------------------
function wsBeaufortText ($beaufortnumber) {
	global $wsDebug;
	$B = array( /* Beaufort 0 to 12 in English */
	"Calm", "Light air", "Light breeze", "Gentle breeze", "Moderate breeze", "Fresh breeze",
	"Strong breeze", "Near gale", "Gale", "Strong gale", "Storm",
	"Violent storm", "Hurricane"
	);
	if(isset($B[$beaufortnumber])) {
		$return = $B[$beaufortnumber];
	} else {
		$error  = "Unknown $beaufortnumber Bft";
		$return = "Calm";
	}
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsBeaufortText in = $beaufortnumber: out: $return -->\n";
		if (isset($error) ) {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}
	}
	return $return;

} // wsBeaufortText
#
#-------------------------------------------------------------------------------------
#	wsBeaufortColor returns color code for Bft number >= 6
#-------------------------------------------------------------------------------------
function wsBeaufortColor ($beaufortnumber) {
	global $wsDebug;
	$color = array(
	"transparent", "transparent", "transparent", "transparent", "transparent", "transparent", 
	"#FFFF53", "#F46E07", "#F00008", "#F36A6A", "#6D6F04", "#640071", "#650003"
	);	
	if(isset($color[$beaufortnumber])) {
		$return = $color[$beaufortnumber];
	} else {
		$error  = "Unknown $beaufortnumber Bft";
		$return = "transparent";
	}
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsBeaufortColor in = $beaufortnumber: out: $return -->\n";
		if (isset($error) ) {echo "<!-- module wsFunctions.php: ========== $error ============== -->".PHP_EOL;}
	}
	return $return;

} // end wsBeaufortColor
#-------------------------------------------------------------------------------------
#   wsGenDifference     generate an up/down arrow to show differences/trend
#
#-------------------------------------------------------------------------------------  
function wsGenDifference($nowTemp, $previousTemp, $unit, $textUP, $textDN, $DP=1) {
	global $SITE, $wsDebug;
	$nowTemp		= str_replace(',','.',$nowTemp);
	$previousTemp	= str_replace(',','.',$previousTemp);
	$tnowTemp 		= 1.0*$nowTemp;
	$tpreviousTemp 	        = 1.0*$previousTemp;
	$diff 			= round(($tnowTemp - $tpreviousTemp),3);
	$absDiff 		= abs($diff);
	$diffStr		= number_format($diff,$DP);
	$absDiffStr		= number_format($absDiff,$DP);
	if($SITE['commaDecimal']) {
		$absDiffStr = preg_replace('|\.|',',',$absDiffStr);
	}
	if($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsGenDifference DP=$DP now='$nowTemp':'$tnowTemp' yest='$previousTemp':'$tpreviousTemp' dif='$diff':'$diffStr' absDiff='$absDiff':'$absDiffStr' -->\n";
		echo "<!-- module wsFunctions.php: function wsGenDifference txtUP='$textUP' txtDN='$textDN' Unit='$unit' -->\n";
	}
	if ($diffStr == '0.0') {			// no change
		$image 		= '&nbsp;'; 
	} elseif ($diffStr > '0.0') {		// today is greater 
		$msg 		= sprintf($textUP,$absDiffStr); 
		$image 		= '<img style="height: 16px;" src="'.$SITE['imgAjaxDir'].'rising.gif" alt="'.$msg.'" title="'.$msg.'" />';
	} else {							// today is lesser
		$msg 		= sprintf($textDN,$absDiffStr); 
		$image 		= '<img style="height: 16px;" src="'.$SITE['imgAjaxDir'].'falling.gif" alt="'.$msg.'" title="'.$msg.'"/>';
	}
	if ($unit) {
		return ($nowTemp . $unit . $image);
	} else {
		return $image;
	}
} // eof   wsGenDifference
#-------------------------------------------------------------------------------------  
# 	wsGetUVword     returns description for uv value and a corresponding color 
#
#-------------------------------------------------------------------------------------  
function wsGetUVword ( $amount ) {
	global $wsDebug;
	$uvWords[0]	= 	array('txt' => 'black', 	'bg' => '#FFFFFF', 'word' =>	'None');
	$uvWords[1]	= 	array('txt' => 'black', 	'bg' => '#A4CE6a', 'word' =>	'Low');
	$uvWords[2]	= 	array('txt' => 'black',		'bg' => '#FBEE09', 'word' =>	'Medium');
	$uvWords[3]	= 	array('txt' => 'black', 	'bg' => '#FD9125', 'word' =>	'High');
	$uvWords[4]	= 	array('txt' => 'white', 	'bg' => '#F63F37', 'word' =>	'Very&nbsp;High');
	$uvWords[5]	= 	array('txt' => '#FFFF00', 	'bg' => '#F63F37', 'word' =>	'Extreme');
	$uv = str_replace(',','.',$amount);
	$uv		= $uv*1.0;
	$n 		= 0;
	switch (TRUE) {
		case ($uv == 0):
			$n 	= 0;
			break;
		case (($uv > 0) and ($uv < 3)):
			$n 	= 1;
			break;
		case (($uv >= 3) and ($uv < 6)):
			$n 	= 2;
			break;
		case (($uv >=6 ) and ($uv < 8)):
			$n 	= 3;
			break;
		case (($uv >=8 ) and ($uv < 11)):
			$n 	= 4;
			break;
		case (($uv >= 11) ):
			$n 	= 5;
			break;
	} // end switch
	$return = '<span style="border: solid 1px; color:'.$uvWords[$n]['txt'].'; background-color: '.$uvWords[$n]['bg'].';">&nbsp;' . langtransstr($uvWords[$n]['word']) . '&nbsp;</span>';
	if ($wsDebug) {
		echo "<!-- module wsFunctions.php: function wsgetUVword in = $amount: out: $n -->\n";
	}
	return $return;
} // end wsgetUVword
#
#------------------------------------------------------------------------------------- 
# returns formatted date or time string same as php date function
# but is used to have an intermidiate between weatherprogram dates and website dates
#------------------------------------------------------------------------------------- 
function string_date ($input, $text) {
	global $SITE;
	$date 	= strtotime(substr($input,0,8).'T'.substr($input,8,6));
	$string = date($text,$date);
	return $string;
}
#
#-------------------------------------------------------------------------------------  
# wsmoonWord used by ajax dashboard
#-------------------------------------------------------------------------------------  
function wsmoonWord ($LunarPhasePerc , $LunarAge) {
	$mdaysd		= 1.0 * $LunarAge;
	$mpct 		= 1.0 * $LunarPhasePerc;
	if ($mdaysd <= 29.53/2) {
		$ph 	= "Waxing";		// increasing illumination
		$qtr 	= "First";
	} else { 
		$ph 	= "Waning";		// decreasing illumination
		$qtr 	= "Last";
	}
	switch (true) {
		case ($mpct < 1 ):
			$return = "New Moon";
			break;
		case ($mpct <= 49 ):
			$return = "$ph Crescent";
			break;
		case ($mpct <= 51):
			$return = "$qtr Quarter";
			break;
		case ($mpct <= 99 ):
			$return = "$ph Gibbous";
			break;
		default:
			$return = "Full Moon";
			break;
	}
	return $return;
} // eof wsmoonWord
#-------------------------------------------------------------------------------------
#  weather display fdate conversion
#-------------------------------------------------------------------------------------
function wdDate($time){    // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
#	global $ymd;
	$int = strtotime($time);
	return (date('Ymd').strftime('%H%M%S',$int) );
}
#-------------------------------------------------------------------------------------
#  weather display fdate conversion incl ymd
#-------------------------------------------------------------------------------------
function wdYMD($year,$month,$day,$hour='12',$minute='00',$seconds='00') {  // month and day can be 1 char long!
	$string= $year.substr('00'.$month,-2).substr('00'.$day,-2).substr('00'.$hour,-2).substr('00'.$minute,-2).substr('00'.$seconds,-2);
	return ($string);
}
#------------------------------------------------------------------------------------- 
# strip trailing units from a measurement  i.e. '30.01 in. Hg' becomes '30.01'
#------------------------------------------------------------------------------------- 
function strip_units ($data) {
preg_match('/([\d\,\.\+\-]+)/',$data,$t);
return $t[1];
}  // eof strip units
#------------------------------------------------------------------------------------- 
# strip trailing units from a measurement  i.e. '30.01 in. Hg' becomes '30.01'
#-------------------------------------------------------------------------------------
$ws_commontemp_type     = 'c';
if ($SITE['uomTemp'] == '&deg;F') {$ws_commontemp_type = 'f';} else {$ws_commontemp_type  = 'c';}
#
function ws_commontemp_color($value){
	global $SITE, $ws_commontemp_type;
	$uomTemp                = $SITE['uomTemp'];
	$tempSimple             = $SITE['tempSimple'];
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
	$color                  = 'red';
	if ($ws_commontemp_type == 'c') {				// for the color lookup we need C as unit
		$colorTemp	= round($value) + 32;			// first color entry => -32 C
	} else {
		$colorTemp	= round( (5*($value-32)/9) ) + 32;
	} 
	if (!$tempSimple) {
		if ($colorTemp < 0)                             {$colorTemp = 0;} 
		elseif ($colorTemp >= count ($tempArray2) )     {$colorTemp = count ($tempArray2) - 1;}
		$color		= $tempArray2[$colorTemp];
		$tempString	= '<span class="myTemp" style="color: '.$color.';" >'.$value.$SITE['uomTemp'].'</span>';	
	} else {
		if ($colorTemp <  32) { $color = 'blue'; } else {$color = 'red';}
		$tempString	= '<span class="myTemp" style="color: '.$color.';" >'.$value.$SITE['uomTemp'].'</span>';	
	}
	return $tempString;
}