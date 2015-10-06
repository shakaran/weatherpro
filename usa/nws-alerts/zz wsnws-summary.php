<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without our menu system
}
$pageName		= 'wsnws-summary.php';	
$pageVersion	= '0.00 2014-04-20';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
#   
#-------------------------------------------------------------------------------------------------
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;libraries=weather"></script>
<script type="text/javascript" src="usa/nws-alerts/nws-alertmap.js"></script>
<?php

echo '
<div class="blockDiv">
<h3 class="blockHead">
'.langtransstr('nws warnings summary').'</h3>
<br />'.PHP_EOL;

include ('nws-alerts-details-inc.php');
echo '</div>';
?>
