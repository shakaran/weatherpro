<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsRegionalMap.php';
$pageVersion	= '3.20 2015-09-28';
#-------------------------------------------------------------------------------
# 3.20 2015-09-28 release 2.8 version  plus validity check latitude longitude.
#-------------------------------------------------------------------------------
# for use with pre 2.8 release
if (!function_exists ('ws_message') ) {
    function ws_message ($message,$always=false) {
	global $wsDebug, $SITE;
	$echo	= $always;
	if ( $echo == false && isset ($wsDebug) && $wsDebug == true ) 			{$echo = true;}
	if ( $echo == false && isset ($SIE['wsDebug']) && $SIE['wsDebug'] == true ) 	{$echo = true;}
	if ( $echo == true ) {echo $message.PHP_EOL;}
    }
}
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
#--------------------------------- Settings ------------------------------------
#$MESO			= 'BNLWN'; #'BNLWN';
$ThisStation 		= 'This station';
$default_lang		= 'en';
#
$folder			='glo/';
$condIconsDir 		= $folder."MESO-images/";
$doLinkTarget 		= true; 			// =true to add target="_blank" to links in popups
$netLinksPath 		= $SITE['cacheDir'];  		// relative path for including the network links files (cached)
#
if ($SITE['uomTemp'] == '&deg;C') {$gmTempUOM = 'C';} else {$gmTempUOM = 'F';}
$gmWindUOM 		= trim($SITE['uomWind']) ; 
$gmBaroUOM 		= trim($SITE['uomBaro']);
$gmRainUOM 		= trim($SITE['uomRain']); 
#  Google map settings
$gmMapCenter 	        = '51.28,4.5';
$gmMapZoom 		= 7; 			// initial map zoom level 1=world, 10=city;
$gmMapType 		= 'TERRAIN'; 		// ='ROADMAP', ='TERRAIN', ='SATELLITE', ='HYBRID' for Google Map Type (ALL CAPS)
$show_offline		= true;			// show table rows also when conditions data available
$printList 		= true;		// print the list of stations, =false; to omit the list
$doRotatingLegends      = true;			// display all weathervalues continuasly
$do_tabs 		= true;			// divide the output in tabs for map - conditions - members 
$do_tabs_members 	= true;			// divide the output in tabs for map - conditions - members 
#-------------------------------------------end settings------------------------
#                          DO NOT CHANGE ANYTHING BELOW THIS LINE
$masterHost 		= 'http://www.northamericanweather.net/';
$masterFile		= 'global-conditions.json';
$masterCharset		= "WINDOWS-1252";
# Here starts the first part
#
$cacheAllowed 		= 6000;  		// 10 minute cache time
$cacheDir 		= $SITE['cacheDir']; 	// target directory for cache files 
#------------------------------ end settings      ------------------------------
#
echo '<!-- start regional map -->'.PHP_EOL;
#
#  check language used
#
$GoogleLang = array ( // ISO 639-1 2-character language abbreviations from country domain to Google usage
  'af' => 'af', 'bg' => 'bg', 'ct' => 'ca', 'dk' => 'da', 'nl' => 'nl', 'en' => 'en',
  'fi' => 'fi', 'fr' => 'fr', 'de' => 'de', 'el' => 'el', 'ga' => 'ga', 'it' => 'it',
  'he' => 'iw', 'hu' => 'hu', 'no' => 'no', 'pl' => 'pl', 'pt' => 'pt', 'ro' => 'ro',
  'es' => 'es', 'se' => 'sv', 'si' => 'sl', );
