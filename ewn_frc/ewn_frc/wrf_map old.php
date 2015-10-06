<?php
############################################################################
# European Weather Network WRF-Forecast v. 3.4 (Oct 2014)
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

$version    = 1.31;   # internal version
$speedms    = array(0,3,5,8,11,14,17,21,25,29,33);
$speedkmh   = array(0,10,20,30,40,50,60,75,90,105,120);
if($usekmh==true){$wspeeds=$speedkmh;$wunit="km/h";}else{$wspeeds=$speedms;$wunit="m/s";}
$frcdata[currlay]=1;
$is_mobile=detectmobile();
$scrambled=frccurl("http://www.europeanweathernetwork.eu/frc/key.php?k=".$apikey);
$frcdata[frc]=15;

if(!$map_only){
  # Parse args from the URL
  if(isset($_GET[lang])){$lang = $_GET[lang];}
  if(isset($_GET[p])){$p = '&p='.$_GET[p];} // Leuven fix
  $args='?lang='.$lang;
  $argb='&geoid=';
  $baseurl='http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"].$args.$p.$argb;

  if($_GET[lat] && is_numeric($_GET[lat])){$centerlat=$_GET[lat];}
  if($_GET[lon] && is_numeric($_GET[lon])){$centerlon=$_GET[lon];}
  if($_GET[geoid]){$geoid=$_GET[geoid];}

  if($geoid){$args.='&geoid='.$geoid.$p;}
  else{$args.='&lat='.$centerlat.'&lon='.$centerlon.$p;}
  
  $ewnargs = '&user=true&wsp='.$wunit.'&datestyle='.$datestyle.'&v='.$version.'&mobile='.$is_mobile.'&key='.$scrambled;

  //if($_GET[lat]||$_GET[geoid]){
    $frcdata = frccurl("http://www.europeanweathernetwork.eu/frc/data_php.php".$args.$ewnargs);
    $frcdata = json_decode($frcdata,true);
    $centerlat=$frcdata[plat];
    $centerlon=$frcdata[plon];
  //}
}

$tzdiff = date("Z")*1000;
$times = frccurl("http://a.maps.nordicweather.net/maptime_e.php?tz=".$tzabb."&du=".time());
$data = json_decode($times,true);

foreach ($days as $key => $value) {
  $da[]=utf8_encode($value);
}
$js_array = json_encode($da);

$ewnhead='
<link rel="stylesheet" href="http://static.nordicweather.net/css/ol2.css" />
<link rel="stylesheet" href="'.$path_to_css.'wrf_s.css?'.$version .'" />';

if($jqueryload ){
  $ewnfooter.='
  <script src="http://static.nordicweather.net/jq/jquery-1.8.2.min.js"></script>
  <script src="http://static.nordicweather.net/jq/bootstrap_jq.min.js"></script>';
}
$ewnfooter.='
<script src="http://static.nordicweather.net/jq/OpenLayersLite.js"></script>
<script src="http://static.nordicweather.net/jq/jquery.tablesorter.js"></script>
<script src="http://static.nordicweather.net/jq/ui_autocomplete.min.js"></script>
<script src="http://static.nordicweather.net/jq/ui_slider.min.js"></script>
<script src="http://static.nordicweather.net/jq/highcharts.4.0-all.js"></script>
<script>
';
if($_GET[lat]||$_GET[geoid]){
$ewnfooter.='
  var deflat='.$centerlat.',deflon='.$centerlon.',ajax=false;
  $(function(){dostuff();});
  ';
}else{
$ewnfooter.='
  var deflat='.$centerlat.',deflon='.$centerlon.',ajax=true;
  var isTouch = Modernizr.touch;
  if(isTouch){
    if(navigator.geolocation){
      function positionSuccess(location) {
        deflat = location.coords.latitude;
        deflon = location.coords.longitude;
        $.cookie("ewnloc", location.coords.latitude+"|"+location.coords.longitude, { expires : 1 });
        dostuff();
      }
      function positionFail() {
        dostuff();
      }
      var options = { enableHighAccuracy: true,timeout: 5000,maximumAge: 240000};
      navigator.geolocation.getCurrentPosition(positionSuccess, positionFail,options);
    }
  }else{
    $(function(){dostuff();});
  }
  ';
}
$ewnfooter.='   
  var baseurl="'.$baseurl.'",p="'.$p.'",mainwidth="'.$mainwidth.'",mousewheel='.$scrollwheel.',tzabb = "'.$tzabb.'",lang = "'.$lang.'";
  var currlay='.$frcdata[currlay].',defzoom='.$deflevel.',defmap="'.$defmap.'",scrollwheel='.$scrollwheel.',defbase="'.$basemap.'";
  var datestyle="'.$datestyle.'",phonedetect='.$phonedetect.',scrambled="'.$scrambled.'",version='.$version.';
  var mapconf = '.$times.';
  var days = '.$js_array.';
  var fdaynames = ['.$daynames.'];
  var ownplaces = "'.OWNPLACES.'",qsearch = "'.QUICKSEARCH.'";
  var temptxt="'.TEMP.'",barotxt="'.BARO.'",prectxt="'.PRECIP.'",windtxt="'.WIND.'",dewtxt="'.DEWP.'",snowtxt="'.SNOB.'",rangetxt="'.RANGE.'";
</script>
<script src="'.$path_to_js.'ewn_frc.js?'.$version .'"></script>
';

