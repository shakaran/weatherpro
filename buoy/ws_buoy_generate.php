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
$pageName	= 'ws_buoy_generate.php';
$pageVersion	= '3.20 2015-09-06';
#-------------------------------------------------------------------------------
# 3.20 2015-09-06 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
$Version 	= "buoy-data.php V1.16 modified 2015-08-26";

if ($SITE['uomTemp'] == '&deg;F') {$myUOM = 'E';} else { $myUOM = 'M';}
$NDBCURL = "http://www.ndbc.noaa.gov";          //NDBC website (omit trailing slash)
#
$return		= load_config($Config);  	// Load the configuration file
if ($return = false) {echo '<h3 style="text-align: center;">No config file found, program ends</h3>'; return;}
#
$ourGraphic 	= $maps_folder.$MapImage;	// set the correct location for the map
#
$Units['time'] 	= date('T',time());
$Units['temp']	= trim($SITE['uomTemp']);
$Units['wind']	= trim($SITE['uomWind']);
$Units['baro']	= trim($SITE['uomBaro']);
$Units['wave'] 	= trim($SITE['uomHeight']);
$Units['dist'] 	= trim($SITE['uomDistance']);
if ($showKnots){$Units['wind'] 	= 'kts'; } 
else 	{	$Units['wind'] 	= trim($SITE['uomWind']);}
#
$uom_in['temp']	= 'C';
$uom_in['wind']	= 'm/s';
$uom_in['baro']	= 'hPa';
$uom_in['wave'] = 'm';
$uom_in['dist'] = 'nmi';	// ?? nmiles

generate_css_part1();        	// generate first (standard) part of CSS

