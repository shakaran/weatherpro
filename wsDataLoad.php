<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'wsDataLoad.php';
$pageVersion	= '3.20 2015-07-27';
#-------------------------------------------------------------------------------
# 3.20 2015-07-26 release 2.8 version (incl console)
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
if (isset ($ws['rtTime']) ) {
        if (substr($ws['actTime'],0,12) > substr($ws['rtTime'],0,12) ) {
                $color_strt = '<b ><a style="color: red;" href="./'.$SITE['pages']['statusWeatherSystems'].'">';
                $color_end = ' ?</a></b><!-- '.$ws['actTime'].'-'.$ws['rtTime']. '-->';}
        else  { $color_strt = $color_end = '';
        }
        $extra_time = ' => '.$color_strt.string_date ($ws['rtTime'],$SITE['timeOnlyFormat']).$color_end;
} 
else {$extra_time = '';
}
$string1 	=
"ajaxVars['ajaxindicator']       = '".  langtransstr("Last updated:")." ';
ajaxVars['langPauseMsg']        = '".  	langtransstr('Updates paused - reload page to start')." ';
ajaxVars['ajaxdate']            = '".  	string_date ($ws['actTime'],$SITE['dateOnlyFormat'])."';
ajaxVars['ajaxtime']            = '".  	string_date ($ws['actTime'],$SITE['timeOnlyFormat']).$extra_time."';
ajaxVars['ajaxcounter']         = '0';";

#  ---------------------------  temp related ajax values
$decTemp	= $SITE['decTemp'];
$string1	.="
ajaxVars['ajaxtemp']            = '".  wsNumber ($ws['tempAct'],$decTemp).$uomTemp."';
ajaxVars['ajaxtempDash']        = '".  ws_commontemp_color(wsNumber ($ws['tempAct'],$decTemp))."';
ajaxVars['ajaxtempNoU']         = '".  wsNumber ($ws['tempAct'],$decTemp)."';
ajaxVars['ajaxbigtemp']         = '" .  wsNumber ($ws['tempAct'],0).$uomTemp."';";

$textUP = langtransstr("Warmer").':  %s '.$uomTemp.' '.langtransstr("than last hour").".";
$textDN = langtransstr("Cooler").':  %s '.$uomTemp.' '.langtransstr("than last hour").".";
$text	= wsGenArrow($ws['tempAct'],$ws['tempDelta'],$textUP, $textDN,$decTemp);
$string1.="
ajaxVars['ajaxtemparrow']       = '".$text."';";

$txt = langtransstr('Currently').': '.$ws['tempAct'].', '.langtransstr('Min').': '.$ws['tempMinToday'].', '.langtransstr('Max').': '.$ws['tempMaxToday'];
if ($ws['tempMaxToday'] >=  $ws['tempMaxYday'] ) {$highTemp = $ws['tempMaxToday'];} else {$highTemp = $ws['tempMaxYday'];}
if ($ws['tempMinToday'] <=  $ws['tempMinYday'] ) {$lowTemp  = $ws['tempMinToday'];} else {$lowTemp  = $ws['tempMinYday'];}
if ($SITE['uomTemp'] == '&deg;C') {$uom = 'C';} else {$uom = 'F';}
$string1.="
ajaxVars['ajaxthermometer']     = '<img class=\"colImgThermo\" src=\"".$SITE['imgDir']."thermometer.php?t=".$ws['tempAct']."&amp;tmin=".$lowTemp."&amp;tmax=".$highTemp."&amp;uom=".$uom."\"  alt=\"".$txt."\" title=\"".$txt. "\"/>';";

