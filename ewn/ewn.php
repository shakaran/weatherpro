<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ewn.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.01 2015-02-17';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.01 2015-02-17 SETTINGS adapted for leuven-Template - sce=view added
#-----------------------------------------------------------------------
############################################################################
# European Weather Network v. 2013 (Nov 2013)
############################################################################
#
# Author:	Henkka <nordicweather@gmail.com.net>
#
# Copyright:	(c) 2008-2013 Copyright nordicweather.net.
#
############################################################################
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
############################################################################
#
# This work is licensed under the 
# Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. 
# To view a copy of this license, visit 
# http://creativecommons.org/licenses/by-nc-nd/3.0/.
#
############################################################################
if (! isset($lang)) { $lang = "en"; }
if($_GET[lang]){$lang=$_GET[lang];}
if($lang == "se"){$lang="sv";$oldlang = "se";}
if($lang == "dk"){$lang="da";$oldlang = "dk";}

include ('ewn/ewn.lang.'.$lang.'.php');

# Your personal API-key, get it from EWN member-area. Required from 2015-01-01.
$apikey       = "";     
$scrollwheel  = 1;                              // Scrollwheel zoom? 1 = yes, 0 = no
$centerlat    = $SITE['latitude'];              // Centerlatitude of the map
$centerlon    = $SITE['longitude'];             // Centerlongitude of the map
$deflevel     = 5;                              // Deafult zoomlevel of the map, 3-9
$basemap      = "sat";

$scrambled=frccurl("http://www.europeanweathernetwork.eu/frc/key.php?k=".$apikey);
$ewnhead='
<link rel="stylesheet" href="http://esri.nordicweather.net/3.6/3.6compact/js/esri/css/esri.css" />
<link rel="stylesheet" href="ewn/ewn_style.css" />
<script src="http://static.nordicweather.net/jq/jquery-1.8.2.min.js"></script>
<script src="http://static.nordicweather.net/jq/bootstrap_jq.min.js"></script>
<script src="http://static.nordicweather.net/jq/jquery.tablesorter.js"></script>
<script src="http://static.nordicweather.net/jq/highstock.js"></script>
<script>var dojoConfig = { paths: {es: location.pathname.replace(location.pathname, "http://static.nordicweather.net/jq")}};</script>
<script src="http://esri.nordicweather.net/3.6/3.6compact/init.js"></script>
';

$ewndata='
<div id="ewnwrapper">
<div id="ewnnav">
<span style="float:right">
<label>
    <select id="wspd">
        <option selected>m/s</option>
        <option>km/h</option>
        <option>mph</option>
    </select>
</label>
</span>
<ul>
<li><span class="ewnnav" data-name="" data-l="1" id="map">'.MAP.'</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.'" data-l="0" id="currcond">'.CURRCOND.'</span></li>
<li><span class="ewnnav" data-name="'.TOPLISTS.'" data-l="1" id="toplist">'.TOPLISTS.'</span></li>
<li><span class="ewnnav" data-name="'.STSTATS.'" data-l="1" id="stats">'.STSTATS.'</span></li>
</ul>

</div>
<div id="ewnsubnav">
<ul>
<li><span class="ewnnav" data-name="'.CURRCOND.' - '.PWSSTATIONS.'" data-l="2" id="pws">'.PWSWX.'</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.' - '.SYNOPSTATIONS.'" data-l="2" id="synop">Synop</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.' - '.ROADEXT.'" data-l="2" id="road">'.ROADWX.'</span></li>
<li><span class="nonlink">'.METEXT.'</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.' - FMI" data-l="2" id="fmi">FMI</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.' - EMHI" data-l="2" id="emhi">EMHI</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.' - SMHI" data-l="2" id="smhi">SMHI</span></li>
<li><span class="ewnnav" data-name="'.CURRCOND.' - met.no" data-l="2" id="yr">met.no</span></li>
</ul>
</div>

<h1 id="ewnheader" style="line-height:42px;"></h1>
<div id="ewndiv"></div>
<div style="padding:4px 0">
<span style="float:right"><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/"><img alt="Creative Commons -lisenssi" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a></span>
&copy; '.date("Y").' <a href="http://www.europeanweathernetwork.eu" target="_blank">European Weathernetwork</a>. Data by each PWS-station, 
<a href="http://www.blitzortung.org" target="_blank">Blitzortung</a>,
<a href="http://www.fmi.fi" target="_blank">FMI</a>, 
<a href="http://www.met.no" target="_blank">met.no</a>,
<a href="http://www.dmi.dk" target="_blank">DMI</a>.
Warnings by <a href="http://www.meteoalarm.eu" target="_blank">Meteoalarm</a>, for newest warnings visit your local metoffice website. 
Script by Henkka, <a href="http://www.nordicweather.net" target="_blank">nordicweather.net</a>. Reusage without permission forbidden.<br/>
Except where otherwise noted, content on this page is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/">Creative Commons Attribution 3.0 License</a>.
</div>
</div>

<script>
var wspd = "m/s",target="toplist",scrambled="'.$scrambled.'";
var globalX,globalY,chart,data;
var deflat='.$centerlat.',deflon='.$centerlon.',defzoom='.$deflevel.',scrollwheel='.$scrollwheel.';
var defbase="'.$basemap.'";

$(function () {

  if($.jStorage.get("ewnwind")){
    wiset=$.jStorage.get("ewnwind");
    $("#wspd").val(wiset);
    wspd = wiset;
  }
  $("#wspd").change(function() {
    wspd = $("#wspd").val();
    $.jStorage.set("ewnwind", wspd);
    $.jStorage.setTTL("ewnwind", 32000000000);
    getpage(target);
  });
  
  getpage("map");  
  var nam = $("#map").attr("data-name");
  $("#map").addClass("selected");
  $("#ewnheader").html(""); 
	
	$(".ewnnav").click(function () {
    target = $(this).attr("id");
    var nam = $(this).attr("data-name");
    var l = $(this).attr("data-l");
    if(l==0){
      $("#ewnsubnav").show();
      $(this).addClass("selected");
    }
    if(l==1){$("#ewnsubnav").hide();}
    if(l==2){
      $("#ewnsubnav .selected").removeClass("selected");
      $(this).addClass("selected");
    }
    if(l<2){
      $("#ewnnav .selected, #ewnsubnav .selected").removeClass("selected");
      $(this).addClass("selected");
    }
    if(l>0){
      $("#ewnheader").html(nam);
      $("#ewndiv").html(\'<div style="width:126px;height:22px;margin:200px auto;"><img src="http://static.nordicweather.net/img/loading10.gif" alt="" style="z-index:100;" /></div>\');
      getpage(target);
    }
    return false;          
  });

  function getpage(nam){
    $.ajax({
      dataType: "jsonp",
      cache: true,
      jsonpCallback: "ewn",
      url		: "http://www.europeanweathernetwork.eu/subpages/"+nam+".php",
      data	: {key:scrambled,mainwidth:875,lang:"'.$lang.'",user:true,wsp:wspd},
      success: function(stuff) {
        $("#ewndiv").html(stuff.data);
      }
    });
    return false;
  }
});
</script>
';

function frccurl($url) {
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120424 Firefox/12.0 PaleMoon/12.0');
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_TIMEOUT,20);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
  $response = curl_exec($ch);
  curl_close($ch); 
  return $response;
}
?>