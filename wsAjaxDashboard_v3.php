<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsAjaxDashboard_v3.php';
$pageVersion	= '3.20 2015-09-29';
#-------------------------------------------------------------------------------
# 3.20 2015-09-29 release 2.8 version plus check for moonrise-set == '', translate 'more information'
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
$more_info = langtransstr('more information');
?>
<!--  dashboard -->
<div class="blockDiv">
	<div class="colLeft">
	<h1 class="ajaxHead"><?php echo langtransstr('Temperature');?></h1>
	<table><tr><td>
	<span class="ajax" id="ajaxconditionicon"><?php echo $vars['ajaxconditionicon'];?></span></td><td>
	<span class="ajax" id="ajaxcurrentcond"><?php echo $vars['ajaxcurrentcond']; ?> </span></td>
	</tr> <!-- today current cond icon and text -->
	<tr><td>
	<span class="ajax" id="ajaxthermometer"><?php echo $vars['ajaxthermometer']; ?></span> <!-- thermometer graphic --></td><td>
	<div class=""> <!-- text next to thermometer -->
		<span class="ajax" id="ajaxtempDash" style=""><?php echo $vars['ajaxtempDash'];?></span>&nbsp;
		<span class="ajax" id="ajaxtemparrow"><?php echo $vars['ajaxtemparrow']; ?></span>
		<br /><br />
		<span class="ajax" id="ajaxheatcolorword"><?php echo $vars['ajaxheatcolorword'];?></span>
		<br /><br />
		<?php echo langtransstr('Feels like'); ?>:
		<span class="ajax" id="ajaxfeelslike"><?php echo $vars['ajaxfeelslike']; ?></span>
<?php   if   (ws_check_setting($SITE['tempInside'])  ){ ?>
	        <br />
	        <span ><?php echo langtransstr("Inside").": ".  wsNumber($ws['tempActInside'],$decTemp).$uomTemp;?></span>
<?php } if  (ws_check_setting($SITE['extraTemp1'])  ){ ?>	
	        <br />
	        <span><?php echo langtransstr("Pool").": ".     wsNumber($ws['tempActExtra1'],$decTemp).$uomTemp; ?></span>
<?php } if  (ws_check_setting($SITE['extraTemp2'])  ){ ?>	
	        <br />
	        <span><?php echo langtransstr("extra2").": ".   wsNumber($ws['tempActExtra2'],$decTemp).$uomTemp; ?></span>
<?php } if  (ws_check_setting($SITE['extraTemp3'])  ){ ?>	
	        <br />
	        <span><?php echo langtransstr("extra3").": ".   wsNumber($ws['tempActExtra3'],$decTemp).$uomTemp; ?></span>
<?php } ?>				
	</div> <!-- text next to thermometer -->
	</td></tr>
	</table>	
<?php 
        echo '
<!-- today yesterday min max values -->	
<table class="dashTemps" style="width: 100%;">
<tbody>
<tr style="height: 18px;">   
        <th>&nbsp;</th>
        <th class="ajaxHead">'.langtransstr('High').'</th>
        <th class="ajaxHead">'.langtransstr('Low').'</th>
</tr>
<tr>    <td class="dashTempsDesc">'.langtransstr('Today').':</td>
	<td class="dashTempsHigh"><span class="ajax" id="ajaxtempmax">'.$vars['ajaxtempmax'].'</span>&nbsp;<small>(<span class="ajax" id="ajaxtempmaxTime">'.$vars['ajaxtempmaxTime'].'</span>)</small></td>
	<td class="dashTempsLow"><span class="ajax" id="ajaxtempmin">'. $vars['ajaxtempmin'].'</span>&nbsp;<small>(<span class="ajax" id="ajaxtempminTime">'.$vars['ajaxtempminTime'].'</span>)</small></td>
</tr>
<tr>    <td class="dashTempsDesc">'.langtransstr('Yesterday').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['tempMaxYday'],$decTemp).$uomTemp;
	if (isset($ws['tempMaxYdayTime']) ) {echo '&nbsp;<small>('.string_date($ws['tempMaxYdayTime'],$SITE['timeOnlyFormat']).')</small>';} 
        echo '
	</td>
	<td class="dashTempsLow">'. wsNumber($ws['tempMinYday'],$decTemp).$uomTemp;
	if (isset($ws['tempMinYdayTime']) ) {echo '&nbsp;<small>('.string_date($ws['tempMinYdayTime'],$SITE['timeOnlyFormat']).')</small>';} 
	echo '
