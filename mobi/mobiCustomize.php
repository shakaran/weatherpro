<?php 
$string = '
<div class="textbox">
<form method="post" name="user_select" action="" style="padding: 0px; margin: 0px">	
<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="mobi" value ="10"/>  <!-- pagina die nu wordt afgebeeld -->
<table class="genericTable" style="text-align: left;">';

if ($SITE['userChangeLang']) {
	$string.='<tr><td style="text-align: right;">'.langtransstr('Choose a language').'&nbsp;</td><td>';
	$flag = '';
	$string .= '<select id="lang" name="lang"  style="">';
	foreach ($SITE['installedLanguages'] as $k => $v) {
		if($SITE['lang'] == $k) {
			$selected = ' selected="selected"';
			$flag = '	<img src="'. $SITE['imgDir'] . 'flag-'. $k .'.gif" alt="'. $v .'" title="'. $v .'" style="width: 16px; height: 11px; padding: 0px; border: 0px; margin: 3px 0 0 0;" />'.PHP_EOL;
		  } else {
			$selected = '';
		  }
	$string .= '	    <option value="'.$k.'"'.$selected.'>'.$v.'</option>'.PHP_EOL;
	} // end foreach
	$string .= '	</select>'.PHP_EOL;
	if($SITE['langFlags'] == true) {
		$string .= $flag;
	}
	$string.='
	</td></tr>';
}

if ($SITE['userChangeUOM']) {
	$question = '';
	$string.='<tr><td style="text-align: right;">'.$question.langtransstr('temperature').'?</td>
	<td><select id="temp" name="temp"  style="">';
	if ($SITE['uomTemp'] == '&deg;C') {	
		$string.='<option value="f">'.langtransstr('Fahrenheit').'</option><option value="c" selected="selected">'.langtransstr('Celcius').'</option>'.PHP_EOL;
	} else {
		$string.='<option value="f" selected="selected">'.langtransstr('Fahrenheit').'</option><option value="c">'.langtransstr('Celcius').'</option>'.PHP_EOL;	
	}
	$string.='	
	</select></td></tr>
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
	<tr><td colspan="2">&nbsp;</td></tr>';
}

$string.='<tr><td style="text-align: right;"><button id="acceptCookie" name="acceptCookie" >'.langtransstr('Save your changes').'</button></td></tr>

</table>
</form>
</div>
<div class="textbox center">'.langtransstr('Your settings are stored in a cookie').'!</div>'.PHP_EOL;
echo $string;