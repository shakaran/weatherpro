<?php # ini_set('display_errors', 'On');   error_reporting(E_ALL);
# A template set for personal weather stations
# Developer :	(c) 2011-2015 Wim van der Kuil  http://leuven-template.eu/ 
# Based on previous scripts from Ken True (Saratoga.org) and a lot of others. See individual scripts for credits.
# Use and adapt as you wish.
#
require 'lib/Util.php';

Util::checkShowSource();

ob_start();
$SITE 		= array();	// to store all settings for all scripts
$pathString	= '';		// to store messages form scripts before echo can be used
#
$pageName	= 'index.php';	
$pageVersion	= '3.20 2015-09-05';
# ----------------------------------------------------------------------------------------
# 3.20 2015-09-05  beta rel 2.8 version
# ----------------------------------------------------------------------------------------
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
$pathString.= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageName]." -->".PHP_EOL;  // echo all modulenames loaded
$extraP	= '';
# -------- first we load the sitewide settings we need from now on      ------------------
$index  = true; 
$pathString.= '<!-- module index.php ('.__LINE__.'): loading wsLoadSettings.php -->'.PHP_EOL;
include 'wsLoadSettings.php';
$lang   = $SITE['lang'];	
#
# -----------------------------------------------------  check if we go have a mobile site
if (ws_check_setting  ($SITE['useMobile'])  )   {
	$pathString.= '<!-- module index.php ('.__LINE__.'): loading ws_check_mobi.php  -->'.PHP_EOL;
	include $SITE['mobileDir'].'ws_check_mobi.php';
}
#------------------------  now we are processing requests for pages on our normal website
ini_set('default_charset', $SITE['charset']);
$pathString.= '<!-- module index.php('.__LINE__.'): loading '.$SITE['langFunc'].' -->'.PHP_EOL;
require_once($SITE['langFunc']);		// so we can translate  to required lang
#
$titleOfPage = langtransstr('Start page') . " - " . langtransstr($SITE['organ']); 
#
#-----------------------------------------------------------------------------------------
#	decide which page to load based on the users input      
#-----------------------------------------------------------------------------------------
$p      = $SITE['noChoice'];
if (isset($_GET['p'])) {
	$p = trim($_GET['p']);	
}elseif (isset($_POST['p'])) {
	$p = trim($_POST['p']);		
}
if ( isset ($_POST['donotwant']) ) {$p = $SITE['noChoice'];}  // if user reject cookie, goback to startpage
#-----------------------------------------------------------------------------------------
#	load menu data into $menuArray for pagenumber checking and into a HTML string 
#-----------------------------------------------------------------------------------------
$pathString.= '<!-- module index.php ('.__LINE__.'): loading '.$SITE['menuLoad'].' -->'.PHP_EOL;
require_once $SITE['menuLoad'] ;
#
if (isset($menuArray[$p]['choice'])) {	// valid page number
	$folder 	= $menuArray[$p]['folder']; 
	$gizmo  	= $menuArray[$p]['gizmo'];
	$css		= $menuArray[$p]['css'];
	$head		= $menuArray[$p]['head'];
	$link		= $menuArray[$p]['link'];
	$noutf8         = $menuArray[$p]['noutf8'];        
	$titleOfPage	= langtransstr($menuArray[$p]['title']). " - ".langtransstr($SITE['organ']);
	$phpself	= "index.php?p=".$p;// if a page wants to reload itself later it uses this var because a page does not know its menunumber
	$phpselftop     = $menuArray[$p]['top']; 
} 
else {  $p      	= $SITE['noChoice'];
	if (!isset ($menuArray[$p]['choice']) ) {$p = 'wsStartPage';}
	header ("Location: index.php?p=".$p);	// back to index/startpage if someone tries an
	exit;  					                //  out of range / non existing page
}
if ( $SITE['charset'] == 'UTF-8'   && $noutf8 <> '') {  // test for: noutf8  = "WINDOWS-1252"
        $SITE['charset'] = $noutf8;             // generate page in requested character set
        ini_set('default_charset', $SITE['charset']);
        $LANGLOOKUP 	= array();		// clean arrays with utf-8 language data
        $missingTrans	= array();
        $dir 		= $SITE['langDir'];
        langMergeFile   ( $dir );		// reload lang files with nonutf8 data
        $menuArray      = array();
        $DropdownMenuText = '';
        $level		= 1;	
        wsMenuGen 	($xmlStr);		// generate new menu files
}
unset ($xmlStr);
#
$SITE['pageRequest'] = $p.'|'.$link;
#-----------------------------------------------------------------------------------------
#            generate headers and first part of page
#-----------------------------------------------------------------------------------------
if (!isset ($lang) ) {$lang = $SITE['lang'];}
header('Vary: User-Agent');
header("Content-Type: text/html; charset=".strtoupper($SITE['charset']));
echo'<!DOCTYPE html>
<html lang="'.$lang.'">
<head>'.PHP_EOL;
#ob_flush();
$htmlTxt	= '';				// in this string we assemble the html output
$htmlTxt       .= '<meta charset="'.strtoupper($SITE['charset']).'"/>'.PHP_EOL;
#-----------------------------------------------------------------------------------------
# javascript for Ajax updates.
# This is in header1 a one line weather description with rotating content: temp / baro a.s.o. => gizmo
# 	or  a set of weather variables boxes in header2
# The gizmo is only shown on a page if requested in the menu file entry for that page  AND if allowed by the settings
# If header2 is used the ajax updates are always done as otherwise old data would be displayed after some time
#-----------------------------------------------------------------------------------------
$ajax_updates   = false;
if (trim($SITE['header']) == '2') { $ajax_updates   = true;} // header 2 needs ajax updates
#
#      if menu file request a gizmo for this page, and it is set in site-settings , than display gizmo
if ($gizmo && ws_check_setting($SITE['ajaxGizmoShow']) ) { 
        $ajax_updates   = true;                         // gizmo needs ajax updates
	$htmlTxt.='<script type="text/javascript" src="'.$SITE['ajaxGizmojs'] .'"></script>'.PHP_EOL;
	if (!isset($SITE['UV']) || !$SITE['UV']) {	// turn of display of UV value if no UV sensor present
		$htmlTxt.='<script type="text/javascript"> showUV = false; </script>'.PHP_EOL;
	} // end of turn gizmo UV display off
} // end of gizmo e
#
if ($ajax_updates && isset($SITE['wsAjaxScript']) ){	// is there a realtime update script for this site allowed
	$htmlTxt.='<script type="text/javascript">
	var wsUrl       = "'.$SITE['wsAjaxDataLoad'].'?lang='.$lang.'&wp='.$SITE['WXsoftware'].'";   
	var reloadTime 	= '.$SITE['wsAjaxDataTime'].'000;
</script>'.PHP_EOL;
	$htmlTxt.='<script type="text/javascript" src="'.$SITE['wsAjaxScript'].'"></script>'.PHP_EOL;
} 	// end if ajaxScript
#
$htmlTxt.= '<script type="text/javascript" src="javaScripts/styleMenu.js"></script>'.PHP_EOL;
#$htmlTxt.= '<script type="text/javascript" src="javaScripts/lightbox.js"></script>'.PHP_EOL;
if (isset ($SITE['autoRefresh'])  &&  $SITE['autoRefresh'] > 60 ){
	$htmlTxt.= '<meta http-equiv="refresh" content="'.$SITE['autoRefresh'].'" />'.PHP_EOL;
}
$htmlTxt.= '	<meta name="description" content="'.$titleOfPage.'" />'.PHP_EOL;
$htmlTxt.= '	<meta name="apple-mobile-web-app-capable" content="yes" />'.PHP_EOL;
#$htmlTxt.= '	<meta name="viewport" content="user-scalable=yes,maximum-scale=2,height=device-height">'.PHP_EOL;
$htmlTxt.= '	<meta http-equiv="Content-Type" content="text/html; charset='.strtoupper($SITE['charset']).'"/>'.PHP_EOL;
#-----------------------------------------------------------------------------------------
#            standard stylesheets
#-----------------------------------------------------------------------------------------
$htmlTxt .='<link rel="stylesheet" type="text/css" href="'.$SITE['CSSscreen'].'" media="all" title="screen" />'.PHP_EOL;
$htmlTxt.='<link rel="stylesheet" type="text/css" href="'.$SITE['CSStable'].'" media="all" title="screen" />'.PHP_EOL;
if ($SITE['stripAll'] || $SITE['menuPlace'] == 'H') {
	$htmlTxt.='<link rel="stylesheet" type="text/css" href="'.$SITE['CSSmenuHor'].'" media="all" title="screen" />'.PHP_EOL;
} else {
	$htmlTxt.='<link rel="stylesheet" type="text/css" href="'.$SITE['CSSmenuVer'].'" media="all" title="screen" />'.PHP_EOL;
}
#------------------------------------------------------------------------------------------
#   extra stylesheet for this menuchoice
#------------------------------------------------------------------------------------------
if ($css <> ''){
	$htmlTxt.='<link rel="stylesheet" type="text/css" href="'.$css.'" media="all" title="screen" />'.PHP_EOL;
}
$htmlTxt.='<link rel="stylesheet" type="text/css" href="'.$SITE['CSSmood'].'" media="all" title="screen" />'.PHP_EOL;
$htmlTxt.='<link rel="stylesheet" type="text/css" href="'.$SITE['CSSprint'].'" media="print" />'.PHP_EOL;
$htmlTxt.='<link rel="stylesheet" type="text/css" href="gauges/css/gauges-ss.css" />'.PHP_EOL;
$htmlTxt.='<link rel="stylesheet" type="text/css" href="_my_texts/my_style.css" />'.PHP_EOL;
$htmlTxt.='<link rel="stylesheet" type="text/css" href="style_lightbox.css" />'.PHP_EOL;

