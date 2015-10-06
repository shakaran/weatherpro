<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'soilTotals.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-09-06';            # 2015-03-21';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------- 
# 3.01 2015-03-21  release version
#-------------------------------------------------------------------------------------------------
$headText = langtransstr('Soil-sensors').': '.langtransstr('Background information') ;
echo '<div class="blockDiv">
<h3 class="blockHead">'.$headText.'</h3>'.PHP_EOL;

# --------------------- texts --------------------------------------------------------------------
$missingLangText = '';
$par='<br />
<p style="width: 90%; color: black; font-size: 100%; text-align: center; margin: 0 auto;">';
$pEnd='</p>
<br />';


$expplain1='
We have soil sensors buried in the ground at the levels indicated below.<br />
Each level has a temperature probe and a moisture sensor.';

if ($SITE['lang'] == 'nl') {
	$expplain1='
We hebben bodemsensoren begraven in de grond op de hieronder aangegeven niveaus.<br />
Elk niveau heeft een temperatuursensor en een vochtsensor.';
} 
if ($SITE['lang'] == 'de') {
	$expplain1='
Wir haben Boden-Sensoren im Boden vergraben auf den Ebenen unten angegeben.<br />
Jede Ebene verfügt über einen Temperatursensor und einen Feuchtigkeitssensor.';
} 
if ($SITE['lang'] == 'fr') {
	$expplain1="
Nous avons des capteurs de sol enfouis dans le sol aux niveaux indiqués ci-dessous.<br />
Chaque niveau a un capteur de température et un capteur d'humidité.";
} 

echo $par.$expplain1.$pEnd;


$headText = langtransstr('Current Planting & Watering Guide');
# --------------------- Current situation-------------------------------------------------------
#	first the box
echo '
<!--  soil -->
<h3 class="blockHead">'.$headText.'</h3>'.PHP_EOL;
#	now the table and headings
if (isset ($ws['moistMinToday']) ) {$tempOnly = false; $colspan='3'; } else  {$tempOnly = true; $colspan = '1';}
echo '<table class="genericTable small" style="width: auto; margin: 10px auto; padding: 5px; border-style:solid; border: 0; border-color: grey;  "><tbody>'.PHP_EOL;
echo '<tr>
<td rowspan="2" style = "padding-right: 5px; border-bottom: 1px solid grey;">&nbsp;'.langtransstr('Depth').'<br />'.langtransstr('in').' '.$SITE['uomSnow'].'&nbsp;</td>
<td colspan="'.$colspan.'" style = "border-bottom: 1px solid grey;">&nbsp;&nbsp;&nbsp;'.langtransstr('Moisture').' - '.'cb &nbsp;&nbsp;&nbsp;</td>
<td colspan="2" style = "border-bottom: 1px solid grey;">&nbsp;</td>
<td colspan="'.$colspan.'" style = "border-bottom: 1px solid grey;">&nbsp;&nbsp;&nbsp;'.langtransstr('Temperature').' - '.$SITE['uomTemp'].'&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>'.PHP_EOL;
if (!$tempOnly) {
	echo '<td style = "border-bottom: 1px solid grey;">'.langtransstr('Min').'</td>';
        echo '<td style = "border-bottom: 1px solid grey;">'.langtransstr('Max').'</td>';} 
echo '<td style = "border-bottom: 1px solid grey;">'.langtransstr('Actual').'</td>';
echo '<td colspan="2" style = "text-align: center; border-bottom: 1px solid grey;">'.langtransstr('Grow conditions').'</td>';
echo '<td style = "border-bottom: 1px solid grey;">'.langtransstr('Actual').'</td>';
if (!$tempOnly) {
	echo '<td style = "border-bottom: 1px solid grey;">'.langtransstr('Max').'</td>';
        echo' <td style = "border-bottom: 1px solid grey;">'.langtransstr('Min').'</td>';} 
echo '</tr>'.PHP_EOL;

