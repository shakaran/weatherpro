<?php
/**
 * Project:   WU GRAPHS
 * Module:    WUG-form.php 
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

$thisPage = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

// resolve graph type
if (substr($thisPage, 0, 6) == 'graphh') {$type = 'hour';}
if (substr($thisPage, 0, 6) == 'graphd') {$type = 'day';}
if (substr($thisPage, 0, 6) == 'graphm') {$type = 'month';}
if (substr($thisPage, 0, 6) == 'graphy') {$type = 'year';}

// ? or & resolving for URL parameters
if (fixGet("nothing") == '') {$rlnk = '?';} else {$rlnk = '&';}
//$rlnk = ''; //--> RLNK DISABLED

// force parameter in refresh button
if ($refreshForced) {$refreshLink = fixGet("force=1");} else {$refreshLink = fixGet("force");}

// set w and h values from URL a switch for win mode (separate window/page)
if (!($_REQUEST["i"] == 1)) {
  $winFlag = true;
  if ($_REQUEST['w'] and is_numeric($_REQUEST['w'])) {$wugWidth = $_REQUEST['w'];}
  if ($_REQUEST['h'] and is_numeric($_REQUEST['h'])) {$wugHeight = $_REQUEST['h'];}
}

// show text if month or year graph have a empty data (1 day of new period)
// period resolving

if ($type == 'month') {$periodTxt = $Tmonth;} elseif ($type == 'year') {$periodTxt = $Tyear;} else {$periodTxt = $Tday;}
if ($emptyGraph || $emptyGraphTP) {
  if (!empty($errorReport)) { // a little bit styling
    $errorReport .= '<br>';
  }
  $periText = !$no_mb ? mb_strtolower($periodTxt, $WUGcharset) : $periodTxt;
  $lackD = $emptyGraphTP ? $TlackData2 : $TlackData;
  $errorReport .= $lackD.' '.$periText.'.';
}
if ($emptyWUfile) {
  if (!empty($errorReport)) {$errorReport .= '<br>';}  // a little bit styling
  $errorReport .= 'Error: Empty data/cache file. Please try using the diagnostic file WUG-test.php in wxwugraphs directory.';
}

// for auto javacript changing <OPTION> to selected
$graphCat = substr($thisPage, 0, -6); // for resolving graph category 
$DayArr = array('1a', '3a', '5a', '4a', '2a', '7a', '6a'); // selector array for day
$MyArr = array('1a', '3a', '2a', '4a', '5a', '6a', '7a'); // selector array for month / year
if ($hGraphs) {
  $Rarr = array('graphh', 'graphd', 'graphm', 'graphy');
} else {
  $Rarr = array('graphd', 'graphm', 'graphy');
}
if ($graphCat == 'graphd' || $graphCat == 'graphh') {
  $catArr = $DayArr;
} else {
  $catArr = $MyArr;
}
$oidx = array_flip($catArr);
$oidx2 = array_flip($Rarr);

// DATEPICKER SWITCH - LANG INIT, STYLE and JS
if (is_file($dpckFile)) { // long month name in month dropdown menu
  $pickerMonths='       monthNamesShort: $.datepicker.regional[\''.$WUGLang.'\'].monthNames,';
}

if ($type == 'day') {
  $pickerStyle = 
    '
      .ui-datepicker {width:19.5em!important;}
      .ui-datepicker-year {width: 40% !important;}
      .ui-datepicker-month {width: 60% !important;}
      #datepicker {width:190px;margin:0px auto;}
    ';
  $pickerJS = 
    '
    			changeMonth: true,
    			changeYear: true,
    			dateFormat: "\'d\'=d&\'m\'=m&\'y\'=yy",
          defaultDate: new Date('.$year.', '.$month.' - 1, '.$day.'),
    			onSelect: function(date, instance) {  
            window.location = \''.$thisPage.fixGet("d&m&y").$rlnk.'\'+date;
          }
    ';
  $pickerTxt = $Taday;
} elseif ($type == 'month') {
  $pickerStyle =
    '
      .ui-datepicker-calendar {display:none;}
      .ui-widget-content {border: 0px none !important}
      .ui-widget {font-size:9pt;}
      .ui-datepicker {width:19.5em!important;}
      .ui-datepicker-year {width: 40% !important;}
      .ui-datepicker-month {width: 60% !important;}
      #datepicker {width:205px;margin:0px auto;}
      .ui-datepicker {padding: 0em;}
    ';
  $pickerJS = 
    '
    			changeMonth: true,
    			changeYear: true,
    			dateFormat: "\'m\'=m&\'y\'=yy",
          defaultDate: new Date('.$year.', '.$month.' - 1, 1),
    			onChangeMonthYear: function(year, month, inst) {
            window.location = \''.$thisPage.fixGet("d&m&y").$rlnk.'m=\'+ month +\'&y=\'+year;
          }
    ';
  $pickerTxt = $Tamonth;
} elseif ($type == 'year') {
    $pickerStyle =
    '
      .ui-datepicker-calendar {display:none;}
      .ui-widget-content {border: 0px none !important}
      .ui-widget {font-size:9pt; width:140px}
      .ui-datepicker-month {display:none;}
      .ui-datepicker-title {margin:0px !important;} 
      #datepicker {width:145px;margin:0px auto;}
      .ui-datepicker {padding: 0em;}
    ';

    $pickerJS = 
    '
    			stepMonths: 12,
          changeYear: true,
    			dateFormat: "\'y\'=yy",
          defaultDate: new Date('.$year.', 1 - 1, 1),
    			onChangeMonthYear: function(year, month, inst) {
            window.location = \''.$thisPage.fixGet("d&m&y").$rlnk.'y=\'+year;
          }
    ';
    $pickerTxt = $Tayear;
} elseif ($type == 'hour') {
  $pickerJS = 'changeMonth: true'; // only for IE9 or IE7 compatibility (unused coma after string in $pickerMonths) 
}

$JSalertOut = $JSalert ? 'alert('.$JSalert.');' : '';
$CreditsBlank = $creditsEnabled ? '// test' : ''; // incomplete

// CONTINUE HEADER SECTION
?>
  <link type="text/css" href="./css/form.css" rel="stylesheet">
<?php
echo '
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <!--<META HTTP-EQUIV=REFRESH CONTENT="'.($autoRefreshT*60).'">-->
';
if ($winFlag) {
    echo'
    <script type="text/javascript">
    function graphSelected () {
      document.GraphType.GraphSelect.options['.$oidx[substr($thisPage, -6, 2)].'].selected = true;
      document.GraphTime.GraphRange.options['.$oidx2[substr($thisPage, 0, 6)].'].selected = true;
    }
    function customSize () {
      var gwidth = document.getElementById("inpwidth").value;
      var gheight = document.getElementById("inpheight").value;
      location.href="'.$thisPage.fixGet("w&h").$rlnk.'" + "w=" + gwidth + "&h=" + gheight;
    }
    // ONLY NUMERIC INPUT FOR W & H
    // copyright 1999 Idocs, Inc. http://www.idocs.com
    // Distribute this script freely but keep this notice in place
    function numbersonly(myfield, e, dec) {
      var key;
      var keychar;    
      if (window.event)
         key = window.event.keyCode;
      else if (e)
         key = e.which;
      else
         return true;
      keychar = String.fromCharCode(key);   
      // control keys
      if ((key==null) || (key==0) || (key==8) || 
          (key==9) || (key==13) || (key==27) )
         return true;    
      // numbers
      else if ((("0123456789").indexOf(keychar) > -1))
         return true;    
      // decimal point jump
      else if (dec && (keychar == ".")) {
         myfield.form.elements[dec].focus();
         return false;
      } else
         return false;
    }
    
    // Some reports as JS alerts
    '.$JSalertOut.'
    
    // Open crecits in _blak target
    '.$CreditsBlank.'
    </script>
    ';
}
echo '
  <!-- UI datepicker theme -->
  <link type="text/css" href="'.$pickerStyleFile.'" rel="stylesheet">
  <!-- few modifications for Datepicker and body theme -->
  <style type="text/css">
'.$pickerStyle.'
body{color:'.$wugfontColor.'; background-color:'.$pgBGC.';}
  </style>
';
?>	
  <!-- jquery datepicker -->
	<script type="text/javascript" src="./js/jq-core-dpick.min.js"></script>
  <!-- Datepicker translation -->
<?php  
// datepicker language switch
if (isset($dpckLangFile)) {
echo '  <script type="text/javascript" src="'.$dpckLangFile.'"></script>'."\n";
}
// Available languages: http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/
// Or create your own: http://docs.jquery.com/UI/Datepicker/Localization
echo 
'	
  <!-- Datepicker init -->
	<script type="text/javascript">
	//$(function() {
	$(document).ready(function() { // display inline
		$("#datepicker").datepicker({ 
      minDate: new Date('.$sinceY.', '.$sinceM.' - 1, '.$sinceD.'),
	    // maxDate: \'Now\',
	    maxDate: new Date('.date('Y').', '.date('n').' - 1, '.date('j').'),
'.$pickerMonths.'
'.$pickerJS.'
      }); 
		});
	</script>

</head>
';
flush(); // flush buffer after header is created (better performance)
#################
# BODY SECTION  #
#################

if ($winFlag) {
  echo '
  <body onload="graphSelected();">
  <!-- Tooltip -->
  <script src="'.$mainDir.'js/wz_tooltip.js" type="text/javascript"></script>
  
  <div id="window" style="width:'.($wugWidth + 40).'px;">
    <div id="inner">';
} else { // other inline css for body
  echo '
  <body style="position: relative;">
  <!-- Tooltip -->
  <script src="'.$mainDir.'js/wz_tooltip.js" type="text/javascript"></script>
  ';
}

// noscript and other warnings
echo '
<noscript>
<div style="color:red;"><b>'.$Tnojs.'</b></div>
</noscript>
<div id="WUG-error"><b>'.$errorReport.'&nbsp;</b></div>
';

// EXTRA TEXT
$extraFileLang = substr($thisPage, 0, -4).'-'.$WUGLang.'.html';
$extraFile = substr($thisPage, 0, -4).'.html';
if (is_file($extraFileLang)) {
echo '<div id="WUGtext">'.file_get_contents($extraFileLang).'</div>';
} else { 
  if ( is_file($extraFile) ) {
  echo '<div id="WUGtext">'.file_get_contents($extraFile).'</div>';
  }
}

if ($winFlag) {
    echo '
    <br>
    <div id="container" style="width: '.$wugWidth.'px; height: '.$wugHeight.'px;">
    </div>
      <div id="gbuttons">
        <a href="javascript:window.close()" onmouseover="Tip(\''.$Tclwin.'\')" onmouseout="UnTip()"><img src="./images/close.png" alt="close" width="40" height="40"></a>
      </div>
      <div id="graph-switch">
        <table>
        <tr>
          <td>
            <form name="GraphType" action="#"><b>'.$TotherValue.' </b>
              <select name="GraphSelect" onchange="location.href=\''.$graphCat.'\'+document.GraphType.GraphSelect.value+\'.php'.fixAmp(fixGet("force")).'\';">';
      if ($graphCat == 'graphd' || $graphCat == 'graphh') { // day/hour graphs have another structure
      echo '
                <option value="1a">'.$TempTran.'</option>
                <option value="3a">'.$HumTran.'</option>
                <option value="5a">'.$BaroTran.'</option>
                <option value="4a">'.$WSTran.'</option>
                <option value="2a">'.$windDirTran.'</option>
                <option value="7a">'.$PrecTran.'</option>
           ';
          if ($showSolar) {echo   '<option value="6a">'.$SunTran2.'</option>'."\n";}
      } else {
      echo '
                <option value="1a">'.$TempTran.'</option>
                <option value="3a">'.$HumTran.'</option>
                <option value="2a">'.$DPTran.'</option>
                <option value="4a">'.$BaroTran.'</option>
                <option value="5a">'.$WSTran.'</option>
                <option value="6a">'.$PrecTran.'</option>  
      ';
      if (($showSolar && $calcSolar) || ($dataSource == 'mysql' && $showSolar)) {echo   '<option value="7a">'.$SunTran2.'</option>'."\n";}
      }
    
    $graphRang = substr($thisPage, 6); // for resolving graph type by date range
    $hourOpt = $hGraphs ? '<option value="graphh">'.$Thour.'</option>' : '';
       
    echo '
              </select>
            </form>
          <td>
            <form id="GraphTime" name="GraphTime" action="#"><b>'.$TotherInterval.' </b>
              <select name="GraphRange" onchange="location.href=document.GraphTime.GraphRange.value+\''.$graphRang.fixAmp(fixGet("force")).'\';">
                '.$hourOpt.'
                <option value="graphd">'.$Tday.'</option>
                <option value="graphm">'.$Tmonth.'</option>
                <option value="graphy">'.$Tyear.'</option> 
              </select>
            </form>
          </tr>
        </table>
      </div>
    ';
  
    echo '
      <table id="resize" style="width:'.($wugWidth-120).'px;">
        <tr>
          <td align="left">
            <div id="size-switch">
              <b>'.$TchangeSize.'</b> <a href="'.fixAmp(fixGet("w=750&h=370", true)).'">750x370</a> &nbsp;|&nbsp; <a href="'.fixAmp(fixGet("w=980&h=400")).'">980x400</a> &nbsp;|&nbsp; <a href="'.fixAmp(fixGet("w=1200&h=420")).'">1200x420</a>
            </div>
    
          <td align="right">
            <b>'.$TcustomSize.' <input id="inpwidth" name="w" type="text" maxlength="4" size="2" onKeyPress="return numbersonly(this, event)" onmouseover="Tip(\''.$Twidth.'\')" onmouseout="UnTip()"> x <input id="inpheight" name="h" type="text" maxlength="4" size="2" onKeyPress="return numbersonly(this, event)" onmouseover="Tip(\''.$Theight.'\')" onmouseout="UnTip()"> </b> <input type="submit" value="'.$Tset.'" onclick="customSize()">
        </tr>
      </table>
    ';
} else {
  echo '
  <div id="container" style="width: '.$wugWidth.'px; height: '.$wugHeight.'px;">
  </div>
  <div id="new-window">
    <a href="'.fixAmp(fixGet("i&w=".$wugWinW."&h=".$wugWinH)).'" target="_blank" title="'.$TopenNp.'" onmouseover="Tip(\''.$TopenNp.'\')" onmouseout="UnTip()"><img src="./images/win.png" alt="new window" width="42"></a>
  </div>';
}

if ($langSwitch && $winFlag) {
  $thLang = $_GET['lang'] ? $_GET['lang'] : $_COOKIE['cookie_lang'];
  $langsw = '<div id="lang-switch" style="position: absolute; right: 155px; top: 16px; z-index: 50;">
  <form name="languages" action="#" style="font-size: 14px;">
  Language: <select name="langSelect" onchange="location.href=\''.$_SERVER["PHP_SELF"].'?lang=\'+document.languages.langSelect.value;">';
  include ('./languages/langlist.php');
  foreach ($langList as $key => $val) {
    $selectedL = $thLang == $key ? ' selected' : '';
    $langsw .= '<option value="'.$key.'"'.$selectedL.'>'.utf8tohtml($val).'</option>';
  }
  $langsw .= '</select>
  </form>
  </div>';
} else {
  $langsw = '';
}

$disable_picker = $type == 'hour' ? 'xx' : '';

echo '
<a href="'.fixAmp($thisPage.$refreshLink).'" onmouseover="Tip(\''.$Trefresh.'\')" onmouseout="UnTip()">
<img id="refresh-button" src="./images/refresh.png" alt="refresh">
</a>

<div id="info-icon" onmouseover="Tip(\''.$TinfoIcon.'\',TITLE,\''.$TinfoIconTitle.'\', LEFT, true)" onmouseout="UnTip()"><img src="./images/info.png" width="40" height="40" alt="info"></div>

'.$langsw.'

<br>

<div class="selectDate'.$disable_picker.'">
  <b>'.$pickerTxt.'</b>
  <br>
  <div id="datepicker'.$disable_picker.'"></div>
</div>
';

require_once('WUG-ver.php');
 
if ($winFlag) { // End #window and #inner
  echo '</div></div>'."\n";
}  

echo $errWUGlang."\n"; // print commented errror text

?>

	</body>
</html>
<?php
//include('WUG-pre.php');
// FUNCTIONS
// FIXGET - modify/resolve URL variable
// http://www.php.net/manual/en/reserved.variables.get.php#93877
function fixGet($args, $empty = false) {
$out = $_GET;
//   if(count($out) > 0 or $empty) {
        if(!empty($args)) {
            $lastkey = "";
            $pairs = explode("&",$args);
            foreach($pairs as $pair) {
                if(strpos($pair,":") !== false) {
                    list($key,$value) = explode(":",$pair);
                    unset($out[$key]);
                    $lastkey = "&$key$value";
                } elseif(strpos($pair,"=") === false)
                    unset($out[$pair]);
                 else {
                    list($key, $value) = explode("=",$pair);
                    $out[$key] = $value;
                }
            }
        } 
        return "?".((count($out) > 0)?http_build_query($out).$lastkey:"");
//   }
}

function fixAmp($link) { // for JS URL W3C validation
return str_replace("&", "&amp;", $link);
//return $link;
}

// convert all non 1-byte characters to htmlentities
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
?>