$nfrcbody = '
    <div id="mmaps">
    <span class="moremaps" id="fmi">FMI Hirlam - '.$data[fmi][fmireso].' km</span>
    <span class="moremaps" id="wrf">EWN WRFDA - '.$data[wrf][wrfreso].' km</span>
    <span class="moremaps col800" id="gfs">GFS - '.$data[gfs][gfsreso].' km</span>
    <span class="moremaps col450" id="base">'.BASEMAPS.'</span>
    </div>
    
    <div id="wrapper" class="tundra" style="position: relative; width: 100%; height: 675px">

    <div id="mapPropsContainer">  
    <table class="mapPropsTable" id="wrftable" style="width:500px;"><tr>
    <td class="mapPropsTd">
    <input type="radio" class="checkboxes" name="radio" id="wrf_dbz" data-txt1="<b>'.MCOMPRADAR2.':</b> '.DBZINFO.'"/>
    <label for="wrf_dbz">'.MCOMPRADAR.'</label><br/>
    <hr/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_temp" data-txt1="2m '.FTEMP.'"/>
    <label for="wrf_temp">'.FTEMP.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_chill" data-txt1="'.FCHILL.'"/>
    <label for="wrf_chill">'.FCHILL.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_dew" data-txt1="'.FDEW.'"/>
    <label for="wrf_dew">'.FDEW.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_tmpsfc" data-txt1="'.FTMPSFC.'"/>
    <label for="wrf_tmpsfc">'.FTMPSFC.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_wind" data-txt1="'.FWIND.'"/>
    <label for="wrf_wind">'.FWIND.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_gust" data-txt1="'.FGUST.'"/>
    <label for="wrf_gust">'.FGUST.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_psnow" data-txt1="'.FPREC.' + '.FCLOUD.'"/>
    <label for="wrf_psnow">'.FPREC.' + '.FCLOUD.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_clouds" data-txt1="'.FCLOUD.'"/>
    <label for="wrf_clouds">'.FCLOUD.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_precip" data-txt1="'.FPREC.'"/>
    <label for="wrf_precip">'.FPREC.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_rrate" data-txt1="'.FRATE.'"/>
    <label for="wrf_rrate">'.FRATE.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_prectyp" data-txt1="'.FPRECTYP.'"/>
    <label for="wrf_prectyp">'.FPRECTYP.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_accum" data-txt1="'.FACCUM.'"/>
    <label for="wrf_accum">'.FACCUM.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_snowd" data-txt1="'.FSNOWD.'"/>
    <label for="wrf_snowd">'.FSNOWD.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_baro" data-txt1="'.FBARO.'"/>
    <label for="wrf_baro">'.FBARO.'</label><br/>
    </td>
    <td  style="width: 1px;padding:0;
  background-image: -webkit-linear-gradient(top, rgba(92,172,238,0), rgba(92,172,238,0.75), rgba(92,172,238,0)); 
  background-image: -moz-linear-gradient(top, rgba(92,172,238,0), rgba(92,172,238,0.75), rgba(92,172,238,0)); 
  background-image: -ms-linear-gradient(top, rgba(92,172,238,0), rgba(92,172,238,0.75), rgba(92,172,238,0)); 
  background-image: -o-linear-gradient(top, rgba(92,172,238,0), rgba(92,172,238,0.75), rgba(92,172,238,0));"></td>
    <td class="mapPropsTd">
    <input type="radio" class="checkboxes" name="radio" id="wrf_severe" data-txt1="'.SEVRISK.'"/>
    <label for="wrf_severe">'.SEVRISK.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_hail" data-txt1="'.SIGHAIL.'"/>
    <label for="wrf_hail">'.SIGHAIL.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_tornado" data-txt1="'.SIGTOR.'"/>
    <label for="wrf_tornado">'.SIGTOR.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_supercell" data-txt1="'.SIGSUP.'"/>
    <label for="wrf_supercell">'.SIGSUP.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_cin" data-txt1="Cin"/>
    <label for="wrf_cin">CIN</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_lftx" data-txt1="Lifted Index"/>
    <label for="wrf_lftx">Lifted Index</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_cape" data-txt1="CAPE"/>
    <label for="wrf_cape">CAPE</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_srh3" data-txt1="<b>Storm-relative Helicity 0-3 km: </b>'.SRHINFO.'"/>
    <label for="wrf_srh3">SRH 0-3 km</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_shear01" data-txt1="Low level Shear 0-1 km"/>
    <label for="wrf_shear01">LLS 0-1 km</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_shear06" data-txt1="Deep level Shear 0-6 km"/>
    <label for="wrf_shear06">DLS 0-6 km</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_hgt500" data-txt1="500 hPa Geopotential Height"/>
    <label for="wrf_hgt500">500 hPa Geopotential Height</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_plcl" data-txt1="Lifting condensation level"/>
    <label for="wrf_plcl">Lifting condensation level</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="wrf_t850" data-txt1="850 hPa '.FTEMP.'"/>
    <label for="wrf_t850">850 hPa '.FTEMP.'</label><br/>
    </td>
    </tr></table>
    
    <table class="mapPropsTable" id="fmitable"><tr>
    <td class="mapPropsTd">
    <input type="radio" class="checkboxes" name="radio" id="fmi_temp" data-txt1="2m '.FTEMP.'"/>
    <label for="fmi_temp">'.FTEMP.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_chill" data-txt1="'.FCHILL.'"/>
    <label for="fmi_chill">'.FCHILL.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_dew" data-txt1="'.FCHILL.'"/>
    <label for="fmi_dew">'.FDEW.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_wind" data-txt1="'.FWIND.'"/>
    <label for="fmi_wind">'.FWIND.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_gust" data-txt1="'.FGUST.'"/>
    <label for="fmi_gust">'.FGUST.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_psnow" data-txt1="'.FPREC.' + '.FCLOUD.'"/>
    <label for="fmi_psnow">'.FPREC.' + '.FCLOUD.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_clouds" data-txt1="'.FCLOUD.'"/>
    <label for="fmi_clouds">'.FCLOUD.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_precip" data-txt1="'.FPREC.'"/>
    <label for="fmi_precip">'.FPREC.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="fmi_baro" data-txt1="'.FBARO.'"/>
    <label for="fmi_baro">'.FBARO.'</label><br/>
    </td>
    </tr></table>
    
    <table class="mapPropsTable" id="gfstable"><tr>
    <td class="mapPropsTd" id="test">
    <input type="radio" class="checkboxes" name="radio" id="gfs_temp" data-txt1="2m '.FTEMP.'"/>
    <label for="gfs_temp">'.FTEMP.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_chill" data-txt1="'.FCHILL.'"/>
    <label for="gfs_chill">'.FCHILL.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_dew" data-txt1="'.FDEW.'"/>
    <label for="gfs_dew">'.FDEW.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_wind" data-txt1="'.FWIND.'"/>
    <label for="gfs_wind">'.FWIND.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_psnow" data-txt1="'.FPREC.'"/>
    <label for="gfs_psnow">'.FPREC.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_baro" data-txt1="'.FBARO.'"/>
    <label for="gfs_baro">'.FBARO.'</label><br/>
    <hr/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_tornado" data-txt1="'.SIGTOR.'"/>
    <label for="gfs_tornado">'.SIGTOR.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_supercell" data-txt1="'.SIGSUP.'"/>
    <label for="gfs_supercell">'.SIGSUP.'</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_cape" data-txt1="CAPE"/>
    <label for="gfs_cape">CAPE</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_lftx" data-txt1="LI Index"/>
    <label for="gfs_lftx">LI Index</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_hgt500" data-txt1="500 hPa Geopotential Height"/>
    <label for="gfs_hgt500">500 hPa Geopotential Height</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_plcl" data-txt1="Lifting condensation level"/>
    <label for="gfs_plcl">Lifting condensation level</label><br/>
    <input type="radio" class="checkboxes" name="radio" id="gfs_t850" data-txt1="850 hPa '.FTEMP.'"/>
    <label for="gfs_t850">850 hPa '.FTEMP.'</label><br/>
    </td>
    </tr></table>
    
    <table class="mapPropsTable" id="basetable"><tr>
    <td class="mapPropsTd">
    <input type="radio" class="checkboxesb" name="radiob" id="gray"/>
    <label for="gray">Natural Earth</label><br/>
    <input type="radio" class="checkboxesb" name="radiob" id="sat"/>
    <label for="sat">'.SATELLITE.'</label><br/>
    <input type="radio" class="checkboxesb" name="radiob" id="normal"/>
    <label for="normal">Normal</label><br/>
    </td>
    </tr></table>
    </div>
    
    <div id="map" style="position: absolute; width:100%; height: 675px; z-index: 1;"></div>
    
    <div id="lalegend"></div>
    <div id="sliderext">
      <span id="extwrap" style="float:right;display:inline-block;">
        <span id="frcplay" class="glyphicon glyphicon-play slide-player"></span>
        <span id="frcstop" class="glyphicon glyphicon-stop slide-player"></span>
        <span id="frcrepeat" class="glyphicon glyphicon-repeat slide-player"></span>
      </span>
      <span class="mapbutton">
        <span class="mapinfo_date"></span><span class="mapinfo_name"></span><span class="mapinfo_name2"></span>
      </span>
    </div>
    <div id="sliderwrap">
      <table style="width:100%"><tr>
        <td style="width:46px">
        <span id="frcprev" class="glyphicon glyphicon-chevron-left slide-prevnext"></span>
        </td>
        <td style="padding:0 10px;">
          <div id="slider" style="width:100%;top:-8px;"></div>
        </td>
        <td style="width:46px">
          <span id="frcnext" class="glyphicon glyphicon-chevron-right slide-prevnext"></span>
        </td>
      </tr></table>
    </div>

    <div id="lelegend"></div>
    </div>
    
    <div id="eventsLog"></div>
    	
	<table style="width:100%;margin-top:20px;" class="nordui-table">
	<tr class="nordui-table-header">
	<th style="width:15%"></th>
	<th style="width:40%">'.FUTIMES.' ('.$tzabb.')</th>
	<th style="width:15%">'.FRESO.'</th>
	<th style="width:15%">'.FLENGTH.'</th>
	<th style="width:15%" class="col450">'.FSTEPS.'</th>
	</tr>
	<tr>
	<td class="finfocontenttd"><b>EWN WRFDA</b></td>
	<td class="finfocontenttd">'.$data[wrf][wrfupd].'</td>
	<td class="finfocontenttd">'.$data[wrf][wrfreso].' km</td>
	<td class="finfocontenttd">'.$data[wrf][wrflength].'h</td>
	<td class="finfocontenttd col450">'.$data[wrf][wrfsteps].'h</td>
	</tr>
  <tr>
	<td class="finfocontenttd"><b>FMI Hirlam</b></td>
	<td class="finfocontenttd">'.$data[fmi][fmiupd].'</td>
	<td class="finfocontenttd">'.$data[fmi][fmireso].' km</td>
	<td class="finfocontenttd">'.$data[fmi][fmilength].'h</td>
	<td class="finfocontenttd col450">'.$data[fmi][fmisteps].'h</td>
	</tr>
	<tr>
	<td class="finfocontenttd"><b>GFS</b></td>
	<td class="finfocontenttd">'.$data[gfs][gfsupd].'</td>
	<td class="finfocontenttd">'.$data[gfs][gfsreso].' km</td>
	<td class="finfocontenttd">'.$data[gfs][gfslength].'h</td>
	<td class="finfocontenttd col450">'.$data[gfs][gfssteps].'h</td>
	</tr>
	</table>

