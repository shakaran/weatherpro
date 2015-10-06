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
$pageName	= 'wsDashEarth.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2014-10-18';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-09-20 release version
# ----------------------------------------------------------------------
?>
<!-- Current day/night from Fourmilab  -->
<div class="blockDiv" style="background: grey; color: white;">
 <h3 class="blockHead"><?php echo langtransstr('Current Day / Night Map');  ?>&nbsp;&nbsp;
 <?php
 if ($phpself <> $SITE['pages']['astronomy']) { 
  echo '   <a href="'.$SITE['pages']['astronomy'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>'.PHP_EOL;
}
?>
 </h3>
 <img style="width: 100%; vertical-align: top;" src="<?php include ('img/fourmilabEarthLight.php'); ?>"  alt="Day / Night Map" />
</div>
<!-- end of current day/night from Fourmilab  -->