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
$pageName	= 'WU_Live.php';		
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
if (isset ($wuSmall) && $wuSmall ) {
        $wulWidth 	= '450px';
        $wulHeight 	= '300px';
        $link           = '<a href="'.$SITE['pages']['WU_Live'].'&amp;lang='.$lang.$extraP.$skiptopText.'">
<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
} else {
        $wulWidth 	= '600px';
        $wulHeight 	= '400px';
        $link           = '';
}
if      ($SITE['region'] == 'europe') 	{ $units = 'metric'; }
elseif  ($SITE['region'] == 'america')	{ $units = 'imperial'; }
else 				        { $units = 'both';}
#
$myUrl		= "http://www.wunderground.com/swf/Rapid_Fire.swf?units=".$units."&station=".$SITE['wuID'];

?>
<script type="text/javascript" src="javaScripts/swfobject.js"></script>
<div class="blockDiv" style="overflow:hidden; ">
<h3 class="blockHead"><?php echo langtransstr('Live Data').' '.langtransstr('from Weather-Underground').'. '.$link; ?></h3>
<div style= "width: <?php echo $wulWidth; ?>; padding: 5px; margin: 0px auto;">
  <div id="wulContent">
<?php 
if (!isset ($flash_replaced)  && !isset ($conflictSteel)) {
	$flash_replaced	= true;
	$script  = 'gauges/gauge_no_flash.php';
	ws_message (  '<!-- module WU_Live.php ('.__LINE__.'): loading '.$script.' -->');
	include $script;
	echo '</br />
<p style="text-align: center;"><small>No FLASH-support found in your browser.   Weather-Underground-live display is replaced with Steelseries</small></p>
';
}
else { 	echo '<b>Weather-Underground</b><br />can not be run, no Flash support available';
}
?>
  </div>
</div>
   <script type="text/javascript">
    swfobject.embedSWF("<?php echo  $myUrl; ?>", "wulContent", "<?php echo $wulWidth; ?>;", "<?php echo $wulHeight; ?>", "9.0.0");
    </script>
</div>
<!-- end of WU Live -->
