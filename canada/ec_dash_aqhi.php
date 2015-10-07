<?php
require_once 'lib/Util.php';

Util::checkShowSource(__FILE__);
$pageName	= 'ec_dash_aqhi.php';
$pageVersion	= '3.20 2015-07-11';
#-----------------------------------------------------------------------
# 3.20 2015-07-11 release 2.8 version only
# ----------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
# ----------------------------------------------------------------------
if (!isset($SITE['caAQHI'])  || $SITE['caAQHI'] == false ) {return;}
# settings:
$aqhiCanadav3   = 'module '.$pageName;
$aqhi_compact   = true;					// true = smalles area, pressing i expands
$aqhiDetailPage = $SITE['pages']['ec_print_aqhi'];   	// set to false if you want to go to the CA website, Set to pagenr of wsMenuData.xml for own page
#$aqhiDetailPage = false; 
$imgI           = './img/i_symbolWhite.png';		// for the information expand
$imgL           = './img/submit.png';			// used for links to other pages
#
$startEcho      = '<!-- '; 	
$endEcho        = ' -->';
#
aqhiCAload_array();
#
$correct_code   = false;
$string = $SITE['aqhiArea'];
#echo '<pre>'; print_r($region_array); exit;
$code	= $SITE["aqhiCode"];
if (!isset ($region_array[$code]) ) {
	echo '<h3 style="text-align: center;"> The Air quality index area could not be located , code '.$code.' not found -  region '.$string.' - script ends </h3>'; 
	return false;
} else {
	$arr_aqhi	= $region_array[$code];
}
#
$cachefile      = $SITE['cacheDir'].'canada_'.$code.'_XML.txt';
$loaded_current = $loaded_fct   = false;
#
$arr_quality    = array ();
$arr_quality['region']  = $arr_aqhi;
#
if (isset($_REQUEST['force']) && strtolower($_REQUEST['force']) == 'aqhi') {
        ws_message ( $startEcho.$aqhiCanadav3. ' ('.__LINE__.'): data freshly loaded while "force" was used.'.$endEcho,true);
        $loaded_current =  $loaded_fct  = false;
}
elseif (file_exists($cachefile) ){
	$file_time      = filemtime($cachefile);
	$now            = time();
	$diff           = ($now-$file_time);
	$cacheAllowed   = 3600;
        ws_message (  '<!-- '.$aqhiCanadav3.' ('.__LINE__.'): '."($cachefile)
        cache time   = ".date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $cacheAllowed (seconds) -->");		
	if ($diff <= $cacheAllowed){
		$arr_quality    =  unserialize(file_get_contents($cachefile));
                ws_message ( $startEcho.$aqhiCanadav3.' ('.__LINE__.'):  data loaded from '.$cachefile.$endEcho);
                $loaded_current =  $loaded_fct  = true;
	} else {
		ws_message ( $startEcho.$aqhiCanadav3.' ('.__LINE__.'): data will be loaded from url '.$endEcho);
	}
}	
#
# load current first
$url    = $arr_aqhi['region_current'];
if (trim($url) == '') {         		// no current AQHI for this region exist
        $loaded_current  = true;
}
elseif ($loaded_current == false) {
	ws_message ( $startEcho.$aqhiCanadav3.' ('.__LINE__.'): loading current data from '.$url.$endEcho);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        $string = curl_exec ($ch);
        curl_close ($ch);
} 
if ($loaded_current == false) {
        if (trim($string) == '') {echo '<h3> input file has no contents - program ends </h3>'; return false;}
        $xml_current    = new SimpleXMLElement($string);
        $arr_quality['current']                 = array ();
        $arr_quality['current']['time']['fr']   = (string) $xml_current -> dateStamp -> textSummary[1];
        $arr_quality['current']['time']['en']   = (string) $xml_current -> dateStamp -> textSummary[0];
        $arr_quality['current']['aqhi']         = (string) $xml_current -> airQualityHealthIndex;
}
# load forecast
$url    = $arr_aqhi['region_fcst'];
if ($loaded_fct == false) {
	ws_message ( $startEcho.$aqhiCanadav3.' ('.__LINE__.'): loading forecast data from '.$url.$endEcho);
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        $string = curl_exec ($ch);
        curl_close ($ch);
 } 
 if ($loaded_fct == false) {
        if (trim($string) == '') {echo '<h3> input file has no contents - program ends </h3>'; return false;}
        $xml_fct        = new SimpleXMLElement($string);
        $arr_quality['fcttime']['fr']   = (string) $xml_fct -> dateStamp -> textSummary[1];
        $arr_quality['fcttime']['en']   = (string) $xml_fct -> dateStamp -> textSummary[0];
        $cnt_fct        = count($xml_fct -> forecastGroup -> forecast);
        $arr_quality['fct']     = array ();
        for ($i = 0; $i < $cnt_fct; $i++) {
                $arr                    = $xml_fct -> forecastGroup -> forecast[$i];
                $fct                    = array();
                $fct['aqhi']            = (string) $arr -> airQualityHealthIndex;
                $fct['period']['fr']    = (string) $arr -> period [1];
                $fct['period']['en']    = (string) $arr -> period [0];
                $arr_quality['fct'][$i] = $fct;
        }
}

