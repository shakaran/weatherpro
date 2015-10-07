<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ws_sounding.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '0.10 2015-01-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 0.10 2015-01-05 first release version
# ----------------------------------------------------------------------
# settings:
$page_title             = 'SPC Sounding Analysis Map';
$utc_12_hour_available  = 13;
$noaa_left_link         = 'http://www.spc.noaa.gov/exper/soundings/help/leftside.html';
$noaa_right_link        = 'http://www.spc.noaa.gov/exper/soundings/help/rightside.html';
if (!isset($skiptopText) ) {$skiptopText = '#data-area';}
#
if (isset ($_GET['spcarea']) ) {
        $spc_area       = $_GET['spcarea'];
        $back_to        = 'Back to the ';
        $ymd_utc        = gmdate('ymd'); 
        $hour_utc       = gmdate('H');       
        $hour_00_img    = 'http://www.spc.noaa.gov/exper/soundings/'.$ymd_utc.'00_OBS/'.$spc_area.'.gif';
        if ($hour_utc >= $utc_12_hour_available) {
                $hour_12_img    = 'http://www.spc.noaa.gov/exper/soundings/'.$ymd_utc.'12_OBS/'.$spc_area.'.gif';}
        else   {$hour_12_img    = '';}
        } 
else {  $spc_area       = $back_to       = '';}
#
if (!isset ($lang) ) {$lang='';}
$link_1 = $phpself.'&amp;lang='.$lang.'&amp;spcarea=';
$link_2 = $skiptopText;
?>
<div class="blockDiv"><!-- leave this opening div as it is needed  a nice page layout -->
<h3 class="blockHead"><?php echo $page_title; ?> - Observed Radiosonde Data</h3>
<?php 
if ($spc_area <> '')  {  // we have to generate tabs
        echo '<div class="tabber" style="width: 100%; ">
<div class="tabbertab " style="padding: 0; background-color: transparent;">
<h3>'.$spc_area.' 00 UTC Sounding</h3>
    <div>
        <br />
        <img src="'.$hour_00_img.'" style="width: 100%;" alt=""/>
    </div>
</div><!-- eo 00 hour tab  div -->';

        if ($hour_12_img <> '') {
                echo '
<div class="tabbertab " style="padding: 0; background-color: transparent;">
<h3>'.$spc_area.' 12 UTC Sounding</h3>
    <div>
        <br />
        <img src="'.$hour_12_img.'" style="width: 100%;" alt=""/>      
    </div>
</div><!-- eo 12 hour tab  div -->';
        } // 12 hour if
        echo '
<div class="tabbertab " style="padding: 0; background-color: transparent;">
<h3>What you see on this page</h3>
<div style="background-color: #003366;">
<table class="genericTable" style=""width: 100%;><tr><td>
<iframe src="'.$noaa_left_link.'" name="leftFrame" style="border: none; width: 700px; height: 700px; "></iframe>
</td><td><iframe src="'.$noaa_right_link.'" name="mainFrame" style="border: none; width: 100%; height: 100%;"></iframe>
</td></tr></table>

</div>
</div> <!-- eo help info tabber div -->';
##
        echo '
<div class="tabbertab " style="padding: 0; background-color: transparent;">
<h3>'.$back_to.' US - Map</h3>'.PHP_EOL;
} 
?>
<p style="text-align:center;">
        <br />
        Clicking on a site on the map will load 00 UTC sounding for that site and if available the 12 UTC reading. 
        <br /> 
        Note:  Not all sites or readings are always available.</p>
<div style="background-color:#003366; text-align:center;">
        <img src="http://sacrey.info/images/usmap.newids.gif" usemap="#usmap.newids" height="486" width="740" alt=""/>
<br />
<p style="color: white; font-size:10px; text-align:center; margin-bottom; 0px;">
        <br />
        Images/Information courtesy of the <a href="http://www.spc.noaa.gov/" style="color:#FFF" target="_blank">SPC</a>.
</p>
<map name="usmap.newids" id="usmap.newids">

<!-- United States -->

<!-- WASHINGTON -->
<area shape="circle" coords="46,68,18" href="<?php echo $link_1.strtoupper('uil').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="116,93,18" href="<?php echo $link_1.strtoupper('otx').$link_2.'"'; ?> alt=" " />

<!-- OREGON -->
<area shape="rect" coords="45,115,95,138" href="<?php echo $link_1.strtoupper('sle').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="38,150,90,170" href="<?php echo $link_1.strtoupper('mfr').$link_2.'"'; ?> alt=" " />

<!-- CALIFORNIA -->
<area shape="rect" coords="20,223,66,252" href="<?php echo $link_1.strtoupper('oak').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="26,263,64,285" href="<?php echo $link_1.strtoupper('vbg').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="74,326,14" href="<?php echo $link_1.strtoupper('nkx').$link_2.'"'; ?> alt=" " />

<!-- NEVADA -->

