<?php   $errorMessages  = false;
#-----------------------------------------------------------------------------------------
# these are your settings for retrieving information from www.meteoalarm.eu/en_UK/0/0/xx123.html
#-----------------------------------------------------------------------------------------
#
$lang		= $SITE['lang'];                // default language - supported nl en fr de
#
$myfolder       = './';                         //##### set if include 'foldername/wrnWarningv3.php'; is used
#$myfolder      = './EUwarning/';               // EXAMPLE: include './EUwarning/uvforecastv3.php';
#
$warnings	= $SITE['warnings'];            // do we want to print warnings
$warnArea	= $SITE['warnArea'];		//      the area we want the warnings for
$warningGreen	= $SITE['warningGreen'];	//      do we print a message also when there are no warnings
$warningFailed	= true;			        //      do we print a message when the Metoalarm site is unavailable
#
$warnImg	= $SITE['imgDir'].'wrnImages/warn_';	// location and first part of the name for the small images of the warnings, f.i. full name warn_11.gif
#
$warnImgLarge   = $SITE['imgDir'].'wrnImagesLarge/';   // location for the large warnimages
#

$infoImg        = $SITE['imgDir'].'wrnImages/i_symbol.png';  // name + location of  the big I which is used in to click on for more information

$warnPage       = 'yes';                        // '' = we have no warning page of our own
$warnPage	= $SITE['warnPage'];	        //      exact name for leuven template
if ($warnPage === true || $warnPage === 'yes') {
        $warnPage = true;
        $warnLink = $SITE['pages']['wrnPrintWarnings'];
} else {$warnPage = false;
        $warnLink = '';
}
#
$warningDetail	= $SITE['warningDetail'];	// maximum number of warnings before we switch to one warning with automatic expansion
$warn1Box	= $SITE['warn1Box'];	        // true = put all warnings in one box;  false = one box for every warning
$warningsXtnd 	= $SITE['warningsXtnd'] ;	// false =  one line per warning, true = also the long text description 
#                                               //      this can be very large chunks of text, be carefull 
$warningSplit	= true;		                // if the large text has multiple languages and a "standard' character-string to divide them is used
#                                               //      only the current language is shown
#                                               //      not all countries support that, at the moment only AT BA  BE  HR  ME  NL  NO PO  RO  RS  SI
$printLevelText = false;                        // print standard texts such as 'The weather is potentially dangerous for outside activities.'