if ($loaded_fct == false || $loaded_current == false) {
	if (!file_put_contents($cachefile, serialize($arr_quality))){   
		exit ( '<h3 style="text-align: center;">ERROR in ec_dash_aqhi.php ('.__LINE__.'):  Could not save ('.$cachefile.') to cache. <br />Please make sure your cache directory exists and is writable.<br />Program ends');
	} 
	else {	ws_message ( $startEcho.$aqhiCanadav3.' ('.__LINE__.'): '.$cachefile.' saved to cache'.$endEcho);
	}
}
#
if ($lang == 'fr') { 
	$index 		= 'fr';
	$aqhi_url_region= 'http://weather.gc.ca/airquality/pages/index_f.html';
}
else {  $index 		= 'en';
	$aqhi_url_region= 'http://weather.gc.ca/airquality/pages/index_e.html';
}
#
if ($aqhi_compact == true ) {
        $linkI 		= '"javascript:hideshow(document.getElementById(\'ahqiextra\'))"';
        $ahqiLinkI 	= '<a href='.$linkI.'><img src="'.$imgI.'" style="margin: 1px; vertical-align: middle;" alt =" " title ="'.langtransstr('more information').'"></a>';
} 
else {	$ahqiLinkI 	= '';
} 
if ($aqhiDetailPage <> false) {
        if (!isset ($skiptopText)) {$skiptopText = '';}
        $linkL = '"'.$aqhiDetailPage.'&amp;lang='.$lang.$extraP.$skiptopText.'"';
} 
else {
        $linkL = '"'.$aqhi_url_region.'" target="_blank"';
}
$ahqiLinkL = '<a href='.$linkL.'><img src="'.$imgL.'" style="margin:1px; vertical-align: middle;" alt =" " title ="'.langtransstr('more information').'"></a>';
// 
$arrow  = '<td> <div style="border-left:10px solid transparent;
border-right:10px solid transparent;
border-top:10px solid #000;
height:0;
width:0;
margin:0 auto;
"></div></td>'.PHP_EOL;
#
$location       = $arr_quality['region']['region_name'][$index];

$title          = $header_arr [8].' - Environment Canada';
$time           = '';
$strOut = '<div class="blockDiv">
<h1 class="ajaxHead" style= "margin: 0px;">'.$location.' - '.$title.'&nbsp;&nbsp;'.$ahqiLinkI.'&nbsp;&nbsp;&nbsp;'.$ahqiLinkL.'</h1>
<div style="max-width: 800px; width: 90%; margin: 0 auto;"><br />
<table class="" style = "border-collapse: collapse;">'.PHP_EOL;
if (isset ($arr_quality['current']) ) {
#        $time           = $arr_quality['current']['time'][$index];
        $period         =langtransstr('Current');
        $ahqi   = $nr   = $arr_quality['current']['aqhi'];
        $nr             = round($nr);
        if ($nr > 11)   {$nr = 11;}
        if ($nr < 1)    {$nr = 1;}
        
        $string                 = $aqhi_desc_array[$nr];
        list ($risk,$txt1,$txt2)= explode ('|',$aqhi_desc_array[$nr]);
        $strOut .= '<tr><td style="text-align: left;"><small>'.$period.'</small></td>
<td style="text-align: left;"><small><b>'.$ahqi.'&nbsp;-&nbsp;</b>'.$risk.'</small></td>
'.PHP_EOL;
	for ($p = 1; $p <= 11; $p++) {
		if ($nr == $p) {$strOut .= $arrow;} else {$strOut .= '<td>&nbsp;</td>'; }
	}
	$strOut .= '</tr>'.PHP_EOL;	
}
$time = langtransstr ('Forecast issued at').': '.$arr_quality['fcttime'][$index];
for ($i = 0; $i < count($arr_quality['fct']); $i++) {
        $arr            = $arr_quality['fct'][$i];
        $period         = $arr['period'][$index];
        $ahqi   = $nr   = $arr['aqhi'];
        if ($nr > 11) {$nr = 11;}
        $string         = $aqhi_desc_array[$nr];
        list ($risk,$txt1,$txt2) = explode ('|',$aqhi_desc_array[$nr]);
        $strOut .= '<tr><td style="text-align: left;"><small>'.$period.'</small></td>
<td style="text-align: left;"><small><b>'.$ahqi.'&nbsp;-&nbsp;</b>'.$risk.'</small></td>
'.PHP_EOL;
	for ($p = 1; $p <= 11; $p++) {
		if ($nr == $p) {$strOut .= $arrow;} else {$strOut .= '<td>&nbsp;</td>'; }
	}
	$strOut .= '</tr>'.PHP_EOL;	
}

