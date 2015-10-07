<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName		='hwaGenerateHtml.php';
$pageVersion	= '3.00 2015-01-26';
$string         = $pageName.'- version: ' . $pageVersion;
$pageFile 	= basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$string .= ' - '.$pageFile .' loaded instead';}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
echo 	        '<!-- module loaded:'.$string.' -->'.PHP_EOL;
#---------------------------------------------------------------------------
# 3.00 2015-01-26  beta version: changed UTF-8  degree symbol temp colors
#---------------------------------------------------------------------------
// temparray 2 starts at -32C, so add 32 to C temp
$tempArray2=array(
'#F6AAB1', '#F6A7B6', '#F6A5BB', '#F6A2C1', '#F6A0C7', '#F79ECD', '#F79BD4', '#F799DB', '#F796E2', '#F794EA', 
'#F792F3', '#F38FF7', '#EA8DF7', '#E08AF8', '#D688F8', '#CC86F8', '#C183F8', '#B681F8', '#AA7EF8', '#9E7CF8', 
'#9179F8', '#8477F9', '#7775F9', '#727BF9', '#7085F9', '#6D8FF9', '#6B99F9', '#68A4F9', '#66AFF9', '#64BBFA', 
'#61C7FA', '#5FD3FA', '#5CE0FA', '#5AEEFA', '#57FAF9', '#55FAEB', '#52FADC', '#50FBCD', '#4DFBBE', '#4BFBAE', 
'#48FB9E', '#46FB8D', '#43FB7C', '#41FB6A', '#3EFB58', '#3CFC46', '#40FC39', '#4FFC37', '#5DFC35', '#6DFC32', 
'#7DFC30', '#8DFC2D', '#9DFC2A', '#AEFD28', '#C0FD25', '#D2FD23', '#E4FD20', '#F7FD1E', '#FDF01B', '#FDDC19', 
'#FDC816', '#FDC816', '#FEB414', '#FEB414', '#FE9F11', '#FE9F11', '#FE890F', '#FE890F', '#FE730C', '#FE730C', 
'#FE5D0A', '#FE5D0A', '#FE4607', '#FE4607', '#FE2F05', '#FE2F05', '#FE1802', '#FE1802', '#FF0000', '#FF0000',
);
$skip			= true;
$timezone 		= $SITE['tz'];
$lat 			= $SITE['latitude'];
$long			= $SITE['longitude'];
$dateTimeFormat = $SITE['timeFormat'];
$timeFormat 	= $SITE['timeOnlyFormat'];
$dateFormat 	= $SITE['dateOnlyFormat'];
$dateLongFormat = isset($SITE['dateLongFormat'])? $SITE['dateLongFormat'] : 'l d F Y';
$utcDiff 		= date('Z');// used for graphs timestamps
$forecasts		= 0;
$from 			= array ('&deg;',' ','/');
$uomRain 		= str_replace ($from,'',$SITE['uomRain']);
$uomTemp 		= str_replace ($from,'',$SITE['uomTemp']);
$uomBaro 		= str_replace ($from,'',$SITE['uomBaro']);
$uomWind 		= 'Bft';
$windIconsSmall	= $SITE['windIconsSmall'];
$tempSimple		= false;
if (isset ($SITE['tempSimple']) ) {$tempSimple = $SITE['tempSimple'];}
#echo '<pre>'; print_r($returnArray['forecast']);
# we loop through all data and build arrays for the coloms of the output.
$foundFirst	= '';
$snow		= false;
$thunder	= false;
$arrTime 	= array ();		$arrTimeGraph 	= array ();
$arrDay 	= array ();
$arrIcon	= array ();		$arrIconGraph	= array ();
$arrDesc	= array ();
$arrTemp	= array ();		$arrTempGraph	= array ();
$arrRain	= array ();		$arrRainGraph	= array ();
$arrCoR		= array ();
$arrCoT		= array ();
$arrCoS		= array ();
$arrWind	= array ();		$arrWindGraph	= array ();
$arrWdir	= array ();		$arrWdirGraph	= array ();
$arrWindIcon= array ();
$arrBaro	= array ();		$arrBaroGraph	= array ();
$graphsDays = array ();
$oldDay		= '';		// to detect day cahnges in input
$graphsData	= '';		// we store all javascript data here
$graphLines = 0;		// number of javascript data lines
$graphsStop = 0;
$graphsStart= 0;
$graphTempMin = 100;
$graphTempMax = -100;
$graphBaroMin = 2000;
$graphBaroMax = 0;
$graphRainMax = 0;
$graphWindMax = 0;
#
$rowColor		= 'row-dark';
$hwaListTable 	= '';
$hwaListTable  .= '<table class="genericTable" style="width: 100%;"><tbody>
<tr class="table-top">
<td>'.langtransstr('Period').'</td><td colspan="2">'.langtransstr('Forecast').'</td>
<td>'.langtransstr('Temperature').'</td><td>'.langtransstr('Precipitation').' '.langtransstr('and chance').'</td>
<td>'.langtransstr('Wind').'</td><td>'.langtransstr('Pressure').'</td>
</tr>'.PHP_EOL;
#
$now = time();
$dataLine = $firstTime = true;
#echo '<pre>'; print_r ($returnArray); exit;
for ($i =1; $i < count($returnArray['forecast']); $i++) {
	$arr 			= $returnArray['forecast'][$i];
	if ($skip == true) {
		if ($now > $arr['timestamp']) {continue;}
	}
	$skip = false;
	if ($dataLine == true) {
		if ($firstTime == true) {
			$firstTime = false; 
			$hwaListTable .= myDateLinePrint($arr['timestamp']-3600);
		} else {
			$hwaListTable .= myDateLinePrint($arr['timestamp']); 
		}
		$dataLine = false; 
		$hour = date('H',$arr['timestamp']);
		if ($hour <> 0) {$oldDay  	= $arr['date'];}
		}
	if ($oldDay <> $arr['date']) {		// do we have a new day
		$oldDay  	= $arr['date'];
		$graphsDays[] = 1000 * ($arr['timestamp']);
		$dataLine 	= true;
	} // 
# first the javascript graph
# time
	$arrTimeGraph[$graphLines]	= $arr['timestamp'] + $utcDiff;
# icon
	$notUsed  = $iconOut = $iconUrlOut = '';
	wsChangeIcon ('hwa',$arr['icon'], $iconOut,$arr['iconUrl'], $iconUrlOut, $notUsed);
	$arrIconGraph[$graphLines]	= str_replace ('/'.$iconOut,'_small/'.$iconOut,$iconUrlOut);
# rain	
	if (!isset ($arr['rainNU']) ) {
		$value = '-';
	} else {
		$value = $arr['rainNU'];
		if ($value > $graphRainMax) {$graphRainMax = $value;}
	}
	$arrRainGraph[$graphLines] = $value;
# baro	
	$value 			= $arr['baroNU'];
	if ($value > $graphBaroMax) {$graphBaroMax = $value;} 
	if ($value < $graphBaroMin) {$graphBaroMin = $value;} 	
	$arrBaroGraph[$graphLines]	= $value;
# temp
	$value 			= $arr['tempNU'];
	if ($value > $graphTempMax) {$graphTempMax = $value;}
	if ($value < $graphTempMin) {$graphTempMin = $value;}
	$arrTempGraph[$graphLines]	= $value;
# wind
	$value 			= $arr['windSpeedNU'];
	if ($value > $graphWindMax) {$graphWindMax = $value;}
	$arrWindGraph[$graphLines]	= $value;
	$arrWdirGraph[$graphLines]	= $arr['windDir'];
# store all javascript data	
	$graphsData	.= 	'tsv['.$graphLines.'] ="'.
					$arrTimeGraph[$graphLines].'|'.
					$arrTempGraph[$graphLines].'|'.
					$arrBaroGraph[$graphLines].'|'.
					$arrWindGraph[$graphLines].'|'.
					$arrWdirGraph[$graphLines].'|'.		
					($arrTimeGraph[$graphLines]-2*3600).'|'.$arrRainGraph[$graphLines].'|'.		
					($arrTimeGraph[$graphLines]).'|'.$arrIconGraph[$graphLines].'";'.PHP_EOL;
				
	$graphLines++;
#
# now the hwa list table
	$hwaListTable .='<tr class="'.$rowColor.'">'.PHP_EOL;;
	if ($rowColor == 'row-dark') {$rowColor = 'row-light';} else {$rowColor =  'row-dark';}
	$start	= $arr['hour'] - 3;
	if ($start < 0) {$start = $start + 24;}
	$period = substr ('00'.$start,-2). ' : '.$arr['hour'];
	$rain = '';
	if (isset ($arr['rain']) && $arr['rain'] <> 0) {
		$rain = $arr['rain'];
	}
	if (isset ($arr['rainChance']) ) {
		if ($arr['rainChance'] <> 0) {
			$rain = $arr['rain'].' ('.langtransstr('chance of').$arr['rainChance'].'% )';		
		} 
		if ($arr['snowChance'] <> 0) {
			$rain .= '<br />'.$arr['snowChance'].'% '.langtransstr('chance of').' '.langtransstr('snow');		
		} 
		if ($arr['thunderChance'] <> 0) {
			$rain .= '<br />'.$arr['thunderChance'].'% '.langtransstr('chance of').' '.langtransstr('thunder');		
		} 
	}
	$notUsed 	= $iconUrl = $iconOut = $iconUrlOut = '';
	wsChangeIcon ('hwa',$arr['icon'], $iconOut,$arr['iconUrl'], $iconUrlOut, $notUsed);
	$icon		= $iconUrlOut;

/*	$temp 		= $arr['tempNU'];
	
	if (strtolower($uomTemp) == 'c') {      //  removed conversion error 2015-01-26
	        $colorTemp = $temp + 32;
	} 
	else {  $colorTemp =  round( 5*($temp-32)/9 ) + 32;  # $temp;
	}
	if (!$tempSimple) {
		$color		= $tempArray2[$colorTemp];
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 200%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	} else {
		if ($colorTemp <  32) { $color = 'blue'; } else {$color = 'red';}
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 150%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	}
*/      $tempString     = hwa_commontemp($arr['tempNU']);
	$windSpeed	= $arr['windSpeedNU'];
	$color		= wsBeaufortColor ($windSpeed);
	$tekst		= langtransstr(wsBeaufortText ($windSpeed));
	$windText	='<span style="background-color: '.$color.';">'.$arr['windSpeed'].' - '.$tekst.'</span>';
	$wind		= $windText.'<br />'.langtransstr ('from the').' '.langtransstr($arr['windDir']);

	$hwaListTable .='<td>'.$period.'</td><td>'.langtransstr($arr['weatherDesc']).'</td>
	<td><img src="'.$icon.'" style="width: 40px;" alt=" "/></td><td>'.$tempString.'</td>
	<td>'.$rain.'</td><td>'.$wind.'</td><td>'.$arr['baro'].'</td></tr>'.PHP_EOL;
	if (!isset ($arr['iconL']) ) {continue;}  // skip non hwa table lines
# now we process only lines for 12 hour tables 
	$forecasts++;
	$arrTime[]	= $arr['timestamp'];
	$dayText 	= langtransstr(date('l', $arr['timestamp']));
	if ($foundFirst === '') { 			// do first time things
		if ($arr['tempHigh'] <> ''){
			$foundFirst = 'day';
			$arrDay[]	= langtransstr('Today');
		} else  {
			$foundFirst = 'night';
			$arrDay[]	= langtransstr('Tonight');
		}			
	} else {
		if ($arr['tempHigh'] <> '' ){
			$arrDay[]	= $dayText;
		} else  {
			$arrDay[]	= langtransstr(date('l', ($arr['timestamp']-12*60*60))).'<br />'.langtransstr('night');
		}			
	}
	$notUsed 	= $iconUrl = $iconOut = $iconUrlOut = '';
	wsChangeIcon ('hwa',$arr['iconL'], $iconOut,$arr['iconUrlL'], $iconUrlOut, $notUsed);
	$arrIcon[]	= $iconUrlOut;
	$arrDesc[]	= langtransstr($arr['weatherDescL']);
	$arrTemp[]	= $arr['tempLowNU'].$arr['tempHighNU'];
	$arrRain[]	= $arr['rainNUL'];
	if (isset($arr['rainChance']) ) 	{$arrCoR[]	= $arr['rainChance']; } 	else {$arrCoR[]	= '-'; }
	if (isset($arr['thunderChance']) && $arr['thunderChance'] <> 0) {
		$arrCoT[]	= $arr['thunderChance']; $thunder	= true;
	} else {
		$arrCoT[]	= '-'; 
	}
	if (isset($arr['snowChance']) && $arr['snowChance'] <> 0) { 
		$arrCoS[]	= $arr['snowChance']; $snow	= true;
	} else {
		$arrCoS[]	= '-'; 
	}
	$arrWind[]		= $arr['windSpeedNUL'];
#	$arrWdir[]		= $arr['windDir'];	
	$arrWindIcon[]	= $arr['windDirIcon'];
	$arrBaro[]		= $arr['baroNU'];
}
#print_r($arrDay);
$hwaListTable  .= '</tbody></table>'.PHP_EOL;

