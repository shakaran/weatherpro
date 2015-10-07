<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'airQualityBE_act.php';
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
#   Actual aire quality Belgium
#
#---------------------------------------------------------------------------
if ($lang == 'fr') {$lan = 'fr';} elseif ($lang == 'nl') {$lan = 'nl';} else {$lan = 'en';}
$headText	= langtransstr('Actual Air quality').'&nbsp;'.langtransstr('Provided by').'&nbsp;';
?>
<div class="blockDiv" style="">
<h3 class="blockHead"><?php echo $headText; ?><a href="http://deus.irceline.be" target="_blank">IRCEL - CELINE</a></h3> 
<div class="tabber" style="width: 710px; margin: 10px auto;">
  <div class="tabbertab" style="padding: 0;">
  <h3><?php langtrans('Table'); ?></h3>
  	<div style ="border: 0; width: 704px;  margin: 0 auto; padding: 0;">
	  <iframe src="http://deus.irceline.be/~celinair/index/subindex_air.php?lan=<?php echo $lan; ?>" style="width: 700px; height: 820px; border: none;" >
	    <div style ="background-color: transparent; border: 0; width: 700px; height: 800px; padding: 0; overflow: hidden;"></div>
	  </iframe>
	</div>	
  </div>
  <div class="tabbertab" style="padding: 0;">
  <h3><?php langtrans('Particulate Matter'); ?> (PM10)</h3>
  	<div style ="border: 0; margin: 0 auto; padding: 0;">
	  <img src="http://deus.irceline.be/~celinair/map/rio_out/pm10anim.gif" alt="Particulate Matter" style=""/>
	</div>
  </div>
  <div class="tabbertab" style="padding: 0;">
  <h3><?php langtrans('Ozone'); ?> (O<sub>3</sub>)</h3>
  	<div style ="border: 0; margin: 0 auto; padding: 0;">
	<img src="http://deus.irceline.be/~celinair/map/rio_out/o3anim.gif" alt="Ozone" style=""/>
	</div>
  </div>
  <div class="tabbertab" style="padding: 0;">
  <h3><?php langtrans('Nitrogen Dioxyde'); ?> (NO<sub>2</sub>)</h3>
  	<div style ="border: 0; margin: 0 auto; padding: 0;">
    <img src="http://deus.irceline.be/~celinair/map/rio_out/no2anim.gif" alt="Nitrogen Dioxide" style=""/>
	</div>
  </div>
  <div class="tabbertab" style="padding: 0;">
  <h3><?php langtrans('Sulphur Dioxyde'); ?> (SO<sub>2</sub>)</h3>
  	<div style ="border: 0; margin: 0 auto; padding: 0;">
    <img src="http://deus.irceline.be/~celinair/map/rio_out/so2anim.gif" alt="Sulphur Dioxide" style=""/>
	</div>
  </div>
</div>
</div>
<script type="text/javascript" src="javaScripts/tabber.js"></script>
