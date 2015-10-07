<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'noaaPlainGenerateHtml.php';
$pageVersion	= '3.20 2015-07-16';  // modified cleaned - latest - fix dualimage.php
#-------------------------------------------------------------------------------
# 3.20 2015-07-16 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
$myPageNoaa1	= $pageFile;
#-------------------------------------------------------------------------------
# Display a list of forecast date from nws/noaa
#-------------------------------------------------------------------------------
# First get the data from the weather class
$weather 	= new noaaPlainWeather ();
$returnArray 	= $weather->getWeatherData($myLatitude,$myLongitude);
#-------------------------------------------------------------------------------
# now we generate the html to be used for output to the screen
#
$city = $myArea; #$returnArray['information']['location'];
$line1= 'National Weather Service '.wsnoaafcttransstr('forecast for').': <span style="color: green;">'.$city.'</span>';
$line2= wsnoaafcttransstr('Issued by').': '.'National Weather Service '.$returnArray['information']['issued'];
$line3= wsnoaafcttransstr('Updated').  ': '.$returnArray['information']['updated']; 
# These are the first three lines on the one page city forecast
$stringTop ='<div style="text-align: center;">'.$line1.'<br />'.$line2.'<br />'.$line3.'</div>';

# the icons 
if ($noaaIconsOwn) {$showPoP = false;} else  {$showPoP = true;}
$tdWidth	= floor(100 / $topCount).'%';
$daypart 	= $icon = $PoP = $desc = $temp = '';
$noaaIconsHtml	='
<table class= "genericTable" style="width: 100%;">
  <tbody>
';
$first='    <tr>
      <td style="width: '.$tdWidth.'; vertical-align: top; text-align:center;  font-size: 80%;">';
$PoPNeeded	= false;
$count		= 1;
foreach ($returnArray['forecast'] as $key => $arr) {
	if (!isset ($arr['PoP']) || !isset ($arr['iconUrl'] ) || $key == 0){continue;}   // skip all other information
	if ($count > $topCount) {break;} else {$count++;}
	$arrTxt 	= explode (' ',$arr['daypart'].' &nbsp;');
	$daypartTxt 	= str_replace($arrTxt[0],$arrTxt[0].'<br />&nbsp;',$arr['daypart']);
	$daypart	.= $first.'<span style="font-weight: bold;">'.$daypartTxt.'</span></td>';
	$iconImg	='<img src="';
	if ($noaaIconsOwn) {
		$iconImg 	.= $arr['noaaIconurl'];
	} else {
		$iconImg 	.= $arr['defaultIconurl'];
	}
	$iconImg	.= '" style="width: '.$wsIconWidth .';" title="'.$arr['weatherDescShort'].'" alt="'.$arr['weatherDescShort'].'">';
	$icon		.= $first.$iconImg.'</td>';
	if ($showPoP && $arr['PoP'] > 0) {
		$PoP	.= $first.'PoP: '.$arr['PoP'].'%</td>';
		$PoPNeeded	= true;
	} else {$PoP	.= $first.'</td>';}
	$descTxt	= str_replace('Slight Chc','Slight&nbsp;Chc',trim($arr['weatherDescShort']) );
	$desc		.= $first.$descTxt.'</td>';
	if (isset ($arr['tempMin']) ){
		$temp	.= $first.'<span style="color: blue;">Lo: </span>'.noaacommontemperature($arr['tempMin']).'</td>';
	} else {
		$temp	.= $first.'<span style="color: red;">Hi: </span>'.noaacommontemperature($arr['tempMax']).'</td>';
	}
	$first='
      <td style="width: '.$tdWidth.'; vertical-align: top; text-align:center; font-size: 80%;">';
}
$daypart.= '
    </tr>'.PHP_EOL;
$icon	.= '
    </tr>'.PHP_EOL;
