<?php    ini_set('display_errors', 'On');   error_reporting(E_ALL);
#
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {    # display source of script if requested so
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
$pageName	= 'davconvp2_v3.php';
$pageVersion	= '0.00 2015-06-10';
#
if (!isset($SITE)){echo "<h3>invalid call to $pageName <h3>"; exit;}	//  page to load without menu system//
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------SETTINGS ------------------------------------
# -------- 		set for VP-2
$console    	= 'VP2';		// $console = 'VP2'; for VPro2 and VPro2 Plus, 
# -------- 		set for VP-2
#$console    	= 'VUE';		// $console = 'VUE'; for Vue
#
# -------- 		WeatherDisplay content of clientrawhour.txt file
$uomsys     	= 'M';			// WD Users Only - $uomsys = 'M'; for Metric units, $uomsys = 'I'; for Imperial (English) units
#
#----------------------GENERAL SETTINGS --------------------------------
# copy settings to local variables	
#
$use_davcon	= false;
#
$uomRain 	= $SITE['uomRain'];
$uomTemp 	= $SITE['uomTemp'];
$uomBaro 	= $SITE['uomBaro'];
$uomWind 	= $SITE['uomWind'];
$uomSolar	= 'w/m2';
$WXsoftware 	= $SITE['WXsoftware'];
$latitude 	= $SITE['latitude'];
$scriptdir	= 'davconsole/'; 
$imgdir    	= 'davconsole/img/';
#
$vpforecasttext	= str_replace ('_', ' ',$ws['fcstTxt']);
$forecast_trans	= langtransstr($ws['fcstTxt']);
$vpforecast_en	= $ws['fcstTxtOrg'];
#
$next_script_VUE= $scriptdir.'davconvueCW-inc.php';
$next_script_VP2= $scriptdir.'davconvp2_v3-inc.php';
#
$dataurl	= '';
if ($WXsoftware == 'CU' && $use_davcon) {
  	$graphurl = $SITE['uploadDir'].'davcon24.txt';    	// for Cumulus graph data
} 
elseif ($SITE['realtime'] == 'cltrw') {	
   	$graphurl = $SITE['clientrawDir'].'clientrawextra.txt';	// for Weather Display graph data
}
if ($dataurl <> '') {
	if (!file_exists($dataurl))  {echo '<h3 style="text-align; center;">' . $dataurl  . ' not found! </h3>' ; return;}
	if (!file_exists($graphurl)) {echo '<h3 style="text-align; center;">' . $graphurl . ' not found! </h3>' ; return;}
}
if ($SITE['UV'] == true  && $SITE['SOLAR']  == true && $console == 'VP2') {$vp2Plus = 'Y';}  else  {$vp2Plus = 'N';}


$itimeout   = 3;    	// time between image updates in seconds
                    	
$tempbtntxt	= langtransstr('24hr Temperature Graph');
$humbtntxt	= langtransstr('24hr Humidity Graph');
$windbtntxt	= langtransstr('24hr Windspeed Graph');
$rainbtntxt	= langtransstr('24hr Rain Graph');
$barbtntxt	= langtransstr('24hr Barometer Graph');
$lampsbtntxt	= langtransstr('Turn Backlight on/off');
$unavailabe_text= langtransstr('Function not available');
$fcastbtntxt    = $WxCenbtntxt	= $graphbtntxt  = $hilowbtntxt  = $alarmbtntxt  = $timebtntxt 	= $unavailabe_text;
$fcastbtn       = $WxCenbtn   	= $graphbtn 	= $hilowbtn     = $alarmbtn     = $timebtn    	= false;

if (isset ($SITE['pages']['ws_yrno_page']) ) {	
	$fcastbtn 	= $WxCenbtn   	= $SITE['pages']['ws_yrno_page'];
	$fcastbtntxt    = $WxCenbtntxt	= langtransstr('Forecast'); 
} elseif (isset ($SITE['pages']['ws_yrno_page']) ) {	
	$fcastbtn 	= $WxCenbtn   	= $SITE['pages']['ws_metno_page'];
	$fcastbtntxt    = $WxCenbtntxt	= langtransstr('Forecast'); 
} 
if (isset ($SITE['pages']['wsWuGraphs']) ) {	
	$graphbtn 	= $SITE['pages']['wsWuGraphs'];
	$graphbtntxt  	= langtransstr("Graphs for Last 48 hrs"); 
}
if (isset ($SITE['pages']['trends']) ) {	
	$hilowbtn 	= $SITE['pages']['trends'];
	$hilowbtntxt    = langtransstr('Trends - Records'); 
}
if (isset ($SITE['pages']['historyv3']) ) {	
	$alarmbtn 	= $timebtn    	= $SITE['pages']['historyv3'];
	$alarmbtntxt    = $timebtntxt	= langtransstr('Annual Data'); 
}

$donebtn      	= './index.php';           			// done button
$donebtntxt   	= langtransstr("Return to Homepage"); 

############################################################################
$fcsticon 	= c_get_fcsticon($vpforecast_en);
$moonage	= $ws['lunarAge'];
$latitude	= $SITE['latitude'];
$moonic 	= c_get_moon($moonage,$latitude);

$console_credit = '
Based on script by <a target="_blank" href="http://www.axelvold.net">Axelvold\'s weather &amp; Photo</a> and <a target="_blank" href="http://www.stenestad-vader.com/">Stenestads Vader</a><br />
Modification by  <a target="_blank" href="http://www.nordicweather.net/">Weatherstation Pertteli</a> and <a target="_blank" href="http://www.lokaltvader.se/">Saro/Budskars Vader</a><br />
Graphics, icons and code revised by <a target="_blank" href="http://silveracorn.co.nz/weather/">Silver Acorn Weather</a><span style="font-size:7px; color:gray">&nbsp;v2.3.0</span><br /><br />
Adapted for this template by<br /><a target="_blank" href="http://leuven-template.eu"> Leuven Template - Wim van der Kuil</a>
';

// END Settings
$page_title     = 'My console';
#
?>
<div class="blockDiv"><!-- leave this opening div as it is needed  a nice page layout -->

<h3 class="blockHead"><?php echo $page_title; ?></h3>
<?php
// Load include file for console type - all console type specific code

if ($console != 'VUE') {require_once($next_script_VP2);} else {	require_once($next_script_VUE);}
// Forecast Icon


// Moon Icon

// make above php variables available for use in both this script and / or jquery.davconsoleCW.js
?>
<script type="text/javascript">
var itimeout	= '<?php echo $itimeout*1000; ?>'
var showsolar   = '<?php echo $showsolar; ?>'
var VPet        = '<?php echo $VPet; ?>'
var uomsys      = '<?php echo $uomsys; ?>'
var fcastbtn    = '<?php echo $fcastbtn; ?>'
var WxCenbtn    = '<?php echo $WxCenbtn; ?>'
var graphbtn    = '<?php echo $graphbtn; ?>'
var hilowbtn    = '<?php echo $hilowbtn; ?>'
var alarmbtn    = '<?php echo $alarmbtn; ?>'
var timebtn     = '<?php echo $timebtn; ?>'
var donebtn     = '<?php echo $donebtn; ?>'
var fcsticon    = '<?php echo $fcsticon; ?>'
var moonic      = '<?php echo $moonic; ?>'
</script>
<script type="text/javascript">
// GRAPHS
<!--
   var d1 = <?php echo $temp ?>;
   var d2 = <?php echo $hum  ?>;
   var d3 = <?php echo $wind ?>;
   var d4 = <?php echo $rain ?>;
   var d5 = <?php echo $baro ?>;
   var d6 = <?php echo $solr ?>;

   var options = {
      xaxis: {mode:null},
      yaxis: {mode:null},
      grid:  {show:false},
      legend:{ show:false}
   };

   var data = {
      data: d1, 
      points: { show: true, fill: true,fillColor: "#053D6C",radius: 1 },
      color: "#053D6C",
      shadowSize: 1
   };

   var data2 = {
      data: d2, 
      points: { show: true, fill: true,fillColor: "#053D6C",radius: 1 },
      color: "#053D6C",
      shadowSize: 1
   };

   var data3 = {
      data: d3, 
      points: { show: true, fill: true,fillColor: "#053D6C",radius: 1 },
      color: "#053D6C",
      shadowSize: 1
   };

   var data4 = {
      data: d4, 
      points: { show: true, fill: true,fillColor: "#053D6C",radius: 1 },
      color: "#053D6C",
      shadowSize: 1
   };

   var data5 = {
      data: d5, 
      points: { show: true, fill: true,fillColor: "#053D6C",radius: 1 },
      color: "#053D6C",
      shadowSize: 1
   };

   var data6 = {
      data: d6, 
      points: { show: true, fill: true,fillColor: "#053D6C",radius: 1 },
      color: "#053D6C",
      shadowSize: 1
   };
   var plot = $.plot($("#placeholder"), [data], options);
   $("#tempbtn").click(function()  { var plot = $.plot($("#placeholder"), [data],  options);
      $("#grlab").unbind('click').html('<?php echo "TEMP"; ?>');
      $("#grmax").unbind('click').html('<?php echo $tmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $tmin;  ?>');
   });

   $("#humbtn" ).click(function()  { var plot = $.plot($("#placeholder"), [data2], options);
      $("#grlab").unbind('click').html('<?php echo "HUM";  ?>');
      $("#grmax").unbind('click').html('<?php echo $hmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $hmin;  ?>');
   });

   $("#windbtn").click(function()  { var plot = $.plot($("#placeholder"), [data3], options);
      $("#grlab").unbind('click').html('<?php echo "WIND"; ?>');
      $("#grmax").unbind('click').html('<?php echo $wmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $wmin;  ?>');
   });

   $("#rainbtn").click(function()  { var plot = $.plot($("#placeholder"), [data4], options);
      $("#grlab").unbind('click').html('<?php echo "RAIN"; ?>');
      $("#grmax").unbind('click').html('<?php echo $rmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $rmin;  ?>');
    });

   $("#barbtn" ).click(function()  { var plot = $.plot($("#placeholder"), [data5], options);
      $("#grlab").unbind('click').html('<?php echo "BAR";  ?>');
      $("#grmax").unbind('click').html('<?php echo $bmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $bmin;  ?>');
   });

   $("#solarbtn").click(function()  { var plot = $.plot($("#placeholder"), [data6], options);
      $("#grlab").unbind('click').html('<?php echo "SOLar"; ?>');
      $("#grmax").unbind('click').html('<?php echo $smax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $smin;  ?>');
    });

   $("#tempbtnt").click(function() { var plot = $.plot($("#placeholder"), [data],  options);
      $("#grlab").unbind('click').html('<?php echo "TEMP"; ?>');
      $("#grmax").unbind('click').html('<?php echo $tmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $tmin;  ?>');
   });

   $("#humbtnt" ).click(function() { var plot = $.plot($("#placeholder"), [data2], options);
      $("#grlab").unbind('click').html('<?php echo "HUM";  ?>');
      $("#grmax").unbind('click').html('<?php echo $hmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $hmin;  ?>');
   });

   $("#windbtnt").click(function() { var plot = $.plot($("#placeholder"), [data3], options);
      $("#grlab").unbind('click').html('<?php echo "WIND"; ?>');
      $("#grmax").unbind('click').html('<?php echo $wmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $wmin;  ?>');
   });

   $("#rainbtnt").click(function() { var plot = $.plot($("#placeholder"), [data4], options);
      $("#grlab").unbind('click').html('<?php echo "RAIN"; ?>');
      $("#grmax").unbind('click').html('<?php echo $rmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $rmin;  ?>');
   });

   $("#barbtnt" ).click(function() { var plot = $.plot($("#placeholder"), [data5], options);
      $("#grlab").unbind('click').html('<?php echo "BAR";  ?>');
      $("#grmax").unbind('click').html('<?php echo $bmax;  ?>');
      $("#grmin").unbind('click').html('<?php echo $bmin;  ?>');
   });

//-->
function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
rotate_n	= 0;
function console_rotate_content(){
	if (cblock0_0.style.display == "block") {
		cblock0_0.style.display = "none"
		cblock0_1.style.display = "block"
	} else 	{
		cblock0_0.style.display = "block"
		cblock0_1.style.display = "none"
	}
	if (showsolar == 'Y') {
		if (cblock1_0.style.display == "block") {
			cblock1_0.style.display = "none"
			cblock1_1.style.display = "block"
		} else 	{
			cblock1_0.style.display = "block"
			cblock1_1.style.display = "none"
		}
	}
	setTimeout("console_rotate_content()",itimeout) 	
}  

setTimeout("console_rotate_content()",itimeout)  
</script>
</div>

<?php
# ------------------------------------------------------------------------------
# Determine forecast icon from Davis forecast string $vpforecast_en
# ------------------------------------------------------------------------------
#
function c_get_fcsticon($vpforecast_en) {
	global $SITE;
	$DC_fcsttmp = "DC_{$vpforecast_en}";  // Station forecast string
# ------------------------------------------------------------------------------
# WARNING              DO NOT CHANGE ANY OF THESE STRING TESTS !!!
#                      TESTS and ORDER CRITICAL !!!
# ------------------------------------------------------------------------------
	if     (preg_match('/Mostly clear/i',                       		$DC_fcsttmp) ) {$fcsticon = "mclr.png";}     
	elseif (preg_match('/increasing clouds and warmer/i',        		$DC_fcsttmp) ||   
		preg_match('/warmer. Precipitation possible within 24/i', 	$DC_fcsttmp) ||   
		preg_match('/Increasing clouds with/i',              		$DC_fcsttmp) ||   
		preg_match('/Partly cloudy/i',                       		$DC_fcsttmp) )  {$fcsticon = "pcld.png";} 
	elseif (preg_match('/cooler. precipitation possible within 12/i', 	$DC_fcsttmp) ||
		preg_match('/cooler. Precipitation likely. Windy/i', 		$DC_fcsttmp) )  {$fcsticon = "rain.png";} 
	elseif (preg_match('/Precipitation ending within 6/i',       		$DC_fcsttmp) ||  
		preg_match('/clearing, cooler and windy/i',          		$DC_fcsttmp) ||
		preg_match('/mostly cloudy and cooler/i',            		$DC_fcsttmp) ||
		preg_match('/Mostly cloudy with/i',                 		$DC_fcsttmp) ||
		preg_match('/change. possible wind shift/i',         		$DC_fcsttmp) ||
		preg_match('/likely/i',                              		$DC_fcsttmp) ||
		 preg_match('/change. precipitation possible within 24/i', 	$DC_fcsttmp) ||
		preg_match('/Precipitation likely possibly/i',       		$DC_fcsttmp) ||
		preg_match('/Precipitation possible within 24/i',    		$DC_fcsttmp) ||
		preg_match('/Precipitation possible within 48/i',    		$DC_fcsttmp) )  {$fcsticon = "mcld.png";} 
	elseif (preg_match('/precipitation continuing/i',       $DC_fcsttmp) ||
		preg_match('/windy within 6/i',                 $DC_fcsttmp) ||
		preg_match('/possible within 12/i',             $DC_fcsttmp) ||
		preg_match('/possible within 6/i',              $DC_fcsttmp) ||
		preg_match('/ending in/i',                      $DC_fcsttmp) ||
		preg_match('/ending within 12/i',               $DC_fcsttmp) )	{$fcsticon = "rain.png";} 
	elseif (preg_match('/Partialy cloudy, Rain possible/i', $DC_fcsttmp) )  {$fcsticon = "pcldrain.png";}
	elseif (preg_match('/Mostly cloudy, Rain possible/i',   $DC_fcsttmp) )	{$fcsticon = "mcldrain.png";} 
	elseif (preg_match('/Partialy cloudy, Snow/i',          $DC_fcsttmp) )	{$fcsticon = "pcldsnow.png";} 
	elseif (preg_match('/Mostly cloudy, Snow/i',            $DC_fcsttmp) )	{$fcsticon = "mcldsnow.png";} 
	elseif (preg_match('/Rain and/i',                       $DC_fcsttmp) )	{$fcsticon = "rainsnow.png";} 
	elseif (preg_match('/Clear/i',                          $DC_fcsttmp) ||
		preg_match('/Sunny/i',                          $DC_fcsttmp) )  {$fcsticon = "mclr.png";}
	elseif (preg_match('/Cloudy/i',                         $DC_fcsttmp) )  {$fcsticon = "mcld.png";}
	elseif (preg_match('/Rain/i',                           $DC_fcsttmp) )  {$fcsticon = "rain.png";}
	elseif (preg_match('/Snow/i',                           $DC_fcsttmp) )	{$fcsticon = "snow.png";}
	elseif (preg_match('/FORECAST/i',                       $DC_fcsttmp) )  {$fcsticon = "grid.png";} // FORECAST REQUIRES 3 HOURS OF RECENT DATA
	elseif (preg_match('/Data will be reloaded/i',          $DC_fcsttmp) )  {$fcsticon = "grid.png";} // Data will be reloaded   
	else {  $DC_fcsttmp = "$DC_fcsttmp|grid.png|\n";          					  // forecast not found !!
		if ($SITE['wsDebug']  == true) {file_put_contents( $SITE['cacheDir'].'davconfcst.txt' , $DC_fcsttmp , FILE_APPEND);} // write un-matched forecast to davconfcst.txt
		$fcsticon = "grid.png";
	}
	return $fcsticon;
} // eo function  get_fcsticon
# ------------------------------------------------------------------------------
# 	Determine Moon icon 
# ------------------------------------------------------------------------------
# TO DO: clean up moonage with texts ??
#
#  Moon icon selection
function c_get_moon($moonage,$latitude) {
	$moonagedays 	= 1.0*$moonage;  preg_replace('|^Moon age:\s+(\d+)\s.*$|is',"\$1",$moonage);
	$topmoon 	= round($moonagedays,1);
	$ns = ($latitude < 0 ) ? 's' : 'n';
	if($topmoon <= 1   || $topmoon >= 27.7) { $moonic = $ns . "moonnew.png";  } else
	if($topmoon > 1    && $topmoon <= 6.5)  { $moonic = $ns . "moonwaxc.png"; } else
	if($topmoon > 6.5  && $topmoon <= 7.5)  { $moonic = $ns . "moonfqtr.png"; } else
	if($topmoon > 7.5  && $topmoon <= 13.5) { $moonic = $ns . "moonwaxg.png"; } else
	if($topmoon > 13.5 && $topmoon <= 14.5) { $moonic = $ns . "moonfull.png"; } else
	if($topmoon > 14.5 && $topmoon <= 20.5) { $moonic = $ns . "moonwang.png"; } else
	if($topmoon > 20.5 && $topmoon <= 21.5) { $moonic = $ns . "moonlqtr.png"; } else
	if($topmoon > 21.5 && $topmoon <  27.7) { $moonic = $ns . "moonwanc.png"; }
	return $moonic;
} // eo function  c_get_moon