<table class="letable gfs_precip wrf_rrate">
<tr><td class="letda" style="background-color:rgb(136,0,102)"></td><td class="letdb">30+ mm</td></tr>
<tr><td class="letda" style="background-color:rgb(131,0,136)"></td><td class="letdb">25-30 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(92,1,135)"></td><td class="letdb">20-25 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(0,0,153)"></td><td class="letdb">15-20 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(17,2,134)"></td><td class="letdb">10-15 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(2,12,156)"></td><td class="letdb">6-10 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(1,34,193)"></td><td class="letdb">4-6 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(1,64,209)"></td><td class="letdb">2-4 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(12,107,255)"></td><td class="letdb">1-2 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(50,162,255)"></td><td class="letdb">0.5-1 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(89,204,255)"></td><td class="letdb">0.2-0.5 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(128,235,255)"></td><td class="letdb">0.1-0.2 mm</td></tr>
</table>

<table class="letable fmi_psnow" style="width:110px;float:right">
<tr><td class="letda" style="background-color:rgb(136,0,102)"></td><td class="letdb">30+ mm</td></tr>
<tr><td class="letda" style="background-color:rgb(131,0,136)"></td><td class="letdb">25-30 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(92,1,135)"></td><td class="letdb">20-25 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(0,0,153)"></td><td class="letdb">15-20 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(17,2,134)"></td><td class="letdb">10-15 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(2,12,156)"></td><td class="letdb">6-10 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(1,34,193)"></td><td class="letdb">4-6 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(1,64,209)"></td><td class="letdb">2-4 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(12,107,255)"></td><td class="letdb">1-2 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(50,162,255)"></td><td class="letdb">0.5-1 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(89,204,255)"></td><td class="letdb">0.2-0.5 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(128,235,255)"></td><td class="letdb">0.1-0.2 mm</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/snow.png" alt="" /></td><td class="letdb">'.FSNOW.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/sleet.png" alt="" /></td><td class="letdb">'.FSLEET.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/hail.png" alt="" /></td><td class="letdb">'.FHAIL.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/frz.png" alt="" /></td><td class="letdb">'.FFRZ.'</td></tr>
</table>

<table class="letable wrf_psnow">
<tr><td class="letda" style="background-color:rgb(136,0,102)"></td><td class="letdb">30+ mm</td></tr>
<tr><td class="letda" style="background-color:rgb(131,0,136)"></td><td class="letdb">25-30 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(92,1,135)"></td><td class="letdb">20-25 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(0,0,153)"></td><td class="letdb">15-20 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(17,2,134)"></td><td class="letdb">10-15 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(2,12,156)"></td><td class="letdb">6-10 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(1,34,193)"></td><td class="letdb">4-6 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(1,64,209)"></td><td class="letdb">2-4 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(12,107,255)"></td><td class="letdb">1-2 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(50,162,255)"></td><td class="letdb">0.5-1 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(89,204,255)"></td><td class="letdb">0.2-0.5 mm</td></tr>
<tr><td class="letda" style="background-color:rgb(128,235,255)"></td><td class="letdb">0.1-0.2 mm</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/snow.png" alt="" /></td><td class="letdb">'.FSNOW.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/sleet.png" alt="" /></td><td class="letdb">'.FSLEET.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/hail.png" alt="" /></td><td class="letdb">'.FHAIL.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/frz.png" alt="" /></td><td class="letdb">'.FFRZ.'</td></tr>
<tr><td class="letda" style="text-align:center;"><img src="http://static.nordicweather.net/img/lightning.png" alt="" /></td><td class="letdb">'.FLIG.'</td></tr>
</table>

