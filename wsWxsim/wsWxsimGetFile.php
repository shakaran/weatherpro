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
$pageName	= 'wsWxsimGetFile.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-03-18';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.01 2015-03-18 2.7 beta release version 
#-----------------------------------------------------------------------------------------
# Convert wxsim output (lastret.txt or latest.csv)  into array for further processing 
#-----------------------------------------------------------------------------------------
# for testing and debugging only  -  set to comment for production versions
# and use option debug=Y on the url to debug in a production environment 
#	
$wxsimDebug	= false; 		// for extra debug info inside html source
#-----------------------------------------------------------------------------------------
#  first get cache filetime if it exist
#
$cacheUOM	= $uomsTo[0].$uomsTo[1].$uomsTo[2].$uomsTo[3].$uomsTo[4].$uomsTo[5];
$from		= array ('/','&deg;',' ','.');
$cacheFile 	= $cacheFolder.str_replace ($from, '',$pageName.'_'.$lang.'_'.$cacheUOM);;
$cacheTime 	= $dateIntOld = 0;
#
if (file_exists($cacheFile)){	
	$cacheTime = filemtime($cacheFile);
}
#  check if input file exist and compare file time with cache
#
if (!file_exists($fileToUse)){
	$string = '<h3 style="text-align; center;">Error  input file not found '.$fileToUse.'<h3>'.PHP_EOL;
	echo $string;
	echo '<!-- '.$string.' -->'.PHP_EOL;
	$wxsimERROR	= true;
	return;
}
$fileTime       = filemtime($fileToUse);
if ($fileTime < $cacheTime) {
	$wsWxsimArray = array();
	echo '<!-- Cached file '.$cacheFile.' will be loaded instead of '.$fileToUse.' filetime: '.$fileTime.' cachetime: '.$cacheTime.' -->'.PHP_EOL;
	$wsWxsimArray   = unserialize(file_get_contents($cacheFile));
	return;
}
echo '<!-- Newer file '.$fileToUse.' will be loaded. filetime: '.$fileTime.' cachetime: '.$cacheTime.' -->'.PHP_EOL;
#
include_once($scriptFolder.'wsConditionsArr.php');	# all known text descriptions for cloud and precipitation conditions
#
$wsWxsimArray 	= array ();
$wxsimFile	= file($fileToUse);			# set in wsWxsimSettings to either the lastret.txt  or the latest.csv
$countLines 	= count($wxsimFile);			# number of lines in input files
# now we check what type of file we are processing
# lastret.txt starts with:   WXSIM - Retrieved Data
# otherwise it is latest.csv which  Year  on first line first position in $wxsimFile[0]
if (substr($wxsimFile[0],0,4) == 'WXSI') {
	$process = 'lastret';
} elseif (substr($wxsimFile[0],0,4) == 'Year') {
	$process = 'latest';
}  else {
	echo '<h3>invalid file fed to the program - this program will fail<h3>';
	echo $wxsimFile[0]; 
	$wxsimERROR	= true;
	exit;
}
if ($process == 'lastret'){
	$separator	= ' ';						# the characters which separate the fields
	$wxsimFile	= preg_replace( '/\,/', '.',  $wxsimFile );	# replace , with . for decimal spaces
	$lineRaw	= preg_replace( '/\s+/', ' ', $wxsimFile[6]) ; 	# remove white space from line 6 with the field names
	$lineNames	= explode ($separator, $lineRaw);
	$lineNames	= array_reverse($lineNames);
	if ($wxsimDebug) { echo '<pre>'.PHP_EOL; print_r ($lineNames); }# line with field names
	$countNames 	= count($lineNames);				# number of fieldnames in lastret.txt  + 2 blank entries
	$countFields	= count($fields);				# number of fieldnames known to the program, array  in wsWxsimSettings
	#check uoms
	$firstUomLine = $countLines - 7;				# uoms are at the end of the lastret.txt file
	if (trim($wxsimFile[$firstUomLine]) ==  'Units:') {$firstUomLine++;} 
	#  check of next line is units otherwise use defaults. some versions it is a line later 
	#                        
	$uomString 	= trim($wxsimFile[$firstUomLine]).'.'.trim($wxsimFile[$firstUomLine + 1]);
	echo '<!-- uoms: '.$uomString.' -->'.PHP_EOL;
	$uomArr         = explode ('.', $uomString);
# print_r ($uomArr);
	$string         = trim( str_replace ('temperature: ','',$uomArr[0]) );  // template ='&deg;C', ='&deg;F'
	if ($string <> 'Celsius') {$uoms[0] = 'F';} else {$uoms[0] = 'C';}
	
	$string         = trim( str_replace ('precipitation: ','',$uomArr[1]) );// template =' mm', =' in'
	if ($string <> 'mm')    {$uoms[1] = 'in';} else {$uoms[1] = 'mm';}
	
	$string         = trim( str_replace ('snow: ','',$uomArr[2]) );		// template =' cm', =' in'
	if ($string <> 'cm')    {$uoms[4] = 'in';} else {$uoms[4] = 'cm';}
	
	$string         = trim( str_replace ('wind: ','',$uomArr[3]) );  	// template =' km/h', =' kts', =' m/s', =' mph'
	if ($string == 'km/hr') 	{$uoms[2] = 'km/h';} 
	elseif ($string == 'mi/hr')	{$uoms[2] = 'mph';}
	elseif ($string == 'knots')	{$uoms[2] = 'kts';}
	else				{$uoms[2] = 'm/s';}
	
	$string         = trim( str_replace ('visibility: ','',$uomArr[6]) );
	if ($string <> 'km')    {$uoms[5] = 'mi';} else {$uoms[5] = 'km';}
} else {
# process = latest
	$pos            = strpos($wxsimFile[0],';');   			// check which separator is used
	if ($pos === false) {$separator = ' ,';} else {$separator = ';';}
#echo '<pre> separator = '.$separator; exit;
	$lineNames 	= explode($separator, $wxsimFile[0]);
	for ($i = 0; $i < count ($lineNames); $i++) {			// first get rid of ( sometekst ) in field names
		$pos    = strpos($lineNames[$i],'(');
		if (!$pos) {continue;}
		$lineNames[$i] = substr($lineNames[$i],0,$pos-1);
	}
	$countNames = count($lineNames);				// number of fieldnames in in latest file
	$lineUoms	= explode($separator, $wxsimFile[1]);
	for ($i = 0; $i < count ($lineUoms); $i++) {			// first get rid of ( sometekst ) in field uom
		$pos 	= strpos($lineUoms[$i],'(');
		if (!$pos) {continue;}
		$lineUoms[$i] = substr($lineUoms[$i],0,$pos-1);
	}
#
	$uomString 	= $wxsimFile[1];
	$errorString 	= 'error retreiving standard data, file seems corrupt, uoms found: '.$uomString;	
	$pos            = strpos($uomString,'deg ');
	if (!$pos) { echo $errorString; exit;}		// no uom line where expected
	$tempFound      = substr($uomString,$pos+4,1);
	if ($tempFound == 'C' || $tempFound == 'F') {
		$uoms[0] = $tempFound;
	} else 	{
		echo $errorString; exit;
	}
	if ( wsFound($uomString,'inches') ) {
		$uoms[1] = $uoms[4] = 'in';
	} else {
		$uoms[1] = 'mm';
		$uoms[4] = 'cm';
	}
	if ( wsFound($uomString,'mi/hr') ) {
		$uoms[2] = 'mph';
	} elseif ( wsFound($uomString,'km/hr') ) {
		$uoms[2] = 'km/h';
	} elseif ( wsFound($uomString,'knots') ) {
		$uoms[2] = 'kts';
	} elseif ( wsFound($uomString,'m/s') ) {
		$uoms[2] = 'm/s';
	} else {
		echo $errorString; exit;
	}
	if ( wsFound($uomString,'mb') ) {
		$uoms[3] = 'mb';
	} else {
		$uoms[3] = 'in';
        }
	if ( wsFound($uomString,'miles') ) {
		$uoms[5] = 'mi';
	} else {
		$uoms[5] = 'km';
	}
	if ($separator <> ';') {
		$wxsimFile	= preg_replace('/\ ,/', ';', $wxsimFile ); 	// replace space+comma with ; which is now the separator for the data
		$separator	= ';';
	}
	$wxsimFile		= preg_replace( '/\,/', '.',  $wxsimFile );  // change decimal , to point
#	print_r($wxsimFile); exit;
	
}  // eo processing lines with names and uoms
# create a lookup table of the  fields this program knows of and could be in the input file
$arrLookup = array();
foreach ($fields as $key => $arr) {
	if ($process == 'lastret') {$name = $arr['nameTxt'];} else {$name = $arr['nameCsv'];}
	$arrLookup[$name] = $key;
} 
for($i=0; $i<$countNames; $i++) {               // loop through all fieldnames in input file to check if we need those fields
	$fieldName = trim($lineNames[$i]);
	if ($fieldName == '') {continue;}	// blank part of line with names
	if (isset ($arrLookup[$fieldName]) ){	// the field from the input file is known to our table
		$key = $arrLookup[$fieldName];
		$fields[$key]['loc'] = $i;      // save location of field in input file
	}
	if ($wxsimDebug) {echo $i.' - '.$name.' - '.$key.PHP_EOL;}
} // eo for checking fields names and saving locations
if ($wxsimDebug) {echo '$fields = <br />'; print_r ($fields);}

