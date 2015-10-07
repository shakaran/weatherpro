<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsWxsimPrintFull.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-05-30';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.01 2015-05-30  2.7 release version 
#-----------------------------------------------------------------------------------------
# settings are  contained in wsWxsimsettings.php
#	the setting for the scriptfolder is needed to load the settingsfile 
$scriptFolder	= 'wsWxsim/';		// location of the ws wxsim scripts, set it here
if (isset ($SITE['wxsimDir']) )		{$scriptFolder = $SITE['wxsimDir'];}  // or is it already set in calling script
include ($scriptFolder.'wsWxsimSettings.php');

$displayTimes 	= true;		        // display create time and next run tiomes 

$iconsDisplay 	= true;			// display the plaintext derived icons on full page
$topCount       = 10;                   // how many icons
$iconsInTabs    = false;
$iconsTooltips  = false;                // set to true if you want the tooltips with the icons

$graphsDisplay  = true;
$graphsInTabs   = false;

$plainDisplay   = true;
$plainInTabs    = true;
$plainTooltips  = false;                // tooltips for text part contains no extra information

$tabsDisplay 	= true;		        // display tables   3 hour - hourly - soil information 
$tabsTooltips   = true;

$wxsimTabHeight	= '500px';

$displayCredits = true;
#
$addSeparator   = true;                // add extra br and hr between different parts
$block_separator= '<br /><hr /><br />'.PHP_EOL;
#$separator      = '<br /><br />'.PHP_EOL;
#
$margin = '0px';                        // default marin, leave as is in most cases
#
echo '<div class="blockDiv">'.PHP_EOL;
#
# load / parse plaintext.txt into array
include ($scriptFolder.'wsWxsimParser.php');	
$timeFormat 	= $SITE['timeOnlyFormat'];		// disturbed by plaintext script
if ($wxsimERROR) { echo '</div>'.PHP_EOL; return;}
#
# load  processing of lastret.txt or latest.csv into array
include ($scriptFolder.'wsWxsimGetFile.php');
if ($wxsimERROR) { echo '</div>'.PHP_EOL; return;}
#
# load first part of printing, all output tables are created here
include ($scriptFolder.'wsWxsimGenerateHtml.php');
if ($wxsimERROR) { echo '</div>'.PHP_EOL; return;}
#
# first we print the header with the date and times of this and next wxsim updates
#
if ($displayTimes) {echo '<div class="ajaxHead" style="">'.PHP_EOL.$wsUpdateTimes.PHP_EOL.'</div>'.PHP_EOL;}

