<?php
/**
 * Project:   WU GRAPHS
 * Module:    WUG-ver.php 
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

// COMPARE VERSION
$verFile = $WUcacheDir.'WUG-version.txt'; 
$remVerCheck = 'http://pocasi.hovnet.cz/wug/version.php'; // server with newest version info
$tHourStart = strtotime(date('Y-m-d').' 00:00:01');  //more compatible solution than strtotime("today")
$nwVerAvail = false;
   
if ($updateCheck) {
  if (is_file($verFile)) { 
    if ( filemtime($verFile) < $tHourStart ) { // compare version from remote server once at day      
      $vf = @fopen($verFile, "w");
      if ($fopenOff) {
        include('./fopener.php');
        $read1 = new HTTPRequest($remVerCheck);
        @fwrite($vf, trim($read1->DownloadToString()));
        if ($SendName) { 
          $read2 = new HTTPRequest($remVerCheck.'?name='.urlencode(utf8_encode($stationName)).'&i=1&web='.urlencode($_SERVER['HTTP_HOST']));        
          $wugarb = $read2->DownloadToString();
        }
      } else {            
        @fwrite($vf, trim(@file_get_contents($remVerCheck)));
        if ($SendName) {
          @file_get_contents( $remVerCheck.'?name='.urlencode(utf8_encode($stationName)).'&i=1&web='.urlencode($_SERVER['HTTP_HOST']) );
        }
      }
      @fclose($vf);
    }
  } else { // create verify file, version will be checked in next page load.
    $vf = @fopen($verFile, "w");
    if ($fopenOff) {
      include('./fopener.php');
      $read1 = new HTTPRequest($remVerCheck);
      @fwrite($vf, trim($read1->DownloadToString()));
      if ($SendName) {      
        $read2 = new HTTPRequest($remVerCheck.'?name='.urlencode(utf8_encode($stationName)).'&i=1&web='.urlencode($_SERVER['HTTP_HOST']));        
        $wugarb = $read2->DownloadToString();
      }
    } else {            
      @fwrite($vf, trim(@file_get_contents($remVerCheck)));
      if ($SendName) {
        @file_get_contents( $remVerCheck.'?name='.urlencode(utf8_encode($stationName)).'&i=1&web='.urlencode($_SERVER['HTTP_HOST']) );
      }
    }
    @fclose($vf);
  }

  // Check version for information at the bottom of the page.
  if (version_compare(VERSION, trim(@file_get_contents($verFile)), '<')) {
    $nwVerAvail = true;
  } else {
    $nwVerAvail = false;
  }
}

//if ($winFlag) {$tabPos = '';} else {$tabPos = 'position: absolute; bottom: 0pt;';}
if ($nwVerAvail) { // tooltip info
  $tooltipVer = 'There is available a newer version ( '.trim(@file_get_contents($verFile)).' ) of this SW. For more information, click on the author\\\'s website link.';
  $redVer = ' style="color:#FA7C78"';
} else {
  $tooltipVer = 'The latest version is already installed.';
  $redVer = ' ';
}

// Copyright, version and other info - Please do not change this code!
if (!is_file('./WUG-settings.php')) {$tabFlag = true;} else {$tabFlag = false;} //resolve mode


// convert all non 1-byte characters to htmlentities
if (!function_exists('utf8tohtml')) {
  function utf8tohtml($utf8, $encodeTags=false) {
      $result = '';
      for ($i = 0; $i < strlen($utf8); $i++) {
          $char = $utf8[$i];
          $ascii = ord($char);
          if ($ascii < 128) {
              // one-byte character
              $result .= ($encodeTags) ? htmlentities($char) : $char;
          } else if ($ascii < 192) {
              // non-utf8 character or not a start byte
          } else if ($ascii < 224) {
              // two-byte character
              //$result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
              $ascii1 = ord($utf8[$i+1]);
              $unicode = (15 & $ascii) * 64 +
                         (63 & $ascii1);
              $result .= "&#$unicode;";
              $i++;
          } else if ($ascii < 240) {
              // three-byte character
              $ascii1 = ord($utf8[$i+1]);
              $ascii2 = ord($utf8[$i+2]);
              $unicode = (15 & $ascii) * 4096 +
                         (63 & $ascii1) * 64 +
                         (63 & $ascii2);
              $result .= "&#$unicode;";
              $i += 2;
          } else if ($ascii < 248) {
              // four-byte character
              $ascii1 = ord($utf8[$i+1]);
              $ascii2 = ord($utf8[$i+2]);
              $ascii3 = ord($utf8[$i+3]);
              $unicode = (15 & $ascii) * 262144 +
                         (63 & $ascii1) * 4096 +
                         (63 & $ascii2) * 64 +
                         (63 & $ascii3);
              $result .= "&#$unicode;";
              $i += 3;
          }
      }
      return $result;
  }
}

// utf-8 convert 
$thisPage = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
if ($thisPage == 'wxwugraphs.php') {
  $TWUsource = utf8tohtml($TWUsource); 
}

if ($dataSource == 'mysql') {
  $sourceString = '';
} else {
  $sourceString = ' '.$TWUsource.' <a href="http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$WUID.'" target="_blank" onmouseover="Tip(\'&lt;img src=&#34;'.$mainDir.'images/wunder-long.png&#34; alt=&#34;Wunderground Logo&#34; width=&#34;230&#34; height=&#34;33&#34; /&gt;\',TITLE,\'Weather Undeground server\')" onmouseout="UnTip()">Wunderground.com ('.$WUID.')</a>';
}

$uvarg = isset($winFlag) ? $winFlag : false;
if ($uvarg || $tabFlag) {
echo '
<table style="width: 100%; color: #bbb;" id="WUGcInfo">
<tr><td class="c-lside">
<div>WU-Graphs <span'.$redVer.' onmouseover="Tip(\''.$tooltipVer.'\',ABOVE, true)" onmouseout="UnTip()">&nbsp;v '.VERSION.'</span></div>
&copy; 2010 <a href="http://pocasi.hovnet.cz/wxwug.php?lang=en" target="_blank" onmouseover="Tip(\'Author\\\'s weather Web site with more information about this software.\',TITLE,\'Weather station Hovezi - Czech Republic\')" onmouseout="UnTip()">Radomir Luza</a>. Powered by <a href="http://www.highcharts.com" target="_blank" rel="nofollow" onmouseover="Tip(\'&lt;div style=&quot;width:190px;&quot;>&lt;img src=&#34;'.$mainDir.'images/higcharts-logo.png&#34; alt=&#34;Highcharts Logo&#34; width=&#34;175&#34; height=&#34;33&#34; /&gt;&lt;/div&gt;\',TITLE,\'JS charts for your webpages\', TEXTALIGN, \'center\')" onmouseout="UnTip()">HighCharts</a> &amp; <a href="http://www.jquery.com" target="_blank" rel="nofollow" onmouseover="Tip(\'&lt;div style=&quot;width:190px;&quot;&gt;&lt;img src=&#34;'.$mainDir.'images/jquery-logo.png&#34; alt=&#34;jQuery Logo&#34; width=&#34;152&#34; height=&#34;33&#34; /&gt;&lt;/div&gt;\',TITLE,\'jQuery - write less, do more\', TEXTALIGN, \'center\')" onmouseout="UnTip()">jQuery UI</a></td>
<td class="c-rside" style="text-align: right; font-weight: bold; vertical-align: bottom; ">'.$sourceString.'</td></tr>
</table>
';
}
?>    