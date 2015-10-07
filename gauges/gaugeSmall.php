<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'gaugeSmall.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-07-27 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$count=0;
if (isset ($SITE['UV']) && $SITE['UV']) {
	$count++;
}
if (isset ($SITE['SOLAR']) && $SITE['SOLAR']) {
	$count++;
}
if (!isset ($skiptopText) ) {$skiptopText = '';}
if (!isset ($SITE['pages']['gaugePage'])) { $link = ''; }
else {$link   = '<a href="'.$SITE['pages']['gaugePage'].'&amp;lang='.$lang.$lang.$skiptopText.'">
<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
} 
?>
<!-- steelseries start -->
<div class="blockDiv">
<h1 class="blockHead"><?php echo langtransstr('Live Weather Data').' '.$link; ?></h1>
<table class="gaugeTable" style="width: 98%; margin: 5px auto;">
<tr>
	<td>
		<div id="tip_0">
		  <canvas id="canvas_temp" ></canvas>
		</div>
		<input id="rad_temp1" type="radio" name="rad_temp" value="out" checked onclick="doTemp(this);">
		<label id="lab_temp1" for="rad_temp1"><?php echo langtransstr('outside') ?></label>
		<input id="rad_temp2" type="radio" name="rad_temp" value="in" onclick="doTemp(this);">
		<label id="lab_temp2" for="rad_temp2"><?php echo langtransstr('inside') ?></label>
	</td>
	<td>
		<div id="tip_4">
			<canvas id="canvas_hum" ></canvas>
		</div>
		<input id="rad_hum1" type="radio" name="rad_hum" value="out" checked onclick="doHum(this);">
		<label id="lab_hum1" for="rad_hum1"><?php echo langtransstr('outside') ?></label>
		<input id="rad_hum2" type="radio" name="rad_hum" value="in" onclick="doHum(this);">
		<label id="lab_hum2" for="rad_hum2"><?php echo langtransstr('inside') ?></label>
	</td>
	<td>
		<div id="tip_5" >
			<canvas id="canvas_baro" ></canvas>
		</div>
	</td>
<?php
if ($count == 0) {	# only 6 dials to display
	echo '</tr>
<tr>'.PHP_EOL;
}
?>
	<td>
		<div id="tip_6" >
			<canvas id="canvas_wind" ></canvas>
		</div>
	</td>
<?php
if ($count == 2) {
	echo '</tr><!-- # 8 dials to display -->
<tr>'.PHP_EOL;
} 
elseif ($count == 1) {
	echo '</tr><!-- # only 7 dials to display -->
</table>
<table class="gaugeTable" style="width: 70%; margin: 5px auto;">
<tr>'.PHP_EOL;
}
?>
	<td>
		<div id="tip_7" >
			<canvas id="canvas_dir" ></canvas>
		</div>
	</td>
	<td>
		<div id="tip_2" style="margin 10px 0 0 10px;">
			<canvas id="canvas_rain" ></canvas>
		</div>
	</td>
<?php
if (isset ($SITE['UV']) && $SITE['UV'] == true) {
?>
 	<td id="uv_cell" style="margin 10px 0 0 10px;">
		<div id="tip_8">
			<canvas id="canvas_uv" ></canvas>
		</div>
	</td>
<?php
}
if (isset ($SITE['SOLAR']) && $SITE['SOLAR']) {
?>
	<td id="solar_cell" style="margin 10px 0 0 10px;">
		<div id="tip_9">
			<canvas id="canvas_solar" ></canvas>
		</div>
	</td>
<?php
}
?>
</tr>
</table>
<p style="text-align: center;"><small>
	<a href="javascript: window.location.reload()" ><canvas id="canvas_led" style="width: 15px; height: 15px;"></canvas></a>&nbsp;&nbsp;&nbsp;
	<canvas id="canvas_status" style="width: 550px; height: 15px;"></canvas>&nbsp;&nbsp;
	<canvas id="canvas_timer" style="width: 50px; height: 15px;"></canvas>
  <br />
  Scripts by <a href="http://wiki.sandaysoft.com/a/SteelSeries_Gauges">Mark Crossley</a> version <span id="scriptVer"></span> - adapted for <a href="http://leuven-template.eu">Leuven-Template</a> (v3) -
  Gauges programmed by Gerrit Grunwald <a href="http://harmoniccode.blogspot.com" target="_blank">SteelSeries</a>.
