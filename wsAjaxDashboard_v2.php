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
$pageName	= 'wsAjaxDashboard.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.02ax 2014-10-26';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-09-20 release version
# 3.01 2014-10-03 add data time display at the bottom
# 3.02 2014-10-26 uv changed for DW xml without UV max
# adapted for extra temp info
#-------------------------------------------------------------------------------------
if (trim($SITE['uomRain']) == 'in') 	{$decPrecip = 2;} else  {$decPrecip = 1;}
if (!function_exists ('wsNumber') ) {
	function wsNumber ($num, $dec=1,$point='',$sep='') {
		global $SITE;
		$amount	= str_replace(',','.',$num);
		if ($point == '') {
			if ($SITE['commaDecimal']) {$commaDecimal = ',';} else {$commaDecimal = '.';} 
		} else {
			$commaDecimal = $point;
		}
		if (is_numeric ($amount) ) {$amount = number_format ($amount,$dec,$commaDecimal,$sep);}
		return $amount;
	}
}
#-------------------------------------------------------------------------------------
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
	<div class="txtTemp"> <!-- text next to thermometer -->
		<span class="ajax" id="ajaxtemp" style="font-size:20px"><?php echo $vars['ajaxtemp'];?></span>
		<br />
		<span class="ajax" id="ajaxtemparrow"><?php echo $vars['ajaxtemparrow']; ?></span>
		<br /><br />
		<span class="ajax" id="ajaxheatcolorword"><?php echo $vars['ajaxheatcolorword'];?></span>
		<br /><br /> 
		<?php echo langtransstr('Feels like'); ?>:
		<span class="ajax" id="ajaxfeelslike"><?php echo $vars['ajaxfeelslike']; ?></span>
		
<?php if ((isset ($SITE['extraTempLeft'])) && ($SITE['extraTempLeft'])) { ?>
        <br /><br /> 
	<span class="lTxt">&nbsp;<?php echo langtransstr("Inside").": ".wsNumber($ws['tempActInside']).$uomTemp;?></span>
<?php } 
	if  ((isset ($SITE['extraTempRight'])) && ($SITE['extraTempRight'])) { ?>
	<br /> 	
	<span class="rTxt"><?php echo langtransstr("Pool").": ".wsNumber($ws['tempActExtra1']).$uomTemp.'&nbsp;'; ?>&nbsp;</span>
<?php } ?>	
		
		
	</div> <!-- text next to thermometer -->
	</td></tr>
	</table>

<!-- today yesterday min max values -->	
<table style="">
<tbody>
<tr style="height: 18px;">    
        <th style="height: 18px;">&nbsp;</th>
        <th  class="ajaxHead" style="height: 18px;"><?php echo langtransstr('High'); ?></th>
        <th  class="ajaxHead" style="height: 18px;"><?php echo langtransstr('Low'); ?></th>
</tr>
<tr>    <td style="font-weight: bold; "><br /><?php echo langtransstr('Today:'); ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #FF0000; background-color: #F3E5E5">
	        <span class="ajax" id="ajaxtempmax"><?php echo $vars['ajaxtempmax'];?></span>
		<br />(<span class="ajax" id="ajaxtempmaxTime"><?php echo  $vars['ajaxtempmaxTime'];?></span>)</td>
	<td style="border: 1px solid; border-radius:5px; border-color: #0000FF; background-color: #D0E2FF">
	        <span class="ajax" id="ajaxtempmin"><?php echo $vars['ajaxtempmin'];?></span>
		<br />(<span class="ajax" id="ajaxtempminTime"><?php echo $vars['ajaxtempminTime'];?></span>)</td>
</tr>
<tr>    <td style="font-weight: bold;"><br /><?php echo langtransstr('Yesterday:'); ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #FF0000; background-color: #F3E5E5">
	        <?php echo wsNumber($ws['tempMaxYday']).$uomTemp;?>
		<br /><?php echo  "(".string_date($ws['tempMaxYdayTime'],$SITE['timeOnlyFormat']).")";?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #0000FF; background-color: #D0E2FF">
	        <?php echo wsNumber($ws['tempMinYday']).$uomTemp;?>
		<br /><?php echo "(".string_date($ws['tempMinYdayTime'],$SITE['timeOnlyFormat']).")";?></td>
</tr>
<tr>    <td style="font-weight: bold;"><br /><?php echo langtransstr('Month:'); ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #FF0000; background-color: #F3E5E5"> 
	        <?php echo wsNumber($ws['tempMaxMonth']).$uomTemp;?>
		<br /><?php echo "(".string_date($ws['tempMaxMonthTime'],$SITE['dateMDFormat']).")";?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #0000FF; background-color: #D0E2FF">
		<?php echo wsNumber($ws['tempMinMonth']).$uomTemp;?>
		<br /><?php echo "(".string_date($ws['tempMinMonthTime'],$SITE['dateMDFormat']).")";?></td>
