<?php
# ----------------------------------------------------------------------
#                HERE YOU NEED TO MAKE SOME CHANGES
# what parts should be printed
#       forecast-times with station information
$yrno_times             = false;        // 
#       icons for top of page or startpage
$iconGraph		= true; 	// icon type header  with 2 icons for each day (12 hours data)
$iconinTabs             = false;
$topCount		= 10;		// max nr of day-part forecasts in icons or graph
#       two kinds of meteograms
$yrnoMeteogram          = true;         // standard yrno meteogram two days - one column per hour
$yrnoMeteogramInTabs	= false; 

$chartsMeteogram	= true;	        // high charts meteogram -  6 days - one colom for every 6 hours - 
$MeteogramInTabs	= false; 	// high charts graph separate (false) or in a tab (true)
$chartsMeteogramHeight  = '340px';
#
$yrnoTable		= true;		// table with one line for every 6 hours
$yrnoDetailTable	= true;		// table with one line for every 3 or 1 hours
$tableHeight            = '500px';      // no restricted height use ''  - restrict use number of pixels: '500px' 
$tableInTabs            = true;         // put tables in tabs
# ----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {       //--self downloader --
   $filenameReal = __FILE__;		# display source of script if requested so
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
$pageName	= 'ws_yrno_page.php';
$pageVersion	= '3.20 2015-09-21';
# ----------------------------------------------------------------------
# 3.20 2015-09-21 release 2.8 version 
# ----------------------------------------------------------------------
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
# ---------THE FOLLOWING SETTINGS ARE COPIED FROM YOUR SETTINGS  -------
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
# multiforecast settings
if (!isset ($SITE['multi_forecast'])) { $SITE['multi_forecast'] = false; }
$selection_file = $SITE["multi_fct_keys"];
#$SITE['multi_forecast'] = false;       // if no selectable forecast wanted, you can override that setting here
# --------------- END OF SETTINGS --------------------------------------
#
# --------------- make selection list of area's ------------------------
$areas          = array($yrnoID);       // default your area
$default        = 0;                     
#
list ($yr_country,$yr_province,$yr_city) = explode ('/',$areas[0].'/' );
$select_list    = array($yr_city);
#
if (isset ($SITE['multi_forecast']) && $SITE['multi_forecast'] == true && file_exists($selection_file) ){
        $arr    = file($selection_file);
        $end    = count ($arr);
        for ($n = 0; $n < $end; $n++) {
                $string         = trim($arr[$n]);
                if ($string == '') {continue;}
                if (substr($string,0,1) == '#')         
                        {continue;}     // skip comments
                list ($none,$none0,$none1,$select,$none2,$none3,$area) = explode ('|',$string.'|||||||');
                $area   = trim ($area);
                $select = trim ($select);
                if ($area == '' || $select == '')       
                        {continue;}     // skip lines with empty fileds
                $areas[]        = str_replace(' ','_',$area);         // replace spaces in names with onderscores
                $select_list[]  = trim($select);
        }
}
$end_areas      = count($areas);
#
ws_message (  '<!-- module ws_yrno_page.php ('.__LINE__.'): '.PHP_EOL.print_r($areas,true).'selects: '.PHP_EOL.print_r($select_list,true).' -->');
#
# check if we have to generate a forecast for another city as the default.
if (isset ($_GET['city']) && 1.0*$_GET['city'] < $end_areas) {  
        $default        = 1.0*$_GET['city']; 
        $yrnoID         = $areas[$default];     // replace default setting only with a valid users request 
        $yourArea       = langtransstr($select_list[$default]);
}   
# ----------------------------------------------------------------------
# now some housekeeping, firsdt check if we want data in tabs
$tabber_needed          = false;
if ($iconGraph && $iconinTabs)                  {$tabber_needed = true;} 
elseif ($yrnoMeteogram && $yrnoMeteogramInTabs) {$tabber_needed = true;} 
elseif ($chartsMeteogram && $MeteogramInTabs)   {$tabber_needed = true;} 
elseif ($yrnoTable && $tableInTabs)             {$tabber_needed = true;} 
elseif ($yrnoDetailTable && $tableInTabs)       {$tabber_needed = true;} 
#
if ($SITE['uomTemp'] == '&deg;C') {$metric = true;} else {$metric = false;}
#
# set some style and heights
$styleborder    = '';
$margin         = ' margin: 10px 0px;'; 
$topWidth       = ' width: '.$defaultWidth.';';
#
if ($chartsMeteogramHeight <> '' )      { $chartsMeteogramHeight  = 'height: '.$chartsMeteogramHeight.';';}
if ($tableHeight <> '')                 { $tableHeight            = 'height: '.$tableHeight.';';}
#
# generate all html for this forecast
$script	= $scriptDir.'yrnoGenerateHtml.php';
ws_message (  '<!-- module ws_yrno_page.php ('.__LINE__.'): loading '.$script.' -->');
if (!include  $script) { return; }      // if the generate script encountered errors, do nothing
#
# send the html to the visitors browser
#
echo '<!-- start output ws_yrno_page -->
<div class="blockDiv" style="">'.PHP_EOL; // the large div
#
if ($end_areas > 1) {                    // do we want a selection box or is it only our own area
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
}                                       // end of multi select
else {                                  // no multi select, standard top part is sent
        if ($yrno_times) {echo '<div class="blockHead">'.$wsUpdateTimes.'</div>'.PHP_EOL;}
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
	$script = $scriptDir.'yrnoavansert4.php';
        ws_message (  '<!-- module ws_yrno_page.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
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
	$script = $scriptDir.'yrnoavansert4.php';
        ws_message (  '<!-- module ws_yrno_page.php ('.__LINE__.'): loading '.$script.' -->');
        include $script;
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
#	echo '<script src="http://code.highcharts.com/highcharts.js"></script>'; # needs adaption in js
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
if ($tabber_needed) {
        echo '<script type="text/javascript" src="'.$javascriptsDir.'tabber.js"></script>'.PHP_EOL;
}
echo '<!-- end of output ws_yrno_page -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-09-21 release 2.8 version 
