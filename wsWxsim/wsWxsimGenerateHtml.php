<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsWxsimGenerateHtml.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-05-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.01 2015-05-30 2.7 release version 
#-----------------------------------------------------------------------------------------
# To do: 
#
#-----------------------------------------------------------------------------------------
#  
$degree_symbol  = '�';
if (isset ($SITE['charset']) && $SITE['charset'] == 'UTF-8') {
        $degree_symbol = iconv('WINDOWS-1252','UTF-8',$degree_symbol);
}
?>
<script type="text/javascript">
<!--
$(document).ready(function(){
$.fn.qtip.defaults = $.extend(true, {}, $.fn.qtip.defaults, {
	style: {background: '#A2D959', color: 'black', textAlign: 'left', width: 500,
			border: { width: 7, radius: 5, color: '#A2D959'},
   			tip: 'bottomLeft'
},
  position: {   corner: {target: 'leftTop', tooltip: 'bottomLeft'}, adjust:{y: -10, screen: true}  },
  show: {event: 'mouseover',delay: 20, solo: true},
  hide: {event: 'mouseleave',fixed: true}
});
});
//-->
</script>
<?php
# --------------
# text for top of page time/date of updates
$wsUpdateTimes  = '<div style="text-align: left; margin:  0px 10px 10px 10px;"><span style="float:right;text-align:right;">';
$wsUpdateTimes .= langtransstr('Updated').': ';			// comes from cache time/filetime of plaintext.txt file
$wsUpdateTimes .= myLongDate ($fileTime).' - '.date ($timeFormat,$fileTime).'<br />'.PHP_EOL;
# create next update time
$fileMin 	= date('i',$fileTime);
$fileTime	= $fileTime - 60*$fileMin;
$plaintextTime 	= $wsWxsimPlain[0]['updateInt'];
#echo '<!--  updated = '.$wsWxsimPlain[0]['updateTxt'].' time = '.$plaintextTime.' -->'.PHP_EOL;

$nextHour 	= $updateHour[0];
$previousHour	= date('G', $fileTime);
$searchHour     = date('G');
if ($previousHour == $searchHour ) {$searchHour++;}
for ($i = 0; $i < count($updateHour); $i++) {
	if ($updateHour[$i] >= $searchHour) {$nextHour = $updateHour[$i]; break;}
}
if ($previousHour >= $nextHour) {$nextDay = 1;} else {$nextDay = 0;}
# $now = 2012-01-05 00:00:00
#  updateTime $now  updatehour[i]:00
#
$nextUpdate     = (int) $fileTime - $previousHour * 60 * 60 + $nextHour * 60 * 60 + $nextDay*24*60*60 + $updateMin * 60;

$wsUpdateTimes .= langtransstr('Next update').': '.myLongDate ($nextUpdate).' - '.date ($timeFormat,$nextUpdate).'</span>'.PHP_EOL;
$wsUpdateTimes .= '<h4 style="margin: 0px;">'.langtransstr('wsWxsimForecast.').' '.langtransstr($SITE['yourArea']);
if ($displayTimes) { $wsUpdateTimes .= '<br />'; } else { $wsUpdateTimes .= '&nbsp;&nbsp;&nbsp;';}
$wsUpdateTimes .= langtransstr('by:').' '.langtransstr($SITE['organ']).'</h4>'.PHP_EOL;
$wsUpdateTimes .='</div>'.PHP_EOL;
#
#-------------------------------------------------------------------------------------
$startHr	= date('YmdH',time());  // to skip 
$dayStarts 	= 8;		// will be set for every day in dayline
$nightStarts 	= 18;		// used for day night icons
$utcDiff 	= date('Z');    // used for graphs timestamps
if (!isset($SITE['hourOnlyFormat']) ) {$SITE['hourOnlyFormat']	= 'H';	}
#
# array of extreme temperatures     temparray2 starts at -32C, so add 30 to C temp
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
$arrIconsWXSIM = array(		// calculated icon Default to WXSIM (yrno) icon
'000'  => '01d',
'000n' => '01n',	
'100'  => '02d',	'110'  => '05d',	'111'  => '05d',	'112'  => '05d',
'100n' => '02n',	'110n' => '05n',	'111n' => '05n',	'112n' => '05n',
'200'  => '02d',	'210'  => '09',		'211'  => '09',		'212'  => '09',
'200n' => '02n',	'210n' => '09',		'211n' => '09',		'212n' => '09',
'300'  => '03d',	'310'  => '09',		'311'  => '09',		'312'  => '09',
'300n' => '03n',	'310n' => '09',		'311n' => '09',		'312n' => '09',
'400'  => '04',		'410'  => '09',		'411'  => '09',		'412'  => '09',
'400n' => '04',		'410n' => '09',		'411n' => '09',		'412n' => '09',
'120'  => '08d',	'121'  => '08d',	'122'  => '13',
'120n' => '08n',	'121n' => '08n',	'122n' => '13',
'220'  => '13',		'221'  => '13',		'222'  => '13',
'220n' => '13',		'221n' => '13',		'222n' => '13',
'320'  => '13',		'321'  => '13',		'322'  => '13',
'320n' => '13',		'321n' => '13',		'322n' => '13',
'420'  => '13',		'421'  => '13',		'422'  => '13',
'420n' => '13',		'421n' => '13',		'422n' => '13',
'130'  => '12',		'131'  => '12',		'132'  => '12',
'130n' => '12',		'131n' => '12',		'132n' => '12',
'230'  => '12',		'231'  => '12',		'232'  => '12',
'230n' => '12',		'231n' => '12',		'232n' => '12',
'330'  => '12',		'331'  => '12',		'332'  => '12',
'330n' => '12',		'331n' => '12',		'332n' => '12',
'430'  => '12',		'431'  => '12',		'432'  => '12',
'430n' => '12',		'431n' => '12',		'432n' => '12',
'140'  => '11',		'141'  => '11',		'142'  => '11',
'140n' => '11',		'141n' => '11',		'142n' => '11',
'240'  => '11',		'241'  => '11',		'242'  => '11',
'240n' => '11',		'241n' => '11',		'242n' => '11',
'340'  => '11',		'341'  => '11',		'342'  => '11',
'340n' => '11',		'341n' => '11',		'342n' => '11',
'440'  => '11',		'441'  => '11',		'442'  => '11',
'440n' => '11',		'441n' => '11',		'442n' => '11',
'150'  => '15',		'151'  => '15',		'152'  => '15',
'150n' => '15',		'151n' => '15',		'152n' => '15',
'250'  => '15',		'251'  => '15',		'252'  => '15',
'250n' => '15',		'251n' => '15',		'252n' => '15',
'350'  => '15',		'351'  => '15',		'352'  => '15',
'350n' => '15',		'351n' => '15',		'352n' => '15',
'450'  => '15',		'451'  => '15',		'452'  => '15',
'450n' => '15',		'451n' => '15',		'452n' => '15',
'600'  => 'windy', '700'  => 'cold',  '701' => 'hot', '800' => 'road' , '900' => 'extreme','901' => 'dunno',
);
# ----------------------- definitions for icon table 
$printIcons	= array ('TimeTxt', 'IconTxt', 'PrecipPlain', 'Temperature', 'Wind', 'UV_Index');
# ----------------------- definitions for plaintext table 
$printPlain[]	= array ('column' => 'TimeTxt', 	'qtip' => 1);
$printPlain[]	= array ('column' => 'IconTxt', 	'qtip' => '');
$printPlain[]	= array ('column' => 'Temperature',	'qtip' => 3);
$printPlain[]	= array ('column' => 'Wind', 		'qtip' => 5);
$printPlain[]	= array ('column' => 'PrecipPlain',	'qtip' => 8);
$printPlain[]	= array ('column' => 'UV_Index',	'qtip' => 7);
$printPlain[]	= array ('column' => 'Description',	'qtip' => '');
$qtipPlain		= array ('ConditionsTxt' => 2, 'Gust' => 6 );
# ----------------------- create  plaintext table to be printed as first tab
# some initializations
$iconTable	= array();
$qtipIdPlain	= 'tablePlainQtip_';
$qtipIdIcon	= 'tableIconQtip_';
$qtipTxtPlain	= '<script type="text/javascript">'.PHP_EOL.'<!--'.PHP_EOL;
$qtipTxtPlain  .= '$(document).ready(function() {'.PHP_EOL;
$qtipTxtIcon	= $qtipTxtPlain;
$tablePlain  	= '<table class=" tabbertab genericTable" style="width: 100%;">'.PHP_EOL.'<tr class="table-top">';
# print column headings
$columns        = count($printPlain);
for ($o=0; $o < $columns; $o++) {
	$arr 	        = $printPlain[$o];
	$column         = $arr['column'];
	$labelPlain	= call_user_func ('my'.$column);
	$tablePlain    .= $labelPlain;
}
$tablePlain    .= '</tr>'.PHP_EOL;
#$string = '<tr style="height: 3px;"><td colspan="99"></td></tr>'.PHP_EOL;
unset($n);
$string         = myDateLinePrint($plaintextTime);

