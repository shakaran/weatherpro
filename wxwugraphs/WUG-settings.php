<?php  if (!isset ($SITE) ) {$SITE=array();  include_once ('../wsLoadSettings.php');ini_set('display_errors', 'Off');}
################################################################################
# All WUG pages are in UTF-8 format !!!!
# do not edit this and other files in Windows notepad - This file must be without a BOM
# more info eg: http://htmlpurifier.org/docs/enduser-utf8.html#migrate-editor
# For editing you can try eg: http://www.pspad.com
# or http://notepad-plus-plus.org 
################################################################################
/**
 * Project:   WU GRAPHS
 * Module:    WUG-settings.php 
 * Copyright: (C) 2010 Radomir Luza
 * Email: luzar(a-t)post(d-o-t)cz
 * WeatherWeb: http://pocasi.hovnet.cz   
 * 
 * MORE INFORMATION AT:
 * http://pocasi.hovnet.cz/wxwug.php?lang=en    
 *  
 * 
 */
# All included php files is under the terms of GNU license.
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 3
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.        
##################################################################################
# begin settings                                                                 #
##################################################################################
## STATION NAME 
// This text will be displayed under the name of the chart.
// For a different name in another language, please fill in the name of the station in the language file.
$stationName = 'Meteostation'; // This can be also changed for each language in translation files.
$stationName = $SITE['organ'];
## Year, Month and day when the station started sending data
$sinceY = '2010'; // year format yyyy 
$sinceY = substr($SITE['wuStart'],6,4);
$sinceM = '5'; // month without leading zero
$sinceM = substr($SITE['wuStart'],3,2);
$sinceD = '1'; // day without leading zero
$sinceD = substr($SITE['wuStart'],0,2);
### GRAPH DATA SOURCE ###
$dataSource = 'wunderground'; // 'mysql' - weather display mysql database ; 'wunderground' - wunderground server ; 'wutowdmysql' - combined WU->WD MySQL

# If is set $datasource to 'wutowdmysql' you must set start date when the station started sending data to WD MySQL database
$wdSinceY = '2010'; // year format yyyy
$wdSinceM = '09'; // month WITH leading zero if is nuber lower than 10
$wdSinceD = '01'; // day WITH leading zero if is nuber lower than 10

### MYSQL CONFIG for 'mysql' datasource ###
$dbhost = "localhost"; //your MySQL host name
$dbuser = "dbuser"; //your MySQL username
$dbpass = "dbpass"; //your MySQL password
$dbname = "dbname"; //your MySQL database name
$dbtable = "myTable"; //your MySQL table name
$datetime_col = 'no'; //Does your MySQL table have a datetime column? 'yes' 'no'
// Select units IN YOUR database
if ($SITE['region'] <>	'america') {
        $db_wind = 'kmh'; //wind speed; 'kts', 'mph' or 'kmh'
        $db_temp = 'C'; //temperature and dewpoint; 'C' or 'F'
        $db_rain = 'mm'; //rainfall; 'mm' or 'inch'
        $db_baro = 'hPa'; //barometer; 'hPa' or 'inHg'
        $db_rate = 'inch'; //rain rate; 'mm' or 'inch'    
} else {
        $db_wind = 'mph'; //wind speed; 'kts', 'mph' or 'kmh'
        $db_temp = 'F'; //temperature and dewpoint; 'C' or 'F'
        $db_rain = 'inch'; //rainfall; 'mm' or 'inch'
        $db_baro = 'inHg'; //barometer; 'hPa' or 'inHg'
        $db_rate = 'inch'; //rain rate; 'mm' or 'inch'
}
// show indoor temperature
$db_i_temp = false;
// show UV radiation in Sun graphs
$db_suv = $SITE['UV']; #$SITE['UV'];
## WD DATABASE PERFORMANCE v.s. GRAPH ACCURACY
# set graph data sampling intervals, larger number = less server CPU overloading 
// month graphs - low CPU overload
$wdMonthLim = 15; //default 15 minutes; must be one from thiese values: 5, 10, 15, 20, 30 or 60
// year graphs - intensive CPU overload
$wdYearLim = 15; //default 15 minutes; must be one from thiese values: 5, 10, 15, 20, 30 or 60
## CACHING FOR 'mysql' DATASOURCE
// Dramatically improve performance in year and month graphs, so you can set $wdMonthLim and $wdYearLim to lower values
// CACHING TYPE 
// Note: 'file' type using directory $WUcacheDir described in 'wunderground' Cache section (described few lines bellow)
$db_cache_type = 'file'; // 'file' or 'db' or 'disabled'  
$db_cache_table = 'graphs_cache'; // if is $db_cache_type set to 'db' you must create table for caching of calculated values
// Example SQL command for creating cache table: 
// CREATE TABLE `graphs_cache` (`id` VARCHAR(15) NOT NULL ,`last_access` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `data` LONGTEXT NOT NULL ,PRIMARY KEY  (`id`)) ENGINE = MYISAM;
$mysqlDMR = 20; // Cache refresh interval for Day/Month data in minutes
$mysqlYR = 12; // Cache refresh interval for Year data in hours

