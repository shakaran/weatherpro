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

define('CURRCOND','Condizioni attuali');
define('METEXT','MET. INST.');
define('ROADEXT','stazioni-Stradali');
define('SOUTH','Sud');
define('WEST','Ovest');
define('NORTH','Nord');
define('EAST','Est');
define('MIDDLE','Middle');
define('YRSTATIONS','stazioni met.no');
define('SYNOPSTATIONS','stazioni Synop');
define('METINFO','MET. INST. - Stazioni Istituto meteorologico locale.');
define('ROADINFO','Parzialmente nuvoloso = Secco.');
define('ROADWX','Roadweather');
define('FITRAFNAME','Amministrazione Finnish Road');
define('SVTRAFNAME','Amministrazione Swedish Road');
define('PWSWX','PWS');
define('PWSSTATIONS','stazioni PWS');
define('STSTATS','Statistiche');
define('STATION','Statione');
define('CHOOSECOUNTRY','Cambia Nazione');
define('CHOOSESTATION','Scegli stazione');
define('SUBMIT','Invio');
define('SINGLESTATION','Singola stazione');
define('COMPARE','Compara');
define('EXTRAMAP','Special layers');
define('SNOWCOVER','Copertura nevosa');
define('ICECOVER','Icecover');
define('SEATEMP','Temperatura mare');
define('SNOWDEPTH','Profondit‡ neve');
define('TERRAIN','Terreno');
define('STRIKES','fulmini');
define('MINUTES','minuti');
define('GUSTINGTO','Raffica a');
define('SAVESETTINGS','Salva come default');
define('APPARENT','Percepita');
define('STATIONDATA','Dati stazione');
define('WARNINGSIN','allarmi in');
define('CLICKONWARN','Clicca sul nome dell\'area per mostrare gli allarmi.');
define('CURREXTREMES','Estremi attuali');

define('SETTINGS','Impostazioni');
define('STATS','Statistiche');
define('STATSFOR','Statistiche European Weather Network');
define('STSPERCOUNTRY','Stazioni/Nazioni');
define('NORTHERNMOST','Pi&ugrave; a Nord/Pi&ugrave; a Sud ecc');
define('NEWEST','nuove stazioni');
define('MAPLOADS','Maploads');
define('MISC','Misc');

define('LIGHT','Chiara');
define('DARK','Scura');
define('BASEMAP','Mappa Base');
define('TYPE','Tipo');
define('DATA','Dati');
define('WINRUN','Percorso Vento');
define('RRATE','Rainrate');

define('ACTIVEALARMS','avvisi attivi');
define('ACTIVETRACKERS','trackers hanno rilevato temporale/i');
define('PWS','Privato');
define('SYNOP','Synop/Metar');
define('NAUTRAL','Naturale');
define('NIGHTVIEW','Vista Notturna');
define('BLUEVIEW','Vista blu');
define('BWTXT','Bianco & Nero');
define('SATELLITE','Satellite');
define('SEAWARN','Avvisi Mare');
define('MALARMWARN','Avvisi Terra');
define('SEAICE','Dati mare');
define('ICE','Ghiaccio');

define('MAP','Mappa');
define('WXSIMFRCST','Previsioni-WXSIM 5 giorni');
define('WXSIMINFO','Stazioni con previsioni proprie elaborate con software WXSIM');
define('FRCST','Previsioni 48 ore');
define('HIDE','Nascondi');
define('TOPLISTS','Toplist');
define('TABLES','Tabelle');
define('INFO','Info');
define('BALTIC','Baltic');
define('MOREWEATHER1','More weather');
define('IHAVENOTWXSTATION','Non ho una stazione meteo');
define('YOURINFO','Tue info');
define('PICKCOORDS','Ottieni coordinate dalla mappa');
define('PASSW','Password');
define('DEFLOC','Posizione default');
define('SAVE','Salva');
define('STATIONLESS','Manuale');

define('SAVED','Salvato');
define('EXTREMECOLDBOX','Inusualmente freddo sulle seguenti stazioni');
define('EXTREMEHOTBOX','Inusualmente caldo sulle seguenti stazioni');

// Main
define('JOINUS', 'Iscriviti');
define('LEGEND', 'Legenda');
define('WEATHER', 'Weather');
define('WEBCAM', 'Webcam');
define('LIGHTNING', 'Fulmini');
define('SNOWNOTE', '<b>*Nota!</b> Non tutte le stazioni riportano la profondit&agrave; del manto nevoso.');
define('NOCOND', 'Alcun report sulle condizioni attuali');
define('LOCALTIME', 'Orario locale');
define('STATIONS', 'stazioni');
define('CLICKFORINFO', 'Clicca per maggiori info relative all\'iscrizione alla rete!');
define('TODAY', 'Oggi');
define('ALLTIME', 'Sempre');
define('FORECAST', 'Previsione');
define('ROAD','Road');
define('WX','Wx');
define('POUTA','Nubi sparse');
define('LPRECIP','Deboli precip');
define('MPRECIP','Mod. precip');
define('HPRECIP','Forti precip');
define('DRY','Secco');
define('WET','Bagnato');
define('ICY','Ghiacciato');
define('SNOWY','Nevoso');
define('SLIPPERY','Scivoloso');
define('LAYER','Layer');
define('REPSNOW','Rep.');
define('ALLCOUT','Tutte le nazioni');

