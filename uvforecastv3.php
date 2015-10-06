<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) { //--self downloader --
   $filenameReal = __FILE__;	# display source of script if requested so
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
$pageName       = 'uvforecastv3.php';
$pageVersion	= '3.20 2015-07-19';
#-------------------------------------------------------------------------------
# 3.20 2015-07-19 release 2.8 version
#-------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#-----------------------------------------------------------------------
# set error reporting  
#-----------------------------------------------------------------------
$errorMessages  = false;                 //##### set to false if everything is OK
#
if (isset($_REQUEST['debug'])){	$errorMessages  = true;} 
if ($errorMessages) { ini_set('display_errors', 'On');  error_reporting(E_ALL);}
#-----------------------------------------------------------------------
# these are your settings for retrieving information from www.tennis.nl
#-----------------------------------------------------------------------
$myfolder       = './';                 //##### set if include 'foldername/uvforecastv3.php'; is used
#$myfolder      = './uvv3/';            // EXAMPLE: include './uvv3/uvforecastv3.php';

$imgdir		= $myfolder.'img/';	// all img's for uv in own folder 
#
$uvimg   	= $imgdir.'uv';         // location of images and first part of names
$uvimgext       = '.gif';
$uvimgsize      = '';                   // default size
$uvimgsize      = 'width: 20px;';       // half size images
#
$maxicons 	= 6;  			// maximum number of icons to display
#
$uvcompact      = true;
$uvprint        = false;
#
$standardfont   = '';                   // use font ssettings from enclosing page
#$standardfont   = 'font-family: sans-serif; font-size: small;';  // own font settings
                   
$leuventemplate = true;                 // main switch for Leuven-template or stand-alone use
#
#-----------------------------------------------------------------------
if ($leuventemplate <> true) {  // settings in not in leuven-template
# -----------------   
#
$lang		= 'nl';			// default language - supported nl en fr de
$latitude       = '50.8444';            // the latitude of your stations location
$longitude      = '4.9876';             // the longitude also
$cachedir       = $myfolder.'cache/';	// directory to cache files     IMPORTANT setting
#
$commadecimal   = true;  	        //  most europeans use a comma for a decimal point.
$charset	= 'UTF-8'; #'UTF-8'; #'windows-1252'; 	// default character set for webpages, UTF-8 is the modern one, windows-1252 is the most used one
$strtolower     = false;                // all texts/translations in lowercase if true
$timezone       = 'Europe/Brussels';    // again important, valid for most of europe.  Do not change if you do not know what valid codes are.
#-----------------------------------------------------------------------
} else {                        // settings for leuven template
# ----------------------------------------------------------------------    
$lang		= $SITE['lang'];        
$latitude       = $SITE['latitude'];    // the latitude of your stations location
$longitude      = $SITE['longitude'];   // the longitude also
$cachedir       = $SITE['cacheDir'];	// directory to cache files     IMPORTANT setting
#
$commadecimal   = $SITE['commaDecimal'];//  most europeans use a comma for a decimal point.
$charset	= $SITE['charset']; 	// default character set for webpages, UTF-8 is the modern one, windows-1252 is the most used one
$strtolower     = $SITE['textLowerCase'];       // all texts/translations in lowercase if true
}
# ----------------------------------------------------------------------
#                    these are the styles used for the boxes and colors
if ($leuventemplate <> true) { 
# ----------------------------------------------------------------------
        $darkcolor      = '#ACACAC';      // dark grey  '#ACACAC';      // dark green   '#00745B';
        $mediumcolor    = '#EEEEEE';      // light grey '#EEEEEE';      // light green  '#B1CBA0';
        $lightcolor     = 'transparent';  // transparent
        $blockdivstyle  = ' style="border-radius: 5px; margin: 5px 5px; color: black; background-color: transparent; border: 1px solid '.$darkcolor.'; border-bottom: 3px solid grey; overflow: hidden; '.$standardfont.'" ';
        $blockheadstyle = ' style="font-size: 100%; border-radius: 3px; font-weight: bold; color:  white; background-color: '.$darkcolor.'; text-align: center;margin: 0;" ';
        $tablestyle     = ' style="border-collapse: collapse; width: 100%; color: black; text-align: center; '.$standardfont.'" ';
        $rowdarkstyle   = ' style="background-color: '.$mediumcolor.';" ';
        $rowlightstyle  = ' style="background-color: '.$lightcolor.';" ';
#-----------------------------------------------------------------------
} else {                        // settings for leuven template
# ----------------------------------------------------------------------    
        $blockdivstyle = ' class="blockDiv"';
        $blockheadstyle= ' class="blockHead" ';
        $tablestyle    = ' class="genericTable" ';
        $rowdarkstyle  = ' class="row-dark" ';
        $rowlightstyle = ' class="row-light" style="background-color: transparent;" ';
}
# ------------------------------------------------------------------------------
#           REALLY , I mean it:         do not change below this point
# ------------------------------------------------------------------------------
$uvcolors        = array ( 
        0  => '#A4CE6A', 1 =>   '#A4CE6A', 2 => '#A4CE6A',  
        3  => '#FBEE09', 4 =>   '#FBEE09', 5 => '#FBEE09',
        6  => '#FD9125', 7 =>   '#FD9125', 8 => '#FD9125',  
        9  => '#F63F37', 10 =>  '#F63F37', 
        11 => '#807780');
$uvwords        = array (
        0  => 'unknown',    1 => 'Low',         2 => 'Low',  
        3  => 'Medium',     4 => 'Medium',      5 => 'Medium',
        6  => 'High',       7 => 'High',        8 => 'High',  
        9  => 'Very high',  10 => 'Very high', 
        11 => 'Extreme'  );
$uvurl          = '';
$uvcacheallowed = 7200;
$uvlat          = round($latitude,2);
$uvlon          = round($longitude,2);
$uvcachefile    = $cachedir .'uv'.$uvlat.'_'.$uvlon;
$uvarr          = array();                      // will contain all information for today and coming days
$scripttext     = '';                           // will contail all kind of messages during execution of the script
$scriptname     = 'module uvforecastv3.php';
$testFile       = ''; #$myfolder.'test1.html';
$fullurl        = 'http://www.temis.nl/uvradiation/nrt/uvindex.php?lon='.$uvlon.'&lat='.$uvlat;
#
if (isset ($uv_page)  && $uv_page == true) {$uvcompact = false;}
#-----------------------------------------------------------------------
# Load language files
#-----------------------------------------------------------------------
if (isset($_REQUEST['lang'])){
        $string = trim($_REQUEST['lang']).'en';
        $lang   = substr($string,0,2);
} 
$uvlanglookup   = array ();
$uvmissingtrans = array ();
uvloadlangstr($lang);
#-----------------------------------------------------------------------
# Set correct timezone, already done for leuven
#-----------------------------------------------------------------------
if ($leuventemplate <> true) {
        if (!function_exists('date_default_timezone_set')) {
                 putenv("TZ=" . $timezone);
        } else {
                 date_default_timezone_set($timezone);
        }
}
#-----------------------------------------------------------------------
# get the uv data from the temis site
#-----------------------------------------------------------------------
$uvarray        = getuvdata();
if (is_array ($uvarray) ) {
  for ( $i = 0; $i < count($uvarray); $i++) {
        if (!isset($uvarray[$i]['uvYmd']) ) {$uvarray[$i]['uvYmd'] = date('Ymd',$uvarray[$i]['unixtime']);}
  } 
} else {$uvarray = array ();}
#echo '<pre>'; print_r ($uvarray); exit;
echo $scripttext;       // the debug info is printed as html comment info
$scripttext     = '';
#
$credit         = uvtransstr('UV forecast courtesy of and Copyright').' &copy; KNMI/ESA (<a href="http://www.temis.nl/" target="_blank">www.temis.nl</a>). '.
uvtransstr('Used with permission.').'&nbsp;&nbsp; - script v3:&nbsp;by Wim van der Kuil - <a href="http://leuven-template.eu/" target="_blank">Leuven-Template.eu</a>';
$maxicons 	= min($maxicons,count($uvarray)); 	// use lesser of number of icons available
$uvhtml = '<!-- uvforecastv3 -->
<div '.$blockdivstyle.'>'.PHP_EOL;
if ($uvcompact) {
        $uvhtml .= '<h3 '.$blockheadstyle.'>'.uvtransstr('UV Forecast').'&nbsp;
<a href="javascript:hideshow(document.getElementById(\'uvextra\'))">
<img src="'.$imgdir.'i_symbolWhite.png" alt=" " style="margin-top: 2px; width: 12px;"></a>
</h3>
<script type="text/javascript">
  function hideshow(which){
    if (!document.getElementById)
    return
    if (which.style.display=="block")
    which.style.display="none"
    else
    which.style.display="block"
  }
</script>';}
else {$uvhtml .= '<h3 '.$blockheadstyle.'>'.uvtransstr('UV Forecast').'</h3>';}
$uvhtml .= '<div style="width: 100%; margin: 0px auto; text-align: center;">'.PHP_EOL;
#
if ($maxicons  < 1 )    {       // there are no uv index found
        $uvhtml .= '<h2>'.uvtransstr('The UV Index Forecast is currently not available').'</h2></div></div>';
        if (isset ($scripttext) ) {$uvhtml .= $scripttext;}
        return;
}

$uvhtml .=    '<table '.$tablestyle.'>'.PHP_EOL;
$width  = floor (100 / $maxicons);
$td     = '<td  style="width: '.$width.'%;">';
$tdend  = '</td>'.PHP_EOL;
$stringtime     = '<tr '.$rowdarkstyle.'>';
$stringimg      = '<tr '.$rowlightstyle.'>';
$stringuv       = '<tr '.$rowdarkstyle.'>';
$stringuvword   = '<tr '.$rowlightstyle.'>';
$stringuvcredit = '<tr '.$rowdarkstyle.'><td colspan="'.$maxicons.'"><small>'.$credit.'</small></td>';
for ($i = 0; $i < $maxicons; $i++) {
        $stringtime     .= $td.uvtransstr( date('l',$uvarray[$i]['unixtime']) ).$tdend;
        $stringimg      .= $td.$uvarray[$i]['img'].$tdend;
        $stringuv       .= $td.$uvarray[$i]['uv'].$tdend;
        $word            = generateuvword ($uvarray[$i]['uv']);
        $stringuvword   .= $td.$word.$tdend;
}
$stringtime     .= '</tr>'.PHP_EOL;
$stringimg      .= '</tr>'.PHP_EOL;
$stringuv       .= '</tr>'.PHP_EOL;
$stringuvword   .= '</tr>'.PHP_EOL;
$stringuvcredit .= '</tr>'.PHP_EOL;
$uvhtml .=    $stringtime.$stringimg.$stringuv.$stringuvword.$stringuvcredit;
$uvhtml .=    '</table>'.PHP_EOL;

if ($uvcompact) {$uvhtml .= '<div id="uvextra" style="display:none;">'.PHP_EOL;}

$uvhtml .= '<table style="width: 100%; '.$standardfont.'"><tr>'.PHP_EOL;
$plus='';
for ($i = 1; $i < 11; $i++) {
         $uvhtml .= '<td style="color:black; width:8%; height: 40px; text-align:center; margin:2px; font: 36px Arial, sans-serif;font-weight: bold;
padding:5px; border-radius:5px; background-color: '.$uvcolors[$i].';">'.$i.'</td>'.PHP_EOL;
}
$uvhtml .= '<td style="color:black; width:20%; height: 40px; text-align:center; margin:2px; font: 30px Arial, sans-serif;font-weight: bold;
padding:5px; border-radius:5px;background-color: '.$uvcolors[$i].';">11 - 16</td>'.PHP_EOL;

$uvtexts[1]     = uvtransstr('green1').'<br />'.uvtransstr('green2');
$uvtexts[3]     = uvtransstr('yellow1').'<br />'.uvtransstr('yellow2');
$uvtexts[6]     = uvtransstr('orange1').'<br />'.uvtransstr('orange2');
$uvtexts[9]     = uvtransstr('red1').'<br />'.uvtransstr('red2');

$uvhtml .= '</tr>
<tr>
<td colspan="2" style="text-align: center; border-radius:5px; background-color: '.$uvcolors[1].';">'.uvtransstr($uvwords[1]).'</td>
<td colspan="3" style="text-align: center; border-radius:5px; background-color: '.$uvcolors[3].';">'.uvtransstr($uvwords[3]).'</td>
<td colspan="3" style="text-align: center; border-radius:5px; background-color: '.$uvcolors[6].';">'.uvtransstr($uvwords[6]).'</td>
<td colspan="2" style="text-align: center; border-radius:5px; background-color: '.$uvcolors[9].';">'.uvtransstr($uvwords[9]).'</td>
<td colspan="1" style="text-align: center; border-radius:5px; background-color: '.$uvcolors[11].';">'.uvtransstr($uvwords[11]).'</td>
</tr><tr>
<td colspan="2" style="vertical-align: top; text-align: center; border-radius:5px; background-color: white; border: 2px solid; border-color: '.$uvcolors[1].';">'.$uvtexts[1].'</td>
<td colspan="3" style="vertical-align: top; text-align: center; border-radius:5px; background-color: white; border: 2px solid; border-color: '.$uvcolors[3].';">'.$uvtexts[3].'</td>
<td colspan="3" style="vertical-align: top; text-align: center; border-radius:5px; background-color: white; border: 2px solid; border-color: '.$uvcolors[6].';">'.$uvtexts[6].'</td>
<td colspan="3" style="vertical-align: top; text-align: center; border-radius:5px; background-color: white; border: 2px solid; border-color: '.$uvcolors[9].';">'.$uvtexts[9].'</td>
</tr>
</table>'.PHP_EOL;
if ($uvcompact) {$uvhtml .= '</div> '.PHP_EOL;}
$uvhtml .=    '</div> 
</div>
<!-- end of uv forecast -->'.PHP_EOL;


$end    = count($uvmissingtrans);
if ($end > 0) {
#$uvlanglookup['from']	        ='van';
        $uvhtml .= '<!-- missing uvlanglookup entries '.PHP_EOL;
        for ($i = 0; $i < $end; $i++)  {
                $uvhtml .= '$uvlanglookup[\''.$uvmissingtrans[$i].'\'] = \''.$uvmissingtrans[$i].'\';'.PHP_EOL;
        }
        $uvhtml .= ' end of missing langlookup -->'.PHP_EOL;
}
if (isset ($uvprint) && $uvprint == true) {echo $uvhtml;}
function getuvdata() {
        global  $uvcachefile, $uvcacheallowed, $fullurl, $scripttext, $commadecimal; 
#
        if ($uvcachefile <> ''){
                $returnArray    = uvloadfromcache($uvcachefile, $uvcacheallowed);  	// load from cache returns data only when its data is valid
                if (!empty($returnArray)) {		// if data is in cache and valid return data to calling program
                        return $returnArray;            // data goes back to calling program
             
                }  // eo return to calling program
        }  // eo check cache
        $rawdata        = uvmakerequest($fullurl);
	if ($rawdata == ''){
		$upped          = 2 * $uvcacheallowed;
		$scripttext     .= '<!-- upped cachetime from '.$uvcacheallowed.' to '.$upped.' as no valid data was retrieved -->'.PHP_EOL;
		$returnArray    = uvloadfromcache($uvcachefile, $upped);       // load from cache returns data only when its data is valid
                if (!empty($returnArray)) {				        // if data is in cache and valid return data to calling program
                        return $returnArray;
                }  // eo return to calling program	
	        return false;
        }
        $replace	= array ("&nbsp;");               // if we need it 
        $rawdata        = str_replace($replace,'',$rawdata);
        $end            = strlen($rawdata);
        $search         = '<tr><td align=left ><i>';
        $pos            = 0;
        $pos            = strpos($rawdata, $search ,0);
        if ($pos == 0) {echo 'unknown error'; return false;}
        $datafound      = true;
        $nextstring     = '<td align=right nowrap>';
        $lennextstring  = strlen($nextstring);
        $endstring      =  '</td>';
        while ($datafound) {
                $startdate      = 0;
                $startdate      = strpos($rawdata, $nextstring, $pos);
                if ($startdate < 1) {
                        $datafound = false;
                        break;
                }
                $startdate      = $startdate + $lennextstring;
                $pos            = strpos($rawdata, $endstring   ,$startdate);
                $len            = $pos - $startdate;
                $uvdate         = trim(substr ($rawdata, $startdate, $len ));
                $date           = strtotime($uvdate);
                $today          = date('Ymd',time());
                $uvfound        = date('Ymd',$date );
                $startuv        = strpos($rawdata, $nextstring, $pos) + $lennextstring;
                $pos            = strpos($rawdata, $endstring,  $startuv);
                $len            = $pos - $startuv;
                $uvuv           = trim(substr ($rawdata, $startuv, $len ));
                $startozone     = strpos($rawdata, $nextstring, $pos) + $lennextstring;
                $pos            = strpos($rawdata, $endstring,           $startozone);
                $len            = $pos - $startozone;
                $uvozone        = trim(substr ($rawdata, $startozone, $len ));
                if ($uvfound < $today) {
                        $scripttext     .= '<!-- skipped, today = '.$today.' - old data  $uvfound = '.$uvfound.' -->'.PHP_EOL;
                        continue;
                }      // skip yesterday, if it is in the data
                $img    = generateuvimage($uvuv);
                if ($commadecimal)      {
                        $uvuv   = str_replace ('.',',',$uvuv); 
                        $uvozone= str_replace ('.',',',$uvozone); 
                }                          
                $uvarray[]      = array ('rawdate' => $uvdate, 'unixtime' => $date,
                                         'uvYmd'   => $uvfound,
                                         'uv' => $uvuv, 'ozone' => $uvozone, 
                                         'img' => $img);                     
        }
        if (isset ($uvarray) ){uvwritetocache($uvcachefile, $uvarray); return $uvarray;} 
        $scripttext     .=  "<!-- No valid data found loading cache of any age -->".PHP_EOL;
        $returnArray    = uvloadfromcache($uvcachefile, 3*24*3600);       // load from cache when not older then 3 days
        if (empty($returnArray)) {
                $scripttext     .=  "<!-- No cache of any age available , return nothing -->".PHP_EOL;
                return $returnArray;
        }
        $scripttext     .=  "<!-- skipping previous dates from cache -->".PHP_EOL;
        $today          = date('Ymd',time());
        $uvarray        = array();
        for ($i = 0; $i < count ($returnArray); $i++) {
                if (!isset ($returnArray[$i]['uvYmd']) ) {$returnArray[$i]['uvYmd'] = date('Ymd',$returnArray[$i]['unixtime']);}
                $scripttext     .=  "<!-- checking ".$returnArray[$i]['uvYmd']." in cache -->".PHP_EOL;
                if ($returnArray[$i]['uvYmd'] >= $today) {$uvarray[] = $returnArray[$i];}
        }
        $count = count($uvarray);
        $scripttext     .=  "<!-- found $count valid UV forecasts in to old cache -->".PHP_EOL;
        return $uvarray;
} // eof getuvdata
#-------------------------------------------------------------------------------
#       decide which icon for this uv-value
#-------------------------------------------------------------------------------
function generateuvimage ($nr){
        global  $uvimg, $uvimgext, $uvimgsize;
        $nr           = str_replace (',','.',$nr);      // make sure it is a decimal point!
        if (!is_numeric ($nr) ) {$nr = 1;}              // ??
        $uvround        = round ($nr, 0);
        if ($uvround > 11) {$uvround = 11; }
        if ($uvround < 1 ) {$uvround = 1; }
        if ($uvround < 10) 
                { $uvnrtxt = (string) '0'.$uvround; }
        else    { $uvnrtxt = (string) $uvround; }
        $img            = '<img src="'.$uvimg.$uvnrtxt.$uvimgext.'" alt="uv index rounded" title="uv index rounded" style="vertical-align: bottom; border-radius:5px; '.$uvimgsize.'"/>';
        return $img;	
}
function generateuvword ($nr){
        global  $uvcolors, $uvwords;
        $nr           = str_replace (',','.',$nr);      // make sure it is a decimal point!
        if (!is_numeric ($nr) ) {$uvround = 0;}         // 0 = unknown
        else {  $uvround   = round ($nr, 0);
                if ($uvround == 0) {$uvround = 1;}
        }
        if ($uvround > 11) {$uvround = 11; }
        return '<span style="border: solid 1px; border-radius: 5px; background-color:'.$uvcolors[$uvround].';">&nbsp;'.uvtransstr($uvwords[$uvround]).'&nbsp;</span>';   
}
function uvloadfromcache($cachefile, $cacheAllowed){
        global $scripttext, $scriptname, $cron_all;
#
        if (!file_exists($cachefile)){
                ws_message ( "<!-- $scriptname (".__LINE__.") ($cachefile) not found -->");
                return '';
        }	
        $file_time      = filemtime($cachefile);
        $now            = time();
        $diff           = ($now     -   $file_time);
        ws_message (  "<!-- $scriptname (".__LINE__.") ($cachefile) cache times:
        cache time   = ".date('c',$file_time)." from unix time $file_time
        current time = ".date('c',$now)." from unix time $now 
        difference   = $diff (seconds)
        diff allowed = $cacheAllowed (seconds) -->");
	if (isset ($cron_all) ) {		// runnig a cron job
		$cacheAllowed 	= $cacheAllowed - 360;	// 
		ws_message ( "<!-- $scriptname  (".__LINE__.")  max cache lowered with 360 seconds as cron job is running -->");
	}	
        if ($diff <= $cacheAllowed){
                ws_message ( "<!-- $scriptname (".__LINE__.") ($cachefile) loaded from cache -->");
                $returnArray =  unserialize(file_get_contents($cachefile));
                return $returnArray;
        }
} // eof uvloadFromCache

function uvwritetocache($cachefile, $data){
        global  $scripttext, $scriptname, $cachedir, $blockdivstyle;
        if ($cachedir   == '')  {
                ws_message ( "<!-- $scriptname (".__LINE__.") WARNING  no cache dir specified. STRONGLY ADVISED TO RECTIFY THAT -->",true);
        } 
        elseif (!file_exists($cachedir)){
                ws_message (  "<!-- $scriptname (".__LINE__.") WARNING  no cache dir exists, tried to create one -  STRONGLY ADVISED TO CHECK IT -->",true);
                mkdir($cachedir, 0777);   // attempt to make the cache dir
        }
        if (file_put_contents($cachefile, serialize($data))){   
                ws_message ( "<!-- $scriptname (".__LINE__.") ($cachefile) saved to cache  -->");
                return;
        } 
        echo '<div '.$blockdivstyle.'>
<h3>'.uvtransstr('cachewarn1').' <i>'.$cachefile.'</i> '.uvtransstr('cachewarn2').'</h3>
<h3> Fatal error - script ended </h3>
</div>'.PHP_EOL;
        exit;
} // eof uvwriteToCache

function uvmakerequest($fullurl){
        global $scripttext, $scriptname, $testFile;
        if (isset($testFile) && $testFile <> '') {
                 ws_message ( "<!-- TESTING $scriptname (".__LINE__.")  uv data loaded from test file: $testFile  -->",true);
                $rawdata        = file_get_contents($testFile);
        } 

        else {
                ws_message ( "<!-- $scriptname (".__LINE__.") uv data loaded from $fullurl  -->");
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_URL, $fullurl);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
                $rawdata = curl_exec ($ch);
                curl_close ($ch);
        }
        if (empty($rawdata)){
                ws_message ( "<!-- $scriptname (".__LINE__.") ERROR  uv data empty,($fullurl)  could not be loaded  -->",true);
                return '';
        }
        $errortext      = array();
        $errortext[]    = 'Service Unavailable';
        $errortext[]    = 'Access denied, please contact the TEMIS team';
        $end            = count ($errortext);
        $errorsfound    = false;
        for ($i = 0; $i < $end; $i++) {
                $pos    = 0;
                $pos    = strpos($rawdata, $errortext[$i] ,0);
                if ($pos > 0) {$errorsfound = true;
                ws_message ( "<!-- $scriptname (".__LINE__.") ERROR  uv data ($fullurl) \n\t $errortext[$i]  - $pos -->",true);
                return '';
                }
        }
        $search         = '<tr><td align=left ><i>';
        $pos            = 0;
        $pos            = strpos($rawdata, $search ,0);	
        if ($pos < 1) {   // no good data found
                ws_message ( "<!-- $scriptname (".__LINE__.") ERROR uv data ($fullurl) Service returns no good data -->",true);
                return '';
        }		
        return $rawdata;
} // eof uvmakeRequest


# -----------------------  language data   -------------------------------------
function uvtransstr ($text) {
        global $uvlanglookup,    $uvmissingtrans, $strtolower;
        if (isset ($uvlanglookup[$text]) ) {
                $return         = $uvlanglookup[$text];
        } else {
                $uvmissingtrans[]= $text; 
                $uvlanglookup[$text]= $text;
                $return         = $text;
        }
        if ($strtolower) {$return = strtolower($return); }
        return $return;
} // eof uvtransstr
#
function uvloadlangstr($lang) {
        global $charset, $uvlanglookup;
if     ($lang == 'nl') {
# -------------------------------------- Nederlands / Dutch texts ------
$uvlanglookup['UV Forecast']    = 'UV-index verwachting';
$uvlanglookup['Saturday']       = 'Zaterdag';
$uvlanglookup['Sunday']         = 'Zondag';
$uvlanglookup['Monday']         = 'Maandag';
$uvlanglookup['Tuesday']        = 'Dinsdag';
$uvlanglookup['Wednesday']      = 'Woensdag';
$uvlanglookup['Thursday']       = 'Donderdag';
$uvlanglookup['Friday']         = 'Vrijdag';
$uvlanglookup['Original by']    = 'Script ontwikkeld door: ';
$uvlanglookup['Adapted by']     = ' aangepast voor de template door: ';
$uvlanglookup['Low']            = 'Laag';
$uvlanglookup['Medium']         = 'Gemiddeld';
$uvlanglookup['High']           = 'Hoog';
$uvlanglookup['Very high']      = 'Zeer hoog';
$uvlanglookup['Extreme']        = 'Extreem';
$uvlanglookup['green1']         = 'U kunt veilig buiten komen zonder bescherming.';
$uvlanglookup['green2']         = 'Draag een zonnebril op heldere dagen en gebruik bescherming als u makkelijk verbrandt.';
$uvlanglookup['yellow1']        = 'Enig risico van verbranding bij onbeschermde huid.';
$uvlanglookup['yellow2']        = 'Zoek de schaduw op tijdens de middag. Draag een hoed / pet, T-shirt. Smeer je in (15+).';
$uvlanglookup['orange1']        = 'Hoog risico van verbranding door de zonnestraling bij onbeschermde huid.';
$uvlanglookup['orange2']        = 'Zoek de schaduw op tijdens de middag. Draag een hoed met een brede rand en een shirt met mouwen. Smeer je in (30+).';
$uvlanglookup['red1']           = 'Zeer hoog risico van snelle verbranding door de zonnestraling bij onbeschermde huid.';
$uvlanglookup['red2']           = 'Ga niet naar buiten tijdens de middag. Zoek zo mogelijk altijd de schaduw op. Brede hoed, ruime bedekkende kleding  en zonnecreme (30+) zijn absoluut noodzakelijk.';
$uvlanglookup['cachewarn1']	= 'Kan de gegevens  ';
$uvlanglookup['cachewarn2']	= ' niet opslaan in de cache. Zorg ervoor dat de cache bestaat en beschrijjfbaar is!';
$uvlanglookup['UV forecast courtesy of and Copyright']='UV verwachting met dank aan en Auteursrecht van ';
$uvlanglookup['Used with permission.']='Gebruikt met toestemming.';
} # --------------------- Nederlands / Dutch texts ------ END OF -------
elseif ($lang == 'fr') {
# ----------------------------------------- French / Français texts ----
$uvlanglookup['UV Forecast']    ='Index UV Prévision';
$uvlanglookup['Saturday']       ='Samedi';
$uvlanglookup['Sunday']         ='Dimanche';
$uvlanglookup['Monday']         ='Lundi';
$uvlanglookup['Tuesday']        ='Mardi';
$uvlanglookup['Wednesday']      ='Mercredi';
$uvlanglookup['Thursday']       ='Jeudi';
$uvlanglookup['Friday']         ='Vendredi';
$uvlanglookup['Original by']    ='Scénario original de ';
$uvlanglookup['Adapted by']     =' Adapté pour le modèle en ';
$uvlanglookup['Low']            = 'Faible';
$uvlanglookup['Medium']         = 'Moyen';
$uvlanglookup['High']           = 'Haut';
$uvlanglookup['Very high']      = 'Très élevé';
$uvlanglookup['Extreme']        = 'Extreme';
$uvlanglookup['green1']         = 'Faible danger des rayons UV du soleil pour la personne moyenne.';
$uvlanglookup['green2']         = 'Portez des lunettes de soleil les journées ensoleillées. Si vous brûlez facilement, couvrir et d\'utiliser un écran solaire.';
$uvlanglookup['yellow1']        = 'Risque modéré de dommages causés par l\'exposition au soleil sans protection.';
$uvlanglookup['yellow2']        = 'Prenez des précautions, telles que la dissimulation, si vous voulez être à l\'extérieur. Restez à l\'ombre près la mi-journée, quand le soleil est le plus fort.';
$uvlanglookup['orange1']        = 'Haut risque de préjudice de l\'exposition au soleil sans protection.';
$uvlanglookup['orange2']        = 'Portez des lunettes de soleil et utiliser un écran solaire SPF30 +. Couvrir le corps avec des vêtements de protection solaire et un chapeau à large bord. Réduire le temps au soleil de deux heures avant et trois heures après le midi solaire.';
$uvlanglookup['red1']           = 'Risque très élevé de dommages causés par l\'exposition au soleil sans protection.';
$uvlanglookup['red2']           = 'Prenez des précautions supplémentaires. Utilisez un écran solaire SPF30 +, une chemise, des lunettes de soleil et un chapeau. Ne pas rester au soleil trop longtemps. Si vous devez être à l\'extérieur d\'éviter le soleil de wo heures avant et trois heures après le midi solaire. ';
$uvlanglookup['cachewarn1']	= 'Impossible d\'enregistrer des données';
$uvlanglookup['cachewarn2']	= 'S\'il vous plaît assurez-vous que votre répertoire de cache existe et est accessible en écriture';
$uvlanglookup['UV forecast courtesy of and Copyright'] = 'UV prévisions courtoisie de droit d\'auteur et';
$uvlanglookup['Used with permission.'] = 'Utilisé avec permission.';
} # --------------------- French / Français texts ------- END OF -------
elseif ($lang == 'en') {
# ----------------------------------------- English  texts -------------
$uvlanglookup['UV Forecast']    ='UV Index Forecast';
$uvlanglookup['Saturday']       ='Saturday';
$uvlanglookup['Sunday']         ='Sunday';
$uvlanglookup['Monday']         ='Monday';
$uvlanglookup['Tuesday']        ='Tuesday';
$uvlanglookup['Wednesday']      ='Wednesday';
$uvlanglookup['Thursday']       ='Thursday';
$uvlanglookup['Friday']         ='Friday';
$uvlanglookup['Original by']    ='Original script by';
$uvlanglookup['Adapted by']     =' Adapted for the template by';
$uvlanglookup['Low']            = 'Low';
$uvlanglookup['Medium']         = 'Medium';
$uvlanglookup['High']           = 'High';
$uvlanglookup['Very high']      = 'Very high';
$uvlanglookup['Extreme']        = 'Extreme';
$uvlanglookup['green1']         = 'Low danger from the sun\'s UV rays for the average person. ';
$uvlanglookup['green2']         = 'Wear sunglasses on bright days. If you burn easily, cover up and use sunscreen.';
$uvlanglookup['yellow1']        = 'Moderate risk of harm from unprotected sun exposure.';
$uvlanglookup['yellow2']        = 'Take precautions, such as covering up, if you will be outside. Stay in shade near midday when the sun is strongest.';
$uvlanglookup['orange1']        = 'High risk of harm from unprotected sun exposure.';
$uvlanglookup['orange2']        = 'Wear sunglasses and use SPF30+ sunscreen. Cover the body with sun protective clothing and a wide-brim hat. Reduce time in the sun from two hours before to three hours after solar noon.';
$uvlanglookup['red1']           = 'Very high risk of harm from unprotected sun exposure.';
$uvlanglookup['red2']           = 'Take extra precautions. Use SPF30+ sunscreen, a shirt, sunglasses and a hat. Do not stay out in the sun for too long. If you have to be outside avoid the sun from wo hours before to three hours after solar noon. ';
$uvlanglookup['cachewarn1']	= 'Could not save data';
$uvlanglookup['cachewarn2']	= 'Please make sure your cache directory exists and is writable';
$uvlanglookup['UV forecast courtesy of and Copyright'] = 'UV forecast courtesy of and Copyright';
$uvlanglookup['Used with permission.'] = 'Used with permission.';
} # ------------------------------- English texts ------- END OF -------
elseif ($lang == 'de') {
# ----------------------------------------- German / Deutsche texts ----
$uvlanglookup['UV Forecast']    ='UV-Index Vorhersage';
$uvlanglookup['Saturday']       ='Samstag';
$uvlanglookup['Sunday']         ='Sonntag';
$uvlanglookup['Monday']         ='Montag';
$uvlanglookup['Tuesday']        ='Dienstag';
$uvlanglookup['Wednesday']      ='Mittwoch';
$uvlanglookup['Thursday']       ='Donnerstag';
$uvlanglookup['Friday']         ='Freitag';
$uvlanglookup['Original by']    ='Original-Skript von';
$uvlanglookup['Adapted by']     =' Für die "template" angepasst durch';
$uvlanglookup['Low']            = 'Niedrig';
$uvlanglookup['Medium']         = 'Mäßig';
$uvlanglookup['High']           = 'Hoch';
$uvlanglookup['Very high']      = 'Sehr hoch';
$uvlanglookup['Extreme']        = 'Extrem';
$uvlanglookup['green1']         = 'Geringer Gefahr von der Sonne für die durchschnittliche Person. ';
$uvlanglookup['green2']         = 'Tragen Sie eine Sonnenbrille an hellen Tagen. Wenn Sie leicht brennen ein Sonnenschutzmittel verwenden.';
$uvlanglookup['yellow1']        = 'Moderaten Risiko von Schäden durch ungeschützten Sonneneinstrahlung.';
$uvlanglookup['yellow2']        = 'Treffen Sie Vorkehrungen, wie Abdecken mit Kleidung  wenn Sie draußen sind. Bleiben Sie im Schatten in der Nähe von Mittag, wenn die Sonne am stärksten ist.';
$uvlanglookup['orange1']        = 'Hohes Risiko für einen Schaden von ungeschützten Sonneneinstrahlung.';
$uvlanglookup['orange2']        = 'Tragen Sie eine Sonnenbrille und Sonnencreme verwenden SPF30 +. Bedecken Sie den Körper mit Sonnenschutzkleidung und einer breiten Krempe. Verringern Sie die Zeit in der Sonne von zwei Stunden vor bis drei Stunden nach Solar Mittag.';
$uvlanglookup['red1']           = 'Sehr hohes Risiko für einen Schaden von ungeschützten Sonneneinstrahlung.';
$uvlanglookup['red2']           = 'Zusätzliche Vorsichtsmaßnahmen. Verwenden SPF30 + Sonnenschutz, ein T-Shirt, Sonnenbrille und einen Hut. Bleiben Sie nicht in der Sonne zu lange. Wenn Sie draußen zu sein wo die Sonne von Stunden zu vermeiden, bevor bis drei Stunden nach Solar Mittag. ';
$uvlanglookup['cachewarn1']	= 'Daten konnten nicht gespeichert werden';
$uvlanglookup['cachewarn2']	= 'Bitte stellen Sie sicher, dass Ihr Cache-Verzeichnis existiert und beschreibbar ist';
$uvlanglookup['UV forecast courtesy of and Copyright'] = 'UV-Vorhersagen mit freundlicher Genehmigung und Copyright von: ';
$uvlanglookup['Used with permission.'] = 'Verwendet mit Erlaubnis.';
} # -------------------------German / Deutsche texts ---- END OF -------
else {
# ----------------------------------------- New  / unknown texts -------
$uvlanglookup['UV Forecast']    ='UV Index Forecast';
$uvlanglookup['Saturday']       ='Saturday';
$uvlanglookup['Sunday']         ='Sunday';
$uvlanglookup['Monday']         ='Monday';
$uvlanglookup['Tuesday']        ='Tuesday';
$uvlanglookup['Wednesday']      ='Wednesday';
$uvlanglookup['Thursday']       ='Thursday';
$uvlanglookup['Friday']         ='Friday';
$uvlanglookup['Saturday']       ='Saturday';
$uvlanglookup['Original by']    ='Original script by';
$uvlanglookup['Adapted by']     =' Adapted for the template by';
$uvlanglookup['Low']            = 'Low';
$uvlanglookup['Medium']         = 'Medium';
$uvlanglookup['High']           = 'High';
$uvlanglookup['Very high']      = 'Very high';
$uvlanglookup['Extreme']        = 'Extreme';
$uvlanglookup['green1']         = 'Low danger from the sun\'s UV rays for the average person. ';
$uvlanglookup['green2']         = 'Wear sunglasses on bright days. If you burn easily, cover up and use sunscreen.';
$uvlanglookup['yellow1']        = 'Moderate risk of harm from unprotected sun exposure.';
$uvlanglookup['yellow2']        = 'Take precautions, such as covering up, if you will be outside. Stay in shade near midday when the sun is strongest.';
$uvlanglookup['orange1']        = 'High risk of harm from unprotected sun exposure.';
$uvlanglookup['orange2']        = 'Wear sunglasses and use SPF30+ sunscreen. Cover the body with sun protective clothing and a wide-brim hat. Reduce time in the sun from two hours before to three hours after solar noon.';
$uvlanglookup['red1']           = 'Very high risk of harm from unprotected sun exposure.';
$uvlanglookup['red2']           = 'Take extra precautions. Use SPF30+ sunscreen, a shirt, sunglasses and a hat. Do not stay out in the sun for too long. If you have to be outside avoid the sun from wo hours before to three hours after solar noon. ';
$uvlanglookup['cachewarn1']	= 'Could not save data';
$uvlanglookup['cachewarn2']	= 'Please make sure your cache directory exists and is writable';
$uvlanglookup['UV forecast courtesy of and Copyright'] = 'UV forecast courtesy of and Copyright';
$uvlanglookup['Used with permission.'] = 'Used with permission.';
} # --------------------- New  / unknown texts ---------- END OF -------
#
        if ($charset <> 'UTF-8') {
                foreach ($uvlanglookup as $key => $translation) { 
                        $translation            = iconv('UTF-8', $charset, $translation);
                        $uvlanglookup[$key]      = $translation;
                }  // end foreach entry in translation array              
        } // eo not utf-8
        return;
} // eof uvloadlangstr
# ----------------------  version history
# 3.20 2015-07-19 release 2.8 version 