### HOUR GRAPHS  ###
if ($SITE['wd_live']) {$hourGraphs = 'craw';} else {$hourGraphs = 'disabled';}
#$hourGraphs = 'disabled'; // 'craw' for data from clientrawhour.txt file or 'db' from MySQL database, or 'disabled' 
$clientRawHpath = '../'; // absolute or relative path with slashes at end to directory where is stored clientrawhour.txt
$clientRawHpath = '../'.$SITE['clientrawDir'];
#### THEME
$wugTheme = 'default'; // 'default' or 'dark'
$CustomBgTheme = 'transparent'; // false or true or 'transparent' - override background color value
$colorpickerBgVal = '#ffffff'; // hex background color val
$CustomFontTheme = false; // false or true - override font color value
$colorpickerFontVal = '#000000'; // hex font color

### WUNDERGROUND ID CONFIG for 'wunderground' datasource ###
$WUID = 'IVLAAMSG47'; // YOUR Wunderground station ID - you must use big letters (for testing KWIMAUST1 - more history with sun sensor - since 2005) default: 'YOURWUID'
$WUID = strtoupper ($SITE['wuID']);
#if ($WUID <> 'IVLAAMSG47') {echo 'false';}
#echo '-'. $SITE['wuID'].'-'.$WUID.'-'; exit;
### DATE/TIME ###
// Format: 'Europe/Prague', 'Etc/GMT+1', 'Etc/GMT-5' or 'CET' etc ... 
// for possible values please visit http://www.php.net/manual/en/timezones.php
$TZconf = 'Europe/Prague';  // Your timezone 
$TZconf = $SITE['tz'];
## DATE AND TIME FORMAT (in tooltips, titles, etc..)
$ddFormat = 0; // 0 = dd.mm.yyyy; 1 = mm.dd.yyyy; 2 = mm/dd/yyyy; 3 = d.m.yy; 4 = mm.dd.yy; 5 = mm/dd/yy
$hourFormat = 0; // 0 = 24 hours format; 1 = 12 hour format (am/pm)

## PASSWORD ###
$WUpassw = 'wugraphs'; // password for WUG-test.php, for removing cached files and for loading saved configuration before upgrade to new version.
$WUpassw = $SITE['password'];
### GRAPH SIZE ###
$wugWidth = '640'; // graph width in pixels (default: '640')
$wugHeight = '380'; // graph height in pixels (default: '380')

### Language / Translation ###
$defaultWUGlang = 'en'; // default language
$defaultWUGlang = $SITE['lang'];
$langSwitch = false; // show language switch

### Units and other settings ###
if ($SITE['region'] ==	'america') {
        $metric = false; // true for metric units, false for imperial units
        $windmu = 'm/s'; // 'm/s' or 'km/h' for metric units selection
} else {
        $metric = true; // true for metric units, false for imperial units
        $windmu = 'm/s'; // 'm/s' or 'km/h' for metric units selection
}
$baroMinMax = true; // true or false - Disable minimum and maximum limits for barometric pressure graphs and informational bands/strips about intensity of pressure.
$showSolar = $SITE['SOLAR']; // if you don't have solar sensor, set this value to false (graph will be disabled/hidded in graph selection and tabs)
$wugWinW = '900'; //pixels; Default WIDTH of graph opened in a new window (default: '900' eq. for Netbooks or small notebooks/laptops)
$wugWinH = '350'; //pixels; Default HEIGHT of graph opened in a new window (default: '350' eq. for Netbooks with few toolbars in browser)
$cookieExp = 0.5; // 1 = 1 day;  expiration time for cookies in day/month/year selection (default: 0.5)

