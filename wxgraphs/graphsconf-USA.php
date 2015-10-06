<?php
// Graphs Package V2.1 16th March 2008
error_reporting(E_ALL);
// Obtain Basic Environment
// in APACHE servers it is always defined
if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) ) {
   $path_trans = str_replace( '\\\\', '/', $_SERVER['PATH_TRANSLATED']);
   $WEBROOT = substr($path_trans, 0, 0-strlen($_SERVER['PHP_SELF']) );
}
else {
   $WEBROOT        = $_SERVER['DOCUMENT_ROOT'];
}
//===============================================================================================
//PLEASE UPDATE ALL THE VARIABLES IN THIS SECTION BELOW TO YOU REQUIRED VALUES
//===============================================================================================
//where are your clientraw*-files and JPGraph relative to where this file is
//note you should only need to change the jpgraph directory if you do not use standard names
$hostloc = "${WEBROOT}/";                   //must have trailing slash
$jploc = "${WEBROOT}/jpgraph-2.3/src/";   //must have trailing slash

//Set this variable to either 12 or 24 depending on whether you run Weather Display in 12  or 24 hour mode
$hourmode = "12";

//Now some variables which tailor the graphs
$xsize = 360;
$ysize = 240;
$xsize1 = 90; //size for tank type graphs

// Margin sizes surrounding the graphs
$lm = 40; // Left Margin
$rm = 40; // Right Margin
$tm = 30; // Top Margin
$bm = 55; // Bottom Margin

// Margin sizes surrounding the single tank rain graphs
$lm1 = 45; // Left Margin
$rm1 = 15; // Right Margin
$tm1 = 30; // Top Margin
$bm1 = 40; // Bottom Margin

$margincolour = "lightblue";
$textcolour = "black";
$xtextcolour = "black";
$ytextcolour = "black";
$y2textcolour = "black";

$label_angle = 60; // Use this to set the label angle for the 24hr graphs, and value between 0 and 90
$label_interval = 1; //This sets how often the xaxis labels should appear, normally 1 or 2

// Set this next variable to the correct value for your speed conversion in wind graphs.
$speed_conv = 1.1520; //Knots to mph 1.1520, Knots to kmh 1.8540, Metres/Sec 0.5150, to leave as Knots use 1
$speed_unit = "mph";   //Set this to the correct speed units
$speed_col = "darkgray"; // Wind speed, gust and direction plot line/dot colour

//Set these values for Temp conversion
$temp_conv = 1.8; // set to 1.8 if using Fahrenheit
$temp_unit = "°F"; // Change to °F if you use Fahrenheit
$temp_format = "%0.1f"; //Sets no of decimal places to display normally %0.1f
$temp_col1 = "red"; // temperature plot line colour for above 0°C or 32°F
$temp_col2 = "darkblue"; // temperature plot line colour for below 0°C or 32°F
$temp_col3 = "lightblue"; // temperature plot line colour for plots with mixed above & below 0°C or 32°F
$temp_col_max = "red"; // temperature plot line colour for max temp on max/min graph
$temp_col_min = "lightblue"; // temperature plot line colour for min temp on max/min graph

//Rain units
$rain_conv = 0.0394; // Change to 0.0394 when using inches use 1 for mm
$rain_unit = "in"; // Change to in when required
$rain_format = "%0.2f"; //Sets no of decimal places to display use %0.2f when using inches and %0.1f when using mm
$rain_col = "blue"; // Rain plot line colour

//Pressure units
$pres_conv = 0.0295; // Set to 0.0295 for in of Mercury or 1 for hPa/mb
$pres_unit = "in"; //Set to required value
$pres_format = "%0.2f"; //Sets no of decimal places to display use %0.2f when using inches and %0.0f when using hPa/MBmm
$pres_automin = 27; // Sets the min Y axis value, suggest 27 for in and 950 for hPa/MB
$pres_automax = 31; // Sets the max Y axis value, suggest 31 for in and 1050 for hPa/MB
$pres_col = "darkgray"; // Pressure plot colour

//Solar units
$solar_unit = "w/m²"; // change to % if you use the percentage value for graphing
$solar_col = "gold"; // Solar plot line colour

//UV
$uv_col = "gold"; // UV plot colour
$uv_y_max = 20;  // UV Y-axis maximum value

//Humidity
$hum_col = "darkgray"; // Humidity plot colour

//Lightning (Nextstorm) values
$light_col = "darkgray"; // Lightning plot colour

//Dew Point
$dp_col = "magenta"; // Dew Point plot colour

//================================================================================================
// END OF USER TAILORING SECTION
//================================================================================================
//Do NOT alter below this line they are functions used in all graphs

