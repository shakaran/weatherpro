<?php
$inside_Leuven_template = true; if (!isset ($skiptopText) ) {$skiptopText = '#data-area';} 
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
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
$pageName	= 'ws_spc_reports.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.10 2015-01-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.10 2015-01-05 first release version
# ----------------------------------------------------------------------
#  This is a minor adaption of the 
#
// Storm Prediction Center Storm Reports 
//                                       
//        last update  03/26/2010        
//                                       
//          curly@ricksturf.com          
//                                       
//         Free to use and modify

// Sort report time.  Choose either one of the next two lines.
//$sort_reports = 'sort';	 //  first report to the last
$sort_reports = 'sort';	// last report to the first

// Change colors
$tornadocolorbkg = "#990000";  // Tornado report background color  RED
$tornadocolorfont = "#FFFFFF";  // Tornado report font color   red tint
$windcolorbkg = "#000066";  // Wind report background color  BLUE
$windcolorfont = "#FFFFFF";  // Wind report font color   blue tint
$hailcolorbkg = "#003300";  // Hail report background color  GREEN
$hailcolorfont = "#FFFFFF";  // Hail report font color   green tint
 

//  Keeps year at two digit. Adds leading zero to single digit year
 if (isset($_GET['mo'])) {
    $archive_year = $_GET['yr'];
   if ($archive_year < 10) {
    $archive_year = preg_replace('/(0)(1-9)*/', '${2}', $archive_year);  
      if ($archive_year <= 9) {
       $archive_year = "0".$archive_year;
      }
   }
    $archived = $archive_year.$_GET["mo"].$_GET["dt"];
    $reports_archive  = "http://www.spc.noaa.gov/climo/reports/".$archived."_rpts.csv"; 	
 }

$cutoff_time = (date("O")+ "1200"); 
$now_time = date("G") .  date("i");
$tz = date("T");
$cut_time = $cutoff_time;
$cut_time = preg_replace('/(\d*)(\d{2})/', '${1}:${2}', $cut_time);

 if ($cutoff_time == "1200") {$cut_time = "noon";}
 else {$cut_time = $cut_time . "am";}
  
// Path to SPC csv data file
$reports_today  = "http://www.spc.noaa.gov/climo/reports/today.csv"; 
$reports_yesterday  = "http://www.spc.noaa.gov/climo/reports/yesterday.csv"; 	

//  Start with defaults
$message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>";
$error_message = "";
$report_listed = "Total reports today: ";
$storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
$reports_today = $reports_today;