if ($fields['tempGrass']['loc'] == 0) {$soilWanted	= false;}  // even if set to display soil page, no input fields override
if ($soilWanted == false) {		        // skip all soil fields if no soil fields wanted tempGrass is not there, lastret.txt field: GRS  latest.csv Grass Temperature
	$fields['tempGrass']['loc'] = $fields['tempSurf']['loc']	= $fields['tempSoil1']['loc']	= $fields['tempSoil2']['loc']	= $fields['tempSoil3']['loc']= 0;
}
# save all field names in use for this input file into output array   $wsWxsimArray
$wsWxsimArray[0]['time']	='time';	// the date-time of each line in lastret.txt
foreach ($fields as $key => $arr) {
	if ($arr['loc'] == 0) {continue;}	// skip fields which are not in the lastret.txt file
	$wsWxsimArray[0][$key]=$key;		// save our field name into array
}
$wsWxsimArray[0]['cond']	='condition';	// the descriptive text of the weathercondition of each line
$wsWxsimArray[0]['condCloud']	='clouds';
$wsWxsimArray[0]['condRain']	='condRain';
#
# loop through data lines in input file 
# lastret.txt - first line:  8  last line contains "Nighttime lows and daytime highs"
# latest.csv  - first line:  3  last line = last line
$eoFileLastret 	= 'Nighttime lows and daytime highs';
$currentTime	= time();
$currentMonth 	= date('m');		// 01  - 12
$currentYear 	= date('Y');		// 2013
$nextYear 	= 1 + date('Y');	// 2014  for december forecasts into next year
$dateTxt	= '';
$dateInt	= 0;
$linesOK	= 1;			// pointer to current line into output data array. element 0 contains the names of the fields
$rainTotal	= 0;			// input file contains total rain in each line, used to calculate rain in period for this line
$oldHour	= 99;			// needed for adapting error in day string in latest.csv if using DST
$oldDay		= 0;

