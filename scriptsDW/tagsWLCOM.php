<?php 	#ini_set('display_errors', 'On');   error_reporting(E_ALL);	
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'tagsWLCOM.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-03-23';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.01 2015-03-23 2.7 release version
# --------------------------------------- version ----------------------
$ws['tags_processed']	= $pageName.'-'.$pageVersion;
$ws['tags_originated']	= 'wl.com-user'.$SITE['wlink_key'];
$ws['tags_today']	= $ws['tags_originated'];
$ws['tags_yday']	= $SITE['ydayTags'];
$ws['tags_yday_time']	= 'n/a';
#
$tagsWLCOM      = $pageName;
$test           = false;
if ($test) {echo '<pre>Start test'.PHP_EOL;}
#
if ($test) {
        $startEcho      = '';           $endEcho        = '';
} else {$startEcho      = '<!-- ';      $endEcho        = ' -->';
}
$url            = 'http://www.weatherlink.com/xml.php?user='.$SITE['wlink_key'].'&pass='.$SITE['wlink_pw'];
$uomTemp	= $SITE['uomTemp'];
$uomBaro	= $SITE['uomBaro'];
$uomRain	= $SITE['uomRain'];
$uomSnow	= $SITE['uomSnow'];
$uomDistance    = $SITE['uomDistance'];
$uomWind	= $SITE['uomWind'];
$uomPerHour	= $SITE['uomPerHour'];
$uomHeight	= $SITE['uomHeight'];
$fileToLoad     = $SITE['wsTags'];
$uoms		= $uomTemp.$uomBaro.$uomWind.$uomRain.$uomSnow.$uomDistance.$uomPerHour.$uomHeight;
$from		= array('/',' ','&deg;','.php');
$to		= '';
$cachefileWLC	= $SITE['cacheDir'].'uploadDW'.str_replace ($from, $to, $SITE['wlink_key'].'_'.$fileToLoad.'_'.$uoms);  // add uoms
#echo $cachefileWLC; exit;
$loaded_current = false;
$local_current  = './uploadDW/weatherlink3.xml';
if ($SITE['wlink_key'] == 'xyz') {$test = true;}	
#
if (isset($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'wlc') {
        echo $startEcho.$tagsWLCOM.': data freshly loaded while "force" was used.'.$endEcho.PHP_EOL;
        $loaded_current =  false;
}elseif (file_exists($cachefileWLC) ){
	$file_time      = filemtime($cachefileWLC);
	$now            = time();
	$diff           = ($now-$file_time);
	$cacheAllowed   = $SITE['cacheDW'];
        echo  "<!-- 
$tagsWLCOM ($cachefileWLC)
        cache time   = ".date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $cacheAllowed (seconds) -->".PHP_EOL;		
	if ($diff <= $cacheAllowed){
		$ws     =  unserialize(file_get_contents($cachefileWLC));
                echo $startEcho.$tagsWLCOM.': data loaded from '.$cachefileWLC.$endEcho.PHP_EOL;
                $loaded_current =   true;
 #               print_r ($ws); # exit;
                return;         // ?????
	} else {
		echo $startEcho.$tagsWLCOM.": data to old, will be loaded from url ".$endEcho.PHP_EOL;
	}
}
if ($test) {
        echo $startEcho.$tagsWLCOM.': data loaded from test-file at '.$local_current.$endEcho.PHP_EOL;
        $string = file_get_contents($local_current);
        $loaded_current = false;
}elseif ($loaded_current == false) {
        echo $startEcho.$tagsWLCOM.': data loaded from url: '.$url.$endEcho.PHP_EOL;
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
        $string = curl_exec ($ch);
 #       echo $string; exit;
        curl_close ($ch);
} 
if ($loaded_current == false && trim($string) == '') {
	if ($diff <= 3* $cacheAllowed){
		$ws     =  unserialize(file_get_contents($cachefileWLC));
                echo $startEcho.$tagsWLCOM.': data loaded from '.$cachefileWLC.' after upping cache time'.$endEcho.PHP_EOL;
                $loaded_current =   true;
 #               print_r ($ws); # exit;
                return;         // ?????
        }
        echo '<H3 input file from wl.com has no contents - program ends, please reload page </h3>'; return;
}
$davis  = new SimpleXMLElement($string);
$xml    = $davis->davis_current_observation;
#$ws = array();          // ????
$ws_date_ft = 'YmdHis';
# --------------------------------------- conditions--------------------

$ws['actTime']	= date($ws_date_ft,strtotime ((string) $davis->observation_time_rfc822)) ;   //'<observation_time_rfc822>Fri, 24 Oct 2014 09:01:16 +0200</observation_time_rfc822>';
$ymd            = substr($ws['actTime'],0,8);
$ws['tags_today_time']	= $ws['actTime'];

# ------------------------------------------ temperature ---------------
#                               NO yesterday values  !
$to 	= $SITE['uomTemp'];
$from	= 'F';
# ------------   temp outside
#$ws['tempAct']		= wsConvertTemperature((string)$davis->temp_c, $from);          // <temp_c>11.6</temp_c> 
$ws['tempAct']		= wsConvertTemperature((string)$davis->temp_f, $from);          // <temp_f>52.8</temp_f>
$ws['tempDelta'] = 0;

$ws['tempMinToday']	= wsConvertTemperature((string)$xml->temp_day_low_f, 	$from); // <temp_day_low_f>51.7</temp_day_low_f>
$ws['tempMinMonth']	= wsConvertTemperature((string)$xml->temp_month_low_f, 	$from); // <temp_month_low_f>44.6</temp_month_low_f>
$ws['tempMinYear']	= wsConvertTemperature((string)$xml->temp_year_low_f, 	$from); // <temp_year_low_f>31</temp_year_low_f>
$ws['tempMinTodayTime']	= wdDate((string)$xml->temp_day_low_time);                      // <temp_day_low_time>2:40pm</temp_day_low_time>

$ws['tempMaxToday']	= wsConvertTemperature((string)$xml->temp_day_high_f, 	$from); // <temp_day_high_f>57.2</temp_day_high_f>
$ws['tempMaxMonth']	= wsConvertTemperature((string)$xml->temp_month_high_f, $from); // <temp_month_high_f>77.3</temp_month_high_f>
$ws['tempMaxYear']	= wsConvertTemperature((string)$xml->temp_year_high_f, 	$from); // <temp_year_high_f>96.4</temp_year_high_f>
$ws['tempMaxTodayTime']	= wdDate((string)$xml->temp_day_high_time);                     // <temp_day_high_time>12:00am</temp_day_high_time>
# ------------   temp inside
$ws['tempActInside']	        = wsConvertTemperature((string)$xml->temp_in_f, 	$from);         // <temp_in_f>77.1</temp_in_f>
$ws['tempInAct']                = $ws['tempActInside'];

$ws['tempInMinToday']           = wsConvertTemperature((string)$xml->temp_in_day_low_f, $from);         // <temp_in_day_low_f>76.7</temp_in_day_low_f>  
$ws['tempInMinMonth']           = wsConvertTemperature((string)$xml->temp_in_month_low_f, $from);       // <temp_in_month_low_f>76.3</temp_in_month_low_f>  
$ws['tempInMinYear']            = wsConvertTemperature((string)$xml->temp_in_year_low_f, $from);        // <temp_in_year_low_f>68</temp_in_year_low_f> 
$ws['tempInMinTodayTime']       = wdDate((string)$xml->temp_in_day_low_time);                           // <temp_in_day_low_time>2:34pm</temp_in_day_low_time>  

$ws['tempInMaxToday']           = wsConvertTemperature((string)$xml->temp_in_day_high_f, $from);        // <temp_in_day_high_f>78.6</temp_in_day_high_f>  
$ws['tempInMaxMonth']           = wsConvertTemperature((string)$xml->temp_in_month_high_f, $from);      //<temp_in_month_high_f>86.4</temp_in_month_high_f> 
$ws['tempInMaxYear']            = wsConvertTemperature((string)$xml->temp_in_year_high_f, $from);       // <temp_in_year_high_f>106</temp_in_year_high_f>
$ws['tempInMaxTodayTime']       = wdDate((string)$xml->temp_in_day_high_time);                          // <temp_in_day_high_time>10:33am</temp_in_day_high_time>  
# ------------   temp extra 1
if (isset ($xml->temp_extra_1) ) {
$ws['tempActExtra1']	        = wsConvertTemperature((string)$xml->temp_extra_1, 	$from);	        // <temp_extra_1>61</temp_extra_1>
$ws['tempE1Act']                = $ws['tempActExtra1'];

$ws['tempE1MinToday']           = wsConvertTemperature((string)$xml->temp_extra_1_day_low, 	$from);	// <temp_extra_1_day_low>61</temp_extra_1_day_low>
$ws['tempE1MinMonth']           = wsConvertTemperature((string)$xml->temp_extra_1_month_low, 	$from);	// <temp_extra_1_month_low>61</temp_extra_1_month_low>
$ws['tempE1MinYear']            = wsConvertTemperature((string)$xml->temp_extra_1_year_low, 	$from);	// <temp_extra_1_year_low>61</temp_extra_1_year_low>
$ws['tempE1MinTodayTime']       = wdDate((string)$xml->temp_extra_1_day_low_time);	                // <temp_extra_1_day_low_time>4:13am</temp_extra_1_day_low_time>

$ws['tempE1MaxToday']           = wsConvertTemperature((string)$xml->temp_extra_1_day_high, 	$from);	// <temp_extra_1_day_high>62</temp_extra_1_day_high>
$ws['tempE1MaxMonth']           = wsConvertTemperature((string)$xml->temp_extra_1_month_high, 	$from);	// <temp_extra_1_month_high>67</temp_extra_1_month_high>
$ws['tempE1MaxYear']            = wsConvertTemperature((string)$xml->temp_extra_1_year_high, 	$from);	// <temp_extra_1_year_high>86</temp_extra_1_year_high>
$ws['tempE1MaxTodayTime']       = wdDate((string)$xml->temp_extra_1_day_high_time);	                // <temp_extra_1_day_high_time>12:00am</temp_extra_1_day_high_time>
}
else {  $ws['tempActExtra1'] = $ws['tempE1Act'] = '';
}
if (isset ($xml->temp_extra_2) ) {
        $ws['tempActExtra2']	= wsConvertTemperature((string)$xml->temp_extra_2, 	$from);	
        $ws['tempE2Act']        = $ws['tempActExtra2'];

        $ws['tempE2MinToday']   = wsConvertTemperature((string)$xml->temp_extra_2_day_low, 	$from);
        $ws['tempE2MinMonth']   = wsConvertTemperature((string)$xml->temp_extra_2_month_low, 	$from);	
        $ws['tempE2MinYear']    = wsConvertTemperature((string)$xml->temp_extra_21_year_low, 	$from);	
        $ws['tempE2MinTodayTime'] = wdDate((string)$xml->temp_extra_2_day_low_time);	

        $ws['tempE2MaxToday']   = wsConvertTemperature((string)$xml->temp_extra_2_day_high, 	$from);
        $ws['tempE2MaxMonth']   = wsConvertTemperature((string)$xml->temp_extra_2_month_high, 	$from);	
        $ws['tempE2MaxYear']    = wsConvertTemperature((string)$xml->temp_extra_2_year_high, 	$from);	
        $ws['tempE2MaxTodayTime']= wdDate((string)$xml->temp_extra_2_day_high_time);
} 
else {  $ws['tempActExtra2'] = $ws['tempE2Act'] = '';
}
# ------------   dewpoint
$ws['dewpAct']  	        = wsConvertTemperature((string)$davis->dewpoint_f, 	$from);         // <dewpoint_f>48</dewpoint_f>
$ws['dewpDelta'] = 0;

$ws['dewpMinToday']  	        = wsConvertTemperature((string)$xml->dewpoint_day_low_f, 	$from); // <dewpoint_day_low_f>46</dewpoint_day_low_f>
$ws['dewpMinMonth']  	        = wsConvertTemperature((string)$xml->dewpoint_month_low_f, 	$from); // <dewpoint_month_low_f>41</dewpoint_month_low_f>
$ws['dewpMinYear']  	        = wsConvertTemperature((string)$xml->dewpoint_year_low_f, 	$from); // <dewpoint_year_low_f>29</dewpoint_year_low_f>
$ws['dewpMinTodayTime']	        = wdDate((string)$xml->dewpoint_day_low_time);                          // <dewpoint_day_low_time>12:34pm</dewpoint_day_low_time>

$ws['dewpMaxToday']  	        = wsConvertTemperature((string)$xml->dewpoint_day_high_f, 	$from); // <dewpoint_day_high_f>53</dewpoint_day_high_f>
$ws['dewpMaxMonth']  	        = wsConvertTemperature((string)$xml->dewpoint_month_high_f, 	$from); // <dewpoint_month_high_f>64</dewpoint_month_high_f>
$ws['dewpMaxYear']  	        = wsConvertTemperature((string)$xml->dewpoint_year_high_f, 	$from); // <dewpoint_year_high_f>72</dewpoint_year_high_f>
$ws['dewpMaxTodayTime']	        = wdDate((string)$xml->dewpoint_day_high_time);                         // <dewpoint_day_high_time>12:00am</dewpoint_day_high_time>
# ------------   temp windchill	
$ws['chilAct']		        = wsConvertTemperature((string)$davis->windchill_f, 	$from);         // <windchill_f>53</windchill_f>
$ws['chilDelta']	        = '0';

$ws['chilMinToday']	        = wsConvertTemperature((string)$xml->windchill_day_low_f, 	$from); // <windchill_day_low_f>52</windchill_day_low_f>
$ws['chilMinMonth']	        = wsConvertTemperature((string)$xml->windchill_month_low_f, 	$from); // <windchill_month_low_f>43</windchill_month_low_f>
$ws['chilMinYear']	        = wsConvertTemperature((string)$xml->windchill_year_low_f, 	$from); // <windchill_year_low_f>31</windchill_year_low_f>
$ws['chilMinTodayTime']	        = wdDate((string)$xml->windchill_day_low_time);                          // <windchill_day_low_time>9:10am</windchill_day_low_time>';
# ------------   heat index	
$ws['heatAct']		= wsConvertTemperature((string)$davis->heat_index_f, 	$from);                 // <heat_index_f>53</heat_index_f>

$ws['heatMaxToday']	= wsConvertTemperature((string)$xml->heat_index_day_high_f, 	$from);         // <heat_index_day_high_f>57</heat_index_day_high_f>
$ws['heatMaxMonth']	= wsConvertTemperature((string)$xml->heat_index_month_high_f, 	$from);         // <heat_index_month_high_f>79</heat_index_month_high_f>
$ws['heatMaxYear']	= wsConvertTemperature((string)$xml->heat_index_year_high_f, 	$from);         // <heat_index_year_high_f>101</heat_index_year_high_f>
$ws['heatMaxTodayTime']	= wdDate((string)$xml->heat_index_day_high_time);                               // <heat_index_day_high_time>12:00am</heat_index_day_high_time>	
# ------------------------------------------ pressure / baro -----------
$to 	= $SITE['uomBaro'];
$from	= 'in';
$ws['baroAct'] 		= wsConvertBaro((string)$davis->pressure_in, 	$from);                         // <pressure_in>30.09</pressure_in>
$ws['baroDelta']        = (string) $xml-> pressure_tendency_string;                                     // <pressure_tendency_string>Steady</pressure_tendency_string>
$ws['baroTrend']        = (string) $xml-> pressure_tendency_string;  

$ws['baroMinToday']	= wsConvertBaro((string)$xml->pressure_day_low_in,   $from);                    // <pressure_day_low_in>30.079</pressure_day_low_in>
$ws['baroMinMonth']	= wsConvertBaro((string)$xml->pressure_month_low_in, 	$from);                 // <pressure_month_low_in>29.512</pressure_month_low_in>
$ws['baroMinYear'] 	= wsConvertBaro((string)$xml->pressure_year_low_in, 	$from);                 // <pressure_year_low_in>29.003</pressure_year_low_in>	
$ws['baroMinTodayTime']	= wdDate((string)$xml->pressure_day_low_time);                                  // <pressure_day_low_time>3:35pm</pressure_day_low_time>

$ws['baroMaxToday']	= wsConvertBaro((string)$xml->pressure_day_high_in, 	$from);                 // <pressure_day_high_in>30.174</pressure_day_high_in>
$ws['baroMaxMonth']	= wsConvertBaro((string)$xml->pressure_month_high_in, 	$from);                 // <pressure_month_high_in>30.206</pressure_month_high_in>
$ws['baroMaxYear'] 	= wsConvertBaro((string)$xml->pressure_year_high_in, 	$from);                 // <pressure_year_high_in>30.577</pressure_year_high_in>
$ws['baroMaxTodayTime']	= wdDate((string)$xml->pressure_day_high_time);                                 // <pressure_day_high_time>12:07am</pressure_day_high_time>

# ------------------------------------------ humidity  -----------------------------------
# ------------   humidity outside 
$ws['humiAct']		        = (string) $davis -> relative_humidity;                                 // <relative_humidity>83</relative_humidity>
$ws['humiDelta'] = 0;

$ws['humiMinToday'] 	        = (string) $xml -> relative_humidity_day_low;                           // <relative_humidity_day_low>80</relative_humidity_day_low>
$ws['humiMinMonth'] 	        = (string) $xml -> relative_humidity_month_low;                         // <relative_humidity_month_low>61</relative_humidity_month_low>
$ws['humiMinYear'] 	        = (string) $xml -> relative_humidity_year_low;                          // <relative_humidity_year_low>27</relative_humidity_year_low>
$ws['humiMinTodayTime']         = wdDate((string)$xml->relative_humidity_day_low_time);                 // <relative_humidity_day_low_time>12:34pm</relative_humidity_day_low_time>

$ws['humiMaxToday']	        = (string) $xml -> relative_humidity_day_high;                          // <relative_humidity_day_high>89</relative_humidity_day_high>
$ws['humiMaxMonth']	        = (string) $xml -> relative_humidity_month_high;                        // <relative_humidity_month_high>97</relative_humidity_month_high>
$ws['humiMaxYear']	        = (string) $xml -> relative_humidity_year_high;                         // <relative_humidity_year_high>99</relative_humidity_year_high>
$ws['humiMaxTodayTime']         = wdDate((string)$xml->relative_humidity_day_high_time);                // <relative_humidity_day_high_time>3:26am</relative_humidity_day_high_time>
# ------------   humidity ibside
$ws['humiInAct']		= (string) $xml -> relative_humidity_in;                                // <relative_humidity_in>83</relative_humidity_in>
$ws['humiInDelta'] = 0;

$ws['humiInMinToday'] 	        = (string) $xml -> relative_humidity_in_day_low;                        // <relative_humidity_in_day_low>33</relative_humidity_in_day_low>
$ws['humiInMinMonth'] 	        = (string) $xml -> relative_humidity_in_month_low;                      // <relative_humidity_in_month_low>31</relative_humidity_in_month_low>
$ws['humiInMinYear'] 	        = (string) $xml -> relative_humidity_in_year_low;                       // <relative_humidity_in_year_low>19</relative_humidity_in_year_low>
$ws['humiInMinTodayTime']       = wdDate((string)$xml->relative_humidity_in_day_low_time);              // <relative_humidity_in_day_low_time>9:34am</relative_humidity_in_day_low_time>

$ws['humiInMaxToday']	        = (string) $xml -> relative_humidity_in_day_high;                       // <relative_humidity_in_day_high>36</relative_humidity_in_day_high>
$ws['humiInMaxMonth']	        = (string) $xml -> relative_humidity_in_month_high;                     // <relative_humidity_in_month_high>40</relative_humidity_in_month_high>
$ws['humiInMaxYear']	        = (string) $xml -> relative_humidity_in_year_high;                      // <relative_humidity_in_year_high>54</relative_humidity_in_year_high>
$ws['humiInMaxTodayTime']       = wdDate((string)$xml->relative_humidity_in_day_high_time);             // <relative_humidity_in_day_high_time>3:10pm</relative_humidity_in_day_high_time>


# ------------------------------------------ rain  ---------------------
$to 	= $SITE['uomRain'];
$from	= 'in'; 
#
$ws['rainRateAct'] 	= wsConvertRainfall((string)$xml->rain_rate_in_per_hr,  $from);                 // <rain_rate_in_per_hr>0.0000</rain_rate_in_per_hr>
$ws['rainRateToday'] 	= wsConvertRainfall((string)$xml->rain_rate_in_per_hr,  $from);                 // ??  <rain_rate_in_per_hr>0.0000</rain_rate_in_per_hr>

$ws['rainToday']	= wsConvertRainfall((string)$xml->rain_day_in,          $from);                 // <rain_day_in>0.0000</rain_day_in>
$ws['rainMonth']	= wsConvertRainfall((string)$xml->rain_month_in,        $from);                 // <rain_month_in>0.7480</rain_month_in>
$ws['rainYear']		= wsConvertRainfall((string)$xml->rain_year_in,         $from) ;                // <rain_year_in>19.2756</rain_year_in>

# ----- ev test
$ws['etToday']		= wsConvertRainfall((string)$xml->et_day,               $from);                 // <et_day>0.007</et_day>
$ws['etMonth']		= wsConvertRainfall((string)$xml->et_month,             $from);                 // <et_month>0.46</et_month>
$ws['etYear']		= wsConvertRainfall((string)$xml->et_year,              $from);                 // <et_year>22.55</et_year>
# ------------------------------------------ wind  ---------------------
$to 	= $SITE['uomWind'];
$from	= 'mph'; 	

$ws['windActDeg']       = (string) $davis -> wind_degrees;                              // <wind_degrees>184</wind_degrees>
$ws['windAvgDir']       = $ws['windActDir'] = $ws['windActDeg'];
#$ws['windActDsc']	= (string) $davis -> wind_dir;                                  // <wind_dir>South</wind_dir>
$ws['windActDsc']	= wsConvertWinddir ($ws['windActDeg']);

$ws['windAct']		= wsConvertWindspeed((string)$xml->wind_ten_min_avg_mph,$from); // <wind_ten_min_avg_mph>1</wind_ten_min_avg_mph>
$ws['windBeafort']	= wsBeaufortNumber ((string)$xml->wind_ten_min_avg_mph, $from);

#$ws['gustAct']		= wsConvertWindspeed((string)$xml->wind_kt, 	        'kts'); // <wind_kt>1</wind_kt>
$ws['gustAct']		= wsConvertWindspeed((string)$xml->wind_mph, 	        $from); // <wind_mph>1</wind_mph>

$ws['gustMaxToday']	= wsConvertWindspeed((string)$xml->wind_day_high_mph, 	$from); // <wind_day_high_mph>9</wind_day_high_mph>
$ws['gustMaxMonth']	= wsConvertWindspeed((string)$xml->wind_month_high_mph, $from); // <wind_month_high_mph>22</wind_month_high_mph>
$ws['gustMaxYear']	= wsConvertWindspeed((string)$xml->wind_year_high_mph, 	$from); // <wind_year_high_mph>27</wind_year_high_mph>

$ws['gustMaxTodayTime']	= wdDate((string)$xml->relative_humidity_in_day_high_time);     // <wind_day_high_time>12:38am</wind_day_high_time>

if ($ws['gustAct'] <= $ws['windAct'])	{$ws['gustAct'] = $ws['windAct'];}
# ------------------------------------------  UV   ---------------------
$ws['uvAct']		= (string)$xml->uv_index;                               // <uv_index>0.0</uv_index>
if (isset($xml->uv_index_day_high) ) { 
        $ws['uvMaxToday']       = (string)$xml->uv_index_day_high;
        $ws['uvMaxTodayTime']   = wdDate((string)$xml->uv_index_day_high_time);}
else {  $ws['uvMaxToday']       = '-1';
        $ws['uvMaxTodayTime']   = '0';  }
$ws['uvMaxMonth']	= (string)$xml->uv_index_month_high;                    // <uv_index_month_high>2.1</uv_index_month_high>
$ws['uvMaxYear']	= (string)$xml->uv_index_year_high;                     // <uv_index_year_high>7.2</uv_index_year_high>
# ------------------------------------------ Solar  --------------------
$ws['solarAct']		= (string)$xml->solar_radiation;                        // <solar_radiation>7</solar_radiation>
$ws['solarMaxToday']	= (string)$xml->solar_radiation_day_high;               // <solar_radiation_day_high>116</solar_radiation_day_high>
$ws['solarMaxMonth']	= (string)$xml->solar_radiation_month_high;             // <solar_radiation_month_high>659</solar_radiation_month_high>
$ws['solarMaxYear']	= (string)$xml->solar_radiation_year_high;              // <solar_radiation_year_high>1264</solar_radiation_year_high>

$ws['solarMaxTodayTime']= wdDate((string)$xml->solar_radiation_day_high_time);  // <solar_radiation_day_high_time>12:18am</solar_radiation_day_high_time>
# ------------------------------------------ sun NO moon --------------
$ws['sunrise']		= wdDate((string)$xml->sunrise);                        //'<sunrise>7:59am</sunrise>';
$ws['sunset']		= wdDate((string)$xml->sunset);;                        //<sunset>6:55pm</sunset>';
# ------------------------------------------ some more -----------------
$ws['wsVersion']	= (string)$davis->credit;                               // <credit>Davis Instruments Corp.</credit>'; // <davis_current_observation version="1.0">
$ws['wsHardware'] 	= (string)$davis->station_id;                           // <station_id>wvdkuil</station_id>'; 
$ws['wsUptime']		= '0';
$ws['latitude']		= (string)$davis->latitude;                             //<latitude>50.8952</latitude>';
$ws['longitude']	= (string)$davis->longitude;                            //<longitude>4.6974</longitude>';
$ws['location']	        = (string)$davis->location;                             //<location>Wilsele, Vlaams Brabant, Belgium</location>';
# -------------------------------------- trends ------------------------
# -------------------------------------- soil moisture -----------------------------------
if ($SITE['soilUsed'] && $SITE['soilCount']*1.0 > 0) {
        $from	        = 'F';
        $i 		= 1;
 # Soil sensor 1 actual value
        if (isset ($xml -> temp_soil_1) )       { $string = (string) $xml -> temp_soil_1;}
        elseif (isset ($davis -> temp_soil_1) ) { $string = (string) $davis -> temp_soil_1;}
        else                                    { $string = (string) $xml -> temp_soil_1_day_high;}   
        $ws['soilTempAct'][$i]		= wsConvertTemperature($string, 	$from);
 # Soil sensor 1 max/min values and time
        $ws['soilTempMaxToday'][$i]	= wsConvertTemperature($xml -> temp_soil_1_day_high, 	$from);
        $ws['soilTempMaxMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_1_month_high, 	$from);
        $ws['soilTempMaxYear'][$i]	= wsConvertTemperature($xml -> temp_soil_1_year_high, 	$from);
        $ws['soilTempMaxTodayTime'][$i]	= wdDate((string)$xml->temp_soil_1_day_high_time);

        $ws['soilTempMinToday'][$i]	= wsConvertTemperature($xml -> temp_soil_1_day_low, 	$from);
        $ws['soilTempMinMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_1_month_low, 	$from);
        $ws['soilTempMinYear'][$i]	= wsConvertTemperature($xml -> temp_soil_1_year_low, 	$from);
        $ws['soilTempMinTodayTime'][$i]	= wdDate((string)$xml->temp_soil_1_day_low_time);     
# Moisture sensor 1 actual value
        if (isset ($xml -> soil_moisture_1) )           { $string = (string) $xml -> soil_moisture_1;}
        elseif (isset ($davis -> soil_moisture_1) )     { $string = (string) $davis -> soil_moisture_1;}
        else                                            { $string = (string) $xml -> soil_moisture_1_day_high;}   
        $ws['moistAct'][$i]		= $string;
 # Moisture sensor 1 max/min values and time
        $ws['moistMaxToday'][$i]	= (string) $xml -> soil_moisture_1_day_high;
        $ws['moistMaxMonth'][$i]	= (string) $xml -> soil_moisture_1_month_high;
        $ws['moistMaxYear'][$i]		= (string) $xml -> soil_moisture_1_year_high;
        $ws['moistMaxTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_1_day_high_time);

        $ws['moistMinToday'][$i]	= (string) $xml -> soil_moisture_1_day_low;
        $ws['moistMinMonth'][$i]	= (string) $xml -> soil_moisture_1_month_low;
        $ws['moistMinYear'][$i]		= (string) $xml -> soil_moisture_1_year_low;
        $ws['moistMinTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_1_day_low_time);
}       
if ($SITE['soilUsed'] && $SITE['soilCount']*1.0 > 1) {
        $i 		= 2;
# Soil sensor 1 actual value
        if (isset ($xml -> temp_soil_2) )       { $string = (string) $xml -> temp_soil_2;}
        elseif (isset ($davis -> temp_soil_2) ) { $string = (string) $davis -> temp_soil_2;}
        else                                    { $string = (string) $xml -> temp_soil_2_day_high;}   
        $ws['soilTempAct'][$i]		= wsConvertTemperature($string, 	$from);
# Soil sensor 1 max/min values and times
        $ws['soilTempMaxToday'][$i]	= wsConvertTemperature($xml -> temp_soil_2_day_high, 	$from);
        $ws['soilTempMaxMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_2_month_high, 	$from);
        $ws['soilTempMaxYear'][$i]	= wsConvertTemperature($xml -> temp_soil_2_year_high, 	$from);
        $ws['soilTempMaxTodayTime'][$i]	= wdDate((string)$xml->temp_soil_2_day_high_time);

        $ws['soilTempMinToday'][$i]	= wsConvertTemperature($xml -> temp_soil_2_day_low, 	$from);
        $ws['soilTempMinMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_2_month_low, 	$from);
        $ws['soilTempMinYear'][$i]	= wsConvertTemperature($xml -> temp_soil_2_year_low, 	$from);
        $ws['soilTempMinTodayTime'][$i]	= wdDate((string)$xml->temp_soil_2_day_low_time);

# Moisture sensor 2 actual value
        if (isset ($xml -> soil_moisture_2) )           { $string = (string) $xml -> soil_moisture_2;}
        elseif (isset ($davis ->  soil_moisture_2) )    { $string = (string) $davis -> soil_moisture_2;}
        else                                            { $string = (string) $xml -> soil_moisture_2_day_high;}   
        $ws['moistAct'][$i]		= $string;
# Moisture sensor 2 max values for today month and year alltime
        $ws['moistMaxToday'][$i]	= (string) $xml -> soil_moisture_2_day_high;
        $ws['moistMaxMonth'][$i]	= (string) $xml -> soil_moisture_2_month_high;
        $ws['moistMaxYear'][$i]		= (string) $xml -> soil_moisture_2_year_high;
        $ws['moistMaxTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_2_day_high_time);

        $ws['moistMinToday'][$i]	= (string) $xml -> soil_moisture_2_day_low;
        $ws['moistMinMonth'][$i]	= (string) $xml -> soil_moisture_2_month_low;
        $ws['moistMinYear'][$i]		= (string) $xml -> soil_moisture_2_year_low;
        $ws['moistMinTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_2_day_low_time);
}
if ($SITE['soilUsed'] && $SITE['soilCount']*1.0 > 2) {
        $i 		= 3;
# Soil sensor 1 actual value
        if (isset ($xml -> temp_soil_3) )       { $string = (string) $xml -> temp_soil_3;}
        elseif (isset ($davis -> temp_soil_3) ) { $string = (string) $davis -> temp_soil_3;}
        else                                    { $string = (string) $xml -> temp_soil_3_day_high;}   
        $ws['soilTempAct'][$i]		= wsConvertTemperature($string, 	$from);
# Soil sensor 1 max/min values and times
        $ws['soilTempMaxToday'][$i]	= wsConvertTemperature($xml -> temp_soil_3_day_high, 	$from);
        $ws['soilTempMaxMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_3_month_high, 	$from);
        $ws['soilTempMaxYear'][$i]	= wsConvertTemperature($xml -> temp_soil_3_year_high, 	$from);
        $ws['soilTempMaxTodayTime'][$i]	= wdDate((string)$xml->temp_soil_3_day_high_time);

        $ws['soilTempMinToday'][$i]	= wsConvertTemperature($xml -> temp_soil_3_day_low, 	$from);
        $ws['soilTempMinMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_3_month_low, 	$from);
        $ws['soilTempMinYear'][$i]	= wsConvertTemperature($xml -> temp_soil_3_year_low, 	$from);
        $ws['soilTempMinTodayTime'][$i]	= wdDate((string)$xml->temp_soil_3_day_low_time);

# Moisture sensor 2 actual value
        if (isset ($xml -> soil_moisture_3) )           { $string = (string) $xml -> soil_moisture_3;}
        elseif (isset ($davis ->  soil_moisture_3) )    { $string = (string) $davis -> soil_moisture_3;}
        else                                            { $string = (string) $xml -> soil_moisture_3_day_high;}   
        $ws['moistAct'][$i]		= $string;
# Moisture sensor 2 max values for today month and year alltime
        $ws['moistMaxToday'][$i]	= (string) $xml -> soil_moisture_3_day_high;
        $ws['moistMaxMonth'][$i]	= (string) $xml -> soil_moisture_3_month_high;
        $ws['moistMaxYear'][$i]		= (string) $xml -> soil_moisture_3_year_high;
        $ws['moistMaxTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_3_day_high_time);

        $ws['moistMinToday'][$i]	= (string) $xml -> soil_moisture_3_day_low;
        $ws['moistMinMonth'][$i]	= (string) $xml -> soil_moisture_3_month_low;
        $ws['moistMinYear'][$i]		= (string) $xml -> soil_moisture_3_year_low;
        $ws['moistMinTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_3_day_low_time);
}
if ($SITE['soilUsed'] && $SITE['soilCount']*1.0 > 3) {
        $i 		= 4;
# Soil sensor 1 actual value
        if (isset ($xml -> temp_soil_4) )       { $string = (string) $xml -> temp_soil_4;}
        elseif (isset ($davis -> temp_soil_4) ) { $string = (string) $davis -> temp_soil_4;}
        else                                    { $string = (string) $xml -> temp_soil_4_day_high;}   
        $ws['soilTempAct'][$i]		= wsConvertTemperature($string, 	$from);
# Soil sensor 1 max/min values and times
        $ws['soilTempMaxToday'][$i]	= wsConvertTemperature($xml -> temp_soil_4_day_high, 	$from);
        $ws['soilTempMaxMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_4_month_high, 	$from);
        $ws['soilTempMaxYear'][$i]	= wsConvertTemperature($xml -> temp_soil_4_year_high, 	$from);
        $ws['soilTempMaxTodayTime'][$i]	= wdDate((string)$xml->temp_soil_4_day_high_time);

        $ws['soilTempMinToday'][$i]	= wsConvertTemperature($xml -> temp_soil_4_day_low, 	$from);
        $ws['soilTempMinMonth'][$i]	= wsConvertTemperature($xml -> temp_soil_4_month_low, 	$from);
        $ws['soilTempMinYear'][$i]	= wsConvertTemperature($xml -> temp_soil_4_year_low, 	$from);
        $ws['soilTempMinTodayTime'][$i]	= wdDate((string)$xml->temp_soil_4_day_low_time);

# Moisture sensor 2 actual value
        if (isset ($xml -> soil_moisture_4) )           { $string = (string) $xml -> soil_moisture_4;}
        elseif (isset ($davis ->  soil_moisture_4) )    { $string = (string) $davis -> soil_moisture_4;}
        else                                            { $string = (string) $xml -> soil_moisture_4_day_high;}   
        $ws['moistAct'][$i]		= $string;
# Moisture sensor 2 max values for today month and year alltime
        $ws['moistMaxToday'][$i]	= (string) $xml -> soil_moisture_4_day_high;
        $ws['moistMaxMonth'][$i]	= (string) $xml -> soil_moisture_4_month_high;
        $ws['moistMaxYear'][$i]		= (string) $xml -> soil_moisture_4_year_high;
        $ws['moistMaxTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_4_day_high_time);

        $ws['moistMinToday'][$i]	= (string) $xml -> soil_moisture_4_day_low;
        $ws['moistMinMonth'][$i]	= (string) $xml -> soil_moisture_4_month_low;
        $ws['moistMinYear'][$i]		= (string) $xml -> soil_moisture_4_year_low;
        $ws['moistMinTodayTime'][$i]	= wdDate((string)$xml->soil_moisture_4_day_low_time);
}
if ($SITE['leafUsed'] && $SITE['leafCount']*1.0 > 0)  {
# leaf sensor 1
        $n = 1;
        if (isset ($xml -> temp_leaf_1) )       { $string = (string) $xml -> temp_leaf_1;}
        elseif (isset ($davis -> temp_leaf_1) ) { $string = (string) $davis -> temp_leaf_1;}
        else                                    { $string = (string) $xml -> temp_leaf_1_day_high;}   
        $ws['leafTempAct'][$n]	        = $string;

        if (isset ($xml -> leaf_wetness_1) )            { $string = (string) $xml -> leaf_wetness_1;}
        elseif (isset ($davis -> leaf_wetness_1) )      { $string = (string) $davis -> leaf_wetness_1;}
        else                                            { $string = (string) $xml -> leaf_wetness_1_day_high;}   
        $ws['leafWetAct'][$n]		= $string;
        $ws['leafWetMaxToday'][$n]	= (string) $xml -> leaf_wetness_1_day_high;
        $ws['leafWetMaxMonth'][$n]	= (string) $xml -> leaf_wetness_1_month_high;
        $ws['leafWetMaxYear'][$n]	= (string) $xml -> leaf_wetness_1_year_high;
        $ws['leafWetMaxTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_1_day_high_time);

        $ws['leafWetMinToday'][$n]	= (string) $xml -> leaf_wetness_1_day_low;
        $ws['leafWetMinMonth'][$n]	= (string) $xml -> leaf_wetness_1_month_low;
        $ws['leafWetMinYear'][$n]	= (string) $xml -> leaf_wetness_1_year_low;
        $ws['leafWetMinTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_1_day_low_time);
}
if ($SITE['leafUsed'] && $SITE['leafCount']*1.0 > 1) {
# leaf sensor 2
        $n = 2;
        if (isset ($xml -> temp_leaf_2) )       { $string = (string) $xml -> temp_leaf_2;}
        elseif (isset ($davis -> temp_leaf_2) ) { $string = (string) $davis -> temp_leaf_2;}
        else                                    { $string = (string) $xml -> temp_leaf_2_day_high;}   
        $ws['leafTempAct'][$n]	        = $string;

        if (isset ($xml -> leaf_wetness_2) )            { $string = (string) $xml -> leaf_wetness_2;}
        elseif (isset ($davis -> leaf_wetness_2) )      { $string = (string) $davis -> leaf_wetness_2;}
        else                                            { $string = (string) $xml -> leaf_wetness_2_day_high;}   
        $ws['leafWetAct'][$n]		= $string;
        $ws['leafWetMaxToday'][$n]	= (string) $xml -> leaf_wetness_2_day_high;
        $ws['leafWetMaxMonth'][$n]	= (string) $xml -> leaf_wetness_2_month_high;
        $ws['leafWetMaxYear'][$n]	= (string) $xml -> leaf_wetness_2_year_high;
        $ws['leafWetMaxTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_2_day_high_time);

        $ws['leafWetMinToday'][$n]	= (string) $xml -> leaf_wetness_2_day_low;
        $ws['leafWetMinMonth'][$n]	= (string) $xml -> leaf_wetness_2_month_low;
        $ws['leafWetMinYear'][$n]	= (string) $xml -> leaf_wetness_2_year_low;
        $ws['leafWetMinTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_2_day_low_time);
}
if ($SITE['leafUsed'] && $SITE['leafCount']*1.0 > 2) {
# leaf sensor 3
        $n = 3;
        if (isset ($xml -> temp_leaf_3) )       { $string = (string) $xml -> temp_leaf_3;}
        elseif (isset ($davis -> temp_leaf_3) ) { $string = (string) $davis -> temp_leaf_3;}
        else                                    { $string = (string) $xml -> temp_leaf_3_day_high;}   
        $ws['leafTempAct'][$n]	        = $string;

        if (isset ($xml -> leaf_wetness_3) )            { $string = (string) $xml -> leaf_wetness_3;}
        elseif (isset ($davis -> leaf_wetness_3) )      { $string = (string) $davis -> leaf_wetness_3;}
        else                                            { $string = (string) $xml -> leaf_wetness_3_day_high;}   
        $ws['leafWetAct'][$n]		= $string;
        $ws['leafWetMaxToday'][$n]	= (string) $xml -> leaf_wetness_3_day_high;
        $ws['leafWetMaxMonth'][$n]	= (string) $xml -> leaf_wetness_3_month_high;
        $ws['leafWetMaxYear'][$n]	= (string) $xml -> leaf_wetness_3_year_high;
        $ws['leafWetMaxTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_3_day_high_time);

        $ws['leafWetMinToday'][$n]	= (string) $xml -> leaf_wetness_3_day_low;
        $ws['leafWetMinMonth'][$n]	= (string) $xml -> leaf_wetness_3_month_low;
        $ws['leafWetMinYear'][$n]	= (string) $xml -> leaf_wetness_3_year_low;
        $ws['leafWetMinTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_3_day_low_time);
}
if ($SITE['leafUsed'] && $SITE['leafCount']*1.0 > 3) {
# leaf sensor 4
        $n = 4;
        if (isset ($xml -> temp_leaf_4) )       { $string = (string) $xml -> temp_leaf_4;}
        elseif (isset ($davis -> temp_leaf_4) ) { $string = (string) $davis -> temp_leaf_4;}
        else                                    { $string = (string) $xml -> temp_leaf_4_day_high;}   
        $ws['leafTempAct'][$n]	        = $string;

        if (isset ($xml -> leaf_wetness_4) )            { $string = (string) $xml -> leaf_wetness_4;}
        elseif (isset ($davis -> leaf_wetness_4) )      { $string = (string) $davis -> leaf_wetness_4;}
        else                                            { $string = (string) $xml -> leaf_wetness_4_day_high;}   
        $ws['leafWetAct'][$n]		= $string;
        $ws['leafWetMaxToday'][$n]	= (string) $xml -> leaf_wetness_4_day_high;
        $ws['leafWetMaxMonth'][$n]	= (string) $xml -> leaf_wetness_4_month_high;
        $ws['leafWetMaxYear'][$n]	= (string) $xml -> leaf_wetness_4_year_high;
        $ws['leafWetMaxTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_4_day_high_time);

        $ws['leafWetMinToday'][$n]	= (string) $xml -> leaf_wetness_4_day_low;
        $ws['leafWetMinMonth'][$n]	= (string) $xml -> leaf_wetness_4_month_low;
        $ws['leafWetMinYear'][$n]	= (string) $xml -> leaf_wetness_4_year_low;
        $ws['leafWetMinTodayTime'][$n]	= wdDate((string)$xml->leaf_wetness_4_day_low_time);
}
#echo '<pre>'.PHP_EOL; print_r ($ws);  exit;
#---------------------------------------------------------------------------
# retrieve missing moon entries
$skipMoonPage = true; include ($SITE['moonSet']); $skipMoonPage = false;
#
$ws['check_ok']         = '0.00';
#
$ws['tempMinYday'] = $ws['tempMaxYday'] = $ws['dewpMinYday'] = $ws['dewpMaxYday'] = $ws['chilMinYday'] = $ws['heatMaxYday'] = '';
$ws['baroMinYday'] = $ws['baroMaxYday'] = '';
$ws['rainRateYday']= $ws['rainYday']    = $ws['etYday']      = '';
$ws['gustMaxYday'] = '';
$ws['humiMinYday'] = $ws['humiMaxYday'] = '';
$ws['uvMaxYday']   = $ws['solarMaxYday']= '';

$ws['tempMinYdayTime']  = $ws['tempMaxYdayTime'] = $ws['dewpMinYdayTime']  = $ws['dewpMaxYdayTime']  = ''; 
$ws['chilMinYdayTime']  = $ws['heatMaxYdayTime'] = $ws['baroMinYdayTime']  = $ws['baroMaxYdayTime']  = ''; 
$ws['humiMinYdayTime']  = $ws['humiMaxYdayTime'] = $ws['gustMaxYdayTime']  = ''; 
$ws['uvMaxYdayTime']    = $ws['solarMaxYdayTime']= ''; 
#
if (!isset ($SITE['ydayTags']) || trim($SITE['ydayTags']) == '') {
        echo $startEcho.$tagsWLCOM.": no yesterday values present - generating placeholders ".$endEcho.PHP_EOL;
} 
else {  echo $startEcho.$tagsWLCOM.": yesterday values loaded from ".$SITE['ydayTags'].$endEcho.PHP_EOL;
        $arr    = file($SITE['ydayTags'],FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $end    = count ($arr);
        for ($n = 0; $n < $end; $n++) {
                $line   = trim ($arr[$n]);
                if ($line  == '' ) {continue;}
                $substr = substr($line,1,5);
                if ($substr  == '-----')  {continue;}
                if (substr($line,0,1) <> '|') {continue;}
                list ($skip,$name, $content) = explode ('|',$line.'|');
                $name   = trim($name);
                $content= trim($content);
                if ($content  == '' ) {
                        echo $startEcho.$tagsWLCOM.'('.__LINE__.'):empty line '.$n.' name '.$name .$endEcho.PHP_EOL; continue;}
                $wx[$name]=$content;
        }
        $ws['tags_yday']	.= ' - '.$wx['pagenameYday'].' - '.$wx['versionYday'];
        $ws['tags_yday_time']	=                        $wx['datetimeYday'];
        $fromYday               =                        $wx['fromtempYday'];
        $ws['tempMinYday']      = wsConvertTemperature  ($wx['tempMinYday'],$fromYday);
        $ws['tempMinYdayTime']  =                        $wx['tempMinYdayTime'];   
        $ws['tempMaxYday']      = wsConvertTemperature  ($wx['tempMaxYday'],$fromYday);
        $ws['tempMaxYdayTime']  =                        $wx['tempMaxYdayTime'];
        $ws['dewpMinYday']      = wsConvertTemperature  ($wx['dewpMinYday'],$fromYday);
        $ws['dewpMinYdayTime']  =                        $wx['dewpMinYdayTime'];   
        $ws['dewpMaxYday']      = wsConvertTemperature  ($wx['dewpMaxYday'],$fromYday);
        $ws['dewpMaxYdayTime']  =                        $wx['dewpMaxYdayTime'];
        $ws['chilMinYday']      = wsConvertTemperature  ($wx['chilMinYday'],$fromYday);
        $ws['chilMinYdayTime']  =                        $wx['chilMinYdayTime'];
        $ws['heatMaxYday']      = wsConvertTemperature  ($wx['heatMaxYday'],$fromYday);
        $ws['heatMaxYdayTime']  =                        $wx['heatMaxYdayTime'];

        $fromYday               = $wx['frombaroYday'];
        $ws['baroMinYday']      = wsConvertBaro         ($wx['baroMinYday'],$fromYday);
        $ws['baroMinYdayTime']  =                        $wx['baroMinYdayTime'];
        $ws['baroMaxYday']      = wsConvertBaro         ($wx['baroMaxYday'],$fromYday);
        $ws['baroMaxYdayTime']  =                        $wx['baroMaxYdayTime'];
       
        $ws['humiMinYday']      =                        $wx['humiMinYday'];
        $ws['humiMinYdayTime']  =                        $wx['humiMinYdayTime'];
        $ws['humiMaxYday']      =                        $wx['humiMaxYday'];
        $ws['humiMaxYdayTime']  =                        $wx['humiMaxYdayTime'];
       
        $fromYday               = $wx['fromrainYday'];
        $ws['rainRateYday']     = wsConvertRainfall     ($wx['rainRateYday'],$fromYday);
        $ws['rainYday']         = wsConvertRainfall     ($wx['rainYday'],$fromYday);
        $ws['etYday']           = wsConvertRainfall     ($wx['etYday'],$fromYday);
        $fromYday               = $wx['fromwindYday'];
        $ws['gustMaxYday']      = wsConvertWindspeed    ($wx['gustMaxYday'],$fromYday);
        $ws['gustMaxYdayTime']  =                        $wx['gustMaxYdayTime'];
        
        $ws['uvMaxYday']        =                        $wx['uvMaxYday'];
        $ws['uvMaxYdayTime']    =                        $wx['uvMaxYdayTime'];
        $ws['solarMaxYday']     =                        $wx['solarMaxYday'];
        $ws['solarMaxYdayTime'] =                        $wx['solarMaxYdayTime'];
        
        $fromYday               = $wx['fromtempYday'];        
        if (isset ($SITE['soilUsed']) && $SITE['soilUsed'] && $SITE['soilCount']*1.0 > 0) {
                $soils           = round($SITE['soilCount']);}
        else {  $soils           = 0;}
        if ($soils > 4) {echo '<!-- reset nr of soil sensors from '.$SITE['soilCount'].' to max 4 -->'.PHP_EOL;$soils  = 4;}
        for  ($n = 1; $n <= $soils; $n++) {
                $ws['soilTempMaxYday'][$n]      = wsConvertTemperature  ($wx['soilTempMaxYday_'.$n],$fromYday);
                $ws['soilTempMaxYdayTime'][$n]  =                        $wx['soilTempMaxYdayTime_'.$n];
                $ws['soilTempMinYday'][$n]      = wsConvertTemperature  ($wx['soilTempMinYday_'.$n],$fromYday);
                $ws['soilTempMinYdayTime'][$n]  =                        $wx['soilTempMinYdayTime_'.$n];
                $ws['moistMaxYday'][$n]         = $wx['moistMaxYday_'.$n];
                $ws['moistMaxYdayTime'][$n]     = $wx['moistMaxYdayTime_'.$n];
                $ws['moistMinYday'][$n]         = $wx['moistMinYday_'.$n];
                $ws['moistMinYdayTime'][$n]     = $wx['moistMinYdayTime_'.$n];
        }
        $leafs = round($SITE['leafCount']);
        if ($leafs > 4) { echo '<!-- reset nr of leaf sensors from '.$SITE['leafCount'].' to max 4 -->'.PHP_EOL;$leafs  = 4;}        
        for  ($n = 1; $n <= $leafs; $n++) {
                $ws['leafWetMaxYday'][$n]       = $wx['leafWetMaxYday_'.$n];
                $ws['leafWetMaxYdayTime'][$n]   = $wx['leafWetMaxYdayTime_'.$n];
                $ws['leafWetMinYday'][$n]       = $wx['leafWetMinYday_'.$n];
                $ws['leafWetMinYdayTime'][$n]   = $wx['leafWetMinYdayTime_'.$n];
        }
}
#
#echo '<pre>'; print_r ($ws); exit;
if (!file_put_contents($cachefileWLC, serialize($ws))){   
        echo $startEcho.$tagsWLCOM.": <br />Could not save (".$cachefileWLC.") to cache. Please make sure your cache directory exists and is writable.".$endEcho.PHP_EOL;
} else {
        echo $startEcho.$tagsWLCOM.": $cachefileWLC saved to cache".$endEcho.PHP_EOL;
}
// end of tagsWLCOM.php
#echo '<pre>'.PHP_EOL; print_r ($ws);  exit;