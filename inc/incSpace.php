<?php
#-----------------------------------------------------------------------
# display source of script if requested so
#-----------------------------------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header("Content-type: text/plain");
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;
}
$pageName	= 'incSpace.php';		
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$pageVersion	= '3.02 2015-04-05';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-----------------------------------------------------------------------------------------
# 3.02 2015-04-05 release 2.7 version - validotor OK - added extra link
#-----------------------------------------------------------------------------------------
# Settings:
#-----------------------------------------------------------------------------------------
$cache_dir              = './';
$cache_time             = 1800;         // genreal cache time / allowed age 
$space_folder           = './space/';
$space_name_start       = str_replace('.php','',$pageName);
$use_tabs               = true;
$tab_string             = '<div class="tabber" style="width: 99%; margin: 10px auto;">';
#-----------------------------------------------------------------------------------------
#
# check if we are in a template and get some values from there
if (isset ($SITE['cacheDir']) ) {$cache_dir     = $SITE['cacheDir']; }
#-----------------------------------------------------------------------------------------
#  3 day forecast text
#-----------------------------------------------------------------------------------------
$url_forecast           = 'http://services.swpc.noaa.gov/text/3-day-forecast.txt';
$cache_fct_time         = 12*60*60 ; // alowed age of forecast text in seconds
$cache_fct_name         = $cache_dir.$space_name_start.'_3-day-forecast.txt';
$load_file              = true;
$result                 = space_load_from_cache($cache_fct_name, $cache_fct_time,$forecast );
if (!$result) {         // no cache file found
        $result         = space_load_from_url($cache_fct_name, $url_forecast,$forecast);
        if ($result) {    space_save_to_cache ($forecast,$cache_fct_name );}
}

if ($use_tabs) {
        $block_end      = '</div>';
        $block_start    = '<div class="tabbertab " style="padding: 0;">';}
else  { $block_end      =  $block_start    = '';}

echo '<div class="blockDiv" style="text-align: center;">
<h3 class="blockHead">Space Weather Observations, Alerts, and Forecast</h3>'.PHP_EOL;

if ($use_tabs) {echo $tab_string;}

echo $block_start.'
  <h3 class="blockHead">'.langtransstr('Forecast text').'</h3>        
  <div style="width: 90%; margin: 10px auto;">
    <pre style="text-align: center;">'.$forecast.'</pre> 
  </div>'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- Real Time Images of the Sun
#-----------------------------------------------------------------------------------------
#
$url_img        = 'http://sohowww.nascom.nasa.gov/data/realtime/';
$link_url       = 'http://sohowww.nascom.nasa.gov/data/realtime/image-description.html';
$link_string    =  '<a target="blank" style="color: white;" href="'.$link_url.'" ><img src="./img/i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;" /> for more information</a>';
echo $block_start.'

<h3 class="blockHead">Sun Images</h3>
<br />
<table class="genericTable">
<tbody>
';
$head   = array();
$head[] = 'eit 171';
$head[] = 'eit 195';
$head[] = 'eit 284';
$head[] = 'eit 304';
$img    = array();
$img[]  = 'eit_171/512/';
$img[]  = 'eit_195/512/';
$img[]  = 'eit_284/512/';
$img[]  = 'eit_304/512/';
$srch   = array();
$srch[] = 'EIT:wavelength=171';
$srch[] = 'EIT:wavelength=195';
$srch[] = 'EIT:wavelength=284';
$srch[] = 'EIT:wavelength=304';

$string1= '<tr>'.PHP_EOL;
$string2= '<tr>'.PHP_EOL;
$string3= '<tr>'.PHP_EOL;

for ($i = 0; $i < count($img); $i++) {
        $string1 .=  '<td class="blockHead" style="width: 25%; text-align: center;">'.$head[$i].'</td>'.PHP_EOL;  
        $string2 .=  '<td style="text-align: center; "><a href="'.$url_img.$img[$i].'latest.jpg" rel="lightbox" title="Click image to enlarge">
<img width="144" height="144" src="'.$url_img.$img[$i].'latest.jpg" alt=""></a></td>'.PHP_EOL;  
        $string3 .=  '<td style="text-align: center; ">
<a target="_blank" href="http://sohodata.nascom.nasa.gov/cgi-bin/data_query_search_url?Session=web&amp;Resolution=2&amp;Display=Images&amp;NumImg=30&amp;Types=instrument='.$srch[$i].'">More 512×512</a></td>'.PHP_EOL;  
}
$string1.= '</tr><tr style="height: 4px;"><td colspan="4">&nbsp;</td></tr>'.PHP_EOL;
$string2.= '</tr>'.PHP_EOL;
$string3.= '</tr>'.PHP_EOL;

