<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsDashAvansert.php';
$pageVersion	= '3.20 2015-07-14';
# ----------------------------------------------------------------------
# 3.20 2015-07-14  release 2.8 version
# ----------------------------------------------------------------------
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# ----------------------------------------------------------------------
$strImg	= 'Yr.no 2 '.langtransstr('day graphical forecast for').' '.$SITE['organ'];
$title	= langtransstr('YRno forecast');
if (isset ($SITE['pages']['ws_yrno_page']) ) 
{
	$strLnk	= '"'.$SITE['pages']['ws_yrno_page'].'&amp;lang='.$lang.$extraP.$skiptopText.'" title="'.$title;
}
else 
{
		$strLnk	= '"http://www.yr.no/place/'.$SITE['yrnoID'].'" target="_blank" title="'.$title;
}
require_once 'wsyrnofct/yrnoavansert4.php';
#
echo '
<div class="blockDiv" style="background-color: grey;">
	<h3 class="ajaxHead" style= "margin:0px;">'.$strImg.'
		<a href='.$strLnk.'">
			<img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information">
    		</a>
	</h3>
	<img src="'.$im.'" alt="'.$strImg.'" style="width: 100%; height: 302px; vertical-align: bottom;" />
</div>'.PHP_EOL;
