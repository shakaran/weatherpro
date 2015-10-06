<?php
/**
 * Project:   WU GRAPHS
 * Module:    configurator.php 
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

session_start();

include_once('./WUG-settings.php');

$demo = false;
if ($demo) {
  $WUpassw = 'demo';
  $demot = '<br>DEMO';
  $demoi = 'Demo mode. For access use password: <i>demo</i><br><br>';  
}

$saveConfTxt = '';
$sesExTxt = '';
// unset login after 20 minutes
if($_SESSION['wulogin'] && time() - $_SESSION['accesst'] >= 60*20) { 
  $_SESSION['wulogin'] = false;
  $_SESSION['accesst'] = 0;
  $sesExTxt = '<span style="color:red;"><b>Sorry. Security session expired. Please login and configure again.</b></span><br />';
}
// logout
if ($_GET['logout']) {
  $_SESSION['wulogin'] = false;
  $_SESSION['accesst'] = 0;
  header('Location: '.$_SERVER['PHP_SELF']); // clean url parameters 
}

if (!is_file($conFile)) {
  $cInfoT = '<span style="color:orange; font-weight:bold;">Warning: File "'.basename($conFile).'" in "wxwugraphs" directory not exist. Reupload this file, or download configuration to file and then upload this file to "wxwugraphs" directory.</span>';
} else {
  if (!is_writable($conFile)) {
    $cInfoT = '<span style="color:orange; font-weight:bold;">Warning: File "'.basename($conFile).'" in "wxwugraphs" directory isn\'t writable for PHP (most often chmod 666). Change attributes for this file, or download configuration to file and then upload this file to "wxwugraphs" directory.</span>';
  } 
}

// BAD PASSWORD INFO
if (!empty($_POST['passw']) && $_POST['passw'] != $WUpassw && !$_SESSION['wulogin']) {
  $saveConfTxt = '<span style="color:red; font-weight:bold; font-size: 110%;">Bad password</span><br />Try again or reset password to default value by removing or editing file <i>settings.php</i> in <i>wxwugraphs</i> directory.<br /><br />';
}

// REMOVE CACHE
if ($_GET['rmc'] && $_SESSION['wulogin']) {
  if (!$demo) {
    // remove cached files
    rmrf(substr($WUcacheDir,0,-1));  
    // remove database cache
    if ($dataSource == 'mysql') {
      @mysql_connect($dbhost, $dbuser, $dbpass);
      @mysql_select_db($dbname);
      @mysql_query("TRUNCATE TABLE `$db_cache_table`");    
    }
    //$saveConfTxt = '<h3><span style="color:green; font-weight:bold;">Cache removed</span></h3>';
  }
  echo 'done';
  exit;
}
function rmrf($dir) {
  global $WUcacheDir;
  foreach (glob($dir) as $file) {
    if (is_dir($file)) { 
      rmrf("$file/*");
      if ($file != substr($WUcacheDir,0,-1)) {
        rmdir($file);
      }
    } else {
      if (basename($file) != 'WUG-version.txt') {
        unlink($file);
      }
    }
  }
} 

// GET CONFIGURATION for better support
if ($_GET['shconf']) {
  $cfgLines = file($conFile);
  foreach ($cfgLines as $cfgLineNr => $cfgLineText) {
    // Remove passwords info
    if ($cfgLineNr <= 4 || $cfgLineNr == 12) {
      continue;
    }
    // put linebreaks
    $cfgPrint .= $cfgLineText.'<br>'."\n";
  }
  echo $cfgPrint;
  exit; 
}

// EXTRACT LANGUAGE
if ($_GET['extlang']) {
  $cfgLines = file('./languages/WUG-language-'.$_GET['extlang'].'.php');
  foreach ($cfgLines as $cfgLineNr => $cfgLineText) {
    $cfgPrint .= $cfgLineText;
  }
  echo '<textarea style="width: 954px; height: 545px;">'.$cfgPrint.'</textarea>';
  exit; 
}

// GET LANGUAGE FILE
if ($_GET['getlang']) {
  $langdfile = './languages/WUG-language-'.$_GET['getlang'].'.php';
  // save configuration to file
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($langdfile));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  readfile($langdfile);
  exit; 
}

if ($_GET['clrwh']) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title>WUG configurator</title>
  </head>
  <body>
    <div>
      <br>
      Menu <b>-></b> "<i>Control panel</i>" <b>-></b> "<i>Web files/Web page</i>" icon <b>-></b> "<i>Realtime client FTW/AJAX/WDL</i>" tab <b>-></b> check "<i>Upload the clientrawhour every 5 minutes</i>"
      <br><br>
      <img src="./images/clientrawhour5m.png">
    </div>
  </body>
</html>
<?php
exit;
}

// TIMEZONES
if ($_GET['zones']) {
  if (strnatcmp(phpversion(),'5.2.0') >= 0) { // function timezone_identifiers_list() require at least PHP 5.2 
    $ce = explode('/',$TZconf,2);
    foreach (timezone_identifiers_list() as $zone) {
      $city = explode('/',$zone,2);
      if ($city[0] == $_GET['continent']) {
        $selz = $ce[1] == $city[1] ? ' selected' : '';
        echo '<option value="'.$city[1].'"'.$selz.'>'.$city[1].'</option>'."\n";  
      }
    }
  } else { // get zones from list... but thiese zones may don't work - depends on user PHP timezone database
    switch ($_GET['continent']) {
      case 'Africa':
        $zones = array('Abidjan','Accra','Addis_Ababa','Algiers','Asmara','Asmera','Bamako','Bangui','Banjul','Bissau','Blantyre','Brazzaville','Bujumbura','Cairo','Casablanca','Ceuta','Conakry','Dakar','Dar_es_Salaam','Djibouti','Douala','El_Aaiun','Freetown','Gaborone','Harare','Johannesburg','Kampala','Khartoum','Kigali','Kinshasa','Lagos','Libreville','Lome','Luanda','Lubumbashi','Lusaka','Malabo','Maputo','Maseru','Mbabane','Mogadishu','Monrovia','Nairobi','Ndjamena','Niamey','Nouakchott','Ouagadougou','Porto-Novo','Sao_Tome','Timbuktu','Tripoli','Tunis','Windhoek');
        break;
      case 'America':
        $zones = array('Adak','Anchorage','Anguilla','Antigua','Araguaina','Argentina/Buenos_Aires','Argentina/Catamarca','Argentina/ComodRivadavia','Argentina/Cordoba','Argentina/Jujuy','Argentina/La_Rioja','Argentina/Mendoza','Argentina/Rio_Gallegos','Argentina/Salta','Argentina/San_Juan','Argentina/San_Luis','Argentina/Tucuman','Argentina/Ushuaia','Aruba','Asuncion','Atikokan','Atka','Bahia','Bahia_Banderas','Barbados','Belem','Belize','Blanc-Sablon','Boa_Vista','Bogota','Boise','Buenos_Aires','Cambridge_Bay','Campo_Grande','Cancun','Caracas','Catamarca','Cayenne','Cayman','Chicago','Chihuahua','Coral_Harbour','Cordoba','Costa_Rica','Cuiaba','Curacao','Danmarkshavn','Dawson','Dawson_Creek','Denver','Detroit','Dominica','Edmonton','Eirunepe','El_Salvador','Ensenada','Fort_Wayne','Fortaleza','Glace_Bay','Godthab','Goose_Bay','Grand_Turk','Grenada','Guadeloupe','Guatemala','Guayaquil','Guyana','Halifax','Havana','Hermosillo','Indiana/Indianapolis','Indiana/Knox','Indiana/Marengo','Indiana/Petersburg','Indiana/Tell_City','Indiana/Vevay','Indiana/Vincennes','Indiana/Winamac','Indianapolis','Inuvik','Iqaluit','Jamaica','Jujuy','Juneau','Kentucky/Louisville','Kentucky/Monticello','Knox_IN','La_Paz','Lima','Los_Angeles','Louisville','Maceio','Managua','Manaus','Marigot','Martinique','Matamoros','Mazatlan','Mendoza','Menominee','Merida','Metlakatla','Mexico_City','Miquelon','Moncton','Monterrey','Montevideo','Montreal','Montserrat','Nassau','New_York','Nipigon','Nome','Noronha','North_Dakota/Beulah','North_Dakota/Center','North_Dakota/New_Salem','Ojinaga','Panama','Pangnirtung','Paramaribo','Phoenix','Port-au-Prince','Port_of_Spain','Porto_Acre','Porto_Velho','Puerto_Rico','Rainy_River','Rankin_Inlet','Recife','Regina','Resolute','Rio_Branco','Rosario','Santa_Isabel','Santarem','Santiago','Santo_Domingo','Sao_Paulo','Scoresbysund','Shiprock','Sitka','St_Barthelemy','St_Johns','St_Kitts','St_Lucia','St_Thomas','St_Vincent','Swift_Current','Tegucigalpa','Thule','Thunder_Bay','Tijuana','Toronto','Tortola','Vancouver','Virgin','Whitehorse','Winnipeg','Yakutat','Yellowknife');
        break;
      case 'Antarctica':
        $zones = array('Casey','Davis','DumontDUrville','Macquarie','Mawson','McMurdo','Palmer','Rothera','South_Pole','Syowa','Vostok');
        break;
      case 'Arctic':
        $zones = array('Longyearbyen');
        break;
      case 'Asia':
        $zones = array('Aden','Almaty','Amman','Anadyr','Aqtau','Aqtobe','Ashgabat','Ashkhabad','Baghdad','Bahrain','Baku','Bangkok','Beirut','Bishkek','Brunei','Calcutta','Choibalsan','Chongqing','Chungking','Colombo','Dacca','Damascus','Dhaka','Dili','Dubai','Dushanbe','Gaza','Harbin','Ho_Chi_Minh','Hong_Kong','Hovd','Irkutsk','Istanbul','Jakarta','Jayapura','Jerusalem','Kabul','Kamchatka','Karachi','Kashgar','Kathmandu','Katmandu','Kolkata','Krasnoyarsk','Kuala_Lumpur','Kuching','Kuwait','Macao','Macau','Magadan','Makassar','Manila','Muscat','Nicosia','Novokuznetsk','Novosibirsk','Omsk','Oral','Phnom_Penh','Pontianak','Pyongyang','Qatar','Qyzylorda','Rangoon','Riyadh','Saigon','Sakhalin','Samarkand','Seoul','Shanghai','Singapore','Taipei','Tashkent','Tbilisi','Tehran','Tel_Aviv','Thimbu','Thimphu','Tokyo','Ujung_Pandang','Ulaanbaatar','Ulan_Bator','Urumqi','Vientiane','Vladivostok','Yakutsk','Yekaterinburg','Yerevan');
        break;
      case 'Atlantic':
        $zones = array('Azores','Bermuda','Canary','Cape_Verde','Faeroe','Faroe','Jan_Mayen','Madeira','Reykjavik','South_Georgia','St_Helena','Stanley');
        break;
      case 'Australia':
        $zones = array('ACT','Adelaide','Brisbane','Broken_Hill','Canberra','Currie','Darwin','Eucla','Hobart','LHI','Lindeman','Lord_Howe','Melbourne','North','NSW','Perth','Queensland','South','Sydney','Tasmania','Victoria','West','Yancowinna');
        break;
      case 'Europe':
        $zones = array('Amsterdam','Andorra','Athens','Belfast','Belgrade','Berlin','Bratislava','Brussels','Bucharest','Budapest','Chisinau','Copenhagen','Dublin','Gibraltar','Guernsey','Helsinki','Isle_of_Man','Istanbul','Jersey','Kaliningrad','Kiev','Lisbon','Ljubljana','London','Luxembourg','Madrid','Malta','Mariehamn','Minsk','Monaco','Moscow','Nicosia','Oslo','Paris','Podgorica','Prague','Riga','Rome','Samara','San_Marino','Sarajevo','Simferopol','Skopje','Sofia','Stockholm','Tallinn','Tirane','Tiraspol','Uzhgorod','Vaduz','Vatican','Vienna','Vilnius','Volgograd','Warsaw','Zagreb','Zaporozhye','Zurich');
        break;
      case 'Indian':
        $zones = array('Antananarivo','Chagos','Christmas','Cocos','Comoro','Kerguelen','Mahe','Maldives','Mauritius','Mayotte','Reunion');
        break;
      case 'Pacific':
        $zones = array('Apia','Auckland','Chatham','Chuuk','Easter','Efate','Enderbury','Fakaofo','Fiji','Funafuti','Galapagos','Gambier','Guadalcanal','Guam','Honolulu','Johnston','Kiritimati','Kosrae','Kwajalein','Majuro','Marquesas','Midway','Nauru','Niue','Norfolk','Noumea','Pago_Pago','Palau','Pitcairn','Pohnpei','Ponape','Port_Moresby','Rarotonga','Saipan','Samoa','Tahiti','Tarawa','Tongatapu','Truk','Wake','Wallis','Yap');
        break;
    }
    $ce = explode('/',$TZconf,2);
    foreach ($zones as $val) {
      $selz = $ce[1] == $val ? ' selected' : '';
      echo '<option value="'.$val.'"'.$selz.'>'.$val.'</option>'."\n";
    }
  }
  exit;  
}


// replace empty imput with defalt value
function nte($old, $new) {
  if (empty($new)) {
    return $old;
  } else {
    return str_replace(',', '.', $new);
  }
}
// slash at end for directories
function eslash ($imput) {
  if (substr($imput, -1) != '/' && substr($imput, -1) != '\\' && $imput != 'auto' && $imput != '') {
    if (strstr($imput, '\\')) {
      return $imput.'\\';
    } else {
      return $imput.'/';
    }
  } else {
    return $imput;
  }
}
// escape double quotes
function rmdq ($string) {
  return str_replace('"','\"',$string);
}

$relogin = false;
if ($_POST['WUpassw1'] != $_POST['WUpassw2'] && !empty($_POST['WUpassw2'])) {
  $passText = '<b>but password for WU Graphs do not match. Please try again change password.</b><br /><br />';
  $savePass = $WUpassw;
} elseif (!empty($_POST['WUpassw1']) && !empty($_POST['WUpassw2'])) {
  $savePass = $_POST['WUpassw1'];
  $relogin = true; // login again with new password
} else {
  $savePass = $WUpassw;
}

if ($_POST['dataSource'] && $_SESSION['wulogin']) {
$saveString = '<?php
### WU GRAPHS configuration ###
// This file is generated by configurator.php

$WUpassw = "'.$savePass.'";
$stationName = "'.rmdq($_POST['stationName']).'";
$sinceY = "'.nte($sinceY, $_POST['sinceY']).'";
$sinceM = "'.nte($sinceM, $_POST['sinceM']).'"; 
$sinceD = "'.nte($sinceD, $_POST['sinceD']).'";
$dataSource = "'.$_POST['dataSource'].'";
$dbhost = "'.$_POST['dbhost'].'";
$dbuser = "'.$_POST['dbuser'].'";
$dbpass = "'.$_POST['dbpass'].'";
$dbname = "'.$_POST['dbname'].'";
$dbtable = "'.$_POST['dbtable'].'";
$datetime_col = "'.$_POST['datetime_col'].'";
$db_wind = "'.$_POST['db_wind'].'";
$db_temp = "'.$_POST['db_temp'].'";
$db_rain = "'.$_POST['db_rain'].'";
$db_baro = "'.$_POST['db_baro'].'";
$db_rate = "'.$_POST['db_rate'].'";
$wdMonthLim = '.$_POST['wdMonthLim'].';
$wdYearLim = '.$_POST['wdYearLim'].';
$db_cache_type = "'.$_POST['db_cache_type'].'";  
$db_cache_table = "'.$_POST['db_cache_table'].'";
$hourGraphs = "'.$_POST['hourGraphs'].'"; 
$clientRawHpath = "'.rmdq(eslash($_POST['clientRawHpath'])).'";
$wugTheme = "'.$_POST['wugTheme'].'";
$WUID = "'.strtoupper($_POST['WUID']).'";
$TZconf = "'.$_POST['tzContinent'].'/'.$_POST['tzCountry'].'"; 
//$fixedTime = '.$_POST['fixedTime'].';
$ddFormat = '.$_POST['ddFormat'].';
$hourFormat = '.$_POST['hourFormat'].'; 
$wugWidth = "'.nte($wugWidth, $_POST['wugWidth']).'";
$wugHeight = "'.nte($wugHeight, $_POST['wugHeight']).'";
$defaultWUGlang = "'.$_POST['defaultWUGlang'].'";
$langSwitch = '.$_POST['langSwitch'].';
$metric = '.$_POST['metric'].';
$windmu = "'.$_POST['windmu'].'";
$showSolar = '.$_POST['showSolar'].';
$wugWinW = "'.nte($wugWinW, $_POST['wugWinW']).'";
$wugWinH = "'.nte($wugWinH, $_POST['wugWinH']).'";
$cookieExp = '.nte($cookieExp, $_POST['cookieExp']).';
$calcMbaroAvg = '.$_POST['calcMbaroAvg'].';
$calcSolar = '.$_POST['calcSolar'].';
$calcWindDir = '.$_POST['calcWindDir'].';
$removeSpikes = '.$_POST['removeSpikes'].';
$dsp = array("temp" => '.nte($dsp['temp'], $_POST['dspTemp']).', "baro" => '.nte($dsp['baro'], $_POST['dspBaro']).', "rain_rate" => '.nte($dsp['rain_rate'], $_POST['dspRRate']).', "rain_total" => '.nte($dsp['rain_total'], $_POST['dspRTotal']).', "humi" => '.nte($dsp['humi'], $_POST['dspHumi']).');
$mysp = array("temp" => '.nte($mysp['temp'], $_POST['myspTemp']).', "baro" => '.nte($mysp['baro'], $_POST['myspBaro']).', "humi" => '.nte($mysp['humi'], $_POST['myspHumi']).');
$IcacheWUfiles = '.$_POST['IcacheWUfiles'].';
$WUcacheDirI = "'.rmdq(eslash(nte('auto', $_POST['WUcacheDirI']))).'";
$Iprecache = '.$_POST['Iprecache'].';
$pause = '.($_POST['pause'] >= 120 ? nte($pause, $_POST['pause']) : 120).';
$maxPre = '.($_POST['maxPre'] > 5 ? 5 : nte($maxPre, $_POST['maxPre'])).';
//$refreshForced = '.$_POST['refreshForced'].';
//$autoRefreshT = "'.nte($autoRefreshT, $_POST['autoRefreshT']).'";
$creditsEnabled = "'.$_POST['creditsEnabled'].'";
$creditsURL = "'.$_POST['creditsURL'].'";
$credits = "'.rmdq($_POST['credits']).'";
//$jQueryFile = "'.$_POST['jQueryFile'].'";
$loadJQuery = '.$_POST['loadJQuery'].';
$incTabsStyle = '.$_POST['incTabsStyle'].';
//$testOn = '.$_POST['testOn'].';
$updateCheck = '.$_POST['updateCheck'].';
$SendName = '.$_POST['SendName'].';
$heightCorr = "'.nte($heightCorr, $_POST['heightCorr']).'";
$standAlone = '.$_POST['standAlone'].';
//$includeMode = '.$_POST['includeMode'].';
$spline_graphs = '.$_POST['spline_graphs'].';
$fopenOff = '.$_POST['fopenOff'].';
//$jsPath = "'.$_POST['jsPath'].'";
$cookieEnabled = '.$_POST['cookieEnabled'].';
$sendAgent = '.$_POST['sendAgent'].';
$no_mb = '.$_POST['no_mb'].';
$db_i_temp = '.$_POST['db_i_temp'].';
$db_suv = '.$_POST['db_suv'].';
$CustomFontTheme = '.$_POST['CustomFontTheme'].';
$colorpickerFontVal = "'.$_POST['colorpickerFontVal'].'";
$CustomBgTheme = '.$_POST['CustomBgTheme'].';
$colorpickerBgVal = "'.$_POST['colorpickerBgVal'].'";
$baroMinMax = '.$_POST['baroMinMax'].';
//$debug = '.$_POST['debug'].';
$wdSinceY = "'.nte($sinceY, $_POST['wdSinceY']).'";
$wdSinceM = "'.nte($sinceM, $_POST['wdSinceM']).'"; 
$wdSinceD = "'.nte($sinceD, $_POST['wdSinceD']).'";
?>';
?>
<?php  
  // SAVE CONFIG TROUGHT BROWSER TO FILE
  if ($_POST['saveT'] == 'file') {
    // save configuration to file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($conFile));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    //header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    echo $saveString;
    exit;
  }

  // RESET CONFIG - USE DEFAULT
  if ($_POST['saveT'] == 'reset') {
    $saveString = '<?php
// This file is generated by configurator.php
// USING DEFAULT VALUES from WUG-settings.php    
?>';
?>
<?php
  }

  //SAVE CONFIG
  if (!$demo) {
    $saveC = @fopen($conFile, "w"); //$conFile is in wug-settings.php
    if ($saveC && fwrite($saveC, $saveString)) {
      $saveConfTxt .= '<h3><span style="color:green; font-weight:bold;">Configuration saved</span></h3><h4>You can change file attributes on "settings.php" to 644.</h4>';
    } else {
      $saveConfTxt .= '<h3><span style="color:red; font-weight:bold;">Error: Can\'t write to configuration file.</span></h3><h4>You can try download configuration to file and then upload this file to "wxwugraphs" directory.</h4>';
    }
    @fclose($saveC);
  }

  $saveConfTxt .= $passText;

  // CREATE TABLE FOR WD MySQL CACHING    
  if ($_POST['db_cache_type'] == 'db') {
    mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
    mysql_select_db($_POST['dbname']);  
    $tbfound = false;
    $sqil = mysql_query("SHOW TABLES FROM ".$_POST['dbname']);
    while ($row = mysql_fetch_row($sqil)) {
      if ($row[0] == $_POST['db_cache_table']) {
        $tbfound = true;
      }
    }
    if (!$tbfound) {
      mysql_query("CREATE TABLE ".$_POST['dbname'].".`".$_POST['db_cache_table']."` (`id` VARCHAR(15) NOT NULL ,`last_access` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `data` LONGTEXT NOT NULL ,PRIMARY KEY (`id`)) ENGINE = MYISAM;");  
    }
    $tbfound2 = false;
    $sqil = mysql_query("SHOW TABLES FROM ".$_POST['dbname']);
    while ($row = mysql_fetch_row($sqil)) {
      if ($row[0] == $_POST['db_cache_table']) {
        $tbfound2 = true;
      }
    }
    if (!$tbfound2) {
      $saveConfTxt .= '<span style="color:orange; font-weight: bold;">Error in creating cache table for WD MySQL datasource.</span><br />Can\'t create table <i>'.$_POST['db_cache_table'].'</i> in database <i>'.$_POST['dbname'].'</i> with username <i>'.$_POST['dbuser'].'</i>. Maybe this user don\'t have enough privilegies for this action or is misconfigured hostname, database name, username or password for MySQL connection.<br /> Eventualy you can try create table manually with this SQL command:<br />'."CREATE TABLE ".$_POST['dbname'].".`".$_POST['db_cache_table']."` (`id` VARCHAR(15) NOT NULL ,`last_access` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `data` LONGTEXT NOT NULL ,PRIMARY KEY (`id`)) ENGINE = MYISAM;<br>";  
    }
  }
  //GET NEW CONFIG
  include($conFile);
}
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Pragma: anytextexeptno-cache', true);
header('Cache-control: private');
header('Expires: 0');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  <title>WUG configurator</title>
  <style type="text/css">
  body {
    font-size: 16px;
  }
  .lside {
    text-align: center;
    width:25%;
  }
  .cside {width:20%; }
  .rside {padding:4px; }
  .tblt {text-align: left;}
  #conftab {
    width:730px;
    margin: 0px auto;
    border: 2px solid black;
    background-color:#F5F5F5;
    border-radius: 12px;
  }
  #logout {
    margin: -10px auto 5px;
    width: 730px;
  }
  #conftab td {
    border-bottom: 1px solid lightgrey;
    padding: 5px 2px 5px 5px;
  }
  td.title1, td.title0 {
    background-color: #D5D5D5;
    font-size: 120%;
    font-weight: bold;
    padding: 8px 0;
    text-align: center;
  }
  td.title1 {
    border-top: 1px dashed black;
  }
  #con-title {
    font: bold 150% bold;
    text-align: center;
    text-decoration: underline;
  	filter: Shadow(Color=#808080, Direction=135, Strength=2);
    text-shadow: 2px 2px 2px gray;
    width: 730px;  
  }
  #rmc {
    font-size: 120%;
  }
  #pptable {width: 730px; background-color: #FFF4C8; border: 2px solid black; margin: 7px auto; padding: 3px;}
  form {margin: 0px;}
  /* Color picker */
  .colsel div {
      background: url("images/colorpicker/select.png") repeat scroll center center transparent;
      height: 30px;
      width: 30px;
  }
  h3 {text-align:center}
  h4 {text-align:center}
  </style>
  <link type="text/css" href="css/colorpicker.css" rel="stylesheet">
  <script type="text/javascript" src="<?php echo $jQueryFile; ?>"></script>
	
  <!-- Color picker -->
  <script type="text/javascript" src="js/colorpicker/colorpicker.js"></script>
  <script type="text/javascript" src="js/colorpicker/eye.js"></script>
  <script type="text/javascript" src="js/colorpicker/utils.js"></script>  
  <script src="js/colorpicker/layout.js?ver=1.0.2" type="text/javascript"></script>
  
  <script type="text/javascript" src="js/stickyfloat.js"></script>
  <script type="text/javascript">
  $(function(){    
    // Timezone selection
    function tzchange () {
      $.ajax({
          type: "GET",
          url: "configurator.php",
          data: "zones=1&continent="+$('#tzContinent option:selected').val(),
          success: function(data){
              $('#tzCountry').html(data);
          }
      });
    }
    tzchange(); // first start
    $('#tzContinent').change(function(){
      tzchange();  
    });
    
    // wunderground or wd mysql
    function sourceChang () {
      if ($('#source option:selected').val() == 'wunderground') {
        $('.wus').fadeIn(600);
        $('.wds').hide();
        $('.wdu').hide();
        $('.wuToWDinfo').hide();
      } else if ($('#source option:selected').val() == 'mysql') {
        $('.wds').fadeIn(600);
        $('.wus').hide();
        $('.wdu').hide();
        $('.wuToWDinfo').hide();      
      } else {
        $('.wus').fadeIn(600);
        $('.wds').fadeIn(600);
        $('.wdu').show();
        $('.wuToWDinfo').fadeIn(600);
      }
    }
    sourceChang(); // initiate
    $('#source').change(function(){
      sourceChang();
    });
    
    // Clientrawhour selection
    function clrawC() {
      if ($('[name="hourGraphs"] option:selected').val() == 'craw') {
        $('#clrawPath').fadeIn(600);  
      } else {
        $('#clrawPath').fadeOut(600);
      }
    }
    clrawC(); // initiate    
    $('[name="hourGraphs"]').change(function(){
      clrawC();
    });
    
    // wind speed units selection
    function wspd () {
      if ($('#metrics option:selected').val() == 'true') {
        $('.wspd').show();
      } else {
        $('.wspd').hide();      
      }    
    }
    wspd();
    $('#metrics').change(function(){
      wspd();
    });
    
    // remove spikes
    function rmsp () {
      if ($('#rmspik option:selected').val() == 'true') {
        $('.spik').fadeIn(600);
      } else {
        $('.spik').fadeOut(600);      
      }    
    }
    rmsp();
    $('#rmspik').change(function(){
      rmsp();
    });
    
    // COLOR PICKER
    // show hide color picker
    function pickerIt () {
      if ($('#CustomBgTheme option:selected').val() == 'false' || $('#CustomBgTheme option:selected').val() == "'transparent'") {
        $('#BgColorSelector').hide();
      } else {
        $('#BgColorSelector').show();      
      }
    }
    pickerIt(); // initiate
    $('#CustomBgTheme').change(function(){
      pickerIt();
    });    
    function pickerIt2 () {
      if ($('#CustomFontTheme option:selected').val() == 'false') {
        $('#FontColorSelector').hide();
      } else {
        $('#FontColorSelector').show();      
      }
    }
    pickerIt2(); // initiate
    $('#CustomFontTheme').change(function(){
      pickerIt2();
    });   
    //color picker config
    $('#BgColorSelector').ColorPicker({
    	color: '<?php echo $colorpickerBgVal; ?>', // get from php....
    	onShow: function (colpkr) {
    		$(colpkr).fadeIn(500);
    		return false;
    	},
    	onHide: function (colpkr) {
    		$(colpkr).fadeOut(500);
    		return false;
    	},
    	onChange: function (hsb, hex, rgb) {
    		$('#BgColorSelector div').css('backgroundColor', '#' + hex);
    		$('#colorpickerBgVal').val('#'+hex);
    	}
    });  
    $('#FontColorSelector').ColorPicker({
    	color: '<?php echo $colorpickerFontVal; ?>', // get from php....
    	onShow: function (colpkr) {
    		$(colpkr).fadeIn(500);
    		return false;
    	},
    	onHide: function (colpkr) {
    		$(colpkr).fadeOut(500);
    		return false;
    	},
    	onChange: function (hsb, hex, rgb) {
    		$('#FontColorSelector div').css('backgroundColor', '#' + hex);
    		$('#colorpickerFontVal').val('#'+hex);
    	}
    });
    
    // DATETIME COL SWITCHING
    function dtcs () {
      if ($('#datetime_col option:selected').val() == 'no') {
        $('.lims').hide();
        $('select[name=wdMonthLim] option:selected, select[name=wdYearLim] option:selected' ).removeAttr('selected');
        $('select[name=wdMonthLim] option[value="15"], select[name=wdYearLim] option[value="15"]').attr('selected',' ');
      } else {
        $('.lims').show();      
      }
    }
    dtcs(); // initiate    
    $('#datetime_col').change(function(){
      dtcs();
    }); 
    /* sticky/floating donate box */
    $('#donate').stickyfloat({duration: 700/*, easing: "easeOutElastic"*/});

    
    // DATETIME WIZARD
    
    /*  -- deprecated method -- because in defaults browsers prevent to open new window by javascript
    $('#wizz').click(function(){
      $.post("./datetime-tool.php", { host: $('[name="dbhost"]').val(), user: $('[name="dbuser"]').val(), pass: $('[name="dbpass"]').val(), name: $('[name="dbname"]').val(), table: $('[name="dbtable"]').val(), pg: 'home1'},  function (data) {  
          var win=window.open('about:blank'); 
          with(win.document) 
          { 
              open(); 
              write(data); 
              close(); 
          } 
      });     
      return false;
    });
    */
    $('#wizz').click(function(){
      $('#formhid [name="host"]').val($('[name="dbhost"]').val());
      $('#formhid [name="user"]').val($('[name="dbuser"]').val());
      $('#formhid [name="pass"]').val($('[name="dbpass"]').val());
      $('#formhid [name="name"]').val($('[name="dbname"]').val());
      $('#formhid [name="table"]').val($('[name="dbtable"]').val());
      $('#formhid').submit();
      return false;    
    });
    
  }); // END JQUERY DOC READY

  function rmcache () {
    var question = confirm('Really remove whole WU graphs cache?');
    if (question) {
      $.ajax({
          type: "GET",
          url: "configurator.php",
          data: "rmc=1",
          success: function(data){
              if (data == 'done') {
                alert ('WU graphs cache data has been removed.');
              } else {
                alert ('Security session expired. Please login again and then remove graphs cache.');
                location.href='<?php echo $_SERVER['PHP_SELF']; ?>';
              }
          }
      });
    }
  }
  
  </script>
  </head>
  <body>
