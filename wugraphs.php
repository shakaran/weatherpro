<?php
############################################################################
#                            WU GRAPHS
#                      STANDALONE VERSION 
#           (WIHTOUT Saratoga/CarterLake templates) 
#
############################################################################
/**
 * Project:   WUNDERGROUND GRAPHS
 * Module:    wugraphs.php 
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

if (isset($SITE['lang'])    ) { $scLang = strtolower($SITE['lang']); }
if (isset($_SESSION['lang'])) { $scLang = strtolower($_SESSION['lang']); }
if (isset($_REQUEST['lang'])) { $scLang = strtolower($_REQUEST['lang']); }

// comment line bellow if is includeMode set to true and you have some header errors
SetCookie ("cookie_lang", $scLang, time()+3600*24*365, "/"); // 365 days

// CHANGE STATION BY URL PARAMETER
$WUID = $_GET['wuid'] ? $_GET['wuid'] : '';
$sinceY = $_GET['sy'] ? $_GET['sy'] : '';
$sinceM = $_GET['sm'] ? $_GET['sm'] : '';
$sinceD = $_GET['sd'] ? $_GET['sd'] : '';
$stn = $_GET['stn'] ? $_GET['stn'] : '';
$wdth = $_GET['wdth'] ? $_GET['wdth'] : '';
SetCookie ("wuid", $WUID, time()+3600*24*365, "/");
SetCookie ("sy", $sinceY, time()+3600*24*365, "/");
SetCookie ("sm", $sinceM, time()+3600*24*365, "/"); 
SetCookie ("sd", $sinceD, time()+3600*24*365, "/");
SetCookie ("stn", $stn, time()+3600*24*365, "/");
SetCookie ("wdth", $wdth, time()+3600*24*365, "/");

require_once('./wxwugraphs/WUG-settings.php');

############################################################################
header('Content-Type: text/html; charset=utf-8');

if (!$includeMode) {

echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/javascript; charset=utf-8">
		
		<title>'.$Tgraphs.'</title>
';
}

if ($incTabsStyle) {
echo '<link type="text/css" href="'.$tabsStyleFile.'" rel="stylesheet">';
}

if ($loadJQuery) {
echo '<script type="text/javascript" src="'.$jQueryFile.'"></script>';
echo '<script type="text/javascript" src="./wxwugraphs/js/jq-core-widget-tabs.min.js"></script>';
}

$JSalertOut = isset($JSalert) ? 'alert('.$JSalert.');' : '';

echo '
<script type="text/javascript">
$(function() {
  $("#WU-MDswitch").tabs({
    cookie: {
      expires: 30,
      path: "'.$_SERVER["PHP_SELF"].'"
    },
    spinner: \''.$Tloading.'\',
    cache: true
  }); 
});       
// auto height
$("WUG-foot").ready(function() {
  var mcheight = document.getElementById("main-copy").offsetHeight;
  document.getElementById("WUG-tabbed").style.height = mcheight + "px";
});

// Some reports as JS alerts
'.$JSalertOut.'
</script>
';
?>	    
<style type="text/css">
.WUG-subtab .ui-tabs-panel {padding:0px;}
#WUGcInfo {
font: 8pt Tahoma,Verdana,Arial,sans-serif;
/*border-top:1px solid #bbb;*/
}
.c-lside a {
font-weight: normal !important;
text-decoration: underline !important;  
color: #bbb !important;
}
.c-rside a {
color: #75A3D1 !important;
}
#main-copy {
font-size: 9pt;
}
body {
<?php
echo 'background-color:'.$pgBGC.';
color:'.$wugfontColor.';
';
?>  
}
.ui-tabs-panel {
<?php echo 'background-color:'.$pgBGC.';'; ?>
}
</style>
</head>
<body style="margin:0; padding:0;">
<script src="./wxwugraphs/js/wz_tooltip.js" type="text/javascript"></script>
<?php
############################################################################
//include("header.php");
############################################################################
//include("menubar.php");
############################################################################

echo 
'
<div id="main-copy">
  <table id="WUG-tabbed" style="width:100%;">
    <tr><td style="vertical-align:top;">'; 
if ($_GET['nohead'] != "1") {    
  echo '    <h1>'.$Tgraphs.'</h1>     
    <br>';
}

$hourTab = '<li><a href="./wxwugraphs/WUG-tabsh.php"><span>'.$Thourly.'</span></a></li>';
$hrTab = $hGraphs ? $hourTab : '';

echo    '<noscript>
    <div style="color:red; text-align:center;"><b>'.$Tnojs.'</b></div>
    </noscript>
       
    <div id="WU-MDswitch" style="position:relative;">
';

if ($langSwitch) {
  $thLang = $_GET['lang'] ? $_GET['lang'] : $_COOKIE['cookie_lang'];
  echo '<div id="lang-switch" style="text-align: right; /*margin-top: 0px; float: right*/ position:absolute; right:0; top:0px;">
  <form name="languages" action="#" style="font-size: 14px;">
  Language: <select name="langSelect" onchange="location.href=\''.$_SERVER["PHP_SELF"].'?lang=\'+document.languages.langSelect.value;">';
  include ('./wxwugraphs/languages/langlist.php');
  foreach ($langList as $key => $val) {
    $selectedL = $thLang == $key ? ' selected' : '';
    echo '<option value="'.$key.'"'.$selectedL.'>'.$val.'</option>';
  }
  echo '</select>
  </form>
  </div>';
}
echo '      <ul style="/*height:25px;*/">
        '.$hrTab.'
        <li><a href="./wxwugraphs/WUG-tabsd.php"><span>'.$Tdaily.'</span></a></li>
        <li><a href="./wxwugraphs/WUG-tabsm.php"><span>'.$Tmonthly.'</span></a></li>
        <li><a href="./wxwugraphs/WUG-tabsy.php"><span>'.$Tyearly.'</span></a></li>  		
     </ul>
    </div>
    </td></tr>
    <tr><td style="vertical-align:bottom;"><div id="WUG-foot">
';
require_once('./wxwugraphs/WUG-ver.php');
echo 
'
    </div></td></tr>
  </table>  
</div><!-- end main-copy -->
';

############################################################################
# End of Page
############################################################################
?>