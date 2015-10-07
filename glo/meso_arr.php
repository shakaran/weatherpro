<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'meso_arr.php';
$pageVersion	= '3.20 2015-09-11';
#-------------------------------------------------------------------------------
# 3.20 2015-08-26 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-------------------------------------------------------------------------------
#
$string = '
|AUWN	|Australia Weather Network		|http://australiawx.net/		|Australia	|other  |C,m/s,hPa,mm,m|
|ARWN	|Argentina Weather Network		|http://www.tiempoarg.com.ar/		|Argentina	|other 	|C,kmh,hPa,mm,m|
|NAMWN	|Namibia Weather Network		|http://www.namibiaweather.info/	|Namibia	|other	|C,kmh,hPa,mm,m|
|NZWN2	|New Zealand Local Weather Network	|http://www.localweather.net.nz/smf/new-zealand-local-weather-network/|New Zealand|other|C,kmh,hPa,mm,m|

|CAWN	|Canadian Atlantic Weather Network	|http://www.eastcoastweather.net/	|Atlantic	|Canada	|C,kmh,hPa,mm,m|
|CMBWN	|Manitoba Weather Network		|http://mb.westerncanadawx.net/		|Manitoba	|Canada	|C,kmh,hPa,mm,m|
|COWN	|Ontario Weather Network		|http://www.ontario-weather.net/	|Ontario	|Canada	|C,kmh,hPa,mm,m|
|CQWN	|Quebec Weather Network		        |http://meteo-quebec.net/		|Quebec	        |Canada	|C,kmh,hPa,mm,m|
|CSKWN	|Saskatchewan Weather Network		|http://sk.westerncanadawx.net/		|Saskatchewan	|Canada	|C,kmh,hPa,mm,m|
|WCWN	|Western Canada Weather Network		|http://westerncanadawx.net/		|Western	|Canada	|C,kmh,hPa,mm,m|

|AKWN	|Alaskan Weather Network		|http://alaskanweather.net/		|Alaska	        |america|F,mph,inHg,in,ft|
|MAWN	|Mid-Atlantic Weather Network		|http://www.midatlanticwx.net/		|Mid-Atlantic	|america|F,mph,inHg,in,ft|
|MWWN	|Midwestern Weather Network		|http://www.midwesternweather.net/	|Mid-West	|america|F,mph,inHg,in,ft|
|MSWN	|Mid-South Weather Network		|http://www.midsouthweather.net/	|Mid-South	|america|F,mph,inHg,in,ft|
|NEWN	|Northeastern Weather Network		|http://www.northeasternweather.net/	|North-East	|america|F,mph,inHg,in,ft|
|NWWN	|Northwest Weather Network		|http://www.northwesternweather.net/	|North-West	|america|F,mph,inHg,in,ft|
|PWN	|Plains Weather Network		        |http://plainsweather.net/		|Plains	        |america|F,mph,inHg,in,ft|
|RMWN	|Rocky Mountain Weather Network		|http://rockymountainweather.net/	|Rocky Mtn.	|america|F,mph,inHg,in,ft|
|SEWN	|Southeastern Weather Network		|http://southeasternweather.net/	|South-East	|america|F,mph,inHg,in,ft|
|SWN	|Southwestern Weather Network		|http://southwesternwx.net/		|South-West	|america|F,mph,inHg,in,ft|


|ATWN	|Austria Weather Network		|http://austrian-weather.com/		|Austria	|Europe	|C,kmh,hPa,mm,m|
|BNLWN	|Benelux Weather Network		|http://www.beneluxweather.net/		|Benelux	|Europe	|C,kmh,hPa,mm,m|
|BHWN	|Bosnia and Herzegovina Weather Network	|http://bosnianweather.net/		|Bosnia and Herzegovina	|Europe	|C,kmh,hPa,mm,m|
|BGWN	|Bulgarian Weather Network		|http://www.bgweather.net/BGWN/		|Bulgaria	|Europe	|C,kmh,hPa,mm,m|
|CZWN	|Czech Republic Weather Weather Network	|http://network.meteopage.com/		|Czech Republic	|Europe	|C,kmh,hPa,mm,m|
|ZEUR	|European Weather Network		|http://www.europeanweathernetwork.eu/	|European	|Europe	|C,m/s,hPa,mm,m|
|FRWN	|French Weather Network		        |http://www.francemeteo.info/		|France	        |Europe	|C,kmh,hPa,mm,m|
|DEWN	|Germany Weather Network		|http://www.wettermap.net/		|Germany	|Europe	|C,kmh,hPa,mm,m|
|GRWN	|Hellas Meteo Network		        |http://www.meteogreece.net/		|Greece	        |Europe	|C,kmh,hPa,mm,m|
|HUWN	|Hungarian Weather Network		|http://www.huweather.hu/		|Hungary	|Europe	|C,kmh,hPa,mm,m|
|IPWN	|Iberian Peninsula Weather Network	|http://www.meteoiberica.net/		|Iberian Peninsula	|Europe	|C,kmh,hPa,mm,m|
|ITAWN	|Italian Weather Network		|http://www.italiamn.it/		|Italy	        |Europe	|C,kmh,hPa,mm,m|
|PLWN	|Poland Weather Network		        |http://www.polishweather.net/		|Poland	        |Europe	|C,kmh,hPa,mm,m|
|ROWN	|Romanian Weather Network		|http://romanianweather.net/		|Romania	|Europe	|C,kmh,hPa,mm,m|
|SCWN	|Scottish Weather Network		|http://www.scottishweather.net/	|Scotland	|Europe	|C,mph,mb,mm,m|
|RSWN	|Serbian Weather Network		|http://www.serbianweather.net/		|Serbia	        |Europe	|C,kmh,hPa,mm,m|
|SVKWN	|Slovakia Weather Network		|http://svkwn.pocasie-bytca.sk/		|Slovakia	|Europe	|C,m/s,hPa,mm,m|
|SIWN	|Slovenia Weather Network		|http://www.sloveniaweather.net/	|Slovenia	|Europe	|C,kmh,hPa,mm,m|
|UKWN	|United Kingdom Weather Network		|http://www.ukwx.net/		        |United Kingdom	|Europe	|C,mph,mb,mm,m|
';
$meso_nets      = array();
$arr_values 	= explode ("\n",$string);
#echo '<pre>'.print_r ($arr_values,true); exit;
foreach ($arr_values as $none => $string) {
	if (trim($string) == '') {continue;}
	list ($none,$wn_code,$wn_name,$wn_url,$wn_country,$wn_region,$wn_uoms) = explode ('|',$string);
	$wn_code        = trim($wn_code);
	$wn_name        = trim($wn_name);
	$wn_url         = trim($wn_url);
	$wn_country     = trim($wn_country);
	$wn_region      = trim(strtolower($wn_region) );
	$wn_uoms        = trim($wn_uoms);
        $meso_nets[$wn_code]['wn_code']         = $wn_code ;
        $meso_nets[$wn_code]['wn_name']         = $wn_name ;
        $meso_nets[$wn_code]['wn_url']          = $wn_url ;
        $meso_nets[$wn_code]['wn_country']      = $wn_country ;
        $meso_nets[$wn_code]['wn_region']       = $wn_region ;
        $meso_nets[$wn_code]['wn_uoms']         = $wn_uoms ;
}
unset ($arr_values );
#echo '<pre>'.print_r ($meso_nets,true); exit;
# ----------------------  version history
# 3.20 2015-09-11 release 2.8 version 

    


