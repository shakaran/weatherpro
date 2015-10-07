<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_spc_day1_3.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.10 2015-01-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.10 2015-01-05 first release version
# ----------------------------------------------------------------------
# General settings for day 1-3 supported by this script
# day 4 is so differtent that it has its own script.
#
$style_select   = 'style="text-align:left; color: white; background-color: black; font-size:12px; border: 1px solid #000;"';
$img_url        = 'http://www.spc.noaa.gov/products/outlook/';
#
$day_arr        = array();              // for every day the tabs
$day_arr[1][1]  = 'TABday_a|day1otlk.gif         |Convective    |Categorical Day 1 Outlook      ';
$day_arr[1][2]  = 'TABday_b|day1probotlk_torn.gif|Tornado       |Probability of a tornado within 25 miles of a point.<br />Hatched Area: 10% or greater probability of EF2 - EF5 tornadoes within 25 miles of a point.';
$day_arr[1][3]  = 'TABday_c|day1probotlk_hail.gif|Hail          |Probability of one inch diameter hail or larger within 25 miles of a point.<br/>Hatched Area: 10% or greater probability of two inch diameter hail or larger within 25 miles of a point. ';
$day_arr[1][4]  = 'TABday_d|day1probotlk_wind.gif|Wind          |Probability of damaging thunderstorm winds or wind gusts of 50 knots or higher within 25 miles of a point.<br />Hatched Area: 10% or greater probability of wind gusts 65 knots or greater within 25 miles of a point.';
$day_arr[2][1]  = 'TABday_a|day2otlk.gif         |Categorical   |Categorical Day 2 Outlook      ';
$day_arr[2][2]  = 'TABday_b|day2probotlk_any.gif |Probabilistic |Probability of severe weather within 25 miles of a point.<br />Hatched Area: 10% or greater probability of significant severe weather within 25 miles of a point.';
$day_arr[3][1]  = 'TABday_a|day3otlk.gif         |Categorical   |Categorical Day 2 Outlook      ';
$day_arr[3][2]  = 'TABday_b|day3prob.gif         |Probabilistic |Probability of severe weather within 25 miles of a point.<br />Hatched Area: 10% or greater probability of significant severe weather within 25 miles of a point.';
#
$day_text       = array();              // for every day the text file
$day_text[1]    = 'http://www.srh.noaa.gov/data/WNS/SWODY1';
$day_text[2]    = 'http://www.srh.noaa.gov/data/WNS/SWODY2';
$day_text[3]    = 'http://www.srh.noaa.gov/data/WNS/SWODY3';
#
# ----------------------------------------------------------------------
# menu entries (small imgages at the right) per start day
#   So at day 2 selected the menu display day 1, 3 and 4.
#   There is no day 0
#
$day_small[1]   = 'day1otlk.gif';
$day_small[2]   = 'day2otlk.gif';
$day_small[3]   = 'day3otlk.gif';
$day_small[4]   = 'day48prob.gif';
$day_menu[1]    = array (0,2,3,4);
$day_menu[2]    = array (0,1,3,4);
$day_menu[3]    = array (0,1,2,4);
$day_menu[4]    = array (0,1,2,3);
#
# ----------------------------------------------------------------------
# Now we decompress the tab array for the the choosen day
#
$tab_arr        = array();              // tha tabs foor the day 4 for day 1,  2 foir day 2-3
$img_arr        = array();              // the images which should be displayed
$text_arr       = array();              // the selection name for the tab
$explain_arr    = array();              // the text beneauth the image in the tab
#
$end_this       = count($day_arr[$nr]);
#                                               // generate tables for this day
for ($i = 1;  $i <= $end_this  ; $i++) {
        list ($tab,$img,$text,$explain) = explode ('|', $day_arr[$nr][$i].'||');
        $tab_arr[$i]    = trim($tab);
        $img_arr[$i]    = trim($img);
        $text_arr[$i]   = trim($text);
        $explain_arr[$i]= trim($explain);
}
# --------------- do we have to generate the page every so many seconds ?
if (isset ($refresh)  && $refresh > 600 ) echo '<meta http-equiv="refresh" content="'.$refresh.'"/>'.PHP_EOL;     // just in case - it was in the old scripts
#
# ----------------------------------------------------------------------
# generate the javascript for the different images.
#
echo
'<script type="text/javascript">
   function show_tab(nam)
   {';
for ($i = 1;  $i <= $end_this  ; $i++) {
        echo    '    document.getElementById("'.$tab_arr[$i].'").style.display= "none";'.PHP_EOL;
}
echo
'    document.getElementById(nam).style.display = "block";
   }
  </script>
