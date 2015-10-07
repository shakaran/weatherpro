<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ec_warning.php';
$pageVersion	= '3.20 2015-07-26';
#-------------------------------------------------------------------------------
# 3.20 2015-07-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ( '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->");
#------------------------------ SETTINGS    ------------------------------------
$wrn_green_color=  '#99ff99';
#$wrn_green_color=  'transparent';	// if no green color wanted, remove comment mark at pos 1
#-----------------------------END OF SETTINGS  ---------------------------------
$warnScriptName = $pageName;
$wrnStrings	= '';

if (!$SITE['warnings'] == true) { return; }	# Check if we want to include warnings on every page
#
$script	        = 'ec_settings.php';
ws_message ( '<!-- module '.$warnScriptName.' ('.__LINE__.'): loading '.$script.' -->');
include $script;
#
$script	        = 'ec_fct_create_arr.php';
ws_message ( '<!-- module '.$warnScriptName.' ('.__LINE__.'): loading '.$script.' -->');
include_once $script ;
$weather 	= new ecPlainWeather ();
$ecForecast 	= $weather->getWeatherData($SITE['caProvince'],$SITE['caCityCode']);
#
# echo '<pre>'; print_r($ecForecast); echo '</pre>'; exit;
#
if (!isset($ecForecast['warnings']['maxType'])) {
	ws_message ('<!-- module '.$warnScriptName.' ('.__LINE__.'): no warnings  retrieved -->');
	return;
}
if ($ecForecast['warnings']['maxType'] == -1) {$warnings = false;} else {$warnings = true;} 
if (!$warnings && !$SITE['warningGreen']) {  // there are no warnings more severe than green and we do not display them either
	ws_message ( '<!-- module '.$warnScriptName.' ('.__LINE__.'): no warnings in order and no green box needed  -->');
	return;
} 
if (!$warnings && $SITE['warningGreen']) {  // there are no warnings more severe than green but we need to display them 
	$wrnStrings	.= '<!-- module '.$warnScriptName.' ('.__LINE__.'): no warnings in order -->
<div class="warnBox" style="background-color:  '.$wrn_green_color.'">			
	<p style="font-weight: bold; margin: 2px 0px 2px 0px; min-height: 0px;">'.langtransstr('There are no warnings in order').'</p>
</div>
<!-- end warnings -->'.PHP_EOL;
	return;
} 
if (! isset ($SITE['pages']['ec_print_warn']) ) 
     {  $warn_link      = '<a href="'.$ecForecast['warnings']['url'].'" target="_blank">'; }
else {  if (!isset ($skiptopText)) {$skiptopText = '#data-area';}
        $string = str_replace ('?','%3F',$ecForecast['warnings']['url']);
        $warn_link      = '<a href="'.$SITE['pages']['ec_print_warn'].'&amp;lang='.$lang.$extraP.
                        '&urlwarn='.$string.$skiptopText.'">';}
$wrnStrings	.= '<div class="warnBox" >'.PHP_EOL;
$wrnStrings	.= '<table class="genericTable">'.PHP_EOL;
$countwarns	= count($ecForecast['warnings']['event']);
$first		= '<td rowspan="'.$countwarns.'">'.$warn_link.'<img src="img/i_symbol.png" alt=" " /></a></td>';
if (isset ($ecForecast['warnings']['warnrain']) )
     {  $SITE['wrnRain'] = true;
        if ($ecForecast['warnings']['raintype'] == 'RAIN') 
             {  $wrnSide = 'Rain'; }
        else {  $wrnSide = 'Snow/Ice'; }
}
if (isset ($ecForecast['warnings']['warnthunder']) )   {$SITE['wrnLightning']	= true;}           
#
for ($i = 0; $i < count($ecForecast['warnings']['event']); $i++) {
	$arr		= $ecForecast['warnings']['event'][$i];
	$severity	= $arr['type'];
	$level		= $arr['priority'];
	$description    = $arr['description'];
	$keyColor	= $validWarningTypes[$severity];
	$color		= $validWarningcolors[$keyColor];		
	$wrnStrings	.= '<tr class="wrn'.$color.'">
	<td>'.langtransstr($severity).'</td>
	<td>'.langtransstr($level).' '.langtransstr('priority').'</td>
	<td>'.$description.'</td>
	'.$first.'
	</tr>'.PHP_EOL;
	$first='';
}  // eo for loop every warning	
$wrnStrings	.= '</table></div>'.PHP_EOL;
$wrnStrings	.= '<!-- end warnings -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-26 release 2.8 version 
