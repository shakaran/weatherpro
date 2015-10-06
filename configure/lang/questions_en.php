<?php
echo '<!-- module questions language  ====  en loaded -->'.PHP_EOL;
$LANGLOOKUP['conf-default_text']='The default value is OK in most cases.<br />Only change this when you are sure.';

$LANGLOOKUP['conf-WXsoftware']	='What weather-program are you using:';
$LANGLOOKUP['conf-region']	='Select the part of the world for the location of your weatherstation:';
$LANGLOOKUP['conf-organ']	='The name of your weather-station without any special characters:';
$LANGLOOKUP['conf-wlink_key']	='As user of Davis Weatherlink.com you have a key to access your data';
$LANGLOOKUP['conf-wlink_pw']	='And a password which you used to set  settings at the weeatherlink.com website.<br />
That password is also used to load your weather-data into the template every 5 minutes';
$LANGLOOKUP['conf-meteoplug_draw']	='This is the key to retrieve the information form Meteoplug.<br />You get this value at step 4 of the installment, leave blank until then';
$LANGLOOKUP['conf-meteoplug_cache']	='Set to the cache time in seconds. Do not set it lower then half the the standard upload time to Meteoplug which is default 10 minutes = 600 seconds.';
$LANGLOOKUP['conf-yourArea']	='A relative short name for the area/region your weather-station is in.<br />It will be to be displayed on every page header and on the forecast pages.';
$LANGLOOKUP['conf-latitude']	='Latitude (and next field longitude) are probably also specified in your weather program and are used here for earthquake reports, astronomy and forecasts.<br />
Example: 50.8500 is for Leuven in Belgium and 41.30068 is for Branford CT, USA. <br />
North of the equator is positive (=no sign).  South of the equator needs a - sign.';
$LANGLOOKUP['conf-longitude']	=' For longitudes left of Greenwich a - sign is needed. <br />
Example: 4.3400 is for Leuven in Belgium and-72.793671 is for Branford CT, USA.';
$LANGLOOKUP['conf-tz']		='Select your time-zone from the drop-down list';

