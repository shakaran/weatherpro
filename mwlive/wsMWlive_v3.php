<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsMWlive_v3.php';
$pageVersion	= '3.20 2015-07-18';
#-------------------------------------------------------------------------------
# 3.20 2015-07-18 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$thisFolder 	= './mwlive/';		        // normally the folder this script is executed from
$this_time      = 30;                           // number of seconds to wait before retrieving new data
if (isset ($SITE['steelTime']) )        {$this_time     = $SITE['steelTime'] ;}
if (isset ($SITE['mwliveTime']) )       {$this_time     = $SITE['mwliveTime'] ;}	

$this_swf       = $thisFolder.'mwdisplay.swf';  // the Meteoware Flash script
$this_datasource= $SITE['meteowareFile'].'?lang='.$lang.'&wp='.$SITE['WXsoftware'].'&refresh='.$this_time.'&key=nokey';
# ---------------------------------------------------------------------------------------
?>
<div class="blockDiv">
<h3 class="blockHead"><?php echo langtransstr('Live weatherdata from'); ?> Meteoware</h3> 
<div style="background-color: #FAFAFA; height: 500px; overflow: hidden;"> 
	<div id="mwlwidget" style="">
<?php 	
if (!isset ($flash_replaced) && !isset ($conflictSteel)) {
	$flash_replaced	= true;
	echo '<br />'.PHP_EOL;
	$script  = 'gauges/gauge_no_flash.php';
	ws_message (  '<!-- module wsMWlive_v3.php ('.__LINE__.'): loading '.$script.' -->');
	include $script;
	echo '</br /></br />
<p style="text-align: center;"><small>No FLASH-support found in your browser.  Meteoware display is replaced with Steelseries</small></p>
';
}
else { 	echo '<b>Meteoware</b><br />can not be run, no Flash support available';
}
?>
	</div>
</div>
</div>
<script type="text/javascript" src="javaScripts/swfobject.js"></script>
<script type="text/javascript">
        var flashVars = {  
        	datasource:  "<?php echo $this_datasource; ?>" 
        };
        var params = {
        	wmode: "transparent;",
        	play: "true",
        	src:  "<?php echo $this_swf; ?>",
                quality: "high",
                bgcolor: "#FAFAFA",
                pluginspage: "http://www.macromedia.com/go/getflashplayer",
                
        };
        var attributes = {
        	style: "margin: -100px auto; z-index: 1"
        }
        swfobject.embedSWF(
                "<?php echo $this_swf; ?>", 
                "mwlwidget",
                "100%",
                "600px",
                "9",
                "expressInstall.swf",
                flashVars,
                params,
                attributes
        );
</script>
<?php
# ----------------------  version history
# 3.20 2015-07-18 release 2.8 version 
