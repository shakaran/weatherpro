<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'meteohubDocs.php';
$pageVersion	= '2.00 2013-01-31';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# Meteohub docs
#---------------------------------------------------------------------------
#
# to do: connect with load meteohub variables
#---------------------------------------------------------------------------
?>
	<h3><?php langtrans('Meteohub Docs / Weather variables'); ?></h3> 
<p>The format of each variable name is as follows:<br />
&nbsp;&nbsp;&nbsp;&lt;<a href="#time"><b>timeframe</b></a>&gt;_&lt;<a href="#sensor"><b>sensor</b></a>
&gt;_&lt;<a href="#dimension"><b>dimension</b></a>&gt;[_&lt;<a href="#dimension"><b>unit</b></a>&gt;]</p>

&lt;<a id="time"></a><b>time frame</b>&gt;&nbsp;&nbsp;<b>= The reported sensor data was measured in this period</b><br />
    
<table class="docTable"  border="1">
<tr><th>timeframe</th><th>Description <span class="examp">Recalculation</span></th></tr>	
<tr>
<td>actual</td><td>Data last seen from the sensor</td></tr>
<tr><td>alltime</td><td>Not documented in the manual!<span class="examp">---------------</span></td></tr>
<tr><td>day1</td><td>Data of the actual day (including min/max values)<span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>hour1</td><td>Data of the actual hour (including min/max values)<span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>last15m</td><td>Data of the last 15 minutes (including min/max values)<br />Not documented in the manual! <span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>last24h</td><td>Data of the last 24 hours (including min/max values)<span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>last60m</td><td>Data of the last 60 minutes (including min/max values)<span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>month1</td><td>Data of the actual month (including min/max values)<span class="examp">Updated  every 6 hours (00:13/06:13/12:13/18:13)</span></td></tr>
<tr><td>year1</td><td>Data of the actual year (including min/max values)<span class="examp">Updated  every day (04:47)</span></td></tr>
<tr><td>seq????</td><td>List of data that are used by WD Live to generate weather graphs<br />Always starts with the last completed timeframe 
e.g. seqday1 starts yesterday (and NOT today)<br />
The number of measurments varies per sensor_dimension, therefore a table is icluded <a href="#seq">here.</a>
</td></tr>
</table>
<br />
<a href="#header"><img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;"></a><br /><br />
There are two kind of sensors:
<ul>
<li>Normal weather sensors like th4 (= thermo hygro sensor number 4) which are described below.
</li><li>Sensors which contain systemwide values like units for temperature, date-formats, sunrise and moonset etc.
</li></ul>
&lt;<a id="sensor"></a><b>Sensor</b>&gt;&nbsp;&nbsp;<b>Weathersensor information</b><br />
<table class="docTable" border="1">
<tr><th>sensor</th><th>Description</th></tr>
<tr><td>wind#</td><td>Data of wind sensor with id #</td></tr>
<tr><td>rain#</td><td>Data of rain sensor with id #</td></tr>
<tr><td>thb#</td><td>Data of thermo/hygro/baro sensor with id #</td></tr>
<tr><td>th#</td><td>Data of thermo/hygro sensor with id #</td></tr>
<tr><td>t#</td><td>Data of thermo sensor with id #</td></tr>
<tr><td>uv#</td><td>Data of uv sensor with id #</td></tr>
<tr><td>sol#</td><td>Data of solar radiation sensor with id #</td></tr>
</table>
<br />
<a href="#header"><img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;"></a>
<br /><br />
&lt;<a id="dimension"><b>Dimension</b></a>&gt;&nbsp;&nbsp;For each sensor only a subset of dimensions is available.
<table class="docTable"  border="1">
<tr><th>timeframe</th><th>sensor</th><th>dimension</th><th>unit</th><th>explanation <span class="examp">Example</span></th></tr>
<tr><td rowspan="49">day1<br />hour1<br />last15m<br />last24h<br />last60m<br />month1<br />year1<br />alltime</td>
    <td colspan="4"><h3>temperature only </h3></td></tr>
