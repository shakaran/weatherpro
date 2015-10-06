<div style="position: relative; margin: inherit;">
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
$pageName	= 'incClientraw4.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-22';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# 3.00 2014-09-22 release version
# -------------------------------------------------------------------------------------------------
#  array with icon description and keys. Array nrs are used in clientraw		
$iconsWD = array (
0=> array ('iconKey'=> 'unknown', 'iconDescription' => 'unknown'),
1=> array ('iconKey'=> 'sunny', 'iconDescription' => 'Sunny'),
2=> array ('iconKey'=> 'clearnight', 'iconDescription' => 'Clear Night'),
3=> array ('iconKey'=> 'cloudy', 'iconDescription' => 'Cloudy'),
4=> array ('iconKey'=> 'cloudy', 'iconDescription' => 'Cloudy'),
5=> array ('iconKey'=> 'cloudynight', 'iconDescription' => 'Cloudy Night'),
6=> array ('iconKey'=> 'dryclear', 'iconDescription' => 'Dry Clear'),
7=> array ('iconKey'=> 'fog', 'iconDescription' => 'Fog'),
8=> array ('iconKey'=> 'hazy', 'iconDescription' => 'Hazy'),
9=> array ('iconKey'=> 'heavyrain', 'iconDescription' => 'Heavy Rain'),
10=> array ('iconKey'=> 'mainlyfine', 'iconDescription' => 'Mainly Fine'),
11=> array ('iconKey'=> 'mist', 'iconDescription' => 'Misty'),
12=> array ('iconKey'=> 'nightfog', 'iconDescription' => 'Night Fog'),
13=> array ('iconKey'=> 'nightheavyrain', 'iconDescription' => 'Night Heavy Rain'),
14=> array ('iconKey'=> 'nightovercast', 'iconDescription' => 'Night Overcast'),
15=> array ('iconKey'=> 'nightrain', 'iconDescription' => 'Night Rain'),
16=> array ('iconKey'=> 'nightshowers', 'iconDescription' => 'Night Showers'),
17=> array ('iconKey'=> 'nightsnow', 'iconDescription' => 'Night Snow'),
18=> array ('iconKey'=> 'nightthunder', 'iconDescription' => 'Night Thunder'),
19=> array ('iconKey'=> 'overcast', 'iconDescription' => 'Overcast'),
20=> array ('iconKey'=> 'partlycloudy', 'iconDescription' => 'Partly Cloudy'),
21=> array ('iconKey'=> 'rain', 'iconDescription' => 'Rain'),
22=> array ('iconKey'=> 'hardrain', 'iconDescription' => 'Hard Rain'),
23=> array ('iconKey'=> 'showers', 'iconDescription' => 'Showers'),
24=> array ('iconKey'=> 'sleet', 'iconDescription' => 'Sleet'),
25=> array ('iconKey'=> 'sleetshowers', 'iconDescription' => 'Sleet Showers'),
26=> array ('iconKey'=> 'snowing', 'iconDescription' => 'Snowing'),
27=> array ('iconKey'=> 'snowmelt', 'iconDescription' => 'Snow Melt'),
28=> array ('iconKey'=> 'snowshowers', 'iconDescription' => 'Snow Showers'),
29=> array ('iconKey'=> 'Sunny', 'iconDescription' => 'Sunny'),
30=> array ('iconKey'=> 'thundershowers', 'iconDescription' => 'Thunder Showers'),
31=> array ('iconKey'=> 'thundershowers', 'iconDescription' => 'Thunder Showers'),
32=> array ('iconKey'=> 'thunderstorms', 'iconDescription' => 'Thunderstorms'),
33=> array ('iconKey'=> 'tornadowarning', 'iconDescription' => 'Tornado Warning'),
34=> array ('iconKey'=> 'windy', 'iconDescription' => 'Windy'),
35=> array ('iconKey'=> 'stoppedraining', 'iconDescription' => 'Stopped Raining'),
36=> array ('iconKey'=> 'windyrain', 'iconDescription' => 'Windy Rain'),
);
#--------------------------------------------------------------------------------------------------		
#  logic control of this program
# kind = file description  - file = exact name of the file - post = name of client file - arr = name of array with description of each data field in file
$controlArr = array( 
'a' => array ('kind' => 'clientraw', 	  	'file' => 'clientraw.txt',		'post' => '', 'arr' => 'ArrClientraw'),
'b' => array ('kind' => 'clientrawextra',	'file' => 'clientrawextra.txt',	'post' => '', 'arr' => 'ArrClientrawExtra'),
'c' => array ('kind' => 'clientrawdaily',	'file' => 'clientrawdaily.txt',	'post' => '', 'arr' => 'ArrClientrawDaily'),
'd' => array ('kind' => 'clientrawhour',	'file' => 'clientrawhour.txt',	'post' => '', 'arr' => 'ArrClientrawHour')
);
#--------------------------------------------------------------------------------------------------		
#  default choice for clientraw.txt when program is started
$choice = 'a';
#--------------------------------------------------------------------------------------------------		
#  or use choice of the file the user indicated by pressing on the <a> choice list
if (isset($_GET['raw'])) {$choice=$_GET['raw'];}
if ( ($choice < 'a') || ($choice > 'd') ) {$choice = 'a'; unset($_GET['raw']);}
#  generate the filename to be used as input
//$fileName		= $path.$controlArr[$choice]['file'];
$path =  $SITE['clientrawDir'];
#--------------------------------------------------------------------------------------------------		
#  or use the filename the user entered 
if (isset($_POST['path'])) {   // pogram is entered by post (a button was pressed)
#--------------------------------------------------------------------------------------------------		
#  	first clean all userinput fields, save the fields and error info so we can show it to the user
	$savedUserInput =  checkUrl($_POST['path']);
	echo "<!-- post =  ".$_POST['path']."   / savedUserInput =  ".$savedUserInput.' -->'.PHP_EOL;
#  check which button was pressed and save it as the users choice
//	if (isset($_POST['submit'])) {}
#  pick the user supplied filename to be used as input
#  		only when it's length suggests it is a filename
#  		this happens when user enters a filename on one line but hits button on another line
	if ( (strlen($savedUserInput) > 15 ) && (substr($savedUserInput,0,5) <> "ERROR") ) {
		$path = $savedUserInput;
	} else {
# otherwise take the own website file for this choice
		$path =  $SITE['clientrawDir'];
		}
} // eo if POST
#--------------------------------------------------------------------------------------------------		
#  set texts on the page to desired language
if(file_exists('cltraw/incClientrawTxt-'.$lang.'.php') ) {
	include_once('incClientrawTxt-'.$lang.'.php');	// put also the text in the table in the correct language
} else {
	echo "<!--  file incClientrawTxt-en.php loaded instead of incClientrawTxt-$lang.php -->".PHP_EOL;
	include_once('incClientrawTxt-en.php');
}  // eo ifelse language
// -----------------------------------------------------
?>

