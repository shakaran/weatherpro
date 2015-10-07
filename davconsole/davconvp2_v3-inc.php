<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'davconvp2_v3-inc.php';
$pageVersion	= '0.00 2015-06-10';
#
if (!isset($SITE)){echo "<h3>invalid call to $pageName <h3>"; exit;}	//  page to load without menu system//
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;

$temp = $hum = $wind = $rain = $baro = $solr = '""';
$VPet = '';
$tmax = $ws['tempMaxToday'];
$hmax = $ws['humiMaxToday'];
$wmax = $ws['gustMaxToday'];
$rmax = $ws['rainToday'];
$bmax = $ws['baroMaxToday'];
$smax = $ws['solarMaxToday'];
$tmin = $ws['tempMinToday'];
$hmin = $ws['humiMinToday'];
$wmin = $ws['windAct'];
$rmin = 0;
$bmin = $ws['baroMinToday'];
$smin = 0;
if ($WXsoftware == 'CU' && $use_davcon) {
// BEGIN Cumulus Graph Data
   if (file_exists($graphurl)) {
      for($i=0;$i<26;$i++){  // 26 data points - vp2 does not use the first one as only 25 points on graph
      $tmcb[] = (time()+($i*3600)*1000); //TiMe Cloud Base
      }
   } else {echo "&nbsp;&nbsp;$graphurl NOT Found!"; Return;} // for Cumulus graph data
$graphurl = implode('', file($graphurl));
$c = explode(' ', $graphurl);
// $temp 25 datapoints for last 24 Hr temp graph 1/hr plus one RH end
$temp = '[['.$tmcb[0].','.$c[1].'],['.$tmcb[1].','.$c[2].'],['.$tmcb[2].','.$c[3].'],['.$tmcb[3].','.$c[4].'],['.$tmcb[4].','.$c[5].'],['.$tmcb[5].','.$c[6].'],['.$tmcb[6].','.$c[7].'],['.$tmcb[7].','.$c[8].'],['.$tmcb[8].','.$c[9].'],['.$tmcb[9].','.$c[10].'],['.$tmcb[10].','.$c[11].'],['.$tmcb[11].','.$c[12].'],['.$tmcb[12].','.$c[13].'],['.$tmcb[13].','.$c[14].'],['.$tmcb[14].','.$c[15].'],['.$tmcb[15].','.$c[16].'],['.$tmcb[16].','.$c[17].'],['.$tmcb[17].','.$c[18].'],['.$tmcb[18].','.$c[19].'],['.$tmcb[19].','.$c[20].'],['.$tmcb[20].','.$c[21].'],['.$tmcb[21].','.$c[22].'],['.$tmcb[22].','.$c[23].'],['.$tmcb[23].','.$c[24].'],['.$tmcb[24].','.$c[25].']]';
// $hum 25 datapoints for last 24 Hr humidity graph 1/hr plus one RH end
$hum = '[['.$tmcb[0].','.$c[27].'],['.$tmcb[1].','.$c[28].'],['.$tmcb[2].','.$c[29].'],['.$tmcb[3].','.$c[30].'],['.$tmcb[4].','.$c[31].'],['.$tmcb[5].','.$c[32].'],['.$tmcb[6].','.$c[33].'],['.$tmcb[7].','.$c[34].'],['.$tmcb[8].','.$c[35].'],['.$tmcb[9].','.$c[36].'],['.$tmcb[10].','.$c[37].'],['.$tmcb[11].','.$c[38].'],['.$tmcb[12].','.$c[39].'],['.$tmcb[13].','.$c[40].'],['.$tmcb[14].','.$c[41].'],['.$tmcb[15].','.$c[42].'],['.$tmcb[16].','.$c[43].'],['.$tmcb[17].','.$c[44].'],['.$tmcb[18].','.$c[45].'],['.$tmcb[19].','.$c[46].'],['.$tmcb[20].','.$c[47].'],['.$tmcb[21].','.$c[48].'],['.$tmcb[22].','.$c[49].'],['.$tmcb[23].','.$c[50].'],['.$tmcb[24].','.$c[51].']]';
// $wind 25 datapoints for last 24 Hr windspeed graph 1/hr plus one RH end
$wind = '[['.$tmcb[0].','.$c[53].'],['.$tmcb[1].','.$c[54].'],['.$tmcb[2].','.$c[55].'],['.$tmcb[3].','.$c[56].'],['.$tmcb[4].','.$c[57].'],['.$tmcb[5].','.$c[58].'],['.$tmcb[6].','.$c[59].'],['.$tmcb[7].','.$c[60].'],['.$tmcb[8].','.$c[61].'],['.$tmcb[9].','.$c[62].'],['.$tmcb[10].','.$c[63].'],['.$tmcb[11].','.$c[64].'],['.$tmcb[12].','.$c[65].'],['.$tmcb[13].','.$c[66].'],['.$tmcb[14].','.$c[67].'],['.$tmcb[15].','.$c[68].'],['.$tmcb[16].','.$c[69].'],['.$tmcb[17].','.$c[70].'],['.$tmcb[18].','.$c[71].'],['.$tmcb[19].','.$c[72].'],['.$tmcb[20].','.$c[73].'],['.$tmcb[21].','.$c[74].'],['.$tmcb[22].','.$c[75].'],['.$tmcb[23].','.$c[76].'],['.$tmcb[24].','.$c[77].']]';
// $rain 25 datapoints for last 24 Hr rain graph 1/hr plus one RH end
$rain = '[['.$tmcb[0].','.$c[79].'],['.$tmcb[1].','.$c[80].'],['.$tmcb[2].','.$c[81].'],['.$tmcb[3].','.$c[82].'],['.$tmcb[4].','.$c[83].'],['.$tmcb[5].','.$c[84].'],['.$tmcb[6].','.$c[85].'],['.$tmcb[7].','.$c[86].'],['.$tmcb[8].','.$c[87].'],['.$tmcb[9].','.$c[88].'],['.$tmcb[10].','.$c[89].'],['.$tmcb[11].','.$c[90].'],['.$tmcb[12].','.$c[91].'],['.$tmcb[13].','.$c[92].'],['.$tmcb[14].','.$c[93].'],['.$tmcb[15].','.$c[94].'],['.$tmcb[16].','.$c[95].'],['.$tmcb[17].','.$c[96].'],['.$tmcb[18].','.$c[97].'],['.$tmcb[19].','.$c[98].'],['.$tmcb[20].','.$c[99].'],['.$tmcb[21].','.$c[100].'],['.$tmcb[22].','.$c[101].'],['.$tmcb[23].','.$c[102].'],['.$tmcb[24].','.$c[103].']]';
// $baro 25 datapoints for last 24 Hr baro graph 1/hr plus one RH end
$baro = '[['.$tmcb[0].','.$c[105].'],['.$tmcb[1].','.$c[106].'],['.$tmcb[2].','.$c[107].'],['.$tmcb[3].','.$c[108].'],['.$tmcb[4].','.$c[109].'],['.$tmcb[5].','.$c[110].'],['.$tmcb[6].','.$c[111].'],['.$tmcb[7].','.$c[112].'],['.$tmcb[8].','.$c[113].'],['.$tmcb[9].','.$c[114].'],['.$tmcb[10].','.$c[115].'],['.$tmcb[11].','.$c[116].'],['.$tmcb[12].','.$c[117].'],['.$tmcb[13].','.$c[118].'],['.$tmcb[14].','.$c[119].'],['.$tmcb[15].','.$c[120].'],['.$tmcb[16].','.$c[121].'],['.$tmcb[17].','.$c[122].'],['.$tmcb[18].','.$c[123].'],['.$tmcb[19].','.$c[124].'],['.$tmcb[20].','.$c[125].'],['.$tmcb[21].','.$c[126].'],['.$tmcb[22].','.$c[127].'],['.$tmcb[23].','.$c[128].'],['.$tmcb[24].','.$c[129].']]';
// $solr 25 datapoints for last 24 Hr solar graph 1/hr plus one RH end
$solr = '[['.$tmcb[0].','.$c[131].'],['.$tmcb[1].','.$c[132].'],['.$tmcb[2].','.$c[133].'],['.$tmcb[3].','.$c[134].'],['.$tmcb[4].','.$c[135].'],['.$tmcb[5].','.$c[136].'],['.$tmcb[6].','.$c[137].'],['.$tmcb[7].','.$c[138].'],['.$tmcb[8].','.$c[139].'],['.$tmcb[9].','.$c[140].'],['.$tmcb[10].','.$c[141].'],['.$tmcb[11].','.$c[142].'],['.$tmcb[12].','.$c[143].'],['.$tmcb[13].','.$c[144].'],['.$tmcb[14].','.$c[145].'],['.$tmcb[15].','.$c[146].'],['.$tmcb[16].','.$c[147].'],['.$tmcb[17].','.$c[148].'],['.$tmcb[18].','.$c[149].'],['.$tmcb[19].','.$c[150].'],['.$tmcb[20].','.$c[151].'],['.$tmcb[21].','.$c[152].'],['.$tmcb[22].','.$c[153].'],['.$tmcb[23].','.$c[154].'],['.$tmcb[24].','.$c[155].']]';
// extract max & min values for each graph data type to display max & min values as graph scale is not constant but scales to data range
$t = array_slice($c, 1, 25);
  $tmax = max($t);
  $tmin = min($t);
$h = array_slice($c, 27, 25);
  $hmax = max($h);
  $hmin = min($h);
$w = array_slice($c, 53, 25);
  $wmax = max($w);
  $wmin = min($w);
$r = array_slice($c, 79, 25);
  $rmax = round(max($r),1);
  $rmin = round(min($r),1);
$b = array_slice($c, 105, 25);
  $bmax = round(max($b),1);
  $bmin = round(min($b),1);
$s = array_slice($c, 131, 25);
  $smax = max($s);
  $smin = min($s);
// END CU graph data
$moonage = $c[156]; // $moonage not part of standard CUtags.php / CU-defs.php
$daynite = ($c[159] == '1') ? 'D' : 'N';

// BEGIN WD graph data
}
elseif ($SITE['realtime'] == 'cltrw') {
   if (file_exists($graphurl)) {
      for($i=0;$i<25;$i++){  // 24 data points - duplicate LH data point to fill 25 point VP2 graph grid
      $tmcb[] = (time()+($i*3600)*1000); //TiMe Cloud Base
      }
   } else { echo "&nbsp;$graphurl NOT Found!"; Return;}
   $graphurl = implode('', file($graphurl));
   $c = explode(' ', $graphurl);
// $temp 25 datapoints for last 24 Hr temp graph 1/hr plus one RH end
$temp = '[['.$tmcb[0].','.$c[21].'],['.$tmcb[1].','.$c[21].'],['.$tmcb[2].','.$c[22].'],['.$tmcb[3].','.$c[23].'],['.$tmcb[4].','.$c[24].'],['.$tmcb[5].','.$c[25].'],['.$tmcb[6].','.$c[26].'],['.$tmcb[7].','.$c[27].'],['.$tmcb[8].','.$c[28].'],['.$tmcb[9].','.$c[29].'],['.$tmcb[10].','.$c[30].'],['.$tmcb[11].','.$c[31].'],['.$tmcb[12].','.$c[32].'],['.$tmcb[13].','.$c[33].'],['.$tmcb[14].','.$c[34].'],['.$tmcb[15].','.$c[35].'],['.$tmcb[16].','.$c[36].'],['.$tmcb[17].','.$c[37].'],['.$tmcb[18].','.$c[38].'],['.$tmcb[19].','.$c[39].'],['.$tmcb[20].','.$c[40].'],['.$tmcb[21].','.$c[566].'],['.$tmcb[22].','.$c[567].'],['.$tmcb[23].','.$c[568].'],['.$tmcb[24].','.$c[569].']]';
// $hum 25 datapoints for last 24 Hr humidity graph 1/hr plus one RH end
$hum = '[['.$tmcb[0].','.$c[611].'],['.$tmcb[1].','.$c[611].'],['.$tmcb[2].','.$c[612].'],['.$tmcb[3].','.$c[613].'],['.$tmcb[4].','.$c[614].'],['.$tmcb[5].','.$c[615].'],['.$tmcb[6].','.$c[616].'],['.$tmcb[7].','.$c[617].'],['.$tmcb[8].','.$c[618].'],['.$tmcb[9].','.$c[619].'],['.$tmcb[10].','.$c[620].'],['.$tmcb[11].','.$c[621].'],['.$tmcb[12].','.$c[622].'],['.$tmcb[13].','.$c[623].'],['.$tmcb[14].','.$c[624].'],['.$tmcb[15].','.$c[625].'],['.$tmcb[16].','.$c[626].'],['.$tmcb[17].','.$c[627].'],['.$tmcb[18].','.$c[628].'],['.$tmcb[19].','.$c[629].'],['.$tmcb[20].','.$c[630].'],['.$tmcb[21].','.$c[631].'],['.$tmcb[22].','.$c[632].'],['.$tmcb[23].','.$c[633].'],['.$tmcb[24].','.$c[634].']]';
// $wind 25 datapoints for last 24 Hr windspeed graph 1/hr plus one RH end
$wind = '[['.$tmcb[0].','.$c[1].'],['.$tmcb[1].','.$c[1].'],['.$tmcb[2].','.$c[2].'],['.$tmcb[3].','.$c[3].'],['.$tmcb[4].','.$c[4].'],['.$tmcb[5].','.$c[5].'],['.$tmcb[6].','.$c[6].'],['.$tmcb[7].','.$c[7].'],['.$tmcb[8].','.$c[8].'],['.$tmcb[9].','.$c[9].'],['.$tmcb[10].','.$c[10].'],['.$tmcb[11].','.$c[11].'],['.$tmcb[12].','.$c[12].'],['.$tmcb[13].','.$c[13].'],['.$tmcb[14].','.$c[14].'],['.$tmcb[15].','.$c[15].'],['.$tmcb[16].','.$c[16].'],['.$tmcb[17].','.$c[17].'],['.$tmcb[18].','.$c[18].'],['.$tmcb[19].','.$c[19].'],['.$tmcb[20].','.$c[20].'],['.$tmcb[21].','.$c[562].'],['.$tmcb[22].','.$c[563].'],['.$tmcb[23].','.$c[564].'],['.$tmcb[24].','.$c[565].']]';
// $rain 25 datapoints for last 24 Hr rain graph 1/hr plus one RH end
$rain = '[['.$tmcb[0].','.$c[41].'],['.$tmcb[1].','.$c[41].'],['.$tmcb[2].','.$c[42].'],['.$tmcb[3].','.$c[43].'],['.$tmcb[4].','.$c[44].'],['.$tmcb[5].','.$c[45].'],['.$tmcb[6].','.$c[46].'],['.$tmcb[7].','.$c[47].'],['.$tmcb[8].','.$c[48].'],['.$tmcb[9].','.$c[49].'],['.$tmcb[10].','.$c[50].'],['.$tmcb[11].','.$c[51].'],['.$tmcb[12].','.$c[52].'],['.$tmcb[13].','.$c[53].'],['.$tmcb[14].','.$c[54].'],['.$tmcb[15].','.$c[55].'],['.$tmcb[16].','.$c[56].'],['.$tmcb[17].','.$c[57].'],['.$tmcb[18].','.$c[58].'],['.$tmcb[19].','.$c[59].'],['.$tmcb[20].','.$c[60].'],['.$tmcb[21].','.$c[570].'],['.$tmcb[22].','.$c[571].'],['.$tmcb[23].','.$c[572].'],['.$tmcb[24].','.$c[573].']]';
// $baro 25 datapoints for last 24 Hr baro graph 1/hr plus one RH end
$baro = '[['.$tmcb[0].','.$c[439].'],['.$tmcb[1].','.$c[439].'],['.$tmcb[2].','.$c[440].'],['.$tmcb[3].','.$c[441].'],['.$tmcb[4].','.$c[442].'],['.$tmcb[5].','.$c[443].'],['.$tmcb[6].','.$c[444].'],['.$tmcb[7].','.$c[445].'],['.$tmcb[8].','.$c[446].'],['.$tmcb[9].','.$c[447].'],['.$tmcb[10].','.$c[448].'],['.$tmcb[11].','.$c[449].'],['.$tmcb[12].','.$c[450].'],['.$tmcb[13].','.$c[451].'],['.$tmcb[14].','.$c[452].'],['.$tmcb[15].','.$c[453].'],['.$tmcb[16].','.$c[454].'],['.$tmcb[17].','.$c[455].'],['.$tmcb[18].','.$c[456].'],['.$tmcb[19].','.$c[457].'],['.$tmcb[20].','.$c[458].'],['.$tmcb[21].','.$c[574].'],['.$tmcb[22].','.$c[575].'],['.$tmcb[23].','.$c[576].'],['.$tmcb[24].','.$c[577].']]';
// $solr 24 datapoints for last 24 Hr Solar graph 1/hr plus one RH end
$solr = '[['.$tmcb[0].','.$c[491].'],['.$tmcb[1].','.$c[492].'],['.$tmcb[2].','.$c[493].'],['.$tmcb[3].','.$c[494].'],['.$tmcb[4].','.$c[495].'],['.$tmcb[5].','.$c[496].'],['.$tmcb[6].','.$c[497].'],['.$tmcb[7].','.$c[498].'],['.$tmcb[8].','.$c[499].'],['.$tmcb[9].','.$c[500].'],['.$tmcb[10].','.$c[501].'],['.$tmcb[11].','.$c[502].'],['.$tmcb[12].','.$c[503].'],['.$tmcb[13].','.$c[504].'],['.$tmcb[14].','.$c[505].'],['.$tmcb[15].','.$c[506].'],['.$tmcb[16].','.$c[507].'],['.$tmcb[17].','.$c[508].'],['.$tmcb[18].','.$c[509].'],['.$tmcb[19].','.$c[510].'],['.$tmcb[20].','.$c[582].'],['.$tmcb[21].','.$c[583].'],['.$tmcb[22].','.$c[584].'],['.$tmcb[23].','.$c[585].']]';
// extract max & min values for each graph data type to display max & min values as graph scale is not constant but scales to data range
$t = array($c[21],$c[22],$c[23],$c[24],$c[25],$c[26],$c[27],$c[28],$c[29],$c[30],$c[31],$c[32],$c[33],$c[34],$c[35],$c[36],$c[37],$c[38],$c[39],$c[40],$c[566],$c[567],$c[568],$c[569]);
// print_r($t);
   $tmax = max($t);
   $tmin = min($t);
$h = array($c[611],$c[612],$c[613],$c[614],$c[615],$c[616],$c[617],$c[618],$c[619],$c[620],$c[621],$c[622],$c[623],$c[624],$c[625],$c[626],$c[627],$c[628],$c[629],$c[630],$c[631],$c[632],$c[633],$c[634]);
   $hmax = max($h);
   $hmin = min($h);
$w = array($c[1],$c[2],$c[3],$c[4],$c[5],$c[6],$c[7],$c[8],$c[9],$c[10],$c[11],$c[12],$c[13],$c[14],$c[15],$c[16],$c[17],$c[18],$c[19],$c[20],$c[562],$c[563],$c[564],$c[565]);
   $wmax = max($w);
   $wmin = min($w);
$r = array($c[41],$c[42],$c[43],$c[44],$c[45],$c[46],$c[47],$c[48],$c[49],$c[50],$c[51],$c[52],$c[53],$c[54],$c[55],$c[56],$c[57],$c[58],$c[59],$c[60],$c[570],$c[571],$c[572],$c[573]);
   $rmax = max($r);
   $rmin = min($r);
$b = array($c[439],$c[440],$c[441],$c[442],$c[443],$c[444],$c[445],$c[446],$c[447],$c[448],$c[449],$c[450],$c[451],$c[452],$c[453],$c[454],$c[455],$c[456],$c[457],$c[458],$c[574],$c[575],$c[576],$c[577]);
   $bmax = max($b);
   $bmin = min($b);
$s = array($c[491],$c[492],$c[493],$c[494],$c[495],$c[496],$c[497],$c[498],$c[499],$c[500],$c[501],$c[502],$c[503],$c[504],$c[505],$c[506],$c[507],$c[508],$c[509],$c[510],$c[582],$c[583],$c[584],$c[585]);
   $smax = max($s);
   $smin = min($s);
   if ($uomsys != 'I') {                  // Convert from default to metric as required
      $wmax = round($wmax * 1.852,1);     // Knots to kph
      $wmin = round($wmin * 1.852,1);
      $bmax = round($bmax,1);             // hPa, mb
      $bmin = round($bmin,1);
   } else {                               // Convert from default to imperial as required
      $tmax = round($tmax * 1.8 + 32,1);  // deg F
      $tmin = round($tmin * 1.8 + 32,1);
      $wmax = round($wmax * 1.1507794,1); // knots to mph
      $wmin = round($wmin * 1.1507794,1);
      $rmax = round($rmax / 25.4,2);      // mm to inches
      $rmin = round($rmin / 25.4,2);
      $bmax = round($bmax * 0.0295317,2); // inHg
      $bmin = round($bmin * 0.0295317,2);
   }
// END WD graph data
$VPet    =  $c[532];
$daynite = ($c[610] == 'D') ? 'D' : 'N';
} 
else {	include 'last24h.php';
}
if ($dayNight == 'daylight') {$daynite = 'D'; } else {$daynite = 'N';}
$stormmin = ($uomRain == 'mm')? 0.4 : 0.02;
if (!isset($vpstormrain) || $vpstormrain < $stormmin) {$dayrnrotate = 1; $vpstormrain = 0;}
$showsolar = ($vp2Plus  == 'Y' && $daynite == 'D') ? 'Y' : 'N';
# headers
$h_temp_out	= strtoupper(langtransstr('TEMP OUT') );
$h_temp_in	= strtoupper(langtransstr('TEMP IN') );
$h_humi_out	= strtoupper(langtransstr('HUM OUT') );
$h_humi_in	= strtoupper(langtransstr('HUM IN') );
$h_barometer	= strtoupper(langtransstr('BAROMETER') );
$h_dewpoint	= strtoupper(langtransstr('DEW') );
$h_solar	= strtoupper(langtransstr('SOLAR') ).' '.$uomSolar;
$h_uvindex	= strtoupper(langtransstr('UV') );
$h_feels_like	= strtoupper(langtransstr('FEELS') );
$h_rain_daily	= strtoupper(langtransstr('DAILY RAIN') );
$h_rain_rate	= strtoupper(langtransstr('RAIN RATE') );
?>
<script type="text/javascript">
var n=0;
function BackLight() {
   n++;
   if (n%2)
      document.getElementById('vp2console').style.backgroundImage = "url('<?php echo $imgdir; ?>vp2_console.png')";
   else
      document.getElementById('vp2console').style.backgroundImage = "url('<?php echo $imgdir; ?>vp2_console_lit.png')";
}
</script>
<noscript><h1 style="color: red;">ENABLE JAVASCRIPT FOR LIVE UPDATES!</h1></noscript>
<table style="margin: 0px auto; width: 720px;">
<tr>
<td>
<div style="text-align:left; margin:0 auto; width:700px;">
<div class="vp2console" id="vp2console">
<!-- FORECAST ICON -->
<span class="vajax" id="cajaxicon" style="top: 65px; left: 200px;"><img src="<?php echo $imgdir.$fcsticon; ?>" alt="forecast image" /></span>

