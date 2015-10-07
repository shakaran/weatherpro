<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsIconUrl.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-27-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$arrLookupNoaa = array (		// known noaa icon codes to default icon translation array
'bkn'	=>	'300', 	'nbkn'	=>	'300n',	'nbknfg'=>	'252n',	'few'	=>	'100',	'nfew'	=>	'100n',
'cloudy'=>	'300', 	'ncloudy'=>	'300n',	'fg'	=>	'252',	'nfg'	=>	'252n',	'hazy'	=>	'150', 
'hi_tsra'=>	'342',	'hi_ntsra'=>	'342',	'ip'	=>	'232',	'nip'	=>  	'232n',	'mist'	=>	'250',
'ovc'	=>	'400',	'novc'	=>	'400n',	'ra'	=>	'211',	'nra'	=>	'211n',	'rasn'	=>	'231',
'nrasn'	=>	'231n',	'ra1'	=>	'901',	'skc'	=>	'100', 	'nskc'	=>	'100n', 'sn'	=>	'121',
'nsn'	=>	'121n',	'sct'	=>	'200',	'nsct'	=>	'200n',	'sctfg'	=>	'251',	'scttsra'=>	'141',
'nscttsra'=>	'141n','nsvrtsra'=>	'342n', 'tsra'	=>	'241',	'ntsra'	=>	'241n',	'shra'	=>	'312',
'nshra'	=>	'312n', 'wind'	=>	'600', 	'nwind' =>	'600','windyrain' =>	'600', 	'mix' 	=>	'432', 
'nmix'	=>	'432n',	'cold'	=>	'700',	'hot'	=>  	'701',	'fzra'	=>  	'231',	'nfzra' => 	'231n',
'hi_shwrs' =>'	111',	'hi_nshwrs'=>	'111n','raip'	=>	'231',	'nraip'=> 	'231n',
);
$arrLookupYA = array (		// yahoo icon to default icon translation array
'0' =>	'600',	'1' =>	'600',	'2' =>	'600',	'3' =>	'442',	'4' =>	'442',
'5' =>	'432',	'6' =>	'431',	'7' =>	'431',	'8' =>	'430',	'9' =>	'410',
'10' =>	'431',	'11' =>	'411',	'12' =>	'411',	'13' =>	'420',	'14' =>	'420',
'15' =>	'421',	'16' =>	'421',	'17' =>	'430',	'18' =>	'431',	'19' =>	'901',
'20' =>	'451',	'21' =>	'450',	'22' =>	'901',	'23' =>	'600',	'24' =>	'600',
'25' =>	'700',	'26' =>	'400',	'27' =>	'300n',	'28' =>	'300',	'29' =>	'200n',
'30' =>	'200',	'31' =>	'000n',	'32' =>	'000',	'33' =>	'100n','34' =>	'100',
'35' =>	'431',	'36' =>	'701',	'37' =>	'440',	'38' =>	'440',	'39' =>	'411',
'40' =>	'421',	'41' =>	'422',	'42' =>	'221',	'43' =>	'422',	'44' =>	'200',
'45' =>	'442',	'46' =>	'431',	'47' =>	'440',	'3200'=> '901'
);
$arrLookupHwa = array (			// hwa icon to default icon translation array
'0' =>	'901',	'1' =>	'000',	'2' =>	'200',	'3' =>	'230',	'4' =>	'212',	'5' =>	'210',
'6' =>	'230',	'7' =>	'141',	'8' =>	'141',	'9' =>	'242',	'10' =>	'130',	'11' =>	'400',
'12' =>	'420',	'13' =>	'411',	'14' =>	'410',	'15' =>	'421',	'16' =>	'421',	'17' =>	'440',
'18' =>	'441',	'19' =>	'441',	'20' =>	'440',	'21' =>	'421',	'22' =>	'452',	'23' =>	'000n',
'24' =>	'100n',	'25' =>	'210n',	'26' =>	'212n',	'27' =>	'211n',	'28' =>	'231n',	'29' =>	'241n',
'30' =>	'241n',	'31' =>	'242n',	'32' =>	'242n',	'33' =>	'400n',	'34' =>	'431n',	'35' =>	'412',
'36' =>	'411',	'37' =>	'431',	'38' =>	'440',	'39' =>	'241',	'40' =>	'441',	'41' =>	'440',
'42' =>	'100',	'43' =>	'100',	'44' =>	'100n',	'45' =>	'200n',	'46' =>	'422',	'47' =>	'130',
'48' =>	'452',	'49' =>	'452n',	'50' =>	'231',	'51' =>	'231n',	'52' =>	'331',	'53' =>	'331n',
'54' =>	'231',	'55' =>	'231n',	'56' =>	'221',	'57' =>	'221n',	'58' =>	'800',	'59' =>	'600', '60' =>	'800'			
);
$arrLookupWorld = array (		// world weather icon to default icon translation array
'113' => '000',	'116' => '200',	'119' => '300',	'122' => '400',	'143' => '150',	'176' => '110',	'179' => '120',
'182' => '432',	'185' => '432',	'200' => '241',	'227' => '422',	'230' => '900',	'248' => '352',	'260' => '432',
'263' => '110',	'266' => '110',	'281' => '432',	'284' => '432',	'293' => '110',	'296' => '110',	'299' => '211',
'302' => '211',	'305' => '412',	'308' => '412',	'311' => '432',	'314' => '432',	'317' => '432',	'320' => '432',
'323' => '120',	'326' => '120',	'329' => '221',	'332' => '322',	'335' => '322',	'338' => '422',	'350' => '432',
'353' => '110',	'356' => '211',	'359' => '412',	'362' => '432',	'365' => '432',	'368' => '120',	'371' => '221',
'374' => '432',	'377' => '432',	'386' => '241', '389' => '342', '392' => '120', '395' => '221',
);
$arrLookupWU = array(		// weather underground to default icon translation array
'chanceflurries'	=>'432',	'chancerain'		=>'110',	'chancesleet'	=>'432',	'chancesnow'	=>'120',			
'chancetstorms'		=>'241',	'clear'			=>'000',	'cloudy' 	=>'400',	'flurries'		=>'432',
'fog' 			=>'352',	'hazy' 			=>'150',	'mostlycloudy' 	=>'300',	'mostlysunny'	=>'100',
'nt_chanceflurries'	=>'432',	'nt_chancerain'		=>'110n',	'nt_chancesleet'=>'432',	'nt_chancesnow'	=>'120n',
'nt_chancetstorms'	=>'241n',	'nt_clear'		=>'000n',	'nt_cloudy'	=>'400n',	'nt_flurries'	=>'432',	
'nt_fog'		=>'352n',	'nt_hazy'		=>'150n',	'nt_mostlycloudy'=>'300n',	'nt_mostlysunny'=>'100n',
'nt_partlycloudy'	=>'200n', 	'nt_partlysunny'	=>'300n',	'nt_rain'	=>'211n',	'nt_sleet'		=>'432',
'nt_snow'		=>'422n',	'nt_sunny'		=>'000n',	'nt_tstorms'	=>'241n',	'partlycloudy'	=>'200',
'partlysunny'		=>'300',	'rain'			=>'211',	'sleet'		=>'432',	'snow'			=>'422',	
'sunny'			=>'000',	'tstorms'		=>'342',	'unknown'	=>'901',	'nt_unknown'	=>'901',
);
$arrLookupYrno = array(		//  YrNo icon to default icon translation array
'01d'=>'000',	'01n'=>'000n',	'02d'=>'100',	'02n'=>'100n',	'03d'=>'300',	'03n'=>'300n',
'04' =>'400',	'04d'=>'400',	'04n'=>'400n',	'05d'=>'211',	'05n'=>'211n',	'06d'=>'241',
'06n'=>'241n',	'07d'=>'231',	'07n'=>'231n',	'08d'=>'221',	'08n'=>'221n',	'09' =>'411',
'09d'=>'211',	'09n'=>'211n',	'10' =>'412',	'10d'=>'212',	'10n'=>'212n',	'11' =>'441',
'11d'=>'241',	'11n'=>'241n',	'12' =>'432',	'12d'=>'232',	'12n'=>'232n',	'13' =>'422',
'13d'=>'222',	'13n'=>'222n',	'14' =>'442',	'14d'=>'242',	'14n'=>'242n',	'15' =>'452',
'15d'=>'252',	'15n'=>'252n',
);
$arrLookupWd = array(		//  WeatherDisplay icon to default icon translation array
0  => '000',	1  => '000n',	2 => '100',	3  => '100',	4  => '200n',	5  => '100',
6  => '352',	7  => '352',	8  => '211',	9  => '100',	10 => '352',	11 => '352n',
12 => '211n',	13 => '400n',	14 => '211n',	15 => '110n',	16 => '221',	17 => '342n',	
18 => '400',	19 => '200',	20 => '110',	21 => '211',	22 => '110',	23 => '432',
24 => '432',	25 => '120',	26 => '120',	27 => '422',	28 => '000',	29 => '442',
30 => '442',	31 => '442',	32 => '442',	33 => '600',	34 => '100',	35 => '110',
);
$arrLookupKDE = array(		//  KDE icons to default icon translation array
'cloudy1'	=> '100',	'cloudy1_night'	=> '100n',	'cloudy2'	=> '200',	'cloudy2_night'	=> '200n',
'cloudy3'	=> '200',	'cloudy3_night'	=> '200n',	'cloudy4'	=> '300',	'cloudy4_night'	=> '300n',
'cloudy5'	=> '400',	'dunno'		=> '901',	'fog'		=> '352',	'fog_night'	=> '352n',
'hail'		=> '432',	'light_rain'	=> '410',	'mist'		=> '150',	'mist_night'	=> '150n',
'overcast'	=> '400n',
'shower1'	=> '110',	'shower1_night'	=> '110n',	'shower2'	=> '211',	'shower2_night'	=> '211n',
'shower3'	=> '412',	'sleet'		=> '432',
'snow1'		=> '120',	'snow1_night'	=> '120n',	'snow2'		=> '221',	'snow2_night'	=> '221n',
'snow3'		=> '322',	'snow4'		=> '422',	'snow5'		=> '422n',	
'sunny'		=> '000',	'sunny_night'	=> '000n',
'tstorm1'	=> '241',	'tstorm1_night'	=> '241n',	'tstorm2'	=> '342',	'tstorm2_night'	=> '342n',
'tstorm3'	=> '442',
);
$arrLookupEC = array(
'000',	'100',	'200',	'300',	'200',	'200',	'110',	'130',	'121',	'142',
'400n',	'410',	'411',	'412',	'431',	'432',	'420',	'421',	'422',	'440',
'200',	'200',	'200',	'150',	'450',	'420',	'000n',	'432',	'411',	'901',
'000n',	'100n',	'200n',	'300n',	'200n',	'200n',	'110n',	'130n',	'121n',	'142n',
'700',	'900',	'900',	'600',	'900',	'900',	'900',	'900',	'900',	'900'
);


