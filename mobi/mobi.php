<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'mobi.php';
$pageVersion	= '3.02 2015-09-25';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString = '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.02 2015-09-25  own icons error rel 2.8
# ----------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="text/html; charset=<?php echo $SITE['charset']; ?>" http-equiv="Content-Type" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<script src="mobi/mobiFunctions.js" type="text/javascript"></script>
<link href="mobi/mobiStyle_v3.css" rel="stylesheet" media="screen" type="text/css" />
<style>.textbox .colImgCCN {width: 26px; height: 26px; vertical-align: bottom;}
</style>
<?php 
#-----------------------------------------------------------------------
if (isset($SITE['wsAjaxScript']) ){	// is there a realtime update script for this site allowed
	echo 
'<script type="text/javascript">
	var wsUrl       = "'.$SITE['wsAjaxDataLoad'].'?lang='.$lang.'&wp='.$SITE['WXsoftware'].'";   
	var reloadTime 	= '.$SITE['wsAjaxDataTime'].'000;
</script>
<script type="text/javascript" src="'.$SITE['wsAjaxScript'].'"></script>'.PHP_EOL;
} 	// end if ajaxScript
#-----------------------------------------------------------------------
require_once($SITE['langFunc']);		        // so we can translate  to required lang
#-----------------------------------------------------------------------
echo "<!-- load functions -->".PHP_EOL;
include_once($SITE['functions']);		        // general functions for data processing
#-----------------------------------------------------------------------
echo "<!-- get data from weatherprogr / wu sites / metar files -->".PHP_EOL;;
include_once($SITE['getData']);			        // get data from weatherprogr / wu sites / metar files
#-----------------------------------------------------------------------
echo "<!-- load weatherdata -->".PHP_EOL;
include_once($SITE['loadData']);		        // load weatherdata in string (=array)for eval in javascript
$vars = array();
$string2=str_replace('ajaxVars','$vars',$string1);      // change javascript to php
eval($string2);						// all vars for use on ajax "normal" pages are now loaded into $vars array
$SITE['docRoot']='../';
#-----------------------------------------------------------------------
?>
<title><?php echo langtransstr($SITE['organ']) . " - " .langtransstr('Start page'); ?></title>
<meta name="Keywords" content="weather, Weather, temperature, dew point, humidity, forecast, Davis Vantage Pro, Weather, weather conditions, live weather, live weather conditions, weather data, weather history, Meteohub " />
<meta name="Description" content="Weather conditions <?php echo langtransstr('Weather conditions').' '.$SITE['organ'] ; ?>" />
</head>
<body>
<?php
 # Now check if we want to include warnings on every page
 echo '<!-- warnings and other info goes here  -->'.PHP_EOL;
 if ($SITE['warnings'] == true) { 
        $SITE['pages']['wrnPrintWarnings'] = 'index.php?p=57';
        $SITE['pages']['wsnws-details']    = 'index.php?p=57';
 	include_once($SITE['warningScript']);
	$warnings = true;
	if (isset ($return) && ($return[0]['msg'] == 'no warnings')) {
		echo '<!-- no warnings  eu -->'.PHP_EOL;
		$warnings = false;
	}  
	elseif (isset ($arrNoaaWarnings) && ($arrNoaaWarnings['general']['msg'] == 'no warnings')) {
		echo '<!-- no warnings  noaa -->'.PHP_EOL;
		$warnings = false;
	}  
	elseif (!isset ($arrNoaaWarnings) && !isset ($return)  ) {
		echo '<!-- warnings no arrays found -->'.PHP_EOL;
		$warnings = false;
	}  
	if ($warnings == false && !$SITE['warningGreen']) {  // there are no warnings more severe than green and we do not display them either
		echo '<!-- '.$SITE['warningScript'].' no warnings in order and no green box needed  -->'.PHP_EOL;
	}
	if ($warnings == false && $SITE['warningGreen']) {  // there are no warnings more severe but we have to display a box
		echo '<!-- '.$SITE['warningScript'].' no warnings in order but green box needed  -->
<div class="warnBox" style="background-color: transparent;">			
	<p style="font-weight: bold; margin: 2px 0px 2px 0px; min-height: 0px; text-align: center;">'.langtransstr('There are no warnings or advisories').'</p>
</div>'.PHP_EOL;
	}	
	if ($warnings <> false) {
		if (isset ($return) ) {
			echo '<div class="warnBox">'.PHP_EOL;
			echo '<a style="display: block;" href="http://www.meteoalarm.eu/handheld.php?level=2&amp;area='.$SITE['warnArea'].'">'.
			'<table class="genericTable">'.PHP_EOL;
			for ($i = 0; $i < count($return); $i++) {
				echo '<tr style="background-color: '.str_replace('"','',$return[$i]['color']).';">';
					echo '<td><img src="'.$SITE['warnImg'].$return[$i]['img'].'" title="'.$return[$i]['msg'].'" alt=""/></td>';
					echo '<td><small>'.langtransstr('from').':&nbsp;'.$return[$i]['from'].'&nbsp;'.langtransstr('until').':&nbsp;'.$return[$i]['until'].'<br />'.
				langtransstr('warning for').':&nbsp;<span style="font-weight: bold;">'.langtransstr('wrn'.$return[$i]['types']).'</span>&nbsp;'.
				langtransstr('level').':&nbsp;<span style="font-weight: bold;">'.langtransstr('wrn'.$return[$i]['level']).'</span></small></td>';
				echo '</tr>'.PHP_EOL;
				if ($i < ( count($return) - 1) ) {
					echo '<tr style="height: 2px; font-size: 1px;"><td colspan="2" style="height: 2px; font-size: 1px;"><hr /></td></tr>'.PHP_EOL;
				}
			}
			echo "</table></a></div>".PHP_EOL;					
		} 
		else { // noaa warnings
			$wrnHref 	= '<a href="'.$SITE['noaawarningURL'].'">';
			echo '<div class="warnBox" >'.PHP_EOL;
			echo '<table class="genericTable">'.PHP_EOL;
			for ($i = 0; $i < count($arrNoaaWarnings['warn']); $i++) {
				$unknown	= 'Unknown';
				$severity	= $arrNoaaWarnings['warn'][$i]['severity'];
				if ($severity == $unknown) {
					$severity 		= '-';
					$severityTxt	= '';
					$color			= $noaaSeverity[$unknown]['color'];
					$level			= '';
				} else {
					$severityTxt	= langtransstr($noaaSeverity[$severity]['explanation']);
					$color			= $noaaSeverity[$severity]['color'];
					$level			= langtransstr('level').':&nbsp;<span style="font-weight: bold;">'.langtransstr($severity);
				}
				$urgency	= $arrNoaaWarnings['warn'][$i]['urgency'];
				if ($urgency == $unknown) {
					$urgency 		= '-';
					$urgencyTxt	= '';
				} else {
					$urgencyTxt	= langtransstr($noaaUrgency[$urgency]['explanation']);
				}
	
				$types		= $arrNoaaWarnings['warn'][$i]['types'];
	
				if ($types == 'Thunderstorms') 					{$SITE['wrnLightning']	= true;}
				if ($types == 'Rain'  || $types == 'Snow/Ice') 	{$SITE['wrnRain'] 		= true;  $wrnSide = $types;}
				echo '
			<tr style="background-color: '.$color.'">
				<td>'.'<img src="'.$SITE['warnImg'].$arrNoaaWarnings['warn'][$i]['img'].'" title="'.$arrNoaaWarnings['warn'][$i]['summary'].'" alt=""/></td>
				<td>'.langtransstr('from').':&nbsp;'.$arrNoaaWarnings['warn'][$i]['from'].'</td>
				<td>'.langtransstr('until').':&nbsp;'.$arrNoaaWarnings['warn'][$i]['until'].'</td>
				<td>'.'<span style="font-weight: bold;">'.$arrNoaaWarnings['warn'][$i]['event'].'</span>&nbsp;'.$level.'</span></td>
				<td>'.$wrnHref.'<img src="img/i_symbol.png" alt=" " /></a></td>
			</tr>'.PHP_EOL;
				
			}  // eo for loop every warning	
			echo '</table></div>'.PHP_EOL;
		}
	}
}
if (!isset ($mobi) ) {  
        $mobi   = 10; 
}
else {  $mobi   = trim($mobi); 
}

