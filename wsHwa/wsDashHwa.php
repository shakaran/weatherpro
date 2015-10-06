<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   //--self downloader --
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
$pageName	= 'wsDashHwa.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.10 2015-01-23';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.10 2015-01-23 beta 2.7 version
# ----------------------------------------------------------------------
# ----------------------------------------------------------------------
#                HERE YOU NEED TO MAKE SOME CHANGES
# ----------------------------------------------------------------------
# IMPORTANT
#
$hwa_link               = $SITE['pages']['hwaFullPage'].'&amp;lang='.$lang.$extraP.$skiptopText;       // pagenumber for full forecast page
#       set to ''; if no link to full page is needed
# ----------------------------------------------------------------------
#
$hwa_mos	        = true;		// original HWA graph / header with 2 coloms for each day (12 hours data)
$hwa_mos_in_tab	        = false;	
#
$hwa_icon_graph	        = true; 	// icon type header  12 hours data
$hwa_top_count	        = 10;		// max nr of day-part forecasts in icons
$hwa_icons_in_tab	= true;
$hwa_icons_default      = false;

$topCount	= $hwa_top_count;	# debug  remove from other scripts


$topWidth	        = '100%';	// set do disered width 999px  or 100%
#
#       meteogram
$hwa_meteogram	        = true;	        // high charts meteogram -  6 days - one colom for every 12 hours - 
$hwa_meteogram_height   = '300px';
$hwa_meteogram_in_tab   = true; 	// high charts graph separate (false) or in a tab (true)
$hwa_meteogram_default  = true;
#
$hwa_table	        = true;		// table with one line for every 3 hours
$hwa_table_height       = '500px';      // no restricted height use ''  - restrict use number of pixels: '500px' 
$hwa_table_in_tab       = true;         // put table in tabs
$hwa_table_default      = false;
#
$hwa_credits	        = true;         // in small available spaces these two lines can be removed by setting this to false

if ($hwa_mos_in_tab || $hwa_icons_in_tab || $hwa_meteogram_in_tab || $hwa_table_in_tab ) {$hwa_tabs = true;} else {$hwa_tabs = false;}
#
$SITE['scriptIconsWind']= './wsHwa/hwa_icons/';
$windIconsSmall		= $SITE['windIconsSmall'];
$javaFolder             = $SITE['javascriptsDir'];
#
# Get the data from a weather class 
#
include 'hwaCreateArr.php';
$weather 	        = new hwaWeather ();
$returnArray 	        = $weather->getWeatherData('');
if (!isset ($returnArray['forecast']) ) {
        echo '<h3 style="color: red; text-align: center;">HWA forecast: Invalid data returned for part / all of the forecast data - forecast incomplete </h3>';
        if ($wsDebug && isset ($returnArray) ) {echo '<pre>'; print_r($returnArray); echo '</pre>';}
        return false;
} 
else  { $end_forecast = count($returnArray['forecast']);
        if ($end_forecast < 3 ) {
                echo '<h3 style="color: red; text-align: center;">HWA forecast: incomplete data returned for part / all of the forecast data</h3>';  
                if ($wsDebug) {echo '<pre>'; print_r($returnArray); echo '</pre>';}
                return false; 
        }
}
#
#---------------------------------------------------------------------------
# Now create all tables and graphs to be printed here
#
include 'hwaGenerateHtml.php';
if ($skip == true) {echo '<h3> No valid input found. All data is in the past.</h3>'.PHP_EOL;  $skip = false; return;}

# Now ready for printing to the screen. Use echo for that
#   $stringColom	: original HWA graph
#	$tableIcons		: icon 
#	$graphPart1		: javascript / highcharts graph
#	$hwaListTable	: table with all forecast lines
$default_tab   = ' tabbertabdefault';
$defaultMargin  = '10px';
$defaultWidth	= '98%';
$styleborder    = ' border: 1px inset; border-radius: 5px;';
$margin         = ' margin: '.$defaultMargin.' auto;';
$width          = ' width: '.$defaultWidth.';';
$style          = 'style="'.$width.$styleborder.$margin.'"';    // all but MOS

#
# the head text is always printed
#
echo '<div  class="ajaxHead" style="">HWA 7 '.langtransstr('day graphical forecast for').' '.$SITE['organ'].PHP_EOL;
if ($hwa_link <> '') { echo '
<a href="'.$hwa_link.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information">
</a>';
}
echo '</div>'.PHP_EOL;
$tabs   = '';
# the MOS

