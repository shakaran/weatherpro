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
$pageName	= 'yrnoCreateDetailArr.php';
$pageVersion	= '3.20 2015-08-02';
#-------------------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# retrieve weather infor from weathersource (YrNo weather) 
# and return array with retrieved data in the desired language and units C/F
#
#  http://www.yr.no/place/Canada/Other/Toronto/forecast_hour_by_hour.xml
#   location Canada/Other/Toronto
#--------------------------------------------------------------------------------------------------
class yrnoDetailWeather{
	# public variables
	public $location	= 'Canada/Other/Toronto'; 
	public $lang		= 'en';			// supported languages english only
	public $uom		= 'metric';	        // units always celsius / hPa / m/s / mm?
	# private variables
	private $uomTemp	= 'c';
	private $uomBaro	= 'hpa';
	private $uomWind	= 'ms';
	private $uomRain	= 'mm';
	private $cachePath	= 'cache';		// cache dir is created when not available
	private $cacheTime 	= 7200; 		// Cache expiration time Default: 3600 seconds = 1 Hour
	private $cacheFile	= 'xxx';
	private $apiUrlpart = array(		// http://www.yr.no/place/Canada/Other/Toronto/forecast_hour_by_hour.xml
	 0 => 'http://www.yr.no/place/',
	 1 => 'userinput',
	 2 => '/forecast_hour_by_hour.xml'
	);
	private $weatherApiUrl = '';
	private $rawData;
#--------------------------------------------------------------------------------------------------
# public functions	
#--------------------------------------------------------------------------------------------------
	public function getWeatherDetailData($userLocation = '') {
		global  $toTemp,  $toWind,  $toRain, $toBaro, $pageName, $cacheDir,
		        $uomTemp, $uomWind, $uomRain,$uomBaro;		
#----------------------------------------------------------------------------------------------
# clean user input
#----------------------------------------------------------------------------------------------
		$userLocation 	        = trim($userLocation);
		$this->location         = $userLocation;
		$filename               = str_replace( '/', '', $userLocation);
		$this->cachePath        = $cacheDir;
		$uoms                   = $toTemp.$toWind.$toRain.$toBaro;
		$this->cacheFile        = $this->cachePath.$pageName.'-'.$this->lang.'-'.$filename.'-' .$uoms;
#----------------------------------------------------------------------------------------------
# try loading data from cache
#----------------------------------------------------------------------------------------------		
		$returnArray    = $this->loadFromCache();	// load from cache returns data only when its data is valid
		if (!empty($returnArray)) {
			return $returnArray;			// if data is in cache and valid return data to calling program
		}  // eo valid data, return to calling program
#----------------------------------------------------------------------------------------------
# combine everything into required url
#----------------------------------------------------------------------------------------------
		#  http://www.yr.no/place/Canada/Other/Toronto/forecast_hour_by_hour.xml
		$this->apiUrlpart[1] = $this->location;
		$this->weatherApiUrl = '';
		for ($i = 0; $i < count($this->apiUrlpart); $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		if (!$this->makeRequest()) {  // load xml from url and process
		        ws_message ('<h3>ERROR yrnoCreateDetailArr.php ('.__LINE__.'): Unable to retrieve xml for '.$this->weatherApiUrl .'</h3>',true);
		        return false;
		}
                $xml = new SimpleXMLElement($this->rawData);
//			print_r ($xml);
                $returnDetails = array();
#--------------------------------------------------------------------------------------------------
# first, get and save request info / units etc
#--------------------------------------------------------------------------------------------------
                $returnDetails['request_info']['type'] 	        = 'xml';
                $returnDetails['request_info']['city']	        = (string) $xml->location->name.'-'.$xml->location->country;
                $returnDetails['request_info']['logo'] 	        = (string) $xml->credit->link['text'];
                $returnDetails['request_info']['link'] 	        = (string) $xml->credit->link['url'];
                $returnDetails['request_info']['uomTemp'] 	= $this->uomTemp;
                $returnDetails['request_info']['uomWind'] 	= $this->uomWind;
                $returnDetails['request_info']['uomRain'] 	= $this->uomRain;
                $returnDetails['request_info']['uomBaro'] 	= $this->uomBaro;
                $returnDetails['request_info']['lastupdate']    = (string) $xml->meta->lastupdate;
                $string                                         = (string) $xml->meta->nextupdate;
                $returnDetails['request_info']['nextupdate']    = $string;
                $returnDetails['request_info']['nextupdateunix']= strtotime($string);
#--------------------------------------------------------------------------------------------------
#  get forecast info
#--------------------------------------------------------------------------------------------------
                $end = count($xml->forecast->tabular->time);   // one forecast is one occurence of <time>...</time>
                for ($i = 0; $i < $end ; $i++){
                        # <time from="2013-05-08T11:00:00" to="2013-05-08T14:00:00">
                        $returnDetails['forecast'][$i]['date'] 	        = substr((string) $xml->forecast->tabular->time[$i-1]['to'],0,10);	
                        $returnDetails['forecast'][$i]['timeFrom']	= strtotime((string) $xml->forecast->tabular->time[$i-1]['from']);
                        $returnDetails['forecast'][$i]['timeTo'] 	= 
                        $returnDetails['forecast'][$i]['timestamp']     = strtotime((string) $xml->forecast->tabular->time[$i-1]['to']);
                        $data 						= $xml->forecast->tabular->time[$i-1];
                        # <symbol number="3" name="Partly cloudy" var="03d"/>
                        $returnDetails['forecast'][$i]['icon']	        = (string) $data->symbol['number'];				
                        $returnDetails['forecast'][$i]['weatherDesc']   = (string) $data->symbol['name'];
                        # <temperature unit="celsius" value="18"/>
                        $string 					= (string) $data->temperature['value'];	
                        $amount						= yrnoConvertTemp($string, $this->uomTemp);
                        $returnDetails['forecast'][$i]['tempNU'] 	= (string) $amount;
                        $returnDetails['forecast'][$i]['temp'] 	        = (string) $amount.$uomTemp;
                        # <windSpeed mps="4.7" name="Gentle breeze"/>
                        $string						= (string) $data->windSpeed['mps'];
                        $amount 					= yrnoConvertWind($string, $this->uomWind);
                        $returnDetails['forecast'][$i]['windSpeedNU']   = (string) $amount;
                        $returnDetails['forecast'][$i]['beaufort']	= yrnobeaufort ($string, $this->uomWind);
                        $returnDetails['forecast'][$i]['windSpeed']	= (string) $amount.$uomWind;
                        $returnDetails['forecast'][$i]['windTxt'] 	= (string) $data->windSpeed['name'];
                        # <windDirection deg="103.6" code="ESE" name="East-southeast"/>
                        $returnDetails['forecast'][$i]['windDir'] 	= (string) $data->windDirection['code'];
                        $returnDetails['forecast'][$i]['windDeg'] 	= (string) $data->windDirection['deg'];
                        # <precipitation value="0"/>
                        if (isset ($data->precipitation['maxvalue'])) {
                                $amount = $data->precipitation['minvalue'];
                                $string = (string) yrnoConvertRain($amount, $this->uomRain);
                                $amount = $data->precipitation['maxvalue'];
                                $string .='-'.(string) yrnoConvertRain($amount, $this->uomRain);
                        } else {
                                $amount = $data->precipitation['value'];
                                $string =(string) yrnoConvertRain($amount, $this->uomRain);					
                        }				
                        $returnDetails['forecast'][$i]['rainNU'] 	= $string;
                        $returnDetails['forecast'][$i]['rain'] 		= $string.$uomRain;
                        # <pressure unit="hPa" value="1014.8"/>
                        $string 										= (string) $data->pressure['value'];
                        $amount											= round(yrnoConvertBaro($string, $this->uomBaro));
                        $returnDetails['forecast'][$i]['baroNU'] 	= (string) $amount;				
                        $returnDetails['forecast'][$i]['baro'] 		= (string) $amount.$uomBaro;
                }	// eo for loop forecasts
                if (!isset ($returnDetails['forecast']) || count($returnDetails['forecast'])  < 3  ) { return $returnDetails;}
		$this->writeToCache($returnDetails);
		return $returnDetails;
		

	} // eof getWeatherData
	
	private function loadFromCache(){
		if (!file_exists($this->cacheFile)){ 
                        ws_message ('<!-- module yrnoCreateDetailArr.php ('.__LINE__.'): '.$this->cacheFile.' not found in cache -->');
                        return; 
                }  // no cached file found => goback
	        if (isset($_REQUEST['force']) && $_REQUEST['force'] == 'yrno') {
                        ws_message ('<!-- module yrnoCreateDetailArr.php ('.__LINE__.'): '.$this->cacheFile.' not used, force was set -->',true);
                return;                
                }                                                     // no cached file found => goback
                $returnArray    = unserialize(file_get_contents($this->cacheFile));	
                $updatetime     = $returnArray['request_info']['nextupdateunix'];
                $updatestring   = date ('c',$updatetime);
                $now            = time();
                $nowtimestring  = date ('c',$now);
                if ($now > $updatetime){ return; }                      // new update should be available => goback
                ws_message ('<!-- module yrnoCreateDetailArr.php ('.__LINE__.'): '.$this->cacheFile." loaded from cache
	next-update at $updatestring ($updatetime)
	it is now      $nowtimestring ($now)  -->"); 
                return $returnArray;
	} // eof loadFromCache
	
	private function writeToCache($data){
		if (!file_put_contents($this->cacheFile, serialize($data))){   
			exit ("ERROR - FATAL module yrnoCreateDetailArr.php (".__LINE__."): ERROR Could not save data to cache ($this->cacheFile).<br />Please make sure your cache directory exists and is writable.<bre />Program ends");
		} 
		else {ws_message ('<!-- module yrnoCreateDetailArr.php ('.__LINE__.'): '.$this->cacheFile.' saved to cache  -->');
		}
	} // eof writeToCache
	
	private function makeRequest(){
	        global $scriptDir;
		$test = false;
		if ($test) {
			$this->rawData  = file_get_contents($scriptDir.'testDetail.xml');
			ws_message ('<!-- module yrnoCreateDetailArr.php ('.__LINE__.'): test file testDetail.xml loaded -->',true);
		} 
		else {	ws_message ('<!-- module yrnoCreateDetailArr.php ('.__LINE__.'): curl for: '.$this->weatherApiUrl.' -->');
                        $this->rawData =yrnoCurl ($this->weatherApiUrl);
		}
		if (empty($this->rawData)){ return false; }
		
		# lets check if good forecast data is retrieved
		$search = array ('<weatherdata>','<forecast>');
                for ($i = 0; $i < count($search); $i++) {
                        $int = strpos($this->rawData , $search[$i]);
                        if (!$int > 0) {return false;}  // one of the needed data tags is not found => not good
                }
		# lets check for known errors
		$search = array ('<message>Not found</message>','<span><H1>Server Error','Service Unavailable','Error 504','Error 503');
		$error = false;
		for ($i = 0; $i < count($search); $i++) {
			$int = strpos($this->rawData , $search[$i]);
			if ($int > 0) {return false;}
		}
		return true;
	} // eof makeRequest
}
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
