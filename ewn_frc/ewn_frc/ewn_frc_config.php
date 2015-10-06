<?php
############################################################################
# EWN Forecast v. 1.41 (October 2014) - adapted leuventemplate 2014-12-23 
############################################################################
#
# Author:	Henkka <nordicweather@gmail.com.net>
#
# Copyright:	(c) 2008-2013 Copyright nordicweather.net.
#
############################################################################
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
############################################################################
#
# This work is licensed under the 
# Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. 
# To view a copy of this license, visit 
# http://creativecommons.org/licenses/by-nc-nd/3.0/.
#
############################################################################

# Your personal API-key, get it from EWN member-area. Required from 2015-01-01.
$apikey                 = "";                               
$tzabb                  = $SITE['tz'];
$scrollwheel            = 0;                    // Scrollwheel zoom? 1 = yes, 0 = no
$centerlat              = $SITE['latitude'];    // Latitude of default location
$centerlon              = $SITE['longitude'];   // Longitude of default location
$deflevel               = 2;                    // Deafult zoomlevel of the map, 0-4
$basemap                = "gray";               // gray,sat or normal
$defmap                 = "wrf_temp";           // Default forecastlayer in "Maps"-tab - see readme
$datestyle              = "Y-m-d";
$jqueryload             = true;                 # Should we load JQuery? (Set to false if your site loads it by default)
$mainwidth              = "100%";               // Use 100% for responsivity
$phonedetect            = 1;                    // Enable detection of phones, 1 = yes, 0 = no - see readme
$usekmh                 = false;                // Windunit: true - km/h, false - m/s

$path_to_langfiles      = "lang/";
$path_to_wrfmap         = "ewn_frc/";
$path_to_js             = "ewn_frc/js/";
$path_to_css            = "ewn_frc/css/";

# END
############################################################################

if($_GET[lang]&&!isset($lang)){$lang=$_GET[lang];}
if($lang == "se"){$lang="sv";$oldlang = "se";}
if($lang == "dk"){$lang="da";$oldlang = "dk";}
if (!isset($lang)) { $lang = "en";}
if ($lang == "fr" )      { $lang = "en";}
$_GET[lang]=$lang;
include_once($path_to_langfiles.'/ewn.lang.'.$lang.'.php');
if($map_only){
  $ewnfooter='<script>var map_only=true;var mobile_frc=false;</script>';
  include 'ewn_frc/wrf_map.php';
}else{
  $ewnfooter='<script>var map_only=false;var mobile_frc=false;</script>';
  include 'ewn_frc/ewn_frc.php';
}
if($mobile_frc){$ewnfooter='<script>var map_only=false;var mobile_frc=true;</script>';}
?>