echo '<!-- loop for '.$SITE['soilCount'].' sensors -->';    //  printf( "%3.0f",
for ($i = 1; $i <= $SITE['soilCount'] ; $i++) {
	$stArr = getSoilTempIndex($ws['soilTempAct'][$i]);
	$smArr = getSoilMoistIndex($ws['moistAct'][$i]);
	echo '<tr>';
        echo '<td>'.$SITE['soilDepth_'.$i].'</td>';
	if (!$tempOnly) {
	        echo '<td style="">'.round($ws['moistMinToday'][$i]).'</td>';	
                echo '<td style="">'.round($ws['moistMaxToday'][$i]).'</td>';} 
	echo '<td style=";">'.round($ws['moistAct'][$i]).'</td>';
        echo '<td style="text-align: center;">';
        echo '    <div style="font-size: 80%; color: '.$smArr['tc'].'; border: 1px inset; border-radius: 5px; background-color: '.$smArr['color'].'; height: 28px; margin: 1px; width: 160px;">'.
		langtransstr($smArr['text']).'<br />('.$smArr['range'].')</div></td>';
	echo '<td style="text-align: center;">';
	echo '    <div style="font-size: 80%; color: '.$stArr['tc'].'; border: 1px inset; border-radius: 5px; background-color:'.$stArr['color'].'; height: 28px; margin: 1px; width: 160px;">'.
		langtransstr($stArr['text']).'<br />('.$stArr['range'].')</div></td>';
	echo '<td style="">'.round($ws['soilTempAct'][$i]).'</td>';
	if (!$tempOnly) {
		echo '<td style="">'.round($ws['soilTempMaxToday'][$i]).'</td>';
                echo '<td style="">'.round($ws['soilTempMinToday'][$i]).'</td>';} 
	echo '</tr>';
}
echo '</tbody></table>'.PHP_EOL;


$headText = langtransstr('Explanation Soil condition');
echo '<h3 class="blockHead">'.$headText.'</h3>'.PHP_EOL;
$expplain1='
Water potential is commonly measured in units of bars (and centibars in the English system of measurement) or kilopascals (in metric units).<br />
One bar is approximately equal to one atmosphere (14.7 lb/in 2 ) of pressure. One centibar is equal to one kilopascal.';

if ($SITE['lang'] == 'nl') {
	$expplain1='
Water potentieel wordt vaak gemeten in eenheden van bars (en centibars in het Engels systeem van de meting) of kilopascal (in metrische eenheden).<br />
Een bar is ongeveer gelijk aan een atmosfeer (14,7 lb/in2) druk. Een centibar is gelijk aan een kilopascal.';
} 
if ($SITE['lang'] == 'fr') {
	$expplain1='
Le potentiel hydrique est souvent mesuré en unités de barres (et centibars dans le système anglais de la mesure) ou en kilopascals (en unités métriques).<br />
Une barre est approximativement égale à une atmosphère (14.7 lb/in2) de pression. Un centibar est égal à un kilopascals.';
} 
if ($SITE['lang'] == 'de') {
	$expplain1='
Wasser-Potenzial wird oft in Einheiten von Bars (und centibars in der englischen Maßsystem) oder Kilopascal (in metrischen Einheiten) gemessen.<br />
Eine Bar ist ungefähr gleich einer Atmosphäre (14,7 lb/in2) Druck. Ein Zentibar gleich einem Kilopascal.';
} 

echo $par.$expplain1.$pEnd.PHP_EOL;

# --------------------- explanation Soil condition-------------------------------------------------------
#	first the box