#
if (!isset ($lang) ) {$lang = $default_lang;}
#
if (isset($_REQUEST['lang'])) {$lang = substr(strtolower($_REQUEST['lang']).'xx',0,2); }
#
if(isset($GoogleLang[$lang])) {
	$Lang = $GoogleLang[$lang];
	ws_message ("<!-- module $pageFile (".__LINE__."): lang=".$lang." used - Google Lang=$Lang -->");
}
# -------------------------- what MESOnet do we process---------------------------------
#
$load_regional	= true;			// default we try to load the regional data
$start_file	= $folder.'start.txt';	// supplied by a mesonet with their values
if ($SITE["mesonetMember"] && $load_regional) {
        $start_file	= $folder.$SITE["mesoID"].'-start.txt';
        ws_message ("<!-- module $pageFile (".__LINE__."): ".$start_file." = $start_file -->");
}
#
if (isset ($_REQUEST['meso']) ) {	// do we try to load another meso net
	$MESO = trim(strtoupper(substr($_REQUEST['meso'].'      ',0,6 ) ));
	$start_extra_file	= $folder.$MESO.'-start.txt';
	if (file_exists($start_extra_file) ){
		$start_file	= $start_extra_file;
		ws_message ("<!-- module $pageFile (".__LINE__."): ".$start_file." = $start_file -->");
	}
	else {	$start_file	= false;
		$load_regional	= false;
		ws_message ("<!-- module $pageFile (".__LINE__."): switched regional plus start_file off -->");
	}
}
#
if (isset ($start_file) && file_exists($start_file) ){		// load startfile and define constants
	$arr	= file($start_file);
	ws_message ( "<!-- module $pageFile (".__LINE__."): Startfile $start_file loaded. -->",true);  
	foreach ($arr as $key => $value) {
		if (substr ($value.'    ',0,4) <> 'MESO') {continue;}
		list ($constant,$value) = explode ('|',$value.'||');
		$constant	= trim($constant);
		$value		= trim($value);
		if ($value == '') {continue;}
		if ($constant == 'MESO') {
			$MESO = $value;
			ws_message ( "<!-- module $pageFile (".__LINE__."): MESO set to $value. -->",true);  
		}
		define ($constant,$value);
	}
}
elseif ($SITE["mesonetMember"] && $load_regional) {
        $MESO = trim(strtoupper(substr($SITE["mesoID"].'      ',0,6 ) ));
        ws_message ( "<!-- module $pageFile (".__LINE__."): For $MESO script defaults will be used for start values. -->");
	regionBoilerplate ();
	$load_regional	= false;   
}
else {	ws_message ( "<!-- module $pageFile (".__LINE__."): Script defaults will be used for start values. -->");
	regionBoilerplate ();
	$load_regional	= false;
}
# ------------------------ what language do we display the information- ----------------
#
$mesoLangFile	= $folder.$MESO. '-language-meso-'.$lang.'.txt';	// the selected language file  for this meso
$defaultLangFile= $folder.'DEFAULT-language-meso-'.$lang.'.txt';	// the selected language DEFAULT file  
$lang_loaded	= false;
if (file_exists($mesoLangFile) ) {
	ws_message ( "<!-- module $pageFile (".__LINE__."): $mesoLangFile loaded. -->");
 	include_once ($mesoLangFile);
 	$lang_loaded	= true;
} 
elseif (file_exists($defaultLangFile) ) {
	ws_message ( "<!-- module $pageFile (".__LINE__."): $defaultLangFile loaded. -->");
 	include_once ($defaultLangFile);
 	$lang_loaded	= true;
}
if ($lang_loaded == false && $lang <> $default_lang) {
	$mesoLangFile	= $folder.$MESO. '-language-meso-'.$default_lang.'.txt';	//  the default language file  for this meso
	$defaultLangFile= $folder.'DEFAULT-language-meso-'.$default_lang.'.txt';	// the default language DEFAULT file 
	if (file_exists($mesoLangFile) ) {
		ws_message ( "<!-- module $pageFile (".__LINE__."): $mesoLangFile loaded for default language as no $lang version exist. -->");
		include_once ($mesoLangFile);
		$lang_loaded	= true;
	}
	elseif (file_exists($defaultLangFile) ) {
		ws_message ( "<!-- module $pageFile (".__LINE__."): $defaultLangFile loaded for default language as no $lang version exist. -->");
		include_once ($defaultLangFile);
		$lang_loaded	= true;
	}
}
if ($lang_loaded == false) {
	ws_message ( "<!-- module $pageFile (".__LINE__."): Script defaults for language sentences will be used, no correct lang files found. -->"); 
	langBoilerplate ();		// load default texts
}
# ------------------------ check unusable entries and adjust- --------------------------
#
if (defined('MESO_MAPZOOM') && MESO_MAPZOOM < 10 && MESO_MAPZOOM > 1) {$gmMapZoom  = round(MESO_MAPZOOM);}
#
if (!defined ('MESO_MAPHEIGHT') ){define ('MESO_MAPHEIGHT', '680'); }
#
if (!defined('MESO_FILES_LOCATION') && !defined('MESO_URL') ) {
	$load_regional	= false;
}
if ($load_regional == true) {		// first check file names and locations
	if (!defined('MESO_FILES_LOCATION') ) 		{define ('MESO_FILES_LOCATION' , trim(MESO_URL) );}
	if (!defined('MESO_CONTROL_FILE') ) 		{define ('MESO_CONTROL_FILE' , $MESO.'-stations-cc.txt');
		if (!defined('MESO_CONTROL_TYPE') ) 	{define ('MESO_CONTROL_TYPE' , 'txt');}
	}
	if (!defined('MESO_CONDITIONS') ) 		{define ('MESO_CONDITIONS' , $MESO.'-conditions.txt');
		if (!defined('MESO_CONDITIONS_TYPE') ) 	{define ('MESO_CONDITIONS_TYPE' , 'txt');}
	}
	if (!defined('MESO_UNITS') ) 			{define ('MESO_UNITS' , 'C,kmh,hPa,mm,m');}
}
# --------------------------------------------------------------------------------------
$fileOK		= false;
$data_global	= false;
# --------------------------start of loading regional data------------------------------
if ($load_regional == true) {		// try to load regional files,
	$controlOK	= false;
	$cacheName	= $cacheDir.MESO_CONTROL_FILE;	
	$cacheAllowed 	= 24*60*60;
	if (isset ($_REQUEST['force']) && strtolower ($_REQUEST['force']) == 'meso') {$cacheAllowed = -1;}
	$cacheOK = meso_check_cache ($cacheName, $cacheAllowed);
	if ($cacheOK == true) {
		$controlOK	= true;
	}  // file is valid 
	else {	$URL		= MESO_FILES_LOCATION.MESO_CONTROL_FILE;
		$rawcontrol	= '';
		$controlOK 	= meso_load_file ($URL, $cacheName, $rawcontrol);
	}
	if ($controlOK == true) {
		$rawcontrol 	= file_get_contents ($cacheName);
	}
	else {	$load_regional	= false;
	}
}
if ($load_regional == true  && MESO_CONDITIONS_TYPE == 'txt') {		// try to load regional files, now the conditions file
	$conditionsOK	= false;
	$cacheName	= $cacheDir.MESO_CONDITIONS;	
	$cacheAllowed 	= 5*60;				####################  test, reset to 5*60
	if (isset ($_REQUEST['force']) && strtolower ($_REQUEST['force']) == 'meso') {$cacheAllowed = -1;}
	$cacheOK = meso_check_cache ($cacheName, $cacheAllowed);
	if ($cacheOK == true) {
		$conditionsOK	= true;
	}  // file is valid 
	else {	$URL		= MESO_FILES_LOCATION.MESO_CONDITIONS;
		$rawconditions	= '';
		$conditionsOK 	= meso_load_file ($URL, $cacheName, $rawconditions);
		if (isset ($remote_time) && $remote_time <> -1) {	//  if the regional hub data was 'stale' (i.e. more than 1 hour old),
			$string = "<!-- module $pageFile (".__LINE__."): remote_time  = ".date(' r',$remote_time);
			$now	= time();
			$diff	= $now - $remote_time;
			if ($diff > 60*60) {
				$conditionsOK = false;
				ws_message ($string. ' which is to old as it is now '.$diff.' seconds later -->');
			}
			ws_message ($string .' wich is less then 1 hour old -->');
		}
	}
	if ($conditionsOK == true) {
		$rawconditions 	= file_get_contents ($cacheName);
	}
	else {	$load_regional	= false;
	}
}
#echo $rawconditions; exit;
# {"town":"Antwerpen-Ekeren, Belgium, Europe","lat":"51.2809101","long":"4.4202713","surl":"www.akker.be/","fcode":"cam","nets":"BNLWN,ZEUR",
#		"conds":"day_cloudy.gif,Overcast,19 C,95%,18 C,WNW,3 kmh,4,0.4 mm,1007.7 hPa,Steady"},
# stations:  Belgium|http://www.akker.be/|Antwerpen-Ekeren|244,486,252,494|Weather,WebCam|none|CR|http://www.akker.be/clientraw.txt|2,-9|EBAW|51.2809101,4.4202713|
#
if ($load_regional == true  && MESO_CONTROL_TYPE == 'txt') {		// only text files supported yet
	$arr_regional_cnds	= array();
	$arr	= explode ("\n",$rawcontrol);
	$count	= count ($arr);
	$valid	= 0;
	$largest_lat	= 0;
	$largest_long	= 0;
	$smallest_lat	= 0;
	$smallest_long	= 0;
	for ($seq=0;  $seq < $count; $seq++) {
 		$line	= $arr	[$seq];	
 		if (substr($line,0,1) == '#') {continue;} 
 		if (trim($line) == '') {continue;}
 		$valid++;
 		list($country,$surl,$name,$coords,$features,$data_page,$data_type,$durl,$offsets,$METAR,$lat_long) = explode('|',trim($line). '||||||||||||');
 		$key	= $seq + 1;
 		$arr_regional_cnds[$key]['town']	= trim($name).', '.trim($country).', '.MESO_REGION;
 		list ($lat, $long)		= explode (',',trim($lat_long).',');
 		$arr_regional_cnds[$key]['lat']	= $lat;
 		$arr_regional_cnds[$key]['long']= $long;
 		$arr_regional_cnds[$key]['surl']= trim(str_replace('http://','',$surl));
 		
 		if ($largest_lat == 0   || $largest_lat   < $lat)  {$largest_lat   = $lat;}
		if ($smallest_lat == 0  || $smallest_lat  > $lat)  {$smallest_lat  = $lat;}
		if ($largest_long == 0  || $largest_long  < $long) {$largest_long  = $long;}
		if ($smallest_long == 0 || $smallest_long > $long) {$smallest_long = $long;}
		
 		$weather = $webcam = $lightning = false;
		$weather	= preg_match('|weather|i',   $features);
		$weather	= true;						################  check
		$webcam 	= preg_match('|webcam|i',    $features);
		$lightning 	= preg_match('|lightning|i', $features);
		if 	($weather && $webcam  && $lightning) 	{$fcode	= 'all';}
		elseif  ($weather             && $lightning) 	{$fcode	= 'lgt';}
		elseif  ($weather             && $webcam)    	{$fcode	= 'cam';}
		elseif  ($weather )    				{$fcode	= 'wx';}
		else 						{$fcode	= 'wx';}	
		$arr_regional_cnds[$key]['fcode']	= $fcode;
		$arr_regional_cnds[$key]['nets']	= $MESO;
		$arr_regional_cnds[$key]['conds']	= 'Offline';
	} // eo loop every station
}
#echo '<pre>'; print_r($arr_regional_cnds); exit;
if ($load_regional == true  && MESO_CONDITIONS_TYPE == 'txt') {		// only text files supported yet
	$oldestData 	= 9999999999999999;
	$newestData 	= 0;
	$now		= time();
	$arr_countries	= array();
	$arr	= explode ("\n",$rawconditions);
	list ($u_temp,$u_wind,$u_baro,$u_rain,$u_height)	= explode (',',MESO_UNITS.',,,,,'); 
	foreach ($arr as $key => $value) {	# Belgium Antwerpen-Ekeren 29|19.3,91,E,1,0.4,1008.7,Steady,day_cloudy.gif,Overcast,17.8,2,1439629191,0.064
		if (trim($value) == '') {continue;}
		list ($station,$condition) 	= explode ('|',$value.'|');
		list ($country,$city,$seq)	= explode ("\t",$station."\t\t");
		$country			= trim ($country);
		$arr_countries[$country]	= '1';
		$seq				= trim($seq);
		list ($temp,$hum,$wdir,$wspd,$rain,$baro,$trend,$icon,$text,$dew,$gust,$updated,$fetch) = explode (',',$condition.',,,,,,,,,,,,,,,,,,,,,,,');
				      	# [conds] => 19.3,91,   E,    1,    0.4, 1008.7,Steady, day_cloudy.gif,Overcast,17.8,  2,  1439629191,0.06
					#          $TEMP,$HUMID,$WDIR,$WSPD,$RAIN,$BARO,$BTRND, $COND,        $CTXT,   $DEWPT,$GUST,$UDATE,   $FTIME
					#"conds":  "day_cloudy.gif,Overcast,19 C,95%,18 C,WNW,3 kmh,4,0.4 mm,1007.7 hPa,Steady"
		$string = $icon.','.$text.','.$temp.' '.$u_temp.','.$hum.'%,'.$dew.' '.$u_temp.','.$wdir.','.$wspd.' '.$u_wind.','.$gust.','.$rain.' '.$u_rain.','.$baro.' '.$u_baro.','.$trend.','.date('G:i:s',$updated);
		$arr_regional_cnds[$seq]['conds']	= $string;
		if($updated > 1000) {
    			$oldestData = min($updated,$oldestData);
    			if ($now > $updated) {
    				$newestData = max($updated,$newestData);
    			}
  		}
	} // eo foreach condition
	$fileOK 	= true;
	$skip_txt 	= 'var data = ';
	$arr_raw['nets']=array();
}
if ($fileOK == true) {		// save json data for this region
	$skip_txt 	= 'var data = ';
	$arr_raw['nets'][MESO]['name'] 	= MESO_LONG;
	$arr_raw['nets'][MESO]['url'] 	= MESO_URL;
	$arr_raw['nets'][MESO]['short'] = MESO_SHORT;
	$arr_raw['nets'][MESO]['region']= MESO_REGION;
	$arr_raw['nets'][MESO]['units'] = MESO_UNITS;
	$arr_sort = array();
	foreach ($arr_regional_cnds as $key => $arr) {
		list ($city,$country,$none) = explode (', ',$arr['town']);
		$sort_key	= $country.$city.$key;
		$arr_sort [$sort_key]	= $arr;
		ksort($arr_sort);
	}
	unset ($arr_regional_cnds);
	$arr_conditions = array();
	foreach ($arr_sort as $key => $value) {
		$arr_conditions[]	= $value;
	}
	unset ($arr_sort);
}
#echo '<pre>'; print_r($arr_regional_cnds); exit;
#echo '<pre>'; print_r($arr_conditions); exit;
# --------------------------end of loading regional data-----------------------------------

