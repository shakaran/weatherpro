<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'float_top.php';
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
#
if (isset ($SITE['skipTop']) && $SITE['skipTop'] == true) {$string = '#data-area';} else {$string = '#';}
	echo '<script type="text/javascript" src="javaScripts/floating.js"></script>
<div id="float_top" style="position: absolute; width: 24px; height: 15px;">
<a href="'.$string.'" title="Goto Top of Page">
<img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;"></a><br>
</div>
<script type="text/javascript">
	floatingMenu.add("float_top",  
	{	targetBottom: 10,
		targetRight: 0
        });  
</script>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