#	now the table and headings
echo '<table class="genericTable small" style="width: auto; "><tbody>
<tr>
<td style = "border-bottom: 1px solid grey;">'.langtransstr('Buttton text').'</td>
<td style = "border-bottom: 1px solid grey;">'.langtransstr('Explanation').'</td>
</tr>';
$soilArr = array (0,10,25,60,100,230);
$textArr[1]=langtransstr('Saturated Soil. Occurs for a day or two after irrigation.');
$textArr[2]=langtransstr('Soil is adequately wet (except coarse sands which are drying out at this range)');
$textArr[3]=langtransstr('Usual range to irrigate or water (except heavy clay soils).').'<br />'.
	langtransstr('Irrigate at the upper end of this range in cool humid climates and with higher water-holding capacity soils.');
$textArr[4]=langtransstr('Usual range to irrigate heavy clay soils');
$textArr[5]=langtransstr('Soil is becoming dangerously dry for maximum production.');


for ($i = 1; $i < count($soilArr); $i++) {
	$smArr = getSoilMoistIndex ($soilArr[$i]);
	echo '
<tr>
<td style = "border-bottom: 1px solid grey;">
  <div style="font-size: 80%; color: '.$smArr['tc'].'; border: 1px inset; border-radius: 5px; background-color: '.$smArr['color'].'; height: 28px; margin: 1px; width: 160px;">'.
		langtransstr($smArr['text']).'<br />('.$smArr['range'].')
  </div>
</td>
<td style = "border-bottom: 1px solid grey; padding: 5px;">'.$textArr[$i].'
</td>
</tr>';
}
echo '
</tbody></table>
<!-- end of explanationl -->
<br />';

# --------------------- records -------------------------------------------------------