$tablePlain    .= $string;
# echo '<pre>'.PHP_EOL; print_r ($wsWxsimPlain);  exit;
#
# print every line
$count 		= count ($wsWxsimPlain);	// nr of lines in converted plaintext.txt 
$fromLT 	= array ('&lt;','&gt;');	// to replace < and > symbol with words when possible
$toLT   	= array (langtransstr('less than').' ',langtransstr('more than').' ');
#echo '<pre><!-- '; print_r($longDays); echo ' -->'.PHP_EOL;
for ($i = 0; $i < $count; $i++) {
	$array 	= $wsWxsimPlain[$i];		// put 1 line of plaintext in array
	$needle = $array['dayPart'];
	unset ($n);
	if (in_array($needle, $longDays) ) {
		$plaintextTime = $plaintextTime + 24*60*60;  // new day		
		$tablePlain 	.= myDateLinePrint($plaintextTime);
#		$array['time']=langtransstr('daytime');
#		if (isset ($wsWxsimPlain[$i+1]) ) {$wsWxsimPlain[$i+1]['time'] = langtransstr('overnight');}
	}
	if (isset ($SITE['charset']) && $SITE['charset'] == 'UTF-8') {
                $array['time'] = iconv('WINDOWS-1252','UTF-8',$array['time']);
                $array['text'] = iconv('WINDOWS-1252','UTF-8',$array['text']);
                $array['cond'] = iconv('WINDOWS-1252','UTF-8',$array['cond']);
        }
	$iconTable[$i]['qtip']	= $qtipIdIcon.$i;
	$qtipTxtPlain	.= '$("#'.$qtipIdPlain.$i.'").qtip({ style: { width: 300 }, content: \'<table style="width: 100%;">';
	$qtipTxtIcon	.= '$(".'.$qtipIdIcon.$i.'").qtip({ style: { width: 300 }, content: \'<table style="width: 100%;">';
	$tablePlain 	.=	'<tr class="row-dark" id="'.$qtipIdPlain.$i.'">';
	$from = array ('<td>','</td>','<td style="min-width: 60px;">', '<td style="min-width: 30%;">',);
	for ($o=0; $o < $columns; $o++) {			// loop all columns to be printed
		$arr 		= $printPlain[$o];
		$column 	= $arr['column'];
		list ($tableValue, $qtipValue) = call_user_func ('my'.$column, $array);
		if (in_array ($column, $printIcons)) {
			$string  = str_replace ($from, '', $tableValue);
			if ($column == 'PrecipPlain') {
				if ($string <> $noRain ) {
					$arrText = explode (' ', $string);
					$last	= count($arrText)-1;
					$string = $arrText[0].' '.$arrText[2].' />'.$arrText[$last];
				} else {$string = '';}
			}
			$iconTable[$i][$column]	= $string;}
		if ($column == 'PrecipPlain') {$tableValue = str_replace ($fromLT, $toLT, $tableValue);}
		$tablePlain .= $tableValue;
		$nr = $arr['qtip'];			        // load pointer to qtip if needed
		if ($nr <> '') {				
			$qtipValuesPlain[$nr] = $qtipValue;     // $qtipValue;		
		}	
	}  // eo loop all columns in table
	$tablePlain .= '</tr>'.PHP_EOL;
	
	foreach ($qtipPlain as $key => $value) {		// loop all remaining qtip fields in this table
		list ($tableValue, $qtipValue) = call_user_func ('my'.$key, $array);	
		$qtipValuesPlain[$value] = $qtipValue;
	}		
	$string  = '<tr>'.$qtipValuesPlain[1].$qtipValuesPlain[2].'</tr>';
	$string .= '<tr><td colspan="2"><hr /></td></tr>';
	for ($p = 3; $p <= 20; $p++){  // generate content of qtip for this row in this table 
		if (isset ($qtipValuesPlain[$p]) ){
			$string .= '<tr>';
			$string .= $qtipValuesPlain[$p];
			$string .= '</tr>';
		} 
	}
	$qtipTxtPlain  .= $string.'</table>\'});'.PHP_EOL;
	$qtipTxtIcon   .= $string.'</table>\'});'.PHP_EOL;	
}
$tablePlain    .= '</table>'.PHP_EOL;;
$qtipTxtPlain  .= '});'.PHP_EOL.'-->'.PHP_EOL.'</script>'.PHP_EOL;
$qtipTxtIcon   .= '});'.PHP_EOL.'-->'.PHP_EOL.'</script>'.PHP_EOL;
#
$tableIcons     = '<div style=" margin: 5px 0px;">'.PHP_EOL;
#
$tableIcons    .= '<table class=" genericTable" style=" background-color: transparent; ">'.PHP_EOL;
if ($topCount > count($iconTable)) {$topCount = count($iconTable);}
$tableIconsTdWidth = 100 / $topCount;

for ($n = 0; $n < count($printIcons); $n++) {
	$tableIcons            .= '<tr >';
	for ($i = 0; $i < $topCount ; $i++) {
		$tableIcons    .= '<td style="width:'.$tableIconsTdWidth.'%;" class= "'.$iconTable[$i]["qtip"].'">';
		$key            = $printIcons[$n];
		$tableIcons    .= $iconTable[$i][$key];
		$tableIcons    .= '</td>';
	}  // eo numer of columns
	$tableIcons            .= '</tr>'.PHP_EOL;
}  // eo number of rows
$tableIcons .= '</table>'.PHP_EOL.'</div>'.PHP_EOL;
# echo $tableIcons;
# echo $qtipTxtIcon;
if (isset ($iconsOnly) ) {return;}
$rain 		= array();
$tempMin	= array();
$i              = 0;
$stringNotPresent='';
$tableName[$i]		= langtransstr('Forecast by 3 hour intervals');
$printTable[$i][] 	= array ('column' => 'Time', 		        'qtip' => 1);
$printTable[$i][] 	= array ('column' => 'Icon', 		        'qtip' => '');
$printTable[$i][] 	= array ('column' => 'Temperature',	        'qtip' => 3);
if (isset ($wsWxsimArray[0]['windSpeed'])) {
	$printTable[$i][] 	= array ('column' => 'Wind', 	        'qtip' => 9);
	if (!isset ($wsWxsimArray[0]['windDir'])) {$stringNotPresent .= '$array["windDir"] = "N";';}
} else {
	$stringNotPresent .= '$array["windSpeed"] = 0;';
	if (isset ($wsWxsimArray[0]['windDir'])) {
		$printTable[$i][] 	= array ('column' => 'Wind',    'qtip' => 9);		
	} else {$stringNotPresent .= '$array["windDir"] = "N";';}
}
if (isset ($wsWxsimArray[0]['rain'])) {
	$printTable[$i][] 	= array ('column' => 'Precipation',	'qtip' => 13);
} else {$stringNotPresent .= '$array["rain"] = 0;';}
if ($wsWxsimArray[0]['snow'] == 1) {
	$printTable[$i][] 	= array ('column' => 'Snow',	        'qtip' => 15);	
} else {$stringNotPresent .= '$array["snow"] = 0;';}
if (isset ($wsWxsimArray[0]['baro'])) {
	$printTable[$i][] 	= array ('column' => 'Pressure',	'qtip' => 11);
} else {$stringNotPresent .= '$array["baro"] = 0;';}
if (isset ($wsWxsimArray[0]['UV'])) {
	$printTable[$i][] 	= array ('column' => 'UV_Index',	'qtip' => 7);
} else {$stringNotPresent .= '$array["UV"] = 0;';}
$printTable[$i][] 	= array ('column' => 'Conditions',              'qtip' => 2);
$qtipTable[$i] 		= array ('Conditions' => 2, 'Chill' => 4, 'TempMin' => 5, 'TempMax' => 6, 'Dewpoint' => 8, 'Gust' => 10, 'Thunder' => 14 );
$printHours[$i]		= 3;		// nr of hours in every row printed
$printHoursMax[$i]	= 999;		// nr of hours to be printed  999 = all lines
$rowColor[$i]		= 'row-light';
$i++;
$tableName[$i]		= langtransstr('48 hours details');
$printTable[$i][] 	= array ('column' => 'Time', 		        'qtip' => 1);
$printTable[$i][] 	= array ('column' => 'Icon', 		        'qtip' => '');
$printTable[$i][] 	= array ('column' => 'Temperature',	        'qtip' => 3);
if (isset ($wsWxsimArray[0]['windSpeed'])) {
	$printTable[$i][] 	= array ('column' => 'Wind', 	        'qtip' => 9);
	if (!isset ($wsWxsimArray[0]['windDir'])) {$stringNotPresent .= '$array["windDir"] = "N";';}
} else {
	$stringNotPresent .= '$array["windSpeed"] = 0;';
	if (isset ($wsWxsimArray[0]['windDir'])) {
		$printTable[$i][] 	= array ('column' => 'Wind',    'qtip' => 9);		
	} else {$stringNotPresent .= '$array["windDir"] = "N";';}
}
if (isset ($wsWxsimArray[0]['rain'])) {
	$printTable[$i][] 	= array ('column' => 'Precipation',	'qtip' => 13);
} else {$stringNotPresent .= '$array["rain"] = 0;';}
if ($thunderProb) {
	$printTable[$i][] 	= array ('column' => 'Thunder', 	'qtip' => 14);
} 
$printTable[$i][] 	= array ('column' => 'Dewpoint', 	        'qtip' => 8);
if (isset ($wsWxsimArray[0]['baro'])) {
	$printTable[$i][] 	= array ('column' => 'Pressure',	'qtip' => 11);
} else {$stringNotPresent .= '$array["baro"] = 0;';}
if (isset ($wsWxsimArray[0]['hum'])) {
	$printTable[$i][] 	= array ('column' => 'Humidity',	'qtip' => 12);
} else {$stringNotPresent .= '$array["hum"] = 0;';}
if (isset ($wsWxsimArray[0]['UV'])) {
	$printTable[$i][] 	= array ('column' => 'UV_Index',	'qtip' => 7);
} else {$stringNotPresent .= '$array["UV"] = 0;';}
if (isset ($wsWxsimArray[0]['skyCover'])) {
	$printTable[$i][] 	= array ('column' => 'Cloud_cover',	'qtip' => 16);
} else {$stringNotPresent .= '$array["skyCover"] = 0;';}
if (isset ($wsWxsimArray[0]['visib'])) {
	$printTable[$i][] 	= array ('column' => 'Visibility',	'qtip' => 15);
} else {$stringNotPresent .= '$array["visib"] = 0;';}
$qtipTable[$i] 		= array ('Conditions' => 2, 'Chill' => 4, 'TempMin' => 5, 'TempMax' => 6, 'Gust' => 10, );
if (!$thunderProb) {
	$qtipTable[$i]['Thunder'] = 14;
}
$printHours[$i]		= 1;		// nr of hours in every row printed
$printHoursMax[$i]	= 48;
$rowColor[$i]		= 'row-light';
if (isset ($wsWxsimArray[0]['tempGrass']) ) {
	$i++;
	$tableName[$i]		= langtransstr('Grass and soil forecast');
	$printTable[$i][] 	= array ('column' => 'Time', 		'qtip' => 1);
	$printTable[$i][] 	= array ('column' => 'Temperature','qtip' => 3);
	if (isset ($wsWxsimArray[0]['rain'])) {
		$printTable[$i][] 	= array ('column' => 'Precipation',	'qtip' => 13);
	} else {$stringNotPresent .= '$array["rain"] = 0;';}

	if (isset ($wsWxsimArray[0]['tempSurf'])) {	
		$printTable[$i][] 	= array ('column' => 'Surface', 	'qtip' => 18);
	} else {$stringNotPresent .= '$array["tempSurf"] = 0;';}

	if (isset ($wsWxsimArray[0]['tempGrass'])) {
		$printTable[$i][] 	= array ('column' => 'Grass', 		'qtip' => 17);
	} else {$stringNotPresent .= '$array["tempGrass"] = 0;';}

	if (isset ($wsWxsimArray[0]['tempSoil1'])) {
		$printTable[$i][] 	= array ('column' => 'Soil1', 		'qtip' => 19);
	} else {$stringNotPresent .= '$array["tempSoil1"] = 0;';}

	if (isset ($wsWxsimArray[0]['tempSoil2'])) {
		$printTable[$i][] 	= array ('column' => 'Soil2',		'qtip' => 20);
	} else {$stringNotPresent .= '$array["tempSoil1"] = 0;';}

	$qtipTable[$i] 		= array ('Conditions'=> 2, 'Chill' => 4, 'TempMin' => 5, 'TempMax' => 6, 'UV_Index'	=> 7, 'Dewpoint' => 8 );
	$printHours[$i]		= 3;	// nr of hours in every row printed
	$printHoursMax[$i]	= 999;
	$rowColor[$i]		= 'row-light';
}
$count = count($wsWxsimArray);

