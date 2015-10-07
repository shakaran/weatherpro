<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'meteoplugAllInOne.php';
$pageVersion	= '3.20 2015-07-11';
#-----------------------------------------------------------------------
# 3.20 2015-07-11 release 2.8 version  ONLY
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# ----------------------- no settings ----------------------------------
#
#-----------------------------------------------------------------------
$url	= '7c747a767d76343c3c3a3c6c393579743d393a25293a3c&engine=javascript';	
$demo   = 'Demo - use your own Meteoplug URL';
if (is_file ('_my_texts/mp_links.txt') ) {
	include '_my_texts/mp_links.txt';
	if (isset ($mp_allinone) && $mp_allinone <> $url){
		$url	= $mp_allinone;
		$demo   = '';
	}
}
?>
<div class="blockDiv" style="background-color: white; text-align: center;">
<h3 class="blockHead" ><?php echo langtransstr('All-in-One').' - '.langtransstr('last three days'); ?>
<br /><?php echo langtransstr("Move the mouse horizontally over the graph and the different values will be displayed"); ?></h3>
<?php if (isset ($demo) && $demo <> '') {echo '<br /><h3>'.$demo.'</h3></br />';} ?>
<iframe  
style="z-index: 11; margin: 0 auto; width:710px; height: 580px; border: none; overflow: hidden;" 
src="http://www.meteoplug.com/cgi-bin/meteochart.cgi?draw=<?php echo $url ?>" >
</iframe>
</div>
