<?php 
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'wsFieldssArr.php';
$pageVersion	= '2.30 2013-09-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 2.30 2013-09-09 release version 
#-----------------------------------------------------------------------------------------
#
$lrFieldsArr = Array ();
$lrFieldsArr['%RH']		= $csvFieldsArr['Rel.Hum.'] 				='Relative humidity';
$lrFieldsArr['15M']		= $csvFieldsArr['15 m'] 					='Temperature 15 m (50 ft)';
$lrFieldsArr['850T']	= $csvFieldsArr['T_850 mb'] 				='Temperature 850mb';
$lrFieldsArr['AIR']		= $csvFieldsArr['Temperature'] 				='Temperature outside';
$lrFieldsArr['BI']		= $csvFieldsArr['xx'] 						='Stability Boyden index';
$lrFieldsArr['BLYR']	= $csvFieldsArr['xx'] 						='Temperature boundary layer';
$lrFieldsArr['D.AD']	= $csvFieldsArr['xx'] 						='Dew point advection';
$lrFieldsArr['DEW']		= $csvFieldsArr['Dew Pt.'] 					='Dew pt';
$lrFieldsArr['ET0S']	= $csvFieldsArr['ET0 Short Reference Crop'] ='ET0 Short Reference Crop';
$lrFieldsArr['ET0L']	= $csvFieldsArr['ET0 Long Reference Crop'] 	='ET0 Long Reference Crop';
$lrFieldsArr['ETAC']	= $csvFieldsArr['Actual Evapotransipration']='Evapotransipration Actual';
$lrFieldsArr['ETST']	= $csvFieldsArr['Total ET0 Short Reference']='ET0 Short Reference Total';
$lrFieldsArr['ETLT']	= $csvFieldsArr['Total ET0 Long Reference'] ='ET0 Long Reference Total';
$lrFieldsArr['ETAT']	= $csvFieldsArr['Total Actual Evapotranspiration'] ='Evapotranspiration Actual Total';
$lrFieldsArr['G1MN']	= $csvFieldsArr['1 min Gust'] 				='Gust 1 minute';
$lrFieldsArr['G10M']	= $csvFieldsArr['10 min Gust'] 				='Gust 10 minutes';
$lrFieldsArr['G1HR']	= $csvFieldsArr['1 hr Gust'] 				='Gust 1 hour';
$lrFieldsArr['G6HR']	= $csvFieldsArr['6 hr Gust'] 				='Gust 6 hour';
$lrFieldsArr['GRS']		= $csvFieldsArr['Grass Temperature'] 		='Temperature grass level';
$lrFieldsArr['HCUP']	= $csvFieldsArr['Heat Conducted Up to Surface']='Heat Conducted Up to Surface';
$lrFieldsArr['HILL']	= $csvFieldsArr['Hill Tmp'] 				='Temperature Hill';
$lrFieldsArr['HMDX']	= $csvFieldsArr['xx'] 						='Humidex';
$lrFieldsArr['HSEN']	= $csvFieldsArr['Sensible Heat'] 			='Heat Sensible';
$lrFieldsArr['HLAT']	= $csvFieldsArr['Latent Heat'] 				='Heat Latent';
$lrFieldsArr['HT.I']	= $csvFieldsArr['Heat Ind'] 				='Heat index';
$lrFieldsArr['INT']		= $csvFieldsArr['xx'] 						= 'INT';
$lrFieldsArr['I.RT']	= $csvFieldsArr['xx'] 						='Irrigation rate';
$lrFieldsArr['ITOT']	= $csvFieldsArr['Total Irrigation'] 		='Irrigation total';
$lrFieldsArr['IRDN']	= $csvFieldsArr['IR Down'] 					='IR Down';
$lrFieldsArr['IRUP']	= $csvFieldsArr['IR Up'] 					='IR Up';
$lrFieldsArr['KI']		= $csvFieldsArr['xx'] 						='Stability K index';
$lrFieldsArr['KOI']		= $csvFieldsArr['xx'] 						='Stability KO index';
$lrFieldsArr['LCL']		= $csvFieldsArr['xx'] 						='Lifting Cond. Level';
$lrFieldsArr['L.CD']	= $csvFieldsArr['Sky Cov'] 					='Lower cloud cover (level 1/2)';
$lrFieldsArr['LI']		= $csvFieldsArr['LI'] 						='Stability Lifted index';
$lrFieldsArr['LVL1']	= $csvFieldsArr['T_Lvl 1'] 					='Temperature level 1';
$lrFieldsArr['MSO1']	= $csvFieldsArr['Soil Moisture Depth 1'] 	='Soil moisture in % - depth 1';
$lrFieldsArr['MSO2']	= $csvFieldsArr['Soil Moisture Depth 2'] 	='Soil moisture in % - depth 2';
$lrFieldsArr['MSO3']	= $csvFieldsArr['Soil Moisture Depth 3'] 	='Soil moisture in % - depth 3';
$lrFieldsArr['MS04']	= $csvFieldsArr['Soil Moisture Depth 4'] 	='Soil moisture in % - depth 4';
$lrFieldsArr['MS05']	= $csvFieldsArr['Soil Moisture Depth 5'] 	='Soil moisture in % - depth 5';
$lrFieldsArr['P.RT']	= $csvFieldsArr['xx'] 						='Precipitation rate';
$lrFieldsArr['POP']		= $csvFieldsArr['xx'] 						='Precipitation chance/hour';
$lrFieldsArr['PTOT']	= $csvFieldsArr['Tot.Prcp'] 				='Precipitation total';
$lrFieldsArr['RNET']	= $csvFieldsArr['Net Radiation'] 			='Net Radiation';
$lrFieldsArr['S.AL']	= $csvFieldsArr['Sun Alt'] 					='Sun altitude';
$lrFieldsArr['S.IR']	= $csvFieldsArr['Solar Rad'] 				='Solar radiation';
$lrFieldsArr['SI']		= $csvFieldsArr['xx'] 						='Stability Showalter index';
$lrFieldsArr['SKY']		= $csvFieldsArr['xx'] 						='Sky cover total';
$lrFieldsArr['SLP']		= $csvFieldsArr['S.L.P.'] 					='Pressure at sea level';	####
$lrFieldsArr['SMT1']	= $csvFieldsArr['Soil Tension Depth 1'] 	='Soil moisture in cb /kPa -depth 1';
$lrFieldsArr['SMT2']	= $csvFieldsArr['Soil Tension Depth 2'] 	='Soil moisture in cb /kPa -depth 2';
$lrFieldsArr['SMT3']	= $csvFieldsArr['Soil Tension Depth 3'] 	='Soil moisture in cb /kPa -depth 3';
$lrFieldsArr['SMT4']	= $csvFieldsArr['Soil Tension Depth 4'] 	='Soil moisture in cb /kPa -depth 4';
$lrFieldsArr['SMT5']	= $csvFieldsArr['Soil Tension Depth 5'] 	='Soil moisture in cb /kPa -depth 5';
$lrFieldsArr['SN.C']	= $csvFieldsArr['Snow Dpth'] 				='Snow/ice depth';
$lrFieldsArr['SURF']	= $csvFieldsArr['Soil Surface Temperature'] ='Temperature surface level';
$lrFieldsArr['SWXO']	= $csvFieldsArr['Severe index'] 			='Severe thunderstorms';
$lrFieldsArr['T.AD']	= $csvFieldsArr['xx'] 						='Temperature Advection';
$lrFieldsArr['THK']		= $csvFieldsArr['10-5 Thk'] 				='Thickness';   ## 1 in lastret  ?more in latest.csv?
$lrFieldsArr['TIME']	= $csvFieldsArr['xx'] 						='TIME';		## for documentation only - not used in program
$lrFieldsArr['TMAX']	= $csvFieldsArr['Hi Temp'] 					='Temperature max';
$lrFieldsArr['TMIN']	= $csvFieldsArr['Low Temp'] 				='Temperature min';
$lrFieldsArr["TOT"]		= $csvFieldsArr['xx'] 						= "TOT";
$lrFieldsArr['TSCD']	= $csvFieldsArr['xx'] 						='Convection index';
$lrFieldsArr['TSMO']	= $csvFieldsArr['Convection index'] 		='Thunder/Showers';
$lrFieldsArr['TSO1']	= $csvFieldsArr['Soil Temperature Depth 1'] ='Temperature soil depth 1';
$lrFieldsArr['TSO2']	= $csvFieldsArr['Soil Temperature Depth 2'] ='Temperature soil depth 2';
$lrFieldsArr['TSO3']	= $csvFieldsArr['Soil Temperature Depth 3']	='Temperature soil depth 3';
$lrFieldsArr['TSO4']	= $csvFieldsArr['Soil Temperature Depth 4']	='Temperature soil depth 4';
$lrFieldsArr['TSO5']	= $csvFieldsArr['Soil Temperature Depth 5']	='Temperature soil depth 5';
$lrFieldsArr['TTI']		= $csvFieldsArr['xx'] 						='Stability Total totals';
$lrFieldsArr['UVI']		= $csvFieldsArr['UV Index'] 				='UV index';
$lrFieldsArr['VIS']		= $csvFieldsArr['VIS'] 						='Visibility horizontal';
$lrFieldsArr['VLY']		= $csvFieldsArr['Vly Tmp'] 					='Temperature valley';
$lrFieldsArr['VST']		= $csvFieldsArr['Vis Trans'] 				='Sky visible trans. %';
$lrFieldsArr['W.DIR']	= $csvFieldsArr['Wind Dir.'] 				='Wind direction degrees';
$lrFieldsArr['W.SP']	= $csvFieldsArr['Wind Spd.'] 				='Wind speed';
$lrFieldsArr['WCF']		= $csvFieldsArr['Wind Chl']					='Windchill';
$lrFieldsArr['WDIR']	= $csvFieldsArr['xx'] 						='Wind direction compass';
$lrFieldsArr['WEATHER']	= $csvFieldsArr['WX Type 1'] 				='Weather condition';   #### in lastret 1 "field" in latest.csv  2 fields
$lrFieldsArr['WEATHER']	= $csvFieldsArr['WX Type 2'] 				='Weather condition';   ## for documentation only - not used in program 
$lrFieldsArr['WET']		= $csvFieldsArr['Wet Bulb'] 				='Wet buld';

$lrFieldsArr['zzzzz']	= $csvFieldsArr['Stn.Pres.'] 				='Station level pressure?';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['SC L1'] 					='SC L1';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['SC L2'] 					='SC L2';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['SC L3'] 					='SC L3';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['SC L4'] 					='SC L4';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['SC L5'] 					='SC L5';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['Freezing Level'] 			='Freezing Level (highest)';
$lrFieldsArr['zzzzz']	= $csvFieldsArr['Snow Level'] 				='Freezing level when no precip';

// converted headings
// Freezing Level = Freezing Level (highest)
// Snow Level  =  Snow Level (= freezing level when no precip)
// Convection index =Convection index (1=very unlikely...2=unlikely...3=sctd.possible...4=sctd.likely...5=numerous likely)
// Severe index (1=very unlikely...2=unlikely...3=sctd.possible...4=sctd.likely...5=numerous likely)