# --------------------------start of loading world data---------------------------------
if ($fileOK== false) {
# file locationsd world
	$characterset		= $masterCharset;
	$cacheName		= $cacheDir.$masterFile;
	if (file_exists($cacheName) ){ 
		$file_time      = filemtime($cacheName);
		$now            = time();
		$diff           = ($now     -   $file_time);
		ws_message (  "<!-- module $pageFile (".__LINE__."): $cacheName times:
		cache time   = ".date('c',$file_time)." from unix time $file_time
		current time = ".date('c',$now)." from unix time $now 
		difference   = $diff seconds
		diff allowed = $cacheAllowed seconds -->");	
		if ($diff <= $cacheAllowed){
			ws_message (  "<!-- module $pageFile (".__LINE__."): File $cacheName current in cache -->");
			$rawdata = file_get_contents ($cacheName);
			$fileOK = true;
		}  // file is valid 
	} // eo if file exist and valid
	if ($fileOK == false) {
		ws_message (  "<!-- module $pageFile (".__LINE__."): For file $cacheName we need to load a fresh copy -->");
		$URL	= $masterHost.$masterFile;
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
		$rawdata	= curl_exec ($ch);
		curl_close ($ch);
		if ($characterset <> "UTF-8") {
			$rawdata = iconv ("WINDOWS-1252", "UTF-8//IGNORE",$rawdata);
		}
		if (file_put_contents($cacheName, $rawdata)) {   
		       ws_message (  "<!-- module $pageFile (".__LINE__."): File $cacheName saved to cache  -->");
		} else {
			ws_message (  "<h3>module $pageFile (".__LINE__."): ERROR  File $cacheName could not be saved to cache. Program stops.</h3>");
			return;
		}
	}  // eo file needs to be read using curl
	$skip_txt = 'var data = ';
	$skip = strlen('var data = ');
	if (substr ($rawdata,0,$skip) <> $skip_txt) {$skip = 0; $skip_txt = '';}
	$arr_raw 	= json_decode(substr($rawdata,$skip),true);
	if (!isset ($arr_raw['nets'][$MESO]) ) { $MESO = MESO; }
	$MESO_facts 	= $arr_raw['nets'][$MESO];
	$units		= $MESO_facts['units'];
	list ($gmTempUOM, $gmWindUOM, $gmBaroUOM, $gmRainUOM , $none) = explode (',',$units.',,,,,'); 
	$count		= count($arr_raw['markers']);
	$n_json		= -1;
	$arr_conditions = array();
	$arr_countries	= array();
	$largest_lat	= 0;
	$largest_long	= 0;
	$smallest_lat	= 0;
	$smallest_long	= 0;
	ws_message (  "<!-- module $pageFile (".__LINE__."): Searching for MESO = $MESO  -->");
	for ($n1 = 0; $n1 < $count; $n1++) {
		$arr	= $arr_raw['markers'][$n1];
		$nets	= explode(',' , $arr['nets'].',');
		$found	= false;
		foreach ($nets as $key => $value) {
			if ($value == $MESO) {
				$found	= true;
#				ws_message (  "<!-- module $pageFile (".__LINE__."): $MESO found -->");
				break;
			}
		}
		if ($found == true) {
			$n_json++;
			$arr['lat']	=  $arr['lat'] * 1.0;
			$arr['long']	= 1.0 * $arr['long'];
			$arr_conditions[$n_json]['town']	= $arr['town'];
			$arr_conditions[$n_json]['lat']		= $arr['lat'];
			$arr_conditions[$n_json]['long']	= $arr['long'];
			$arr_conditions[$n_json]['surl']	= $arr['surl'];
			$arr_conditions[$n_json]['fcode']	= $arr['fcode'];
			$arr_conditions[$n_json]['nets']	= $arr['nets'];
			$arr_conditions[$n_json]['conds']	= $arr['conds'];
			list ($city,$country,$region) 		= explode (',',$arr['town']);
			$arr_countries[$country]	= '1';
			if ($largest_lat == 0 ) {$largest_lat   = $arr['lat'];}
			if ($smallest_lat == 0) {$smallest_lat  = $arr['lat'];}
			$difference = abs( (180 + $largest_lat) - (180 + $arr['lat'])  );
			if ($difference < 180) {
			        if ($largest_lat   < $arr['lat'])  { $largest_lat   = $arr['lat'];}
	                        if ($smallest_lat  > $arr['lat'])  { $smallest_lat  = $arr['lat'];}
	                }
	                if ($largest_long  == 0){$largest_long  = $arr['long'];} 
	                if ($smallest_long == 0){$smallest_long = $arr['long'];}
	                $difference = abs( (180 + $largest_long) - (180 + $arr['long'])  );
			if ($difference < 180) {	                
			        if ($largest_long  < $arr['long']) {$largest_long  = $arr['long'];}
			        if ($smallest_long > $arr['long']) {$smallest_long = $arr['long'];}
			}
		}
	}
	
#print_r ($arr_conditions[0]);
$data_global	= true;
} // eo $fileOK== false
# --------------------------end of loading world data-----------------------------------
if (!defined ('MESO_MAPCENTER')  || !defined ('MESO_MAPZOOM') ) {
	$diff_lat	= abs($largest_lat - $smallest_lat);
	ws_message (  "<!-- module $pageFile (".__LINE__."): ".'$largest_lat = '. $largest_lat .' - $smallest_lat = '. $smallest_lat .' difference = '.$diff_lat. ' -->');
	ws_message (  "<!-- module $pageFile (".__LINE__."): ".'$largest_long = '. $largest_long .' - $smallest_long = '. $smallest_long .' -->');
}
if (!defined ('MESO_MAPCENTER') ) {
	$lat	= round( ($largest_lat	+ $smallest_lat )/2 , 2);
	$long	= round( ($largest_long + $smallest_long)/2 , 2);
	define 	('MESO_MAPCENTER', $lat.','.$long);
	ws_message (  "<!-- module $pageFile (".__LINE__."): Map center set to ".MESO_MAPCENTER."  -->");	
}
if (!defined ('MESO_MAPZOOM') ) {
	$correct = 0;
	if 	($diff_lat > 20 && $gmMapZoom > 4) { $correct = 3;}
	elseif 	($diff_lat > 14 && $gmMapZoom > 4) { $correct = 2;}
	elseif 	($diff_lat >  7 && $gmMapZoom > 4) { $correct = 1;}
	if ($correct <> 0) {
		ws_message (  "<!-- module $pageFile (".__LINE__."): MapZoom set to ".$gmMapZoom." - $correct  -->");
		$gmMapZoom = $gmMapZoom - $correct; 
	}
	define 	('MESO_MAPZOOM', $gmMapZoom);
}
$gmMapCenter	= MESO_MAPCENTER;
$gmMapZoom	= MESO_MAPZOOM;
ws_message (  "<!-- module $pageFile (".__LINE__."): MapZoom = $gmMapZoom -  MapCenter = $gmMapCenter -->");
#echo '<pre>'; print_r($arr_conditions); exit;
# --------------------------------------------------------------------------------------
# print the json of the stations for the map
# --------------------------------------------------------------------------------------
$json_out  	= '';
$json_out      .= '<script  type="text/javascript">'.PHP_EOL;
$json_out      .= $skip_txt.'{"markers": ';
$json_out      .= json_encode ($arr_conditions,JSON_UNESCAPED_SLASHES);
$json_out      .= ','.PHP_EOL.'"nets":'.PHP_EOL;
$json_out      .= json_encode ($arr_raw['nets'],JSON_UNESCAPED_SLASHES);

echo $json_out.'}
</script>'.PHP_EOL; 

# --------------------------------------------------------------------------------------
# print the references for scripts and the css for the map part.
# --------------------------------------------------------------------------------------
echo '<script src="http://maps.google.com/maps/api/js?sensor=false&amp;language='.$Lang.'" type="text/javascript"></script>'.PHP_EOL;
#echo '<script src="'.$folder.'global-map.js" type="text/javascript"></script>'.PHP_EOL;
echo '<script type="text/javascript" src="./javaScripts/sorttable.js"></script>'.PHP_EOL;
echo '<div class="blockDiv">
<style scoped>
@charset "UTF-8";
/* CSS for styling Global Google Map and controls */
/* Version 2.00 - 27-Nov-2013 */
  #map-container {
	padding: 0px;
	border-width: 0px;
	border-style: solid;
	border-color: #ccc #ccc #999 #ccc;
	-webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
	-moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
	box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
	width: 100%;
  }
  #map {width: 100%;height: '.MESO_MAPHEIGHT.'px;}

  #legend {
	color: blue;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10pt;
  }
  .GMcontent0 { display: inline;}
  .GMcontent1 { display: none;}
  .GMcontent2 { display: none;}
  .GMcontent3 { display: none;}
  .GMcontent4 { display: none;}
  .GMcontent5 { display: none;}
  .GMcontent6 { display: none;}
  .GMcontent7 { display: none;}
  .GMcontent8 { display: none;}
  #GMcontrols {
	  font-family: Verdana, Arial, Helvetica, sans-serif;
	  font-size: 8pt;
	  font-weight: normal;
	  position: relative;
	  display: inline;
	  padding: 0 0;
	  margin: 0 0;
	  border: none;
  }
  #GMcontrolsUOM select {
	  font-family: Arial, Helvetica, sans-serif;
	  font-size: 8pt;
	  font-weight: normal;
	  padding: 0 0;
	  margin: 0 0;
  }
