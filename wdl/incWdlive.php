<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'incWdlive.php';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
if (isset ($wdlSmall) && $wdlSmall ) {
        $wdlHeight 	= '300px';
        $wdlWidth 	= '810px';
        $link           = '<a href="'.$SITE['pages']['incWdlive'].'&amp;lang='.$lang.'">
<img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
} else {
        $wdlHeight 	= '540px';
        $wdlWidth 	= '810px';
        $link           = '';
}
$this_folder            = './wdl/';

$this_config 	        = $SITE['siteUrl'].$SITE['clientrawDir'].'wdl-'.$lang.'-config.xml';
$default_config         = $SITE['siteUrl'].$SITE['clientrawDir'].'wdl-en-config.xml';
if (!file_exists ($SITE['clientrawDir'].'wdl-'.$lang.'-config.xml')  )      {$this_config = $default_config;}
#
$this_url		= $this_folder.'wdlv6_09.swf'.'?'.$this_config;

$head_text              = langtransstr('Live Data').' '.langtransstr('by WD live').' '.$link;
?>
<!-- WD Live  -->
<div class="blockDiv">
<h3 class="blockHead"><?php echo $head_text;  ?></h3>
  <div style = "width: <?php echo $wdlWidth; ?>; padding: 5px; margin: 0px auto;">
	<div id="wdlwidget" >
<?php 	
if (!isset ($flash_replaced) && !isset ($conflictSteel) ) {
	$flash_replaced	= true;
	$script  = 'gauges/gauge_no_flash.php';
	ws_message (  '<!-- module incWdlive.php ('.__LINE__.'): loading '.$script.' -->');
	include $script;
	echo '<p style="text-align: center;"><small>No FLASH-support found in your browser.  WeatherDisplay-Live is replaced with Steelseries</small></p>
';
}
else { 	echo '<b>Weather Display Live</b><br />can not be run, no Flash support available';
}
?>
	</div>
  </div>
</div>
<script type="text/javascript" src="javaScripts/swfobject.js"></script>
<script type="text/javascript">
        var flashVars = {    
        };
        var params = {
                quality: "high",
                bgcolor: "#FFFFFF",
                allowscriptaccess: "always",
                allowfullscreen: "true",
                menu: "false",
                wmode: "transparent",
        };
        var attributes = {
                id:"wdlwidget",
                name:"wdlwidget"    
        }
        swfobject.embedSWF(
                "<?php echo  $this_url; ?>", 
                "wdlwidget",
                "<?php echo $wdlWidth; ?>",
                "<?php echo $wdlHeight; ?>",
                "9",
                "expressInstall.swf",
                flashVars,
                params,
                attributes
        );
</script>
<!-- end of WD Live  -->
<?php
# ----------------------  version history
# 3.20 2015-07-28 release 2.8 version 
