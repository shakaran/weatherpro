<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName		= 'astronomy.php';
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
#  define sun moon functions only when not used elsewhere
#-----------------------------------------------------------------------
echo '<!--  check if moon functions exist -->'.PHP_EOL;
if (!function_exists ('getUSNOsunmoon')) {
echo '<!--  moon functions do not exist -->'.PHP_EOL;
# ----------------------------------------------------------------------
# getUSNOsunmoon
# ----------------------------------------------------------------------
function getUSNOsunmoon() {
global $SITE, $myLong, $myLat, $sunMoonInfo;
$Z	= round(date(" Z",time()) / 3600); 
#
$USNOUrl = "http://api.usno.navy.mil/rstt/oneday?date=today&coords=$myLat,$myLong&tz=$Z";
/*What we need is:
$sunMoonInfo =>
    [date] => 1438186636
    [error] => 
    [beginciviltwilight] => 1438163580
    [sunrise] => 1438165440
    [suntransit] => 1438191240
    [sunset] => 1438217040
    [endciviltwilight] => 1438218900
    [moonrise] => 1438211340
    [moontransit] => 1438230000
    [moonset] => 1438248780
    	[prev_moonrise] => 1438191840     optional
     	[prev_moontransit] => 1438230000  optional
    	[prev_moonset] => 1438191840      optional
    	[next_moonrise] => 1438191840     optional
    	[next_moontransit] => 1438230000  optional
    	[next_moonset] => 1438191840      optional    
    [moonPhase] => waxing gibbous 
    [moonPerc] => 96
    [hoursofpossibledaylight] => 14:20
*/
# USNO returns info like we use for test where the codes are:
# BC = Begin civil twilight
# R = Rise
# U = Upper Transit
# S = Set
# EC = End civil twilight

$html = '{	
"error":false,
"apiversion":"1.0",
"year":2015, "month":7,"day":30,
"datechanged":false,
"lon":-72.79,"lat":41.30,
"tz":-4,  
"sundata":[
   	{"phen":"BC", "time":"05:13"},
    	{"phen":"R", "time":"05:44"},
      	{"phen":"U", "time":"12:58"},
        {"phen":"S", "time":"20:10"},
        {"phen":"EC", "time":"20:42"}
        ],
"moondata":[
 	{"phen":"S", "time":"04:51"},
        {"phen":"R", "time":"19:25"} 
        ] , 
"prevmoondata": [ 
	{"phen":"R","time":"18:35"} , 
	{"phen":"U","time":"23:42"} 
	], 
"closestphase":{ "phase":"Full Moon", "date":"July 31, 2015","time":"06:43"},
 "fracillum":"99%" , 
 "curphase":"Waxing Gibbous"
  }';
$test = false;
if (!$test) {
	$html = usno_makeRequest($USNOUrl,''); // load sun moon data from usno site
}         
#
$MoonJSON 	= json_decode($html,true);
if (!is_array($MoonJSON) ) 		{$sunMoonInfo['error'] = 'invalid data - no array'; return false;}
elseif (!isset ($MoonJSON['error']) ) 	{$sunMoonInfo['error'] = 'invalid data - missing error field';}
elseif ($MoonJSON['error'] <> false) 	{$sunMoonInfo['error'] = $MoonJSON['error'];}
#
$dateprior 	= date('Y/m/d',strtotime("-1 day"));
$datenow   	= date('Y/m/d');
$datenext  	= date('Y/m/d',strtotime("+1 day"));
$from		= strtotime ($datenow. ' 00:00');
$to		= strtotime ($datenext.' 00:00');
$sunMoonInfo['beginciviltwilight']	= '$from';
$sunMoonInfo['sunrise']			= '$from';
$sunMoonInfo['suntransit']		= strtotime ($datenow. ' 12:00');
$sunMoonInfo['sunset']			= '$to';
$sunMoonInfo['endciviltwilight']	= '$to';

$suntrans['BC']	= 'beginciviltwilight';
$suntrans['R']	= 'sunrise';
$suntrans['U']	= 'suntransit';
$suntrans['S']	= 'sunset';
$suntrans['EC']	= 'endciviltwilight';


$count	= count ($MoonJSON['sundata']);
for ($n1 = 0; $n1 < $count; $n1++) {
	$arr =	$MoonJSON['sundata'][$n1];
	$key			= $arr['phen'];
	$time			= $arr['time'];
	$unix			= strtotime ($datenow.' '.	$time);
	$new_key		= $suntrans[$key];
	$sunMoonInfo[$new_key]	= $unix;
}
$moontrans['R']	= 'moonrise';
$moontrans['U']	= 'moonransit';
$moontrans['S']	= 'moonset';	

$count	= count ($MoonJSON['moondata']);
for ($n1 = 0; $n1 < $count; $n1++) {
	$arr 		= $MoonJSON['moondata'][$n1];
	$key		= $arr['phen'];
	$time		= $arr['time'];
	$unix		= strtotime ($datenow.' '.$time);
	$new_key	= $moontrans[$key];
	$sunMoonInfo[$new_key]	= $unix;
}

if (isset ($MoonJSON['prevmoondata']) ) {
#	usno_traverse ($MoonJSON['prevmoondata'], $dateprior);
	$count	= count ($MoonJSON['prevmoondata']);
	for ($n1 = 0; $n1 < $count; $n1++) {
		$arr 		= $MoonJSON['prevmoondata'][$n1];
		$key		= $arr['phen'];
		$time		= $arr['time'];
		$unix		= strtotime ($dateprior.' '.$time);
		$new_key	= $moontrans[$key];
		if (isset ($sunMoonInfo[$new_key]) ) {
			$new_key	= 'prev_'.$new_key;
		}
		$sunMoonInfo[$new_key]	= $unix;
	}
}
if (isset ($MoonJSON['nextmoondata']) ) {
#	usno_traverse ($MoonJSON['nextmoondata'], $datenext);
	$count	= count ($MoonJSON['nextmoondata']);
	for ($n1 = 0; $n1 < $count; $n1++) {
		$arr 		= $MoonJSON['nextmoondata'][$n1];
		$key		= $arr['phen'];
		$time		= $arr['time'];
		$unix		= strtotime ($datenext.' '.$time);
		$new_key	= $moontrans[$key];
		if (isset ($sunMoonInfo[$new_key]) ) {
			$new_key	= 'next_'.$new_key;
		}
		$sunMoonInfo[$new_key]	= $unix;
	}
} 
if (isset ($MoonJSON['curphase']) )  {
	$sunMoonInfo['moonPhase']	= $MoonJSON['curphase'];
}
elseif (isset ($MoonJSON['closestphase']['phase']) ) {
	$sunMoonInfo['moonPhase']	= $MoonJSON['closestphase']['phase'];
	$phase	= strtolower(str_replace (' ','',$sunMoonInfo['moonPhase']) );
	switch ($phase) {
		case 'newmoon' :
			$sunMoonInfo['moonPerc']='0%';	
		break;
		case 'fullmoon' :
			$sunMoonInfo['moonPerc']='100%';
		break;
		default :
			$sunMoonInfo['moonPerc']='50%';
		break;
	}
	
}
if (isset ($MoonJSON['fracillum']) ) {$sunMoonInfo['moonPerc']	= $MoonJSON['fracillum'];}
if(isset($sunMoonInfo['sunrise']) and isset($sunMoonInfo['sunset'])) {
	$diff =	$sunMoonInfo['sunset']-$sunMoonInfo['sunrise'];
	$diffh = intval($diff/3600); // hours
	$diffm = intval(($diff / 60) % 60);
	$sunMoonInfo['hoursofpossibledaylight'] = sprintf("%02d:%02d",$diffh,$diffm);
}
#echo '<pre>'.print_r($sunMoonInfo,true); exit;
return;
} // end of getUSNOsunmoon function
# -----------------------------------------------------------------------------
# MOON FUNTIONS  Courtesy of Bashewa Weather, PHP conversion by WebsterWeather from ajaxWDwx.js V9.13 (WD)                                                             .
# -----------------------------------------------------------------------------
function wsGetMoonInfo ($time='') { // very crude way of determining moon phase (but very accurate)
// ------------- start of USNO moon data -----------------------------
// PHP tables generated from USNO moon ephemeris data http://aa.usno.navy.mil/data/docs/MoonPhase.php
// using the one-year at a time query option
// Ken True - Saratoga-weather.org generated by USNO-moonphases.php - Version 1.00 - 15-Jan-2011 on 15 January 2011 21:48 EST

$newMoons = array( // unixtime values in UTC/GMT
/* 2014 */ /* 01-Jan-2014 11:14 */ 1388574840, 1391117880, 1393660800, 1396205100, 1398752040, 1401302400, 1403856480, 1406414520, 1408975980, 1411539240, 1414101420, 1416659520, 1419212160, 
/* 2015 */ /* 20-Jan-2015 13:14 */ 1421759640, 1424303220, 1426844160, 1429383420, 1431922380, 1434463500, 1437009840, 1439563980, 1442126460, 1444694760, 1447264020, 1449829740, 
/* 2016 */ /* 10-Jan-2016 01:30 */ 1452389400, 1454942340, 1457488440, 1460028240, 1462562940, 1465095540, 1467630060, 1470170640, 1472720580, 1475280660, 1477849080, 1480421880, 1482994380, 
/* 2017 */ /* 28-Jan-2017 00:07 */ 1485562020, 1488121080, 1490669820, 1493208960, 1495741440, 1498271460, 1500803100, 1503340200, 1505885400, 1508440320, 1511005320, 1513578600, 
/* 2018 */ /* 17-Jan-2018 02:17 */ 1516155420, 1518728700, 1521292260, 1523843820, 1526384880, 1528918980, 1531450080, 1533981480, 1536516060, 1539056820, 1541606520, 1544167200, 
/* 2019 */ /* 06-Jan-2019 01:28 */ 1546738080, 1549314180, 1551888240, 1554454200, 1557009900, 1559556120, 1562094960, 1564629120, 1567161420, 1569695160, 1572233880, 1574780700, 1577337180, 
/* 2020 */ /* 24-Jan-2020 21:42 */ 1579902120, 1582471920, 1585042080, 1587608760, 1590169140, 1592721660, 1595266380, 1597804920, 1600340400, 1602876660, 1605416820, 1607962560, 
/* 2021 */ /* 13-Jan-2021 05:00 */ 1610514000, 1613070360, 1615630860, 1618194660, 1620759600, 1623322380, 1625879760, 1628430600, 1630975920, 1633518300, 1636060440, 1638603780, 
/* 2022 */ /* 02-Jan-2022 18:33 */ 1641148380, 1643694360, 1646242500, 1648794240, 1651350480, 1653910200, 1656471120, 1659030900, 1661588220, 1664142840, 1666694940, 1669244220, 1671790620, 
/* 2023 */ /* 21-Jan-2023 20:53 */ 1674334380, 1676876760, 1679419380, 1681963920, 1684511580, 1687063020, 1689618720, 1692178680, 1694742000, 1697306100, 1699867620
); /* end of newMoons array */

$Q1Moons = array( // unixtime values in UTC/GMT
/* 2014 */ /* 08-Jan-2014 03:39 */ 1389152340, 1391714520, 1394285220, 1396859460, 1399432500, 1402000740, 1404561540, 1407113400, 1409656260, 1412191920, 1414723680, 1417255560, 1419791460, 
/* 2015 */ /* 27-Jan-2015 04:48 */ 1422334080, 1424884440, 1427442180, 1430006100, 1432574340, 1435143720, 1437710640, 1440271860, 1442825940, 1445373060, 1447914420, 1450451640, 
/* 2016 */ /* 16-Jan-2016 23:26 */ 1452986760, 1455522360, 1458061380, 1460606340, 1463158920, 1465719000, 1468284720, 1470853260, 1473421740, 1475987580, 1478548260, 1481101380, 
/* 2017 */ /* 05-Jan-2017 19:47 */ 1483645620, 1486181940, 1488713520, 1491244740, 1493779620, 1496320920, 1498870260, 1501428180, 1503994380, 1506567180, 1509142920, 1511715780, 1514280000, 
/* 2018 */ /* 24-Jan-2018 22:20 */ 1516832400, 1519373340, 1521905700, 1524433500, 1526960940, 1529491860, 1532029920, 1534578480, 1537139700, 1539712920, 1542293640, 1544874540, 
/* 2019 */ /* 14-Jan-2019 06:45 */ 1547448300, 1550010360, 1552559220, 1555095960, 1557623520, 1560146340, 1562669700, 1565199060, 1567739400, 1570294020, 1572862980, 1575442680, 
/* 2020 */ /* 03-Jan-2020 04:45 */ 1578026700, 1580607720, 1583179020, 1585736460, 1588279080, 1590809400, 1593332160, 1595853120, 1598378280, 1600912500, 1603459380, 1606020300, 1608594060, 
/* 2021 */ /* 20-Jan-2021 21:01 */ 1611176460, 1613760420, 1616337600, 1618901940, 1621451580, 1623988440, 1626516660, 1629040740, 1631565540, 1634095500, 1636634760, 1639186500, 
/* 2022 */ /* 09-Jan-2022 18:11 */ 1641751860, 1644328200, 1646909100, 1649486880, 1652055660, 1654613280, 1657160040, 1659697560, 1662228480, 1664756040, 1667284620, 1669818960, 1672363200, 
/* 2023 */ /* 28-Jan-2023 15:19 */ 1674919140, 1677485100, 1680057120, 1682630400, 1685200920, 1687765800, 1690322820, 1692871020, 1695411120, 1697945340, 1700477400
); /* end of Q1Moons array */

$fullMoons = array( // unixtime values in UTC/GMT
/* 2014 */ /* 16-Jan-2014 04:52 */ 1389847920, 1392421980, 1394989680, 1397547720, 1400094960, 1402632660, 1405164300, 1407694140, 1410226680, 1412765460, 1415312580, 1417868820, 
/* 2015 */ /* 05-Jan-2015 04:53 */ 1420433580, 1423004940, 1425578700, 1428149100, 1430710920, 1433261940, 1435803600, 1438339380, 1440873300, 1443408600, 1445947500, 1448491440, 1451041860, 
/* 2016 */ /* 24-Jan-2016 01:46 */ 1453599960, 1456165200, 1458734460, 1461302640, 1463865240, 1466420520, 1468968960, 1471512360, 1474052700, 1476591780, 1479131520, 1481673900, 
/* 2017 */ /* 12-Jan-2017 11:34 */ 1484220840, 1486773180, 1489330440, 1491890880, 1494452520, 1497013800, 1499573160, 1502129460, 1504681380, 1507228800, 1509772980, 1512316020, 
/* 2018 */ /* 02-Jan-2018 02:24 */ 1514859840, 1517405220, 1519951860, 1522499820, 1525049880, 1527603540, 1530161580, 1532722800, 1535284560, 1537843920, 1540399500, 1542951540, 1545500880, 
/* 2019 */ /* 21-Jan-2019 05:16 */ 1548047760, 1550591580, 1553132580, 1555672320, 1558213860, 1560760260, 1563313080, 1565872140, 1568435580, 1571000880, 1573565640, 1576127520, 
/* 2020 */ /* 10-Jan-2020 19:21 */ 1578684060, 1581233580, 1583776080, 1586313300, 1588848300, 1591384320, 1593924240, 1596470340, 1599024120, 1601586300, 1604155740, 1606728600, 1609298880, 
/* 2021 */ /* 28-Jan-2021 19:16 */ 1611861360, 1614413820, 1616957280, 1619494260, 1622027640, 1624560000, 1627094220, 1629633720, 1632182100, 1634741820, 1637312220, 1639888500, 
/* 2022 */ /* 17-Jan-2022 23:48 */ 1642463280, 1645030560, 1647587820, 1650135300, 1652674440, 1655207520, 1657737480, 1660268160, 1662803940, 1665348900, 1667905320, 1670472480, 
/* 2023 */ /* 06-Jan-2023 23:08 */ 1673046480, 1675621680, 1678192800, 1680755640, 1683308040, 1685850120, 1688384340, 1690914720, 1693445700, 1695981420, 1698524640, 1701076560
); /* end of fullMoons array */

$Q3Moons = array( // unixtime values in UTC/GMT
/* 2014 */ /* 24-Jan-2014 05:20 */ 1390540800, 1393089300, 1395625560, 1398153120, 1400677140, 1403203140, 1405735680, 1408278360, 1410833100, 1413400320, 1415978100, 1418561460, 
/* 2015 */ /* 13-Jan-2015 09:46 */ 1421142360, 1423713000, 1426268880, 1428810240, 1431340560, 1433864520, 1436387040, 1438912980, 1441446840, 1443992760, 1446553440, 1449128400, 
/* 2016 */ /* 02-Jan-2016 05:30 */ 1451712600, 1454297280, 1456873860, 1459437420, 1461986940, 1464523920, 1467051540, 1469574000, 1472096460, 1474624560, 1477163640, 1479717180, 1482285360, 
/* 2017 */ /* 19-Jan-2017 22:13 */ 1484863980, 1487446380, 1490025480, 1492595820, 1495153980, 1497699180, 1500233160, 1502759700, 1505283900, 1507811100, 1510346160, 1512892260, 
/* 2018 */ /* 08-Jan-2018 22:25 */ 1515450300, 1518018840, 1520594400, 1523171820, 1525745340, 1528309920, 1530863460, 1533406680, 1535942220, 1538473500, 1541004000, 1543537140, 1546076040, 
/* 2019 */ /* 27-Jan-2019 21:10 */ 1548623400, 1551180480, 1553746200, 1556317080, 1558888380, 1561455960, 1564017480, 1566572160, 1569120060, 1571661540, 1574197860, 1576731420, 
/* 2020 */ /* 17-Jan-2020 12:58 */ 1579265880, 1581805020, 1584351240, 1586904960, 1589464980, 1592029440, 1594596540, 1597164300, 1599729960, 1602290340, 1604843160, 1607387760, 
/* 2021 */ /* 06-Jan-2021 09:37 */ 1609925820, 1612460220, 1614994200, 1617530520, 1620071400, 1622618640, 1625173860, 1627737360, 1630307580, 1632880620, 1635451500, 1638016080, 1640571840, 
/* 2022 */ /* 25-Jan-2022 13:41 */ 1643118060, 1645655520, 1648186620, 1650714960, 1653244980, 1655781060, 1658326680, 1660883760, 1663451520, 1666026900, 1668605220, 1671180960, 
/* 2023 */ /* 15-Jan-2023 02:10 */ 1673748600, 1676304060, 1678846080, 1681377060, 1683901680, 1686425460, 1688953680, 1691490480, 1694038860, 1696600080, 1699173420, 1701755340
); /* end of Q3Moons array */

// ------------- end of USNO moon data -----------------------------
	global $sunMoonInfo;
	if (empty($time)) {$date = time();} else {$date = $time;}	
//	$date = time();  // Unix date from local time
	if ($date < $newMoons[1]) {
		   $sunMoonInfo['error'] .= "Date must be after " .date("r",$newMoons[1]);
		   return ($sunMoonInfo);
	}
	if ($date > $newMoons[count($newMoons)-1]) {
		   $sunMoonInfo['error'] .= "Date must be before ".date("r",$newMoons[count($newMoons)-1]);
		   return ($sunMoonInfo);
	}
	
	foreach ($newMoons as $mi=>$newMoon) { // find next New Moon from given date
	if ($newMoon>$date) {break;}
	}
	// Get Moon dates
	$NM = $newMoons [$mi-1]; // previous new moon
	$Q1 = $Q1Moons  [$mi-1]; // 1st Q end
	$Q2 = $fullMoons[$mi-1]; // 2nd Q end - Full moon
	$Q3 = $Q3Moons  [$mi-1]; // 3rd Q end
	$Q4 = $newMoons [$mi  ]; // 4th Q end - next new moon
	
	// Divide each phase into 7 periods (4 phases x 7 = 28 periods)
	$Q1p = round(($Q1-$NM)/7);
	$Q2p = round(($Q2-$Q1)/7);
	$Q3p = round(($Q3-$Q2)/7);
	$Q4p = round(($Q4-$Q3)/7);
	
	// Determine start and end times for major phases (lasting 1 period of 28)
	$NMe = $NM+($Q1p/2);                         //  0% .... - New moon
	$Q1s = $Q1-($Q1p/2);  $Q1e = $Q1+($Q2p/2);   // 50% 1stQ - First Quarter
	$Q2s = $Q2-($Q2p/2);  $Q2e = $Q2+($Q3p/2);   //100% 2ndQ - Full moon
	$Q3s = $Q3-($Q3p/2);  $Q3e = $Q3+($Q4p/2);   // 50% 3rdQ - Last Quarter
	$NMs = $Q4-($Q4p/2);                         //  0% 4thQ - New Moon
	
	// Determine age of moon in days since last new moon
	$age = ($date - $newMoons[$mi-1])/86400; // age in days since last new moon
	$dd  = intval($age);
	$hh  = intval(($age-$dd)*24);
	$mm  = intval(((($age-$dd)*24)-$hh)*60);
	$sunMoonInfo['ageDD']	= $dd;
	$sunMoonInfo['ageHH']	= $hh;   
	$sunMoonInfo['ageMM']	= $mm;   
	
	// Illumination
	switch (true) { // Determine moon age in degrees (0 to 360)
	case ($date<=$Q1): $ma = ($date - $NM) * (90 / ($Q1 - $NM))+  0; break; // NM to Q1
	case ($date<=$Q2): $ma = ($date - $Q1) * (90 / ($Q2 - $Q1))+ 90; break; // Q1 to FM
	case ($date<=$Q3): $ma = ($date - $Q2) * (90 / ($Q3 - $Q2))+180; break; // FM to Q3
	case ($date<=$Q4): $ma = ($date - $Q3) * (90 / ($Q4 - $Q3))+270; break; // Q3 to NM
	}
	$sunMoonInfo['ill'] = abs(round(100*(1+cos($ma*(M_PI/180)))/2)-100);
	
	// Deterime picture number (0-27) and moon phase
	switch (true) {
	case ($date<=$NMe): $pic =  0;                        $ph = 'New Moon';          break;
	case ($date< $Q1s): $pic =  1  +(($date-$NMe)/$Q1p);  $ph = 'Waxing Crescent';   break; // Waxing Crescent
	case ($date<=$Q1e): $pic =  7;                        $ph = 'First Quarter';     break;
	case ($date< $Q2s): $pic =  7.5+(($date-$Q1e)/$Q2p);  $ph = 'Waxing Gibbous';    break;
	case ($date<=$Q2e): $pic = 14;                        $ph = 'Full Moon';         break;
	case ($date< $Q3s): $pic = 14.5+(($date-$Q2e)/$Q3p);  $ph = 'Waning Gibbous';    break;
	case ($date<=$Q3e): $pic = 21;                        $ph = 'Last Quarter';      break;
	case ($date< $NMs): $pic = 21.5+(($date-$Q3e)/$Q4p);  $ph = 'Waning Crescent';   break; // Waning Crecent
	default           : $pic =  0;                        $ph = 'New Moon';
	}
	$sunMoonInfo['pic']   = round($pic);
	$sunMoonInfo['phase'] = $ph;
	$sunMoonInfo['NM']    = $NM;
	$sunMoonInfo['Q1']    = $Q1;
	$sunMoonInfo['FM']   = $Q2;
	$sunMoonInfo['Q3']    = $Q3;
	$sunMoonInfo['Q4']    = $Q4;
	$sunMoonInfo['FM2']   = $fullMoons[$mi];
	
	return ($sunMoonInfo);
}  // eof getMoonInfo
# -----------------------------------------------------------------------------
# SEASON FUNTIONS  return season dates based on USNO dates for Spring, Summer, Fall, Winter                                                             .
# -----------------------------------------------------------------------------
function getSeasonInfo ($YY=0) { // feed it the year
$seasonList = array( // seasons from USNO in WD date format
// year => 'Spring|Summer|Autumn|Winter'
'2015' => '22:45 GMT 20 March 2015|16:38 GMT 21 June 2015|08:21 GMT 23 September 2015|04:48 GMT 22 December 2015|',
'2016' => '04:30 GMT 20 March 2016|22:34 GMT 20 June 2016|14:21 GMT 22 September 2016|10:44 GMT 21 December 2016|',
'2017' => '10:29 GMT 20 March 2017|04:24 GMT 21 June 2017|20:02 GMT 22 September 2017|16:28 GMT 21 December 2017|',
'2018' => '16:15 GMT 20 March 2018|10:07 GMT 21 June 2018|01:54 GMT 23 September 2018|22:23 GMT 21 December 2018|',
'2019' => '21:58 GMT 20 March 2019|15:54 GMT 21 June 2019|07:50 GMT 23 September 2019|04:19 GMT 22 December 2019|',
'2020' => '03:50 GMT 20 March 2020|21:44 GMT 20 June 2020|13:31 GMT 22 September 2020|10:02 GMT 21 December 2020|',
); // end of seasonList
global $sunMoonInfo;
if($YY<2015) {$YY = idate('Y');} // use current year 

if(!isset($seasonList[$YY])) {
	   $sunMoonInfo['error'] .= "Year $YY not in list";
	   return $sunMoonInfo;
}
list($spring,$summer,$fall,$winter) = explode('|',$seasonList[$YY]);
	
//$sunMoonInfo['spring'] = date_timestamp_get(DateTime::createFromFormat('G:i T d M Y',$spring));
//$sunMoonInfo['summer'] = date_timestamp_get(DateTime::createFromFormat('G:i T d M Y',$summer));;
//$sunMoonInfo['fall']   = date_timestamp_get(DateTime::createFromFormat('G:i T d M Y',$fall));;
//$sunMoonInfo['winter'] = date_timestamp_get(DateTime::createFromFormat('G:i T d M Y',$winter));;

$sunMoonInfo['spring'] = strtotime ($spring);
$sunMoonInfo['summer'] = strtotime ($summer);
$sunMoonInfo['fall']   = strtotime ($fall);
$sunMoonInfo['winter'] = strtotime ($winter);
return ($sunMoonInfo);

}  // eof getSeasonInfo 
# -----------------------------------------------------------------------------
#  usno_makeRequest   get contents from one URL and return as string 
# -----------------------------------------------------------------------------
function usno_makeRequest($url,$PostParms='') {
	global $SITE;
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	$rawData  = curl_exec ($ch);
	echo '<!-- '.$url. '-->'.PHP_EOL;
	echo '<!-- '.$rawData. '-->'.PHP_EOL;
	curl_close ($ch);
	return($rawData);
}    // end usno_makeRequest()
}  // eo function exist
#---------------------------------------------------------------------------
# Based on astronomy pages of Ken, combines usno,adapted/standardized arrays,dates, etc
$refetchSeconds = 3600;            	// refetch every nnnn seconds 3600=1 hour
$ourTZ 	        = $SITE['tz'];
$myLat 	        = $SITE['latitude'];
$myLong 	= $SITE['longitude'];
$myCity 	= $SITE['organ'];
$imagesDir	= $SITE['imgAjaxDir']	;
$timeFormat	= $SITE['timeFormat'];
$timeOnlyFormat = $SITE['timeOnlyFormat'];
$dateOnlyFormat = $SITE['dateOnlyFormat'];
$cacheFileDir	= $SITE['cacheDir']; 
#---------------------------------------------------------------------------
$cacheName 	= 'sunMoondata.arr';  	// used to store the file so we don't have to fetch from USNO website
$cacheFileDir	= $SITE['cacheDir'];
$cacheName 	= $cacheFileDir.$cacheName;
$today		= date ('j',time() );
#
# do we have to skip the cache
if (isset ($_REQUEST['force']) && $_REQUEST['force'] == 'usno') {$use_cache = false;} else {$use_cache = true;}
#
#$use_cache = false;
#
# either load the cached array with data or fetch a new html page
#
if ($use_cache && file_exists($cacheName) &&  ( date ('j',filemtime($cacheName) ) == $today) ){
	echo '<!-- saved data last time ('.$cacheName.') loaded from cache '.date ($SITE['timeFormat'],filemtime($cacheName) ).' -->'.PHP_EOL;
	$sunMoonInfo =  unserialize(file_get_contents($cacheName));
} else {
	echo "<!-- loading $cacheName from USNO -->".PHP_EOL;
	$sunMoonInfo            = array();
	$sunMoonInfo['date']	= time();;
	$sunMoonInfo['error']   = '';
	getUSNOsunmoon();
	wsGetMoonInfo();
	getSeasonInfo();
	if (!file_exists($cacheFileDir)){
		mkdir($cacheFileDir, 0777);   // attempt to make the cache dir
	} 
	if (!file_put_contents($cacheName, serialize($sunMoonInfo))){   
		echo PHP_EOL."<!-- Could not save data to cache $cacheName. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
	} else {echo "<!-- data ($cacheName) saved to cache  -->".PHP_EOL;}
}
if ($wsDebug) {echo '<!-- <pre>'.PHP_EOL; print_r ($sunMoonInfo); echo '</pre> -->'.PHP_EOL;}
# ----------------------------------------------------------------------------------
# Do we print the page or supply only the missing weather variables for some weather programs
# ----------------------------------------------------------------------------------
if (isset ($skipMoonPage) && ($skipMoonPage == true) ){
	$ws['moonrise']		= wsTimeOnlyToText($sunMoonInfo['moonrise']);
	$ws['moonset']		= wsTimeOnlyToText($sunMoonInfo['moonset']);
	$ws['lunarPhasePerc']	= $sunMoonInfo['ill'];
	$ws['lunarAge']		= $sunMoonInfo['pic'];
	return;
}
$lunarPhasePerc = $sunMoonInfo['ill'];
if ( isset($sunMoonInfo['illumination']) ) {
	$lunarPhasePerc = $sunMoonInfo['illumination'];
}  
$lunarNM	= $sunMoonInfo['NM'];
$lunarNextNM	= $sunMoonInfo['Q4'];
$lunarFQ	= $sunMoonInfo['Q1'];
$lunarLQ	= $sunMoonInfo['Q3'];
$lunarFM	= $sunMoonInfo['FM'];
$lunarPhaseName = $sunMoonInfo['phase'];
if ( isset($sunMoonInfo['moonphase']) )	{
	$lunarPhaseName = $sunMoonInfo['moonphase']; 
}
$lunarAgeDays	= $sunMoonInfo['pic'];
$lunarAgeText	= langtransstr('Moon age').': '.$sunMoonInfo['ageDD'].' '.langtransstr('days').', ' .$sunMoonInfo['ill'].'%'; 
$marchequinox = $sunMoonInfo['spring']; 
$junesolstice = $sunMoonInfo['summer'];
$sepequinox   = $sunMoonInfo['fall']; 
$decsolstice  = $sunMoonInfo['winter'];
if ( isset($sunMoonInfo['sunrise']) )	{
	$sunriseDate = $sunMoonInfo['sunrise']; 
}
if ( isset($sunMoonInfo['sunset']) )   {
	$sunsetDate =  $sunMoonInfo['sunset'];
} 
if ( isset($sunMoonInfo['moonrise']) ) {
	$moonriseDate= $sunMoonInfo['moonrise'];
}  
if ( isset($sunMoonInfo['moonset']) ) {
	$moonsetDate = $sunMoonInfo['moonset'];
}  
if(isset($sunMoonInfo['hoursofpossibledaylight'])) {
	$hoursofpossibledaylight = $sunMoonInfo['hoursofpossibledaylight'];
} 
# ----------------------------------------------------------------------------------
# Now we generate the page
# <div style="background: black; border-radius: 5px; color: white;">
# ----------------------------------------------------------------------------------
?>
<div class="blockDiv" style="background: black; color: white;">
<h3 class="blockHead"><?php langtrans('Astronomy'); ?></h3> 
<br />
<table class="genericTable" style="color: white;">
<tr><td style="width: 33%;">&nbsp;</td><td style="width: 33%;">&nbsp;</td><td style="width: 33%;">&nbsp;</td></tr>
<tr>
<td><h3><?php langtrans('Sun&nbsp;'); ?></h3>&nbsp;</td>
<td><h3><?php langtrans('Our home'); ?></h3>&nbsp;</td>
<td><h3><?php langtrans('Moon'); ?></h3>&nbsp;</td>
</tr>
<tr>              
<td><img src="http://sohowww.nascom.nasa.gov/data/realtime/eit_304/512/latest.jpg" height="100" width="100" alt=" "  style = "border: 0;"
	onclick="javascript:bigwin=window.open('http://sdo.gsfc.nasa.gov/data/aiahmi/latest.php?t=aia_0304&amp;r=512',
 	'klikwindow', 'width=512, height=512, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no, status=no, menubar=yes'); 
 	bigwin.focus()" style = "border: 0;"/>
