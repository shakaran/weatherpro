<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'printKnmi.php';	
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.00 2015-09-06';    # 2014-10-13';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.00 2014-10-13 test version
# ----------------------------------------------------------------------
include 'europe/ws_knmi_fct.php';
#
# If you want to add extra information you can add it here also.


#
# But do not remove the small javascript part below here
?>
<script type="text/javascript">  
if (document.getElementById) {
if (knmiExtra.style.display=="none") knmiExtra.style.display="block"
}
</script>

