<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'sitemap.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-19';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-19 release version
# -------------------------------------------------------------------------------------------------
?>
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('Website Map') ?></h3>
<br />
<ul>
<?php echo $DropdownMenuText; ?>
</ul>
<br />
</div>