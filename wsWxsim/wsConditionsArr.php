<?php 
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'wsConditionsArr.php';
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
$conditionsArr = Array ();
##		 cloud coverage
# none  	code 000		= CLR SKC  // clear for night - sunny for day
$conditionsArr['CLEAR']		=
$conditionsArr['SUNNY']		= 								  array ('text' =>'clear', 				'out' =>'clear', 			'code' => 0, 'cond' => 'sky');			
#
$conditionsArr['HAZY SUN']  = 								  array ('text' =>'sunny with haze', 	'out' =>'sunny with haze',	'code' => 0, 'cond' => 'sky');
# minimum	code 100		= FEW
$conditionsArr['M.SUNNY']	= 
$conditionsArr['CLR-FAIR']	= 
$conditionsArr['FAIR']		= 
$conditionsArr['M.CLEAR']	= 								  array ('text' =>'clear mostly', 		'out' =>'mostly clear', 	'code' => 100, 'cond' => 'sky');
# 			code 200		= SCT
$conditionsArr['P.CLOUDY']	= 
$conditionsArr['FAIR-P.C']	= $conditionsArr['FAIR-P.C.']	= array ('text' =>'cloudy partly', 		'out' =>'partly cloudy', 	'code' => 200, 'cond' => 'sky');
# 			code 300		= BKN
$conditionsArr['M.CLOUDY']	=
$conditionsArr['P.-M.CLD']	= $conditionsArr['P.-M.CLDY']	= array ('text' =>'cloudy mostly', 		'out' =>'mostly cloudy', 	'code' => 300, 'cond' => 'sky');
# maximum 	code 400		= OVC VV
$conditionsArr['CLOUDY'] 	=
$conditionsArr['M.C.-CLD']	= $conditionsArr['M.C.-CLDY']	= array ('text' =>'cloudy', 			'out' =>'cloudy', 			'code' => 400, 'cond' => 'sky');
#
$conditionsArr['DNS.OVCS']	= $conditionsArr['DNS.OVCST']	= array ('text' =>'overcast', 			'out' =>'overcast', 		'code' => 400, 'cond' => 'sky');
// the following descriptions  are standardized
// 'Fair';
// 'Fair to partly cloudy';
// 'Mostly clear to cloudy';     ####
// 'Mostly clear';				####
// 'Clear to fair';
// 'Partly to mostly cloudy';
// 'Sunny';
##       weather condition
# rain 			code +10	= DZ RA
# light rain	code + 0
$conditionsArr['CHNC. DRZ'] = $conditionsArr['CHNC. DRZL'] = array ('text' =>'drizzle chance of ', 			'out' =>'chance of drizzle',	'code' => 10, 'cond' => 'drizzle');
$conditionsArr['CHNC. SHW'] = $conditionsArr['CHNC. SHWR'] = array ('text' =>'showers chance of ', 			'out' =>'chance of showers', 	'code' => 10, 'cond' => 'rain');
#
$conditionsArr['DRIZZLE']	= 								 array ('text' =>'drizzle', 					'out' =>'drizzle', 			'code' => 10, 'cond' => 'rain');
#
$conditionsArr['LIGHT RAI'] = $conditionsArr['LIGHT RAIN'] = array ('text' =>'rain light ', 				'out' =>'light rain', 			'code' => 10, 'cond' => 'rain');
#
$conditionsArr['PROB. DRZ'] = $conditionsArr['PROB. DRZL'] = array ('text' =>'drizzle probably', 			'out' =>'probably drizzle',	'code' => 10, 'cond' => 'drizzle');
#
$conditionsArr['PROB. SHW'] = $conditionsArr['PROB. SHWR'] = array ('text' =>'showers probably', 			'out' =>'probably showers',	'code' => 10, 'cond' => 'rain');
# moderate rain	code +  1
$conditionsArr['MOD. RAIN']	= 								 array ('text' =>'rain moderate', 				'out' =>'moderate rain',		'code' => 11, 'cond' => 'rain');
# heavy rain	code +  2
$conditionsArr['HEAVY RAI'] = $conditionsArr['HEAVY RAIN'] = array ('text' =>'rain heavy', 					'out' =>'heavy rain', 			'code' => 12, 'cond' => 'rain');
# snow			code + 20	= SN  SG
# light snow	code +  0
$conditionsArr['CH. RN/SN']	= $conditionsArr['CH. RN/SNW'] = array ('text' =>'rain and or snow chance of', 	'out' =>'chance of snow and or rain',	'code' => 20, 'cond' => 'rain');
$conditionsArr['CH. SNW/R'] = $conditionsArr['CH. SNW/RN'] = array ('text' =>'snow and or rain chance of ', 'out' =>'chance of snow and or rain', 	'code' => 20, 'cond' => 'snow');
$conditionsArr['CHNC. SNO'] = $conditionsArr['CHNC. SNOW'] = array ('text' =>'snow chance of ', 			'out' =>'chance of snow', 				'code' => 20, 'cond' => 'snow');
$conditionsArr['LIGHT SNO'] = $conditionsArr['LIGHT SNOW'] = array ('text' =>'snow light ', 				'out' =>'light snow ', 				'code' => 20, 'cond' => 'snow');
$conditionsArr['LT. RN/SN'] = $conditionsArr['LT. RN/SNW'] = array ('text' =>'rain and or snow light ', 	'out' =>'light rain and or snow', 		'code' => 20, 'cond' => 'rain');
$conditionsArr['LT. SNW/R'] = $conditionsArr['LT. SNW/RN'] = array ('text' =>'snow and or rain light ', 	'out' =>'light rain and or snow', 		'code' => 20, 'cond' => 'snow');
$conditionsArr['PR. RN/SN'] = $conditionsArr['PR. RN/SNW'] = array ('text' =>'rain and or snow probably', 	'out' =>'probably rain and or snow', 	'code' => 20, 'cond' => 'rain');
$conditionsArr['PR. SNW/R'] = $conditionsArr['PR. SNW/RN'] = array ('text' =>'snow and or rain probably', 	'out' =>'probably rain and or snow', 	'code' => 20, 'cond' => 'snow');
$conditionsArr['PROB.SNOW'] = 
$conditionsArr['PROB. SNO'] = $conditionsArr['PROB. SNOW'] = array ('text' =>'snow probably', 				'out' =>'probably snow', 				'code' => 20, 'cond' => 'snow');
# moderate snow	code +  1
$conditionsArr['MOD. SNOW']	= 								 array ('text' =>'snow moderate',				'out' =>'moderate snow', 				'code' => 21, 'cond' => 'snow');
$conditionsArr['SN. FLURR'] = $conditionsArr['SN. FLURRY'] = array ('text' =>'snow flurries', 				'out' =>'snow flurries', 				'code' => 21, 'cond' => 'snow');
$conditionsArr['RAIN/SNOW'] = 								 array ('text' =>'rain and or snow', 			'out' =>'snow and or rain', 			'code' => 21, 'cond' => 'rain');
$conditionsArr['SNOW/RAIN'] = 								 array ('text' =>'snow and or rain', 			'out' =>'snow and or rain', 			'code' => 21, 'cond' => 'snow');
# heavy snow	code +  2
$conditionsArr['HEAVY SNO'] = $conditionsArr['HEAVY SNOW'] = array ('text' =>'snow heavy', 					'out' =>'heavy snow', 					'code' => 22, 'cond' => 'snow');
$conditionsArr['HVY.SNW/R'] = $conditionsArr['HVY.SNW/RN'] = array ('text' =>'snow and or rain  heavy', 	'out' =>'heavy snow and or rain', 		'code' => 22, 'cond' => 'snow');
$conditionsArr['HVY.RN/SN'] = $conditionsArr['HVY.RN/SNW'] = array ('text' =>'rain and or snow  heavy', 	'out' =>'heavy snow and or rain', 		'code' => 22, 'cond' => 'rain');
# winter conditions	code + 30 = IC  PE  GR  GS
# light winter 		code +  0
$conditionsArr['CH. FRZ.R'] = $conditionsArr['CH. FRZ.RN'] = array ('text' =>'freezing rain chance of', 			'out' =>'chance of freezing rain', 			'code' => 30, 'cond' => 'icy');
$conditionsArr['CH. RN/SL']	= $conditionsArr['CH. RN/SLT'] = array ('text' =>'rain and or sleet chance of', 		'out' =>'chance of rain and or sleet',		'code' => 30, 'cond' => 'icy');
$conditionsArr['CH. SLT/F'] = $conditionsArr['CH. SLT/FR'] = array ('text' =>'sleet and or freezing rain chance of','out' =>'chance of sleet and or freezing rain', 'code' => 30, 'cond' => 'icy');
$conditionsArr['CH.SLT/MI'] = $conditionsArr['CH.SLT/MIX'] = array ('text' =>'sleet and or mix chance of',			'out' =>'chance of sleet and or mix',		'code' => 30, 'cond' => 'icy');
$conditionsArr['CH.SLT/SN'] = $conditionsArr['CH.SLT/SNW'] = array ('text' =>'sleet and or snow chance of',			'out' =>'chance of sleet and or snow',		'code' => 30, 'cond' => 'icy');
$conditionsArr['CHNC.SLEE'] = $conditionsArr['CHNC.SLEET'] = array ('text' =>'sleet chance of',						'out' =>'chance of sleet',					'code' => 30, 'cond' => 'icy');

