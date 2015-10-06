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
$pageName	= 'ec_print_fct.php';
$pageVersion	= '3.00 2015-05-24';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile       = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-----------------------------------------------------------------------
# Display a list of forecast date from nws/noaa
#-----------------------------------------------------------------------
#
$yourArea	= $SITE['yourArea'];
$string         = '|'.trim($SITE['caProvince']).'|'.trim($SITE['caCityCode']).'|'.$yourArea.'|';
$areas          = array($string);
$default        = 0;
$select_list    = array($yourArea);
#
if (!isset ($SITE['multi_forecast'])) { $SITE['multi_forecast'] = false; }
$selection_file = $SITE['multi_fct_keys'];
#
if ( $SITE['multi_forecast'] == true && file_exists($selection_file) ){
        $arr    = file($selection_file);
        $end    = count ($arr);
        for ($n = 0; $n < $end; $n++) {        
                $string         = trim($arr[$n]);
                if ($string == '') {continue;}
                if (substr($string,0,1) == '#') {continue;}
                list ($none,$lat,$lon,$area,$metar,$yahoo,$yrno,$province,$code) = explode ('|',$string.'|||');
                $province       = trim($province);
                $code           = trim($code);
                $area           = trim($area);
                if ($province == '' || $code == '' || $area == '' )       
                        {continue;}     // skip lines with invalid key values
                $areas[]        = '|'.$province.'|'.$code.'|'.$area.'|';
                $select_list[]  = trim($area);
        }
}
$end_areas      = count($areas);
if (isset ($wsDebug) && $wsDebug == true){ echo '<!-- areas:'.PHP_EOL; print_r($areas); echo 'selects: '.PHP_EOL; print_r($select_list); echo ' -->'.PHP_EOL;}
#echo '<pre>areas:'.PHP_EOL; print_r($areas); echo 'selects: '.PHP_EOL; print_r($select_list); echo ''.PHP_EOL; exit;
if (isset ($_GET['ec-city']) && 1.0*$_GET['ec-city'] < $end_areas) {$default = 1.0*$_GET['ec-city'];}
list ($none,$caProvince,$caCityCode,$yourArea)    = explode ('|',$areas[$default]);
#echo "<pre>$areas[$default] \n caProvince = $caProvince -caCityCode= $caCityCode -default= $default -yourArea = $yourArea"; exit;

#
$script	= 'ec_fct_generate_html.php';
ws_message (  '<!-- module ec_print_fct.php ('.__LINE__.'): loading '.$script.' -->');
$return = include $script;
if ($return == false) {
	ws_message ( '<br />Module ec_print_fct.php ('.__LINE__.'): No good data - ending script');
	return;
}
#
echo '<div class="blockDiv">'.PHP_EOL;
echo '<div class="blockHead" style="">';
if ($end_areas > 1) {
        echo '<div class="blockHead">
<table class="genericTable"><tr><td style="text-align: left;">
<form action="" method="get">
<fieldset style="border: 0px;">
<legend>'.langtransstr('Choose another area here').'</legend>                
<select name="ec-city">';
        for ($n = 0; $n < $end_areas; $n++) {
                if($n == $default) {$extra = 'selected="selected"';} else {$extra = '';}
                echo '<option value="' . $n . '" ' . $extra . '>' . $select_list[$n] . '</option>'."\n";
        }
        echo '</select>
<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" value="'.$p.'">
<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="lang" value="'.$lang.'">'.PHP_EOL;
        if ($SITE['ipad'])  { echo'<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="ipad" value ="y"/>'.PHP_EOL;}
        echo '<input type="submit" value="submit" />
</fieldset>
</form>
</td>
<td>
<h3 style="">'.$line1.'</h3>
</td>
<td style="text-align:right;">'.$line2.'</tr></table>
</div>'.PHP_EOL;
} // end of multi select
else { echo $stringTop;}
	
	
	
echo '</div><br>'.PHP_EOL;
echo $ecIcons;
#echo $hazardsString;
echo $ecPlainTextHead;
echo $ecPlainText;
echo '<div class="blockHead" style="">';
	echo $creditLink ;
echo '</div>'.PHP_EOL;
echo '</div>'.PHP_EOL;
#-----------------------------------------------------------------------
