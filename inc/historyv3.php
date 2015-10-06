<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { 
   $filenameReal = __FILE__;			# display source of script if requested so
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
$pageName	= 'historyv3.php';
$pageVersion	= '3.20 2015-07-29';
#-------------------------------------------------------------------------------
# 3.20 2015-07-29 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# settings:
$page_title     = langtransstr('Weather history');
$timeOnlyFormat	= $SITE['timeOnlyFormat'];
$dateOnlyFormat	= $SITE['dateOnlyFormat'];
$dateMDFormat   = $SITE['dateMDFormat'];
$no_value       = ''; # langtransstr('n/a');
#
#               set to false if this weather station /sopftware does not supply those values
$use_temp       = true;  
$use_chill      = true; 
$use_heat       = true; 
$use_solar      = $SITE['SOLAR'];
$use_uv         = $SITE['UV'];
$use_et         = true;
$use_rain       = true; 
$use_snow       = false;
$use_humi       = true;
$use_dewp       = true;
$use_baro       = true;
$use_wind       = true;
$use_soil       = $SITE['soilUsed']; 
$use_leaf       = $SITE['leafUsed']; 

$show_alltime   = $SITE['alltime_values'];
$image_dir      = $SITE['imgDir'];     // directory to find images used in hi/low/avg script 
$img_temp       = $image_dir.'temp_hist.gif';
$img_chill      = $image_dir.'chill_hist.gif';
$img_heat       = $image_dir.'heat_hist.gif';
$img_solar      = $image_dir.'solar_hist.gif';
$img_uv         = $image_dir.'uv_hist.gif';
$img_rain       = $image_dir.'rain_hist.gif';
$img_humi       = $image_dir.'humi_hist.gif';
$img_dewp       = $image_dir.'dewp_hist.gif';
$img_baro       = $image_dir.'baro_hist.gif';
$img_wind       = $image_dir.'wind_hist.gif';
$img_soil       = $image_dir.'humi_hist.gif';
$img_leaf       = $image_dir.'humi_hist.gif';
#
$nr_cols        = 12;
if (!$show_alltime) {$nr_cols   = $nr_cols - 2;}
$col_width      = floor  ((200 / $nr_cols));
$col_width2     = floor( ($col_width/2));
$rest	= 100 - ($nr_cols*$col_width2);
#echo '<pre>'; print_r($ws); exit;
echo '<div class="blockDiv" style="background-color: transparent;">
<h3 class="blockHead" style="border-bottom: 1px solid; padding-bottom: 2px;">'.$page_title.'</h3>
<table class="genericTable">';
$table_head     = '
<tr>
<th class="blockHead" colspan ="2" style="width: '. $col_width.'%;">'.langtransstr('Current').'</th>
<th class="blockHead" colspan ="2" style="width: '. $col_width.'%; text-align: center;">'.langtransstr('Today').'</th>
<th class="blockHead" colspan ="2" style="width: '. $col_width.'%; text-align: center;">'.langtransstr('Yesterday').'</th>
<th class="blockHead" colspan ="2" style="width: '. $col_width.'%; text-align: center;">'.langtransstr('This Month').'</th>
<th class="blockHead" colspan ="2" style="width: '. $col_width.'%; text-align: center;">'.langtransstr('This year').'</th>'.PHP_EOL;
if ($show_alltime) {
        $table_head .= '<th class="blockHead" colspan ="2" style="width: '. ($col_width+$rest).'%; text-align: center;">'.langtransstr('Station Record').'</th>'.PHP_EOL;
}
$table_head     .= '</tr>'.PHP_EOL;
$height = 'height: 0px;';
$table_head2     = '
<tr style="'.$height.'">
<th class="blockHead" colspan ="2" style="width:'. $col_width.'%;'.$height.'"></th>
<th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th><th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th>
<th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th><th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th>
<th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th><th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th>
<th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th><th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th>';      
if ($show_alltime) {
        $table_head2 .= '<th class="blockHead" style="width: '. $col_width2.'%; '.$height.'"></th><th class="blockHead" style="width: '. ($col_width2+$rest).'%; '.$height.'"></th>'.PHP_EOL;
}
$table_head2     .= '</tr>'.PHP_EOL;

echo '<thead>'.$table_head2.$table_head.'</thead><tbody>'.PHP_EOL;
#
$uom            = $uomTemp;
$decimals       = $decTemp;
$colspan        = $nr_cols - 1;
if ($use_temp) {
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Temperature').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxtemp" style=" ">'.$vars['ajaxtemp'].'</span><span class="ajax" id="ajaxtemparrow">'.$vars['ajaxtemparrow'].'</span></b></td>';
        echo '</tr>'.PHP_EOL;
        echo '<tr><td rowspan="2"><div style="text-align: center;"><img src='.$img_temp.' style="margin-left: 0px; vertical-align: bottom; height: 30px;" alt =" "/></div></td>';
        echo '<td  style="text-align: right" >'.langtransstr('Maximum').'</td>';
        if (isset ($ws['tempMaxToday']) )       { echo generate_value ($ws['tempMaxToday'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMaxTodayTime']) )   { echo generate_date  ($ws['tempMaxTodayTime'],'time');} else { echo generate_date ('n/a');} 
        if (isset ($ws['tempMaxYday']) )        { echo generate_value ($ws['tempMaxYday'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMaxYdayTime']) )    { echo generate_date  ($ws['tempMaxYdayTime'],'time');}  else { echo generate_date ('n/a');} 
        if (isset ($ws['tempMaxMonth']) )       { echo generate_value ($ws['tempMaxMonth'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMaxMonthTime']) )   { echo generate_date  ($ws['tempMaxMonthTime'],'month');} else { echo generate_date ('n/a');} 
        if (isset ($ws['tempMaxYear']) )        { echo generate_value ($ws['tempMaxYear'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMaxYearTime']) )    { echo generate_date  ($ws['tempMaxYearTime'],'month');}  else { echo generate_date ('n/a');} 
        if ($show_alltime) {
                if (isset ($ws['tempMaxAll']) )     { echo generate_value ($ws['tempMaxAll'],$uom);}            else { echo generate_value ('n/a');}
                if (isset ($ws['tempMaxAllTime']) ) { echo generate_date  ($ws['tempMaxAllTime'],'date');}      else { echo generate_date ('n/a');}      
        }
        echo '</tr>'.PHP_EOL;
        echo '<tr><td  style="text-align: right" >'.langtransstr('Minimum').'</td>';
        if (isset ($ws['tempMinToday']) )       { echo generate_value ($ws['tempMinToday'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMinTodayTime']) )   { echo generate_date  ($ws['tempMinTodayTime'],'time');} else { echo generate_date ('n/a');} 
        if (isset ($ws['tempMinYday']) )        { echo generate_value ($ws['tempMinYday'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMinYdayTime']) )    { echo generate_date  ($ws['tempMinYdayTime'],'time');}  else { echo generate_date ('n/a');} 
        if (isset ($ws['tempMinMonth']) )       { echo generate_value ($ws['tempMinMonth'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMinMonthTime']) )   { echo generate_date  ($ws['tempMinMonthTime'],'month');} else { echo generate_date ('n/a');} 
        if (isset ($ws['tempMinYear']) )        { echo generate_value ($ws['tempMinYear'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['tempMinYearTime']) )    { echo generate_date  ($ws['tempMinYearTime'],'month');}  else { echo generate_date ('n/a');} 
        if ($show_alltime) {
                if (isset ($ws['tempMinAll']) )     { echo generate_value ($ws['tempMinAll'],$uom);}            else { echo generate_value ('n/a');}
                if (isset ($ws['tempMinAllTime']) ) { echo generate_date  ($ws['tempMinAllTime'],'date');}       else { echo generate_date ('n/a');} 
        }
        echo '</tr>'.PHP_EOL;
}  // end of use-temp
#
if ($use_chill && isset ($ws['chilAct']) ){
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Windchill').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxchill" style=" ">'.wsNumber($ws['chilAct'],$decimals).$uom.'</span><span class="ajax" id="ajaxchillarrow">&nbsp;</span></b></td>';
        echo '</tr>'.PHP_EOL;
        echo '<tr><td rowspan="1"><div style="text-align: center;"><img src='.$img_chill.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right" >'.langtransstr('Minimum').'</td>';
        if (isset ($ws['chilMinToday']) )       { echo generate_value ($ws['chilMinToday'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['chilMinTodayTime']) )   { echo generate_date  ($ws['chilMinTodayTime'],'time');} else { echo generate_date ('n/a');} 

        if (isset ($ws['chilMinYday']) )        { echo generate_value ($ws['chilMinYday'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['chilMinYdayTime']) )    { echo generate_date  ($ws['chilMinYdayTime'],'time');}  else { echo generate_date ('n/a');} 

        if (isset ($ws['chilMinMonth']) )       { echo generate_value ($ws['chilMinMonth'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['chilMinMonthTime']) )   { echo generate_date  ($ws['chilMinMonthTime'],'month');} else { echo generate_date ('n/a');} 

        if (isset ($ws['chilMinYear']) )        { echo generate_value ($ws['chilMinYear'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['chilMinYearTime']) )    { echo generate_date  ($ws['chilMinYearTime'],'month');}  else { echo generate_date ('n/a');} 

        if ($show_alltime) {
                if (isset ($ws['chilMinAll']) )     { echo generate_value ($ws['chilMinAll'],$uom);}            else { echo generate_value ('n/a');}
                if (isset ($ws['chilMinAllTime']) ) { echo generate_date  ($ws['chilMinAllTime'],'date');}       else { echo generate_date ('n/a');}
        }
        echo '</tr>'.PHP_EOL;
} // eo use chill
#
if ($use_heat && isset ($ws['heatAct'])) {
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Heatindex').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxheat" style=" ">'.wsNumber($ws['heatAct'],$decimals).$uom.'</span>&nbsp;<span class="ajax" id="ajaxheatarrow">&nbsp;</span></b></td>';
        echo '</tr>'.PHP_EOL;
        echo '<tr><td rowspan="1"><div style="text-align: center;"><img src='.$img_heat.' style="margin-left: 0px; vertical-align: bottom;" alt =" "/></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Maximum').'</td>';
        if (isset ($ws['heatMaxToday']) )       { echo generate_value ($ws['heatMaxToday'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['heatMaxTodayTime']) )   { echo generate_date ($ws['heatMaxTodayTime'],'time');} else { echo generate_date ('n/a');} 
        if (isset ($ws['heatMaxYday']) )        { echo generate_value ($ws['heatMaxYday'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['heatMaxYdayTime']) )    { echo generate_date ($ws['heatMaxYdayTime'],'time');}  else { echo generate_date ('n/a');} 
        if (isset ($ws['heatMaxMonth']) )       { echo generate_value ($ws['heatMaxMonth'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['heatMaxMonthTime']) )   { echo generate_date ($ws['heatMaxMonthTime'],'month');} else { echo generate_date ('n/a');} 
        if (isset ($ws['heatMaxYear']) )        { echo generate_value ($ws['heatMaxYear'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['heatMaxYearTime']) )    { echo generate_date ($ws['heatMaxYearTime'],'month');}  else { echo generate_date ('n/a');} 
        if ($show_alltime) {
                if (isset ($ws['heatMaxAll']) )     { echo generate_value ($ws['heatMaxAll'],$uom);} else { echo generate_value ('n/a');}
                if (isset ($ws['heatMaxAllTime']) ) { echo generate_date ($ws['heatMaxAllTime'],'date');}  else { echo generate_date ('n/a');}
        }
        echo '</tr>'.PHP_EOL;
} // eo use heat
#
if ($use_solar) {
        $uom            = ' w/m2';
        $decimals       = 0;
        if      (isset ($SITE['uomsSolar']) )   {$uom    = $SITE['uomsSolar'];} 
        elseif  (isset ($uomsSolar) )           {$uom    = $uomsSolar;} 
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Solar radiation').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxsolar" style=" ">'.$vars['ajaxsolar'].$uom.'</span><span class="ajax" id="ajaxsolararrow">'.'&nbsp;'.'</span></b></td>';
        echo '</tr>'.PHP_EOL;
        echo '<tr><td rowspan="1"><div style="text-align: center;"><img src='.$img_solar.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Maximum').'</td>';
        if (isset ($ws['solarMaxToday']) )      { 
                echo '<td style="text-align: right;"> <span class="ajax" id="ajaxsolarmax" >'.$vars['ajaxsolarmax'].$uom.'</span></td>'.PHP_EOL;} 
        else { echo generate_value ('n/a');} 
        if (isset ($ws['solarMaxTodayTime']) )  { echo generate_date ($ws['solarMaxTodayTime'],'time');} else { echo generate_date ('n/a');} 
        if (isset ($ws['solarMaxYday']) )       { echo generate_value ($ws['solarMaxYday'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['solarMaxYdayTime']) )   { echo generate_date ($ws['solarMaxYdayTime'],'time');}  else { echo generate_date ('n/a');} 
        if (isset ($ws['solarMaxMonth']) )      { echo generate_value ($ws['solarMaxMonth'],$uom);}      else { echo generate_value ('n/a');} 
        if (isset ($ws['solarMaxMonthTime']) )  { echo generate_date ($ws['solarMaxMonthTime'],'month');} else { echo generate_date ('n/a');} 
        if (isset ($ws['solarMaxYear']) )       { echo generate_value ($ws['solarMaxYear'],$uom);}       else { echo generate_value ('n/a');} 
        if (isset ($ws['solarMaxYearTime']) )   { echo generate_date ($ws['solarMaxYearTime'],'month');}  else { echo generate_date ('n/a');} 
        if ($show_alltime) {
                if (isset ($ws['solarMaxAll']) )     { echo generate_value ($ws['solarMaxAll'],$uom);}            else { echo generate_value ('n/a');}
                if (isset ($ws['solarMaxAllTime'])) { echo generate_date ($ws['solarMaxAllTime'],'date');}      else { echo generate_date ('n/a');}
        }
        echo '</tr>'.PHP_EOL;
} // eo use solar
#
if ($use_uv) {
        $uom            = ' ';
        $decimals       = 1;
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('UV index').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxuv" style=" ">'.$vars['ajaxuv'].$uom.'</span>&nbsp;<span class="ajax" id="ajaxuvarrow">'.'&nbsp;'.'</span></b></td>';
        echo '</tr>'.PHP_EOL;
        echo '<tr><td rowspan="1"><div style="text-align: center;"><img src='.$img_uv.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Maximum').'</td>'.PHP_EOL;
        if (isset ($ws['uvMaxToday']) )         { 
                echo '<td style="text-align: right;"> <span class="ajax" id="ajaxuvmax" style="text-align: right;">'.$vars['ajaxuvmax'].$uom.'</span></td>'.PHP_EOL;} 
        else {  echo generate_value ('n/a');} 
        if (isset ($ws['uvMaxTodayTime']) )     { 
                echo '<td style="text-align: left;"><small>&nbsp;(<span class="ajax" id="ajaxuvmaxtime" style="">'.$vars['ajaxuvmaxtime'].'</span>)</small></td>'.PHP_EOL;} 
        else {  echo generate_date ('n/a');} 
        if (isset ($ws['uvMaxYday']) )          { echo generate_value ($ws['uvMaxYday'],$uom);}         else { echo generate_value ('n/a');} 
        if (isset ($ws['uvMaxYdayTime']) )      { echo generate_date ($ws['uvMaxYdayTime'],'time');}    else { echo generate_date ('n/a');} 
        if (isset ($ws['uvMaxMonth']) )         { echo generate_value ($ws['uvMaxMonth'],$uom);}        else { echo generate_value ('n/a');} 
        if (isset ($ws['uvMaxMonthTime']) )     { echo generate_date ($ws['uvMaxMonthTime'],'month');}   else { echo generate_date ('n/a');} 
        if (isset ($ws['uvMaxYear']) )          { echo generate_value ($ws['uvMaxYear'],$uom);}         else { echo generate_value ('n/a');} 
        if (isset ($ws['uvMaxYearTime']) )      { echo generate_date ($ws['uvMaxYearTime'],'month');}    else { echo generate_date ('n/a');} 
        if ($show_alltime) {
                if (isset ($ws['uvMaxAll']) )    { echo generate_value ($ws['uvMaxAll'],$uom);}   else { echo generate_value ('n/a');}
                if (isset ($ws['uvMaxAllTime']) )   { echo generate_date ($ws['uvMaxAllTime'],'date');} else { echo generate_date ('n/a');}
        }
        echo '</tr>'.PHP_EOL;
} // eo use solar
#
echo $table_head;
if ($use_rain) {
        if ($use_et && isset ($ws['etToday']) ) {$span ='2';} else {$span ='1';}
        $uom            = $uomRain;
        $decimals       = $decPrecip;
        if (trim($SITE['uomRain']) == 'in') 	{$decimals  = 2;} else  {$decimals = 1;}
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Precipitation').'</b></td>';
        echo '</tr>'.PHP_EOL;
        echo '<tr><td rowspan="'.$span.'"><div style="text-align: center;"><img src='.$img_rain.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Rain').' / '.langtransstr('Melt').'</td>
<td style="text-align: right;"><span  class="ajax" id="ajaxrain">'.$vars['ajaxrain'].'</span></td><td>&nbsp;</td>
<td style="text-align: right;">'.wsNumber($ws['rainYday'],$decPrecip).$uom.'</td><td>&nbsp;</td>
<td style="text-align: right;"><span class="ajax" id="ajaxrainmo">'.$vars['ajaxrainmo'].'</span></td><td>&nbsp;</td>
<td style="text-align: right;"><span class="ajax" id="ajaxrainyr">'.$vars['ajaxrainyr'].'</span></td><td>&nbsp;</td>';
        if ($show_alltime) {
        	if (isset ($ws['rainAll']) ) 		{ echo generate_value ($ws['rainAll'],$uom);} 		else { echo generate_value ('n/a');}
        	if (isset ($ws['rainMaxAllTime']) )   	{ echo generate_date ($ws['rainMaxAllTime'],'date');} 	else { echo generate_date ('n/a');}
        }
        echo '</tr>'.PHP_EOL;
        if ($use_et && isset ($ws['etToday'])) {
                echo'<tr><td  style="text-align: right;" >'.langtransstr('Evapotranspiration').'</td>'.generate_value ($ws['etToday'],$uom).''.PHP_EOL;
                echo '<td>&nbsp;</td>';
                if (isset ($ws['etYday'])) {echo generate_value ($ws['etYday'],$uom);}  else { echo generate_value ('n/a');}
                echo '<td>&nbsp;</td>';           
                if (isset ($ws['etMonth'])){echo generate_value ($ws['etMonth'],$uom);} else { echo generate_value ('n/a');}
                echo '<td>&nbsp;</td>';
                if (isset ($ws['etYear'])) {echo generate_value ($ws['etYear'],$uom);}  else { echo generate_value ('n/a');}
                echo '<td>&nbsp;</td>';
                if ($show_alltime) {
                        if (isset ($ws['etAll']) )  	{ echo generate_value ($ws['etAll'],$uom);}  	else { echo generate_value ('n/a'); } 
                        if (isset ($ws['etAllTime']) )  { echo generate_date ($ws['etAllTime'],$uom);}  else { echo generate_date ('n/a'); } 
                }
                echo '</tr>'.PHP_EOL;
        }
} // eo use rain
#
if ($use_humi) {
        $uom    = '%';
        $decimals  = 0;
        $span ='2';
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Humidity').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxhumidity" style="">'.$vars['ajaxhumidity'].'</span>&nbsp;<span class="ajax" id="ajaxhumidityarrow">'.'&nbsp;'.'</span></b></td>';
        echo '</tr>'.PHP_EOL;       
        echo '<tr><td rowspan="'.$span.'"><div style="text-align: center;"><img src='.$img_humi.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Maximum').'</td>';
        if (isset ($ws['humiMaxToday']) )       { echo generate_value ($ws['humiMaxToday'],$uom);}         else { echo generate_value ('n/a');} 
        if (isset ($ws['humiMaxTodayTime']) )   { echo generate_date ($ws['humiMaxTodayTime'],'time');}    else { echo generate_date ('n/a');}  
        if (isset ($ws['humiMaxYday']) )        { echo generate_value ($ws['humiMaxYday'],$uom);}          else { echo generate_value ('n/a');} 
        if (isset ($ws['humiMaxYdayTime']) )    { echo generate_date ($ws['humiMaxYdayTime'],'time');}     else { echo generate_date ('n/a');} 
        if (isset ($ws['humiMaxMonth']) )      { 
                echo generate_value ($ws['humiMaxMonth'],$uom);     
                if (isset ($ws['humiMaxMonthTime']) )   {
                        echo generate_date ($ws['humiMaxMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['humiMaxYear']) )       { 
                echo generate_value ($ws['humiMaxYear'],$uom);         
                if (isset ($ws['humiMaxYearTime']) )    { 
                        echo generate_date ($ws['humiMaxYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 

        if ($show_alltime) {
                if ( isset ($ws['humiMaxAll']) )                { echo generate_value ($ws['humiMaxAll'],$uom);         
                        if (isset ($ws['humiMaxAllTime']) )     { echo generate_date ($ws['humiMaxAllTime'],'date');}     else {  echo generate_date ('n/a');} 
                }
                else { echo '<td>&nbsp;</td><td>&nbsp;</td>';}
        } 
        
        echo '</tr><tr>'.PHP_EOL;
        echo '<td  style="text-align: right;" >'.langtransstr('Minimum').'</td>';
        if (isset ($ws['humiMinToday']) )       { echo generate_value ($ws['humiMinToday'],$uom);}  else { echo generate_value ('n/a');} 
        if (isset ($ws['humiMinTodayTime']) )   { echo generate_date ($ws['humiMinTodayTime']);}    else { echo generate_date ('n/a');}  
        if (isset ($ws['humiMinYday']) )        { echo generate_value ($ws['humiMinYday'],$uom);}   else { echo generate_value ('n/a');} 
        if (isset ($ws['humiMinYdayTime']) )    { echo generate_date ($ws['humiMinYdayTime']);}     else { echo generate_date ('n/a');} 
#        echo $ws['humiMinYdayTime']; exit;
#echo '<pre>'; print_r($ws); exit;
        if (isset ($ws['humiMinMonth']) )      { 
                echo generate_value ($ws['humiMinMonth'],$uom);     
                if (isset ($ws['humiMinMonthTime']) )   {
                        echo generate_date ($ws['humiMinMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['humiMinYear']) )       { 
                echo generate_value ($ws['humiMinYear'],$uom);         
                if (isset ($ws['humiMinYearTime']) )    { 
                        echo generate_date ($ws['humiMinYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if ($show_alltime) {
                if (isset ($ws['humiMinAll']) )                 { echo generate_value ($ws['humiMinAll'],$uom);         
                        if (isset ($ws['humiMinAllTime']) )     { echo generate_date ($ws['humiMinAllTime'],'date');}     else {  echo generate_date ('n/a');} 
                }
                else { echo '<td>&nbsp;</td><td>&nbsp;</td>';}
        } 

        echo '</tr>'.PHP_EOL;
} // eo humidity
#
if ($use_dewp) {
        $uom            = $uomTemp;
        $decimals       = $decTemp;
        $span           ='2';
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Dewpoint').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxdew" style="">'.$vars['ajaxdew'].'</span>&nbsp;<span class="ajax" id="ajaxdewarrow">'.'&nbsp;'.'</span></b></td>';
        echo '</tr>'.PHP_EOL;       
        echo '<tr><td rowspan="'.$span.'"><div style="text-align: center;"><img src='.$img_dewp.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Maximum').'</td>';
        if (isset ($ws['dewpMaxToday']) )       { echo generate_value ($ws['dewpMaxToday'],$uom);}         else { echo generate_value ('n/a');} 
        if (isset ($ws['dewpMaxTodayTime']) )   { echo generate_date ($ws['dewpMaxTodayTime'],'time');}    else { echo generate_date ('n/a');}  
        if (isset ($ws['dewpMaxYday']) )        { echo generate_value ($ws['dewpMaxYday'],$uom);}          else { echo generate_value ('n/a');} 
        if (isset ($ws['dewpMaxYdayTime']) )    { echo generate_date ($ws['dewpMaxYdayTime'],'time');}     else { echo generate_date ('n/a');} 
        if (isset ($ws['dewpMaxMonth']) )      { 
                echo generate_value ($ws['dewpMaxMonth'],$uom);     
                if (isset ($ws['dewpMaxMonthTime']) )   {
                        echo generate_date ($ws['dewpMaxMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['dewpMaxYear']) )       { 
                echo generate_value ($ws['dewpMaxYear'],$uom);         
                if (isset ($ws['dewpMaxYearTime']) )    { 
                        echo generate_date ($ws['dewpMaxYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if ($show_alltime) {
                if  ( isset ($ws['dewpMaxAll']) )       { 
                        echo generate_value ($ws['dewpMaxAll'],$uom);         
                        if (isset ($ws['dewpMaxAllTime']) )     { echo generate_date ($ws['dewpMaxAllTime'],'date');}     
                        else                                    { echo generate_date ('n/a');} 
                }
                else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        }
        
        echo '</tr><tr>'.PHP_EOL;
        echo '<td  style="text-align: right;" >'.langtransstr('Minimum').'</td>';
        if (isset ($ws['dewpMinToday']) )       { echo generate_value ($ws['dewpMinToday'],$uom);}  else { echo generate_value ('n/a');} 
        if (isset ($ws['dewpMinTodayTime']) )   { echo generate_date ($ws['dewpMinTodayTime']);}    else { echo generate_date ('n/a');}  
        if (isset ($ws['dewpMinYday']) )        { echo generate_value ($ws['dewpMinYday'],$uom);}   else { echo generate_value ('n/a');} 
        if (isset ($ws['dewpMinYdayTime']) )    { echo generate_date ($ws['dewpMinYdayTime']);}     else { echo generate_date ('n/a');} 
#        echo $ws['dewpMinYdayTime']; exit;
#echo '<pre>'; print_r($ws); exit;
        if (isset ($ws['dewpMinMonth']) )      { 
                echo generate_value ($ws['dewpMinMonth'],$uom);     
                if (isset ($ws['dewpMinMonthTime']) )   {
                        echo generate_date ($ws['dewpMinMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['dewpMinYear']) )       { 
                echo generate_value ($ws['dewpMinYear'],$uom);         
                if (isset ($ws['dewpMinYearTime']) )    { 
                        echo generate_date ($ws['dewpMinYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if ($show_alltime) {
                if (isset ($ws['dewpMinAll']) )                 { 
                        echo generate_value ($ws['dewpMinAll'],$uom);         
                        if (isset ($ws['dewpMinAllTime']) )     { 
                                echo generate_date ($ws['dewpMinAllTime'],'date');}  
                        else {  echo generate_date ('n/a');} 
                }
                else{ echo '<td>&nbsp;</td><td>&nbsp;</td>';}
        } 
        echo '</tr>'.PHP_EOL;
} // eo dewpoint
#
echo $table_head;
if ($use_baro) {
        $uom            = $uomBaro;
        $decimals       = $decBaro ;
        $span           ='2';
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Pressure').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxbaro" style="">'.$vars['ajaxbaro'].'</span>&nbsp;<span class="ajax" id="ajaxbaroarrow">'.'&nbsp;'.'</span>';
        echo '<span class="ajax" id="ajaxbarotrendtext" style="">'.$vars['ajaxbarotrendtext'].'</span></b></td>';
        echo '</tr>'.PHP_EOL;       
        echo '<tr><td rowspan="'.$span.'"><div style="text-align: center;"><img src='.$img_baro.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Maximum').'</td>';
        if (isset ($ws['baroMaxToday']) )       { echo generate_value ($ws['baroMaxToday'],$uom);}         else { echo generate_value ('n/a');} 
        if (isset ($ws['baroMaxTodayTime']) )   { echo generate_date ($ws['baroMaxTodayTime'],'time');}    else { echo generate_date ('n/a');}  
        if (isset ($ws['baroMaxYday']) )        { echo generate_value ($ws['baroMaxYday'],$uom);}          else { echo generate_value ('n/a');} 
        if (isset ($ws['baroMaxYdayTime']) )    { echo generate_date ($ws['baroMaxYdayTime'],'time');}     else { echo generate_date ('n/a');} 
        if (isset ($ws['baroMaxMonth']) )      { 
                echo generate_value ($ws['baroMaxMonth'],$uom);     
                if (isset ($ws['baroMaxMonthTime']) )   {
                        echo generate_date ($ws['baroMaxMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['baroMaxYear']) )       { 
                echo generate_value ($ws['baroMaxYear'],$uom);         
                if (isset ($ws['baroMaxYearTime']) )    { 
                        echo generate_date ($ws['baroMaxYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        
        if ($show_alltime) {
                if ( isset ($ws['baroMaxAll']) )                { echo generate_value ($ws['baroMaxAll'],$uom);         
                        if (isset ($ws['baroMaxAllTime']) )     { echo generate_date ($ws['baroMaxAllTime'],'date');}     else {  echo generate_date ('n/a');} 
                }
                else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        }
        echo '</tr><tr>'.PHP_EOL;
        echo '<td  style="text-align: right;" >'.langtransstr('Minimum').'</td>';
        if (isset ($ws['baroMinToday']) )       { echo generate_value ($ws['baroMinToday'],$uom);}  else { echo generate_value ('n/a');} 
        if (isset ($ws['baroMinTodayTime']) )   { echo generate_date ($ws['baroMinTodayTime']);}    else { echo generate_date ('n/a');}  
        if (isset ($ws['baroMinYday']) )        { echo generate_value ($ws['baroMinYday'],$uom);}   else { echo generate_value ('n/a');} 
        if (isset ($ws['baroMinYdayTime']) )    { echo generate_date ($ws['baroMinYdayTime']);}     else { echo generate_date ('n/a');} 
#        echo $ws['dewpMinYdayTime']; exit;
#echo '<pre>'; print_r($ws); exit;
        if (isset ($ws['baroMinMonth']) )      { 
                echo generate_value ($ws['baroMinMonth'],$uom);     
                if (isset ($ws['baroMinMonthTime']) )   {
                        echo generate_date ($ws['baroMinMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['baroMinYear']) )       { 
                echo generate_value ($ws['baroMinYear'],$uom);         
                if (isset ($ws['baroMinYearTime']) )    { 
                        echo generate_date ($ws['baroMinYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if ($show_alltime) {
                if (isset ($ws['baroMinAll']) )                 { echo generate_value ($ws['baroMinAll'],$uom);         
                        if (isset ($ws['baroMinAllTime']) )     { echo generate_date ($ws['baroMinAllTime'],'date');}      else {  echo generate_date ('n/a');} 
                }
                else { echo '<td>&nbsp;</td><td>&nbsp;</td>';}
        } 
        echo '</tr>'.PHP_EOL;
} // eo baro
#
if ($use_wind) {
        $uom            = $uomWind;
        $decimals 	= $decWind;
        $span           ='1';
        echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Wind').'&nbsp;&nbsp;';
        echo '<span class="ajax" id="ajaxwind" style="">'.$vars['ajaxwind'].'</span>&nbsp;<span class="ajax" id="ajaxwindarrow">'.'&nbsp;'.'</span>&nbsp;';
        echo '<span class="ajax" id="ajaxwinddir" style="">'.$vars['ajaxwinddir'].'</span>&nbsp;&nbsp;<span class="ajax" id="ajaxbeaufort" style="">'.$vars['ajaxbeaufort'].'</span></b></td>';
        echo '</tr>'.PHP_EOL;       
        echo '<tr><td rowspan="'.$span.'"><div style="text-align: center;"><img src='.$img_wind.' style="margin-left: 0px; vertical-align: bottom;" alt =" " /></div></td>';
        echo '<td  style="text-align: right;" >'.langtransstr('Gust').'</td>';
        if (isset ($ws['gustMaxToday']) )       { echo generate_value ($ws['gustMaxToday'],$uom);}         else { echo generate_value ('n/a');} 
        if (isset ($ws['gustMaxTodayTime']) )   { echo generate_date ($ws['gustMaxTodayTime'],'time');}    else { echo generate_date ('n/a');}  
        if (isset ($ws['gustMaxYday']) )        { echo generate_value ($ws['gustMaxYday'],$uom);}          else { echo generate_value ('n/a');} 
        if (isset ($ws['gustMaxYdayTime']) )    { echo generate_date ($ws['gustMaxYdayTime'],'time');}     else { echo generate_date ('n/a');} 
        if (isset ($ws['gustMaxMonth']) )      { 
                echo generate_value ($ws['gustMaxMonth'],$uom);     
                if (isset ($ws['gustMaxMonthTime']) )   {
                        echo generate_date ($ws['gustMaxMonthTime'],'month');}    
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if (isset ($ws['gustMaxYear']) )       { 
                echo generate_value ($ws['gustMaxYear'],$uom);         
                if (isset ($ws['gustMaxYearTime']) )    { 
                        echo generate_date ($ws['gustMaxYearTime'],'month');}     
                else {  echo generate_date ('n/a');} 
        }
        else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        if ($show_alltime) {
                if (isset ($ws['gustMaxAll']) )                 { echo generate_value ($ws['gustMaxAll'],$uom);         
                        if (isset ($ws['gustMaxAllTime']) )     { echo generate_date ($ws['gustMaxAllTime'],'date');}     else {  echo generate_date ('n/a');} 
                }
                else { echo '<td>&nbsp;</td><td>&nbsp;</td>';} 
        }
        echo '</tr>'.PHP_EOL;
} // eo wind
#
if ($use_soil && isset ($SITE['soilCount']) &&  $SITE['soilCount']*1.0 > 0 ){
        echo $table_head;
        $uom            = $uomTemp;
        $decimals       = $decTemp;
        $span           ='2';
        $i              = 1;
        $count          = $SITE['soilCount']*1.0;
        if ($count > 4) {$count = 4;}
        for ($i = 1; $i <= $count; $i++) {
                echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Soil sensor').$i.
                        '&nbsp;&nbsp;'.langtransstr('Temperature at depth of'). ' '.$SITE['soilDepth_'.$i].$SITE['uomSnow'];
                echo ' = '.$ws['soilTempAct'][$i].$uom.'</b></td>'.PHP_EOL;
                echo '</tr>'.PHP_EOL;
                echo '<tr><td rowspan="2"><div style="text-align: center;"><img src='.$img_temp.' style="margin-left: 0px; vertical-align: bottom; height: 30px;" alt =" "/></div></td>';
		if (isset ($ws['soilTempMaxToday'][$i]) )       {
			echo '<td  style="text-align: right" >'.langtransstr('Maximum').'</td>';
			if (isset ($ws['soilTempMaxToday'][$i]) )       { echo generate_value ($ws['soilTempMaxToday'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMaxTodayTime'][$i]) )   { echo generate_date  ($ws['soilTempMaxTodayTime'][$i],'time');} else { echo generate_date ('n/a');} 
			if (isset ($ws['soilTempMaxYday'][$i]) )        { echo generate_value ($ws['soilTempMaxYday'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMaxYdayTime'][$i]) )    { echo generate_date  ($ws['soilTempMaxYdayTime'][$i],'time');}  else { echo generate_date ('n/a');} 
			if (isset ($ws['soilTempMaxMonth'][$i]) )       { echo generate_value ($ws['soilTempMaxMonth'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMaxMonthTime'][$i]) )   { echo generate_date  ($ws['soilTempMaxMonthTime'][$i],'month');} else { echo generate_date ('n/a');} 
			if (isset ($ws['soilTempMaxYear'][$i]) )        { echo generate_value ($ws['soilTempMaxYear'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMaxYearTime'][$i]) )    { echo generate_date  ($ws['soilTempMaxYearTime'][$i],'month');}  else { echo generate_date ('n/a');} 
			if ($show_alltime) {
				if (isset ($ws['soilTempMaxAll'][$i]) )         { echo generate_value ($ws['soilTempMaxAll'][$i],$uom);}            else { echo generate_value ('n/a');}
				if (isset ($ws['soilTempMaxAllTime'][$i]) )     { echo generate_date  ($ws['soilTempMaxAllTime'][$i],'date');}      else { echo generate_date ('n/a');}      
			}
			echo '</tr>'.PHP_EOL;
		} else { echo '<td colspan="'.$colspan.'">&nbsp;</td></tr>'.PHP_EOL; }
		if (isset ($ws['soilTempMinToday'][$i]) )       {	
			echo '<tr><td  style="text-align: right" >'.langtransstr('Minimum').'</td>';
			if (isset ($ws['soilTempMinToday'][$i]) )       { echo generate_value ($ws['soilTempMinToday'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMinTodayTime'][$i]) )   { echo generate_date  ($ws['soilTempMinTodayTime'][$i],'time');} else { echo generate_date ('n/a');} 
			if (isset ($ws['soilTempMinYday'][$i]) )        { echo generate_value ($ws['soilTempMinYday'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMinYdayTime'][$i]) )    { echo generate_date  ($ws['soilTempMinYdayTime'][$i],'time');}  else { echo generate_date ('n/a');} 
			if (isset ($ws['soilTempMinMonth'][$i]) )       { echo generate_value ($ws['soilTempMinMonth'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMinMonthTime'][$i]) )   { echo generate_date  ($ws['soilTempMinMonthTime'][$i],'month');} else { echo generate_date ('n/a');} 
			if (isset ($ws['soilTempMinYear'][$i]) )        { echo generate_value ($ws['soilTempMinYear'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['soilTempMinYearTime'][$i]) )    { echo generate_date  ($ws['soilTempMinYearTime'][$i],'month');}  else { echo generate_date ('n/a');} 
			if ($show_alltime) {
				if (isset ($ws['soilTempMinAll'][$i]) )         { echo generate_value ($ws['soilTempMinAll'][$i],$uom);}            else { echo generate_value ('n/a');}
				if (isset ($ws['soilTempMinAllTime'][$i]) )     { echo generate_date  ($ws['soilTempMinAllTime'][$i],'date');}      else { echo generate_date ('n/a');}      
			}
			echo '</tr>'.PHP_EOL;
		} else { echo '<tr><td colspan="'.$colspan.'">&nbsp;</td></tr>'.PHP_EOL; }
        } // eo loop 4 sensors  
        $uom            = $SITE['uomMoist'];
        $decimals       = 0;  
        for ($i = 1; $i <= $count; $i++) {
                echo '<tr class="row-dark"><td>&nbsp;</td><td  style="text-align: left;" colspan="'.$colspan.'"><b>'.langtransstr('Soil sensor').$i.
                        '&nbsp;&nbsp;'.langtransstr('Moisture at depth of'). ' '.$SITE['soilDepth_'.$i].$SITE['uomSnow'];
                echo ' = '.$ws['moistAct'][$i].$uom.'</b></td>'.PHP_EOL;
                echo '</tr>'.PHP_EOL;
                echo '<tr><td rowspan="2"><div style="text-align: center;"><img src='.$img_dewp.' style="margin-left: 0px; vertical-align: bottom; height: 30px;" alt =" "/></div></td>';
                if (isset ($ws['moistMaxToday'][$i]) ) {
			echo '<td  style="text-align: right" >'.langtransstr('Maximum').'</td>';
			if (isset ($ws['moistMaxToday'][$i]) )       { echo generate_value ($ws['moistMaxToday'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMaxTodayTime'][$i]) )   { echo generate_date  ($ws['moistMaxTodayTime'][$i],'time');} else { echo generate_date ('n/a');} 
			if (isset ($ws['moistMaxYday'][$i]) )        { echo generate_value ($ws['moistMaxYday'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMaxYdayTime'][$i]) )    { echo generate_date  ($ws['moistMaxYdayTime'][$i],'time');}  else { echo generate_date ('n/a');} 
			if (isset ($ws['moistMaxMonth'][$i]) )       { echo generate_value ($ws['moistMaxMonth'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMaxMonthTime'][$i]) )   { echo generate_date  ($ws['moistMaxMonthTime'][$i],'month');} else { echo generate_date ('n/a');} 
			if (isset ($ws['moistMaxYear'][$i]) )        { echo generate_value ($ws['moistMaxYear'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMaxYearTime'][$i]) )    { echo generate_date  ($ws['moistMaxYearTime'][$i],'month');}  else { echo generate_date ('n/a');} 
			if ($show_alltime) {
				if (isset ($ws['moistMaxAll'][$i]) )         { echo generate_value ($ws['moistMaxAll'][$i],$uom);}            else { echo generate_value ('n/a');}
				if (isset ($ws['moistMaxAllTime'][$i]) )     { echo generate_date  ($ws['moistMaxAllTime'][$i],'date');}      else { echo generate_date ('n/a');}      
			}
                } else { echo '<td colspan="'.$colspan.'">&nbsp;</td></tr>'.PHP_EOL; }
                echo '</tr>'.PHP_EOL;
                if (isset ($ws['moistMinToday'][$i]) && $ws['moistMinToday'][$i] <> 0) {               
			echo '<tr><td  style="text-align: right" >'.langtransstr('Minimum').'</td>';
			if (isset ($ws['moistMinToday'][$i]) )       { echo generate_value ($ws['moistMinToday'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMinTodayTime'][$i]) )   { echo generate_date  ($ws['moistMinTodayTime'][$i],'time');} else { echo generate_date ('n/a');} 
			if (isset ($ws['moistMinYday'][$i]) )        { echo generate_value ($ws['moistMinYday'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMinYdayTime'][$i]) )    { echo generate_date  ($ws['moistMinYdayTime'][$i],'time');}  else { echo generate_date ('n/a');} 
			if (isset ($ws['moistMinMonth'][$i]) )       { echo generate_value ($ws['moistMinMonth'][$i],$uom);}       else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMinMonthTime'][$i]) )   { echo generate_date  ($ws['moistMinMonthTime'][$i],'month');} else { echo generate_date ('n/a');} 
			if (isset ($ws['moistMinYear'][$i]) )        { echo generate_value ($ws['moistMinYear'][$i],$uom);}        else { echo generate_value ('n/a');} 
			if (isset ($ws['moistMinYearTime'][$i]) )    { echo generate_date  ($ws['moistMinYearTime'][$i],'month');}  else { echo generate_date ('n/a');} 
			if ($show_alltime) {
				if (isset ($ws['moistMinAll'][$i]) )         { echo generate_value ($ws['moistMinAll'][$i],$uom);}            else { echo generate_value ('n/a');}
				if (isset ($ws['moistMinAllTime'][$i]) )     { echo generate_date  ($ws['moistMinAllTime'][$i],'date');}      else { echo generate_date ('n/a');}      
			}
			echo '</tr>'.PHP_EOL;
		}else { echo '<tr><td colspan="'.$colspan.'">&nbsp;</td></tr>'.PHP_EOL; }
        } // eo loop 4 sensors     

} // eo soil
?>
</tbody>
</table>
</div>
<?php
function generate_value ($value,$uom='', $style='') {
        global  $no_value, $decimals;
        if ($style == '') {
                $style = ' style="text-align: right;" ';}
        else  { $style = ' style="text-align: left;" ';}
        
        $from   = array('n/a','---', '--');
        $repl   = trim(str_replace ($from, '', $value));
        if ($repl <> '') {
                return  '<td'.$style.'>'.wsNumber($value,$decimals).$uom.'</td>'; }
        else {  return  '<td'.$style.'>'.$no_value.'</td>'; }
}
function generate_date ($value,$date_time='time', $style='') {
        global $no_value, $timeOnlyFormat, $dateOnlyFormat, $dateMDFormat;
        if ($date_time == 'date')       {$format = $dateOnlyFormat;} 
        elseif ($date_time == 'month')  {$format = $dateMDFormat;} 
        else                            {$format = $timeOnlyFormat;}
        global  $no_value; 
        $from   = array('n/a','---', '--');
        $repl   = trim(str_replace ($from, '', $value));
        if ($repl <> '') {
                return  '<td style="text-align: left;"><small>&nbsp;('.string_date($value,$format).')</small></td>'; }
        else {  return  '<td style="text-align: left;"><small>&nbsp;'.$no_value.'</small></td>'; }
}
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 