<?php
if ($_POST['passw'] != $WUpassw && !$_SESSION['wulogin'] && !$relogin) {
  $WUGbackPath = $standAlone ? '../wugraphs.php' : '../wxwugraphs.php';
?>
  <div id="maindiv" style="text-align: center;">
  <div id="con-title" style="margin: 10px auto 15px">- WU Graphs Configurator -<?php echo $demot; ?></div>
    <div style="text-align: center;">
      <?php echo $sesExTxt.$saveConfTxt.$demoi; ?>
      <form id="formp" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-bottom:10px;">
        <table allign="center" style="width:270px; margin:0px auto;">
          <tr>
            <td>Password: 
            <td><input name="passw" type="password" size="12">
            <td><input type="submit" value="Submit" name="send">
        </table>
      </form>
      <a href="<?php echo '../' ?>">Back to website</a>
    </div>
  </div>
<?php
} else {
$_SESSION['wulogin'] = true;
$_SESSION['accesst'] = time();
?>
  <div id="maindiv" style="text-align: center;">
  <div id="content" style="width:750px; margin:0 auto; padding-left: 165px; text-align:left;">
  <div id="con-title" style="margin: 10px 0 15px 7px;">- WU Graphs Configurator -<?php echo $demot; ?></div>
      <?php echo $saveConfTxt; ?>
      <div id="logout" style="text-align:center;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logout=1" title="Autologout after 15 minutes">Logout</a></div>
<?php      
if ($cInfoT) {
echo '     <div style="text-align: center; width:600px; margin: 0px auto;">'.$cInfoT.'</div>';
}
?>
      <div id="donate" style="position:absolute; width:160px; background-color:#FFF4C8; border:2px solid black; padding:6px 3px; margin-left:-175px; margin-top: 67px; text-align:center; border-radius: 12px;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHRwYJKoZIhvcNAQcEoIIHODCCBzQCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYA3IdAmOgISCxkuaQJzng/itdSYLax137QLWf5nZoNK0CEiVwtW9NHrS+5GhRuAqZ1avICBQ/YDpgcfzHsxP1pIponnFWjP/2oGmpE7Zs4cg5AIqwVtF5kT7Tt739q08S2el3WGtP6kDvysaQYtUzz6T0AadZ/9+CrTDeAgLmNyfjELMAkGBSsOAwIaBQAwgcQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIynVtjilkN9OAgaCKzc9K3pJII97+R7bEMjnU1w3LehI30TiJbVJztRyv35/EIXxJh/wbw3dajfVsJ6ghwekssWfNgN9d4lfJV2bfkbIUrRXgQEF3L9ewJxUlCruqt6tK2jXglZ/joWR/ZmjMcQHN4ZVMY0H/IUmpykwt6dewKWQYFUc7xwUA2r+i0IUYWYFkrMEzNA3pSIT2LDdQrnlJ1Et+MyVPXR9LdRZToIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTAwNzIyMTU1NDMwWjAjBgkqhkiG9w0BCQQxFgQUG1qI+u+4DT7RSc8Dw+0reKC6M98wDQYJKoZIhvcNAQEBBQAEgYAIqe9y+wJtjlDM+wtQvGlf8e52ej8carQtvUthRtLyamSNwoeaA1AXRGnVTrLMfp1No0N3tu+Vt00MTetAMuSfAb9J+tVKEQFyYj8yX/9ymsDjdw536rg4w9dTw3k5OzOnlxo+sDeaLcv/hAVycYgCHqLhu3UTeT+XLsu/PCqHFw==-----END PKCS7-----
        ">
                <input type="image" src="./images/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="./images/pixel.gif" width="1" height="1">
                <br>
                If you find these scripts useful to you,
                please consider making a small donation to help offset my time working on them.<br>
                Thanks for your kind support!
        </form>
      </div>      
      <form id="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table id="conftab" align="center" cellspacing="0">
          <tr>   
            <td colspan="3" class="title0" style="border-radius: 12px 12px 0 0;">Main configuration
          <tr>
            <td class="lside">Station name:
            <td class="cside"><input name="stationName" value="<?php echo $stationName; ?>">
            <td class="rside">Used in graphs and page titles
          <tr>
            <td colspan="3" class="tblt" style="padding:0 0 0 30px;"><b>Date when station started to send data</b>
          <tr>
            <td class="lside">Year:
            <td class="cside">
              <select name="sinceY">
                <?php
                for ($i=1980;$i<=date('Y');$i++){
                  $sinceys = $sinceY == $i ? ' selected' : '';
                  echo '<option value="'.$i.'"'.$sinceys.'>'.$i.'</option>';
                }
                ?>
              </select>
            <td class="rside"> &nbsp;
          <tr>
            <td class="lside">Month:
            <td class="cside">
              <select name="sinceM">
                <?php
                for ($i=1;$i<=12;$i++){
                  $sincems = $sinceM == $i ? ' selected' : '';
                  echo '<option value="'.$i.'"'.$sincems.'>'.$i.'</option>';
                }
                ?>
              </select>
            <td class="rside"> &nbsp;
          <tr>
            <td class="lside">Day:
            <td class="cside">
              <select name="sinceD">
                <?php
                for ($i=1;$i<=31;$i++){
                  $sinceds = $sinceD == $i ? ' selected' : '';
                  echo '<option value="'.$i.'"'.$sinceds.'>'.$i.'</option>';
                }
                ?>
              </select>
            <td class="rside">&nbsp;
          <tr>
            <td class="lside">Password:<br>Password again:
            <td class="cside"><input name="WUpassw1" type="password"><br><input name="WUpassw2" type="password">
            <td class="rside">Change password used in <a href="WUG-test.php" target="_blank">WUG-test.php</a>, this configurator and for removing cache.
          <tr>
            <td class="lside" id="cache-dir">Cache directory:
            <td class="cside"><input name="WUcacheDirI" value="<?php echo $WUcacheDirI; ?>">
            <td class="rside">Cache directory must be writable PHP (most often chmod&nbsp;777). Must be absolute path with slashes at end. If you do not know the absolute path to your website directory, try set to <i>auto</i> (cache directory will be in wxwugraphs).
          <tr>
            <td class="lside">Solar graphs:
            <td class="cside">
              <select name="showSolar">
                <option value="true"<?php if ($showSolar){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$showSolar){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">If you don't have solar sensor, set this value to Disabled (graph will be disabled/hid in graph selection and tabs)            
          <tr>
            <td class="lside">Standalone:
            <td class="cside">
              <select name="standAlone">
                <option value="true"<?php if ($standAlone){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$standAlone){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside"><i>Disabled</i>: use wxwugraphs.php (intended for Saratoga / Carterlake weather web templates)<br /><i>Enabled</i>: use wugraphs.php
          <tr>
            <td class="lside">Default language:
            <td class="cside">
              <select name="defaultWUGlang">
              <?php
              include ('./languages/langlist.php');
              foreach ($langList as $key => $val) {
                $langS = $defaultWUGlang == $key ? ' selected' : '';
                echo '<option value="'.$key.'"'.$langS.'>['.$key.'] - '.$val.'</option>';
              }              
              ?>
              </select>
            <td class="rside">&nbsp;
          <tr>                          
            <td class="lside">Units:
            <td class="cside">
              <select id="metrics" name="metric">
                <option value="true"<?php if ($metric){echo ' selected';}?>>Metric</option>
                <option value="false"<?php if (!$metric){echo ' selected';}?>>Imperial</option>
              </select>
            <td class="rside">Choose units used in graphs output.
          <tr>
            <td class="lside">Timezone:
            <td class="cside">
              <select id="tzContinent" name="tzContinent">
              <?php
              $continents = array('Africa','America','Antarctica','Arctic','Asia','Atlantic','Australia','Europe','Indian','Pacific');
              $ce = explode('/',$TZconf,2);
              foreach ($continents as $continent) {
                $conS = $ce[0] == $continent ? ' selected' : '';
                echo '<option value="'.$continent.'"'.$conS.'>'.$continent.'</option>';
              }
              ?>  
              </select>
              <br>
              <select id="tzCountry" name="tzCountry">
              </select>
              <!--<input name="TZconf" value="<?php echo $TZconf; ?>">-->
            <td class="rside">Select closest location to your weather station
          <tr>
            <td class="lside"><b>Graphs data source:</b>
            <td class="cside">
              <select id="source" name="dataSource">
                <option value="mysql"<?php if ($dataSource == 'mysql'){echo ' selected';}?>>WD MySQL database</option>
                <option value="wunderground"<?php if ($dataSource == 'wunderground'){echo ' selected';} ?>>Wunderground.com</option>
                <option value="wutowdmysql"<?php if ($dataSource == 'wutowdmysql'){echo ' selected';} ?>>Combined</option>
              </select>
            <td class="rside"><span class="wuToWDinfo">Combined datasource is useful if you started using WD MySQL and you want to have also available older data from Wunderground data source</span>&nbsp;
            
                                 
          <tr class="wus">   
            <td colspan="3" class="title1">Settings for Weather Underground data source
          
          <tr class="wus">
            <td class="lside">Wunderground ID:
            <td class="cside"><input name="WUID" value="<?php echo $WUID; ?>">
            <td class="rside">YOUR Wunderground station ID. Use big letters. (for testing you can try <i>KWIMAUST1</i> - more history with sun sensor - since 2005)
          <tr class="wus">
            <td class="lside">WU Caching:
            <td class="cside">
              <select name="IcacheWUfiles">
                <option value="true"<?php if ($IcacheWUfiles){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$IcacheWUfiles){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">WU Caching enable graphs data file caching (significantly improve graph loading speed)
          <tr class="wus">
            <td class="lside">Precaching:
            <td class="cside">
              <select name="Iprecache">
                <option value="true"<?php if ($Iprecache){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$Iprecache){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">Precaching create WU cache files for other days when visitor browsing at graph pages.
          <tr class="wus">
            <td class="lside">Precaching interval:
            <td class="cside"><input name="pause" value="<?php echo $pause; ?>" size="4"> seconds
            <td class="rside">Pause between creating next cache file (minimum is 120)
          <tr class="wus">
            <td class="lside">Precached Years:
            <td class="cside"><input name="maxPre" value="<?php echo $maxPre; ?>" size="4"> years
            <td class="rside">Number of precached years backward from today (max:5)
          <tr class="wus">
            <td class="lside">Calculate avg barometric pressure:
            <td class="cside">
              <select name="calcMbaroAvg">
                <option value="true"<?php if ($calcMbaroAvg){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$calcMbaroAvg){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">Calculate values for month/year graphs from daily data
          <tr class="wus">
            <td class="lside">Calculate avg solar radiation:
            <td class="cside">
              <select name="calcSolar">
                <option value="true"<?php if ($calcSolar){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$calcSolar){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">Calculate values for month/year graphs from daily data. If is enabled <i>solar graphs</i> and this feature, then solar tab appear in month/year graphs.
          <tr class="wus">
            <td class="lside">Calculate avg wind direction:
            <td class="cside">
              <select name="calcWindDir">
                <option value="true"<?php if ($calcWindDir){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$calcWindDir){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">Calculate values for month/year graphs from daily data
          <tr class="wds">
            <td colspan="3" class="title1">Settings for WD MySQL data source
          
          <tr class="wds wdu">
            <td colspan="3" class="tblt" style="padding:0 0 0 30px;"><b>Date when station started send data to WD MySQL</b>
          <tr class="wds wdu">
            <td class="lside">Year:
            <td class="cside">
              <select name="wdSinceY">
                <?php
                for ($i=1980;$i<=date('Y');$i++){
                  $sinceys = $wdSinceY == $i ? ' selected' : '';
                  echo '<option value="'.$i.'"'.$sinceys.'>'.$i.'</option>';
                }
                ?>
              </select>
            <td class="rside"> &nbsp;
          <tr class="wds wdu">
            <td class="lside">Month:
            <td class="cside">
              <select name="wdSinceM">
                <?php
                for ($i=1;$i<=12;$i++){
                  $sincems = $wdSinceM == $i ? ' selected' : '';
                  echo '<option value="'.$i.'"'.$sincems.'>'.$i.'</option>';
                }
                ?>
              </select>
            <td class="rside"> &nbsp;
          <tr class="wds wdu">
            <td class="lside">Day:
            <td class="cside">
              <select name="wdSinceD">
                <?php
                for ($i=1;$i<=31;$i++){
                  $sinceds = $wdSinceD == $i ? ' selected' : '';
                  echo '<option value="'.$i.'"'.$sinceds.'>'.$i.'</option>';
                }
                ?>
              </select>
            <td class="rside">&nbsp;            

          <tr class="wds">    
            <td class="lside">Host name:
            <td class="cside"><input name="dbhost" value="<?php echo $dbhost; ?>">
            <td class="rside">Database server address 
          <tr class="wds">
            <td class="lside">Username:
            <td class="cside"><input name="dbuser" value="<?php echo $dbuser; ?>">
            <td class="rside">User name with privileges to given database
          <tr class="wds">
            <td class="lside">Password:
            <td class="cside"><input name="dbpass" value="<?php echo $dbpass; ?>" type="password">
            <td class="rside">Password for username
          <tr class="wds">
            <td class="lside">Database name:
            <td class="cside"><input name="dbname" value="<?php echo $dbname; ?>">
            <td class="rside">Name of database where table with measured values is stored
          <tr class="wds">
            <td class="lside">Table name:
            <td class="cside"><input name="dbtable" value="<?php echo $dbtable; ?>">
            <td class="rside">Name of table with measured values
          <tr class="wds">
            <td class="lside">Datetime column:
            <td class="cside">
              <select name="datetime_col" id="datetime_col">
                <option value="no"<?php if ($datetime_col == 'no'){echo ' selected';}?>>No</option>
                <option value="yes"<?php if ($datetime_col == 'yes'){echo ' selected';}?>>Yes</option>
              </select>
            <td class="rside">Does your MySQL table have a datetime column?<br>You can use <a href="#" id="wizz">Wizard</a> to add datetime to your database.        
          <tr class="wds">
            <td class="lside">Indoor temperature:
            <td class="cside">
              <select name="db_i_temp">
                <option value="true"<?php if ($db_i_temp){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$db_i_temp){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">Show indoor temperature in temperature graphs.
          <tr class="wds">
            <td class="lside">UV radiation:
            <td class="cside">
              <select name="db_suv">
                <option value="true"<?php if ($db_suv){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$db_suv){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">Show UV plot in Sun graphs.
          <tr class="wds">
            <td colspan="3" class="tblt"><b>Select units in your database</b>
          <tr class="wds">
            <td class="lside">Wind speed:
            <td class="cside">
              <select name="db_wind">
                <option value="kmh"<?php if ($db_wind == 'kmh'){echo ' selected';}?>>kmh</option>
                <option value="mph"<?php if ($db_wind == 'mph'){echo ' selected';}?>>mph</option>
                <option value="kts"<?php if ($db_wind == 'kts'){echo ' selected';}?>>kts</option>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td class="lside">Temperature:
            <td class="cside">
              <select name="db_temp">
                <option value="C"<?php if ($db_temp == 'C'){echo ' selected';}?>>&deg;C</option>
                <option value="F"<?php if ($db_temp == 'F'){echo ' selected';}?>>&deg;F</option>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td class="lside">Rainfall:
            <td class="cside">
              <select name="db_rain">
                <option value="mm"<?php if ($db_rain == 'mm'){echo ' selected';}?>>mm</option>
                <option value="inch"<?php if ($db_rain == 'inch'){echo ' selected';}?>>inch</option>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td class="lside">Rain rate:
            <td class="cside">
              <select name="db_rate">
                <option value="mm"<?php if ($db_rate == 'mm'){echo ' selected';}?>>mm/h</option>
                <option value="inch"<?php if ($db_rate == 'inch'){echo ' selected';}?>>inch/h</option>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td class="lside">Barometer:
            <td class="cside">
              <select name="db_baro">
                <option value="hPa"<?php if ($db_baro == 'hPa'){echo ' selected';}?>>hPa</option>
                <option value="inHg"<?php if ($db_baro == 'inHg'){echo ' selected';}?>>inHg</option>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td colspan="3" class="tblt"><b>WD database performance v.s. graph accuracy</b><br>
              Set graph data sampling intervals, larger number = less server CPU overloading & shorter graphs loading time
          <tr class="wds">
            <td class="lside">Monthly graphs limit:
            <td class="cside">
              <select name="wdMonthLim">
                <option class='lims' value="'disabled'"<?php if ($wdMonthLim == 'disabled'){echo ' selected';}?>>disabled</option>
                <option class='lims' value="3"<?php if ($wdMonthLim == 3){echo ' selected';}?>>3 minutes</option>
                <?php
                $accVals = array(5,10,15,20,30,60);
                foreach ($accVals as $val) {
                  $swm = $val == $wdMonthLim ? ' selected' : '';
                  echo '<option value="'.$val.'"'.$swm.'>'.$val.' minutes</option>';
                }
                ?>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td class="lside">Yearly graphs limit:
            <td class="cside">
              <select name="wdYearLim">
                <option class='lims' value="'disabled'"<?php if ($wdYearLim == 'disabled'){echo ' selected';}?>>disabled</option>
                <option class='lims' value="3"<?php if ($wdYearLim == 3){echo ' selected';}?>>3 minutes</option>
                <?php
                $accVals = array(5,10,15,20,30,60);
                foreach ($accVals as $val) {
                  $swy = $val == $wdYearLim ? ' selected' : '';
                  echo '<option value="'.$val.'"'.$swy.'>'.$val.' minutes</option>';
                }
                ?>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td colspan="3" class="tblt"><b>Caching for WD MySQL datasource</b><br>
            Dramatically improve performance in year and month graphs, so you can set Month Limit and Year limit to lower values
          <tr class="wds">
            <td class="lside">Cache type:
            <td class="cside">
              <select name="db_cache_type">
                <option value="file"<?php if ($db_cache_type == 'file'){echo ' selected';}?>>file</option>
                <option value="db"<?php if ($db_cache_type == 'db'){echo ' selected';}?>>database</option>
                <option value="disabled"<?php if ($db_cache_type == 'disabled'){echo ' selected';}?>>disabled</option>
              </select>
            <td class="rside">&nbsp;
          <tr class="wds">
            <td class="lside">Cache table:
            <td class="cside"><input name="db_cache_table" value="<?php echo $db_cache_table; ?>">
            <td id="haf" class="rside">If Cache type is set to 'database' you must configure table name for caching. This table will be created in database configured above.
          <tr>   
            <td colspan="3" class="title1">Hour graphs config
          <tr>
            <td class="lside">Hour graphs datasource:
            <td class="cside">
              <select name="hourGraphs">
                <option value="disabled"<?php if ($hourGraphs == 'disabled'){echo ' selected';}?>>Hour graphs disabled</option>
                <option value="craw"<?php if ($hourGraphs == 'craw'){echo ' selected';}?>>clientraw</option>
                <option value="db"<?php if ($hourGraphs == 'db'){echo ' selected';}?>>WD MySQL database</option>
              </select>
            <td class="rside">Hour graphs do not use wundergroud data source. Set to disabled if you don't have the SW that can periodically upload clientrawhour.txt to your website or periodically sends data to MySQL database.
          <tr id="clrawPath">
            <td class="lside">Clientraw directory path:
            <td class="cside"><input name="clientRawHpath" value="<?php echo $clientRawHpath; ?>">
            <td class="rside">If Hour graphs datasource is set to 'clientraw' you must set path to file <i>clientrawhour.txt</i> (absolute or relative to 'wxwugraphs' directory with a slash at the end). <a href="<?php echo $_SERVER['PHP_SELF']; ?>?clrwh=1" target="_blank">How to</a> updating clientrawhour.txt every 5. minutes.
          <tr>   
            <td colspan="3" class="title1">Misc. configuration
          <tr>
            <td class="lside"><b>Theme:</b>
            <td class="cside">
              <select name="wugTheme">
<?php
$availThemes = glob('./themes/*.php');
$availThemes[] = 'default.php';
foreach ($availThemes as $aTheme) {
  $aTheme2 = basename($aTheme,'.php');
  $selTheme = $wugTheme == $aTheme2 ? ' selected' : '';
  echo '<option value="'.$aTheme2.'"'.$selTheme.'>'.$aTheme2.'</option>';
}
?>
              </select>
            <td class="rside">Select color theme for WU graphs pages
          <tr>
            <td class="lside">Theme background color:
            <td class="cside">     
              <select id="CustomBgTheme" name="CustomBgTheme" style="float: left; margin-top: 5px;">
                <option value="false"<?php if ($CustomBgTheme === false){echo ' selected';}?>>default</option>
                <option value="'transparent'"<?php if ($CustomBgTheme == 'transparent'){echo ' selected';}?>>transparent</option>
                <option value="true"<?php if ($CustomBgTheme === true){echo ' selected';}?>>custom</option>
              </select>
              <div id="BgColorSelector" class="colsel" style="float: left; margin-left: 20px;"><div style="background-color: <?php echo $colorpickerBgVal; ?>"></div></div>
              <input type="hidden" id="colorpickerBgVal" name="colorpickerBgVal" value="<?php echo $colorpickerBgVal; ?>">
            <td class="rside">Override page background color for selected theme              
          <tr>
            <td class="lside">Theme font color:
            <td class="cside">     
              <select id="CustomFontTheme" name="CustomFontTheme" style="float: left; margin-top: 5px;">
                <option value="false"<?php if (!$CustomFontTheme){echo ' selected';}?>>default</option>
                <option value="true"<?php if ($CustomFontTheme){echo ' selected';}?>>custom</option>
              </select>
              <div id="FontColorSelector" class="colsel" style="float: left; margin-left: 20px;"><div style="background-color: <?php echo $colorpickerFontVal; ?>"></div></div>
              <input type="hidden" id="colorpickerFontVal" name="colorpickerFontVal" value="<?php echo $colorpickerFontVal; ?>">
            <td class="rside">Override primary font color for selected theme          
          <tr>
            <td class="lside">Language switch:
            <td class="cside">
              <select name="langSwitch">
                <option value="true"<?php if ($langSwitch){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$langSwitch){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">Show/hide language switch
          <tr>
            <td class="lside">Date format:
            <td class="cside">
              <select name="ddFormat">
                <option value="0"<?php if ($ddFormat == 0){echo ' selected';}?>>dd.mm.yyyy</option>
                <option value="1"<?php if ($ddFormat == 1){echo ' selected';}?>>mm.dd.yyyy</option>
                <option value="2"<?php if ($ddFormat == 2){echo ' selected';}?>>mm/dd/yyyy</option>
                <option value="3"<?php if ($ddFormat == 3){echo ' selected';}?>>d.m.yy</option>
                <option value="4"<?php if ($ddFormat == 4){echo ' selected';}?>>mm.dd.yy</option>
                <option value="5"<?php if ($ddFormat == 5){echo ' selected';}?>>mm/dd/yy</option>
              </select>
            <td class="rside">Date format displayed in graphs title and tooltip
          <tr>
            <td class="lside">Time format:
            <td class="cside">
              <select name="hourFormat">
                <option value="0"<?php if ($hourFormat == 0){echo ' selected';}?>>24 hours</option>
                <option value="1"<?php if ($hourFormat == 1){echo ' selected';}?>>12 hours (am/pm)</option>
              </select>
            <td class="rside">Time format displayed in graphs title and tooltip
          <tr class="wspd">
            <td class="lside">Metric wind speed units:
            <td class="cside">
              <select name="windmu">
                <option value="m/s"<?php if ($windmu == 'm/s'){echo ' selected';}?>>m/s</option>
                <option value="km/h"<?php if ($windmu == 'km/h'){echo ' selected';}?>>km/h</option>
              </select>
            <td class="rside">Choose wind speed units for graphs
          <tr>
            <td class="lside">Baro min/max and bands:
            <td class="cside">
              <select name="baroMinMax">
                <option value="true"<?php if ($baroMinMax){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$baroMinMax){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">Disable/Enable minimum and maximum limits for barometric pressure graphs and informational bands/strips about intensity of pressure.
          <tr>
            <td class="lside">Remove spikes:
            <td class="cside">
              <select id="rmspik" name="removeSpikes">
                <option value="true"<?php if ($removeSpikes){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$removeSpikes){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">Replace spike/measuring error with last 'non-spiked' value. Maximum for correction is 3 spiked values in row.           
          <tr class="spik">
            <td class="lside">Day spike tresholds:
            <td class="cside">Temperature <input name="dspTemp" value="<?php echo $dsp['temp']; ?>" size="2">&deg;C<br>
            Barometer <input name="dspBaro" value="<?php echo $dsp['baro']; ?>" size="2">hPa<br>
            Rain rate <input name="dspRRate" value="<?php echo $dsp['rain_rate']; ?>" size="2">mm/h<br>
            Rain total <input name="dspRTotal" value="<?php echo $dsp['rain_total']; ?>" size="2">mm<br>
            Humidity <input name="dspHumi" value="<?php echo $dsp['humi']; ?>" size="2">%
            <td class="rside">Values for day spiked data corrector (must be in metric units - converted later in code). This is a max accepted change in measured values of interval for DAY graphs (interval is usually 5 minutes + potential station data sending failure).<br>Higher value means less sensitivity for spike corrector. 
          <tr class="spik">
            <td class="lside">Month/Year spike tresholds:
            <td class="cside">Temperature <input name="myspTemp" value="<?php echo $mysp['temp']; ?>" size="2">&deg;C<br>
            Barometer <input name="myspBaro" value="<?php echo $mysp['baro']; ?>" size="2">hPa<br>
            Humidity <input name="myspHumi" value="<?php echo $mysp['humi']; ?>" size="2">%
            <td class="rside">This is a max change in measured values of interval (usually 1 day) for MONTH/YEAR graphs.<br>Higher value mean a less sensitivity for spike corector.
          <tr>
            <td class="lside">Graph size in tabs:
            <td class="cside"><input name="wugWidth" value="<?php echo $wugWidth; ?>" size="4"> x <input name="wugHeight" value="<?php echo $wugHeight; ?>"  size="4"> px
            <td class="rside">  &nbsp;
          <tr>
            <td class="lside">Graph size in new window:
            <td class="cside"><input name="wugWinW" value="<?php echo $wugWinW; ?>"  size="4"> x <input name="wugWinH" value="<?php echo $wugWinH; ?>"  size="4"> px
            <td class="rside">Graph size opened in a new separate window.
          <tr>
            <td class="lside">Graphs drawing type:
            <td class="cside">
              <select name="spline_graphs">
                <option value="true"<?php if ($spline_graphs){echo ' selected';}?>>Spline</option>
                <option value="false"<?php if (!$spline_graphs){echo ' selected';}?>>Line</option>
              </select>
            <td class="rside">Spline = better look, but lower accuracy and possible problems with gaps in graph data.
          <tr>
            <td class="lside">Credits text in graphs:
            <td class="cside">
              <select name="creditsEnabled">
                <option value="true"<?php if ($creditsEnabled == 'true'){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if ($creditsEnabled == 'false'){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">Text with URL located in right bottom corner of the graphs.
          <tr>
            <td class="lside">Credits text:
            <td class="cside"><input name="credits" value="<?php echo $credits; ?>">
            <td class="rside">&nbsp;
          <tr>
            <td class="lside">Credits URL:
            <td class="cside"><input name="creditsURL" value="<?php echo $creditsURL; ?>">
            <td class="rside">&nbsp;
          <!--
          <tr>
            <td class="lside">Autorefresh time:
            <td class="cside"><input name="autoRefreshT" value="<?php echo $autoRefreshT; ?>" size="3"> minutes
            <td class="rside">Graph pages auto refresh time in minutes. If is used MySQL datasource without caching, is better use higher values (eg. 60 and more) for lower CPU server usage.
          -->
          <tr>
            <td class="lside">Cookies expiration:
            <td class="cside"><input name="cookieExp" value="<?php echo $cookieExp; ?>" size="3"> days
            <td class="rside">Expiration time for cookies in day/month/year selection.
          <tr>
            <td class="lside">Version auto check:
            <td class="cside">
              <select name="updateCheck">
                <option value="true"<?php if ($updateCheck){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$updateCheck){echo ' selected';}?>>Disabled</option>
              </select>
              <td class="rside">If there is a new version, ver. info at bottom of the page will be red. <i>To work properly, <a href="#cache-dir">Cache directory</a> must be writable.</i>
          <tr>
            <td class="lside">Send station name:
            <td class="cside">
              <select name="SendName">
                <option value="true"<?php if ($SendName){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$SendName){echo ' selected';}?>>No</option>
              </select>
              <td class="rside">Send station name and web address with first version check.
          <tr>
            <td class="lside">Include jQuery:
            <td class="cside">
              <select name="loadJQuery">
                <option value="true"<?php if ($loadJQuery){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$loadJQuery){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">If you have own jQuery library, jQuery Tabs and jQuery Cookie loaded in Saratoga/CarterLake template (top.php), then you can (optionally) set this to <i>No</i>.
          <tr>
            <td class="lside">Include Tabs CSS:
            <td class="cside">
              <select name="incTabsStyle">
                <option value="true"<?php if ($incTabsStyle){echo ' selected';}?>>Yes</option>
                <option value="false"<?php if (!$incTabsStyle){echo ' selected';}?>>No</option>
              </select>
            <td class="rside">If you set "Include jQuery" to <i>No</i>, then you might want to use own default CSS style for jQuery tabs.


          <tr>   
            <td colspan="3" class="title1">Other/malfunction settings


          <tr>
            <td class="lside">Remove graphs cache:
            <td class="cside"><input id="rmcb" type="button" value="Remove" onclick="rmcache();">
            <td class="rside">Remove all cached graphs data<br>(files and database cache table)
          <tr>
            <td class="lside">Cookies:
            <td class="cside">
              <select name="cookieEnabled">
                <option value="true"<?php if ($cookieEnabled){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$cookieEnabled){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">Set to "Disabled" if you have cookie PHP MOD_SECURITY problems in <a href="WUG-test.php" target="_blank">WUG-test.php</a>
          <tr>
            <td class="lside">Alternative URL fopen:
            <td class="cside">
              <select name="fopenOff">                                                                   
                <option value="true"<?php if ($fopenOff){echo ' selected';}?>>Enable</option>
                <option value="false"<?php if (!$fopenOff){echo ' selected';}?>>Disable</option>
              </select>
            <td class="rside">Enable if you have <i>allow_url_fopen = Off</i> in PHP configuration. But you may have problems with units in graphs (Metric vs Imperial - may depend on your server location).
          <tr>
            <td class="lside">Send agent:
            <td class="cside">
              <select name="sendAgent">                                                                   
                <option value="true"<?php if ($sendAgent){echo ' selected';}?>>Enabled</option>
                <option value="false"<?php if (!$sendAgent){echo ' selected';}?>>Disabled</option>
              </select>
            <td class="rside">Enable if you have <i>allow_url_fopen = On</i> in PHP configuration and still you have empty graphs or cache files.
          <tr>
            <td class="lside">Multibyte support:
            <td class="cside">
              <select name="no_mb">
                <option value="true"<?php if ($no_mb){echo ' selected';}?>>Disabled</option>
                <option value="false"<?php if (!$no_mb){echo ' selected';}?>>Enabled</option>
              </select>
            <td class="rside">Disable MB string support - only if you get some MB errors in <a href="WUG-test.php" target="_blank">WUG-test.php</a>
          <tr>
            <td class="lside">Tabbed height correction:
            <td class="cside"><input name="heightCorr" value="<?php echo $heightCorr; ?>" size="3"> pixels
            <td class="rside">Change if you have some graph appeareance problems (overlaping, etc...)

        
          <tr>
            <td colspan="3" class="title1" style="padding: 8px 0; border-radius: 0 0 12px 12px;"> 
              <select name="saveT" style="font-size:16px;">
                <option value="direct">Save config directly</option>
                <option value="file">Download config file</option>
                <option value="reset">Reset config to default</option>
              </select>                            
              &nbsp; &raquo; &nbsp;
              <input type="submit" value="Proceed" name="send" style="font-size:16px;">

             
        </table>
      </form>
      <div style="text-align:center;"><small>&copy;2011 <a href="http://pocasi.hovnet.cz/wxwug.php">Radomir Luza</a></small></div>
    </div>
    
    <div style="display:none;">
      <form id="formhid" method="post" action="./datetime-tool.php" target="_blank">
        <input type="text" value="" name="host">
        <input type="text" value="" name="user">
        <input type="text" value="" name="pass">
        <input type="text" value="" name="name">
        <input type="text" value="" name="table">
        <input type="text" value="home1" name="pg">
      </form>
    </div>
    
    </div>
  </body>
</html>
<?php
}// end if is password ok
?>
