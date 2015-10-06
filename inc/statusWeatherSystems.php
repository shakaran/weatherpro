<?php
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
$pageName	= 'statusWeatherSystems.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2014-12-21';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-19 release version
# 3.01 2014-12-21 added generic realtime name support (VWS
#---------------------------------------------------------------------------
$rowColor = 1;
?> 
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('Current status of the weather systems used in the operation of this website');?></h3>
<?php 
#--------------------------------  program up time   -------------------------------------
if (isset($ws['wsUptime']) && $ws['wsUptime'] <> '0' && $ws['wsUptime'] <> '' && $ws['wsUptime'] <> '--') {
        $txtYears	= langtransstr('years');
        $txtMonths	= langtransstr('months');
	$txtDays	= langtransstr('days');
	$txtHours	= langtransstr('hours');
	$txtMinutes	= langtransstr('minutes');
	$txtSeconds	= langtransstr('seconds');
	
	$from 		= array ('Years'  , 'Months'  , 'Days',   'Hours',   'Minutes',   'Seconds',   'years',   'months',   'days',  'hours',   'minutes',   'seconds');
	$to 		= array ($txtYears, $txtMonths, $txtDays, $txtHours, $txtMinutes, $txtSeconds, $txtYears, $txtMonths, $txtDays, $txtHours, $txtMinutes, $txtSeconds);
	switch ($SITE['WXsoftware']) {
		case 'MH':
			$uptime_in_sec  = $ws['wsUptime'];
			$stdDate 		= substr($ws['actTime'],0,8).'T'.substr($ws['actTime'],8,6);   // actual date of weather info
			$date 			= date_create($stdDate);								// create php date
			$dateWeatherData = $date;
			date_modify($date, "- $uptime_in_sec seconds");				// date system/software was started
			$string 		= date_format($date, $SITE['timeFormat']);			// print date/time	
	#
			$divider		= 24*60*60;	// days
			$days 			= floor($uptime_in_sec / $divider);
			$uptime_in_sec 	= $uptime_in_sec - ($days * $divider);
			$divider		= 60*60;	// hours
			$hours 			= floor($uptime_in_sec / $divider);	
			$uptime_in_sec 	= $uptime_in_sec - ($hours * $divider);
			$divider		= 60;	// minutes
			$minutes 		= floor($uptime_in_sec / $divider);	
			$seconds 		= $uptime_in_sec - ($minutes * $divider);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system is up for').'<b>'.
				' '.$days.	' '.langtransstr('days').	
				' '.$hours.	' '.langtransstr('hours').
				' '.$minutes.	' '.langtransstr('minutes').
				' '.$seconds.	' '.langtransstr('seconds').'</b>'.
				'</p>
				<p style="width: 98%; margin: 10px auto;">'.langtransstr("It was last started")." <b>$string</b>.".'</p>';
		break;
#
		case 'MB':
			$uptime_in_sec  = $ws['wsUptime'];
			$stdDate 		= substr($ws['actTime'],0,8).'T'.substr($ws['actTime'],8,6);   // actual date of weather info
			$date 			= date_create($stdDate);								// create php date
			$dateWeatherData = $date;
			date_modify($date, "- $uptime_in_sec seconds");				// date system/software was started
			$string 		= date_format($date, $SITE['timeFormat']);			// print date/time	
	#
			$divider		= 24*60*60;	// days
			$days 			= floor($uptime_in_sec / $divider);
			$uptime_in_sec 	= $uptime_in_sec - ($days * $divider);
			$divider		= 60*60;	// hours
			$hours 			= floor($uptime_in_sec / $divider);	
			$uptime_in_sec 	= $uptime_in_sec - ($hours * $divider);
			$divider		= 60;	// minutes
			$minutes 		= floor($uptime_in_sec / $divider);	
			$seconds 		= $uptime_in_sec - ($minutes * $divider);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system is up for').'<b>'.
				' '.$days.	' '.langtransstr('days').	
				' '.$hours.	' '.langtransstr('hours').
				' '.$minutes.	' '.langtransstr('minutes').
				' '.$seconds.	' '.langtransstr('seconds').'</b>'.
				'</p>
				<p style="width: 98%; margin: 10px auto;">'.langtransstr("It was last started")." <b>$string</b>.".'</p>';
		break;

                case 'CW':
			$string		= str_replace($from, $to, $ws['wsUptime']);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system at ').'<b> '.$string.'</b></p>'.PHP_EOL;		
		break;
/*		case 'WD':
			$string		= str_replace($from, $to, $ws['wsUptime']);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system is up for').'<b> '.$string.'</b></p>'.PHP_EOL;		
		break;
		case 'CU':
			$string		= str_replace($from, $to, $ws['wsUptime']);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system is up for').'<b> '.$string.'</b></p>'.PHP_EOL;				
		break;
		case 'WV':
			$string		= str_replace($from, $to, $ws['wsUptime']);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system is up for').'<b> '.$string.'</b></p>'.PHP_EOL;				
		break;
*/			
		default:
			$string		= str_replace($from, $to, $ws['wsUptime']);
			echo '<p style="width: 98%; margin: 10px auto;">'.PHP_EOL;
			echo langtransstr('Station system is up for').'<b> '.$string.'</b></p>'.PHP_EOL;				
	} 
}

