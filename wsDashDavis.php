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
$pageName	= 'wsDashDavis.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-14';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->'.PHP_EOL;
#-------------------------------------------------------------------------------------
if (!isset($SITE['DavisVP']) ||  !$SITE['DavisVP'])             { return; }
if (!isset($ws['fcstTxt'])   ||  '' == trim($ws['fcstTxt']) )   { return; }
#
echo '<!-- VP/VUE forecast  txt-->
<div class="blockDiv" style="text-align: center;">
<h3 class="blockHead">'.langtransstr('Our weatherstation').' '.$SITE['stationTxt'].' '.langtransstr('forecasts').'</h3>'.PHP_EOL;

$from 		= array ('hrs.', 'temp.',' windy');
$to		= array ('hours', 'temperature', '.windy');
$string		= str_replace ($from, $to, $ws['fcstTxt'] );		
$arrVantage	= explode('.',$string);
$text	= '';
$br     = '';
$count	= count($arrVantage);
for ($i=0; $i < $count; $i++){
	$string	= trim( strtolower($arrVantage[$i]) );
	if ($string <> '') {
		$text   .= $br .langtransstr(ucfirst($string));
		$br     = '<br />';
	}
}
echo $text.'
</div>
<!--  end of VP/VUE forecast  txt-->'.PHP_EOL;
?>