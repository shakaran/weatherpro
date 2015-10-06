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

define('CURRCOND','Current conditions');
define('METEXT','MET. INST.');
define('ROADEXT','Road-stations');
define('SOUTH','South');
define('WEST','West');
define('NORTH','North');
define('EAST','East');
define('MIDDLE','Middle');
define('YRSTATIONS','met.no stations');
define('SYNOPSTATIONS','Synop-stations');
define('METINFO','MET. INST. - The local meteorologic institutes stations.');
define('ROADINFO','Partly cloudy = Dry weather.');
define('ROADWX','Roadweather');
define('FITRAFNAME','Finnish Road Administration');
define('SVTRAFNAME','Swedish Road Administration');
define('PWSWX','PWS');
define('PWSSTATIONS','PWS-stations');
define('STSTATS','Statistics');
define('STATION','Station');
define('CHOOSECOUNTRY','Change country');
define('CHOOSESTATION','Choose station');
define('SUBMIT','Submit');
define('SINGLESTATION','Single station');
define('COMPARE','Compare');
define('EXTRAMAP','Special layers');
define('SNOWCOVER','Snowcover');
define('ICECOVER','Icecover');
define('SEATEMP','Seatemperature');
define('SNOWDEPTH','Snowdepth');
define('TERRAIN','Terrain');
define('STRIKES','strikes');
define('MINUTES','minutes');
define('GUSTINGTO','Gusting to');
define('SAVESETTINGS','Save as default');
define('APPARENT','Feels like');
define('STATIONDATA','Stationdata');
define('WARNINGSIN','warnings in');
define('CLICKONWARN','Click on the areaname to show the warning.');
define('CURREXTREMES','Current extremes');

define('SETTINGS','Settings');
define('STATS','Estadisticas');
define('STATSFOR','Estadísticas de la Red Europea del tiempo');
define('STSPERCOUNTRY','Estaciones / País');
define('NORTHERNMOST','Northernmost/Southernmost etc');
define('NEWEST','Nuevas estaciones');
define('MAPLOADS','Maploads');
define('MISC','Miscelanea');

define('LIGHT','Light');
define('DARK','Oscuro');
define('BASEMAP','Mapa base');
define('TYPE','Tipo');
define('DATA','Datos');
define('WINRUN','Windrun');
define('RRATE','Rainrate');

define('ACTIVEALARMS','Advertencias activas');
define('ACTIVETRACKERS','Tormentas detectadas');
define('PWS','PWS');
define('SYNOP','Synop/Metar');
define('NAUTRAL','Natural');
define('NIGHTVIEW','Vista nocturna');
define('BLUEVIEW','Blueview');
define('BWTXT','Blanco & Negro');
define('SATELLITE','Satelite');
define('SEAWARN','Seawarnings');
define('MALARMWARN','Landwarnings');
define('SEAICE','Seadata');
define('ICE','Hielo');

define('MAP','Mapa');
define('WXSIMFRCST','5 dias WXSIM-forecast');
define('WXSIMINFO',' Previsión hecha con WXSIM-software');
define('FRCST','48 previsión horas');
define('HIDE','Hide');
define('TOPLISTS','Lista Principal');
define('TABLES','Tablas');
define('INFO','Info');
define('BALTIC','Baltico');
define('MOREWEATHER1','Mas Tiempo');
define('IHAVENOTWXSTATION','I don\'t have weatherstation');
define('YOURINFO','Tus informaciones');
define('PICKCOORDS','Pick coordenadas del mapa');
define('PASSW','Contraseña');
define('DEFLOC','Ubicacion predeterminada');
define('SAVE','Save');
define('STATIONLESS','Manual');
define('SAVED','Saved');
define('EXTREMECOLDBOX','inusualmente frío en las estaciones siguientes');
define('EXTREMEHOTBOX',' inusualmente cálido en las estaciones siguientes');

// Main
define('JOINUS', 'Unete');
define('LEGEND', 'Legend');
define('WEATHER', 'Weather');
define('WEBCAM', 'Webcam');
define('LIGHTNING', 'Lightning');
define('SNOWNOTE', '<b> * Nota </ ??b> No todas las emisoras informan Espesor!.');
define('NOCOND', 'No informar de las condiciones actuales');
define('LOCALTIME', 'Hora Local');
define('STATIONS', 'Estaciones');
define('CLICKFORINFO', 'Haga clic para más información de cómo unirse a nosotros!');
define('TODAY', 'Hoy');
define('ALLTIME', 'Alltime');
define('FORECAST', 'Pronostico');
define('ROAD','Camino');
define('WX','Wx');
define('POUTA','Dry');
define('LPRECIP','Light precip');
define('MPRECIP','Mod. precip');
define('HPRECIP','Heavy precip');
define('DRY','Dry');
define('WET','Wet');
define('ICY','Icy');
define('SNOWY','Snowy');
define('SLIPPERY','Resbaladizo');
define('LAYER','Capa');
define('REPSNOW','Representante.');
define('ALLCOUT','Todos los Paises');

