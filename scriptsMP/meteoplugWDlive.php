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
$pageName	= 'meteoplugWDlive.php';		
$pageVersion	= '3.20 2015-07-11';
#-----------------------------------------------------------------------
# 3.20 2015-07-11 release 2.8 version  ONLY
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
# ---------------------------settings ----------------------------------
$width  = 800;			// width of the WD-live flash
$height = (int) ($width / 2 );
#
#-----------------------------------------------------------------------
#
$url	= '6263696d6a790c05154d03100a160a0117070c462b30382e3e32';	
$demo   = 'Demo - use your own Meteoplug URL</h3>';
$blockheight    = $height;
if (is_file ('_my_texts/mp_links.txt') ) {
	include '_my_texts/mp_links.txt';
	if (isset ($mp_wdlive) && $mp_wdlive <> $url){
		$url	= $mp_wdlive;
		$demo   = '';
	}
}
?>
<div class="blockDiv" style=" text-align: center; background-color: white;">
<h3 class="blockHead"><?php langtrans('WD-Live Data'); ?> &nbsp;&nbsp;(<?php langtrans(' - MeteoPlug'); ?>).</h3>
<?php if (isset ($demo) && $demo <> '') {echo '<br /><h3>'.$demo.'</h3></br />';}  ?>
<iframe style=" text-align: center; margin: 0 auto; overflow:hidden; border: 0px;  background: transparent;  
                width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;" 
        src="http://www.meteoplug.com/cgi-bin/meteochart.cgi?draw=<?php echo $url; ?>">
</iframe>
</div>