if ($hwa_mos == true) {
  if (!$hwa_mos_in_tab) {
	echo '<div id="printGraph" style="width: 100%; '.$margin.'"><!-- enclosing div for hwa MOS -->'.PHP_EOL;
	echo $stringColom.PHP_EOL;
	echo '</div><!-- eo enclosing div for hwa print graph -->'.PHP_EOL;}
  else {
	$tabs  .=  '<div class="tabbertab" style="padding: 0px;"><h2>'.langtransstr('MOS-verwachting').'</h2>'.PHP_EOL;
        $tabs  .=  '<div id="printGraph" style="width: 100%; '.$margin.'"><!-- enclosing div for hwa MOS -->'.PHP_EOL;   
        $tabs  .=  $stringColom.PHP_EOL;
        $tabs  .=  '</div><!-- eo enclosing div for hwa print graph -->'.PHP_EOL; 
        $tabs  .=  '</div>'.PHP_EOL; 
  }
} 
#
if ($hwa_icon_graph == true) {
  if (!$hwa_icons_in_tab) {
	echo '<div id="iconGraph" '.$style.'><!-- enclosing div for hwa print icons -->'.PHP_EOL; 
	echo $tableIcons.PHP_EOL;
	echo '</div><!-- eo enclosing div for hwa print icons -->'.PHP_EOL; }
  else {
	$tabs  .=  '<div class="tabbertab ';
	if ($hwa_icons_default) {
	        $tabs          .= $default_tab;
	        $default_tab    = '';
	}
	$tabs  .=  '" style="padding: 0px;"><h2>'.langtransstr('Icons').'</h2>'.PHP_EOL;
 	$tabs  .=  '<div id="iconGraph" '.$style.'><!-- enclosing div for hwa print icons IN TAB -->'.PHP_EOL;  
	$tabs  .=  $tableIcons.PHP_EOL;
        $tabs  .=  '</div><!-- eo enclosing div for hwa print icons IN TAB -->'.PHP_EOL; 	
        $tabs  .=  '</div>'.PHP_EOL;  	
  }
}
#
if ($hwa_meteogram == true) {
  $style         = 'style="'.$width.$styleborder.$margin.' height: '.$hwa_meteogram_height.'; "';    // all but MOS
  if (!$hwa_meteogram_in_tab) {
	echo '<div id="containerTemp" '.$style.'>here the graphics will be drawn</div>'.PHP_EOL;
	echo $graphPart1.PHP_EOL;} 
  else {
	$tabs  .=  '<div class="tabbertab ';
	if ($hwa_meteogram_default) {
	        $tabs          .= $default_tab;
	        $default_tab    = '';
	}
	$tabs  .=  '" style="padding: 0px;"><h2>'.langtransstr('Meteogram').'</h2>'.PHP_EOL;
	$tabs  .=  '<div id="containerTemp" '.$style.'>here the graph will be drawn</div>'.PHP_EOL;
        $tabs  .=  $graphPart1.PHP_EOL;
        $tabs  .=  '</div>'.PHP_EOL;  	  
  }
}
if ($hwa_table == true) {
  $style         = 'style=" width: 100%; overflow-y: scroll; height: '.$hwa_table_height.'; "';    // all but MOS
  if (!$hwa_table_in_tab) {
	echo '<div '.$style.'>'.PHP_EOL;
	echo $hwaListTable.PHP_EOL;
	echo '</div>'.PHP_EOL;}
  else {
	$tabs  .=  '<div class="tabbertab ';
	if ($hwa_table_default) {
	        $tabs          .= $default_tab;
	        $default_tab    = '';
	}
	$tabs  .=  '" style="padding: 0px;"><h2>'.langtransstr('Details').'</h2>'.PHP_EOL;
        $tabs  .=  '<div '.$style.'>'.PHP_EOL;
        $tabs  .=  $hwaListTable.PHP_EOL;
        $tabs  .=  '</div>'.PHP_EOL;
        $tabs  .=  '</div>'.PHP_EOL;          
  }
}
#echo '</div>'.PHP_EOL;
if ($hwa_tabs) {
        echo '<div class="tabber"  style="width: 100%; margin-top: 10px;">'.PHP_EOL;
        echo $tabs;
        echo '</div>'.PHP_EOL;
}
if (!$hwa_tabs || $hwa_credits) {
        echo '<div class="ajaxHead" style="background-color: transparent;">'.$credits.'</div>'.PHP_EOL;
}
#
echo '<script type="text/javascript" src="'.$javaFolder.'jquery.js"></script>'.PHP_EOL;
        if ($hwa_tabs) {
	        echo '<script type="text/javascript" src="'.$javaFolder.'tabber.js"></script>'.PHP_EOL;
	}
echo '<script type="text/javascript" src="'.$javaFolder.'highcharts.js"></script>'.PHP_EOL;
echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;

?>