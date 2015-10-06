 <?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
#  display source of script if requested so
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
$pageName	= 'gauge.php';
$pageVersion	= '3.10 2015-06-21';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.10 2015-06-21 release version / removed url error
#--------------------------------------------------------------------------------------------------
?> 
<div class="blockDiv">
<!-- Hidden span to force early lcd font download -->
<span style="visibility:hidden; font-family:LCDMono2Ultra">Dummy</span>
<table class="gaugeTable">
<tr>
  <td colspan="3" style="text-align: center;">
	<a href="javascript: window.location.reload()" ><canvas id="canvas_led" width="25" height="25"></canvas></a>&nbsp;&nbsp;&nbsp;
	<canvas id="canvas_status" width="350" height="25"></canvas>&nbsp;&nbsp;
	<canvas id="canvas_timer" width="50" height="25"></canvas>
  </td>
</tr>
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
	<div id="tip_1">
	  <canvas id="canvas_dew" ></canvas>
	</div>
	<input id="rad_dew1" type="radio" name="rad_dew" value="dew" checked onclick="doDew(this);">
	<label id="lab_dew1" for="rad_dew1"><?php echo langtransstr('dewpoint') ?></label>
<?php 
if ($SITE['WXsoftware'] == 'CU'|| $SITE['WXsoftware'] == 'WC') {  
?>
        <input id="rad_dew2" type="radio" name="rad_dew" value="app"  onclick="doDew(this);">
        <label id="lab_dew2" for="rad_dew2"><?php echo langtransstr('apparent') ?></label>
<?php 
} 
?>
        <input id="rad_dew3" type="radio" name="rad_dew" value="wnd" onclick="doDew(this);">
        <label id="lab_dew3" for="rad_dew3"><?php echo langtransstr('Wind Chill') ?></label>
        <br>
        <input id="rad_dew4" type="radio" name="rad_dew" value="hea" onclick="doDew(this);">
        <label id="lab_dew4" for="rad_dew4"><?php echo langtransstr('Heat Index') ?></label>
<?php 
if ($SITE['WXsoftware'] <> 'WL' && $SITE['WXsoftware'] <> 'WS') { 		 
?>
        <input id="rad_dew5" type="radio" name="rad_dew" value="hum" onclick="doDew(this);">
        <label id="lab_dew5" for="rad_dew5"><?php echo langtransstr('humidex') ?></label>
<?php 
} 
?>
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
    </tr>
    <tr><td colspan="3"><br /></td></tr>
    <tr>
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
    <tr><td colspan="3"><br /></td></tr>
<?php
$count=0;
if (isset ($SITE['UV']) && $SITE['UV']) {
	$count++;
}
if (isset ($SITE['SOLAR']) && $SITE['SOLAR']) {
	$count++;
}
if ($count == 2 || $count == 0 ) {
	$colcount = 2;
?>
  </table>
  <table class="genericTable">
<?php 
} else {$colcount = 3;}
?>
    <tr>
      <td>
        <div id="tip_2" style="margin 10px 0 0 10px;">
          <canvas id="canvas_rain" ></canvas>
        </div>
      </td>
      <td>
        <div id="tip_3" style="margin 10px 0 0 10px;">
          <canvas id="canvas_rrate" ></canvas>
        </div>
      </td>
<?php
if ($count == 2 || $count == 0 ) {
?>
    </tr><tr><td colspan="<?php echo $colcount; ?>"><br /></td></tr>
    <tr>
<?php 
}
if (isset ($SITE['UV']) && $SITE['UV']) {
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

<div style="width: 100%; text-align: left">
  <hr />
  <p style="text-align: center; padding: 3px;"><small>
  Scripts by <a href="http://wiki.sandaysoft.com/a/SteelSeries_Gauges">Mark Crossley</a> version <span id="scriptVer"></span> - adapted for <a href="http://leuven-template.eu">Leuven-Template</a> (v3) -
  Gauges programmed by Gerrit Grunwald <a href="http://harmoniccode.blogspot.com" target="_blank">SteelSeries</a>.
 </small></p>
</div><!-- Credits -->
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="gauges/scripts/tween.min.js"></script>
<script src="gauges/scripts/language.js"></script> 
<?php
if (!isset ($gaugSize) )                { $gaugSize = 221;}
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
echo '	var g_showUvGauge	        = true;'.PHP_EOL;
} else {
echo '	var g_showUvGauge	        = false;'.PHP_EOL;
}
if ($SITE['SOLAR']) {
echo '	var g_showSolarGauge	        = true;'.PHP_EOL;
} else {
echo '	var g_showSolarGauge	        = false;'.PHP_EOL;
}	  
#	ws_popupGraphs	0 = no graphs 1 = Cumulus, 2 = wxGraphs (WD or MH)   3 = weatherlink 4 = weathercat
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
