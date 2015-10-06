<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'wuforecast3.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# User settings
#$myfolder	= './wuforecast/';
$javaFolder 	= $myfolder.'javascripts/';		        // highcharts and jquery
$highchartsurl	= $javaFolder.'highcharts.js';
$jqueryurl	= $javaFolder.'jquery.js';

if ($wuicons) {
	$iconsUrl	= $myfolder.'img/wu_icons/';
	$iconsUrlSmall	= $myfolder.'img/wu_icons_small/';
	$iconsExt	= '.gif';  				//  extension of the weather icons 	
}
else {
	$iconsUrl	= $myfolder.'img/wx_icons/';
	$iconsUrlSmall	= $myfolder.'img/wx_icons_small/';
	$iconsExt	= '.png';  				//  extension of the weather icons 	
}
$windUrl	= $myfolder.'img/wind_icons/';
$windIconsSmall	= $myfolder.'img/wind_icons_small/';

$lang		= $myLang;	        // supported languages from wu: http://www.wunderground.com/weather/api/d/documentation.html
$wswufctlang	= $myfolder.'lang/';	// lang files
$location	= '';

$enableCache    = true;			// cache should be anabled when frequent request are made. Keep in mind that the forecast data is only refreshed every 10 - 12 hours
$cachePath	= $myfolder.'cache/';
$cacheTime	= 7200; 		// Cache expiration time Default: 7200 seconds = 2 Hours
$cacheFile	= '';
#
# constants 
# temperature color array   $temparray2 starts at -32C, so add 32 to C temp
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
$utcDiff 	= date('Z');// used for graphs timestamps
# ------------------------------------------------------------------------------
# translation information
# ------------------------------------------------------------------------------
#       known codes to break the wind forecast in smaller chunks
$arrBreak['nl'] ='van';
$arrBreak['en'] ='at';
$arrBreak['de'] ='mit';
$arrBreak['fr'] ='soufflant de';
$arrBreak['it'] =', da';
$arrBreak['dk'] ='på';
#
# translation for foreign winddirs to standard (english) winddirs
$dirs['en']	='|E|E|#|ENE|ENE|#|ESE|ESE|#|East|East|#|N|N|#|NE|NE|#|NNE|NNE|#|NNW|NNW|#|NW|NW|#|North|North|#|S|S|#|SE|SE|#|SSE|SSE|#|SSW|SSW|#|SW|SW|#|South|South|#|W|W|#|WNW|WNW|#|WSW|WSW|#|West|West|';
# wind direction compass headings  Dutch
$dirs['nl']	='|E|O|#|ENE|ONO|#|ESE|OZO|#|East|Oost|#|N|N|#|NE|NO|#|NNE|NNO|#|NNW|NNW|#|NW|NW|#|North|Noord|#|S|Z|#|SE|ZO|#|SSE|ZZO|#|SSW|ZZW|#|SW|ZW|#|South|Zuid|#|W|W|#|WNW|WNW|#|WSW|WZW|#|West|West|';
# wind direction compass headings  German
$dirs['de']	='|E|O|#|ENE|ONO|#|ESE|OSO|#|East|Osten|#|N|N|#|NE|NO|#|NNE|NNO|#|NNW|NNW|#|NW|NW|#|North|Norden|#|S|S|#|SE|SO|#|SSE|SSO|#|SSW|SSW|#|SW|SW|#|South|Süden|#|W|W|#|WNW|WNW|#|WSW|WSW|#|West|Westen|';
# wind direction compass headings  French
$dirs['fr']	='|E|E|#|ENE|ENE|#|ESE|ESE|#|East|Est|#|N|N|#|NE|NE|#|NNE|NNE|#|NNW|NNO|#|NW|NO|#|North|Nord|#|S|S|#|SE|SE|#|SSE|SSE|#|SSW|SSO|#|SW|SO|#|South|Sud|#|W|O|#|WNW|ONO|#|WSW|OSO|#|West|Ouest|';
#
# extra translations  for foreign winddirs. example for dutch  (nl)
#                                        lang  N   E   S   W   North   East   South   West
$windCodes['nl']        = array ('nl','N','O','Z','W','Noord','Oost','Zuid', 'West');
$windCodes['it']        = array ('it','N','E','S','O','Nord', 'Est', 'Sud',  'Ovest');
$windCodes['dk']        = array ('dk','N','Ø','S','V','Nord', 'Øst', 'Syd',  'Vest');     
#$windCodes['??']       = array ('??','N','E','S','W','North','East','South','West');      // change to your new language 
#
if (!isset ($dirs[$lang]) ) {
        if (isset($windCodes[$lang]) ) {
                $windFrom       = array ('0','1','2','3','4','5',   '6',   '7',    '8');        
                $dirs['XX']	='|E|2|#|ENE|212|#|ESE|232|#|East|6|#|N|1|#|NE|12|#|NNE|112|#|NNW|114|#|NW|14|#|North|5|#|S|3|#|SE|32|#|SSE|332|#|SSW|334|#|SW|34|#|South|7|#|W|4|#|WNW|414|#|WSW|434|#|West|8|';
                $text           = str_replace ($windFrom,$windCodes[$lang],$dirs['XX']);
                $dirs[$lang]    = $text;
                echo '<!-- new language '.PHP_EOL.$text.PHP_EOL.' -->'.PHP_EOL;
        }
}