$charset	= $SITE['charset'];             // default character set for webpages, UTF-8 is the modern one, windows-1252 is the most used one
#
#
$cacheDir	= $SITE['cacheDir'];		 // directory to cache files     IMPORTANT setting
$timezone       = $SITE['tz'] ;                 // again important, valid for most of europe.  Do not change if you do not know what valid codes are.
#                                               //      check here:     http://php.net/manual/en/timezones.php
# ------------------------------------------------------------------------------
# these are the styles used for the boxes and warnings
# ------------------------------------------------------------------------------
#       colors used for the meteoalarm warnings
$white          = '#fff';               // Missing, insufficient, outdated or suspicious data.
$green          = '#99ff99';            // The weather provides no immediate danger.
$yellow         = '#FFFD38';            // The weather is potentially dangerous for outside activities.
$orange         = '#FDCA44';            // Potential danger to anyone.
$red            = '#FB101D';            // The weather is very dangerous.
#
#       styling for the box in which the warning is displayed
$styleBox       = 'margin: 5px; border-radius: 5px; border: 1px solid grey; border-bottom: 3px solid grey; color: black; text-align: center; overflow: hidden; min-height: 15px;';
$styleH3        = 'style = ""';
$styleH3        = 'class="blockHead"';  // example Leuven-template
# 
#       color for message box
$warnMsgColor   = $yellow;              // or any color you specify  with '#123456' or 'transparent'
# ------------------------------------------------------------------------------
#           REALLY , I mean it:         do not change below this point
# ------------------------------------------------------------------------------
#$extraCURL      ='http://include.hosting2go.nl/include.php?url=';
if (!isset ($extraCURL) ) {$extraCURL = '';}   
#
$warncolors     = array ('White' => $white, 'Green' => $green, 'Yellow' => $yellow, 'Orange' => $orange, 'Red' => $red);
$warnTable      = 'style= "border-collapse: collapse; width: 100%; color: black;text-align: center;"';
$wrnBox         = 'style= "'.$styleBox;
$euwarningURL	= 'http://www.meteoalarm.eu/en_UK/0/0/'.$warnArea.'.html';  
#                                               // here we go if visitor wants more info and if we do not have our own detailed warning page
$warndebug      = false;
$testFile       = '';
$cacheAllowed   = 900;
$cacheFile      = $cacheDir .'warning'.$warnArea;
# ------------------------------------------------------------------------------
# these are countries with multiple languages where they use a specific character string to split
#       the long string in different languages
$warnlangs['AT']        = array('en' => 0,'de' => 1);
$warnsplit['AT']        = 'deutsch:';
$warnlangs['BA']        = array('bo' => 0,'en' => 1);
$warnsplit['BA']        = '/';
$warnlangs['BE']        = array('nl' => 0,'fr' => 1);
$warnsplit['BE']        = 'FR :';
$warnlangs['FR']        = array('fr' => 0,'en' => 1);
$warnsplit['FR']        = 'english:';
$warnlangs['HR']        = array('cr' => 0,'en' => 1);
$warnsplit['HR']        = '/';
$warnlangs['ME']        = array('en' => 0,'me' => 1);
$warnsplit['ME']        = 'me:';
$warnlangs['NL']        = array('nl' => 0,'en' => 1);
$warnsplit['NL']        = array ('%lng=\\en_UK\\' ,'english:');             # 'english:';    # %lng=\\en_UK\\
$warnlangs['NO']        = array('no' => 0,'en' => 1);
$warnsplit['NO']        = '/';
$warnlangs['PL']        = array('en' => 0,'pl' => 1);
$warnsplit['PL']        = 'polski:';
$warnlangs['RO']        = array('en' => 0,'ro' => 1);
$warnsplit['RO']        = '/';
$warnlangs['RS']        = array('en' => 0,'sr' => 1);
$warnsplit['RS']        = 'sr:';
$warnlangs['SI']        = array('en' => 0,'sl' => 1);
$warnsplit['SI']        = 'sl:';
# ------------------------------------------------------------------------------
# these are strings to remove from the warnings for some countries
#
$warnclean['AT']        = 'français:';
$warnclean['BE']        = 'NL :';
$warnclean['FR']        = 'français: <br>           <br>';
$warnclean['ME']        = 'en:';
$warnclean['NL']        = array('nederlands:','<br>','%lng=\\en_','%lng=\\ne_NL\\' ,'UK\\');  # '%lng=\en_UK\'  '%lng=\ne_NL\'   
$warnclean['RS']        = 'en:';
$warnclean['PL']        = 'english:';
$warnclean['SI']        = 'en:';
#
$timeFormat             = 'd-m-Y H:i';		
$timeOnlyFormat         = 'H:i';
$warnCountry            = substr($warnArea,0,2);        // get country code from warnarea   
# ------------------------------------------------------------------------------
#       based on the displayed image this array contains the levels, descriptions and other image information 
$warninglarge = array();
$warninglarge['aw000']  = array ('level' => 'Green',  'info' => 'none',          'imgLarge' => 'aw000.jpg','img' => '01.gif');
$warninglarge['aw112']  = array ('level' => 'Yellow', 'info' => '',              'imgLarge' => 'aw112.jpg','img' => '02.gif');
#
$warninglarge['aw12']   = array ('level' => 'Yellow', 'info' => 'Wind',          'imgLarge' => 'aw12.jpg', 'img' => '11.gif');
$warninglarge['aw13']   = array ('level' => 'Orange', 'info' => 'Wind',          'imgLarge' => 'aw13.jpg', 'img' => '21.gif');
$warninglarge['aw14']   = array ('level' => 'Red',    'info' => 'Wind',          'imgLarge' => 'aw14.jpg', 'img' => '31.gif');
$warninglarge['aw22']   = array ('level' => 'Yellow', 'info' => 'Snow/Ice',      'imgLarge' => 'aw22.jpg', 'img' => '12.gif');
$warninglarge['aw23']   = array ('level' => 'Orange', 'info' => 'Snow/Ice',      'imgLarge' => 'aw23.jpg', 'img' => '22.gif');
$warninglarge['aw24']   = array ('level' => 'Red',    'info' => 'Snow/Ice',      'imgLarge' => 'aw24.jpg', 'img' => '32.gif');
$warninglarge['aw32']   = array ('level' => 'Yellow', 'info' => 'Thunderstorms', 'imgLarge' => 'aw32.jpg', 'img' => '13.gif');
$warninglarge['aw33']   = array ('level' => 'Orange', 'info' => 'Thunderstorms', 'imgLarge' => 'aw33.jpg', 'img' => '23.gif');
$warninglarge['aw34']   = array ('level' => 'Red',    'info' => 'Thunderstorms', 'imgLarge' => 'aw34.jpg', 'img' => '33.gif');
$warninglarge['aw42']   = array ('level' => 'Yellow', 'info' => 'Fog',           'imgLarge' => 'aw42.jpg', 'img' => '14.gif');
$warninglarge['aw43']   = array ('level' => 'Orange', 'info' => 'Fog',           'imgLarge' => 'aw43.jpg', 'img' => '24.gif');
$warninglarge['aw44']   = array ('level' => 'Red',    'info' => 'Fog',           'imgLarge' => 'aw44.jpg', 'img' => '34.gif');
$warninglarge['aw52']   = array ('level' => 'Yellow', 'info' => 'Extreme high temperature', 'imgLarge' => 'aw52.jpg', 'img' => '15.gif');
$warninglarge['aw53']   = array ('level' => 'Orange', 'info' => 'Extreme high temperature', 'imgLarge' => 'aw53.jpg', 'img' => '25.gif');
$warninglarge['aw54']   = array ('level' => 'Red',    'info' => 'Extreme high temperature', 'imgLarge' => 'aw54.jpg', 'img' => '35.gif');
$warninglarge['aw62']   = array ('level' => 'Yellow', 'info' => 'Extreme low temperature',  'imgLarge' => 'aw62.jpg', 'img' => '16.gif');
$warninglarge['aw63']   = array ('level' => 'Orange', 'info' => 'Extreme low temperature',  'imgLarge' => 'aw63.jpg', 'img' => '26.gif');
$warninglarge['aw64']   = array ('level' => 'Red',    'info' => 'Extreme low temperature',  'imgLarge' => 'aw64.jpg', 'img' => '36.gif');
$warninglarge['aw72']   = array ('level' => 'Yellow', 'info' => 'Coastal Event', 'imgLarge' => 'aw72.jpg', 'img' => '17.gif');
$warninglarge['aw73']   = array ('level' => 'Orange', 'info' => 'Coastal Event', 'imgLarge' => 'aw73.jpg', 'img' => '27.gif');
$warninglarge['aw74']   = array ('level' => 'Red',    'info' => 'Coastal Event', 'imgLarge' => 'aw74.jpg', 'img' => '37.gif');
$warninglarge['aw82']   = array ('level' => 'Yellow', 'info' => 'Forestfire',    'imgLarge' => 'aw82.jpg', 'img' => '18.gif');
$warninglarge['aw83']   = array ('level' => 'Orange', 'info' => 'Forestfire',    'imgLarge' => 'aw83.jpg', 'img' => '28.gif');
$warninglarge['aw84']   = array ('level' => 'Red',    'info' => 'Forestfire',    'imgLarge' => 'aw84.jpg', 'img' => '38.gif');
$warninglarge['aw92']   = array ('level' => 'Yellow', 'info' => 'Avalanches',    'imgLarge' => 'aw92.jpg', 'img' => '19.gif');
$warninglarge['aw93']   = array ('level' => 'Orange', 'info' => 'Avalanches',    'imgLarge' => 'aw93.jpg', 'img' => '29.gif');
$warninglarge['aw94']   = array ('level' => 'Red',    'info' => 'Avalanches',    'imgLarge' => 'aw94.jpg', 'img' => '39.gif');
$warninglarge['aw102']  = array ('level' => 'Yellow', 'info' => 'Rain',          'imgLarge' => 'aw102.jpg','img' => '20.gif');
$warninglarge['aw103']  = array ('level' => 'Orange', 'info' => 'Rain',          'imgLarge' => 'aw103.jpg','img' => '30.gif');
$warninglarge['aw104']  = array ('level' => 'Red',    'info' => 'Rain',          'imgLarge' => 'aw104.jpg','img' => '40.gif');
$warninglarge['aw122']  = array ('level' => 'Yellow', 'info' => 'Flood',          'imgLarge' => 'aw122.jpg','img' => '122.gif');
$warninglarge['aw123']  = array ('level' => 'Orange', 'info' => 'Flood',          'imgLarge' => 'aw123.jpg','img' => '123.gif');
$warninglarge['aw124']  = array ('level' => 'Red',    'info' => 'Flood',          'imgLarge' => 'aw124.jpg','img' => '124.gif');
$warninglarge['aw132']  = array ('level' => 'Yellow', 'info' => 'Rain-Flood',     'imgLarge' => 'aw122.jpg','img' => '132.gif');
$warninglarge['aw133']  = array ('level' => 'Orange', 'info' => 'Rain-Flood',     'imgLarge' => 'aw123.jpg','img' => '133.gif');
$warninglarge['aw134']  = array ('level' => 'Red',    'info' => 'Rain-Flood',     'imgLarge' => 'aw124.jpg','img' => '134.gif');
#
$eumessagebottom = iconv('UTF-8',$charset.'//TRANSLIT','
<p style="width: 90%; margin: 5px auto; padding: 10px;">
<a href="'.$euwarningURL.'" target="_blank">Warning data</a> courtesy of and Copyrighted © EUMETNET-METEOalarm (http://www.meteoalarm.eu/). Used with permission.
<br>Time delays between this website and the www.meteoalarm.eu website are possible, 
for the most up to date information about alert levels as published by the participating National Meteorological Services 
please use <a href="'.$euwarningURL.'" target="_blank">www.meteoalarm.eu</a></p>
');
#-----------------------------------------------------------------------
# set error reporting  
#-----------------------------------------------------------------------
if (isset($_REQUEST['debug'])){		// display error messages 
	$errorMessages = $warndebug  = true;
} 
if ($errorMessages) {
	ini_set('display_errors', 'On'); 
	error_reporting(E_ALL);
}
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   //--self downloader --
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
#---------------------------- ADAPTED FOR HOSTING2GO -------------------
# testing center extra texts in box / iconv translit / center multiple warning message / chrome redraw errors / new images / myfolder
# iconv problem multiple chgartacter sets in 1 website.
$pageName	= 'wrnWarningv3.php';
$pageVersion	= '3.06 2015-03-28';    // added FR split
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
// $wrnStrings will contain the warning message if needed
$wrnStrings     = '<!-- module '.$pageFile.' = '.$SITE['wsModules'][$pageFile].' -->'.PHP_EOL;
#-----------------------------------------------------------------------
# Load language files
#
if (isset($_REQUEST['lang'])){		// display error messages
        $string = trim($_REQUEST['lang']).'en';
        $lang   = substr($string,0,2);
} 
$warnlookup     = array ();
wrnloadlangstr($lang);
#-------------------------------------------------------------------------------
# Set correct timezone
if (!function_exists('date_default_timezone_set')) {
	 putenv("TZ=" . $timezone);
} else {
	 date_default_timezone_set($timezone);
}
/*
#-------------------------------------------------------------------------------
# First check of there you want your own warning to be displayed. fi: Site is in maintenanc
#-------------------------------------------------------------------------------
$wrnHead	= '';	        // this will contain our own message, if it is there
#
if ($warnMsg == true) {
        if ($warndebug) {echo '<!-- module '.$pageFile .' = check for own message  -->'.PHP_EOL;}
	if (file_exists($warnMsgTxt)) {
		$ownWarning     = file_get_contents($warnMsgTxt);
		if (strlen($ownWarning) >= 4 && substr($ownWarning,0,4) <> 'none') {
		        $ownWarning     = iconv('UTF-8',$charset.'//TRANSLIT', $ownWarning );
			$wrnHead .= '<div '.$wrnBox.'background-color: '.$warnMsgColor.';">'.PHP_EOL;
			$wrnHead .= $ownWarning.PHP_EOL;
			$wrnHead .= "</div>".PHP_EOL;
			if ($warndebug) {echo '<!-- module '.$pageFile .' = own message found - box created '.$wrnHead.'  -->'.PHP_EOL;} 		
		} // eo check warning file is to be displayed
	}  // eo warning file exist
}  // eo check own warning
*/
#
#-------------------------------------------------------------------------------
# Now check if we want to include warnings on every page 
#-------------------------------------------------------------------------------
if (!$warnings == true) { 
        if ($warndebug) {echo '<!-- module '.$pageFile .' = no warning needed - leave script  -->'.PHP_EOL;}
        return; 
}	// no weather warnings needed
#
$wrnStrings     .= '<!-- warnings and other info goes here  -->'.PHP_EOL;
#-------------------------------------------------------------------------------
#                       get the weather alarms 
#-------------------------------------------------------------------------------
$return 	= getMeteoAlarm ();
if (!$return) {
#
#               error no warning info could be loaded
#
        $wrnStrings     .= '<!-- ERROR no warnings  retrieved, script ends -->'.PHP_EOL;
        if ($warningFailed <> true) {
                echo               '<!-- ERROR no warnings  retrieved, script ends -->'.PHP_EOL;
                return;
        }
	$wrnStrings .= '<div '.$wrnBox.' background-color: white;">
<p>'.warntransstr('errorPart1').':
<a href="'.$euwarningURL.'?lang='.$lang.'" target="_blank">www.meteoalarm.eu</a>
 '.warntransstr('errorPart2').'.</p>
</div>'.PHP_EOL;
	return;
}
#------------------------------- split  alarm messages  ------------------------
if ($warningSplit == true && isset($warnsplit[$warnCountry])  ) {
//      set splitnr based on language
        $splitnr        = 999;
        if (is_array($warnsplit[$warnCountry]) ) {
                $splitchar      = $warnsplit[$warnCountry];}
        else {  $splitchar[0]   = $warnsplit[$warnCountry];}
 #       $splitchar      = $warnsplit[$warnCountry];
        if (isset ($warnlangs[$warnCountry][$lang]) ) {$splitnr = $warnlangs[$warnCountry][$lang];}
        for ($i = 0; $i < count($return); $i++){
            if (isset ($return[$i]['msg']) ) {
                $message        = $return[$i]['msg'];
                for ($n = 0; $n < count($splitchar); $n++) {
                        $needle = $splitchar[$n];
                        $pos    = strpos($message, $needle);
                        if ($pos > 0) {break;}
                }
                if (!$pos) {continue;}
                $splits = explode($needle,$return[$i]['msg']);
                if (isset ($splits[$splitnr]) ) {
                        $return[$i]['msg']      = $splits[$splitnr];
                }
            }
        }
}
#------------------------------- clean alarm messages  -------------------------
if (isset ($warnclean[$warnCountry]) ) {
        for ($i = 0; $i < count($return); $i++){
            if (isset ($return[$i]['msg']) ) {
# do the cleaning
                $from                   = $warnclean[$warnCountry]; 
                $to                     = ''; 
                $return[$i]['msg']      = str_replace ($from, $to, $return[$i]['msg']);
            }
        }
}
#----------------------mobi pages do their own warning display------------------
if (isset ($mobi) && $mobi) {return;}
#
#-----------------------------------------------------------------------------------------
if ($return[0]['msg'] == 'no warnings') {
#
#               there are no warnings more severe than green
#
        if ($warnPage) {  // if we have our own warningspage, make the green box for that page
                $warncolor      = ' background-color: '.$warncolors['Green'];
                $greenImage     = 'aw000.jpg';
                $ownpagehtml    = '<h3 '.$styleH3.'>'.warntransstr('Weather warnings').': '.$return[0]['area'].'</h3>'.PHP_EOL;
                $ownpagehtml    .= '<table style="width: 100%; border-collapse: collapse;">
<tbody><tr style="'.$warncolor.'">
   <td rowspan="1" style="width: 250px;"><img src="'.$warnImgLarge.$greenImage.'" alt=" "  style="width: 250px; height: 167px; margin: 5px; vertical-align: top;"></td>
   <td colspan= "1" style="text-align: center;">&nbsp;<b>'.warntransstr('GreenTxt').'</b></td>
</tr></tbody>
</table>'.$eumessagebottom.PHP_EOL; 
        }
        if (!$warningGreen) {  
#               there are no warnings more severe than green and we do not display them either
	        $wrnStrings .= '<!-- no warnings in order and no green box needed, script ends  -->'.PHP_EOL;
	}
	else {
#               there are no warnings more severe than green but we generate the small green box
                $wrnStrings .= '<!-- no warnings in order -->'.PHP_EOL;
                $warncolor      = ' background-color: '.$warncolors['Green'];
                $wrnStrings     .= '<div '.$wrnBox.$warncolor.'" >'.PHP_EOL;
                $wrnStrings     .= '    <span style="font-weight: bold;">'.warntransstr('GreenTxt').'&nbsp;&nbsp;';
                $wrnStrings     .= '<img src="'.$warnImg.'01.gif" alt="" style="vertical-align: bottom;" />&nbsp;&nbsp;';
                $wrnStrings     .= warntransstr('no warnings').'</span>'.PHP_EOL;
                $wrnStrings     .= '</div>'.PHP_EOL;
                $wrnStrings     .= '<!-- end warnings -->'.PHP_EOL;
        }
	return;
}
#----------------------now some housekeeping -----------------------------------
# convert character set
if ($charset <> 'UTF-8') {
        for ($i = 0; $i < count($return); $i++){
            if (isset ($return[$i]['msg']) ) {
                $return[$i]['msg'] = iconv('UTF-8', $charset.'//TRANSLIT', $return[$i]['msg']);
            }
        }
}
#
if ($warnPage) {	                        // do we have our own detailed warning page
	$wrnHref 	= '<a href="'.$warnLink.'&amp;lang='.$lang.$extraP.$skiptopText.'">';           //      then we send them there on the same page
} else {                                                        // otherwise we send visitors to the official warning site
        $wrnHref 	= '<a href="'.$euwarningURL.'" target="_blank">';	//  on a new page		
}
$arrayLevel['Green']	= 0;	                // these colors used by meteowarning.eu
$arrayLevel['Yellow']	= 1;
$arrayLevel['Orange']	= 2;
$arrayLevel['Red']	= 3;
#
$levelMax	= 0;				// max warning level of all warnings found
$color		= 'green';			//      corresponding color for this level
#
$txtFrom	= warntransstr('from')  .':&nbsp;';
$txtUntil	= warntransstr('until') .':&nbsp;';
$txtFor		= warntransstr('warning for').':&nbsp;';
$txtLevel	= warntransstr('level') .':&nbsp;';
#
# check max number of warnings before we compress them in 1 box / 1 line
$warningDetail  = intval ($warningDetail);
if ($warningDetail < 1) {
        $warningDetail = 1;
}
#-----------------------------------------------------------------------------------------
# do processing for each warning and store texts in array
#
$wrnRows	= array ();			// every warning is first printed to array	
$cnt		= count($return);	        // number of warnings
for ($i = 0; $i < $cnt; $i++) {	// for every warning
#       first we clean  
	$level	= $return[$i]['level'];
	if (isset ($arrayLevel[$level]) ) {	// do we know this level coding
		$levelFound		= $arrayLevel[$level];
		if ($levelFound > $levelMax) {
			$levelMax	= $levelFound;	// max level for all warnings
			$color		= $level;	// corresponding color for max level
		}
	}	
	if ($printLevelText == false) {			// can we skip those standard texts
		$extraText	= '';
	} else {
		$extraText	= '<br />'.warntransstr($return[$i]['level'].'Txt');
	}
	$warningText	= warntransstr($return[$i]['types']);
	if ($return[$i]['types'] == 'Thunderstorms') 					{$SITE['wrnLightning']	= true; $wrnThun = $return[$i]['types'];}
	if ($return[$i]['types'] == 'Rain'  || $return[$i]['types'] == 'Snow/Ice') 	{$SITE['wrnRain'] 	= true; $wrnRain = $return[$i]['types'];}
	$wrnRows[$i]['txt']	= '  <td>'.'<img src="'.$warnImg.$return[$i]['img'].'" title="'.$return[$i]['msg'].'" alt="'.$warningText.'" style="vertical-align: bottom;"/></td>'.PHP_EOL;
	$wrnRows[$i]['txt']    .= '  <td>'.$txtFrom.$return[$i]['from'].'</td>'.PHP_EOL;
	$wrnRows[$i]['txt']    .= '  <td>'.$txtUntil.$return[$i]['until'].'</td>'.PHP_EOL;
	$wrnRows[$i]['txt']    .= '  <td>'.$txtFor.'<span style="font-weight: bold;">'.$warningText.'</span>&nbsp;'.
			$txtLevel.'<span style="font-weight: bold;">'.
			warntransstr($level).'</span>'.$extraText.'</td>'.PHP_EOL;
	$wrnRows[$i]['txt']    .= '  <td>'.$wrnHref.'<img src="'.$infoImg.'" alt="info button" style="vertical-align: bottom; width: 20px;"/></a></td>'.PHP_EOL;
} // eo for every warning
#-----------------------------------------------------------------------------------------
#               start generating the html output
#
#-----------------------------------------------------------------------------------------
$ownpagehtml    = '';
#               if we have our own detailed warning page
if ($warnPage) {
#                       we have to create the info block for our own warnpage
        $ownpagehtml    .= '<h3 '.$styleH3.'>'.warntransstr('Weather warnings').': '.$return[0]['area'].'</h3>'.PHP_EOL;
        $ownpagehtml    .= '<table style="width: 100%; border-collapse: collapse;">
<tbody>'.PHP_EOL;
        for ($i = 0; $i < $cnt; $i++) {	// for every warning
        $level	        = $return[$i]['level'];
        $backcolor      = $warncolors[$level];
        $warningText	= warntransstr($return[$i]['types']);
                $ownpagehtml    .= '<tr style="background-color: '.$backcolor.'">
    <td rowspan="3" style="width: 250px;">
        <img src="'.$warnImgLarge.$return[$i]['imglarge'].'" alt=" " 
                style="width: 250px; height: 167px; margin: 5px; vertical-align: top;">
    </td>
    <td colspan= "2" style="text-align: left;">
        <span style="margin: 5px 5px 0px 5px; display: block;"><b>
        '.$txtFrom.'</b>'.$return[$i]['from'].'&nbsp;&nbsp;&nbsp;<b>'.$txtUntil.'</b>'.$return[$i]['until'].'</span>
    </td>
</tr>
<tr style="background-color: '.$backcolor.'">
    <td style="text-align: left; "><span style="margin-left: 5px;"><b>'.$warningText.'&nbsp;</b></span></td>
    <td style="text-align: right;"><span style="margin-right: 5px;"> '.$txtLevel.': <b>'.warntransstr($level).'&nbsp;</b></span></td>
</tr>
<tr style="background-color: '.$backcolor.'">
    <td colspan= "2" style="text-align: left; ">
    <p style="margin: 5px 5px 15px 5px; padding: 5px 0px 5px 5px; background-color: white;">
'.$return[$i]['msgOriginal'].'    </p>
    </td>
</tr>
<tr style="height: 4px; background-color: transparent;"><td colspan="2"> </td></tr>'.PHP_EOL;  
        }
        $ownpagehtml    .= '</tbody>
</table>'.$eumessagebottom.PHP_EOL; 
}
#-----------------------------------------------------------------------------------------
#               one compressed box with 1 line for all warnings
#                       generated when  max allowed is less than nummber of warnings
#
if ($warningDetail  < $cnt) {
	$firstText	= warntransstr('multiplewarnings');
	$secondText	= warntransstr('checkhere');
	$warncolor      = ' background-color: '.$warncolors[$color];
        $wrnStrings     .= '<!-- box with 1 line for all warnings plus javascript extension -->'.PHP_EOL;
	$wrnStrings	.= '<!-- compressed warnings -->'.PHP_EOL;
	$wrnStrings     .= '<div '.$wrnBox.$warncolor.'" >'.PHP_EOL;
	$wrnStrings     .= '<span>'.$firstText.'&nbsp;<a href="javascript:hideshow(document.getElementById(\'warnExtra\'))">';
	$wrnStrings     .= '<img src="'.$infoImg.'" alt=" " style="vertical-align: bottom; padding: 2px; width: 18px;"/></a>&nbsp;';
	$wrnStrings     .= $secondText.'</span>'.PHP_EOL;
	$wrnStrings     .= '</div>
<script type="text/javascript">
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>'.PHP_EOL;
	$wrnStrings     .= '<div id="warnExtra" style="display:none;">'.PHP_EOL;
	for ($i = 0; $i < $cnt; $i++) {	
	        $warncolor      = ' background-color: '.$warncolors[$return[$i]['level'] ];
	        $wrnStrings     .= '<div '.$wrnBox.$warncolor.'" >'.PHP_EOL;
	        $wrnStrings     .= '<table '.$warnTable.'>'.PHP_EOL;
		$wrnStrings     .= '<tr>'.PHP_EOL.$wrnRows[$i]['txt'].'</tr>'.PHP_EOL;
		if ($warningsXtnd) {
			$wrnStrings .= '<tr><td colspan = "5" style="text-align: center;">'.$return[$i]['msg'].'</td></tr>'.PHP_EOL;
		}
		$wrnStrings .= '</table>'.PHP_EOL;
		$wrnStrings .= '</div>'.PHP_EOL;
	}  // eo for loop every warning	
	$wrnStrings     .= '</div>'.PHP_EOL;
	$wrnStrings     .= '<!-- eo compressed warnings -->'.PHP_EOL;
	return;
}
#-----------------------------------------------------------------------------------------
#               generate all information one warning per box
#                       generated when no combination box is set 
#
if (!isset ($warn1Box) || $warn1Box == false) {
        $wrnStrings     .= '<!-- one warning per box -->'.PHP_EOL;
	for ($i = 0; $i < $cnt; $i++) {	
	        $warncolor      = ' background-color: '.$warncolors[$return[$i]['level'] ];
	        $wrnStrings     .= '<div '.$wrnBox.$warncolor.'" >'.PHP_EOL;
		$wrnStrings     .= '<table '.$warnTable.'>'.PHP_EOL;
		$wrnStrings     .= '<tr>'.PHP_EOL.$wrnRows[$i]['txt'].'</tr>'.PHP_EOL;
		if ($warningsXtnd) {
			$wrnStrings .= '<tr><td colspan = "5" style="text-align: center;">'.$return[$i]['msg']."</td></tr>".PHP_EOL;
		}
		$wrnStrings     .= '</table>'.PHP_EOL;
		$wrnStrings     .= '</div>'.PHP_EOL;
	}  // eo for loop every warning	
	$wrnStrings .= '<!-- end warnings -->'.PHP_EOL;
	return;
}

#--------------------------------------------------------------------------------------------------
#               default: when no other setting was executed: 
#                       generate one box with all warnings
#
$wrnStrings     .= '<!-- one box with all warnings -->'.PHP_EOL;
$wrnStrings     .= '<div '.$wrnBox.'" >'.PHP_EOL;
$wrnStrings     .= '<table '.$warnTable.'>'.PHP_EOL;
for ($i = 0; $i < $cnt; $i++) {
        $warncolor      = ' background-color: '.$warncolors[$return[$i]['level'] ];	
	$wrnStrings .= '<tr style="'.$warncolor.'">'.PHP_EOL.$wrnRows[$i]['txt'].'</tr>'.PHP_EOL;  // no extended 
	if ($warningsXtnd) {
		$wrnStrings .= '<tr style="'.$warncolor.'"><td colspan = "5" style="text-align: center;">'.$return[$i]['msg']."</td></tr>".PHP_EOL;
	}
}  // eo for loop every warning	
$wrnStrings     .= '</table>'.PHP_EOL;
$wrnStrings     .= '</div>'.PHP_EOL;
$wrnStrings     .= '<!-- end warnings -->'.PHP_EOL;
return; 
#--------------------------------------------------------------------------------------------------
# functions	
#--------------------------------------------------------------------------------------------------
function getMeteoAlarm() {
	global  $wrnStrings, $cacheAllowed, $cacheFile, $warnArea, $timeOffset, $timeFormat, $timeOnlyFormat, 
	        $charset, $warninglarge, $warncolors;
	# check if data (for this location) is in cache
	if ($cacheFile <> ''){
		$returnArray    = wrnloadFromCache($cacheFile);  	// load from cache returns data only when its data is valid
		if (!empty($returnArray)) {		// if data is in cache and valid return data to calling program
			return $returnArray;
		}  // eo return to calling program
	}  // eo check cache
        #----------------------------------------------------------------------------------------------
        # combine user constants and input (1)location (2)units for temp etc  to required url
        #----------------------------------------------------------------------------------------------
        global $urlPart;                // www.meteoalarm.eu/en_UK/0/0/BE004.html
        $urlPart = array(
		'http://www.meteoalarm.eu/en_UK/0/0/',
		$warnArea,
		'.html'
        );
	$fullUrl		= '';
	for ($i = 0; $i < count($urlPart); $i++){
		$fullUrl .= $urlPart[$i];
	}
	#----------------------------------------------------------------------------------------------
	$rawData        = wrnmakeRequest($fullUrl);
	if ($rawData == ''){
		$upped          = 2 * $cacheAllowed;
		$wrnStrings     .= '<!-- upped cachetime from '.$cacheAllowed.' to '.$upped.' as no valid data was retrieved -->'.PHP_EOL;
		$cacheAllowed   = $upped;
		$returnArray    = wrnloadFromCache($cacheFile);  	// load from cache returns data only when its data is valid
                if (!empty($returnArray)) {				// if data is in cache and valid return data to calling program
                        return $returnArray;
                }  // eo return to calling program	
	        return false;
        }
        $replace	= array ("\n", "\t");
        $rawData        = str_replace($replace,'',$rawData);
        $end            = strlen($rawData);
        $strname        = '<h1>';
        $posh1          = strpos($rawData, $strname,0);
        if ($posh1 == false || $posh1 > $end)      {echo 'error no area found'; exit;}
        $posh1          = $posh1+4;
        $posend         = strpos($rawData, '<',$posh1);
        $length         = $posend - $posh1;
        $string         = substr($rawData, $posh1 , $length);
        list ($none, $area) = explode (':',$string)  ;
        $returnArray[0]['area'] =  $area; #iconv('UTF-8', $charset.'//TRANSLIT', $area);     
        $strNoWarn	= 'aw000';
        $strStart	= '<div class="warnbox';	// start of first warning
        $strFrom	= '<b>valid from</b>';
        $strUntil	= '<b>Until</b>';
        $pos            = strpos($rawData, $strStart,0);
        if ($pos == false || $pos > $end)      {echo 'error no start text found '; exit;}
        $pos            = $pos + strlen($strStart);
        $moreData       = true;
        $cntWarn        = 0;

        while ($moreData) {
                $pos            = strpos($rawData, '<img',$pos);
                if ($pos == false || $pos > $end)      {echo 'error no img found in data'; exit;}
                $imgstart       = $pos + 4;
                $pos            = strpos($rawData, '>',$pos);
                if ($pos == false || $pos > $end)      {echo 'error end of img  not found in data'; exit;}
                $length         = $pos - $imgstart;
                $imgtxt         = substr( $rawData, $imgstart, $length);
                $arr            = explode('/',$imgtxt);
                $last           = count($arr)-1;
                $imgnr          = $arr[$last]; 
                $arr            = explode ('.',$imgnr);
                $imgcode        = $arr[0];
 #
                if ($strNoWarn == $imgcode) {     	// no data this time
                        $returnArray[0]['msg'] = 'no warnings';
                        wrnwriteToCache($returnArray);
                        return $returnArray;
                }
#
                $search         = $strFrom;
                $pos            = strpos($rawData, $search,$pos);
                if ($pos == false || $pos > $end)      {echo 'error from date not found in data'; exit;}
                $pos            = $pos + strlen($search);
                $fromstart      = $pos;
                $search         = $strUntil;
                $pos            = strpos($rawData, $search,$pos);
                if ($pos == false || $pos > $end)      {echo 'error until date not found in data'; exit;}
                $length         = $pos -  $fromstart;
                $fromText       = trim(substr( $rawData, $fromstart, $length)); 
 #               echo '$fromText = '.$fromText.PHP_EOL;
                $pos            = $pos + strlen($search);
                $untilstart     = $pos;
                $search         = '</div>';
                $pos            = strpos($rawData, $search,$pos);
                if ($pos == false || $pos > $end)      {echo 'error until date not found in data'; exit;}
                $length         = $pos -  $untilstart;
                $untilText      = trim(substr( $rawData, $untilstart, $length)); 
#                echo '$untilText = '.$fromText.PHP_EOL;
                $search         = '<div class="text">';
                $pos            = strpos($rawData, $search,$pos);
                if ($pos == false || $pos > $end)      {echo 'error text not found in data'; exit;}
                $pos            = $pos + strlen($search);
                $textstart      = $pos;
                $search         = '</div>';
                $pos            = strpos($rawData, $search,$pos);
                if ($pos == false || $pos > $end)      {echo 'error text not found in data'; exit;}
                $length         = $pos -   $textstart;
                $warntext       = substr( $rawData, $textstart, $length); 
#
                $level                                  = $warninglarge[$imgcode]['level']; 
                $returnArray[$cntWarn]['level']         = $level;               // Orange
                $returnArray[$cntWarn]['color']         = $warncolors[$level];  // "#FECC33"
#       clean dates
        if (date('I')) {
                $toTZ           = array ('CEST');
                $frTZ           = array ('CET');
                $fromText       = str_replace ($frTZ , $toTZ, $fromText);
                $untilText      = str_replace ($frTZ , $toTZ, $untilText);
        }
                $unixFrom       = strtotime ($fromText);
                $unixUntil	= strtotime ($untilText);
                $returnArray[$cntWarn]['from']          = date ($timeFormat, $unixFrom );						
#       less text if start and until day are the same
                if (date ('Ymd' , $unixFrom ) <> date ('Ymd' , $unixUntil) ) {
                        $returnArray[$cntWarn]['until']	= date ($timeFormat, $unixUntil );
                } else {
                        $returnArray[$cntWarn]['until']	= date ($timeOnlyFormat, $unixUntil );
                }
                $returnArray[$cntWarn]['types']         = $warninglarge[$imgcode]['info'];      // Rain
                $returnArray[$cntWarn]['img']           = $warninglarge[$imgcode]['img'];       // 30.gif
                $returnArray[$cntWarn]['imglarge']      = $warninglarge[$imgcode]['imgLarge'];  // aw34.jpg
                if ($charset <> 'UTF-8') {
                        $warntext       = $warntext; #iconv('UTF-8', $charset.'//TRANSLIT', $warntext);
                }
                $returnArray[$cntWarn]['msg']           = $warntext;   // bla bla 
                $returnArray[$cntWarn]['msgOriginal']   = $warntext;   // before cleaning and splitting up 
                $pos            = strpos($rawData, $strStart,$pos);
                if ($pos == false) {$moreData = false;}
                $cntWarn++;
        }
#        print_r ($returnArray);
#        echo 'halt'.$wrnStrings; exit;

        wrnwriteToCache($returnArray);
        $rawData  = '';
        return $returnArray;
}  // eof getMeteoAlarm

function wrnloadFromCache($cacheFile){
        global $wrnStrings, $cacheAllowed;
        if (!file_exists($cacheFile)){
                $wrnStrings .= "<!-- Severe weatherdata ($cacheFile) not found in cache -->".PHP_EOL;
                return '';
        }
        if (isset($_REQUEST['force']) && $_REQUEST['force'] == 'alarm') {
                $wrnStrings .= "<!-- $cacheFile not used, force was set -->".PHP_EOL;
                return '';                
        }
        $file_time      = filemtime($cacheFile);
        $now            = time();
        $diff           = ($now     -   $file_time);
        $wrnStrings     .=  "<!-- Severe weatherdata ($cacheFile)
        cache time   = ".date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $cacheAllowed (seconds) -->".PHP_EOL;	
        if ($diff <= $cacheAllowed){
                $wrnStrings .= "<!-- Severe weatherdata ($cacheFile) loaded from cache -->".PHP_EOL;
                $returnArray =  unserialize(file_get_contents($cacheFile));
                return $returnArray;
        }
} // eof wrnloadFromCache
	
function wrnwriteToCache($data){
        global $wrnStrings, $cacheDir, $cacheFile, $wrnBox;
        if ($cacheDir   == '')  {
                $wrnStrings     .= "<!-- WARNING  no cache specified for severe weatherdata  STRONGLY ADVISED TO RECTIFY THAT -->".PHP_EOL; return;
        }
        if (!file_exists($cacheDir)){
                mkdir($cacheDir, 0777);   // attempt to make the cache dir
        }
        if (file_put_contents($cacheFile, serialize($data))){   
                $wrnStrings .= "<!-- Severe weatherdata ($cacheFile) saved to cache  -->".PHP_EOL;
                return;
        }
        $wrnStrings .= '<div '.$wrnBox.' background-color: white;">
<p>'.warntransstr('warnPart1').' <i>'.$cacheFile.'</i> '.warntransstr('warnPart2').'
</div>'.PHP_EOL;
        return;
} // eof wrnwriteToCache

function wrnmakeRequest($fullUrl){
        global $wrnStrings, $testFile, $extraCURL;
        if (isset($testFile) && $testFile <> '') {
                $wrnStrings     .= "<!-- TESTING   Severe weatherdata loaded from test file: $testFile  -->".PHP_EOL;
                $rawData        = file_get_contents($testFile);
        } 
        else {
                $wrnStrings .= "<!-- Severe weatherdata loaded from $fullUrl  -->".PHP_EOL;
                IF ($extraCURL <> '') {
                        $replFrom       = array ('?','&');
                        $replTo         = array ('%3F','%26');
                        $fullUrl        = str_replace ($replFrom,$replTo,$fullUrl);
                        $fullUrl        = $extraCURL.$fullUrl;
                }
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_URL, $fullUrl);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
                $rawData = curl_exec ($ch);
                curl_close ($ch);
        }
        if (empty($rawData)){
                $wrnStrings .= "<!-- ERROR Severe weatherdata empty,($fullUrl)  could not be loaded  -->".PHP_EOL;
                return '';
        }
        $dataError      = strpos($rawData, 'Service Unavailable' ,0);	
        $dataDetect     = strpos($rawData, 'InformationToHelpDiagnose:' ,0);
        if($dataError > 0  || $dataDetect > 0) {   // error string found
                $wrnStrings .= "<!-- ERROR Severe weatherdata ($fullUrl) Service Unavailable  -->".PHP_EOL;
                return '';
        }
        $searchOK       = '<div class="warnbox';
        $dataOK         = strpos($rawData, $searchOK ,0);	
        if(!$dataOK) {   // no good data found
                $wrnStrings .= "<!-- ERROR Severe weatherdata ($fullUrl) Service returns no good data -->".PHP_EOL;
                return '';
        }		
        return $rawData;	
} // eof wrnmakeRequest
# -----------------------  language functions ----------------------------------
function warntransstr ($text) {
        global $warnlookup;
        if (isset ($warnlookup[$text]) ) {
                return $warnlookup[$text];
        } else {
                return $text;
        }
} // eof warntransstr

# -----------------------  language data   -----------------------------
function wrnloadlangstr($lang) {
        global $charset, $warnlookup;
if     ($lang == 'nl') {
# -------------------------------------- Nederlands / Dutch texts ------
$warnlookup['Weather warnings'] ='Weerwaarschuwingen';
$warnlookup['from']	        ='van';
$warnlookup['until']	        ='tot';
$warnlookup['level']	        ='niveau';
$warnlookup['warning for']	='waarschuwing voor';
#
$warnlookup['no warnings']	='Er zijn geen waarschuwingen afgegeven.';
#
$warnlookup['Wind']	        ='Wind';
$warnlookup['Snow/Ice']	        ='Sneeuw/IJzel/Bevriezing';
$warnlookup['Thunderstorms']	='Onweer';
$warnlookup['Fog']	        ='Mist';
$warnlookup['Extreme high temperature']	='Extreme hitte';
$warnlookup['Extreme low temperature']	='Extreme koude';
$warnlookup['Coastal Event']	='Kustbedreiging';
$warnlookup['Forestfire']	='Bos- en heidebranden';
$warnlookup['Avalanches']	='Lawines';
$warnlookup['Rain']	        ='Regen';
$warnlookup['Flood']	        ='Overstromingen';
$warnlookup['Rain-flood']	='Overstromingen';
#
$warnlookup['White']	        ='Wit';
$warnlookup['Green']	        ='Groen';
$warnlookup['Yellow']	        ='Geel';
$warnlookup['Orange']	        ='Oranje';
$warnlookup['Red']	        ='Rood';
#
$warnlookup['WhiteTxt']	        ='Ontbrekende-, onvoldoende-, verouderde- of verdachte gegevens.';
$warnlookup['GreenTxt']	        ='Het weer levert geen direct gevaar op.';
$warnlookup['YellowTxt']	='Het weer is in aanleg gevaarlijk.';
$warnlookup['OrangeTxt']	='Het weer is gevaarlijk.';
$warnlookup['RedTxt']	        ='Het weer is zeer gevaarlijk.';
#
$warnlookup['multiplewarnings']	='Er zijn meerdere weer-waarschuwingen';
$warnlookup['checkhere']	='Klik hier voor meer informatie';
$warnlookup['errorPart1']	='We konden geen weer-waarchuwingen ophalen. Ga rechtstreeks naar de Meteoalarm site ';
$warnlookup['errorPart2']	='en controleer daar ';
$warnlookup['warnPart1']	='Kan de gegevens  ';
$warnlookup['warnPart2']	=' niet opslaan in de cache. Zorg ervoor dat de cache bestaat en beschrijjfbaar is!';
} # --------------------- Nederlands / Dutch texts ------ END OF -------
elseif ($lang == 'fr') {
# ----------------------------------------- French / FranÃ§ais texts ----
$warnlookup['Weather warnings'] ='Vigilance météorologique';
$warnlookup['from']	        ='Valable du';
$warnlookup['until']	        ='Au';
$warnlookup['level']	        ='Niveau';
$warnlookup['warning for']	='Avertissement pour';
#
$warnlookup['no warnings']	="Il n'y a aucun avertissement";
#
$warnlookup['Wind']	        ='Vent';
$warnlookup['Snow/Ice']	        ='Neige/verglas';
$warnlookup['Thunderstorms']	='Orages';
$warnlookup['Fog']	        ='Brouillard';
$warnlookup['Extreme high temperature']	='Chaleur extrême';
$warnlookup['Extreme low temperature']	='Froid extrême';
$warnlookup['Coastal Event']	='Vagues-Submersion';
$warnlookup['Forestfire']	='Feux de forêt';
$warnlookup['Avalanches']	='Avalanches';
$warnlookup['Rain']	        ='Pluies';
$warnlookup['Flood']	        ='Crues';
$warnlookup['Rain-flood']	='Fortes précipitations et/ou inondations';
#
$warnlookup['White']	        ='Blanc';
$warnlookup['Green']	        ='Vert';
$warnlookup['Yellow']	        ='Jaune';
$warnlookup['Orange']	        ='Orange';
$warnlookup['Red']	        ='Rouge';
#
$warnlookup['WhiteTxt']	        ='Données manquantes, insuffisantes, obsolètes ou douteuses';
$warnlookup['GreenTxt']	        ='Pas de vigilance météorologique particulière.';
$warnlookup['YellowTxt']	='Soyez attentifs; si vous pratiquez des activités sensibles au risque météorologique ou exposées aux crues.';
$warnlookup['OrangeTxt']	='Soyez très vigilants : des phénomènes dangereux sont prévus. ';
$warnlookup['RedTxt']	        ="Une vigilance absolue s'impose.";
#
$warnlookup['multiplewarnings']	='Plusieurs avertissements en vigueur';
$warnlookup['checkhere']	="plus d'informations";
$warnlookup['errorPart1']	="Il s'agit d'un problème de communication. Allez directement sur le site Meteoalarm ";
$warnlookup['errorPart2']	=' et vérifier les avertissements météorologiques.';
$warnlookup['warnPart1']	= 'Could not save data';
$warnlookup['warnPart2']	= 'Please make sure your cache directory exists and is writable';
} # --------------------- French / FranÃ§ais texts ------- END OF -------
elseif ($lang == 'en') {
# ----------------------------------------- English  texts -------------
$warnlookup['Weather warnings'] ='Weather warnings';
$warnlookup['from']	        ='From';
$warnlookup['until']	        ='Until';
$warnlookup['level']	        ='Level';
$warnlookup['warning for']	='warning for';
#
$warnlookup['no warnings']	='There are no Weather-warnings';
#
$warnlookup['Wind']	        ='Wind';
$warnlookup['Snow/Ice']	        ='Snow/Ice';
$warnlookup['Thunderstorms']	='Thunderstorms';
$warnlookup['Fog']	        ='Fog';
$warnlookup['Extreme high temperature']	='Extreme high temperature';
$warnlookup['Extreme low temperature']	='Extreme low temperature';
$warnlookup['Coastal Event']	='Coastal Event';
$warnlookup['Forestfire']	='Forest fire';
$warnlookup['Avalanches']	='Avalanches';
$warnlookup['Rain']	        ='Rain';
$warnlookup['Flood']	        ='Flood';
$warnlookup['Rain-flood']	='Rain-flood';
#
$warnlookup['White']	        ='White';
$warnlookup['Green']	        ='Green';
$warnlookup['Yellow']	        ='Yellow';
$warnlookup['Orange']	        ='Orange';
$warnlookup['Red']	        ='Red';
#
$warnlookup['WhiteTxt']	        ='Missing, insufficient, outdated or suspicious data.';
$warnlookup['GreenTxt']	        ='No particular awareness of the weather is required.';
$warnlookup['YellowTxt']	='The weather is potentially dangerous.';
$warnlookup['OrangeTxt']	='The weather is dangerous. ';
$warnlookup['RedTxt']	        ='The weather is very dangerous.';
#
$warnlookup['multiplewarnings']	='There are multiple Weather-warnings';
$warnlookup['checkhere']	='Click here for more information';
$warnlookup['errorPart1']	='There is a communication problem. Go directly to the Meteoalarm site ';
$warnlookup['errorPart2']	='and check the Weather-warnings there ';
$warnlookup['warnPart1']	= 'Could not save data';
$warnlookup['warnPart2']	='Please make sure your cache directory exists and is writable';
} # ------------------------------- English texts ------- END OF -------
elseif ($lang == 'de') {
# ----------------------------------------- German / Deutsche texts ----
$warnlookup['Weather warnings'] ='Wetter-Warnungen';
$warnlookup['from']	        ='Gültig von';
$warnlookup['until']	        ='bis';
$warnlookup['level']	        ='Gefahrenstufe';
$warnlookup['warning for']	='Warnung für';
#
$warnlookup['no warnings']	='Es gibt keine Warnungen.';
#
$warnlookup['Wind']	        ='Wind';
$warnlookup['Snow/Ice']	        ='Schnee/Eis';
$warnlookup['Thunderstorms']	='Gewitter';
$warnlookup['Fog']	        ='Nebel';
$warnlookup['Extreme high temperature']	='Extrem hohe Temperatur';
$warnlookup['Extreme low temperature']	='Extrem niedrige Temperatur';
$warnlookup['Coastal Event']	='Küstenereignis';
$warnlookup['Forestfire']	='Waldbrand';
$warnlookup['Avalanches']	='Lawinen';
$warnlookup['Rain']	        ='Regen';
$warnlookup['Flood']	        ='Hochwasser';
$warnlookup['Rain-flood']	='Regen/Hochwasser';
#
$warnlookup['White']	        ='Weiss';
$warnlookup['Green']	        ='Grün';
$warnlookup['Yellow']	        ='Gelb';
$warnlookup['Orange']	        ='Orange';
$warnlookup['Red']	        ='Rot';
#
$warnlookup['WhiteTxt']	        ='Fehlende, unvollständige, veraltete oder nicht vertrauenswürdige Daten.';
$warnlookup['GreenTxt']	        ='Es ist keine erhöhte Aufmerksamkeit aufgrund des Wetters notwendig.';
$warnlookup['YellowTxt']	='Das Wetter ist potenziell gefährlich.';
$warnlookup['OrangeTxt']	='Das Wetter ist gefährlich.';
$warnlookup['RedTxt']	        ='Das Wetter ist sehr gefährlich.';
#
$warnlookup['multiplewarnings']	='Mehrere Warnungen in Kraft';
$warnlookup['checkhere']	='Mehr Informationen';
$warnlookup['errorPart1']	='Es liegt ein Kommunikationsproblem . Gehen Sie direkt zur Website Meteoalarm ';
$warnlookup['errorPart2']	=' und überprüfen Sie die Wetterwarnungen.';
$warnlookup['warnPart1']	= 'Could not save data';
$warnlookup['warnPart2']	='Please make sure your cache directory exists and is writable';
} # -------------------------German / Deutsche texts ---- END OF -------
else {
# ----------------------------------------- New  / unknown texts -------
$warnlookup['from']	        ="From";
$warnlookup['until']	        ="Until";
$warnlookup['level']	        ="Level";
$warnlookup['warning for']	="warning for";
#
$warnlookup['no warnings']	="There are no Weather-warnings";
#
$warnlookup['Wind']	        ="Wind";
$warnlookup['Snow/Ice']	        ="Snow/Ice";
$warnlookup['Thunderstorms']	="Thunderstorms";
$warnlookup['Fog']	        ="Fog";
$warnlookup['Extreme high temperature']	="Extreme high temperature";
$warnlookup['Extreme low temperature']	="Extreme low temperature";
$warnlookup['Coastal Event']	="Coastal Event";
$warnlookup['Forestfire']	="Forest fire";
$warnlookup['Avalanches']	="Avalanches";
$warnlookup['Rain']	        ="Rain";
$warnlookup['Flood']	        ="Flood";
$warnlookup['Rain-flood']	="Rain-flood";
#
$warnlookup['White']	        ="White";
$warnlookup['Green']	        ="Green";
$warnlookup['Yellow']	        ="Yellow";
$warnlookup['Orange']	        ="Orange";
$warnlookup['Red']	        ="Red";
#
$warnlookup['WhiteTxt']	        ="Missing, insufficient, outdated or suspicious data.";
$warnlookup['GreenTxt']	        ="No particular awareness of the weather is required.";
$warnlookup['YellowTxt']	="The weather is potentially dangerous.";
$warnlookup['OrangeTxt']	="The weather is dangerous. ";
$warnlookup['RedTxt']	        ="The weather is very dangerous.";
#
$warnlookup['multiplewarnings']	="There are multiple Weather-warnings";
$warnlookup['checkhere']	="Click here for more information";
$warnlookup['errorPart1']	="There is a communication problem. Go directly to the Meteoalarm site ";
$warnlookup['errorPart2']	="and check the Weather-warnings there ";
$warnlookup['warnPart1']	= 'Could not save data';
$warnlookup['warnPart2']	='Please make sure your cache directory exists and is writable';

} # --------------------- New  / unknown texts ---------- END OF -------
#
        if ($charset <> 'UTF-8') {
                foreach ($warnlookup as $key => $translation) { 
                        $translation            = iconv('UTF-8', $charset.'//TRANSLIT', $translation);
                        $warnlookup[$key]       = $translation;
                }  // end foreach entry in translation array              
        } // eo not utf-8
        return $warnlookup;
} // eof wrnloadlangstr
?>