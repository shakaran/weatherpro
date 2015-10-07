<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsHeader1.php';
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
echo '<!-- header -->
<div id="header" style="height: 90px;"> 
<div class="headerTitle">
<div style=" float: left; margin: 8px 0 5px 5px;">'.PHP_EOL;
#
if ($SITE['userChangeLang']) {			// insert code for language select 
	echo '<!-- begin language select -->'.PHP_EOL.print_language_selects($p).PHP_EOL.'<!-- end language select -->'.PHP_EOL;
} 	// end code for language select
#
echo '</div>'.PHP_EOL;
if (isset ($SITE['socialSiteSupport']) && $SITE['socialSiteSupport'] == "H") {	// insert code for facebook etc.
	echo '<div style=" float: left; padding-left: 10px;">'.PHP_EOL;
	ws_message ( '<!-- module wsHeader3.php ('.__LINE__.'): loading ./_widgets/social_buttons_header.txt -->');  
	include './_widgets/social_buttons_header.txt';
	echo '</div>'.PHP_EOL;
}

?>
<span  style=" float: right;">
<a href="index.php?default" title="Browse to homepage"><?php echo langtransstr($SITE['organ']); ?></a>
</span>
</div><!-- end headerTitle -->
<div class="headerTemp"><!-- headerTemperature gives large temp at right side -->
<span class="doNotPrint">
  <span class="ajax" id="ajaxbigtemp"><?php echo $vars['ajaxbigtemp']; ?></span>
</span>
</div><!-- end headerTemp -->
<div class="subHeaderRight"> <!-- header right gizmo -->
<?php
#---------------------------------------------------------------------------
#		Ajax gizmo
#---------------------------------------------------------------------------
if ((int)($gizmo) >= 1  && isset($SITE['ajaxGizmoShow']) && $SITE['ajaxGizmoShow']) {  
        include_once($SITE['ajaxGizmo']);
} else  {
        echo '&nbsp;<br/><br/><br/>'.PHP_EOL; // needed as placeholder if no gizmo 
}
?>
</div> <!-- end header right gizmo -->
</div> <!-- end header -->
<?php
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 