<tr><td rowspan="4">t#<br />th#<br />thb#</td>
    <td rowspan="4">temp<br />tempmin<br />tempmax</td>
    <td>c</td><td>celcius<span class="examp">month1_thb0_temp_c 19.0</span></td></tr>
<tr><td>f</td><td>fahrenheit<span class="examp">year1_th1_dew_f 46.2</span></td></tr>
<tr><td>trend</td><td>+ / - value representing change in observed timeframe (not for min max values)<span class="examp">month1_th0_heatindex_trend -1</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last15m_th1_dewmax_time 20120105093606</span></td></tr>
<tr><td colspan="4"><h3> humidity only </h3></td></tr>
<tr><td rowspan="3">th#<br />thb#</td>
    <td rowspan="3">hum<br />hummin<br />hummax</td>
    <td>rel</td><td>relative humidity in percent<span class="examp">last15m_th0_hum_rel 82.9</span></td></tr>
<tr><td>trend</td><td>+ / - value representing change in observed timeframe (not for min max values)<span class="examp">last15m_thb0_hum_trend 0</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last15m_thb0_hummax_time 20120105093540</span></td></tr>
<tr><td colspan="4"><h3> temperature and humidity </h3></td></tr>
<tr><td rowspan="4">th#<br />thb#</td>
    <td rowspan="4">dew<br />dewmin<br />dewmax<br /><br />heatindex<br />heatindexmin<br />heatindexmax<br /><br />humidex<br />humidexmin<br />humidexmax</td>
    <td>c</td><td>celcius<span class="examp">month1_thb0_temp_c 19.0</span></td></tr>
<tr><td>f</td><td>fahrenheit<span class="examp">year1_th1_dew_f 46.2</span></td></tr>
<tr><td>trend</td><td>+ / - value representing change in observed timeframe (not for min max values)<span class="examp">month1_th0_heatindex_trend -1</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last15m_th1_dewmax_time 20120105093606</span></td></tr>
<tr><td colspan="4"><h3> pressure </h3></td></tr>
<tr><td rowspan="6">thb#</td>
    <td rowspan="6">press<br />pressmin<br />pressmax<br /><br />sealevel<br />sealevelmin<br />sealevelmax</td>
    <td>hpa</td><td>hecto pascal (equal to millibar)<span class="examp">day1_thb0_press_hpa 997.3</span></td></tr>
<tr><td>psi</td><td>pound per square inch<span class="examp">day1_thb0_press_psi 14.46</span></td></tr>
<tr><td>mmhg</td><td>millimeter of mercury<span class="examp">day1_thb0_press_mmhg 748.0</span></td></tr>
<tr><td>inhg</td><td>inch of mercury<span class="examp">day1_thb0_press_inhg 29.45</span></td></tr>
<tr><td>trend</td><td>+ / - value representing change in observed timeframe (not for min max values)<span class="examp">day1_thb0_press_trend -1</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last24h_thb0_sealevelmax_time 20120104114340</span></td></tr>
<tr><td colspan="4"><h3> rain </h3></td></tr>
<tr><td rowspan="4">rain#</td>
    <td rowspan="3">total<br /><br />rate<br />ratemin<br />ratemax</td>
    <td>mm</td><td>millimeter<span class="examp">month1_rain0_total_mm 22.40</span></td></tr>
<tr><td>in</td><td>inch<span class="examp">month1_rain0_ratemax_mm 29.8</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">month1_rain0_ratemax_time 20120102052257</span></td></tr>
<tr><td rowspan="1">days</td>
    <td>&nbsp;</td><td>number of days with rain (no unit-name)<span class="examp">month1_rain0_days 5</span></td></tr>
<tr><td colspan="4"><h3> wind </h3></td></tr>
<tr><td rowspan="9">wind#</td>
    <td rowspan="7">speed<br />speedmin<br />speedmax<br /><br />gustspeed<br />gustspeedmin<br />gustspeedmax</td>
    <td>ms</td><td>meters per second<span class="examp">month1_wind0_speedmax_ms 4.9</span></td></tr>
