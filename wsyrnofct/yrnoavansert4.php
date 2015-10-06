<?php # ini_set('display_errors', 1);  error_reporting(E_ALL);
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;			// display source of script if requested so
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
$pageName	= 'yrnoavansert4.php';
$pageVersion    = '3.20 2015-08-02';
#-----------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
if (!function_exists ('LoadPNG') ) {
        function LoadPNG (&$retCode, $imgname) { 
                $im = @imagecreatefrompng ($imgname); 		// Attempt to open 
                if (!$im) {									// See if it failed
                        $retCode = false;
                } // eo test failed 
                return $im; 
        } // eof  loadPNG
}
if (!isset($yrnoID) ) {$yrnoID = $SITE['yrnoID'];}
$yr_id_string   = str_replace (' ','_',trim($yrnoID));
$cache_string   = str_replace ('/','_',trim($yrnoID));
if (!function_exists ('stripAccents') ){
        function stripAccents($str) {
            return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝæøØÅå'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUYaoOAa');
        }
}
$cache_string   = stripAccents( $cache_string );
$cacheFile	= $SITE['cacheDir'].'avansertYrno4_'.$cache_string.'.png';
$yrTotalPng 	= 'http://www.yr.no/place/'.$yr_id_string.'/avansert_meteogram.png';  
$retCodeOK	= true;
$cacheAllowed   = 7200;
if (file_exists($cacheFile)){	
        $file_time      = filemtime($cacheFile);
        $now            = time();
        $diff           = ($now - $file_time);
        ws_message (  '<!-- module yrnoavansert4.php ('.__LINE__.'): '.$cacheFile."
	cache time   = ".date('c',$file_time)." from unix time $file_time
	current time = ".date('c',$now)." from unix time $now 
	difference   = $diff (seconds)
	diff allowed = $cacheAllowed (seconds) -->");	
        if ($diff <= $cacheAllowed){			// is it still usable
                $im = 	$cacheFile;
                ws_message (  '<!-- module yrnoavansert4.php ('.__LINE__.'): image returned = '.$im.' -->');
                return;
        } 
}
ws_message (  '<!-- module yrnoavansert4.php ('.__LINE__.'): loading fresh image -->');
$im 	= LoadPNG ($retCodeOK, $yrTotalPng);		// load picture
if (!$retCodeOK) {					// something went wrong, probably loading url
        ws_message (  '<!-- module yrnoavansert4.php ('.__LINE__.'): no good return code, up cache time 5* -->');
	if ((file_exists($cacheFile)) && ($diff < 5 * $cacheAllowed) ) {	// if it is not tooooooo old, try cache 
                ws_message (  '<!-- module yrnoavansert4.php ('.__LINE__.'): cached image not to old -->');
		$im = 	$cacheFile;
		return;
	}
	ws_message ('<!-- module yrnoavansert4.php ('.__LINE__.'): no good image load, returned url = '.$im.' -->',true);
	$im 	= $yrTotalPng;
	return;
}						// we correctly loaded a new picture
ws_message ('<!-- module yrnoavansert4.php ('.__LINE__.'): try to save image in cache -->');
$return = imagepng($im,$cacheFile);		// than save it in the cache
if ($return) {
	$im 	= $cacheFile;
	ws_message ('<!-- module yrnoavansert4.php ('.__LINE__.'): image returned = '.$im.' -->');
	return;
}
ws_message ('<!-- module yrnoavansert4.php ('.__LINE__.'):Save to cache error - returned url = '.$im.' -->',true);
$im 	= $yrTotalPng;
return;
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
