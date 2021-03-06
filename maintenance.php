<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);

$pageName	= 'maintenance.php';
$pageVersion	= '3.00 2015-05-23';    // added FR split
#
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) 
{
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}

#-------------------------------------------------------------------------------
# Check if you want a message such as "Site is in maintenance"  if there is such a file found 
#-------------------------------------------------------------------------------
if (isset ($SITE['maintenanceTxt']) && file_exists($SITE['maintenanceTxt']) ) 
{
        $ownWarning     = file_get_contents($SITE['maintenanceTxt']);
        if (strlen($ownWarning) >= 4 && substr($ownWarning,0,4) <> 'none') 
        {
                $ownWarning     = iconv('UTF-8',$SITE['charset'].'//TRANSLIT', $ownWarning );
                echo '<div class="warnBox">'.PHP_EOL.$ownWarning.PHP_EOL.'</div>'.PHP_EOL;
        } // eo check warning file is to be displayed
}  // eo check own warning  && warning file exist
#