// Get data for selected day
 if (isset($_GET['today'])) {
   $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>"; 
   $error_message = "";
   $report_listed = "Total reports today: ";
   $storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
   $reports_today = $reports_today;
  }

  if (isset($_GET['yesterday'])) { 
   $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/yesterday.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>YESTERDAY'S STORM REPORTS</b></a>";
   $error_message = "";
   $report_listed = "Total reports yesterday: ";
   $storm_map = "http://www.spc.noaa.gov/climo/reports/yesterday.gif";
   $reports_today = $reports_yesterday;
  }	 		  

  if (isset($_GET['mo'])) {		

      if ($_GET["mo"]!=true || $_GET["dt"] != true || $_GET["yr"] != true) {  //  Month, date, or year not selected but submitted
         $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>"; 
		 $error_message = "<em>Month, Date or Year not selected</em><br /><br />";
		 $report_listed = "Total reports today: ";
         $storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
         $reports_today = $reports_today;
       } 
	   
      else {    
	   	  if (checkdate($_GET["mo"],$_GET["dt"],$_GET['yr'])==true){   // Proceed if valid date was submitted	
             $tj_date = cal_to_jd  ( CAL_GREGORIAN  , date("m")  , date("d")  , date("y")  );         // Todays Julian date
             $aj_date = cal_to_jd  ( CAL_GREGORIAN  , $_GET["mo"]  , $_GET["dt"]  , $archive_year  ); // Archived Julian date
   		
             if ($aj_date == $tj_date-1 && $now_time <= $cutoff_time+4) {   //  Yesterdays date but still in todays report  
                $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>"; 
				$error_message = "";
                $report_listed = "Total reports today: ";
                $storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
                $reports_today = $reports_today;
             }	
         else {
             if ($aj_date <= $tj_date-1) {   //  Archived data from current day to the past
                $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/".$archived."_rpts.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\">Reports for <b>".
				$_GET["mo"] . "/" . $_GET["dt"] . "/" . $archive_year . "</b></a>";
				$error_message = "";
	            $report_listed = "Total reports for " . $_GET["mo"] . "/" . $_GET["dt"] . "/" . $archive_year . " : ";
                $storm_map = "http://www.spc.noaa.gov/climo/reports/".$archived."_rpts.gif";
                $reports_today = $reports_archive;
             }
    	   }
             if ($aj_date == $tj_date) {   //  Todays date was selected
                $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>"; 
				$error_message = "";
                $report_listed = "Total reports today: ";
                $storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
                $reports_today = $reports_today;
             }	
			 
             if ($aj_date >= $tj_date+1) {   //  If future date was selected
                $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>";
				$error_message = "<em>Future date was selected - </em>" . $_GET["mo"] . "/" . $_GET["dt"] . "/" . $archive_year. "<br /><br />";
                $report_listed = "Total reports today: ";
                $storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
                $reports_today = $reports_today;
             }	
	   	  }			
  
	   	  if (checkdate($_GET["mo"],$_GET["dt"],$_GET['yr'])==false){   // Proceed if invalid date was submitted
             $message = "<a href=\"http://www.spc.noaa.gov/climo/reports/today.html\" title=\"View these reports at SPC - Courtesy of NOAA\" target=\"_blank\"><b>TODAY'S STORM REPORTS</b></a>";
             $error_message = "<em>Date submitted does not exist - </em>" . $_GET["mo"] . "/" . $_GET["dt"] . "/" . $archive_year. "<br /><br />";			 
             $report_listed = "Total reports today: ";
             $storm_map = "http://www.spc.noaa.gov/climo/reports/today.gif";
             $reports_today = $reports_today;
          }	
      } 
  }	 
	
// Begin  data extraction
// curl replacement
  if(function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$reports_today);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $raw_data = curl_exec($ch);
    curl_close($ch);
  } else {
    $raw_data = file_get_contents($reports_today);
  }

// Checks for invalid hour. Sets invalid time to 12:00 UTC
 function bad_timeA($matches) {
   return $matches[1].($matches[2]-$matches[2]+1).($matches[3]-$matches[3]+2).($matches[4]-$matches[4]).($matches[5]-$matches[5]).$matches[6];
 }
$raw_data = preg_replace_callback("|(\s)([3-9])(\d{1})(\d{1})(\d{1})([\,])|","bad_timeA" ,  $raw_data);  // works for first wrong time digit

// Checks for invalid minutes. Sets invalid time to 12:00 UTC
 function bad_timeC($matches) {
   return $matches[1].($matches[2]-$matches[2]+1).($matches[3]-$matches[3]+2).($matches[4]-$matches[4]).($matches[5]-$matches[5]).$matches[6];
 }
$raw_data = preg_replace_callback("|(\s)(\d{1})(\d{1})([6-9])(\d{1})([\,])|","bad_timeC" ,  $raw_data); 

// Gets posted UTC time and convert it to local time
 function local_time($matches) {
   return $matches[1].($matches[2]+24).$matches[3].$matches[4];
 }
$raw_data = preg_replace_callback("|(\s)([00-4])(\d{2})([\,])|","local_time" ,  $raw_data);
  
// Takes the SPC UTC time of event and adjusts it to your local time
 function adj_time($matches) {
     $time_zone=date("Z")/60/60;
     return $matches[1].($matches[2]+($time_zone)).$matches[3].$matches[4];
 }
$raw_data = preg_replace_callback("|(\s)(\d{2})(\d{2})([\,])|","adj_time" ,  $raw_data);  //  Subtract time zone difference from hour

// Adds 24 hours to a negative time
 function mid_time($matches) {
     return $matches[1].($matches[2]+24).$matches[3].$matches[4];
 }