<tr><td>kmh</td><td>kilometers per hour<span class="examp">month1_wind0_speedmax_kmh 17.6</span></td></tr>
<tr><td>mph</td><td>miles per hour<span class="examp">month1_wind0_speedmax_mph 11.0</span></td></tr>
<tr><td>kn</td><td>knots<span class="examp">month1_wind0_speedmax_kn 9.5</span></td></tr>
<tr><td>bft</td><td>Beaufort<span class="examp">month1_wind0_speedmax_bft 3.3</span></td></tr>
<tr><td>bftint</td><td>Beaufort number<span class="examp">month1_wind0_speedmax_bftint 3</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">month1_wind0_gustspeedmin_time 20120101004455</span></td></tr>
<tr><td rowspan="2">maxspeeddir<br />maindir</td>
    <td>deg</td><td>wind direction in degrees<span class="examp">month1_wind0_maindir_deg 225.0</span></td></tr>
<tr><td>(ll)</td><td>direction text in different languages<br />en English  de German  nl Dutch<span class="examp">year1_wind0_maxspeeddir_nl WZW</span></td></tr>
<tr><td colspan="4"><h3> windchill </h3></td></tr>
<tr><td rowspan="3">wind#</td>
    <td rowspan="3">chill<br />chillmin<br />chillmax</td>
    <td>c</td><td>celcius<span class="examp">last15m_wind0_chill_c 9.2</span></td></tr>
<tr><td>f</td><td>fahrenheit<span class="examp">last15m_wind0_chillmin_f 45.7</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last15m_wind0_chillmax_time 20120105093624</span></td></tr>
<tr><td colspan="4"><h3> uv </h3></td></tr>
<tr><td rowspan="2">uv#</td>
    <td rowspan="2">index<br />indexmin<br />indexmax</td>
    <td>&nbsp;</td><td>(no unit-name)<span class="examp">last24h_uv0_index 0.1</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last24h_uv0_indexmax_time 20120104120310</span></td></tr>
<tr><td colspan="4"><h3> solar </h3></td></tr>
<tr><td rowspan="5">sol#</td>
    <td rowspan="2">radiation<br />radiationmin<br />radiationmax</td>
    <td>wqm</td><td>solar radiation values in watts per square meter<span class="examp">last24h_sol0_radiation_wqm 39.4</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last24h_sol0_radiationmax_time 20120104120352</span></td></tr>
<tr><td rowspan="3">et<br />etmin<br />etmax</td>
    <td>mm</td><td>Evapotranspiration in millimeter<span class="examp">last24h_sol0_et_mm 3.4</span></td></tr>
<tr><td>in</td><td>Evapotranspiration in inch<span class="examp">last24h_sol0_etmin_in 0.0</span></td></tr>
<tr><td>time</td><td>datetime  this value was measured (only for min max values)<span class="examp">last24h_sol0_etmax_time 20120104230134</span></td></tr>
</table>
<br />
<a href="#header"><img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;"></a>
<br /><br />
<a id="seq"><b>seq&lt;timeframe&gt;</b></a>&nbsp;Values are recorded with different intervals.
<br />
<table class="docTable"  border="1">
<tr><th>timeframe</th><th>Description <span class="examp">Recalculation</span></th></tr>
<tr><td>seqday1</td>
<td>Last 15 days in steps of 1 day(yesterday .. today -23 days)<span class="examp">Updated  every 6 hours (00:13/06:13/12:13/18:13)</span></td></tr>
<tr><td>seqhour1</td>
<td>Last 25 hours in steps of 1 hour(now -1hr .. now -25hrs)<span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>seqhour6</td>
<td>6 hour interval, total 23 steps (now .. now -~138 hrs)<span class="examp">Updated  every 6 hours (00:13/06:13/12:13/18:13)</span></td></tr>
<tr><td>seqmin1</td>
<td>Last 60 mins in steps of 1 minute (now -1min .. now -60 minutes)<span class="examp">Updated  every minute</span></td></tr>
<tr><td>seqmin15</td>
<td>Last 23.5 hours in steps of 15 minutes hour(now -15mins .. now -23.5 hours)<span class="examp">Updated  every 5 minutes</span></td></tr>
<tr><td>seqmonth1</td>
<td>Last 14 months in steps of 1 month(last month .. today -14 months)<span class="examp">Updated  every day (04:47)</span></td></tr>				
</table>
<br />
<b>seq&lt;timeframe&gt;_&lt;dimension&gt;</b>&nbsp;Tne number of values recorded varies per sensor-dimension!.
<br />
<table class="docTable"  border="1">
<tr><th colspan="3">seq&lt;timeframe&gt;_&lt;sensor&gt;_&lt;dimension&gt;_&lt;unit&gt;</th><th colspan="6">Number of values for this timeframe</th></tr>
<tr><th>Sensor</th><th>Dimension</th><th>Unit</th>
    <th>day1</th><th>hour1</th><th>hour6</th><th>min15</th><th>min1</th><th>month1</th></tr>
