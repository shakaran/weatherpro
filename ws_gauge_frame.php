<?php
#-------------------- S e t t i n g s ----------------------------------
#
$gaugSize       = 121;  // ###  size of gauses, always odd 121 151 
$wsDebug        = true; // ###  set to false after testing
#
#--end of settings.  do not change anything below this point  ----------
#
#
#
#
if (isset($_REQUEST['debug']) || $wsDebug == true) {
 	ini_set('display_errors', 'On'); 
	error_reporting(E_ALL);	
}
if (isset($_REQUEST['lang']) ) {
        $lang   = trim($_REQUEST['lang']);
 	$lang   = substr($lang.'en',0,2);
}
$SITE= array();
if (isset($_REQUEST['wp']) ) {
        $string = trim($_REQUEST['wp']);
 	$SITE['switch_wp']      = substr($string.'WD',0,2);
}

include 'wsLoadSettings.php';
#echo $SITE['WXsoftware']; exit;
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
$pageName	= 'ws_gauge_frame.php';
$pageVersion	= '3.11 2015-06-21';

if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
$pathString     .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
#3.11 2015-06-21  release 2.7 version  + added clik to led + removed url error
# -------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" style="overflow-y: hidden;">
<head>
	<meta charset="<?php echo $SITE['charset']; ?>" />
	<meta name="description" content="home - your organization" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $SITE['charset']; ?>"/>
	<link rel="stylesheet" href="./gauges/css/gauges-ss.css" />
	<title>home - your organization</title>
</head>
<body style="margin: 0 8px;">
<?php  echo $pathString; ?>
<!-- steelseries start -->
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
<tr><td colspan="5" style="text-align: right;">
<span style="margin: 0 40px 0 0;">
	<a href="javascript: window.location.reload()" ><canvas id="canvas_led" style="width: 15px; height: 16px;"></canvas></a>&nbsp;&nbsp;&nbsp;
	<canvas id="canvas_status" style="width: 350px; height: 16px;"></canvas>&nbsp;&nbsp;
	<canvas id="canvas_timer" style="width: 50px; height: 16px;"></canvas>
</span>
</td>
</tr>
</table>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<script src="./gauges/scripts/tween.min.js"></script>
<script src="./gauges/scripts/language.js"></script> 
<?php
if (!isset ($gaugSize) )                { $gaugSize = 151;}
if (!isset ($SITE['steelTime']) )       { $SITE['steelTime'] = 30;}
if ($SITE['steelTime'] > 60 )           { $SITE['steelTime'] = 30;}
if ($SITE['steelTime'] < 10 )           { $SITE['steelTime'] = 10;}
#                                                               var g_realTimeURL       = "./gauges/realtime'.$SITE['WXsoftware'].'.php"; 
echo '<script>
        var g_size              = '.$gaugSize.';
        var g_count             = '.$SITE['steelTime'].';
        var wxLang              = "'.$lang.'&wp='.$SITE['WXsoftware'].'";
        var LANG                = LANG_'.strtoupper($lang).';
        var g_showUvGauge	= false;
        var g_showSolarGauge	= false;
        var g_realTimeURL       = "./ws_realtime.php"; 
        var g_showPopupDataGraphs  = false;
        var ws_popupGraphs      = 0;
</script>'.PHP_EOL;
?> 
  <script src="./gauges/scripts/steelseries.min.js"></script>
  <script src="./gauges/scripts/wsGauges.js"></script>
  <script src="./gauges/scripts/ddimgtooltip.min.js"></script>
</body>
</html>