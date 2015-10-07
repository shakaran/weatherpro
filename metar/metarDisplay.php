<?php
#---------------------------SETTINGS         -----------------------------------
# Default we use the file with multiple locations as used for the foreecasts
#
#$SITE['multi_forecast'] = false;
#
# But removing the comment mark on the line above we use our own list generated at the Saratoga site.
#
if (!$SITE['multi_forecast']) { 
        $MetarList = array( // set this list to your local METARs 
 // Metar(ICAO) | Name of station | dist-mi | dist-km | direction |
'EBBR|Brussels International Airport, Belgium|9|14|W|', // lat=50.9000,long=4.5000, elev=58, dated=26-APR-06
'EBBE|Beauvechain (Bevekom), Belgium|10|17|SSE|',       // lat=50.7500,long=4.7667, elev=127, dated=26-APR-06
'EBDT|Schaffen, Belgium|18|29|ENE|',                    // lat=51.0167,long=5.0667, elev=54, dated=26-APR-06
'EBAW|Antwerp/Deurne, Belgium|23|38|NNW|',              // lat=51.2000,long=4.4667, elev=14, dated=26-APR-06
'EBCI|Charleroi/Gosselies, Belgium|32|51|SSW|',         // lat=50.4667,long=4.4500, elev=192, dated=26-APR-06
'EBLG|Bierset/Liege, Belgium|38|60|ESE|',               // lat=50.6333,long=5.4500, elev=178, dated=26-APR-06
// list generated Mon, 09-Jan-2012 12:48am PST at http://saratoga-weather.org/wxtemplates/find-metar.php
        );
}
#--------------------------- END OF SETTINGS         ---------------------------
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'metarDisplay.php';
$pageVersion	= '3.20 2015-09-01';
#-------------------------------------------------------------------------------
# 3.20 2015-09-01 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
# Display a table with metar conditions
#-------------------------------------------------------------------------------
$condIconDir	= $SITE['imgAjaxDir'];
$cacheFileDir	= $SITE['cacheFileDir'] = $SITE['cacheDir'];
$condIconType 	= '.gif';
// optional settings for the Wind Rose graphic in ajaxwindiconwr as wrName . winddir . wrType
$wrName         = 'wr-';       // first part of the graphic filename (followed by winddir to complete it)
$wrType         = '.png';      // extension of the graphic filename
$wrHeight       = '58';        // windrose graphic height=
$wrWidth        = '58';        // windrose graphic width=
$time 	        = time(); 
$mtrLang        = $SITE['lang'];
// english also own wr  2012-04-23
//if ($mtrLang <> 'en') {$wrName.=$mtrLang.'-';}

$wrName         .=$mtrLang.'-';
$wrCalm          = $wrName.'calm.png';
$maxCols        = 3;

if (!isset ($MetarList) || !is_array ($MetarList) ) {   // we use the multiforecast file
        $MetarList      = array();
        $selection_file = $SITE["multi_fct_keys"];
        $arr            = file($selection_file);
        $end            = count ($arr);
        for ($n = 0; $n < $end; $n++) {
                $string = trim($arr[$n]);               // |51.2603851|4.3577201|Antwerp   |EBAW |966591|xx  |
                if ($string == '') {continue;}
                if (substr($string,0,1) == '#')         
                        {continue;}     // skip comments
                list ($none0,$none1,$none2,$name,$metar,$none5,$none6) = explode ('|',$string.'||||');
                $metar  = trim ($metar);
                $name   = trim ($name);
                if ($name == '' || $metar == '')       {continue;}      // skip lines with empty fileds
                $MetarList[]    = $metar.'|'.$name.'|||||'; // 'EBBR|Brussels International Airport, Belgium|9|14|W|',
        }
}
$maxAge         = 75*60;
$folder         = 'metar/';
if (!function_exists ('mtr_conditions') ) { 
        $script         = $folder.'wsMetarTxt.php';
        ws_message ( '<!-- module metarDisplay.php ('.__LINE__.'): loading '.$script.' -->');
        include $script; 
}
#
$toDisplay = array(  // keys in $mtr array to display (if they exist) and in this order
        'conditions'    => 'Summary',
        'wind_speed_kt' => 'Wind', 
        'temp'          => 'Temperature',
        'windchill_c'   => 'Wind Chill',
        'heatindex_c'   => 'Heat Index',
        'dewpoint_c'    => 'Dew Point',
        'humidity'      => 'Humidity',
        'slp_hpa'       => 'Barometer',
        'covers_max'    => 'Clouds',
        'visibility_sm' => 'Visibility',
        'covers'        => 'Cloud details'
        
);
# Get translation values if available
foreach ($toDisplay as $key => $val) {
	$toDisplay[$key] = langtransstr($val);
}
$thisCol        = 0;
$col_width      = round(100 / $maxCols, 4);
echo '<!--  metardisplay -->
<div class="blockDiv">
<h3 class="blockHead">'.langtransstr('Nearby METAR Reports').'</h3>
<table class="genericTable">
<tr>';
for ($n1 = 0; $n1 < $maxCols; $n1++) {
        echo '<th style="width: '.$col_width.'%;">&nbsp;</th>';
}
echo '</tr>
<tr>'.PHP_EOL;