<!-- MOON ICON -->
<span class="vajax" id="cajaxmoon" style="top: 65px; left: 270px;"><img src="<?php echo $imgdir.$moonic; ?>" alt="moon image" /></span>

<!-- TIME DATE -->
<span class="vajax" id="cajaxtime" style="font-size: 14px; top: 62px; right: 320px;"><?php echo $vars['ajaxtime']; ?></span>
<span class="vajax" id="cajaxdate" style="font-size: 14px; top: 62px; right: 245px;"><?php echo $vars['ajaxdate']; ?></span>

<!-- WIND -->
<span class="vajax" id="cajaxwdir" style="height:90px; width:90px; top: 80px; left: 77px; transform: rotate(<?php echo $ws['windActDir']; ?>deg); -webkit-transform: rotate(<?php echo $ws['windActDir']; ?>deg) translate3d(0px, 0px, 0px);">
<span style="position: absolute; top: 0px; left: 37px; 
border-left:7px solid transparent; 
border-right:7px solid transparent;
border-top:20px solid #1E2023;
height:0;
width:0;
margin:0 auto;
">
</span>
</span>
<div id="cblock0_0" style="display: block;">
<span class="vajax" id="cajaxwinddir" style="top: 114px; right: 556px;"><?php echo $vars['ajaxwindNoU']; ?></span>
<span class="small" id="cajaxwindu" style="top:134px; left: 108px;"><?php echo $uomWind; ?></span>
</div>
<div id="cblock0_1" style="display: none;">
<span class="vajax" id="cajaxwinddeg" style="top: 114px; right: 556px;"><?php echo $vars['ajaxwinddeg']; ?></span>
<span class="small" id="cajaxwinddu" style="top:108px; left: 146px;">&deg;</span>
</div>
<!-- TEMP OUTSIDE -->
<span class="small"  style= "top: 95px; left: 210px;"><?php echo $h_temp_out; ?></span>
<span class="vajax"  id="cajaxtemp" style="top: 115px; right: 440px;"><?php echo $vars['ajaxtempNoU']; ?></span>
<span class="small" style="top:110px; left: 265px;"><?php echo $uomTemp; ?></span>