#--------------------------------  weather program information ---------------------------
echo '<p style="width: 98%; margin: 10px auto;">'.langtransstr('This station uses').' <b>'. $SITE['WXsoftwareLongName']; 
if(isset($ws['wsVersion']) && $ws['wsVersion'] <> '' && $ws['wsVersion'] <> '--') {
	echo ' ('.$ws['wsVersion'].')';
	}
echo '</b> '.langtransstr('software').' '.langtransstr('for weather conditions reporting').'</p>'.PHP_EOL; 

#--------------------------------  hardware information ----------------------------------
if (!isset($ws['wsHardware']) ){  // hardware info as given by weather program
	if (isset($SITE['pcTxt']) ) {$ws['wsHardware'] = $SITE['pcTxt'];}
}
if ($ws['wsHardware'] == '' || $ws['wsHardware'] == 'unknown' || $ws['wsHardware'] == '--') {
	if (isset($SITE['pcTxt']) ) {$ws['wsHardware'] = $SITE['pcTxt'];}
}
if(isset($ws['wsHardware']) && $ws['wsHardware'] <> '' && $ws['wsHardware'] <> 'unknown' && $ws['wsHardware'] <> '--' && $SITE['WXsoftware'] <> 'DW') {
	echo '<p style="width: 98%; margin: 10px auto;">'.langtransstr('This software runs on a').' <b>'. $ws['wsHardware']  .'</b> '. langtransstr('computer').'</p>'.PHP_EOL; 
}
echo '
<table class="genericTable" style="width: 100%; margin: 0px auto;">
<tr class="row-dark">
<th>&nbsp;</th>
<th style="text-align: left;">'.langtransstr('Component').'</th>
<th style="text-align: left;">'.langtransstr('Status').'</th>
<th style="text-align: right;">'.langtransstr('Age').'<br />'.langtransstr('h:m:s').'</th>
<th style="text-align: right;">'.langtransstr('Latest update time as of').'&nbsp;<br/>'.date($SITE['timeFormat'],time()).'&nbsp;</th>
</tr>'.PHP_EOL;
#
$wpCode	=  $SITE['WXsoftware'];
$wpName	=  ''; # $SITE['WXsoftwareLongName']." ";
#
# check WD live file first
#
if (isset ($SITE['cltrPage'])) {
	if ($SITE['cltrPage'] === 'yes' || $SITE['cltrPage'] === true) {
		do_check($wpName."WD live",$SITE['clientrawDir'].'clientraw.txt',$SITE['wsRealTime'],'file');
	}
}
#
# check steelseries
#
if (isset ($SITE['steelFile']) && 	$SITE['steelFile'] <> '') {
	do_check($wpName."Steelseries gauges",$SITE['steelFile'],$SITE['wsRealTime'],'file');	    
}
#
# check realtime files
#
if (isset ($SITE['realtime_file']) ) {
 	do_check($wpName."Realtime file",$SITE['realtime_file'],$SITE['wsRealTime'],'file');	    
}
#
# check weatherfiles all tags or today tags
#
if ($wpCode <> 'DW') {
        do_check($wpName."Weather data files",$SITE['wsTags'] ,$SITE['wsNormTime'],'file');
}
#
# check weatherfiles with yesterday tags
#
if (isset ($SITE['wsYTags']) && $SITE['wsYTags'] <> '' && $SITE['wsYTags'] <> 'no') {
	do_check($wpName."Weather data for yesterday",$SITE['wsYTags'] ,24*60*60,'file');
}
if (isset ($SITE['ydayTags']) && $SITE['ydayTags'] <> '' && $SITE['ydayTags'] <> 'no') {
	do_check($wpName."Weather data for yesterday",$SITE['ydayTags'] ,24*60*60,'file');
}
#
# check actual time of tags
#
$stringtime	= string_date($ws['actTime'],'Y-m-j G:i');
$unixtime 	= strtotime($stringtime);
do_check($wpName."Weather data time",$unixtime,$SITE['wsDataTime'],'applic');
#
$graphsArr = array ('CU','MH','WC','WD','WL');
if (in_array($wpCode,$graphsArr) ){
	switch ($wpCode) {
	case 'WD':
		do_check($wpName."Graphs",$SITE['graphImageDir'].'dirplot.gif',$SITE['wsFtpTime'],'file');  // windrose.png
		break;
	case 'MH':
		do_check($wpName."Graphs",$SITE['graphImageDir'].'windrose.png',$SITE['wsFtpTime'],'file');  // windrose.png
		break;
	case 'CU':
		do_check($wpName."Graphs",$SITE['graphImageDir'].'windrose.png',$SITE['wsFtpTime'],'file');  // windrose.png
		break;
	case 'WL':
		do_check($wpName."Graphs",$SITE['graphImageDir'].'OutsideTempHistory.gif',$SITE['wsFtpTime'],'file');  // windrose.png
		break;
	case 'WC':
		do_check($wpName."Graphs",$SITE['graphImageDir'].'temperature1.jpg',$SITE['wsFtpTime'],'file');  // windrose.png
		break;
	}  // eo switch wpcode
}  // eo in array