<script type="text/javascript" src="javaScripts/sorttable.js"></script>
<script type="text/javascript" src="javaScripts/floating.js"></script>
<div id="floatdiv" style="position:absolute;  width: 85px; height: 91px; padding:4px; background: yellow; border:4px solid #FFF;  z-index:100; -moz-border-radius: 5px;    -webkit-border-radius: 5px;">  
<a href="#header" title="Goto Top of Page">
<img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;" /></a><br />
<a href="#<?php echo $controlArr['a']['kind'] ?>"><?php echo $controlArr['a']['kind']  ?></a><br />
	<a href="#<?php echo $controlArr['b']['kind'] ?>"><?php echo $controlArr['b']['kind']  ?></a><br />
	<a href="#<?php echo $controlArr['c']['kind'] ?>"><?php echo $controlArr['c']['kind']  ?></a><br />
	<a href="#<?php echo $controlArr['d']['kind'] ?>"><?php echo $controlArr['d']['kind']  ?></a><br />   
</div>
<script type="text/javascript">
	floatingMenu.add('floatdiv',  
	{	targetBottom: 10,
		prohibitXMovement: true,
	snap: true
});  
</script>
<div class="blockDiv">
<h3 class="blockHead"><?php langtrans('This Station Clientraw'); ?></h3>
<div style="width: 98%; margin: 0 auto;"> 
<?php echo $lientrawTxt[0]; 

?>