for ($n=0;$n < count($printTable);$n++){  // initialize for every table
	$rain[$n] 	= 0;
	$tempMin[$n]    = 99;
	$tempMax[$n]    = -99;
	$gust[$n] 	= 0;
	$thunder[$n]    = 0;
	$qtipId[$n]	= 'table'.$n.'qtip_';
	$qtipTxt[$n]    ='<script type="text/javascript">'.PHP_EOL.'<!--'.PHP_EOL;
	$qtipTxt[$n]    .= '$(document).ready(function() {'.PHP_EOL;;
	$table[$n]      = '<table class=" tabbertab genericTable" style="width: 100%;">'.PHP_EOL;
	$tableHeading[$n] = '<tr class="table-top">';
	$columns = count($printTable[$n]);
	for ($o=0; $o < $columns; $o++) {
		$arr 	= $printTable[$n][$o];
		$column = $arr['column'];
		$label[$n]	= call_user_func ('my'.$column);
		$tableHeading[$n] .= $label[$n];
	}
	$tableHeading[$n] .= '</tr>';
}   // initialize for every table
#
$skip 		= true;				// maybe make it user config if we have to skip
$prevDay 	= 0;
$graphsData	= '';				// for every input line the data is also stored here for javascript graphs
$graphsDays	= array ();			// number of days in input lines
$graphsStart	= 0;
$rainGraphCnt	= 0;				// as rain is graphed for three hour periods a separate field is used to count 3 hours rainfall
$windGraphCnt	= 0;
$gustGraphCnt	= 0;
$graphLines	= 0;
$precipGraph	= 0;
$cloudsGraph	= 0;
$graphTempMin	= 100;
$graphTempMax	= -100;
$graphBaroMin	= 5000;
$graphBaroMax	= -100;
$graphRainMax	= -100;				// no minnimum rain, always 0
$graphSolarMax	= -100;
$graphUVMax	= -100;
$graphWindMax	= -100;
$graphSnowMax	= -100;
# set nul to all missing input fields
$missing = array ('gust1Hr', 'tempMax', 'tempMin', 'snow', 'visib', 'moist1', 'moist2','thunder','solar', 'heat');
for ($i = 0; $i < count($missing); $i++) {
	$key = $missing[$i];
	if (!isset ($wsWxsimArray[1][$key])) {$stringNotPresent .= '$array["'.$key.'"] = 0;'; }
}
if ($stringNotPresent <> '') {echo '<!-- not present, set to nul: '.$stringNotPresent.' -->'.PHP_EOL;}
for ($i = 1; $i < $count; $i++) {  				// for every data line
	$dateThisLine	= $wsWxsimArray[$i]['time'];
	$hrInput 		= date('YmdH',$dateThisLine);	// data hour of data line
	if ($skip == true) {		
		if ($hrInput < $startHr) { continue;}   // skip all outdated lines
		$skip = false;							// we found at least one current line
	}  // eo skip old lines
	if ($prevDay < date('Ymd',$dateThisLine-60)) {  // print a line with sunrise and set information for this new day
		for ($n=0;$n < count($printTable);$n++){
			$startHour = date('H',$dateThisLine) + $printHours[$n];
			if ($startHour <= 24 &&  $printHoursMax[$n]	>= 0) {
			$table[$n] .=  $dataString = myDateLinePrint($dateThisLine);  // and print it in every table
			$table[$n] .= $tableHeading[$n];
			}
		}	
		$prevDay = date('Ymd',$dateThisLine);
		$graphsDays[] = 1000 * (($dateThisLine + $utcDiff) - 3600); 
	}
	for ($n=0;$n < count($printTable);$n++){		// for every table print all required datafields in one row
		$array  		= $wsWxsimArray[$i];			// put the data line into an array	
		eval($stringNotPresent.PHP_EOL);
		$thisLineHour	= date('H',$array['time']);
		$thisLineMinute	= date('i',$array['time']);
		if ($n == 0) {		// only the first iteration used for graph
# generate javascript graph entries
			if ($graphsStart == 0) {$graphsStart = 1000 * ($dateThisLine + $utcDiff) ;}
			if (!isset ($array['gust'])) {$array['gust'] = $array['gust1Hr'];}
			if (is_numeric($array['windDir']) ) {
				$windDir = wsConvertWinddir ($array['windDir']);
			} else {
				$windDir = $array['windDir'];
			}
			$rainGraph	= $windGraph = $gustGraph	='-';
			if ($array['condRain']  > $precipGraph  && $array['condRain'] < 40) {$precipGraph = $array['condRain'];}
			if ($array['condCloud'] > $cloudsGraph) {$cloudsGraph = $array['condCloud'];}
			$rainTime	= '-';
			$graphTime	= (int) ($array['time']+ $utcDiff); 
			$rainGraphCnt	+= $array['rain'];
			if ($windGraphCnt < $array['windSpeed']) {$windGraphCnt = $array['windSpeed'];}
			if ($gustGraphCnt < $array['gust1Hr']) {$gustGraphCnt = $array['gust1Hr'];}
			if (0 == $thisLineHour % 3  && $thisLineMinute == 00) {
				$rainGraph		= $rainGraphCnt;
				$rainTime		= (int) $graphTime - 1.5 * 3600;
				if ($rainGraph > $graphRainMax ) {$graphRainMax = $rainGraph;}
				$windGraph 		= $windGraphCnt;
				$gustGraph		= $gustGraphCnt;				
				$rainGraphCnt	= $windGraphCnt = $gustGraphCnt = 0;
			}
			if (0 == $thisLineHour % 6 && $thisLineMinute == 00) {
				$url = $icon = '';
				$saveArr = $array;
				$array['condRain'] = $precipGraph; 
				$array['condCloud']= $cloudsGraph;
				list ($url, $icon)	= myIconUrl($array);
				$array = $saveArr;
				$precipGraph = $cloudsGraph = 0;
				$iconGraph	= $iconsSmall.$icon.'.png';
				$iconTime	= (int) $graphTime - 3 * 3600;			 
			} else {
				$iconGraph	= '-';
				$iconTime	= '-'; 
			}
			$array['rain']	= round($array['rain'],3);
			$rainGraph		= round($rainGraph,3);
			$thunderGraph	= round($array['thunder']);	
			if ($thunderGraph < 0) { $thunderGraph = 0;}
			list($feelsLike, $word) = wsFeelslikeTemp ($array['temp'],$array['chill'],$array['heat'],$SITE['uomTemp']);
			$graphsData	.= 	'tsv['.$graphLines.'] ="'.$graphTime.'|'.	$array['temp'].'|'.		$array['tempMax'].'|'.		$array['tempMin'].'|'.
					$array['dew'].'|'.		$feelsLike.'|'.	$array['heat'].'|'.			$array['baro'].'|'.
					$array['UV'].'|'.		$array['solar'].'|'.	$windGraph.'|'.	$gustGraph.'|'.
					$windDir.'|'.			$array['snow'].'|'.		$thunderGraph.'|'.			$rainGraph.'|'.
					$rainTime.'|'. 			$iconGraph.'|'.			$iconTime.'|'.				$array['rain'].'|";'.PHP_EOL;
# check max and min values for graphs
			if ($feelsLike > $graphTempMax )		{$graphTempMax = $feelsLike;}
			if ($feelsLike < $graphTempMin ) 		{$graphTempMin = $feelsLike;}
			if ($array['tempMax'] > $graphTempMax ) {$graphTempMax = $array['tempMax'];}			
			if ($array['tempMin'] < $graphTempMin ) {$graphTempMin = $array['tempMin'];}
			if ($array['dew'] < $graphTempMin ) 	{$graphTempMin = $array['dew'];}			
			if ($array['baro'] > $graphBaroMax) 	{$graphBaroMax = $array['baro'];}	
			if ($array['baro'] < $graphBaroMin) 	{$graphBaroMin = $array['baro'];}
			if ($array['solar'] > $graphSolarMax)	{$graphSolarMax= $array['solar'];}
			if ($array['UV'] > $graphUVMax)	{$graphUVMax= $array['UV'];}	
			if ($array['windSpeed'] > $graphWindMax){$graphWindMax = $array['windSpeed'];}
			if ($array['gust1Hr'] > $graphWindMax)  {$graphWindMax = $array['gust1Hr'];}
			if ($array['snow'] > $graphSnowMax)  	{$graphSnowMax = $array['snow'];}
			$graphLines++;
		}   // eo graph processing
		$rain[$n] += $array['rain'];				// calculate correct rainvalue
		$array['rain'] = $rain[$n];					// save to array for printing
		if (isset ($array['tempMin']) && $array['tempMin'] < $tempMin[$n]) {
			$tempMin[$n] 	= $array['tempMin'];	
		}
		$array['tempMin']	= $tempMin[$n];
		if (isset ($array['tempMax']) && $array['tempMax'] > $tempMax[$n]) {
			$tempMax[$n]	= $array['tempMax'];
		}
		$array['tempMax']	= $tempMax[$n];					
		if 		(isset ($array['gust']) 	&& $array['gust']    > $gust[$n])    {$gust[$n] = $array['gust'];}	
		elseif	(isset ($array['gust1Hr'])	&& $array['gust1Hr'] > $gust[$n])    {$gust[$n] = $array['gust1Hr'];}	
		$array['gust'] = $gust[$n];
		if (isset ($array['thunder']) && $array['thunder'] > $thunder[$n]) {
			$thunder[$n] = 	$array['thunder'];
		}
		$array['thunder'] = $thunder[$n];
		if ($thisLineMinute <> 00) { continue; }			// skip all parts of 1 hour, print only full hours

		if ( $printHoursMax[$n]	>= 0 ) { $printHoursMax[$n] = $printHoursMax[$n] - 1;} else { continue; }  // max nr of lines reached	
		if (! 0 == $thisLineHour % $printHours[$n]) { continue; }	// print only 1 row / so many hours
		if (isset ($array['thunder']) && $array['thunder'] > 0) {
			if (isset ($array['skyCover']) && $array['skyCover'] < $thunderCover ) {
				$array['skyCover'] = $thunderCover;
			}	
		}
		$isDaylight = true;
		if (date('H',$array['time']) <  $dayStarts) {$isDaylight = false;}
		elseif (date('H',$array['time']) >  $nightStarts) {$isDaylight = false;}
		$qtipTxt[$n].= '$("#'.$qtipId[$n].$i.'").qtip({ style: { width: 500 }, content: \'<table style="width: 100%;">';
		$table[$n] .= '<tr class="'.$rowColor[$n].'" id="'.$qtipId[$n].$i.'">';
		$columns = count($printTable[$n]);
		for ($o=0; $o < $columns; $o++) {			// loop all columns in table
			$arr 	= $printTable[$n][$o];
			reset ($array);
			$column = $arr['column'];
			list ($tableValue, $qtipValue) = call_user_func ('my'.$column, $array);
			$table[$n] .= $tableValue;
			$nr = $arr['qtip'];			// load pointer to qtip if needed
			if ($nr <> '') {				
				$qtipValues[$n][$nr] = $qtipValue;  // $qtipValue;		
			}	
		}  // eo loop all columns in table
		if ($rowColor[$n] == 'row-dark') {$rowColor[$n] = 'row-light';} else {$rowColor[$n] =  'row-dark';}	
		foreach ($qtipTable[$n] as $key => $value) {			// loop all remaining qtip fields in this table
			list ($tableValue, $qtipValue) = call_user_func ('my'.$key, $array);	
			$qtipValues[$n][$value] = $qtipValue;
		}		
		$rain[$n] 	= 0;		
		$table[$n] .= '</tr>'.PHP_EOL;
		$string = '';
		for ($p = 1; $p <= 20; $p=$p+2){  // generate content of qtip for this row in this table ($n)
			if (isset ($qtipValues[$n][$p]) ||  isset ($qtipValues[$n][$p+1]) ){
				$string .= '<tr>';
				if (isset ($qtipValues[$n][$p]) ) {$value = $qtipValues[$n][$p];} else {$value = '<td colspan=2>&nbsp;</td>'; }
				$string .= $value;
				if (isset ($qtipValues[$n][$p+1]) ) {$value = $qtipValues[$n][$p+1];} else {$value = '<td colspan=2>&nbsp;</td>'; }
				$string .= $value;
				$string .= '</tr>';
				if ($p == 1) {$string .= '<tr><td colspan="4"><hr /></td></tr>';}
			} 
		}
		$qtipTxt[$n].= $string.'</table>\'});'.PHP_EOL;
		$tempMin[$n]= 99;
		$tempMax[$n]= -99;
		$gust[$n] 	= 0;
		$thunder[$n]= 0;
	}  // eof all fields in one row
} // for every data line