list($feelslike,$heatcolourword) = wsFeelslikeTemp($ws['tempAct'], $ws['chilAct'], $ws['heatAct'] ,$uomTemp);
$string1.="
ajaxVars['ajaxheatcolorword']   = '".	$heatcolourword."';
ajaxVars['ajaxfeelslike']       = '".	$feelslike . $uomTemp."';
ajaxVars['ajaxfeelslikeNoU']    = '".	 $feelslike ."';		
ajaxVars['ajaxtempmax']         = '".	wsNumber ($ws['tempMaxToday'],$decTemp).$uomTemp."';
ajaxVars['ajaxtempmin']         = '".	wsNumber ($ws['tempMinToday'],$decTemp).$uomTemp."';
ajaxVars['ajaxtempmaxTime']     = '".	string_date($ws['tempMaxTodayTime'],$SITE['timeOnlyFormat'])."';
ajaxVars['ajaxtempminTime']     = '".	string_date($ws['tempMinTodayTime'],$SITE['timeOnlyFormat'])."';";

#   Dewpoint  items
$textUP = langtransstr("Increased by").':  %s '.$uomTemp.' '.langtransstr("the last hour").".";
$textDN = langtransstr("Decreased by").':  %s '.$uomTemp.' '.langtransstr("the last hour").".";
$text	= wsGenArrow($ws['dewpAct'], $ws['dewpDelta'], $textUP, $textDN, $decTemp);

$string1.="
ajaxVars['ajaxdew']             = '".	wsNumber ($ws['dewpAct'],$decTemp).$uomTemp."';
ajaxVars['ajaxdewNoU']          = '".	wsNumber ($ws['dewpAct'],$decTemp)."';
ajaxVars['ajaxdewarrow']        = '".	$text."';";

#  ---------------------------  Current condition items
$condDescAlt    = str_replace('<br />','-',$condDesc);
$conditionicon  = '<img class="colImgCCN" src="'.$ccnIconUrl.'" alt="'.$condDescAlt.'" title="'.$condDescAlt.'"/>';
$conditioniconM = '<img class="colimg"    src="'.$ccnIconUrl.'" alt="'.$condDescAlt.'" title="'.$condDescAlt.'" style ="width: 26px; height: 26px;"/>';
$string1.="
ajaxVars['ajaxconditionicon']   = '". $conditionicon."';
ajaxVars['ajaxconditioniconMobi'] = '". $conditioniconM."';
ajaxVars['ajaxcurrentcond']     = '". $condDesc."';
ajaxVars['ajaxcurrentcondalt']  = '". $condDescAlt."';";

#  ---------------------------  Rain items
$unit 		= ' '.langtransstr(trim($uomRain));
$decPrecip	= $SITE['decPrecip'];
$string1.="
ajaxVars['ajaxrain']            = '". 	wsNumber ($ws['rainToday'],$decPrecip).$unit."';
ajaxVars['ajaxrainNoU']         = '". 	wsNumber ($ws['rainToday'],$decPrecip)."';
ajaxVars['ajaxrainrateNoU']     = '". 	wsNumber ($ws['rainRateAct'],$decPrecip)."';";

if (isset($ws['rainHour']) ) {
	$string1.="
ajaxVars['ajaxrainratehrNoU']   = '".   wsNumber ($ws['rainHour'],$decPrecip)."';	
ajaxVars['ajaxrainratehr']      = '".   wsNumber ($ws['rainHour'],$decPrecip).$unit."';";
} else {
	$string1.="
ajaxVars['ajaxrainratehrNoU']   = '".   wsNumber ($ws['rainRateAct'],$decPrecip)."';	
ajaxVars['ajaxrainratehr']      = '".   wsNumber ($ws['rainRateAct'],$decPrecip).$unit."';";
}
$string1.="
ajaxVars['ajaxrainmo']          = '".	wsNumber ($ws['rainMonth'],$decPrecip).$unit."';
ajaxVars['ajaxrainyr']          = '".	wsNumber ($ws['rainYear'],$decPrecip).$unit."';";

