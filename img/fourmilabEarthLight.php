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
$pageName	= 'fourmilabEarthLight.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-24';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
#-----------------------------------------------------------------------
# 3.00 2014-09-24 release version
# ----------------------------------------------------------------------
if (!function_exists ('loadJPEG') ) {
	function loadJPEG (&$retCode, $imgname) { 
		$im = @imagecreatefromjpeg ($imgname); 		// Attempt to open 
		if (!$im) {									// See if it failed
			$retCode = false;
		} // eo test failed 
		return $im; 
	} // eof  loadJPEG
}
$cacheDir	= $SITE['cacheDir'];
$cacheFile	= 'fourmilabEarthLight.jpg';
$cacheTime	= 1800;  // in seconds
#
$urlPic 	= 'http://www.fourmilab.ch/cgi-bin/Earth?img=learth.evif&imgsize=320&dynimg=y&opt=-p&lat=&lon=&alt=&tle=&date=0&utc=&jd=';  
#
# ---- no settings below this line
#
$cacheFile	= $cacheDir.$cacheFile;
$retCodeOK	= true;
#
if (file_exists($cacheFile)) {				// check if a cached version exist
	$fileTime       = filemtime($cacheFile);	// check age of cached file
	$now            = time();
	$diff           = ($now - $fileTime);
	if ($diff <= $cacheTime){			// is it still usable
		echo $cacheFile;
		return;
	} 
}
$im = loadJPEG ($retCodeOK, $urlPic);			// load picture
#
if (!$retCodeOK) {					// something went wrong, probably loading url
	if ((file_exists($cacheFile)) && ($diff < 5*$cacheTime) ) {	// if it is not tooooooo old, try cache 
		echo $cacheFile;
		return;
	}
}
#
if ($retCodeOK) {					// did we correctly load a new picture
	imagejpeg($im,$cacheFile);			// than save it in the cache
	$im = $cacheFile;
} else {
	$im = $urlPic;
}
echo $im;
return;
?>