if ($skip == true) {
	echo '<h3> No valid input found. All data is in the past.</h3>'.PHP_EOL;
	$wxsimERROR	= true;
}
for ($n=0;$n < count($printTable);$n++){ 			// close  all table related strings
	$table[$n] .=  '</table>'.PHP_EOL;
	$qtipTxt[$n].= '});'.PHP_EOL.'-->'.PHP_EOL.'</script>'.PHP_EOL;
}
# echo '<!-- '.PHP_EOL.$graphsData.'-->';
#
#
#	these are the <div> for the grpahs which will be displayed
#	they are in a string to be echo-ed when needed on the output page
if (!isset($widthGraphs) ) {
        $width_graphs_x = ($areaWidth-20).'px;';}
else {  $width_graphs_x = $widthGraphs.'px;';}

$stringGraphDivs  = '<!-- test -->
<div class="tabber"  id="TabGraphs" style=" height: 360px; margin: 0px 0px;">
	<div class="tabbertab" style=" height: 360px;"><h3>'.langtransstr('Temperature').'</h3>
		<div id="containerTemp" style="width: '.$width_graphs_x.' height: 340px; margin: 0">
		</div>
	</div>
	<div class="tabbertab" style=" height: 360px;"><h3>'.langtransstr('Thunder').'</h3>
		<div id="containerThun" style="width: '.$width_graphs_x.' height: 340px; margin: 0">
		</div>
	</div>';
if ($wsWxsimArray[0]['snow'] == 1) {
$stringGraphDivs  .= '
	<div class="tabbertab" style=" height: 360px;"><h3>'.langtransstr('Snow').'</h3>
		<div id="containerSnow" style="width: '.$width_graphs_x.' height: 340px; margin: 0">
		</div>
	</div>';
}
$stringGraphDivs  .=
'	<div class="tabbertab" style=" height: 360px;"><h3>'.langtransstr('Sol').'</h3>
		<div id="containerSola" style="width: '.$width_graphs_x.' height: 340px; margin: 0">
		</div>
	</div>
	<div class="tabbertab" style=" height: 360px;"><h3>'.langtransstr('Wind').'</h3>
		<div id="containerWind" style="width: '.$width_graphs_x.' height: 340px; margin: 0">
		</div>
	</div>
	<div class="tabbertab" style=" height: 360px;"><h3>'.langtransstr('Max/Min Temperature').'</h3>
		<div id="containerMaxMin" style="width: '.$width_graphs_x.' height: 340px; margin: 0">
		</div>
	</div>
</div>'.PHP_EOL;
#
#
# generate the graphs
# calculate Y axis steps for graphs
$graphTempMin	= floor ($graphTempMin);
$minTemp	= $graphTempMin;
$graphTempMax	= ceil ($graphTempMax);
$maxTemp	= $graphTempMax;
echo '<!-- temp max: '.$graphTempMax. ' temp min: '.$graphTempMin;
//if($graphTempMax %2 <> 0)	{$graphTempMax += 1;}
$graphTempStep	= ceil(($graphTempMax - $graphTempMin) / 6);
echo ' temp step: '.$graphTempStep;
if ($graphTempMin < 0) {
	$result = abs($graphTempMin) / $graphTempStep;
	$result = ceil ($result);
	$graphTempMin = -1 * $result * $graphTempStep;
} else {
	$result = floor ($graphTempMin / $graphTempStep );
	$graphTempMin = $result * $graphTempStep;
}
$graphTempMax	= $graphTempMin + 8 * $graphTempStep;
$minTemp	= $minTemp - $graphTempStep;
$maxTemp	= $maxTemp + $graphTempStep;
echo '  temp max: '.$graphTempMax;
$graphIconYvalue = $graphTempMax - ($graphTempStep / 2);
echo ' icon: '.$graphIconYvalue. ' -->'.PHP_EOL;
$rainMax = $graphRainMax;
if (preg_match("|mm|",$uomsTo[1])) {
	if ($graphRainMax < 7) {$graphRainMax = 7;}
	$graphRainStep	= ceil ($graphRainMax / 7);
	$graphRainMax	= $graphRainStep * 7;
	echo '<!-- rain max: '.$graphRainMax.' -->'.PHP_EOL;	
} else {
	if ($graphRainMax < 1.3) {$graphRainMax = 14;} else {$graphRainMax = 10 * $graphRainMax;}
	$graphRainStep	= (ceil ($graphRainMax / 7))/ 10;
	$graphRainMax	= $graphRainStep * 7;	
}
$rainMax = $rainMax + $graphRainStep;

