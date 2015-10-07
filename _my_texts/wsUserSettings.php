<?php
$pageName	= "wsUserSettings.php";
$pageVersion	= "0.02 2015-09-19";
#
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE["wsModules"][$pageName] = "version: " . $pageVersion;
if (!isset($pathString)) {$pathString="";}
$pathString .= "<!-- module ".$pageName." ==== ".$SITE["wsModules"][$pageName]." -->".PHP_EOL;
#---------------------------------------------------------
# 0.02  generated 2015-09-19
#---------------------------------------------------------
$SITE["wsDebug"]         = true;         	##### 
#$SITE["wsDebug"]        = false;        	##### remove comment mark at position 1 when you are fully satisfied with your site.
#---------------------------------------------------------
# Two important questions to select the correct questions from now on
#---------------------------------------------------------
$SITE["WXsoftware"]     = "WC";                 # CU = Cumulus  |  CW = consoleWD  |  DW = Davis_WL.com  |  MB = Meteobridge  |  MH = Meteohub  |  MP = Meteoplug  |  VW = VWS  |  WC = WeatherCat  |  WD = WeatherDisplay  |  WL = WeatherLink  |  WS = WSWIN  |  WV = Wview
$SITE["region"]         = "europe";             # america = USA  |  canada = Canada  |  europe = Europe  |  other = Other
#---------------------------------------------------------
# General settings such as station location, identity
#---------------------------------------------------------
# 
# The name of your Weather-Station and the area it services
$SITE["organ"]          = "Inital settings";
$SITE["yourArea"]       = "your area";
# 
# The location of your weatherstation on the globe
$SITE["latitude"]       = "50.85000";           # 30 = 70
$SITE["longitude"]      = "4.34000";            # -30 = 40
# 
# The timezone you are in and the language you use
$SITE["tz"]             = "Europe/Brussels";
$SITE["lang"]           = "en";                 # en = English  |  nl = Dutch  |  fr = French  |  de = German
# 
# Where to load the current conditions from
$SITE["curCondFrom"]    = "yahoo";              # yahoo = Yahoo - needs area code  |  metar = metar - uses your nearby metar  |  wd = WeatherDisplay if it has a solar sensor and is configured correctly
$SITE["yaPlaceID"]      = "973505";
$SITE["METAR"]          = "EBBR";               # 4
#---------------------------------------------------------
# The general parts for every page
#---------------------------------------------------------
# 
# Page and menu size
$SITE["pageWidth"]      = "1200";               # 1200 = 2400
$SITE["menuWidth"]      = "180";                # 180 = 220
$SITE["menuPlace"]      = "V";                  # V = Vertical left side  |  H = Horizontal
# 
# The  yellow box at the top
$SITE["maintenanceShow"]= true;                 # true = Display the yellow box on top of the page  |  false = Do not use
$SITE["maintenanceTxt"] = "./_my_texts/maintenance.txt";
# 
# Type of header and what color scheme
$SITE["header"]         = "3";                  # 3 = Header with gauges  |  2 = Normal header with blocks  |  1 = Lower header with less information
$SITE["colorNumber"]    = "0";                  # 0 = weather_adapted  |  1 = green  |  2 = blue  |  3 = pastel  |  4 = red  |  5 = orange  |  6 = none  |  7 = ws_clouds  |  8 = ws_cloudsn  |  9 = ws_mist  |  10 = ws_moon  |  11 = ws_pclouds  |  12 = ws_rain  |  13 = ws_snow  |  14 = ws_storm  |  15 = ws_sun  |  16 = ws_thunder
# 
# Optional side and bottom area
$SITE["sideDisplay"]    = true;                 # true = Side area will be used  |  false = Do not display
$SITE["bottomDisplay"]  = true;                 # true = Bottom area will be used  |  false = Do not display
# 
# Banners for adds , photo's and so on
$SITE["bannerTop"]      = true;                 # true = use my text file  |  false = Do not display
$SITE["bannerTopTxt"]   = "./_my_texts/banner.txt";
$SITE["bannerBottom"]   = true;                 # true = use my text file  |  false = Do not display
$SITE["bannerBottomTxt"]= "./_my_texts/banner.txt";
# 
# Do we strip parts of the page for joomla type use
$SITE["stripAll"]       = false;                # true = Remove all (optional) areas of a page  |  false = No "Joomla" needed. - We use the normal page setup
$SITE["stripMenu"]      = false;                # true = Menu will not be displayed  |  false = Leave menu as is
# 
# Will we display these optional information blocks (and where)
$SITE["langFlags"]      = true;                 # true = Displayed next to the language selection  |  false = Do not display
$SITE["partners"]       = "B";                  # B = In bottom area  |  V = In side area  |  false = Do not display
$SITE["equipment"]      = "B";                  # B = In bottom area  |  V = In side area  |  false = Do not display
$SITE["otherWS"]        = "V";                  # V = In side area  |  false = Do not display
# 
# Facebook - Twitter and other social sites
$SITE["socialSiteSupport"]= "V";                # H = In header small horizontal  |  V = In side area  |  false = Do not display
$SITE["socialSiteKey"]  = "4fd8a66b72fa8566";
$SITE["socialSiteCode"] = "./_widgets/social_buttons.txt";
# 
# Rain and Thunder-warnings
$SITE["showRain"]       = false;                # true = Always  |  false = Do not display  |  optional = Display with rain warning
$SITE["showLightning"]  = false;                # true = Always  |  false = Do not display  |  optional = Display with thunder warning
# 
# Donate button
$SITE["donateButton"]   = false;                # V = In side area  |  false = Do not display
$SITE["donateCode"]     = "./_widgets/donateButton.php";
# 
# Weatherwarnings and how are we going to display them
$SITE["warnings"]       = false;                # true = Use the weatherwarnings for your area  |  false = Do not display
$SITE["warnArea"]       = "BE004";              # 5
$SITE["warningInside"]  = false;                # true = Display the warnings below the header  |  false = Display the warnings above all other information
$SITE["warningGreen"]   = false;                # true = Display a message also when there is no warning  |  false = Display only a message when there are warnings
$SITE["warningsXtnd"]   = false;                # true = Display the whole text of the warning   |  false = Display the main warning text only
$SITE["warningDetail"]  = "3";                  # 1 = 9999
$SITE["warn1Box"]       = false;                # true = All warnings combined into one box  |  false = Every warning in a separate box
$SITE["warnPage"]       = true;                 # true = Use template page with detailed information  |  false = Go to warning site for detailed information
# 
# Widgets and other settings for all pages
$SITE["noChoice"]       = "wsStartPage";        # wsStartPage = Normal dashboard startpage (p=10)  |  gaugePage = Steelseries page  |  wsPrecipRadar = Rain radar page  |  wsForecast = Standard forecast page
# 
# Website trafic
$SITE["statsCode"]      = true;                 # true = Use the external statistics code in the footer  |  false = Do not use
$SITE["statsCodeTxt"]   = "./_widgets/histats.txt";
# 
# Visitors counter
$SITE["showVisitors"]   = true;                 # true = Use your own visitors counter  |  false = Do not use
$SITE["visitorsFile"]   = "wolExported.str";
$SITE["geoKey"]         = "";
# 
# Count all pages visited
$SITE["pages_visited"]  = false;                # true = Enable this page  |  false = Do not display
# 
# Back to top of page button
$SITE["floatTop"]       = true;                 # true = Use the floating go to top marker  |  false = Do not use
$SITE["floatTopTxt"]    = "./_widgets/float_top.php";
$SITE["skipTop"]        = true;                 # true = Go to data-part of page to display more information  |  false = Always display header
# 
# Rotating weather-values display
$SITE["ajaxGizmoShow"]  = true;                 # true = Enable this facility  |  false = Do not display
#---------------------------------------------------------
# Menu items / extra pages to display
#---------------------------------------------------------
# 
# Pages for some european countries only
$SITE["belgium"]        = true;                 # true = Weather-Station is located in Belgium  |  false = Not located in Belgium
$SITE["netherlands"]    = true;                 # true = Weather-Station is located in the Netherlands  |  false = Not located in the Netherlands
# 
# Special pages	for smartphone users
$SITE["useMobile"]      = true;                 # true = Use the mobile pages for "smartphone" users  |  false = Only allow main-site use
# 
# Contact page
$SITE["contactPage"]    = true;                 # true = Enable this page  |  false = Do not display
$SITE["contactName"]    = "your name to sign the emails";
$SITE["contactEmail"]   = "noreply@yourstation.com";
$SITE["contactEmailTo"] = "contact@yourstation.com";
# 
# Do we have one or more webcam images to display.
$SITE["webcam"]         = false;                # true = Yes, we have a webcam we want to show  |  false = Do not display
$SITE["webcamSide"]     = true;                 # V = In side area  |  false = Do not display
$SITE["webcamPage"]     = false;                # true = Enable this page  |  false = Do not display
# 
# What will be displayed during night-time
$SITE["webcamNight"]    = true;                 # true = Use a "It is night" image  |  false = Use uploaded picture as normal
$SITE["webcamImgNight"] = "./img/webcam_night.png";
# 
# Webcam 1
$SITE["webcamName_1"]   = "My webcam";
$SITE["webcamImg_1"]    = "http://wiri.be/image.jpg";
# 
# Another webcam
$SITE["webcam_2"]       = false;                # true = Yes, we have a second webcam we want to show  |  false = Do not display
$SITE["webcamName_2"]   = "My webcam 2";
$SITE["webcamImg_2"]    = "http://wiri.be/image.jpg";
#---------------------------------------------------------
# Extra weather-programs used for this station
#---------------------------------------------------------
# 
# WXSIM weather-forecasting
$SITE["wxsimPage"]      = false;                # true = Yes, we use the WXSIM paid program to make our weather-forecasts  |  false = Do not display
$SITE["wxsimData"]      = "../";
$SITE["wxsim_file"]     = "latest.csv";         # latest.csv = lates.csv file will be used  |  lastret.txt = lastret.txt file will be used
$SITE["wxsimIconsOwn"]  = false;                # false = Use template standard icons  |  true = Use YrNo icons
# 
# Meteoplug: storage and graphs server
$SITE["meteoplug"]      = false;                # false = We do not use Meteoplug  |  true = We use Meteoplug and display the extra pages
# 
# WD-Live: Paid-for website script/program, uses clientraw files to display extensive customizable flash dashboard
$SITE["wd_live"]        = false;                # false = No WD-live user  |  true = We use WD-Live and display the dashboard
# 
# Meteroware-Live: free website script/program, it display a basic flash dashboard
$SITE["meteoware"]      = false;                # false = No interest in that dashboard  |  true = Yes, display the Meteoware dashboard
#---------------------------------------------------------
# Menu items / forecast pages
#---------------------------------------------------------
# 
# Default forecast on the start-page
$SITE["fctOrg"]         = "metno";              # yahoo = Yahoo - needs area code = all  |  wu = WeatherUnderground - needs key = all  |  wxsim = WXSIM needs extra program = all  |  yrno = Yrno needs area code = all  |  metno = MetNo = all  |  yowindow = YoWindow gadget = all  |  noaa = NOAA = america  |  ec = Environment Canada = canada  |  hwa = Het Weer Actueel - alleen voor leden = europe
# 
# Multiple selections on forecast pages
$SITE["multi_forecast"] = true;                 # true = Enable this facility  |  false = Do not display
$SITE["multi_fct_keys"] = "./_my_texts/eu_multi_fct.txt";
# 
# Weather Underground Forecast and Almanac
$SITE["wuPage"]         = false;                # true = Yes, we want this extra forecast  |  false = Do not display
$SITE["wuKey"]          = "196cb91ad1b50c06";
$SITE["wuIconsOwn"]     = false;                # false = Use template standard icons  |  true = Use WU icons
$SITE["wuIconsCache"]   = false;                # false = Use WU icons from uor cache  |  true = Use WU icons directly form their site
# 
# Met.no
$SITE["metnoPage"]      = true;                 # true = Yes, we want this extensive free forecast  |  false = Do not display
$SITE["metnoiconsOwn"]  = false;                # false = Use template standard icons  |  true = Use YrNo icons
# 
# YR.no  - This forecast is required for the "smart-phone / mobile" pages
$SITE["yrnoPage"]       = true;                 # true = Yes, we want this forecast, also for our Mobile pages  |  false = Do not display
$SITE["yrnoID"]         = "Belgium/Brussels/Brussels/";
$SITE["yrnoIconsOwn"]   = false;                # false = Use template standard icons  |  true = Use YrNo icons
# 
# Het Weer Actueel
$SITE["hwaPage"]        = false;                # true = Yes, we are a registred HWA member  |  false = Do not display
$SITE["hwaXmlId"]       = "000";                # 000 = 999
$SITE["hwaXmlKey"]      = "";
$SITE["hwaIconsOwn"]    = false;                # false = Use template standard icons  |  true = Use HWA icons
# 
# Yahoo
$SITE["yahooPage"]      = true;                 # true = Yes, we want this extra forecast  |  false = Do not display
$SITE["yahooIconsOwn"]  = false;                # false = Use template standard icons  |  true = Use Yahoo icons
# 
# World Weather
$SITE["worldPage"]      = false;                # true = Yes, we want this extra forecast  |  false = Do not display
$SITE["worldAPI"]       = "2";                  # 1 = API version 1, only for users who have an old API-1 key  |  2 = Use API version 2, current version
$SITE["worldKey"]       = "";
$SITE["worldKey2"]      = "";
$SITE["worldIconsOwn"]  = false;                # false = Use template standard icons  |  true = Use Worldweather icons
# 
# Refresh-times
$SITE["autoRefresh"]    = "0";                  # 0 = 999
$SITE["wsAjaxDataTime"] = "30";                 # 10 = 999
$SITE["wsSteelTime"]    = "30";                 # 10 = 999
# 
# Miscellaneous
$SITE["charset"]        = "UTF-8";              # UTF-8 = default UTF-8 character set  |  windows-1252 = windows-1252
$SITE["topfolder"]      = "./";
$SITE["password"]       = "";
#---------------------------------------------------------
# Partners
#---------------------------------------------------------
# 
# Your visitors can find your weather data also	at: Weather Underground
$SITE["wuMember"]       = false;                # false = We are not uploading to Weather Underground  |  true = We are a member of Weather Underground
$SITE["wuID"]           = "";
$SITE["wuStart"]        = "dd-mm-yyyy";
# 
# Mesonet
$SITE["mesonetMember"]  = false;                # false = We are not member of a regional Mesonet of weather-Stations  |  true = We are a proud member of a regional Mesonet of weather-Stations
$SITE["mesoID"]         = "";
$SITE["mesoName"]       = "";
$SITE["mesoLink"]       = "";
# 
# Het Weer Actueel
$SITE["hwaMember"]      = false;                # false = We are not uploading to Het Weer Actueel  |  true = We are a member of Het Weer Actueel
$SITE["hwaID"]          = "";
# 
# European Weather Network
$SITE["ewnMember"]      = "";
$SITE["ewnID"]          = "";
#---------------------------------------------------------
# Other organizations one can upload to
#---------------------------------------------------------
# 
# wow.metoffice.gov.uk/
$SITE["wowMember"]      = false;                # false = We are not uploading to wow-metoffice  |  true = We are a member of wow-metoffice
$SITE["wowID"]          = "";
# 
# Awekas
$SITE["awekasMember"]   = false;                # false = We are not uploading to Awekas  |  true = We are a member of Awekas
$SITE["awekasID"]       = "";
# 
# WeatherCloud
$SITE["wcloudMember"]   = false;                # false = We are not uploading to Weather Cloud  |  true = We are a member of Weather Cloud
$SITE["wcloudID"]       = "";
# 
# CWOP
$SITE["cwopMember"]     = false;                # false = We are not uploading to CWOP  |  true = We are a member of CWOP
$SITE["cwopID"]         = "";
# 
# Weatherlink.com
$SITE["wl_comMember"]   = false;                # false = We are not uploading to WeatherLink.com  |  true = We are uploading ouw weather-data to WeatherLink.com
$SITE["weatherlinkID"]  = "";
# 
# And some more
$SITE["anWeMember"]     = false;                # false = We are not uploading to Anything Weather  |  true = We are a member of Anything Weather
$SITE["anWeID"]         = "";
$SITE["pwsMember"]      = false;                # false = We are not uploading to PWS  |  true = We are a member of PWS
$SITE["pwsID"]          = "";
$SITE["wp24ID"]         = false;                # false = We are not uploading to wp24  |  true = We are uploading to wp24
$SITE["uswg"]           = false;                # false = We are not uploading to uswg  |  true = We are uploading to uswg
# 
# cookie support / visitors are allowed to do customization
$SITE["cookieSupport"]  = true;                 # true = Yes, we warn visitors if there adaptions need a cookie  |  false = Do not use customization / cookies
$SITE["userChangeDebug"]= true;                 # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeColors"]= true;                # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeMenu"] = true;                 # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeHeader"]= true;                # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeChoice"]= true;                # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeLang"] = true;                 # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeUOM"]  = true;                 # true = Allow user to change this setting  |  false = Do not use allow this one
$SITE["userChangeForecast"]= true;              # true = Allow user to change this setting  |  false = Do not use allow this one
#---------------------------------------------------------
# Optional components attached to your weatherstation
#---------------------------------------------------------
# 
# UV/ Solar sensors
$SITE["UV"]             = false;                # false = No UV sensor attached  |  true = We have an UV sensor on our station
$SITE["SOLAR"]          = false;                # false = No solar sensor attached  |  true = We have a Solar sensor on our station
# 
# Other often atttached sensors sensors
$SITE["tempInside"]     = true;                 # false = We will NOT use an inside temperature sensor  |  true = We want to display the inside temperature
$SITE["extraTemp1"]     = false;                # false = No need for an extra temperature sensor  |  true = We have an extra sensor on our station
$SITE["extraTemp2"]     = false;                # false = No need for another extra temperature sensor  |  true = We have a second extra sensor on our station
# 
# Soil / moisture sensors
$SITE["soilUsed"]       = false;                # false = There are no soil sensors attached  |  true = We have soil sensors on our station
$SITE["soilPage"]       = false;                # false = Still we do not want a extra soil mositure page  |  true = Display the soil/moisture page
$SITE["soilCount"]      = "2";                  # 0 = 4
$SITE["soilDepth_1"]    = "10";                 # 1 = 100
$SITE["soilDepth_2"]    = "20";                 # 1 = 100
$SITE["soilDepth_3"]    = "30";                 # 1 = 100
$SITE["soilDepth_4"]    = "40";                 # 1 = 100
$SITE["uomMoist"]       = "cb";
$SITE["leafUsed"]       = false;                # false = There are no leaf sensors attached  |  true = We have leaf sensors on our station
$SITE["leafCount"]      = "2";                  # 0 = 4
#---------------------------------------------------------
# Display some extra information about:
#---------------------------------------------------------
# 
# The type of weather-station
$SITE["DavisVP"]        = false;                # false = Our station is not a Davis one  |  true = We use a Davis weather-station station
$SITE["stationShow"]    = false;                # false = We do not show the weatherstation name/picture on our pages  |  true = We want to display the information about the weatherstation
$SITE["stationTxt"]     = "Davis VP2";
$SITE["stationJpg"]     = "img/davis_logo.png";
$SITE["stationLink"]    = "http://www.davisnet.com/weather/products/professional-home-weather-stations.asp";
# 
# The website / provider
$SITE["providerShow"]   = false;                # false = Do not show the name/picture of our privider  |  true = We want to display the information about the provider
$SITE["providerTxt"]    = "My provider";
$SITE["providerJpg"]    = "img/_provider.jpg";
$SITE["providerLink"]   = "http://www.provider.xyz/";
# 
# The computer (device) used
$SITE["pcShow"]         = false;                # false = Do not show the type and name of our weather-computer  |  true = We want to display the information our weather-computer
$SITE["pcTxt"]          = "My Computer";
$SITE["pcJpg"]          = "img/_computer.png";
$SITE["pcLink"]         = "http://www.computer.xyz";
# 
# The Weather-Program
$SITE["WXsoftwareShow"] = false;                # false = No information about our weather-program is needed  |  true = We want to display the information our weather-program
#---------------------------------------------------------
# units, time and date formats and some other settings
#---------------------------------------------------------
# 
# Some other settings first
$SITE["commaDecimal"]   = true;                 # false = Use a decimal point [22.50]  |  true = Use a comma [22,50] as decimal"point"
$SITE["tempSimple"]     = false;                # false = Use multicolored temperatures  |  true = Use blue/red temperatures only
$SITE["textLowerCase"]  = false;                # false = Use all texts as they are  |  true = convert all texts to lowercase
$SITE["nightDayBefore"] = true;                 # false = Dayparts start with Night then morning, afternoon to evening)  |  true = Dayparts start with Morning then Afternoon, Evening to Night
# 
# The units to be used for all weather-values, first one is temperature
$SITE["uomTemp"]        = "&deg;C";             # &deg;C = Celcius  |  &deg;F = Fahrenheit
$SITE["decTemp"]        = "1";                  # 0 = No decimals 14 C  |  1 = One decimal 14.1 C
# 
# For pressure in inches you need one or two decimals
$SITE["uomBaro"]        = " hPa";               #  hPa = hPa  |   mb = milibar  |   inHg = inHg
$SITE["decBaro"]        = "1";                  # 0 = No decimals 1017 hPa  |  1 = One decimal  1017.1 hPa  |  2 = Two decimals 30,23 inHg
# 
# 
$SITE["uomWind"]        = " km/h";              #  km/h =  km/h  |   kts =  kts  |   m/s =  m/s  |   mph =  mph
$SITE["decWind"]        = "1";                  # 0 = No decimals 14 km/h  |  1 = One decimal 3.4 m/s
# 
# 
$SITE["uomRain"]        = " mm";                #  mm =  mm  |   in =  in
$SITE["decPrecip"]      = "1";                  # 0 = No decimals 4 mm  |  1 = One decimal 4.2 mm  |  2 = Two decimals 2.35 in
# 
# 
$SITE["uomSnow"]        = " cm";                #  cm =  cm  |   in =  in
$SITE["decSnow"]        = "0";                  # 0 = No decimals 22 cm  |  1 = One decimal 22.4 cm
# 
# 
$SITE["uomDistance"]    = " km";                #  km =  kilometer  |   mi =  mile
$SITE["decDistance"]    = "0";                  # 0 = No decimals 15 mi  |  1 = One decimal 14.8 mi
# 
# 
$SITE["uomPerHour"]     = " mm";                # /hr =  / hour
$SITE["decPerHour"]     = "1";                  # 0 = No decimals  |  1 = One decimal
# 
# 
$SITE["uomHeight"]      = " ft";                #  ft =  feet  |   m =  meter
$SITE["decHeight"]      = "1";                  # 0 = No decimals  |  1 = One decimal
# 
# How to display the date and time information
$SITE["hourDisplay"]    = "24";                 # 12 = 12 hours  |  24 = 24 hours
$SITE["timeFormat"]     = "d-m-Y H:i";          # M j Y g:i a = Dec 31 2013 2:03 pm  |  d-m-Y H:i = 31-03-2012 14:03
$SITE["timeOnlyFormat"] = "H:i";                # g:i a = 2:03 pm  |  H:i = 14:03
$SITE["hourOnlyFormat"] = "H";                  # ga = 2pm  |  H = 14
$SITE["dateOnlyFormat"] = "d-m-Y";              # M j Y = Dec 31 2013  |  d-m-Y = 31-03-2012
$SITE["dateMDFormat"]   = "d-m";                # M j = Dec 31  |  d-m = 31-03
$SITE["dateLongFormat"] = "l d F Y";            # l M j Y = Friday Jan 22 2015  |  l d F Y = Friday, 5 februari 2013
#---------------------------------------------------------
# All done
#---------------------------------------------------------
#
#---------------------------------------------------------
# COMPATIBILLITY     for WeatherDisplay / consoleWD users
# set to true ONLY if it is ABSOLUTELY  necessary to use testtags.php from Saratoga or Leuven
#---------------------------------------------------------
$SITE["use_testtags"]   = false;  
#
#---------------------------------------------------------
# IMPORTANT     will you be uploading to the default upload folder (uploadXX) where xx is the short code for your weather program
#---------------------------------------------------------
#
$SITE["standard_upload"]= true;
#
#       If you do not want or are not able to upload to the default folder set the correct upload folder here
#     
#$SITE["uploadDir"]	= "../";        	# example for upload to root
#$SITE["clientrawDir"] 	= "../";
#$SITE["graphImageDir"] = "../";
#
#---------------------------------------------------------
$SITE["tpl_version"]    = "2.80";
#---------------------------------------------------------
# If you add an language add the new language code to this array
#---------------------------------------------------------
#
$SITE["installedLanguages"] = array (
"nl" => "Nederlands",
"en" => "English",
"fr" => "Fran&ccedil;ais",
"de" => "Deutsch",
);