</tr>
<tr>    <td class="dashTempsDesc">'.langtransstr('Month').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['tempMaxMonth'],$decTemp).$uomTemp;
	if (isset($ws['tempMaxMonthTime']) ) {echo '&nbsp;<small>('.string_date($ws['tempMaxMonthTime'],$SITE['dateMDFormat']).')</small>';} 
	echo '
	</td>
	<td class="dashTempsLow">'. wsNumber($ws['tempMinMonth'],$decTemp).$uomTemp;
	if (isset($ws['tempMinMonthTime']) ) {echo '&nbsp;<small>('.string_date($ws['tempMinMonthTime'],$SITE['dateMDFormat']).')</small>';} 	
	echo '	
	</td>
</tr>
<tr>    <td class="dashTempsDesc">'.langtransstr('Year').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['tempMaxYear'],$decTemp). $uomTemp;
	if (isset($ws['tempMaxYearTime']) ) {echo '&nbsp;<small>('.string_date($ws['tempMaxYearTime'],$SITE['dateMDFormat']).')</small>';} 	
	echo '	
	</td>
	<td class="dashTempsLow">'. wsNumber($ws['tempMinYear'],$decTemp). $uomTemp;
	if (isset($ws['tempMinYearTime']) ) {echo '&nbsp;<small>('.string_date($ws['tempMinYearTime'],$SITE['dateMDFormat']).')</small>';} 		
	echo '
	</td>
</tr>';
#
        if (isset ($ws['last_year_low']) && isset ($ws['last_year_high'])){ 
                echo '
<tr>    <td class="dashTempsDesc">'.langtransstr('Last year').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['last_year_high'],$decTemp).$uomTemp;
	        if (isset($ws['last_year_highT']) ) {echo '&nbsp;<small>('.string_date($ws['last_year_highT'],$SITE['timeOnlyFormat']).')</small>';} 
	        echo '</td>
	<td class="dashTempsLow">'.wsNumber($ws['last_year_low'],$decTemp).$uomTemp;
	        if (isset($ws['last_year_lowT']) ) {echo '&nbsp;<small>('.string_date($ws['last_year_lowT'],$SITE['timeOnlyFormat']).')</small>';}
	        echo '</td>
</tr>';
        } // eo lastyear

#
        if (isset ($ws['tempMaxRecordToday']) && isset ($ws['tempMinRecordToday'])){ 
                echo '
<tr>    <td class="dashTempsDesc">'.langtransstr('Station record').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['tempMaxRecordToday'],$decTemp).$uomTemp;
	        if (isset($ws['tempMaxRecordTodayYear']) ) {echo '&nbsp;<small>('.$ws['tempMaxRecordTodayYear'].')</small>';} 
	        echo '</td>
	<td class="dashTempsLow">'.wsNumber($ws['tempMinRecordToday'],$decTemp).$uomTemp;
	        if (isset($ws['tempMinRecordTodayYear']) ) {echo '&nbsp;<small>('.$ws['tempMinRecordTodayYear'].')</small>';}
	        echo '</td>
</tr>';
        } // eo tempMax Min RecordToday
        if (isset ($ws['record_high']) ) {
                echo '
<tr>    <td class="dashTempsDesc">'.langtransstr('Records/today').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['record_high'],$decTemp).$uomTemp.'&nbsp;<small>('.$ws['record_high_year'].')</small></td>
	<td class="dashTempsLow">'. wsNumber($ws['record_low'],$decTemp). $uomTemp.'&nbsp;<small>('.$ws['record_low_year'].')</small></td>
</tr>';
        } // eo record_high/low
        if (isset ($ws['normal_high']) ) {
                echo '
<tr>    <td class="dashTempsDesc">'.langtransstr('Normals/today').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['normal_high'],$decTemp).$uomTemp.'</td>
	<td class="dashTempsLow">'. wsNumber($ws['normal_low'],$decTemp). $uomTemp.'</td>
</tr>';
        } // eo normal high / low
        elseif ($SITE['region'] == 'canada') {
                if (!isset ($ecForecast) ) {
                        $script	= './canada/ec_settings.php';
                        ws_message (  '<!-- module wsAjaxDashboard_v3.php ('.__LINE__.'): loading '.$script.' -->');
                        include($script);
                        $script	= './canada/ec_fct_create_arr.php';
                        ws_message (  '<!-- module wsAjaxDashboard_v3.php ('.__LINE__.'): loading '.$script.' -->');
                        echo '<!-- trying to load '.$script.' -->'.PHP_EOL;
                        include_once($script);
                        $weather 	= new ecPlainWeather ();
                        $ecForecast 	= $weather->getWeatherData($SITE['caProvince'],$SITE['caCityCode']);
                }
                $ws['normal_high']   = $ecForecast['information']['normalTempMax'];
                $ws['normal_low']    = $ecForecast['information']['normalTempMin'];
                echo '
<tr>    <td class="dashTempsDesc">'.langtransstr('Normals').':</td>
	<td class="dashTempsHigh">'.wsNumber($ws['normal_high'],$decTemp).$uomTemp.'</td>
	<td class="dashTempsLow">'. wsNumber($ws['normal_low'],$decTemp). $uomTemp.'</td>
</tr>';
        }  // eo normal high low => canada 
        echo '