</small></p>
<!-- Credits -->
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="gauges/scripts/tween.min.js"></script>
<script src="gauges/scripts/language.js"></script> 
<?php
if (!isset ($gaugSize) )                { $gaugSize = 171;}
if (!isset ($SITE['steelTime']) )       { $SITE['steelTime'] = 30;}
if ($SITE['steelTime'] > 60 )           { $SITE['steelTime'] = 30;}
if ($SITE['steelTime'] < 10 )           { $SITE['steelTime'] = 10;}
#
echo '<script>'.PHP_EOL;
echo '	var g_size                      = '.$gaugSize.';'.PHP_EOL;
echo '	var g_count                     = '.$SITE['steelTime'].';'.PHP_EOL;
echo '	var wxLang                      = "'.$lang.'&wp='.$SITE['WXsoftware'].'";'.PHP_EOL;  
echo '	var LANG                        = LANG_'.strtoupper($lang).';'.PHP_EOL;
if ($SITE['UV']) {
	echo '	var g_showUvGauge		= true;'.PHP_EOL;
} else {
	echo '	var g_showUvGauge		= false;'.PHP_EOL;
}
if ($SITE['SOLAR']) {
	echo '	var g_showSolarGauge		= true;'.PHP_EOL;
} else {
	echo '	var g_showSolarGauge		= false;'.PHP_EOL;
}	  
#	ws_popupGraphs	0 = no graphs 1 = Cumulus, 2 = wxGraphs (WD or MH)   3 = weatherlink
#	g_realTimeURL	name of the file or script which supplies the realtime values
#	g_imgPathURL	relative path for the graph images

echo '	var g_realTimeURL               = "./ws_realtime.php";'.PHP_EOL;   
echo '	var g_showPopupDataGraphs       = true;'.PHP_EOL; 
 
switch ($SITE['WXsoftware']) {
  case  'CU':
        echo '	var ws_popupGraphs              = 1; '.PHP_EOL;
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL;
  break;	
  case  'WD':
        echo '	var ws_popupGraphs              = 2; '.PHP_EOL;
        echo '	var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
  break;
  case  'CW':
        echo '	var ws_popupGraphs              = 2; '.PHP_EOL;
        echo '	var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
  break;
  case  'WL':
        echo '	var ws_popupGraphs              = 3;'.PHP_EOL; 
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL; 
  break;
  case'MH':  
        echo '	var ws_popupGraphs              = 2; '.PHP_EOL; 
        echo '	var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
  break;
  case 'WC': 
        echo '	var ws_popupGraphs              = 4; '.PHP_EOL; 
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL; 
  break;	
  case 'VW': 
        echo '	var ws_popupGraphs              = 5; '.PHP_EOL; 
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL; 
  break;	
  case 'WV': 
        echo '	var ws_popupGraphs              = 6; '.PHP_EOL; 
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL; 
  break;
  case  'WS':
        echo '	var ws_popupGraphs              = 2; '.PHP_EOL;
        echo '	var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
  break;	
  default:
        echo '	var ws_popupGraphs              = 0; '.PHP_EOL;
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL; 
        echo '	var g_showPopupDataGraphs       = false;'.PHP_EOL;  
}

echo '</script>'.PHP_EOL;
?> 
<script src="gauges/scripts/steelseries.min.js"></script>
<script src="gauges/scripts/wsGauges.js"></script>
<script src="gauges/scripts/ddimgtooltip.min.js"></script>
<?php
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 

