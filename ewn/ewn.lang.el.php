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

define('CURRCOND','Τρέχουσες Συνθήκες');
define('METEXT','Μετ. Υπηρεσίες');
define('ROADEXT','Σταθμοί Οδών');
define('SOUTH','Νότια');
define('WEST','Δυτικά');
define('NORTH','Βόρεια ');
define('EAST','Ανατολικά');
define('MIDDLE','Κεντρικά');
define('YRSTATIONS','Σταθμοί met.no');
define('SYNOPSTATIONS','Σταθμοί Synop');
define('METINFO','Μετ. Υπηρεσίες - Τοπικοί Σταθμοί Μετεωρολογικής Υπηρεσίας.');
define('ROADINFO','Μερικώς Νεφελώδης = Στεγνός καιρός.');
define('ROADWX','Καιρός Οδών');
define('FITRAFNAME','Διαχείριση Φινλανδικών Οδών');
define('SVTRAFNAME','Διαχείριση Σουηδικών Οδών');
define('PWSWX','ΙΜΣ');
define('PWSSTATIONS','ΙΜΣ-σταθμοί');
define('STSTATS','Στατιστικά');
define('STATION','Σταθμός');
define('CHOOSECOUNTRY','Αλλαγή Χώρας');
define('CHOOSESTATION','Επιλογή Σταθμού');
define('SUBMIT','Επιλογή');
define('SINGLESTATION','Ενας σταθμός');
define('COMPARE','Σύγκριση');
define('EXTRAMAP','Ειδικά επίπεδα');
define('SNOWCOVER','Χιονοκάλυψη');
define('ICECOVER','Παγοκάλυψη');
define('SEATEMP','Θερμοκρασία θαλάσσης');
define('SNOWDEPTH','Υψος χιονιού');
define('TERRAIN','Εδαφος');
define('STRIKES','χτυπήματα');
define('MINUTES','λεπτά');
define('GUSTINGTO','Με ριπές ως');
define('SAVESETTINGS','Αποθήκευσή σαν αρχικό');
define('APPARENT','Αίσθηση ως');
define('STATIONDATA','Δεδομένα σταθμού');
define('WARNINGSIN','Προειδοποιήσεις σε');
define('CLICKONWARN','Πατήστε στην περιοχή για εμφάνιση προειδοποίησης.');
define('CURREXTREMES','Τρέχουσες ακραίες');

define('SETTINGS','Ρυθμίσεις');
define('STATS','Στατιστικά');
define('STATSFOR','Στατιστικά για Ευρωπαικό Δίκτυο Καιρού');
define('STSPERCOUNTRY','Σταθμοί/Χώρα');
define('NORTHERNMOST','Βορειότερος/Νοτιότερος κλπ');
define('NEWEST','νεότεροι σταθμοί');
define('MAPLOADS','Χάρτες');
define('MISC','Διάφορα');

define('LIGHT','Φωτεινός');
define('DARK','Σκοτεινός');
define('BASEMAP','Βασικός χάρτης');
define('TYPE','Τύπος');
define('DATA','Δεδομένα');
define('WINRUN','Ροή ΑνέμουW');
define('RRATE','Ραγδαιότητα');

define('ACTIVEALARMS','ενεργές προειδοποιήσεις');
define('ACTIVETRACKERS','ανιχνευτές που εντόπισαν καταιγίδες');
define('PWS','Ιδιωτικός');
define('SYNOP','Synop/Metar');
define('NAUTRAL','Φυσικό');
define('NIGHTVIEW','ΝύχτερινήNightview');
define('BLUEVIEW','Blueview');
define('BWTXT','Μαύρο & άσπρο');
define('SATELLITE','Δορυφορικό');
define('SEAWARN','Προειδοποιήσεις θάλασσας');
define('MALARMWARN','Προειδοποιήσεις ξηράς');
define('SEAICE','Δεδομένα θάλασσας');
define('ICE','Πάγος');

define('MAP','Χάρτης');
define('WXSIMFRCST','Πρόγνωση 5 ημερών WXSIM');
define('WXSIMINFO','Προγνώσεις σταθμών με λογισμικό WXSIM');
define('FRCST','πρόγνωση48 ωρών');
define('HIDE','Απόκρυψη');
define('TOPLISTS','Κορυφαίοι');
define('TABLES','Πίνακες');
define('INFO','Πληροφορίες');
define('BALTIC','Βαλτική');
define('MOREWEATHER1','Περισσότερος καιρός');
define('IHAVENOTWXSTATION','Δε διαθέτω σταθμό');
define('YOURINFO','Οι πληροφορίες σας');
define('PICKCOORDS','Επιλέξτε συντεταγμένες από χάρτη');
define('PASSW','Κωδικός');
define('DEFLOC','Αρχική περιοχή');
define('SAVE','Αποθήκευση');
define('STATIONLESS','Χειροκίνητα');
define('SAVED','Αποθηκευμένο');
define('EXTREMECOLDBOX','Συνήθως έχει κρύο στους ακόλουθους σταθμούς');
define('EXTREMEHOTBOX','Συνήθως έχει ζέστη στους ακόλουθους σταθμούς');