</style>'.PHP_EOL;
# --------------------------------------------------------------------------------------
# start building the page
# --------------------------------------------------------------------------------------
echo '<h3 class="blockHead" style="margin: 0;">'.MESO_PAGEHEAD.' '.MESO_LONG.'</h3>'.PHP_EOL;
if (!isset ($do_tabs) ) {$do_tabs = false;}	// maybe some removed the line instead of setting to false.
#
if ($do_tabs == true) {				// print the enclosing divs for the tabs
	echo '<br />
<div class="tabber" style="width: 100%; margin: 0 auto;">'.PHP_EOL; 
	}
# --------------------------------------------------------------------------------------
# map part
# --------------------------------------------------------------------------------------
if(!file_exists ($folder.'global-map-inc.php')) { 
	echo '<p>Module  '.$pageFile.' ('.__LINE__.') The Regional map is currently not available.</p>';  
	echo '</div>
<!-- end regional map -->'.PHP_EOL;
	
} 
else {	if ($do_tabs == true) { 
	echo '<div class="tabbertab" style="width: 100%; padding: 0;">
<h3>'.langtransstr('Map').'</h3>'.PHP_EOL;
	} 
	include_once    $folder.'global-map-inc.php'; 
	if ($load_regional == true) {
	echo '<p style="text-align: center; margin-top: 20px; font-size: x-small;">'.MESO_CONDSFROM.date (' r',$oldestData).' '.MESO_CONDSTO.' '.date ('r',$newestData).'</p>'.PHP_EOL;
	}   
	if ($do_tabs == true) { 
		echo '</div>'.PHP_EOL;
	}
}
# --------------------------------------------------------------------------------------
#display conditions of stations.
# --------------------------------------------------------------------------------------
if ($do_tabs == true) { 
	echo '<div class="tabbertab" style="width: 100%; padding: 0;">
<h3>'.MESO_CONDHEAD.'</h3>'.PHP_EOL; 
} 
else {	echo '<h3 style="margin: 20px; text-align: center;">'.MESO_CONDHEAD.'</h3>'.PHP_EOL; 
}
# generate headers
echo '
<p style="margin: 20px; text-align: center;"><small>'. MESO_COLSORT.'</small></p>
<table style="border: 1px; width: 100%; border-collapse: collapse; border-spacing: 2px; text-align: center; font-size: x-small;" class="sortable MESOtable">
 <thead>
 <tr style="border-bottom: 1px solid black; border-top: 1px solid black;">
  <th style="text-align: left;	 cursor: n-resize; padding-left: 5px;">'.			MESO_STATE.	'</th>
  <th style="text-align: left;	 cursor: n-resize;">'.						MESO_STATION.	'</th>
  <th style="text-align: center;" '.			'class="sorttable_nosort">&nbsp;</th>
  <th style="text-align: center; cursor: n-resize;">'.						MESO_CONDL.	'</th>
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_TEMP.	'</th>
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_DEWPT.	'</th>
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_HUM.	'</th>
  <th colspan="2" style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_AVGWIND.	'</th>
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_GUSTWIND.	'</th>
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_PRECIPS.	'</th>
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_BAROB.	'</th>
  <th style="text-align: center; cursor: n-resize;">'.						MESO_BAROT.	'</th>';