$raw_data = preg_replace_callback("|(\s)(-\d{1})(\d{2})([\,])|","mid_time" ,  $raw_data);  

//  Adds leading zero to am hours
 function zero_time($matches) {
     return $matches[1].("0".$matches[2]).$matches[3].$matches[4];
 }
$raw_data = preg_replace_callback("|(\s)(\d{1})(\d{2})([\,])|","zero_time" ,  $raw_data);  

// Sets midnight hour to 2400
 function midnight_time($matches) {
     return $matches[1].($matches[2]+24).$matches[3].$matches[4];
 }
$raw_data = preg_replace_callback("|(\s)(0)(00)([\,])|","midnight_time" ,  $raw_data);  

// Adds UNKNOWN location if missing  (UNK)
 function missing_loc($matches) {
     return $matches[1].$matches[2].$matches[3].$matches[4]."(UNK)".$matches[5].$matches[6].$matches[7];
 }
$raw_data = preg_replace_callback("|(\,)(-[0-9]*.[0-9]*)(\,)([A-Z0-9*\.*\s].*\s)(\s)(\d{4})(\,)|","missing_loc" ,  $raw_data);  

// Separate event type data
$raw_data = preg_replace('/Time,/', '', $raw_data);   // removes Time
$raw_data = preg_replace('/F[_|-]Scale,/', 'TORNADO@ ', $raw_data); // gets tornado data
$raw_data = preg_replace('/Speed,/', 'WIND@ ', $raw_data);  // gets wind data
$raw_data = preg_replace('/Size,/', 'HAIL@ ', $raw_data);   // gets hail data
$raw_data = preg_replace('/Location,County,State,Lat,Lon,Comments(\s|\r|\n)/', '', $raw_data);  // removes unwanted
$raw_data = preg_replace('/(\()([A-Z]*)(\))(\s)/', ',${1}${2}${3}|${4}', $raw_data);   // ends the string   adds a pipe after (IWX)   example (IWX)|
$raw_data = preg_replace('/(\s)(\d{4})(\,)([A-Z0-9]*)(\,)([1]\s)([E|N|S|W]*)/', '${1}${2}${3}${4}${5}${6} MILE ${7} OF ', $raw_data);  // Adds MILE after 1 mile in location
$raw_data = preg_replace('/(\s)(\d{4})(\,)([A-Z0-9]*)(\,)([2-9]|[1-9][0-9])(\s[E|N|S|W]*)/', '${1}${2}${3}${4}${5}${6} MILES${7} OF', $raw_data);   // Adds MILES after #  in location
$raw_data = preg_replace('/,,/', ', ,', $raw_data);  
$raw_data = preg_replace('/ & /', ' &amp; ', $raw_data);  
$raw_data = preg_replace('/\,UNK\,/', ',,', $raw_data);   
$t_rep = explode("|",$raw_data);
$t_reps = count($t_rep);
$total_reports = $t_reps -1;

