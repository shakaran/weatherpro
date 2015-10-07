<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage 
	exit; 
}
$pageName		= 'wsReportsFunctions.php';
$pageVersion	= '0.01 2014-05-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-------------------------------------------------------------------------------------
# adapted for wsreports stand-alone
#-------------------------------------------------------------------------------------
#    Convert windspeed
function wsReportConvertWind($amount, $usedunit,$reqUnit='') {
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
	$from 	= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   	= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	$error	= 'invalid UOM';
	if (($from ==='kmh') || ($from === 'kts') || ($from === 'ms') || ($from === 'mh')) {
		if (($to ==='kmh') || ($to === 'kts') || ($to === 'ms') || ($to === 'mh')) {
			$out = $convertArr[$from][$to];
			$error	= '';
			}  
	}
	$return 	= round($out*$amount,1);
	if ($wsDebug) {
		echo "<!-- function wsConvertWindspeed in = speed:$amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- ========== $error ============== -->".PHP_EOL;}
	}	
	return $return;
} // eof convert windspeed
#
#-------------------------------------------------------------------------------------
#    Convert baro pressure
function wsReportConvertBaro($amount, $usedunit,$reqUnit='') {
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
	if (($from ==='mb') || ($from === 'hpa') || ($from === 'mmhg') || ($from === 'inhg')) {
		if (($to ==='mb') || ($to === 'hpa') || ($to === 'mmhg') || ($to === 'inhg')) {
			$out = $convertArr[$from][$to];
			$error	= '';
		}  
	}
	if ($toUnit == "hpa" || $toUnit == "mb" ) {
		$return	= round($out*(float)$amount,1);
	} else {
		$return	= round($out*(float)$amount,2);
	}
	if ($wsDebug) {
		echo "<!-- function wsConvertBaro in = pressure:$amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- ========== $error ============== -->".PHP_EOL;}		
	}
	return$return;
} // eof convert baropressure
#
#-------------------------------------------------------------------------------------
#    Convert rainfall
function wsReportConvertRain($amount, $usedunit,$reqUnit='') {
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
		echo "<!-- function wsConvertRainfall in = rainfall: $amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- ========== $error ============== -->".PHP_EOL;}
	}
	return $return;
} // eof convert rainfall
#
#-------------------------------------------------------------------------------------
#    Convert temperature and clean up input
function wsReportConvertTemp($amount, $usedunit,$reqUnit='') {
	global $SITE, $wsDebug;
	if (isset ($amount)) {
		$amount	= str_replace(',','.',$amount);
		$out 	= $amount*1.0;
	} else {
		$out	= 0;
	}
	if ($reqUnit == '') {$toUnit = $SITE['uomTemp'];} else {$toUnit = $reqUnit;}
	$repl 	= array ('/',' ','&deg;','elsius','�C');
	$with 	= array ('','','','','c');
	$from 	= trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   	= trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if ($from == $to) {$return	= $out;}
	elseif (($from == 'c') && ($to = 'f')) {$out = 32 +(9*$amount/5);}
	elseif (($from == 'f') && ($to = 'c')) {$out = 5*($amount -32)/9;}
	else { $error	= 'invalid UOM';}
	$return = round($out,1);
	if ($wsDebug) {
		if ($amount == '---') {$amount = '- - -';}
		echo "<!-- function wsConvertTemperature in = temperature: $amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if (isset($error)) {echo "<!-- ========== $error ============== -->".PHP_EOL;}
	}
	return $return;
} // eof convert temperature
#
#-------------------------------------------------------------------------------------
#    Convert distance
function wsReportConvertRun($amount, $usedunit,$reqUnit='') {
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
	$convertArr= array
			   ("km"	=> array('km' => 1					, 'mi' => 0.621371192237		, 'ft' => 3280.83989501 		, 'm' => 1000 ),
				"mi"	=> array('km' => 1.609344000000865	, 'mi' => 1						, 'ft' => 5280					, 'm' => 1609.344000000865 ),
				"ft"	=> array('km' => 0.0003048			, 'mi' => 0.000189393939394		, 'ft' => 1					 	, 'm' => 0.30480000000029017 ),
				"m"		=> array('km' => 0.001				, 'mi' => 0.000621371192237		, 'ft' => 3.28083989501 		, 'm' => 1 )
				);
	if (($from ==='km') || ($from === 'mi') || ($from === 'ft') || ($from === 'm')) {
		if (($to ==='km') || ($to === 'mi') || ($to === 'ft') || ($to === 'm')) {
			$out = $convertArr[$from][$to];
			$error	= '';
			}  
	}      // invalid unit
	$return = round($out*$amount,1);
	if ($wsDebug) {
		echo "<!-- function wsConvertDistance in = distance: $amount , unitFrom: $usedunit ,unitTo: $reqUnit, out = $return -->\n";
		if ($error <> '') {echo "<!-- ========== $error ============== -->".PHP_EOL;}
	}	
	return $return;
} // eof convert distance
#
#-------------------------------------------------------------------------------------
#  Language function
function wsReporttransstr ($string) {
	global $trans,  $wsReportLOOKUP, $missingTrans, $lower;	
	$value	= trim ($string);
	if (!isset ($wsReportLOOKUP[$value]) ) {
		$return					= str_replace ($trans,'',$string);
		$missingTrans[$value]	= $return;
		return $return;	
	} else {
		$value					= $wsReportLOOKUP[$value];
		return $value;
	}
} 
#-------------------------------------------------------------------------------------
#  curl function
function wsReportCurl ($string) {
	echo  "<!-- Weather data loaded from url: $string  -->".PHP_EOL;
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $string);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	$rawData = curl_exec ($ch);
	curl_close ($ch);
	if (empty($rawData)){
		echo "<!-- ERROR Weather data loaded from url: $string - FAILED  -->".PHP_EOL;
		return false;
	}
