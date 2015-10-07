<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsCustomize.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.20 2015-07-07';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);	
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageName].' -->'.PHP_EOL;
#-----------------------------------------------------------------------
# 3.20 2015-07-07 release 2.8 beta
#-----------------------------------------------------------------------
$string='';
$string.='<form method="post" name="user_select" action="'.$SITE['pages']['wsStartPage'].'&amp;wp='.$SITE["WXsoftware"].'" style="padding: 0px; margin: 0px;">	';
if ($cookieAllowed	== false) {
	$string .= '<div style="width: 80%; margin: auto;" ><h3>Cookie laws </h3><p>'.
langtransstr('Like nearly all websites we store your preferences in a small file (cookie) in the browser on your PC. On a subsequent visit, you do not have to set your preferences again.').
'<br />'.
langtransstr('The cookie law requires us to ask you permission for the posting of this file.').
'<br />'.
langtransstr('After setting your preferences, you can click on').' <strong>"'.
langtransstr('Save your changes').'" </strong> '.
langtransstr(', giving us permission to store a cookie.').
'<br />'.
langtransstr('If you click on').'  <strong>"'.
langtransstr('Do not want that').'" </strong> '.
langtransstr('you go back to the starting page without storing your preferences for the next visit.').
'</p>'; 
	$string.='<p><button id="donotwant" name="donotwant">'.langtransstr('Do not want that').'</button><br /><br /><br /></p></div>';
}

$string.='<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" 	value ="'. $p .'"/>  <!-- pagina die nu wordt afgebeeld -->
<table class="genericTable" style="text-align: left;">';

if ($SITE['userChangeLang']) {
	$string.='
<tr><td style="text-align: right;">'.langtransstr('Choose a language').'&nbsp;</td><td>'.PHP_EOL;
	$flag = '';
	$string .= '<select id="lang2" name="lang2"  style="">'.PHP_EOL;
	foreach ($SITE['installedLanguages'] as $k => $v) {
		if($SITE['lang'] == $k) {
			$selected = ' selected="selected"';
			$flag = '<img src="'. $SITE['imgDir'] . 'flag-'. $k .'.gif" alt="'. $v .'" title="'. $v .'" style="width: 16px; height: 11px; padding: 0px; border: 0px; margin: 3px 0 0 0;" />'.PHP_EOL;
		  } else {
			$selected = '';
		  }
	$string .= '	<option value="'.$k.'"'.$selected.'>'.$v.'</option>'.PHP_EOL;
	} // end foreach
	$string .= '</select>'.PHP_EOL;
	if($SITE['langFlags'] == true) {
		$string .= $flag;
	}
	$string.='</td></tr>'.PHP_EOL.PHP_EOL;
}

if ($SITE['userChangeMenu']) {
	$string.='<tr><td style="text-align: right;">'.langtransstr('Where do we place the menu').'?&nbsp;</td>
<td><select id="menu" name="menu"  style="">'.PHP_EOL;
	if ( $SITE['menuPlace'] == 'H') {
		$string.='	<option value="h" selected="selected">'.langtransstr('Horizontal below header').'</option>'.PHP_EOL.'	<option value="v">'.langtransstr('Vertical - leftside').'</option>'.PHP_EOL;
	} else {
		$string.='	<option value="h">'.langtransstr('Horizontal below header').'</option>'.PHP_EOL.'	<option value="v" selected="selected">'.langtransstr('Vertical - leftside').'</option>'.PHP_EOL;
	}
	$string.='</select></td></tr>'.PHP_EOL.PHP_EOL;
}

if ($SITE['userChangeColors']) {
	$arr = array();
	$cnt = count($SITE['colorStyles']);
	$string.='<tr><td style="text-align: right;">'.langtransstr('Automatically adept coulors and backgrounds').'?&nbsp;</td>
<td><select id="mood" name="mood"  style="">'.PHP_EOL;
	for ($i = 0; $i < $cnt; $i++) {
		if ($SITE['colorNumber'] == $i) {$selTxt = ' selected="selected"';} else {$selTxt = '';}
		$string.= '	<option value="'.$i.'"'.$selTxt.'>'.langtransstr('Colour and style').': '.$SITE['colorStyles'][$i].'</option>'.PHP_EOL;			
	}
	$string.='</select></td></tr>'.PHP_EOL.PHP_EOL;
}