$baroMax	= $graphBaroMax;
$baroMin	= $graphBaroMin;
if (preg_match("|hPa|",$uomsTo[3])  || preg_match("|mb|",$uomsTo[3])) {
	$graphBaroDiff = $graphBaroMax - $graphBaroMin;
	if (ceil($graphBaroDiff / 15) <= 7) {$graphBaroStep = 15; } else {$graphBaroStep = 20;}
	$graphBaroMax  = $graphBaroStep * (1 + ceil($graphBaroMax / $graphBaroStep));
	if ($graphBaroMax < 1050) { $graphBaroMax = 1050;}
	$graphBaroMin = $graphBaroMax - 7 * $graphBaroStep;
	echo '<!-- baro max: '.$graphBaroMax.' baro min: '.$graphBaroMin.'-->'.PHP_EOL;	
} else {  // inHg
	$graphBaroMax = 32; $graphBaroMin = 28.5; $graphBaroStep = .5;
}
$baroMax	= $baroMax	+ $graphBaroStep;
$baroMin	= $baroMin	- $graphBaroStep;

$solarMax	= $graphSolarMax;

if ($graphSolarMax < 490) {$graphSolarMax = 490;}
$graphSolarStep = ceil ($graphSolarMax / 7);
$graphSolarMax	= 7 * $graphSolarStep;
$solarMax	= $solarMax	+ $graphSolarStep;

$UVMax	= $graphUVMax;
$graphUVStep    = ceil ($graphUVMax / 7);
$graphUVMax	= 7 * $graphUVStep;
$UVMax		= $UVMax + $graphUVStep;

$windMax	= $graphWindMax;
if ($graphWindMax < 7) {$graphWindMax = 7;}
$graphWindStep 	= ceil ($graphWindMax / 7);
$graphWindMax  	= 7 * $graphWindStep;
$windMax	= $windMax	+ $graphWindStep;

$graphSnowMaxTemp = 4 * $graphSnowMax;
if ($graphSnowMaxTemp < 7) {$graphSnowMax  = 7;}
	$graphSnowStep = 0.25 * ceil ($graphSnowMaxTemp / 7);
	$graphSnowMax  = 7 * $graphSnowStep;

// ##### nog toevoegen andere  eenheden
$negValue = "return '<span style=\"fill: blue;\">' + this.value + '</span>'";
$posValue = "return '<span style=\"fill: red;\">' + this.value + '</span>'";

$graphDaysString = '{';
$daysShort	= array ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
$daysLong	= array ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
for ($i = 0; $i < count ($daysShort); $i++) {
	$graphDaysString .= "'$daysShort[$i]':'".langtransstr($daysLong[$i])."',";
}
$graphDaysString .= '}';
$graphPart1='
<script type="text/javascript">
<!--
var days        = '.$graphDaysString.';
';
$graphsStop     = 1000 * ($dateThisLine + $utcDiff);    // last line processed in milliseconds
$ddays		= '';
for($i=0 ; $i<count($graphsDays); $i++) {               //  shaded background every other day
	if($i ==  count($graphsDays)-1) {               // last incomplete day
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.($graphsStop).', color: "rgba(255, 255, 255, 0.9)" },'; } 
        else {  $ddays.= '{ from: '.$graphsDays[$i].', to: '.$graphsDays[$i+1].', color: "rgba(255, 255, 255, 0.9)" },'; }
	$i++;		// skip next day
}
#
$graphPart1 .='
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
var chartI = 0;
var charts = [];
var tsv = [] ;
'.$graphsData.'var date, rada, ts = "",
temps = [],
chills = [],
gsts = [],
wsps = [],
baros = [],
sols = [],
uvs = [],
precs = [],
snows = [],
kiws = [],
dews = [],
icos = [],
maxs = [],
mins = [],
precHalfHour = [];
var arr = ["00","03","06","09","12","15","18","21"];  // for windicon display
for (j = 0; j < tsv.length; j++) {
	var line =[];
	line = tsv[j].split("|");
    date = 1000 * parseInt(line[0]);
    d = new Date (date);
    if(line[0].length > 0 && parseInt(line[0]) != "undefined"){
    	if(line[16] != "-" && parseFloat(line[15]) > 0 ){
    		line[16] = line[16] *1000;
        	precs.push([date, parseFloat(line[15])]);
        } else { precs.push([date,0]);}                
        temps.push([date, parseFloat(line[1])]);
        chills.push([date, parseFloat(line[5])]);
        dews.push([date, parseFloat(line[4])]);
        baros.push([date, parseFloat(line[7])]);
        kiws.push([date, parseFloat(line[14])]);
        sols.push([date, parseFloat(line[9])]);
        uvs.push([date, parseFloat(line[8])]);
        snows.push([date, parseFloat(line[13])]);
        if (line[11] != \'-\') { gsts.push([date, parseFloat(line[11])]);}
        mkr = "'.$windIconsSmall.'" +line[12]+".png";
        str = {x:date,y:parseFloat(line[10]), marker:{symbol:\'url(\'+mkr+\')\'}};
        if (line[10] != \'-\') { wsps.push(str); }			
        if(line[18] != "-" && line[17] != "undefined"){
        	line[18] = line[18] *1000;   	
            mkr = line[17];
            str = {x:line[18],y:'.$graphIconYvalue.', marker:{symbol:\'url(\'+mkr+\')\'}};
            icos.push(str);    			
        }
        maxs.push([date, parseFloat(line[2])]);
        mins.push([date, parseFloat(line[3])]);
        precHalfHour[date] = parseFloat(line[19]);        
    } // Line contains correct data           
}; // eo for each tsv

var yTitles = {color: "#000000", fontWeight: "bold", fontSize:"10px"};
var yLabels = {fontWeight: "normal",fontSize:"8px"};

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
			marginRight: 40,
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

            positioner: function () {return { x: 0,};},

		 backgroundColor: "#A2D959",
         borderColor: "#fff",
         borderRadius: 3,
         borderWidth: 0,  
         shared: true,
         crosshairs: { width: 0.5,color: "#666"},
         style: {lineHeight: "1.3em",fontSize: "11px",color: "#000"},
         formatter: function() { if (this.points == undefined)  {return false;}
            var s = "" + days[Highcharts.dateFormat(\'%a\', this.x)]+" "+ Highcharts.dateFormat(\'%H:%M\', this.x) +"";
            $.each(this.points, function(i, point) {
            	if (point.series.name != " ") {
					var unit = {
					   "'.langtransstr('Precipation').'": " '.$uomsTo[1].'",
					   "'.langtransstr('Snowdepth').'": " '.$uomsTo[4].'",
					   "'.langtransstr('Wind').'": " '.$uomsTo[2].'",
					   "'.langtransstr('Windgust').'": " '.$uomsTo[2].'",
					   "'.langtransstr('Cloud cover').'": " %",
					   "'.langtransstr('Thunderstorm').'": " %",
					   "'.langtransstr('Solar').'": " w/m2",
					   "UV Index": "",
					   " ": "",
					   "'.langtransstr('Temperature').'": "'.$degree_symbol.$uomsTo[0].'",
					   "'.langtransstr('Maximum temperature').'": "'.$degree_symbol.$uomsTo[0].'",
					   "'.langtransstr('Minimum temperature').'": "'.$degree_symbol.$uomsTo[0].'",
					   "'.langtransstr('Feels like').'": "'.$degree_symbol.$uomsTo[0].'",
					   "'.langtransstr('Dewpoint').'": "'.$degree_symbol.$uomsTo[0].'",
					   "'.langtransstr('Pressure').'": " '.$uomsTo[3].'"
					}[point.series.name];
					if(point.series.name != "'.langtransstr('Precipation').'") {
						s += "<br/>"+point.series.name+": <b>"+point.y+unit+"</b>";
					} 
					if (point.series.name == "'.langtransstr('Precipation').'") {
					 if (precHalfHour[this.x] != 0 ) {
						s += "<br/>'.langtransstr('Precipation').': <b>";
						s += precHalfHour[this.x] + "'.$uomsTo[1].'</b>";
					 } else {
						s += "<br/>'.langtransstr('Precipation').': <b>'.langtransstr("No precipation").'</b>";
					 }
					}
				}   
            });  // eo each
            return s;
         }
      },
		
		
		
	});  // eo set general options
    charts[chartI]  = {
        chart: {events: {load: applyGraphGradient}, renderTo: "containerTemp" },		
      	yAxis: [
      	{ lineWidth: 2, 
          gridLineWidth: 0.4, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.', offset: 25,
          title: {text: "'.$degree_symbol.$uomsTo[0].'", rotation: 0, align:"high", offset: 4, y: 0, style:yTitles},
          labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$minTemp.' || this.value > '.$maxTemp.' ){ return ""; } 
          else
          {if (this.value < 0) {'.$negValue.';} else {'.$posValue.';}}
          },style:yLabels}   
      	},{
          gridLineWidth: 0, min: 0,max:'.$graphRainMax.',tickInterval:'.$graphRainStep.', offset: 0,
          title: {text: "'.$uomsTo[1].'", rotation: 0, align:"low", offset: 0,x: -30, y: 15, style:yTitles},
          labels: {align: "left", x: -20, y: 1,  formatter: function() {if (this.value < 0 || this.value > '.$rainMax.' ){ return ""; } else {return this.value;}},style:yLabels}
      	},{
      	 gridLineWidth: 0, min: '.$graphBaroMin.',max: '.$graphBaroMax.',tickInterval: '.$graphBaroStep.',opposite: true,
         title: {text:"'.$uomsTo[3].'", rotation: 0, align:"high", offset: 25, y: 0, style:yTitles,},        
         labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value < '.$baroMin.' || this.value > '.$baroMax.' ){ return ""; } else {return this.value;}},style:yLabels}
      	}],
      	series: [
      		{name: "'.langtransstr('Feels like').'",data: chills,color:"rgba(24,116,205,0.6)",dashStyle:"Dot"},
      		{name: "'.langtransstr('Pressure').'",data: baros,color: "#9ACD32",yAxis: 2},
      		{name: "'.langtransstr('Precipation').'",data: precs,color:"#4572A7",type:"column",yAxis:1},
      		{name: "'.langtransstr('Temperature').'",data: temps,color:"#EE7621"},
      		{name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos}
      	]
        };  // eo chart 
    xyz  = new Highcharts.Chart(charts[chartI]);
    chartI = chartI + 1;
    charts[chartI]  = {
        chart: {renderTo: "containerThun" },	
      	yAxis: [
      	{ lineWidth: 2, 
          gridLineWidth: 0.4, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.', offset: 25,
          title: {text: "'.$degree_symbol.$uomsTo[0].'", rotation: 0, align:"high", offset: 4, y: 0, style:yTitles},
          labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$minTemp.' || this.value > '.$maxTemp.' ){ return ""; } 
          else
          {if (this.value < 0) {'.$negValue.';} else {'.$posValue.';}}
        },style:yLabels}   
      	},{ 
         gridLineWidth: 0, min: -10 ,max:100, tickInterval:20, offset: 20,
         title: {text: "%", rotation: 0, align:"low", offset: 0,x: 0, y: 15, style:yTitles},
         labels: {align: "left", x: 0, y: 1, formatter: function() {if (this.value < 0 || this.value > 99 ){ return ""; } else {return this.value;}},style:yLabels}
      	},{
      	 gridLineWidth: 0, min: '.$graphBaroMin.',max: '.$graphBaroMax.',tickInterval: '.$graphBaroStep.',opposite: true,
         title: {text:"'.$uomsTo[3].'", rotation: 0, align:"high", offset: 25, y: 0, style:yTitles,},        
         labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value < '.$baroMin.' || this.value > '.$baroMax.' ){ return ""; } else {return this.value;}},style:yLabels}
      	}],
      	series: [
      		{name: "'.langtransstr('Pressure').'",    data: baros, color:"#9ACD32", yAxis: 2},
      		{name: "'.langtransstr('Thunderstorm').'",data: kiws,  color:"#4572A7", yAxis: 1},
      		{name: "'.langtransstr('Dewpoint').'",    data: dews,  color:"#EE7621"},
      		{name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos}
      	]
    };  // eo chart Thunder