if (!isset ($dirs[$lang]) ) {
	$lang   = $myLang	= 'en';
}
$arr	= explode ("#",$dirs[$lang]);
#echo '<pre>'; print_r($arr); exit;
for ($i = 0; $i < count($arr); $i++) {
	$string	= $arr[$i];
	list ($none, $endir, $wudir) = explode ('|',$string);
	$winddirstoenglish[$wudir] = $endir;
}
# values for graphs
$graphsStop 	= 0;
$graphsStart	= 0;
$graphTempMin	= 100;
$graphTempMax	= -100;
$graphHumMin	= 101;
$graphHumMax	= 0;
$graphRainMax	= 0;
$graphWindMax	= 0;

$trans	        = 'wufct_';
$userLocation	= trim($myLatitude).','.trim($myLongitude);

$script = $myfolder.'wuweather3.php';
ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): loading '.$script.' -->');
if (!include $script) {return;}

if ($returnArray == false || !isset ($returnArray['forecast']) ) {
        echo '<h3 style="color: red; text-align: center;">WU forecast: Invalid / no data returned for part / all of the forecast data - forecast incomplete </h3>';
        ws_message ('<pre>'.print_r($returnArray,true).'</pre>',true);
        return false; 
}    // no good data found - errors displayed
else {  $end_forecast = count($returnArray['forecast']);
        if ($end_forecast < 3 ) {
                echo '<h3 style="color: red; text-align: center;">WU forecast: incomplete data returned for part / all of the forecast data</h3>';  
                ws_message ('<pre>'.print_r($returnArray,true).'</pre>',true);
                return false; 
        }
}

if ($metric)	{$code = 'm';} else {$code = 'e';}

$repl 		= array ('/',' ','p');
$fromwind	= trim(strtolower(str_replace ($repl,'',$returnArray['request'][$code]['uomWind']) ) );
$towind		= trim(strtolower(str_replace ($repl,'',$myWind) ) );
$alloweduoms	= array ('kmh', 'kts', 'ms', 'mh');
if (!in_array($fromwind,$alloweduoms ) ) {echo '<h3>Invalid uom for windspeed: '.$returnArray['request'][$code]['uomWind'].' program halt</h3>'; exit;}
if (!in_array($towind,$alloweduoms ) )   {echo '<h3>Invalid uom for windspeed: '.$myWind.' program halt</h3>'; exit;}

$repl 		= array ('&deg;',' ','/');
$fromtemp	= trim(strtolower(str_replace ($repl,'',$returnArray['request'][$code]['uomTemp']) ) );
$totemp		= trim(strtolower(str_replace ($repl,'',$myTemp) ) );
$alloweduoms	= array ('c', 'f');
if (!in_array($fromtemp,$alloweduoms ) ) {echo '<h3>Invalid uom for temperature: '.$returnArray['request'][$code]['uomTemp'].' program halt</h3>'; exit;}
if (!in_array($totemp,$alloweduoms ) )   {echo '<h3>Invalid uom for temperature: '.$myTemp.' program halt</h3>'; exit;}

