<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'wsDashYowindow.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# More information: 	go to : http://yowindow.com/ for more information
#     
# ----------------------------------Settings for output-----------------------------------
#
$use_header             = true;                // print line with  explanation "YoWindow 7 day graphical forecast"
# ----------------------------------------------------------------------------------------
$use_yow_ipad_pic       = false;                // use a meteogram as background for non-flash devices
$use_yow_all_pic        = true;                 // use a meteogram as background for non-flash USERS
#
# ---------------Settings for changing what yowindow displays-----------------------------
#
#       The ID (YoWindowPlaceID) for your city can be either from a geoname id or from the latitude / longitude
#               geoname id:  http://yowindow.com/id  to get that numeric city number example:  2796012=herent, 2783612=wilsele
#       DEFAULT lat-lon comes from the wsUserSettings.php
#$yowindow_place_ID      = '2792482';            // 2792482 = leuven
$use_yow_lat_lon        = true;                 //  set to true and the latitude/longitude will be used, the  $YoWindowPlaceID will not be used     
#
# ----------------------------------------------------------------------------------------
#       The name of your city in the right part of the gadget
$yowindow_location_name = $SITE['organ'];       // use the same name as in the rest of the template
#$yowindow_location_name= 'type your own name'; // remove # to add your own name
#$yowindow_location_name= '';                   // remove # and leave as ''; to use the name from the yowindow database
#
# ----------------------------------------------------------------------------------------
#       The landscape
$yow_landscape	        = 'village';
$yow_landscape	        = 'seaside';            // select the landscape by removing the commment mark # at one landscape  only
#$yow_landscape	        = 'oriental';           // put the comment mark # at the unwanted landscapes
#$yow_landscape	        = 'airport';
#$yow_landscape	        = 'valley';
#$yow_landscape	        = 'town';               // watch out in Beta at yowindow 2014-11-18  does not fit nicely

#
# ----------------------------------------------------------------------------------------
#       height of the yowindow gadget
$yow_height	        = '300px';              // normally leave as is
# ----------------------------------------------------------------------------------------
#       Use internet to download yowindow parts or our own version (no crossdomain.xml in the root needed)
#       only valid for templates version 2.6g and newer, others leave as is
$use_yow_site           = true;                 // set to false to use your own copy (as in the template download from 2.6g)
# ----------------------------------------------------------------------------------------
#       Use ouw own current data (for temp, pressure, uv and so on) or use METAR for that also
$use_yow_own_data       = true;                 // set to false to skip using your own data. F.I. when adding a yowinodw gadget for another city
# ----------------------------------------------------------------------------------------
#       number of forecasts shown when started  // for region america|| other it is lowered to  max 6 in the script
$use_yow_nr_fct         = 9;
# ----------------------------------------------------------------------------------------
#       use metar set in wsUserSettings.php, || your own || let yowindow decide (default)
$yow_metar              = $SITE['METAR'];       // metar as in settings
#$yow_metar              = 'EBBR';              // another metar for yowindow
$yow_metar              = '';                   // the metar yowindow wants to use
#
#--------------Please: no changes below this line ----------------------------------------
#
if (!isset ($use_yow_nr_fct ) ) { 
        $use_yow_nr_fct = 9;}
if ( ($SITE['region'] <> 'europe') && ($SITE['region'] <> 'canada') && ($use_yow_nr_fct > 6)   ) {
        $use_yow_nr_fct = 6;}
if (!isset ($yow_landscape) ) {
        $yow_landscape          = 'village';}
if (!isset ($SITE['yow_widget'])) {
        $SITE['yow_widget']     = './yowidget/yowidget.swf'; }
if (isset ($use_yow_site) && $use_yow_site) { 
        $yow_widget             = 'http://swf.yowindow.com/yowidget3.swf';}
else {  $yow_widget             = $SITE['yow_widget'];}
if (!isset ($yow_metar) || $yow_metar == '') {
        $yow_current    = ''; }
else  { $yow_current    = PHP_EOL.'      current_weather_icao: "'.$yow_metar.'",';}
if (!isset ($yow_height) ){
        $yow_height = '250px';}
if (!isset ($use_yow_ipad_pic ) ) { 
        $use_yow_ipad_pic = true;}
if (!isset ($use_yow_all_pic ) ) { 
        $use_yow_all_pic = true;}
#-----------------------------------------------------------------------------------------
# folowing lines put a meteogram begind the youwindow gadget for visitors without flash support
$yow_alternative_content= '';
$yow_header             = langtransstr('YoWindow 7 day graphical forecast');
if ($use_yow_ipad_pic) {
	$script	= $SITE['mobileDir'].'Mobile_Detect.php';
	ws_message (  '<!-- module wsDashYowindow.php ('.__LINE__.'): loading '.$script.' -->');
        require_once $script;       // credits : http://mobiledetect.net/ 
        $detect = new Mobile_Detect; 
        if( $detect->isTablet() ){$use_yow_all_pic = true;}
}
if ($use_yow_all_pic) {
	$yow_header    = '';
	$script	= 'wsyrnofct/yrnoavansert4.php';
	ws_message (  '<!-- module wsDashYowindow.php ('.__LINE__.'): loading '.$script.' -->');
	include $script;
	echo '<!-- put yr.no static picture here -->'.PHP_EOL;
                $yow_alternative_content = '  <a href="http://www.yr.no/place/'.$SITE['yrnoID'].'" target="_blank" title="forecast meteogram yr.no">
   <img src="'.$im.'" alt="  " style=" width: 100%; height:'.$yow_height.'; vertical-align: top;"/>
  </a>';
}  // eo use ipad pict
# print enclosing div
#echo '<div class="blockDiv" style="">'.PHP_EOL;
if ($use_header && $yow_header <> '') {echo '<h3 class="blockHead">'.$yow_header.'</h3>'.PHP_EOL;}
# we now print the div for the yowindow gadget
echo '<div style="vertical-align: bottom; z-index: 1;">
 <div id="yowidget"  style="height:'.$yow_height.';overflow: hidden; ">
 '.$yow_alternative_content.'
 </div>'.PHP_EOL;