// Get event data
preg_match_all("|TORNADO@(.*)WIND@|Uis", $raw_data, $tornado_data);
preg_match_all("|WIND@(.*)HAIL@|Uis", $raw_data, $wind_data);
preg_match_all("|HAIL@(.*)$|Uis", $raw_data, $hail_data);
$tor_reports=$h_reports=$w_reports=0;
//  TORNADO  //
if (preg_match_all("|TORNADO@(.*)WIND@|Uis", $raw_data, $tornado_data)>0) {
  $tornado_data = preg_replace('/(\s)(\d{4})(\,)([A-Z]*)(\,)/', '${1}${2}${3}TORNADO,${4}${5}', $tornado_data[0][0]);
  $tornado_data = preg_replace('/TORNADO@/', '', $tornado_data);
  $tornado_data = preg_replace('/WIND@/', '', $tornado_data);
  $tor_rep = explode("|",$tornado_data);
  $tor_reps = count($tor_rep);
  $tor_reports = $tor_reps -1;
  array_splice($tor_rep, -1);
}
//  WIND  //
if (preg_match_all("|WIND@(.*)HAIL@|Uis", $raw_data, $wind_data)>0) {
  $wind_data = preg_replace('/(\s)(\d{4})(\,)([A-Z]*)(\,)/', '${1}${2}${3}WIND,${4}${5}',$wind_data[0][0]);
  $wind_data = preg_replace('/(\s)(\d{4})(\,)([0-9]*)(\,)/', '${1}${2}${3}WIND,${4} MPH ${5}',$wind_data);
  $wind_data = preg_replace('/WIND@/', '', $wind_data);
  $wind_data = preg_replace('/HAIL@/', '', $wind_data);
  $w_rep = explode("|",$wind_data);
  $w_reps = count($w_rep);
  $w_reports = $w_reps -1;
  array_splice($w_rep, -1);
}
//  HAIL  //
if (preg_match_all("|HAIL@(.*)$|Uis", $raw_data, $hail_data)>0) {
  $hail_data = preg_replace('/(\s)(\d{4})(\,)([0-9]*)(\,)/', '${1}${2}${3}HAIL,${4}${5}', $hail_data[0][0]);
  $hail_data = preg_replace('/(\s)(\d{4})(\,)([A-Z]*)(\,)(\d{2})([\,])/', '${1}${2}${3}${4}${5}0.${6}"${7}', $hail_data);
  $hail_data = preg_replace('/(\s)(\d{4})(\,)([A-Z]*)(\,)(\d{1})(\d{2})([\,])/', '${1}${2}${3}${4}${5}${6}.${7}"${8}', $hail_data);
  $hail_data = preg_replace('/HAIL@/', '', $hail_data);
  $h_rep = explode("|",$hail_data);
  $h_reps = count($h_rep);
  $h_reports = $h_reps -1;
  array_splice($h_rep, -1);
}

// Gets total number of reports for each event
$thw_reports = $tor_reports+$h_reports+$w_reports;

$spc_map = "<img src=\"$storm_map\"  width=\"582\" height=\"408\" alt=\"Storm map\"/>";

// Combine data from all events
$thw_data = $tornado_data.$wind_data.$hail_data;

// Changes comma delimiter to a caret delimiter
$thw_data = preg_replace('/([0-2][0-9][0-9][0-9])(\,)(TORNADO|HAIL|WIND)(\,)([A-Z0-9\.\'\"\s]*)(\,)([A-Z.0-9-.\'\/\(\)\s]*)(\,)([A-Z0-9-\(\.\'\s]*)(\,)([A-Z0-9]*)(\,)([0-9]*.[0-9]*)(\,)(-[0-9]*.[0-9]*)(\,)(\s|[A-Z0-9*\.*\s].*\s)(\,)(\()([A-Z]*)(\))(\|)/', '${1}^${3}^${5}^${7}^${9}^${11}^${13}^${15}^${17}^${20}|', $thw_data);

// Arranges the time in posting order
 function recent_time($matches) {
    $utc_dif = "1200" + date("O") - 1; 
    if ($matches[2] >=0 && $matches[2] <= $utc_dif) {
	    return ($matches[1]."2").$matches[2].$matches[3];
       } 
    else {
       return ($matches[1]."1").$matches[2].$matches[3];
       } 
 }
 
$thw_data = preg_replace_callback("|(\s)(\d{4})(\^)|","recent_time" ,  $thw_data);  

// Makes Tornado bold
$thw_data = preg_replace('/TORNADO/', '<b>TORNADO</b>', $thw_data);

// Put everything in an array
$spc_array = explode("|",$thw_data);	

// Remove last array
array_splice($spc_array, -1);

// Sort the array
$sort_reports($spc_array,SORT_NUMERIC );	

?>

<table class="center" style="width:100%; text-align:center; margin: 0px 0px 16px 0px; background-color: #E9E9E9; border:1px solid #000;">
  <tr>
    <td style="text-align: center; font-size: 16px; font-family: Arial, Helvetica, sans-serif; background-color: #000066; color: #FFFFFF;"><b>SEVERE &nbsp;WEATHER &nbsp;REPORTS</b><br />
    Daily reports for the entire US issued by the <b>S</b>torm <b>P</b>rediction <b>C</b>enter*
	</td>
  </tr>
