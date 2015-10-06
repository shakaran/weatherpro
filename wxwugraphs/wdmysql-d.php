<?php
/**
 * Project:   WU GRAPHS
 * Module:    wdmysql-d.php 
 * Copyright: (C) 2010 Radomir Luza
 * Email: luzar(a-t)post(d-o-t)cz
 * WeatherWeb: http://pocasi.hovnet.cz 
 */
################################################################################
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
################################################################################

//$debug = true;
if ($debug) {
  $stimer = explode(' ', microtime());
  $stimer = $stimer[1] + $stimer[0];
}

// connect to db
mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect");
mysql_select_db($dbname) or die("Unable to select database");

// CACHING
$dbcFile = $WUcacheDir.'dbc-day-'.$year.$month.$day.'.txt';
$db_caching = $db_cache_type == 'db' || $db_cache_type == 'file' ? true : false; 
if ($db_caching) {
  // search if exist cache for this request
  if ($db_cache_type == 'file') {
    $fromCache = is_file($dbcFile) ? true : false;
    $last_access = $fromCache ? filemtime($dbcFile) : 1;
  } elseif ($db_cache_type == 'db') {
    $csq = mysql_query("SELECT data,last_access FROM $db_cache_table WHERE id='day-$year$month$day'");
    $cRow = mysql_fetch_row($csq);
    $fromCache = $cRow ? true : false;
    $last_access = $cRow ? strtotime($cRow[1]) : 1;
  } else {
    $fromCache = false;
  }
  // recreating cache data 
  $ReqDay = $year.'-'.$month.'-'.$day;
  $Thday = date('Y-m-d');
  $endReqDay = strtotime($year.'-'.$month.'-'.$day.' 23:59:59');
  if ($fromCache) {
    if ($ReqDay != $Thday && $last_access <= $endReqDay) { // recreate uncomplete day
      $fromCache = false;
      $updCache = true;    
    } elseif ($ReqDay == $Thday and ($last_access + ($mysqlDMR*60)) < time()) { // recreate today cache if expired refresh interval
      $fromCache = false;
      $updCache = true;    
    } else {
      $updCache = false;
    } 
  }
}