if ($process == 'lastret') {
# calculate timeoffset for input lines.  10:00a M.C.-CLD MOD. FOG    9,7
# when there are 2 lines per hour:time format should be  " 2:00p  2:30p 3:00p  but some times 1:55p  2:25p  2:55p
# when there are 3 lines per hour  2:00p 2:20p 2:40p 3:00p
# what we do is to calculate the minutes difference
# 0 , 20, 30  = ok, all others offset= 60 -/- minutes in first line ; if offset > 30 subtract 30
	$rawMinRight 	= substr($wxsimFile[8],4,1);   // line 8 is first data line position 01234 length 1
	if ($rawMinRight <> '0') {   // not a 10 minute multiple
		$rawMin = substr($wxsimFile[8],3,2);
		$i  = 60 - $rawMin;
		if ($i > 30) {$i = $i - 30;}
		$minuteOffset = $i;
		echo '<!-- $minuteOffset = '.$minuteOffset.' -->'.PHP_EOL;
	}
} else {
# process for latest  watch out for explode character ;  or , $separator
#  2013 , 1 , 1 , 10 ,   = clean hour input
#  2013 , 1 , 1 , 10.5 ,   = clean half hour input
#  2012 , 12 , 31 , 20.917 , = 5 min before hour line
# change , to . ;round up minutes ; difference with minutes = $minuteOffset
	for ($i = 2; $i < 6; $i++) {
		$string			= preg_replace( '/\s+/', ' ', $wxsimFile[$i]) ; // remove white space form first data line (third line in input)
		$arr			= explode($separator, $string);
		$arr2                   = explode ('.',$arr[3].'.0');            
		$raw[$i-2]		= '0.'.$arr2[1];
	}
	$rawMin			= max($raw);	
	$rndMin			= ceil($rawMin);
	$minuteOffset	= $rndMin - $rawMin;

	echo '<!-- $rawMin ='.$rawMin. ' $minuteOffset = '.$minuteOffset.' -->'.PHP_EOL;
	if ($minuteOffset == '0.5') {$minuteOffset = 0;}
# echo '<pre>'; print_r($raw);  exit;
}
for ($i = 2; $i < $countLines ; $i++) {
	$lineRaw = $wxsimFile[$i];
	if ( trim($lineRaw) == '') {continue;} // skip  empty line  and last line on latest.csv
	if ($wxsimDebug) {echo '$i ='.$i.' raw line '.$lineRaw.PHP_EOL;}
# check for end of lines to be processed
	if ( wsFound($lineRaw, $eoFileLastret) ) { break; } 
#
	$lineRawParts = explode ($separator, preg_replace( '/\s+/', ' ', $lineRaw) );
	if ($wxsimDebug) {print_r ($lineRawParts);}
# check to see if this is a date line which contains info like  26 Sep ----------- in the lastret file
	if ($process == 'lastret' && preg_match('|-------|',$lineRaw) ) { 
		if ($wxsimDebug) {echo 'date line'.PHP_EOL;}
		$dayName	= trim($wxsimFile[$i]); // next line contains "   Friday    "
		$dayNumber	= trim($lineRawParts[0]);
		$monthShort	= trim($lineRawParts[1]); 		// is_numeric($lineRawParts[0]) 
		if (is_numeric($monthShort)) {$string = $monthShort; $monthShort = $dayNumber; $dayNumber = $string;}
		$dateTxt	= $dayNumber.' '.$monthShort.' ';
		if ( ($currentMonth == "12") && ($monthShort == "Jan") ) { 
			$dateTxt .= $nextYear; 
		} else { 
			$dateTxt .= $currentYear; 
		}
		$dateInt = strtotime('00:00:00'. $dateTxt);
		echo '<!-- new date '.$dateTxt.' '.date($SITE['timeFormat'],$dateInt) .' integer date: '.$dateInt.' -->'.PHP_EOL;
		continue;
	}
# process all fields in data line
	if ($process == 'lastret') {$sortedData = array_reverse($lineRawParts);} else {$sortedData = $lineRawParts;}
	if ($wxsimDebug) {print_r ($sortedData);}
	if ($process == 'lastret') {
		if (!isset ($sortedData[2]) || !is_numeric($sortedData[2]) ) { 
			if ($wxsimDebug) {echo 'line skipped - not numeric'.PHP_EOL;}
			continue; 
		} // skip non data lines
	}
# 
	if ($process == 'lastret') { // process time which is first 6 characters of a line  " 2:00p"  or "10:30a"
		$rawTime 	= substr($lineRaw,0,6);
		$rawMinutes	= substr($rawTime,3,2) + $minuteOffset;	// correct for input like 2:25p and convert to 2:30p
		$extraHour      = 0;
		if ($rawMinutes > 59) { $rawMinutes = $rawMinutes - 60;  $extraHour  = 1;}
		$rawMinutes     = substr('00'.$rawMinutes,-2,2);
		$rawHour	= substr($rawTime,0,2);
		if(wsFound($rawTime,'a')) { $rawHour = str_replace('12','0',$rawHour);}
		if(wsFound($rawTime,'p')) {
			if($rawHour < 12) { $rawHour = $rawHour + 12;}
		}
		$timeStr 	= $rawHour.':'.$rawMinutes.':00';
		$dateInt 	= strtotime($timeStr. $dateTxt);
		$dateInt 	= $dateInt + 3600 * $extraHour;
		if (!isset ($dateInt_old) ) {$dateInt_old = $dateInt;}
		$difference     = $dateInt - $dateInt_old;
		if ($difference > 23*3600) {$dateInt = $dateInt - 24*3600;}     // to cope with 24 hour error wxsim files
		$dateInt_old    = $dateInt;
# echo '<!-- line '.$i.' $timeStr '.$timeStr.' '.date($SITE['timeFormat'],$dateInt) .' integer date: '.$dateInt.' -->'.PHP_EOL;
	} 
	else 
	{ 
# date is $lineRawParts  Y 0 M 1 D 2 H = left part 3 Min = right part 3
  		$extraHour      = $extraDay  = 0;
# echo PHP_EOL.'<!-- date fields '.$lineRawParts[0].$lineRawParts[1].$lineRawParts[2].$lineRawParts[3];		
		$rawHour	= $lineRawParts[3] + $minuteOffset;
		$rawMinutes	= round(60 * ($rawHour - floor($rawHour)));
# echo ' $rawHour = '.$rawHour.' floor($rawHour) = '.floor($rawHour).'$rawMinutes = '.$rawMinutes.' -->'.PHP_EOL;
		$rawHour        = floor($rawHour);
		if ($rawMinutes > 59) {$rawMinutes = $rawMinutes - 60;$extraHour  = 1;}
# echo '<!-- $rawMinutes = '.$rawMinutes;
		$rawMinutes = substr('00'.$rawMinutes,-2,2);
# echo ' $rawMinutes = '.$rawMinutes.' -->'.PHP_EOL;
		if ($rawHour >= 24) { $rawHour = $rawHour - 24; $extraDay  = 1;}
		$rawHour	= substr('00'.floor($rawHour),-2,2);
		$dateString     = trim($lineRawParts[0]).substr('00'.trim($lineRawParts[1]),-2,2).substr('00'.trim($lineRawParts[2]),-2,2).'T'.$rawHour.$rawMinutes.'00';	
		$oldHour	= $rawHour;
		$dateInt 	= strtotime($dateString);
		$dateInt 	= $dateInt + $extraHour*3600 + $extraDay*24*3600;
		if (!isset ($dateInt_old) ) {$dateInt_old = $dateInt;}
		$difference     = $dateInt - $dateInt_old;
		if ($difference > 23*3600) {$dateInt = $dateInt - 24*3600;}     // to cope with 24 hour error wxsim files
		$dateInt_old    = $dateInt;
# echo '<!-- line '.$i.' $dateString '.$dateString.' '.date($SITE['timeFormat'],$dateInt) .' integer date: '.$dateInt.' -->'.PHP_EOL;
	}
	$wsWxsimArray[$linesOK]['time'] = $dateInt;
	if ($wxsimDebug) {echo 'Time found = '.$rawTime.' converted to '.$timeStr.'<br />intTime - '.$dateInt.' - string time - '.date($SITE['timeFormat'],$dateInt).PHP_EOL;}
	foreach ($fields as $key => $arr) {		// first we process all data fields in this line by looping through our definitions
		if ($arr['loc'] == 0) {continue;}	// skip the fields from our definitions which are not in this line / lastret.txt file
		if (!isset($sortedData[$arr['loc']])){continue;}  // skip missing fields
		$value = trim($sortedData[$arr['loc']]);
		if ($key == 'rain') {				// rain is a cumulative value. get the value for this line
			$value          = $value - $rainTotal;
			$rainTotal      = $sortedData[$arr['loc']];
		}
		if ($key == 'snow') {				// if a snow value is found the snowindicator is set so the snow graph can be displayed
			if ($value <> 0) {$snowfound = true;}
		}		
		$wsWxsimArray[$linesOK][$key]=$value;
	}
	#####
	if ($process == 'lastret') { 			// now process the condition text
		$textClouds     = substr ($lineRaw, 7,9);
		$textPrecip     = substr ($lineRaw, 16,10).' ';
	} else {
		$cnd1	        = $sortedData[$fields['cnd1']['loc']];
		$cnd2	        = $sortedData[$fields['cnd2']['loc']];		
		$textClouds     = str_replace ($cnd2,'',$cnd1);
		$textPrecip     = $cnd2; 
	}
	$wsWxsimArray[$linesOK]['cond'] = $textClouds.$textPrecip;
	if (isset ($wsWxsimArray[$linesOK]['thunder']) ) {
		$wsWxsimArray[$linesOK]['thunder']              = $wsWxsimArray[$linesOK]['thunder'] * 20;
		if ($wsWxsimArray[$linesOK]['thunder'] > 80)    { $wsWxsimArray[$linesOK]['thunder'] = 80;}
	}	
	if (isset ($wsWxsimArray[$linesOK]['rain']) ) 	        { $rain = $wsWxsimArray[$linesOK]['rain'];} 	else {$rain  = 0;}
	
	if (isset ($wsWxsimArray[$linesOK]['skyCover']) )	{ $cloud = $wsWxsimArray[$linesOK]['skyCover'];} 
        else {  if (isset ($wsWxsimArray[$linesOK]['skyCover2']) )	{
			$cloud                                  = $wsWxsimArray[$linesOK]['skyCover2'];
			$wsWxsimArray[$linesOK]['skyCover']     = $cloud;} 
		else {  $cloud                                  = 50;  // no cloudcover fields present in input
		}
	}
	if (isset ($wsWxsimArray[$linesOK]['skyCover2']) )	{ $cloud = $wsWxsimArray[$linesOK]['skyCover2'];}
	
	list ($cloudText, $condText , $condCloud, $condRain) = cleanCondition($textClouds,$textPrecip,$cloud,$rain,$wsWxsimArray[$linesOK]['temp']);
	$wsWxsimArray[$linesOK]['cond']         = $cloudText.'|'.$condText;
	$wsWxsimArray[$linesOK]['condCloud']    = $condCloud;
	$wsWxsimArray[$linesOK]['condRain']	= $condRain;
#
	if (isset ($wsWxsimArray[$linesOK]['tempSoil3']) )	{
		if (!isset ($wsWxsimArray[$linesOK]['tempSoil2']) )	{
			$wsWxsimArray[$linesOK]['tempSoil2']    = $wsWxsimArray[$linesOK]['tempSoil3'];
			$wsWxsimArray[$linesOK]['moist2']       = $wsWxsimArray[$linesOK]['moist3'];			
		}
	}
# if ($linesOK == 2) {print_r($wsWxsimArray);}
	$linesOK++;
} // eo for each data line
#
if (isset ($snowfound) ) {$wsWxsimArray[0]['snow'] = 1;}  else {$wsWxsimArray[0]['snow'] = 0;}
# print_r ($wsWxsimArray);
#
# 				adapt uom input to default uom of website
$arrLabels 	= $wsWxsimArray[0];
if ($wxsimDebug) { print_r ($arrLabels); }
$arrConvert = array();
foreach ($arrLabels as $key ) {  // decide which colomns should be converted; item 0 and 1 are time and cond
	if (!isset ($fields[$key]['unit']) ) {continue;}
	$number = $fields[$key]['unit'];		//  0 - 5 for array $uoms $uomsTo
	if (!is_numeric($number)) {continue;}
	$from	= $uoms[$number];	
	$to	= $uomsTo[$number];
	$string = '<br />key = '.$key.' number = '.$number.' from = '.$from.' to = '.$to;	
	if ($to <> $from) {
		$string .= ' will be converted ';
		$arrConvert[]=$key;
	} // to from not equal
	if ($wxsimDebug) {echo $string;}
} // eo for to find which fields should be converted
if (count($arrConvert) == 0) {
	echo '<!-- UOM no conversion of input needed -->'.PHP_EOL;} 
