<?php
# FORECAST RELATED
define('FRCWARN','Click to show the whole warning. Warnings by');
define('EWNACC','The accuracy-check compares measured temperature to the forecasted temperature for ca 30 places north of 52&deg;N up to Nordkapp in North Norway. One criteria for the choosed locations is need of a realible weatherstation (in most cases met office stations). Error-values are in absolute degrees, what means the values are allways positive. The check shows data for last 30 days.');
define('LIGHTNINGMAP','Lightningmap');
define('DAYLENGTH','Daylength');
define('MIDNIGHTSUN','Midnight Sun');
define('POLARNIGHT','Polar Night');
define('QUICKLINKS','Direct links');
define('CLOSESTWXSTATION','Closest weatherstation');
define('POP','PoP');
define('RANGE','Range');
define('DAYFRC2','day weatherforecast');
define('MORE','More &raquo;');
define('DAILYMAP','Daily maps');
define('FDAYS','Day');
define('DTOMORROW','Day 3');
define('FMAP','Map');
define('MAXI','Highest');
define('MINI','Lowest');
define('SEVRISK','Thunderstorm probability');
define('LEV3','Extremely severe');
define('LEV2','Severe weather');
define('TS','Lightning probability');
define('SEVERERISK','SEVERE WEATHER');
define('TSRISK','Risk for thundershowers');
define('SUPERCELLRISK','Risk for supercells');
define('TORNADORISK','Risk for tornados');
define('HAILRISK','Risk for hail');

define('CLICKINFO','Click on map to get forecast from anywhere!');
define('FSLEET','Sleet');
define('YOURLOCATION','Your current location');
define('FRCACCURACY','Accuracy of the forecast');
define('FRCACCINFO','We check the accuracy for some of the used models for a few places by checking the 12h point in the forecast.');
define('DOWNLOADED_FRC','Loaded forecasts');
define('TOTDOWNLOADED_FRC','Total loaded forecasts');
define('PLACE','Place');
define('FRCS','Forecasts');
define('LATI','Latitude');
define('LONGI','Longitude');

define('TOMORROW','Tomorrow');
define('SUNANDMOON','Sun and moon');
define('UVFORECAST','4 day UV-forecast');
define('LOW','Low');
define('MED','Medium');
define('HIG','High');
define('LOCATIONINFO','Locationinfo');
define('ELEVATION','Elevation');
define('POPULATION','Population');
define('FEELS','Feels');
define('GUST','Gust');

