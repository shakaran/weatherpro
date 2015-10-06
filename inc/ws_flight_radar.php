<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'ws_flight_radar.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
$style_selected = 'style="margin: 4px;  background-color: grey;"';
$style_other    = 'style="margin: 4px;  background-color: white;"';
#
$page   = 'simple';
if      (isset ($_REQUEST['flight_simple']) )         {$page   = 'simple';}
if      (isset ($_REQUEST['flight_normal']) )         {$page   = 'normal';}


$form   = '<form method="get" style="display: inline;">'.PHP_EOL;
if ($page == 'simple') { $style     = $style_selected; }  else { $style = $style_other; }
$form   .= '  <input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" value="'.$p.'">
  <input type="submit" id="flight_simple" name="flight_simple" value="Normal view" '. $style.'>'.PHP_EOL;
if ($page == 'normal') { $style     = $style_selected; }  else { $style = $style_other; }
$form   .= '  <input type="submit" id="flight_normal" name="flight_normal" value="complex view" '. $style.'>
</form>'.PHP_EOL;

$page_title     = langtransstr('Flight radar');

if ($page == 'normal')  { $frame_link   = 'http://www.flightradar24.com/'.$SITE['latitude'].','.$SITE['longitude'].'/7';}
else                    { $frame_link   = 'http://www.flightradar24.com/simple_index.php?lat='.$SITE['latitude'].'&amp;lon='.$SITE['longitude'].'&amp;z=6';}
#
$link1          = 'http://www.flightradar24.com/';

$credit         = langtransstr('Map created by').' Flightradar24.com <a href="'.$link1.'" target="_blank"><img src="./img/submit.png" 
style="margin: 1px; vertical-align: bottom;" alt="more information" title="more information"></a>';
#
echo '<!-- flight radar -->
<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'</h3>
<div class="blockHead">'.$form .'</div>
<iframe src="'.$frame_link.'" style="border: none; width: 100%; height: 800px; margin: 0px; padding: 0px; vertical-align: bottom;"></iframe>
<h3 class="blockHead" style="padding: 4px;">'.$credit.'</h3>
</div>
<!-- end of flightradar script -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
