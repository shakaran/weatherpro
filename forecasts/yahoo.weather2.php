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
$pageName	= 'yahoo.weather2.php';
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
# retrieve weather info from weathersource (yahoo) and return an array with retrieved data in the desired units C/F
# language always en-US
# http request:  http://weather.yahooapis.com/forecastrss?w=LOCATIONCODE&u=c
#   LOCATIONCODE = for instance BEXX0026 for  <yweather:location city="Beauvechain" region="" country="BE"/>
#   _c = Celsius  changes weather units to metric <yweather:units temperature="C" distance="km" pressure="mb" speed="km/h"/>
#	_f = fahrenheit  <yweather:units temperature="F" distance="mi" pressure="in" speed="mph"/>
#--------------------------------------------------------------------------------------------------
class yahooWeather{
	# public variables
	public $location	= '973505';  	// must be a yahoo code (f.i. 973505) go to http://weather.yahoo.com/, find your city and check the numeric w code in the url
	public $lang		= 'en';		// supported languages from yahoo weather only en-us 
	public $yahooKey	= '';		// no Key needed for yahoo
	# private variables
	private $uomTemp	= '&deg;C';
	private $uomBaro	= ' mb';
	private $uomWind	= 'kmh';
	private $uomRain	= ' mm';
	private $uomDistance    = ' km';
	private $enableCache= true;		// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cachePath	= 'cache/';
	private $cacheTime 	= 600; 		// Cache expiration time Default:  = 10 min
	private $icons_url 	= 'http://l.yimg.com/a/i/us/we/52/';  //  Location of the weather icons 
	private $icons_ext 	= '.gif';       //  Location of the weather icons
	private $weathersource	= 'yahooweather';
	private $cacheFile;
	private $weatherApiUrl = '';
	private $apiUrlpart = array(	        // http://weather.yahooapis.com/forecastrss?w=973505&u=c    version 2
		0 =>	'http://weather.yahooapis.com/forecastrss?w=',
		1 =>	'$this->location',
		2 =>	'&u=',
		3 =>	'c' 
	);
	private $rawData;					// to store retieved xml 
	#--------------------------------------------------------------------------------------------------
	# public functions	
	#--------------------------------------------------------------------------------------------------
	public function getWeatherData($userLocation = '') {
		global $SITE, $pageName, $dayNight;
		#----------------------------------------------------------------------------------------------
		# clean user input
		#----------------------------------------------------------------------------------------------
		# location should be 8 chars without any special characters
		#
		$userLocation = trim($userLocation);
		if (strlen($userLocation) > 3) {$this->location = $userLocation;}
		#----------------------------------------------------------------------------------------------
		# check if data (location and unit) is in cache
		$filename 		= str_replace( '/', '.', $userLocation);
		$this->cachePath	= $SITE['cacheDir'];
		$string			= $pageName.$this->lang.$userLocation.$SITE['uomTemp'].$SITE['uomWind'].$SITE['uomBaro'].$SITE['uomDistance'];
		$from			= array('&deg;','°','/',' ', '.', '-');
		$to			= '';
		$string			= str_replace($from,$to,$string);
		if ( $SITE['uomTemp'] <> '&deg;C') {$this->apiUrlpart[3]	= 'f';} 
		$this->cacheFile = $this->cachePath.$string.'.txt';
		if ($this->enableCache && !empty($this->cachePath)){
			$returnArray=$this->loadFromCache();  	// load from cache returns data only when its data is valid
			if (!empty($returnArray)) {		// if data is in cache and valid return data to calling program
				return $returnArray;
			}  // eo valid data and return to calling program
		}  // eo check cache
		#----------------------------------------------------------------------------------------------
		# combine everything into required url
		#----------------------------------------------------------------------------------------------
		#
		$this->apiUrlpart[1] = $this->location;
		$this->weatherApiUrl = '';
		for ($i = 0; $i < count($this->apiUrlpart); $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------		
		if ($this->makeRequest()){   			// load xml from url and process
			$this->rawData = str_replace( '<yweather:', '<yweather', $this->rawData);  // clean up unneeded ":" =  Namespace Elements
			$xml = new SimpleXMLElement($this->rawData);  // process xml
			$returnArray = array();
			#--------------------------------------------------------------------------------------------------
			# first, get and save request infor / units etc
			#--------------------------------------------------------------------------------------------------
			$i=0;
			$returnArray['request_info'][$i]['type'] 		= 'type';
			$returnArray['request_info'][$i]['city'] 		= 'city';
			$returnArray['request_info'][$i]['time']		= 'unixTime';
			$returnArray['request_info'][$i]['uomTemp'] 		= 'uomTemp';
			$returnArray['request_info'][$i]['uomDistance'] 	= 'uomDistance';
			$returnArray['request_info'][$i]['uomBaro'] 		= 'uomBaro';
			$returnArray['request_info'][$i]['uomWind'] 		= 'uomWind';
			$i=0;
			$returnArray['request_info'][$i]['type'] 		= (string) $xml->channel->title;
			$returnArray['request_info'][$i]['city'] 		= (string) $xml->channel->yweatherlocation['city'].' - '. 
										  (string) $xml->channel->yweatherlocation['country'];
			$returnArray['request_info'][$i]['time']		= (string) $xml->channel->lastBuildDate;
# yweather:units temperature="F" distance="mi" pressure="in" speed="mph"
# yweather:units temperature="C" distance="km" pressure="mb" speed="km/h"
			$returnArray['request_info'][$i]['uomTemp'] 		= $this->uomTemp	= (string) $xml->channel->yweatherunits['temperature'];
			$returnArray['request_info'][$i]['uomDistance'] 	= $this->uomDistance	= (string) $xml->channel->yweatherunits['distance'];
			$returnArray['request_info'][$i]['uomBaro'] 		= $this->uomBaro	= (string) $xml->channel->yweatherunits['pressure'];
			$returnArray['request_info'][$i]['uomWind'] 		= $this->uomWind	= (string) $xml->channel->yweatherunits['speed'];
// <lastBuildDate>Tue, 10 Sep 2013 2:25 pm CEST</lastBuildDate>
// <yweather:location city="Leuven" region="" country="Belgium"/>
			#--------------------------------------------------------------------------------------------------
			# get current condition descriptions
			#--------------------------------------------------------------------------------------------------
			$i=0;
			$returnArray['ccn'][$i]['humidity'] 	= 'humidity';  
			$returnArray['ccn'][$i]['baroNU'] 	= 'baro';			
			$returnArray['ccn'][$i]['baro'] 	= 'baro';
			$returnArray['ccn'][$i]['baroTrend']	= 'baroTrend';
			$returnArray['ccn'][$i]['visibNU']	= 'visibility';
			$returnArray['ccn'][$i]['visib']	= 'visibility';
			$returnArray['ccn'][$i]['windNU']	= 'wind';
			$returnArray['ccn'][$i]['wind']		= 'wind';
			$returnArray['ccn'][$i]['windDeg']	= 'windDeg';
			$returnArray['ccn'][$i]['description']	= 'description';
			$returnArray['ccn'][$i]['tempNU'] 	= 'temp';					
			$returnArray['ccn'][$i]['temp'] 	= 'temp';			
			$returnArray['ccn'][$i]['icon'] 	= 'icon'; 
			$returnArray['ccn'][$i]['iconUrl']	= 'iconUrl'; 
			$returnArray['ccn'][$i]['date'] 	= 'date';
			$returnArray['ccn'][$i]['timestamp'] 	= 'timestamp';			
			$i=1;			
			$returnArray['ccn'][$i]['humidity'] 	= (string) $xml->channel->yweatheratmosphere['humidity'];
			$amount 				= (string) $xml->channel->yweatheratmosphere['pressure'];
			$returnArray['ccn'][$i]['baroNU'] 	= $result = (string) wsConvertBaro($amount, $this->uomBaro,$SITE['uomBaro']);		
			$returnArray['ccn'][$i]['baro'] 	= $result.$SITE['uomBaro'];
			$returnArray['ccn'][$i]['baroTrend']	= (string) $xml->channel->yweatheratmosphere['rising'];
			$amount 				= (string) $xml->channel->yweatheratmosphere['visibility'];			
			$returnArray['ccn'][$i]['visibNU']	= $result = (string) wsConvertDistance($amount, $this->uomDistance,$SITE['uomDistance']);
			$returnArray['ccn'][$i]['visib']	= $result.$SITE['uomDistance'];
			$amount 				= (string) $xml->channel->yweatherwind['speed'];
			$returnArray['ccn'][$i]['windNU']	= $result = (string) wsConvertWindspeed($amount, $this->uomWind,$SITE['uomWind']);
			$returnArray['ccn'][$i]['wind']		= $result.$SITE['uomWind'];
			$returnArray['ccn'][$i]['windDeg']	= (string) $xml->channel->yweatherwind['direction'];
			$returnArray['ccn'][$i]['text']		= (string) $xml->channel->item->yweathercondition['text'];
			$amount 				= (string) $xml->channel->item->yweathercondition['temp'];
			$returnArray['ccn'][$i]['tempNU'] 	= $result =  (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
			$returnArray['ccn'][$i]['temp'] 	= $result.$SITE['uomTemp'];			
			$yaIconNr 				= (int) 1.0*$xml->channel->item->yweathercondition['code'];			
			if (!isset($dayNight)) { $dayNight = 'daylight';}
			$search = array ('26' ,'28', '30','32' ,'34' );
			$repl	= array ('27' ,'27', '29','31' ,'33' );
			if ($dayNight == 'nighttime') {
				$yaIconNr = str_replace ($search, $repl, $yaIconNr);		// night is icon minus 1 for certain conditions
				if ($returnArray['ccn'][$i]['text'] == 'Sunny') {$returnArray['ccn'][$i]['text'] = 'Clear';}
			} else {
				$yaIconNr = str_replace ($repl, $search, $yaIconNr);		// night is icon minus 1 for certain conditions
				if ($returnArray['ccn'][$i]['text'] == 'Clear') {$returnArray['ccn'][$i]['text'] = 'Sunny';}			
			}			
			$returnArray['ccn'][$i]['icon'] 	= $yaIconNr; 
			$returnArray['ccn'][$i]['iconUrl']	= $this->icons_url.$yaIconNr.$this->icons_ext; 
			$returnArray['ccn'][$i]['date'] 	= (string) $xml->channel->item->yweathercondition['date'];
			$returnArray['ccn'][$i]['timestamp'] 	= strtotime((string) $xml->channel->item->yweathercondition['date']);
			ws_message ( '<!-- module yahoo.weather2.php ('.__LINE__.') Condition text: '.$returnArray['ccn'][$i]['text'].'  icon: '.$yaIconNr.'       -->');
			#--------------------------------------------------------------------------------------------------
			#  get forecast info
			#--------------------------------------------------------------------------------------------------
			$i=0;
			$returnArray['forecast'][$i]['weekday'] 	= 'day';
			$returnArray['forecast'][$i]['date']		= 'date';
			$returnArray['forecast'][$i]['timestamp']	='timestamp';
			$returnArray['forecast'][$i]['tempLowNU']	= 'tempLow';
			$returnArray['forecast'][$i]['tempLow']		= 'tempLow';
			$returnArray['forecast'][$i]['tempHighNU'] 	= 'tempHigh';
			$returnArray['forecast'][$i]['tempHigh'] 	= 'tempHigh';
			$returnArray['forecast'][$i]['icon'] 		= 'icon';				
			$returnArray['forecast'][$i]['condition']	= 'text';			
			for ($i = 1; $i <= count($xml->channel->item->yweatherforecast); $i++){
				$data = $xml->channel->item->yweatherforecast[$i-1];
				$returnArray['forecast'][$i]['weekday'] 	= (string) 	$data['day'];
				$returnArray['forecast'][$i]['date']		= (string) 	$data['date'];
				$returnArray['forecast'][$i]['timestamp']	= strtotime((string) $data['date']);
				$amount						= (string) 	$data['low'];
				$returnArray['forecast'][$i]['tempLowNU']	= $result = (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
				$returnArray['forecast'][$i]['tempLow'] 	= $result.$SITE['uomTemp'];
				$amount						= (string) 	$data['high'];
				$returnArray['forecast'][$i]['tempHighNU'] 	= $result = (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
				$returnArray['forecast'][$i]['tempHigh'] 	= $result.$SITE['uomTemp'];
				$returnArray['forecast'][$i]['icon'] 		= (string) 	$data['code'];				
				$returnArray['forecast'][$i]['condition']	= (string)	$data['text'];
				$returnArray['forecast'][$i]['iconUrl'] 	= $this->icons_url.$returnArray['forecast'][$i]['icon'].$this->icons_ext;
			}  // eo for loop forecast
		}  
		else  {         // eo makerequest processing
			ws_message ( '<!-- module yahoo.weather2.php ('.__LINE__.') weatherdata not available, try to load ('.$this->cacheFile.')  from cache  -->');
			$this->cacheTime = 100 * $this->cacheTime; 
			$returnArray=$this->loadFromCache();
			return $returnArray;
		}
		#--------------------------------------------------------------------------------------------------
		#  write to cache
		#--------------------------------------------------------------------------------------------------
		if ($this->enableCache && !empty($this->cachePath)){
			$this->writeToCache($returnArray);
		}		
		return $returnArray;	
} // eof getWeatherData
	#--------------------------------------------------------------------------------------------------
	# private functions	
	#--------------------------------------------------------------------------------------------------
	private function loadFromCache(){
		global $dayNight, $SITE, $cron_all;
		if ($SITE['curCondFrom'] <> 'yahoo') {$this->cacheTime = 3600;}  // default 10 minutes if used for CCN
#
		$cacheFile      = $this->cacheFile;
		$scriptname     = 'module yahoo.weather2.php';
		if (!file_exists($cacheFile)){ 
			ws_message ( "<!-- $scriptname (".__LINE__.") ($cacheFile) does not exist yet  -->"); 
			return;
		}
		$cacheFile      = $this->cacheFile;
		$cacheAllowed   = $this->cacheTime;
                $file_time      = filemtime($cacheFile);
                $now            = time();
                $diff           = ($now     -   $file_time);
                ws_message (  "<!-- $scriptname (".__LINE__.") ($cacheFile):
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff (seconds)
	diff allowed = $cacheAllowed (seconds) -->");
		if (isset ($cron_all) ) {		// runnig a cron job
			$cacheAllowed 	= 300;		// 
			ws_message (  "<!-- $scriptname (".__LINE__.") max cache set to 300 seconds as cron job is runnig -->");
		}	
                if ($diff <= $cacheAllowed){
                        ws_message (  "<!-- $scriptname (".__LINE__.") ($cacheFile) loaded from cache -->");
                        $returnArray =  unserialize(file_get_contents($this->cacheFile));
                        $yaIconNr 	= $returnArray['ccn'][1]['icon'];		
                        if (!isset($dayNight)) { $dayNight 	= 'daylight';}
                        $search = array ('26' ,'28', '30','32' ,'34' );
                        $repl	= array ('27' ,'27', '29','31' ,'33' );
                        if ($dayNight == 'nighttime') {
                                $yaIconNr = str_replace ($search, $repl, $yaIconNr);		// night is icon minus 1 for certain conditions
                                if ($returnArray['ccn'][1]['text'] == 'Sunny') {$returnArray['ccn'][1]['text'] = 'Clear';}
                        } else {
                                $yaIconNr = str_replace ($repl, $search, $yaIconNr);		// night is icon minus 1 for certain conditions
                                if ($returnArray['ccn'][1]['text'] == 'Clear') {$returnArray['ccn'][1]['text'] = 'Sunny';}			
                        }			
                        $returnArray['ccn'][1]['icon'] 		= $yaIconNr; 
                        $returnArray['ccn'][1]['iconUrl']	= $this->icons_url.$yaIconNr.$this->icons_ext; 			
                        return $returnArray;
                }  // eo filte time ok -> get file from cache
	}  // eof loadFromCache
	#--------------------------------------------------------------------------------------------------
	private function writeToCache($data){
		if (!file_exists($this->cachePath)){
			// attempt to make the dir
			mkdir($this->cachePath, 0777);
		}
		$scriptname     = 'module yahoo.weather2.php';
		if (!file_put_contents($this->cacheFile, serialize($data))){
			exit('<h3 style="text-align: center;">'. $scriptname." (".__LINE__.") 
			Could not save data to cache $this->cacheFile. Please make sure your cache directory exists and is writable. </h3>".PHP_EOL);
		} else {ws_message ( "<!-- $scriptname (".__LINE__.") ($this->cacheFile) saved to cache  -->");}
	}
	#--------------------------------------------------------------------------------------------------
	private function makeRequest(){
		global $SITE;
		$test = false;
		$scriptname     = 'module yahoo.weather2.php';
		if ($test == TRUE) {
			$this->rawData  = file_get_contents('forecasts/testyahoo.xml');
			ws_message ("<!-- $scriptname (".__LINE__.") testfile (forecasts/testyahoo.xml) loaded -->");
		} else {
		        ws_message ( "<!-- $scriptname (".__LINE__.") ($this->weatherApiUrl) loading with CURL  -->");
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
			$this->rawData = curl_exec ($ch);
			curl_close ($ch);
		}
		if (empty($this->rawData)) {return false;}
		$search = array ('<title>Yahoo! Weather</title>');
		$goodData = false;
		for ($i = 0; $i < count($search); $i++) {
			$int = strpos($this->rawData , $search[$i]);
			if ($int > 0) {$goodData = true; break;}
		}
		return $goodData;
	} // eof makeRequest
	#--------------------------------------------------------------------------------------------------
}  // eo class
# ----------------------  version history
# 3.20 2015-07-26 release 2.8 version 
