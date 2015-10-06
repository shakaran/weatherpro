<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   //--self downloader --
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
$pageName	= 'hwaCreateArr.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.0b 2014-09-14';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) { $SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# retrieve weather infor from weathersource  
# 3.0b 2014-09-14 beta release version
#--------------------------------------------------------------------------------------------------
class hwaWeather{
	# public variables
	# private variables
	private $test		= false;
	private $uomTemp	= '&deg;C';
	private $uomBaro	= 'hPa';
	private $uomWind	= 'bft';
	private $uomRain	= ' mm';
	private $enableCache= true;		// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cachePath	= 'cache';		// cache dir is created when not available
	private $cacheTime 	= 3600; 		// Cache expiration time Default: 3600 seconds = 1/1 Hour
	private $iconType	= '.png';
	private $cacheFile      = '';
	private $apiUrlpart     = array();
	private $weatherApiUrl  = '';
	private $rawData        = '';
	private $descriptions   = array (
	'buien'=> 'Showers',
	'geheel bewolkt'=> 'Cloudy',
	'hagel'=> 'Hail',
	'ijsregen'=> 'Freezing rain',
	'half bewolkt'=> 'Partly cloudy',
	'licht bewolkt'=> 'Mostly clear',
	'mist'=> 'Fog',
	'motregen'=> 'Drizzle',
	'motsneeuw'=> 'Snow grains',
	'nevel'=> 'Mist',
	'onbewolkt'=> 'Clear',
	'onweer'=> 'Thunderstorm',
	'regen'=> 'Rain',
	'sneeuw'=> 'Snow',
	'sneeuwbui'=> 'Snow showers',
	'veel wind'=> 'Windy',
	'zwaar bewolkt'=> 'Cloudy',
	'zzz'=> 'zzz');
	private $windDir = array (
	'N'=>'North','NNO'=>'NNE','NO'=>'NE','ONO'=>'ENE',
	'O'=>'East','OZO'=>'ESE','ZO'=>'SE','ZZO'=>'SSE',
	'Z'=>'South','ZZW'=>'SSW','ZW'=>'SW','WZW'=>'WSW',
	'W'=>'West','WNW'=>'WNW','NW'=>'NW','NNW'=>'NNW');
#--------------------------------------------------------------------------------------------------
# public functions	
#--------------------------------------------------------------------------------------------------
	public function getWeatherData($location = '') {
		global $SITE, $sunset, $sunrise, $pageName;
		$SITE['hwaIconsDir']    = 'wsHwa/hwa_icons/';
		# check if data (for this location) is in cache
		if ($this->enableCache && !empty($this->cachePath)){
			$this->cachePath        = $SITE['cacheDir'];
			$uoms                   = $SITE['uomTemp'].'-'.$SITE['uomBaro'].'-'.$SITE['uomRain'];
			$from                   = array('&deg;','°','/',' ');
			$to                     = array('','','','');
			$uoms                   = str_replace($from,$to,$uoms);
			$this->cacheFile        = $this->cachePath.$pageName.'-'.$uoms;
			$returnArray            = $this->loadFromCache(); 	// load from cache returns data only when its data is valid
			if (!empty($returnArray)) {	// if data is in cache and valid return data to calling program
				return $returnArray;
			}  // eo return to calling program
		}  // eo check cache
		#----------------------------------------------------------------------------------------------
		# combine user constants and input (1)location (2)units for temp etc  to required url
		#----------------------------------------------------------------------------------------------
		$this->apiUrlpart[0] = 'http://www.hetweeractueel.nl/includes/custom/mosfeed.php?id=';
		$this->apiUrlpart[1] = $SITE['hwaXmlId'];
		$this->apiUrlpart[2] = '&securitycode=';
		$this->apiUrlpart[3] = $SITE['hwaXmlKey'];
		$this->apiUrlpart[4] = '&extended=1';
//		# combine thew params tinto a correct URL
		$this->weatherApiUrl = '';
		for ($i = 0; $i < count($this->apiUrlpart); $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------
		if ($this->makeRequest()){
			$xml = new SimpleXMLElement($this->rawData);	
//			echo "<pre>"; print_r ($xml);
			$returnArray = array();
			$rainSave 		= 0;
			$windSave 		= 0;
			$tempMaxSave 	= -100;
			$tempMinSave 	= +100;
			$windSave		= 0;
			#--------------------------------------------------------------------------------------------------
			# first, get and save request info / units etc
			#--------------------------------------------------------------------------------------------------
			$i=0;  // headings
			$returnArray['request_info'][$i]['type'] 		= 'type';
			$returnArray['request_info'][$i]['city'] 		= 'city';
			$returnArray['request_info'][$i]['datum'] 		= 'date';
			$returnArray['request_info'][$i]['url'] 		= 'url';
			$returnArray['request_info'][$i]['timestamp'] 	= 'timestamp';
			$returnArray['request_info'][$i]['uomTemp'] 	= 'uomTemp';
			$returnArray['request_info'][$i]['uomBaro'] 	= 'uomBaro';
			$returnArray['request_info'][$i]['uomWind'] 	= 'uomWind';
			$returnArray['request_info'][$i]['uomRain'] 	= 'uomRain';
			
			$i=1;  // data
			$returnArray['request_info'][$i]['type'] 		= (string) $xml->Plaatsen->Plaats['id'];
			$returnArray['request_info'][$i]['city'] 		= (string) $xml->Plaatsen->Plaats['naam'];			
			$returnArray['request_info'][$i]['datum'] 		= (string) $xml['aanmaakdatum'];
			$returnArray['request_info'][$i]['url'] 		= (string) $xml->Plaatsen->Plaats['alias'];			
			$returnArray['request_info'][$i]['timestamp'] 	= strtotime($returnArray['request_info'][$i]['datum'].(string) $xml['aanmaaktijd']);			
			$returnArray['request_info'][$i]['uomTemp'] 	= '&deg;C';
			$returnArray['request_info'][$i]['uomBaro'] 	= 'hPa';
			$returnArray['request_info'][$i]['uomWind'] 	= ' bft';
			$returnArray['request_info'][$i]['uomRain'] 	= 'mm';
			#--------------------------------------------------------------------------------------------------
			#  get forecast info
			#--------------------------------------------------------------------------------------------------
			$i = 0;
			$returnArray['forecast'][$i]['date'] 		= 'date';
			$returnArray['forecast'][$i]['hour'] 		= 'hour';			
			$returnArray['forecast'][$i]['timestamp']	= 'timestamp';
#	EXTRA 12 hours data	
			$returnArray['forecast'][$i]['iconL'] 		= 'icon';
			$returnArray['forecast'][$i]['iconUrlL'] 	= 'iconUrl';			
			$returnArray['forecast'][$i]['weatherDescL']= 'description';	
			$returnArray['forecast'][$i]['part'] 		= 'Day/Night';
			$returnArray['forecast'][$i]['tempLowNU']	= 'tempLow';
			$returnArray['forecast'][$i]['tempLow']		= 'tempLow';
			$returnArray['forecast'][$i]['tempHighNU']	= 'tempHigh';
			$returnArray['forecast'][$i]['tempHigh']	= 'tempHigh';
			$returnArray['forecast'][$i]['rainChance'] 	= 'CoR';
			$returnArray['forecast'][$i]['snowChance'] 	= 'CoS';
			$returnArray['forecast'][$i]['thunderChance']= 'CoT'; 			
#	6 hours data
			$returnArray['forecast'][$i]['rainNU'] 		= 'rain';
			$returnArray['forecast'][$i]['rain'] 		= 'rain';
#   3 hours data			
			$returnArray['forecast'][$i]['icon'] 		= 'icon';
			$returnArray['forecast'][$i]['iconUrl'] 	= 'iconUrl';			
			$returnArray['forecast'][$i]['weatherDesc'] = 'description';		
			$returnArray['forecast'][$i]['tempNU']		= 'temp';
			$returnArray['forecast'][$i]['temp']		= 'temp';
			$returnArray['forecast'][$i]['baro']		= 'baro';
			$returnArray['forecast'][$i]['windSpeedNU']	= 'wind';
			$returnArray['forecast'][$i]['windSpeed']	= 'wind';
			$returnArray['forecast'][$i]['windDir'] 	= 'windDir';
			$returnArray['forecast'][$i]['windDirIcon'] = 'windDirIcon';		
#
			$i2 = count($xml->Plaatsen->Plaats->Verwachtingen->Verwachting);
			$utcDiff 	= date('Z');// to help to correct utc differences

			for ($n = 1; $n <= $i2; $n++){
				$arr=$xml->Plaatsen->Plaats->Verwachtingen->Verwachting[$n-1];
#				if (!isset($arr->weersymbool_12u) ){continue;}  // skip new 3 hour details
				$i++;
				$returnArray['forecast'][$i]['date'] 			= $datum = (string) $arr['datum'];  // [date] => 20130424
				$returnArray['forecast'][$i]['hour'] 			= $hour  = substr('00'.(string) $arr['uur'],-2);   // [hour] => 12
				$timeString										= $datum.'T'.$hour.'0000';
				$returnArray['forecast'][$i]['timestamp']		= $timeOurs = $utcDiff + strtotime($timeString);
				$returnArray['forecast'][$i]['date'] 			= date('Ymd', $timeOurs);
				$returnArray['forecast'][$i]['hour'] 			= date('H', $timeOurs);
#	12 hour forecast data
				if (isset ($arr->weersymbool_12u) ) {
					$tekst										= (string) $arr->weersymbool_12u;
					$returnArray['forecast'][$i]['iconL']		= $tekst;
					$returnArray['forecast'][$i]['iconUrlL']	= $SITE['hwaIconsDir'].$tekst.$this->iconType;					
					$tekst 										= (string) $arr->weersomschrijving_12u;
					if (!isset ($this->descriptions[$tekst]) ) {
						$returnArray['forecast'][$i]['weatherDescL'] = $tekst;
					} else {
						$returnArray['forecast'][$i]['weatherDescL'] = $this->descriptions[$tekst];
					}
					if (isset($arr->maximumtemperatuur)) {
						$returnArray['forecast'][$i]['part']		= 'daytime';
						$returnArray['forecast'][$i]['tempLow']		= $returnArray['forecast'][$i]['tempLowNU']	= '';
						if ($amount < $tempMaxSave)	{$amount = $tempMaxSave;}
						$amount										= (string) 	$arr->maximumtemperatuur;
						$returnArray['forecast'][$i]['tempHighNU']	= $result = (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
						$returnArray['forecast'][$i]['tempHigh']	= $result.$SITE['uomTemp'];
					} else {
						$returnArray['forecast'][$i]['part']		= 'nighttime';
						$amount										= (string) $arr->minimumtemperatuur;
						if ($amount > $tempMinSave)	{$amount = $tempMinSave;}
						$returnArray['forecast'][$i]['tempLowNU']	= $result = (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
						$returnArray['forecast'][$i]['tempLow']		= $result.$SITE['uomTemp'];
						$returnArray['forecast'][$i]['tempHigh']	= $returnArray['forecast'][$i]['tempHighNU'] = '';
					}
					$returnArray['forecast'][$i]['rainChance'] 		= (int) $arr->neerslagkans;
					$returnArray['forecast'][$i]['snowChance'] 		= (int) $arr->sneeuwkans;
					$returnArray['forecast'][$i]['thunderChance']	= (int) $arr->onweerkans;
#
					$amount											= (string) $arr->neerslaghoeveelheid;
					$result 										= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
					$returnArray['forecast'][$i]['rainNUL'] 		= $result + $rainSave;
					$returnArray['forecast'][$i]['rainL'] 			= ($result + $rainSave).$SITE['uomRain'];
					$amount											= (int) $arr->windsnelheid;
					if ($windSave > $amount) {$amount = $windSave;}
					$returnArray['forecast'][$i]['windSpeedL']		= $amount.' bft';
					$returnArray['forecast'][$i]['windSpeedNUL']	= $amount;

#					echo '<!-- rain at '.$timeString.' '.($result + $rainSave).' == '.$returnArray['forecast'][$i]['rainL'] .'  -->'.PHP_EOL;

				}  // eo 12 hour data				
# 	6 hours data
				if (isset ($arr->neerslaghoeveelheid) ) {
					$amount										= (string) $arr->neerslaghoeveelheid;
					$returnArray['forecast'][$i]['rainNU'] 		= $result =  (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
					$returnArray['forecast'][$i]['rain'] 		= $result.$SITE['uomRain'];
					$rainSave									= $rainSave + $result;
					if (isset($arr->maximumtemperatuur)) {
						$amount			= (string) $arr->maximumtemperatuur;
						$tempMaxSave	= (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
					} else {
						$amount			= (string) $arr->minimumtemperatuur;
						$tempMinSave	= (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
					}
				}
#	3 hours data
				$tekst											= (string) $arr->weersymbool;
				if ($tekst == '') {
					$string = '<!-- '.$pageName.' -- invalid input for '.$timeString.' -->'.PHP_EOL;
					echo $string;
					unset ($returnArray['forecast'][$i]);
					$i = $i - 1;
					continue;
				}
				$returnArray['forecast'][$i]['icon']			= $tekst;
				$returnArray['forecast'][$i]['iconUrl']			= $SITE['hwaIconsDir'].$tekst.$this->iconType;
				(string) $arr->weersymbool;	
				$tekst 											= (string) $arr->weersomschrijving;	
				if ($tekst == '') {
					$string = '<!-- '.$pageName.' -- invalid input for '.$timeString.' -->'.PHP_EOL;
					echo $string;
					unset ($returnArray['forecast'][$i]);
					$i = $i - 1;
					continue;
				}
				if (!isset ($this->descriptions[$tekst]) ) {
					$returnArray['forecast'][$i]['weatherDesc'] = $tekst;
				} else {
					$returnArray['forecast'][$i]['weatherDesc'] = $this->descriptions[$tekst];
				}				
				$amount											= (string) 	$arr->temperatuur;
				$returnArray['forecast'][$i]['tempNU']			= $result = (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
				$returnArray['forecast'][$i]['temp']			= $result.$SITE['uomTemp'];
				$amount											= (string) 	$arr->luchtdruk;
				$returnArray['forecast'][$i]['baroNU']			= $result = (string) wsConvertBaro($amount, $this->uomBaro,$SITE['uomBaro']);
				$returnArray['forecast'][$i]['baro']			= (string) $arr->luchtdruk.$SITE['uomBaro'];
				$amount											= (int) $arr->windsnelheid;
				$returnArray['forecast'][$i]['windSpeed']		= $amount.' bft';
				$returnArray['forecast'][$i]['windSpeedNU']		= $amount;
				if ($amount > $windSave)	{$windSave = $amount;}
				$dir											= strtoupper($arr->windrichting);
				if ($dir == '') {$dir = 'N';}
				$returnArray['forecast'][$i]['windDir'] 		= $this->windDir[$dir];
				$returnArray['forecast'][$i]['windDirIcon'] 	= (string) $arr->windrichting;
				
//				echo '<!--  '; print_r ($returnArray['forecast'][$i]);  echo ' -->';	
				if (isset ($arr->weersymbool_12u) ) {
					$rainSave		= 0;		// 	used to gather 12 hour data for 6 hour data
					$tempMaxSave 	= -100;		//  12 hours data from 3 hours data
					$tempMinSave 	= +100;		//  same
					$windSave		= 0;		//  same
				}
			}	// eo for loop forecasts
		}  // eo makeRequest processing
		$this->rawdata='';
		
                if (!isset ($returnArray['forecast']) || count($returnArray['forecast'])  < 3  ) { return $returnArray;}

		if ($this->enableCache && !empty($this->cachePath)){
			$this->writeToCache($returnArray);
		}
		$this->rawdata='';
		
#		echo '<pre>'.PHP_EOL; print_r ($returnArray); exit;

		return $returnArray;
	} // eof getWeatherData
	
	private function loadFromCache(){
		$cachefile      = $this->cacheFile;
	        $cacheAllowed   = $this->cacheTime;
	        if (isset($_REQUEST['force']) && $_REQUEST['force'] == 'hwa') {
                        echo "<!-- Weatherforecast ($cachefile) not used, force was set -->".PHP_EOL;
                        return;                
                } 
		if (file_exists($cachefile)){	
			$file_time      = filemtime($cachefile);
			$now            = time();
			$diff           = ($now - $file_time);
		        echo  "<!-- hwaCreateArr.php ($cachefile)
        cache time   = ".date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $cacheAllowed (seconds) -->".PHP_EOL;	
			if ($diff <= $cacheAllowed){
				echo "<!-- hwaCreateArr.php ($cachefile) loaded from cache  -->".PHP_EOL;
				$returnArray    =  unserialize(file_get_contents($cachefile));
				return $returnArray;
			}
		}		
	} // eof loadFromCache
	
	private function writeToCache($data){
		if (!file_exists($this->cachePath)){
			mkdir($this->cachePath, 0777);   // attempt to make the cache dir
		}
	//			print_r ($data); return;
		if (!file_put_contents($this->cacheFile, serialize($data))){   
			echo PHP_EOL."<!-- Could not save data to cache $this->cacheFile. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
		} else {echo "<!-- hwaCreateArr.php ($this->cacheFile) saved to cache  -->".PHP_EOL;}
#		echo '<pre>';print_r($data); exit;
	} // eof writeToCache
	
	private function makeRequest(){
		global $SITE;
		if ($this->test == TRUE) {
			$this->rawData  = file_get_contents('testHwa.xml');
		} else {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
#		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $SITE['curlFollow']);
		$this->rawData = curl_exec ($ch);
		curl_close ($ch);
		}
		if (empty($this->rawData)){
			return false;
		}else{
			return true;
		}	
	} // eof makeRequest
	
}