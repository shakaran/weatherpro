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
$pageName	= 'yrnoCreateArr.php';
$pageVersion	= '3.20 2015-08-25';
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
#  http://www.yr.no/place/Belgium/Flanders/Wilsele/varsel.xml
#   location Belgium/Flanders/Wilsele
#--------------------------------------------------------------------------------------------------
class yrnoWeather{
	# public variables
	public $location	= 'Belgium/Flanders/wilsele';  
	public $lang		= 'en';		// supported languages english only
	public $uom		= 'metric';	// units always celsius / hPa / m/s / mm?
	# private variables
	private $uomTemp	= 'c';
	private $uomBaro	= 'hpa';
	private $uomWind	= 'ms';
	private $uomRain	= 'mm';
	private $enableCache    = true;	// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cachePath	= 'cache';	// cache dir is created when not available
	private $cacheTime 	= 7200; 	// Cache expiration time Default: 7200 seconds = 2 Hour
	private $cacheFile	= 'xxx';
	private $apiUrlpart = array(		// http://www.yr.no/place/Belgium/Flanders/Wilsele/varsel.xml
	 0 => 'http://www.yr.no/place/',
	 1 => 'userinput',
	 2 => '/varsel.xml'
	);
	private $weatherApiUrl = '';
	private $rawData;
#--------------------------------------------------------------------------------------------------
# public functions	
#--------------------------------------------------------------------------------------------------
	public function getWeatherData($userLocation = '') {
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
		if ( $this->enableCache) {
			$returnArray    = $this->loadFromCache();	// load from cache returns data only when its data is valid
			if (!empty($returnArray)) {
				return $returnArray;			// if data is in cache and valid return data to calling program
			}  // eo valid data, return to calling program
		}  // eo check cache
#----------------------------------------------------------------------------------------------
# combine everything into required url
#----------------------------------------------------------------------------------------------
		#  http://www.yr.no/place/Belgium/Flanders/Wilsele/varsel.xml
		$this->apiUrlpart[1]    = $this->location;
		$this->weatherApiUrl    = '';
		for ($i = 0; $i < count($this->apiUrlpart); $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		if (!$this->makeRequest()) {    // load xml from url, if fails stop
		        ws_message ('<h3>ERROR module yrnoCreateArr.php ('.__LINE__.'): Unable to retrieve xml for '.$this->weatherApiUrl .'</h3>',true);
		        return false;
		}
                $xml = new SimpleXMLElement($this->rawData);
                $returnArray = array();
#--------------------------------------------------------------------------------------------------
# first, get and save request info / units etc
#--------------------------------------------------------------------------------------------------	
                $returnArray['request_info']['type'] 	        = 'xml';
                $returnArray['request_info']['city']	        = (string) $xml->location->name.'-'.$xml->location->country;
                $returnArray['request_info']['logo'] 	        = (string) $xml->credit->link['text'];
                $returnArray['request_info']['link'] 	        = (string) $xml->credit->link['url'];
                $returnArray['request_info']['uomTemp'] 	= $this->uomTemp;
                $returnArray['request_info']['uomWind'] 	= $this->uomWind;
                $returnArray['request_info']['uomRain'] 	= $this->uomRain;
                $returnArray['request_info']['uomBaro'] 	= $this->uomBaro;
                $returnArray['request_info']['uomDistance']     = 'n/a';
                $returnArray['request_info']['lastupdate'] 	= (string) $xml->meta->lastupdate;
                $returnArray['request_info']['nextupdate'] 	= (string) $xml->meta->nextupdate;
                $returnArray['request_info']['nextupdate'] 	= (string) $xml->meta->nextupdate;
                $returnArray['request_info']['nextupdateunix']	= strtotime($returnArray['request_info']['nextupdate']);
#--------------------------------------------------------------------------------------------------
# YR.NO only supplies forecast, no current condition descriptions
#--------------------------------------------------------------------------------------------------
#--------------------------------------------------------------------------------------------------
#  get forecast info
#--------------------------------------------------------------------------------------------------
                $end = count($xml->forecast->tabular->time);  // one forecast is one occurence of <time>...</time>
                for ($i = 0; $i < $end; $i++){
                        # <time from="2013-05-08T05:00:00" to="2013-05-08T11:00:00" period="1">
                        $returnArray['forecast'][$i]['date'] 		= substr((string) $xml->forecast->tabular->time[$i]['from'],0,10);
                        $returnArray['forecast'][$i]['timeFrom']	= strtotime((string) $xml->forecast->tabular->time[$i]['from']);
                        $returnArray['forecast'][$i]['timeTo'] 		= 
                        $returnArray['forecast'][$i]['timestamp'] 	= strtotime((string) $xml->forecast->tabular->time[$i]['to']);
                        $returnArray['forecast'][$i]['hour'] 		= (string) $xml->forecast->tabular->time[$i]['period'];

                        if (!isset($offset_0) ){
 				$period					= $returnArray['forecast'][$i]['hour'];
 				$string					= (string) $xml->forecast->tabular->time[$i]['to'];
 				list($none,$time)			= explode ('T',$string);
                        	$hour					= explode (':', $time);	
                        	if ($period == '0') {
                        		$offset_0			= 6 - $hour[0];
                        		$returnArray['request_info']['offset'] = $offset_0;
                        		echo '<!-- offset night set to '.$offset_0.' hours -->'.PHP_EOL;
                        	}
                        }
                        $data 						= $xml->forecast->tabular->time[$i];
                        # <symbol number="2" name="Fair" var="02d"/>
                        $returnArray['forecast'][$i]['icon']		= (string) $data->symbol['number'];		// = icon number
                         $returnArray['forecast'][$i]['weatherDesc']	= (string) $data->symbol['name'];
                        # <temperature unit="celsius" value="11"/>
                        $string 					= (string) $data->temperature['value'];
                        $amount						= yrnoConvertTemp($string, $this->uomTemp);
                        $returnArray['forecast'][$i]['tempNU'] 		= (string) $amount;
                        $returnArray['forecast'][$i]['temp'] 		= (string) $amount.$uomTemp;
                        # <windSpeed mps="3.4" name="Gentle breeze"/>
                        $string						= (string) $data->windSpeed['mps'];
                        $amount 					= yrnoConvertWind($string, $this->uomWind);
                        $returnArray['forecast'][$i]['windSpeedNU']	= (string) $amount;
                        $returnArray['forecast'][$i]['beaufort']	= yrnobeaufort ($string, $this->uomWind);
                        $returnArray['forecast'][$i]['windSpeed']	= (string) $amount.$uomWind;
                        $returnArray['forecast'][$i]['windTxt'] 	= (string) $data->windSpeed['name'];
                        # <windDirection deg="105.3" code="ESE" name="East-southeast"/>
                        $returnArray['forecast'][$i]['windDir'] 	= (string) $data->windDirection['code'];
                        $returnArray['forecast'][$i]['windDeg'] 	= (string) $data->windDirection['deg'];
                        # <precipitation value="0.9"/>
                        if (isset ($data->precipitation['maxvalue'])) {
                                $amount                                 = $data->precipitation['minvalue'];
                                $string                                 = (string) yrnoConvertRain($amount, $this->uomRain);
                                $amount                                 = $data->precipitation['maxvalue'];
                                $string                                 .='-'.(string) yrnoConvertRain($amount, $this->uomRain);
                        } else {
                                $amount                                 = $data->precipitation['value'];
                                $string                                 =(string) yrnoConvertRain($amount, $this->uomRain);					
                        }				
                        $returnArray['forecast'][$i]['rainNU'] 		= $string;
                        $returnArray['forecast'][$i]['rain'] 		= $string.$uomRain;
                        # <pressure unit="hPa" value="1014.8"/>
                        $string 				        = (string) $data->pressure['value'];
                        $amount						= yrnoConvertBaro($string, $this->uomBaro);
                        $returnArray['forecast'][$i]['baroNU'] 		= (string) $amount;				
                        $returnArray['forecast'][$i]['baro'] 		= (string) $amount.$uomBaro;
                }	// eo for loop forecasts
                
                if (!isset ($returnArray['forecast']) || count($returnArray['forecast'])  < 3  ) { return $returnArray;}
                if ($this->enableCache){
                        $this->writeToCache($returnArray);
                }		
                return $returnArray;	
	} // eof getWeatherData
	
	private function loadFromCache(){
		if (!file_exists($this->cacheFile)){ 
                        ws_message (  '<!-- module yrnoCreateArr.php ('.__LINE__.'): '.$this->cacheFile.' not found in cache -->');
                        return; 
                }  // no cached file found => goback
	        if (isset($_REQUEST['force']) && $_REQUEST['force'] == 'yrno') {
                       ws_message (  "<!-- Weatherforecast ($this->cacheFile) not used, force was set -->",true);
                return;                
                }                                                     // no cached file found => goback
                $returnArray    = unserialize(file_get_contents($this->cacheFile));	
                $updatetime     = $returnArray['request_info']['nextupdateunix'];
                $updatestring   = date ('c',$updatetime);
                $now            = time();
                $nowtimestring  = date ('c',$now);
                if ($now > $updatetime){ return; }              // new update should be available => goback
                ws_message (  '<!-- module yrnoCreateArr.php ('.__LINE__.'):'.$this->cacheFile." loaded from cache
	next-update at $updatestring ($updatetime)
	it is now      $nowtimestring ($now)  -->"); 
                return $returnArray;
	} // eof loadFromCache
	
	private function writeToCache($data){
		if (!file_put_contents($this->cacheFile, serialize($data))){   
			exit("ERROR - FATAL module yrnoCreateArr.php (".__LINE__."): Could not save data to cache ($this->cacheFile).<br />Please make sure your cache directory exists and is writable.  Program ends");
		} else {ws_message (  '<!-- module yrnoCreateArr.php ('.__LINE__.'): '.$this->cacheFile.' saved to cache  -->');
		}
	} // eof writeToCache
	
	private function makeRequest(){
	        global $scriptDir;
    		$test = false;
		if ($test) {
			$this->rawData  = file_get_contents($scriptDir.'testYrno.xml');
			ws_message ('<!-- module yrnoCreateArr.php ('.__LINE__.'): test file testDetail.xml loaded -->',true);
		} 
		else {	ws_message ('<!-- module yrnoCreateArr.php ('.__LINE__.'): curl for: '.$this->weatherApiUrl.' -->');
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
		$error  = false;
		for ($i = 0; $i < count($search); $i++) {
			$int = strpos($this->rawData , $search[$i]);
			if ($int > 0) {return false;}   // error messages => not good
		}
		return true;    // no errors and correct data found => OK
	} // eof makeRequest	
}
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
