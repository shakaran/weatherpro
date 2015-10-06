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
$SITE			= array();
#
$pageName		= 'ws_realtime.php';
$pageVersion	        = '3.11 2015-02-13';
$SITE['wsModules'][$pageName]   = 'version: ' . $pageVersion; 
$pageFile = basename(__FILE__);	
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName.' - '.$pageVersion; }
$realtimeScript         = $pageName.' - '.$SITE['wsModules'][$pageFile];
# ------------------------------------------------------------------------------
# to load the realtime data into string and send it back to the requestingsteelseries javascript handler 
# ------------------------------------------------------------------------------
#
include         'wsLoadSettings.php';
$realtime       = true;
$rtOut          = 'steel';
include $SITE['wsAjaxDataLoad'];
return;
?>