</td>
<?php
if ($SITE['latitude'] 	< 0) {$lat = (string)-1 * $SITE['latitude']; $ns='SOUTH';} else {$lat = (string) $SITE['latitude']; $ns='NORTH';}
if ($SITE['longitude']	< 0) {$lon = (string)-1 * $SITE['longitude']; $ew='WEST';} else {$lon = (string) $SITE['longitude']; $ew='EAST';}
$strLatLon = 'lat='.$lat.'&amp;ns='.$ns.'&amp;lon='.$lon.'&amp;ew='.$ew;	
echo '<!-- $SITE["latitude"] = '.$SITE['latitude'].'| $SITE["longitude"] = '.$SITE['longitude'].' ==> '.$strLatLon.' -->'.PHP_EOL;
?>
<td rowspan="2">
    <a href="http://www.fourmilab.ch/cgi-bin/Earth?imgsize=640&amp;opt=-l&amp;<?php echo $strLatLon; ?>&amp;alt=10000&amp;img=learth.evif" target="_blank">
        <img src="http://www.fourmilab.ch/cgi-bin/Earth?img=learth.evif&amp;imgsize=160&amp;dynimg=y&amp;opt=-l&amp;<?php echo $strLatLon; ?>&amp;alt=35785&amp;tle=&amp;date=0&amp;utc=&amp;jd="
    	    width="160" height="160" style="border: 0;"
	    alt="<?php langtrans('Our home on our planet as seen from space') ?>"
	    title="<?php langtrans('Our home on our planet as seen from space') ?>"
        />
    </a>
