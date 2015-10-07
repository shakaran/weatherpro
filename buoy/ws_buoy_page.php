<?php
#--------           select your buoy area here               -------------------
#
$my_bouys 	= 'mybuoy-United_Kingdom.txt';          // configuration file name
#$my_bouys 	= 'mybuoy-Monterey_Bay.txt';          	// configuration file name
#$my_bouys 	= 'mybuoy-NorthEast.txt';          	// configuration file name
#---------------------------------end of settings       ------------------------ 
# To find other areas please visit JKen True's bouy page  at http://saratoga-weather.org/scripts-buoy.php#buoydata  
# There you can download the original set of scripts which also contain other areas with bouys.
#-------------------------------------------------------------------------------
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_buoy_page.php';
$pageVersion	= '3.20 2015-09-06';
#-------------------------------------------------------------------------------
# 3.20 2015-08-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$my_folder 	= 'buoy/';
$maps_folder	= 'buoy/locations/';
$Config 	= $maps_folder.$my_bouys;	// configuration file
$cacheName 	= $SITE['cacheDir'].'NDBC-bouys.txt';
$windArrowDir 	= $my_folder.'arrows/'; 
$refetchSeconds = 600;     		// refetch every nnnn seconds (600=10 minutes)
$doWindConvert 	= true;  		// true: wind as default template, false: leave at default m/s
$showKnots 	= false;      		// true: show wind in Knots do not use template default
$windArrowSize 	= 'S';   		// ='S' for Small 9x9 arrows   (*-sm.gif)
$skipNoData 	= false;    		// display 'no recent data' in table
$doPrintTable 	= true;   		// turn on/off print of the table data
$doPrintMap 	= true;     		// turn on/off print of the meso-map
$timeFormat 	= $SITE['timeFormat'];  
$Status 	= '';
$ImageW         = '';
$doPrintBUOY 	= false;
$script         = 'ws_buoy_generate.php';
ws_message (  '<!-- module ws_buoy_page.php ('.__LINE__.'): loading '.$script.' -->');
include 	$script;
echo 		$BUOY_CSS; 
?>

<div class="blockDiv" style="text-align: center;">
<h3 class="blockHead"><?php langtrans('Buoys and other weather mesuring devices at sea');?></h3>
<br />
<div style="margin: auto; width: <?php echo $ImageW; ?>px;"><?php print $BUOY_MAP; ?></div>
<br />
<div style="width: 100%;"><?php print $BUOY_TABLE; ?></div>
<br />
<h3 class="blockHead">
<small><?php langtrans('Original script by'); ?>&nbsp;
<a href="http://saratoga-weather.org/scripts-buoy.php#buoydata" target="_blank">Saratoga-weather.org</a>
</small>
</h3>
</div>
<?php
# ----------------------  version history
# 3.20 2015-09-06 release 2.8 version 
