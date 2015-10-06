<?php
# ----------------------------------------------------------------------
#                HERE YOU NEED TO MAKE SOME CHANGES
#
$fullpage_link          = true;                 # set to false if no link to full page is wanted            ######
$yrno_page              = 'ws_yrno_page';      // script name of the full page metno forecast
#
$yrno_times             = false;        // 
#       icons for top of page or startpage
$iconGraph		= true; 	// icon type header  with 2 icons for each day (12 hours data)
$iconinTabs             = true;
$topCount		= 10;		// max nr of day-part forecasts in icons or graph
#       two kinds of meteograms
$yrnoMeteogram          = true;         // standard yrno meteogram two days - one column per hour
$yrnoMeteogramInTabs	= true; 

$chartsMeteogram	= true;	        // high charts meteogram -  6 days - one colom for every 6 hours - 
$MeteogramInTabs	= true; 	// high charts graph separate (false) or in a tab (true)
$chartsMeteogramHeight  = '340px';
#
$yrnoTable		= true;		// table with one line for every 6 hours
$yrnoDetailTable	= true;		// table with one line for every 3 or 1 hours
$tableHeight            = '500px';      // no restricted height use ''  - restrict use number of pixels: '500px' 
$tableInTabs            = true;         // put tables in tabs
# ----------------------------------------------------------------------
# display source of script if requested so
#
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
$pageName	= 'wsDashYrno.php';
$pageVersion	= '3.20 2015-09-25';
#-------------------------------------------------------------------------------
# 3.20 2015-09-25 release 2.8 version  + remove error avansert3 => 4
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# ---------THE FOLLOWING SETTINGS ARE COPIED FROM YOUR SETTINGS  -------
#
$yrnoID		= $SITE['yrnoID'];
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
$scriptDir      = './wsyrnofct/';
#
# --------------- END OF SETTINGS ----------------------------------------
$tabber_needed          = false;
if ($iconGraph && $iconinTabs)                  {$tabber_needed = true;} 
elseif ($yrnoMeteogram && $yrnoMeteogramInTabs) {$tabber_needed = true;} 
elseif ($chartsMeteogram && $MeteogramInTabs)   {$tabber_needed = true;} 
elseif ($yrnoTable && $tableInTabs)             {$tabber_needed = true;} 
elseif ($yrnoDetailTable && $tableInTabs)       {$tabber_needed = true;} 
#
# print version of script in the html of the generated page
#
#
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
#
$script	= $scriptDir.'yrnoGenerateHtml.php';
ws_message (  '<!-- module wsDashYrno.php ('.__LINE__.'): loading '.$script.' -->');
if (!include  $script) { return; }

$styleborder    = '';
$margin         = ' margin: 10px 0px;'; 
$topWidth       = ' width: '.$defaultWidth.';';
if ($chartsMeteogramHeight <> '' ) {
        $chartsMeteogramHeight  = 'height: '.$chartsMeteogramHeight.';';
}
if ($tableHeight <> '') {
	$tableHeight            = 'height: '.$tableHeight.';';
}
echo '<!-- output of wsDashYrno -->
<div class="blockDiv" style="">'.PHP_EOL;
echo '<div class="blockHead" >'.langtransstr('Weather Forecast').' ('.langtransstr('summary').')&nbsp;&nbsp;';
if ($fullpage_link <> '') {
        $yrnoLink       =   $SITE['pages'][$yrno_page].'&amp;lang='.$lang.$extraP.$skiptopText;       // pagenumber for full forecast page 
        echo '<a href="'.$yrnoLink.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
}
echo '
</div>'.PHP_EOL;
#
if ($end_forecast == 0) {$times = true;} // errors retrieving forecast data
#
if ($yrno_times) {
        $style  = 'class="blockHead" style="" ';
        echo '<div id="times" '.$style.'>'.$wsUpdateTimes.'</div>'.PHP_EOL;
}
#
if ($iconGraph && !$iconinTabs) {
        $style  = 'style="'.$topWidth.$styleborder.$margin.'"';
	echo '<div id="iconGraph" '.$style.'>';
	echo $tableIcons.PHP_EOL;
        echo '</div>'.PHP_EOL;
}
if ($yrnoMeteogram && !$MeteogramInTabs) {
        $style  = 'style="'.$topWidth.$styleborder.$margin.'overflow: hidden; "';
        include $scriptDir.'yrnoavansert4.php';
        echo '<div '.$style.'>';
        echo '  <a href="http://www.yr.no/place/'.$yrnoID.'" target="_blank" title="Vooruitzichten scandinavische leveranciers yr.no">'.PHP_EOL;
        echo '     <img src="'.$im.'" alt="  " style="vertical-align: top; width: 100%; height:302px;"/>'.PHP_EOL;  
        echo '  </a>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
}
if ($chartsMeteogram && !$MeteogramInTabs) {		// are the graphs separate (=false) on the page or are they in a tab
        $style  = 'style="'.$topWidth.$styleborder.$margin.$chartsMeteogramHeight.'"';
        echo '<div id="containerTemp" '.$style.'>
        here the graph will be drawn
        </div>'.PHP_EOL;
        echo $graphPart1.PHP_EOL;
}
if ($tabber_needed) { // generate html for tabs
        echo '<div class="tabber"  style="'.$topWidth.' margin: 10px 0px;">'.PHP_EOL;
}
if ($iconGraph && $iconinTabs) {
        $style  = 'style="'.$topWidth.$styleborder.$margin.'"';
        echo '<div class="tabbertab" style="padding: 0px;"><h2>'.yrnotransstr('Icons').'</h2>'.PHP_EOL;
                echo '<div id="iconGraph" '.$style.'>';
                echo $tableIcons.PHP_EOL;
                echo '</div>'.PHP_EOL;
        echo '</div><br />'.PHP_EOL;
}