';
if ($wsWxsimArray[0]['snow'] == 1) {$graphPart1 .='
   chartI = chartI + 1;
   charts[chartI]  = {
   	  chart: {renderTo: "containerSnow" },
      yAxis: [{  // snow depth
         gridLineWidth: 0.4, min: 0, max: '.$graphSnowMax.',tickInterval:'.$graphSnowStep.', opposite:true,
         title: {text: "'.$uomsTo[4].'", rotation:0, align:"high", offset: 15, y:-15, style:yTitles,}, 
         labels: {align: "left",x: 4, y: 1, formatter: function() {return this.value;}, style:yLabels}       
      },{    // icons    
      	 gridLineWidth: 0, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.',
         title: {text:"", rotation: 0, align:"high", offset: 4, y: -15, style:yTitles},
         labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$minTemp.' || this.value > '.$maxTemp.' ){ return ""; } 
          else
          {if (this.value < 0) {'.$negValue.';} else {'.$posValue.';}}
          },style:yLabels}         
      }],
      series: [
      	{name:"'.langtransstr('Snowdepth').'",color:"#87CEFA",type:"areaspline",data:snows},
      	{name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos, yAxis:1}
      ]    
   };
';}
$graphPart1 .='  
	chartI = chartI + 1;  
    charts[chartI]   = {
      chart:{renderTo: "containerSola"},
      yAxis: [{  // UV
         gridLineWidth: 0.4, min: 0, tickInterval: 1, opposite: true,max: '.$graphUVMax.', tickInterval:'.$graphUVStep.',
         title: {text: "UV", rotation:0, align:"high", offset: 25, y: 0, style:yTitles,},      
         labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value > '.$UVMax.') {return "";} else {return this.value;}}, style:yLabels}      
      },{
         lineWidth: 1,gridLineWidth: 0.4,min: 0,max: '.$graphSolarMax.', tickInterval:'.$graphSolarStep.',
         title: {text: "w/m2",y:-7,margin:-26,style:yTitles,rotation:0,align:"high"},
         labels: {x: -4,formatter: function() {if (this.value > '.$solarMax.' ){ return ""; } else {return this.value;}},style:yLabels}
      },{        
      	 gridLineWidth: 0, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.',
         title: {text:"", rotation: 0, align:"high", offset: 4, y: -15, style:yTitles},
         labels: {x: -4, y: 1, formatter: function() {return ""},style:yLabels}       
      }],
      series: [
      {name: "'.langtransstr('Solar').'",data: sols,  color:"#EEEE00",type: "areaspline", yAxis:1},
      {name: "UV Index",                 data: uvs,   color:"#FFB90F",type: "areaspline", zIndex: 2},
      {name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos, yAxis:2}
      ]
    };  // eo chart solar uv   
	chartI = chartI + 1;  
    charts[chartI]   = {
      chart:{renderTo: "containerWind"},
      yAxis: [{  // wind and gust
         gridLineWidth: 0.4, min: 0, max: '.$graphWindMax.', tickInterval: '.$graphWindStep.', opposite: true,
         title: {text: "'.$uomsTo[2].'", rotation:0, align:"high", offset: 25, y: 0, style:yTitles,},     
         labels: {align: "left",x: 4, y: 1, formatter: function()  {if (this.value > '.$windMax.' ){ return ""; } else {return this.value;}},style:yLabels}     
       },{    // icons    
      	 gridLineWidth: 0, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.',
         title: {text:"", rotation: 0, align:"high", offset: 4, y: -15, style:yTitles},
         labels: {x: -4, y: 1, formatter: function() {return ""},style:yLabels}       
      }],
      series: [
      {name: "'.langtransstr('Wind').'",     data: wsps,  color:"#EEEE00", marker:{radius:2,symbol:"circle"}},
      {name: "'.langtransstr('Windgust').'", data: gsts,  color:"#FFB90F", marker:{radius:2,symbol:"circle"}},
      {name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos, yAxis:1}
      ]
    };  // eo chart wind   
	chartI = chartI + 1;  
    charts[chartI]   = {
      chart:{renderTo: "containerMaxMin"},
      	yAxis: [
      	{ lineWidth: 2, 
          gridLineWidth: 0.4, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.', offset: 25,
          title: {text: "'.$degree_symbol.$uomsTo[0].'", rotation: 0, align:"high", offset: 4, y: 0, style:yTitles},
          labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$minTemp.' || this.value > '.$maxTemp.' ){ return ""; } 
          else
          {if (this.value < 0) {'.$negValue.';} else {'.$posValue.';}}
          },style:yLabels}   
      }],
      series: [
      {name: "'.langtransstr('Maximum temperature').'", data: maxs,  color:"#EE7621"},
      {name: "'.langtransstr('Minimum temperature').'", data: mins,  color:"#9ACD32"},
      {name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos, yAxis:0}
      ]
    };  // eo chart wind   
/**
 * Event handler for applying different colors above and below a threshold value. 
 * Currently this only works in SVG capable browsers. A full solution is scheduled
 * for Highcharts 3.0. In the current example the data is static, so we dont need to
 * recompute after altering the data. In dynamic series, the same event handler 
 * should be added to yAxis.events.setExtremes and possibly other events, like
 * chart.events.resize.
 */
function applyGraphGradient() { 
    // Options
    var threshold = 0,
        colorAbove = "#EE4643",
        colorBelow = "#4572EE";   
    // internal
    if ("'.$uomsTo[0].'" == "F") {threshold = 32;}
    var series = this.series[3],  i,point;      
    if (this.renderer.box.tagName === "svg") {  
        var translatedThreshold = series.yAxis.translate(threshold),
            y1 = Math.round(series.yAxis.len - translatedThreshold),
            y2 = y1 + 2; // 0.01 would be fine, but IE9 requires 2     
        // Apply gradient to the path
        series.graph.attr({
            stroke: {
                linearGradient: [0, y1, 0, y2],
                stops: [
                    [0, colorAbove],
                    [1, colorBelow]
                ]
            }
         });      
    }
    // prevent the old color from coming back after hover
    delete series.pointAttr.hover.fill;
    delete series.pointAttr[""].fill;  
}
}); // eo document ready
-->
</script>'.
'<script type="text/javascript">
var tabberOptions = {
 /* Optional: code to run when the user clicks a tab. If this
     function returns boolean false then the tab will not be changed
     (the click is canceled). If you do not return a value or return
     something that is not boolean false, */

  	"onClick": function(argsObj) {

    var t = argsObj.tabber; /* Tabber object */
    var id = t.id; /* ID of the main tabber DIV */
    var i = argsObj.index; /* Which tab was clicked (0 is the first tab) */
    var e = argsObj.event; /* Event object */
    
    if (id != "TabGraphs") { return true; }
     chart = new Highcharts.Chart(charts[i]);
     return true;
  }
};
</script>'.PHP_EOL;
# ----------------------------------------------------------------------------------------
function myLongDate ($time) {
	global $dateLongFormat, $longDays, $myLongDays, $longMonths, $myLongMonths;
	$longDate = date ($dateLongFormat,$time);
	$from	= array();
	$to	= array();
	for ($i = 0; $i < count($longDays); $i++) {
		if (wsfound($longDate,$longDays[$i])) {
			$from[] = $longDays[$i];
			$to[] 	= $myLongDays[$i];
			break;
		}
	}
	for ($i = 0; $i < count($longMonths); $i++) {
		if (wsfound($longDate,$longMonths[$i])) {
			$from[] = $longMonths[$i];
			$to[] 	= $myLongMonths[$i];
			break;
		}
	}
	$longDate = str_replace ($from, $to, $longDate);
	return $longDate;
}
function myDateLinePrint($time) {
	global  $SITE, $img,$timeFormat, $lat, $long, $dayStarts, $nightStarts, $rowColor, $n, $printTable;
			
// 		if ($rowColor[$n] == 'row-dark') {$rowColor[$n] = 'row-light';} else {$rowColor[$n] =  'row-dark';}	

	$prevDay = date('Ymd',$time);
	$srise 	= date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $lat, $long);   // standard time integer
	$sset 	= date_sunset ($time, SUNFUNCS_RET_TIMESTAMP, $lat, $long);
	$inter1= floor($sset - $srise)/60;  $hours = floor($inter1/60); $mins = substr('0'.floor($inter1 - $hours*60),-2);  $dlength = $hours.':'.$mins;
	$text = '';  #   $text = '<!--   $srise = '.$srise.' $sset =  '.$srise.'  $dlength=  '.$dlength.' -->'.PHP_EOL;
	$dayStarts 		= date('H',$srise);
	$nightStarts 	= date('H',$sset);
	$longDate = myLongDate ($time);
	if (isset ($n) ) {
		$columns 	= count($printTable[$n]);
		$color		= $rowColor[$n];
	} else {
		$columns 	= 6;
		$color		= 'row-dark';
	}
	$string='<tr class="wsWxsimDateline '.$color.'"><td colspan="'.$columns.'">
