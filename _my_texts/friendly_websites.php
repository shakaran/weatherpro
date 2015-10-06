<?php
#-----------------------------------------------------------------------
# FIRST SOME HOUSEKEEPING  - DO NOT CHANGE ANYTHING HERE
# ----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;            # display source of script if requested so
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
$pageName	= 'friendly_websites.php';
$pageVersion	= '3.20 2015-07-10';
#-----------------------------------------------------------------------
# 3.20 2015-07-15 release 2.8 version
#-----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
$sideArrCoop	= array();		# we store all befriended websites in this array
# ----------------------------------------------------------------------
# BELOW THIS LINE YOU  ADAPT AS YOU WISH
# ----------------------------------------------------------------------
#
# these are the friendly websites we want to display in the side area om every page
#
# A website starts with:	$sideArrCoop[] 	= array (  
#
# and ends with			);
#
# The fields to fill in are::
#
#	"show"	=>	true,	// if set to true the information for this website is shown, you can temporarily hide a site bu setting this to false
#	"name"	=>	"set to the name of the site",
#	"icon"	=>	"if you have a link to an icon of the site set it here",
#	"link"	=>	"this is the URL of the website",
#	"alt"	=>	"any normal text is OK to be typed here" 
#
#
$headString = '<p>'.langtransstr('Visit our other weathersites').':</p>'.PHP_EOL;	// this is the text printed just above the group of webnsites
#$headString = "";	// if you do not weant extra text before the website links, remove the comment mark on the first position
#
$doIcons	= false;		# true = icons will be displayed if "icon" not empty  |  false = no icons will be displayed
#
#	this is an example to use which will be displayd as it has "show" => true,
#
$sideArrCoop[] 	= array (
	"show"	=>	true,
	"name"	=>	"Template documentation <br /> and downloads",
	"icon"	=>	"",						// add image link here
	"link"	=>	"http://leuven-template.eu/index.php?lang=".$lang,
	"alt"	=>	"Documentation for the template used" 
); 
#
#	this is another example adapt as you like
#
$sideArrCoop[] = array (
	"show"	=>	true,
	"name"	=>	"And another site",
	"icon"	=>	"",
	"link"	=>	'http://www.weerstation-leuven.be/',
	"alt"	=>	"Website of another weatherstation" 
);
#
#	and the following examples will NOT be displayed as "show" => false,
#		again adapt as you like, do not forget to set: set "show" => true,
$sideArrCoop[] = array (
	"show"	=>	false,
	"name"	=>	"Template - WeatherDisplay version",
	"icon"	=>	"",
	"link"	=>	"http://www.weerstation-leuven.be/weather2/",
	"alt"	=>	"Website of another weatherstation" 
);
#
$sideArrCoop[] = array (
	"show"	=>	false,
	"name"	=>	"And another website",
	"icon"	=>	"",
	"link"	=>	"http://www.weerstation-leuven.be/weather2/",
	"alt"	=>	"Website of another weatherstation" 
);
