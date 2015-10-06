<?php
ini_set('display_errors', 'On');  error_reporting(E_ALL);	
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
$FIND['topfolder']	= $topfolderOld = 'weather28/'; // weather template is default installed here
#
echo '<pre>'.PHP_EOL;
echo 'startHere.php (v2.7): This small program test your web server settings if they prohibit installing the Leuven-template.
<br />You only need to run this program in step3 of the install procedure.
<br />Do not run this program after adapting the settings files, only when asked by support.<br />'.PHP_EOL;
$fatalErrors    = false;
$errorKind	= 'FATAL';
#
$checkWhat 	= '$_SERVER["DOCUMENT_ROOT"]';
echo 'step 1 checking: '.$checkWhat.': result = ';
if (!isset ($_SERVER['DOCUMENT_ROOT']) ) {
	echo 'ERROR 1 '.$checkWhat.' is not defined, '.$errorKind.PHP_EOL;
	$fatalErrors	= true;
} else { echo ' OK'.PHP_EOL;}
#
$checkWhat 	= 'CURL support';
echo 'step 2 checking: '.$checkWhat.': result = ';
if (!function_exists ('curl_init') ) {
	echo 'ERROR 2 '.$checkWhat.' is not supported, '.$errorKind.PHP_EOL;
	$fatalErrors	= true;
} else { echo ' OK'.PHP_EOL;}
#
$reason		= 'open_basedir restriction in effect';
$checkWhat 	= 'file_exists';
echo 'step 3.1 checking: '.$checkWhat.': result = ';
if (!file_exists('startHere.php')) {
	echo 'ERROR 3.1 '.$checkWhat.' is not supported, ?'.$reason.$errorKind.PHP_EOL;
	$fatalErrors	= true;
} else { echo ' OK'.PHP_EOL;}
$checkWhat 	= 'chdir';
echo 'step 3.2 checking: '.$checkWhat.': result = ';

$errorKind	= 'WARNING';
$ret	=  chdir ('cache');
if ($ret == false) {
	echo 'ERROR 3.2 '.$checkWhat.' is not supported, ?'.$reason.$errorKind.PHP_EOL;
} else {echo ' OK'.PHP_EOL; 
        chdir ('../');
}
$errorKind	= 'FATAL';
$checkWhat 	= 'file_put_contents';
$string         =  'step 3.3 checking: '.$checkWhat.': result = ';
echo $string;
$file           = './cache/test.txt';
$ret	= file_put_contents ($file, $string);
if ($ret == false) {
        echo 'ERROR 3.2 '.$checkWhat.' is not supported, ?'.$reason.'  or cache folder not writable'.$errorKind.PHP_EOL;
        $fatalErrors	= true; 
} else { echo ' OK'.PHP_EOL;
        $errorKind	= 'WARNING';
        $checkWhat 	= 'chmod';
        $string =  'step 3.4 checking: '.$checkWhat.': result = ';
        echo $string;
        $ret	= chmod ($file, '755');
        if ($ret == false) {
                echo 'ERROR 3.2 '.$checkWhat.' is not supported, ?'.$reason.'  or ache folder not writable'.$errorKind.PHP_EOL;
        } else { echo ' OK'.PHP_EOL;}
        $errorKind	= 'WARNING';
        $checkWhat 	= 'unlink';
        $string =  'step 3.5 checking: '.$checkWhat.': result = ';
        echo $string;
        $ret	= unlink ($file);
        if ($ret == false) {
                echo 'ERROR 3.2 '.$checkWhat.' is not supported, ?'.$reason.'  or ache folder not writable'.$errorKind.PHP_EOL;
        } else { echo ' OK'.PHP_EOL;}    
}	
$errorKind	= 'FATAL';
$length		= 100;
$weatherApiUrl	= 'http://www.weerstation-leuven.be/yowindow.xml';