<!-- HUMIDITY OUTSIDE -->
<span class="small"  style= "top: 95px; left: 285px;"><?php echo $h_humi_out; ?></span>
<span class="vajax"  id="cajaxhumidity" style="top: 115px;  right: 377px;"><?php echo $vars['ajaxhumidityNoU']; ?></span>
<span class="small" style="top:110px; left:325px;">%</span>

<!-- BAROMETER -->
<span class="small"  style="top:95px; right:265px;"><?php echo $h_barometer; ?></span>
<span class="vajax"  id="cajaxbaroarrow" style="height:15px; width:15px; top:92px; left:440px; transform: rotate(-90deg); -webkit-transform: rotate(-90deg) translate3d(0px, 0px, 0px);">
<span style="position: absolute; top: 4px; left: 7px; 
border-left:3px solid transparent; 
border-right:3px solid transparent;
border-top:15px solid #1E2023;
height:0;
width:0;
margin:0 auto;
">
</span>


</span>
<span class="vajax"  id="cajaxbaro" style="top: 115px; right: 265px;"><?php echo $vars['ajaxbaroNoU']; ?></span>
<span class="small" style="top:115px; left: 440px;"><?php echo $uomBaro; ?></span>

<div id ="cblock1_0" style="display: block;">
	<!-- TEMP INSIDE -->
	<span class="small"  style="top:150px; left:215px;"><?php echo $h_temp_in; ?></span>
	<span class="vajax"  id="cajaxitemp" style="top: 170px; right: 440px;"><?php echo wsNumber($ws['tempActInside'],$SITE['decTemp'] ); ?></span>
	<span class="small" id="itempuom" style="top:164px; left: 265px;"><?php echo $uomTemp; ?></span>

	<!-- HUMIDITY INSIDE -->
	<span class="small"  style="top:150px; right:350px;"><?php echo $h_humi_in; ?></span>
	<span class="vajax"  id="cajaxihumidity" style="top: 170px; right: 355px;"><?php 
	if (!isset ($ws['humiInAct']) ) {echo '--'; } else {echo wsNumber($ws['humiInAct'],$SITE['decTemp'] );} 
	?></span>
	<span class="small"  style="top:164px; right:342px;">%</span>

	<!-- FEELS TEMP -->
	<span class="small"  style="top:150px; right:265px;"><?php echo $h_feels_like; ?></span>
	<span class="vajax"  id="cajaxapp" style="top: 170px; right: 265px;"><?php echo $vars['ajaxfeelslikeNoU']; ?></span>
	<span class="small"  style="top:164px; left: 440px;"><?php echo $uomTemp; ?></span>