</table>
<table class="center" style="width:100%; background-color: #CCCCCC;">
  <tr>
    <td style="text-align: center; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
<a href=<?php 
if (isset ($inside_Leuven_template)  ) {
        echo '"'.$phpself.'&amp;spc_reports=reports&amp;yesterday'.$skiptopText.'"';}
else {  echo '"?yesterday"';}
?> title="Yesterday's Reports">Yesterday's Reports </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href=<?php 
if (isset ($inside_Leuven_template)  ) {
        echo '"'.$phpself.'&amp;spc_reports=reports&amp;today'.$skiptopText.'"';}
else {  echo '"?today"';}
?> title="Today's Reports"> Today's Reports</a></td>
  </tr>
  <tr>
    <td style="text-align: center;">
    <form method="get">
<table>
<tr><td style="text-align: center; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">Archived Reports 
</td>
<td style="text-align: center;">
<?php
if (isset ($inside_Leuven_template)  ) {
echo '<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" value="'.$p.'">'.PHP_EOL;
echo '<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="spc_reports" value="reports">'.PHP_EOL;	
}
?>
<select name="mo">
<option value=''>Select Month</option> 
<option value='01'>January</option>
<option value='02'>February</option>
<option value='03'>March</option>
<option value='04'>April</option>
<option value='05'>May</option>
<option value='06'>June</option>
<option value='07'>July</option>
<option value='08'>August</option>
<option value='09'>September</option>
<option value='10'>October</option>
<option value='11'>November</option>
<option value='12'>December</option>
</select></td><td style="text-align: left;">
<select name="dt">
<option value=''>Date</option> 
<option value='01'>01</option>
<option value='02'>02</option>
<option value='03'>03</option>
<option value='04'>04</option>
<option value='05'>05</option>
<option value='06'>06</option>
<option value='07'>07</option>
<option value='08'>08</option>
<option value='09'>09</option>
<option value='10'>10</option>
<option value='11'>11</option>
<option value='12'>12</option>
<option value='13'>13</option>
<option value='14'>14</option>
<option value='15'>15</option>
<option value='16'>16</option>
<option value='17'>17</option>
<option value='18'>18</option>
<option value='19'>19</option>
<option value='20'>20</option>
<option value='21'>21</option>
<option value='22'>22</option>
<option value='23'>23</option>
<option value='24'>24</option>
<option value='25'>25</option>
<option value='26'>26</option>
<option value='27'>27</option>
<option value='28'>28</option>
<option value='29'>29</option>
<option value='30'>30</option>
<option value='31'>31</option>
</select>
</td><td style="text-align: left;">
<select name="yr">
<option value=''>Year</option> 
<option value = "<?php echo date("y"); ?>"><?php echo date("Y"); ?></option>
<option value = "<?php echo date("y")-1; ?>"><?php echo date("Y")-1; ?></option>
<option value = "<?php echo date("y")-2; ?>"><?php echo date("Y")-2; ?></option>
<option value = "<?php echo date("y")-3; ?>"><?php echo date("Y")-3; ?></option>
<option value = "<?php echo date("y")-4; ?>"><?php echo date("Y")-4; ?></option>
<option value = "<?php echo date("y")-5; ?>"><?php echo date("Y")-5; ?></option>
<option value = "<?php echo date("y")-6; ?>"><?php echo date("Y")-6; ?></option>
</select>
</td><td style="text-align: left;">
<input name="submit" type="submit" value="Submit" />
</form>
</td></tr>
</table>
    </form></td>
</tr>
</table>

<table class="center" style="width:100%; background-color: #CCCCCC;">
  <tr>
    <td colspan="4" style="text-align:center;font-size: 16px; font-family: Arial, Helvetica, sans-serif; background-color: #CCCCCC;"><?php echo $error_message; echo $message;?></td>
  </tr>
  <tr>
    <th style="text-align: center; font-size: 14px; font-family: Arial, Helvetica, sans-serif; background-color: #CCCCCC;"><?php echo $report_listed . $total_reports?></th>
    <th style="text-align: left; font-size: 14px; color: #FF0000; font-family: Arial, Helvetica, sans-serif;"><?php echo $tor_reports;?>-Tornado </th>
    <th style="text-align: left; font-size: 14px; color: #0000FF; font-family: Arial, Helvetica, sans-serif;"><?php echo $w_reports;?>-Wind </th>
    <th style="text-align: left; font-size: 14px; color: #339900; font-family: Arial, Helvetica, sans-serif;"><?php echo $h_reports;?>-Hail &nbsp;&nbsp;&nbsp;&nbsp;</th>
  </tr>
  <tr>
    <th colspan="4" style="text-align: center; font-size: 14px; font-family: Arial, Helvetica, sans-serif; background-color: #CCCCCC;"><?php print $spc_map ?></th>
  </tr>
