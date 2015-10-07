<?php
# ---------------HERE YOU NEED TO MAKE SOME CHANGES --------------------
#  first we set which parts  of the page should be printed
#
$updateTimes	        = true;		// two lines with recent file / new update information
#
$showHazards            = true;         // show hazard warnings when available
#
# --------------- END OF SETTINGS --------------------------------------
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'noaa_plain_page.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version 
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
$insideTemplate         = true;
$myTimezone 		= $SITE['tz'];
$myLatitude		= $SITE['latitude'];
$myLongitude		= $SITE['longitude'];
$myLang 		= $SITE['lang'];
$myArea			= $SITE['yourArea'];
$myStation		= $SITE['organ'];
$myCharset		= $SITE['charset'];
$noaaIconsOwn		= $SITE['noaaIconsOwn']; 
$tempSimple     	= $SITE['tempSimple'];
$lower			= $SITE['textLowerCase'];	
#
$dateTimeFormat     	= $SITE['timeFormat'];
$timeFormat 		= $SITE['timeOnlyFormat'];
$hourOnlyFormat 	= $SITE['hourOnlyFormat'];
$dateOnlyFormat 	= $SITE['dateOnlyFormat'];
$dateLongFormat 	= $SITE['dateLongFormat'];
#
$wsmyfolder		= './usa/noaafct/';		// only change this if you stored the wu forecasts scripts in another folder
#
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
$string         = '|'.trim($myLatitude).'|'.trim($myLongitude).'|'.$myArea.'|';
$areas          = array($string);
$default        = 0;
$select_list    = array($myArea);
#
if (!isset ($SITE['multi_forecast'])) { $SITE['multi_forecast'] = false; }
#$SITE['multi_forecast'] = false;       // if no selectable forecast wanted, you can override that setting here by removing the # on the first position
$selection_file = $SITE['multi_fct_keys'];
#
if ( $SITE['multi_forecast'] == true && file_exists($selection_file) ){
        $arr    = file($selection_file);
        $end    = count ($arr);
        for ($n = 0; $n < $end; $n++) {        
                $string         = trim($arr[$n]);
                if ($string == '') {continue;}
                if (substr($string,0,1) == '#') {continue;}
                list ($none,$lat,$lon,$area) = explode ('|',$string.'|||');
                $areas[]        = '|'.trim($lat).'|'.trim($lon).'|'.trim($area).'|';
                $select_list[]  = trim($area);
        }
}
$end_areas      = count($areas);
if (isset ($wsDebug) && $wsDebug) {echo '<!-- areas:'.PHP_EOL; print_r($areas); echo 'selects: '.PHP_EOL; print_r($select_list); echo ' -->'.PHP_EOL;}
#echo '<pre>areas:'.PHP_EOL; print_r($areas); echo 'selects: '.PHP_EOL; print_r($select_list); echo ''.PHP_EOL;
if (isset ($_GET['noaa-city']) && 1.0*$_GET['noaa-city'] < $end_areas) {$default = 1.0*$_GET['noaa-city'];}
list ($none,$myLatitude,$myLongitude,$myArea)    = explode ('|',$areas[$default]);
#echo "<pre>$areas[$default] \n myLatitude = $myLatitude -myLongitude= $myLongitude -default= $default -myArea = $myArea"; exit;

#
echo '<div class="blockDiv" style="">'.PHP_EOL;
# ------------------------------------------------------------------------------
# here we build the noaa page
#  ------------------- load all settings ---------------------------------------
$script	= 'noaaSettings.php';
ws_message (  '<!-- module noaa_plain_page.php ('.__LINE__.'): loading '.$script.' -->');
include $script ;
#  ------------------- generate and print requested info   ---------------------
$script	= 'noaaPlainGenerateHtml.php';
ws_message (  '<!-- module noaa_plain_page.php ('.__LINE__.'): loading '.$script.' -->');
include $script ;
#  ------------------- print multi select --------------------------------------
if ($end_areas > 1) {
        if (!$updateTimes) {$lines='<h3>'.$line1.'&nbsp;&nbsp;</h3>';}else{$lines=$line1.'&nbsp;<br />'.$line2.'&nbsp;<br />'.$line3;}
	$multi_link 	= $SITE['pages']['noaa_plain_page'].'&amp;lang='.$lang.$extraP.$skiptopText;
       echo '<div class="blockHead">
<table class="genericTable"><tr><td style="text-align: left;">
<form action="'.$multi_link.'" method="get">
<fieldset style="border: 0px;">
<legend>'.langtransstr('Choose another area here').'</legend>                
<select name="noaa-city">';
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
<td style="text-align:center;">'.$lines.'&nbsp;'.'</td>
</tr></table>
</div>'.PHP_EOL;
} #  ----------end of  print multi select 
else {	#  ---------- optional times 
	if ($updateTimes) {echo '<div class="blockHead" style="">'.$stringTop.'</div>'.PHP_EOL;}
}
#  ---------- optional hazards 
if ($showHazards && $hazardsString <> '') { echo $hazardsString;}
#  ---------- main parts to print
echo '<div style="padding: 10px;">'.$noaaIconsHtml.'</div>'.PHP_EOL;
echo $noaaPlainTextHead;
echo $noaaPlainText;
echo $creditLink ;
echo '</div>'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