<table class="letable wrf_precip fmi_precip" style="width:85px;float:right">
<tr><td class="letda2" style="background-color:rgb(157,128,187)"></td><td class="letdb">50 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(186,166,208)"></td><td class="letdb">40 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(214,202,228)"></td><td class="letdb">30 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(252,227,227)"></td><td class="letdb">26 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(244,209,209)"></td><td class="letdb">22 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(222,161,161)"></td><td class="letdb">18 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,93,93)"></td><td class="letdb">14 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,0,0)"></td><td class="letdb">10 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(222,0,0)"></td><td class="letdb">9 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,0,0)"></td><td class="letdb">8 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,112,0)"></td><td class="letdb">7 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,186,0)"></td><td class="letdb">6 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(246,255,0)"></td><td class="letdb">5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(170,253,166)"></td><td class="letdb">4.5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(149,243,145)"></td><td class="letdb">4 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(120,230,115)"></td><td class="letdb">3.5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(94,217,88)"></td><td class="letdb">3 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(70,206,63)"></td><td class="letdb">2.5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(50,173,43)"></td><td class="letdb">2 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(45,30,165)"></td><td class="letdb">1.5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(72,60,200)"></td><td class="letdb">1 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(30,110,235)"></td><td class="letdb">0.8 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(60,150,245)"></td><td class="letdb">0.6 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(120,185,250)"></td><td class="letdb">0.4 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(150,210,250)"></td><td class="letdb">0.2 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(180,240,250)"></td><td class="letdb">0.05 mm</td></tr>
</table>

<table class="letable wrf_temp wrf_dew wrf_chill wrf_tmpsfc fmi_temp fmi_chill fmi_dew gfs_temp gfs_chill gfs_dew gfs_t850 wrf_t850" style="width:60px;float:right">
<tr><td class="letda2" style="background-color:rgb(223,6,84)"></td><td></td></tr>
<tr><td class="letda2" style="background-color:rgb(219, 9, 73)"></td><td></td></tr>
<tr><td class="letda2" style="background-color:rgb(215, 12, 62)"></td><td rowspan="5" class="letdb2">35&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(211, 15, 51)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(207, 18, 40)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(203, 21, 29)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(199, 24, 18)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(195, 28, 7)"></td><td rowspan="5" class="letdb2">30&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(207, 32, 7)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(211, 42, 8)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(215,52,8)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(219, 62, 9)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(223, 72, 9)"></td><td rowspan="5" class="letdb2">25&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(227,82,9)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(232, 92, 10)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(236, 102, 10)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(240, 112, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 122, 11)"></td><td rowspan="5" class="letdb2">20&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 144, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 152, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 160, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 168, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 176, 11)"></td><td rowspan="5" class="letdb2">15&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 184, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 192, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 200, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 208, 11)"></td></tr>
<tr><td class="letda2" style="background-color:#f4d90b"></td><td rowspan="5" class="letdb2">10&deg;</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td></tr>
<tr><td class="letda2" style="background-color:#ebe816"></td></tr>
<tr><td class="letda2" style="background-color:#e6eb20"></td></tr>
<tr><td class="letda2" style="background-color:#dcec2e"></td></tr>
<tr><td class="letda2" style="background-color:#d2ee3c"></td><td rowspan="5" class="letdb2">5&deg;</td></tr>
<tr><td class="letda2" style="background-color:#c8f04a"></td></tr>
<tr><td class="letda2" style="background-color:#bef158"></td></tr>
<tr><td class="letda2" style="background-color:#b4f266"></td></tr>
<tr><td class="letda2" style="background-color:#aff474"></td></tr>
<tr><td class="letda2" style="background-color:#aaf682"></td><td rowspan="5" class="letdb2">0&deg;</td></tr>
<tr><td class="letda2" style="background-color:#c7e4ff"></td></tr>
<tr><td class="letda2" style="background-color:#b5dcff"></td></tr>
<tr><td class="letda2" style="background-color:#a3d4ff"></td></tr>
<tr><td class="letda2" style="background-color:#9ad0ff"></td></tr>
<tr><td class="letda2" style="background-color:#91ccff"></td><td rowspan="5" class="letdb2">-5&deg;</td></tr>
<tr><td class="letda2" style="background-color:#7fc4ff"></td></tr>
<tr><td class="letda2" style="background-color:#6dbcff"></td></tr>
<tr><td class="letda2" style="background-color:#5bb4ff"></td></tr>
<tr><td class="letda2" style="background-color:#49acff"></td></tr>
<tr><td class="letda2" style="background-color:#259aff"></td><td rowspan="5" class="letdb2">-10&deg;</td></tr>
<tr><td class="letda2" style="background-color:#1392ff"></td></tr>
<tr><td class="letda2" style="background-color:#0082ef"></td></tr>
<tr><td class="letda2" style="background-color:#0072cf"></td></tr>
<tr><td class="letda2" style="background-color:#0062af"></td></tr>
<tr><td class="letda2" style="background-color:#00528f"></td><td rowspan="5" class="letdb2">-15&deg;</td></tr>
<tr><td class="letda2" style="background-color:#00467f"></td></tr>
<tr><td class="letda2" style="background-color:#003c7f"></td></tr>
<tr><td class="letda2" style="background-color:#00327f"></td></tr>
<tr><td class="letda2" style="background-color:#00287f"></td></tr>
<tr><td class="letda2" style="background-color:#001e7f"></td><td rowspan="5" class="letdb2">-20&deg;</td></tr>
<tr><td class="letda2" style="background-color:#00187f"></td></tr>
<tr><td class="letda2" style="background-color:#00007f"></td></tr>
<tr><td class="letda2" style="background-color:#0c007f"></td></tr>
<tr><td class="letda2" style="background-color:#19007f"></td></tr>
<tr><td class="letda2" style="background-color:#25007f"></td><td rowspan="5" class="letdb2">-25&deg;</td></tr>
<tr><td class="letda2" style="background-color:#32007f"></td></tr>
<tr><td class="letda2" style="background-color:#3e007f"></td></tr>
<tr><td class="letda2" style="background-color:#4b007f"></td></tr>
<tr><td class="letda2" style="background-color:#57007f"></td></tr>
<tr><td class="letda2" style="background-color:#64007f"></td><td rowspan="5" class="letdb2">-30&deg;</td></tr>
<tr><td class="letda2" style="background-color:#78048d"></td></tr>
<tr><td class="letda2" style="background-color:#870898"></td></tr>
<tr><td class="letda2" style="background-color:#960ca3"></td></tr>
<tr><td class="letda2" style="background-color:#a510ae"></td></tr>
<tr><td class="letda2" style="background-color:rgb(180,20,185)"></td><td rowspan="6" class="letdb2">-35&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(182,18,160)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(185,16,150)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(188,14,140)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(191,13,130)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(194,12,120)"></td></tr>
</table>