#-----------------------------------------------------------------------------------------
# other header lines 
#-----------------------------------------------------------------------------------------
if (isset ($SITE['stationTxt'])  && $SITE['stationTxt'] <> '') {$extra_text =  ', '.$SITE['stationTxt']; } else {$extra_text = '';}
if ($SITE['yourArea'] <> '')            {$extra_text .=  ', '.$SITE['yourArea'];}
if ($SITE['webcamPage']  == true)       {$extra_text .=  ', webcam';}
if ($SITE['soilUsed']  == true)         {$extra_text .=  ', soil, moisture';}
$htmlTxt.='<link rel="icon" href="img/icon.png" type="image/x-icon" />
<meta name="Keywords" content="weather, Weather, temperature, dew point, humidity,
forecast'.$extra_text .',  Weather, '.$SITE['organ'].', weather conditions, 
live weather, live weather conditions, weather data, weather history, '.$SITE['WXsoftwareLongName'].' " />
<title>'.$titleOfPage.'</title>'.PHP_EOL;
echo $htmlTxt;							// now all header text is sent
#-----------------------------------------------------------------------------------------
# general functions used in all scripts
#-----------------------------------------------------------------------------------------
$pathString	.= '<!-- module index.php ('.__LINE__.'): loading '.$SITE['functions'].'  -->'.PHP_EOL;
include_once $SITE['functions'];		// general functions for data processing
ws_message ($pathString);
$pathString	= '';			        // reset for further use
#-----------------------------------------------------------------------------------------
# a different script for every supported weather program is used to convert weatherdata
#-----------------------------------------------------------------------------------------
$rtOut          = 'ajax';
$load_ajax_only = true;
ws_message ( '<!-- module index.php ('.__LINE__.'): loading '.$SITE['wsAjaxDataLoad'].' -->');
include $SITE['wsAjaxDataLoad'];
$vars           = array();
$string2        =str_replace('ajaxVars','$vars',$string1);  // change javascript to php
eval($string2);					// all vars for use in headings and ajax "normal" pages are now loaded into $vars array
#-----------------------------------------------------------------------------------------
#	do we want a colored background a.s.o.
#-----------------------------------------------------------------------------------------
if ($SITE['colorNumber'] <>  0) {		// fixed color scheme (1 and higher) overrides dynamic (0) based on weather conditions
	$headerClass = $SITE['colorStyles'][$SITE['colorNumber']];	
}
ws_message ('<!-- module index.php ('.__LINE__.'): icon = '.$ccnIconNr.'  , hdrclass = '.$headerClass.' -->');
#-----------------------------------------------------------------------------------------
# we calculate the width of the page and the width of the usable area
#-----------------------------------------------------------------------------------------
#
if (isset($SITE['pageWidth'])) { $pageWidth     = $SITE['pageWidth'];}  else { $pageWidth	= '1000';}
if (isset($SITE['menuWidth'])) { $menuWidth	= $SITE['menuWidth'];}  else { $menuWidth	='140';}
$areaWidth = $pageWidth;
if ($SITE['sideDisplay'] || $SITE['menuPlace'] <> 'H') {
	$areaWidth	= $pageWidth - $menuWidth;// usable area = pagewidth minus optional menu/sidecolom width
}
#-----------------------------------------------------------------------------------------
#---  extras for the html head section go between here   ---------------------------------
#
if ($head <> ''){				// extras to be included based on the menu settings F.i. generate a special css a.s.o.
        $save_p = $p;                           // ewn fct disturbs this, so we save it for later
	ws_message ( '<!-- module index.php ('.__LINE__.'): loading '.$head.' -->');
	include $head ; 			// load  /execute the requested extra's
	$p = $save_p; 
}
#                                  
if (!isset($noDocready) ) {			// WU-Graphs  v 1.8.0 / � 2010 Radomir Luza. cannot run with this code, it has its own version of it
        echo '<script type="text/javascript">
var docready=[],$=function(){return{ready:function(fn){docready.push(fn)}}};
</script>'.PHP_EOL;
}
#
#---------  and here     -----------------------------------------------------------------
echo '</head>'.PHP_EOL;
echo '<body class="'.$headerClass.'">'.PHP_EOL;
#-----------------------------------------------------------------------------------------
# Now we set a picture as background based on the  settings
#-----------------------------------------------------------------------------------------
$img            = str_replace ('ws_', '', $headerClass);
$fileString     ='img/background-'.$img.'.jpg';
if (file_exists($fileString)) {
	echo '<img src="'.$fileString.'" class="bg" alt="" />'.PHP_EOL;
}
#-----------------------------------------------------------------------------------------
# we start outputting the page
#-----------------------------------------------------------------------------------------
echo '<!-- page wrapper -->'.PHP_EOL;
if (isset ($SITE['message']) ) {echo $SITE['message'];}
#
if ($SITE['stripAll'] && $SITE['stripMenu']) {
        echo '<div id="pagina" style="width:100%; position: relative; z-index: 2;">'.PHP_EOL;       
} else {
        echo '<div id="pagina" style="width:'.$pageWidth.'px; position: relative; z-index: 2;">'.PHP_EOL;
}
#-----------------------------------------------------------------------------------------
# Warnings boxes
if (ws_check_setting  ($SITE['maintenanceShow']) ) {
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading maintenance.php -->');
	include 'maintenance.php';
}
if (ws_check_setting  ($SITE['warnings']) )  {
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading '.$SITE['warningScript'].' -->');
        include $SITE['warningScript'];
        if (ws_check_setting  ($SITE['warningInside']) <> true) { echo $wrnStrings;} 
}
if (ws_check_setting ($SITE['bannerTop']) ) {
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading '.$SITE['bannerTopTxt'].' -->');
	include $SITE['bannerTopTxt'];
}
#
if (!$SITE['stripAll']) { 				// a normal page incl headers footers and (optional/default) a menu
	#-------------------------------------------------------------------------------------
	#  header either low (type 1)  or 2 = default
	#-------------------------------------------------------------------------------------
	$header			= 2;			// default header = 2
	if ( isset($SITE['header']) ){
		$header		= $SITE['header'];	//  which header 1 or 2
	}
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading '."wsHeader$header.php".' -->');	
	include ("wsHeader$header.php");
	#-------------------------------------------------------------------------------------
	#  where do we place the menu
	#-------------------------------------------------------------------------------------
	if (!$SITE['stripMenu'] && $SITE['menuPlace'] == 'H') { // no strip menu and horizontal
	#  							// horizontal menu
		echo '<div id="menu-hor" style="margin: 5px 5px; overflow: hidden; width:">'.PHP_EOL;
		echo '<ul id="nav" style="max-width: 100%;">'.PHP_EOL;
		echo $DropdownMenuText;
		echo '</ul>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	} 
	if ($SITE['sideDisplay'] || $SITE['menuPlace'] <> 'H') {	// vertical menu in side area
	#  								//  vertical menu in side area
		echo '<!-- first colomn with links to external sites and or vertical menu-->'.PHP_EOL;
		echo '<div id="info-ver" style="width: '.$menuWidth.'px; clear: both;">'.PHP_EOL;
		ws_message ( '<!-- module index.php ('.__LINE__.'): loading '.$SITE['sideInc'].' -->');
		include_once($SITE['sideInc']);
		echo '</div>'.PHP_EOL;   		//
		echo '<!-- end first colomn -->'.PHP_EOL;
		$areaWidth =  $pageWidth - $menuWidth;	// subtract menu width and 10px margin	
	}
} else { 
        $areaWidth =  $pageWidth;						
	if (!$SITE['stripMenu']) {              // strip all extras but do we want a menu?
		echo '<div id="menu-hor" style="width: '.($areaWidth - 10).'px; margin: 5px;">'.PHP_EOL;	// only a horizontal menu is possibles as there is no sidecolom etc
		echo '<ul id="nav">'.PHP_EOL.$DropdownMenuText;
		echo '</ul>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
}
if ($SITE['stripAll'] && $SITE['stripMenu']) {
        echo '<div id="data-area" style="width: 100%; ">'.PHP_EOL;
} else {
        if ($SITE['floatTop']) {
        	ws_message (  '<!-- module index.php ('.__LINE__.'): loading '.$SITE['floatTopTxt'].' -->');
        	include $SITE['floatTopTxt'];
        }
        echo '<div id="data-area" style="width: '.$areaWidth.'px;">'.PHP_EOL;
}
#
if (ws_check_setting  ($SITE['warnings']) && ws_check_setting ($SITE['warningInside']) ) { echo $wrnStrings;} 
#---------------------------------------------------------------------------
#		load the requested page as specified in menu settings
#
ws_message (  '<!-- module index.php ('.__LINE__.'): loading  '.$link.' -->');
include $link;
echo'</div>
<!-- end data area -->'.PHP_EOL;
#---------------------------------------------------------------------------
#		bottom area and footer
#
if (!$SITE['stripAll'] && $SITE['bottomDisplay']) {	// optional bottom area
	echo '<div id="bottom" class="doNotPrint">'.PHP_EOL;
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading  '.$SITE['bottomInc'].' -->');
	include $SITE['bottomInc'];
	echo '</div>'.PHP_EOL;
}
if (!$SITE['stripAll']) {
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading  '.$SITE['footer'].' -->');
	include $SITE['footer'];  // always the footer with optional whoisonline
} else {
	echo '<div id="footer" class="doNotPrint" style="height: 0px;"></div>';
}
if (ws_check_setting ($SITE['bannerBottom']) ) {
	ws_message (  '<!-- module index.php ('.__LINE__.'): loading  '.$SITE['bannerBottomTxt'].' -->');
	include $SITE['bannerBottomTxt'];
}
?>
</div>
<!-- end id="page" wrapper -->
<?php 
echo '<!-- '.PHP_EOL;
echo '_____________________________|_____________________________'.PHP_EOL;
echo '              loaded modules | version number'.PHP_EOL;
echo '_____________________________|_____________________________'.PHP_EOL;
foreach ( $SITE['wsModules'] as $key => $val) {
	echo substr('                       '.$key.' | ',-31,31).$val.PHP_EOL;
}
echo '_____________________________|_____________________________'.PHP_EOL;
if (isset ($ws['error']) ) {
        foreach ($ws['error'] as $key => $count) {
                echo $key;
                if ($count > 1) {echo ': '.$count.' times';}
                echo PHP_EOL;
        }
}
if (isset ($SITE['error']) ) {
        foreach ($SITE['error'] as $key => $count) {
                echo $key;
                if ($count > 1) {echo ': '.$count.' times';}
                echo PHP_EOL;
        }
}
echo '-->'.PHP_EOL;
?>
<br />
<script type="text/javascript" src="javaScripts/lightbox.js"></script>
 </body>
</html>
<?php 
#
# Leave this code here .. it will help you see what language translations are missing by running any page on your
# website with a ?show=missing argument
#
$langfile	= '';
$string		= '';
if(isset($_REQUEST['show']) and strtolower($_REQUEST['show']) == 'missing') {
	echo '<!-- '.'index.php'.' missing langlookup entries for lang='.$lang.PHP_EOL;
	foreach ($missingTrans as $key => $val) {
		$string.= 'langlookup|'.$key.'|'.$key.'|
';
	}
	if (strlen($string) > 0) {
		echo $string;
		if ($wsDebug) {
			$langfile 	= './_my_texts/wsLanguage-' . $lang .'-local.txt';
			file_put_contents($langfile,$string,FILE_APPEND);
		}
	}
	echo count($missingTrans).' entries.  End of missing langlookup entries -->'.PHP_EOL;
}
function ws_message ($message,$always=false,&$string=false) {
	global $wsDebug, $SITE;
	$echo	= $always;
	if ( $echo == false && isset ($wsDebug) && $wsDebug == true ) 			{$echo = true;}
	if ( $echo == false && isset ($SIE['wsDebug']) && $SIE['wsDebug'] == true ) 	{$echo = true;}
	if ( $echo == true  && $string === false) {echo $message.PHP_EOL;}
	if ( $echo == true  && $string <>  false) {$string .= $message.PHP_EOL;}
}
# ----------------------  version history
# 3.20 2015-09-07 release 2.8 version 