</div>
<div id ="cblock1_1" style="display: none;">
	<!-- SOLAR  -->
	<span class="small"  style="top:150px; left:215px;"><?php echo $h_solar; ?></span>
	<span class="vajax"  id="csolar" style="top: 170px; right: 440px;"><?php echo $vars['ajaxsolar']; ?></span>
	
	<!-- UV  -->
	<span class="small"  style="top:150px; right:350px;"><?php echo $h_uvindex; ?></span>
	<span class="vajax"  id="cuvindex" style="top: 170px; right: 355px;"><?php echo $vars['ajaxuv']; ?></span>
	<span class="small"  style="top:170px; right:325px;">index</span>

	<span class="small" style="top:164px; left: 440px;"><?php echo $uomTemp; ?></span>
	<!-- DEW TEMP -->
	<span class="small"  style="top:150px; right:265px;"><?php echo $h_dewpoint; ?></span>
	<span class="vajax"  id="cajaxdew" style="top: 170px; right: 265px;"><?php echo $vars['ajaxdewNoU']; ?></span>
	<span class="small"  style="top:164px; left: 440px;"><?php echo $uomTemp; ?></span>

</div>

<!-- DAILY RAIN-->
<span class="small" style="top:215px; right:410px;"><?php echo $h_rain_daily; ?></span>
<span class="vajax" id="cajaxrain" style="top:235px; right:410px;"><?php echo $vars['ajaxrainNoU']; ?></span>
<span class="small" id="rrt" style="top:241px; left:293px;"><?php echo $uomRain; ?></span>