</tbody>
</table>
<!-- today yesterday min max values -->'.PHP_EOL;
?>
        </div> 
<!-- col-temp -->	
	<div class="colRight">	
	<h3 class="ajaxHead"><?php echo langtransstr("Rain");?></h3>
<?php $unit = ' '.langtransstr($SITE['uomRain']);?>
	<p><span class="lTxt"><?php echo langtransstr("Rain Today").":";?></span><span class="rTxt"><span  class="ajax" id="ajaxrain"><?php echo $vars['ajaxrain']?></span></span></p>
	<hr />
	<p><span class="lTxt"><?php echo langtransstr("Rain Rate").langtransstr($uomPerHour).":";?></span><span class="rTxt"><span class="ajax" id="ajaxrainratehr"><?php echo $vars['ajaxrainratehr']?></span></span></p>
	<hr />
	<p><span class="lTxt"><?php echo langtransstr("Yesterday").":";?></span><span class="rTxt"><?php echo wsNumber($ws['rainYday'],$decPrecip).$unit?></span></p>
	<hr />
	<p><span class="lTxt"><?php echo langtransstr("This Month").":";?></span><span class="rTxt"><span class="ajax" id="ajaxrainmo"><?php echo $vars['ajaxrainmo']?></span></span></p>
	<hr />
	<p><span class="lTxt"><?php echo langtransstr("Season Total").":";?></span><span class="rTxt"><span class="ajax" id="ajaxrainyr"><?php echo $vars['ajaxrainyr']?></span></span></p>	
	<hr />
	<?php if (isset ($ws['rainDayMnth']) && !$ws['rainDayMnth'] == false && !$ws['rainDayMnth'] == 'n/a') { ?>
	<p><span class="lTxt"><?php echo $ws['rainDayMnth']." " . langtransstr('days with rain this month'); ?></span><span class="rTxt">&nbsp;</span></p>
	<?php } ?>
	</div> <!-- col-rain -->
	<div class="colMiddle">
	<h3 class="ajaxHead"><?php echo langtransstr("Wind");?></h3>
	<span class="ajax" id="ajaxwindiconwr"><?php echo $vars['ajaxwindiconwr']; ?></span>
	<div style="text-align: right; padding: 5px;">
	<p>	<span class="ajax" id="ajaxwinddir"><?php echo $vars['ajaxwinddir']; ?></span><br />
		<span class="ajax" id="ajaxwind"><?php echo $vars['ajaxwind']; ?></span>
	</p>
	<p>	<span class="ajax" id="ajaxbeaufortnum"><?php echo $vars['ajaxbeaufortnum']; ?></span> Bft
		<br /> 
		<span class="ajax" id="ajaxbeaufort"><?php echo $vars['ajaxbeaufort']; ?> </span>
	</p>
	<br />	
	<p>	<span><?php echo langtransstr('Gust'); ?>: <br /></span> 
		<span class="ajax" id="ajaxgust"><?php echo $vars['ajaxgust']; ?></span>
	</p>	

	<p>	<br /><span><?php echo langtransstr('Gust today'); ?>: </span><br />
		<span class="ajax" id="ajaxwindmaxgust"><?php echo $vars['ajaxwindmaxgust']; ?></span>
		<span class="ajax" id="ajaxwindmaxgusttime"> <?php echo $vars['ajaxwindmaxgusttime']; ?></span>
		</p>
	</div>
	</div> <!-- col-wind -->
	<hr style="border: 0px; clear: right;"/>  <!-- just reallign cols -->
	<div class="colRight">	
	<h3 class="ajaxHead"><?php echo langtransstr("Almanac");?></h3>
	<p>
	<span class="lTxt"><?php echo langtransstr("Sun&nbsp;").":&nbsp;&nbsp;";?></span>
	<span class="rTxt"><small><img src="img/sunrise.png" alt="sunrise"/>&nbsp;&nbsp;<?php echo $sunrise?>
	<img src="img/sunset.png" alt="sunset"/>&nbsp;&nbsp;<?php echo $sunset?></small></span>
	</p>
	<p class="mTxt"><?php echo $ws['daylight']; ?></p>
	<hr />
	<p>
	<span class="lTxt"><?php echo langtransstr("Moon").":&nbsp;";?></span>
	<span class="rTxt"><small><img src="img/moonrise.png" alt="moonrise"/>&nbsp;