<form action="<?php echo $phpself; ?>" method="post">
<input type="text" name="path" value="<?php echo $path ?>" id="raw_a" size="80" maxlength="120"/><input type="submit" name="submit" value="submit" />
</form>
<?php // 4 keer
foreach ($controlArr as $key =>  $notused) { 
	$choice = $key;
?>
<div id="<?php echo $controlArr[$choice]['kind']; ?>">
<br />
<?php
//if ($choice <> 'a') {
//	echo '<a href="#header">'.langtransstr("Go back to the top of this page").'</a><br />'.langtransstr("Or go directly to:").' <br />';
//	
//} else {
//	echo langtransstr("Go directly to:").' <br />';
//}
?>

<?php  // do all fileprocessing
$error			= '';
$fileName		= $path.$controlArr[$choice]['file'];
$file 			= file_get_contents($fileName);
if (substr($file,0,5)<>'12345' ) {
	$error 		= langtransstr('not a clientraw file / '); 
	unset($file);
	$file		= false;
	} 
if (!$file ) {
	$error 		.= langtransstr("file not available, switched to local file".'<br />');
	$fileName 	= $SITE['clientrawDir'].$controlArr[$choice]['file'];
	$file 		= file_get_contents($fileName);
	}
$includeName	= 'ws'.$controlArr[$choice]['arr'].'.arr';
include ($includeName);  // array with descriptions
$arrName		= $controlArr[$choice]['arr'];
$data 			= explode(" ", $file);
if ($choice == 'a') {$data [49]	= str_replace('_',' ',$data [49]);}
if ($choice == 'b') {$data [531]= str_replace('_',' ',$data [531]);}

?>
<h3><?php echo $controlArr[$choice]['kind']; ?></h3>
<p><?php echo $error.' '.langtransstr('The requested file').' ('.$fileName.') '.langtransstr('contains the following raw data'); ?>:<br />
<small><?php echo $file; ?></small>
</p>
<?php echo $lientrawTxt[1]; ?>
<table  border="1" class="sortable genericTable" >
<thead>
<tr>
<th style="text-align: center; vertical-align: top; cursor: n-resize;">Field</th>
<th style="text-align: left;   vertical-align: top; cursor: n-resize;">Label</th>
<th style="text-align: center; vertical-align: top; cursor: n-resize;">Type</th>
<th style="text-align: left;   vertical-align: top;" class="sorttable_nosort">Value</th>
</tr>
</thead>
<tbody>
<?php
foreach (${$arrName} as $arr) {
	$nr=$arr['seq'];
	$nrString=substr('0000'.$nr,-3);
	$backData = 'transparent';
	$type		= $arr['type'];
	if (!isset ($data[$nr])) {
		$textData='missing in file';
		$backData = 'yellow';
	} else {
		$textData=$data[$nr];
		checkConvertData($textData,$type,$backData);

	}
	$string= '<tr>
<td style="text-align: center">'.$nrString.'</td><td style="text-align: left">'.wsLangtransCltraw($arr['label']).' '.$arr['subNr'];
	if (Langtransstr($arr['optional'])<> '') {$string .= '<small> (optional)</small>';}
	if ((strlen($arr['comment'])+strlen($arr['label']) ) > 30) {$break = '<br /> (';} else {$break = ' ( ';}
	if ($arr['comment'] <> '')  {$string .= '<small>'.$break.Langtransstr($arr['comment']).')</small>';}
	$string .='</td><td>'.$type.'</td><td style="text-align: left; background-color:'.$backData.';">'.$textData.'</td></tr>';
	echo $string;
}
$nr++;
if (count ($data) > $nr ) {  // more data than expected
	for ($i = $nr; $i < count ($data)-1; $i++) {
		$nrString=substr('0000'.$i,-3);
		$string= '
		<tr>
		<td style="text-align: center">'.$nrString.'</td>
		<td style="text-align: left">New field, not documented yet</td>
		<td>??</td>
		<td style="text-align: left; background-color:yellow;">'.$data[$i].'</td>
		</tr>';
		echo $string;	
	}
}
?>
</tbody>
</table>
</div>  <!--  eo div clientraw-xxx  -->
<?php 
} // eo foreach
?>
<br />
</div>
</div>
<?php  // local  functions
#--------------------------------------------------------------------------------------------------
function checkConvertData($textData,$type,$backData) {
	global $textData,$type,$backData,$iconsWD;
	$input=trim($textData);
	if ($input === '-') {return;}
	if ($input === '_') {$input = '-'; return;}	
	switch ($type) {
		case 'B':
		break;
	case 'C':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input <= -30) || ($input >= 40)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }
		$convert = wsConvertTemperature($input, $type,'f');
		$textData = $input.'&deg;C ( = '.$convert.' &deg;F )';
	break;
	case 'D':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input < 0) || ($input >= 360.1)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }
		$convert = wsConvertWinddir ($input);
		$textData = $input.'&deg;( = '.$convert.' )';
	break;
	case 'F':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input < 0) || ($input >= 30000)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }    		
		$convert = round(($input/3.28084),0);
		$textData = $input.' feet ( = '.$convert.' meter )';    		
	break;
	case 'H':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input <= 900) || ($input >= 1400)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }
		$convertMb 		= wsConvertBaro($input, 'hpa','mb');
		$convertMmhg	= wsConvertBaro($input, 'hpa','mmhg');
		$convertInhg	= wsConvertBaro($input, 'hpa','inhg');
		$textData = $input.' hPa ( = '.$convertMb.' mb; '. $convertMmhg.' mmHg; '. $convertInhg.' inHg )';    	
	break;
	case 'I':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input < 0) || ($input > 36)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }
		$textData = $input.' ( '.$iconsWD[$input]['iconDescription'].' )';
	break;
	case 'K':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input < 0) || ($input >= 100)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }    		
		$convertKmh = wsConvertWindspeed($input, 'kts','kmh');
		$convertMs = wsConvertWindspeed($input, 'kts','ms');
		$convertMph = wsConvertWindspeed($input, 'kts','mph');
		$textData = $input.' kts ( = '.$convertKmh.' k/H; '. $convertMs.' ms; '. $convertMph.' m/H )';    		
	break;
	case 'M':
		if (!is_numeric($input)){$backData='yellow'; return; }
		if (($input <0) || ($input >= 300)) {$textData .= ' exceptional data? '; $backData='yellow'; return; }
		$convert 		= wsConvertRainfall($input, 'mm','in');
		$textData = $input.' mm ( = '.$convert.' in )';    	
	break;
	case 'N':
		if (!is_numeric($input)){$backData='yellow'; return; }
	break;
	case 'P':
		$textData= $input.'%';
	break;
	case 'T':
		if ($input == 0) {return;};
		$time=strtotime($input);
		$string=date('H:i',$time);
		if ($string <> $input) {$textData = $string.' <> '. $input.' exceptional data? '; $backData='yellow'; return;}
	break;    	
	case 'U':
	break;    	
}
}
#--------------------------------------------------------------------------------------------------
$transCltraw = array ();
#--------------------------------------------------------------------------------------------------
function wsLangtransCltraw ( $item ) {
global $LANGLOOKUP,$missingTrans, $transCltraw;
$string = '';
$textArr = explode (' ',trim ((string) $item));

for ($i = 0; $i < count($textArr); $i++) {
	$stringPart = trim($textArr[$i]);
	if (isset($transCltraw[$stringPart])) {
		$stringPart = $transCltraw[$stringPart];
	} else {
		if (isset($LANGLOOKUP[$stringPart])) {
			$transCltraw[$stringPart]=$LANGLOOKUP[$stringPart];
			$stringPart = $LANGLOOKUP[$stringPart];
		} else {
			if(isset($stringPart) and $stringPart <> '') {$missingTrans[$stringPart] = true; }
		}  // if else  found / not found LANGLOOKUP
	} // if else  found / not found transCltraw
	$string .= $stringPart. ' ';
}  // eo for loop
return $string;
}
#--------------------------------------------------------------------------------------------------
function checkUrl($input) {
	if ($input == '') { return ($input);}
	$result = filter_var($input, FILTER_SANITIZE_URL);

	if (substr($result,-1) <> '/') {$result .= '/';} 
	$string=strtoupper(substr($result,0,7));
	if ($string <> "HTTP://") {$result="HTTP://".$result;}
	if (!filter_var($result,FILTER_VALIDATE_URL)) {
		$result= langtransstr('ERROR is not a valid url').': '.$result;
	}
	return ($result);
}
?>

</div>