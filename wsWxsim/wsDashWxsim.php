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
$pageName	= 'wsDashWxsim.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.12 2015-03-18';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.12 2015-03-18 beta 2.7 version
# ----------------------------------------------------------------------
# To do: 
#   
#-----------------------------------------------------------------------------------------
#
#       settings are  contained in wsWxsimsettings.php
#	the setting for the scriptfolder is needed to load the settingsfile 
#
$scriptFolder	= 'wsWxsim/';		// location of the ws wxsim scripts, set it here
if (isset ($SITE['wxsimDir']) )		{$scriptFolder = $SITE['wxsimDir'];}  // or is it already set in calling script
include $scriptFolder.'wsWxsimSettings.php';

$displayTimes 	= false;		// display create time and next run tiomes 

$displayIcons 	= true;		        // display the plaintext icons on full page
$topCount       = 10;                   // how many icons

$graphsDisplay  = true;
$graphsSeparate = false;		// set to false if graphs should be separate tab in table area


$displayTabs 	= true;		        // display tables with plaintext,  3 and hourly information 
$wxsimTabHeight	= '500px';

$displayCredits = false;
#                                       // link to full page forecast. 
$fullpage_link  = true;                 # set to false if no link to full page is wanted            ######
$full_page      = 'wsWxsimPrintFull';   // script name of the full page metno forecast
#
# load / parse plaintext.txt into array
include $scriptFolder.'wsWxsimParser.php';	
$timeFormat 	= $SITE['timeOnlyFormat'];	// modified by plaintext script
if ($wxsimERROR) {return;}
#
# load  processing of lastret.txt or latest.csv into array
include $scriptFolder.'wsWxsimGetFile.php';
if ($wxsimERROR) {return;}
#
# load first part of printing, all output tables are created here
include $scriptFolder.'wsWxsimGenerateHtml.php';
if ($wxsimERROR) {return;}
#
echo '<div class="ajaxHead" >'.langtransstr('Weather Forecast').' ('.langtransstr('summary').')&nbsp;&nbsp;';
if ($fullpage_link) {
        $wxsimLink      =   $SITE['pages'][$full_page].'&amp;lang='.$lang.$extraP.$skiptopText; 
        echo '<a href="'.$wxsimLink.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>';
}
#
echo '</div>'.PHP_EOL;

if ($displayTimes) {
        echo '<div class="ajaxHead" style="">'.PHP_EOL.$wsUpdateTimes.PHP_EOL.'</div>'.PHP_EOL;
}
#
if ($displayIcons) {			        // icons and tooltips based on plaintext.txt are assembled in wsWxsimPrint1
	echo '<div style="width: 100;">';
	echo $tableIcons;			// icons
	echo '</div><br />'.PHP_EOL;
}
#
if ($graphsDisplay && $graphsSeparate) {	// are the graphs separate (=true) on the page or are they in a tab
	echo '<div style="width: '.$width_graphs_x.' margin: 0 auto;">';
	echo $stringGraphDivs;
	echo '</div>'.PHP_EOL;
}
# now the tabs for the tables with wxsim data
#
if ( isset($wxsimTabHeight) ) {
	$styleHeight='height:'.$wxsimTabHeight.';';
} else {
	$styleHeight='';
}
if ($displayTabs) {
        echo '<div class="tabber"  style="width: 100%;">'.PHP_EOL;
        if ($graphsDisplay && !$graphsSeparate) {	// are the graphs separate on the page or are they in a tab  (=false)
                echo '<div class="tabbertab" style="overflow: hidden;"><h2>'.langtransstr('Graphs').'</h2>'.PHP_EOL;
                   echo '<br /><div style="width: '.$width_graphs_x.'">';
                   echo $stringGraphDivs;
                   echo '</div>'.PHP_EOL;
                echo '</div>'.PHP_EOL;
        }
        echo '<div class="tabbertab" style="'.$styleHeight.'"><h2>'.langtransstr('Overview').'</h2>'.PHP_EOL;
                echo $tablePlain;	// plaintext.txt based information tab						// tooltips
        echo '</div>'.PHP_EOL;
        #
        for ($n=0;$n < count($printTable);$n++){ 	// print all table strings
                echo '<div class="tabbertab" style="'.$styleHeight.'"><h2>'.$tableName[$n].'</h2>'.PHP_EOL;
                echo $table[$n];
                echo '</div>'.PHP_EOL;
        }
         echo '</div>'.PHP_EOL;
}
if ($graphsDisplay){
        echo $graphPart1;		// javascripts with all  the graphs are shown in the divs outputted above
}
#
# credit who is credit due
$arrContrib		= array();
$arrContrib[]	= array ('name' => 'Ken True',	'organ' => 'SaratogaWeather', 	'href' => 'http://saratoga-weather.org/');
$arrContrib[]	= array ('name' => 'Rob', 		'organ' => 'WellingtonWeather', 'href' => 'http://www.wellingtonweather.co.uk');
$arrContrib[]	= array ('name' => 'HENKKA',		'organ' => 'NordicWeather', 	'href' => 'http://www.nordicweather.net');
if (!isset($SITE['wxsimIconsOwn']) || $SITE['wxsimIconsOwn'] == false) {
	$arrContrib[]	= array ('name' => 'icons',   	'organ' => 'KDE', 	'href' => 'http://kde-look.org/content/show.php?content=39988');
} else {
	$arrContrib[]	= array ('name' => 'icons',   	'organ' => 'DotVoid', 	'href' => 'http://www.dotvoid.com/weather-icons/');
}

if ($displayCredits) {
        echo '
<div class="blockHead">
<small> WXSIM script developed by Wim van der Kuil from <a href="http://leuven-template.eu/" target="_blank">Leuven-Template.eu</a>.&nbsp;&nbsp;
Based on scripts/snippets/icons from: ';
        $last	= count($arrContrib);
        for ($i = 0; $i < $last; $i++) {
                echo $arrContrib[$i]['name'].' from <a target="_blank" href="'.$arrContrib[$i]['href'].'">'.$arrContrib[$i]['organ'].'</a>';
                if ($i < $last - 2) 	{echo '; ';}
                if ($i == $last - 2) 	{echo ' and ';}
        }
        echo '.&nbsp;
Forecast is created from data by  <a target="_blank" href="http://www.wxsim.com">WXSIM</a>. Graphs are drawn using <a target="_blank"  href="http://www.highcharts.com">Highcharts</a>
</small>
</div>';
}
# these are the needed javascripts which should be loaded after everything else
if ($graphsDisplay){
        echo '<script type="text/javascript" src="'.$javaFolder.'jquery.js"></script>'.PHP_EOL;
        echo '<script type="text/javascript" src="'.$javaFolder.'jquery.qtip.js"></script>'.PHP_EOL;
        echo '<script type="text/javascript" src="'.$javaFolder.'highcharts.js"></script>'.PHP_EOL;
        echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
if ($displayTabs || $graphsDisplay) {
        echo '<script type="text/javascript" src="'.$javaFolder.'tabber.js"></script>'.PHP_EOL;
}
?>