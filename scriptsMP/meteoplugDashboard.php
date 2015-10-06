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
$pageName		= 'meteoplugDashboard.php';
$pageVersion	= '3.20 2015-07-11';
#-----------------------------------------------------------------------
# 3.20 2015-07-11 release 2.8 version  ONLY
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# ---------------------------settings ----------------------------------
#
# This is the key part for the Meteplug dashboard URL 
#
$draw	= '9595949d9c8cc0cfccc2cd9ddedfd6d79fd4c6c6c2f28ac7c3dcc3c3d0d2';
# 
$demo   = '<br /><h3 style="color: white;">Demo - use your own Meteoplug URL</h3><br />'; 
# ------------------------end of settings ------------------------------
#
if (is_file ('_my_texts/mp_links.txt') ) {
	include '_my_texts/mp_links.txt';
	if (isset ($mp_dashboard) && $mp_dashboard <> $draw){
		$draw	= $mp_dashboard;
		$demo   = '';
	}
}
if ($SITE['WXsoftware'] == 'MB') {
        if (trim($SITE['uomTemp']) <> '&deg;C') { 
        	$link 	= $ws['wsDashboardImp']; 
        } 
        else { 	$link 	= $ws['wsDashboardDec']; 
        }       
}
else {	$link 	= 'http://www.meteoplug.com/cgi-bin/meteochart.cgi?draw='.$draw;	
}
?>
<div class="blockDiv" style="text-align: center; background-color: black;">
<h3 class="blockHead"><?php echo langtransstr('Live weatherdata from'); ?> MeteoPlug</h3><?php echo $demo; ?>
<div style="width: 620px; margin: 0 auto;">
    <iframe style="text-align: center; border: 0px; height: 340px; width: 620px;" src="<?php echo $link; ?>">
    </iframe>
</div>
</div>