// Main
define('JOINUS', 'Συνδεθείτε');
define('LEGEND', 'Επεξηγήσεις');
define('WEATHER', 'Καρός');
define('WEBCAM', 'Webcam');
define('LIGHTNING', 'Αστραπή');
define('SNOWNOTE', '<b>*Σημείωση!</b> Δεν αναφέρουν ύψος χιονιού όλοι οι σταθμοί.');
define('NOCOND', 'Δεν υπάρχει αναφορά καιρικών συνθηκών');
define('LOCALTIME', 'Τοπική ώρα');
define('STATIONS', 'σταθμοί');
define('CLICKFORINFO', 'Πατήστε για περισσότερες πληροφορίες για σύνδεση με μας!');
define('TODAY', 'Σήμερα');
define('ALLTIME', 'Από αρχή λειτουργίας');
define('FORECAST', 'Πρόγνωση');
define('ROAD','Οδός');
define('WX','Wx');
define('POUTA','Στεγνός');
define('LPRECIP','Ασθενής υετός');
define('MPRECIP','Μέτριος υετός');
define('HPRECIP','Εντονος υετός');
define('DRY','Στεγνός');
define('WET','Υγρός');
define('ICY','Παγωμένος');
define('SNOWY','Χιονισμένος');
define('SLIPPERY','Πιθανόν ολισθηρός');
define('LAYER','Επίπεδο');
define('REPSNOW','Rep.');
define('ALLCOUT','Ολες οι χώρες');

define('COORDS','Συντεταγμένες');
define('NOCAM','Ο σταθμός δε διαθέτει κάμερα have not cam');
define('SYNPRODBY','Δεδομένα Synop/Metar από');
define('ROADPRODBY','Tiesδδ-data produced by');
define('SGRAPHHEAD','Τελευταίες 24 ώρες');
define('MOREGRAPH','Περισσότερα γραφήματα');
define('MAPSETTINGS','Ρυθμίσεις χάρτη');
define('WARMCOLD','Θερμότερος/Ψυχρότερος');
define('DATACOUNTER','Μετρητής');
define('STARTED','Εναρξη');
define('JOINUS','Συνδεθείτε με μας');
define('MEMBERAREA','Χώρος μελών');
define('WAIT','Φορτώνω...');
define('GRAPH','Γραφήματα');
define('DAYS','Ημέρες');
define('THUNDER','Κεραυνός');
define('SHOWICON','Εικόνες');
define('SHOWSTATION','Σταθμοί');

// V5
$txtcolors = array('Κίτρινος','Πορτοκαλί','Κόκκινος');
$txttypes = array('Ανεμος','Χιόνι/Πάγος','Καταιγίδες','Ομίχλη','Εξαιρετικά υψηλή θερμοκρασία','Εξαιρετικά χαμηλή θερμοκρασία','Παράκτιο Συμβάν','Δασική Πυρκαιά','Χιονοστιβάδες','Βροχή');
define('DISTANCE', 'Απόσταση');
define('AWLEVEL', 'Επίπεδο Επιφυλακής');
define('VALID','Ισχύον');
define('NWNDISCL','<br/>Το EWN δεν είναι υπεύθυνο για την ακρίβεια των πληροφοριών.');


define('ALT', 'Alt');
define('HOMEPAGE', 'Ιστοσελίδα');
define('HERE', 'εδώ');
define('CONDITIONS', 'Συνθήκες');
define('FCST', 'Πρόγνωση');
define('NOTHUNDER', 'Δεν ανιχνεύθηκαν καταιγίδες');
define('FEATB', 'Σταθμός');
define('WINDBOX', 'Θυελλώδεις άνεμοι εντοπίζονται στους παρακάτω σταθμούς (> 22 m/s)');
define('SUMMERFROSTBOX', 'Παγετός εντοπίζεται στους παρακάτω σταθμούς');
define('WARN', 'Προειδοποιήσεις');

