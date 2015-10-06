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

########################################################################

define('CURRCOND','Forholdene nå');
define('METEXT','MET. INST.');
define('ROADEXT','Veistasjoner');
define('SOUTH','Sør');
define('WEST','Vest');
define('NORTH','Nord');
define('EAST','Øst');
define('MIDDLE','Sentral');
define('YRSTATIONS','met.no-stasjoner');
define('SYNOPSTATIONS','Synop-stasjoner');
define('METINFO','Met. institutter - stationer fra det lokale meteorologiske institutt.');
define('ROADINFO','Delvis skyet = tørt vær.');
define('ROADWX','Vei-vær');
define('FITRAFNAME','Finske veimyndigheter');
define('SVTRAFNAME','Svenske veimyndigheter');
define('PWSWX','PWS');
define('PWSSTATIONS','PWS-stasjoner');
define('STSTATS','Statistikk');
define('STATION','Stasjon');
define('CHOOSECOUNTRY','Bytt land');
define('CHOOSESTATION','Velg stasjon');
define('SUBMIT','Send');
define('SINGLESTATION','Enkle stasjon');
define('COMPARE','Sammenlikn');
define('EXTRAMAP','Spesial lag');
define('SNOWCOVER','Snødekke');
define('ICECOVER','Isdekke');
define('SEATEMP','Sjøtemperatur');
define('SNOWDEPTH','Snødybde');
define('TERRAIN','Terreng');
define('STRIKES','nedslag');
define('MINUTES','minutter');
define('GUSTINGTO','Vindkast på');
define('SAVESETTINGS','Lagre som standard');
define('APPARENT','Føles som');
define('STATIONDATA','Stasjonsdata');
define('WARNINGSIN','varsler i');
define('CLICKONWARN','Klikk på område for å se advarsel.');
define('CURREXTREMES','Ekstremer nå');

define('SETTINGS','Innstillinger');
define('STATS','Statistikk');
define('STATSFOR','Statistikk for European Weather Network');
define('STSPERCOUNTRY','Stasjoner/Land');
define('NORTHERNMOST','Nordligste/Sørligste etc');
define('NEWEST','nyeste stasjon');
define('MAPLOADS','Kartlastinger');
define('MISC','Forskj.');

define('LIGHT','Lys');
define('DARK','Mørk');
define('BASEMAP','Basiskart');
define('TYPE','Type');
define('DATA','Data');
define('WINRUN','Windrun');
define('RRATE','Regnrate');

define('ACTIVEALARMS','aktive advarsler');
define('ACTIVETRACKERS','detektorer har registrert storm');
define('PWS','Privat');
define('SYNOP','Synop/Metar');
define('NAUTRAL','Naturlig');
define('NIGHTVIEW','Nattvisning');
define('BLUEVIEW','Blå visning');
define('BWTXT','Svart / hvitt');
define('SATELLITE','Satellitt');
define('SEAWARN','Sjøadvarsler');
define('MALARMWARN','Landadvarsler');
define('SEAICE','Sjødata');
define('ICE','Is');

define('MAP','Kart');
define('WXSIMFRCST','5 dagers WXSIM-værvarsel');
define('WXSIMINFO','Stasjonens egen værvarsel laget med WXSIM-programvare');
define('FRCST','48 timers værvarsel');
define('HIDE','Lukk');
define('TOPLISTS','Topplister');
define('TABLES','Tabeller');
define('INFO','Info');
define('BALTIC','Baltikum');
define('MOREWEATHER1','Mer vær');
define('IHAVENOTWXSTATION','Jeg har ikke værstasjon');
define('YOURINFO','Din info');
define('PICKCOORDS','Velg koordinater fra kartet');
define('PASSW','Passord');
define('DEFLOC','Standard sted');
define('SAVE','Lagre');
define('STATIONLESS','Manuell');
define('SAVED','Lagret');
define('EXTREMECOLDBOX','Det er unormalt kaldt på følgende stasjoner');
define('EXTREMEHOTBOX','Det er unormalt varmt på følgende stasjoner');

