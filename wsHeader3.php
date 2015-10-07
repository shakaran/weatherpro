<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsHeader3.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-27-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$frame_link = './ws_gauge_frame.php?lang='.$lang.'&amp;wp='.$SITE['WXsoftware'];
#
echo '<!-- header -->
<div id="header"> 
<div class="headerTitle">
<div style=" float: left; margin: 8px 0 5px 5px;">'.PHP_EOL;
#
if ($SITE['userChangeLang']) {			// insert code for language select 
	echo '<!-- begin language select -->'.PHP_EOL.print_language_selects($p).PHP_EOL.'<!-- end language select -->'.PHP_EOL;
} 	// end code for language select
#
echo '</div>'.PHP_EOL;
if (isset ($SITE['socialSiteSupport']) && $SITE['socialSiteSupport'] == "H") {	// insert code for facebook etc.
	echo '<div style=" float: left; padding-left: 10px;">'.PHP_EOL;
	ws_message ( '<!-- module wsHeader3.php ('.__LINE__.'): loading ./_widgets/social_buttons_header.txt -->');  
	include './_widgets/social_buttons_header.txt';
	echo '</div>'.PHP_EOL;
}
echo '<span  style=" float: right; margin: 0 5px 0 0;"><a href="index.php?default&amp;lang='.$SITE['langBackup'].'" title="Browse to homepage">'.langtransstr($SITE['organ']).'</a></span>
</div>
<iframe src="'.$frame_link.'" style ="width:100%; height: 150px; border: none; overflow: hidden; background: transparent;"></iframe>
</div>
<!-- end header -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 

