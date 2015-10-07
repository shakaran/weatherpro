<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'links.php';
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.00 2014-09-19';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------
# 3.00 2014-09-19 release version
# ----------------------------------------------------------------------
# Links to interesting web resources
#-----------------------------------------------------------------------
# If you want to add a groud of interesting links add a chapter description first
#-----------------------------------------------------------------------
$chapters['forums']     = 'Weather forums';
$chapters['pws']        = 'Personal Weather Station Networks';
$chapters['education']  = 'Weather Education';
$chapters['software']   = 'Weather Station Software';
$chapters['scripts']    = 'Weather Website PHP Scripts';
#$chapters['xx']     = 'yy';
#
#-----------------------------------------------------------------------
# The information for each line in a chapter is build as follows
#
#  website  |  language or machine | description   | forum link
#
#  all parts except description are optional
#-----------------------------------------------------------------------
$links['forums'][]      = '|nl  |Vereniging Weerkunde en Klimatologie           |http://forum.vwkweb.nl/';
$links['forums'][]      = '|nl  |Het Weer Actueel                               |http://www.hetweeractueel.nl/forum/?func=listcat';
$links['forums'][]      = '|en  |WX-forum - The Independent Weather Enthusiasts |http://www.wxforum.net';
$links['forums'][]      = '|en  |Weather Watch - Weather Display Support        |http://www.weather-watch.com/smf/    ';
$links['forums'][]      = '|en  |Meteohub - Meteobridge support                 |http://forum.meteohub.de/';
#$links['forums'][]     ='|en     |xx';

$links['pws'][] = 'http://www.beneluxweather.net/       |nl fr en       |Benelux Weather Network';
$links['pws'][] = 'http://www.wunderground.com/         |nl fr de en    |Weather Underground Personal Weather Stations';
$links['pws'][] = 'http://www.hetweeractueel.nl/meedoen |nl             |Het Weer Actueel - Meedoen?';
$links['pws'][] = 'http://www.awekas.at/nl/benutzer.php?mode=new|nl fr de en     |AWEKAS Signup Page';
$links['pws'][] = 'http://wow.metoffice.gov.uk/    |en     |UK Met Office - Weather observations Website ';
$links['pws'][] = 'http://www.hamweather.net/weatherstations/    |en     |WeatherForYou Signup Page';
$links['pws'][] = 'http://www.anythingweather.com/contactjoinnetwork.aspx    |en     |AnythingWeather Signup Page';
$links['pws'][] = 'http://www.wxqa.com/    |en     |Citizen Weather Observer Program';
#$links['pws'][]= 'xx    |en     |xx';

$links['education'][]   = 'http://www.keesfloor.nl/weerkunde/index.htm  |nl     |On-line boek - Weerkunde voor iedereen';
$links['education'][]   = 'http://zakelijk.meteovista.nl/cursussen      |nl     |Cursussen Weerkunde';
$links['education'][]   = 'http://www.belgocontrol.be/belgoweb/publishing.nsf/Content/Meteo_Training    |en nl fr     |Meteo Training';
$links['education'][]   = 'http://amsglossary.allenpress.com/glossary   |en     |Glossary of Meteorology';
$links['education'][]   = 'http://www.ofcm.gov/fmh-1/fmh1.htm           |en     |Federal Meteorological Handbook No. 1';
$links['education'][]   = 'http://science-edu.larc.nasa.gov/SCOOL/tutorial/clouds/cloudtypes.html    |en     |S&quot;COOL Cloud Types Audio/Visual Tutorial';
#$links['education'][]   = 'xx    |en     |xx';

$links['software'][]    = 'http://www.weather-display.com/                      |Mac Win Linux  |Weather-Display &amp; consoleWD|http://www.weather-watch.com/smf/|';
$links['software'][]    = 'http://www.tee-boy.com/weathersnoop                  |Mac            |Weather Snoop                  |http://www.tee-boy.com/forums/ ';
$links['software'][]    = 'http://sandaysoft.com/                               |Win            |Cumulus                        |http://sandaysoft.com/forum/ ';
$links['software'][]    = 'http://wiki.meteohub.de/Main_Page                    |Linux          |Meteohub                       |http://forum.meteohub.de/ ';
$links['software'][]    = 'http://meteobridge.com/wiki/index.php/Main_Page      |own            |Meteobridge                    |http://forum.meteohub.de/ ';
$links['software'][]    = 'http://trixology.com/weathercat/                     |Mac            |WeatherCat                     |http://athena.trixology.com/ ';
$links['software'][]    = 'http://www.afterten.com/products/weathertracker/     |MAC Win        |Weather tracker                |http://www.afterten.com/support/ ';
#$links['software'][]    = 'xx    |en     |xx    |xx ';

$links['scripts'][]    = 'http://leuven-template.eu/?lang=en            ||Leuven-template       |';
$links['scripts'][]    = 'http://saratoga-weather.org/scripts.php       ||Saratoga-Weather.org  |';
$links['scripts'][]    = 'http://www.carterlake.org/weatherphp.php      ||Carterlake.org        |';
$links['scripts'][]    = 'http://www.jcweather.us/scripts.php           ||Jcweather.us          |';
$links['scripts'][]    = 'http://www.642weather.com/weather/scripts.php ||Long Beach Weather    |';
$links['scripts'][]    = 'http://www.tnetweather.com/scripts.php        ||TNETWeather.com       |';
#$links['scripts'][]    = 'xx    ||xx    |';
#
#-----------------------------------------------------------------------
$text_website   = langtransstr('Website');
$text_forum     = langtransstr('Forum');

echo '<div class="blockDiv" style="text-align: center;">'.PHP_EOL;
foreach ($chapters as $key => $description) {
        echo
'<h3 class="blockHead">'.langtransstr($chapters[$key]).'</h3>
<br />'.PHP_EOL;
        foreach ($links[$key] as $key2 => $info) {
                if (trim($info == '') ) {continue;}
                list ($url, $language, $description, $forum) = explode ( '|', $info.'||');
                echo''.trim($description).'&nbsp;&nbsp;';
                if (trim($language) <> ''  ) { echo '('.trim($language).')&nbsp;&nbsp;'.PHP_EOL;}
                if (trim($url) <> ''  ) { echo '<a href="'.trim($url).'"   title="'.trim($description).'" target="_blank">'.$text_website.'</a>&nbsp;&nbsp;'.PHP_EOL;}
                if (trim($forum) <> '') { echo '<a href="'.trim($forum).'" title="'.$text_forum.       '" target="_blank">'.$text_forum.'</a>'.PHP_EOL;}
                echo '<br /> '.PHP_EOL;
        }
        echo '<br />';
}
?>
</div>