echo $string1.$string2; 
#echo $string3;
echo '<tr><td colspan="4"><p>Images: From left to right: EIT 171, EIT 195, EIT 284, EIT 304 EIT (Extreme ultraviolet Imaging Telescope) 
images the solar atmosphere at several wavelengths, and therefore, shows solar material at different temperatures. 
In the images taken at 304 Angstrom the bright material is at 60,000 to 80,000 degrees Kelvin. 
In those taken at 171 Angstrom, at 1 million degrees. 195 Angstrom images correspond to about 1.5 million Kelvin, 284 Angstrom to 2 million degrees. 
The hotter the temperature, the higher you look in the solar atmosphere.
</p></td></tr>

<tr><td colspan="4">&nbsp;</td></tr>';
$head   = array();
$head[] = 'SDO/HMI<br />Continuum';
$head[] = 'SDO/HMI<br />Magnetogram';
$head[] = 'LASCO C2';
$head[] = 'LASCO C3';

$img    = array();
$img[]  = 'hmi_igr/512/';
$img[]  = '/hmi_mag/512/';
$img[]  = 'c2/512/';
$img[]  = 'c3/512/';
$srch   = array();
$srch[] = 'HMI:obs_type=Continuum';
$srch[] = 'HMI:obs_type=Magnetogram';
$srch[] = 'LASCO:detector=C2';
$srch[] = 'LASCO:detector=C3';

$string1= '<tr>'.PHP_EOL;
$string2= '<tr>'.PHP_EOL;
$string3= '<tr>'.PHP_EOL;
for ($i = 0; $i < count($img); $i++) {
        $string1 .=  '<td style="text-align: center; " class="blockHead">'.$head[$i].'</td>'.PHP_EOL;  
        $string2 .=  '<td style="text-align: center; "><a href="'.$url_img.$img[$i].'latest.jpg" rel="lightbox" title="Click image to enlarge">
<img width="144" height="144" src="'.$url_img.$img[$i].'latest.jpg" alt=""></a></td>'.PHP_EOL;  
        $string3 .=  '<td style="text-align: center; ">
<a target="_blank" href="http://sohodata.nascom.nasa.gov/cgi-bin/data_query_search_url?Session=web&amp;Resolution=2&amp;Display=Images&amp;NumImg=30&amp;Types=instrument='.$srch[$i].'">More 512×512</a></td>'.PHP_EOL;  
}
$string1.= '</tr><tr style="height: 4px;"><td colspan="4">&nbsp;</td></tr>'.PHP_EOL;
$string2.= '</tr>'.PHP_EOL;
$string3.= '</tr>'.PHP_EOL;
echo $string1.$string2; 
#echo $string3;
echo '<tr>
<td colspan="2" style="vertical-align: top;"><p>The MDI (Michelson Doppler Imager) images shown here are taken in the continuum near the Ni I 6768 Angstrom line. 
The most prominent features are the <a target="_blank" href="http://en.wikipedia.org/wiki/Sunspot">sun spots</a>.<br />&nbsp;</p></td>

<td colspan="2" style="vertical-align: top;"><p>LASCO (Large Angle Spectrometric Coronagraph) is able to take images of the solar corona by blocking the light coming directly from the Sun 
with an occulter disk, creating an artificial eclipse within the instrument itself.
</p></td>
</tr>
<tr><td colspan="4" class="blockHead">'.$link_string.'</td></tr>

<tr>
<td style="text-align: center; " colspan="4" class="table"><br />
Bigger versions of this page in a new window:<br />
<a target="_blank" href="http://sohowww.nascom.nasa.gov/data/realtime/realtime-update.html">New regular size page</a>,
<a href="#" onclick="window.open(\'http://sohowww.nascom.nasa.gov/data/realtime/realtime-update-1280.html\',\'sohoimg\',config=\'height=1024,width=1280\')">New 1280×1024 window</a>, and
<a href="#" onclick="window.open(\'http://sohowww.nascom.nasa.gov/data/realtime/realtime-update-1600.html\',\'sohoimg\',config=\'height=1200,width=1600\')">New 1600×1200 window</a>.
</td>
</tr>

