<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsAbout.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2015-02-16';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#
#-----------------------------------------------------------------------------------------
# 3.00 2015-02-16
#-----------------------------------------------------------------------------------------
#
echo '<div class="blockDiv">'.PHP_EOL;
$folder         = '_my_texts/';
$file           = $folder."about-".$lang.'.html';
if(file_exists($file) ) {
	include_once $file;
} else {
	echo "<h3>Sorry, no '".$lang."' version of this page can be found.</h3>";
	include_once  $folder.'about-en.html';    
}
?>
</div>