<table class="letable wrf_wind wrf_gust fmi_wind fmi_gust gfs_wind" style="width:85px;float:right">
<tr><td class="letda2" style="background-color:#f529d1"></td><td class="letdb">'.$wspeeds[10].'> '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(153,0,0)"></td><td class="letdb">'.$wspeeds[9].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,0,0)"></td><td class="letdb">'.$wspeeds[8].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,0,0)"></td><td class="letdb">'.$wspeeds[7].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,112,0)"></td><td class="letdb">'.$wspeeds[6].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(238,172,22)"></td><td class="letdb">'.$wspeeds[5].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:#ffff00"></td><td class="letdb">'.$wspeeds[4].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(223,248,6)"></td><td class="letdb">'.$wspeeds[3].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(160,230,23)"></td><td class="letdb">'.$wspeeds[2].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(75,205,46)"></td><td class="letdb">'.$wspeeds[1].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(0,153,0)"></td><td class="letdb">'.$wspeeds[0].' '.$wunit.'</td></tr>
</table>

<table class="letable wrf_cape gfs_cape" style="width:90px;float:right">
<tr><td class="letda2" style="background-color:#8C0A16"></td><td class="letdb">2600 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#a50b19"></td><td class="letdb">2400 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">2200 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">2000 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">1800 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">1600 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">1400 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">1200 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">1000 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">800 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">600 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">400 J/kg</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">200 J/kg</td></tr>
</table>

<table class="letable wrf_dbz" style="width:75px;float:right">
<tr><td class="letda2" style="background-color:#e6005a"></td><td class="letdb">70 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#dc0050"></td><td class="letdb">65 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#c8003c"></td><td class="letdb">60 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#a50b19"></td><td class="letdb">55 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">50 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">45 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">40 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">35 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">30 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">25 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">20 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">15 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#05CDAA"></td><td class="letdb">10 dBZ</td></tr>
<tr><td class="letda2" style="background-color:#0A9BE1"></td><td class="letdb">5 dBZ</td></tr>
</table>

<table class="letable wrf_lftx gfs_lftx" style="width:55px;float:right">
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">-1 K</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">-2 K</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">-3 K</td></tr>
<tr><td class="letda2" style="background-color:#ffff00"></td><td class="letdb">-4 K</td></tr>
<tr><td class="letda2" style="background-color:#ffcc00"></td><td class="letdb">-5 K</td></tr>
<tr><td class="letda2" style="background-color:#ff9900"></td><td class="letdb">-6 K</td></tr>
<tr><td class="letda2" style="background-color:#ff6600"></td><td class="letdb">-7 K</td></tr>
<tr><td class="letda2" style="background-color:#ff3300"></td><td class="letdb">-8 K</td></tr>
<tr><td class="letda2" style="background-color:#f529d1"></td><td class="letdb">-9 K</td></tr>
</table>

<table class="letable wrf_srh1 wrf_srh3 gfs_srh1 gfs_srh3" style="width:95px;float:right">
<tr><td class="letda2" style="background-color:#e6005a"></td><td class="letdb">950 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#dc0050"></td><td class="letdb">900 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#c8003c"></td><td class="letdb">850 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#a50b19"></td><td class="letdb">800 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">750 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">700 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">650 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">600 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">550 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">500 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">450 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">400 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">350 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">300 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">250 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">200 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">150 m&sup2;/s&sup2;</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">100 m&sup2;/s&sup2;</td></tr>
</table>

<table class="letable wrf_shear01 gfs_shear01" style="width:75px;float:right">
<tr><td class="letda2" style="background-color:#e6005a"></td><td class="letdb">46 m/s</td></tr>
<tr><td class="letda2" style="background-color:#dc0050"></td><td class="letdb">44 m/s</td></tr>
<tr><td class="letda2" style="background-color:#c8003c"></td><td class="letdb">42 m/s</td></tr>
<tr><td class="letda2" style="background-color:#a50b19"></td><td class="letdb">40 m/s</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">38 m/s</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">36 m/s</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">34 m/s</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">32 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">30 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">28 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">26 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">24 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">22 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">20 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">16 m/s</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">12 m/s</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">8 m/s</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">4 m/s</td></tr>
</table>

<table class="letable wrf_shear06 gfs_shear06" style="width:75px;float:right">
<tr><td class="letda2" style="background-color:#e6005a"></td><td class="letdb">30 m/s</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">28 m/s</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">26 m/s</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">24 m/s</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">22 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">20 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">18 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">16 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">14 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">12 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">10 m/s</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">8 m/s</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">6 m/s</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">4 m/s</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">2 m/s</td></tr>
</table>

<table class="letable wrf_tornado gfs_tornado" style="width:55px;float:right">
<tr><td class="letda2" style="background-color:#8C0A16"></td><td class="letdb">3.0</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">2.8</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">2.6</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">2.4</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">2.2</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">2.0</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">1.8</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">1.6</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">1.4</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">1.2</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">1.0</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">0.8</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">0.6</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">0.4</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">0.2</td></tr>
</table>

<table class="letable wrf_supercell gfs_supercell" style="width:55px;float:right">
<tr><td class="letda2" style="background-color:#8C0A16"></td><td class="letdb">15</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">14</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">13</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">12</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">11</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">10</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">9</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">8</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">7</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">6</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">5</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">4</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">3</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">2</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">1</td></tr>
</table>

<table class="letable wrf_hail" style="width:65px;float:right">
<tr><td class="letda2" style="background-color:#8C0A16"></td><td class="letdb">90%</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">80%</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">70%</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">60%</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">50%</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">40%</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">30%</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">20%</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">10%</td></tr>
</table>