</tbody>
</table>
'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- Solar cycle
#-----------------------------------------------------------------------------------------
$link_url       = 'http://www.swpc.noaa.gov/communities/space-weather-enthusiasts';
$link_string    =  '<a target="blank" style="color: white;" href="'.$link_url.'" ><img src="./img/i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;" /> for more information</a>';

echo $block_start.'<h3 class="blockHead">Solar cycle</h3>
<br />
<table class="genericTable">
<tbody>
';
$head   = array();
$head[] = 'Sunspot numbers';
$head[] = 'F10.7CM Radio flux';
$head[] = 'AP';
#
$url_img        = 'http://services.swpc.noaa.gov/images/solar-cycle-';
$img    = array();
$img[]  = 'sunspot-number.gif';
$img[]  = '10-cm-radio-flux.gif';
$img[]  = 'planetary-a-index.gif';
$string1= '<tr>'.PHP_EOL;
$string2= '<tr>'.PHP_EOL;
$string3= '<tr>'.PHP_EOL;
$time='?time='.date('Ymd');
#$time= '?time='.time();
for ($i = 0; $i < count($img); $i++) {
        $string1 .=  '<td style="text-align: center; " class="blockHead">'.$head[$i].'</td>'.PHP_EOL;  
        $string2 .=  '<td style="text-align: center; "><a href="'.$url_img.$img[$i].$time.'" rel="lightbox" title="Click image to enlarge">
<img style="max-width: 240px; margin: 0 auto;" src="'.$url_img.$img[$i].$time.'" alt=""></a></td>'.PHP_EOL;  
        $string3 .=  '<td style="text-align: center; ">
<a target="_blank" href="">xxxx</a></td>'.PHP_EOL;  
}
$string1.= '</tr><tr style="height: 4px;"><td colspan="3">&nbsp;</td></tr>'.PHP_EOL;
$string2.= '</tr>'.PHP_EOL;
$string3.= '</tr>'.PHP_EOL;
echo $string1.$string2; 
#echo $string3;
echo '<tr><td colspan="3" class="blockHead">'.$link_string.'</td></tr>
<tr><td colspan="3"><p>
The <a target="_blank" href="http://en.wikipedia.org/wiki/Solar_cycle" >Solar Cycle</a> is observed by counting the frequency and placement of sunspots visible on the Sun.
Solar minimum occurred in December, 2008.   Solar maximum in May, 2013.
</p></td></tr>
<tr><td colspan="3">&nbsp;</td></tr>';
$head   = array();
$head[] = 'Solar wind';
$head[] = 'Satellite impact';
$head[] = 'Xray flux';
#
$url_img        = 'http://services.swpc.noaa.gov/images/';
$img    = array();
$img[]  = 'ace-mag-swepam-3-day.gif';
$img[]  = 'satellite-env.gif';
$img[]  = 'goes-xray-flux.gif';
$string1= '<tr>'.PHP_EOL;
$string2= '<tr>'.PHP_EOL;
$string3= '<tr>'.PHP_EOL;
$time='?time='.date('Ymd');
#$time= '?time='.time();
for ($i = 0; $i < count($img); $i++) {
        $string1 .=  '<td style="text-align: center; " class="blockHead">'.$head[$i].'</td>'.PHP_EOL;  
        $string2 .=  '<td style="text-align: center; "><a href="'.$url_img.$img[$i].$time.'" rel="lightbox" title="Click image to enlarge">
<img style="max-width: 240px; margin: 0 auto;" src="'.$url_img.$img[$i].$time.'" alt=""></a></td>'.PHP_EOL;  
        $string3 .=  '<td style="text-align: center; ">
<a target="_blank" href="">xxxx</a></td>'.PHP_EOL;  
}
$string1.= '</tr><tr style="height: 4px;"><td colspan="3">&nbsp;</td></tr>'.PHP_EOL;
$string2.= '</tr>'.PHP_EOL;
$string3.= '</tr>'.PHP_EOL;
echo $string1.$string2; 
#echo $string3;
echo '<tr>';
$link_url       = 'http://www.swpc.noaa.gov/products/solar-wind-transit-time';
$link_string    =  '<a target="blank" style="color: white;" href="'.$link_url.'" ><img src="./img/i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;" /> for more information</a>';
echo '<td class="blockHead">'.$link_string.'</td>';
$link_url       = 'http://www.swpc.noaa.gov/communities/satellites';
$link_string    =  '<a target="blank" style="color: white;" href="'.$link_url.'" ><img src="./img/i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;" /> for more information</a>';
echo '<td class="blockHead">'.$link_string.'</td>';
$link_url       = 'http://www.swpc.noaa.gov/products/goes-x-ray-flux';
$link_string    =  '<a target="blank" style="color: white;" href="'.$link_url.'" ><img src="./img/i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;" /> for more information</a>';
echo '<td class="blockHead">'.$link_string.'</td></tr>';
echo '<tr><td colspan="3"><p>
On the left: Real-Time Solar Wind data broadcast from NASA\'s ACE satellite.
Middle: The Satellite Environment Plot combines satellite and ground-based data to provide an overview of the current geosynchronous satellite environment.
Right:  3-days of 5-minute solar x-ray flux values measured on the SWPC primary and secondary GOES satellites.
</p></td></tr>';