if ($yrnoMeteogram && $MeteogramInTabs) {
        $style  = 'style="width: 100%; overflow: hidden; "';
        include $scriptDir.'yrnoavansert4.php';                 # changed 2015-09-22
        echo '<div class="tabbertab" style="padding: 0px;"><h2>'.yrnotransstr('Meteogram').'</h2>'.PHP_EOL;
                echo '<div '.$style.'>';
                echo '  <a href="http://www.yr.no/place/'.$yrnoID.'" target="_blank" title="Vooruitzichten scandinavische leveranciers yr.no">'.PHP_EOL;
                echo '          <img src="'.$im.'" alt="  " style="vertical-align: top;  width: 100%; height:302px;"/>'.PHP_EOL;  
                echo '  </a>'.PHP_EOL;
                echo '</div>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
}
if ($chartsMeteogram && $MeteogramInTabs) {
        $style  = 'style="width: 100%; overflow: hidden; '.$chartsMeteogramHeight.'"';
        echo '<div class="tabbertab" style="padding: 0px;"><h2>'.yrnotransstr('Graph').'</h2>'.PHP_EOL;
                echo '<div id="containerTemp" '.$style.'>';
                echo 'here the graph will be drawn</div>'.PHP_EOL;
                echo $graphPart1.PHP_EOL;
        echo '</div>'.PHP_EOL;	 
}
if ($tableInTabs) {
        $style  = 'style="padding: 0px;'.$tableHeight.'"';
        if ($yrnoTable) {
                echo '<div class="tabbertab" '.$style.'>'.PHP_EOL;
                        echo '<h2>'.yrnotransstr('Forecast by 6 hour intervals').'</h2>'.PHP_EOL;
                        echo $yrnoListTable.PHP_EOL;
                echo '</div>'.PHP_EOL;
        }
        if ($yrnoDetailTable) {
                echo '<div class="tabbertab" '.$style.'>'.PHP_EOL;
                        echo '<h2>'.yrnotransstr('Forecast details').'</h2>'.PHP_EOL;
                        echo $yrnoDetailTable.PHP_EOL;
                echo '</div>'.PHP_EOL;
        }
 }       
if ($tableInTabs || $MeteogramInTabs) {
        echo '</div>'.PHP_EOL;
}
if (!$tableInTabs) {
        $style  = 'style="'.$topWidth.$margin.$tableHeight.' overflow: auto;"';
        if ($yrnoTable) {       
                echo '<div '.$style.'>'.PHP_EOL;
                        echo $yrnoListTable.PHP_EOL;
                echo '</div>'.PHP_EOL;
        }
        if ($yrnoDetailTable) {
                echo '<div '.$style.'>'.PHP_EOL;
                        echo $yrnoDetailTable.PHP_EOL;
                echo '</div>'.PHP_EOL;
        }
}
#	    
#       IMPORTANT do not delete as legally you are bind to display 
#       credit to Metno/Yrno in original readable text, same size as average text in window
#
$style  = 'style="'.$topWidth.'"';
echo '<div id="credit" '.$style.'>'.PHP_EOL;
        echo $creditString.PHP_EOL;
echo '</div>'.PHP_EOL;
echo '</div>'.PHP_EOL;
#---------------------------------------------------------------------------
#  the following lines output the needed scripts / html for a stand alone page
#
if ($chartsMeteogram) {
	echo '<script type="text/javascript" src="'.$javascriptsDir.'jquery.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$javascriptsDir.'highcharts.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
if ($tabber_needed) {
        echo '<script type="text/javascript" src="'.$javascriptsDir.'tabber.js"></script>'.PHP_EOL;
}
echo '<!-- end of wsDashYrno -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-09-25 release 2.8 version 
