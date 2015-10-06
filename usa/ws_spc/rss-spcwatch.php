<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
#  display source of script if requested so
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
$pageName	= 'rss-spcwatch.php';
$pageVersion	= '3.00 2015-04-03';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) { $SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName; }
if (!isset($pathString)) {$pathString='';}
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->'.PHP_EOL;
#-----------------------------------------------------------------------
$cache_path     = $SITE['cacheDir'];
$cache_file     = $cache_path.'usa_watches.arr';
$cache_time     = 3600;
$test_file      = ''; 
$more_info      = './img/i_symbol.png';
$test_file      = './usa/ws_spc/rss.xml';   # testfile
$ww_url         = 'http://www.spc.noaa.gov/products/spcwwrss.xml';
$valid_data     = false;
#
# first check is valid data is in cache
#
if (file_exists($cache_file)){
        $file_time      = filemtime($cache_file);
        $now            = time();
        $diff           = ($now - $file_time);
        echo "<!-- Weather-watches ($cache_file) cache time=  $file_time - current time = $now - difference  =  $diff - Diff allowed = $cache_time -->".PHP_EOL;	
        if ($diff <= $cache_time) {
                echo "<!-- Weather-watches ($cache_file) loaded from cache -->".PHP_EOL;
                $valid_data     = file_get_contents($cache_file); 
        }  // eo filte time ok -> get file from cache
}  // eo file exists
#
#if (isset ($test_file) && $test_file <> '') { $valid_data = file_get_contents($test_file); echo "<!-- Weather-watches  loaded from testfile ($test_file) -->".PHP_EOL;}
#
if (!$valid_data) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $ww_url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        $valid_data     = curl_exec ($ch);
        curl_close ($ch);
        $pos            = strpos ('  '.$valid_data,'<item>');        
        if (! ($pos > 0) ) {$valid_data = false;}       // the data must contain information
        $pos            = strpos ('  '.$valid_data,'<channel>');        
        if (! ($pos > 0) ) {$valid_data = false;}       // the data must contain information
# 
        if ($valid_data === false) {
                echo '<h3 style="text-align; center;">No valid data could be gathered, sorry </h3>'; return;
        }
	if (!file_put_contents($cache_file, $valid_data)) {
	        echo "<!-- <br />Could not save ($cache_file) to cache. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
	} 
	else {  echo "<!-- Weather-watches ($cache_file) saved to cache -->".PHP_EOL;
	}
}
#
$xml 		= new SimpleXMLElement($valid_data); 
$array_watches  = array();
$count  = count ($xml -> channel -> item);
#
for ($n = 0; $n < $count; $n++) {
        $var                            = $xml -> channel -> item[$n];
        $array_watches[$n]['link']      = (string) $var -> link;
        $array_watches[$n]['title']     = (string) $var -> title;
        $description                    = (string) $var -> description;
        $description                    = str_replace ('<a ','<a target="_blank" ',$description);
        $array_watches[$n]['desc']      = $description;     
}
$links = '';
#print_r($array_watches[0]); exit;
for ($n = 0; $n < $count; $n++) {
        echo '<p style="text-align: center; width: 525px; margin: 0 auto;"><a href="javascript:ww_us_click(\'ww'.$n.'\')">'.$array_watches[$n]['title'].'&nbsp;&nbsp;'.
 #     '<img src="'.$more_info.'" width="16" style="margin:1px; vertical-align: middle;" alt =" " title ="more information">'.
        '</a></p>'.PHP_EOL;
        $links  .= 'hidediv(document.getElementById("ww'.$n.'"))'.PHP_EOL;
}
echo '<br />';
for ($n = 0; $n < $count; $n++) {
        echo '<div id="ww'.$n.'" style="display:none; width: 525px; margin: auto;">'.$array_watches[$n]['desc'].'</div>'.PHP_EOL;
}
?>
<script type="text/javascript">
    function ww_us_click(displ) {
        if (!document.getElementById)return
<?php echo $links; ?>
        wwshow = document.getElementById(displ)
        wwshow.style.display="block"
    }
  function hidediv(which){
    if (which.style.display=="block") which.style.display="none"
  }
</script>