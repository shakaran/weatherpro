<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'gaugeXsmall.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.10 2015-06-21';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.10 2015-06-21 release verion / removed url error
# -------------------------------------------------------------------------------------------------
if (!isset ($skiptopText) ) {$skiptopText = '';}
if (!isset ($SITE['pages']['gaugePage'])) { $link = ''; }
else {$link   = '<a href="'.$SITE['pages']['gaugePage'].'&amp;lang='.$lang.$lang.$skiptopText.'">
<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
} 
?>
<!-- steelseries start -->
<div class="blockDiv">
<h1 class="blockHead"><?php echo langtransstr('Live Weather Data').' '.$link; ?></h1>
<table class="gaugeTable" style="width: 100%; padding 3px;">
<tr>
	<td>
		<div id="tip_0">
		  <canvas id="canvas_temp" ></canvas>
		</div>
	</td>
	<td>
		<div id="tip_4">
			<canvas id="canvas_hum" ></canvas>
		</div>
	</td>
	<td>
		<div id="tip_5" >
			<canvas id="canvas_baro" ></canvas>
		</div>
	</td>
	<td>
		<div id="tip_6" >
			<canvas id="canvas_wind" ></canvas>
		</div>
	</td>
	<td>
		<div id="tip_7" >
			<canvas id="canvas_dir" ></canvas>
		</div>
	</td>
</tr>
</table>
<div style="text-align: center;"><small>
	<a href="javascript: window.location.reload()" ><canvas id="canvas_led" style="width: 15px; height: 15px;"></canvas></a>&nbsp;&nbsp;&nbsp;
	<canvas id="canvas_status" style="width: 550px; height: 15px;"></canvas>&nbsp;&nbsp;
	<canvas id="canvas_timer" style="width: 50px; height: 15px;"></canvas>
</small></div>
<!-- Credits -->
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="gauges/scripts/tween.min.js"></script>
<script src="gauges/scripts/language.js"></script> 
<?php
if (!isset ($gaugSize) )                { $gaugSize = 151;}
if (!isset ($SITE['steelTime']) )       { $SITE['steelTime'] = 30;}
if ($SITE['steelTime'] > 60 )           { $SITE['steelTime'] = 30;}
if ($SITE['steelTime'] < 10 )           { $SITE['steelTime'] = 10;}
#
echo '<script>'.PHP_EOL;
echo '	var g_size                      = '.$gaugSize.';'.PHP_EOL;
echo '	var g_count                     = '.$SITE['steelTime'].';'.PHP_EOL;
echo '	var wxLang                      = "'.$lang.'&wp='.$SITE['WXsoftware'].'";'.PHP_EOL;  
echo '	var LANG                        = LANG_'.strtoupper($lang).';'.PHP_EOL;
echo '	var g_showUvGauge	        = false;'.PHP_EOL;
echo '	var g_showSolarGauge	        = false;'.PHP_EOL;
#	  
#	ws_popupGraphs	0 = no graphs 1 = Cumulus, 2 = wxGraphs (WD or MH)   3 = weatherlink
#	g_realTimeURL	name of the file or script which supplies the realtime values
#	g_imgPathURL	relative path for the graph images
 
echo '	var g_realTimeURL               = "./ws_realtime.php";  '.PHP_EOL;   
echo '	var g_showPopupDataGraphs       = true;'.PHP_EOL; 

switch ($SITE['WXsoftware']) {
  case  'CU':
        echo '	var ws_popupGraphs              = 1; '.PHP_EOL;
        echo '	var g_imgPathURL                = "'.$SITE['graphImageDir'].'"; '.PHP_EOL;
  break;	
  case  'WD':
        echo '	var ws_popupGraphs              = 2; '.PHP_EOL;
        echo '  var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
  break;
  case  'CW':
        echo '	var ws_popupGraphs              = 2; '.PHP_EOL;
        echo '  var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
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
        echo '  var g_imgPathURL                = "wxgraphs/";'.PHP_EOL;
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