</td>
<td><a href="http://www.fourmilab.ch/cgi-bin/Earth?img=MoonTopo.evif&amp;imgsize=320&amp;opt=-l&amp;<?php echo $strLatLon; ?>&amp;alt=35785&amp;tle=&amp;date=0&amp;utc=&amp;jd=" target="_blank">
	<img src="http://www.fourmilab.ch/cgi-bin/Earth?img=MoonTopo.evif&amp;imgsize=100&amp;dynimg=y&amp;opt=-l&amp;<?php echo $strLatLon; ?>&amp;alt=35785&amp;tle=&amp;date=0&amp;utc=&amp;jd=" width="100" height="100" alt="<?php echo $lunarAgeText; ?>" title="<?php echo $lunarAgeText; ?>"/>
    </a>
</td>
</tr>
<tr>
<td>
<?php 
	echo 	langtransstr('sunrise').': '.wsTimeOnlyToText($sunriseDate).'<br />'.
		langtransstr('Sunset').': '.wsTimeOnlyToText($sunsetDate).'<br />'.
		langtransstr('Daylight').': '.$hoursofpossibledaylight;
?>
</td>
<td>
<?php 	$rise 	= langtransstr('Moonrise').': '.wsTimeOnlyToText($moonriseDate);
	$now	= wsDateOnlyToText(time());
	$extra 	= wsDateOnlyToText($moonriseDate);
	if ($now <> $extra)  {
		$rise  .= '<small> (' . $extra . ')</small>'; 
	}	  	
	$set	= langtransstr('Moonset').': '. wsTimeOnlyToText($moonsetDate);
	$extra = wsDateOnlyToText($moonsetDate);
	if ($now <> $extra)  {
		$set  .= '<small> (' . $extra . ')</small>'; 
	}	  	
	if ($moonsetDate > $moonriseDate) {
		echo $rise.'<br />'.$set.'<br />';
	}
	else {	echo $set.'<br />'.$rise.'<br />';
	}
	echo 	langtransstr($lunarPhaseName).'<br/>'.
	  	$lunarPhasePerc.' % '. langtransstr('Illuminated'); 
