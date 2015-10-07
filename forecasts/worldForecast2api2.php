<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName       ='worldForecast2api2.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2015-03-16';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2015-03-16 adapted for v2 of the API
#-------------------------------------------------------------------------------
# First get the data from a weather class 
#
class worldWeather{
	# public variables
	public $location	= '';  
	public $uom		= '';	
	public $key		= ''; 
	# private variables
	private $uomTemp	= 'c';
	private $uomBaro	= 'hPa';
	private $uomWind	= 'km/h';
	private $uomRain	= 'mm';
	private $enableCache    = true; 
	private $cacheFile      = '';
	private $cacheTime 	= 3600; // Cache expiration time in seconds. Default: 1/1 Hour
	private $icons_url 	= '';  	//  Location of the weather icons on weather server included in xml
	private $icons_ext 	= '';  			//  n/a
	private $apiUrlpart = array(    // http://api.worldweatheronline.com/free/v2/weather.ashx?q=51.11,-113.96&format=xml&num_of_days=5&key=4a3e73ef230d5b4fa2d8e27ae390
		0 => 'http://api.worldweatheronline.com/free/v2/weather.ashx?q=',	
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
		# clean user input
		#----------------------------------------------------------------------------------------------
                $lat                    = round($SITE['latitude'],2); 
                $lon                    = round($SITE['longitude'],2);
		$this->location         = $userLocation = $lat.','.$lon;
		#----------------------------------------------------------------------------------------------
		# try loading data from cache
		#----------------------------------------------------------------------------------------------	
		$toTemp                 = $SITE['uomTemp'];
		$toWind                 = $SITE['uomWind'];
		$toRain                 = $SITE['uomRain'];
		$this->cachePath        = $SITE['cacheDir'];
		if ( $this->enableCache ){
                        $filename       = $pageName.'_'.$lat.'_'.$lon.'_'.$toTemp.'_'.$toWind.'_'.$toRain;
		        $from           = array ('.',',');			
			$filename       = str_replace( $from, '_', $filename);
			$from           = array ('&deg;','∞','/',' ');
                        $filename       = str_replace( $from, '', $filename);
			$this->cacheFile= $this->cachePath.$filename;
			$returnArray    = $this->loadFromCache();	// load from cache returns data only when its data is valid
			if ($returnArray) {		                // if data is in cache and valid return data to calling program
				return $returnArray;
			}       // eo return to calling program
		}  // eo check cache
# echo $this->cacheFile; exit;
		#----------------------------------------------------------------------------------------------
		$this->apiUrlpart[1]    =	$this->location;
		$this->apiUrlpart[3]    = 	$this->key;			// key as supplied by weatherservice	
		for ($i = 0; $i < count($this->apiUrlpart); $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------
#		echo $this->weatherApiUrl; exit;
		$good_data             = $this->makeRequest();
		if (!$good_data) {
		        echo '<!-- <br />Could not load weatherdata ('.$this->weatherApiUrl.') - no information returned -->'.PHP_EOL;
		        if ($this->enableCache && !empty($this->cachePath)){
		                echo '<!-- Try to use the cache if it is not too old  -->'.PHP_EOL;
		                $this->cacheTime= $this->cacheTime * 5;
		                return $this->loadFromCache();
		        }

		        return false;
		}
                $xml = new SimpleXMLElement($this->rawData);
# echo '<pre>';	print_r ($xml); 
                $returnArray = array();
                #--------------------------------------------------------------------------------------------------
                # first, get and save request info / units etc
                #--------------------------------------------------------------------------------------------------
                $returnArray['request_info']['type'] 		= (string) $xml->request->type;
                $returnArray['request_info']['query'] 		= (string) $xml->request->query;
                $returnArray['request_info']['time']		= (string) $xml->current_condition->observation_time;
                #--------------------------------------------------------------------------------------------------
                # get current condition descriptions
                #--------------------------------------------------------------------------------------------------
                $returnArray['current_condition']['time']	= (string) $xml->current_condition->observation_time;
                $returnArray['current_condition']['weatherCode']= (string) $xml->current_condition->weatherCode;
                $returnArray['current_condition']['iconUrl']	= trim((string) $xml->current_condition->weatherIconUrl);
                $returnArray['current_condition']['weatherDesc']= (string) $xml->current_condition->weatherDesc;
                if (!strpos ($SITE['uomTemp'],'C') ) {$to_temp = 'f';} else {$to_temp = 'c';}			
                if ($to_temp == 'c') {
                        $returnArray['current_condition']['temp'] 	= (string) (string) $xml->current_condition->temp_C;
                        $returnArray['current_condition']['feelsLike'] 	= (string) (string) $xml->current_condition->FeelsLikeC;
                } else {
                        $returnArray['current_condition']['temp'] 	= (string) (string) $xml->current_condition->temp_F;
                        $returnArray['current_condition']['feelsLike'] 	= (string) (string) $xml->current_condition->FeelsLikeF;
                }     
                $amount 					= (string) $xml->current_condition->windspeedKmph;
                $returnArray['current_condition']['wind']	= (string) wsConvertWindspeed($amount, $this->uomWind);
                $amount 					= (string) $xml->current_condition->precipMM;
                $returnArray['current_condition']['rain']	= (string) wsConvertRainfall($amount, $this->uomRain);
                $amount 					= (string) $xml->current_condition->pressure;
                $returnArray['current_condition']['baro']	= (string) wsConvertBaro($amount, $this->uomBaro);
                        
                $returnArray['current_condition']['humidity']	= (string) $xml->current_condition->humidity;
                
                $returnArray['current_condition']['windDir']	= (string) $xml->current_condition->winddir16Point;
                $returnArray['current_condition']['windDeg']	= (string) $xml->current_condition->winddirDegree;
                $returnArray['current_condition']['cloudCover']	= (string) $xml->current_condition->cloudcover;  // ?  ??  percent ?
                $returnArray['current_condition']['visibility']	= (string) $xml->current_condition->visibility;  // miles ?			
                #--------------------------------------------------------------------------------------------------
                #  get forecast info
                #--------------------------------------------------------------------------------------------------
                $end_weather    =count($xml->weather);
                
                for ($i = 0; $i < $end_weather; $i++){
                        $returnArray['forecast'][$i] = array();
                        $object = $xml->weather[$i];
                        $returnArray['forecast'][$i]['date'] 		= strtotime($object->date);
                        if ($to_temp == 'c') {
                                $returnArray['forecast'][$i]['tempLow']	= (string) $object->mintempC;
                                $returnArray['forecast'][$i]['tempHigh']= (string) $object->maxtempC;
                        } else {
                                $returnArray['forecast'][$i]['tempLow']	= (string) $object->mintempF;
                                $returnArray['forecast'][$i]['tempHigh']= (string) $object->maxtempF;
                        }
                        $returnArray['forecast'][$i]['UV']              = (string) $object->uvIndex;
                        $object = $object->hourly[0];
                        $returnArray['forecast'][$i]['weatherCode']	= (string) $object->weatherCode;
                        $returnArray['forecast'][$i]['iconUrl'] 	= trim((string) $object->weatherIconUrl);
                        $returnArray['forecast'][$i]['weatherDesc']     = (string) $object->weatherDesc;

                        $amount						= (string) $object->windspeedKmph;
                        $returnArray['forecast'][$i]['wind']		= (string) wsConvertWindspeed($amount, $this->uomWind);
                        $amount						= (string) $object->precipMM;
                        $returnArray['forecast'][$i]['rain'] 		= (string) wsConvertRainfall($amount, $this->uomRain);
                        $returnArray['forecast'][$i]['rainChance'] 	= (string) $object->chanceofrain;
                        $returnArray['forecast'][$i]['windDir'] 	= (string) $object->winddir16Point;
                        $returnArray['forecast'][$i]['windDeg'] 	= (string) $object->winddirDegree;
                }	// eo for loop forecasts
		
                if ($this->enableCache && !empty($this->cachePath)){
                        $this->writeToCache($returnArray);
                }
                $this->rawdata='';
                return $returnArray;
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
			return false;
		}	
	} // eof loadFromCache
	