if  ($data_global <> true) {
	echo '
  <th style="text-align: center; cursor: n-resize;" '.	'class="sorttable_numeric">'.		MESO_DATAUPDT.	'</th>';
}
echo '
</tr>
</thead>
<tbody>'.PHP_EOL;
if ($doLinkTarget == true) {$target = ' target="_blank" ';} else {$target = '';}
foreach ($arr_conditions as $nr => $arr) {
	list ($city,$country,$region)	= explode (',',$arr['town'].',,');
# "conds":"day_clear.gif,Clear,29 C,61%,21 C,ESE,2 kmh,2,0.0 mm,1013.9 hPa,Falling Slowly"
	if ($arr['conds']  <> 'Offline') {
		list ($icon,$text,$temp,$hum,$dewpt,$wdsc,$wspd,$gust,$rain,$baro,$trend,$updated) = explode (',',$arr['conds'].',,,,,,,,,,');
		$offline = '';
	} 
	else {	if (!$show_offline) {continue;}
		$offline = '&nbsp;<small style="color: red;">'.MESO_OFFLINE.'</small>';
	}
	$surl	= $arr['surl'];
	echo '<tr style="border-bottom: 1px solid grey; height: 27px;">
<td style="text-align: left; padding-left: 5px;">'.$country.'</td>
<td style="text-align: left;"><a href="http://'.$surl.'"'.$target.'>'.$city.'</a></td>';
	if ($offline <> '') { 
		echo '<td>'.$offline.'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
		if  ($data_global <> true) { echo '<td></td>';}
		echo PHP_EOL.'</tr>';
		continue;
	} // eo offline
	$text		= langtransstr($text);
	if (is_file($condIconsDir.$icon) ) {	// icon exists
		$string = '<img src="'.$condIconsDir.$icon.'" style="vertical-align:bottom;" alt="'.$text.'"/>';
	} 
	else  {	$string = '-';
	}
	$trend		= langtransstr($trend);
	$windicon	= $condIconsDir.$wdsc.'.gif';
	$wind_string	= '';
	if (is_file($windicon)  ) {
		$wind_string = '<img src="'.$windicon.'" style="vertical-align:bottom;" alt="'.$wdsc.'"/>';	
	} 
	list($text1,$text2)	= explode (':',$text.':');
	if ($text2 <> '') {$text=$text2;}
	echo '<td>'.$string.'</td><td>'.$text.'</td><td>'.$temp.'</td><td>'.$dewpt.'</td><td>'.$hum.'</td><td style="text-align: right;">'.$wspd.'</td><td style="text-align: left;"> '.langtransstr($wdsc).$wind_string.'</td><td>'.$gust.'</td><td>'.$rain.'</td><td>'.$baro.'</td><td>'.$trend.'</td>';
	if  ($data_global <> true) {echo '<td>'.$updated.'</td>';}
	echo PHP_EOL.'</tr>';
}  // eo for each
echo'
</tbody>
</table>
<br />'.PHP_EOL;
if ($do_tabs == true) {
	echo '</div>'.PHP_EOL;
}
# --------------------------------------------------------------------------------------
# display the list of stations  $printList
# --------------------------------------------------------------------------------------
if ($printList) {
	if (count($arr_countries) > 10) {$do_tabs_members = false;}
	if (count($arr_countries) == 1) {$do_tabs_members = false;}
	if ($do_tabs == true) {
		echo '<div class="tabbertab " style="width: 100%; padding: 0;">
<h3>'.MESO_MEMBHEAD.' '.MESO_LONG.'</h3><br />'.PHP_EOL;
	}
	if (!isset ($do_tabs_members) ) {$do_tabs_members = false;}
	#
	if ($do_tabs_members == true) {
		echo '<p style="width: 98%;  margin: 0px auto;">'.MESO_THISSTATION.MESO_BOXPRTB.'</p>';
		echo '<div class="tabber" style="width: 100%;">'.PHP_EOL;
	}
	else {	echo '<div style="width: 600px;">
<h3 style="margin: 20px;">'.MESO_MEMBHEAD.' '.MESO_LONG.'</h3>
<p style=" margin: 10px;">'.MESO_THISSTATION.MESO_BOXPRTB.'<br /><br /></p>
<ul style="margin-left: 20px; list-style: square;">'.PHP_EOL;
	}
	$country_old	= '';
	$country_switch	= '';
	$first	= '';
	$count	= count($arr_conditions);
	for ($n1 = 0; $n1 < $count; $n1++) {
		$arr	= $arr_conditions[$n1];
		list ($city,$country,$region)	= explode (',',$arr['town'].',,');
		if ($country_old <> $country ) {
			echo $country_switch;
			if (isset ($do_tabs_members)  && $do_tabs_members == true) {
				echo '<div class="tabbertab " style="text-align: left;">
<h3>'.$country.'</h3><br />
<div style="width: 600px; ">'.PHP_EOL;
				$country_switch	= '</ul></div>'.PHP_EOL.'</div>'.PHP_EOL;
			}
			else {	echo '<li>'.$country.PHP_EOL;
				$country_switch	= '</ul>'.PHP_EOL.'</li>'.PHP_EOL;
			}
			echo '<ul style="margin-left: 20px; list-style: circle">'.PHP_EOL;
			$country_old = $country;
		}
		$fcode	= $arr['fcode'];
		switch ($fcode) {
			case 'all':
				$features = '[Weather, Lightning, WebCam]';
			break;
			case 'lgt':
				$features = '[Weather, Lightning]';
			break;
			case 'cam':
				$features = '[Weather, WebCam]';
			break;
			default:
				$features = '[Weather]';		
	
		}
		echo '<li><a href="http://'.$arr['surl'].'" target="_blank">'.$city.'</a>&nbsp;&nbsp;'.$features.'</li>'.PHP_EOL;
	} // eo for 
	echo '</ul>'.PHP_EOL;
	if ($do_tabs_members == true) {
		echo '</div></div>'.PHP_EOL.'</div>'.PHP_EOL;
	}
	else {	echo '</li>'.PHP_EOL.'</ul>'.PHP_EOL.'</div>'.PHP_EOL;
	}
	echo '<br /><br />';

	if ($do_tabs == true) { echo '</div>'.PHP_EOL; }   //   enclosing /div for main tabs
} // eo printlist;

