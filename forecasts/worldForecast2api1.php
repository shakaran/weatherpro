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
$pageName       = 'worldForecast2api1.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2015-02-16';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2015-02-16 beta release 2.7 version
#-------------------------------------------------------------------------------
# Display a list of forecast date from World Weather
#-------------------------------------------------------------------------------
# First get the data from a weather class 
class worldWeather{
	# public variables
	public $location	= ''; 
	public $key		= ''; 
	# private variables
	private $uomTemp	= '&deg;C';
	private $uomBaro	= ' hPa';
	private $uomWind	= ' km/h';
	private $uomRain	= ' mm';
	private $enableCache    = true;		// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cachePath	= '';		// cache dir is created when not available
	private $cacheFile      = '';
	private $cacheTime 	= 3600; 	// Cache expiration time in seconds. Default: 1/1 Hour
	private $apiUrlpart = array(            //http://api.worldweatheronline.com/free/v1/weather.ashx?q=41.30068,-72.793671&format=xml&num_of_days=5&key=zegdt5rnspg8ypk8zuwpnnbw8
		0 => 'http://api.worldweatheronline.com/free/v1/weather.ashx?q=',
		1 => 'location',
		2 => '&format=xml&num_of_days=5&key=',
		3 => 'key'							
	);
	private $weatherApiUrl = '';
	private $rawData;
#--------------------------------------------------------------------------------------------------
# public functions	
#--------------------------------------------------------------------------------------------------
	public function getWeatherData($userLocation = '') {
		global $SITE, $pageName;
		#----------------------------------------------------------------------------------------------
		$this->location = $userLocation = $SITE['latitude'].','.$SITE['longitude'];
		#----------------------------------------------------------------------------------------------
		# try loading data from cache
		#----------------------------------------------------------------------------------------------		
		if ( $this->enableCache && !empty($SITE['cacheDir']) ){
			$this->cachePath= $SITE['cacheDir'];
			$uoms           = $SITE['uomTemp'].'_'.$SITE['uomWind'].'_'.$SITE['uomRain'];
			$from           = array('&deg;','∞','/',' ',',');
			$name           = $pageName.'_'.$this->location.'_'.$uoms;
			$name           = str_replace($from,'',$name);
			$this->cacheFile= $this->cachePath.$name;
			$returnArray=$this->loadFromCache();	// load from cache returns data only when its data is valid
			if (!empty($returnArray)) {		// if data is in cache and valid return data to calling program
				return $returnArray;
			}  // eo return to calling program
		}  // eo check cache
		#----------------------------------------------------------------------------------------------
		# combine user constants and input (1)location (2)units for temp etc  to required url
		#http://free.worldweatheronline.com/feed/weather.ashx?q=leuven,belgium&format=xml&num_of_days=5&key=0f9bf9919c100150121001
		#----------------------------------------------------------------------------------------------
		$this->apiUrlpart[1] =	$this->location;
		$this->apiUrlpart[3] = 	$this->key;		
		for ($i = 0; $i < count($this->apiUrlpart); $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------
		if ($this->makeRequest()){
			$xml = new SimpleXMLElement($this->rawData);
			$returnArray = array();
			#--------------------------------------------------------------------------------------------------
			# first, get and save request info / units etc
			#--------------------------------------------------------------------------------------------------
			$i=0;  // headings
			$returnArray['request_info'][$i]['type'] 	= 'type';
			$returnArray['request_info'][$i]['city'] 	= 'city';
			$returnArray['request_info'][$i]['time']	= 'time';
			$returnArray['request_info'][$i]['uomTemp'] 	= 'uomTemp';
			$returnArray['request_info'][$i]['uomDistance'] = 'uomDistance';
			$returnArray['request_info'][$i]['uomBaro'] 	= 'uomBaro';
			$returnArray['request_info'][$i]['uomWind'] 	= 'uomWind';
			$i=1;  // data
			$returnArray['request_info'][$i]['type'] 	= (string) $xml->request->type;
			$returnArray['request_info'][$i]['city'] 	= (string) $xml->request->query;
			$returnArray['request_info'][$i]['time']	= (string) $xml->current_condition->observation_time;
			$returnArray['request_info'][$i]['uomTemp'] 	= '&deg;C';
			$returnArray['request_info'][$i]['uomDistance'] = ' km';
			$returnArray['request_info'][$i]['uomBaro'] 	= ' hpa';
			$returnArray['request_info'][$i]['uomWind'] 	= ' km/h';
			#--------------------------------------------------------------------------------------------------
			# get current condition descriptions
			#--------------------------------------------------------------------------------------------------
			$i=0;  // headings
			$returnArray['current_condition'][$i]['time']		= 'time';
			$returnArray['current_condition'][$i]['weatherCode']    = 'weatherCode';
			$returnArray['current_condition'][$i]['iconUrl']	= 'iconUrl';
			$returnArray['current_condition'][$i]['weatherDesc']    = 'description';
			$returnArray['current_condition'][$i]['tempNU'] 	= 'temp';
			$returnArray['current_condition'][$i]['temp'] 		= 'temp';
			$returnArray['current_condition'][$i]['windNU']		= 'wind';
			$returnArray['current_condition'][$i]['wind']		= 'wind';
			$returnArray['current_condition'][$i]['rainNU']		= 'rain';
			$returnArray['current_condition'][$i]['rain']		= 'rain';
			$returnArray['current_condition'][$i]['baroNU']		= 'baro';
			$returnArray['current_condition'][$i]['baro']		= 'baro'; 			
			$returnArray['current_condition'][$i]['humidity']	= 'humidity';
			$returnArray['current_condition'][$i]['windDir']	= 'windDir';
			$returnArray['current_condition'][$i]['windDeg']	= 'windDeg';
			$returnArray['current_condition'][$i]['cloudCover']	= 'cloudCover';  // ?  ??  percent ?
			$i=1;  // data
			$returnArray['current_condition'][$i]['time']		= (string) $xml->current_condition->observation_time;
			$returnArray['current_condition'][$i]['weatherCode']    = (string) $xml->current_condition->weatherCode;
			$returnArray['current_condition'][$i]['iconUrl']	= (string) $xml->current_condition->weatherIconUrl;
			$returnArray['current_condition'][$i]['weatherDesc']    = (string) $xml->current_condition->weatherDesc;			
			$amount 						= (string) $xml->current_condition->temp_C;
			$returnArray['current_condition'][$i]['tempNU'] 	= (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
			$returnArray['current_condition'][$i]['temp'] 		= (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']).$SITE['uomTemp'];
			$amount 						= (string) $xml->current_condition->windspeedKmph;
			$returnArray['current_condition'][$i]['windNU']		= (string) wsConvertWindspeed($amount, $this->uomWind,$SITE['uomWind']);
			$returnArray['current_condition'][$i]['wind']		= (string) wsConvertWindspeed($amount, $this->uomWind,$SITE['uomWind']).$SITE['uomWind'];			
			$amount 						= (string) $xml->current_condition->precipMM;
			$returnArray['current_condition'][$i]['rainNU']		= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
			$returnArray['current_condition'][$i]['rain']		= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']).$SITE['uomRain'];			
			$amount 						= (string) $xml->current_condition->pressure;
			$returnArray['current_condition'][$i]['baroNU']		= (string) wsConvertBaro($amount, $this->uomBaro,$SITE['uomBaro']);
			$returnArray['current_condition'][$i]['baro']		= (string) wsConvertBaro($amount, $this->uomBaro,$SITE['uomBaro']).$SITE['uomBaro'];
				
			$returnArray['current_condition'][$i]['humidityNU']	= (string) $xml->current_condition->humidity;
			$returnArray['current_condition'][$i]['humidity']	= (string) $xml->current_condition->humidity.' %';
			
			$returnArray['current_condition'][$i]['windDir']	= (string) $xml->current_condition->winddir16Point;
			$returnArray['current_condition'][$i]['windDeg']	= (string) $xml->current_condition->winddirDegree;
			$returnArray['current_condition'][$i]['cloudCover']	= (string) $xml->current_condition->cloudcover;  // ?  ??  percent ?
			#--------------------------------------------------------------------------------------------------
			#  get forecast info
			#--------------------------------------------------------------------------------------------------
			$i = 0;
			$returnArray['forecast'][$i]['date'] 		= 'date';
			$returnArray['forecast'][$i]['weatherCode']	= 'weatherCode';
			$returnArray['forecast'][$i]['iconUrl'] 	= 'iconUrl';
			$returnArray['forecast'][$i]['weatherDesc']     = 'description';
			$returnArray['forecast'][$i]['tempLowNU']	= 'tempLow';
			$returnArray['forecast'][$i]['tempLow']		= 'tempLow';
			$returnArray['forecast'][$i]['tempHighNU']	= 'tempHigh';
			$returnArray['forecast'][$i]['tempHigh']	= 'tempHigh';
			$returnArray['forecast'][$i]['windNU']		= 'wind';
			$returnArray['forecast'][$i]['wind']		= 'wind';
			$returnArray['forecast'][$i]['rainNU'] 		= 'rain';
			$returnArray['forecast'][$i]['rain'] 		= 'rain'; 			
			$returnArray['forecast'][$i]['windDir'] 	= 'windDir';
			$returnArray['forecast'][$i]['windDeg'] 	= 'windDeg';

			$i2 =count($xml->weather);
			for ($i = 1; $i <= $i2; $i++){
				$returnArray['forecast'][$i]['date'] 		= strtotime((string) $xml->weather[$i-1]->date);
				$returnArray['forecast'][$i]['weatherCode']	= (string) $xml->weather[$i-1]->weatherCode;
				$returnArray['forecast'][$i]['iconUrl'] 	= (string) $xml->weather[$i-1]->weatherIconUrl;
				$returnArray['forecast'][$i]['weatherDesc']     = langtransstr((string) $xml->weather[$i-1]->weatherDesc);
				$amount						= (string) $xml->weather[$i-1]->tempMinC;
				$returnArray['forecast'][$i]['tempLowNU']	= (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
				$returnArray['forecast'][$i]['tempLow']		= (string) $returnArray['forecast'][$i]['tempLowNU'].$SITE['uomTemp'];
				$amount						= (string) $xml->weather[$i-1]->tempMaxC;
				$returnArray['forecast'][$i]['tempHighNU']	= (string) wsConvertTemperature($amount, $this->uomTemp,$SITE['uomTemp']);
				$returnArray['forecast'][$i]['tempHigh']	= (string) $returnArray['forecast'][$i]['tempHighNU'].$SITE['uomTemp'];
				$amount						= (string) $xml->weather[$i-1]->windspeedKmph;
				$returnArray['forecast'][$i]['windNU']		= (string) wsConvertWindspeed($amount, $this->uomWind,$SITE['uomWind']);
				$returnArray['forecast'][$i]['wind']		= (string) $returnArray['forecast'][$i]['windNU'].$SITE['uomWind'];
				$amount						= (string) $xml->weather[$i-1]->precipMM;
				$returnArray['forecast'][$i]['rainNU'] 		= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
				$returnArray['forecast'][$i]['rain'] 		= (string) $returnArray['forecast'][$i]['rainNU'].$SITE['uomRain'];
				$returnArray['forecast'][$i]['windDir'] 	= (string) $xml->weather[$i-1]->winddir16Point;
				$returnArray['forecast'][$i]['windDeg'] 	= (string) $xml->weather[$i-1]->winddirDegree;

			}	// eo for loop forecasts
                        if ($this->enableCache && !empty($this->cachePath)){
                                $this->writeToCache($returnArray);
                        }
		        $this->rawdata  = '';
		        return $returnArray;	  
		}  // eo makeRequest processing
		return false;
	} // eof getWeatherData
	
	private function loadFromCache(){
		if (file_exists($this->cacheFile)){	
			$file_time = filemtime($this->cacheFile);
			$now = time();
			$diff = ($now-$file_time);		
			if ($diff <= $this->cacheTime){
				echo "<!-- weatherdata ($this->cacheFile) loaded from cache cachetime = $diff - allowed = $this->cacheTime -->".PHP_EOL;
				$returnArray =  unserialize(file_get_contents($this->cacheFile));
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
			echo "<!-- <br />Could not save (".$this->cacheFile.") to cache. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
		} else {echo "<!-- Weatherdata ($this->cacheFile) saved to cache -->".PHP_EOL;}
	} // eof writeToCache
	
	private function makeRequest(){	
		global $SITE;
		$test= false;
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
                curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, $SITE['curlFollow']);
                $this->rawData = curl_exec ($ch);
                curl_close ($ch);
		if (empty($this->rawData))      {return false;}
		if (strpos ($this->rawData, 'API key does not have permission') ) {return false;}
		return true;	
	} // eof makeRequest
	
}
$weather        = new worldWeather ();
$weather->key   = $SITE['worldKey'];
$returnArray    = $weather->getWeatherData(' ');
if (!$returnArray) {
        echo '<div class="blockDiv">'.PHP_EOL;
        echo '<h3 class="blockHead">&nbsp;World Weather 5 ' . langtransstr('day forecast for').' '.$SITE['organ']."</h3>".PHP_EOL;
        echo '<h3>Forecast not available </h3></div>'.PHP_EOL;
        return;
}
#---------------------------------------------------------------------------
# define array and fill first row with headings
#---------------------------------------------------------------------------
$forecast = array ();   			
$forecast[0]['period']		=langtransstr('date'); //  .' / '.langtransstr('period');
$forecast[0]['condition']	=langtransstr('forecast');
$forecast[0]['icon']		=langtransstr('icon');
$forecast[0]['tempLow']		=langtransstr('low');
$forecast[0]['tempHigh']	=langtransstr('high');
$forecast[0]['rain']		=langtransstr('rain');
$forecast[0]['wind']		=langtransstr('wind').langtransstr('speed').'<br />'.langtransstr('and').' '.langtransstr('direction');
#---------------------------------------------------------------------------
# load icon translate if necessary
include_once ('wsIconUrl.php');
#---------------------------------------------------------------------------
#process each forecast
$id='1';
foreach ($returnArray['forecast'] as $arr) {
	if ($arr['windDir'] <> 'windDir') {   // skip first row with headers
		$forecast[$id]['period'] = langtransstr(date('l',$arr['date'])).' '.date ('j ',$arr['date']).langtransstr(date ('M',$arr['date']));
		$forecastTxt	= langtransstr($arr['weatherDesc']);
		$forecast[$id]['condition']	= str_replace( ' ', '<br />', $forecastTxt);
		$notUsed = '';	$iconOut='';	$iconUrlOut = '';
		wsChangeIcon ('world',$arr['weatherCode'], $iconOut, $arr['iconUrl'], $iconUrlOut, $notUsed);
		$forecast[$id]['iconUrl']	= '<img alt="icon '.$arr['weatherDesc'].'" src="'.$iconUrlOut.'" style="height: 45px; margin: 0px; padding: 0px;"/>';
		if ($arr['tempLow'] < 0) {
			$forecast[$id]['tempLow'] = '<span style="color: red;">'.$arr['tempLow'].'</span>';
		} else {
			$forecast[$id]['tempLow'] = $arr['tempLow'];
		}		
		if ($arr['tempHigh'] < 0) {
			$forecast[$id]['tempHigh'] = '<span style="color: red;">'.$arr['tempHigh'].'</span>';
		} else {
			$forecast[$id]['tempHigh'] = $arr['tempHigh'];
		}		
		$forecast[$id]['rain']	= $arr['rain'];
		$forecast[$id]['wind']	= $arr['wind'].'<br />'.langtransstr ('from the ').' '.langtransstr ($arr['windDir']);
		$id++;
	}
}
#-------------------------------------------------------------------------------
# now we are going to print the data to the screen
#-------------------------------------------------------------------------------
$rowcolor=0;
echo '<div class="blockDiv">'.PHP_EOL;
echo '<h3 class="blockHead">&nbsp;World Weather 5 ' . langtransstr('day forecast for').' '.$SITE['organ']."</h3>".PHP_EOL;
echo '<br />
<table class="genericTable">
<tbody>
<tr class="row-dark">';
// print headings
echo '
<th rowspan="2">'.$forecast[0]['period'].'</th>
<th rowspan="2" colspan="2">'.langtransstr('forecast').'</th>
<th colspan="2">'.langtransstr('temperature').'</th>
<th rowspan="2">'.$forecast[0]['rain'].'</th>
<th rowspan="2">'.$forecast[0]['wind'].'</th>
</tr>'.PHP_EOL;
echo '
<tr class="row-dark">
<th >'.$forecast[0]['tempLow']	.'</th>
<th >'.$forecast[0]['tempHigh']	.'</th>
</tr>';
$style='row-light';
for ($i=1;$i<=count($forecast)-1;$i++) {
	$string='';
	$string.='
<tr class="'.$style.'"  style="height: 40px;">
<td>'.$forecast[$i]['period'].'</td>
<td>'.$forecast[$i]['condition'].'</td>
<td>'.$forecast[$i]['iconUrl'].'</td>
<td>'.$forecast[$i]['tempLow'].'</td>
<td>'.$forecast[$i]['tempHigh'].'</td>
<td>'.$forecast[$i]['rain'].'</td>
<td>'.$forecast[$i]['wind'].'</td>';
	echo $string;
	echo '
</tr>'.PHP_EOL;  
	if ($rowcolor == 0)										// for odd even lines with different color
		{$style='row-dark'; $rowcolor = 1;} 
		else 
		{$style='row-light'; $rowcolor = 0;}
}

echo '
<tr class="'.$style.'">
<td colspan="3" style="text-align: right;"><img src="img/ww_logo2.png"  alt="World Weather Online logo" /></td>
<td colspan="4"><a href="http://www.worldweatheronline.com/" target="_blank" style="vertical-align: middle;">Weather forecast from World Weather Online</a></td>
</tr>
</tbody></table>';

echo '<br /><h3 class="blockHead">&nbsp;<small>'.
langtransstr('Original script by').'&nbsp;<a href="http://leuven-template.eu/" target="_blank">Weerstation Leuven</a>&nbsp;'.
langtransstr('Forecast for').'&nbsp;'.$returnArray['request_info'][1]['city'].'&nbsp;'.
langtransstr('updated').'&nbsp;'.$returnArray['request_info'][1]['time'].
'</small></h3>
</div>
';
?>