$conditionsArr['FRZ. DRZL'] = 								 array ('text' =>'freezing drizzle',					'out' =>'freezing drizzle', 				'code' => 30, 'cond' => 'icy');
$conditionsArr['FRZ. RAIN'] = 								 array ('text' =>'freezing rain',						'out' =>'freezing rain', 					'code' => 30, 'cond' => 'icy');
$conditionsArr['ICE STORM'] = 								 array ('text' =>'ice storm',							'out' =>'ice storm', 						'code' => 30, 'cond' => 'icy');

$conditionsArr['LT. SLEET'] = 								 array ('text' =>'sleet light',							'out' =>'light sleet light',				'code' => 30, 'cond' => 'icy');
$conditionsArr['LT.SLT/MI'] = $conditionsArr['LT.SLT/MIX'] = array ('text' =>'sleet and or mixture light', 			'out' =>'light sleet and or mixture',		'code' => 30, 'cond' => 'icy');
$conditionsArr['LT.SLT/SN'] = $conditionsArr['LT.SLT/SNW'] = array ('text' =>'sleet and or snow light', 			'out' =>'light sleet and or snow', 			'code' => 30, 'cond' => 'icy');

$conditionsArr['PR. FRZ.R'] = $conditionsArr['PR. FRZ.RN'] = array ('text' =>'freezing rain probably', 				'out' =>'probably freezing rain ', 			'code' => 30, 'cond' => 'icy');
$conditionsArr['PR. RN/SL'] = $conditionsArr['PR. RN/SLT'] = array ('text' =>'rain and or sleet probably', 			'out' =>'probably rain and or sleet probably', 'code' => 30, 'cond' => 'icy');

