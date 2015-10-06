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
$pageName	= 'printZambretti.php';	
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$script	= 'forecasts/wsZambretti.php';
ws_message (  '<!-- module printZambretti.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
#
# If you want to add extra information you can add it here also.


#
# But do not remove the small javascript part below here
?>
<script type="text/javascript">  
if (document.getElementById) {
if (zamExtra.style.display=="none") zamExtra.style.display="block"
}
</script>
<?php
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
