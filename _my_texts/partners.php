<?php
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;            # display source of script if requested so
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
$pageName	= 'partners.php';
$pageVersion	= '3.20 2015-07-10';
#-----------------------------------------------------------------------------------------
# 3.20 2015-07-10 release 2.8 version
#-----------------------------------------------------------------------------------------
if (!isset($SITE)){echo "<h3>invalid call to script $pageName</h3>";exit;}
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;}
ws_message ('<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile].' -->');
#
if (!isset($SITE['pages']['ewnMembersBody2']) ) {$SITE['pages']['ewnMembersBody2'] = 'index.php';}
#-----------------------------------------------------------------------------------------
// these are the parties we share our weatherinfo with
//
//$name = the key for the table, can be anything but the namesshould all be different
//$arrPartners[$name]['name']		= the short name of the partner, which is printed as the first word of the describing text
//$arrPartners[$name]['id']		= your id to get your info at their site. this id should be inserted autiomatically in ['href'] and ['frame'] 
//$arrPartners[$name]['sideBar']	= if true the info is displayed on the sidebar, otherwise only on the partnershipspage
//					        preferably use the id's from $SITE. when you do not have an id insert 'false'; 
//$arrPartners[$name]['href'] 		= the http link towards your page on their site, if it exists, otherwhise '';
//$arrPartners[$name]['target']		= '_blank' for a complete page  , '_self' for a frame

//$arrPartners[$name]['frame'] 		= the http link if its possible to get your info in a frame on our own site.  
//$arrPartners[$name]['webLink']	= the general (home) http link for the partners site
//$arrPartners[$name]['webImg']		= a reference towards a nice graphic of their name/site
//$arrPartners[$name]['webImgSmall']	= a reference towards a nice graphic of their name/site
//$arrPartners[$name]['webAlt']		= default: ['name'] , but if you can put som other info also	
//$arrPartners[$name]['text']		= either empty (we look in a seperate language textfile also) or some text describing the parners business
//
# Set the partners you want in the sequence you want them to appear.
# Set a comment mark for all partners you do not want
$arr_part_OK[]     = 'hwa';
$arr_part_OK[]     = 'wow';
$arr_part_OK[]     = 'ewn';
$arr_part_OK[]     = 'wcloud';
$arr_part_OK[]     = 'wu';
$arr_part_OK[]     = 'meso';
$arr_part_OK[]     = 'awekas';
$arr_part_OK[]     = 'weatherlink';
$arr_part_OK[]     = 'cwop';
$arr_part_OK[]     = 'pws';
$arr_part_OK[]     = 'wp24';
$arr_part_OK[]     = 'anWe';
#$arr_part_OK[]     = 'uswxgroup';
#$arr_part_OK[]     = 'new1';
#
$name = 'new1';
if (in_array ($name,$arr_part_OK) ) {
        $arrPartners[$name]['name']	= 'name of the organization';
        $arrPartners[$name]['id']	= $SITE['id from settings'];
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= 'link of your page on their website ';
#       $arrPartners[$name]['frame'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;  
        $arrPartners[$name]['target']	= '_blank';                     # = '_self';  // if you want the data in a frame              
        $arrPartners[$name]['webLink']	= 'link to their start page';
        $arrPartners[$name]['webImg']	= 'link to a (small) image / logo on their site';
#       $arrPartners[$name]['webImgSmall']	= 'img/xyz.jpg';        // optional a local copy of their logo
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];  // leave as is	
        $arrPartners[$name]['text']	= '';                           // you can add text here if needed in one language, AND - OR  use the partners_??.txt where ?? is the language code
}
#
$name = 'wcloud';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['wcloudMember'] == true) {$id = $SITE['wcloudID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'WeatherCloud';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['frame'] 	= 'http://app.weathercloud.net/'.$arrPartners[$name]['id'].'#current';// http://app.weathercloud.net/d1686817653#profile
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;  
        $arrPartners[$name]['target']	= '_self';                     # = '_self';  // if you want the data in a frame              
        $arrPartners[$name]['webLink']	= 'http://weathercloud.net';
        $arrPartners[$name]['webImg']	= 'img/weathercloud.jpg';
 #       $arrPartners[$name]['webImgSmall']	= 'img/xyz.jpg';        // optional a local copy of their logo
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];  // leave as is	
        $arrPartners[$name]['text']	= '';                           // you can add text here if needed in one language, AND - OR  use the partners_??.txt where ?? is the language code
}

