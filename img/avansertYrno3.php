<?php   # ini_set('display_errors', 1);    error_reporting(E_ALL);
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
$pageName	= 'avansertYrno3.php';			// the name of this module 
$pageVersion    = '3.11 2014-02-11';			// the version  = last version
#
if (!function_exists ('LoadPNG') ) {
        function LoadPNG (&$retCode, $imgname) { 
                $im = @imagecreatefrompng ($imgname); 	// Attempt to open 
                if (!$im) {				// See if it failed
                        $retCode = false;
                } // eo test failed 
                return $im; 
        } // eof  loadPNG
}

$cacheFile	= $SITE['cacheDir'].'avansertYrno3.png';
$yrTotalPng 	= 'http://www.yr.no/place/'.$SITE['yrnoID'].'avansert_meteogram.png';  

$cacheTime	= 3600;
$retCodeOK	= true;
if (file_exists($cacheFile)) {				// check if a cached version exist
	$fileTime       = filemtime($cacheFile);	// check age of cached file
	$now            = time();
	$diff           = ($now - $fileTime);
	if ($diff <= $cacheTime){			// is it still usable
		$im     =  $cacheFile.'?'.date('YMDh');
		return;
	} 
}
$im = LoadPNG ($retCodeOK, $yrTotalPng);		// load picture
if (!$retCodeOK) {							// something went wrong, probably loading url
	if ((file_exists($cacheFile)) && ($diff < 10*$cacheTime) ) {	// if it is not tooooooo old, try cache 
                $retCodeOK = true;
	}
}
if ($retCodeOK) {					// did we correctly load a new picture
	imagepng($im,$cacheFile);			// than save it in the cache
	$im = $cacheFile.'?'.date('YMDh');
} else {
	$im = $yrTotalPng;
}
?>