<table class="letable le21" style="width:55px;float:right">
<tr><td class="letda2" style="background-color:#8C0A16"></td><td class="letdb">42</td></tr>
<tr><td class="letda2" style="background-color:#a50b19"></td><td class="letdb">40</td></tr>
<tr><td class="letda2" style="background-color:#ba130f"></td><td class="letdb">38</td></tr>
<tr><td class="letda2" style="background-color:#ce2007"></td><td class="letdb">36</td></tr>
<tr><td class="letda2" style="background-color:#dc2708"></td><td class="letdb">34</td></tr>
<tr><td class="letda2" style="background-color:#e83709"></td><td class="letdb">32</td></tr>
<tr><td class="letda2" style="background-color:#f4440b"></td><td class="letdb">30</td></tr>
<tr><td class="letda2" style="background-color:#f45f0b"></td><td class="letdb">28</td></tr>
<tr><td class="letda2" style="background-color:#f47a0b"></td><td class="letdb">26</td></tr>
<tr><td class="letda2" style="background-color:#f4950b"></td><td class="letdb">24</td></tr>
<tr><td class="letda2" style="background-color:#f4b00b"></td><td class="letdb">22</td></tr>
<tr><td class="letda2" style="background-color:#f4cb0b"></td><td class="letdb">20</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td><td class="letdb">18</td></tr>
<tr><td class="letda2" style="background-color:#ccff00"></td><td class="letdb">16</td></tr>
<tr><td class="letda2" style="background-color:#99ff00"></td><td class="letdb">14</td></tr>
<tr><td class="letda2" style="background-color:#99ff66"></td><td class="letdb">12</td></tr>
</table> 

<table class="letable wrf_snowd" style="width:85px;float:right;line-height:0.98">
<tr><td class="letda" style="background-color:#c00099"></td><td class="letdb">90+ cm</td></tr>
<tr><td class="letda" style="background-color:#800099"></td><td class="letdb">80-90 cm</td></tr>
<tr><td class="letda" style="background-color:#400099"></td><td class="letdb">70-80 cm</td></tr>
<tr><td class="letda" style="background-color:#000099"></td><td class="letdb">60-70 cm</td></tr>
<tr><td class="letda" style="background-color:#0000cc"></td><td class="letdb">50-60 cm</td></tr>
<tr><td class="letda" style="background-color:#0019ff"></td><td class="letdb">40-50 cm</td></tr>
<tr><td class="letda" style="background-color:#0059ff"></td><td class="letdb">30-40 cm</td></tr>
<tr><td class="letda" style="background-color:#0099ff"></td><td class="letdb">20-30 cm</td></tr>
<tr><td class="letda" style="background-color:#20b2ff"></td><td class="letdb">10-20 cm</td></tr>
<tr><td class="letda" style="background-color:#40ccff"></td><td class="letdb">1-10 cm</td></tr>
</table>

<table class="letable wrf_prectyp fmi_prectyp" style="width:120px;float:right">
<tr><td class="letda" style="background-color:#c00099;width:20px"></td><td class="letdb">'.FSNOW.'</td></tr>
<tr><td class="letda" style="background-color:rgb(75,205,46)"></td><td class="letdb">'.FSLEET.'</td></tr>
<tr><td class="letda" style="background-color:#0000cc"></td><td class="letdb">'.FRAIN.'</td></tr>
</table>

<table class="letable wrf_accum" style="width:95px;float:right">
<tr><td class="letda2" style="background-color:rgb(157,128,187)"></td><td class="letdb">&gt;120 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(186,166,208)"></td><td class="letdb">115 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(214,202,228)"></td><td class="letdb">110 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(252,227,227)"></td><td class="letdb">105 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(244,209,209)"></td><td class="letdb">100 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(222,161,161)"></td><td class="letdb">95 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,93,93)"></td><td class="letdb">90 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,0,0)"></td><td class="letdb">85 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(222,0,0)"></td><td class="letdb">80 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,0,0)"></td><td class="letdb">75 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,112,0)"></td><td class="letdb">70 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,186,0)"></td><td class="letdb">65 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(246,255,0)"></td><td class="letdb">60 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(170,253,166)"></td><td class="letdb">55 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(149,243,145)"></td><td class="letdb">50 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(120,230,115)"></td><td class="letdb">45 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(94,217,88)"></td><td class="letdb">45 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(70,206,63)"></td><td class="letdb">40 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(50,173,43)"></td><td class="letdb">35 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(45,30,165)"></td><td class="letdb">30 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(72,60,200)"></td><td class="letdb">25 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(30,110,235)"></td><td class="letdb">20 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(60,150,245)"></td><td class="letdb">15 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(120,185,250)"></td><td class="letdb">10 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(150,210,250)"></td><td class="letdb">5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(180,240,250)"></td><td class="letdb">0.1 mm</td></tr>
</table>

<table class="letable wrf_plcl gfs_plcl" style="width:90px;float:right">
<tr><td class="letda2" style="background-color:rgba(100,0,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(50,0,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,50,254,0.85)"></td><td rowspan="4" class="letdb2">960 hPa</td></tr>
<tr><td class="letda2" style="background-color:rgba(0,100,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,150,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,200,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,230,240,0.85)"></td><td rowspan="4" class="letdb2">880 hPa</td></tr>
<tr><td class="letda2" style="background-color:rgba(0,230,160,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,230,120,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,230,80,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,240,40,0.85)"></td><td rowspan="4" class="letdb2">800 hPa</td></tr>
<tr><td class="letda2" style="background-color:rgba(0,250,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,254,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,225,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,200,0,0.85)"></td><td rowspan="4" class="letdb2">720 hPa</td></tr>
<tr><td class="letda2" style="background-color:rgba(254,175,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,150,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(230,125,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(230,100,0,0.85)"></td><td rowspan="4" class="letdb2">640 hPa</td></tr>
<tr><td class="letda2" style="background-color:rgba(220,75,30,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(200,50,30,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(180,25,30,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(170,0,30,0.85)"></td><td rowspan="4" class="letdb2">560 hPa</td></tr>
<tr><td class="letda2" style="background-color:rgba(180,0,50,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(200,0,100,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,0,200,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,0,254,0.85)"></td>
</table>