define('COORDS','Coordinadas');
define('NOCAM','Station have not cam');
define('SYNPRODBY','Synop/Metar-datos producidos por');
define('ROADPRODBY','-data produced by');
define('SGRAPHHEAD','Ultimas 24 horas');
define('MOREGRAPH','Mas graficos');
define('MAPSETTINGS','Mapsettings');
define('WARMCOLD','Warmest/Coldest');
define('DATACOUNTER','Datacounter');
define('STARTED','Introduccion');
define('JOINUS','Join Us');
define('MEMBERAREA','Zona Miembros');
define('WAIT','Cargando...');
define('GRAPH','Graficos');
define('DAYS','Dias');
define('THUNDER','Thunder');
define('SHOWICON','Iconos');
define('SHOWSTATION','Estaciones');

// V5
$txtcolors = array('Yellow','Orange','Red');
$txttypes = array('Viento', 'Nieve / Hielo', 'Lluvia', 'Niebla', 'alta temperatura extrema', 'baja temperatura Extreme', 'Evento Costero', 'Forestfire "," avalanchas "," Lluvia ');
define('AWLEVEL', 'Awareness level');
define('VALID','Valido');
define('NWNDISCL','Admin &amp; script made by <a rel="external" href="http://www.europeanweathernetwork.eu">European Weather Network</a>. <br/>EWN is not responsible for the accuracy of the information.');


define('ALT', 'Alt');
define('HOMEPAGE', 'Pagina Inicio');
define('HERE', 'aqui');
define('CONDITIONS', 'Condiciones');
define('FCST', 'Pronostico');
define('NOTHUNDER', 'Tormentas no detectadas');
define('FEATB', 'Estacion');
define('WINDBOX', 'vientos huracanados se detectan en las estaciones siguientes(> 22 m/s)');
define('SUMMERFROSTBOX', 'Frost se detectan en las estaciones siguientes');
define('WARN', 'Advertencias');

// NEW
define('SYNEXT', 'Synop estaciones');
define('ROADSTS', 'Roadwx');
define('TIME', 'Tiempo');
define('WEBCAMS', 'Webcams');
define('LIGHTNINGS', 'Relampagos');
define('OPTIONS', 'Opciones');
define('COND', 'Condiciones');
define('DIR', 'Direccion');
define('RRADAR', 'Radar Lluvia');
define('STATIONSS', ' estaciones');
define('CALM', 'Calma');
define('DEWP', 'Rocio');
define('CCOVER', 'Cloudcover');
define('VISIB', 'Visibilidad');
define('NOOBS', 'No observation');
define('SHOW', 'Mostrar');

define('WCHILL', 'Windchill');
define('HEAT', 'Heatidx');
define('TRACA', 'Seguimiento');
define('TRACB', 'tormentas');
define('TRACBOX' , 'Las siguientes estaciones detectan tormentas');
define('BOXPRTB', '');

// NEW
define('NOPE', 'No');
define('YES', 'Si');
define('', '');

// MISC

define('TOPMISC', 'Misc data');
define('NORTHMST', 'Northernmost');
define('SOUTHMST', 'Southernmost');
define('WESTMST', 'Westernmost');
define('EASTMST', 'Easternmost');
define('HIGHMST', 'Highest');
define('LOWMST', 'Lowest');
define('CAMS', 'Webcams');
define('TRACKERS', 'Stormtrackers');


// BALLOONS
define('TRACKER', 'Stormtracker');
define('NORAIN', 'No precip. today');
define('RAIN', 'Precip');
define('SNOWD', 'Snowdepth');
define('WIND', 'Wind');
define('WINDFROM', 'Wind from');
define('HUMI', 'Humidity');
define('BARO', 'Baro');
define('NOFRAME', 'Your browser does not support inline frames or is currently configured not to display inline frames.');
define('CURRHEAD', 'Current conditions at member stations of the European Weather Network');
define('POLARN', 'Polar Night');
define('MIDNIGHTS', 'Midnight Sun');

// TABLEHEADER
define('FEAT', 'Station/<br/>Altitude');
define('CURHEAD', 'Curr<br />Cond');
define('TEMP', 'Temp');
define('HUM', 'Hum');
define('AVG', 'Avg wind');
define('PRECIPS', 'Precip.');
define('BAROB', 'Baro');
define('SNOB', 'Snow');
define('TXTGUST', 'Gust');

// TOPLISTS
define('TOPHEAD', 'Top 10 observations from European Weather Network');
define('MAXTEMP', 'Max. temperature');
define('MINTEMP', 'Min. temperature');
define('MAXAVGW', 'Max. avg. wind');
define('PRECIP', 'Precipitation ');
define('MAXHUMI', 'Max. humidex');
define('MINCHILL', 'Min. windchill');
define('MAXGUSTW', 'Max. gust wind');
define('CURRAVG', 'Current averages');
define('DAILYPREC', 'Daily precipitation');
define('TOTPREC', 'Total precipitation');
define('TXTGUST', 'Gust');

define('NOSTORMS', 'No thunderstorms detected');
define('TRACA', 'Tracking');
define('TRACB', 'thunderstorms');


function defcountries($rawc) {
return $rawc;
}

function defmonths($rawf) {
$txtmon =  array(  
'January' => 'January',
'February' => 'February',
'March' => 'March',
'April' => 'April',
'May' => 'May',
'June' => 'June',
'July' => 'July',
'August' => 'August',
'September' => 'September',
'October' => 'October',
'November' => 'November',
'December' => 'December'
);
$txtmonth = $txtmon[$rawf];
return $txtmonth;
}

?>