$credits		= '<h3 class="blockHead"><small>Dit script (v3) is ontwikkeld door  <a target="_blank" href="http://leuven-template.eu/index.php"> Weerstation Leuven.</a>'.PHP_EOL;
$credits .= ' - Grafieken zijn vervaardigd met behulp van <a target="_blank" href="http://www.highcharts.com">Highcharts.</a>'.PHP_EOL;
if ($SITE['hwaIconsOwn'] == true) {
$credits .= '&nbsp;De iconen zijn van HWA/MC. ';
} else {
$credits .= '&nbsp;De iconen zijn van <a target="_blank" href="http://kde-look.org/content/show.php?content=39988">KDE.</a>'.PHP_EOL;
}
$credits .= '<br />De weergegevens voor deze verwachting komen voort uit de samenwerking van '.$SITE['organ'].' met 
<a target="_blank" href="http://hetweeractueel.nl">Het Weer Actueel &amp; Meteo Consult. </a>
</small></h3>'.PHP_EOL;

$qtipTxtPlain	=	'<script type="text/javascript">'.PHP_EOL.'<!--'.PHP_EOL;
$qtipTxtPlain	.=	'$(document).ready(function() {'.PHP_EOL;
$qtipTxtIcon	=	'tableIconQtip_';
if (count($arrTime) < $topCount) {$end	= count($arrTime); } else {$end	= $topCount;}
$end 		= 2 * floor ($end/2);		// for hwa/mos
$colwidth       = round (80 / (2*$end));
$colTable 	= 2+ 2*$end;		// 
$iconWidth	= 100 / $end;
$tableIcons  ='
<!-- start icon output -->
<table class=" genericTable" style=" background-color: transparent;">
 <tbody>
 <tr>'.PHP_EOL;

