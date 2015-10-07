<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'airQualityBE_fct.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-18';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-18 release version
# -------------------------------------------------------------------------------------------------
#   forecast air quality belgium
#
#---------------------------------------------------------------------------
if ($lang == 'fr') {$lan = 'FR';} elseif ($lang == 'nl') {$lan = 'NL';} else {$lan = 'EN';}
$headText	= langtransstr('Forecast Air quality').'&nbsp;'.langtransstr('Provided by').'&nbsp;';
?>
<div class="blockDiv" style="background-color: #C0C0C0;">
<h3 class="blockHead"><?php echo $headText; ?><a href="http://deus.irceline.be" target="_blank">IRCEL - CELINE</a></h3>
  <iframe src="http://deus.irceline.be/~celinair/forecast/model/forecast.php?lang=<?php echo $lan; ?>&amp;var_type=ATMOovl&amp;fct_day=-1" 
   style ="background-color: transparent; border: 0; width: 100%; height: 800px; margin: 0; padding: 0;">
  </iframe>	
</div>