#------------------------------ WXSIM check    -------------------------------------------		
if(isset($SITE['wxsimPage']) ) {
	$file	= $SITE['wxsimData'].'plaintext.txt';
	if (file_exists($file) ) {
		do_check(langtransstr("WXSIM forecast"),$file,6*60*60 + 2*60,'file');	// note: '6*60*60 + 2*60' is 6:02:00 hms	
	}
}
#-------------------------METEOHUB data usage --------------------------------------------
if ($wpCode == 'MH') {// only needed for MH
	if (isset ($ws['wsDataUse']) ) { do_check_data ($ws['wsDataUse'],'Data area');}
	if (isset ($ws['wsRootUse']) ) { do_check_data ($ws['wsRootUse'],'Root area');}
}
#---------------------------------------------------------------------------------------------------
function do_check_data ($percent,$text) {
	global $rowColor;
	if ($rowColor == 0) { $rowColor = 1; $class = 'class="row-dark"';} else {$rowColor = 0; $class = 'class="row-light"';}
	$use	=  $percent*100;
	if ( $use < 80) {
		$stat = '<span style="color: green"><b>Acceptable</b></span>';
	} else {
		$stat = '<span style="color: red"><b>To high, take action</b></span>';
	}
	$percentText	= $use.'%';
	echo '    <tr '.$class.'>
	<td style="text-align: left;">&nbsp;Disk space usage</td>
	<td style="text-align: left;">'.$text.'</td>
	<td style="text-align: left;">'	.$stat.'</td>
	<td style="text-align: right;">'.$percentText.'</td>
	<td style="text-align: right;">&nbsp;</td>
	</tr>'.PHP_EOL;	
}	
/*
if(file_exists('nexstorm.jpg')) {
        do_check(langtransstr("Nexstorm Lightning map"),'nexstorm.jpg',10*60+15,'file');
}
#---------------------------------------------------------------------------------------------------
if(file_exists('TRACReport.txt')) {
        do_check(langtransstr("Nexstorm TRACreport"),'TRACReport.txt',10*60+15,'file');
}
#---------------------------------------------------------------------------------------------------
if(file_exists('nexstorm_arc.dat')) {
        do_check(langtransstr("Nexstorm Data file"),'nexstorm_arc.dat',10*60+15,'file');		
}
#---------------------------------------------------------------------------------------------------	
*/	
?>
</table>
</div>
<?php
#-----------------------------------------------------------------------------------------
# function do_check		check age of file or app and print results
#-----------------------------------------------------------------------------------------
function do_check($title, $fileOrAppTime,$maxFileSecs,$type='file') {
//	NOTE: $fileOrAppTime contains either a filename to check or a time
	global $rowColor, $wsDebug, $SITE;  								// for alternating dark - light lines in table
	if ($wsDebug) {
		echo '<!-- $title = '.$title.' $fileOrAppTime = '.$fileOrAppTime.' $maxFileSecs = '.$maxFileSecs.' $type = '.$type.' -->'.PHP_EOL;
	}
	if(preg_match('/file/i',$type)) {
	   $cur_status 		= do_check_file($fileOrAppTime,$maxFileSecs);
	} else {
	   $cur_status 		= do_check_applic($fileOrAppTime,$maxFileSecs);
	   $fileOrAppTime	= date( $SITE['timeFormat'],$fileOrAppTime);
	}
	list($stat,$age,$data) = $cur_status;
	if ($rowColor == 0) { $rowColor = 1; $class = 'class="row-dark"';} else {$rowColor = 0; $class = 'class="row-light"';}
	echo '    <tr '.$class.'>
	<td style="text-align: left;">&nbsp;'	.$title.'</td>
	<td style="text-align: left;">'	.$fileOrAppTime.'</td>
	<td style="text-align: left;">'	.$stat.'</td>
	<td style="text-align: right;">'.$age.'</td>
	<td style="text-align: right;">'.$data.'</td>
	</tr>'.PHP_EOL;	
} // eof do_check
#-----------------------------------------------------------------------------------------
#	function do_check_file    	check time on a file for last update
#-----------------------------------------------------------------------------------------
function do_check_file($chkfile,$maxFileSecs) {
	global $SITE;
	$timeFormat = $SITE['timeFormat']; 
	$now 		= time();
	if (file_exists($chkfile)) {
		$age 		= $now - filemtime($chkfile);
		$updateTime = date($timeFormat,filemtime($chkfile));
	} else {
		$age 		= 'unknown';
		$updateTime = 'file not found';
	}
	$status = '';
	$age 	= sec2hms($age);
	if (file_exists($chkfile) and (filemtime($chkfile) + $maxFileSecs > $now) ) {     // stale file
		$status = '<span style="color: green"><b>Current</b></span>';
	} else {
		$status 	= '<span style="color: red"><b>NOT Current</b></span>';
		$updateTime	= sec2hms($maxFileSecs) .'&nbsp;<br/><b>'.$updateTime.'</b>&nbsp;';
	}
	return array ($status,$age,$updateTime);
}  // eof do_check_file
#---------------------------------------------------------------------------------------------------
#	function do_check_applic 	check time on an application returned update time
#---------------------------------------------------------------------------------------------------
function do_check_applic($applTime,$maxFileSecs) {
	global $SITE;
	$timeFormat = $SITE['timeFormat']; 
	$now 		= time();
	$age 		= $now - $applTime;
	$updateTime = date($timeFormat,$applTime);
	$status 	= '';
	$age = sec2hms($age);
	if ($applTime + $maxFileSecs > $now ) {     // stale file
		$status = '<span style="color: green"><b>Current</b></span>'; 
	} else {
		$status = '<span style="color: red"><b>NOT Current</b></span>';
		$updateTime = sec2hms($maxFileSecs) .'&nbsp;<br/><b>'.$updateTime.'</b>&nbsp;';
	}
	return array ($status,$age,$updateTime);
}	// eof do_check_applic
#---------------------------------------------------------------------------------------------------
#	function sec2hms  		convert nr of seconds to HH:MM:SS
#---------------------------------------------------------------------------------------------------
function sec2hms ($sec, $padHours = false)  {
	if (! is_numeric($sec)) { return($sec); }
	$hours		= intval(intval($sec) / 3600); 
	$minutes	= intval(($sec / 60) % 60); 
	$seconds	= intval($sec % 60); 
	$hms		= $hours.':'.
				  str_pad($minutes, 2, "0", STR_PAD_LEFT). ':'.
				  str_pad($seconds, 2, "0", STR_PAD_LEFT);
	return $hms;
} // eof function sec2hms 
?>