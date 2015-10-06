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
$pageName	= 'wsDashSoil.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.07 2015-05-04';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.07 2015-05-04 beta 2.7 release version // 3.06 remove error line 87 // 7 submit button
# ----------------------------------------------------------------------
#
if ( !isset ($SITE['soilUsed'])  ||   $SITE['soilUsed']  == false   ||
     !isset ($SITE['soilCount']) ||   $SITE['soilCount'] == 0 )  {return; }
# ----------------------------------------------------------------------
# SETTINGS
$fullpage_link  = true;                 # set to false if no link to full page is wanted            ######
$soil_page      = 'soilTotals';         // script name of the soil page 
#
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
#
#	first the box

echo '<!--  soil -->
<div class="blockDiv">	
<h2 class="blockHead" style= "margin:0px;">'.langtransstr('Soil moisture and temperature');
if ($fullpage_link) {
        $link = $SITE['pages'][$soil_page].'&amp;lang='.$lang.$extraP.$skiptopText;
	echo '&nbsp;<a href="'.$link.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>'.PHP_EOL;}
echo '</h2>
<div style="width: 100%;">'.PHP_EOL;
#	now the table and headings

if (isset ($ws['moistMinToday']) ) {$tempOnly = false; $colspan='3'; $width='width: auto;';} else  {$tempOnly = true; $colspan = '1'; $width='width: auto;';}
echo '<table class="genericTable small" style="'.$width.' margin: 10px auto; padding: 5px; border-style:solid; border: 0; border-color: grey;  "><tbody>'.PHP_EOL;
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
echo '</tbody></table>


</div>
</div> 
<!-- end of soil -->'.PHP_EOL;

?>