$yowindow_location_name = htmlspecialchars_decode ( $yowindow_location_name, ENT_QUOTES | ENT_HTML5); 
#-----------------------------------------------------------------------------------------
echo  ' <!-- put yowindow dynamic picture here -->'.PHP_EOL;
# documentation for units and yowindow on this page  http://yowindow.com/widget_parameters.php
# temp
if ($SITE['uomTemp'] == '&deg;C') {$yow_temp = 'c';} else {$yow_temp = 'f';}
# wind
$uom            = strtolower (trim($SITE['uomWind']) );
$from           = array (' ',   '&nbsp;','km/h','kts'   ,'m/s'  ,'mph'); 
$to             = array ('',    '',      'kph', 'knot'  ,'mps'  ,'mph' );
$yow_wind        = str_replace ($from,  $to, $uom );
# pressure
$uom            = strtolower (trim($SITE['uomBaro']) );
$from           = array (' ',   '&nbsp;','hpa', 'mb',   'inhg'); 
$to             = array ('',    '',      'hpa', 'mbar', 'in');
$yow_baro       = str_replace ($from,  $to, $uom);
# rain
$uom            = strtolower (trim($SITE['uomRain']) );
$from           = array (' ', '&nbsp;');
$yow_rain       = str_replace ($from,  '', $uom );
if (trim($yow_rain) == 'mm') {$yow_rain = 'mm';} else {$yow_rain = 'in';}
# distance
$uom            = strtolower (trim($SITE['uomDistance']) );
$from           = array (' ', '&nbsp;');
$yow_dist       = str_replace ($from,  '', $uom );
if (trim($yow_dist)  == 'km') {$yow_dist = 'km';} else {$yow_dist = 'mile';}

# check for yrno xml file
if ($use_yow_own_data && isset($SITE['yrnoXmlName']) ) {
        $yow_url_data = trim($SITE['yrnoXmlName']);}     else    {$yow_url_data = '';}

# set 12 or 24 hour display
if (isset($SITE['hourDisplay']) && trim($SITE['hourDisplay']) == '12') {$time_format = '12'; } else {$time_format = '24'; }

# UV
if (isset($SITE["UV"]) && $SITE["UV"]) {$yow_UV = '1';} else {$yow_UV = '0';}

#  ID  or lat lon
if (isset ($use_yow_lat_lon) && $use_yow_lat_lon == false) {
        if (isset($SITE['YoWindowPlaceID']) ) {
                $location_id    ='location_id:  "gn:'.$SITE['YoWindowPlaceID'].'",';} 
        else {  $location_id    ='location_id:  "gn:'.$yowindow_place_ID.'",';} 
}
else {  $location_id    =
'lat: "'.$SITE['latitude'].'",
      lon: "'.$SITE['longitude'].'",';
}
# u_pressure_level: "location",
echo '<script type="text/javascript" src="javaScripts/swfobject.js"></script>
<script type="text/javascript">
    var flashvars = {
      '.$location_id.'
      location_name: "'.$yowindow_location_name.'",
      current_weather_url: "'.$yow_url_data.'",
      time_format: "'.$time_format.'",
      us: "custom",
      u_temperature: "'.$yow_temp.'",
      u_wind_speed: "'.$yow_wind .'",
      u_pressure: "'.$yow_baro.'",
      u_pressure_level: "sealevel",
      u_distance: "'.$yow_dist.'",
      u_rain_rate: "'.$yow_rain.'",
      lang: "'.$lang.'",
      background: "transparent",
      copyright_bar: false,
      fp_day_count: '.$use_yow_nr_fct.',
      landscape: "'.$yow_landscape.'",'.$yow_current .'
      i_uv: '.$yow_UV.'
   };
    var params = {
      quality: "high",
      bgcolor: "#FFFFFF",
      allowscriptaccess: "always",
      allowfullscreen: "true",
      menu: "false",
      wmode: "transparent",   
    };
    var attributes = {
       id:"yowidget",
       align: "top",
       name:"yowidget"
    };
    swfobject.embedSWF(
       "'.$yow_widget.'",
       "yowidget", 
       "100%", 
       "'.$yow_height.'",
       "9.0.0",
       "expressInstall.swf",
       flashvars,
       params,
       attributes
    );
</script>
</div>
<!-- end of Yowindow -->
<!-- eo dash -->'.PHP_EOL;
# ----------------------  version history
# 3.20 2015-07-15   release 2.8 version + selection always background for non flash support visitors