### EXTRA GRAPHS/VALUES for month/year graphs recalculated from day WU cache files
# used only for 'wunderground' datasource
$calcMbaroAvg = true; // Calculate month/year average barometric pressure
$calcSolar = true; // Calculate month/year average solar radiation 
$calcWindDir = true; // Calculate month/year average wind direction

### SPIKE DATA REMOVING for Day,Month,Year graphs
//Note: Spike data will be replaced with last 'non-spiked' value. Maximum for correction is 3 spiked values in row.
$removeSpikes = true; // true or false;
# SPIKED DATA TRESHOLDS
// Values for day spiked data corrector (must be in metric units - converted later in code)
// This is a max accepted change in measured values of the interval for DAY graphs (interval is usually 5 minutes + potential station data sending failure)
// Higher value mean a less sensitivity for spike corector.
$dsp = array('temp' => 1.4, 'baro' => 1.8, 'rain_rate' => 150, 'rain_total' => 35, 'humi' => 8);
// This is a max change in measured values of the interval (usually 1 day) for MONTH/YEAR graphs
// Higher value mean a less sensitivity for spike corector.
$mysp = array('temp' => 20, 'baro' => 10, 'humi' => 70);

### CACHING CONFIGURATION FOR 'wunderground' datasource ###
# Global cache control - for 'wunderground' datasource
$IcacheWUfiles = true; // enable global WU file caching (improve speed)  true or false
// CACHE DIRECTORY MUST BE WRITABLE FOR PHP (most often chmod 777)
// ABSOLUTE path with slashes
$WUcacheDirI = '../cache/'; // If you do not know the absolute path to your site, try set to 'auto' (cache directory will be in wxwugraphs) Default value: 'auto'

## PRECACHING - for 'wunderground' datasource
# create WU cache files for other days, when visitor browsing in graph pages 
$Iprecache = false; // true = enabled; false = disabled 
$pause = 120; //seconds; minimal value: 120; pause between creating next cache file 
$maxPre = 2; // maximum precached years backward - default: 2; max: 5 years;

# Year Graph cache 
// Cache disc space requirements: 1 year total max = +/-40kB
$tYearCache = true; // enable this year WU file caching - true or false (false = cache only outdated year when global chaching is enabled)
$tYearCacheT = 12; // HOURS. Cache time for "this year" file, then will be recreated (default: 12)

# Month Graph cache
// Cache disc space requirements: 1 month = 2~3KB ; 1 year total max = 0.3~0.5MB
$tMonthCache = true; // enable this month WU file caching - true or false (false = cache only outdated month when global chaching is enabled)
$tMonthCacheT = 12; // HOURS. Cache time for "this month" file, then will be recreated (default: 12)

# Day Graph cache
// Cache disc space requirements: 1 day (5 minutes recording intervals) = 28~35KB ; 1 year total max = 11~13MB
$todayCache = true; // enable today WU file caching - true or false (false = cache only outdated day when global chaching is enabled)
$todayCacheT = 20; // MINUTES. Cache time for today file, then will be recreated. (default: 20)

### REFRESH PAGE
/* Use 'force=1' URL parameter to refresh button?
Force parameter to recreate WU cache file. 
Useful if you want to recreate any data every time it is clicked to Refresh the icon.
However, if wundergroud lost your data, a problem may arise. */
$refreshForced = false; //true for using force parameter in refresh button link

//$autoRefreshT = '15'; // graph auto refresh in minutes. If is used MySQL datasource without caching, is better use higher values (eg. 60) for lower CPU server usage. 

### Credits text in Graph 
$creditsEnabled = 'false'; // 'true' to show credits in graph (default: 'false' WITH quotation marks)
$credits = 'some site';
$creditsURL = 'http://www.somesite.com';


### JS and CSS jQuery support
$jQueryFile = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'; // Custom path to jQuery libary (always needed)
$loadJQuery = true; // if you have own jQuery library, jQuery Tabs and jQuery Cookie loaded in Saratoga/CarterLake template (top.php), then you can (optionally) set this to false
$incTabsStyle = true; // if you set $loadJQuery to false, then you may want to use own default CSS style for jQuery tabs. In this case set to false.