function wsHeaderLookup ($provider,$iconIn) {
	global	$SITE,			$wsDebug,
			$arrLookupYA,	$arrLookupWd,	$arrLookupNoaa;
	# detect what background should be used based on this icon / weathercondition
	switch ($provider) {
		case ('wd'):
			$iconOwn = $arrLookupWd[$iconIn];
		break;
		case ('yahoo'):
			$iconOwn = $arrLookupYA[$iconIn];
		break;
		case ('default'):
			$iconOwn = (string) $iconIn;
		break;		
	}
	$iconClean = str_replace ('n','',$iconOwn);
	if ($iconClean == $iconOwn) {$day = true; } else {$day = false; }
	$figure1	= floor($iconClean /100);
	$rest		= $iconClean - 100 * $figure1;
	$figure2	= floor($rest /10);
#'clouds','cloudsn','mist','moon','pclouds','rain','snow','storm','sun','thunder');

	switch (true) {
		case ( $iconOwn == '000' ):
			$headerClass = 'ws_sun';
			break;
		case ( $iconOwn == '000n' ):
			$headerClass = 'ws_moon';
			break;
		case ( $iconClean == '100' || $iconClean == '200'):
			$headerClass = 'ws_pclouds';
			break;
		case ( $iconClean == '300' || $iconClean == '400'):
			$headerClass = 'ws_clouds';
			if (!$day) {$headerClass = $headerClass.'n';}
			break;
		case ($figure2 == 1):
			$headerClass = 'ws_rain';
			break;
		case ($figure2 == 2 || $figure2 == 3):
			$headerClass = 'ws_snow';	
			break;
		case ($figure2 == 4):
			$headerClass = 'ws_thunder';
			break;
		case ($figure2 == 5):
			$headerClass = 'ws_mist';
			break;
		case ( $iconOwn == '600' ):
			$headerClass = 'ws_storm';
			break;
		default:
			$headerClass = 'ws_clouds';			
	
	}
	ws_message ('<!-- module wsIconUrl.php ('.__LINE__.') function wsHeaderLookup: $provider ='.$provider.' $iconIn ='.$iconIn.' Out = $iconOwn = '.$iconOwn.' $headerClass = '.$headerClass.' -->');
	return $headerClass;
}  // eof wsHeaderLookup
#
function wsChangeIcon ($provider,$iconIn, &$iconOut, $iconUrlIn, &$iconUrlOut, $headerClass='') {
	global	$SITE,			$wsDebug,		$pageFile,
			$arrLookupWU,	$arrLookupYrno,	$arrLookupYA,	$arrLookupWorld,	$arrLookupHwa,
			$arrLookupWd,	$arrLookupNoaa,	$arrLookupKDE,	$arrIconsWXSIM,		$arrLookupEC;
#	default no icon change
	$iconOut				= $iconIn;
	$iconUrlOut				= $iconUrlIn;
#	if we do not find the icon in the icon set we output a dunno icon
	$iconOwn 				= 'dunno';
#
	switch ($provider) {
		case 'default':
			$iconOwn 		= $iconIn;
			$iconUrlOut		= $SITE['defaultIconsDir'].$iconIn.'.png';
		break;
		case 'hwa':
			if ( isset ($arrLookupHwa[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn	= $arrLookupHwa[$iconIn];
			}
			if (isset($SITE['hwaIconsOwn']) && $SITE['hwaIconsOwn'] == false)	{
				// use hwa icons from cache (true) or our template icons (false)
				$iconOut 	= $iconOwn;
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'yahoo':
			if ( isset ($arrLookupYA[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn = $arrLookupYA[$iconIn];
			}
			if ( isset($SITE['yahooIconsOwn']) 	&& $SITE['yahooIconsOwn'] 	== false ) {
				// use yahoo icons (true) or our template icons (false)
				$iconOut	= $iconOwn;
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'wu':
			if ( isset ($arrLookupWU[$iconIn]) )  { 
				// do we find the specified icon in our table for this iconset
				$iconOwn	= $arrLookupWU[$iconIn];
			} 
			if ((isset($SITE['wuIconsCache'])) 	&& ($SITE['wuIconsCache']	== true))	{
				// use wu icons from our cache (true) or our wu icons from wu url (false)	
				$iconUrlOut	= $SITE['wuIconsDir'].$iconIn.'.gif';
			}
			if ((isset($SITE['wuIconsOwn'])) 	&& ($SITE['wuIconsOwn'] 	== false)) { 
				// use wu icons (true) or our template icons (false)	
				$iconOut 	= $iconOwn; 
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'yrno':
			if ( isset ($arrLookupYrno[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn	= $arrLookupYrno[$iconIn];
			}
			if ((isset($SITE['yrnoIconsOwn'])) 	&& ($SITE['yrnoIconsOwn'] 	== false))	{
				// use yrno icons (true) or our template icons (false)
				$iconOut	= $iconOwn;
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOwn.'.png';
			} else {
				$iconUrlOut	= $SITE['yrnoIconsDir'].$iconOut.'.png';}
			break;
		case 'wxsim':
			if (isset($SITE['wxsimIconsOwn']) && ($SITE['wxsimIconsOwn'] 	== true))	{
				$iconOwn	= $iconOut 	= $arrIconsWXSIM[$iconIn];
				$iconUrlOut	= $SITE['wxsimIconsDir'].$iconOut.'.png';			
			} else {
				$iconOwn	= $iconOut 	= $iconIn;
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOut.'.png';				
			}
		break;
		case 'world':
			if ( isset ($arrLookupWorld[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn	= $arrLookupWorld[$iconIn];
			}
			if ( isset($SITE['worldIconsOwn']) 	&& $SITE['worldIconsOwn'] 	== false )	{
				// use worldweather icons (true) or our template icons (false)
				$iconOut	= $iconOwn;
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'wd':
			if ( isset ($arrLookupWd[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn = $arrLookupWd[$iconIn];
			}
			// we do not use the wd icons for output as we could not find the correct icons
			$iconOut = $iconOwn;  
			$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png';			
			break;
		case 'noaa':
			if ( isset ($arrLookupNoaa[$iconIn]) ) {
				$iconOwn = $arrLookupNoaa[$iconIn];
			} else {$iconOwn = '901';}
			$iconOut = $iconOwn;
			$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png';	
			break;
		case 'ec':
			if (isset($SITE['ecIconsOwn']) && ($SITE['ecIconsOwn'] 	== true))	{
				$iconOwn	= $iconOut 	= $iconIn;
				$iconUrlOut	= 'canada/ec-icons/'.$iconOut.'.gif';			
			} else {
				$iconOwn	= $iconOut 	= $arrLookupEC[(int)$iconIn];
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOut.'.png';				
			}
			break;
# kde is only used on icons page, not in other scripts		
		case 'kde':		
			if ( isset ($arrLookupKDE[$iconIn]) )   {
				$iconOwn = $arrLookupKDE[$iconIn];
			}
			$iconOut = $iconOwn;
			$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png';
			break;
	}  // eo switch
#
	if ($iconOwn == 'dunno' || $wsDebug == true) {	// if we did not find the icon we echo debug-type information
		if ($iconOwn == 'dunno') {$message = 'WARNING - icon unknown';} else {$message = '';}
		ws_message ( '<!-- module wsIconUrl.php ('.__LINE__.') function wsChangeIcon: '.$message.' '.$provider.'  -  '.$iconIn.'  -  '.$iconOut.'  -  '.$iconUrlIn.'  -  '.$iconUrlOut.'  - '. $headerClass.'     -->',true);
	}
#
}  // eo function
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