$strOut .= '
<tr><td>&nbsp;</td><td>&nbsp;</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #9cf; ">1</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #6cf; ">2</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #0cf; ">3</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #9CC; ">4</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #999; ">5</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #996; ">6</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #960; ">7</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #963; ">8</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #930; ">9</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: #963; ">10</td>
<td style = "width: 6%; border: 1px solid grey; color: white; background-color: RED; ">+</td>
</tr>
<tr><td></td><td>&nbsp;</td><td colspan="10"><small>'.$time.'</small></td><td>&nbsp;</td></tr>
</table>
</div>		
'.PHP_EOL;

if ($aqhi_compact == true ) 
     {  $strHide        = '<div id="ahqiextra" style="display: none;">';} 
else {  $strHide        = '<div>';} 
$strHide        .= '
<table class="genericTable" style="border: 1px black solid; collapse; collapse; text-align: center; width: 90%; margin: 0 auto;">
<thead style="background-color: #ccc;"><tr>
<th rowspan="2" style="min-width: 80px;">'.$header_arr [1].'</th>
<th rowspan="2" style="min-width: 80px;">'.$header_arr [2].'</th>
<th colspan="2">'.$header_arr [3].'</th>
</tr><tr>
<th >'.$header_arr [4].'</th>
<th >'.$header_arr [5].'</th>
</tr></thead>
<tbody>
<script type="text/javascript">
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>
';
$keys           = array (1,4,7,11);
$aqhi_ranges    = array ('1 - 3','4 - 6','7 - 9','10 +');
for ($n = 0; $n < count ($keys); $n++) { 
        $strHide        .= '<tr>';
        list($risk,$at_risk,$general)     = explode ('|',$aqhi_desc_array[$keys[$n]]);
      #  if ($n
        $strHide        .= '<td style="border: 1px solid black;">'.$risk.'</td><td style="border: 1px solid black;">'.$aqhi_ranges[$n].'</td><td style="border: 1px solid black;">'.$at_risk.'</td><td style="border: 1px solid black;">'.$general.'</td></tr>'.PHP_EOL;
}
$text	= langtransstr('More information at');
$strHide        .= '<tr><td colspan="4">'.$header_arr [6].'</td></tr>
<tr><td colspan="4" style="border: 1px solid black;">'.$text.
': <a href="'.$header_arr [7].'" target="_blank"><img src="'.$imgL.'" style="margin:1px; vertical-align: middle;" alt =" " title ="'.$text.'">http://ec.gc.ca/cas-aqhi</a></td></tr>
</tbody>
</table>
<br />
</div>';

