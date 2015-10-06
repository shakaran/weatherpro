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
$pageName	= 'wsZambretti.php';
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
$page_title     = 'Zambretti '.langtransstr('forecast');

$uom_baro       = strtolower(trim ( $SITE['uomBaro'] ) );
$baro_now       = $ws['baroAct'];       // $ws['baroAct'] 		= wsConvertBaro('1020.5', $from);
$baro_max       = $ws['baroMaxYear'];
$baro_min       = $ws['baroMinYear'];
if ($uom_baro <> 'hpa') {
        $baro_now       =  wsConvertBaro($baro_now, $uom_baro, 'hpa'); 
        $baro_max       =  wsConvertBaro($baro_max, $uom_baro, 'hpa'); 
        $baro_min       =  wsConvertBaro($baro_min, $uom_baro, 'hpa'); 
}
$wind_desc      = $ws['windActDsc'];
$baro_trend     = 1*$ws['baroDelta'];
if      ($baro_trend > 0)       {$baro_trend = 1; $baro_trend_txt = langtransstr('Rising');}      //  0 = no change, 1= rise, 2 = fall
elseif  ($baro_trend < 0)       {$baro_trend = 2; $baro_trend_txt = langtransstr('Falling');}
else                            {$baro_trend_txt = langtransstr('No change');}      
if ($SITE['latitude'] < 0 )     {$hemisphere = '2'; $hemisphere_txt = langtransstr('Southern');}  
else                            {$hemisphere = '1'; $hemisphere_txt = langtransstr('Northern');}     // Northern = 1 or Southern = 2 hemisphere
$month          = date('n',time());     // 1 - 12

$forecast_params= 
langtransstr('Forecast calculated using').': '.
langtransstr('Pressure').': '.$baro_now.' hPa - '.
langtransstr('Trend').': '.$baro_trend. ' ('.$baro_trend_txt.') - '.
langtransstr('max').': '.$baro_max.' - '.
langtransstr('min').': '.$baro_min.'<br />'.
langtransstr('Wind').' '.langtransstr('from').': '.$wind_desc.' (English)<br />'.
langtransstr('Month').': '.$month.'. '.
langtransstr('Hemisphere').': '.$hemisphere.' ('.$hemisphere_txt.'). ';

$zam_forecast   = betel_cast( $baro_now, $month, $wind_desc, $baro_trend, $hemisphere, $baro_max, $baro_min) ;
$zam_forecast   = iconv('UTF-8',$SITE['charset'],$zam_forecast);

echo '<!-- zambretti forecast -->
<div class="blockDiv">
<h3 class="blockHead">'.$page_title.'
<a href="javascript:zamclick()">
<img src="./img/i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;"></a>
<script type="text/javascript">
  function zamclick() {
        hideshow(document.getElementById("zamExtra"))
        }
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>
</h3>
<p style="text-align: center; min-height: 1em;">'.$zam_forecast.'</p>
<div id="zamExtra" style="display: none;">
<hr /><p style="text-align: center; min-height: 1em;">'.$forecast_params.'</p><hr />
<div>
<table class="genericTable"><tr>
<td><img src="img/zamb3.jpg" alt="Zambetti" style="max-width: 300px;"></td>
<td>'.iconv('UTF-8',$SITE['charset'],zam_explains ()).'
<a href="http://www.meteormetrics.com/zambretti.htm" target="_blank">meteormetrics</a>
</td></tr></table>
</div>
<h3 class="blockHead">
The original idea for a program like this, comes from 
<a href="http://www.beteljuice.co.uk/zambretti/forecast.html" target ="_blank">beteljuice</a> 
and operates in different incarnations since 2008.</h3>
</div>
</div>
<!-- end of Zambretti forecast -->'.PHP_EOL;
#
// beteljuice.com - near enough Zambretti Algorhithm 
// June 2008 - v1.0
//
//Converted Beleljuice's Javascript to php function- PM May 2010
//just include this file in your php script
//usage for php function same as js- see below
//
/* Negretti and Zambras 'slide rule' is supposed to be better than 90% accurate 
for a local forecast upto 12 hrs, it is most accurate in the temperate zones and about 09:00  hrs local solar time.
I hope I have been able to 'tweak it' a little better ;-)	
This code is free to use and redistribute as long as NO CHARGE is EVER made for its use or output
*/
// ---- 'environment' variables ------------
//$z_where  Northern = 1 or Southern = 2 hemisphere
//$z_baro_top  upper limits of your local 'weather window' (1050.0 hPa for UK)
//$z_baro_bottom	 lower limits of your local 'weather window' (950.0 hPa for UK)
// usage:   forecast = betel_cast( $z_hpa, $z_month, $z_wind, $z_trend [, $z_where] [, $z_baro_top] [, $z_baro_bottom]);
// $z_hpa is Sea Level Adjusted (Relative) barometer in hPa or mB
// $z_month is current month as a number between 1 to 12
// $z_wind is English windrose cardinal eg. N, NNW, NW etc.
// NB. if calm a 'nonsense' value should be sent as $z_wind (direction) eg. 1 or calm !
// $z_trend is barometer trend: 0 = no change, 1= rise, 2 = fall
// $z_where - OPTIONAL for posting with form
// $z_baro_top - OPTIONAL for posting with form
// $z_baro_bottom - OPTIONAL for posting with form
// a short forecast text is returned