else {  echo '<!-- '; 
	for ($n=0; $n < count($arrConvert); $n++){
		$number		= $fields[$arrConvert[$n]]['unit'];
		$usedunit	= $uoms[$number];
		echo '<!-- UOM conversion from = '.$uoms[$number].' to = '.$uomsTo[$number].' -->'.PHP_EOL;
	} 
	for ($i = 1; $i < $linesOK; $i++) {
		for ($n=0; $n < count($arrConvert); $n++){
			$number		= $fields[$arrConvert[$n]]['unit'];
			$usedunit	= $uoms[$number];
			$amount		= $wsWxsimArray[$i][$arrConvert[$n]];
			switch ($number) {
			case 0:  	// temp
				$wsWxsimArray[$i][$arrConvert[$n]] = wsConvertTemperature($amount, $usedunit);
				break;
			case 1:		// rain
				$wsWxsimArray[$i][$arrConvert[$n]] = wsConvertRainfall($amount, $usedunit);
				break;
			case 2:		// wind	
				$wsWxsimArray[$i][$arrConvert[$n]] = wsConvertWindspeed($amount, $usedunit);
				break;		
			case 3:		// baro	
				$wsWxsimArray[$i][$arrConvert[$n]] = wsConvertBaro($amount, $usedunit);
				break;		
			case 4:		// snow	
				$wsWxsimArray[$i][$arrConvert[$n]] = wsConvertRainfall($amount, $usedunit);
				break;
			case 5:		// distance	
				$wsWxsimArray[$i][$arrConvert[$n]] = wsConvertDistance($amount, $usedunit);
				break;		
			echo '<!-- unsupported conversion in '.$pageName.' -->'.PHP_EOL;
			} // eo switch convert
		}  // eo for every field to be converted
	}  // eo every line
} // eo convert
# save array in cache