### SPECIAL ###
// Enable tested/unstable functions/features
$testOn = false; // false to disable, true to enable

### Version auto checker
// to work correctly, caching must be enabled 
$updateCheck = true; // if there is a new version, v. info on bottom of the page will be red.
$SendName = true; // send station name ($gSubtitle) with first update check for better support

## GRAPH APPEREANCE PROBLEMS ##
// Height correction in tabbed mode 
$heightCorr = '20'; // pixels;(defaut value: '20')

### WITHOUT SARATOGA/CARTERLAKE TEMPLATES
/**
 * THE EASY WAY IS USE ONLY WINDOW MODE. ie link to graphs will be http://www.yourwebsite.com/wxwugraphs/graphd1a.php
 * Or ways like iframe method. But Mulilingual support is fuctional only in Saratoga CarterLate templates.
 * 
 * If you want use other way, then read next text.
 * These scripts are intended (especially wxwugraphs.php) for Saratoga/CarterLake webpage templates, but can be used relative easily in other sites using file wugraphs.php
 * If is set $includeMode to false, then wugraphs.php is in STANDALONE MODE (header sended)
 * If you want use include Mode, then consider modification of wugraphs.php and your page where wugraphs loaded.
 * This means own JS and CSS loading in your html <header> section (include mode loads JS and CSS in <body> section)
 * Other problems in include mode is possibility of PHP variable collisions!! So include mode is only for people who known PHP code.
 */ 
$standAlone = false;  // true = use wugraphs.php (also you may need set $loadJQuery and $incTabsStyle to true); false = using wxwughraph.php for saratoga carterlake templates
$includeMode = true; // true = included by PHP include function (eg: include('wugraphs.php'); ) 

##### OTHER OR MALFUNCTION SETTINGS
$spline_graphs = false; // spline or line graph type; true (spline) = better look, but low accuracy and possible problems with gaps in graphs data.
$fopenOff = false; // Enable if you have allow_url_fopen = Off in PHP configuration. But you may have problems with units in graphs (Metric vs Imperial - may depend on your server location).
//Directory for used javascripts (jquery, higcharts, ui.datepicker, ui.core ...) in graphxx.php pages
$jsPath = './js/'; // with trailing slashes (default is './js/')
$cookieEnabled = true; // set to false if you have cookie MOD_SECURITY problems in WUG-test.php
$sendAgent = false; // If you have allow_url_fopen On in PHP configuration and still you have empty graphs or cache files
$no_mb = false; // disable MB string support - only if you get some MB errors in WUG-test.php
$debug = false; // if true, all notices and errors will be shown
##################################################################################
# end settings                                                                   #
##################################################################################

// ---------------------------------------------------------------------------//
//                            I M P O R T A N T                               //
//              DO NOT customize the stuff below this point.                  //
//            Everything is controlled by your settings above.                //
// ---------------------------------------------------------------------------//

## old settings to remove:
 
## end of old settings

define('VERSION', '1.8.0');

//fix a bad url parameter interpreting on some PHP servers (eq: default config in XAMPP)  
@ini_set('arg_separator.output', '&');

$conFile = realpath(dirname(__FILE__)).'/settings.php'; // path to configuration file
// Load config
@include($conFile);

// CHANGE STATION from cookies
$WUID   = empty($_COOKIE['wuid']) ? $WUID : $_COOKIE['wuid']; 
$sinceY = empty($_COOKIE['sy'])   ? $sinceY : $_COOKIE['sy'];
$sinceM = empty($_COOKIE['sm'])   ? $sinceM : $_COOKIE['sm'];
$sinceD = empty($_COOKIE['sd'])   ? $sinceD : $_COOKIE['sd'];
$stationName = empty($_COOKIE['stn']) ? $stationName : $_COOKIE['stn'];
$wugWidth = empty($_COOKIE['wdth']) ? $wugWidth : $_COOKIE['wdth'];

$WUGcharset = 'UTF-8';
if (!$standAlone) {
  $SITE['charset'] = $SITE['charset'] == '' ? 'UTF-8' : $SITE['charset'];  // for saratoga/carterlake templates
}

$WUcacheDir = $WUcacheDirI == 'auto' ? realpath(dirname(__FILE__)).'/cache/' : $WUcacheDirI;

