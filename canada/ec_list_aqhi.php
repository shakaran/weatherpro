<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;					# display source of script if requested so
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
$pageName	= 'ec_list_aqhi.php';
$pageVersion	= '3.20 2015-07-27';
#-----------------------------------------------------------------------
# 3.20 2015-07-27 release 2.8 version ONLY
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
# utility program to display during install of the template a list of area codes /names
# also used by the AQHI script for loading that table from the cache
# the table is retrieved once and stored in the cache
# url with force=aqhilist  will reload the table from the url
# ----------------------------------------------------------------------
# settings:
$page_title     = 'AQHI list of regions and codes';
$url            = 'dd.meteo.gc.ca/air_quality/doc/AQHI_XML_File_List.xml';
#$local          = './canada/AQHI_XML_File_List.xml';      // for test/developement
$cachefile      = $SITE['cacheDir'].'canada_AQHI_XML.txt';
$startEcho      = '<!-- ';       #$startEcho      = '';
$endEcho        = ' -->';        #$endEcho        = '';
$test           = false;
$invalidcharset = true;
$loaded         = false;
$ec_list_aqhi   = 'module '.$pageFile ;
#
if (isset($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'aqhilist') {
        ws_message ( $startEcho.$ec_list_aqhi.' ('.__LINE__.'): data freshly loaded while "force" was used.'.$endEcho,true);
        $loaded = false;
}
elseif (file_exists($cachefile) && !$test){
        $region_array   =  unserialize(file_get_contents($cachefile));
        ws_message ( $startEcho.$ec_list_aqhi.' ('.__LINE__.'): data loaded from '.$cachefile.$endEcho);
        $loaded         = true;
}	
if ($loaded == false && !$test) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        $string = curl_exec ($ch);
        curl_close ($ch);
 } 
 if ($loaded == false && $test) {
        ws_message ( $startEcho.$ec_list_aqhi.' ('.__LINE__.'): data loaded from test-file at '.$local.$endEcho,true);
        $string = file_get_contents($local);
}
if ($loaded == false && $invalidcharset) {
        $string = str_replace ('ISO-8859-1','UTF-8',$string);
}
if ($loaded == false) {
        if (trim($string) == '') {exit ('<h3>'.$ec_list_aqhi.': input file has no contents - program ends </h3>'); }
        $xml            = new SimpleXMLElement($string);
        $region_array   = array();
        $cnt_zones      = count ($xml -> EC_administrativeZone);
        ws_message (  $startEcho.$ec_list_aqhi.' read '.$cnt_zones.' administrative zones.'.$endEcho);
        if ($cnt_zones < 1 || $cnt_zones == false) {exit ('<h3>'.$pageName.': invalid file '.$url.' loaded - program ends </h3>'); }
        for ($i = 0; $i < $cnt_zones; $i++) {
                $arr_zone       = $xml -> EC_administrativeZone[$i];
                $zone_name_en   = (string) $arr_zone['name_en_CA'];
                $zone_name_fr   = (string) $arr_zone['name_fr_CA'];
                ws_message (  $startEcho.$ec_list_aqhi.'('.__LINE__.'): Processed = '.$i.' - '.$zone_name_en.' - '.$zone_name_fr.$endEcho);
                $cnt_regions    = count ($arr_zone -> regionList -> region);
                ws_message ( $startEcho.$ec_list_aqhi.'('.__LINE__.') - for '.$zone_name_en.' read '.$cnt_regions.' regions.'.$endEcho);
                for ($n = 0; $n < $cnt_regions; $n++) {
                        $arr_region     = $arr_zone -> regionList -> region[$n];
                        $region_name_en = (string) $arr_region['nameEn'];
                        $region_name_fr = (string) $arr_region['nameFr'];
                        $region_code    = (string) $arr_region['cgndb'];
                        $region_current = (string) $arr_region -> pathToCurrentObservation;
                        $region_fcst    = (string) $arr_region -> pathToCurrentForecast;
                        $region_array[$region_code]['region_code']      = $region_code;
                        $region_array[$region_code]['zone_name']['en']  = $zone_name_en;
                        $region_array[$region_code]['zone_name']['fr']  = $zone_name_fr;                        
                        $region_array[$region_code]['region_name']['en']= $region_name_en;
                        $region_array[$region_code]['region_name']['fr']= $region_name_fr;                        
                        $region_array[$region_code]['region_current']   = $region_current;                        
                        $region_array[$region_code]['region_fcst']      = $region_fcst;                        
                }  // eo for reagion      
        }  // eo for zones
        if (!file_put_contents($cachefile, serialize($region_array)))
             {  exit ("<h3> $pageName: Could not save region array to cache ($cachefile). Please make sure your cache directory exists and is writable.<br /> Program ends</h3>");
        } 
	else {  ws_message ( $startEcho.$pageName .' ('.__LINE__.'): '.$cachefile.' saved to cache '.$endEcho);}
        $loaded = true;
}
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 

