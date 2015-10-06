<?php
#
# E X A M P L E   SCRIPT
# script to switch from normal uploaded webcam image to special night version
# script switch_webcam.php   version 0.00 2014-09-28
# --------------------  settings -------------------------------
$cut_off        = '15';                                 // extra time before sunrise and after sunset in minutes
$website_name   = 'http://wiri.be/';                   // full name 'http://www.cavecountryweather.com/' for jpg or png -  '../' for php
#$website_name   = '../';                                // location of images mostly in the root of your website
$day_image      = $website_name.'image.jpg';            // filename day time = relative to website_name 'jpgwebcamIR.jpg'; 
$night_image    = $website_name.'imagenight.jpg';       // filename of use for the night time image 'webcam160.jpg';
# --------------------  end of settings ------------------------
$cut_off        = 1.0 * $cut_off;                       // check cut off setting
if (! is_numeric ($cut_off) || $cut_off > 60 || $cut_off < 5) {$cut_off = 15;}
$SITE           = array();
include 'wsLoadSettings.php';                               // latitude / longitude  / tz
$wsDebug        = false;
if (isset($_REQUEST['debug'])) {
	ini_set('display_errors', 'On'); 
	error_reporting(E_ALL);	
}
#
$nowInt		= time();
$now		= date($SITE["timeOnlyFormat"],$nowInt);
$lat		= $SITE['latitude'];
$long		= $SITE['longitude'];
$sunriseInt	= date_sunrise($nowInt, SUNFUNCS_RET_TIMESTAMP, $lat, $long);   // standard time integer
$sunsetInt	= date_sunset ($nowInt, SUNFUNCS_RET_TIMESTAMP, $lat, $long);
$show_day_from  = $sunriseInt - 60 * $cut_off;          // unix time sunrise minus cut_off time
$show_day_until = $sunsetInt  + 60 * $cut_off;          // unix time sunset plus cut_off
$camera_on	= date($SITE["timeOnlyFormat"],$show_day_from);
$camera_off	= date($SITE["timeOnlyFormat"],$show_day_until);
#
if ($wsDebug) {
        echo 'It is now: '.$now.' - webcam on at: '.$camera_on.' - webcam off at: '.$camera_off.PHP_EOL; 
}
if  ($nowInt > $show_day_from && $nowInt < $show_day_until) 
        { $outputImage    = $day_image; }
else    { $outputImage    = $night_image; }

if ($wsDebug) {
        echo 'Output image : '.$outputImage.PHP_EOL; 
        return;
}
$arr            = explode ('.',$outputImage);
$count          = count($arr);
$image_type     = $arr[$count-1];
#
if ($image_type == 'php') {include $outputImage; return;}       // NOT TESTED YET

$retCode        = true;
if ($image_type == 'jpg') {
        if (!function_exists ('LoadJPEG') ) {                   // this function is used in other scripts also - do not change
            function LoadJPEG (&$retCode, $imgname) { 
                $im = @imagecreatefromjpeg ($imgname); 		// Attempt to open 
                if (!$im) {$retCode = false;}                   // if it fails we do not know what to do with webcam images 
                return $im; 
            } // eof  LoadJPEG
        } // eo check if function already defined/used
        $image = LoadJPEG($retCode, $outputImage);
  #      echo '>'.$retCode.'<'; 
   #     echo '>'.$image.'<';; exit;
        imagejpeg($image);
        imagedestroy($image);
        return;
}
if ($image_type == 'png') {
        if (!function_exists ('LoadPNG') ) {                   // this function is used in other scripts also - do not change
            function LoadPNG (&$retCode, $imgname) { 
                $im = @imagecreatefrompng ($imgname); 		// Attempt to open 
                if (!$im) {$retCode = false;}                   // if it fails we do not know what to do with webcam images 
                return $im; 
            } // eof  LoadPNG
        } // eo check if function already defined/used
        $image = LoadPNG($retCode, $outputImage);
        imagepng($image);
        imagedestroy($image);
        return;
}
echo 'invalid image type';