<?php 	if ($ws['moonrise'] == '0' || $ws['moonrise'] == '') {
		 $ws['moonrise'] = langtransstr('&lt; 00:00');
	} 
	echo $ws['moonrise']; ?>
	<img src="img/moonset.png" alt="moonset"/>&nbsp;
<?php 	if ($ws['moonset'] == '0' || $ws['moonset'] == '') { 
		$ws['moonset'] = langtransstr('&gt; 24:00');
	}
	echo $ws['moonset']; ?></small></span>
	</p>

	<p class="mTxt">
	<?php echo langtransstr(wsmoonWord ($ws['lunarPhasePerc'] , $ws['lunarAge']));?>,&nbsp;
	<?php echo wsNumber ($ws['lunarPhasePerc'],0).'%&nbsp;'.langtransstr('Illuminated'); ?>
	</p>
	</div> <!-- col-almanac -->
	<div class="colMiddle" >
	<h3 class="ajaxHead"><?php echo langtransstr("Humidity"); ?> &amp; <?php echo langtransstr("Barometer");?></h3>
	<p>
	<span class="lTxt"><?php echo langtransstr("Humidity").":";?></span>
	<span class="rTxt"><span class="ajax" id="ajaxhumidityoarrow"><?php echo $vars['ajaxhumidityarrow'];?></span></span>
	<span class="rTxt"><span class="ajax" id="ajaxhumidity"><?php echo $vars['ajaxhumidity'];?></span></span>
	</p>
	<hr style="width: 100%;" />
	<p>
	<span class="lTxt"><?php echo langtransstr("Dew Point").":";?></span>
	<span class="rTxt"><span class="ajax" id="ajaxdewarrow"><?php echo $vars['ajaxdewarrow']; ?></span></span>
	<span class="rTxt"><span class="ajax" id="ajaxdew"><?php echo $vars['ajaxdew'];?></span></span>
	</p>	
	<hr style="width: 100%;" />
	<p>
	<span class="lTxt"><?php echo langtransstr("Barometer").":";?></span>
	<span class="rTxt"><span class="ajax" id="ajaxbaroarrow"><?php echo $vars['ajaxbaroarrow']; ?></span></span>
	<span class="rTxt"><span class="ajax" id="ajaxbaro"> <?php echo $vars['ajaxbaro'];?></span></span>
	</p>
	<hr style="width: 100%;" />
	<p>
	<span class="lTxt"><?php echo langtransstr("Trend").":";?></span>
	<span class="rTxt"><span class="ajax" id="ajaxbarotrendtext"><?php echo $vars['ajaxbarotrendtext']; ?></span></span>
	</p>	
	</div> <!-- col-humidity -->
	<hr style="border: 0px; clear: right;"/>  <!-- just reallign cols -->
	<div class="colRight">