# end of display
if ($do_tabs == true) { // do_tabs_members
	echo '<br />
</div>';
}
if  ($do_tabs == true || $do_tabs_members == true) {
	echo '<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
echo '<h3 class="blockHead">'.$creditPart.'</h3>';
echo '</div>
<!-- end regional map -->'.PHP_EOL;

function meso_load_file ($URL, $cacheName, &$rawdata) {
	global $pageFile, $characterset, $remote_time ;
	ws_message (  "<!-- module $pageFile (".__LINE__."): For file $cacheName we need to load a fresh copy -->");
	ws_message (  "<!-- module $pageFile (".__LINE__."): loading $URL -->");
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120424 Firefox/12.0 PaleMoon/12.0');
        curl_setopt($ch, CURLOPT_FILETIME, 1);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 3);
        $rawdata 	= curl_exec ($ch);
        $info 		= curl_getinfo ($ch);
	$remote_time	= $info['filetime'];
	ws_message (  "<!-- module $pageFile (".__LINE__."): ". print_r($info,true).' -->');
	ws_message (  "<!-- module $pageFile (".__LINE__."): URL $URL loaded in  ".$info['total_time']." seconds  -->");
	curl_close ($ch);
	if ($characterset <> "UTF-8") {
		$rawdata = iconv ("WINDOWS-1252", "UTF-8//IGNORE",$rawdata);
	}
	if (file_put_contents($cacheName, $rawdata)) {   
	       ws_message (  "<!-- module $pageFile (".__LINE__."): File $cacheName saved to cache  -->");
	} else {
		ws_message (  "<h3>module $pageFile (".__LINE__."): ERROR  File $cacheName could not be saved to cache. Program stops.</h3>");
		return false;
	}
	return true;
}

