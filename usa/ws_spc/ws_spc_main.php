<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_spc_days.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.10 2015-01-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.10 2015-01-05 first release version
# ----------------------------------------------------------------------
# display head lines of page
# ----------------------------------------------------------------------
echo '<div class="blockDiv">
<h3 class="blockHead">Information from NOAA Storm Prediction Center at this website</h3>'.PHP_EOL;
# ----------------------------------------------------------------------
# display one or more extra links to other SPC information
# ----------------------------------------------------------------------
# first check wich page / script is needed.
$page   = 'day';
if      (isset ($_REQUEST['spc_outlook']) )         {$page   = 'outlook';}
elseif  (isset ($_REQUEST['spc_discussions']) )     {$page   = 'discussions';}
elseif  (isset ($_REQUEST['spc_watches']) )         {$page   = 'watches';}
elseif  (isset ($_REQUEST['spc_reports']) )         {$page   = 'reports';}
#
$style_selected = 'style="background-color: grey;"';
$style_other    = 'style="background-color: white;"';
#
echo '<div><table class="genericTable" style="margin: 10px auto;">
<tr><td style="text-align: right;">More SPC info on this site:&nbsp;</td>
<td style ="text-align: left;">
<form method="get">
	<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" value="'.$p.'">'.PHP_EOL;
if ($page == 'day')             { $style     = $style_selected; }  else { $style = $style_other; }
echo'	<input type="submit" id="spc_day"           name="spc_day"              value="Daily outlooks" '.$style.'>'.PHP_EOL;

if ($page == 'reports')         { $style     = $style_selected; }  else { $style = $style_other; }       
echo'	<input type="submit" id="spc_reports"       name="spc_reports"          value="Severe Weather Reports" '.$style.'>'.PHP_EOL;

if ($page == 'outlook')         { $style     = $style_selected; }  else { $style = $style_other; }
echo'	<input type="submit" id="spc_outlook"       name="spc_outlook"          value="Public Weather Outlook" '.$style.'>'.PHP_EOL;

if ($page == 'watches')         { $style     = $style_selected; }  else { $style = $style_other; }
echo'	<input type="submit" id="spc_watches"       name="spc_watches"          value="Severe Weather Watches" '.$style.'>'.PHP_EOL;

if ($page == 'discussions')     { $style     = $style_selected; }  else { $style = $style_other; }
echo'	<input type="submit" id="spc_discussions"   name="spc_discussions"      value="Mesoscale Discussions" '.$style.'>'.PHP_EOL;
echo '</form>
</td></tr>
</table></div>'.PHP_EOL;

#
switch ($page) {
# ----------------------------------------------------------------------
# load the day script
# ----------------------------------------------------------------------
        case 'day':
                $script_load[1] = 'ws_spc_day1_3.php';
                $script_load[2] = 'ws_spc_day1_3.php';
                $script_load[3] = 'ws_spc_day1_3.php';
                $script_load[4] = 'ws_spc_day4_8.php';
                $script_head[1] = 'SPC Day 1 Outlook';
                $script_head[2] = 'SPC Day 2 Outlook';
                $script_head[3] = 'SPC Day 3 Outlook';
                $script_head[4] = 'SPC Day 4-8 Outlook';
                $script_text[1] = '0600 UTC, 1300 UTC, 1630 UTC, 2000 UTC, 0100 UTC';
                $script_text[2] = '100 am CST/CDT (0600/0700 UTC) and 1730 UTC';
                $script_text[3] = '400 am CST/CDT (0900/1000 UTC)';
                $script_text[4] = '400 am CST/CDT (0900/1000 UTC)';
                # ----------------------------------------------------------------------
                # check which page of 4 available should be displayed
                # ----------------------------------------------------------------------
                if (isset ($_REQUEST['day']) )  {$nr = (int) $_REQUEST['day'];} else { $nr = 1;}
                if ($nr < 0 || $nr > 4) {$nr = 1;}
                echo '<h3 class="blockHead">'.$script_head[$nr].'</h3>
                <h4 class="blockHead"><small>Updates are issued at '.$script_text[$nr].'&nbsp;-&nbsp;Current UTC time: '.gmdate ($SITE['timeFormat']).'</small></h4>'.PHP_EOL;
                echo '<!-- loading '.$script_load[$nr].' -->'.PHP_EOL;
                include $script_load[$nr];
        break;
# ----------------------------------------------------------------------
# load the spc_outlook information
# ----------------------------------------------------------------------
        case 'outlook':
                echo '<h3 class="blockHead">SPC Public Severe Weather Outlook</h3>
<h4 class="blockHead">Will be updated as necessary by the SPC.</h4>
<p style="text-align:center;"><br />The information below may or may not be current. Check the issuing Time and Date.</p>
<div style="text-align:left; font-weight:bold;">
<pre style="text-align:center;">'.PHP_EOL;
                $nws = file_get_contents("http://w1.weather.gov/data/WNS/PWOSPC");
                print $nws;
                echo '</pre></div>'.PHP_EOL;
        break;
# ----------------------------------------------------------------------
# load the spc_discussions  RSS feed 
# ----------------------------------------------------------------------
        case 'discussions' :
                echo '<h3 class="blockHead">SPC Mesoscale Discussions</h3>
<div style="width: 590px; margin: 10px auto;">
        <img src="http://www.spc.noaa.gov/products/md/validmd.png"      style="width: 582px;" alt=""/>
        <img src="http://www.spc.noaa.gov/products/md/mdlegend.gif"     style="width: 582px;" alt=""/>
</div>
<div style="width: 100%; text-align: center; margin: 10px;">'.PHP_EOL;
                $doSummary      = true;
                $includeOnly    = 'Y';
                include  'rss-mesoscale-test2.php';
                echo '</div>'.PHP_EOL;        
        break;
# ----------------------------------------------------------------------
# load the spc_watches RSS feed
# ----------------------------------------------------------------------
        case 'watches' :
                echo '<h3 class="blockHead">SPC Watches</h3>
<div style="width: 590px; margin: 10px auto;">
        <img src="http://www.spc.noaa.gov/products/watch/validww.png"   width="582" alt=""/><br />
        <img src="http://www.spc.noaa.gov/products/watch/wwlegend.png"  width="582" height="20" alt=""/>               
</div>                
<div style="width: 100%; text-align: center; margin: 10px;">'.PHP_EOL;
                $doSummary      = true;
                $includeOnly    = 'Y';
                include 'rss-spcwatch.php';
                echo '</div>'.PHP_EOL;
        break;
# ----------------------------------------------------------------------
# load the spc_reports script
# ----------------------------------------------------------------------
        case 'reports' :
                echo '<!-- loading ws_spc_reports.php -->'.PHP_EOL;
                include 'ws_spc_reports.php';
        break;
# ----------------------------------------------------------------------        
        default:
                echo 'not yet processed -'.$page;    
}
# ----------------------------------------------------------------------
# closing div for the page
# ----------------------------------------------------------------------
?>
<h3 class="blockHead">
Largly based on original scripts from Ken True: 
<a href="http://saratoga-weather.org/" target="_blank">saratoga-weather.org</a>
and Rick Curly:  
<a href="http://ricksturf.com" target="_blank">ricksturf.com</a></h3>
</div>