<!-- Is Raining or snow ICON -->
<span class="vajax" id="cajaxumbr" style="top:220px; right:360px;"></span>

<!-- RAIN RATE -->
<span class="small" style="top: 215px; right: 265px;"><?php echo $h_rain_rate; ?></span>
<span class="vajax" id="cajaxrainratehr" style="top: 235px; right: 265px;"><?php echo $vars['ajaxrainratehrNoU']; ?></span>
<span class="small" id="rrth" style="top: 241px; left: 438px;"><?php echo $uomRain; ?>/h</span>

<!-- FORECAST TEXT -->
<div id="vp2_scroller_container">
 <div class="jscroller2_left jscroller2_speed-50 jscroller2_mousemove jscroller2_ignoreleave">
 <?php echo $forecast_trans; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
 <div class="jscroller2_left_endless jscroller2"><?php echo $forecast_trans; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
</div>

<!-- GRAPH LABEL -->
<span class="small" id="grhrs" style="position:absolute; top:176px; left:57px;">24 x 1hr</span>
<span class="small" id="grlab" style="position:absolute; top:252px; left:57px;">TEMP</span>
<span class="small" style="position:absolute; top:252px; left:93px;">Hi</span>
<span class="small" id="grmax" style="position:absolute; top:252px; left:107px;"><?php echo $tmax; ?></span>
<span class="small" style="position:absolute; top:252px; left:145px;">Lo</span>
<span class="small" id="grmin" style="position:absolute; top:252px; left:160px;"><?php echo $tmin; ?></span>
<!-- GRAPH WINDOW -->
<div id="placeholder" style="height:60px; width:130px; position:absolute; top:193px; left:57px;"></div>