$allowd_mobi    = array('10' => '10', '11' => '11', '12' => '12', '2' => '2'); 
if (isset ($allowd_mobi[$mobi]) ){  
        $mobi   = $allowd_mobi[$mobi]; 
}
else {  $mobi   = 10; }
#
echo '
<!-- end warnings mobi  -->
<div class="topbar">
  <div class="menuButtons"><small>
    <a href="index.php?pcSite=Y">PC Site</a>
    <a ';
	
if ($mobi >= 10 && $mobi <= 12) 
        {echo 'class="pressed"';} 
echo ' href="index.php?mobi=10">';
echo langtransstr('Weather').'</a>
    <a ';
if ($mobi == 2) 
        {echo 'class="pressed"';}
echo ' href="index.php?mobi=2">Contact</a>
  </small></div>
</div>';  
if ($mobi >= 10 && $mobi <= 12) { 
echo '
<div class="topbar">	
  <div class="menuButtons">
  <small>
    <a ';
if ($mobi == 10) 
        {echo 'class="pressed"';}
echo ' href="index.php?mobi=10">'.langtransstr('Current').'
    </a>
    <a ';
if ($mobi == 11) 
        {echo 'class="pressed"';}
echo ' href="index.php?mobi=11">'.langtransstr('Forecast').'
    </a>
    <a ';
if ($mobi == 12) 
        {echo 'class="pressed"';}
echo ' href="index.php?mobi=12">Customize
    </a>
  </small>
  </div>	
</div>'.PHP_EOL;
} 
echo '<div class="pageContents">'.PHP_EOL;
#
# page mobi = 10 - startpage -----------------------------------
#
if ($mobi == 10) {
        echo '
<div class="textbox center" style="">'.langtransstr("Current weather for").' '.$SITE['yourArea'].' '.langtransstr('at').': 
	<span class="ajax" id="ajaxtime">'.$vars['ajaxtime'].'</span><br />
</div>	
<div class="textbox center" style="">
	<span class="ajax" id="ajaxconditionicon">'.$vars['ajaxconditionicon'].'</span>&nbsp;
	<span class="ajax" id="ajaxcurrentcond">'.$vars['ajaxcurrentcond'].'</span> 
</div>
<div class="textbox center" style="">'.langtransstr("Temperature").': 
	<span class="ajax" id="ajaxtemp">'.$vars['ajaxtemp'].'</span>  
	<span class="ajax" id="ajaxtemparrow">'.$vars['ajaxtemparrow'].'</span>  <small>
	<span class="ajax" id="ajaxheatcolorword" style="vertical-align: top;">'.$vars['ajaxheatcolorword'].'</span></small>
</div>		
<div class="textbox center" style="">'.langtransstr("Humidity").': 
	<span class="ajax" id="ajaxhumidity">'.$vars['ajaxhumidity'].' </span> 
	<span class="ajax" id="ajaxhumidityarrow">'.$vars['ajaxhumidityarrow'].'</span>
</div>
<div class="textbox center" style="">'.langtransstr("Pressure").': 
	<span class="ajax" id="ajaxbaro">'.$vars['ajaxbaro'].'</span> 
	<span class="ajax" id="ajaxbaroarrow">'.$vars['ajaxbaroarrow'].'</span>
</div>
<div class="textbox center" style="">
<span class="ajax" id="ajaxwinddir">'.$vars['ajaxwinddir'].'</span>  
<span class="ajax" id="gizmowindicon">'.$vars['gizmowindicon'].'</span>  
<span class="ajax" id="ajaxwind">'.$vars['ajaxwind'].'</span>
</div>
<div class="textbox center" style="">'.langtransstr("Rain Today").': 
	<span class="ajax" id="ajaxrain">'.$vars['ajaxrain'].'</span>
</div>'.PHP_EOL;
        if ($SITE['SOLAR']) { 
                echo '<div class="textbox center" style="">'.langtransstr("Solar Radiation").': 
	<span class="ajax" id="ajaxsolar">'.$vars['ajaxsolar'].'</span> W/m<sup>2</sup>
</div>'.PHP_EOL;
        }
echo '<div class="textbox center" style="border-bottom: 1px solid;">';
        if (!$SITE['UV']) { 
                echo '
        UV ';
                $skipUVhtml     = true;
                include_once ($SITE['uvScript']);
                echo langtransstr('today').' ';
                echo '<b>'. $uvarray[0]['uv'].'</b>&nbsp;'.wsGetUVword($uvarray[0]['uv']).'&nbsp; ';
                echo langtransstr('forecasts').' ';
        } 
        else { 
                echo langtransstr('UV Index').' ';
                echo 	'
	<span class="ajax" id="ajaxuv">'.$vars['ajaxuv'].'</span>
	<span class="ajax" id="ajaxuvword">'.$vars['ajaxuvword'].'</span><small> ('.langtransstr('Max:').'
	<span class="ajax" id="ajaxuvmax">'.$vars['ajaxuvmax'].'</span>&nbsp;@&nbsp;
	<span class="ajax" id="ajaxuvmaxtime">'.$vars['ajaxuvmaxtime'].'</span>)</small>';
        } 
        echo '
</div>';
		
        if ((isset($SITE['DavisVP'])) && ($SITE['DavisVP'])) {
                $from = array ('hrs.', 'temp.');
                $to   = array ('hours', 'temperature');
                $ws['fcstTxt'] = ucfirst(str_replace ($from, $to, $ws['fcstTxt'] ));		
                $arrVantage=explode('.',$ws['fcstTxt']);
                $text='';
                for ($i=0;$i < count($arrVantage); $i++){
                        $strng=$arrVantage[$i];
                        if ((isset($strng) && (strlen($strng) >= 1))) {
                                $text.=langtransstr($strng).' ';
                        }
                }
                echo '
<div class="textbox center" style="border-bottom: 1px solid;">  
    	<p class="mTxt"><small>'.langtransstr('Our weatherstation').' Davis VP '.langtransstr('forecasts').':</small><br />'.$text.'</p>
</div>';	
        }
        if ($ws['moonset'] === 0 || $ws['moonset'] == '--') { $moonset = '> 24:00';} 
        echo '
<div class="textbox center">
    <table class="genericTable">
        <tr>
        <td>'.langtransstr("Sun&nbsp;").':&nbsp;&nbsp;</td>
        <td><img src="img/sunrise.png" width="24" height="12" alt="sunrise" />&nbsp;&nbsp;'.$sunrise.'</td>
        <td><img src="img/sunset.png"  width="24" height="12" alt="sunset"  />&nbsp;&nbsp;'.$sunset.'</td>
        </tr>
        <tr>
        <td>'.langtransstr("Moon").':&nbsp;</td>
        <td><img src="img/moonrise.png" width="24" height="12" alt="moonrise"/>&nbsp;&nbsp;'.$ws['moonrise'].'</td>
        <td><img src="img/moonset.png"  width="24" height="12" alt="moonset" />&nbsp;&nbsp;'.$ws['moonset'].'</td>
        </tr>
    </table>
</div>';
  	if ($SITE["webcam"] == true) {
        	echo '
<img src="'.$SITE['webcamImg_1'].'" style="margin: 0; width: 100%; vertical-align: bottom;" alt="My webcam">'.PHP_EOL;
  	}
} // eo if page 10
#
# page mobi = 11 - forecast  -----------------------------------
#
elseif ($mobi == 11) {
        include($SITE['yrnoFcst']);
        $SITE['yrnoIconsDir'] = $SITE['iconsDir'].'yrno_icons/';
#echo '<pre>'; print_r ($returnArray); echo 'test </pre>'.PHP_EOL;
        $forecast       = array ();	
        $id             = '1';
        foreach ($returnArray['forecast'] as $arr) {
                if ((1.0*$arr['hour'] == 0)|| (1.0*$arr['hour'] == 3) ) {$imgstr='n';}  else {$imgstr='d';}
                        // images are differtent for night and day. CHECK if you made a copy of daytime only images (f.i.there is only one  4.png, make a copy 4n.pg)
                $unixTime 	                = $arr['timeFrom'];
                $forecast[$id]['period']	= langtransstr(date('l', $unixTime)).' '.date('j', $unixTime).' '.langtransstr(date('F', $unixTime)).' '.date('Y', $unixTime);
                $forecast[$id]['time']		= date('H', $unixTime).'-'.date('H', $arr['timeTo']);
                $forecastTxt			= langtransstr($arr['weatherDesc']);
                $forecast[$id]['condition']	= str_replace( ' ', '<br />', $forecastTxt);
                if (strlen($arr['icon']) == 1) {$icon='0'.$arr['icon'].$imgstr;} else {$icon=$arr['icon'].$imgstr;}
                $notUsed = '';	$iconOut='';	$iconUrlOut = '';
                $iconUrl = 'wsIcons/yrno_icons/'.$icon.'.png';
                wsChangeIcon ('yrno',$icon, $iconOut, $iconUrl, $iconUrlOut, $notUsed);
                $forecast[$id]['icon']	= '<img alt=" " title="icon '.$forecastTxt.'" src="'.$iconUrlOut.'" width="26px" />';
                $forecast[$id]['tempNU']	= $arr['tempNU'];
                $forecast[$id]['rainNU']	= $arr['rainNU'];
                $forecast[$id]['baroNU']	= $arr['baroNU'];
                $forecast[$id]['windDir']	= langtransstr ($arr['windDir']);
                $forecast[$id]['windNU']	= $arr['windSpeedNU'];
                $id++;
        }
        echo '<div class="textbox center" style="">Yr.no 7 '.langtransstr('day forecast for').' '.$SITE['organ'].':</div>
<div class="textbox">
<table class="genericTable">
<tr class="small">
<td>&nbsp;</td><td>&nbsp;</td><td>'.$SITE['uomTemp'].'</td><td>&nbsp;</td><td>'.$SITE['uomWind'].'</td><td>'.$SITE['uomRain'].'</td><td>'.$SITE['uomBaro'].'</td>
</tr>'.PHP_EOL;
        $period='';
        for ($i=1;$i<=count($forecast)-1;$i++) {
                if ($period <> $forecast[$i]['period']) { 
                        echo '<tr class="headrow" ><td colspan="7" style="text-align: left;">'.$forecast[$i]['period'].'</td></tr>'; 
                        $period = $forecast[$i]['period'];
                        }
                echo '<tr>
<td>'.$forecast[$i]['time'].'</td>
<td>'.$forecast[$i]['icon'].'</td>
<td>'.$forecast[$i]['tempNU'].'</td>
<td>'.$forecast[$i]['windDir'].'</td>
<td>'.$forecast[$i]['windNU'].'</td>
<td>'.$forecast[$i]['rainNU'].'</td>
<td>'.$forecast[$i]['baroNU'].'</td>
</tr>'.PHP_EOL;  
        }
        echo '
<tr><td colspan="7"><hr style="width: 100%" />
<small><img src="http://www.yr.no/grafikk/yr-logo.png" align="middle" alt="Yr.No logo" height ="20"/>
<a target="_new" href="http://www.yr.no/place/'.$SITE['yrnoID'].'">
Weather forecast from yr.no, delivered by the Norwegian Meteorological Institute and the NRK</a> ('.$SITE["yrnoID"].')
</small></td></tr>
</table>
</div>'.PHP_EOL;
} // eo mobi = 11
#
# page mobi = 12 - customize  -----------------------------------
#
elseif ($mobi == 12) { 
        echo '<div class="textbox center" style="">'.langtransstr('Customize the appearence of this site').':</div>';
        include ('mobiCustomize.php');
} // eo mobi = 12
#
# page mobi = 2 - contact    -----------------------------------
#
elseif ($mobi == 2) {
        $SITE['pages']['incContact']= 'index.php?mobi=2';
        echo '<div class="textbox" style="text-align: center;">'.PHP_EOL;
        include './contact/incContact.php';
        echo '</div>'.PHP_EOL;
} // eo mobi = 2
echo '
</div>
<div id="footer">
<p><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="16" width="44" /></a></p>
</div>
</body>
</html>';
exit;