?>
</td>
</tr>
</table>
<br />
<h3 class="blockHead"><small><?php langtrans('When you click on the image of the sun, earth or moon, you go to new pages with interesting information'); ?></small></h3>
</div>
<br />
<div class="blockDiv" style="background: black; color: white;">
<h3 class="blockHead"><?php langtrans('Lunar cycle'); ?></h3>
<br />
<table class="genericTable" style="color: white;">
<?php
$txtFQ=langtransstr('First Quarter Moon');
$txtFM=langtransstr('Full Moon');
$txtLQ=langtransstr('Last Quarter Moon');
$txtNM=langtransstr('New Moon');
?>
<tr>
<td style="width:25%;"><?php echo $txtFQ; ?></td>
<td style="width:25%;"><?php echo $txtFM; ?></td>
<td style="width:25%;"><?php echo $txtLQ; ?></td>
<td style="width:25%;"><?php echo $txtNM; ?></td>
</tr>
<tr>
<td><img src="<?php echo $imagesDir; ?>moon-firstquar.gif" width="100" height="100" alt="<?php echo $txtFQ; ?>" title="<?php echo $txtFQ; ?>"/></td>
<td><img src="<?php echo $imagesDir; ?>moon-fullmoon.gif" width="100" height="100" alt="<?php echo $txtFM; ?>" title="<?php echo $txtFM; ?>"/></td>
<td><img src="<?php echo $imagesDir; ?>moon-lastquar.gif" width="100" height="100" alt="<?php echo $txtLQ; ?>" title="<?php echo $txtLQ; ?>"/></td>
<td><img src="<?php echo $imagesDir; ?>moon-newmoon.gif" width="100" height="100"  alt="<?php echo $txtNM; ?>" title="<?php echo $txtNM; ?>"/></td>
</tr>
<tr>
<td><?php echo wsDateToText($lunarFQ); ?><br/><small>(utc) <?php echo wsDateToText(get_utcdate($lunarFQ)); ?></small></td>
<td><?php echo wsDateToText($lunarFM); ?><br/><small>(utc) <?php echo wsDateToText(get_utcdate($lunarFM)); ?></small></td>
<td><?php echo wsDateToText($lunarLQ); ?><br/><small>(utc) <?php echo wsDateToText(get_utcdate($lunarLQ)); ?></small></td>
<td><?php echo wsDateToText($lunarNextNM); ?><br/><small>(utc) <?php echo wsDateToText(get_utcdate($lunarNextNM)); ?></small></td>
</tr>
</table>
</div>
<br />
<div  class="blockDiv" style="background: black; color: white;">
<h3 class="blockHead"><?php langtrans('Seasons'); ?></h3>
<br />
<table class="genericTable" style="color: white;">
<?php
$txtSpring	=langtransstr('Start of Spring');
$txtSummer	=langtransstr('Start of Summer');
$txtFall	=langtransstr('Start of Fall');
$txtWinter	=langtransstr('Start of Winter');
?>
<tr>
<td><?php langtrans('Vernal Equinox'); ?><br/><small><?php echo $txtSpring; ?></small></td>
<td><?php langtrans('Summer Solstice'); ?><br/><small><?php echo $txtSummer; ?></small></td>
<td><?php langtrans('Autumn Equinox'); ?> <br/><small><?php echo $txtFall; ?></small></td>
<td><?php langtrans('Winter Solstice'); ?><br/><small><?php echo $txtWinter; ?></small></td>
</tr>
<tr>