// Main
define('JOINUS', 'Bli med');
define('LEGEND', 'Forklaring');
define('WEATHER', 'Vær');
define('WEBCAM', 'Webcam');
define('LIGHTNING', 'Lyn');
define('SNOWNOTE', '<b>*Merk!</b> Alle stasjoner rapporterer ikke snødybde.');
define('NOCOND', 'Ingen nye værdata');
define('LOCALTIME', 'Lokal tid');
define('STATIONS', 'stasjoner');
define('CLICKFORINFO', 'Klikk her for mer info om hvordan du kan bli med!');
define('TODAY', 'Idag');
define('ALLTIME', 'Alltime');
define('FORECAST', 'Prognose');
define('ROAD','Vei');
define('WX','Vær');
define('POUTA','Opphold');
define('LPRECIP','Lett nedbør');
define('MPRECIP','Moderat nedbør');
define('HPRECIP','Kraftig nedbør');
define('DRY','Tørt');
define('WET','Vått');
define('ICY','Isete');
define('SNOWY','Snø');
define('SLIPPERY','Glatt');
define('LAYER','Kart-lag');
define('REPSNOW','Rap.');
define('ALLCOUT','Alle land');

define('COORDS','Koordinater');
define('NOCAM','Stationen har ikke webcam');
define('SYNPRODBY','Synop/Metar-data levert av');
define('ROADPRODBY','Vei-data levert av');
define('REMOVEFAVO','Fjern favorittstasjon');
define('SAVEFAVO','Lagre som favorittstasjon');
define('SGRAPHHEAD','Siste 24 timene');
define('MOREGRAPH','Flere grafer');
define('MAPSETTINGS','Kartinstillinger');
define('WARMCOLD','Varmeste/Kaldeste');
define('DATACOUNTER','Datateller');
define('STARTED','Startet');
define('JOINUS','Bli med');
define('MEMBERAREA','Medlemsområde');
define('WAIT','Laster...');
define('FAVOOFFLINE','Ser ut som din favorittstasjon er offline :(');
define('GRAPH','Grafer');
define('DAYS','Dager');
define('THUNDER','Torden');
define('SHOWICON','Ikoner');
define('SHOWSTATION','Stasjoner');

define('DISTANCE', 'Avstand');
define('YOURCLOSEST','Din nærmeste stasjon');
define('AWLEVEL', '');
define('VALID','Gyldig');

// V5

define('ALT', 'Hoh');
define('HOMEPAGE', 'Hjemmeside');
define('HERE', 'her');
define('CONDITIONS', 'Forhold');
define('FCST', 'Varsel');
define('NOTHUNDER', 'Ingen tordenvær oppdaget');
define('FEATB', 'Stasjon');
define('WINDBOX', 'Kuling er observert på følgende stasjoner (> 22 m/s)');
define('SUMMERFROSTBOX', 'Frost er observert på følgende stasjoner');
define('WARN', 'Advarsler');

// NEW
define('SYNEXT', 'Synop-stasjoner');
define('ROADSTS', 'Vei');
define('TIME', 'Tid');
define('WEBCAMS', 'Webcams');
define('LIGHTNINGS', 'Lyndetektorer');
define('OPTIONS', 'Innstillinger');
define('COND', 'Værforhold');
define('DIR', 'Retning');
define('RRADAR', 'Nedbørsradar');
define('STATIONSS', ' stasjoner');
define('CALM', 'Stille');
define('DEWP', 'Duggpunkt');
define('CCOVER', 'Skydekke');
define('VISIB', 'Sikt');
define('NOOBS', 'Ingen observasjon');
define('SHOW', 'Vis');

define('WCHILL', 'Følt temperatur');
define('HEAT', 'Varmeindeks');
define('TRACA', 'Følger');
define('TRACB', 'tordenvær');
define('TRACBOX' , 'Følgende stasjoner følger tordenvær nå');

// NEW
define('STSTATS', 'Statistikk');
define('TECHNIC', 'Teknikk');
define('SOFTWARE', 'Program');
define('HARDWARE', 'Type');
define('NOPE', 'Nei');
define('YES', 'Ja');
define('', '');

