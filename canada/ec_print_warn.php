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
$pageVersion	= '3.20 2015-08-02';
#-------------------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
if ($SITE['pageWidth'] < 1000) {$width = '700px';} else {$width = '800px';}
#
if (!isset ($_REQUEST['urlwarn']) )
     {  $page = 'http://weather.gc.ca/warnings/';
}
else {  $page = $_REQUEST['urlwarn'];
}
echo '<!-- EC warnings -->
<div class="blockDiv" style="background-color: #f9f9f9;">
<div style="width: '.$width.'; margin: 0 auto; ">
<iframe  id="iframe" src="./canada/frame.php?width='.$width.'&amp;url='.$page.'" 
style="margin-top: 0px;  height: 1200px; width:'.$width.'; " frameborder="0" scrolling="yes">
</iframe>
</div>
</div>
<!-- end of EC warnings -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