echo'
</tbody>
</table>
'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- Auroral activity 
#-----------------------------------------------------------------------------------------
echo $block_start.'<h3 class="blockHead"> Auroral activity</h3>
<br />
<table class="genericTable">
<tbody>
';
$head   = array();
$head[] = 'Northern Auroral map';
$head[] = 'Southern Auroral map';
#
$url_img        = 'http://services.swpc.noaa.gov/images/animations/';  // http://services.swpc.noaa.gov/images/animations/ovation-south/latest.png
$img    = array();
$img[]  = 'ovation-north/latest.png';
$img[]  = 'ovation-south/latest.png';
$string1= '<tr>'.PHP_EOL;
$string2= '<tr>'.PHP_EOL;
$string3= '<tr>'.PHP_EOL;
$time='?time='.date('Ymd');
#$time= '?time='.time();
$end    = count($img); 
for ($i = 0; $i < $end; $i++) {
        $string1 .=  '<td style="text-align: center; " class="blockHead">'.$head[$i].'</td>'.PHP_EOL;  
        $string2 .=  '<td style="text-align: center; "><a href="'.$url_img.$img[$i].$time.'" rel="lightbox" title="Click image to enlarge">
<img style="max-width: 300px; margin: 0 auto;" src="'.$url_img.$img[$i].$time.'" alt=""></a></td>'.PHP_EOL;  
        $string3 .=  '<td style="text-align: center; ">
<a target="_blank" href="">xxxx</a></td>'.PHP_EOL;  
}
$string1.= '</tr><tr style="height: 4px;"><td colspan="'.$end.'">&nbsp;</td></tr>'.PHP_EOL;
$string2.= '</tr>'.PHP_EOL;
$string3.= '</tr>'.PHP_EOL;
echo $string1.$string2; 
#echo $string3;
echo'
</tbody>
</table>
<br />
<div class="blockDiv">
Instruments on board the NOAA Polar-orbiting Operational Environmental Satellite (POES) continually monitor the power flux carried by 
the protons and electrons that produce aurora in the atmosphere. 
SWPC has developed a technique that uses the power flux observations obtained during a single pass of the satellite over a polar region 
(which takes about 25 minutes) to estimate the total power deposited in an entire polar region by these auroral particles. 
The power input estimate is converted to an auroral activity index that ranges from 1 to 10.
</div>
'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- Alerts 
#-----------------------------------------------------------------------------------------
echo $block_start.'<h3 class="blockHead">Alerts</h3>
<br />
 <iframe src="http://www.swpc.noaa.gov/products/alerts-watches-and-warnings" 
 style="scrolling: auto; overflow: hidden; border: none; width: 100%; height: 2000px; ">
 Space Weather Alerts
 </iframe>
