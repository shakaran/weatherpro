<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
# display source of script if requested so
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
$pageName	= 'ca_AQHI_list.php';
$pageVersion	= '3.00 2015-04-11';
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-10-22 release version
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
#
if (isset($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'aqhilist') {
        echo $startEcho.$pageName.': data freshly loaded while "force" was used.'.$endEcho.PHP_EOL;
        $loaded = false;
}
elseif (file_exists($cachefile) && !$test){
        $region_array   =  unserialize(file_get_contents($cachefile));
#echo '<pre>'; print_r ($region_array);
        echo $startEcho.$pageName.': data loaded from '.$cachefile.$endEcho.PHP_EOL;
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
        echo $startEcho.$pageName.': data loaded from test-file at '.$local.$endEcho.PHP_EOL;
        $string = file_get_contents($local);
}
if ($loaded == false && $invalidcharset) {
        $string = str_replace ('ISO-8859-1','UTF-8',$string);
}
if ($loaded == false) {
        if (trim($string) == '') {echo '<H3 input file has no contents - program ends </h3>'; return;}
        $xml            = new SimpleXMLElement($string);
        $region_array   = array();
        $cnt_zones      = count ($xml -> EC_administrativeZone);
        echo $startEcho.$pageName.' read '.$cnt_zones.' administrative zones.'.$endEcho.PHP_EOL;
        if ($cnt_zones < 1 || $cnt_zones == false) {echo '<H3 invalid file '.$url.' loaded - program ends </h3>'; return;}
        for ($i = 0; $i < $cnt_zones; $i++) {
                $arr_zone       = $xml -> EC_administrativeZone[$i];
                $zone_name_en   = (string) $arr_zone['name_en_CA'];
                $zone_name_fr   = (string) $arr_zone['name_fr_CA'];
                echo $startEcho.'Processed = '.$i.' - '.$zone_name_en.' - '.$zone_name_fr.$endEcho.PHP_EOL;
                $cnt_regions    = count ($arr_zone -> regionList -> region);
                echo $startEcho.$pageName.' - for '.$zone_name_en.' read '.$cnt_regions.' regions.'.$endEcho.PHP_EOL;
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
             {  echo PHP_EOL.$startEcho."$pageName: Could not save region array to cache ($cachefile). Please make sure your cache directory exists and is writable.".$endEcho.PHP_EOL;} 
	else {  echo $startEcho."$pageName: ($cachefile) saved to cache ".$endEcho.PHP_EOL;}
        $loaded = true;
}
if (isset ($skipAQHIdisplay) && $skipAQHIdisplay) {return;}
?>
<script type="text/javascript" src="javaScripts/sorttable.js"></script>
<div class="blockDiv" style="border-left: none; border-right: none;">
<h3 class="blockHead">
<?php echo $page_title.'<br />'.langtransstr('You can sort the table on the values in the columns by clicking in the corresponding heading'); ?>
</h3>
<table border="1" class="sortable genericTable" style="text-align: left;">
<thead><tr>
<?php
echo '<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('code').'</th>';
echo '<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('zone').'</th>';
echo '<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('region').'</th>';
echo '<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('zone').'</th>';
echo '<th style=" padding-left: 5px; cursor: n-resize;" >'.langtransstr('region').'</th>';
?>
</tr>
</thead>
<tbody>
<?php
$cnt_arr        = count ($region_array);
foreach ($region_array as $key => $arr) {
        echo    '<tr>';
        echo    '<td style=" padding-left: 5px;">'.$arr['region_code'].'</td>'.PHP_EOL;
        echo    '<td style=" padding-left: 5px;">'.$arr['zone_name']['en'].'</td>'.PHP_EOL;
        echo    '<td style=" padding-left: 5px;">'.$arr['region_name']['en'].'</td>'.PHP_EOL;
        echo    '<td style=" padding-left: 5px;">'.$arr['zone_name']['fr'].'</td>'.PHP_EOL;
        echo    '<td style=" padding-left: 5px;">'.$arr['region_name']['fr'].'</td>'.PHP_EOL;
        echo    '</tr>';        
}

?>
</tbody>
</table>
</div>