include ("graphlang.php");

$month_array = array("null","$txt_mth1","$txt_mth2","$txt_mth3","$txt_mth4","$txt_mth5","$txt_mth6","$txt_mth7","$txt_mth8","$txt_mth9","$txt_mth10","$txt_mth11","$txt_mth12");

$negvalue = 0;
$posvalue = 0;

// Speed conversion function
function KtoV(&$value) {
	global $speed_conv;
	$value = ($speed_conv * $value);
	$value = round($value,0);
  	return $value;
} // end function

//Celcius to Farenheit conversion
function CtoF(&$value) {
	global $temp_conv;
	$value = ($temp_conv * $value)+32;
	$value = round($value,1);
  	return $value;
} // end function

// Rain conversion function 
function MtoI(&$value) {
	global $rain_conv;
	$value = ($rain_conv * $value);
	$value = round($value,2);
  	return $value;
} // end function

// Baro conversion function
function HtoI(&$value) {
	global $pres_conv;
	$value = $pres_conv * $value;
	$value = round($value,3);
  	return $value;
} // end function

// Check for negative values in array
function NegVal(&$value) {
        global $negvalue;
        global $temp_conv;
        $mintemp = 0;
        if ($temp_conv == 1.8) $mintemp = 32;
	if ($value < $mintemp) $negvalue = 1;
	return $negvalue;
} // end function

// Check for negative values in array
function PosVal(&$value) {
        global $posvalue;
        global $temp_conv;
        $mintemp = 0;
        if ($temp_conv == 1.8) $mintemp = 32;
	if ($value >= $mintemp) $posvalue = 1;
	return $posvalue;
} // end function

// Get data routine
function get_raw( $rawfile ) {
  if (substr($rawfile,0,7) != "http://") {
      if (!file_exists($rawfile)) {
          $string = "Unable to find";
          create_image1($string,$rawfile);
          exit;
      }
  }
  $rawdata = array();
  $fd = fopen($rawfile, "r");
  if ($fd) {
      $rawcontents = '';
      while (! feof ($fd) ) {
         $rawcontents .= fread($fd, 8192);
      }
      fclose($fd);
      $delimiter = " ";
      $rawdata = explode ($delimiter, $rawcontents);
  } 
  else {
      $rawdata[0]= -9999;
  }
  return $rawdata;
}
// This function gets the hour needed for Last Whole Hour graphs and requires user to
// specify their mode they run WD in. Specified in user config section above
function get_date( $rawfile ) {
  global $hostloc;
  global $hourmode;
  $clientraw = get_raw("${hostloc}clientraw.txt");
  $rawdata = $clientraw[29];
  $rawdata = $rawdata - 1;
  if ($hourmode == "24") {
    if ($rawdata == -1) $rawdata = 23;
    if ($rawdata < 10) $rawdata = "0".$rawdata;
  }
  if ($hourmode == "12") {
    if ($rawdata >= 13) $rawdata = $rawdata - 12;
    if ($rawdata == -1) $rawdata = 11;
    if ($rawdata == 0) $rawdata = 12;
  }
  return $rawdata;
}
// Calculate dew point - found at http://pear.php.net/index.php
// Modified by jmcmurry for use here with Broadstairs library & configuration file
// the default in the clienraw files is Celcius so do this now and convert later to Farenheit if needed
function dp($temperature, $humidity) {
	if ($temperature >= 0) {
		$a = 7.5;
		$b = 237.3;
	} 
        else {
		$a = 7.6;
		$b = 240.7;
	}
	// First calculate saturation steam pressure for temperature
	$SSP = 6.1078 * pow(10, ($a * $temperature) / ($b + $temperature));
	// Steam pressure
	$SP  = $humidity / 100 * $SSP;
	$v   = log($SP / 6.1078, 10);
	$dpoint = round($b * $v / ($a - $v), 1);
	return $dpoint;
}
function create_image1(&$value,$value1)
{
    //Set the image width and height
    $width = 400;
    $height = 100;

    //Create the image resource
    $image = ImageCreate($width, $height);

    //We are making three colors, white, black and gray
    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, 0, 0, 0);
    $grey = ImageColorAllocate($image, 204, 204, 204);

    //Make the background black
    ImageFill($image, 0, 0, $grey);

    //Add randomly generated string in white to the image
    ImageString($image, 5, 40, 20, $value, $black);
    ImageString($image, 5, 40, 60, $value1, $black);

    //Tell the browser what kind of file is come in
    header("Content-Type: image/jpeg");

    //Output the newly created image in jpeg format
    ImageJpeg($image);

    //Free up resources
    ImageDestroy($image);
}
?>