<area shape="circle" coords="68,205,17" href="<?php echo $link_1.strtoupper('rev').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="116,212,14" href="<?php echo $link_1.strtoupper('lkn').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="110,281,18" href="<?php echo $link_1.strtoupper('vef').$link_2.'"'; ?> alt=" " />

<!-- IDAHO -->
<area shape="circle" coords="129,166,20" href="<?php echo $link_1.strtoupper('boi').$link_2.'"'; ?> alt=" " />

<!-- MONTANA -->
<area shape="circle" coords="231,101,17" href="<?php echo $link_1.strtoupper('ggw').$link_2.'"'; ?> alt=" " />

<area shape="circle" coords="190,120,21" href="<?php echo $link_1.strtoupper('tfx').$link_2.'"'; ?> alt=" " />

<!-- WYOMING -->
<area shape="circle" coords="207,190,21" href="<?php echo $link_1.strtoupper('riw').$link_2.'"'; ?> alt=" " />

<!-- UTAH -->
<area shape="circle" coords="162,220,18" href="<?php echo $link_1.strtoupper('slc').$link_2.'"'; ?> alt=" " />

<!-- ARIZONA -->
<area shape="circle" coords="148,290,17" href="<?php echo $link_1.strtoupper('fgz').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="152,358,13" href="<?php echo $link_1.strtoupper('tus').$link_2.'"'; ?> alt=" " />

<!-- NEW MEXIC0 -->
<area shape="circle"
 coords="217,307,20" href="<?php echo $link_1.strtoupper('abq').$link_2.'"'; ?> alt=" " />

<!-- COLORADO -->
<area shape="circle" coords="201,251,18" href="<?php echo $link_1.strtoupper('gjt').$link_2.'"'; ?> alt=" " />

<area shape="circle" coords="255,250,18" href="<?php echo $link_1.strtoupper('dnr').$link_2.'"'; ?> alt=" " />

<!-- NORTH DAKOTA -->
<area shape="circle" coords="299,129,16" href="<?php echo $link_1.strtoupper('bis').$link_2.'"'; ?> alt=" " />

<!-- SOUTH DAKOTA -->

<area shape="circle" coords="321,163,15" href="<?php echo $link_1.strtoupper('abr').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="263,172,309,196" href="<?php echo $link_1.strtoupper('rap').$link_2.'"'; ?> alt=" " />

<!-- NEBRASKA -->
<area shape="rect" coords="273,206,309,231" href="<?php echo $link_1.strtoupper('lbf').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="331,207,363,228" href="<?php echo $link_1.strtoupper('oax').$link_2.'"'; ?> alt=" " />

<!-- KANSAS -->
<area shape="rect" coords="288,261,323,282" href="<?php echo $link_1.strtoupper('ddc').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="355,250,14" href="<?php echo $link_1.strtoupper('top').$link_2.'"'; ?> alt=" " />

<!-- OKLAHOMA -->
<area shape="poly" coords="319,303,320,319,338,323,351,316,350,306,328,305,319,305,319,310,319,313" href="<?php echo $link_1.strtoupper('oun').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="334,296,8" href="<?php echo $link_1.strtoupper('lmn').$link_2.'"'; ?> alt=" " />

<!-- TEXAS -->
<area shape="circle" coords="280,309,19" href="<?php echo $link_1.strtoupper('ama').$link_2.'"'; ?> alt=" " />

<area shape="rect" coords="197,343,230,372" href="<?php echo $link_1.strtoupper('epz').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="256,361,297,387" href="<?php echo $link_1.strtoupper('maf').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="320,351,365,378" href="<?php echo $link_1.strtoupper('fwd').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="279,403,322,420" href="<?php echo $link_1.strtoupper('drt').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="314,424,349,443" href="<?php echo $link_1.strtoupper('crp').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="325,458,379,481" href="<?php echo $link_1.strtoupper('bro').$link_2.'"'; ?> alt=" " />

<!-- LOUISIANA -->
<area shape="rect" coords="374,354,417,371" href="<?php echo $link_1.strtoupper('shv').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="382,379,420,404" href="<?php echo $link_1.strtoupper('lch').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="430,386,472,409" href="<?php echo $link_1.strtoupper('lix').$link_2.'"'; ?> alt=" " />

<!-- ARKANSAS -->
<area shape="circle" coords="399,315,16" href="<?php echo $link_1.strtoupper('lzk').$link_2.'"'; ?> alt=" " />

<!--MISSOURI -->
<area shape="circle" coords="388,278,15" href="<?php echo $link_1.strtoupper('sgf').$link_2.'"'; ?> alt=" " />

<!-- IOWA -->
<area shape="circle" coords="400,210,19" href="<?php echo $link_1.strtoupper('dvn').$link_2.'"'; ?> alt=" " />

<!-- MINNESOTA -->
<area shape="circle" coords="377,117,15" href="<?php echo $link_1.strtoupper('inl').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="379,171,19" href="<?php echo $link_1.strtoupper('mpx').$link_2.'"'; ?> alt=" " />

