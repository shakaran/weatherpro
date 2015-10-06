<?php
# ---------------HERE YOU NEED TO MAKE SOME CHANGES ----------------------
#
$monthDay	= false;		// #####	june 30  ==> true   30 june	==> false				
$myfolder	= './wuforecast/';	// #####        only change this if you stored the wu forecasts scripts in another folder
#
$printIcons	= true;                 //              icons part
$iconsSeparate  = false;		//              set to false if icons should be a tab in table area
$cntIcons	= 10;			// #####        number of icons to be printed
#
$printGraph	= true;                 //              meteogram graph
$graphsSeparate = false;		//              set to false if graphs should be a tab in table area
$graphHeight    = 340;                  //              340 = 340px heigh  So it is in pixels without the px characters
#
$printTable	= true;                 //              details table
$tableSeparate  = false;
$wuTabHeight	= '500px';
#
$printCredit	= false;                 //              credit line and debug info
#
# IMPORTANT
$fullpage_link  = true;                 # set to false if no link to full page is wanted            ######
$wu_fct_page    = 'wu_fct_page';        // script name of the full page metno forecast
# --------------- END OF SETTINGS ----------------------------------------
#
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
#
$pageName	= 'wsDashWU.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
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
$wuKey		        = $SITE['wuKey'];
$myLang		        = $SITE['lang'];
$myLatitude	        = $SITE['latitude']; 
$myLongitude	        = $SITE['longitude']; 
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
$tabber_needed          = false;
if ($printIcons && !$iconsSeparate)     {$tabber_needed = true;} 
elseif ($printGraph && !$graphsSeparate){$tabber_needed = true;} 
elseif ($printTable && !$tableSeparate) {$tabber_needed = true;} 
#
$script = $myfolder.'wuforecast3.php';
ws_message (  '<!-- module wsDashWU.php ('.__LINE__.'): loading '.$script.' -->');
if (!include $script) {return;}
#
echo '<div class="blockDiv" style="">'.PHP_EOL;
#-------------------------------------------------------------------------------
echo '<div class="ajaxHead" >'.langtransstr('Weather Forecast').' ('.langtransstr('summary').')&nbsp;&nbsp;';
if ($fullpage_link <> '') {         
        $wuLink         =  $SITE['pages'][$wu_fct_page].'&amp;lang='.$lang.$extraP.$skiptopText;  // 52-1
        echo '<a href="'.$wuLink.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
}
echo '</div>'.PHP_EOL;
#-------------------------------------------------------------------------------
# icons
if ($printIcons && $iconsSeparate) { echo    $htmlicons.'<br />'; }
#-------------------------------------------------------------------------------
# the graphs
if ($printGraph && $graphsSeparate) {
        echo    '<div id="containerTemp" style="height: 340px;">';
        echo    'here the graph will be drawn';
        echo    '</div><br />'.PHP_EOL;
	echo    $graphPart1.PHP_EOL;
}
#-------------------------------------------------------------------------------
if ($printTable && $tableSeparate) {
        echo    '<div style="">';
        echo    $htmltable;
        echo    '</div><br />'.PHP_EOL;
}
if ($tabber_needed) {
        echo    '<div class="tabber"  style="width:100% margin: auto;">'.PHP_EOL;
#-------------------------------------------------------------------------------
# icons
        if ($printIcons && !$iconsSeparate) { 
                echo    '<div class="tabbertab" style=""><h2>'.wswufcttransstr($trans.'Icons').'</h2><br />'.PHP_EOL;
                        echo    $htmlicons;
                echo    '<br /></div>'.PHP_EOL; 
        }
#-------------------------------------------------------------------------------
# the graphs
        if ($printGraph && !$graphsSeparate) {
                echo    '<div class="tabbertab" style=""><h2>'.wswufcttransstr($trans.'Graphs').'</h2><br />'.PHP_EOL;        
                        echo    '<div id="containerTemp" style="height: 340px;">';
                        echo    'here the graph will be drawn';
                        echo    '</div>'.PHP_EOL;
                        echo    $graphPart1.PHP_EOL;
                echo    '</div>'.PHP_EOL;
        }
#-------------------------------------------------------------------------------
        if ($printTable && !$tableSeparate) {
                echo    '<div class="tabbertab" style="height: '.$wuTabHeight.'"><h2>'.wswufcttransstr($trans.'Table').'</h2>'.PHP_EOL;                
                        echo    '<div style="">';
                        echo    $htmltable;
                        echo    '</div>'.PHP_EOL;
                echo    '</div>'.PHP_EOL;
        }
        echo    '</div>'.PHP_EOL;
}
#-------------------------------------------------------------------------------
if ($printCredit) {echo $credit;}
#
echo '</div>'.PHP_EOL;
#
$myLang = $lang = $saved_lang;
#---------------------------------------------------------------------------
#  the following lines output the needed scripts / html for a stand alone page
#
#-------------------I M P O R T A N T  -------------------------------------
# now we add the needed javascripts if we display the graphs
# if you use this script inside another script make sure you add the javascripts yourself
#---------------------------------------------------------------------------
#
if ($tabber_needed) {
        echo '<script type="text/javascript" src="'.$javaFolder.'tabber.js"></script>'.PHP_EOL;
}

if ($printGraph) {
	echo '<script type="text/javascript" src="'.$jqueryurl.'"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$highchartsurl.'"></script>'.PHP_EOL;
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