$repl 		= array ('/',' ');
$fromrain	= trim(strtolower(str_replace ($repl,'',$returnArray['request'][$code]['uomRain']) ) );
$torain		= trim(strtolower(str_replace ($repl,'',$myRain) ) );
$alloweduoms	= array ('mm', 'in', 'cm');
if (!in_array($fromrain,$alloweduoms ) ) {echo '<h3>Invalid uom for rain: '.$returnArray['request'][$code]['uomRain'].' program halt</h3>'; exit;}
if (!in_array($torain,$alloweduoms ) ) 	 {echo '<h3>Invalid uom for rain: '.$myRain.' program halt</h3>'; exit;}
#
# first some housekeeping
#-------------------------------------------------------------------------------------
#  Language array construct
ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): Creating lang translate array -->');
$ownTranslate	= true;
$wswufctLOOKUP 	= array();		// array with FROM and TO languages
$missingTrans	= array();		// array with strings with missing translation requests
$langfile	= $wswufctlang.'wulanguage-'.$lang.'.txt';
ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): Trying to load langfile '.$langfile.'  -->');
if (file_exists($langfile) ) {
	ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): Langfile '.$langfile.' loading -->');
	$loaded = $nLanglookup = $skipped = $invalid = 0;
	$lfile 	= file($langfile);		// read the file
	foreach ($lfile as $rec) { 
		$loaded++;
		$recin = trim($rec);
		list($type, $item,$translation) = explode('|',$recin . '|||||');
		if ($type <> 'langlookup') {$skipped++; continue;}
		if ($item && $translation) {
			$translation		= trim($translation);
			$item 				= trim($item);
			if ($charset <> 'UTF-8') {
				$translation 	= utf8_decode($translation);
			} 
			if ($lower) {
				$translation	= strtolower($translation);
			}						
			$wswufctLOOKUP[$item]  = $translation;
			$nLanglookup++;
		} else {
			$invalid++;
		}  // eo is langlookup
	}  // eo for each lang record
	ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): loaded: '.$loaded.' - skipped: '.$skipped.' - invalid: '.$invalid.' - used: '.$nLanglookup.' entries of file '.$langfile.' -->');
} // eo file exist
#---------------------------------------------------------------------------
$forecast	= array ();		// will contain all info for tabulare foracst	
$iconArray	= array ();		// will contain the icon forecast
#---------------------------------------------------------------------------
# first we process the wu text forecast.  
# there are 2 times the text forecastst compared to the detailed forcasts
# echo '<pre>'.PHP_EOL; print_r($returnArray); exit;
# echo '<pre>'.PHP_EOL; print_r($returnArray['txt_forecast']);
foreach  ($returnArray['txt_forecast'] as $arr) {
# echo '<pre>'.PHP_EOL; print_r($arr); exit;
        $string_fcttext  = str_replace ('...', ' - ', $arr[$code]['fcttext']);
        if ($lang == 'it') { 
                $string_fcttext = str_replace ('Prob. pioggia','Probabilità',$string_fcttext);
        }
	list ($condition,$temp,$wind,$rain)	= explode ('.',  $string_fcttext.'.....');
	$i				= $arr['period'];
	if (!$lower) {$arr['daypart']   = ucfirst($arr['daypart']);}	
	$forecast[$i]['period']		= $arr['daypart'];
	$forecast[$i]['condition']	= $condition;
	$forecast[$i]['icon_url']	= '<img alt="icon '.$condition.'" src="'.$iconsUrl. $arr['icon'].$iconsExt.'" width="43"/>';
	$arrIconGraph[$i]		= $iconsUrlSmall. $arr['icon'].$iconsExt;
	$forecast[$i]['temp']		= $temp;
	$forecast[$i]['humidity']	= '';
	$forecast[$i]['rain'] 		= trim($rain);
	if (isset ($arrBreak[$lang]) ){
	        $wind			= str_replace($arrBreak[$lang],'<br />',$wind);
	}
	$forecast[$i]['wind']		= $wind;
	$iconArray['period'][$i]	= $arr['daypart'];
	$arr['icon']			= str_replace ('nt_','',$arr['icon']);
	$iconArray['condition'][$i]	= $arr['icon'];	
	$iconArray['icon_url'][$i]	= $forecast[$i]['icon_url'];
#	$iconArray['temp'][$i]		= $temp;	
	$iconArray['rain'][$i]		= trim($rain);
	$iconArray['pop'][$i]		= $arr['pop'];
#	$iconArray['wind'][$i]		= $wind;
	
	
}
# process each detailed forecast, split them over two lines from the text forecast
#echo '<pre>'.PHP_EOL;   print_r($iconArray);  print_r($returnArray['txt_forecast']); exit;
$i = 0;
$graphsStart 				= $returnArray['forecast'][$i]['timestamp'];
foreach  ($returnArray['forecast'] as $arr) {
	$graphLines			= $i;
	$unixTime 			= $arr['timestamp'];
	$graphsDays[]			= 1000 * ($unixTime + $utcDiff);
	$graphsDays[]			= 1000 * ($unixTime + 12*3600 + $utcDiff);	
	$graphsStop			= $unixTime;
	$arrTimeGraph[$graphLines]	= $arr['timestamp'] + $utcDiff+ 6*3600;
	$arrTimeGraph[$graphLines+1]= $arr['timestamp'] + $utcDiff+18*3600;
	$forecast[$i]['period']		.= '<br />';
	$daynr				= date('j', $unixTime);
	$monthname			= wswufcttransstr($trans.date('F', $unixTime));
	if ($monthDay)  {$forecast[$i]['period'] .= $monthname.' '.$daynr;}
	else 		{$forecast[$i]['period'] .= $daynr.' '.$monthname;}
	
	$from	= $to =	 $string	= $iconArray['period'][$i];
	if (!$lower) {$to	        = ucfirst($from);}
	$iconArray['period'][$i]	= $to;
	$iconArray['period'][$i+1]	= str_replace($string,$to.'<br />',$iconArray['period'][$i+1]);
# temp
	$tempH 				= $arr[$code]['tempHigh'];
	$arrTempGraph[$graphLines]	= $tempH;
	if ($tempH > $graphTempMax) {$graphTempMax = $tempH;}
	$tempL 				= $arr[$code]['tempLow'];
	$arrTempGraph[$graphLines+1]    = $tempL;	
	if ($tempL < $graphTempMin) {$graphTempMin = $tempL;}
	$forecast[$i+1]['temp']  	= $tempL.$myTemp;
	$forecast[$i]['temp']  		= $tempH.$myTemp;
	$iconArray['temp'][$i]		= $tempH.$myTemp;
	$iconArray['temp'][$i+1]	= $tempL.$myTemp;
# humid
	$value				= $arr['humidity'];
	$forecast[$i]['humidity']	= $value.'%';
	if ($arr['humidVar'] <> '') {
		$arr['humidVar']	.='<br />('.$arr['humidVar'].'%)';
	}
	if ($value < $graphHumMin) {$graphHumMin = $value;}
	if ($value > $graphHumMax) {$graphHumMax = $value;}
	$arrHumGraph[$graphLines]	= $arr['humidity'];	
# rain
	$rainDay			= wuconvertrain($arr[$code]['rainDay']);
	$arrRainGraph[$graphLines]	= $rainDay;
	$snowDay			= $arr[$code]['snowDay'];
	if ($rainDay > $graphRainMax) {$graphRainMax = $rainDay;}	
	$iconArray['rain'][$i]		= '';
	$iconArray['rain'][$i+1]	= '';
	if ($forecast[$i]['rain'] <> '') {
		$forecast[$i]['rain'] 	.= '<br />'.$rainDay.$myRain;
		if ($snowDay <> 0) {
			$forecast[$i]['rain']	.= ' ('.$snowDay.$mySnow.' '.wswufcttransstr($trans.'snow').')';
		}
		$iconArray['rain'][$i]		= $rainDay.$myRain;
	}
	$rainNight					= wuconvertrain($arr[$code]['rainNight']);
	$arrRainGraph[$graphLines+1]= $rainNight;
	$snowNight					= $arr[$code]['snowDay'];
	if ($rainNight > $graphRainMax) {$graphRainMax = $rainNight;}	
	if ($forecast[$i+1]['rain'] <> '') {
		$forecast[$i+1]['rain']		.= '<br />'.$rainNight.$myRain;
		if ($snowNight <> 0) {
			$forecast[$i]['rain']	.= ' ('.$snowNight.$mySnow.' '.wswufcttransstr($trans.'snow').')';
		}
		$iconArray['rain'][$i+1]	= $rainNight.$myRain;
	}
	$iconArray['windDir'][$i]	= $arr['windDir'];  // 'windDirEn'
	$iconArray['windDirEn'][$i]	= $arr['windDirEn'];
	$value						= wuconvertwind($arr[$code]['wind']);
	$iconArray['wind'][$i]		= $value;
	$arrWindGraph[$graphLines]	= $value;
	if ($value > $graphWindMax) {$graphWindMax = $value;}
	$arrWdirGraph[$graphLines]	= $arr['windDirEn'];
	$i++;
	$i++;
} // end of for each forecast record
#echo '<pre>'; print_r($arrTimeGraph); exit;
$end		= count ($arrTimeGraph);
$graphsData	= '';
for ($graphLines = 0; $graphLines < $end; $graphLines++){
	$graphsData	.= 'tsv['.$graphLines.'] ="';
	$graphsData	.= $arrTimeGraph[$graphLines].'|';
	$graphsData	.= $arrTempGraph[$graphLines].'|';
	if (!isset ($arrHumGraph[$graphLines]) ){
		$graphsData	.=  $arrHumGraph[$graphLines-1].'|';
	} else {
		$graphsData	.= $arrHumGraph[$graphLines].'|';
	}
	if (!isset ($arrWindGraph[$graphLines]) ){
		$graphsData	.= $arrWindGraph[$graphLines-1].'|';
	} else {
		$graphsData	.=  $arrWindGraph[$graphLines].'|';
	}
	if (!isset ($arrWdirGraph[$graphLines]) ){
		$graphsData	.= $arrWdirGraph[$graphLines-1].'|';
	} else {
		$graphsData	.= $arrWdirGraph[$graphLines].'|';
	}
	if (!isset ($arrRainGraph[$graphLines]) ){
		$graphsData	.= '|';
	} else {
		$graphsData	.= $arrRainGraph[$graphLines].'|';
	}
	$graphsData	.= $arrIconGraph[$graphLines].'|";'.PHP_EOL;			
}
$graphNrLines	= 6;
$graphTempMin	= $tempMin = floor ($graphTempMin);  // round down
$graphTempMax	= ceil 	($graphTempMax);  // round up
$stringY = 'temp max: '.$graphTempMax. ' temp min: '.$graphTempMin;
$graphTempStep	= 2* ceil(($graphTempMax - $graphTempMin) / $graphNrLines);
$stringY .= ' temp step: '.$graphTempStep;
$graphTempMax	= $graphTempStep * ceil($graphTempMax/$graphTempStep);
$tempMax		= $graphTempMax;
$tempMin		= $tempMin - $graphTempStep;
$graphTempMax	= $graphTempMax	+  $graphTempStep;
$graphTempMin   = $graphTempMax - (1+ $graphNrLines) * $graphTempStep;
$stringY .= '  temp max: '.$graphTempMax.' temp min: '.$graphTempMin;

