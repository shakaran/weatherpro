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
$pageName	= 'wsnws-details.php';
$pageVersion	= '3.20 2015-07-13';

if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.20 2015-07-13  rel. 2.8 version
#-----------------------------------------------------------------------
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;libraries=weather"></script>
<script type="text/javascript" src="usa/nws-alerts/nws-alertmap.js"></script>
<div class="blockDiv">
<?php
if (isset ($_REQUEST['a']) && '' <> trim($_REQUEST['a']) ){
	echo '<h3 class="blockHead">'.langtransstr('nws warnings details').'</h3><br />'.PHP_EOL;
	include ('nws-alerts-details-inc.php');
} else {
	echo '<h3 class="blockHead">'.langtransstr('nws warnings summary').'</h3><br />'.PHP_EOL;
	include ('nws-alerts-summary-inc.php');	
}
?>
<br />
<h3 class="blockHead">
<small>Scripts developed by&nbsp;Curly of 
<a href="http://www.weather.ricksturf.com/" target="_blank">Michana Weather.</a>
</small>
</h3>
</div>