<?php
if (!$SITE['SOLAR']) { 
	echo '	<h3 class="ajaxHead">'.langtransstr('UV Index Forecast').
	'&nbsp;<a href="'.$SITE['pages']['printUV'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="'.$more_info.'" title="'.$more_info.'"></a>'.'</h3>'.PHP_EOL;
        $skipUVhtml     = true;
        $script	= $SITE['uvScript'];
        ws_message (  '<!-- module wsAjaxDashboard_v3.php ('.__LINE__.'): loading '.$script.' -->');
        include_once ($script);
	if (isset ($uvarray[1]) ){
	        echo '<p class="lTxt" style="padding: 15px;">'.$uvarray[1]['img'].'</p>';
		echo '	<p class="mTxt">';
		echo langtransstr(date('l',$uvarray[1]['unixtime']));
		echo '</p>'.PHP_EOL.'<hr />'.PHP_EOL;
		echo '	<p class="mTxt">';
		echo '<b>'. $uvarray[1]['uv'].'</b>&nbsp;&nbsp;'.wsGetUVword($uvarray[1]['uv']);
		echo '</p>'.PHP_EOL;	
	}
} else { 
?>
	<h3 class="ajaxHead"><?php echo langtransstr('Solar Radiation');?></h3>
	<p class="mTxt">
	<span class="ajax" id="ajaxsolar"><?php echo $vars['ajaxsolar']; ?></span> W/m<sup>2</sup>
<?php if(isset($vars['ajaxsolarpct']) && $vars['ajaxsolarpct'] >= 0) { // display only if data available 
?> (<span class="ajax" id="ajaxsolarpct"><?php echo $vars['ajaxsolarpct']; ?></span>%)
<?php } // end of $currentsolarpercent ?>
	</p>
	<hr />
	<p class="mTxt"><?php echo langtransstr('High:')?>
	  <span class="ajax" id="ajaxsolarmax"><?php echo $vars['ajaxsolarmax'];?></span>&nbsp;@&nbsp;
	  <span class="ajax" id="ajaxsolarmaxtime"><?php echo $vars['ajaxsolarmaxtime']; ?></span>
	</p>
<?php 
} ?>
	</div> <!-- col-solar -->
	<div class="colMiddle">
<?php 
if (!$SITE['UV']) { 
	echo '	<h3 class="ajaxHead">'.langtransstr('UV Index Forecast').
	'&nbsp;<a href="'.$SITE['pages']['printUV'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="'.$more_info.'" title="'.$more_info.'"></a>'.'</h3>'.PHP_EOL;
        $skipUVhtml     = true;
        $script	= $SITE['uvScript'];
        ws_message (  '<!-- module wsAjaxDashboard_v3.php ('.__LINE__.'): loading '.$script.' -->');
        include_once ($script);
	if (isset ($uvarray[0]) ){
	        echo '  <p class="rTxt" style="padding: 15px;">'.$uvarray[0]['img'].'</p>'.PHP_EOL;
		echo '	<p class="mTxt" >';
		echo langtransstr(date('l',$uvarray[0]['unixtime']));
		echo '</p>'.PHP_EOL.'<hr />'.PHP_EOL;
		echo '	<p class="mTxt">';
		echo '<b>'. $uvarray[0]['uv'].'</b>&nbsp;&nbsp;'.wsGetUVword($uvarray[0]['uv']);
		echo '</p>'.PHP_EOL;	
	}
} else { 
        if (!isset ($skiptopText) ) {$skiptopText =  '#data-area';}
	echo '	<h3 class="ajaxHead">'.langtransstr('UV Index').
	'&nbsp;<a href="'.$SITE['pages']['printUV'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/submit.png" style="margin:1px; vertical-align: middle;" alt="'.$more_info.'" title="'.$more_info.'"></a>'.'</h3>'.PHP_EOL;
        if ($vars['ajaxuvmax'] == -1){ echo '<br />'; }
?>
	<p class="mTxt"><?php echo langtransstr('Now:')?>
	  <span class="ajax" id="ajaxuv"><?php echo $vars['ajaxuv']; ?></span>&nbsp;&nbsp;
	  <span class="ajax" id="ajaxuvword"><?php echo $vars['ajaxuvword']; ?></span>
	</p>
<?php
        if ($vars['ajaxuvmax'] <> -1 ){
?>	
	<hr />
	<p class="mTxt"><?php echo langtransstr('Max:')?>
	<span class="ajax" id="ajaxuvmax"><?php echo $vars['ajaxuvmax']?></span>&nbsp;@&nbsp;
	<span class="ajax" id="ajaxuvmaxtime"><?php echo $vars['ajaxuvmaxtime']?></span>
	</p>
<?php 
        }
} ?>
	</div>	<!-- col-UV -->
	<hr style="border: 0px; clear: right;"/>  <!-- just reallign cols -->
<?php
if ( $gizmo && isset($SITE['ajaxGizmoShow']) && $SITE['ajaxGizmoShow']  && $SITE['header'] == '3' ) {	
echo '	
<div style="width: 100%; text-align: right;"><hr />
<small>
<span class="ajax" id="gizmoindicator">'.$vars['ajaxindicator'].'</span>:&nbsp;
<span class="ajax" id="gizmodate">'.$vars['ajaxdate'].'</span>&nbsp; 
<span class="ajax" id="gizmotime">'.$vars['ajaxtime'].'</span>
<span id="ajaxcounter"></span>
&nbsp;'.langtransstr('seconds ago').'&nbsp;
</small>
</div>
'.PHP_EOL;
}  
?>
</div>
<!--  end of dashboard -->
<?php
# ----------------------  version history
# 3.20 2015-09-29 release 2.8 version 