foreach ($MetarList as $idx => $Mrec) {
	list($mtrICAO,$mtrName,$mtrDistanceM,$mtrDistanceK,$direction) = explode('|',$Mrec.'|||||');
	if($mtrICAO == '') { continue;}
	
	$mtr   = mtr_conditions($mtrICAO);
	if ($mtr == false) {
	        continue;
	        ws_message ( '<!-- module metarDisplay.php ('.__LINE__.'): Invalid METAR: '.$mtrICAO.', skipped -->',true);
	}
	$metarUpdated = date($SITE['timeFormat'],strtotime($mtr['time']) );
/* now $mtr array contains the decoded values
    [metar_raw] => KAVP 011107Z 13003KT 4SM BR BKN023 18/17 A3010 RMK AO2 T01830167
    [metar_cleaned] => KAVP 011107Z 13003KT 4SM BR BKN023 18/17 A3010 RMK AO2 T01830167
    [station_id] => KAVP
    [errors] => Array  ()
    [time] => 2015-09-01T11:07:00 UTC
    [age] => 1643
    [max-icon] => 350
    [wind_dir] => 130
    [wind_speed_kt] => 03
    [wind_unit_org] => kts
    [wind_speed_org] => 03
    [visibility_prefix] => 
    [visibility_sm] => 4
    [visibility_org] => 4
    [visibility_unit] => mi
    [conditions] => Array ([0] => Mist)
    [conditions_codes] => Array ( [0] => BR)
    [covers] => Array ( [0] => Array ( [txt] => Mostly Cloudy [height] => 2300))
    [covers_max] => Mostly Cloudy
    [temp] => 18
    [dewpoint_c] => 17
    [humidity] => 94
    [slp_hpa] => 1019.3
    [slp_inhg] => 30.10
    [icon_url] => wsIcons/default_icons/350.png  */
	foreach ($toDisplay as $key => $legend) {   	
                $clean  = array ('covers');
                $extra  = $long_text    ='';
	        if (in_array($key, $clean) && isset ($mtr[$key]) ) {
	                $end     = count ($mtr[$key]);
	                for ($n1 = 0; $n1 < $end; $n1++) {
	                        $text   = $mtr[$key][$n1]['txt'];
	                        $height = $mtr[$key][$n1]['height'];
	                        $text   = langtransstr($text);
	                        if ($height <> '') {
	                                $height =  round( wsConvertDistance($height, 'ft', $SITE["uomHeight"]), $SITE["decHeight"]);
	                        }
	                        $long_text     .= $extra.$text.' '.$height.' '.$SITE["uomHeight"];
	                        $extra          = ',<br /> ';
	                }
	                $mtr[ $key.'_text']      = $long_text;
		        ws_message (  '<!-- module metarDisplay.php ('.__LINE__.'):'. ' '.$key.'  = '.$long_text.' -->');     
	        } // eo covers
                $clean = array ('conditions', 'covers_max' );
                $extra  = $long_text    = '';
	        if (in_array($key, $clean) ) {
	                if (isset ($mtr['conditions_text']) ) {continue;}
                        if (isset ($mtr['conditions']) && $mtr['conditions'] <> '' ) {  #$key == 'conditions') {
                                $end     = count ($mtr['conditions']);
                                for ($n1 = 0; $n1 < $end; $n1++) {
                                        $text           = langtransstr($mtr['conditions'][$n1]);
                                        $long_text     .= $extra.$text;
                                        $extra          = ', ';
                                }
                        }
	                if (isset ($mtr['covers_max']) && $mtr['covers_max'] <> '') {
	                        $text           = langtransstr($mtr['covers_max']);
	                        $long_text     .= $extra.$text;
	                }
	                $mtr['conditions_text']      = $long_text;
		        ws_message (  '<!-- module metarDisplay.php ('.__LINE__.'):'. ' '.$key.'  = '.$long_text.' '.$mtr['conditions_text'] .' -->');     
	        
	        }  // eo conditions
                # $result['wind_speed_kt'] = 0;
                # $result['wind_dir']     = 'varies';
	        if ($key == 'wind_speed_kt') {
	                $long_text      = $legend.': ';
	                if (isset ($mtr['wind_dir'])  ) {
	                        if ($mtr['wind_dir'] <> 'varies') {
	                                $dirlabel       =  wsConvertWinddir ($mtr['wind_dir']);
	                                $dir            = langtransstr ( $dirlabel );
	                                $long_text      .= $dir.' ';
	                        }
	                        else {  $long_text      .= langtransstr ('Varies').' ';
	                                $dirlabel       = 'calm';
	                                
	                        }
	                } // eo wind
	                if (isset ($mtr['wind_speed_kt']) && $mtr['wind_speed_kt'] <> 0) {
	                        $speed  = round ( wsConvertWindspeed($mtr['wind_speed_kt'], 'kts', $SITE["uomWind"])  , $SITE["decWind"]);
                                $long_text      .= $speed.$SITE["uomWind"];
	                }
	                else {  $long_text      .= langtransstr ('Calm').' ';
	                        $dirlabel       = 'calm';
	                } // eo wind_speed
	                 if (isset ($mtr['gust_speed']) ) {
	                        $speed  = round ( wsConvertWindspeed($mtr['wind_speed_kt'], 'kts', $SITE["uomWind"])  , $SITE["decWind"]);
	                        $long_text      .= '<br />'.langtransstr ('Gust to').' '.$speed.$SITE["uomWind"];
	                 } // eo gust
	                 $mtr['wind_text']      = $long_text;
	                 ws_message (  '<!-- module metarDisplay.php ('.__LINE__.'):'. ' '.$key.'  = '.$long_text.' -->');   
	        } // eo wind

	}  // end clean up, translate etc
ws_message (  '<!-- module metarDisplay.php ('.__LINE__.'):'.print_r($mtr,true). '  -->');
	// time to format the output for display
	if($thisCol >= $maxCols) {
		echo '</tr>'.PHP_EOL;
		echo '<tr><td colspan="'.$maxCols.'"></td></tr>'.PHP_EOL;
		echo '<tr>'.PHP_EOL;
		$thisCol = 0;
	}	
        echo '<td style="vertical-align:top; text-align: center; ">
<table  class="genericTable">
  <tr class="row-dark"><th colspan="2" class="blockHead">'.$mtrICAO.' - '.$mtrName; 
	if($mtrDistanceM <> '' and $mtrDistanceK <> '') {
		echo    '<br/>'.langtransstr('Distance from station').': ';
		if($direction <> '') {
			echo ' '.langtransstr($direction).' ';
		}
		if(isset($SITE['uomDistance']) and preg_match('|mi|i',$SITE['uomDistance'])) {
			echo $mtrDistanceM.$SITE['uomDistance'];
		}
		else {  echo $mtrDistanceK.$SITE['uomDistance'];
		}
	 } 
	 echo '</th></tr>
  <tr><td colspan="2" style="text-align: center;border: none">'.langtransstr('Updated') . ': '.$metarUpdated;
	if($mtr['age'] > $maxAge) {
	        echo '<br/><span style="color: red"><b>'.langtransstr('NOT Current').'</b></span>'.PHP_EOL;
	}
	echo '</td></tr>
  <tr><td class="" style="vertical-align:top; text-align: right; border: none; padding-right: 5px; width:50%;">';
	$text   =  '';  
	if (isset ($mtr[ 'conditions_text']) ) {
	        $text  .= $mtr[ 'conditions_text'];
	}
        echo '
          <img src="'.$mtr['icon_url'].'" alt="'.$text.'" title="'.$text.'" style="height: '.$wrHeight.'px; " />&nbsp;&nbsp;<br/><b>'.$text.'&nbsp;&nbsp;</b></td>
      <td class="" style="vertical-align:top; text-align: left; padding-left: 5px; border: none">&nbsp;&nbsp;';
        if(isset($mtr['wind_text'])) { 
		$wr     = $condIconDir . $wrName . $dirlabel . $wrType; // normal wind rose
		$wrtext = $mtr['wind_text'];
		echo '
          <img src="'.$wr.'" style="height: '.$wrHeight.'px; width: '.$wrWidth.'px; text-align:center;" ';
		
		echo ' title="'.$wrtext.'" alt="'.$wrtext.'" /><br/> <span style="">'.$wrtext.'</span>';
        } 
        else {  echo "&nbsp;"; 
	} 
	echo '</td></tr>'.PHP_EOL;
        $td_left        = '  <tr><td style="text-align: right; padding-right: 5px;">';
        $td_right       = '      <td style="text-align: left; padding-left: 5px; font-weight: bold;">';
        foreach ($toDisplay as $key => $legend) {
                switch ($key) {
                        case 'windchill_c': 
                        case 'heatindex_c';
                        case 'dewpoint_c';
                        case 'temp':
                                if (!isset ($mtr[$key]) ) {break;}
                                echo $td_left.$legend.':</td>';
                                $value   = round(wsConvertTemperature($mtr[$key],'c',$SITE["uomTemp"]), $SITE["decTemp"]);
                                echo $td_right.$value.$SITE["uomTemp"].'</td></tr>'.PHP_EOL;
                                break;
                        case 'humidity':
                                if (!isset ($mtr[$key]) ) {break;}
                                echo $td_left.$legend.':</td>'.$td_right.$mtr[$key].'%</td></tr>'.PHP_EOL;
                                break;
                        case 'slp_hpa':
                                if (!isset ($mtr[$key]) ) {break;}
                                echo $td_left.$legend.':</td>';
                                $value   = round(wsConvertBaro($mtr[$key],'hpa',$SITE["uomBaro"]), $SITE["decBaro"]);
                                echo $td_right.$value.$SITE["uomBaro"].'</td></tr>'.PHP_EOL;
                                break;
                        case 'covers_max':
                                if (!isset ($mtr[$key]) ) {break;}
                                echo $td_left.$legend.':</td>';
                                echo $td_right.$mtr[$key].'</td></tr>'.PHP_EOL;
                                break;
                        case 'covers':
                                if (!isset ($mtr[$key]) ) {break;}
                                echo $td_left.$legend.':</td>';
                        
                                echo $td_right.$mtr['covers_text'].'</td></tr>'.PHP_EOL;
                                break;
                        case 'visibility_sm':
                                if (!isset ($mtr[$key]) ) {break;}
                                $value  = $mtr[$key];
                                $prefix = '';
                                if (isset($mtr['visibility_prefix']) && $mtr['visibility_prefix'] <> '') {
                                        $prefix = langtransstr($mtr['visibility_prefix']).' ';
                                }
                                echo $td_left.$legend.':</td>';
                                $value  = round(wsConvertDistance($mtr[$key],'mi',$SITE["uomDistance"]), $SITE["decDistance"]);
                                echo $td_right.$prefix.$value.$SITE["uomDistance"].'</td></tr>'.PHP_EOL;
                                break;
                        default:
                                break;         
                }   	 // eo switch
        }  // eo foreach
        print '  <tr><td colspan="2"><small>'.$mtr['metar_raw'].'</small></td></tr>
</table>
</td>'.PHP_EOL;
        $thisCol++;
} // end of metar loop
for ($n = $thisCol; $n < $maxCols; $n++) {
	echo '<td>&nbsp;</td>';
}
?></tr>
</table>
<br />
<h3 class="blockHead">
<small><?php langtrans('Original script by'); ?>&nbsp;
<a href="http://saratoga-weather.org/wxtemplates/find-metar.php#findmetar" target="_blank">Saratoga-weather.org</a>
<?php langtrans('Adapted for the template by'); ?>&nbsp;
<a href="http://leuven-template.eu/" target="_blank">Weerstation Leuven</a>
</small>
</h3>
</div>
<!-- end metardisplay -->
<?php
# ----------------------  version history
# 3.20 2015-09-01 release 2.8 version 