<br />
<table class="genericTable" style="max-width: 1100px;  width:98%; margin: 0 auto;">
  <tr>
     <td style="text-align: left; width: 80%; padding-right: 4px; " >'.PHP_EOL;
# ----------------------------------------------------------------------
# generate the "mouse over menu"
#
for ($i = 1;  $i <= $end_this  ; $i++) {
        echo '        <b  '.$style_select .'  onmouseover="show_tab(\''.$tab_arr[$i].'\')">'.$text_arr[$i].'</b>'.PHP_EOL;
}
echo ' 
        <i style="text-align:left;"><small> &lt;= Move cursor over selections to display the selected graphic below.</small></i>           
    </td>
     <td>
     <p style="font-size:14px; font-weight:bold;">Day '.$day_menu[$nr][1].'</p>
     </td>
   </tr>
   <tr>  
      <td style="text-align: left; width: 80%; padding-right: 4px; " >
        <div style="text-align:center; max-width:860px; margin: 0 auto; width: 100%;">'.PHP_EOL;
$display = 'block';
# ----------------------------------------------------------------------
# generate the tabs and the link to the images in the tabs
#
for ($i = 1;  $i <= $end_this  ; $i++) {
        echo '                <div id="'.$tab_arr[$i].'" style="display:'.$display.';">
                        <img alt="" src="'.$img_url.$img_arr[$i].'" style="width: 100%;"/>
                        <br />'.$explain_arr[$i].'<br />
                </div>'.PHP_EOL;
        $display ='none';
}
# ----------------------------------------------------------------------
# load the text file for this day 
#
echo
'        </div>
        <pre style="text-align: left; width: 80%; margin: 0 auto;">'.PHP_EOL;
                $nws = file_get_contents($day_text[$nr]);
                print $nws;
# ----------------------------------------------------------------------
# generate the menu 
#
echo
'        </pre>        
     </td>
     <td style="vertical-align: top;">
        <a href="index.php?p='.$p.'&amp;day='.$day_menu[$nr][1].'&amp;lang='.$lang.'#data-area">
        <img src="'.$img_url.$day_small[$day_menu[$nr][1]].'" alt="" style="width: 100%;"/></a>

        <p style="text-align:center; font-size:14px; font-weight:bold;">Day '.$day_menu[$nr][2].'</p>
        <a href="index.php?p='.$p.'&amp;day='.$day_menu[$nr][2].'&amp;lang='.$lang.'#data-area">
        <img src="'.$img_url.$day_small[$day_menu[$nr][2]].'" alt="" style="width: 100%;"/></a>

        <p style="text-align:center; font-size:14px; font-weight:bold;">Day '.$day_menu[$nr][3].'</p>
        <a href="index.php?p='.$p.'&amp;day='.$day_menu[$nr][3].'&amp;lang='.$lang.'#data-area">
        <img src="'.$img_url.$day_small[$day_menu[$nr][3]].'" alt="" style="width: 100%;"/></a>
     </td>
  </tr>
</table>'.PHP_EOL;
#
#-------------------------------------------------------
# mouse over blocks                     #  menu area
#-------------------------------------- #
# images only one shown, rest hidden    #
# ------------------------------------- #   
# text forecast for this   day          #
#-------------------------------------------------------                               