$graphIconYvalue = $graphTempMax - ($graphTempStep/2);
#$graphIconYvalue = $graphTempMax;

$stringY .= ' icon: '.$graphIconYvalue. PHP_EOL;
#
$rainMax		=  $graphRainMax;
$graphRainStep	= (ceil (10*$graphRainMax / $graphNrLines))/ 5;
$graphRainMax	= $graphRainStep * $graphNrLines;	
$rainMax		= $rainMax + $graphRainStep;
$stringY .= 'rain max: '.$graphRainMax.'   rain step: '.$graphRainStep.PHP_EOL;
#
$humMax			= $graphHumMax;
$humMin			= $graphHumMin;
$graphhumDiff	= $graphHumMax - $graphHumMin;
if (ceil($graphhumDiff / 15) <= $graphNrLines) {$graphhumStep = 15; } else {$graphhumStep = 20;}
$graphHumMax  = $graphhumStep * (ceil($graphHumMax / $graphhumStep));
if ($graphHumMax < 80) { $graphHumMax = 80;}
$graphHumMin = $graphHumMax - $graphNrLines * $graphhumStep;

$humMax		= $humMax + $graphhumStep;
$humMin		= $humMin - $graphhumStep;
$stringY .='hum max: '.$graphHumMax.' hum min: '.$graphHumMin.PHP_EOL;
#
if ($graphWindMax < $graphNrLines) {$graphWindMax = $graphNrLines;}
$graphWindStep = ceil ($graphWindMax / $graphNrLines);
$graphWindMax  = $graphNrLines * $graphWindStep;
$windMax		= $graphWindMax;
$graphWindMax  = $graphWindMax	* 2;
$graphWindStep = $graphWindStep * 2;
$stringY .='wind max: '.$graphWindMax.' wind step: '.$graphWindStep.PHP_EOL;
ws_message (  '<!-- module wuforecast3.php ('.__LINE__.'): '.PHP_EOL. $stringY. '-->');
#
$graphDaysString = '{';
$daysShort	= array ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
$daysLong	= array ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
for ($i = 0; $i < count ($daysShort); $i++) {
	$graphDaysString .= "'$daysShort[$i]':'".wswufcttransstr($trans.$daysLong[$i])."',";
}
$graphDaysString 	= substr($graphDaysString, 0, strlen($graphDaysString) -1);
$graphDaysString   .= '}';
$graphsStart		= 1000 * ($graphsStart + 6*3600 + $utcDiff);
$graphsStop			= 1000 * ($graphsStop  + 18*3600 + $utcDiff);

