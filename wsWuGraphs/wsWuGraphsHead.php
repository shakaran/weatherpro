<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
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
$pageName	= 'wsWuGraphsHead.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-09-12 release version
# ----------------------------------------------------------------------
# Project:   template ==> WU GRAPHS
# Module:    wsWuGraphs.php 
#
# To do: 
#   
#---------------------------------------------------------------------------
#
#
#
ini_set('display_errors', 'Off'); 
$noDocready = true;
require_once('./wxwugraphs/WUG-settings.php');
echo '<meta name="robots" content="index, nofollow" />'.PHP_EOL;
echo '<link type="text/css" href="'.$tabsStyleFile.'" rel="stylesheet" />'.PHP_EOL;
echo '<script type="text/javascript" src="'.$jQueryFile.'"></script>'.PHP_EOL;
echo '<script type="text/javascript" src="./wxwugraphs/js/jq-core-widget-tabs.min.js"></script>'.PHP_EOL;

$JSalertOut = isset($JSalert) ? 'alert('.$JSalert.');' : '';
$scLang = $lang;
echo '
<script type="text/javascript">
	      $(function() {
          $("#WU-MDswitch").tabs({
            cookie: {
              expires: 30,
              path: "'.$_SERVER["PHP_SELF"].'"
            },
            spinner: \''.$Tloading.'\',
            cache: true
          }); 
        });       
</script>

<script type="text/javascript">
// auto height
$("WUG-foot").ready(function() {
  var mcheight = document.getElementById("data-area").offsetHeight;
  document.getElementById("WUG-tabbed").style.height = mcheight + "px";
});

// Some reports as JS alerts
'.$JSalertOut.'
</script>	    
';
?>
<style type="text/css">
.WUG-subtab .ui-tabs-panel {padding:0px;}
#WUGcInfo {
font: 8pt Tahoma,Verdana,Arial,sans-serif;
/*border-top:1px solid #bbb;*/
}
.c-lside a {
font-weight: normal !important;
text-decoration: underline !important;  
color: #bbb !important;
}
.c-rside a {
color: #75A3D1 !important;
}
#main-copy {
font-size: 9pt;
<?php
echo 'background-color:'.$pgBGC.';
color:'.$wugfontColor.';
';
?> 
}
.ui-tabs-panel {
<?php echo 'background-color:'.$pgBGC.';'; ?>
}
</style>