# refresh cached copy of page if needed
$dataOK	= false;
if (file_exists($cacheName) and filemtime($cacheName) + $refetchSeconds > time()) {
	$dataOK	= true;
	ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): using Cached version from '.$cacheName.' -->');
	$rawdata 	= file_get_contents($cacheName);
	ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): cache last updated '. date($timeFormat,filemtime($cacheName)) .' size = ' . strlen($rawdata) . ' bytes -->');
}
if ($dataOK == false) {
	$URL	= 'http://www.ndbc.noaa.gov/data/latest_obs/latest_obs.txt';
	ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'):  Need to load a fresh copy');
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
	$rawdata	= curl_exec ($ch);
	curl_close ($ch);
	$from = array (str_repeat(' ', 10), str_repeat(' ', 9), str_repeat(' ', 8), str_repeat(' ', 7), str_repeat(' ', 6), str_repeat(' ', 5), str_repeat(' ', 4), str_repeat(' ', 3), str_repeat(' ', 2)  );
	$rawdata = str_replace ($from,' ',$rawdata);
	if (file_put_contents($cacheName, $rawdata)) {   
                ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): '.$cacheName.' saved to cache  -->');
        }
}  // eo curl
$buoydata 	= explode("\n",$rawdata); 	// get lines of html to process
$end		= count ($buoydata);
ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): linecount ' . $end . ' -->');
if ($end < 3) {echo '<h3  tyle="text-align: center;"No data found, program halts </h3>'; return; }
#-------------------------------------------------------------------------------
# check units in file
#-------------------------------------------------------------------------------
$line	= $buoydata[1];
# echo $line; exit;
$arr 	= explode (' ', $line);
#echo '<pre>'; print_r ($arr); exit;
$uom_in['temp']	= str_replace('deg','',$arr[17]);
$uom_in['wind']	= $arr[10];
$uom_in['baro']	= $arr[15];
$uom_in['wave'] = $arr[11];
$uom_in['dist'] = $arr[20];  # nmiles  nmi?
#-------------------------------------------------------------------------------
# do all unit conversions and save only needed lines.
#-------------------------------------------------------------------------------
$seenBuoy	= array();
$found_wtmp	=  $found_pdty	= $found_wvht	= $found_gust	= $found_vis	= false;
for ($n1=2; $n1 < $end; $n1++) { // read each text line of buoy data
	$line	= $buoydata[$n1];	
	$ID	= substr($line,0,5);
	if (!isset($Buoys[$ID]) ) {continue;}	// skip unneeded lines
	$from 	= array ('MM');			// n/a data replace with -
	$to 	= array ('-');	
	$line 	= str_replace ($from,$to,$line);
#
	list($ID, $LAT, $LON, $year,$month,$day,$hour,$minute,$WDIR,$WSPD,$GST,$WVHT,$DPD,$APD,$MWD,$PRES,$PTDY,$ATMP,$WTMP,$DEWP,$VIS) = explode(' ',$line.str_repeat(' ', 30));
# time
	$time	= gmmktime($hour,$minute,00,$month,$day,$year);
	$TIME	= date($SITE['timeOnlyFormat'],$time);
# air temp
	if ($ATMP <> '-') {
		$temp	= wsConvertTemperature($ATMP, $uom_in['temp']);
		$ATMP	= wsNumber($temp,$SITE['decTemp']);
	}
# water temp	
	if ($WTMP <> '-') {
		$found_wtmp	= true;
		$temp		= wsConvertTemperature($WTMP, $uom_in['temp']);
		$WTMP		= wsNumber($temp,$SITE['decTemp']);
	}
# wind description based on wind dir in degrees
	$WDSC 	= getWindDir($WDIR);		//  180 degr => S
# wind speed
	$wind	= convertWind($WSPD);
	$WSPD	= wsNumber($wind,$SITE['decWind']);
# wind gust 
	if ($GST <> '-') {
		$found_gust	= true;
		$wind	= convertWind($GST);
		$GST	= wsNumber($wind,$SITE['decWind']);
	}
# pressure
	if ($PRES <> '-') {
		$baro	= wsConvertBaro($PRES,$uom_in['baro']);
		$PRES	= wsNumber($baro,$SITE['decBaro']);
	}
# pressure trend
	if ($PTDY <> '-') {
		$found_pdty	= true;
		$old		= $PTDY;
		$baro		= wsConvertBaro(abs($PTDY),$uom_in['baro']);
		$PTDY		= wsNumber($baro,$SITE['decBaro']);
		if ($old < 0) {$PTDY = '-'.$PTDY;} else {$PTDY = '+'.$PTDY;}
	}
# wave height
	if ($WVHT <> '-') {
		$found_wvht	= true;
		$height		= wsConvertDistance($WVHT,$uom_in['wave'],$Units['wave']);
		if (!isset ($SITE['decHeight']) ) {$SITE['decHeight'] = 1;}
		$WVHT		=  wsNumber($height,$SITE['decHeight']);
	}
# visibillity
	if ($VIS <> '-') {
		$found_vis	= true;
		$height		= wsConvertDistance($VIS,$uom_in['dist']);
		if (!isset ($SITE['decDistance']) ) {$SITE['decDistance'] = 1;}
		$VIS		=  wsNumber($height,$SITE['decDistance']);
	}
# list ($ID, $LAT, $LON, $TIME, $ATMP, $WTMP, $WDIR , $WDSC, $WSPD, $GST, $PRES, $PTDY,  $WVHT,  $VIS)
	$seenBuoy[$ID] = $ID.'|'.$LAT.'|'.$LON.'|'.$TIME.'|'.$ATMP.'|'.$WTMP.'|'.$WDIR .'|'.$WDSC.'|'.$WSPD.'|'.$GST.'|'.$PRES.'|'.$PTDY.'|'.$WVHT.'|'.$VIS;
} // end for loop
# echo '<pre> $found_wtmp='.$found_wtmp.'  $found_pdty='.$found_pdty.' $found_wvht='.$found_wvht.' $found_gust='.$found_gust.'  $found_vis= '.$found_vis.PHP_EOL;  print_r($seenBuoy); exit;
# ------------------------------------------------------------------------------
# now generate the data table by looking at our buoys
# ------------------------------------------------------------------------------
prt_tablehead();
foreach ($Buoys as $key => $buoy) { 	// loop over buoys in config file
	prt_tabledata($key);
} // eo for each buoy
$table .= "</table>\n";
# ------------------------------------------------------------------------------
# Now generate the mesomap 
# ------------------------------------------------------------------------------
# top boilerplate for include text
$html_map= '<div id="mesobuoy">
<img src="'.$ourGraphic.'" usemap="#meso" alt="Mesomap of nearby weather buoys" style="border: none"/>'.PHP_EOL;
$html = '<p>'.PHP_EOL.'  <map name="meso" id="meso">'.PHP_EOL;
// generate the map hotspots and links
reset($Buoys);
foreach ($Buoys as $key => $val) { 
	list($BuoyName,$Coords) = explode("\t",$Buoys["$key"]);
	$BuoyURL= $NDBCURL . '/station_page.php?station='.$key.'&amp;unit='.$myUOM;
	$tag 	= gen_tag($key);
	$html  .= '    <area shape="rect" coords="'.$Coords.'" href="'.$BuoyURL.'" target="_blank" '.PHP_EOL.'     title="'.$tag.'" '.PHP_EOL.'     alt="'.$tag.'" />'.PHP_EOL;
}
// finish up the CSS/HTML assembly strings
$html 		.= '  </map>'.PHP_EOL.'</p>'.PHP_EOL;
$CSS 		.= '</style>'.PHP_EOL.'<!-- end buoy-data CSS -->'.PHP_EOL;
$scroller 	.= '</div>'.PHP_EOL;
$table 		.= '<!-- end of included buoy-data text -->'.PHP_EOL;
// ---------------------------------------------------------------------
// now that all the HTML is ready, print it (or the CSS)
if (! $doPrintBUOY) { // no printing needed.. just return the full variables for printing on the including page
$BUOY_CSS 	= $CSS;
$BUOY_MAP 	= $html_map . $scroller . $html . prt_jscript(); 
$BUOY_TABLE	= $table;
return;
}
// --------------------- END OF MAIN PROGRAM ----------------------------
# ------------------------------------------------------------------------------
#  convert degrees into wind direction abbreviation  
# ------------------------------------------------------------------------------ 
function getWindDir ($degrees) {
	if ($degrees == '-') {return '';}
	$string	=  wsConvertWinddir ($degrees);
	$from	= array ('North', 'East', 'South', 'West');
	$to	= array ('N', 'E', 'S', 'W');
	return str_replace ($from,$to,$string);
} // end function getWindDir
# ------------------------------------------------------------------------------
# change wind units if necessary
# ------------------------------------------------------------------------------
function convertWind($wind) {
	global $doWindConvert, $Units, $uom_in;
	if ($wind == '-') 		{return $wind;}
	if ($doWindConvert == false) 	{return $wind;}
	return wsConvertWindspeed($wind, $uom_in['wind'],$Units['wind']);
} // end convertWind
// ------------------------------------------------------------------
# ------------------------------------------------------------------------------
#  produce the table heading row 
# ------------------------------------------------------------------------------
function prt_tablehead ( ){
	global $updated,$maxDistance,$distUnits, $Units, $table, $scroller,$CSS,$LegendX,$LegendY,$ControlX,$ControlY;
	global $found_wtmp, $found_gust, $found_pdty, $found_wvht, $found_vis, $coloms;
	$table .= '<table class="genericTable" style=""><tr>'.PHP_EOL;
	$table .= '<th>ID</th>'.PHP_EOL;
	$table .= '<th>'.langtransstr('Name').	'</th>'.PHP_EOL;
	$table .= '<th>'.langtransstr('Time'). 	' '.$Units['time'] . '</th>'.PHP_EOL;
	$table .= '<th>'.langtransstr('Air').	' '.$Units['temp'] . '</th>'.PHP_EOL;
	$coloms	= 4;
	if ($found_wtmp) {
		$table .= '<th>'.langtransstr('Water').	' '.$Units['temp'] . '</th>'.PHP_EOL;
		$coloms++;
	}
	$table .= '<th>'.langtransstr('Wind').	' '.$Units['wind'] . '</th>'.PHP_EOL;
	if ($found_gust) {
		$table .= '<th>'.langtransstr('Gust').	' '.$Units['wind'] . '</th>'.PHP_EOL;
		$coloms++;
	}
	$table .= '<th>'.langtransstr('Baro').	' '.$Units['baro'] . '</th>'.PHP_EOL;
	if ($found_pdty) {
		$table .= '<th>'.langtransstr('Trend').	' '.$Units['baro'] . '</th>'.PHP_EOL;
		$coloms++;
	}
	if ($found_wvht) {
		$table .= '<th>'.langtransstr('Waves').	' '.$Units['wave'] . '</th>'.PHP_EOL;
		$coloms++;
	}
	if ($found_vis) {
		$table .= '<th>'.langtransstr('Visibillity').' '.$Units['dist'] . '</th>'.PHP_EOL;
		$coloms++;
	}
	$table .= '</tr>'.PHP_EOL;
	$scroller .= "<p id=\"mesolegend\">
<span class=\"content0\">&nbsp;".langtransstr('Air Temperature')."&nbsp;</span>
<span class=\"content1\">&nbsp;".langtransstr('Water Temperature')."&nbsp;</span>
<span class=\"content2\">&nbsp;".langtransstr('Wind Direction @ Speed')."&nbsp;</span>
<span class=\"content3\">&nbsp;".langtransstr('Wind Gust Speed')."&nbsp;</span>
<span class=\"content4\">&nbsp;".langtransstr('Barometer')."&nbsp;</span>
<span class=\"content5\">&nbsp;".langtransstr('Barometer Trend')."&nbsp;</span>
<span class=\"content6\">&nbsp;".langtransstr('Wave Height')."&nbsp;</span>
<span class=\"content7\">&nbsp;".langtransstr('Visibillity')."&nbsp;</span>
<span class=\"content8\"></span>
</p>\n";


	$Top 	= 5;  // default location for values legend on map
	$Left 	= 5;
	if ($LegendX) {$Left = $LegendX;}
	if ($LegendY) {$Top = $LegendY;}
	$CSS .= "#mesolegend {
	top:  ${Top}px;
	left: ${Left}px;
	font-size: 10pt;
	color: #0000FF;
	background-color: #FFFFFF;
	padding: 3px 3px;
}
";