$stringColom ='<!-- start colom output -->
<div>
<table class="verwachting" style=" border: none; width: 100%;">
  <tbody>
  <tr class="trLow">';
$width='20%';
for ($x = 0; $x < $colTable; $x++) {
    $stringColom .= '<td style="width: '.$width.';">&nbsp;</td>';
    $width = $colwidth.'%';
}
$stringColom .= '
  </tr>
  <tr>
    <td style="" class="tijd">';

$unixTime 	= $returnArray['request_info'][1]['timestamp'];
$stringColom .= langtransstr('Forecast generated').'<br />'.date($SITE['timeFormat'] , $unixTime).'</td>';
$odd = false;
$colspan=3;
if ($foundFirst == 'night') {$extra = 0;} else {$extra = $end-1;}
for ($i = 0; $i < $end; $i++) {
	if ($odd == true) {
		$odd = false;
		$unixTime = $arrTime [$i];
		if ($i == $extra) {$stringColom .= '<td class="dagNaam row-dark" colspan="1" >'.substr(langtransstr(date('D', $unixTime)),0,3).'<br /><span class="dmj">'.date('j', $unixTime).'</span></td>';}
		elseif ($i == ($end - 1) ) {
		        $unixTime = $arrTime [$i+1];
		        $colspan = 3;
		        $stringColom .= '<td class="dagNaam row-dark" colspan="'.$colspan.'">'.langtransstr(date('l', $unixTime)).'<br /><span class="dmj">'.date($SITE['dateOnlyFormat'], $unixTime).'</span></td>'.PHP_EOL;
		}
	} else {
		$odd = true;	
		$unixTime = $arrTime [$i];
		if ($i == $extra) {
		        $stringColom .= '<td class="dagNaam row-dark" colspan="1" >'.substr(langtransstr(date('D', $unixTime)),0,3).'<br /><span class="dmj">'.date('j', $unixTime).'</span></td>';
		} else {	
		        $stringColom .= '<td class="dagNaam row-dark" colspan="'.$colspan.'">'.langtransstr(date('l', $unixTime)).'<br /><span class="dmj">'.date($SITE['dateOnlyFormat'], $unixTime).'</span></td>'.PHP_EOL;
		}
		$colspan = 4;
		if ($i == ($end - 2) ) {$colspan = 3;}	
	}
	$tableIcons  .=  '<td style="width:'.$iconWidth.'%;">'.$arrDay[$i].'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>
<tr>'.PHP_EOL;
$stringColom .= '<td></td></tr>
  <tr>
    <td class="stationnaam table-top" colspan="'.$colTable.'">'.$returnArray['request_info'][1]['city'].'</td>
  </tr>
<!--  <tr class="">
    <td class="stationurl row-dark" colspan="'.$colTable.'">'.$returnArray['request_info'][1]['url'].'</td>
  </tr>  -->
  <tr>
    <td class="toelichting">'.langtransstr('Forecast').'</td>'.PHP_EOL;;
$odd = false;
if ($foundFirst == 'night') {
	$tekst 			= 'min/max';
	$leftString		= '<td colspan="2" class="lwaarden row-dark">';
	$leftStringBottom	= '<td colspan="2" class="lwaarden row-dark" style = "border-bottom-width: 1px;">';
	$rightString		= '<td colspan="2" class="rwaarden">';
	$rightStringBottom	= '<td colspan="2" class="rwaarden" style = "border-bottom-width: 1px;">';
} else {
        
	$tekst 			= 'max/min';
	$leftString		= '<td colspan="2" class="lwaarden ">';
	$leftStringBottom	= '<td colspan="2" class="lwaarden" style = "border-bottom-width: 1px;">';	
	$rightString		= '<td colspan="2" class="rwaarden row-dark">';
	$rightStringBottom	= '<td colspan="2" class="rwaarden row-dark" style = "border-bottom-width: 1px;">';
}
$tableDesc = '<tr>';
for ($i = 0; $i < $end; $i++) {
	$icon = '<img src="'.$arrIcon[$i].'" alt ="" style="width: 40px;" title="'.$arrDesc[$i].'"/>';
	if ($odd == false) {
		$odd = true;	    
		$stringColom .= $leftString.$icon.'</td>';			
	} else {
		$odd = false;	
		$stringColom .= $rightString.$icon.'</td>';
		$stringColom .= ''.PHP_EOL;
	}
	$tableIcons     .=  '<td style="width:'.$iconWidth.'%;">'.$icon.'</td>'.PHP_EOL;
	$tableDesc      .=  '<td style="width:'.$iconWidth.'%;">'.$arrDesc[$i].'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>'.
$tableDesc.'</tr>
<tr>'.PHP_EOL;
$stringColom .= '
  <td></td></tr>  
  <tr class="rowFigures">
    <td class="toelichting">';
$stringColom .= langtransstr('Temperature'). ' '.langtransstr($tekst).' '.$SITE['uomTemp'].'</td>';
$odd = false;
for ($i = 0; $i < $end; $i++) {
	if ($odd == false) {
		$odd = true;	    
		$stringColom .= $leftString.$arrTemp[$i].'</td>';			
	} else {
		$odd = false;	
		$stringColom .= $rightString.$arrTemp[$i].'</td>';
		$stringColom .= ''.PHP_EOL;
	}
/*	$temp = round($arrTemp[$i]);
	if (strtolower($uomTemp) == 'c') {$colorTemp = $temp + 32;} else {$colorTemp = $temp;}
	if (!$tempSimple) {
		$color	= $tempArray2[$colorTemp];	
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 200%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	} else {
		if ($colorTemp <  32) { $color = 'blue'; } else {$color = 'red';}
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 150%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	}
*/      $tempString     = hwa_commontemp($arrTemp[$i]);
	$tableIcons  .=  '<td>'.$tempString.'</td>'.PHP_EOL;
}
$stringColom .= '
  <td></td></tr>
  <tr class="rowFigures">
    <td class="toelichting">'.langtransstr('Pressure').' ('.$SITE['uomBaro'].')</td>';
$odd = false;
for ($i = 0; $i < $end; $i++) {
	if ($odd == false) {
		$odd = true;	    
		$stringColom .= $leftString.$arrBaro[$i].'</td>';			
	} else {
		$odd = false;	
		$stringColom .= $rightString.$arrBaro[$i].'</td>';
		$stringColom .= ''.PHP_EOL;
	}
}
$tableIcons  .= '</tr>
<tr>'.PHP_EOL;
$stringColom .= '
  <td></td></tr>  
  <tr class="rowFigures">
    <td class="toelichting">'.langtransstr('Amount of rain').' ('.$SITE['uomRain'].')</td>';
$odd = false;
for ($i = 0; $i < $end; $i++) {
	if ($arrCoR[$i] == '-') { 
		$rainFormat = '-'; 
	} else {
		$rainFormat = sprintf ("%01.1f",$arrRain[$i]);
	}
	if ($odd == false) {
		$odd = true;	    
		$stringColom .= $leftString.$rainFormat.'</td>';			
	} else {
		$odd = false;	
		$stringColom .= $rightString.$rainFormat.'</td>';
		$stringColom .= ''.PHP_EOL;
	}
	if ($arrCoR[$i] > 5) {$tableIcons  .=  '<td>'.$arrRain[$i].$SITE['uomRain'].'<br />'.$arrCoR[$i].':%</td>'.PHP_EOL;} else {$tableIcons  .=  '<td>-</td>'.PHP_EOL;}
}
$tableIcons  .= '</tr>
<tr>'.PHP_EOL;
$stringColom .= '
  <td></td></tr>

  <tr class="rowFigures">
    <td class="toelichting">'.langtransstr('Chance of rain').'(%)</td>';
$odd = false;
for ($i = 0; $i < $end; $i++) {
	if ($odd == false) {
		$odd = true;	    
		$stringColom .= $leftString.$arrCoR[$i].'</td>';			
	} else {
		$odd = false;	
		$stringColom .= $rightString.$arrCoR[$i].'</td>';
		$stringColom .= ''.PHP_EOL;
	}
}
$stringColom .= '
  <td></td></tr>';
$found = false; 
for ($i = 0; $i < $end; $i++) {if ($arrCoT[$i] <> '-') {$found = true;} }
if ($thunder == true && $found == true) {
	$stringColom .= '
  <tr class="rowFigures">
	<td class="toelichting">'.langtransstr('Chance of Thunder').'(%)</td>';
	$odd = false;
	for ($i = 0; $i < $end; $i++) {
		if ($odd == false) {
			$odd = true;	    
			$stringColom .= $leftString.$arrCoT[$i].'</td>';			
		} else {
			$odd = false;	
			$stringColom .= $rightString.$arrCoT[$i].'</td>';
			$stringColom .= ''.PHP_EOL;
		}
	}
	$stringColom .= '
  <td></td></tr>';
}
$found = false; 
for ($i = 0; $i < $end; $i++) {if ($arrCoS[$i] <> '-') {$found = true;} } 
if ($snow == true && $found == true) {
	$stringColom .= '
	  <tr class="rowFigures">
		<td class="toelichting">'.langtransstr('Chance of Snow').'(%)</td>';
	$odd = false;
	for ($i = 0; $i < $end; $i++) {
		if ($odd == false) {
			$odd = true;	    
			$stringColom .= $leftString.$arrCoS[$i].'</td>';			
		} else {
			$odd = false;	
			$stringColom .= $rightString.$arrCoS[$i].'</td>';
			$stringColom .= ''.PHP_EOL;
		}
	}
	$stringColom .= '
  </tr>';

} 
$stringColom .= ' 
  <tr class="rowFigures">
    <td class="toelichting">'.langtransstr('Wind direction and force ').'('.$uomWind.')</td>';
$odd = false;
for ($i = 0; $i < $end; $i++) {
	$stringWind = '<img src="'.$SITE['scriptIconsWind'].$arrWindIcon[$i].'.png" alt="" /><br />'.$arrWind[$i];
	if ($odd == false) {
		$odd = true;    
		$stringColom .=  $leftStringBottom.$stringWind.'</td>';			
	} else {
		$odd = false;	
		$stringColom .=  $rightStringBottom.$stringWind.'</td>';	
		$stringColom .=  ''.PHP_EOL;
	}
	$tableIcons  .=  '<td>'.$stringWind.' '.$uomWind.'</td>'.PHP_EOL;	
}
$tableIcons  .= '</tr>
</tbody></table>
<!-- end icon ouptput -->
'.PHP_EOL;

$stringColom .=  ' 
  <td></td></tr>
  <tr>
    <td class="samenwerking" colspan="'.($colTable-1).'"><a class="samenwerking" href="http://www.hetweeractueel.nl/" target="_blank">Samenwerking MeteoConsult/Hetweeractueel.nl</a></td><td>&nbsp;</td>
  </tr>
  <tr class="trLow">
    <td colspan="'.$colTable.'" class="trLow"></td>
  </tr>
</tbody></table>
</div>
<!-- end colom ouptput -->
'.PHP_EOL;
# now we are going to generate the javascript graphs
# calculate Y axis steps for graphs
$graphNrLines	= 6;
$graphTempMin	= $tempMin = floor ($graphTempMin);  // round down
$graphTempMax	= ceil 	($graphTempMax);  // round up
$stringY = '<!-- temp max: '.$graphTempMax. ' temp min: '.$graphTempMin;
$graphTempStep	= 2 * ceil(($graphTempMax - $graphTempMin) / $graphNrLines);
$stringY .= ' temp step: '.$graphTempStep;
#
$graphTempMax	= $graphTempStep * ceil($graphTempMax/$graphTempStep);
$tempMax		= $graphTempMax;
$tempMin		= $tempMin - $graphTempStep;
$graphTempMax	= $graphTempMax	+  $graphTempStep;
$graphTempMin   = $graphTempMax - (1+ $graphNrLines) * $graphTempStep;

$stringY .= '  temp max: '.$graphTempMax.' temp min: '.$graphTempMin;

$graphIconYvalue = $graphTempMax - ($graphTempStep/2);
#$graphIconYvalue = $graphTempMax;

$stringY .= ' icon: '.$graphIconYvalue. ' -->'.PHP_EOL;
#
$rainMax		=  $graphRainMax;
if (preg_match("|mm|",$SITE['uomRain'])) {
	if ($graphRainMax < 4) {$graphRainMax = 4;}
	$graphRainStep	= round (($graphRainMax / $graphNrLines));
	$graphRainMax	= $graphRainStep * $graphNrLines;
} else {
	if ($graphRainMax < 1.3) {$graphRainMax = 14;} else {$graphRainMax = 10 * $graphRainMax;}
	$graphRainStep	= (ceil ($graphRainMax / $graphNrLines))/ 10;
	$graphRainMax	= $graphRainStep * (1+ $graphNrLines);	
}
$graphRainMax	= 2 * $graphRainMax;		// to make sure that the rain graphs are in the lowest part of the grpah
$graphRainStep	= 2 * $graphRainStep;
$rainMax		= $rainMax + $graphRainStep;
$stringY .= '<!-- rain max: '.$graphRainMax.'   rain step: '.$graphRainStep.' -->'.PHP_EOL;
$baroMax		= $graphBaroMax;
$baroMin		= $graphBaroMin;
if (preg_match("|hPa|",$SITE['uomBaro'])  || preg_match("|mb|",$SITE['uomBaro'])) {
	$graphBaroDiff = $graphBaroMax - $graphBaroMin;
	if (ceil($graphBaroDiff / 15) <= $graphNrLines) {$graphBaroStep = 15; } else {$graphBaroStep = 20;}
	$graphBaroMax  = $graphBaroStep + $graphBaroStep * (ceil($graphBaroMax / $graphBaroStep));
	if ($graphBaroMax < 1035) { $graphBaroMax = 1035;}
	$graphBaroMin = $graphBaroMax - (1+$graphNrLines) * $graphBaroStep;
} else {  // inHg
	$graphBaroMax = 32; $graphBaroMin = 28.5; $graphBaroStep = .5;
}
$baroMax		= $baroMax + $graphBaroStep;
$baroMin		= $baroMin - $graphBaroStep;
$stringY .='<!-- baro max: '.$graphBaroMax.' baro min: '.$graphBaroMin.'-->'.PHP_EOL;
#
$windMax		= $graphWindMax + 2;
if ($graphWindMax < $graphNrLines) {$graphWindMax = $graphNrLines;}
$graphWindStep	= ceil ($graphWindMax / $graphNrLines);
$graphWindMax	= $graphNrLines * $graphWindStep;
$graphWindMax	= 24;
$graphWindStep	=  2;
$stringY .='<!-- wind max: '.$graphWindMax.' wind step: '.$graphWindStep.'-->'.PHP_EOL;
echo $stringY;
#
$graphDaysString = '{';
$daysShort	= array ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
$daysLong	= array ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
for ($i = 0; $i < count ($daysShort); $i++) {
	$graphDaysString .= "'$daysShort[$i]':'".langtransstr($daysLong[$i])."',";
}
$graphDaysString = substr($graphDaysString, 0, strlen($graphDaysString) -1);
$graphDaysString .= '}';
$graphsStart 	= 1000 * ($arrTime[0]-6*3600);
$n				= count($arrDay)-1;
$graphsStop		= 1000 * ($arrTime[$n]-2*3600);
$ddays		= '';
#
for($i=0 ; $i<count($graphsDays); $i++) { //  shaded background every other day
	if($i ==  count($graphsDays)-1) {     // last incomplete day
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.($graphsStop +3*3600*1000).', color: "rgba(255, 255, 255, 0.9)" },';
	} else {
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.$graphsDays[$i+1].', color: "rgba(255, 255, 255, 0.9)" },';
	}
	$i++;		// skip next day
}
$negValue = "return '<span style=\"fill: blue;\">' + this.value + '</span>'";
$posValue = "return '<span style=\"fill: red;\">' + this.value + '</span>'";
$degreesymbol   = iconv("UTF-8",$SITE["charset"].'//TRANSLIT', '°');
if (strtolower($uomTemp) == 'f') {$treshold = 32;} else {$treshold = 0;}
$graphPart1='
<script type="text/javascript">
<!--
var days        = '.$graphDaysString.';

var globalX = [{
	type: "datetime",
	min: '.$graphsStart.',
	max: '.$graphsStop.',
	plotBands: ['.substr($ddays, 0, -1).'],
	title: {text: null},
	dateTimeLabelFormats: {day: "%H",hour: "%H"},	
	tickInterval: 6 * 3600 * 1000,	
	gridLineWidth: 0.4,      
	lineWidth: 0,
	labels: {y: 20,style:{fontWeight: \'normal\',fontSize:\'10px\'},
		formatter: function() { 
			var uh = Highcharts.dateFormat("%H", this.value);
			if(uh=="12"){return Highcharts.dateFormat("%H <br />", this.value) + days[Highcharts.dateFormat("%a", this.value)];}
			else{return Highcharts.dateFormat("%H", this.value);}
		}
	}
}];
-->
</script>
';
$graphPart1 .='
<script type="text/javascript">
<!--
var tsv = [];
'.$graphsData.'
var temps = [],
wsps = [],
baros = [],
precs = [],
icos = [];
var arr = ["00","03","06","09","12","15","18","21"];  // for windicon display
for (j = 0; j < tsv.length; j++) {
	var line =[];
	line = tsv[j].split("|");
	if(line[0].length > 0 && parseInt(line[0]) != "undefined"){
    	date = 1000 * parseInt(line[0]);
    	d = new Date (date);
		temps.push([date, parseFloat(line[1])]);
		baros.push([date, parseFloat(line[2])]);
		mkr = "'.$windIconsSmall.'" +line[4]+".png";
		str = {x:date,y:parseFloat(line[3]), marker:{symbol:\'url(\'+mkr+\')\'}};
		wsps.push(str);
		if (line[6] != \'-\') {
			date = 1000 * parseInt(line[5]);
			precs.push([date, parseFloat(line[6])]);
			date = 1000 * parseInt(line[7]);
			mkr = line[8];
			str = {x:date,y:'.$graphIconYvalue.', marker:{symbol:\'url(\'+mkr+\')\'}};
			icos.push(str); 
		}
    } // Line contains correct data           
}; // eo for each tsv

var yTitles 	= {color: "#000000", fontWeight: "bold", fontSize:"10px"};
var yLabels 	= {color: "#4572A7", fontWeight: "bold", fontSize:"8px"};
var yLabelsWind = {color: "#1485DC", fontWeight: "bold", fontSize:"8px"};
var yLabelsBaro = {color: "#9ACD32", fontWeight: "bold", fontSize:"8px"};

$(document).ready(function() {
	Highcharts.setOptions({
		chart: {
		    spacingTop:4,
			renderTo: "placeholder",
			defaultSeriesType: "spline",
			backgroundColor: "rgba(255, 255, 255, 0.4)",
			plotBackgroundColor: {linearGradient: [0, 0, 0, 150],stops: [[0, "#ddd"],[1, "rgba(255, 255, 255, 0.4)"]]},
			plotBorderColor: "#88BCCE",
			plotBorderWidth: 0.5,
			marginRight: 60,
			marginTop: 30,
			marginLeft: 60,
			style: {fontFamily: \'"UbuntuM","Lucida Grande",Verdana,Helvetica,sans-serif\',fontSize:\'11px\'}
		},
		title: {text: ""},
		xAxis: globalX,
		lang: {thousandsSep: ""},
		credits: {enabled: false},
		plotOptions: {
			series: {marker: { radius: 0,states: {hover: {enabled: true}}}},
			spline: {lineWidth: 1.5, shadow: false, cursor: "pointer",states:{hover:{enabled: false}}},
			column: {pointWidth:15},
			areaspline: {lineWidth: 1.5, shadow: false,states:{hover:{enabled: false}}}
		},
		legend: { borderWidth: 0, align: \'center\', verticalAlign: \'top\', rtl: true},
		exporting: {enabled:false},
		tooltip: {
            positioner: function () {return { x: 0};},
			backgroundColor: "#A2D959",
         	borderColor: "#fff",
         	borderRadius: 0,
         	borderWidth: 0,  
         	shared: true,
         	crosshairs: { width: 0.5,color: "#666"},
         	style: {lineHeight: "1.3em",fontSize: "11px",color: "#000"},
         	formatter: function() {
              var s = "" + days[Highcharts.dateFormat(\'%a\', this.x)]+" "+ Highcharts.dateFormat(\'%H:%M\', this.x) +"";
              $.each(this.points, function(i, point) {
				var unit = {
				   "'.langtransstr('Precipation').'": " '.$uomRain.'",
				   "'.langtransstr('Wind').'": " '.$uomWind.'",
				   "'.langtransstr('Temperature').'": "'.$degreesymbol.$uomTemp.'",
				   "'.langtransstr('Pressure').'": " '.$uomBaro.'"
				}[point.series.name];
				if(point.series.name != " ") {
					s += "<br/>"+point.series.name+": <b>"+point.y+unit+"</b>";
				}				     
            });  // eo each
            return s;
         }
      }
	});  // eo set general options
   chartTempData  = new Highcharts.Chart({
        chart: {renderTo: "containerTemp" },		
      	yAxis: [
      	{ lineWidth: 2, 
          gridLineWidth: 0.4, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.', offset: 25,
          title: {text: "'.$degreesymbol.$uomTemp.'", rotation: 0, align:"high", offset: 4, y: 0, style:yTitles},
           labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$tempMin.' || this.value > '.$tempMax.' ){ return ""; } 
          else
          {if (this.value < 0) {'.$negValue.';} else {'.$posValue.';}}
          },style:yLabels}       
      	},
      	{ 
          gridLineWidth: 0, min: 0,max:'.$graphRainMax.',tickInterval:'.$graphRainStep.', offset: 0,
          title: {text: "'.$uomRain.'", rotation: 0, align:"low", offset: 0,x: -30, y: 15, style:yTitles},
          labels: {align: "left", x: -20, y: 1,  formatter: function() {if (this.value < 0 || this.value > '.$rainMax.' ){ return ""; } else {return this.value;}},style:yLabels}
      	},
      	{ 
          gridLineWidth: 0, min: 0, max: 12, tickInterval: '.$graphWindStep.', opposite: true,
          title: {text: "'.$uomWind.'", rotation:0, align:"low", offset: 5,x: 10, y: 15, style:yTitles},      
          labels: {align: "right",x: 25, y: 1, formatter: function() {if (this.value < 0 || this.value > '.$windMax.' ){ return ""; } else {return this.value;}},style:yLabelsWind}      
      	},
      	{ lineWidth: 2, 
          gridLineWidth: 0, min: '.$graphBaroMin.',max: '.$graphBaroMax.',tickInterval: '.$graphBaroStep.',opposite: true, offset: 30,
          title: {text:"'.$uomBaro.'", rotation: 0, align:"high", offset: 20, y: 0, style:yTitles},        
          labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value < '.$baroMin.' || this.value > '.$baroMax.' ){ return ""; } else {return this.value;}},style:yLabelsBaro}
        }
       	],
      	series: [
      		{name: "'.langtransstr('Wind').'", data: wsps,  color:"#EEEE00",yAxis:2, marker:{radius:2,symbol:"circle"}},
      		{name: "'.langtransstr('Pressure').'",data: baros,color: "#9ACD32",yAxis: 3,events:{legendItemClick:false}},
      		{name: "'.langtransstr('Precipation').'",data: precs,color:"#4572A7",type:"column",yAxis:1,events:{legendItemClick:false}},
      		{name: "'.langtransstr('Temperature').'",data: temps,color:"#EE4643", threshold: '.$treshold.', negativeColor: "#4572EE"},
      		{name: " ",color:"#006400",events:{legendItemClick:false},data:icos}
      	]
        });  // eo chart    

}); // eo document ready
-->
</script>'.PHP_EOL;	

#echo '<pre>'; print_r($returnArray); exit;
#echo '<pre>'.$hwaListTable; exit;

# ------------------------------------------------------------------
function myLongDate ($time) {
	global $dateLongFormat, $longDays, $myLongDays, $longMonths, $myLongMonths;
#
	$longDays		= array ("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$longMonths		= array ("January","February","March","April","May","June","July","August","September","October","November","December");
#
	$longDate = date ($dateLongFormat,$time);
	$from	= array();
	$to		= array();
	for ($i = 0; $i < count($longDays); $i++) {
		if (wsfound($longDate,$longDays[$i])) {
			$from[] = $longDays[$i];
			$to[] 	= langtransstr($longDays[$i]);
			break;
		}
	}
	for ($i = 0; $i < count($longMonths); $i++) {
		if (wsfound($longDate,$longMonths[$i])) {
			$from[] = $longMonths[$i];
			$to[] 	= langtransstr($longMonths[$i]);
			break;
		}
	}
	$longDate = str_replace ($from, $to, $longDate);
	return $longDate;
}

function myDateLinePrint($time) {
	global  $SITE, $lat, $long, $rowColor, $timeFormat;
	$srise 	= date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $lat, $long);   // standard time integer
	$sset 	= date_sunset ($time, SUNFUNCS_RET_TIMESTAMP, $lat, $long);
	$dlength= $sset - $srise - 3600;
	$longDate = myLongDate ($time);
	$string='<tr class="dateline '.$rowColor.'"><td colspan="7">
<span style="float:left; position:relative;">&nbsp;<b>'.$longDate.'</b></span>
<span style="float:right;position:relative;">
	<span class="rTxt">
		<img src="'.$SITE['imgDir'].'/sunrise.png" style="width: 24px; height: 12px;" alt="sunrise" />&nbsp;&nbsp;'.date($timeFormat,$srise).'&nbsp;&nbsp;
		<img src="'.$SITE['imgDir'].'/sunset.png"  style="width: 24px; height: 12px;" alt="sunset" />&nbsp;&nbsp;'.date($timeFormat,$sset).'&nbsp;&nbsp;&nbsp;'.
		langtransstr('Daylength').': '.date($timeFormat,$dlength).'&nbsp;
	</span>
</span>
</td></tr>'.PHP_EOL;
	if ($rowColor == 'row-dark') {$rowColor = 'row-light';} else {$rowColor =  'row-dark';}	
	return $string;
}
# Returns whether needle was found in haystack
function wsFound($haystack, $needle){
$pos = strpos($haystack, $needle);
   if ($pos === false) {
   return false;
   } else {
   return true;
   }
}
function hwa_commontemp($value){
	global $uomTemp , $tempArray2, $tempSimple;
	$color                  = 'red';
	$temp                   = round($value);
	$toTemp                 = strtolower($uomTemp);
	if ($toTemp == 'c') {						// for the color lookup we need C as unit
		$colorTemp	= $temp + 32;			// first color entry => -32 C
	} else {
		$colorTemp	= round( 5*($value-32)/9 ) + 32;
	} 
	if (!$tempSimple) {
		if ($colorTemp < 0) {$colorTemp = 0;} 
		elseif ($colorTemp >= count ($tempArray2) )  {$colorTemp = count ($tempArray2) - 1;}
		$color		= $tempArray2[$colorTemp];
		$tempString	= '<span class="myTemp" style="color: '.$color.';" >'.$temp.'&deg;</span>';	
	} else {
		if ($colorTemp <  32) { $color = 'blue'; } else {$color = 'red';}
		$tempString	= '<span class="myTemp" style="font-size: 150%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	}
	return $tempString;
}

?>