if (!isset ($SITE['userChangeHeader']) ){$SITE['userChangeHeader'] = true;}

if ($SITE['userChangeHeader'])	{
// change header type 1 = smaal 2 = with pictures
	$string.='<tr><td style="text-align: right;">'.langtransstr('Which header type do you want').'?&nbsp;</td>
<td><select id="hdrSelect" name="hdrSelect"  style="">'.PHP_EOL;
        $maxheaders  = 3;
        for ($i = 1; $i <= $maxheaders; $i++) {$sel[$i] = '';}
        $i      = (int) $SITE['header'];
        $sel[$i]= ' selected="selected"';	
	$string.=
'	<option value="1" '.$sel[1].'>'.langtransstr('Low 80px header, text only').'</option>
	<option value="2" '.$sel[2].'>'.langtransstr('160px header  with pictures').'</option>
	<option value="3" '.$sel[3].'>'.langtransstr('240px header  gauges').'</option>'.PHP_EOL;
	$string.='</select></td></tr>'.PHP_EOL.PHP_EOL;	
}

if ($SITE['userChangeForecast']) {
        $SITE['allowedFctOrgs'] = array();
        if ($SITE['yahooPage'] === 'yes' ||  $SITE['yahooPage'] === true) {
                $SITE['allowedFctOrgs']['yahoo'] = true;
        }
        if ($SITE['metnoPage'] === 'yes' ||  $SITE['metnoPage'] === true) {
                $SITE['allowedFctOrgs']['metno'] = true;
        }
        if ($SITE['hwaPage'] === 'yes' ||  $SITE['hwaPage'] === true) {
                $SITE['allowedFctOrgs']['hwa'] 	= true;
        }
        if ($SITE['wxsimPage'] === 'yes' ||  $SITE['wxsimPage'] === true) {
                $SITE['allowedFctOrgs']['wxsim'] = true;
        }
        if ($SITE['noaaPage'] === 'yes' ||  $SITE['noaaPage'] === true) {
                $SITE['allowedFctOrgs']['noaa'] = true;
        }
        if ($SITE['ecPage'] === 'yes' ||  $SITE['ecPage'] === true) {
                $SITE['allowedFctOrgs']['ec'] 	= true;
        }
        if ($SITE['wuPage'] === 'yes' ||  $SITE['wuPage'] === true) {
                $SITE['allowedFctOrgs']['wu'] 	= true;
        }
        if ($SITE['yrnoPage'] === 'yes' ||  $SITE['yrnoPage'] === true) {
                $SITE['allowedFctOrgs']['yrno'] 	= true;
        }
        $SITE['allowedFctOrgs']['yowindow']     = true;
#
	$string .= '<!--  Current forecast: '. $SITE['fctOrg'].' -->'.PHP_EOL;
	$string .='<tr><td style="text-align: right;">'.langtransstr('What forecast do you want on the main page').'?</td><td>'.PHP_EOL;
	$string .= '<select id="fct" name="fct"  style="">'.PHP_EOL;
	$txtDebug = $SITE['fctOrg'];
#	echo '<pre>'; print_r($SITE['allowedFctOrgs']); exit;
	foreach ($SITE['allowedFctOrgs'] as $k => $arr) {
		if ($SITE['fctOrg']	== $k) {$selected = ' selected="selected"';}  else {$selected = '';}
		$string .= '	<option value="'.$k.'" '.$selected.'>'.$k.'</option>'.PHP_EOL;
/*		$end = count($arr);
		for ($i = 0; $i < $end; $i++) {
			$content = $arr[$i];
			if ($SITE['fctContent'] == $content && $select1 == true) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			$string .= '	<option value="'.$k.'|'.$content.'"'.$selected.'>'.$k.'-'.$content.'</option>'.PHP_EOL;
		} */
	}
	$string .= '</select></td></tr>'.PHP_EOL.PHP_EOL;	
}