// NEW
define('SYNEXT', 'Σταθμοί-Synop');
define('ROADSTS', 'Σταθμοί οδών');
define('TIME', 'Ωρα');
define('WEBCAMS', 'Webcams');
define('LIGHTNINGS', 'Ανιχνευτές καταιγίδων');
define('OPTIONS', 'Επιλογές');
define('COND', 'Συνθήκες');
define('DIR', 'Διεύθυνση');
define('RRADAR', 'Ραντάρ βροχής');
define('STATIONSS', ' σταθμοί');
define('CALM', 'Απνοια');
define('DEWP', 'Σημείο δρόσου');
define('CCOVER', 'Νεφοκάλυψη');
define('VISIB', 'Ορατότητα');
define('NOOBS', 'Χωρίς παρατήρηση');
define('SHOW', 'Εμφάνιση');

define('WCHILL', 'Δείκτης ψύχρας');
define('HEAT', 'Δείκτης Δυσφορίας');
define('TRACA', 'Εντοπισμός');
define('TRACB', 'Καταιγίδες');
define('TRACBOX' , 'Οι ανιχνευτές των παρακάτω σταθμών εντοπίζουν καταιγίδες αυτή τη στιγμή');
define('BOXPRTB', '');

// NEW
define('NOPE', 'Οχι');
define('YES', 'Ναι');
define('', '');

// MISC

define('TOPMISC', 'Διάφορα δεδομένα');
define('NORTHMST', 'Βορειότερος');
define('SOUTHMST', 'Νοτιότερος');
define('WESTMST', 'Δυτικότερος');
define('EASTMST', 'Ανατολικότερος');
define('HIGHMST', 'Υψηλότερη');
define('LOWMST', 'χαμηλότερη');
define('CAMS', 'Webcams');
define('TRACKERS', 'Ανιχνευτές καταιγίδων');


// BALLOONS
define('TRACKER', 'Ανιχνευτής καταιγίδων');
define('NORAIN', 'Χωρίς υετό σήμερα');
define('RAIN', 'Υετός');
define('SNOWD', 'Υψος χιονιού');
define('WIND', 'Ανεμος');
define('WINDFROM', 'Ανεμος από');
define('HUMI', 'Υγρασία');
define('BARO', 'Πίεση');
define('NOFRAME', 'Ο περιηγητής σας δεν υποστηρίζει ή δεν είναι ρυθμισμένος να εμφανίζει inline frames.');
define('CURRHEAD', 'Τρέχουσες συνθήκες στου σταθμούς μελών του Ευρωπαικού Δικτύου Καιρού');
define('POLARN', 'Πολική Νύχτα');
define('MIDNIGHTS', 'Ηλιος Μεσονυκτίου');

// TABLEHEADER
define('FEAT', 'Σταθμός/<br/>Υψόμετρο');
define('CURHEAD', 'Τρεχ.<br />Συνθ.');
define('TEMP', 'Θερμοκρασία');
define('HUM', 'Υγρασία');
define('AVG', 'Μεσ. άνεμος');
define('PRECIPS', 'Υετός');
define('BAROB', 'Πίεση');
define('SNOB', 'Χιόνι');
define('TXTGUST', 'Ριπή');

// TOPLISTS
define('TOPHEAD', 'Πρώτες 10 παρατηρήσεις από Ευρωπαικό Δίκτυο Καιρού');
define('MAXTEMP', 'Μέγ. θερμοκρασία');
define('MINTEMP', 'Ελάχ. θερμοκρασία');
define('MAXAVGW', 'Μέγ. μέσ. άνεμος');
define('PRECIP', 'Υετός ');
define('MAXHUMI', 'Μέγ. δείκ. δυσφορίας');
define('MINCHILL', 'Ελάχ. δείντ. ψύχρας');
define('MAXGUSTW', 'Μέγ. ριπή ανέμου');
define('CURRAVG', 'Τρέχουσες μέσες');
define('DAILYPREC', 'Ημερήσιος υετός');
define('TOTPREC', 'Συνολικός υετός');
define('TXTGUST', 'Ριπή');

define('NOSTORMS', 'Δεν εντοπίζονται καταιγίδες');
define('TRACA', 'Εντοπισμός');
define('TRACB', 'καταιγίδες');


function defcountries($rawc) {
return $rawc;
}

function defmonths($rawf) {
$txtmon =  array(  
'January' => 'Ιανουάριος',
'February' => 'Φεβρουάριος',
'March' => 'Μάρτιος',
'April' => 'Απρίλιος',
'May' => 'Μάϊος',
'June' => 'Ιούνιος',
'July' => 'Ιούλιος',
'August' => 'Αύγουστος',
'September' => 'Σεπτέμβριος',
'October' => 'Οκτώβριος',
'November' => 'Νοέμβριος',
'December' => 'Δεκέμβριος'
);
$txtmonth = $txtmon[$rawf];
return $txtmonth;
}

?>