if (!file_put_contents($cacheFile, serialize($wsWxsimArray))){   
	echo PHP_EOL."<!-- Could not save data to cache $cacheFile. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
} else {echo "<!-- weatherdata ($cacheFile) saved to cache  -->".PHP_EOL;}

# ------------------- functions to be used scanning input -------------------------
function cleanCondition($textClouds,$textPrecip,$cover,$rain, $temp){
	global $conditionsArr;
	$arrClouds = $conditionsArr[trim($textClouds)]; // array ('text' =>'clear mostly', 'code' => 100, 'cond' => 'sky');
	switch (TRUE) {
		case ($cover < 5): 	
			$clouds = 0;
			if ($rain > 0) {$clouds = 100;}			
			break;
		case ($cover < 25): $clouds = 100; break;
		case ($cover < 50): $clouds = 200; break;
		case ($cover < 80): $clouds = 300; break;
		default: $clouds = 400;		
	}
	if ($arrClouds['code'] > $clouds) {
		echo '<!-- $textClouds = '.$textClouds.' result = '.$arrClouds['code'].' calculated = '.$clouds.' based on $cover = '.$cover.' -->'.PHP_EOL;
		$clouds = $arrClouds['code'];
	}
	$textClouds = $arrClouds['out'];
#	
# echo '<!-- $textClouds = '.$textClouds.' $textPrecip = '.$textPrecip.' -->'.PHP_EOL;
#
	$precip = 0;
	if (trim($textPrecip) == '') {return array ($textClouds, $textPrecip ,$clouds, $precip);}
	#
	$arrPrecip=$conditionsArr[trim($textPrecip)]; 
		//$$conditionsArr['CHNC. DRZL'] = array ('text' =>'drizzle chance of ', 'out' =>'chance of drizzle', 'code' => 10, 'cond' => 'drizzle');
	$textPrecip = $arrPrecip['out'];
#
	if 	(wsFound($textPrecip, 'snow')) 		{$precip = 20;}
	elseif	(wsFound($textPrecip, 'flurries')) 	{$precip = 20;}
	elseif	(wsFound($textPrecip, 'sleet'))		{$precip = 30;}
	elseif	(wsFound($textPrecip, 'rain')) 		{$precip = 10;}
	elseif	(wsFound($textPrecip, 'drizzle')) 	{$precip = 10;}
	elseif	(wsFound($textPrecip, 'showers')) 	{$precip = 10;}	
	elseif	(wsFound($textPrecip, 'fog'))  		{$precip = 50;}
#	
	if (!wsFound($textPrecip, 'frost') && !wsFound($textPrecip, 'dew')) {
		if 	(wsFound($textPrecip, 'light'))		{$precip = $precip + 0;}
		elseif 	(wsFound($textPrecip, 'moderate'))	{$precip = $precip + 1;}
		elseif 	(wsFound($textPrecip, 'heavy'))		{$precip = $precip + 2;}
	}			
	if ($arrPrecip['code'] > $precip) {$precip = $arrPrecip['code'];}
	echo '<!-- '.$textClouds.' '.$clouds.' '.$textPrecip.' '.$precip.' -->'.PHP_EOL;
	return array ($textClouds, $textPrecip ,$clouds, $precip);		
}
?>