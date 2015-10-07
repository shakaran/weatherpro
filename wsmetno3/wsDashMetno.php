<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
# ----------------------------------------------------------------------
#                HERE YOU NEED TO MAKE SOME CHANGES
# ----------------------------------------------------------------------
# IMPORTANT
$fullpage_link          = true;                 # set to false if no link to full page is wanted            ######
$metno_page             = 'ws_metno_page';      // script name of the full page metno forecast
#
$defaultMargin          = '10px';
# what parts should be printed
#       forecast-times with station information
$metno_times            = false;        // 
#       icons 
$metno_icon_graph	= true; 	// icon type header  with 2 icons for each day (12 hours data)
$metno_top_count	= 8;		// max nr of day-part forecasts in icons or graph
$metno_icons_in_tab	= true;
#       meteogram
$metno_meteogram	= true;	        // high charts meteogram -  6 days - one colom for every 6 hours - 
$metno_meteogram_height = '340px';
$metno_meteogram_in_tabs= true; 	// high charts graph separate (false) or in a tab (true)
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
# --------------- END OF SETTINGS --------------------------------------
#
# print version of script in the html of the generated page
#
$pageName	= 'wsDashMetno.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
# page name of the full pagew link
$topCount = $metno_top_count;   # to debug in all scripts remove $topCount
$script	= $scriptDir.'metnoGenerateHtml.php';
ws_message (  '<!-- module wsDashMetno.php ('.__LINE__.'): loading '.$script.' -->');
if (!include $script) return;
#
$styleborder    = '';  #' border: 1px inset; border-radius: 5px;';
$margin         = ' margin: 10px 0px;';
$width          = ' width: '.$defaultWidth.';';
echo '<!-- forecast -->
<div class="blockDiv">
<div class="ajaxHead" >'.langtransstr('Weather Forecast').' ('.langtransstr('summary').')&nbsp;&nbsp;';
if ($fullpage_link <> '') {
        $metnoLink      =  $SITE['pages'][$metno_page].'&amp;lang='.$lang.$extraP.$skiptopText;       // pagenumber for full forecast page
        echo '<a href="'.$metnoLink.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
}
echo '
</div>'.PHP_EOL;


if ($metno_meteogram_height <> '' )   { $metno_meteogram_height = 'height: '.$metno_meteogram_height.';';}
if ($tableHeight <> '')               { $tableHeight            = 'height: '.$tableHeight.';';}
#
if ($metno_times)                     { echo $wsUpdateTimes.PHP_EOL;}
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
        $style  = 'style="'.$margin.$tableHeight.'"';
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
/*
$style  = 'style="'.$width.$margin.'"';
echo '<div id="credit" '.$style.'>';
        echo $creditString;
echo '</div>'.PHP_EOL;
#echo '<br />'.PHP_EOL;
*/
#-------------------I M P O R T A N T  -------------------------------------
# now we add the needed javascripts if we display the graphs
# if you use this script inside another script make sure you add the javascripts yourself
#---------------------------------------------------------------------------
#
if ($metno_meteogram) {
	echo '<script type="text/javascript" src="'.$javascriptsDir.'jquery.js"></script>'.PHP_EOL;
        if ($tableInTabs || $metno_meteogram_in_tabs) {
	        echo '<script type="text/javascript" src="'.$javascriptsDir.'tabber.js"></script>'.PHP_EOL;
	}
	echo '<script type="text/javascript" src="'.$javascriptsDir.'highcharts.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
echo '</div>
<!--  end of forecast -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