if ($SITE['userChangeChoice']) {
	$string.='<tr><td style="text-align: right;">'.langtransstr('The first page you want to see when you visit our site').'?</td>
<td><select id="choice" name="choice"  style="">
	<option value="wsStartPage"'; 	if ($SITE['noChoice'] == 'wsStartPage')	{$string.=' selected="selected"';}  $string.='>'.langtransstr('Home page').'</option>
	<option value="wsPrecipRadar"';	if ($SITE['noChoice'] == 'wsPrecipRadar'){$string.=' selected="selected"';} $string.='>'.langtransstr('Precipitation Radar').'</option>
	<option value="gaugePage"';	if ($SITE['noChoice'] == 'gaugePage')	{$string.=' selected="selected"';} $string.='>'.langtransstr('Steelseries').'</option>
	<option value="wsForecast"';	if ($SITE['noChoice'] == 'wsForecast')	{$string.=' selected="selected"';} $string.='>'.langtransstr('Forecast page').'</option>
</select></td></tr>'.PHP_EOL.PHP_EOL;
}

if ($SITE['userChangeUOM']) {
	if  (isset($mobi) && $mobi==12) {$question = '';}
	else
		{$question = langtransstr('Which unit of measurement do you prefer for').' ';}
	$string.='<tr><td style="text-align: right;">'.$question.langtransstr('temperature').'?</td>
<td><select id="temp" name="temp"  style="">'.PHP_EOL;
	if ($SITE['uomTemp'] == '&deg;C') {	
		$string.='	<option value="f">'.langtransstr('Fahrenheit').'</option>'.PHP_EOL.'	<option value="c" selected="selected">'.langtransstr('Celcius').'</option>'.PHP_EOL;
	} else {
		$string.='	<option value="f" selected="selected">'.langtransstr('Fahrenheit').'</option>'.PHP_EOL.'	<option value="c">'.langtransstr('Celcius').'</option>'.PHP_EOL;	
	}
	$string.='</select></td></tr>
<tr><td style="text-align: right;">'.$question.langtransstr('pressure').'?</td>
<td><select id="baro" name="baro"  style="">
	<option value="hpa"';	if ($SITE['uomBaro'] == " hPa")	{$string.=' selected="selected"';} $string.='>'.langtransstr('hPa').'</option>
	<option value="mb"';	if ($SITE['uomBaro'] == " mb") 	{$string.=' selected="selected"';} $string.='>'.langtransstr('mb').'</option>
	<option value="inhg"';	if ($SITE['uomBaro'] == " inHg"){$string.=' selected="selected"';} $string.='>'.langtransstr('inHg').'</option>
</select></td></tr>
<tr><td style="text-align: right;">'.$question.langtransstr('windspeed').'?</td>
<td><select id="wind" name="wind"  style="">
	<option value="kmh"';	if ($SITE['uomWind'] == " km/h")	{$string.=' selected="selected"';} $string.='>'.langtransstr('km/h').'</option>
	<option value="kts"';	if ($SITE['uomWind'] == " kts") 	{$string.=' selected="selected"';} $string.='>'.langtransstr('kts').'</option>
	<option value="ms"';	if ($SITE['uomWind'] == " m/s")		{$string.=' selected="selected"';} $string.='>'.langtransstr('m/s').'</option>
	<option value="mph"';	if ($SITE['uomWind'] == " mph")		{$string.=' selected="selected"';} $string.='>'.langtransstr('mph').'</option>
</select></td></tr>
<tr><td style="text-align: right;">'.$question.langtransstr('rain').'?</td>
<td><select id="rain" name="rain"  style="">
	<option value="mm"';	if ($SITE['uomRain'] == " mm")	{$string.=' selected="selected"';} $string.='>'.langtransstr('mm').'</option>
	<option value="in"';	if ($SITE['uomRain'] == " in") 	{$string.=' selected="selected"';} $string.='>'.langtransstr('in').'</option>
</select></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>'.PHP_EOL.PHP_EOL;
}

$string.='<tr><td style="text-align: right;"><button id="acceptCookie" name="acceptCookie" >'.langtransstr('Save your changes').'</button></td>
<td style="text-align: left;"><input type="submit" id="default" name="default" value="'.langtransstr('Reset to website defaults').'"/></td></tr>'.PHP_EOL;
$string.='
</table>
</form>'.PHP_EOL;
echo '<div class="blockDiv">'.PHP_EOL;
echo '<h3 class="blockHead">'.langtransstr('Customize the appearence of this site').'</h3><br />'.PHP_EOL;
echo $string;
echo '<br /></div>'.PHP_EOL;
?>
