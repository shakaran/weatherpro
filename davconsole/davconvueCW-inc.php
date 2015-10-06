<?php
if ($WXsoftware == 'CU') {
// BEGIN Cumulus Graph Data
   if (file_exists($graphurl)) {
      for($i=0;$i<26;$i++){  // 26 data points
      $tmcb[] = (time()+($i*3600)*1000); //TiMe Cloud Base
      }
   } else {echo "&nbsp;&nbsp;$graphurl NOT Found!"; Return;} // for Cumulus graph data
$graphurl = implode('', file($graphurl));
$c = explode(' ', $graphurl);
// $temp 26 datapoints for last 24 Hr temp graph 1/hr plus one each end
$temp = '[['.$tmcb[0].','.$c[0].'],['.$tmcb[1].','.$c[1].'],['.$tmcb[2].','.$c[2].'],['.$tmcb[3].','.$c[3].'],['.$tmcb[4].','.$c[4].'],['.$tmcb[5].','.$c[5].'],['.$tmcb[6].','.$c[6].'],['.$tmcb[7].','.$c[7].'],['.$tmcb[8].','.$c[8].'],['.$tmcb[9].','.$c[9].'],['.$tmcb[10].','.$c[10].'],['.$tmcb[11].','.$c[11].'],['.$tmcb[12].','.$c[12].'],['.$tmcb[13].','.$c[13].'],['.$tmcb[14].','.$c[14].'],['.$tmcb[15].','.$c[15].'],['.$tmcb[16].','.$c[16].'],['.$tmcb[17].','.$c[17].'],['.$tmcb[18].','.$c[18].'],['.$tmcb[19].','.$c[19].'],['.$tmcb[20].','.$c[20].'],['.$tmcb[21].','.$c[21].'],['.$tmcb[22].','.$c[22].'],['.$tmcb[23].','.$c[23].'],['.$tmcb[24].','.$c[24].'],['.$tmcb[25].','.$c[25].']]';
// $hum 26 datapoints for last 24 Hr humidity graph 1/hr plus one each end
$hum = '[['.$tmcb[0].','.$c[26].'],['.$tmcb[1].','.$c[27].'],['.$tmcb[2].','.$c[28].'],['.$tmcb[3].','.$c[29].'],['.$tmcb[4].','.$c[30].'],['.$tmcb[5].','.$c[31].'],['.$tmcb[6].','.$c[32].'],['.$tmcb[7].','.$c[33].'],['.$tmcb[8].','.$c[34].'],['.$tmcb[9].','.$c[35].'],['.$tmcb[10].','.$c[36].'],['.$tmcb[11].','.$c[37].'],['.$tmcb[12].','.$c[38].'],['.$tmcb[13].','.$c[39].'],['.$tmcb[14].','.$c[40].'],['.$tmcb[15].','.$c[41].'],['.$tmcb[16].','.$c[42].'],['.$tmcb[17].','.$c[43].'],['.$tmcb[18].','.$c[44].'],['.$tmcb[19].','.$c[45].'],['.$tmcb[20].','.$c[46].'],['.$tmcb[21].','.$c[47].'],['.$tmcb[22].','.$c[48].'],['.$tmcb[23].','.$c[49].'],['.$tmcb[24].','.$c[50].'],['.$tmcb[25].','.$c[51].']]';
// $wind 26 datapoints for last 24 Hr windspeed graph 1/hr plus one each end
$wind = '[['.$tmcb[0].','.$c[52].'],['.$tmcb[1].','.$c[53].'],['.$tmcb[2].','.$c[54].'],['.$tmcb[3].','.$c[55].'],['.$tmcb[4].','.$c[56].'],['.$tmcb[5].','.$c[57].'],['.$tmcb[6].','.$c[58].'],['.$tmcb[7].','.$c[59].'],['.$tmcb[8].','.$c[60].'],['.$tmcb[9].','.$c[61].'],['.$tmcb[10].','.$c[62].'],['.$tmcb[11].','.$c[63].'],['.$tmcb[12].','.$c[64].'],['.$tmcb[13].','.$c[65].'],['.$tmcb[14].','.$c[66].'],['.$tmcb[15].','.$c[67].'],['.$tmcb[16].','.$c[68].'],['.$tmcb[17].','.$c[69].'],['.$tmcb[18].','.$c[70].'],['.$tmcb[19].','.$c[71].'],['.$tmcb[20].','.$c[72].'],['.$tmcb[21].','.$c[73].'],['.$tmcb[22].','.$c[74].'],['.$tmcb[23].','.$c[75].'],['.$tmcb[24].','.$c[76].'],['.$tmcb[25].','.$c[77].']]';
// $rain 26 datapoints for last 24 Hr rain graph 1/hr plus one each end
$rain = '[['.$tmcb[0].','.$c[78].'],['.$tmcb[1].','.$c[79].'],['.$tmcb[2].','.$c[80].'],['.$tmcb[3].','.$c[81].'],['.$tmcb[4].','.$c[82].'],['.$tmcb[5].','.$c[83].'],['.$tmcb[6].','.$c[84].'],['.$tmcb[7].','.$c[85].'],['.$tmcb[8].','.$c[86].'],['.$tmcb[9].','.$c[87].'],['.$tmcb[10].','.$c[88].'],['.$tmcb[11].','.$c[89].'],['.$tmcb[12].','.$c[90].'],['.$tmcb[13].','.$c[91].'],['.$tmcb[14].','.$c[92].'],['.$tmcb[15].','.$c[93].'],['.$tmcb[16].','.$c[94].'],['.$tmcb[17].','.$c[95].'],['.$tmcb[18].','.$c[96].'],['.$tmcb[19].','.$c[97].'],['.$tmcb[20].','.$c[98].'],['.$tmcb[21].','.$c[99].'],['.$tmcb[22].','.$c[100].'],['.$tmcb[23].','.$c[101].'],['.$tmcb[24].','.$c[102].'],['.$tmcb[25].','.$c[103].']]';
// $baro 26 datapoints for last 24 Hr baro graph 1/hr plus one each end
$baro = '[['.$tmcb[0].','.$c[104].'],['.$tmcb[1].','.$c[105].'],['.$tmcb[2].','.$c[106].'],['.$tmcb[3].','.$c[107].'],['.$tmcb[4].','.$c[108].'],['.$tmcb[5].','.$c[109].'],['.$tmcb[6].','.$c[110].'],['.$tmcb[7].','.$c[111].'],['.$tmcb[8].','.$c[112].'],['.$tmcb[9].','.$c[113].'],['.$tmcb[10].','.$c[114].'],['.$tmcb[11].','.$c[115].'],['.$tmcb[12].','.$c[116].'],['.$tmcb[13].','.$c[117].'],['.$tmcb[14].','.$c[118].'],['.$tmcb[15].','.$c[119].'],['.$tmcb[16].','.$c[120].'],['.$tmcb[17].','.$c[121].'],['.$tmcb[18].','.$c[122].'],['.$tmcb[19].','.$c[123].'],['.$tmcb[20].','.$c[124].'],['.$tmcb[21].','.$c[125].'],['.$tmcb[22].','.$c[126].'],['.$tmcb[23].','.$c[127].'],['.$tmcb[24].','.$c[128].'],['.$tmcb[25].','.$c[129].']]';
// solr not used in Vue but required for common php code
$solr = '[['.$tmcb[0].','.$c[131].'],['.$tmcb[1].','.$c[132].'],['.$tmcb[2].','.$c[133].'],['.$tmcb[3].','.$c[134].'],['.$tmcb[4].','.$c[135].'],['.$tmcb[5].','.$c[136].'],['.$tmcb[6].','.$c[137].'],['.$tmcb[7].','.$c[138].'],['.$tmcb[8].','.$c[139].'],['.$tmcb[9].','.$c[140].'],['.$tmcb[10].','.$c[141].'],['.$tmcb[11].','.$c[142].'],['.$tmcb[12].','.$c[143].'],['.$tmcb[13].','.$c[144].'],['.$tmcb[14].','.$c[145].'],['.$tmcb[15].','.$c[146].'],['.$tmcb[16].','.$c[147].'],['.$tmcb[17].','.$c[148].'],['.$tmcb[18].','.$c[149].'],['.$tmcb[19].','.$c[150].'],['.$tmcb[20].','.$c[151].'],['.$tmcb[21].','.$c[152].'],['.$tmcb[22].','.$c[153].'],['.$tmcb[23].','.$c[154].'],['.$tmcb[24].','.$c[155].']]';
// extract max & min values for each graph data type to display max & min values as graph scale is not constant but scales to data range
$t = array_slice($c, 0, 26);
  $tmax = max($t);
  $tmin = min($t);
$h = array_slice($c, 26, 26);
  $hmax = max($h);
  $hmin = min($h);
$w = array_slice($c, 52, 26);
  $wmax = max($w);
  $wmin = min($w);
$r = array_slice($c, 78, 26);
  $rmax = round(max($r),1);
  $rmin = round(min($r),1);
$b = array_slice($c, 104, 26);
  $bmax = round(max($b),1);
  $bmin = round(min($b),1);
$s = array_slice($c, 131, 25);  // solar not used in Vue but required for common php code
  $smax = max($s);
  $smin = min($s);
// END CU graph data
$moonage = $c[156]; // $moonage not standard part of CUtags.php / CU-defs.php
$daynite = ($c[159] == '1') ? 'D' : 'N';

// BEGIN WD graph data
} elseif ($WXsoftware == 'WD') {
   if (file_exists($graphurl)) {
      for($i=0;$i<25;$i++){  // 24 data points - duplicate LH data point to fill VP2 graph to match grid, Vue graph one data point short
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
$stormmin = ($uomRain == 'mm')? 0.4 : 0.02;
if (!isset($vpstormrain) || $vpstormrain < $stormmin) {$dayrnrotate = 1; $vpstormrain = 0;}
$showsolar = 'N';
?>
<script type="text/javascript">
var n=0;
function BackLight() {
   n++;
   if (n%2)
      document.getElementById('vueconsole').style.backgroundImage = "url('./davcon/vue_console.png')";
   else
      document.getElementById('vueconsole').style.backgroundImage = "url('./davcon/vue_console_lit.png')";
}
</script>
<div id="main-copy">
<noscript><h1><font color = "red">ENABLE JAVASCRIPT FOR LIVE UPDATES!</font></h1></noscript>
<div style="text-align:left; margin:0 auto; width:600px;">
<br />
<div class="vueconsole" id="vueconsole">
<!-- TIME DATE -->
<span class="vajax" id="cajaxhhmm" style="font-size: 20px; top: 102px; right: 480px;"></span>
<span class="small" id="cajaxampm" style="top: 104px; right: 464px;"></span>
<span class="vajax" id="cajaxddmo" style="font-size: 20px; top: 102px; right: 415px;"></span>

<!-- MOON ICON -->
<span class="vajax" id="cajaxmoon" style="top: 142px; left: 195px;"></span>

<!-- FORECAST ICON -->
<span class="vajax" id="cajaxicon" style="top: 168px; left: 195px;"></span>

<!-- WIND -->
<span class="vajax" id="wdir" style="height:90px; width:90px; top: 132px; left: 90px;"></span>
<span class="small" id="cajaxwindu" style="top:148px; left: 130px;"></span>
<span class="vajax" id="cajaxwind" style="top: 167px; right: 444px;"></span>
<span class="small" id="cajaxwinddu" style="top:161px; left: 158px;"></span>


<!-- TEMP INSIDE -->
<span class="vajax"  id="cajaxitemp" style="top: 105px; right: 322px;"></span>
<span class="smallb" style="top:100px; left: 280px;"><?php echo $uomTemp; ?></span>
<span class="small"  style="top: 123px; left: 248px;">INSIDE</span>

<!-- TEMP OUTSIDE -->
<span class="vajax" id="cajaxtemp" style="top: 105px; right: 238px;"></span>
<span class="smallb" style="top:100px; left: 365px;"><?php echo $uomTemp; ?></span>
<span class="small" style= "top: 123px; left: 324px;">OUTSIDE</span>

<!-- HUMIDITY OUTSIDE -->
<span class="vajax" id="cajaxhumidity" style="top: 142px;  right: 233px;"></span>

<!-- HUMIDITY INSIDE -->
<span class="vajax" id="cajaxihumidity" style="top: 142px;  right: 321px;"></span>

<!-- BAROMETER -->
<span class="vajax" id="cajaxbaroarrow" style="height:30px; width:30px; top:159px; left:355px; z-index:100;"></span>
<span class="vajax" id="cajaxbaro" style="top: 170px; right: 245px;"></span>
<span class="small" style="top:176px; left: 360px;"><?php echo $uomBaro; ?></span>

<!-- APPARENT TEMP -->
<span class="small"  id="app" style="top:195px; right:355px;">Apparent</span>
<span class="vajax"  id="cajaxapp" style="top: 213px; right: 355px;"></span>
<span class="smallb" style="top:207px; left: 248px;"><?php echo $uomTemp; ?></span>

<!-- Is Raining or snow ICON -->
<span class="vajax" id="cajaxumbr" style="top:212px; right:312px;"></span>

<!-- DAILY RAIN -->
<span class="small" id="rrd" style="top:195px; right:255px;">RAIN&nbsp;&nbsp;DAY</span>
<span class="vajax" id="cajaxrain" style="top:213px; right:255px;"></span>
<span class="small" id="rrt" style="top:218px; left:350px;"><?php echo $uomRain; ?></span>

<!-- RAIN RATE -->
<span class="small" id="rrh" style="top: 235px; right: 255px;">RAIN&nbsp;&nbsp;RATE</span>
<span class="vajax" id="cajaxrainratehr" style="top: 253px; right: 255px;"></span>
<span class="small" id="rrth" style="top: 252px; left: 350px;"><?php echo $uomRain; ?>/h</span>

<!-- FORECAST TEXT -->
<div id="vue_scroller_container">
 <div class="jscroller2_left jscroller2_speed-50 jscroller2_mousemove jscroller2_ignoreleave">
 <?php langtrans($vpforecasttext); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
 <div class="jscroller2_left_endless jscroller2"><?php langtrans($vpforecasttext); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
</div>

<!-- GRAPH LABEL -->
<span class="small" id="grhrs" style="position:absolute; top:227px; left:77px;"><?php echo "24 x 1hr"; ?></span>
<span class="small" id="grlab" style="position:absolute; top:227px; left:155px;"><?php echo "TEMP"; ?></span>
<span class="small" id="grmax" style="position:absolute; top:238px; left:231px;"><?php echo $tmax; ?></span>
<span class="small" id="grmin" style="position:absolute; top:297px; left:231px;"><?php echo $tmin; ?></span>
<!-- GRAPH WINDOW -->
<div id="placeholder" style="height:62px; width:148px; position:absolute; top:246px; left:79px;"></div>

<!-- BUTTONS Console-->
<div id="lampsbtn" onclick="BackLight()" style="cursor:pointer; height:27px; width:43px; position:absolute; top:410px; left:90px"></div>
<div id="tempbtn"  style="cursor:pointer; height:27px; width:43px; position:absolute; top:410px; left:140px;"></div>
<div id="humbtn"   style="cursor:pointer; height:27px; width:43px; position:absolute; top:410px; left:190px;"></div>
<div id="windbtn"  style="cursor:pointer; height:27px; width:43px; position:absolute; top:410px; left:240px;"></div>
<div id="rainbtn"  style="cursor:pointer; height:27px; width:43px; position:absolute; top:410px; left:290px;"></div>
<div id="barbtn"   style="cursor:pointer; height:27px; width:43px; position:absolute; top:410px; left:340px;"></div>
<!-- <div id="secndbtn" style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:90px;" ></div> -->
<?php   if($WxCenbtntxt != "") { ?>
   <?php if ($taboption===2||$taboption===3) { ?><div id="WxCenbtn"
      <?php if ($showtooltip) { ?> vdata-tip="NewTab" onclick="window.open('<?php echo $WxCenbtn ?>')"
      <?php } else { ?> onclick="window.open('<?php echo $WxCenbtn ?>')" <?php } ?>
   <?php } else { ?><div id="WxCenbtn" onclick="top.location.href=WxCenbtn" <?php } ?>
   style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:140px"></div>
<?php } if($graphbtntxt != "") { ?>
   <?php if ($taboption===2||$taboption===3) { ?><div id="graphbtn"
      <?php if ($showtooltip) { ?> vdata-tip="NewTab" onclick="window.open('<?php echo $graphbtn ?>')"
      <?php } else { ?> onclick="window.open('<?php echo $graphbtn ?>')" <?php } ?>
   <?php } else { ?><div id="graphbtn" onclick="top.location.href=graphbtn" <?php } ?>
   style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:190px"></div>
<?php } if($hilowbtntxt != "") { ?>
   <?php if ($taboption===2||$taboption===3) { ?><div id="hilowbtn"
      <?php if ($showtooltip) { ?> vdata-tip="NewTab" onclick="window.open('<?php echo $hilowbtn ?>')"
      <?php } else { ?> onclick="window.open('<?php echo $hilowbtn ?>')" <?php } ?>
   <?php } else { ?><div id="hilowbtn" onclick="top.location.href=hilowbtn" <?php } ?>
   style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:240px"></div>
<?php } if($timebtntxt != "") { ?>
   <?php if ($taboption===2||$taboption===3) { ?><div id="timebtn"
      <?php if ($showtooltip) { ?> vdata-tip="NewTab" onclick="window.open('<?php echo $timebtn ?>')"
      <?php } else { ?> onclick="window.open('<?php echo $timebtn ?>')" <?php } ?>
   <?php } else { ?><div id="timebtn" onclick="top.location.href=timebtn" <?php } ?>
   style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:290px"></div>
<!-- Use this format for returning to index.php -->
<?php if($donebtntxt != "") { ?>
   <div id="donebtn" onclick="top.location.href=donebtn"
   style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:340px"></div>
<?php } ?>
<!-- -->
<!-- Use this format for links to other pages -->
<!--
<?php } if($donebtntxt != "") { ?>
   <?php if ($taboption===2||$taboption===3) { ?><div id="donebtn"
      <?php if ($showtooltip) { ?> vdata-tip="NewTab" onclick="window.open('<?php echo $donebtn ?>')"
      <?php } else { ?> onclick="window.open('<?php echo $donebtn ?>')" <?php } ?>
   <?php } else { ?><div id="donebtn" onclick="top.location.href=donebtn" <?php } ?>
   style="cursor:pointer; height:27px; width:43px; position:absolute; top:448px; left:340px"></div>
<?php } ?>
-->

</div>

<div id="preload" style="display:none"><img src="./davcon/vue_console.png" alt="" /></div>

<table width="650px">
   <col width="10%"/>
   <col width="40%"/>
   <col width="10%"/>
<tr>
   <?php if ($showupdate) { ?>
   <td colspan = "2">
      <?php echo 'Next Graph and Forecast update @ ~ '; echo $timeofnextupdate; ?>
   </td>
   <?php }
   if(isset($SITE['ajaxScript']) && $showage) { ?>
   <td colspan = "2">
      <?php echo '"Realtime" data updated '; ?> <b><span id="ajaxcounter"></span></b> <?php echo 'secs ago'; ?>
   </td>
   <?php } ?>
</tr>
<tr>
   <td></td>
</tr>
<tr>
   <td colspan="2"><b>Click Buttons on Console or Table</b></td>
</tr>
<tr>
   <td><input type="button" class="btnwhi" style="cursor:pointer" value="LIGHT" onclick="BackLight()" /></td>
   <td>Turn Backlight on/off</td>
</tr>
<tr>
   <td><input type="button" class="btnbrn" style="cursor:pointer" value="TEMP" id="tempbtnt" /></td>
   <td>24hr Outside Temperature Graph</td>
   <?php if($WxCenbtntxt == "") { ?>
      <td><input type="button" class="btnbrn" value="WxCen" disabled /></td>
   <?php } else { ?>   
      <?php if ($taboption===1||$taboption===3) { ?>
         <?php if ($showtooltip) { ?> <td><div data-tip="NewTab"><input type="button" class="btnbrn" value="WxCen" style="cursor:pointer" onclick="window.open('<?php echo $WxCenbtn ?>')" /></div></td>
         <?php } else { ?> <td><input type="button" class="btnbrn" value="WxCen" style="cursor:pointer" onclick="window.open('<?php echo $WxCenbtn ?>')" /></td> <?php } ?>
      <?php } else { ?> <td><input type="button" class="btnbrn" value="WxCen" style="cursor:pointer" onclick="parent.location= '<?php echo $WxCenbtn ?>' " /></td> <?php } ?>
      <td><?php echo $WxCenbtntxt; ?></td>
   <?php } ?>
</tr>
<tr>
   <td><input type="button" class="btnbrn" style="cursor:pointer" value="HUM" id="humbtnt"/></td>
   <td>24hr Outside Humidity Graph</td>
   <?php if($graphbtntxt == "") { ?>
      <td><input type="button" class="btnbrn" value="GRAPH" disabled="disabled" /></td>
   <?php } else { ?>   
      <?php if ($taboption===1||$taboption===3) { ?>
         <?php if ($showtooltip) { ?> <td><div data-tip="NewTab"><input type="button" class="btnbrn" value="GRAPH" style="cursor:pointer" onclick="window.open('<?php echo $graphbtn ?>')" /></div></td>
         <?php } else { ?> <td><input type="button" class="btnbrn" value="GRAPH" style="cursor:pointer" onclick="window.open('<?php echo $graphbtn ?>')" /></td> <?php } ?>
      <?php } else { ?> <td><input type="button" class="btnbrn" value="GRAPH" style="cursor:pointer" onclick="parent.location = '<?php echo $graphbtn ?>' " /></td> <?php } ?>
      <td><?php echo $graphbtntxt; ?></td>
   <?php } ?>
</tr>
<tr>
   <td><input type="button" class="btnbrn" style="cursor:pointer" value="WIND" id="windbtnt"/></td>
   <td>24hr Windspeed Graph</td>
   <?php if($hilowbtntxt == "") { ?>
      <td><input type="button" class="btnbrn" value="HI/LOW" disabled="disabled" /></td>
   <?php } else { ?>   
      <?php if ($taboption===1||$taboption===3) { ?>
         <?php if ($showtooltip) { ?> <td><div data-tip="NewTab"><input type="button" class="btnbrn" value="HI/LOW" style="cursor:pointer" onclick="window.open('<?php echo $hilowbtn ?>')" /></div></td>
         <?php } else { ?> <td><input type="button" class="btnbrn" value="HI/LOW" style="cursor:pointer" onclick="window.open('<?php echo $hilowbtn ?>')" /></td> <?php } ?>
      <?php } else { ?> <td><input type="button" class="btnbrn" value="HI/LOW" style="cursor:pointer" onclick="parent.location = '<?php echo $hilowbtn ?>' " /></td> <?php } ?>
      <td><?php echo $hilowbtntxt; ?></td>
   <?php } ?>
</tr>
<tr>
   <td><input type="button" class="btnbrn" style="cursor:pointer" value="RAIN" id="rainbtnt"/></td>
   <td>24hr Rain Graph</td>
   <?php if($timebtntxt == "") { ?>
      <td><input type="button" class="btnbrn" value="TIME" disabled="disabled" /></td>
   <?php } else { ?>   
      <?php if ($taboption===1||$taboption===3) { ?>
         <?php if ($showtooltip) { ?> <td><div data-tip="NewTab"><input type="button" class="btnbrn" value="TIME" style="cursor:pointer" onclick="window.open('<?php echo $timebtn ?>')" /></div></td>
         <?php } else { ?> <td><input type="button" class="btnbrn" value="TIME" style="cursor:pointer" onclick="window.open('<?php echo $timebtn ?>')" /></td> <?php } ?>
      <?php } else { ?> <td><input type="button" class="btnbrn" value="TIME" style="cursor:pointer" onclick="parent.location = '<?php echo $timebtn ?>' " /></td> <?php } ?>
      <td><?php echo $timebtntxt; ?></td>
   <?php } ?>
</tr>
<tr>
   <td><input type="button" class="btnbrn" style="cursor:pointer" value="BAR" id="barbtnt"/></td>
   <td>24hr Barometer Graph</td>
   <?php if($donebtntxt == "") { ?>
      <td><input type="button" class="btnbrn" value="DONE" disabled="disabled" /></td>
   <?php } else { ?>   
<!-- Use this format for returning to index.php -->
      <td><input type="button" class="btnbrn" value="DONE" style="cursor:pointer" onclick="parent.location = '<?php echo $donebtn ?>' " /></td>
<!-- -->      
<!-- Use this format for links to other pages -->
<!--
      <?php if ($taboption===1||$taboption===3) { ?>
         <?php if ($showtooltip) { ?> <td><div data-tip="NewTab"><input type="button" class="btnbrn" value="DONE" style="cursor:pointer" onclick="window.open('<?php echo $donebtn ?>')" /></div></td>
         <?php } else { ?> <td><input type="button" class="btnbrn" value="DONE" style="cursor:pointer" onclick="window.open('<?php echo $donebtn ?>')" /></td> <?php } ?>
      <?php } else { ?> <td><input type="button" class="btnbrn" value="DONE" style="cursor:pointer" onclick="parent.location = '<?php echo $donebtn ?>' " /></td> <?php } ?>
-->
      <td><?php echo $donebtntxt; ?></td>
   <?php } ?>
</tr>
</table>
</div>
