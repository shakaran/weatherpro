<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'metnoGenerateHtml.php';
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
$script	= $scriptDir.'metnoSettings.php';
ws_message (  '<!-- module metnoGenerateHtml.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
$script	        = $scriptDir.'metnoCreateArr.php';
ws_message (  '<!-- module metnoGenerateHtml.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
$weather 	= new metnoWeather ();
$returnArray 	= $weather->getWeatherData($latitude,$longitude);
unset($weather);
if (!isset ($returnArray['forecast']) ) {
        ws_message ( '<h3 style="color: red; text-align: center;">Metno forecast: Invalid data returned for part / all of the forecast data - forecast incomplete </h3>',true);
        ws_message ('<pre>'.print_r($returnArray,true).'</pre>');
        return false;
} 
 $end_forecast = count($returnArray['forecast']);
if ($end_forecast < 3 ) {
	ws_message ( '<h3 style="color: red; text-align: center;">Metno forecast: incomplete data returned for part / all of the forecast data</h3>',true);  
	ws_message ('<pre>'.print_r($returnArray,true).'</pre>');
	return false; 
}

#echo '<pre>'; print_r($returnArray); exit;
#-------------------------------------------------------------------------------
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

$lat 			= $latitude;
$long			= $longitude;
$dateTimeFormat         = $timeFormat;
$timeFormat 	        = $timeOnlyFormat;
$dateFormat 	        = $dateOnlyFormat;
$dateLongFormat         = isset($dateLongFormat)? $dateLongFormat : 'l d F Y';
$utcDiff 		= date('Z');// used for graphs timestamps
$srise			= 8;
$sset			= 20;
$dayParts		= array ( metnotransstr('evening'), metnotransstr('night'), metnotransstr('morning'), metnotransstr('afternoon'), );
$nightDayBefore         = true;
# --------------
# text for top of page time/date of updates
$fileTime	= strtotime($returnArray['dates']['filetime']);
$nextUpdate 	= $returnArray['dates']['nextupdateunix'];
$time_update    = metnotransstr('Updated').': '.        metnolongdate ($fileTime).  ' - '.date ($timeFormat,$fileTime);
$time_next      = metnotransstr('Next update').': '.    metnolongdate ($nextUpdate).' - '.date ($timeFormat,$nextUpdate);
$fct_area       = metnotransstr('MetNoForecast.').' '.  metnotransstr ($yourArea);
$wsUpdateTimes = '
<div style="text-align: left; margin:  0px 10px 0px 10px;">
  <span style="float:right;text-align:right;">'.$time_update.'<br />'.$time_next.'</span>
  <h4 style="margin: 0px;">'.metnotransstr('MetNoForecast.').' '.metnotransstr($yourArea).'<br />'.$fct_area.'</h4>
</div>';
#
# we loop through all data and build arrays for the coloms of the output.
$foundFirst	= '';
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
$graphLines     = 0;		// number of javascript data lines
$graphsStop     = 0;
$graphsStart    = 0;
$graphTempMin   = 100;
$graphTempMax   = -100;
$graphBaroMin   = 2000;
$graphBaroMax   = 0;
$graphRainMax   = 0;
$graphWindMax   = 0;
#
$rowColor	= $rowColorDtl  = 'row-dark';
#
$metnoListTable = '';
$metnoListTable .= '<table class="genericTable" style="width: 100%;"><tbody>'.PHP_EOL;
$metnoListHead	= '
<tr class="table-top">
<td>'.metnotransstr('Period').'</td>
<td colspan="2">'.metnotransstr('Forecast').'</td>
<td>'.metnotransstr('Temperature').'</td>
<td>'.metnotransstr('Precipitation').'</td>
<td colspan="2">'.metnotransstr('Wind').'</td>
<td>'.metnotransstr('Humidity').'</td>
<td>'.metnotransstr('Pressure').'</td>
</tr>'.PHP_EOL;
$metnoDetailTable       = '<table class="genericTable" style="width: 100%;"><tbody>'.PHP_EOL;
$metnoDetailHead	= '
<tr class="table-top">
<td>'.metnotransstr('Period').'</td>
<td colspan="2">'.metnotransstr('Forecast').'</td>
<td>'.metnotransstr('Temperature').'</td>
<td>'.metnotransstr('Precipitation').'</td>
<td colspan="2">'.metnotransstr('Wind').'</td>
<td>'.metnotransstr('Humidity').'</td>
<td>'.metnotransstr('Cloud cover').'</td>
<td>'.metnotransstr('Pressure').'</td>
</tr>'.PHP_EOL;
#
$skip 		= true;
$now 		= time();
$oldDay  	= $oldDayDtl = '';
$dataLine       = true;
#echo '<pre>'.PHP_EOL; print_r ($returnArray); echo '</pre>'.PHP_EOL;
$endforecasts   = count($returnArray['forecast']);
for ($i = 0; $i < $endforecasts; $i++) {
	$arr 			= $returnArray['forecast'][$i];
	$arrDateTo 		= substr( $arr['dateTo'],0,10);
	if ($skip == true) {	
		if ($now > $arr['timestamp']) {continue;}	// skip all lines in the past;
		$skip = false;
	}
#
	$strDay	                = date('Y-m-d',$arr['timestamp']);
	if ($oldDay <> $strDay) {		// do we have a new day
		$oldDay  	= $strDay;
		$graphsDays[]	= 1000 * strtotime($strDay.'T00:00:00Z');
		$dataLine	= true;
	} 
	if ($arr['timeFrame'] <> 6) {  // detail table
		if ($strDay <> $oldDayDtl)	{
			$cols	= 10;
			$metnoDetailTable  .= metnodateline($arr['timestamp'], $rowColorDtl);
			$metnoDetailTable  .= $metnoDetailHead;
			$oldDayDtl		= $strDay;
		}
		$metnoDetailTable	.= '<tr class="'.$rowColorDtl.'">'.PHP_EOL;
		if ($rowColorDtl == 'row-dark') {$rowColorDtl = 'row-light';} else {$rowColorDtl =  'row-dark';}
		$to		= date($hourOnlyFormat,$arr['timestamp']);
		$from	        = date($hourOnlyFormat,$arr['timeFrom']);
		if ($to <> $from) {$to = $from.'-'.$to;}
		$rain           = '';
		if (isset ($arr['rainDtl']) && $arr['rainDtl'] <> 0) {
			        $rain = $arr['rainTxtDtl'];
		}
		$temp 		= $arr['tempNU'];
		$tempString	= metnocommontemp($temp);
		$windSpeed	= $arr['windSpeedNU'];
	        list ($value, $color, $tekst) = metnobeaufort($windSpeed,$toWind);
		$tekst		= metnotransstr($tekst);
		$windIcon       = '<img style="width: 32px;" src="'. $iconsWind. $arr['windDirTxt']. '.png" alt=""/>';
		$wind	        = $arr['windSpeedNU'].'&nbsp;'.trim($uomWind).' - '.metnotransstr($arr['windDirTxt']).'<br /><span style="background-color: '.$color.';">'.$tekst.'</span>';
		$humidity	= $arr['hum'].'%';
		if ($arr['timestamp'] < $srise || $arr['timestamp'] > $sset) {$imgstr='n';}  else {$imgstr='d';}	
		if (strlen($arr['iconDtl']) == 1) {$icon ='0'.$arr['iconDtl'].$imgstr;} else {$icon = $arr['iconDtl'].$imgstr;}
                $iconIn         = $icon;
                list ($url, $urlsmall)  = metnoIcon ($iconIn);
                $description    = metnotransstr($arr['weatherDescDtl']);
		$icon = '<img src="'.$url.'" alt ="'.$description.'" title="'.$description.'"/>';
		$metnoDetailTable	.= 	'<td>'.$to.'</td><td>'.$description.'</td>
		<td>'.$icon.'</td><td>'.$tempString.'</td>
		<td>'.$rain.'</td><td>'.$windIcon.'</td>
		<td>'.$wind.'</td><td>'.$humidity.'</td><td>'.floor($arr['clouds']).'%</td><td>'.$arr['baro'].'</td></tr>'.PHP_EOL;	
	}
	if (!isset ($arr['dayPart']) ) {continue;}
#	translate icon	day or night
	if ($arr['dayPart'] <= 1 )  {$imgstr='n';}  else {$imgstr='d';}			// ????
	if (strlen($arr['icon']) == 1) {$arr['icon']='0'.$arr['icon'].$imgstr;} else {$arr['icon']=$arr['icon'].$imgstr;}
# first the javascript graph
# time
	$arrTimeGraph[$graphLines]	= $arr['timestamp']+$utcDiff;
# icon
        $iconIn                         = $arr['icon'];
        list ($url, $urlsmall)          = metnoIcon ($iconIn);	
	$arrIconGraph[$graphLines]	= $urlsmall;
# rain	
	if (!isset ($arr['rain']) ) {
		$value = '-';
	} else {
		$value = $arr['rain'];
		if ($value > $graphRainMax) {$graphRainMax = $value;}
	}
	$arrRainGraph[$graphLines]      = $value;
# baro	
	$value 			        = $arr['baroNU'];
	if ($value > $graphBaroMax) {$graphBaroMax = $value;} 
	if ($value < $graphBaroMin) {$graphBaroMin = $value;} 	
	$arrBaroGraph[$graphLines]	= $value;
# temp
	$value 			        = $arr['tempNU'];
	if ($value > $graphTempMax) {$graphTempMax = $value;}
	if ($value < $graphTempMin) {$graphTempMin = $value;}
	$arrTempGraph[$graphLines]	= $value;
# wind
	$value 			        = $arr['windSpeedNU'];
	if ($value > $graphWindMax) {$graphWindMax = $value;}
	$arrWindGraph[$graphLines]	= $value;
	$arrWdirGraph[$graphLines]	= $arr['windDirTxt'];
# store all javascript data	
	$graphsData	.= 	'tsv['.$graphLines.'] ="'.
					$arrTimeGraph[$graphLines].'|'.
					$arrTempGraph[$graphLines].'|'.
					$arrBaroGraph[$graphLines].'|'.
					$arrWindGraph[$graphLines].'|'.
					$arrWdirGraph[$graphLines].'|'.		
					$arrTimeGraph[$graphLines].'|'.$arrRainGraph[$graphLines].'|'.		
					$arrTimeGraph[$graphLines].'|'.$arrIconGraph[$graphLines].'|";'.PHP_EOL;			
	$graphLines++;
#
# now the yrno list table
	if ($dataLine == true) {
		$cols = 9;
		$metnoListTable  .= metnodateline($arr['timestamp'], $rowColor);
		$metnoListTable  .= $metnoListHead;
		$dataLine = false;
	}
	$metnoListTable  .='<tr class="'.$rowColor.'">'.PHP_EOL;
	if ($rowColor == 'row-dark') {$rowColor = 'row-light';} else {$rowColor =  'row-dark';}
	$to		= date($hourOnlyFormat,$arr['timestamp']);
	$start  = date($hourOnlyFormat,($arr['timestamp'] - 6* 60 *60));
	$period = $start .' - '. $to;
	$rain = '';
	if (isset ($arr['rain']) && $arr['rain'] <> 0) {
		$rain   = $arr['rainTxt'];
	}
	$temp 		= $arr['tempNU'];
	$tempString     = metnocommontemp($temp);
	$windIcon       = '<img style="width: 32px;" src="'. $iconsWind. $arr['windDirTxt']. '.png" alt=""/>';
	$windSpeed	= $arr['windSpeedNU'];
	list ($value, $color, $tekst) = metnobeaufort($windSpeed,$toWind);
        $tekst		= metnotransstr($tekst);
	$wind	        = $arr['windSpeed'].' - '. metnotransstr($arr['windDirTxt']).
	                '<br /><span style="background-color: '.$color.';">'.$tekst.'</span>';
	$humidity	= $arr['hum'].'%';
        $iconIn                 = $arr['icon'];
        list ($url, $urlsmall)  = metnoIcon ($iconIn);
	$description    = metnotransstr($arr['weatherDesc']);
	$icon = '<img src="'.$url.'" alt ="'.$description.'" title="'.$description.'"/>';
	$metnoListTable  .='<td>'.$period.'</td><td>'.$description.'</td>
	<td>'.$icon.'</td><td>'.$tempString.'</td>
	<td>'.$rain.'</td><td>'.$windIcon.'</td><td>'.$wind.'</td><td>'.$humidity.'</td><td>'.$arr['baro'].'</td></tr>'.PHP_EOL;
#
	$arrTime[]	= $arr['timestamp'];

	if ($arr['dayPart'] == 1 && $nightDayBefore) {
		$dayText 	= metnotransstr( date('l', ($arr['timestamp']-27*60*60) ) );
	} else {
		$dayText 	= metnotransstr( date('l', ($arr['timestamp']-3*60*60) ) );
	}
	$dayText2	        = $dayParts[$arr['dayPart']];
	if ($foundFirst === '') { 			// do first time things
		$foundFirst     = 'xx';
		$arrDay[] 	= metnotransstr('this').'<br />'.$dayText2;
	} else {
		$arrDay[]	= $dayText.'<br />'.$dayText2;
	}	
        $iconIn                 = $arr['icon'];
        list ($url, $urlsmall)  = metnoIcon ($iconIn);
	$arrIcon[]		= $url;
	$arrDesc[]		= $description;
	$arrTemp[]		= $arr['tempNU'];
	$arrRain[]		= $arr['rain'];
	$arrRainTxt[]		= $arr['rainTxt'];	
	$arrWind[]		= metnotransstr($arr['windDirTxt']).'-'.$arr['windSpeed'];
#	$arrWdir[]		= $arr['windDir'];	
	$arrWindIcon[]	        = $arr['windDirTxt'];
	$arrBaro[]		= $arr['baroNU'];
#
	$DateLineString = '';
}
if ($skip == true) {
	echo '<h3> No valid input found. All data is in the past.</h3>'.PHP_EOL;
	$metnoERROR	= true;
	return;
}
$metnoERROR	= false;
$metnoListTable  .= '</tbody></table>'.PHP_EOL;
$metnoDetailTable  .= '</tbody></table>'.PHP_EOL;
#
$end            = count($arrTime);
if ($topCount < $end) { $end = $topCount;}
$iconWidth	= 100 / $end;
$newline        = '</tr>
<tr>'.PHP_EOL;
$tableIcons  ='
<!-- start icon output -->
<table class=" genericTable" style=" background-color: transparent;">
 <tbody>
 <tr>'.PHP_EOL;
for ($i = 0; $i < $end; $i++) {
	$tableIcons    .=  '<td style="width: '.$iconWidth.'%;">'.$arrDay[$i].'</td>'.PHP_EOL;
}
$tableIcons    .= $newline;
for ($i = 0; $i < $end; $i++) {
	$icon           = '<img src="'.$arrIcon[$i].'" alt ="'.$arrDesc[$i].'" title="'.$arrDesc[$i].'"/>';
	$tableIcons    .=  '<td style="width: '.$iconWidth.'%;">'.$icon.'</td>'.PHP_EOL;
}
$tableIcons    .= $newline;
for ($i = 0; $i < $end; $i++) {
	$tableIcons    .=  '<td style="width: '.$iconWidth.'%;">'.$arrDesc[$i].'</td>'.PHP_EOL;
}
$tableIcons    .= $newline;
for ($i = 0; $i < $end; $i++) {
	$tempString     = metnocommontemp($arrTemp[$i]);
	$tableIcons    .= '<td>'.$tempString.'</td>'.PHP_EOL;
}
$tableIcons    .= $newline;
for ($i = 0; $i < $end; $i++) {
	if ($arrRain[$i] == 0) {$rain = '-';} else {$rain = $arrRainTxt[$i];}
	$tableIcons    .=  '<td>'.$rain.'</td>'.PHP_EOL;
}
$tableIcons    .= $newline;
for ($i = 0; $i < $end; $i++) {
	$stringWind     = '<img src="'.$iconsWind.$arrWindIcon[$i].'.png" style="width: 32px;" alt="" /><br />'.$arrWind[$i];
	$tableIcons    .= '<td>'.$stringWind.'</td>'.PHP_EOL;	
}
$tableIcons  .= '</tr>
</tbody></table>
<!-- end icon ouptput -->
'.PHP_EOL;
# now we are going to generate the javascript graphs
# calculate Y axis steps for graphs
$graphNrLines	= 6;
$graphTempMin	= $tempMin = floor ($graphTempMin);  // round down
$graphTempMax	= ceil 	($graphTempMax);  // round up
$stringY = 'temp max: '.$graphTempMax. ' temp min: '.$graphTempMin;
$graphTempStep	= 2* ceil(($graphTempMax - $graphTempMin) / $graphNrLines);
$stringY .= ' temp step: '.$graphTempStep;
/*
if ($graphTempMin < 0) {
	$result = abs($graphTempMin) / $graphTempStep;
	$result = ceil ($result);
	$graphTempMin = -1 * $result * $graphTempStep;
} else {
	$result = floor ($graphTempMin / $graphTempStep );
	$graphTempMin = $result * $graphTempStep;
}
*/
$graphTempMax	= $graphTempStep * ceil($graphTempMax/$graphTempStep);
$tempMax		= $graphTempMax;
$tempMin		= $tempMin - $graphTempStep;
$graphTempMax	= $graphTempMax	+  $graphTempStep;
$graphTempMin   = $graphTempMax - (1+ $graphNrLines) * $graphTempStep;

$stringY .= '  temp max: '.$graphTempMax.' temp min: '.$graphTempMin;

$graphIconYvalue = $graphTempMax - ($graphTempStep/2);
#$graphIconYvalue = $graphTempMax;

$stringY .= ' icon: '.$graphIconYvalue.PHP_EOL;
#
$rainMax		=  $graphRainMax;
if (preg_match("|mm|",$uomRain)) {
	if ($graphRainMax < 3.5) {$graphRainMax = 3.5;}
	$graphRainStep	= round (($graphRainMax / $graphNrLines),0);
	$graphRainMax	=  $graphRainStep * $graphNrLines;
} else {
	if ($graphRainMax < 1.3) {$graphRainMax = 14;} else {$graphRainMax = 10 * $graphRainMax;}
	$graphRainStep	= (ceil ($graphRainMax / $graphNrLines))/ 10;
	$graphRainMax	= $graphRainStep * $graphNrLines;	
}

$graphRainMax	= $graphRainMax	* 2;
$graphRainStep	= $graphRainStep * 2;
$rainMax		= $rainMax + $graphRainStep;
$stringY .= 'rain max: '.$graphRainMax.'   rain step: '.$graphRainStep.PHP_EOL;
$baroMax		= $graphBaroMax;
$baroMin		= $graphBaroMin;
if (preg_match("|hPa|",$uomBaro)  || preg_match("|mb|",$uomBaro)) {
	$graphBaroDiff = $graphBaroMax - $graphBaroMin;
	if (ceil($graphBaroDiff / 15) <= $graphNrLines) {$graphBaroStep = 15; } else {$graphBaroStep = 20;}
	$graphBaroMax  = $graphBaroStep * (ceil($graphBaroMax / $graphBaroStep));
	if ($graphBaroMax < 1035) { $graphBaroMax = 1035;}
	$graphBaroMin = $graphBaroMax - $graphNrLines * $graphBaroStep;
} else {  // inHg
	$graphBaroMax = 32; $graphBaroMin = 28.5; $graphBaroStep = .5;
}
$baroMax		= $baroMax + $graphBaroStep;
$baroMin		= $baroMin - $graphBaroStep;
$stringY .='baro max: '.$graphBaroMax.' baro min: '.$graphBaroMin.PHP_EOL;
if ($graphWindMax < $graphNrLines) {$graphWindMax = $graphNrLines;}
$graphWindStep = ceil ($graphWindMax / $graphNrLines);
$graphWindMax  = $graphNrLines * $graphWindStep;
$windMax		= $graphWindMax;
$graphWindMax  = $graphWindMax	* 2;
$graphWindStep = $graphWindStep * 2;
$stringY .='wind max: '.$graphWindMax.' wind step: '.$graphWindStep.PHP_EOL;
ws_message (  '<!-- module metnoGenerateHtml.php ('.__LINE__.'): '.PHP_EOL.$stringY.' -->');
#
$graphDaysString = '{';
$daysShort	= array ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
$daysLong	= array ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
for ($i = 0; $i < count ($daysShort); $i++) {
	$graphDaysString .= "'$daysShort[$i]':'".metnotransstr($daysLong[$i])."',";
}
$graphDaysString = substr($graphDaysString, 0, strlen($graphDaysString) -1);
$graphDaysString .= '}';
$graphsStart 	= 1000 * ($arrTime[0] - 3600 + $utcDiff);
$n				= count($arrDay)-1;
$graphsStop		= 1000 * ($arrTime[$n] + 3600);
$ddays		= '';
#
for($i=1 ; $i<count($graphsDays); $i++) { //  shaded background every other day
	if($i ==  count($graphsDays)-1) {     // last incomplete day
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.($graphsStop).', color: "rgba(255, 255, 255, 0.9)" },';
	} else {
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.$graphsDays[$i+1].', color: "rgba(255, 255, 255, 0.9)" },';
	}
	$i++;		// skip next day
}
$from = array ('&deg;',' ','/');
$uomRain = str_replace ($from,'',$uomRain);
$uomTemp = str_replace ($from,'',$uomTemp);
$uomBaro = str_replace ($from,'',$uomBaro);
$uomWind = str_replace ($from,'',$uomWind);
$negValue = "return '<span style=\"fill: blue;\">' + this.value + '</span>'";
$posValue = "return '<span style=\"fill: red;\">' + this.value + '</span>'";
$treshold = 0;
$degreesymbol   = iconv("UTF-8",$charset.'//TRANSLIT', '°');
if ($uomTemp == "F") {$treshold = 32;}

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
for (j = 0; j < tsv.length; j++) {
	var line =[];
	line = tsv[j].split("|");
	if(line[0].length > 0 && parseInt(line[0]) != "undefined"){
    	date = 1000 * parseInt(line[0]);
    	d = new Date (date);
		temps.push([date, parseFloat(line[1])]);
		baros.push([date, parseFloat(line[2])]);
		mkr = "'.$iconsWindSmall.'" +line[4]+".png";
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
         	borderRadius: 3,
         	borderWidth: 0,  
         	shared: true,
         	crosshairs: { width: 0.5,color: "#666"},
         	style: {lineHeight: "1.3em",fontSize: "11px",color: "#000"},
         	formatter: function() {
              var s = "" + days[Highcharts.dateFormat(\'%a\', this.x)]+" "+ Highcharts.dateFormat(\'%H:%M\', this.x) +"";
              $.each(this.points, function(i, point) {
				var unit = {
				   "'.metnotransstr('Precipitation').'": " '.$uomRain.'",
				   "'.metnotransstr('Wind').'": " '.$uomWind.'",
				   "'.metnotransstr('Temperature').'": "'.$degreesymbol.$uomTemp.'",
				   "'.metnotransstr('Pressure').'": " '.$uomBaro.'"
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
        chart: { renderTo: "containerTemp" },		
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
          gridLineWidth: 0, min: 0, max: '.$graphWindMax.', tickInterval: '.$graphWindStep.', opposite: true,
          title: {text: "'.$uomWind.'", rotation:0, align:"low", offset: 5,x: 0, y: 15, style:yTitles},      
          labels: {align: "right",x: 25, y: 1, formatter: function() {if (this.value < 0 || this.value > '.$windMax.' ){ return ""; } else {return this.value;}},style:yLabelsWind}      
      	},
      	{ lineWidth: 2, 
          gridLineWidth: 0, min: '.$graphBaroMin.',max: '.$graphBaroMax.',tickInterval: '.$graphBaroStep.',opposite: true, offset: 30,
          title: {text:"'.$uomBaro.'", rotation: 0, align:"high", offset: 20, y: 0, style:yTitles},        
          labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value < '.$baroMin.' || this.value > '.$baroMax.' ){ return ""; } else {return this.value;}},style:yLabelsBaro}
        }
       	],
      	series: [

      		{name: "'.metnotransstr('Pressure').'",data: baros,color: "#9ACD32",yAxis: 3},
      		{name: "'.metnotransstr('Precipitation').'",data: precs,color:"#4572A7",type:"column",yAxis:1},
      		{name: "'.metnotransstr('Temperature').'",data: temps,color:"#EE4643", threshold: '.$treshold.', negativeColor: "#4572EE"},
      		{name: "'.metnotransstr('Wind').'", data: wsps,  color:"#1485DC",type: "scatter",yAxis:2, marker:{radius:2,symbol:"circle"}},
      		{name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos}
      	]
        });  // eo chart    
}); // eo document ready
-->
</script>'.PHP_EOL;	
$logoMetYr = '<img src="'.$imgDir.'met.no_logo2_eng_250px.jpg" style="height: 30px; margin: 4px 4px 4px 4px;" alt="Met.No - Yr.No logo"/>';
$creditString= '
<div style="width: 100%;">
<table style="width: 100%;"><tr><td>'.$logoMetYr.'</td><td>
<small style="text-align: center;">Meteogram and script(v3) developed by <a target="_blank" href="http://leuven-template.eu"> WeerstationLeuven</a>.&nbsp;&nbsp;
Graphs are drawn using <a target="_blank"  href="http://www.highcharts.com">Highcharts</a><br />
Weather <a target="new" href="http://www.yr.no/?lang=en">forecast</a> from met.no, 
delivered by the Norwegian Meteorological Institute and the NRK. </small></td>';
if ($wsDebug) { $creditString .= '<td><small>
Data for '.$returnArray['dates']['location'].'<br />'.$returnArray['dates']['filetime'].'
</small></td>';}
$creditString .= '</tr></table>
</div>';

#echo '<pre>'; print_r($returnArray); exit;
#echo '<pre>'.$metnoListTable ; exit;

# ------------------------------------------------------------------
#
# ------------------------------------------------------------------
function metnocommontemp($value){
	global $toTemp , $tempArray2, $tempSimple;
	$color                  = 'red';
	$temp                   = round($value);
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
#-----------------------------------------------------------------------
#
#-----------------------------------------------------------------------
function metnolongdate ($time) {
	global $dateLongFormat;
	$longDays	= array ("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$longMonths	= array ("January","February","March","April","May","June","July","August","September","October","November","December");
#
	$longDate       = date ($dateLongFormat,$time);
	$from	        = array();
	$to		= array();
	for ($i = 0; $i < count($longDays); $i++) {
		if (scriptfound($longDate,$longDays[$i])) {
			$from[] = $longDays[$i];
			$to[] 	= metnotransstr($longDays[$i]);
			break;
		}
	}
	for ($i = 0; $i < count($longMonths); $i++) {
		if (scriptfound($longDate,$longMonths[$i])) {
			$from[] = $longMonths[$i];
			$to[] 	= metnotransstr($longMonths[$i]);
			break;
		}
	}
	$longDate       = str_replace ($from, $to, $longDate);
	return $longDate;
}
#-----------------------------------------------------------------------
#
#-----------------------------------------------------------------------
function metnodateline($time, &$rowColor) {
	global  $latitude, $longitude, $timeFormat, $imgDir, $srise, $sset, $cols; 
	$srise 	        = date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude);   // standard time integer
	$sset 	        = date_sunset ($time, SUNFUNCS_RET_TIMESTAMP, $latitude, $longitude);
	$dlength        = $sset - $srise;
	$dlengthHr      = floor ($dlength /3600);
	$dlengthMin     = round (($dlength - (3600 * $dlengthHr) ) / 60);
	$strDayLength   = $dlengthHr.':'. substr('00'.$dlengthMin,-2);
	$longDate       = metnolongdate ($time);
	$string='<tr class="dateline '.$rowColor.'"><td colspan="'.$cols.'">
<span style="float:left; position:relative;">&nbsp;<b>'.$longDate.'</b></span>
<span style="float:right;position:relative;">
	<span class="rTxt">
		<img src="'.$imgDir.'/sunrise.png"  alt="sunrise" />&nbsp;&nbsp;'.date($timeFormat,$srise).'&nbsp;&nbsp;
		<img src="'.$imgDir.'/sunset.png"   alt="sunset" />&nbsp;&nbsp;'.date($timeFormat,$sset).'&nbsp;&nbsp;&nbsp;'.
		metnotransstr('Daylength').': '.$strDayLength.'&nbsp;
	</span>
</span>
</td></tr>'.PHP_EOL;
	if ($rowColor == 'row-dark') {$rowColor = 'row-light';} else {$rowColor =  'row-dark';}	
	return $string;
}