// set up the run/pause/step controls
	$scroller .= '<form action="index.php"> 
<p id="BuoyControls">
<input type="button" value="Run"   name="run"   onclick="set_run(1);" />
<input type="button" value="Pause" name="pause" onclick="set_run(0);" />
<input type="button" value="Step"  name="step"  onclick="step_content();" />
</p>
</form>
';

	$Top = $Top + 25;  // default start for controls is under legend
	if (trim($ControlX) <> '') {$Left = $ControlX;}
	if (trim($ControlY) <> '') {$Top = $ControlY;}


	$CSS .= "#BuoyControls {
	top: ${Top}px;
	left: ${Left}px;
	font-family: Verdana, Arial, Helvetica, sans-serif; 
	font-size: 8pt;
	font-weight: normal;
	position: relative;
	display: inline;
	padding: 0 0;
	border: none;
	z-index: 15;
}
#BuoyControls a {
	padding: 3px 3px;
	background: #666666;
	color: white;
	border: 1px solid white;
}
";
return;
}  // end function prt_tablehead
# ------------------------------------------------------------------------------
# produce one row of buoy data
# ------------------------------------------------------------------------------
function prt_tabledata($ID) {
	global $seenBuoy,$Buoys,$Units,$NDBCURL,$myUOM,$table,$scroller,$CSS,$skipNoData,$windArrowDir,$windArrowSize, $SITE;
	global $found_wtmp, $found_gust, $found_pdty, $found_wvht, $found_vis, $coloms;
	list($Name,$Coords,$Offsets) = explode("\t",$Buoys["$ID"]);

	$BuoyURL = $NDBCURL . '/station_page.php?station='.$ID.'&amp;unit='.$myUOM;
	if (!isset($seenBuoy[$ID])) {
		if ($skipNoData){ return; }
		$table .= '<tr><td><a href="'.$BuoyURL.'" target="_blank">'.$ID.'</a></td><td>'.$Name.'</td><td colspan="'.$coloms.'" style="text-align:left;">No recent reports.</td></tr>'.PHP_EOL;
		return;
	}
# got data for one of our buoys.. format the table entry
	$string = $seenBuoy[$ID];
	list  ($ID, $LAT, $LON, $TIME, $ATMP, $WTMP, $WDIR , $WDSC, $WSPD, $GST, $PRES, $PTDY,  $WVHT,  $VIS) = explode('|',$string.'|');
	$table .= '<tr>'.PHP_EOL;
	$table .= '<td><a href="'.$BuoyURL.'" target="_blank">'.$ID.'</a></td>'.PHP_EOL;
	$table .= '<td>'.$Name.'</td>'.PHP_EOL;
	$table .= '<td>'.$TIME.'</td>'.PHP_EOL;
	$table .= '<td style="text-align: center;">'.$ATMP.'</td>'.PHP_EOL;
	if ($found_wtmp) {
		$table .= '<td style="text-align: center;">'.$WTMP.'</td>'.PHP_EOL;
	}
	$table .= '<td style="text-align: center;">'; 
	if ($WDIR == '-') {
		$table .= $WDIR; 
	} 
	else {	$table .=  $WDSC .' <img src="'.$windArrowDir.$WDSC.'.gif" height="14" width="14"  alt="Wind from '.$WDSC.'" title="Wind from '.$WDSC.'" /> '.$WSPD;
	}
	$table .= '</td>'.PHP_EOL;
	if ($found_gust) {
		$table .= '<td style="text-align: center;">'.$GST.'</td>'.PHP_EOL;
	}
	$table .= '<td style="text-align: center;">'.$PRES.'</td>'.PHP_EOL;
	if ($found_pdty) {	
		$table .= '<td style="text-align: center;">'.$PTDY.'</td>'.PHP_EOL;
	}
	if ($found_wvht) {
		$table .= '<td style="text-align: center;">'.$WVHT.'</td>'.PHP_EOL;
	}
	if ($found_vis) {
		$table .= '<td style="text-align: center;">'.$VIS.'</td>'.PHP_EOL;
	}
	$table .= '</tr>'.PHP_EOL;

# generate the data for the changing conditions display  NOTE: changes here may break the rotating conditions display..
	$scroller .= '<p id="buoy'.$ID.'">
<span class="content0">'.$ATMP.$Units['temp'].'</span>
<span class="content1">'.$WTMP;
	if ($WTMP <> '-') {$scroller .= $Units['temp'];}
	$scroller .= '</span>
<span class="content2">';
	if ($WDIR <> '-') {
		if ($windArrowDir) {
			$scroller .= '<img src="'.$windArrowDir.$WDSC.'.gif" height="14" width="14"  alt="Wind from '.$WDSC.'" title="Wind from '.$WDSC.'" style="float: left"/>';
		}
		$scroller 	.= $WDSC.'@'.$WSPD .' '.$Units['wind'] ;
	} 
	else {	$scroller .= $WDIR;
	}
	$scroller .= '</span>
<span class="content3">'.$GST;
	if ($GST <> '-') {$scroller .= $Units['wind'];}
	$scroller .= '</span>
<span class="content4">'.$PRES 	. $Units['baro'] . '</span>
<span class="content5">'.$PTDY;
	if ($PTDY <> '-') {$scroller .= $Units['baro'];}
$scroller .= '</span>
<span class="content6">'.$WVHT;
	if ($WVHT <> '-') {$scroller .= $Units['wave'];}
	$scroller .= '</span>
<span class="content7">'.$VIS;
	if ($VIS <> '-') {$scroller .= $Units['dist'];}
	$scroller .= '</span>
<span class="content8"></span>
</p>'.PHP_EOL;

// now generate the CSS to place the rotating display over the map
	$Coords = preg_replace("|\s|is","",$Coords);
	list($Left,$Top,$Right,$Bottom) = explode(",",$Coords.',,,,');
	list($OLeft,$OTop) 		= explode(",",$Offsets.',,,');
	if (! $Offsets) {  // use default positioning
		$Bottom = $Bottom - 2;
		$Left 	= $Left - 5 ;
	} 
	else {  $Bottom = $Bottom + $OTop; // use relative positioning from bottom/left
	  	$Left 	= $Left + $OLeft;
	}
	$CSS .= '#buoy'.$ID.' {top:  '.$Bottom.'px;left: '.$Left.'px;}'.PHP_EOL;
	return;
} // end prt_tabledata
# ------------------------------------------------------------------------------
# generate the alt=/title= text for area statement tooltip popups
# ------------------------------------------------------------------------------
function gen_tag($ID) {
	global $seenBuoy,$Buoys,$Units,$NDBCURL;
	list($Name,$Coords) = preg_split("/\t/",$Buoys["$ID"]);
	if (!isset ($seenBuoy[$ID]) ) {$tag = 'No recent reports.'; return $tag;}
	$string = $seenBuoy[$ID];
#echo '<pre>'.$string.PHP_EOL; exit;
	list  ($ID, $LAT, $LON, $TIME, $ATMP, $WTMP, $WDIR , $WDSC, $WSPD, $GST, $PRES, $PTDY,  $WVHT,  $VIS) = explode('|',$string.'|');
	$tag = $Name.' at '.$TIME. ': ';
	if ($ATMP <> '-') 	{ $tag 	.= 'Air:. '.	$ATMP.	$Units['temp'].', ';}
	if ($WTMP <> '-') 	{ $tag 	.= 'Water: '.	$WTMP.	$Units['temp'].', ';}
	if ($WDIR <> '-') 	{ $tag  .= 'Wind: '. 	$WDSC 	.'@'.	$WSPD. $Units['wind'].', ';}
	if ($GST <> '-') 	{ $tag  .= 'Gust: '.	$GST. 	$Units['wind'].', ';}
	if ($PRES <> '-') 	{ 
		$tag  .= 'Baro: '.$PRES.$Units['baro'];
		if ($PTDY <> '-') 	{ $tag  .= ' ('. $PTDY.')';}
		$tag  .= ', ';
	}
	if ($WVHT <> '-') 	{ $tag  .= 'Waves:  '.$WVHT. $Units['wave'];}
	return $tag;
} // end gen_tag
# ------------------------------------------------------------------------------
# convert GMT to locally selected time if needed
# ------------------------------------------------------------------------------
function chgTime($TIME) {

global $firstDate,$firstTime,$lastDate,$lastTime;

$thisDate = $firstDate;
if ($TIME < $firstTime) {
$thisDate = $lastDate;
}
// assemble date for processing
//    thisDate=mm/dd/yyyy TIME=hhmm
$d = substr($thisDate,6,4) . '-' .
substr($thisDate,0,2) . '-' .
		substr($thisDate,3,2) . ' ' .
		substr($TIME,0,2) . ':' .
		substr($TIME,2,2) . ':00 GMT';
$t = strtotime($d);
$TIME = date('Hi',$t);
return $TIME;
}
# ------------------------------------------------------------------------------
# load configuration file from disk
# ------------------------------------------------------------------------------
function load_config($Config) {
    	global $MapImage,$myLat,$myLong,$Buoys,$ImageH,$ImageW,$LegendX,$LegendY,$maxDistance,$ControlX,$ControlY;
    	$rawconfig 	= file($Config); 		// read file into array
    	if ($rawconfig == false) {return false;}
    	ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): loading config from '.$Config.' -->');