$LANGLOOKUP['conf-WUregion']	='Select your region for the Wunderground regional maps';
$LANGLOOKUP['conf-radarStation']='Use the button below. Default the USA map is shown. For canadian stations, see below. 
Select your area by clicking on the map, do not use the animate button  at this time. 
Example you arrive at page<br />
<a href="http://www.wunderground.com/weather-radar/united-states-regional/nc/charlotte" target="_blank">
<b><small>http://www.wunderground.com/weather-radar/united-states-regional/nc/charlotte</small></b></a><br />
Move to one of the radars (blue round area\'s) example at Jacksonville and a round area with the name of the station (Morehead) and the three letter code (MHX for this example) appears
<br /><br />For Canada you first have to select Canada in the links below "Weather Radar Maps". Then click on the white <b>+</b>-signs to arrive at your WU radar station. The 3-letter code is in the browser address line.';

$LANGLOOKUP['conf-caProvince']	= 'Select your province here.';
$LANGLOOKUP['conf-caCityCode']	= 'Select nearest city. The selection HERE is English names only, but French ones will be used in the scripts when necessary.';

$LANGLOOKUP['conf-caAQHI']	= 'Do you want a air-quility health index displayed. Check in the list below if there is such an index for your area first.';
$LANGLOOKUP['conf-aqhiArea']	= 'Select nearest city. The selection HERE is English names only, but French ones will be used in the scripts when necessary.';

$LANGLOOKUP['conf-lang']	='The default language (which is shown first) for your website.';
$LANGLOOKUP['conf-METAR']	='The "METAR" code can be used to obtain the current 
weather conditions and if otherwise unavailable to obtain almanac data for your area.
<br />Use Google to find the closest METAR to your city.';
$LANGLOOKUP['conf-yaPlaceID']	='The best solution to obtain those current conditions is to use Yahoo. For that you need a Yahoo city ID.<br />
<b>HOW TO</b>: Go to the Yahoo website and do a search for Leuven, 
accept the proposed <b>"Leuven, Vlaams Brabant, BE"</b> and you see the following line in your browser address field:<br />
https://weather.yahoo.com/belgium/vlaams-brabant/leuven-973505/ <br />
The number 973505 is the yahoo place id for Leuven. You can use a similar approach for finding your own ID and you should insert it in the setting.';
$LANGLOOKUP['conf-curCondFrom']	='Select the source to get the current conditions from.';

$LANGLOOKUP['conf-pageWidth']	= 'Page-width: '.$LANGLOOKUP['conf-default_text'];
$LANGLOOKUP['conf-menuWidth']	= 'Menu-width: '.$LANGLOOKUP['conf-default_text'];

$LANGLOOKUP['conf-menuPlace']	='The menu can either be placed  vertical on the left (default) or just below the header.';


$LANGLOOKUP['conf-maintenanceShow']	='The yellow box can be shown or hidden using this setting.';
$LANGLOOKUP['conf-maintenanceTxt']	='<span style="float: left;">FROM THE PROGRAMMER:</span> <br />I use this yellow box also for information about new pages added 
or for a warning that certain pages are temporarily disabled because of problems with a forecast-organization.<br />
Either modify the standard text every time or use multiple text-files.';

$LANGLOOKUP['conf-header']	='The default selection has the weathervalues displayed using the the gauges.<br />
The next choice displays the most important weather values in always visible text-blocks.<br >
The last choice needs the least amount of space as it uses a rotating line to display all values.';
$LANGLOOKUP['conf-colorNumber']='Default the color-style is set to "weather-adapted", 
so that the current weather-conditions are used to set the back-ground picture and the color-scheme.';
$LANGLOOKUP['conf-sideDisplay'] =' If you selected the default vertical menu the 
sideDisplay is always displayed regardless of the setting here.';
$LANGLOOKUP['conf-bottomDisplay']= 'The side-area and bottom-area can be used to display interesting links';

$LANGLOOKUP['conf-bannerTop']		='If you want to put some adds or extra information on the top of the page';
$LANGLOOKUP['conf-bannerTopTxt']	='Use this file or type the location of your own file';
$LANGLOOKUP['conf-bannerBottom']	='If you want to put some adds or extra information at the bottom of the page';
$LANGLOOKUP['conf-bannerBottomTxt']	='Use this file or type the location of your own file';

$LANGLOOKUP['conf-stripAll']='The various pages of the template can be invoked from within the joomla menu which is a standard facility of Joomla. 
With "stripAll" set to true there will be no Header, no Footer and and no Sidearea shown.  The horizontal menu will be used.';
$LANGLOOKUP['conf-stripMenu']='With "stripMenu" set to true, the menu is not shown also.';
$LANGLOOKUP['conf-langFlags']	='Default there are small country-flags displayed next to the language selection.<br />
Disable the display of these small flags here.';
$LANGLOOKUP['conf-partners']	='Partners are organizations you upload your weatherinformation to, for example "WeatherUnderground". 
Here you choose if and where you want to display information about these companies.';
$LANGLOOKUP['conf-equipment']	='Do you want and where do you want to display information about the computer, weatherprogram, webhost you use to run your weather site.';
$LANGLOOKUP['conf-otherWS']	='The link to your previous websites and to your friends websites can be displayed in the side-area.';
$LANGLOOKUP['conf-socialSiteSupport']='You can display these "social-site" buttons in the side-area, (3 large icons) or in the header (an all-in-1 icon). 
The icons and all needed javascript-code are generated using the free "addthis.com" service.';
$LANGLOOKUP['conf-socialSiteKey']='If you want to get a better insight in your visitors you should ask for your own free key';
$LANGLOOKUP['conf-socialSiteCode']=  'Script: '.$LANGLOOKUP['conf-default_text'];

$LANGLOOKUP['conf-showRain']		='Small rain-radar image: The image can be shown always,<br />based on the weather-warnings<br />or never.<br />
When the web-site visitor clicks on the image a larger image on top of the page is displayed.';
$LANGLOOKUP['conf-showLightning']	='Small thunder-radar image';

$LANGLOOKUP['conf-donateButton']='Default there is no donate button shown.<br />
IMPORTANT: You have to generate the Paypal code first.';
$LANGLOOKUP['conf-donateCode']	='Put the link to <b>your</b> payPal donate code here.';


$LANGLOOKUP['conf-warnings']		='Show (optional) meteo warnings on every page.';
$LANGLOOKUP['conf-useCurly']		='For region USA the default warnings scripts uses NOAA as source for "one area"-warnings. 
The users in the USA can choose also the more extensive Curly scripts here. Those scripts  make it possible to display multiple warnings for multiple areas. 
If one choses for the Curly scripts the adaptions are done by modifying the setting scripts in the nws-alerts folder in weather??/usa/';
$LANGLOOKUP['conf-warnArea']		='You find your area code by browsing to your area at the alarm-website. 
<br />Example: http://www.meteoalarm.eu/en_UK/0/0/<b>UK012</b>-East_of_England.html
<br />Here <b>UK012</b> is the code for East England. Another code is <b>BE004</b> for Vlaams Brabant.';
$LANGLOOKUP['conf-warnAreaNoaa']	='The example CT009 = is an area in Connecticut. You can find your area own code at the link below at the Zone List | County List". 
Clicking on either Zone or County list for your state lists all codes you can use here.';
$LANGLOOKUP['conf-warningInside']	='Default the warnings are displayed before everything else on the page. 
If you want the warning inside your page select that here.';
$LANGLOOKUP['conf-warningGreen']	='If you want a display message also when there is no warning, select that here.';
$LANGLOOKUP['conf-warningsXtnd']	='If you want the whole text of the warning displayed on your page, select that here. Be careful, use with care. 
The whole text can be extremely large, for instance do not use it in Belgium or half of your window will be used by the warning.';
$LANGLOOKUP['conf-warningDetail']	='The number of individual warnings before warnings are combined. Because sometimes there are so many weather-warnings that to much screen-space is wasted.';
$LANGLOOKUP['conf-warn1Box']		='To put all separate warnings in one box, again to use less space in the browser window.';
$LANGLOOKUP['conf-warnPage']		='Do you want to use your own page when detailed information is requested or should the visitor go directly to the weather warning site.';

$LANGLOOKUP['conf-noChoice']		='Select the first page your visitors will go to when they visit your website for the first time';
$LANGLOOKUP['conf-statsCode']		='Use a external statistics counter to measure your website trafic.';
$LANGLOOKUP['conf-statsCodeTxt']	='Replace the default counter code with your own code obtained from histats.com.';
$LANGLOOKUP['conf-floatTop']		='Use a "floating" go-to-top marker to quickly go to the top of the page, usefull for long pages.';
$LANGLOOKUP['conf-floatTopTxt']		='Use the template code for this facility or point to your own code.';
$LANGLOOKUP['conf-skipTop']		='Except on the startpage, go directly to the data part of a page to display more information';
$LANGLOOKUP['conf-ajaxGizmoShow']	='Use this facilty to display a rotating line with a different weathervalue on each line';
$LANGLOOKUP['conf-showVisitors']	='Use a visitors counter in the footer and also  a page with visitors of the last 4 days.';
$LANGLOOKUP['conf-geoKey']		= 'If you want to include a flag and extra info about the visitor you need "geolookup". 
This version of the template suppports a paid subscription http://www.ipgp.net as an extension.';
$LANGLOOKUP['conf-visitorsFile']	= 'The IP info about your visitors is kept in this file (in the cache directory), if not present it is created';

$LANGLOOKUP['conf-belgium']		='Some European pages are only valid for this country ';
$LANGLOOKUP['conf-netherlands']		='Some European pages are only valid for this country ';
$LANGLOOKUP['conf-useMobile'] 		= 'There are a few pages with support for phones and other small devices with an internet browser. You can check this page yourself from the menu to see what possibillities are offered here.
When visitors with a tablet browse to your site, they are directed to the normal site. Only small screen smart-phones are directed to the mobile pages.';
$LANGLOOKUP['conf-contactPage'] 	= 'If you leave this setting as is, there will  be a small link in the footer to the contact page and the contact page will be in the menu as a separate choice.';
$LANGLOOKUP['conf-contactName'] 	= 'The name used on the contact page';
$LANGLOOKUP['conf-contactEmail'] 	= 'If you included a contact page  you have to specify correct e-mail addresses here. Use a noreply@ address for the Webmaster E-mail from (which the visitor sees when he asks for a copy of the e-mail). ';
$LANGLOOKUP['conf-contactEmailTo'] 	= 'Use a valid e-mail address to receive the inquiries from the visitor. This  address is not seen by the visitor so robots / hackers can not see them either.';
$LANGLOOKUP['conf-noaaPage'] 		= 'This page is used to display the NOAA forecasts';
$LANGLOOKUP['conf-noaaIconsOwn'] 	= 'Do you want to use the default template icons or the NOAA icons';
$LANGLOOKUP['conf-cwopPage'] 		= 'Displays your CWOP statisatics';
$LANGLOOKUP['conf-ecPage'] 		= 'Use for the Environment Canada forecasts';
$LANGLOOKUP['conf-pages_visited'] 	= 'Optional "debug" type page which counts all visited pages and can display a sortable listing from it.';

$LANGLOOKUP['conf-webcam']		='Only enable this if you have a webcam and webcam pictures to show.';
$LANGLOOKUP['conf-webcamSide']		='A smal webcam picture will be shown in the side-area which is enlarged on top of the page when the users click on it.';
$LANGLOOKUP['conf-webcamPage']		='Do we use a separate page or is it adequate to use a pop-up from the small side-area picture.';
$LANGLOOKUP['conf-webcamNight']		= 'Do we need a "it is night" message-picture or do we display the normal webcam picture';
$LANGLOOKUP['conf-webcamImgNight']	='Set to the location of the night-time replacement picture.';
$LANGLOOKUP['conf-webcamName_1']	='The short name above the webcam image .';
$LANGLOOKUP['conf-webcamImg_1']		='The exact link to the webcam image.';
$LANGLOOKUP['conf-webcam_2']		='Only enable this if you have a second webcam with webcam pictures to show.';
$LANGLOOKUP['conf-webcamName_2']	='The short name above the webcam image .';
$LANGLOOKUP['conf-webcamImg_2']		='The exact link to the webcam image.';

$LANGLOOKUP['conf-wxsimPage'] 		= 'If you bought and use WXSIM this can be set here';
$LANGLOOKUP['conf-wxsimData'] 		= 'The location of the uploaded WXSIM files is often the root, that is set as the default value.<br />
If the files are in another folder, specify the path relative to the weather??/ folder.';
$LANGLOOKUP['conf-wxsim_file'] 		= 'There are three files available by WXSIM of which two are used. Default the most extensive file is used. 
If there are errors in the script or in a file, one can switch between the files here.';
$LANGLOOKUP['conf-wxsimIconsOwn'] 	= 'The template uses the same icons for all forecasts. But if you prefer to use the icons from the forecasters themselves you can set that for every separate forecaster. 
<br />There are no "own icons" for WXSIM but often the YrNo icons are used as a replacement.';
$LANGLOOKUP['conf-meteoplug']		= 'Do you have a subscription to the paid graphing services for Metohub or Meteobridge.<br />
Or do you use Meteoplug as your "weatherprogram".';
$LANGLOOKUP['conf-wd_live']		= 'Do you run WeatherDisplay-Live on your webserver.<br />This FLASH program needs clientraw* files and it is a paid-for program.';
$LANGLOOKUP['conf-meteoware']		= 'Meteoware is a free FLASH program which displays a realtime dashboard.';



$LANGLOOKUP['conf-fctOrg'] 		= 'Here you select the default forecast which will be avilable on the </b>start-page</b>.';
$LANGLOOKUP['conf-multi_forecast'] 	= 'Do you want a dropdown with the names of "nearby" cities on top of every forecast?<br />Visitors can then not only choose your own city but also some other ones.';
$LANGLOOKUP['conf-multi_fct_keys'] 	= 'To use this drop-down selection a file with the different city-names and program-keys is present in your "_my_texts/" folder. If you stored this file on another location you can set that location here. ';
#$LANGLOOKUP['conf-select_ec'] 		= 'To use this drop-down selection with EC-scripts a file with the different city-names and program-keys is present in your "_my_texts/" folder. If you stored this file on another location you can set that location here. ';

$LANGLOOKUP['conf-wuPage'] 		= 'Do you want a weather-forecast from Weather-Underground?<br />Be aware that there are often translation problems with langauges other then English.';
$LANGLOOKUP['conf-wuKey'] 		= 'If you want to use the one page WU forecast (and the almanac data on the dashboard) you have to request a free key and modify this setting.';
$LANGLOOKUP['conf-wuIconsOwn'] 		= 'The template uses the same icons for all forecasts.<br />But if you prefer the WU icons to be used in the WU forecast you can set that here.';
$LANGLOOKUP['conf-wuIconsCache'] 	= 'You can than choose to use cached WU icons or retrieve them from the WU site.';

$LANGLOOKUP['conf-metnoPage'] 		= 'This extensive forecast form the Norwegion Weather Service is the most often used one. <br />
They supply a forecast for any place in the world and that without a key or a special place-id. <br />
Only your latitude/longitude are used to obtain this extensive forecast.';
$LANGLOOKUP['conf-metnoiconsOwn'] 		= 'Do you want to use the default template icons or the Yr.No (as there are no Met.no) icons.';

$LANGLOOKUP['conf-yrnoPage'] 		=  'Another Norwegian forecaster which generates also a extensive forecast but also supplies a meteogram in png format.
When you are using the "Smart-phone / Mobile"  pages you have to correctly specify the yrNo settings as this forecast is used on those pages.';
$LANGLOOKUP['conf-yrnoID'] 		= 'To find the correct Country/Region[/sub-region]/City code use the link and locate the nearest city to your place. 
In the browser address area you find the correct code. Example http://www.yr.no/place/<b>Belgium/Flanders/Leuven/</b>';
$LANGLOOKUP['conf-yrnoIconsOwn'] 	= 'Do you want to use the default template icons or the Yr.No icons.';
$LANGLOOKUP['conf-select_yrno'] 	= 'To use the drop-down selection you need a small file with the different names and keys. It is present in your "_my_texts/" folder. If you stored this file on another location you can set that location here.';
$LANGLOOKUP['conf-hwaPage'] 		= 'The "Het Weer Actueel" weatherstations have their own forecast data. You have to be a member and registred correctly to have an ID and a key.';
$LANGLOOKUP['conf-hwaXmlId'] 		= 'You retrieve your <b/>hwaXmlId</b> at the management page for your weather-station at the HWA site.';
$LANGLOOKUP['conf-hwaXmlKey'] 		= 'Same for the <b>hwaXmlKey</b>';
$LANGLOOKUP['conf-hwaIconsOwn'] 	= 'Do you want to use the default template icons or the HWA icons?';

$LANGLOOKUP['conf-yahooPage'] 		= 'As Yahoo can also supply the current weather conditions and seems to have a reliable 24/7 website, this 5-day forecast is often used on the startpage.';
$LANGLOOKUP['conf-yahooIconsOwn'] 	= 'Do you want to use the default template icons or the Yahoo icons?';

$LANGLOOKUP['conf-worldPage'] 		= 'This small worldwide forecast needs a free key.';
$LANGLOOKUP['conf-worldAPI'] 		= 'Adjust according the type of key you have / reqquested.';
$LANGLOOKUP['conf-worldKey'] 		= 'You have to get a free key from their site. 
The forecast is reterieved from latitude/longitude settings so an ID is not needed';
$LANGLOOKUP['conf-worldKey2'] 		= 'If you currently request an API-key you get a type-2 key. you set that here. If you already have an older type-1 key use the other setting.';
$LANGLOOKUP['conf-worldIconsOwn'] 	= 'Do you want to use the default template icons or the WorldWeather icons?';

$LANGLOOKUP['conf-autoRefresh'] 	= 'The page is automatically refreshed after ther number of seconds you set here.<br />Leave at a zero to disable this mostly annoying feature.';
$LANGLOOKUP['conf-wsAjaxDataTime'] 	= 'Time between request for fresh data on Ajax based pages (Dashboard) in seconds.
<br />Minimum wait time is 10 seconds. 
<br />Do not set this time much shorter then the upload of your realtime-data files.';
$LANGLOOKUP['conf-wsSteelTime'] 	= 'Same type of setting, but now for the Steelseries pages. 
<br />Normaly set the same as for the Ajax updates.';
$LANGLOOKUP['conf-charset'] 		= '<b>The template is fully HTML-5 / UTF-8.</b>  
<br />Individual pages who need a different character-set can use a page-setting in the menu file. 
<br />If for a very rare reason you want to use another character set, you can do that here.';
$LANGLOOKUP['conf-topfolder'] 		= 'Set this to the exact folder the template is in. If you installed in the root, it is "./". If you installed in the default weather?? folder it is example "weather28/" ';
$LANGLOOKUP['conf-password'] 		= 'After you are fully finished testing, you can set a password to make it more difficult to list your settings-files.
But wait until you know for sure that support is not necessary anymore. 
It is impossible to assist with problems caused by errors in the settings when they are password protected.';

$LANGLOOKUP['conf-wuMember'] 		= 'Are you a member of WeatherUnderground, aka uploading your data to them.';
$LANGLOOKUP['conf-wuID'] 		= 'The ID of your personal weather station at the Wunderground site, example IVLAAMSG47.';
$LANGLOOKUP['conf-wuStart'] 		= 'Stations first day of operation at WeatherUnderground.<br >Format dd-mm-yyyy needed for all wu template pages, example 15-03-2013.';
$LANGLOOKUP['conf-mesonetMember'] 	= 'Are you a member of a "Regional Weather Network". Examples: the BNLWN = Benelux Weather Network, or the AKWN = Alaska Weather Netwerk. All their names end on WN.';
$LANGLOOKUP['conf-mesoID'] 		= 'The ID / abbreviation of that network, example AKWN.';
$LANGLOOKUP['conf-mesoName'] 		= 'The name to use on your site for that network, example:  Alaska Weather Network';
$LANGLOOKUP['conf-mesoLink'] 		= 'The link / URL to their main website: http://alaskanweather.net/';
$LANGLOOKUP['conf-hwaMember'] 		= 'Are you a member of HetWeerActueel, aka are they using  your weatherdata.';
$LANGLOOKUP['conf-hwaID'] 		= 'The ID to acces your status-page on the HWA site, example: herent';
$LANGLOOKUP['conf-ewnMember'] 		= 'Are you a member of the European Weather Network, aka are they using  your weatherdata.';

$LANGLOOKUP['conf-wowMember'] 		= 'Are you a member of wow/metoffice, aka are they using your weatherdata.';
$LANGLOOKUP['conf-wowID'] 		= 'The ID to acces your status-page on the WOW site, example: 123456789';

$LANGLOOKUP['conf-awekasMember'] 	= 'Are you a member of Awekas, aka uploading your data to them.';
$LANGLOOKUP['conf-awekasID'] 		= 'The ID to acces your status-page on the Awekas site, example: 8506';
$LANGLOOKUP['conf-wcloudMember'] 	= 'Are you a member of WeatherCloud.net, aka are they using  your weatherdata.';
$LANGLOOKUP['conf-wcloudID'] 		= 'The ID to acces your own information on WeatherCloud.net, example: d1686817653.';
$LANGLOOKUP['conf-cwopMember'] 		= 'cwopMember';
$LANGLOOKUP['conf-cwopID'] 		= 'The ID to acces your information-page on the CWOP site, example: C6440.<br />
Important: to access your data for the template pages the W should be removed from the ID.<br />
Although this stations ID is CW6440 in  Branford-CT, the pages are accessed without the W, so the ID would be C6440.';
$LANGLOOKUP['conf-wl_comMember'] 	= 'If you have a Davis IP-logger or use the WeatherLink program, you can upload your data to WeatherLink.com.';
$LANGLOOKUP['conf-weatherlinkID'] 	= 'The ID used to access your 2 pages at the WL.com website. example: wvdkuil.';
$LANGLOOKUP['conf-anWeMember'] 		= '<b>Anything-Weather</b> website';
$LANGLOOKUP['conf-anWeID'] 		= 'The ID to access your information at their website.';
$LANGLOOKUP['conf-pwsMember'] 		= '<b>pwsweather</b> website';
$LANGLOOKUP['conf-pwsID'] 		= 'The ID to access your information at their website.';
$LANGLOOKUP['conf-wp24ID'] 		= '<b>wetterpage24</b> website.';
$LANGLOOKUP['conf-uswg'] 		= '<b>US weather Group</b> website.';



$LANGLOOKUP['conf-cookieSupport'] 	= 'There is a separate "customize" page in the menu where the visitor can change the color scheme, the default forecaster and all units used. 
<br />At least if you allow here your visitors to do that.';
$LANGLOOKUP['conf-userChangeDebug'] 	= 'Allow a visitor (and support, and you yourself) to use debug=Y on the URL';
$LANGLOOKUP['conf-userChangeColors'] 	= 'Allow user to select website colors / back-ground photos according "colorStyles" setting.';
$LANGLOOKUP['conf-userChangeMenu'] 	= 'Allow user to choose Vertical or Horizontal menus';
$LANGLOOKUP['conf-userChangeHeader'] 	= 'Allow user to select between available  headers';
$LANGLOOKUP['conf-userChangeChoice'] 	= 'Allow user to choose which page will to displayed on next visit';
$LANGLOOKUP['conf-userChangeLang'] 	= 'Disable / enable language support. Leave on if you want multiple languages on your site.';
$LANGLOOKUP['conf-userChangeUOM'] 	= 'Allow user to select their own units of measurement';
$LANGLOOKUP['conf-userChangeForecast'] 	= 'Allow user to select between different forecast for the startpage ';

$LANGLOOKUP['conf-UV'] 			= 'Does your station has an UV sensor and is it supported by your weather-program?';
$LANGLOOKUP['conf-SOLAR'] 		= 'Same question for a solar sensor.';
$LANGLOOKUP['conf-tempInside'] 		= 'Basically these other settings are used for inside temperature, a glass-house or the pool-temperature.';
$LANGLOOKUP['conf-extraTemp1'] 		= 'But they can be used for any temperature sensor you want to use. 
When used on the default locations (start-page next to the thermometer image) you can give them any name you want.';
$LANGLOOKUP['conf-extraTemp2'] 		= 'If you have a temperature sensor in your barn and you want to check it on the startpage, 
you can use one of the two sensors and change the language translation files to the correct name to be displayed.';

$LANGLOOKUP['conf-soilUsed'] 	= 'Some weather-stations and some weather-programs support the attached soil-sensors.';
$LANGLOOKUP['conf-soilPage'] 	= 'There is a separate page with soil moisture and soil temperature information.';
$LANGLOOKUP['conf-soilCount'] 	= 'The number (1-4) of soil sensors attached to your station.';
$LANGLOOKUP['conf-soilDepth_1'] = 'The depth of the first attached soilsensor.<br />All depths in the same unit as you specified for snow (cm or inch).';
$LANGLOOKUP['conf-soilDepth_2'] = 'Depth second sensor.';
$LANGLOOKUP['conf-soilDepth_3'] = 'Depth third sensor.';
$LANGLOOKUP['conf-soilDepth_4'] = 'Depth fourth sensor.';
$LANGLOOKUP['conf-uomMoist'] 	= 'Unit of measure for moisture in soil: cb (default) or Kpa. ';
$LANGLOOKUP['conf-leafUsed'] 	= 'Are there leaf sensors attached?';
$LANGLOOKUP['conf-leafCount'] 	= 'Number of leaf sensors.';

$LANGLOOKUP['conf-DavisVP'] 		= 'Are you using a Davis weather-station and is that station able to deliver the Davis forecast texts?
All weather-programs, DW (Davis Weatherlink.com) excluded, will use the forecast texts.';
$LANGLOOKUP['conf-stationShow'] 	= 'In the side- or bottom-area you can display an icon of your weather station and a link.<br />
You have to supply some information about your weather-station.<br />Default settings are for a Davis station.';
$LANGLOOKUP['conf-stationTxt'] 		= 'Brand / type of your station.';
$LANGLOOKUP['conf-stationJpg'] 		= 'The location (or URL) of an image.';
$LANGLOOKUP['conf-stationLink'] 	= 'The URL to the manuafacturers website.';

$LANGLOOKUP['conf-providerShow'] 	= 'Similar to the information about the brand of our weather-station you can decide if you want to display information about the provider for your website.';
$LANGLOOKUP['conf-providerTxt'] 	= 'The name of your provider.';
$LANGLOOKUP['conf-providerJpg'] 	= 'The link to an image of the provider.';
$LANGLOOKUP['conf-providerLink'] 	= 'The URL to their main site.';
$LANGLOOKUP['conf-pcShow'] 		= 'Similar to the information about the brand of your weather-station you can decide if you want to display information about the computer you run your weather-program on.';
$LANGLOOKUP['conf-pcTxt'] 		= 'The brand of computer or device you are using.';
$LANGLOOKUP['conf-pcJpg'] 		= 'The link to an image.';
$LANGLOOKUP['conf-pcLink'] 		= 'The URL to a relavant website.';
$LANGLOOKUP['conf-WXsoftwareShow'] 	= 'The  information and links for your weather-program are already in the scripts ';

$LANGLOOKUP['conf-commaDecimal'] 	= 'Most Europeans use a comma for a decimal point.  Most Americans a point.';
$LANGLOOKUP['conf-tempSimple'] 		= 'Either you use a 2color-tone for the temperature where blue = below freezing, red = above.<br />Or a long range of colors from light blue to deepest dark red.';
$LANGLOOKUP['conf-textLowerCase'] 	= 'All text can de displayed as lowercase with this setting.';
$LANGLOOKUP['conf-nightDayBefore'] 	= 'Leave as is, only change if the time frames of a forecast do not match your regional wording for this.';
$LANGLOOKUP['conf-uomTemp'] 		= 'The UOM\'s are set according to your region. You can change them here to adapt to local usage.';
$LANGLOOKUP['conf-decTemp'] 		= 'You also set the number of decimals to be shown for each UOM.';
$LANGLOOKUP['conf-uomBaro'] 		= 'Pressure';
$LANGLOOKUP['conf-decBaro'] 		= '';
$LANGLOOKUP['conf-uomWind'] 		= 'Windspeed';
$LANGLOOKUP['conf-decWind'] 		= '';
$LANGLOOKUP['conf-uomRain'] 		= 'Rain/melt';
$LANGLOOKUP['conf-decPrecip'] 		= '';
$LANGLOOKUP['conf-uomSnow'] 		= 'Snow-depth';
$LANGLOOKUP['conf-decSnow'] 		= '';
$LANGLOOKUP['conf-uomDistance'] 	= 'Distance';
$LANGLOOKUP['conf-decDistance'] 	= '';
$LANGLOOKUP['conf-uomPerHour'] 		= '/ hour';
$LANGLOOKUP['conf-decPerHour'] 		= '';
$LANGLOOKUP['conf-uomHeight'] 		= 'Height';
$LANGLOOKUP['conf-decHeight'] 		= '';
$LANGLOOKUP['conf-hourDisplay'] 	= 'Use either 24 hour or 12 hour display.';
$LANGLOOKUP['conf-timeFormat'] 		= 'How to display the date - time.';
$LANGLOOKUP['conf-timeOnlyFormat'] 	= 'How to display time only.';
$LANGLOOKUP['conf-hourOnlyFormat'] 	= 'How to display the hour only.';
$LANGLOOKUP['conf-dateOnlyFormat'] 	= 'How to display date only.';
$LANGLOOKUP['conf-dateMDFormat'] 	= 'How to display month plus day.';
$LANGLOOKUP['conf-dateLongFormat'] 	= 'How to display the descriptive month day time';
$LANGLOOKUP['conf-my_date_format'] 	= 'Select how YOUR weather-program uploads the date information by selecting a "standard" format here.<br />If your program uses a not listed format you should choose "not in this list" and define the format-parts in the next 4 lines.';
$LANGLOOKUP['conf-my_char_sep'] 	= 'What separator is used between the different parts?';
$LANGLOOKUP['conf-my_day'] 		= 'The day part is either the first, second or third part of the date string.';
$LANGLOOKUP['conf-my_month'] 		= 'Same for the month part.';
$LANGLOOKUP['conf-my_year'] 		= 'Same for the year part.';


$LINKLOOKUP['link-latitude'] 	= 'http://itouchmap.com/latlong.html';
$LANGLOOKUP['link-latitude']	= 'This a zoomable map to find your lat/long';

$LINKLOOKUP['link-longitude']	= 'http://www.gpscoordinaten.nl/converteer-gps-coordinaten.php';
$LANGLOOKUP['link-longitude']	= 'You can convert from HH/MM/SS notation to decimal notation here';

$LINKLOOKUP['link-radarStation']= 'http://www.wunderground.com/weather-radar/united-states/';
$LANGLOOKUP['link-radarStation']= 'Find your station here';

$LINKLOOKUP['link-METAR']	= 'http://weather.rap.ucar.edu/surface/stations.txt';
$LANGLOOKUP['link-METAR']	= 'A text file with "all" metar codes can be found here';

$LINKLOOKUP['link-METAR']	= 'http://www.travelmath.com/nearest-airport/';
$LANGLOOKUP['link-METAR']	= 'This site can locate those airports. The 4 letter code is what you need.';

$LINKLOOKUP['link-yaPlaceID']	= 'https://weather.yahoo.com/';
$LANGLOOKUP['link-yaPlaceID']	= 'Find your Yahoo City ID here';

$LINKLOOKUP['link-colorNumber'] = './/testMood.php';
$LANGLOOKUP['link-colorNumber']	= 'Display all different color-schemes available';

$LINKLOOKUP['link-socialSiteKey']= 'http://www.addthis.com/';
$LANGLOOKUP['link-socialSiteKey']= 'Go the AddThis website to obtain your free key';

$LINKLOOKUP['link-donateButton']= 'https://developer.paypal.com/docs/integration/web/';
$LANGLOOKUP['link-donateButton']= 'Paypal stepts to add a donate button';

$LINKLOOKUP['link-warnArea'] 	= 'http://www.meteoalarm.eu/';
$LANGLOOKUP['link-warnArea']	= 'Go to the Meteoalarm site to find your area code';

$LINKLOOKUP['link-warnAreaNoaa']= 'http://alerts.weather.gov/#a';
$LANGLOOKUP['link-warnAreaNoaa']= 'NOAA - NWS county / zone list';

$LINKLOOKUP['link-wuKey'] 	= 'http://www.wunderground.com/weather/api/ ';
$LANGLOOKUP['link-wuKey']	= 'Go to the WU site to request your own free API key';

$LINKLOOKUP['link-yrnoID'] 	= 'http://www.yr.no/';
$LANGLOOKUP['link-yrnoID']	= 'Go to the YrNo site to find the correct ID for your area(s)';

$LINKLOOKUP['link-worldAPI'] 	= 'http://developer.worldweatheronline.com/';
$LANGLOOKUP['link-worldAPI']	= 'Go to the WorldWeather site to request your own free API key';

$LINKLOOKUP['link-geoKey'] 	= 'http://www.ipgp.net';
$LANGLOOKUP['link-geoKey']	= 'Here you can buy your "geo" look-up API key';

$LINKLOOKUP['link-cwopId'] 	= 'http://weather-template.nl/weather27_america/index.php?p=82&s=cwop&lang=en#data-area';
$LANGLOOKUP['link-cwopId']	= 'An example of how the page will look for <b>North-American</b> users only.';