$checkWhat 	= 'load xml from <a href="'.$weatherApiUrl.'" target="_blank">test site </a>';
echo 'step 4.1 checking: '.$checkWhat.': result = ';
$ch = curl_init();
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_URL, $weatherApiUrl);
curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
$rawData = curl_exec ($ch);
#echo PHP_EOL.$rawData.PHP_EOL;
curl_close ($ch);
if (!$rawData || strlen($rawData) < 50) {
	echo 'ERROR 4.1 '.$checkWhat.' failed, no data or to few data chars'.PHP_EOL;
	$fatalErrors	= true; 
} else {
	$string = substr($rawData,0,7);
	if ($string <> '<!-- My') {
		echo 'ERROR 4.1 '.$checkWhat.' failed, invalid data'.PHP_EOL;
		$fatalErrors	= true; 
	} else {echo ' OK ';
                $from 	= array('<','>');
                $to		= array('&lt;','&gt;');
                $string = str_replace ($from, $to, $rawData);
                echo '<br />step 4.1 first '.$length.' data chars of retrieved xml = <br />'.substr($string,0,$length).'<br />'.PHP_EOL;
	}
}
#
$weatherApiUrl	= 'http://weather.noaa.gov/pub/data/observations/metar/stations/EBBR.TXT';
$checkWhat 	= 'load xml from <a href="'.$weatherApiUrl.'" target="_blank">metar site </a>';
echo 'step 4.2 checking: '.$checkWhat.': result = ';
$ch = curl_init();
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_URL, $weatherApiUrl);
curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
$rawData = curl_exec ($ch);
#echo PHP_EOL.$rawData.PHP_EOL;
curl_close ($ch);
if (!$rawData || strlen($rawData) < 50) {
	echo 'ERROR 4.2 '.$checkWhat.' failed, no data or to few data chars'.PHP_EOL;
	$fatalErrors	= true; 
} else { 
	echo ' OK, ';
	$from 	= array('<','>');
	$to		= array('&lt;','&gt;');
	$string = str_replace ($from, $to, $rawData);
	echo '<br />step 4.2 first '.$length.' data chars of retrieved xml = <br />'.substr($string,0,$length).''.PHP_EOL;
}
#
$checkWhat = 'json support gauges earthquakes';
echo 'step 4.3 checking: '.$checkWhat.': result = ';
if (function_exists('json_encode') ) {
	echo 'OK'; 
} else {
 	echo 'error. no json support in PHP found';
 	$fatalErrors = true;
} 
echo PHP_EOL;
#
if ($fatalErrors == true) {
	echo PHP_EOL.'There are fatal errors<br /><strong>copy the output on this screen to an email and sent it to wvdkuil@gmail.com</strong>'.PHP_EOL;
	return;
} 
$checkWhat = 'path to installation folder';
echo 'step 5 checking: '.$checkWhat.': result = ';

$config			= false;
#
$findFolder = true;
if ($findFolder) {
# ---------------alternative --------- assemble the folder structure of our program -----------
	$docRoot 			= $_SERVER['DOCUMENT_ROOT'].'/';
	$docRoot 			= str_replace ('//','/',$docRoot);
	$string 			= $_SERVER['SCRIPT_FILENAME'];
	$folders			= str_replace($docRoot , '', $string);
	$folders			= str_replace('\\' , '/', $folders);
	$arr 				= explode ('/', $folders);
	$count				= count($arr);
	$n					= $count - 1;
	switch ($count) {
		case 2:
			$FIND['topfolder']	= $arr['0'].'/';
			$FIND['my-folder']	= $arr['0'].'/';
			break;
		case 1:
			$FIND['topfolder']	= './';
			$FIND['my-folder']	= './';	
			echo PHP_EOL.'WARNING:  non-standard folder settings, please check!'.PHP_EOL;
			break;
		default:
	#		$count - 2 == weather folder if executed from weather2
	#		$count - 3 == weather folder if executed from __config
			if ($config == true) {$end = $count - 3;} else {$end = $count - 2;}
			$FIND['topfolder']	= '';
			for ($i = 0; $i <= ($end); $i++) {		// assemble the topfolder 
				$FIND['topfolder'] .= $arr[$i].'/';
			}
			$end++;
			$FIND['my-folder']	= $arr[$end].'/';		
	}
	if ($topfolderOld	<> $FIND['topfolder']) {
		echo PHP_EOL.'WARNING - Change wsUserSettings.php:  $SITE["topfolder"]  from : '.$topfolderOld.' to: '.$FIND['topfolder'].' -->'.PHP_EOL;
	}	else {
		echo 'Folder settings OK'.PHP_EOL;
	}
}
