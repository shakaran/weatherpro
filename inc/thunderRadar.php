<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'thunderRadar.php';
$pageVersion	= '3.20 2015-07-13';
#-------------------------------------------------------------------------------
# 3.20 2015-07-13 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# -------------------------------------Settings:      --------------------------
if (!isset ($ws['img_lightning']) ) {
	$script	= '_my_scripts/set_links.php';
	ws_message (  '<!-- module thunderRadar.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
}
$img_lightning  =$ws['img_lightning'].'?t='.time();
#
$imgWidth	= '925px';	// natural size 925px
$imgWidth	= '100%';
#
$imgStyle	= 'style="width:'.$imgWidth.'; vertical-align: bottom;"';
# 
#-------------------------------------------------------------------------------
#
echo '<div class="blockDiv" style="text-align: center;">
  <h3 class="blockHead">'.langtransstr('Thunderstorms').'<br />'.langtransstr('Go to the blitzortung.org site with a lot of extra information').'
   <a href="http://www.blitzortung.org/Webpages/index.php?lang='.$lang.'" target="_blank">
     <img src="./img/submit.png" style="margin: 1px; vertical-align: middle; width: 15px;" alt="more information" title="more information" /> 
   </a>
  </h3>
  <img src="'.$img_lightning.'" alt="blitzortung!" '.$imgStyle.' /> 
</div>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-13 release 2.8 version 

