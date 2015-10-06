<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;		# display source of script if requested so
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
$pageName	= 'wsnws-alerts.php';
$pageVersion	= '3.20 2015-07-13';
#-----------------------------------------------------------------------
# 3.20 2015-07-13 release 2.8 version
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
#
#####   true=not using cron, update data when cache file expires   false=use cron to update data
#
$wrnStrings	        = '';
include 'nws-alerts-config.php';
$save_nws_updateTime    = $updateTime;                  // these 3 are for running the cronjob information
$save_nws_filename      = $cacheFileDir.$cacheFileName; //
if (file_exists($save_nws_filename) ) 
        {$save_nws_filemtime     = filemtime($save_nws_filename);}
else    {$save_nws_filemtime     = 0;}
$save_nocron            = $noCron;
#
if ( isset($_REQUEST['mu']) && $_REQUEST['mu'] == 1) { $noCron = true; }  // override nocron setting, reload cache
if ( !isset($cron_nws) ) {$cron_nws = false;} 
#
if ($noCron || $cron_nws) {
        $cron_string = 'loading nws-alerts.php';
        if($noCron <> $save_nocron)     {$cron_string .= '<br />&nbsp;&nbsp;as $nocron is set to true with ?mu=1 ';}
        elseif($noCron)                 {$cron_string .= '<br />&nbsp;&nbsp;as $nocron is set to true ';}
        if($cron_nws)                   {$cron_string .= '<br />&nbsp;&nbsp;as cronjob is executed ';}
        echo '<!-- '.$cron_string.' -->'.PHP_EOL;
	include 'nws-alerts.php';
}
include($cacheFileDir.$aboxFileName);
if (trim($alertBox) <> '') {
	$wrnStrings = '<div class="warnBox" style="background-color: green;">
	'.$alertBox.'
</div>'.PHP_EOL;
}
?>