if ($showPoP) {$PoP	.= '
    </tr>'.PHP_EOL;}
$desc	.= '
    </tr>'.PHP_EOL;
$temp	.= '
    </tr>'.PHP_EOL;

$noaaIconsHtml .= $daypart.$icon;
if ($PoPNeeded == true) {$noaaIconsHtml .= $PoP;}
$noaaIconsHtml .= $desc.$temp.'  <tbody>
</table>'.PHP_EOL;

# -----------   are there any warnings
$hazardsString	= '';
if ($returnArray['information']['hazards']<> 0 && isset ($returnArray['hazard'])) {
	$hazardsString = '<div style="width: 100%; background-color: #FFF0F0; font-weight: bold; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #980000;">
  <p style="width: 100%; background-color: #980000; margin: 0px; padding: 3px 0px; color: white;">&nbsp;&nbsp;&nbsp;HAZARDOUS&nbsp;&nbsp;WEATHER&nbsp;&nbsp;CONDITIONS</p>
    <p style="padding: 5px 0px 10px 15px; margin: 0px; font-size: 110%; color: #980000;">'.PHP_EOL;
	foreach ($returnArray['hazard'] as $key => $arr) {
		$hazardsString .= '    <a href="'.$arr['hazardUrl'].'" style="color: #980000; ">'.$arr['hazardType'].'</a><br />'.PHP_EOL;
	}  // eo each forecast
	$hazardsString .= '</p></div>'.PHP_EOL;	

}  // eo hazards
# -----------
$noaaPlainTextHead = '<p style="margin: 4px; color: blue; font-size: 200%;">7-DAY FORECAST</p>'.PHP_EOL;
$rowColor	= 'row-dark'; // = row-light;
$noaaPlainText= '
<table class="genericTable" style="width: 100%;  text-align:left;">
  <tbody>'.PHP_EOL;
foreach ($returnArray['forecast'] as $key => $arr) {
	if (!isset ($arr['PoP']) || !isset ($arr['iconUrl'] ) || $key == 0){continue;}   // skip all other information
	$arrTxt = explode (' ',$arr['daypart'].' &nbsp;');
	$daypartString = str_replace($arrTxt[0],$arrTxt[0].'<br />&nbsp;',$arr['daypart']);
	if ($noaaIconsOwn) {
		$iconTable	= $arr['noaaIconurl'];
	} else {
		$iconTable 	= $arr['defaultIconurl'];
	}
	$noaaPlainText .= '
    <tr class="'.$rowColor.'" >
      <td style="vertical-align: middle; text-align:right;  font-weight:bold; padding: 10px 10px 10px 10px;"><span style="">'.$daypartString.'</span></td>';
 	$noaaPlainText .= '
      <td style="vertical-align: top; text-align:right;  font-weight:bold; padding: 10px 10px 10px 10px;">
      <img src="'.$iconTable.'" style="vertical-align: bottom; width: '.$wsIconWidth .';" title="'.$arr['weatherDescShort'].'" alt="'.$arr['weatherDescShort'].'"></td>';
	
    	$noaaPlainText .= '
      <td  style="vertical-align: middle; padding: 10px 10px 10px 10px;">'.$arr['weatherDescText'].'</td>
    </tr>'.PHP_EOL;
    if ($rowColor	== 'row-dark') {$rowColor	= 'row-light';} else {$rowColor	= 'row-dark';}
}
$noaaPlainText .= '
  <tbody>
</table>
<br />'.PHP_EOL;
$city_link=str_replace(' ','%20',$city);
$creditLink = '&nbsp;Forecast from <a href="http://forecast.weather.gov/MapClick.php?CityName='.$city_link.'" target="_blank">NOAA-NWS</a> for '.$returnArray['information']['location'].'<br /><br />';
#--------------------------------------------------------------------------------------------------
# retrieve weather infor from weathersource  
# and return array with retrieved data in the desired language and units C/F
#--------------------------------------------------------------------------------------------------
class noaaPlainWeather{
	# public variables
	public $lat		= '41.3';	// 
	public $lon		= '-72.8';	// 
# private variables
	private $uomTemp	= 'F';		// <temperature type="maximum" units="Fahrenheit" 
	private $uomWindDir	= 'deg';	// <direction type="wind" units="degrees
	private $uomWindSpeed	= 'kts';	// <wind-speed type="sustained" units="knots"
	private $uomHum		= 'percent';	// <humidity type="relative" 
	private $uomBaro	= 'inHg';	// <pressure type="barometer" units="inches of mercury"
	private $uomCloud	= 'percent';	// 
	private $uomRain	= 'in';
	private $uomDistance	= 'mi';		// <visibility units="statute miles">
	private $uomPoP		= '%';		// <probability-of-precipitation  units="percent"
	
	private $enableCache	= true; 	// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google 
	private $cache		= 'cache';	// cache dir is created when not available
	private $cacheTime 	= 3600; 	// Cache expiration time Default: 3600 seconds = 1 Hour
	private $cacheFile	= 'xxx';
	private $apiUrlpart 	= array(	// http://forecast.weather.gov/MapClick.php?lat=41.3&lon=-72.78&FcstType=dwml
	 0 => 'http://forecast.weather.gov/MapClick.php?lat=',
	 1 => 'userinputLatitude',
	 2 => '&lon=',
	 3 => 'userinputLatitude',
	 4 => '&FcstType=dwml',
	);
	private $weatherApiUrl	= '';
	private $rawData		= '';
#--------------------------------------------------------------------------------------------------
# public functions	
#--------------------------------------------------------------------------------------------------
	public function getWeatherData($lat = '', $lon = '') {
		global $dateLongFormat, $timeFormat, $myCacheDir, $myPageNoaa1,
		$uomTemp, $uomWind, $uomRain, $uomSnow, $uomDistance,
		$myNoaaIconsDir, $myNoaaIconsExt, $myDefaultIconsDir, $myDefaultIconsExt ;
		#----------------------------------------------------------------------------------------------
		# clean user input
		#----------------------------------------------------------------------------------------------
		$this->apiUrlpart[1] = round(trim($lat),3);
		$this->apiUrlpart[3] = round(trim($lon),3);
		#----------------------------------------------------------------------------------------------
		# try loading data from cache
		#----------------------------------------------------------------------------------------------		
		if ( $this->enableCache && !empty($this->cache) ){
			$this->cache	= $myCacheDir;
			$uoms		= $uomTemp.'-'.$uomWind.'-'.$uomRain.'-'.$uomSnow.'-'.$uomDistance;
			$string		= $myPageNoaa1.$this->apiUrlpart[1].$this->apiUrlpart[3] .$uoms;			
			$from		= array('&deg;','∞','/',' ','.',);
			$string		= str_replace($from,'',$string);
			$this->cacheFile= $this->cache .$string.'.txt';
			$returnArray	= $this->loadFromCache();	// load from cache returns data only when its data is valid
			if (!empty($returnArray)) {
				return $returnArray;					// if data is in cache and valid return data to calling program
			}	// eo valid data, return to calling program
		}  		// eo check cache
		#----------------------------------------------------------------------------------------------
		# combine everything into required url
		#  http://forecast.weather.gov/MapClick.php?lat=41.3&lon=-72.78&FcstType=dwml
		#----------------------------------------------------------------------------------------------
		$this->weatherApiUrl = '';
		$end	= count($this->apiUrlpart);
		for ($i = 0; $i < $end; $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------		
		ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'):  loading from  '.$this->weatherApiUrl.'   -->');		
		if ($this->makeRequest()) {  	// load xml from url and process
			$this->rawData = str_replace ('-name','_name',$this->rawData);
			$xml = new SimpleXMLElement($this->rawData);
#			echo '<pre>'.PHP_EOL;  
#			print_r ($xml); 
#			echo 'test'; 
#			var_dump( $xml->data[0]->{'time-layout'}[1]->{'start-valid-time'}[1]['period_name']);
#			echo (string)  $xml->data[0]->{'time-layout'}[1]->{'start-valid-time'}[1]['period_name'];
#			exit;
			$returnArray = array();
			$utcDiff 								= date('Z');// to help to correct utc differences
			$string		= (string) $xml->head->product->{'creation-date'};
			$time		= strtotime($string);
			$returnArray['information']['filetime']		= date('c', strtotime($string ) );
			$returnArray['information']['updated']		= date($dateLongFormat, $time).' '.date($timeFormat, $time);			
			$returnArray['information']['location']		= (string) $xml->data[0]->location->description;
			if ($returnArray['information']['location'] == '' ) {
				$returnArray['information']['location']		= (string) $xml->data[0]->location->{'area-description'};			
			}
			$returnArray['information']['issued']		= (string) $xml->head->source->{'production-center'};
			
#--------------------------------------------------------------------------------------------------
#  get forecast info
#--------------------------------------------------------------------------------------------------
			$i=0;
			$returnArray['forecast'][$i]['dayPart'] 	= 'daypart';	// <start-valid-time period-name="Today
			$returnArray['forecast'][$i]['startTime'] 	= 'startTime';	// <start-valid-time period->2013-05-24T06:00:00-04:00</start-valid-time>			
			$returnArray['forecast'][$i]['endTime'] 	= 'endTime';	// <end-valid-time>2013-05-30T18:00:00-04:00</end-valid-time>			
# <temperature type="maximum" units="Fahrenheit" time-layout="k-p24h-n7-1"><value>68</value>
# <temperature type="minimum" units="Fahrenheit" time-layout="k-p24h-n6-2"><value>50</value>			
			$returnArray['forecast'][$i]['tempMaxNU'] 	= 'temp';
			$returnArray['forecast'][$i]['tempMax'] 	= 'temp';
			$returnArray['forecast'][$i]['tempMinNU'] 	= 'temp';
			$returnArray['forecast'][$i]['tempMin'] 	= 'temp';	
# <probability-of-precipitation  type="12 hour" units="percent" time-layout="k-p12h-n13-1">
			$returnArray['forecast'][$i]['PoP'] 		= 'PoP';
# <weather time-layout="k-p12h-n13-1"><name>Weather Type, Coverage, Intensity</name><weather-conditions weather-summary="Showers"/>
			$returnArray['forecast'][$i]['weatherDescShort']	= 'condition';	// Showers
			$returnArray['forecast'][$i]['weatherDescText']	= 'condition Description';	// Showers and possibly a thunderstorm.  High near 68. Southwest
			$returnArray['forecast'][$i]['icon']		= 'icon code';	// nra90	
			$returnArray['forecast'][$i]['iconUrl']		= 'icon Url';	// http://forecast.weather.gov/images/wtf/medium/ra80.png	
			$endLayouts	= count ($xml->data[0]->{'time-layout'});
			$arrTimes	= array();
			for ($nLayouts = 0; $nLayouts < $endLayouts; $nLayouts++) {
				$forecast = $xml->data[0]->{'time-layout'}[$nLayouts];
				$endTimes = count($forecast->{'start-valid-time'});
				$layoutKey = (string) $forecast->{'layout-key'};
				for ($nTimes = 0; $nTimes < $endTimes; $nTimes++) {
					$key 		= $layoutKey.'|'.$nTimes;
					$startTime	= (string) $forecast->{'start-valid-time'}[$nTimes];
					$daypart	= (string) $forecast->{'start-valid-time'}[$nTimes]['period_name'];
					$arrTimes[$key]['startTime']= $startTime;
					if ($daypart <> '') {$arrTimes[$key]['daypart'] 	= $daypart;}
					$endTime	= (string) $forecast->{'end-valid-time'}[$nTimes];
					if ($endTime <> '') {$arrTimes[$key]['endTime']= $endTime;}
				}  // eof times
			}  // eof layouts
			
			$endTemp = count ($xml->data[0]->parameters->temperature);
			for ($nTemp = 0; $nTemp < $endTemp; $nTemp++) {
				$temps 		= $xml->data[0]->parameters->temperature[$nTemp];
#				print_r($temps);
				$layoutKey	= (string) $temps['time-layout'];
				$string		= (string) $temps['type'];
				if ($string == 'maximum') {$valueKey = 'tempMax';} else {$valueKey = 'tempMin';}
				$string		= (string) $temps['units'];
				$uomTemp = substr($string,0,1);
				$endValue	= count ($temps->value);
#				echo '$endValue '.$endValue ; exit;
				for ($nValue = 0; $nValue < $endValue; $nValue++) {
					$key 		= $layoutKey.'|'.$nValue;
					$string 	= (string) $temps->value[$nValue]; 
					$value		= round( noaaconvertemp($string,$uomTemp) );
					$arrTimes[$key][$valueKey.'NU'] = $value;
					$arrTimes[$key][$valueKey] = $value.$uomTemp;
				}
			}
			$endPoP = count ($xml->data[0]->parameters->{'probability-of-precipitation'});
			for ($nPoP = 0; $nPoP < $endPoP; $nPoP++) {
				$PoPs 		= $xml->data[0]->parameters->{'probability-of-precipitation'}[$nPoP];
#				print_r($PoPs);
				$layoutKey	= (string) $PoPs['time-layout'];
				$valueKey	= 'PoP';				
				$endValue	= count ($PoPs->value);
#				echo '$endValue '.$endValue ; exit;
				for ($nValue = 0; $nValue < $endValue; $nValue++) {
					$key 		= $layoutKey.'|'.$nValue;
					$arrTimes[$key][$valueKey] = (string) $PoPs->value[$nValue];
				}
			}
			$endCondition = count ($xml->data[0]->parameters->{'weather'});
			for ($nCondition = 0; $nCondition < $endCondition; $nCondition++) {
				$Conditions 		= $xml->data[0]->parameters->{'weather'}[$nCondition];
#				print_r($Conditions);
				$layoutKey	= (string) $Conditions['time-layout'];
				$valueKey	= 'weatherDescShort';				
				$endValue	= count ($Conditions->{'weather-conditions'});
#				echo '$endValue '.$endValue ; exit;
				for ($nValue = 0; $nValue < $endValue; $nValue++) {
					$key 		= $layoutKey.'|'.$nValue;
					$value 		= (string) $Conditions->{'weather-conditions'}[$nValue]{'weather-summary'};
					$arrTimes[$key][$valueKey] = $value;	 
				}
			}
			$endIcon = count ($xml->data[0]->parameters->{'conditions-icon'});
			for ($nIcon = 0; $nIcon < $endIcon; $nIcon++) {
				$Icons 		= $xml->data[0]->parameters->{'conditions-icon'}[$nIcon];
#				print_r($Icons);
				$layoutKey	= (string) $Icons['time-layout'];
				$valueKey	= 'iconUrl';				
				$endValue	= count ($Icons->{'icon-link'});
#				echo '$endValue '.$endValue ; exit;
				for ($nValue = 0; $nValue < $endValue; $nValue++) {
					$key 		= $layoutKey.'|'.$nValue;
					$value 		= (string) $Icons->{'icon-link'}[$nValue];
					$arrTimes[$key][$valueKey] = $value;
				}
			}
			$endhazards = count ($xml->data[0]->parameters->{'hazards'});
			$returnArray['information']['hazards'] = $endhazards;
			for ($nhazards = 0; $nhazards < $endhazards; $nhazards++) {
				$hazards 		= $xml->data[0]->parameters->{'hazards'}[$nhazards];
#				print_r($hazards);
				$layoutKey	= (string) $hazards['time-layout'];				
				$endValue	= count ($hazards->{'hazard-conditions'}->hazard);
#				echo '$endValue '.$endValue ; exit;
				for ($nValue = 0; $nValue < $endValue; $nValue++) {
					$key 		= $layoutKey.'|'.$nValue;
					$string = (string) $hazards->{'hazard-conditions'}->hazard[$nValue]->hazardTextURL;
					$arrTimes[$key]['hazardUrl']= str_replace('&','&amp;',$string);
					$arrTimes[$key]['hazardType']	= (string) $hazards->{'hazard-conditions'}->hazard[$nValue]{'headline'};	 
				}
			}
			$endWord = count ($xml->data[0]->parameters->{'wordedForecast'});
			for ($nWord = 0; $nWord < $endWord; $nWord++) {
				$Words 		= $xml->data[0]->parameters->{'wordedForecast'}[$nWord];
#				print_r($Words);
				$layoutKey	= (string) $Words['time-layout'];
				$valueKey	= 'weatherDescText';				
				$endValue	= count ($Words->{'text'});
#				echo '$endValue '.$endValue ; exit;
				for ($nValue = 0; $nValue < $endValue; $nValue++) {
					$key 		= $layoutKey.'|'.$nValue;
					$value 		= (string) $Words->{'text'}[$nValue];
					$arrTimes[$key][$valueKey] = $value;	 
				}
			}
#			echo '<pre>'.PHP_EOL; print_r ($arrTimes); exit;
			foreach ($arrTimes as $key => $arr) {
				if (!isset ($arr['hazardType']) ) {
					$type	= 'forecast';
					$start	= $arr['startTime']; 
				} else {
					$type	= 'hazard';
					$start	= $arr['hazardType']; 
					if ($start == '') {continue;}
				}	
#				$returnArray[$type][$start]['startTime']=$start;
/*           
<hazardTextURL>http://forecast.weather.gov/showsigwx.php?warnzone=NVZ037&amp;warncounty=NVC011&amp;firewxzone=NVZ454&amp;local_place1=6+Miles+NNW+Eureka+NV&amp;product1=Red+Flag+Warning</hazardTextURL>

*/
				foreach ($arr as $keyValue => $value) {
#					echo $keyValue.' - '.$value.PHP_EOL;
#					if ($keyValue == 'startTime') {continue;}
					$returnArray[$type][$start][$keyValue]=$value;
				}
			}
			foreach ($returnArray['forecast'] as $key => $arr) {
				if (!isset ($arr['PoP']) || !isset ($arr['iconUrl'] ) || $key == 0){continue;}
				$remove		= $arr['PoP'];
				$arrparts	= explode('/',$arr['iconUrl']);  	// http://forecast.weather.gov/DualImage.php?i=shra&j=tsra&ip=30&jp=60
				$location	= count($arrparts)-1;			// http://forecast.weather.gov/newimages/medium/shra30.png
				$value		= $arrparts[$location];
				if (substr ($value,0,13) == 'DualImage.php') {
					$rest 		= substr($value,16);
					$arr_icon 	= explode ('&',$rest);
					$icon		= $arr_icon[0];
				} 
				else {
					$iconPoP	= $none = '';
					list($iconPoP,$none) = explode('.',$value);
					$icon		= str_replace ($remove,'',$iconPoP);	
				}		
				$defaulticon 	= wsconvertnoaaicon ($icon);
				$returnArray['forecast'][$key]['noaaIcon'] 	= $icon;								
				$returnArray['forecast'][$key]['defaultIcon'] 	= $defaulticon;
#				$returnArray['forecast'][$key]['noaaIconurl'] 	= $myNoaaIconsDir.   $iconPoP.'.'.    $myNoaaIconsExt;
				$returnArray['forecast'][$key]['noaaIconurl'] 	= htmlspecialchars($arr['iconUrl']);
				$returnArray['forecast'][$key]['defaultIconurl']= $myDefaultIconsDir.$defaulticon.'.'.$myDefaultIconsExt;
				
			}
#		echo '<pre>'.PHP_EOL; print_r ($returnArray); exit;
		$ret = $this->writeToCache($returnArray);
		return $returnArray;
		}  // eo makeRequest processing

	} // eof getWeatherData
	
	private function loadFromCache(){
	        global $wsDebug, $cron_all;
	        if (isset ($_REQUEST['force']) && $_REQUEST['force'] == 'noaafct') {
	                ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'):  no cache checked as force=noaafct is used  -->',true);
	                return;
	        }
		if (file_exists($this->cacheFile)){	
			$file_time	= filemtime($this->cacheFile);
			$now 		= time();
			$diff		= ($now - $file_time);		
			ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'): '."($this->cacheFile) 
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff (seconds)
	diff allowed = $this->cacheTime (seconds) -->");	
			if (isset ($cron_all) ) {		// runnig a cron job
				$this->cacheTime = $this->cacheTime - 360;
				ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'): max cache lowered with 360 seconds as cron job is running -->');
			}
			if ($diff <= $this->cacheTime){
				ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'): '."($this->cacheFile) loaded from cache  -->");
				$returnArray =  unserialize(file_get_contents($this->cacheFile));
				return $returnArray;
			}
		}
		else {	ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'): '."($this->cacheFile) does not exist yet  -->");
		}		
	} // eof loadFromCache
	
	private function writeToCache($data){
		if (!file_exists($this->cache)){
			mkdir($this->cache, 0777);   // attempt to make the cache dir
		}
	//			print_r ($data); return;
		if (!file_put_contents($this->cacheFile, serialize($data))){   
			exit ("<h3>Could not save data to cache ($this->cacheFile).<br />Please make sure your cache directory exists and is writable.<br />Script ended</h3>");
		}
		else {	ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'): '."($this->cacheFile) saved to cache  -->");
		}
	} // eof writeToCache
	
	private function makeRequest(){
		$test = false; $testfile	= './usa/noaafct/plain.xml';
		if ($test == true) {
			$this->rawData  = file_get_contents($testfile);
			ws_message (  '<!-- module noaaPlainGenerateHtml.php ('.__LINE__.'): test file '.$testfile.' loading -->');
#			$this->rawData  = file_get_contents('./noaa/digitalDWML.xml');
#			print_r ($this->rawData); exit;
		} 
		else {	$ch = curl_init();
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
			$this->rawData = curl_exec ($ch);
			curl_close ($ch);
		}
/*		if (file_put_contents( $testfile, $this->rawData) ) {
			echo '<!-- test file '.$testfile.' saved -->'.PHP_EOL;
		} */
#		echo $this->weatherApiUrl;
		if (empty($this->rawData)){
			return false;
		}
		$search = array ('Service Unavailable','Error 504','Error 503');
		$error = false;
		for ($i = 0; $i < count($search); $i++) {
			$int = strpos($this->rawData , $search[$i]);
			if ($int > 0) {$error = true; break;}
		}
		if ($error == false) {return true;	} else {return false;}
	} // eof makeRequest
}
# ----------------------  version history
# 3.20 2015-07-16 release 2.8 version 