<!-- BUTTONS Console-->
<div id="tempbtn"  style="cursor:pointer; height:25px; width:70px; position:absolute; top:52px; left:510px;"></div>
<div id="humbtn"   style="cursor:pointer; height:25px; width:70px; position:absolute; top:94px; left:510px;"></div>
<div id="windbtn"  style="cursor:pointer; height:22px; width:70px; position:absolute; top:136px; left:510px;"></div>
<div id="rainbtn"  style="cursor:pointer; height:70px; width:70px; position:absolute; top:180px; left:510px;"></div>
<div id="barbtn"   style="cursor:pointer; height:25px; width:70px; position:absolute; top:262px; left:510px;"></div>
<div id="lampsbtn" onclick="BackLight()" style="cursor:pointer; height:25px; width:70px; position:absolute; top:31px; left:600px"></div>

<?php 
if ($fcastbtn != false) { echo '
<div id="fcastbtn" onclick="top.location.href=fcastbtn" style="cursor:pointer; height:25px; width:70px; position:absolute; top:73px; left:600px"></div>';
}  
if ($graphbtn != false) { echo '
<div id="graphbtn" onclick="top.location.href=graphbtn" style="cursor:pointer; height:25px; width:70px; position:absolute; top:115px; left:600px"></div>';
}
if ($hilowbtn != false) { echo '
<div id="hilowbtn" onclick="window.open(\''.$hilowbtn.'\')" style="cursor:pointer; height:25px; width:70px; position:absolute; top:157px; left:600px"></div>';
}
if ($alarmbtn != false) { echo '
<div id="alarmbtn" onclick="window.open(\''.$alarmbtn.'\')" style="cursor:pointer; height:25px; width:70px; position:absolute; top:199px; left:600px"></div>';
}
if ($donebtn !=  false) { echo '   
<div id="donebtn" onclick="top.location.href=donebtn"  style="cursor:pointer; height:25px; width:70px; position:absolute; top:241px; left:600px"></div>';
}

