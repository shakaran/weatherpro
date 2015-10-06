<?php
# ---------------HERE YOU NEED TO MAKE SOME CHANGES ----------------------
#
$wsreportsDir		= './wsreports/';	// only change this if you stored the wsreports scripts in another folder
#
# --------------- END OF SETTINGS ----------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { //--self downloader --
   $filenameReal = __FILE__;            # display source of script if requested so
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
#
# print version of script in the html of the generated page
#
$pageName	= 'wsReportsStart.php';
$pageVersion	= '3.01 2015-05-23';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
$saved_lang		= $lang;
$insideTemplate		= $insideLeuven = true;
$color			= true;
$SITE['mypage'] 	= $phpself;
$charset		= $SITE['charset'];     //  'ISO-8859-1';	'windows-1252';  'UTF-8';
echo '<!-- charset set to: '.$charset.' -->'.PHP_EOL;
#
echo '<div class="blockDiv">'.PHP_EOL;
include 'wsReports1part.php';
echo '</div>'.PHP_EOL;
#
$SITE['lang'] = $lang = $saved_lang;