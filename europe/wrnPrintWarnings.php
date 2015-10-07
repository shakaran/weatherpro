<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wrnPrintWarnings.php';
$pageVersion	= '3.01 2015-04-02';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
#-------------------------------------------------------------------------------
# Display a list of warnings from other sources
#-------------------------------------------------------------------------------
?>
<div class="blockDiv">
<div style="width: 100%; margin: 0 auto;">
<?php 
echo $ownpagehtml;
echo '</div>'.PHP_EOL;
?>
<h3 class="blockHead">
<small><?php langtrans('Original script (v3) by'); ?>&nbsp;<a href="http://leuven-template.eu/index.php?lang=<?php echo $lang; ?>" target="_blank">Weerstation Leuven</a></small>
</h3>
</div>