<tr><td colspan="3">_utcdate</td><td rowspan="2">24</td><td rowspan="2">24</td><td rowspan="2">24</td><td rowspan="2">1</td><td rowspan="2">1</td><td rowspan="2">1</td></tr>
<tr><td colspan="3">_localdate </td></tr>
<tr><td rowspan="4">_wind#</td>
    <td>_gustspeed </td><td>_kn</td><td rowspan="18">40</td><td rowspan="18">26</td><td rowspan="18">30</td><td rowspan="18">93</td><td rowspan="4">59</td><td rowspan="18">(1)</td></tr>
<tr><td>_speed </td><td>_kn</td></tr>
<tr><td>_maindir </td><td>_deg</td></tr>
<tr><td>_chill </td><td>_c</td></tr>
<tr><td rowspan="3">_rain#</td>
    <td>_rate</td><td>_mm</td><td rowspan="3">57<br /><br />(3)</td></tr>
<tr><td>_total</td><td>_mm</td></tr>
<tr><td>_totalcumul&nbsp;&nbsp;&nbsp;(2)</td><td>_mm</td></tr>
<tr><td rowspan="3">_thb#<br />_th# <br />_t#</td>
    <td>_temp </td><td>_c</td><td rowspan="11">58</td></tr>
<tr><td>_tempmin </td><td>_c</td></tr>
<tr><td>_tempmax </td><td>_c</td></tr>
<tr><td rowspan="4">_thb#<br />_th#</td>
    <td>_dew </td><td>_c</td></tr>
<tr><td>_hum </td><td>_rel</td></tr>
<tr><td>_heatindex </td><td>_c</td></tr>
<tr><td>_humidex </td><td>_c</td></tr>
<tr><td rowspan="2">_thb#</td>
    <td>_press </td><td>_hpa</td></tr>
<tr><td>_sealevel </td><td>_hpa</td></tr>
<tr><td rowspan="1">_uv#</td>
    <td colspan="2">_index </td></tr>
<tr><td rowspan="1">_sol#</td>
    <td>_radiation </td><td>_wqm</td></tr>
<tr><td colspan="9">
Legenda:<br />
(1) = number of months meteohub is operational with a maximum of ?? months.<br />
(2) = values for _rain_total_cumul same as in _rain_total<br />
(3) = values always zero for seqmin1_rain# (one minute rain), also one zero value not supplied (only a -)
</td></tr>
</table>
<br />
<a href="#header"><img src="ajaxImages/toparrow.gif" alt=" " style="border: 0px;"></a>
<br /><br />
<b>Credits:</b><br />Essential parts of this information are  generously provided by <a href="http://weather.westerkerkweg.nl/wxmeteohub.php">Ysbrand</a><br />
The manual (version 4.7 is the most recent) of meteohub can be found at <a href="http://wiki.meteohub.de/Documentation">the meteohub wiki</a><br />
The <a href="http://forum.meteohub.de/viewforum.php?f=8">forum</a> also contains valuble info.