define('EWNFORECAST','EWN-Forecast');
define('FRCSEARCH','Search forecast');
define('FRCSAVE','Save location');
define('FORECASTINFO','The EWN-Forecast is EWN\'s own in-house generated forecast. Its the only forecast whats generated on demand, when you query it! This means you get allways the most recent data available.<br/>The EWN-Forecast is available for ca 850000 locations trought whole Europe.');
define('FRCINFOB','The EWN Forecast use multiple free numerical models as base for the forecast. EWN are not liable for the quality and accuracy of the forecast.<br/>In addition, we do run an own run of WRFDA EMS model at 12 km resolution whats included in the data. The maps are also generated from the WRFDA EMS model.<br/><br/>
In the forecasts are content and data used from:<br/>
<a href="http://nomads.ncep.noaa.gov/" target="_blank">NCEP</a> (GFS, GEFS &amp; UV), <a href="http://weather.gc.ca" target="_blank">The Canadian Meteorological Center</a> (GEM-GDPS), <a href="http://www.met.no" target="_blank">Norwegian Meteorological Institute</a> (MET Norway), <a href="http://www.smhi.se" target="_blank">Swedish Meteorological and Hydrological Institute</a> (SMHI), <a href="http://www.fmi.fi" target="_blank">Finnish meteorological institute</a> (FMI), <a href="http://www.geonames.org" target="_blank">Geonames</a>, <a href="http://www.meteoalarm.eu" target="_blank">MeteoAlarm</a>, <span id="moresources"></span>WXSIM - near private weatherstations with the WXSIM-software.
');

define('SAVEFAVO','Default location');
define('SAVEQUI','Add to quicksearch');
define('SEARCHCOUNTRY','Country');
define('PLACESEARCH','Search');
define('QUICKSEARCH','Quicksearch');
define('OWNPLACES','Own places');

define('FORECASTFOR','Forecast for');
define('ISSUED','Issued');

define('SHORTFRC','Overview');
define('HOURFRC','Hour-by-hour');
define('DAYFRC','day forecast');
define('NERDFRC','Extras');
define('COMPFRC','Compare');
define('MAPFRC','Maps');

define('YRDESC','Our "in-house"-forecast in cooperation with European Weathernetwork.<br/>The forecast are done with a software called STRC EMS and its resolution is 9 km. Its updated 4 times a day.<br/>You can browse about 200000 forecasts in Europe.');
define('FGUST','Max windgust');
define('FRATE','Rainrate');
define('BASEMAPS','Basemap');
define('SATELLITE','Satellite');
define('TTINFO','TS Probality: 45-50 possible, <br/>50-55 more likely, 55-60 severe likely');
define('SRHINFO','150-299 supercells possible, <br/> 300-500 supercells likely and tornados possible');
define('DBZINFO','20 light rain, <br/> 30 moderate rain, 50 heavy rain, hail');
define('KIINFO','ca 2 x K Index = Thunder probability');
define('FTEMP','Temperature');
define('FCHILL','Windchill');
define('FDEW','Dewpoint');
define('FWIND','Wind');
define('FPREC','Precipitation');
define('FCLOUD','Clouds');
define('FBARO','Pressure');
define('FSNOW','Snowfall');
define('FHAIL','Hail');
define('FFRZ','Freezing');
define('FLIG','Thunder');
define('FTHUN','Thunder probability');
define('FCONV','Convective precip.');
define('FINSTA','Pot. Instability');
define('FVISIB','Visibility');
define('FFORE','Forecasts');
define('FAVG','Average');
define('FUTIMES','Updatetimes');
define('FRESO','Resolution');
define('FLENGTH','Length');
define('FSTEPS','Steps');
define('FRADAR','radar');
define('FSAT','satellite');
define('SIGHAIL','Sig. Hailrisk');
define('SIGTOR','Sig. Tornadorisk');
define('SIGSUP','Sig. Supercellrisk');
define('MCOMPRADAR','Max comp. radar');
define('SIGHAIL2','Significant Hailrisk');
define('SIGTOR2','Significant Tornadorisk');
define('SIGSUP2','Significant Supercellrisk');
define('MCOMPRADAR2','Max composite radar');
define('FRCCINFO','
<b>Lightning probability maps</b> - Green and yellow colors indicates lightning probability within 40 km of a point. Orange, red and violet are similar levels seen on estofex.org including also probablity for hails and supercells.<br/><br/>
<b>dBZ</b> - dBZ stands for decibels relative to Z. It is a meteorological measure of equivalent reflectivity (Z) of a radar signal reflected off a remote object
<br/><br/>
<b>Cape</b> - Convective available potential energy (CAPE), sometimes, simply, available potential energy (APE), is the amount of energy a parcel of air would have if lifted a certain distance vertically through the atmosphere.
<br/><br/>
<b>K Index</b> - K-Index is a measure of the thunderstorm potential based on vertical temperature lapse rate, moisture content of the lower atmosphere, and the vertical extent of the moist layer.
<br/><br/>
<b>TT Index</b> - Convective index used for forecasting severe weather.
<br/><br/>
<b>WRF</b> - The Weather Research and Forecasting (WRF) is a next-generation mesocale numerical weather prediction system designed to serve both operational forecasting and atmospheric research needs. WRF is a cooperation between National Center for Atmospheric Research (NCAR), National Centers for Environmental Prediction (NCEP), Forecast Systems Laboratory (FSL), Air Force Weather Agency (AFWA), Naval Research Laboratory, University of Oklahoma, and the Federal Aviation Administration (FAA). This software are runned on our server.
<br/><br/>
<b>WRFDA</b> - A more advanced version of WRF<br/><br/>
<b>Arome</b> - The 2.5 km Arome model. Provided by Norwegian Meteorological Institute.
<br/><br/>
<b>MET Norway</b> - The proff-default 4km meteorological data is a best-guess selection of meteorological models run at met.no and joined/interpolated into a common dataset. Provided by Norwegian Meteorological Institute.
<br/><br/>
<b>SMHI</b> - The Hirlam model provided by Swedish meteorological institute.
<br/><br/>
<b>FMI</b> - The Hirlam model provided by Finnish meteorological institute. Covering only Finland.
<br/><br/>
<b>GFS</b> - The Global Forecast System (GFS) is a global numerical weather prediction computer model run by NOAA.
<br/><br/>
<b>GEM-GDPS</b> - The GEM-GDPS is a global numerical weather prediction computer model run by the  Canadian Meteorological Centre.
<br/><br/>
<span style="color:#888"><b>Credits/Info:</b> Thanks to Stefan Gofferje at <a href="http://www.saakeskus.fi" target="_blank">s&auml;&auml;keskus.fi</a> for some of the formulas for GrADS. Infos: Wikipedia.</span>');
define('SHOWADV','Show advanced');
define('FPRECTYP','Precipitation type');
define('FSNOWD','Snowdepth');
define('FRAIN','Rain');
define('FTMPSFC','Surface Temperature');
define('FACCUM','Accumulative Precipitation');
define('FHUM','Humidity');
define('FFEELS','Feels');
define('FTODAY','Today');
define('FTOMOZ','Tomorrow');

$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$daynames = '"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat","Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"';

################################################

define('CURRCOND','��������� ��������');
define('METEXT','���. ���������');
define('ROADEXT','������� ����');
define('SOUTH','�����');
define('WEST','������');
define('NORTH','������ ');
define('EAST','���������');
define('MIDDLE','��������');
define('YRSTATIONS','������� met.no');
define('SYNOPSTATIONS','������� Synop');
define('METINFO','���. ��������� - ������� ������� �������������� ���������.');
define('ROADINFO','������� ��������� = ������� ������.');
define('ROADWX','������ ����');
define('FITRAFNAME','���������� ����������� ����');
define('SVTRAFNAME','���������� ��������� ����');
define('PWSWX','���');
define('PWSSTATIONS','���-�������');
define('STSTATS','����������');
define('STATION','�������');
define('CHOOSECOUNTRY','������ �����');
define('CHOOSESTATION','������� �������');
define('SUBMIT','�������');
define('SINGLESTATION','���� �������');
define('COMPARE','��������');
define('EXTRAMAP','������ �������');
define('SNOWCOVER','�����������');
define('ICECOVER','����������');
define('SEATEMP','����������� ��������');
define('SNOWDEPTH','���� �������');
define('TERRAIN','������');
define('STRIKES','���������');
define('MINUTES','�����');
define('GUSTINGTO','�� ����� ��');
define('SAVESETTINGS','���������� ��� ������');
define('APPARENT','������� ��');
define('STATIONDATA','�������� �������');
define('WARNINGSIN','��������������� ��');
define('CLICKONWARN','������� ���� ������� ��� �������� ��������������.');
define('CURREXTREMES','��������� �������');

define('SETTINGS','���������');
define('STATS','����������');
define('STATSFOR','���������� ��� ��������� ������ ������');
define('STSPERCOUNTRY','�������/����');
define('NORTHERNMOST','�����������/���������� ���');
define('NEWEST','�������� �������');
define('MAPLOADS','������');
define('MISC','�������');

define('LIGHT','��������');
define('DARK','���������');
define('BASEMAP','������� ������');
define('TYPE','�����');
define('DATA','��������');
define('WINRUN','��� ������W');
define('RRATE','�����������');

define('ACTIVEALARMS','������� ���������������');
define('ACTIVETRACKERS','���������� ��� ��������� ����������');
define('PWS','���������');
define('SYNOP','Synop/Metar');
define('NAUTRAL','������');
define('NIGHTVIEW','���������Nightview');
define('BLUEVIEW','Blueview');
define('BWTXT','����� & �����');
define('SATELLITE','����������');
define('SEAWARN','��������������� ��������');
define('MALARMWARN','��������������� �����');
define('SEAICE','�������� ��������');
define('ICE','�����');

define('MAP','������');
define('WXSIMFRCST','�������� 5 ������ WXSIM');
define('WXSIMINFO','���������� ������� �� ��������� WXSIM');
define('FRCST','��������48 ����');
define('HIDE','��������');
define('TOPLISTS','���������');
define('TABLES','�������');
define('INFO','�����������');
define('BALTIC','�������');
define('MOREWEATHER1','������������ ������');
define('IHAVENOTWXSTATION','�� ������� ������');
define('YOURINFO','�� ����������� ���');
define('PICKCOORDS','�������� ������������� ��� �����');
define('PASSW','�������');
define('DEFLOC','������ �������');
define('SAVE','����������');
define('STATIONLESS','�����������');
define('SAVED','������������');
define('EXTREMECOLDBOX','������� ���� ���� ����� ���������� ��������');
define('EXTREMEHOTBOX','������� ���� ����� ����� ���������� ��������');

// Main
define('JOINUS', '����������');
define('LEGEND', '�����������');
define('WEATHER', '�����');
define('WEBCAM', 'Webcam');
define('LIGHTNING', '�������');
define('SNOWNOTE', '<b>*��������!</b> ��� ��������� ���� ������� ���� �� �������.');
define('NOCOND', '��� ������� ������� �������� ��������');
define('LOCALTIME', '������ ���');
define('STATIONS', '�������');
define('CLICKFORINFO', '������� ��� ������������ ����������� ��� ������� �� ���!');
define('TODAY', '������');
define('ALLTIME', '��� ���� �����������');
define('FORECAST', '��������');
define('ROAD','����');
define('WX','Wx');
define('POUTA','�������');
define('LPRECIP','������� �����');
define('MPRECIP','������� �����');
define('HPRECIP','������� �����');
define('DRY','�������');
define('WET','�����');
define('ICY','���������');
define('SNOWY','�����������');
define('SLIPPERY','������� ���������');
define('LAYER','�������');
define('REPSNOW','Rep.');
define('ALLCOUT','���� �� �����');

define('COORDS','�������������');
define('NOCAM','� ������� �� �������� ������ have not cam');
define('SYNPRODBY','�������� Synop/Metar ���');
define('ROADPRODBY','Ties��-data produced by');
define('SGRAPHHEAD','���������� 24 ����');
define('MOREGRAPH','����������� ���������');
define('MAPSETTINGS','��������� �����');
define('WARMCOLD','����������/����������');
define('DATACOUNTER','��������');
define('STARTED','������');
define('JOINUS','���������� �� ���');
define('MEMBERAREA','����� �����');
define('WAIT','�������...');
define('GRAPH','���������');
define('DAYS','������');
define('THUNDER','��������');
define('SHOWICON','�������');
define('SHOWSTATION','�������');

// V5
$txtcolors = array('��������','���������','��������');
$txttypes = array('������','�����/�����','����������','������','���������� ����� �����������','���������� ������ �����������','�������� ������','������ �������','�������������','�����');
define('DISTANCE', '��������');
define('AWLEVEL', '������� ����������');
define('VALID','������');
define('NWNDISCL','<br/>�� EWN ��� ����� �������� ��� ��� �������� ��� �����������.');


define('ALT', 'Alt');
define('HOMEPAGE', '����������');
define('HERE', '���');
define('CONDITIONS', '��������');
define('FCST', '��������');
define('NOTHUNDER', '��� ������������ ����������');
define('FEATB', '�������');
define('WINDBOX', '���������� ������ ������������ ����� �������� �������� (> 22 m/s)');
define('SUMMERFROSTBOX', '������� ����������� ����� �������� ��������');
define('WARN', '���������������');

// NEW
define('SYNEXT', '�������-Synop');
define('ROADSTS', '������� ����');
define('TIME', '���');
define('WEBCAMS', 'Webcams');
define('LIGHTNINGS', '���������� ����������');
define('OPTIONS', '��������');
define('COND', '��������');
define('DIR', '���������');
define('RRADAR', '������ ������');
define('STATIONSS', ' �������');
define('CALM', '������');
define('DEWP', '������ ������');
define('CCOVER', '����������');
define('VISIB', '���������');
define('NOOBS', '����� ����������');
define('SHOW', '��������');

define('WCHILL', '������� ������');
define('HEAT', '������� ���������');
define('TRACA', '����������');
define('TRACB', '����������');
define('TRACBOX' , '�� ���������� ��� �������� ������� ���������� ���������� ���� �� ������');
define('BOXPRTB', '');

// NEW
define('NOPE', '���');
define('YES', '���');
define('', '');

// MISC

define('TOPMISC', '������� ��������');
define('NORTHMST', '�����������');
define('SOUTHMST', '����������');
define('WESTMST', '�����������');
define('EASTMST', '��������������');
define('HIGHMST', '���������');
define('LOWMST', '����������');
define('CAMS', 'Webcams');
define('TRACKERS', '���������� ����������');


// BALLOONS
define('TRACKER', '���������� ����������');
define('NORAIN', '����� ���� ������');
define('RAIN', '�����');
define('SNOWD', '���� �������');
define('WIND', '������');
define('WINDFROM', '������ ���');
define('HUMI', '�������');
define('BARO', '�����');
define('NOFRAME', '� ���������� ��� ��� ����������� � ��� ����� ����������� �� ��������� inline frames.');
define('CURRHEAD', '��������� �������� ���� �������� ����� ��� ���������� ������� ������');
define('POLARN', '������ �����');
define('MIDNIGHTS', '����� �����������');

// TABLEHEADER
define('FEAT', '�������/<br/>��������');
define('CURHEAD', '����.<br />����.');
define('TEMP', '�����������');
define('HUM', '�������');
define('AVG', '���. ������');
define('PRECIPS', '�����');
define('BAROB', '�����');
define('SNOB', '�����');
define('TXTGUST', '����');

// TOPLISTS
define('TOPHEAD', '������ 10 ������������ ��� ��������� ������ ������');
define('MAXTEMP', '���. �����������');
define('MINTEMP', '����. �����������');
define('MAXAVGW', '���. ���. ������');
define('PRECIP', '����� ');
define('MAXHUMI', '���. ����. ���������');
define('MINCHILL', '����. �����. ������');
define('MAXGUSTW', '���. ���� ������');
define('CURRAVG', '��������� �����');
define('DAILYPREC', '��������� �����');
define('TOTPREC', '��������� �����');
define('TXTGUST', '����');

define('NOSTORMS', '��� ������������ ����������');
define('TRACA', '����������');
define('TRACB', '����������');


function defcountries($rawc) {
return $rawc;
}

function defmonths($rawf) {
$txtmon =  array(  
'January' => '����������',
'February' => '�����������',
'March' => '�������',
'April' => '��������',
'May' => '�����',
'June' => '�������',
'July' => '�������',
'August' => '���������',
'September' => '�����������',
'October' => '���������',
'November' => '���������',
'December' => '����������'
);
$txtmonth = $txtmon[$rawf];
return $txtmonth;
}

?>