// MISC
define('TOPMISC', 'Diverse data');
define('NORTHMST', 'Nordligste');
define('SOUTHMST', 'Sydligste');
define('WESTMST', 'Vestligste');
define('EASTMST', 'Østligste');
define('HIGHMST', 'Høyeste');
define('LOWMST', 'Laveste');
define('CAMS', 'Webkameraer');
define('TRACKERS', 'Lyndetektorer');

// BALLOONS
define('TRACKER', 'Lyndetektor');
define('NORAIN', 'Ingen nedbør i dag');
define('RAIN', 'Nedbør');
define('SNOWD', 'Snødybde');
define('WIND', 'Vind');
define('WINDFROM', 'Vind fra');
define('HUMI', 'Fuktighet');
define('BARO', 'Lufttrykk');
define('NOFRAME', 'Din nettleser har ikke støtte for "inline frames" eller er ikke konfigurert til å støtte "inline frames".');
define('CURRHEAD', 'Nåværende værforhold hos European Weather Network');
define('POLARN', 'Mørketid');
define('MIDNIGHTS', 'Midnattsol');

// TABLEHEADER
define('FEAT', 'Stasjon<br/>Egenskaper/Høyde o.h.');
define('CURHEAD', 'Vær<br />nå');
define('TEMP', 'Temp');
define('HUM', 'Fukt');
define('AVG', 'Gjsn vind');
define('PRECIPS', 'Nedbør');
define('BAROB', 'Lufttr.');
define('SNOB', 'Snø');

// TOPLISTS
define('TOPHEAD', 'Topp 10 observasjoner fra European Weather Network');
define('MAXTEMP', 'Høyeste temperatur');
define('MINTEMP', 'Laveste temperatur');
define('MAXAVGW', 'Høyeste gjsn. vind');
define('PRECIP', 'Nedbør ');
define('MAXHUMI', 'Høyeste følt temp');
define('MINCHILL', 'Laveste vindkjøling');
define('MAXGUSTW', 'Høyeste vindkast');
define('CURRAVG', 'Nåværende gjennomsnitt');
define('DAILYPREC', 'Daglig nedbør');
define('TOTPREC', 'Total nedbør');
define('TXTGUST', 'Vindkast');

define('NOSTORMS', 'Ingen tordenvær');
define('TRACA', 'Følger');
define('TRACB', 'tordenvær');


function defcountries($rawc) {
$txtcountries =  array(
"Albania" => "Albania",
"Andorra" => "Andorra",
"Austria" => "Østerrike",
"Belgium" => "Belgia",
"Bulgaria" => "Bulgaria",
"Switzerland" => "Sveits",
"Czech" => "Tsjekkia",
"Germany" => "Tyskland",
"Denmark" => "Danmark",
"Estonia" => "Estland",
"Spain" => "Spania",
"Finland" => "Finland",
"Faroe" => "Færøyene",
"France" => "Frankrike",
"UK" => "Stor-Britannia",
"Greece" => "Grekland",
"Hungary" => "Ungaren",
"Croatia" => "Kroatien",
"Ireland" => "Irland",
"Iceland" => "Island",  
"Italy" => "Italia",
"Latvia" => "Latvien",
"Lithuania" => "Litauen",
"Luxembourg" => "Luxemburg",
"Netherlands" => "Nederland",
"Norway" => "Norge", 
"Poland" => "Polen",
"Portugal" => "Portugal",
"Sweden" => "Sverige",
"Slovakia" => "Slovakien",
"Slovenia" => "Slovenia",
"Greenland" => "Grönland",
"Macedonia" => "Macedonia",
"Serbia" => "Serbia",
"Bosnia" => "Bosnia",
"Romania" => "Romania");
if($txtcountries[$rawc]<>''){return $txtcountries[$rawc];}
else {return $rawc;}
}

function defmonths($rawf) {
$txtmon =  array(
'January' => 'Januar',
'February' => 'Februar',
'March' => 'Mars',
'April' => 'April',
'May' => 'Mai',
'June' => 'Juni',
'July' => 'Juli',
'August' => 'August',
'September' => 'September',
'October' => 'Oktober',
'November' => 'November',
'December' => 'Desember'
);

$txtmonth = $txtmon[$rawf];
return $txtmonth;
}

?>