define('COORDS','Coordinate');
define('NOCAM','La stazione non ha la cam');
define('SYNPRODBY','Synop/Metar-data prodotti da');
define('ROADPRODBY','Ties‰‰-data prodotti da');
define('SGRAPHHEAD','Ult. 24 Ore');
define('MOREGRAPH','Pi&ugrave; grafici');
define('MAPSETTINGS','Settaggi Mappa');
define('WARMCOLD','Pi&ugrave; Calde/Pi&ugrave; Fredde');
define('DATACOUNTER','Datacounter');
define('STARTED','Partito');
define('JOINUS','Iscriviti');
define('MEMBERAREA','Area membri');
define('WAIT','Caricamento...');
define('GRAPH','Grafici');
define('DAYS','Giorni');
define('THUNDER','Temporali');
define('SHOWICON','Icone');
define('SHOWSTATION','Stazioni');

// V5
$txtcolors = array('Giallo','Arancione','Rosso');
$txttypes = array('Vento','Neve/Ghiaccio','Temporali','Nebbia','Temperature massime estreme','Temperature minime estreme','Eventi costieri','Incendi','Valanghe','Pioggia');
define('DISTANCE', 'Distanza');
define('AWLEVEL', 'Livello di comprensione');
define('VALID','Valido');
define('NWNDISCL','Admin &amp; script made by <a rel="external" href="http://www.nordicweather.net">nordicweather.net</a>. <br/>EWN non &egrave; responsabile circa la precisione delle informazioni meteo.');


define('ALT', 'Alt');
define('HOMEPAGE', 'Homepage');
define('HERE', 'qui');
define('CONDITIONS', 'Condizioni');
define('FCST', 'Previsione');
define('NOTHUNDER', 'Nessun temporale rilevato');
define('FEATB', 'Stazione');
define('WINDBOX', 'Forti venti di tempesta sono rilevati dalle seguenti stazioni (> 22 m/s)');
define('SUMMERFROSTBOX', 'Gelate sono rilevate dalle seguenti stazioni');
define('WARN', 'Avvisi');

// NEW
define('SYNEXT', 'stazioni Synop');
define('ROADSTS', 'Roadwx');
define('TIME', 'Ora');
define('WEBCAMS', 'Webcam');
define('LIGHTNINGS', 'Stormtrackers');
define('OPTIONS', 'Opzioni');
define('COND', 'Condizioni');
define('DIR', 'Direzione');
define('RRADAR', 'Radar pioggia');
define('STATIONSS', ' stazioni');
define('CALM', 'Calmo');
define('DEWP', 'Dewpoint');
define('CCOVER', 'Cop.Nuvolosa');
define('VISIB', 'Visibilit&agrave;');
define('NOOBS', 'N.B.');
define('SHOW', 'Mostra');

define('WCHILL', 'Windchill');
define('HEAT', 'Heatidx');
define('TRACA', 'Rilevamento');
define('TRACB', 'temporali');
define('TRACBOX' , 'Le seguenti stazioni hanno rilevato temporali in questo momento');
define('BOXPRTB', '');

// NEW
define('NOPE', 'No');
define('YES', 'S&igrave;');
define('', '');

// MISC

define('TOPMISC', 'Misc data');
define('NORTHMST', 'Pi&ugrave; a Nord');
define('SOUTHMST', 'Pi&ugrave; a Sud');
define('WESTMST', 'Pi&ugrave; a Ovest');
define('EASTMST', 'Pi&ugrave; a Est');
define('HIGHMST', 'Pi&ugrave; alta');
define('LOWMST', 'Pi&ugrave; bassa');
define('CAMS', 'Webcam');
define('TRACKERS', 'Rilevatori di fulmini');


// BALLOONS
define('TRACKER', 'Rilevatore temporale');
define('NORAIN', 'No precip. oggi');
define('RAIN', 'Precip');
define('SNOWD', 'Profondit&agrave; Neve');
define('WIND', 'Vento');
define('WINDFROM', 'Vento da');
define('HUMI', 'Umidit&agrave;');
define('BARO', 'Baro');
define('NOFRAME', 'Il tuo browser non supporta frames inline oppure &egrave; attualmente configurato per non mostrare frames inline.');
define('CURRHEAD', 'Attuali condizioni delle stazioni membre di European Weather Network');
define('POLARN', 'Notte Polare');
define('MIDNIGHTS', 'Sole a mezzanotte');

// TABLEHEADER
define('FEAT', 'Altitudine/<br/>Stazione');
define('CURHEAD', 'Cond<br />Attuali');
define('TEMP', 'Temp');
define('HUM', 'Hum');
define('AVG', 'Media Vento');
define('PRECIPS', 'Precip.');
define('BAROB', 'Baro');
define('SNOB', 'Neve');
define('TXTGUST', 'Raffica');

// TOPLISTS
define('TOPHEAD', 'Top 10 osservazioni da European Weather Network');
define('MAXTEMP', 'Temperature Max');
define('MINTEMP', 'Temperature Min');
define('MAXAVGW', 'Max Media Vento');
define('PRECIP', 'Precipitazioni');
define('MAXHUMI', 'Max Humidex');
define('MINCHILL', 'Min Windchill');
define('MAXGUSTW', 'Max Raffica vento');
define('CURRAVG', 'Medie attuali');
define('DAILYPREC', 'Precipitazioni odierne');
define('TOTPREC', 'Precipitazioni totali');
define('TXTGUST', 'Raffica');

define('NOSTORMS', 'Nessun temporale rilevato');
define('TRACA', 'Rilevamento');
define('TRACB', 'temporali');


function defcountries($rawc) {
return $rawc;
}

function defmonths($rawf) {
$txtmon =  array(  
'January' => 'Gennaio',
'February' => 'Febbraio',
'March' => 'Marzo',
'April' => 'Aprile',
'May' => 'Maggio',
'June' => 'Giugno',
'July' => 'Luglio',
'August' => 'Agosto',
'September' => 'Settembre',
'October' => 'Ottobre',
'November' => 'Novembre',
'December' => 'Dicembre'
);
$txtmonth = $txtmon[$rawf];
return $txtmonth;
}

?>