<td><img src="<?php print $imagesDir; ?>earth-spring.jpg" width="125" height="125" alt="<?php echo $txtSpring; ?>" title="<?php echo $txtSpring; ?>"/>
</td>
<td>
<?php 
if($SITE['latitude'] >=0) { // Use Northern Summer 
	echo '<img src="'.$imagesDir.'earth-summer.jpg" width="125" height="125"  alt="'.$txtSummer.'" title="'.$txtSummer.'"/>'.PHP_EOL;
} 
else { // use Southern Summer image 
	echo '<img src="'.$imagesDir.'earth-winter.jpg" width="125" height="125"   alt="'.$txtSummer.'" title="'.$txtSummer.'"/>'.PHP_EOL;
}
?>
</td>
<td><img src="<?php print $imagesDir; ?>earth-fall.jpg" width="125" height="125"  alt="<?php echo $txtFall; ?>" title="<?php echo $txtFall; ?>"/></td>
<td>
<?php 
if($SITE['latitude'] >=0) { // Use Northern Winter 
	echo '<img src="'.$imagesDir,'earth-winter.jpg" width="125" height="125" alt="'.$txtWinter,'" title="'.$txtWinter.'"/>'.PHP_EOL;
} 
else { // use Southern Winter image 
	echo '<img src="'.$imagesDir.'earth-summer.jpg" width="125" height="125"  alt="'.$txtWinter,'" title="'.$txtWinter.'"/>'.PHP_EOL;
} 
?>
</td>
</tr>
<tr>
<?php 
if ($SITE['latitude'] >= 0) { // Use Northern Hemisphere dates with images
echo 
'<td>'.wsDateToText($marchequinox).'<br/><small>(utc)'.wsDateToText(get_utcdate($marchequinox)).'</small></td>
<td>'.wsDateToText($junesolstice).'<br/><small>(utc)'.wsDateToText(get_utcdate($junesolstice)).'</small></td>
<td>'.wsDateToText($sepequinox).  '<br/><small>(utc)'.wsDateToText(get_utcdate($sepequinox)).  '</small></td>
<td>'.wsDateToText($decsolstice). '<br/><small>(utc)'.wsDateToText(get_utcdate($decsolstice)). '</small></td>'.PHP_EOL;
} 
else { // Use Southern Hemisphere dates with images
'<td>'.wsDateToText($sepequinox).  '<br/><small>(utc)'.wsDateToText(get_utcdate($sepequinox)).  '</small></td>
<td>'.wsDateToText($decsolstice). '<br/><small>(utc)'.wsDateToText(get_utcdate($decsolstice)). '</small></td>
<td>'.wsDateToText($marchequinox).'<br/><small>(utc)'.wsDateToText(get_utcdate($marchequinox)).'</small></td>
<td>'.wsDateToText($junesolstice).'<br/><small>(utc)'.wsDateToText(get_utcdate($junesolstice)).'</small></td>'.PHP_EOL;
}
?>
</tr>
</table>
</div>
<br />
<div  class="blockDiv" style="background: black; color: white;">
<h3 class="blockHead"><?php langtrans('Moon phases'); ?></h3>
<br />
<table  class="genericTable moon" style="border-width: 1px; border-style: solid; border-color: white;  width: 80%; margin: 0 auto; color: white; ">
<?php