</table>
<p style="font-family: Arial, Helvetica, sans-serif; font-size: 10px;"> <?php if ($thw_reports >= 1) {print "Report times reflect $tz ";}?> </p>

<?php

foreach ($spc_array as $event_data) {
list($Time,$Event,$Msrmnt,$Loc,$Cnty,$State,$Lat,$Lon,$Comment,$Office) = explode("^",$event_data);

// Set color for event type
if ($Event == '<b>TORNADO</b>') { $reportcolor = $tornadocolorbkg; $reportcolorA = $tornadocolorfont;}
if ($Event == 'WIND') { $reportcolor = $windcolorbkg; $reportcolorA = $windcolorfont;}
if ($Event == 'HAIL') { $reportcolor = $hailcolorbkg; $reportcolorA = $hailcolorfont; }

// Convert times am, pm ,noon, midnight
$Time = preg_replace('/(\d{1})(\d{4})/', '${2} ', $Time);

// Format the time	  
preg_match_all("|(\d{4})|", $Time, $hour_data);

$time = $hour_data[0][0];

 if ($time >= 0000 && $time <= 1159) {$meridiem = " am";}
 if ($time >= 1200 && $time <= 2359 ) {$meridiem = " pm";}
 if ($time >= 00 && $time <= 59) {$time = $time + 1200;}    // if the hour is 00, it adds 12
 
$time = preg_replace('/([0])(\d{3})/', '${2}', $time);
 if ($time >= 1300 && $time <= 2400) {$time = $time - 1200;}    // if the hour is after 1pm, changes to 12 hour clock time
$time = preg_replace("/(\d*)(\d{2})/", '${1}:${2}', $time);  //  adds : to the time
$time = $time. $meridiem;
$time = preg_replace("/12:00 am/", 'MIDNIGHT', $time);
$time = preg_replace('/12:00 pm/', 'NOON', $time);

// The report table	
print '<div style="width:100%; margin: 4px 0px 4px 0px; border: 1px solid #555">'."\n";
print "<table class = \"center\" style = \"width:100%;\">\n";
print "<tr>\n";
print "<td style=\"text-align:left;font-family: Arial, Helvetica, sans-serif; font-size: 12px; padding: 7px 6px 0px 6px; color: $reportcolorA; background-color: $reportcolor;\">$time &nbsp;&nbsp;$State &nbsp;- &nbsp;&nbsp;$Cnty COUNTY - $Loc</td>\n";
print "</tr>\n";
print "<tr>\n";
print "<td style=\"text-align:left;font-size: 12px; font-family: Arial, Helvetica, sans-serif; padding: 4px 6px 7px 6px; color: $reportcolorA; background-color: $reportcolor;\">$Event - $Msrmnt $Comment</td>\n";
print "</tr>\n";
print "</table>\n";
print "</div>\n";
}
?>
<div style="width:98%; border: none; text-align:left; margin: 10px auto; font-size:10px;"> 
* Time of reports. The Storm Prediction Center 24 hour daily reports are generated between 1200 UTC and 1159 UTC for a common time reference across the US. 
The actual &quot;day&quot; starts at noon UTC and ends at one minute before noon UTC the next day. 
For the <?php echo date("T")?> timezone, the daily reports start at <?php echo $cut_time;?> and ends at <?php echo $cutoff_time/100-1;?>:59am the following day. 
Reports sorted above reflect <?php echo date("T");?> and start with the most recent. All of the reports are considered preliminary and should be treated as such.
</div>