$langDir = './languages/'; // with trailing slashes
$mainDir = './';
$outpath = false;
if (!is_file('./WUG-settings.php')) { //resolve path
  $outpath = true;
  $langDir = './wxwugraphs/languages/';
  $mainDir = './wxwugraphs/';
}

// metric / imperial (engligsh) units text switch
if ($metric) {
  $TtempUnits = '°C';
  $TbaroUnits = 'hPa';
  $TwindUnits = $windmu;
  $TsizeUnits = 'mm';
  $TsunUnits = 'watt/m2';
  $TprecSpd = 'mm/h';
} else {
  $TtempUnits = '°F';
  $TbaroUnits = 'in.Hg';
  $TwindUnits = 'mph';
  $TsizeUnits = 'in.';
  $TsunUnits = 'watt/m2';
  $TprecSpd = 'in/h';
}
if ($windmu == 'km/h' and $metric) {
  $windcon = 1;
} elseif ($windmu == 'm/s' and $metric) {
  $windcon = 0.277778;
} else {
  $windcon = 1;
}

// charset config for PHP mb_ strings function
if (function_exists('mb_internal_encoding') and !$no_mb) {
  mb_internal_encoding($WUGcharset);
}

// Timezone PHP5 vs PHP4
if (!function_exists('date_default_timezone_set')) { //PHP4
   putenv("TZ=" . $TZconf);
} else { //PHP5
   date_default_timezone_set("$TZconf");
}

// Rain units conversion (cm to mm)
$rainMultip = $metric ? 10 : 1 ;

## language and mainDir switch
// include english language => no empty variables
include($langDir.'WUG-language-en.php');

$WUGLang = !empty($_COOKIE['cookie_lang']) ? $_COOKIE['cookie_lang'] : strtolower($defaultWUGlang) ; // if there's no cookie, set the default

if (isset ($_GET['lang']) ) { // override language by URL parameter
  $WUGLang = $_GET['lang'];
  SetCookie ("cookie_lang", $WUGLang, time()+3600*24*30, "/");
}
$WUGlangFile = $langDir.'WUG-language-'.$WUGLang.'.php'; 
if (is_file($WUGlangFile)) {
  include_once($WUGlangFile);
  $errWUGlang = '<!-- WU Graphs: Language file is "'.$WUGlangFile.'" -->'."\n";
} else {
  $errWUGlang = '<!-- WU Graphs: File "'.$WUGlangFile.'" not found. Language file is set to english. -->'."\n";
  include_once($langDir.'WUG-language-en.php');
}

// Station name vs language
if (empty($TstationName)) {
  $gSubtitle = $stationName;
} else {
  $gSubtitle = $TstationName;
}

// datepicker language
$dpckFile = $langDir.'datepicker/jquery.ui.datepicker-'.$WUGLang.'.js';
if (is_file($dpckFile)) {
  $dpckLangFile = $dpckFile;
  $errWUGlang .= '<!-- WU Graphs: Datepicker language file is "'.$dpckLangFile.'". -->'."\n";
//$dpckLang = $WUGLang; 
} else {
  $errWUGlang .= '<!-- WU Graphs: ERROR - datepicker laguage file "'.$dpckFile.'" not found. Using english. -->'."\n";
  $dpckLangFile = $langDir.'datepicker/jquery.ui.datepicker-en.js';
//$dpckLang = 'en'; 
}

// THEMES
#$wugTheme = $_GET['theme'] ? $_GET['theme'] : $wugTheme;
//echo $_COOKIE['wu_graph_theme']; exit;
//$wugTheme = !empty($_COOKIE['wu_graph_theme']) ? $_COOKIE['wu_graph_theme'] : $wugTheme;
if ($cookieEnabled && $outpath) {
  SetCookie ("wu_graph_theme", $wugTheme, time()+30, "/");
  //header("Set-Cookie: SIDNAME=wu_graph_theme; path=/; secure"); 
}

// default theme
$preImg = './wxwugraphs/images/loading2.gif';
$pgBGC = $CustomBgTheme === 'transparent' ? 'transparent' : '#ffffff';
$pgBGC = $CustomBgTheme === true ? $colorpickerBgVal : $pgBGC;
$wugfontColor = '#000000';
$higchartsTheme = 'Highcharts.theme = {chart: {backgroundColor: "'.$pgBGC.'"}};
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);';
$tabsStyleFile = './wxwugraphs/css/tabs.css';
$pickerStyleFile = './css/jquery-ui-1.8.2.custom.css';