$now = getdate(time());
$time = mktime(0,0,0, $now['mon'], 1, $now['year']);
$date = getdate($time);
$dayTotal = cal_days_in_month(0, $date['mon'], $date['year']);
wsGetMoonInfo ($time);
$picNbr = $sunMoonInfo['pic'];
print '
<tr style ="height: 40px; border-bottom:solid 1px white;"><td colspan="7"><strong>' . langtransstr($date['month']) . '</strong></td></tr>

<tr style ="height: 30px; border-bottom:solid 1px white;">
<td>'.langtransstr('Sunday').'</td>
<td>'.langtransstr('Monday').'</td>
<td>'.langtransstr('Tuesday').'</td>
<td>'.langtransstr('Wednesday').'</td>
<td>'.langtransstr('Thursday').'</td>
<td>'.langtransstr('Friday').'</td>
<td>'.langtransstr('Saturday').'</td>
</tr>';

for ($i = 0; $i < 6; $i++) {
    	$string1 = $string2 = '';
        for ($j = 1; $j <= 7; $j++) {
            	$dayNum = $j + $i*7 - $date['wday'];
            	$string1 .= '<td  style="border-width: 1px; text-align: center; ">';
            	$string2 .= '<td  style="border-top-width: 0px;">';
            	if ($dayNum > 0 && $dayNum <= $dayTotal) {
            		if ($dayNum == $now['mday']) {
            			$string1.='<span style="font-weight: bold; background: white; color: black; ">&nbsp;&nbsp;&nbsp;&nbsp;'.$dayNum.'&nbsp;&nbsp;&nbsp;&nbsp;</span>';
            		}
            		else {	$string1.= $dayNum;
            		 }
 #               	$string1.= ($dayNum == $now['mday']) ? ' background: white; color: black;">' : '">';
#              	$string1.= ($dayNum == date("d")) ? '<strong>'.$dayNum.'</strong>' : $dayNum;
                	$string2.= '<img src="ajaxImages/moon'.$picNbr.'.gif" alt=""/>'; 
			$picNbr = $picNbr+1;
			if ($picNbr > 27) {$picNbr = 0;}               
            	} 
            	else {	$string1.='&nbsp;';
            		$string2.='&nbsp;';           	
            	}
            	$string1.='</td>'.PHP_EOL;
            	$string2.='</td>'.PHP_EOL;           
        }
        echo '<tr style ="height: 30px">'.PHP_EOL.$string1.'</tr>'.PHP_EOL;
        echo '<tr style ="height: 80px; border-bottom:solid 1px white;">'.PHP_EOL.$string2.'</tr>'.PHP_EOL;
        
  	$time = mktime(0,0,0, $now['mon'],$dayNum+1, $now['year']); 
	echo '<!--  '.$now['mon'].' - '.$dayNum.' - '.$now['year'].' -->';
	wsGetMoonInfo ($time);
	$picNbr = $sunMoonInfo['pic'];  
	echo "<!-- = $picNbr -->".PHP_EOL;
        if ($dayNum >= $dayTotal) {  
        	break; 
        }
}
?>
</table>
<br />
</div>
<br />
<?php include 'wsDashEarth.php';
?>
<div class="blockDiv" style="background: black; color: white;">
<h3 class="blockHead">
	<small><?php langtrans('Original script by'); ?>&nbsp;
	<a href="http://saratoga-weather.org/" target="_blank">Saratoga-weather.org</a>
	<?php langtrans('Adapted and extended for the template by'); ?>&nbsp;
	<a href="http://leuven-template.eu/" target="_blank">Weerstation Leuven</a>
	</small>
</h3>
</div>
