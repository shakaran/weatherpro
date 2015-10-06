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
$pageName	= 'ec_print_aqhi.php';
$pageVersion	= '3.20 2015-08-02';
#-------------------------------------------------------------------------------
# 3.20 2015-08-02 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
if ($SITE['pageWidth'] < 1000) {$width = '700px';} else {$width = '800px';}
#
if (strtolower($lang) == 'fr') {
	$langcode = 'f';
} 
else {	$langcode = 'e';
}
$aqhiArea_f	= mb_strtolower($SITE['aqhiArea-f'],'UTF-8');
$aqhiArea	= mb_strtolower($SITE['aqhiArea'],'UTF-8');
$province	= mb_strtolower($SITE['caProvince'],'UTF-8');
#
$url 		= 'http://weather.gc.ca/airquality/pages/provincial_summary/'.$province.'_'.$langcode.'.html';
#
$cacheDir       = $SITE['cacheDir'];
$cacheFileName	= $cacheDir.'ec_aqhi_urls_'.$province.'_'.$langcode.'.txt';
#
$dataLoaded	= false;
#
if (file_exists($cacheFileName)) {
# echo "<!-- checking weatherdata ($cacheFileName) in cache-->".PHP_EOL;	
	$file_time      = filemtime($cacheFileName);
	$now            = time();
	$diff           = ($now-$file_time);
	$cacheAllowed   = 16*3600;
        ws_message ('<!-- module ec_print_aqhi.php ('.__LINE__.'): '.$cacheFileName.' times are
        cache time   = '.date('c',$file_time).' from unix time '.$file_time.'
        current time = '.date('c',$now).' from unix time '.$now.' 
        difference   = '.$diff.' seconds
        diff allowed = '.$cacheAllowed.' seconds -->');		
	if ($diff <= $cacheAllowed){
		if (isset ($_REQUEST['force']) && $_REQUEST['force'] == 'urls') {
			ws_message ('<!-- module ec_print_aqhi.php ('.__LINE__.'): '. $cacheFileName.' skipped as "&force=urls" was used-->');
			$dataLoaded	= false;
		}
		else  {	ws_message ('<!-- module ec_print_aqhi.php ('.__LINE__.'): '. $cacheFileName.' loaded from cache-->');
			$arrLocation 	= unserialize(file_get_contents($cacheFileName));
# echo '<pre>'; print_r ($arrLocation); exit;
			$dataLoaded	= true;
		}
	} 
	else  { ws_message ('<!-- module ec_print_aqhi.php ('.__LINE__.'): from '.$url.' -->');
	}
} // eo chack cache
#
if ($dataLoaded	== false) {		// curl the file into string   weather.gc.ca/airquality/pages/provincial_summary/on_e.html
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
        $string = curl_exec ($ch);
	curl_close ($ch);
# check if file is OK:
	$pos 	= strpos($string, 'id="cite_1">');
	if (!$pos) {
		$strOut = "<p>  error loading AQHI information for province <b>$province</b> language <b>$langcode</b>".PHP_EOL;
                $strOut .= '<br />Using url <a href="'.$url.'">'.$url.'</a></p>';
		$strOut .= '<p>Please try again later</p>'.PHP_EOL;
		return;
	}
# process the html info for this province into an array and save it into cache
        $search = true;
        $last_pos       = 0;
        $arrLocation    = array();
        while ($search) {
/*
            <td class="text-left" headers="location">
              <a class="" href="/airquality/pages/nbaq-004_e.html">Bathurst</a>
            </td>
*/  
                $pos = strpos($string,'<a class="" href="/airquality/pages/',$last_pos);
                if (!$pos) {$search = false; break;}
                $pos_end        = strpos($string,'</a>',$pos);
                $string_url     = substr($string, $pos, $pos_end - $pos);
                $pos_index      = strpos($string_url,'index');
                if ($pos_index > 0) {$last_pos = $pos_end; continue;}   // skip urls to other parts, we only need urls to areas
                $string_url     = str_replace ('<a class="" href="','',$string_url);
                list ($url,$name) = explode ('">',$string_url);
# echo '<pre> $url= '.$url.' $name= '.$name."</pre> \n ";
                $arrLocation[]= array ('location' => $name , 'url' => $url);
                $last_pos = $pos_end;
        }
	if (!file_put_contents($cacheFileName, serialize($arrLocation))){   
		exit ('ERROR FATAL module ec_print_aqhi.php ('.__LINE__.'): Could not save '.$cacheFileName.'. Please make your cache directory writable.<br />Program ended.');
	} 
	else {	ws_message ('<!-- module ec_print_aqhi.php ('.__LINE__.'): '. $cacheFileName.' saved to cache-->');;
	}
}
$url    = '';
for ($n = 0; $n < count ($arrLocation); $n ++) {
	$location       = $arrLocation[$n]['location'];
	$compare        = mb_strtolower($location,'UTF-8');
	if ($aqhiArea == $compare || $aqhiArea_f == $compare){
	        $page   = 'http://weather.gc.ca'.$arrLocation[$n]['url'];
		break;
	}
}
/*
echo '<pre>
$location       = '.$location.'
$compare        = '.$compare.'
$page   	= '.$page.'
$aqhiArea	= '.$aqhiArea.PHP_EOL;

print_r ($arrLocation); exit;
*/
#
echo '<!-- air quality canada -->
<div class="blockDiv" style="background-color: #f9f9f9;">
<div style="width: '.$width.'; margin: 0 auto; ">
<iframe  id="iframe" src="./canada/frame.php?width='.$width.'&amp;url='.$page.'" 
style="margin-top: 0px;  height: 1000px; width: '.$width.'; border: none; overflow: hidden; back-ground: transparent;" >
</iframe>
</div>
</div>
<!-- end of air quality canada -->
';
# ----------------------  version history
# 3.20 2015-08-02 release 2.8 version 