<span style="float:left; position:relative;">&nbsp;<b>'.$longDate.'</b></span>
<span style="float:right;position:relative;">
	<span class="rTxt">
		<img src="'.$img.'/sunrise.png" style=" width: 24px; height: 12px;" alt="sunrise" />&nbsp;&nbsp;'.date($timeFormat,$srise).'&nbsp;&nbsp;
		<img src="'.$img.'/sunset.png"  style=" width: 24px; height: 12px;" alt="sunset" />&nbsp;&nbsp;'.date($timeFormat,$sset).'&nbsp;&nbsp;&nbsp;'.
		langtransstr('Daylength').': '.$dlength.'&nbsp;
	</span>
	'.$text.'
</span>
</td></tr>'.PHP_EOL;
	if ($rowColor[$n] == 'row-dark') {$rowColor[$n] = 'row-light';} else {$rowColor[$n] =  'row-dark';}	
	return $string;
}
function myTime($array=''){
	global $SITE, $longDays, $myLongDays, $printHours, $n ;
	if (!is_array($array) ) {return'<td>'.langtransstr('Period').'</td>';}
	$return = '<td>'.date($SITE['timeOnlyFormat'],$array['time']).'</td>';
	$date = date ('l d',$array['time']-$printHours[$n]*60*60);
	$from = ''; $to = '';
	for ($i = 0; $i < count($longDays); $i++) {
		if (wsfound($date,$longDays[$i])) {
			$from   = $longDays[$i];
			$to 	= $myLongDays[$i];
			break;
		}
	}
	$date 		= str_replace ($from, $to, $date);
	$endHour	= date ($SITE['hourOnlyFormat'],$array['time']);		//		$SITE['hourOnlyFormat']	= 'H';			// Euro format 'H'  (hh=00..23);  us format 'h a'   05 pm
	$startHour  = date ($SITE['hourOnlyFormat'],$array['time'] - $printHours[$n]*60*60 );
	$return		= '<td>'.$startHour.'-'.$endHour.'</td>';
	$tip 		= '<td colspan="2">'.$date.': '.$startHour.'-'.$endHour.'</td>';
	return array ($return, $tip);
}
function myTimeTxt($array=''){
	if (!is_array($array) ) {return '<td>&nbsp;</td>';}
	$tekst	= '<td>'.$array['time'].'</td>';
	$tip	= '<td style="text-align: left;">'.$array['time'].'</td>';
	return array ($tekst, $tip);
}
function myIconUrl($array=''){
	global $SITE, $isDaylight ;
	$icon=$array['condCloud'] + 1.0*$array['condRain'];
	if ( ($icon > 0 ) && ($icon < 100 ) ) {$icon = $icon + 100;}  // clear but still some rain etc., there should be some clouds
	$icon = (string) substr('000'.$icon,-3,3);
	if (! $isDaylight) {$icon .= 'n';}
	$iconOut = $iconUrlIn = $iconUrlOut = $headerClass = '';
	$icon = wsChangeIcon ('wxsim',$icon, $iconOut, $iconUrlIn, $iconUrlOut, $headerClass);
	return array ($iconUrlOut, $iconOut);
}
function myIcon($array=''){
	if (!is_array($array) ) {return '<td>'.langtransstr('Icon').'</td>';}
	$icon = '';
	list ($iconUrl, $icon)  = myIconUrl($array);
	$return = '<td><img class="imgCCN" src="'.$iconUrl.'"  alt=""/></td>';
	return array ($return, '');
}
function myIconTxt($array=''){
	if (!is_array($array) ) {return '<td>'.langtransstr('Icon').'</td>';}
	$iconOut = $iconUrlIn = $iconUrlOut = $headerClass = '';
	$icon = wsChangeIcon ('noaa',$array['icon'], $iconOut, $iconUrlIn, $iconUrlOut, $headerClass);
	$icon = wsChangeIcon ('wxsim',$iconOut, $iconOut, $iconUrlIn, $iconUrlOut, $headerClass);
	$tekst	= '<td><img class="imgCCN" src="'.$iconUrlOut.'"  alt=""/></td>';
	$tip	= '<td colspan="2">'.$tekst.'</td>';
	return array ($tekst, $tip);
}
function myDescription($array=''){
	global $SITE;
	if (!is_array($array) ){return '';}
	$tekst	= '</tr><tr class="row-light"><td colspan ="6">'.$array['text'];
	$tekst	.=  '<br /></td>';
	return array ($tekst, '');
}
function myConditions($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Conditions').'</td>';}
	$arr = explode ('|',$array['cond']);
	$string = '';
	for ($i = 0; $i < count($arr); $i++){
		if ($i > 0 && strlen(trim($arr[$i])) > 0) {$string .= '<br />';}
		$string .= langtransstr(ucfirst($arr[$i]));	
	}
	$tip = '<td colspan="2">'.$string.'</td>';
	return array ('<td>'.$string.'</td>', $tip);
}
function myConditionsTxt($array=''){
	if (!is_array($array) ) {return '<td>'.langtransstr('Conditions').'</td>';}
	$tekst	= '<td>'.$array['cond'].'</td>';
	$tekst  = str_replace(PHP_EOL,'',$tekst);
	return array ($tekst, $tekst);
}
function myTemperature($array=''){
	global $SITE, $tempArray, $windchillDiff, $heatDiff;
	if (!is_array($array) ) {return '<td>'.langtransstr('Temperature').'</td>';}
	$temp 	= $array['temp'];
	$string = myCommonTemperature($temp);	// first create temp in nice colors
	$chill = $heat = $temp;
	$feelText = '';
	if (isset ($array['chill']) && $array['chill'] <> '') {$chill = $array['chill'];}
	if (isset ($array['heat'])  && $array['heat']  <> '') {$heat  = $array['heat'];}
	if ($chill <> $temp || $heat <> $temp) {
		list($feelsLike, $word) = wsFeelslikeTemp ($temp,$chill,$heat,$SITE['uomTemp']);
		if ($feelsLike < ($temp + $windchillDiff) )	{			// windchill is far lower ($windchillDiff) than current temp 
			$showFeel = true;
		} elseif ($feelsLike > ($temp + $heatDiff) ){	// heat is far greater ($heatDiff) than current temp ){
			$showFeel = true;	
		} else {$showFeel = false;}
		if ($showFeel == true)	{
			$feelText = '<br />'.langtransstr('Feels like').':<br />'.$feelsLike.'&deg;';
		} 
	}
	$return = '<td>'.$string.$feelText.'</td>';	
	$tip = '<td style="text-align: right;">'.langtransstr('Temperature').':&nbsp;</td><td>'.$array['temp'].$SITE['uomTemp'].'</td>';
#	echo "<!-- temp = $temp - chill = $chill - heat = $heat - Feels = $feelsLike -->".PHP_EOL;
	return array ($return, $tip);
}
function myCommonTemperature($value){
	global $SITE, $tempArray2, $tempSimple;
	$color = 'red';
	$temp = round($value);
	if (strpos ($SITE['uomTemp'], 'C') ) {$colorTemp = $temp + 32;} else {$colorTemp = 32 + round(wsConvertTemperature($value, 'F', 'C')  ); } // for the color lookup we need C as unit
	if (!$tempSimple) {
		if ($colorTemp < 0) {$colorTemp = 0;} elseif ($colorTemp >= count ($tempArray2) )  {$colorTemp = count ($tempArray2) - 1;}
		$color		= $tempArray2[$colorTemp];
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 200%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	} else {
		if ($colorTemp <  32) { $color = 'blue'; } else {$color = 'red';}
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 150%; color: '.$color.';" >'.$temp.'&deg;</span>';	
	}
	return $tempString;
}
function mySurface($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Surface temperature').'</td>';}
	$string = '<td>'.myCommonTemperature($array['tempSurf']).'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Surface temperature').':&nbsp;</td><td>'.$array['tempSurf'].$SITE['uomTemp'].'</td>';
	return array ($string, $tip);
}
function myGrass($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Grass temperature').'</td>';}
	$string =  '<td>'.myCommonTemperature($array['tempGrass']).'</td>';
	$tip = 'Grass temperature';
	$tip = '<td style="text-align: right;">'.langtransstr('Grass temperature').':&nbsp;</td><td>'.$array['tempGrass'].$SITE['uomTemp'].'</td>';

	return array ($string, $tip);
}
function mySoil1($array=''){
	global $SITE, $sgdepth1;
	if (!is_array($array) ) {return '<td>'.langtransstr('Soil').' -'.$sgdepth1.'</td>';}
	$string = '<td>'.myCommonSoil($array['tempSoil1'],$array['moist1']).'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Soil').' -'.$sgdepth1.':<br />'.langtransstr('Moisture').':&nbsp;</td><td>'.$array['tempSoil1'].$SITE['uomTemp'].'<br />'.$array['moist1'].' cb</td>';
	return array ($string, $tip);
}
function mySoil2($array=''){
	global $SITE, $sgdepth2;
	if (!is_array($array) ) {return '<td>'.langtransstr('Soil').' -'.$sgdepth2.'</td>';}
	$string = '<td>'.myCommonSoil($array['tempSoil2'],$array['moist2']).'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Soil').' -'.$sgdepth2.':<br />'.langtransstr('Moisture').':&nbsp;</td><td>'.$array['tempSoil2'].$SITE['uomTemp'].'<br />'.$array['moist2'].' cb</td>';
	return array ($string, $tip);
}
function myCommonSoil($temp, $moist){
	$string = myCommonTemperature($temp);
	$string .='<br /><span>'.$moist.' cb</span>';
	return $string;
}
function myWind($array=''){
	global $SITE, $windIcons, $windlimit;
	if (!is_array($array) ) {return '<td>'.langtransstr('Wind').'</td>';}
	$windspeed		= $array['windSpeed'];
	$windDir   		= trim($array['windDir']);
	$windBftNr		= wsBeaufortNumber ($windspeed,$SITE['uomWind']);
	$windBftText	= wsBeaufortText ($windBftNr);
	$windBftColor	= wsBeaufortColor ($windBftNr);
	if (is_numeric($windDir) ) {$windDir = wsConvertWinddir ($windDir);}
	$windTekst 		= '<span style="margin: 0 0 5px 0; width: 100%; font-size: 90%; background-color: '.$windBftColor.';">'.round($windspeed).$SITE['uomWind'];
	if ($windBftNr >= $windlimit) {
		$windTekst 	= '<span style="border: solid 1px; color: black; background-color: '.$windBftColor.';">&nbsp;'.langtransstr($windBftText).'&nbsp;</span><br />'.$windTekst;
	}
	$windTekst .='</span></td>';
	$return = '<td style="min-width: 60px;"><img style="height: 32px;    width: 32px;" src="'. $windIcons. $windDir. '.png" alt=""/><br />'.$windTekst;
#	$tip 	= '<td style="text-align: right;">'.langtransstr('Wind').':&nbsp;</td><td>'.langtransstr($windDir).' '.round($windspeed).$SITE['uomWind'].'</td>';
	$tip 	= '<td style="text-align: right;">'.langtransstr('Wind').':&nbsp;</td><td>'.langtransstr($windDir).'<br />'.langtransstr($windBftText).'-'.round($windspeed).$SITE['uomWind'].'</td>';
	return array ($return, $tip);
}
function myPrecipation($array=''){
	global $SITE, $noRain;
	if (!is_array($array) ) {return '<td>'.langtransstr('Precipation').'</td>';}
	
	if ($array['rain'] == 0) {
		$string = "<td>$noRain</td>";
	} elseif (wsfound($SITE['uomRain'], 'm') ){		// decimal units
		if ($array['rain'] < 0.1) {
			$string = '<td>'.langtransstr('less than').'<br /> 0.1 '. $SITE['uomRain'].'</td>';
		} else {
			$string = '<td>'.round($array['rain'],1).' '. $SITE['uomRain'].'</td>';
		}
	} elseif ($array['rain'] < 0.005) {				// inches
		$string = '<td>'.langtransstr('less than').'<br /> 0.005 '. $SITE['uomRain'].'</td>';  
	} else {
		$string = '<td>'.round($array['rain'],3).' '. $SITE['uomRain'].'</td>';
	}
	$tip = '<td style="text-align: right;">'.langtransstr('Rain').':&nbsp;</td><td>'.$array['rain'].' '.$SITE['uomRain'].'</td>';
	return array ($string, $tip);
}
function mySnow($array=''){
	global $SITE, $noSnow;
	if (!is_array($array) ) {return '<td>'.langtransstr('Snowdepth').'</td>';}
	if ($array['snow'] == 0) {
		$string = "<td>$noSnow</td>";
	} else {
		$string = '<td>'.round($array['snow'],1).' '. $SITE['uomSnow'].'</td>';
	}
	$tip = '<td style="text-align: right;">'.langtransstr('Snowdepth').':&nbsp;</td>'.$string;
	return array ($string, $tip);
}