?>

</div>
</div>
</td>
<td style="vertical-align: top;"><a href="javascript:hideshow(document.getElementById('consoleExtra'))">
<img src="./img/i_symbol.png" alt=" " style="margin-top: 2px; width: 16px;"></a></td>
<td style="vertical-align: top; width: 200px;">
<div id="consoleExtra" style="display: none; position: relative; top:0; left: 0; width: 200px;">
<span style="position:absolute; top:34px; left:0px;">=>&nbsp;<?php echo $lampsbtntxt; ?></span>
<span style="position:absolute; top:56px; left:0px;"><?php echo $tempbtntxt; ?></span>
<span style="position:absolute; top:77px; left:0px;">=>&nbsp;<?php echo $fcastbtntxt; ?></span>
<span style="position:absolute; top:98px; left:0px;"><?php echo $humbtntxt; ?></span>
<span style="position:absolute; top:119px; left:0px;">=>&nbsp;<?php echo $graphbtntxt; ?></span>
<span style="position:absolute; top:140px; left:0px;"><?php echo $windbtntxt; ?></span>
<span style="position:absolute; top:161px; left:0px;">=>&nbsp;<?php echo $hilowbtntxt; ?></span>
<span style="position:absolute; top:184px; left:0px;"><?php echo $rainbtntxt; ?></span>
<span style="position:absolute; top:203px; left:0px;">=>&nbsp;<?php echo $alarmbtntxt; ?></span>
<span style="position:absolute; top:226px; left:0px;"><?php echo $rainbtntxt; ?></span>
<span style="position:absolute; top:245px; left:0px;">=>&nbsp;<?php echo $donebtntxt; ?></span>
<span style="position:absolute; top:264px; left:0px;"><?php echo $rainbtntxt; ?></span>
<span style="position:absolute; top:304px; left:0px; text-align:left; font-size:10px;"><?php echo $console_credit; ?></span>
</div>
</td>
</tr>
</table>

<div id="preload" style="display:none"><img src="./davconsole/img/vp2_console.png" alt="" /></div>