# strip comment records, build $Stations indexed array
	foreach ($rawconfig as $rec) {
		$rec = preg_replace("|\n|","",$rec);
		$len = strlen($rec);
		if($rec and substr($rec,0,1) == "#") { continue; } //only take non-comments
#
		$rec .='||||||||'; // null defaults for missing arguments
		list($BuoyID,$BuoyName,$Coords,$Offsets,$COffsets) = explode("|",$rec.'|||');	
		if ($BuoyID == 'MAPIMAGE') {			// MAPIMAGE|Monterey_Bay.jpg|550,485|50,225|
			$MapImage 	= trim($BuoyName);
			$arr 		= explode(",",$Coords.',');
			$ImageH		= 1.0*trim($arr[1]);
			$ImageW		= 1.0*trim($arr[0]);
			$arr 		= explode(",",$Offsets.',');
			$LegendX	= 1.0*trim($arr[0]);
			$LegendY	= 1.0*trim($arr[1]);
			$arr 		= explode(",",$COffsets.',');
			$ControlX	= trim($arr[0]);
			$ControlY	= trim($arr[1]);
			ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): image = '.$MapImage.' w='.$ImageW.' h='.$ImageH.' LX='.$LegendX.' LY='.$LegendY.' CX='.$ControlX.' CY='.$ControlY.' -->');
		} 
		elseif($BuoyID == 'LOCATION') {			// LOCATION|37N|122W|750|
			$myLat 		= $BuoyName;
			$myLong 	= $Coords;
			if ($Offsets) {
				$maxDistance 	= $Offsets;
			}
			ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): location myLat='.$myLat.' myLong='.$myLong.' maxDist='.$maxDistance.' -->');
		} 
		else {		
			list($Left,$Top,$Right,$Bottom) = explode(",",$Coords);	// PCOC1|Port Chicago, CA|364,155,401,170|39,-15|
			if ($Bottom) { // look like a coord set?
				$Buoys["$BuoyID"] = "$BuoyName\t$Coords\t$Offsets";  // prepare for sort
				ws_message ('<!-- module ws_buoy_generate.php ('.__LINE__.'): buoy='.$BuoyID.' name='.$BuoyName.' coord='.$Coords.' offsets='.$Offsets.' -->');
			 }
		}
	} // eo for each
	return true;
} // end function load_config
# ------------------------------------------------------------------------------
# get first/last date/time for observations from 'observations'  line in website
# ------------------------------------------------------------------------------
function getTimeRange($text) {
	$t = preg_match_all("|(\d+) observations from (\S+) (\S+) GMT to (\S+) (\S+) GMT|Usi",$text,$dates);
//	 print "<!-- \n";
//	 print_r ($dates);
//	 print "-->\n";
	$rtn[0] = $dates[1][0];
		 $rtn[1] = $dates[2][0];
		 $rtn[2]  = $dates[3][0];
		 $rtn[3]  = $dates[4][0];
		 $rtn[4]  = $dates[5][0];
	return $rtn; // list($firstDate,$firstTime,$lastDate,$lastTime) in $rtn
} // end getTimeRange
# ------------------------------------------------------------------------------
# print the rotation JavaScript to browser page
# ------------------------------------------------------------------------------
function prt_jscript () {
// NOTE: the following is not PHP, it's JavaScript
//   no changes should be required here.
	$t = '
<script type="text/javascript">
<!-- 
var delay=3000;
var ie4=document.all;
var browser = navigator.appName;
var ie8 = false;
if (ie4 && /MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
var ieversion=new Number(RegExp.$1) // capture x.x portion and store as a number
if (ieversion>=8) {
ie4=false;
ie8=true;
}
}
var curindex = 0;
var totalcontent = 0;
var runrotation = 1;
var browser = navigator.appName;

function get_content_tags ( tag ) {
// search all the span tags and return the list with class=tag 
//
if (ie4 && browser != "Opera" && ! ie8) {
var elem = document.body.getElementsByTagName(\'span\');
	var lookfor = \'className\';
} else {
var elem = document.getElementsByTagName(\'span\');
	var lookfor = \'class\';
}
var arr = new Array();
for(i = 0,iarr = 0; i < elem.length; i++) {
att = elem[i].getAttribute(lookfor);
if(att == tag) {
arr[iarr] = elem[i];
iarr++;
}
}

return arr;
}
function get_total() {
if (ie4) {
//    while (eval("document.all.content"+totalcontent)) {
//      totalcontent++;
	  totalcontent = 8;
//	}
} else{
//    while (var elements = document.getElementsByName("content"+totalcontent)) {
//      var nelements = elements.length;
//	  alert("content"+totalcontent + " length=" +nelements);
	  totalcontent = 8;
//	}
}
}

function contract_all() {
for (var y=0;y<totalcontent;y++) {
var elements = get_content_tags("content"+y);
	  var numelements = elements.length;
//	  alert("contract_all: content"+y+" numelements="+numelements);
	  for (var index=0;index!=numelements;index++) {
var element = elements[index];
		 element.style.display="none";
}
}
}

function expand_one(which) {
contract_all();
var elements = get_content_tags("content"+which);
var numelements = elements.length;
for (var index=0;index!=numelements;index++) {
var element = elements[index];
	 element.style.display="inline";
}
}
function step_content() {
get_total();
contract_all();
curindex=(curindex<totalcontent-1)? curindex+1: 0;
expand_one(curindex);
}
function set_run(val) {
runrotation = val;
rotate_content();
}
function rotate_content() {
if (runrotation) {
get_total();
contract_all();
expand_one(curindex);
curindex=(curindex<totalcontent-1)? curindex+1: 0;
setTimeout("rotate_content()",delay);
}
}

rotate_content();
// -->
</script>
';
	return($t);
// That's the end of the JavaScript, now back to PHP
}  // end prt_jscript
# ------------------------------------------------------------------------------
# initalize the assembly string for CSS
function generate_css_part1 () {
	global $CSS,$MapImage,$maps_folder;
// top of CSS for mesomap display
$CSS ='<!-- begin buoy-data CSS -->
<style scoped>
#mesobuoy {
	background: url(' .$maps_folder. $MapImage . ') no-repeat;
	font-family: Tahoma,Arial,sans-serif;
	font-size: 8pt;
	color: #000088;
	position: relative;
}
#mesobuoy p {
	position: absolute;
	margin: 0 0 0 0;
	padding: 0 0 0 0;
}
#mesobuoy p img {
	border-style: none;
}
#mesobuoy img {
	border-style: none;
}
.buoytable {
	font-family: Verdana,Arial,sans-serif;
	font-size: 10pt;
	color: #000000;
}
.content0 {display: inline;}
.content1 {display: none;}
.content2 {display: none;}
.content3 {display: none;}
.content4 {display: none;}
.content5 {display: none;}
.content6 {display: none;}
.content7 {display: none;}
'; 
} // end load_strings
# --------------end of functions ---------------------------------------
# ----------------------  version history
# 3.20 2015-09-06 release 2.8 version 