if (!isset($ws['soilTempMinMonthTime']) || !isset($ws['soilTempMinAllTime']) ) {echo '<!--  problem  --></div>'; return;}
$headText = langtransstr('Record Soil Temperatures');
$width = 'width: '.round ((100/13),1).'%; ';
echo '
<div style="width: 100%;">
<table class="genericTable small" style="border-style:solid; border: 0; border-color: grey;  "><tbody>
<tr style="height: 15px;"><td class="blockHead"> </td><td colspan="13" class="blockHead">'.langtransstr('Records').'</td></tr>
<tr>
<td rowspan="2" style = "'.$width.'border-bottom: 1px solid grey; border-right: 1px solid grey;">'.langtransstr('Depth').'<br />'.langtransstr('in').' '.$SITE['uomSnow'].'</td>
<td colspan="4" style = "border-bottom: 1px solid grey; border-right: 1px solid grey;">'.langtransstr('This month').'</td>
<td colspan="4" style = "border-bottom: 1px solid grey; border-right: 1px solid grey;">'.langtransstr('This year').'</td>
<td colspan="4" style = "border-bottom: 1px solid grey;">'.langtransstr('All time').'</td>
</tr>
<tr>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey;">'.langtransstr('Date').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; ">'.langtransstr('Min').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; ">'.langtransstr('Max').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; border-right: 1px solid grey;">'.langtransstr('Date').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey;">'.langtransstr('Date').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; ">'.langtransstr('Min').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; ">'.langtransstr('Max').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; border-right: 1px solid grey;">'.langtransstr('Date').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey;">'.langtransstr('Date').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; ">'.langtransstr('Min').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey; ">'.langtransstr('Max').'</td>
<td colspan="1" style = "'.$width.'border-bottom: 1px solid grey;">'.langtransstr('Date').'</td>
</tr>'.PHP_EOL;
echo '<tr style="height: 15px;"><td class="blockHead"> </td><td colspan="13" class="blockHead">'.langtransstr('Soil Temperatures').'</td></tr>'.PHP_EOL;
echo '<!-- loop for '.$SITE['soilCount'].' sensors -->'.PHP_EOL;  // sprintf("%01.1f", $money)
for ($i = 1; $i <= $SITE['soilCount'] ; $i++) {
	echo '<tr>
<td style = "border-right: 1px solid grey;">'.$SITE['soilDepth_'.$i].'</td>
<td>'.string_date($ws['soilTempMinMonthTime'][$i],	$SITE['dateMDFormat']).'</td>
<td style="">'.sprintf("%01.1f", $ws['soilTempMinMonth'][$i]).'</td>
<td style="">'.sprintf("%01.1f", $ws['soilTempMaxMonth'][$i]).'</td>
<td style = "border-right: 1px solid grey;">'.string_date($ws['soilTempMaxMonthTime'][$i],	$SITE['dateMDFormat']).'</td>';
	
	echo '
<td>'.string_date($ws['soilTempMinYearTime'][$i], $SITE['dateMDFormat']).'</td>
<td style="">'.sprintf("%01.1f", $ws['soilTempMinYear'][$i]).'</td>
<td style="">'.sprintf("%01.1f", $ws['soilTempMaxYear'][$i]).'</td>
<td style = "border-right: 1px solid grey;">'.string_date($ws['soilTempMaxYearTime'][$i],	$SITE['dateMDFormat']).'</td>';
	
	echo '
<td>'.string_date($ws['soilTempMinAllTime'][$i], $SITE['dateOnlyFormat']).'</td>
<td style="">'.sprintf("%01.1f", $ws['soilTempMinAll'][$i]).'</td>
<td style="">'.sprintf("%01.1f", $ws['soilTempMaxAll'][$i]).'</td>
<td>'	.string_date($ws['soilTempMaxAllTime'][$i],		$SITE['dateOnlyFormat']).'</td>';
	echo '</tr>';
}
if (isset ($ws['moistMinYearTime']) ) {
        echo '<tr style="height: 15px;"><td class="blockHead"> </td><td colspan="13" class="blockHead">'.langtransstr('Soil Moisture').'</td></tr>'.PHP_EOL;
        echo '<!-- loop for '.$SITE['soilCount'].' sensors -->'.PHP_EOL;  // sprintf("%01.1f", $money)
        for ($i = 1; $i <= $SITE['soilCount'] ; $i++) {
                echo '<tr>
<td style = "border-right: 1px solid grey;">'.$SITE['soilDepth_'.$i].'</td>
<td>'.string_date($ws['moistMinMonthTime'][$i],	$SITE['dateMDFormat']).'</td>
<td style="">'.sprintf("%01.1f", $ws['moistMinMonth'][$i]).'</td>
<td style="">'.sprintf("%01.1f", $ws['moistMaxMonth'][$i]).'</td>
<td style = "border-right: 1px solid grey;">'.string_date($ws['moistMaxMonthTime'][$i],	$SITE['dateMDFormat']).'</td>';
	
	        echo '
<td>'.string_date($ws['moistMinYearTime'][$i], $SITE['dateMDFormat']).'</td>
<td style="">'.sprintf("%01.1f", $ws['moistMinYear'][$i]).'</td>
<td style="">'.sprintf("%01.1f", $ws['moistMaxYear'][$i]).'</td>
<td style = "border-right: 1px solid grey;">'.string_date($ws['moistMaxYearTime'][$i],	$SITE['dateMDFormat']).'</td>';
	
	        echo '
<td>'.string_date($ws['moistMinAllTime'][$i], $SITE['dateOnlyFormat']).'</td>
<td style="">'.sprintf("%01.1f", $ws['moistMinAll'][$i]).'</td>
<td style="">'.sprintf("%01.1f", $ws['moistMaxAll'][$i]).'</td>
<td>'	.string_date($ws['moistMaxAllTime'][$i],		$SITE['dateOnlyFormat']).'</td>';
	        echo '</tr>';
        }
}
echo '	
</tbody></table>
</div>
<!-- end of current soil -->
</div>'.PHP_EOL; 
# ------------supportting soil moist  functions ------------------------------------------
#
function getSoilMoistIndex ( $moist ) {
	$soilArr = array (0,11,26,61,101,240);
	$moist = $moist*1.0;
	global $SITE;
	switch (TRUE) {
		case ($moist  < $soilArr[1]):
			$smArr = array('color'	=>	'#003399',	'tc' => 'white',	'text' => 'Saturated', 			'range' => $soilArr[0].' - '.$soilArr[1].$SITE['uomMoist'] );
		break;
		case ($moist  < $soilArr[2]):
			$smArr = array('color'	=>	'#33FF00',	'tc' => 'black',	'text' => 'Adequate', 			'range' => $soilArr[1].' - '.$soilArr[2].$SITE['uomMoist'] );
		break;
		case ($moist  < $soilArr[3]):
			$smArr = array('color'	=>	'#FF9933',	'tc' => 'black',	'text' => 'Irrigation desired',	'range' => $soilArr[2].' - '.$soilArr[3].$SITE['uomMoist'] );
		break;
		case ($moist  < $soilArr[4]):
			$smArr = array('color'	=>	'#FF0000',	'tc' => 'white',	'text' => 'Irrigation needed',	'range' => $soilArr[3].' - '.$soilArr[4].$SITE['uomMoist'] );
		break;
		case ($moist  < $soilArr[5]):
			$smArr = array('color'	=>	'#9933CC',	'tc' => 'white',	'text' => 'Dangerous Dry',		'range' => $soilArr[4].' - '.$soilArr[5].$SITE['uomMoist'] );
		break;
		case ($moist >= $soilArr[5]):
			$smArr = array('color'	=>	'grey',	'tc' => 'red',	'text' => 'ERROR no reading',	'range' => '>= '.$soilArr[5].$SITE['uomMoist'] );
		break;
	}			// end switch
	return $smArr;
}				// eof get_SoilMoistIndex
#
function getSoilTempIndex ( $soiltemp ) {
	global $SITE;
	if ($SITE['uomTemp'] == '&deg;C') {$tempArr = array (-18,-6,0,10,15,21,37); } else {$tempArr = array (0,20,33,50,60,70,100);}
	switch (TRUE) {
		case ($soiltemp  < $tempArr[1]):
		$stArr = array('color'	=>	'#003399',	'tc' => 'white',	'text' => 'Deep freeze', 	'range' => $tempArr[0].' - '.$tempArr[1].$SITE['uomTemp']);
	break;
		case ($soiltemp  < $tempArr[2]):
		$stArr = array('color'	=>	'#33CCFF',	'tc' => 'black',	'text' => 'Frost line', 		'range' => $tempArr[1].' - '.$tempArr[2].$SITE['uomTemp']);
	break;
		case ($soiltemp  < $tempArr[3]):
		$stArr = array('color'	=>	'#FF0000',	'tc' => 'white',	'text' => 'Too cold to plant', 	'range' => $tempArr[2].' - '.$tempArr[3].$SITE['uomTemp']);
	break;
		case ($soiltemp  < $tempArr[4]):
		$stArr = array('color'	=>	'#FF9933',	'tc' => 'black',	'text' => 'Minimum growth', 	'range' => $tempArr[3].' - '.$tempArr[4].$SITE['uomTemp']);
	break;
		case ($soiltemp  < $tempArr[5]):
		$stArr = array('color'	=>	'#669900',	'tc' => 'white',	'text' => 'Optimal growth', 	'range' => $tempArr[4].' - '.$tempArr[5].$SITE['uomTemp']);
	break;
		case ($soiltemp  < $tempArr[6]):
		$stArr = array('color'	=>	'#33FF00',	'tc' => 'black',	'text' => 'Ideal growth', 		'range' => $tempArr[5].' - '.$tempArr[6].$SITE['uomTemp']);
	break;
		case ($soiltemp >= $tempArr[6]):
		$stArr = array('color'	=>	'grey',	'tc' => 'red',	'text' => 'ERROR no reading', 	'range' => ' &gt;= '.$soiltemp[6].$SITE['uomTemp']);
	break;
	}			// end switch
	return $stArr;
}				// end get_SoilTemperature
?>