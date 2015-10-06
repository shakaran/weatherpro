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
$pageName		= 'graphs.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------------------
# 3.00 2014-09-12 release version
# --------------------------------------------------------------------------------------
# Graphs as supplied by weather software
# --------------------------------------------------------------------------------------
$arrKind	        = array();
$arrKind['temp']        = langtransstr('Temperature') ;
$arrKind['humi']        = langtransstr('Humidity');
$arrKind['wind']        = langtransstr('Wind');
$arrKind['baro']        = langtransstr('Barometer');
$arrKind['rain']        = langtransstr('Rain');
$arrKind['sola']        = langtransstr('Solar Radiation');
$arrKind['uvuv']        = langtransstr('UV Index');
$arrKind['souv']        = langtransstr('Solar/UV');
$arrKind['2424']        = langtransstr('Last 24 Hours');
$arrKind['7272']        = langtransstr('Last 72 Hours');
$arrKind['mont']        = langtransstr('Month to Date');
#
$arrTabs[]		= array('temp','humi');
$arrTabs[]		= array('wind','baro');
$arrTabs[]		= array('rain');
if ($SITE['SOLAR'])	{$arrTabs[]	= array('sola');}
if ($SITE['UV'])	{$arrTabs[]	= array('uvuv');}		

#
$arrImgs		= array();
switch ($SITE['WXsoftware']) {
  case 'WL':
	$width		= 310;
	$height		= 200;
	$arrImgs[]	='|temp|OutsideTempHistory.gif		|Temperature|||';
	$arrImgs[]	='|humi|OutsideHumidityHistory.gif	|Humidity|||';
	$arrImgs[]	='|temp|DewPointHistory.gif		|Dew Point|||';
	$arrImgs[]	='|temp|THWHistory.gif			|THW Index|||';
	$arrImgs[]	='|temp|WindChillHistory.gif		|Wind Chill|||';
	$arrImgs[]	='|temp|HeatIndexHistory.gif		|Heat Index|||';
	$arrImgs[]	='|wind|WindSpeedHistory.gif		|Wind Speed|||';
	$arrImgs[]	='|wind|WindDirectionHistory.gif	|Wind Direction|||';
	$arrImgs[]	='|wind|HiWindSpeedHistory.gif		|High Wind Speed|||';
	$arrImgs[]	='|baro|BarometerHistory.gif		|Barometer|||';
	$arrImgs[]	='|rain|RainHistory.gif			|Rain|||';
	$arrImgs[]	='|rain|RainRateHistory.gif		|Rain Rate|||';
	$arrImgs[]	='|sola|SolarRadHistory.gif		|Solar Radiation|||';
	$arrImgs[]	='|uvuv|UVHistory.gif			|UV Index|||';
  break;
  case'CU':
	$width		= 600;
	$height		= 240;
	$arrImgs[]	='|temp|temp.png	|Temperature|||';
	$arrImgs[]	='|humi|hum.png	        |Humidity|||';
	$arrImgs[]	='|wind|wind.png	|Wind Speed|||';
	$arrImgs[]	='|wind|windd.png       |Wind Direction|||';
	$arrImgs[]	='|baro|press.png       |Barometer|||';
	$arrImgs[]	='|rain|raint.png       |Rain|||';
	$arrImgs[]	='|rain|rain.png        |Rain Rate|||';
  break;
  case 'WD':
	$width		= 469;
	$height		= 555;
	$arrTabs	= array();
	$arrTabs[]	= array('2424');
	$arrTabs[]	= array('7272');
	$arrTabs[]	= array('mont');
	$arrImgs[]	='|2424|curr24hourgraph.gif	|Last 24 hours|||';
	$arrImgs[]	='|7272|curr72hourgraph.gif	|Last 72 hours|||';
	$arrImgs[]	='|mont|monthtodate.gif		|Month to Date|||';
  break;
  case 'MH':
	$width		= 610;
	$height		= 300;
	$arrTabs	= array();
	$arrTabs[]	= array('temp');
	$arrTabs[]	= array('wind','rain');
	if ($SITE['SOLAR'])	{$arrTabs[]	= array('sola');}
	if ($SITE['UV'])	{$arrTabs[]	= array('uvuv');}		
	if ($SITE['SOLAR'] && $SITE['UV']) 	{
	        $arrTabs[]	= array('souv');
	}
	$arrImgs[]	='|temp|tdpb2day.png	|Last 48 hours|||';
	$arrImgs[]	='|wind|windrain2day.png|Last 48 hours|||';
	$arrImgs[]	='|souv|soluv2day.png	|Last 48 hours|||';
	$arrImgs[]	='|sola|solhi2day.png	|Last 48 hours|||';
	$arrImgs[]	='|uvuv|uvhi2day.png	|Last 48 hours|||';
  break;
  case 'WC':
	$width		= 610;
	$height		= 300;
	$arrTabs	= array();
	$arrTabs[]	= array('temp');
	$arrTabs[]	= array('wind','rain');
	if ($SITE['SOLAR'])	{$arrTabs[]	= array('sola');}
	if ($SITE['UV'])	{$arrTabs[]	= array('uvuv');}		
	if ($SITE['SOLAR'] && $SITE['UV']) 	{
	        $arrTabs[]	= array('souv');
	}
	$arrImgs[]	='|temp|customgraph1.jpg|This week |||';
	$arrImgs[]	='|wind|customgraph2.jpg|This week |||';
	$arrImgs[]	='|souv|customgraph3.jpg|Last 3 days |||';
	$arrImgs[]	='|sola|customgraph4.jpg|Last 3 days |||';
	$arrImgs[]	='|uvuv|customgraph5.jpg|Last 3 days |||';
  break;
  case 'MB':
	$width		= 590;
	$height		= 300;
	$arrTabs	= array();
	$arrTabs[]	= array('temp');
	$arrTabs[]	= array('rain');
	if ($SITE['uomTemp']  == '&deg;C') {
		$arrImgs[]	='|temp|'.$ws['wsPhoneGr1Dec'].'|This week |||';
		$arrImgs[]	='|rain|'.$ws['wsPhoneGr2Dec'].'|This week |||';
	} else {
		$arrImgs[]	='|temp|'.$ws['wsPhoneGr1Imp'].'|This week |||';
		$arrImgs[]	='|rain|'.$ws['wsPhoneGr2Imp'].'|This week |||';	
	}
  break	;
  default: 
        return;
}
# create array of available images
$cntImgs	= count ($arrImgs);
$arrImg	        = array();
for ($n = 0; $n < $cntImgs; $n++) { 
	$string	= $arrImgs[$n];
	$arr	= explode ('|',$string);
	$arrImg[$n]['key']	= $arr[1];
	$arrImg[$n]['img']	= trim($arr[2]);
	$arrImg[$n]['alt']	= $arr[3];
	$arrImg[$n]['wdt']	= $width;
	$arrImg[$n]['hgt']	= $height;
	if (isset ($arr[3]) && $arr[4] <> '') {$arrImg[$n]['wdt'] = (int) $arr[3];}
	if (isset ($arr[4]) && $arr[5] <> '') {$arrImg[$n]['hgt'] = (int) $arr[4];}
}
$cntTabs	= count($arrTabs);
$strTabs	= '';
for ($i = 0; $i < $cntTabs; $i++) { 
	$arrTxt		= $arrTabs[$i];
	$txtCount	= count ($arrTxt);
	$txtString	= '';
	$continue	= '';
	for ($n = 0; $n < $txtCount; $n++) {
		$key	= $arrTxt[$n];
		$txtString	.= $continue.$arrKind[$key];
		$continue	= ' / ';
	}
	$strTabHead	= PHP_EOL.'<div class="tabbertab">										
	<h3 class="blockHead">'.$txtString.'</h3> '.PHP_EOL;
	$strTabImgs	= '';
	for ($n = 0; $n < $cntImgs; $n++) {
#		echo '<pre>'; print_r ($arrImg); print_r ($arrTabs); # exit;
		if (in_array($arrImg[$n]['key'], $arrTabs[$i]) ){
			$strTabImgs .= genImageLink ($arrImg[$n]['img'], $arrImg[$n]['alt'], $arrImg[$n]['wdt'], $arrImg[$n]['hgt']);
		}
	}
	if ($strTabImgs	<> '') {
		$strTabs	.= $strTabHead . $strTabImgs . '</div>'.PHP_EOL;
	}
	
}  // eo every tab
$tabwidth = $width+10;
echo '<div class="blockDiv" style="text-align: center;">
<h3 class="blockHead">'.langtransstr('Weather Trend Graphs').'&nbsp;'.langtransstr('Graphs generated by').' '.$SITE['WXsoftwareLongName'].'</h3>
<div class="tabber">'.PHP_EOL;
echo $strTabs;
echo '</div>
</div>
<script type="text/javascript" src="javaScripts/tabber.js">
</script>'.PHP_EOL;
#------------------------ function genImageLink  -----------------------------------------
#
function genImageLink ( $imagename, $alttext, $width, $height ) {
	global $SITE ;
	$time	= '?t='.time();
	if  ($SITE['WXsoftware'] == 'MB') { 
		$graphImageDir	= $time = '';	
	} else {
		$graphImageDir	= $SITE['graphImageDir'];
		if(!file_exists($graphImageDir.$imagename)) {
			return "$graphImageDir$imagename not found.";
		}
	}
	return '<p style="text-align: center;"><br /><img src="'.$graphImageDir.$imagename.$time.'" alt="'.$alttext.'" style=" width: '.$width.'px; height: '.$height.'px;" /></p>'.PHP_EOL;
}
#-----------------------------------------------------------------------------------------
?>