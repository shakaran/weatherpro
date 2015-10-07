<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wnGeneralFrame.php';		// #### change to exact page name
$pageVersion	= '3.20 2015-09-17';
#-------------------------------------------------------------------------------
# 3.20 2015-08-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# Settings:
#
$frameHeight	= '1600px'; 
#   
#-------------------------------------------------------------------------------------------------
$wnCode 	= $SITE['mesoID'];		//  Here the code of your Weather Network is inserted
$wnName 	= $SITE['mesoName'];		// and here the name of your Weahter Network
$wnScript	= '../'.$SITE['mesoID'].'-mesomap/'.$SITE['mesoID'].'-mesomap-testpage.php';
#
if (file_exists($wnScript) ) {$script_ok = true;} else  {$script_ok = false;}
#
echo '<div class="blockDiv">'.PHP_EOL;
echo '<h3 class="blockHead">'.langtransstr($wnName).'</h3>'.PHP_EOL;
if ($script_ok) {
        echo '<iframe src="'.$wnScript.'" style ="width:100%; height: '.$frameHeight.';"></iframe>'.PHP_EOL;
}
else {  echo '<h3 style="text-align: center;">Script not found: '.$wnScript.'</h3>'.PHP_EOL;
}
echo '</div>'.PHP_EOL;

# ----------------------  version history
# 3.20 2015-08-26 release 2.8 version 