<!-- WISCONSIN -->
<area shape="rect" coords="407,152,449,175" href="<?php echo $link_1.strtoupper('grb').$link_2.'"'; ?> alt=" " />

<!-- MICHIGAN -->

<area shape="rect" coords="456,150,490,175" href="<?php echo $link_1.strtoupper('apx').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="469,185,507,209" href="<?php echo $link_1.strtoupper('dtx').$link_2.'"'; ?> alt=" " />

<!-- ILLINOIS -->

<area shape="circle"
 coords="432,246,14" href="<?php echo $link_1.strtoupper('ilx').$link_2.'"'; ?> alt=" " />

<!-- OHIO -->
<area shape="circle"
 coords="497,230,17" href="<?php echo $link_1.strtoupper('iln').$link_2.'"'; ?> alt=" " />

<!-- TENNESSEE -->
<area shape="circle" coords="468,301,16" href="<?php echo $link_1.strtoupper('bna').$link_2.'"'; ?> alt=" " />

<!-- MISSISSIPPI -->
<area shape="circle" coords="438,351,16" href="<?php echo $link_1.strtoupper('jan').$link_2.'"'; ?> alt=" " />

<!-- ALABAMA -->
<area shape="rect" coords="459,326,493,350" href="<?php echo $link_1.strtoupper('bmx').$link_2.'"'; ?> alt=" " />

<!-- GEORGIA -->
<area shape="rect" coords="500,327,537,347" href="<?php echo $link_1.strtoupper('ffc').$link_2.'"'; ?> alt=" " />

<!-- FLORIDA -->
<area shape="rect" coords="503,360,536,387" href="<?php echo $link_1.strtoupper('tlh').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="547,365,585,382" href="<?php echo $link_1.strtoupper('jax').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="572,402,9" href="<?php echo $link_1.strtoupper('xmr').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="574,426,604,447" href="<?php echo $link_1.strtoupper('mia').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="514,410,558,441" href="<?php echo $link_1.strtoupper('tbw').$link_2.'"'; ?> alt=" " />

<!-- SOUTH CAROLINA -->
<area shape="rect" coords="544,314,582,338" href="<?php echo $link_1.strtoupper('chs').$link_2.'"'; ?> alt=" " />

<!-- NORTH CAROLINA -->
<area shape="rect" coords="585,284,626,317" href="<?php echo $link_1.strtoupper('mhx').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="534,278,572,302" href="<?php echo $link_1.strtoupper('gso').$link_2.'"'; ?> alt=" " />

<!-- VIRGINA, MARYLAND, D.C. -->
<area shape="rect" coords="536,254,585,272" href="<?php echo $link_1.strtoupper('rnk').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="597,235,641,251" href="<?php echo $link_1.strtoupper('wal').$link_2.'"'; ?> alt=" " />

<area shape="rect" coords="557,229,585,246" href="<?php echo $link_1.strtoupper('iad').$link_2.'"'; ?> alt=" " />

<!-- PENNSYLVANIA -->
<area shape="rect" coords="523,189,559,218" href="<?php echo $link_1.strtoupper('pit').$link_2.'"'; ?> alt=" " />

<!-- NEW YORK -->
<area shape="rect" coords="539,168,582,185" href="<?php echo $link_1.strtoupper('buf').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="573,147,607,168" href="<?php echo $link_1.strtoupper('alb').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="614,179,661,200" href="<?php echo $link_1.strtoupper('okx').$link_2.'"'; ?> alt=" " />

<!-- NEW ENGLAND -->
<area shape="rect" coords="638,151,687,173" href="<?php echo $link_1.strtoupper('chh').$link_2.'"'; ?> alt=" " />
<area shape="rect" coords="616,78,652,102" href="<?php echo $link_1.strtoupper('car').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="635,128,16" href="<?php echo $link_1.strtoupper('gyx').$link_2.'"'; ?> alt=" " />

<!--  Canada  -->
<!-- British Columbia -->
<area shape="circle" coords="124,49,20" href="<?php echo $link_1.strtoupper('ylw').$link_2.'"'; ?> alt=" " />

<!-- Ontario -->
<area shape="circle" coords="418,68,23" href="<?php echo $link_1.strtoupper('wpl').$link_2.'"'; ?> alt=" " />
<area shape="circle" coords="499,55,26" href="<?php echo $link_1.strtoupper('ymo').$link_2.'"'; ?> alt=" " />

<!-- Quebec -->
<area shape="rect" coords="543,86,595,121" href="<?php echo $link_1.strtoupper('wmw').$link_2.'"'; ?> alt=" " />
<!-- Nova Scotia Area -->
<area shape="rect" coords="660,112,709,141" href="<?php echo $link_1.strtoupper('yqi').$link_2.'"'; ?> alt=" " />

</map>
</div><!-- eo map div -->
<?php 
if ($spc_area <> '')  {
        echo '
</div><!-- eo tabber tab div -->
</div><!-- eo tabber  div -->
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;
}
?>
</div><!-- leave this closing div as it is needed  for the opening div -->


