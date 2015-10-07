<?php
# ---------------HERE YOU NEED TO MAKE SOME CHANGES ----------------------
#
$monthDay		= false;		// #####	june 30  ==> true   30 june	==> false				
$myfolder		= './wuforecast/';	// #####        only change this if you stored the wu forecasts scripts in another folder
#
$printHead		= true;                 //              head string
#
$printIcons		= true;                 //              icons part
$cntIcons		= 10;			// #####        number of icons to be printed
#
$printGraph		= true;                 //              meteogram graph
$graphHeight            = 340;                  // 340 = 340px heigh  So it is in pixels without the px characters
#
$printTable		= true;                 //              details table
#
$printCredit	        = true;                 //              credit line and debug info
#
# --------------- END OF SETTINGS ----------------------------------------
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wu_fct_page.php';
$pageVersion	= '3.20 2015-09-20';
#-------------------------------------------------------------------------------
# 3.20 2015-09-20 release 2.8 version + remove error own area load
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
# ---------THE FOLLOWING SETTINGS ARE COPIED FROM YOUR SETTINGS  -------
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
$wuKey		        = $SITE['wuKey'];
$myLang		        = $SITE['lang'];
$myLatitude	        = $SITE['latitude']; 
$myLongitude	        = $SITE['longitude'];
$myArea	                = $SITE['yourArea']; 
$myWeatherstation	= $SITE['organ'];
$myTimeFormat           = $SITE['timeFormat'];
$wuicons	        = $SITE['wuIconsOwn'];
#
$charset		= $SITE['charset'];
$lower			= $SITE['textLowerCase'];
$tempSimple		= $SITE['tempSimple'];
#
$myTemp		        = $SITE['uomTemp'];
$myBaro		        = $SITE['uomBaro'];
$myRain 	        = $SITE['uomRain'];
$myWind  	        = $SITE['uomWind'];
$mySnow                 = $SITE['uomSnow'];
#
$saved_lang		= $lang;
$myLang		        = $lang;
$myCache                = $SITE['cacheDir'];
#
# multiforecast settings
$selection_file = $SITE['multi_fct_keys'];
#$SITE['multi_forecast'] = false;       // if no selectable forecast wanted, you can override that setting here
#
# --------------- make selection list of area's ------------------------
$string         = '|'.trim($myLatitude).'|'.trim($myLongitude).'|'.$myArea.'|';
$areas          = array($string);
$default        = 0;
$select_list    = array($myArea);
#
#
if ( $SITE['multi_forecast'] == true && file_exists($selection_file) ){
        $arr    = file($selection_file);
        $end    = count ($arr);
        for ($n = 0; $n < $end; $n++) {        
                $string         = trim($arr[$n]);
                if ($string == '') {continue;}
                if (substr($string,0,1) == '#') {continue;}
                list ($none,$lat,$lon,$area) = explode ('|',$string.'|||');
                $lat            = trim($lat);
                $lon            = trim($lon);
                $area           = trim($area);
                if ($lat == '' || $lon == '' || $area == '' )       
                        {continue;}     // skip lines with invalid key values
                if (!is_numeric($lat) ||   !is_numeric($lon) ) 
                        {continue;}     // skip lines with non-numeric lat lon                          
                $areas[]        = '|'.$lat.'|'.$lon.'|'.$area.'|';
                $select_list[]  = $area;
        }
}
$end_areas      = count($areas);
#
ws_message ( '<!-- areas:'.PHP_EOL.print_r($areas,true).'selects: '.PHP_EOL.print_r($select_list,true).' -->');
#
# check if we have to generate a forecast for another city as the default.
if (isset ($_GET['city']) && 1.0*$_GET['city'] < $end_areas) {
        $default        = 1.0*$_GET['city'];
        $yourArea       = langtransstr($select_list[$default]);
        list ($none,$myLatitude,$myLongitude,$area)    = explode ('|',$areas[$default]);
}
#echo "<pre>$areas[$default] \n latitude = $myLatitude -longitude= $myLongitude -default= $default - area = $myArea".'<br /></pre>'; 
# --------------- END OF SETTINGS ----------------------------------------
$script = $myfolder.'wuforecast3.php';
ws_message (  '<!-- module wu_fct_page.php ('.__LINE__.'): loading '.$script.' -->');
if (!include $script) {return;}
#
echo '<div class="blockDiv">'.PHP_EOL;
#-----------------------------------------------------------------------
if ($end_areas > 1) {
	$multi_link 	= $SITE['pages']['wu_fct_page'].'&amp;lang='.$lang.$extraP.$skiptopText;
        echo '<div class="blockHead">
<table class="genericTable"><tr><td style="text-align: left;">
<form action="'.$multi_link.'" method="get">
<fieldset style="border: 0px;">'.langtransstr('Choose another city here').':                
<select name="city">';
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
<h3 style=""> 10 '.wswufcttransstr($trans.'day forecast for').' '.wswufcttransstr($select_list[$default]).'</h3>
</td>
</tr></table>
</div>'.PHP_EOL;
} // end of multi select
elseif ($printHead)     {echo  $headStr;}

#-------------------------------------------------------------------------------
if ($printIcons)        {echo    '<div style="margin: 10px 0px;">'.$htmlicons.'</div>'.PHP_EOL;}
if ($printGraph)        {
        echo    '<div id="containerTemp" style="height: 340px; margin: 10px 0px;">here the graph will be drawn</div>'.PHP_EOL;
	echo    $graphPart1.PHP_EOL;
}
if ($printTable)        {echo    '<div style="margin: 10px 0px;">'.$htmltable.'</div>'.PHP_EOL;}
if ($printCredit) {echo $credit;}
#
echo '</div>'.PHP_EOL;
#
$myLang = $lang = $saved_lang;
#
if ($printGraph) {
	echo '<script type="text/javascript" src="'.$jqueryurl.'"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$highchartsurl.'"></script>'.PHP_EOL;
#	echo '<script src="http://code.highcharts.com/highcharts.js"></script>'; # needs adaption in js
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
# ----------------------  version history
# 3.20 2015-09-20 release 2.8 version 