	private function writeToCache($data){
		if (!file_put_contents($this->cacheFile, serialize($data))){   
			echo "<!-- <br />Could not save (".$this->cacheFile.") to cache. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
		} else {echo "<!-- Weatherdata ($this->cacheFile) saved to cache -->".PHP_EOL;}
	} // eof writeToCache
	
	private function makeRequest(){	
		$ch             = curl_init();
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
		$this->rawData  = curl_exec ($ch);
		curl_close ($ch);
# echo $this->rawData;
		if (empty($this->rawData)){ return false; }
		$arr_errors     = array('does not have permission to access','not a valid key');
		$errors         = false;
		for ($n = 0; $n < count($arr_errors); $n++) {
		        $search = $arr_errors[$n];
		        $pos            = strpos($this->rawData,$search); 
		        if ($pos > 0) {$errors = true; break;}
		}  
		if (!$errors) {return true;}       // good data, no error string found
                echo '<!-- <br />Could not load weatherdata ('.$this->weatherApiUrl.') - error '.$search.'  -->'.PHP_EOL;
		return false;                   // data not retrieved - an error string was found	
	} // eof makeRequest
}
$weather = new worldWeather ();
$weather->key = $SITE['worldKey2'];
$returnArray = $weather->getWeatherData('');
if (!$returnArray) {
        echo '<div class="blockDiv">'.PHP_EOL;
        echo '<h3 class="blockHead">&nbsp;World Weather 5 ' . langtransstr('day forecast for').' '.$SITE['organ']."</h3>".PHP_EOL;
        echo '<h3 style="text-align: center;"> Errors retrieving information from World Weather Online, please try later </h3></div>'; return;
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
if (!strpos ($SITE['uomTemp'],'C') ) {$freeze = 32;} else {$freeze = 0;}
foreach ($returnArray['forecast'] as $arr) {
        $forecast[$id]['period'] = langtransstr(date('l',$arr['date'])).' '.date ('j ',$arr['date']).langtransstr(date ('M',$arr['date']));
        $forecastTxt	= langtransstr($arr['weatherDesc']);
        $forecast[$id]['condition']	= str_replace( ' ', '<br />', $forecastTxt);
        $notUsed = '';	$iconOut='';	$iconUrlOut = '';
        wsChangeIcon ('world',$arr['weatherCode'], $iconOut, $arr['iconUrl'], $iconUrlOut, $notUsed);
        $forecast[$id]['iconUrl']	= '<img alt="icon '.$arr['weatherDesc'].'" src="'.$iconUrlOut.'" style="height: 45px; margin: 0px; padding: 0px; vertical-align: bottom;"/>';
        if ($arr['tempLow'] < $freeze) {
                $forecast[$id]['tempLow'] = '<span style="color: red;">'.$arr['tempLow'].$SITE['uomTemp'].'</span>';
        } else {
                $forecast[$id]['tempLow'] = $arr['tempLow'].$SITE['uomTemp'];
        }		
        if ($arr['tempHigh'] < $freeze) {
                $forecast[$id]['tempHigh'] = '<span style="color: red;">'.$arr['tempHigh'].$SITE['uomTemp'].'</span>';
        } else {
                $forecast[$id]['tempHigh'] = $arr['tempHigh'].$SITE['uomTemp'];
        }		
        $forecast[$id]['rain']	= $arr['rain'].$SITE['uomRain'];
        $forecast[$id]['wind']	= $arr['wind'].$SITE['uomWind'].'<br />'.langtransstr ('from the ').' '.langtransstr ($arr['windDir']);
        $id++;
}
#-------------------------------------------------------------------------------
# now we are going to print the data to the screen
#-------------------------------------------------------------------------------
$rowcolor=0;
$world_fcst_small = '<div class="blockDiv">'.PHP_EOL;
$world_fcst_small .= '<h3 class="blockHead">&nbsp;World Weather 5 ' . langtransstr('day forecast for').' '.$SITE['organ']."</h3>".PHP_EOL;
$world_fcst_small .= '<table class="genericTable">
<tbody>
<tr class="row-dark">';
// print headings
$world_fcst_small .= '
<th rowspan="2">'.$forecast[0]['period'].'</th>
<th rowspan="2" colspan="2">'.langtransstr('forecast').'</th>
<th colspan="2">'.langtransstr('temperature').'</th>
<th rowspan="2">'.$forecast[0]['rain'].'</th>
<th rowspan="2">'.$forecast[0]['wind'].'</th>
</tr>'.PHP_EOL;
$world_fcst_small .=  '
<tr class="row-dark">
<th >'.$forecast[0]['tempLow']	.'</th>
<th >'.$forecast[0]['tempHigh']	.'</th>
</tr>';
$style='row-light';
for ($i=1; $i< count($forecast) ;$i++) {
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
	$world_fcst_small .=  $string;
	$world_fcst_small .=  '
</tr>'.PHP_EOL;  
	if ($rowcolor == 0)										// for odd even lines with different color
		{$style='row-dark'; $rowcolor = 1;} 
		else 
		{$style='row-light'; $rowcolor = 0;}
}
/*
echo '
<tr class="'.$style.'">
<td colspan="3" style="text-align: right;"><img src="img/ww_logo2.png"  alt="World Weather Online logo" style="vertical-align: bottom; height: 40px;"/></td>
<td colspan="4"><a href="http://www.worldweatheronline.com/" target="_blank" style="vertical-align: middle;">Weather forecast from World Weather Online</a></td>
</tr> */
$world_fcst_small .=  '
</tbody></table>'.PHP_EOL;
$world_fcst_credit =  '<h3 class="blockHead">
<small style="color: white;">
<a href="http://www.worldweatheronline.com/" title="Free Weather API" target="_blank" style="color: white;">Forecast by World Weather Online</a>&nbsp;'.
langtransstr('Script by').'<a href="http://leuven-template.eu/index.php" target="_blank" style="color: white;">'.' Weerstation Leuven</a>&nbsp;'.
langtransstr('Forecast for').'&nbsp;'.$returnArray['request_info']['query'].'&nbsp;'.
langtransstr('updated').'&nbsp;'.$returnArray['request_info']['time'].
'</small></h3>'.PHP_EOL;
echo $world_fcst_small;
echo $world_fcst_credit;
echo '</div>'.PHP_EOL;
?>