$conditionsArr['PR.SLT/MI'] = $conditionsArr['PR.SLT/MIX'] = array ('text' =>'sleet and or mixture probably', 		'out' =>'probably sleet and or mixture', 	'code' => 30, 'cond' => 'icy');
$conditionsArr['PR.SLT/SN'] = $conditionsArr['PR.SLT/SNW'] = array ('text' =>'sleet and or snow probably', 			'out' =>'probably sleet and or snow', 		'code' => 30, 'cond' => 'icy');
$conditionsArr['PR. SLT/F'] = $conditionsArr['PR. SLT/FR'] = array ('text' =>'sleet and or freezing rain probably', 'out' =>'probably sleet and or freezing rain', 'code' => 30, 'cond' => 'icy');
$conditionsArr['PROB.SLEE'] = $conditionsArr['PROB.SLEET'] = array ('text' =>'sleet probably', 						'out' =>'probably sleet', 					'code' => 30, 'cond' => 'icy');

$conditionsArr['SN. FLURRY']= 								 array ('text' =>'snow flurries', 						'out' =>'snow flurries', 					'code' => 30, 'cond' => 'snow');

# moderate 	winter	code +  1
$conditionsArr['MOD. SLEE'] = $conditionsArr['MOD. SLEET'] = array ('text' =>'sleet moderate', 						'out' =>'moderate sleet', 					'code' => 31, 'cond' => 'icy');
$conditionsArr['MOD. SNOW'] = 								 array ('text' =>'snow moderate', 						'out' =>'moderate snow ', 					'code' => 31, 'cond' => 'snow');
$conditionsArr['RAIN/SNOW']	= 								 array ('text' =>'rain and or snow', 					'out' =>'snow and or rain', 				'code' => 31, 'cond' => 'rain');
$conditionsArr['SNOW/RAIN']	= 								 array ('text' =>'snow and or rain', 					'out' =>'snow and or rain', 				'code' => 31, 'cond' => 'icy');
$conditionsArr['RAIN/SLEE'] = $conditionsArr['RAIN/SLEET'] = array ('text' =>'rain and or sleet', 					'out' =>'rain and or sleet', 				'code' => 31, 'cond' => 'icy');
$conditionsArr['SLEET/MIX'] = 								 array ('text' =>'sleet and or mixture', 				'out' =>'sleet and or mixture', 			'code' => 31, 'cond' => 'icy');
$conditionsArr['SLEET/SNO'] = $conditionsArr['SLEET/SNOW'] = array ('text' =>'sleet and or snow', 					'out' =>'sleet and or snow', 				'code' => 31, 'cond' => 'icy');
$conditionsArr['SLT/FRZ.R'] = $conditionsArr['SLT/FRZ.RN'] = array ('text' =>'sleet and or freezing rain', 			'out' =>'sleet and or freezing rain', 		'code' => 31, 'cond' => 'icy');
# extreme winter	code +  2
$conditionsArr['BLIZZARD']  = 								 array ('text' =>'snow and blizzards', 					'out' =>'snow and blizzards', 				'code' => 32, 'cond' => 'icy');
$conditionsArr['HVY. SLEE'] = $conditionsArr['HVY. SLEET'] = array ('text' =>'sleet heavy', 						'out' =>'heavy sleet ', 					'code' => 32, 'cond' => 'icy');
$conditionsArr['HVY.SLT/M'] = $conditionsArr['HVY.SLT/MX'] = array ('text' =>'sleet and or mix heavy', 				'out' =>'heavy sleet and or mix', 			'code' => 32, 'cond' => 'icy');
$conditionsArr['HVY.SLT/S'] = $conditionsArr['HVY.SLT/SN'] = array ('text' =>'sleet and or mix heavy', 				'out' =>'heavy sleet and or mix', 			'code' => 32, 'cond' => 'icy');
# fog		code + 50		= BR  FG
# light		code +  0
$conditionsArr['LIGHT FOG'] = 								 array ('text' =>'fog light', 							'out' =>'light fog', 		'code' => 50, 'cond' => 'fog');
# moderate	code +  1
$conditionsArr['MOD. FOG']	= 								 array ('text' =>'fog moderate ', 						'out' =>'moderate fog', 	'code' => 51, 'cond' => 'fog');
# extreme	code +  2
$conditionsArr['DENSE FOG'] = 								 array ('text' =>'fog dense', 							'out' =>'fog dense', 		'code' => 52, 'cond' => 'fog');
# 		dew discarded in icon.  used for grass / soil	## less to high
$conditionsArr['LIGHT DEW'] = 								 array ('text' =>'dew light ', 							'out' =>'light dew', 		'code' => -1, 'cond' => 'dew');
$conditionsArr['MOD. DEW']	= 								 array ('text' =>'dew moderate', 						'out' =>'moderate dew', 	'code' => -1, 'cond' => 'dew');
$conditionsArr['HEAVY DEW'] = 								 array ('text' =>'dew heavy ', 							'out' =>'heavy dew', 		'code' => -1, 'cond' => 'dew');
# discarded or code extreme cold ? extra text   		## less to high
$conditionsArr['SCTD.FROS'] = $conditionsArr['SCTD.FROST'] = array ('text' =>'frost scattered ', 					'out' =>'frost scattered',	'code' => -1, 'cond' => 'frost');
$conditionsArr['LT. FROST'] = $conditionsArr['LT. FROST']  = array ('text' =>'frost light ', 						'out' =>'light frost',		'code' => -1, 'cond' => 'frost');
$conditionsArr['MOD. FROS']	= $conditionsArr['MOD. FROST'] = array ('text' =>'frost moderate ', 					'out' =>'moderate frost', 	'code' => -1, 'cond' => 'frost');
$conditionsArr['HVY. FROS'] = $conditionsArr['HVY. FROST'] = array ('text' =>'frost heavy ', 						'out' =>'frost heavy', 		'code' => -1, 'cond' => 'frost');