$margin = '0px';
#
if ($iconsDisplay && !$iconsInTabs) {		// icons and tooltips based on plaintext.txt are assembled in wsWxsimPrint1
	echo '<div style="width: 100%; margin: '.$margin.' auto;">';
	echo $tableIcons;			// icons
	if ($iconsTooltips) { echo $qtipTxtIcon;}	
	echo '</div>'.PHP_EOL;
	if ($addSeparator) {echo $block_separator;}
}
#
if ($graphsDisplay && !$graphsInTabs) {	
	echo '<div style="width: '.$width_graphs_x.' margin: '.$margin.' auto;">';
	echo $stringGraphDivs;
	echo '</div>'.PHP_EOL;
	if ($addSeparator) {echo $block_separator;}
}
#
if ($plainDisplay && !$plainInTabs) {
        echo '<div style="width: 100%; margin: '.$margin.' auto;">';
        echo $tablePlain;	        // plaintext.txt based information tab
        if ($plainTooltips) { echo $qtipTxtPlain; }     // optional tooltips
        echo '</div>'.PHP_EOL;
	if ($addSeparator) {echo $block_separator;}
}
#
# now the tabs 
#
if ($tabsDisplay || ($iconsDisplay && $iconsInTabs) || ($graphsDisplay && $graphsInTabs) || ($plainDisplay && $plainInTabs) ) {
        $tabber_needed  = true;
        if ( isset($wxsimTabHeight) ) {$styleHeight='max-height:'.$wxsimTabHeight.';';} else { $styleHeight='';}
        
        echo '<div class="tabber"  style="width: 100%; margin: '.$margin.' auto;">'.PHP_EOL;
        
        if ($iconsDisplay && $iconsInTabs) {		
                echo '<div class="tabbertab" style="overflow: hidden;"><h2>'.langtransstr('Icons').'</h2>'.PHP_EOL;
                echo $tableIcons;			// icons
                if ($iconsTooltips) { echo $qtipTxtIcon;}	
                echo '</div>'.PHP_EOL;
        }
        if ($graphsDisplay && $graphsInTabs) {		// are the graphs separate on the page or are they in a tab  (=false)
                echo '<div class="tabbertab" style="overflow: hidden;"><h2>'.langtransstr('Graphs').'</h2>'.PHP_EOL;
                $margin = '0px';
                echo '<div style="width: '.$width_graphs_x.' margin: '.$margin.' auto;">';
                echo '<br />';
                echo $stringGraphDivs;
                echo '</div>'.PHP_EOL;
                echo '</div>'.PHP_EOL;
        }
        if ($plainDisplay && $plainInTabs) {
                echo '<div class="tabbertab" style="'.$styleHeight.'"><h2>'.langtransstr('Overview').'</h2>'.PHP_EOL;
                echo $tablePlain;	        // plaintext.txt based information tab
                if ($plainTooltips) { echo $qtipTxtPlain; }     // optional tooltips
                echo '</div>'.PHP_EOL;
        }
        if ($tabsDisplay) {
                for ($n=0;$n < count($printTable);$n++) { 	// print all table strings
                        echo '<div class="tabbertab" style="'.$styleHeight.'"><h2>'.$tableName[$n].'</h2>'.PHP_EOL;
                        echo $table[$n];
                        if ($tabsTooltips) {echo $qtipTxt[$n];}
                        echo '</div>'.PHP_EOL;
                }
        }
        echo '</div>'.PHP_EOL;
} // eo display tabs
#
if ($graphsDisplay) {
        echo $graphPart1;			// javascripts with all the graphs are shown in the divs outputted above
}
#
if ($displayCredits) {
        # credit who is credit due
        $arrContrib	        = array();
        $arrContrib[]	        = array ('name' => 'Ken True',	'organ' => 'SaratogaWeather', 	'href' => 'http://saratoga-weather.org/');
        $arrContrib[]	        = array ('name' => 'Rob', 	'organ' => 'WellingtonWeather', 'href' => 'http://www.wellingtonweather.co.uk');
        $arrContrib[]	        = array ('name' => 'HENKKA',	'organ' => 'NordicWeather', 	'href' => 'http://www.nordicweather.net');
        if (!isset($SITE['wxsimIconsOwn']) || $SITE['wxsimIconsOwn'] == false) {
                $arrContrib[]	= array ('name' => 'icons',   	'organ' => 'KDE', 	        'href' => 'http://kde-look.org/content/show.php?content=39988');
        } else {
                $arrContrib[]	= array ('name' => 'icons',   	'organ' => 'DotVoid', 	        'href' => 'http://www.dotvoid.com/weather-icons/');
        }
        echo '<br /><div class="blockHead"> <small> WXSIM script developed by Wim van der Kuil from <a href="http://leuven-template.eu/" target="_blank">Leuven-Template.eu</a>.&nbsp;&nbsp;.&nbsp;&nbsp; Based on scripts/snippets/icons from: ';
        $last	= count($arrContrib);
        for ($i = 0; $i < $last; $i++) {
                echo $arrContrib[$i]['name'].' from <a target="_blank" href="'.$arrContrib[$i]['href'].'">'.$arrContrib[$i]['organ'].'</a>';
                if ($i < $last - 2) 	{echo '; ';}
                if ($i == $last - 2) 	{echo ' and ';}
        }
        echo '.&nbsp;Forecast is created from data by  <a target="_blank" href="http://www.wxsim.com">WXSIM</a>. Graphs are drawn using <a target="_blank"  href="http://www.highcharts.com">Highcharts</a></small></div>';
}
echo '</div>';
# these are the needed javascripts which should be loaded after everything else
if ($graphsDisplay){
        echo '<script type="text/javascript" src="'.$javaFolder.'jquery.js"></script>'.PHP_EOL;
        echo '<script type="text/javascript" src="'.$javaFolder.'jquery.qtip.js"></script>'.PHP_EOL;
        echo '<script type="text/javascript" src="'.$javaFolder.'highcharts.js"></script>'.PHP_EOL;
        echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
if (isset($tabber_needed) ) {
        echo '<script type="text/javascript" src="'.$javaFolder.'tabber.js"></script>'.PHP_EOL;
}