$name = 'hwa';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['hwaMember'] == true) {$id = $SITE['hwaID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'Het Weer Aktueel';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['frame'] 	= 'http://www.hetweeractueel.nl/weer/'.$arrPartners[$name]['id'].'/actueel/';
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;  
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.hetweeractueel.nl/';
        $arrPartners[$name]['webImg']	= 'http://www.hetweeractueel.nl/images/hetweeractueelpromotie.gif';
        $arrPartners[$name]['webImgSmall']	= 'img/hwa.jpg';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'wow';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['wowMember'] == true) {$id = $SITE['wowID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'UK Metoffice';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;  
#        $arrPartners[$name]['frame'] 	= 'http://wow.metoffice.gov.uk/sitehandlerservlet?requestedAction=READ&amp;siteID='.$arrPartners[$name]['id']; 
        $arrPartners[$name]['frame'] 	= 'http://wow.metoffice.gov.uk/weather/view?siteID='.$arrPartners[$name]['id'];
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://wow.metoffice.gov.uk/';
        $arrPartners[$name]['webImg']	= 'img/wow.png';
        $arrPartners[$name]['webImgSmall']	= 'img/metoffice.jpg';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'ewn';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['ewnMember'] == true) {$id = $SITE['ewnID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'European Weather Network';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= $SITE['pages']['ewnMembersBody2'].'&amp;lang='.$lang.$extraP.$skiptopText;
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.europeanweathernetwork.eu/';
        $arrPartners[$name]['webImg']	= 'wsEwn/ewn.png';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'meso';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['mesonetMember'] == true) {$id = $SITE['mesoID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= langtransstr($SITE['mesoName']);      # do not change here, change in your settings file
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        if      (isset ($SITE['pages']['wsRegionalMap'])  )    {$wn_link = $SITE['pages']['wsRegionalMap'];} 
        elseif  (isset ($SITE['pages']['wnSpecificInc'])  )     {$wn_link = $SITE['pages']['wnSpecificInc'];}
        elseif  (isset ($SITE['pages']['wnGeneralFrame'])  )    {$wn_link = $SITE['pages']['wnGeneralFrame'];} 
        else    {$wn_link = 'index.php';} 
        $arrPartners[$name]['href'] 	= $wn_link.'&amp;lang='.$lang.$extraP.$skiptopText;
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= $SITE['mesoLink'];
        $arrPartners[$name]['webImg']	= 'img/_mesoLogo.png';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'awekas';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['awekasMember'] == true) {$id = $SITE['awekasID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'AWEKAS';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $lg=substr($SITE['lang'],0,1);
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText; 
        if ($SITE['uomTemp'] == '&deg;F') {$type = 'f';} else {$type = 'c';}        
        $arrPartners[$name]['frame'] 	= 'http://www.awekas.at/premium/insert.php?id='.$arrPartners[$name]['id'].'&amp;lg='.$lg.'&amp;eh='.$type.'&amp;tz=0&amp;header=0';
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.awekas.at';
        $arrPartners[$name]['webImg']	= 'http://www.awekas.at/images/awekas-bm1-nl.gif';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'weatherlink';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['wl_comMember'] == true) {$id = $SITE['weatherlinkID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'WeatherlinkIP';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;
        if ($SITE['uomTemp'] == '&deg;F') {$type = '&amp;type=2';} else {$type = '&amp;type=1';}
        $arrPartners[$name]['frame'] 	= 'http://www.weatherlink.com/user/'.$arrPartners[$name]['id'].'/index.php?view=summary&amp;headers=0'.$type; // 
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.weatherlink.com/findoutmore.php';
        $arrPartners[$name]['webImg']	= 'http://www.weatherlink.com/images/wl_top.png';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'wu';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['wuMember'] == true) {$id = $SITE['wuID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'Weather Underground';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;
        $arrPartners[$name]['frame'] 	= 'http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID='.$arrPartners[$name]['id'];
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.wunderground.com';
        $arrPartners[$name]['webImg']	= './img/wuLogoNon.png';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'cwop';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['cwopMember'] == true) {$id = $SITE['cwopID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'Citizen Weather Observer Program (CWOP) ';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['frame'] 	= 'http://weather.gladstonefamily.net/site/'.$arrPartners[$name]['id'];
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s='.$name.'&amp;lang='.$lang.$extraP.$skiptopText;
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.findu.com/citizenweather/';
        $arrPartners[$name]['webImg']	= 'http://www.wxqa.com/archive/cwp_logo.gif';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'pws';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['pwsMember'] == true) {$id = $SITE['pwsID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'HAMweather, WeatherForYou, PWS Weather';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s=pws'.'&amp;lang='.$lang.$extraP.$skiptopText;
        $arrPartners[$name]['frame'] 	= 'http://www.pwsweather.com/obs/'.$arrPartners[$name]['id'].'.html';   
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.pwsweather.com/';
        $arrPartners[$name]['webImg']	= 'http://www.pwsweather.com/images/header_left.png';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'anWe';
if (in_array ($name,$arr_part_OK) ) {
	if ($SITE['anWeMember'] == true) {$id = $SITE['anWeID'];} else {$id =  false;}
        $arrPartners[$name]['name']	= 'Anything Weather';
        $arrPartners[$name]['id']	= $id;
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= $SITE['pages']['wsPartners'].'&amp;s=anWe'.'&amp;lang='.$lang.$extraP.$skiptopText;
        $arrPartners[$name]['frame'] 	= 'http://www.anythingweather.com/current.aspx?id='.$arrPartners[$name]['id'];
        $arrPartners[$name]['target']	= '_self';
        $arrPartners[$name]['webLink']	= 'http://www.anythingweather.com/current.aspx?id='.$arrPartners[$name]['id'];
        $arrPartners[$name]['webImg']	= 'http://www.anythingweather.com/images/logo_anything_weather.gif';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'wp24';
if (in_array ($name,$arr_part_OK) ) {
        $arrPartners[$name]['name']	= 'Wetterpage 24';
        $arrPartners[$name]['id']	= $SITE['wp24ID']; # do not change here, change in your settings file
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= 'http://www.wetterpage24.de/';
        $arrPartners[$name]['target']	= '_blank';
        $arrPartners[$name]['webLink']	= 'http://www.wetterpage24.de/';
        $arrPartners[$name]['webImg']	= './img/wp24.jpg';
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
$name = 'uswxgroup';
if (in_array ($name,$arr_part_OK) ) {
        $arrPartners[$name]['name']	= 'United States Weather Group';
        $arrPartners[$name]['id']	= $SITE['uswg']; # do not change here, change in your settings file
        $arrPartners[$name]['sideBar']	= true;
        $arrPartners[$name]['href'] 	= 'http://www.uswxgroup.org';
        $arrPartners[$name]['target']	= '_blank';
        $arrPartners[$name]['webLink']	= $arrPartners[$name]['href'] ;
        $arrPartners[$name]['webImg']	= 'http://uswxgroup.org/rankings/button.php?u='.$arrPartners[$name]['id']; // #####
        $arrPartners[$name]['webAlt']	= $arrPartners[$name]['name'];	
        $arrPartners[$name]['text']	= '';
}
# ----------------------  version history
# 3.20 2015-07-10 release 2.8 version 