<table class="letable wrf_hgt500 gfs_hgt500" style="width:90px;float:right">
<tr><td class="letda2" style="background-color:rgba(180,0,50,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(170,0,30,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(180,25,30,0.85)"></td><td rowspan="4" class="letdb2">584 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(200,50,30,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(220,75,30,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(230,100,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(230,125,0,0.85)"></td><td rowspan="4" class="letdb2">568 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(254,150,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,175,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,200,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(254,225,0,0.85)"></td><td rowspan="4" class="letdb2">552 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(254,254,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,250,0,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,240,40,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,230,80,0.85)"></td><td rowspan="4" class="letdb2">536 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(0,230,120,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,230,160,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,230,240,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,200,254,0.85)"></td><td rowspan="4" class="letdb2">520 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(0,150,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,100,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(0,50,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(50,0,254,0.85)"></td><td rowspan="4" class="letdb2">504 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(100,0,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(150,0,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(200,0,254,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(250,0,254,0.85)"></td><td rowspan="4" class="letdb2">488 gpdm</td></tr>
<tr><td class="letda2" style="background-color:rgba(200,0,200,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(100,0,100,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(50,0,50,0.85)"></td>
<tr><td class="letda2" style="background-color:rgba(25,0,25,0.85)"></td>
</table>

<table class="letable top_wrf_severe" style="width:380px;float:right">
<tr>
<td class="letda">'.LEV3.'</td>
<td class="letda" colspan="2">'.LEV2.'</td>
<td class="letda" colspan="2">'.TS.'</td>
</tr><tr>
<td class="letda2" style="background-color:rgb(245,41,209);width:20%;font-weight:bold;">15%</td>
<td class="letda2" style="background-color:rgb(191,0,0);width:20%;font-weight:bold;">15%</td>
<td class="letda2" style="background-color:rgb(255,112,0);width:20%;font-weight:bold;">5%</td>
<td class="letda2" style="background-color:rgb(255,255,0);width:20%;font-weight:bold;color:#222;">50%</td>
<td class="letda2" style="background-color:rgb(75,205,46);width:20%;font-weight:bold;">15%</td>
</table>
';

$ndailybody='
<table  style="width: 100%; height: 600px;margin-bottom:25px;">
<tr><td style="width:80%;" id="dailymapcontainer">
<div style="position: relative;">
<div class="shadow16" style="width:455px; height: 600px; z-index: 1;" id="dailymap"></div>
<div id="dlalegend"></div>
<div id="dlelegend"></div>
</div>
</td><td style="width:20%;vertical-align:top">
<h2>'.FDAYS.'</h2>
<div>
<input type="radio" class="dailydays" name="radiod1" id="today" data-typ="1" selected="selected"/>
<label for="today">'.TODAY.'</label><br/>
<input type="radio" class="dailydays" name="radiod1" id="tomoz" data-typ="2"/>
<label for="tomoz">'.TOMORROW.'</label><br/>
<input type="radio" class="dailydays" name="radiod1" id="dtomoz" data-typ="3"/>
<label for="dtomoz">'.DTOMORROW.'</label><br/>
</div>
<h2>'.FMAP.'</h2>
<div>
<input type="radio" class="dailymap" name="radiod2" id="dsevere" data-typ="severe" selected="selected"/>
<label for="dsevere">'.SEVRISK.'</label><br/>
<input type="radio" class="dailymap" name="radiod2" id="dmaxtemp" data-typ="maxtemp"/>
<label for="dmaxtemp">'.MAXI.' '.strtolower(FTEMP).'</label><br/>
<input type="radio" class="dailymap" name="radiod2" id="dmintemp" data-typ="mintemp"/>
<label for="dmintemp">'.MINI.' '.strtolower(FTEMP).'</label><br/>
<input type="radio" class="dailymap" name="radiod2" id="dmaxgust" data-typ="gust"/>
<label for="dmaxgust">'.FGUST.'</label><br/>
<input type="radio" class="dailymap" name="radiod2" id="dprecip" data-typ="precip"/>
<label for="dprecip">24h '.strtolower(FPREC).'</label><br/>
</div>
</td></tr></table>

<table class="letable wrf_dmaxtemp wrf_dmintemp" style="width:60px;float:right">
<tr><td class="letda2" style="background-color:rgb(223,6,84)"></td><td></td></tr>
<tr><td class="letda2" style="background-color:rgb(219, 9, 73)"></td><td></td></tr>
<tr><td class="letda2" style="background-color:rgb(215, 12, 62)"></td><td rowspan="5" class="letdb2">35&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(211, 15, 51)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(207, 18, 40)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(203, 21, 29)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(199, 24, 18)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(195, 28, 7)"></td><td rowspan="5" class="letdb2">30&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(207, 32, 7)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(211, 42, 8)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(215,52,8)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(219, 62, 9)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(223, 72, 9)"></td><td rowspan="5" class="letdb2">25&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(227,82,9)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(232, 92, 10)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(236, 102, 10)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(240, 112, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 122, 11)"></td><td rowspan="5" class="letdb2">20&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 144, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 152, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 160, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 168, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 176, 11)"></td><td rowspan="5" class="letdb2">15&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 184, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 192, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 200, 11)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(244, 208, 11)"></td></tr>
<tr><td class="letda2" style="background-color:#f4d90b"></td><td rowspan="5" class="letdb2">10&deg;</td></tr>
<tr><td class="letda2" style="background-color:#f4e60b"></td></tr>
<tr><td class="letda2" style="background-color:#ebe816"></td></tr>
<tr><td class="letda2" style="background-color:#e6eb20"></td></tr>
<tr><td class="letda2" style="background-color:#dcec2e"></td></tr>
<tr><td class="letda2" style="background-color:#d2ee3c"></td><td rowspan="5" class="letdb2">5&deg;</td></tr>
<tr><td class="letda2" style="background-color:#c8f04a"></td></tr>
<tr><td class="letda2" style="background-color:#bef158"></td></tr>
<tr><td class="letda2" style="background-color:#b4f266"></td></tr>
<tr><td class="letda2" style="background-color:#aff474"></td></tr>
<tr><td class="letda2" style="background-color:#aaf682"></td><td rowspan="5" class="letdb2">0&deg;</td></tr>
<tr><td class="letda2" style="background-color:#c7e4ff"></td></tr>
<tr><td class="letda2" style="background-color:#b5dcff"></td></tr>
<tr><td class="letda2" style="background-color:#a3d4ff"></td></tr>
<tr><td class="letda2" style="background-color:#9ad0ff"></td></tr>
<tr><td class="letda2" style="background-color:#91ccff"></td><td rowspan="5" class="letdb2">-5&deg;</td></tr>
<tr><td class="letda2" style="background-color:#7fc4ff"></td></tr>
<tr><td class="letda2" style="background-color:#6dbcff"></td></tr>
<tr><td class="letda2" style="background-color:#5bb4ff"></td></tr>
<tr><td class="letda2" style="background-color:#49acff"></td></tr>
<tr><td class="letda2" style="background-color:#259aff"></td><td rowspan="5" class="letdb2">-10&deg;</td></tr>
<tr><td class="letda2" style="background-color:#1392ff"></td></tr>
<tr><td class="letda2" style="background-color:#0082ef"></td></tr>
<tr><td class="letda2" style="background-color:#0072cf"></td></tr>
<tr><td class="letda2" style="background-color:#0062af"></td></tr>
<tr><td class="letda2" style="background-color:#00528f"></td><td rowspan="5" class="letdb2">-15&deg;</td></tr>
<tr><td class="letda2" style="background-color:#00467f"></td></tr>
<tr><td class="letda2" style="background-color:#003c7f"></td></tr>
<tr><td class="letda2" style="background-color:#00327f"></td></tr>
<tr><td class="letda2" style="background-color:#00287f"></td></tr>
<tr><td class="letda2" style="background-color:#001e7f"></td><td rowspan="5" class="letdb2">-20&deg;</td></tr>
<tr><td class="letda2" style="background-color:#00187f"></td></tr>
<tr><td class="letda2" style="background-color:#00007f"></td></tr>
<tr><td class="letda2" style="background-color:#0c007f"></td></tr>
<tr><td class="letda2" style="background-color:#19007f"></td></tr>
<tr><td class="letda2" style="background-color:#25007f"></td><td rowspan="5" class="letdb2">-25&deg;</td></tr>
<tr><td class="letda2" style="background-color:#32007f"></td></tr>
<tr><td class="letda2" style="background-color:#3e007f"></td></tr>
<tr><td class="letda2" style="background-color:#4b007f"></td></tr>
<tr><td class="letda2" style="background-color:#57007f"></td></tr>
<tr><td class="letda2" style="background-color:#64007f"></td><td rowspan="5" class="letdb2">-30&deg;</td></tr>
<tr><td class="letda2" style="background-color:#78048d"></td></tr>
<tr><td class="letda2" style="background-color:#870898"></td></tr>
<tr><td class="letda2" style="background-color:#960ca3"></td></tr>
<tr><td class="letda2" style="background-color:#a510ae"></td></tr>
<tr><td class="letda2" style="background-color:rgb(180,20,185)"></td><td rowspan="6" class="letdb2">-35&deg;</td></tr>
<tr><td class="letda2" style="background-color:rgb(182,18,160)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(185,16,150)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(188,14,140)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(191,13,130)"></td></tr>
<tr><td class="letda2" style="background-color:rgb(194,12,120)"></td></tr>
</table>

<table class="letable wrf_dwind" style="width:85px;float:right">
<tr><td class="letda2" style="background-color:#f529d1"></td><td class="letdb">'.$wspeeds[10].'> '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(153,0,0)"></td><td class="letdb">'.$wspeeds[9].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,0,0)"></td><td class="letdb">'.$wspeeds[8].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,0,0)"></td><td class="letdb">'.$wspeeds[7].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,112,0)"></td><td class="letdb">'.$wspeeds[6].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(238,172,22)"></td><td class="letdb">'.$wspeeds[5].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:#ffff00"></td><td class="letdb">'.$wspeeds[4].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(223,248,6)"></td><td class="letdb">'.$wspeeds[3].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(160,230,23)"></td><td class="letdb">'.$wspeeds[2].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(75,205,46)"></td><td class="letdb">'.$wspeeds[1].' '.$wunit.'</td></tr>
<tr><td class="letda2" style="background-color:rgb(0,153,0)"></td><td class="letdb">'.$wspeeds[0].' '.$wunit.'</td></tr>
</table>

<table class="letable top_wrf_dsevere" style="width:380px;float:right">
<tr>
<td class="letda">'.LEV3.'</td>
<td class="letda" colspan="2">'.LEV2.'</td>
<td class="letda" colspan="2">'.TS.'</td>
</tr><tr>
<td class="letda2" style="background-color:rgb(245,41,209);width:20%;font-weight:bold;">15%</td>
<td class="letda2" style="background-color:rgb(191,0,0);width:20%;font-weight:bold;">15%</td>
<td class="letda2" style="background-color:rgb(255,112,0);width:20%;font-weight:bold;">5%</td>
<td class="letda2" style="background-color:rgb(255,255,0);width:20%;font-weight:bold;color:#222;">50%</td>
<td class="letda2" style="background-color:rgb(75,205,46);width:20%;font-weight:bold;">15%</td>
</table>

<table class="letable wrf_dprecip" style="width:95px;float:right">
<tr>
<tr><td class="letda2" style="background-color:rgb(157,128,187)"></td><td class="letdb">&gt;120 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(186,166,208)"></td><td class="letdb">115 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(214,202,228)"></td><td class="letdb">110 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(252,227,227)"></td><td class="letdb">105 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(244,209,209)"></td><td class="letdb">100 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(222,161,161)"></td><td class="letdb">95 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,93,93)"></td><td class="letdb">90 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(191,0,0)"></td><td class="letdb">85 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(222,0,0)"></td><td class="letdb">80 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,0,0)"></td><td class="letdb">75 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,112,0)"></td><td class="letdb">70 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(255,186,0)"></td><td class="letdb">65 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(246,255,0)"></td><td class="letdb">60 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(170,253,166)"></td><td class="letdb">55 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(149,243,145)"></td><td class="letdb">50 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(120,230,115)"></td><td class="letdb">45 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(94,217,88)"></td><td class="letdb">45 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(70,206,63)"></td><td class="letdb">40 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(50,173,43)"></td><td class="letdb">35 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(45,30,165)"></td><td class="letdb">30 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(72,60,200)"></td><td class="letdb">25 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(30,110,235)"></td><td class="letdb">20 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(60,150,245)"></td><td class="letdb">15 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(120,185,250)"></td><td class="letdb">10 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(150,210,250)"></td><td class="letdb">5 mm</td></tr>
<tr><td class="letda2" style="background-color:rgb(180,240,250)"></td><td class="letdb">0.1 mm</td></tr>
</table>
';

if($map_only){$tpm="margin-top:10px;";}else{$tpm="";}
$nfrccreds='
<div style="'.$tpm.'padding:4px 0">
<span style="float:right"><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/"><img alt="Creative Commons -lisenssi" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" /></a></span>
&copy; '.date("Y").' <a href="http://www.europeanweathernetwork.eu" target="_blank">European Weathernetwork</a>.
Script by Henkka, <a href="http://www.nordicweather.net" target="_blank">nordicweather.net</a> with help from 
Oebel (<a href="http://www.weerstation-assen.nl" target="_blank">weerstation-assen.nl</a>), 
Henrik (<a href="http://www.silkeborg-vejret.dk" target="_blank">silkeborg-vejret.dk</a>), 
Alex (<a href="http://www.lokaltvader.se" target="_blank">lokaltvader.se</a>) and
Daniel (<a href="http://www.wetterdienst.de" target="_blank">wetterdienst.de</a>). 
Reusage without permission forbidden.<br/>
Except where otherwise noted, content on this page is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/">Creative Commons Attribution 3.0 License</a>.
</div>';

function frccurl($url) {
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120424 Firefox/12.0 PaleMoon/12.0');
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_TIMEOUT,20);
  curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
  $response = curl_exec($ch);
  if(strlen($response)<10){
    sleep(1);
    $response = curl_exec($ch);
  }
  curl_close($ch); 
  return $response;
}

function detectmobile() {
  # http://detectmobilebrowsers.com/
  $useragent=$_SERVER['HTTP_USER_AGENT'];
  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
    return true;
  } else {
    return false;
  }
}
?>