$n					= $end - 1;
$ddays		= '';
#
for($i=0 ; $i<count($graphsDays); $i++) { //  shaded background every other day
	if($i ==  count($graphsDays)-1) {     // last incomplete day
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.($graphsStop).', color: "rgba(255, 255, 255, 0.9)" },';
	} else {
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.$graphsDays[$i+1].', color: "rgba(255, 255, 255, 0.9)" },';
	}
	$i++;		// skip next day
}
#echo $ddays; exit;
$uomRainG = $torain;
$uomTempG = ucfirst($totemp);
$uomWindG = $towind;
$negValue = "return '<span style=\"fill: blue;\">' + this.value + '</span>'";
$posValue = "return '<span style=\"fill: red;\">' + this.value + '</span>'";
$treshold = 0;
if ($totemp == 'f') {$treshold = 32;}

$graphPart1='
<script type="text/javascript">
<!--
var days        = '.$graphDaysString.';

var globalX = [{
	type: "datetime",
	min: '.$graphsStart.',
	max: '.$graphsStop.',
	plotBands: ['.substr($ddays, 0, -1).'],
	title: {text: null},
	dateTimeLabelFormats: {day: "%H",hour: "%H"},	
	tickInterval: 12 * 3600 * 1000,	
	gridLineWidth: 0.4,      
	lineWidth: 0,
	labels: {y: 20,style:{fontWeight: \'normal\',fontSize:\'10px\'},
		formatter: function() { 
			var uh = Highcharts.dateFormat("%H", this.value);
			if(uh=="12"){return days[Highcharts.dateFormat("%a", this.value)];}
		}
	}
}];
-->
</script>
';
$graphPart1 .='
<script type="text/javascript">
<!--
var tsv = [];
'.$graphsData.'
var temps = [],
wsps = [],
hums = [],
precs = [],
icos = [];
for (j = 0; j < tsv.length; j++) {
	var line =[];
	line = tsv[j].split("|");
	if(line[0].length > 0 && parseInt(line[0]) != "undefined"){
    	date = 1000 * parseInt(line[0]);
    	d = new Date (date);
		temps.push([date, parseFloat(line[1])]);
		hums.push([date, parseFloat(line[2])]);
		mkr = "'.$windIconsSmall.'" +line[4]+".png";
		str = {x:date,y:parseFloat(line[3]), marker:{symbol:\'url(\'+mkr+\')\'}};
		if (line[4] != \'\') {
			wsps.push(str);
		}
		else {
			wsps.push([date, parseFloat(0.0)]);
		}		
		if (line[6] != \'-\') {
			precs.push([date, parseFloat(line[5])]);
			mkr = line[6];
			str = {x:date,y:'.$graphIconYvalue.', marker:{radius: 4, symbol:\'url(\'+mkr+\')\'}};
			icos.push(str); 
		}
    } // Line contains correct data           
}; // eo for each tsv

var yTitles 	= {color: "#000000", fontWeight: "bold", fontSize:"10px"};
var yLabels 	= {color: "#4572A7", fontWeight: "bold", fontSize:"8px"};
var yLabelsWind = {color: "#1485DC", fontWeight: "bold", fontSize:"8px"};
var yLabelsHum  = {color: "#9ACD32", fontWeight: "bold", fontSize:"8px"};
$(document).ready(function() {
	Highcharts.setOptions({
		chart: {
		    spacingTop:4,
			renderTo: "placeholder",
			defaultSeriesType: "spline",
			backgroundColor: "rgba(255, 255, 255, 0.4)",
			plotBackgroundColor: {linearGradient: [0, 0, 0, 150],stops: [[0, "#ddd"],[1, "rgba(255, 255, 255, 0.4)"]]},
			plotBorderColor: "#88BCCE",
			plotBorderWidth: 0,
			marginRight: 60,
			marginTop: 30,
			marginLeft: 60,
			style: {fontFamily: \'"UbuntuM","Lucida Grande",Verdana,Helvetica,sans-serif\',fontSize:\'11px\'}
		},
		title: {text: ""},
		xAxis: globalX,
		lang: {thousandsSep: ""},
		credits: {enabled: false},
		plotOptions: {
			series: {marker: { radius: 0,states: {hover: {enabled: true}}}},
			spline: {lineWidth: 1.5, shadow: false, cursor: "pointer",states:{hover:{enabled: false}}},
			column: {pointWidth:15},
			areaspline: {lineWidth: 1.5, shadow: false,states:{hover:{enabled: false}}}
		},
		legend: { borderWidth: 0, align: \'center\', verticalAlign: \'top\', rtl: true},
		exporting: {enabled:false},
		tooltip: {
            positioner: function () {return { x: 0};},
			backgroundColor: "#A2D959",
         	borderColor: "#fff",
         	borderRadius: 3,
         	borderWidth: 0,  
         	shared: true,
         	crosshairs: { width: 0.5,color: "#666"},
         	style: {lineHeight: "1.3em",fontSize: "11px",color: "#000"},
         	formatter: function() {
              var s = "" + days[Highcharts.dateFormat(\'%a\', this.x)]+" "+ Highcharts.dateFormat(\'%H:%M\', this.x) +"";
              $.each(this.points, function(i, point) {
				var unit = {
				   "'.wswufcttransstr($trans.'Precipation').'": " '.$uomRainG.'",
				   "'.wswufcttransstr($trans.'Wind').'": " '.$uomWindG.'",
				   "'.wswufcttransstr($trans.'Temperature').'": " '.$uomTempG.'",
				   "'.wswufcttransstr($trans.'Humidity').'": " %",
				   " ": ""
				}[point.series.name];
				if(point.series.name != " ") {
					s += "<br/>"+point.series.name+": <b>"+point.y+unit+"</b>";
				}				     
            });  // eo each
            return s;
         }
      }
	});  // eo set general options
   chartTempData  = new Highcharts.Chart({
        chart: { renderTo: "containerTemp" },		
      	yAxis: [
      	{ lineWidth: 2, 
          gridLineWidth: 0.4, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.', offset: 25,
          title: {text: "'.$uomTempG.'", rotation: 0, align:"high", offset: 4, y: 0, style:yTitles},
          labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$tempMin.' || this.value > '.$tempMax.' ){ return ""; } 
          else
          {if (this.value < '.$treshold.') {'.$negValue.';} else {'.$posValue.';}}
          },style:yLabels}       
      	},
      	{ 
          gridLineWidth: 0, min: 0,max:'.$graphRainMax.',tickInterval:'.$graphRainStep.', offset: 0,
          title: {text: "'.$uomRainG.'", rotation: 0, align:"low", offset: 0,x: -30, y: 10, style:yTitles},
          labels: {align: "left", x: -20, y: 1,  formatter: function() {if (this.value < 0 || this.value > '.$rainMax.' ){ return ""; } else {return this.value;}},style:yLabels}
      	},
      	{ 
          gridLineWidth: 0, min: 0, max: '.$graphWindMax.', tickInterval: '.$graphWindStep.', opposite: true,
          title: {text: "'.$uomWindG.'", rotation:0, align:"low", offset: 5,x: 0, y: 10, style:yTitles},      
          labels: {align: "right",x: 25, y: 1, formatter: function() {if (this.value < 0 || this.value > '.$windMax.' ){ return ""; } else {return this.value;}},style:yLabelsWind}      
      	},
      	{ lineWidth: 2, 
          gridLineWidth: 0, min: '.$graphHumMin.',max: '.$graphHumMax.',tickInterval: '.$graphhumStep.',opposite: true, offset: 30,
          title: {text:"%", rotation: 0, align:"high", offset: 20, y: 0, style:yTitles},        
          labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value < '.$humMin.' || this.value > 99 ){ return ""; } else {return this.value;}},style:yLabelsHum}
        }
       	],
      	series: [

      		{name: "'.wswufcttransstr($trans.'Humidity').'",data: hums,color: "#9ACD32",yAxis: 3},
      		{name: "'.wswufcttransstr($trans.'Precipation').'",data: precs,color:"#4572A7",type:"column",yAxis:1},
      		{name: "'.wswufcttransstr($trans.'Temperature').'",data: temps,color:"#EE4643", threshold: '.$treshold.', negativeColor: "#4572EE"},
      		{name: "'.wswufcttransstr($trans.'Wind').'", data: wsps,  color:"#EEEE00",yAxis:2, marker:{radius:2,symbol:"circle"}},
      		{name: " ",color:"transparent", events:{ legendItemClick:false},data:icos}
      	]
        });  // eo chart    
}); // eo document ready
-->
</script>'.PHP_EOL;	// {name: "wind",     data: wsps,  color:"#EEEE00", marker:{radius:2,symbol:"circle"}},


#-------------------------------------------------------------------------------
# now we are going create the icon part
#-------------------------------------------------------------------------------
#echo '<pre>'.PHP_EOL;   print_r($iconArray);  print_r($returnArray['forecast'][0]); exit;
$htmlicons	= 
'<table class="genericTable">
<tbody>'.PHP_EOL;
$width		= round(100 / $cntIcons);
$before		= '<td style="width: '.$width.'%">';
$after		= '</td>';
$trperiod 	= $tricons = $trcondition = $trtemp = $trrain = $trwind = '<tr>';
$wind		= true;
For ($i = 0; $i < $cntIcons; $i++) {
	$trperiod 	.= $before.$iconArray['period'][$i].$after;
	$tricons 	.= $before.$iconArray['icon_url'][$i].$after;
	$trcondition    .= $before.wswufcttransstr($trans.$iconArray['condition'][$i]).$after;
	$temp		=  $iconArray['temp'][$i];
	$temp		=  myCommonTemperature($temp);
	$trtemp 	.= $before.$temp.$after;
	$value		=  $iconArray['pop'][$i];
	if ($iconArray['rain'][$i] <> '') {
		$string = $value.'% '.wswufcttransstr($trans.'chance of').'<br />'.$iconArray['rain'][$i];
	} 
	else { $string  = '-';}
	$trrain 	.= $before.$string.$after;
	if ($wind) {
		if ($iconArray['windDirEn'][$i] <> '') 
		     {  $image  = '<img src="'.$windUrl.$iconArray['windDirEn'][$i].'.png" style="width: 32px;" alt=""/>';}  
		else {  $image  = '';}
		$trwind .= '<td colspan="2">'.$image.'<br />'.$iconArray['wind'][$i].' '.$myWind.' '.$iconArray['windDir'][$i].$after;
		$wind	= false;
	}
	else {  $wind	= true;}
}
$htmlicons	.= $trperiod.'</tr>'.$tricons.'</tr>'.$trcondition.'</tr>'
			  .$trtemp.'</tr>'.$trrain.'</tr>'.$trwind.'</tr>'.
'</tbody>
</table>'.PHP_EOL;

#-------------------------------------------------------------------------------
# now we are going to create the table part
#-------------------------------------------------------------------------------
$htmltable = 
'<table class="genericTable">
<tbody>
<tr class="table-top">
<th style="width: 10%;">'.wswufcttransstr($trans.'date').'</th>
<th style="width: 25%;">'.wswufcttransstr($trans.'forecast').'</th>
<th colspan="2" style="width: 15%;">'.wswufcttransstr($trans.'icon').'</th>
<th style="width: 10%;">'.wswufcttransstr($trans.'humidity').'</th>
<th style="width: 20%;">'.wswufcttransstr($trans.'rain').'</th>
<th style="width: 20%;">'.wswufcttransstr($trans.'wind').'</th>
</tr>'.PHP_EOL;  
#
$style='row-light';
for ($i=0;$i< count($forecast);$i++) {
	$temp		=  $forecast[$i]['temp'];
	$temp		=  myCommonTemperature($temp);
	$htmltable .= 
'<tr class="'.$style.'">
<td>'.$forecast[$i]['period'].'</td>
<td>'.$forecast[$i]['condition'].'</td>
<td>'.$forecast[$i]['icon_url'].'</td>
<td>'.$temp.'</td>
<td>'.$forecast[$i]['humidity'].'</td>
<td>'.$forecast[$i]['rain'].'</td>
<td>'.$forecast[$i]['wind'].'</td>
</tr>'.PHP_EOL;  
	if ($style == 'row-light')	{									// for odd even lines with different color
		$style='row-dark';} 
	else {
		$style='row-light';}
}  // end for loop
$htmltable      .= '
<tr>
<td colspan="3" style="text-align: right;"><img src="'.$myfolder.'img/WUlogo.png"  alt="wu logo" /></td>
<td colspan="4" style="text-align: left;"><a href="'.$returnArray['request']['link'].'" target="_blank" style="vertical-align: middle;">'.wswufcttransstr($trans.'Weather forecast by').'  WeatherUnderground</a></td>
</tr>
</tbody></table>'.PHP_EOL;

$credit         = '<div class="blockHead">&nbsp;<small>(v3) '.
wswufcttransstr($trans.'Original script by').'&nbsp;<a href="http://leuven-template.eu/?lang=en" target="_blank">Weerstation Leuven</a>&nbsp;'.
wswufcttransstr($trans.'Forecast for').'&nbsp;'.$returnArray['request']['city'].'&nbsp;&nbsp;'.
$returnArray['request']['updated'].'</small></div>'.PHP_EOL;

$headStr        = '<div class="blockHead"> 10 '.wswufcttransstr($trans.'day forecast for').' '.$myWeatherstation.'</div>'.PHP_EOL;
#-------------------------------------------------------------------------------------
#  convert windspeed
function wuconvertwind($value){
	global $fromwind, $towind, $wsDebug;
	if ($fromwind == $towind) {
		if ($wsDebug) {echo '<!-- function wuconvertwind: in = speed: '.$value.', unitFrom: '.$fromwind.', unitTo: '.$towind.'. No conversion needed -->'.PHP_EOL;}
		return $value;
	}
	$amount		=str_replace(',','.',$value);
	$out 		= 0;	
	$convertArr= array
			   ("kmh"=> array('kmh' => 1		, 'kts' => 0.5399568034557235	, 'ms' => 0.2777777777777778 	, 'mh' => 0.621371192237334 ),
				"kts"=> array('kmh' => 1.852	, 'kts' => 1 					, 'ms' => 0.5144444444444445 	, 'mh' => 1.1507794480235425),
				"ms" => array('kmh' => 3.6		, 'kts' => 1.9438444924406046	, 'ms' => 1 					, 'mh' => 2.236936292054402 ),
				"mh" => array('kmh' => 1.609344	, 'kts' => 0.8689762419006479	, 'ms' => 0.44704 				, 'mh' => 1 ));
	if ((  $fromwind ==='kmh') || ($fromwind === 'kts') || ($fromwind === 'ms') || ($fromwind === 'mh') ) {
		if (($towind ==='kmh') || ($towind === 'kts')   || ($towind === 'ms')   || ($towind === 'mh') ) {
			$out = $convertArr[$fromwind][$towind];
		}  
	}
	$return 	= round($out*$amount,0);
	if ($wsDebug) {
		echo '<!-- function wuconvertwind: in = speed: '.$value.', unitFrom: '.$fromwind.' ,unitTo: '.$towind.', out = '.$return.' -->'.PHP_EOL;
	}	
	return $return;
} // eof convert windspeed	
#-------------------------------------------------------------------------------------
#    Convert rainfall
function wuconvertrain($value) {
	global $fromrain, $torain, $wsDebug;
	if ($fromrain == $torain) {
		if ($wsDebug) {echo '<!-- function wuconvertrain: in = amount: '.$value.', unitFrom: '.$fromrain.', unitTo: '.$torain.'. No conversion needed -->'.PHP_EOL;}
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
	if ($wsDebug) {
		echo '<!-- function wuconvertrain: in = rainfall: '.$amount.' , unitFrom: '.$fromrain.' ,unitTo: '.$torain.', out = '.$return.' -->'.PHP_EOL;;
	}
	return $return;
} // eof convert rainfall
#
function myCommonTemperature($value){
	global $metric, $tempArray2, $tempSimple;
	$color			= 'red';
	$temp			= round($value);
	if ($metric) {								// for the color lookup we need C as unit
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
# missing language translations
#print_r ($missingTrans); exit; 
if (count ($missingTrans) > 0) {
	$string	= '';
	echo '<!--  wuforecast missing langlookup entries for lang='.$lang.PHP_EOL;
	foreach ($missingTrans as $key => $val) {
		$string.= "langlookup|$key|$val|".PHP_EOL;
	}
	if (strlen($string) > 0) {
		echo $string;
	}
	echo count($missingTrans).' entries.'.PHP_EOL.'End of missing langlookup entries -->'.PHP_EOL;
}

#-------------------------------------------------------------------------------------
#  Language function
function wswufcttransstr ($string) {
	global $trans,  $wswufctLOOKUP, $missingTrans;	
	$value	= trim ($string);
	if (!isset ($wswufctLOOKUP[$value]) ) {
		$return			= str_replace ($trans,'',$string);
		$missingTrans[$value]	= $return;
		return $return;	
	} else {
		$value			= $wswufctLOOKUP[$value];
		return $value;
	}
	
} 
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
