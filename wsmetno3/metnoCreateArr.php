<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'metnoCreateArr.php';
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# retrieve weather infor from weathersource (MetNo weather == YrNo) 
# and return array with retrieved data in the desired language and units C/F
#	&lat  = latitude   &lon  = longitude
#  http://api.met.no/weatherapi/locationforecast/1.9/?lat=$lat&lon=$lon
#   wilsele  50.8952   4.6974
#-----------------------------------------------------------------------
# version 3.00 release version  2014-07-25
# 2014-09-29 change in cache file name for negative lat lon - multiple - html error
#-----------------------------------------------------------------------
class metnoWeather{
	# public variables
	public $lat		= '50.8952';	// 
	public $lon		= '4.6974';	// 
# private variables
	private $uomTemp	= 'c';
	private $uomWindDir	= 'deg';
	private $uomWindSpeed	= 'ms';
	private $uomHum		= '%';
	private $uomBaro	= 'hpa';
	private $uomCloud	= '%';
	private $uomRain	= 'mm';
	private $lang	        = 'en';
	private $enableCache	= true;		        // cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cachePath	= 'cache';		// cache dir is created when not available
	private $cacheTime 	= 7200; 		// Cache expiration time Default: 7200 seconds = 2 Hour
	private $cacheFile	= 'xxx';
	private $apiUrlpart 	= array(		// http://api.met.no/weatherapi/locationforecast/1.9/?lat=50.8952&lon=4.6974
	 0 => 'http://api.met.no/weatherapi/locationforecast/1.9/?lat=',
	 1 => 'userinputLatitude',
	 2 => '&lon=',
	 3 => 'userinputLatitude'
	);
	private $weatherApiUrl	= '';
	private $rawData		= '';
#-----------------------------------------------------------------------
# main function 	
#-----------------------------------------------------------------------
public function getWeatherData($lat = '', $lon = '') {
        global  $toTemp,  $toWind,  $toRain, $toBaro, $pageName, $cacheDir,
                $uomTemp, $uomWind, $uomRain,$uomBaro;		
#----------------------------------------------------------------------------------------------
# clean user input
#----------------------------------------------------------------------------------------------
        $this->apiUrlpart[1]    = trim($lat);
        $this->apiUrlpart[3]    = trim($lon);
        $this->cachePath        = $cacheDir;
#----------------------------------------------------------------------------------------------
# try loading data from cache
#----------------------------------------------------------------------------------------------		
        if ( $this->enableCache && !empty($this->cachePath) ){
                $uoms                   = $toTemp.$toWind.$toRain.$toBaro;
                $pagestring             = str_replace ('.php','',$pageName);
                $userLocation           = $this->apiUrlpart[1].'_'.$this->apiUrlpart[3];
                $filename               = str_replace( '.', '',$userLocation);
                $this->cacheFile        = $this->cachePath.$pagestring.'_'.$this->lang.'_'.$filename.'_' .$uoms;
                $returnArray	        = $this->loadFromCache();	// load from cache returns data only when its data is valid
                if (!empty($returnArray)) {
                        return $returnArray;				// if data is in cache and valid return data to calling program
                }	// eo valid data, return to calling program
        }       // cache is checked
        else {	exit ('<h3>Metno forecast: ERROR  cache setting is disabled or cache folder not specified - data can not be cached.<br />Program ends<h3>.');
        }       // eo check cache
        #----------------------------------------------------------------------------------------------
        # combine everything into required url
        #  http://api.met.no/weatherapi/locationforecast/1.8/?lat=50.8952&lon=4.6974
        #----------------------------------------------------------------------------------------------
        $this->weatherApiUrl = '';
        $end	= count($this->apiUrlpart);
        for ($i = 0; $i < $end; $i++){
                $this->weatherApiUrl .= $this->apiUrlpart[$i];
        }
        #----------------------------------------------------------------------------------------------		
        if (!$this->makeRequest()) {  return false; }   // trying to load date failed

        $xml = new SimpleXMLElement($this->rawData);
        $returnArray = array();
        $utcDiff 								= date('Z');// to help to correct utc differences
        $yrNo			= true;
        if (isset ($xml->meta->model[1]['runended']) ) {$i = 1;}  else {$i=0; $yrNo = false;}
        $returnArray['dates']['filetime']		= date('c', strtotime((string) $xml->meta->model[$i]['runended']) );
        $unixtime                                       = strtotime( (string) $xml->meta->model[$i]['nextrun']);
        $returnArray['dates']['nextupdateunix']         = $unixtime;
        $returnArray['dates']['nextupdate']		= date('c', $unixtime);
        $returnArray['dates']['location']		= 'latitude='.$this->apiUrlpart[1]. ' - longitude= ' . $this->apiUrlpart[3];
			
#--------------------------------------------------------------------------------------------------
#  get forecast info
#--------------------------------------------------------------------------------------------------			
        $end = count($xml->product->time);   // 2012-03-09T18:00:00
        $i		= 0; // new forecast to assemble
        $oldTimeTo	= (string) $xml->product->time[0]['to'];
        $strTimeFrom 	= (string) $xml->product->time[1]['from'];
        $lastTimeTo     = strtotime($strTimeFrom);
        for ($n = 0; $n < $end; $n++) {
                $time			= $xml->product->time[$n];
                $data 			= $time->location;
                $strTimeTo		= (string) $time['to'];
                if ($strTimeTo <> $oldTimeTo) {
                        $i++;						// new set of forecasts started
                        $lastTimeTo	= strtotime($oldTimeTo);	
                        $strTimeFrom 	= $oldTimeTo;					
                        $oldTimeTo 	= $strTimeTo;
                }				
                if (isset ($data->temperature) ){	                // most info from a point record			
                        $returnArray['forecast'][$i]['dateFrom'] 	= $strTimeFrom;
                        $returnArray['forecast'][$i]['dateTo'] 		= $strTimeTo;
                        $returnArray['forecast'][$i]['timeFrom'] 	= strtotime($strTimeFrom);
                        $returnArray['forecast'][$i]['timestamp']	= $timeTo	= strtotime($strTimeTo);
                        $returnArray['forecast'][$i]['timeFrame']	= round( ($timeTo - $lastTimeTo)/3600);;
# 	<temperature id="TTT" unit="celcius" value="-4.0"/>				
                        $string 					= (string) $data->temperature['value'];
                        $amount						= round(metnoConvertTemp($string, $this->uomTemp));
                        $returnArray['forecast'][$i]['tempNU'] 		= (string) $amount;
                        $returnArray['forecast'][$i]['temp'] 		= (string) $amount.$uomTemp;
#  	<windDirection id="dd" deg="107.2" name="E"/>
                        $returnArray['forecast'][$i]['windDirTxt'] 	= (string) $data->windDirection['name'];
                        $returnArray['forecast'][$i]['windDirDeg'] 	= (string) $data->windDirection['deg'];
# 	<windSpeed id="ff" mps="0.7" beaufort="1" name="Flau vind"/>
                        $string						= (string) $data->windSpeed['mps'];
                        $amount 					= round( metnoConvertWind($string, $this->uomWindSpeed));
                        $returnArray['forecast'][$i]['windSpeedNU']	= (string) $amount;
                        $returnArray['forecast'][$i]['windSpeed']	= (string) $amount.$uomWind;
                        $returnArray['forecast'][$i]['windBft'] 	= (string) $data->windSpeed['beaufort'];
#	<humidity value="32.5" unit="percent"/>														
                        $returnArray['forecast'][$i]['hum']			= round((string) $data->humidity['value']);
# 	<pressure id="pr" unit="hPa" value="1020.5"/>
                        $string 									= (string) $data->pressure['value'];
                        $amount										= metnoConvertBaro($string, $this->uomBaro);
                        $returnArray['forecast'][$i]['baroNU'] 		= (string) $amount;				
                        $returnArray['forecast'][$i]['baro'] 		= (string) $amount.$uomBaro;
# 	<cloudiness id="NN" percent="67.4"/>
                        $returnArray['forecast'][$i]['clouds']		= (string) $data->cloudiness['percent'];
# 	<fog id="FOG" percent="0.2"/>
                        $returnArray['forecast'][$i]['fog']			= (string) $data->fog['percent'];
                        continue;
                } 

                $strTimeFrom            = (string) $time['from'];
                $intTimeFrom		= strtotime($strTimeFrom);
                $intTimeTo		= strtotime($strTimeTo);
                $timeFrame		= round( ($intTimeTo - $intTimeFrom) /3600);
                $utcDiff 		= date('Z', $intTimeTo);	
                $hour			= date('H',$intTimeTo - $utcDiff);
                $rest			= $hour % 6;
                if ($rest == 0)	{
                        $utcDiffHrs     = round ($utcDiff / 3600);
                        switch (true) {
                                case ($utcDiffHrs >= 9) :	$daypartDiff = 2;	break;
                                case ($utcDiffHrs >= 3) :	$daypartDiff = 1;	break;
                                case ($utcDiffHrs <= -9):	$daypartDiff = -2;	break;
                                case ($utcDiffHrs <= -3):	$daypartDiff = -1;	break;
                        default: $daypartDiff = 0;
                        }
                        $hour           = $daypartDiff + ($hour / 6);
                        if      ($hour < 0) {$hour = $hour + 4;} 
                        elseif  ($hour > 3) {$hour = $hour - 4;}
                        $returnArray['forecast'][$i]['dayPart'] = $hour;
#		<symbol id="PARTLYCLOUD" number="3"/>
                        $returnArray['forecast'][$i]['icon']		= (string) $data->symbol['number'];		// = icon number
                        $returnArray['forecast'][$i]['weatherDesc']	= (string) $data->symbol['id'];
#		<precipitation unit="mm" value="0.0" minvalue="0.0" maxvalue="0.0"/>
                        if (isset ($data->precipitation['maxvalue'])) {
                                $amount		= (string) $data->precipitation['minvalue'];
                                $string		= (string) metnoConvertRain($amount, $this->uomRain,$uomRain);
                                $amountMax	= (string) $data->precipitation['maxvalue'];
                                if ($amount <> $amountMax) {
                                        $string		.= '-'.(string) metnoConvertRain($amountMax, $this->uomRain,$uomRain);
                                        $amount 	= ($amount + $amountMax)/2;
                                }
                        } else {
                                $amount = (string) $data->precipitation['value'];
                                $string = (string) metnoConvertRain($amount, $this->uomRain,$uomRain);					
                        }				
                        $returnArray['forecast'][$i]['rainTxtNU'] 		= $string;
                        $returnArray['forecast'][$i]['rainTxt'] 		= $string.$uomRain;
                        $returnArray['forecast'][$i]['rain'] 			= (string) metnoConvertRain($amount, $this->uomRain,$uomRain);
#  eo 6 hour period / daypart										
                }
#
                if ($timeFrame == $returnArray['forecast'][$i]['timeFrame']) { // detail information but search for correct time period
#		<symbol id="PARTLYCLOUD" number="3"/>
                        $returnArray['forecast'][$i]['iconDtl']		= (string) $data->symbol['number'];		// = icon number
                        $returnArray['forecast'][$i]['weatherDescDtl']	= (string) $data->symbol['id'];
#		<precipitation unit="mm" value="0.0" minvalue="0.0" maxvalue="0.0"/>
                        if (isset ($data->precipitation['maxvalue'])) {
                                $amount		= (string) $data->precipitation['minvalue'];
                                $string		= (string) metnoConvertRain($amount, $this->uomRain);
                                $amountMax	= (string) $data->precipitation['maxvalue'];
                                if ($amount <> $amountMax) {
                                        $string .='-'.(string) metnoConvertRain($amountMax, $this->uomRain);
                                        $amount = ($amount + $amountMax)/2;
                                }
                        } 
                        else {
                                $amount = (string) $data->precipitation['value'];
                                $string = (string) metnoConvertRain($amount, $this->uomRain);					
                        }				
                        $returnArray['forecast'][$i]['rainTxtNUDtl'] 	= $string;
                        $returnArray['forecast'][$i]['rainTxtDtl'] 	= $string.$uomRain;
                        $returnArray['forecast'][$i]['rainDtl'] 	= (string) metnoConvertRain($amount, $this->uomRain);
                        continue;
                } 
        }	// eo for loop forecasts
#
        if (!isset ($returnArray['forecast']) || count($returnArray['forecast'])  < 3  ) { return $returnArray;}
        if ($this->enableCache && !empty($this->cachePath)){
                $this->writeToCache($returnArray);
        }		
        return $returnArray;
        
 
} // eof getWeatherData
#-----------------------------------------------------------------------
# load form cache
# if file in cache it is used 
#       when file not older than cache time allowed
#  or   if there will be no new forecast available yet
#-----------------------------------------------------------------------	
private function loadFromCache(){
	global $cron_all;
        if (!file_exists($this->cacheFile)){
                ws_message (  '<!-- module metnoCreateArr.php ('.__LINE__.'): '.$this->cacheFile.' not found in cache -->');
                return;
        }
        if (isset($_REQUEST['force']) && $_REQUEST['force'] == 'metno') {
                ws_message (  '<!-- module metnoCreateArr.php ('.__LINE__.'): '.$this->cacheFile.' not used, force was set',true);
                return;                
        }                                                     // no cached file found => goback
        $file_time      = filemtime($this->cacheFile);
        $now            = time();
        $diff           = ($now     -   $file_time);
        ws_message (  '<!-- module metnoCreateArr.php ('.__LINE__.'): '.$this->cacheFile.'
        cache time   = '.date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $this->cacheTime (seconds) -->");
	if (isset ($cron_all) ) {		// runnig a cron job
		$this->cacheTime = $this->cacheTime - 600;
		ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'): max cache lowered with 600 seconds as cron job is running -->');
	}	        	
        if ($diff <= $this->cacheTime){
                ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'): Weatherforecast is loaded from cache  -->');
                $returnArray =  unserialize(file_get_contents($this->cacheFile));
                return $returnArray;
        }
        $returnArray    = unserialize(file_get_contents($this->cacheFile));
        $updatetime     = $returnArray['dates']['nextupdateunix'];
        $updatestring   = date ('c',$updatetime);
        $now            = time();
        $nowtimestring  = date ('c',$now);
        ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'):  Weatherforecast update times
        it is now      '.$nowtimestring." ($now)
        next-update at $updatestring ($updatetime)"); 
        if ($now > $updatetime){ 
                ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'): Weatherforecast new version of data will be loaded -->');
        }  else {
                ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'): Weatherforecast used from cache as there is no new forecast available yet -->'); 
                return $returnArray;
        }
} // eof loadFromCache
#-----------------------------------------------------------------------
# write to cache
#-----------------------------------------------------------------------		
private function writeToCache($data){
        $string = serialize($data);
        if (file_put_contents($this->cacheFile, $string)){
                echo "<!-- Weatherforecast ($this->cacheFile) saved to cache  -->".PHP_EOL;
                return;
        }
        exit ("<3>Metno forecast: ERROR  Could not save data to cache ($this->cachePath). <br />
Please make sure your cache directory exists and is writable.<br />Program ends</h3>");
} // eof writeToCache
#-----------------------------------------------------------------------
# make request for forecast data
#-----------------------------------------------------------------------			
private function makeRequest(){
        global $scriptDir;
        $test= false;
        if ($test) {
                $this->rawData  = file_get_contents($scriptDir.'test.xml');
                ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'): test file test.xml loaded ',true);
                return;
        }
        ws_message ('<!-- module metnoCreateArr.php ('.__LINE__.'):  CURL will be executed for: '.$this->weatherApiUrl.' -->');
        $this->rawData  = metnoCurl ($this->weatherApiUrl);
        if (empty($this->rawData)){
                return false;
        }
        $search = array ('Service Unavailable','Error 504','Error 503');
        $error  = false;
        for ($i = 0; $i < count($search); $i++) {
                $int = strpos($this->rawData , $search[$i]);
                if ($int > 0) {$error = true; break;}
        }
        if ($error == false) {return true;} else {return false;}
} // eof makeRequest
	
}