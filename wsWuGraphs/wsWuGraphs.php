<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
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
$pageName	= 'wsWuGraphs.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-12';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-09-12 release version
# ----------------------------------------------------------------------
# Project:   template ==> WU GRAPHS
# Module:    wsWuGraphs.php 
#
# To do: 
#   
#---------------------------------------------------------------------------
#
#
#
require_once('./wxwugraphs/WUG-settings.php');
echo '<script src="./wxwugraphs/js/wz_tooltip.js" type="text/javascript"></script>';
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

$hourTab = '<li><a href="./wxwugraphs/WUG-tabsh.php?lang='.$scLang.'"><span>'.utf8tohtml($Thourly).'</span></a></li>';
$hrTab = $hGraphs ? $hourTab : '';

echo 
'
<div class="blockDiv">

<h3 class="blockHead">'.langtransstr('Weather Underground History page Graphs').'</h3>
<br />
  <table id="WUG-tabbed" style="width:100%;">
    <tr><td style="vertical-align:top;"> 
    <noscript>
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
    echo '<option value="'.$key.'"'.$selectedL.'>'.utf8tohtml($val).'</option>';
  }
  echo '</select>
  </form>
  </div>';
}
echo '      <ul style="/*height:25px;*/">
        '.$hrTab.'
        <li><a href="./wxwugraphs/WUG-tabsd.php?lang='.$scLang.'"><span>'.utf8tohtml($Tdaily).'</span></a></li>
        <li><a href="./wxwugraphs/WUG-tabsm.php?lang='.$scLang.'"><span>'.utf8tohtml($Tmonthly).'</span></a></li>
        <li><a href="./wxwugraphs/WUG-tabsy.php?lang='.$scLang.'"><span>'.utf8tohtml($Tyearly).'</span></a></li>  		
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
</div>';
echo $errWUGlang;

#chdir ('./weather2/');
?>