if ($fromCache) {
  // LOAD FROM CACHE
  if ($db_cache_type == 'file') { // from file
    $outData = file_get_contents($dbcFile);
  } else { // from db
    $outData = $cRow[0];    
  }
} else {
//if (!$fromCache || empty($outData)) {
  // LOAD/RECALCULATE FROM DB
  
  // conversion ratios 
  $r_wind = $db_wind == 'kts' && $metric ? 1.851 : 1; // kts to kmh
  $r_wind = $db_wind == 'mph' && $metric ? 1.61 : $r_wind; // mph to kmh
  $r_wind = $db_wind == 'kts' && !$metric ? 1.15 : $r_wind; // kts to mph
  $r_wind = $db_wind == 'kmh' && !$metric ? 0.62 : $r_wind; // kmh to mph
  $r_rain = $db_rain == 'mm' && !$metric ? 1/25.4 : 1;  // mm to inch
  $r_rain = $db_rain == 'inch' && $metric ? 25.4 : $r_rain;  // inch to mm
  $r_baro = $db_baro == 'hPa' && !$metric ? 0.0295 : 1; // hPa to inHg
  $r_baro = $db_baro == 'inHg' && $metric ? 33.863 : $r_baro; // inHg to hPa
  $r_rate = $db_rate == 'mm' && !$metric ? 1/25.4 : 1;  // mm to inch
  $r_rate = $db_rate == 'inch' && $metric ? 25.4 : $r_rate;  // inch to mm
  
  function convcf ($cval) { // convert Celsius to Farenheit
    global $metric, $db_temp;
    if ($db_temp == 'C' && !$metric) {
      return ($cval * 9) / 5 + 32; // C to F
    } elseif ($db_temp == 'F' && $metric) {
      return ($cval - 32) / 9 * 5; // F to C
    } else {
      return $cval;
    }
  }
  
  $start = $year.'-'.$month.'-'.$day." 00:00:00";
  $end = date("Y-m-d", strtotime($start)+24*60*60)." 00:00:00";
  
  // used values
  //Select columns which be added to query (lower column count = better performance/speed)
  if ($db_cache_type == 'disabled') {
    $thisPag = substr(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1),5,-5);
    switch ($thisPag) {
        case 'h1':
        case 'd1':
            $datalist = array('temperature','indoor_temperature','dew_point_temperature');
            break;
        case 'm1':
        case 'y1':      
            $datalist = array('temperature','indoor_temperature');
            break;
        case 'h3':
        case 'd3':
        case 'm3':
        case 'y3':
            $datalist = array('outdoor_humidity');
            break;
        case 'h5':
        case 'd5':
        case 'm4':
        case 'y4':
            $datalist = array('barometer');
            break;
        case 'd4':
        case 'h4':
            $datalist = array('gust_windspeed','average_windspeed');
            break;
        case 'm5':
        case 'y5':
            $datalist = array('gust_windspeed','average_windspeed','wind_direction');
            break;
        case 'd2':
        case 'h2':
            $datalist = array('wind_direction');
            break;
        case 'm6':
        case 'y6':
            $datalist = array('daily_rainfall');
            break;
        case 'h6':
        case 'd6':
        case 'm7':
        case 'y7':
            $datalist = array('actual_solar_reading', 'davis_vp_uv', 'current_weather_desc');
            break;
        case 'h7':
        case 'd7':
            $datalist = array('daily_rainfall', 'rain_rate');
            break;
        case 'm2':
        case 'y2':
            $datalist = array('dew_point_temperature');
    }
  } else {
    $datalist = array('temperature', 'indoor_temperature', 'dew_point_temperature', 'barometer', 'wind_direction', 'average_windspeed', 'gust_windspeed', 'outdoor_humidity', 'rain_rate', 'daily_rainfall', 'actual_solar_reading', 'current_weather_desc', 'davis_vp_uv');
  }
  foreach ($datalist as $val) {
    $datacol .= $val.", ";
  }
  $datacol = substr($datacol,0,-2);
  
  // Limit the number of records from query
  // set default value if is configurable value unalowed
  $wdDayLim = 5;
  if ($wdDayLim == 'disabled') {
    $limit = '';  
  } else {
    $intvals = array(5,10,15,20,30,60);
    foreach ($intvals as $val) {
      if ($val == $wdDayLim) {
        $ival = $val;
      }
    }
    if ($datetime_col == 'no') {
      $interval = isset($ival) ? $ival : 5;
    } else {
      $interval = $wdDayLim;
    }
    // create regexp string
    for ($i=0; $i<60; $i+=$interval) {
      $regstr .= zero_bd($i).'|';   
    }
    $regstr = substr($regstr, 0, -1); // remove extra pipe
    $regstr = "'..:($regstr):..'";
    $limit = "AND datetime REGEXP $regstr";
  }

  
  switch ($datetime_col) {
      case 'yes':
          $que = "SELECT datetime, $datacol FROM $dbtable WHERE datetime BETWEEN '$start' AND '$end' $limit ORDER BY datetime ASC";
          break;
      case 'no':
          $que = "SELECT datetime, $datacol FROM(SELECT CONCAT(date,' ',time)as datetime, $datacol from $dbtable) as temp WHERE datetime BETWEEN '$start' AND '$end' $limit ORDER BY datetime ASC";            
  }

  $sql = mysql_query($que);
  if (!$sql) {
    echo "mysql query error: ".mysql_error().'<br>';
    $db_caching = false;
  }
  
  date_default_timezone_set('UTC'); // Set timezone offset to 0
  
  //Proceed data
  while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
    $dTimeArray .= (strtotime($row['datetime'])*1000).',';
    $dTemp .= convcf($row['temperature']).',';
    $dDP .= convcf($row['dew_point_temperature']).',';
    $dBaro .= round($row['barometer']*$r_baro, 2).',';
    $dWindDir .= $row['wind_direction'].',';
    $dAvgWS .= round($row['average_windspeed']*$r_wind*$windcon, 1).',';
    $dGustWS .= round($row['gust_windspeed']*$r_wind*$windcon, 1).',';
    $dHum .= $row['outdoor_humidity'].',';
    $dRainSpd .= round($row['rain_rate']*$r_rate, 2).',';
    $dRainT .= round($row['daily_rainfall']*$r_rain, 2).',';
    $dSolar .= $row['actual_solar_reading'].',';
    $dCond .= '"'.str_replace('_',' ',$row['current_weather_desc']).'",';
    $dUV .= $row['davis_vp_uv'].',';
    $dIndTemp .= convcf($row['indoor_temperature']).',';  
  }
  
  date_default_timezone_set($TZconf); // Back to configured timezone
  
  if (mysql_affected_rows() < 2) { // we need at least two values for drawing a line in graph.
    if ($WUrequest == $Today) {
      $emptyGraph = true;
    } else {
      $emptyGraphTP = true;
    }
  }
  
  // metric/imperial units conversion for spike correcting
  $baroSpike = $metric ? 1  : 33.86;
  $tempSpike = $metric ? 1  : 1.8 ;
  $rainSpike = $metric ? 1  : 2.54;
  $windSpike = $metric ? 1  : 0.6214;

  $outData = 'var dTemp    = ['.rmSpike(substr($dTemp, 0, -1), $dsp['temp']*$tempSpike).'];