echo '<!--  Air Qaulity  CA only  -->'.PHP_EOL;
echo $strOut;
echo $strHide;
echo '</div><!--  end of  Air Qaulity  CA only  -->'.PHP_EOL;
return true;
#
function aqhiCAload_array() {
        global $SITE, $lang, $region_array, $aqhi_desc_array,$header_arr , $startEcho, $aqhiCanadav3, $endEcho;
        if ($lang == 'fr') {
                $aqhi_desc_array[1]	= $aqhi_desc_array[2]	= $aqhi_desc_array[3]   =
                "Risque faible|<b>Profitez</b> de vos activités habituelles en plein air.|Qualité de l'air <b>idéale</b> pour les activités en plein air.|";
                $aqhi_desc_array[4]	= $aqhi_desc_array[5]	= $aqhi_desc_array[6]	= 
                "Risque modéré|<b>Envisagez de réduire </b> ou de réorganiser les activités exténuantes en plein air si vous éprouvez des symptômes.|<b>Aucun besoin de modifier</b> vos activités habituelles en plein air à moins d'éprouver des symptômes comme la toux et une irritation de la gorge.|";
                $aqhi_desc_array[7]	= $aqhi_desc_array[8]	=  $aqhi_desc_array[9]	= $aqhi_desc_array[10]	= 
                "Risque élevé|<b>Réduisez</b> ou réorganisez les activités exténuantes en plein air. Les enfants et les personnes âgées devraient également modérer leurs activités.|<b>Envisagez de réduire</b> ou de réorganiser les activités exténuantes en plein air si vous éprouvez des symptômes comme la toux et une irritation de la gorge.|";
                $aqhi_desc_array[11]	= 
                "Risque très élevé|<b>Évitez </b> les activités exténuantes en plein air. Les enfants et les personnes âgées devraient également éviter de se fatiguer en plein air.|<b>Réduisez</b> ou réorganisez les activités exténuantes en plein air, particulièrement si vous éprouvez des symptômes comme la toux et une irritation de la gorge.|";
        } else {  // default
                $aqhi_desc_array[1]	= $aqhi_desc_array[2]   = $aqhi_desc_array[3]	= 
                "Low risk|<b>Enjoy</b> your usual outdoor activities.|<b>Ideal</b> air quality for outdoor activities.|";
                $aqhi_desc_array[4]	= $aqhi_desc_array[5]	= $aqhi_desc_array[6]	= 
                "Moderate risk|<b>Consider reducing</b> or rescheduling strenuous activities outdoors if you are experiencing symptoms.|<b>No need to modify</b> your usual outdoor activities unless you experience symptoms such as coughing and throat irritation.|";
                $aqhi_desc_array[7]	= $aqhi_desc_array[8]	= $aqhi_desc_array[9]	= $aqhi_desc_array[10]	= 
                "High  risk|<b>Reduce</b> or reschedule strenuous activities outdoors. Children and the elderly should also take it easy.|<b>Consider reducing</b> or rescheduling strenuous activities outdoors if you experience symptoms such as coughing and throat irritation.|";
                $aqhi_desc_array[11]	= 
                "Very high health risk|<b>Avoid</b> strenuous activities outdoors.  Children and the elderly should also avoid outdoor physical exertion.|<b>Reduce</b> or reschedule strenuous activities outdoors, especially if you experience symptoms such as coughing and throat irritation.|";
        }
        ws_message ( $startEcho.$aqhiCanadav3.' ('.__LINE__.'): loading canada/ec_list_aqhi.php '.$endEcho,true);
        include 'canada/ec_list_aqhi.php';
        if ($lang == 'fr') {
                $header_arr [1] = 'Risque pour la santé';
                $header_arr [2] = 'CAS';
                $header_arr [3] = 'Messages santé';
                $header_arr [4] = 'Population touchée*';
                $header_arr [5] = 'Population en général'; 
                $header_arr [6] = "* Les personnes éprouvant des problèmes cardiaques ou respiratoires sont les plus menacées, observez les conseils habituels de votre médecin sur l'exercice et la manière de prendre soin de vous.";
                $header_arr [7] = 'http://ec.gc.ca/cas-aqhi/default.asp?lang=Fr&amp;n=065BE995-1';
                $header_arr [8] = 'Cote air santé (CAS)';             
         } else {
                $header_arr [1] = 'Health Risk ';
                $header_arr [2] = 'AQHI';
                $header_arr [3] = 'Health Messages';
                $header_arr [4] = 'At Risk Population*';
                $header_arr [5] = 'General Population'; 
                $header_arr [6] = "* People with heart or breathing problems are at greater risk. Follow your doctor's usual advice about exercising and managing your condition.";
                $header_arr [7] = 'http://ec.gc.ca/cas-aqhi/default.asp?lang=En&amp;n=065BE995-1';
                $header_arr [8] = 'Air Quality Health Index (AQHI)';     
        }
}
# ----------------------  version history
# 3.20 2015-07-11 release 2.8 version 
