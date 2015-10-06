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
# the leuven scripts can not be used / started  without index.php
if (!isset($SITE)){echo "<h3>invalid call to script ws_metno_page.php</h3>";exit;}
# ----------------------------------------------------------------------
#                HERE YOU NEED TO MAKE SOME CHANGES
# ----------------------------------------------------------------------
# what parts should be printed
#       forecast-times with station information
$metno_times            = true;        // 
#       icons 
$metno_icon_graph	= true; 	// icon type header  with 2 icons for each day (12 hours data)
$metno_top_count	= 8;		// max nr of day-part forecasts in icons or graph
$metno_icons_in_tab	= false;
#       meteogram
$metno_meteogram	= true;	        // high charts meteogram -  6 days - one colom for every 6 hours - 
$metno_meteogram_height = '340px';
$metno_meteogram_in_tabs= false; 	// high charts graph separate (false) or in a tab (true)
#
$metnoTable		= true;	        // table with one line for every 6 hours
$metnoDetailsTable	= true;	        // table with one line for every 3 or 1 hours
$tableHeight            = '500px';      // no restricted height use ''  - restrict use number of pixels: '500px' 
$tableInTabs            = true;         // put tables in tabs
#
# ---------THE FOLLOWING SETTINGS ARE COPIED FROM YOUR SETTINGS  -------
#
$iconsOwn	= $SITE['yrnoIconsOwn'];
$yourArea	= $SITE['yourArea'];
$organ		= $SITE['organ'];
$latitude	= $SITE['latitude'];
$longitude	= $SITE['longitude'];
$charset        = $SITE['charset'];
$lower          = $SITE['textLowerCase'];
$tempSimple	= $SITE['tempSimple'];  
#
$uomTemp	= $SITE['uomTemp'];
$uomRain	= $SITE['uomRain'];
$uomWind 	= $SITE['uomWind'];
$uomBaro	= $SITE['uomBaro'];
$uomSnow        = $SITE['uomSnow'];
$uomDistance    = $SITE['uomDistance'];
#
$timeFormat	= $SITE['timeFormat'];
$timeOnlyFormat	= $SITE['timeOnlyFormat'];
$hourOnlyFormat	= $SITE['hourOnlyFormat'];
$dateOnlyFormat	= $SITE['dateOnlyFormat'];
$dateLongFormat	= $SITE['dateLongFormat'];
$timezone	= $SITE['tz'];
#
$defaultWidth	= '100%';
$insideTemplate = true;
$scriptDir      = './wsmetno3/';
#
# multiforecast settings
if (!isset ($SITE['multi_forecast'])) { $SITE['multi_forecast'] = false; }
$selection_file = $SITE['multi_fct_keys'];
#$SITE['multi_forecast'] = false;       // if no selectable forecast wanted, you can override that setting here by removing the # on the first position
# --------------- END OF SETTINGS --------------------------------------
#
# --------------- make selection list of area's ------------------------
$string         = '|'.trim($latitude).'|'.trim($longitude).'|'.$yourArea.'|';
$areas          = array($string);
$default        = 0;
$select_list    = array($yourArea);
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
        list ($none,$latitude,$longitude,$area)    = explode ('|',$areas[$default]);
}
# ----------------------------------------------------------------------
$pageName	= 'ws_metno_page.php';
$pageVersion	= '3.20 2015-07-28';
#-------------------------------------------------------------------------------
# 3.20 2015-07-28 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
$topCount       = $metno_top_count;   # to debug in all scripts remove $topCount
#
$styleborder    = '';  #' border: 1px inset; border-radius: 5px;';
$margin         = ' margin: 10px 0px;';
$width          = ' width: '.$defaultWidth.';';
#
if ($metno_meteogram_height <> '' )   { $metno_meteogram_height = 'height: '.$metno_meteogram_height.';';}
if ($tableHeight <> '')               { $tableHeight            = 'height: '.$tableHeight.';';}
#
# generate all html for this forecast
$script	        = $scriptDir.'metnoGenerateHtml.php';
ws_message (  '<!-- module ws_metno_page.php ('.__LINE__.'): loading '.$script.' -->');
if (!include $script) return;   // if the generate script encountered errors, do nothing

echo '<!-- start output ws_metno_page.php -->
<div class="blockDiv">';