var dDP      = ['.rmSpike(substr($dDP, 0, -1), $dsp['temp']*$tempSpike).'];
var dBaro    = ['.rmSpike(substr($dBaro, 0, -1), $dsp['baro']/$baroSpike).'];
var dWindDir = ['.substr($dWindDir, 0, -1).'];
var dAvgWS   = ['.substr($dAvgWS, 0, -1).'];
var dGustWS  = ['.substr($dGustWS, 0, -1).'];
var dHum     = ['.rmSpike(substr($dHum, 0, -1), $dsp['humi']).'];
var dRainSpd = ['.rmSpike(substr($dRainSpd, 0, -1), $dsp['rain_rate']/$rainSpike).'];
var dRainT   = ['.rmSpike(substr($dRainT, 0, -1), $dsp['rain_total']/$rainSpike).'];
var dSolar   = ['.substr($dSolar, 0, -1).'];
var dCond    = ['.substr($dCond, 0, -1).'];
var dUV      = ['.substr($dUV, 0, -1).'];
var dIndTemp    = ['.rmSpike(substr($dIndTemp, 0, -1), $dsp['temp']*$tempSpike).'];
var timeArray = ['.substr($dTimeArray, 0, -1).'];';


  
  // SAVE DATA TO CACHE
  if ($db_caching) {
    if ($db_cache_type == 'file') {
      if ($updCache) {
        unlink($dbcFile);
      }
      $cacheF = fopen($dbcFile, "w");
      fwrite($cacheF, $outData);
      fclose($cacheF);
    } else {
      if ($updCache) {
        mysql_query("UPDATE $dbname.$db_cache_table SET `data`='$outData', `last_access`='".date('Y-m-d H:i:s')."' WHERE `id`='day-$year$month$day'");
      } else {
        mysql_query("INSERT INTO $dbname.$db_cache_table (`id`, `data`) VALUES ('day-$year$month$day', '$outData')");
      }
    }
  }
}

if (strlen($outData) < 242) { // empty data detection for cached value
  $emptyGraph = true;
}

$JSdata = $outData.'

// Function for creating graph array
function comArr(unitsArray) { 
    var outarr = [];
    for (var i = 0; i < timeArray.length; i++) {
     outarr[i] = [timeArray[i], unitsArray[i]];
    }
  return outarr;
}  
';
// zero before digits
function zero_bd ($value) {
  if ($value <= 9) {
    return "0".$value;
  } else {
    return $value;
  }
}
// Remove spikes
function rmSpike ($data, $diff, $maxBadVal = 3) {
  global $removeSpikes;
  if ($removeSpikes) {
      $array = explode(",", $data);
      $c = 0;
      $Ncorr = 1; // after $maxBadVal times is bad value ignored, so $maxBadVal is maximum nr. of bad values 
      foreach ($array as $value) {
        if (!isset($lv1)) {
          $lv1 = $value;
          $c++;
          continue;
        } 
        if ( abs($value-$lv1) > $diff and $Ncorr <= $maxBadVal ) { //    
          $array[$c] = $lv1; // replaces the current value of the last one
          $Ncorr++;
        } else {
          $lv1 = $value;
          $Ncorr = 0;
        }
        $c++;
      }
      return implode(",",$array);
  } else {
    return $data;
  }
}

if ($debug) {
  $etimer = explode(' ', microtime());
  $etimer = $etimer[1] + $etimer[0];
  printf("Script timer: <b>%f</b> seconds.", ($etimer - $stimer));
} 
?>