// load theme
if ($wugTheme != 'default') {
  if ($outpath) {
    @include('./wxwugraphs/themes/'.$wugTheme.'.php');
  } else {
    @include('./themes/'.$wugTheme.'.php'); 
  }
  $pickerStyleFile = $tabsStyleFile; // datepicker style is included in $tabsStyleFile
}

// override theme colors
$pgBGC = $CustomBgTheme === true ? $colorpickerBgVal : $pgBGC;
$pgBGC = $CustomBgTheme === 'transparent' ? 'transparent' : $pgBGC;
$wugfontColor = $CustomFontTheme ? $colorpickerFontVal : $wugfontColor;

// theme for windowed mode (graphs opened in new page)
$thisPag = substr(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1),0,5);
if (isset ($_GET['i']) && $_GET['i'] != '1' && $thisPag == 'graph') {
  $higchartsTheme = '';
  $wugfontColor = '#000000';
  $pgBGC = '#ffffff';
  $pickerStyleFile = './css/jquery-ui-1.8.2.custom.css';
}

// language and theme suport for HighCharts
$langChart = $higchartsTheme.'
      Highcharts.setOptions({
        lang: {
        	months: ['.$jsMnth.'],
        	weekdays: ['.$jsDays.'],
        	resetZoom: \''.$TresetZomm.'\',
        	resetZoomTitle: \''.$TresetZommTitle.'\',
        	downloadPNG: "'.$Tdpng.'",
        	downloadJPEG: "'.$Tdjpeg.'",
        	downloadPDF: "'.$Tdpdf.'",
        	downloadSVG: "'.$Tdsvg.'",
        	exportButtonTitle: "'.$TexpBt.'",
        	printButtonTitle: "'.$TprintBt.'",
        	loading: "'.$Tloading.'"
        }
      });
';

// These values must be respected, otherwise in certain cases can lead to bad graph plotting.
$maxZoomDay = '2*5*60'; // (WU recording interval 5 mintues)
$maxZoomMonth = '1'; // (WU recording interval 1 day) 
$maxZoomYear = '1'; // (WU recording interval 1 day)

$precache = $Iprecache;
$cacheWUfiles = $IcacheWUfiles;

// Debug info 
if ($debug) {
  error_reporting(E_ALL);
} else {
  error_reporting(E_ALL ^ E_NOTICE);
}
ini_set("display_errors", 1); // override 'bad' php error config in some webhostings
//echo ini_get('error_reporting');exit; //6135

if (!function_exists('mb_stroupper')) {
  function mb_stroupper ($string) {
    global $no_mb;
    if ($no_mb) {
      return $string;
    } else {
      return stroupper($string);
    }
  }
}
if (!function_exists('mb_strtolower')) {
  function mb_strtolower ($string) {
    global $no_mb;
    if ($no_mb) {
      return $string;
    }else{
      return strtolower($string);
    }
  }
}
if (!function_exists('mb_substr')) {
  function mb_substr ($string,$p1,$p2,$enc) {
    global $no_mb;
    if ($no_mb) {
      return $string;
    } else {
      return substr($string,$p1,$p2);
    }
  }
}
/*
if (!function_exists('mb_convert_encoding')) {
  function mb_substr ($string,$p1,$p2,$enc) {
    global $no_mb;
    if ($no_mb) {
      return $string;
    } else {
      return substr($string,$p1,$p2);
    }
  }
}
*/
// set user_agent if is there some problem with fopen 
if ($sendAgent) {
  ini_set ('user_agent', $_SERVER['HTTP_USER_AGENT']);
}

// for higcharts exporting module
$hchExport = "
        navigation: {
          buttonOptions: {
            align: 'right',
            verticalAlign: 'top',
            y :20
          }
        },
";

// hour graphs switch
$hGraphs = $hourGraphs == 'craw' || $hourGraphs == 'db' ? true : false;

// spline or line graph switch
$spline = $spline_graphs ? 'spline' : 'line';
$aspline = $spline_graphs ? 'areaspline' : 'area';

if ($_GET['config']) {
  header('Location: '.$mainDir.'configurator.php');  
}

#session_start();
?>