return $rawData;
}  // eo function 
#-------------------------------------------------------------------------------------
#  specific report functions
#-------------------------------------------------------------------------------------
#  generate a compleet td with correct value and color
function tdGenerate ($value) {
	global $numFormat, $round, $noValue, $empty;
	$level	= colorLookup ($value);	 
	if ( ! ($value === $empty || $value === $noValue) ) {
		$value	= round($value, $round);
		$value	= sprintf($numFormat,$value);
	}
	return '<td class="'.$level.'">'.$value.'</td>';
}
#-------------------------------------------------------------------------------------
#  find correct color
function colorLookup ($value) {
	global $levelArr, $$levelArr, $noValue, $empty, $color, $kind;
	if (isset ($color) && $color == false) 	{$color == true; return 'level_nocolor';}
    if ($value === $noValue) {return 'level_novalue';}
    if ($value === 0 && $kind == 'rain') {return 'level_novalue';}
    if ($value === $empty) 	 {return 'level_empty';}
    $limit = count($$levelArr);
    for ($i = 0; $i < $limit; $i++){
    	if ($value <= ${$levelArr}[$i]) {
			return 'level_'.($i);
        }
    }
    return 'level_'.($limit-1);
}
#-------------------------------------------------------------------------------------
#  convert uom
function convertUom ($value) {
	global $uomOut, $uomInput, $wsDebug;
	if ($uomOut == $uomInput) 	{return $value;}
	if     ($uomInput	== 'c'   || $uomInput	== 'f' ) 	{$value	= wsReportConvertTemp($value, $uomInput,$uomOut);} 
	elseif ($uomInput	== 'cm'  || $uomInput	== 'in') 	{$value	= wsReportConvertRain($value, $uomInput,$uomOut);}
	elseif ($uomInput	== 'hpa' || $uomInput	== 'inhg') 	{$value	= wsReportConvertBaro($value, $uomInput,$uomOut);}
	elseif ($uomInput	== 'kmh' || $uomInput	== 'mph') 	{$value	= wsReportConvertWind($value, $uomInput,$uomOut);}
	elseif ($uomInput	== 'km'  || $uomInput	== 'mi') 	{$value	= wsReportConvertRun($value, $uomInput,$uomOut);}
	else {echo 'Program / input error, unknown UOM :'.$uomInput.'. Program halted'; exit;}
	return $value;
}
#-------------------------------------------------------------------------------------
#  Language array construct
echo '<!-- Creating lang translate array -->'.PHP_EOL;
$ownTranslate	= true;
$wsReportLOOKUP = array();				// array with FROM and TO languages
$missingTrans	= array();				// array with strings with missing translation requests
$langfile		= $wuLang.'wulanguage-'.$lang.'.txt';
if (!file_exists($langfile) ) {
	'<!-- Langfile '.$langfile.' does not exist -->'.PHP_EOL;
	$langfile = $wuLang.'wulanguage-en.txt';
}
echo  '<!-- Trying to load langfile '.$langfile.'  -->'.PHP_EOL;
if (file_exists($langfile) ) {
	echo  '<!-- Langfile '.$langfile.' loading -->'.PHP_EOL;
	$loaded = $nLanglookup = $skipped = $invalid = 0;
	$lfile 		= file($langfile);		// read the file
	foreach ($lfile as $rec) { 
		$loaded++;
		$recin = trim($rec);
		list($type, $item,$translation) = explode('|',$recin . '|||||');
		if ($type <> 'langlookup') {$skipped++; continue;}
		if ($item && $translation) {
			$translation	= trim($translation);
			$item 			= trim($item);
#			if ($charset <> 'UTF-8') {$translation = utf8_decode($translation);}
			if ($charset <> 'UTF-8') {$translation = iconv("UTF-8",$charset.'//TRANSLIT', $translation);}
			if ($lower) 			 {$translation = strtolower($translation);}
			$wsReportLOOKUP[$item]  = $translation;
			$nLanglookup++;
		} else {
			$invalid++;
		}  // eo is langlookup
	}  // eo for each lang record
	echo '<!-- loaded: '.$loaded.' - skipped: '.$skipped.' - invalid: '.$invalid.' - used: '.$nLanglookup.' entries of file '.$langfile.' -->'.PHP_EOL;
} // eo file exist