</tr>
<tr>    <td style="font-weight: bold;"><br /><?php echo langtransstr('Year:'); ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #FF0000; background-color: #F3E5E5">
	        <?php echo wsNumber($ws['tempMaxYear']).$uomTemp;?></span>
		<br /><?php echo "(".string_date($ws['tempMaxYearTime'],$SITE['dateMDFormat']).")";?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #0000FF; background-color: #D0E2FF">
		<?php echo wsNumber($ws['tempMinYear']).$uomTemp;?>
		<br /><?php echo "(".string_date($ws['tempMinYearTime'],$SITE['dateMDFormat']).")";?></td>
</tr>
<?php if (isset ($ws['tempMinRecordToday']) && isset ($ws['tempMaxRecordToday'])){ ?>
<tr>    <td style="font-weight: bold; "><?php echo langtransstr('Rcrd this Day:'); ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #FF0000; background-color: #F3E5E5">
	        <?php echo  $ws['tempMaxRecordToday'].$uomTemp;
	        if (isset($ws['tempMaxRecordTodayYear']) ) { ?>
	                <br/><?php echo "(".$ws['tempMaxRecordTodayYear'].")";
	        } ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #0000FF; background-color: #D0E2FF">
	        <?php echo  $ws['tempMinRecordToday'].$uomTemp;
	        if (isset($ws['tempMinRecordTodayYear']) ) { ?>
	                <br/><?php echo "(".$ws['tempMinRecordTodayYear'].")";
	        }?>
	        </td>
	</tr>
<?php 
} // eo Rcrd this Day:
if ($SITE['region'] == 'canada') {
    if (!isset ($ecForecast) ) {
	$script	= 'ecPlainCreateArr.php';
	echo '<!-- trying to load '.$script.' -->'.PHP_EOL;
	include($script);
	$weather 	= new ecPlainWeather ();
	$ecForecast 	= $weather->getWeatherData($SITE['caProvince'],$SITE['caCityCode']);
   }
   $ws['NormsMaxTemp']     = $ecForecast['information']['normalTempMax'];
   $ws['NormsMinTemp']     = $ecForecast['information']['normalTempMin'];
?>
	<tr><td style="font-weight: bold; "><?php echo langtransstr('Normals:'); ?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #FF0000;background-color: #F3E5E5"><?php echo  $ws['NormsMaxTemp'].$uomTemp;?></td>
	<td style="border: 1px solid; border-radius:5px; border-color: #0000FF; background-color: #D0E2FF"><?php echo  $ws['NormsMinTemp'].$uomTemp;?><br/></td>
	</tr>
	
<?php
}
?>
</tbody>
</table> 
<!-- today yesterday min max values -->
	</div> <!-- col-temp -->	
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
	<?php if (isset ($ws['rainDayMnth']) && !$ws['rainDayMnth'] == false ) { ?>
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
	<br />
	<hr />
	<p>
	<span class="lTxt"><?php echo langtransstr("Moon").":&nbsp;";?></span>
	<span class="rTxt"><small><img src="img/moonrise.png" alt="moonrise"/>&nbsp;
<?php if ($ws['moonrise'] == '0') { $ws['moonrise'] = langtransstr('&lt; 00:00');} echo $ws['moonrise']?>
	<img src="img/moonset.png" alt="moonset"/>&nbsp;
<?php if ($ws['moonset'] == '0') { $ws['moonset'] = langtransstr('&gt; 24:00');} echo $ws['moonset']?></small></span>
	</p>

	<p class="mTxt">
	<?php echo langtransstr(wsmoonWord ($ws['lunarPhasePerc'] , $ws['lunarAge']));?><br/>
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
	'&nbsp;<a href="'.$SITE['uvLink'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/i_symbolWhite.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>'.'</h3>'.PHP_EOL;
        $skipUVhtml     = true;
        include_once ('uvforecastv3.php');
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
<?php if(isset($vars['ajaxsolarpct']) && $vars['ajaxsolarpct'] <> 0) { // display only if data available 
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
	'&nbsp;<a href="'.$SITE['uvLink'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/i_symbolWhite.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>'.'</h3>'.PHP_EOL;
        $skipUVhtml     = true;
        include_once ($SITE['uvScript']);
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
	'&nbsp;<a href="'.$SITE['uvLink'].'&amp;lang='.$lang.$extraP.$skiptopText.'"><img src="./img/i_symbolWhite.png" style="margin:1px; vertical-align: middle;" alt="more information" title="more information"></a>'.'</h3>'.PHP_EOL;
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