function myPrecipPlain($array=''){
	global $SITE, $noRain, $uomsTo;
	if (!is_array($array) ) {return '<td>'.langtransstr('Precipation').'</td>';}
	if ($array['pop'] == '' ) {$stringPop = '';} else {$stringPop = '<br />'.langtransstr('PoP').': '.$array['pop'].'%';}
	if ($uomsTo[1] == 'mm') {$rain = round($array['rain'],0);} else {$rain = round($array['rain'],3);}
	$stringTip	= '<td>'.$array['rainExtra'].$rain.' '. $SITE['uomRain'].'</td>';
	$tip 		=  $array['rainExtra'].$rain.' '. $SITE['uomRain'];
	if ($array['rain'] == 0) {$string = $noRain;} else {$string = $tip;}
	$return 	= '<td style="min-width: 30%;">'.$string.$stringPop.'</td>';	
	$tip 		= '<td style="text-align: right;">'.langtransstr('Precipation').':&nbsp;</td><td>'.$tip.$stringPop.'</td>';
	return array ($return, $tip);
}
function myThunder($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Thunderstorm').'<br />'.langtransstr('probability').'</td>';}
	$value = round($array['thunder']);	
	if ($value < 0) { $value = 0;}
	$string = '<td>'.$value.'%</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Thunderstorm').' '.langtransstr('probability').':&nbsp;</td>'.$string;
	return array ($string, $tip);
}
function myDewpoint($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Dewpoint').'</td>';}
	$string = '<td>'.round($array['dew'],0).'&deg;</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Dewpoint').':&nbsp;</td><td>'.$array['dew'].$SITE['uomTemp'].'</td>';
	return array ($string, $tip);
}
function myPressure($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Pressure').'</td>';}
	$string = '<td>'.$array['baro'].$SITE['uomBaro'].'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Pressure').':&nbsp;</td>'.'<td>'.$array['baro'].$SITE['uomBaro'].'</td>';
	return array ($string, $tip);
}
function myUV_Index($array=''){
	global $SITE, $minUV;
	$uvTxt 	= langtransstr('UV');
	if (!is_array($array) ) {return '<td>'.$uvTxt.'</td>';}
	$uvValue= round($array['UV']*1.0);
	if (!isset ($minUV) ) {$minUV = 0;}
	if ($uvValue >= $minUV) {
		$string =  '<span style="">'.$uvTxt.' '.round($uvValue).'<br /></span>';
		$string .= ''.wsGetUVword ($uvValue);
	} else {$string = '';}
	$tip = '<td style="text-align: right;">'.langtransstr('UV').':&nbsp;</td><td>'.wsgetUVword ($uvValue).'</td>';
	return array ('<td>'.$string.'</td>', $tip);
}
function myCloud_cover($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Cloud cover').'</td>';}
	$string = '<td>'.round($array['skyCover']).'%</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Cloud cover').':&nbsp;</td>'.$string;
	return array ($string, $tip);
}
function myVisibility($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Visibility').'</td>';}
	$string = '<td>'.round($array['visib']).$SITE['uomDistance'].'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Visibility').':&nbsp;</td>'.$string;
	return array ($string, $tip);
}
function myChill ($array=''){
	global $SITE ;
	if (!is_array($array) ) {return '<td>'.langtransstr('Feels like').'</td>';}
	$temp = $array['temp'];
	if (!isset ($array['chill']) ) {$chill = $temp;} else  {$chill = $array['chill'];}
	if (!isset ($array['heat']) )  {$heat  = $temp;} else  {$heat  = $array['heat'];}
	list($value, $word) = wsFeelslikeTemp ($temp,$chill,$heat,$SITE['uomTemp']);
	$tip = '<td style="text-align: right;">'.langtransstr('Feels like').':&nbsp;</td><td>'.$value.$SITE['uomTemp'].'</td>';
	return array ('<td>'.$word.'</td>', $tip);		
}
function myTempMin ($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Minimum temperature').'</td>';}
	$string = '<td>'.myCommonTemperature($array['tempMin']).'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Minimum temperature').':&nbsp;</td><td>'.$array['tempMin'].$SITE['uomTemp'].'</td>';
	return array ($string, $tip);		
}
function myTempMax ($array=''){
	global $SITE;
	if (!is_array($array) ) {return '<td>'.langtransstr('Maximum temperature').'</td>';}
	$string = '<td>'.myCommonTemperature($array['tempMax']).'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Maximum temperature').':&nbsp;</td><td>'.$array['tempMax'].$SITE['uomTemp'].'</td>';
	return array ($string, $tip);		
}
function myGust ($array=''){
	global $SITE ;
	if (!is_array($array) ) {return '<td>'.langtransstr('Windgust').'</td>';}
	$string = '<td>'.round($array['gust']).$SITE['uomWind'].'</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Windgust').':&nbsp;</td>'.$string;
	return array ($string, $tip);		
}
function myHumidity ($array=''){
	global $SITE ;
	if (!is_array($array) ) {return '<td>'.langtransstr('Humidity').'</td>';}
	$string = '<td>'.round($array['hum']).'%</td>';
	$tip = '<td style="text-align: right;">'.langtransstr('Humidity').':&nbsp;</td>'.$string;
	return array ($string, $tip);		
}