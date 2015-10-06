<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'wsnws-alerts.php';
$pageVersion	= '2.5x 2014-04-22';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 2.5x 2014-04-22 release version 
#---------------------------------------------------------------------------
# First check of there you want your own warning to be displayed. fi: Site is in maintenanc
#-----------------------------------------------------------------------------------------

$wrnHead	= 	$wrnStrings	= '';

if ($SITE['warningTxt'] == true) {
	 $file	=	$SITE['warnTxt'];
	 if (file_exists($file)) {
		$ownWarning = file_get_contents($file);
		if (strlen($ownWarning) >= 4 && substr($ownWarning,0,4) <> 'none') {
			$wrnHead	.= '<div class="warnBox">'.PHP_EOL;
			$wrnHead	.= $ownWarning.PHP_EOL;
			$wrnHead	.= "</div>".PHP_EOL; 		
		} // eo check warning file is to be displayed
	 }  // eo warning file exist
}  // eo check own warning

#-----------------------------------------------------------------------------------------
# Now check if we want to include warnings on every page
#-----------------------------------------------------------------------------------------
#
include('nws-alerts-config.php');
#
if ( isset($_REQUEST['mu']) && $_REQUEST['mu'] == 1) { $noCron = true; }  // override nocron setting, reload cache
#
if ($noCron == true) {
	include('nws-alerts/nws-alerts.php');
}
include($cacheFileDir.$aboxFileName);
if (trim($alertBox) <> '') {
	$wrnStrings = '<div class="warnBox" style="background-color: transparent;">
	'.$alertBox.'
</div>'.PHP_EOL;
}
 ?>