function betel_cast( $z_hpa, $z_month, $z_wind, $z_trend, $z_where = 1, $z_baro_top = 1050, $z_baro_bottom = 950) 
{       $z_forecast = zam_texts ();
        // equivalents of Zambretti 'dial window' letters A - Z
        $rise_options   = Array(25,25,25,24,24,19,16,12,11,9,8,6,5,2,1,1,0,0,0,0,0,0) ; 
        $steady_options = Array(25,25,25,25,25,25,23,23,22,18,15,13,10,4,1,1,0,0,0,0,0,0) ; 
        $fall_options   = Array(25,25,25,25,25,25,25,25,23,23,21,20,17,14,7,3,1,1,1,0,0,0) ; 
	$z_range        = $z_baro_top - $z_baro_bottom;
	$z_constant     = round(($z_range / 22), 3); 
	$z_season       = (($z_month >= 4) && ($z_month <= 9)) ; 	// true if 'Summer'
	if ($z_where == 1) {  		// North hemisphere
		if ($z_wind == "N") {  
			$z_hpa += 6 / 100 * $z_range ;  
		} else if ($z_wind == "NNE") {  
			$z_hpa += 5 / 100 * $z_range ;  
		} else if ($z_wind == "NE") {  
//			$z_hpa += 4 ;  
			$z_hpa += 5 / 100 * $z_range ;  
		} else if ($z_wind == "ENE") {  
			$z_hpa += 2 / 100 * $z_range ;  
		} else if ($z_wind == "E") {  
			$z_hpa -= 0.5 / 100 * $z_range ;  
		} else if ($z_wind == "ESE") {  
//			$z_hpa -= 3 ;  
			$z_hpa -= 2 / 100 * $z_range ;  
		} else if ($z_wind == "SE") {  
			$z_hpa -= 5 / 100 * $z_range ;  
		} else if ($z_wind == "SSE") {  
			$z_hpa -= 8.5 / 100 * $z_range ;  
		} else if ($z_wind == "S") {  
//			$z_hpa -= 11 ;  
			$z_hpa -= 12 / 100 * $z_range ;  
		} else if ($z_wind == "SSW") {  
			$z_hpa -= 10 / 100 * $z_range ;  //
		} else if ($z_wind == "SW") {  
			$z_hpa -= 6 / 100 * $z_range ;  
		} else if ($z_wind == "WSW") {  
			$z_hpa -= 4.5 / 100 * $z_range ;  //
		} else if ($z_wind == "W") {  
			$z_hpa -= 3 / 100 * $z_range ;  
		} else if ($z_wind == "WNW") {  
			$z_hpa -= 0.5 / 100 * $z_range ;  
		}else if ($z_wind == "NW") {  
			$z_hpa += 1.5 / 100 * $z_range ;  
		} else if ($z_wind == "NNW") {  
			$z_hpa += 3 / 100 * $z_range ;  
		} 
		if ($z_season == TRUE) {  	// if Summer
			if ($z_trend == 1) {  	// rising
				$z_hpa += 7 / 100 * $z_range;  
			} else if ($z_trend == 2) {  //	falling
				$z_hpa -= 7 / 100 * $z_range; 
			} 
		} 
	} else {  	// must be South hemisphere
		if ($z_wind == "S") {  
			$z_hpa += 6 / 100 * $z_range ;  
		} else if ($z_wind == "SSW") {  
			$z_hpa += 5 / 100 * $z_range ;  
		} else if ($z_wind == "SW") {  
//			$z_hpa += 4 ;  
			$z_hpa += 5 / 100 * $z_range ;  
		} else if ($z_wind == "WSW") {  
			$z_hpa += 2 / 100 * $z_range ;  
		} else if ($z_wind == "W") {  
			$z_hpa -= 0.5 / 100 * $z_range ;  
		} else if ($z_wind == "WNW") {  
//			$z_hpa -= 3 ;  
			$z_hpa -= 2 / 100 * $z_range ;  
		} else if ($z_wind == "NW") {  
			$z_hpa -= 5 / 100 * $z_range ;  
		} else if ($z_wind == "NNW") {  
			$z_hpa -= 8.5 / 100 * $z_range ;  
		} else if ($z_wind == "N") {  
//			$z_hpa -= 11 ;  
			$z_hpa -= 12 / 100 * $z_range ;  
		} else if ($z_wind == "NNE") {  
			$z_hpa -= 10 / 100 * $z_range ;  //
		} else if ($z_wind == "NE") {  
			$z_hpa -= 6 / 100 * $z_range ;  
		} else if ($z_wind == "ENE") {  
			$z_hpa -= 4.5 / 100 * $z_range ;  //
		} else if ($z_wind == "E") {  
			$z_hpa -= 3 / 100 * $z_range ;  
		} else if ($z_wind == "ESE") {  
			$z_hpa -= 0.5 / 100 * $z_range ;  
		}else if ($z_wind == "SE") {  
			$z_hpa += 1.5 / 100 * $z_range ;  
		} else if ($z_wind == "SSE") {  
			$z_hpa += 3 / 100 * $z_range ;  
		} 
		if ($z_season == FALSE) { 	// if Winter
			if ($z_trend == 1) {  // rising
				$z_hpa += 7 / 100 * $z_range;  
			} else if ($z_trend == 2) {  // falling
				$z_hpa -= 7 / 100 * $z_range; 
			} 
		} 
	} 	// END North / South
	if($z_hpa == $z_baro_top) {$z_hpa = $z_baro_top - 1;}
	$z_option = floor(($z_hpa - $z_baro_bottom) / $z_constant); 
 	$z_output = "";
	if($z_option < 0) {
		$z_option = 0;
		$z_output = "Exceptional Weather, ";
	}
	if($z_option > 21) {
		$z_option = 21;
		$z_output = "Exceptional Weather, ";
	}
	if ($z_trend == 1) {
		$z_output .= $z_forecast[$rise_options[$z_option]] ; 
	} else if ($z_trend == 2) { 
		$z_output .= $z_forecast[$fall_options[$z_option]] ; 
	} else { 
		$z_output .= $z_forecast[$steady_options[$z_option]] ; 
	} 
	return ($z_output) ; 
}	// END function   
function zam_texts () {
        global $lang;
        switch ($lang) {
                case 'nl':        
                        $z_forecast     = array("Blijvend mooi weer", "Mooi weer", "het wordt mooier weer", "Mooi weer, wordt minder", "mooi weer, mogelijk buien", "Vrij goed weer, het wordt nog beter ", "Vrij goed weer, eerst mogelijk wat buien", "Vrij goed weer, later wat buien "," Eeerst wat buien, daarna beter weer "," Veranderlijk weer, wordt beter "," Vrij goed weer, buien waarschijnlijk "," Nogal onrustig weer, wat helderder later "," Veranderlijk, later beter "," Buien, heldere tussenpozen "," Buien, steeds veranderlijker weer"," Veranderlijk, wat neerslag "," Onbestendig weer, korte tussenpozen met beter weer "," Onbestendig, later neerslag "," Onbestendig, wat neerslag "," Meestal erg onrustig "," Af en toe neerslag, weer wordt slechter "," Soms wat neerslag , zeer onrustig "," Met regelmatige tussenpozen wat neerslag","Veel neerslag, heel onrustig weer"," Stormachtig, kan verbeteren "," Stormachtig, veel neerslag ");
                break;
                case 'en':        
                        $z_forecast     = array("Settled fine", "Fine weather", "Becoming fine", "Fine, becoming less settled", "Fine, possible showers", "Fairly fine, improving", "Fairly fine, possible showers early", "Fairly fine, showery later", "Showery early, improving", "Changeable, mending", "Fairly fine, showers likely", "Rather unsettled clearing later", "Unsettled, probably improving", "Showery, bright intervals", "Showery, becoming less settled", "Changeable, some precipitation", "Unsettled, short fine intervals", "Unsettled, precipitation later", "Unsettled, some precipitation", "Mostly very unsettled", "Occasional precipitation, worsening", "Precipitation at times, very unsettled", "Precipitation at frequent intervals", "Precipitation, very unsettled", "Stormy, may improve", "Stormy, much precipitation"); 
                break;
                case 'fr':
                         $z_forecast     = array("Beau temps établi", "Beau temps", "Tendance au beau temps", "Beau temps, tendance à se dégrader", "Beau, averses possibles", "Plutôt beau, en amélioration", "Plutôt beau averses possibles en matinée", "Assez beau, des averses en soirée", "Averses, en amélioration", "Variable, en amélioration", "Plutôt beau, averses probables", "Plutôt perturbé, s'améliorant", "Perturbé, s'améliorant probablement", "Pluies éparses, belles éclaircies", "Averses, temps se découvrant", "Variable, quelques précipitations", "Perturbé, de rares éclairces", "Variable, quelques précipitations tardives", "Variable, quelques précipitations", "Très perturbé", "Précipitations occasionnelles, se dégradant", "Quelques précipitations, très perturbé", "Precipitations à intervalles fréquents", "Pluie, très perturbé", "Tempête, pourrait s'améliorer", "Tempête, fortes pluies" ); 
                break;
                default:
                        $z_forecast     = array("Settled fine", "Fine weather", "Becoming fine", "Fine, becoming less settled", "Fine, possible showers", "Fairly fine, improving", "Fairly fine, possible showers early", "Fairly fine, showery later", "Showery early, improving", "Changeable, mending", "Fairly fine, showers likely", "Rather unsettled clearing later", "Unsettled, probably improving", "Showery, bright intervals", "Showery, becoming less settled", "Changeable, some precipitation", "Unsettled, short fine intervals", "Unsettled, precipitation later", "Unsettled, some precipitation", "Mostly very unsettled", "Occasional precipitation, worsening", "Precipitation at times, very unsettled", "Precipitation at frequent intervals", "Precipitation, very unsettled", "Stormy, may improve", "Stormy, much precipitation");                 

        }
        return $z_forecast;
}
function zam_explains () {
        global $lang;
        switch ($lang) {
                case 'nl':
                        $text = 
'Dit programma probeert "hetzelfde resultaat te voorspellen", zoals bij het gebruik van de 
<br /><br /> <b> 1915 Negretti en Zambra (Zambretti) Forecaster </b>.
<br /><br /> De originele papieren voorspeller is ontworpen voor het noordelijk halfrond en de weers omstandigheden in de UK. 
Dat wil zeggen: Hoe dichterbij de Polen hoe groter de "spread" tussen de onderste en bovenste barometer niveaus en 
grote "trends" zijn dan nodig om de weersomstandigheden te laten veranderen. 

<br /><br />Omgekeerd, hoe dichter bij de evenaar, hoe kleiner de "spread" en kleinere "trends" zijn dan nodig voor verandering. 
<br /><br />Aan de linkerkant een foto van de originele weersvoorspelling disc. 
<br /><br />Een verwachting gemaakt rond ongeveer 09:00 lokale zonnetijd is naar verluidt meer dan 90% nauwkeurig! - 
Ook al houdt het geen rekening met snelheid van barometer verandering, windsnelheid of temperatuur.
<br /><br />Meer achtergrond informatie op:';
                break;
                case 'en':
                        $text =
'This program tries to get a "same result forecast" as when using the 
<br /><br /><b>1915 Negretti and Zambra (Zambretti) Forecaster</b>.
<br /><br />The original paper forecaster was designed for the Northern Hemisphere and UK "Weather Range". ie. 
The nearer the Poles the greater the spread between lower and upper barometer levels 
and large "trends" are needed for weather conditions change. 

<br /><br />Conversely, the nearer the Equator the smaller the "spread" and smaller "trends" for change.
<br /><br />On the left a picture of the original weather prediction disc. 
<br /><br />A forecast at approx. 09:00 local Solar Time is allegedly better than 90% accurate ! - 
even though it takes no account of rate of barometer change, wind speed or temperature.
<br /><br />More information can be found here:';                        
                break;  
                default:
                        $text =
'This program tries to get a "same result forecast" as when using the 
<br /><br /><b>1915 Negretti and Zambra (Zambretti) Forecaster</b>.
<br /><br />The original paper forecaster was designed for the Northern Hemisphere and UK "Weather Range". ie. 
The nearer the Poles the greater the spread between lower and upper barometer levels 
and large "trends" are needed for weather conditions change. 

<br /><br />Conversely, the nearer the Equator the smaller the "spread" and smaller "trends" for change.
<br /><br />On the left a picture of the original weather prediction disc. 
<br /><br />A forecast at approx. 09:00 local Solar Time is allegedly better than 90% accurate ! - 
even though it takes no account of rate of barometer change, wind speed or temperature.
<br /><br />More information can be found here:';  
        }
        return $text;                        
}
# ----------------------  version history
# 3.20 2015-07-29 release 2.8 version 