#  ---------------------------  wind items
if ($ws['windBeafort'] == '')  {
	$ws['windBeafort'] = 		wsBeaufortNumber ($ws['windAct'],$SITE['uomWind']);
}
$beaufortText 	= langtransstr(wsBeaufortText ($ws['windBeafort']));
$convertText	= $beaufortText;
if ( (($ws['windAct']  + $ws['gustAct']) < 0.1 ) || ($ws['windActDsc'] == '---')) {
        $wrDsc 	='calm';                // use calm 
        $wrtext = $convertText;
} 
else {	$wrDsc 	= $ws['windActDsc'];
        $wrtext = langtransstr('Wind from') ." " . langtransstr($ws['windActDsc']); 
}
$wr1 	= $SITE['imgAjaxDir'].'wr-'.$SITE['lang'].'-'. $wrDsc .'.png';
$wr2 	= $SITE['imgAjaxDir'].'wind-'. $wrDsc .'.gif'; 
$text1	= '<img class="colImgWind" src="'.$wr1.'" title="'.$wrtext.'" alt="'.$wrtext.'"/>';
$text2	= '<img src="'.$wr2.'" style="height: 14px; width: 14px;" title="'.$wrtext.'" alt="'.$wrtext.'"/>';
$unit 	= ' '.langtransstr(trim($uomWind));
$string1.="
ajaxVars['ajaxwindiconwr']      = '".   $text1."';
ajaxVars['gizmowindicon']       = '".   $text2."';
ajaxVars['ajaxwinddir']         = '".   $wrtext."';
ajaxVars['ajaxwinddeg']         = '".   $ws['windActDir']."';
ajaxVars['ajaxwinddirNoU']      = '".   langtransstr($ws['windActDsc'])."';
ajaxVars['ajaxwind']            = '".   wsNumber ($ws['windAct'],$decWind).$unit."';
ajaxVars['ajaxwindNoU']         = '".   wsNumber ($ws['windAct'],$decWind)."';
ajaxVars['ajaxgust']            = '".   wsNumber ($ws['gustAct'],$decWind).$unit."';
ajaxVars['ajaxgustNoU']         = '".   wsNumber ($ws['gustAct'],$decWind)."';
ajaxVars['ajaxbeaufortnum']     = '".   $ws['windBeafort']."';
ajaxVars['ajaxbeaufort']        = '".   $convertText."';
ajaxVars['ajaxwindmaxgust']     = '".   wsNumber ($ws['gustMaxToday'],$decWind).$unit."';
ajaxVars['ajaxwindmaxgusttime'] = '".   langtransstr('at').' '.string_date ($ws['gustMaxTodayTime'],$SITE['timeOnlyFormat'])."';";

#  ---------------------------  Humidity  items
$textUP = langtransstr("Increased by").": ".' %s%% '.langtransstr("the last hour").".";
$textDN = langtransstr("Decreased by").": ".' %s%% '.langtransstr("the last hour").".";
$text	= wsGenArrow($ws['humiAct'],$ws['humiDelta'], $textUP, $textDN, '0');

$string1.="
ajaxVars['ajaxhumidity']        = '".	wsNumber ($ws['humiAct'],0)."%';
ajaxVars['ajaxhumidityNoU']     = '".	wsNumber ($ws['humiAct'],0)."';
ajaxVars['ajaxhumidityarrow']   = '".	$text."';";