if ($end_areas > 1) {           // do we want a selection box or is it only our own area
	$multi_link 	= $SITE['pages']['ws_metno_page'].'&amp;lang='.$lang.$extraP.$skiptopText;
        echo '<div class="blockHead">
<table class="genericTable"><tr><td style="text-align: left;">
<form action="'.$multi_link.'" method="get">
<fieldset style="border: 0px;">
<legend>'.langtransstr('Choose another city here').':</legend>                
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
<td><h3 style="">'.$fct_area.'</h3></td>
<td style="text-align:right;">'.$time_update.'&nbsp;<br />'.$time_next.'&nbsp;</td>
</tr></table>
</div>'.PHP_EOL;
}                               // end of multi select
else {                           // no multi select, standard top part is sent if requested
        if ($metno_times){ echo '<div class="blockHead">'.$wsUpdateTimes.'</div>'.PHP_EOL;}
}
#
if ($metno_icon_graph && !$metno_icons_in_tab) {
        $style  = 'style="'.$width.$styleborder.$margin.'"';
	echo '<div id="iconGraph" '.$style.'>';
	        echo $tableIcons.PHP_EOL;
        echo '</div>'.PHP_EOL;
#	echo '<br />'.PHP_EOL;
}
if ($metno_meteogram && !$metno_meteogram_in_tabs) {		// are the graphs separate (=false) on the page or are they in a tab
	if (isset ($metno_meteogram) && $metno_meteogram == true) {
	        $style  = 'style="'.$width.$styleborder.$margin.$metno_meteogram_height.'"';
                echo '<div id="containerTemp" '.$style.'>here the graph will be drawn
                </div>'.PHP_EOL;
#                echo '<br />'.PHP_EOL;
                echo $graphPart1.PHP_EOL;
        }
}
if ($metno_icons_in_tab || $tableInTabs || $metno_meteogram_in_tabs) { // generate html for tabs
        echo '<br /><div class="tabber"  style="'.$width.' margin: auto;">'.PHP_EOL;
}
if ($metno_icon_graph && $metno_icons_in_tab) {
        $style  = 'style="width: 100%;'.$styleborder.$margin.'"';
	echo '<div class="tabbertab" style="padding: 0px;"><h2>'.metnotransstr('Icons').'</h2>'.PHP_EOL;
                $style  = 'style="'.$width.$styleborder.$margin.'"';
                echo '<div id="iconGraph" '.$style.'>';
                        echo $tableIcons.PHP_EOL;
                echo '</div>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
#	echo '<br />'.PHP_EOL;
}

if ($metno_meteogram && $metno_meteogram_in_tabs) {
        $style  = 'style="width: 100%; overflow: hidden; '.$metno_meteogram_height.'"';
        echo '<div class="tabbertab" style="padding: 0px;"><h2>'.metnotransstr('Graph').'</h2>'.PHP_EOL;
                echo '<div id="containerTemp" '.$style.'>';
                echo 'here the graph will be drawn</div>'.PHP_EOL;
                echo $graphPart1.PHP_EOL;
        echo '</div>'.PHP_EOL;	 
}
if ($tableInTabs) {
        $style  = 'style="'.$tableHeight.'"';
        if ($metnoTable) {
                echo '<div class="tabbertab" '.$style.'><h2>'.metnotransstr('Forecast by 6 hour intervals').'</h2>'.PHP_EOL;
		        echo $metnoListTable.PHP_EOL;
	        echo '</div>'.PHP_EOL;
	}
	if ($metnoDetailsTable) {
	        echo '<div class="tabbertab" '.$style.'><h2>'.metnotransstr('Forecast details').'</h2>'.PHP_EOL;
		        echo $metnoDetailTable.PHP_EOL;
	        echo '</div>'.PHP_EOL;
        }
}
if ($tableInTabs || $metno_meteogram_in_tabs) {
        echo '</div>'.PHP_EOL;
}
if (!$tableInTabs) {
        $style  = 'style="'.$width.$margin.$tableHeight.' overflow: auto;"';
        if ($metnoTable) {
                echo '<div class="tabbertab" '.$style.'><h2>'.metnotransstr('Forecast by 6 hour intervals').'</h2>'.PHP_EOL;
		        echo $metnoListTable.PHP_EOL;
	        echo '</div>'.PHP_EOL;
	}
	if ($metnoDetailsTable) {
	        echo '<div class="tabbertab" '.$style.'><h2>'.metnotransstr('Forecast details').'</h2>'.PHP_EOL;
		        echo $metnoDetailTable.PHP_EOL;
	        echo '</div>'.PHP_EOL;
        }
}

$style  = 'style="'.$width.$margin.'"';
echo '<div id="credit" '.$style.'>';
        echo $creditString;
echo '</div>'.PHP_EOL;
echo '</div>'.PHP_EOL;

#-------------------I M P O R T A N T  -------------------------------------
# now we add the needed javascripts if we display the graphs
# if you use this script inside another script make sure you add the javascripts yourself
#---------------------------------------------------------------------------
#
if ($metno_meteogram) {
	echo '<script type="text/javascript" src="'.$javascriptsDir.'jquery.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$javascriptsDir.'highcharts.js"></script>'.PHP_EOL;
#	echo '<script src="http://code.highcharts.com/highcharts.js"></script>'; # needs adaption in js
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
if ($tableInTabs || $metno_meteogram_in_tabs) {
	echo '<script type="text/javascript" src="'.$javascriptsDir.'tabber.js"></script>'.PHP_EOL;
}
?>