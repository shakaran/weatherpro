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
$pageName	= 'ec_print_dash_fct.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-07-27 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# Display icons of forecasts and link to or fold out of rest of information
#-------------------------------------------------------------------------------
# SETTINGS  
# 
$fullpage_link          = false;                // set to false if no link to full page is wanted, fold-out is used then
#
# END of user SETTINGS
#-------------------------------------------------------------------------------
$caProvince             = trim($SITE['caProvince']);
$caCityCode             = trim($SITE['caCityCode']);
$yourArea	        = $SITE['yourArea'];
#
$ec_page                = 'ec_print_fct';       // script name of the full page  forecast
#
if (!isset ($SITE['pages'][$ec_page] ) ) {$fullpage_link = false;}
#
$script	= 'ec_fct_generate_html.php';
ws_message (  '<!-- module ec_print_dash_fct.php ('.__LINE__.'): loading '.$script.' -->');
include $script;
#
echo '<div class="blockDiv">'.PHP_EOL;
#
if ($fullpage_link == true) {
        $link           =  $SITE['pages'][$ec_page].'&amp;lang='.$lang.$extraP.$skiptopText;       // pagenumber for full forecast page
        $javascript     = '';
} 
else {  $link           =  'javascript:ec_hideshow(document.getElementById(\'ec_extra\'))';
        $javascript     = '<script type="text/javascript">
  function ec_hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>';
}
echo '<div class="blockHead" >'.langtransstr('Weather Forecast').' ('.langtransstr('summary').')&nbsp;&nbsp;';
echo '  <a href="'.$link.'"><img src="./img/i_symbolWhite.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
echo '</div>'.PHP_EOL;
echo $ecIcons;
if (!$fullpage_link) {
        echo '<div id="ec_extra" style="display: none;">'.$ecPlainText.$javascript.PHP_EOL;
        echo '<div class="blockHead"><small>'.$line2.$creditLink.'</small></div></div>';
}
echo '
</div>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
