<?php
/**
 * Project:   WU GRAPHS
 * Module:    wdmysql-m.php 
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
mysql_connect($dbhost, $dbuser, $dbpass) or die("Unable to connect database");
mysql_select_db($dbname) or die("Unable to select cache database '$dbname'");

// CACHING
$dbcFile = $WUcacheDir.'dbc-month-'.$year.$month.'.txt';
$db_caching = $db_cache_type == 'db' || $db_cache_type == 'file' ? true : false; 
if ($db_caching) {
  // search if exist cache for this request
  if ($db_cache_type == 'file') {
    $fromCache = is_file($dbcFile) ? true : false;
    $last_access = $fromCache ? filemtime($dbcFile) : 1;
  } elseif ($db_cache_type == 'db') {
    $csq = mysql_query("SELECT data,last_access FROM $db_cache_table WHERE id='month-$year$month'");
    $cRow = mysql_fetch_row($csq);
    $fromCache = $cRow ? true : false;
    $last_access = $cRow ? strtotime($cRow[1]) : 1;
  } else {
    $fromCache = false;
  }
  // recreating cache data 
  $ReqMnt = $year.'-'.$month;
  $TMnt = date('Y-m');
  $daysInMnt = date('t', strtotime($year.'-'.$month.'-01 00:00:00'));
  $endReqMnt = strtotime($year.'-'.$month.'-'.$daysInMnt.' 23:59:59');
  if ($fromCache) {
    if ($ReqMnt != $TMnt && $last_access <= $endReqMnt) { // recreate uncomplete month
      $fromCache = false;
      $updCache = true;    
    } elseif ($ReqMnt == $TMnt and ($last_access + ($mysqlDMR*60)) < time()) { // recreate today cache if expired refresh interval
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
  
  $start = $year.'-'.$month."-01 00:00:00";
  $daysim = date('t', strtotime($start));
  $end = date("Y-m-d", strtotime($start)+24*60*60*$daysim)." 00:00:00"; 
  
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
            $datalist = array('actual_solar_reading', 'davis_vp_uv');
            break;
        case 'h7':
        case 'd7':
            $datalist = array('daily_rainfall');
            break;
        case 'm2':
        case 'y2':
            $datalist = array('dew_point_temperature');
    }
  } else {
    $datalist = array('temperature', 'indoor_temperature', 'dew_point_temperature', 'barometer', 'wind_direction', 'average_windspeed', 'gust_windspeed', 'outdoor_humidity', 'daily_rainfall', 'actual_solar_reading', 'davis_vp_uv');
  }
  foreach ($datalist as $val) {
    $datacol .= $val.", ";
  }
  $datacol = substr($datacol,0,-2);
  
  // Limit the number of records from query
  // set default value if is configurable value unalowed
  if ($wdMonthLim == 'disabled') {
    $limit = '';  
  } else {
    $intvals = array(5,10,15,20,30,60);
    foreach ($intvals as $val) {
      if ($val == $wdMonthLim) {
        $ival = $val;
      }
    }
    if ($datetime_col == 'no') {
      $interval = isset($ival) ? $ival : 10;
    } else {
      $interval = $wdMonthLim;
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
  $avgBaro = '';
  while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
    $farr++;
    $actDay = substr($row['datetime'], 8, 2);
    if (!isset($chDay)) { //first run
      $chDay = $actDay;
      $precipStart = substr($row['datetime'], 8, 2)*1;
    }
    if ($farr == mysql_affected_rows()) { // las record reached, add this record to arrays for calculation
      $aTemp[] = convcf($row['temperature']);
      $aDP[] = convcf($row['dew_point_temperature']);
      $aHum[] = $row['outdoor_humidity'];
      $aBaro[] = $row['barometer']*$r_baro;
      $aWS[] = $row['average_windspeed']*$r_wind;
      $agWS[] = $row['gust_windspeed']*$r_wind;
      $awindir[] = $row['wind_direction'];
      $aSolar[] = $row['actual_solar_reading'];
      $aUV[] = $row['davis_vp_uv'];
      $aIndTemp[] = convcf($row['indoor_temperature']);
      $dailyRain = $row['daily_rainfall'];
      //$pdtstamp = strtotime($row['datetime'])*1000;
      // callsign to create calculation for last day in queue
      $qend = true;  
    }
    if ($chDay != $actDay || $qend) { // day is changed, calculate/add previous day
      // CALCULATE/CREATE JS VALUES FROM PHP ARRAYS
      //echo $actDay.'<br>';
      $timeArray .= $pdtstamp.',';
      $maxTemp .= max($aTemp).',';
      $minTemp .= min($aTemp).',';
      $avgTemp .= round(array_sum($aTemp)/count($aTemp), 1).',';    
      unset($aTemp);
      $maxDP .= max($aDP).',';
      $minDP .= min($aDP).',';
      $avgDP .= round(array_sum($aDP)/count($aDP), 1).',';    
      unset($aDP);
      $maxHum .= max($aHum).',';
      $minHum .= min($aHum).',';
      $avgHum .= round(array_sum($aHum)/count($aHum), 1).',';    
      unset($aHum);
      $maxBaro .= max($aBaro).',';
      $minBaro .= min($aBaro).',';
      $avgBaro .= round(array_sum($aBaro)/count($aBaro), 1).',';    
      unset($aBaro);
      $maxWS .= round(max($aWS)*$windcon, 1).',';
      $avgWS .= round((array_sum($aWS)/count($aWS))*$windcon, 1).',';    
      unset($aWS);
      $gustWS .= round(max($agWS)*$windcon, 1).',';
      unset($agWS); 
      $preSum += round($dailyRain*$r_rain, 1); // $dailyRain is last data from previous day ... 
      $precip .= '['.$pdnr.','.round($dailyRain*$r_rain, 2).'],';
      $precipT .= '['.$pdnr.','.$preSum.'],';
      $windir .= round(array_sum($awindir)/count($awindir), 0).',';
      unset($awindir);
      $avgSolar .= round(array_sum($aSolar)/count($aSolar), 1).',';
      unset($aSolar);
      $avgUV .= round(array_sum($aUV)/count($aUV), 1).',';
      unset($aUV);
      $avgIndTemp .= round(array_sum($aIndTemp)/count($aIndTemp), 1).',';
      unset($aIndTemp);
      // NEXT DAY sign   
      $chDay = $actDay;
    }
    // CREATE ARRAYS AND VARIABLES FOR DATA CALCULATION
    $aTemp[] = convcf($row['temperature']);
    $aDP[] = convcf($row['dew_point_temperature']);
    $aHum[] = $row['outdoor_humidity'];
    $aBaro[] = round($row['barometer']*$r_baro, 2);
    $aWS[] = round($row['average_windspeed']*$r_wind, 1);
    $agWS[] = round($row['gust_windspeed']*$r_wind, 1);
    $awindir[] = $row['wind_direction'];
    $aSolar[] = $row['actual_solar_reading'];
    $aUV[] = $row['davis_vp_uv'];
    $aIndTemp[] = convcf($row['indoor_temperature']);
    $dailyRain = $row['daily_rainfall']; //  yesterday_rainfall system is a little bit problematic
    $pdtstamp = strtotime(substr($row['datetime'],0,10).' 00:00:00')*1000; //js timestamp; substr is for time correction
    $pdnr = substr($row['datetime'], 8, 2)*1;
  }
  
  date_default_timezone_set($TZconf); // Back to configured timezone
  
  if (mysql_affected_rows() < 2) { // we need at least two values for drawing a line in graph.
    if ($WUrequest == $thisMonth) {
      $emptyGraph = true;
    } else {
      $emptyGraphTP = true;
    }
  }
  
  // metric/imperial units conversion for spike correcting
  $baroSpike = $metric ? 1  : 33.86;
  $tempSpike = $metric ? 1  : 1.8 ;
  $windSpike = $metric ? 1  : 0.6214;
  
  $outData = 'var maxTemp = ['.rmSpikeYM(substr($maxTemp, 0, -1), $mysp['temp']*$tempSpike).'];
var avgTemp = ['.rmSpikeYM(substr($avgTemp, 0, -1), $mysp['temp']*$tempSpike).'];
var minTemp = ['.rmSpikeYM(substr($minTemp, 0, -1), $mysp['temp']*$tempSpike).'];
var maxDP   = ['.rmSpikeYM(substr($maxDP, 0, -1), $mysp['temp']*$tempSpike).'];
var avgDP   = ['.rmSpikeYM(substr($avgDP, 0, -1), $mysp['temp']*$tempSpike).'];
var minDP   = ['.rmSpikeYM(substr($minDP, 0, -1), $mysp['temp']*$tempSpike).'];
var maxHum  = ['.rmSpikeYM(substr($maxHum, 0, -1), $mysp['humi']).'];
var avgHum  = ['.rmSpikeYM(substr($avgHum, 0, -1), $mysp['humi']).'];
var minHum  = ['.rmSpikeYM(substr($minHum, 0, -1), $mysp['humi']).'];
var maxBaro = ['.rmSpikeYM(substr($maxBaro, 0, -1), $mysp['baro']/$baroSpike).'];
var avgBaro = ['.rmSpikeYM(substr($avgBaro, 0, -1), $mysp['baro']/$baroSpike).'];
var minBaro = ['.rmSpikeYM(substr($minBaro, 0, -1), $mysp['baro']/$baroSpike).'];
var maxWS   = ['.substr($maxWS, 0, -1).'];
var avgWS   = ['.substr($avgWS, 0, -1).'];
var gustWS  = ['.substr($gustWS, 0, -1).'];
var precipC = ['.substr($precip, 0, -1).'];
var precipT = ['.substr($precipT, 0, -1).'];
var precipS = "'.$precipStart.'";
var winddir = ['.substr($windir, 0, -1).'];
var avgSolar = ['.substr($avgSolar, 0, -1).'];
var avgUV = ['.substr($avgUV, 0, -1).'];
var avgIndTemp = ['.rmSpikeYM(substr($avgIndTemp, 0, -1), $mysp['temp']*$tempSpike).'];
var timeArray = ['.substr($timeArray, 0, -1).'];';

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
        mysql_query("UPDATE $dbname.$db_cache_table SET `data`='$outData', `last_access`='".date('Y-m-d H:i:s')."' WHERE `id`='month-$year$month'");
      } else {
        mysql_query("INSERT INTO $dbname.$db_cache_table (`id`, `data`) VALUES ('month-$year$month', '$outData')");
      }
    }
  }
}

if (strlen($outData) < 405) { // empty data detection for cached value
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

if ($debug) {
  $etimer = explode(' ', microtime());
  $etimer = $etimer[1] + $etimer[0];
  printf("Script timer: <b>%f</b> seconds.", ($etimer - $stimer));
  echo mysql_error().'<br>';
}
?>