'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- intro movie 
#-----------------------------------------------------------------------------------------
$url_img        = 'http://sohowww.nascom.nasa.gov/';
echo $block_start.'<h3 class="blockHead">Introduction Movie</h3>
<br />
<p> Conditions on the Sun and in the solar wind, magnetosphere, ionosphere and thermosphere that can influence the performance and reliability of space-borne and
ground-based technological systems and can endanger human life or health.
This introduction movie in the English language will open on a new tab/window when you click on the image below.
</p><br />
<a   target="blank" href="'.$url_img.'classroom/nordlys_english.mp4">
<img src="'.$url_img.'classroom/nordlys_logo.jpg" alt="" />
</a>
<br />
<p class="right_p">
Also in Quicktime format: <a class="content_link" href="'.$url_img.'/classroom/nordlys_english.mov">Large (269M)</a> 
and <a class="content_link" href="'.$url_img.'/classroom/nordlys_small.mov">Small ( 60M).</a>
</p>
'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- links
#-----------------------------------------------------------------------------------------
echo $block_start.'<h3 class="blockHead">links</h3>
<br />
<b>Space Weather links:</b><br />
<a target="_blank" href="http://spaceweather.com/"><img src="img/menu_new.gif" style="max-width: 30px;" alt =" " >Losts of interesting information</a><br>
<a target="_blank" href="http://www.swpc.noaa.gov/products/3-day-forecast">3-Day Forecast of Solar and Geophysical Activity</a><br />
<a target="_blank" href="http://www.swpc.noaa.gov/products/space-weather-overview">Space Weather overview</a><br />
<a target="_blank" href="http://www.swpc.noaa.gov/products/lasco-coronagraph">LASCO Coronagraph</a><br />
<a target="_blank" href="http://www.swpc.noaa.gov/products/ace-real-time-solar-wind">Solar wind</a><br />
<a target="_blank" href="http://www.swpc.noaa.gov/products/forecast-discussion">Forecast discussion</a><br />
<a target="_blank" href="http://sohowww.nascom.nasa.gov/home.html">Solar and Heliospheric Observatory (SOHO)</a><br />
<a target="_blank" href="http://sohowww.nascom.nasa.gov/data/realtime-images.html">The Very Latest SOHO Images</a><br />
<br /><br /><b>Space Agencies:</b><br />
<a target="_blank" href="http://www.esa.int/" >European Space Agency (ESA)</a><br />
<a target="_blank" href="http://www.isas.ac.jp/e/index.html" >Institute of Space and Astronautical Science (ISAS)</a><br />
<a target="_blank" href="http://www.nasa.gov/" >National Aeronautics and Space Administration (NASA)</a><br />
<a target="_blank" href="http://www.iki.rssi.ru/eng/index.htm" >Russian Space Agency</a>
'.$block_end;
#-----------------------------------------------------------------------------------------
# --------------- Credits
#-----------------------------------------------------------------------------------------
if ($use_tabs) {
        echo '</div>
<script type="text/javascript" src="javaScripts/tabber.js"></script>'.PHP_EOL;

echo'<br />
<h3 class="blockHead">Credits:</h3>
<p>
Space Weather Images and Information (excluded from copyright) courtesy of:<br />
<a target="_blank" href="http://www.swpc.noaa.gov/">NOAA / NWS Space Weather Prediction Center</a>,
<a target="_blank" href="http://www2.hao.ucar.edu/mlso/mlso-home-page">Mauna Loa Solar Observatory (HAO/NCAR)</a>,
and <a target="_blank" href="http://sohowww.nascom.nasa.gov/home.html">SOHO (ESA &amp; NASA)</a>.
</p>
</div>'; // eo page = blockDiv
}

return;
#--------------------------------------------------------------------------------------------------
#  functions	
#--------------------------------------------------------------------------------------------------
function space_load_from_cache($cache_file_name, $cache_fct_time, &$raw_data){
	global  $space_name_start;
	if (file_exists($cache_file_name)){
		$file_time      = filemtime($cache_file_name);
		$now            = time();
		$diff           = $now - $file_time;
		echo "<!-- $space_name_start: ($cache_file_name) cache time=  $file_time - current time = $now - difference  =  $diff - Diff allowed = $cache_fct_time -->".PHP_EOL;	
		if ($diff <= $cache_fct_time){
			echo "<!-- $space_name_start: loaded from cache -->".PHP_EOL;
			$raw_data       =  file_get_contents($cache_file_name);
			return true;
		}  // eo filte time ok -> get file from cache
	}  // eo file exists
	return false;
}  // eof loadFromCache
#
function space_save_to_cache ($raw_data,$cache_fct_name) {
        global  $space_name_start;
	if (!file_put_contents($cache_fct_name, $raw_data)){
		echo "<!-- $space_name_start ERROR: ($cache_fct_name) could not be saved. Please make sure your cache directory exists and is writable. -->".PHP_EOL;
	} else {echo "<!-- $space_name_start ($cache_fct_name) saved to cache -->".PHP_EOL;}
}
function space_load_from_url($cache_fct_name, $url_forecast,&$raw_data) {
	global  $space_name_start;
        echo '<!-- $space_name_start - loading from url: '.$url_forecast.' -->'.PHP_EOL;
        $dataOK = true;
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $url_forecast);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        $raw_data       = curl_exec ($ch);
        if (!$raw_data) {
                $raw_data = curl_error($ch);
                $dataOK = false;}
        curl_close ($ch);
	return $dataOK;
} // eof load from curl
?>