#  ---------------------------  Pressure  items
$textUP = langtransstr('Rising')  . ' %s '. $uomBaro .' '.langtransstr(trim($uomPerHour));
$textDN = langtransstr('Falling') . ' %s '. $uomBaro .' '.langtransstr(trim($uomPerHour));
$text	= wsGenArrow($ws['baroAct'],$ws['baroDelta'],$textUP,$textDN,$SITE['decBaro']);
if ($SITE['WXsoftware'] == 'WL' || $SITE['WXsoftware'] == 'DW' ) {
	$pressuretrendname      = langtransstr($ws['baroTrend']);
} 
else {	$pressuretrendname      = wsBarotrendText($ws['baroDelta'],$uomBaro);
}
$string1.="
ajaxVars['ajaxbaro']            = '".	wsNumber ($ws['baroAct'],$decBaro).$uomBaro."';
ajaxVars['ajaxbaroNoU']         = '".	wsNumber ($ws['baroAct'],$decBaro)."';
ajaxVars['ajaxbaroarrow']       = '".	$text."';
ajaxVars['ajaxbarotrendtext']   = '".	$pressuretrendname."';";
#  ---------------------------  solar / uv values - sent only when there are sensors
if ($SITE['SOLAR']) {
        $string1.="
ajaxVars['ajaxsolar']           = '".   wsNumber ($ws['solarAct'],0)."';";
        if (isset ($ws['solActPerc']) ){
        	$string1.="
ajaxVars['ajaxsolarpct']        = '".   wsNumber ($ws['solActPerc'],0)."';";
        }
        elseif (isset ($ws['solarActPerc']) ){
        	$string1.="
ajaxVars['ajaxsolarpct']        = '".   wsNumber ($ws['solarActPerc'],0)."';";
        }
        $string1.="
ajaxVars['ajaxsolarmax']        = '".   wsNumber ($ws['solarMaxToday'],0)."';"; 
        if ($ws['solarMaxToday'] <> 0) {$string1.="
ajaxVars['ajaxsolarmaxtime']    = '".   string_date($ws['solarMaxTodayTime'], $SITE['timeOnlyFormat'])."';";
        } 
        else { 	$string1.="
ajaxVars['ajaxsolarmaxtime']    = 'none';";
        }
}  // EO solar
if ($SITE['UV']) {
        $string1.="
ajaxVars['ajaxuv']              = '".   wsNumber ($ws['uvAct'])."';
ajaxVars['ajaxuvword']          = '".   wsgetUVword($ws['uvAct'])."';
ajaxVars['ajaxuvmax']           = '".   wsNumber ($ws['uvMaxToday'])."';";
        if ($ws['uvMaxTodayTime']  <> 0) {
        	$string1.="
ajaxVars['ajaxuvmaxtime']       = '".   string_date($ws['uvMaxTodayTime'] , $SITE['timeOnlyFormat'])."';";
        } 
        else {$string1.="
ajaxVars['ajaxuvmaxtime']       = 'none';".PHP_EOL;
        }
} // eo UV
#
function wsGenArrow ($nowTemp, $diff, $textUP, $textDN, $DP=1) {
	global $SITE, $wsDebug;
	if ($SITE['WXsoftware'] == 'WL' || $SITE['WXsoftware'] == 'DW' ) {return '';}
	$nowTemp	= 1.0*(str_replace(',','.',$nowTemp) );
	$diff		= 1.0*(str_replace(',','.',$diff) );
	$absDiff 	= abs($diff);
	$diffStr	= number_format($diff,$DP);
	$absDiffStr	= number_format($absDiff,$DP);
	if($SITE['commaDecimal']) {
		$absDiffStr = preg_replace('|\.|',',',$absDiffStr);
	}
	if ($diffStr == 0) {		// no change
		$image 		= '<img style="height: 16px;" src="'.$SITE['imgAjaxDir'].'steady.gif" alt="steady" title="'.langtransstr('steady').'" />';
	} 
	elseif ($diffStr > 0) {	// now is greater 
		$msg 		= sprintf($textUP,$absDiffStr); 
		$image 		= '<img style="height: 16px;" src="'.$SITE['imgAjaxDir'].'rising.gif" alt="rising" title="'.$msg.'" />';
	} 
	else {	$msg 		= sprintf($textDN,$absDiffStr); 
		$image 		= '<img style="height: 16px;" src="'.$SITE['imgAjaxDir'].'falling.gif" alt="falling" title="'.$msg.'"/>';
	}
	ws_message ( '<!-- module wsDataLoad.php: function wsGenArrow DP='.$DP.' now= '.$nowTemp.' dif='.$diff.' result = '.$image.'-->');
	return $image;
} // eof   wsGenArrow
# ----------------------  version history
# 3.20 2015-07-27 release 2.8 version 
