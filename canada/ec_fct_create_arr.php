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
$pageName	= 'ec_fct_create_arr.php';
$pageVersion	= '3.20 2015-07-26';
#-------------------------------------------------------------------------------
# 3.20 2015-07-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
class ecPlainWeather{
	# public variables
	public $userinputProv	= 'ON';		// 
	public $userinputSite	= 's0000710';	// 
	public $userinputLang	= 'e';		// 	
# private variables
	private $uomTemp	= 'C';		// <temperature unitType="metric" units="C" class="high">17</temperature>
	private $uomWindDir	= 'deg';	// <bearing units="degrees">46.0</bearing>
	private $uomWindSpeed	= 'km/h';	// <speed unitType="metric" units="km/h">11</speed>
	private $uomHum		= 'percent';	// <relativeHumidity units="%">66</relativeHumidity>
	private $uomBaro	= 'hPa';	// ??
	private $uomCloud	= '%';		// 
	private $uomRain	= 'in';		// <precipType start="" end=""/>
	private $uomDistance	= '??';		// <otherVisib cause="other">
	private $uomPoP		= '%';		// <pop units="%"/>
	
	private $enableCache	= true; 	// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cache		= 'cache';	// cache dir is created when not available
	private $cacheTime 	= 3600; 	// Cache expiration time Default: 3600 seconds = 1 Hour
	private $cacheFile	= 'xxx';	// http://dd.meteo.gc.ca/citypage_weather/xml/BC/s0000141_e.xml
						// http://dd.meteo.gc.ca/citypage_weather/xml/NU/s0000714_e.xml
	private $apiUrlpart 	= array(	// http://dd.meteo.gc.ca/citypage_weather/xml/ON/s0000710_e.xml
	 0 => 'http://dd.meteo.gc.ca/citypage_weather/xml/',
	 1 => 'userinputProv',
	 2 => '/',
	 3 => 'userinputSite',
	 4 => '_',
	 5 => 'userinputLang',
	 6 => '.xml');
	private $weatherApiUrl	= '';
	private $rawData	= '';
	private $script_name	= 'module ec_fct_create_arr.php'; # ecPlainCreateArr
#--------------------------------------------------------------------------------------------------
# public functions	
#--------------------------------------------------------------------------------------------------
	public function getWeatherData($prov = '', $site = '') {
		global $SITE, $_REQUEST, $myPageEC, $validWarningTypes, $validWarningPriorities, $pageName;
		#----------------------------------------------------------------------------------------------
		# clean user input
		#----------------------------------------------------------------------------------------------
		$this->apiUrlpart[1]    = $prov    = trim($prov);
		$this->apiUrlpart[3]    = $site    = trim($site);
		$my_lang                = substr(trim($SITE['lang']),0,1);
		if ($my_lang <> 'f') {$my_lang = 'e'; }
		$this->apiUrlpart[5]    = $my_lang;
		#----------------------------------------------------------------------------------------------
		# combine everything into required url
		#  http://dd.meteo.gc.ca/citypage_weather/xml/ON/s0000710_e.xml
		#----------------------------------------------------------------------------------------------
		$this->weatherApiUrl = '';
		$end	= count($this->apiUrlpart);
		for ($i = 0; $i < $end; $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------
		# try loading data from cache
		#----------------------------------------------------------------------------------------------		
		if ($this->enableCache && !empty($this->cache ) ){			
			$this->cache	= $SITE['cacheDir'];
			$uoms		= $SITE['uomTemp'].'_'.$SITE['uomWind'].'_'.$SITE['uomBaro'].'_'.$SITE['uomRain'].'_'.$SITE['uomSnow'].'_'.$SITE['uomDistance'];
			$from		= array('&deg;','/',' ');
			$to		= '';
			$uoms		= str_replace($from,$to,$uoms);
			$fileName	= 'ecweatherxml_'.$prov.'_'.$site.'_'.$my_lang.'_'.$uoms;
			$this->cacheFile= $this->cache . $fileName.'.arr';
			if (isset ($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'ec') {
                                $returnArray	= '';
                                ws_message ( '<!-- '.$this->script_name.' ('.__LINE__.'): cache skipped as force was used -->');
                        } else {
                                ws_message ( '<!-- '.$this->script_name.' ('.__LINE__.'): try to load '.$this->cacheFile.'  from cache  -->');
			        $returnArray	= $this->loadFromCache();	// load from cache returns data only when its data is valid
			}
			if (!empty($returnArray)) {
				return $returnArray;					// if data is in cache and valid return data to calling program
			}	// eo valid data, return to calling program
		}  		// eo check cache
		#----------------------------------------------------------------------------------------------		
		if ($this->makeRequest() ==  false) { 
			ws_message ( '<!-- '.$this->script_name.' ('.__LINE__.'): no good date loaded, back to calling script  -->');
			return false;
		} 	
		$xml = new SimpleXMLElement($this->rawData);  // load xml from url and process
#			echo '<pre>'.PHP_EOL;  
#			print_r ($xml); 
#			exit;
		$returnArray = array();
		$saveIcon	= $SITE['ecIconsOwn'];
		$SITE['ecIconsOwn']	= false;
		$utcDiff 	= date('Z');// to help to correct utc differences
		$time		= strtotime((string) $xml->forecastGroup->dateTime[1]->timeStamp);
		$returnArray['information']['province']         = $prov;
		$returnArray['information']['cityCode']         = $site;
		$returnArray['information']['language']         = $my_lang;			
		$returnArray['information']['fileTimeStamp']    = $time;
		$returnArray['information']['fileTime']		= date('c', $time );
		$returnArray['information']['updated']		= date($SITE['dateLongFormat'], $time).' '.date($SITE['timeOnlyFormat'], $time);			
		$returnArray['information']['location']		= $this->utf8_text((string) $xml->location->region);
		$returnArray['information']['uomWindSpeed']	= 'km/h';
		$returnArray['information']['uomPrecipRain']    = 'mm';
		$returnArray['information']['uomPrecipSnow']    = 'cm';	
/* <regionalNormals>
        <textSummary>Low zero. High 10.</textSummary>
        <temperature unitType="metric" units="C" class="high">10</temperature>
        <temperature unitType="metric" units="C" class="low">0</temperature>
</regionalNormals> */
		$returnArray['information']['normalTempText']	= (string) $xml->forecastGroup->regionalNormals->textSummary;
		$returnArray['information']['normalTempMin']	= (string) $xml->forecastGroup->regionalNormals->temperature[1];
		$returnArray['information']['normalTempMax']	= (string) $xml->forecastGroup->regionalNormals->temperature[0];
/*<almanac>
        <temperature class="extremeMax" period="2011-2014" unitType="metric" units="C" year="2011">22.2</temperature>
        <temperature class="extremeMin" period="2011-2014" unitType="metric" units="C" year="2014">-2.3</temperature>
        <temperature class="normalMin" unitType="metric" units="C"/>
        <temperature class="normalMean" unitType="metric" units="C"/>
        <precipitation class="extremeRainfall" period="-" unitType="metric" units="mm" year=""/>
        <precipitation class="extremeSnowfall" period="-" unitType="metric" units="cm" year=""/>
        <precipitation class="extremePrecipitation" period="2011-2014" unitType="metric" units="mm" year="2013">3.5</precipitation>
        <precipitation class="extremeSnowOnGround" period="-" unitType="metric" units="cm" year=""/>
        <pop units="%"/>
</almanac>*/
		$returnArray['information']['extremeMax']	= (string) $xml->almanac->extremeMax;
		$returnArray['information']['extremeMin']	= (string) $xml->almanac->extremeMin;
#--------------------------------------------------------------------------------------------------
#  get alerts  info
#--------------------------------------------------------------------------------------------------
/* <warnings url="http://weather.gc.ca/warnings/report_e.html?bc42">
 <event type="warning" priority="low" description="WIND WARNING  IN EFFECT">
 . . . <dateTime nam . . . 
 </event>
*/
		$returnArray['warnings']['url']		= (string) $xml->warnings['url'];
		$maxType		= -1;
		$maxPrio		= -1;
		$n				= -1;
		$searchRain 	= array ('SNOW','RAIN');
		$searchThunder 	= array ('THUNDER');
#
		for ($i = 0; $i < count($xml->warnings->event); $i++) {
			$type										= (string) $xml->warnings->event[$i]['type'];
			if ($type == 'ended') {
				continue;
			} else {
				$n++;
			}
			$returnArray['warnings']['event'][$n]['type']		= $type;
			if (isset ($validWarningTypes[$type]) && $maxType < $validWarningTypes[$type]) {
				$maxType 								= $validWarningTypes[$type];
			}
			$priority									= (string) $xml->warnings->event[$i]['priority'];
			$returnArray['warnings']['event'][$n]['priority']	= $priority;
			if (isset ($validWarningPriorities[$priority]) && $maxPrio < $validWarningPriorities[$priority]) {
				$maxPrio 								= $validWarningPriorities[$priority];
			}
			$stringDesc									= $this->utf8_text((string) $xml->warnings->event[$i]['description']);
			$returnArray['warnings']['event'][$n]['description']	= $stringDesc;
			$stringDesc	= $stringDesc;
			for ($p = 0; $p < count($searchRain); $p++) {
				$needle = $searchRain[$p];
				$pos = strpos('   '.$stringDesc, $needle);
				if ($pos > 1) {
					$returnArray['warnings']['warnrain']= true; 
					$returnArray['warnings']['raintype']= $needle;
				}
			}
			for ($p = 0; $p < count($searchThunder); $p++) {
				$pos = strpos('   '.$stringDesc, $searchThunder[$p]);
				if ($pos > 1) {
					$returnArray['warnings']['warnthunder']= true;
					break;
				}
			}
		}
		$returnArray['warnings']['maxType']	= $maxType;
		$returnArray['warnings']['maxPrio']	= $maxPrio;
#--------------------------------------------------------------------------------------------------
#  get forecast info
#--------------------------------------------------------------------------------------------------
		$endLayouts	= count ($xml->forecastGroup->forecast);
		if ($endLayouts == 0)  {echo '<h3> '.$myPageEC . ' - invalid xml file - program halted </h3>'; exit;}
#	echo "forecast retrieved: $endLayouts".PHP_EOL;;
		for ($nLayouts = 0; $nLayouts < $endLayouts; $nLayouts++) {
			$forecast = $xml->forecastGroup->forecast[$nLayouts];
#				echo "forecast $nLayouts".PHP_EOL;
#				print_r ($forecast);
/* <period textForecastName="Today">Friday</period>*/
			$returnArray['forecast'][$nLayouts]['period']			= $this->utf8_text((string) $forecast->period);
			$returnArray['forecast'][$nLayouts]['daypart']			= $this->utf8_text((string) $forecast->period['textForecastName']);
/* <textSummary>Rain ending this morning then cloudy with 60 percent chance of showers. High 13.</textSummary>*/				
			$returnArray['forecast'][$nLayouts]['forecastText']		= $this->utf8_text((string) $forecast->textSummary);
/* <cloudPrecip>
<textSummary>Rain ending this morning then cloudy with 60 percent chance of showers.</textSummary>
</cloudPrecip>*/
			$returnArray['forecast'][$nLayouts]['cloudText']		= $this->utf8_text((string) $forecast->cloudPrecip->textSummary);
/*<abbreviatedForecast>
<iconCode format="gif">12</iconCode>
<pop units="%">60</pop>
<textSummary>Chance of showers</textSummary>
</abbreviatedForecast>*/
			$returnArray['forecast'][$nLayouts]['iconText']			= $this->utf8_text((string) $forecast->abbreviatedForecast->textSummary);
			$icon	= (string) $forecast->abbreviatedForecast->iconCode;
			$returnArray['forecast'][$nLayouts]['iconNumber']		= $icon;
			$iconOut	=	$none	= $icon;
			$save		= 
			$ret = wsChangeIcon ('ec',$icon, $iconOut, $none, $none);
			$returnArray['forecast'][$nLayouts]['defaultIcon'] 		= $iconOut;
			$returnArray['forecast'][$nLayouts]['PoP']		 		= (string) $forecast->abbreviatedForecast->pop;
/*<temperatures>
<textSummary>Low 12. High 17.</textSummary>
<temperature unitType="metric" units="C" class="high">17</temperature>
<temperature unitType="metric" units="C" class="low">12</temperature>
</temperatures>*/
			$returnArray['forecast'][$nLayouts]['tempText']			= $this->utf8_text((string) $forecast->temperatures->textSummary);
			for ($n = 0; $n < count($forecast->temperatures->temperature); $n++) {
				$key	= (string) $forecast->temperatures->temperature[$n]['class'];
				$returnArray['forecast'][$nLayouts]['temp'][$key]	= (string) $forecast->temperatures->temperature[$n].$SITE['uomTemp'];
			}
/* 
<winds>
<textSummary>Wind southeast 20 km/h becoming south 40 to 60 late in the afternoon then becoming west 40 to 60.</textSummary>
<wind index="1" rank="minor">
	<speed unitType="metric" units="km/h">10</speed>
	<gust unitType="metric" units="km/h">00</gust>
	<direction>SE</direction>
	<bearing units="degrees">14</bearing>
</wind>
<wind index="2" rank="major">
	<speed unitType="metric" units="km/h">20</speed>
	<gust unitType="metric" units="km/h">00</gust>
	<direction>SE</direction>
	<bearing units="degrees">14</bearing>
</wind>
. . .
</winds>*/
			if (!isset ($forecast->winds->textSummary) ) {
				$returnArray['forecast'][$nLayouts]['windsText']			= '';
			} else {
				$returnArray['forecast'][$nLayouts]['windsText']			= $this->utf8_text((string) $forecast->winds->textSummary);
				for ($n = 0; $n < count($forecast->winds->wind); $n++) {
					$index 	= $forecast->winds->wind[$n]['index'];
					$returnArray['forecast'][$nLayouts]['winds'][$n]['rank']	= (string) $forecast->winds->wind[$n]['rank'];
					$returnArray['forecast'][$nLayouts]['winds'][$n]['speed']	= (string) $forecast->winds->wind[$n]->speed;
					$returnArray['forecast'][$nLayouts]['winds'][$n]['gust']	= (string) $forecast->winds->wind[$n]->gust;
					$returnArray['forecast'][$nLayouts]['winds'][$n]['dir']		= (string) $forecast->winds->wind[$n]->direction;
					$returnArray['forecast'][$nLayouts]['winds'][$n]['deg']		= (string) $forecast->winds->wind[$n]->bearing;					
				}
			}
/*
<precipitation>
<textSummary>Amount 50 mm near the North Shore.</textSummary>
<precipType start="12" end="25">rain</precipType>
<accumulation>
	<name>rain</name>
	<amount unitType="metric" units="mm">5</amount>
</accumulation>
</precipitation>
*/
			if (!isset ($forecast->precipitation) ) {
				$returnArray['forecast'][$nLayouts]['precipText']			= '';
			} else {
				$returnArray['forecast'][$nLayouts]['precipText']			= $this->utf8_text((string) $forecast->precipitation->textSummary);
				for ($n = 0; $n < count ($forecast->precipitation->precipType); $n++) {			// 0? - ??
					$returnArray['forecast'][$nLayouts]['precipType'][$n]	= (string) $forecast->precipitation->precipType[$n];
				}
				for ($n = 0; $n < count ($forecast->precipitation->accumulation); $n++) {		// 0 - 4
					$returnArray['forecast'][$nLayouts]['precipName'][$n]	= (string) $forecast->precipitation->accumulation[$n]->name;	
					$returnArray['forecast'][$nLayouts]['precipAmount'][$n]	= (string) $forecast->precipitation->accumulation[$n]->amount;
				}
			}
/* <visibility>
<otherVisib cause="other">
	<textSummary>Fog patches dissipating this morning.</textSummary>
</otherVisib>
</visibility>  */
			if (!isset ($forecast->visibility->otherVisib) ) {
				$returnArray['forecast'][$nLayouts]['visibOtherText']	= '';
			} else {
				$returnArray['forecast'][$nLayouts]['visibOtherText']	= $this->utf8_text((string) $forecast->visibility->otherVisib->textSummary);				
				$returnArray['forecast'][$nLayouts]['visibOtherCause']	= (string) $forecast->visibility->otherVisib['cause'];
			}
			if (!isset ($forecast->visibility->windVisib) ) {
				$returnArray['forecast'][$nLayouts]['visibWindText']	= '';
			} else {
				$returnArray['forecast'][$nLayouts]['visibWindText']	= $this->utf8_text((string) $forecast->visibility->windVisib->textSummary);				
				$returnArray['forecast'][$nLayouts]['visibWindCause']	= (string) $forecast->visibility->windVisib['cause'];
			}					

/* <uv category="moderate">
<index>5</index>
<textSummary>UV index 5 or moderate.</textSummary>
</uv>  */
			if (!isset ( $forecast->UV) ) {
				$returnArray['forecast'][$nLayouts]['UvText']		= '';
			} else {
				$returnArray['forecast'][$nLayouts]['UVindex']		= (string) $forecast->uv->index;
				$returnArray['forecast'][$nLayouts]['UvCategory']	= (string) $forecast->uv['category'];				
				$returnArray['forecast'][$nLayouts]['UvText']		= $this->utf8_text((string) $forecast->uv->textSummary);
			}
/* <relativeHumidity units="%">65</relativeHumidity>  */
			$returnArray['forecast'][$nLayouts]['humidity']			= (string) $forecast->relativeHumidity;
/*  <snowlevel><textSummary>????</textSummary></snowlevel>  */	
		if (!isset ( $forecast->snowlevel) ) {
			$returnArray['forecast'][$nLayouts]['snowlevelText']	= '';
		} else {
			$returnArray['forecast'][$nLayouts]['snowlevelText']	= $this->utf8_text((string) $forecast->snowlevel->textSummary);			
		}
/*  <frost><textSummary>????</textSummary></frost>  */	
		if (!isset ( $forecast->snowlevel) ) {
			$returnArray['forecast'][$nLayouts]['frostText']	= '';
		} else {
			$returnArray['forecast'][$nLayouts]['frostText']	= $this->utf8_text((string) $forecast->frost->textSummary);			
		}
/*  <comfort><textSummary>cool.</textSummary></comfort>  */
		if (!isset ( $forecast->comfort) ) {
			$returnArray['forecast'][$nLayouts]['comfortText']	= '';
		} else {
			$returnArray['forecast'][$nLayouts]['comfortText']	= $this->utf8_text((string) $forecast->comfort->textSummary);			
		}
#			print_r ($returnArray['forecast'][$nLayouts]); 
		}  // eof layouts
#		exit;
	$SITE['ecIconsOwn'] = $saveIcon;
	$ret = $this->writeToCache($returnArray);
	return $returnArray;
		

	} // eof getWeatherData
	
	private function loadFromCache(){
		global $cron_all;
	        $cachefile      = $this->cacheFile;
	        $cacheAllowed   = $this->cacheTime;
		if (!file_exists($cachefile)){return;}
		$file_time      = filemtime($cachefile);
		$now            = time();
		$diff           = ($now - $file_time);
		ws_message (  '<!-- '.$this->script_name.' ('.__LINE__.'): ('.$cachefile.'):
	cache time   = '.date('c',$file_time).' from unix time '.$file_time.'
	current time = '.date('c',$now).' from unix time '.$now.' 
	difference   = '.$diff.' (seconds)
	diff allowed = '.$cacheAllowed.' (seconds) -->');	
		if (isset ($cron_all) ) {		// runnig a cron job
			$cacheAllowed 	= $cacheAllowed - 600;	// 
			ws_message ('<!-- '.$this->script_name.' ('.__LINE__.'): max cache lowered with 600 seconds as cron job is running -->');
		}	
		if ($diff <= $cacheAllowed){
			ws_message ( '<!-- '.$this->script_name.' ('.__LINE__.'): ('.$cachefile.') loaded from cache  -->');
			$returnArray =  unserialize(file_get_contents($cachefile));
			return $returnArray;
		}	
	} // eof loadFromCache
	
	private function writeToCache(&$returnArray){
		$returnArray['information']['cacheTimestamp']	= time()+ $this->cacheTime;
		$returnArray['information']['cacheTime']	= date('c', (time()+ $this->cacheTime));		
		if ( $this->enableCache && !empty($this->cache) ){
			if (!file_put_contents($this->cacheFile, serialize($returnArray))){   
				exit ("<h3 style=\"text-align; center\"> $this->script_name: Could not save data ($this->cacheFile) to cache ($this->cacheFile).<br />
Please make sure your cache directory exists and is writable. <br />Program halted </h3>");
			} 
			else {ws_message ( '<!-- '.$this->script_name.' ('.__LINE__.'): ('.$this->cacheFile.') saved to cache  -->');
			}
		}
	} // eof writeToCache
	
	private function makeRequest(){
		global $SITE;
		$test= false;
		if ($test) {
			ws_message ('<!-- module ec_fct_create_arr.php ('.__LINE__.'): test file ./canada/test2.xml loaded ',true);
			$this->rawData  = file_get_contents('./canada/test2.xml');
			return;
		} 
		ws_message ('<!-- module ec_fct_create_arr.php ('.__LINE__.'):  CURL will be executed for: '.$this->weatherApiUrl.' -->');
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
#		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $SITE['curlFollow']);
		$this->rawData = curl_exec ($ch);
		curl_close ($ch);
#		echo '<!-- '.$this->weatherApiUrl .' -->'.PHP_EOL;
#		echo '<!-- '.$this->rawData .' -->'.PHP_EOL; exit;
		if (empty($this->rawData)){
			return false;
		}
		$search = array ('Service Unavailable','Error 504','Error 503','HTTP Error 404','Page Not Found');
		$error = false;
		for ($i = 0; $i < count($search); $i++) {
			$int = strpos($this->rawData , $search[$i]);
			if ($int > 0) {
				$error = $search[$i]; 
				break;
			}
		}
		if ($error === false) {
			return true;
		} 
		else {	ws_message (  '<h3> module ec_fct_create_arr.php ('.__LINE__.'): ERROR '.$error.'  when loading forecast from url: '.$this->weatherApiUrl.'</h3>',true);
			return false;
		}
	} // eof makeRequest
	
	private function utf8_text($text){
	        global $SITE;
	        if ($SITE['charset'] <> 'UTF-8') {
	                $text   = iconv ('UTF-8',$SITE['charset'].'//TRANSLIT',$text);
	        }
	        return $text;
	}

}
# ----------------------  version history
# 3.20 2015-07-26 release 2.8 version 