function meso_check_cache ($cacheName, $cacheAllowed) {
	global $pageFile;
	if ($cacheAllowed <= 0) {
		ws_message (  "<!-- module $pageFile (".__LINE__."): $cacheName not checked, allowed time = $cacheAllowed -->", true);
		return false;
	}
	if (!file_exists($cacheName) ) {
		ws_message (  "<!-- module $pageFile (".__LINE__."): $cacheName does not exist yet -->");
		return false;
	}
	$cache = false;
	$file_time      = filemtime($cacheName);
	$now            = time();
	$diff           = ($now     -   $file_time);
	ws_message (  "<!-- module $pageFile (".__LINE__."): $cacheName times:
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff seconds
	diff allowed = $cacheAllowed seconds -->");	
	if ($diff <= $cacheAllowed){
		ws_message (  "<!-- module $pageFile (".__LINE__."): File $cacheName current in cache -->");
		$cache = true;
	}  // file is valid 
	return $cache;
} // eo function meso_check_cache

function regionBoilerplate () {
#define('MESO',			'BNLWN');
define('MESO_LONG',		'Regional Weather Network');
define('MESO_SHORT',		'Regional Weather Network');
define('MESO_URL',		'http://www.northamericanweather.net/');
define('MESO_AREA',		'This region');	
define('MESO_REGION',		'America');	
define('MESO_CHARSET',		'UTF-8');
}
function langBoilerplate () {
define('MESO_TXT1', 		'<p>The <a href="'.MESO_URL.'">'.MESO_LONG.'</a>
is an affiliation of personal weather Websites located in '.MESO_AREA.'.');

define('MESO_THISSTATION',	'This station'				); 

define('MESO_BOXPRTB', 		' is proud to be a member of the '.MESO_LONG.'.<br/><br/>
Please take a moment to visit other stations on the network by clicking on the map above or clicking on the links below. 
The stations are displaying the weather conditions: Temperature, dew point, humidity,
wind direction and speed/gust speed, today\'s rain, barometric pressure and trend, current conditions
and the name of the city where the weather station is located.');
/*
<noscript><p><br /><br />
<b>Note: Enable JavaScript for live updates.</b></p></noscript>
<h2>Member stations of the '.MESO_LONG.'</h2>
<p>as of ');
*/
define('MESO_COLSORT', 		'Note: Click on the table column title to change the order of that column\'s values.');

# Tables and title= tags
define('MESO_PAGEHEAD', 	'Current weather conditions obtained from the member stations of the'	);
define('MESO_CONDHEAD', 	'Current weather conditions at the weather stations');
define('MESO_MEMBHEAD', 	'Member stations of the ');

# table headers
define('MESO_FEAT', 		'Station<br/>Features/Altitude'		);
define('MESO_STATE', 		'Country'				);
define('MESO_STATION',		'Station'				);
#define('MESO_CURHEAD', 		'Current<br />Cond.'			);
define('MESO_CONDL',		'Current Condition'			);
define('MESO_TEMP', 		'Temp.'					);
define('MESO_TEMPL', 		'Temperature'				);
define('MESO_DEWPT', 		'Dew Point'				);
define('MESO_DEWPTABBREV',	'DP'					);
define('MESO_HUM', 		'Humid'					);
define('MESO_HUML', 		'Humidity'				);
define('MESO_AVGWIND', 		'Wind'					);
define('MESO_GUSTWIND', 	'Gust'					);
define('MESO_GUSTABBREV',	'G'					);
define('MESO_WINDL', 		'Wind Direction and Speed/Gust'		);
define('MESO_WIND', 		'Wind'					);
define('MESO_WINDFROM', 	'Wind from'				);
define('MESO_PRECIPS', 		'Rain'					);
define('MESO_PRECIPSL', 	'Rain Today'				);
define('MESO_BAROB', 		'Pressure'				);
define('MESO_BAROT', 		'Trend'					);
define('MESO_BAROL', 		'Pressure and Trend'			);
define('MESO_SNOB', 		'Snow'					);
define('MESO_TXTGUST', 		'Gust'					);
define('MESO_DATAUPDT', 	'Last<br />Update'			);
define('MESO_NOCOND', 		'No current conditions report'		);
define('MESO_TOWN',		'Name of the town'			);
define('MESO_OFFLINE',		'Offline'				); // text to display on mesomap when station data is stale/not available

# for javascript animation control button lables
define('MESO_RUN', 		'Run'					);
define('MESO_PAUSE', 		'Pause'					);
define('MESO_STEP', 		'Step'					);

# date-time 
define('MESO_CONDSFROM', 	'Conditions data shown was collected from');
define('MESO_CONDSTO', 		'to'	);

define('MESO_HEADER1', 		'Header text ?'	);

}
# ----------------------  version history
# 3.20 2015-09-28 release 2.8 version  plus validity check latitude longitude.
